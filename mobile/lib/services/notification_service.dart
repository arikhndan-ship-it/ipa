import 'dart:async';
import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:shared_preferences/shared_preferences.dart';

/// Callback invoked when the user taps a notification.
/// [type] is the notification type (article, comment, etc.)
/// [notifiableId] is the ID of the related entity
/// [url] is an optional web URL to open
typedef NotificationTapCallback = void Function(String type, int? notifiableId, String? url);

class NotificationService {
  static final NotificationService _instance = NotificationService._internal();
  factory NotificationService() => _instance;
  NotificationService._internal();

  static const String _prefsKey = 'last_notification_id';

  final FlutterLocalNotificationsPlugin _localNotifications =
      FlutterLocalNotificationsPlugin();

  Timer? _pollingTimer;
  int _lastNotificationId = 0;
  bool _hasBaseline = false;
  String _currentLocale = 'ckb';
  bool _initialized = false;
  NotificationTapCallback? _onTapCallback;

  /// Set the callback for notification taps
  void setOnTapCallback(NotificationTapCallback callback) {
    _onTapCallback = callback;
  }

  Future<void> init() async {
    if (_initialized) return;

    // Load saved notification ID from persistent storage
    try {
      final prefs = await SharedPreferences.getInstance();
      _lastNotificationId = prefs.getInt(_prefsKey) ?? 0;
      // If we have a saved ID, set baseline to true so old notifications
      // don't re-appear on every app launch
      _hasBaseline = _lastNotificationId > 0;
      debugPrint('Loaded last notification ID: $_lastNotificationId (hasBaseline: $_hasBaseline)');
    } catch (e) {
      debugPrint('Failed to load preferences: $e');
    }

    const androidSettings = AndroidInitializationSettings('@mipmap/ic_launcher');
    const iosSettings = DarwinInitializationSettings(
      requestAlertPermission: true,
      requestBadgePermission: true,
      requestSoundPermission: true,
    );

    const initSettings = InitializationSettings(
      android: androidSettings,
      iOS: iosSettings,
    );

    await _localNotifications.initialize(
      initSettings,
      onDidReceiveNotificationResponse: _onNotificationTap,
    );

    _initialized = true;
  }

  void setLocale(String locale) {
    _currentLocale = locale;
  }

  void _onNotificationTap(NotificationResponse response) {
    debugPrint('Notification tapped: ${response.payload}');
    if (response.payload == null || response.payload!.isEmpty) return;

    try {
      final data = json.decode(response.payload!);
      final type = data['type'] as String? ?? '';
      final notifiableId = data['notifiable_id'] as int?;
      final action = data['action'] as String? ?? '';
      final url = data['url'] as String?;

      _onTapCallback?.call(type, notifiableId, url);
    } catch (e) {
      debugPrint('Error parsing notification payload: $e');
    }
  }

  void startPolling() {
    _pollingTimer?.cancel();
    _fetchNotifications();
    _pollingTimer = Timer.periodic(const Duration(seconds: 30), (_) {
      _fetchNotifications();
    });
  }

  void stopPolling() {
    _pollingTimer?.cancel();
    _pollingTimer = null;
  }

  /// Save the highest notification ID so we don't re-show old ones on next launch
  Future<void> _saveLastNotificationId(int id) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      await prefs.setInt(_prefsKey, id);
    } catch (e) {
      debugPrint('Failed to save notification ID: $e');
    }
  }

  Future<void> _fetchNotifications() async {
    try {
      final response = await http
          .get(
            Uri.parse('https://khandantelegraph.news/api/v1/notifications'),
            headers: {'Accept-Language': _currentLocale},
          )
          .timeout(const Duration(seconds: 15));

      if (response.statusCode != 200) return;

      final data = json.decode(response.body);
      final List notifications = data['data'] ?? [];
      if (notifications.isEmpty) return;

      // Find the highest notification ID
      int maxId = 0;
      for (final n in notifications) {
        final id = n['id'] as int;
        if (id > maxId) maxId = id;
      }

      // Only show notifications NEWER than our last known ID
      // This works for both first launch and subsequent polls
      final now = DateTime.now().millisecondsSinceEpoch ~/ 1000;

      for (final n in notifications) {
        final id = n['id'] as int;
        if (id <= _lastNotificationId) continue; // Already seen, skip

        final title = n['title'] as String? ?? '';
        final body = n['body'] as String? ?? '';
        final notifType = n['type'] as String? ?? '';
        final notifiableId = n['notifiable_id'] as int?;
        final action = n['action'] as String? ?? '';
        final url = n['url'] as String?;

        // Skip empty titles
        if (title.isEmpty) continue;

        // On the VERY first launch ever (no saved ID),
        // only show notifications from the last 24 hours
        if (!_hasBaseline && _lastNotificationId == 0) {
          final createdAt = n['created_at'] as String?;
          if (createdAt != null) {
            try {
              final created = DateTime.parse(createdAt);
              if (now - created.millisecondsSinceEpoch ~/ 1000 > 86400) {
                continue; // Older than 24 hours, skip
              }
            } catch (_) {
              // If we can't parse the date, still show it
            }
          }
        }

        _showLocalNotification(
          id,
          title,
          body,
          type: notifType,
          action: action,
          notifiableId: notifiableId,
          url: url,
        );
      }

      // Update last seen ID and persist it
      if (maxId > _lastNotificationId) {
        _lastNotificationId = maxId;
        _saveLastNotificationId(maxId);
      }

      _hasBaseline = true;
    } catch (e) {
      debugPrint('Notification polling error: $e');
    }
  }

  Future<void> _showLocalNotification(
    int id,
    String title,
    String body, {
    String type = '',
    String action = '',
    int? notifiableId,
    String? url,
  }) async {
    // Build payload JSON for navigation on tap
    final payload = json.encode({
      'notification_id': id,
      'type': type,
      'action': action,
      'notifiable_id': notifiableId,
      'url': url,
    });

    const androidDetails = AndroidNotificationDetails(
      'khandan_notifications',
      'Khandan Notifications',
      channelDescription: 'Notifications from Khandan Telegraph',
      importance: Importance.high,
      priority: Priority.high,
      showWhen: true,
      enableVibration: true,
      playSound: true,
    );

    const iosDetails = DarwinNotificationDetails(
      presentAlert: true,
      presentBadge: true,
      presentSound: true,
    );

    const details = NotificationDetails(
      android: androidDetails,
      iOS: iosDetails,
    );

    await _localNotifications.show(
      id,
      title,
      body,
      details,
      payload: payload,
    );
  }

  /// Register the device FCM token with the backend
  Future<bool> registerDeviceToken(String token, String platform) async {
    try {
      final response = await http
          .post(
            Uri.parse(
                'https://khandantelegraph.news/api/v1/devices/register'),
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
            },
            body: json.encode({
              'token': token,
              'platform': platform,
              'locale': _currentLocale,
            }),
          )
          .timeout(const Duration(seconds: 10));

      return response.statusCode == 200;
    } catch (e) {
      debugPrint('Device registration error: $e');
      return false;
    }
  }

  /// Unregister the device token
  Future<bool> unregisterDeviceToken(String token) async {
    try {
      final response = await http
          .post(
            Uri.parse(
                'https://khandantelegraph.news/api/v1/devices/unregister'),
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
            },
            body: json.encode({
              'token': token,
            }),
          )
          .timeout(const Duration(seconds: 10));

      return response.statusCode == 200;
    } catch (e) {
      debugPrint('Device unregistration error: $e');
      return false;
    }
  }
}

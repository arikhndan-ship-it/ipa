import 'package:flutter/material.dart';
import 'package:flutter_localizations/flutter_localizations.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'app/app.dart';
import 'app/theme.dart';
import 'services/notification_service.dart';
import 'services/api_service.dart';
import 'screens/article_detail_screen.dart';
import 'screens/notifications_screen.dart';
import 'package:khandan_app/l10n/app_localizations.dart';

/// Global navigator key for navigation from outside the widget tree
final GlobalKey<NavigatorState> navigatorKey = GlobalKey<NavigatorState>();

/// Handle background FCM message (app killed or in background)
@pragma('vm:entry-point')
Future<void> _firebaseMessagingBackgroundHandler(RemoteMessage message) async {
  await Firebase.initializeApp();
  debugPrint('Background FCM message: ${message.messageId}');
}

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  // Initialize Firebase (gracefully falls back if no config)
  try {
    await Firebase.initializeApp();
    // Request notification permissions
    final messaging = FirebaseMessaging.instance;
    await messaging.requestPermission(
      alert: true,
      badge: true,
      sound: true,
    );

    // Set up background message handler
    FirebaseMessaging.onBackgroundMessage(_firebaseMessagingBackgroundHandler);

    // Get and register FCM token
    final fcmToken = await messaging.getToken();
    if (fcmToken != null) {
      await NotificationService().registerDeviceToken(fcmToken, 'android');
      debugPrint('FCM token registered: ${fcmToken.substring(0, 20)}...');
    }

    // Listen for token refresh
    messaging.onTokenRefresh.listen((newToken) {
      NotificationService().registerDeviceToken(newToken, 'android');
    });

    // Handle FCM messages when app is in foreground
    FirebaseMessaging.onMessage.listen((RemoteMessage message) {
      debugPrint('Foreground FCM message: ${message.notification?.title}');
      // The polling service will also pick these up
    });

    // Handle FCM tap when app was in background
    FirebaseMessaging.onMessageOpenedApp.listen(_handleFcmMessage);
  } catch (e) {
    debugPrint('Firebase init skipped (no project config): $e');
  }

  await NotificationService().init();
  runApp(const KhandanApp());
}

/// Handle FCM message tap - navigate to article
void _handleFcmMessage(RemoteMessage message) {
  final data = message.data;
  final type = data['type'] ?? '';
  final notifiableIdStr = data['article_id'] ?? data['notifiable_id'] ?? '';
  final notifiableId = int.tryParse(notifiableIdStr.toString());

  if (type == 'article' && notifiableId != null) {
    _navigateToArticle(notifiableId);
  }
}

/// Navigate to article detail screen
void _navigateToArticle(int articleId, {int retries = 5}) {
  final context = navigatorKey.currentContext;
  if (context == null) {
    if (retries > 0) {
      Future.delayed(const Duration(milliseconds: 500), () {
        _navigateToArticle(articleId, retries: retries - 1);
      });
    }
    return;
  }

  ApiService.setLocale(
    Localizations.localeOf(context).languageCode,
  );

  ApiService().getArticle(articleId).then((article) {
    Navigator.of(context).push(
      MaterialPageRoute(
        builder: (_) => ArticleDetailScreen(article: article),
      ),
    );
  }).catchError((e) {
    debugPrint('Failed to load article for notification: $e');
  });
}

class KhandanApp extends StatefulWidget {
  const KhandanApp({super.key});

  @override
  State<KhandanApp> createState() => KhandanAppState();

  static KhandanAppState? of(BuildContext context) {
    return context.findAncestorStateOfType<KhandanAppState>();
  }
}

class KhandanAppState extends State<KhandanApp> with WidgetsBindingObserver {
  Locale _locale = const Locale('ckb');

  void setLocale(Locale locale) {
    NotificationService().setLocale(locale.languageCode);
    ApiService.setLocale(locale.languageCode);
    NotificationService().startPolling();
    setState(() => _locale = locale);
  }

  /// Handle notification tap - navigate to article or open notifications list
  void _handleNotificationTap(String type, int? notifiableId, String? url) {
    debugPrint('Notification tapped: type=$type, notifiableId=$notifiableId, url=$url');
    if (type == 'article' && notifiableId != null) {
      _navigateToArticle(notifiableId);
    } else {
      // For any other notification type, go to the in-app notifications list
      _navigateToNotifications();
    }
  }

  /// Navigate to the in-app notifications screen
  void _navigateToNotifications({int retries = 5}) {
    final context = navigatorKey.currentContext;
    if (context == null) {
      if (retries > 0) {
        Future.delayed(const Duration(milliseconds: 500), () {
          _navigateToNotifications(retries: retries - 1);
        });
      }
      return;
    }
    // Pop to root first, then push the notifications screen
    Navigator.of(context).popUntil((route) => route.isFirst);
    Navigator.of(context).push(
      MaterialPageRoute(
        builder: (_) => const NotificationsScreen(),
      ),
    );
  }

  /// Navigate to home screen safely
  void _ensureHome({int retries = 3}) {
    final context = navigatorKey.currentContext;
    if (context == null) {
      if (retries > 0) {
        Future.delayed(const Duration(milliseconds: 500), () {
          _ensureHome(retries: retries - 1);
        });
      }
      return;
    }
    Navigator.of(context).popUntil((route) => route.isFirst);
  }

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addObserver(this);

    NotificationService().setLocale(_locale.languageCode);
    ApiService.setLocale(_locale.languageCode);

    // Set notification tap callback
    NotificationService().setOnTapCallback(_handleNotificationTap);

    // Start polling after a short delay
    Future.delayed(const Duration(seconds: 3), () {
      NotificationService().startPolling();
    });
  }

  @override
  void dispose() {
    WidgetsBinding.instance.removeObserver(this);
    NotificationService().stopPolling();
    super.dispose();
  }

  @override
  void didChangeAppLifecycleState(AppLifecycleState state) {
    if (state == AppLifecycleState.resumed) {
      // Refresh notifications when app comes to foreground
      NotificationService().startPolling();
    }
    // Don't stop polling on pause - keep it running in background
  }

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Khandan | خەندان',
      debugShowCheckedModeBanner: false,
      navigatorKey: navigatorKey,
      locale: _locale,
      theme: AppTheme.theme,
      home: const App(),
      scrollBehavior: const _NoOverscrollBehavior(),
      localizationsDelegates: const [
        AppLocalizations.delegate,
        GlobalMaterialLocalizations.delegate,
        GlobalWidgetsLocalizations.delegate,
        GlobalCupertinoLocalizations.delegate,
      ],
      supportedLocales: const [
        Locale('en'),
        Locale('ckb'),
      ],
      localeResolutionCallback: (locale, supportedLocales) {
        for (var supportedLocale in supportedLocales) {
          if (supportedLocale.languageCode == locale?.languageCode) {
            return supportedLocale;
          }
        }
        return const Locale('ckb');
      },
    );
  }
}

/// Prevents overscroll bounce/glow on all platforms
class _NoOverscrollBehavior extends ScrollBehavior {
  const _NoOverscrollBehavior();

  @override
  Widget buildOverscrollIndicator(
      BuildContext context, Widget child, ScrollableDetails details) {
    return child;
  }

  @override
  ScrollPhysics getScrollPhysics(BuildContext context) {
    return const ClampingScrollPhysics();
  }
}

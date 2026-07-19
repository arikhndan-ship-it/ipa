import '../services/api_service.dart';

class Journalist {
  final int id;
  final String name;
  final String? bio;
  final String? image;
  final int sortOrder;
  final bool isActive;
  final int? userId;

  Journalist({
    required this.id,
    required this.name,
    this.bio,
    this.image,
    this.sortOrder = 0,
    this.isActive = true,
    this.userId,
  });

  factory Journalist.fromJson(Map<String, dynamic> json) {
    return Journalist(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      bio: json['bio'],
      image: json['image_url'] as String? ?? ApiService.resolveImage(json['image']),
      sortOrder: json['sort_order'] ?? 0,
      isActive: json['is_active'] ?? true,
      userId: json['user_id'] as int?,
    );
  }
}

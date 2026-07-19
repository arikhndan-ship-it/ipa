import '../services/api_service.dart';

class Article {
  final int id;
  final String slug;
  final String title;
  final String body;
  final String? excerpt;
  final String? image;
  final String status;
  final bool isFeatured;
  final bool isBreaking;
  final int viewCount;
  final int? authorId;
  final String? authorName;
  final String? authorImage;
  final String? categoryName;
  final String? publishedAt;

  Article({
    required this.id,
    required this.slug,
    required this.title,
    required this.body,
    this.excerpt,
    this.image,
    required this.status,
    required this.isFeatured,
    required this.isBreaking,
    required this.viewCount,
    this.authorId,
    this.authorName,
    this.authorImage,
    this.categoryName,
    this.publishedAt,
  });

  /// Returns the full image URL (resolved from relative if needed)
  String? get imageUrl => ApiService.resolveImage(image);

  factory Article.fromJson(Map<String, dynamic> json) {
    final title = json['title'] as String? ?? '';
    final body = json['body'] as String? ?? '';
    final excerpt = json['excerpt'] as String?;

    // Extract author info from the nested author object or flat field
    int? authorId;
    String? authorName;
    if (json['author'] is Map<String, dynamic>) {
      final author = json['author'] as Map<String, dynamic>;
      authorId = author['id'] as int?;
      authorName = author['name'] as String?;
    }

    return Article(
      id: json['id'] as int,
      slug: json['slug'] as String? ?? '',
      title: title,
      body: body,
      excerpt: excerpt,
      image: json['featured_image'] as String? ??
          json['og_image'] as String?,
      status: json['status'] as String? ?? 'draft',
      isFeatured: json['is_featured'] as bool? ?? false,
      isBreaking: json['is_breaking'] as bool? ?? false,
      viewCount: json['view_count'] as int? ?? 0,
      authorId: authorId ?? json['author_id'] as int?,
      authorName: authorName ?? json['author_name'] as String?,
      authorImage: json['author_image'] as String?,
      categoryName: json['category_name'] as String?,
      publishedAt: json['published_date'] as String? ??
          json['published_at'] as String?,
    );
  }
}

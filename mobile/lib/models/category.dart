class Category {
  final int id;
  final String slug;
  final String name;
  final String? description;
  final int articlesCount;

  Category({
    required this.id,
    required this.slug,
    required this.name,
    this.description,
    this.articlesCount = 0,
  });

  factory Category.fromJson(Map<String, dynamic> json) {
    // Read from top-level localized fields returned by backend accessors
    final name = json['name'] as String? ?? json['slug'] ?? '';

    return Category(
      id: json['id'] as int,
      slug: json['slug'] as String? ?? '',
      name: name,
      description: json['description'] as String?,
      articlesCount: json['articles_count'] as int? ?? 0,
    );
  }
}

class Comment {
  final int id;
  final String authorName;
  final String body;
  final String createdAt;
  final bool isApproved;

  Comment({
    required this.id,
    required this.authorName,
    required this.body,
    required this.createdAt,
    required this.isApproved,
  });

  factory Comment.fromJson(Map<String, dynamic> json) {
    return Comment(
      id: json['id'] as int,
      authorName: json['author_name'] as String? ?? 'Anonymous',
      body: json['body'] as String? ?? '',
      createdAt: json['created_at'] as String? ?? '',
      isApproved: json['is_approved'] as bool? ?? false,
    );
  }
}

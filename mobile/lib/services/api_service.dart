import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/article.dart';
import '../models/category.dart';
import '../models/comment.dart';
import '../models/journalist.dart';

class ApiService {
  static const String baseUrl = 'https://khandantelegraph.news/api/v1';

  static String _currentLocale = 'ckb';

  static String get currentLocale => _currentLocale;

  static void setLocale(String locale) {
    _currentLocale = locale;
  }

  /// Converts relative image paths to full URLs
  static String? resolveImage(String? imagePath) {
    if (imagePath == null || imagePath.isEmpty) return null;
    if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
      return imagePath;
    }
    // Remove leading slash and prepend base
    final clean = imagePath.startsWith('/') ? imagePath.substring(1) : imagePath;
    // If path already starts with storage/ use it directly
    if (clean.startsWith('storage/')) {
      return 'https://khandantelegraph.news/$clean';
    }
    // Otherwise prepend storage/ (most images are in the storage symlink)
    return 'https://khandantelegraph.news/storage/$clean';
  }

  Future<List<Article>> getBreakingArticles() async {
    final params = <String, String>{
      'breaking': 'true',
      'per_page': '20',
      'locale': _currentLocale,
    };
    final uri =
        Uri.parse('$baseUrl/articles').replace(queryParameters: params);
    final response = await http.get(
      uri,
      headers: {'Accept-Language': _currentLocale},
    );
    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      if (data['data'] != null) {
        return (data['data'] as List)
            .map((json) => Article.fromJson(json))
            .toList();
      }
      return [];
    }
    throw Exception('Failed to load breaking news');
  }

  Future<List<Article>> getArticles({
    int page = 1,
    int? categoryId,
    int? authorId,
    bool? featured,
  }) async {
    final params = <String, String>{
      'page': page.toString(),
      'locale': _currentLocale,
    };
    if (categoryId != null) params['category_id'] = categoryId.toString();
    if (authorId != null) params['author_id'] = authorId.toString();
    if (featured != null) params['featured'] = featured.toString();

    final uri =
        Uri.parse('$baseUrl/articles').replace(queryParameters: params);
    final response = await http.get(
      uri,
      headers: {'Accept-Language': _currentLocale},
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      if (data['data'] != null) {
        return (data['data'] as List)
            .map((json) => Article.fromJson(json))
            .toList();
      }
      return [];
    }
    throw Exception('Failed to load articles');
  }

  Future<Article> getArticle(int id) async {
    final response = await http.get(
      Uri.parse('$baseUrl/articles/$id'),
      headers: {'Accept-Language': _currentLocale},
    );
    if (response.statusCode == 200) {
      return Article.fromJson(json.decode(response.body));
    }
    throw Exception('Failed to load article');
  }

  Future<List<Category>> getCategories() async {
    final response = await http.get(
      Uri.parse('$baseUrl/categories'),
      headers: {'Accept-Language': _currentLocale},
    );
    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      if (data is List) {
        return data.map((json) => Category.fromJson(json)).toList();
      }
      if (data['data'] != null) {
        return (data['data'] as List)
            .map((json) => Category.fromJson(json))
            .toList();
      }
      return [];
    }
    throw Exception('Failed to load categories');
  }

  Future<Map<String, dynamic>> search(String query) async {
    final params = <String, String>{
      'q': query,
      'locale': _currentLocale,
    };
    final uri =
        Uri.parse('$baseUrl/search').replace(queryParameters: params);
    final response = await http.get(
      uri,
      headers: {'Accept-Language': _currentLocale},
    );
    if (response.statusCode == 200) {
      return json.decode(response.body);
    }
    throw Exception('Search failed');
  }

  Future<List<Journalist>> getJournalists() async {
    final response = await http.get(
      Uri.parse('$baseUrl/journalists'),
      headers: {'Accept-Language': _currentLocale},
    );
    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      if (data is List) {
        return data.map((json) => Journalist.fromJson(json)).toList();
      }
      if (data['data'] != null) {
        return (data['data'] as List)
            .map((json) => Journalist.fromJson(json))
            .toList();
      }
      return [];
    }
    throw Exception('Failed to load journalists');
  }

  /// Fetch author profile info by user ID
  Future<Map<String, dynamic>> getAuthor(int userId) async {
    final response = await http.get(
      Uri.parse('$baseUrl/authors/$userId'),
      headers: {'Accept-Language': _currentLocale},
    );
    if (response.statusCode == 200) {
      return json.decode(response.body);
    }
    throw Exception('Failed to load author info');
  }

  Future<List<Comment>> getComments(int articleId) async {
    final response = await http.get(
      Uri.parse('$baseUrl/comments/$articleId'),
      headers: {'Accept-Language': _currentLocale},
    );
    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      if (data is List) {
        return data.map((json) => Comment.fromJson(json)).toList();
      }
      if (data['data'] != null) {
        return (data['data'] as List)
            .map((json) => Comment.fromJson(json))
            .toList();
      }
      return [];
    }
    throw Exception('Failed to load comments');
  }

  Future<void> postComment(
    int articleId,
    String name,
    String email,
    String body,
  ) async {
    final response = await http.post(
      Uri.parse('$baseUrl/comments'),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Accept-Language': _currentLocale,
      },
      body: json.encode({
        'article_id': articleId,
        'author_name': name,
        'author_email': email,
        'body': body,
      }),
    );
    if (response.statusCode == 201) {
      return;
    }
    // Try to get error message from response
    final data = json.decode(response.body);
    final message = data['message'] ?? 'Failed to post comment';
    throw Exception(message);
  }
}

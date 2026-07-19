import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../models/article.dart';
import '../services/api_service.dart';
import '../widgets/article_card.dart';
import '../widgets/animations.dart';
import '../app/theme.dart';
import 'package:khandan_app/l10n/app_localizations.dart';

class AuthorArticlesScreen extends StatefulWidget {
  final int authorId;
  final String authorName;

  const AuthorArticlesScreen({
    super.key,
    required this.authorId,
    required this.authorName,
  });

  @override
  State<AuthorArticlesScreen> createState() => _AuthorArticlesScreenState();
}

class _AuthorArticlesScreenState extends State<AuthorArticlesScreen> {
  final ApiService _api = ApiService();
  List<Article> _articles = [];
  bool _isLoading = true;
  String? _error;
  String? _authorImage;
  String? _authorBio;

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  Future<void> _loadData() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });
    try {
      // Fetch author info and articles in parallel
      final results = await Future.wait([
        _api.getAuthor(widget.authorId),
        _api.getArticles(authorId: widget.authorId),
      ]);

      final authorData = results[0] as Map<String, dynamic>;
      final articles = results[1] as List<Article>;

      if (!mounted) return;
      setState(() {
        _authorImage = authorData['image'] as String?;
        _authorBio = authorData['bio'] as String?;
        _articles = articles;
        _isLoading = false;
      });
    } catch (e) {
      if (!mounted) return;
      setState(() {
        _error = e.toString();
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context)!;
    final isRtl = Directionality.of(context).index == 1;

    return Scaffold(
      backgroundColor: AppTheme.bgDark,
      appBar: AppBar(
        backgroundColor: Colors.black,
        leading: IconButton(
          icon: Icon(isRtl ? Icons.arrow_forward : Icons.arrow_back, color: Colors.white),
          onPressed: () => Navigator.pop(context),
        ),
        title: Text(
          widget.authorName,
          style: const TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold),
        ),
      ),
      body: _buildBody(l10n),
    );
  }

  Widget _buildBody(AppLocalizations l10n) {
    if (_isLoading) {
      return const Center(
        child: CircularProgressIndicator(color: AppTheme.primaryColor),
      );
    }

    if (_error != null) {
      return Center(
        child: Padding(
          padding: const EdgeInsets.all(24),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Icon(Icons.error_outline, size: 48, color: Colors.redAccent),
              const SizedBox(height: 16),
              Text(_error!, textAlign: TextAlign.center,
                style: const TextStyle(color: AppTheme.textGray)),
              const SizedBox(height: 16),
              ElevatedButton.icon(
                onPressed: _loadData,
                icon: const Icon(Icons.refresh),
                label: Text(l10n.retry),
              ),
            ],
          ),
        ),
      );
    }

    return ListView(
      physics: const ClampingScrollPhysics(),
      children: [
        // Author Header
        Padding(
          padding: const EdgeInsets.all(24),
          child: Column(
            children: [
              if (_authorImage != null)
                ClipOval(
                  child: CachedNetworkImage(
                    imageUrl: _authorImage!,
                    height: 80,
                    width: 80,
                    fit: BoxFit.cover,
                    placeholder: (_, _) => Container(
                      height: 80, width: 80,
                      decoration: const BoxDecoration(
                        color: AppTheme.bgMuted,
                        shape: BoxShape.circle,
                      ),
                    ),
                    errorWidget: (_, _, _) => Container(
                      height: 80, width: 80,
                      decoration: BoxDecoration(
                        color: AppTheme.bgMuted,
                        shape: BoxShape.circle,
                      ),
                      child: const Icon(Icons.person, color: Colors.grey, size: 40),
                    ),
                  ),
                )
              else
                Container(
                  width: 80, height: 80,
                  decoration: const BoxDecoration(
                    color: AppTheme.bgMuted,
                    shape: BoxShape.circle,
                  ),
                  child: Center(
                    child: Text(
                      widget.authorName[0].toUpperCase(),
                      style: const TextStyle(color: AppTheme.primaryColor, fontSize: 32, fontWeight: FontWeight.bold),
                    ),
                  ),
                ),
              const SizedBox(height: 12),
              Text(
                widget.authorName,
                style: const TextStyle(color: Colors.white, fontSize: 20, fontWeight: FontWeight.bold),
              ),
              if (_authorBio != null) ...[
                const SizedBox(height: 8),
                Text(
                  _authorBio!,
                  textAlign: TextAlign.center,
                  style: const TextStyle(color: AppTheme.textGray, fontSize: 13),
                ),
              ],
            ],
          ),
        ),

        // Articles list
        if (_articles.isEmpty)
          Padding(
            padding: const EdgeInsets.all(32),
            child: Center(
              child: Column(
                children: [
                  Icon(Icons.article_outlined, size: 48, color: Colors.grey[700]),
                  const SizedBox(height: 12),
                  Text(l10n.noArticles,
                    style: const TextStyle(fontSize: 16, color: AppTheme.textGray)),
                ],
              ),
            ),
          )
        else
          ..._articles.asMap().entries.map((entry) {
            final index = entry.key;
            final article = entry.value;
            return FadeInUp(
              delay: Duration(milliseconds: 80 * (index % 6)),
              offset: 20,
              child: ArticleCard(article: article),
            );
          }),
      ],
    );
  }
}

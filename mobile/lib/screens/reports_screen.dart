import 'dart:async';
import 'package:flutter/material.dart';
import 'package:flutter/cupertino.dart';
import '../models/article.dart';
import '../models/category.dart';
import '../services/api_service.dart';
import '../widgets/article_card.dart';
import '../app/theme.dart';
import 'package:khandan_app/l10n/app_localizations.dart';

class ReportsScreen extends StatefulWidget {
  const ReportsScreen({super.key});

  @override
  State<ReportsScreen> createState() => _ReportsScreenState();
}

class _ReportsScreenState extends State<ReportsScreen> {
  final ApiService _api = ApiService();
  final TextEditingController _searchController = TextEditingController();
  List<Article> _articles = [];
  List<Category> _categories = [];
  int? _selectedCategoryId;
  bool _isLoading = true;
  String? _error;
  Timer? _debounce;

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  @override
  void dispose() {
    _searchController.dispose();
    _debounce?.cancel();
    super.dispose();
  }

  Future<void> _loadData() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });
    try {
      final results = await Future.wait([
        _api.getArticles(page: 1),
        _api.getCategories(),
      ]);
      if (!mounted) return;
      setState(() {
        _articles = results[0] as List<Article>;
        _categories = results[1] as List<Category>;
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

  Future<void> _loadArticles({int? categoryId, String? query}) async {
    setState(() {
      _error = null;
    });

    try {
      if (query != null && query.isNotEmpty) {
        final data = await _api.search(query);
        if (!mounted) return;
        final results = data['data'] as List? ?? [];
        setState(() {
          _articles = results.map((json) => Article.fromJson(json)).toList();
        });
      } else {
        final articles = await _api.getArticles(page: 1, categoryId: categoryId);
        if (!mounted) return;
        setState(() {
          _articles = articles;
        });
      }
    } catch (e) {
      if (!mounted) return;
      setState(() {
        _error = e.toString();
      });
    }
  }

  void _onSearchChanged(String value) {
    _debounce?.cancel();
    _debounce = Timer(const Duration(milliseconds: 500), () {
      _search(value);
    });
  }

  void _search(String query) {
    _selectedCategoryId = null;
    _loadArticles(query: query);
  }

  void _onCategoryTap(int? categoryId) {
    _searchController.clear();
    setState(() => _selectedCategoryId = categoryId);
    _loadArticles(categoryId: categoryId);
  }

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context)!;
    final isCkb = Localizations.localeOf(context).languageCode == 'ckb';

    return Column(
      children: [
        // Search bar
        Padding(
          padding: const EdgeInsets.fromLTRB(12, 12, 12, 4),
          child: Container(
            decoration: BoxDecoration(
              color: AppTheme.bgMuted,
              border: Border.all(color: AppTheme.borderGray),
            ),
            child: CupertinoTextField(
              controller: _searchController,
              onChanged: _onSearchChanged,
              textInputAction: TextInputAction.search,
              placeholder: l10n.searchArticles,
              placeholderStyle: const TextStyle(color: AppTheme.textMuted),
              style: const TextStyle(color: Colors.white),
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
              decoration: const BoxDecoration(),
              prefix: const Padding(
                padding: EdgeInsets.only(left: 12),
                child: Icon(Icons.search, size: 22, color: AppTheme.textGray),
              ),
              suffix: _searchController.text.isNotEmpty
                  ? Padding(
                      padding: const EdgeInsets.only(right: 4),
                      child: CupertinoButton(
                        padding: EdgeInsets.zero,
                        onPressed: () {
                          _searchController.clear();
                          setState(() => _selectedCategoryId = null);
                          _loadArticles();
                        },
                        child: const Icon(Icons.clear, size: 20, color: AppTheme.textGray),
                      ),
                    )
                  : null,
            ),
          ),
        ),

        // Category filter tabs
        if (_categories.isNotEmpty)
          SizedBox(
            height: 44,
            child: ListView(
              scrollDirection: Axis.horizontal,
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
              children: [
                _buildCategoryChip(isCkb ? 'هەموو' : 'All', null),
                ..._categories.map((cat) => _buildCategoryChip(cat.name, cat.id)),
              ],
            ),
          ),

        // Articles or loading/error
        Expanded(
          child: _buildContent(l10n),
        ),
      ],
    );
  }

  Widget _buildCategoryChip(String label, int? id) {
    final selected = _selectedCategoryId == id;
    return Padding(
      padding: const EdgeInsets.only(right: 6),
      child: GestureDetector(
        onTap: () => _onCategoryTap(id),
        child: Container(
          padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
          decoration: BoxDecoration(
            color: selected ? AppTheme.primaryColor : AppTheme.bgCard,
            border: Border.all(
              color: selected ? AppTheme.primaryColor : AppTheme.borderGray,
            ),
          ),
          child: Text(
            label,
            style: TextStyle(
              fontSize: 11,
              fontWeight: FontWeight.bold,
              color: selected ? Colors.white : AppTheme.textGray,
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildContent(AppLocalizations l10n) {
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
              Text(l10n.error, style: const TextStyle(color: AppTheme.textGray)),
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

    if (_articles.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.article_outlined, size: 64, color: Colors.grey[700]),
            const SizedBox(height: 12),
            Text(
              _searchController.text.isNotEmpty ? l10n.noResults : l10n.noArticles,
              style: const TextStyle(fontSize: 16, color: AppTheme.textGray),
            ),
          ],
        ),
      );
    }

    return ListView.builder(
      physics: const ClampingScrollPhysics(),
      itemCount: _articles.length,
      itemBuilder: (_, index) => ArticleCard(article: _articles[index]),
    );
  }
}

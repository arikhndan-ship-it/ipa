import 'dart:async';
import 'package:flutter/material.dart';
import 'package:flutter/cupertino.dart';
import '../models/article.dart';
import '../services/api_service.dart';
import '../widgets/article_card.dart';
import '../app/theme.dart';
import 'package:khandan_app/l10n/app_localizations.dart';

class SearchScreen extends StatefulWidget {
  const SearchScreen({super.key});

  @override
  State<SearchScreen> createState() => _SearchScreenState();
}

class _SearchScreenState extends State<SearchScreen> {
  final ApiService _api = ApiService();
  final TextEditingController _searchController = TextEditingController();
  List<Article> _results = [];
  bool _isSearching = false;
  bool _hasSearched = false;
  String? _error;
  Timer? _debounce;

  @override
  void dispose() {
    _searchController.dispose();
    _debounce?.cancel();
    super.dispose();
  }

  Future<void> _search(String query) async {
    if (query.trim().isEmpty) {
      setState(() {
        _results = [];
        _hasSearched = false;
      });
      return;
    }

    setState(() {
      _isSearching = true;
      _error = null;
    });

    try {
      final data = await _api.search(query);
      if (!mounted) return;
      final articles = data['data'] as List? ?? [];
      setState(() {
        _results = articles.map((json) => Article.fromJson(json)).toList();
        _isSearching = false;
        _hasSearched = true;
      });
    } catch (e) {
      if (!mounted) return;
      setState(() {
        _error = e.toString();
        _isSearching = false;
        _hasSearched = true;
      });
    }
  }

  void _onSearchChanged(String value) {
    _debounce?.cancel();
    _debounce = Timer(const Duration(milliseconds: 500), () {
      _search(value);
    });
  }

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context)!;

    return Column(
      children: [
        // Search bar
        Padding(
          padding: const EdgeInsets.all(12),
          child: Container(
            decoration: BoxDecoration(
              color: AppTheme.bgMuted,
              border: Border.all(color: AppTheme.borderGray),
            ),
            child: CupertinoTextField(
              controller: _searchController,
              onChanged: _onSearchChanged,
              textInputAction: TextInputAction.search,
              onSubmitted: _search,
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
                          setState(() {
                            _results = [];
                            _hasSearched = false;
                          });
                        },
                        child: const Icon(Icons.clear, size: 20, color: AppTheme.textGray),
                      ),
                    )
                  : null,
            ),
          ),
        ),
        Expanded(
          child: _buildResults(l10n),
        ),
      ],
    );
  }

  Widget _buildResults(AppLocalizations l10n) {
    if (_isSearching) {
      return const Center(child: CircularProgressIndicator(color: AppTheme.primaryColor));
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
              Text('${l10n.error}: $_error',
                style: const TextStyle(color: AppTheme.textGray)),
              const SizedBox(height: 16),
              ElevatedButton.icon(
                onPressed: () => _search(_searchController.text),
                icon: const Icon(Icons.refresh),
                label: Text(l10n.retry),
              ),
            ],
          ),
        ),
      );
    }

    if (!_hasSearched) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.search, size: 64, color: Colors.grey[700]),
            const SizedBox(height: 12),
            Text(
              l10n.searchArticles,
              style: const TextStyle(fontSize: 16, color: AppTheme.textGray),
            ),
          ],
        ),
      );
    }

    if (_results.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.search_off, size: 64, color: Colors.grey[700]),
            const SizedBox(height: 12),
            Text(
              l10n.noResults,
              style: const TextStyle(fontSize: 16, color: AppTheme.textGray),
            ),
          ],
        ),
      );
    }

    return ListView.builder(
      itemCount: _results.length,
      itemBuilder: (_, index) => ArticleCard(article: _results[index]),
    );
  }
}

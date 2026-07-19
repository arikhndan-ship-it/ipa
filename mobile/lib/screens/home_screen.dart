import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../models/article.dart';
import '../services/api_service.dart';
import '../widgets/article_card.dart';
import '../widgets/animations.dart';
import '../app/theme.dart';
import 'article_detail_screen.dart';
import 'package:khandan_app/l10n/app_localizations.dart';
import 'notifications_screen.dart';

class HomeScreen extends StatefulWidget {
  final void Function(Locale locale)? onLocaleChanged;
  final Locale? currentLocale;
  final VoidCallback? onNavigateToReports;

  const HomeScreen({super.key, this.onLocaleChanged, this.currentLocale, this.onNavigateToReports});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final ApiService _api = ApiService();
  List<Article> _articles = [];
  List<Article> _featuredArticles = [];
  List<Article> _breakingArticles = [];
  List<Article> _nonFeaturedArticles = [];
  bool _isLoading = true;
  bool _isLoadingMore = false;
  int _currentPage = 1;
  bool _hasMore = true;
  String? _error;
  final ScrollController _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    _loadArticles();
    _scrollController.addListener(_onScroll);
  }

  @override
  void dispose() {
    _scrollController.dispose();
    super.dispose();
  }

  void _onScroll() {
    if (_scrollController.position.pixels >= _scrollController.position.maxScrollExtent - 200) {
      _loadMore();
    }
  }

  Future<void> _loadArticles() async {
    setState(() {
      _isLoading = true;
      _error = null;
      _currentPage = 1;
      _hasMore = true;
    });
    try {
      // Fetch breaking news separately
      final breakingArticles = await _api.getBreakingArticles();
      // Fetch all articles
      final articles = await _api.getArticles(page: 1);
      if (!mounted) return;
      setState(() {
        _articles = articles;
        _breakingArticles = breakingArticles;
        _featuredArticles = articles.where((a) => a.isFeatured).toList();
        _nonFeaturedArticles = articles.where((a) => !a.isFeatured).toList();
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

  Future<void> _loadMore() async {
    if (_isLoadingMore || !_hasMore) return;
    setState(() => _isLoadingMore = true);
    try {
      final nextPage = _currentPage + 1;
      final articles = await _api.getArticles(page: nextPage);
      if (!mounted) return;
      setState(() {
        _currentPage = nextPage;
        if (articles.isEmpty) {
          _hasMore = false;
        } else {
          _nonFeaturedArticles.addAll(articles.where((a) => !a.isFeatured));
          _articles.addAll(articles);
        }
        _isLoadingMore = false;
      });
    } catch (_) {
      if (!mounted) return;
      setState(() => _isLoadingMore = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context)!;
    final isRtl = Directionality.of(context).index == 1;
    final locale = Localizations.localeOf(context);
    final isCkb = locale.languageCode == 'ckb';

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
                onPressed: _loadArticles,
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
        // Top language bar
        _buildLangBar(l10n, isCkb),

        // Breaking News Ticker
        if (_breakingArticles.isNotEmpty)
          _buildBreakingTicker(l10n),

        // Hero Section
        _buildHeroSection(l10n, isCkb),

        // Featured Reports
          if (_featuredArticles.isNotEmpty) ...[
            _buildSectionHeader(l10n.mainReports, () {}),
            ...List.generate(
              _featuredArticles.length.clamp(0, 3),
              (i) => FadeInUp(
                delay: Duration(milliseconds: 150 * (i + 1)),
                offset: 20,
                child: _buildFeaturedCard(_featuredArticles[i], l10n, isRtl, i),
              ),
            ),
          ],

        // Latest News Header with View All
        _buildSectionHeaderWithAction(
          l10n.latestNews,
          l10n.allReports,
          widget.onNavigateToReports ?? () {},
        ),

        // All article cards (show all articles, featured ones already shown above)
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
            final i = entry.key;
            final article = entry.value;
            return FadeInUp(
              delay: Duration(milliseconds: 80 * (i % 6)),
              offset: 20,
              child: ArticleCard(article: article),
            );
          }),

        // Loading more indicator
        if (_isLoadingMore)
          const Padding(
            padding: EdgeInsets.symmetric(vertical: 20),
            child: Center(
              child: CircularProgressIndicator(color: AppTheme.primaryColor),
            ),
          )
        else
          const SizedBox(height: 32),

        const SizedBox(height: 24),
      ],
    );
  }

  Widget _buildLangBar(AppLocalizations l10n, bool isCkb) {
    return Container(
      color: Colors.black,
      padding: EdgeInsets.only(top: MediaQuery.of(context).padding.top, bottom: 8),
      child: Row(
        children: [
          const SizedBox(width: 12),
          Image.asset(
            Localizations.localeOf(context).languageCode == 'ckb'
                ? 'assets/images/logo-ckb.png'
                : 'assets/images/logo-en.png',
            height: 24,
          ),
          const SizedBox(width: 8),
          Text(
            l10n.appName,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 13,
              fontWeight: FontWeight.bold,
              fontFamily: 'Serif',
            ),
          ),
          const Spacer(),
          // Notification Bell
          GestureDetector(
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (_) => const NotificationsScreen(),
                ),
              );
            },
            child: Padding(
              padding: const EdgeInsets.only(right: 8),
              child: Icon(
                Icons.notifications_outlined,
                color: Colors.grey[400],
                size: 20,
              ),
            ),
          ),
          Container(
            decoration: BoxDecoration(
              border: Border.all(color: AppTheme.borderGray),
            ),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                GestureDetector(
                  onTap: () => widget.onLocaleChanged?.call(const Locale('ckb')),
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                    color: isCkb ? AppTheme.primaryColor : Colors.transparent,
                    child: Text(
                      'کوردی',
                      style: TextStyle(
                        color: isCkb ? Colors.white : AppTheme.textGray,
                        fontSize: 10,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                ),
                Container(width: 1, height: 14, color: AppTheme.borderGray),
                GestureDetector(
                  onTap: () => widget.onLocaleChanged?.call(const Locale('en')),
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                    color: !isCkb ? AppTheme.primaryColor : Colors.transparent,
                    child: Text(
                      'EN',
                      style: TextStyle(
                        color: !isCkb ? Colors.white : AppTheme.textGray,
                        fontSize: 10,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(width: 12),
        ],
      ),
    );
  }

  Widget _buildBreakingTicker(AppLocalizations l10n) {
    // If empty, hide entirely (handled by caller)
    if (_breakingArticles.isEmpty) return const SizedBox.shrink();

    return Container(
      color: AppTheme.primaryColor,
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        children: [
          Container(
            color: Colors.black,
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                _PulsingDot(),
                const SizedBox(width: 6),
                Text(
                  l10n.breakingNews,
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 11,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ],
            ),
          ),
          Expanded(
            child: SizedBox(
              height: 28,
              child: _MarqueeTicker(
                articles: _breakingArticles,
                speed: const Duration(seconds: 12),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildHeroSection(AppLocalizations l10n, bool isCkb) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(vertical: 48, horizontal: 24),
      decoration: const BoxDecoration(
        color: Color(0xFF0A0A0A),
      ),
      child: Column(
        children: [
          // Logo - fade-in-scale (matching web)
          FadeInScale(
            delay: const Duration(milliseconds: 0),
            child: Image.asset(
            Localizations.localeOf(context).languageCode == 'ckb'
                ? 'assets/images/logo-ckb.png'
                : 'assets/images/logo-en.png',
            height: 120,
          ),
          ),
          const SizedBox(height: 16),
          // Site Name - fade-in-up delay 100
          FadeInUp(
            delay: const Duration(milliseconds: 100),
            offset: 20,
            child: Text(
              l10n.appName,
              style: const TextStyle(
                color: Colors.white,
                fontSize: 30,
                fontWeight: FontWeight.bold,
                fontFamily: 'Serif',
                letterSpacing: -0.5,
              ),
            ),
          ),
          const SizedBox(height: 8),
          // Tagline - fade-in-up delay 200
          FadeInUp(
            delay: const Duration(milliseconds: 200),
            offset: 15,
            child: Text(
              l10n.siteSubtitle,
              style: const TextStyle(
                color: AppTheme.primaryColor,
                fontSize: 11,
                fontWeight: FontWeight.bold,
                letterSpacing: 3,
              ),
              textAlign: TextAlign.center,
            ),
          ),
          const SizedBox(height: 16),
          // Crimson divider - scale-x-in delay 300
          ScaleXIn(
            delay: const Duration(milliseconds: 300),
            child: Container(
              width: 120,
              height: 1,
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  colors: [
                    Colors.transparent,
                    AppTheme.primaryColor,
                    Colors.transparent,
                  ],
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSectionHeader(String title, VoidCallback onTap) {
    return FadeInUp(
      delay: const Duration(milliseconds: 100),
      offset: 15,
      child: Padding(
        padding: const EdgeInsets.fromLTRB(16, 24, 16, 12),
        child: Row(
          children: [
            Expanded(
              child: Row(
                children: [
                  Text(
                    title,
                    style: const TextStyle(
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                      fontFamily: 'Serif',
                      color: Colors.white,
                    ),
                  ),
                  const SizedBox(width: 12),
                  const Expanded(
                    child: Divider(thickness: 1),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSectionHeaderWithAction(
    String title, String actionLabel, VoidCallback onTap) {
    return FadeInUp(
      delay: const Duration(milliseconds: 100),
      offset: 15,
      child: Padding(
        padding: const EdgeInsets.fromLTRB(16, 24, 16, 12),
        child: Row(
          children: [
            Row(
              children: [
                Text(
                  title,
                  style: const TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.bold,
                    fontFamily: 'Serif',
                    color: Colors.white,
                  ),
                ),
                const SizedBox(width: 12),
                const Expanded(
                  child: Divider(thickness: 1),
                ),
              ],
            ),
            const Spacer(),
            GestureDetector(
              onTap: onTap,
              child: Text(
                actionLabel,
                style: const TextStyle(
                  fontSize: 11,
                  fontWeight: FontWeight.bold,
                  color: AppTheme.primaryColor,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildFeaturedCard(Article article, AppLocalizations l10n, bool isRtl, int index) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
      child: InkWell(
        onTap: () => Navigator.push(
          context,
          MaterialPageRoute(
            builder: (_) => ArticleDetailScreen(article: article),
          ),
        ),
        child: Container(
          decoration: BoxDecoration(
            color: AppTheme.bgCard,
            border: Border.all(color: AppTheme.borderGray),
          ),
          clipBehavior: Clip.antiAlias,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Image with category badge (always show, like web)
              Stack(
                children: [
                  if (article.imageUrl != null)
                    SizedBox(
                      height: 200,
                      width: double.infinity,
                      child: CachedNetworkImage(
                        imageUrl: article.imageUrl!,
                        fit: BoxFit.cover,
                        placeholder: (_, _) => Container(
                          color: AppTheme.bgMuted,
                          child: const Center(
                            child: CircularProgressIndicator(color: AppTheme.primaryColor),
                          ),
                        ),
                        errorWidget: (_, _, _) => Container(
                          color: AppTheme.bgMuted,
                          child: const Icon(Icons.broken_image, size: 48, color: Colors.grey),
                        ),
                      ),
                    )
                  else
                    Container(
                      height: 200,
                      width: double.infinity,
                      color: AppTheme.bgMuted,
                      child: const Center(
                        child: Icon(Icons.image_outlined, size: 48, color: Colors.grey),
                      ),
                    ),
                  Positioned.fill(
                    child: Container(
                      decoration: BoxDecoration(
                        gradient: LinearGradient(
                          begin: Alignment.topCenter,
                          end: Alignment.bottomCenter,
                          colors: [
                            Colors.transparent,
                            Colors.black.withValues(alpha: 0.3),
                          ],
                        ),
                      ),
                    ),
                  ),
                  if (article.categoryName != null)
                    Positioned(
                      top: 12,
                      left: isRtl ? null : 12,
                      right: isRtl ? 12 : null,
                      child: Container(
                        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                        color: AppTheme.primaryColor,
                        child: Text(
                          article.categoryName!,
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 10,
                            fontWeight: FontWeight.bold,
                            letterSpacing: 1,
                          ),
                        ),
                      ),
                    ),
                ],
              ),
              Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Text(
                          '//',
                          style: TextStyle(
                            color: AppTheme.primaryColor,
                            fontWeight: FontWeight.bold,
                            fontSize: 12,
                            fontFamily: 'monospace',
                          ),
                        ),
                        const SizedBox(width: 8),
                        Text(
                          _formatDate(article.publishedAt ?? ''),
                          style: const TextStyle(
                            fontSize: 11,
                            color: AppTheme.textMuted,
                            fontFamily: 'monospace',
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 10),
                    Text(
                      article.title,
                      style: const TextStyle(
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                        fontFamily: 'Serif',
                        color: Colors.white,
                        height: 1.2,
                      ),
                      maxLines: 3,
                      overflow: TextOverflow.ellipsis,
                    ),
                    const SizedBox(height: 8),
                    if (article.excerpt != null)
                      Text(
                        article.excerpt!,
                        style: const TextStyle(
                          fontSize: 13,
                          color: AppTheme.textGray,
                          height: 1.4,
                        ),
                        maxLines: 3,
                        overflow: TextOverflow.ellipsis,
                      ),
                    const SizedBox(height: 12),
                    Row(
                      children: [
                        Text(
                          l10n.readMore,
                          style: const TextStyle(
                            fontSize: 11,
                            fontWeight: FontWeight.bold,
                            color: AppTheme.primaryColor,
                            letterSpacing: 1,
                          ),
                        ),
                        const SizedBox(width: 6),
                        Icon(
                          isRtl ? Icons.arrow_back_ios : Icons.arrow_forward_ios,
                          size: 12,
                          color: AppTheme.primaryColor,
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  String _formatDate(String dateStr) {
    try {
      final date = DateTime.parse(dateStr);
      return '${_months[date.month - 1]} ${date.day}, ${date.year}';
    } catch (_) {
      return dateStr;
    }
  }

  static const _months = [
    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
  ];
}

class _PulsingDot extends StatefulWidget {
  @override
  State<_PulsingDot> createState() => _PulsingDotState();
}

class _PulsingDotState extends State<_PulsingDot>
    with SingleTickerProviderStateMixin {
  late AnimationController _controller;
  late Animation<double> _animation;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(
      vsync: this,
      duration: const Duration(seconds: 1),
    )..repeat(reverse: true);
    _animation = Tween<double>(begin: 0.4, end: 1.0).animate(_controller);
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return AnimatedBuilder(
      animation: _animation,
      builder: (_, child) => Container(
        width: 8,
        height: 8,
        decoration: BoxDecoration(
          color: Colors.white.withValues(alpha: _animation.value),
          shape: BoxShape.circle,
        ),
      ),
    );
  }
}

/// A continuous horizontally-scrolling marquee for breaking news titles.
class _MarqueeTicker extends StatefulWidget {
  final List<Article> articles;
  final Duration speed;
  const _MarqueeTicker({required this.articles, this.speed = const Duration(seconds: 15)});

  @override
  State<_MarqueeTicker> createState() => _MarqueeTickerState();
}

class _MarqueeTickerState extends State<_MarqueeTicker>
    with SingleTickerProviderStateMixin {
  late AnimationController _controller;
  final ScrollController _scrollController = ScrollController();
  double _contentWidth = 0;
  final GlobalKey _contentKey = GlobalKey();

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(
      vsync: this,
      duration: widget.speed,
    );
    _controller.addListener(_scrollContent);
    _controller.repeat();
    // Re-measure on next frame
    WidgetsBinding.instance.addPostFrameCallback((_) => _measureContent());
  }

  @override
  void didUpdateWidget(_MarqueeTicker oldWidget) {
    super.didUpdateWidget(oldWidget);
    WidgetsBinding.instance.addPostFrameCallback((_) => _measureContent());
  }

  void _measureContent() {
    final box = _contentKey.currentContext?.findRenderObject() as RenderBox?;
    if (box != null) {
      setState(() => _contentWidth = box.size.width);
    }
  }

  void _scrollContent() {
    if (_contentWidth <= 0 || !_scrollController.hasClients) return;
    final halfWidth = _contentWidth / 2;
    final target = _controller.value * halfWidth;
    if (!_scrollController.hasClients) return;
    _scrollController.jumpTo(target.clamp(0, halfWidth));
  }

  @override
  void dispose() {
    _controller.dispose();
    _scrollController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final List<Widget> items = [];
    for (final article in widget.articles) {
      items.add(
        GestureDetector(
          onTap: () => Navigator.push(
            context,
            MaterialPageRoute(
              builder: (_) => ArticleDetailScreen(article: article),
            ),
          ),
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 12),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(
                  article.title,
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 12,
                    fontWeight: FontWeight.bold,
                  ),
                  overflow: TextOverflow.visible,
                ),
                const Text(
                  ' /// ',
                  style: TextStyle(
                    color: Colors.black54,
                    fontSize: 12,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ],
            ),
          ),
        ),
      );
    }

    // Duplicate for seamless looping
    final allItems = [...items, ...items];

    return SingleChildScrollView(
      controller: _scrollController,
      scrollDirection: Axis.horizontal,
      physics: const NeverScrollableScrollPhysics(),
      child: Row(
        key: _contentKey,
        mainAxisSize: MainAxisSize.min,
        children: allItems,
      ),
    );
  }
}

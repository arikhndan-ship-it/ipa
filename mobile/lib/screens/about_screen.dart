import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:url_launcher/url_launcher.dart';
import '../app/theme.dart';
import '../models/journalist.dart';
import '../services/api_service.dart';
import '../widgets/animations.dart';
import 'package:khandan_app/l10n/app_localizations.dart';
import 'author_articles_screen.dart';

class AboutScreen extends StatefulWidget {
  const AboutScreen({super.key});

  @override
  State<AboutScreen> createState() => _AboutScreenState();
}

class _AboutScreenState extends State<AboutScreen> {
  final ApiService _api = ApiService();
  List<Journalist> _journalists = [];
  bool _isLoading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _loadJournalists();
  }

  Future<void> _loadJournalists() async {
    try {
      final journalists = await _api.getJournalists();
      if (!mounted) return;
      setState(() {
        _journalists = journalists;
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
    final locale = Localizations.localeOf(context);
    final isCkb = locale.languageCode == 'ckb';

    return SingleChildScrollView(
      child: Column(
        children: [
          // Top spacing for status bar
          Container(
            height: MediaQuery.of(context).padding.top,
            color: Colors.black,
          ),

          // Profile / Header section (matching web with animations)
          Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(vertical: 40, horizontal: 16),
            decoration: const BoxDecoration(
              color: Color(0xFF0A0A0A),
              border: Border(
                bottom: BorderSide(color: AppTheme.borderGray),
              ),
            ),
            child: Column(
              children: [
                // Profile photo - fade-in-scale (matching web)
                FadeInScale(
                  delay: const Duration(milliseconds: 0),
                  child: Stack(
                    alignment: Alignment.center,
                    children: [
                      Container(
                        width: 140, height: 140,
                        decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          color: AppTheme.primaryColor.withValues(alpha: 0.15),
                          boxShadow: [BoxShadow(
                            color: AppTheme.primaryColor.withValues(alpha: 0.3),
                            blurRadius: 30, spreadRadius: 5,
                          )],
                        ),
                      ),
                      Container(
                        width: 120, height: 120,
                        decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          border: Border.all(color: AppTheme.primaryColor, width: 3),
                          color: AppTheme.bgMuted,
                        ),
                        clipBehavior: Clip.antiAlias,
                        child: ClipOval(
                          child: CachedNetworkImage(
                            imageUrl: 'https://khandantelegraph.news/images/ari_author.jpg',
                            fit: BoxFit.cover,
                            placeholder: (_, _) => const Icon(Icons.person, size: 50, color: Colors.grey),
                            errorWidget: (_, _, _) => const Icon(Icons.person, size: 50, color: Colors.grey),
                          ),
                        ),
                      ),
                      Positioned(top: 0, left: 0, child: _CornerBracket(top: true, left: true)),
                      Positioned(top: 0, right: 0, child: _CornerBracket(top: true, left: false)),
                      Positioned(bottom: 0, left: 0, child: _CornerBracket(top: false, left: true)),
                      Positioned(bottom: 0, right: 0, child: _CornerBracket(top: false, left: false)),
                    ],
                  ),
                ),
                const SizedBox(height: 20),

                // Role label - fade-in-up delay 200
                FadeInUp(
                  delay: const Duration(milliseconds: 200),
                  offset: 15,
                  child: Text(
                    l10n.founderTitle,
                    style: const TextStyle(
                      fontSize: 11, fontWeight: FontWeight.bold,
                      color: AppTheme.primaryColor, letterSpacing: 3,
                    ),
                  ),
                ),
                const SizedBox(height: 8),

                // Founder name - fade-in-up delay 300
                FadeInUp(
                  delay: const Duration(milliseconds: 300),
                  offset: 20,
                  child: Text(
                    l10n.founderName,
                    style: const TextStyle(
                      fontSize: 28, fontWeight: FontWeight.bold,
                      fontFamily: 'Serif', color: Colors.white,
                    ),
                    textAlign: TextAlign.center,
                  ),
                ),
                const SizedBox(height: 16),

                // Crimson divider - scale-x-in delay 500
                ScaleXIn(
                  delay: const Duration(milliseconds: 500),
                  child: Container(
                    width: 100, height: 1,
                    decoration: BoxDecoration(
                      gradient: LinearGradient(colors: [
                        Colors.transparent, AppTheme.primaryColor, Colors.transparent,
                      ]),
                    ),
                  ),
                ),
                const SizedBox(height: 16),

                // About description - fade-in-up delay 600
                FadeInUp(
                  delay: const Duration(milliseconds: 600),
                  offset: 15,
                  child: Text(
                    l10n.aboutDescription,
                    style: const TextStyle(
                      fontSize: 14, color: AppTheme.textGray, height: 1.6,
                    ),
                    textAlign: TextAlign.center,
                  ),
                ),
              ],
            ),
          ),

          // Bio + Quote (scroll-reveal effect)
          FadeInUp(
            delay: const Duration(milliseconds: 200),
            offset: 25,
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Container(
                decoration: BoxDecoration(
                  color: AppTheme.bgCard,
                  border: Border.all(color: AppTheme.borderGray),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.stretch,
                  children: [
                    Container(height: 4, color: AppTheme.primaryColor),
                    Padding(
                      padding: const EdgeInsets.all(20),
                      child: Column(
                        crossAxisAlignment: isCkb ? CrossAxisAlignment.end : CrossAxisAlignment.start,
                        children: [
                          Text(
                            l10n.aboutStory,
                            style: const TextStyle(
                              fontSize: 14, color: AppTheme.textGray, height: 1.6,
                            ),
                          ),
                          const SizedBox(height: 20),
                          // Quote container
                          Container(
                            padding: const EdgeInsets.all(16),
                            decoration: BoxDecoration(
                              border: Border(
                                left: isCkb ? BorderSide.none : const BorderSide(color: AppTheme.primaryColor, width: 3),
                                right: isCkb ? const BorderSide(color: AppTheme.primaryColor, width: 3) : BorderSide.none,
                              ),
                              color: AppTheme.bgMuted.withValues(alpha: 0.5),
                            ),
                            child: Row(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                if (!isCkb) ...[
                                  const Icon(Icons.format_quote, color: AppTheme.primaryColor, size: 24),
                                  const SizedBox(width: 8),
                                ],
                                Expanded(
                                  child: Text(
                                    l10n.aboutQuote,
                                    style: const TextStyle(
                                      fontSize: 14, fontStyle: FontStyle.italic,
                                      color: Colors.white, height: 1.5,
                                    ),
                                  ),
                                ),
                                if (isCkb) ...[
                                  const SizedBox(width: 8),
                                  const Icon(Icons.format_quote, color: AppTheme.primaryColor, size: 24),
                                ],
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),

          const SizedBox(height: 32),

          // The Three Pillars
          FadeInUp(
            delay: const Duration(milliseconds: 200),
            offset: 15,
            child: Padding(
              padding: const EdgeInsets.fromLTRB(16, 32, 16, 8),
              child: Column(
                children: [
                  Text(
                    isCkb ? 'سێ بنەماکە' : 'Our Three Pillars',
                    style: const TextStyle(
                      fontSize: 22, fontWeight: FontWeight.bold,
                      fontFamily: 'Serif', color: Colors.white,
                    ),
                    textAlign: TextAlign.center,
                  ),
                  const SizedBox(height: 4),
                  Container(width: 60, height: 3, color: AppTheme.primaryColor),
                ],
              ),
            ),
          ),

          const SizedBox(height: 24),

          // Pillars with staggered animation
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: Column(
              children: [
                FadeInUp(
                  delay: const Duration(milliseconds: 300),
                  offset: 20,
                  child: _PillarCard(
                    icon: Icons.visibility,
                    title: l10n.principle1Title,
                    description: l10n.principle1Desc,
                  ),
                ),
                const SizedBox(height: 12),
                FadeInUp(
                  delay: const Duration(milliseconds: 450),
                  offset: 20,
                  child: _PillarCard(
                    icon: Icons.shield,
                    title: l10n.principle2Title,
                    description: l10n.principle2Desc,
                  ),
                ),
                const SizedBox(height: 12),
                FadeInUp(
                  delay: const Duration(milliseconds: 600),
                  offset: 20,
                  child: _PillarCard(
                    icon: Icons.record_voice_over,
                    title: l10n.principle3Title,
                    description: l10n.principle3Desc,
                  ),
                ),
              ],
            ),
          ),

          const SizedBox(height: 32),

          // Social Media Links
          FadeInUp(
            delay: const Duration(milliseconds: 200),
            offset: 15,
            child: Padding(
              padding: const EdgeInsets.fromLTRB(16, 16, 16, 8),
              child: Column(
                children: [
                  Text(
                    isCkb ? 'شوێنمان بکەون' : 'Follow Us',
                    style: const TextStyle(
                      fontSize: 22, fontWeight: FontWeight.bold,
                      fontFamily: 'Serif', color: Colors.white,
                    ),
                    textAlign: TextAlign.center,
                  ),
                  const SizedBox(height: 4),
                  Container(width: 60, height: 3, color: AppTheme.primaryColor),
                ],
              ),
            ),
          ),

          const SizedBox(height: 20),

          // Social icons row
          FadeInUp(
            delay: const Duration(milliseconds: 350),
            offset: 15,
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                // Telegram
                GestureDetector(
                  onTap: () => launchUrl(
                    Uri.parse('https://t.me/khandantelegraph'),
                    mode: LaunchMode.externalApplication,
                  ).catchError((_) => false),
                  child: Container(
                    width: 60, height: 60,
                    decoration: const BoxDecoration(
                      color: Color(0xFF0088cc),
                      shape: BoxShape.circle,
                    ),
                    child: const Icon(Icons.send, color: Colors.white, size: 28),
                  ),
                ),
                const SizedBox(width: 24),
                // Facebook
                GestureDetector(
                  onTap: () => launchUrl(
                    Uri.parse('https://www.facebook.com/share/194x5ECuH1/'),
                    mode: LaunchMode.externalApplication,
                  ).catchError((_) => false),
                  child: Container(
                    width: 60, height: 60,
                    decoration: const BoxDecoration(
                      color: Color(0xFF1877F2),
                      shape: BoxShape.circle,
                    ),
                    child: const Center(
                      child: Text('f',
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 34,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),

          const SizedBox(height: 32),

          // Our Journalists
          FadeInUp(
            delay: const Duration(milliseconds: 200),
            offset: 15,
            child: Padding(
              padding: const EdgeInsets.fromLTRB(16, 16, 16, 8),
              child: Column(
                children: [
                  Text(
                    isCkb ? 'ڕۆژنامەنووسەکانمان' : 'Our Journalists',
                    style: const TextStyle(
                      fontSize: 22, fontWeight: FontWeight.bold,
                      fontFamily: 'Serif', color: Colors.white,
                    ),
                    textAlign: TextAlign.center,
                  ),
                  const SizedBox(height: 4),
                  Container(width: 60, height: 3, color: AppTheme.primaryColor),
                ],
              ),
            ),
          ),

          const SizedBox(height: 16),

          // Journalists list
          _buildJournalistsSection(),

          const SizedBox(height: 32),

          // Founder tile
          FadeInUp(
            delay: const Duration(milliseconds: 300),
            offset: 15,
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Container(
                decoration: BoxDecoration(
                  color: AppTheme.bgCard,
                  border: Border.all(color: AppTheme.borderGray),
                ),
                child: ListTile(
                  leading: Container(
                    width: 40, height: 40,
                    decoration: const BoxDecoration(
                      color: AppTheme.primaryColor,
                      shape: BoxShape.circle,
                    ),
                    child: const Icon(Icons.person, color: Colors.white, size: 22),
                  ),
                  title: Text(l10n.founderName,
                    style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
                  subtitle: Text(l10n.founderTitle,
                    style: const TextStyle(color: AppTheme.textGray, fontSize: 12)),
                ),
              ),
            ),
          ),

          const SizedBox(height: 32),
        ],
      ),
    );
  }

  Widget _buildJournalistsSection() {
    final isCkb = Localizations.localeOf(context).languageCode == 'ckb';
    if (_isLoading) {
      return const Padding(
        padding: EdgeInsets.symmetric(vertical: 16),
        child: Center(
          child: SizedBox(
            width: 24, height: 24,
            child: CircularProgressIndicator(
              color: AppTheme.primaryColor,
              strokeWidth: 2,
            ),
          ),
        ),
      );
    }

    if (_error != null) {
      return const SizedBox.shrink();
    }

    if (_journalists.isEmpty) {
      return Padding(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
        child: Text(
          isCkb ? 'هێشتا هیچ ڕۆژنامەنووسێک زیاد نەکراوە.' : 'No journalists added yet.',
          style: const TextStyle(color: AppTheme.textGray, fontSize: 14),
          textAlign: TextAlign.center,
        ),
      );
    }

    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Column(
        children: _journalists.asMap().entries.map((entry) {
          final i = entry.key;
          final journalist = entry.value;
          return FadeInUp(
            delay: Duration(milliseconds: 150 * (i % 6)),
            offset: 15,
            child: GestureDetector(
              onTap: () {
                if (journalist.userId != null) {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (_) => AuthorArticlesScreen(
                        authorId: journalist.userId!,
                        authorName: journalist.name,
                      ),
                    ),
                  );
                }
              },
              child: Padding(
                padding: const EdgeInsets.only(bottom: 12),
                child: Container(
                  decoration: BoxDecoration(
                    color: AppTheme.bgCard,
                    border: Border.all(color: AppTheme.borderGray),
                  ),
                  padding: const EdgeInsets.all(16),
                  child: Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // Photo
                      Container(
                        width: 64, height: 64,
                        decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          border: Border.all(color: AppTheme.primaryColor, width: 2),
                        ),
                        clipBehavior: Clip.antiAlias,
                        child: journalist.image != null
                            ? ClipOval(
                                child: CachedNetworkImage(
                                  imageUrl: journalist.image!,
                                  fit: BoxFit.cover,
                                  placeholder: (_, _) => const Icon(Icons.person, size: 30, color: Colors.grey),
                                  errorWidget: (_, _, _) => _defaultAvatar(),
                                ),
                              )
                            : _defaultAvatar(),
                      ),
                      const SizedBox(width: 16),
                      // Name + Bio
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              children: [
                                Expanded(
                                  child: Text(
                                    journalist.name,
                                    style: const TextStyle(
                                      fontSize: 16,
                                      fontWeight: FontWeight.bold,
                                      fontFamily: 'Serif',
                                      color: Colors.white,
                                    ),
                                  ),
                                ),
                                if (journalist.userId != null)
                                  const Icon(Icons.chevron_right, color: AppTheme.primaryColor, size: 20),
                              ],
                            ),
                            if (journalist.bio != null && journalist.bio!.isNotEmpty) ...[
                              const SizedBox(height: 6),
                              Text(
                                journalist.bio!,
                                style: const TextStyle(
                                  fontSize: 13,
                                  color: AppTheme.textGray,
                                  height: 1.4,
                                ),
                              ),
                            ],
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          );
        }).toList(),
      ),
    );
  }

  Widget _defaultAvatar() {
    return Container(
      color: AppTheme.bgMuted,
      child: const Icon(Icons.person, size: 30, color: Colors.grey),
    );
  }
}

class _CornerBracket extends StatelessWidget {
  final bool top;
  final bool left;

  const _CornerBracket({required this.top, required this.left});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 16, height: 16,
      decoration: BoxDecoration(
        border: Border(
          top: top ? const BorderSide(color: AppTheme.primaryColor, width: 2) : BorderSide.none,
          bottom: !top ? const BorderSide(color: AppTheme.primaryColor, width: 2) : BorderSide.none,
          left: left ? const BorderSide(color: AppTheme.primaryColor, width: 2) : BorderSide.none,
          right: !left ? const BorderSide(color: AppTheme.primaryColor, width: 2) : BorderSide.none,
        ),
      ),
    );
  }
}

class _PillarCard extends StatelessWidget {
  final IconData icon;
  final String title;
  final String description;

  const _PillarCard({required this.icon, required this.title, required this.description});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: AppTheme.bgCard,
        border: Border.all(color: AppTheme.borderGray),
      ),
      child: Column(
        children: [
          Container(
            width: 48, height: 48,
            color: AppTheme.bgDark,
            child: Icon(icon, color: Colors.white, size: 24),
          ),
          const SizedBox(height: 12),
          Text(
            title,
            style: const TextStyle(
              fontSize: 16, fontWeight: FontWeight.bold,
              fontFamily: 'Serif', color: AppTheme.primaryColor,
            ),
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 8),
          Text(
            description,
            style: const TextStyle(fontSize: 13, color: AppTheme.textGray, height: 1.5),
            textAlign: TextAlign.center,
          ),
        ],
      ),
    );
  }
}

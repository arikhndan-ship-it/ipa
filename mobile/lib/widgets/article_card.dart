import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:intl/intl.dart';
import '../models/article.dart';
import '../app/theme.dart';
import '../screens/article_detail_screen.dart';
import 'animations.dart';
import 'package:khandan_app/l10n/app_localizations.dart';

class ArticleCard extends StatelessWidget {
  final Article article;

  const ArticleCard({super.key, required this.article});

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context)!;
    final isRtl = Directionality.of(context).index == 1;

    return FadeInUp(
      delay: Duration.zero,
      offset: 15,
      duration: const Duration(milliseconds: 500),
      child: Padding(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
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
              // Image with category badge overlay (always show, like web)
              Stack(
                children: [
                  // Image or placeholder
                  if (article.imageUrl != null)
                    CachedNetworkImage(
                      imageUrl: article.imageUrl!,
                      height: 180,
                      width: double.infinity,
                      fit: BoxFit.cover,
                      placeholder: (_, _) => Container(
                        height: 180,
                        color: AppTheme.bgMuted,
                        child: const Center(
                          child: CircularProgressIndicator(color: AppTheme.primaryColor),
                        ),
                      ),
                      errorWidget: (_, _, _) => Container(
                        height: 180,
                        color: AppTheme.bgMuted,
                        child: const Icon(Icons.broken_image, size: 48, color: Colors.grey),
                      ),
                    )
                  else
                    Container(
                      height: 180,
                      width: double.infinity,
                      color: AppTheme.bgMuted,
                      child: const Center(
                        child: Icon(Icons.image_outlined, size: 48, color: Colors.grey),
                      ),
                    ),
                  // Dark overlay
                  Positioned.fill(
                    child: Container(
                      decoration: BoxDecoration(
                        gradient: LinearGradient(
                          begin: Alignment.topCenter,
                          end: Alignment.bottomCenter,
                          colors: [
                            Colors.transparent,
                            Colors.black.withValues(alpha: 0.2),
                          ],
                        ),
                      ),
                    ),
                  ),
                  // Category badge
                  if (article.categoryName != null)
                    Positioned(
                      top: 12,
                      left: isRtl ? null : 12,
                      right: isRtl ? 12 : null,
                      child: Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                        color: AppTheme.primaryColor,
                        child: Text(
                          article.categoryName!,
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 10,
                            fontWeight: FontWeight.w700,
                            letterSpacing: 0.5,
                          ),
                        ),
                      ),
                    ),
                ],
              ),
              // Content area
              Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Date with // prefix (matching web)
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
                    // Title
                    Text(
                      article.title,
                      style: const TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                        fontFamily: 'Serif',
                        color: Colors.white,
                        height: 1.3,
                      ),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    if (article.excerpt != null) ...[
                      const SizedBox(height: 8),
                      Text(
                        article.excerpt!,
                        style: const TextStyle(
                          fontSize: 13,
                          color: AppTheme.textGray,
                          height: 1.4,
                        ),
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                      ),
                    ],
                    const SizedBox(height: 12),
                    // Read More (matching web)
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
    ),
  );
  }
}

  String _formatDate(String dateStr) {
    try {
      final date = DateTime.parse(dateStr);
      return DateFormat('MMM dd, yyyy').format(date);
    } catch (_) {
      return dateStr;
    }
  }

import 'package:flutter/material.dart';
import 'package:flutter/cupertino.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:intl/intl.dart';
import 'package:share_plus/share_plus.dart';
import '../models/article.dart';
import '../models/comment.dart';
import '../services/api_service.dart';
import '../app/theme.dart';
import '../widgets/article_card.dart';
import 'package:khandan_app/l10n/app_localizations.dart';
import 'author_articles_screen.dart';

class ArticleDetailScreen extends StatefulWidget {
  final Article article;

  const ArticleDetailScreen({super.key, required this.article});

  @override
  State<ArticleDetailScreen> createState() => _ArticleDetailScreenState();
}

class _ArticleDetailScreenState extends State<ArticleDetailScreen> {
  final ApiService _api = ApiService();
  List<Comment> _comments = [];
  List<Article> _relatedArticles = [];
  bool _isLoadingComments = true;
  bool _isLoadingRelated = true;

  final TextEditingController _nameController = TextEditingController();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _commentController = TextEditingController();
  bool _isPostingComment = false;
  String? _commentMessage;
  bool _commentSuccess = false;

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _commentController.dispose();
    super.dispose();
  }

  Future<void> _loadData() async {
    await Future.wait([_loadComments(), _loadRelated()]);
  }

  Future<void> _loadComments() async {
    try {
      final comments = await _api.getComments(widget.article.id);
      if (!mounted) return;
      setState(() {
        _comments = comments;
        _isLoadingComments = false;
      });
    } catch (_) {
      if (!mounted) return;
      setState(() => _isLoadingComments = false);
    }
  }

  Future<void> _loadRelated() async {
    try {
      final articles = await _api.getArticles(page: 1);
      if (!mounted) return;
      setState(() {
        _relatedArticles =
            articles.where((a) => a.id != widget.article.id).take(5).toList();
        _isLoadingRelated = false;
      });
    } catch (_) {
      if (!mounted) return;
      setState(() => _isLoadingRelated = false);
    }
  }

  Future<void> _postComment() async {
    final name = _nameController.text.trim();
    final email = _emailController.text.trim();
    final body = _commentController.text.trim();
    final l10n = AppLocalizations.of(context)!;

    if (name.isEmpty || email.isEmpty || body.isEmpty) {
      setState(() => _commentMessage = l10n.pleaseFillAllFields);
      return;
    }

    setState(() {
      _isPostingComment = true;
      _commentMessage = null;
      _commentSuccess = false;
    });

    try {
      await _api.postComment(widget.article.id, name, email, body);
      if (!mounted) return;
      _nameController.clear();
      _emailController.clear();
      _commentController.clear();
      setState(() {
        _isPostingComment = false;
        _commentMessage = l10n.commentSuccess;
        _commentSuccess = true;
      });
      _loadComments();
    } catch (e) {
      if (!mounted) return;
      setState(() {
        _isPostingComment = false;
        _commentMessage = e.toString().replaceFirst('Exception: ', '');
        _commentSuccess = false;
      });
    }
  }

  void _shareArticle() {
    final l10n = AppLocalizations.of(context)!;
    final article = widget.article;
    final webUrl = 'https://khandantelegraph.news/articles/${article.slug}';
    final shareText = '${article.title}\n\n$webUrl';
    SharePlus.instance.share(ShareParams(
      text: shareText,
      title: article.title,
    ));
  }

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context)!;
    final article = widget.article;
    final isRtl = Directionality.of(context).index == 1;

    return Scaffold(
      appBar: AppBar(
        leading: IconButton(
          icon: const Icon(Icons.home, color: AppTheme.primaryColor),
          onPressed: () => Navigator.of(context).pop(),
          tooltip: l10n.backToHome,
        ),
        title: Image.asset(
          Localizations.localeOf(context).languageCode == 'ckb'
              ? 'assets/images/logo-ckb.png'
              : 'assets/images/logo-en.png',
          height: 28,
          errorBuilder: (_, _, _) => Text(
            l10n.appName,
            style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold),
          ),
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.share, color: AppTheme.primaryColor),
            onPressed: _shareArticle,
            tooltip: l10n.share,
          ),
        ],
      ),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Featured Image
            if (article.imageUrl != null)
              CachedNetworkImage(
                imageUrl: article.imageUrl!,
                width: double.infinity,
                height: 250,
                fit: BoxFit.cover,
                placeholder: (_, _) => Container(
                  height: 250,
                  color: AppTheme.bgMuted,
                  child: const Center(
                    child: CircularProgressIndicator(color: AppTheme.primaryColor),
                  ),
                ),
                errorWidget: (_, _, _) => Container(
                  height: 250,
                  color: AppTheme.bgMuted,
                  child: const Icon(Icons.broken_image, size: 48, color: Colors.grey),
                ),
              ),

            Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: isRtl ? CrossAxisAlignment.end : CrossAxisAlignment.start,
                children: [
                  // Category badge
                  if (article.categoryName != null)
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                      color: AppTheme.primaryColor,
                      child: Text(
                        article.categoryName!,
                        style: const TextStyle(
                          color: Colors.white,
                          fontSize: 11,
                          fontWeight: FontWeight.w700,
                          letterSpacing: 1,
                        ),
                      ),
                    ),
                  const SizedBox(height: 12),

                  // Title
                  Text(
                    article.title,
                    style: const TextStyle(
                      fontSize: 22,
                      fontWeight: FontWeight.bold,
                      fontFamily: 'Serif',
                      color: Colors.white,
                      height: 1.3,
                    ),
                  ),
                  const SizedBox(height: 12),

                  // Author & Date & Views row
                  Row(
                    children: [
                      if (article.authorName != null) ...[
                        // Author avatar - use image if available
                        Container(
                          width: 24, height: 24,
                          clipBehavior: Clip.antiAlias,
                          decoration: const BoxDecoration(
                            color: AppTheme.primaryColor,
                            shape: BoxShape.circle,
                          ),
                          child: article.authorImage != null
                              ? ClipOval(
                                  child: CachedNetworkImage(
                                    imageUrl: article.authorImage!,
                                    width: 24, height: 24,
                                    fit: BoxFit.cover,
                                    placeholder: (_, _) => Container(color: AppTheme.primaryColor),
                                    errorWidget: (_, _, _) => const Center(
                                      child: Text('?', style: TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.bold)),
                                    ),
                                  ),
                                )
                              : Center(
                                  child: Text(
                                    article.authorName![0].toUpperCase(),
                                    style: const TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.bold),
                                  ),
                                ),
                        ),
                        const SizedBox(width: 6),
                        GestureDetector(
                          onTap: () {
                            final authorId = article.authorId;
                            if (authorId != null) {
                              Navigator.push(
                                context,
                                MaterialPageRoute(
                                  builder: (_) => AuthorArticlesScreen(
                                    authorId: authorId,
                                    authorName: article.authorName ?? '',
                                  ),
                                ),
                              );
                            }
                          },
                          child: Text(
                            article.authorName!,
                            style: const TextStyle(
                              fontSize: 12,
                              color: AppTheme.primaryColor,
                              decoration: TextDecoration.underline,
                            ),
                          ),
                        ),
                        const SizedBox(width: 16),
                      ],
                      Icon(Icons.access_time, size: 14, color: AppTheme.textMuted),
                      const SizedBox(width: 4),
                      Text(
                        _formatDate(article.publishedAt),
                        style: const TextStyle(fontSize: 12, color: AppTheme.textMuted),
                      ),
                      const Spacer(),
                      Icon(Icons.visibility, size: 14, color: AppTheme.textMuted),
                      const SizedBox(width: 4),
                      Text(
                        '${article.viewCount}',
                        style: const TextStyle(fontSize: 12, color: AppTheme.textMuted),
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),
                  const Divider(),
                  const SizedBox(height: 16),

                  // Article Body
                  SelectableText(
                    _stripHtml(article.body),
                    style: const TextStyle(
                      fontSize: 15,
                      height: 1.7,
                      color: AppTheme.textGray,
                    ),
                  ),
                  const SizedBox(height: 24),

                  // Share button
                  SizedBox(
                    width: double.infinity,
                    child: OutlinedButton.icon(
                      onPressed: _shareArticle,
                      icon: const Icon(Icons.share, size: 16),
                      label: Text(l10n.share.toUpperCase()),
                      style: OutlinedButton.styleFrom(
                        foregroundColor: AppTheme.primaryColor,
                        side: const BorderSide(color: AppTheme.primaryColor),
                        padding: const EdgeInsets.symmetric(vertical: 12),
                        shape: const RoundedRectangleBorder(),
                      ),
                    ),
                  ),
                  const SizedBox(height: 24),
                  const Divider(),
                  const SizedBox(height: 16),

                  // Comments Section
                  Text(
                    '${l10n.comments} (${_comments.length})',
                    style: const TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: Colors.white,
                    ),
                  ),
                  const SizedBox(height: 12),
                  _buildCommentForm(l10n),
                  const SizedBox(height: 16),
                  if (_isLoadingComments)
                    const Center(child: CircularProgressIndicator(color: AppTheme.primaryColor))
                  else if (_comments.isEmpty)
                    Padding(
                      padding: const EdgeInsets.all(16),
                      child: Text(
                        l10n.noComments,
                        style: const TextStyle(color: AppTheme.textMuted),
                      ),
                    )
                  else
                    ..._comments
                        .where((c) => c.isApproved)
                        .map((comment) => _CommentTile(comment: comment)),
                  const SizedBox(height: 24),
                  const Divider(),
                  const SizedBox(height: 16),

                  // Related Articles
                  Text(
                    l10n.relatedArticles,
                    style: const TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: Colors.white,
                    ),
                  ),
                  const SizedBox(height: 8),
                  if (_isLoadingRelated)
                    const Center(child: CircularProgressIndicator(color: AppTheme.primaryColor))
                  else
                    ..._relatedArticles.map((a) => ArticleCard(article: a)),
                  const SizedBox(height: 24),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildCommentForm(AppLocalizations l10n) {
    return Container(
      decoration: BoxDecoration(
        color: AppTheme.bgCard,
        border: Border.all(color: AppTheme.borderGray),
      ),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              l10n.leaveComment,
              style: const TextStyle(
                fontSize: 14,
                fontWeight: FontWeight.w600,
                color: Colors.white,
              ),
            ),
            const SizedBox(height: 12),
            CupertinoTextField(
              controller: _nameController,
              placeholder: l10n.yourName,
              placeholderStyle: const TextStyle(color: AppTheme.textMuted),
              style: const TextStyle(color: Colors.white),
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
              decoration: BoxDecoration(
                color: AppTheme.bgMuted,
                border: Border.all(color: AppTheme.borderGray),
              ),
            ),
            const SizedBox(height: 8),
            CupertinoTextField(
              controller: _emailController,
              keyboardType: TextInputType.emailAddress,
              placeholder: l10n.yourEmail,
              placeholderStyle: const TextStyle(color: AppTheme.textMuted),
              style: const TextStyle(color: Colors.white),
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
              decoration: BoxDecoration(
                color: AppTheme.bgMuted,
                border: Border.all(color: AppTheme.borderGray),
              ),
            ),
            const SizedBox(height: 8),
            CupertinoTextField(
              controller: _commentController,
              placeholder: l10n.yourMessage,
              placeholderStyle: const TextStyle(color: AppTheme.textMuted),
              style: const TextStyle(color: Colors.white),
              maxLines: 3,
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
              decoration: BoxDecoration(
                color: AppTheme.bgMuted,
                border: Border.all(color: AppTheme.borderGray),
              ),
            ),
            const SizedBox(height: 8),
            if (_commentMessage != null)
              Padding(
                padding: const EdgeInsets.only(bottom: 8),
                child: Text(
                  _commentMessage!,
                  style: TextStyle(
                    color: _commentSuccess ? Colors.greenAccent : Colors.redAccent,
                    fontSize: 13,
                  ),
                ),
              ),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: _isPostingComment ? null : _postComment,
                child: _isPostingComment
                    ? const SizedBox(
                        height: 18,
                        width: 18,
                        child: CircularProgressIndicator(
                          strokeWidth: 2,
                          color: Colors.white,
                        ),
                      )
                    : Text(l10n.send),
              ),
            ),
          ],
        ),
      ),
    );
  }

  String _formatDate(String? dateStr) {
    if (dateStr == null) return '';
    try {
      final date = DateTime.parse(dateStr);
      return DateFormat('MMM dd, yyyy').format(date);
    } catch (_) {
      return dateStr;
    }
  }

  String _stripHtml(String html) {
    return html
        .replaceAll(RegExp(r'<[^>]*>'), '')
        .replaceAll('&nbsp;', ' ')
        .replaceAll('&amp;', '&')
        .replaceAll('&lt;', '<')
        .replaceAll('&gt;', '>')
        .replaceAll('&quot;', '"')
        .replaceAll('&#39;', "'")
        .trim();
  }
}

class _CommentTile extends StatelessWidget {
  final Comment comment;

  const _CommentTile({required this.comment});

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(bottom: 8),
      decoration: BoxDecoration(
        color: AppTheme.bgCard,
        border: Border.all(color: AppTheme.borderGray),
      ),
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Container(
                  width: 28, height: 28,
                  decoration: const BoxDecoration(
                    color: AppTheme.primaryColor,
                    shape: BoxShape.circle,
                  ),
                  child: Center(
                    child: Text(
                      comment.authorName[0].toUpperCase(),
                      style: const TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.bold),
                    ),
                  ),
                ),
                const SizedBox(width: 8),
                Text(
                  comment.authorName,
                  style: const TextStyle(
                    fontWeight: FontWeight.w600,
                    fontSize: 14,
                    color: Colors.white,
                  ),
                ),
                const Spacer(),
                Text(
                  _formatDate(comment.createdAt),
                  style: const TextStyle(fontSize: 11, color: AppTheme.textMuted),
                ),
              ],
            ),
            const SizedBox(height: 8),
            Text(
              comment.body,
              style: const TextStyle(fontSize: 14, height: 1.4, color: AppTheme.textGray),
            ),
          ],
        ),
      ),
    );
  }

  String _formatDate(String dateStr) {
    try {
      final date = DateTime.parse(dateStr);
      return DateFormat('MMM dd, yyyy').format(date);
    } catch (_) {
      return dateStr;
    }
  }
}

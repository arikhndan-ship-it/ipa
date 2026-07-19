import 'package:flutter/material.dart';
import '../app/theme.dart';
import 'package:khandan_app/l10n/app_localizations.dart';
import 'package:url_launcher/url_launcher.dart';

class SettingsScreen extends StatefulWidget {
  final void Function(Locale locale)? onLocaleChanged;

  const SettingsScreen({
    super.key,
    this.onLocaleChanged,
  });

  @override
  State<SettingsScreen> createState() => _SettingsScreenState();
}

class _SettingsScreenState extends State<SettingsScreen> {
  Locale _currentLocale = const Locale('ckb');

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    _currentLocale = Localizations.localeOf(context);
  }

  void _openWebsite() {
    final uri = Uri.parse('https://khandantelegraph.news');
    launchUrl(uri, mode: LaunchMode.externalApplication).catchError((_) => false);
  }

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context)!;

    return ListView(
      physics: const ClampingScrollPhysics(),
      padding: const EdgeInsets.all(16),
      children: [
        // Language Section
        Container(
          decoration: BoxDecoration(
            color: AppTheme.bgCard,
            border: Border.all(color: AppTheme.borderGray),
          ),
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    Icon(Icons.language, color: AppTheme.primaryColor, size: 20),
                    const SizedBox(width: 8),
                    Text(
                      l10n.language,
                      style: const TextStyle(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                        color: Colors.white,
                        fontFamily: 'Serif',
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                _LanguageTile(
                  title: 'English',
                  subtitle: l10n.english,
                  isSelected: _currentLocale.languageCode == 'en',
                  onTap: () => _changeLocale(const Locale('en')),
                ),
                const SizedBox(height: 4),
                _LanguageTile(
                  title: 'کوردی',
                  subtitle: l10n.kurdish,
                  isSelected: _currentLocale.languageCode == 'ckb',
                  onTap: () => _changeLocale(const Locale('ckb')),
                ),
              ],
            ),
          ),
        ),
        const SizedBox(height: 16),

        // App Info
        Container(
          decoration: BoxDecoration(
            color: AppTheme.bgCard,
            border: Border.all(color: AppTheme.borderGray),
          ),
          child: ListTile(
            leading: const Icon(Icons.info_outline, color: AppTheme.primaryColor),
            title: Text(l10n.about,
              style: const TextStyle(color: Colors.white)),
            subtitle: Text('${l10n.version}: 1.0.0',
              style: const TextStyle(color: AppTheme.textGray)),
          ),
        ),
        const SizedBox(height: 16),

        // Secure Contact Section
        Container(
          decoration: BoxDecoration(
            color: AppTheme.bgCard,
            border: Border.all(color: AppTheme.borderGray),
          ),
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    const Icon(Icons.lock, color: AppTheme.primaryColor, size: 20),
                    const SizedBox(width: 8),
                    Text(
                      l10n.secureContact,
                      style: const TextStyle(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                        color: Colors.white,
                        fontFamily: 'Serif',
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                Container(
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: AppTheme.primaryColor.withValues(alpha: 0.1),
                    border: Border.all(color: AppTheme.primaryColor.withValues(alpha: 0.3)),
                  ),
                  child: Row(
                    children: [
                      const Icon(Icons.shield, color: AppTheme.primaryColor, size: 20),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              l10n.identityProtection,
                              style: const TextStyle(
                                fontWeight: FontWeight.w600,
                                fontSize: 13,
                                color: Colors.white,
                              ),
                            ),
                            const SizedBox(height: 2),
                            Text(
                              l10n.identityProtectionDesc,
                              style: const TextStyle(
                                fontSize: 12,
                                color: AppTheme.textGray,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 12),
                _ContactMethodTile(
                  icon: Icons.email,
                  label: l10n.secureEmail,
                  value: 'khandatelegraph@gmail.com',
                ),
                _ContactMethodTile(
                  icon: Icons.chat,
                  label: l10n.telegram,
                  value: '@khandantelegraph',
                ),
              ],
            ),
          ),
        ),
        const SizedBox(height: 16),

        // Visit Website
        Container(
          decoration: BoxDecoration(
            color: AppTheme.bgCard,
            border: Border.all(color: AppTheme.borderGray),
          ),
          child: ListTile(
            leading: const Icon(Icons.open_in_new, color: AppTheme.primaryColor),
            title: Text(l10n.visitWebsite,
              style: const TextStyle(color: Colors.white)),
            subtitle: const Text('https://khandantelegraph.news',
              style: TextStyle(color: AppTheme.textGray)),
            onTap: _openWebsite,
          ),
        ),
        const SizedBox(height: 16),

        // Privacy Policy
        Container(
          decoration: BoxDecoration(
            color: AppTheme.bgCard,
            border: Border.all(color: AppTheme.borderGray),
          ),
          child: ListTile(
            leading: const Icon(Icons.privacy_tip_outlined, color: AppTheme.primaryColor),
            title: Text(l10n.privacyPolicy,
              style: const TextStyle(color: Colors.white)),
            subtitle: const Text('khandantelegraph.news/privacy',
              style: TextStyle(color: AppTheme.textGray)),
            onTap: () {
              final uri = Uri.parse('https://khandantelegraph.news/privacy');
              launchUrl(uri, mode: LaunchMode.externalApplication)
                  .catchError((_) => false);
            },
          ),
        ),
        const SizedBox(height: 16),
      ],
    );
  }

  void _changeLocale(Locale locale) {
    setState(() => _currentLocale = locale);
    widget.onLocaleChanged?.call(locale);
  }
}

class _LanguageTile extends StatelessWidget {
  final String title;
  final String subtitle;
  final bool isSelected;
  final VoidCallback onTap;

  const _LanguageTile({
    required this.title,
    required this.subtitle,
    required this.isSelected,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
        decoration: BoxDecoration(
          color: isSelected ? AppTheme.primaryColor.withValues(alpha: 0.15) : AppTheme.bgMuted,
          border: Border.all(
            color: isSelected ? AppTheme.primaryColor : AppTheme.borderGray,
          ),
        ),
        child: Row(
          children: [
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    title,
                    style: const TextStyle(
                      fontWeight: FontWeight.w600,
                      fontSize: 14,
                      color: Colors.white,
                    ),
                  ),
                  Text(
                    subtitle,
                    style: const TextStyle(
                      fontSize: 12,
                      color: AppTheme.textGray,
                    ),
                  ),
                ],
              ),
            ),
            Icon(
              isSelected ? Icons.check_circle : Icons.radio_button_unchecked,
              color: isSelected ? AppTheme.primaryColor : AppTheme.textMuted,
              size: 22,
            ),
          ],
        ),
      ),
    );
  }
}

class _ContactMethodTile extends StatelessWidget {
  final IconData icon;
  final String label;
  final String value;

  const _ContactMethodTile({
    required this.icon,
    required this.label,
    required this.value,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
      decoration: BoxDecoration(
        color: AppTheme.bgMuted,
        border: Border.all(color: AppTheme.borderGray),
      ),
      child: Row(
        children: [
          Icon(icon, size: 18, color: AppTheme.primaryColor),
          const SizedBox(width: 10),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                label,
                style: const TextStyle(fontSize: 11, color: AppTheme.textMuted),
              ),
              Text(
                value,
                style: const TextStyle(
                  fontSize: 13,
                  fontWeight: FontWeight.w500,
                  color: Colors.white,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

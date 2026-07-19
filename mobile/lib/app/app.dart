import 'package:flutter/material.dart';
import '../screens/home_screen.dart';
import '../screens/reports_screen.dart';
import '../screens/about_screen.dart';
import '../screens/settings_screen.dart';
import '../main.dart';
import '../services/api_service.dart';
import 'theme.dart';
import 'package:khandan_app/l10n/app_localizations.dart';
import 'package:url_launcher/url_launcher.dart';

class App extends StatefulWidget {
  const App({super.key});

  @override
  State<App> createState() => _AppState();
}

class _AppState extends State<App> {
  Locale _appLocale = const Locale('ckb');
  int _currentPage = 0;

  void _onLocaleChanged(Locale locale) {
    ApiService.setLocale(locale.languageCode);
    KhandanApp.of(context)?.setLocale(locale);
    setState(() => _appLocale = locale);
  }

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context)!;

    return Scaffold(
      body: _buildPage(l10n),
      bottomNavigationBar: _buildBottomNav(l10n),
    );
  }

  Widget _buildBottomNav(AppLocalizations l10n) {
    final activeColor = AppTheme.primaryColor;
    final inactiveColor = AppTheme.textMuted;
    final items = [
      _NavItem(Icons.home_outlined, Icons.home, l10n.home, 0),
      _NavItem(Icons.article_outlined, Icons.article, l10n.reports, 1),
      _NavItem(Icons.contact_mail_outlined, Icons.contact_mail, l10n.contactUs, 2),
      _NavItem(Icons.info_outline, Icons.info, l10n.aboutUs, 3),
      _NavItem(Icons.settings_outlined, Icons.settings, l10n.settings, 4),
    ];

    return Container(
      decoration: const BoxDecoration(
        color: Colors.black,
        border: Border(
          top: BorderSide(color: AppTheme.primaryColor, width: 2),
        ),
      ),
      child: SafeArea(
        top: false,
        child: Padding(
          padding: const EdgeInsets.symmetric(vertical: 4),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: items.map((item) {
              final selected = _currentPage == item.index;
              return Expanded(
                child: GestureDetector(
                  onTap: () {
                    if (item.index == 2) {
                      // Contact - open in browser with correct language
                      final locale = _appLocale.languageCode;
                      final baseUrl = locale == 'en'
                          ? 'https://khandantelegraph.news/contact?locale=en'
                          : 'https://khandantelegraph.news/contact?locale=ckb';
                      launchUrl(Uri.parse(baseUrl), mode: LaunchMode.externalApplication);
                    } else {
                      setState(() => _currentPage = item.index);
                    }
                  },
                  behavior: HitTestBehavior.opaque,
                  child: Container(
                    padding: const EdgeInsets.symmetric(vertical: 6),
                    decoration: BoxDecoration(
                      border: Border(
                        top: BorderSide(
                          color: selected ? activeColor : Colors.transparent,
                          width: 2,
                        ),
                      ),
                    ),
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(
                          selected ? item.selectedIcon : item.icon,
                          color: selected ? activeColor : inactiveColor,
                          size: 22,
                        ),
                        const SizedBox(height: 2),
                        Text(
                          item.label,
                          style: TextStyle(
                            fontSize: 10,
                            fontWeight: selected ? FontWeight.w600 : FontWeight.w400,
                            color: selected ? activeColor : inactiveColor,
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              );
            }).toList(),
          ),
        ),
      ),
    );
  }

  Widget _buildPage(AppLocalizations l10n) {
    switch (_currentPage) {
      case 1:
        return ReportsScreen(key: ValueKey('reports_${_appLocale.languageCode}'));
      case 3:
        return AboutScreen(key: ValueKey('about_${_appLocale.languageCode}'));
      case 4:
        return SettingsScreen(
          key: ValueKey('settings_${_appLocale.languageCode}'),
          onLocaleChanged: _onLocaleChanged,
        );
      default:
        return HomeScreen(
          key: ValueKey('home_${_appLocale.languageCode}'),
          onLocaleChanged: _onLocaleChanged,
          currentLocale: _appLocale,
          onNavigateToReports: () => setState(() => _currentPage = 1),
        );
    }
  }
}

class _NavItem {
  final IconData icon;
  final IconData selectedIcon;
  final String label;
  final int index;
  const _NavItem(this.icon, this.selectedIcon, this.label, this.index);
}

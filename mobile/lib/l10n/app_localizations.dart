import 'dart:async';

import 'package:flutter/foundation.dart';
import 'package:flutter/widgets.dart';
import 'package:flutter_localizations/flutter_localizations.dart';
import 'package:intl/intl.dart' as intl;

import 'app_localizations_ckb.dart';
import 'app_localizations_en.dart';

// ignore_for_file: type=lint

/// Callers can lookup localized strings with an instance of AppLocalizations
/// returned by `AppLocalizations.of(context)`.
///
/// Applications need to include `AppLocalizations.delegate()` in their app's
/// `localizationDelegates` list, and the locales they support in the app's
/// `supportedLocales` list. For example:
///
/// ```dart
/// import 'l10n/app_localizations.dart';
///
/// return MaterialApp(
///   localizationsDelegates: AppLocalizations.localizationsDelegates,
///   supportedLocales: AppLocalizations.supportedLocales,
///   home: MyApplicationHome(),
/// );
/// ```
///
/// ## Update pubspec.yaml
///
/// Please make sure to update your pubspec.yaml to include the following
/// packages:
///
/// ```yaml
/// dependencies:
///   # Internationalization support.
///   flutter_localizations:
///     sdk: flutter
///   intl: any # Use the pinned version from flutter_localizations
///
///   # Rest of dependencies
/// ```
///
/// ## iOS Applications
///
/// iOS applications define key application metadata, including supported
/// locales, in an Info.plist file that is built into the application bundle.
/// To configure the locales supported by your app, you’ll need to edit this
/// file.
///
/// First, open your project’s ios/Runner.xcworkspace Xcode workspace file.
/// Then, in the Project Navigator, open the Info.plist file under the Runner
/// project’s Runner folder.
///
/// Next, select the Information Property List item, select Add Item from the
/// Editor menu, then select Localizations from the pop-up menu.
///
/// Select and expand the newly-created Localizations item then, for each
/// locale your application supports, add a new item and select the locale
/// you wish to add from the pop-up menu in the Value field. This list should
/// be consistent with the languages listed in the AppLocalizations.supportedLocales
/// property.
abstract class AppLocalizations {
  AppLocalizations(String locale)
    : localeName = intl.Intl.canonicalizedLocale(locale.toString());

  final String localeName;

  static AppLocalizations? of(BuildContext context) {
    return Localizations.of<AppLocalizations>(context, AppLocalizations);
  }

  static const LocalizationsDelegate<AppLocalizations> delegate =
      _AppLocalizationsDelegate();

  /// A list of this localizations delegate along with the default localizations
  /// delegates.
  ///
  /// Returns a list of localizations delegates containing this delegate along with
  /// GlobalMaterialLocalizations.delegate, GlobalCupertinoLocalizations.delegate,
  /// and GlobalWidgetsLocalizations.delegate.
  ///
  /// Additional delegates can be added by appending to this list in
  /// MaterialApp. This list does not have to be used at all if a custom list
  /// of delegates is preferred or required.
  static const List<LocalizationsDelegate<dynamic>> localizationsDelegates =
      <LocalizationsDelegate<dynamic>>[
        delegate,
        GlobalMaterialLocalizations.delegate,
        GlobalCupertinoLocalizations.delegate,
        GlobalWidgetsLocalizations.delegate,
      ];

  /// A list of this localizations delegate's supported locales.
  static const List<Locale> supportedLocales = <Locale>[
    Locale('ckb'),
    Locale('en'),
  ];

  /// No description provided for @appName.
  ///
  /// In en, this message translates to:
  /// **'Khandantelegraph'**
  String get appName;

  /// No description provided for @siteSubtitle.
  ///
  /// In en, this message translates to:
  /// **'Human Rights in Iranian Kurdistan'**
  String get siteSubtitle;

  /// No description provided for @home.
  ///
  /// In en, this message translates to:
  /// **'Home'**
  String get home;

  /// No description provided for @categories.
  ///
  /// In en, this message translates to:
  /// **'Categories'**
  String get categories;

  /// No description provided for @search.
  ///
  /// In en, this message translates to:
  /// **'Search'**
  String get search;

  /// No description provided for @settings.
  ///
  /// In en, this message translates to:
  /// **'Settings'**
  String get settings;

  /// No description provided for @latestNews.
  ///
  /// In en, this message translates to:
  /// **'Latest News'**
  String get latestNews;

  /// No description provided for @mainReports.
  ///
  /// In en, this message translates to:
  /// **'Featured Reports'**
  String get mainReports;

  /// No description provided for @allReports.
  ///
  /// In en, this message translates to:
  /// **'View All Reports'**
  String get allReports;

  /// No description provided for @breakingNews.
  ///
  /// In en, this message translates to:
  /// **'Breaking News'**
  String get breakingNews;

  /// No description provided for @readMore.
  ///
  /// In en, this message translates to:
  /// **'Read Full Report'**
  String get readMore;

  /// No description provided for @noArticles.
  ///
  /// In en, this message translates to:
  /// **'No articles found'**
  String get noArticles;

  /// No description provided for @loading.
  ///
  /// In en, this message translates to:
  /// **'Loading...'**
  String get loading;

  /// No description provided for @error.
  ///
  /// In en, this message translates to:
  /// **'An error occurred'**
  String get error;

  /// No description provided for @retry.
  ///
  /// In en, this message translates to:
  /// **'Retry'**
  String get retry;

  /// No description provided for @comments.
  ///
  /// In en, this message translates to:
  /// **'Comments'**
  String get comments;

  /// No description provided for @leaveComment.
  ///
  /// In en, this message translates to:
  /// **'Leave a Comment'**
  String get leaveComment;

  /// No description provided for @yourName.
  ///
  /// In en, this message translates to:
  /// **'Name (Optional)'**
  String get yourName;

  /// No description provided for @yourEmail.
  ///
  /// In en, this message translates to:
  /// **'Email or Phone Number'**
  String get yourEmail;

  /// No description provided for @yourMessage.
  ///
  /// In en, this message translates to:
  /// **'Your Information / Message'**
  String get yourMessage;

  /// No description provided for @send.
  ///
  /// In en, this message translates to:
  /// **'Send Information'**
  String get send;

  /// No description provided for @share.
  ///
  /// In en, this message translates to:
  /// **'Share'**
  String get share;

  /// No description provided for @relatedArticles.
  ///
  /// In en, this message translates to:
  /// **'Related Reports'**
  String get relatedArticles;

  /// No description provided for @language.
  ///
  /// In en, this message translates to:
  /// **'Language'**
  String get language;

  /// No description provided for @english.
  ///
  /// In en, this message translates to:
  /// **'English'**
  String get english;

  /// No description provided for @kurdish.
  ///
  /// In en, this message translates to:
  /// **'کوردی'**
  String get kurdish;

  /// No description provided for @about.
  ///
  /// In en, this message translates to:
  /// **'About Khandantelegraph'**
  String get about;

  /// No description provided for @allRightsReserved.
  ///
  /// In en, this message translates to:
  /// **'All rights reserved'**
  String get allRightsReserved;

  /// No description provided for @breaking.
  ///
  /// In en, this message translates to:
  /// **'Breaking'**
  String get breaking;

  /// No description provided for @byAuthor.
  ///
  /// In en, this message translates to:
  /// **'By'**
  String get byAuthor;

  /// No description provided for @views.
  ///
  /// In en, this message translates to:
  /// **'views'**
  String get views;

  /// No description provided for @noResults.
  ///
  /// In en, this message translates to:
  /// **'No results found'**
  String get noResults;

  /// No description provided for @searchArticles.
  ///
  /// In en, this message translates to:
  /// **'Search reports...'**
  String get searchArticles;

  /// No description provided for @commentSuccess.
  ///
  /// In en, this message translates to:
  /// **'Comment posted successfully!'**
  String get commentSuccess;

  /// No description provided for @noComments.
  ///
  /// In en, this message translates to:
  /// **'No comments yet'**
  String get noComments;

  /// No description provided for @version.
  ///
  /// In en, this message translates to:
  /// **'Version'**
  String get version;

  /// No description provided for @visitWebsite.
  ///
  /// In en, this message translates to:
  /// **'Visit Website'**
  String get visitWebsite;

  /// No description provided for @secureContact.
  ///
  /// In en, this message translates to:
  /// **'Secure Contact'**
  String get secureContact;

  /// No description provided for @identityProtection.
  ///
  /// In en, this message translates to:
  /// **'Identity Protection'**
  String get identityProtection;

  /// No description provided for @identityProtectionDesc.
  ///
  /// In en, this message translates to:
  /// **'We place great importance on the safety of our sources. All personal information is kept fully confidential and only the content of the news is published.'**
  String get identityProtectionDesc;

  /// No description provided for @secureEmail.
  ///
  /// In en, this message translates to:
  /// **'Secure Email'**
  String get secureEmail;

  /// No description provided for @signal.
  ///
  /// In en, this message translates to:
  /// **'Signal'**
  String get signal;

  /// No description provided for @telegram.
  ///
  /// In en, this message translates to:
  /// **'Telegram'**
  String get telegram;

  /// No description provided for @founderTitle.
  ///
  /// In en, this message translates to:
  /// **'Founder & Editor-in-Chief'**
  String get founderTitle;

  /// No description provided for @founderName.
  ///
  /// In en, this message translates to:
  /// **'Ari Mohamad Khandani'**
  String get founderName;

  /// No description provided for @aboutDescription.
  ///
  /// In en, this message translates to:
  /// **'We are the voice of the silenced — an independent journalism platform that courageously documents the crimes of the Iranian regime against the Kurdish people and stands against media censorship.'**
  String get aboutDescription;

  /// No description provided for @principle1Title.
  ///
  /// In en, this message translates to:
  /// **'1) Exposing Crimes'**
  String get principle1Title;

  /// No description provided for @principle1Desc.
  ///
  /// In en, this message translates to:
  /// **'We meticulously and fearlessly document every human rights violation committed by the Iranian regime against civilians, and bring them to the world\'s attention.'**
  String get principle1Desc;

  /// No description provided for @principle2Title.
  ///
  /// In en, this message translates to:
  /// **'2) Against Censorship'**
  String get principle2Title;

  /// No description provided for @principle2Desc.
  ///
  /// In en, this message translates to:
  /// **'In the face of government-imposed media blackouts and severe censorship, we stand as a fortress of information, refusing to let the truth be buried.'**
  String get principle2Desc;

  /// No description provided for @principle3Title.
  ///
  /// In en, this message translates to:
  /// **'3) Voice of Kurdistan'**
  String get principle3Title;

  /// No description provided for @principle3Desc.
  ///
  /// In en, this message translates to:
  /// **'We become a megaphone for the suppressed voice of the Kurdish people — for activists, political prisoners, and the families of those they have lost.'**
  String get principle3Desc;

  /// No description provided for @aboutStory.
  ///
  /// In en, this message translates to:
  /// **'Journalist and human rights activist Ari Mohamad Khandani founded Khandantelegraph as a direct response to the relentless oppression carried out daily on the soil of Iranian Kurdistan. Having witnessed firsthand the suffering of his people and the Iranian regime\'s brutal censorship, Ari resolved to build a platform that doesn\'t merely report the news — but serves as a historical archive of documented crimes against the Kurdish people. His commitment is unwavering: to give a voice to the voiceless, to refuse complicity in injustice, and to ensure that truth is never extinguished in silence.'**
  String get aboutStory;

  /// No description provided for @aboutQuote.
  ///
  /// In en, this message translates to:
  /// **'\"Silence in the face of oppression is participation in the crime. We are here to ensure the truth is never buried in the dark.\"'**
  String get aboutQuote;

  /// No description provided for @reports.
  ///
  /// In en, this message translates to:
  /// **'Reports'**
  String get reports;

  /// No description provided for @contactUs.
  ///
  /// In en, this message translates to:
  /// **'Contact'**
  String get contactUs;

  /// No description provided for @aboutUs.
  ///
  /// In en, this message translates to:
  /// **'About Us'**
  String get aboutUs;

  /// No description provided for @arrests.
  ///
  /// In en, this message translates to:
  /// **'Arrests'**
  String get arrests;

  /// No description provided for @humanRights.
  ///
  /// In en, this message translates to:
  /// **'Human Rights'**
  String get humanRights;

  /// No description provided for @courts.
  ///
  /// In en, this message translates to:
  /// **'Courts'**
  String get courts;

  /// No description provided for @prison.
  ///
  /// In en, this message translates to:
  /// **'Prison'**
  String get prison;

  /// No description provided for @freedom.
  ///
  /// In en, this message translates to:
  /// **'Freedom Demands'**
  String get freedom;

  /// No description provided for @economy.
  ///
  /// In en, this message translates to:
  /// **'Economy'**
  String get economy;

  /// No description provided for @iranRegime.
  ///
  /// In en, this message translates to:
  /// **'Iranian Regime'**
  String get iranRegime;

  /// No description provided for @contactTitle.
  ///
  /// In en, this message translates to:
  /// **'Contact'**
  String get contactTitle;

  /// No description provided for @contactSubtitle.
  ///
  /// In en, this message translates to:
  /// **'Do you have information or evidence of human rights violations? Send it to us securely. Your identity will remain confidential.'**
  String get contactSubtitle;

  /// No description provided for @contactFormTitle.
  ///
  /// In en, this message translates to:
  /// **'Submit a Tip or Report'**
  String get contactFormTitle;

  /// No description provided for @contactFormName.
  ///
  /// In en, this message translates to:
  /// **'Name (Optional)'**
  String get contactFormName;

  /// No description provided for @contactFormEmail.
  ///
  /// In en, this message translates to:
  /// **'Email or Phone Number'**
  String get contactFormEmail;

  /// No description provided for @contactFormSubject.
  ///
  /// In en, this message translates to:
  /// **'Subject'**
  String get contactFormSubject;

  /// No description provided for @contactFormMessage.
  ///
  /// In en, this message translates to:
  /// **'Your Information / Message'**
  String get contactFormMessage;

  /// No description provided for @contactFormSend.
  ///
  /// In en, this message translates to:
  /// **'Send Information'**
  String get contactFormSend;

  /// No description provided for @pleaseFillAllFields.
  ///
  /// In en, this message translates to:
  /// **'Please fill all fields'**
  String get pleaseFillAllFields;

  /// No description provided for @readMoreAt.
  ///
  /// In en, this message translates to:
  /// **'Read more at Khandantelegraph'**
  String get readMoreAt;

  /// No description provided for @secureEmailValue.
  ///
  /// In en, this message translates to:
  /// **'khandatelegraph@gmail.com'**
  String get secureEmailValue;

  /// No description provided for @privacyPolicy.
  ///
  /// In en, this message translates to:
  /// **'Privacy Policy'**
  String get privacyPolicy;

  /// No description provided for @telegramValue.
  ///
  /// In en, this message translates to:
  /// **'@khandantelegraph'**
  String get telegramValue;

  /// No description provided for @messageSent.
  ///
  /// In en, this message translates to:
  /// **'Your information was sent successfully!'**
  String get messageSent;

  /// No description provided for @messageSentDesc.
  ///
  /// In en, this message translates to:
  /// **'Thank you for your contribution. Your information will remain confidential with us.'**
  String get messageSentDesc;

  /// No description provided for @notifications.
  ///
  /// In en, this message translates to:
  /// **'Notifications'**
  String get notifications;

  /// No description provided for @noNotifications.
  ///
  /// In en, this message translates to:
  /// **'No notifications yet'**
  String get noNotifications;

  /// No description provided for @errorLoading.
  ///
  /// In en, this message translates to:
  /// **'Failed to load content'**
  String get errorLoading;

  /// No description provided for @notificationOpened.
  ///
  /// In en, this message translates to:
  /// **'Notification opened'**
  String get notificationOpened;
}

class _AppLocalizationsDelegate
    extends LocalizationsDelegate<AppLocalizations> {
  const _AppLocalizationsDelegate();

  @override
  Future<AppLocalizations> load(Locale locale) {
    return SynchronousFuture<AppLocalizations>(lookupAppLocalizations(locale));
  }

  @override
  bool isSupported(Locale locale) =>
      <String>['ckb', 'en'].contains(locale.languageCode);

  @override
  bool shouldReload(_AppLocalizationsDelegate old) => false;
}

AppLocalizations lookupAppLocalizations(Locale locale) {
  // Lookup logic when only language code is specified.
  switch (locale.languageCode) {
    case 'ckb':
      return AppLocalizationsCkb();
    case 'en':
      return AppLocalizationsEn();
  }

  throw FlutterError(
    'AppLocalizations.delegate failed to load unsupported locale "$locale". This is likely '
    'an issue with the localizations generation tool. Please file an issue '
    'on GitHub with a reproducible sample app and the gen-l10n configuration '
    'that was used.',
  );
}

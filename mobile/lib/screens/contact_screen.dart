import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:khandan_app/l10n/app_localizations.dart';
import 'package:url_launcher/url_launcher.dart';

class ContactScreen extends StatefulWidget {
  const ContactScreen({super.key});

  @override
  State<ContactScreen> createState() => _ContactScreenState();
}

class _ContactScreenState extends State<ContactScreen> {
  final _formKey = GlobalKey<FormState>();
  final _nameCtl = TextEditingController();
  final _emailCtl = TextEditingController();
  final _subjectCtl = TextEditingController();
  final _messageCtl = TextEditingController();
  bool _sending = false;
  bool _sent = false;
  String? _error;

  @override
  void dispose() {
    _nameCtl.dispose();
    _emailCtl.dispose();
    _subjectCtl.dispose();
    _messageCtl.dispose();
    super.dispose();
  }

  void _openUrl(String url) {
    launchUrl(Uri.parse(url), mode: LaunchMode.externalApplication).catchError((_) {});
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() { _sending = true; _error = null; });
    try {
      final loc = Localizations.localeOf(context).languageCode;
      final res = await http.post(
        Uri.parse('https://khandantelegraph.news/api/v1/contact'),
        headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'Accept-Language': loc},
        body: jsonEncode({
          'name': _nameCtl.text.trim(), 'email': _emailCtl.text.trim(),
          'subject': _subjectCtl.text.trim(), 'message': _messageCtl.text.trim(),
        }),
      );
      if (!mounted) return;
      if (res.statusCode == 200) {
        setState(() { _sent = true; });
        _nameCtl.clear(); _emailCtl.clear(); _subjectCtl.clear(); _messageCtl.clear();
      } else {
        String msg = 'Error';
        try {
          final d = jsonDecode(res.body);
          msg = d['message'] ?? msg;
        } catch (_) {}
        if (mounted) setState(() { _error = msg; });
      }
    } catch (_) {
      if (mounted) setState(() { _error = 'Connection error'; });
    } finally {
      if (mounted) setState(() { _sending = false; });
    }
  }

  @override
  Widget build(BuildContext context) {
    final t = AppLocalizations.of(context)!;
    return ListView(
      padding: EdgeInsets.zero,
      children: [
        // Header
        Container(
          width: double.infinity,
          color: Colors.black,
          padding: const EdgeInsets.fromLTRB(16, 40, 16, 24),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(t.contactTitle, style: const TextStyle(fontSize: 28, fontWeight: FontWeight.bold, color: Colors.white, fontFamily: 'Serif')),
              const SizedBox(height: 6),
              Text(t.contactSubtitle, style: const TextStyle(fontSize: 13, color: Colors.grey)),
            ],
          ),
        ),
        // Red line
        Container(height: 4, width: double.infinity, color: const Color(0xFFCC0000)),
        // Body
        Container(
          width: double.infinity,
          color: const Color(0xFF1A1A1A),
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Error
              if (_error != null)
                Container(
                  width: double.infinity,
                  padding: const EdgeInsets.all(12),
                  margin: const EdgeInsets.only(bottom: 16),
                  color: const Color(0x33FF0000),
                  child: Text(_error!, style: const TextStyle(color: Colors.redAccent, fontSize: 13)),
                ),
              // Success
              if (_sent)
                Container(
                  width: double.infinity,
                  padding: const EdgeInsets.all(24),
                  margin: const EdgeInsets.only(bottom: 16),
                  color: const Color(0x3300FF00),
                  child: Column(
                    children: [
                      const Icon(Icons.check_circle, color: Colors.green, size: 48),
                      const SizedBox(height: 12),
                      Text(t.messageSent, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.green), textAlign: TextAlign.center),
                      const SizedBox(height: 8),
                      Text(t.messageSentDesc, style: const TextStyle(fontSize: 13, color: Colors.green), textAlign: TextAlign.center),
                    ],
                  ),
                ),
              // Form
              if (!_sent)
                Container(
                  width: double.infinity,
                  padding: const EdgeInsets.all(20),
                  color: const Color(0xFF222222),
                  child: Form(
                    key: _formKey,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Container(height: 4, width: 80, color: const Color(0xFFCC0000)),
                        const SizedBox(height: 16),
                        Text(t.contactFormTitle, style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white, fontFamily: 'Serif')),
                        const SizedBox(height: 24),
                        // Name
                        Text(t.contactFormName, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: Color(0xFFBBBBBB))),
                        const SizedBox(height: 6),
                        TextFormField(
                          controller: _nameCtl,
                          style: const TextStyle(color: Colors.white, fontSize: 14),
                          decoration: const InputDecoration(filled: true, fillColor: Color(0xFF333333), border: OutlineInputBorder(borderSide: BorderSide.none), contentPadding: EdgeInsets.all(14)),
                          validator: (v) => (v == null || v.trim().isEmpty) ? t.pleaseFillAllFields : null,
                        ),
                        const SizedBox(height: 16),
                        // Email
                        Text(t.contactFormEmail, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: Color(0xFFBBBBBB))),
                        const SizedBox(height: 6),
                        TextFormField(
                          controller: _emailCtl,
                          keyboardType: TextInputType.emailAddress,
                          style: const TextStyle(color: Colors.white, fontSize: 14),
                          decoration: const InputDecoration(filled: true, fillColor: Color(0xFF333333), border: OutlineInputBorder(borderSide: BorderSide.none), contentPadding: EdgeInsets.all(14)),
                          validator: (v) => (v == null || v.trim().isEmpty) ? t.pleaseFillAllFields : null,
                        ),
                        const SizedBox(height: 16),
                        // Subject
                        Text(t.contactFormSubject, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: Color(0xFFBBBBBB))),
                        const SizedBox(height: 6),
                        TextFormField(
                          controller: _subjectCtl,
                          style: const TextStyle(color: Colors.white, fontSize: 14),
                          decoration: const InputDecoration(filled: true, fillColor: Color(0xFF333333), border: OutlineInputBorder(borderSide: BorderSide.none), contentPadding: EdgeInsets.all(14)),
                          validator: (v) => (v == null || v.trim().isEmpty) ? t.pleaseFillAllFields : null,
                        ),
                        const SizedBox(height: 16),
                        // Message
                        Text(t.contactFormMessage, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: Color(0xFFBBBBBB))),
                        const SizedBox(height: 6),
                        TextFormField(
                          controller: _messageCtl,
                          maxLines: 5, minLines: 4,
                          style: const TextStyle(color: Colors.white, fontSize: 14),
                          decoration: const InputDecoration(filled: true, fillColor: Color(0xFF333333), border: OutlineInputBorder(borderSide: BorderSide.none), contentPadding: EdgeInsets.all(14)),
                          validator: (v) => (v == null || v.trim().isEmpty) ? t.pleaseFillAllFields : null,
                        ),
                        const SizedBox(height: 24),
                        // Submit
                        SizedBox(
                          width: double.infinity,
                          child: ElevatedButton(
                            onPressed: _sending ? null : _submit,
                            style: ElevatedButton.styleFrom(
                              backgroundColor: const Color(0xFFCC0000),
                              foregroundColor: Colors.white,
                              padding: const EdgeInsets.symmetric(vertical: 16),
                              shape: const RoundedRectangleBorder(),
                              elevation: 0,
                            ),
                            child: _sending
                                ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                                : Row(
                                    mainAxisAlignment: MainAxisAlignment.center,
                                    children: [
                                      Text(t.contactFormSend, style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold, letterSpacing: 0.5)),
                                      const SizedBox(width: 8),
                                      const Icon(Icons.send, size: 16),
                                    ],
                                  ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              const SizedBox(height: 16),
              // Shield
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(16),
                color: const Color(0xFF0A0A0A),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Icon(Icons.shield, color: Color(0xFFCC0000), size: 28),
                    const SizedBox(height: 12),
                    Text(t.identityProtection, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.white, fontFamily: 'Serif')),
                    const SizedBox(height: 8),
                    Text(t.identityProtectionDesc, style: const TextStyle(fontSize: 12, color: Colors.grey, height: 1.4)),
                  ],
                ),
              ),
              const SizedBox(height: 12),
              // Contact methods
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(16),
                color: const Color(0xFF222222),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        const Icon(Icons.contact_mail, color: Color(0xFFCC0000), size: 20),
                        const SizedBox(width: 8),
                        Text(t.secureContact, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.white, fontFamily: 'Serif')),
                      ],
                    ),
                    const SizedBox(height: 16),
                    _ContactTile(Icons.email, t.secureEmail, t.secureEmailValue, () => _openUrl('mailto:khandatelegraph@gmail.com')),
                    const SizedBox(height: 4),
                    _ContactTile(Icons.chat, t.telegram, t.telegramValue, () => _openUrl('https://t.me/khandantelegraph')),
                  ],
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }
}

class _ContactTile extends StatelessWidget {
  final IconData icon;
  final String label;
  final String value;
  final VoidCallback? onTap;

  const _ContactTile(this.icon, this.label, this.value, this.onTap);

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: double.infinity,
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
        color: const Color(0xFF333333),
        child: Row(
          children: [
            Icon(icon, size: 18, color: const Color(0xFFCC0000)),
            const SizedBox(width: 10),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(label, style: const TextStyle(fontSize: 11, color: Colors.grey)),
                  Text(value, style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w500, color: Colors.white)),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

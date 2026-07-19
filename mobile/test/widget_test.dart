import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';

import 'package:khandan_app/main.dart';

void main() {
  testWidgets('App loads successfully', (WidgetTester tester) async {
    await tester.pumpWidget(const KhandanApp());
    expect(find.byType(MaterialApp), findsOneWidget);
  });
}

# Flutter ProGuard Rules
-keep class io.flutter.app.** { *; }
-keep class io.flutter.plugin.** { *; }
-keep class io.flutter.util.** { *; }
-keep class io.flutter.view.** { *; }
-keep class io.flutter.** { *; }
-keep class io.flutter.plugins.** { *; }
-keep class com.khandan.telegraph.** { *; }

# Keep JSON serialization
-keepattributes Signature
-keepattributes *Annotation*
-keep class com.google.gson.** { *; }
-keep class * implements com.google.gson.TypeAdapterFactory
-keep class * implements com.google.gson.JsonSerializer
-keep class * implements com.google.gson.JsonDeserializer

# Keep HTTP client
-keep class org.apache.http.** { *; }
-keep class okhttp3.** { *; }
-keep class retrofit2.** { *; }

# Play Core (used by Flutter internally)
-dontwarn com.google.android.play.core.**
-keep class com.google.android.play.core.** { *; }

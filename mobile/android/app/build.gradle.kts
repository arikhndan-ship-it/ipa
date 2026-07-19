plugins {
    id("com.android.application")
    id("dev.flutter.flutter-gradle-plugin")
    id("com.google.gms.google-services")
}

android {
    namespace = "com.khandan.telegraph"
    compileSdk = flutter.compileSdkVersion
    ndkVersion = flutter.ndkVersion

    compileOptions {
        sourceCompatibility = JavaVersion.VERSION_17
        targetCompatibility = JavaVersion.VERSION_17
        isCoreLibraryDesugaringEnabled = true
    }

    defaultConfig {
        applicationId = "com.khandan.telegraph"
        minSdk = flutter.minSdkVersion
        targetSdk = flutter.targetSdkVersion
        versionCode = 6
        versionName = "0.0.4"
    }

    signingConfigs {
        create("release") {
            val keystorePropsFile = rootProject.file("key.properties")
            if (keystorePropsFile.exists()) {
                val lines = keystorePropsFile.readLines()
                val props = mutableMapOf<String, String>()
                lines.forEach { line ->
                    val parts = line.split("=", limit = 2)
                    if (parts.size == 2) {
                        props[parts[0].trim()] = parts[1].trim()
                    }
                }
                storeFile = rootProject.file(props["storeFile"] ?: "upload-keystore.jks")
                storePassword = props["storePassword"]
                keyAlias = props["keyAlias"]
                keyPassword = props["keyPassword"]
            } else {
                signingConfigs.getByName("debug")
            }
        }
    }

    buildTypes {
        release {
            signingConfig = signingConfigs.getByName("release")
            isMinifyEnabled = false
            isShrinkResources = false
        }
    }
}

kotlin {
    compilerOptions {
        jvmTarget = org.jetbrains.kotlin.gradle.dsl.JvmTarget.JVM_17
    }
}

flutter {
    source = "../.."
}

dependencies {
    coreLibraryDesugaring("com.android.tools:desugar_jdk_libs:2.1.5")
}

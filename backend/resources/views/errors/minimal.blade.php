<!DOCTYPE html>
<html lang="{{ app()->getLocale() === 'ckb' ? 'ckb' : 'en' }}" dir="{{ app()->getLocale() === 'ckb' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Khandan | خەندان')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: #0A0A0A;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            padding: 24px;
        }
        .container { max-width: 480px; }
        h1 { font-size: 72px; color: #8B0000; font-weight: 700; margin-bottom: 16px; }
        p { color: #9CA3AF; font-size: 18px; line-height: 1.6; margin-bottom: 24px; }
        a {
            display: inline-block;
            background: #8B0000;
            color: #fff;
            padding: 12px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
        }
        a:hover { background: #CC0000; }
    </style>
</head>
<body>
    <div class="container">
        <h1>@yield('code')</h1>
        <p>@yield('message')</p>
        <a href="{{ url('/') }}">{{ app()->getLocale() === 'ckb' ? 'بگەڕێوە بۆ ماڵەوە' : 'Back to Home' }}</a>
    </div>
</body>
</html>

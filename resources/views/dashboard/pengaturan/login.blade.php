<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaturan dan Kendali Project - {{ $project->name }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            padding: 20px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            margin: 0;
        }
        .wrap { width: 100%; max-width: 384px; }
        h1 { font-size: 20px; font-weight: 700; margin: 0 0 4px 0; color: #0f172a; }
        .subtitle { color: #475569; margin: 0 0 20px 0; font-size: 14px; }
        label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #334155;
            margin-bottom: 6px;
        }
        .field { margin-bottom: 16px; }
        input[type="text"], input[type="password"] {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            outline: none;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #0f172a;
        }
        .btn-primary {
            width: 100%;
            background: #0f172a;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            margin-top: 4px;
        }
        .btn-primary:hover { background: #1e293b; }
        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            font-size: 13px;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 16px;
        }
        .back-link {
            display: block;
            text-align: center;
            font-size: 14px;
            color: #64748b;
            text-decoration: none;
            margin-top: 16px;
        }
        .back-link:hover { color: #0f172a; }
    </style>
    <link rel="stylesheet" href="{{ asset('css/site-responsive.css') }}?v={{ filemtime(public_path('css/site-responsive.css')) }}">
</head>
<body>
    <div class="wrap">
        <h1>Pengaturan dan Kendali Project</h1>
        <p class="subtitle">{{ $project->name }}</p>

        @if ($errors->any())
            <div class="error-box">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('dashboard.pengaturan.login', ['slug' => $project->slug]) }}">
            @csrf
            <div class="field">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary">Masuk</button>
        </form>

        <a href="{{ url($project->monitoring_path) }}" class="back-link">
            &larr; Kembali ke Monitoring
        </a>
    </div>
</body>
</html>

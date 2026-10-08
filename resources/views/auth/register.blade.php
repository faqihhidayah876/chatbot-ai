<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    <title>Daftar — SAHAJA AI</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600|jetbrains-mono:400,500" rel="stylesheet">
    <link rel="icon" type="image/png" href="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png">
    @include('partials.pwa')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Theme Init (Anti-FOUC) --}}
    <script>
        (function() {
            if (localStorage.getItem('sahaja-theme') === 'light') {
                document.documentElement.classList.add('light-mode');
            }
        })();
    </script>

    <style>
        :root {
            --bg-base: #0B0D12;
            --bg-subtle: #12151C;
            --bg-elevated: #1A1E27;
            --bg-overlay: #232833;
            --bg-hover: #1E2229;
            --border-subtle: rgba(255, 255, 255, 0.06);
            --border-medium: rgba(255, 255, 255, 0.10);
            --border-strong: rgba(255, 255, 255, 0.16);
            --text-primary: #E8EAED;
            --text-secondary: #9AA0A6;
            --text-tertiary: #5F6368;
            --accent: #3B82F6;
            --accent-hover: #60A5FA;
            --accent-subtle: hsl(217 91% 60% / 0.12);
            --accent-border: hsl(217 91% 60% / 0.30);
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --danger-subtle: rgba(239, 68, 68, 0.12);
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 20px;
            --radius-full: 9999px;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.30);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.35);
            --shadow-lg: 0 12px 32px rgba(0, 0, 0, 0.45);
            --ease: cubic-bezier(0.4, 0, 0.2, 1);
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --font-mono: 'JetBrains Mono', 'Fira Code', monospace;
        }

        body.light-mode,
        html.light-mode body {
            --bg-base: #FAFBFC;
            --bg-subtle: #F1F3F5;
            --bg-elevated: #FFFFFF;
            --bg-overlay: #FFFFFF;
            --bg-hover: #EEF1F5;
            --border-subtle: rgba(0, 0, 0, 0.06);
            --border-medium: rgba(0, 0, 0, 0.10);
            --border-strong: rgba(0, 0, 0, 0.16);
            --text-primary: #111827;
            --text-secondary: #4B5563;
            --text-tertiary: #9CA3AF;
            --accent: #2563EB;
            --accent-hover: #1D4ED8;
            --accent-subtle: hsl(221 83% 53% / 0.08);
            --accent-border: hsl(221 83% 53% / 0.25);
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.10);
            --shadow-lg: 0 12px 32px rgba(0, 0, 0, 0.14);
        }

        .preload * {
            transition: none !important;
        }

        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-base);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow-x: hidden;
            line-height: 1.5;
            transition: background-color 200ms var(--ease), color 200ms var(--ease);
        }

        /* Subtle radial ambient (opacity < 0.05) */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(circle at 15% 10%, rgba(37, 99, 235, 0.04) 0%, transparent 45%);
            pointer-events: none;
            z-index: -1;
        }

        /* Theme Toggle Button */
        .theme-toggle-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 36px;
            height: 36px;
            border-radius: var(--radius-md);
            background: var(--bg-subtle);
            border: 1px solid var(--border-subtle);
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 150ms var(--ease), color 150ms var(--ease), border-color 150ms var(--ease);
            z-index: 50;
        }

        .theme-toggle-btn:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
            border-color: var(--border-medium);
        }

        .theme-toggle-btn:focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
        }

        /* Card Container */
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-xl);
            padding: 40px 32px;
            text-align: center;
            box-shadow: var(--shadow-md);
            animation: cardFadeUp 500ms var(--ease) both;
        }

        @keyframes cardFadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-img {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-md);
            object-fit: contain;
            margin-bottom: 24px;
        }

        .auth-title {
            font-size: 24px;
            font-weight: 600;
            letter-spacing: -0.02em;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .auth-subtitle {
            font-size: 14px;
            font-weight: 400;
            color: var(--text-secondary);
            margin-bottom: 32px;
            line-height: 1.5;
        }

        /* Form */
        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 18px;
            text-align: left;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            height: 44px;
            padding: 0 14px;
            background: var(--bg-subtle);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-md);
            color: var(--text-primary);
            font-size: 15px;
            font-family: inherit;
            outline: none;
            transition: border-color 150ms var(--ease), background-color 150ms var(--ease);
        }

        .form-input::placeholder {
            color: var(--text-tertiary);
        }

        .form-input:focus {
            border-color: var(--accent);
            background-color: var(--bg-base);
        }

        .form-input.is-invalid {
            border-color: var(--danger);
        }

        .error-msg {
            font-size: 12px;
            color: var(--danger);
            margin-top: 4px;
        }

        /* Password Strength Bar (3 horizontal bars) */
        .password-strength {
            display: flex;
            gap: 6px;
            margin-top: 8px;
            height: 4px;
            width: 100%;
        }

        .strength-bar {
            flex: 1;
            height: 100%;
            background: var(--border-medium);
            border-radius: var(--radius-full);
            transition: background-color 200ms var(--ease);
        }

        .strength-bar.weak {
            background-color: var(--danger);
        }

        .strength-bar.medium {
            background-color: var(--warning);
        }

        .strength-bar.strong {
            background-color: var(--success);
        }

        .btn-submit {
            width: 100%;
            height: 44px;
            background: var(--accent);
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            margin-top: 8px;
            transition: background-color 150ms var(--ease), transform 150ms var(--ease);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-submit:hover {
            background: var(--accent-hover);
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        .btn-submit:focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
        }

        /* Footer Links */
        .auth-footer {
            margin-top: 24px;
            font-size: 13px;
            color: var(--text-secondary);
            text-align: center;
        }

        .auth-link {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
            transition: text-decoration 150ms var(--ease);
        }

        .auth-link:hover {
            text-decoration: underline;
        }

        .back-link {
            display: block;
            text-align: center;
            font-size: 13px;
            color: var(--text-tertiary);
            text-decoration: none;
            margin-top: 12px;
            transition: color 150ms var(--ease);
        }

        .back-link:hover {
            color: var(--text-primary);
        }

        /* Mobile */
        @media (max-width: 480px) {
            .auth-card {
                padding: 32px 24px;
            }
            .auth-title {
                font-size: 22px;
            }
        }

        /* Prefers-reduced-motion */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
            }
            .auth-card {
                animation: none !important;
            }
        }
    </style>
</head>
<body class="preload">
    {{-- Theme Toggle --}}
    <button type="button" class="theme-toggle-btn" id="themeToggleBtn" aria-label="Toggle tema tampilan" onclick="setTheme()">
        <i class="fas fa-moon" id="themeIcon"></i>
    </button>

    <div class="auth-card">
        <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="Logo SAHAJA AI" class="logo-img" loading="eager">
        <h1 class="auth-title">Buat Akun Baru</h1>
        <p class="auth-subtitle">Bergabung dengan SAHAJA AI sekarang</p>

        <form action="{{ route('register.post') }}" method="POST" class="auth-form" novalidate>
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autocomplete="name"
                    placeholder="Nama Kamu"
                    class="form-input @error('name') is-invalid @enderror"
                    @error('name') aria-invalid="true" aria-describedby="name-error" @enderror
                >
                @error('name')
                    <div class="error-msg" id="name-error" role="alert">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    placeholder="nama@email.com"
                    class="form-input @error('email') is-invalid @enderror"
                    @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                >
                @error('email')
                    <div class="error-msg" id="email-error" role="alert">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Minimal 8 karakter"
                    class="form-input @error('password') is-invalid @enderror"
                    @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
                >
                <div class="password-strength" id="passwordStrength" style="display: none;" aria-hidden="true">
                    <div class="strength-bar" id="strengthBar1"></div>
                    <div class="strength-bar" id="strengthBar2"></div>
                    <div class="strength-bar" id="strengthBar3"></div>
                </div>
                @error('password')
                    <div class="error-msg" id="password-error" role="alert">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Ulangi password"
                    class="form-input"
                >
            </div>

            <button type="submit" class="btn-submit">Daftar</button>
        </form>

        <div class="auth-footer">
            Sudah punya akun? <a href="{{ route('login') }}" class="auth-link">Masuk</a>
        </div>

        <a href="{{ route('home') }}" class="back-link">← Kembali ke Beranda</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.body.classList.remove('preload');
            if (localStorage.getItem('sahaja-theme') === 'light') {
                document.body.classList.add('light-mode');
                const themeIcon = document.getElementById('themeIcon');
                if (themeIcon) {
                    themeIcon.className = 'fas fa-sun';
                }
            }

            // Password strength meter
            const pwdInput = document.getElementById('password');
            const pwdStrength = document.getElementById('passwordStrength');
            const bar1 = document.getElementById('strengthBar1');
            const bar2 = document.getElementById('strengthBar2');
            const bar3 = document.getElementById('strengthBar3');

            if (pwdInput && pwdStrength) {
                pwdInput.addEventListener('input', () => {
                    const val = pwdInput.value;
                    if (!val) {
                        pwdStrength.style.display = 'none';
                        return;
                    }
                    pwdStrength.style.display = 'flex';
                    let score = 0;
                    if (val.length >= 8) score++;
                    if (/[0-9]/.test(val)) score++;
                    if (/[^A-Za-z0-9]/.test(val)) score++;

                    bar1.className = 'strength-bar';
                    bar2.className = 'strength-bar';
                    bar3.className = 'strength-bar';

                    if (score <= 1) {
                        bar1.classList.add('weak');
                    } else if (score === 2) {
                        bar1.classList.add('medium');
                        bar2.classList.add('medium');
                    } else {
                        bar1.classList.add('strong');
                        bar2.classList.add('strong');
                        bar3.classList.add('strong');
                    }
                });
            }
        });

        function setTheme() {
            const isLight = document.documentElement.classList.toggle('light-mode');
            document.body.classList.toggle('light-mode', isLight);
            localStorage.setItem('sahaja-theme', isLight ? 'light' : 'dark');
            const themeIcon = document.getElementById('themeIcon');
            if (themeIcon) {
                themeIcon.className = isLight ? 'fas fa-sun' : 'fas fa-moon';
            }
        }
    </script>
</body>
</html>

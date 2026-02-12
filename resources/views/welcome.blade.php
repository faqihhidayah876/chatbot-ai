<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SAHAJA AI - Selamat Datang</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="icon"
        href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🤖</text></svg>">

    <style>
        /* ===== THEME VARIABLES (SAMA DENGAN CHATBOT UI) ===== */
        :root {
            --main-bg: #0a0e17;
            --sidebar-bg: rgba(15, 23, 42, 0.95);
            --glass-bg: rgba(15, 27, 45, 0.7);
            --glass-border: rgba(98, 160, 234, 0.15);
            --glass-highlight: rgba(255, 255, 255, 0.05);
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --accent-color: #2563eb;
            --accent-gradient: linear-gradient(135deg, #2563eb, #06b6d4);
            --accent-light: #62a0ea;
        }

        body.light-mode {
            --main-bg: #ffffff;
            --sidebar-bg: #f8fafc;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: #e2e8f0;
            --glass-highlight: #f1f5f9;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --accent-gradient: linear-gradient(135deg, #2563eb, #3b82f6);
        }

        /* ===== GLOBAL RESET ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--main-bg);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            transition: background 0.3s, color 0.3s;
        }

        /* Background pattern */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 20% 30%, rgba(37, 99, 235, 0.15) 0%, transparent 60%),
                radial-gradient(circle at 80% 70%, rgba(6, 182, 212, 0.12) 0%, transparent 60%),
                radial-gradient(circle at 40% 80%, rgba(37, 99, 235, 0.1) 0%, transparent 70%);
            z-index: -1;
            pointer-events: none;
        }

        body.light-mode::before {
            background: radial-gradient(circle at 20% 30%, rgba(37, 99, 235, 0.05) 0%, transparent 60%),
                radial-gradient(circle at 80% 70%, rgba(6, 182, 212, 0.05) 0%, transparent 60%);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button {
            cursor: pointer;
            border: none;
            background: none;
            font-family: inherit;
        }

        /* ===== LAYOUT ===== */
        .welcome-container {
            width: 100%;
            max-width: 1200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2.5rem;
            z-index: 10;
        }

        /* HEADER */
        .welcome-header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 1.2rem;
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 60px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            background: var(--accent-gradient);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.4);
        }

        .brand-text {
            font-size: 1.4rem;
            font-weight: 700;
            background: linear-gradient(145deg, #ffffff, var(--accent-light));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        body.light-mode .brand-text {
            background: linear-gradient(145deg, #1e293b, var(--accent-color));
            -webkit-background-clip: text;
            color: transparent;
        }

        .theme-toggle {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--glass-border);
            border-radius: 30px;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            transition: 0.2s;
        }

        .theme-toggle:hover {
            background: var(--glass-highlight);
            color: var(--text-primary);
        }

        /* HERO */
        .hero-section {
            text-align: center;
            margin-bottom: 1rem;
        }

        .hero-badge {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.4rem 1.2rem;
            border-radius: 30px;
            display: inline-block;
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 2px 12px rgba(37, 99, 235, 0.4);
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(to right, #e6f1ff, var(--accent-light));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        body.light-mode .hero-title {
            background: linear-gradient(to right, #1e293b, var(--accent-color));
            -webkit-background-clip: text;
            color: transparent;
        }

        .hero-version {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--accent-light);
            background: rgba(37, 99, 235, 0.1);
            padding: 0.2rem 1rem;
            border-radius: 60px;
            display: inline-block;
            border: 1px solid rgba(37, 99, 235, 0.3);
            margin-bottom: 1.2rem;
        }

        .hero-description {
            font-size: 1.1rem;
            color: var(--text-secondary);
            max-width: 700px;
            margin: 0 auto 2.5rem;
            line-height: 1.7;
        }

        /* CARD GRID */
        .card-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
        }

        .auth-card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 32px;
            padding: 2.5rem 2rem;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.2rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        body.light-mode .auth-card {
            background: rgba(255, 255, 255, 0.8);
        }

        .auth-card:hover {
            transform: translateY(-8px);
            border-color: var(--accent-light);
            box-shadow: 0 25px 45px rgba(37, 99, 235, 0.2);
        }

        .card-icon {
            width: 70px;
            height: 70px;
            background: var(--accent-gradient);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
            margin-bottom: 0.5rem;
        }

        .auth-card h2 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .auth-card p {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        /* BUTTONS */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            padding: 0.9rem 2rem;
            border-radius: 40px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s;
            width: 100%;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--accent-gradient);
            color: white;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
        }

        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 28px rgba(37, 99, 235, 0.6);
        }

        .btn-secondary {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(98, 160, 234, 0.3);
            color: var(--text-primary);
        }

        body.light-mode .btn-secondary {
            background: #f1f5f9;
            color: #1e293b;
            border-color: #cbd5e1;
        }

        .btn-secondary:hover {
            background: rgba(30, 41, 59, 0.95);
            border-color: var(--accent-light);
        }

        body.light-mode .btn-secondary:hover {
            background: #e2e8f0;
        }

        /* FOOTER */
        .welcome-footer {
            margin-top: 3rem;
            font-size: 0.8rem;
            color: var(--text-secondary);
            display: flex;
            gap: 1.5rem;
            justify-content: center;
        }

        .welcome-footer a:hover {
            color: var(--accent-light);
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }

            .card-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .auth-card {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="welcome-container">
        <header class="welcome-header">
            <div class="brand">
                <div class="brand-icon"><i class="fas fa-robot"></i></div>
                <span class="brand-text">SAHAJA AI</span>
            </div>
            <button class="theme-toggle" id="themeToggleBtn">
                <i class="fas fa-moon" id="themeIcon"></i>
                <span id="themeLabel">Mode Gelap</span>
            </button>
        </header>

        <div class="hero-section">
            <span class="hero-badge">AI ASSISTANT</span>
            <h1 class="hero-title">SAHAJA AI</h1>
            <div class="hero-version">V 1.0</div>
            <p class="hero-description">
                Asisten cerdas berbasis <strong>Gemini Pro</strong> yang siap membantu tugas kuliah, coding, dan
                brainstorming ide Anda.
            </p>
        </div>

        <div class="card-grid">
            <div class="auth-card">
                <div class="card-icon"><i class="fas fa-sign-in-alt"></i></div>
                <h2>Login</h2>
                <p>Masuk untuk melanjutkan percakapan dengan SAHAJA AI.</p>
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right-to-bracket"></i> Masuk
                </a>
            </div>

            <div class="auth-card">
                <div class="card-icon"><i class="fas fa-user-plus"></i></div>
                <h2>Register</h2>
                <p>Daftar sekarang dan nikmati layanan AI canggih gratis.</p>
                <a href="{{ route('register') }}" class="btn btn-secondary">
                    <i class="fas fa-user-plus"></i> Daftar
                </a>
            </div>
        </div>

        <footer class="welcome-footer">
            <span>© {{ date('Y') }} SAHAJA AI. By Mahasiswa SI.</span>
        </footer>
    </div>

    <script>
        (function() {
            const themeToggle = document.getElementById('themeToggleBtn');
            const themeIcon = document.getElementById('themeIcon');
            const themeLabel = document.getElementById('themeLabel');
            const body = document.body;

            if (localStorage.getItem('theme') === 'light') {
                body.classList.add('light-mode');
                themeIcon.className = 'fas fa-sun';
                themeLabel.innerText = 'Mode Terang';
            }

            themeToggle.addEventListener('click', () => {
                body.classList.toggle('light-mode');
                if (body.classList.contains('light-mode')) {
                    localStorage.setItem('theme', 'light');
                    themeIcon.className = 'fas fa-sun';
                    themeLabel.innerText = 'Mode Terang';
                } else {
                    localStorage.setItem('theme', 'dark');
                    themeIcon.className = 'fas fa-moon';
                    themeLabel.innerText = 'Mode Gelap';
                }
            });
        })();
    </script>
</body>

</html>

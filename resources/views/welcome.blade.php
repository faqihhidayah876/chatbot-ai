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
        /* ===== THEME VARIABLES ===== */
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
            justify-content: space-between;
            padding: 1.5rem 0 0 0;
            position: relative;
            transition: background 0.3s, color 0.3s;
            overflow-x: hidden;
            width: 100%;
        }

        /* ===== BACKGROUND 3D AESTHETIC ===== */
        .bg-3d {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
            overflow: hidden;
        }

        .bg-3d-grid {
            position: absolute;
            top: -50%;
            left: -10%;
            width: 120%;
            height: 200%;
            background-image:
                linear-gradient(rgba(98, 160, 234, 0.12) 1px, transparent 1px),
                linear-gradient(90deg, rgba(98, 160, 234, 0.12) 1px, transparent 1px);
            background-size: 40px 40px;
            transform: perspective(600px) rotateX(55deg) scale(1.8);
            transform-origin: center top;
            opacity: 0.3;
            animation: gridMove 30s infinite linear;
        }

        .bg-3d-shape {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, rgba(37, 99, 235, 0.25), rgba(6, 182, 212, 0.1));
            filter: blur(70px);
            opacity: 0.4;
        }

        .shape1 {
            top: 10%;
            left: -5%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.3), rgba(37, 99, 235, 0.1));
            transform: translateZ(-50px) rotate(20deg);
            animation: float1 25s ease-in-out infinite;
        }

        .shape2 {
            bottom: 5%;
            right: -2%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.2), rgba(6, 182, 212, 0.05));
            filter: blur(90px);
            animation: float2 30s ease-in-out infinite;
        }

        .shape3 {
            top: 40%;
            right: 15%;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.2), rgba(99, 102, 241, 0.05));
            filter: blur(60px);
            animation: float3 20s ease-in-out infinite;
        }

        .bg-3d-dots {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(circle at 30% 40%, rgba(98, 160, 234, 0.2) 2px, transparent 2px);
            background-size: 50px 50px;
            opacity: 0.2;
            transform: perspective(500px) rotateX(50deg) scale(1.5);
            transform-origin: center;
        }

        body.light-mode .bg-3d-grid {
            background-image:
                linear-gradient(rgba(37, 99, 235, 0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(37, 99, 235, 0.08) 1px, transparent 1px);
            opacity: 0.4;
        }

        body.light-mode .bg-3d-shape {
            opacity: 0.5;
        }

        body.light-mode .shape1 {
            background: radial-gradient(circle, rgba(37, 99, 235, 0.2), rgba(37, 99, 235, 0.05));
        }

        body.light-mode .shape2 {
            background: radial-gradient(circle, rgba(6, 182, 212, 0.15), rgba(6, 182, 212, 0.02));
        }

        body.light-mode .shape3 {
            background: radial-gradient(circle, rgba(99, 102, 241, 0.12), rgba(99, 102, 241, 0.03));
        }

        body.light-mode .bg-3d-dots {
            background-image: radial-gradient(circle at 30% 40%, rgba(37, 99, 235, 0.1) 2px, transparent 2px);
            opacity: 0.3;
        }

        @keyframes gridMove {
            0% { transform: perspective(600px) rotateX(55deg) scale(1.8) translateY(0); }
            50% { transform: perspective(600px) rotateX(55deg) scale(1.8) translateY(-20px); }
            100% { transform: perspective(600px) rotateX(55deg) scale(1.8) translateY(0); }
        }

        @keyframes float1 {
            0% { transform: translateZ(-50px) rotate(20deg) translate(0, 0); }
            50% { transform: translateZ(-50px) rotate(25deg) translate(30px, -20px); }
            100% { transform: translateZ(-50px) rotate(20deg) translate(0, 0); }
        }

        @keyframes float2 {
            0% { transform: translate(0, 0); }
            50% { transform: translate(-40px, 30px); }
            100% { transform: translate(0, 0); }
        }

        @keyframes float3 {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(20px, -30px) scale(1.1); }
            100% { transform: translate(0, 0) scale(1); }
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(circle at 20% 30%, rgba(37, 99, 235, 0.15) 0%, transparent 60%),
                radial-gradient(circle at 80% 70%, rgba(6, 182, 212, 0.12) 0%, transparent 60%),
                radial-gradient(circle at 40% 80%, rgba(37, 99, 235, 0.1) 0%, transparent 70%);
            z-index: -2;
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
            flex: 1;
            justify-content: center;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
            margin: 0 auto;
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

        /* ===== SECTION KIMI K 2.5 ===== */
        .kimi-section {
            width: 100%;
            max-width: 900px;
            margin: 2rem auto;
            text-align: center;
        }

        .kimi-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(to right, var(--accent-light), #ffffff);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        body.light-mode .kimi-title {
            background: linear-gradient(to right, var(--accent-color), #1e293b);
            -webkit-background-clip: text;
            color: transparent;
        }

        .kimi-description {
            font-size: 1rem;
            color: var(--text-secondary);
            max-width: 700px;
            margin: 0 auto 2rem;
            line-height: 1.6;
        }

        .kimi-image-wrapper {
            width: 100%;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
            background-color: #1a1a2e; /* Background fallback jika gambar gagal load */
            min-height: 200px; /* Memberi ruang minimal */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* .kimi-image-wrapper:hover {
            transform: scale(1.02);
        } */

        .kimi-image-wrapper img {
            width: 100%;
            height: auto;
            display: block;
            border: none;
            max-width: 100%;
        }

        /* Fallback text jika gambar gagal dimuat */
        .kimi-image-wrapper img[alt] {
            font-family: 'Poppins', sans-serif;
            color: var(--text-secondary);
        }

        /* ===== FOOTER ===== */
        .footer-large {
            width: 100%;
            background: rgba(10, 14, 23, 0.98);
            backdrop-filter: blur(20px);
            border-top: 1px solid var(--glass-border);
            margin-top: 4rem;
            padding: 3rem 0 2rem 0;
            color: var(--text-secondary);
            position: relative;
            left: 0;
            right: 0;
        }

        body.light-mode .footer-large {
            background: rgba(248, 250, 252, 0.98);
            border-top-color: #e2e8f0;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2.5rem;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .footer-section h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1.2rem;
            letter-spacing: 0.5px;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section li {
            margin-bottom: 0.7rem;
        }

        .footer-section a {
            color: var(--text-secondary);
            font-size: 0.9rem;
            transition: color 0.2s;
            display: inline-block;
        }

        .footer-section a:hover {
            color: var(--accent-light);
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--glass-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            font-size: 0.8rem;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .footer-bottom-left {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .footer-bottom-right {
            display: flex;
            gap: 0.8rem;
            color: var(--text-secondary);
        }

        .footer-bottom a {
            color: var(--text-secondary);
        }

        .footer-bottom a:hover {
            color: var(--accent-light);
        }

        .footer-icp {
            max-width: 1200px;
            margin: 0 auto;
            margin-top: 0.5rem;
            font-size: 0.75rem;
            color: var(--text-secondary);
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .footer-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 2rem;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem 0 0 0;
            }

            .welcome-container {
                padding-left: 1rem;
                padding-right: 1rem;
            }

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

            .kimi-title {
                font-size: 1.6rem;
            }

            .kimi-description {
                font-size: 0.95rem;
            }

            .footer-container {
                grid-template-columns: 1fr;
                gap: 1.8rem;
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .footer-bottom {
                flex-direction: column;
                align-items: flex-start;
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .footer-large {
                padding: 2rem 0 1.5rem 0;
            }
        }

        @media (max-width: 480px) {
            .footer-large {
                padding: 2rem 0 1.5rem 0;
            }

            .footer-bottom-left {
                flex-direction: column;
                gap: 0.8rem;
            }

            .footer-container {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .footer-bottom {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- BACKGROUND 3D ELEMENTS -->
    <div class="bg-3d">
        <div class="bg-3d-grid"></div>
        <div class="bg-3d-shape shape1"></div>
        <div class="bg-3d-shape shape2"></div>
        <div class="bg-3d-shape shape3"></div>
        <div class="bg-3d-dots"></div>
    </div>

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
            <div class="hero-version">Beta V 2.0</div>
            <p class="hero-description">
                Asisten cerdas berbasis <strong>Kimi K 2.5</strong> setara dengan GPT 5.2 siap membantu dan
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

        <!-- ===== SECTION KIMI K 2.5 ===== -->
        <div class="kimi-section">
            <h2 class="kimi-title">Hadir dengan Kimi K 2.5</h2>
            <p class="kimi-description">
                SAHAJA AI kini hadir dengan mesin AI terbaru dari Kimi, model reasoning yang handal dan model open-source paling canggih, mampu menandingi Opus 4.5 dan GPT 5.2 dalam beberapa skenario.
            </p>
            <div class="kimi-image-wrapper">
                <!-- Menggunakan tag img langsung tanpa link di sekitarnya untuk menghindari masalah -->
                <img src="https://platform.moonshot.ai/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fk25-en.4301d842.png&w=3840&q=75"
                     alt="benchmark-kimi-k-2-5"
                     border="0"
                     onerror="this.onerror=null; this.src='https://via.placeholder.com/800x400?text=Gambar+Tidak+Dapat+Dimuat'; this.style.opacity='0.7';">

                <!-- Teks kecil untuk membantu debugging -->
                <div style="display: none;" id="debug-info"></div>
            </div>

            <!-- Link terpisah untuk gambar jika ingin mengunjungi sumber -->
            <p style="margin-top: 0.5rem; font-size: 0.8rem; color: var(--text-secondary);">
                Sumber gambar: <a href="https://platform.moonshot.ai/docs/guide/kimi-k2-5-quickstart#overview-of-kimi-k25-model" target="_blank" style="color: var(--accent-light); text-decoration: underline;">moonshoot.ai</a>
            </p>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer-large">
        <div class="footer-container">
            <!-- Another Project -->
            <div class="footer-section">
                <h3>Another Project</h3>
                <ul>
                    <li><a href="https://surat-admin.alwaysdata.net/">Layanan Mandiri & Surat Desa</a></li>
                    <li><a href="https://sistem-deteksi-penyakit-daun.vercel.app/">Sistem Deteksi Dini Penyakit Daun Patat</a></li>
                </ul>
            </div>

            <!-- About Me -->
            <div class="footer-section">
                <h3>About Me</h3>
                <ul>
                    <li><a href="https://github.com/faqihhidayah876">GitHub</a></li>
                    <li><a href="https://www.linkedin.com/in/faqih-hidayah-b4a134381/">LinkedIn</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-bottom-left">
                <span>© 2026 SAHAJA AI. All rights reserved.</span>
            </div>
            <div class="footer-bottom-right">
                <span>SAHAJA AI by: Faqih Hidayah</span>
            </div>
        </div>

        <div class="footer-icp">
            <!-- Additional info if needed -->
        </div>
    </footer>

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

            // Debugging: Cek apakah gambar berhasil dimuat
            const img = document.querySelector('.kimi-image-wrapper img');
            if (img) {
                img.addEventListener('load', function() {
                    console.log('Gambar berhasil dimuat');
                });
                img.addEventListener('error', function() {
                    console.error('Gambar gagal dimuat. URL:', img.src);
                });
            }
        })();
    </script>
</body>

</html>

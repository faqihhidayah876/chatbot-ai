<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SAHAJA AI — Selamat Datang</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png">

    <style>
        /* ===== THEME VARIABLES ===== */
        :root {
            --bg-primary: #080c14;
            --bg-secondary: #0d1321;
            --surface: rgba(18, 26, 44, 0.75);
            --surface-hover: rgba(22, 32, 54, 0.85);
            --surface-border: rgba(74, 130, 220, 0.14);
            --surface-border-hover: rgba(74, 130, 220, 0.35);
            --text-primary: #e8edf4;
            --text-secondary: #8899b4;
            --text-muted: #5c6e85;
            --accent: #3b82f6;
            --accent-soft: #60a5fa;
            --accent-glow: rgba(59, 130, 246, 0.35);
            --accent-gradient: linear-gradient(135deg, #3b82f6 0%, #22c5e0 100%);
            --accent-gradient-soft: linear-gradient(135deg, #3b82f6 0%, #60a5fa 50%, #22c5e0 100%);
            --card-shadow: 0 1px 2px rgba(0, 0, 0, 0.4), 0 8px 32px rgba(0, 0, 0, 0.35);
            --card-shadow-hover: 0 1px 2px rgba(0, 0, 0, 0.5), 0 16px 48px rgba(37, 99, 235, 0.18);
            --glass-highlight: rgba(255, 255, 255, 0.04);
            --radius-sm: 10px;
            --radius-md: 18px;
            --radius-lg: 24px;
            --radius-xl: 32px;
            --radius-full: 9999px;
            --transition-fast: 0.18s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-smooth: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-spring: 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        body.light-mode {
            --bg-primary: #f6f8fb;
            --bg-secondary: #eef1f6;
            --surface: rgba(255, 255, 255, 0.8);
            --surface-hover: rgba(255, 255, 255, 0.95);
            --surface-border: #dde3ed;
            --surface-border-hover: #bcc7db;
            --text-primary: #1a2332;
            --text-secondary: #55667d;
            --text-muted: #8899b4;
            --accent: #2563eb;
            --accent-soft: #3b82f6;
            --accent-glow: rgba(37, 99, 235, 0.2);
            --accent-gradient: linear-gradient(135deg, #2563eb 0%, #0d9488 100%);
            --accent-gradient-soft: linear-gradient(135deg, #2563eb 0%, #3b82f6 50%, #0d9488 100%);
            --card-shadow: 0 1px 2px rgba(0, 0, 0, 0.06), 0 8px 24px rgba(0, 0, 0, 0.07);
            --card-shadow-hover: 0 1px 2px rgba(0, 0, 0, 0.08), 0 16px 40px rgba(37, 99, 235, 0.1);
            --glass-highlight: rgba(255, 255, 255, 0.6);
            --surface-border: #dde3ed;
            --surface-border-hover: #bcc7db;
        }

        /* ===== GLOBAL RESET ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 0 0 0;
            position: relative;
            transition: background 0.4s ease, color 0.4s ease;
            overflow-x: hidden;
            width: 100%;
            line-height: 1.6;
            letter-spacing: 0.01em;
        }

        /* ===== AMBIENT BACKGROUND ===== */
        .bg-ambient {
            position: fixed;
            inset: 0;
            z-index: -2;
            pointer-events: none;
            overflow: hidden;
        }

        .bg-ambient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.28;
            will-change: transform;
        }

        .bg-ambient-orb--1 {
            width: 520px;
            height: 520px;
            background: radial-gradient(circle at 35% 35%, rgba(59, 130, 246, 0.5), rgba(59, 130, 246, 0.08) 70%);
            top: -8%;
            left: -10%;
            animation: orbDrift1 28s ease-in-out infinite;
        }

        .bg-ambient-orb--2 {
            width: 440px;
            height: 440px;
            background: radial-gradient(circle at 40% 40%, rgba(34, 197, 224, 0.4), rgba(34, 197, 224, 0.05) 70%);
            bottom: -6%;
            right: -6%;
            animation: orbDrift2 32s ease-in-out infinite;
        }

        .bg-ambient-orb--3 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle at 30% 30%, rgba(99, 130, 200, 0.35), rgba(99, 130, 200, 0.04) 70%);
            top: 45%;
            right: 18%;
            animation: orbDrift3 24s ease-in-out infinite;
        }

        body.light-mode .bg-ambient-orb {
            opacity: 0.35;
        }
        body.light-mode .bg-ambient-orb--1 {
            background: radial-gradient(circle at 35% 35%, rgba(37, 99, 235, 0.3), rgba(37, 99, 235, 0.03) 70%);
        }
        body.light-mode .bg-ambient-orb--2 {
            background: radial-gradient(circle at 40% 40%, rgba(13, 148, 136, 0.25), rgba(13, 148, 136, 0.02) 70%);
        }
        body.light-mode .bg-ambient-orb--3 {
            background: radial-gradient(circle at 30% 30%, rgba(99, 120, 180, 0.2), rgba(99, 120, 180, 0.02) 70%);
        }

        @keyframes orbDrift1 {
            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }
            30% {
                transform: translate(40px, -28px) scale(1.06);
            }
            60% {
                transform: translate(-18px, 20px) scale(0.96);
            }
        }

        @keyframes orbDrift2 {
            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }
            35% {
                transform: translate(-35px, 22px) scale(1.05);
            }
            70% {
                transform: translate(20px, -18px) scale(0.95);
            }
        }

        @keyframes orbDrift3 {
            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }
            40% {
                transform: translate(22px, -32px) scale(1.08);
            }
            75% {
                transform: translate(-15px, 16px) scale(0.94);
            }
        }

        /* Subtle grain-like dot pattern overlay */
        .bg-dot-overlay {
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            opacity: 0.06;
            background-image: radial-gradient(circle at 25% 35%, rgba(148, 180, 220, 0.5) 1px, transparent 1px),
                radial-gradient(circle at 65% 55%, rgba(148, 180, 220, 0.4) 1px, transparent 1px),
                radial-gradient(circle at 40% 75%, rgba(148, 180, 220, 0.45) 1px, transparent 1px);
            background-size: 55px 55px, 70px 70px, 60px 60px;
            background-position: 0 0, 25px 25px, 15px 15px;
        }

        body.light-mode .bg-dot-overlay {
            opacity: 0.1;
            background-image: radial-gradient(circle at 25% 35%, rgba(60, 80, 110, 0.5) 1px, transparent 1px),
                radial-gradient(circle at 65% 55%, rgba(60, 80, 110, 0.4) 1px, transparent 1px),
                radial-gradient(circle at 40% 75%, rgba(60, 80, 110, 0.45) 1px, transparent 1px);
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: color var(--transition-fast);
        }

        button {
            cursor: pointer;
            border: none;
            background: none;
            font-family: inherit;
            font-size: inherit;
        }

        /* ===== MAIN LAYOUT ===== */
        .welcome-container {
            width: 100%;
            max-width: 1100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2.75rem;
            z-index: 10;
            flex: 1;
            justify-content: center;
            padding: 0 1.5rem;
            margin: 0 auto;
        }

        /* ===== HEADER ===== */
        .welcome-header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.7rem 1.1rem 0.7rem 1.4rem;
            background: var(--surface);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid var(--surface-border);
            border-radius: var(--radius-full);
            box-shadow: var(--card-shadow);
            transition: border-color var(--transition-smooth), box-shadow var(--transition-smooth);
        }

        .welcome-header:hover {
            border-color: var(--surface-border-hover);
            box-shadow: var(--card-shadow-hover);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.55rem;
        }

        .brand-logo {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .brand-text {
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            background: var(--accent-gradient-soft);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .theme-toggle {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid var(--surface-border);
            border-radius: var(--radius-full);
            padding: 0.45rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.45rem;
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 500;
            transition: all var(--transition-fast);
            white-space: nowrap;
        }

        .theme-toggle:hover {
            background: var(--glass-highlight);
            color: var(--text-primary);
            border-color: var(--surface-border-hover);
        }

        .theme-toggle i {
            font-size: 0.95rem;
        }

        body.light-mode .theme-toggle {
            background: rgba(0, 0, 0, 0.04);
        }
        body.light-mode .theme-toggle:hover {
            background: rgba(0, 0, 0, 0.07);
        }

        /* ===== HERO ===== */
        .hero-section {
            text-align: center;
            margin-bottom: 0.5rem;
            animation: fadeInUp 0.7s ease-out both;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(59, 130, 246, 0.12);
            color: var(--accent-soft);
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.35rem 1.1rem;
            border-radius: var(--radius-full);
            margin-bottom: 1.4rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            border: 1px solid rgba(59, 130, 246, 0.25);
            transition: all var(--transition-smooth);
        }

        .hero-badge:hover {
            background: rgba(59, 130, 246, 0.18);
            border-color: rgba(59, 130, 246, 0.4);
        }

        .hero-badge-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #22c5e0;
            animation: pulseDot 2.2s ease-in-out infinite;
        }

        @keyframes pulseDot {
            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(34, 197, 224, 0.6);
            }
            50% {
                box-shadow: 0 0 0 8px rgba(34, 197, 224, 0);
            }
        }

        .hero-title {
            font-size: clamp(2.4rem, 5.5vw, 3.4rem);
            font-weight: 700;
            margin-bottom: 0.4rem;
            letter-spacing: -0.03em;
            background: linear-gradient(160deg, #e8edf4 0%, #a0c4f0 50%, #7ec8e0 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.15;
        }

        body.light-mode .hero-title {
            background: linear-gradient(160deg, #1a2332 0%, #2563eb 50%, #0d6b60 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-version {
            display: inline-block;
            font-size: 1rem;
            font-weight: 600;
            color: var(--accent-soft);
            background: rgba(59, 130, 246, 0.1);
            padding: 0.25rem 1rem;
            border-radius: var(--radius-full);
            border: 1px solid rgba(59, 130, 246, 0.22);
            margin-bottom: 1.3rem;
            letter-spacing: 0.02em;
        }

        .hero-description {
            font-size: 1.05rem;
            color: var(--text-secondary);
            max-width: 640px;
            margin: 0 auto 2.2rem;
            line-height: 1.7;
            font-weight: 400;
        }

        .hero-description strong {
            color: var(--text-primary);
            font-weight: 600;
        }

        /* ===== AUTH CARDS ===== */
        .card-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.75rem;
            width: 100%;
            max-width: 780px;
            margin: 0 auto;
        }

        .auth-card {
            background: var(--surface);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--surface-border);
            border-radius: var(--radius-xl);
            padding: 2.5rem 2rem 2.25rem;
            text-align: center;
            transition: all var(--transition-smooth);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.7s ease-out both;
        }

        .auth-card:nth-child(1) {
            animation-delay: 0.1s;
        }
        .auth-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .auth-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(160deg,
                    rgba(255, 255, 255, 0.06) 0%,
                    rgba(255, 255, 255, 0.02) 30%,
                    transparent 60%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
            transition: opacity var(--transition-smooth);
        }

        .auth-card:hover {
            transform: translateY(-5px);
            border-color: var(--surface-border-hover);
            box-shadow: var(--card-shadow-hover);
        }

        .auth-card:hover::before {
            opacity: 0.5;
        }

        .card-icon {
            width: 62px;
            height: 62px;
            background: var(--accent-gradient);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: #fff;
            box-shadow: 0 6px 18px var(--accent-glow);
            transition: all var(--transition-spring);
            flex-shrink: 0;
        }

        .auth-card:hover .card-icon {
            transform: scale(1.06);
            box-shadow: 0 10px 28px var(--accent-glow);
        }

        .auth-card h2 {
            font-size: 1.55rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: -0.25rem;
        }

        .auth-card p {
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 0.5rem;
            max-width: 260px;
        }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.8rem 1.8rem;
            border-radius: var(--radius-full);
            font-weight: 600;
            font-size: 0.95rem;
            transition: all var(--transition-fast);
            width: 100%;
            border: none;
            cursor: pointer;
            letter-spacing: 0.01em;
            position: relative;
            overflow: hidden;
        }

        .btn:active {
            transform: scale(0.97);
        }

        .btn-primary {
            background: var(--accent-gradient);
            color: #fff;
            box-shadow: 0 4px 14px var(--accent-glow);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px var(--accent-glow);
            filter: brightness(1.08);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--surface-border);
            color: var(--text-primary);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--surface-border-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
        }

        body.light-mode .btn-secondary {
            background: #f1f4f8;
            color: #1a2332;
            border-color: #d0d8e4;
        }
        body.light-mode .btn-secondary:hover {
            background: #e4e9f1;
            border-color: #b0bdd0;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.07);
        }

        /* ===== COMPARISON SECTION ===== */
        .comparison-section {
            width: 100%;
            max-width: 1000px;
            margin: 1.5rem auto 0;
            text-align: center;
            animation: fadeInUp 0.7s ease-out both;
            animation-delay: 0.35s;
        }

        .comparison-title {
            font-size: clamp(1.5rem, 3vw, 1.85rem);
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
            background: linear-gradient(160deg, var(--text-primary) 0%, var(--accent-soft) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .comparison-sub {
            font-size: 0.95rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: var(--radius-lg);
            background: var(--surface);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid var(--surface-border);
            padding: 0.25rem;
            box-shadow: var(--card-shadow);
            transition: all var(--transition-smooth);
        }

        .table-wrapper:hover {
            border-color: var(--surface-border-hover);
            box-shadow: var(--card-shadow-hover);
        }

        .comparison-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            min-width: 750px;
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .comparison-table thead th {
            background: rgba(59, 130, 246, 0.08);
            color: var(--accent-soft);
            font-weight: 600;
            padding: 1rem 1rem;
            text-align: center;
            border-bottom: 2px solid var(--surface-border);
            font-size: 0.85rem;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .comparison-table thead th:first-child {
            text-align: left;
            padding-left: 1.4rem;
            border-radius: var(--radius-lg) 0 0 0;
        }

        .comparison-table thead th:last-child {
            border-radius: 0 var(--radius-lg) 0 0;
        }

        .comparison-table tbody td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(128, 150, 180, 0.12);
            text-align: center;
            transition: background var(--transition-fast);
            white-space: nowrap;
        }

        .comparison-table tbody td:first-child {
            font-weight: 600;
            color: var(--text-primary);
            text-align: left;
            padding-left: 1.4rem;
            letter-spacing: 0.01em;
        }

        .comparison-table tbody tr:last-child td {
            border-bottom: none;
        }
        .comparison-table tbody tr:last-child td:first-child {
            border-radius: 0 0 0 var(--radius-lg);
        }
        .comparison-table tbody tr:last-child td:last-child {
            border-radius: 0 0 var(--radius-lg) 0;
        }

        .comparison-table tbody tr:hover {
            background: var(--glass-highlight);
        }

        .indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-weight: 500;
            font-size: 0.85rem;
            padding: 0.2rem 0.6rem;
            border-radius: var(--radius-full);
            letter-spacing: 0.01em;
        }

        .indicator--check {
            color: #10b981;
            background: rgba(16, 185, 129, 0.1);
        }
        .indicator--warning {
            color: #f59e0b;
            background: rgba(245, 158, 11, 0.1);
        }
        .indicator--cross {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }

        .indicator i {
            font-size: 0.8rem;
        }

        /* ===== SECTION KIMI (commented out, CSS preserved) ===== */
        .kimi-section {
            width: 100%;
            max-width: 900px;
            margin: 2rem auto;
            text-align: center;
            animation: fadeInUp 0.7s ease-out both;
            animation-delay: 0.25s;
        }

        .kimi-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            letter-spacing: -0.02em;
            background: linear-gradient(160deg, var(--text-primary) 0%, var(--accent-soft) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .kimi-description {
            font-size: 0.95rem;
            color: var(--text-secondary);
            max-width: 640px;
            margin: 0 auto 2rem;
            line-height: 1.6;
        }

        .kimi-image-wrapper {
            width: 100%;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: all var(--transition-smooth);
            background: var(--bg-secondary);
            min-height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--surface-border);
        }

        .kimi-image-wrapper:hover {
            border-color: var(--surface-border-hover);
            box-shadow: var(--card-shadow-hover);
        }

        .kimi-image-wrapper img {
            width: 100%;
            height: auto;
            display: block;
            max-width: 100%;
        }

        /* ===== FOOTER ===== */
        .footer-large {
            width: 100%;
            background: rgba(8, 12, 20, 0.97);
            backdrop-filter: blur(22px);
            -webkit-backdrop-filter: blur(22px);
            border-top: 1px solid var(--surface-border);
            margin-top: 4rem;
            padding: 3rem 0 1.75rem 0;
            color: var(--text-secondary);
            position: relative;
            left: 0;
            right: 0;
        }

        body.light-mode .footer-large {
            background: rgba(246, 248, 251, 0.97);
            border-top-color: #dde3ed;
        }

        .footer-container {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr;
            gap: 2.5rem;
            padding: 0 1.5rem;
        }

        .footer-brand-desc {
            font-size: 0.85rem;
            line-height: 1.65;
            margin-top: 0.5rem;
            color: var(--text-muted);
            max-width: 260px;
        }

        .footer-section h3 {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
            letter-spacing: 0.02em;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section li {
            margin-bottom: 0.55rem;
        }

        .footer-section a {
            color: var(--text-secondary);
            font-size: 0.85rem;
            transition: color var(--transition-fast);
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .footer-section a:hover {
            color: var(--accent-soft);
        }

        .footer-section a i {
            font-size: 0.8rem;
            opacity: 0.7;
        }

        .footer-bottom {
            max-width: 1100px;
            margin: 0 auto;
            margin-top: 2rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--surface-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.8rem;
            font-size: 0.78rem;
            color: var(--text-muted);
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .footer-bottom a {
            color: var(--text-muted);
            transition: color var(--transition-fast);
        }
        .footer-bottom a:hover {
            color: var(--accent-soft);
        }

        .footer-bottom-left {
            display: flex;
            gap: 1.2rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .footer-bottom-right {
            display: flex;
            gap: 0.6rem;
            align-items: center;
            color: var(--text-muted);
        }

        .footer-bottom-right .footer-heart {
            color: #ef4444;
            font-size: 0.75rem;
            animation: heartBeat 1.5s ease-in-out infinite;
        }

        @keyframes heartBeat {
            0%,
            100% {
                transform: scale(1);
            }
            15% {
                transform: scale(1.25);
            }
            30% {
                transform: scale(1);
            }
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .footer-container {
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
            }
            .footer-brand-desc {
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 0.75rem 0 0 0;
            }
            .welcome-container {
                padding: 0 1rem;
                gap: 2rem;
            }
            .welcome-header {
                padding: 0.55rem 0.9rem 0.55rem 1.1rem;
            }
            .brand-text {
                font-size: 1.15rem;
            }
            .brand-logo {
                width: 28px;
                height: 28px;
                border-radius: 6px;
            }
            .hero-title {
                font-size: 2rem;
            }
            .hero-description {
                font-size: 0.9rem;
                max-width: 100%;
            }
            .card-grid {
                grid-template-columns: 1fr;
                gap: 1.25rem;
                max-width: 420px;
            }
            .auth-card {
                padding: 2rem 1.5rem 1.75rem;
            }
            .comparison-title {
                font-size: 1.4rem;
            }
            .comparison-sub {
                font-size: 0.85rem;
            }
            .table-wrapper {
                padding: 0.15rem;
                border-radius: var(--radius-md);
            }
            .comparison-table {
                font-size: 0.8rem;
                min-width: 600px;
            }
            .comparison-table thead th,
            .comparison-table tbody td {
                padding: 0.6rem 0.6rem;
            }
            .comparison-table thead th:first-child,
            .comparison-table tbody td:first-child {
                padding-left: 0.9rem;
            }
            .indicator {
                font-size: 0.75rem;
                padding: 0.15rem 0.45rem;
            }
            .footer-container {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                padding: 0 1rem;
            }
            .footer-bottom {
                flex-direction: column;
                align-items: flex-start;
                padding-left: 1rem;
                padding-right: 1rem;
                gap: 0.5rem;
            }
            .footer-bottom-left {
                flex-direction: column;
                gap: 0.4rem;
            }
            .footer-large {
                padding: 2rem 0 1.25rem 0;
                margin-top: 2.5rem;
            }
            .theme-toggle span {
                display: none;
            }
            .theme-toggle {
                padding: 0.45rem 0.7rem;
                gap: 0;
            }
            .theme-toggle i {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .hero-badge {
                font-size: 0.68rem;
                padding: 0.3rem 0.8rem;
            }
            .hero-title {
                font-size: 1.7rem;
            }
            .hero-version {
                font-size: 0.85rem;
            }
            .card-icon {
                width: 50px;
                height: 50px;
                border-radius: 14px;
                font-size: 1.3rem;
            }
            .auth-card h2 {
                font-size: 1.3rem;
            }
            .btn {
                font-size: 0.88rem;
                padding: 0.7rem 1.4rem;
            }
            .comparison-table {
                min-width: 520px;
                font-size: 0.72rem;
            }
            .footer-large {
                padding: 1.5rem 0 1rem 0;
            }
            .footer-container {
                gap: 1.2rem;
            }
        }
    </style>
</head>

<body>
    {{-- AMBIENT BACKGROUND --}}
    <div class="bg-ambient">
        <div class="bg-ambient-orb bg-ambient-orb--1"></div>
        <div class="bg-ambient-orb bg-ambient-orb--2"></div>
        <div class="bg-ambient-orb bg-ambient-orb--3"></div>
    </div>
    <div class="bg-dot-overlay"></div>

    <div class="welcome-container">
        {{-- HEADER --}}
        <header class="welcome-header">
            <div class="brand">
                <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png"
                alt="SAHAJA AI Logo"
                class="brand-logo"
                loading="eager">
                <span class="brand-text">SAHAJA AI</span>
            </div>
            <button class="theme-toggle" id="themeToggleBtn" aria-label="Toggle theme">
                <i class="fas fa-moon" id="themeIcon"></i>
                <span id="themeLabel">Gelap</span>
            </button>
        </header>

        {{-- HERO --}}
        <section class="hero-section">
            <span class="hero-badge">
                <span class="hero-badge-dot"></span> AI Assistant
            </span>
            <h1 class="hero-title">SAHAJA AI</h1>
            <div class="hero-version">Beta V 5.0</div>
            <p class="hero-description">
                Asisten cerdas berbasis model AI besar
                <strong>Mistral Small, Kimi &amp; Qwen 3 Coder</strong>
                — setara dengan AI besar lainnya, siap membantu dan menjadi rekan
                <em>brainstorming</em> ide Anda.
            </p>
        </section>

        {{-- AUTH CARDS --}}
        <div class="card-grid">
            <div class="auth-card">
                <div class="card-icon"><i class="fas fa-sign-in-alt"></i></div>
                <h2>Masuk</h2>
                <p>Lanjutkan percakapan Anda dengan SAHAJA AI.</p>
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right-to-bracket"></i> Masuk Sekarang
                </a>
            </div>

            <div class="auth-card">
                <div class="card-icon"><i class="fas fa-user-plus"></i></div>
                <h2>Daftar</h2>
                <p>Buat akun gratis dan nikmati layanan AI canggih.</p>
                <a href="{{ route('register') }}" class="btn btn-secondary">
                    <i class="fas fa-user-plus"></i> Daftar Gratis
                </a>
            </div>
        </div>

        {{-- SECTION KIMI K 2.5 (commented) --}}
        {{-- <div class="kimi-section">
            <h2 class="kimi-title">Hadir dengan Kimi K 2.5</h2>
            <p class="kimi-description">
                SAHAJA AI kini hadir dengan mode cerdas berbasis mesin AI terbaru dari Kimi,
                model reasoning yang handal dan model open-source paling canggih.
            </p>
            <div class="kimi-image-wrapper">
                <img src="https://platform.moonshot.ai/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fk25-en.4301d842.png&w=3840&q=75"
                     alt="Benchmark Kimi K 2.5"
                     loading="lazy"
                     onerror="this.onerror=null; this.src='https://via.placeholder.com/800x400?text=Gambar+Tidak+Dapat+Dimuat'; this.style.opacity='0.7';">
            </div>
            <p style="margin-top:0.5rem;font-size:0.78rem;color:var(--text-muted);">
                Sumber: <a href="https://platform.moonshot.ai/docs/guide/kimi-k2-5-quickstart#overview-of-kimi-k25-model" target="_blank" rel="noopener" style="color:var(--accent-soft);text-decoration:underline;">moonshot.ai</a>
            </p>
        </div> --}}

        {{-- COMPARISON TABLE --}}
        <section class="comparison-section">
            <h2 class="comparison-title">Mengapa SAHAJA AI?</h2>
            <p class="comparison-sub">
                Setelah melalui berbagai penyempurnaan, SAHAJA AI hadir sebagai AI lokal yang
                autentik dan cerdas dalam memberikan jawaban.
            </p>

            <div class="table-wrapper">
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th>Aspek</th>
                            <th>SAHAJA AI v5.0</th>
                            <th>ChatGPT</th>
                            <th>Gemini</th>
                            <th>DeepSeek</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Reasoning</td>
                            <td><span class="indicator indicator--check"><i class="fas fa-check-circle"></i> CoT eksplisit</span></td>
                            <td><span class="indicator indicator--warning"><i class="fas fa-minus-circle"></i> Tersirat</span></td>
                            <td><span class="indicator indicator--check"><i class="fas fa-check-circle"></i> Multi-perspective</span></td>
                            <td><span class="indicator indicator--check"><i class="fas fa-check-circle"></i> Chain-of-thought</span></td>
                        </tr>
                        <tr>
                            <td>Konteks Lokal</td>
                            <td><span class="indicator indicator--check"><i class="fas fa-check-circle"></i> Indonesia banget</span></td>
                            <td><span class="indicator indicator--cross"><i class="fas fa-times-circle"></i> Global generik</span></td>
                            <td><span class="indicator indicator--warning"><i class="fas fa-minus-circle"></i> Regional terbatas</span></td>
                            <td><span class="indicator indicator--cross"><i class="fas fa-times-circle"></i> Global generik</span></td>
                        </tr>
                        <tr>
                            <td>Kedalaman Teknis</td>
                            <td><span class="indicator indicator--check"><i class="fas fa-check-circle"></i> Fullstack + AI</span></td>
                            <td><span class="indicator indicator--warning"><i class="fas fa-minus-circle"></i> Luas tapi generik</span></td>
                            <td><span class="indicator indicator--check"><i class="fas fa-check-circle"></i> Teknis kuat</span></td>
                            <td><span class="indicator indicator--check"><i class="fas fa-check-circle"></i> Coding kuat</span></td>
                        </tr>
                        <tr>
                            <td>Kontrol Output</td>
                            <td><span class="indicator indicator--check"><i class="fas fa-check-circle"></i> Strict formatting</span></td>
                            <td><span class="indicator indicator--warning"><i class="fas fa-minus-circle"></i> Variatif</span></td>
                            <td><span class="indicator indicator--check"><i class="fas fa-check-circle"></i> Konsisten</span></td>
                            <td><span class="indicator indicator--warning"><i class="fas fa-minus-circle"></i> Variatif</span></td>
                        </tr>
                        <tr>
                            <td>Protokol Keamanan</td>
                            <td><span class="indicator indicator--check"><i class="fas fa-check-circle"></i> Detail + lokal</span></td>
                            <td><span class="indicator indicator--check"><i class="fas fa-check-circle"></i> Global</span></td>
                            <td><span class="indicator indicator--check"><i class="fas fa-check-circle"></i> Global</span></td>
                            <td><span class="indicator indicator--check"><i class="fas fa-check-circle"></i> Global</span></td>
                        </tr>
                        <tr>
                            <td>Personality</td>
                            <td><span class="indicator indicator--check"><i class="fas fa-check-circle"></i> Autentik + lokal</span></td>
                            <td><span class="indicator indicator--warning"><i class="fas fa-minus-circle"></i> Netral</span></td>
                            <td><span class="indicator indicator--warning"><i class="fas fa-minus-circle"></i> Profesional</span></td>
                            <td><span class="indicator indicator--warning"><i class="fas fa-minus-circle"></i> Netral</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    {{-- FOOTER --}}
    <footer class="footer-large">
        <div class="footer-container">
            {{-- Brand --}}
            <div class="footer-section">
                <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.4rem;">
                    <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png"
                    alt="Logo"
                    style="width:26px;height:26px;border-radius:6px;object-fit:contain;"
                    loading="lazy">
                    <span style="font-weight:700;font-size:1.05rem;color:var(--text-primary);letter-spacing:-0.01em;">SAHAJA AI</span>
                </div>
                <p class="footer-brand-desc">
                    Asisten AI lokal yang autentik, cerdas, dan selalu siap membantu
                    kebutuhan produktivitas dan brainstorming Anda.
                </p>
            </div>

            {{-- Projects --}}
            <div class="footer-section">
                <h3>Proyek Lain</h3>
                <ul>
                    <li>
                        <a href="https://surat-admin.alwaysdata.net/" target="_blank" rel="noopener">
                            <i class="fas fa-external-link-alt"></i> Layanan Mandiri & Surat Desa
                        </a>
                    </li>
                    <li>
                        <a href="https://sistem-deteksi-penyakit-daun.vercel.app/" target="_blank" rel="noopener">
                            <i class="fas fa-external-link-alt"></i> Deteksi Dini Penyakit Daun Patat
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Social / About --}}
            <div class="footer-section">
                <h3>Tentang Saya</h3>
                <ul>
                    <li>
                        <a href="https://github.com/faqihhidayah876" target="_blank" rel="noopener">
                            <i class="fab fa-github"></i> GitHub
                        </a>
                    </li>
                    <li>
                        <a href="https://www.linkedin.com/in/faqih-hidayah-b4a134381/" target="_blank" rel="noopener">
                            <i class="fab fa-linkedin"></i> LinkedIn
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-bottom-left">
                <span>&copy; 2026 SAHAJA AI. Hak cipta dilindungi.</span>
            </div>
            <div class="footer-bottom-right">
                <span>Dibuat oleh:</span>
                <span><strong style="color:var(--text-primary);">Faqih Hidayah</strong></span>
            </div>
        </div>
    </footer>

    <script>
        (function() {
            const body = document.body;
            const themeToggle = document.getElementById('themeToggleBtn');
            const themeIcon = document.getElementById('themeIcon');
            const themeLabel = document.getElementById('themeLabel');

            // Initialize theme from localStorage
            const savedTheme = localStorage.getItem('sahaja-theme');
            if (savedTheme === 'light') {
                body.classList.add('light-mode');
                themeIcon.className = 'fas fa-sun';
                themeLabel.innerText = 'Terang';
            } else {
                // Default dark
                themeIcon.className = 'fas fa-moon';
                themeLabel.innerText = 'Gelap';
            }

            // Toggle theme
            themeToggle.addEventListener('click', () => {
                const isLight = body.classList.toggle('light-mode');
                if (isLight) {
                    localStorage.setItem('sahaja-theme', 'light');
                    themeIcon.className = 'fas fa-sun';
                    themeLabel.innerText = 'Terang';
                } else {
                    localStorage.setItem('sahaja-theme', 'dark');
                    themeIcon.className = 'fas fa-moon';
                    themeLabel.innerText = 'Gelap';
                }
            });

            // Debug: log Kimi image load status if section is uncommented
            const kimiImg = document.querySelector('.kimi-image-wrapper img');
            if (kimiImg) {
                kimiImg.addEventListener('load', () => console.log('[SAHAJA] Kimi image loaded successfully.'));
                kimiImg.addEventListener('error', () => console.warn('[SAHAJA] Kimi image failed to load. URL:', kimiImg.src));
            }
        })();
    </script>
</body>

</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SAHAJA AI — Asisten Cerdas Multi-Engine</title>
    <link rel="icon" type="image/png" href="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png">
    @include('partials.pwa')

    {{-- Typography: Inter (sans) & JetBrains Mono (mono) --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|jetbrains-mono:400,500,600" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        (function() {
            if (localStorage.getItem('sahaja-theme') === 'light' || localStorage.getItem('theme') === 'light') {
                document.documentElement.classList.add('light-mode');
                document.addEventListener('DOMContentLoaded', () => {
                    document.body.classList.add('light-mode');
                });
            }
        })();
    </script>

    <style>
        /* ===== DESIGN TOKENS ===== */
        :root {
            --bg-base: #080b11;
            --bg-elevated: #0f141f;
            --bg-elevated-hover: #151c2b;
            --bg-subtle: rgba(255, 255, 255, 0.04);
            --bg-hover: rgba(255, 255, 255, 0.06);
            --border-subtle: rgba(255, 255, 255, 0.08);
            --border-medium: rgba(255, 255, 255, 0.14);
            --border-strong: rgba(255, 255, 255, 0.22);
            --accent-border: rgba(59, 130, 246, 0.45);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-tertiary: #64748b;
            --accent: #3b82f6;
            --accent-hover: #2563eb;
            --accent-subtle: rgba(59, 130, 246, 0.14);
            --accent-text: #60a5fa;
            --shadow-md: 0 4px 20px rgba(0, 0, 0, 0.35);
            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 20px;
            --radius-xl: 28px;
            --radius-full: 9999px;
            --ease: cubic-bezier(0.4, 0, 0.2, 1);
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }

        body.light-mode {
            --bg-base: #f4f6fa;
            --bg-elevated: #ffffff;
            --bg-elevated-hover: #f8fafc;
            --bg-subtle: rgba(15, 23, 42, 0.04);
            --bg-hover: rgba(15, 23, 42, 0.06);
            --border-subtle: rgba(15, 23, 42, 0.08);
            --border-medium: rgba(15, 23, 42, 0.14);
            --border-strong: rgba(15, 23, 42, 0.22);
            --accent-border: rgba(37, 99, 235, 0.4);
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-tertiary: #94a3b8;
            --accent: #2563eb;
            --accent-hover: #1d4ed8;
            --accent-subtle: rgba(37, 99, 235, 0.09);
            --accent-text: #2563eb;
            --shadow-md: 0 4px 20px rgba(15, 23, 42, 0.06);
        }

        /* ===== GLOBAL RESET ===== */
        *, *::before, *::after {
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
            font-family: var(--font-sans);
            background-color: var(--bg-base);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
            line-height: 1.6;
            letter-spacing: -0.01em;
            transition: background-color 300ms var(--ease), color 300ms var(--ease);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-sans);
            font-weight: 600;
            letter-spacing: -0.02em;
            color: var(--text-primary);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* ===== CURSOR POINTER DI SEMUA BUTTON / LINK ===== */
        button, 
        [role="button"],
        a.btn,
        .btn,
        .prompt-icon-btn,
        .prompt-send-btn,
        .theme-toggle,
        .theme-toggle-btn,
        .header-login-btn,
        .cta-primary,
        .cta-secondary {
            cursor: pointer;
        }

        button:disabled,
        .btn:disabled {
            cursor: not-allowed;
        }

        /* ===== SCROLLBAR STYLING (GLOBAL) ===== */
        /* Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: var(--border-medium) transparent;
        }

        body.light-mode * {
            scrollbar-color: var(--border-medium) transparent;
        }

        /* Webkit (Chrome, Edge, Safari) */
        *::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        *::-webkit-scrollbar-track {
            background: transparent;
        }

        *::-webkit-scrollbar-thumb {
            background: var(--border-medium);
            border-radius: var(--radius-full);
            border: 2px solid transparent;
            background-clip: padding-box;
            transition: background-color 150ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        *::-webkit-scrollbar-thumb:hover {
            background: var(--text-tertiary);
            background-clip: padding-box;
            border: 2px solid transparent;
        }

        *::-webkit-scrollbar-thumb:active {
            background: var(--accent);
            background-clip: padding-box;
            border: 2px solid transparent;
        }

        *::-webkit-scrollbar-corner {
            background: transparent;
        }

        /* ===== BACKGROUND OVERLAYS (SUBTLE & RESTRAINED) ===== */
        .bg-subtle-radial {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: -2;
            opacity: 0.05;
            background: radial-gradient(circle at 18% 12%, #3b82f6 0%, transparent 45%);
        }

        body.light-mode .bg-subtle-radial {
            opacity: 1;
            background: 
                radial-gradient(circle at 15% 10%, rgba(37, 99, 235, 0.07) 0%, transparent 50%),
                radial-gradient(circle at 85% 25%, rgba(14, 165, 233, 0.06) 0%, transparent 45%),
                radial-gradient(circle at 50% 85%, rgba(99, 102, 241, 0.04) 0%, transparent 50%);
        }

        .bg-dot-pattern {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: -1;
            opacity: 0.03;
            background-image: radial-gradient(rgba(255, 255, 255, 0.9) 1px, transparent 1px);
            background-size: 28px 28px;
        }

        body.light-mode .bg-dot-pattern {
            opacity: 1;
            /* Motif: tactile dot grid pattern + subtle micro-grid blueprint mesh */
            background-image: 
                radial-gradient(circle at 1px 1px, rgba(30, 41, 59, 0.12) 1.2px, transparent 0),
                linear-gradient(to right, rgba(100, 116, 139, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(100, 116, 139, 0.04) 1px, transparent 1px);
            background-size: 24px 24px, 96px 96px, 96px 96px;
        }

        /* ===== TECHNIQUE 5: SCROLL PROGRESS BAR ===== */
        .scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 2px;
            width: 0%;
            background: var(--accent);
            z-index: 9999;
            transition: width 100ms linear;
            will-change: width;
        }

        /* ===== STICKY FLOATING HEADER ===== */
        .site-header {
            position: fixed;
            top: 16px;
            left: 50%;
            transform: translateX(-50%);
            max-width: 1080px;
            width: calc(100% - 48px);
            padding: 12px 20px;
            border-radius: var(--radius-full);
            background: rgba(35, 40, 51, 0.6);
            backdrop-filter:blur(12px) saturate(140%);
            -webkit-backdrop-filter:blur(12px) saturate(140%);
            border: 1px solid rgba(255, 255, 255, 0.06);
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: 
                background 300ms cubic-bezier(0.4, 0, 0.2, 1),
                box-shadow 300ms cubic-bezier(0.4, 0, 0.2, 1),
                border-color 300ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        .site-header.scrolled {
            background: rgba(35, 40, 51, 0.9);
            border-color: rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.3);
        }

        body.light-mode .site-header {
            background: rgba(255, 255, 255, 0.75);
            border-color: rgba(0, 0, 0, 0.06);
        }

        body.light-mode .site-header.scrolled {
            background: rgba(255, 255, 255, 0.95);
            border-color: rgba(0, 0, 0, 0.1);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-logo {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            object-fit: contain;
        }

        .header-title {
            font-size: 15px;
            font-weight: 600;
            letter-spacing: -0.02em;
            color: var(--text-primary);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .theme-toggle-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: var(--radius-full);
            border: 1px solid var(--border-subtle);
            background: var(--bg-subtle);
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 500;
            transition: all 200ms var(--ease);
        }

        .theme-toggle-btn:hover {
            color: var(--text-primary);
            border-color: var(--border-strong);
        }

        .header-login-btn {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            padding: 6px 14px;
            border-radius: var(--radius-full);
            transition: color 200ms var(--ease);
        }

        .header-login-btn:hover {
            color: var(--text-primary);
        }

        /* ===== TECHNIQUE 1: REVEAL ON SCROLL ===== */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: 
                opacity 700ms cubic-bezier(0.4, 0, 0.2, 1),
                transform 700ms cubic-bezier(0.4, 0, 0.2, 1);
            transition-delay: var(--reveal-delay, 0ms);
        }

        .reveal.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        /* ===== TECHNIQUE 2: HERO SECTION (PARALLAX + FADE) ===== */
        .hero-section {
            min-height: 85vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 160px 24px 80px;
            position: relative;
            will-change: transform, opacity;
        }

        .hero-parallax {
            transform: translateY(var(--hero-y, 0));
            opacity: var(--hero-opacity, 1);
            will-change: transform, opacity;
            max-width: 820px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            font-family: var(--font-mono);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 500;
            background: var(--accent-subtle);
            color: var(--accent-text);
            padding: 6px 14px;
            border-radius: var(--radius-full);
            border: 1px solid rgba(59, 130, 246, 0.2);
            margin-bottom: 24px;
            animation: heroFadeUp 600ms cubic-bezier(0.4, 0, 0.2, 1) both;
        }

        .hero-title {
            font-size: clamp(48px, 8vw, 88px);
            font-weight: 700;
            letter-spacing: -0.03em;
            line-height: 1.0;
            color: var(--text-primary);
            margin-bottom: 20px;
            animation: heroFadeUp 700ms cubic-bezier(0.4, 0, 0.2, 1) 100ms both;
        }

        .hero-subheading {
            font-size: 18px;
            color: var(--text-secondary);
            max-width: 560px;
            line-height: 1.6;
            margin-bottom: 36px;
            font-weight: 400;
            animation: heroFadeUp 700ms cubic-bezier(0.4, 0, 0.2, 1) 200ms both;
        }

        .hero-cta-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            animation: heroFadeUp 700ms cubic-bezier(0.4, 0, 0.2, 1) 300ms both;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 48px;
            padding: 0 24px;
            border-radius: var(--radius-full);
            font-size: 15px;
            font-weight: 500;
            transition: all 200ms var(--ease);
        }

        .btn-primary {
            background: var(--accent);
            color: #ffffff;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: var(--bg-subtle);
            color: var(--text-primary);
            border: 1px solid var(--border-subtle);
        }

        .btn-secondary:hover {
            background: var(--bg-elevated);
            border-color: var(--border-strong);
            transform: translateY(-1px);
        }

        .scroll-cue {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            animation: scrollCuePulse 2.4s ease-in-out infinite;
            color: var(--text-tertiary);
            font-size: 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            pointer-events: none;
            transition: opacity 250ms var(--ease);
        }

        .scroll-cue i {
            font-size: 12px;
        }

        @keyframes scrollCuePulse {
            0%, 100% { transform: translateX(-50%) translateY(0); opacity: 0.5; }
            50% { transform: translateX(-50%) translateY(6px); opacity: 1; }
        }

        @keyframes heroFadeUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== FIX 3: BIG PROMPT BOX (DEEPSEEK STYLE) ===== */
        .prompt-showcase {
            padding: 40px 24px 80px;
            display: flex;
            justify-content: center;
            position: relative;
            z-index: 5;
        }

        .prompt-container {
            width: 100%;
            max-width: 720px;
        }

        .prompt-box {
            background: var(--bg-elevated);
            border: 1px solid var(--border-medium);
            border-radius: var(--radius-xl);
            padding: 20px 24px 16px;
            transition: 
                border-color 200ms cubic-bezier(0.4, 0, 0.2, 1),
                box-shadow 200ms cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .prompt-box:hover {
            border-color: var(--accent-border);
            box-shadow: var(--shadow-md);
        }

        .prompt-input-area {
            padding: 12px 0 32px;
            min-height: 80px;
        }

        .prompt-placeholder {
            font-size: 18px;
            color: var(--text-tertiary);
            font-family: var(--font-sans);
        }

        .prompt-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            border-top: 1px solid var(--border-subtle);
        }

        .prompt-left-actions,
        .prompt-right-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .prompt-icon-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-md);
            background: transparent;
            border: 1px solid transparent;
            color: var(--text-secondary);
            font-size: 16px;
            transition: 
                background 150ms cubic-bezier(0.4, 0, 0.2, 1),
                color 150ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        .prompt-icon-btn:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .prompt-send-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-full);
            background: var(--accent);
            border: none;
            color: #ffffff;
            font-size: 14px;
            transition: 
                background 150ms cubic-bezier(0.4, 0, 0.2, 1),
                transform 150ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        .prompt-send-btn:hover {
            background: var(--accent-hover);
            transform: scale(1.05);
        }

        .prompt-send-btn:active {
            transform: scale(0.95);
        }

        .prompt-hint {
            text-align: center;
            font-size: 13px;
            color: var(--text-tertiary);
            margin-top: 16px;
        }

        /* ===== TECHNIQUE 3: STICKY SCROLL STORY (SHOWCASE) ===== */
        .showcase-section {
            height: 300vh;
            position: relative;
        }

        .showcase-sticky {
            position: sticky;
            top: 0;
            height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 48px;
            overflow: hidden;
        }

        .showcase-content {
            position: relative;
            height: 280px;
        }

        .showcase-text {
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            width: 100%;
            opacity: 0;
            pointer-events: none;
            transition: opacity 400ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        .showcase-text.active {
            opacity: 1;
            pointer-events: auto;
        }

        .showcase-step-tag {
            font-family: var(--font-mono);
            font-size: 12px;
            font-weight: 500;
            color: var(--accent-text);
            letter-spacing: 0.08em;
            margin-bottom: 12px;
        }

        .showcase-text h2 {
            font-size: clamp(30px, 4vw, 42px);
            font-weight: 600;
            letter-spacing: -0.02em;
            line-height: 1.15;
            margin-bottom: 16px;
        }

        .showcase-text p {
            font-size: 16px;
            color: var(--text-secondary);
            line-height: 1.65;
            max-width: 480px;
        }

        .showcase-visual {
            position: relative;
            width: 100%;
            height: 460px;
        }

        /* FIX 1: LIGHT MODE & DARK MODE ADAPTIVE MOCKUP */
        .visual-layer {
            position: absolute;
            inset: 0;
            opacity: 0;
            transform: translateY(30px);
            transition: 
                opacity 500ms cubic-bezier(0.4, 0, 0.2, 1),
                transform 500ms cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-xl);
            padding: 24px;
            box-shadow: var(--shadow-md);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            pointer-events: none;
            color: var(--text-primary);
        }

        .visual-layer.active {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .mockup-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border-subtle);
        }

        .mockup-window-dots {
            display: flex;
            gap: 6px;
        }

        .mockup-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--border-medium);
        }

        .mockup-pill-group {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .mockup-badge {
            font-family: var(--font-mono);
            font-size: 11px;
            padding: 3px 8px;
            border-radius: var(--radius-sm);
            background: var(--bg-subtle);
            border: 1px solid var(--border-subtle);
            color: var(--text-secondary);
            display: inline-flex;
            align-items: center;
        }

        .mockup-badge.active {
            background: var(--accent-subtle);
            border-color: var(--accent-subtle);
            color: var(--accent);
            font-weight: 500;
        }

        .mockup-body {
            flex: 1;
            padding: 16px 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
            justify-content: center;
        }

        .mockup-bubble-user {
            align-self: flex-end;
            max-width: 85%;
            background: var(--accent-subtle);
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            padding: 10px 14px;
            border-radius: var(--radius-md) var(--radius-md) 4px var(--radius-md);
            font-size: 13px;
        }

        .mockup-bubble-ai {
            align-self: flex-start;
            max-width: 95%;
            background: var(--bg-subtle);
            border: 1px solid var(--border-subtle);
            padding: 12px 14px;
            border-radius: var(--radius-md) var(--radius-md) var(--radius-md) 4px;
            font-size: 13px;
            color: var(--text-primary);
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .mockup-code-block {
            font-family: var(--font-mono);
            font-size: 12px;
            background: var(--bg-subtle);
            border: 1px solid var(--border-subtle);
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            color: var(--accent);
            overflow-x: auto;
        }

        .mockup-meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: var(--font-mono);
            font-size: 11px;
            color: var(--text-tertiary);
            padding-top: 12px;
            border-top: 1px solid var(--border-subtle);
        }

        /* Mockup Slide 2 (Document Analysis) */
        .doc-preview-card {
            background: var(--bg-subtle);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-md);
            padding: 14px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .doc-highlight {
            background: var(--accent-subtle);
            border-left: 2px solid var(--accent);
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 12px;
            color: var(--text-primary);
        }

        .doc-metric-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 4px;
        }

        .doc-metric-item {
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            padding: 8px 10px;
            border-radius: var(--radius-sm);
        }

        .doc-metric-item span {
            font-size: 11px;
            color: var(--text-tertiary);
            display: block;
        }

        .doc-metric-item strong {
            font-family: var(--font-mono);
            font-size: 13px;
            color: var(--text-primary);
        }

        /* Mockup Slide 3 (Deep Research) */
        .search-pill-list {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .search-source-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--bg-subtle);
            border: 1px solid var(--border-subtle);
            padding: 4px 10px;
            border-radius: var(--radius-full);
            font-size: 11px;
            color: var(--text-secondary);
        }

        .research-summary-box {
            background: var(--bg-subtle);
            border: 1px solid var(--border-subtle);
            padding: 14px;
            border-radius: var(--radius-md);
            font-size: 13px;
            line-height: 1.6;
            color: var(--text-primary);
        }

        /* ===== FIX 2: HORIZONTAL SCROLL ON VERTICAL (NO OVERLAP) ===== */
        .features-header {
            position: relative;
            padding: 60px 48px 24px;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
        }

        .features-header h2 {
            font-size: clamp(28px, 4vw, 38px);
            font-weight: 600;
            letter-spacing: -0.02em;
            margin-bottom: 8px;
        }

        .features-header p {
            font-size: 15px;
            color: var(--text-secondary);
        }

        .features-track-wrapper {
            position: sticky;
            top: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            overflow: hidden;
            z-index: 1;
            margin-top: 0;
        }

        /* Section wrapper tetap scroll-linked */
        .features-section {
            position: relative;
            height: 170vh;
        }

        /* Track di dalam wrapper tetap scroll-linked */
        .features-track {
            display: flex;
            gap: 24px;
            padding: 0 48px;
            will-change: transform;
        }

        .feature-card {
            flex: 0 0 380px;
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            padding: 32px;
            height: 340px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: border-color 250ms var(--ease);
        }

        .feature-card:hover {
            border-color: var(--border-strong);
        }

        .feature-card-top .feature-tag {
            font-family: var(--font-mono);
            font-size: 11px;
            color: var(--accent-text);
            letter-spacing: 0.08em;
            margin-bottom: 14px;
            display: block;
        }

        .feature-card h3 {
            font-size: 20px;
            font-weight: 600;
            letter-spacing: -0.02em;
            margin-bottom: 12px;
        }

        .feature-card p {
            font-size: 14px;
            color: var(--text-secondary);
            line-height: 1.6;
        }

        .feature-card-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: var(--font-mono);
            font-size: 12px;
            color: var(--text-tertiary);
            border-top: 1px solid var(--border-subtle);
            padding-top: 14px;
        }

        /* ===== COMPARISON SECTION ===== */
        .comparison-section {
            max-width: 1080px;
            margin: 40px auto 80px;
            padding: 0 24px;
            text-align: center;
        }

        .comparison-title {
            font-size: clamp(28px, 4vw, 36px);
            font-weight: 600;
            letter-spacing: -0.02em;
            margin-bottom: 12px;
        }

        .comparison-sub {
            font-size: 15px;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto 36px;
            line-height: 1.6;
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: var(--radius-lg);
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.16);
        }

        .comparison-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            min-width: 720px;
            text-align: left;
        }

        .comparison-table thead th {
            background: var(--bg-subtle);
            color: var(--text-secondary);
            font-weight: 600;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-subtle);
            font-size: 12px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            font-family: var(--font-mono);
        }

        .comparison-table thead th:first-child {
            width: 28%;
        }

        .comparison-table tbody td {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-subtle);
            color: var(--text-secondary);
            transition: background-color 150ms var(--ease);
        }

        .comparison-table tbody tr:last-child td {
            border-bottom: none;
        }

        .comparison-table tbody tr:hover td {
            background: var(--bg-subtle);
        }

        .comparison-table tbody td:first-child {
            font-weight: 600;
            color: var(--text-primary);
        }

        .indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 500;
            padding: 2px 8px;
            border-radius: var(--radius-full);
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

        /* ===== FOOTER ===== */
        .site-footer {
            border-top: 1px solid var(--border-subtle);
            background: var(--bg-elevated);
            padding: 64px 24px 32px;
            color: var(--text-secondary);
        }

        .footer-container {
            max-width: 1080px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr;
            gap: 48px;
        }

        .footer-brand-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .footer-brand-title span {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            letter-spacing: -0.02em;
        }

        .footer-brand-desc {
            font-size: 13px;
            line-height: 1.6;
            color: var(--text-tertiary);
            max-width: 300px;
        }

        .footer-col h4 {
            font-size: 13px;
            font-weight: 600;
            font-family: var(--font-mono);
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--text-primary);
            margin-bottom: 16px;
        }

        .footer-col ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-col a {
            font-size: 13px;
            color: var(--text-secondary);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 150ms var(--ease);
        }

        .footer-col a:hover {
            color: var(--text-primary);
        }

        .footer-bottom {
            max-width: 1080px;
            margin: 48px auto 0;
            padding-top: 24px;
            border-top: 1px solid var(--border-subtle);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 12px;
            color: var(--text-tertiary);
        }

        .footer-bottom strong {
            color: var(--text-secondary);
        }

        /* ===== RESPONSIVE BREAKPOINTS ===== */
        @media (max-width: 1024px) {
            .showcase-sticky {
                gap: 40px;
                padding: 0 32px;
            }
            .features-header {
                padding: 60px 32px 30px;
            }
            .features-track {
                padding: 0 32px;
            }
        }

        @media (max-width: 768px) {
            .site-header {
                width: calc(100% - 32px);
                padding: 10px 16px;
            }
            .theme-toggle-btn span {
                display: none;
            }
            .hero-section {
                padding: 100px 20px 60px;
                min-height: auto;
            }
            .hero-title {
                font-size: 40px;
            }
            .hero-subheading {
                font-size: 16px;
                margin-bottom: 28px;
            }

            .prompt-showcase {
                padding: 20px 20px 60px;
            }

            /* Disable Sticky Showcase on Mobile */
            .showcase-section {
                height: auto;
                padding: 60px 0;
            }
            .showcase-sticky {
                position: static;
                height: auto;
                display: flex;
                flex-direction: column;
                gap: 48px;
                padding: 0 20px;
            }
            .showcase-content {
                position: static;
                height: auto;
                display: flex;
                flex-direction: column;
                gap: 48px;
            }
            .showcase-text {
                position: static;
                transform: none;
                opacity: 1;
                pointer-events: auto;
            }
            .showcase-visual {
                position: static;
                height: auto;
                display: flex;
                flex-direction: column;
                gap: 24px;
            }
            .visual-layer {
                position: static;
                opacity: 1;
                transform: none;
                pointer-events: auto;
                height: 380px;
            }

            /* Disable Features Horizontal Scroll on Mobile */
            .features-section {
                height: auto;
                padding: 30px 0 20px;
            }
            .features-header {
                padding: 0 20px 20px;
            }
            .features-track-wrapper {
                position: static;
                height: auto;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .features-track {
                transform: none !important;
                padding: 0 20px;
                gap: 16px;
            }
            .feature-card {
                flex: 0 0 300px;
                height: auto;
                min-height: 290px;
                padding: 24px;
            }
            .comparison-section {
                margin: 30px auto 60px;
            }

            .footer-container {
                grid-template-columns: 1fr;
                gap: 32px;
            }
        }

        /* ===== PREFERS-REDUCED-MOTION (MANDATORY) ===== */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
            }
            .reveal {
                opacity: 1 !important;
                transform: none !important;
            }
            .showcase-text, .visual-layer {
                opacity: 1 !important;
                transform: none !important;
            }
            .scroll-progress {
                display: none !important;
            }
            .hero-parallax {
                transform: none !important;
                opacity: 1 !important;
            }
            .features-track {
                transform: none !important;
            }
            .scroll-cue {
                display: none !important;
            }
        }

        /* ===== CUSTOM CURSOR ===== */
        .cursor-dot,
        .cursor-ring {
            position: fixed;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 99999;
            will-change: transform;
            transform: translate3d(-100px, -100px, 0);
            mix-blend-mode: difference;
            /* Default: hidden. Hanya muncul saat hover target. */
            opacity: 0;
            transition: opacity 200ms cubic-bezier(0.4, 0, 0.2, 1),
                        width 300ms cubic-bezier(0.4, 0, 0.2, 1),
                        height 300ms cubic-bezier(0.4, 0, 0.2, 1),
                        margin 300ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Dot kecil di tengah ring */
        .cursor-dot {
            width: 8px;
            height: 8px;
            background: #FFFFFF;
            border-radius: 50%;
            margin-left: -4px;
            margin-top: -4px;
        }

        /* Ring utama */
        .cursor-ring {
            width: 8px;
            height: 8px;
            background: #FFFFFF;
            border-radius: 50%;
            margin-left: -4px;
            margin-top: -4px;
        }

        /* Saat hover di target: munculkan cursor */
        body.cursor-active .cursor-dot,
        body.cursor-active .cursor-ring {
            opacity: 1;
        }

        /* Saat hover di target: ring membesar */
        body.cursor-active .cursor-ring {
            width: 100px;
            height: 100px;
            margin-left: -50px;
            margin-top: -50px;
        }

        /* ===== LIGHT MODE OVERRIDE ===== */
        /* Di light mode, background putih + cursor putih -> invisible.
           Ganti cursor jadi hitam pekat biar kontras. */
        body.light-mode .cursor-dot,
        body.light-mode .cursor-ring {
            background: #0B0D12;
        }

        /* ===== REDUCED MOTION ===== */
        @media (prefers-reduced-motion: reduce) {
            .cursor-dot,
            .cursor-ring {
                display: none !important;
            }
        }

        /* ===== TOUCH DEVICE ===== */
        @media (hover: none) and (pointer: coarse) {
            .cursor-dot,
            .cursor-ring {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    {{-- Background subtle overlays --}}
    <div class="bg-subtle-radial"></div>
    <div class="bg-dot-pattern"></div>

    {{-- Technique 5: Scroll Progress Bar --}}
    <div class="scroll-progress" aria-hidden="true"></div>

    {{-- Sticky Floating Header --}}
    <header class="site-header">
        <div class="header-brand">
            <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="SAHAJA AI Logo" class="header-logo" loading="eager">
            <span class="header-title">SAHAJA AI</span>
        </div>
        <div class="header-actions">
            <button class="theme-toggle-btn" id="themeToggleBtn" aria-label="Toggle tema tampilan">
                <i class="fas fa-moon" id="themeIcon"></i>
                <span id="themeLabel">Gelap</span>
            </button>
            <a href="{{ route('login') }}" class="header-login-btn">Masuk</a>
        </div>
    </header>

    {{-- Technique 2: Hero Section (Parallax + Fade) --}}
    <section class="hero-section">
        <div class="hero-parallax">
            <div class="hero-badge">v5.0 · AI Assistant</div>
            <h1 class="hero-title">SAHAJA AI</h1>
            <p class="hero-subheading">
                Asisten cerdas untuk brainstorming, coding, dan analisis dokumen. Dibangun dengan model AI terkini.
            </p>
            <div class="hero-cta-group">
                <a href="{{ route('register') }}" class="btn btn-primary">
                    Mulai Sekarang <i class="fas fa-arrow-right" style="font-size:12px;"></i>
                </a>
                <a href="{{ route('login') }}" class="btn btn-secondary">
                    Masuk
                </a>
            </div>
        </div>

        <div class="scroll-cue" aria-hidden="true">
            <span>Scroll</span>
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    {{-- FIX 3: Big Prompt Box (DeepSeek Style) --}}
    <section class="prompt-showcase">
        <div class="prompt-container">
            <a href="{{ route('register') }}" style="text-decoration:none;display:block;">
                <div class="prompt-box">
                    <div class="prompt-input-area">
                        <span class="prompt-placeholder">Tanya apa saja...</span>
                    </div>
                    <div class="prompt-actions">
                        <div class="prompt-left-actions">
                            <button type="button" class="prompt-icon-btn" aria-label="Lampirkan dokumen">
                                <i class="fas fa-paperclip"></i>
                            </button>
                            <button type="button" class="prompt-icon-btn" aria-label="Pilih model">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div class="prompt-right-actions">
                            <button type="button" class="prompt-send-btn" aria-label="Kirim pertanyaan">
                                <i class="fas fa-arrow-up"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </a>
            <p class="prompt-hint">Coba gratis — tidak perlu kartu kredit</p>
        </div>
    </section>

    {{-- Technique 3: Sticky Scroll Story (Showcase) --}}
    <section class="showcase-section">
        <div class="showcase-sticky">
            <div class="showcase-content">
                <div class="showcase-text active" data-slide="0">
                    <div class="showcase-step-tag">01 — CAPABILITY</div>
                    <h2>Multi-Engine AI</h2>
                    <p>
                        Pilih dari Mistral, NVIDIA, atau Cerebras untuk pengalaman reasoning, brainstorming, dan penulisan kode berkecepatan tinggi sesuai preferensi Anda.
                    </p>
                </div>
                <div class="showcase-text" data-slide="1">
                    <div class="showcase-step-tag">02 — CAPABILITY</div>
                    <h2>Analisis Dokumen</h2>
                    <p>
                        Unggah berkas PDF, dokumen teks, atau tautan repositori kode untuk ekstraksi konteks instan dengan pemahaman mendalam tanpa batas token lokal.
                    </p>
                </div>
                <div class="showcase-text" data-slide="2">
                    <div class="showcase-step-tag">03 — CAPABILITY</div>
                    <h2>Deep Research</h2>
                    <p>
                        Riset mendalam berbekal mesin pencarian web terverifikasi dan LLM untuk menyusun sintesis komprehensif berlandaskan sumber informasi aktual.
                    </p>
                </div>
            </div>

            <div class="showcase-visual">
                {{-- Layer 0: Multi-Engine AI Mockup --}}
                <div class="visual-layer active" data-layer="0">
                    <div class="mockup-header">
                        <div class="mockup-window-dots">
                            <span class="mockup-dot"></span>
                            <span class="mockup-dot"></span>
                            <span class="mockup-dot"></span>
                        </div>
                        <div class="mockup-pill-group">
                            <span class="mockup-badge active">Mistral Small</span>
                            <span class="mockup-badge">NVIDIA NIM</span>
                            <span class="mockup-badge">Cerebras Llama</span>
                        </div>
                    </div>
                    <div class="mockup-body">
                        <div class="mockup-bubble-user">
                            Buatkan index database optimal untuk tabel riwayat pesan dengan 1 juta entri.
                        </div>
                        <div class="mockup-bubble-ai">
                            <span>Rekomendasi compound index berbasis composite key:</span>
                            <div class="mockup-code-block">CREATE INDEX idx_chat_history ON messages (conversation_id, created_at DESC);</div>
                            <span style="font-size:12px;color:var(--text-secondary);">Indeks ini memangkas waktu query scan dari O(N) ke O(log N).</span>
                        </div>
                    </div>
                    <div class="mockup-meta-row">
                        <span>Latency: 48 tokens/detik</span>
                        <span>TTFT: 120ms</span>
                    </div>
                </div>

                {{-- Layer 1: Document Analysis Mockup --}}
                <div class="visual-layer" data-layer="1">
                    <div class="mockup-header">
                        <div class="mockup-window-dots">
                            <span class="mockup-dot"></span>
                            <span class="mockup-dot"></span>
                            <span class="mockup-dot"></span>
                        </div>
                        <span class="mockup-badge active">
                            <i class="fas fa-file-pdf" style="margin-right:4px;"></i> laporan_keuangan_q3.pdf
                        </span>
                    </div>
                    <div class="mockup-body">
                        <div class="doc-preview-card">
                            <span style="font-size:11px;color:var(--text-tertiary);font-family:var(--font-mono);">BAGIAN 3.2 — EVALUASI OPERASIONAL</span>
                            <div class="doc-highlight">
                                "...margin laba operasional tercatat meningkat 14.2% YoY didorong oleh efisiensi infrastruktur cloud dan automasi alur kerja."
                            </div>
                        </div>
                        <div class="doc-metric-grid">
                            <div class="doc-metric-item">
                                <span>Margin Laba</span>
                                <strong>+14.2% YoY</strong>
                            </div>
                            <div class="doc-metric-item">
                                <span>Beban Opex</span>
                                <strong>-6.4% MoM</strong>
                            </div>
                        </div>
                    </div>
                    <div class="mockup-meta-row">
                        <span>Ekstraksi Selesai</span>
                        <span>42 Halaman Terverifikasi</span>
                    </div>
                </div>

                {{-- Layer 2: Deep Research Mockup --}}
                <div class="visual-layer" data-layer="2">
                    <div class="mockup-header">
                        <div class="mockup-window-dots">
                            <span class="mockup-dot"></span>
                            <span class="mockup-dot"></span>
                            <span class="mockup-dot"></span>
                        </div>
                        <div class="search-pill-list">
                            <span class="search-source-chip"><i class="fas fa-globe"></i> tavily.com</span>
                            <span class="search-source-chip"><i class="fas fa-book"></i> arxiv.org</span>
                            <span class="search-source-chip"><i class="fas fa-check"></i> nature.com</span>
                        </div>
                    </div>
                    <div class="mockup-body">
                        <div class="research-summary-box">
                            <strong style="color:var(--text-primary);display:block;margin-bottom:6px;">Sintesis Penemuan Terbaru:</strong>
                            <p style="color:var(--text-secondary);font-size:12px;margin-bottom:8px;">
                                Integrasi arsitektur inference berbasis spec-decoding mengurangi pemakaian VRAM hingga 35% tanpa degradasi output reasoning [1][2].
                            </p>
                            <span style="font-size:11px;color:var(--accent);font-family:var(--font-mono);">
                                [1] arXiv:2603.1189 · [2] Benchmark IEEE 2026
                            </span>
                        </div>
                    </div>
                    <div class="mockup-meta-row">
                        <span>98% Akurasi Sintesis</span>
                        <span>8 Sumber Divalidasi</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FIX 2: Horizontal Scroll on Vertical (Features) --}}
    <section class="features-section">
        <div class="features-header">
            <h2>Fitur Unggulan</h2>
            <p>Semua yang kamu butuhkan dalam satu asisten cerdas.</p>
        </div>
        <div class="features-track-wrapper">
            <div class="features-track">
                <div class="feature-card">
                    <div class="feature-card-top">
                        <span class="feature-tag">01 · ARCHITECTURE</span>
                        <h3>Multi-Engine AI</h3>
                        <p>Akses ke model open-weights dan reasoning tercanggih: Mistral Small, NVIDIA NIM, dan Cerebras Llama dalam satu antarmuka terpadu.</p>
                    </div>
                    <div class="feature-card-bottom">
                        <span>Engine Router</span>
                        <i class="fas fa-microchip"></i>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-card-top">
                        <span class="feature-tag">02 · DEVELOPER TOOLS</span>
                        <h3>Code Assistant</h3>
                        <p>Optimasi algoritma, inspeksi celah keamanan kode, serta generasi arsitektur backend Laravel, Python, dan React dengan penjelasan terstruktur.</p>
                    </div>
                    <div class="feature-card-bottom">
                        <span>Syntax & Logic</span>
                        <i class="fas fa-code"></i>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-card-top">
                        <span class="feature-tag">03 · CONTEXT RAG</span>
                        <h3>Analisis Dokumen</h3>
                        <p>Parsing cerdas dokumen PDF, spreadsheet, maupun struktur berkas repositori kode untuk penemuan fakta akurat tanpa halusinasi.</p>
                    </div>
                    <div class="feature-card-bottom">
                        <span>Multi-File Ingestion</span>
                        <i class="fas fa-file-lines"></i>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-card-top">
                        <span class="feature-tag">04 · REAL-TIME GROUNDING</span>
                        <h3>Deep Research</h3>
                        <p>Mesin verifikasi web terintegrasi yang menyaring sumber kredibel secara otomatis untuk menjawab pertanyaan dinamis terkini.</p>
                    </div>
                    <div class="feature-card-bottom">
                        <span>Live Web Grounding</span>
                        <i class="fas fa-compass"></i>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-card-top">
                        <span class="feature-tag">05 · LOCAL RELEVANCE</span>
                        <h3>Konteks Lokal Indonesia</h3>
                        <p>Memahami tata bahasa, perbendaharaan istilah teknis, dan nuansa birokrasi Indonesia dengan akurasi dan etika yang kontekstual.</p>
                    </div>
                    <div class="feature-card-bottom">
                        <span>Native Context</span>
                        <i class="fas fa-language"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Comparison Section (Reveal on Scroll) --}}
    <section class="comparison-section reveal">
        <h2 class="comparison-title">Mengapa SAHAJA AI?</h2>
        <p class="comparison-sub">
            Dirancang secara presisi sebagai asisten AI lokal yang autentik, cepat, dan cerdas dalam memberikan jawaban teknis maupun strategis.
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

    {{-- Footer --}}
    <footer class="site-footer reveal">
        <div class="footer-container">
            <div class="footer-col">
                <div class="footer-brand-title">
                    <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="SAHAJA AI" style="width:24px;height:24px;border-radius:6px;object-fit:contain;" loading="lazy">
                    <span>SAHAJA AI</span>
                </div>
                <p class="footer-brand-desc">
                    Asisten AI lokal yang autentik, cerdas, dan siap mendampingi kebutuhan produktivitas serta riset teknis Anda.
                </p>
            </div>

            <div class="footer-col">
                <h4>Proyek Lain</h4>
                <ul>
                    <li>
                        <a href="https://surat-admin.alwaysdata.net/" target="_blank" rel="noopener">
                            <i class="fas fa-external-link-alt" style="font-size:11px;"></i> Layanan Mandiri &amp; Surat Desa
                        </a>
                    </li>
                    <li>
                        <a href="https://sistem-deteksi-penyakit-daun.vercel.app/" target="_blank" rel="noopener">
                            <i class="fas fa-external-link-alt" style="font-size:11px;"></i> Deteksi Dini Penyakit Daun Patat
                        </a>
                    </li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Tentang Pengembang</h4>
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
            <span>&copy; {{ date('Y') }} SAHAJA AI. Hak cipta dilindungi.</span>
            <span>Dibuat oleh <strong>Faqih Hidayah</strong></span>
        </div>
    </footer>

    <!-- Custom Cursor (untuk efek DeepSeek-style) -->
    <div class="cursor-dot" aria-hidden="true"></div>
    <div class="cursor-ring" aria-hidden="true"></div>

    {{-- Scroll-Driven Animation & UI Interaction Script --}}
    <script>
        (function() {
            // Theme toggle state
            const body = document.body;
            const themeToggle = document.getElementById('themeToggleBtn');
            const themeIcon = document.getElementById('themeIcon');
            const themeLabel = document.getElementById('themeLabel');

            const savedTheme = localStorage.getItem('sahaja-theme') || localStorage.getItem('theme');
            if (savedTheme === 'light') {
                body.classList.add('light-mode');
                document.documentElement.classList.add('light-mode');
                if (themeIcon) themeIcon.className = 'fas fa-sun';
                if (themeLabel) themeLabel.innerText = 'Terang';
            } else {
                if (themeIcon) themeIcon.className = 'fas fa-moon';
                if (themeLabel) themeLabel.innerText = 'Gelap';
            }

            if (themeToggle) {
                themeToggle.addEventListener('click', () => {
                    const isLight = body.classList.toggle('light-mode');
                    document.documentElement.classList.toggle('light-mode', isLight);
                    if (isLight) {
                        localStorage.setItem('sahaja-theme', 'light');
                        if (themeIcon) themeIcon.className = 'fas fa-sun';
                        if (themeLabel) themeLabel.innerText = 'Terang';
                    } else {
                        localStorage.setItem('sahaja-theme', 'dark');
                        if (themeIcon) themeIcon.className = 'fas fa-moon';
                        if (themeLabel) themeLabel.innerText = 'Gelap';
                    }
                });
            }

            // Technique 1: IntersectionObserver Reveal on Scroll
            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                        revealObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.15,
                rootMargin: '0px 0px -50px 0px'
            });

            document.querySelectorAll('.reveal').forEach(el => {
                revealObserver.observe(el);
            });

            // Scroll-driven animation nodes
            const progressBar = document.querySelector('.scroll-progress');
            const siteHeader = document.querySelector('.site-header');
            const heroSection = document.querySelector('.hero-section');
            const scrollCue = document.querySelector('.scroll-cue');
            const showcaseSection = document.querySelector('.showcase-section');
            const showcaseSlides = showcaseSection ? showcaseSection.querySelectorAll('.showcase-text') : [];
            const showcaseLayers = showcaseSection ? showcaseSection.querySelectorAll('.visual-layer') : [];
            const featuresSection = document.querySelector('.features-section');
            const featuresTrack = document.querySelector('.features-track');

            function updateProgressBar() {
                if (!progressBar) return;
                const scrollY = window.scrollY;
                const totalHeight = document.documentElement.scrollHeight - window.innerHeight;
                const progress = totalHeight > 0 ? (scrollY / totalHeight) * 100 : 0;
                progressBar.style.width = progress + '%';
            }

            function updateHeader() {
                if (!siteHeader) return;
                siteHeader.classList.toggle('scrolled', window.scrollY > 80);
            }

            function updateHeroParallax() {
                if (!heroSection) return;
                if (window.innerWidth <= 768) {
                    heroSection.style.setProperty('--hero-y', '0px');
                    heroSection.style.setProperty('--hero-opacity', '1');
                    return;
                }
                const scrollY = window.scrollY;
                const heroHeight = heroSection.offsetHeight;
                const progress = Math.min(scrollY / heroHeight, 1);
                const translateY = Math.min(scrollY * 0.2, 60);
                const opacity = Math.max(1 - progress * 1.4, 0);

                heroSection.style.setProperty('--hero-y', `-${translateY}px`);
                heroSection.style.setProperty('--hero-opacity', opacity);

                if (scrollCue) {
                    scrollCue.style.opacity = Math.max(1 - progress * 3, 0);
                }
            }

            function updateShowcase() {
                if (!showcaseSection || showcaseSlides.length === 0) return;
                if (window.innerWidth <= 768) return;
                const rect = showcaseSection.getBoundingClientRect();
                const sectionHeight = showcaseSection.offsetHeight - window.innerHeight;
                if (sectionHeight <= 0) return;
                const progress = Math.max(0, Math.min(1, -rect.top / sectionHeight));
                const activeIndex = Math.floor(progress * 3);
                const clampedIndex = Math.min(activeIndex, 2);

                showcaseSlides.forEach((slide, i) => {
                    slide.classList.toggle('active', i === clampedIndex);
                });
                showcaseLayers.forEach((layer, i) => {
                    layer.classList.toggle('active', i === clampedIndex);
                });
            }

            function updateFeaturesScroll() {
                if (!featuresSection || !featuresTrack) return;
                if (window.innerWidth <= 768) {
                    featuresTrack.style.transform = 'none';
                    return;
                }
                const rect = featuresSection.getBoundingClientRect();
                const sectionHeight = featuresSection.offsetHeight - window.innerHeight;
                if (sectionHeight <= 0) return;
                const progress = Math.max(0, Math.min(1, -rect.top / sectionHeight));
                const maxShift = featuresTrack.scrollWidth - window.innerWidth + 96;
                featuresTrack.style.transform = `translateX(-${progress * Math.max(maxShift, 0)}px)`;
            }

            // Scroll listener throttled via requestAnimationFrame (60fps)
            let ticking = false;
            function onScroll() {
                if (!ticking) {
                    requestAnimationFrame(() => {
                        updateProgressBar();
                        updateHeader();
                        updateHeroParallax();
                        updateShowcase();
                        updateFeaturesScroll();
                        ticking = false;
                    });
                    ticking = true;
                }
            }

            window.addEventListener('scroll', onScroll, { passive: true });
            window.addEventListener('resize', () => {
                updateShowcase();
                updateFeaturesScroll();
            }, { passive: true });

            // Initial calculation
            onScroll();
        })();

        // ===== CUSTOM CURSOR (DEEPSEEK STYLE) =====
        (function() {
            const dot = document.querySelector('.cursor-dot');
            const ring = document.querySelector('.cursor-ring');

            if (!dot || !ring) return;

            // Skip di touch device atau reduced motion
            const isTouch = window.matchMedia('(hover: none) and (pointer: coarse)').matches;
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (isTouch || prefersReducedMotion) return;

            let mouseX = 0, mouseY = 0;
            let ringX = 0, ringY = 0;
            let isHoveringTarget = false;

            // Update posisi mouse (dot: instan, ring: smooth follow)
            document.addEventListener('mousemove', (e) => {
                mouseX = e.clientX;
                mouseY = e.clientY;
                dot.style.transform = `translate3d(${mouseX}px, ${mouseY}px, 0)`;
            });

            // Smooth follow untuk ring
            function animateRing() {
                ringX += (mouseX - ringX) * 0.18;
                ringY += (mouseY - ringY) * 0.18;
                ring.style.transform = `translate3d(${ringX}px, ${ringY}px, 0)`;
                requestAnimationFrame(animateRing);
            }
            animateRing();

            // TARGET ELEMEN — spesifik tanpa tag universal
            const expandTargets = document.querySelectorAll(
                '.hero-title, .prompt-box, .btn-primary, .btn-secondary'
            );

            // Pasang event listener ke setiap target
            expandTargets.forEach((el) => {
                el.addEventListener('mouseenter', () => {
                    isHoveringTarget = true;
                    document.body.classList.add('cursor-active');
                });
                el.addEventListener('mouseleave', () => {
                    isHoveringTarget = false;
                    document.body.classList.remove('cursor-active');
                });
            });

            // Sembunyikan saat mouse keluar window
            document.addEventListener('mouseleave', () => {
                isHoveringTarget = false;
                document.body.classList.remove('cursor-active');
            });
        })();
    </script>
</body>

</html>

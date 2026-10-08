<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation — SAHAJA AI</title>

    <!-- Theme Initialization (Anti-FOUC) -->
    <script>
        (function() {
            if (localStorage.getItem('sahaja-theme') === 'light') {
                document.documentElement.classList.add('light-mode');
            }
        })();
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Highlight.js for Syntax Highlighting -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

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
            --text-disabled: #3C4043;
            --accent: #3B82F6;
            --accent-hover: #60A5FA;
            --accent-subtle: rgba(59, 130, 246, 0.12);
            --accent-border: rgba(59, 130, 246, 0.30);
            --success: #10B981;
            --success-subtle: rgba(16, 185, 129, 0.12);
            --warning: #F59E0B;
            --warning-subtle: rgba(245, 158, 11, 0.12);
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
            --font-mono: 'JetBrains Mono', monospace;
        }

        html.light-mode,
        body.light-mode {
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
            --text-disabled: #D1D5DB;
            --accent: #2563EB;
            --accent-hover: #1D4ED8;
            --accent-subtle: rgba(37, 99, 235, 0.08);
            --accent-border: rgba(37, 99, 235, 0.25);
            --success: #059669;
            --success-subtle: rgba(5, 150, 105, 0.12);
            --warning: #D97706;
            --warning-subtle: rgba(217, 119, 6, 0.12);
            --danger: #DC2626;
            --danger-subtle: rgba(220, 38, 38, 0.12);
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.10);
            --shadow-lg: 0 12px 32px rgba(0, 0, 0, 0.14);
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-base);
            color: var(--text-primary);
            min-height: 100vh;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* Header */
        .docs-header {
            position: sticky;
            top: 0;
            z-index: 40;
            background-color: var(--bg-base);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-subtle);
            height: 64px;
            display: flex;
            align-items: center;
        }

        .header-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: inherit;
        }

        .header-logo {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            object-fit: contain;
        }

        .brand-title {
            font-size: 16px;
            font-weight: 600;
            letter-spacing: -0.01em;
            color: var(--text-primary);
        }

        .badge-docs {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background-color: var(--accent-subtle);
            color: var(--accent);
            border: 1px solid var(--accent-border);
            border-radius: var(--radius-full);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 8px 16px;
            font-family: var(--font-sans);
            font-size: 13px;
            font-weight: 500;
            border-radius: var(--radius-md);
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.15s var(--ease), border-color 0.15s var(--ease), color 0.15s var(--ease);
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .btn-subtle {
            background-color: var(--bg-elevated);
            color: var(--text-secondary);
            border-color: var(--border-subtle);
        }

        .btn-subtle:hover {
            background-color: var(--bg-hover);
            color: var(--text-primary);
            border-color: var(--border-medium);
        }

        .icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: var(--radius-md);
            background-color: var(--bg-elevated);
            color: var(--text-secondary);
            border: 1px solid var(--border-subtle);
            cursor: pointer;
            transition: all 0.15s var(--ease);
        }

        .icon-btn:hover {
            background-color: var(--bg-hover);
            color: var(--text-primary);
            border-color: var(--border-medium);
        }

        /* Layout */
        .docs-layout {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 24px 80px 24px;
            display: flex;
            gap: 48px;
            position: relative;
        }

        /* Sidebar Nav */
        .docs-sidebar {
            width: 240px;
            flex-shrink: 0;
            position: sticky;
            top: 96px;
            height: calc(100vh - 120px);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding-right: 12px;
        }

        .sidebar-title {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-tertiary);
            margin-bottom: 8px;
            padding: 0 12px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: var(--radius-md);
            transition: all 0.15s var(--ease);
            border-left: 2px solid transparent;
        }

        .nav-link:hover {
            color: var(--text-primary);
            background-color: var(--bg-hover);
        }

        .nav-link.active {
            color: var(--accent);
            background-color: var(--accent-subtle);
            border-left-color: var(--accent);
            font-weight: 600;
        }

        /* Main Content */
        .docs-content {
            flex: 1;
            min-width: 0;
            max-width: 800px;
        }

        .doc-section {
            padding-bottom: 48px;
            border-bottom: 1px solid var(--border-subtle);
            margin-bottom: 48px;
            scroll-margin-top: 88px;
        }

        .doc-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .section-title {
            font-size: 24px;
            font-weight: 600;
            letter-spacing: -0.01em;
            color: var(--text-primary);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-desc {
            font-size: 15px;
            color: var(--text-secondary);
            margin-bottom: 20px;
        }

        .method-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            font-size: 12px;
            font-weight: 600;
            border-radius: var(--radius-sm);
            font-family: var(--font-mono);
            text-transform: uppercase;
        }

        .method-badge.post {
            background-color: rgba(59, 130, 246, 0.15);
            color: var(--accent);
            border: 1px solid var(--accent-border);
        }

        .method-badge.get {
            background-color: var(--success-subtle);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.25);
        }

        .endpoint-uri {
            font-family: var(--font-mono);
            font-size: 16px;
            color: var(--text-primary);
            font-weight: 600;
        }

        /* Tables */
        .table-container {
            width: 100%;
            overflow-x: auto;
            background-color: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            margin: 16px 0 24px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 13px;
        }

        th {
            background-color: var(--bg-subtle);
            color: var(--text-tertiary);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.05em;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-subtle);
        }

        td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-subtle);
            color: var(--text-secondary);
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        td code {
            font-family: var(--font-mono);
            font-size: 12px;
            background-color: var(--bg-subtle);
            color: var(--accent);
            padding: 2px 6px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-subtle);
        }

        .badge-required {
            color: var(--danger);
            font-weight: 600;
            font-size: 11px;
        }

        .badge-optional {
            color: var(--text-tertiary);
            font-size: 11px;
        }

        /* Code Blocks */
        .code-block-wrapper {
            position: relative;
            background-color: var(--bg-subtle);
            border: 1px solid var(--border-medium);
            border-radius: var(--radius-lg);
            overflow: hidden;
            margin: 16px 0;
        }

        .code-block-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 16px;
            background-color: var(--bg-elevated);
            border-bottom: 1px solid var(--border-subtle);
            font-size: 12px;
            color: var(--text-tertiary);
            font-family: var(--font-mono);
        }

        .code-copy-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            background-color: var(--bg-elevated);
            color: var(--text-secondary);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-sm);
            padding: 4px 10px;
            font-size: 12px;
            font-family: var(--font-sans);
            cursor: pointer;
            transition: all 0.15s var(--ease);
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 10;
        }

        .code-copy-btn:hover {
            color: var(--text-primary);
            border-color: var(--border-medium);
            background-color: var(--bg-hover);
        }

        pre {
            margin: 0;
            padding: 16px;
            overflow-x: auto;
            background: transparent !important;
        }

        pre code {
            font-family: var(--font-mono);
            font-size: 13px;
            line-height: 1.5;
            background: transparent !important;
            padding: 0 !important;
        }

        /* Tabs */
        .tabs-container {
            margin: 20px 0;
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            overflow: hidden;
            background-color: var(--bg-elevated);
        }

        .tabs-nav {
            display: flex;
            border-bottom: 1px solid var(--border-subtle);
            background-color: var(--bg-subtle);
            overflow-x: auto;
        }

        .tab-btn {
            padding: 10px 20px;
            font-family: var(--font-sans);
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            background: none;
            border: none;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.15s var(--ease);
            white-space: nowrap;
        }

        .tab-btn:hover {
            color: var(--text-primary);
        }

        .tab-btn.active {
            color: var(--accent);
            border-bottom-color: var(--accent);
            background-color: var(--bg-elevated);
            font-weight: 600;
        }

        .tab-pane {
            display: none;
            position: relative;
        }

        .tab-pane.active {
            display: block;
        }

        .tab-pane .code-copy-btn {
            top: 12px;
            right: 12px;
        }

        /* Callout */
        .callout {
            background-color: var(--accent-subtle);
            border: 1px solid var(--accent-border);
            border-radius: var(--radius-lg);
            padding: 16px;
            margin: 20px 0;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 14px;
            color: var(--text-primary);
        }

        .callout-icon {
            color: var(--accent);
            font-size: 16px;
            margin-top: 2px;
        }

        .callout a {
            color: var(--accent);
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 860px) {
            .docs-layout {
                flex-direction: column;
                gap: 24px;
                padding-top: 16px;
            }

            .docs-sidebar {
                width: 100%;
                height: auto;
                position: static;
                border-bottom: 1px solid var(--border-subtle);
                padding-bottom: 16px;
                overflow-x: auto;
                flex-direction: row;
                flex-wrap: wrap;
            }

            .sidebar-title {
                display: none;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="docs-header">
        <div class="header-container">
            <a href="{{ route('chat.index') }}" class="header-brand">
                <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="SAHAJA AI" class="header-logo">
                <span class="brand-title">SAHAJA AI Docs</span>
                <span class="badge-docs">v1</span>
            </a>

            <div class="header-actions">
                <button type="button" class="icon-btn" id="themeToggleBtn" aria-label="Toggle tema" title="Ganti Tema">
                    <i class="fas fa-moon" id="themeIcon"></i>
                </button>
                <a href="{{ route('developer.index') }}" class="btn btn-subtle">
                    <i class="fas fa-key"></i>
                    <span>Developer Portal</span>
                </a>
                <a href="{{ route('chat.index') }}" class="btn btn-subtle">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Chat</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Documentation Layout -->
    <div class="docs-layout">
        <!-- Sidebar Navigation -->
        <aside class="docs-sidebar">
            <div class="sidebar-title">Panduan API</div>
            <a href="#overview" class="nav-link active" data-section="overview">
                <i class="fas fa-compass" style="width: 16px;"></i>
                <span>Overview</span>
            </a>
            <a href="#auth" class="nav-link" data-section="auth">
                <i class="fas fa-lock" style="width: 16px;"></i>
                <span>Authentication</span>
            </a>
            <a href="#endpoint-chat" class="nav-link" data-section="endpoint-chat">
                <i class="fas fa-comment-dots" style="width: 16px;"></i>
                <span>POST /chat</span>
            </a>
            <a href="#endpoint-me" class="nav-link" data-section="endpoint-me">
                <i class="fas fa-user-shield" style="width: 16px;"></i>
                <span>GET /me</span>
            </a>
            <a href="#error-codes" class="nav-link" data-section="error-codes">
                <i class="fas fa-triangle-exclamation" style="width: 16px;"></i>
                <span>Error Codes</span>
            </a>
            <a href="#rate-limits" class="nav-link" data-section="rate-limits">
                <i class="fas fa-gauge-high" style="width: 16px;"></i>
                <span>Rate Limits</span>
            </a>
            <a href="#code-examples" class="nav-link" data-section="code-examples">
                <i class="fas fa-code" style="width: 16px;"></i>
                <span>Code Examples</span>
            </a>
        </aside>

        <!-- Content Area -->
        <main class="docs-content">

            <!-- 1. Overview -->
            <section id="overview" class="doc-section">
                <h1 class="section-title">Overview</h1>
                <p class="section-desc">
                    SAHAJA AI API memungkinkan Anda integrasi chatbot AI ke aplikasi apa pun. Endpoint tersedia via HTTPS dengan format response standar JSON.
                </p>

                <h3 style="font-size: 14px; font-weight: 600; margin-bottom: 8px;">Base URL</h3>
                <div class="code-block-wrapper">
                    <button type="button" class="code-copy-btn" onclick="copySnippet(this, 'https://sahaja-chatbot-ai.alwaysdata.net/api/v1')">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                    <pre><code class="language-plaintext">https://sahaja-chatbot-ai.alwaysdata.net/api/v1</code></pre>
                </div>
            </section>

            <!-- 2. Authentication -->
            <section id="auth" class="doc-section">
                <h2 class="section-title">Authentication</h2>
                <p class="section-desc">
                    Setiap request memerlukan API key di header <code>Authorization</code> dengan skema Bearer token.
                </p>

                <div class="code-block-wrapper">
                    <button type="button" class="code-copy-btn" onclick="copySnippet(this, 'Authorization: Bearer sahaja_sk_xxxxxxxxxxxxx')">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                    <pre><code class="language-http">Authorization: Bearer sahaja_sk_xxxxxxxxxxxxx</code></pre>
                </div>

                <div class="callout">
                    <i class="fas fa-info-circle callout-icon"></i>
                    <div>
                        Dapatkan API key Anda di <a href="{{ route('developer.index') }}">Developer Portal</a>. Harap jaga kerahasiaan key dan jangan pernah commit token ke repository publik.
                    </div>
                </div>
            </section>

            <!-- 3. POST /api/v1/chat -->
            <section id="endpoint-chat" class="doc-section">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                    <span class="method-badge post">POST</span>
                    <span class="endpoint-uri">/api/v1/chat</span>
                </div>
                <p class="section-desc">
                    Mengirim pesan user ke SAHAJA AI dan menerima jawaban inferensi secara sinkron.
                </p>

                <h3 style="font-size: 14px; font-weight: 600; margin-top: 24px;">Request Body</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Field</th>
                                <th>Type</th>
                                <th>Required</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>message</code></td>
                                <td>string</td>
                                <td><span class="badge-required">Yes</span></td>
                                <td>Pesan teks user, maksimal 10.000 karakter.</td>
                            </tr>
                            <tr>
                                <td><code>mode</code></td>
                                <td>string</td>
                                <td><span class="badge-optional">No</span></td>
                                <td>Pilihan mode AI: <code>auto</code>, <code>fast</code>, <code>smart</code>, <code>coding</code> (default: <code>auto</code>).</td>
                            </tr>
                            <tr>
                                <td><code>max_tokens</code></td>
                                <td>integer</td>
                                <td><span class="badge-optional">No</span></td>
                                <td>Maksimal token output: 100 s/d 8192 (default: 2048).</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3 style="font-size: 14px; font-weight: 600; margin-top: 24px;">Response Example</h3>
                <div class="code-block-wrapper">
                    <button type="button" class="code-copy-btn" onclick="copySnippetFromCode(this)">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                    <pre><code class="language-json">{
  "success": true,
  "data": {
    "reply": "Halo! Saya SAHAJA AI...",
    "mode": "fast",
    "model": "ministral-14b-latest",
    "provider": "mistral"
  },
  "usage": {
    "daily_limit": 100,
    "usage_today": 5,
    "remaining": 95
  },
  "meta": {
    "response_time_ms": 1234,
    "timestamp": "2026-10-08T14:30:00+07:00"
  }
}</code></pre>
                </div>
            </section>

            <!-- 4. GET /api/v1/me -->
            <section id="endpoint-me" class="doc-section">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                    <span class="method-badge get">GET</span>
                    <span class="endpoint-uri">/api/v1/me</span>
                </div>
                <p class="section-desc">
                    Mengecek metadata key yang sedang digunakan, sisa kuota harian, serta data pemilik akun.
                </p>

                <h3 style="font-size: 14px; font-weight: 600; margin-top: 24px;">Response Example</h3>
                <div class="code-block-wrapper">
                    <button type="button" class="code-copy-btn" onclick="copySnippetFromCode(this)">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                    <pre><code class="language-json">{
  "success": true,
  "data": {
    "key_name": "My API Key",
    "masked_key": "sahaja_sk_QL...bbmx",
    "is_active": true,
    "daily_limit": 100,
    "usage_today": 5,
    "usage_total": 250,
    "last_used_at": "2026-10-08T14:30:00+07:00",
    "owner": {
      "name": "Faqih Hidayah",
      "email": "faqih@email.com"
    }
  }
}</code></pre>
                </div>
            </section>

            <!-- 5. Error Codes -->
            <section id="error-codes" class="doc-section">
                <h2 class="section-title">Error Codes</h2>
                <p class="section-desc">
                    Respons error mengembalikan HTTP status code sesuai kategori dan JSON body yang menjelaskan penyebab kegagalan.
                </p>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Error Code</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>401</code></td>
                                <td><code>missing_api_key</code></td>
                                <td>Header Authorization tidak ditemukan atau format bukan Bearer token.</td>
                            </tr>
                            <tr>
                                <td><code>401</code></td>
                                <td><code>invalid_api_key</code></td>
                                <td>API key tidak valid atau tidak cocok dengan data sistem.</td>
                            </tr>
                            <tr>
                                <td><code>403</code></td>
                                <td><code>inactive_api_key</code></td>
                                <td>API key dinonaktifkan oleh pemilik key.</td>
                            </tr>
                            <tr>
                                <td><code>422</code></td>
                                <td><code>validation_error</code></td>
                                <td>Data request body tidak valid (misal: message kosong).</td>
                            </tr>
                            <tr>
                                <td><code>429</code></td>
                                <td><code>quota_exceeded</code></td>
                                <td>Quota harian untuk API key telah habis.</td>
                            </tr>
                            <tr>
                                <td><code>500</code></td>
                                <td><code>server_error</code></td>
                                <td>Terjadi gangguan internal pada server inferensi.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- 6. Rate Limits -->
            <section id="rate-limits" class="doc-section">
                <h2 class="section-title">Rate Limits</h2>
                <p class="section-desc">
                    Free tier: <strong>100 request/hari</strong> per API key. Kuota direset otomatis setiap pukul <strong>00:00 WIB</strong> (Waktu Indonesia Barat).
                </p>
                <p class="section-desc">
                    Setiap response header atau response body menyertakan informasi pemakaian terkini: <code>daily_limit</code>, <code>usage_today</code>, dan <code>remaining</code>.
                </p>
            </section>

            <!-- 7. Code Examples -->
            <section id="code-examples" class="doc-section">
                <h2 class="section-title">Code Examples</h2>
                <p class="section-desc">
                    Contoh implementasi pemanggilan endpoint <code>/api/v1/chat</code> di berbagai bahasa pemrograman.
                </p>

                <div class="tabs-container">
                    <div class="tabs-nav">
                        <button type="button" class="tab-btn active" onclick="switchTab(this, 'tab-curl')">cURL</button>
                        <button type="button" class="tab-btn" onclick="switchTab(this, 'tab-php')">PHP</button>
                        <button type="button" class="tab-btn" onclick="switchTab(this, 'tab-js')">JavaScript</button>
                        <button type="button" class="tab-btn" onclick="switchTab(this, 'tab-python')">Python</button>
                    </div>

                    <!-- Tab cURL -->
                    <div class="tab-pane active" id="tab-curl">
                        <button type="button" class="code-copy-btn" onclick="copySnippetFromCode(this)">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                        <pre><code class="language-bash">curl -X POST https://sahaja-chatbot-ai.alwaysdata.net/api/v1/chat \
  -H "Authorization: Bearer sahaja_sk_xxxxx" \
  -H "Content-Type: application/json" \
  -d '{"message": "Halo SAHAJA AI", "mode": "fast"}'</code></pre>
                    </div>

                    <!-- Tab PHP -->
                    <div class="tab-pane" id="tab-php">
                        <button type="button" class="code-copy-btn" onclick="copySnippetFromCode(this)">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                        <pre><code class="language-php">&lt;?php
$apiKey = 'sahaja_sk_xxxxx';
$ch = curl_init('https://sahaja-chatbot-ai.alwaysdata.net/api/v1/chat');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json',
    ],
    CURLOPT_POSTFIELDS => json_encode([
        'message' => 'Halo SAHAJA AI',
        'mode' => 'fast',
    ]),
]);
$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);
echo $data['data']['reply'];</code></pre>
                    </div>

                    <!-- Tab JS -->
                    <div class="tab-pane" id="tab-js">
                        <button type="button" class="code-copy-btn" onclick="copySnippetFromCode(this)">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                        <pre><code class="language-javascript">const apiKey = 'sahaja_sk_xxxxx';
const response = await fetch('https://sahaja-chatbot-ai.alwaysdata.net/api/v1/chat', {
    method: 'POST',
    headers: {
        'Authorization': `Bearer ${apiKey}`,
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        message: 'Halo SAHAJA AI',
        mode: 'fast',
    }),
});
const data = await response.json();
console.log(data.data.reply);</code></pre>
                    </div>

                    <!-- Tab Python -->
                    <div class="tab-pane" id="tab-python">
                        <button type="button" class="code-copy-btn" onclick="copySnippetFromCode(this)">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                        <pre><code class="language-python">import requests

api_key = 'sahaja_sk_xxxxx'
response = requests.post(
    'https://sahaja-chatbot-ai.alwaysdata.net/api/v1/chat',
    headers={
        'Authorization': f'Bearer {api_key}',
        'Content-Type': 'application/json',
    },
    json={
        'message': 'Halo SAHAJA AI',
        'mode': 'fast',
    }
)
data = response.json()
print(data['data']['reply'])</code></pre>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <script>
        // Init highlight.js
        hljs.highlightAll();

        // Theme toggle logic
        const themeToggleBtn = document.getElementById('themeToggleBtn');
        const themeIcon = document.getElementById('themeIcon');

        function updateThemeIcon() {
            const isLight = document.documentElement.classList.contains('light-mode');
            if (themeIcon) {
                themeIcon.className = isLight ? 'fas fa-sun' : 'fas fa-moon';
            }
        }
        updateThemeIcon();

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                const isLight = document.documentElement.classList.toggle('light-mode');
                document.body.classList.toggle('light-mode', isLight);
                localStorage.setItem('sahaja-theme', isLight ? 'light' : 'dark');
                updateThemeIcon();
            });
        }

        // Tab switching
        function switchTab(btn, tabId) {
            const tabsNav = btn.parentElement;
            tabsNav.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const container = tabsNav.parentElement;
            container.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            const targetPane = document.getElementById(tabId);
            if (targetPane) {
                targetPane.classList.add('active');
            }
        }

        // Copy button functions
        function copySnippet(btn, text) {
            navigator.clipboard.writeText(text).then(() => {
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check" style="color: var(--success);"></i> Copied';
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                }, 2000);
            });
        }

        function copySnippetFromCode(btn) {
            const wrapper = btn.closest('.code-block-wrapper, .tab-pane');
            const codeEl = wrapper ? wrapper.querySelector('pre code') : null;
            if (!codeEl) return;
            const text = codeEl.innerText;
            copySnippet(btn, text);
        }

        // IntersectionObserver for Sidebar Active Highlight
        const sections = document.querySelectorAll('.doc-section');
        const navLinks = document.querySelectorAll('.docs-sidebar .nav-link');

        const observerOptions = {
            root: null,
            rootMargin: '-20% 0px -70% 0px',
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    navLinks.forEach(link => {
                        if (link.getAttribute('data-section') === id) {
                            link.classList.add('active');
                        } else {
                            link.classList.remove('active');
                        }
                    });
                }
            });
        }, observerOptions);

        sections.forEach(section => observer.observe(section));
    </script>
</body>
</html>

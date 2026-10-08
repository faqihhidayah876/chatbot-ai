<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Developer Portal — SAHAJA AI</title>

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
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-base);
            color: var(--text-primary);
            min-height: 100vh;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* Header */
        .portal-header {
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

        .badge-dev {
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

        .btn-primary {
            background-color: var(--accent);
            color: #FFFFFF;
        }

        .btn-primary:hover {
            background-color: var(--accent-hover);
        }

        .btn-danger-outline {
            background-color: transparent;
            color: var(--danger);
            border-color: var(--danger-subtle);
        }

        .btn-danger-outline:hover {
            background-color: var(--danger-subtle);
            border-color: var(--danger);
        }

        .btn-sm {
            padding: 5px 12px;
            font-size: 12px;
            border-radius: var(--radius-sm);
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

        /* Main layout */
        .portal-main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 24px 64px 24px;
        }

        /* Page Title */
        .page-header {
            margin-bottom: 32px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 600;
            letter-spacing: -0.02em;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .page-subtitle {
            font-size: 15px;
            color: var(--text-secondary);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .stat-card {
            background-color: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            box-shadow: var(--shadow-sm);
        }

        .stat-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-tertiary);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.1;
        }

        /* Chart Section */
        .chart-section {
            background-color: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            padding: 24px;
            margin-bottom: 32px;
            box-shadow: var(--shadow-sm);
        }

        .chart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .section-heading {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .chart-container {
            position: relative;
            height: 280px;
            width: 100%;
        }

        /* API Keys Section */
        .keys-section {
            margin-top: 16px;
        }

        .keys-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .keys-title-wrapper {
            display: flex;
            align-items: baseline;
            gap: 10px;
        }

        .keys-counter {
            font-size: 13px;
            color: var(--text-tertiary);
            font-weight: 500;
        }

        .keys-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .key-card {
            background-color: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            transition: border-color 0.15s var(--ease), box-shadow 0.15s var(--ease);
            box-shadow: var(--shadow-sm);
        }

        .key-card:hover {
            border-color: var(--border-medium);
        }

        .key-info {
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
            min-width: 0;
        }

        .key-name-row {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .key-name {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: 600;
            border-radius: var(--radius-full);
        }

        .badge-status.active {
            background-color: var(--success-subtle);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.25);
        }

        .badge-status.inactive {
            background-color: var(--warning-subtle);
            color: var(--warning);
            border: 1px solid rgba(245, 158, 11, 0.25);
        }

        .key-masked {
            font-family: var(--font-mono);
            font-size: 13px;
            color: var(--text-secondary);
            user-select: all;
        }

        .key-meta-row {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 12px;
            color: var(--text-tertiary);
            flex-wrap: wrap;
            margin-top: 4px;
        }

        .key-usage-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .progress-bar-bg {
            width: 100px;
            height: 6px;
            background-color: var(--bg-subtle);
            border-radius: var(--radius-full);
            overflow: hidden;
            border: 1px solid var(--border-subtle);
        }

        .progress-bar-fill {
            height: 100%;
            background-color: var(--accent);
            border-radius: var(--radius-full);
            transition: width 0.3s var(--ease);
        }

        .progress-bar-fill.warning {
            background-color: var(--warning);
        }

        .progress-bar-fill.danger {
            background-color: var(--danger);
        }

        .key-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        /* Empty state */
        .empty-state {
            background-color: var(--bg-elevated);
            border: 1px dashed var(--border-strong);
            border-radius: var(--radius-lg);
            padding: 56px 24px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .empty-icon {
            font-size: 48px;
            color: var(--text-tertiary);
            opacity: 0.35;
            margin-bottom: 4px;
        }

        .empty-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .empty-desc {
            font-size: 14px;
            color: var(--text-secondary);
            max-width: 380px;
            margin-bottom: 8px;
        }

        /* Modals */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 50;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal-card {
            background-color: var(--bg-elevated);
            border: 1px solid var(--border-medium);
            border-radius: var(--radius-xl);
            width: 100%;
            max-width: 480px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .modal-header {
            padding: 20px 24px 16px 24px;
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .modal-close-btn {
            background: none;
            border: none;
            color: var(--text-tertiary);
            font-size: 16px;
            cursor: pointer;
            padding: 4px;
            border-radius: var(--radius-sm);
            line-height: 1;
        }

        .modal-close-btn:hover {
            color: var(--text-primary);
        }

        .modal-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .form-input {
            width: 100%;
            padding: 10px 12px;
            background-color: var(--bg-subtle);
            border: 1px solid var(--border-medium);
            border-radius: var(--radius-md);
            color: var(--text-primary);
            font-family: var(--font-sans);
            font-size: 14px;
            outline: none;
            transition: border-color 0.15s var(--ease);
        }

        .form-input:focus {
            border-color: var(--accent);
        }

        .form-hint {
            font-size: 12px;
            color: var(--text-tertiary);
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border-subtle);
            background-color: var(--bg-subtle);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* Warning banner in modal */
        .alert-warning-box {
            background-color: var(--warning-subtle);
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-radius: var(--radius-md);
            padding: 12px 14px;
            color: var(--warning);
            font-size: 13px;
            line-height: 1.4;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .key-display-box {
            background-color: var(--bg-subtle);
            border: 1px solid var(--border-medium);
            border-radius: var(--radius-md);
            padding: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .key-code {
            font-family: var(--font-mono);
            font-size: 13px;
            color: var(--text-primary);
            word-break: break-all;
            user-select: all;
        }

        /* Toast */
        .toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 100;
            display: flex;
            flex-direction: column;
            gap: 8px;
            pointer-events: none;
        }

        .toast-msg {
            pointer-events: auto;
            background-color: var(--bg-overlay);
            color: var(--text-primary);
            border: 1px solid var(--border-medium);
            padding: 12px 18px;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            max-width: 360px;
            animation: toastIn 0.2s var(--ease);
        }

        .toast-msg.success i { color: var(--success); }
        .toast-msg.error i { color: var(--danger); }
        .toast-msg.info i { color: var(--accent); }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="portal-header">
        <div class="header-container">
            <a href="{{ route('chat.index') }}" class="header-brand">
                <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="SAHAJA AI" class="header-logo">
                <span class="brand-title">SAHAJA AI</span>
                <span class="badge-dev">Developer</span>
            </a>

            <div class="header-actions">
                <button type="button" class="icon-btn" id="themeToggleBtn" aria-label="Toggle tema" title="Ganti Tema">
                    <i class="fas fa-moon" id="themeIcon"></i>
                </button>
                <a href="{{ route('docs.api') }}" class="btn btn-subtle">
                    <i class="fas fa-book"></i>
                    <span>Dokumentasi API</span>
                </a>
                <a href="{{ route('chat.index') }}" class="btn btn-subtle">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Chat</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="portal-main">
        <!-- Page Title -->
        <section class="page-header">
            <h1 class="page-title">Developer Portal</h1>
            <p class="page-subtitle">Kelola API key dan akses SAHAJA AI dari aplikasi Anda</p>
        </section>

        <!-- Stats Row -->
        <section class="stats-grid">
            <div class="stat-card">
                <span class="stat-label">Total Requests</span>
                <div class="stat-number">{{ number_format($totalRequests) }}</div>
            </div>
            <div class="stat-card">
                <span class="stat-label">Active Keys</span>
                <div class="stat-number">{{ $activeKeys }}</div>
            </div>
            <div class="stat-card">
                <span class="stat-label">Requests Today</span>
                <div class="stat-number">{{ number_format($todayRequests) }}</div>
            </div>
        </section>

        <!-- Chart Section -->
        <section class="chart-section">
            <div class="chart-header">
                <h2 class="section-heading">Request 7 Hari Terakhir</h2>
            </div>
            <div class="chart-container">
                <canvas id="usageChart"></canvas>
            </div>
        </section>

        <!-- API Keys Section -->
        <section class="keys-section">
            <div class="keys-header">
                <div class="keys-title-wrapper">
                    <h2 class="section-heading">API Keys</h2>
                    <span class="keys-counter">({{ count($apiKeys) }}/5)</span>
                </div>
                <button type="button" class="btn btn-primary" onclick="openGenerateModal()">
                    <i class="fas fa-plus"></i>
                    <span>Generate API Key</span>
                </button>
            </div>

            @if(count($apiKeys) === 0)
                <div class="empty-state" id="emptyState">
                    <i class="fas fa-key empty-icon"></i>
                    <h3 class="empty-title">Belum ada API key</h3>
                    <p class="empty-desc">Generate key pertama Anda untuk mulai integrasi SAHAJA AI ke aplikasi Anda.</p>
                    <button type="button" class="btn btn-primary" onclick="openGenerateModal()">
                        <i class="fas fa-plus"></i>
                        <span>Generate API Key</span>
                    </button>
                </div>
            @else
                <div class="keys-list" id="keysList">
                    @foreach($apiKeys as $key)
                        @php
                            $usagePercent = $key->daily_limit > 0 ? min(100, round(($key->usage_today / $key->daily_limit) * 100)) : 0;
                            $barClass = '';
                            if ($usagePercent >= 90) {
                                $barClass = 'danger';
                            } elseif ($usagePercent >= 70) {
                                $barClass = 'warning';
                            }
                        @endphp
                        <div class="key-card" id="key-card-{{ $key->id }}">
                            <div class="key-info">
                                <div class="key-name-row">
                                    <span class="key-name">{{ $key->name }}</span>
                                    <span class="badge-status {{ $key->is_active ? 'active' : 'inactive' }}" id="status-badge-{{ $key->id }}">
                                        <i class="fas fa-circle" style="font-size: 7px;"></i>
                                        <span>{{ $key->is_active ? 'Active' : 'Inactive' }}</span>
                                    </span>
                                </div>
                                
                                <div class="key-masked">{{ $key->masked_key }}</div>

                                <div class="key-meta-row">
                                    <div class="key-usage-wrapper">
                                        <span>{{ number_format($key->usage_today) }} / {{ number_format($key->daily_limit) }} today</span>
                                        <div class="progress-bar-bg" title="{{ $usagePercent }}% terpakai">
                                            <div class="progress-bar-fill {{ $barClass }}" style="width: {{ $usagePercent }}%;"></div>
                                        </div>
                                    </div>
                                    <span>•</span>
                                    <span>Terakhir digunakan: {{ $key->last_used_at ? $key->last_used_at->diffForHumans() : 'Belum pernah' }}</span>
                                </div>
                            </div>

                            <div class="key-actions">
                                <button type="button" 
                                    class="btn btn-subtle btn-sm" 
                                    id="toggle-btn-{{ $key->id }}" 
                                    onclick="toggleKey({{ $key->id }})">
                                    <i class="fas {{ $key->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                    <span>{{ $key->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</span>
                                </button>
                                <button type="button" 
                                    class="btn btn-danger-outline btn-sm" 
                                    onclick="confirmDeleteKey({{ $key->id }}, '{{ addslashes($key->name) }}')">
                                    <i class="fas fa-trash-can"></i>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </main>

    <!-- Modal: Generate API Key -->
    <div class="modal-overlay" id="generateModal" onclick="handleBackdropClick(event, 'generateModal')">
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="generateModalTitle">
            <div class="modal-header">
                <h3 class="modal-title" id="generateModalTitle">Generate API Key</h3>
                <button type="button" class="modal-close-btn" onclick="closeModal('generateModal')" aria-label="Tutup">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <form id="generateForm" onsubmit="generateKey(event)">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="keyNameInput" class="form-label">Nama Key</label>
                        <input type="text" id="keyNameInput" class="form-input" placeholder="contoh: Production Web, App Mobile" required maxlength="100" autocomplete="off">
                        <span class="form-hint">Beri nama untuk mengenali aplikasi atau environment key ini.</span>
                    </div>
                    <div class="form-group">
                        <label for="dailyLimitInput" class="form-label">Daily Limit (Requests/Hari)</label>
                        <input type="number" id="dailyLimitInput" class="form-input" value="100" min="10" max="10000" step="1">
                        <span class="form-hint">Maksimum request yang diizinkan per hari (default: 100).</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-subtle" onclick="closeModal('generateModal')">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitGenerate">
                        <i class="fas fa-key"></i>
                        <span>Generate Key</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Show Plain Key (One-time) -->
    <div class="modal-overlay" id="plainKeyModal" onclick="handleBackdropClick(event, 'plainKeyModal')">
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="plainKeyModalTitle">
            <div class="modal-header">
                <h3 class="modal-title" id="plainKeyModalTitle">Simpan API Key Anda</h3>
                <button type="button" class="modal-close-btn" onclick="closePlainKeyModal()" aria-label="Tutup">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert-warning-box">
                    <i class="fas fa-triangle-exclamation" style="font-size: 16px; margin-top: 2px;"></i>
                    <div>
                        <strong>PENTING:</strong> Simpan API key ini di tempat yang aman. Kunci ini hanya ditampilkan sekali ini saja dan tidak dapat dilihat lagi!
                    </div>
                </div>

                <div class="key-display-box">
                    <code class="key-code" id="plainKeyDisplay">sahaja_sk_...</code>
                    <button type="button" class="btn btn-subtle btn-sm" id="btnCopyKey" onclick="copyKey()">
                        <i class="fas fa-copy"></i>
                        <span id="btnCopyText">Copy</span>
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="closePlainKeyModal()">
                    <i class="fas fa-check"></i>
                    <span>Saya sudah simpan, tutup</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal: Konfirmasi Hapus -->
    <div class="modal-overlay" id="deleteConfirmModal" onclick="handleBackdropClick(event, 'deleteConfirmModal')">
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
            <div class="modal-header">
                <h3 class="modal-title" id="deleteModalTitle">Hapus API Key</h3>
                <button type="button" class="modal-close-btn" onclick="closeModal('deleteConfirmModal')" aria-label="Tutup">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <p style="font-size: 14px; color: var(--text-secondary);">
                    Apakah Anda yakin ingin menghapus API key <strong id="deleteKeyNameTarget" style="color: var(--text-primary);"></strong>?
                </p>
                <div class="alert-warning-box" style="margin-top: 4px;">
                    <i class="fas fa-triangle-exclamation" style="margin-top: 2px;"></i>
                    <div>
                        Semua aplikasi yang menggunakan key ini akan langsung kehilangan akses dan menerima error 401 Unauthorized.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-subtle" onclick="closeModal('deleteConfirmModal')">Batal</button>
                <button type="button" class="btn btn-danger-outline" id="btnExecuteDelete" onclick="executeDeleteKey()">
                    <i class="fas fa-trash-can"></i>
                    <span>Hapus Permanen</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <script>
        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Active key ID for deletion
        let deletingKeyId = null;
        let generatedPlainKey = '';

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
                if (window.usageChartInstance) {
                    updateChartColors();
                }
            });
        }

        // Chart.js initialization
        const chartLabels = {!! json_encode($chartLabels) !!};
        const chartData = {!! json_encode($chartData) !!};

        const ctx = document.getElementById('usageChart');
        let usageChartInstance = null;

        function getChartStyles() {
            const isLight = document.documentElement.classList.contains('light-mode');
            return {
                gridColor: isLight ? 'rgba(0, 0, 0, 0.05)' : 'rgba(255, 255, 255, 0.04)',
                textColor: isLight ? '#9CA3AF' : '#5F6368',
                accentColor: isLight ? '#2563EB' : '#3B82F6',
                gradientTop: isLight ? 'rgba(37, 99, 235, 0.18)' : 'rgba(59, 130, 246, 0.20)',
            };
        }

        if (ctx) {
            const styles = getChartStyles();
            const canvasCtx = ctx.getContext('2d');
            const gradient = canvasCtx.createLinearGradient(0, 0, 0, 260);
            gradient.addColorStop(0, styles.gradientTop);
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

            window.usageChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        data: chartData,
                        borderColor: styles.accentColor,
                        backgroundColor: gradient,
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: styles.accentColor,
                        pointBorderColor: styles.accentColor,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(18, 21, 28, 0.95)',
                            titleColor: '#E8EAED',
                            bodyColor: '#E8EAED',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' requests';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: styles.gridColor,
                                drawBorder: false,
                            },
                            ticks: {
                                color: styles.textColor,
                                font: { family: 'Inter', size: 12 }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: styles.gridColor,
                                drawBorder: false,
                            },
                            ticks: {
                                color: styles.textColor,
                                font: { family: 'Inter', size: 12 },
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        function updateChartColors() {
            if (!window.usageChartInstance) return;
            const styles = getChartStyles();
            const canvasCtx = ctx.getContext('2d');
            const gradient = canvasCtx.createLinearGradient(0, 0, 0, 260);
            gradient.addColorStop(0, styles.gradientTop);
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

            window.usageChartInstance.data.datasets[0].borderColor = styles.accentColor;
            window.usageChartInstance.data.datasets[0].backgroundColor = gradient;
            window.usageChartInstance.data.datasets[0].pointBackgroundColor = styles.accentColor;
            window.usageChartInstance.data.datasets[0].pointBorderColor = styles.accentColor;
            window.usageChartInstance.options.scales.x.grid.color = styles.gridColor;
            window.usageChartInstance.options.scales.x.ticks.color = styles.textColor;
            window.usageChartInstance.options.scales.y.grid.color = styles.gridColor;
            window.usageChartInstance.options.scales.y.ticks.color = styles.textColor;
            window.usageChartInstance.update();
        }

        // Modal Helpers
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('open');
                const firstInput = modal.querySelector('input');
                if (firstInput) setTimeout(() => firstInput.focus(), 50);
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) modal.classList.remove('open');
        }

        function handleBackdropClick(event, id) {
            if (event.target.id === id) {
                closeModal(id);
            }
        }

        // Generate Key flow
        function openGenerateModal() {
            document.getElementById('generateForm').reset();
            document.getElementById('dailyLimitInput').value = '100';
            openModal('generateModal');
        }

        async function generateKey(event) {
            event.preventDefault();
            const submitBtn = document.getElementById('btnSubmitGenerate');
            const nameInput = document.getElementById('keyNameInput');
            const limitInput = document.getElementById('dailyLimitInput');

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

            try {
                const response = await fetch("{{ route('developer.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        name: nameInput.value.trim(),
                        daily_limit: parseInt(limitInput.value) || 100
                    })
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    showToast(data.message || 'Gagal generate API key', 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-key"></i> Generate Key';
                    return;
                }

                closeModal('generateModal');
                generatedPlainKey = data.plain_key;
                document.getElementById('plainKeyDisplay').textContent = data.plain_key;
                openModal('plainKeyModal');
                showToast('API Key berhasil dibuat!', 'success');

            } catch (err) {
                showToast('Terjadi kesalahan jaringan.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-key"></i> Generate Key';
            }
        }

        function closePlainKeyModal() {
            closeModal('plainKeyModal');
            // Reload page to reflect new key in list & counters
            window.location.reload();
        }

        function copyKey() {
            if (!generatedPlainKey) return;
            navigator.clipboard.writeText(generatedPlainKey).then(() => {
                const btnText = document.getElementById('btnCopyText');
                const btn = document.getElementById('btnCopyKey');
                if (btnText) btnText.textContent = 'Copied!';
                if (btn) btn.style.borderColor = 'var(--success)';
                showToast('Key berhasil disalin ke clipboard!', 'success');
                setTimeout(() => {
                    if (btnText) btnText.textContent = 'Copy';
                    if (btn) btn.style.borderColor = '';
                }, 2000);
            }).catch(() => {
                showToast('Gagal menyalin key.', 'error');
            });
        }

        // Toggle Key Active / Inactive
        async function toggleKey(id) {
            const toggleBtn = document.getElementById(`toggle-btn-${id}`);
            const badge = document.getElementById(`status-badge-${id}`);
            if (!toggleBtn) return;

            toggleBtn.disabled = true;

            try {
                const response = await fetch(`/developer/keys/${id}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    const isActive = data.is_active;
                    if (badge) {
                        badge.className = `badge-status ${isActive ? 'active' : 'inactive'}`;
                        badge.innerHTML = `<i class="fas fa-circle" style="font-size: 7px;"></i> <span>${isActive ? 'Active' : 'Inactive'}</span>`;
                    }
                    toggleBtn.innerHTML = `<i class="fas ${isActive ? 'fa-pause' : 'fa-play'}"></i> <span>${isActive ? 'Nonaktifkan' : 'Aktifkan'}</span>`;
                    showToast(`API Key berhasil ${isActive ? 'diaktifkan' : 'dinonaktifkan'}.`, 'success');
                } else {
                    showToast(data.message || 'Gagal mengubah status key.', 'error');
                }
            } catch (err) {
                showToast('Terjadi kesalahan jaringan.', 'error');
            } finally {
                toggleBtn.disabled = false;
            }
        }

        // Delete Key
        function confirmDeleteKey(id, name) {
            deletingKeyId = id;
            document.getElementById('deleteKeyNameTarget').textContent = name;
            openModal('deleteConfirmModal');
        }

        async function executeDeleteKey() {
            if (!deletingKeyId) return;

            const deleteBtn = document.getElementById('btnExecuteDelete');
            deleteBtn.disabled = true;
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghapus...';

            try {
                const response = await fetch(`/developer/keys/${deletingKeyId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    closeModal('deleteConfirmModal');
                    showToast('API Key berhasil dihapus.', 'success');
                    const card = document.getElementById(`key-card-${deletingKeyId}`);
                    if (card) card.remove();
                    deletingKeyId = null;
                    setTimeout(() => window.location.reload(), 500);
                } else {
                    showToast(data.message || 'Gagal menghapus API key.', 'error');
                }
            } catch (err) {
                showToast('Terjadi kesalahan jaringan.', 'error');
            } finally {
                deleteBtn.disabled = false;
                deleteBtn.innerHTML = '<i class="fas fa-trash-can"></i> Hapus Permanen';
            }
        }

        // Toast Helper
        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `toast-msg ${type}`;
            
            let iconClass = 'fa-info-circle';
            if (type === 'success') iconClass = 'fa-circle-check';
            if (type === 'error') iconClass = 'fa-circle-exclamation';

            toast.innerHTML = `<i class="fas ${iconClass}"></i><span>${message}</span>`;
            container.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.2s var(--ease)';
                setTimeout(() => toast.remove(), 200);
            }, 3000);
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal('generateModal');
                closeModal('deleteConfirmModal');
            }
        });
    </script>
</body>
</html>

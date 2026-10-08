<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SAHAJA AI</title>
    <link rel="icon" type="image/png" href="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
            --text-disabled: #3C4043;
            --accent: #3B82F6;
            --accent-hover: #60A5FA;
            --accent-active: #2563EB;
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
            --duration-micro: 150ms;
            --duration-base: 200ms;
            --duration-macro: 350ms;
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        html.light-mode,
        html.light-mode body,
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
            --accent-active: #1E40AF;
            --accent-subtle: rgba(37, 99, 235, 0.08);
            --accent-border: rgba(37, 99, 235, 0.25);
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

        /* ===== SCROLLBAR STYLING ===== */

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

        /* Table container — scrollbar lebih tipis */
        .table-container::-webkit-scrollbar,
        .table-responsive-wrapper::-webkit-scrollbar,
        .table-scroll::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100% !important;
        }

        body {
            font-family: var(--font-sans);
            background: var(--bg-base);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            transition: background-color var(--duration-base) var(--ease), color var(--duration-base) var(--ease);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 240px;
            min-width: 240px;
            background: var(--bg-subtle);
            border-right: 1px solid var(--border-subtle);
            display: flex;
            flex-direction: column;
            padding: 20px 16px;
            height: 100vh;
            position: sticky;
            top: 0;
            z-index: 100;
            transition: background-color var(--duration-base) var(--ease), border-color var(--duration-base) var(--ease);
        }

        .sidebar-brand {
            padding: 4px 8px 20px;
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-brand-inner {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .brand-logo-icon {
            width: 28px;
            height: 28px;
            border-radius: var(--radius-sm);
            background: var(--accent);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .brand-name {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.01em;
        }

        .brand-badge {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            font-family: var(--font-mono);
            letter-spacing: 0.05em;
            padding: 2px 6px;
            border-radius: var(--radius-sm);
            background: var(--accent-subtle);
            color: var(--accent);
            border: 1px solid var(--accent-border);
        }

        .sidebar-nav {
            list-style: none;
            margin-top: 16px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sidebar-item {
            height: 40px;
            padding: 10px 14px;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: background-color var(--duration-micro) var(--ease), color var(--duration-micro) var(--ease);
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
        }

        .sidebar-item-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-item i {
            font-size: 16px;
            width: 16px;
            color: var(--text-tertiary);
            transition: color var(--duration-micro) var(--ease);
        }

        .sidebar-item:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .sidebar-item:hover i {
            color: var(--text-primary);
        }

        .sidebar-item.active {
            background: var(--accent-subtle);
            color: var(--accent);
        }

        .sidebar-item.active i {
            color: var(--accent);
        }

        .nav-badge {
            background: var(--danger-subtle);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
            padding: 2px 8px;
            border-radius: var(--radius-full);
            font-size: 11px;
            font-family: var(--font-mono);
            font-weight: 600;
        }

        .sidebar-footer {
            border-top: 1px solid var(--border-subtle);
            padding-top: 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .btn-theme-toggle {
            height: 38px;
            width: 100%;
            padding: 8px 12px;
            border-radius: var(--radius-md);
            background: var(--bg-elevated);
            color: var(--text-secondary);
            border: 1px solid var(--border-subtle);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background-color var(--duration-micro) var(--ease), color var(--duration-micro) var(--ease), border-color var(--duration-micro) var(--ease);
        }

        .btn-theme-toggle:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
            border-color: var(--border-medium);
        }

        .logout-btn,
        .btn-logout {
            height: 38px;
            width: 100%;
            padding: 8px 12px;
            border-radius: var(--radius-md);
            background: var(--danger-subtle);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.25);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background-color var(--duration-micro) var(--ease), color var(--duration-micro) var(--ease);
            font-family: var(--font-sans);
        }

        .logout-btn:hover,
        .btn-logout:hover {
            background: var(--danger);
            color: #ffffff;
        }

        /* ===== MAIN WRAPPER ===== */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow-y: auto;
            position: relative;
        }

        /* Mobile Header */
        .mobile-header {
            display: none;
            background: var(--bg-subtle);
            padding: 14px 20px;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-subtle);
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .mobile-hamburger-btn {
            background: transparent;
            border: none;
            color: var(--text-primary);
            font-size: 18px;
            cursor: pointer;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-sm);
        }

        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 95;
            opacity: 0;
            transition: opacity var(--duration-macro) var(--ease);
        }

        .mobile-overlay.active {
            display: block;
            opacity: 1;
        }

        /* Main Content Container */
        .main-content {
            padding: 32px 40px;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* Header Area */
        .page-header {
            padding-bottom: 8px;
        }

        .header-title {
            font-size: 28px;
            font-weight: 600;
            color: var(--text-primary);
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .header-subtitle {
            font-size: 14px;
            color: var(--text-secondary);
            margin-top: 6px;
        }

        /* Alert */
        .alert-toast-inline {
            background: var(--success-subtle);
            border: 1px solid rgba(16, 185, 129, 0.25);
            color: var(--success);
            padding: 12px 16px;
            border-radius: var(--radius-md);
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Tab Bar */
        .tab-bar {
            display: flex;
            gap: 4px;
            border-bottom: 1px solid var(--border-subtle);
        }

        .tab-btn {
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
            background: transparent;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: color var(--duration-micro) var(--ease), border-color var(--duration-micro) var(--ease);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tab-btn:hover {
            color: var(--text-primary);
        }

        .tab-btn.active {
            color: var(--accent);
            border-bottom-color: var(--accent);
        }

        /* Tab Panes */
        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* ===== STAT CARDS ROW ===== */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .stat-card {
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            transition: border-color var(--duration-base) var(--ease), box-shadow var(--duration-base) var(--ease);
        }

        .stat-card:hover {
            border-color: var(--border-medium);
            box-shadow: var(--shadow-sm);
        }

        .stat-info {
            display: flex;
            flex-direction: column;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.1;
            font-family: var(--font-sans);
        }

        .stat-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-tertiary);
            margin-top: 6px;
        }

        .stat-icon {
            font-size: 16px;
            color: var(--text-tertiary);
            opacity: 0.5;
            padding: 6px;
        }

        /* ===== CHARTS ROWS ===== */
        .charts-row-1 {
            display: grid;
            grid-template-columns: 3fr 2fr;
            gap: 16px;
        }

        .charts-row-2 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .chart-box {
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            padding: 24px;
            display: flex;
            flex-direction: column;
        }

        .chart-box-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .chart-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            letter-spacing: -0.01em;
        }

        .chart-canvas-wrapper {
            position: relative;
            flex: 1;
            width: 100%;
            height: 260px;
        }

        /* ===== TABLES (TOP USERS & USERS) ===== */
        .table-card {
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .table-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-card-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .table-scroll {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            min-width: 600px;
        }

        .data-table th {
            background: var(--bg-subtle);
            padding: 12px 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border-subtle);
            white-space: nowrap;
        }

        .data-table td {
            padding: 14px 20px;
            font-size: 14px;
            color: var(--text-primary);
            border-top: 1px solid var(--border-subtle);
            vertical-align: middle;
        }

        .data-table tbody tr {
            transition: background-color var(--duration-micro) var(--ease);
        }

        .data-table tbody tr:hover {
            background: var(--bg-hover);
        }

        .rank-badge {
            font-family: var(--font-mono);
            font-size: 14px;
            font-weight: 600;
            color: var(--text-tertiary);
        }

        .user-profile-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar-circle {
            width: 36px;
            height: 36px;
            min-width: 36px;
            border-radius: var(--radius-full);
            background: var(--accent-subtle);
            color: var(--accent);
            border: 1px solid var(--accent-border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name-text {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
        }

        .user-email-text {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .stats-badge-list {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .stats-pill {
            padding: 3px 8px;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-family: var(--font-mono);
            font-weight: 500;
            background: var(--bg-subtle);
            border: 1px solid var(--border-subtle);
            color: var(--text-secondary);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .action-btn-group {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-action-warn {
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            background: var(--warning-subtle);
            color: var(--warning);
            border: 1px solid var(--warning);
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background-color var(--duration-micro) var(--ease), color var(--duration-micro) var(--ease);
        }

        .btn-action-warn:hover {
            background: var(--warning);
            color: #ffffff;
        }

        .btn-action-danger {
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            background: var(--danger-subtle);
            color: var(--danger);
            border: 1px solid var(--danger);
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background-color var(--duration-micro) var(--ease), color var(--duration-micro) var(--ease);
        }

        .btn-action-danger:hover {
            background: var(--danger);
            color: #ffffff;
        }

        /* ===== FEEDBACK CARDS ===== */
        .feedback-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 16px;
        }

        .feedback-card {
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            padding: 16px;
            display: flex;
            flex-direction: column;
            transition: border-color var(--duration-micro) var(--ease);
        }

        .feedback-card:hover {
            border-color: var(--border-medium);
        }

        .feedback-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .feedback-user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .feedback-avatar {
            width: 32px;
            height: 32px;
            min-width: 32px;
            border-radius: var(--radius-full);
            background: var(--accent-subtle);
            color: var(--accent);
            border: 1px solid var(--accent-border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
        }

        .feedback-meta {
            display: flex;
            flex-direction: column;
        }

        .feedback-author {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .feedback-email {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .feedback-time {
            font-size: 12px;
            color: var(--text-tertiary);
            font-family: var(--font-mono);
            white-space: nowrap;
        }

        .feedback-card-body {
            margin-top: 12px;
            padding: 12px;
            background: var(--bg-subtle);
            border-radius: var(--radius-md);
            font-size: 14px;
            line-height: 1.5;
            color: var(--text-primary);
            border: 1px solid var(--border-subtle);
        }

        .empty-state-card {
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            padding: 48px 24px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .empty-icon {
            font-size: 32px;
            color: var(--text-tertiary);
        }

        .empty-text {
            font-size: 14px;
            color: var(--text-secondary);
        }

        /* ===== TOAST NOTIFICATION ===== */
        .toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 8px;
            pointer-events: none;
        }

        .toast-item {
            background: var(--bg-overlay);
            color: var(--text-primary);
            border: 1px solid var(--border-medium);
            padding: 12px 18px;
            border-radius: var(--radius-md);
            font-size: 13px;
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 10px;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            opacity: 0;
            transform: translateY(10px);
            transition: opacity var(--duration-base) var(--ease), transform var(--duration-base) var(--ease);
            pointer-events: auto;
        }

        .toast-item.show {
            opacity: 1;
            transform: translateY(0);
        }

        .toast-item.error {
            border-color: rgba(239, 68, 68, 0.4);
            color: var(--danger);
        }

        .toast-item.success {
            border-color: rgba(16, 185, 129, 0.4);
            color: var(--success);
        }

        /* ===== MODAL KONFIRMASI ===== */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: 
                opacity 200ms cubic-bezier(0.4, 0, 0.2, 1),
                visibility 200ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        .modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-confirm {
            background: var(--bg-overlay);
            border: 1px solid var(--border-medium);
            border-radius: var(--radius-xl);
            padding: 28px;
            max-width: 380px;
            width: calc(100% - 32px);
            text-align: center;
            box-shadow: var(--shadow-lg);
            transform: translateY(8px);
            transition: transform 200ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        .modal-overlay.show .modal-confirm {
            transform: translateY(0);
        }

        .modal-confirm-icon {
            width: 56px;
            height: 56px;
            background: var(--danger-subtle);
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: var(--danger);
            font-size: 20px;
        }

        .modal-confirm-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .modal-confirm-text {
            font-size: 14px;
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .modal-confirm-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn-cancel,
        .btn-confirm {
            flex: 1;
            height: 40px;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: 
                background 150ms cubic-bezier(0.4, 0, 0.2, 1),
                border-color 150ms cubic-bezier(0.4, 0, 0.2, 1);
            font-family: var(--font-sans);
        }

        .btn-cancel {
            background: transparent;
            border: 1px solid var(--border-medium);
            color: var(--text-primary);
        }

        .btn-cancel:hover {
            background: var(--bg-hover);
            border-color: var(--border-strong);
        }

        .btn-confirm {
            background: var(--danger);
            border: 1px solid var(--danger);
            color: #fff;
        }

        .btn-confirm:hover {
            background: #DC2626;
            border-color: #DC2626;
        }

        /* ===== RESPONSIVE BREAKPOINTS ===== */
        @media (max-width: 1024px) {
            .charts-row-1 {
                grid-template-columns: 1fr;
            }
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .mobile-header {
                display: flex;
            }

            .sidebar {
                position: fixed;
                left: -260px;
                width: 260px;
                box-shadow: var(--shadow-lg);
                transition: transform var(--duration-macro) var(--ease);
            }

            .sidebar.active {
                transform: translateX(260px);
            }

            .main-content {
                padding: 20px 16px;
                gap: 16px;
            }

            .stats-row {
                grid-template-columns: 1fr;
            }

            .feedback-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div class="mobile-overlay" id="mobileOverlay" onclick="toggleMobileSidebar()"></div>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand-inner">
                <div class="brand-logo-icon">
                    <i class="fas fa-robot"></i>
                </div>
                <span class="brand-name">SAHAJA AI</span>
            </a>
            <span class="brand-badge">ADMIN</span>
        </div>

        <ul class="sidebar-nav">
            <li>
                <button type="button" class="sidebar-item active" id="navItemDashboard" onclick="switchTab('tab-dashboard')">
                    <span class="sidebar-item-left">
                        <i class="fas fa-chart-pie"></i>
                        <span>Dashboard & Analytics</span>
                    </span>
                </button>
            </li>
            <li>
                <button type="button" class="sidebar-item" id="navItemUsers" onclick="switchTab('tab-users')">
                    <span class="sidebar-item-left">
                        <i class="fas fa-users"></i>
                        <span>Kelola Pengguna</span>
                    </span>
                </button>
            </li>
            <li>
                <button type="button" class="sidebar-item" id="navItemFeedback" onclick="switchTab('tab-feedback')">
                    <span class="sidebar-item-left">
                        <i class="fas fa-envelope"></i>
                        <span>Umpan Balik</span>
                    </span>
                    @if(isset($feedbacks) && count($feedbacks) > 0)
                        <span class="nav-badge">{{ count($feedbacks) }}</span>
                    @endif
                </button>
            </li>
        </ul>

        <div class="sidebar-footer">
            <button type="button" class="btn-theme-toggle" onclick="toggleTheme()" id="themeToggleBtn">
                <i class="fas fa-sun" id="themeIcon"></i>
                <span id="themeText">Ganti Tema</span>
            </button>
            <button type="button" class="logout-btn" onclick="openLogoutModal()">
                <i class="fas fa-arrow-right-from-bracket" style="font-size: 16px;"></i> 
                Logout
            </button>
        </div>
    </aside>

    <!-- MAIN WRAPPER -->
    <div class="main-wrapper">
        <!-- Mobile Header Bar -->
        <header class="mobile-header">
            <div style="display: flex; align-items: center; gap: 12px;">
                <button type="button" class="mobile-hamburger-btn" onclick="toggleMobileSidebar()" aria-label="Menu">
                    <i class="fas fa-bars"></i>
                </button>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div class="brand-logo-icon" style="width: 24px; height: 24px; font-size: 12px;">
                        <i class="fas fa-robot"></i>
                    </div>
                    <span style="font-weight: 700; font-size: 14px;">SAHAJA AI</span>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <button type="button" class="btn-theme-toggle" onclick="toggleTheme()" style="width: 32px; height: 32px; padding: 0;">
                    <i class="fas fa-sun" id="mobileThemeIcon"></i>
                </button>
                <div class="user-avatar-circle" style="width: 32px; height: 32px; font-size: 12px;">A</div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="header-title">Dashboard</h1>
                <p class="header-subtitle">Statistik & analitik SAHAJA AI</p>
            </div>

            @if(session('success'))
                <div class="alert-toast-inline">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Tab Navigation Bar -->
            <div class="tab-bar">
                <button type="button" class="tab-btn active" id="tabBtnDashboard" onclick="switchTab('tab-dashboard')">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard & Analitik</span>
                </button>
                <button type="button" class="tab-btn" id="tabBtnUsers" onclick="switchTab('tab-users')">
                    <i class="fas fa-users"></i>
                    <span>Kelola Pengguna</span>
                </button>
                <button type="button" class="tab-btn" id="tabBtnFeedback" onclick="switchTab('tab-feedback')">
                    <i class="fas fa-envelope"></i>
                    <span>Umpan Balik</span>
                    @if(isset($feedbacks) && count($feedbacks) > 0)
                        <span class="nav-badge" style="margin-left: 2px;">{{ count($feedbacks) }}</span>
                    @endif
                </button>
            </div>

            <!-- TAB 1: DASHBOARD & ANALYTICS -->
            <div id="tab-dashboard" class="tab-pane active">
                <!-- Stat Cards Row -->
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-info">
                            <span class="stat-number" id="statToday">-</span>
                            <span class="stat-label">Chats Hari Ini</span>
                        </div>
                        <i class="fas fa-comment-dots stat-icon"></i>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <span class="stat-number" id="statWeek">-</span>
                            <span class="stat-label">Chats Minggu Ini</span>
                        </div>
                        <i class="fas fa-calendar-week stat-icon"></i>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <span class="stat-number" id="statMonth">-</span>
                            <span class="stat-label">Chats Bulan Ini</span>
                        </div>
                        <i class="fas fa-calendar-alt stat-icon"></i>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <span class="stat-number" id="statActiveUsers">-</span>
                            <span class="stat-label">User Aktif (7 Hari)</span>
                        </div>
                        <i class="fas fa-user-check stat-icon"></i>
                    </div>
                </div>

                <!-- Charts Row 1 -->
                <div class="charts-row-1">
                    <div class="chart-box">
                        <div class="chart-box-header">
                            <h2 class="chart-title">Aktivitas Chat 30 Hari Terakhir</h2>
                        </div>
                        <div class="chart-canvas-wrapper">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                    <div class="chart-box">
                        <div class="chart-box-header">
                            <h2 class="chart-title">Distribusi Mode AI</h2>
                        </div>
                        <div class="chart-canvas-wrapper">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Charts Row 2 -->
                <div class="charts-row-2">
                    <div class="chart-box">
                        <div class="chart-box-header">
                            <h2 class="chart-title">Top 5 Model Populer (30 Hari Terakhir)</h2>
                        </div>
                        <div class="chart-canvas-wrapper" style="height: 240px;">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Top 10 Users Table -->
                <div class="table-card">
                    <div class="table-card-header">
                        <h2 class="table-card-title">Top 10 User Paling Aktif (30 Hari Terakhir)</h2>
                    </div>
                    <div class="table-scroll">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">Rank (#)</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th style="text-align: right;">Jumlah Chat</th>
                                </tr>
                            </thead>
                            <tbody id="topUsersBody">
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 32px; color: var(--text-tertiary);">
                                        Memuat data analitik pengguna...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 2: USERS MANAGEMENT -->
            <div id="tab-users" class="tab-pane">
                <div class="table-card">
                    <div class="table-card-header">
                        <h2 class="table-card-title">Database Pengguna Terdaftar ({{ isset($users) ? count($users) : 0 }})</h2>
                    </div>
                    <div class="table-scroll">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Pengguna</th>
                                    <th>Statistik</th>
                                    <th>Aktivitas Terakhir</th>
                                    <th>Tgl Bergabung</th>
                                    <th style="text-align: right;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users ?? [] as $user)
                                <tr>
                                    <td>
                                        <div class="user-profile-cell">
                                            <div class="user-avatar-circle">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div class="user-details">
                                                <span class="user-name-text">{{ $user->name }}</span>
                                                <span class="user-email-text">{{ $user->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="stats-badge-list">
                                            <span class="stats-pill"><i class="fas fa-folder-open"></i> {{ $user->total_sessions ?? 0 }} sesi</span>
                                            <span class="stats-pill"><i class="fas fa-comments"></i> {{ $user->total_chats ?? 0 }} chat</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="font-family: var(--font-mono); font-size: 13px; color: {{ ($user->last_activity ?? '') == 'Belum ada aktivitas' ? 'var(--text-tertiary)' : 'var(--text-primary)' }};">
                                            {{ $user->last_activity ?? 'Belum ada aktivitas' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="font-family: var(--font-mono); font-size: 13px; color: var(--text-secondary);">
                                            {{ $user->created_at->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td style="text-align: right;">
                                        <div class="action-btn-group" style="justify-content: flex-end;">
                                            <form action="{{ route('admin.clearChats', $user->id) }}" method="POST" onsubmit="return confirm('Bersihkan semua riwayat chat user {{ $user->name }}?');" style="margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action-warn" title="Hapus Riwayat Chat">
                                                    <i class="fas fa-broom"></i>
                                                    <span>Hapus Chat</span>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" onsubmit="return confirm('PERINGATAN!\nHapus permanen akun user {{ $user->name }}?');" style="margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action-danger" title="Hapus Akun Pengguna">
                                                    <i class="fas fa-trash-alt"></i>
                                                    <span>Hapus User</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-secondary);">
                                        Belum ada pengguna terdaftar.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: FEEDBACK -->
            <div id="tab-feedback" class="tab-pane">
                @if(isset($feedbacks) && count($feedbacks) > 0)
                    <div class="feedback-grid">
                        @foreach($feedbacks as $fb)
                        <div class="feedback-card">
                            <div class="feedback-card-header">
                                <div class="feedback-user-info">
                                    <div class="feedback-avatar">
                                        {{ strtoupper(substr($fb->user->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div class="feedback-meta">
                                        <span class="feedback-author">{{ $fb->user->name ?? 'Pengguna Anonim' }}</span>
                                        <span class="feedback-email">{{ $fb->user->email ?? '-' }}</span>
                                    </div>
                                </div>
                                <span class="feedback-time">{{ $fb->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="feedback-card-body">
                                {{ $fb->message }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state-card">
                        <i class="fas fa-inbox empty-icon"></i>
                        <span class="empty-text">Belum ada umpan balik yang masuk dari pengguna.</span>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Modal Konfirmasi Logout -->
    <div class="modal-overlay" id="logoutConfirmModal">
        <div class="modal-confirm">
            <div class="modal-confirm-icon">
                <i class="fas fa-arrow-right-from-bracket"></i>
            </div>
            <h3 class="modal-confirm-title">Keluar dari Admin Panel?</h3>
            <p class="modal-confirm-text">
                Anda akan keluar dari sesi admin. Yakin ingin melanjutkan?
            </p>
            <div class="modal-confirm-actions">
                <button type="button" class="btn-cancel" onclick="closeLogoutModal()">
                    Batal
                </button>
                <button type="button" class="btn-confirm" onclick="confirmLogout()">
                    Ya, Keluar
                </button>
            </div>
        </div>
    </div>

    <!-- Form logout tersembunyi (untuk submit) -->
    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <script>
        // Set Chart.js Defaults
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = getComputedStyle(document.body).getPropertyValue('--text-tertiary').trim() || '#9AA0A6';

        // Chart Instance References
        let lineChartInstance = null;
        let pieChartInstance = null;
        let barChartInstance = null;

        // Toast Notification Function
        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `toast-item ${type}`;

            let iconClass = 'fas fa-info-circle';
            if (type === 'error') iconClass = 'fas fa-exclamation-circle';
            if (type === 'success') iconClass = 'fas fa-check-circle';

            toast.innerHTML = `<i class="${iconClass}"></i><span>${message}</span>`;
            container.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.add('show');
            });

            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 250);
            }, 3000);
        }

        // Theme Toggle Function
        function toggleTheme() {
            const isLight = document.documentElement.classList.toggle('light-mode');
            document.body.classList.toggle('light-mode', isLight);
            localStorage.setItem('sahaja-theme', isLight ? 'light' : 'dark');
            updateThemeUI(isLight);
            updateChartThemeColors();
        }

        function updateThemeUI(isLight) {
            const themeText = document.getElementById('themeText');
            const themeIcon = document.getElementById('themeIcon');
            const mobileThemeIcon = document.getElementById('mobileThemeIcon');

            if (isLight) {
                if (themeText) themeText.textContent = 'Mode Gelap';
                if (themeIcon) { themeIcon.className = 'fas fa-moon'; }
                if (mobileThemeIcon) { mobileThemeIcon.className = 'fas fa-moon'; }
            } else {
                if (themeText) themeText.textContent = 'Mode Terang';
                if (themeIcon) { themeIcon.className = 'fas fa-sun'; }
                if (mobileThemeIcon) { mobileThemeIcon.className = 'fas fa-sun'; }
            }
        }

        // Mobile Sidebar Toggle
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('mobileOverlay');
            if (sidebar && overlay) {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            }
        }

        // Tab Switching Logic
        function switchTab(tabId) {
            // Hide all tab panes
            document.querySelectorAll('.tab-pane').forEach(tab => tab.classList.remove('active'));

            // Show selected tab pane
            const targetPane = document.getElementById(tabId);
            if (targetPane) targetPane.classList.add('active');

            // Update Tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            if (tabId === 'tab-dashboard') document.getElementById('tabBtnDashboard')?.classList.add('active');
            if (tabId === 'tab-users') document.getElementById('tabBtnUsers')?.classList.add('active');
            if (tabId === 'tab-feedback') document.getElementById('tabBtnFeedback')?.classList.add('active');

            // Update Sidebar buttons
            document.querySelectorAll('.sidebar-item').forEach(item => item.classList.remove('active'));
            if (tabId === 'tab-dashboard') document.getElementById('navItemDashboard')?.classList.add('active');
            if (tabId === 'tab-users') document.getElementById('navItemUsers')?.classList.add('active');
            if (tabId === 'tab-feedback') document.getElementById('navItemFeedback')?.classList.add('active');

            // Close mobile sidebar if open
            if (window.innerWidth <= 768) {
                const sidebar = document.getElementById('adminSidebar');
                const overlay = document.getElementById('mobileOverlay');
                sidebar?.classList.remove('active');
                overlay?.classList.remove('active');
            }
        }

        // Fetch Analytics Data
        async function loadAnalytics() {
            try {
                const res = await fetch('{{ route("admin.analytics") }}');
                const data = await res.json();
                if (!data.success) {
                    showToast('Format respon analitik tidak valid', 'error');
                    return;
                }

                // Update Stat Cards
                if (data.stats) {
                    document.getElementById('statToday').textContent = Number(data.stats.chats_today || 0).toLocaleString();
                    document.getElementById('statWeek').textContent = Number(data.stats.chats_week || 0).toLocaleString();
                    document.getElementById('statMonth').textContent = Number(data.stats.chats_month || 0).toLocaleString();
                    document.getElementById('statActiveUsers').textContent = Number(data.stats.active_users_week || 0).toLocaleString();
                }

                // Render Charts
                renderLineChart(data.daily_activity);
                renderPieChart(data.mode_distribution);
                renderBarChart(data.top_models);

                // Render Top Users Table
                renderTopUsers(data.top_users);
            } catch (e) {
                console.error('Analytics load error:', e);
                showToast('Gagal memuat analitik', 'error');
                const topUsersBody = document.getElementById('topUsersBody');
                if (topUsersBody) {
                    topUsersBody.innerHTML = `
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 24px; color: var(--danger);">
                                Gagal memuat data analitik. Silakan muat ulang halaman.
                            </td>
                        </tr>
                    `;
                }
            }
        }

        // 1. Line Chart: Aktivitas Chat 30 Hari Terakhir
        function renderLineChart(activityData) {
            const ctx = document.getElementById('lineChart');
            if (!ctx) return;

            if (lineChartInstance) lineChartInstance.destroy();

            const labels = activityData ? activityData.labels : [];
            const dataValues = activityData ? activityData.data : [];

            const isLight = document.body.classList.contains('light-mode');
            const gridColor = isLight ? 'rgba(0, 0, 0, 0.05)' : 'rgba(255, 255, 255, 0.04)';
            const tickColor = isLight ? '#9CA3AF' : '#5F6368';

            lineChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Chat',
                        data: dataValues,
                        borderColor: '#3B82F6',
                        borderWidth: 2,
                        tension: 0.3,
                        pointRadius: 0,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: '#3B82F6',
                        pointHoverBorderColor: '#ffffff',
                        pointHoverBorderWidth: 2,
                        fill: true,
                        backgroundColor: function(context) {
                            const chart = context.chart;
                            const { ctx, chartArea } = chart;
                            if (!chartArea) return null;
                            const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.15)');
                            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.00)');
                            return gradient;
                        }
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isLight ? '#1F2937' : '#1A1E27',
                            titleColor: '#F3F4F6',
                            bodyColor: '#F3F4F6',
                            borderColor: isLight ? 'rgba(0,0,0,0.1)' : 'rgba(255,255,255,0.1)',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: gridColor, drawBorder: false },
                            ticks: { color: tickColor, font: { size: 12, family: "'Inter', sans-serif" } }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { color: tickColor, font: { size: 11, family: "'Inter', sans-serif" }, maxTicksLimit: 10 }
                        }
                    },
                    interaction: { mode: 'index', intersect: false }
                }
            });
        }

        // 2. Pie Chart: Distribusi Mode AI
        function renderPieChart(distributionData) {
            const ctx = document.getElementById('pieChart');
            if (!ctx) return;

            if (pieChartInstance) pieChartInstance.destroy();

            const items = distributionData || [];
            const labels = items.map(item => item.mode ? item.mode.toUpperCase() : 'UNKNOWN');
            const dataValues = items.map(item => item.total);

            const palette = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899'];
            const isLight = document.body.classList.contains('light-mode');
            const borderColor = isLight ? '#FFFFFF' : '#1A1E27';
            const legendTextColor = isLight ? '#4B5563' : '#9AA0A6';

            pieChartInstance = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels.length > 0 ? labels : ['Belum Ada Data'],
                    datasets: [{
                        data: dataValues.length > 0 ? dataValues : [1],
                        backgroundColor: dataValues.length > 0 ? palette.slice(0, dataValues.length) : ['#3C4043'],
                        borderColor: borderColor,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: legendTextColor,
                                font: { size: 12, family: "'Inter', sans-serif" },
                                padding: 14,
                                usePointStyle: true,
                                boxWidth: 8
                            }
                        },
                        tooltip: {
                            backgroundColor: isLight ? '#1F2937' : '#1A1E27',
                            titleColor: '#F3F4F6',
                            bodyColor: '#F3F4F6',
                            borderColor: isLight ? 'rgba(0,0,0,0.1)' : 'rgba(255,255,255,0.1)',
                            borderWidth: 1,
                            padding: 10
                        }
                    }
                }
            });
        }

        // 3. Bar Chart: Top 5 Model Populer
        function renderBarChart(modelsData) {
            const ctx = document.getElementById('barChart');
            if (!ctx) return;

            if (barChartInstance) barChartInstance.destroy();

            const items = modelsData || [];
            const labels = items.map(item => item.model || 'Unknown');
            const dataValues = items.map(item => item.total);

            const isLight = document.body.classList.contains('light-mode');
            const gridColor = isLight ? 'rgba(0, 0, 0, 0.05)' : 'rgba(255, 255, 255, 0.04)';
            const tickColor = isLight ? '#9CA3AF' : '#5F6368';

            barChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels.length > 0 ? labels : ['Belum Ada Data'],
                    datasets: [{
                        data: dataValues.length > 0 ? dataValues : [0],
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        hoverBackgroundColor: '#3B82F6',
                        borderRadius: 6,
                        maxBarThickness: 36
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isLight ? '#1F2937' : '#1A1E27',
                            titleColor: '#F3F4F6',
                            bodyColor: '#F3F4F6',
                            borderColor: isLight ? 'rgba(0,0,0,0.1)' : 'rgba(255,255,255,0.1)',
                            borderWidth: 1,
                            padding: 10,
                            callbacks: {
                                label: function(context) {
                                    return ` Digunakan: ${context.parsed.y} kali`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: gridColor, drawBorder: false },
                            ticks: { color: tickColor, font: { size: 12, family: "'Inter', sans-serif" } }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { color: tickColor, font: { size: 12, family: "'Inter', sans-serif" } }
                        }
                    }
                }
            });
        }

        // 4. Render Top Users Table
        function renderTopUsers(users) {
            const tbody = document.getElementById('topUsersBody');
            if (!tbody) return;

            if (!users || users.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 32px; color: var(--text-tertiary);">
                            Belum ada aktivitas percakapan pengguna dalam 30 hari terakhir.
                        </td>
                    </tr>
                `;
                return;
            }

            let html = '';
            users.forEach((user, index) => {
                const initial = (user.name || 'U').charAt(0).toUpperCase();
                html += `
                    <tr>
                        <td>
                            <span class="rank-badge">#${index + 1}</span>
                        </td>
                        <td>
                            <div class="user-profile-cell">
                                <div class="user-avatar-circle">${initial}</div>
                                <span class="user-name-text">${escapeHtml(user.name || 'Pengguna')}</span>
                            </div>
                        </td>
                        <td>
                            <span class="user-email-text">${escapeHtml(user.email || '-')}</span>
                        </td>
                        <td style="text-align: right;">
                            <span style="font-family: var(--font-mono); font-weight: 600; color: var(--accent); font-size: 14px;">
                                ${Number(user.chat_count || 0).toLocaleString()}
                            </span>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function updateChartThemeColors() {
            const isLight = document.body.classList.contains('light-mode');
            const gridColor = isLight ? 'rgba(0, 0, 0, 0.05)' : 'rgba(255, 255, 255, 0.04)';
            const tickColor = isLight ? '#9CA3AF' : '#5F6368';
            const legendTextColor = isLight ? '#4B5563' : '#9AA0A6';
            const pieBorderColor = isLight ? '#FFFFFF' : '#1A1E27';

            if (lineChartInstance) {
                lineChartInstance.options.scales.y.grid.color = gridColor;
                lineChartInstance.options.scales.y.ticks.color = tickColor;
                lineChartInstance.options.scales.x.ticks.color = tickColor;
                lineChartInstance.update();
            }

            if (pieChartInstance) {
                pieChartInstance.data.datasets[0].borderColor = pieBorderColor;
                pieChartInstance.options.plugins.legend.labels.color = legendTextColor;
                pieChartInstance.update();
            }

            if (barChartInstance) {
                barChartInstance.options.scales.y.grid.color = gridColor;
                barChartInstance.options.scales.y.ticks.color = tickColor;
                barChartInstance.options.scales.x.ticks.color = tickColor;
                barChartInstance.update();
            }
        }

        // Logout Confirmation Modal
        function openLogoutModal() {
            document.getElementById('logoutConfirmModal')?.classList.add('show');
        }

        function closeLogoutModal() {
            document.getElementById('logoutConfirmModal')?.classList.remove('show');
        }

        function confirmLogout() {
            document.getElementById('logoutForm')?.submit();
        }

        // Close modal saat klik overlay (bukan modal content)
        document.getElementById('logoutConfirmModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'logoutConfirmModal') {
                closeLogoutModal();
            }
        });

        // Close modal saat tekan ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeLogoutModal();
            }
        });

        // Initialize on DOMContentLoaded
        document.addEventListener('DOMContentLoaded', () => {
            const isLight = localStorage.getItem('sahaja-theme') === 'light';
            if (isLight) {
                document.body.classList.add('light-mode');
            }
            updateThemeUI(isLight);

            // Fetch and load analytics
            loadAnalytics();
        });
    </script>
</body>
</html>

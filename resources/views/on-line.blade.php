<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SAHAJA Connect - Komunitas</title>
    <link rel="icon" type="image/png" href="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <script>
      (function() {
        if (localStorage.getItem('sahaja-theme') === 'light' || localStorage.getItem('theme') === 'light') {
          document.documentElement.classList.add('light-mode');
          document.addEventListener('DOMContentLoaded', () => 
            document.body.classList.add('light-mode'));
        }
      })();
    </script>
    <style>
        /* ===== DESIGN TOKENS (DARK MODE DEFAULT) ===== */
        :root {
            --bg-base:      #0B0D12;
            --bg-subtle:    #12151C;
            --bg-elevated:  #1A1E27;
            --bg-overlay:   #232833;
            --bg-hover:     #1E2229;

            --border-subtle: rgba(255, 255, 255, 0.06);
            --border-medium: rgba(255, 255, 255, 0.10);
            --border-strong: rgba(255, 255, 255, 0.16);

            --text-primary:   #E8EAED;
            --text-secondary: #9AA0A6;
            --text-tertiary:  #5F6368;
            --text-disabled:  #3C4043;

            --accent:          #3B82F6;
            --accent-hover:    #60A5FA;
            --accent-active:   #2563EB;
            --accent-subtle:   rgba(59, 130, 246, 0.12);
            --accent-border:   rgba(59, 130, 246, 0.30);

            --success:        #10B981;
            --success-subtle: rgba(16, 185, 129, 0.12);
            --warning:        #F59E0B;
            --warning-subtle: rgba(245, 158, 11, 0.12);
            --danger:         #EF4444;
            --danger-subtle:  rgba(239, 68, 68, 0.12);

            --radius-sm:   6px;
            --radius-md:   10px;
            --radius-lg:   14px;
            --radius-xl:   20px;
            --radius-full: 9999px;

            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.30);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.35);
            --shadow-lg: 0 12px 32px rgba(0, 0, 0, 0.45);

            --ease:           cubic-bezier(0.4, 0, 0.2, 1);
            --duration-micro: 150ms;
            --duration-base:  200ms;
            --duration-macro: 350ms;

            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;

            /* Backward compatibility aliases */
            --main-bg: var(--bg-base);
            --sidebar-bg: var(--bg-overlay);
            --glass-border: var(--border-subtle);
            --glass-highlight: var(--bg-hover);
            --accent-color: var(--accent);
            --accent-gradient: var(--accent);
            --footer-bg: var(--bg-subtle);
            --danger-color: var(--danger);
            --success-color: var(--success);
        }

        /* ===== LIGHT MODE OVERRIDES ===== */
        html.light-mode,
        html.light-mode body,
        body.light-mode {
            --bg-base:      #FAFBFC;
            --bg-subtle:    #F1F3F5;
            --bg-elevated:  #FFFFFF;
            --bg-overlay:   #FFFFFF;
            --bg-hover:     #EEF1F5;

            --border-subtle: rgba(0, 0, 0, 0.06);
            --border-medium: rgba(0, 0, 0, 0.10);
            --border-strong: rgba(0, 0, 0, 0.16);

            --text-primary:   #111827;
            --text-secondary: #4B5563;
            --text-tertiary:  #9CA3AF;
            --text-disabled:  #D1D5DB;

            --accent:          #2563EB;
            --accent-hover:    #1D4ED8;
            --accent-active:   #1E40AF;
            --accent-subtle:   rgba(37, 99, 235, 0.08);
            --accent-border:   rgba(37, 99, 235, 0.25);

            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.10);
            --shadow-lg: 0 12px 32px rgba(0, 0, 0, 0.14);

            --main-bg: var(--bg-base);
            --sidebar-bg: var(--bg-overlay);
            --glass-border: var(--border-subtle);
            --glass-highlight: var(--bg-hover);
            --accent-color: var(--accent);
            --accent-gradient: var(--accent);
            --footer-bg: var(--bg-subtle);
            --danger-color: var(--danger);
            --success-color: var(--success);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--font-sans);
        }

        body {
            background-color: var(--bg-base);
            color: var(--text-primary);
            height: 100vh;
            overflow: hidden;
            display: flex;
            transition: background var(--duration-base) var(--ease), color var(--duration-base) var(--ease);
            font-family: var(--font-sans);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button {
            cursor: pointer;
            border: none;
            outline: none;
            background: none;
            color: inherit;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            background: var(--bg-subtle);
            border-right: 1px solid var(--border-subtle);
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: width var(--duration-macro) var(--ease);
            z-index: 50;
            flex-shrink: 0;
            overflow: hidden;
            white-space: nowrap;
        }

        .sidebar.collapsed {
            width: 64px;
        }

        .sidebar.collapsed .text-label,
        .sidebar.collapsed .brand-text,
        .sidebar.collapsed .sidebar-footer-details,
        .sidebar.collapsed .options-btn,
        .sidebar.collapsed .footer-menu-icon,
        .sidebar.collapsed .brand-logo-container {
            display: none !important;
            opacity: 0;
        }

        .sidebar.collapsed .sidebar-brand {
            justify-content: center;
            align-items: center;
            padding: 16px 0;
        }

        .sidebar.collapsed .toggle-btn-sidebar {
            margin: 0 auto;
        }

        .sidebar.collapsed .new-chat-btn,
        .sidebar.collapsed .history-item-wrapper,
        .sidebar.collapsed .history-item,
        .sidebar.collapsed .history-link,
        .sidebar.collapsed .sidebar-footer,
        .sidebar.collapsed .user-profile {
            justify-content: center;
        }

        .sidebar-brand {
            padding: 20px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .brand-logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-text {
            font-weight: 700;
            font-size: 16px;
            color: var(--text-primary);
            letter-spacing: 0;
        }

        .toggle-btn-sidebar {
            color: var(--text-secondary);
            width: 36px;
            height: 36px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 16px;
            transition: background-color var(--duration-micro) var(--ease), color var(--duration-micro) var(--ease);
        }

        .toggle-btn-sidebar:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .new-chat-wrapper {
            padding: 0 16px 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex-shrink: 0;
        }

        .new-chat-btn {
            background: var(--accent);
            color: #ffffff;
            border-radius: var(--radius-md);
            height: 40px;
            padding: 0 14px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            transition: background-color var(--duration-micro) var(--ease);
            border: 1px solid transparent;
            box-sizing: border-box;
        }

        .new-chat-btn:hover {
            background: var(--accent-hover);
        }

        .new-chat-btn.secondary {
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border-subtle);
        }

        .new-chat-btn.secondary:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
            border-color: var(--border-medium);
        }

        .history-container {
            flex: 1;
            overflow-y: auto;
            padding: 8px 12px;
            overflow-x: hidden;
        }

        .history-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-tertiary);
            margin-bottom: 8px;
            padding-left: 8px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .history-item-wrapper {
            position: relative;
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            border-radius: var(--radius-md);
            transition: background-color var(--duration-micro) var(--ease);
        }

        .history-item-wrapper:hover {
            background: var(--bg-hover);
        }

        .history-item {
            padding: 10px 14px;
            display: flex;
            align-items: center;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 14px;
            flex-grow: 1;
            min-width: 0;
            height: 36px;
            transition: color var(--duration-micro) var(--ease);
        }

        .history-item:hover {
            color: var(--text-primary);
        }

        .history-link {
            display: flex;
            align-items: center;
            width: 100%;
            overflow: hidden;
        }

        .history-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .options-btn {
            opacity: 0;
            transition: opacity var(--duration-micro) var(--ease);
            padding: 6px;
            border-radius: var(--radius-sm);
            color: var(--text-tertiary);
            flex-shrink: 0;
            margin-right: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .history-item-wrapper:hover .options-btn {
            opacity: 1;
        }

        .options-btn:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .options-menu {
            position: absolute;
            right: 8px;
            top: 40px;
            background: var(--bg-overlay);
            border: 1px solid var(--border-medium);
            border-radius: var(--radius-md);
            padding: 4px;
            width: 160px;
            box-shadow: var(--shadow-lg);
            z-index: 100;
            display: none;
        }

        .options-menu.show {
            display: block;
            animation: menuFadeIn var(--duration-micro) var(--ease);
        }

        .option-item {
            padding: 8px 12px;
            font-size: 13px;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: background-color var(--duration-micro) var(--ease);
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
        }

        .option-item:hover {
            background: var(--bg-hover);
        }

        .option-item.delete {
            color: var(--danger);
        }

        .sidebar-footer {
            padding: 12px 16px;
            border-top: 1px solid var(--border-subtle);
            background: var(--bg-subtle);
            position: relative;
            flex-shrink: 0;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 6px 8px;
            border-radius: var(--radius-md);
            transition: background-color var(--duration-micro) var(--ease);
        }

        .user-profile:hover {
            background: var(--bg-hover);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-full);
            background: var(--accent-subtle);
            border: 1px solid var(--accent-border);
            color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 13px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .logout-menu {
            position: absolute;
            bottom: 70px;
            left: 16px;
            width: 220px;
            background: var(--bg-overlay);
            border: 1px solid var(--border-medium);
            border-radius: var(--radius-md);
            padding: 4px;
            box-shadow: var(--shadow-lg);
            z-index: 100;
            display: none;
        }

        .logout-menu.show {
            display: block;
            animation: menuFadeIn var(--duration-micro) var(--ease);
        }

        /* ===== MODAL & SETTINGS ===== */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100000;
            opacity: 0;
            visibility: hidden;
            transition: opacity var(--duration-base) var(--ease), visibility var(--duration-base);
        }

        .modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-content,
        .settings-modal-box {
            background: var(--bg-overlay) !important;
            border: 1px solid var(--border-medium);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            color: var(--text-primary);
        }

        .settings-modal-box {
            width: 800px;
            max-width: 95%;
            height: 560px;
            max-height: 90vh;
            display: flex;
            overflow: hidden;
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 16px;
            right: 16px;
            background: var(--bg-hover);
            border: none;
            border-radius: var(--radius-md);
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            font-size: 16px;
            cursor: pointer;
            transition: background-color var(--duration-micro) var(--ease), color var(--duration-micro) var(--ease);
        }

        .modal-close:hover {
            background: var(--danger-subtle);
            color: var(--danger);
        }

        .modal-close-outside {
            position: absolute;
            top: 16px;
            right: 16px;
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid var(--border-medium);
            border-radius: var(--radius-full);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            z-index: 100001;
            transition: background-color var(--duration-micro) var(--ease);
        }

        .modal-close-outside:hover {
            background: var(--danger);
        }

        .settings-sidebar {
            width: 220px;
            background: var(--bg-subtle);
            padding: 16px 8px;
            border-right: 1px solid var(--border-subtle);
            display: flex;
            flex-direction: column;
            gap: 2px;
            flex-shrink: 0;
        }

        .settings-sidebar h3 {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-tertiary);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 8px;
            margin-bottom: 4px;
        }

        .nav-btn {
            padding: 10px 12px;
            text-align: left;
            background: none;
            border-radius: var(--radius-md);
            color: var(--text-secondary);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color var(--duration-micro) var(--ease), color var(--duration-micro) var(--ease);
            display: flex;
            align-items: center;
            gap: 10px;
            border: none;
            width: 100%;
        }

        .nav-btn:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .nav-btn.active {
            background: var(--accent-subtle);
            color: var(--accent);
        }

        .settings-content {
            padding: 24px 30px;
            flex: 1;
            overflow-y: auto;
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
            animation: menuFadeIn var(--duration-micro) var(--ease);
        }

        .theme-btn {
            padding: 12px 16px;
            border: 1px solid var(--border-medium);
            border-radius: var(--radius-md);
            flex: 1;
            background: var(--bg-subtle);
            color: var(--text-primary);
            font-weight: 500;
            cursor: pointer;
            transition: all var(--duration-micro) var(--ease);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .theme-btn:hover {
            background: var(--bg-hover);
        }

        .theme-btn.active {
            border-color: var(--accent);
            background: var(--accent-subtle);
            color: var(--accent);
        }

        .github-input {
            width: 100%;
            padding: 10px 14px;
            background: var(--bg-elevated);
            border: 1px solid var(--border-medium);
            color: var(--text-primary);
            border-radius: var(--radius-md);
            outline: none;
            font-size: 14px;
            transition: border-color var(--duration-micro) var(--ease);
        }

        .github-input:focus {
            border-color: var(--accent);
        }

        .github-submit-btn {
            background: var(--accent);
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: var(--radius-md);
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
            transition: background var(--duration-micro) var(--ease);
        }

        .github-submit-btn:hover {
            background: var(--accent-hover);
        }

        /* ===== MAIN CONTENT (TIMELINE) ===== */
        .main-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: relative;
            overflow-y: auto;
            background: var(--bg-base);
        }

        .timeline-header {
            padding: 0 5%;
            height: 56px;
            border-bottom: 1px solid var(--border-subtle);
            background: var(--bg-base);
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }

        .timeline-header h2 {
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-primary);
        }

        .mobile-toggle-btn {
            display: none;
            color: var(--text-primary);
            font-size: 18px;
            background: none;
            border: none;
            cursor: pointer;
        }

        .compose-box {
            display: flex;
            gap: 15px;
            padding: 20px 5%;
            border-bottom: 1px solid var(--border-subtle);
            background: var(--bg-subtle);
        }

        .avatar-circle {
            width: 42px;
            height: 42px;
            border-radius: var(--radius-full);
            background: var(--accent-subtle);
            border: 1px solid var(--accent-border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-weight: 600;
            font-size: 14px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .compose-input {
            width: 100%;
            background: transparent;
            border: none;
            color: var(--text-primary);
            font-size: 14px;
            line-height: 1.6;
            resize: none;
            outline: none;
            padding: 8px 0;
            border-bottom: 1px solid transparent;
            transition: border-color var(--duration-micro) var(--ease);
        }

        .compose-input:focus {
            border-bottom-color: var(--accent);
        }

        .post-btn {
            background: var(--accent);
            color: white;
            padding: 8px 20px;
            border-radius: var(--radius-full);
            font-weight: 500;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: background var(--duration-micro) var(--ease);
        }

        .post-btn:hover {
            background: var(--accent-hover);
        }

        .feed-container {
            padding-bottom: 80px;
            background: var(--bg-base);
        }

        .post-card {
            display: flex;
            gap: 15px;
            padding: 20px 5%;
            border-bottom: 1px solid var(--border-subtle);
            transition: background-color var(--duration-micro) var(--ease);
        }

        .post-card:hover {
            background: var(--bg-hover);
        }

        .post-content {
            flex: 1;
        }

        .post-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
            flex-wrap: wrap;
        }

        .post-name {
            font-weight: 600;
            font-size: 14px;
            color: var(--text-primary);
        }

        .post-time {
            font-size: 12px;
            color: var(--text-tertiary);
        }

        .post-body {
            font-size: 14px;
            line-height: 1.6;
            color: var(--text-primary);
            white-space: pre-wrap;
        }

        .post-actions {
            display: flex;
            gap: 24px;
            margin-top: 12px;
        }

        .action-btn {
            background: transparent;
            color: var(--text-secondary);
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all var(--duration-micro) var(--ease);
            padding: 4px 8px;
            border-radius: var(--radius-sm);
            border: none;
            cursor: pointer;
        }

        .action-btn:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .action-btn.like-btn:hover {
            color: var(--danger);
            background: var(--danger-subtle);
        }

        .action-btn.comment-btn:hover {
            color: var(--accent);
            background: var(--accent-subtle);
        }

        /* ===== TOAST & RESPONSIVE ===== */
        #toast-container {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 100000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast {
            background: var(--bg-overlay);
            border: 1px solid var(--border-medium);
            color: var(--text-primary);
            padding: 12px 20px;
            border-radius: var(--radius-md);
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown var(--duration-base) var(--ease) forwards;
            box-shadow: var(--shadow-lg);
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                height: 100%;
                transform: translateX(-100%);
                z-index: 99;
                width: 260px !important;
                transition: transform var(--duration-macro) var(--ease);
            }
            .sidebar.mobile-open {
                transform: translateX(0);
                box-shadow: var(--shadow-lg);
            }
            .toggle-btn-sidebar { display: none; }
            .mobile-toggle-btn { display: block; margin-right: 12px; }

            .settings-modal-box {
                flex-direction: column;
                height: auto;
                max-height: 85vh;
                width: 95%;
                margin-top: 50px;
            }
            .settings-sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid var(--border-subtle);
                flex-direction: row;
                padding: 8px;
                overflow-x: auto;
                white-space: nowrap;
                flex-shrink: 0;
            }
            .settings-sidebar h3 { display: none; }
            .nav-btn { padding: 8px 12px; font-size: 13px; }
            .settings-content { padding: 16px; overflow-y: auto; }
        }

        /* ============================================================
           SCROLLBAR STYLING — Global
           ============================================================ */

        /* Firefox */
        * {
          scrollbar-width: thin;
          scrollbar-color: var(--border-medium) transparent;
        }

        body.light-mode *,
        html.light-mode * {
          scrollbar-color: var(--border-medium) transparent;
        }

        /* Webkit (Chrome, Edge, Safari, Opera, Brave) */
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
          transition: background-color var(--duration-micro) var(--ease);
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

        /* Thin scrollbar untuk komponen spesifik */
        .multi-file-container::-webkit-scrollbar,
        .suggested-actions-grid::-webkit-scrollbar,
        .comments-list::-webkit-scrollbar {
          height: 4px;
        }

        .multi-file-container::-webkit-scrollbar-thumb,
        .suggested-actions-grid::-webkit-scrollbar-thumb,
        .comments-list::-webkit-scrollbar-thumb {
          background: var(--border-medium);
          border-radius: var(--radius-full);
        }

        /* Scrollbar di code block (pre) — lebih tipis & subtle */
        .markdown-body pre::-webkit-scrollbar,
        .markdown-body pre::-webkit-scrollbar-track {
          height: 6px;
        }

        .markdown-body pre::-webkit-scrollbar-thumb {
          background: rgba(255, 255, 255, 0.15);
          border-radius: var(--radius-full);
        }

        body.light-mode .markdown-body pre::-webkit-scrollbar-thumb,
        html.light-mode .markdown-body pre::-webkit-scrollbar-thumb {
          background: rgba(0, 0, 0, 0.15);
        }

        .markdown-body pre::-webkit-scrollbar-thumb:hover {
          background: rgba(255, 255, 255, 0.3);
        }

        body.light-mode .markdown-body pre::-webkit-scrollbar-thumb:hover,
        html.light-mode .markdown-body pre::-webkit-scrollbar-thumb:hover {
          background: rgba(0, 0, 0, 0.3);
        }

        /* Firefox untuk code block */
        .markdown-body pre {
          scrollbar-width: thin;
          scrollbar-color: rgba(255, 255, 255, 0.15) transparent;
        }

        body.light-mode .markdown-body pre,
        html.light-mode .markdown-body pre {
          scrollbar-color: rgba(0, 0, 0, 0.15) transparent;
        }

    </style>
</head>

<body>

    <div id="toast-container"></div>

    <div class="sidebar" id="sidebar">
        {{-- Brand --}}
        <div class="sidebar-brand">
            <div class="brand-logo-container">
                <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="Logo SAHAJA AI"
                    style="width: 28px; height: 28px; border-radius: 6px; object-fit: contain; flex-shrink: 0;">
                <span class="brand-text text-label">SAHAJA AI</span>
            </div>
            <button class="toggle-btn-sidebar" id="sidebarToggleBtn" aria-label="Toggle sidebar">
                <i class="fas fa-bars" style="font-size: 16px;"></i>
            </button>
        </div>

        {{-- Nav Buttons --}}
        <div class="new-chat-wrapper">
            <a href="{{ route('chat.new') }}" class="new-chat-btn secondary" aria-label="Percakapan baru">
                <i class="fas fa-plus" style="font-size: 16px;"></i>
                <span class="btn-text text-label">Percakapan Baru</span>
            </a>
            <a href="{{ route('online.index') }}" class="new-chat-btn" aria-label="SAHAJA Connect">
                <i class="fas fa-globe" style="font-size: 16px;"></i>
                <span class="btn-text text-label">SAHAJA Connect</span>
            </a>
            <a href="{{ route('sahaja-llm.index') }}" class="new-chat-btn secondary" aria-label="SAHAJA LLM">
                <i class="fas fa-book-open" style="font-size: 16px;"></i>
                <span class="btn-text text-label">SAHAJA LLM</span>
            </a>
        </div>

        {{-- History --}}
        <div class="history-container">
            <div class="history-label text-label">Riwayat</div>
            @foreach ($sessions as $session)
                <div class="history-item-wrapper" id="session-{{ $session->id }}">
                    <a href="{{ route('chat.show', $session->id) }}" class="history-item" aria-label="{{ $session->title ?? 'Chat Baru' }}">
                        <div class="history-link">
                            <span class="history-text text-label" id="title-{{ $session->id }}">{{ $session->title ?? 'Chat Baru' }}</span>
                        </div>
                    </a>
                    <button class="options-btn" onclick="toggleMenu(event, 'menu-{{ $session->id }}')" aria-label="Opsi percakapan">
                        <i class="fas fa-ellipsis" style="font-size: 14px;"></i>
                    </button>
                    <div class="options-menu" id="menu-{{ $session->id }}">
                        <button class="option-item" onclick="shareSession({{ $session->id }})">
                            <i class="fas fa-share-nodes"></i> Bagikan
                        </button>
                        <button class="option-item" onclick="renameSession({{ $session->id }})">
                            <i class="fas fa-pen"></i> Ganti Nama
                        </button>
                        <div class="dropdown-divider" style="margin: 4px 0; border-top: 1px solid var(--border-subtle);"></div>
                        <button class="option-item delete" onclick="deleteSession({{ $session->id }})">
                            <i class="fas fa-trash-can"></i> Hapus
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- User Profile Footer --}}
        <div class="sidebar-footer">
            <div class="user-profile" onclick="toggleMenu(event, 'logout-menu')" role="button" tabindex="0" aria-label="Menu pengguna">
                @if (Auth::user()->avatar)
                    <img src="{{ Auth::user()->avatar }}" class="user-avatar" style="object-fit: cover; width: 32px; height: 32px;" alt="{{ Auth::user()->name }}">
                @else
                    <div class="user-avatar" aria-hidden="true">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</div>
                @endif
                <div class="sidebar-footer-details text-label">
                    <div style="font-size: 14px; font-weight: 500; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ Auth::user()->name ?? 'Pengguna' }}
                    </div>
                </div>
            </div>
            <div class="logout-menu" id="logout-menu">
                <button class="option-item" onclick="openSettingsModal()">
                    <i class="fas fa-gear"></i> Pengaturan
                </button>
                <div style="margin: 4px 0; border-top: 1px solid var(--border-subtle);"></div>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="option-item delete" style="width: 100%;">
                        <i class="fas fa-arrow-right-from-bracket"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="timeline-header">
            <div style="display: flex; align-items: center; gap: 12px;">
                <button class="mobile-toggle-btn" id="mobileToggleBtn" aria-label="Buka navigasi"><i class="fas fa-bars"></i></button>
                <h2><i class="fas fa-globe" style="color: var(--accent);"></i> SAHAJA Connect</h2>
            </div>
            <div style="display: flex; align-items: center; gap: 16px;">
                <p style="font-size: 13px; color: var(--text-secondary); margin: 0; display: none; @media(min-width: 768px){display: block;}">
                    Forum resmi pengguna.
                </p>
                <button onclick="openSettingsModal()" aria-label="Pengaturan" style="background: none; border: none; font-size: 18px; color: var(--text-secondary); cursor: pointer; transition: color var(--transition-fast);">
                    <i class="fas fa-gear"></i>
                </button>
            </div>
        </div>

        <div class="compose-box">
            <div class="avatar-circle">
                @if (Auth::user()->avatar)
                    <img src="{{ Auth::user()->avatar }}" alt="Avatar"
                        style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                @else
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                @endif
            </div>
            <div style="flex: 1;">
                <textarea class="compose-input" id="postInput" placeholder="Ada ide prompt menarik hari ini? Bagikan ke komunitas..."
                    rows="2" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                <div style="display: flex; justify-content: flex-end; margin-top: 10px;">
                    <button class="post-btn" onclick="submitPost()">Kirim Postingan</button>
                </div>
            </div>
        </div>

        <div class="feed-container" id="feedContainer">
            @foreach($posts as $post)
                <div class="post-card" id="post-{{ $post->id }}" style="flex-direction: column; gap: 10px;">
                    <div style="display: flex; gap: 15px;">
                        @if($post->user)
                            @if($post->user->avatar)
                                <img src="{{ $post->user->avatar }}" class="avatar-circle" style="object-fit: cover;">
                            @else
                                <div class="avatar-circle">{{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}</div>
                            @endif
                        @else
                            <div class="avatar-circle" style="background: #444;">?</div>
                        @endif

                        <div class="post-content" style="width: 100%;">
                            <div class="post-header" style="justify-content: space-between;">
                                <div>
                                    <span class="post-name">{{ $post->user->name ?? 'Mantan Pengguna' }}</span>
                                    <span class="post-time">· {{ $post->created_at->diffForHumans() }}</span>
                                </div>
                                @if($post->user_id == Auth::id())
                                    <button onclick="openConfirmModal('Hapus Postingan?', 'Postingan ini akan hilang dari linimasa SAHAJA Connect.', 'deletePost', {{ $post->id }})" class="action-btn" style="color: var(--danger); opacity: 0.7;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.7'">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                @endif
                            </div>
                            <div class="post-body">{!! nl2br(e($post->body)) !!}</div>

                            <div class="post-actions">
                                @php $isLiked = $post->likes->where('user_id', Auth::id())->count() > 0; @endphp
                                <button class="action-btn like-btn" onclick="toggleLike({{ $post->id }}, this)" style="color: {{ $isLiked ? '#ef4444' : 'var(--text-secondary)' }}">
                                    <i class="{{ $isLiked ? 'fas' : 'far' }} fa-heart"></i> <span class="like-count">{{ $post->likes->count() }}</span>
                                </button>
                                <button class="action-btn comment-btn" onclick="toggleComment({{ $post->id }})">
                                    <i class="far fa-comment"></i> {{ $post->comments->count() }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="comments-section" id="comments-section-{{ $post->id }}" style="display: none; margin-left: 60px; padding-top: 15px; border-top: 1px dashed var(--border-subtle);">

                        @if($post->comments && $post->comments->count() > 0)
                            <div class="comments-list" style="max-height: 250px; overflow-y: auto; margin-bottom: 15px; display: flex; flex-direction: column; gap: 12px; padding-right: 5px;">
                                @foreach($post->comments as $comment)
                                    <div class="comment-item" style="display: flex; gap: 10px; background: var(--bg-hover); padding: 12px; border-radius: 12px;">

                                        @if($comment->user)
                                            @if($comment->user->avatar)
                                                <img src="{{ $comment->user->avatar }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
                                            @else
                                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 0.75rem; color: white; font-weight: bold; flex-shrink: 0;">
                                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        @else
                                            <div style="width: 32px; height: 32px; border-radius: 50%; background: #444; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; color: #ccc; flex-shrink: 0;">?</div>
                                        @endif

                                        <div>
                                            <div style="font-size: 0.85rem; font-weight: 600;">
                                                {{ $comment->user->name ?? 'Mantan Pengguna' }}
                                                <span style="color: var(--text-secondary); font-weight: normal; font-size: 0.75rem;">· {{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div style="font-size: 0.9rem; margin-top: 3px; color: var(--text-primary);">{{ $comment->body }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div style="display: flex; gap: 10px;">
                            <input type="text" id="comment-input-{{ $post->id }}" class="github-input" placeholder="Tulis balasan Anda..." style="padding: 10px 15px; font-size: 0.9rem;" onkeydown="if(event.key === 'Enter') submitComment({{ $post->id }})">
                            <button class="github-submit-btn" onclick="submitComment({{ $post->id }})" style="padding: 10px 20px;"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="modal-overlay" id="settingsModal" style="z-index: 100000;">
        <button class="modal-close-outside" onclick="closeSettingsModal()"><i class="fas fa-times"></i></button>
        <div class="settings-modal-box">
            <div class="settings-sidebar">
                <h3 style="padding: 10px 10px; font-size: 1.1rem; color: var(--text-primary);">Pengaturan</h3>
                <button class="nav-btn active" onclick="switchTab('umum')"><i class="fas fa-cog"></i> Umum</button>
                <button class="nav-btn" onclick="switchTab('profil')"><i class="fas fa-user"></i> Profil</button>
                <button class="nav-btn" onclick="switchTab('data')"><i class="fas fa-database"></i> Data</button>
                <button class="nav-btn" onclick="switchTab('tentang')"><i class="fas fa-info-circle"></i>
                    Tentang</button>
            </div>

            <div class="settings-content">
                <div id="tab-umum" class="tab-pane active">
                    <h3 style="margin-bottom: 20px;">Umum</h3>
                    <label
                        style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 10px; display: block;">Tema</label>
                    <div style="display: flex; gap: 10px;">
                        <button class="theme-btn" id="btnThemeLight" onclick="setTheme('light')"><i
                                class="fas fa-sun"></i> Terang</button>
                        <button class="theme-btn" id="btnThemeDark" onclick="setTheme('dark')"><i
                                class="fas fa-moon"></i> Gelap</button>
                    </div>
                </div>

                <div id="tab-profil" class="tab-pane">
                    <h3 style="margin-bottom: 20px;">Profil</h3>
                    <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px;">
                        <img id="previewAvatar" src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=2563eb&color=fff' }}" alt="Avatar" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                        <div style="display: flex; flex-direction: column; gap: 5px;">
                            <div style="display: flex; gap: 10px;">
                                <input type="file" id="avatarInput" accept="image/png, image/jpeg, image/webp" style="display:none">
                                <button class="github-submit-btn" onclick="document.getElementById('avatarInput').click()" style="padding: 5px 15px; font-size: 0.85rem;">Pilih Foto</button>
                                @if(Auth::user()->avatar)
                                    <button class="action-btn" onclick="openConfirmModal('Hapus Foto Profil?', 'Foto profil akan dikembalikan ke inisial nama Anda.', 'deleteAvatar')" style="color: var(--danger); border: 1px solid var(--danger); padding: 5px 10px; border-radius: 8px; font-size: 0.85rem;"><i class="fas fa-trash"></i></button>
                                @endif
                            </div>
                            <p style="font-size: 0.75rem; color: var(--text-secondary);">Maks 2MB. Jangan lupa klik Simpan di bawah.</p>
                        </div>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 5px; display: block;">Nama Tampilan</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="text" id="inputNamaProfil" class="github-input" value="{{ Auth::user()->name }}">
                            <button class="github-submit-btn" onclick="simpanProfil()">Simpan</button>
                        </div>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 5px; display: block;">Alamat Email</label>
                        <input type="email" class="github-input" value="{{ Auth::user()->email }}" disabled style="opacity: 0.6;">
                    </div>
                    <hr style="border: 0; border-top: 1px solid var(--border-subtle); margin: 20px 0;">
                    <button class="option-item delete" style="width: auto; padding: 10px; font-weight: 600; border: 1px solid var(--danger);" onclick="openConfirmModal('Hapus Akun Permanen?', 'Seluruh data akun, foto, dan obrolan akan hilang selamanya.', 'deleteAccount')"><i class="fas fa-trash-alt"></i> Hapus Akun</button>
                </div>

                <div id="tab-data" class="tab-pane">
                    <h3 style="margin-bottom: 20px;">Data</h3>

                    <div
                        style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-subtle); padding-bottom: 15px; margin-bottom: 15px;">
                        <div>
                            <strong style="display: block;">Tautan yang dibagikan</strong>
                            <span style="font-size: 0.8rem; color: var(--text-secondary);">Kelola percakapan yang Anda
                                bagikan.</span>
                        </div>
                        <button class="github-submit-btn"
                            style="background: transparent; color: var(--text-primary); border: 1px solid var(--border-subtle);">Kelola</button>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong style="display: block;">Hapus semua obrolan</strong>
                            <span style="font-size: 0.8rem; color: var(--text-secondary);">Tindakan ini tidak dapat
                                dibatalkan.</span>
                        </div>
                        <button class="option-item delete" style="width: auto; padding: 8px 15px; border: 1px solid var(--danger); margin-top:10px;" onclick="openConfirmModal('Hapus Semua Obrolan?',
                        'Seluruh riwayat chat Anda di semua percakapan akan musnah. Ini tidak dapat dibatalkan.', 'clearAllChats')">Hapus semua obrolan</button>
                    </div>
                </div>

                <div id="tab-tentang" class="tab-pane">
                <h3 style="margin-bottom: 20px;">Tentang SAHAJA AI</h3>
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-subtle); padding-bottom: 10px;">
                        <span>Syarat Penggunaan</span>
                        <button class="github-submit-btn" style="background: transparent; color: var(--text-primary); border: 1px solid var(--border-subtle); padding: 5px 15px;" onclick="window.open('{{ route('terms') }}', '_blank')">Lihat</button>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-subtle); padding-bottom: 10px;">
                        <span>Kebijakan Privasi</span>
                        <button class="github-submit-btn" style="background: transparent; color: var(--text-primary); border: 1px solid var(--border-subtle); padding: 5px 15px;" onclick="window.open('{{ route('privacy') }}', '_blank')">Lihat</button>
                    </div>
                    <div style="margin-top: 20px; text-align: center; color: var(--text-secondary); font-size: 0.85rem;">
                        Versi Beta 3.5<br>
                        Dibuat oleh: Faqih Hidayah
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    {{-- Share Modal --}}
    <div class="modal-overlay" id="shareModal" style="z-index: 100005;">
        <div class="modal-content" style="max-width: 440px; background: var(--bg-overlay) !important; padding: 24px; border-radius: var(--radius-lg); border: 1px solid var(--border-subtle);">
            <button class="modal-close" onclick="closeCustomModal('shareModal')" style="position: absolute; right: 15px; top: 15px;" aria-label="Tutup modal"><i class="fas fa-times"></i></button>
            <h2 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 8px; color: var(--text-primary);"><i class="fas fa-share-nodes" style="color: var(--accent); margin-right: 8px;"></i>Bagikan Percakapan</h2>
            <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 16px;">Siapa saja yang memiliki tautan ini dapat melihat percakapan.</p>
            <div class="github-input-group" style="display: flex; gap: 8px;">
                <input type="text" id="shareLinkInput" class="github-input" readonly style="flex: 1; font-size: 0.85rem;">
                <button class="github-submit-btn" onclick="copyShareLink()">Salin</button>
            </div>
        </div>
    </div>

    {{-- Rename Modal --}}
    <div class="modal-overlay" id="renameRoomModal" style="z-index: 100005;">
        <div class="modal-content" style="max-width: 400px; background: var(--bg-overlay) !important; padding: 25px; border-radius: var(--radius-lg); border: 1px solid var(--border-subtle);">
            <button class="modal-close" onclick="closeCustomModal('renameRoomModal')" style="position: absolute; right: 15px; top: 15px;" aria-label="Tutup modal"><i class="fas fa-times"></i></button>
            <h2 style="font-size: 1.2rem; margin-bottom: 15px; color: var(--text-primary);"><i class="fas fa-pen" style="color: var(--accent); margin-right: 8px;"></i>Ganti Nama</h2>
            <div class="github-input-group" style="display: flex; gap: 10px;">
                <input type="text" id="renameInput" class="github-input" placeholder="Nama percakapan baru...">
                <button id="btnConfirmRename" class="github-submit-btn" onclick="executeRename()">Simpan</button>
            </div>
        </div>
    </div>

    {{-- Confirm Danger Modal --}}
    <div class="modal-overlay" id="confirmDangerModal" style="z-index: 100005;">
        <div class="modal-content" style="max-width: 400px; background: var(--bg-overlay) !important; padding: 25px; border-radius: var(--radius-lg); border: 1px solid var(--border-subtle); text-align: center;">
            <button class="modal-close" onclick="closeCustomModal('confirmDangerModal')" style="position: absolute; right: 15px; top: 15px;" aria-label="Tutup modal"><i class="fas fa-times"></i></button>
            <div style="font-size: 3rem; color: var(--danger); margin-bottom: 10px;"><i class="fas fa-triangle-exclamation"></i></div>
            <h2 id="dangerModalTitle" style="font-size: 1.2rem; margin-bottom: 10px; color: var(--text-primary);">Konfirmasi</h2>
            <p id="dangerModalText" style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 20px;">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button class="github-submit-btn" style="background: transparent; border: 1px solid var(--border-subtle); color: var(--text-primary);" onclick="closeCustomModal('confirmDangerModal')">Batal</button>
                <button id="btnConfirmDanger" class="github-submit-btn" style="background: var(--danger);" onclick="executeDangerAction()">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <script>
        // 1. VARIABEL GLOBAL
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let pendingAvatarBase64 = null;
        let targetActionId = null;
        let targetActionType = '';

        // 2. FUNGSI TOAST (BADGE NOTIFIKASI)
        window.showToast = function(message, type = 'info') {
            let container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                container.style.cssText = 'position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 100000; display: flex; flex-direction: column; gap: 10px; pointer-events: none;';
                document.body.appendChild(container);
            }
            const toast = document.createElement('div');
            const icon = type === 'success' ? 'check-circle' : (type === 'error' ? 'exclamation-circle' : 'info-circle');
            const color = type === 'success' ? '#10b981' : (type === 'error' ? '#ef4444' : '#3b82f6');
            toast.style.cssText = `background: rgba(30, 41, 59, 0.95); color: white; padding: 12px 24px; border-radius: 12px; font-size: 0.9rem; display: flex; align-items: center; gap: 10px; animation: slideDown 0.3s ease forwards; backdrop-filter: blur(8px); border-left: 4px solid ${color};`;
            toast.innerHTML = `<i class="fas fa-${icon}"></i> <span>${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
        };

        // 3. KENDALI MODAL & UI
        window.closeCustomModal = function(modalId) {
            const m = document.getElementById(modalId);
            if (m) m.classList.remove('show');
        };

        window.openConfirmModal = function(title, text, type, id = null) {
            targetActionType = type; targetActionId = id;
            document.getElementById('dangerModalTitle').innerText = title;
            document.getElementById('dangerModalText').innerText = text;
            document.getElementById('confirmDangerModal').classList.add('show');
            document.querySelectorAll('.options-menu, .logout-menu').forEach(el => el.classList.remove('show'));
        };

        window.toggleMenu = function(e, id) {
            if (e) { e.preventDefault(); e.stopPropagation(); }
            const targetMenu = document.getElementById(id);
            if (!targetMenu) return;
            const isShown = targetMenu.classList.contains('show');
            document.querySelectorAll('.options-menu, .logout-menu').forEach(el => el.classList.remove('show'));
            if (!isShown) targetMenu.classList.add('show');
        };

        // 4. MANAJEMEN SESSION
        window.renameSession = function(id) {
            targetActionId = id;
            document.getElementById('renameInput').value = document.getElementById(`title-${id}`).innerText;
            document.getElementById('renameRoomModal').classList.add('show');
            document.getElementById(`menu-${id}`)?.classList.remove('show');
        };

        window.deleteSession = function(id) { openConfirmModal("Hapus Percakapan?", "Percakapan ini akan dihapus secara permanen.", "deleteRoom", id); };
        window.clearAllChats = function() { openConfirmModal("Hapus Semua Obrolan?", "Seluruh riwayat chat Anda di semua percakapan akan musnah. Ini tidak dapat dibatalkan.", "clearAllChats"); };

        window.shareSession = async function(id) {
            try {
                const response = await fetch(`/session/${id}/share`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken } });
                const data = await response.json();
                if (data.success) {
                    document.getElementById('shareLinkInput').value = data.url;
                    document.getElementById('shareModal').classList.add('show');
                }
            } catch(e) { showToast("Gagal membuat link", "error"); }
            document.getElementById(`menu-${id}`)?.classList.remove('show');
        };

        window.copyShareLink = function() {
            document.getElementById('shareLinkInput').select();
            document.execCommand("copy");
            showToast("Tautan berhasil disalin!", "success");
            closeCustomModal('shareModal');
        };

        // 5. OTAK DATABASE (SIMPAN & HAPUS)
        window.executeRename = async function() {
            const newName = document.getElementById('renameInput').value.trim();
            if(!newName) return showToast("Nama tidak boleh kosong", "error");
            try {
                await fetch(`/session/${targetActionId}/rename`, { method: 'PUT', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ title: newName }) });
                document.getElementById(`title-${targetActionId}`).innerText = newName;
                closeCustomModal('renameRoomModal');
                showToast("Nama berhasil diubah", "success");
            } catch(e) { showToast("Gagal mengganti nama", "error"); }
        };

        window.executeDangerAction = async function() {
            closeCustomModal('confirmDangerModal');
            try {
                if (targetActionType === 'deleteRoom') {
                    await fetch(`/session/${targetActionId}/delete`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken } });
                    document.getElementById(`session-${targetActionId}`)?.remove();
                    showToast("Percakapan dihapus", "success");

                } else if (targetActionType === 'clearAllChats') {
                    await fetch('/profile/chat/clear', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken } });
                    showToast("Seluruh riwayat berhasil dihapus", "success");
                    setTimeout(() => window.location.href = '/chat', 1000);

                } else if (targetActionType === 'deleteAccount') {
                    await fetch('/profile/account/delete', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken } });
                    window.location.href = '/';

                } else if (targetActionType === 'deletePost') {
                    const res = await fetch(`/online/${targetActionId}/delete`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } });
                    const data = await res.json();
                    if(data.success) {
                        document.getElementById(`post-${targetActionId}`)?.remove();
                        showToast("Postingan dihapus", "success");
                    } else showToast(data.message || "Gagal menghapus", "error");

                } else if (targetActionType === 'deleteAvatar') {
                    await fetch('/profile/update', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ avatar: null }) });
                    showToast("Foto profil dihapus", "success");
                    setTimeout(() => window.location.reload(), 1000);
                }
            } catch(e) { showToast("Terjadi kesalahan server", "error"); }
        };

        // 6. PENGATURAN PROFIL
        window.openSettingsModal = function() {
            document.getElementById('settingsModal').classList.add('show');
            document.getElementById('logout-menu')?.classList.remove('show');
            const isLight = document.body.classList.contains('light-mode') || document.documentElement.classList.contains('light-mode');
            document.getElementById('btnThemeLight')?.classList.toggle('active', isLight);
            document.getElementById('btnThemeDark')?.classList.toggle('active', !isLight);
        };
        window.closeSettingsModal = function() { document.getElementById('settingsModal').classList.remove('show'); };

        window.switchTab = function(tabId) {
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.nav-btn').forEach(el => el.classList.remove('active'));
            document.getElementById('tab-' + tabId).classList.add('active');
            event.currentTarget.classList.add('active');
        };

        window.setTheme = function(mode) {
            const isLight = mode === 'light';
            document.documentElement.classList.toggle('light-mode', isLight);
            document.body.classList.toggle('light-mode', isLight);
            localStorage.setItem('sahaja-theme', isLight ? 'light' : 'dark');
            document.getElementById('btnThemeLight')?.classList.toggle('active', isLight);
            document.getElementById('btnThemeDark')?.classList.toggle('active', !isLight);
            showToast("Tema berhasil diubah", "success");
        };

        document.getElementById('avatarInput')?.addEventListener('change', function(e) {
            const file = e.target.files[0]; if (!file) return;
            const reader = new FileReader();
            reader.onload = function(event) {
                const img = new Image(); img.src = event.target.result;
                img.onload = () => {
                    const canvas = document.createElement('canvas'); const MAX = 200; let w = img.width; let h = img.height;
                    if (w > h) { if (w > MAX) { h *= MAX / w; w = MAX; } } else { if (h > MAX) { w *= MAX / h; h = MAX; } }
                    canvas.width = w; canvas.height = h; canvas.getContext('2d').drawImage(img, 0, 0, w, h);
                    pendingAvatarBase64 = canvas.toDataURL('image/jpeg', 0.8);
                    document.getElementById('previewAvatar').src = pendingAvatarBase64;
                    showToast("Foto siap. Klik 'Simpan' untuk menerapkan.", "info");
                }
            }
            reader.readAsDataURL(file);
        });

        window.simpanProfil = async function() {
            const newName = document.getElementById('inputNamaProfil').value.trim();
            if(!newName) return showToast("Nama tidak boleh kosong!", "error");
            const payload = { name: newName }; if (pendingAvatarBase64 !== null) payload.avatar = pendingAvatarBase64;
            try {
                const res = await fetch('/profile/update', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify(payload) });
                const data = await res.json();
                if(data.success) { showToast("Profil diperbarui!", "success"); setTimeout(() => window.location.reload(), 1000); }
            } catch(e) { showToast("Gagal menyimpan profil", "error"); }
        };

        // 7. POST & LIKE FASE 2
        window.submitPost = async function() {
            const input = document.getElementById('postInput');
            const text = input.value.trim();
            const btn = document.querySelector('.post-btn');
            if (!text) return showToast("Postingan tidak boleh kosong!", "error");
            btn.innerText = "Mengirim...";
            try {
                const response = await fetch("{{ route('online.post') }}", {
                    method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" },
                    body: JSON.stringify({ body: text })
                });
                const data = await response.json();
                if(data.success) { showToast("Berhasil diposting!", "success"); setTimeout(() => window.location.reload(), 800); }
                else throw new Error(data.message);
            } catch(e) { showToast("Gagal mengirim", "error"); } finally { btn.innerText = "Kirim Postingan"; }
        };

        window.toggleLike = async function(postId, btnElement) {
            try {
                const response = await fetch(`/online/${postId}/like`, { method: "POST", headers: { "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" } });
                const data = await response.json();
                const icon = btnElement.querySelector('i'); const countSpan = btnElement.querySelector('.like-count');
                let currentCount = parseInt(countSpan.innerText);
                if (data.status === 'liked') { icon.className = 'fas fa-heart'; btnElement.style.color = '#ef4444'; countSpan.innerText = currentCount + 1; }
                else { icon.className = 'far fa-heart'; btnElement.style.color = 'var(--text-secondary)'; countSpan.innerText = currentCount - 1; }
            } catch(e) { showToast("Gagal memproses like", "error"); }
        };

        // 8. LOGIKA KOMENTAR FASE 2 (YANG DITUNGGU-TUNGGU!)
        window.toggleComment = function(postId) {
            const section = document.getElementById(`comments-section-${postId}`);
            if (!section) return showToast("Error: Area komentar tidak ditemukan", "error");

            if (section.style.display === 'none' || section.style.display === '') {
                section.style.display = 'block';
                // Delay animasi kecil agar kolom sempat di-render browser
                setTimeout(() => {
                    const input = document.getElementById(`comment-input-${postId}`);
                    if(input) input.focus();
                }, 50);
            } else {
                section.style.display = 'none';
            }
        };

        window.submitComment = async function(postId) {
            const input = document.getElementById(`comment-input-${postId}`);
            if(!input) return;
            const text = input.value.trim();

            if (!text) return showToast("Komentar tidak boleh kosong!", "error");

            const btn = input.nextElementSibling;
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'; // Efek loading

            try {
                const res = await fetch(`/online/${postId}/comment`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ body: text })
                });

                const data = await res.json();
                if (data.success) {
                    showToast("Komentar terkirim!", "success");
                    input.value = '';
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    showToast("Gagal mengirim komentar", "error");
                    btn.innerHTML = originalIcon;
                }
            } catch (e) {
                showToast("Terjadi kesalahan server", "error");
                btn.innerHTML = originalIcon;
            }
        };

        // 9. EVENT LISTENER BAWAAN (TEMA & KLIK LUAR)
        if (localStorage.getItem('sahaja-theme') === 'light' || localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.add('light-mode');
            document.body.classList.add('light-mode');
        }
        document.getElementById('sidebarToggleBtn')?.addEventListener('click', e => { e.stopPropagation(); document.getElementById('sidebar').classList.toggle('collapsed'); });
        document.getElementById('mobileToggleBtn')?.addEventListener('click', e => { e.stopPropagation(); document.getElementById('sidebar').classList.toggle('mobile-open'); });

        window.addEventListener('click', e => {
            if (window.innerWidth <= 768 && !document.getElementById('sidebar').contains(e.target) && !e.target.closest('.mobile-toggle-btn')) document.getElementById('sidebar').classList.remove('mobile-open');
            if (!e.target.closest('.settings-modal-box')) document.querySelectorAll('.options-menu, .logout-menu').forEach(el => el.classList.remove('show'));
        });
    </script>
</body>

</html>

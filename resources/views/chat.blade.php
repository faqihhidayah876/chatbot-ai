<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SAHAJA AI</title>
    <link rel="icon"
        href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🤖</text></svg>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Roboto+Mono:wght@400;500&display=swap"
        rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.6.0/mammoth.browser.min.js"></script>

    <style>
        /* ===== CSS VARIABLES ===== */
        :root {
            --main-bg: #0a0e17;
            --sidebar-bg: rgba(15, 23, 42, 0.95);
            --glass-border: rgba(98, 160, 234, 0.15);
            --glass-highlight: rgba(255, 255, 255, 0.05);
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --accent-color: #2563eb;
            --accent-gradient: linear-gradient(135deg, #2563eb, #06b6d4);
            --message-user-bg: linear-gradient(135deg, #2563eb, #1d4ed8);
            --message-ai-bg: transparent;
            --footer-bg: rgba(15, 23, 42, 0.5);
            --danger-color: #ef4444;
            --code-bg: #1e1e1e;
            --inline-code-bg: rgba(37, 99, 235, 0.15);
            --inline-code-text: #60a5fa;
        }

        body.light-mode {
            --main-bg: #ffffff;
            --sidebar-bg: #f8fafc;
            --glass-border: #e2e8f0;
            --glass-highlight: #f1f5f9;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --message-ai-bg: transparent;
            --footer-bg: #f1f5f9;
            --code-bg: #f4f6f8;
            --inline-code-bg: #e2e8f0;
            --inline-code-text: #d93025;
            background: #ffffff;
        }

        /* ===== GLOBAL RESET ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--main-bg);
            color: var(--text-primary);
            height: 100vh;
            overflow: hidden;
            display: flex;
            transition: background 0.3s, color 0.3s;
        }

        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 20% 30%, rgba(37, 99, 235, 0.15) 0%, transparent 70%),
                radial-gradient(circle at 80% 80%, rgba(6, 182, 212, 0.15) 0%, transparent 70%);
            z-index: -2;
            pointer-events: none;
        }

        body.light-mode::before {
            background: radial-gradient(circle at 20% 30%, rgba(37, 99, 235, 0.05) 0%, transparent 70%),
                radial-gradient(circle at 80% 80%, rgba(6, 182, 212, 0.05) 0%, transparent 70%);
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
            width: 280px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--glass-border);
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 50;
            flex-shrink: 0;
            overflow: hidden;
            white-space: nowrap;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar.collapsed .text-label,
        .sidebar.collapsed .brand-text,
        .sidebar.collapsed .sidebar-footer-details,
        .sidebar.collapsed .options-btn {
            display: none !important;
            opacity: 0;
        }

        .sidebar.collapsed .sidebar-brand {
            justify-content: center;
            padding: 20px 0;
            flex-direction: column;
            gap: 15px;
        }

        .sidebar.collapsed .brand-logo-container,
        .sidebar.collapsed .toggle-btn-sidebar,
        .sidebar.collapsed .history-icon {
            margin-right: 0;
            margin-left: 0;
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
            padding: 24px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand-logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon-bg {
            width: 40px;
            height: 40px;
            background: var(--accent-gradient);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            color: white;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .brand-text {
            font-weight: 700;
            font-size: 1.2rem;
            letter-spacing: 1px;
            color: var(--text-primary);
        }

        .toggle-btn-sidebar {
            color: var(--text-secondary);
            width: 36px;
            height: 36px;
            border-radius: 8px;
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-btn-sidebar:hover {
            background: var(--glass-highlight);
            color: var(--text-primary);
        }

        .new-chat-wrapper {
            padding: 0 16px 20px;
        }

        .new-chat-btn {
            background: var(--accent-gradient);
            color: white;
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            transition: 0.3s;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .new-chat-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
        }

        .history-container {
            flex: 1;
            overflow-y: auto;
            padding: 10px 12px;
            overflow-x: hidden;
        }

        .history-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 12px;
            padding-left: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .history-item-wrapper {
            position: relative;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 10px;
            transition: 0.2s;
        }

        .history-item-wrapper:hover {
            background: var(--glass-highlight);
        }

        .history-item-wrapper.active {
            background: rgba(37, 99, 235, 0.15);
            border: 1px solid rgba(37, 99, 235, 0.3);
        }

        .history-item {
            padding: 10px 12px;
            display: flex;
            align-items: center;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            flex-grow: 1;
            min-width: 0;
        }

        .history-item:hover,
        .history-item-wrapper.active .history-item {
            color: var(--text-primary);
        }

        .history-link {
            display: flex;
            align-items: center;
            width: 100%;
            overflow: hidden;
        }

        .history-icon {
            margin-right: 12px;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .history-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }

        .options-btn {
            opacity: 0;
            transition: 0.2s;
            padding: 8px;
            border-radius: 6px;
            color: var(--text-secondary);
            flex-shrink: 0;
            margin-right: 5px;
        }

        .history-item-wrapper:hover .options-btn {
            opacity: 1;
        }

        .options-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
        }

        .options-menu {
            position: absolute;
            right: 10px;
            top: 40px;
            background: #1e293b;
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            padding: 6px;
            width: 140px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.5);
            z-index: 100;
            display: none;
            backdrop-filter: blur(16px);
        }

        body.light-mode .options-menu {
            background: #ffffff;
            color: #333;
            border-color: #e2e8f0;
        }

        .options-menu.show {
            display: block;
            animation: fadeIn 0.2s ease;
        }

        .option-item {
            padding: 8px 12px;
            font-size: 0.85rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.2s;
        }

        body.light-mode .option-item {
            color: #333;
        }

        .option-item:hover {
            background: var(--glass-highlight);
        }

        .option-item.delete {
            color: var(--danger-color);
            width: 100%;
            text-align: left;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid var(--glass-border);
            background: var(--footer-bg);
            transition: background 0.3s;
            position: relative;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            border-radius: 12px;
            padding: 5px;
            transition: 0.2s;
        }

        .user-profile:hover {
            background: var(--glass-highlight);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--accent-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
        }

        body.light-mode .sidebar-footer-details div {
            color: #334155 !important;
        }

        body.light-mode .sidebar-footer-details div:last-child {
            color: #64748b !important;
        }

        .logout-menu {
            position: absolute;
            bottom: 70px;
            left: 10px;
            width: 260px;
            background: #1e293b;
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 6px;
            display: none;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.3);
            z-index: 101;
        }

        body.light-mode .logout-menu {
            background: #ffffff;
            border-color: #e2e8f0;
            color: #333;
        }

        .logout-menu.show {
            display: block;
            animation: fadeIn 0.2s ease;
        }

        /* MAIN CONTENT */
        .main-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: relative;
            transition: all 0.3s ease;
        }

        .chat-header {
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--glass-border);
            background: rgba(10, 14, 23, 0.6);
            backdrop-filter: blur(12px);
            z-index: 10;
        }

        body.light-mode .chat-header {
            background: rgba(255, 255, 255, 0.8);
        }

        .mobile-toggle-btn {
            display: none;
            font-size: 1.2rem;
            margin-right: 16px;
            color: var(--text-primary);
        }

        .chat-title {
            font-size: 1.2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-primary);
        }

        .model-badge {
            background: rgba(37, 99, 235, 0.15);
            border: 1px solid var(--accent-color);
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--accent-color);
        }

        .settings-container {
            position: relative;
            z-index: 200;
            /* Tambah z-index tinggi */
        }

        .icon-btn {
            font-size: 1.2rem;
            color: var(--text-secondary);
            transition: 0.2s;
            padding: 8px;
            border-radius: 8px;
        }

        .icon-btn:hover {
            background: var(--glass-highlight);
            color: var(--text-primary);
        }

        .settings-menu-dropdown {
            position: absolute;
            right: 0;
            top: 50px;
            background: #1e293b;
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 8px;
            width: 200px;
            display: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
            z-index: 1000;
            /* Naikkan z-index */
            pointer-events: auto;
            /* Pastikan bisa diklik */
        }

        body.light-mode .settings-menu-dropdown {
            background: #ffffff;
            color: #333;
        }

        .settings-menu-dropdown.show {
            display: block !important;
            /* Pakai !important untuk override */
            animation: fadeIn 0.2s ease;
        }

        /* Animasi fadeIn jika belum ada */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* PERBAIKAN TOMBOL SETTINGS */
        #settingsBtn {
            position: relative;
            z-index: 201;
            cursor: pointer;
            pointer-events: auto;
        }

        /* ===== ATTACH MENU DROPDOWN ===== */
        .attach-menu {
            position: absolute;
            bottom: 50px;
            left: 0;
            background: #1e293b;
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 8px;
            width: 220px;
            display: none;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.4);
            z-index: 101;
            backdrop-filter: blur(16px);
        }

        body.light-mode .attach-menu {
            background: #ffffff;
            border-color: #e2e8f0;
            color: #333;
        }

        .attach-menu.show {
            display: block;
            animation: fadeIn 0.2s ease;
        }

        /* MESSAGES - Padding bottom ditambahkan agar tidak tertutup input box */
        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 30px 5% 120px;
            display: flex;
            flex-direction: column;
            gap: 30px;
            scroll-behavior: smooth;
        }

        .message {
            display: flex;
            gap: 16px;
            max-width: 100%;
            animation: slideUp 0.3s ease-out;
        }

        .message.user {
            flex-direction: row-reverse;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .user-avatar-msg {
            background: var(--message-user-bg);
            color: white;
        }

        .ai-avatar-msg {
            background: var(--accent-gradient);
            color: white;
        }

        .message-content {
            display: flex;
            flex-direction: column;
            max-width: 85%;
            min-width: 0;
        }

        /* FIX BATAS TINGGI BUBBLE USER (Agar PDF tidak kepanjangan di UI) */
        .message-bubble {
            padding: 16px 20px;
            border-radius: 16px;
            line-height: 1.6;
            font-size: 0.95rem;
            position: relative;
        }

        .user .message-bubble {
            background: var(--message-user-bg);
            color: white;
            border-bottom-right-radius: 4px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            max-height: 400px;
            overflow-y: auto;
            white-space: pre-wrap;
        }

        .ai .message-bubble {
            background: transparent;
            border: none;
            padding: 0 5px;
            color: var(--text-primary);
            box-shadow: none;
            backdrop-filter: none;
        }

        .markdown-body {
            width: 100%;
            display: block;
            line-height: 1.7;
            font-size: 0.95rem;
            overflow-x: auto;
        }

        .markdown-body>* {
            margin-bottom: 16px;
        }

        .markdown-body p {
            margin-bottom: 16px;
            white-space: pre-wrap;
        }

        .markdown-body h1,
        .markdown-body h2,
        .markdown-body h3 {
            font-weight: 600;
            margin-top: 24px;
            margin-bottom: 12px;
            color: var(--text-primary);
        }

        .markdown-body pre {
            background: #282c34 !important;
            border-radius: 8px;
            padding: 16px;
            border: 1px solid var(--glass-border);
            overflow-x: auto;
            margin: 16px 0;
            color: #e3e3e3;
            display: block;
            max-width: 100%;
        }

        .markdown-body code {
            font-family: 'Roboto Mono', monospace;
            font-size: 0.9em;
        }

        /* ===== TABEL MARKDOWN ===== */
        .markdown-body table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            margin-bottom: 16px;
            display: block;
            overflow-x: auto; /* Biar tabel bisa di-scroll ke kanan di HP */
            border-radius: 8px;
            border: 1px solid var(--glass-border);
        }

        .markdown-body table th,
        .markdown-body table td {
            padding: 10px 16px;
            border: 1px solid var(--glass-border);
            text-align: left;
            font-size: 0.9rem;
        }

        .markdown-body table th {
            background-color: rgba(37, 99, 235, 0.15); /* Biru transparan elegan */
            font-weight: 600;
            color: var(--accent-color);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .markdown-body table tr {
            background-color: transparent;
            transition: background-color 0.2s;
        }

        .markdown-body table tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.02); /* Efek belang-belang */
        }

        .markdown-body table tr:hover {
            background-color: rgba(255, 255, 255, 0.05); /* Efek nyala saat di-hover */
        }

        /* Penyesuaian Tabel untuk Light Mode */
        body.light-mode .markdown-body table th {
            background-color: #e2e8f0;
            color: #1e293b;
        }

        body.light-mode .markdown-body table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        body.light-mode .markdown-body table tr:hover {
            background-color: #f1f5f9;
        }

        /* ===== TYPING INDICATOR ===== */
        .typing-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 12px;
            border: 1px solid rgba(37, 99, 235, 0.2);
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            background: var(--accent-color);
            border-radius: 50%;
            animation: typingBounce 1.4s infinite ease-in-out both;
        }

        .typing-dot:nth-child(1) {
            animation-delay: -0.32s;
        }

        .typing-dot:nth-child(2) {
            animation-delay: -0.16s;
        }

        .mode-badge {
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 12px;
            margin-bottom: 5px;
            display: inline-block;
            font-weight: 600;
        }

        .mode-smart {
            background: rgba(255, 215, 0, 0.15);
            color: #d4a017;
            border: 1px solid rgba(255, 215, 0, 0.3);
        }

        .mode-fast {
            background: rgba(78, 205, 196, 0.15);
            color: #2a9d8f;
            border: 1px solid rgba(78, 205, 196, 0.3);
        }

        .switch-btn {
            background: none;
            border: none;
            color: #e74c3c;
            font-size: 0.75rem;
            text-decoration: underline;
            cursor: pointer;
            margin-left: 5px;
        }

        @keyframes typingBounce {

            0%,
            80%,
            100% {
                transform: scale(0.6);
                opacity: 0.5;
            }

            40% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .typing-text {
            font-size: 0.85rem;
            color: var(--accent-color);
            margin-left: 8px;
            font-weight: 500;
        }

        body.light-mode .typing-indicator {
            background: rgba(37, 99, 235, 0.05);
            border-color: rgba(37, 99, 235, 0.15);
        }

        /* ===== CODE HEADER ===== */
        .code-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #1e1e1e;
            padding: 8px 16px;
            border-radius: 8px 8px 0 0;
            border: 1px solid var(--glass-border);
            border-bottom: none;
        }

        .code-lang {
            font-size: 0.75rem;
            color: #9ca3af;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .code-header .code-copy-btn {
            position: static;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 0.75rem;
            color: #e3e3e3;
            display: flex;
            align-items: center;
            gap: 6px;
            backdrop-filter: blur(4px);
            font-weight: 500;
            transform: none;
            box-shadow: none;
        }

        .code-header .code-copy-btn:hover {
            background: rgba(37, 99, 235, 0.9);
            border-color: var(--accent-color);
            color: white;
        }

        body.light-mode .code-header {
            background: #f1f5f9;
            border-color: #e2e8f0;
        }

        body.light-mode .code-lang {
            color: #64748b;
        }

        body.light-mode .code-header .code-copy-btn {
            background: rgba(30, 41, 59, 0.9);
            color: #f1f5f9;
            border-color: rgba(255, 255, 255, 0.2);
        }

        body.light-mode .code-header .code-copy-btn:hover {
            background: var(--accent-color);
            color: white;
        }

        .code-header+pre {
            margin-top: 0 !important;
            border-top: none !important;
            border-radius: 0 0 8px 8px !important;
        }

        .ai-actions {
            display: flex;
            gap: 8px;
            margin-top: 5px;
            margin-left: 5px;
            opacity: 0;
            transition: 0.3s;
        }

        .message.ai:hover .ai-actions {
            opacity: 1;
        }

        .action-btn {
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 0.85rem;
            color: var(--text-secondary);
            background: transparent;
            border: 1px solid transparent;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .action-btn:hover {
            background: var(--glass-highlight);
            color: var(--text-primary);
            border-color: var(--glass-border);
        }

        /* ========================================================
           INPUT AREA BARU (GEMINI STYLE + VOICE + ATTACHMENT)
           ======================================================== */
        .input-container {
            padding: 12px 16px;
            background: rgba(10, 14, 23, 0.85);
            backdrop-filter: blur(12px);
            border-top: 1px solid var(--glass-border);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            position: relative;
            z-index: 20;
            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.2);
        }

        body.light-mode .input-container {
            background: rgba(255, 255, 255, 0.85) !important;
        }

        .input-wrapper {
            width: 100%;
            max-width: 800px;
            position: relative;
            display: flex;
            flex-direction: column;
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 12px 16px 8px 16px;
            transition: 0.3s;
            min-height: 56px;
        }

        body.light-mode .input-wrapper {
            background: #f1f5f9;
            border-color: #e2e8f0;
        }

        .input-wrapper:focus-within {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }

        /* File Preview Box */
        .file-preview-container {
            display: none;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: rgba(37, 99, 235, 0.15);
            border-radius: 12px;
            font-size: 0.85rem;
            color: var(--text-primary);
            margin-bottom: 8px;
            border: 1px solid rgba(37, 99, 235, 0.3);
            width: fit-content;
            max-width: 100%;
            overflow: hidden;
        }

        body.light-mode .file-preview-container {
            background: #e0e7ff;
            color: #1e3a8a;
            border-color: #bfdbfe;
        }

        .file-preview-container .fa-times {
            cursor: pointer;
            color: var(--danger-color);
            margin-left: 8px;
            padding: 4px;
            border-radius: 50%;
        }

        .file-preview-container .fa-times:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        .file-name-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 250px;
            font-weight: 500;
        }

        .chat-input {
            width: 100%;
            background: transparent;
            border: none;
            color: var(--text-primary);
            font-size: 0.95rem;
            padding: 4px 4px 10px 4px;
            resize: none;
            max-height: 150px;
            outline: none;
            line-height: 1.5;
        }

        body.light-mode .chat-input {
            color: #1e293b;
        }

        /* ===== LAYOUT TOMBOL BARU (KIRI DAN KANAN) ===== */
        .input-actions-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding-top: 4px;
        }

        .action-left {
            display: flex;
            align-items: center;
        }

        .action-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .icon-action-btn {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            transition: 0.3s;
            border: 1px solid transparent;
        }

        .icon-action-btn:hover {
            background: var(--glass-highlight);
            color: var(--text-primary);
        }

        body.light-mode .icon-action-btn {
            background: transparent;
            color: #64748b;
        }

        body.light-mode .icon-action-btn:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        .voice-btn.recording {
            background: #ef4444 !important;
            color: white !important;
            animation: pulseRecord 1.5s infinite;
        }

        @keyframes pulseRecord {
            0% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.6);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            }
        }

        .send-btn {
            width: 42px;
            height: 42px;
            background: var(--accent-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: 0.3s;
            flex-shrink: 0;
            box-shadow: 0 2px 10px rgba(37, 99, 235, 0.3);
        }

        .send-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.5);
        }

        .input-footer {
            font-size: 0.7rem;
            color: var(--text-secondary);
            text-align: center;
            margin-top: 2px;
            opacity: 0.8;
        }

        .welcome-screen {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
        }

        .welcome-logo {
            font-size: 5rem;
            margin-bottom: 20px;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            color: transparent;
            filter: drop-shadow(0 4px 12px rgba(37, 99, 235, 0.3));
        }

        .welcome-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        /* MODAL POPUP */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s;
        }

        /* ===== MODAL GITHUB ===== */
        .github-input-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 15px;
        }

        .github-input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid var(--glass-border);
            background: rgba(10, 14, 23, 0.5);
            color: var(--text-primary);
            font-size: 0.95rem;
            outline: none;
            transition: 0.3s;
        }

        body.light-mode .github-input {
            background: #f1f5f9;
            color: #1e293b;
        }

        .github-input:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }

        .github-submit-btn {
            background: var(--accent-gradient);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .github-submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: var(--sidebar-bg);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            color: var(--text-primary);
            backdrop-filter: blur(16px);
        }

        body.light-mode .modal-content {
            background: rgba(255, 255, 255, 0.95);
            color: #1e293b;
        }

        .modal-close {
            position: absolute;
            top: 16px;
            right: 16px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            font-size: 1.2rem;
            cursor: pointer;
            transition: 0.2s;
        }

        .modal-close:hover {
            background: var(--accent-color);
            color: white;
        }

        .modal-content h2 {
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            color: var(--accent-color);
            border-bottom: 1px solid var(--glass-border);
            padding-bottom: 0.5rem;
        }

        .modal-content p {
            margin-bottom: 1rem;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                height: 100%;
                transform: translateX(-100%);
                z-index: 99;
                width: 280px !important;
                transition: transform 0.3s ease;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
                box-shadow: 10px 0 30px rgba(0, 0, 0, 0.5);
            }

            .toggle-btn-sidebar {
                display: none;
            }

            .mobile-toggle-btn {
                display: block;
            }

            .main-container {
                width: 100%;
            }

            .input-container {
                padding: 4px 12px !important;
                position: sticky !important;
                bottom: 0 !important;
            }

            .input-wrapper {
                padding: 8px 12px 6px 12px !important;
                border-radius: 20px !important;
                min-height: auto !important;
            }

            .chat-input {
                padding: 2px 2px 6px 2px !important;
                font-size: 0.9rem !important;
            }

            .icon-action-btn,
            .send-btn {
                width: 36px !important;
                height: 36px !important;
            }

            .input-footer {
                font-size: 0.6rem !important;
                margin-top: 0 !important;
            }

            /* Padding bawah di-ekstra untuk mobile biar tidak nutup tombol copy */
            .messages-container {
                padding: 20px 4% 150px !important;
                gap: 20px !important;
            }

            .modal-content {
                padding: 1.5rem;
                width: 95%;
            }
        }

        @media (max-width: 375px) {
            .messages-container {
                padding: 15px 3% 130px !important;
            }
        }

        @supports (padding-bottom: env(safe-area-inset-bottom)) {
            .input-container {
                padding-bottom: max(2px, env(safe-area-inset-bottom)) !important;
            }

            /* Penyesuaian ekstrim padding untuk device dengan notch (iPhone) */
            .messages-container {
                padding-bottom: max(150px, env(safe-area-inset-bottom) + 120px) !important;
            }
        }

        .gemini-block {
            opacity: 0;
            transform: translateY(15px);
            transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1),
                transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 12px;
            display: block;
        }

        .gemini-block.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>
</head>

<body>

    <div class="modal-overlay" id="updateModal">
        <div class="modal-content">
            <button class="modal-close" id="closeModalBtn"><i class="fas fa-times"></i></button>
            <h2>
                <center>PEMBARUAN SAHAJA AI</center>
            </h2>
            <div class="modal-body">
                <p><strong>1. Analisis Dokumen & Gambar OCR</strong><br>
                    Sekarang Anda bisa mengupload file PDF atau Word (DOCX) & Gambar! SAHAJA AI akan menganalisis isinya
                    untukmu.
                </p>
                <p><strong>2. Fitur Share Chat</strong><br>
                    Bagikan obrolan SAHAJA AI kamu ke teman-teman dengan menggunakan link.
                </p>
                <p><strong>3. Voice Input</strong><br>
                    Anda bisa langsung ngobrol dengan AI tanpa mengetik dengan menggunakan mikrofon.
                </p>
                <p><strong>4. Fitur Upload Link Repo Github</strong><br>
                    Sekarang Anda bisa upload link repo github anda, SAHAJA AI akan membaca codingan Anda.
                </p>
            </div>
        </div>
    </div>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo-container">
                <div class="brand-icon-bg"><i class="fas fa-robot"></i></div>
                <span class="brand-text text-label">SAHAJA AI</span>
            </div>
            <button class="toggle-btn-sidebar" id="sidebarToggleBtn"><i class="fas fa-bars"></i></button>
        </div>
        <div class="new-chat-wrapper">
            <a href="{{ route('chat.new') }}" class="new-chat-btn">
                <i class="fas fa-plus"></i> <span class="btn-text text-label">Percakapan Baru</span>
            </a>
        </div>
        <div class="history-container">
            <div class="history-label text-label">Riwayat</div>
            @foreach ($sessions as $session)
                <div class="history-item-wrapper" id="session-{{ $session->id }}">
                    <a href="{{ route('chat.show', $session->id) }}"
                        class="history-item {{ isset($currentSession) && $currentSession->id == $session->id ? 'active' : '' }}">
                        <i class="far fa-comment-dots history-icon"></i>
                        <div class="history-link">
                            <span class="history-text text-label"
                                id="title-{{ $session->id }}">{{ $session->title ?? 'Chat Baru' }}</span>
                        </div>
                    </a>
                    <button class="options-btn" onclick="toggleMenu(event, 'menu-{{ $session->id }}')"><i
                            class="fas fa-ellipsis-v"></i></button>
                    <div class="options-menu" id="menu-{{ $session->id }}">
                        <div class="option-item" onclick="shareSession({{ $session->id }})"><i
                                class="fas fa-share-alt"></i> Bagikan</div>
                        <div class="option-item" onclick="renameSession({{ $session->id }})"><i
                                class="fas fa-pen"></i> Ganti Nama</div>
                        <div class="option-item delete" onclick="deleteSession({{ $session->id }})"><i
                                class="fas fa-trash"></i> Hapus</div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="sidebar-footer">
            <div class="user-profile" onclick="toggleMenu(event, 'logout-menu')">
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</div>
                <div class="sidebar-footer-details text-label" style="margin-left: 10px;">
                    <div style="font-weight: 600; color: var(--text-primary);">{{ Auth::user()->name ?? 'Pengguna' }}
                    </div>
                </div>
            </div>
            <div class="logout-menu" id="logout-menu">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf <button type="submit" class="option-item delete" style="width: 100%;"><i
                            class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="chat-header">
            <div style="display: flex; align-items: center;">
                <button class="mobile-toggle-btn" id="mobileToggleBtn"><i class="fas fa-bars"></i></button>
                <div class="chat-title">
                    <i class="fas fa-robot" style="color: var(--accent-color);"></i> SAHAJA AI
                    <span class="model-badge">Powered by: Kimi K 2.5</span>
                </div>
            </div>
            <div class="settings-container">
                <button class="icon-btn" id="settingsBtn"><i class="fas fa-cog"></i></button>
                <div class="settings-menu-dropdown" id="settingsMenu">
                    <div class="option-item" id="themeToggleItem"><i class="fas fa-adjust"></i> <span
                            id="themeText">Ganti Tema</span></div>
                </div>
            </div>
        </div>

        <div class="welcome-screen" id="welcomeScreen" style="{{ count($chats) > 0 ? 'display: none;' : '' }}">
            <div class="welcome-logo"><i class="fas fa-robot"></i></div>
            <div class="welcome-text">
                <h1 class="welcome-title">SAHAJA AI</h1>
                <p style="color: var(--text-secondary); font-size: 1.1rem;">Halo, {{ Auth::user()->name ?? 'Teman' }}.
                    Apa yang bisa saya bantu?</p>
            </div>
        </div>

        <div class="messages-container" id="messagesContainer"
            style="{{ count($chats) == 0 ? 'display: none;' : '' }}">
            @foreach ($chats as $chat)
                <div class="message user">
                    <div class="message-avatar user-avatar-msg"><i class="fas fa-user"></i></div>
                    <div class="message-content">
                        @php
                            // FILTER KHUSUS PDF: Menyembunyikan teks panjang dari layar
                            $displayMsg = $chat->user_message;
                            if (strpos($displayMsg, '[Lampiran Dokumen: ') === 0) {
                                preg_match('/\[Lampiran Dokumen: (.*?)\]/', $displayMsg, $match);
                                $fName = $match[1] ?? 'Dokumen';
                                $pos = strrpos($displayMsg, 'Instruksi User: ');
                                $inst = $pos !== false ? trim(substr($displayMsg, $pos + 16)) : '';
                                $displayMsg = '📎 [' . $fName . "]\n" . $inst;
                            }
                        @endphp
                        <div class="message-bubble">{{ $displayMsg }}</div>
                    </div>
                </div>
                <div class="message ai">
                    <div class="message-avatar ai-avatar-msg"><i class="fas fa-robot"></i></div>
                    <div class="message-content">
                        <div class="message-bubble markdown-body ai-raw-data" style="display: none;">
                            {{ $chat->ai_response }}</div>
                        <div class="message-bubble markdown-body ai-rendered-data"></div>
                        <div class="ai-actions">
                            <button class="action-btn" onclick="copyText(this)"><i class="far fa-copy"></i>
                                Salin</button>
                            <button class="action-btn"><i class="far fa-thumbs-up"></i></button>
                            <button class="action-btn"><i class="far fa-thumbs-down"></i></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="input-container">
            <div class="input-wrapper">

                <div class="file-preview-container" id="filePreviewContainer">
                    <i class="fas fa-file-alt" style="color: var(--accent-color);"></i>
                    <span class="file-name-text" id="fileNameDisplay">document.pdf</span>
                    <i class="fas fa-times" onclick="removeFile()" title="Hapus File"></i>
                </div>

                <input type="file" id="docInput"
                    accept=".pdf, .docx, application/pdf, application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                    style="display: none;">
                <input type="file" id="imageInput" accept="image/png, image/jpeg, image/jpg, image/webp"
                    style="display: none;">

                <textarea class="chat-input" id="chatInput" placeholder="Ketik pesan Anda di sini..." rows="1"></textarea>

                <div class="input-actions-wrapper">
                    <div class="action-left" style="position: relative;">
                        <button type="button" class="icon-action-btn" id="attachButton" title="Lampirkan File">
                            <i class="fas fa-paperclip"></i>
                        </button>

                        <div class="attach-menu" id="attachMenu">
                            <div class="option-item" id="btnUploadImage">
                                <i class="fas fa-image" style="color: #4ade80;"></i> Analisis Gambar (OCR)
                            </div>
                            <div class="option-item" id="btnUploadDoc">
                                <i class="fas fa-file-pdf" style="color: #f87171;"></i> File (PDF/DOCS)
                            </div>
                            <div class="option-item" id="btnUploadGithub">
                                <i class="fab fa-github" style="color: #a855f7;"></i> Link GitHub (Beta)
                            </div>
                        </div>
                    </div>

                    <div class="action-right">
                        <button type="button" class="icon-action-btn voice-btn" id="voiceButton"
                            title="Bicara dengan SAHAJA">
                            <i class="fas fa-microphone"></i>
                        </button>
                        <button type="button" class="send-btn" id="sendButton" title="Kirim Pesan">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="input-footer">SAHAJA AI dapat membuat kesalahan, periksa lebih lanjut</div>
        </div>
    </div>
    <div class="modal-overlay" id="githubModal">
        <div class="modal-content" style="max-width: 450px;">
            <button class="modal-close" id="closeGithubModalBtn"><i class="fas fa-times"></i></button>
            <h2 style="font-size: 1.3rem; border: none; margin-bottom: 0;"><i class="fab fa-github"></i> Impor Repository (Beta)</h2>
            <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 15px;">SAHAJA AI akan menganalisis file kode utama (.php, .js, dll) dari repo public.
                (Fitur masih dalam tahap pengembangan dan tidak sempurna)</p>

            <div class="github-input-group">
                <input type="text" id="githubLinkInput" class="github-input" placeholder="https://github.com/username/repo">
                <button id="submitGithubBtn" class="github-submit-btn">Muat Repository</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

    <script>
        // ========== KONFIGURASI AWAL ==========
        let currentSessionId = "{{ $currentSession ? $currentSession->id : '' }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let currentController = null;
        let lastUserMessage = "";
        window.activeForceMode = null;

        const chatInput = document.getElementById('chatInput');
        const voiceBtn = document.getElementById('voiceButton');

        // VARIABEL UNTUK FILE UPLOAD
        let extractedFileText = "";
        let base64Image = null; // untuk gambar
        let currentFileName = "";
        let currentGithubRepo = ""; //untuk github

        const attachBtn = document.getElementById('attachButton');
        const attachMenu = document.getElementById('attachMenu');
        const docInput = document.getElementById('docInput');
        const imageInput = document.getElementById('imageInput');
        const filePreviewContainer = document.getElementById('filePreviewContainer');
        const fileNameDisplay = document.getElementById('fileNameDisplay');

        marked.setOptions({
            sanitize: true,
            breaks: true,
            gfm: true,
            highlight: function(code, lang) {
                const language = hljs.getLanguage(lang) ? lang : 'plaintext';
                return hljs.highlight(code, {
                    language
                }).value;
            }
        });

        // ==========================================
        // EVENT LISTENERS UMUM & TEMA
        // ==========================================
        // ==========================================
        // EVENT LISTENERS UMUM & TEMA - PERBAIKAN TOTAL
        // ==========================================

        // 1. Sidebar toggles
        document.getElementById('sidebarToggleBtn').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('sidebar').classList.toggle('collapsed');
        });

        document.getElementById('mobileToggleBtn').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('sidebar').classList.toggle('mobile-open');
        });

        // 2. SETTINGS BUTTON - PERBAIKAN UTAMA
        const settingsBtn = document.getElementById('settingsBtn');
        const settingsMenu = document.getElementById('settingsMenu');

        if (settingsBtn && settingsMenu) {
            // Hapus event listener lama jika ada (prevent duplikat)
            const newSettingsBtn = settingsBtn.cloneNode(true);
            settingsBtn.parentNode.replaceChild(newSettingsBtn, settingsBtn);

            // Tambah event listener fresh
            newSettingsBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Settings clicked!');

                // Toggle dengan force reflow untuk memastikan render
                const isShown = settingsMenu.classList.contains('show');

                // Tutup menu lain dulu
                document.querySelectorAll('.options-menu, .logout-menu, .attach-menu').forEach(el => {
                    el.classList.remove('show');
                });

                if (isShown) {
                    settingsMenu.classList.remove('show');
                } else {
                    settingsMenu.classList.add('show');
                    // Force browser render
                    void settingsMenu.offsetWidth;
                }
            });

            // Update reference untuk penggunaan lain
            window.settingsBtnRef = newSettingsBtn;
        }

        // 3. THEME TOGGLE
        const themeToggleItem = document.getElementById('themeToggleItem');
        if (themeToggleItem) {
            themeToggleItem.addEventListener('click', function(e) {
                e.stopPropagation();
                document.body.classList.toggle('light-mode');
                const isLight = document.body.classList.contains('light-mode');
                localStorage.setItem('theme', isLight ? 'light' : 'dark');

                const themeText = document.getElementById('themeText');
                if (themeText) themeText.innerText = isLight ? 'Mode Gelap' : 'Mode Terang';

                // Tutup settings menu
                if (settingsMenu) settingsMenu.classList.remove('show');
            });
        }

        // 4. Inisialisasi tema
        if (localStorage.getItem('theme') === 'light') {
            document.body.classList.add('light-mode');
            const themeText = document.getElementById('themeText');
            if (themeText) themeText.innerText = 'Mode Gelap';
        }

        // 5. WINDOW CLICK HANDLER - PERBAIKAN
        window.addEventListener('click', function(e) {
            // Debug: console.log('Window clicked', e.target);

            // Cek apakah klik di dalam settings container
            const settingsContainer = document.querySelector('.settings-container');
            const clickedInsideSettings = settingsContainer && settingsContainer.contains(e.target);

            // Tutup sidebar mobile
            if (window.innerWidth <= 768) {
                const sidebar = document.getElementById('sidebar');
                if (sidebar && !sidebar.contains(e.target) && !e.target.closest('.mobile-toggle-btn')) {
                    sidebar.classList.remove('mobile-open');
                }
            }

            // Tutup SEMUA menu kecuali jika klik di settings (karena settings handle sendiri)
            if (!clickedInsideSettings) {
                document.querySelectorAll('.options-menu, .settings-menu-dropdown, .logout-menu, .attach-menu')
                    .forEach(el => {
                        el.classList.remove('show');
                    });
            }
        });

        // 6. Toggle menu function untuk history items
        function toggleMenu(e, id) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }

            const targetMenu = document.getElementById(id);
            if (!targetMenu) return;

            const isShown = targetMenu.classList.contains('show');

            // Tutup semua menu lain
            document.querySelectorAll('.options-menu, .settings-menu-dropdown, .logout-menu, .attach-menu').forEach(el => {
                if (el.id !== id) el.classList.remove('show');
            });

            // Toggle target
            if (isShown) {
                targetMenu.classList.remove('show');
            } else {
                targetMenu.classList.add('show');
            }
        }

        // ==========================================
        // FUNGSI MENU ATTACH & BACA FILE
        // ==========================================
        attachBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            attachMenu.classList.toggle('show');
        });

        document.getElementById('btnUploadDoc').addEventListener('click', () => {
            docInput.click();
            attachMenu.classList.remove('show');
        });

        document.getElementById('btnUploadImage').addEventListener('click', () => {
            imageInput.click();
            attachMenu.classList.remove('show');
        });

        // --- LOGIKA GITHUB BARU ---
        const githubModal = document.getElementById('githubModal');
        const closeGithubModalBtn = document.getElementById('closeGithubModalBtn');
        const submitGithubBtn = document.getElementById('submitGithubBtn');
        const githubLinkInput = document.getElementById('githubLinkInput');

        // Buka Modal GitHub
        document.getElementById('btnUploadGithub').addEventListener('click', () => {
            attachMenu.classList.remove('show');
            githubModal.classList.add('show');
            githubLinkInput.focus();
        });

        // Tutup Modal GitHub
        closeGithubModalBtn.addEventListener('click', () => {
            githubModal.classList.remove('show');
            githubLinkInput.value = '';
        });

        // Simpan Link & Munculkan Preview
        submitGithubBtn.addEventListener('click', () => {
            const link = githubLinkInput.value.trim();
            if (link.includes('github.com')) {
                removeFile(); // Bersihkan file lain jika ada

                // Ambil nama repo dari link (contoh: username/repo)
                const urlParts = link.split('github.com/');
                if (urlParts.length > 1) {
                    let repoName = urlParts[1].replace('.git', '').split('/').slice(0, 2).join('/');
                    currentGithubRepo = link;
                    currentFileName = repoName;

                    // Munculkan di Preview Box
                    filePreviewContainer.style.display = 'flex';
                    filePreviewContainer.querySelector('i').className = 'fab fa-github';
                    filePreviewContainer.querySelector('i').style.color = '#a855f7';
                    fileNameDisplay.textContent = "Repo: " + repoName;

                    githubModal.classList.remove('show');
                    githubLinkInput.value = '';
                }
            } else {
                alert('Tolong masukkan link GitHub yang valid!');
            }
        });

        imageInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;
            removeFile();

            currentFileName = file.name;
            filePreviewContainer.style.display = 'flex';
            filePreviewContainer.querySelector('i').className = 'fas fa-image';
            filePreviewContainer.querySelector('i').style.color = '#4ade80';
            fileNameDisplay.textContent = "Mengompresi gambar...";
            attachBtn.style.display = 'none';

            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = event => {
                const img = new Image();
                img.src = event.target.result;
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    // ini kompres gambar nya jadi 800 x 800 pixel
                    const MAX_WIDTH = 1600;
                    const MAX_HEIGHT = 1600;
                    let width = img.width;
                    let height = img.height;

                    if (width > height && width > MAX_WIDTH) {
                        height *= MAX_WIDTH / width;
                        width = MAX_WIDTH;
                    } else if (height > MAX_HEIGHT) {
                        width *= MAX_HEIGHT / height;
                        height = MAX_HEIGHT;
                    }

                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    base64Image = canvas.toDataURL('image/jpeg', 0.9);
                    fileNameDisplay.textContent = currentFileName + " (Siap dikirim)";
                    attachBtn.style.display = 'flex';
                    imageInput.value = '';
                }
            };
        });

        docInput.addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (!file) return;
            removeFile();

            currentFileName = file.name;
            filePreviewContainer.style.display = 'flex';
            filePreviewContainer.querySelector('i').className = 'fas fa-file-alt';
            filePreviewContainer.querySelector('i').style.color = 'var(--accent-color)';
            fileNameDisplay.textContent = "Mengekstrak teks...";
            attachBtn.style.display = 'none';

            try {
                if (file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf')) {
                    extractedFileText = await extractPdfText(file);
                } else if (file.name.toLowerCase().endsWith('.docx') || file.type.includes(
                        'wordprocessingml')) {
                    extractedFileText = await extractDocxText(file);
                } else {
                    alert("Format tidak didukung!");
                    removeFile();
                    return;
                }
                if (extractedFileText.length > 25000) {
                    extractedFileText = extractedFileText.substring(0, 25000) + "\n\n[INFO: TEKS DIPOTONG]";
                }
                fileNameDisplay.textContent = currentFileName;
            } catch (err) {
                alert("Gagal membaca dokumen.");
                removeFile();
            } finally {
                attachBtn.style.display = 'flex';
                docInput.value = '';
            }
        });

        function removeFile() {
            extractedFileText = "";
            base64Image = null;
            currentFileName = "";
            currentGithubRepo = ""; // RESET DATA GITHUB
            filePreviewContainer.style.display = 'none';
            docInput.value = '';
            imageInput.value = '';
        }

        async function extractPdfText(file) {
            const arrayBuffer = await file.arrayBuffer();
            const pdf = await pdfjsLib.getDocument({
                data: arrayBuffer
            }).promise;
            let text = "";
            const maxPages = Math.min(pdf.numPages, 25);
            for (let i = 1; i <= maxPages; i++) {
                const page = await pdf.getPage(i);
                const content = await page.getTextContent();
                const strings = content.items.map(item => item.str);
                text += strings.join(" ") + "\n";
            }
            return text;
        }

        async function extractDocxText(file) {
            const arrayBuffer = await file.arrayBuffer();
            const result = await mammoth.extractRawText({
                arrayBuffer: arrayBuffer
            });
            return result.value;
        }

        // ==========================================
        // FUNGSI VOICE INPUT & KIRIM PESAN
        // ==========================================
        let recognition = null;
        let isRecording = false;
        let final_transcript = '';

        if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRecognition();
            recognition.lang = 'id-ID';
            recognition.interimResults = true;
            recognition.continuous = false;

            recognition.onstart = function() {
                isRecording = true;
                final_transcript = '';
                voiceBtn.classList.add('recording');
                voiceBtn.innerHTML = '<i class="fas fa-stop"></i>';
                chatInput.placeholder = "Mendengarkan... (Bicara sekarang)";
            };

            recognition.onresult = function(event) {
                let interim_transcript = '';
                for (let i = event.resultIndex; i < event.results.length; ++i) {
                    if (event.results[i].isFinal) final_transcript += event.results[i][0].transcript;
                    else interim_transcript += event.results[i][0].transcript;
                }
                const prefix = window.preRecordInput ? window.preRecordInput + ' ' : '';
                chatInput.value = prefix + final_transcript + interim_transcript;
                chatInput.dispatchEvent(new Event('input'));
            };

            recognition.onerror = function(event) {
                forceStopRecordingUI();
            };
            recognition.onend = function() {
                forceStopRecordingUI();
            };
        } else {
            voiceBtn.style.display = 'none';
        }

        function toggleRecording() {
            if (!recognition) return alert("Browser tidak support.");
            if (isRecording) {
                recognition.stop();
                forceStopRecordingUI();
            } else {
                window.preRecordInput = chatInput.value.trim();
                try {
                    recognition.start();
                } catch (e) {}
            }
        }

        function forceStopRecordingUI() {
            isRecording = false;
            voiceBtn.classList.remove('recording');
            voiceBtn.innerHTML = '<i class="fas fa-microphone"></i>';
            chatInput.placeholder = "Ketik pesan Anda di sini...";
        }
        voiceBtn.addEventListener('click', toggleRecording);

        function switchToMode(targetMode) {
            window.activeForceMode = targetMode;
            if (currentController) currentController.abort();
            const oldLoading = document.querySelector('.message.ai:last-child');
            if (oldLoading && oldLoading.querySelector('.typing-indicator')) oldLoading.remove();
            sendMessage();
        }

        function switchToFastMode() {
            switchToMode('fast');
        }

        async function sendMessage() {
            if (isRecording && recognition) {
                recognition.stop();
                forceStopRecordingUI();
            }

            const messageInput = chatInput.value.trim();
            let finalMessageToSend;
            let displayMessage = messageInput;

            if (window.activeForceMode !== null) {
                if (!lastUserMessage) return;
                finalMessageToSend = lastUserMessage;
            } else {
                if (!messageInput && !extractedFileText && !base64Image) return;

                if (extractedFileText) {
                    const promptQuestion = messageInput || "Tolong analisis isi dokumen ini.";
                    finalMessageToSend =
                        `[Lampiran Dokumen: ${currentFileName}]\n"""\n${extractedFileText}\n"""\n\nInstruksi User: ${promptQuestion}`;
                    displayMessage = `📎 [${currentFileName}]\n${promptQuestion}`;
                } else if (base64Image) {
                    const promptQuestion = messageInput || "Tolong jelaskan gambar ini secara detail.";
                    finalMessageToSend = promptQuestion;
                    displayMessage = `🖼️ [Gambar: ${currentFileName}]\n${promptQuestion}`;
                } else if (currentGithubRepo) { // KONDISI BARU: JIKA ADA GITHUB REPO
                    const promptQuestion = messageInput || "Tolong analisis kode di repository ini.";
                    // Yang dikirim ke backend tetap teks prompt-nya saja
                    finalMessageToSend = promptQuestion;
                    displayMessage = `📦 [GitHub: ${currentFileName}]\n${promptQuestion}`;
                } else {
                    finalMessageToSend = messageInput;
                }
                lastUserMessage = finalMessageToSend;
            }

            if (window.activeForceMode === null) {
                document.getElementById('welcomeScreen').style.display = 'none';
                document.getElementById('messagesContainer').style.display = 'flex';
                chatInput.value = '';
                chatInput.style.height = 'auto';
                appendMessage('user', displayMessage);
            }

            const payload = {
                message: finalMessageToSend,
                session_id: currentSessionId
            };
            if (base64Image) payload.image_data = base64Image;
            if (currentGithubRepo) payload.github_repo = currentGithubRepo; // Untuk Github
            if (window.activeForceMode !== null) payload.force_mode = window.activeForceMode;

            let isComplex = detectComplexity(finalMessageToSend);
            if (extractedFileText) isComplex = true;
            let mode = (window.activeForceMode !== null) ? window.activeForceMode : (isComplex ? 'smart' : 'fast');
            if (base64Image) mode = 'vision';

            const loadingId = appendLoadingWithMode(mode);
            scrollToBottom();

            if (window.activeForceMode === null) removeFile();

            if (currentController) currentController.abort();
            currentController = new AbortController();

            try {
                const response = await fetch("{{ route('chat.send') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json"
                    },
                    body: JSON.stringify(payload),
                    signal: currentController.signal
                });

                if (!response.ok) throw new Error(`Server Error: ${response.status}`);
                const data = await response.json();
                if (data.error) throw new Error(data.message);

                const loadingBubble = document.getElementById(loadingId);
                if (loadingBubble) {
                    const aiMessageDiv = document.createElement('div');
                    aiMessageDiv.className = 'message ai';

                    let finalModelLabel = 'Mode Cepat';
                    let finalBadgeClass = 'mode-fast';
                    let extraStyle = '';

                    if (data.model_used && data.model_used.includes('llama')) {
                        finalModelLabel = 'Mode Vision (Llama 3.2)';
                        extraStyle =
                            'background: rgba(74, 222, 128, 0.15); color: #22c55e; border: 1px solid rgba(74, 222, 128, 0.3);';
                    } else if (data.model_used && data.model_used.includes('k2.5')) {
                        finalModelLabel = 'Mode Cerdas';
                        finalBadgeClass = 'mode-smart';
                    }

                    aiMessageDiv.innerHTML = `
                        <div class="message-avatar ai-avatar-msg"><i class="fas fa-robot"></i></div>
                        <div class="message-content">
                            <div class="mode-badge ${finalBadgeClass}" style="${extraStyle}">${finalModelLabel}</div>
                            <div class="message-bubble markdown-body"></div>
                            <div class="ai-actions">
                                <button class="action-btn" onclick="copyText(this)"><i class="far fa-copy"></i> Salin</button>
                            </div>
                        </div>
                    `;
                    loadingBubble.parentNode.replaceChild(aiMessageDiv, loadingBubble);
                    animateGeminiStyle(aiMessageDiv.querySelector('.message-bubble'), data.ai_response);
                    scrollToBottom();
                }

                if (!currentSessionId && data.session_id) {
                    window.history.pushState({}, '', `/chat/${data.session_id}`);
                    currentSessionId = data.session_id;
                }
                window.activeForceMode = null;

            } catch (error) {
                document.getElementById(loadingId)?.remove();
                if (error.name !== 'AbortError') alert("Gagal: " + error.message);
                window.activeForceMode = null;
            }
        }

        // ==========================================
        // FUNGSI UI & RENDER MATEMATIKA
        // ==========================================
        function appendLoadingWithMode(mode) {
            const id = 'loading-' + Date.now();
            const div = document.createElement('div');
            div.id = id;
            div.className = 'message ai';

            let badgeHtml = '';
            let textHtml = '';

            if (mode === 'vision') {
                badgeHtml =
                    `<div class="mode-badge" style="background: rgba(74, 222, 128, 0.15); color: #22c55e; border: 1px solid rgba(74, 222, 128, 0.3);"><i class="fas fa-eye"></i> Mode Vision (Llama 3.2)</div>`;
                textHtml = `<span class="typing-text">Menganalisis gambar...</span>`;
            } else if (mode === 'smart') {
                badgeHtml = `<div class="mode-badge mode-smart"><i class="fas fa-brain"></i> Mode Cerdas (K2.5)</div>`;
                textHtml =
                    `<span class="typing-text">Menganalisis logika kompleks... <button class="switch-btn" onclick="switchToFastMode()">[Beralih ke Cepat]</button></span>`;
            } else {
                badgeHtml = `<div class="mode-badge mode-fast"><i class="fas fa-bolt"></i> Mode Cepat (K2)</div>`;
                textHtml =
                    `<span class="typing-text">SAHAJA AI sedang berpikir... <button class="switch-btn" style="color:#d4a017;" onclick="switchToMode('smart')">[Beralih ke Cerdas]</button></span>`;
            }

            div.innerHTML =
                `<div class="message-avatar ai-avatar-msg"><i class="fas fa-robot"></i></div><div class="message-content">${badgeHtml}<div class="message-bubble"><div class="typing-indicator"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div>${textHtml}</div></div></div>`;
            document.getElementById('messagesContainer').appendChild(div);
            return id;
        }

        function detectComplexity(text) {
            const t = text.toLowerCase();
            const complex = ['coding', 'buatkan', 'analisis', 'html', 'laravel', 'script', 'error', 'database'];
            const simple = ['halo', 'hai', 'tes', 'ngoding', 'cerita'];
            if (complex.some(k => t.includes(k))) return true;
            if (t.split(' ').length < 10 && simple.some(k => t.includes(k))) return false;
            return t.split(' ').length > 15;
        }

        // ========== FUNGSI COPY UNTUK PESAN (ROBUST) ==========

        // ========== FUNGSI COPY UNTUK PESAN (ROBUST) ==========
        function copyText(btn) {
            console.log('copyText dipanggil', btn);
            try {
                // Cari elemen teks yang akan disalin
                const messageContent = btn.closest('.message-content');
                if (!messageContent) {
                    console.error('Tidak ditemukan .message-content');
                    return;
                }

                // Coba cari .markdown-body di dalam message-content
                let textElement = messageContent.querySelector('.markdown-body');
                if (!textElement) {
                    // Fallback: cari .message-bubble biasa
                    textElement = messageContent.querySelector('.message-bubble');
                }
                if (!textElement) {
                    alert('Tidak dapat menemukan teks untuk disalin.');
                    return;
                }

                const textToCopy = textElement.innerText || textElement.textContent;
                if (!textToCopy) {
                    alert('Tidak ada teks untuk disalin.');
                    return;
                }

                const originalHTML = btn.innerHTML;
                const showSuccess = () => {
                    btn.innerHTML = '<i class="fas fa-check"></i> Disalin';
                    btn.style.color = '#4ade80';
                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.style.color = '';
                    }, 2000);
                };

                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(textToCopy).then(showSuccess).catch(() => {
                        fallbackCopyText(textToCopy, showSuccess);
                    });
                } else {
                    fallbackCopyText(textToCopy, showSuccess);
                }
            } catch (err) {
                console.error('Error di copyText:', err);
                alert('Gagal menyalin teks.');
            }
        }

        // Fallback copy menggunakan execCommand
        function fallbackCopyText(text, callback) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-9999px';
            textArea.style.top = '0';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                const successful = document.execCommand('copy');
                if (successful && callback) callback();
                else alert('Gagal menyalin teks.');
            } catch (err) {
                alert('Gagal menyalin teks. Browser Anda tidak mendukung.');
            }
            document.body.removeChild(textArea);
        }

        // ========== FUNGSI COPY UNTUK CODE BLOCK ==========
        function copyCode(button, codeElement) {
            console.log('copyCode dipanggil', button, codeElement);
            if (!codeElement) return;
            const textToCopy = codeElement.textContent || codeElement.innerText;
            const originalHTML = button.innerHTML;
            const showSuccess = () => {
                button.innerHTML = '<i class="fas fa-check"></i> Disalin';
                button.style.background = 'rgba(74, 222, 128, 0.9)';
                button.style.color = 'white';
                setTimeout(() => {
                    button.innerHTML = '<i class="far fa-copy"></i> Salin';
                    button.style.background = '';
                    button.style.color = '';
                }, 2000);
            };

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(textToCopy).then(showSuccess).catch(() => {
                    fallbackCopyText(textToCopy, showSuccess);
                });
            } else {
                fallbackCopyText(textToCopy, showSuccess);
            }
        }

        // ========== FUNGSI MENAMBAHKAN HEADER DAN TOMBOL COPY PADA CODE BLOCK ==========
        function addCopyButtonsToCodeBlocks() {
            document.querySelectorAll('.markdown-body pre').forEach((pre) => {
                // Cek apakah sudah ada header
                if (pre.previousElementSibling?.classList.contains('code-header')) return;

                const code = pre.querySelector('code');
                if (!code) return;

                // Deteksi bahasa dari class
                let language = 'plaintext';
                const langClass = code.className.match(/language-(\w+)/);
                if (langClass) language = langClass[1];

                // Buat header
                const header = document.createElement('div');
                header.className = 'code-header';
                header.innerHTML = `
            <span class="code-lang">${language}</span>
            <button class="code-copy-btn" aria-label="Salin kode">
                <i class="far fa-copy"></i> Salin
            </button>
        `;

                // Sisipkan header sebelum pre
                pre.parentNode.insertBefore(header, pre);

                // Sesuaikan gaya pre
                pre.style.borderRadius = '0 0 8px 8px';
                pre.style.marginTop = '0';

                // Tambahkan event listener ke tombol copy
                const copyBtn = header.querySelector('.code-copy-btn');
                copyBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    copyCode(copyBtn, code);
                });
            });
        }

        function animateGeminiStyle(element, markdownText) {
            // 1. AMANKAN KODINGAN: Sembunyikan block code agar tidak ikut terpotong
            let safeText = markdownText;
            const codeBlocks = [];
            safeText = safeText.replace(/```[\s\S]*?```/g, function(match) {
                codeBlocks.push(match);
                // Ganti sementara dengan kode rahasia
                return `\n\n@@CODE_BLOCK_${codeBlocks.length - 1}@@\n\n`;
            });

            // 2. Potong teks berdasarkan baris kosong (sekarang aman karena code block disembunyikan)
            const parts = safeText.split(/\n\s*\n/);
            element.innerHTML = '';
            let totalDelay = 0;

            parts.forEach((part) => {
                if (!part.trim()) return; // Abaikan teks kosong

                // 3. KEMBALIKAN KODINGAN: Ganti kode rahasia dengan kodingan aslinya
                let restoredPart = part;
                codeBlocks.forEach((code, index) => {
                    restoredPart = restoredPart.replace(`@@CODE_BLOCK_${index}@@`, code);
                });

                const block = document.createElement('div');
                block.className = 'gemini-block';
                renderAIContent(restoredPart, block);
                element.appendChild(block);

                setTimeout(() => {
                    block.classList.add('show');
                    scrollToBottom();
                }, totalDelay);

                totalDelay += 200; // Percepat sedikit animasinya biar lebih enak dilihat
            });

            setTimeout(addCopyButtonsToCodeBlocks, totalDelay + 100);
        }

        function appendMessage(role, text) {
            const div = document.createElement('div');
            div.className = `message ${role}`;
            const bubbleContent = role === 'user' ? text : marked.parse(text);
            const actions = role === 'user' ? '' :
                `<div class="ai-actions"><button class="action-btn" onclick="copyText(this)"><i class="far fa-copy"></i> Salin</button></div>`;
            div.innerHTML =
                `<div class="message-avatar ${role==='user'?'user-avatar-msg':'ai-avatar-msg'}"><i class="fas fa-${role==='user'?'user':'robot'}"></i></div><div class="message-content"><div class="message-bubble ${role==='user'?'':'markdown-body'}">${bubbleContent}</div>${actions}</div>`;
            document.getElementById('messagesContainer').appendChild(div);
        }

        function scrollToBottom() {
            const c = document.getElementById('messagesContainer');
            c.scrollTop = c.scrollHeight;
        }

        // ==========================================
        // FUNGSI MANAJEMEN SESSION (SHARE, RENAME, DELETE)
        // ==========================================
        async function shareSession(id) {
            try {
                // Ambil elemen tombol yang diklik
                const btn = event.currentTarget || event.target.closest('.option-item');
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

                const response = await fetch(`/session/${id}/share`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const data = await response.json();
                if (data.success) {
                    navigator.clipboard.writeText(data.url)
                        .then(() => alert("✅ Berhasil! Link public chat disalin:\n" + data.url))
                        .catch(() => prompt("Copy link ini untuk membagikan chat:", data.url));
                }

                // Kembalikan tombol seperti semula dan tutup menu
                btn.innerHTML = originalHtml;
                document.getElementById(`menu-${id}`).classList.remove('show');
            } catch (e) {
                alert("Gagal membuat link share.");
            }
        }

        async function renameSession(id) {
            const newName = prompt("Masukkan nama baru untuk percakapan ini:");
            if (newName && newName.trim() !== "") {
                try {
                    await fetch(`/session/${id}/rename`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            title: newName
                        })
                    });
                    // Langsung ubah teks di sidebar tanpa perlu refresh
                    document.getElementById(`title-${id}`).innerText = newName;
                    document.getElementById(`menu-${id}`).classList.remove('show');
                } catch (e) {
                    alert("Gagal mengganti nama.");
                }
            }
        }

        async function deleteSession(id) {
            // Konfirmasi penghapusan
            if (confirm("Apakah Anda yakin ingin menghapus obrolan ini secara permanen?")) {
                try {
                    // Tembak API untuk hapus data di database
                    await fetch(`/session/${id}/delete`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    // 1. Hapus dari sidebar (Dengan pengecekan aman agar tidak crash)
                    const sessionDiv = document.getElementById(`session-${id}`);
                    if (sessionDiv) {
                        sessionDiv.remove();
                    }

                    // 2. Redirect jika yang dihapus adalah obrolan yang sedang dibuka
                    // Kita paksa konversi ke String agar tipe datanya 100% cocok
                    if (String(currentSessionId).trim() === String(id).trim()) {
                        // Gunakan Route Laravel langsung agar URL-nya dijamin akurat!
                        window.location.href = "{{ route('chat.new') }}";
                    }
                } catch (e) {
                    alert("Gagal menghapus obrolan. Coba muat ulang halaman.");
                }
            }
        }
        async function renameSession(id) {
            const newName = prompt("Nama baru:");
            if (newName) {
                try {
                    await fetch(`/session/${id}/rename`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            title: newName
                        })
                    });
                    document.getElementById(`title-${id}`).innerText = newName;
                } catch (e) {}
            }
        }
        async function deleteSession(id) {
            if (confirm("Hapus chat ini?")) {
                try {
                    await fetch(`/session/${id}/delete`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });
                    document.getElementById(`session-${id}`).remove();
                    if (currentSessionId == id) window.location.href = "/chat";
                } catch (e) {}
            }
        }

        document.getElementById('sendButton').addEventListener('click', () => sendMessage());
        chatInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        chatInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.message.ai').forEach((el) => {
                const rawDiv = el.querySelector('.ai-raw-data');
                const renderDiv = el.querySelector('.ai-rendered-data');
                if (rawDiv && renderDiv) renderAIContent(rawDiv.textContent.trim(), renderDiv);
            });
            setTimeout(addCopyButtonsToCodeBlocks, 500);

            // Modal Logic
            const modal = document.getElementById('updateModal');
            const closeBtn = document.getElementById('closeModalBtn');
            if (!sessionStorage.getItem('sahajaModalShown')) {
                modal.classList.add('show');
                sessionStorage.setItem('sahajaModalShown', 'true');
            }
            closeBtn.addEventListener('click', () => {
                modal.classList.remove('show');
            });
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.remove('show');
                }
            });
        });

        function renderAIContent(text, containerElement) {
            let rawText = text.replace(/\\\[/g, '$$$$').replace(/\\\]/g, '$$$$').replace(/\\\(/g, '$$').replace(/\\\)/g,
                '$$');
            const mathBlocks = {};
            let mathIndex = 0;
            rawText = rawText.replace(/\$\$([\s\S]*?)\$\$/g, function(match) {
                const placeholder = `@@MATH_BLOCK_${mathIndex}@@`;
                mathBlocks[placeholder] = match;
                mathIndex++;
                return placeholder;
            });
            rawText = rawText.replace(/\$([^$\n]*?)\$/g, function(match) {
                const placeholder = `@@MATH_INLINE_${mathIndex}@@`;
                mathBlocks[placeholder] = match;
                mathIndex++;
                return placeholder;
            });

            let htmlContent = marked.parse(rawText);
            for (const [placeholder, mathText] of Object.entries(mathBlocks)) {
                htmlContent = htmlContent.split(placeholder).join(mathText);
            }
            containerElement.innerHTML = htmlContent;

            if (window.renderMathInElement) {
                renderMathInElement(containerElement, {
                    delimiters: [{
                        left: '$$',
                        right: '$$',
                        display: true
                    }, {
                        left: '$',
                        right: '$',
                        display: false
                    }],
                    throwOnError: false
                });
            }
            containerElement.querySelectorAll('pre code').forEach((block) => {
                if (window.hljs) hljs.highlightElement(block);
            });
        }
    </script>
</body>

</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SAHAJA AI</title>
    <link rel="icon" <link rel="icon" type="image/png" href="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png">

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
            height: 100dvh; /* FIX UNTUK MOBILE BROWSER */
            overflow: hidden;
            display: flex;
            transition: background 0.3s, color 0.3s;
        }

        .main-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            height: 100dvh; /* FIX UNTUK MOBILE BROWSER */
            position: relative;
            transition: all 0.3s ease;
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
            overflow: hidden; /* FIX: Cegah teks mendobrak batas layar */
        }

        .message-bubble {
            padding: 16px 20px;
            border-radius: 16px;
            line-height: 1.6;
            font-size: 0.95rem;
            position: relative;
            word-wrap: break-word; /* FIX: Paksa teks panjang turun ke bawah */
            overflow-wrap: break-word;
            word-break: break-word;
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
            /* overflow-x: auto Dihapus dari sini agar teks tidak meluber */
            word-wrap: break-word;
        }

        .markdown-body>* {
            margin-bottom: 16px;
        }

        .markdown-body p {
            margin-bottom: 16px;
            white-space: normal; /* FIX: Ganti pre-wrap jadi normal agar natural membungkus baris */
            word-wrap: break-word;
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
            overflow-x: auto;
            /* Biar tabel bisa di-scroll ke kanan di HP */
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
            background-color: rgba(37, 99, 235, 0.15);
            /* Biru transparan elegan */
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
            background-color: rgba(255, 255, 255, 0.02);
            /* Efek belang-belang */
        }

        .markdown-body table tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
            /* Efek nyala saat di-hover */
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
            overflow-y: auto; /* FIX: Biar tombol bisa di-scroll di HP kecil */
            min-height: 0;    /* FIX: Mencegah elemen mendorong kotak input ke bawah layar */
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

        /* RESPONSIVE (KHUSUS HP) */
        @media (max-width: 768px) {
            .sidebar { position: fixed; left: 0; top: 0; height: 100%; transform: translateX(-100%); z-index: 99; width: 280px !important; transition: transform 0.3s ease; }
            .sidebar.mobile-open { transform: translateX(0); box-shadow: 10px 0 30px rgba(0, 0, 0, 0.5); }
            .toggle-btn-sidebar { display: none; }
            .mobile-toggle-btn { display: block; background: transparent; border: none; font-size: 1.4rem; color: var(--text-primary); margin-right: 15px; }

            /* KEMBALI KE KODINGAN LAMA YANG SEMPURNA */
            .input-container {
                padding: 4px 12px !important;
                position: relative !important;
            }

            /* PERBAIKAN POP-UP SETTINGS UNTUK HP */
            .settings-modal-box { flex-direction: column; height: 85vh; width: 95%; }
            .settings-sidebar { width: 100%; border-right: none; border-bottom: 1px solid var(--glass-border); flex-direction: row; padding: 10px; overflow-x: auto; white-space: nowrap; flex-shrink: 0; }
            .settings-sidebar h3 { display: none; }
            .nav-btn { padding: 8px 12px; font-size: 0.85rem; }
            .settings-content { padding: 15px; overflow-y: auto; }
            .profile-upload { flex-direction: column; text-align: center; }
        }

        @media (max-width: 375px) {
            .messages-container {
                padding: 15px 3% 130px !important;
            }
        }

        /* JURUS RAHASIA DARI KODINGAN LAMAMU UNTUK GESTURE BAR / NOTCH */
        @supports (padding-bottom: env(safe-area-inset-bottom)) {
            .input-container {
                padding-bottom: max(10px, env(safe-area-inset-bottom)) !important;
            }

            /* Penyesuaian ekstrim padding untuk device dengan notch (iPhone/Android Modern) */
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

        #scrollToBottomBtn {
            position: absolute;
            /* KUNCI RAHASIA: Mengikuti kotak input, bukan layar monitor */
            top: -60px;
            /* Melayang pas di atas kotak input */
            right: 0;
            /* Selalu rata dengan sisi kanan kotak input */

            background-color: var(--accent-color, #3b82f6);
            /* BIRU ICONIC SAHAJA AI */
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            z-index: 100;
            display: none;
            transition: all 0.3s ease;
        }

        #scrollToBottomBtn:hover {
            background-color: #2563eb;
            /* Biru agak gelap saat disentuh mouse */
            transform: translateY(-3px);
        }

        /* 1. DEKLARASI WARNA DEFAULT (DARK MODE) */
        :root {
            --chip-bg: #1e1e2d;
            /* Hitam elegan */
            --chip-border: rgba(255, 255, 255, 0.1);
            /* Garis putih tipis */
            --chip-hover: #2a2a3c;
            --chip-text: #ffffff;
            --chip-shadow: rgba(0, 0, 0, 0.3);
        }

        /* 2. DEKLARASI WARNA LIGHT MODE */
        /* Sesuaikan 'body.light-mode' dengan class Light Mode di kodinganmu (kadang namanya .light-theme atau [data-theme="light"]) */
        body.light-mode {
            --chip-bg: #f3f4f6;
            /* Abu-abu sangat terang/putih */
            --chip-border: rgba(0, 0, 0, 0.1);
            /* Garis hitam tipis */
            --chip-hover: #e5e7eb;
            /* Abu-abu sedikit lebih gelap saat disentuh */
            --chip-text: #1f2937;
            /* Teks hitam/abu gelap */
            --chip-shadow: rgba(0, 0, 0, 0.05);
            /* Bayangan sangat halus */
        }

        /* 3. CSS TOMBOL CHIP YANG SUDAH PINTAR */
        .suggested-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
            margin-top: 35px;
            max-width: 650px;
        }

        .action-chip {
            background-color: var(--chip-bg);
            border: 1px solid var(--chip-border);
            color: var(--chip-text);
            padding: 12px 20px;
            border-radius: 25px;
            font-size: 0.95rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            /* Transisi diperhalus biar pas ganti tema kelihatan smooth */
        }

        .action-chip i {
            font-size: 1.1rem;
        }

        .action-chip:hover {
            background-color: var(--chip-hover);
            transform: translateY(-2px);
            border-color: var(--chip-border);
            box-shadow: 0 4px 12px var(--chip-shadow);
        }
        /* CSS MODAL PENGATURAN (RESPONSIF) */
        .settings-modal-box {
            background: var(--sidebar-bg);
            border: 1px solid var(--glass-border);
            width: 800px;
            max-width: 95%;
            height: 550px;
            max-height: 90vh;
            display: flex;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .settings-sidebar {
            width: 220px;
            background: rgba(10, 14, 23, 0.4);
            padding: 20px 10px;
            border-right: 1px solid var(--glass-border);
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        body.light-mode .settings-sidebar { background: rgba(241, 245, 249, 0.5); }

        .nav-btn {
            padding: 12px 15px;
            text-align: left;
            background: none;
            border-radius: 8px;
            color: var(--text-secondary);
            font-weight: 500;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav-btn:hover { background: var(--glass-highlight); color: var(--text-primary); }
        .nav-btn.active { background: var(--glass-highlight); color: var(--accent-color); }

        .settings-content { padding: 30px; flex: 1; overflow-y: auto; }
        .tab-pane { display: none; animation: fadeIn 0.3s ease; }
        .tab-pane.active { display: block; }

        .theme-btn {
            padding: 15px; border: 1px solid var(--glass-border); border-radius: 12px; flex: 1;
            background: transparent; color: var(--text-primary); font-weight: 600; transition: 0.2s;
        }
        .theme-btn.active { border-color: var(--accent-color); background: rgba(37, 99, 235, 0.1); }

        /* HP / Layar Kecil */
        @media (max-width: 768px) {
            .settings-modal-box { flex-direction: column; }
            .settings-sidebar {
                width: 100%; border-right: none; border-bottom: 1px solid var(--glass-border);
                flex-direction: row; padding: 10px; overflow-x: auto; white-space: nowrap;
            }
            .nav-btn { padding: 8px 12px; font-size: 0.9rem; }
            .settings-content { padding: 20px; }
        }
        /* PERBAIKAN UNTUK MESSAGES TIDAK BOCOR KE KANAN */
    .messages-container {
        overflow-x: hidden !important;
        width: 100% !important;
        word-wrap: break-word;
        word-break: break-word;
    }

    .message {
        max-width: 100% !important;
        width: 100%;
    }

    .message-content {
        max-width: calc(100% - 50px) !important; /* Kurangi ruang avatar */
        min-width: 0;
        overflow-x: auto;
    }

    .message-bubble {
        word-break: break-word;
        overflow-wrap: break-word;
        max-width: 100%;
    }

    /* Konten Markdown (kode, tabel, gambar) tidak boleh bocor */
    .markdown-body pre,
    .markdown-body table,
    .markdown-body img {
        max-width: 100% !important;
        overflow-x: auto !important;
        display: block;
    }

    .markdown-body table {
        display: block;
        white-space: nowrap;
    }
    /* ====================================================== */
        /* PERBAIKAN FINAL: ANTI MELUBER & LAYOUT FLEXBOX MURNI   */
        /* ====================================================== */

        /* 1. MENCEGAH TEKS & LINK PANJANG MELUBER KE KANAN */
        .message-content {
            min-width: 0 !important;
        }
        .message-bubble, .markdown-body, .markdown-body p {
            word-wrap: break-word !important;
            overflow-wrap: anywhere !important; /* Paksa link panjang putus ke bawah */
            word-break: break-word !important;
            max-width: 100% !important;
        }
        .markdown-body pre {
            max-width: 100% !important;
            overflow-x: auto !important; /* Hanya box kodingan yang bisa digeser kiri-kanan */
        }

        /* 2. LAYOUT HP (KEMBALI KE FLEXBOX MURNI, BUKAN FIXED) */
        @media (max-width: 768px) {
            body, html {
                height: 100% !important;
                height: 100dvh !important;
                overflow: hidden !important;
            }

            .main-container {
                height: 100% !important;
                height: 100dvh !important;
                display: flex !important;
                flex-direction: column !important;
                overflow: hidden !important;
            }

            .welcome-screen, .messages-container {
                flex: 1 !important;
                overflow-y: auto !important;
                padding-bottom: 20px !important; /* Hapus padding raksasa yang kemarin */
            }

            .input-container {
                /* KEMBALIKAN KE ALIRAN NORMAL (Bukan Fixed) agar tidak menimpa konten */
                position: relative !important;
                width: 100% !important;
                flex-shrink: 0 !important; /* Kunci agar tidak tergencet/nyungsep */

                /* Turunkan z-index agar Pop-up Announcement & Settings tetap di depan */
                z-index: 20 !important;

                /* Bantalan bawah cerdas untuk garis navigasi HP (Gesture Bar) */
                padding: 10px 15px max(15px, env(safe-area-inset-bottom)) !important;

                background: rgba(10, 14, 23, 0.95) !important;
                backdrop-filter: blur(12px) !important;
                border-top: 1px solid var(--glass-border) !important;
            }

            body.light-mode .input-container {
                background: rgba(255, 255, 255, 0.95) !important;
            }

            .message-content {
                max-width: 95% !important; /* Biar chat di HP lebih lebar dan lega */
            }
        }
    </style>

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
                <p><strong>1.Fitur Baru: SAHAJA Connect</strong><br>
                    Kini tersedia fitur halaman Forum komunitas. dilengkapi fitur <b>Komentar</b>
                </p>
                <p><strong>2.Kustomisasi Profil & Avatar</strong><br>
                    Sekarang Anda bisa mengunggah <b>Foto Profil</b> (maks 2MB).
                </p>
                <p><strong>3.Kontrol Penuh Privasi & Data</strong><br>
                    Kini tersedia tombol untuk <b>Hapus Semua Obrolan</b> dan <b>Hapus Akun secara Permanen</b>. Halaman <i>Syarat Penggunaan</i> dan <i>Kebijakan Privasi</i> juga ditambahkan dan transparan.
                </p>
                <p><strong>4.Bug Fixes & Stabilitas</strong><br>
                    Perbaikan beberapa *bug* fatal dan meningkatkan stabilitas.
                </p>
            </div>
        </div>
    </div>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo-container">
                <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="Logo"
                    style="width: 24px; height: 24px; margin-right: 8px; border-radius: 4px;">
                <span class="brand-text text-label">SAHAJA AI</span>
            </div>
            <button class="toggle-btn-sidebar" id="sidebarToggleBtn"><i class="fas fa-bars"></i></button>
        </div>
        <div class="new-chat-wrapper">
            <a href="{{ route('chat.new') }}" class="new-chat-btn" style="margin-bottom: 10px;">
                <i class="fas fa-plus"></i> <span class="btn-text text-label">Percakapan Baru</span>
            </a>
            <a href="{{ route('online.index') }}" class="new-chat-btn" style="background: transparent; border: 1px solid var(--accent-color); color: var(--text-primary); text-decoration: none; justify-content: center;">
                <i class="fas fa-globe" style="color: var(--accent-color);"></i> <span class="btn-text text-label">SAHAJA Connect</span>
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
            @if(Auth::user()->avatar)
                <img src="{{ Auth::user()->avatar }}" class="user-avatar" style="object-fit: cover;">
            @else
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</div>
            @endif
            <div class="sidebar-footer-details text-label" style="margin-left: 10px;">
                <div style="font-weight: 600; color: var(--text-primary);">{{ Auth::user()->name ?? 'Pengguna' }}</div>
            </div>
        </div>
            <div class="logout-menu" id="logout-menu">
                <div class="option-item" onclick="openSettingsModal()">
                    <i class="fas fa-cog"></i> Pengaturan
                </div>
                <div class="option-item" onclick="window.location.href='#'">
                    <i class="fas fa-question-circle"></i> Bantuan & Umpan Balik
                </div>
                <hr style="border: 0; border-top: 1px solid var(--glass-border); margin: 5px 0;">
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="option-item delete" style="width: 100%;">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="chat-header">
            <div style="display: flex; align-items: center;">
                <button class="mobile-toggle-btn" id="mobileToggleBtn"><i class="fas fa-bars"></i></button>
                <div class="chat-title" style="display: flex; align-items: center;">
                    <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="Logo"
                        style="width: 28px; height: 28px; margin-right: 10px; border-radius: 6px; object-fit: contain;">
                    SAHAJA AI
                </div>
            </div>
            <div class="settings-container">
                <button class="icon-btn" onclick="openSettingsModal()"><i class="fas fa-cog"></i></button>
            </div>
        </div>

        <div class="welcome-screen" id="welcomeScreen" style="{{ count($chats) > 0 ? 'display: none;' : '' }}">
            <div class="welcome-logo" style="background: transparent; box-shadow: none;">
                <img src="https://i.ibb.co.com/wrrG06ds/Logo-SAHAJA-AI.png" alt="Logo SAHAJA AI"
                    style="width: 100px; height: auto; border-radius: 15px;">
            </div>
            <div class="welcome-text">
                <h1 class="welcome-title">SAHAJA AI</h1>
                <p style="color: var(--text-secondary); font-size: 1.1rem;">Halo, {{ Auth::user()->name ?? 'Teman' }}.
                    Apa yang bisa saya bantu?</p>
            </div>

            <div class="suggested-actions">
                <button class="action-chip"
                    onclick="useShortcut('Bantu saya menganalisis dan mencari error dari link GitHub berikut: ')">
                    <i class="fab fa-github" style="color: #a855f7;"></i> <span>Analisis Kode GitHub</span>
                </button>
                <button class="action-chip"
                    onclick="useShortcut('Tolong buatkan contoh kodingan Laravel CRUD sederhana.')">
                    <i class="fas fa-code" style="color: #3b82f6;"></i> <span>Buat Kode Laravel</span>
                </button>
                <button class="action-chip"
                    onclick="useShortcut('Buatkan saya ide judul project akhir website berbasis AI.')">
                    <i class="fas fa-lightbulb" style="color: #eab308;"></i> <span>Ide Project Web</span>
                </button>
                <button class="action-chip"
                    onclick="useShortcut('Jelaskan materi kuliah Sistem Informasi tentang basis data relasional.')">
                    <i class="fas fa-book" style="color: #10b981;"></i> <span>Rangkuman Materi Kuliah</span>
                </button>
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
                    <div class="message-avatar ai-avatar-msg" style="background: transparent; padding: 0;">
                        <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="AI"
                            style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 1px solid var(--border-color);">
                    </div>
                    <div class="message-content">
                        <div class="message-bubble markdown-body ai-raw-data" style="display: none;">
                            {{ $chat->ai_response }}</div>
                        <div class="message-bubble markdown-body ai-rendered-data"></div>
                        <div class="ai-actions">
                            <button class="action-btn" onclick="copyText(this)"><i class="far fa-copy"></i>
                                Salin</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="input-container">
            <div class="input-wrapper" style="position: relative;">

                <button id="scrollToBottomBtn" onclick="scrollToBottomSmooth()" title="Ke pesan terbaru">
                    <i class="fas fa-chevron-down"></i>
                </button>

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
                    <div class="action-left" style="display: flex; gap: 5px;">

                        <div style="position: relative;">
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

                        <div style="position: relative;">
                            <button type="button" class="icon-action-btn" id="modelSelectButton"
                                title="Pilih Model AI">
                                <i class="fas fa-magic" id="currentModelIcon"></i>
                            </button>

                            <div class="attach-menu" id="modelMenu" style="width: 280px;">
                                <div class="option-item model-option"
                                    style="background: var(--glass-highlight); align-items: flex-start;"
                                    onclick="selectModelMode('auto', 'fa-magic')">
                                    <i class="fas fa-magic" style="color: #3b82f6; margin-top: 4px; width: 20px;"></i>
                                    <div style="display: flex; flex-direction: column; gap: 2px;">
                                        <strong style="font-size: 0.95rem; color: var(--text-primary);">Mode
                                            Otomatis</strong>
                                        <span
                                            style="font-size: 0.75rem; color: var(--text-secondary); line-height: 1.3;">Sistem
                                            menentukan berdasarkan prompt anda</span>
                                    </div>
                                </div>
                                <div class="option-item model-option" style="align-items: flex-start;"
                                    onclick="selectModelMode('fast', 'fa-bolt')">
                                    <i class="fas fa-bolt" style="color: #f59e0b; margin-top: 4px; width: 20px;"></i>
                                    <div style="display: flex; flex-direction: column; gap: 2px;">
                                        <strong style="font-size: 0.95rem; color: var(--text-primary);">Mode
                                            Cepat</strong>
                                        <span
                                            style="font-size: 0.75rem; color: var(--text-secondary); line-height: 1.3;">Menjawab
                                            cepat dengan Kimi K2</span>
                                    </div>
                                </div>
                                <div class="option-item model-option" style="align-items: flex-start;"
                                    onclick="selectModelMode('smart', 'fa-brain')">
                                    <i class="fas fa-brain" style="color: #ec4899; margin-top: 4px; width: 20px;"></i>
                                    <div style="display: flex; flex-direction: column; gap: 2px;">
                                        <strong style="font-size: 0.95rem; color: var(--text-primary);">Mode
                                            Cerdas</strong>
                                        <span
                                            style="font-size: 0.75rem; color: var(--text-secondary); line-height: 1.3;">Bernalar
                                            akurat dengan DeepSeek v3.2</span>
                                    </div>
                                </div>
                                <div class="option-item model-option" style="align-items: flex-start;"
                                    onclick="selectModelMode('vision', 'fa-eye')">
                                    <i class="fas fa-eye" style="color: #10b981; margin-top: 4px; width: 20px;"></i>
                                    <div style="display: flex; flex-direction: column; gap: 2px;">
                                        <strong style="font-size: 0.95rem; color: var(--text-primary);">Mode
                                            Vision</strong>
                                        <span
                                            style="font-size: 0.75rem; color: var(--text-secondary); line-height: 1.3;">Menganalisa
                                            gambar dengan Gemma 4</span>
                                    </div>
                                </div>
                                <div class="option-item model-option" style="align-items: flex-start;"
                                    onclick="selectModelMode('coding', 'fa-code')">
                                    <i class="fas fa-code" style="color: #a855f7; margin-top: 4px; width: 20px;"></i>
                                    <div style="display: flex; flex-direction: column; gap: 2px;">
                                        <strong style="font-size: 0.95rem; color: var(--text-primary);">Mode
                                            Coding</strong>
                                        <span
                                            style="font-size: 0.75rem; color: var(--text-secondary); line-height: 1.3;">Konteks
                                            besar dengan Qwen 3 Coder</span>
                                    </div>
                                </div>
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
            <h2 style="font-size: 1.3rem; border: none; margin-bottom: 0;"><i class="fab fa-github"></i> Impor
                Repository (Beta)</h2>
            <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 15px;">SAHAJA AI akan
                menganalisis file kode utama (.php, .js, dll) dari repo public.
                (Fitur masih dalam tahap pengembangan dan tidak sempurna)</p>

            <div class="github-input-group">
                <input type="text" id="githubLinkInput" class="github-input"
                    placeholder="https://github.com/username/repo">
                <button id="submitGithubBtn" class="github-submit-btn">Muat Repository</button>
            </div>
        </div>
    </div>
    <div class="modal-overlay" id="settingsModal" style="z-index: 100000;">
        <div class="settings-modal-box">
            <button class="modal-close" onclick="closeSettingsModal()"><i class="fas fa-times"></i></button>

            <div class="settings-sidebar">
                <h3 style="padding: 10px 10px; font-size: 1.1rem; color: var(--text-primary);">Pengaturan</h3>
                <button class="nav-btn active" onclick="switchTab('umum')"><i class="fas fa-cog"></i> Umum</button>
                <button class="nav-btn" onclick="switchTab('profil')"><i class="fas fa-user"></i> Profil</button>
                <button class="nav-btn" onclick="switchTab('data')"><i class="fas fa-database"></i> Data</button>
                <button class="nav-btn" onclick="switchTab('tentang')"><i class="fas fa-info-circle"></i> Tentang</button>
            </div>

            <div class="settings-content">
                <div id="tab-umum" class="tab-pane active">
                    <h3 style="margin-bottom: 20px;">Umum</h3>
                    <label style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 10px; display: block;">Tema</label>
                    <div style="display: flex; gap: 10px;">
                        <button class="theme-btn" id="btnThemeLight" onclick="setTheme('light')"><i class="fas fa-sun"></i> Terang</button>
                        <button class="theme-btn" id="btnThemeDark" onclick="setTheme('dark')"><i class="fas fa-moon"></i> Gelap</button>
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
                                    <button class="action-btn" onclick="openConfirmModal('Hapus Foto Profil?', 'Foto profil akan dikembalikan ke inisial nama Anda.', 'deleteAvatar')" style="color: var(--danger-color); border: 1px solid var(--danger-color); padding: 5px 10px; border-radius: 8px; font-size: 0.85rem;"><i class="fas fa-trash"></i></button>
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
                    <hr style="border: 0; border-top: 1px solid var(--glass-border); margin: 20px 0;">
                    <button class="option-item delete" style="width: auto; padding: 10px; font-weight: 600; border: 1px solid #ef4444;" onclick="openConfirmModal('Hapus Akun Permanen?', 'Seluruh data akun, foto, dan obrolan akan hilang selamanya.', 'deleteAccount')"><i class="fas fa-trash-alt"></i> Hapus Akun</button>
                </div>

                <div id="tab-data" class="tab-pane">
                    <h3 style="margin-bottom: 20px;">Data</h3>

                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--glass-border); padding-bottom: 15px; margin-bottom: 15px;">
                        <div>
                            <strong style="display: block;">Tautan yang dibagikan</strong>
                            <span style="font-size: 0.8rem; color: var(--text-secondary);">Kelola percakapan yang Anda bagikan.</span>
                        </div>
                        <button class="github-submit-btn" style="background: transparent; color: var(--text-primary); border: 1px solid var(--glass-border);">Kelola</button>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong style="display: block;">Hapus semua obrolan</strong>
                            <span style="font-size: 0.8rem; color: var(--text-secondary);">Tindakan ini tidak dapat dibatalkan.</span>
                        </div>
                        <button class="option-item delete" style="width: auto; padding: 8px 15px; border: 1px solid #ef4444; margin-top:10px;" onclick="openConfirmModal('Hapus Semua Obrolan?',
                        'Seluruh riwayat chat Anda di semua percakapan akan musnah. Ini tidak dapat dibatalkan.', 'clearAllChats')">Hapus semua obrolan</button>
                    </div>
                </div>

                <div id="tab-tentang" class="tab-pane">
                <h3 style="margin-bottom: 20px;">Tentang SAHAJA AI</h3>
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--glass-border); padding-bottom: 10px;">
                        <span>Syarat Penggunaan</span>
                        <button class="github-submit-btn" style="background: transparent; color: var(--text-primary); border: 1px solid var(--glass-border); padding: 5px 15px;" onclick="window.open('{{ route('terms') }}', '_blank')">Lihat</button>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--glass-border); padding-bottom: 10px;">
                        <span>Kebijakan Privasi</span>
                        <button class="github-submit-btn" style="background: transparent; color: var(--text-primary); border: 1px solid var(--glass-border); padding: 5px 15px;" onclick="window.open('{{ route('privacy') }}', '_blank')">Lihat</button>
                    </div>
                    <div style="margin-top: 20px; text-align: center; color: var(--text-secondary); font-size: 0.85rem;">
                        Beta V 3.6<br>
                        Dibuat oleh: Faqih Hidayah
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="modal-overlay" id="shareModal" style="z-index: 100005;">
        <div class="modal-content" style="max-width: 400px; background: var(--sidebar-bg); padding: 25px; border-radius: 12px; border: 1px solid var(--glass-border); text-align: center;">
            <button class="modal-close" onclick="closeCustomModal('shareModal')" style="position: absolute; right: 15px; top: 15px;"><i class="fas fa-times"></i></button>
            <h3 style="margin-bottom: 15px;"><i class="fas fa-share-alt" style="color: var(--accent-color);"></i> Bagikan Percakapan</h3>
            <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 15px;">Salin tautan di bawah ini untuk membagikan percakapan ini ke publik.</p>
            <div style="display: flex; gap: 10px;">
                <input type="text" id="shareLinkInput" class="github-input" readonly style="flex: 1; background: var(--glass-highlight);">
                <button class="github-submit-btn" onclick="copyShareLink()"><i class="far fa-copy"></i> Salin</button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="renameRoomModal" style="z-index: 100005;">
        <div class="modal-content" style="max-width: 400px; background: var(--sidebar-bg); padding: 25px; border-radius: 12px; border: 1px solid var(--glass-border);">
            <button class="modal-close" onclick="closeCustomModal('renameRoomModal')" style="position: absolute; right: 15px; top: 15px;"><i class="fas fa-times"></i></button>
            <h2 style="font-size: 1.2rem; margin-bottom: 15px;"><i class="fas fa-pen" style="color: var(--accent-color);"></i> Ganti Nama</h2>
            <div class="github-input-group" style="display: flex; gap: 10px;">
                <input type="text" id="renameInput" class="github-input" placeholder="Nama percakapan baru...">
                <button id="btnConfirmRename" class="github-submit-btn" onclick="executeRename()">Simpan</button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="confirmDangerModal" style="z-index: 100005;">
        <div class="modal-content" style="max-width: 400px; background: var(--sidebar-bg); padding: 25px; border-radius: 12px; border: 1px solid var(--glass-border); text-align: center;">
            <button class="modal-close" onclick="closeCustomModal('confirmDangerModal')" style="position: absolute; right: 15px; top: 15px;"><i class="fas fa-times"></i></button>
            <div style="font-size: 3rem; color: var(--danger-color); margin-bottom: 10px;"><i class="fas fa-exclamation-triangle"></i></div>
            <h2 id="dangerModalTitle" style="font-size: 1.2rem; margin-bottom: 10px;">Konfirmasi</h2>
            <p id="dangerModalText" style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 20px;">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button class="github-submit-btn" style="background: transparent; border: 1px solid var(--glass-border); color: var(--text-primary);" onclick="closeCustomModal('confirmDangerModal')">Batal</button>
                <button id="btnConfirmDanger" class="github-submit-btn" style="background: var(--danger-color);" onclick="executeDangerAction()">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let currentSessionId = "{{ $currentSession ? $currentSession->id : '' }}";
        let currentController = null;
        let lastUserMessage = "";
        window.activeForceMode = null;

        let extractedFileText = ""; let base64Image = null; let currentFileName = ""; let currentGithubRepo = "";
        let pendingAvatarBase64 = null; let targetActionId = null; let targetActionType = '';

        const chatInput = document.getElementById('chatInput'); const voiceBtn = document.getElementById('voiceButton');
        const attachBtn = document.getElementById('attachButton'); const attachMenu = document.getElementById('attachMenu');
        const docInput = document.getElementById('docInput'); const imageInput = document.getElementById('imageInput');
        const filePreviewContainer = document.getElementById('filePreviewContainer'); const fileNameDisplay = document.getElementById('fileNameDisplay');

        function showToast(message, type = 'info') {
            let container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div'); container.id = 'toast-container';
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
        }

        function closeCustomModal(modalId) { const m = document.getElementById(modalId); if (m) m.classList.remove('show'); }
        function openConfirmModal(title, text, type, id = null) {
            targetActionType = type; targetActionId = id;
            document.getElementById('dangerModalTitle').innerText = title; document.getElementById('dangerModalText').innerText = text;
            document.getElementById('confirmDangerModal').classList.add('show');
            document.querySelectorAll('.options-menu, .logout-menu').forEach(el => el.classList.remove('show'));
        }
        function toggleMenu(e, id) {
            if (e) { e.preventDefault(); e.stopPropagation(); }
            const targetMenu = document.getElementById(id); if (!targetMenu) return;
            const isShown = targetMenu.classList.contains('show');
            document.querySelectorAll('.options-menu, .logout-menu, .attach-menu').forEach(el => el.classList.remove('show'));
            if (!isShown) targetMenu.classList.add('show');
        }

        function renameSession(id) {
            targetActionId = id; document.getElementById('renameInput').value = document.getElementById(`title-${id}`).innerText;
            document.getElementById('renameRoomModal').classList.add('show'); document.getElementById(`menu-${id}`)?.classList.remove('show');
        }
        function deleteSession(id) { openConfirmModal("Hapus Percakapan?", "Percakapan ini akan dihapus secara permanen.", "deleteRoom", id); }
        function clearAllChats() { openConfirmModal("Hapus Semua Obrolan?", "Seluruh riwayat chat Anda akan musnah.", "clearAllChats"); }

        async function shareSession(id) {
            try {
                const response = await fetch(`/session/${id}/share`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken } });
                const data = await response.json();
                if (data.success) { document.getElementById('shareLinkInput').value = data.url; document.getElementById('shareModal').classList.add('show'); }
            } catch(e) { showToast("Gagal membuat link", "error"); }
            document.getElementById(`menu-${id}`)?.classList.remove('show');
        }
        function copyShareLink() { document.getElementById('shareLinkInput').select(); document.execCommand("copy"); showToast("Tautan berhasil disalin!", "success"); closeCustomModal('shareModal'); }

        async function executeRename() {
            const newName = document.getElementById('renameInput').value.trim();
            if(!newName) return showToast("Nama tidak boleh kosong", "error");
            try {
                await fetch(`/session/${targetActionId}/rename`, { method: 'PUT', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ title: newName }) });
                document.getElementById(`title-${targetActionId}`).innerText = newName;
                closeCustomModal('renameRoomModal'); showToast("Nama berhasil diubah", "success");
            } catch(e) { showToast("Gagal mengganti nama", "error"); }
        }

        async function executeDangerAction() {
            closeCustomModal('confirmDangerModal');
            try {
                if (targetActionType === 'deleteRoom') {
                    await fetch(`/session/${targetActionId}/delete`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken } });
                    document.getElementById(`session-${targetActionId}`)?.remove();
                    if (window.location.pathname.includes('/chat/')) { const parts = window.location.pathname.split('/'); if(parts[parts.length-1] == targetActionId) window.location.href = "/chat"; }
                    showToast("Percakapan dihapus", "success");
                } else if (targetActionType === 'clearAllChats') {
                    await fetch('/profile/chat/clear', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken } });
                    showToast("Seluruh riwayat dihapus", "success"); setTimeout(() => window.location.href = '/chat', 1000);
                } else if (targetActionType === 'deleteAccount') {
                    await fetch('/profile/account/delete', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken } });
                    window.location.href = '/';
                } else if (targetActionType === 'deleteAvatar') {
                    await fetch('/profile/update', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ avatar: null }) });
                    showToast("Foto profil dihapus", "success"); setTimeout(() => window.location.reload(), 1000);
                }
            } catch(e) { showToast("Kesalahan server", "error"); }
        }

        function openSettingsModal() { document.getElementById('settingsModal').classList.add('show'); document.getElementById('logout-menu')?.classList.remove('show'); }
        function closeSettingsModal() { document.getElementById('settingsModal').classList.remove('show'); }
        function switchTab(tabId) {
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.nav-btn').forEach(el => el.classList.remove('active'));
            document.getElementById('tab-' + tabId).classList.add('active'); event.currentTarget.classList.add('active');
        }
        function setTheme(mode) {
            const isLight = mode === 'light'; document.body.classList.toggle('light-mode', isLight); localStorage.setItem('theme', isLight ? 'light' : 'dark');
            document.getElementById('btnThemeLight').classList.toggle('active', isLight); document.getElementById('btnThemeDark').classList.toggle('active', !isLight);
        }

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

        async function simpanProfil() {
            const newName = document.getElementById('inputNamaProfil').value.trim();
            if(!newName) return showToast("Nama tidak boleh kosong!", "error");
            const payload = { name: newName }; if (pendingAvatarBase64 !== null) payload.avatar = pendingAvatarBase64;
            try {
                const res = await fetch('/profile/update', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify(payload) });
                const data = await res.json();
                if(data.success) { showToast("Profil diperbarui!", "success"); setTimeout(() => window.location.reload(), 1000); }
            } catch(e) { showToast("Gagal menyimpan profil", "error"); }
        }

        document.getElementById('btnUploadDoc')?.addEventListener('click', () => { docInput.click(); attachMenu.classList.remove('show'); });
        document.getElementById('btnUploadImage')?.addEventListener('click', () => { imageInput.click(); attachMenu.classList.remove('show'); });

        const githubModal = document.getElementById('githubModal');
        document.getElementById('btnUploadGithub')?.addEventListener('click', () => { attachMenu.classList.remove('show'); githubModal.classList.add('show'); document.getElementById('githubLinkInput').focus(); });
        document.getElementById('closeGithubModalBtn')?.addEventListener('click', () => { githubModal.classList.remove('show'); });
        document.getElementById('submitGithubBtn')?.addEventListener('click', () => {
            const link = document.getElementById('githubLinkInput').value.trim();
            if (link.includes('github.com')) {
                removeFile();
                const urlParts = link.split('github.com/');
                if (urlParts.length > 1) {
                    let repoName = urlParts[1].replace('.git', '').split('/').slice(0, 2).join('/');
                    currentGithubRepo = link; currentFileName = repoName;
                    filePreviewContainer.style.display = 'flex'; filePreviewContainer.querySelector('i').className = 'fab fa-github'; filePreviewContainer.querySelector('i').style.color = '#a855f7';
                    fileNameDisplay.textContent = "Repo: " + repoName; githubModal.classList.remove('show');
                }
            } else alert('Link GitHub tidak valid!');
        });

        imageInput?.addEventListener('change', (e) => {
            const file = e.target.files[0]; if (!file) return; removeFile();
            currentFileName = file.name; filePreviewContainer.style.display = 'flex'; filePreviewContainer.querySelector('i').className = 'fas fa-image'; filePreviewContainer.querySelector('i').style.color = '#4ade80';
            fileNameDisplay.textContent = "Mengompresi..."; attachBtn.style.display = 'none';
            const reader = new FileReader(); reader.readAsDataURL(file);
            reader.onload = event => {
                const img = new Image(); img.src = event.target.result;
                img.onload = () => {
                    const canvas = document.createElement('canvas'); const MAX = 1600; let w = img.width; let h = img.height;
                    if (w > h && w > MAX) { h *= MAX / w; w = MAX; } else if (h > MAX) { w *= MAX / h; h = MAX; }
                    canvas.width = w; canvas.height = h; canvas.getContext('2d').drawImage(img, 0, 0, w, h);
                    base64Image = canvas.toDataURL('image/jpeg', 0.9);
                    fileNameDisplay.textContent = currentFileName + " (Siap)"; attachBtn.style.display = 'flex'; imageInput.value = '';
                }
            };
        });

        docInput?.addEventListener('change', async (e) => {
            const file = e.target.files[0]; if (!file) return; removeFile();
            currentFileName = file.name; filePreviewContainer.style.display = 'flex'; filePreviewContainer.querySelector('i').className = 'fas fa-file-alt'; filePreviewContainer.querySelector('i').style.color = 'var(--accent-color)';
            fileNameDisplay.textContent = "Mengekstrak..."; attachBtn.style.display = 'none';
            try {
                if (file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf')) extractedFileText = await extractPdfText(file);
                else if (file.name.toLowerCase().endsWith('.docx') || file.type.includes('wordprocessingml')) extractedFileText = await extractDocxText(file);
                else { alert("Format ditolak!"); removeFile(); return; }
                if (extractedFileText.length > 25000) extractedFileText = extractedFileText.substring(0, 25000) + "\n\n[INFO: TEKS DIPOTONG]";
                fileNameDisplay.textContent = currentFileName;
            } catch (err) { alert("Gagal membaca dokumen."); removeFile(); } finally { attachBtn.style.display = 'flex'; docInput.value = ''; }
        });

        function removeFile() { extractedFileText = ""; base64Image = null; currentFileName = ""; currentGithubRepo = ""; filePreviewContainer.style.display = 'none'; docInput.value = ''; imageInput.value = ''; }

        async function extractPdfText(file) {
            const arrayBuffer = await file.arrayBuffer(); const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
            let text = ""; const maxPages = Math.min(pdf.numPages, 25);
            for (let i = 1; i <= maxPages; i++) { const page = await pdf.getPage(i); const content = await page.getTextContent(); text += content.items.map(item => item.str).join(" ") + "\n"; }
            return text;
        }

        async function extractDocxText(file) {
            const arrayBuffer = await file.arrayBuffer(); const result = await mammoth.extractRawText({ arrayBuffer: arrayBuffer });
            return result.value;
        }

        let userSelectedMode = 'auto';
        document.getElementById('modelSelectButton')?.addEventListener('click', (e) => { e.stopPropagation(); attachMenu.classList.remove('show'); document.getElementById('modelMenu').classList.toggle('show'); });
        attachBtn?.addEventListener('click', (e) => { e.stopPropagation(); document.getElementById('modelMenu').classList.remove('show'); attachMenu.classList.toggle('show'); });
        function selectModelMode(mode, iconClass) { userSelectedMode = mode; document.getElementById('currentModelIcon').className = `fas ${iconClass}`; document.querySelectorAll('.model-option').forEach(el => el.style.background = 'transparent'); event.currentTarget.style.background = 'var(--glass-highlight)'; document.getElementById('modelMenu').classList.remove('show'); }
        function switchToMode(targetMode) { window.activeForceMode = targetMode; if (currentController) currentController.abort(); const oldLoading = document.querySelector('.message.ai:last-child'); if (oldLoading && oldLoading.querySelector('.typing-indicator')) oldLoading.remove(); sendMessage(); }
        function switchToFastMode() { switchToMode('fast'); }
        function detectComplexity(text) { const t = text.toLowerCase(); const complex = ['coding', 'buatkan', 'analisis', 'html', 'laravel', 'script', 'error', 'database']; const simple = ['halo', 'hai', 'tes', 'ngoding', 'cerita']; if (complex.some(k => t.includes(k))) return true; if (t.split(' ').length < 10 && simple.some(k => t.includes(k))) return false; return t.split(' ').length > 15; }

        async function sendMessage() {
            if (typeof isRecording !== 'undefined' && isRecording && recognition) { recognition.stop(); forceStopRecordingUI(); }
            const messageInput = chatInput.value.trim(); let finalMessageToSend; let displayMessage = messageInput;
            if (window.activeForceMode !== null) { if (!lastUserMessage) return; finalMessageToSend = lastUserMessage; } else {
                if (!messageInput && !extractedFileText && !base64Image && !currentGithubRepo) return;
                if (extractedFileText) { finalMessageToSend = `[Lampiran Dokumen: ${currentFileName}]\n"""\n${extractedFileText}\n"""\n\nInstruksi User: ${messageInput || "Tolong analisis"}`; displayMessage = `<i class="fas fa-file-pdf" style="color: #3b82f6; margin-right: 5px;"></i> <b>[Dokumen: ${currentFileName}]</b>\n${messageInput}`; }
                else if (base64Image) { finalMessageToSend = messageInput || "Jelaskan gambar ini."; displayMessage = `<i class="fas fa-image" style="color: #10b981; margin-right: 5px;"></i> <b>[Gambar: ${currentFileName}]</b>\n${messageInput}`; }
                else if (currentGithubRepo) { finalMessageToSend = messageInput || "Analisis kode ini."; displayMessage = `<i class="fab fa-github" style="color: #a855f7; margin-right: 5px;"></i> <b>[GitHub: ${currentFileName}]</b>\n${messageInput}`; }
                else { finalMessageToSend = messageInput; }
                lastUserMessage = finalMessageToSend;
            }

            if (window.activeForceMode === null) {
                const welcome = document.getElementById('welcomeScreen'); if (welcome) welcome.style.display = 'none';
                const msgContainer = document.getElementById('messagesContainer'); if (msgContainer) msgContainer.style.display = 'flex';
                chatInput.value = ''; chatInput.style.height = 'auto'; appendMessage('user', displayMessage);
            }

            const payload = { message: finalMessageToSend, session_id: currentSessionId, manual_mode: userSelectedMode };
            if (base64Image) payload.image_data = base64Image; if (currentGithubRepo) payload.github_repo = currentGithubRepo; if (window.activeForceMode !== null) payload.force_mode = window.activeForceMode;
            let mode = 'fast'; if (window.activeForceMode !== null) mode = window.activeForceMode; else if (userSelectedMode !== 'auto') mode = userSelectedMode; else { let isComplex = detectComplexity(finalMessageToSend); if (extractedFileText) isComplex = true; mode = isComplex ? 'smart' : 'fast'; if (base64Image) mode = 'vision'; if (currentGithubRepo) mode = 'github'; }

            const loadingId = appendLoadingWithMode(mode); scrollToBottom();
            if (window.activeForceMode === null) removeFile();
            if (currentController) currentController.abort(); currentController = new AbortController();

            try {
                const response = await fetch("{{ route('chat.send') }}", { method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" }, body: JSON.stringify(payload), signal: currentController.signal });
                if (!response.ok) throw new Error(`Server Error: ${response.status}`);
                const data = await response.json(); if (data.error) throw new Error(data.message);

                const loadingBubble = document.getElementById(loadingId);
                if (loadingBubble) {
                    const aiMessageDiv = document.createElement('div'); aiMessageDiv.className = 'message ai';
                    let finalModelLabel = '<i class="fas fa-bolt"></i> Mode Cepat (Kimi K2)'; let finalBadgeClass = 'mode-fast'; let extraStyle = ''; const modelUsedStr = (data.model_used || '').toLowerCase();
                    if (modelUsedStr.includes('vision') || modelUsedStr.includes('gemma')) { finalModelLabel = '<i class="fas fa-eye"></i> Mode Vision'; extraStyle = 'background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);'; finalBadgeClass = ''; }
                    else if (modelUsedStr.includes('deepseek')) { finalModelLabel = '<i class="fas fa-brain"></i> Mode Cerdas'; finalBadgeClass = 'mode-smart'; }
                    else if (modelUsedStr.includes('coder') || modelUsedStr.includes('qwen')) { finalModelLabel = '<i class="fas fa-code"></i> Mode Code'; extraStyle = 'background: rgba(168, 85, 247, 0.15); color: #a855f7; border: 1px solid rgba(168, 85, 247, 0.3);'; finalBadgeClass = ''; }

                    aiMessageDiv.innerHTML = `<div class="message-avatar ai-avatar-msg" style="background: transparent; padding: 0;"><img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;"></div><div class="message-content"><div class="mode-badge ${finalBadgeClass}" style="${extraStyle}">${finalModelLabel}</div><div class="message-bubble markdown-body"></div><div class="ai-actions"><button class="action-btn" onclick="copyText(this)"><i class="far fa-copy"></i> Salin</button></div></div>`;
                    loadingBubble.parentNode.replaceChild(aiMessageDiv, loadingBubble);
                    const bubble = aiMessageDiv.querySelector('.message-bubble'); if (bubble) animateGeminiStyle(bubble, data.ai_response); scrollToBottom();
                }
                if (!currentSessionId && data.session_id) { window.history.pushState({}, '', `/chat/${data.session_id}`); currentSessionId = data.session_id; }
                window.activeForceMode = null;
            } catch (error) {
                const lBubble = document.getElementById(loadingId); if (lBubble) lBubble.remove();
                if (error.name !== 'AbortError') showToast("Gagal: " + error.message, "error");
                window.activeForceMode = null;
            }
        }

        function appendMessage(sender, text) {
            const messageDiv = document.createElement('div'); messageDiv.classList.add('message', sender);
            let safeText = text; if (sender === 'user') safeText = text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
            const avatarHtml = sender === 'user' ? `<div class="message-avatar"><img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=2563eb&color=fff' }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;"></div>` : `<div class="message-avatar ai-avatar-msg"><i class="fas fa-robot"></i></div>`;
            messageDiv.innerHTML = `${avatarHtml}<div class="message-content"><div class="message-bubble">${safeText}</div></div>`;
            document.getElementById('messagesContainer').appendChild(messageDiv); scrollToBottom();
        }

        function appendLoadingWithMode(mode) {
            const id = 'loading-' + Date.now(); const div = document.createElement('div'); div.id = id; div.className = 'message ai';
            let badgeHtml = ''; let textHtml = '';
            if (mode === 'vision') { badgeHtml = `<div class="mode-badge" style="background: rgba(16, 185, 129, 0.15); color: #10b981;"><i class="fas fa-eye"></i> Mode Vision</div>`; textHtml = `<span class="typing-text">Menganalisis...</span>`; }
            else if (mode === 'github' || mode === 'coding') { badgeHtml = `<div class="mode-badge" style="background: rgba(168, 85, 247, 0.15); color: #a855f7;"><i class="fas fa-code"></i> Mode Code</div>`; textHtml = `<span class="typing-text">Menganalisis...</span>`; }
            else if (mode === 'smart') { badgeHtml = `<div class="mode-badge mode-smart"><i class="fas fa-brain"></i> Mode Cerdas</div>`; textHtml = `<span class="typing-text">Bernalar... <button class="switch-btn" onclick="switchToFastMode()">[Beralih ke Cepat]</button></span>`; }
            else { badgeHtml = `<div class="mode-badge mode-fast"><i class="fas fa-bolt"></i> Mode Cepat</div>`; textHtml = `<span class="typing-text">Berpikir... <button class="switch-btn" style="color:#d4a017;" onclick="switchToMode('smart')">[Beralih ke Cerdas]</button></span>`; }
            div.innerHTML = `<div class="message-avatar ai-avatar-msg"><i class="fas fa-robot"></i></div><div class="message-content">${badgeHtml}<div class="message-bubble"><div class="typing-indicator"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div>${textHtml}</div></div></div>`;
            document.getElementById('messagesContainer').appendChild(div); return id;
        }

        // ==========================================
        // 7. UTILITIES (Copy, Markdown, Event Listeners)
        // ==========================================
        function copyText(btn) { try { const messageContent = btn.closest('.message-content'); if (!messageContent) return; let textElement = messageContent.querySelector('.markdown-body') || messageContent.querySelector('.message-bubble'); if (!textElement) return; const textToCopy = textElement.innerText || textElement.textContent; const originalHTML = btn.innerHTML; const showSuccess = () => { btn.innerHTML = '<i class="fas fa-check"></i> Disalin'; btn.style.color = '#4ade80'; setTimeout(() => { btn.innerHTML = originalHTML; btn.style.color = ''; }, 2000); }; if (navigator.clipboard && window.isSecureContext) navigator.clipboard.writeText(textToCopy).then(showSuccess).catch(() => fallbackCopyText(textToCopy, showSuccess)); else fallbackCopyText(textToCopy, showSuccess); } catch (err) { showToast('Gagal menyalin teks.', 'error'); } }
        function copyCode(button, codeElement) { if (!codeElement) return; const textToCopy = codeElement.textContent || codeElement.innerText; const showSuccess = () => { button.innerHTML = '<i class="fas fa-check"></i> Disalin'; button.style.background = 'rgba(74, 222, 128, 0.9)'; button.style.color = 'white'; setTimeout(() => { button.innerHTML = '<i class="far fa-copy"></i> Salin'; button.style.background = ''; button.style.color = ''; }, 2000); }; if (navigator.clipboard && window.isSecureContext) navigator.clipboard.writeText(textToCopy).then(showSuccess).catch(() => fallbackCopyText(textToCopy, showSuccess)); else fallbackCopyText(textToCopy, showSuccess); }
        function fallbackCopyText(text, callback) { const textArea = document.createElement('textarea'); textArea.value = text; textArea.style.position = 'fixed'; textArea.style.left = '-9999px'; document.body.appendChild(textArea); textArea.focus(); textArea.select(); try { if (document.execCommand('copy') && callback) callback(); else showToast('Gagal menyalin', 'error'); } catch (err) {} document.body.removeChild(textArea); }
        function addCopyButtonsToCodeBlocks() { document.querySelectorAll('.markdown-body pre').forEach((pre) => { if (pre.previousElementSibling?.classList.contains('code-header')) return; const code = pre.querySelector('code'); if (!code) return; let language = 'plaintext'; const langClass = code.className.match(/language-(\w+)/); if (langClass) language = langClass[1]; const header = document.createElement('div'); header.className = 'code-header'; header.innerHTML = `<span class="code-lang">${language}</span><button class="code-copy-btn" aria-label="Salin kode"><i class="far fa-copy"></i> Salin</button>`; pre.parentNode.insertBefore(header, pre); pre.style.borderRadius = '0 0 8px 8px'; pre.style.marginTop = '0'; const copyBtn = header.querySelector('.code-copy-btn'); copyBtn.addEventListener('click', (e) => { e.preventDefault(); e.stopPropagation(); copyCode(copyBtn, code); }); }); }
        function animateGeminiStyle(element, markdownText) { const tempDiv = document.createElement('div'); renderAIContent(markdownText, tempDiv); element.innerHTML = ''; Array.from(tempDiv.children).forEach((child) => { const wrapper = document.createElement('div'); wrapper.className = 'gemini-block'; wrapper.appendChild(child); element.appendChild(wrapper); }); let delay = 0; element.querySelectorAll('.gemini-block').forEach((block) => { setTimeout(() => { block.classList.add('show'); scrollToBottom(); }, delay); delay += 120; }); setTimeout(addCopyButtonsToCodeBlocks, delay + 100); }
        function renderAIContent(text, containerElement) { let rawText = text.replace(/\\\[/g, '$$$$').replace(/\\\]/g, '$$$$').replace(/\\\(/g, '$$').replace(/\\\)/g, '$$'); const mathBlocks = {}; let mathIndex = 0; rawText = rawText.replace(/\$\$([\s\S]*?)\$\$/g, function(match) { const placeholder = `@@MATH_BLOCK_${mathIndex}@@`; mathBlocks[placeholder] = match; mathIndex++; return placeholder; }); rawText = rawText.replace(/\$([^$\n]*?)\$/g, function(match) { const placeholder = `@@MATH_INLINE_${mathIndex}@@`; mathBlocks[placeholder] = match; mathIndex++; return placeholder; }); let htmlContent = marked.parse(rawText); for (const [placeholder, mathText] of Object.entries(mathBlocks)) htmlContent = htmlContent.split(placeholder).join(mathText); containerElement.innerHTML = htmlContent; if (window.renderMathInElement) window.renderMathInElement(containerElement, { delimiters: [{ left: '$$', right: '$$', display: true }, { left: '$', right: '$', display: false }], throwOnError: false }); containerElement.querySelectorAll('pre code').forEach((block) => { if (window.hljs) hljs.highlightElement(block); }); }
        function scrollToBottom() { const c = document.getElementById('messagesContainer'); if(c) c.scrollTop = c.scrollHeight; }
        function scrollToBottomSmooth() { const c = document.getElementById('messagesContainer'); if(c) c.scrollTo({ top: c.scrollHeight, behavior: 'smooth' }); }
        function formatAttachmentIcons() { document.querySelectorAll('.message.user .message-bubble').forEach(el => { let html = el.innerHTML; html = html.replace(/📎 \[(.*?)\]/g, '<i class="fas fa-file-pdf" style="color: #3b82f6; margin-right: 5px;"></i> <b>[Dokumen: $1]</b>'); html = html.replace(/🖼️ \[(.*?)\]/g, '<i class="fas fa-image" style="color: #10b981; margin-right: 5px;"></i> <b>[$1]</b>'); html = html.replace(/📦 \[GitHub: (.*?)\]/g, '<i class="fab fa-github" style="color: #a855f7; margin-right: 5px;"></i> <b>[GitHub: $1]</b>'); el.innerHTML = html; }); }
        function useShortcut(text) { chatInput.value = text; chatInput.focus(); }

        document.getElementById('sendButton')?.addEventListener('click', () => sendMessage());
        chatInput?.addEventListener('input', function() { this.style.height = 'auto'; this.style.height = (this.scrollHeight) + 'px'; });
        chatInput?.addEventListener('keydown', (e) => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); } });
        document.getElementById('sidebarToggleBtn')?.addEventListener('click', e => { e.stopPropagation(); document.getElementById('sidebar').classList.toggle('collapsed'); });
        document.getElementById('mobileToggleBtn')?.addEventListener('click', e => { e.stopPropagation(); document.getElementById('sidebar').classList.toggle('mobile-open'); });
        window.addEventListener('click', e => {
            if (window.innerWidth <= 768 && !document.getElementById('sidebar').contains(e.target) && !e.target.closest('.mobile-toggle-btn')) document.getElementById('sidebar').classList.remove('mobile-open');
            if (!e.target.closest('.settings-container')) document.querySelectorAll('.options-menu, .logout-menu, .attach-menu').forEach(el => el.classList.remove('show'));
        });

        const chatContainerBox = document.getElementById('messagesContainer');
        if (chatContainerBox) { chatContainerBox.addEventListener('scroll', () => { if (chatContainerBox.scrollTop + chatContainerBox.clientHeight < chatContainerBox.scrollHeight - 150) document.getElementById('scrollToBottomBtn').style.display = 'block'; else document.getElementById('scrollToBottomBtn').style.display = 'none'; }); }

        document.addEventListener('DOMContentLoaded', () => {
            formatAttachmentIcons();
            document.querySelectorAll('.message.ai').forEach((el) => { const rawDiv = el.querySelector('.ai-raw-data'); const renderDiv = el.querySelector('.ai-rendered-data'); if (rawDiv && renderDiv) renderAIContent(rawDiv.textContent.trim(), renderDiv); });
            setTimeout(addCopyButtonsToCodeBlocks, 500);
            const chatCount = {{ count($chats ?? []) }}; const updateModal = document.getElementById('updateModal');
            if (chatCount === 0 && updateModal && !sessionStorage.getItem('sahajaModalShown')) { setTimeout(() => { updateModal.classList.add('show'); sessionStorage.setItem('sahajaModalShown', 'true'); }, 500); }
            document.getElementById('closeModalBtn')?.addEventListener('click', () => updateModal?.classList.remove('show'));
        });

        // ==========================================
        // 8. VOICE RECORDING
        // ==========================================
        let recognition = null; let isRecording = false; let final_transcript = '';
        if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRecognition(); recognition.lang = 'id-ID'; recognition.interimResults = true; recognition.continuous = false;
            recognition.onstart = function() { isRecording = true; final_transcript = ''; voiceBtn.classList.add('recording'); voiceBtn.innerHTML = '<i class="fas fa-stop"></i>'; chatInput.placeholder = "Mendengarkan..."; };
            recognition.onresult = function(event) { let interim_transcript = ''; for (let i = event.resultIndex; i < event.results.length; ++i) { if (event.results[i].isFinal) final_transcript += event.results[i][0].transcript; else interim_transcript += event.results[i][0].transcript; } const prefix = window.preRecordInput ? window.preRecordInput + ' ' : ''; chatInput.value = prefix + final_transcript + interim_transcript; chatInput.dispatchEvent(new Event('input')); };
            recognition.onerror = function() { forceStopRecordingUI(); }; recognition.onend = function() { forceStopRecordingUI(); };
        } else { if(voiceBtn) voiceBtn.style.display = 'none'; }

        function forceStopRecordingUI() { isRecording = false; voiceBtn.classList.remove('recording'); voiceBtn.innerHTML = '<i class="fas fa-microphone"></i>'; chatInput.placeholder = "Ketik pesan..."; }
        voiceBtn?.addEventListener('click', () => { if (!recognition) return showToast("Browser tidak support Voice", "error"); if (isRecording) { recognition.stop(); forceStopRecordingUI(); } else { window.preRecordInput = chatInput.value.trim(); try { recognition.start(); } catch (e) {} } });
    </script>
</body>
</html>

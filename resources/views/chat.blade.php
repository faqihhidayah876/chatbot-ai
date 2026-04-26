<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SAHAJA AI</title>
    <link rel="icon" <link rel="icon" type="image/png" href="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html-docx-js@0.3.1/dist/html-docx.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/mermaid@10.8.0/dist/mermaid.min.js"></script>

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

        /* JURUS GEMBOK LAYAR MUTLAK ANTI MELUBER */
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100% !important;
        }
        .main-container, .messages-container {
            overflow-x: hidden !important;
            max-width: 100vw !important;
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
            /* TAMBAHAN: Memaksa kode tidak meluber */
            white-space: pre-wrap !important;
            word-wrap: break-word !important;
            word-break: break-all !important;
        }

        .markdown-body pre code {
            font-family: 'Roboto Mono', monospace;
            font-size: 0.9em;
            /* TAMBAHAN: Pastikan kode di dalam pre juga wrap */
            white-space: pre-wrap !important;
            word-wrap: break-word !important;
            word-break: break-all !important;
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
        /* ================================================= */
        /* TOMBOL SILANG (X) DI LUAR POP-UP SETTINGS         */
        /* ================================================= */
        .modal-close-outside {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.6);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.3rem;
            cursor: pointer;
            z-index: 100001; /* Pastikan di atas overlay */
            transition: 0.2s;
            backdrop-filter: blur(4px);
        }

        .modal-close-outside:hover {
            background: var(--danger-color);
            border-color: var(--danger-color);
            transform: scale(1.1);
        }

        /* Penyesuaian khusus untuk HP */
        @media (max-width: 768px) {
            .modal-close-outside {
                top: 15px;
                right: 15px;
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
            }
            .settings-modal-box {
                margin-top: 50px; /* Biar box agak turun menjauhi tombol X */
            }
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

        /* CSS UNTUK TOGGLE SWITCH (ENABLE THINKING) */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
            flex-shrink: 0;
        }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-slider {
            position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
            background-color: var(--glass-border); transition: .4s; border-radius: 24px;
        }
        .toggle-slider:before {
            position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px;
            background-color: var(--text-secondary); transition: .4s; border-radius: 50%;
        }
        .toggle-switch input:checked + .toggle-slider { background-color: var(--accent-color); }
        .toggle-switch input:checked + .toggle-slider:before {
            transform: translateX(20px); background-color: white;
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
        /* ===== THINKING MODE UI ===== */
        .thinking-container {
            margin-bottom: 15px; border: 1px solid var(--glass-border);
            border-radius: 8px; overflow: hidden; background: rgba(0, 0, 0, 0.15);
        }
        body.light-mode .thinking-container { background: rgba(241, 245, 249, 0.7); }

        .thinking-header {
            padding: 10px 12px; cursor: pointer; display: flex; align-items: center;
            gap: 8px; font-size: 0.85rem; color: var(--text-secondary);
            background: rgba(255, 255, 255, 0.05); user-select: none; transition: 0.2s;
        }
        .thinking-header:hover { background: rgba(255, 255, 255, 0.1); color: var(--text-primary); }
        body.light-mode .thinking-header { background: rgba(0, 0, 0, 0.03); }
        body.light-mode .thinking-header:hover { background: rgba(0, 0, 0, 0.08); color: #1e293b; }

        .thinking-content {
            display: none; padding: 12px; font-size: 0.85rem; color: var(--text-secondary);
            border-top: 1px solid var(--glass-border); white-space: pre-wrap;
            font-style: italic; line-height: 1.6;
        }
        .thinking-content.show { display: block; animation: fadeIn 0.3s ease; }

        /* ===== MERMAID DIAGRAM UI ===== */
        .mermaid-wrapper { border: 1px solid var(--glass-border); border-radius: 8px; margin: 15px 0; overflow: hidden; }
        .mermaid-header { display: flex; justify-content: space-between; background: rgba(255,255,255,0.05); padding: 8px 15px; align-items: center; border-bottom: 1px solid var(--glass-border); }
        .mermaid-tabs { display: flex; gap: 10px; }
        .mermaid-tab { background: transparent; color: var(--text-secondary); border: none; cursor: pointer; padding: 5px 10px; font-size: 0.85rem; border-radius: 4px; transition: 0.3s; }
        .mermaid-tab.active { background: var(--accent-color); color: white; }
        .mermaid-download { background: transparent; color: var(--text-primary); border: 1px solid var(--glass-border); padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 0.85rem; }
        .mermaid-download:hover { background: rgba(255,255,255,0.1); }
        .mermaid-content { background: rgba(0,0,0,0.2); padding: 15px; overflow-x: auto; text-align: center; }
        .mermaid-code { display: none; background: #1e1e1e; padding: 15px; text-align: left; overflow-x: auto; }

        /* ===== ONBOARDING SLIDER ANIMATION ===== */
        @keyframes rotateGlow {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .slide-in-right {
            animation: slideInRight 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) forwards;
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .onboard-dots {
            display: flex; gap: 6px; align-items: center;
        }
        .dot {
            width: 8px; height: 8px; border-radius: 50%; background: var(--text-secondary); opacity: 0.3; transition: 0.3s;
        }
        .dot.active {
            width: 24px; border-radius: 10px; background: var(--accent-color); opacity: 1;
        }
        /* ===== ANIMASI BACKGROUND AURORA & ORBS ===== */
        @keyframes gradientAurora {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes floatOrb {
            0% { transform: translateY(0px) scale(1); opacity: 0.5; }
            50% { transform: translateY(-25px) scale(1.2); opacity: 0.9; }
            100% { transform: translateY(0px) scale(1); opacity: 0.5; }
        }
        /* ===== ANIMASI MEWAH: FADE IN & POP ===== */
        @keyframes logoPopIn {
            0% { opacity: 0; transform: scale(0.5); }
            70% { transform: scale(1.05); }
            100% { opacity: 1; transform: scale(1); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-logo {
            animation: logoPopIn 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            opacity: 0;
        }
        .animate-title {
            animation: fadeInUp 0.8s ease-out forwards;
            animation-delay: 0.3s;
            opacity: 0;
        }
        .animate-desc {
            animation: fadeInUp 0.8s ease-out forwards;
            animation-delay: 0.6s;
            opacity: 0;
        }
        .animate-footer {
            animation: fadeInUp 0.8s ease-out forwards;
            animation-delay: 0.9s;
            opacity: 0;
        }
        /* ===== ANIMASI BENTO BOX (SLIDE 2) ===== */
        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 16px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            margin-bottom: 12px;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            opacity: 0; /* Disembunyikan untuk animasi awal */
        }

        .feature-item:hover {
            transform: translateY(-4px) scale(1.02);
            background: rgba(37, 99, 235, 0.08);
            border-color: rgba(37, 99, 235, 0.3);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        body.light-mode .feature-item {
            background: #f8fafc;
        }

        body.light-mode .feature-item:hover {
            background: #eff6ff;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.1);
        }

        .feature-icon-wrapper {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        /* ===== GEMINI STYLE WELCOME SCREEN (REFINED) ===== */
        .welcome-screen {
            padding-top: 40px !important; /* Paksa logo agak turun */
        }

        .welcome-logo-container {
            position: relative; margin-bottom: 20px;
        }

        .welcome-logo-glow {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: 80px; height: 80px; background: rgba(37, 99, 235, 0.4);
            filter: blur(30px); border-radius: 50%; z-index: 1;
        }

        .welcome-logo-img {
            width: 65px; position: relative; z-index: 2; border-radius: 16px; /* Diperkecil */
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        }

        @keyframes floatLogo {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .welcome-greeting {
            font-size: 2.4rem; font-weight: 600; margin-bottom: 5px;
            background: linear-gradient(135deg, #4285f4, #d96570, #9b72cb, #06b6d4);
            background-size: 300% 300%;
            animation: gradientAurora 8s ease infinite;
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            letter-spacing: -1px; text-align: center; line-height: 1.2;
        }

        .welcome-subtext {
            font-size: 1.2rem; color: var(--text-secondary); margin-bottom: 35px; font-weight: 500; text-align: center;
        }

        /* Grid Cards Desktop (Lebih Kompak & Rapi) */
        .suggested-actions-grid {
            display: grid; grid-template-columns: repeat(2, 1fr);
            gap: 12px; max-width: 650px; width: 100%; margin: 0 auto;
        }

        .action-card {
            background: rgba(30, 41, 59, 0.4); border: 1px solid var(--glass-border);
            border-radius: 14px; padding: 14px 16px; cursor: pointer; text-align: left;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); position: relative; overflow: hidden;
            display: flex; flex-direction: column; justify-content: flex-start; min-height: 90px;
        }

        body.light-mode .action-card { background: rgba(255, 255, 255, 0.6); }

        .action-card:hover {
            background: rgba(37, 99, 235, 0.1); border-color: rgba(37, 99, 235, 0.4);
            transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .action-card-icon {
            font-size: 1.2rem; margin-bottom: 8px; width: 34px; height: 34px;
            background: rgba(255,255,255,0.05); border-radius: 50%; display: flex;
            align-items: center; justify-content: center; transition: 0.3s; flex-shrink: 0;
        }

        body.light-mode .action-card-icon { background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .action-card:hover .action-card-icon { transform: scale(1.1); }

        .action-card-text { font-size: 0.85rem; color: var(--text-primary); font-weight: 500; line-height: 1.4; }

        /* ===== PERBAIKAN KHUSUS HP (Horizontal Scroll ala Gemini) ===== */
        @media (max-width: 768px) {
            .welcome-screen { padding-top: 15px !important; justify-content: center; }
            .welcome-logo-img { width: 55px; }
            .welcome-logo-glow { width: 70px; height: 70px; }
            .welcome-greeting { font-size: 1.8rem; }
            .welcome-subtext { font-size: 0.95rem; margin-bottom: 25px; }

            /* Menyulap Grid menjadi Scroll Samping di HP */
            .suggested-actions-grid {
                display: flex; flex-wrap: nowrap; overflow-x: auto;
                scroll-snap-type: x mandatory; padding-bottom: 15px; gap: 10px;
                -webkit-overflow-scrolling: touch;
            }
            .suggested-actions-grid::-webkit-scrollbar { display: none; /* Sembunyikan scrollbar agar rapi */ }
            .action-card {
                flex: 0 0 200px; /* Lebar kartu pas untuk layar HP */
                scroll-snap-align: start; min-height: 95px;
            }
        }

        .action-card {
            background: rgba(30, 41, 59, 0.4); border: 1px solid var(--glass-border);
            border-radius: 16px; padding: 20px; cursor: pointer; text-align: left;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); position: relative; overflow: hidden;
            display: flex; flex-direction: column; justify-content: space-between; min-height: 120px;
        }

        body.light-mode .action-card { background: rgba(255, 255, 255, 0.6); }

        .action-card:hover {
            background: rgba(37, 99, 235, 0.1); border-color: rgba(37, 99, 235, 0.4);
            transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }

        .action-card-icon {
            font-size: 1.4rem; margin-bottom: 15px; width: 42px; height: 42px;
            background: rgba(255,255,255,0.05); border-radius: 50%; display: flex;
            align-items: center; justify-content: center; transition: 0.3s;
        }

        body.light-mode .action-card-icon { background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .action-card:hover .action-card-icon { transform: scale(1.1); }

        .action-card-text { font-size: 0.95rem; color: var(--text-primary); font-weight: 500; line-height: 1.5; }

        /* ====================================================== */
        /* JURUS MUTLAK ANTI MELUBER (BERSIH & FLEXBOX)           */
        /* ====================================================== */
        .messages-container {
            overflow-x: hidden !important;
            width: 100% !important;
        }

        .message {
            width: 100%;
            max-width: 100% !important;
            display: flex;
            gap: 15px;
        }

        .message-content {
            /* flex: 1;              Paksa mengisi sisa ruang yang kosong */
            min-width: 0 !important;         /* KUNCI FLEXBOX: Izinkan menyusut */
            max-width: calc(100% - 55px) !important; /* Sisakan ruang mutlak untuk avatar AI/User */
            overflow: visible !important; /* JANGAN pakai hidden, agar dropdown Salin/DOCX tidak terpotong! */
        }

        /* 1. Paksa potong teks biasa dan link panjang */
        .message-bubble, .markdown-body {
            max-width: 100%;
            word-wrap: break-word !important;
            overflow-wrap: anywhere !important; /* 'anywhere' sangat ampuh memutus link panjang */
            word-break: normal !important;
        }

        .markdown-body p, .markdown-body li {
            white-space: pre-wrap !important; /* Pertahankan enter/paragraf */
            word-wrap: break-word !important;
            overflow-wrap: anywhere !important;
            max-width: 100%;
        }

        /* 2. KEMBALIKAN FITUR SCROLL KIRI-KANAN UNTUK KODINGAN & TABEL */
        /* Kodingan dan tabel JANGAN dipotong paksa, biarkan dia memanjang tapi beri Scrollbar! */
        .markdown-body pre,
        .markdown-body table,
        .mermaid-wrapper {
            max-width: 100% !important;
            width: 100%;
            overflow-x: auto !important; /* Munculkan scrollbar horizontal */
            display: block;
            word-break: normal !important;
            overflow-wrap: normal !important;
            white-space: normal !important;
        }

        .markdown-body pre code {
            white-space: pre !important; /* Kembalikan ke normal agar syntax rapi memanjang */
            word-break: normal !important;
            overflow-wrap: normal !important;
            display: block;
        }

        /* 3. Penyesuaian Ruang di HP (FIXED ABADI) */
        @media (max-width: 768px) {
            .message {
                max-width: 100% !important;
                width: 100% !important;
            }
            .message-content {
                /* Sisakan ruang 55px untuk avatar dan margin, sisanya pakai persen agar patuh! */
                max-width: calc(100% - 55px) !important;
                min-width: 0 !important;
                /* Paksa konten di dalamnya terpotong rapi jika over */
                overflow: hidden !important;
            }
            .message-bubble {
                width: 100% !important;
                max-width: 100% !important;
            }
            .markdown-body pre,
            .markdown-body table,
            .mermaid-wrapper {
                /* Gunakan 100% BUKAN 100vw agar patuh pada kotak bubble! */
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
                overflow-x: auto !important; /* Kodingan/tabel akan punya scrollbar sendiri */
            }
        }
        /* ====================================================== */
        /* FASE 2: FITUR ALPHA (DEEP RESEARCH SPLIT SCREEN)       */
        /* ====================================================== */
        .research-panel {
            width: 0;
            background: rgba(10, 14, 23, 0.95);
            border-left: 1px solid var(--glass-border);
            display: flex;
            flex-direction: column;
            transition: width 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            overflow: hidden;
            z-index: 40;
            white-space: nowrap;
        }
        body.light-mode .research-panel { background: #f8fafc; }

        .research-panel.active { width: 400px; }

        .research-header {
            padding: 18px 20px; border-bottom: 1px solid var(--glass-border);
            display: flex; align-items: center; justify-content: space-between;
            font-weight: 700; color: #ef4444; font-size: 1.1rem;
        }

        .research-logs {
            flex: 1; padding: 20px; overflow-y: auto; overflow-x: hidden;
            display: flex; flex-direction: column; gap: 12px;
            font-family: 'Roboto Mono', monospace; font-size: 0.85rem;
        }

        .log-item {
            background: rgba(0,0,0,0.3); padding: 12px; border-radius: 8px;
            border-left: 3px solid #3b82f6; color: var(--text-primary);
            animation: slideInRight 0.3s ease forwards; white-space: normal;
        }
        .log-item.processing { border-color: #f59e0b; color: #f59e0b; }
        .log-item.success { border-color: #10b981; color: #10b981; }

        @media (max-width: 768px) {
            .research-panel.active {
                position: absolute; top: 0; right: 0;
                width: 100% !important; height: 100%; z-index: 150; border-left: none;
            }
        }
        #floatingResearchBtn {
            position: fixed;
            top: 20px; /* Pindahkan agak ke atas agar tidak tertutup input chat */
            right: 20px;
            z-index: 10001; /* Pastikan di atas segalanya */
            background: var(--accent-gradient);
            color: white;
            padding: 12px 20px;
            border-radius: 30px;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
            font-size: 0.9rem;
            border: 1px solid rgba(255,255,255,0.3);
            font-weight: 600;
        }
        /* ===== STYLE LINK DI DALAM JAWABAN AI ===== */
        .markdown-body a {
            color: #3b82f6 !important; /* Warna biru cerah */
            text-decoration: none;
            font-weight: 600;
            border-bottom: 1px dashed rgba(59, 130, 246, 0.4);
            transition: all 0.2s ease;
        }

        .markdown-body a:hover {
            color: #60a5fa !important;
            border-bottom: 1px solid #60a5fa;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 4px;
            padding: 0 2px;
        }

        /* Ikon link otomatis setelah tautan referensi */
        .markdown-body a::after {
            content: "\f35d"; /* Icon font-awesome external-link */
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 0.7rem;
            margin-left: 4px;
            vertical-align: super;
        }

        body.light-mode .markdown-body a {
            color: #2563eb !important;
        }
        /* ===== CSS UNTUK LOGO SIDEBAR BERPUTAR ===== */
        .sidebar-logo-spin {
            width: 20px !important;
            height: 20px !important;
            border-radius: 50%;
            object-fit: cover;
            animation: spin 1s linear infinite; /* Animasi putar */
            margin-right: 12px;
        }
    </style>
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>
</head>

<body>
    <div class="modal-overlay" id="updateModal" style="z-index: 100010;">
        <div class="modal-content" style="padding: 0; overflow: hidden; max-width: 550px; background: var(--sidebar-bg); border-radius: 20px;">
            <button class="modal-close" id="closeModalBtn" style="z-index: 50; top: 15px; right: 15px; background: rgba(0,0,0,0.3); color: white;"><i class="fas fa-times"></i></button>

            <div id="onboard-step-1" style="display: block; position: relative;">
                <div style="background: linear-gradient(-45deg, #0a0e17, #1e293b, var(--accent-color), #06b6d4); background-size: 400% 400%; animation: gradientAurora 12s ease infinite; padding: 60px 20px; text-align: center; position: relative; overflow: hidden;">

                    <div style="position: absolute; top: -10%; left: -10%; width: 180px; height: 180px; background: rgba(37, 99, 235, 0.6); border-radius: 50%; filter: blur(40px); animation: floatOrb 7s ease-in-out infinite;"></div>
                    <div style="position: absolute; bottom: -20%; right: -10%; width: 220px; height: 220px; background: rgba(6, 182, 212, 0.5); border-radius: 50%; filter: blur(50px); animation: floatOrb 9s ease-in-out infinite reverse;"></div>
                    <div style="position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 60%); animation: rotateGlow 20s linear infinite;"></div>

                    <img src="https://i.ibb.co.com/wrrG06ds/Logo-SAHAJA-AI.png" alt="SAHAJA AI" class="animate-logo" style="width: 90px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.4); position: relative; z-index: 2; margin-bottom: 20px;">

                    <div class="animate-title" style="position: relative; z-index: 2; margin: 0;">
                        <h2 style="color: white; font-weight: 700; margin: 0; border: none; letter-spacing: 0.5px; font-size: 1.8rem; line-height: 1.3; text-shadow: 0 4px 15px rgba(0,0,0,0.4);">
                            Selamat Datang di<br>
                            <span class="welcome-greeting" style="font-size: 2.4rem; display: inline-block; padding-top: 5px; text-shadow: none;">SAHAJA AI</span>
                        </h2>
                    </div>
                </div>
                <div style="padding: 30px 25px;">
                    <p class="animate-desc" style="color: var(--text-secondary); font-size: 0.95rem; line-height: 1.6; margin-bottom: 30px; text-align: center;">Asisten AI cerdas yang dirancang khusus untuk mempermudah pengerjaan tugas, penulisan kodingan, hingga analisis data Anda. Mari lihat apa saja yang baru!</p>
                    <div class="animate-footer" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="onboard-dots">
                            <div class="dot active"></div>
                            <div class="dot"></div>
                        </div>
                        <button onclick="nextOnboardStep()" class="github-submit-btn" style="position: relative; z-index: 999; cursor: pointer; padding: 10px 25px; border-radius: 30px; font-size: 0.9rem;">Selanjutnya <i class="fas fa-arrow-right" style="margin-left: 5px;"></i></button>
                    </div>
                </div>
            </div>

            <div id="onboard-step-2" style="display: none; padding: 0;">
                <div style="background: var(--sidebar-bg); padding: 25px 25px 10px 25px; border-bottom: 1px solid var(--glass-border); position: relative; z-index: 10;">
                    <h2 style="color: var(--accent-color); font-size: 1.4rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 10px;">
                      PEMBARUAN SAHAJA AI
                    </h2>
                    <p style="color: var(--text-secondary); font-size: 0.85rem; margin-top: 5px; margin-bottom: 0;">Apa yang baru dari SAHAJA AI Beta v3.6?</p>
                </div>

                <div class="modal-body" style="padding: 20px 25px; max-height: 350px; overflow-y: auto;">

                    <div class="feature-item" style="animation: fadeInUp 0.6s ease forwards; animation-delay: 0.1s; background: rgba(239, 68, 68, 0.05); border-color: rgba(239, 68, 68, 0.2);">
                        <div class="feature-icon-wrapper" style="background: linear-gradient(135deg, #ef4444, #b91c1c);">
                            <i class="fas fa-atom"></i>
                        </div>
                        <div>
                            <strong style="display: block; font-size: 0.95rem; color: #ef4444; margin-bottom: 3px;">Mode Alpha (Deep Research) 🚀</strong>
                            <span style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.4;">Agen peneliti cerdas yang mampu menjelajahi internet, mencari jurnal, dan menyusun laporan riset. dapat diekspor dalam format DOCX.</span>
                        </div>
                    </div>

                    <div class="feature-item" style="animation: fadeInUp 0.6s ease forwards; animation-delay: 0.2s;">
                        <div class="feature-icon-wrapper" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <strong style="display: block; font-size: 0.95rem; color: #3b82f6; margin-bottom: 3px;">SAHAJA Connect</strong>
                            <span style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.4;">Forum komunitas baru yang dilengkapi dengan fitur diskusi dan komentar antar pengguna.</span>
                        </div>
                    </div>

                    <div class="feature-item" style="animation: fadeInUp 0.6s ease forwards; animation-delay: 0.3s;">
                        <div class="feature-icon-wrapper" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="fas fa-file-word"></i>
                        </div>
                        <div>
                            <strong style="display: block; font-size: 0.95rem; color: #10b981; margin-bottom: 3px;">Ekspor DOCX Canggih</strong>
                            <span style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.4;">Sekarang kamu bisa langsung mengunduh hasil jawaban AI ke dalam file Microsoft Word yang rapi (Khusus Laptop), termasuk hasil riset mendalam.</span>
                        </div>
                    </div>

                    <div class="feature-item" style="animation: fadeInUp 0.6s ease forwards; animation-delay: 0.4s;">
                        <div class="feature-icon-wrapper" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <div>
                            <strong style="display: block; font-size: 0.95rem; color: #8b5cf6; margin-bottom: 3px;">Kustomisasi Profil</strong>
                            <span style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.4;">Personalisasi akunmu dengan foto profil dan nama tampilan baru melalui menu Pengaturan.</span>
                        </div>
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 25px; border-top: 1px solid var(--glass-border); background: var(--sidebar-bg);">
                    <div class="onboard-dots">
                        <div class="dot"></div>
                        <div class="dot active" style="width: 24px; background: var(--success-color);"></div>
                    </div>
                    <button onclick="closeOnboardModal()" class="github-submit-btn" style="position: relative; z-index: 999; cursor: pointer; padding: 10px 25px; border-radius: 30px; font-size: 0.9rem; background: var(--success-color); box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);">
                        Mulai Sekarang <i class="fas fa-check" style="margin-left: 5px;"></i>
                    </button>
                </div>
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
                <div class="option-item" onclick="openHelpModal()">
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
            <div class="welcome-logo-container">
                <div class="welcome-logo-glow"></div>
                <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="Logo SAHAJA AI" class="welcome-logo-img">
            </div>

            <div class="welcome-text">
                <h1 class="welcome-greeting">Halo, {{ explode(' ', Auth::user()->name ?? 'Teman')[0] }}</h1>
                <h2 class="welcome-subtext">Apa yang bisa saya bantu hari ini?</h2>
            </div>

            <div class="suggested-actions-grid">
                <button class="action-card" onclick="useShortcut('Bantu saya menganalisis dan mencari error dari link GitHub berikut: ')">
                    <div class="action-card-icon" style="color: #a855f7;"><i class="fab fa-github"></i></div>
                    <span class="action-card-text">Analisis kode dari repository GitHub secara mendalam</span>
                </button>

                <button class="action-card" onclick="useShortcut('Tolong buatkan contoh kodingan Laravel CRUD sederhana.')">
                    <div class="action-card-icon" style="color: #3b82f6;"><i class="fas fa-code"></i></div>
                    <span class="action-card-text">Buat kodingan Laravel, PHP, atau framework lainnya</span>
                </button>

                <button class="action-card" onclick="useShortcut('Buatkan saya ide judul project akhir website berbasis AI.')">
                    <div class="action-card-icon" style="color: #eab308;"><i class="fas fa-lightbulb"></i></div>
                    <span class="action-card-text">Eksplorasi ide project akhir & rancangan sistem</span>
                </button>

                <button class="action-card" onclick="useShortcut('Jelaskan materi kuliah Sistem Informasi tentang basis data relasional.')">
                    <div class="action-card-icon" style="color: #10b981;"><i class="fas fa-book"></i></div>
                    <span class="action-card-text">Rangkum materi perkuliahan & jurnal akademis</span>
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
                        <div class="ai-actions" style="position: relative; display: flex; gap: 5px; align-items: center;">
                            <button class="action-btn" onclick="copyText(this)"><i class="far fa-copy"></i> Salin</button>

                            <div class="export-dropdown-container">
                                <button class="action-btn" onclick="toggleExportMenu(this)"><i class="fas fa-ellipsis-v"></i></button>
                                <div class="export-menu" style="display: none; position: absolute; bottom: 100%; left: 0; background: var(--sidebar-bg); border: 1px solid var(--glass-border); border-radius: 8px; padding: 5px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); z-index: 50; width: 140px; margin-bottom: 5px;">
                                    <div class="option-item" style="font-size: 0.8rem; padding: 6px 10px;" onclick="exportToDoc(this)"><i class="fas fa-file-word" style="color: #3b82f6;"></i> Ekspor ke DOCS</div>
                                </div>
                            </div>
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
                                        <strong style="font-size: 0.95rem; color: var(--text-primary);">Mode Otomatis</strong>
                                        <span style="font-size: 0.75rem; color: var(--text-secondary); line-height: 1.3;">Sistem menentukan berdasarkan prompt anda</span>
                                    </div>
                                </div>
                                <div class="option-item model-option" style="align-items: flex-start;"
                                    onclick="selectModelMode('fast', 'fa-bolt')">
                                    <i class="fas fa-bolt" style="color: #f59e0b; margin-top: 4px; width: 20px;"></i>
                                    <div style="display: flex; flex-direction: column; gap: 2px;">
                                        <strong style="font-size: 0.95rem; color: var(--text-primary);">Mode Cepat</strong>
                                        <span style="font-size: 0.75rem; color: var(--text-secondary); line-height: 1.3;">Menjawab instan dengan Groq Compound</span>
                                    </div>
                                </div>
                                <div class="option-item model-option" style="align-items: flex-start;"
                                    onclick="selectModelMode('smart', 'fa-brain')">
                                    <i class="fas fa-brain" style="color: #ec4899; margin-top: 4px; width: 20px;"></i>
                                    <div style="display: flex; flex-direction: column; gap: 2px;">
                                        <strong style="font-size: 0.95rem; color: var(--text-primary);">Mode Cerdas</strong>
                                        <span style="font-size: 0.75rem; color: var(--text-secondary); line-height: 1.3;">Bernalar tajam dengan Mistral 119B</span>
                                    </div>
                                </div>
                                <div class="option-item model-option" style="align-items: flex-start;"
                                    onclick="selectModelMode('vision', 'fa-eye')">
                                    <i class="fas fa-eye" style="color: #10b981; margin-top: 4px; width: 20px;"></i>
                                    <div style="display: flex; flex-direction: column; gap: 2px;">
                                        <strong style="font-size: 0.95rem; color: var(--text-primary);">Mode Vision</strong>
                                        <span style="font-size: 0.75rem; color: var(--text-secondary); line-height: 1.3;">Menganalisa gambar dengan Gemma 4</span>
                                    </div>
                                </div>
                                <div class="option-item model-option" style="align-items: flex-start;"
                                    onclick="selectModelMode('coding', 'fa-code')">
                                    <i class="fas fa-code" style="color: #a855f7; margin-top: 4px; width: 20px;"></i>
                                    <div style="display: flex; flex-direction: column; gap: 2px;">
                                        <strong style="font-size: 0.95rem; color: var(--text-primary);">Mode Coding</strong>
                                        <span style="font-size: 0.75rem; color: var(--text-secondary); line-height: 1.3;">Konteks besar dengan Qwen 3 Coder</span>
                                    </div>
                                </div>
                                <div class="option-item model-option" style="align-items: flex-start;" onclick="selectModelMode('alpha', 'fa-atom')">
                                    <i class="fas fa-atom" style="color: #ef4444; margin-top: 4px; width: 20px;"></i>
                                    <div style="display: flex; flex-direction: column; gap: 2px;">
                                        <strong style="font-size: 0.95rem; color: var(--text-primary);">Mode Alpha</strong>
                                        <span style="font-size: 0.75rem; color: var(--text-secondary); line-height: 1.3;">Deep Research Agent (Tavily + Mistral)</span>
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
        <button class="modal-close-outside" onclick="closeSettingsModal()"><i class="fas fa-times"></i></button>
        <div class="settings-modal-box">
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
                    <hr style="border: 0; border-top: 1px solid var(--glass-border); margin: 25px 0 15px 0;">
                    <h3 style="margin-bottom: 15px; color: var(--accent-color);"><i class="fas fa-sliders-h"></i> Konfigurasi Mesin AI</h3>

                    <div style="margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <strong style="font-size: 0.9rem;">Max Tokens (Panjang Jawaban)</strong>
                            <span id="tokenValueDisplay" style="font-size: 0.85rem; font-family: monospace; color: var(--accent-color); font-weight: bold;">4096</span>
                        </div>
                        <input type="range" id="maxTokensInput" min="512" max="8192" step="512" value="4096" style="width: 100%; accent-color: var(--accent-color); cursor: pointer;" oninput="document.getElementById('tokenValueDisplay').innerText = this.value">
                        <span style="font-size: 0.75rem; color: var(--text-secondary);">Atur batas maksimal kata. <b style="color: var(--danger-color);">*Hanya berlaku di Mode Cerdas.</b></span>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong style="display: block; font-size: 0.9rem;">Enable Thinking Mode</strong>
                            <span style="font-size: 0.75rem; color: var(--text-secondary);">AI akan bernalar mendalam. <b style="color: var(--danger-color);">*Hanya berlaku di Mode Cerdas.</b></span>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="enableThinkingInput">
                            <span class="toggle-slider"></span>
                        </label>
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
    <div class="modal-overlay" id="helpModal" style="z-index: 100005;">
        <div class="modal-content" style="max-width: 550px; background: var(--sidebar-bg); padding: 25px; border-radius: 12px; border: 1px solid var(--glass-border);">
            <button class="modal-close" onclick="closeCustomModal('helpModal')" style="position: absolute; right: 15px; top: 15px;"><i class="fas fa-times"></i></button>
            <h2 style="font-size: 1.3rem; margin-bottom: 15px; color: var(--accent-color);"><i class="fas fa-question-circle"></i> Bantuan & Umpan Balik</h2>

            <div style="display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 1px solid var(--glass-border); padding-bottom: 10px;">
                <button onclick="switchHelpTab('faq')" class="github-submit-btn" id="btn-faq" style="flex: 1; padding: 8px;">FAQ Bantuan</button>
                <button onclick="switchHelpTab('feedback')" class="github-submit-btn" id="btn-feedback" style="flex: 1; padding: 8px; background: transparent; color: var(--text-primary); border: 1px solid var(--glass-border);">Kirim Masukan</button>
            </div>

            <div id="help-faq">
                <p style="margin-bottom: 10px;"><strong>Q: Apa itu Thinking Mode?</strong><br><span style="color: var(--text-secondary); font-size: 0.9rem;">Fitur untuk memaksa AI bernalar mendalam (Chain of Thought). Cocok untuk Coding & Logika.</span></p>
                <p style="margin-bottom: 10px;"><strong>Q: Mengapa kena Error 502 Bad Gateway?</strong><br><span style="color: var(--text-secondary); font-size: 0.9rem;">Server NVIDIA kehabisan waktu memproses karena AI berpikir terlalu lama. Matikan Thinking Mode untuk tugas naratif biasa.</span></p>
            </div>

            <div id="help-feedback" style="display: none;">
                <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 10px;">Masukan Anda membantu SAHAJA AI menjadi lebih baik.</p>
                <textarea id="feedbackText" class="github-input" placeholder="Tulis masukan, kritik, atau laporan bug..." style="width: 100%; height: 100px; margin-bottom: 15px; resize: none;"></textarea>
                <button onclick="submitFeedback()" class="github-submit-btn" style="width: 100%;">Kirim Sekarang</button>
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
    <button id="floatingResearchBtn" onclick="toggleResearchPanel()" style="display: none; position: fixed; top: 80px; right: 20px; z-index: 100; background: var(--accent-gradient); color: white; padding: 10px 18px; border-radius: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.4); font-size: 0.85rem; border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(8px); cursor: pointer;">
        <i class="fas fa-atom fa-spin" style="margin-right: 6px;"></i> Buka Panel Riset
    </button>
    <div class="research-panel" id="researchPanel">
        <div class="research-header">
            <span><i class="fas fa-atom fa-spin" style="margin-right: 8px;"></i> Deep Research</span>
            <button onclick="toggleResearchPanel()" class="icon-btn" title="Minimize Panel"><i class="fas fa-compress-alt"></i></button>
        </div>
        <div class="research-logs" id="researchLogs">
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
        let isSwitchingMode = false; // VARIABEL PENANDA BARU

        function switchToMode(targetMode) {
            window.activeForceMode = targetMode;
            if (currentController) currentController.abort();
            const oldLoading = document.querySelector('.message.ai:last-child');
            if (oldLoading && oldLoading.querySelector('.typing-indicator')) oldLoading.remove();
            sendMessage();
        }

        function switchToFastMode() { switchToMode('fast'); }

        function detectComplexity(text) {
            const t = text.toLowerCase();
            const complex = ['coding', 'buatkan', 'analisis', 'html', 'laravel', 'script', 'error', 'database'];
            const simple = ['halo', 'hai', 'tes', 'ngoding', 'cerita'];
            if (complex.some(k => t.includes(k))) return true;
            if (t.split(' ').length < 10 && simple.some(k => t.includes(k))) return false;
            return t.split(' ').length > 15;
        }

        async function sendMessage() {
            if (typeof isRecording !== 'undefined' && isRecording && recognition) { recognition.stop(); forceStopRecordingUI(); }
            const messageInput = chatInput.value.trim();

            // Cegah pengiriman jika kosong
            if (!messageInput && !extractedFileText && !base64Image && !currentGithubRepo) return;

            if (userSelectedMode === 'alpha' && window.activeForceMode === null) {
                startDeepResearch(messageInput);
                chatInput.disabled = false;
                chatInput.style.height = 'auto';
                chatInput.value = '';
                chatInput.focus();
                return; // STOP! Biarkan Sang Mandor (Deep Research) yang bekerja!
            }
            // ========================================================

            let finalMessageToSend;
            let displayMessage = messageInput;

            if (window.activeForceMode !== null) {
                if (!lastUserMessage) return;
                finalMessageToSend = lastUserMessage;
            } else {
                if (extractedFileText) {
                    finalMessageToSend = `[Lampiran Dokumen: ${currentFileName}]\n"""\n${extractedFileText}\n"""\n\nInstruksi User: ${messageInput || "Tolong analisis"}`;
                    displayMessage = `📎 [${currentFileName}]\n${messageInput}`;
                } else if (base64Image) {
                    finalMessageToSend = messageInput || "Jelaskan gambar ini.";
                    displayMessage = `🖼️ [${currentFileName}]\n${messageInput}`;
                } else if (currentGithubRepo) {
                    finalMessageToSend = messageInput || "Analisis kode ini.";
                    displayMessage = `📦 [GitHub: ${currentFileName}]\n${messageInput}`;
                } else {
                    finalMessageToSend = messageInput;
                }
                lastUserMessage = finalMessageToSend;

                if (window.activeForceMode === null) {
                    // --- JURUS INSTAN SIDEBAR (ICON LINGKARAN) ---
                    if (!currentSessionId) {
                        const historyContainer = document.querySelector('.history-container');
                        const label = historyContainer.querySelector('.history-label');
                        const tempHtml = `
                            <div class="history-item-wrapper active" id="temp-session-loading">
                                <a href="#" class="history-item" style="pointer-events: none;">
                                    <i class="fas fa-circle-notch fa-spin history-icon" style="color: var(--accent-color);"></i>
                                    <div class="history-link">
                                        <span class="history-text text-label">Menyiapkan chat...</span>
                                    </div>
                                </a>
                            </div>`;
                        if (label) label.insertAdjacentHTML('afterend', tempHtml);
                    }

                    const welcome = document.getElementById('welcomeScreen');
                    if (welcome) welcome.style.display = 'none';
                    const msgContainer = document.getElementById('messagesContainer'); if (msgContainer) msgContainer.style.display = 'flex';
                    chatInput.value = ''; chatInput.style.height = 'auto';
                    appendMessage('user', displayMessage); formatAttachmentIcons();
                }
            }

            const payload = {
                message: finalMessageToSend, session_id: currentSessionId, manual_mode: userSelectedMode,
                max_tokens: document.getElementById('maxTokensInput').value, enable_thinking: document.getElementById('enableThinkingInput').checked
            };
            if (base64Image) payload.image_data = base64Image;
            if (extractedFileText) payload.file_name = currentFileName;
            if (currentGithubRepo) payload.github_repo = currentGithubRepo;

            currentController = new AbortController();
            const loadingId = appendLoadingWithMode(userSelectedMode); scrollToBottom();
            if (window.activeForceMode === null) removeFile();

            try {
                const response = await fetch('/send', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'text/event-stream' },
                    body: JSON.stringify(payload),
                    signal: currentController.signal
                });

                if (!currentSessionId) {
                    const dataRes = await response.clone().json();

                    // ========================================================
                    // --- JURUS AMPUH RELOAD (SOLUSI ROOM CHAT HILANG) ---
                    // ========================================================
                    if (dataRes.session_id) {
                        // Redirect ke URL chat baru agar sidebar me-render ulang room yang baru dibuat
                        window.location.href = `/chat/${dataRes.session_id}`;
                        return;
                    }
                    // ========================================================
                }

                if (!response.ok) {
                    document.getElementById(loadingId).remove();
                    const errorData = await response.json();
                    appendMessage('ai', `Error: ${errorData.message || 'Terjadi kesalahan'}`);
                    return;
                }

                document.getElementById(loadingId).remove();
                const aiMessageDiv = appendMessage('ai', '');
                const rawDiv = aiMessageDiv.querySelector('.ai-raw-data');
                const renderDiv = aiMessageDiv.querySelector('.ai-rendered-data');

                const reader = response.body.getReader();
                const decoder = new TextDecoder();
                let accumulatedContent = "";

                while (true) {
                    const { done, value } = await reader.read();
                    if (done) break;
                    const chunk = decoder.decode(value);
                    const lines = chunk.split('\n');
                    for (const line of lines) {
                        if (line.startsWith('data: ')) {
                            const data = line.slice(6);
                            if (data === '[DONE]') break;
                            try {
                                const json = JSON.parse(data);
                                if (json.content) {
                                    accumulatedContent += json.content;
                                    rawDiv.textContent = accumulatedContent;
                                    renderAIContent(accumulatedContent, renderDiv);
                                    scrollToBottomSmooth();
                                }
                            } catch (e) { console.error("Error parsing stream:", e); }
                        }
                    }
                }
                formatAttachmentIcons();

            } catch (error) {
                if (error.name !== 'AbortError') {
                    document.getElementById(loadingId).remove();
                    appendMessage('ai', `Terjadi kesalahan koneksi.`);
                }
            } finally { currentController = null; window.activeForceMode = null; chatInput.disabled = false; chatInput.focus(); }
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
        function animateGeminiStyle(element, markdownText) {
            const tempDiv = document.createElement('div'); renderAIContent(markdownText, tempDiv);
            element.innerHTML = '';
            Array.from(tempDiv.children).forEach((child) => { const wrapper = document.createElement('div'); wrapper.className = 'gemini-block'; wrapper.appendChild(child); element.appendChild(wrapper); });
            let delay = 0;
            element.querySelectorAll('.gemini-block').forEach((block) => { setTimeout(() => { block.classList.add('show'); scrollToBottom(); }, delay); delay += 120; });

            // INI YANG DITAMBAHKAN: Panggil Mermaid setelah animasi selesai
            setTimeout(() => {
                addCopyButtonsToCodeBlocks();
                processMermaidDiagrams(element);
            }, delay + 100);
        }
        function renderAIContent(text, containerElement) {
            let rawText = text.replace(/\\\[/g, '$$$$').replace(/\\\]/g, '$$$$').replace(/\\\(/g, '$$').replace(/\\\)/g, '$$');

            // 1. JURUS RAHASIA: TANGKAP TAG <thinking> ATAU <think> DULUAN
            const thinkingBlocks = {};
            let thinkingIndex = 0;

            // RegEx ini sudah di-upgrade agar bisa menangkap <think> dan <thinking> sekaligus
            rawText = rawText.replace(/<(?:thinking|think)>([\s\S]*?)<\/(?:thinking|think)>/gi, function(match, innerThinking) {
                const placeholder = `@@THINKING_BLOCK_${thinkingIndex}@@`;
                const cleanThinking = innerThinking.trim().replace(/</g, "&lt;").replace(/>/g, "&gt;");

                thinkingBlocks[placeholder] = `
                <div class="thinking-container" style="margin-bottom: 15px;">
                    <div class="thinking-header" onclick="this.nextElementSibling.classList.toggle('show'); const icon = this.querySelector('.fa-chevron-right'); if(icon.style.transform === 'rotate(90deg)') { icon.style.transform = 'none'; } else { icon.style.transform = 'rotate(90deg)'; }">
                        <i class="fas fa-brain"></i> <span style="font-weight: 500;">Alur Berpikir AI</span>
                        <i class="fas fa-chevron-right" style="margin-left: auto; transition: 0.2s;"></i>
                    </div>
                    <div class="thinking-content">${cleanThinking}</div>
                </div>`;
                thinkingIndex++;
                return placeholder;
            });

            // 2. TANGKAP RUMUS MATEMATIKA (Biar tidak rusak oleh Markdown)
            const mathBlocks = {};
            let mathIndex = 0;
            rawText = rawText.replace(/\$\$([\s\S]*?)\$\$/g, function(match) { const placeholder = `@@MATH_BLOCK_${mathIndex}@@`; mathBlocks[placeholder] = match; mathIndex++; return placeholder; });
            rawText = rawText.replace(/\$([^$\n]*?)\$/g, function(match) { const placeholder = `@@MATH_INLINE_${mathIndex}@@`; mathBlocks[placeholder] = match; mathIndex++; return placeholder; });

            // 3. UBAH TEKS JADI MARKDOWN (Tabel, Kodingan, dll)
            let htmlContent = marked.parse(rawText);

            // 4. KEMBALIKAN RUMUS & KOTAK THINKING KE TEMPAT ASALNYA
            for (const [placeholder, mathText] of Object.entries(mathBlocks)) htmlContent = htmlContent.split(placeholder).join(mathText);
            for (const [placeholder, thinkText] of Object.entries(thinkingBlocks)) htmlContent = htmlContent.split(placeholder).join(thinkText);

            containerElement.innerHTML = htmlContent;

            // 5. EKSEKUSI RENDER HIGHLIGHT KODE
            if (window.renderMathInElement) window.renderMathInElement(containerElement, { delimiters: [{ left: '$$', right: '$$', display: true }, { left: '$', right: '$', display: false }], throwOnError: false });
            containerElement.querySelectorAll('pre code').forEach((block) => { if (window.hljs) hljs.highlightElement(block); });
        }
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
            document.querySelectorAll('.message.ai').forEach((el) => {
                const rawDiv = el.querySelector('.ai-raw-data');
                const renderDiv = el.querySelector('.ai-rendered-data');
                if (rawDiv && renderDiv) {
                    renderAIContent(rawDiv.textContent.trim(), renderDiv);
                    // INI YANG DITAMBAHKAN: Proses diagram untuk chat masa lalu
                    processMermaidDiagrams(renderDiv);
                }
            });
            setTimeout(addCopyButtonsToCodeBlocks, 500);
            const chatCount = {{ count($chats ?? []) }};
            const updateModal = document.getElementById('updateModal');

            if (chatCount === 0 && updateModal && !sessionStorage.getItem('sahajaModalShown')) {
                setTimeout(() => {
                    updateModal.classList.add('show');
                    sessionStorage.setItem('sahajaModalShown', 'true');
                }, 1000); }
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

        function openHelpModal() { document.getElementById('helpModal').classList.add('show'); document.getElementById('logout-menu').classList.remove('show'); }
        function switchHelpTab(tab) {
            document.getElementById('help-faq').style.display = tab === 'faq' ? 'block' : 'none';
            document.getElementById('help-feedback').style.display = tab === 'feedback' ? 'block' : 'none';
            document.getElementById('btn-faq').style.background = tab === 'faq' ? 'var(--accent-gradient)' : 'transparent';
            document.getElementById('btn-faq').style.border = tab === 'faq' ? 'none' : '1px solid var(--glass-border)';
            document.getElementById('btn-feedback').style.background = tab === 'feedback' ? 'var(--accent-gradient)' : 'transparent';
            document.getElementById('btn-feedback').style.border = tab === 'feedback' ? 'none' : '1px solid var(--glass-border)';
        }
        async function submitFeedback() {
            const btn = document.querySelector('#help-feedback button');
            const textArea = document.getElementById('feedbackText');
            const text = textArea.value.trim();

            if(!text) return showToast("Tulis masukan terlebih dahulu", "error");

            // Ubah tombol jadi loading
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
            btn.disabled = true;

            try {
                const response = await fetch("{{ route('feedback.send') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ message: text })
                });

                const data = await response.json();
                if(response.ok) {
                    showToast("Terima kasih! Masukan Anda telah terkirim.", "success");
                    textArea.value = '';
                    closeCustomModal('helpModal');
                } else {
                    throw new Error("Gagal mengirim data");
                }
            } catch (error) {
                showToast("Terjadi kesalahan jaringan", "error");
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }
        // Fungsi Buka Tutup Menu Export
        function toggleExportMenu(btn) {
            const menu = btn.nextElementSibling;
            const isShowing = menu.style.display === 'block';
            document.querySelectorAll('.export-menu').forEach(m => m.style.display = 'none'); // Tutup yang lain
            if (!isShowing) menu.style.display = 'block';
            event.stopPropagation();
        }

        // Tutup menu jika klik di luar
        window.addEventListener('click', function() {
            document.querySelectorAll('.export-menu').forEach(m => m.style.display = 'none');
        });

        // ==========================================
        // 1. MESIN PEMBERSIH & FORMATTER (WAJIB ADA)
        // ==========================================
        function prepareExportContent(container) {
            const printDiv = document.createElement('div');
            printDiv.innerHTML = container.innerHTML;

            // Buang tombol dan kotak thinking
            printDiv.querySelectorAll('.thinking-container, .code-header, .code-copy-btn, button').forEach(el => el.remove());

            // JURUS ANTI MARKDOWN MENTAH: Paksa terjemahkan jika masih ada simbol # atau **
            if (printDiv.innerHTML.includes('**') || printDiv.innerHTML.includes('###')) {
                if (typeof marked !== 'undefined') {
                    printDiv.innerHTML = marked.parse(printDiv.textContent || printDiv.innerText);
                }
            }

            // SUNTIKAN GAYA BRUTAL (Agar Word & PDF Patuh 100%)
            printDiv.style.fontFamily = "'Times New Roman', Times, serif";
            printDiv.style.fontSize = "12pt";
            printDiv.style.color = "#000000";
            printDiv.style.backgroundColor = "#ffffff";
            printDiv.style.lineHeight = "1.5";

            // Paksa semua teks di dalamnya jadi hitam legam
            printDiv.querySelectorAll('*').forEach(el => {
                el.style.color = "#000000";
                el.style.fontFamily = "'Times New Roman', Times, serif";
            });

            // Paksa Tabel Punya Garis Hitam
            printDiv.querySelectorAll('table').forEach(table => {
                table.setAttribute('border', '1');
                table.style.width = '100%';
                table.style.borderCollapse = 'collapse';
                table.style.marginBottom = '15pt';
                table.style.border = '1px solid #000000';
            });
            printDiv.querySelectorAll('th, td').forEach(cell => {
                cell.style.border = '1px solid #000000';
                cell.style.padding = '8pt';
                cell.style.textAlign = 'left';
            });
            printDiv.querySelectorAll('th').forEach(th => {
                th.style.backgroundColor = '#f2f2f2';
                th.style.fontWeight = 'bold';
            });

            // Rapikan Judul (Heading) & Kodingan
            printDiv.querySelectorAll('h1, h2, h3').forEach(h => {
                h.style.fontWeight = 'bold';
                h.style.marginTop = '15pt';
                h.style.marginBottom = '10pt';
            });
            printDiv.querySelectorAll('pre, code').forEach(code => {
                code.style.fontFamily = "'Courier New', Courier, monospace";
                code.style.backgroundColor = '#f4f4f4';
                code.style.padding = '8pt';
                code.style.borderRadius = '3pt';
                code.style.whiteSpace = 'pre-wrap';
                code.style.border = '1px solid #cccccc';
            });

            return printDiv;
        }

        // ==========================================
        // FUNGSI EXPORT KE DOCX (ASLI, SUPPORT HP)
        // ==========================================
        window.exportToDoc = function(btn) {
            // 1. Ambil isi teks dari gelembung chat AI (Ambil yang SUDAH RENDER / HTML)
            const messageDiv = btn.closest('.message-content');
            // Prioritaskan mengambil .ai-rendered-data agar yang diambil adalah HTML bersih, bukan raw markdown
            const bubble = messageDiv.querySelector('.ai-rendered-data') || messageDiv.querySelector('.markdown-body');

            if (!bubble) {
                if (typeof showToast === "function") showToast("Gagal mengambil teks!", "error");
                return;
            }

            if (typeof showToast === "function") showToast("Merakit file DOCX...", "info");

            // 2. KUNCI RAHASIA: Masukkan bubble ke dalam Mesin Pembersih (prepareExportContent)
            // Langkah ini yang kemarin terlewat!
            const cleanPrintDiv = prepareExportContent(bubble);

            // 3. Siapkan kerangka HTML yang bersih agar rapi di Word
            const contentHTML = `
                <!DOCTYPE html>
                <html lang="id">
                <head>
                    <meta charset="UTF-8">
                    <title>SAHAJA AI Export</title>
                    <style>
                        body { font-family: 'Segoe UI', Arial, sans-serif; color: #333; line-height: 1.6; }
                        h1, h2, h3 { color: #2563eb; }
                        code { background-color: #f1f5f9; padding: 2px 5px; border-radius: 4px; font-family: monospace; }
                        pre { background-color: #f8fafc; padding: 15px; border-left: 4px solid #2563eb; }
                        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
                        table, th, td { border: 1px solid #cbd5e1; }
                        th, td { padding: 10px; text-align: left; }
                        th { background-color: #f1f5f9; }
                    </style>
                </head>
                <body>
                    <div style="border-bottom: 2px solid #2563eb; padding-bottom: 10px; margin-bottom: 20px;">
                        <h2 style="margin: 0;">SAHAJA AI Document</h2>
                        <span style="color: #64748b; font-size: 12px;">Diekspor pada: ${new Date().toLocaleString('id-ID')}</span>
                    </div>
                    ${cleanPrintDiv.innerHTML}
                </body>
                </html>
            `;

            try {
                // 4. Gunakan Library untuk merakit HTML menjadi file .docx asli (Blob)
                const converted = htmlDocx.asBlob(contentHTML);

                // 5. Buat proses download otomatis
                const link = document.createElement('a');
                link.href = URL.createObjectURL(converted);
                link.download = 'SAHAJA_AI_Export_' + Date.now() + '.docx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                if (typeof showToast === "function") showToast("Berhasil diunduh! (DOCX)", "success");
            } catch (error) {
                console.error("Export Error:", error);
                if (typeof showToast === "function") showToast("Gagal mengekspor dokumen.", "error");
            }
        };

        // ====================fungsi mermaid======================== //
        // 1. Inisialisasi Tema Mermaid agar cocok dengan SAHAJA AI
        mermaid.initialize({ startOnLoad: false, theme: 'dark' });

        // 2. Fungsi Menyulap Teks Code Menjadi Visual Diagram (ANTI ERROR / ANTI BOM)
        async function processMermaidDiagrams(container) {
            const mermaidBlocks = container.querySelectorAll('code.language-mermaid');
            if(mermaidBlocks.length === 0) return;

            // Gunakan perulangan FOR biasa (bukan forEach) agar bisa pakai 'await'
            for (let i = 0; i < mermaidBlocks.length; i++) {
                const codeBlock = mermaidBlocks[i];
                const preBlock = codeBlock.parentElement;

                if(preBlock.classList.contains('mermaid-processed')) continue;
                preBlock.classList.add('mermaid-processed');

                // Ambil teks murni
                let rawCode = codeBlock.textContent || codeBlock.innerText;

                // 1. Bersihkan spasi ghaib (NBSP)
                rawCode = rawCode.replace(/\u00A0/g, ' ').trim();

                // 2. JURUS FILTER MESIN CUCI: Bersihkan kotoran sisa Markdown AI
                // Hilangkan kata "mermaid" di awal teks jika AI tidak sengaja menuliskannya
                rawCode = rawCode.replace(/^mermaid\s*/i, '');
                // Hilangkan sisa backtick (```) yang nyangkut
                rawCode = rawCode.replace(/```/g, '');
                // Hilangkan spasi berlebih di awal & akhir
                rawCode = rawCode.trim();

                const uniqueId = 'mermaid-' + Date.now() + '-' + i;

                const wrapper = document.createElement('div');
                wrapper.className = 'mermaid-wrapper';
                wrapper.innerHTML = `
                    <div class="mermaid-header">
                        <div class="mermaid-tabs">
                            <button class="mermaid-tab active" onclick="switchMermaid('${uniqueId}', 'diagram', this)">Visual Diagram</button>
                            <button class="mermaid-tab" onclick="switchMermaid('${uniqueId}', 'code', this)">Source Code</button>
                        </div>
                        <button class="mermaid-download" onclick="downloadMermaid('${uniqueId}')"><i class="fas fa-image"></i> JPG</button>
                    </div>
                    <div id="${uniqueId}-diagram" class="mermaid-content">
                        <div style="color: var(--accent-color); padding: 20px;"><i class="fas fa-spinner fa-spin"></i> Menggambar diagram...</div>
                    </div>
                    <div id="${uniqueId}-code" class="mermaid-code">
                        <pre><code class="language-mermaid"></code></pre>
                    </div>
                `;

                wrapper.querySelector('.language-mermaid').textContent = rawCode;
                preBlock.replaceWith(wrapper);

                try {
                    // RENDER DIAGRAM SATU PER SATU SECARA AMAN
                    const { svg } = await mermaid.render(uniqueId + '-svg', rawCode);
                    document.getElementById(uniqueId + '-diagram').innerHTML = svg;
                } catch (e) {
                    console.error("Mermaid Render Error:", e);
                    // Kalau AI ngasih kodingan error, tampilkan pesan rapi, BUKAN logo bom!
                    document.getElementById(uniqueId + '-diagram').innerHTML = `
                        <div style="color: #ef4444; padding: 15px; border: 1px dashed #ef4444; border-radius: 8px; text-align: left;">
                            <i class="fas fa-exclamation-triangle"></i> <b>Diagram Gagal Digambar</b><br>
                            <span style="font-size: 0.85rem; color: var(--text-secondary);">Sintaks diagram dari AI tidak valid. Klik tab <b>Source Code</b> untuk melihat kodenya.</span>
                        </div>
                    `;
                }
            }
        }

        // 3. Fungsi Tombol Switch (Diagram vs Code)
        window.switchMermaid = function(id, mode, btn) {
            const wrapper = btn.closest('.mermaid-wrapper');
            wrapper.querySelectorAll('.mermaid-tab').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            if(mode === 'diagram') {
                document.getElementById(id + '-diagram').style.display = 'block';
                document.getElementById(id + '-code').style.display = 'none';
            } else {
                document.getElementById(id + '-diagram').style.display = 'none';
                document.getElementById(id + '-code').style.display = 'block';
            }
        };

        // 4. Fungsi Tombol Download Gambar Diagram (UBAH KE JPG UNTUK SUPPORT HP)
        window.downloadMermaid = function(id) {
            const svg = document.querySelector(`#${id}-diagram svg`);
            if(!svg) return showToast('Diagram belum selesai diproses', 'error');

            showToast('Merender gambar Ultra HD...', 'info');

            // 1. Gandakan SVG agar tidak merusak tampilan asli di layar HP
            const svgClone = svg.cloneNode(true);

            // 2. KUNCI RAHASIA: Tentukan Skala Resolusi (3x lipat lebih tajam)
            const scale = 5;
            const origWidth = svg.getBoundingClientRect().width || 800;
            const origHeight = svg.getBoundingClientRect().height || 600;

            // 3. Paksa kloningan SVG menjadi ukuran raksasa sebelum difoto
            svgClone.setAttribute("width", origWidth * scale);
            svgClone.setAttribute("height", origHeight * scale);

            // 4. Ambil data mentah dari SVG raksasa
            const svgData = new XMLSerializer().serializeToString(svgClone);
            const canvas = document.createElement("canvas");
            const ctx = canvas.getContext("2d");
            const img = new Image();

            const b64Start = 'data:image/svg+xml;base64,';
            const image64 = b64Start + btoa(unescape(encodeURIComponent(svgData)));

            img.onload = function() {
                // 5. Atur kanvas menjadi ukuran raksasa
                canvas.width = origWidth * scale;
                canvas.height = origHeight * scale;

                // 6. Beri latar belakang putih bersih
                ctx.fillStyle = "#ffffff";
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                // 7. Gambar diagram HD ke atas kanvas
                ctx.drawImage(img, 0, 0);

                // 8. Cetak menjadi JPG dengan Kualitas Super Maximum (1.0)
                const imgURI = canvas.toDataURL("image/jpeg", 1.0);

                const link = document.createElement("a");
                link.download = 'SAHAJA_Mermaid_' + Date.now() + '.jpg';
                link.href = imgURI;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                showToast('Gambar Berhasil Diunduh!', 'success');
            };

            img.src = image64;
        };
        // ==========================================
        // FUNGSI SLIDER ONBOARDING / UPDATE MODAL
        // ==========================================
        function nextOnboardStep() {
            const step1 = document.getElementById('onboard-step-1');
            const step2 = document.getElementById('onboard-step-2');

            // 1. Buat Slide 1 memudar dan bergeser perlahan ke kiri
            step1.style.transition = "all 0.4s ease-in-out";
            step1.style.opacity = "0";
            step1.style.transform = "translateX(-30px)";

            // 2. Tunggu 400ms (sampai animasi slide 1 selesai), baru panggil Slide 2
            setTimeout(() => {
                step1.style.display = 'none';
                step2.style.display = 'block';
                // Slide 2 masuk dari kanan (menggunakan CSS slide-in-right yang sudah ada)
                step2.classList.add('slide-in-right');
            }, 400);
        }
        // Fungsi untuk menutup modal dengan mulus
        window.closeOnboardModal = function() {
            const updateModal = document.getElementById('updateModal');
            if(updateModal) {
                // Berikan efek memudar sebelum hilang
                updateModal.style.transition = "opacity 0.4s ease";
                updateModal.style.opacity = "0";

                setTimeout(() => {
                    updateModal.classList.remove('show');
                    // Reset opacity untuk pemakaian berikutnya
                    updateModal.style.opacity = "1";
                    updateModal.style.display = "none";
                }, 400);
            }
        };
        // ==========================================
        // SANG MANDOR (VERSI X-RAY ANTI SILENT DEATH)
        // ==========================================
        let currentResearchId = null;

        async function startDeepResearch(prompt) {
            document.getElementById('floatingResearchBtn').style.display = 'none';
            document.getElementById('researchPanel').classList.add('active');
            const logsContainer = document.getElementById('researchLogs');
            logsContainer.innerHTML = '';

            appendResearchLog('Menginisialisasi Agen Alpha...', 'processing');

            try {
                const res = await fetch('/deep-research/init', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({topic: prompt, session_id: currentSessionId})
                });

                // JURUS X-RAY: Ambil teks mentah dari server sebelum di-parse jadi JSON!
                const rawText = await res.text();

                let data;
                try {
                    data = JSON.parse(rawText);
                } catch(err) {
                    // JIKA GAGAL JADI JSON (LARAVEL MELEMPAR ERROR 500)
                    console.error("🔥 LARAVEL ERROR KETAHUAN:", rawText);
                    appendResearchLog('Server Laravel Meledak! Buka Console (F12).', 'error');
                    alert("ERROR SERVER! Tekan F12 di keyboard, buka tab 'Console' untuk melihat penyakit aslinya!");
                    return; // Hentikan proses agar tidak mutar-mutar
                }

                if(data.success) {
                    currentResearchId = data.research_id;

                    // UPDATE URL JIKA SESSION BARU: Biar kalau refresh nggak balik ke welcome screen
                    if (!currentSessionId && data.session_id) {
                    window.history.pushState({}, '', `/chat/${data.session_id}`);
                    currentSessionId = data.session_id;

                    // HAPUS ITEM SEMENTARA: Biar tidak double saat halaman di-render ulang nanti
                    const tempItem = document.getElementById('temp-session-loading');
                    if (tempItem) tempItem.remove();
                }

                    appendResearchLog('Agen berhasil diaktifkan. Memulai pencarian data...', 'info');
                    setTimeout(pollResearchStep, 2000);
                } else {
                    appendResearchLog('Gagal Inisialisasi: ' + (data.message || 'Server Menolak'), 'error');
                }
            } catch (e) {
                appendResearchLog('Gagal menyambung ke server! Koneksi terputus.', 'error');
            }
        }

        async function pollResearchStep() {
            if(!currentResearchId) return;

            try {
                const res = await fetch('/deep-research/step', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({research_id: currentResearchId})
                });

                // JURUS X-RAY UNTUK STEP 2
                const rawText = await res.text();
                let data;
                try {
                    data = JSON.parse(rawText);
                } catch(err) {
                    console.error("🔥 LARAVEL ERROR PADA SAAT PROSES AI:", rawText);
                    appendResearchLog('Proses terhenti karena Error di Server. Cek Console.', 'error');
                    return; // Hentikan agar tidak polling abadi
                }

                const logsContainer = document.getElementById('researchLogs');
                logsContainer.innerHTML = '';
                if(data.logs && data.logs.length > 0) {
                    data.logs.forEach(log => {
                        logsContainer.innerHTML += `<div class="log-item info"><span style="color: #94a3b8; font-size: 0.75rem; margin-right: 5px;">[${log.time}]</span> ${log.message}</div>`;
                    });
                }
                logsContainer.scrollTop = logsContainer.scrollHeight;

                if(data.status === 'selesai') {
                    appendResearchLog('Menutup Agen Alpha...', 'success');

                    // SULAP HASILNYA MENJADI CARD CHAT!
                    const aiMessageDiv = document.createElement('div');
                    aiMessageDiv.className = 'message ai';
                    aiMessageDiv.innerHTML = `<div class="message-avatar ai-avatar-msg" style="background: transparent; padding: 0;"><img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;"></div><div class="message-content"><div class="mode-badge" style="background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3);"><i class="fas fa-atom"></i> Hasil Deep Research</div><div class="message-bubble markdown-body ai-raw-data" style="display:none;">${data.result}</div><div class="message-bubble markdown-body ai-rendered-data"></div><div class="ai-actions" style="position: relative; display: flex; gap: 5px; align-items: center;"><button class="action-btn" onclick="copyText(this)"><i class="far fa-copy"></i> Salin</button><div class="export-dropdown-container"><button class="action-btn" onclick="toggleExportMenu(this)"><i class="fas fa-ellipsis-v"></i></button><div class="export-menu" style="display: none; position: absolute; bottom: 100%; left: 0; background: var(--sidebar-bg); border: 1px solid var(--glass-border); border-radius: 8px; padding: 5px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); z-index: 50; width: 140px; margin-bottom: 5px;"><div class="option-item" style="font-size: 0.8rem; padding: 6px 10px;" onclick="exportToDoc(this)"><i class="fas fa-file-word" style="color: #3b82f6;"></i> Unduh DOCS</div></div></div></div></div>`;

                    document.getElementById('messagesContainer').appendChild(aiMessageDiv);

                    const rawDiv = aiMessageDiv.querySelector('.ai-raw-data');
                    const renderDiv = aiMessageDiv.querySelector('.ai-rendered-data');
                    renderAIContent(rawDiv.textContent.trim(), renderDiv);
                    scrollToBottomSmooth();

                    // PERBAIKAN: Tutup panel dan hapus tombol melayang secara paksa tanpa fungsi toggle
                    setTimeout(() => {
                        document.getElementById('researchPanel').classList.remove('active');
                        document.getElementById('floatingResearchBtn').style.display = 'none';
                        currentResearchId = null; // Bersihkan memori agar tombol tidak "nyangkut"
                    }, 3000);
                    return;

                } else if (data.status === 'error') {
                    appendResearchLog('Proses dibatalkan karena error.', 'error');

                    // PERBAIKAN: Tutup juga saat error agar bersih
                    setTimeout(() => {
                        document.getElementById('researchPanel').classList.remove('active');
                        document.getElementById('floatingResearchBtn').style.display = 'none';
                        currentResearchId = null;
                    }, 3000);
                    return;
                }

                setTimeout(pollResearchStep, 2000);
            } catch(e) {
                setTimeout(pollResearchStep, 5000);
            }
        }

        function appendResearchLog(text, type = 'info') {
            const logsContainer = document.getElementById('researchLogs');
            const icon = type === 'processing' ? '<i class="fas fa-circle-notch fa-spin"></i>' :
                         (type === 'success' ? '<i class="fas fa-check"></i>' : '<i class="fas fa-info-circle"></i>');

            logsContainer.innerHTML += `<div class="log-item ${type}">${icon} <span style="margin-left: 8px;">${text}</span></div>`;
            logsContainer.scrollTop = logsContainer.scrollHeight; // Auto scroll ke bawah
        }
        // Fungsi untuk Buka/Tutup Panel Riset (Fixed)
        window.toggleResearchPanel = function() {
            const panel = document.getElementById('researchPanel');
            const floatBtn = document.getElementById('floatingResearchBtn');

            if(panel.classList.contains('active')) {
                // Minimize
                panel.classList.remove('active');
                // Selalu munculkan tombol jika riset sedang berjalan atau baru saja mulai
                floatBtn.style.display = 'block';
            } else {
                // Expand
                panel.classList.add('active');
                floatBtn.style.display = 'none';
            }
        };
    </script>
</body>
</html>

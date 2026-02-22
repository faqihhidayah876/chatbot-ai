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

        /* FOOTER & LOGOUT */
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
            z-index: 101;
        }

        body.light-mode .settings-menu-dropdown {
            background: #ffffff;
            color: #333;
        }

        .settings-menu-dropdown.show {
            display: block;
        }

        /* MESSAGES */
        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 30px 5% 40px;
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
        }

        .ai .message-bubble {
            background: transparent;
            border: none;
            padding: 0 5px;
            color: var(--text-primary);
            box-shadow: none;
        }

        /* MARKDOWN + CODE HIGHLIGHTING */
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

        .markdown-body ul,
        .markdown-body ol {
            margin-bottom: 16px;
            padding-left: 24px;
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

        /* ===== BADGE & SWITCH BTN ===== */
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

        .switch-btn:hover {
            color: #c0392b;
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
        }

        .code-header .code-copy-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 0.75rem;
            color: #e3e3e3;
            display: flex;
            align-items: center;
            gap: 6px;
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
           INPUT AREA BARU (GEMINI STYLE)
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
        }

        body.light-mode .input-container {
            background: rgba(255, 255, 255, 0.85) !important;
        }

        .input-wrapper {
            width: 100%;
            max-width: 800px;
            display: flex;
            flex-direction: column;
            /* Atas Bawah */
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 12px 16px 8px 16px;
            transition: 0.3s;
        }

        body.light-mode .input-wrapper {
            background: #f1f5f9;
            border-color: #e2e8f0;
        }

        .input-wrapper:focus-within {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
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

        .input-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
            width: 100%;
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
            box-shadow: 0 2px 10px rgba(37, 99, 235, 0.3);
        }

        .send-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.5);
        }

        /* Tombol Voice Baru */
        .voice-btn {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            transition: 0.3s;
        }

        .voice-btn:hover {
            background: var(--glass-highlight);
            color: var(--text-primary);
        }

        body.light-mode .voice-btn {
            color: #64748b;
        }

        body.light-mode .voice-btn:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        /* Animasi Rekam Suara */
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
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
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

            .voice-btn,
            .send-btn {
                width: 36px !important;
                height: 36px !important;
            }

            .input-footer {
                font-size: 0.6rem !important;
                margin-top: 0 !important;
            }

            .messages-container {
                padding: 20px 4% 60px !important;
                gap: 20px !important;
            }

            .modal-content {
                padding: 1.5rem;
                width: 95%;
            }
        }

        @media (max-width: 375px) {
            .input-container {
                padding: 2px 10px !important;
            }

            .input-wrapper {
                padding: 8px 10px 6px 10px !important;
            }

            .chat-input {
                font-size: 0.85rem !important;
            }

            .voice-btn,
            .send-btn {
                width: 34px !important;
                height: 34px !important;
            }

            .messages-container {
                padding: 15px 3% 50px !important;
            }
        }

        @supports (padding-bottom: env(safe-area-inset-bottom)) {
            .input-container {
                padding-bottom: max(2px, env(safe-area-inset-bottom)) !important;
            }

            .messages-container {
                padding-bottom: max(40px, env(safe-area-inset-bottom) + 20px) !important;
            }
        }
    </style>
</head>

<body>

    <div class="modal-overlay" id="updateModal">
        <div class="modal-content">
            <button class="modal-close" id="closeModalBtn"><i class="fas fa-times"></i></button>
            <h2>
                <center>PEMBARUAN SAHAJA AI</center>
            </h2>
            <div class="modal-body">
                <p><strong>1. Fitur Voice Input (Baru!) 🎙️</strong><br>
                    Kini kamu bisa ngobrol langsung dengan SAHAJA AI tanpa ngetik. Cukup tekan tombol ikon Mikrofon di
                    kanan bawah, lalu berbicaralah.
                </p>
                <p><strong>2. Fitur Fleksibel Switching Model ⚡</strong><br>
                    Sekarang anda bisa mengubah mode jawaban respon AI dari cepat ke cerdas secara langsung.
                </p>
                <p><strong>3. Redesain Chatbox ala Gemini 🎨</strong><br>
                    Tampilan input chat kini lebih luas dan nyaman untuk menulis prompt yang panjang.
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
            <button class="toggle-btn-sidebar" id="sidebarToggleBtn" title="Toggle Sidebar">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="new-chat-wrapper">
            <a href="{{ route('chat.new') }}" class="new-chat-btn">
                <i class="fas fa-plus"></i>
                <span class="btn-text text-label">Percakapan Baru</span>
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
                    <button class="options-btn" onclick="toggleMenu(event, 'menu-{{ $session->id }}')">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div class="options-menu" id="menu-{{ $session->id }}">
                        <div class="option-item" onclick="shareSession({{ $session->id }})">
                            <i class="fas fa-share-alt"></i> Bagikan
                        </div>
                        <div class="option-item" onclick="renameSession({{ $session->id }})">
                            <i class="fas fa-pen"></i> Ganti Nama
                        </div>
                        <div class="option-item delete" onclick="deleteSession({{ $session->id }})">
                            <i class="fas fa-trash"></i> Hapus
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="sidebar-footer">
            <div class="user-profile" onclick="toggleMenu(event, 'logout-menu')">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="sidebar-footer-details text-label" style="margin-left: 10px;">
                    <div style="font-weight: 600; color: var(--text-primary);">{{ Auth::user()->name ?? 'Pengguna' }}
                    </div>
                </div>
            </div>
            <div class="logout-menu" id="logout-menu">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="option-item delete" style="width: 100%;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="chat-header">
            <div style="display: flex; align-items: center;">
                <button class="mobile-toggle-btn" id="mobileToggleBtn"><i class="fas fa-bars"></i></button>
                <div class="chat-title">
                    <i class="fas fa-robot" style="color: var(--accent-color);"></i>
                    SAHAJA AI
                    <span class="model-badge">Powered by: Kimi K 2.5</span>
                </div>
            </div>
            <div class="settings-container">
                <button class="icon-btn" id="settingsBtn"><i class="fas fa-cog"></i></button>
                <div class="settings-menu-dropdown" id="settingsMenu">
                    <div class="option-item" id="themeToggleItem">
                        <i class="fas fa-adjust"></i>
                        <span id="themeText">Ganti Tema</span>
                    </div>
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
                        <div class="message-bubble">{{ $chat->user_message }}</div>
                    </div>
                </div>
                <div class="message ai">
                    <div class="message-avatar ai-avatar-msg"><i class="fas fa-robot"></i></div>
                    <div class="message-content">
                        <div class="message-bubble markdown-body">{{ $chat->ai_response }}</div>
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
                <textarea class="chat-input" id="chatInput" placeholder="Ketik pesan Anda di sini..." rows="1"></textarea>

                <div class="input-actions">
                    <button type="button" class="voice-btn" id="voiceButton" title="Bicara dengan SAHAJA">
                        <i class="fas fa-microphone"></i>
                    </button>
                    <button type="button" class="send-btn" id="sendButton" title="Kirim Pesan">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
            <div class="input-footer">SAHAJA AI dapat membuat kesalahan, periksa lebih lanjut</div>
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

        // Perbaikan Naming Variable agar tak bentrok
        window.activeForceMode = null;

        // TANGKAP ELEMENT INPUT
        const chatInput = document.getElementById('chatInput');
        const voiceBtn = document.getElementById('voiceButton');

        // MARKED SETUP
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
        // FITUR VOICE INPUT (WEB SPEECH API) 🎙️ (VERSI STABIL)
        // ==========================================
        let recognition = null;
        let isRecording = false;

        if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRecognition();
            recognition.lang = 'id-ID';
            recognition.interimResults = true;

            // PERBAIKAN 1: Jadikan false agar tidak bentrok di browser HP
            recognition.continuous = false;

            recognition.onstart = function() {
                isRecording = true;
                voiceBtn.classList.add('recording');
                voiceBtn.innerHTML = '<i class="fas fa-stop"></i>';
                chatInput.placeholder = "Mendengarkan... (Bicara sekarang)";
            };

            recognition.onresult = function(event) {
                // PERBAIKAN 2: Logika perakitan teks yang lebih kuat
                let interim_transcript = '';
                let final_transcript = '';

                for (let i = 0; i < event.results.length; ++i) {
                    if (event.results[i].isFinal) {
                        final_transcript += event.results[i][0].transcript;
                    } else {
                        interim_transcript += event.results[i][0].transcript;
                    }
                }

                const prefix = window.preRecordInput ? window.preRecordInput + ' ' : '';
                chatInput.value = prefix + final_transcript + interim_transcript;

                // Trigger event input agar tinggi textarea otomatis menyesuaikan
                chatInput.dispatchEvent(new Event('input'));
            };

            recognition.onerror = function(event) {
                console.error("Voice Error:", event.error);
                forceStopRecordingUI();

                // Tambahan peringatan agar kita tau kalau error
                if (event.error === 'not-allowed') {
                    alert("Akses Mikrofon ditolak! Pastikan Anda mengizinkan mic di browser.");
                } else if (event.error === 'no-speech') {
                    alert("Tidak ada suara yang terdengar. Coba bicara lebih keras.");
                }
            };

            recognition.onend = function() {
                forceStopRecordingUI();
            };
        } else {
            voiceBtn.style.display = 'none'; // Sembunyikan jika browser tidak support
        }

        function toggleRecording() {
            if (!recognition) {
                alert("Browser Anda tidak mendukung Voice Input. Wajib gunakan Google Chrome.");
                return;
            }
            if (isRecording) {
                recognition.stop(); // Matikan mic
                forceStopRecordingUI();
            } else {
                window.preRecordInput = chatInput.value.trim();
                try {
                    recognition.start();
                } catch (e) {}
            }
        }

        // Fungsi wajib untuk mematikan animasi mic & teks placeholder
        function forceStopRecordingUI() {
            isRecording = false;
            voiceBtn.classList.remove('recording');
            voiceBtn.innerHTML = '<i class="fas fa-microphone"></i>';
            chatInput.placeholder = "Ketik pesan Anda di sini...";
        }

        // Pasang Event ke tombol Voice
        voiceBtn.addEventListener('click', toggleRecording);

        // ==========================================
        // FUNGSI SWITCH MODE & KIRIM PESAN
        // ==========================================
        function switchToMode(targetMode) {
            console.log("Switching to " + targetMode + " mode...");
            window.activeForceMode = targetMode;

            if (currentController) {
                currentController.abort();
                currentController = null;
            }

            const oldLoading = document.querySelector('.message.ai:last-child');
            if (oldLoading && oldLoading.querySelector('.typing-indicator')) {
                oldLoading.remove();
            }
            sendMessage();
        }

        async function sendMessage() {
            // JIKA MIC MASIH NYALA SAAT TEKAN KIRIM, MATIKAN PAKSA!
            if (isRecording && recognition) {
                recognition.stop();
                forceStopRecordingUI();
            }

            const messageInput = chatInput.value.trim();
            let message;

            if (window.activeForceMode !== null) {
                if (!lastUserMessage) return;
                message = lastUserMessage;
            } else {
                if (!messageInput) return;
                message = messageInput;
                lastUserMessage = message;
            }

            if (window.activeForceMode === null) {
                document.getElementById('welcomeScreen').style.display = 'none';
                document.getElementById('messagesContainer').style.display = 'flex';
                chatInput.value = '';
                chatInput.style.height = 'auto';
                appendMessage('user', message);
            }

            const isComplex = detectComplexity(message);
            const mode = (window.activeForceMode !== null) ? window.activeForceMode : (isComplex ? 'smart' : 'fast');

            const loadingId = appendLoadingWithMode(mode);
            scrollToBottom();

            if (currentController) currentController.abort();
            currentController = new AbortController();

            try {
                const payload = {
                    message: message,
                    session_id: currentSessionId
                };
                if (window.activeForceMode !== null) payload.force_mode = window.activeForceMode;

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
                    let finalModelLabel = '';
                    let finalBadgeClass = '';

                    if (data.model_used && data.model_used.includes('k2.5')) {
                        finalModelLabel = '🧠 Mode Cerdas';
                        finalBadgeClass = 'mode-smart';
                    } else if (data.model_used && data.model_used.includes('k2')) {
                        finalModelLabel = '⚡ Mode Cepat';
                        finalBadgeClass = 'mode-fast';
                    }

                    aiMessageDiv.innerHTML = `
                        <div class="message-avatar ai-avatar-msg"><i class="fas fa-robot"></i></div>
                        <div class="message-content">
                            <div class="mode-badge ${finalBadgeClass}">${finalModelLabel}</div>
                            <div class="message-bubble markdown-body"></div>
                            <div class="ai-actions">
                                <button class="action-btn" onclick="copyText(this)"><i class="far fa-copy"></i> Salin</button>
                                <button class="action-btn"><i class="far fa-thumbs-up"></i></button>
                                <button class="action-btn"><i class="far fa-thumbs-down"></i></button>
                            </div>
                        </div>
                    `;

                    loadingBubble.parentNode.replaceChild(aiMessageDiv, loadingBubble);
                    const bubble = aiMessageDiv.querySelector('.message-bubble');
                    typeWriter(bubble, data.ai_response, 12);
                    scrollToBottom();
                }

                if (!currentSessionId && data.session_id) {
                    window.history.pushState({}, '', `/chat/${data.session_id}`);
                    currentSessionId = data.session_id;
                }

                window.activeForceMode = null;

            } catch (error) {
                document.getElementById(loadingId)?.remove();
                if (error.name !== 'AbortError') {
                    console.error("Detail Error:", error);
                    alert("Gagal: " + error.message);
                }
                window.activeForceMode = null;
            }
        }

        function appendLoadingWithMode(mode) {
            const id = 'loading-' + Date.now();
            const div = document.createElement('div');
            div.id = id;
            div.className = 'message ai';

            let badgeHtml, textHtml;

            if (mode === 'smart') {
                badgeHtml = `<div class="mode-badge mode-smart"><i class="fas fa-brain"></i> Mode Cerdas (K2.5)</div>`;
                textHtml = `
                    <span class="typing-text">
                        Menganalisis logika kompleks (1-3 menit)...
                        <button class="switch-btn" onclick="switchToMode('fast')">[Beralih ke Cepat]</button>
                    </span>`;
            } else {
                badgeHtml = `<div class="mode-badge mode-fast"><i class="fas fa-bolt"></i> Mode Cepat (K2)</div>`;
                textHtml = `
                    <span class="typing-text">
                        SAHAJA AI sedang berpikir...
                        <button class="switch-btn" style="color: #d4a017;" onclick="switchToMode('smart')">[Beralih ke Cerdas]</button>
                    </span>
                `;
            }

            div.innerHTML = `
                <div class="message-avatar ai-avatar-msg"><i class="fas fa-robot"></i></div>
                <div class="message-content">
                    ${badgeHtml}
                    <div class="message-bubble">
                        <div class="typing-indicator">
                            <div class="typing-dot"></div>
                            <div class="typing-dot"></div>
                            <div class="typing-dot"></div>
                            ${textHtml}
                        </div>
                    </div>
                </div>`;

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

        // ==========================================
        // FUNGSI UI LAINNYA
        // ==========================================
        function copyText(btn) {
            const messageContent = btn.closest('.message-content');
            const textElement = messageContent.querySelector('.markdown-body');
            if (!textElement) return;
            const textToCopy = textElement.innerText;
            const showSuccess = () => {
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> Disalin';
                btn.style.color = '#4ade80';
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.style.color = '';
                }, 2000);
            };
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(textToCopy).then(showSuccess).catch(() => fallbackCopyText(textToCopy));
            } else {
                fallbackCopyText(textToCopy);
            }

            function fallbackCopyText(text) {
                const textArea = document.createElement("textarea");
                textArea.value = text;
                textArea.style.position = "fixed";
                textArea.style.left = "-9999px";
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                    showSuccess();
                } catch (err) {
                    alert('Gagal menyalin teks.');
                }
                document.body.removeChild(textArea);
            }
        }

        function addCopyButtonsToCodeBlocks() {
            document.querySelectorAll('.markdown-body pre').forEach((pre) => {
                if (pre.previousElementSibling?.classList.contains('code-header')) return;
                const code = pre.querySelector('code');
                if (!code) return;
                let language = 'plaintext';
                const langClass = code.className.match(/language-(\w+)/);
                if (langClass) {
                    language = langClass[1];
                }
                const header = document.createElement('div');
                header.className = 'code-header';
                header.innerHTML =
                    `<span class="code-lang">${language}</span><button class="code-copy-btn" aria-label="Salin kode"><i class="far fa-copy"></i> Salin</button>`;
                pre.parentNode.insertBefore(header, pre);
                pre.style.borderRadius = '0 0 8px 8px';
                pre.style.marginTop = '0';
                header.querySelector('.code-copy-btn').addEventListener('click', (e) => {
                    e.preventDefault();
                    copyCode(e.currentTarget, code);
                });
            });
        }

        function typeWriter(element, markdownText, speed = 12) {
            const html = marked.parse(markdownText);
            const dummy = document.createElement('div');
            dummy.innerHTML = html;
            const plainText = dummy.textContent || dummy.innerText || '';
            let i = 0;
            element.textContent = '';

            function typing() {
                if (i < plainText.length) {
                    element.textContent += plainText.charAt(i);
                    i++;
                    setTimeout(typing, speed);
                } else {
                    element.innerHTML = html;
                    element.querySelectorAll('pre code').forEach((block) => {
                        hljs.highlightElement(block);
                    });
                    addCopyButtonsToCodeBlocks();
                }
            }
            typing();
        }

        function appendMessage(role, text) {
            const isUser = role === 'user';
            const div = document.createElement('div');
            div.className = `message ${isUser ? 'user' : 'ai'}`;
            const bubbleContent = isUser ? text : marked.parse(text);
            const actionsHtml = isUser ? '' :
                `<div class="ai-actions"><button class="action-btn" onclick="copyText(this)"><i class="far fa-copy"></i> Salin</button><button class="action-btn"><i class="far fa-thumbs-up"></i></button><button class="action-btn"><i class="far fa-thumbs-down"></i></button></div>`;
            div.innerHTML =
                `<div class="message-avatar ${isUser ? 'user-avatar-msg' : 'ai-avatar-msg'}"><i class="fas fa-${isUser ? 'user' : 'robot'}"></i></div><div class="message-content"><div class="message-bubble ${isUser ? '' : 'markdown-body'}">${bubbleContent}</div>${actionsHtml}</div>`;
            document.getElementById('messagesContainer').appendChild(div);
            if (!isUser) {
                div.querySelectorAll('pre code').forEach((block) => {
                    hljs.highlightElement(block);
                });
                addCopyButtonsToCodeBlocks();
            }
        }

        function scrollToBottom() {
            const container = document.getElementById('messagesContainer');
            container.scrollTop = container.scrollHeight;
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

        // ===== FUNGSI SHARE SESSION =====
        async function shareSession(id) {
            try {
                // Tampilkan loading kecil
                const btn = event.currentTarget;
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
                    // Copy URL ke Clipboard otomatis
                    navigator.clipboard.writeText(data.url).then(() => {
                        alert("Berhasil! Link chat publik telah disalin ke Clipboard.\n\nLink: " + data.url);
                    }).catch(err => {
                        // Fallback kalau browser ngeblokir clipboard
                        prompt("Gagal menyalin otomatis. Silakan copy link ini manual:", data.url);
                    });
                }

                // Kembalikan tombol
                btn.innerHTML = originalHtml;
                // Tutup menu
                document.getElementById(`menu-${id}`).classList.remove('show');

            } catch (e) {
                alert("Terjadi kesalahan saat membuat link share.");
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

        // ===== EVENT LISTENERS UMUM =====
        const sidebar = document.getElementById('sidebar');
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const mobileToggleBtn = document.getElementById('mobileToggleBtn');
        sidebarToggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
        mobileToggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.toggle('mobile-open');
        });
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768 && !sidebar.contains(e.target)) sidebar.classList.remove('mobile-open');
        });

        const themeToggleItem = document.getElementById('themeToggleItem');
        const themeText = document.getElementById('themeText');
        const body = document.body;
        if (localStorage.getItem('theme') === 'light') {
            body.classList.add('light-mode');
            themeText.innerText = 'Mode Gelap';
        }
        themeToggleItem.addEventListener('click', () => {
            body.classList.toggle('light-mode');
            if (body.classList.contains('light-mode')) {
                localStorage.setItem('theme', 'light');
                themeText.innerText = 'Mode Gelap';
            } else {
                localStorage.setItem('theme', 'dark');
                themeText.innerText = 'Mode Terang';
            }
        });

        const settingsBtn = document.getElementById('settingsBtn');
        const settingsMenu = document.getElementById('settingsMenu');
        settingsBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            settingsMenu.classList.toggle('show');
        });

        function toggleMenu(event, menuId) {
            event.preventDefault();
            event.stopPropagation();
            document.querySelectorAll('.options-menu, .settings-menu-dropdown, .logout-menu').forEach(el => {
                if (el.id !== menuId) el.classList.remove('show');
            });
            document.getElementById(menuId).classList.toggle('show');
        }
        window.addEventListener('click', () => {
            document.querySelectorAll('.options-menu, .settings-menu-dropdown, .logout-menu').forEach(el => el
                .classList.remove('show'));
        });

        // Event Listener Kirim (SUDAH AMAN DARI PARAMETER EVENT)
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

        // Initial Render & Modal Logic
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.markdown-body').forEach(el => {
                const raw = el.textContent.trim();
                if (raw) {
                    el.innerHTML = marked.parse(raw);
                    el.querySelectorAll('pre code').forEach((block) => {
                        hljs.highlightElement(block);
                    });
                }
            });
            addCopyButtonsToCodeBlocks();

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
                if (e.target === modal) modal.classList.remove('show');
            });
        });
    </script>
</body>

</html>

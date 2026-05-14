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

.markdown-body p,
.markdown-body li {
    margin-bottom: 12px;
    white-space: normal !important;
    line-height: 1.6;
    word-wrap: break-word;
}

.markdown-body > * {
    margin-bottom: 12px;
}

.markdown-body *:last-child {
    margin-bottom: 0;
}

.markdown-body h1,
.markdown-body h2,
.markdown-body h3,
.markdown-body h4,
.markdown-body h5,
.markdown-body h6 {
    font-weight: 700; /* Ditebalkan sedikit agar lebih tegas */
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    color: var(--text-primary);
    line-height: 1.3;
}

.markdown-body h1 { font-size: 1.6rem; }
.markdown-body h2 { font-size: 1.4rem; }
.markdown-body h3 { font-size: 1.25rem; }

.markdown-body h4 { font-size: 1.1rem; }
.markdown-body h5 { font-size: 1rem; }
.markdown-body h6 { font-size: 0.95rem; color: var(--text-secondary); }


.markdown-body p strong,
.markdown-body li strong {
    font-weight: 700;
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

    //* FIX KOTAK INPUT TERPENDAM DI BAWAH LAYAR HP */
    .input-container {
        /* Padding: Atas 8px, Kanan 12px, Bawah 25px, Kiri 12px */
        padding: 8px 12px 25px 12px !important;
        /* Jurus Pamungkas: Tambahkan jarak aman otomatis untuk HP modern (iPhone/Android) */
        padding-bottom: calc(25px + env(safe-area-inset-bottom)) !important;
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
/* ====================================================== */
/* FIX MUTLAK 2.0: KOTAK INPUT & SIDEBAR (ANTI TABRAKAN)  */
/* ====================================================== */
@media (max-width: 768px) {
    /* 1. Jadikan Sidebar Kasta Tertinggi agar tidak tertutup */
    .sidebar {
        z-index: 10000 !important;
    }

    .input-container {
        padding-top: 8px !important;
        padding-right: 12px !important;
        padding-left: 12px !important;
        /* Bantalan diubah jadi 45px agar teks peringatan ikut naik */
        padding-bottom: calc(45px + env(safe-area-inset-bottom)) !important;
        position: relative !important;
        z-index: 99 !important; /* Turunkan dari 999 agar tidak menimpa sidebar */
    }

    /* 3. Beri ruang ekstra untuk list chat di atas kotak input */
    .messages-container {
        padding-bottom: calc(160px + env(safe-area-inset-bottom)) !important;
    }
}
/* ===== MULTI-UPLOAD UI ===== */
.multi-file-container {
    display: flex; gap: 10px; overflow-x: auto; padding: 5px 0; margin-bottom: 5px;
    scrollbar-width: thin; scrollbar-color: var(--accent-color) transparent;
}
.multi-file-container::-webkit-scrollbar { height: 4px; }
.multi-file-container::-webkit-scrollbar-thumb { background: var(--accent-color); border-radius: 10px; }
.file-chip {
    display: flex; align-items: center; gap: 8px; padding: 6px 12px;
    background: var(--glass-highlight); border: 1px solid var(--glass-border);
    border-radius: 20px; font-size: 0.8rem; white-space: nowrap; flex-shrink: 0;
    position: relative; overflow: hidden;
}
.file-chip .remove-btn {
    background: rgba(239, 68, 68, 0.2); color: #ef4444; border: none;
    border-radius: 50%; width: 20px; height: 20px; display: flex;
    align-items: center; justify-content: center; cursor: pointer;
}
.file-chip .remove-btn:hover { background: #ef4444; color: white; }
.file-chip.loading { opacity: 0.7; pointer-events: none; }

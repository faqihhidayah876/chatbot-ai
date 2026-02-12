<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SAHAJA AI</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🤖</text></svg>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        /* --- CSS VARIABLES (TETAP SAMA) --- */
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
            --code-text: #e3e3e3;
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
            --code-text: #1f1f1f;
            --inline-code-bg: #e2e8f0;
            --inline-code-text: #d93025;
            background: #ffffff;
        }

        /* --- GLOBAL RESET --- */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--main-bg); color: var(--text-primary); height: 100vh; overflow: hidden; display: flex; transition: background 0.3s, color 0.3s; }
        body::before { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 20% 30%, rgba(37, 99, 235, 0.15) 0%, transparent 70%), radial-gradient(circle at 80% 80%, rgba(6, 182, 212, 0.15) 0%, transparent 70%); z-index: -2; pointer-events: none; }
        body.light-mode::before { background: radial-gradient(circle at 20% 30%, rgba(37, 99, 235, 0.05) 0%, transparent 70%), radial-gradient(circle at 80% 80%, rgba(6, 182, 212, 0.05) 0%, transparent 70%); }
        a { text-decoration: none; color: inherit; }
        button { cursor: pointer; border: none; outline: none; background: none; color: inherit; }

        /* --- SIDEBAR --- */
        .sidebar { width: 280px; background: var(--sidebar-bg); border-right: 1px solid var(--glass-border); display: flex; flex-direction: column; height: 100%; transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); z-index: 50; flex-shrink: 0; overflow: hidden; white-space: nowrap; }

        .sidebar.collapsed { width: 80px; }
        .sidebar.collapsed .text-label, .sidebar.collapsed .brand-text, .sidebar.collapsed .sidebar-footer-details, .sidebar.collapsed .options-btn { display: none !important; opacity: 0; }
        .sidebar.collapsed .sidebar-brand { justify-content: center; padding: 20px 0; flex-direction: column; gap: 15px; }
        .sidebar.collapsed .brand-logo-container { margin-right: 0; }
        .sidebar.collapsed .toggle-btn-sidebar { margin-left: 0; }
        .sidebar.collapsed .new-chat-btn { padding: 12px; justify-content: center; }
        .sidebar.collapsed .history-item-wrapper { justify-content: center; padding: 0 10px; }
        .sidebar.collapsed .history-item { justify-content: center; padding: 12px; }
        .sidebar.collapsed .history-link { justify-content: center; width: 100%; }
        .sidebar.collapsed .history-icon { margin-right: 0; }
        .sidebar.collapsed .sidebar-footer { justify-content: center; }
        .sidebar.collapsed .user-profile { justify-content: center; }

        .sidebar-brand { padding: 24px 20px; display: flex; align-items: center; justify-content: space-between; }
        .brand-logo-container { display: flex; align-items: center; gap: 12px; }
        .brand-icon-bg { width: 40px; height: 40px; background: var(--accent-gradient); border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3); color: white; font-size: 1.2rem; flex-shrink: 0; }
        .brand-text { font-weight: 700; font-size: 1.2rem; letter-spacing: 1px; color: var(--text-primary); }

        .toggle-btn-sidebar { color: var(--text-secondary); width: 36px; height: 36px; border-radius: 8px; transition: 0.2s; display: flex; align-items: center; justify-content: center; }
        .toggle-btn-sidebar:hover { background: var(--glass-highlight); color: var(--text-primary); }

        .new-chat-wrapper { padding: 0 16px 20px; }
        .new-chat-btn { background: var(--accent-gradient); color: white; border-radius: 12px; padding: 12px 20px; font-weight: 600; display: flex; align-items: center; gap: 12px; width: 100%; transition: 0.3s; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); }
        .new-chat-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3); }

        .history-container { flex: 1; overflow-y: auto; padding: 10px 12px; overflow-x: hidden; }
        .history-label { font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 12px; padding-left: 8px; text-transform: uppercase; letter-spacing: 1px; }

        .history-item-wrapper { position: relative; margin-bottom: 4px; display: flex; align-items: center; justify-content: space-between; border-radius: 10px; transition: 0.2s; }
        .history-item-wrapper:hover { background: var(--glass-highlight); }
        .history-item-wrapper.active { background: rgba(37, 99, 235, 0.15); border: 1px solid rgba(37, 99, 235, 0.3); }

        .history-item { padding: 10px 12px; display: flex; align-items: center; color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; flex-grow: 1; min-width: 0; }
        .history-item:hover, .history-item-wrapper.active .history-item { color: var(--text-primary); }
        .history-link { display: flex; align-items: center; width: 100%; overflow: hidden; }
        .history-icon { margin-right: 12px; font-size: 1.1rem; flex-shrink: 0; }
        .history-text { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; }

        .options-btn { opacity: 0; transition: 0.2s; padding: 8px; border-radius: 6px; color: var(--text-secondary); flex-shrink: 0; margin-right: 5px; }
        .history-item-wrapper:hover .options-btn { opacity: 1; }
        .options-btn:hover { background: rgba(255,255,255,0.1); color: var(--text-primary); }

        .options-menu { position: absolute; right: 10px; top: 40px; background: #1e293b; border: 1px solid var(--glass-border); border-radius: 10px; padding: 6px; width: 140px; box-shadow: 0 4px 16px rgba(0,0,0,0.5); z-index: 100; display: none; backdrop-filter: blur(16px); }
        body.light-mode .options-menu { background: #ffffff; color: #333; border-color: #e2e8f0; }
        .options-menu.show { display: block; animation: fadeIn 0.2s ease; }
        .option-item { padding: 8px 12px; font-size: 0.85rem; color: var(--text-primary); display: flex; align-items: center; gap: 10px; border-radius: 6px; cursor: pointer; transition: 0.2s; }
        body.light-mode .option-item { color: #333; }
        .option-item:hover { background: var(--glass-highlight); }
        .option-item.delete { color: var(--danger-color); width: 100%; text-align: left; }

        /* FOOTER & LOGOUT STYLE */
        .sidebar-footer { padding: 20px; border-top: 1px solid var(--glass-border); background: var(--footer-bg); transition: background 0.3s; position: relative; }
        .user-profile { display: flex; align-items: center; gap: 12px; cursor: pointer; border-radius: 12px; padding: 5px; transition: 0.2s; }
        .user-profile:hover { background: var(--glass-highlight); }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--accent-gradient); display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; flex-shrink: 0; }
        body.light-mode .sidebar-footer-details div { color: #334155 !important; }
        body.light-mode .sidebar-footer-details div:last-child { color: #64748b !important; }

        /* Logout Menu */
        .logout-menu {
            position: absolute; bottom: 70px; left: 10px; width: 260px;
            background: #1e293b; border: 1px solid var(--glass-border); border-radius: 12px;
            padding: 6px; display: none; box-shadow: 0 -4px 20px rgba(0,0,0,0.3); z-index: 101;
        }
        body.light-mode .logout-menu { background: #ffffff; border-color: #e2e8f0; color: #333; }
        .logout-menu.show { display: block; animation: fadeIn 0.2s ease; }

        /* --- MAIN CONTENT --- */
        .main-container { flex: 1; display: flex; flex-direction: column; height: 100vh; position: relative; transition: all 0.3s ease; }
        .chat-header { padding: 16px 24px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--glass-border); background: rgba(10, 14, 23, 0.6); backdrop-filter: blur(12px); z-index: 10; }
        body.light-mode .chat-header { background: rgba(255, 255, 255, 0.8); }
        .mobile-toggle-btn { display: none; font-size: 1.2rem; margin-right: 16px; color: var(--text-primary); }
        .chat-title { font-size: 1.2rem; font-weight: 700; display: flex; align-items: center; gap: 10px; color: var(--text-primary); }
        .model-badge { background: rgba(37, 99, 235, 0.15); border: 1px solid var(--accent-color); border-radius: 20px; padding: 4px 12px; font-size: 0.7rem; font-weight: 600; color: var(--accent-color); }

        .settings-container { position: relative; }
        .icon-btn { font-size: 1.2rem; color: var(--text-secondary); transition: 0.2s; padding: 8px; border-radius: 8px; }
        .icon-btn:hover { background: var(--glass-highlight); color: var(--text-primary); }
        .settings-menu-dropdown { position: absolute; right: 0; top: 50px; background: #1e293b; border: 1px solid var(--glass-border); border-radius: 12px; padding: 8px; width: 200px; display: none; box-shadow: 0 4px 20px rgba(0,0,0,0.4); z-index: 101; }
        body.light-mode .settings-menu-dropdown { background: #ffffff; color: #333; }
        .settings-menu-dropdown.show { display: block; }

        /* Chat Area */
        .messages-container { flex: 1; overflow-y: auto; padding: 30px 5%; display: flex; flex-direction: column; gap: 30px; scroll-behavior: smooth; }
        .message { display: flex; gap: 16px; max-width: 100%; animation: slideUp 0.3s ease-out; }
        .message.user { flex-direction: row-reverse; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .message-avatar { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.2); }
        .user-avatar-msg { background: var(--message-user-bg); color: white; }
        .ai-avatar-msg { background: var(--accent-gradient); color: white; }

        .message-content { display: flex; flex-direction: column; max-width: 85%; }
        .message-bubble { padding: 16px 20px; border-radius: 16px; line-height: 1.6; font-size: 0.95rem; position: relative; }
        .user .message-bubble { background: var(--message-user-bg); color: white; border-bottom-right-radius: 4px; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3); }
        .ai .message-bubble { background: transparent; border: none; padding: 0 5px; color: var(--text-primary); box-shadow: none; backdrop-filter: none; }

        /* MARKDOWN FORMATTING */
        .markdown-body { width: 100%; display: block; line-height: 1.7; font-size: 0.95rem; }
        .markdown-body > * { margin-bottom: 16px; }
        .markdown-body p { margin-bottom: 16px; white-space: pre-wrap; }
        .markdown-body h1, .markdown-body h2, .markdown-body h3 { font-weight: 600; margin-top: 24px; margin-bottom: 12px; color: var(--text-primary); }
        .markdown-body h1 { font-size: 1.4rem; }
        .markdown-body h2 { font-size: 1.2rem; }
        .markdown-body ul, .markdown-body ol { margin-bottom: 16px; padding-left: 24px; }
        .markdown-body li { margin-bottom: 6px; }
        .markdown-body pre { background: var(--code-bg) !important; border-radius: 8px; padding: 16px; border: 1px solid var(--glass-border); overflow-x: auto; margin: 16px 0; color: var(--code-text); display: block; }
        .markdown-body code { font-family: 'Roboto Mono', monospace; font-size: 0.9em; }
        .markdown-body p code, .markdown-body li code { background: var(--inline-code-bg); color: var(--inline-code-text); padding: 2px 6px; border-radius: 4px; }
        .markdown-body strong { font-weight: 600; color: var(--accent-color); }
        .markdown-body table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        .markdown-body th, .markdown-body td { border: 1px solid var(--glass-border); padding: 8px 12px; text-align: left; }
        .markdown-body th { background: rgba(37, 99, 235, 0.1); }
        body.light-mode .markdown-body th { background: #f1f5f9; }

        /* --- ANIMASI TYPING 3 TITIK BERGOYANG --- */
        .typing { display: flex; align-items: center; gap: 6px; padding: 8px 0; }
        .typing .dot { width: 8px; height: 8px; background: var(--text-secondary); border-radius: 50%; animation: bounce 1.4s infinite ease-in-out both; }
        .typing .dot:nth-child(1) { animation-delay: -0.32s; }
        .typing .dot:nth-child(2) { animation-delay: -0.16s; }
        @keyframes bounce { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }

        .ai-actions { display: flex; gap: 8px; margin-top: 5px; margin-left: 5px; opacity: 0; transition: 0.3s; }
        .message.ai:hover .ai-actions { opacity: 1; }
        .action-btn { padding: 6px 10px; border-radius: 8px; font-size: 0.85rem; color: var(--text-secondary); background: transparent; border: 1px solid transparent; transition: 0.2s; display: flex; align-items: center; gap: 6px; }
        .action-btn:hover { background: var(--glass-highlight); color: var(--text-primary); border-color: var(--glass-border); }

        .input-container { padding: 16px 24px; background: rgba(10, 14, 23, 0.6); backdrop-filter: blur(12px); border-top: 1px solid var(--glass-border); display: flex; flex-direction: column; align-items: center; gap: 8px; }
        body.light-mode .input-container { background: rgba(255, 255, 255, 0.8); }
        .input-wrapper { width: 100%; max-width: 800px; position: relative; display: flex; align-items: flex-end; background: rgba(30, 41, 59, 0.5); border: 1px solid var(--glass-border); border-radius: 24px; padding: 8px; transition: 0.3s; }
        body.light-mode .input-wrapper { background: #f1f5f9; border-color: #e2e8f0; }
        .input-wrapper:focus-within { border-color: var(--accent-color); box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2); }
        .chat-input { flex: 1; background: transparent; border: none; color: var(--text-primary); font-size: 0.95rem; padding: 10px 14px; resize: none; max-height: 120px; outline: none; }
        .send-btn { width: 42px; height: 42px; background: var(--accent-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; transition: 0.3s; flex-shrink: 0; margin-left: 8px; box-shadow: 0 2px 10px rgba(37, 99, 235, 0.3); }
        .input-footer { font-size: 0.75rem; color: var(--text-secondary); text-align: center; margin-top: 4px; }

        .welcome-screen { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 20px; }
        .welcome-logo { font-size: 5rem; margin-bottom: 20px; background: var(--accent-gradient); -webkit-background-clip: text; color: transparent; filter: drop-shadow(0 4px 12px rgba(37, 99, 235, 0.3)); }
        .welcome-title { font-size: 2.2rem; font-weight: 700; color: var(--text-primary); margin-bottom: 10px; }

        @media (max-width: 768px) {
            .sidebar { position: fixed; left: 0; top: 0; height: 100%; transform: translateX(-100%); z-index: 99; width: 280px !important; transition: transform 0.3s ease; }
            .sidebar.mobile-open { transform: translateX(0); box-shadow: 10px 0 30px rgba(0,0,0,0.5); }
            .toggle-btn-sidebar { display: none; }
            .mobile-toggle-btn { display: block; }
            .main-container { width: 100%; }
        }
    </style>
</head>
<body>

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
            @foreach($sessions as $session)
            <div class="history-item-wrapper" id="session-{{ $session->id }}">
                <a href="{{ route('chat.show', $session->id) }}" class="history-item {{ isset($currentSession) && $currentSession->id == $session->id ? 'active' : '' }}">
                    <i class="far fa-comment-dots history-icon"></i>
                    <div class="history-link">
                        <span class="history-text text-label" id="title-{{ $session->id }}">{{ $session->title ?? 'Chat Baru' }}</span>
                    </div>
                </a>
                <button class="options-btn" onclick="toggleMenu(event, 'menu-{{ $session->id }}')">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <div class="options-menu" id="menu-{{ $session->id }}">
                    <div class="option-item" onclick="renameSession({{ $session->id }})"><i class="fas fa-pen"></i> Ganti Nama</div>
                    <div class="option-item delete" onclick="deleteSession({{ $session->id }})"><i class="fas fa-trash"></i> Hapus</div>
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
                    <div style="font-weight: 600; color: var(--text-primary);">{{ Auth::user()->name ?? 'Pengguna' }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-secondary);">Premium Plan</div>
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
                    <span class="model-badge">Powered by: Gemini Flash 2.5</span>
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
                <p style="color: var(--text-secondary); font-size: 1.1rem;">Halo, {{ Auth::user()->name ?? 'Teman' }}. Apa yang bisa saya bantu?</p>
            </div>
        </div>

        <div class="messages-container" id="messagesContainer" style="{{ count($chats) == 0 ? 'display: none;' : '' }}">
            @foreach($chats as $chat)
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
                            <button class="action-btn" onclick="copyText(this)"><i class="far fa-copy"></i> Salin</button>
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
                <button class="send-btn" id="sendButton"><i class="fas fa-paper-plane"></i></button>
            </div>
            <div class="input-footer">SAHAJA AI dapat membuat kesalahan, periksa lebih lanjut</div>
        </div>
    </div>

    <script>
        let currentSessionId = "{{ $currentSession ? $currentSession->id : '' }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        marked.setOptions({ breaks: true, gfm: true });

        // --- THEME LOGIC ---
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

        // --- SIDEBAR TOGGLE ---
        const sidebar = document.getElementById('sidebar');
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const mobileToggleBtn = document.getElementById('mobileToggleBtn');
        sidebarToggleBtn.addEventListener('click', () => { sidebar.classList.toggle('collapsed'); });
        mobileToggleBtn.addEventListener('click', (e) => { e.stopPropagation(); sidebar.classList.toggle('mobile-open'); });
        document.addEventListener('click', (e) => { if (window.innerWidth <= 768 && !sidebar.contains(e.target)) sidebar.classList.remove('mobile-open'); });

        // --- MENU TOGGLE (Settings, Options, Logout) ---
        const settingsBtn = document.getElementById('settingsBtn');
        const settingsMenu = document.getElementById('settingsMenu');
        settingsBtn.addEventListener('click', (e) => { e.stopPropagation(); settingsMenu.classList.toggle('show'); });

        function toggleMenu(event, menuId) {
            event.preventDefault(); event.stopPropagation();
            document.querySelectorAll('.options-menu, .settings-menu-dropdown, .logout-menu').forEach(el => { if(el.id !== menuId) el.classList.remove('show'); });
            document.getElementById(menuId).classList.toggle('show');
        }
        window.addEventListener('click', () => { document.querySelectorAll('.options-menu, .settings-menu-dropdown, .logout-menu').forEach(el => el.classList.remove('show')); });

        // --- FITUR SALIN DENGAN FALLBACK (ROBUST) ---
        function copyText(btn) {
            // Cari elemen teks
            const messageContent = btn.closest('.message-content');
            const textElement = messageContent.querySelector('.markdown-body');

            if (!textElement) return;

            const textToCopy = textElement.innerText; // Teks murni

            // Fungsi feedback sukses
            const showSuccess = () => {
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> Disalin';
                btn.style.color = '#4ade80'; // Warna hijau
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.style.color = '';
                }, 2000);
            };

            // 1. Coba modern Clipboard API
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(textToCopy)
                    .then(showSuccess)
                    .catch(() => fallbackCopyText(textToCopy));
            } else {
                // 2. Jika tidak support / bukan HTTPS, pakai fallback
                fallbackCopyText(textToCopy);
            }

            // Fallback manual (textarea + execCommand)
            function fallbackCopyText(text) {
                const textArea = document.createElement("textarea");
                textArea.value = text;
                textArea.style.position = "fixed";
                textArea.style.left = "-9999px";
                textArea.style.top = "0";
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();

                try {
                    document.execCommand('copy');
                    showSuccess();
                } catch (err) {
                    alert('Gagal menyalin teks. Browser Anda membatasi akses clipboard.');
                }

                document.body.removeChild(textArea);
            }
        }

        // --- RENAME SESSION ---
        async function renameSession(id) {
            const newName = prompt("Nama baru:");
            if (newName) {
                try {
                    await fetch(`/session/${id}/rename`, {
                        method: 'PUT',
                        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken},
                        body: JSON.stringify({title: newName})
                    });
                    document.getElementById(`title-${id}`).innerText = newName;
                } catch(e) {}
            }
        }

        // --- DELETE SESSION ---
        async function deleteSession(id) {
            if (confirm("Hapus chat ini?")) {
                try {
                    await fetch(`/session/${id}/delete`, {
                        method: 'DELETE',
                        headers: {'X-CSRF-TOKEN': csrfToken}
                    });
                    document.getElementById(`session-${id}`).remove();
                    if(currentSessionId == id) window.location.href = "/chat";
                } catch(e) {}
            }
        }

        // --- RENDER MARKDOWN UNTUK CHAT YANG SUDAH ADA ---
        document.querySelectorAll('.markdown-body').forEach(el => {
            const raw = el.textContent.trim();
            if(raw) el.innerHTML = marked.parse(raw);
        });

        // --- AUTO RESIZE TEXTAREA ---
        const chatInput = document.getElementById('chatInput');
        chatInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // --- FUNGSI KIRIM PESAN ---
        async function sendMessage() {
            const message = chatInput.value.trim();
            if (!message) return;
            document.getElementById('welcomeScreen').style.display = 'none';
            document.getElementById('messagesContainer').style.display = 'flex';
            chatInput.value = '';
            chatInput.style.height = 'auto';
            appendMessage('user', message);
            const loadingId = appendLoading(); // Menampilkan animasi 3 titik
            scrollToBottom();
            try {
                const response = await fetch("{{ route('chat.send') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken },
                    body: JSON.stringify({ message: message, session_id: currentSessionId })
                });
                const data = await response.json();
                document.getElementById(loadingId).remove(); // Hapus indikator loading
                appendMessage('ai', data.ai_response);
                scrollToBottom();
                if (!currentSessionId && data.session_id) window.location.href = `/chat/${data.session_id}`;
            } catch (error) {
                document.getElementById(loadingId).remove();
                alert("Gagal mengirim.");
            }
        }

        // --- APPEND PESAN (USER / AI) ---
        function appendMessage(role, text) {
            const isUser = role === 'user';
            const div = document.createElement('div');
            div.className = `message ${isUser ? 'user' : 'ai'}`;
            const actionsHtml = isUser ? '' : `
                <div class="ai-actions">
                    <button class="action-btn" onclick="copyText(this)"><i class="far fa-copy"></i> Salin</button>
                    <button class="action-btn"><i class="far fa-thumbs-up"></i></button>
                    <button class="action-btn"><i class="far fa-thumbs-down"></i></button>
                </div>`;
            div.innerHTML = `
                <div class="message-avatar ${isUser ? 'user-avatar-msg' : 'ai-avatar-msg'}"><i class="fas fa-${isUser ? 'user' : 'robot'}"></i></div>
                <div class="message-content">
                    <div class="message-bubble ${isUser ? '' : 'markdown-body'}">${isUser ? text : marked.parse(text)}</div>
                    ${actionsHtml}
                </div>`;
            document.getElementById('messagesContainer').appendChild(div);
        }

        // --- APPEND INDIKATOR LOADING (ANIMASI 3 TITIK) ---
        function appendLoading() {
            const id = 'loading-' + Date.now();
            const div = document.createElement('div');
            div.id = id;
            div.className = 'message ai';
            div.innerHTML = `
                <div class="message-avatar ai-avatar-msg"><i class="fas fa-robot"></i></div>
                <div class="message-content">
                    <div class="message-bubble">
                        <div class="typing">
                            <div class="dot"></div>
                            <div class="dot"></div>
                            <div class="dot"></div>
                        </div>
                    </div>
                </div>`;
            document.getElementById('messagesContainer').appendChild(div);
            return id;
        }

        // --- SCROLL KE BAWAH ---
        function scrollToBottom() {
            const container = document.getElementById('messagesContainer');
            container.scrollTop = container.scrollHeight;
        }

        // --- EVENT LISTENER ---
        document.getElementById('sendButton').addEventListener('click', sendMessage);
        chatInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    </script>
</body>
</html>

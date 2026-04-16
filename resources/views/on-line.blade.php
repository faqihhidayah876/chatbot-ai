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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            --footer-bg: rgba(15, 23, 42, 0.5);
            --danger-color: #ef4444;
        }

        body.light-mode {
            --main-bg: #ffffff;
            --sidebar-bg: #f8fafc;
            --glass-border: #e2e8f0;
            --glass-highlight: #f1f5f9;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --footer-bg: #f1f5f9;
        }

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

        .sidebar.collapsed .new-chat-btn,
        .sidebar.collapsed .history-item-wrapper,
        .sidebar.collapsed .history-item,
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

        .brand-text {
            font-weight: 700;
            font-size: 1.2rem;
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

        .history-item {
            padding: 10px 12px;
            display: flex;
            align-items: center;
            color: var(--text-secondary);
            font-size: 0.9rem;
            flex-grow: 1;
            min-width: 0;
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
        }

        body.light-mode .options-menu {
            background: #ffffff;
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

        .option-item:hover {
            background: var(--glass-highlight);
        }

        .option-item.delete {
            color: var(--danger-color);
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid var(--glass-border);
            background: var(--footer-bg);
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
        }

        .logout-menu.show {
            display: block;
            animation: fadeIn 0.2s ease;
        }

        /* ===== MODAL PENGATURAN (DARI FASE 1) ===== */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 100000;
            backdrop-filter: blur(5px);
        }

        .modal-overlay.show {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

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

        .modal-close {
            position: absolute;
            top: 15px;
            right: 20px;
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--text-secondary);
            cursor: pointer;
            z-index: 10;
        }

        .modal-close:hover {
            color: var(--text-primary);
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

        .settings-sidebar {
            width: 220px;
            background: rgba(10, 14, 23, 0.4);
            padding: 20px 10px;
            border-right: 1px solid var(--glass-border);
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        body.light-mode .settings-sidebar {
            background: rgba(241, 245, 249, 0.5);
        }

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

        .nav-btn:hover {
            background: var(--glass-highlight);
            color: var(--text-primary);
        }

        .nav-btn.active {
            background: var(--glass-highlight);
            color: var(--accent-color);
        }

        .settings-content {
            padding: 30px;
            flex: 1;
            overflow-y: auto;
        }

        .tab-pane {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .tab-pane.active {
            display: block;
        }

        .theme-btn {
            padding: 15px;
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            flex: 1;
            background: transparent;
            color: var(--text-primary);
            font-weight: 600;
            transition: 0.2s;
        }

        .theme-btn.active {
            border-color: var(--accent-color);
            background: rgba(37, 99, 235, 0.1);
        }

        .github-input {
            width: 100%;
            padding: 10px 15px;
            background: var(--main-bg);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            border-radius: 8px;
            outline: none;
        }

        .github-submit-btn {
            background: var(--accent-gradient);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
        }

        /* ===== MAIN CONTENT (TIMELINE FASE 2) ===== */
        .main-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: relative;
            overflow-y: auto;
        }

        .timeline-header {
            padding: 20px 5%;
            border-bottom: 1px solid var(--glass-border);
            background: rgba(10, 14, 23, 0.8);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        body.light-mode .timeline-header {
            background: rgba(255, 255, 255, 0.8);
        }

        .timeline-header h2 {
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mobile-toggle-btn {
            display: none;
        }

        .compose-box {
            display: flex;
            gap: 15px;
            padding: 20px 5%;
            border-bottom: 1px solid var(--glass-border);
            background: var(--sidebar-bg);
        }

        .avatar-circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--accent-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            flex-shrink: 0;
        }

        .compose-input {
            width: 100%;
            background: transparent;
            border: none;
            color: var(--text-primary);
            font-size: 1rem;
            resize: none;
            outline: none;
            padding: 10px 0;
            border-bottom: 1px solid transparent;
            transition: 0.3s;
        }

        .compose-input:focus {
            border-bottom-color: var(--accent-color);
        }

        .post-btn {
            background: var(--accent-gradient);
            color: white;
            padding: 8px 24px;
            border-radius: 30px;
            font-weight: 600;
            transition: 0.3s;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .post-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
        }

        .feed-container {
            padding-bottom: 80px;
        }

        .post-card {
            display: flex;
            gap: 15px;
            padding: 20px 5%;
            border-bottom: 1px solid var(--glass-border);
            transition: 0.2s;
        }

        /* Efek hover dimatikan (hanya ubah warna latar tipis, tanpa geser) */
        .post-card:hover {
            background: var(--glass-highlight);
        }

        .post-content {
            flex: 1;
        }

        .post-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            flex-wrap: wrap;
        }

        .post-name {
            font-weight: 600;
            color: var(--text-primary);
        }

        .post-time {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .post-body {
            font-size: 0.95rem;
            line-height: 1.6;
            color: var(--text-primary);
            white-space: pre-wrap;
        }

        .post-actions {
            display: flex;
            gap: 30px;
            margin-top: 15px;
        }

        .action-btn {
            background: transparent;
            color: var(--text-secondary);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.2s;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .action-btn.like-btn:hover {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }

        .action-btn.comment-btn:hover {
            color: var(--accent-color);
            background: rgba(37, 99, 235, 0.1);
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
            background: rgba(30, 41, 59, 0.95);
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.3s ease forwards;
            backdrop-filter: blur(8px);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

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

        @media (max-width: 768px) {
            .sidebar { position: fixed; left: 0; top: 0; height: 100%; transform: translateX(-100%); z-index: 99; width: 280px !important; transition: transform 0.3s ease; }
            .sidebar.mobile-open { transform: translateX(0); box-shadow: 10px 0 30px rgba(0, 0, 0, 0.5); }
            .toggle-btn-sidebar { display: none; }
            .mobile-toggle-btn { display: block; background: transparent; border: none; font-size: 1.4rem; color: var(--text-primary); margin-right: 15px; }

            /* PERBAIKAN POP-UP SETTINGS UNTUK HP */
            .settings-modal-box { flex-direction: column; height: 85vh; width: 95%; }
            .settings-sidebar { width: 100%; border-right: none; border-bottom: 1px solid var(--glass-border); flex-direction: row; padding: 10px; overflow-x: auto; white-space: nowrap; flex-shrink: 0; }
            .settings-sidebar h3 { display: none; } /* Sembunyikan tulisan 'Pengaturan' biar lega */
            .nav-btn { padding: 8px 12px; font-size: 0.85rem; }
            .settings-content { padding: 15px; overflow-y: auto; }
            .profile-upload { flex-direction: column; text-align: center; }
            .mobile-toggle-btn {
                display: block;
                background: transparent;
                border: none;
                font-size: 1.4rem;
                color: var(--text-primary);
                margin-right: 15px;
            }
        }

    </style>
</head>

<body>

    <div id="toast-container"></div>

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
            <a href="{{ route('online.index') }}" class="new-chat-btn"
                style="background: transparent; border: 1px solid var(--accent-color); color: var(--text-primary); text-decoration: none; justify-content: center;">
                <i class="fas fa-globe" style="color: var(--accent-color);"></i> <span
                    class="btn-text text-label">SAHAJA Connect</span>
            </a>
        </div>
        <div class="history-container">
            <div class="history-label text-label">Riwayat</div>
            @foreach ($sessions as $session)
                <div class="history-item-wrapper" id="session-{{ $session->id }}">
                    <a href="{{ route('chat.show', $session->id) }}" class="history-item">
                        <i class="far fa-comment-dots history-icon"></i>
                        <div class="history-link">
                            <span class="history-text text-label" id="title-{{ $session->id }}">{{ $session->title ?? 'Chat Baru' }}</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="sidebar-footer">
            <div class="user-profile" onclick="toggleMenu(event, 'logout-menu')">
                @if (Auth::user()->avatar)
                    <img src="{{ Auth::user()->avatar }}" class="user-avatar" style="object-fit: cover;">
                @else
                    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</div>
                @endif
                <div class="sidebar-footer-details text-label" style="margin-left: 10px;">
                    <div style="font-weight: 600; color: var(--text-primary);">{{ Auth::user()->name ?? 'Pengguna' }}
                    </div>
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
        <div class="timeline-header">
            <div style="display: flex; align-items: center;">
                <button class="mobile-toggle-btn" id="mobileToggleBtn"><i class="fas fa-bars"></i></button>
                <h2><i class="fas fa-globe" style="color: var(--accent-color);"></i> SAHAJA Connect</h2>
            </div>
            <div style="display: flex; align-items: center; gap: 15px;">
                <p
                    style="font-size: 0.9rem; color: var(--text-secondary); display: none; @media(min-width: 768px){display: block;}">
                    Forum resmi pengguna.</p>
                <button onclick="openSettingsModal()"
                    style="font-size: 1.2rem; color: var(--text-secondary); transition: 0.2s;"><i
                        class="fas fa-cog"></i></button>
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
                                    <button onclick="openConfirmModal('Hapus Postingan?', 'Postingan ini akan hilang dari linimasa SAHAJA Connect.', 'deletePost', {{ $post->id }})" class="action-btn" style="color: var(--danger-color); opacity: 0.7;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.7'">
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

                    <div class="comments-section" id="comments-section-{{ $post->id }}" style="display: none; margin-left: 60px; padding-top: 15px; border-top: 1px dashed var(--glass-border);">

                        @if($post->comments && $post->comments->count() > 0)
                            <div class="comments-list" style="max-height: 250px; overflow-y: auto; margin-bottom: 15px; display: flex; flex-direction: column; gap: 12px; padding-right: 5px;">
                                @foreach($post->comments as $comment)
                                    <div class="comment-item" style="display: flex; gap: 10px; background: var(--glass-highlight); padding: 12px; border-radius: 12px;">

                                        @if($comment->user)
                                            @if($comment->user->avatar)
                                                <img src="{{ $comment->user->avatar }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
                                            @else
                                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--accent-gradient); display: flex; align-items: center; justify-content: center; font-size: 0.75rem; color: white; font-weight: bold; flex-shrink: 0;">
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

                    <div
                        style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--glass-border); padding-bottom: 15px; margin-bottom: 15px;">
                        <div>
                            <strong style="display: block;">Tautan yang dibagikan</strong>
                            <span style="font-size: 0.8rem; color: var(--text-secondary);">Kelola percakapan yang Anda
                                bagikan.</span>
                        </div>
                        <button class="github-submit-btn"
                            style="background: transparent; color: var(--text-primary); border: 1px solid var(--glass-border);">Kelola</button>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong style="display: block;">Hapus semua obrolan</strong>
                            <span style="font-size: 0.8rem; color: var(--text-secondary);">Tindakan ini tidak dapat
                                dibatalkan.</span>
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
                        Versi Beta 3.5<br>
                        Dibuat oleh: Faqih Hidayah
                    </div>
                </div>
            </div>
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
        };
        window.closeSettingsModal = function() { document.getElementById('settingsModal').classList.remove('show'); };

        window.switchTab = function(tabId) {
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.nav-btn').forEach(el => el.classList.remove('active'));
            document.getElementById('tab-' + tabId).classList.add('active');
            event.currentTarget.classList.add('active');
        };

        window.setTheme = function(mode) {
            const isLight = mode === 'light'; document.body.classList.toggle('light-mode', isLight); localStorage.setItem('theme', isLight ? 'light' : 'dark');
            document.getElementById('btnThemeLight').classList.toggle('active', isLight); document.getElementById('btnThemeDark').classList.toggle('active', !isLight);
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
        if (localStorage.getItem('theme') === 'light') document.body.classList.add('light-mode');
        document.getElementById('sidebarToggleBtn')?.addEventListener('click', e => { e.stopPropagation(); document.getElementById('sidebar').classList.toggle('collapsed'); });
        document.getElementById('mobileToggleBtn')?.addEventListener('click', e => { e.stopPropagation(); document.getElementById('sidebar').classList.toggle('mobile-open'); });

        window.addEventListener('click', e => {
            if (window.innerWidth <= 768 && !document.getElementById('sidebar').contains(e.target) && !e.target.closest('.mobile-toggle-btn')) document.getElementById('sidebar').classList.remove('mobile-open');
            if (!e.target.closest('.settings-modal-box')) document.querySelectorAll('.options-menu, .logout-menu').forEach(el => el.classList.remove('show'));
        });
    </script>
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
    <script>
        // ==========================================
        // 1. VARIABEL GLOBAL
        // ==========================================
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let pendingAvatarBase64 = null;
        let targetActionId = null;
        let targetActionType = '';

        // ==========================================
        // 2. FUNGSI TOAST (BADGE NOTIFIKASI)
        // ==========================================
        function showToast(message, type = 'info') {
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
        }

        // ==========================================
        // 3. KENDALI MODAL CUSTOM (UI)
        // ==========================================
        function closeCustomModal(modalId) {
            const m = document.getElementById(modalId);
            if (m) m.classList.remove('show');
        }

        function openConfirmModal(title, text, type, id = null) {
            targetActionType = type;
            targetActionId = id;
            document.getElementById('dangerModalTitle').innerText = title;
            document.getElementById('dangerModalText').innerText = text;
            document.getElementById('confirmDangerModal').classList.add('show');
            document.querySelectorAll('.options-menu, .logout-menu').forEach(el => el.classList.remove('show'));
        }

        function toggleMenu(e, id) {
            if (e) { e.preventDefault(); e.stopPropagation(); }
            const targetMenu = document.getElementById(id);
            if (!targetMenu) return;
            const isShown = targetMenu.classList.contains('show');
            document.querySelectorAll('.options-menu, .logout-menu').forEach(el => el.classList.remove('show'));
            if (!isShown) targetMenu.classList.add('show');
        }

        // ==========================================
        // 4. MANAJEMEN SESSION (RENAME, DELETE, SHARE)
        // ==========================================
        function renameSession(id) {
            targetActionId = id;
            document.getElementById('renameInput').value = document.getElementById(`title-${id}`).innerText;
            document.getElementById('renameRoomModal').classList.add('show');
            document.getElementById(`menu-${id}`)?.classList.remove('show');
        }

        function deleteSession(id) { openConfirmModal("Hapus Percakapan?", "Percakapan ini akan dihapus secara permanen.", "deleteRoom", id); }
        function clearAllChats() { openConfirmModal("Hapus Semua Obrolan?", "Seluruh riwayat chat Anda di semua percakapan akan musnah. Ini tidak dapat dibatalkan.", "clearAllChats"); }

        async function shareSession(id) {
            try {
                const response = await fetch(`/session/${id}/share`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken } });
                const data = await response.json();
                if (data.success) {
                    document.getElementById('shareLinkInput').value = data.url;
                    document.getElementById('shareModal').classList.add('show');
                }
            } catch(e) { showToast("Gagal membuat link", "error"); }
            document.getElementById(`menu-${id}`)?.classList.remove('show');
        }

        function copyShareLink() {
            const input = document.getElementById('shareLinkInput');
            input.select(); document.execCommand("copy");
            showToast("Tautan berhasil disalin!", "success");
            closeCustomModal('shareModal');
        }

        // ==========================================
        // 5. EKSEKUSI TOMBOL MODAL (OTAK DATABASE)
        // ==========================================
        async function executeRename() {
            const newName = document.getElementById('renameInput').value.trim();
            if(!newName) return showToast("Nama tidak boleh kosong", "error");
            try {
                await fetch(`/session/${targetActionId}/rename`, { method: 'PUT', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ title: newName }) });
                document.getElementById(`title-${targetActionId}`).innerText = newName;
                closeCustomModal('renameRoomModal'); // TUTUP MODAL DULU
                showToast("Nama berhasil diubah", "success"); // BARU MUNCULKAN TOAST
            } catch(e) { showToast("Gagal mengganti nama", "error"); }
        }

        async function executeDangerAction() {
            closeCustomModal('confirmDangerModal');
            showToast("Memproses...", "info");

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
                    const res = await fetch(`/online/${targetActionId}/delete`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken } });
                    const data = await res.json();
                    if(data.success) {
                        document.getElementById(`post-${targetActionId}`)?.remove();
                        showToast("Postingan dihapus", "success");
                    } else showToast("Gagal menghapus", "error");

                } else if (targetActionType === 'deleteAvatar') {
                    await fetch('/profile/update', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ avatar: null }) });
                    showToast("Foto profil dihapus", "success");
                    setTimeout(() => window.location.reload(), 1000);
                }
            } catch(e) { showToast("Terjadi kesalahan server", "error"); }
        }

        // ==========================================
        // 6. SETTINGS MODAL & PROFIL (Avatar, Tema)
        // ==========================================
        function openSettingsModal() {
            document.getElementById('settingsModal').classList.add('show');
            document.getElementById('logout-menu')?.classList.remove('show');
            const isLight = document.body.classList.contains('light-mode');
            document.getElementById('btnThemeLight').classList.toggle('active', isLight);
            document.getElementById('btnThemeDark').classList.toggle('active', !isLight);
        }

        function closeSettingsModal() { document.getElementById('settingsModal').classList.remove('show'); }

        function switchTab(tabId) {
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.nav-btn').forEach(el => el.classList.remove('active'));
            document.getElementById('tab-' + tabId).classList.add('active');
            event.currentTarget.classList.add('active');
        }

        function setTheme(mode) {
            const isLight = mode === 'light';
            document.body.classList.toggle('light-mode', isLight);
            localStorage.setItem('theme', isLight ? 'light' : 'dark');
            document.getElementById('btnThemeLight').classList.toggle('active', isLight);
            document.getElementById('btnThemeDark').classList.toggle('active', !isLight);
            showToast("Tema berhasil diubah", "success");
        }

        document.getElementById('avatarInput')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(event) {
                const img = new Image();
                img.src = event.target.result;
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    const MAX = 200;
                    let w = img.width; let h = img.height;
                    if (w > h) { if (w > MAX) { h *= MAX / w; w = MAX; } }
                    else { if (h > MAX) { w *= MAX / h; h = MAX; } }
                    canvas.width = w; canvas.height = h;
                    canvas.getContext('2d').drawImage(img, 0, 0, w, h);

                    pendingAvatarBase64 = canvas.toDataURL('image/jpeg', 0.8);
                    document.getElementById('previewAvatar').src = pendingAvatarBase64;
                    showToast("Foto siap. Jangan lupa klik tombol 'Simpan'.", "info");
                }
            }
            reader.readAsDataURL(file);
        });

        async function simpanProfil() {
            const newName = document.getElementById('inputNamaProfil').value.trim();
            if(!newName) return showToast("Nama tidak boleh kosong!", "error");
            const payload = { name: newName };
            if (pendingAvatarBase64 !== null) payload.avatar = pendingAvatarBase64;

            try {
                const res = await fetch('/profile/update', {
                    method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify(payload)
                });
                const data = await res.json();
                if(data.success) {
                    showToast("Profil berhasil diperbarui!", "success");
                    setTimeout(() => window.location.reload(), 1000);
                }
            } catch(e) { showToast("Gagal menyimpan profil", "error"); }
        }

        // ==========================================
        // 7. FUNGSI POST & LIKE (KHUSUS ONLINE)
        // ==========================================
        async function submitPost() {
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
                if(data.success) {
                    showToast("Berhasil diposting!", "success");
                    setTimeout(() => window.location.reload(), 800);
                } else throw new Error(data.message);
            } catch(e) { showToast("Gagal mengirim postingan", "error"); }
            finally { btn.innerText = "Kirim Postingan"; }
        }

        async function toggleLike(postId, btnElement) {
            try {
                const response = await fetch(`/online/${postId}/like`, { method: "POST", headers: { "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" } });
                const data = await response.json();
                const icon = btnElement.querySelector('i'); const countSpan = btnElement.querySelector('.like-count');
                let currentCount = parseInt(countSpan.innerText);
                if (data.status === 'liked') { icon.className = 'fas fa-heart'; btnElement.style.color = '#ef4444'; countSpan.innerText = currentCount + 1; }
                else { icon.className = 'far fa-heart'; btnElement.style.color = 'var(--text-secondary)'; countSpan.innerText = currentCount - 1; }
            } catch(e) { showToast("Gagal memproses like", "error"); }
        }

        // ==========================================
        // 8. EVENT LISTENER BAWAAN HALAMAN
        // ==========================================
        if (localStorage.getItem('theme') === 'light') document.body.classList.add('light-mode');

        document.getElementById('sidebarToggleBtn')?.addEventListener('click', e => { e.stopPropagation(); document.getElementById('sidebar').classList.toggle('collapsed'); });
        document.getElementById('mobileToggleBtn')?.addEventListener('click', e => { e.stopPropagation(); document.getElementById('sidebar').classList.toggle('mobile-open'); });

        window.addEventListener('click', e => {
            if (window.innerWidth <= 768 && !document.getElementById('sidebar').contains(e.target) && !e.target.closest('.mobile-toggle-btn')) document.getElementById('sidebar').classList.remove('mobile-open');
            if (!e.target.closest('.settings-modal-box')) document.querySelectorAll('.options-menu, .logout-menu').forEach(el => el.classList.remove('show'));
        });

        // ==========================================
        // 9. LOGIKA KOMENTAR FASE 2 (YANG SEMPAT HILANG)
        // ==========================================

        function toggleComment(postId) {
            const section = document.getElementById(`comments-section-${postId}`);
            if (!section) return;

            if (section.style.display === 'none' || section.style.display === '') {
                section.style.display = 'block';
                // Beri sedikit delay agar animasi render dulu sebelum autofokus
                setTimeout(() => {
                    const input = document.getElementById(`comment-input-${postId}`);
                    if(input) input.focus();
                }, 100);
            } else {
                section.style.display = 'none';
            }
        }

        async function submitComment(postId) {
            const input = document.getElementById(`comment-input-${postId}`);
            const text = input.value.trim();

            if (!text) return showToast("Komentar tidak boleh kosong!", "error");

            // Ubah icon tombol jadi loading pas diklik
            const btn = input.nextElementSibling;
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            try {
                const res = await fetch(`/online/${postId}/comment`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ body: text })
                });

                const data = await res.json();

                if (data.success) {
                    showToast("Komentar terkirim!", "success");
                    input.value = '';
                    // Reload untuk merender komentar baru
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    showToast("Gagal mengirim komentar", "error");
                    btn.innerHTML = originalIcon;
                }
            } catch (e) {
                showToast("Terjadi kesalahan server", "error");
                btn.innerHTML = originalIcon;
            }
        }
    </script>
</body>

</html>

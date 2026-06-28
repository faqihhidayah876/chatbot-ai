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
        <a href="{{ route('sahaja-llm.index') }}" class="new-chat-btn" style="background: transparent; border: 1px solid #10b981; color: var(--text-primary); text-decoration: none; justify-content: center; margin-top: 10px;">
            <i class="fas fa-book-reader" style="color: #10b981;"></i> <span class="btn-text text-label">SAHAJA LLM</span>
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

    <!-- ========================================== -->
    <!-- WIDGET BANNER PRODUK BARU -->
    <!-- ========================================== -->
    <div id="sahaja-product-banner" class="sahaja-banner-container">
        <div class="banner-header">
            <span class="banner-title text-label">New product launched</span>
            <button id="close-banner-btn" class="close-btn" aria-label="Close banner">&times;</button>
        </div>
        <p class="banner-subtitle text-label">
            Click to enter and explore more model capabilities.
        </p>
        <div class="banner-buttons">
            <a href="https://sistem-deteksi-penyakit-daun.vercel.app" target="_blank" class="banner-btn">
                🌿 Disease Leaves Early Detection
            </a>
            <a href="https://explainable-ai-blush.vercel.app" target="_blank" class="banner-btn">
                🗣️ Fatigue Detection System
            </a>
        </div>
    </div>
    <!-- ========================================== -->

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

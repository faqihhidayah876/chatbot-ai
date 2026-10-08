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
        <a href="{{ route('chat.new') }}" class="new-chat-btn" aria-label="Percakapan baru">
            <i class="fas fa-plus" style="font-size: 16px;"></i>
            <span class="btn-text text-label">Percakapan Baru</span>
        </a>
        <a href="{{ route('online.index') }}" class="new-chat-btn secondary" aria-label="SAHAJA Connect">
            <i class="fas fa-globe" style="font-size: 16px;"></i>
            <span class="btn-text text-label">SAHAJA Connect</span>
        </a>
        <a href="{{ route('sahaja-llm.index') }}" class="new-chat-btn secondary" aria-label="SAHAJA Workspace">
            <i class="fas fa-book-open" style="font-size: 16px;"></i>
            <span class="btn-text text-label">SAHAJA LLM</span>
        </a>
    </div>

    {{-- History --}}
    <div class="history-container">
        <div class="history-label text-label">Riwayat</div>
        
        {{-- Search Input --}}
        <div class="history-search">
            <i class="fas fa-magnifying-glass history-search-icon" aria-hidden="true"></i>
            <input 
                type="text" 
                id="historySearchInput" 
                class="history-search-input"
                placeholder="Cari percakapan..." 
                autocomplete="off"
                aria-label="Cari riwayat percakapan">
            <button 
                type="button" 
                id="historySearchClear"
                class="history-search-clear"
                aria-label="Hapus pencarian"
                style="display: none;">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        
        {{-- Container untuk history items --}}
        <div class="history-items" id="historyItems">
            @foreach ($sessions as $session)
                <div class="history-item-wrapper{{ isset($currentSession) && $currentSession->id == $session->id ? ' active' : '' }}"
                    id="session-{{ $session->id }}"
                    data-title="{{ strtolower($session->title ?? 'Chat Baru') }}">
                    <a href="{{ route('chat.show', $session->id) }}"
                        class="history-item"
                        aria-label="{{ $session->title ?? 'Chat Baru' }}">
                        <div class="history-link">
                            <span class="history-text text-label"
                                id="title-{{ $session->id }}">{{ $session->title ?? 'Chat Baru' }}</span>
                        </div>
                    </a>
                    <button class="options-btn" onclick="toggleMenu(event, 'menu-{{ $session->id }}')"
                        aria-label="Opsi percakapan">
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
        
        {{-- Empty state (muncul kalau tidak ada hasil) --}}
        <div class="history-empty" id="historyEmpty" style="display: none;">
            <i class="fas fa-magnifying-glass"></i>
            <p>Tidak ada percakapan yang cocok</p>
            <span>Coba kata kunci lain</span>
        </div>
    </div>

    {{-- Product Banner --}}
    <div id="sahaja-product-banner" class="sahaja-banner-container">
        <div class="banner-header">
            <span class="banner-title text-label">Project lainnya</span>
            <button id="close-banner-btn" class="close-btn" aria-label="Tutup banner">
                <i class="fas fa-xmark" style="font-size: 14px;"></i>
            </button>
        </div>
        <p class="banner-subtitle text-label">
            Jelajahi project AI lain yang telah dikembangkan.
        </p>
        <div class="banner-buttons">
            <a href="https://sistem-deteksi-penyakit-daun.vercel.app" target="_blank" rel="noopener" class="banner-btn">
                <img src="https://i.ibb.co.com/FkTcv772/Picsart-26-06-29-00-33-18-415.png" alt="Logo" class="btn-logo-micro">
                Leaf Disease Detection
            </a>
            <a href="https://explainable-ai-blush.vercel.app" target="_blank" rel="noopener" class="banner-btn">
                <img src="https://i.ibb.co.com/Rpkm30y0/logo-datmin.png" alt="Logo" class="btn-logo-micro">
                Fatigue Detection System
            </a>
        </div>
    </div>

    {{-- User Profile Footer --}}
    <div class="sidebar-footer">
        <div class="user-profile" onclick="toggleMenu(event, 'logout-menu')" role="button" tabindex="0"
            aria-label="Menu pengguna">
            @if(Auth::user()->avatar)
                <img src="{{ Auth::user()->avatar }}"
                    class="user-avatar"
                    style="object-fit: cover; width: 32px; height: 32px;"
                    alt="{{ Auth::user()->name }}">
            @else
                <div class="user-avatar" aria-hidden="true">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
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
            <button class="option-item" onclick="openHelpModal()">
                <i class="fas fa-circle-question"></i> Bantuan
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

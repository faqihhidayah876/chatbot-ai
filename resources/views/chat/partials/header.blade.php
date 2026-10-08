<div class="chat-header">
    <div style="display: flex; align-items: center;">
        <button class="mobile-toggle-btn" id="mobileToggleBtn" aria-label="Buka sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <div class="chat-title">
            <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="Logo SAHAJA AI"
                style="width: 24px; height: 24px; border-radius: 6px; object-fit: contain; flex-shrink: 0;">
            SAHAJA AI
        </div>
    </div>
    <div class="header-actions">
        @if(isset($currentSession) && $currentSession)
            {{-- Tombol Export (hanya muncul kalau ada session aktif) --}}
            <div class="export-dropdown-wrapper">
                <button class="icon-btn" id="exportBtn" 
                    onclick="toggleHeaderExportMenu(event)"
                    aria-label="Export percakapan" 
                    title="Export Percakapan">
                    <i class="fas fa-download" style="font-size: 18px;"></i>
                </button>
                <div class="header-export-menu" id="headerExportMenu">
                    <button class="option-item" onclick="exportSession('markdown')">
                        <i class="fas fa-file-lines"></i> Export ke Markdown (.md)
                    </button>
                    <button class="option-item" onclick="exportSession('json')">
                        <i class="fas fa-file-code"></i> Export ke JSON (.json)
                    </button>
                </div>
            </div>
        @endif
        
        <button class="icon-btn" id="settingsBtn" onclick="openSettingsModal()"
            aria-label="Buka pengaturan" title="Pengaturan">
            <i class="fas fa-gear" style="font-size: 18px;"></i>
        </button>
    </div>
</div>

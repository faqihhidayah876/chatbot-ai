{{-- Custom PWA Install Prompt --}}
<div id="pwaInstallPrompt" class="pwa-install-prompt" style="display: none;">
    <div class="pwa-prompt-content">
        <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" 
            alt="SAHAJA AI" 
            class="pwa-prompt-logo">
        <div class="pwa-prompt-text">
            <strong>Install SAHAJA AI</strong>
            <span>Akses lebih cepat dari home screen Anda</span>
        </div>
        <div class="pwa-prompt-actions">
            <button class="pwa-btn-dismiss" onclick="dismissInstallPrompt()">
                Nanti
            </button>
            <button class="pwa-btn-install" onclick="triggerInstallPrompt()">
                Install
            </button>
        </div>
    </div>
</div>

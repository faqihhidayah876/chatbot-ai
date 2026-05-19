<div class="input-container">
    <div class="input-wrapper" style="position: relative;">

        <button id="scrollToBottomBtn" onclick="scrollToBottomSmooth()" title="Ke pesan terbaru">
            <i class="fas fa-chevron-down"></i>
        </button>

        <div id="multiFileContainer" class="multi-file-container" style="display: none;"></div>

        <input type="file" id="fileInput" accept=".pdf,image/png,image/jpeg,image/webp" multiple
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
                    <button type="button" class="icon-action-btn" id="modelSelectButton" title="Pilih Model AI">
                        <i class="fas fa-magic" id="currentModelIcon"></i>
                    </button>

                    <div class="attach-menu" id="modelMenu" style="width: 280px;">
                        <div class="option-item model-option"
                            style="background: var(--glass-highlight); align-items: flex-start;"
                            onclick="selectModelMode('auto', 'fa-magic')">
                            <i class="fas fa-magic" style="color: #3b82f6; margin-top: 4px; width: 20px;"></i>
                            <div style="display: flex; flex-direction: column; gap: 2px;">
                                <strong style="font-size: 0.95rem; color: var(--text-primary);">Mode Otomatis</strong>
                                <span style="font-size: 0.75rem; color: var(--text-secondary); line-height: 1.3;">Sistem
                                    menentukan berdasarkan prompt anda</span>
                            </div>
                        </div>
                        <div class="option-item model-option" style="align-items: flex-start;"
                            onclick="selectModelMode('fast', 'fa-bolt')">
                            <i class="fas fa-bolt" style="color: #f59e0b; margin-top: 4px; width: 20px;"></i>
                            <div style="display: flex; flex-direction: column; gap: 2px;">
                                <strong style="font-size: 0.95rem; color: var(--text-primary);">Mode Cepat</strong>
                                <span style="font-size: 0.75rem; color: var(--text-secondary); line-height: 1.3;">Cepat
                                    & akurat dengan Mistral Small 4</span>
                            </div>
                        </div>
                        <div class="option-item model-option" style="align-items: flex-start;"
                            onclick="selectModelMode('smart', 'fa-brain')">
                            <i class="fas fa-brain" style="color: #ec4899; margin-top: 4px; width: 20px;"></i>
                            <div style="display: flex; flex-direction: column; gap: 2px;">
                                <strong style="font-size: 0.95rem; color: var(--text-primary);">Mode Cerdas</strong>
                                <span
                                    style="font-size: 0.75rem; color: var(--text-secondary); line-height: 1.3;">Bernalar
                                    tajam dengan Minimax 2.7</span>
                            </div>
                        </div>
                        <div class="option-item model-option" style="align-items: flex-start;"
                            onclick="selectModelMode('coding', 'fa-code')">
                            <i class="fas fa-code" style="color: #a855f7; margin-top: 4px; width: 20px;"></i>
                            <div style="display: flex; flex-direction: column; gap: 2px;">
                                <strong style="font-size: 0.95rem; color: var(--text-primary);">Mode Coding</strong>
                                <span
                                    style="font-size: 0.75rem; color: var(--text-secondary); line-height: 1.3;">Konteks
                                    besar dengan Qwen 3 Coder</span>
                            </div>
                        </div>
                        <div class="option-item model-option" style="align-items: flex-start;"
                            onclick="selectModelMode('alpha', 'fa-atom')">
                            <i class="fas fa-atom" style="color: #ef4444; margin-top: 4px; width: 20px;"></i>
                            <div style="display: flex; flex-direction: column; gap: 2px;">
                                <strong style="font-size: 0.95rem; color: var(--text-primary);">Mode Alpha</strong>
                                <span style="font-size: 0.75rem; color: var(--text-secondary); line-height: 1.3;">Deep
                                    Research Agent</span>
                            </div>
                        </div>
                        <div class="option-item model-option" style="align-items: flex-start;"
                            onclick="selectModelMode('imagen', 'fa-paint-brush')">
                            <i class="fas fa-paint-brush" style="color: #f43f5e; margin-top: 4px; width: 20px;"></i>
                            <div style="display: flex; flex-direction: column; gap: 2px;">
                                <strong style="font-size: 0.95rem; color: var(--text-primary);">Sahaja Imagen</strong>
                                <span
                                    style="font-size: 0.75rem; color: var(--text-secondary); line-height: 1.3;">Generator
                                    Gambar AI (Nano-banana-2)</span>
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

<div class="input-container">
    <div class="input-wrapper" style="position: relative;">

        {{-- Scroll to bottom button --}}
        <button id="scrollToBottomBtn" onclick="scrollToBottomSmooth()" title="Ke pesan terbaru"
            aria-label="Gulir ke bawah" style="display: none; align-items: center; justify-content: center;">
            <i class="fas fa-chevron-down" style="font-size: 14px;"></i>
        </button>

        {{-- Multi file chips --}}
        <div id="multiFileContainer" class="multi-file-container" style="display: none;"></div>

        {{-- Hidden file input --}}
        <input type="file" id="fileInput" accept=".pdf,image/png,image/jpeg,image/webp" multiple
            style="display: none;">

        {{-- Textarea --}}
        <textarea class="chat-input" id="chatInput"
            placeholder="Ketik pesan di sini..." rows="1"
            aria-label="Input pesan"></textarea>

        {{-- Action row --}}
        <div class="input-actions-wrapper">
            {{-- Left: Attach + Model selector --}}
            <div class="action-left">

                {{-- Attach button --}}
                <div style="position: relative;">
                    <button type="button" class="icon-action-btn" id="attachButton"
                        aria-label="Lampirkan file" title="Lampirkan File">
                        <i class="fas fa-paperclip" style="font-size: 16px;"></i>
                    </button>

                    <div class="attach-menu" id="attachMenu">
                        <button class="option-item" id="btnUploadImage">
                            <i class="fas fa-image" style="font-size: 14px;"></i>
                            Analisis Gambar (OCR)
                        </button>
                        <button class="option-item" id="btnUploadDoc">
                            <i class="fas fa-file-pdf" style="font-size: 14px;"></i>
                            File (PDF / DOCX)
                        </button>
                        <button class="option-item" id="btnUploadGithub">
                            <i class="fas fa-code-branch" style="font-size: 14px;"></i>
                            Link GitHub (Beta)
                        </button>
                    </div>
                </div>

                {{-- Model selector --}}
                <div style="position: relative;">
                    <button type="button" class="icon-action-btn" id="modelSelectButton"
                        aria-label="Pilih mode AI" title="Pilih Mode AI">
                        <i class="fas fa-chevron-down" id="currentModelIcon" style="font-size: 14px;"></i>
                    </button>

                    <div class="attach-menu" id="modelMenu" style="width: 260px;">
                        <button class="option-item model-option"
                            style="background: var(--bg-hover);"
                            onclick="selectModelMode('auto', 'fa-chevron-down')">
                            <i class="fas fa-chevron-down" style="font-size: 14px; color: var(--text-secondary); margin-top: 2px; width: 16px;"></i>
                            <div style="display: flex; flex-direction: column; gap: 2px;">
                                <strong style="font-size: 14px; color: var(--text-primary);">Mode Otomatis</strong>
                                <span style="font-size: 12px; color: var(--text-tertiary); line-height: 1.4;">Sistem menentukan berdasarkan prompt</span>
                            </div>
                        </button>
                        <button class="option-item model-option"
                            onclick="selectModelMode('fast', 'fa-bolt')">
                            <i class="fas fa-bolt" style="font-size: 14px; color: var(--text-secondary); margin-top: 2px; width: 16px;"></i>
                            <div style="display: flex; flex-direction: column; gap: 2px;">
                                <strong style="font-size: 14px; color: var(--text-primary);">Mode Cepat</strong>
                                <span style="font-size: 12px; color: var(--text-tertiary); line-height: 1.4;">Jawaban lebih cepat</span>
                            </div>
                        </button>
                        <button class="option-item model-option"
                            onclick="selectModelMode('smart', 'fa-microchip')">
                            <i class="fas fa-microchip" style="font-size: 14px; color: var(--accent); margin-top: 2px; width: 16px;"></i>
                            <div style="display: flex; flex-direction: column; gap: 2px;">
                                <strong style="font-size: 14px; color: var(--text-primary);">Mode Cerdas</strong>
                                <span style="font-size: 12px; color: var(--text-tertiary); line-height: 1.4;">Bernalar lebih tajam</span>
                            </div>
                        </button>
                        <button class="option-item model-option"
                            onclick="selectModelMode('coding', 'fa-code')">
                            <i class="fas fa-code" style="font-size: 14px; color: var(--text-secondary); margin-top: 2px; width: 16px;"></i>
                            <div style="display: flex; flex-direction: column; gap: 2px;">
                                <strong style="font-size: 14px; color: var(--text-primary);">Mode Coding</strong>
                                <span style="font-size: 12px; color: var(--text-tertiary); line-height: 1.4;">Konteks kode lebih besar</span>
                            </div>
                        </button>
                        <button class="option-item model-option"
                            onclick="selectModelMode('alpha', 'fa-circle-nodes')">
                            <i class="fas fa-circle-nodes" style="font-size: 14px; color: var(--accent); margin-top: 2px; width: 16px;"></i>
                            <div style="display: flex; flex-direction: column; gap: 2px;">
                                <strong style="font-size: 14px; color: var(--text-primary);">Mode Alpha</strong>
                                <span style="font-size: 12px; color: var(--text-tertiary); line-height: 1.4;">Deep Research mendalam</span>
                            </div>
                        </button>
                        <button class="option-item model-option"
                            onclick="selectModelMode('imagen', 'fa-image')">
                            <i class="fas fa-image" style="font-size: 14px; color: var(--text-secondary); margin-top: 2px; width: 16px;"></i>
                            <div style="display: flex; flex-direction: column; gap: 2px;">
                                <strong style="font-size: 14px; color: var(--text-primary);">Sahaja Imagen</strong>
                                <span style="font-size: 12px; color: var(--text-tertiary); line-height: 1.4;">Buat gambar dari teks</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Right: Voice + Send --}}
            <div class="action-right">
                <button type="button" class="icon-action-btn voice-btn" id="voiceButton"
                    aria-label="Rekam suara" title="Bicara dengan SAHAJA">
                    <i class="fas fa-microphone" style="font-size: 16px;"></i>
                </button>
                <button type="button" class="send-btn" id="sendButton" aria-label="Kirim pesan" title="Kirim">
                    <i class="fas fa-arrow-up" style="font-size: 14px;"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="input-footer">SAHAJA AI dapat membuat kesalahan — selalu periksa informasi penting</div>
</div>

<div class="messages-container" id="messagesContainer"
    style="{{ count($chats) == 0 ? 'display: none;' : '' }}">
    @foreach ($chats as $chat)
        <div class="message user">
            <div class="message-avatar user-avatar-msg"><i class="fas fa-user"></i></div>
            <div class="message-content">
                @php
                    // FILTER KHUSUS PDF: Menyembunyikan teks panjang dari layar
                    $displayMsg = $chat->user_message;
                    if (strpos($displayMsg, '[Lampiran Dokumen: ') === 0) {
                        preg_match('/\[Lampiran Dokumen: (.*?)\]/', $displayMsg, $match);
                        $fName = $match[1] ?? 'Dokumen';
                        $pos = strrpos($displayMsg, 'Instruksi User: ');
                        $inst = $pos !== false ? trim(substr($displayMsg, $pos + 16)) : '';
                        $displayMsg = '📎 [' . $fName . "]\n" . $inst;
                    }
                @endphp
                <div class="message-bubble">{{ $displayMsg }}</div>
            </div>
        </div>
        <div class="message ai">
            <div class="message-avatar ai-avatar-msg" style="background: transparent; padding: 0;">
                <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="AI"
                    style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 1px solid var(--border-color);">
            </div>
            <div class="message-content">
                <div class="message-bubble markdown-body ai-raw-data" style="display: none;">
                    {{ $chat->ai_response }}</div>
                <div class="message-bubble markdown-body ai-rendered-data"></div>
                <div class="ai-actions" style="position: relative; display: flex; gap: 5px; align-items: center;">
                    <button class="action-btn" onclick="copyText(this)"><i class="far fa-copy"></i> Salin</button>

                    <div class="export-dropdown-container">
                        <button class="action-btn" onclick="toggleExportMenu(this)"><i class="fas fa-ellipsis-v"></i></button>
                        <div class="export-menu" style="display: none; position: absolute; bottom: 100%; left: 0; background: var(--sidebar-bg); border: 1px solid var(--glass-border); border-radius: 8px; padding: 5px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); z-index: 50; width: 140px; margin-bottom: 5px;">
                            <div class="option-item" style="font-size: 0.8rem; padding: 6px 10px;" onclick="exportToDoc(this)"><i class="fas fa-file-word" style="color: #3b82f6;"></i> Ekspor ke DOCS</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

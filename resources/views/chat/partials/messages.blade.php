<div class="messages-container" id="messagesContainer" style="{{ count($chats) == 0 ? 'display: none;' : '' }}">
    @foreach ($chats as $chat)
        <div class="message user">
            <div class="message-avatar user-avatar-msg" style="padding:0; overflow:hidden;">
                <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=2563eb&color=fff' }}"
                    style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="message-content">
                @php
                    $displayMsg = $chat->user_message;

                    // FILTER 1: PDF DARI CHAT BIASA
                    if (preg_match('/\[Dokumen \d+: (.*?)\]\n"""\n.*?\n"""\n\n/s', $displayMsg, $match)) {
                        $pos = strrpos($displayMsg, 'Instruksi User: ');
                        $inst = $pos !== false ? trim(substr($displayMsg, $pos + 16)) : '';
                        $displayMsg = '📎 [' . $match[1] . "]\n" . $inst;
                    }
                    // FILTER 2: [BARU!] TEKS DARI SAHAJA LLM WORKSPACE
                    elseif (strpos($displayMsg, '[REFERENSI DOKUMEN]') !== false) {
                        // Cari nama-nama file yang disisipkan
                        preg_match_all('/\[File: (.*?)\]/', $displayMsg, $fileMatches);
                        $fileNames = !empty($fileMatches[1]) ? implode(', ', $fileMatches[1]) : 'Dokumen Workspace';

                        // Cari instruksi user-nya
                        $pos = strpos($displayMsg, 'Pertanyaan/Instruksi User: ');
                        $inst = $pos !== false ? trim(substr($displayMsg, $pos + 27)) : 'Instruksi LLM';

                        // Hapus tag HTML <b> jika ada sisa dari tombol Studio
                        $inst = strip_tags($inst);

                        $displayMsg = '📎 [' . $fileNames . "]\n" . $inst;
                    }
                    // FILTER 3: GITHUB
                    elseif (strpos($displayMsg, '📦 [GitHub:') === 0) {
                        if (preg_match('/(📦 \[GitHub: .*?\]).*?\[PERTANYAAN USER\]: (.*)/s', $displayMsg, $match)) {
                            $displayMsg = $match[1] . "\n\n" . trim($match[2]);
                        } else {
                            $lines = explode("\n", $displayMsg);
                            $displayMsg = $lines[0] . "\n\n" . end($lines);
                        }
                    }
                @endphp
                <div class="message-bubble">{{ $displayMsg }}</div>
            </div>
        </div>

        <div class="message ai">
            <div class="message-avatar ai-avatar-msg"
                style="background: transparent; padding: 0; border: 1px solid var(--glass-border); overflow:hidden;">
                <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="AI"
                    style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="message-content">
                <div class="message-bubble markdown-body ai-raw-data" style="display: none;">{{ $chat->ai_response }}
                </div>
                <div class="message-bubble markdown-body ai-rendered-data"></div>

                <div class="ai-actions" style="position: relative; display: flex; gap: 5px; align-items: center;">
                    <button class="action-btn" onclick="copyText(this)"><i class="far fa-copy"></i> Salin</button>

                    <div class="export-dropdown-container">
                        <button class="action-btn" onclick="toggleExportMenu(this)"><i
                                class="fas fa-ellipsis-v"></i></button>
                        <div class="export-menu"
                            style="display: none; position: absolute; bottom: 100%; left: 0; background: var(--sidebar-bg); border: 1px solid var(--glass-border); border-radius: 8px; padding: 5px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); z-index: 50; width: 140px; margin-bottom: 5px;">
                            <div class="option-item" style="font-size: 0.8rem; padding: 6px 10px;"
                                onclick="exportToDoc(this)"><i class="fas fa-file-word" style="color: #3b82f6;"></i>
                                Ekspor ke DOCS</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

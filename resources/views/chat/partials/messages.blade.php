<div class="messages-container" id="messagesContainer" style="{{ count($chats) == 0 ? 'display: none;' : '' }}">
    @foreach ($chats as $chat)
        {{-- User message --}}
        <div class="message user">
            <div class="message-avatar user-avatar-msg" style="padding:0; overflow:hidden;">
                <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=3b82f6&color=fff&size=64' }}"
                    style="width: 100%; height: 100%; object-fit: cover;"
                    alt="{{ Auth::user()->name }}">
            </div>
            <div class="message-content">
                @php
                    $displayMsg = $chat->user_message;

                    // FILTER 1: PDF dari chat biasa
                    if (preg_match('/\[Dokumen \d+: (.*?)\]\n"""\n.*?\n"""\n\n/s', $displayMsg, $match)) {
                        $pos = strrpos($displayMsg, 'Instruksi User: ');
                        $inst = $pos !== false ? trim(substr($displayMsg, $pos + 16)) : '';
                        $displayMsg = '[Lampiran: ' . $match[1] . "]\n" . $inst;
                    }
                    // FILTER 2: Teks dari SAHAJA LLM Workspace
                    elseif (strpos($displayMsg, '[REFERENSI DOKUMEN]') !== false) {
                        preg_match_all('/\[File: (.*?)\]/', $displayMsg, $fileMatches);
                        $fileNames = !empty($fileMatches[1]) ? implode(', ', $fileMatches[1]) : 'Dokumen Workspace';
                        $pos = strpos($displayMsg, 'Pertanyaan/Instruksi User: ');
                        $inst = $pos !== false ? trim(substr($displayMsg, $pos + 27)) : 'Instruksi LLM';
                        $inst = strip_tags($inst);
                        $displayMsg = '[Lampiran: ' . $fileNames . "]\n" . $inst;
                    }
                    // FILTER 3: GitHub
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

        {{-- AI message --}}
        <div class="message ai">
            <div class="message-avatar ai-avatar-msg">
                <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="SAHAJA AI"
                    style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="message-content">
                {{-- Hidden raw data for JS rendering --}}
                <div class="message-bubble markdown-body ai-raw-data" style="display: none;">{{ $chat->ai_response }}</div>
                {{-- Rendered markdown --}}
                <div class="message-bubble markdown-body ai-rendered-data"></div>

                {{-- Action buttons --}}
                <div class="ai-actions" style="position: relative;">
                    <button class="action-btn" onclick="copyText(this)" aria-label="Salin teks">
                        <i class="far fa-copy" style="font-size: 14px;"></i> Salin
                    </button>

                    <div class="export-dropdown-container">
                        <button class="action-btn" onclick="toggleExportMenu(this)" aria-label="Ekspor opsi">
                            <i class="fas fa-ellipsis" style="font-size: 14px;"></i>
                        </button>
                        <div class="export-menu">
                            <button class="option-item" style="font-size: 13px;" onclick="exportToDoc(this)">
                                <i class="fas fa-file-word" style="font-size: 14px;"></i>
                                Ekspor ke DOCX
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obrolan Publik - SAHAJA AI</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🤖</text></svg>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        /* CSS Disederhanakan untuk Public View */
        :root {
            --main-bg: #0a0e17; --text-primary: #f1f5f9; --text-secondary: #94a3b8;
            --accent-color: #2563eb; --accent-gradient: linear-gradient(135deg, #2563eb, #06b6d4);
            --message-user-bg: linear-gradient(135deg, #2563eb, #1d4ed8);
            --glass-border: rgba(98, 160, 234, 0.15);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--main-bg); color: var(--text-primary); display: flex; flex-direction: column; min-height: 100vh; }
        body::before {
            content: ''; position: fixed; inset: 0;
            background: radial-gradient(circle at 20% 30%, rgba(37, 99, 235, 0.15) 0%, transparent 70%),
                        radial-gradient(circle at 80% 80%, rgba(6, 182, 212, 0.15) 0%, transparent 70%);
            z-index: -2; pointer-events: none;
        }

        .public-container { max-width: 800px; width: 100%; margin: 0 auto; display: flex; flex-direction: column; flex: 1; border-left: 1px solid var(--glass-border); border-right: 1px solid var(--glass-border); background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(10px); }
        .header { padding: 20px; border-bottom: 1px solid var(--glass-border); text-align: center; }
        .header-title { font-size: 1.5rem; font-weight: bold; color: var(--text-primary); display: flex; align-items: center; justify-content: center; gap: 10px; }
        .header-topic { font-size: 0.9rem; color: var(--text-secondary); margin-top: 5px; }
        .badge-public { font-size: 0.7rem; background: rgba(6, 182, 212, 0.2); border: 1px solid #06b6d4; color: #06b6d4; padding: 3px 8px; border-radius: 12px; vertical-align: middle; }

        .messages-container { flex: 1; padding: 30px 20px; display: flex; flex-direction: column; gap: 30px; overflow-y: auto; }
        .message { display: flex; gap: 15px; }
        .message.user { flex-direction: row-reverse; }
        .message-avatar { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .user-avatar-msg { background: var(--message-user-bg); color: white; }
        .ai-avatar-msg { background: var(--accent-gradient); color: white; }
        .message-content { max-width: 85%; }

        .message-bubble { padding: 15px 20px; border-radius: 16px; line-height: 1.6; font-size: 0.95rem; white-space: pre-wrap; }
        .user .message-bubble { background: var(--message-user-bg); color: white; border-bottom-right-radius: 4px; }

        /* Markdown Styles */
        .markdown-body { width: 100%; display: block; overflow-x: auto; white-space: normal; line-height: 1.7; }
        .markdown-body > * { margin-bottom: 16px; }
        .markdown-body p { margin-bottom: 15px; white-space: pre-wrap; }
        .markdown-body h1, .markdown-body h2, .markdown-body h3 { font-weight: 600; margin-top: 24px; margin-bottom: 12px; color: var(--text-primary); }

        .markdown-body ul, .markdown-body ol { margin-bottom: 16px; padding-left: 24px; }
        .markdown-body li { margin-bottom: 6px; }

        .markdown-body pre { background: #282c34 !important; border-radius: 8px; padding: 15px; margin: 15px 0; overflow-x: auto; border: 1px solid var(--glass-border); display: block; max-width: 100%; }
        .markdown-body code { font-family: 'Roboto Mono', monospace; font-size: 0.9em; color: #e3e3e3; }

        .footer { padding: 20px; text-align: center; border-top: 1px solid var(--glass-border); font-size: 0.85rem; color: var(--text-secondary); }
        .footer a { color: var(--accent-color); text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="public-container">
        <div class="header">
            <div class="header-title">
                <i class="fas fa-robot"></i> SAHAJA AI
                <span class="badge-public">Public Read-Only</span>
            </div>
            <div class="header-topic">Topik: "{{ $session->title ?? 'Tanpa Judul' }}"</div>
        </div>

        <div class="messages-container">
            @foreach ($chats as $chat)
                <div class="message user">
                    <div class="message-avatar user-avatar-msg"><i class="fas fa-user"></i></div>
                    <div class="message-content">
                        @php
                            $rawMsg = trim($chat->user_message);

                            // Gunakan Regex (tambah flag /i untuk case-insensitive dan /s untuk multiline)
                            // Pola ini akan mendeteksi bracket meski ada spasi/enter di dalamnya
                            if (preg_match('/\[\s*Lampiran Dokumen\s*:\s*(.*?)\s*\]/is', $rawMsg, $match)) {
                                $fName = !empty($match[1]) ? trim($match[1]) : 'Dokumen';

                                // Ambil semua teks SETELAH kurung siku penutup ']'
                                $parts = explode(']', $rawMsg, 2);
                                $inst = isset($parts[1]) ? trim($parts[1]) : '';

                                // Bersihkan kata "Instruksi User:" jika ada di awal teks (case-insensitive)
                                $inst = preg_replace('/^Instruksi User\s*:\s*/is', '', $inst);

                                $displayMsg = "📎 [" . $fName . "]\n\n" . $inst;
                            } else {
                                // Jika tidak ada lampiran, tampilkan pesan apa adanya
                                $displayMsg = $rawMsg;
                            }
                        @endphp

                        {{-- Gunakan nl2br(e()) untuk menjamin \n berubah menjadi <br> di HTML dengan aman --}}
                        <div class="message-bubble">{!! nl2br(e($displayMsg)) !!}</div>
                        <div class="message-bubble">{{ $displayMsg }}</div>
                    </div>
                </div>
                <div class="message ai">
                    <div class="message-avatar ai-avatar-msg"><i class="fas fa-robot"></i></div>
                    <div class="message-content">
                        <div class="message-bubble markdown-body raw-content" style="display: none;">{{ $chat->ai_response }}</div>
                        <div class="message-bubble markdown-body rendered-content"></div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="footer">
            Dibagikan melalui <a href="/">SAHAJA AI</a>.
            Mulai obrolan pintarmu sendiri sekarang!
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script>
        marked.setOptions({ sanitize: true, breaks: true, gfm: true });

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.message.ai').forEach(el => {
                const rawDiv = el.querySelector('.raw-content');
                const renderDiv = el.querySelector('.rendered-content');
                if (rawDiv && renderDiv) {
                    const rawText = rawDiv.textContent.trim();
                    renderDiv.innerHTML = marked.parse(rawText);
                    renderDiv.querySelectorAll('pre code').forEach((block) => {
                        hljs.highlightElement(block);
                    });
                }
            });
        });
    </script>
</body>
</html>

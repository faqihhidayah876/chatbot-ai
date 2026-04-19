<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obrolan Publik - SAHAJA AI</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/mermaid@10.8.0/dist/mermaid.min.js"></script>
    <link rel="icon" type="image/png" href="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Roboto+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/html-docx-js@0.3.1/dist/html-docx.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* CSS Disederhanakan untuk Public View */
        :root {
            --main-bg: #0a0e17;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --accent-color: #2563eb;
            --accent-gradient: linear-gradient(135deg, #2563eb, #06b6d4);
            --message-user-bg: linear-gradient(135deg, #2563eb, #1d4ed8);
            --glass-border: rgba(98, 160, 234, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--main-bg);
            color: var(--text-primary);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(circle at 20% 30%, rgba(37, 99, 235, 0.15) 0%, transparent 70%),
                radial-gradient(circle at 80% 80%, rgba(6, 182, 212, 0.15) 0%, transparent 70%);
            z-index: -2;
            pointer-events: none;
        }

        .public-container {
            max-width: 800px;
            width: 100%;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            flex: 1;
            border-left: 1px solid var(--glass-border);
            border-right: 1px solid var(--glass-border);
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(10px);
        }

        .header {
            padding: 20px;
            border-bottom: 1px solid var(--glass-border);
            text-align: center;
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .header-topic {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-top: 5px;
        }

        .badge-public {
            font-size: 0.7rem;
            background: rgba(6, 182, 212, 0.2);
            border: 1px solid #06b6d4;
            color: #06b6d4;
            padding: 3px 8px;
            border-radius: 12px;
            vertical-align: middle;
        }

        .messages-container {
            flex: 1;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            gap: 30px;
            overflow-y: auto;
        }

        .message {
            display: flex;
            gap: 15px;
        }

        .message.user {
            flex-direction: row-reverse;
        }

        .message-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .user-avatar-msg {
            background: var(--message-user-bg);
            color: white;
        }

        .ai-avatar-msg {
            background: var(--accent-gradient);
            color: white;
        }

        .message-content {
            min-width: 0 !important;
            max-width: calc(100% - 50px) !important;
            /* Agar tidak mendobrak layar */
        }

        .message-bubble {
            padding: 15px 20px;
            border-radius: 16px;
            line-height: 1.6;
            font-size: 0.95rem;
            white-space: pre-wrap;
            word-wrap: break-word !important;
            overflow-wrap: anywhere !important;
            word-break: break-word !important;
        }

        .user .message-bubble {
            background: var(--message-user-bg);
            color: white;
            border-bottom-right-radius: 4px;
        }

        /* Markdown Styles */
        .markdown-body {
            width: 100%;
            display: block;
            overflow-x: auto;
            white-space: normal;
            line-height: 1.7;
        }

        .markdown-body>* {
            margin-bottom: 16px;
        }

        .markdown-body p {
            margin-bottom: 15px;
            white-space: pre-wrap;
        }

        .markdown-body h1,
        .markdown-body h2,
        .markdown-body h3 {
            font-weight: 600;
            margin-top: 24px;
            margin-bottom: 12px;
            color: var(--text-primary);
        }

        .markdown-body ul,
        .markdown-body ol {
            margin-bottom: 16px;
            padding-left: 24px;
        }

        .markdown-body li {
            margin-bottom: 6px;
        }

        .markdown-body pre {
            background: #282c34 !important;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            overflow-x: auto;
            border: 1px solid var(--glass-border);
            display: block;
            max-width: 100%;
        }

        .markdown-body code {
            font-family: 'Roboto Mono', monospace;
            font-size: 0.9em;
            color: #e3e3e3;
        }

        /* ===== TABEL MARKDOWN ===== */
        .markdown-body table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            margin-bottom: 16px;
            display: block;
            overflow-x: auto;
            /* Biar tabel bisa di-scroll ke kanan di HP */
            border-radius: 8px;
            border: 1px solid var(--glass-border);
        }

        .markdown-body table th,
        .markdown-body table td {
            padding: 10px 16px;
            border: 1px solid var(--glass-border);
            text-align: left;
            font-size: 0.9rem;
        }

        .markdown-body table th {
            background-color: rgba(37, 99, 235, 0.15);
            /* Biru transparan elegan */
            font-weight: 600;
            color: var(--accent-color);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .markdown-body table tr {
            background-color: transparent;
            transition: background-color 0.2s;
        }

        .markdown-body table tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.02);
            /* Efek belang-belang */
        }

        .markdown-body table tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
            /* Efek nyala saat di-hover */
        }

        /* Penyesuaian Tabel untuk Light Mode */
        body.light-mode .markdown-body table th {
            background-color: #e2e8f0;
            color: #1e293b;
        }

        body.light-mode .markdown-body table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        body.light-mode .markdown-body table tr:hover {
            background-color: #f1f5f9;
        }

        .footer {
            padding: 20px;
            text-align: center;
            border-top: 1px solid var(--glass-border);
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .footer a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: bold;
        }

        /* ===== THINKING MODE UI (WAJIB ADA BIAR TERBUNGKUS) ===== */
        .thinking-container {
            margin-bottom: 15px;
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            overflow: hidden;
            background: rgba(0, 0, 0, 0.15);
        }

        .thinking-header {
            padding: 10px 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: var(--text-secondary);
            background: rgba(255, 255, 255, 0.05);
            user-select: none;
            transition: 0.2s;
        }

        .thinking-header:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
        }

        .thinking-content {
            display: none;
            padding: 12px;
            font-size: 0.85rem;
            color: var(--text-secondary);
            border-top: 1px solid var(--glass-border);
            white-space: pre-wrap;
            font-style: italic;
            line-height: 1.6;
        }

        .thinking-content.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        /* ===== MERMAID DIAGRAM UI ===== */
        .mermaid-wrapper {
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            margin: 15px 0;
            overflow: hidden;
        }

        .mermaid-header {
            display: flex;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.05);
            padding: 8px 15px;
            align-items: center;
            border-bottom: 1px solid var(--glass-border);
        }

        .mermaid-tabs {
            display: flex;
            gap: 10px;
        }

        .mermaid-tab {
            background: transparent;
            color: var(--text-secondary);
            border: none;
            cursor: pointer;
            padding: 5px 10px;
            font-size: 0.85rem;
            border-radius: 4px;
            transition: 0.3s;
        }

        .mermaid-tab.active {
            background: var(--accent-color);
            color: white;
        }

        .mermaid-download {
            background: transparent;
            color: var(--text-primary);
            border: 1px solid var(--glass-border);
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85rem;
        }

        .mermaid-download:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .mermaid-content {
            background: rgba(0, 0, 0, 0.2);
            padding: 15px;
            overflow-x: auto;
            text-align: center;
        }

        .mermaid-code {
            display: none;
            background: #1e1e1e;
            padding: 15px;
            text-align: left;
            overflow-x: auto;
        }

        /* ===== TOMBOL AI ACTIONS (SALIN & EKSPOR) ===== */
        .ai-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
            align-items: center;
        }

        .action-btn {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            color: var(--text-secondary);
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        .action-btn:hover {
            background: rgba(37, 99, 235, 0.15);
            color: var(--text-primary);
            border-color: rgba(37, 99, 235, 0.3);
        }

        .export-dropdown-container {
            position: relative;
        }

        .export-menu {
            position: absolute;
            bottom: 100%;
            left: 0;
            background: #1e293b;
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            padding: 5px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
            z-index: 50;
            width: 140px;
            margin-bottom: 5px;
            display: none;
        }

        .export-menu .option-item {
            padding: 8px 10px;
            font-size: 0.85rem;
            color: var(--text-primary);
            cursor: pointer;
            border-radius: 6px;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .export-menu .option-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Light Mode Support */
        body.light-mode .action-btn {
            background: transparent;
            color: #64748b;
        }

        body.light-mode .action-btn:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        body.light-mode .export-menu {
            background: #ffffff;
        }

        body.light-mode .export-menu .option-item {
            color: #333;
        }

        body.light-mode .export-menu .option-item:hover {
            background: #f1f5f9;
        }

        /* ===== TOMBOL AI ACTIONS (SALIN & EKSPOR) ===== */
        .ai-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
            align-items: center;
        }

        .action-btn {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            color: var(--text-secondary);
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        .action-btn:hover {
            background: rgba(37, 99, 235, 0.15);
            color: var(--text-primary);
            border-color: rgba(37, 99, 235, 0.3);
        }

        /* DROPDOWN MEWAH (ANTI BERANTAKAN) */
        .export-dropdown-container {
            position: relative;
            display: inline-block;
        }

        .export-menu {
            display: none;
            position: absolute;
            bottom: 100%;
            left: 0;
            background: #1e293b;
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            padding: 6px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.6);
            z-index: 100;
            width: 170px;
            /* <--- Diperlebar agar teks muat */
            margin-bottom: 8px;
        }

        .export-menu .option-item {
            padding: 10px 12px;
            font-size: 0.85rem;
            color: var(--text-primary);
            cursor: pointer;
            border-radius: 6px;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
        }

        .export-menu .option-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Light Mode Support */
        body.light-mode .action-btn {
            background: transparent;
            color: #64748b;
        }

        body.light-mode .action-btn:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        body.light-mode .export-menu {
            background: #ffffff;
        }

        body.light-mode .export-menu .option-item {
            color: #333;
        }

        body.light-mode .export-menu .option-item:hover {
            background: #f1f5f9;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>
</head>

<body>
    <div class="public-container">
        <div class="header">
            <div class="header-title">
                SAHAJA AI
                <span class="badge-public">Public Read-Only</span>
            </div>
            <div class="header-topic">Topik: "{{ $session->title ?? 'Tanpa Judul' }}"</div>
        </div>

        <div class="messages-container" id="messagesContainer" style="{{ count($chats) == 0 ? 'display: none;' : '' }}">
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
                        <div class="ai-actions">
                            <button class="action-btn" onclick="copyText(this)"><i class="far fa-copy"></i>
                                Salin</button>

                            <div class="export-dropdown-container">
                                <button class="action-btn" onclick="toggleExportMenu(this)"><i
                                        class="fas fa-ellipsis-v"></i></button>
                                <div class="export-menu">
                                    <div class="option-item" onclick="exportToDoc(this)">
                                        <i class="fas fa-file-word" style="color: #3b82f6;"></i> Ekspor ke DOCS
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="footer">
            Dibagikan melalui <a href="/">SAHAJA AI</a>.
            Gass cobain Sahaja AI Sekarang!!
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script>
        marked.setOptions({
            sanitize: true,
            breaks: true,
            gfm: true
        });

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.message.ai').forEach(el => {
                // PERBAIKAN 1: Panggil nama class yang benar sesuai HTML di atas
                const rawDiv = el.querySelector('.ai-raw-data');
                const renderDiv = el.querySelector('.ai-rendered-data');

                if (rawDiv && renderDiv) {
                    const rawText = rawDiv.textContent.trim();
                    // Gunakan fungsi pintar yang baru kita buat
                    renderAIContent(rawDiv.textContent.trim(), renderDiv);

                    // PERBAIKAN EXTRA: Panggil fungsi render Mermaid (Diagram) agar jalan di Public Chat
                    if (typeof processMermaidDiagrams === 'function') {
                        processMermaidDiagrams(renderDiv);
                    }
                }
            });
        });
        // ==========================================
        // FUNGSI SALIN TEKS (JALUR GANDA - ANTI MACET DI HP)
        // ==========================================
        window.copyText = function(btn) {
            try {
                // 1. Ambil elemen teks
                const messageDiv = btn.closest('.message-content');
                if (!messageDiv) return;

                const rawData = messageDiv.querySelector('.ai-raw-data');
                if (!rawData) return;

                const textToCopy = (rawData.textContent || rawData.innerText).trim();
                if (!textToCopy) return;

                // 2. Fungsi Animasi Berhasil
                const showSuccess = () => {
                    const originalHTML = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-check"></i> Tersalin';
                    btn.style.color = '#10b981';
                    btn.style.borderColor = '#10b981';
                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.style.color = '';
                        btn.style.borderColor = '';
                    }, 2000);
                };

                // 3. JURUS 1: Coba pakai Clipboard API Modern (Untuk Laptop / Browser Modern)
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(textToCopy)
                        .then(() => showSuccess())
                        .catch(() => fallbackCopyTextToClipboard(textToCopy, showSuccess));
                } else {
                    // JURUS 2: Kalau ditolak (Webview WA/IG/HP Lama), pakai mode jadul
                    fallbackCopyTextToClipboard(textToCopy, showSuccess);
                }
            } catch (error) {
                console.error("Error copy: ", error);
                alert("Gagal menyalin teks. Silakan salin manual.");
            }
        };

        // FUNGSI BANTUAN (JURUS 2)
        function fallbackCopyTextToClipboard(text, successCallback) {
            const textArea = document.createElement("textarea");
            textArea.value = text;

            // Sembunyikan textarea agar layar tidak berkedip/geser
            textArea.style.position = "fixed";
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.width = "2em";
            textArea.style.height = "2em";
            textArea.style.padding = "0";
            textArea.style.border = "none";
            textArea.style.outline = "none";
            textArea.style.boxShadow = "none";
            textArea.style.background = "transparent";

            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    successCallback();
                } else {
                    console.error('Fallback: Gagal copy');
                    alert("Browser tidak mendukung fitur salin. Silakan salin manual.");
                }
            } catch (err) {
                console.error('Fallback Error', err);
            }

            document.body.removeChild(textArea);
        }

        // ==========================================
        // FUNGSI EKSPOR DOCX (SUPPORT HP & RAPI)
        // ==========================================
        window.exportToDoc = function(btn) {
            const originalContainer = btn.closest('.message-content').querySelector('.ai-rendered-data');
            if (!originalContainer) return;

            // Gunakan mesin pembersih agar tidak berantakan
            const printDiv = prepareExportContent(originalContainer);

            const contentHTML = `
            <!DOCTYPE html>
            <html>
            <head><meta charset="UTF-8"><style>
                body { font-family: 'Times New Roman', serif; line-height: 1.6; color: #000; }
                table { border-collapse: collapse; width: 100%; }
                table, th, td { border: 1px solid black; padding: 8px; }
                pre { background: #f4f4f4; padding: 10px; border: 1px solid #ccc; }
            </style></head>
            <body>
                <h2 style="color: #2563eb;">SAHAJA AI Export</h2>
                <hr>${printDiv.innerHTML}
            </body>
            </html>`;

            try {
                // Merakit file .docx asli menggunakan library
                const converted = htmlDocx.asBlob(contentHTML);
                const link = document.createElement('a');
                link.href = URL.createObjectURL(converted);
                link.download = 'SAHAJA_AI_' + Date.now() + '.docx';
                link.click();
            } catch (e) {
                console.error("Gagal Ekspor:", e);
            }
        };

        // ==========================================
        // MESIN PEMBERSIH KONTEN EKSPOR
        // ==========================================
        function prepareExportContent(container) {
            const printDiv = document.createElement('div');
            printDiv.innerHTML = container.innerHTML;
            // Buang elemen yang tidak perlu di Word
            printDiv.querySelectorAll('.thinking-container, .mermaid-wrapper, button, i').forEach(el => el.remove());
            return printDiv;
        }

        // ==========================================
        // MESIN RENDER AI (MARKDOWN + THINKING)
        // ==========================================
        function renderAIContent(text, containerElement) {
            let rawText = text.replace(/\\\[/g, '$$$$').replace(/\\\]/g, '$$$$').replace(/\\\(/g, '$$').replace(/\\\)/g,
                '$$');

            // Tangkap Tag Thinking
            const thinkingBlocks = {};
            let thinkingIndex = 0;
            rawText = rawText.replace(/<(?:thinking|think)>([\s\S]*?)<\/(?:thinking|think)>/gi, function(match, inner) {
                const placeholder = `@@THINK_BLOCK_${thinkingIndex}@@`;
                thinkingBlocks[placeholder] = `
            <div class="thinking-container">
                <div class="thinking-header" onclick="this.nextElementSibling.classList.toggle('show')">
                    <i class="fas fa-brain"></i> Alur Berpikir AI
                </div>
                <div class="thinking-content">${inner.trim()}</div>
            </div>`;
                thinkingIndex++;
                return placeholder;
            });

            let htmlContent = marked.parse(rawText);

            // Kembalikan Thinking
            for (const [key, val] of Object.entries(thinkingBlocks)) {
                htmlContent = htmlContent.split(key).join(val);
            }

            containerElement.innerHTML = htmlContent;

            // Syntax Highlighting
            containerElement.querySelectorAll('pre code').forEach((block) => {
                if (window.hljs) hljs.highlightElement(block);
            });
        }

        // Fungsi Dropdown
        window.toggleExportMenu = function(btn) {
            const menu = btn.nextElementSibling;
            const isShowing = menu.style.display === 'block';
            document.querySelectorAll('.export-menu').forEach(m => m.style.display = 'none');
            if (!isShowing) menu.style.display = 'block';
            event.stopPropagation();
        };

        document.addEventListener('click', () => {
            document.querySelectorAll('.export-menu').forEach(m => m.style.display = 'none');
        });
    </script>
</body>

</html>

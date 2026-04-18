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
        .message-content {
            min-width: 0 !important;
            max-width: calc(100% - 50px) !important; /* Agar tidak mendobrak layar */
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

        /* ===== TABEL MARKDOWN ===== */
        .markdown-body table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            margin-bottom: 16px;
            display: block;
            overflow-x: auto; /* Biar tabel bisa di-scroll ke kanan di HP */
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
            background-color: rgba(37, 99, 235, 0.15); /* Biru transparan elegan */
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
            background-color: rgba(255, 255, 255, 0.02); /* Efek belang-belang */
        }

        .markdown-body table tr:hover {
            background-color: rgba(255, 255, 255, 0.05); /* Efek nyala saat di-hover */
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

        .footer { padding: 20px; text-align: center; border-top: 1px solid var(--glass-border); font-size: 0.85rem; color: var(--text-secondary); }
        .footer a { color: var(--accent-color); text-decoration: none; font-weight: bold; }

        /* ===== THINKING MODE UI (WAJIB ADA BIAR TERBUNGKUS) ===== */
        .thinking-container {
            margin-bottom: 15px; border: 1px solid var(--glass-border);
            border-radius: 8px; overflow: hidden; background: rgba(0, 0, 0, 0.15);
        }
        .thinking-header {
            padding: 10px 12px; cursor: pointer; display: flex; align-items: center;
            gap: 8px; font-size: 0.85rem; color: var(--text-secondary);
            background: rgba(255, 255, 255, 0.05); user-select: none; transition: 0.2s;
        }
        .thinking-header:hover { background: rgba(255, 255, 255, 0.1); color: var(--text-primary); }
        .thinking-content {
            display: none; padding: 12px; font-size: 0.85rem; color: var(--text-secondary);
            border-top: 1px solid var(--glass-border); white-space: pre-wrap;
            font-style: italic; line-height: 1.6;
        }
        .thinking-content.show { display: block; animation: fadeIn 0.3s ease; }

        /* ===== MERMAID DIAGRAM UI ===== */
        .mermaid-wrapper { border: 1px solid var(--glass-border); border-radius: 8px; margin: 15px 0; overflow: hidden; }
        .mermaid-header { display: flex; justify-content: space-between; background: rgba(255,255,255,0.05); padding: 8px 15px; align-items: center; border-bottom: 1px solid var(--glass-border); }
        .mermaid-tabs { display: flex; gap: 10px; }
        .mermaid-tab { background: transparent; color: var(--text-secondary); border: none; cursor: pointer; padding: 5px 10px; font-size: 0.85rem; border-radius: 4px; transition: 0.3s; }
        .mermaid-tab.active { background: var(--accent-color); color: white; }
        .mermaid-download { background: transparent; color: var(--text-primary); border: 1px solid var(--glass-border); padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 0.85rem; }
        .mermaid-download:hover { background: rgba(255,255,255,0.1); }
        .mermaid-content { background: rgba(0,0,0,0.2); padding: 15px; overflow-x: auto; text-align: center; }
        .mermaid-code { display: none; background: #1e1e1e; padding: 15px; text-align: left; overflow-x: auto; }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>
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
                                    <div class="option-item" style="font-size: 0.8rem; padding: 6px 10px;" onclick="exportToPDF(this)"><i class="fas fa-file-pdf" style="color: #ef4444;"></i> Ekspor ke PDF</div>
                                    <div class="option-item" style="font-size: 0.8rem; padding: 6px 10px;" onclick="exportToDoc(this)"><i class="fas fa-file-word" style="color: #3b82f6;"></i> Ekspor ke DOCS</div>
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
        marked.setOptions({ sanitize: true, breaks: true, gfm: true });

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.message.ai').forEach(el => {
                const rawDiv = el.querySelector('.raw-content');
                const renderDiv = el.querySelector('.rendered-content');
                if (rawDiv && renderDiv) {
                const rawText = rawDiv.textContent.trim();
                // Gunakan fungsi pintar yang baru kita buat
                renderAIContent(rawText, renderDiv);
            }
            });
        });

        // ==========================================
        // FUNGSI BARU: RENDER MARKDOWN + MATEMATIKA (FINAL FIX)
        // ==========================================
        function renderAIContent(text, containerElement) {
            // 1. Seragamkan format LaTeX AI menjadi standar KaTeX ($$ dan $)
            let rawText = text
                .replace(/\\\[/g, '$$$$')
                .replace(/\\\]/g, '$$$$')
                .replace(/\\\(/g, '$$')
                .replace(/\\\)/g, '$$');

            // 2. EKSTRAK RUMUS MATEMATIKA (Gunakan @@ agar kebal dari Markdown)
            const mathBlocks = {};
            let mathIndex = 0;

            // A. Amankan Block Math ($$ ... $$)
            rawText = rawText.replace(/\$\$([\s\S]*?)\$\$/g, function(match) {
                const placeholder = `@@MATH_BLOCK_${mathIndex}@@`;
                mathBlocks[placeholder] = match;
                mathIndex++;
                return placeholder;
            });

            // B. Amankan Inline Math ($ ... $)
            rawText = rawText.replace(/\$([^$\n]*?)\$/g, function(match) {
                const placeholder = `@@MATH_INLINE_${mathIndex}@@`;
                mathBlocks[placeholder] = match;
                mathIndex++;
                return placeholder;
            });

            // 3. Render Markdown
            let htmlContent = marked.parse(rawText);

            // 4. KEMBALIKAN RUMUS ke posisinya menggunakan split.join (lebih aman dari replace)
            for (const [placeholder, mathText] of Object.entries(mathBlocks)) {
                htmlContent = htmlContent.split(placeholder).join(mathText);
            }

            containerElement.innerHTML = htmlContent;

            // 5. Panggil KaTeX untuk menyulap teks menjadi rumus visual
            if (window.renderMathInElement) {
                renderMathInElement(containerElement, {
                    delimiters: [
                        {left: '$$', right: '$$', display: true},
                        {left: '$', right: '$', display: false}
                    ],
                    throwOnError: false
                });
            }

            // 6. Warnai blok kodingan (Syntax Highlighting) jika ada
            containerElement.querySelectorAll('pre code').forEach((block) => {
                if (window.hljs) hljs.highlightElement(block);
            });
        }
        // 1. JURUS RAHASIA: TANGKAP TAG <thinking> DULUAN
            const thinkingBlocks = {};
            let thinkingIndex = 0;
            // Deteksi tag <thinking> ... </thinking> (Tidak peduli huruf besar/kecil)
            rawText = rawText.replace(/<thinking>([\s\S]*?)<\/thinking>/gi, function(match, innerThinking) {
                const placeholder = `@@THINKING_BLOCK_${thinkingIndex}@@`;
                // Bersihkan teks & cegah injeksi HTML berbahaya
                const cleanThinking = innerThinking.trim().replace(/</g, "&lt;").replace(/>/g, "&gt;");

                // Sulap menjadi UI Kotak Interaktif
                thinkingBlocks[placeholder] = `
                <div class="thinking-container">
                    <div class="thinking-header" onclick="this.nextElementSibling.classList.toggle('show'); const icon = this.querySelector('.fa-chevron-right'); if(icon.style.transform === 'rotate(90deg)') { icon.style.transform = 'none'; } else { icon.style.transform = 'rotate(90deg)'; }">
                        <i class="fas fa-brain"></i> <span style="font-weight: 500;">Alur Berpikir AI</span>
                        <i class="fas fa-chevron-right" style="margin-left: auto; transition: 0.2s;"></i>
                    </div>
                    <div class="thinking-content">${cleanThinking}</div>
                </div>`;
                thinkingIndex++;
                return placeholder;
            });
            // Fungsi Buka Tutup Menu Export
        function toggleExportMenu(btn) {
            const menu = btn.nextElementSibling;
            const isShowing = menu.style.display === 'block';
            document.querySelectorAll('.export-menu').forEach(m => m.style.display = 'none'); // Tutup yang lain
            if (!isShowing) menu.style.display = 'block';
            event.stopPropagation();
        }

        // ==========================================
        // 1. MESIN PEMBERSIH & FORMATTER (WAJIB ADA)
        // ==========================================
        function prepareExportContent(container) {
            const printDiv = document.createElement('div');
            printDiv.innerHTML = container.innerHTML;

            // Buang tombol dan kotak thinking
            printDiv.querySelectorAll('.thinking-container, .code-header, .code-copy-btn, button').forEach(el => el.remove());

            // JURUS ANTI MARKDOWN MENTAH: Paksa terjemahkan jika masih ada simbol # atau **
            if (printDiv.innerHTML.includes('**') || printDiv.innerHTML.includes('###')) {
                if (typeof marked !== 'undefined') {
                    printDiv.innerHTML = marked.parse(printDiv.textContent || printDiv.innerText);
                }
            }

            // SUNTIKAN GAYA BRUTAL (Agar Word & PDF Patuh 100%)
            printDiv.style.fontFamily = "'Times New Roman', Times, serif";
            printDiv.style.fontSize = "12pt";
            printDiv.style.color = "#000000";
            printDiv.style.backgroundColor = "#ffffff";
            printDiv.style.lineHeight = "1.5";

            // Paksa semua teks di dalamnya jadi hitam legam
            printDiv.querySelectorAll('*').forEach(el => {
                el.style.color = "#000000";
                el.style.fontFamily = "'Times New Roman', Times, serif";
            });

            // Paksa Tabel Punya Garis Hitam
            printDiv.querySelectorAll('table').forEach(table => {
                table.setAttribute('border', '1');
                table.style.width = '100%';
                table.style.borderCollapse = 'collapse';
                table.style.marginBottom = '15pt';
                table.style.border = '1px solid #000000';
            });
            printDiv.querySelectorAll('th, td').forEach(cell => {
                cell.style.border = '1px solid #000000';
                cell.style.padding = '8pt';
                cell.style.textAlign = 'left';
            });
            printDiv.querySelectorAll('th').forEach(th => {
                th.style.backgroundColor = '#f2f2f2';
                th.style.fontWeight = 'bold';
            });

            // Rapikan Judul (Heading) & Kodingan
            printDiv.querySelectorAll('h1, h2, h3').forEach(h => {
                h.style.fontWeight = 'bold';
                h.style.marginTop = '15pt';
                h.style.marginBottom = '10pt';
            });
            printDiv.querySelectorAll('pre, code').forEach(code => {
                code.style.fontFamily = "'Courier New', Courier, monospace";
                code.style.backgroundColor = '#f4f4f4';
                code.style.padding = '8pt';
                code.style.borderRadius = '3pt';
                code.style.whiteSpace = 'pre-wrap';
                code.style.border = '1px solid #cccccc';
            });

            return printDiv;
        }

        // ==========================================
        // 3. FUNGSI EXPORT DOCS (RAPI & ADA TABEL)
        // ==========================================
        function exportToDoc(btn) {
            const originalContainer = btn.closest('.message-content').querySelector('.markdown-body');
            if (!originalContainer) return showToast("Gagal mengambil konten", "error");

            showToast("Menyusun format Word...", "info");

            const printDiv = prepareExportContent(originalContainer);

            // Bungkus dengan standar XML Microsoft Word
            const header = `<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
                            <head><meta charset='utf-8'><title>Document</title></head><body>`;
            const footer = "</body></html>";

            const sourceHTML = header + printDiv.outerHTML + footer;
            const blob = new Blob(['\ufeff', sourceHTML], { type: 'application/msword' });

            const url = URL.createObjectURL(blob);
            const link = document.createElement("a");
            link.href = url;
            link.download = 'SAHAJA_AI_' + new Date().getTime() + '.doc';
            link.click();
            URL.revokeObjectURL(url);

            showToast("Word Berhasil diunduh!", "success");
        }

        // ====================fungsi mermaid======================== //
        // 1. Inisialisasi Tema Mermaid agar cocok dengan SAHAJA AI
        mermaid.initialize({ startOnLoad: false, theme: 'dark' });

        // 2. Fungsi Menyulap Teks Code Menjadi Visual Diagram (ANTI-ERROR)
        async function processMermaidDiagrams(container) {
            const mermaidBlocks = container.querySelectorAll('code.language-mermaid');
            if(mermaidBlocks.length === 0) return;

            for (let i = 0; i < mermaidBlocks.length; i++) {
                const codeBlock = mermaidBlocks[i];
                const preBlock = codeBlock.parentElement;

                // Jangan proses dua kali
                if(preBlock.classList.contains('mermaid-processed')) continue;
                preBlock.classList.add('mermaid-processed');

                // JURUS PEMBERSIH: Hapus Header "Salin" bawaan pre agar tidak dobel/nyangkut
                if (preBlock.previousElementSibling && preBlock.previousElementSibling.classList.contains('code-header')) {
                    preBlock.previousElementSibling.remove();
                }

                // Ambil teks murni
                let rawCode = codeBlock.textContent || codeBlock.innerText;

                // JURUS ANTI-BOMB: Bersihkan spasi ghaib (NBSP) yang bikin Mermaid meledak
                rawCode = rawCode.replace(/\u00A0/g, ' ').trim();

                const uniqueId = 'mermaid-' + Date.now() + '-' + i;

                // Buat UI Kotak DeepSeek Style
                const wrapper = document.createElement('div');
                wrapper.className = 'mermaid-wrapper';
                wrapper.innerHTML = `
                    <div class="mermaid-header">
                        <div class="mermaid-tabs">
                            <button class="mermaid-tab active" onclick="switchMermaid('${uniqueId}', 'diagram', this)">Visual Diagram</button>
                            <button class="mermaid-tab" onclick="switchMermaid('${uniqueId}', 'code', this)">Source Code</button>
                        </div>
                        <button class="mermaid-download" onclick="downloadMermaid('${uniqueId}')"><i class="fas fa-download"></i> SVG</button>
                    </div>
                    <div id="${uniqueId}-diagram" class="mermaid-content">
                        <div style="color: var(--accent-color); padding: 20px;"><i class="fas fa-spinner fa-spin"></i> Sedang menggambar diagram...</div>
                    </div>
                    <div id="${uniqueId}-code" class="mermaid-code">
                        <pre><code class="language-mermaid"></code></pre>
                    </div>
                `;

                // Masukkan teks ke dalam tab Source Code dengan aman
                wrapper.querySelector('.language-mermaid').textContent = rawCode;

                preBlock.replaceWith(wrapper);

                // JURUS RENDER MURNI (Langsung suntik SVG, bypass HTML error)
                try {
                    const { svg } = await mermaid.render(uniqueId + '-svg', rawCode);
                    document.getElementById(uniqueId + '-diagram').innerHTML = svg;
                } catch (e) {
                    console.error("Mermaid Render Error:", e);
                    document.getElementById(uniqueId + '-diagram').innerHTML = `
                        <div style="color: #ef4444; padding: 15px; border: 1px dashed #ef4444; border-radius: 8px; text-align: left;">
                            <i class="fas fa-exclamation-triangle"></i> <b>Diagram Gagal Digambar</b><br>
                            <span style="font-size: 0.85rem; color: var(--text-secondary);">AI salah memberikan format/sintaks. Silakan klik tab <b>Source Code</b> untuk melihat kodenya.</span>
                        </div>
                    `;
                }
            }
        }

        // 3. Fungsi Tombol Switch (Diagram vs Code)
        window.switchMermaid = function(id, mode, btn) {
            const wrapper = btn.closest('.mermaid-wrapper');
            wrapper.querySelectorAll('.mermaid-tab').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            if(mode === 'diagram') {
                document.getElementById(id + '-diagram').style.display = 'block';
                document.getElementById(id + '-code').style.display = 'none';
            } else {
                document.getElementById(id + '-diagram').style.display = 'none';
                document.getElementById(id + '-code').style.display = 'block';
            }
        };

        // 4. Fungsi Tombol Download Gambar Diagram (SVG)
        window.downloadMermaid = function(id) {
            const svg = document.querySelector(`#${id}-diagram svg`);
            if(!svg) return showToast('Diagram belum selesai diproses', 'error');

            const svgData = new XMLSerializer().serializeToString(svg);
            const blob = new Blob([svgData], {type: 'image/svg+xml;charset=utf-8'});
            const url = URL.createObjectURL(blob);

            const link = document.createElement('a');
            link.href = url;
            link.download = 'SAHAJA_Diagram_' + Date.now() + '.svg';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            showToast('Gambar Diagram Berhasil Diunduh!', 'success');
        };
    </script>
</body>
</html>

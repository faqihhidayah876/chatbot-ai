<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAHAJA LLM - Workspace</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';</script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <link rel="icon" <link rel="icon" type="image/png" href="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --main-bg: #0a0e17; /* Latar belakang paling gelap */
            --panel-bg: #151b23; /* Warna panel ala NotebookLM/GitHub Dark */
            --glass-border: rgba(255, 255, 255, 0.1);
            --glass-hover: rgba(255, 255, 255, 0.05);
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --accent-color: #2563eb; /* Biru SAHAJA */
            --llm-accent: #10b981; /* Hijau zamrud untuk LLM */
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        body {
            background-color: var(--main-bg);
            color: var(--text-primary);
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* ===== HEADER ===== */
        .llm-header {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            border-bottom: 1px solid var(--glass-border);
            background: var(--main-bg);
            flex-shrink: 0;
        }

        .header-left, .header-right { display: flex; align-items: center; gap: 15px; }

        .btn-header {
            background: transparent; color: var(--text-primary); border: 1px solid var(--glass-border);
            padding: 6px 14px; border-radius: 20px; text-decoration: none; font-size: 0.85rem;
            display: flex; align-items: center; gap: 8px; cursor: pointer; transition: 0.2s;
        }
        .btn-header:hover { background: var(--glass-hover); }
        .btn-header.primary { border-color: var(--text-secondary); }

        /* ===== GRID LAYOUT (3 PANEL) ===== */
        .llm-workspace {
            display: grid;
            grid-template-columns: 300px 1fr 300px; /* Panel Kiri, Tengah (Flex), Kanan */
            gap: 15px;
            padding: 15px;
            height: calc(100vh - 60px);
            overflow: hidden;
        }

        /* Desain Umum Panel */
        .panel {
            background: var(--panel-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px; /* Sudut membulat ala NotebookLM */
            display: flex;
            flex-direction: column;
            overflow: hidden;
            position: relative;
        }

        .panel-header {
            padding: 15px 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid transparent;
        }

        /* ===== PANEL KIRI (SUMBER) ===== */
        .source-content { padding: 0 15px 15px 15px; overflow-y: auto; flex: 1; }

        .btn-add-source {
            width: 100%; background: transparent; border: 1px solid var(--glass-border);
            color: var(--text-primary); padding: 12px; border-radius: 12px;
            font-size: 0.85rem; display: flex; align-items: center; justify-content: center;
            gap: 8px; cursor: pointer; transition: 0.2s; margin-bottom: 15px;
        }
        .btn-add-source:hover { background: var(--glass-hover); }

        .search-source {
            display: flex; align-items: center; background: rgba(0,0,0,0.3);
            border: 1px solid var(--glass-border); border-radius: 12px; padding: 10px 15px;
            gap: 10px; margin-bottom: 30px;
        }
        .search-source input { background: transparent; border: none; color: white; flex: 1; outline: none; font-size: 0.85rem; }

        .empty-state { text-align: center; color: var(--text-secondary); margin-top: 50px; padding: 0 10px; }
        .empty-state i { font-size: 2.5rem; margin-bottom: 15px; opacity: 0.5; }
        .empty-state p { font-size: 0.8rem; line-height: 1.6; }

        /* ===== PANEL TENGAH (CHAT / MAIN) ===== */
        .chat-content { flex: 1; overflow-y: auto; padding: 40px; display: flex; flex-direction: column; justify-content: center; }

        .greeting-area { text-align: left; max-width: 650px; margin: 0 auto; width: 100%; }
        .greeting-area h1 { font-size: 2.2rem; margin-bottom: 15px; font-weight: 600; }
        .greeting-area p { color: var(--text-secondary); font-size: 0.95rem; line-height: 1.6; margin-bottom: 30px; }

        .suggestion-chips { display: flex; flex-direction: column; gap: 10px; }
        .chip {
            background: transparent; border: 1px solid var(--glass-border); color: var(--text-primary);
            padding: 10px 20px; border-radius: 20px; font-size: 0.85rem; cursor: pointer;
            transition: 0.2s; text-align: left; width: fit-content;
        }
        .chip:hover { background: var(--glass-hover); }

        /* Input Area (Tengah Bawah) */
        .chat-input-wrapper { padding: 20px; background: var(--panel-bg); }
        .chat-input-box {
            background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border);
            border-radius: 24px; padding: 12px 20px; display: flex; align-items: center; gap: 15px;
        }
        .chat-input-box input { flex: 1; background: transparent; border: none; color: white; outline: none; font-size: 0.95rem; }
        .source-count { font-size: 0.75rem; color: var(--text-secondary); }
        .btn-send {
            background: var(--glass-border); color: var(--text-primary); border: none;
            width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center;
            justify-content: center; cursor: pointer; transition: 0.2s;
        }
        .btn-send:hover { background: var(--text-secondary); color: var(--main-bg); }
        .input-footer { text-align: center; font-size: 0.7rem; color: var(--text-secondary); margin-top: 10px; }

        /* ===== PANEL KANAN (STUDIO) ===== */
        .studio-content { padding: 0 15px 15px 15px; overflow-y: auto; flex: 1; }

        .promo-card {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(16, 185, 129, 0.1));
            border: 1px solid var(--glass-border); border-radius: 12px; padding: 15px;
            display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;
        }
        .promo-card p { font-size: 0.8rem; font-weight: 600; margin-left: 10px; flex: 1; }
        .promo-card button { background: rgba(255,255,255,0.1); border: none; color: white; padding: 5px 15px; border-radius: 15px; font-size: 0.75rem; cursor: pointer; }

        .studio-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .studio-btn {
            background: transparent; border: 1px solid var(--glass-border); border-radius: 12px;
            padding: 15px 10px; display: flex; flex-direction: column; align-items: flex-start;
            gap: 10px; color: var(--text-secondary); cursor: pointer; transition: 0.2s; text-align: left;
        }
        .studio-btn i { font-size: 1.2rem; color: var(--text-primary); }
        .studio-btn span { font-size: 0.75rem; }
        .studio-btn:hover { background: var(--glass-hover); color: white; }

        /* ===== RESPONSIVE KHUSUS MOBILE ===== */
        .mobile-toggle { display: none; background: transparent; border: none; color: white; font-size: 1.2rem; cursor: pointer; }

        @media (max-width: 1024px) {
            .llm-workspace { grid-template-columns: 280px 1fr; }
            .studio-panel { position: fixed; right: -100%; top: 60px; height: calc(100vh - 60px); width: 300px; z-index: 1000; transition: 0.3s; border-radius: 0; border-left: 1px solid var(--glass-border); }
            .studio-panel.show { right: 0; }
            .btn-studio-toggle { display: flex; }
        }

        @media (max-width: 768px) {
            .llm-workspace { grid-template-columns: 1fr; padding: 10px; }
            .source-panel { position: fixed; left: -100%; top: 60px; height: calc(100vh - 60px); width: 280px; z-index: 1000; transition: 0.3s; border-radius: 0; border-right: 1px solid var(--glass-border); }
            .source-panel.show { left: 0; }
            .mobile-toggle { display: block; }
            .greeting-area h1 { font-size: 1.8rem; }
            .header-right .btn-header span { display: none; } /* Sembunyikan teks tombol di HP */
        }
    </style>
</head>
<body>

    <header class="llm-header">
        <div class="header-left">
            <button class="mobile-toggle" onclick="document.getElementById('sourcePanel').classList.toggle('show')">
                <i class="fas fa-bars"></i>
            </button>
            <a href="{{ route('chat.index') }}" class="btn-header"><i class="fas fa-arrow-left"></i> <span>Kembali</span></a>
            <span style="font-weight: 600; font-size: 1.1rem;"><i class="fas fa-book-reader" style="color: var(--llm-accent); margin-right: 5px;"></i> SAHAJA LLM</span>
        </div>
        <div class="header-right">
            <button class="btn-header"><i class="fas fa-chart-line"></i> <span>Analytics</span></button>
            <button class="btn-header"><i class="fas fa-share-alt"></i> <span>Bagikan</span></button>
            <button class="btn-header primary"><i class="fas fa-cog"></i> <span>Setelan</span></button>
            <button class="mobile-toggle btn-studio-toggle" style="display: none;" onclick="document.getElementById('studioPanel').classList.toggle('show')">
                <i class="fas fa-layer-group"></i>
            </button>
            <div style="width: 32px; height: 32px; background: var(--accent-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </div>
        </div>
    </header>

    <div class="llm-workspace">

        <aside class="panel source-panel" id="sourcePanel">
            <div class="panel-header">
                Sumber
                <button style="background:transparent; border:none; color:var(--text-secondary); cursor:pointer;"><i class="fas fa-expand-alt"></i></button>
            </div>
            <div class="source-content">
                <input type="file" id="llmFileInput" accept=".pdf" style="display: none;">
                <button class="btn-add-source" onclick="document.getElementById('llmFileInput').click()"><i class="fas fa-plus"></i> Tambahkan sumber (PDF)</button>
                <div id="documentListContainer">
                    @foreach($documents as $doc)
                        <div style="background: rgba(255,255,255,0.05); padding: 10px; border-radius: 8px; margin-bottom: 10px; font-size: 0.85rem; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-file-pdf" style="color: #ef4444;"></i>
                            <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $doc->file_name }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="search-source">
                    <i class="fas fa-search" style="color: var(--text-secondary);"></i>
                    <input type="text" placeholder="Telusuri sumber baru di web">
                </div>

                <div class="empty-state">
                    <i class="fas fa-file-alt"></i>
                    <p><strong>Sumber yang telah disimpan akan muncul di sini</strong><br><br>
                    Klik Tambahkan sumber di atas untuk menambahkan PDF, situs, teks, video, atau file audio.</p>
                </div>
            </div>
        </aside>

        <main class="panel chat-panel">
            <div class="chat-content">
                <div class="greeting-area">
                    <h1>👋 Mari mulai membuat notebook...</h1>
                    <p>Dengan notebook ini, Anda dapat mulai memahami, membuat, atau mengembangkan hal baru. Anda dapat meminta bantuan saya untuk memulai atau langsung menambahkan sumber Anda sendiri.</p>

                    <strong style="display: block; font-size: 0.9rem; margin-bottom: 15px;">Apa yang ingin Anda lakukan dengan notebook ini?</strong>

                    <div class="suggestion-chips">
                        <button class="chip">Memulai project</button>
                        <button class="chip">Mempelajari atau memahami sesuatu</button>
                        <button class="chip">Membuat ringkasan, laporan, slide presentasi, dll.</button>
                        <button class="chip">Lainnya...</button>
                    </div>
                </div>
            </div>

            <div class="chat-input-wrapper">
                <div class="chat-input-box">
                    <input type="text" id="llmChatInput" placeholder="Tanyakan isi dokumen ..." onkeydown="if(event.key==='Enter') sendLlmChat()">
                    <span class="source-count">{{ count($documents) }} sumber</span>
                    <button class="btn-send" onclick="sendLlmChat()"><i class="fas fa-arrow-up"></i></button>
                </div>
                <div class="input-footer">SAHAJA LLM mungkin tidak akurat, jadi periksa kembali responsnya.</div>
            </div>
        </main>

        <aside class="panel studio-panel" id="studioPanel">
            <div class="panel-header">
                Studio
                <button style="background:transparent; border:none; color:var(--text-secondary); cursor:pointer;"><i class="fas fa-expand-alt"></i></button>
            </div>
            <div class="studio-content">

                <div class="promo-card">
                    <i class="fas fa-magic" style="color: #60a5fa;"></i>
                    <p>Coba penyesuaian Peta Pikiran baru</p>
                    <button>Coba</button>
                </div>

                <div class="studio-grid">
                    <button class="studio-btn" onclick="generateStudio('ringkasan')"><i class="fas fa-align-left"></i> <span>Ringkasan...</span></button>
                    <button class="studio-btn" onclick="generateStudio('slide')"><i class="fas fa-tv"></i> <span>Slide Presentasi...</span></button>
                    <button class="studio-btn" onclick="generateStudio('laporan')"><i class="fas fa-file-alt"></i> <span>Laporan Singkat</span></button>
                    <button class="studio-btn" onclick="generateStudio('mindmap')"><i class="fas fa-project-diagram"></i> <span>Peta Pikiran</span></button>
                    <button class="studio-btn" onclick="generateStudio('kuis')"><i class="fas fa-question-circle"></i> <span>Kuis (5 Soal)</span></button>
                    <button class="studio-btn" onclick="generateStudio('tabel')"><i class="fas fa-table"></i> <span>Tabel Data Ekstrak</span></button>
                </div>

                <div class="empty-state" style="margin-top: 30px;">
                    <i class="fas fa-magic" style="font-size: 1.5rem;"></i>
                    <p style="font-size: 0.75rem; margin-top: 10px;">Output studio akan disimpan di sini. Setelah menambahkan sumber, klik untuk menambahkan Ringkasan, Laporan, atau Peta Pikiran.</p>
                </div>

            </div>

            <div style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); width: 90%;">
                <button style="width: 100%; background: white; color: black; border: none; padding: 12px; border-radius: 24px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 10px; cursor: pointer; box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
                    <i class="fas fa-edit"></i> Tambahkan catatan
                </button>
            </div>
        </aside>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const workspaceId = {{ $workspace->id }};

        // Kumpulkan semua teks dari database yang sudah dirender di halaman
        let allDocumentText = ``;
        @foreach($documents as $doc)
            allDocumentText += `[File: {{ $doc->file_name }}]\n{{ $doc->content }}\n\n`;
        @endforeach

        // ==========================================
        // FITUR TOAST NOTIFICATION (PENGGANTI ALERT)
        // ==========================================
        function showToast(message, type = 'info') {
            let container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div'); container.id = 'toast-container';
                container.style.cssText = 'position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 100000; display: flex; flex-direction: column; gap: 10px; pointer-events: none;';
                document.body.appendChild(container);

                // Tambahkan animasi CSS
                const style = document.createElement('style');
                style.innerHTML = `@keyframes slideDownLLM { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }`;
                document.head.appendChild(style);
            }
            const toast = document.createElement('div');
            const icon = type === 'success' ? 'check-circle' : (type === 'error' ? 'exclamation-circle' : 'info-circle');
            const color = type === 'success' ? '#10b981' : (type === 'error' ? '#ef4444' : '#3b82f6');
            toast.style.cssText = `background: rgba(30, 41, 59, 0.95); color: white; padding: 12px 24px; border-radius: 12px; font-size: 0.9rem; display: flex; align-items: center; gap: 10px; animation: slideDownLLM 0.3s ease forwards; backdrop-filter: blur(8px); border-left: 4px solid ${color}; box-shadow: 0 10px 25px rgba(0,0,0,0.3);`;
            toast.innerHTML = `<i class="fas fa-${icon}"></i> <span>${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s'; setTimeout(() => toast.remove(), 300); }, 3000);
        }

        // ==========================================
        // 1. ENGINE UPLOAD & EKSTRAK PDF (UPGRADED)
        // ==========================================
        document.getElementById('llmFileInput').addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;

            showToast("Membaca PDF: " + file.name + "...", "info");

            try {
                // Ekstrak teks pakai PDF.js
                const arrayBuffer = await file.arrayBuffer();
                const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
                let text = "";
                // Batasi max 25 halaman agar server tidak meledak
                const maxPages = Math.min(pdf.numPages, 25);
                for (let i = 1; i <= maxPages; i++) {
                    const page = await pdf.getPage(i);
                    const content = await page.getTextContent();
                    text += content.items.map(item => item.str).join(" ") + "\n";
                }

                // JURUS ANTI ERROR LARAVEL: Cek apakah teks kosong!
                if (text.trim() === "") {
                    showToast("Gagal: PDF kosong atau merupakan hasil scan (tanpa teks).", "error");
                    e.target.value = ''; // Reset input
                    return;
                }

                // Potong jika terlalu besar (Chunking Lite) - Maks 25rb karakter
                if (text.length > 25000) text = text.substring(0, 25000) + "\n\n[INFO: TEKS DIPOTONG]";

                // Kirim ke Backend untuk disimpan permanen
                const response = await fetch("{{ route('sahaja-llm.upload') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken },
                    body: JSON.stringify({ workspace_id: workspaceId, file_name: file.name, content: text })
                });

                const data = await response.json();
                if(data.success) {
                    showToast("Sukses mengunggah dokumen!", "success");
                    setTimeout(() => { location.reload(); }, 1500); // Beri jeda sedikit sebelum refresh
                } else {
                    showToast("Gagal menyimpan ke database: " + data.message, "error");
                }
            } catch (err) {
                showToast("Error membaca PDF: " + err.message, "error");
            }
            e.target.value = ''; // Reset input
        });

        // ==========================================
        // 2. ENGINE STUDIO (DYNAMIC PROMPT INJECTION)
        // ==========================================
        async function generateStudio(type) {
            if (allDocumentText.trim() === "") {
                showToast("Harap unggah minimal 1 PDF terlebih dahulu di panel Sumber!", "error");
                return;
            }

            // Siapkan Instruksi Rahasia berdasarkan tombol yang diklik
            let secretPrompt = "";
            if(type === 'ringkasan') secretPrompt = "Buatkan ringkasan komprehensif dari dokumen ini.";
            if(type === 'kuis') secretPrompt = "Buatkan 5 soal pilihan ganda beserta kunci jawabannya berdasarkan dokumen ini.";
            if(type === 'laporan') secretPrompt = "Susun laporan terstruktur (Latar Belakang, Isi, Kesimpulan) dari dokumen ini.";
            if(type === 'mindmap') secretPrompt = "Buatkan kerangka Peta Pikiran (Mind Map) menggunakan format bullet points dari dokumen ini.";
            if(type === 'slide') secretPrompt = "Buatkan struktur materi presentasi (Slide 1, Slide 2, dst) yang siap dipindahkan ke PowerPoint dari dokumen ini.";
            if(type === 'tabel') secretPrompt = "Ekstrak data-data penting, angka, atau fakta dari dokumen ini ke dalam format Tabel Markdown.";

            const finalPayload = `[SUMBER PENGETAHUAN DARI WORKSPACE]\n"""\n${allDocumentText}\n"""\n\nInstruksi AI: ${secretPrompt}`;

            // Hapus layar ucapan selamat datang, ganti dengan UI loading
            document.querySelector('.greeting-area').innerHTML = `
                <div id="aiResponseArea" style="background: rgba(0,0,0,0.4); border: 1px solid var(--glass-border); border-radius: 16px; padding: 25px; min-height: 300px;">
                    <h3 style="color: var(--accent-color); margin-bottom: 15px;"><i class="fas fa-magic fa-spin"></i> Menghasilkan ${type}...</h3>
                    <div class="markdown-body" id="aiResultText">Mohon tunggu, AI sedang membaca dan menyusun...</div>
                </div>
            `;

            // Tembak ke API Chat yang sudah ada sebelumnya!
            try {
                const response = await fetch("{{ route('chat.send') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" },
                    body: JSON.stringify({
                        message: finalPayload,
                        session_id: null,
                        manual_mode: 'coding',
                        enable_thinking: false
                    })
                });

                const data = await response.json();
                if (data.error) throw new Error(data.message);

                // Render hasil Markdown-nya ke layar tengah
                document.getElementById('aiResultText').innerHTML = marked.parse(data.ai_response);
            } catch (error) {
                document.getElementById('aiResultText').innerHTML = `<span style="color:#ef4444;">Terjadi Kesalahan: ${error.message}</span>`;
            }
        }
        // ==========================================
        // 3. ENGINE CHAT LLM (CHAT DI TENGAH)
        // ==========================================
        async function sendLlmChat() {
            const input = document.getElementById('llmChatInput');
            const message = input.value.trim();
            if (!message) return;
            if (allDocumentText.trim() === "") {
                showToast("Unggah dokumen dulu bosku!", "error");
                return;
            }

            input.value = "";
            input.disabled = true;

            // UI Loading di area tengah
            const greetingArea = document.querySelector('.greeting-area');
            if (!document.getElementById('aiResponseArea')) {
                greetingArea.innerHTML = `
                    <div id="aiResponseArea" style="background: rgba(0,0,0,0.4); border: 1px solid var(--glass-border); border-radius: 16px; padding: 25px; min-height: 300px; display: flex; flex-direction: column; gap: 15px;">
                        <div id="llmChatHistory" style="flex: 1; overflow-y: auto; display: flex; flex-direction: column; gap: 15px; margin-bottom: 10px;"></div>
                    </div>
                `;
            }

            const history = document.getElementById('llmChatHistory');
            history.innerHTML += `<div style="align-self: flex-end; background: var(--accent-color); padding: 10px 15px; border-radius: 15px 15px 0 15px; max-width: 80%; font-size: 0.9rem;">${message}</div>`;

            const loadingDiv = document.createElement('div');
            loadingDiv.style.cssText = "align-self: flex-start; color: var(--llm-accent); font-size: 0.85rem;";
            loadingDiv.innerHTML = `<i class="fas fa-circle-notch fa-spin"></i> SAHAJA sedang membaca...`;
            history.appendChild(loadingDiv);

            const finalPayload = `[REFERENSI DOKUMEN]\n"""\n${allDocumentText}\n"""\n\nPertanyaan User: ${message}`;

            try {
                const response = await fetch("{{ route('chat.send') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" },
                    body: JSON.stringify({
                        message: finalPayload,
                        session_id: null,
                        manual_mode: 'coding', // Kita ganti ke Qwen (Coding) agar lebih ngebut baca teks besar!
                        enable_thinking: false // Matikan thinking agar tidak timeout
                    })
                });

                const data = await response.json();
                loadingDiv.remove();

                if (data.error) throw new Error(data.message);

                history.innerHTML += `
                    <div style="align-self: flex-start; background: rgba(255,255,255,0.05); padding: 15px; border-radius: 15px 15px 15px 0; max-width: 90%; font-size: 0.9rem; border: 1px solid var(--glass-border);" class="markdown-body">
                        ${marked.parse(data.ai_response)}
                    </div>
                `;
            } catch (error) {
                loadingDiv.innerHTML = `<span style="color:#ef4444;">Error: ${error.message}</span>`;
            } finally {
                input.disabled = false;
                input.focus();
            }
        }
    </script>
</body>
</html>

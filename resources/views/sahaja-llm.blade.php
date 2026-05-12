<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAHAJA LLM - Workspace</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    </script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <link rel="icon" type="image/png" href="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --main-bg: #0a0e17;
            --panel-bg: #151b23;
            --glass-border: rgba(255, 255, 255, 0.1);
            --glass-hover: rgba(255, 255, 255, 0.05);
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --accent-color: #2563eb;
            --llm-accent: #10b981;
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
            z-index: 100;
        }

        .header-left,
        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-header {
            background: transparent;
            color: var(--text-primary);
            border: 1px solid var(--glass-border);
            padding: 6px 14px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-header:hover {
            background: var(--glass-hover);
        }

        /* ===== GRID LAYOUT (3 PANEL) ===== */
        .llm-workspace {
            display: grid;
            grid-template-columns: 300px 1fr 300px;
            gap: 15px;
            padding: 15px;
            height: calc(100vh - 60px);
            overflow: hidden;
        }

        .panel {
            background: var(--panel-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
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
            border-bottom: 1px solid var(--glass-border);
        }

        /* ===== PANEL KIRI (SUMBER) ===== */
        .source-content {
            padding: 15px;
            overflow-y: auto;
            flex: 1;
        }

        .btn-add-source {
            width: 100%;
            background: transparent;
            border: 1px dashed var(--llm-accent);
            color: var(--llm-accent);
            padding: 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: 0.2s;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .btn-add-source:hover {
            background: rgba(16, 185, 129, 0.1);
        }

        .doc-item {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: 0.2s;
        }

        .doc-item:hover {
            background: var(--glass-hover);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .empty-state {
            text-align: center;
            color: var(--text-secondary);
            margin-top: 30px;
            padding: 0 10px;
        }

        .empty-state i {
            font-size: 2rem;
            margin-bottom: 15px;
            opacity: 0.3;
        }

        .empty-state p {
            font-size: 0.8rem;
            line-height: 1.6;
        }

        /* ===== PANEL TENGAH (CHAT / MAIN) ===== */
        /* PERBAIKAN BUG TERPOTONG: Hilangkan justify-content: center di sini! */
        .chat-content {
            flex: 1;
            overflow-y: auto;
            padding: 20px 40px;
            display: flex;
            flex-direction: column;
            scroll-behavior: smooth;
        }

        .greeting-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .greeting-wrapper h1 {
            font-size: 1.8rem;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .greeting-wrapper p {
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.6;
            max-width: 500px;
        }

        /* Area Chat History */
        #llmChatHistory {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .chat-msg {
            display: flex;
            flex-direction: column;
            gap: 5px;
            max-width: 90%;
        }

        .chat-msg.user {
            align-self: flex-end;
        }

        .chat-msg.ai {
            align-self: flex-start;
        }

        .bubble {
            padding: 15px 20px;
            font-size: 0.95rem;
            line-height: 1.6;
            border-radius: 16px;
            word-wrap: break-word;
        }

        .chat-msg.user .bubble {
            background: var(--accent-color);
            color: white;
            border-bottom-right-radius: 4px;
        }

        .chat-msg.ai .bubble {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            border-bottom-left-radius: 4px;
        }

        /* Animasi Text Mulus */
        .gemini-block {
            opacity: 0;
            transform: translateY(15px);
            transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .gemini-block.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Input Area */
        .chat-input-wrapper {
            padding: 15px 20px;
            background: var(--panel-bg);
            border-top: 1px solid var(--glass-border);
            display: flex;
            flex-direction: column;
            align-items: center;
            /* KUNCI RAHASIA: Memaksa semua isi ke tengah */
        }

        .chat-input-box {
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            width: 100%;
            max-width: 800px;
            /* Biar ukurannya proporsional di layar besar */
        }

        .chat-input-box input {
            flex: 1;
            background: transparent;
            border: none;
            color: white;
            outline: none;
            font-size: 0.95rem;
        }

        .source-count {
            font-size: 0.75rem;
            color: var(--llm-accent);
            font-weight: bold;
            background: rgba(16, 185, 129, 0.1);
            padding: 4px 10px;
            border-radius: 12px;
            white-space: nowrap;
        }

        .btn-send {
            background: var(--text-primary);
            color: var(--main-bg);
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.2s;
            flex-shrink: 0;
        }

        .btn-send:hover {
            transform: scale(1.1);
        }

        .input-footer {
            text-align: center;
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 12px;
            opacity: 0.7;
            /* Sedikit dipudarkan agar elegan */
            font-weight: 500;
        }

        /* ===== PANEL KANAN (STUDIO) ===== */
        .studio-content {
            padding: 15px;
            overflow-y: auto;
            flex: 1;
        }

        .studio-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .studio-btn {
            background: transparent;
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 15px 10px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            color: var(--text-secondary);
            cursor: pointer;
            transition: 0.2s;
            text-align: left;
        }

        .studio-btn i {
            font-size: 1.2rem;
            color: var(--text-primary);
        }

        .studio-btn span {
            font-size: 0.75rem;
        }

        .studio-btn:hover {
            background: var(--glass-hover);
            color: white;
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* Stylingan Markdown */
        .markdown-body pre {
            background: #000;
            padding: 15px;
            border-radius: 8px;
            overflow-x: auto;
            margin: 10px 0;
            border: 1px solid var(--glass-border);
        }

        .markdown-body code {
            font-family: monospace;
            color: #60a5fa;
        }

        .markdown-body ul,
        .markdown-body ol {
            margin-left: 20px;
            margin-bottom: 10px;
        }

        .markdown-body p {
            margin-bottom: 10px;
        }

        /* ===== RESPONSIVE KHUSUS MOBILE ===== */
        .mobile-toggle {
            display: none;
            background: transparent;
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            color: white;
            padding: 6px 12px;
            font-size: 1rem;
            cursor: pointer;
        }

        @media (max-width: 1024px) {
            .llm-workspace {
                grid-template-columns: 280px 1fr;
            }

            .studio-panel {
                position: fixed;
                right: -100%;
                top: 60px;
                height: calc(100vh - 60px);
                width: 300px;
                z-index: 1000;
                transition: 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
                border-radius: 0;
                border-left: 1px solid var(--glass-border);
            }

            .studio-panel.show {
                right: 0;
                box-shadow: -10px 0 30px rgba(0, 0, 0, 0.5);
            }

            .mobile-toggle {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .llm-workspace {
                grid-template-columns: 1fr;
                padding: 0;
                gap: 0;
            }

            .panel.chat-panel {
                border-radius: 0;
                border: none;
            }

            .source-panel {
                position: fixed;
                left: -100%;
                top: 60px;
                height: calc(100vh - 60px);
                width: 280px;
                z-index: 1000;
                transition: 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
                border-radius: 0;
                border-right: 1px solid var(--glass-border);
            }

            .source-panel.show {
                left: 0;
                box-shadow: 10px 0 30px rgba(0, 0, 0, 0.5);
            }

            .header-right .btn-header span {
                display: none;
            }

            .chat-content {
                padding: 20px 15px;
            }
        }
    </style>
</head>

<body>

    <header class="llm-header">
        <div class="header-left">
            <button class="mobile-toggle" onclick="document.getElementById('sourcePanel').classList.toggle('show')">
                <i class="fas fa-folder"></i>
            </button>
            <a href="{{ route('chat.index') }}" class="btn-header"><i class="fas fa-arrow-left"></i>
                <span>Kembali</span></a>
            <span style="font-weight: 600; font-size: 1.1rem; margin-left: 10px;"><i class="fas fa-book-reader"
                    style="color: var(--llm-accent); margin-right: 5px;"></i> SAHAJA LLM</span>
        </div>
        <div class="header-right">
            <button class="mobile-toggle" onclick="document.getElementById('studioPanel').classList.toggle('show')">
                <i class="fas fa-layer-group"></i>
            </button>
            <div
                style="width: 32px; height: 32px; background: var(--accent-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.85rem;">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </div>
        </div>
    </header>

    <div class="llm-workspace">

        <aside class="panel source-panel" id="sourcePanel">
            <div class="panel-header">
                File Sumber
                <button onclick="document.getElementById('sourcePanel').classList.remove('show')"
                    style="background:transparent; border:none; color:var(--text-secondary); cursor:pointer; display: none;"
                    class="mobile-close"><i class="fas fa-times"></i></button>
            </div>
            <div class="source-content">
                <input type="file" id="llmFileInput" accept=".pdf" style="display: none;">
                <button class="btn-add-source" onclick="document.getElementById('llmFileInput').click()">
                    <i class="fas fa-upload"></i> Unggah PDF Baru
                </button>

                <div id="documentListContainer">
                    @forelse($documents as $doc)
                        <div class="doc-item" id="doc-{{ $doc->id }}">
                            <div style="display: flex; align-items: center; gap: 10px; overflow: hidden; flex: 1;">
                                <i class="fas fa-file-pdf" style="color: #ef4444; font-size: 1.2rem;"></i>
                                <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                    title="{{ $doc->file_name }}">{{ $doc->file_name }}</span>
                            </div>
                            <button onclick="deleteDocument({{ $doc->id }})"
                                style="background: transparent; border: none; color: var(--text-secondary); cursor: pointer; padding: 5px; margin-left: 5px;"
                                title="Hapus file">
                                <i class="fas fa-times hover-danger" onmouseover="this.style.color='#ef4444'"
                                    onmouseout="this.style.color='var(--text-secondary)'"></i>
                            </button>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-folder-open"></i>
                            <p><strong>Belum ada sumber</strong><br>Unggah file PDF materi Anda untuk mulai berdiskusi.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </aside>

        <main class="panel chat-panel" onclick="closeMobiles()">
            <div class="chat-content" id="centerChatContent">

                <div class="greeting-wrapper" id="welcomeGreeting">

                    <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="Logo SAHAJA AI"
                        class="welcome-logo-img">

                    <h1>Halo! Saya Pakar Dokumen Anda.</h1>
                    <p>Unggah modul/PDF di sebelah kiri, lalu gunakan menu <b>Studio</b> di kanan untuk merangkum, atau
                        tanyakan langsung di kotak bawah!</p>
                </div>

                <div id="llmChatHistory"></div>

            </div>

            <div class="chat-input-wrapper">
                <div class="chat-input-box">
                    <input type="text" id="llmChatInput" placeholder="Tanyakan isi dokumen ..."
                        onkeydown="if(event.key==='Enter') sendLlmChat()">
                    <span class="source-count">{{ count($documents) }} sumber</span>
                    <button class="btn-send" onclick="sendLlmChat()" id="btnSendChat"><i
                            class="fas fa-arrow-up"></i></button>
                </div>
                <div class="input-footer">SAHAJA LLM dapat berhalusinasi, harap verifikasi fakta penting.</div>
            </div>
        </main>

        <aside class="panel studio-panel" id="studioPanel">
            <div class="panel-header">
                Studio Generator
                <button onclick="document.getElementById('studioPanel').classList.remove('show')"
                    style="background:transparent; border:none; color:var(--text-secondary); cursor:pointer; display: none;"
                    class="mobile-close"><i class="fas fa-times"></i></button>
            </div>
            <div class="studio-content">
                <div class="studio-grid">
                    <button class="studio-btn"
                        onclick="generateStudio('Ringkasan', 'Buatkan ringkasan lengkap dan mudah dipahami dari semua dokumen ini.')"><i
                            class="fas fa-align-left"></i> <span>Ringkasan</span></button>
                    <button class="studio-btn"
                        onclick="generateStudio('Struktur Presentasi', 'Susun kerangka materi presentasi (Slide 1, Slide 2, dst) berdasarkan dokumen ini.')"><i
                            class="fas fa-tv"></i> <span>Slide...</span></button>
                    <button class="studio-btn"
                        onclick="generateStudio('Laporan Analisis', 'Susun laporan terstruktur (Latar Belakang, Isi Utama, Kesimpulan) dari dokumen ini.')"><i
                            class="fas fa-file-alt"></i> <span>Laporan</span></button>
                    <button class="studio-btn"
                        onclick="generateStudio('Peta Pikiran', 'Buatkan kerangka Peta Pikiran (Mind Map) hierarkis dari konsep utama dokumen ini.')"><i
                            class="fas fa-project-diagram"></i> <span>Peta Pikiran</span></button>
                    <button class="studio-btn"
                        onclick="generateStudio('Kuis & Ujian', 'Buatkan 5 soal pilihan ganda yang menantang beserta kunci jawaban dari dokumen ini.')"><i
                            class="fas fa-question-circle"></i> <span>Kuis</span></button>
                    <button class="studio-btn"
                        onclick="generateStudio('Ekstrak Tabel', 'Ekstrak data-data penting atau perbandingan konsep dari dokumen ini ke dalam format Tabel Markdown.')"><i
                            class="fas fa-table"></i> <span>Tabel Data</span></button>
                </div>
            </div>
        </aside>

    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const workspaceId = {{ $workspace->id }};

        let allDocumentText = ``;
        @foreach ($documents as $doc)
            allDocumentText += `[File: {{ $doc->file_name }}]\n{{ $doc->content }}\n\n`;
        @endforeach

        // Helper Tutup Panel di HP
        function closeMobiles() {
            if (window.innerWidth <= 1024) {
                document.getElementById('sourcePanel').classList.remove('show');
                document.getElementById('studioPanel').classList.remove('show');
            }
        }

        // Atur tombol close X muncul di HP
        if (window.innerWidth <= 1024) document.querySelectorAll('.mobile-close').forEach(b => b.style.display = 'block');

        // FITUR TOAST NOTIFICATION
        function showToast(message, type = 'info') {
            let container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                container.style.cssText =
                    'position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 100000; display: flex; flex-direction: column; gap: 10px; pointer-events: none;';
                document.body.appendChild(container);
                const style = document.createElement('style');
                style.innerHTML =
                    `@keyframes slideDownLLM { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }`;
                document.head.appendChild(style);
            }
            const toast = document.createElement('div');
            const icon = type === 'success' ? 'check-circle' : (type === 'error' ? 'exclamation-circle' : 'info-circle');
            const color = type === 'success' ? '#10b981' : (type === 'error' ? '#ef4444' : '#3b82f6');
            toast.style.cssText =
                `background: rgba(30, 41, 59, 0.95); color: white; padding: 12px 24px; border-radius: 12px; font-size: 0.9rem; display: flex; align-items: center; gap: 10px; animation: slideDownLLM 0.3s ease forwards; backdrop-filter: blur(8px); border-left: 4px solid ${color}; box-shadow: 0 10px 25px rgba(0,0,0,0.3);`;
            toast.innerHTML = `<i class="fas fa-${icon}"></i> <span>${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.3s';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function scrollToBottom() {
            const c = document.getElementById('centerChatContent');
            if (c) c.scrollTop = c.scrollHeight;
        }

        // FUNGSI ANIMASI KETIK (RENDER BLOCK BY BLOCK)
        function animateResponse(element, htmlContent) {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = htmlContent;
            element.innerHTML = '';

            Array.from(tempDiv.children).forEach(child => {
                const wrapper = document.createElement('div');
                wrapper.className = 'gemini-block';
                wrapper.appendChild(child);
                element.appendChild(wrapper);
            });

            let delay = 0;
            element.querySelectorAll('.gemini-block').forEach((block) => {
                setTimeout(() => {
                    block.classList.add('show');
                    scrollToBottom();
                }, delay);
                delay += 150;
            });
        }

        // ==========================================
        // 1. FITUR DELETE DOCUMENT
        // ==========================================
        async function deleteDocument(id) {
            if (!confirm("Yakin ingin menghapus dokumen ini dari sumber?")) return;
            showToast("Menghapus...", "info");
            try {
                const res = await fetch(`/sahaja-llm/document/${id}`, {
                    method: 'DELETE',
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json"
                    }
                });
                const data = await res.json();
                if (data.success) {
                    document.getElementById(`doc-${id}`).remove();
                    showToast("Dokumen dihapus!", "success");
                    setTimeout(() => location.reload(), 1000); // Reload agar memori teks bersih
                } else {
                    showToast("Gagal menghapus", "error");
                }
            } catch (e) {
                showToast("Terjadi kesalahan jaringan", "error");
            }
        }

        // ==========================================
        // 2. ENGINE UPLOAD & EKSTRAK PDF
        // ==========================================
        document.getElementById('llmFileInput').addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;

            showToast("Membaca PDF: " + file.name + "...", "info");

            try {
                const arrayBuffer = await file.arrayBuffer();
                const pdf = await pdfjsLib.getDocument({
                    data: arrayBuffer
                }).promise;
                let text = "";
                const maxPages = Math.min(pdf.numPages, 25);
                for (let i = 1; i <= maxPages; i++) {
                    const page = await pdf.getPage(i);
                    const content = await page.getTextContent();
                    text += content.items.map(item => item.str).join(" ") + "\n";
                }

                if (text.trim() === "") {
                    showToast("Gagal: PDF kosong / hasil scan gambar.", "error");
                    e.target.value = '';
                    return;
                }

                if (text.length > 25000) text = text.substring(0, 25000) + "\n\n[INFO: TEKS DIPOTONG]";

                const response = await fetch("{{ route('sahaja-llm.upload') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({
                        workspace_id: workspaceId,
                        file_name: file.name,
                        content: text
                    })
                });

                const data = await response.json();
                if (data.success) {
                    showToast("Sukses mengunggah dokumen!", "success");
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast("Gagal menyimpan ke database", "error");
                }
            } catch (err) {
                showToast("Error membaca PDF", "error");
            }
            e.target.value = '';
        });

        // ==========================================
        // 3. ENGINE CHAT & STUDIO
        // ==========================================
        async function processAI(messageStr, mode = 'coding') {
            const history = document.getElementById('llmChatHistory');
            const welcome = document.getElementById('welcomeGreeting');
            if (welcome) welcome.style.display = 'none';

            // Munculkan chat user
            const isStudio = messageStr.startsWith("Buatkan"); // Deteksi kalau dari tombol Studio
            const displayUserMsg = isStudio ? `<b>[Perintah Studio]</b> ${messageStr}` : messageStr;

            history.innerHTML += `<div class="chat-msg user"><div class="bubble">${displayUserMsg}</div></div>`;

            // Munculkan Loading AI
            const loadingId = 'loading-' + Date.now();
            history.innerHTML +=
                `<div class="chat-msg ai" id="${loadingId}"><div class="bubble" style="color: var(--llm-accent);"><i class="fas fa-circle-notch fa-spin"></i> SAHAJA sedang memproses dokumen...</div></div>`;
            scrollToBottom();

            const finalPayload =
                `[REFERENSI DOKUMEN]\n"""\n${allDocumentText}\n"""\n\nPertanyaan/Instruksi User: ${messageStr}`;

            try {
                const response = await fetch("{{ route('chat.send') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        message: finalPayload,
                        session_id: null,
                        manual_mode: mode, // Qwen untuk ngebut
                        enable_thinking: false
                    })
                });

                const data = await response.json();
                if (data.error) throw new Error(data.message);

                // Ganti Kotak Loading dengan Kotak Hasil
                const loadingBox = document.getElementById(loadingId);
                loadingBox.innerHTML = `<div class="bubble markdown-body" id="result-${loadingId}"></div>`;

                // Terapkan Animasi
                const resultBox = document.getElementById(`result-${loadingId}`);
                animateResponse(resultBox, marked.parse(data.ai_response));

            } catch (error) {
                document.getElementById(loadingId).innerHTML =
                    `<div class="bubble" style="color:#ef4444;"><i class="fas fa-exclamation-triangle"></i> Terjadi Kesalahan: ${error.message}</div>`;
            }
        }

        // Trigger Tombol Studio
        function generateStudio(title, prompt) {
            if (allDocumentText.trim() === "") return showToast("Unggah dokumen terlebih dahulu!", "error");
            closeMobiles(); // Tutup panel kanan di HP
            processAI(`Buatkan ${title}.\n\nInstruksi: ${prompt}`);
        }

        // Trigger Input Bawah
        function sendLlmChat() {
            const input = document.getElementById('llmChatInput');
            const message = input.value.trim();
            if (!message) return;
            if (allDocumentText.trim() === "") return showToast("Unggah dokumen terlebih dahulu!", "error");

            input.value = "";
            processAI(message);
        }
    </script>
</body>

</html>

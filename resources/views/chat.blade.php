<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SAHAJA AI</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <style>
        /* --- CSS BAWAAN KAMU (SAYA RAPIKAN SEDIKIT) --- */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', system-ui, sans-serif; }

        :root {
            --sidebar-bg: #0f172a;
            --main-bg: #0a0e17;
            --message-user-bg: #1a5fb4;
            --accent-color: #1a5fb4;
            --accent-light: #62a0ea;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --hover-bg: rgba(98, 160, 234, 0.1);
            --glass-bg: rgba(15, 27, 45, 0.7);
            --border-color: rgba(98, 160, 234, 0.15);
        }

        body { background-color: var(--main-bg); color: var(--text-primary); height: 100vh; overflow: hidden; display: flex; }

        /* Sidebar */
        .sidebar { width: 260px; background-color: var(--sidebar-bg); display: flex; flex-direction: column; border-right: 1px solid var(--border-color); transition: transform 0.3s ease; z-index: 10; }
        .sidebar-header { padding: 16px; border-bottom: 1px solid var(--border-color); }

        .new-chat-btn { background: linear-gradient(135deg, var(--accent-color), #26c6da); color: white; border: none; border-radius: 8px; padding: 10px 16px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 8px; width: 100%; transition: all 0.2s; }
        .new-chat-btn:hover { opacity: 0.9; transform: translateY(-1px); }

        .history-container { flex: 1; overflow-y: auto; padding: 10px; }
        .history-title { font-size: 0.75rem; color: var(--text-secondary); padding: 10px 12px 4px; text-transform: uppercase; letter-spacing: 0.05em; }
        .history-item { padding: 10px 12px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 12px; margin-bottom: 4px; transition: background-color 0.2s; }
        .history-item:hover { background-color: var(--hover-bg); }
        .history-text { font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .sidebar-footer { padding: 16px; border-top: 1px solid var(--border-color); }
        .user-info { display: flex; align-items: center; gap: 12px; padding: 10px; border-radius: 8px; cursor: pointer; }
        .user-info:hover { background-color: var(--hover-bg); }
        .user-avatar { width: 36px; height: 36px; border-radius: 8px; background: linear-gradient(135deg, var(--accent-color), var(--accent-light)); display: flex; align-items: center; justify-content: center; }

        /* Main Chat Area */
        .main-container { flex: 1; display: flex; flex-direction: column; overflow: hidden; position: relative; }
        .main-container::before { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 20% 30%, rgba(26, 95, 180, 0.1) 0%, transparent 50%), radial-gradient(circle at 80% 70%, rgba(38, 198, 218, 0.08) 0%, transparent 50%); z-index: -1; }

        .chat-header { padding: 16px 24px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border-color); background: var(--glass-bg); backdrop-filter: blur(10px); z-index: 5; }
        .chat-title { font-size: 1.2rem; font-weight: 600; display: flex; align-items: center; gap: 10px; }
        .model-badge { background-color: rgba(98, 160, 234, 0.2); border: 1px solid rgba(98, 160, 234, 0.3); border-radius: 12px; padding: 4px 10px; font-size: 0.75rem; color: var(--accent-light); }

        /* Messages */
        .messages-container { flex: 1; overflow-y: auto; padding: 24px; display: flex; flex-direction: column; gap: 24px; scroll-behavior: smooth; }
        .message { display: flex; gap: 20px; max-width: 100%; animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .message-avatar { width: 36px; height: 36px; border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .user-avatar-small { background: linear-gradient(135deg, var(--accent-color), #26c6da); }
        .ai-avatar-small { background: linear-gradient(135deg, #4f46e5, #7c3aed); }

        .message-content { flex: 1; padding-top: 4px; min-width: 0; /* Fix overflow text */ }
        .message-role { font-size: 0.9rem; font-weight: 600; margin-bottom: 8px; }
        .message-text { line-height: 1.6; font-size: 1rem; color: #e2e8f0; }

        /* Styling Markdown Results */
        .message-text p { margin-bottom: 12px; }
        .message-text pre { background: rgba(15, 23, 42, 0.8); padding: 16px; border-radius: 8px; overflow-x: auto; border: 1px solid rgba(98, 160, 234, 0.2); margin: 12px 0; }
        .message-text code { font-family: 'Courier New', monospace; color: #62a0ea; }
        .message-text pre code { color: #f8fafc; }
        .message-text ul { margin-left: 20px; margin-bottom: 12px; }

        .message-actions { display: flex; gap: 8px; margin-top: 12px; opacity: 0; transition: opacity 0.2s; }
        .message:hover .message-actions { opacity: 1; }
        .action-btn { background: none; border: none; color: var(--text-secondary); cursor: pointer; padding: 4px; transition: 0.2s; }
        .action-btn:hover { color: var(--text-primary); }

        /* Input Area */
        .input-container { padding: 20px 24px; background: var(--glass-bg); backdrop-filter: blur(10px); border-top: 1px solid var(--border-color); }
        .input-wrapper { max-width: 768px; margin: 0 auto; position: relative; }
        .chat-input { width: 100%; background-color: rgba(30, 40, 60, 0.6); border: 1px solid var(--border-color); border-radius: 12px; padding: 16px 52px 16px 20px; color: var(--text-primary); font-size: 1rem; resize: none; outline: none; transition: 0.2s; }
        .chat-input:focus { border-color: var(--accent-light); box-shadow: 0 0 0 2px rgba(98, 160, 234, 0.2); }

        .send-button { position: absolute; right: 12px; bottom: 12px; background: linear-gradient(135deg, var(--accent-color), #26c6da); color: white; border: none; border-radius: 8px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s; }
        .send-button:hover { transform: translateY(-1px); }
        .send-button:disabled { opacity: 0.5; cursor: not-allowed; }

        /* Welcome Screen */
        .welcome-screen { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px; text-align: center; }
        .welcome-title { font-size: 2.5rem; font-weight: 600; margin-bottom: 16px; background: linear-gradient(to right, #e6f1ff, var(--accent-light)); -webkit-background-clip: text; color: transparent; }
        .capabilities-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; max-width: 800px; width: 100%; margin-top: 40px; }
        .capability-card { background: var(--glass-bg); border: 1px solid rgba(98, 160, 234, 0.2); border-radius: 12px; padding: 20px; text-align: left; transition: 0.3s; }
        .capability-card:hover { transform: translateY(-5px); border-color: rgba(98, 160, 234, 0.5); }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: rgba(15, 27, 45, 0.3); }
        ::-webkit-scrollbar-thumb { background: linear-gradient(to bottom, var(--accent-color), var(--accent-light)); border-radius: 10px; }

        /* Typing Dots Animation */
        .typing-dots i { animation: bounce 1.4s infinite ease-in-out both; }
        @keyframes bounce { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }
    </style>
</head>
<body>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <form action="{{ route('chat.clear') }}" method="POST" onsubmit="return confirm('Hapus semua histori chat?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="new-chat-btn">
                    <i class="fas fa-plus"></i>
                    New Chat / Reset
                </button>
            </form>
        </div>

        <div class="history-container">
            <div class="history-title">Riwayat Chat</div>
            @foreach($chats->reverse()->take(5) as $history)
            <div class="history-item">
                <i class="fas fa-message" style="font-size: 0.8rem; color: #94a3b8;"></i>
                <span class="history-text">{{ Str::limit($history->user_message, 25) }}</span>
            </div>
            @endforeach

            @if($chats->isEmpty())
                <div class="history-item" style="cursor: default; opacity: 0.5;">
                    <span class="history-text">Belum ada riwayat</span>
                </div>
            @endif
        </div>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="user-details">
                    <div class="user-name">Admin</div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="chat-header">
            <div class="chat-title">
                <i class="fas fa-brain" style="color: var(--accent-light);"></i>
                SAHAJA AI
                <span class="model-badge">Powered by: Gemini 2.5</span>
            </div>
            <div>
                <button class="action-btn" title="Settings"><i class="fas fa-cog"></i></button>
            </div>
        </div>

        <div class="welcome-screen" id="welcomeScreen" style="{{ $chats->count() > 0 ? 'display: none;' : '' }}">
            <h1 class="welcome-title">SAHAJA AI</h1>
            <p style="color: var(--text-secondary);">Halo, Apa yang bisa saya bantu hari ini?</p>

            <div class="capabilities-grid">
                <div class="capability-card">
                    <i class="fas fa-code" style="color: var(--accent-light); margin-bottom: 10px;"></i>
                    <h3>Coding Helper</h3>
                    <p style="font-size: 0.9rem; color: var(--text-secondary);">Bantu buatkan kodingan Laravel, HTML, CSS</p>
                </div>
                <div class="capability-card">
                    <i class="fas fa-book" style="color: var(--accent-light); margin-bottom: 10px;"></i>
                    <h3>Tugas Kuliah</h3>
                    <p style="font-size: 0.9rem; color: var(--text-secondary);">Jelaskan materi Sistem Informasi</p>
                </div>
            </div>
        </div>

        <div class="messages-container" id="messagesContainer" style="{{ $chats->count() == 0 ? 'display: none;' : '' }}">
            @foreach($chats as $chat)
                <div class="message" style="flex-direction: row-reverse;">
                    <div class="message-avatar user-avatar-small">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="message-content">
                        <div class="message-role" style="text-align: right;">You</div>
                        <div class="message-text" style="background: var(--message-user-bg); padding: 12px 16px; border-radius: 12px 0 12px 12px;">
                            {{ $chat->user_message }}
                        </div>
                    </div>
                </div>

                <div class="message">
                    <div class="message-avatar ai-avatar-small">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="message-content">
                        <div class="message-role">SAHAJA AI</div>
                        <div class="message-text markdown-body">
                            {{ $chat->ai_response }}
                        </div>
                        <div class="message-actions">
                            <button class="action-btn" onclick="copyText(this)" title="Copy"><i class="far fa-copy"></i></button>
                            <button class="action-btn"><i class="far fa-thumbs-up"></i></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="input-container">
            <div class="input-wrapper">
                <textarea class="chat-input" id="chatInput" placeholder="Tanya sesuatu ke AI..." rows="1"></textarea>
                <button class="send-button" id="sendButton">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
            <p style="text-align: center; font-size: 0.75rem; color: var(--text-secondary); margin-top: 10px;">
                SAHAJA AI dapat membuat kesalahan. Cek kembali informasi penting.
            </p>
        </div>
    </div>

    <script>
        const chatInput = document.getElementById('chatInput');
        const sendButton = document.getElementById('sendButton');
        const messagesContainer = document.getElementById('messagesContainer');
        const welcomeScreen = document.getElementById('welcomeScreen');

        // 1. Auto Resize Textarea
        chatInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // 2. Format Markdown saat halaman dimuat
        document.querySelectorAll('.markdown-body').forEach(el => {
            el.innerHTML = marked.parse(el.innerText);
        });
        messagesContainer.scrollTop = messagesContainer.scrollHeight;

        // 3. Fungsi Kirim Pesan (AJAX)
        async function sendMessage() {
            const message = chatInput.value.trim();
            if (!message) return;

            // UI Updates
            welcomeScreen.style.display = 'none';
            messagesContainer.style.display = 'flex';
            chatInput.value = '';
            chatInput.style.height = 'auto';

            // Tampilkan Pesan User
            appendMessage('user', message);

            // Tampilkan Loading (Typing...)
            const loadingId = appendLoading();

            try {
                // Kirim ke Laravel
                const response = await fetch("{{ route('chat.send') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message: message })
                });

                const data = await response.json();

                // Hapus Loading & Tampilkan Balasan AI
                document.getElementById(loadingId).remove();
                appendMessage('ai', data.ai_response);

            } catch (error) {
                document.getElementById(loadingId).remove();
                alert("Gagal terhubung. Cek internet/server.");
            }
        }

        // Helper: Tampilkan Pesan di Layar
        function appendMessage(role, text) {
            const isUser = role === 'user';
            const div = document.createElement('div');
            div.className = 'message';
            if(isUser) div.style.flexDirection = 'row-reverse';

            const avatarHtml = isUser
                ? `<div class="message-avatar user-avatar-small"><i class="fas fa-user"></i></div>`
                : `<div class="message-avatar ai-avatar-small"><i class="fas fa-robot"></i></div>`;

            const contentStyle = isUser
                ? `background: var(--message-user-bg); padding: 12px 16px; border-radius: 12px 0 12px 12px;`
                : ``;

            // Parsing Markdown jika AI
            const formattedText = isUser ? text : marked.parse(text);

            const actionsHtml = isUser ? '' : `
                <div class="message-actions">
                    <button class="action-btn" onclick="copyText(this)"><i class="far fa-copy"></i></button>
                    <button class="action-btn"><i class="far fa-thumbs-up"></i></button>
                </div>`;

            div.innerHTML = `
                ${avatarHtml}
                <div class="message-content">
                    <div class="message-role" style="${isUser ? 'text-align: right;' : ''}">${isUser ? 'You' : 'NeuraGPT'}</div>
                    <div class="message-text" style="${contentStyle}">${formattedText}</div>
                    ${actionsHtml}
                </div>
            `;

            messagesContainer.appendChild(div);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Helper: Tampilkan Loading
        function appendLoading() {
            const id = 'loading-' + Date.now();
            const div = document.createElement('div');
            div.className = 'message';
            div.id = id;
            div.innerHTML = `
                <div class="message-avatar ai-avatar-small"><i class="fas fa-robot"></i></div>
                <div class="message-content">
                    <div class="message-role">NeuraGPT</div>
                    <div class="message-text">
                        <span class="typing-dots" style="font-size: 1.5rem;"><i class="fas fa-ellipsis-h"></i></span>
                    </div>
                </div>
            `;
            messagesContainer.appendChild(div);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
            return id;
        }

        // Helper: Copy Text
        function copyText(btn) {
            const text = btn.parentElement.previousElementSibling.innerText;
            navigator.clipboard.writeText(text);
            const icon = btn.querySelector('i');
            icon.className = 'fas fa-check';
            setTimeout(() => icon.className = 'far fa-copy', 2000);
        }

        // Event Listeners
        sendButton.addEventListener('click', sendMessage);
        chatInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    </script>
</body>
</html>

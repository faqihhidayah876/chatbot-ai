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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script>
        (function() {
            const theme = localStorage.getItem('sahaja-theme') || localStorage.getItem('theme');
            if (theme === 'light') {
                document.documentElement.classList.add('light-mode');
                document.addEventListener('DOMContentLoaded', () => {
                    document.body.classList.add('light-mode');
                });
            }
        })();
    </script>
    <style>
        :root {
            --bg-base: #11141a;
            --bg-subtle: #181c24;
            --bg-elevated: #1e232d;
            --bg-overlay: #232833;
            --bg-hover: rgba(255, 255, 255, 0.05);

            --border-subtle: rgba(255, 255, 255, 0.08);
            --border-medium: rgba(255, 255, 255, 0.14);
            --border-strong: rgba(255, 255, 255, 0.24);

            --text-primary: #f0f2f5;
            --text-secondary: #9aa0a6;
            --text-tertiary: #5f6368;
            --text-disabled: #3c4043;

            --accent: #3b82f6;
            --accent-hover: #2563eb;
            --accent-active: #1d4ed8;
            --accent-subtle: rgba(59, 130, 246, 0.12);
            --accent-border: rgba(59, 130, 246, 0.3);

            --success: #10b981;
            --success-subtle: rgba(16, 185, 129, 0.12);
            --warning: #f59e0b;
            --warning-subtle: rgba(245, 158, 11, 0.12);
            --danger: #ef4444;
            --danger-subtle: rgba(239, 68, 68, 0.12);

            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;

            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 20px;
            --radius-full: 9999px;

            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.3);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.4);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.5);

            --ease: cubic-bezier(0.4, 0, 0.2, 1);
            --duration-micro: 150ms;
            --duration-base: 200ms;
            --duration-macro: 350ms;

            --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-normal: 200ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: 350ms cubic-bezier(0.4, 0, 0.2, 1);

            /* Backward compatibility aliases */
            --main-bg: var(--bg-base);
            --panel-bg: var(--bg-subtle);
            --glass-border: var(--border-subtle);
            --glass-hover: var(--bg-hover);
            --accent-color: var(--accent);
            --llm-accent: var(--accent);
        }

        html.light-mode,
        html.light-mode body,
        body.light-mode {
            --bg-base: #f8fafc;
            --bg-subtle: #f1f5f9;
            --bg-elevated: #ffffff;
            --bg-overlay: #ffffff;
            --bg-hover: rgba(0, 0, 0, 0.04);

            --border-subtle: rgba(0, 0, 0, 0.08);
            --border-medium: rgba(0, 0, 0, 0.15);
            --border-strong: rgba(0, 0, 0, 0.25);

            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-tertiary: #94a3b8;
            --text-disabled: #cbd5e1;

            --accent: #2563eb;
            --accent-hover: #1d4ed8;
            --accent-active: #1e40af;
            --accent-subtle: rgba(37, 99, 235, 0.08);
            --accent-border: rgba(37, 99, 235, 0.25);

            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.12);

            --main-bg: var(--bg-base);
            --panel-bg: var(--bg-subtle);
            --glass-border: var(--border-subtle);
            --glass-hover: var(--bg-hover);
            --accent-color: var(--accent);
            --llm-accent: var(--accent);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--font-sans);
        }

        body {
            background-color: var(--bg-base);
            color: var(--text-primary);
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* ===== HEADER ===== */
        .llm-header {
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            border-bottom: 1px solid var(--border-subtle);
            background: var(--bg-base);
            flex-shrink: 0;
            z-index: 100;
        }

        .header-left,
        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-header {
            background: var(--bg-elevated);
            color: var(--text-primary);
            border: 1px solid var(--border-subtle);
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: var(--transition-fast);
        }

        .btn-header:hover {
            background: var(--bg-hover);
            border-color: var(--border-medium);
        }

        /* ===== GRID LAYOUT (3 PANEL) ===== */
        .llm-workspace {
            display: grid;
            grid-template-columns: 300px 1fr 300px;
            gap: 14px;
            padding: 14px;
            height: calc(100vh - 56px);
            overflow: hidden;
            background: var(--bg-base);
        }

        .panel {
            background: var(--bg-subtle);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            position: relative;
        }

        .panel-header {
            padding: 14px 18px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-subtle);
            background: var(--bg-subtle);
        }

        /* ===== PANEL KIRI (SUMBER) ===== */
        .source-content {
            padding: 16px;
            overflow-y: auto;
            flex: 1;
        }

        .btn-add-source {
            width: 100%;
            background: var(--accent-subtle);
            border: 1px dashed var(--accent);
            color: var(--accent);
            padding: 12px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: var(--transition-fast);
            margin-bottom: 16px;
        }

        .btn-add-source:hover {
            background: var(--accent);
            color: #ffffff;
        }

        .doc-item {
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            padding: 12px;
            border-radius: var(--radius-md);
            margin-bottom: 8px;
            font-size: 13px;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: var(--transition-fast);
        }

        .doc-item:hover {
            background: var(--bg-hover);
            border-color: var(--border-medium);
        }

        .empty-state {
            text-align: center;
            color: var(--text-secondary);
            margin-top: 30px;
            padding: 0 10px;
        }

        .empty-state i {
            font-size: 2rem;
            margin-bottom: 12px;
            opacity: 0.3;
        }

        .empty-state p {
            font-size: 13px;
            line-height: 1.6;
        }

        /* ===== PANEL TENGAH (CHAT / MAIN) ===== */
        .chat-content {
            flex: 1;
            overflow-y: auto;
            padding: 24px 32px;
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
            font-size: 1.6rem;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .greeting-wrapper p {
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.6;
            max-width: 520px;
        }

        .welcome-logo-img {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            margin-bottom: 16px;
        }

        /* Area Chat History */
        #llmChatHistory {
            display: flex;
            flex-direction: column;
            gap: 16px;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .chat-msg {
            display: flex;
            flex-direction: column;
            gap: 4px;
            max-width: 88%;
        }

        .chat-msg.user {
            align-self: flex-end;
        }

        .chat-msg.ai {
            align-self: flex-start;
        }

        .bubble {
            padding: 14px 18px;
            font-size: 14px;
            line-height: 1.6;
            border-radius: var(--radius-lg);
            word-wrap: break-word;
        }

        .chat-msg.user .bubble {
            background: var(--accent);
            color: #ffffff;
            border-bottom-right-radius: 4px;
        }

        .chat-msg.ai .bubble {
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            border-bottom-left-radius: 4px;
        }

        /* Animasi Text */
        .gemini-block {
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .gemini-block.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Input Area */
        .chat-input-wrapper {
            padding: 14px 20px;
            background: var(--bg-subtle);
            border-top: 1px solid var(--border-subtle);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .chat-input-box {
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-full);
            padding: 8px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            max-width: 800px;
            transition: border-color var(--transition-fast);
        }

        .chat-input-box:focus-within {
            border-color: var(--accent-border);
        }

        .chat-input-box input {
            flex: 1;
            background: transparent;
            border: none;
            color: var(--text-primary);
            outline: none;
            font-size: 14px;
        }

        .chat-input-box input::placeholder {
            color: var(--text-tertiary);
        }

        .source-count {
            font-size: 12px;
            color: var(--accent);
            font-weight: 600;
            background: var(--accent-subtle);
            padding: 4px 10px;
            border-radius: var(--radius-full);
            white-space: nowrap;
        }

        .btn-send {
            background: var(--accent);
            color: #ffffff;
            border: none;
            width: 34px;
            height: 34px;
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition-fast);
            flex-shrink: 0;
        }

        .btn-send:hover {
            background: var(--accent-hover);
        }

        .input-footer {
            text-align: center;
            font-size: 12px;
            color: var(--text-tertiary);
            margin-top: 8px;
            font-weight: 400;
        }

        /* ===== PANEL KANAN (STUDIO) ===== */
        .studio-content {
            padding: 16px;
            overflow-y: auto;
            flex: 1;
        }

        .studio-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .studio-btn {
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-md);
            padding: 14px 12px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
            color: var(--text-secondary);
            cursor: pointer;
            transition: var(--transition-fast);
            text-align: left;
        }

        .studio-btn i {
            font-size: 16px;
            color: var(--accent);
        }

        .studio-btn span {
            font-size: 12px;
            font-weight: 500;
        }

        .studio-btn:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
            border-color: var(--border-medium);
        }

        /* Styling Markdown */
        .markdown-body pre {
            background: var(--bg-base);
            padding: 14px;
            border-radius: var(--radius-md);
            overflow-x: auto;
            margin: 10px 0;
            border: 1px solid var(--border-subtle);
        }

        .markdown-body code {
            font-family: var(--font-mono);
            color: var(--accent);
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
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            padding: 6px 12px;
            font-size: 14px;
            cursor: pointer;
        }

        @media (max-width: 1024px) {
            .llm-workspace {
                grid-template-columns: 280px 1fr;
            }

            .studio-panel {
                position: fixed;
                right: -100%;
                top: 56px;
                height: calc(100vh - 56px);
                width: 300px;
                z-index: 1000;
                transition: var(--transition-normal);
                border-radius: 0;
                border-left: 1px solid var(--border-subtle);
            }

            .studio-panel.show {
                right: 0;
                box-shadow: var(--shadow-lg);
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
                top: 56px;
                height: calc(100vh - 56px);
                width: 280px;
                z-index: 1000;
                transition: var(--transition-normal);
                border-radius: 0;
                border-right: 1px solid var(--border-subtle);
            }

            .source-panel.show {
                left: 0;
                box-shadow: var(--shadow-lg);
            }

            .header-right .btn-header span {
                display: none;
            }

            .llm-header {
                padding: 0 12px;
            }

            .header-left span {
                font-size: 14px !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 140px;
            }

            .chat-content {
                padding: 16px 12px;
                padding-bottom: 110px;
            }

            .chat-input-wrapper {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                padding: 10px;
                padding-bottom: calc(10px + env(safe-area-inset-bottom));
                background: var(--bg-base);
                border-top: 1px solid var(--border-subtle);
                z-index: 100;
            }

            .chat-input-box {
                flex-wrap: nowrap;
                gap: 8px;
                width: 100%;
                border-radius: var(--radius-full);
                padding: 8px 12px;
            }

            .source-count {
                display: none;
            }
        }

        /* ===== MODAL KONFIRMASI CUSTOM ===== */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px);
            display: flex; align-items: center; justify-content: center;
            z-index: 100000; opacity: 0; visibility: hidden; transition: var(--transition-normal);
        }
        .modal-overlay.show { opacity: 1; visibility: visible; }

        .modal-content {
            background: var(--bg-overlay) !important; border: 1px solid var(--border-medium);
            border-radius: var(--radius-lg); padding: 24px; width: 90%; max-width: 400px;
            box-shadow: var(--shadow-lg); text-align: center;
            transform: translateY(16px); transition: var(--transition-normal);
        }
        .modal-overlay.show .modal-content { transform: translateY(0); }

        .btn-cancel {
            background: var(--bg-elevated); border: 1px solid var(--border-subtle); color: var(--text-primary);
            padding: 8px 16px; border-radius: var(--radius-sm); cursor: pointer; transition: var(--transition-fast); font-weight: 500; flex: 1;
        }
        .btn-cancel:hover { background: var(--bg-hover); border-color: var(--border-medium); }

        .btn-danger {
            background: var(--danger); border: none; color: #ffffff;
            padding: 8px 16px; border-radius: var(--radius-sm); cursor: pointer; transition: var(--transition-fast); font-weight: 500; flex: 1;
        }
        .btn-danger:hover { background: #dc2626; }

        /* ============================================================
           SCROLLBAR STYLING — Global
           ============================================================ */

        /* Firefox */
        * {
          scrollbar-width: thin;
          scrollbar-color: var(--border-medium) transparent;
        }

        body.light-mode *,
        html.light-mode * {
          scrollbar-color: var(--border-medium) transparent;
        }

        /* Webkit (Chrome, Edge, Safari, Opera, Brave) */
        *::-webkit-scrollbar {
          width: 8px;
          height: 8px;
        }

        *::-webkit-scrollbar-track {
          background: transparent;
        }

        *::-webkit-scrollbar-thumb {
          background: var(--border-medium);
          border-radius: var(--radius-full);
          border: 2px solid transparent;
          background-clip: padding-box;
          transition: background-color var(--duration-micro) var(--ease);
        }

        *::-webkit-scrollbar-thumb:hover {
          background: var(--text-tertiary);
          background-clip: padding-box;
          border: 2px solid transparent;
        }

        *::-webkit-scrollbar-thumb:active {
          background: var(--accent);
          background-clip: padding-box;
          border: 2px solid transparent;
        }

        *::-webkit-scrollbar-corner {
          background: transparent;
        }

        /* Thin scrollbar untuk komponen spesifik */
        .multi-file-container::-webkit-scrollbar,
        .suggested-actions-grid::-webkit-scrollbar,
        .comments-list::-webkit-scrollbar,
        .source-content::-webkit-scrollbar,
        .studio-content::-webkit-scrollbar {
          height: 4px;
          width: 6px;
        }

        .multi-file-container::-webkit-scrollbar-thumb,
        .suggested-actions-grid::-webkit-scrollbar-thumb,
        .comments-list::-webkit-scrollbar-thumb,
        .source-content::-webkit-scrollbar-thumb,
        .studio-content::-webkit-scrollbar-thumb {
          background: var(--border-medium);
          border-radius: var(--radius-full);
        }

        /* Scrollbar di code block (pre) — lebih tipis & subtle */
        .markdown-body pre::-webkit-scrollbar,
        .markdown-body pre::-webkit-scrollbar-track {
          height: 6px;
        }

        .markdown-body pre::-webkit-scrollbar-thumb {
          background: rgba(255, 255, 255, 0.15);
          border-radius: var(--radius-full);
        }

        body.light-mode .markdown-body pre::-webkit-scrollbar-thumb,
        html.light-mode .markdown-body pre::-webkit-scrollbar-thumb {
          background: rgba(0, 0, 0, 0.15);
        }

        .markdown-body pre::-webkit-scrollbar-thumb:hover {
          background: rgba(255, 255, 255, 0.3);
        }

        body.light-mode .markdown-body pre::-webkit-scrollbar-thumb:hover,
        html.light-mode .markdown-body pre::-webkit-scrollbar-thumb:hover {
          background: rgba(0, 0, 0, 0.3);
        }

        /* Firefox untuk code block */
        .markdown-body pre {
          scrollbar-width: thin;
          scrollbar-color: rgba(255, 255, 255, 0.15) transparent;
        }

        body.light-mode .markdown-body pre,
        html.light-mode .markdown-body pre {
          scrollbar-color: rgba(0, 0, 0, 0.15) transparent;
        }
    </style>
</head>

<body>

    <header class="llm-header">
        <div class="header-left">
            <button class="mobile-toggle" onclick="document.getElementById('sourcePanel').classList.toggle('show')" aria-label="Menu sumber">
                <i class="fas fa-folder"></i>
            </button>
            <a href="{{ route('chat.index') }}" class="btn-header" aria-label="Kembali ke chat">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
            <div style="display: flex; align-items: center; gap: 8px; margin-left: 6px;">
                <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="Logo SAHAJA AI"
                    style="width: 24px; height: 24px; border-radius: 4px; object-fit: contain;">
                <span style="font-weight: 600; font-size: 15px; color: var(--text-primary);">SAHAJA LLM</span>
            </div>
        </div>
        <div class="header-right">
            <button class="mobile-toggle" onclick="document.getElementById('studioPanel').classList.toggle('show')" aria-label="Menu studio">
                <i class="fas fa-layer-group"></i>
            </button>
            @if(Auth::user()->avatar)
                <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}"
                    style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border-subtle);">
            @else
                <div class="user-avatar" style="width: 32px; height: 32px; background: var(--bg-elevated); border: 1px solid var(--border-subtle); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 13px; color: var(--text-primary);">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
            @endif
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
                            <button onclick="confirmDelete({{ $doc->id }})"
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
        <div class="modal-overlay" id="confirmDangerModal">
        <div class="modal-content">
            <div style="font-size: 3.5rem; color: #ef4444; margin-bottom: 10px;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h2 style="font-size: 1.3rem; margin-bottom: 10px; color: var(--text-primary); font-weight: 600;">Hapus Dokumen?</h2>
            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 25px; line-height: 1.5;">
                Apakah Anda yakin ingin menghapus file ini dari sumber SAHAJA LLM?
            </p>
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button class="btn-cancel" onclick="closeConfirmModal()">Batal</button>
                <button class="btn-danger" id="btnConfirmDelete">Ya, Hapus</button>
            </div>
        </div>
    </div>
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
            const icon = type === 'success' ? 'circle-check' : (type === 'error' ? 'circle-exclamation' : 'circle-info');
            const color = type === 'success' ? 'var(--success)' : (type === 'error' ? 'var(--danger)' : 'var(--accent)');
            toast.style.cssText =
                `background: var(--bg-overlay); color: var(--text-primary); border: 1px solid var(--border-subtle); padding: 12px 20px; border-radius: var(--radius-md); font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 10px; animation: slideDownLLM 0.3s ease forwards; backdrop-filter: blur(8px); border-left: 4px solid ${color}; box-shadow: var(--shadow-md);`;
            toast.innerHTML = `<i class="fas fa-${icon}" style="color: ${color};"></i> <span>${message}</span>`;
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
        // 1. FITUR DELETE DOCUMENT (WITH CUSTOM MODAL)
        // ==========================================
        let documentIdToDelete = null;

        function confirmDelete(id) {
            documentIdToDelete = id;
            document.getElementById('confirmDangerModal').classList.add('show');
        }

        function closeConfirmModal() {
            documentIdToDelete = null;
            document.getElementById('confirmDangerModal').classList.remove('show');
        }

        document.getElementById('btnConfirmDelete').addEventListener('click', async function() {
            if (!documentIdToDelete) return;

            const id = documentIdToDelete;
            closeConfirmModal(); // Tutup modalnya dulu
            showToast("Menghapus dokumen...", "info");

            try {
                const res = await fetch(`/sahaja-llm/document/${id}`, {
                    method: 'DELETE',
                    headers: { "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" }
                });
                const data = await res.json();

                if (data.success) {
                    const docElement = document.getElementById(`doc-${id}`);
                    if (docElement) docElement.remove();

                    showToast("Dokumen berhasil dihapus!", "success");

                    // Update jumlah sumber di kotak input
                    const countElements = document.querySelectorAll('.source-count');
                    countElements.forEach(el => {
                        let currentCount = parseInt(el.innerText);
                        if(currentCount > 0) el.innerText = (currentCount - 1) + " sumber";
                    });

                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast("Gagal menghapus", "error");
                }
            } catch (e) {
                showToast("Terjadi kesalahan jaringan", "error");
            }
        });

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
                `<div class="chat-msg ai" id="${loadingId}"><div class="bubble" style="color: var(--accent);"><i class="fas fa-circle-notch fa-spin"></i> SAHAJA sedang memproses dokumen...</div></div>`;
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

/* ============================================================
   SAHAJA AI — Chat Interface Styles v2.0
   ============================================================ */

/* ===== DESIGN TOKENS ===== */
:root {
  --bg-base:      #0B0D12;
  --bg-subtle:    #12151C;
  --bg-elevated:  #1A1E27;
  --bg-overlay:   #232833;
  --bg-hover:     #1E2229;

  --border-subtle: rgba(255, 255, 255, 0.06);
  --border-medium: rgba(255, 255, 255, 0.10);
  --border-strong: rgba(255, 255, 255, 0.16);

  --text-primary:   #E8EAED;
  --text-secondary: #9AA0A6;
  --text-tertiary:  #5F6368;
  --text-disabled:  #3C4043;

  --accent:          #3B82F6;
  --accent-hover:    #60A5FA;
  --accent-active:   #2563EB;
  --accent-subtle:   rgba(59, 130, 246, 0.12);
  --accent-border:   rgba(59, 130, 246, 0.30);

  --success:        #10B981;
  --success-subtle: rgba(16, 185, 129, 0.12);
  --warning:        #F59E0B;
  --warning-subtle: rgba(245, 158, 11, 0.12);
  --danger:         #EF4444;
  --danger-subtle:  rgba(239, 68, 68, 0.12);

  --radius-sm:   6px;
  --radius-md:   10px;
  --radius-lg:   14px;
  --radius-xl:   20px;
  --radius-full: 9999px;

  --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.30);
  --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.35);
  --shadow-lg: 0 12px 32px rgba(0, 0, 0, 0.45);

  --ease:           cubic-bezier(0.4, 0, 0.2, 1);
  --duration-micro: 150ms;
  --duration-base:  200ms;
  --duration-macro: 350ms;

  --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  --font-mono: 'JetBrains Mono', 'Fira Code', monospace;
}

html.light-mode,
html.light-mode body,
body.light-mode {
  --bg-base:      #FAFBFC;
  --bg-subtle:    #F1F3F5;
  --bg-elevated:  #FFFFFF;
  --bg-overlay:   #FFFFFF;
  --bg-hover:     #EEF1F5;

  --border-subtle: rgba(0, 0, 0, 0.06);
  --border-medium: rgba(0, 0, 0, 0.10);
  --border-strong: rgba(0, 0, 0, 0.16);

  --text-primary:   #111827;
  --text-secondary: #4B5563;
  --text-tertiary:  #9CA3AF;
  --text-disabled:  #D1D5DB;

  --accent:          #2563EB;
  --accent-hover:    #1D4ED8;
  --accent-active:   #1E40AF;
  --accent-subtle:   rgba(37, 99, 235, 0.08);
  --accent-border:   rgba(37, 99, 235, 0.25);

  --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.06);
  --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.10);
  --shadow-lg: 0 12px 32px rgba(0, 0, 0, 0.14);
}

/* ===== REDUCED MOTION ===== */
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    transition-duration: 0.01ms !important;
  }
}

/* ===== GLOBAL RESET ===== */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* Anti-overflow absolute lock */
html, body {
  overflow-x: hidden !important;
  max-width: 100vw !important;
  width: 100% !important;
}

body {
  font-family: var(--font-sans);
  background: var(--bg-base);
  color: var(--text-primary);
  height: 100vh;
  height: 100dvh;
  overflow: hidden;
  display: flex;
  transition:
    background-color var(--duration-base) var(--ease),
    color var(--duration-base) var(--ease);
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

a {
  text-decoration: none;
  color: inherit;
}

button {
  cursor: pointer;
  border: none;
  outline: none;
  background: none;
  color: inherit;
  font-family: var(--font-sans);
}

/* ===== LAYOUT ===== */
.main-container {
  flex: 1;
  display: flex;
  flex-direction: column;
  height: 100vh;
  height: 100dvh;
  position: relative;
  overflow-x: hidden;
  max-width: 100vw;
  transition: all var(--duration-macro) var(--ease);
}

/* ===== SIDEBAR ===== */
.sidebar {
  width: 260px;
  background: var(--bg-subtle);
  border-right: 1px solid var(--border-subtle);
  display: flex;
  flex-direction: column;
  height: 100%;
  transition: width var(--duration-macro) var(--ease);
  z-index: 50;
  flex-shrink: 0;
  overflow: hidden;
  white-space: nowrap;
}

.sidebar.collapsed {
  width: 64px;
}

.sidebar.collapsed .text-label,
.sidebar.collapsed .brand-text,
.sidebar.collapsed .sidebar-footer-details,
.sidebar.collapsed .options-btn,
.sidebar.collapsed .brand-logo-container {
  display: none !important;
  opacity: 0;
}

.sidebar.collapsed .sidebar-brand {
  justify-content: center;
  align-items: center;
  padding: 16px 0;
}

.sidebar.collapsed .toggle-btn-sidebar {
  margin: 0 auto;
}

.sidebar.collapsed .new-chat-btn,
.sidebar.collapsed .history-item-wrapper,
.sidebar.collapsed .history-item,
.sidebar.collapsed .history-link,
.sidebar.collapsed .sidebar-footer,
.sidebar.collapsed .user-profile {
  justify-content: center;
}

.sidebar-brand {
  padding: 20px 16px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-shrink: 0;
}

.brand-logo-container {
  display: flex;
  align-items: center;
  gap: 10px;
}

.brand-text {
  font-weight: 700;
  font-size: 16px;
  color: var(--text-primary);
  letter-spacing: 0;
}

.toggle-btn-sidebar {
  color: var(--text-secondary);
  width: 36px;
  height: 36px;
  border-radius: var(--radius-md);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  font-size: 16px;
  transition:
    background-color var(--duration-micro) var(--ease),
    color var(--duration-micro) var(--ease);
}

.toggle-btn-sidebar:hover {
  background: var(--bg-hover);
  color: var(--text-primary);
}

.new-chat-wrapper {
  padding: 0 12px 16px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.new-chat-btn {
  background: var(--accent);
  color: #fff;
  border-radius: var(--radius-md);
  padding: 0 16px;
  height: 40px;
  font-size: 14px;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 8px;
  width: 100%;
  text-decoration: none;
  transition: background-color var(--duration-micro) var(--ease);
}

.new-chat-btn:hover {
  background: var(--accent-hover);
}

.new-chat-btn.secondary {
  background: transparent;
  border: 1px solid var(--border-medium);
  color: var(--text-secondary);
}

.new-chat-btn.secondary:hover {
  background: var(--bg-hover);
  border-color: var(--border-strong);
  color: var(--text-primary);
}

.history-container {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
  padding: 0 8px;
}

.history-label {
  font-size: 11px;
  font-weight: 600;
  color: var(--text-tertiary);
  text-transform: uppercase;
  letter-spacing: 0.08em;
  padding: 16px 8px 8px;
}

.history-item-wrapper {
  position: relative;
  margin-bottom: 2px;
  display: flex;
  align-items: center;
  border-radius: var(--radius-md);
  transition: background-color var(--duration-micro) var(--ease);
}

.history-item-wrapper:hover {
  background: var(--bg-hover);
}

.history-item-wrapper.active {
  background: var(--accent-subtle);
}

.history-item {
  padding: 10px 14px;
  display: flex;
  align-items: center;
  color: var(--text-secondary);
  text-decoration: none;
  font-size: 14px;
  flex-grow: 1;
  min-width: 0;
  height: 36px;
  transition: color var(--duration-micro) var(--ease);
}

.history-item-wrapper:hover .history-item,
.history-item-wrapper.active .history-item {
  color: var(--text-primary);
}

.history-link {
  display: flex;
  align-items: center;
  width: 100%;
  overflow: hidden;
}

.history-text {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  display: block;
}

.options-btn {
  opacity: 0;
  transition: opacity var(--duration-micro) var(--ease);
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: var(--radius-sm);
  color: var(--text-tertiary);
  flex-shrink: 0;
  margin-right: 8px;
  font-size: 14px;
}

.history-item-wrapper:hover .options-btn {
  opacity: 1;
}

.options-btn:hover {
  background: var(--bg-elevated);
  color: var(--text-primary);
}

/* Options Dropdown */
.options-menu {
  position: absolute;
  right: 8px;
  top: 40px;
  background: var(--bg-overlay);
  border: 1px solid var(--border-medium);
  border-radius: var(--radius-md);
  padding: 4px;
  width: 152px;
  box-shadow: var(--shadow-lg);
  z-index: 100;
  display: none;
}

.options-menu.show {
  display: block;
  animation: menuFadeIn var(--duration-base) var(--ease);
}

@keyframes menuFadeIn {
  from { opacity: 0; transform: translateY(-4px); }
  to   { opacity: 1; transform: translateY(0); }
}

.option-item {
  padding: 8px 12px;
  font-size: 13px;
  color: var(--text-primary);
  display: flex;
  align-items: center;
  gap: 10px;
  border-radius: var(--radius-sm);
  cursor: pointer;
  transition: background-color var(--duration-micro) var(--ease);
  background: transparent;
  border: none;
  width: 100%;
  text-align: left;
  font-family: var(--font-sans);
}

.option-item i { font-size: 14px; color: var(--text-secondary); width: 16px; flex-shrink: 0; }
.option-item:hover { background: var(--bg-hover); }
.option-item.delete { color: var(--danger); }
.option-item.delete i { color: var(--danger); }
.option-item.delete:hover { background: var(--danger-subtle); }

/* Sidebar Footer */
.sidebar-footer {
  padding: 16px;
  border-top: 1px solid var(--border-subtle);
  position: relative;
  flex-shrink: 0;
}

.user-profile {
  display: flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  border-radius: var(--radius-md);
  padding: 8px;
  height: 48px;
  transition: background-color var(--duration-micro) var(--ease);
}

.user-profile:hover {
  background: var(--bg-hover);
}

.user-avatar {
  width: 32px;
  height: 32px;
  border-radius: var(--radius-full);
  background: var(--accent-subtle);
  border: 1px solid var(--accent-border);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 13px;
  color: var(--accent);
  flex-shrink: 0;
  object-fit: cover;
}

.sidebar-footer-details {
  min-width: 0;
  flex: 1;
}

.logout-menu {
  position: absolute;
  bottom: 68px;
  left: 8px;
  right: 8px;
  background: var(--bg-overlay);
  border: 1px solid var(--border-medium);
  border-radius: var(--radius-md);
  padding: 4px;
  display: none;
  box-shadow: var(--shadow-lg);
  z-index: 101;
}

.logout-menu.show {
  display: block;
  animation: menuFadeIn var(--duration-base) var(--ease);
}

/* Sahaja product banner */
.sahaja-banner-container {
  background: var(--bg-elevated);
  border: 1px solid var(--border-subtle);
  border-radius: var(--radius-lg);
  padding: 16px;
  margin: 8px 12px 12px;
  position: relative;
  transition: opacity var(--duration-base) var(--ease);
}

.banner-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 8px;
}

.banner-title {
  font-weight: 600;
  font-size: 13px;
  color: var(--text-primary);
}

.close-btn {
  background: none;
  border: none;
  font-size: 16px;
  line-height: 1;
  cursor: pointer;
  color: var(--text-tertiary);
  transition: color var(--duration-micro) var(--ease);
  padding: 2px;
}

.close-btn:hover { color: var(--danger); }

.banner-subtitle {
  font-size: 12px;
  color: var(--text-secondary);
  margin-bottom: 12px;
  line-height: 1.5;
}

.banner-buttons {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.banner-btn {
  display: flex;
  align-items: center;
  padding: 8px 10px;
  background: var(--bg-hover);
  color: var(--text-secondary);
  text-decoration: none;
  border-radius: var(--radius-md);
  font-size: 12px;
  font-weight: 500;
  transition:
    background-color var(--duration-micro) var(--ease),
    color var(--duration-micro) var(--ease);
  border: 1px solid var(--border-subtle);
}

.banner-btn:hover {
  background: var(--bg-elevated);
  color: var(--text-primary);
  border-color: var(--border-medium);
}

.btn-logo-micro {
  width: 14px;
  height: 14px;
  margin-right: 6px;
  border-radius: 2px;
  vertical-align: middle;
  object-fit: contain;
}

/* ===== CHAT HEADER ===== */
.chat-header {
  height: 56px;
  padding: 0 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 1px solid var(--border-subtle);
  background: var(--bg-base);
  z-index: 10;
  flex-shrink: 0;
}

.mobile-toggle-btn {
  display: none;
  font-size: 18px;
  margin-right: 12px;
  color: var(--text-secondary);
  width: 36px;
  height: 36px;
  border-radius: var(--radius-md);
  align-items: center;
  justify-content: center;
  transition:
    background-color var(--duration-micro) var(--ease),
    color var(--duration-micro) var(--ease);
}

.mobile-toggle-btn:hover {
  background: var(--bg-hover);
  color: var(--text-primary);
}

.chat-title {
  font-size: 15px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 10px;
  color: var(--text-primary);
}

/* Settings container */
.settings-container {
  position: relative;
  z-index: 200;
}

.icon-btn {
  font-size: 18px;
  color: var(--text-secondary);
  width: 36px;
  height: 36px;
  border-radius: var(--radius-md);
  display: flex;
  align-items: center;
  justify-content: center;
  transition:
    background-color var(--duration-micro) var(--ease),
    color var(--duration-micro) var(--ease);
}

.icon-btn:hover {
  background: var(--bg-hover);
  color: var(--text-primary);
}

.icon-btn:focus-visible {
  outline: 2px solid var(--accent);
  outline-offset: 2px;
}

/* Settings dropdown */
.settings-menu-dropdown {
  position: absolute;
  right: 0;
  top: 44px;
  background: var(--bg-overlay);
  border: 1px solid var(--border-medium);
  border-radius: var(--radius-md);
  padding: 4px;
  width: 200px;
  display: none;
  box-shadow: var(--shadow-lg);
  z-index: 1000;
  pointer-events: auto;
}

.settings-menu-dropdown.show {
  display: block !important;
  animation: menuFadeIn var(--duration-base) var(--ease);
}

/* Attach menu dropdown */
.attach-menu {
  position: absolute;
  bottom: 48px;
  left: 0;
  background: var(--bg-overlay);
  border: 1px solid var(--border-medium);
  border-radius: var(--radius-md);
  padding: 4px;
  width: 220px;
  display: none;
  box-shadow: var(--shadow-lg);
  z-index: 101;
}

.attach-menu.show {
  display: block;
  animation: menuFadeIn var(--duration-base) var(--ease);
}

/* Model menu wider */
#modelMenu {
  width: 260px;
}

/* ===== MESSAGES AREA ===== */
.messages-container {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
  padding: 24px 0 120px;
  display: flex;
  flex-direction: column;
  gap: 24px;
  scroll-behavior: smooth;
  max-width: 100vw;
}

/* Centered message column */
.messages-container > .message {
  width: 100%;
  max-width: 720px;
  margin: 0 auto;
  padding: 0 24px;
}

.message {
  display: flex;
  gap: 12px;
  max-width: 100%;
  animation: msgSlideIn var(--duration-base) var(--ease);
}

.message.user {
  flex-direction: row-reverse;
}

@keyframes msgSlideIn {
  from { opacity: 0; transform: translateY(8px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* Message avatar */
.message-avatar {
  width: 32px;
  height: 32px;
  border-radius: var(--radius-md);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  overflow: hidden;
}

.user-avatar-msg {
  background: var(--accent-subtle);
  border: 1px solid var(--accent-border);
  color: var(--accent);
  font-weight: 600;
  font-size: 13px;
}

.ai-avatar-msg {
  background: transparent;
  border: 1px solid var(--border-subtle);
}

/* Message content */
.message-content {
  display: flex;
  flex-direction: column;
  max-width: 100%;
  min-width: 0;
  overflow: visible; /* keep dropdown visible */
}

.message.user .message-content {
  max-width: 70%;
  align-items: flex-end;
}

/* Message bubble */
.message-bubble {
  padding: 12px 16px;
  line-height: 1.6;
  font-size: 15px;
  position: relative;
  word-wrap: break-word;
  overflow-wrap: break-word;
  word-break: break-word;
}

/* User bubble — solid accent */
.user .message-bubble {
  background: var(--accent);
  color: #fff;
  border-radius: var(--radius-lg) var(--radius-lg) var(--radius-sm) var(--radius-lg);
  max-height: 400px;
  overflow-y: auto;
  white-space: pre-wrap;
}

/* AI response — no bubble */
.ai .message-bubble {
  background: transparent;
  border: none;
  padding: 0;
  color: var(--text-primary);
  box-shadow: none;
}

/* ===== MARKDOWN BODY ===== */
.markdown-body {
  width: 100%;
  display: block;
  line-height: 1.7;
  font-size: 15px;
  color: var(--text-primary);
  word-wrap: break-word;
  overflow-wrap: anywhere;
}

.markdown-body > * { margin-bottom: 16px; }
.markdown-body > *:last-child { margin-bottom: 0; }

.markdown-body p,
.markdown-body li {
  white-space: normal !important;
  line-height: 1.7;
  word-wrap: break-word;
  overflow-wrap: anywhere;
  margin-bottom: 8px;
}

.markdown-body h1,
.markdown-body h2,
.markdown-body h3,
.markdown-body h4,
.markdown-body h5,
.markdown-body h6 {
  font-weight: 600;
  margin-top: 24px;
  margin-bottom: 12px;
  color: var(--text-primary);
  line-height: 1.3;
}

.markdown-body h1 { font-size: 24px; }
.markdown-body h2 { font-size: 20px; }
.markdown-body h3 { font-size: 18px; }
.markdown-body h4 { font-size: 16px; }
.markdown-body h5 { font-size: 15px; }
.markdown-body h6 { font-size: 13px; color: var(--text-secondary); }

.markdown-body p strong,
.markdown-body li strong {
  font-weight: 600;
  color: var(--text-primary);
}

/* Inline code */
.markdown-body code {
  font-family: var(--font-mono);
  font-size: 13px;
  background: var(--bg-subtle);
  padding: 2px 6px;
  border-radius: var(--radius-sm);
  color: var(--accent);
  border: 1px solid var(--border-subtle);
}

/* Code block */
.markdown-body pre {
  background: var(--bg-subtle) !important;
  border-radius: 0 0 var(--radius-md) var(--radius-md);
  padding: 16px;
  border: 1px solid var(--border-subtle);
  border-top: none;
  overflow-x: auto;
  margin: 0 0 16px;
  max-width: 100%;
  display: block;
}

.markdown-body pre code {
  font-family: var(--font-mono);
  font-size: 13px;
  line-height: 1.6;
  white-space: pre !important;
  word-break: normal !important;
  overflow-wrap: normal !important;
  display: block;
  max-width: 100%;
  background: transparent;
  padding: 0;
  border: none;
  color: var(--text-primary);
}

/* Code header */
.code-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: var(--bg-elevated);
  padding: 8px 16px;
  border-radius: var(--radius-md) var(--radius-md) 0 0;
  border: 1px solid var(--border-subtle);
  border-bottom: none;
}

.code-lang {
  font-size: 12px;
  color: var(--text-tertiary);
  text-transform: uppercase;
  font-weight: 500;
  letter-spacing: 0.05em;
  font-family: var(--font-mono);
}

.code-copy-btn {
  background: transparent;
  border: 1px solid var(--border-medium);
  border-radius: var(--radius-sm);
  padding: 4px 10px;
  font-size: 12px;
  color: var(--text-secondary);
  display: flex;
  align-items: center;
  gap: 6px;
  font-weight: 500;
  cursor: pointer;
  transition:
    background-color var(--duration-micro) var(--ease),
    color var(--duration-micro) var(--ease),
    border-color var(--duration-micro) var(--ease);
  font-family: var(--font-sans);
}

.code-copy-btn:hover {
  background: var(--bg-hover);
  color: var(--text-primary);
  border-color: var(--border-strong);
}

.code-header .code-copy-btn { /* specificity match for old code */
  position: static;
  transform: none;
  box-shadow: none;
}

/* Table */
.markdown-body table {
  width: 100%;
  border-collapse: collapse;
  margin: 0 0 16px;
  display: block;
  overflow-x: auto;
  border: 1px solid var(--border-subtle);
  border-radius: var(--radius-md);
}

.markdown-body table th,
.markdown-body table td {
  padding: 10px 14px;
  border: 1px solid var(--border-subtle);
  text-align: left;
  font-size: 14px;
}

.markdown-body table th {
  background: var(--bg-subtle);
  font-weight: 600;
  color: var(--text-secondary);
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 0.04em;
}

.markdown-body table tr:hover td {
  background: var(--bg-hover);
}

/* Blockquote */
.markdown-body blockquote {
  border-left: 3px solid var(--accent);
  padding-left: 16px;
  color: var(--text-secondary);
  font-style: italic;
  margin: 0 0 16px;
}

/* Link */
.markdown-body a {
  color: var(--accent) !important;
  text-decoration: underline;
  text-decoration-color: var(--accent-border);
  font-weight: 500;
  transition: color var(--duration-micro) var(--ease);
}

.markdown-body a:hover {
  color: var(--accent-hover) !important;
  text-decoration-color: var(--accent-hover);
}

.markdown-body a::after {
  content: "\f35d";
  font-family: "Font Awesome 6 Free";
  font-weight: 900;
  font-size: 10px;
  margin-left: 3px;
  vertical-align: super;
  opacity: 0.6;
}

/* Image */
.markdown-body img {
  max-width: 100%;
  border-radius: var(--radius-md);
  border: 1px solid var(--border-subtle);
  display: block;
}

/* Lists */
.markdown-body ul {
  list-style-type: disc !important;
  padding-left: 24px !important;
  margin-bottom: 16px;
}

.markdown-body ol {
  list-style-type: decimal !important;
  padding-left: 24px !important;
  margin-bottom: 16px;
}

.markdown-body li {
  margin-bottom: 6px;
  display: list-item !important;
  line-height: 1.7;
}

/* Loose-list fix */
.markdown-body li p {
  margin-bottom: 0 !important;
  display: inline !important;
}

.markdown-body ul ul,
.markdown-body ol ul,
.markdown-body ul ol,
.markdown-body ol ol {
  margin-top: 6px;
  margin-bottom: 0;
}

/* Overflow guard for pre/table/mermaid */
.markdown-body pre,
.markdown-body table,
.mermaid-wrapper {
  max-width: 100% !important;
  width: 100%;
  overflow-x: auto !important;
  display: block;
  word-break: normal !important;
  overflow-wrap: normal !important;
  white-space: normal !important;
}

/* ===== MODE BADGE ===== */
.mode-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 4px 10px;
  border-radius: var(--radius-sm);
  font-size: 11px;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  background: var(--bg-subtle);
  color: var(--text-secondary);
  border: 1px solid var(--border-subtle);
  margin-bottom: 8px;
}
.mode-badge i { font-size: 12px; }
.mode-smart i,
.mode-alpha i { color: var(--accent); }

/* ===== TYPING INDICATOR ===== */
.typing-indicator {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 14px;
  background: var(--bg-elevated);
  border-radius: var(--radius-md);
  border: 1px solid var(--border-subtle);
  width: fit-content;
}

.typing-dot {
  width: 6px;
  height: 6px;
  background: var(--text-tertiary);
  border-radius: 50%;
  animation: typingBounce 1.4s infinite ease-in-out both;
}

.typing-dot:nth-child(1) { animation-delay: -0.32s; }
.typing-dot:nth-child(2) { animation-delay: -0.16s; }

@keyframes typingBounce {
  0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
  40% { transform: scale(1); opacity: 1; }
}

.typing-text {
  font-size: 13px;
  color: var(--text-tertiary);
  margin-left: 4px;
}

/* ===== AI ACTION BUTTONS ===== */
.ai-actions {
  display: flex;
  gap: 4px;
  margin-top: 8px;
  opacity: 0;
  transition: opacity var(--duration-micro) var(--ease);
}

.message.ai:hover .ai-actions {
  opacity: 1;
}

.action-btn {
  padding: 6px 10px;
  border-radius: var(--radius-sm);
  font-size: 13px;
  color: var(--text-secondary);
  background: transparent;
  border: 1px solid transparent;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 6px;
  font-family: var(--font-sans);
  transition:
    background-color var(--duration-micro) var(--ease),
    color var(--duration-micro) var(--ease),
    border-color var(--duration-micro) var(--ease);
}

.action-btn:hover {
  background: var(--bg-hover);
  color: var(--text-primary);
  border-color: var(--border-subtle);
}

.export-dropdown-container {
  position: relative;
}

/* Export menu */
.export-menu {
  display: none;
  position: absolute;
  bottom: 100%;
  left: 0;
  background: var(--bg-overlay);
  border: 1px solid var(--border-medium);
  border-radius: var(--radius-md);
  padding: 4px;
  box-shadow: var(--shadow-lg);
  z-index: 50;
  width: 160px;
  margin-bottom: 6px;
}

/* ===== INPUT AREA ===== */
.input-container {
  padding: 16px 24px 24px;
  background: var(--bg-base);
  border-top: 1px solid var(--border-subtle);
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  position: relative;
  z-index: 20;
  flex-shrink: 0;
}

.input-wrapper {
  width: 100%;
  max-width: 720px;
  position: relative;
  display: flex;
  flex-direction: column;
  background: var(--bg-elevated);
  border: 1px solid var(--border-medium);
  border-radius: var(--radius-xl);
  padding: 12px 16px 8px;
  transition:
    border-color var(--duration-micro) var(--ease),
    background-color var(--duration-micro) var(--ease);
  min-height: 56px;
}

.input-wrapper:focus-within {
  border-color: var(--accent);
  background: var(--bg-elevated);
}

/* File preview */
.file-preview-container {
  display: none;
  align-items: center;
  gap: 8px;
  padding: 6px 12px;
  background: var(--accent-subtle);
  border-radius: var(--radius-md);
  font-size: 13px;
  color: var(--text-primary);
  margin-bottom: 8px;
  border: 1px solid var(--accent-border);
  width: fit-content;
  max-width: 100%;
  overflow: hidden;
}

.file-name-text {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 200px;
  font-weight: 500;
}

/* Multi-file chips */
.multi-file-container {
  display: flex;
  gap: 8px;
  overflow-x: auto;
  padding: 4px 0;
  margin-bottom: 4px;
  scrollbar-width: thin;
  scrollbar-color: var(--border-medium) transparent;
}

.multi-file-container::-webkit-scrollbar { height: 4px; }
.multi-file-container::-webkit-scrollbar-thumb { background: var(--border-medium); border-radius: var(--radius-full); }

.file-chip {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 4px 10px;
  background: var(--bg-subtle);
  border: 1px solid var(--border-subtle);
  border-radius: var(--radius-full);
  font-size: 12px;
  white-space: nowrap;
  flex-shrink: 0;
  color: var(--text-secondary);
}

.file-chip .remove-btn {
  background: transparent;
  color: var(--text-tertiary);
  border: none;
  border-radius: 50%;
  width: 16px;
  height: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 10px;
  transition: color var(--duration-micro) var(--ease);
}
.file-chip .remove-btn:hover { color: var(--danger); }
.file-chip.loading { opacity: 0.6; pointer-events: none; }

/* Textarea */
.chat-input {
  width: 100%;
  background: transparent;
  border: none;
  color: var(--text-primary);
  font-size: 15px;
  padding: 0 0 8px 0;
  resize: none;
  max-height: 200px;
  outline: none;
  line-height: 1.5;
  font-family: var(--font-sans);
}

.chat-input::placeholder { color: var(--text-tertiary); }

/* Action row */
.input-actions-wrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

.action-left {
  display: flex;
  align-items: center;
  gap: 4px;
}

.action-right {
  display: flex;
  align-items: center;
  gap: 8px;
}

/* Input action buttons */
.icon-action-btn {
  width: 32px;
  height: 32px;
  background: transparent;
  border-radius: var(--radius-md);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--text-secondary);
  font-size: 16px;
  transition:
    background-color var(--duration-micro) var(--ease),
    color var(--duration-micro) var(--ease);
}

.icon-action-btn:hover {
  background: var(--bg-hover);
  color: var(--text-primary);
}

.icon-action-btn:focus-visible {
  outline: 2px solid var(--accent);
  outline-offset: 2px;
}

/* Voice button recording state */
.voice-btn.recording {
  background: var(--danger) !important;
  color: #fff !important;
  border-radius: var(--radius-md);
}

/* Send button */
.send-btn {
  width: 32px;
  height: 32px;
  background: var(--accent);
  border-radius: var(--radius-full);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 14px;
  transition: background-color var(--duration-micro) var(--ease);
  flex-shrink: 0;
}

.send-btn:hover {
  background: var(--accent-hover);
}

/* Input footer text */
.input-footer {
  font-size: 11px;
  color: var(--text-tertiary);
  text-align: center;
}

/* Scroll to bottom button */
#scrollToBottomBtn {
  position: absolute;
  top: -52px;
  right: 24px;
  background: var(--bg-elevated);
  color: var(--text-secondary);
  border: 1px solid var(--border-medium);
  border-radius: var(--radius-full);
  width: 36px;
  height: 36px;
  font-size: 14px;
  cursor: pointer;
  box-shadow: var(--shadow-md);
  z-index: 100;
  display: none;
  align-items: center;
  justify-content: center;
  transition:
    background-color var(--duration-micro) var(--ease),
    color var(--duration-micro) var(--ease);
}

#scrollToBottomBtn:hover {
  background: var(--bg-hover);
  color: var(--text-primary);
}

/* ===== WELCOME SCREEN ===== */
.welcome-screen {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 48px 24px 24px;
  overflow-y: auto;
  min-height: 0;
}

.welcome-logo-container {
  position: relative;
  margin-bottom: 24px;
}

.welcome-logo-img {
  width: 64px;
  height: 64px;
  border-radius: var(--radius-lg);
  object-fit: cover;
  position: relative;
  z-index: 1;
}

.welcome-greeting {
  font-size: 32px;
  font-weight: 600;
  color: var(--text-primary);
  margin-bottom: 8px;
  letter-spacing: -0.02em;
  line-height: 1.2;
}

.welcome-subtext {
  font-size: 16px;
  color: var(--text-secondary);
  margin-bottom: 48px;
  font-weight: 400;
}

/* Suggestion cards grid */
.suggested-actions-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
  max-width: 640px;
  width: 100%;
  margin: 0 auto;
}

.action-card {
  background: var(--bg-elevated);
  border: 1px solid var(--border-subtle);
  border-radius: var(--radius-lg);
  padding: 16px;
  cursor: pointer;
  text-align: left;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  min-height: 96px;
  transition:
    border-color var(--duration-micro) var(--ease),
    background-color var(--duration-micro) var(--ease);
}

.action-card:hover {
  border-color: var(--accent-border);
  background: var(--bg-hover);
}

.action-card-icon {
  font-size: 18px;
  margin-bottom: 12px;
  color: var(--text-secondary);
}

.action-card-text {
  font-size: 13px;
  color: var(--text-primary);
  font-weight: 400;
  line-height: 1.5;
}

/* ===== MODAL SYSTEM ===== */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  opacity: 0;
  visibility: hidden;
  transition:
    opacity var(--duration-base) var(--ease),
    visibility var(--duration-base);
}

.modal-overlay.show {
  opacity: 1;
  visibility: visible;
}

.modal-content {
  background: var(--bg-overlay);
  border: 1px solid var(--border-medium);
  border-radius: var(--radius-xl);
  padding: 24px;
  max-width: 500px;
  width: calc(100% - 32px);
  max-height: 85vh;
  overflow-y: auto;
  position: relative;
  box-shadow: var(--shadow-lg);
  color: var(--text-primary);
  transform: translateY(8px);
  transition: transform var(--duration-base) var(--ease);
}

.modal-overlay.show .modal-content {
  transform: translateY(0);
}

.modal-close {
  position: absolute;
  top: 16px;
  right: 16px;
  background: var(--bg-hover);
  border: none;
  border-radius: var(--radius-md);
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--text-secondary);
  font-size: 16px;
  cursor: pointer;
  transition:
    background-color var(--duration-micro) var(--ease),
    color var(--duration-micro) var(--ease);
}

.modal-close:hover {
  background: var(--danger-subtle);
  color: var(--danger);
}

.modal-close-outside {
  position: absolute;
  top: 16px;
  right: 16px;
  background: rgba(0, 0, 0, 0.5);
  border: 1px solid var(--border-medium);
  border-radius: var(--radius-full);
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 16px;
  cursor: pointer;
  z-index: 100001;
  transition:
    background-color var(--duration-micro) var(--ease);
}

.modal-close-outside:hover {
  background: var(--danger);
}

.modal-content h2 {
  margin-bottom: 20px;
  font-size: 18px;
  font-weight: 600;
  color: var(--text-primary);
  border-bottom: 1px solid var(--border-subtle);
  padding-bottom: 16px;
}

.modal-content p {
  margin-bottom: 16px;
  line-height: 1.6;
  font-size: 15px;
  color: var(--text-secondary);
}

/* Settings modal box */
.settings-modal-box {
  background: var(--bg-overlay);
  border: 1px solid var(--border-medium);
  width: 800px;
  max-width: 95%;
  height: 560px;
  max-height: 90vh;
  display: flex;
  border-radius: var(--radius-xl);
  overflow: hidden;
  position: relative;
  box-shadow: var(--shadow-lg);
}

.settings-sidebar {
  width: 220px;
  background: var(--bg-subtle);
  padding: 16px 8px;
  border-right: 1px solid var(--border-subtle);
  display: flex;
  flex-direction: column;
  gap: 2px;
  flex-shrink: 0;
}

.settings-sidebar h3 {
  font-size: 12px;
  font-weight: 600;
  color: var(--text-tertiary);
  text-transform: uppercase;
  letter-spacing: 0.08em;
  padding: 8px;
  margin-bottom: 4px;
}

.nav-btn {
  padding: 10px 12px;
  text-align: left;
  background: none;
  border-radius: var(--radius-md);
  color: var(--text-secondary);
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition:
    background-color var(--duration-micro) var(--ease),
    color var(--duration-micro) var(--ease);
  display: flex;
  align-items: center;
  gap: 10px;
  border: none;
  font-family: var(--font-sans);
  width: 100%;
}

.nav-btn i { font-size: 16px; color: var(--text-tertiary); }
.nav-btn:hover { background: var(--bg-hover); color: var(--text-primary); }
.nav-btn.active { background: var(--accent-subtle); color: var(--accent); }
.nav-btn.active i { color: var(--accent); }

.settings-content { padding: 24px; flex: 1; overflow-y: auto; }
.tab-pane { display: none; }
.tab-pane.active { display: block; animation: menuFadeIn var(--duration-base) var(--ease); }

.theme-btn {
  padding: 12px 16px;
  border: 1px solid var(--border-medium);
  border-radius: var(--radius-md);
  flex: 1;
  background: transparent;
  color: var(--text-primary);
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition:
    background-color var(--duration-micro) var(--ease),
    border-color var(--duration-micro) var(--ease),
    color var(--duration-micro) var(--ease);
  font-family: var(--font-sans);
}

.theme-btn:hover { background: var(--bg-hover); }
.theme-btn.active { border-color: var(--accent); background: var(--accent-subtle); color: var(--accent); }

/* Toggle switch */
.toggle-switch {
  position: relative;
  display: inline-block;
  width: 44px;
  height: 24px;
  flex-shrink: 0;
}
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider {
  position: absolute;
  cursor: pointer;
  inset: 0;
  background: var(--border-medium);
  transition: background-color var(--duration-base) var(--ease);
  border-radius: var(--radius-full);
}
.toggle-slider::before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background: var(--text-secondary);
  transition: transform var(--duration-base) var(--ease);
  border-radius: 50%;
}
.toggle-switch input:checked + .toggle-slider { background: var(--accent); }
.toggle-switch input:checked + .toggle-slider::before {
  transform: translateX(20px);
  background: #fff;
}

/* GitHub Modal */
.github-input-group {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-top: 16px;
}

.github-input {
  width: 100%;
  padding: 10px 14px;
  border-radius: var(--radius-md);
  border: 1px solid var(--border-medium);
  background: var(--bg-subtle);
  color: var(--text-primary);
  font-size: 14px;
  outline: none;
  font-family: var(--font-sans);
  transition:
    border-color var(--duration-micro) var(--ease),
    background-color var(--duration-micro) var(--ease);
}

.github-input:focus {
  border-color: var(--accent);
  background: var(--bg-base);
}

.github-submit-btn {
  background: var(--accent);
  color: #fff;
  border: none;
  padding: 10px 16px;
  border-radius: var(--radius-md);
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: background-color var(--duration-micro) var(--ease);
  font-family: var(--font-sans);
}

.github-submit-btn:hover {
  background: var(--accent-hover);
}

/* ===== THINKING BLOCK ===== */
.thinking-container {
  margin-bottom: 16px;
  border: 1px solid var(--border-subtle);
  border-radius: var(--radius-md);
  overflow: hidden;
  background: var(--bg-subtle);
}

.thinking-header {
  padding: 10px 16px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  color: var(--text-secondary);
  background: transparent;
  user-select: none;
  transition: background-color var(--duration-micro) var(--ease);
}

.thinking-header:hover {
  background: var(--bg-hover);
  color: var(--text-primary);
}

.thinking-content {
  display: none;
  padding: 16px;
  font-size: 13px;
  color: var(--text-secondary);
  border-top: 1px solid var(--border-subtle);
  white-space: pre-wrap;
  line-height: 1.6;
}

.thinking-content.show {
  display: block;
  animation: menuFadeIn var(--duration-base) var(--ease);
}

/* ===== MERMAID DIAGRAM ===== */
.mermaid-wrapper {
  border: 1px solid var(--border-subtle);
  border-radius: var(--radius-md);
  margin: 16px 0;
  overflow: hidden;
}

.mermaid-header {
  display: flex;
  justify-content: space-between;
  background: var(--bg-elevated);
  padding: 8px 16px;
  align-items: center;
  border-bottom: 1px solid var(--border-subtle);
}

.mermaid-tabs { display: flex; gap: 4px; }

.mermaid-tab {
  background: transparent;
  color: var(--text-secondary);
  border: none;
  cursor: pointer;
  padding: 4px 10px;
  font-size: 13px;
  border-radius: var(--radius-sm);
  transition:
    background-color var(--duration-micro) var(--ease),
    color var(--duration-micro) var(--ease);
  font-family: var(--font-sans);
}

.mermaid-tab:hover { background: var(--bg-hover); color: var(--text-primary); }
.mermaid-tab.active { background: var(--accent); color: #fff; }

.mermaid-download {
  background: transparent;
  color: var(--text-secondary);
  border: 1px solid var(--border-medium);
  padding: 4px 10px;
  border-radius: var(--radius-sm);
  cursor: pointer;
  font-size: 13px;
  transition:
    background-color var(--duration-micro) var(--ease),
    color var(--duration-micro) var(--ease);
  font-family: var(--font-sans);
}

.mermaid-download:hover { background: var(--bg-hover); color: var(--text-primary); }

.mermaid-content {
  background: var(--bg-subtle);
  padding: 16px;
  overflow-x: auto;
  text-align: center;
}

.mermaid-code {
  display: none;
  background: var(--bg-subtle);
  padding: 16px;
  text-align: left;
  overflow-x: auto;
  font-family: var(--font-mono);
  font-size: 13px;
}

/* ===== DEEP RESEARCH PANEL ===== */
.research-panel {
  width: 0;
  background: var(--bg-subtle);
  border-left: 1px solid var(--border-subtle);
  display: flex;
  flex-direction: column;
  transition: width var(--duration-macro) var(--ease);
  overflow: hidden;
  z-index: 40;
  white-space: nowrap;
}

.research-panel.active { width: 360px; }

.research-header {
  padding: 16px 20px;
  border-bottom: 1px solid var(--border-subtle);
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-weight: 600;
  color: var(--text-primary);
  font-size: 14px;
}

.research-logs {
  flex: 1;
  padding: 16px;
  overflow-y: auto;
  overflow-x: hidden;
  display: flex;
  flex-direction: column;
  gap: 8px;
  font-family: var(--font-mono);
  font-size: 13px;
}

.log-item {
  background: var(--bg-elevated);
  padding: 10px 12px;
  border-radius: var(--radius-sm);
  border-left: 2px solid var(--border-medium);
  color: var(--text-secondary);
  animation: msgSlideIn var(--duration-base) var(--ease);
  white-space: normal;
  line-height: 1.5;
}

.log-item.processing { border-color: var(--warning); color: var(--warning); }
.log-item.success { border-color: var(--success); color: var(--success); }

#floatingResearchBtn {
  position: fixed;
  top: 72px;
  right: 20px;
  z-index: 10001;
  background: var(--accent);
  color: #fff;
  padding: 10px 16px;
  border-radius: var(--radius-full);
  box-shadow: var(--shadow-md);
  font-size: 13px;
  border: none;
  font-weight: 500;
  cursor: pointer;
  transition: background-color var(--duration-micro) var(--ease);
  font-family: var(--font-sans);
}

#floatingResearchBtn:hover { background: var(--accent-hover); }

/* ===== ONBOARDING DOTS ===== */
.onboard-dots {
  display: flex;
  gap: 6px;
  align-items: center;
}

.dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: var(--border-medium);
  transition:
    width var(--duration-base) var(--ease),
    background-color var(--duration-base) var(--ease),
    border-radius var(--duration-base) var(--ease);
}

.dot.active {
  width: 20px;
  border-radius: var(--radius-full);
  background: var(--accent);
}

/* Onboarding items */
.feature-item {
  display: flex;
  align-items: flex-start;
  gap: 16px;
  padding: 16px;
  border-radius: var(--radius-lg);
  background: var(--bg-subtle);
  border: 1px solid var(--border-subtle);
  margin-bottom: 12px;
  transition:
    border-color var(--duration-micro) var(--ease),
    background-color var(--duration-micro) var(--ease);
}

.feature-item:hover {
  border-color: var(--accent-border);
  background: var(--bg-elevated);
}

.feature-icon-wrapper {
  width: 40px;
  height: 40px;
  border-radius: var(--radius-md);
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--bg-elevated);
  border: 1px solid var(--border-subtle);
  color: var(--text-secondary);
  font-size: 18px;
  flex-shrink: 0;
}

/* ===== MODEL OPTION IN DROPDOWN ===== */
.model-option {
  align-items: flex-start !important;
  gap: 12px !important;
  padding: 10px 12px !important;
}

.model-option i { margin-top: 2px !important; }

/* Gemini-block stream animation */
.gemini-block {
  opacity: 0;
  transform: translateY(8px);
  transition:
    opacity var(--duration-base) var(--ease),
    transform var(--duration-base) var(--ease);
  margin-bottom: 12px;
  display: block;
}

.gemini-block.show {
  opacity: 1;
  transform: translateY(0);
}

/* Sidebar logo spin (used during AI thinking) */
.sidebar-logo-spin {
  width: 20px !important;
  height: 20px !important;
  border-radius: 50%;
  object-fit: cover;
  animation: logoSpin 1s linear infinite;
  margin-right: 12px;
}

@keyframes logoSpin {
  from { transform: rotate(0deg); }
  to   { transform: rotate(360deg); }
}

/* Slide in right (for research panel items) */
.slide-in-right {
  animation: slideInRight var(--duration-macro) var(--ease) forwards;
}

@keyframes slideInRight {
  from { opacity: 0; transform: translateX(16px); }
  to   { opacity: 1; transform: translateX(0); }
}

/* ===== RESPONSIVE — MOBILE ===== */
@media (max-width: 768px) {
  .sidebar {
    position: fixed;
    left: 0;
    top: 0;
    height: 100%;
    transform: translateX(-100%);
    z-index: 10000 !important;
    width: 260px !important;
    transition: transform var(--duration-macro) var(--ease);
  }

  .sidebar.mobile-open {
    transform: translateX(0);
    box-shadow: var(--shadow-lg);
  }

  .toggle-btn-sidebar { display: none; }

  .mobile-toggle-btn {
    display: flex;
  }

  .messages-container {
    padding: 16px 0 calc(160px + env(safe-area-inset-bottom)) !important;
  }

  .messages-container > .message {
    padding: 0 16px;
  }

  .message-content {
    max-width: calc(100% - 44px) !important;
    min-width: 0 !important;
    overflow: hidden !important;
  }

  .message.user .message-content {
    max-width: 80% !important;
  }

  .message-bubble {
    width: 100% !important;
    max-width: 100% !important;
  }

  .markdown-body pre,
  .markdown-body table,
  .mermaid-wrapper {
    width: 100% !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
    overflow-x: auto !important;
  }

  .input-container {
    padding: 8px 12px 12px !important;
    padding-bottom: calc(12px + env(safe-area-inset-bottom)) !important;
  }

  .settings-modal-box {
    flex-direction: column;
    height: 85vh;
    width: 95%;
  }

  .settings-sidebar {
    width: 100%;
    border-right: none;
    border-bottom: 1px solid var(--border-subtle);
    flex-direction: row;
    padding: 8px;
    overflow-x: auto;
    white-space: nowrap;
    flex-shrink: 0;
    gap: 4px;
  }

  .settings-sidebar h3 { display: none; }
  .nav-btn { padding: 8px 10px; font-size: 13px; }
  .settings-content { padding: 16px; }

  .suggested-actions-grid {
    display: flex;
    flex-wrap: nowrap;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    padding-bottom: 12px;
    gap: 8px;
    -webkit-overflow-scrolling: touch;
  }

  .suggested-actions-grid::-webkit-scrollbar { display: none; }

  .action-card {
    flex: 0 0 220px;
    scroll-snap-align: start;
    min-height: 96px;
  }

  .research-panel.active {
    position: absolute;
    top: 0;
    right: 0;
    width: 100% !important;
    height: 100%;
    z-index: 150;
    border-left: none;
  }

  .modal-close-outside {
    top: 12px;
    right: 12px;
    width: 36px;
    height: 36px;
    font-size: 14px;
  }

  .settings-modal-box {
    margin-top: 40px;
  }
}

@media (max-width: 375px) {
  .messages-container {
    padding: 12px 0 calc(140px + env(safe-area-inset-bottom)) !important;
  }
}

/* Safe area support */
@supports (padding-bottom: env(safe-area-inset-bottom)) {
  .input-container {
    padding-bottom: max(12px, env(safe-area-inset-bottom)) !important;
  }
}

/* Modal & settings box opacity enforcement */
.modal-content,
.settings-modal-box {
  background: var(--bg-overlay) !important;
}

/* ===== LIGHT MODE OVERRIDES ===== */
html.light-mode .chat-header,
body.light-mode .chat-header {
  background: var(--bg-base);
}

html.light-mode .messages-container > .message,
body.light-mode .messages-container > .message {
  /* inherits bg-base from body */
}

html.light-mode .code-header,
body.light-mode .code-header {
  background: var(--bg-subtle);
  border-color: var(--border-subtle);
}

html.light-mode .code-lang,
body.light-mode .code-lang { color: var(--text-tertiary); }

html.light-mode .code-copy-btn,
body.light-mode .code-copy-btn {
  color: var(--text-secondary);
  border-color: var(--border-medium);
}

html.light-mode .options-menu,
html.light-mode .attach-menu,
html.light-mode .logout-menu,
html.light-mode .settings-menu-dropdown,
body.light-mode .options-menu,
body.light-mode .attach-menu,
body.light-mode .logout-menu,
body.light-mode .settings-menu-dropdown {
  background: var(--bg-overlay);
  border-color: var(--border-medium);
}

html.light-mode .user-avatar,
body.light-mode .user-avatar {
  background: var(--accent-subtle);
  border-color: var(--accent-border);
  color: var(--accent);
}

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
.comments-list::-webkit-scrollbar {
  height: 4px;
}

.multi-file-container::-webkit-scrollbar-thumb,
.suggested-actions-grid::-webkit-scrollbar-thumb,
.comments-list::-webkit-scrollbar-thumb {
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

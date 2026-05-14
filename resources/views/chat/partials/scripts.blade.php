const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
let currentSessionId = "{{ $currentSession ? $currentSession->id : '' }}";
let currentController = null;
let lastUserMessage = "";
window.activeForceMode = null;

let attachedFiles = []; let fileIdCounter = 0; let currentGithubRepo = "";
let pendingAvatarBase64 = null; let targetActionId = null; let targetActionType = '';

const chatInput = document.getElementById('chatInput'); const voiceBtn = document.getElementById('voiceButton');
const attachBtn = document.getElementById('attachButton'); const attachMenu = document.getElementById('attachMenu');
const docInput = document.getElementById('docInput'); const imageInput = document.getElementById('imageInput');
const filePreviewContainer = document.getElementById('filePreviewContainer'); const fileNameDisplay = document.getElementById('fileNameDisplay');

function showToast(message, type = 'info') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div'); container.id = 'toast-container';
        container.style.cssText = 'position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 100000; display: flex; flex-direction: column; gap: 10px; pointer-events: none;';
        document.body.appendChild(container);
    }
    const toast = document.createElement('div');
    const icon = type === 'success' ? 'check-circle' : (type === 'error' ? 'exclamation-circle' : 'info-circle');
    const color = type === 'success' ? '#10b981' : (type === 'error' ? '#ef4444' : '#3b82f6');
    toast.style.cssText = `background: rgba(30, 41, 59, 0.95); color: white; padding: 12px 24px; border-radius: 12px; font-size: 0.9rem; display: flex; align-items: center; gap: 10px; animation: slideDown 0.3s ease forwards; backdrop-filter: blur(8px); border-left: 4px solid ${color};`;
    toast.innerHTML = `<i class="fas fa-${icon}"></i> <span>${message}</span>`;
    container.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
}

function closeCustomModal(modalId) { const m = document.getElementById(modalId); if (m) m.classList.remove('show'); }
function openConfirmModal(title, text, type, id = null) {
    targetActionType = type; targetActionId = id;
    document.getElementById('dangerModalTitle').innerText = title; document.getElementById('dangerModalText').innerText = text;
    document.getElementById('confirmDangerModal').classList.add('show');
    document.querySelectorAll('.options-menu, .logout-menu').forEach(el => el.classList.remove('show'));
}
function toggleMenu(e, id) {
    if (e) { e.preventDefault(); e.stopPropagation(); }
    const targetMenu = document.getElementById(id); if (!targetMenu) return;
    const isShown = targetMenu.classList.contains('show');
    document.querySelectorAll('.options-menu, .logout-menu, .attach-menu').forEach(el => el.classList.remove('show'));
    if (!isShown) targetMenu.classList.add('show');
}

function renameSession(id) {
    targetActionId = id; document.getElementById('renameInput').value = document.getElementById(`title-${id}`).innerText;
    document.getElementById('renameRoomModal').classList.add('show'); document.getElementById(`menu-${id}`)?.classList.remove('show');
}
function deleteSession(id) { openConfirmModal("Hapus Percakapan?", "Percakapan ini akan dihapus secara permanen.", "deleteRoom", id); }
function clearAllChats() { openConfirmModal("Hapus Semua Obrolan?", "Seluruh riwayat chat Anda akan musnah.", "clearAllChats"); }

async function shareSession(id) {
    try {
        const response = await fetch(`/session/${id}/share`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken } });
        const data = await response.json();
        if (data.success) { document.getElementById('shareLinkInput').value = data.url; document.getElementById('shareModal').classList.add('show'); }
    } catch(e) { showToast("Gagal membuat link", "error"); }
    document.getElementById(`menu-${id}`)?.classList.remove('show');
}
function copyShareLink() { document.getElementById('shareLinkInput').select(); document.execCommand("copy"); showToast("Tautan berhasil disalin!", "success"); closeCustomModal('shareModal'); }

async function executeRename() {
    const newName = document.getElementById('renameInput').value.trim();
    if(!newName) return showToast("Nama tidak boleh kosong", "error");
    try {
        await fetch(`/session/${targetActionId}/rename`, { method: 'PUT', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ title: newName }) });
        document.getElementById(`title-${targetActionId}`).innerText = newName;
        closeCustomModal('renameRoomModal'); showToast("Nama berhasil diubah", "success");
    } catch(e) { showToast("Gagal mengganti nama", "error"); }
}

async function executeDangerAction() {
    closeCustomModal('confirmDangerModal');
    try {
        if (targetActionType === 'deleteRoom') {
            await fetch(`/session/${targetActionId}/delete`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken } });
            document.getElementById(`session-${targetActionId}`)?.remove();
            if (window.location.pathname.includes('/chat/')) { const parts = window.location.pathname.split('/'); if(parts[parts.length-1] == targetActionId) window.location.href = "/chat"; }
            showToast("Percakapan dihapus", "success");
        } else if (targetActionType === 'clearAllChats') {
            await fetch('/profile/chat/clear', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken } });
            showToast("Seluruh riwayat dihapus", "success"); setTimeout(() => window.location.href = '/chat', 1000);
        } else if (targetActionType === 'deleteAccount') {
            await fetch('/profile/account/delete', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken } });
            window.location.href = '/';
        } else if (targetActionType === 'deleteAvatar') {
            await fetch('/profile/update', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ avatar: null }) });
            showToast("Foto profil dihapus", "success"); setTimeout(() => window.location.reload(), 1000);
        }
    } catch(e) { showToast("Kesalahan server", "error"); }
}

function openSettingsModal() { document.getElementById('settingsModal').classList.add('show'); document.getElementById('logout-menu')?.classList.remove('show'); }
function closeSettingsModal() { document.getElementById('settingsModal').classList.remove('show'); }
function switchTab(tabId) {
    document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.nav-btn').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-' + tabId).classList.add('active'); event.currentTarget.classList.add('active');
}
function setTheme(mode) {
    const isLight = mode === 'light'; document.body.classList.toggle('light-mode', isLight); localStorage.setItem('theme', isLight ? 'light' : 'dark');
    document.getElementById('btnThemeLight').classList.toggle('active', isLight); document.getElementById('btnThemeDark').classList.toggle('active', !isLight);
}

document.getElementById('avatarInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0]; if (!file) return;
    const reader = new FileReader();
    reader.onload = function(event) {
        const img = new Image(); img.src = event.target.result;
        img.onload = () => {
            const canvas = document.createElement('canvas'); const MAX = 200; let w = img.width; let h = img.height;
            if (w > h) { if (w > MAX) { h *= MAX / w; w = MAX; } } else { if (h > MAX) { w *= MAX / h; h = MAX; } }
            canvas.width = w; canvas.height = h; canvas.getContext('2d').drawImage(img, 0, 0, w, h);
            pendingAvatarBase64 = canvas.toDataURL('image/jpeg', 0.8);
            document.getElementById('previewAvatar').src = pendingAvatarBase64;
            showToast("Foto siap. Klik 'Simpan' untuk menerapkan.", "info");
        }
    }
    reader.readAsDataURL(file);
});

async function simpanProfil() {
    const newName = document.getElementById('inputNamaProfil').value.trim();
    if(!newName) return showToast("Nama tidak boleh kosong!", "error");
    const payload = { name: newName }; if (pendingAvatarBase64 !== null) payload.avatar = pendingAvatarBase64;
    try {
        const res = await fetch('/profile/update', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify(payload) });
        const data = await res.json();
        if(data.success) { showToast("Profil diperbarui!", "success"); setTimeout(() => window.location.reload(), 1000); }
    } catch(e) { showToast("Gagal menyimpan profil", "error"); }
}

// Mengarahkan kedua tombol menu ke satu Pintu Input Sakti dengan Mode Bunglon
        document.getElementById('btnUploadDoc')?.addEventListener('click', () => {
            const fileInput = document.getElementById('fileInput');
            fileInput.setAttribute('accept', '.pdf,.doc,.docx'); // Ubah mode jadi khusus Dokumen
            fileInput.click();
            document.getElementById('attachMenu').classList.remove('show');
        });

        document.getElementById('btnUploadImage')?.addEventListener('click', () => {
            const fileInput = document.getElementById('fileInput');
            fileInput.setAttribute('accept', 'image/*'); // Ubah mode jadi khusus Gambar/Galeri
            fileInput.click();
            document.getElementById('attachMenu').classList.remove('show');
        });

const githubModal = document.getElementById('githubModal');
document.getElementById('btnUploadGithub')?.addEventListener('click', () => { attachMenu.classList.remove('show'); githubModal.classList.add('show'); document.getElementById('githubLinkInput').focus(); });
document.getElementById('closeGithubModalBtn')?.addEventListener('click', () => { githubModal.classList.remove('show'); });
document.getElementById('submitGithubBtn')?.addEventListener('click', () => {
    const link = document.getElementById('githubLinkInput').value.trim();
    if (link.includes('github.com')) {
        removeFile(); // Bersihkan memori file lain agar fokus ke GitHub
        const urlParts = link.split('github.com/');
        if (urlParts.length > 1) {
            let repoName = urlParts[1].replace('.git', '').split('/').slice(0, 2).join('/');
            currentGithubRepo = link;
            currentFileName = repoName;

            // JURUS BARU: Gunakan Container Multi-File yang baru!
            const container = document.getElementById('multiFileContainer');
            container.style.display = 'flex';
            container.innerHTML = `
                <div class="file-chip" id="github-chip">
                    <i class="fab fa-github" style="color: #a855f7;"></i>
                    <span style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${repoName}">Repo: ${repoName}</span>
                    <button class="remove-btn" onclick="removeGithubRepo()"><i class="fas fa-times"></i></button>
                </div>
            `;

            githubModal.classList.remove('show');
            document.getElementById('githubLinkInput').value = ''; // Kosongkan input
            showToast("Repository berhasil dimuat!", "success");
        }
    } else {
        showToast('Link GitHub tidak valid!', 'error');
    }
});

// FUNGSI BARU: Untuk menghapus tombol silang di chip GitHub
window.removeGithubRepo = function() {
    currentGithubRepo = "";
    currentFileName = "";
    const chip = document.getElementById('github-chip');
    if (chip) chip.remove();
    if (attachedFiles.length === 0) {
        document.getElementById('multiFileContainer').style.display = 'none';
    }
};

// ========================================================
// ENGINE MULTI-UPLOAD BARU (MAKS 5 FILE)
// ========================================================

// Fungsi helper kompresi gambar dari kodingan lamamu
function convertToBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = event => {
            const img = new Image(); img.src = event.target.result;
            img.onload = () => {
                const canvas = document.createElement('canvas'); const MAX = 1600; let w = img.width; let h = img.height;
                if (w > h && w > MAX) { h *= MAX / w; w = MAX; } else if (h > MAX) { w *= MAX / h; h = MAX; }
                canvas.width = w; canvas.height = h; canvas.getContext('2d').drawImage(img, 0, 0, w, h);
                resolve(canvas.toDataURL('image/jpeg', 0.9));
            }
            img.onerror = error => reject(error);
        };
        reader.onerror = error => reject(error);
    });
}

document.getElementById('fileInput').addEventListener('change', async function(e) {
    const files = Array.from(e.target.files);
    e.target.value = ''; // Reset input agar bisa pilih file yang sama lagi

    if (attachedFiles.length + files.length > 5) {
        showToast("Batas wajar: Maksimal 5 file dapat diunggah sekaligus!", "error");
        return;
    }

    const container = document.getElementById('multiFileContainer');
    container.style.display = 'flex';

    for (const file of files) {
        fileIdCounter++;
        const currentId = fileIdCounter;

        // 1. Munculkan UI Loading Chip
        const chipHtml = `
            <div class="file-chip loading" id="file-chip-${currentId}">
                <i class="fas fa-circle-notch fa-spin text-accent" style="color: #3b82f6;"></i>
                <span style="max-width: 100px; overflow: hidden; text-overflow: ellipsis;">Memproses...</span>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', chipHtml);

        // 2. Ekstrak Data File secara Pararel
        try {
            let extractedText = "";
            if (file.type === "application/pdf" || file.name.toLowerCase().endsWith('.pdf')) {
                extractedText = await extractPdfText(file);
                if (extractedText.length > 25000) extractedText = extractedText.substring(0, 25000) + "\n\n[INFO: TEKS DIPOTONG]";
                attachedFiles.push({ id: currentId, type: 'pdf', name: file.name, data: extractedText });
                updateFileChipUI(currentId, file.name, 'fas fa-file-pdf', '#ef4444');
            } else if (file.name.toLowerCase().endsWith('.docx') || file.type.includes('wordprocessingml')) {
                extractedText = await extractDocxText(file);
                if (extractedText.length > 25000) extractedText = extractedText.substring(0, 25000) + "\n\n[INFO: TEKS DIPOTONG]";
                attachedFiles.push({ id: currentId, type: 'pdf', name: file.name, data: extractedText }); // DOCX disamakan tipe pdf (teks dokumen)
                updateFileChipUI(currentId, file.name, 'fas fa-file-word', '#3b82f6');
            } else if (file.type.startsWith("image/")) {
                const base64 = await convertToBase64(file);
                attachedFiles.push({ id: currentId, type: 'image', name: file.name, data: base64 });
                updateFileChipUI(currentId, file.name, 'fas fa-image', '#10b981');
            } else {
                throw new Error("Format tidak didukung");
            }
        } catch (err) {
            document.getElementById(`file-chip-${currentId}`).remove();
            showToast("Gagal memproses " + file.name, "error");
        }
    }

    if(attachedFiles.length === 0) container.style.display = 'none';
});

function updateFileChipUI(id, name, iconClass, color) {
    const chip = document.getElementById(`file-chip-${id}`);
    if(chip) {
        chip.className = 'file-chip';
        chip.innerHTML = `
            <i class="${iconClass}" style="color: ${color};"></i>
            <span style="max-width: 100px; overflow: hidden; text-overflow: ellipsis;" title="${name}">${name}</span>
            <button class="remove-btn" onclick="removeSpecificFile(${id})"><i class="fas fa-times"></i></button>
        `;
    }
}

function removeSpecificFile(id) {
    attachedFiles = attachedFiles.filter(f => f.id !== id);
    const chip = document.getElementById(`file-chip-${id}`);
    if (chip) chip.remove();
    if (attachedFiles.length === 0) document.getElementById('multiFileContainer').style.display = 'none';
}

function removeFile() { // Timpa fungsi removeFile bawaan lama
    attachedFiles = [];
    currentGithubRepo = "";
    const container = document.getElementById('multiFileContainer');
    if(container) {
        container.innerHTML = '';
        container.style.display = 'none';
    }
}

async function extractPdfText(file) {
    const arrayBuffer = await file.arrayBuffer(); const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
    let text = ""; const maxPages = Math.min(pdf.numPages, 25);
    for (let i = 1; i <= maxPages; i++) { const page = await pdf.getPage(i); const content = await page.getTextContent(); text += content.items.map(item => item.str).join(" ") + "\n"; }
    return text;
}

async function extractDocxText(file) {
    const arrayBuffer = await file.arrayBuffer(); const result = await mammoth.extractRawText({ arrayBuffer: arrayBuffer });
    return result.value;
}

let userSelectedMode = 'auto';
document.getElementById('modelSelectButton')?.addEventListener('click', (e) => { e.stopPropagation(); attachMenu.classList.remove('show'); document.getElementById('modelMenu').classList.toggle('show'); });
attachBtn?.addEventListener('click', (e) => { e.stopPropagation(); document.getElementById('modelMenu').classList.remove('show'); attachMenu.classList.toggle('show'); });
function selectModelMode(mode, iconClass) { userSelectedMode = mode; document.getElementById('currentModelIcon').className = `fas ${iconClass}`; document.querySelectorAll('.model-option').forEach(el => el.style.background = 'transparent'); event.currentTarget.style.background = 'var(--glass-highlight)'; document.getElementById('modelMenu').classList.remove('show'); }
let isSwitchingMode = false; // VARIABEL PENANDA BARU

function switchToMode(targetMode) {
    window.activeForceMode = targetMode;
    if (currentController) currentController.abort();
    const oldLoading = document.querySelector('.message.ai:last-child');
    if (oldLoading && oldLoading.querySelector('.typing-indicator')) oldLoading.remove();
    sendMessage();
}

function switchToFastMode() { switchToMode('fast'); }

function detectComplexity(text) {
    const t = text.toLowerCase();
    const complex = ['coding', 'buatkan', 'analisis', 'html', 'laravel', 'script', 'error', 'database'];
    const simple = ['halo', 'hai', 'tes', 'ngoding', 'cerita'];
    if (complex.some(k => t.includes(k))) return true;
    if (t.split(' ').length < 10 && simple.some(k => t.includes(k))) return false;
    return t.split(' ').length > 15;
}

async function sendMessage() {
    if (typeof isRecording !== 'undefined' && isRecording && recognition) { recognition.stop(); forceStopRecordingUI(); }
    const messageInput = chatInput.value.trim();

    // 1. KUMPULKAN DATA MULTI-FILE DARI ARRAY
    let combinedPdfText = "";
    let base64ImagesArray = [];
    let pdfCount = 0; let imgCount = 0;

    attachedFiles.forEach(file => {
        if (file.type === 'pdf') {
            combinedPdfText += `[Dokumen ${pdfCount + 1}: ${file.name}]\n"""\n${file.data}\n"""\n\n`;
            pdfCount++;
        } else if (file.type === 'image') {
            base64ImagesArray.push(file.data);
            imgCount++;
        }
    });

    // 2. CEGAH PENGIRIMAN JIKA KOSONG
    if (!messageInput && attachedFiles.length === 0 && !currentGithubRepo) return;

    // 3. JURUS BYPASS MODE ALPHA (DEEP RESEARCH)
    if (userSelectedMode === 'alpha' && window.activeForceMode === null) {
        startDeepResearch(messageInput);
        chatInput.disabled = false;
        chatInput.style.height = 'auto';
        chatInput.value = '';
        chatInput.focus();
        return;
    }

    // 4. SUSUN PESAN USER BERSERTA LAMPIRANNYA
    let finalMessageToSend = messageInput;
    let displayMessage = messageInput;

    if (window.activeForceMode !== null) {
        if (!lastUserMessage) return; finalMessageToSend = lastUserMessage;
    } else {
        if (combinedPdfText !== "") {
            finalMessageToSend = combinedPdfText + `Instruksi User: ${messageInput || "Tolong analisis dokumen di atas."}`;
            displayMessage = `📎 [${pdfCount} Dokumen Terlampir]\n${messageInput}`;
        }
        if (base64ImagesArray.length > 0) {
            if (finalMessageToSend === messageInput) finalMessageToSend = messageInput || "Jelaskan gambar-gambar ini.";
            displayMessage = `🖼️ [${imgCount} Gambar Terlampir]\n` + displayMessage;
        }
        if (currentGithubRepo) {
            finalMessageToSend = messageInput || "Analisis kode ini.";
            displayMessage = `📦 [GitHub: ${currentFileName || 'Repo'}]\n${messageInput}`;
        }
        lastUserMessage = finalMessageToSend;

        if (window.activeForceMode === null) {
            const welcome = document.getElementById('welcomeScreen'); if (welcome) welcome.style.display = 'none';
            const msgContainer = document.getElementById('messagesContainer'); if (msgContainer) msgContainer.style.display = 'flex';
            chatInput.value = ''; chatInput.style.height = 'auto';
            appendMessage('user', displayMessage); formatAttachmentIcons();
        }
    }

    // 5. SIAPKAN PAYLOAD UNTUK LARAVEL
    const payload = {
        message: finalMessageToSend,
        session_id: currentSessionId,
        manual_mode: userSelectedMode,
        max_tokens: document.getElementById('maxTokensInput').value,
        enable_thinking: document.getElementById('enableThinkingInput').checked,
        web_search: document.getElementById('enableWebSearchInput') ? document.getElementById('enableWebSearchInput').checked : false
    };

    if (base64ImagesArray.length > 0) payload.image_data_array = base64ImagesArray;
    if (currentGithubRepo) payload.github_repo = currentGithubRepo;
    if (window.activeForceMode !== null) payload.force_mode = window.activeForceMode;

    // 6. DETEKSI MODE AI OTOMATIS (Cerdas / Cepat / Vision)
    let mode = 'fast';
    if (window.activeForceMode !== null) mode = window.activeForceMode;
    else if (userSelectedMode !== 'auto') mode = userSelectedMode;
    else {
        let isComplex = detectComplexity(finalMessageToSend);
        if (combinedPdfText !== "") isComplex = true;
        mode = isComplex ? 'smart' : 'fast';

        if (base64ImagesArray.length > 0) {
            mode = isComplex ? 'smart' : 'vision';
        }
        if (currentGithubRepo) mode = 'github';
    }

    const loadingId = appendLoadingWithMode(mode); scrollToBottom();
    if (window.activeForceMode === null) removeFile();
    if (currentController) currentController.abort(); currentController = new AbortController();

    chatInput.disabled = true;
    const sendBtn = document.getElementById('sendButton');
    sendBtn.style.opacity = '0.5';
    sendBtn.style.pointerEvents = 'none';

    // 7. TEMBAK API KE BACKEND LARAVEL
    try {
        const response = await fetch("{{ route('chat.send') }}", { method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" }, body: JSON.stringify(payload), signal: currentController.signal });
        if (!response.ok) throw new Error(`Server Error: ${response.status}`);
        const data = await response.json(); if (data.error) throw new Error(data.message);

        const loadingBubble = document.getElementById(loadingId);
        if (loadingBubble) {
            const aiMessageDiv = document.createElement('div'); aiMessageDiv.className = 'message ai';
            let finalModelLabel = '<i class="fas fa-bolt"></i> Mode Cepat';
                    let finalBadgeClass = 'mode-fast';
                    let extraStyle = '';
                    const modelUsedStr = (data.model_used || '').toLowerCase();

                    if (modelUsedStr.includes('vision') || modelUsedStr.includes('gemma')) {
                        finalModelLabel = '<i class="fas fa-eye"></i> Mode Vision';
                        extraStyle = 'background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);';
                        finalBadgeClass = '';
                    }
                    else if (modelUsedStr.includes('coder') || modelUsedStr.includes('qwen')) {
                        finalModelLabel = '<i class="fas fa-code"></i> Mode Code';
                        extraStyle = 'background: rgba(168, 85, 247, 0.15); color: #a855f7; border: 1px solid rgba(168, 85, 247, 0.3);';
                        finalBadgeClass = '';
                    }
                    // JURUS PEMBEDA MISTRAL: Cek apakah ini versi Medium/128B (Cerdas) atau Small/119B (Cepat)
                    else if (modelUsedStr.includes('medium') || modelUsedStr.includes('128b')) {
                        finalModelLabel = '<i class="fas fa-brain"></i> Mode Cerdas';
                        finalBadgeClass = 'mode-smart';
                    }
                    else if (modelUsedStr.includes('small') || modelUsedStr.includes('119b')) {
                        finalModelLabel = '<i class="fas fa-bolt"></i> Mode Cepat';
                        finalBadgeClass = 'mode-fast';
                    }

            aiMessageDiv.innerHTML = `<div class="message-avatar ai-avatar-msg" style="background: transparent; padding: 0;"><img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;"></div><div class="message-content"><div class="mode-badge ${finalBadgeClass}" style="${extraStyle}">${finalModelLabel}</div><div class="message-bubble markdown-body"></div><div class="ai-actions" style="position: relative; display: flex; gap: 5px; align-items: center;"><button class="action-btn" onclick="copyText(this)"><i class="far fa-copy"></i> Salin</button><div class="export-dropdown-container"><button class="action-btn" onclick="toggleExportMenu(this)"><i class="fas fa-ellipsis-v"></i></button><div class="export-menu" style="display: none; position: absolute; bottom: 100%; left: 0; background: var(--sidebar-bg); border: 1px solid var(--glass-border); border-radius: 8px; padding: 5px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); z-index: 50; width: 140px; margin-bottom: 5px;"><div class="option-item" style="font-size: 0.8rem; padding: 6px 10px;" onclick="exportToDoc(this)"><i class="fas fa-file-word" style="color: #3b82f6;"></i> Unduh DOCS</div></div></div></div></div>`;
            loadingBubble.parentNode.replaceChild(aiMessageDiv, loadingBubble);

            const bubble = aiMessageDiv.querySelector('.message-bubble'); if (bubble) animateGeminiStyle(bubble, data.ai_response); scrollToBottom();
        }

        if (!currentSessionId && data.session_id) { window.history.pushState({}, '', `/chat/${data.session_id}`); currentSessionId = data.session_id; }
        window.activeForceMode = null;

        // TAMBAHAN WAJIB: RESET GITHUB SETELAH SUKSES TERKIRIM
        currentGithubRepo = "";
        currentFileName = "";

    } catch (error) {
        const lBubble = document.getElementById(loadingId);
        if (lBubble) lBubble.remove();
        if (error.name !== 'AbortError') showToast("Gagal: " + error.message, "error");
        window.activeForceMode = null;
    } finally {
        chatInput.disabled = false;
        sendBtn.style.opacity = '1';
        sendBtn.style.pointerEvents = 'auto';
        chatInput.focus();
    }
}

function appendMessage(sender, text) {
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('message', sender);

    let safeText = text;
    if (sender === 'user') safeText = text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");

    // FIX: Pastikan avatar AI dan User konsisten menggunakan desain baru!
    const avatarHtml = sender === 'user'
        ? `<div class="message-avatar user-avatar-msg" style="padding:0; overflow:hidden;"><img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=2563eb&color=fff' }}" style="width: 100%; height: 100%; object-fit: cover;"></div>`
        : `<div class="message-avatar ai-avatar-msg" style="background: transparent; padding: 0; border: 1px solid var(--glass-border); overflow:hidden;"><img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="AI" style="width: 100%; height: 100%; object-fit: cover;"></div>`;

    messageDiv.innerHTML = `${avatarHtml}<div class="message-content"><div class="message-bubble">${safeText}</div></div>`;
    document.getElementById('messagesContainer').appendChild(messageDiv);
    scrollToBottom();
}

function appendLoadingWithMode(mode) {
    const id = 'loading-' + Date.now();
    const div = document.createElement('div');
    div.id = id;
    div.className = 'message ai';

    let badgeHtml = ''; let textHtml = '';
    if (mode === 'vision') { badgeHtml = `<div class="mode-badge" style="background: rgba(16, 185, 129, 0.15); color: #10b981;"><i class="fas fa-eye"></i> Mode Vision</div>`; textHtml = `<span class="typing-text">Menganalisis...</span>`; }
    else if (mode === 'github' || mode === 'coding') { badgeHtml = `<div class="mode-badge" style="background: rgba(168, 85, 247, 0.15); color: #a855f7;"><i class="fas fa-code"></i> Mode Code</div>`; textHtml = `<span class="typing-text">Menganalisis...</span>`; }
    else if (mode === 'smart') { badgeHtml = `<div class="mode-badge mode-smart"><i class="fas fa-brain"></i> Mode Cerdas</div>`; textHtml = `<span class="typing-text">Bernalar... <button class="switch-btn" onclick="switchToFastMode()">[Beralih ke Cepat]</button></span>`; }
    else { badgeHtml = `<div class="mode-badge mode-fast"><i class="fas fa-bolt"></i> Mode Cepat</div>`; textHtml = `<span class="typing-text">Berpikir... <button class="switch-btn" style="color:#d4a017;" onclick="switchToMode('smart')">[Beralih ke Cerdas]</button></span>`; }

    // FIX: Ganti icon robot dengan Logo SAHAJA AI
    div.innerHTML = `
        <div class="message-avatar ai-avatar-msg" style="background: transparent; padding: 0; border: 1px solid var(--glass-border); overflow:hidden;">
            <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="AI" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div class="message-content">
            ${badgeHtml}
            <div class="message-bubble">
                <div class="typing-indicator">
                    <div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div>${textHtml}
                </div>
            </div>
        </div>`;

    document.getElementById('messagesContainer').appendChild(div);
    return id;
}

// ==========================================
// 7. UTILITIES (Copy, Markdown, Event Listeners)
// ==========================================
function copyText(btn) { try { const messageContent = btn.closest('.message-content'); if (!messageContent) return; let textElement = messageContent.querySelector('.markdown-body') || messageContent.querySelector('.message-bubble'); if (!textElement) return; const textToCopy = textElement.innerText || textElement.textContent; const originalHTML = btn.innerHTML; const showSuccess = () => { btn.innerHTML = '<i class="fas fa-check"></i> Disalin'; btn.style.color = '#4ade80'; setTimeout(() => { btn.innerHTML = originalHTML; btn.style.color = ''; }, 2000); }; if (navigator.clipboard && window.isSecureContext) navigator.clipboard.writeText(textToCopy).then(showSuccess).catch(() => fallbackCopyText(textToCopy, showSuccess)); else fallbackCopyText(textToCopy, showSuccess); } catch (err) { showToast('Gagal menyalin teks.', 'error'); } }
function copyCode(button, codeElement) { if (!codeElement) return; const textToCopy = codeElement.textContent || codeElement.innerText; const showSuccess = () => { button.innerHTML = '<i class="fas fa-check"></i> Disalin'; button.style.background = 'rgba(74, 222, 128, 0.9)'; button.style.color = 'white'; setTimeout(() => { button.innerHTML = '<i class="far fa-copy"></i> Salin'; button.style.background = ''; button.style.color = ''; }, 2000); }; if (navigator.clipboard && window.isSecureContext) navigator.clipboard.writeText(textToCopy).then(showSuccess).catch(() => fallbackCopyText(textToCopy, showSuccess)); else fallbackCopyText(textToCopy, showSuccess); }
function fallbackCopyText(text, callback) { const textArea = document.createElement('textarea'); textArea.value = text; textArea.style.position = 'fixed'; textArea.style.left = '-9999px'; document.body.appendChild(textArea); textArea.focus(); textArea.select(); try { if (document.execCommand('copy') && callback) callback(); else showToast('Gagal menyalin', 'error'); } catch (err) {} document.body.removeChild(textArea); }
function addCopyButtonsToCodeBlocks() { document.querySelectorAll('.markdown-body pre').forEach((pre) => { if (pre.previousElementSibling?.classList.contains('code-header')) return; const code = pre.querySelector('code'); if (!code) return; let language = 'plaintext'; const langClass = code.className.match(/language-(\w+)/); if (langClass) language = langClass[1]; const header = document.createElement('div'); header.className = 'code-header'; header.innerHTML = `<span class="code-lang">${language}</span><button class="code-copy-btn" aria-label="Salin kode"><i class="far fa-copy"></i> Salin</button>`; pre.parentNode.insertBefore(header, pre); pre.style.borderRadius = '0 0 8px 8px'; pre.style.marginTop = '0'; const copyBtn = header.querySelector('.code-copy-btn'); copyBtn.addEventListener('click', (e) => { e.preventDefault(); e.stopPropagation(); copyCode(copyBtn, code); }); }); }
function animateGeminiStyle(element, markdownText) {
    const tempDiv = document.createElement('div'); renderAIContent(markdownText, tempDiv);
    element.innerHTML = '';
    Array.from(tempDiv.children).forEach((child) => { const wrapper = document.createElement('div'); wrapper.className = 'gemini-block'; wrapper.appendChild(child); element.appendChild(wrapper); });
    let delay = 0;
    element.querySelectorAll('.gemini-block').forEach((block) => { setTimeout(() => { block.classList.add('show'); scrollToBottom(); }, delay); delay += 120; });

    // INI YANG DITAMBAHKAN: Panggil Mermaid setelah animasi selesai
    setTimeout(() => {
        addCopyButtonsToCodeBlocks();
        processMermaidDiagrams(element);
    }, delay + 100);
}
function renderAIContent(text, containerElement) {
    let rawText = text.replace(/\\\[/g, '$$$$').replace(/\\\]/g, '$$$$').replace(/\\\(/g, '$$').replace(/\\\)/g, '$$');

    // 1. TANGKAP TAG THINKING
    const thinkingBlocks = {};
    let thinkingIndex = 0;
    rawText = rawText.replace(/<(?:thinking|think)>([\s\S]*?)<\/(?:thinking|think)>/gi, function(match, innerThinking) {
        const placeholder = `@@THINKING_BLOCK_${thinkingIndex}@@`;
        const cleanThinking = innerThinking.trim().replace(/</g, "&lt;").replace(/>/g, "&gt;");

        thinkingBlocks[placeholder] = `
        <div class="thinking-container" style="margin: 10px 0 20px 0; border: 1px solid var(--glass-border); border-radius: 12px; overflow: hidden; background: rgba(0, 0, 0, 0.2);">
            <div class="thinking-header" style="padding: 10px 15px; cursor: pointer; display: flex; align-items: center; gap: 10px; font-size: 0.85rem; color: var(--text-secondary); background: rgba(255, 255, 255, 0.05);" onclick="toggleThinking(this)">
                <i class="fas fa-brain"></i> <span style="font-weight: 500;">Alur Berpikir SAHAJA AI</span>
                <i class="fas fa-chevron-right" style="margin-left: auto; transition: 0.2s;"></i>
            </div>
            <div class="thinking-content" style="display: none; padding: 15px; font-size: 0.85rem; color: var(--text-secondary); border-top: 1px solid var(--glass-border); white-space: pre-wrap; font-style: italic; line-height: 1.6;">${cleanThinking}</div>
        </div>`;
        thinkingIndex++;
        return placeholder;
    });

    // 2. TANGKAP RUMUS MATEMATIKA (Biar tidak rusak oleh Markdown)
    const mathBlocks = {};
    let mathIndex = 0;
    rawText = rawText.replace(/\$\$([\s\S]*?)\$\$/g, function(match) { const placeholder = `@@MATH_BLOCK_${mathIndex}@@`; mathBlocks[placeholder] = match; mathIndex++; return placeholder; });
    rawText = rawText.replace(/\$([^$\n]*?)\$/g, function(match) { const placeholder = `@@MATH_INLINE_${mathIndex}@@`; mathBlocks[placeholder] = match; mathIndex++; return placeholder; });

    // 3. UBAH TEKS JADI MARKDOWN
    let htmlContent = marked.parse(rawText);

    // 4. KEMBALIKAN RUMUS MATEMATIKA
    for (const [placeholder, mathText] of Object.entries(mathBlocks)) {
        htmlContent = htmlContent.split(placeholder).join(mathText);
    }

    // 5. KEMBALIKAN KOTAK THINKING (JURUS ANTI-BUG PARAGRAF)
    for (const [placeholder, thinkText] of Object.entries(thinkingBlocks)) {
        // Hancurkan tag <p> yang membungkusnya!
        const pRegex = new RegExp(`<p>\\s*${placeholder}\\s*</p>`, 'g');
        if (pRegex.test(htmlContent)) {
            htmlContent = htmlContent.replace(pRegex, thinkText);
        } else {
            htmlContent = htmlContent.split(placeholder).join(thinkText);
        }
    }

    containerElement.innerHTML = htmlContent;

    // 6. EKSEKUSI RENDER HIGHLIGHT KODE & MATEMATIKA
    if (window.renderMathInElement) window.renderMathInElement(containerElement, { delimiters: [{ left: '$$', right: '$$', display: true }, { left: '$', right: '$', display: false }], throwOnError: false });
    containerElement.querySelectorAll('pre code').forEach((block) => { if (window.hljs) hljs.highlightElement(block); });
}

// Fungsi Helper untuk klik buka/tutup (letakkan di luar renderAIContent)
function toggleThinking(header) {
    const content = header.nextElementSibling;
    const icon = header.querySelector('.fa-chevron-right');
    const isVisible = content.style.display === 'block';
    content.style.display = isVisible ? 'none' : 'block';
    icon.style.transform = isVisible ? 'none' : 'rotate(90deg)';
}
function scrollToBottom() { const c = document.getElementById('messagesContainer'); if(c) c.scrollTop = c.scrollHeight; }
function scrollToBottomSmooth() { const c = document.getElementById('messagesContainer'); if(c) c.scrollTo({ top: c.scrollHeight, behavior: 'smooth' }); }
function formatAttachmentIcons() { document.querySelectorAll('.message.user .message-bubble').forEach(el => { let html = el.innerHTML; html = html.replace(/📎 \[(.*?)\]/g, '<i class="fas fa-file-pdf" style="color: #3b82f6; margin-right: 5px;"></i> <b>[Dokumen: $1]</b>'); html = html.replace(/🖼️ \[(.*?)\]/g, '<i class="fas fa-image" style="color: #10b981; margin-right: 5px;"></i> <b>[$1]</b>'); html = html.replace(/📦 \[GitHub: (.*?)\]/g, '<i class="fab fa-github" style="color: #a855f7; margin-right: 5px;"></i> <b>[GitHub: $1]</b>'); el.innerHTML = html; }); }
function useShortcut(text) { chatInput.value = text; chatInput.focus(); }

document.getElementById('sendButton')?.addEventListener('click', () => sendMessage());
chatInput?.addEventListener('input', function() { this.style.height = 'auto'; this.style.height = (this.scrollHeight) + 'px'; });
chatInput?.addEventListener('keydown', (e) => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); } });
document.getElementById('sidebarToggleBtn')?.addEventListener('click', e => { e.stopPropagation(); document.getElementById('sidebar').classList.toggle('collapsed'); });
document.getElementById('mobileToggleBtn')?.addEventListener('click', e => { e.stopPropagation(); document.getElementById('sidebar').classList.toggle('mobile-open'); });
window.addEventListener('click', e => {
    if (window.innerWidth <= 768 && !document.getElementById('sidebar').contains(e.target) && !e.target.closest('.mobile-toggle-btn')) document.getElementById('sidebar').classList.remove('mobile-open');
    if (!e.target.closest('.settings-container')) document.querySelectorAll('.options-menu, .logout-menu, .attach-menu').forEach(el => el.classList.remove('show'));
});

const chatContainerBox = document.getElementById('messagesContainer');
if (chatContainerBox) { chatContainerBox.addEventListener('scroll', () => { if (chatContainerBox.scrollTop + chatContainerBox.clientHeight < chatContainerBox.scrollHeight - 150) document.getElementById('scrollToBottomBtn').style.display = 'block'; else document.getElementById('scrollToBottomBtn').style.display = 'none'; }); }

document.addEventListener('DOMContentLoaded', () => {
    formatAttachmentIcons();
    document.querySelectorAll('.message.ai').forEach((el) => {
        const rawDiv = el.querySelector('.ai-raw-data');
        const renderDiv = el.querySelector('.ai-rendered-data');
        if (rawDiv && renderDiv) {
            renderAIContent(rawDiv.textContent.trim(), renderDiv);
            // INI YANG DITAMBAHKAN: Proses diagram untuk chat masa lalu
            processMermaidDiagrams(renderDiv);
        }
    });
    setTimeout(addCopyButtonsToCodeBlocks, 500);
    const chatCount = {{ count($chats ?? []) }};
    const updateModal = document.getElementById('updateModal');

    if (chatCount === 0 && updateModal && !sessionStorage.getItem('sahajaModalShown')) {
        setTimeout(() => {
            updateModal.classList.add('show');
            sessionStorage.setItem('sahajaModalShown', 'true');
        }, 1000); }
    document.getElementById('closeModalBtn')?.addEventListener('click', () => updateModal?.classList.remove('show'));
});

// ==========================================
// 8. VOICE RECORDING
// ==========================================
let recognition = null; let isRecording = false; let final_transcript = '';
if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    recognition = new SpeechRecognition(); recognition.lang = 'id-ID'; recognition.interimResults = true; recognition.continuous = false;
    recognition.onstart = function() { isRecording = true; final_transcript = ''; voiceBtn.classList.add('recording'); voiceBtn.innerHTML = '<i class="fas fa-stop"></i>'; chatInput.placeholder = "Mendengarkan..."; };
    recognition.onresult = function(event) { let interim_transcript = ''; for (let i = event.resultIndex; i < event.results.length; ++i) { if (event.results[i].isFinal) final_transcript += event.results[i][0].transcript; else interim_transcript += event.results[i][0].transcript; } const prefix = window.preRecordInput ? window.preRecordInput + ' ' : ''; chatInput.value = prefix + final_transcript + interim_transcript; chatInput.dispatchEvent(new Event('input')); };
    recognition.onerror = function() { forceStopRecordingUI(); }; recognition.onend = function() { forceStopRecordingUI(); };
} else { if(voiceBtn) voiceBtn.style.display = 'none'; }

function forceStopRecordingUI() { isRecording = false; voiceBtn.classList.remove('recording'); voiceBtn.innerHTML = '<i class="fas fa-microphone"></i>'; chatInput.placeholder = "Ketik pesan..."; }
voiceBtn?.addEventListener('click', () => { if (!recognition) return showToast("Browser tidak support Voice", "error"); if (isRecording) { recognition.stop(); forceStopRecordingUI(); } else { window.preRecordInput = chatInput.value.trim(); try { recognition.start(); } catch (e) {} } });

function openHelpModal() { document.getElementById('helpModal').classList.add('show'); document.getElementById('logout-menu').classList.remove('show'); }
function switchHelpTab(tab) {
    document.getElementById('help-faq').style.display = tab === 'faq' ? 'block' : 'none';
    document.getElementById('help-feedback').style.display = tab === 'feedback' ? 'block' : 'none';
    document.getElementById('btn-faq').style.background = tab === 'faq' ? 'var(--accent-gradient)' : 'transparent';
    document.getElementById('btn-faq').style.border = tab === 'faq' ? 'none' : '1px solid var(--glass-border)';
    document.getElementById('btn-feedback').style.background = tab === 'feedback' ? 'var(--accent-gradient)' : 'transparent';
    document.getElementById('btn-feedback').style.border = tab === 'feedback' ? 'none' : '1px solid var(--glass-border)';
}
async function submitFeedback() {
    const btn = document.querySelector('#help-feedback button');
    const textArea = document.getElementById('feedbackText');
    const text = textArea.value.trim();

    if(!text) return showToast("Tulis masukan terlebih dahulu", "error");

    // Ubah tombol jadi loading
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
    btn.disabled = true;

    try {
        const response = await fetch("{{ route('feedback.send') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Accept": "application/json"
            },
            body: JSON.stringify({ message: text })
        });

        const data = await response.json();
        if(response.ok) {
            showToast("Terima kasih! Masukan Anda telah terkirim.", "success");
            textArea.value = '';
            closeCustomModal('helpModal');
        } else {
            throw new Error("Gagal mengirim data");
        }
    } catch (error) {
        showToast("Terjadi kesalahan jaringan", "error");
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}
// Fungsi Buka Tutup Menu Export
function toggleExportMenu(btn) {
    const menu = btn.nextElementSibling;
    const isShowing = menu.style.display === 'block';
    document.querySelectorAll('.export-menu').forEach(m => m.style.display = 'none'); // Tutup yang lain
    if (!isShowing) menu.style.display = 'block';
    event.stopPropagation();
}

// Tutup menu jika klik di luar
window.addEventListener('click', function() {
    document.querySelectorAll('.export-menu').forEach(m => m.style.display = 'none');
});

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
// FUNGSI EXPORT KE DOCX (ASLI, SUPPORT HP)
// ==========================================
window.exportToDoc = function(btn) {
    // 1. Ambil isi teks dari gelembung chat AI (Ambil yang SUDAH RENDER / HTML)
    const messageDiv = btn.closest('.message-content');
    // Prioritaskan mengambil .ai-rendered-data agar yang diambil adalah HTML bersih, bukan raw markdown
    const bubble = messageDiv.querySelector('.ai-rendered-data') || messageDiv.querySelector('.markdown-body');

    if (!bubble) {
        if (typeof showToast === "function") showToast("Gagal mengambil teks!", "error");
        return;
    }

    if (typeof showToast === "function") showToast("Merakit file DOCX...", "info");

    // 2. KUNCI RAHASIA: Masukkan bubble ke dalam Mesin Pembersih (prepareExportContent)
    // Langkah ini yang kemarin terlewat!
    const cleanPrintDiv = prepareExportContent(bubble);

    // 3. Siapkan kerangka HTML yang bersih agar rapi di Word
    const contentHTML = `
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <title>SAHAJA AI Export</title>
            <style>
                body { font-family: 'Segoe UI', Arial, sans-serif; color: #333; line-height: 1.6; }
                h1, h2, h3 { color: #2563eb; }
                code { background-color: #f1f5f9; padding: 2px 5px; border-radius: 4px; font-family: monospace; }
                pre { background-color: #f8fafc; padding: 15px; border-left: 4px solid #2563eb; }
                table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
                table, th, td { border: 1px solid #cbd5e1; }
                th, td { padding: 10px; text-align: left; }
                th { background-color: #f1f5f9; }
            </style>
        </head>
        <body>
            <div style="border-bottom: 2px solid #2563eb; padding-bottom: 10px; margin-bottom: 20px;">
                <h2 style="margin: 0;">SAHAJA AI Document</h2>
                <span style="color: #64748b; font-size: 12px;">Diekspor pada: ${new Date().toLocaleString('id-ID')}</span>
            </div>
            ${cleanPrintDiv.innerHTML}
        </body>
        </html>
    `;

    try {
        // 4. Gunakan Library untuk merakit HTML menjadi file .docx asli (Blob)
        const converted = htmlDocx.asBlob(contentHTML);

        // 5. Buat proses download otomatis
        const link = document.createElement('a');
        link.href = URL.createObjectURL(converted);
        link.download = 'SAHAJA_AI_Export_' + Date.now() + '.docx';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        if (typeof showToast === "function") showToast("Berhasil diunduh! (DOCX)", "success");
    } catch (error) {
        console.error("Export Error:", error);
        if (typeof showToast === "function") showToast("Gagal mengekspor dokumen.", "error");
    }
};

// ====================fungsi mermaid======================== //
// 1. Inisialisasi Tema Mermaid agar cocok dengan SAHAJA AI
        mermaid.initialize({
            startOnLoad: false,
            theme: 'dark',
            suppressErrorRendering: true
        });

// 2. Fungsi Menyulap Teks Code Menjadi Visual Diagram (ANTI ERROR / ANTI BOM)
async function processMermaidDiagrams(container) {
    const mermaidBlocks = container.querySelectorAll('code.language-mermaid');
    if(mermaidBlocks.length === 0) return;

    // Gunakan perulangan FOR biasa (bukan forEach) agar bisa pakai 'await'
    for (let i = 0; i < mermaidBlocks.length; i++) {
        const codeBlock = mermaidBlocks[i];
        const preBlock = codeBlock.parentElement;

        if(preBlock.classList.contains('mermaid-processed')) continue;
        preBlock.classList.add('mermaid-processed');

        // Ambil teks murni
        let rawCode = codeBlock.textContent || codeBlock.innerText;

        // 1. Bersihkan spasi ghaib (NBSP)
        rawCode = rawCode.replace(/\u00A0/g, ' ').trim();

        // 2. JURUS FILTER MESIN CUCI: Bersihkan kotoran sisa Markdown AI
        // Hilangkan kata "mermaid" di awal teks jika AI tidak sengaja menuliskannya
        rawCode = rawCode.replace(/^mermaid\s*/i, '');
        // Hilangkan sisa backtick (```) yang nyangkut
        rawCode = rawCode.replace(/```/g, '');
        // Hilangkan spasi berlebih di awal & akhir
        rawCode = rawCode.trim();

        const uniqueId = 'mermaid-' + Date.now() + '-' + i;

        const wrapper = document.createElement('div');
        wrapper.className = 'mermaid-wrapper';
        wrapper.innerHTML = `
            <div class="mermaid-header">
                <div class="mermaid-tabs">
                    <button class="mermaid-tab active" onclick="switchMermaid('${uniqueId}', 'diagram', this)">Visual Diagram</button>
                    <button class="mermaid-tab" onclick="switchMermaid('${uniqueId}', 'code', this)">Source Code</button>
                </div>
                <button class="mermaid-download" onclick="downloadMermaid('${uniqueId}')"><i class="fas fa-image"></i> JPG</button>
            </div>
            <div id="${uniqueId}-diagram" class="mermaid-content">
                <div style="color: var(--accent-color); padding: 20px;"><i class="fas fa-spinner fa-spin"></i> Menggambar diagram...</div>
            </div>
            <div id="${uniqueId}-code" class="mermaid-code">
                <pre><code class="language-mermaid"></code></pre>
            </div>
        `;

        wrapper.querySelector('.language-mermaid').textContent = rawCode;
        preBlock.replaceWith(wrapper);

    try {
        const { svg } = await mermaid.render(uniqueId + '-svg', rawCode);
        document.getElementById(uniqueId + '-diagram').innerHTML = svg;
    } catch (e) {
        // Sapu ranjau: Hapus elemen SVG error yang terlanjur terlempar ke DOM
        const errorElement = document.getElementById(uniqueId + '-svg');
        const dErrorElement = document.getElementById('d' + uniqueId + '-svg');
        if (errorElement) errorElement.remove();
        if (dErrorElement) dErrorElement.remove();

        // Tampilkan UI Error Custom kita
        document.getElementById(uniqueId + '-diagram').innerHTML = `
            <div style="color: #ef4444; padding: 15px; border: 1px dashed #ef4444; border-radius: 8px; margin: 10px;">
                <div style="font-weight: bold; margin-bottom: 5px;"><i class="fas fa-exclamation-triangle"></i> Diagram Gagal Digambar</div>
                Sintaks diagram dari AI tidak valid. Klik tab <b>Source Code</b> untuk melihat kodenya.
            </div>`;
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

// 4. Fungsi Tombol Download Gambar Diagram (UBAH KE JPG UNTUK SUPPORT HP)
window.downloadMermaid = function(id) {
    const svg = document.querySelector(`#${id}-diagram svg`);
    if(!svg) return showToast('Diagram belum selesai diproses', 'error');

    showToast('Merender gambar Ultra HD...', 'info');

    // 1. Gandakan SVG agar tidak merusak tampilan asli di layar HP
    const svgClone = svg.cloneNode(true);

    // 2. KUNCI RAHASIA: Tentukan Skala Resolusi (3x lipat lebih tajam)
    const scale = 5;
    const origWidth = svg.getBoundingClientRect().width || 800;
    const origHeight = svg.getBoundingClientRect().height || 600;

    // 3. Paksa kloningan SVG menjadi ukuran raksasa sebelum difoto
    svgClone.setAttribute("width", origWidth * scale);
    svgClone.setAttribute("height", origHeight * scale);

    // 4. Ambil data mentah dari SVG raksasa
    const svgData = new XMLSerializer().serializeToString(svgClone);
    const canvas = document.createElement("canvas");
    const ctx = canvas.getContext("2d");
    const img = new Image();

    const b64Start = 'data:image/svg+xml;base64,';
    const image64 = b64Start + btoa(unescape(encodeURIComponent(svgData)));

    img.onload = function() {
        // 5. Atur kanvas menjadi ukuran raksasa
        canvas.width = origWidth * scale;
        canvas.height = origHeight * scale;

        // 6. Beri latar belakang putih bersih
        ctx.fillStyle = "#ffffff";
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // 7. Gambar diagram HD ke atas kanvas
        ctx.drawImage(img, 0, 0);

        // 8. Cetak menjadi JPG dengan Kualitas Super Maximum (1.0)
        const imgURI = canvas.toDataURL("image/jpeg", 1.0);

        const link = document.createElement("a");
        link.download = 'SAHAJA_Mermaid_' + Date.now() + '.jpg';
        link.href = imgURI;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        showToast('Gambar Berhasil Diunduh!', 'success');
    };

    img.src = image64;
};
// ==========================================
// FUNGSI SLIDER ONBOARDING / UPDATE MODAL
// ==========================================
function nextOnboardStep() {
    const step1 = document.getElementById('onboard-step-1');
    const step2 = document.getElementById('onboard-step-2');

    // 1. Buat Slide 1 memudar dan bergeser perlahan ke kiri
    step1.style.transition = "all 0.4s ease-in-out";
    step1.style.opacity = "0";
    step1.style.transform = "translateX(-30px)";

    // 2. Tunggu 400ms (sampai animasi slide 1 selesai), baru panggil Slide 2
    setTimeout(() => {
        step1.style.display = 'none';
        step2.style.display = 'block';
        // Slide 2 masuk dari kanan (menggunakan CSS slide-in-right yang sudah ada)
        step2.classList.add('slide-in-right');
    }, 400);
}
// Fungsi untuk menutup modal dengan mulus
window.closeOnboardModal = function() {
    const updateModal = document.getElementById('updateModal');
    if(updateModal) {
        // Berikan efek memudar sebelum hilang
        updateModal.style.transition = "opacity 0.4s ease";
        updateModal.style.opacity = "0";

        setTimeout(() => {
            updateModal.classList.remove('show');
            // Reset opacity untuk pemakaian berikutnya
            updateModal.style.opacity = "1";
            updateModal.style.display = "none";
        }, 400);
    }
};
// ==========================================
// SANG MANDOR (VERSI X-RAY ANTI SILENT DEATH)
// ==========================================
let currentResearchId = null;

async function startDeepResearch(prompt) {
    document.getElementById('floatingResearchBtn').style.display = 'none';
    document.getElementById('researchPanel').classList.add('active');
    const logsContainer = document.getElementById('researchLogs');
    logsContainer.innerHTML = '';

    appendResearchLog('Menginisialisasi Agen Alpha...', 'processing');

    try {
        const res = await fetch('/deep-research/init', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({topic: prompt, session_id: currentSessionId})
        });

        // JURUS X-RAY: Ambil teks mentah dari server sebelum di-parse jadi JSON!
        const rawText = await res.text();

        let data;
        try {
            data = JSON.parse(rawText);
        } catch(err) {
            // JIKA GAGAL JADI JSON (LARAVEL MELEMPAR ERROR 500)
            console.error("🔥 LARAVEL ERROR KETAHUAN:", rawText);
            appendResearchLog('Server Laravel Meledak! Buka Console (F12).', 'error');
            alert("ERROR SERVER! Tekan F12 di keyboard, buka tab 'Console' untuk melihat penyakit aslinya!");
            return; // Hentikan proses agar tidak mutar-mutar
        }

        if(data.success) {
            currentResearchId = data.research_id;

            // UPDATE URL JIKA SESSION BARU: Biar kalau refresh nggak balik ke welcome screen
            if (!currentSessionId && data.session_id) {
                window.history.pushState({}, '', `/chat/${data.session_id}`);
                currentSessionId = data.session_id;

                // HAPUS ITEM SEMENTARA: Biar tidak double saat halaman di-render ulang nanti
                const tempItem = document.getElementById('temp-session-loading');
                if (tempItem) tempItem.remove();
            }

            appendResearchLog('Agen berhasil diaktifkan. Memulai pencarian data...', 'info');
            setTimeout(pollResearchStep, 2000);
        } else {
            appendResearchLog('Gagal Inisialisasi: ' + (data.message || 'Server Menolak'), 'error');
        }
    } catch (e) {
        appendResearchLog('Gagal menyambung ke server! Koneksi terputus.', 'error');
    }
}

async function pollResearchStep() {
    if(!currentResearchId) return;

    try {
        const res = await fetch('/deep-research/step', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({research_id: currentResearchId})
        });

        // JURUS X-RAY UNTUK STEP 2
        const rawText = await res.text();
        let data;
        try {
            data = JSON.parse(rawText);
        } catch(err) {
            console.error("🔥 LARAVEL ERROR PADA SAAT PROSES AI:", rawText);
            appendResearchLog('Proses terhenti karena Error di Server. Cek Console.', 'error');
            return; // Hentikan agar tidak polling abadi
        }

        const logsContainer = document.getElementById('researchLogs');
        logsContainer.innerHTML = '';
        if(data.logs && data.logs.length > 0) {
            data.logs.forEach(log => {
                logsContainer.innerHTML += `<div class="log-item info"><span style="color: #94a3b8; font-size: 0.75rem; margin-right: 5px;">[${log.time}]</span> ${log.message}</div>`;
            });
        }
        logsContainer.scrollTop = logsContainer.scrollHeight;

        if(data.status === 'selesai') {
            appendResearchLog('Menutup Agen Alpha...', 'success');

            // SULAP HASILNYA MENJADI CARD CHAT!
            const aiMessageDiv = document.createElement('div');
            aiMessageDiv.className = 'message ai';
            aiMessageDiv.innerHTML = `<div class="message-avatar ai-avatar-msg" style="background: transparent; padding: 0;"><img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;"></div><div class="message-content"><div class="mode-badge" style="background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3);"><i class="fas fa-atom"></i> Hasil Deep Research</div><div class="message-bubble markdown-body ai-raw-data" style="display:none;">${data.result}</div><div class="message-bubble markdown-body ai-rendered-data"></div><div class="ai-actions" style="position: relative; display: flex; gap: 5px; align-items: center;"><button class="action-btn" onclick="copyText(this)"><i class="far fa-copy"></i> Salin</button><div class="export-dropdown-container"><button class="action-btn" onclick="toggleExportMenu(this)"><i class="fas fa-ellipsis-v"></i></button><div class="export-menu" style="display: none; position: absolute; bottom: 100%; left: 0; background: var(--sidebar-bg); border: 1px solid var(--glass-border); border-radius: 8px; padding: 5px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); z-index: 50; width: 140px; margin-bottom: 5px;"><div class="option-item" style="font-size: 0.8rem; padding: 6px 10px;" onclick="exportToDoc(this)"><i class="fas fa-file-word" style="color: #3b82f6;"></i> Unduh DOCS</div></div></div></div></div>`;

            document.getElementById('messagesContainer').appendChild(aiMessageDiv);

            const rawDiv = aiMessageDiv.querySelector('.ai-raw-data');
            const renderDiv = aiMessageDiv.querySelector('.ai-rendered-data');
            renderAIContent(rawDiv.textContent.trim(), renderDiv);
            scrollToBottomSmooth();

            // PERBAIKAN: Tutup panel dan hapus tombol melayang secara paksa tanpa fungsi toggle
            setTimeout(() => {
                document.getElementById('researchPanel').classList.remove('active');
                document.getElementById('floatingResearchBtn').style.display = 'none';
                currentResearchId = null; // Bersihkan memori agar tombol tidak "nyangkut"
            }, 3000);
            return;

        } else if (data.status === 'error') {
            appendResearchLog('Proses dibatalkan karena error.', 'error');

            // PERBAIKAN: Tutup juga saat error agar bersih
            setTimeout(() => {
                document.getElementById('researchPanel').classList.remove('active');
                document.getElementById('floatingResearchBtn').style.display = 'none';
                currentResearchId = null;
            }, 3000);
            return;
        }

        setTimeout(pollResearchStep, 2000);
    } catch(e) {
        setTimeout(pollResearchStep, 5000);
    }
}

function appendResearchLog(text, type = 'info') {
    const logsContainer = document.getElementById('researchLogs');
    const icon = type === 'processing' ? '<i class="fas fa-circle-notch fa-spin"></i>' :
                 (type === 'success' ? '<i class="fas fa-check"></i>' : '<i class="fas fa-info-circle"></i>');

    logsContainer.innerHTML += `<div class="log-item ${type}">${icon} <span style="margin-left: 8px;">${text}</span></div>`;
    logsContainer.scrollTop = logsContainer.scrollHeight; // Auto scroll ke bawah
}
// Fungsi untuk Buka/Tutup Panel Riset (Fixed)
window.toggleResearchPanel = function() {
    const panel = document.getElementById('researchPanel');
    const floatBtn = document.getElementById('floatingResearchBtn');

    if(panel.classList.contains('active')) {
        // Minimize
        panel.classList.remove('active');
        // Selalu munculkan tombol jika riset sedang berjalan atau baru saja mulai
        floatBtn.style.display = 'block';
    } else {
        // Expand
        panel.classList.add('active');
        floatBtn.style.display = 'none';
    }
};

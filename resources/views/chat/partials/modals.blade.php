{{-- MODAL UPDATE / ONBOARDING --}}
<div class="modal-overlay" id="updateModal" style="z-index: 100010;">
    <div class="modal-content"
        style="padding: 0; overflow: hidden; max-width: 550px; background: var(--sidebar-bg); border-radius: 20px;">
        <button class="modal-close" id="closeModalBtn"
            style="z-index: 50; top: 15px; right: 15px; background: rgba(0,0,0,0.3); color: white;"><i
                class="fas fa-times"></i></button>

        <div id="onboard-step-1" style="display: block; position: relative;">
            <div
                style="background: linear-gradient(-45deg, #0a0e17, #1e293b, var(--accent-color), #06b6d4); background-size: 400% 400%; animation: gradientAurora 12s ease infinite; padding: 60px 20px; text-align: center; position: relative; overflow: hidden;">

                <div
                    style="position: absolute; top: -10%; left: -10%; width: 180px; height: 180px; background: rgba(37, 99, 235, 0.6); border-radius: 50%; filter: blur(40px); animation: floatOrb 7s ease-in-out infinite;">
                </div>
                <div
                    style="position: absolute; bottom: -20%; right: -10%; width: 220px; height: 220px; background: rgba(6, 182, 212, 0.5); border-radius: 50%; filter: blur(50px); animation: floatOrb 9s ease-in-out infinite reverse;">
                </div>
                <div
                    style="position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 60%); animation: rotateGlow 20s linear infinite;">
                </div>

                <img src="https://i.ibb.co.com/wrrG06ds/Logo-SAHAJA-AI.png" alt="SAHAJA AI" class="animate-logo"
                    style="width: 90px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.4); position: relative; z-index: 2; margin-bottom: 20px;">

                <div class="animate-title" style="position: relative; z-index: 2; margin: 0;">
                    <h2
                        style="color: white; font-weight: 700; margin: 0; border: none; letter-spacing: 0.5px; font-size: 1.8rem; line-height: 1.3; text-shadow: 0 4px 15px rgba(0,0,0,0.4);">
                        Selamat Datang di<br>
                        <span class="welcome-greeting"
                            style="font-size: 2.4rem; display: inline-block; padding-top: 5px; text-shadow: none;">SAHAJA
                            AI</span>
                    </h2>
                </div>
            </div>
            <div style="padding: 30px 25px;">
                <p class="animate-desc"
                    style="color: var(--text-secondary); font-size: 0.95rem; line-height: 1.6; margin-bottom: 30px; text-align: center;">
                    Asisten AI cerdas yang dirancang khusus untuk mempermudah pengerjaan tugas, penulisan kodingan,
                    hingga analisis data Anda. Mari lihat apa saja yang baru!</p>
                <div class="animate-footer" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="onboard-dots">
                        <div class="dot active"></div>
                        <div class="dot"></div>
                    </div>
                    <button onclick="nextOnboardStep()" class="github-submit-btn"
                        style="position: relative; z-index: 999; cursor: pointer; padding: 10px 25px; border-radius: 30px; font-size: 0.9rem;">Selanjutnya
                        <i class="fas fa-arrow-right" style="margin-left: 5px;"></i></button>
                </div>
            </div>
        </div>

        <div id="onboard-step-2" style="display: none; padding: 0;">
            <div
                style="background: var(--sidebar-bg); padding: 25px 25px 10px 25px; border-bottom: 1px solid var(--glass-border); position: relative; z-index: 10;">
                <h2
                    style="color: var(--accent-color); font-size: 1.4rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 10px;">
                    PEMBARUAN SAHAJA AI <span
                        style="font-size: 0.7rem; background: var(--accent-color); color: #000; padding: 2px 8px; border-radius: 10px; margin-left: 5px;">v5.0</span>
                </h2>
            </div>
            <div class="modal-body" style="padding: 20px 25px; max-height: 400px; overflow-y: auto;">
                <div class="feature-item"
                    style="animation: fadeInUp 0.6s ease forwards; animation-delay: 0.1s; background: rgba(236, 72, 153, 0.05); border-color: rgba(236, 72, 153, 0.2);">
                    <div class="feature-icon-wrapper" style="background: linear-gradient(135deg, #ec4899, #be185d);">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                    <div>
                        <strong style="display: block; font-size: 0.95rem; color: #ec4899; margin-bottom: 3px;">Sahaja
                            Imagen</strong>
                        <span style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.4;">Buat gambar dengan mengetik <code>/imagen</code> atau lampirkan foto untuk mengedit
                            gambar. powered by: <b>Nano Banana 2</b>.</span>
                    </div>
                </div>
                <div class="feature-item"
                    style="animation: fadeInUp 0.6s ease forwards; animation-delay: 0.2s; background: rgba(139, 92, 246, 0.05); border-color: rgba(139, 92, 246, 0.2);">
                    <div class="feature-icon-wrapper" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div>
                        <strong style="display: block; font-size: 0.95rem; color: #8b5cf6; margin-bottom: 3px;">Notebook
                            LLM (Workspace)</strong>
                        <span style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.4;">Analisis
                            dokumen PDF panjang kini lebih cerdas dan akurat dengan mesin <b>MiniMax M2.7</b></span>
                    </div>
                </div>
                <div class="feature-item"
                    style="animation: fadeInUp 0.6s ease forwards; animation-delay: 0.4s; background: rgba(6, 182, 212, 0.05); border-color: rgba(6, 182, 212, 0.2);">
                    <div class="feature-icon-wrapper" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
                        <i class="fas fa-shield-halved"></i>
                    </div>
                    <div>
                        <strong style="display: block; font-size: 0.95rem; color: #06b6d4; margin-bottom: 3px;">Improve Security
                            & Speed</strong>
                        <span style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.4;">
                            SAHAJA AI kini lebih aman, privat, dan responsif!</span>
                    </div>
                </div>
            </div>
            <div
                style="display: flex; justify-content: space-between; align-items: center; padding: 20px 25px; border-top: 1px solid var(--glass-border); background: var(--sidebar-bg);">
                <div class="onboard-dots">
                    <div class="dot"></div>
                    <div class="dot active" style="width: 24px; background: var(--success-color);"></div>
                </div>
                <button id="finish-onboard-btn" onclick="closeOnboardModal()" class="github-submit-btn"
                    style="position: relative; z-index: 999; cursor: pointer; padding: 10px 25px; border-radius: 30px; font-size: 0.9rem; background: var(--success-color); box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); border: none; color: white; font-weight: 600;">
                    Mulai Chat!
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL GITHUB --}}
<div class="modal-overlay" id="githubModal">
    <div class="modal-content" style="max-width: 450px;">
        <button class="modal-close" id="closeGithubModalBtn"><i class="fas fa-times"></i></button>
        <h2 style="font-size: 1.3rem; border: none; margin-bottom: 0;"><i class="fab fa-github"></i> Impor
            Repository (Beta)</h2>
        <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 15px;">SAHAJA AI akan
            menganalisis file kode utama (.php, .js, dll) dari repo public.
            (Fitur masih dalam tahap pengembangan dan tidak sempurna)</p>

        <div class="github-input-group">
            <input type="text" id="githubLinkInput" class="github-input"
                placeholder="https://github.com/username/repo">
            <button id="submitGithubBtn" class="github-submit-btn">Muat Repository</button>
        </div>
    </div>
</div>

{{-- MODAL SETTINGS --}}
<div class="modal-overlay" id="settingsModal" style="z-index: 100000;">
    <button class="modal-close-outside" onclick="closeSettingsModal()"><i class="fas fa-times"></i></button>
    <div class="settings-modal-box">
        <div class="settings-sidebar">
            <h3 style="padding: 10px 10px; font-size: 1.1rem; color: var(--text-primary);">Pengaturan</h3>
            <button class="nav-btn active" onclick="switchTab('umum')"><i class="fas fa-cog"></i> Umum</button>
            <button class="nav-btn" onclick="switchTab('profil')"><i class="fas fa-user"></i> Profil</button>
            <button class="nav-btn" onclick="switchTab('data')"><i class="fas fa-database"></i> Data</button>
            <button class="nav-btn" onclick="switchTab('tentang')"><i class="fas fa-info-circle"></i>
                Tentang</button>
        </div>

        <div class="settings-content">
            <div id="tab-umum" class="tab-pane active">
                <h3 style="margin-bottom: 20px;">Umum</h3>
                <label
                    style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 10px; display: block;">Tema</label>
                <div style="display: flex; gap: 10px;">
                    <button class="theme-btn" id="btnThemeLight" onclick="setTheme('light')"><i
                            class="fas fa-sun"></i> Terang</button>
                    <button class="theme-btn" id="btnThemeDark" onclick="setTheme('dark')"><i
                            class="fas fa-moon"></i> Gelap</button>
                </div>
            </div>

            <div id="tab-profil" class="tab-pane">
                <h3 style="margin-bottom: 20px;">Profil</h3>
                <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px;">
                    <img id="previewAvatar"
                        src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=2563eb&color=fff' }}"
                        alt="Avatar" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <div style="display: flex; gap: 10px;">
                            <input type="file" id="avatarInput" accept="image/png, image/jpeg, image/webp"
                                style="display:none">
                            <button class="github-submit-btn" onclick="document.getElementById('avatarInput').click()"
                                style="padding: 5px 15px; font-size: 0.85rem;">Pilih Foto</button>
                            @if (Auth::user()->avatar)
                                <button class="action-btn"
                                    onclick="openConfirmModal('Hapus Foto Profil?', 'Foto profil akan dikembalikan ke inisial nama Anda.', 'deleteAvatar')"
                                    style="color: var(--danger-color); border: 1px solid var(--danger-color); padding: 5px 10px; border-radius: 8px; font-size: 0.85rem;"><i
                                        class="fas fa-trash"></i></button>
                            @endif
                        </div>
                        <p style="font-size: 0.75rem; color: var(--text-secondary);">Maks 2MB. Jangan lupa klik Simpan
                            di bawah.</p>
                    </div>
                </div>
                <div style="margin-bottom: 15px;">
                    <label
                        style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 5px; display: block;">Nama
                        Tampilan</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" id="inputNamaProfil" class="github-input"
                            value="{{ Auth::user()->name }}">
                        <button class="github-submit-btn" onclick="simpanProfil()">Simpan</button>
                    </div>
                </div>
                <div style="margin-bottom: 20px;">
                    <label
                        style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 5px; display: block;">Alamat
                        Email</label>
                    <input type="email" class="github-input" value="{{ Auth::user()->email }}" disabled
                        style="opacity: 0.6;">
                </div>
                <hr style="border: 0; border-top: 1px solid var(--glass-border); margin: 20px 0;">
                <button class="option-item delete"
                    style="width: auto; padding: 10px; font-weight: 600; border: 1px solid #ef4444;"
                    onclick="openConfirmModal('Hapus Akun Permanen?', 'Seluruh data akun, foto, dan obrolan akan hilang selamanya.', 'deleteAccount')"><i
                        class="fas fa-trash-alt"></i> Hapus Akun</button>
            </div>

            <div id="tab-data" class="tab-pane">
                <h3 style="margin-bottom: 20px;">Data</h3>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong style="display: block;">Hapus semua obrolan</strong>
                        <span style="font-size: 0.8rem; color: var(--text-secondary);">Tindakan ini tidak dapat
                            dibatalkan.</span>
                    </div>
                    <button class="option-item delete"
                        style="width: auto; padding: 8px 15px; border: 1px solid #ef4444; margin-top:10px;"
                        onclick="openConfirmModal('Hapus Semua Obrolan?',
                    'Seluruh riwayat chat Anda di semua percakapan akan musnah. Ini tidak dapat dibatalkan.', 'clearAllChats')">Hapus
                        semua obrolan</button>
                </div>
                <hr style="border: 0; border-top: 1px solid var(--glass-border); margin: 25px 0 15px 0;">
                <h3 style="margin-bottom: 15px; color: var(--accent-color);"><i class="fas fa-sliders-h"></i>
                    Konfigurasi Mesin AI</h3>

                <div style="margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <strong style="font-size: 0.9rem;">Max Tokens (Panjang Jawaban)</strong>
                        <span id="tokenValueDisplay"
                            style="font-size: 0.85rem; font-family: monospace; color: var(--accent-color); font-weight: bold;">4096</span>
                    </div>
                    <input type="range" id="maxTokensInput" min="512" max="8192" step="512"
                        value="4096" style="width: 100%; accent-color: var(--accent-color); cursor: pointer;"
                        oninput="document.getElementById('tokenValueDisplay').innerText = this.value">
                    <span style="font-size: 0.75rem; color: var(--text-secondary);">Atur batas maksimal kata.</span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong style="display: block; font-size: 0.9rem;">Enable Thinking Mode</strong>
                        <span style="font-size: 0.75rem; color: var(--text-secondary);">AI akan bernalar
                            mendalam.</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="enableThinkingInput">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                    <div>
                        <strong style="display: block; font-size: 0.9rem;">Web Search</strong>
                        <span style="font-size: 0.75rem; color: var(--text-secondary);">AI akan mencari info terbaru
                            dari internet sebelum menjawab.</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="enableWebSearchInput">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            <div id="tab-tentang" class="tab-pane">
                <h3 style="margin-bottom: 20px;">Tentang SAHAJA AI</h3>
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--glass-border); padding-bottom: 10px;">
                        <span>Syarat Penggunaan</span>
                        <button class="github-submit-btn"
                            style="background: transparent; color: var(--text-primary); border: 1px solid var(--glass-border); padding: 5px 15px;"
                            onclick="window.open('{{ route('terms') }}', '_blank')">Lihat</button>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--glass-border); padding-bottom: 10px;">
                        <span>Kebijakan Privasi</span>
                        <button class="github-submit-btn"
                            style="background: transparent; color: var(--text-primary); border: 1px solid var(--glass-border); padding: 5px 15px;"
                            onclick="window.open('{{ route('privacy') }}', '_blank')">Lihat</button>
                    </div>
                    <div
                        style="margin-top: 20px; text-align: center; color: var(--text-secondary); font-size: 0.85rem;">
                        Beta V 4.0<br>
                        Dibuat oleh: Faqih Hidayah
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL BANTUAN & FEEDBACK --}}
<div class="modal-overlay" id="helpModal" style="z-index: 100005;">
    <div class="modal-content"
        style="max-width: 550px; background: var(--sidebar-bg); padding: 25px; border-radius: 12px; border: 1px solid var(--glass-border);">
        <button class="modal-close" onclick="closeCustomModal('helpModal')"
            style="position: absolute; right: 15px; top: 15px;"><i class="fas fa-times"></i></button>
        <h2 style="font-size: 1.3rem; margin-bottom: 15px; color: var(--accent-color);"><i
                class="fas fa-question-circle"></i> Bantuan & Umpan Balik</h2>

        <div
            style="display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 1px solid var(--glass-border); padding-bottom: 10px;">
            <button onclick="switchHelpTab('faq')" class="github-submit-btn" id="btn-faq"
                style="flex: 1; padding: 8px;">FAQ Bantuan</button>
            <button onclick="switchHelpTab('feedback')" class="github-submit-btn" id="btn-feedback"
                style="flex: 1; padding: 8px; background: transparent; color: var(--text-primary); border: 1px solid var(--glass-border);">Kirim
                Masukan</button>
        </div>

        <div id="help-faq">
            <p style="margin-bottom: 10px;"><strong>Q: Apa itu Thinking Mode?</strong><br><span
                    style="color: var(--text-secondary); font-size: 0.9rem;">Fitur untuk memaksa AI bernalar mendalam
                    (Chain of Thought). Cocok untuk Coding & Logika.</span></p>
            <p style="margin-bottom: 10px;"><strong>Q: Mengapa kena Error 502 Bad Gateway?</strong><br><span
                    style="color: var(--text-secondary); font-size: 0.9rem;">Server NVIDIA kehabisan waktu memproses
                    karena AI berpikir terlalu lama. Matikan Thinking Mode untuk tugas naratif biasa.</span></p>
        </div>

        <div id="help-feedback" style="display: none;">
            <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 10px;">Masukan Anda membantu
                SAHAJA AI menjadi lebih baik.</p>
            <textarea id="feedbackText" class="github-input" placeholder="Tulis masukan, kritik, atau laporan bug..."
                style="width: 100%; height: 100px; margin-bottom: 15px; resize: none;"></textarea>
            <button onclick="submitFeedback()" class="github-submit-btn" style="width: 100%;">Kirim Sekarang</button>
        </div>
    </div>
</div>

{{-- MODAL SHARE --}}
<div class="modal-overlay" id="shareModal" style="z-index: 100005;">
    <div class="modal-content"
        style="max-width: 400px; background: var(--sidebar-bg); padding: 25px; border-radius: 12px; border: 1px solid var(--glass-border); text-align: center;">
        <button class="modal-close" onclick="closeCustomModal('shareModal')"
            style="position: absolute; right: 15px; top: 15px;"><i class="fas fa-times"></i></button>
        <h3 style="margin-bottom: 15px;"><i class="fas fa-share-alt" style="color: var(--accent-color);"></i> Bagikan
            Percakapan</h3>
        <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 15px;">Salin tautan di bawah ini
            untuk membagikan percakapan ini ke publik.</p>
        <div style="display: flex; gap: 10px;">
            <input type="text" id="shareLinkInput" class="github-input" readonly
                style="flex: 1; background: var(--glass-highlight);">
            <button class="github-submit-btn" onclick="copyShareLink()"><i class="far fa-copy"></i> Salin</button>
        </div>
    </div>
</div>

{{-- MODAL RENAME ROOM --}}
<div class="modal-overlay" id="renameRoomModal" style="z-index: 100005;">
    <div class="modal-content"
        style="max-width: 400px; background: var(--sidebar-bg); padding: 25px; border-radius: 12px; border: 1px solid var(--glass-border);">
        <button class="modal-close" onclick="closeCustomModal('renameRoomModal')"
            style="position: absolute; right: 15px; top: 15px;"><i class="fas fa-times"></i></button>
        <h2 style="font-size: 1.2rem; margin-bottom: 15px;"><i class="fas fa-pen"
                style="color: var(--accent-color);"></i> Ganti Nama</h2>
        <div class="github-input-group" style="display: flex; gap: 10px;">
            <input type="text" id="renameInput" class="github-input" placeholder="Nama percakapan baru...">
            <button id="btnConfirmRename" class="github-submit-btn" onclick="executeRename()">Simpan</button>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI DANGER ACTION --}}
<div class="modal-overlay" id="confirmDangerModal" style="z-index: 100005;">
    <div class="modal-content"
        style="max-width: 400px; background: var(--sidebar-bg); padding: 25px; border-radius: 12px; border: 1px solid var(--glass-border); text-align: center;">
        <button class="modal-close" onclick="closeCustomModal('confirmDangerModal')"
            style="position: absolute; right: 15px; top: 15px;"><i class="fas fa-times"></i></button>
        <div style="font-size: 3rem; color: var(--danger-color); margin-bottom: 10px;"><i
                class="fas fa-exclamation-triangle"></i></div>
        <h2 id="dangerModalTitle" style="font-size: 1.2rem; margin-bottom: 10px;">Konfirmasi</h2>
        <p id="dangerModalText" style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 20px;">Apakah
            Anda yakin ingin melanjutkan tindakan ini?</p>
        <div style="display: flex; gap: 10px; justify-content: center;">
            <button class="github-submit-btn"
                style="background: transparent; border: 1px solid var(--glass-border); color: var(--text-primary);"
                onclick="closeCustomModal('confirmDangerModal')">Batal</button>
            <button id="btnConfirmDanger" class="github-submit-btn" style="background: var(--danger-color);"
                onclick="executeDangerAction()">Ya, Hapus</button>
        </div>
    </div>
</div>

{{-- MODAL / PANEL DEEP RESEARCH (AGEN ALPHA) --}}
<div id="floatingResearchBtn"
    style="display: none; position: fixed; bottom: 80px; right: 20px; z-index: 99; background: var(--accent-gradient); color: white; padding: 12px 20px; border-radius: 30px; cursor: pointer; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);"
    onclick="toggleResearchPanel()">
    <i class="fas fa-atom fa-spin-slow"></i> Agen Alpha Aktif
</div>

<div id="researchPanel" class="research-panel"
    style="position: fixed; top: 0; right: -400px; width: 350px; height: 100vh; background: var(--sidebar-bg); border-left: 1px solid var(--glass-border); z-index: 100000; transition: 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); display: flex; flex-direction: column; box-shadow: -5px 0 25px rgba(0,0,0,0.5);">
    <div
        style="padding: 20px; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center; background: rgba(0,0,0,0.2);">
        <h3 style="margin: 0; color: #ef4444; font-size: 1.1rem;"><i class="fas fa-atom"></i> Agen Alpha</h3>
        <button onclick="toggleResearchPanel()"
            style="background: transparent; border: none; color: var(--text-secondary); cursor: pointer; font-size: 1.2rem;"><i
                class="fas fa-times"></i></button>
    </div>
    <div id="researchLogs"
        style="flex: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 12px; font-size: 0.85rem; font-family: monospace;">
    </div>
</div>

<style>
    #researchPanel.active {
        right: 0 !important;
    }

    .log-item {
        padding: 12px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.05);
        border-left: 3px solid #3b82f6;
        line-height: 1.5;
    }

    .log-item.processing {
        border-color: #f59e0b;
        color: #fcd34d;
        background: rgba(245, 158, 11, 0.1);
    }

    .log-item.success {
        border-color: #10b981;
        color: #6ee7b7;
        background: rgba(16, 185, 129, 0.1);
    }

    .log-item.error {
        border-color: #ef4444;
        color: #fca5a5;
        background: rgba(239, 68, 68, 0.1);
    }

    .log-item.info {
        border-color: #3b82f6;
        color: #93c5fd;
    }

    .fa-spin-slow {
        animation: fa-spin 3s infinite linear;
    }
</style>

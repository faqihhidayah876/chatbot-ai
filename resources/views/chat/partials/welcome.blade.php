<div class="welcome-screen" id="welcomeScreen" style="{{ count($chats) > 0 ? 'display: none;' : '' }}">
    <div class="welcome-logo-container">
        <div class="welcome-logo-glow"></div>
        <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="Logo SAHAJA AI" class="welcome-logo-img">
    </div>

    <div class="welcome-text">
        <h1 class="welcome-greeting">Halo, {{ explode(' ', Auth::user()->name ?? 'Teman')[0] }}</h1>
        <h2 class="welcome-subtext">Apa yang bisa saya bantu hari ini?</h2>
    </div>

    <div class="suggested-actions-grid">
        <button class="action-card" onclick="useShortcut('Bantu saya menganalisis dan mencari error dari link GitHub berikut: ')">
            <div class="action-card-icon" style="color: #a855f7;"><i class="fab fa-github"></i></div>
            <span class="action-card-text">Analisis kode dari repository GitHub secara mendalam</span>
        </button>

        <button class="action-card" onclick="useShortcut('Tolong buatkan contoh kodingan Laravel CRUD sederhana.')">
            <div class="action-card-icon" style="color: #3b82f6;"><i class="fas fa-code"></i></div>
            <span class="action-card-text">Buat kodingan Laravel, PHP, atau framework lainnya</span>
        </button>

        <button class="action-card" onclick="useShortcut('Buatkan saya ide judul project akhir website berbasis AI.')">
            <div class="action-card-icon" style="color: #eab308;"><i class="fas fa-lightbulb"></i></div>
            <span class="action-card-text">Eksplorasi ide project akhir & rancangan sistem</span>
        </button>

        <button class="action-card" onclick="useShortcut('Jelaskan materi kuliah Sistem Informasi tentang basis data relasional.')">
            <div class="action-card-icon" style="color: #10b981;"><i class="fas fa-book"></i></div>
            <span class="action-card-text">Rangkum materi perkuliahan & jurnal akademis</span>
        </button>
    </div>
</div>

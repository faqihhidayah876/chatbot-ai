<div class="welcome-screen" id="welcomeScreen" style="{{ count($chats) > 0 ? 'display: none;' : '' }}">
    {{-- Logo --}}
    <div class="welcome-logo-container">
        <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="Logo SAHAJA AI" class="welcome-logo-img">
    </div>

    {{-- Greeting --}}
    <div>
        <h1 class="welcome-greeting">Halo, {{ explode(' ', Auth::user()->name ?? 'Teman')[0] }}</h1>
        <p class="welcome-subtext">Ada yang bisa dibantu?</p>
    </div>

    {{-- Suggestion cards --}}
    <div class="suggested-actions-grid">
        <button class="action-card" onclick="useShortcut('Bantu saya menganalisis dan mencari error dari link GitHub berikut: ')">
            <div class="action-card-icon">
                <i class="fas fa-code-branch" style="font-size: 18px;"></i>
            </div>
            <span class="action-card-text">Analisis kode dari repository GitHub secara mendalam</span>
        </button>

        <button class="action-card" onclick="useShortcut('Tolong buatkan contoh kodingan Laravel CRUD sederhana.')">
            <div class="action-card-icon">
                <i class="fas fa-code" style="font-size: 18px;"></i>
            </div>
            <span class="action-card-text">Buat kodingan Laravel, PHP, atau framework lainnya</span>
        </button>

        <button class="action-card" onclick="useShortcut('Buatkan saya ide judul project akhir website berbasis AI.')">
            <div class="action-card-icon">
                <i class="fas fa-bolt" style="font-size: 18px;"></i>
            </div>
            <span class="action-card-text">Eksplorasi ide project akhir dan rancangan sistem</span>
        </button>

        <button class="action-card" onclick="useShortcut('Jelaskan materi kuliah Sistem Informasi tentang basis data relasional.')">
            <div class="action-card-icon">
                <i class="fas fa-book-open" style="font-size: 18px;"></i>
            </div>
            <span class="action-card-text">Rangkum materi perkuliahan dan jurnal akademis</span>
        </button>
    </div>
</div>

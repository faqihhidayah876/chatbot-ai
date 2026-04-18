<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SAHAJA AI</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🤖</text></svg>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --main-bg: #0a0e17; --sidebar-bg: rgba(15, 23, 42, 0.95);
            --glass-bg: rgba(30, 41, 59, 0.7); --glass-border: rgba(98, 160, 234, 0.15);
            --text-primary: #f1f5f9; --text-secondary: #94a3b8;
            --accent-color: #2563eb; --accent-gradient: linear-gradient(135deg, #2563eb, #06b6d4);
            --danger-color: #ef4444; --warning-color: #f59e0b; --success-color: #10b981;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: var(--main-bg); color: var(--text-primary); min-height: 100vh; overflow: hidden; display: flex; }

        body::before {
            content: ''; position: fixed; inset: 0;
            background: radial-gradient(circle at 50% 0%, rgba(37, 99, 235, 0.1) 0%, transparent 70%); z-index: -2;
        }

        /* ===== SIDEBAR KIRI ===== */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg); border-right: 1px solid var(--glass-border);
            display: flex; flex-direction: column; padding: 20px 0;
            z-index: 105; /* <--- UBAH ANGKA INI JADI 105 */
        }
        .sidebar-brand {
            padding: 0 20px 20px; border-bottom: 1px solid var(--glass-border);
            display: flex; align-items: center; gap: 10px; font-size: 1.3rem; font-weight: 700; color: var(--accent-color);
        }
        .sidebar-menu { list-style: none; margin-top: 20px; flex: 1; }
        .sidebar-menu li {
            padding: 15px 25px; cursor: pointer; color: var(--text-secondary);
            display: flex; align-items: center; gap: 15px; font-weight: 500; transition: 0.3s;
        }
        .sidebar-menu li:hover, .sidebar-menu li.active {
            background: rgba(37, 99, 235, 0.15); color: var(--text-primary); border-right: 4px solid var(--accent-color);
        }
        .sidebar-logout { padding: 20px; border-top: 1px solid var(--glass-border); }
        .logout-btn {
            width: 100%; background: rgba(239, 68, 68, 0.1); color: var(--danger-color); padding: 12px; border-radius: 10px;
            font-weight: 600; border: 1px solid rgba(239, 68, 68, 0.3); cursor: pointer; transition: 0.3s; display: flex; justify-content: center; gap: 8px;
        }
        .logout-btn:hover { background: var(--danger-color); color: white; }

        /* ===== KONTEN UTAMA ===== */
        .main-content { flex: 1; padding: 30px; overflow-y: auto; position: relative; }
        .header-title { font-size: 1.8rem; margin-bottom: 5px; color: white; }
        .header-subtitle { color: var(--text-secondary); font-size: 0.95rem; margin-bottom: 30px; }

        /* Alert */
        .alert { padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-weight: 500; }
        .alert-success { background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: var(--success-color); }

        /* Sistem Tab */
        .tab-pane { display: none; animation: fadeIn 0.3s ease; }
        .tab-pane.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card {
            background: var(--glass-bg); padding: 20px; border-radius: 20px; border: 1px solid var(--glass-border);
            position: relative; overflow: hidden; transition: 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); border-color: var(--accent-color); }
        .stat-icon { position: absolute; right: -10px; bottom: -10px; font-size: 4rem; opacity: 0.05; color: white; }
        .stat-number { font-size: 2.2rem; font-weight: 700; color: #f1f5f9; margin-bottom: 5px; }
        .stat-label { color: var(--accent-color); font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }

        /* Grafik Area */
        .chart-container {
            background: var(--glass-bg); padding: 20px; border-radius: 20px; border: 1px solid var(--glass-border);
            margin-bottom: 30px; height: 350px;
        }

        /* Table Area */
        .table-container { background: var(--glass-bg); padding: 25px; border-radius: 20px; border: 1px solid var(--glass-border); overflow-x: auto; }
        .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .table-header h2 { font-size: 1.2rem; color: var(--text-primary); }

        table { width: 100%; border-collapse: collapse; min-width: 800px; }
        th { text-align: left; padding: 15px; color: var(--text-secondary); border-bottom: 2px solid var(--glass-border); font-size: 0.8rem; text-transform: uppercase; }
        td { padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); color: var(--text-primary); vertical-align: middle; font-size: 0.9rem;}
        tr:hover td { background: rgba(255,255,255,0.02); }

        .user-info { display: flex; align-items: center; gap: 15px; }
        .user-avatar { width: 40px; height: 40px; background: var(--accent-gradient); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: bold; }
        .user-name { font-weight: 600; color: white; }
        .user-email { font-size: 0.8rem; color: var(--text-secondary); }

        .badge { padding: 4px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 600; }
        .badge-blue { background: rgba(37, 99, 235, 0.1); color: #60a5fa; border: 1px solid rgba(37, 99, 235, 0.3); }
        .badge-purple { background: rgba(168, 85, 247, 0.1); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.3); }

        .actions-group { display: flex; gap: 8px; flex-wrap: wrap; }
        .btn-action { padding: 8px 12px; border-radius: 8px; border: none; cursor: pointer; font-size: 0.8rem; display: flex; align-items: center; gap: 6px; transition: 0.2s; }
        .btn-warn { background: rgba(245, 158, 11, 0.1); color: var(--warning-color); border: 1px solid rgba(245, 158, 11, 0.3); }
        .btn-warn:hover { background: var(--warning-color); color: white; }
        .btn-danger { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); border: 1px solid rgba(239, 68, 68, 0.3); }
        .btn-danger:hover { background: var(--danger-color); color: white; }

        /* Khusus Feedback */
        .feedback-msg { background: rgba(0,0,0,0.2); padding: 10px 15px; border-radius: 8px; border-left: 3px solid var(--accent-color); font-style: italic; }

        /* ===== RESPONSIVE & TOGGLE SIDEBAR ===== */
        .mobile-header {
            display: none; /* Sembunyi di desktop */
            background: var(--sidebar-bg);
            padding: 15px 20px;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--glass-border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 104;
        }

        @media (max-width: 768px) {
            body { flex-direction: column; overflow-y: auto; }

            .sidebar {
                position: fixed;
                left: -260px; /* Sembunyi ke kiri */
                height: 100vh;
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 10px 0 30px rgba(0,0,0,0.5);
            }

            .sidebar.active {
                transform: translateX(260px);
            }

            .sidebar-overlay.active {
                display: block;
            }

            .mobile-header { display: flex; }
            .main-content { padding: 20px; width: 100%; }
            .stats-grid { grid-template-columns: 1fr 1fr; } /* 2 Kolom di HP */
            .header-title { font-size: 1.4rem; }
            .chart-container { height: 280px; }

            /* Sembunyikan kolom tabel yang kurang penting di HP agar tidak meluber */
            th:nth-child(3), td:nth-child(3),
            th:nth-child(4), td:nth-child(4) { display: none; }
        }

        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; } /* 1 Kolom di HP kecil */
        }
    </style>
</head>
<body>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileSidebar()"></div>

    <div class="sidebar" id="adminSidebar">
        <div class="sidebar-brand" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <span><i class="fas fa-satellite-dish"></i> Admin</span>
            <i class="fas fa-times" onclick="toggleMobileSidebar()" style="cursor: pointer; font-size: 1.2rem; color: var(--text-secondary);" id="mobileCloseBtn"></i>
        </div>
        <style> @media(min-width: 769px) { #mobileCloseBtn { display: none; } } </style>
        <ul class="sidebar-menu">
            <li class="active" onclick="switchAdminTab('tab-dashboard', this)">
                <i class="fas fa-chart-pie"></i> Dashboard & Analitik
            </li>
            <li onclick="switchAdminTab('tab-users', this)">
                <i class="fas fa-users"></i> Kelola Pengguna
            </li>
            <li onclick="switchAdminTab('tab-feedback', this)">
                <i class="fas fa-envelope-open-text"></i> Umpan Balik <span style="background: var(--danger-color); color: white; padding: 2px 6px; border-radius: 20px; font-size: 0.65rem;">{{ isset($feedbacks) ? count($feedbacks) : 0 }}</span>
            </li>
        </ul>
        <div class="sidebar-logout">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn"><i class="fas fa-power-off"></i> Logout Admin</button>
            </form>
        </div>
    </div>

    <div style="flex: 1; display: flex; flex-direction: column; overflow: hidden;">

        <div class="mobile-header">
            <div style="display: flex; align-items: center; gap: 15px;">
                <button onclick="toggleMobileSidebar()" style="background: none; border: none; color: white; font-size: 1.3rem; cursor: pointer;">
                    <i class="fas fa-bars"></i>
                </button>
                <span style="font-weight: 700; color: var(--accent-color); font-size: 1.1rem;">Dashboard Admin</span>
            </div>
            <div class="user-avatar" style="width: 35px; height: 35px; background: var(--accent-gradient); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white;">A</div>
        </div>

        <div class="main-content">
            <h1 class="header-title">Admin Control Panel</h1>
        <p class="header-subtitle">Kelola lalu lintas dan analitik SAHAJA AI dari satu layar.</p>

        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        <div id="tab-dashboard" class="tab-pane active">
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-users stat-icon"></i>
                    <div class="stat-number">{{ $totalUsers ?? 0 }}</div>
                    <div class="stat-label">Total Pengguna</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-comments stat-icon"></i>
                    <div class="stat-number">{{ $totalSessions ?? 0 }}</div>
                    <div class="stat-label">Ruang Obrolan</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-paper-plane stat-icon"></i>
                    <div class="stat-number">{{ $totalChats ?? 0 }}</div>
                    <div class="stat-label">Total Prompt Diproses</div>
                </div>
                <div class="stat-card" style="border-color: rgba(16, 185, 129, 0.3);">
                    <i class="fas fa-share-alt stat-icon"></i>
                    <div class="stat-number" style="color: var(--success-color);">{{ $totalShared ?? 0 }}</div>
                    <div class="stat-label" style="color: var(--success-color);">Share Link Publik</div>
                </div>
            </div>

            <div class="chart-container">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <div id="tab-users" class="tab-pane">
            <div class="table-container">
                <div class="table-header">
                    <h2><i class="fas fa-database"></i> Database Pengguna Terdaftar</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Riwayat Penggunaan</th>
                            <th>Aktivitas Terakhir</th>
                            <th>Tgl. Bergabung</th>
                            <th>Kontrol God Mode</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users ?? [] as $user)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                    <div>
                                        <div class="user-name">{{ $user->name }}</div>
                                        <div class="user-email">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px; flex-direction: column; align-items: flex-start;">
                                    <span class="badge badge-blue"><i class="fas fa-folder-open"></i> {{ $user->total_sessions ?? 0 }} Topik</span>
                                    <span class="badge badge-purple"><i class="fas fa-comment-dots"></i> {{ $user->total_chats ?? 0 }} Prompt</span>
                                </div>
                            </td>
                            <td>
                                <div style="color: {{ ($user->last_activity ?? 'Belum ada') == 'Belum ada aktivitas' ? 'var(--text-secondary)' : '#f1f5f9' }};">
                                    {{ $user->last_activity ?? 'Belum terdeteksi' }}
                                </div>
                            </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="actions-group">
                                    <form action="{{ route('admin.clearChats', $user->id) }}" method="POST" onsubmit="return confirm('Sapu bersih riwayat obrolan user {{ $user->name }}?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action btn-warn" title="Bersihkan Chat"><i class="fas fa-broom"></i> Hapus Chat</button>
                                    </form>
                                    <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" onsubmit="return confirm('PERINGATAN KRITIS!\nMusnahkan user {{ $user->name }} secara permanen?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action btn-danger" title="Hapus Akun"><i class="fas fa-skull-crossbones"></i> Banned</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 30px; color: var(--text-secondary);">Belum ada data pengguna.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div id="tab-feedback" class="tab-pane">
            <div class="table-container">
                <div class="table-header">
                    <h2><i class="fas fa-envelope-open-text"></i> Kotak Masuk Umpan Balik</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 25%;">Pengirim</th>
                            <th style="width: 55%;">Pesan Umpan Balik / Laporan Bug</th>
                            <th style="width: 20%;">Waktu Kirim</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feedbacks ?? [] as $fb)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar" style="width: 35px; height: 35px; font-size: 1rem;">{{ strtoupper(substr($fb->user->name ?? 'A', 0, 1)) }}</div>
                                    <div>
                                        <div class="user-name">{{ $fb->user->name ?? 'Anonim' }}</div>
                                        <div class="user-email">{{ $fb->user->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="feedback-msg">"{{ $fb->message }}"</div>
                            </td>
                            <td>{{ $fb->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 40px; color: var(--text-secondary);">
                                <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.3;"></i><br>
                                Belum ada umpan balik yang masuk dari pengguna.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    </div> <script>
        // Fungsi Pindah Tab
        function switchAdminTab(tabId, el) {
            // Sembunyikan semua tab
            document.querySelectorAll('.tab-pane').forEach(tab => tab.classList.remove('active'));
            // Tampilkan tab target
            document.getElementById(tabId).classList.add('active');

            // Ubah gaya tombol sidebar
            document.querySelectorAll('.sidebar-menu li').forEach(li => li.classList.remove('active'));
            el.classList.add('active');
        }

        // Inisialisasi Grafik Menggunakan Chart.js
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('growthChart').getContext('2d');

            // Mengambil data dari Backend PHP (Controller)
            const chartLabels = {!! isset($chartData) ? json_encode($chartData['labels']) : "['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']" !!};
            const chartValues = {!! isset($chartData) ? json_encode($chartData['data']) : '[0, 5, 12, 25, 45, 100]' !!};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Pertumbuhan Pengguna Terdaftar',
                        data: chartValues,
                        borderColor: '#2563eb', // Warna Biru SAHAJA
                        backgroundColor: 'rgba(37, 99, 235, 0.15)', // Latar biru transparan
                        borderWidth: 3,
                        pointBackgroundColor: '#06b6d4',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#06b6d4',
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        fill: true,
                        tension: 0.4 // Membuat garis melengkung elegan
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: { color: '#f1f5f9', font: { family: 'Poppins', size: 13 } }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false },
                            ticks: { color: '#94a3b8' }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { color: '#94a3b8' }
                        }
                    },
                    interaction: { mode: 'index', intersect: false }
                }
            });
        });
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        // Otomatis tutup sidebar di HP kalau menu diklik
        function switchAdminTab(tabId, el) {
            document.querySelectorAll('.tab-pane').forEach(tab => tab.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            document.querySelectorAll('.sidebar-menu li').forEach(li => li.classList.remove('active'));
            el.classList.add('active');

            // Jika di mobile (lebar layar < 768), tutup sidebar setelah pilih menu
            if (window.innerWidth <= 768) {
                toggleMobileSidebar();
            }
        }
    </script>
</body>
</html>

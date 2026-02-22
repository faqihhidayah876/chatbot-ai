<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>God Mode Admin - SAHAJA AI</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🤖</text></svg>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --main-bg: #0a0e17; --glass-bg: rgba(30, 41, 59, 0.7); --glass-border: rgba(98, 160, 234, 0.15);
            --text-primary: #f1f5f9; --text-secondary: #94a3b8;
            --accent-color: #2563eb; --accent-gradient: linear-gradient(135deg, #2563eb, #06b6d4);
            --danger-color: #ef4444; --warning-color: #f59e0b; --success-color: #10b981;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: var(--main-bg); color: var(--text-primary); min-height: 100vh; padding: 20px; }

        body::before {
            content: ''; position: fixed; inset: 0;
            background: radial-gradient(circle at 50% 0%, rgba(37, 99, 235, 0.1) 0%, transparent 70%); z-index: -2;
        }

        .container { max-width: 1300px; margin: 0 auto; }

        /* Header */
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding: 20px 30px; background: var(--glass-bg); border-radius: 20px; border: 1px solid var(--glass-border); backdrop-filter: blur(10px); }
        .title h1 { font-size: 1.8rem; background: var(--accent-gradient); -webkit-background-clip: text; color: transparent; display: flex; align-items: center; gap: 10px; }
        .logout-btn { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); padding: 10px 20px; border-radius: 10px; font-weight: 600; border: 1px solid rgba(239, 68, 68, 0.3); cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 8px; }
        .logout-btn:hover { background: var(--danger-color); color: white; }

        /* Alert System */
        .alert { padding: 15px 20px; border-radius: 12px; margin-bottom: 30px; display: flex; align-items: center; gap: 10px; font-weight: 500; animation: slideDown 0.5s ease; }
        .alert-success { background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: var(--success-color); }
        @keyframes slideDown { from{opacity:0; transform:translateY(-10px);} to{opacity:1; transform:translateY(0);} }

        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: var(--glass-bg); padding: 25px; border-radius: 20px; border: 1px solid var(--glass-border); position: relative; overflow: hidden; transition: 0.3s; }
        .stat-card:hover { transform: translateY(-5px); border-color: var(--accent-color); box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .stat-icon { position: absolute; right: -10px; bottom: -10px; font-size: 5rem; opacity: 0.05; color: white; }
        .stat-number { font-size: 2.5rem; font-weight: 700; color: #f1f5f9; margin-bottom: 5px; }
        .stat-label { color: var(--accent-color); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }

        /* Table Area */
        .table-container { background: var(--glass-bg); padding: 30px; border-radius: 20px; border: 1px solid var(--glass-border); overflow-x: auto; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .table-header h2 { font-size: 1.3rem; color: var(--text-primary); }

        table { width: 100%; border-collapse: collapse; min-width: 900px; }
        th { text-align: left; padding: 15px; color: var(--text-secondary); border-bottom: 2px solid var(--glass-border); font-weight: 600; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; }
        td { padding: 18px 15px; border-bottom: 1px solid rgba(255,255,255,0.05); color: var(--text-primary); vertical-align: middle; }
        tr:hover td { background: rgba(255,255,255,0.02); }
        tr:last-child td { border-bottom: none; }

        /* Badges & Elements */
        .user-info { display: flex; align-items: center; gap: 15px; }
        .user-avatar { width: 40px; height: 40px; background: var(--accent-gradient); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem; }
        .user-name { font-weight: 600; color: white; }
        .user-email { font-size: 0.8rem; color: var(--text-secondary); }

        .badge { padding: 4px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 600; }
        .badge-blue { background: rgba(37, 99, 235, 0.1); color: #60a5fa; border: 1px solid rgba(37, 99, 235, 0.3); }
        .badge-purple { background: rgba(168, 85, 247, 0.1); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.3); }

        /* Actions */
        .actions-group { display: flex; gap: 8px; flex-wrap: wrap; }
        .btn-action { padding: 8px 12px; border-radius: 8px; border: none; cursor: pointer; font-size: 0.8rem; font-weight: 500; display: flex; align-items: center; gap: 6px; transition: 0.2s; text-decoration: none; }

        .btn-warn { background: rgba(245, 158, 11, 0.1); color: var(--warning-color); border: 1px solid rgba(245, 158, 11, 0.3); }
        .btn-warn:hover { background: var(--warning-color); color: white; }

        .btn-danger { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); border: 1px solid rgba(239, 68, 68, 0.3); }
        .btn-danger:hover { background: var(--danger-color); color: white; }
    </style>
</head>
<body>
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="header">
            <div class="title">
                <h1><i class="fas fa-satellite-dish"></i>Dahsboard Admin</h1>
                <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 5px;">Halaman kelola seluruh user di SAHAJA AI</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn"><i class="fas fa-power-off"></i>Logout</button>
            </form>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-users stat-icon"></i>
                <div class="stat-number">{{ $totalUsers }}</div>
                <div class="stat-label">Total Pengguna</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-comments stat-icon"></i>
                <div class="stat-number">{{ $totalSessions }}</div>
                <div class="stat-label">Ruang Obrolan</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-paper-plane stat-icon"></i>
                <div class="stat-number">{{ $totalChats }}</div>
                <div class="stat-label">Total Teks Terkirim</div>
            </div>
            <div class="stat-card" style="border-color: rgba(16, 185, 129, 0.3);">
                <i class="fas fa-share-alt stat-icon"></i>
                <div class="stat-number" style="color: var(--success-color);">{{ $totalShared }}</div>
                <div class="stat-label" style="color: var(--success-color);">Total Share Link</div>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h2>Database Pengguna</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Riwayat Chat</th>
                        <th>Aktivitas Terakhir</th>
                        <th>Tgl. Bergabung</th>
                        <th>Kontrol</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
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
                                <span class="badge badge-blue"><i class="fas fa-folder-open"></i> {{ $user->total_sessions }} Topik Sesi</span>
                                <span class="badge badge-purple"><i class="fas fa-comment-dots"></i> {{ $user->total_chats }} Pesan Diproses</span>
                            </div>
                        </td>
                        <td>
                            <div style="color: {{ $user->last_activity == 'Belum ada aktivitas' ? 'var(--text-secondary)' : '#f1f5f9' }}; font-size: 0.9rem;">
                                {{ $user->last_activity }}
                            </div>
                        </td>
                        <td style="font-size: 0.9rem; color: var(--text-secondary);">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td>
                            <div class="actions-group">
                                <form action="{{ route('admin.clearChats', $user->id) }}" method="POST" onsubmit="return confirm('Sapu bersih semua riwayat obrolan user {{ $user->name }}? (Akun tidak dihapus)');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-warn" title="Bersihkan Riwayat Chat">
                                        <i class="fas fa-broom"></i> Bersihkan
                                    </button>
                                </form>

                                <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" onsubmit="return confirm('PERINGATAN KRITIS!\nYakin ingin memusnahkan user {{ $user->name }} secara permanen beserta semua datanya?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-danger" title="Hapus Akun Permanen">
                                        <i class="fas fa-skull-crossbones"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-secondary);">
                            <i class="fas fa-ghost" style="font-size: 3rem; margin-bottom: 10px; opacity: 0.5;"></i><br>
                            Belum ada pengguna biasa yang terdaftar di sistem.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SAHAJA AI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --main-bg: #0a0e17;
            --glass-bg: rgba(30, 41, 59, 0.7);
            --glass-border: rgba(98, 160, 234, 0.15);
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --accent-gradient: linear-gradient(135deg, #2563eb, #06b6d4);
            --danger-color: #ef4444;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: var(--main-bg); color: var(--text-primary); min-height: 100vh; padding: 20px; }

        /* Layout */
        .container { max-width: 1200px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; padding: 20px; background: var(--glass-bg); border-radius: 20px; border: 1px solid var(--glass-border); backdrop-filter: blur(10px); }
        .title h1 { font-size: 1.5rem; background: var(--accent-gradient); -webkit-background-clip: text; color: transparent; }
        .logout-btn { background: #dc2626; color: white; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-size: 0.9rem; border: none; cursor: pointer; transition: 0.3s; }
        .logout-btn:hover { background: #b91c1c; }

        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: var(--glass-bg); padding: 25px; border-radius: 20px; border: 1px solid var(--glass-border); text-align: center; transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-5px); border-color: #2563eb; }
        .stat-number { font-size: 2.5rem; font-weight: 700; color: #60a5fa; margin-bottom: 5px; }
        .stat-label { color: var(--text-secondary); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; }

        /* Table */
        .table-container { background: var(--glass-bg); padding: 25px; border-radius: 20px; border: 1px solid var(--glass-border); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 600px; }
        th { text-align: left; padding: 15px; color: var(--text-secondary); border-bottom: 1px solid var(--glass-border); font-weight: 600; }
        td { padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); color: var(--text-primary); }
        tr:last-child td { border-bottom: none; }
        .action-btn { background: rgba(239, 68, 68, 0.2); color: var(--danger-color); padding: 8px 12px; border-radius: 8px; border: 1px solid rgba(239, 68, 68, 0.3); cursor: pointer; transition: 0.2s; }
        .action-btn:hover { background: var(--danger-color); color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">
                <h1>Dashboard Admin</h1>
                <p style="color: var(--text-secondary); font-size: 0.9rem;">SAHAJA AI Monitoring</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $totalUsers }}</div>
                <div class="stat-label">Total User</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $totalSessions }}</div>
                <div class="stat-label">Total Sesi Chat</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $totalChats }}</div>
                <div class="stat-label">Total Pesan Terkirim</div>
            </div>
        </div>

        <div class="table-container">
            <h2 style="margin-bottom: 20px; font-size: 1.2rem;">Daftar Pengguna</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Tanggal Daftar</th>
                        <th>Aktivitas (Chat)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>#{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>{{ $user->chat_count }} Pesan</td>
                        <td>
                            <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini? Semua chat mereka akan hilang.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

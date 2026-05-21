<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    <title>Login - SAHAJA AI</title>
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet">
    <link rel="icon" type="image/png" href="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-primary: #080c14;
            --surface: rgba(18, 26, 44, 0.8);
            --surface-border: rgba(74, 130, 220, 0.15);
            --text-primary: #e8edf4;
            --text-secondary: #8899b4;
            --accent: #3b82f6;
            --accent-gradient: linear-gradient(135deg, #3b82f6 0%, #22c5e0 100%);
            --input-bg: rgba(18, 26, 44, 0.7);
            --error: #ef4444;
            --radius-lg: 20px;
            --radius-full: 9999px;
            --transition: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content: '';
            position: fixed; inset:0;
            background: radial-gradient(circle at 20% 30%, rgba(37,99,235,0.15) 0%, transparent 60%),
                        radial-gradient(circle at 80% 70%, rgba(34,197,224,0.12) 0%, transparent 60%);
            z-index: -1;
        }
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: var(--surface);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid var(--surface-border);
            border-radius: var(--radius-lg);
            padding: 2.5rem 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
            animation: fadeInUp 0.5s ease;
        }
        @keyframes fadeInUp {
            from { opacity:0; transform: translateY(20px); }
            to { opacity:1; transform: translateY(0); }
        }
        .logo-img {
            width: 90px;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
            margin-bottom: 1.5rem;
        }
        h2 {
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 0.25rem;
        }
        .subtitle {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }
        .form-group {
            text-align: left;
            margin-bottom: 1.2rem;
        }
        label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 0.4rem;
            letter-spacing: 0.02em;
        }
        input {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            background: var(--input-bg);
            border: 1px solid var(--surface-border);
            color: var(--text-primary);
            font-size: 0.95rem;
            outline: none;
            transition: var(--transition);
        }
        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        }
        .btn {
            width: 100%;
            padding: 0.85rem;
            border-radius: var(--radius-full);
            background: var(--accent-gradient);
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
            margin-top: 1rem;
            transition: var(--transition);
            box-shadow: 0 4px 12px rgba(59,130,246,0.3);
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59,130,246,0.5);
        }
        .footer-link {
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }
        .footer-link a {
            color: #62a0ea;
            text-decoration: none;
            font-weight: 500;
        }
        .footer-link a:hover {
            text-decoration: underline;
        }
        .error-msg {
            color: var(--error);
            font-size: 0.78rem;
            margin-top: 0.3rem;
        }
        @media (max-width: 480px) {
            .auth-card { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <img src="https://i.ibb.co.com/wrrG06ds/Logo-SAHAJA-AI.png" alt="Logo SAHAJA AI" class="logo-img">
        <h2>Selamat Datang Kembali</h2>
        <p class="subtitle">Masuk untuk melanjutkan ke SAHAJA AI</p>
        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="user@email.com" required value="{{ old('email') }}">
                @error('email') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn">Masuk</button>
        </form>
        <div class="footer-link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
        </div>
        <div class="footer-link" style="margin-top:0.5rem;">
            <a href="{{ route('home') }}"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>

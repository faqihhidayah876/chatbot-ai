<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    <title>Daftar - SAHAJA AI</title>
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --main-bg: #0a0e17;
            --glass-bg: rgba(15, 27, 45, 0.7);
            --glass-border: rgba(98, 160, 234, 0.15);
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --accent-gradient: linear-gradient(135deg, #2563eb, #06b6d4);
            --input-bg: rgba(30, 41, 59, 0.5);
            --error: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: var(--main-bg);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-y: auto; /* PENTING: agar body bisa di-scroll */
            padding: 20px;
        }

        body::before {
            content: '';
            position: fixed; /* fixed, bukan absolute, agar background tetap saat scroll */
            inset: 0;
            background: radial-gradient(circle at 80% 20%, rgba(6, 182, 212, 0.15) 0%, transparent 60%),
                        radial-gradient(circle at 20% 80%, rgba(37, 99, 235, 0.12) 0%, transparent 60%);
            z-index: -1;
            pointer-events: none;
        }

        .auth-card {
            width: 100%;
            max-width: 450px;
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 2.5rem;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            margin: auto; /* untuk centering vertical saat di-scroll */
        }

        .logo {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            color: transparent;
        }

        h2 {
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }

        p {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.2rem;
            text-align: left;
        }

        label {
            display: block;
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        input {
            width: 100%;
            padding: 0.8rem 1rem;
            border-radius: 12px;
            background: var(--input-bg);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            font-size: 0.95rem;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }

        .btn {
            width: 100%;
            padding: 0.9rem;
            border-radius: 12px;
            font-weight: 600;
            background: var(--accent-gradient);
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 1rem;
            transition: 0.3s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.4);
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
            font-size: 0.8rem;
            margin-top: 0.3rem;
        }

        /* RESPONSIVE UNTUK HANDPHONE */
        @media (max-width: 480px) {
            body {
                padding: 16px;
                display: block; /* ganti flex jadi block agar bisa scroll natural */
            }

            .auth-card {
                padding: 1.8rem 1.5rem;
                margin: 10px auto;
                width: 100%;
            }

            .logo {
                font-size: 2.5rem;
            }

            h2 {
                font-size: 1.5rem;
            }

            .form-group {
                margin-bottom: 1rem;
            }

            input {
                padding: 0.7rem 0.9rem;
            }

            .btn {
                padding: 0.8rem;
            }

            .footer-link {
                margin-top: 1.2rem;
            }
        }

        /* Untuk layar sangat kecil (misal: iPhone SE) */
        @media (max-width: 360px) {
            .auth-card {
                padding: 1.5rem 1.2rem;
            }

            .logo {
                font-size: 2.2rem;
                margin-bottom: 0.5rem;
            }

            h2 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="logo"><i class="fas fa-user-plus"></i></div>
        <h2>Buat Akun Baru</h2>
        <p>Bergabung dengan SAHAJA AI sekarang</p>

        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" placeholder="Nama Kamu" required value="{{ old('name') }}">
                @error('name') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="contoh@email.com" required value="{{ old('email') }}">
                @error('email') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Minimal 8 karakter" required>
                @error('password') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" placeholder="Ulangi password" required>
            </div>

            <button type="submit" class="btn">Daftar</button>
        </form>

        <div class="footer-link">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk Disini</a>
        </div>
        <div class="footer-link" style="margin-top: 0.5rem; margin-bottom: 0.2rem;">
            <a href="{{ route('home') }}"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>

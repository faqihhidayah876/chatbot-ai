<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline — SAHAJA AI</title>
    <link rel="icon" type="image/png" href="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-base: #0B0D12;
            --text-primary: #E8EAED;
            --text-secondary: #9AA0A6;
            --text-tertiary: #5F6368;
            --accent: #3B82F6;
            --accent-hover: #60A5FA;
            --radius-md: 10px;
            --radius-lg: 14px;
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: var(--font-sans);
            background: var(--bg-base);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            text-align: center;
        }
        .offline-container {
            max-width: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
        .offline-icon {
            font-size: 64px;
            color: var(--text-tertiary);
            opacity: 0.4;
            margin-bottom: 8px;
        }
        .offline-title {
            font-size: 24px;
            font-weight: 600;
            letter-spacing: -0.02em;
            color: var(--text-primary);
        }
        .offline-text {
            font-size: 15px;
            color: var(--text-secondary);
            line-height: 1.6;
            max-width: 320px;
        }
        .offline-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 500;
            font-family: var(--font-sans);
            cursor: pointer;
            text-decoration: none;
            transition: background 200ms cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 8px;
        }
        .offline-btn:hover {
            background: var(--accent-hover);
        }
    </style>
</head>
<body>
    <div class="offline-container">
        <div class="offline-icon">
            <i class="fas fa-wifi"></i>
        </div>
        <h1 class="offline-title">Anda Sedang Offline</h1>
        <p class="offline-text">
            Koneksi internet Anda terputus. Periksa koneksi dan coba lagi.
        </p>
        <button class="offline-btn" onclick="window.location.reload()">
            <i class="fas fa-rotate"></i> Coba Lagi
        </button>
    </div>
</body>
</html>

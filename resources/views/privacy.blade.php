<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Kebijakan Privasi - SAHAJA AI</title>
    <link rel="icon" type="image/png" href="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #0a0e17;
            color: #f1f5f9;
            line-height: 1.6;
            padding: 40px 10%;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(15, 23, 42, 0.8);
            padding: 40px;
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.1);
        }

        h1 {
            color: #10b981;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        h2 {
            margin-top: 30px;
            color: #06b6d4;
            font-size: 1.3rem;
            border-bottom: 1px solid rgba(6, 182, 212, 0.3);
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        h3 {
            color: #34d399;
            margin-top: 20px;
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 12px;
            text-align: justify;
        }

        ul, ol {
            padding-left: 25px;
            margin: 10px 0;
        }

        li {
            margin-bottom: 8px;
        }

        .highlight {
            background: rgba(16, 185, 129, 0.1);
            border-left: 4px solid #10b981;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }

        .contact-box {
            background: rgba(6, 182, 212, 0.1);
            border: 1px solid rgba(6, 182, 212, 0.3);
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }

        .last-updated {
            color: #94a3b8;
            font-style: italic;
            margin-bottom: 30px;
            font-size: 0.9rem;
        }

        /* RESPONSIVE UNTUK HP */
        @media screen and (max-width: 768px) {
            body {
                padding: 16px; /* Padding kecil di HP */
            }

            .container {
                padding: 24px 20px; /* Padding lebih kecil di container */
                border-radius: 12px;
                width: 100%;
            }

            h1 {
                font-size: 1.5rem; /* Judul lebih kecil di HP */
            }

            h2 {
                font-size: 1.15rem;
                margin-top: 24px;
            }

            h3 {
                font-size: 1rem;
            }

            p, li {
                font-size: 0.95rem;
                line-height: 1.7;
                text-align: left; /* Rata kiri lebih enak di HP */
            }

            ul, ol {
                padding-left: 20px;
            }

            .highlight, .contact-box {
                padding: 12px;
                margin: 15px 0;
            }
        }

        /* UNTUK HP YANG SANGAT KECIL */
        @media screen and (max-width: 480px) {
            body {
                padding: 12px;
            }

            .container {
                padding: 20px 16px;
                border-radius: 10px;
            }

            h1 {
                font-size: 1.3rem;
            }

            h2 {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><center>Kebijakan Privasi</center></h1>
        <p class="last-updated">Terakhir diperbarui: 16 April 2026</p>

        <div class="highlight">
            <strong>Penting:</strong> Dengan menggunakan SAHAJA AI, Anda mempercayakan informasi Anda kepada kami. Kami berkomitmen untuk melindungi privasi Anda dan menjaga transparansi penuh mengenai penggunaan data Anda.
        </div>

        <h2>1. Informasi yang Kami Kumpulkan</h2>

        <h3>1.1 Informasi Akun</h3>
        <p>Saat Anda mendaftar di SAHAJA AI, kami mengumpulkan:</p>
        <ul>
            <li><strong>Identitas:</strong> Nama lengkap atau nama pengguna</li>
            <li><strong>Kontak:</strong> Alamat email aktif</li>
            <li><strong>Profil:</strong> Foto profil/avatar (jika diunggah)</li>
            <li><strong>Kredensial:</strong> Password yang di-hash dengan aman</li>
        </ul>

        <h3>1.2 Data Penggunaan</h3>
        <p>Selama penggunaan layanan, kami mengumpulkan:</p>
        <ul>
            <li><strong>Riwayat Percakapan:</strong> Semua pesan yang Anda kirimkan ke AI dan respons yang diterima</li>
            <li><strong>Konteks Dokumen:</strong> Tautan GitHub, file dokumen, atau konten yang Anda bagikan untuk diproses AI</li>
            <li><strong>Log Teknis:</strong> Alamat IP, jenis browser, perangkat yang digunakan, dan timestamp akses</li>
            <li><strong>Preferensi:</strong> Pengaturan tema, format output yang disukai, dan konfigurasi personalisasi lainnya</li>
        </ul>

        <h3>1.3 Informasi dari Pihak Ketiga</h3>
        <p>Jika Anda menghubungkan akun Google atau platform lain untuk login, kami menerima:</p>
        <ul>
            <li>Email verifikasi dari provider OAuth</li>
            <li>Profil dasar yang Anda izinkan untuk dibagikan</li>
        </ul>

        <h2>2. Cara Kami Menggunakan Data Anda</h2>
        <p>Data yang dikumpulkan digunakan untuk:</p>
        <ol>
            <li><strong>Penyediaan Layanan:</strong> Memproses prompt Anda ke model AI (NVIDIA/Gemini) dan mengembalikan respons yang relevan</li>
            <li><strong>Pengalaman Personal:</strong> Menyimpan riwayat chat agar Anda dapat melanjutkan percakapan kapan saja</li>
            <li><strong>Analisis Dokumen:</strong> Membaca dan menganalisis tautan GitHub atau dokumen yang Anda berikan untuk konteks percakapan</li>
            <li><strong>Perbaikan Layanan:</strong> Menganalisis pola penggunaan (secara anonim) untuk meningkatkan performa dan fitur</li>
            <li><strong>Keamanan:</strong> Mendeteksi aktivitas mencurigakan, spam, atau penyalahgunaan platform</li>
            <li><strong>Komunikasi:</strong> Mengirimkan notifikasi penting, update fitur, atau informasi keamanan akun</li>
        </ol>

        <h2>3. Penyimpanan dan Keamanan Data</h2>

        <h3>3.1 Infrastruktur Penyimpanan</h3>
        <ul>
            <li>Database utama disimpan di <strong>Alwaysdata</strong> dengan enkripsi TLS/SSL</li>
            <li>Password di-hash menggunakan algoritma Bcrypt dengan salt</li>
            <li>Riwayat chat disimpan dalam format terenkripsi di database</li>
            <li>Backup data dilakukan secara berkala dengan akses terbatas</li>
        </ul>

        <h3>3.2 Retensi Data</h3>
        <ul>
            <li><strong>Data Aktif:</strong> Disimpan selama akun Anda aktif</li>
            <li><strong>Data Terhapus:</strong> Setelah Anda menghapus chat atau akun, data dihapus secara permanen dalam waktu 30 hari dari sistem backup</li>
            <li><strong>Log Server:</strong> Dihapus otomatis setelah 90 hari kecuali terkait investigasi keamanan</li>
        </ul>

        <h3>3.3 Keamanan Teknis</h3>
        <p>Kami menerapkan standar keamanan industri termasuk:</p>
        <ul>
            <li>Enkripsi data saat transit (HTTPS/TLS 1.3)</li>
            <li>Enkripsi data saat disimpan (AES-256)</li>
            <li>Autentikasi dua faktor (2FA) opsional untuk akun</li>
            <li>Audit keamanan berkala dan penetration testing</li>
        </ul>

        <h2>4. Berbagi Data dengan Pihak Ketiga</h2>

        <h3>4.1 Provider AI</h3>
        <p>Untuk memberikan respons AI yang berkualitas, kami mengirimkan prompt Anda ke:</p>
        <ul>
            <li><strong>NVIDIA AI / Gemini API:</strong> Hanya konten prompt yang dikirim, tanpa data profil pribadi</li>
            <li>Data ini tidak disimpan oleh provider AI untuk training model tanpa izin eksplisit Anda</li>
        </ul>

        <h3>4.2 Layanan Pendukung</h3>
        <p>Kami menggunakan layanan pihak ketiga untuk:</p>
        <ul>
            <li>Hosting database (Alwaysdata)</li>
            <li>Analytics anonim (jika ada)</li>
            <li>Layanan email untuk notifikasi sistem</li>
        </ul>

        <h3>4.3 Kewajiban Hukum</h3>
        <p>Kami hanya akan membagikan data jika diwajibkan oleh:</p>
        <ul>
            <li>Perintah pengadilan atau proses hukum yang sah</li>
            <li>Permintaan resmi dari aparat penegak hukum</li>
            <li>Perlindungan terhadap hak, properti, atau keselamatan kami atau pengguna lain</li>
        </ul>

        <div class="highlight">
            <strong>Penting:</strong> Kami <em>tidak pernah</em> menjual data pribadi Anda kepada pengiklan atau pihak ketiga untuk tujuan komersial.
        </div>

        <h2>5. Cookies dan Teknologi Pelacakan</h2>
        <p>Kami menggunakan cookies untuk:</p>
        <ul>
            <li>Menjaga sesi login Anda (session cookies)</li>
            <li>Mengingat preferensi tema dan pengaturan (persistent cookies, durasi 1 tahun)</li>
            <li>Analisis traffic dasar untuk perbaikan performa</li>
        </ul>
        <p>Anda dapat mengatur browser untuk menolak cookies, namun beberapa fitur mungkin tidak berfungsi optimal.</p>

        <h2>6. Hak Anda sebagai Pengguna</h2>
        <p>Berdasarkan regulasi privasi data (GDPR, UU PDP Indonesia), Anda memiliki hak:</p>

        <h3>6.1 Akses dan Portabilitas</h3>
        <ul>
            <li>Melihat semua data pribadi yang kami miliki tentang Anda</li>
            <li>Mengunduh data dalam format yang dapat dibaca (JSON/CSV)</li>
        </ul>

        <h3>6.2 Koreksi</h3>
        <ul>
            <li>Memperbarui informasi profil (nama, email, avatar)</li>
            <li>Mengubah password kapan saja</li>
        </ul>

        <h3>6.3 Penghapusan (Right to be Forgotten)</h3>
        <ul>
            <li>Menghapus riwayat chat tertentu atau seluruhnya melalui menu Pengaturan</li>
            <li>Menghapus akun secara permanen beserta seluruh data terkait</li>
            <li>Penghapusan akun akan menghapus: profil, semua chat, preferensi, dan data analytics personal</li>
        </ul>

        <h3>6.4 Pembatasan Pemrosesan</h3>
        <ul>
            <li>Menonaktifkan penyimpanan riwayat chat untuk percakapan mendatang</li>
            <li>Menghentikan newsletter atau notifikasi marketing (jika ada)</li>
        </ul>

        <h2>7. Perlindungan Data Anak</h2>
        <p>SAHAJA AI tidak ditujukan untuk pengguna di bawah 13 tahun. Jika kami menemukan akun yang didaftarkan oleh anak di bawah 13 tahun, kami akan menghapus akun tersebut segera setelah diketahui. Jika Anda adalah orang tua/wali yang mengetahui anak Anda telah memberikan data pribadi, silakan hubungi kami untuk penghapusan data.</p>

        <h2>8. Transfer Data Internasional</h2>
        <p>Mengingat kami menggunakan layanan AI dari provider global (NVIDIA, Google/Gemini), data Anda mungkin diproses di server internasional yang berlokasi di:</p>
        <ul>
            <li>United States (untuk NVIDIA AI dan Gemini)</li>
            <li>European Union (backup dan redundansi)</li>
        </ul>
        <p>Kami memastikan bahwa transfer data dilakukan dengan perlindungan sesuai standar kontrak (SCC) yang disetujui Uni Eropa.</p>

        <h2>9. Perubahan Kebijakan Privasi</h2>
        <p>Kami dapat memperbarui kebijakan ini sewaktu-waktu. Perubahan material akan dinotifikaskan melalui:</p>
        <ul>
            <li>Email ke alamat terdaftar Anda</li>
            <li>Notifikasi dalam aplikasi saat login</li>
            <li>Pengumuman di halaman ini dengan tanggal update yang jelas</li>
        </ul>
        <p>Penggunaan berkelanjutan setelah perubahan berarti Anda menerima kebijakan yang telah diperbarui.</p>

        <h2>10. Kontak dan Pengaduan</h2>
        <div class="contact-box">
            <p><strong>Tim Privasi SAHAJA AI</strong></p>
            <p>Jika Anda memiliki pertanyaan, permintaan hak privasi, atau ingin mengajukan pengaduan:</p>
            <ul>
                <li>Email: vaaaqee@gmail.com</li>
                <li>Alamat: Unspecified</li>
                <li>Telepon: Unspecified</li>
            </ul>
            <p>Respons akan diberikan dalam waktu maksimal 30 hari kerja sesuai regulasi yang berlaku.</p>
        </div>

        <p style="margin-top: 40px; text-align: center; color: #64748b; font-size: 0.9rem;">
            © 2026 SAHAJA AI. Seluruh hak dilindungi undang-undang.
        </p>
    </div>
</body>
</html>

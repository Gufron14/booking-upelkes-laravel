<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Syarat & Ketentuan Booking - UPELKES Jawa Barat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">
        
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>

<body class="bg-light">
    @include('components.navbar')
    <div class="container my-5">
        <div class="card shadow-sm">
            <div class="card-body p-5">
                <h2 class="text-center mb-4">📝 Syarat & Ketentuan Booking</h2>
                <h5 class="text-center text-secondary mb-5">Unit Pelayanan Kesehatan (UPELKES) Jawa Barat</h5>

                <!-- 1. Ketentuan Umum -->
                <h4 class="mb-3">1. Ketentuan Umum</h4>
                <ul>
                    <li>Booking hanya diperuntukkan bagi <strong>instansi pemerintahan</strong> (pusat maupun daerah)
                        yang akan melaksanakan kegiatan resmi seperti pelatihan, seminar, bimbingan teknis, workshop,
                        atau sejenisnya.</li>
                    <li>Pemesanan wajib dilakukan oleh <strong>perwakilan resmi instansi</strong>.</li>
                </ul>

                <!-- 2. Prosedur Pendaftaran -->
                <h4 class="mt-4 mb-3">2. Prosedur Pendaftaran</h4>
                <ol>
                    <li>Pendaftaran dilakukan secara online melalui sistem booking pada website resmi UPELKES Jawa
                        Barat.</li>
                    <li>Instansi diwajibkan mengisi data lengkap:
                        <ul>
                            <li>Nama instansi</li>
                            <li>Jenis kegiatan</li>
                            <li>Tanggal kegiatan</li>
                            <li>Jumlah peserta</li>
                            <li>Kebutuhan kamar dan/atau ruangan</li>
                        </ul>
                    </li>
                    <li>Admin akan memverifikasi data dan ketersediaan fasilitas.</li>
                </ol>

                <!-- 3. Persyaratan Booking -->
                <h4 class="mt-4 mb-3">3. Persyaratan Booking</h4>
                <ul>
                    <li>Melampirkan dokumen:
                        <ul>
                            <li>Surat Permohonan Pemakaian Fasilitas (PDF)</li>
                            <li>Surat Tugas/Surat Pengantar dari instansi</li>
                            <li>Rencana Jadwal Kegiatan (jika ada)</li>
                        </ul>
                    </li>
                    <li>Pendaftaran minimal dilakukan <strong>H-7 hari</strong> sebelum kegiatan.</li>
                </ul>

                <!-- 4. Ketentuan Pembayaran -->
                <h4 class="mt-4 mb-3">4. Ketentuan Pembayaran</h4>
                <ul>
                    <li>Setelah verifikasi, admin akan mengirimkan rincian biaya sewa kamar dan/atau ruangan.</li>
                    <li>Pembayaran dilakukan ke rekening resmi UPELKES, paling lambat <strong>H-3 sebelum
                            kegiatan</strong>.</li>
                    <li>Bukti pembayaran wajib diunggah ke sistem.</li>
                </ul>

                <!-- 5. Pembatalan & Perubahan Jadwal -->
                <h4 class="mt-4 mb-3">5. Pembatalan & Perubahan Jadwal</h4>
                <ul>
                    <li>Pembatalan dilakukan maksimal <strong>H-3 sebelum kegiatan</strong>.</li>
                    <li>Perubahan hanya diperbolehkan jika fasilitas belum digunakan dan masih tersedia.</li>
                    <li>Dana tidak dapat dikembalikan kecuali pembatalan karena force majeure (bencana alam, kebijakan
                        pemerintah, dll).</li>
                </ul>

                <!-- 6. Selama Kegiatan Berlangsung -->
                <h4 class="mt-4 mb-3">6. Selama Kegiatan Berlangsung</h4>
                <ul>
                    <li>Peserta wajib menjaga kebersihan, ketertiban, dan keamanan fasilitas.</li>
                    <li>Dilarang membawa alat elektronik tambahan tanpa izin.</li>
                    <li>Instansi bertanggung jawab atas kerusakan atau kehilangan selama penggunaan.</li>
                </ul>

                <!-- 7. Penutup -->
                <h4 class="mt-4 mb-3">7. Penutup</h4>
                <p>Dengan melakukan booking, instansi dianggap telah membaca, memahami, dan menyetujui seluruh syarat
                    dan ketentuan yang berlaku.</p>
                <p>UPELKES Jawa Barat berhak menolak atau membatalkan booking jika tidak sesuai dengan ketentuan.</p>

                <!-- CTA -->
                <div class="text-center mt-5">
                    <a href="/booking" class="btn btn-primary btn-lg px-4">Ajukan Booking Sekarang</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

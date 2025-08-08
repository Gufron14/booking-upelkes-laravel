<!DOCTYPE html>
<html>
<head>
    <title>Notifikasi Booking Baru</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .footer { margin-top: 20px; padding: 20px; background-color: #f8f9fa; text-align: center; font-size: 12px; }
        .button {
            display: inline-block; padding: 10px 20px; background-color: #007bff; 
            color: white; text-decoration: none; border-radius: 5px; margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Notifikasi Booking Baru</h2>
        </div>
        
        <div class="content">
            <p>Halo Admin,</p>
            <p>Terdapat booking baru dengan detail sebagai berikut:</p>
            
            <h3>Informasi User</h3>
            <p><strong>Nama:</strong> {{ $details['user_name'] }}</p>
            <p><strong>Email:</strong> {{ $details['user_email'] }}</p>
            <p><strong>No. HP:</strong> {{ $details['user_phone'] }}</p>
            <p><strong>Instansi:</strong> {{ $details['user_instansi'] }}</p>
            
            <h3>Informasi Booking</h3>
            <p><strong>Nama Kegiatan:</strong> {{ $details['booking_activity'] }}</p>
            <p><strong>Tanggal:</strong> {{ $details['booking_date'] }}</p>
            
            <p>Silahkan klik tombol di bawah ini untuk melihat detail booking:</p>
            <a href="{{ $details['booking_url'] }}" class="button">Lihat Booking</a>
        </div>
        
        <div class="footer">
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
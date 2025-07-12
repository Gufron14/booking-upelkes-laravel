<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Cetak Laporan' }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                font-size: 12px;
            }
            
            .table {
                font-size: 11px;
            }
            
            .page-break {
                page-break-before: always;
            }
        }
        
        .header-logo {
            max-height: 80px;
        }
        
        .table th {
            background-color: #f8f9fa !important;
            font-weight: bold;
        }
        
        .signature-section {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    
    {{ $slot }}
    
    <script>
        Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>

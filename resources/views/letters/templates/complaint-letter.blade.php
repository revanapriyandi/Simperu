<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $letter['subject'] ?? 'Surat Resmi' }}</title>
    <style>
        @page {
            margin: 2.5cm 2cm;
            size: A4;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            line-height: 1.6;
            font-size: 12pt;
            color: #000;
            margin: 0;
            padding: 0;
            background: white;
        }
        
        .letterhead {
            text-align: center;
            border-bottom: 3px solid #8B4513;
            padding-bottom: 15px;
            margin-bottom: 30px;
            position: relative;
        }
        
        .letterhead::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 1px;
            background: #8B4513;
        }
        
        .letterhead .logo {
            float: left;
            width: 80px;
            height: 80px;
            margin-right: 20px;
        }
        
        .letterhead .org-info {
            text-align: center;
            margin-left: 100px;
        }
        
        .letterhead .org-name {
            font-size: 18pt;
            font-weight: bold;
            color: #8B4513;
            font-style: italic;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }
        
        .letterhead .org-address {
            font-size: 11pt;
            color: #333;
            line-height: 1.4;
        }
        
        .letter-header {
            margin-bottom: 30px;
        }
        
        .letter-date {
            text-align: right;
            margin-bottom: 25px;
            font-size: 12pt;
        }
        
        .letter-meta {
            margin-bottom: 5px;
            font-size: 12pt;
        }
        
        .letter-meta strong {
            display: inline-block;
            width: 80px;
        }
        
        .letter-subject {
            margin-bottom: 5px;
            text-decoration: underline;
            font-weight: bold;
            font-size: 12pt;
        }
        
        .letter-subject strong {
            display: inline-block;
            width: 80px;
        }
        
        .letter-recipient {
            margin-bottom: 25px;
            margin-top: 20px;
        }
        
        .letter-recipient div {
            margin-bottom: 3px;
        }
        
        .letter-content {
            text-align: justify;
            margin-bottom: 60px;
            line-height: 1.8;
        }
        
        .greeting {
            font-style: italic;
            margin-bottom: 25px;
        }
        
        .main-content {
            margin: 25px 0;
            text-indent: 1.5cm;
        }
        
        .main-content p {
            margin-bottom: 15px;
            text-indent: 1.5cm;
        }
        
        .closing {
            font-style: italic;
            margin-top: 30px;
            margin-bottom: 25px;
        }
        
        .signature-section {
            margin-top: 60px;
            text-align: right;
        }
        
        .signature-date {
            margin-bottom: 15px;
        }
        
        .signature-role {
            margin-bottom: 80px;
        }
        
        .signature-name {
            font-weight: bold;
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 200px;
            text-align: center;
            padding-bottom: 5px;
        }
        
        .digital-signature {
            margin-top: 40px;
            border: 2px solid #8B4513;
            padding: 15px;
            text-align: center;
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            border-radius: 8px;
        }
        
        .digital-signature-title {
            font-weight: bold;
            color: #8B4513;
            font-size: 13pt;
            margin-bottom: 10px;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100pt;
            color: rgba(255, 255, 0, 0.05);
            z-index: -1;
            font-weight: bold;
            white-space: nowrap;
        }
        
        .barcode {
            margin: 15px 0;
        }
        
        .barcode img {
            height: 50px;
            border: 1px solid #e2e8f0;
            padding: 5px;
            background: white;
        }
        
        .verification-info {
            font-size: 9pt;
            color: #666;
            margin-top: 12px;
            border-top: 1px solid #ccc;
            padding-top: 8px;
            line-height: 1.4;
        }
        
        .verification-info div {
            margin-bottom: 3px;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">VILLA WINDARO PERMAI</div>
    
    <!-- Letterhead -->
    <div class="letterhead clearfix">
        @if(isset($organization['logo_path']) && file_exists($organization['logo_path']))
        <img src="{{ $organization['logo_path'] }}" alt="Logo Villa Windaro Permai" class="logo">
        @endif
        
        <div class="org-info">
            <div class="org-name">{{ $organization['name'] ?? 'Perumahan Villa Windaro Permai' }}</div>
            <div class="org-address">
                {{ $organization['address'] ?? 'Alamat : Jl. Amarta, RT 03/RW 01 Kelurahan Delima, Kecamatan Binawidya, Kota Pekanbaru, Riau 28292' }}
            </div>
        </div>
    </div>
    
    <!-- Letter Header -->
    <div class="letter-header">
        <div class="letter-date">
            {{ $organization['city'] ?? 'Pekanbaru' }}, {{ $letter['date'] ?? now()->locale('id')->isoFormat('D MMMM Y') }}
        </div>
        
        <div class="letter-meta"><strong>Nomor</strong> : {{ $letter['number'] ?? '-' }}</div>
        <div class="letter-meta"><strong>Lampiran</strong> : {{ $letter['attachment'] ?? '-' }}</div>
        <div class="letter-subject"><strong>Hal</strong> : {{ $letter['subject'] ?? 'Surat Resmi' }}</div>
        
        @if(isset($letter['recipient']) && $letter['recipient'])
        <div class="letter-recipient">
            <div>Kepada Yth.</div>
            <div><strong>{{ $letter['recipient'] }}</strong></div>
            <div>Di-Tempat</div>
        </div>
        @endif
    </div>
    
    <!-- Letter Content -->
    <div class="letter-content">
        @if(isset($letter['greeting']) && $letter['greeting'])
        <div class="greeting">{{ $letter['greeting'] }}</div>
        @else
        <div class="greeting">Assalamu'alaikum warahmatullahi wabarakatuh</div>
        @endif
        
        <div class="main-content">
            {!! nl2br(e($letter['content'] ?? '')) !!}
        </div>
        
        @if(isset($letter['closing']) && $letter['closing'])
        <div class="closing">{{ $letter['closing'] }}</div>
        @else
        <div class="closing">Wassalamu'alaikum warahmatullahi wabarakatuh</div>
        @endif
    </div>
    
    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-date">
            {{ $organization['city'] ?? 'Pekanbaru' }}, {{ $letter['date'] ?? now()->locale('id')->isoFormat('D MMMM Y') }}
        </div>
        <div class="signature-role">Hormat kami</div>
        <br><br><br>
        <div class="signature-name">
            {{ $signature['signer_name'] ?? 'Pengurus Perumahan Villa Windaro Permai' }}
        </div>
    </div>
    
    @if(isset($signature['signature_hash']))
    <!-- Digital Signature -->
    <div class="digital-signature">
        <div class="digital-signature-title">🔒 DOKUMEN DIGITAL TERSERTIFIKASI</div>
        <div style="font-size: 11pt; margin-bottom: 10px;">
            Dokumen ini telah ditandatangani secara digital dan terverifikasi oleh sistem
        </div>
        
        @if(isset($signature['barcode_path']) && file_exists($signature['barcode_path']))
        <div class="barcode">
            <img src="{{ $signature['barcode_path'] }}" alt="Barcode Tanda Tangan Digital">
        </div>
        @endif
        
        <div class="verification-info">
            <div><strong>Ditandatangani oleh:</strong> {{ $signature['signer_name'] ?? 'Sistem' }}</div>
            <div><strong>Tanggal:</strong> {{ $signature['signed_at'] ?? now()->locale('id')->isoFormat('D MMMM Y, HH:mm') }}</div>
            <div><strong>Hash Verifikasi:</strong> <code>{{ substr($signature['signature_hash'], 0, 40) }}...</code></div>
            <div><strong>Status:</strong> ✅ Valid dan Terverifikasi</div>
        </div>
    </div>
    @endif
    
    <!-- Footer -->
    <div style="margin-top: 30px; font-size: 8pt; color: #666; text-align: center; border-top: 1px solid #ddd; padding-top: 10px;">
        Dokumen ini dibuat melalui Sistem Informasi Manajemen Perumahan Villa Windaro Permai<br>
        Untuk verifikasi keaslian dokumen, silakan hubungi pengurus atau cek melalui sistem online.
    </div>
</body>
</html>

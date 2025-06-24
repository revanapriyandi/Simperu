<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $letter->category->name }} - {{ $letter->letter_number }}</title>
    <style>
        @page {
            margin: 2.5cm 2cm 2cm 2cm;
            font-family: 'Times New Roman', serif;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
            margin: 0;
            padding: 0;
        }
        
        .letterhead {
            text-align: center;
            border-bottom: 3px solid #8B4513;
            padding-bottom: 15px;
            margin-bottom: 30px;
            position: relative;
        }
        
        .letterhead h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 0 0 5px 0;
            color: #8B4513;
            text-transform: uppercase;
        }
        
        .letterhead .address {
            font-size: 11pt;
            margin: 5px 0;
            font-style: italic;
            line-height: 1.4;
        }
        
        .letter-meta {
            margin-bottom: 30px;
        }
        
        .letter-meta table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .letter-meta td {
            padding: 3px 0;
            vertical-align: top;
        }
        
        .letter-meta .label {
            width: 100px;
            font-weight: normal;
        }
        
        .letter-meta .colon {
            width: 20px;
            text-align: left;
        }
        
        .letter-meta .value {
            font-weight: normal;
        }
        
        .date-location {
            text-align: right;
            margin-bottom: 30px;
            font-size: 12pt;
        }
        
        .recipient {
            margin: 30px 0;
        }
        
        .recipient-line {
            margin-bottom: 5px;
        }
        
        .content {
            text-align: justify;
            margin: 30px 0;
            min-height: 200px;
            line-height: 1.8;
        }
        
        .content p {
            margin-bottom: 15px;
        }
        
        .greeting {
            font-style: italic;
            margin-bottom: 20px;
        }
        
        .closing {
            font-style: italic;
            margin-top: 20px;
        }
        
        .signature-section {
            margin-top: 50px;
            display: table;
            width: 100%;
        }
        
        .signature-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        .signature-right {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        
        .signature-box {
            border: 1px solid #ccc;
            height: 80px;
            margin: 15px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 10pt;
        }
        
        .barcode {
            text-align: center;
            margin: 15px 0;
        }
        
        .barcode img {
            max-width: 120px;
            height: auto;
        }
        
        .verification-code {
            font-size: 8pt;
            text-align: center;
            margin-top: 10px;
            color: #666;
            font-family: monospace;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 48pt;
            color: rgba(139, 69, 19, 0.05);
            z-index: -1;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .approved-stamp {
            position: absolute;
            top: 150px;
            right: 50px;
            width: 100px;
            height: 100px;
            border: 3px solid #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(40, 167, 69, 0.1);
            transform: rotate(-15deg);
        }
        
        .approved-stamp .text {
            color: #28a745;
            font-weight: bold;
            font-size: 11pt;
            text-align: center;
            line-height: 1.2;
        }
        
        .status-section {
            margin-top: 40px;
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #28a745;
            border-radius: 0 5px 5px 0;
            font-size: 10pt;
        }
        
        .status-section h4 {
            margin: 0 0 10px 0;
            color: #28a745;
            font-size: 11pt;
        }
        
        .status-info {
            line-height: 1.5;
        }
        
        .qr-section {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
    </style>
</head>
<body>
    @if($letter->approval_status === 'approved')
        <div class="watermark">Villa Windaro Permai</div>
        
        <div class="approved-stamp">
            <div class="text">
                DISETUJUI<br>
                <span style="font-size: 9pt;">{{ $signature_info['signed_date'] ?? '' }}</span>
            </div>
        </div>
    @endif

    <!-- Letterhead -->
    <div class="letterhead">
        <h1>{{ $letterhead['title'] }}</h1>
        <div class="address">
            {{ $letterhead['address'] }}
        </div>
    </div>

    <!-- Date and Location -->
    <div class="date-location">
        Pekanbaru, {{ $letter->letter_date->format('d F Y') }}
    </div>

    <!-- Letter Metadata -->
    <div class="letter-meta">
        <table>
            <tr>
                <td class="label">Nomor</td>
                <td class="colon">:</td>
                <td class="value">{{ $letter->letter_number }}</td>
            </tr>
            <tr>
                <td class="label">Lampiran</td>
                <td class="colon">:</td>
                <td class="value">-</td>
            </tr>
            <tr>
                <td class="label">Hal</td>
                <td class="colon">:</td>
                <td class="value"><strong><u>{{ $letter->subject }}</u></strong></td>
            </tr>
        </table>
    </div>

    <!-- Recipient -->
    <div class="recipient">
        <div class="recipient-line">
            <strong>Kepada Yth.</strong>
        </div>
        <div class="recipient-line">
            {{ $letter->recipient }}
        </div>
        <div class="recipient-line">
            <u>Di-Tempat</u>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="greeting">
            <em>Assalamu'alaikum warahmatullahi wabarakatuh</em>
        </div>
        
        <div style="margin: 25px 0;">
            {!! nl2br(e($letter->content)) !!}
        </div>
        
        <div class="closing">
            <em>Wassalamu'alaikum warahmatullahi wabarakatuh</em>
        </div>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-left">
            <!-- Space for additional signatures if needed -->
        </div>
        
        <div class="signature-right">
            <div style="margin-bottom: 10px;">{{ $letter->letter_date->format('d F Y') }}</div>
            <div style="margin-bottom: 15px;"><strong>Hormat kami</strong></div>
            
            @if($letter->approval_status === 'approved' && isset($signature_info['barcode_path']) && $signature_info['barcode_path'])
                <div class="qr-section">
                    <div class="barcode">
                        <img src="{{ $signature_info['barcode_path'] }}" alt="Digital Signature QR Code">
                    </div>
                    <div style="margin-top: 15px;">
                        <strong>{{ $signature_info['signed_by'] ?? 'Pengurus' }}</strong><br>
                        <em>Pengurus Perumahan Villa Windaro Permai</em>
                    </div>
                    <div class="verification-code">
                        Kode Verifikasi: {{ substr($signature_info['verification_code'] ?? '', 0, 16) }}...
                    </div>
                </div>
            @else
                <div class="signature-box">
                    <span>Menunggu Persetujuan</span>
                </div>
                <div style="margin-top: 15px;">
                    <strong>Pengurus Perumahan Villa Windaro Permai</strong>
                </div>
            @endif
        </div>
    </div>

    <!-- Status Information -->
    @if($letter->approval_status === 'approved')
        <div class="status-section">
            <h4>Informasi Persetujuan</h4>
            <div class="status-info">
                <strong>Status:</strong> {{ $letter->approval_status_label }}<br>
                <strong>Disetujui oleh:</strong> {{ $signature_info['signed_by'] ?? 'Pengurus' }}<br>
                <strong>Tanggal Persetujuan:</strong> {{ $signature_info['signed_date'] ?? '' }}
                @if($letter->approval_notes)
                    <br><strong>Catatan:</strong> {{ $letter->approval_notes }}
                @endif
            </div>
        </div>
    @endif

    <!-- Footer -->
    <div style="margin-top: 30px; text-align: center; font-size: 9pt; color: #666;">
        <hr style="border: none; border-top: 1px solid #ccc; margin: 20px 0;">
        Dokumen ini telah ditandatangani secara digital dan sah menurut hukum yang berlaku
        @if($letter->signature_hash)
            <br>Hash Verifikasi: {{ substr($letter->signature_hash, 0, 32) }}...
        @endif
    </div>
</body>
</html>

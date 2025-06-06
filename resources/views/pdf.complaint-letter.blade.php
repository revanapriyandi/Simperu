<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pengaduan</title>
    <style>
        @page {
            margin: 2cm;
            size: A4;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #8B4513;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .logo {
            width: 80px;
            height: 80px;
            background-color: #FFD700;
            border: 2px solid #8B4513;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            font-weight: bold;
            font-size: 10pt;
            color: #8B4513;
        }

        .header-text {
            text-align: left;
        }

        .housing-name {
            font-size: 18pt;
            font-weight: bold;
            color: #8B4513;
            margin: 0;
            font-style: italic;
        }

        .housing-address {
            font-size: 10pt;
            margin: 5px 0 0 0;
            color: #333;
        }

        .letter-info {
            margin: 30px 0;
            float: right;
            text-align: right;
        }

        .letter-details {
            margin: 40px 0;
            clear: both;
        }

        .letter-details table {
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .letter-details td {
            padding: 3px 0;
            vertical-align: top;
        }

        .letter-details .label {
            width: 80px;
            font-weight: bold;
        }

        .letter-details .colon {
            width: 20px;
            text-align: center;
        }

        .recipient {
            margin: 30px 0;
            text-align: left;
        }

        .greeting {
            margin: 20px 0;
            font-style: italic;
        }

        .content {
            text-align: justify;
            margin: 20px 0;
            line-height: 1.8;
        }

        .content p {
            margin-bottom: 15px;
        }

        .closing {
            margin-top: 40px;
            margin-bottom: 80px;
            font-style: italic;
        }

        .signature {
            float: right;
            text-align: center;
            margin-top: 30px;
        }

        .signature-line {
            margin-top: 60px;
            border-bottom: 1px solid #000;
            width: 200px;
            margin-bottom: 5px;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 72pt;
            color: rgba(255, 215, 0, 0.1);
            font-weight: bold;
            z-index: -1;
            border: 8px solid rgba(255, 215, 0, 0.1);
            padding: 20px 40px;
            border-radius: 20px;
        }

        .category-badge {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 10pt;
            display: inline-block;
            margin-bottom: 10px;
        }

        .urgent {
            background-color: #ffebee;
            border-color: #f44336;
            color: #c62828;
        }

        .medium {
            background-color: #fff3e0;
            border-color: #ff9800;
            color: #ef6c00;
        }

        .high {
            background-color: #fce4ec;
            border-color: #e91e63;
            color: #ad1457;
        }

        .priority-text {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10pt;
        }
    </style>
</head>

<body>
    <!-- Watermark -->
    <div class="watermark">
        VILLA<br>WINDARO PERMAI
    </div>

    <!-- Header -->
    <div class="header">
        <div class="logo-container">
            <div class="logo">
                VILLA<br>WINDARO<br>PERMAI
            </div>
            <div class="header-text">
                <h1 class="housing-name">{{ $housing_name }}</h1>
                <p class="housing-address">Alamat: {{ $housing_address }}</p>
            </div>
        </div>
    </div>

    <!-- Letter Date and Location -->
    <div class="letter-info">
        {{ $current_city }}, {{ $current_date }}
    </div>

    <!-- Letter Details -->
    <div class="letter-details">
        <table>
            <tr>
                <td class="label">Nomor</td>
                <td class="colon">:</td>
                <td>{{ $letter_number }}</td>
            </tr>
            <tr>
                <td class="label">Lampiran</td>
                <td class="colon">:</td>
                <td>-</td>
            </tr>
            <tr>
                <td class="label">Hal</td>
                <td class="colon">:</td>
                <td><strong><u>{{ $complaint->subject }}</u></strong></td>
            </tr>
        </table>

        <!-- Category and Priority -->
        <div style="margin: 15px 0;">
            <span class="category-badge">{{ $category->name }}</span>
            @if ($complaint->priority)
                <span class="category-badge {{ $complaint->priority }}">
                    <span class="priority-text">
                        @switch($complaint->priority)
                            @case('low')
                                Prioritas: Rendah
                            @break

                            @case('medium')
                                Prioritas: Sedang
                            @break

                            @case('high')
                                Prioritas: Tinggi
                            @break

                            @case('urgent')
                                Prioritas: Sangat Penting
                            @break

                            @default
                                Prioritas: {{ $complaint->priority }}
                        @endswitch
                    </span>
                </span>
            @endif
        </div>
    </div>

    <!-- Recipient -->
    <div class="recipient">
        <p>Kepada Yth.<br>
            <strong>Pengurus Perumahan Villa Windaro Permai</strong><br>
            Di-Tempat
        </p>
    </div>

    <!-- Greeting -->
    <div class="greeting">
        <p><em>Assalamu'alaikum warahmatullahi wabarakatuh</em></p>
    </div>

    <!-- Content -->
    <div class="content">
        <p>Dengan hormat,</p>

        <p>Yang bertanda tangan di bawah ini:</p>

        <table style="margin: 15px 0; border-collapse: collapse;">
            <tr>
                <td style="width: 120px; padding: 3px 0;">Nama</td>
                <td style="width: 20px; text-align: center;">:</td>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">No. Rumah</td>
                <td style="text-align: center;">:</td>
                <td>{{ $user->house_number ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">No. KK</td>
                <td style="text-align: center;">:</td>
                <td>{{ $user->kk_number ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">No. Telepon</td>
                <td style="text-align: center;">:</td>
                <td>{{ $user->phone ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">Email</td>
                <td style="text-align: center;">:</td>
                <td>{{ $user->email }}</td>
            </tr>
        </table>

        <p>Dengan ini mengajukan pengaduan terkait <strong>{{ $category->name }}</strong> dengan rincian sebagai
            berikut:</p>

        <div
            style="margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9;">
            {!! nl2br(e($complaint->content)) !!}
        </div>

        <p>Demikian pengaduan ini kami sampaikan. Atas perhatian dan tindak lanjut dari Bapak/Ibu pengurus, kami ucapkan
            terima kasih.</p>
    </div>

    <!-- Closing -->
    <div class="closing">
        <p><em>Wassalamu'alaikum warahmatullahi wabarakatuh</em></p>
    </div>

    <!-- Signature -->
    <div class="signature">
        <p>{{ $current_city }}, {{ $current_date }}</p>
        <p>Hormat kami</p>
        <div class="signature-line"></div>
        <p><strong>{{ $user->name }}</strong></p>
        <p style="font-size: 10pt;">Warga Perumahan Villa Windaro Permai</p>
    </div>

    <div style="clear: both;"></div>
</body>

</html>

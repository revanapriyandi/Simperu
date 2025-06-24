<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $complaint->subject }} - {{ $complaint->letter_number }}</title>
    <style>
        @page {
            margin: 1cm 1.5cm 2.5cm 1.5cm;
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
            width: 100%;
            padding-bottom: 10px;
            margin-bottom: 20px;
            position: relative;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 100%;
            margin-bottom: 10px;
        }

        .logo-section {
            flex: 0 0 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
        }

        .logo-img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }

        .logo-placeholder {
            width: 70px;
            height: 70px;
            border: 2px solid #000;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 10pt;
        }

        .header-text {
            flex: 1;
            text-align: center;
        }

        .header-text h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 0 0 5px 0;
            color: #000;
        }

        .header-text .address {
            font-size: 10pt;
            margin: 0;
            line-height: 1.2;
            color: #000;
        }

        .header-divider {
            width: 100%;
            height: 3px;
            background: #000;
            border: none;
            margin: 0;
            display: block;
        }

        .date-location {
            text-align: right;
            margin: 20px 0;
            font-size: 12pt;
        }

        .letter-meta {
            margin: 20px 0;
            width: 100%;
        }

        .letter-meta table {
            width: 100%;
            border-collapse: collapse;
        }

        .letter-meta td {
            padding: 2px 0;
            vertical-align: top;
        }

        .letter-meta .label {
            width: 80px;
            font-weight: normal;
        }

        .letter-meta .colon {
            width: 20px;
            text-align: left;
        }

        .letter-meta .value {
            font-weight: normal;
        }

        .recipient {
            margin: 20px 0;
        }

        .recipient-line {
            margin-bottom: 3px;
            font-size: 12pt;
            line-height: 1.3;
        }

        .content {
            text-align: justify;
            margin: 20px 0;
            line-height: 1.6;
        }

        .content p {
            margin-bottom: 12px;
            text-indent: 0;
        }

        .greeting {
            margin-bottom: 15px;
        }

        .closing {
            margin-top: 20px;
        }

        .applicant-info {
            margin: 15px 0 15px 30px;
        }

        .applicant-info table {
            border-collapse: collapse;
            width: 100%;
        }

        .applicant-info td {
            padding: 3px 0;
            vertical-align: top;
        }

        .complaint-content {
            margin: 15px 0;
            padding: 10px 0;
            text-align: justify;
            line-height: 1.6;
        }

        .signature-section {
            margin-top: 40px;
            width: 100%;
            display: table;
        }

        .signature-right {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
            float: right;
        }

        .signature-box {
            height: 60px;
            margin: 15px 0;
            border-bottom: 1px solid #000;
            width: 200px;
            margin-left: auto;
            margin-right: auto;
        }

        .signature-name {
            margin-top: 5px;
            text-align: center;
            font-weight: normal;
        }

        .underline {
            text-decoration: underline;
        }

        .bold {
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .watermark-logo {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.05;
            z-index: -1;
            width: 400px;
            height: 400px;
            object-fit: contain;
        }

        /* Styling sesuai template */
        .spacing {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    @php
        $logoPath = public_path('assets/logo.png');
        $logoSrc = '';
        if (file_exists($logoPath)) {
            try {
                $logoData = base64_encode(file_get_contents($logoPath));
                $logoSrc = 'data:image/png;base64,' . $logoData;
            } catch (Exception $e) {
                $logoSrc = '';
            }
        }
    @endphp

    @if ($logoSrc)
        <img src="{{ $logoSrc }}" alt="Logo Watermark" class="watermark-logo">
    @endif

    <!-- Letterhead sesuai template -->
    <div style="width:100%;margin-bottom:20px;">
        <div style="display:table;width:100%;">
            <div style="display:table-row;">
                <div style="display:table-cell;width:90px;vertical-align:middle;text-align:center;">
                    @if ($logoSrc)
                        <img src="{{ $logoSrc }}" alt="Logo {{ $housing_name }}"
                            style="width:70px; height:70px; object-fit:contain;">
                    @else
                        <div
                            style="width:70px; height:70px; border:2px solid #000; border-radius:8px; display:flex;align-items:center;justify-content:center;font-weight:bold;font-size:10pt;">
                            LOGO</div>
                    @endif
                </div>
                <div style="display:table-cell;vertical-align:middle;text-align:center;padding-left:0;">
                    <div style="font-size:18pt;font-weight:bold;letter-spacing:1px;line-height:1.2;">{{ $housing_name }}
                    </div>
                    <div style="font-size:11pt;margin-top:2px;">Alamat : {{ $housing_address }}</div>
                </div>
            </div>
        </div>
        <hr style="height:3px;background:#000;border:none;margin:8px 0 0 0;">
    </div>

    <!-- Tanggal dan Lokasi -->
    <div class="date-location">
        @php
            // Extract city name from address
            $addressParts = explode(',', $housing_address);
            $cityName = 'Pekanbaru'; // default
            if (count($addressParts) > 0) {
                // Try to find city name in the last parts of address
                foreach (array_reverse($addressParts) as $part) {
                    $part = trim($part);
                    // Look for common city patterns
                    if (preg_match('/Kota\s+(\w+)|(\w+)\s+\d{5}/', $part, $matches)) {
                        $cityName = $matches[1] ?? ($matches[2] ?? $cityName);
                        break;
                    }
                }
            }
        @endphp
        {{ ucfirst($cityName) }}, {{ $letter_date }}
    </div>

    <!-- Metadata Surat -->
    <div class="letter-meta">
        <table>
            <tr>
                <td class="label">Nomor</td>
                <td class="colon">:</td>
                <td class="value">{{ $letter_number }}</td>
            </tr>
            <tr>
                <td class="label">Lampiran</td>
                <td class="colon">:</td>
                <td class="value">
                    @if ($complaint->attachments && count($complaint->attachments) > 0)
                        {{ count($complaint->attachments) }} berkas
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Hal</td>
                <td class="colon">:</td>
                <td class="value">{{ $complaint->subject }}</td>
            </tr>
        </table>
    </div>

    <!-- Penerima -->
    <div class="recipient">
        <div class="recipient-line"><strong>Kepada Yth.</strong></div>
        <div class="recipient-line">{{ $complaint->recipient }}</div>
        <div class="recipient-line"><u>Di-Tempat</u></div>
    </div>

    <!-- Isi Surat -->
    <div class="content">
        <div class="greeting spacing">
            Assalamu'alaikum warahmatullahi wabarakatuh
        </div>

        <p>
            Sehubungan dengan kondisi yang kami alami di lingkungan {{ $housing_name }},
            bersama ini kami sampaikan pengaduan sebagai berikut:
        </p>

        <p><strong>Yang bertanda tangan di bawah ini:</strong></p>

        <div class="applicant-info">
            <table>
                <tr>
                    <td style="width: 120px;">Nama</td>
                    <td style="width: 15px;">:</td>
                    <td><strong>{{ $user->name }}</strong></td>
                </tr>
                @if ($user->family && $user->family->address)
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td>{{ $user->family->address }}</td>
                    </tr>
                @endif
                @if ($user->phone)
                    <tr>
                        <td>No. Telepon</td>
                        <td>:</td>
                        <td>{{ $user->phone }}</td>
                    </tr>
                @endif
                <tr>
                    <td>Email</td>
                    <td>:</td>
                    <td>{{ $user->email }}</td>
                </tr>
            </table>
        </div>

        <p>
            Dengan ini mengajukan pengaduan mengenai:
        </p>

        <div class="complaint-content">
            {!! $complaint->content !!}
        </div>

        <p>
            Demikian pengaduan ini kami sampaikan, atas perhatian dan tindak lanjutnya
            kami ucapkan terima kasih.
        </p>

        <div class="closing spacing">
            Wassalamu'alaikum warahmatullahi wabarakatuh
        </div>
    </div>

    <!-- Bagian Tanda Tangan -->
    <div class="signature-section">
        <div style="clear: both; height: 20px;"></div>
        <div class="signature-right">
            <div class="date-location" style="text-align: center; margin-bottom: 10px;">
                {{ ucfirst($cityName) }}, {{ $letter_date }}
            </div>
            <div style="margin-bottom: 15px;"><strong>Hormat kami</strong></div>
            <div style="margin-bottom: 15px;"><strong>Pengurus {{ $housing_name }}</strong></div>
            <div class="signature-box">
                @if ($complaint->approval_status === 'approved')
                    <div style="text-align: center; padding-top: 20px;">
                        <strong>( Disetujui )</strong>
                    </div>
                @endif
            </div>
            <div class="signature-name">
                @if ($complaint->approval_status === 'approved' && $complaint->signedBy)
                    <u>{{ $complaint->signedBy->name }}</u>
                @else
                    (............................)
                @endif
            </div>
        </div>
    </div>

    <div style="clear: both; height: 40px;"></div>

    <!-- Footer -->
    <div
        style="margin-top: 50px; text-align: center; font-size: 9pt; color: #666; border-top: 1px solid #ddd; padding-top: 10px;">
        <strong>{{ $housing_name }}</strong><br>
        {{ $housing_address }}<br>
        Dokumen ini dibuat secara otomatis oleh sistem<br>
        Tanggal cetak: {{ now()->format('d F Y H:i') }} WIB | No: {{ $complaint->letter_number }}
        @if ($complaint->approval_status === 'approved' && $complaint->signature_hash)
            <br>Hash: {{ substr($complaint->signature_hash, 0, 12) }}...
        @endif
    </div>
</body>

</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pengaduan</title>
    <style>
        @page {
            margin: 2.5cm;
            size: A4;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .kop {
            display: flex;
            align-items: center;
            border-bottom: 3px solid #222;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }

        .kop-logo {
            width: 90px;
            height: 90px;
            margin-right: 18px;
        }

        .kop-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-title {
            font-size: 18pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .kop-address {
            font-size: 11pt;
            margin-bottom: 2px;
        }

        .kop-contact {
            font-size: 10pt;
        }

        .garis-tebal {
            border-bottom: 3px solid #222;
            margin: 0 0 18px 0;
        }

        .info-surat {
            margin-bottom: 18px;
            padding-left: 10px;
            padding-right: 10px;
        }

        .info-surat table {
            width: 100%;
            font-size: 12pt;
        }

        .info-surat td {
            padding: 2px 0;
            vertical-align: top;
        }

        .kepada {
            margin-bottom: 12px;
            padding-left: 10px;
            padding-right: 10px;
        }

        .isi-surat {
            margin-bottom: 18px;
            text-align: justify;
            padding-left: 10px;
            padding-right: 10px;
        }

        .ttd {
            width: 40%;
            float: right;
            text-align: center;
            margin-top: 40px;
        }

        .ttd-space {
            height: 60px;
        }

        .footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 1.5cm;
            text-align: center;
            font-size: 10pt;
            color: #888;
        }

        .qr {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <!-- KOP SURAT -->
    <div class="kop">
        <div class="kop-logo">
            {{-- Ganti src logo sesuai kebutuhan, atau gunakan logo default --}}
            <img src="{{ public_path('assets/logo.png') }}" alt="Logo" />
        </div>
        <div class="kop-text">
            <div class="kop-title">{{ $housing_name }}</div>
            <div class="kop-address">{{ $housing_address }}</div>
            <div class="kop-contact">Telp: {{ $contact_phone }} | Email: {{ $contact_email }}</div>
        </div>
    </div>

    <!-- Info Surat -->
    <div class="info-surat">
        <table>
            <tr>
                <td style="width:90px;">Nomor</td>
                <td style="width:10px;">:</td>
                <td>{{ $letter_number }}</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td><strong><u>{{ $complaint->subject }}</u></strong></td>
            </tr>
        </table>
        <div style="text-align:right; margin-top: -60px;">{{ $current_city }}, {{ $current_date }}</div>
    </div>

    <!-- Kepada Yth -->
    <div class="kepada">
        Kepada Yth.<br>
        <strong>{{ $complaint->recipient ?? 'Pengurus Perumahan' }}</strong><br>
        Di Tempat
    </div>

    <!-- Salam Pembuka -->
    <div class="isi-surat">
        <p>Assalamu'alaikum warahmatullahi wabarakatuh</p>
        <p>Dengan hormat,</p>
        <p>Yang bertanda tangan di bawah ini:</p>
        <table style="margin-bottom:10px;">
            <tr>
                <td style="width:110px;">Nama</td>
                <td style="width:10px;">:</td>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <td>No. Rumah</td>
                <td>:</td>
                <td>{{ $user->house_number ?? '-' }}</td>
            </tr>
            <tr>
                <td>No. KK</td>
                <td>:</td>
                <td>{{ $user->kk_number ?? '-' }}</td>
            </tr>
            <tr>
                <td>No. Telepon</td>
                <td>:</td>
                <td>{{ $user->phone ?? '-' }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>:</td>
                <td>{{ $user->email }}</td>
            </tr>
        </table>
        <p>Dengan ini mengajukan pengaduan terkait <strong>{{ $category->name }}</strong> dengan rincian sebagai
            berikut:</p>
        <div
            style="margin: 10px 0 18px 0; padding: 12px; border: 1px solid #bbb; border-radius: 5px; background: #f8f8f8;">
            {!! nl2br(e($complaint->content)) !!}
        </div>
        <p>Demikian pengaduan ini kami sampaikan. Atas perhatian dan tindak lanjut dari Bapak/Ibu pengurus, kami ucapkan
            terima kasih.</p>
        <p>Wassalamu'alaikum warahmatullahi wabarakatuh</p>
    </div>

    <!-- Tanda Tangan -->
    <div class="ttd">
        {{ $current_city }}, {{ $current_date }}<br>
        Hormat kami,<br>
        <div class="ttd-space"></div>
        <strong>{{ $user->name }}</strong><br>
        <span style="font-size:10pt;">Warga {{ $housing_name }}</span>
        @if (!empty($letter->digital_signature))
            <div style="margin-top:10px;">
                <img src="{{ public_path($letter->barcode_path ?? '') }}" alt="QR" width="80" class="qr">
                <div style="font-size:8pt; color:#888;">Verifikasi: {{ $letter->signature_hash }}</div>
            </div>
        @endif
    </div>

    <div style="clear:both;"></div>

    <!-- Footer jika ada -->
    <div class="footer">
        {{ $housing_name }} &bull; {{ $housing_address }} &bull; Telp: {{ $contact_phone }}
    </div>
</body>

</html>

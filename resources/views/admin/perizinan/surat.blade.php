<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Izin Santri</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
        }

        .kop img {
            width: 100%;
            max-height: 150px;
        }

        .content {
            margin-top: 20px;
            line-height: 1.6;
        }

        .ttd {
            margin-top: 50px;
            text-align: right;
        }

        .print-btn {
            display: none;
        }

        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="kop">
        <img src="{{ asset('storage/kop.png') }}" alt="Kop Pondok">
    </div>

    <div class="content">
        <h2 style="text-align: center;">SURAT IZIN SANTRI</h2>

        <p>Yang bertanda tangan di bawah ini, memberikan izin kepada santri berikut:</p>

        <table>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>{{ $izin->santri->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>NIS</td>
                <td>:</td>
                <td>{{ $izin->nis }}</td>
            </tr>
            <tr>
                <td>Tanggal Izin</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($izin->tanggal)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Tanggal Kembali</td>
                <td>:</td>
                <td>{{ $izin->tanggal_kembali ? \Carbon\Carbon::parse($izin->tanggal_kembali)->translatedFormat('d F Y') : '-' }}</td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td>:</td>
                <td>{{ ucfirst($izin->keterangan) }}</td>
            </tr>
        </table>

        <p>Demikian surat ini dibuat untuk dapat digunakan sebagaimana mestinya.</p>

        <div class="ttd">
            <p>____________________</p>
            <p>{{ $izin->pengurus->name ?? '-' }}</p>
        </div>
    </div>

    <div class="text-center">
        <button onclick="window.print()" class="print-btn">🖨️ Print</button>
    </div>

</body>

</html>
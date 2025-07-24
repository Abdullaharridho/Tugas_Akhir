<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Keterlambatan</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            padding: 40px;
        }

        .kop {
            text-align: center;
            margin-bottom: 20px;
        }

        .isi {
            margin-top: 30px;
            line-height: 1.6;
            font-size: 16px;
        }

        .ttd {
            margin-top: 50px;
            text-align: right;
        }
    </style>
    <script>
    window.onload = function() {
        window.print();
    };
</script>
</head>

<body>
    <div class="kop">
        <img src="{{ asset('storage/kop.png') }}" alt="Kop Pondok" width="100%">
    </div>

    <div class="isi">
        <p>Kepada Yth.</p>
        <p>Orang tua/wali dari:</p>
        <p><strong>Nama:</strong> {{ $izin->santri->nama }}<br>
            <strong>NIS:</strong> {{ $izin->santri->nis }}</p>

        <p>Dengan ini kami memberitahukan bahwa santri tersebut kembali ke pondok pada tanggal <strong>{{ \Carbon\Carbon::parse($izin->updated_at)->format('d F Y') }}</strong>, yang seharusnya kembali pada <strong>{{ \Carbon\Carbon::parse($izin->tanggal_kembali)->format('d F Y') }}</strong>.</p>

        <p>Oleh karena itu, santri dikenakan <strong>hukuman 1 sak semen</strong> sebagai bentuk tanggung jawab atas keterlambatannya.</p>

        <p>Demikian surat ini disampaikan untuk menjadi perhatian dan pelajaran bersama.</p>
    </div>

    <div class="ttd">
        <p>Hormat kami,</p>
        <p style="margin-top: 60px;">__________________________<br>
            Pengurus Pondok</p>
    </div>

    <script>
        window.print();
    </script>
</body>

</html>

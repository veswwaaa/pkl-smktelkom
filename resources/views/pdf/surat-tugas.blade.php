<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Tugas PKL</title>
    <style>
        @page {
            margin: 1.5cm 2cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
        }

        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 30px;
            position: relative;
        }

        .kop-surat img {
            width: 70px;
            height: auto;
            position: absolute;
            left: 0;
            top: 0;
        }

        .kop-surat .text {
            padding-left: 0;
        }

        .kop-surat .judul {
            margin: 0;
            font-size: 11pt;
            font-weight: normal;
            letter-spacing: 2px;
        }

        .kop-surat h2 {
            margin: 5px 0;
            font-size: 20pt;
            font-weight: bold;
        }

        .kop-surat .sub-judul {
            margin: 5px 0;
            font-size: 11pt;
            font-weight: bold;
        }

        .kop-surat .program {
            margin: 3px 0;
            font-size: 9pt;
            font-weight: bold;
        }

        .kop-surat .alamat {
            margin: 3px 0;
            font-size: 8pt;
        }

        .nomor-surat {
            text-align: center;
            margin: 30px 0;
        }

        .nomor-surat h4 {
            margin: 5px 0;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            letter-spacing: 2px;
        }

        .nomor-surat p {
            margin: 5px 0;
            font-size: 11pt;
        }

        .content {
            text-align: justify;
            margin: 20px 0;
            text-indent: 0;
        }

        .content p {
            margin: 15px 0;
            text-indent: 50px;
        }

        .content p.no-indent {
            text-indent: 0;
        }

        table.data-siswa {
            margin: 20px 0 20px 100px;
            border-collapse: collapse;
        }

        table.data-siswa td {
            padding: 3px 0;
            vertical-align: top;
        }

        table.data-siswa td:first-child {
            width: 150px;
        }

        table.data-siswa td:nth-child(2) {
            width: 20px;
        }

        .ttd-section {
            margin-top: 50px;
        }

        .ttd-box {
            float: left;
            margin-left: 50px;
            text-align: center;
        }

        .ttd-box p {
            margin: 3px 0;
        }

        .ttd-space {
            height: 60px;
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>
    <div class="kop-surat">
        <img src="{{ public_path('img/logo-telkom-schools.png') }}" alt="Logo Telkom Schools">
        <div class="text">
            <p class="judul">YAYASAN PENDIDIKAN TELKOM</p>
            <h2>SMK Telkom Banjarbaru</h2>
            <p class="sub-judul">Terakreditasi A</p>
            <p class="program">Program Keahlian :<br>
                (1) Teknik Jaringan Komputer dan Telekomunikasi (2) Desain Komunikasi Visual<br>
                (3) Pengembangan Perangkat Lunak dan Gim (4) Animasi
            </p>
            <p class="alamat">Jl. Pangeran Suriansyah No. 3 Banjarbaru, 70711 – Telp/Fax : 0511-4772818 /
                4772700<br>
                w : www.smktelkom-bjb.sch.id &nbsp;&nbsp; e : smktelbjb@ypt.or.id
            </p>
        </div>
    </div>

    <div class="nomor-surat">
        <h4>SURAT TUGAS</h4>
        <p>Nomor : {{ $nomorSurat }}</p>
    </div>

    <div class="content">
        <p class="no-indent">Yang bertanda tangan dibawah ini Kepala SMK Telkom Banjarbaru, memberikan tugas kepada
            :</p>

        <table class="data-siswa">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>{{ strtoupper($siswa->nama) }}</td>
            </tr>
            <tr>
                <td>NIS</td>
                <td>:</td>
                <td>{{ $siswa->nis }}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{ $siswa->kelas }}</td>
            </tr>
            <tr>
                <td>Program Keahlian</td>
                <td>:</td>
                <td>{{ $siswa->jurusan }}</td>
            </tr>
        </table>

        <p>Untuk melaksanakan Praktek Kerja Lapangan (PKL) terhitung dari tanggal pada tanggal 21 Juli s.d 21 November
            2025 di Neumedira Indonesia beralamat di Komplek Miranti Griya Asri II No. G13, Mentaos, Kec. Banjarbaru
            Utara, Kota Banjarbaru, Kalimantan Selatan, 70714.</p>

        <p>Demikian Surat Tugas ini kami berikan agar dapat dilaksanakan dengan sebaik-baiknya.</p>
    </div>

    <div class="ttd-section">
        <div class="ttd-box">
            <p>Banjarbaru, {{ $tanggalSurat }}</p>
            <p>Kepala SMK Telkom Banjarbaru</p>
            <div class="ttd-space"></div>
            <p><strong><u>Jatminto, M.Pd.</u></strong></p>
            <p>NIP 01700024</p>
        </div>
    </div>

    <div class="clear"></div>
</body>

</html>

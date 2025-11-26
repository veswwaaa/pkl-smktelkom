<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Surat Permohonan Data PKL</title>
    <style>
        .header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 12px; /* push header slightly down */
            margin-bottom: 28px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            padding-left: 6px; /* nudge everything a bit to the left */
        }

        .header .logo img {
            height: 50px; /* slightly larger logo to match sample */
            width: auto;
            display: block;
        }

        .header .brand {
            flex: 1;
            text-align: center;
            transform: translateX(8px); /* shift brand a little left */
            width: auto;
            display: block;
        }


        .header .brand {
            flex: 1;
            text-align: center;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 16pt;
        }

        .header p {
            margin: 3px 0;
            font-size: 10pt;
        }

        .nomor-surat {
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
        }

        .content {
            margin: 30px 0;
            text-align: justify;
        }

        .content p {
            margin: 10px 0;
        }

        .content ul {
            margin: 15px 0;
            padding-left: 40px;
        }

        .content ul li {
            margin: 8px 0;
        }

        .box-request {
            border: 2px solid #000;
            padding: 15px;
            margin: 20px 0;
            background-color: #f9f9f9;
        }

        .box-request h3 {
            margin-top: 0;
            font-size: 13pt;
        }

        .ttd {
            margin-top: 50px;
            float: right;
            width: 300px;
            text-align: center;
        }

        .ttd-space {
            height: 80px;
        }

        .footer {
            clear: both;
            margin-top: 100px;
            font-size: 10pt;
            font-style: italic;
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="logo">
            {{-- Embed logo as base64 to ensure it appears in generated PDFs --}}
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/telkom-logo.png'))) }}" alt="Telkom Logo">
        </div>
        <div class="brand">
            <h2>SMK TELKOM BANJARBARU</h2>
            <p>Jl. A YANI No.128, Banjarbaru</p>
            <p>Telp: (0281) 641629 | Email: smktelkom-bjb@telkom.co.id</p>
        </div>
    </div>
    <!-- Nomor Surat -->
    <div class="nomor-surat">
        Nomor: {{ $nomor_surat ?? '___/PERM-PKL/SMK-TELKOM/' . date('Y') }}
    </div>

    <!-- Tanggal -->
    <p style="margin: 20px 0;">
        Purwokerto, {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM Y') }}
    </p>

    <!-- Kepada -->
    <p>
        Kepada Yth,<br>
        <strong>{{ $dudi->nama_dudi }}</strong><br>
        {{ $dudi->alamat ?? 'Alamat tidak tersedia' }}<br>
        di Tempat
    </p>

    <!-- Perihal -->
    <p style="margin-top: 20px;">
        <strong>Hal: Permohonan Data Profil Penerimaan PKL</strong>
    </p>

    <!-- Isi Surat -->
    <div class="content">
        <p>Dengan hormat,</p>

        <p>
            Dalam rangka mempersiapkan Program Praktek Kerja Lapangan (PKL) siswa SMK Telkom Purwokerto tahun ajaran
            {{ date('Y') }}/{{ date('Y') + 1 }},
            dengan ini kami memohon kepada Bapak/Ibu untuk dapat memberikan informasi mengenai profil penerimaan PKL di
            {{ $dudi->nama_dudi }}.
        </p>

        <div class="box-request">
            <h3>Data yang Kami Perlukan:</h3>
            <ul>
                <li>
                    <strong>Jurusan yang Diterima</strong><br>
                    <small>Jurusan/kompetensi keahlian siswa apa saja yang dapat diterima untuk melaksanakan PKL
                        (contoh: RPL, TKJ, Multimedia, DKV, PPLG, TJKT)</small>
                </li>
                <li>
                    <strong>Jobdesk Siswa PKL</strong><br>
                    <small>Deskripsi tugas dan tanggung jawab yang akan dikerjakan oleh siswa selama melaksanakan PKL
                        di perusahaan/instansi Bapak/Ibu</small>
                </li>
                <li>
                    <strong>Kuota Penerimaan</strong><br>
                    <small>Jumlah maksimal siswa yang dapat diterima untuk melaksanakan PKL</small>
                </li>
                <li>
                    <strong>Periode PKL</strong><br>
                    <small>Waktu pelaksanaan PKL yang tersedia (opsional)</small>
                </li>
            </ul>
        </div>

        @if ($catatan)
            <p>
                <strong>Catatan Tambahan:</strong><br>
                {{ $catatan }}
            </p>
        @endif

        <p>
            Data yang Bapak/Ibu berikan akan sangat membantu kami dalam proses penempatan siswa PKL sesuai dengan
            kebutuhan perusahaan/instansi dan kompetensi siswa.
        </p>

        <p>
            Bapak/Ibu dapat mengirimkan informasi tersebut melalui:
        </p>
        <ul>
            <li>Email: pkl@smktelkom-pwt.sch.id</li>
            <li>Surat balasan (format bebas)</li>
            <li>Sistem online PKL SMK Telkom Purwokerto</li>
        </ul>

        <p>
            Demikian surat permohonan ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.
        </p>
    </div>

    <!-- Tanda Tangan -->
    <div class="ttd">
        <p>Hormat kami,</p>
        <p><strong>Kepala Sekolah</strong></p>
        <div class="ttd-space"></div>
        <p>
            <strong><u>{{ $kepala_sekolah ?? 'Drs. H. Jarminto Sutopo, M.Pd' }}</u></strong><br>
            NIP. {{ $nip_kepala_sekolah ?? '196505121990031007' }}
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh Sistem Informasi PKL SMK Telkom Purwokerto</p>
    </div>
</body>

</html>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Surat Pengajuan PKL</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            margin: 40px;
        }

        .kop-surat {
            text-align: center;
            margin-bottom: 30px;
        }

        .kop-surat img {
            width: 100%;
            height: auto;
            display: block;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 12px;
            margin-bottom: 28px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            padding-left: 6px;
        }

        .header .logo img {
            height: 80px;
            width: auto;
            display: block;
        }

        .header .brand {
            flex: 1;
            text-align: center;
            transform: translateX(-8px);
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }

        table td {
            border: 1px solid #000;
            padding: 8px;
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
    <!-- Kop Surat -->
    <div class="kop-surat">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/kop-surat.png'))) }}"
            alt="Kop Surat SMK Telkom Banjarbaru">
    </div>

    <!-- Nomor Surat -->
    <div class="nomor-surat">
        Nomor: {{ $nomor_surat ?? '___/PKL/SMK-TELKOM/' . date('Y') }}
    </div>

    <!-- Tanggal -->
    <p style="margin: 20px 0;">
        Banjarbaru, {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM Y') }}
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
        <strong>Hal: Pengajuan Praktek Kerja Lapangan (PKL) Siswa</strong>
    </p>

    <!-- Isi Surat -->
    <div class="content">
        <p>Dengan hormat,</p>

        <p>
            Dalam rangka melaksanakan Program Praktek Kerja Lapangan (PKL) siswa SMK Telkom Banjarbaru tahun ajaran
            {{ date('Y') }}/{{ date('Y') + 1 }},
            bersama ini kami mengajukan permohonan untuk dapat menerima siswa kami melaksanakan PKL di
            {{ $dudi->nama_dudi }}.
        </p>

        <p>
            Adapun siswa yang kami ajukan untuk melaksanakan PKL adalah sebagai berikut:
        </p>

        <!-- Tabel Siswa -->
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Nama Siswa</th>
                    <th width="15%">NIS</th>
                    <th width="25%">Jurusan</th>
                    <th width="10%">Kelas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($siswas as $index => $siswa)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $siswa->nama }}</td>
                        <td>{{ $siswa->nis }}</td>
                        <td>{{ $siswa->jurusan }}</td>
                        <td>{{ $siswa->kelas }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p>
            PKL direncanakan akan dilaksanakan pada periode
            <strong>{{ $periode_pkl ?? 'akan ditentukan kemudian' }}</strong>.
        </p>

        @if ($catatan)
            <p>
                <strong>Catatan:</strong><br>
                {{ $catatan }}
            </p>
        @endif

        <p>
            Demikian surat pengajuan ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.
        </p>
    </div>

    <!-- Tanda Tangan -->
    <div class="ttd">
        <p>Hormat kami,</p>
        <p><strong>Kepala Sekolah</strong></p>
        <div class="ttd-space"></div>
        <p>
            <strong><u>{{ $kepala_sekolah ?? 'Drs. H. Bambang Sutopo, M.Pd' }}</u></strong><br>
            NIP. {{ $nip_kepala_sekolah ?? '196505121990031007' }}
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh Sistem Informasi PKL SMK Telkom Banjarbaru</p>
    </div>
</body>

</html>

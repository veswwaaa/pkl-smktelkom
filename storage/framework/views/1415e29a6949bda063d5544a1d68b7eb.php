<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Balasan Permohonan Data PKL</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            margin: 40px;
        }

        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-surat h2 {
            margin: 0;
            font-size: 18pt;
            font-weight: bold;
        }

        .kop-surat p {
            margin: 2px 0;
            font-size: 11pt;
        }

        .nomor-surat {
            margin: 20px 0;
        }

        .penerima {
            margin: 20px 0;
        }

        .isi-surat {
            text-align: justify;
            margin: 20px 0;
        }

        .isi-surat p {
            margin-bottom: 15px;
        }

        .data-penerimaan {
            margin: 20px 0;
        }

        .jurusan-item {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .jurusan-item h4 {
            margin: 10px 0 5px 0;
            padding: 5px;
            background-color: #f0f0f0;
            border-left: 4px solid #dc3545;
        }

        .detail-item {
            margin-left: 20px;
            margin-bottom: 10px;
        }

        .detail-item strong {
            display: inline-block;
            width: 150px;
        }

        .ttd {
            margin-top: 40px;
            text-align: right;
        }

        .ttd-content {
            display: inline-block;
            text-align: center;
        }

        .ttd-space {
            height: 80px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        table td {
            padding: 5px;
            vertical-align: top;
        }

        .label {
            width: 150px;
        }
    </style>
</head>

<body>
    <!-- Kop Surat -->
    <div class="kop-surat">
        <h2><?php echo e(strtoupper($dudi->nama_dudi)); ?></h2>
        <p><?php echo e($dudi->alamat); ?></p>
        <p>Telp: <?php echo e($dudi->nomor_telpon ?? '-'); ?></p>
    </div>

    <!-- Nomor Surat -->
    <div class="nomor-surat">
        <table>
            <tr>
                <td class="label">Nomor</td>
                <td>: <?php echo e($surat->nomor_surat_permohonan ?? '-'); ?>/BALASAN/<?php echo e(date('Y')); ?></td>
            </tr>
            <tr>
                <td class="label">Lampiran</td>
                <td>: -</td>
            </tr>
            <tr>
                <td class="label">Perihal</td>
                <td>: <strong>Balasan Data Penerimaan PKL</strong></td>
            </tr>
        </table>
    </div>

    <!-- Penerima -->
    <div class="penerima">
        <p>Kepada Yth.<br>
            <strong>Admin SMK Telkom Banjarbaru</strong><br>
            Di tempat
        </p>
    </div>

    <!-- Isi Surat -->
    <div class="isi-surat">
        <p>Dengan hormat,</p>

        <p>Sehubungan dengan surat permohonan data penerimaan PKL dari SMK Telkom Banjarbaru, dengan ini kami sampaikan
            informasi mengenai penerimaan siswa PKL di perusahaan kami sebagai berikut:</p>
    </div>

    <!-- Data Penerimaan PKL -->
    <div class="data-penerimaan">
        <h3 style="margin-bottom: 15px;">DATA PENERIMAAN SISWA PKL</h3>

        <?php $__currentLoopData = $dataPenerimaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jurusan => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="jurusan-item">
                <h4><?php echo e($jurusan); ?>

                    <?php if($jurusan == 'RPL'): ?>
                        (Rekayasa Perangkat Lunak)
                    <?php elseif($jurusan == 'DKV'): ?>
                        (Desain Komunikasi Visual)
                    <?php elseif($jurusan == 'ANM'): ?>
                        (Animasi)
                    <?php elseif($jurusan == 'TKJ'): ?>
                        (Teknik Komputer dan Jaringan)
                    <?php elseif($jurusan == 'TJAT'): ?>
                        (Teknik Jaringan Akses Telekomunikasi)
                    <?php endif; ?>
                </h4>
                <div class="detail-item">
                    <strong>Kuota Penerimaan:</strong> <?php echo e($data['kuota']); ?> Siswa
                </div>
                <div class="detail-item">
                    <strong>Jobdesk/Tugas:</strong>
                    <div style="margin-left: 150px; margin-top: 5px;">
                        <?php echo e($data['jobdesk']); ?>

                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php if($catatan): ?>
        <div class="isi-surat">
            <p><strong>Catatan Tambahan:</strong></p>
            <p><?php echo e($catatan); ?></p>
        </div>
    <?php endif; ?>

    <div class="isi-surat">
        <p>Demikian surat balasan ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>
    </div>

    <!-- Tanda Tangan -->
    <div class="ttd">
        <div class="ttd-content">
            <p><?php echo e($dudi->alamat); ?>, <?php echo e(date('d F Y')); ?></p>
            <p><strong><?php echo e($dudi->nama_dudi); ?></strong></p>
            <div class="ttd-space"></div>
            <p><strong><u><?php echo e($dudi->person_in_charge ?? 'Pimpinan'); ?></u></strong></p>
            <p>Person In Charge</p>
        </div>
    </div>
</body>

</html>
<?php /**PATH C:\laragon\www\pkl-smktelkom\resources\views/pdf/surat-balasan-permohonan.blade.php ENDPATH**/ ?>
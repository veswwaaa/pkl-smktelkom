<?php

namespace App\Exports;

use App\Models\PengajuanPkl;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengajuanApprovedExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
     * Ambil data pengajuan yang sudah approved
     */
    public function collection()
    {
        return PengajuanPkl::with([
            'siswa',
            'dudiPilihan1',
            'dudiPilihan2',
            'dudiPilihan3',
            'dudiMandiriPilihan1.dudi',
            'dudiMandiriPilihan2.dudi',
            'dudiMandiriPilihan3.dudi'
        ])
            ->where('status', 'approved')
            ->whereHas('siswa') // Pastikan relasi siswa ada
            ->orderBy('tanggal_pengajuan', 'desc')
            ->get()
            ->filter(function ($pengajuan) {
                // Filter hanya yang memiliki data siswa lengkap
                return $pengajuan->siswa &&
                    $pengajuan->siswa->nis &&
                    $pengajuan->siswa->nama;
            });
    }

    /**
     * Mapping data untuk setiap row
     */
    public function map($pengajuan): array
    {
        static $counter = 0;
        $counter++;

        // Tentukan DUDI berdasarkan pilihan aktif
        $pilihanAktif = $pengajuan->pilihan_aktif;
        $dudi = null;
        $namaDudi = '-';
        $alamatDudi = '-';
        $nomorTeleponDudi = '-';

        // Cek apakah PKL di sekolah
        if ($pilihanAktif === 'SMK Telkom Banjarbaru') {
            $namaDudi = 'SMK Telkom Banjarbaru';
            $alamatDudi = 'Jl. Telkom, Banjarbaru';
            $nomorTeleponDudi = '-';
        } else {
            // Ambil DUDI berdasarkan pilihan aktif
            if ($pilihanAktif == '1') {
                $dudi = $pengajuan->dudiPilihan1;
                // Jika null, coba dari DUDI Mandiri yang sudah punya akun
                if (!$dudi && $pengajuan->dudiMandiriPilihan1 && $pengajuan->dudiMandiriPilihan1->dudi) {
                    $dudi = $pengajuan->dudiMandiriPilihan1->dudi;
                }
            } elseif ($pilihanAktif == '2') {
                $dudi = $pengajuan->dudiPilihan2;
                if (!$dudi && $pengajuan->dudiMandiriPilihan2 && $pengajuan->dudiMandiriPilihan2->dudi) {
                    $dudi = $pengajuan->dudiMandiriPilihan2->dudi;
                }
            } elseif ($pilihanAktif == '3') {
                $dudi = $pengajuan->dudiPilihan3;
                if (!$dudi && $pengajuan->dudiMandiriPilihan3 && $pengajuan->dudiMandiriPilihan3->dudi) {
                    $dudi = $pengajuan->dudiMandiriPilihan3->dudi;
                }
            }

            // Set data DUDI
            if ($dudi) {
                $namaDudi = $dudi->nama_dudi ?? '-';
                $alamatDudi = $dudi->alamat ?? '-';
                $nomorTeleponDudi = $dudi->nomor_telpon ?? '-';
            }
        }

        return [
            $counter,
            $pengajuan->siswa->nis ?? '-',
            $pengajuan->siswa->nama ?? '-',
            $pengajuan->siswa->kelas ?? '-',
            $pengajuan->siswa->jurusan ?? '-',
            $namaDudi,
            $alamatDudi,
            $nomorTeleponDudi,
        ];
    }

    /**
     * Heading untuk kolom Excel
     */
    public function headings(): array
    {
        return [
            'No',
            'NIS',
            'Nama Siswa',
            'Kelas',
            'Jurusan',
            'Tempat PKL',
            'Alamat',
            'Nomor Telepon',
        ];
    }

    /**
     * Styling untuk Excel
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk header
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'DC2626'] // Warna merah sesuai tema
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}

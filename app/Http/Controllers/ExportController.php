<?php

namespace App\Http\Controllers;

use App\Models\LabSubmersible;
use App\Models\Submersible;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportCsv($scale)
    {
        // Keep bookmarked PV exports consistent with the dashboard's six columns.
        if ($scale === 'lab-scale') {
            return response()->stream(function () {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['Waktu', 'Tegangan (V)', 'Arus (A)', 'Daya (W)', 'Suhu (°C)', 'Est. irradiansi (W/m²)']);
                LabSubmersible::orderBy('id')->chunkById(1000, function ($rows) use ($handle) {
                    foreach ($rows as $row) {
                        fputcsv($handle, [
                            $row->created_at?->format('d/m/Y H:i:s') ?? '-',
                            $row->tegangan,
                            $row->tegangan > 0 ? round($row->daya / $row->tegangan, 2) : 0,
                            $row->daya,
                            $row->suhu,
                            $row->estimasi_irradiansi,
                        ]);
                    }
                });
                fclose($handle);
            }, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="submersible-data_lab-scale.csv"',
            ]);
        }

        // 1. Tentukan Model dan Filename di awal berdasarkan parameter $scale
        if ($scale === "living-lab") {
            $model = new Submersible();
            $filename = 'submersible-data_living-lab.csv';
        } else {
            $model = new LabSubmersible();
            $filename = 'submersible-data_lab-scale.csv';
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        // 2. Gunakan 'use' agar $model bisa masuk ke dalam scope closure
        $callback = function () use ($model) {
            $handle = fopen('php://output', 'w');

            // Header CSV
            fputcsv($handle, [
                'Tegangan (V)',
                'Daya (W)',
                'Energi Harian (Wh)',
                'Energi Total (Wh)',
                'Debit (l/min)',
                'Volume Harian (l)',
                'Volume Total (l)',
                'Durasi Pemakaian Harian (s)',
                'Durasi Pemakaian Total (s)',
                'Intensitas Cahaya (Lux)',
                'Suhu (C)',
                'Suhu Harian (C)',
                'Durasi Koneksi (s)',
                'Created At'
            ]);

            // 3. Ambil data dengan chunking (lebih aman untuk memory jika data banyak)
            $model->chunk(1000, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $row->tegangan,
                        $row->daya,
                        $row->energi_harian,
                        $row->energi_total,
                        $row->debit ?? '-', // Gunakan null coalescing jika kolom tidak ada
                        $row->volume_harian ?? '-',
                        $row->volume_total ?? '-',
                        $row->durasi_pemakaian_harian,
                        $row->durasi_pemakaian_total,
                        $row->intensitas_cahaya,
                        $row->suhu,
                        $row->suhu_harian,
                        $row->durasi_koneksi,
                        $row->created_at ? $row->created_at->format('d-m-Y H:i') : '-'
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}

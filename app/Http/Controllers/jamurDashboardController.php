<?php

namespace App\Http\Controllers;

use App\Models\Jamur;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class jamurDashboardController extends Controller
{
    // Rentang ideal budidaya jamur tiram
    const SUHU_MIN = 22;
    const SUHU_MAX = 28;
    const RH_MIN   = 80;
    const RH_MAX   = 90;

    /**
     * Ambil data terbaru + hitung status kondisi & saran tindakan.
     */
    function getLatestData(): mixed
    {
        try {
            $latest = Jamur::getLatestData();
        } catch (QueryException $exception) {
            report($exception);
            return null;
        }

        if (!$latest) {
            return null;
        }

        // ---- Status online / offline ----
        if ($latest->created_at) {
            $timeDif = $latest->created_at->diffInSeconds(Carbon::now());
            $latest->online_status = $timeDif > 300 ? "Offline" : "Online";
            $latest->waktu_update  = $latest->created_at->format('H:i, d M Y');
        } else {
            $latest->online_status = "Offline";
            $latest->waktu_update  = "-";
        }

        $latest->status_message = $latest->online_status === "Online"
            ? "Alat terhubung, data diperbarui otomatis"
            : "Alat tidak mengirim data lebih dari 5 menit";

        $suhu = $latest->suhu_node1;
        $rh   = $latest->kelembaban_node1;

        // ---- Status suhu ----
        if ($suhu === null) {
            $latest->suhu_status = "-";
            $latest->suhu_warna  = "abu";
        } elseif ($suhu > self::SUHU_MAX) {
            $latest->suhu_status = "Terlalu Panas";
            $latest->suhu_warna  = "merah";
        } elseif ($suhu < self::SUHU_MIN) {
            $latest->suhu_status = "Terlalu Dingin";
            $latest->suhu_warna  = "biru";
        } else {
            $latest->suhu_status = "Ideal";
            $latest->suhu_warna  = "hijau";
        }

        // ---- Status kelembaban ----
        if ($rh === null) {
            $latest->rh_status = "-";
            $latest->rh_warna  = "abu";
        } elseif ($rh < self::RH_MIN) {
            $latest->rh_status = "Terlalu Kering";
            $latest->rh_warna  = "oranye";
        } elseif ($rh > self::RH_MAX) {
            $latest->rh_status = "Terlalu Lembap";
            $latest->rh_warna  = "biru";
        } else {
            $latest->rh_status = "Ideal";
            $latest->rh_warna  = "hijau";
        }

        // ---- Saran tindakan (penyemprotan manual) ----
        if ($rh === null) {
            $latest->saran        = "Menunggu data";
            $latest->saran_detail = "Belum ada pembacaan sensor";
            $latest->saran_warna  = "abu";
        } elseif ($rh < self::RH_MIN) {
            $latest->saran        = "Perlu Disemprot";
            $latest->saran_detail = "Kelembaban di bawah 80%, segera semprot kumbung";
            $latest->saran_warna  = "oranye";
        } elseif ($rh > self::RH_MAX) {
            $latest->saran        = "Jangan Disemprot";
            $latest->saran_detail = "Kelembaban sudah di atas 90%, cukup dulu";
            $latest->saran_warna  = "biru";
        } else {
            $latest->saran        = "Belum Perlu";
            $latest->saran_detail = "Kelembaban masih ideal, belum perlu disemprot";
            $latest->saran_warna  = "hijau";
        }

        // ---- Kesimpulan keseluruhan ----
        $latest->kondisi_ideal = ($latest->suhu_status === "Ideal" && $latest->rh_status === "Ideal");

        if ($suhu === null || $rh === null) {
            $latest->kondisi_judul = "Menunggu pembacaan lengkap";
            $missing = [];
            if ($suhu === null) { $missing[] = "suhu"; }
            if ($rh === null) { $missing[] = "kelembapan"; }
            $latest->kondisi_detail = "Data " . implode(" dan ", $missing) . " belum tersedia.";
        } elseif ($latest->kondisi_ideal) {
            $latest->kondisi_judul = "Kondisi kumbung berada dalam rentang ideal";
            $latest->kondisi_detail = "Suhu dan kelembapan sesuai rentang acuan jamur tiram.";
        } else {
            $latest->kondisi_judul = "Kondisi kumbung perlu perhatian";
            $issues = [];
            if ($suhu > self::SUHU_MAX) {
                $issues[] = "Suhu di atas rentang ideal (22–28 °C)";
            } elseif ($suhu < self::SUHU_MIN) {
                $issues[] = "Suhu di bawah rentang ideal (22–28 °C)";
            }
            if ($rh < self::RH_MIN) {
                $issues[] = "Kelembapan di bawah rentang ideal (80–90%)";
            } elseif ($rh > self::RH_MAX) {
                $issues[] = "Kelembapan di atas rentang ideal (80–90%)";
            }
            $latest->kondisi_detail = implode(". ", $issues) . ".";
        }

        return $latest;
    }

    // Halaman utama dashboard jamur
    public function index()
    {
        $latest = $this->getLatestData();
        return view('jamur-dashboard', compact('latest'));
    }

    // Halaman penjelasan budidaya dan sistem pemantauan jamur.
    public function explanation()
    {
        return view('jamur-penjelasan');
    }

    // Endpoint AJAX: angka terbaru
    public function getDashboardData()
    {
        return response()->json([
            "data" => $this->getLatestData()
        ]);
    }

    // Endpoint AJAX: data grafik
    public function chartData($data)
    {
        $data_select = [
            "suhu"       => ["suhu_node1", "created_at"],
            "kelembaban" => ["kelembaban_node1", "created_at"],
            "semua"      => ["suhu_node1", "kelembaban_node1", "created_at"],
        ];

        $columns = $data_select[$data] ?? $data_select["semua"];

        $latestDate = Jamur::max("created_at");
        $latestDate = $latestDate ? date('Y-m-d', strtotime($latestDate)) : now()->format('Y-m-d');

        $chart = Jamur::select($columns)
            ->whereDate("created_at", $latestDate)
            ->get();

        return response()->json(["data" => $chart]);
    }

    /**
     * Export seluruh data jamur ke file CSV.
     */
    public function exportCsv(Request $request)
    {
        // Rentang tanggal opsional: ?start=YYYY-MM-DD&end=YYYY-MM-DD
        $start = $request->query('start');
        $end   = $request->query('end');

        $namaRentang = ($start || $end)
            ? '_' . ($start ?: 'awal') . '_sd_' . ($end ?: 'akhir')
            : '_semua';

        $filename = 'data_jamur' . $namaRentang . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ];

        $callback = function () use ($start, $end) {
            $file = fopen('php://output', 'w');

            // BOM agar karakter Indonesia terbaca benar di Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'No', 'Tanggal', 'Waktu', 'Suhu (C)', 'Kelembaban (%)',
                'Status Suhu', 'Status Kelembaban', 'Saran'
            ]);

            $no = 1;
            $query = Jamur::orderBy('created_at', 'asc');
            if ($start) { $query->whereDate('created_at', '>=', $start); }
            if ($end)   { $query->whereDate('created_at', '<=', $end); }

            $query->chunk(500, function ($rows) use ($file, &$no) {
                foreach ($rows as $r) {
                    $suhu = $r->suhu_node1;
                    $rh   = $r->kelembaban_node1;

                    $sSuhu = $suhu === null ? '-'
                        : ($suhu > self::SUHU_MAX ? 'Terlalu Panas'
                        : ($suhu < self::SUHU_MIN ? 'Terlalu Dingin' : 'Ideal'));

                    $sRh = $rh === null ? '-'
                        : ($rh < self::RH_MIN ? 'Terlalu Kering'
                        : ($rh > self::RH_MAX ? 'Terlalu Lembap' : 'Ideal'));

                    $saran = $rh === null ? '-'
                        : ($rh < self::RH_MIN ? 'Perlu Disemprot'
                        : ($rh > self::RH_MAX ? 'Jangan Disemprot' : 'Belum Perlu'));

                    fputcsv($file, [
                        $no++,
                        $r->created_at ? $r->created_at->format('d/m/Y') : '-',
                        $r->created_at ? $r->created_at->format('H:i:s') : '-',
                        $suhu ?? '-',
                        $rh ?? '-',
                        $sSuhu,
                        $sRh,
                        $saran,
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

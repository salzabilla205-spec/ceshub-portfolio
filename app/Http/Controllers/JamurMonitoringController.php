<?php

namespace App\Http\Controllers;

use App\Models\Jamur;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class JamurMonitoringController extends Controller
{
    public function index(Request $request)
    {
        [$from, $to] = $this->period($request);
        $databaseError = false;
        try {
            $history = Jamur::whereBetween('created_at', [$from, $to])->orderByDesc('created_at')
                ->orderByDesc('id')->paginate(25)->withQueryString();
        } catch (QueryException $exception) {
            report($exception);
            $databaseError = true;
            $history = new LengthAwarePaginator([], 0, 25, 1, ['path' => $request->url()]);
        }
        $project = Project::where('status', 'published')->where(function ($query) {
            $query->where('slug', 'jamur')->orWhere('dashboard_url', '/dashboard/jamur')
                ->orWhere('dashboard_url', 'like', '%/dashboard/jamur')
                ->orWhere('dashboard_url', '/riset/jamur')->orWhere('dashboard_url', 'like', '%/riset/jamur');
        })->with(['teamMembers', 'posts' => fn ($query) => $query->where('status', 'published')->latest('published_at')])->first();
        return view('jamur-dashboard', compact('history', 'from', 'to', 'project', 'databaseError'));
    }

    public function data()
    {
        try {
            $latest = Jamur::latest('created_at')->latest('id')->first();
        } catch (QueryException $exception) {
            report($exception);
            return response()->json(['message' => 'Database Jamur belum dapat diakses.'], 503);
        }
        $age = $latest?->created_at?->diffInSeconds(now(), false);
        return response()->json([
            'reading' => $latest ? $this->reading($latest) : null,
            'online' => $age !== null && $age >= -60 && $age <= 300,
        ])->header('Cache-Control', 'no-store');
    }

    public function readings(Request $request)
    {
        if ($request->boolean('latest')) {
            $rows = Jamur::orderByDesc('created_at')->orderByDesc('id')->limit(60)->get()->reverse()->values()->map(fn ($row) => $this->reading($row));
            return response()->json(['rows' => $rows])->header('Cache-Control', 'no-store');
        }

        [$from, $to] = $this->period($request);
        try {
            $query = Jamur::whereBetween('created_at', [$from, $to]);
            if ((clone $query)->count() > 10000) {
                throw ValidationException::withMessages(['from' => 'Rentang melebihi 10.000 pembacaan. Pilih rentang lebih pendek untuk grafik dan ekspor.']);
            }
            $rows = $query->orderBy('created_at')->orderBy('id')->get()->map(fn ($row) => $this->reading($row));
        } catch (QueryException $exception) {
            report($exception);
            return response()->json(['message' => 'Database Jamur belum dapat diakses.'], 503);
        }
        return response()->json([
            'from' => $from->format('d/m/Y H:i:s'), 'to' => $to->format('d/m/Y H:i:s'),
            'timezone' => config('app.timezone'), 'rows' => $rows,
        ]);
    }

    private function period(Request $request): array
    {
        $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d\TH:i'],
            'to' => ['nullable', 'date_format:Y-m-d\TH:i', 'after_or_equal:from'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);
        $from = $request->filled('from') ? Carbon::parse($request->input('from')) : now()->startOfDay();
        $to = $request->filled('to') ? Carbon::parse($request->input('to'))->endOfMinute() : now()->endOfDay();
        if ($to->lt($from)) throw ValidationException::withMessages(['to' => 'Waktu akhir harus sesudah waktu mulai.']);
        return [$from, $to];
    }

    private function reading(Jamur $row): array
    {
        return [
            'time' => $row->created_at->format('d/m/Y H:i:s'),
            'label' => $row->created_at->format('d/m H:i'),
            'suhu' => $row->suhu_node1 === null ? null : (float) $row->suhu_node1,
            'kelembapan' => $row->kelembaban_node1 === null ? null : (float) $row->kelembaban_node1,
        ];
    }
}

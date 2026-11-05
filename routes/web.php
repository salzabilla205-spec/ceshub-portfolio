<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\labDashboardController;
use App\Http\Controllers\jamurDashboardController;
use App\Http\Controllers\hessDashboardController;
use App\Http\Controllers\DigitalTwinController;
use App\Http\Controllers\DashboardPengaturanController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\espController;

Route::get('/test-route-cek', fn() => 'ROUTE INI AKTIF');
Route::get('/', [ProjectController::class, 'home'])->name('home');

// ===== Riset & Tim =====
Route::get('/riset', [ProjectController::class, 'index'])->name('riset.index');

// Route Statis (Wajib ditaruh di ATAS wildcard {project:slug})
Route::get('/riset/hess', [hessDashboardController::class, 'index'])->name('riset.hess');
Route::get('/riset/digital-twin', [DigitalTwinController::class, 'index'])->name('riset.digital-twin');
Route::get('/riset/jamur', [\App\Http\Controllers\JamurMonitoringController::class, 'index'])->name('riset.jamur');

// Route Wildcard Database
Route::get('/riset/{project:slug}', [ProjectController::class, 'show'])->name('riset.show');
Route::get('/tim', [ProjectController::class, 'tim'])->name('tim.index');
Route::get('/dashboard/tim/{project}', [ProjectController::class, 'projectTim'])->name('dashboard.tim.project');

// ===== Dashboard Pengaturan (login per-project) =====
Route::get('/dashboard/pengaturan/{slug}', [DashboardPengaturanController::class, 'index'])->name('dashboard.pengaturan');
Route::post('/dashboard/pengaturan/{slug}/login', [DashboardPengaturanController::class, 'login'])->middleware('throttle:5,1')->name('dashboard.pengaturan.login');
Route::post('/dashboard/pengaturan/{slug}/logout', [DashboardPengaturanController::class, 'logout'])->name('dashboard.pengaturan.logout');
Route::post('/dashboard/pengaturan/{slug}/catatan', [\App\Http\Controllers\ProjectNoteController::class, 'store'])->middleware('throttle:30,1')->name('dashboard.notes.store');
Route::put('/dashboard/pengaturan/{slug}/catatan/{note}', [\App\Http\Controllers\ProjectNoteController::class, 'update'])->whereNumber('note')->middleware('throttle:30,1')->name('dashboard.notes.update');

// ===== Dashboard - Living Lab =====
Route::redirect('/dashboard', '/dashboard/smart-water-pump');
Route::get('/dashboard/smart-water-pump', [\App\Http\Controllers\SmartWaterPumpController::class, 'index'])->name('dashboard.living-lab');
Route::get('/dashboard/smart-water-pump/monitoring', [\App\Http\Controllers\SmartWaterPumpController::class, 'data'])->name('water-pump.monitoring');
Route::get('/dashboard/smart-water-pump/readings', [\App\Http\Controllers\SmartWaterPumpController::class, 'readings'])->name('water-pump.readings');
Route::get('/dashboard/data', [dashboardController::class, 'getDashboardData']);
Route::get('/dashboard/chart/{data}', [dashboardController::class, 'chartData']);
Route::get('/dashboard/heatmap', [dashboardController::class, 'heatMapData']);
Route::post('/dashboard/pump-activation/{scale?}', [dashboardController::class, 'pumpActive'])->middleware(\App\Http\Middleware\EnsureProjectControl::class.':route')->name('pumpActivation');

// ===== Dashboard - Lab Scale =====
Route::get('/dashboard/lab-scale', [\App\Http\Controllers\LabMonitoringController::class, 'index'])->name('dashboard.lab-scale');
Route::get('/dashboard/lab-scale/monitoring', [\App\Http\Controllers\LabMonitoringController::class, 'data'])->name('lab.monitoring');
Route::get('/dashboard/lab-scale/readings', [\App\Http\Controllers\LabMonitoringController::class, 'readings'])->name('lab.readings');
Route::get('/dashboard/lab-scale/data', [labDashboardController::class, 'getDashboardData']);
Route::get('/dashboard/lab-scale/chart/{data}', [labDashboardController::class, 'chartData']);
Route::get('/dashboard/lab-scale/history', [\App\Http\Controllers\LabMonitoringController::class, 'readings']);
Route::get('/dashboard/lab-scale/heatmap', [labDashboardController::class, 'heatMapData']);
Route::post('/dashboard/lab-scale/toggle/{lampu}', [labDashboardController::class, 'toggleRelay'])->middleware(\App\Http\Middleware\EnsureProjectControl::class.':lab-scale')->name('lab-scale.toggle');
Route::get(
    '/api/lab-scale/logging',
    [espController::class, 'labLogging']
);

Route::get(
    '/api/lab-scale/relay-command',
    [labDashboardController::class, 'relayCommand']
);

Route::get('/export/{scale}', [ExportController::class, 'exportCsv'])->name('exportCsv');

// ===== Dashboard - Jamur =====
Route::get('/dashboard/jamur', [\App\Http\Controllers\JamurMonitoringController::class, 'index'])->name('dashboard.jamur');
Route::get('/dashboard/jamur/data', [jamurDashboardController::class, 'getDashboardData']);
Route::get('/dashboard/jamur/chart/{data}', [jamurDashboardController::class, 'chartData']);
Route::get('/dashboard/jamur/export', [jamurDashboardController::class, 'exportCsv']);

// ===== Dashboard - HESS =====
Route::get('/dashboard/hess', [hessDashboardController::class, 'index'])->name('dashboard.hess'); // Backup redirect alias
Route::get('/riset/hess/data', [hessDashboardController::class, 'getDashboardData']);
Route::get('/riset/hess/chart/all', [hessDashboardController::class, 'chartAll']);
Route::get('/riset/hess/export', [hessDashboardController::class, 'exportCsv']);
Route::get('/dashboard/hess/data', [hessDashboardController::class, 'getDashboardData']); // Alias data
Route::get('/dashboard/hess/chart/all', [hessDashboardController::class, 'chartAll']);
Route::get('/dashboard/hess/export', [hessDashboardController::class, 'exportCsv']);
//Route::post('/api/hess/telemetry', [hessDashboardController::class, 'storeTelemetry']);

// ===== Dashboard - Digital Twin =====
Route::get('/dashboard/digital-twin', [DigitalTwinController::class, 'index'])->name('dashboard.digital-twin');
Route::get('/dashboard/digital-twin/chart', [DigitalTwinController::class, 'getChartData']);
Route::post('/api/digital-twin/data', [DigitalTwinController::class, 'storeData']);

Route::get('/dashboard/jamur/monitoring', [\App\Http\Controllers\JamurMonitoringController::class, 'data'])->name('jamur.monitoring');
Route::get('/dashboard/jamur/readings', [\App\Http\Controllers\JamurMonitoringController::class, 'readings'])->name('jamur.readings');
Route::get('/dashboard/{project}/readings-history', [\App\Http\Controllers\DashboardHistoryController::class, 'readings'])->whereIn('project', ['hess', 'digital-twin']);

Route::get('/admin/barang/template', \App\Http\Controllers\InventoryTemplateController::class)->middleware(\App\Http\Middleware\EnsureCesAdmin::class)->name('inventory.template');

// QR inventory: guests may record stock out; receiving and returns stay admin-only.
Route::get('/barang', [\App\Http\Controllers\PublicInventoryController::class, 'index'])->name('inventory.public');
Route::get('/barang/qr-cetak', [\App\Http\Controllers\PublicInventoryController::class, 'labels'])->name('inventory.public.labels');
Route::get('/barang/bukti/{checkout}', [\App\Http\Controllers\PublicInventoryController::class, 'receipt'])->whereUuid('checkout')->name('inventory.public.receipt');
Route::get('/barang/rak/{rack}', [\App\Http\Controllers\PublicInventoryController::class, 'rack'])->name('inventory.public.rack');
Route::post('/barang/rak/{rack}', [\App\Http\Controllers\PublicInventoryController::class, 'store'])->middleware('throttle:20,1')->name('inventory.public.store');

Route::middleware(\App\Http\Middleware\EnsureCesAdmin::class)->group(function () {
    Route::get('/admin/barang/pakai', [\App\Http\Controllers\InventoryUsageController::class, 'index'])->name('inventory.usage');
    Route::post('/admin/barang/pakai', [\App\Http\Controllers\InventoryUsageController::class, 'store'])->middleware('throttle:30,1')->name('inventory.usage.store');
});

// Only the new host opts into public monitoring reads from the old receiver.
foreach (Route::getRoutes() as $monitoringRoute) {
    if (in_array($monitoringRoute->uri(), [...config('monitoring-source.routes', []), 'dashboard/pump-activation/{scale?}', 'dashboard/lab-scale/toggle/{lampu}'], true)) {
        $monitoringRoute->middleware(\App\Http\Middleware\ReadLegacyMonitoring::class);
    }
}

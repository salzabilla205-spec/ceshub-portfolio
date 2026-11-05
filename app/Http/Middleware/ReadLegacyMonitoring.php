<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReadLegacyMonitoring
{
    public function handle(Request $request, Closure $next)
    {
        $source = rtrim((string) config('monitoring-source.url'), '/');
        if ($source === '' || $request->getHost() !== 'ceshub.id') {
            return $next($request);
        }

        // Fixed, user-owned source. Never forward browser cookies, auth headers,
        // login pages, device writes, arbitrary destinations, or arbitrary paths.
        abort_unless($source === 'https://cespv.com', 503, 'Sumber monitoring belum dikonfigurasi dengan benar.');
        // Switch migrated project reads and exports. Existing actuator routes remain
        // on the old control path until their separate device cutover.
        $localPv = config('monitoring-source.pv_local', false)
            && $request->is('dashboard/lab-scale', 'dashboard/lab-scale/*', 'export/lab-scale');
        $localHess = config('monitoring-source.hess_local', false)
            && $request->is('dashboard/hess', 'dashboard/hess/*', 'riset/hess', 'riset/hess/*');
        if ($request->isMethod('GET') && ($localPv || $localHess)) {
            $response = $next($request);
            $response->headers->set('X-CES-Data-Source', $localHess ? 'local-hess' : 'local-pv');
            $response->headers->set('Cache-Control', 'private, no-store, max-age=0');
            return $response;
        }
        $route = $request->route();
        if ($route && ! $request->isMethod('GET') && in_array($route->uri(), ['dashboard/pump-activation/{scale?}', 'dashboard/lab-scale/toggle/{lampu}'], true)) {
            $slug = $route->uri() === 'dashboard/lab-scale/toggle/{lampu}' ? 'lab-scale' : ($route->parameter('scale') ?: 'living-lab');
            $controlUrl = 'https://cespv.com/dashboard/pengaturan/'.rawurlencode($slug);
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Gunakan halaman kontrol perangkat di situs lama.', 'control_url' => $controlUrl], 409);
            }
            return redirect()->away($controlUrl, 303);
        }
        if (! $request->isMethod('GET')) {
            return $next($request);
        }
        if (! $route || ! in_array($route->uri(), config('monitoring-source.routes', []), true)) {
            return $next($request);
        }

        $query = $request->only(['from', 'to', 'page', 'latest', 'table', 'date', 'start_date', 'end_date', 'start_time', 'end_time']);
        try {
            $upstream = Http::connectTimeout(3)->timeout(12)
                ->withHeaders(['Accept' => $request->expectsJson() ? 'application/json' : 'text/html,application/json;q=0.9,*/*;q=0.8'])
                ->withOptions(['allow_redirects' => false])
                ->get($source.'/'.$request->path(), $query);
            if ($upstream->successful()) {
                $type = strtolower((string) $upstream->header('Content-Type'));
                if (! str_contains($type, 'json') && ! str_contains($type, 'text/')) {
                    throw new \RuntimeException('Unsupported monitoring response');
                }
                $target = $request->getSchemeAndHttpHost();
                $body = str_replace([$source, str_replace('/', '\\/', $source)], [$target, str_replace('/', '\\/', $target)], $upstream->body());
                if (str_contains($type, 'text/html')) {
                    $body = preg_replace('/(?:Dr\. Ir\. )?Hari Maghfiroh, S\.T\., M\.Eng\.(?:, M\.Sc\.)?/u', 'Dr. Ir. Hari Maghfiroh, S.T., M.Eng., M.Sc.', $body);
                }
                $response = response($body, $upstream->status())
                    ->header('Content-Type', $type)
                    ->header('Cache-Control', 'private, no-store, max-age=0');
                if ($upstream->header('Content-Disposition')) {
                    $response->header('Content-Disposition', $upstream->header('Content-Disposition'));
                }
                return $response;
            }
            if ($upstream->status() === 422) {
                return response($upstream->body(), 422)->header('Content-Type', 'application/json')->header('Cache-Control', 'no-store');
            }
        } catch (\Throwable $exception) {
            report($exception);
        }

        $message = 'Sumber data perangkat belum dapat dihubungi. Coba lagi beberapa saat.';
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 503)->header('Cache-Control', 'no-store');
        }
        return response('<!doctype html><html lang="id"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Monitoring sementara belum tersedia</title><main style="max-width:640px;margin:12vh auto;padding:24px;font-family:system-ui"><h1>Monitoring sementara belum tersedia</h1><p>'.$message.'</p><a href="https://cespv.com/'.e($request->path()).'">Buka monitoring di situs lama</a></main></html>', 503)
            ->header('Cache-Control', 'no-store');
    }
}

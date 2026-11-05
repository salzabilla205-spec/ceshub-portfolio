<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShowMigrationNotice
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Keep device ingestion, private controls, downloads and JSON untouched.
        if (! in_array($request->getHost(), ['cespv.com', 'www.cespv.com'], true)
            || ! $request->isMethod('GET') || $response->getStatusCode() !== 200
            || $request->is('admin', 'admin/*', 'api', 'api/*', 'dashboard/pengaturan', 'dashboard/pengaturan/*', 'export/*')
            || ! str_starts_with(strtolower($response->headers->get('Content-Type', '')), 'text/html')
            || ! method_exists($response, 'getContent')) {
            return $response;
        }

        $html = $response->getContent();
        if (! is_string($html) || ! str_contains(strtolower($html), '</body>')) {
            return $response;
        }

        $notice = view('partials.migration-notice')->render();
        $response->setContent(preg_replace('~</body>~i', $notice.'</body>', $html, 1));
        $response->headers->set('Cache-Control', 'no-store, private');
        $response->headers->remove('Content-Length');

        return $response;
    }
}

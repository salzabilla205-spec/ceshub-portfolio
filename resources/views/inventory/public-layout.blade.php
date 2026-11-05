<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#164e63">
    <title>@yield('title', 'Barang Lab') | CES</title>
    <link rel="stylesheet" href="{{ asset('css/inventory-public.css') }}?v=20261022">
</head>
<body>
<header class="public-header"><a href="{{ route('inventory.public') }}" aria-label="Barang Lab CES"><strong>CES</strong><span>Barang Lab</span></a><a class="text-link" href="{{ route('inventory.public') }}">Semua rak</a></header>
<main class="public-main">@yield('content')</main>
<footer class="public-footer">Control Energy System · Universitas Sebelas Maret</footer>
@stack('scripts')
</body>
</html>

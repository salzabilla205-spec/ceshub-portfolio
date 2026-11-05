<!doctype html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>CES - Living Lab</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

    @include('partials.sidebar-style')

    <style>
        body {
            font-family: 'Inter', sans-serif;
            font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11';
        }
    </style>
</head>

<body class="bg-white text-slate-800 has-sidebar">
    @include('partials.sidebar')

    <div class="app-main" id="app-main">
        <div class="max-w-7xl mx-auto px-6 lg:px-10 py-10 min-h-screen">

            <p class="text-sm text-slate-400 mb-6">
                <a href="{{ url('/') }}" class="hover:text-slate-700">Beranda</a>
                <span class="mx-1">/</span>
                <a href="{{ route('riset.index') }}" class="hover:text-slate-700">Riset &amp; Project</a>
                <span class="mx-1">/</span>
                <a href="{{ route('riset.show', 'living-lab') }}" class="hover:text-slate-700">Living Lab</a>
                <span class="mx-1">/</span>
                <span class="text-slate-600">Monitoring</span>
            </p>

            <section class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-10">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/logo/ces-logo.png') }}" alt="Logo" class="lg:block hidden w-10 h-11">
                    <div>
                        <h1 class="text-xl font-semibold text-slate-900">Dashboard Monitoring</h1>
                        <p class="text-sm text-slate-500 mt-0.5">Pompa Submersible &middot; Masaran, Sragen</p>
                    </div>
                </div>

                <a class="text-white text-sm font-semibold px-4 py-2.5 bg-slate-900 rounded-xl hover:bg-slate-800 transition hidden lg:inline-flex items-center gap-2 w-fit" href="{{ route('exportCsv', 'living-lab') }}">
                    <svg width="18" height="18" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13.5 18L7.875 12.375L9.45 10.7437L12.375 13.6687V4.5H14.625V13.6687L17.55 10.7437L19.125 12.375L13.5 18ZM6.75 22.5C6.13125 22.5 5.60175 22.2799 5.1615 21.8396C4.72125 21.3994 4.50075 20.8695 4.5 20.25V16.875H6.75V20.25H20.25V16.875H22.5V20.25C22.5 20.8687 22.2799 21.3986 21.8396 21.8396C21.3994 22.2806 20.8695 22.5007 20.25 22.5H6.75Z"
                            fill="white" />
                    </svg>
                    Export
                </a>
            </section>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <div class="block lg:flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-slate-900 rounded-xl">
                                <svg width="24" height="24" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M4.08325 15.9576C4.08325 11.0763 8.25992 6.25214 11.1929 3.46731C11.9462 2.73965 12.9526 2.33295 13.9999 2.33295C15.0472 2.33295 16.0537 2.73965 16.8069 3.46731C19.7388 6.25331 23.9166 11.0763 23.9166 15.9576C23.9166 20.7433 20.1611 25.6666 13.9999 25.6666C7.83875 25.6666 4.08325 20.7433 4.08325 15.9576Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M4.6665 14.3313C6.37567 13.8016 9.7905 13.6313 13.9812 15.9856C18.1648 18.3353 21.6018 17.4976 23.3332 16.4908"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h1 class="text-sm font-semibold text-slate-900">Volume Air <br> yang Dipompa</h1>
                        </div>
                        <div class="mt-3 lg:mt-0 w-fit mx-auto lg:mx-0 text-right lg:text-left">
                            <span id="volume_harian" class="text-2xl font-semibold text-slate-900">{{ $latest->volume_harian }}</span>
                            <span class="text-sm text-slate-400">L</span>
                        </div>
                    </div>
                    <hr class="my-4 border-slate-100">
                    <div class="flex justify-between text-sm text-slate-500 py-1">
                        <h1>Debit air</h1>
                        <h1 class="text-slate-900"><span id="debit">{{ $latest->debit }}</span> L/min</h1>
                    </div>
                    <div class="flex justify-between text-sm text-slate-500 py-1">
                        <h1>Kecepatan aliran</h1>
                        <h1 class="text-slate-900"><span id="v_water">{{ number_format($latest->v_water, 2) }}</span> km/j</h1>
                    </div>
                    <div class="flex justify-between text-sm text-slate-500 py-1">
                        <h1>Status operasional</h1>
                        <h1 class="text-slate-900"><span id="status">{{ $latest->status ? "Nyala" : "Mati" }}</span></h1>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <div class="block lg:flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-slate-900 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white"
                                    class="bi bi-brightness-high-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M12 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0M8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0m0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13m8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5M3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8m10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0m-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0m9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707M4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708" />
                                </svg>
                            </div>
                            <h1 class="text-sm font-semibold text-slate-900">Daya Listrik <br> Panel Surya</h1>
                        </div>
                        <div class="mt-3 lg:mt-0 w-fit mx-auto lg:mx-0 text-right lg:text-left">
                            <span id="daya" class="text-2xl font-semibold text-slate-900">{{ $latest->daya }}</span>
                            <span class="text-sm text-slate-400">W</span>
                        </div>
                    </div>
                    <hr class="my-4 border-slate-100">
                    <div class="flex justify-between text-sm text-slate-500 py-1">
                        <h1>Tegangan</h1>
                        <h1 class="text-slate-900"><span id="tegangan">{{ $latest->tegangan }}</span> Volt</h1>
                    </div>
                    <div class="flex justify-between text-sm text-slate-500 py-1">
                        <h1>Arus</h1>
                        <h1 class="text-slate-900"><span id="arus">{{ number_format($latest->arus, 2) }}</span> Amp</h1>
                    </div>
                    <div class="flex justify-between text-sm text-slate-500 py-1">
                        <h1>Daya puncak</h1>
                        <h1 class="text-slate-900">
                            <span id="max_power">{{ $latest->max_power }}</span> Wp
                        </h1>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <div class="block lg:flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-slate-900 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white"
                                    class="bi bi-lightning-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M5.52.359A.5.5 0 0 1 6 0h4a.5.5 0 0 1 .474.658L8.694 6H12.5a.5.5 0 0 1 .395.807l-7 9a.5.5 0 0 1-.873-.454L6.823 9.5H3.5a.5.5 0 0 1-.48-.641z" />
                                </svg>
                            </div>
                            <h1 class="text-sm font-semibold text-slate-900">Penggunaan <br> Energi Listrik</h1>
                        </div>
                        <div class="mt-3 lg:mt-0 w-fit mx-auto lg:mx-0 text-right lg:text-left">
                            <span id="energi_harian" class="text-2xl font-semibold text-slate-900">{{ number_format(($latest->energi_harian / 1000), 2) }}</span>
                            <span class="text-sm text-slate-400">kWh</span>
                        </div>
                    </div>
                    <hr class="my-4 border-slate-100">
                    <div class="flex justify-between text-sm text-slate-500 py-1">
                        <h1>Penghematan biaya</h1>
                        <h1 class="text-slate-900">Rp. <span id="biaya">{{ number_format($latest->biaya, 0, ',', '.') }}</span></h1>
                    </div>
                    <div class="flex justify-between text-sm text-slate-500 py-1">
                        <h1>Reduksi emisi</h1>
                        <h1 class="text-slate-900"><span id="emisi">{{ number_format($latest->emisi, 2) }}</span> kg CO²</h1>
                    </div>
                    <div class="flex justify-between text-sm text-slate-500 py-1">
                        <h1>Durasi operasional</h1>
                        <h1 class="text-slate-900"><span id="durasi_pemakaian_harian">{{ $latest->durasi_pemakaian_harian }}</span> menit</h1>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-6 lg:col-span-2">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex gap-3 items-center">
                            <svg width="28" height="28" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 6C8.93913 6 7.92172 6.42143 7.17157 7.17157C6.42143 7.92172 6 8.93913 6 10V30C6 31.0609 6.42143 32.0783 7.17157 32.8284C7.92172 33.5786 8.93913 34 10 34H30C31.0609 34 32.0783 33.5786 32.8284 32.8284C33.5786 32.0783 34 31.0609 34 30V10C34 8.93913 33.5786 7.92172 32.8284 7.17157C32.0783 6.42143 31.0609 6 30 6H10ZM20 20C20.2652 20 20.5196 20.1054 20.7071 20.2929C20.8946 20.4804 21 20.7348 21 21V27C21 27.2652 20.8946 27.5196 20.7071 27.7071C20.5196 27.8946 20.2652 28 20 28C19.7348 28 19.4804 27.8946 19.2929 27.7071C19.1054 27.5196 19 27.2652 19 27V21C19 20.7348 19.1054 20.4804 19.2929 20.2929C19.4804 20.1054 19.7348 20 20 20ZM12 17C12 16.7348 12.1054 16.4804 12.2929 16.2929C12.4804 16.1054 12.7348 16 13 16C13.2652 16 13.5196 16.1054 13.7071 16.2929C13.8946 16.4804 14 16.7348 14 17V27C14 27.2652 13.8946 27.5196 13.7071 27.7071C13.5196 27.8946 13.2652 28 13 28C12.7348 28 12.4804 27.8946 12.2929 27.7071C12.1054 27.5196 12 27.2652 12 27V17ZM27 12C27.2652 12 27.5196 12.1054 27.7071 12.2929C27.8946 12.4804 28 12.7348 28 13V27C28 27.2652 27.8946 27.5196 27.7071 27.7071C27.5196 27.8946 27.2652 28 27 28C26.7348 28 26.4804 27.8946 26.2929 27.7071C26.1054 27.5196 26 27.2652 26 27V13C26 12.7348 26.1054 12.4804 26.2929 12.2929C26.4804 12.1054 26.7348 12 27 12Z" fill="#0f172a" />
                            </svg>
                            <div class="relative inline-block">
                                <button id="dropdownNavbarLink" class="flex items-center justify-between gap-2 text-base font-semibold text-slate-900">
                                    <span id="selectedMode">Produksi Energi</span>
                                    <svg id="caretIcon" class="transition-transform duration-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
                                    </svg>
                                </button>

                                <div id="dropdownNavbar" class="hidden absolute left-0 z-20 bg-white border border-slate-200 rounded-xl shadow-lg w-80 mt-2">
                                    <ul class="p-2 text-sm font-medium">
                                        <li>
                                            <div class="flex p-2 rounded-lg hover:bg-slate-50">
                                                <div class="flex items-center h-5">
                                                    <input id="helper-radio-1" type="radio" name="mode" value="power" class="w-4 h-4 text-slate-900 bg-white border-slate-300" checked>
                                                </div>
                                                <div class="ms-2 text-sm">
                                                    <label for="helper-radio-1" class="font-medium text-slate-900 cursor-pointer">
                                                        <div>Produksi Energi</div>
                                                        <p class="text-xs font-normal text-slate-500">Daya dan energi listrik yang dihasilkan panel surya</p>
                                                    </label>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="flex p-2 rounded-lg hover:bg-slate-50">
                                                <div class="flex items-center h-5">
                                                    <input id="helper-radio-2" type="radio" name="mode" value="pump" class="w-4 h-4 text-slate-900 bg-white border-slate-300">
                                                </div>
                                                <div class="ms-2 text-sm">
                                                    <label for="helper-radio-2" class="font-medium text-slate-900 cursor-pointer">
                                                        <div>Operasional Pompa</div>
                                                        <p class="text-xs font-normal text-slate-500">Debit dan volume air yang dipompa</p>
                                                    </label>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="flex p-2 rounded-lg hover:bg-slate-50">
                                                <div class="flex items-center h-5">
                                                    <input id="helper-radio-3" type="radio" name="mode" value="environment" class="w-4 h-4 text-slate-900 bg-white border-slate-300">
                                                </div>
                                                <div class="ms-2 text-sm">
                                                    <label for="helper-radio-3" class="font-medium text-slate-900 cursor-pointer">
                                                        <div>Kondisi Lingkungan</div>
                                                        <p class="text-xs font-normal text-slate-500">Suhu dan intensitas cahaya lingkungan</p>
                                                    </label>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="relative" style="height: 400px;">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-slate-900 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" class="bi bi-asterisk"
                                viewBox="0 0 16 16">
                                <path
                                    d="M8 0a1 1 0 0 1 1 1v5.268l4.562-2.634a1 1 0 1 1 1 1.732L10 8l4.562 2.634a1 1 0 1 1-1 1.732L9 9.732V15a1 1 0 1 1-2 0V9.732l-4.562 2.634a1 1 0 1 1-1-1.732L6 8 1.438 5.366a1 1 0 0 1 1-1.732L7 6.268V1a1 1 0 0 1 1-1" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="font-semibold text-slate-900">Riwayat Aktivitas Terakhir</h1>
                            <h2 class="text-sm text-slate-500">4 aktivitas terakhir</h2>
                        </div>
                    </div>

                    @foreach ($logs->take(4) as $log)
                    <hr class="my-4 border-slate-100">
                    <div class="flex items-center gap-4">
                        <h1 class="px-3 py-2 text-xs text-white font-semibold rounded-full bg-slate-900">{{ \Carbon\Carbon::parse($log->date)->format('d/m') }}</h1>
                        <div class="w-full">
                            <h1 class="text-slate-900 text-sm font-medium">{{ \Carbon\Carbon::parse($log->date)->translatedFormat('d F Y') }}</h1>
                            <div class="items-center gap-2 text-xs text-slate-500 hidden lg:flex mt-1">
                                <h1>{{ $log->volume_harian }} L</h1>
                                <h1>&middot;</h1>
                                <h1>{{ $log->durasi_harian ?? 0 }} menit</h1>
                                <h1>&middot;</h1>
                                <h1>{{ $log->energi_harian }} kWh</h1>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-500 lg:hidden mt-2">
                        <h1>{{ $log->volume_harian }} L</h1>
                        <h1>&middot;</h1>
                        <h1>{{ $log->durasi_harian ?? 0 }} menit</h1>
                        <h1>&middot;</h1>
                        <h1>{{ $log->energi_harian }} kWh</h1>
                    </div>
                    @endforeach

                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-slate-900 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white"
                                class="bi bi-brightness-high-fill" viewBox="0 0 16 16">
                                <path
                                    d="M12 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0M8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0m0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13m8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5M3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8m10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0m-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0m9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707M4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708" />
                            </svg>
                        </div>
                        <h1 class="font-semibold text-slate-900">Intensitas Cahaya Lingkungan</h1>
                    </div>

                    <div class="relative flex justify-center items-center pt-3">
                        <canvas id="gaugeChart" width="250" height="150" class="block"></canvas>

                        <div class="absolute inset-0 flex flex-col items-center justify-center" style="margin-top: 25px;">
                            <div class="text-lg font-semibold text-slate-900" id="intensitas_cahaya_card" data-lux="{{ $latest->intensitas_cahaya }}"><span id="intensitas_cahaya" class="text-3xl text-slate-900">{{ number_format(($latest->intensitas_cahaya/1000), 1) }}</span>k</div>
                            <span class="text-sm text-slate-500">Lux</span>
                        </div>
                    </div>
                    <h1 class="text-center text-sm text-slate-500 mt-4">Intensitas cahaya saat ini senilai <span id="intensitas_cahaya_text" class="text-slate-900 font-medium">{{ $latest->intensitas_cahaya }}</span> lux,<br>cukup baik untuk menyalakan pompa</h1>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <div class="block lg:flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-slate-900 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white"
                                    class="bi bi-brightness-high-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M12 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0M8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0m0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13m8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5M3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8m10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0m-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0m9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707M4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708" />
                                </svg>
                            </div>
                            <h1 class="text-sm font-semibold text-slate-900">Suhu Udara <br> Lingkungan</h1>
                        </div>
                        <div class="mt-3 lg:mt-0 w-fit mx-auto lg:mx-0 text-right lg:text-left">
                            <span class="text-2xl font-semibold text-slate-900">{{ $latest->suhu }}</span>
                            <span class="text-sm text-slate-400">°C</span>
                        </div>
                    </div>
                    <hr class="my-4 border-slate-100">
                    <div class="flex justify-between text-sm text-slate-500 py-1">
                        <h1>Rata-rata hari ini</h1>
                        <h1 class="text-slate-900"><span id="suhu_harian">{{ $latest->suhu_harian }}</span> °C</h1>
                    </div>
                    <div class="flex justify-between text-sm text-slate-500 py-1">
                        <h1>Rata-rata minggu ini</h1>
                        <h1 class="text-slate-900"><span id="suhu_mingguan">{{ $latest->suhu_mingguan }}</span> °C</h1>
                    </div>
                    <hr class="my-4 border-slate-100">

                    <div id="heatmap-container" class="grid grid-cols-8 gap-2">

                    </div>

                    <div class="flex items-center justify-center gap-5 pt-4">
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <div class="size-3 rounded-full bg-slate-200"></div>
                            <h1>Dingin</h1>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <div class="size-3 rounded-full bg-emerald-500"></div>
                            <h1>Panas</h1>
                        </div>
                    </div>

                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-slate-900 rounded-xl">
                            <svg width="20" height="20" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_3350_6)">
                                    <path
                                        d="M14 24.5C13.1833 24.5 12.4931 24.218 11.9292 23.6541C11.3653 23.0902 11.0833 22.4 11.0833 21.5833C11.0833 20.7666 11.3653 20.0764 11.9292 19.5125C12.4931 18.9486 13.1833 18.6666 14 18.6666C14.8167 18.6666 15.5069 18.9486 16.0708 19.5125C16.6347 20.0764 16.9167 20.7666 16.9167 21.5833C16.9167 22.4 16.6347 23.0902 16.0708 23.6541C15.5069 24.218 14.8167 24.5 14 24.5ZM7.40833 17.9083L4.95833 15.4C6.10556 14.2527 7.45228 13.3439 8.9985 12.6735C10.5447 12.003 12.2119 11.6674 14 11.6666C15.7881 11.6658 17.4557 12.0061 19.0027 12.6875C20.5497 13.3688 21.896 14.2924 23.0417 15.4583L20.5917 17.9083C19.7361 17.0527 18.7444 16.3819 17.6167 15.8958C16.4889 15.4097 15.2833 15.1666 14 15.1666C12.7167 15.1666 11.5111 15.4097 10.3833 15.8958C9.25555 16.3819 8.26389 17.0527 7.40833 17.9083ZM2.45 12.95L0 10.5C1.78889 8.67218 3.87917 7.24302 6.27083 6.21246C8.6625 5.1819 11.2389 4.66663 14 4.66663C16.7611 4.66663 19.3375 5.1819 21.7292 6.21246C24.1208 7.24302 26.2111 8.67218 28 10.5L25.55 12.95C24.0528 11.4527 22.3176 10.2814 20.3443 9.43596C18.3711 8.59052 16.2563 8.1674 14 8.16663C11.7437 8.16585 9.62928 8.58896 7.65683 9.43596C5.68439 10.283 3.94878 11.4543 2.45 12.95Z"
                                        fill="white" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_3350_6">
                                        <rect width="28" height="28" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <h1 class="font-semibold text-slate-900">Konektivitas Perangkat IoT</h1>
                    </div>

                    <h1 class="text-slate-900 text-2xl font-semibold my-5 text-center" id="online_status">{{ $latest->online_status }}</h1>
                    <h2 class="text-center text-sm text-slate-500" id="status_message">{{ $latest->status_message }}</h2>

                    <hr class="my-4 border-slate-100">
                    <div class="flex justify-between text-sm text-slate-500 py-1">
                        <h1>Update data terakhir</h1>
                        <h1 id="created_at_desktop" class="lg:inline-block hidden text-slate-900">{{ \Carbon\Carbon::parse($latest->created_at)->format('H:i:s d/m/Y') }}</h1>
                        <h1 id="created_at_mobile" class="block lg:hidden text-slate-900">{{ \Carbon\Carbon::parse($latest->created_at)->format('H:i d/m') }}</h1>
                    </div>
                    <div class="flex justify-between text-sm text-slate-500 py-1">
                        <h1>Durasi terhubung</h1>
                        <h1 class="text-slate-900"><span id="durasi_koneksi_j">{{ $latest->durasi_koneksi_j }}</span> jam - <span id="durasi_koneksi_m">{{ $latest->durasi_koneksi_m }}</span> menit</h1>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-slate-900 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" class="bi bi-asterisk"
                                viewBox="0 0 16 16">
                                <path
                                    d="M8 0a1 1 0 0 1 1 1v5.268l4.562-2.634a1 1 0 1 1 1 1.732L10 8l4.562 2.634a1 1 0 1 1-1 1.732L9 9.732V15a1 1 0 1 1-2 0V9.732l-4.562 2.634a1 1 0 1 1-1-1.732L6 8 1.438 5.366a1 1 0 0 1 1-1.732L7 6.268V1a1 1 0 0 1 1-1" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="font-semibold text-slate-900">Statistik Total Penggunaan</h1>
                            <h2 class="text-sm text-slate-500 hidden lg:block">Jan 2026 - {{ \Carbon\Carbon::parse($latest->created_at)->translatedFormat('M Y') }}</h2>
                        </div>
                    </div>

                    <h1 class="text-3xl font-semibold text-slate-900 my-6 text-center"> {{ number_format($latest->volume_total/1000, 2) }} <span class="text-lg text-slate-400">m³</span></h1>

                    <hr class="my-3 border-slate-100">
                    <div class="flex justify-between items-center py-2">
                        <div class="flex items-center gap-2 text-sm text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#94a3b8"
                                class="bi bi-stopwatch" viewBox="0 0 16 16">
                                <path d="M8.5 5.6a.5.5 0 1 0-1 0v2.9h-3a.5.5 0 0 0 0 1H8a.5.5 0 0 0 .5-.5z" />
                                <path
                                    d="M6.5 1A.5.5 0 0 1 7 .5h2a.5.5 0 0 1 0 1v.57c1.36.196 2.594.78 3.584 1.64l.012-.013.354-.354-.354-.353a.5.5 0 0 1 .707-.708l1.414 1.415a.5.5 0 1 1-.707.707l-.353-.354-.354.354-.013.012A7 7 0 1 1 7 2.071V1.5a.5.5 0 0 1-.5-.5M8 3a6 6 0 1 0 .001 12A6 6 0 0 0 8 3" />
                            </svg>
                            <h1>Durasi operasional</h1>
                        </div>
                        <h1 class="text-sm text-slate-900">{{ $latest->durasi_pemakaian_total }} jam</h1>
                    </div>
                    <hr class="my-3 border-slate-100">
                    <div class="flex justify-between items-center py-2">
                        <div class="flex items-center gap-2 text-sm text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#94a3b8" class="bi bi-fire"
                                viewBox="0 0 16 16">
                                <path
                                    d="M8 16c3.314 0 6-2 6-5.5 0-1.5-.5-4-2.5-6 .25 1.5-1.25 2-1.25 2C11 4 9 .5 6 0c.357 2 .5 4-2 6-1.25 1-2 2.729-2 4.5C2 14 4.686 16 8 16m0-1c-1.657 0-3-1-3-2.75 0-.75.25-2 1.25-3C6.125 10 7 10.5 7 10.5c-.375-1.25.5-3.25 2-3.5-.179 1-.25 2 1 3 .625.5 1 1.364 1 2.25C11 14 9.657 15 8 15" />
                            </svg>
                            <h1>Reduksi emisi</h1>
                        </div>
                        <h1 class="text-sm text-slate-900">{{ number_format($latest->total_emisi, 2) }} kg CO²</h1>
                    </div>
                    <hr class="my-3 border-slate-100">
                    <div class="flex justify-between items-center py-2">
                        <div class="flex items-center gap-2 text-sm text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#94a3b8" class="bi bi-cash"
                                viewBox="0 0 16 16">
                                <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4" />
                                <path
                                    d="M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2z" />
                            </svg>
                            <h1>Penghematan <span class="hidden lg:inline">biaya</span></h1>
                        </div>
                        <h1 class="text-sm text-slate-900">Rp. {{ number_format($latest->total_biaya, 0, ',', '.') }}</h1>
                    </div>
                    <hr class="my-3 border-slate-100">
                    <div class="flex justify-between items-center py-2">
                        <div class="flex items-center gap-2 text-sm text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#94a3b8"
                                class="bi bi-lightning" viewBox="0 0 16 16">
                                <path
                                    d="M5.52.359A.5.5 0 0 1 6 0h4a.5.5 0 0 1 .474.658L8.694 6H12.5a.5.5 0 0 1 .395.807l-7 9a.5.5 0 0 1-.873-.454L6.823 9.5H3.5a.5.5 0 0 1-.48-.641zM6.374 1 4.168 8.5H7.5a.5.5 0 0 1 .478.647L6.78 13.04 11.478 7H8a.5.5 0 0 1-.474-.658L9.306 1z" />
                            </svg>
                            <h1>Produksi energi</h1>
                        </div>
                        <h1 class="text-sm text-slate-900">{{ number_format(($latest->energi_total / 1000), 2) }} kWh</h1>
                    </div>
                </div>
            </section>

        </div>
    </div>

</body>

<script>
    function formatTanggal(isoString, formatType) {
        const date = new Date(isoString);

        const jam = String(date.getHours()).padStart(2, '0');
        const menit = String(date.getMinutes()).padStart(2, '0');
        const detik = String(date.getSeconds()).padStart(2, '0');
        const tgl = String(date.getDate()).padStart(2, '0');
        const bulan = String(date.getMonth() + 1).padStart(2, '0');
        const tahun = date.getFullYear();

        if (formatType === 'full') {
            return `${jam}:${menit}:${detik} ${tgl}/${bulan}/${tahun}`;
        } else {
            return `${jam}:${menit} ${tgl}/${bulan}`;
        }
    }

    function updateDashboard() {
        const apiUrl = '/dashboard/data';

        fetch(apiUrl)
            .then(response => response.json())
            .then(json => {
                const data = json.data;
                if (!data) {
                    window.location.reload();
                    return;
                }

                document.getElementById('active_message_title').textContent = data.active_message_title;
                document.getElementById('active_message_subtitle').textContent = data.active_message_subtitle;
                document.getElementById('active_message_button').textContent = data.active_message_button;
                document.getElementById('volume_harian').textContent = data.volume_harian;
                document.getElementById('debit').textContent = data.debit;
                document.getElementById('v_water').textContent = data.v_water.toFixed(2);
                document.getElementById('status').textContent = data.status;
                document.getElementById('daya').textContent = data.daya;
                document.getElementById('tegangan').textContent = data.tegangan;
                document.getElementById('arus').textContent = data.arus.toFixed(2);
                document.getElementById('max_power').textContent = data.max_power;
                document.getElementById('energi_harian').textContent = data.energi_harian;
                document.getElementById('biaya').textContent = data.biaya.toFixed(2);
                document.getElementById('emisi').textContent = data.emisi.toFixed(2);
                document.getElementById('durasi_pemakaian_harian').textContent = data.durasi_pemakaian_harian;
                document.getElementById('debit').textContent = data.debit;
                document.getElementById('intensitas_cahaya').textContent = (data.intensitas_cahaya / 1000).toFixed(1);
                document.getElementById('intensitas_cahaya_text').textContent = data.intensitas_cahaya;
                document.getElementById('suhu').textContent = data.suhu;
                document.getElementById('suhu_harian').textContent = data.suhu_harian;
                document.getElementById('suhu_mingguan').textContent = data.suhu_mingguan;
                document.getElementById('online_status').textContent = data.online_status;
                document.getElementById('status_message').textContent = data.status_message;
                document.getElementById('created_at_desktop').textContent = formatTanggal(data.created_at, 'full');
                document.getElementById('created_at_mobile').textContent = formatTanggal(data.created_at, 'mobile');
                document.getElementById('durasi_koneksi_j').textContent = data.durasi_koneksi_j;
                document.getElementById('durasi_koneksi_m').textContent = data.durasi_koneksi_m;
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    setInterval(updateDashboard, 120000);
</script>

<script>
    const ctxg = document.getElementById('gaugeChart').getContext('2d');

    const value = document.getElementById('intensitas_cahaya_card').dataset.lux;

    const maxValue = 200000;

    const gaugeChartText = {
        id: 'gaugeChartText',
        afterDatasetsDraw(chart) {
        }
    };

    const tickPositions = [0, 66666, 99999, 133333, 166666, 200000];
    const segments = tickPositions.length - 1;

    const dataArray = [];
    const colorArray = [];

    for (let i = 0; i < segments; i++) {
        const segmentStart = tickPositions[i];
        const segmentEnd = tickPositions[i + 1];
        const segmentSize = segmentEnd - segmentStart;

        dataArray.push(segmentSize);

        if (value >= segmentEnd) {
            colorArray.push('#0f172a');
        } else if (value > segmentStart) {
            colorArray.push('#0f172a');
        } else {
            colorArray.push('#e2e8f0');
        }
    }

    const data = {
        datasets: [{
            data: dataArray,
            backgroundColor: colorArray,
            borderWidth: 6,
            borderColor: '#ffffff',
            circumference: 270,
            rotation: 225,
            cutout: '85%',
            borderRadius: 15
        }]
    };

    const config = {
        type: 'doughnut',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            aspectRatio: 2,
            animation: {
                animateRotate: true,
                animateScale: false,
                onComplete: function() {}
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: false
                }
            },
            layout: {
                padding: 0
            }
        },
        plugins: [gaugeChartText]
    };

    const gaugeChart = new Chart(ctxg, config);

    function updateGaugeValue(newValue) {
        const newColors = [];
        for (let i = 0; i < segments; i++) {
            const segmentStart = tickPositions[i];
            const segmentEnd = tickPositions[i + 1];

            if (newValue >= segmentEnd) {
                newColors.push('#0f172a');
            } else if (newValue > segmentStart) {
                newColors.push('#0f172a');
            } else {
                newColors.push('#e2e8f0');
            }
        }

        gaugeChart.data.datasets[0].backgroundColor = newColors;
        gaugeChart.update();

        const label = document.querySelector('.text-lg.text-gray-600');
        if (newValue >= 70) {
            label.textContent = 'Excellent';
        } else if (newValue >= 40) {
            label.textContent = 'Good';
        } else if (newValue >= 20) {
            label.textContent = 'Fair';
        } else {
            label.textContent = 'Poor';
        }
    }
</script>

<script>
    async function fetchHeatmapData() {
        try {
            const response = await fetch('/dashboard/heatMap');
            const result = await response.json();

            const data = result.data.map(item => ({
                date: new Date(item.date),
                temp: parseFloat(item.suhu_harian),
                value: item.value
            }));

            return data;
        } catch (error) {
            console.error('Error fetching heatmap data:', error);
            return [];
        }
    }

    function getColorByValue(value) {
        if (value === 0) return '#e2e8f0';
        if (value < 25) return '#94a3b8';
        if (value < 50) return '#64748b';
        if (value < 75) return '#334155';
        return '#0f172a';
    }

    function formatDate(date) {
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        return date.toLocaleDateString('id-ID', options);
    }

    async function renderHeatmap() {
        const container = document.getElementById('heatmap-container');

        container.innerHTML = '<div class="text-slate-400 text-sm">Loading data...</div>';

        const data = await fetchHeatmapData();

        if (data.length === 0) {
            container.innerHTML = '<div class="text-red-500 text-sm">Gagal memuat data</div>';
            return;
        }

        container.innerHTML = data.map((item, index) => {
            const color = getColorByValue(item.value);
            const dateStr = formatDate(item.date);

            return `
                <div 
                    class="w-7 h-7 rounded-full transition-all duration-300 hover:scale-125 cursor-pointer relative group" 
                    style="background-color: ${color}"
                >
                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block z-10 w-48">
                        <div class="bg-slate-900 text-white text-xs rounded-lg py-2 px-3 shadow-lg">
                            <div class="font-semibold mb-1">${item.date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}</div>
                            <div>Suhu rata-rata: <span class="font-bold">${item.temp}°C</span></div>
                            <div class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1">
                                <div class="border-4 border-transparent border-t-slate-900"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    renderHeatmap();

</script>

<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const dropdownButton = document.getElementById('dropdownNavbarLink');
    const dropdownMenu = document.getElementById('dropdownNavbar');
    const caretIcon = document.getElementById('caretIcon');
    let myChart;

    const modeConfigs = {
        power: {
            title: 'Produksi Energi',
            datasets: [{
                    key: 'daya',
                    label: 'Daya (W)',
                    color: '#0f172a'
                },
                {
                    key: 'energi_harian',
                    label: 'Energi (Wh)',
                    color: '#94a3b8'
                }
            ],
            yAxisLabel: 'Daya dan Energi'
        },
        pump: {
            title: 'Operasional Pompa',
            datasets: [{
                    key: 'debit',
                    label: 'Debit (L/min)',
                    color: '#0f172a'
                },
                {
                    key: 'volume_harian',
                    label: 'Volume (L)',
                    color: '#94a3b8'
                }
            ],
            yAxisLabel: 'Debit dan Volume'
        },
        environment: {
            title: 'Kondisi Lingkungan',
            datasets: [{
                    key: 'suhu',
                    label: 'Suhu (°C)',
                    color: '#94a3b8'
                },
                {
                    key: 'intensitas_cahaya',
                    label: 'Intensitas Cahaya (lux)',
                    color: '#0f172a'
                }
            ],
            yAxisLabel: 'Suhu dan Intensitas Cahaya'
        }
    };

    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = date.getDate();
        const month = date.toLocaleString('id-ID', {
            month: 'short'
        });
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${day} ${month} ${hours}:${minutes}`;
    }

    async function loadChartData(mode) {
        try {
            const response = await fetch(`/dashboard/chart/${mode}`);
            const result = await response.json();

            if (!result.data || result.data.length === 0) {
                console.warn('No data available');
                return;
            }

            const config = modeConfigs[mode];
            const labels = result.data.map(item => formatDate(item.created_at));

            const datasets = config.datasets.map(ds => ({
                label: ds.label,
                data: result.data.map(item => parseFloat(item[ds.key]) || 0),
                backgroundColor: ds.color,
                pointBackgroundColor: 'rgb(255,255,255)',
                pointRadius: 5,
                pointHoverRadius: 7,
                borderColor: ds.color,
                borderWidth: 2,
                tension: 0.3
            }));

            updateChart(labels, datasets, config.yAxisLabel);
        } catch (error) {
            console.error('Error loading chart data:', error);
        }
    }

    function updateChart(labels, datasets, yAxisLabel) {
        if (myChart) {
            myChart.destroy();
        }

        myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 8,
                            boxHeight: 8,
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Waktu'
                        },
                        ticks: {
                            callback: function(val, index) {
                                const maxLabels = 6;
                                const step = Math.ceil(this.getLabels().length / maxLabels);
                                return index % step === 0 ? this.getLabelForValue(val) : '';
                            }
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: yAxisLabel
                        }
                    }
                }
            }
        });
    }

    dropdownButton.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdownMenu.classList.toggle('hidden');

        if (dropdownMenu.classList.contains('hidden')) {
            caretIcon.style.transform = 'rotate(0deg)';
        } else {
            caretIcon.style.transform = 'rotate(180deg)';
        }
    });

    document.addEventListener('click', function(event) {
        if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.add('hidden');
            caretIcon.style.transform = 'rotate(0deg)';
        }
    });

    document.querySelectorAll('input[name="mode"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const mode = this.value;
            const config = modeConfigs[mode];

            document.getElementById('selectedMode').textContent = config.title;

            dropdownMenu.classList.add('hidden');
            caretIcon.style.transform = 'rotate(0deg)';

            loadChartData(mode);
        });
    });

    loadChartData('power');
</script>

</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjelasan Monitoring Jamur</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    @include('partials.sidebar-style')
    <style>body { font-family: 'Inter', sans-serif; }</style>
    <link rel="stylesheet" href="{{ asset('css/site-responsive.css') }}?v={{ filemtime(public_path('css/site-responsive.css')) }}">
</head>
<body class="has-sidebar bg-slate-50">
    @include('partials.sidebar')

    <div class="main" id="main">
        <main class="mx-auto max-w-6xl p-5 lg:p-8">
            <section class="mb-7 flex flex-col justify-between gap-5 sm:flex-row sm:items-center">
                <div>
                    <p class="mb-2 text-sm font-semibold uppercase tracking-[.16em] text-[#62A19E]">Budidaya Jamur Tiram</p>
                    <h1 class="text-3xl font-semibold text-slate-800">Penjelasan Sistem</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">Sistem membantu memantau suhu dan kelembapan kumbung, lalu menampilkan kondisi serta saran penyemprotan.</p>
                </div>
                <a href="{{ url('/dashboard/jamur') }}" class="inline-flex items-center justify-center rounded-xl bg-[#4CAF50] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#43a047]">
                    Buka Monitoring <span class="ml-2" aria-hidden="true">→</span>
                </a>
            </section>

            <section class="grid gap-5 lg:grid-cols-3">
                <article class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 lg:col-span-2">
                    <div class="mb-4 flex items-center gap-3">
                        <div class="rounded-full bg-[#62A19E]/10 p-3 text-[#43817e]" aria-hidden="true">◎</div>
                        <h2 class="text-lg font-semibold text-slate-800">Apa yang dipantau?</h2>
                    </div>
                    <p class="text-sm leading-7 text-slate-600">Sensor mengirim pembacaan suhu dan kelembapan udara kumbung. Nilai terbaru, status alat, serta grafik perubahan ditampilkan pada halaman Monitoring dan diperbarui otomatis.</p>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Status keseluruhan mempertimbangkan kedua parameter. Jika salah satunya berada di luar rentang acuan, ringkasan akan menunjukkan parameter yang perlu diperhatikan.</p>
                </article>

                <article class="rounded-2xl bg-[#f3faf8] p-6 ring-1 ring-[#dcefeb]">
                    <h2 class="text-lg font-semibold text-slate-800">Rentang acuan</h2>
                    <dl class="mt-5 space-y-4">
                        <div class="flex items-center justify-between gap-3 border-b border-[#dcefeb] pb-3">
                            <dt class="text-sm text-slate-600">Suhu</dt>
                            <dd class="font-semibold text-slate-800">22–28 °C</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-sm text-slate-600">Kelembapan</dt>
                            <dd class="font-semibold text-slate-800">80–90%</dd>
                        </div>
                    </dl>
                    <p class="mt-4 text-xs leading-5 text-slate-500">Rentang ini menjadi acuan status yang digunakan pada dashboard.</p>
                </article>

                <article class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                    <div class="mb-4 flex items-center gap-3">
                        <div class="rounded-full bg-amber-50 p-3 text-amber-600" aria-hidden="true">◷</div>
                        <h2 class="text-lg font-semibold text-slate-800">Saran penyemprotan</h2>
                    </div>
                    <p class="text-sm leading-7 text-slate-600">Saran penyemprotan ditentukan dari kelembapan. Jika kelembapan di bawah acuan, dashboard menyarankan penyemprotan. Jika sudah dalam rentang, penyemprotan belum disarankan; jika di atas rentang, penyemprotan dihentikan sementara.</p>
                    <p class="mt-3 text-xs leading-5 text-slate-500">Penyemprotan tetap dilakukan secara manual. Sistem hanya memberikan saran berdasarkan data sensor.</p>
                </article>

                <article class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 lg:col-span-2">
                    <div class="mb-4 flex items-center gap-3">
                        <div class="rounded-full bg-sky-50 p-3 text-sky-700" aria-hidden="true">ⓘ</div>
                        <h2 class="text-lg font-semibold text-slate-800">Membaca status alat</h2>
                    </div>
                    <p class="text-sm leading-7 text-slate-600">Status <strong>Online</strong> berarti data diterima dalam lima menit terakhir. Jika data tidak diterima lebih dari lima menit, status berubah menjadi <strong>Offline</strong>. Periksa koneksi alat dan waktu pembaruan ketika data terlihat berhenti.</p>
                </article>
            </section>
        </main>
    </div>
</body>
</html>

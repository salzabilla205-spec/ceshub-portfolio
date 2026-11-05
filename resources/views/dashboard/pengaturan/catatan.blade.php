<section class="journal" id="catatan" aria-labelledby="journal-title">
    <div class="journal-heading">
        <div><h2 id="journal-title">Catatan harian</h2><p>Simpan progres, masalah alat, dan rencana tindak lanjut.</p></div>
        <span class="journal-private">Privat · Kontrol project</span>
    </div>

    @if(session('note_saved'))
        <p class="journal-notice" role="status">{{ session('note_saved') }}</p>
    @endif
    @if($errors->any())
        <div class="journal-errors" role="alert"><p>Catatan belum tersimpan:</p><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form class="journal-form" method="POST" action="{{ $editNote ? route('dashboard.notes.update', [$project->slug, $editNote->id]) : route('dashboard.notes.store', $project->slug) }}">
        @csrf
        @if($editNote) @method('PUT') @endif
        <h3>{{ $editNote ? 'Ubah catatan' : 'Tulis catatan' }}</h3>
        <div class="journal-fields">
            <label>Tanggal<input type="date" name="note_date" required value="{{ old('note_date', $editNote?->note_date->format('Y-m-d') ?? now('Asia/Jakarta')->format('Y-m-d')) }}"></label>
            <label>Status<select name="status">@foreach(\App\Models\ProjectNote::STATUSES as $value => $label)<option value="{{ $value }}" @selected(old('status', $editNote?->status ?? 'open') === $value)>{{ $label }}</option>@endforeach</select></label>
        </div>
        <label>Progres hari ini<textarea name="progress" rows="3" maxlength="10000" placeholder="Apa yang sudah dilakukan atau berhasil hari ini?">{{ old('progress', $editNote?->progress) }}</textarea></label>
        <label>Masalah / kendala<textarea name="problems" rows="3" maxlength="10000" placeholder="Misalnya suplai turun, sensor error, atau data terputus. Catat waktu dan hasil pengukuran jika ada.">{{ old('problems', $editNote?->problems) }}</textarea></label>
        <label>Rencana / tindak lanjut<textarea name="next_steps" rows="2" maxlength="10000" placeholder="Apa yang perlu dicek atau dikerjakan berikutnya?">{{ old('next_steps', $editNote?->next_steps) }}</textarea></label>
        <div class="journal-actions"><button class="btn-primary" type="submit">{{ $editNote ? 'Simpan perubahan' : 'Simpan catatan' }}</button>@if($editNote)<a href="{{ route('dashboard.pengaturan', $project->slug) }}#catatan">Batal ubah</a>@endif</div>
        <p class="journal-help">Satu catatan per tanggal. Catatan yang sudah disimpan bisa diubah.</p>
    </form>

    <div class="journal-history-heading"><h3>Riwayat catatan</h3><form method="GET" action="{{ route('dashboard.pengaturan', $project->slug) }}#catatan" class="journal-filter"><label for="note-filter">Tanggal</label><input type="date" id="note-filter" name="date" value="{{ request('date') }}"><button type="submit">Tampilkan</button>@if(request('date'))<a href="{{ route('dashboard.pengaturan', $project->slug) }}#catatan">Semua</a>@endif</form></div>
    @forelse($notes as $note)
        <article class="journal-entry">
            <header><div><time datetime="{{ $note->note_date->format('Y-m-d') }}">{{ $note->note_date->format('d/m/Y') }}</time><span class="journal-badge journal-badge-{{ $note->status }}">{{ \App\Models\ProjectNote::STATUSES[$note->status] ?? 'Catatan' }}</span></div><a href="{{ route('dashboard.pengaturan', ['slug' => $project->slug, 'edit' => $note->id]) }}#catatan" aria-label="Ubah catatan {{ $note->note_date->format('d/m/Y') }}">Ubah</a></header>
            @foreach(['progress' => 'Progres', 'problems' => 'Masalah / kendala', 'next_steps' => 'Tindak lanjut'] as $field => $label)
                @if(filled($note->{$field}))<div class="journal-entry-section"><h4>{{ $label }}</h4><p>{{ $note->{$field} }}</p></div>@endif
            @endforeach
        </article>
    @empty
        <p class="journal-empty">{{ request('date') ? 'Belum ada catatan pada tanggal ini.' : 'Belum ada catatan. Mulai dengan mengisi formulir di atas.' }}</p>
    @endforelse
    @if($notes->hasPages())<nav class="journal-pagination" aria-label="Halaman catatan">@if($notes->previousPageUrl())<a href="{{ $notes->previousPageUrl() }}#catatan">← Sebelumnya</a>@endif<span>{{ $notes->currentPage() }} / {{ $notes->lastPage() }}</span>@if($notes->nextPageUrl())<a href="{{ $notes->nextPageUrl() }}#catatan">Berikutnya →</a>@endif</nav>@endif
</section>

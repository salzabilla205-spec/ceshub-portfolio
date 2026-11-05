<section class="lab-card lab-history dashboard-history" data-history-project="{{ $historyProject }}">
    <div class="lab-card-heading"><h2>Riwayat pembacaan</h2><div class="export-actions"></div></div>
    <form class="lab-filters dashboard-history-filter">
        <label>Dari<input type="datetime-local" name="history_from" value="{{ now()->startOfDay()->format('Y-m-d\TH:i') }}" required></label>
        <label>Sampai<input type="datetime-local" name="history_to" value="{{ now()->endOfDay()->format('Y-m-d\TH:i') }}" required></label>
        <button class="lab-button" type="submit">Terapkan</button><button type="button" class="history-today">Hari ini</button><small>Zona waktu: {{ config('app.timezone') }}</small>
    </form>
    <p class="history-message" role="status"></p>
    <div class="lab-table-scroll"><table><thead></thead><tbody></tbody></table></div>
    <div class="lab-pagination"><span class="history-count"></span><nav class="history-pages" aria-label="Halaman riwayat"></nav></div>
</section>

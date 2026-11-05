@forelse($history as $row)
<tr><td>{{ $row->created_at->format('d/m/Y H:i:s') }}</td><td>{{ $row->tegangan }}</td><td>{{ number_format($row->tegangan > 0 ? $row->daya / $row->tegangan : 0, 2) }}</td><td>{{ $row->daya }}</td><td>{{ $row->suhu }}</td><td>{{ number_format($row->estimasi_irradiansi, 2, ',', '.') }}</td></tr>
@empty
<tr><td colspan="6" class="lab-empty">Belum ada pembacaan dalam rentang ini.</td></tr>
@endforelse

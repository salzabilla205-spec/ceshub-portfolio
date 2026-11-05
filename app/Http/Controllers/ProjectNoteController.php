<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectNote;
use Illuminate\Http\Request;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProjectNoteController extends Controller
{
    private function authorizedProject(string $slug): Project
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        $credential = Auth::guard('dashboard')->user();
        abort_unless($credential && (int) $credential->project_id === (int) $project->id,
            403, 'Masuk dengan sandi project sebelum mengubah catatan.');

        return $project;
    }

    private function validatedNote(Request $request, Project $project, ?ProjectNote $note = null): array
    {
        $uniqueDate = Rule::unique('project_notes', 'note_date')->where('project_id', $project->id);
        if ($note) {
            $uniqueDate->ignore($note->id);
        }

        $validator = Validator::make($request->only(['note_date', 'progress', 'problems', 'next_steps', 'status']), [
            'note_date' => ['required', 'date_format:Y-m-d', $uniqueDate],
            'progress' => ['nullable', 'string', 'max:10000'],
            'problems' => ['nullable', 'string', 'max:10000'],
            'next_steps' => ['nullable', 'string', 'max:10000'],
            'status' => ['required', Rule::in(array_keys(ProjectNote::STATUSES))],
        ], [
            'note_date.unique' => 'Catatan tanggal ini sudah ada. Pilih Ubah pada catatan tersebut.',
            'note_date.required' => 'Pilih tanggal catatan.',
            'note_date.date_format' => 'Tanggal catatan tidak valid.',
            '*.max' => 'Setiap kolom maksimal 10.000 karakter.',
            'status.in' => 'Pilih status yang tersedia.',
        ]);
        $validator->after(function ($validator) use ($request) {
            if (collect(['progress', 'problems', 'next_steps'])->every(fn ($key) => ! is_string($request->input($key)) || trim($request->input($key)) === '')) {
                $validator->errors()->add('progress', 'Isi minimal salah satu: progres, masalah, atau tindak lanjut.');
            }
        });

        return $validator->validate();
    }

    public function store(Request $request, string $slug)
    {
        $project = $this->authorizedProject($slug);
        $values = $this->validatedNote($request, $project);
        $note = new ProjectNote($values);
        $note->project_id = $project->id;
        try {
            $note->save();
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages(['note_date' => 'Catatan tanggal ini sudah ada. Pilih Ubah pada catatan tersebut.']);
        }

        return redirect()->to(route('dashboard.pengaturan', $slug).'#catatan')
            ->with('note_saved', 'Catatan harian berhasil disimpan.');
    }

    public function update(Request $request, string $slug, int $note)
    {
        $project = $this->authorizedProject($slug);
        $record = ProjectNote::where('project_id', $project->id)->findOrFail($note);
        $values = $this->validatedNote($request, $project, $record);
        try {
            $record->update($values);
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages(['note_date' => 'Catatan tanggal ini sudah ada. Pilih tanggal lain.']);
        }

        return redirect()->to(route('dashboard.pengaturan', $slug).'#catatan')
            ->with('note_saved', 'Perubahan catatan berhasil disimpan.');
    }
}

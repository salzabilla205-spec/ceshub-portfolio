<?php

namespace App\Services;

use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Projects\ProjectResource;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ResearchContentService
{
    public function paginate(?string $search, ?array $filters, int $page, int|string $perPage): LengthAwarePaginator
    {
        // A read-only union keeps each original record, relationship and edit route intact.
        $projects = Project::withTrashed()->selectRaw("id as source_id, 'project' as content_type, slug, name as title, summary, thumbnail, status, published_at, created_at, deleted_at, category")
            ->addSelect(['team_count' => DB::table('project_team')->selectRaw('count(*)')->whereColumn('project_id', 'projects.id')]);
        $posts = Post::withTrashed()->selectRaw("id as source_id, 'post' as content_type, slug, title, excerpt as summary, thumbnail, status, published_at, created_at, deleted_at, NULL as category, NULL as team_count");
        $query = DB::query()->fromSub($projects->toBase()->unionAll($posts->toBase()), 'research_content');

        $trashed = $filters['trashed']['value'] ?? null;
        if ($trashed === null || $trashed === '') {
            $query->whereNull('deleted_at');
        } elseif (! (bool) $trashed) {
            $query->whereNotNull('deleted_at');
        }
        $status = $filters['status']['value'] ?? null;
        if (in_array($status, ['draft', 'published'], true)) {
            $query->where('status', $status);
        }
        $type = $filters['content_type']['value'] ?? null;
        if (in_array($type, ['project', 'post'], true)) {
            $query->where('content_type', $type);
        }
        if (filled($search)) {
            $query->where(fn ($matches) => $matches->where('title', 'like', '%'.$search.'%')
                ->orWhere('summary', 'like', '%'.$search.'%')
                ->orWhere('category', 'like', '%'.$search.'%')
                ->orWhere('slug', 'like', '%'.$search.'%'));
        }

        $total = (clone $query)->count();
        $perPage = $perPage === 'all' ? max(1, $total) : max(1, (int) $perPage);
        $rows = $query->orderByDesc('published_at')->orderByDesc('created_at')->orderBy('content_type')->orderBy('source_id')
            ->forPage($page, $perPage)->get()->mapWithKeys(function ($row) {
                $record = (array) $row;
                $key = $record['content_type'].'-'.$record['source_id'];
                $resource = $record['content_type'] === 'project' ? ProjectResource::class : PostResource::class;
                $record['edit_url'] = $resource::getUrl('edit', ['record' => $record['content_type'] === 'project' ? $record['slug'] : $record['source_id']]);

                return [$key => $record];
            });

        return new LengthAwarePaginator($rows, $total, $perPage, $page, ['path' => ProjectResource::getUrl()]);
    }

    public function apply(array $record, string $operation): void
    {
        abort_unless(auth()->user()?->is_admin, 403);
        $resource = match ($record['content_type']) {
            'project' => ProjectResource::class,
            'post' => PostResource::class,
            default => abort(404),
        };
        /** @var Model $model */
        $modelClass = $resource::getModel();
        $model = $modelClass::withTrashed()->findOrFail($record['source_id']);
        abort_unless(in_array($operation, ['delete', 'restore', 'forceDelete'], true), 400);
        abort_unless($resource::{'can'.ucfirst($operation)}($model), 403);
        $model->{$operation}();
    }
}

<details class="lab-card project-research-card" data-reveal data-mobile-card open>
    <summary class="mobile-card-toggle"><h2>{{ $researchTitle ?? 'Kabar Riset' }}</h2></summary>
    <div class="mobile-card-content">
    @if($project)
        @if(count($project->media_images))
            @include('partials.media-carousel', ['images'=>$project->media_images, 'mediaKey'=>'project-'.$project->id, 'mediaAlt'=>$project->name, 'mediaClass'=>'project-research-photo'])
        @endif
        <a class="project-research-title" href="{{ route('riset.show',$project) }}">{{ $project->name }} <span aria-hidden="true">↗</span></a>
        @if($project->summary)<p class="lab-hint">{{ $project->summary }}</p>@endif
    @endif
    @forelse($project?->posts ?? [] as $post)
    <details class="lab-news"><summary><strong>{{ $post->title }}</strong><small>{{ $post->published_at?->format('d M Y') }}</small></summary>
        @include('partials.media-carousel', ['images'=>$post->media_images, 'mediaKey'=>'post-'.$post->id, 'mediaAlt'=>$post->title])
        <p>{{ $post->excerpt }}</p><div>{{ strip_tags($post->body ?? '') }}</div>
    </details>
    @empty
        @unless($project)<p class="lab-hint">Informasi project belum ditambahkan.</p>@endunless
    @endforelse
    @if($project)<a class="lab-link" href="{{ route('riset.show',$project) }}">Informasi project →</a>@endif
    </div>
</details>

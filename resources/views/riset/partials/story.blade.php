@php $featured = $featured ?? false; @endphp
<article class="research-story {{ $featured ? 'research-story--featured' : '' }}">
    <a class="research-story__image" href="{{ route('riset.show', $project->slug) }}" tabindex="-1" aria-hidden="true">
        @if(count($project->media_images))
            @include('partials.media-carousel', ['images'=>$project->media_images, 'mediaKey'=>'project-'.$project->id, 'mediaAlt'=>$project->name, 'showMediaControls'=>false])
        @else
            <span>CES <small>Control Energy System</small></span>
        @endif
    </a>
    <div class="research-story__body">
        @if($project->category)<span class="research-story__category">{{ $project->category }}</span>@endif
        <h3><a href="{{ route('riset.show', $project->slug) }}">{{ $project->name }}</a></h3>
        @if($project->summary)<p>{{ $project->summary }}</p>@endif
        <div class="research-story__meta"><span>CES Research</span>@if($project->published_at)<time datetime="{{ $project->published_at->toDateString() }}">{{ $project->published_at->translatedFormat('d M Y') }}</time>@endif</div>
    </div>
</article>

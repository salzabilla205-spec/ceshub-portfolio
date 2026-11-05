<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $project->name }} | CES</title>
    <meta name="description" content="{{ $project->summary }}">
    @include('partials.landing-style')
    <link rel="stylesheet" href="{{ asset('css/lab-monitoring.css') }}?v={{ filemtime(public_path('css/lab-monitoring.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/research-detail.css') }}?v={{ filemtime(public_path('css/research-detail.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/site-responsive.css') }}?v={{ filemtime(public_path('css/site-responsive.css')) }}">
</head>
<body class="research-detail-page">
@include('partials.landing-header')
@php
    $articleParagraphs = static function ($html) {
        $text = preg_replace('/<br\s*\/?\s*>/i', "\n", (string) $html);
        $text = preg_replace('/<\/(?:p|div|h[1-6]|li)>/i', "\n\n", $text);
        return preg_split('/\R{2,}/', trim(html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    };
@endphp
<main class="wrap research-detail"><article class="research-article">
    <nav class="article-breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Beranda</a><span>/</span><a href="{{ route('riset.index') }}">Kabar Riset</a></nav>
    <header class="article-heading">
        @if($project->category)<div class="article-category">{{ $project->category }}</div>@endif
        <h1>{{ $project->name }}</h1>
        <div class="article-meta"><span>CES Research</span>@if($project->published_at)<span aria-hidden="true">·</span><time datetime="{{ $project->published_at->toDateString() }}">{{ $project->published_at->locale('id')->translatedFormat('d F Y') }}</time>@endif</div>
        @if($project->summary)<p class="article-deck">{{ $project->summary }}</p>@endif
    </header>
    @if(count($project->media_images))
        @include('partials.media-carousel', ['images'=>$project->media_images, 'mediaKey'=>'project-'.$project->id, 'mediaAlt'=>$project->name, 'mediaClass'=>'research-cover', 'mediaShare'=>true, 'mediaShareTitle'=>$project->name, 'mediaShareUrl'=>route('riset.show', $project)])
    @else
        <div class="article-actions"><button type="button" class="article-share" data-article-share data-share-title="{{ $project->name }}" data-share-url="{{ route('riset.show', $project) }}">@include('partials.ui-icon',['icon'=>'share']) Bagikan</button><span role="status" aria-live="polite"></span></div>
    @endif
    <div class="article-body">@foreach($articleParagraphs($project->description ?? $project->summary ?? '') as $paragraph)@if(trim($paragraph) !== '')<p>{{ $paragraph }}</p>@endif @endforeach</div>
    @if($project->monitoring_path)<a class="article-dashboard" href="{{ $project->monitoring_path }}">Buka Dashboard @include('partials.ui-icon',['icon'=>'external'])</a>@endif
    @if(is_array($project->specs) && count($project->specs))
        <details class="article-specs"><summary>Spesifikasi project @include('partials.ui-icon',['icon'=>'down'])</summary><dl>@foreach($project->specs as $key=>$value)<div><dt>{{ $key }}</dt><dd>{{ is_scalar($value) ? $value : json_encode($value, JSON_UNESCAPED_UNICODE) }}</dd></div>@endforeach</dl></details>
    @endif
    @if($project->posts->isNotEmpty())
        <section class="article-updates" aria-labelledby="project-updates"><h2 id="project-updates">Kabar dari project ini</h2>@foreach($project->posts as $post)
            <section id="kabar-{{ $post->id }}" class="article-update"><h3>{{ $post->title }}</h3>@if($post->published_at)<time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->locale('id')->translatedFormat('d F Y') }}</time>@endif
                @include('partials.media-carousel', ['images'=>$post->media_images, 'mediaKey'=>'post-'.$post->id, 'mediaAlt'=>$post->title, 'mediaShare'=>true, 'mediaShareTitle'=>$post->title, 'mediaShareUrl'=>route('riset.show', $project).'#kabar-'.$post->id])
                @foreach($articleParagraphs($post->body ?: $post->excerpt ?: '') as $paragraph)@if(trim($paragraph) !== '')<p>{{ $paragraph }}</p>@endif @endforeach
            </section>@endforeach
        </section>
    @endif
    <section class="article-team"><h2>Tim project</h2><div class="research-member-grid">@forelse($project->teamMembers as $member)<article>@if($member->photo)<img src="{{ asset('uploads/'.$member->photo) }}" alt="" loading="lazy">@else<span class="lab-avatar" aria-hidden="true">{{ mb_substr($member->name,0,1) }}</span>@endif<div><h3>{{ $member->name }}</h3><p>{{ $member->pivot->role_in_project ?: $member->role }}</p></div></article>@empty<p>Anggota project belum ditambahkan.</p>@endforelse</div><a class="article-team-link" href="{{ route('dashboard.tim.project',$project->slug) }}">Lihat tim project →</a></section>
    @if(in_array($project->slug,['living-lab','smart-water-pump'],true))<details class="article-location"><summary>Lokasi project @include('partials.ui-icon',['icon'=>'down'])</summary><iframe title="Lokasi Smart Water Pump Sragen" src="https://www.google.com/maps?q=-7.4903813,110.9074568&z=15&output=embed" height="240" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></details>@endif
</article></main>
@include('partials.landing-footer')
@include('partials.landing-script')
</body>
</html>

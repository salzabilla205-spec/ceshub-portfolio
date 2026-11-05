@php
    $mediaImages = $images ?? [];
    $mediaClass = $mediaClass ?? '';
@endphp
@if(count($mediaImages))
<div class="media-carousel {{ $mediaClass }}" data-media-carousel data-media-key="{{ $mediaKey }}" aria-label="Galeri foto" aria-roledescription="carousel">
    @foreach($mediaImages as $image)
    <img class="media-slide {{ $loop->first ? 'is-active' : '' }}" src="{{ asset('uploads/'.$image) }}" alt="{{ $mediaAlt ?? 'Dokumentasi project' }}{{ count($mediaImages)>1 ? ' — foto '.$loop->iteration : '' }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}" aria-hidden="{{ $loop->first ? 'false' : 'true' }}">
    @endforeach
    @if((count($mediaImages)>1 && ($showMediaControls ?? true)) || ($mediaShare ?? false))
    <div class="media-controls">
        @if(count($mediaImages)>1 && ($showMediaControls ?? true))
        <button type="button" data-media-prev aria-label="Foto sebelumnya">@include('partials.ui-icon', ['icon'=>'left'])</button><span data-media-counter>1 / {{ count($mediaImages) }}</span><button type="button" data-media-next aria-label="Foto berikutnya">@include('partials.ui-icon', ['icon'=>'right'])</button>
        @endif
        @if($mediaShare ?? false)<button type="button" class="media-share" data-article-share data-share-title="{{ $mediaShareTitle ?? $mediaAlt ?? '' }}" data-share-url="{{ $mediaShareUrl ?? url()->current() }}">@include('partials.ui-icon', ['icon'=>'share'])<span>Bagikan</span></button>@endif
    </div>
    @if($mediaShare ?? false)<span class="media-share-status" data-share-status role="status" aria-live="polite"></span>@endif
    @endif
</div>
@endif

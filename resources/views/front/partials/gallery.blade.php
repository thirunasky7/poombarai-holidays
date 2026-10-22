@php
  $frames = collect($images ?? [])->map(function ($image) {
      if (is_array($image)) {
          return $image;
      }
      return ['src' => $image->src(), 'alt' => $image->alt];
  })->filter(fn ($image) => ! empty($image['src']))->values();
@endphp
<div class="gallery">
  @if($frames->isNotEmpty())
    <div class="media {{ !empty($wide) ? 'media-wide' : '' }}">
      <button type="button" class="media-btn" data-gallery-kind="{{ $kind }}" data-gallery-id="{{ $id }}" data-gallery-index="0" aria-label="Open photos of {{ $label }}">
        <img src="{{ $frames[0]['src'] }}" alt="{{ $frames[0]['alt'] }}" width="960" height="640" loading="lazy" decoding="async">
        @if($frames->count() > 1)
          <span class="photo-count" aria-hidden="true">{{ $frames->count() }} photos</span>
        @endif
      </button>
    </div>
    @if($frames->count() > 1)
      <div class="thumbs">
        @foreach($frames as $index => $frame)
          <div class="media">
            <button type="button" class="media-btn" data-gallery-kind="{{ $kind }}" data-gallery-id="{{ $id }}" data-gallery-index="{{ $index }}" aria-label="Photo {{ $index + 1 }} of {{ $label }}">
              <img src="{{ $frame['src'] }}" alt="{{ $frame['alt'] }}" width="320" height="220" loading="lazy" decoding="async">
            </button>
          </div>
        @endforeach
      </div>
    @endif
  @endif
</div>

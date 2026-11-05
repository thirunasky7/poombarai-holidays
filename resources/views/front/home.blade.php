@extends('layouts.site')

@section('content')
  <section class="home-banner" data-home-banner aria-roledescription="carousel" aria-label="What we offer">
    @forelse($banners as $banner)
      <article class="home-slide" data-banner-slide @unless($loop->first) hidden @endunless aria-hidden="{{ $loop->first ? 'false' : 'true' }}">
        <img src="{{ $banner->imageSrc() }}" alt="{{ $banner->image_alt }}" width="1600" height="900" @unless($loop->first) loading="lazy" @endunless decoding="async">
        <div class="home-slide-copy">
          <p class="home-slide-kicker">{{ $page->eyebrow }}</p>
          <h2>{{ $banner->title }}</h2>
          <p>{{ $banner->text }}</p>
          @if($banner->button_label && $banner->button_url)
            <a class="btn btn-clay" href="{{ $banner->button_url }}">{{ $banner->button_label }}</a>
          @endif
        </div>
      </article>
    @empty
      <article class="home-slide" data-banner-slide>
        <img src="{{ $heroUrl }}" alt="{{ $site['hero_image_alt'] }}" width="1600" height="900" decoding="async">
      </article>
    @endforelse
    @if($banners->count() > 1)
      <button type="button" class="home-banner-arrow prev" data-banner-prev aria-label="Previous banner">‹</button>
      <button type="button" class="home-banner-arrow next" data-banner-next aria-label="Next banner">›</button>
      <div class="home-banner-dots" role="tablist" aria-label="Choose a banner">
        @foreach($banners as $banner)
          <button type="button" data-banner-dot="{{ $loop->index }}" aria-label="Show {{ $banner->title }}" @if($loop->first) aria-current="true" @endif></button>
        @endforeach
      </div>
    @endif
  </section>

  @if($shorts->isNotEmpty())
    <section class="shorts-band" aria-labelledby="shorts-heading">
      <div class="wrap">
        <div class="shorts-head">
          <h2 id="shorts-heading">Shorts</h2>
          <p>Swipe to watch</p>
        </div>
        <div class="shorts-row">
          @foreach($shorts as $short)
            @php
              $ext = strtolower(pathinfo($short->video_path, PATHINFO_EXTENSION));
              $mime = $ext === 'webm' ? 'video/webm' : ($ext === 'mov' ? 'video/quicktime' : 'video/mp4');
            @endphp
            <article class="short-card">
              <video muted loop playsinline preload="metadata" data-short-video @if($short->posterUrl()) poster="{{ $short->posterUrl() }}" @endif>
                <source src="{{ $short->videoUrl() }}" type="{{ $mime }}">
              </video>
              <button type="button" class="short-mute" data-short-mute aria-pressed="true" aria-label="Unmute video">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 10v4h3.2L12 18.2V5.8L7.2 10H4z"/><path class="sound-on" d="M15.2 9.2a3.6 3.6 0 0 1 0 5.6M17.6 6.8a7 7 0 0 1 0 10.4"/></svg>
              </button>
              <div class="short-meta">
                <h3>{{ $short->title }}</h3>
                @if($short->caption)<p>{{ $short->caption }}</p>@endif
                <button type="button" class="btn btn-wa short-enquire" data-enquiry-type="short video" data-item-name="{{ $short->title }}">{{ $short->button_label }}</button>
              </div>
            </article>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  <section class="band" aria-labelledby="packages-heading">
    <div class="wrap">
      <div class="section-head">
        <h2 id="packages-heading" class="section-title">Package</h2>
        <p class="lede">Open a package to see its photos. Enquire on WhatsApp when you are ready.</p>
      </div>
      @if($packages->isNotEmpty())
        <div class="home-packages">
          @foreach($packages as $package)
            <article class="home-package">
              @if($package->imageSrc())
                <a href="{{ route('packages.show', $package) }}" tabindex="-1" aria-hidden="true">
                  <img src="{{ $package->imageSrc() }}" alt="{{ $package->image_alt ?: $package->name }}" width="640" height="420" loading="lazy" decoding="async">
                </a>
              @endif
              <div class="home-package-body">
                <h3>{{ $package->name }}</h3>
                <a class="btn btn-line" href="{{ route('packages.show', $package) }}">View detail</a>
              </div>
            </article>
          @endforeach
        </div>
      @else
        <p class="lede">Packages will appear here once they are added in admin.</p>
      @endif
    </div>
  </section>
@endsection

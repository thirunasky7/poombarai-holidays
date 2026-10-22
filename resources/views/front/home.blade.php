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

  <section class="band home-intro">
    <div class="wrap home-intro-inner">
      <p class="eyebrow">{{ $page->eyebrow }}</p>
      <h1>{{ $page->h1 }}</h1>
      <p class="lede">{{ $page->intro }}</p>
    </div>
  </section>

  <section class="band" style="padding-top:0" aria-label="Choose what you need">
    <div class="wrap choice-cards">
      <a class="choice-card" href="{{ route('rooms') }}">
        <p class="kicker">1 · Stay</p>
        <h2>Sleep in Poombarai</h2>
        <p>A room, cottage, or homestay in the village.</p>
        <span class="text-link">See stays</span>
      </a>
      <a class="choice-card" href="{{ route('cars') }}">
        <p class="kicker">2 · Car</p>
        <h2>Hire a car and guide</h2>
        <p>A driver who knows the road to the lake and viewpoints.</p>
        <span class="text-link">See cars</span>
      </a>
      <a class="choice-card" href="{{ route('places') }}">
        <p class="kicker">3 · Places</p>
        <h2>Pick where to go</h2>
        <p>Start in the village, then Kodai Lake and the viewpoints.</p>
        <span class="text-link">See places</span>
      </a>
    </div>
  </section>

  <section class="band" style="background:#fff;padding-top:0" aria-labelledby="how-heading">
    <div class="wrap">
      <h2 id="how-heading" class="section-title">How booking works</h2>
      <ol class="steps">
        <li><strong>Message us</strong><span>Send your dates, or the places you want to see, on WhatsApp.</span></li>
        <li><strong>We reply</strong><span>You get a room or a car, and the price, in the chat.</span></li>
        <li><strong>You confirm</strong><span>Nothing is booked until you say yes. This site does not take payment.</span></li>
      </ol>
    </div>
  </section>

  @if($rooms->isNotEmpty())
    <section class="band" style="padding-top:0" aria-labelledby="stays-heading">
      <div class="wrap">
        <div class="section-head">
          <h2 id="stays-heading" class="section-title">Stays guests ask for first</h2>
          <p class="lede">Prices are a starting rate for the night. We confirm the exact price on WhatsApp.</p>
        </div>
        <div class="home-stays">
          @foreach($rooms as $room)
            <a class="home-stay" href="{{ route('rooms') }}#{{ $room->slug }}">
              @if($room->cover())
                <img src="{{ $room->cover()->src() }}" alt="{{ $room->cover()->alt }}" width="640" height="420" loading="lazy" decoding="async">
              @endif
              <span class="kicker">{{ $room->type }} · up to {{ $room->occupancy }} guests</span>
              <strong>{{ $room->name }}</strong>
              <span>{{ $room->price_label }}</span>
            </a>
          @endforeach
        </div>
        <p class="mt-6"><a class="btn btn-line" href="{{ route('rooms') }}">All stays</a></p>
      </div>
    </section>
  @endif

  <section class="cta-band band" aria-labelledby="cta-heading">
    <div class="wrap">
      <h2 id="cta-heading">Ask on WhatsApp</h2>
      <p>Tell us if you need a stay, a car, or both. We reply during the hours in the footer.</p>
      <div class="cta-actions">
        <a class="btn btn-wa" data-enquiry-type="general" data-item-name="Home call to action" href="https://wa.me/{{ preg_replace('/\D+/', '', $site['whatsapp_number']) }}">WhatsApp the host</a>
        <a class="btn btn-clay" data-bind-href="tel" href="tel:{{ preg_replace('/[^\d+]/', '', $site['phone']) }}">Call <span data-bind="phone">{{ $site['phone'] }}</span></a>
      </div>
    </div>
  </section>
@endsection

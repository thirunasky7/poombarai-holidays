@extends('layouts.site')

@push('head')
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'BreadcrumbList',
  'itemListElement' => [
    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => rtrim($site['site_url'], '/').'/'],
    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Places from Poombarai', 'item' => rtrim($site['site_url'], '/').'/places'],
  ],
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
  <div class="page-banner">
    <img src="{{ $page->bannerUrl() }}" alt="{{ $page->banner_alt }}" width="1600" height="900" decoding="async">
  </div>
  <header class="page-intro">
    <div class="wrap">
      <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a> <span aria-hidden="true">/</span> <span aria-current="page">Places</span></nav>
      <p class="eyebrow">{{ $page->eyebrow }}</p>
      <h1>{{ $page->h1 }}</h1>
      <p class="lede">{{ $page->intro }}</p>
    </div>
  </header>

  <section class="band" style="padding-top:0.5rem" aria-labelledby="directory-heading">
    <div class="wrap">
      <h2 id="directory-heading" class="visually-hidden">Place directory</h2>
      <div class="place-list">
        @foreach($places as $place)
          <article class="place-card" id="{{ $place->slug }}">
            @include('front.partials.gallery', [
                'images' => [['src' => $place->imageSrc(), 'alt' => $place->image_alt]],
                'kind' => 'place',
                'id' => $place->slug,
                'label' => $place->name,
            ])
            <div class="card-body">
              <h3>{{ $place->name }}@if($place->name_ta) <span class="hint">({{ $place->name_ta }})</span>@endif</h3>
              <p class="summary">{{ $place->description }}</p>
              <dl class="facts">
                <div><dt>Best time</dt><dd>{{ $place->best_time }}</dd></div>
                <div><dt>Time needed</dt><dd>{{ $place->time_needed }}</dd></div>
              </dl>
              <label class="choice">
                <input type="checkbox" form="places-form" data-place-select data-field="places" value="{{ $place->name }}">
                <span>Include {{ $place->name }} in my car enquiry</span>
              </label>
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section class="band" style="background:#fff" aria-labelledby="plans-heading">
    <div class="wrap">
      <h2 id="plans-heading" class="section-title">Ready-made days from the village</h2>
      <div class="plan-grid">
        @foreach($plans as $plan)
          @php
            $stops = $plan->stops->filter(fn ($stop) => $stop->destination);
            $grouped = $stops->groupBy(fn ($stop) => $stop->day_label ?: '');
          @endphp
          <article class="plan-card">
            <p class="kicker">{{ $plan->duration }}</p>
            <h3>{{ $plan->title }}</h3>
            <p class="summary">{{ $plan->summary }}</p>
            @if($grouped->keys()->filter()->isNotEmpty())
              <div class="plan-days">
                @foreach($grouped as $label => $dayStops)
                  @if($label !== '')
                    <div>
                      <h4>{{ $label }}</h4>
                      <ul class="mini-list">
                        @foreach($dayStops as $stop)
                          <li>{{ $stop->destination->name }}</li>
                        @endforeach
                      </ul>
                    </div>
                  @endif
                @endforeach
              </div>
            @else
              <ul class="mini-list">
                @foreach($stops as $stop)
                  <li>{{ $stop->destination->name }}</li>
                @endforeach
              </ul>
            @endif
            <div class="plan-actions">
              <button type="button" class="btn btn-line" data-plan-select="{{ $plan->slug }}">Add this plan</button>
              <button type="button" class="btn btn-wa" data-plan-send="{{ $plan->slug }}">Book this plan on WhatsApp</button>
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section class="band" aria-labelledby="places-form-heading">
    <div class="wrap" style="max-width:46rem">
      <form class="js-places-form enquiry-card" id="places-form" novalidate>
        <h2 id="places-form-heading" class="form-title">Book a sightseeing car</h2>
        <p class="lang-status" data-lang-status>WhatsApp message: English</p>
        <p data-selected-count>No places selected yet</p>
        <p data-active-plan></p>
        <div class="form-grid cols-2">
          <div class="field"><label for="p-name">Full name</label><input id="p-name" data-field="name" required maxlength="80"><p class="error-text" data-error-for="name" hidden></p></div>
          <div class="field"><label for="p-phone">Mobile number</label><input id="p-phone" data-field="phone" type="tel" required maxlength="16"><p class="error-text" data-error-for="phone" hidden></p></div>
          <div class="field"><label for="p-date">Date</label><input id="p-date" data-field="date" type="date" required><p class="error-text" data-error-for="date" hidden></p></div>
          <div class="field"><label for="p-time">Pickup time</label><input id="p-time" data-field="time" type="time" required><p class="error-text" data-error-for="time" hidden></p></div>
          <div class="field span-2"><label for="p-pickup">Pickup</label><input id="p-pickup" data-field="pickup" value="My stay in Poombarai" required maxlength="120"><p class="error-text" data-error-for="pickup" hidden></p></div>
          <div class="field"><label for="p-vehicle">Vehicle</label><select id="p-vehicle" data-field="vehicle" data-options="vehicles" required><option value="">Select a vehicle</option></select><p class="error-text" data-error-for="vehicle" hidden></p></div>
          <div class="field"><label for="p-passengers">Passengers</label><input id="p-passengers" data-field="passengers" type="number" min="1" max="20" value="2" required><p class="error-text" data-error-for="passengers" hidden></p></div>
        </div>
        <p class="visually-hidden" data-form-status aria-live="assertive"></p>
        <div class="form-actions"><button type="submit" class="btn btn-wa">Send places on WhatsApp</button></div>
        <p class="form-note">Tick places above, or add a ready-made plan. We confirm the fare before you travel.</p>
      </form>
    </div>
  </section>
  <div id="selection-bar" class="selection-bar" hidden>
    <span><span data-selection-count>0</span> selected</span>
    <button type="button" data-scroll="#places-form">Book a car</button>
  </div>
@endsection

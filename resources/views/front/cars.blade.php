@extends('layouts.site')

@push('head')
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'BreadcrumbList',
  'itemListElement' => [
    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => rtrim($site['site_url'], '/').'/'],
    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Car rental from Poombarai', 'item' => rtrim($site['site_url'], '/').'/cars'],
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
      <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a> <span aria-hidden="true">/</span> <span aria-current="page">Cars &amp; guide</span></nav>
      <p class="eyebrow">{{ $page->eyebrow }}</p>
      <h1>{{ $page->h1 }}</h1>
      <p class="lede">{{ $page->intro }}</p>
    </div>
  </header>
  <section class="band" style="padding-top:0.5rem" aria-labelledby="vehicles-heading">
    <div class="wrap">
      <h2 id="vehicles-heading" class="section-title">Vehicles</h2>
      <div class="card-grid two">
        @foreach($vehicles as $vehicle)
          @include('front.partials.vehicle-card', ['vehicle' => $vehicle])
        @endforeach
      </div>
    </div>
  </section>
  <section class="band" style="background:#fff" aria-labelledby="packages-heading">
    <div class="wrap">
      <h2 id="packages-heading" class="section-title">Sightseeing from Poombarai</h2>
      <p class="lede">These are starting sedan fares. A guide, a larger car, or a forest permit changes the number. We confirm it on WhatsApp.</p>
      <div class="card-grid two" style="margin-top:1.25rem">
        @foreach($packages as $package)
          @include('front.partials.package-card', ['package' => $package])
        @endforeach
      </div>
    </div>
  </section>
  <section class="band" aria-labelledby="car-form-heading">
    <div class="wrap" style="max-width:46rem">
      <div class="enquiry-card">
        @include('front.partials.car-form', ['prefix' => 'c', 'heading' => 'Book a car and guide'])
      </div>
    </div>
  </section>
@endsection

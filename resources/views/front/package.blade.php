@extends('layouts.site')

@php
  $seoBase = rtrim($site['site_url'] ?: url('/'), '/');
  $packageUrl = $seoBase.'/packages/'.$package->slug;
  $packageImage = $package->imageSrc();
  if ($packageImage && ! preg_match('#^https?://#i', $packageImage)) {
      $packageImage = $seoBase.'/'.ltrim($packageImage, '/');
  }
@endphp

@push('head')
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'BreadcrumbList',
  'itemListElement' => [
    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $seoBase.'/'],
    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Packages', 'item' => $seoBase.'/#packages-heading'],
    ['@type' => 'ListItem', 'position' => 3, 'name' => $package->name, 'item' => $packageUrl],
  ],
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'TouristTrip',
  'name' => $package->name,
  'description' => $package->summary ?: ('Sightseeing package from Poombarai village near Kodaikanal: '.$package->name),
  'url' => $packageUrl,
  'image' => array_values(array_filter(array_map(function ($frame) use ($seoBase) {
      $src = $frame['src'] ?? '';
      if (! $src) {
          return null;
      }
      if (preg_match('#^https?://#i', $src)) {
          return $src;
      }

      return $seoBase.'/'.ltrim($src, '/');
  }, $frames))),
  'touristType' => ['Family', 'Couples', 'Groups'],
  'itinerary' => [
      '@type' => 'ItemList',
      'name' => $package->name.' stops',
      'itemListElement' => $package->destinations->values()->map(function ($place, $index) {
          return [
              '@type' => 'ListItem',
              'position' => $index + 1,
              'name' => $place->name,
          ];
      })->all(),
  ],
  'provider' => [
      '@type' => 'LodgingBusiness',
      'name' => $site['business_name'],
      'url' => $seoBase.'/',
      'telephone' => $site['phone'],
  ],
  'offers' => [
      '@type' => 'Offer',
      'url' => $packageUrl,
      'priceCurrency' => 'INR',
      'availability' => 'https://schema.org/InStock',
      'category' => 'Enquiry only',
      'description' => $package->price_label ?: 'Fare confirmed on WhatsApp. Nothing is paid on this website.',
  ],
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
  <header class="page-intro package-page-intro">
    <div class="wrap">
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('home') }}#packages-heading">Packages</a>
        <span aria-hidden="true">/</span>
        <span aria-current="page">{{ $package->name }}</span>
      </nav>
      <p class="eyebrow">Poombarai · Kodaikanal sightseeing</p>
      <h1>{{ $package->name }}</h1>
      @if($package->duration)
        <p class="kicker">{{ $package->duration }} from the village</p>
      @endif
      @if($package->summary)
        <p class="lede">{{ $package->summary }}</p>
      @else
        <p class="lede">A village-based outing from Poombarai near Kodaikanal. Enquire on WhatsApp for the fare and dates. Nothing is booked or paid on this site.</p>
      @endif
      @if($package->destinations->isNotEmpty())
        <p class="lede">Stops may include {{ $package->destinations->pluck('name')->join(', ') }}.</p>
      @endif
    </div>
  </header>

  <section class="band package-page" aria-labelledby="package-gallery-heading">
    <div class="wrap package-page-layout">
      <h2 id="package-gallery-heading" class="visually-hidden">Photos of {{ $package->name }} in Poombarai, Kodaikanal</h2>
      @include('front.partials.gallery', [
        'images' => $frames,
        'kind' => 'package',
        'id' => $package->slug,
        'label' => $package->name.' Poombarai package',
        'wide' => true,
      ])
      <div class="package-page-actions">
        <button type="button" class="btn btn-wa" data-open-enquiry data-service="package" data-item-name="{{ $package->name }}">Enquire</button>
        <a class="btn btn-line" href="{{ route('home') }}#packages-heading">All packages</a>
        <a class="btn btn-line" href="{{ route('contact') }}">Contact</a>
      </div>
      @if($related->isNotEmpty())
        <div class="section-head" style="margin-top:0.5rem">
          <h2 class="section-title" style="font-size:1.45rem">More packages from Poombarai</h2>
          <p class="lede">
            @foreach($related as $other)
              <a class="text-link" href="{{ route('packages.show', $other) }}">{{ $other->name }}</a>@if(! $loop->last)<span aria-hidden="true"> · </span>@endif
            @endforeach
          </p>
        </div>
      @endif
    </div>
  </section>
@endsection

@extends('layouts.site')

@push('head')
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'BreadcrumbList',
  'itemListElement' => [
    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => rtrim($site['site_url'], '/').'/'],
    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Stays in Poombarai', 'item' => rtrim($site['site_url'], '/').'/stays'],
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
      <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a> <span aria-hidden="true">/</span> <span aria-current="page">Stays</span></nav>
      <p class="eyebrow">{{ $page->eyebrow }}</p>
      <h1>{{ $page->h1 }}</h1>
      <p class="lede">{{ $page->intro }}</p>
    </div>
  </header>
  <section class="band" style="padding-top:0.5rem">
    <div class="wrap rooms-layout">
      <aside class="enquiry-aside">
        <div class="enquiry-card">
          @include('front.partials.stay-form', ['prefix' => 'r', 'heading' => 'Check these dates'])
        </div>
      </aside>
      <div>
        <div class="filter-bar" role="group" aria-label="Filter stays">
          <button type="button" data-filter="all" class="is-active" aria-pressed="true">All</button>
          <button type="button" data-filter="Room" aria-pressed="false">Rooms</button>
          <button type="button" data-filter="Cottage" aria-pressed="false">Cottages</button>
          <button type="button" data-filter="Homestay" aria-pressed="false">Homestays</button>
        </div>
        <p data-filter-count></p>
        <div class="listing-grid card-grid">
          @foreach($rooms as $room)
            @include('front.partials.room-card', ['room' => $room, 'compact' => false])
          @endforeach
        </div>
        <p data-empty hidden>No stays in this group right now.</p>
      </div>
    </div>
  </section>
@endsection

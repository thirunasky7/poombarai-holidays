@extends('layouts.site')

@section('content')
  <header class="page-intro package-page-intro">
    <div class="wrap">
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span aria-hidden="true">/</span>
        <span aria-current="page">{{ $package->name }}</span>
      </nav>
      <h1>{{ $package->name }}</h1>
    </div>
  </header>

  <section class="band package-page" aria-labelledby="package-gallery-heading">
    <div class="wrap package-page-layout">
      <h2 id="package-gallery-heading" class="visually-hidden">Photos of {{ $package->name }}</h2>
      @include('front.partials.gallery', [
        'images' => $frames,
        'kind' => 'package',
        'id' => $package->slug,
        'label' => $package->name,
        'wide' => true,
      ])
      <div class="package-page-actions">
        <button type="button" class="btn btn-wa" data-open-enquiry data-service="package" data-item-name="{{ $package->name }}">Enquire</button>
        <a class="btn btn-line" href="{{ route('home') }}#packages-heading">All packages</a>
      </div>
    </div>
  </section>
@endsection

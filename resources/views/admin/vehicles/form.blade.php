@extends('layouts.admin')
@php
  $rates = collect($vehicle->rates ?? []);
  $local = $rates->firstWhere('label', 'Local hire') ?? [];
  $extra = $rates->firstWhere('label', 'Extra kilometre') ?? [];
  $out = $rates->firstWhere('label', 'Outstation') ?? [];
@endphp
@section('title', $vehicle->exists ? 'Edit vehicle' : 'New vehicle')
@section('content')
  <div class="admin-top"><h1>{{ $vehicle->exists ? 'Edit vehicle' : 'New vehicle' }}</h1><a class="btn line" href="{{ route('admin.vehicles.index') }}">Back</a></div>
  <form class="card grid" method="post" action="{{ $vehicle->exists ? route('admin.vehicles.update', $vehicle) : route('admin.vehicles.store') }}" enctype="multipart/form-data">
    @csrf
    @if($vehicle->exists) @method('PUT') @endif
    <div class="grid two">
      <div><label for="name">Name</label><input id="name" name="name" value="{{ old('name', $vehicle->name) }}" required></div>
      <div><label for="example">Example model</label><input id="example" name="example" value="{{ old('example', $vehicle->example) }}"></div>
      <div><label for="seats">Seats</label><input id="seats" name="seats" type="number" min="1" value="{{ old('seats', $vehicle->seats) }}" required></div>
      <div><label for="price_label">Price label</label><input id="price_label" name="price_label" value="{{ old('price_label', $vehicle->price_label) }}" required></div>
      <div><label for="local_value">Local hire fare</label><input id="local_value" name="local_value" value="{{ old('local_value', $local['value'] ?? '') }}" required></div>
      <div><label for="local_detail">Local hire detail</label><input id="local_detail" name="local_detail" value="{{ old('local_detail', $local['detail'] ?? '') }}"></div>
      <div><label for="extra_km">Extra kilometre</label><input id="extra_km" name="extra_km" value="{{ old('extra_km', $extra['value'] ?? '') }}" required></div>
      <div><label for="outstation">Outstation</label><input id="outstation" name="outstation" value="{{ old('outstation', $out['value'] ?? '') }}" required></div>
      <div><label for="sort_order">Sort order</label><input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', $vehicle->sort_order ?? 0) }}"></div>
    </div>
    <div><label for="blurb">Description</label><textarea id="blurb" name="blurb" required>{{ old('blurb', $vehicle->blurb) }}</textarea></div>
    <div class="checks">
      <label><input type="checkbox" name="ac" value="1" @checked(old('ac', $vehicle->ac ?? true))> Air conditioned</label>
      <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $vehicle->exists ? $vehicle->is_active : true))> Visible</label>
    </div>
    <div>
      <label for="image">Photo</label>
      @if($vehicle->imageSrc())<img class="thumb" src="{{ $vehicle->imageSrc() }}" alt="">@endif
      <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp">
    </div>
    <div><label for="image_alt">Photo description</label><input id="image_alt" name="image_alt" value="{{ old('image_alt', $vehicle->image_alt) }}" required></div>
    <button class="btn" type="submit">Save vehicle</button>
  </form>
  @if($vehicle->exists)
    <form method="post" action="{{ route('admin.vehicles.destroy', $vehicle) }}" onsubmit="return confirm('Remove this vehicle?')" style="margin-top:1rem">@csrf @method('DELETE')<button class="btn danger" type="submit">Delete</button></form>
  @endif
@endsection

@extends('layouts.admin')
@section('title', $package->exists ? 'Edit package' : 'New package')
@section('content')
  @php $selected = old('destinations', $package->exists ? $package->destinations->pluck('id')->all() : []); @endphp
  <div class="admin-top"><h1>{{ $package->exists ? 'Edit package' : 'New package' }}</h1><a class="btn line" href="{{ route('admin.packages.index') }}">Back</a></div>
  <form class="card grid" method="post" action="{{ $package->exists ? route('admin.packages.update', $package) : route('admin.packages.store') }}" enctype="multipart/form-data">
    @csrf
    @if($package->exists) @method('PUT') @endif
    <div class="grid two">
      <div><label for="name">Name</label><input id="name" name="name" value="{{ old('name', $package->name) }}" required></div>
      <div><label for="duration">Duration</label><input id="duration" name="duration" value="{{ old('duration', $package->duration) }}" required></div>
      <div><label for="fare">Fare</label><input id="fare" name="fare" value="{{ old('fare', $package->fare) }}" required></div>
      <div><label for="price_label">Price note</label><input id="price_label" name="price_label" value="{{ old('price_label', $package->price_label) }}" required></div>
      <div><label for="sort_order">Sort order</label><input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', $package->sort_order ?? 0) }}"></div>
    </div>
    <div><label for="summary">Summary</label><textarea id="summary" name="summary" required>{{ old('summary', $package->summary) }}</textarea></div>
    <fieldset>
      <legend>Places included</legend>
      <div class="checks">
        @foreach($destinations as $place)
          <label><input type="checkbox" name="destinations[]" value="{{ $place->id }}" @checked(in_array($place->id, $selected))> {{ $place->name }}</label>
        @endforeach
      </div>
    </fieldset>
    <div class="checks">
      <label><input type="checkbox" name="featured" value="1" @checked(old('featured', $package->featured))> Homepage</label>
      <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $package->exists ? $package->is_active : true))> Visible</label>
    </div>
    <div>
      <label for="image">Thumbnail photo</label>
      @if($package->imageSrc())<img class="thumb" src="{{ $package->imageSrc() }}" alt="">@endif
      <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp">
    </div>
    <div><label for="image_alt">Thumbnail description</label><input id="image_alt" name="image_alt" value="{{ old('image_alt', $package->image_alt) }}" required></div>
    @if($package->exists)
      <fieldset>
        <legend>Gallery photos</legend>
        @forelse($package->images as $image)
          <div class="grid two" style="margin-bottom:0.8rem">
            <img class="thumb" src="{{ $image->src() }}" alt="">
            <div>
              <label for="alt-{{ $image->id }}">Description</label>
              <input id="alt-{{ $image->id }}" name="existing_alts[{{ $image->id }}]" value="{{ $image->alt }}">
              <label><input type="checkbox" name="remove_images[]" value="{{ $image->id }}"> Remove this photo</label>
            </div>
          </div>
        @empty
          <p>No gallery photos yet.</p>
        @endforelse
      </fieldset>
    @endif
    <div>
      <label for="photos">Add gallery photos (JPEG, PNG or WebP, up to 4 MB each)</label>
      <input id="photos" name="photos[]" type="file" accept="image/jpeg,image/png,image/webp" multiple>
    </div>
    <button class="btn" type="submit">Save package</button>
  </form>
  @if($package->exists)
    <p style="margin-top:1rem"><a href="{{ route('packages.show', $package) }}" target="_blank" rel="noopener">Open package page</a></p>
    <form method="post" action="{{ route('admin.packages.destroy', $package) }}" onsubmit="return confirm('Remove this package?')" style="margin-top:1rem">@csrf @method('DELETE')<button class="btn danger" type="submit">Delete</button></form>
  @endif
@endsection

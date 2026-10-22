@extends('layouts.admin')
@section('title', $room->exists ? 'Edit stay' : 'New stay')
@section('content')
  <div class="admin-top">
    <h1>{{ $room->exists ? 'Edit stay' : 'New stay' }}</h1>
    <a class="btn line" href="{{ route('admin.rooms.index') }}">Back</a>
  </div>
  <form class="card grid" method="post" action="{{ $room->exists ? route('admin.rooms.update', $room) : route('admin.rooms.store') }}" enctype="multipart/form-data">
    @csrf
    @if($room->exists) @method('PUT') @endif
    <div class="grid two">
      <div><label for="name">Name</label><input id="name" name="name" value="{{ old('name', $room->name) }}" required></div>
      <div><label for="slug">Slug</label><input id="slug" name="slug" value="{{ old('slug', $room->slug) }}" placeholder="Filled from the name if empty"></div>
      <div>
        <label for="type">Type</label>
        <select id="type" name="type">
          @foreach(['Room', 'Cottage', 'Homestay'] as $type)
            <option value="{{ $type }}" @selected(old('type', $room->type) === $type)>{{ $type }}</option>
          @endforeach
        </select>
      </div>
      <div><label for="price_label">Price label</label><input id="price_label" name="price_label" value="{{ old('price_label', $room->price_label) }}" required></div>
      <div><label for="occupancy">Guests</label><input id="occupancy" name="occupancy" type="number" min="1" max="20" value="{{ old('occupancy', $room->occupancy) }}" required></div>
      <div><label for="sort_order">Sort order</label><input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', $room->sort_order ?? 0) }}"></div>
    </div>
    <div><label for="location">Location</label><input id="location" name="location" value="{{ old('location', $room->location) }}" required></div>
    <div><label for="summary">Summary</label><textarea id="summary" name="summary" required>{{ old('summary', $room->summary) }}</textarea></div>
    <div><label for="amenities">Amenities, separated by commas</label><input id="amenities" name="amenities" value="{{ old('amenities', implode(', ', $room->amenities ?? [])) }}"></div>
    <div class="checks">
      <label><input type="checkbox" name="featured" value="1" @checked(old('featured', $room->featured))> Show on the homepage</label>
      <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $room->exists ? $room->is_active : true))> Visible on the site</label>
    </div>
    @if($room->exists)
      <fieldset>
        <legend>Current photos</legend>
        @forelse($room->images as $image)
          <div class="grid two" style="margin-bottom:0.8rem">
            <img class="thumb" src="{{ $image->src() }}" alt="">
            <div>
              <label for="alt-{{ $image->id }}">Description</label>
              <input id="alt-{{ $image->id }}" name="existing_alts[{{ $image->id }}]" value="{{ $image->alt }}">
              <label><input type="checkbox" name="remove_images[]" value="{{ $image->id }}"> Remove this photo</label>
            </div>
          </div>
        @empty
          <p>No photos yet.</p>
        @endforelse
      </fieldset>
    @endif
    <div>
      <label for="photos">Add photos (JPEG, PNG or WebP, up to 4 MB each)</label>
      <input id="photos" name="photos[]" type="file" accept="image/jpeg,image/png,image/webp" multiple>
    </div>
    <button class="btn" type="submit">Save stay</button>
  </form>
  @if($room->exists)
    <form method="post" action="{{ route('admin.rooms.destroy', $room) }}" onsubmit="return confirm('Remove this stay?')" style="margin-top:1rem">
      @csrf
      @method('DELETE')
      <button class="btn danger" type="submit">Delete stay</button>
    </form>
  @endif
@endsection

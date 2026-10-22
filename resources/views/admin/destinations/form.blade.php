@extends('layouts.admin')
@section('title', $destination->exists ? 'Edit place' : 'New place')
@section('content')
  <div class="admin-top"><h1>{{ $destination->exists ? 'Edit place' : 'New place' }}</h1><a class="btn line" href="{{ route('admin.destinations.index') }}">Back</a></div>
  <form class="card grid" method="post" action="{{ $destination->exists ? route('admin.destinations.update', $destination) : route('admin.destinations.store') }}" enctype="multipart/form-data">
    @csrf
    @if($destination->exists) @method('PUT') @endif
    <div class="grid two">
      <div><label for="name">Name</label><input id="name" name="name" value="{{ old('name', $destination->name) }}" required></div>
      <div><label for="name_ta">Tamil name</label><input id="name_ta" name="name_ta" value="{{ old('name_ta', $destination->name_ta) }}"></div>
      <div><label for="best_time">Best time</label><input id="best_time" name="best_time" value="{{ old('best_time', $destination->best_time) }}" required></div>
      <div><label for="time_needed">Time needed</label><input id="time_needed" name="time_needed" value="{{ old('time_needed', $destination->time_needed) }}" required></div>
      <div><label for="sort_order">Sort order</label><input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', $destination->sort_order ?? 0) }}"></div>
    </div>
    <div><label for="description">Description</label><textarea id="description" name="description" required>{{ old('description', $destination->description) }}</textarea></div>
    <div class="checks">
      <label><input type="checkbox" name="popular" value="1" @checked(old('popular', $destination->popular))> Show in the homepage scroller</label>
      <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $destination->exists ? $destination->is_active : true))> Visible</label>
    </div>
    <div>
      <label for="image">Photo</label>
      @if($destination->imageSrc())<img class="thumb" src="{{ $destination->imageSrc() }}" alt="">@endif
      <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp">
    </div>
    <div><label for="image_alt">Photo description</label><input id="image_alt" name="image_alt" value="{{ old('image_alt', $destination->image_alt) }}" required></div>
    <button class="btn" type="submit">Save place</button>
  </form>
  @if($destination->exists)
    <form method="post" action="{{ route('admin.destinations.destroy', $destination) }}" onsubmit="return confirm('Remove this place?')" style="margin-top:1rem">@csrf @method('DELETE')<button class="btn danger" type="submit">Delete</button></form>
  @endif
@endsection

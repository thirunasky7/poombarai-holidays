@extends('layouts.admin')
@section('title', $banner->exists ? 'Edit banner' : 'New banner')
@section('content')
  <div class="admin-top"><h1>{{ $banner->exists ? 'Edit banner' : 'New banner' }}</h1><a class="btn line" href="{{ route('admin.banners.index') }}">Back</a></div>
  <form class="card grid" method="post" action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" enctype="multipart/form-data">
    @csrf
    @if($banner->exists) @method('PUT') @endif
    <div><label for="title">Title</label><input id="title" name="title" value="{{ old('title', $banner->title) }}" required maxlength="80"></div>
    <div><label for="text">Short line</label><input id="text" name="text" value="{{ old('text', $banner->text) }}" required maxlength="240"></div>
    <div class="grid two">
      <div><label for="button_label">Button label</label><input id="button_label" name="button_label" value="{{ old('button_label', $banner->button_label) }}"></div>
      <div><label for="button_url">Button link</label><input id="button_url" name="button_url" value="{{ old('button_url', $banner->button_url) }}" placeholder="/stays"></div>
      <div><label for="sort_order">Order</label><input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', $banner->sort_order ?? 0) }}"></div>
    </div>
    <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $banner->exists ? $banner->is_active : true))> Show on the homepage</label>
    <div>
      <label for="image">Photo</label>
      @if($banner->imageSrc())<img class="thumb" src="{{ $banner->imageSrc() }}" alt="">@endif
      <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp">
    </div>
    <div><label for="image_alt">Photo description</label><input id="image_alt" name="image_alt" value="{{ old('image_alt', $banner->image_alt) }}" required></div>
    <button class="btn" type="submit">Save banner</button>
  </form>
  @if($banner->exists)
    <form method="post" action="{{ route('admin.banners.destroy', $banner) }}" onsubmit="return confirm('Remove this banner?')" style="margin-top:1rem">@csrf @method('DELETE')<button class="btn danger" type="submit">Delete</button></form>
  @endif
@endsection

@extends('layouts.admin')
@section('title', 'Home banners')
@section('content')
  <div class="admin-top"><h1>Home banners</h1><a class="btn" href="{{ route('admin.banners.create') }}">Add banner</a></div>
  <p>These photos rotate at the top of the homepage. Keep the title to a few words.</p>
  <div class="table-wrap"><table>
    <thead><tr><th></th><th>Title</th><th>Order</th><th></th></tr></thead>
    <tbody>
      @foreach($banners as $banner)
        <tr>
          <td>@if($banner->imageSrc())<img class="thumb" src="{{ $banner->imageSrc() }}" alt="">@endif</td>
          <td>{{ $banner->title }}@unless($banner->is_active) <em>(hidden)</em>@endunless</td>
          <td>{{ $banner->sort_order }}</td>
          <td><a href="{{ route('admin.banners.edit', $banner) }}">Edit</a></td>
        </tr>
      @endforeach
    </tbody>
  </table></div>
@endsection

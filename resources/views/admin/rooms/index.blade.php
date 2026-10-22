@extends('layouts.admin')
@section('title', 'Stays')
@section('content')
  <div class="admin-top"><h1>Stays and photos</h1><a class="btn" href="{{ route('admin.rooms.create') }}">Add stay</a></div>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Photo</th><th>Stay</th><th>Type</th><th>Price</th><th></th></tr></thead>
      <tbody>
        @foreach($rooms as $room)
          <tr>
            <td>@if($room->cover())<img class="thumb" src="{{ $room->cover()->src() }}" alt="">@endif</td>
            <td>{{ $room->name }}@unless($room->is_active) <em>(hidden)</em>@endunless</td>
            <td>{{ $room->type }}</td>
            <td>{{ $room->price_label }}</td>
            <td><a href="{{ route('admin.rooms.edit', $room) }}">Edit</a></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection

@extends('layouts.admin')
@section('title', 'Places')
@section('content')
  <div class="admin-top"><h1>Places</h1><a class="btn" href="{{ route('admin.destinations.create') }}">Add place</a></div>
  <div class="table-wrap"><table>
    <thead><tr><th></th><th>Place</th><th>Popular</th><th></th></tr></thead>
    <tbody>
      @foreach($destinations as $destination)
        <tr>
          <td>@if($destination->imageSrc())<img class="thumb" src="{{ $destination->imageSrc() }}" alt="">@endif</td>
          <td>{{ $destination->name }}</td>
          <td>{{ $destination->popular ? 'Yes' : 'No' }}</td>
          <td><a href="{{ route('admin.destinations.edit', $destination) }}">Edit</a></td>
        </tr>
      @endforeach
    </tbody>
  </table></div>
@endsection

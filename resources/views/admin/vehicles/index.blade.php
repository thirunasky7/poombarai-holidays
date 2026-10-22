@extends('layouts.admin')
@section('title', 'Vehicles')
@section('content')
  <div class="admin-top"><h1>Vehicles</h1><a class="btn" href="{{ route('admin.vehicles.create') }}">Add vehicle</a></div>
  <div class="table-wrap"><table>
    <thead><tr><th></th><th>Name</th><th>Seats</th><th>Price</th><th></th></tr></thead>
    <tbody>
      @foreach($vehicles as $vehicle)
        <tr>
          <td>@if($vehicle->imageSrc())<img class="thumb" src="{{ $vehicle->imageSrc() }}" alt="">@endif</td>
          <td>{{ $vehicle->name }}</td>
          <td>{{ $vehicle->seats }}</td>
          <td>{{ $vehicle->price_label }}</td>
          <td><a href="{{ route('admin.vehicles.edit', $vehicle) }}">Edit</a></td>
        </tr>
      @endforeach
    </tbody>
  </table></div>
@endsection

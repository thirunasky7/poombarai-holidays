@extends('layouts.admin')
@section('title', 'Packages')
@section('content')
  <div class="admin-top"><h1>Packages</h1><a class="btn" href="{{ route('admin.packages.create') }}">Add package</a></div>
  <div class="table-wrap"><table>
    <thead><tr><th></th><th>Package</th><th>Fare</th><th>Places</th><th></th></tr></thead>
    <tbody>
      @foreach($packages as $package)
        <tr>
          <td>@if($package->imageSrc())<img class="thumb" src="{{ $package->imageSrc() }}" alt="">@endif</td>
          <td>{{ $package->name }}</td>
          <td>{{ $package->fare }}</td>
          <td>{{ $package->destinations->pluck('name')->join(', ') }}</td>
          <td><a href="{{ route('admin.packages.edit', $package) }}">Edit</a></td>
        </tr>
      @endforeach
    </tbody>
  </table></div>
@endsection

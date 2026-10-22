@extends('layouts.admin')
@section('title', 'Pages')
@section('content')
  <div class="admin-top"><h1>Pages and SEO</h1></div>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Page</th><th>Title</th><th></th></tr></thead>
      <tbody>
        @foreach($pages as $page)
          <tr>
            <td>{{ $page->nav_label }}</td>
            <td>{{ $page->meta_title }}</td>
            <td><a href="{{ route('admin.pages.edit', $page) }}">Edit</a></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection

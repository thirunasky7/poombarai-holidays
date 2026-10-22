@extends('layouts.admin')
@section('title', 'FAQs')
@section('content')
  <div class="admin-top"><h1>Questions</h1><a class="btn" href="{{ route('admin.faqs.create') }}">Add question</a></div>
  <div class="table-wrap"><table>
    <tbody>
      @foreach($faqs as $faq)
        <tr><td>{{ $faq->question }}</td><td><a href="{{ route('admin.faqs.edit', $faq) }}">Edit</a></td></tr>
      @endforeach
    </tbody>
  </table></div>
@endsection

@extends('layouts.app')
@section('title', $pageTitle)
@section('content')
  <section class="space-y-6">
    <div class="card ribbon-box">
      <div class="card-body">
        <div class="ribbon ribbon-primary float-start"><i class="mdi mdi-access-point me-1"></i> {{ $article->title }}</div>
        <div class="ribbon-content p-6">
          {!! $article->content !!}
        </div>
      </div>
    </div>
  </section>
@endsection

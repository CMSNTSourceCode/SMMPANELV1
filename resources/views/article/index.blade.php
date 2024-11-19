@extends('layouts.app')
@section('title', $pageTitle)
@section('content')
  <section class="space-y-6">
    <div class="text-center">
      <h1 class="fw-bold text-danger mb-3" style="font-size: 30px">Danh Sách Bài Viết Hướng Dẫn</h1>
    </div>

    <div class="row">
      @foreach ($articles as $post)
        <div class="col-md-2">
          <div class="card">
            <div class="mb-2">
              <a href="{{ route('articles.show', ['slug' => $post->slug]) }}">
                <img data-src="{{ asset($post->thumbnail) }}" alt="{{ $post->title }}" class="lazyload w-full h-full object-cover rounded-t-lg" style="height: 210px; width: 100%; border-radius: 5px 5px 0 0">
              </a>
            </div>
            <div class=" mb-3 text-start font-bold" style="padding: 10px">
              <a href="{{ route('articles.show', ['slug' => $post->slug]) }}" class="text-lg">{{ $post->title }}</a>
            </div>
            <div class="d-flex justify-content-between" style="padding: 10px">
              <a href="#!" class="btn btn-sm btn-outline-primary">Ngày: {{ $post->created_at }}</a>
              <a href="{{ route('articles.show', ['slug' => $post->slug]) }}" class="btn btn-sm btn-primary">Xem Thêm <i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="mt-3">
      {{ $articles->links() }}
    </div>
  </section>
@endsection

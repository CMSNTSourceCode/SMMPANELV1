@php use App\Helpers\Helper; @endphp
@extends('layouts.app')
@section('title', $pageTitle)
@section('css')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

  <style>
    .new-feeds {
      max-height: 483px;
      overflow-y: auto;
    }

    [v-cloak] {
      width: 100%;
      height: 600px;
      background: url('https://i.gifer.com/ZKZg.gif') no-repeat center center;
    }
  </style>
@endsection
@section('content')
  @php
    $userDiscount = Helper::getDiscountByRank(auth()->user()->rank ?? 'bronze');
  @endphp
  <div class="alert alert-primary">
    {!! Helper::getNotice('page_new_order') !!}
  </div>
  <div class="row">
    <div class="col-12 col-lg-8 col-md-8 col-sm-12">
      <div class="card custom-card">
        <div class="card-header">
          <h3 class="card-title">{{ __t('Tạo đơn hàng') }}</h3>
        </div>
        <div class="card-body" id="app" v-cloak>
          <baocms-form-buy />
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-4 col-md-4 col-sm-12">
      <div class="card overflow-hidden border-0 p-0 text-nowrap">
        <div class="min-vh-25 p-4" style="background: linear-gradient(to right, #4361ee, #160f6b);">
          <div class="mb-4 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center rounded-pill bg-opacity-50 bg-dark p-1 text-white fw-semibold pe-3">
              <img class="rounded-circle border-white me-2" src="{{ setting('avatar_user', '/assets/images/faces/9.jpg') }}" alt="image" style="height: 2rem; width: 2rem; object-cover;">
              {{ auth()->user()->username ?? 'Chưa đăng nhập' }}
            </div>
            <a href="{{ route('account.deposits.transfer') }}" class="btn btn-dark d-flex align-items-center justify-content-center rounded-circle p-0" style="height: 2.25rem; width: 2.25rem;">
              <svg class="m-auto" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round" style="height: 1.5rem; width: 1.5rem;">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
              </svg>
            </a>
          </div>
          <div class="d-flex align-items-center justify-content-between ">
            <p class="h5 mb-0 text-white">{{ __t('Số dư') }}</p>
            <h5 class="h4 mb-0 ms-auto text-white"><span><span class="user-balance">{{ formatCurrency(auth()->user()->balance ?? 0) }}</span></span></h5>
          </div>
        </div>
        <div class="row g-2 px-4" style="margin-top: -20px">
          <div class="col-6">
            <div class="rounded bg-white p-3 shadow-sm dark-bg">
              <span class="d-flex align-items-center justify-content-between text-dark">
                {{ __t('Tổng nạp') }}
                <svg class="text-success" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="height: 1rem; width: 1rem;">
                  <path d="M19 15L12 9L5 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </span>
              <div class="btn w-100 bg-light text-dark fw-semibold border-0 py-1 mt-2">{{ formatCurrency(auth()->user()->total_deposit ?? 0) }}</div>
            </div>
          </div>
          <div class="col-6">
            <div class="rounded bg-white p-3 shadow-sm dark-bg">
              <span class="d-flex align-items-center justify-content-between text-dark">
                {{ __t('Tổng tiêu') }}
                <svg class="text-danger" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="height: 1rem; width: 1rem;">
                  <path d="M19 9L12 15L5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </span>
              <div class="btn w-100 bg-light text-dark fw-semibold border-0 py-1 mt-2">{{ formatCurrency((auth()->user()->total_deposit ?? 0) - (auth()->user()->balance ?? 0)) }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="text-center">
        <h3>{{ __t('Thông báo mới') }}</h3>
      </div>
      <div class="new-feeds">
        @foreach ($posts as $post)
          <div class="card">
            <div class="card-body">
              <h5>----- {{ $post->created_at }}</h5>
              <hr />
              <div class="card-content">
                {!! $post->content !!}
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- Modal -->
  @if ($msgModal = Helper::getNotice('modal_dashboard'))
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __t('Thông báo mới') }}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            {!! $msgModal !!}
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __t('Đóng') }}</button>
          </div>
        </div>
      </div>
    </div>
  @endif
@endsection
@section('scripts')
  @vite('resources/js/modules/services/form/index.js')

  <script>
    $(document).ready(function() {
      // Kiểm tra nếu đã hiển thị modal trong vòng 30 phút trước đó
      var lastShownTime = localStorage.getItem('lastShownTime');
      var currentTime = new Date().getTime();
      var timeDiff = currentTime - lastShownTime;

      if (lastShownTime === null || timeDiff > 30 * 60 * 1000) {
        // Nếu chưa hiển thị trong vòng 30 phút, hiển thị modal
        $('#exampleModal').modal('show');

        // Lưu thời điểm hiển thị modal
        localStorage.setItem('lastShownTime', currentTime);
      }
    });
  </script>
@endsection

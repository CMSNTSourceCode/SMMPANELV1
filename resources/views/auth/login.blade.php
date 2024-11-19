@extends('layouts.guest')
@section('title', __t('Đăng Nhập Vào Hệ Thống'))
@section('content')
  <!-- PAGE -->
  <div class="page login-page">
    <div>
      <!-- CONTAINER OPEN -->
      <div class="col col-login mx-auto mt-7">
        <div class="text-center">
          <a href="{{ route('home') }}">
            <img src="{{ setting('logo_light', '/assets/images/brand-logos/desktop-white.png') }}" class="header-brand-img" width="180px">
          </a>
        </div>
      </div>
      <div class="container-login100">
        <div class="card  wrap-login100 p-0">
          <div class="card-body">
            @include('layouts.include.alert')
            <form class="login100-form validate-form form-login" method="POST" action="{{ route('login') }}">
              @csrf
              <span class="login100-form-title">
                {{ __t('Đăng Nhập') }}
              </span>
              <div class="wrap-input100 validate-input">
                <input type="text" class="form-control input100" name="username" id="username" placeholder="{{ __t('Tên tài khoản') }}" value="{{ env('PRJ_DEMO_MODE', false) === true ? 'admin' : '' }}">
                <span class="focus-input100"></span>
                <span class="symbol-input100">
                  <i class="ri-user-fill" aria-hidden="true"></i>
                </span>
              </div>
              <div class="wrap-input100 validate-input" data-bs-validate = "Password is required">
                <input type="password" class="form-control input100" name="password" id="password" placeholder="{{ __t('Mật khẩu truy cập') }}" value="{{ env('PRJ_DEMO_MODE', false) === true ? 'demo123' : '' }}">
                <span class="focus-input100"></span>
                <span class="symbol-input100">
                  <i class="ri-lock-fill" aria-hidden="true"></i>
                </span>
              </div>
              <div class="text-end pt-1">
                <p class="mb-0"><a href="{{ route('password.request') }}" target="_blank" class="text-primary ms-1">{{ __t('Quên mật khẩu?') }}</a></p>
              </div>
              <div class="container-login100-form-btn">
                <button type="submit" class="login100-form-btn btn-primary">
                  {{ __t('Đăng Nhập Ngay') }}
                </button>
              </div>
              <div class="text-center pt-3">
                <p class="text-dark mb-0">{{ __t('Chưa có tài khoản?') }}<a href="{{ route('register') }}" class="text-primary ms-1">{{ __t('Đăng ký ngay') }}</a></p>
              </div>
            </form>
          </div>
          <div class="card-footer border-top">
            <div class="d-flex justify-content-center my-3">
              <a href="javascript:void(0);" class="social-login  text-center">
                <i class="ri-google-fill"></i>
              </a>
              <a href="javascript:void(0);" class="social-login  text-center mx-4">
                <i class="ri-facebook-fill"></i>
              </a>
              <a href="javascript:void(0);" class="social-login  text-center">
                <i class="ri-twitter-fill"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
      <!-- CONTAINER CLOSED -->
    </div>
  </div>
  <!-- End PAGE -->
@endsection
@section('scripts')
  <script>
    $(document).ready(function() {
      $(".form-login").submit(async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target),
          action = e.target.action,
          method = e.target.method,
          button = $(e.target).find('button[type="submit"]');
        const payload = $formDataToPayload(formData);
        payload.remember = payload.remember === "on" ? true : false;

        $setLoading(button);

        try {
          const {
            data: result
          } = await axios.post(action, payload);
          const message = result?.message || 'Can\'t not process your request';

          Swal.fire({
            title: '{{ __t('Thành công') }}',
            text: result.message,
            icon: 'success',
            showConfirmButton: false,
            timer: 10000,
            allowOutsideClick: false,
          })

          setTimeout(() => {
            window.location.href = result.data.redirect;
          }, 1000);
        } catch (error) {
          const errors = error?.response?.data?.errors || null;

          if (errors !== null) {
            for (const [key, value] of Object.entries(errors)) {
              $(`#${key}`).addClass("is-invalid");
              $(`#${key}-error`).html(value);
            }
          }
          $swal("error", $catchMessage(error));

          $removeLoading(button);
        } finally {}
      });
    });
  </script>
@endsection

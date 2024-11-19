@extends('layouts.guest')
@section('title', __t('Đăng Ký Tài Khoản'))
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
            <form class="login100-form validate-form form-register" method="POST" action="{{ route('register') }}">
              @csrf

              <span class="login100-form-title">
                {{ __t('Đăng Ký') }}
              </span>

              <div class="wrap-input100 validate-input">
                <input type="email" class="form-control input100" name="email" id="email" placeholder="{{ __t('Địa chỉ email') }}">
                <span class="focus-input100"></span>
                <span class="symbol-input100">
                  <i class="ri-mail-fill" aria-hidden="true"></i>
                </span>
              </div>

              <div class="wrap-input100 validate-input">
                <input type="text" class="form-control input100" name="username" id="username" placeholder="{{ __t('Tên tài khoản') }}">
                <span class="focus-input100"></span>
                <span class="symbol-input100">
                  <i class="ri-user-fill" aria-hidden="true"></i>
                </span>
              </div>

              <div class="wrap-input100 validate-input" data-bs-validate = "Password is required">
                <input type="password" class="form-control input100" name="password" id="password" placeholder="{{ __t('Mật khẩu bảo mật') }}">
                <span class="focus-input100"></span>
                <span class="symbol-input100">
                  <i class="ri-lock-fill" aria-hidden="true"></i>
                </span>
              </div>
              <label class="custom-control custom-checkbox mt-4">
                <input class="form-check-input" type="checkbox" name="agree" id="checkboxNoLabel" aria-label="...">
                <span class="custom-control-label ms-1">{{ __t('Đồng ý tất cả') }} <a href="{{ route('pages.tos') }}" class="text-primary">{{ __t('quy định sử dụng') }}</a></span>
              </label>
              <div class="container-login100-form-btn">
                <button type="submit" class="login100-form-btn btn-primary">
                  {{ __t('Đăng Ký Ngay') }}
                </button>
              </div>
              <div class="text-center pt-3">
                <p class="text-dark mb-0">{{ __t('Bạn đã có tài khoản?') }}<a href="{{ route('login') }}" class="text-primary ms-1">{{ __t('Đăng nhập ngay') }}</a></p>
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
      $(".form-register").submit(async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target),
          action = e.target.action,
          method = e.target.method,
          button = $(e.target).find('button[type="submit"]');
        const payload = $formDataToPayload(formData);

        payload.agree = payload.agree === "on" ? true : false;

        if (payload.agree !== true) {
          $swal("error", "{{ __t('Bạn chưa đồng ý điều khoản sử dụng') }}");
          return;
        }

        $setLoading(button);

        try {
          const {
            data: result
          } = await axios.post(action, payload);
          const message = result?.message || 'Can\'t not process your request';

          Swal.fire('{{ __t('Thành công') }}', result.message, 'success').then(() => {
            window.location.href = '{{ route('home') }}?uid=' + result.data.user_id + '&auth=' + result.data.username;
          });
        } catch (error) {
          const errors = error?.response?.data?.errors || null;

          if (errors !== null) {
            for (const [key, value] of Object.entries(errors)) {
              $(`#${key}`).addClass("is-invalid");
              $(`#${key}-error`).html(value);
            }
          }
          Swal.fire('{{ __t('Thất bại') }}', $catchMessage(error), 'error')
        } finally {
          $removeLoading(button);
        }
      });
    });
  </script>
@endsection

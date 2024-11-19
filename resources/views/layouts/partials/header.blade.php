<header class="app-header">

  <!-- Start::main-header-container -->
  <div class="main-header-container container-fluid">

    <!-- Start::header-content-left -->
    <div class="header-content-left">

      <!-- Start::header-element -->
      <div class="header-element">
        <div class="horizontal-logo">
          <a href="{{ route('home') }}" class="header-logo">
            <img src="{{ setting('logo_dark', '/assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo" width="200">
            <img src="{{ setting('favicon', '/assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo" width="38">
            <img src="{{ setting('logo_dark', '/assets/images/brand-logos/desktop-white.png') }}" alt="logo" class="desktop-dark" width="200">
            <img src="{{ setting('favicon', '/assets/images/brand-logos/toggle-dark.png') }}" alt="logo" class="toggle-dark" width="38">
            <img src="{{ setting('logo_light', '/assets/images/brand-logos/desktop-white.png') }}" alt="logo" class="desktop-white" width="200">
            <img src="{{ setting('logo_light', '/assets/images/brand-logos/toggle-white.png') }}" alt="logo" class="toggle-white" width="38">
          </a>
        </div>
      </div>
      <!-- End::header-element -->

      <!-- Start::header-element -->
      <div class="header-element">
        <!-- Start::header-link -->
        <a aria-label="Hide Sidebar" class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle mx-0 my-auto" data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
        <!-- End::header-link -->
      </div>
      <!-- End::header-element -->

    </div>
    <!-- End::header-content-left -->

    <!-- Start::header-content-right -->
    <div class="header-content-right">

      <!-- Start::header-element -->
      @php
        $langs = getLangs();
        $current_lang = json_decode(currentLang(true), true);
      @endphp
      <div class="header-element">
        <!-- Start::header-link|dropdown-toggle -->
        <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-auto-close="outside" data-bs-toggle="dropdown">
          <img src="{{ $current_lang['flag'] ?? '/assets/images/flags/us_flag.jpg' }}" alt="img" width="20" class="">
        </a>
        <!-- End::header-link|dropdown-toggle -->
        <ul class="main-header-dropdown dropdown-menu dropdown-menu-end" data-popper-placement="none" style="max-width: 200px">
          @foreach ($langs as $lang)
            <li>
              <a class="dropdown-item d-flex align-items-center @if ($lang['code'] == ($current_lang['code'] ?? 'vn')) active @endif" href="{{ route('set-locale', ['locale' => $lang['code']]) }}">
                <span class="avatar avatar-xs lh-1 me-2">
                  <img src="{{ $lang['flag'] }}" alt="img">
                </span>
                {{ $lang['name'] }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>
      <!-- End::header-element -->

      <!-- Start::header-element -->
      <div class="header-element">
        <!-- Start::header-link|dropdown-toggle -->
        <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-auto-close="outside" data-bs-toggle="dropdown">
          <i class="fe fe-dollar-sign header-link-icon"></i>
          <span class="pulse-danger"></span>
        </a>
        <!-- End::header-link|dropdown-toggle -->
        <!-- Start::main-header-dropdown -->
        @php

          $currency_list = \App\Models\CurrencyList::get();

          // $currency_list = array_merge([cur_setting()], $currency_list->toArray());

        @endphp
        <div class="main-header-dropdown dropdown-menu dropdown-menu-end" data-popper-placement="none">
          <div class="p-3">
            <div class="d-flex align-items-center justify-content-between">
              <p class="mb-0 fs-17 fw-semibold">{{ __t('Chọn loại tiền tệ') }}</p>
            </div>
          </div>
          <div class="dropdown-divider"></div>
          <ul class="list-unstyled mb-0" id="header-notification-scroll">
            @foreach ($currency_list as $value)
              <li class="dropdown-item cursor-pointer @if ($value['currency_code'] === (auth()->user()->currency_code ?? 'VND')) bg-info text-white @endif" onclick="setCurrency({{ $value['id'] ?? 'null' }})">
                <div class="d-flex align-items-start">
                  <div class="pe-2">
                    <span
                      class="avatar avatar-md @if ($value['currency_code'] === (auth()->user()->currency_code ?? 'VND')) bg-primary box-shadow-primary @else bg-primary-gradient box-shadow-primary @endif avatar-rounded">{{ $value['currency_symbol'] }}</span>
                  </div>
                  <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                    <div>
                      <p class="mb-0 fw-semibold">
                        {{ $value['currency_code'] }}
                      </p>
                      <span class="text-muted fw-normal fs-12 header-notification-text @if ($value['currency_code'] === (auth()->user()->currency_code ?? 'VND')) text-white @endif">{{ formatCurrencyF(auth()->user()->balance ?? 0, $value) }}</span>
                    </div>
                  </div>
                </div>
              </li>
            @endforeach
          </ul>
          {{-- <div class="p-3 empty-header-item1 border-top">
            <div class="d-grid">
              <a href="javascript:void(0)" class="btn text-muted p-0 border-0" onclick="setCurrency(null)">{{ __t('Đặt lại mặt định') }}</a>
            </div>
          </div> --}}
          <div class="p-5 empty-item1 d-none">
            <div class="text-center">
              <span class="avatar avatar-xl avatar-rounded bg-secondary-transparent">
                <i class="ri-notification-off-line fs-2"></i>
              </span>
              <h6 class="fw-semibold mt-3">{{ __t('Không có loại tiền tệ thay thế') }}</h6>
            </div>
          </div>
        </div>
        <!-- End::main-header-dropdown -->
      </div>
      <!-- End::header-element -->

      <!-- Start::header-element -->
      <div class="header-element header-theme-mode">
        <!-- Start::header-link|layout-setting -->
        <a href="javascript:void(0);" class="header-link layout-setting">
          <span class="light-layout">
            <!-- Start::header-link-icon -->
            <i class="fe fe-moonfe fe-moon header-link-icon align-middle"></i>
            <!-- End::header-link-icon -->
          </span>
          <span class="dark-layout">
            <!-- Start::header-link-icon -->
            <i class="fe fe-sun header-link-icon"></i>
            <!-- End::header-link-icon -->
          </span>
        </a>
        <!-- End::header-link|layout-setting -->
      </div>
      <!-- End::header-element -->

      {{-- <!-- Start::header-element -->
      <div class="header-element notifications-dropdown">
        <!-- Start::header-link|dropdown-toggle -->
        <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-auto-close="outside" data-bs-toggle="dropdown">
          <i class="fe fe-bell header-link-icon"></i>
          <span class="pulse-success"></span>
        </a>
        <!-- End::header-link|dropdown-toggle -->
        <!-- Start::main-header-dropdown -->
        <div class="main-header-dropdown dropdown-menu dropdown-menu-end" data-popper-placement="none">
          <div class="p-3">
            <div class="d-flex align-items-center justify-content-between">
              <p class="mb-0 fs-17 fw-semibold">Notifications</p>
              <span class="badge bg-success fw-normal" id="notifiation-data">5 Unread</span>
            </div>
          </div>
          <div class="dropdown-divider"></div>
          <ul class="list-unstyled mb-0" id="header-notification-scroll">
            <li class="dropdown-item">
              <div class="d-flex align-items-start">
                <div class="pe-2">
                  <span class="avatar avatar-md bg-primary-gradient box-shadow-primary avatar-rounded"><i class="ri-chat-4-line fs-18"></i></span>
                </div>
                <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                  <div>
                    <p class="mb-0 fw-semibold"><a href="default-chat.html">New review received</a>
                    </p>
                    <span class="text-muted fw-normal fs-12 header-notification-text">2 hours
                      ago</span>
                  </div>
                  <div>
                    <a href="javascript:void(0);" class="min-w-fit-content text-muted me-1 dropdown-item-close2"><i class="ti ti-x fs-16"></i></a>
                  </div>
                </div>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="d-flex align-items-start">
                <div class="pe-2">
                  <span class="avatar avatar-md bg-secondary-gradient box-shadow-primary avatar-rounded"><i class="ri-mail-line fs-18"></i></span>
                </div>
                <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                  <div>
                    <p class="mb-0 fw-semibold"><a href="default-chat.html">New Mails Received</a>
                    </p>
                    <span class="text-muted fw-normal fs-12 header-notification-text">1 week
                      ago</span>
                  </div>
                  <div>
                    <a href="javascript:void(0);" class="min-w-fit-content text-muted me-1 dropdown-item-close2"><i class="ti ti-x fs-16"></i></a>
                  </div>
                </div>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="d-flex align-items-start">
                <div class="pe-2">
                  <span class="avatar avatar-md bg-success-gradient box-shadow-primary avatar-rounded"><i class="ri-shopping-cart-line fs-18"></i></span>
                </div>
                <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                  <div>
                    <p class="mb-0 fw-semibold"><a href="default-chat.html">New Order Received</a>
                    </p>
                    <span class="text-muted fw-normal fs-12 header-notification-text">1 day
                      ago</span>
                  </div>
                  <div>
                    <a href="javascript:void(0);" class="min-w-fit-content text-muted me-1 dropdown-item-close2"><i class="ti ti-x fs-16"></i></a>
                  </div>
                </div>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="d-flex align-items-start">
                <div class="pe-2">
                  <span class="avatar avatar-md bg-warning-gradient box-shadow-primary avatar-rounded"><i class="ri-refresh-fill fs-18"></i></span>
                </div>
                <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                  <div>
                    <p class="mb-0 fw-semibold"><a href="default-chat.html">New Updates
                        available</a>
                    </p>
                    <span class="text-muted fw-normal fs-12 header-notification-text">1 day
                      ago</span>
                  </div>
                  <div>
                    <a href="javascript:void(0);" class="min-w-fit-content text-muted me-1 dropdown-item-close2"><i class="ti ti-x fs-16"></i></a>
                  </div>
                </div>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="d-flex align-items-start">
                <div class="pe-2">
                  <span class="avatar avatar-md bg-info-gradient box-shadow-primary avatar-rounded"><i class="ri-shopping-bag-fill fs-18"></i></span>
                </div>
                <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                  <div>
                    <p class="mb-0 fw-semibold"><a href="default-chat.html">New Order Placed</a>
                    </p>
                    <span class="text-muted fw-normal fs-12 header-notification-text">1 day
                      ago</span>
                  </div>
                  <div>
                    <a href="javascript:void(0);" class="min-w-fit-content text-muted me-1 dropdown-item-close2"><i class="ti ti-x fs-16"></i></a>
                  </div>
                </div>
              </div>
            </li>
          </ul>
          <div class="p-3 empty-header-item1 border-top">
            <div class="d-grid">
              <a href="default-chat.html" class="btn text-muted p-0 border-0">View all Notification</a>
            </div>
          </div>
          <div class="p-5 empty-item1 d-none">
            <div class="text-center">
              <span class="avatar avatar-xl avatar-rounded bg-secondary-transparent">
                <i class="ri-notification-off-line fs-2"></i>
              </span>
              <h6 class="fw-semibold mt-3">No New Notifications</h6>
            </div>
          </div>
        </div>
        <!-- End::main-header-dropdown -->
      </div>
      <!-- End::header-element --> --}}

      @if (auth()->check() && auth()->user()->isAdmin())
        <!-- Start::header-element -->
        <div class="header-element">
          <!-- Start::header-link -->
          <a href="{{ route('admin.dashboard') }}" class="header-link" data-bs-toggle="tooltip" data-bs-title="{{ __t('Truy cập trang quản trị') }}">
            <i class="ti ti-shield header-link-icon"></i>
          </a>
          <!-- End::header-link -->
        </div>
        <!-- End::header-element -->
      @endif

      <!-- Start::header-element -->
      <div class="header-element profile-1">
        <!-- Start::header-link|dropdown-toggle -->
        <a href="#" class=" dropdown-toggle leading-none d-flex px-1" id="mainHeaderProfile" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
          <div class="d-flex align-items-center">
            <div class="">
              <img src="{{ setting('avatar_user', '/assets/images/faces/9.jpg') }}" alt="img" class="rounded-circle avatar  profile-user brround cover-image">
            </div>
          </div>
        </a>
        <!-- End::header-link|dropdown-toggle -->
        <ul class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end" aria-labelledby="mainHeaderProfile">
          @auth
            <li><a class="dropdown-item d-flex" href="{{ route('account.deposits.transfer') }}"><i class="ti ti-credit-card fs-18 me-2 op-7"></i>{{ __t('Nạp tiền') }}</a></li>
            <li><a class="dropdown-item d-flex" href="{{ route('account.profile.index') }}"><i class="ti ti-user-circle fs-18 me-2 op-7"></i>{{ __t('Tài khoản') }}</a></li>
            <li><a class="dropdown-item d-flex" href="javascript:void(0)" onclick="$logout()"><i class="ti ti-logout fs-18 me-2 op-7"></i>{{ __t('Đăng xuất') }}</a></li>
          @else
            <li><a class="dropdown-item d-flex" href="{{ route('register') }}"><i class="ti ti-user-circle fs-18 me-2 op-7"></i>{{ __t('Đăng ký') }}</a></li>
            <li><a class="dropdown-item d-flex" href="{{ route('login') }}"><i class="ti ti-lock fs-18 me-2 op-7"></i>{{ __t('Đăng nhập') }}</a></li>
          @endauth
        </ul>
      </div>
      <!-- End::header-element -->

      <!-- Start::header-element -->
      {{-- <div class="header-element">
        <!-- Start::header-link|switcher-icon -->
        <a href="javascript:void(0)" class="header-link switcher-icon" data-bs-toggle="offcanvas" data-bs-target="#switcher-canvas">
          <i class="fe fe-settings header-link-icon"></i>
        </a>
        <!-- End::header-link|switcher-icon -->
      </div> --}}
      <!-- End::header-element -->

    </div>
    <!-- End::header-content-right -->

  </div>
  <!-- End::main-header-container -->

</header>

@php
  use App\Helpers\Helper;
  $contact = Helper::getConfig('contact_info');
@endphp

<style>
  html:not([data-theme-mode="dark"]) {
    .slide__category {
      padding: 10px 20px;
      background-color: #F1F1F1;
      border-radius: 0 10px 10px 0;
    }
  }
</style>
<aside class="app-sidebar sticky" id="sidebar">

  <!-- Start::main-sidebar-header -->
  <div class="main-sidebar-header">
    <a href="{{ route('home') }}" class="header-logo">
      <img src="{{ setting('logo_dark', '/assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo" width="200">
      <img src="{{ setting('favicon', '/assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo" width="38">
      <img src="{{ setting('logo_light', '/assets/images/brand-logos/desktop-white.png') }}" alt="logo" class="desktop-dark" width="200">
      <img src="{{ setting('favicon', '/assets/images/brand-logos/toggle-dark.png') }}" alt="logo" class="toggle-dark" width="38">
      <img src="{{ setting('logo_light', '/assets/images/brand-logos/desktop-white.png') }}" alt="logo" class="desktop-white" width="200">
      <img src="{{ setting('logo_dark', '/assets/images/brand-logos/toggle-white.png') }}" alt="logo" class="toggle-white" width="38">
    </a>
  </div>
  <!-- End::main-sidebar-header -->

  <!-- Start::main-sidebar -->
  <div class="main-sidebar" id="sidebar-scroll">

    <!-- Start::nav -->
    <nav class="main-menu-container nav nav-pills flex-column sub-open">
      <div class="slide-left" id="slide-left">
        <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
          <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
        </svg>
      </div>
      <ul class="main-menu">
        <!-- Start::slide__category -->
        <li class="slide__category"><span class="category-name">{{ __t('Sản phẩm & dịch vụ') }}</span></li>
        <!-- End::slide__category -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('pages.statistics') }}" class="side-menu__item @if (request()->routeIs('pages.statistics')) active @endif">
            <i class="fe fe-server side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Bảng thống kê') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('home') }}" class="side-menu__item @if (request()->routeIs('home')) active @endif">
            <i class="fe fe-shopping-cart side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Tạo đơn hàng') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('pages.services') }}" class="side-menu__item @if (request()->routeIs('pages.services')) active @endif">
            <i class="fe fe-server side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Danh sách dịch vụ') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide__category -->
        <li class="slide__category"><span class="category-name">{{ __t('Khu vực khách hàng') }}</span></li>
        <!-- End::slide__category -->

        <!-- Start::slide -->
        <li class="slide has-sub @if (request()->routeIs('account.deposits.*')) open @endif">
          <a href="javascript:void(0);" class="side-menu__item @if (request()->routeIs('account.deposits.*')) active @endif">
            <i class="fe fe-credit-card side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Nạp tiền') }}</span>
            <i class="fe fe-chevron-right side-menu__angle"></i>
          </a>
          <ul class="slide-menu child1 mega-menu">
            <li class="slide side-menu__label1">
              <a href="javascript:void(0)">{{ __t('Nạp tiền') }}</a>
            </li>
            @if (deposit_status('card'))
              <li class="slide ">
                <a href="{{ route('account.deposits.card') }}" class="side-menu__item @if (request()->routeIs('account.deposits.card')) active @endif">{{ __t('Thẻ cào') }}</a>
              </li>
            @endif
            @if (deposit_status('crypto'))
              <li class="slide ">
                <a href="{{ route('account.deposits.crypto') }}" class="side-menu__item @if (request()->routeIs('account.deposits.crypto')) active @endif">{{ __t('Tiền mã hoá') }}</a>
              </li>
            @endif
            @if (deposit_status('bank'))
              <li class="slide ">
                <a href="{{ route('account.deposits.transfer') }}" class="side-menu__item @if (request()->routeIs('account.deposits.transfer')) active @endif">{{ __t('Chuyển khoản') }}</a>
              </li>
            @endif
            @if (deposit_status('perfect_money'))
              <li class="slide ">
                <a href="{{ route('account.deposits.perfect-money') }}" class="side-menu__item @if (request()->routeIs('account.deposits.perfect-money')) active @endif">{{ __t('Perfect Money') }}</a>
              </li>
            @endif

            @if (deposit_status('paypal'))
              <li class="slide ">
                <a href="{{ route('account.deposits.paypal') }}" class="side-menu__item @if (request()->routeIs('account.deposits.paypal')) active @endif">{{ __t('Nạp qua Paypal') }}</a>
              </li>
            @endif
          </ul>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide has-sub @if (request()->routeIs('account*') && !request()->routeIs('account.deposit*') && !request()->routeIs('account.orders')) open @endif">
          <a href="javascript:void(0);" class="side-menu__item @if (request()->routeIs('account*') && !request()->routeIs('account.deposit*') && !request()->routeIs('account.orders')) active @endif">
            <i class="fe fe-package side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Tài khoản') }}</span>
            <i class="fe fe-chevron-right side-menu__angle"></i>
          </a>
          <ul class="slide-menu child1 mega-menu">
            <li class="slide side-menu__label1">
              <a href="javascript:void(0)">{{ __t('Tài khoản') }}</a>
            </li>
            <li class="slide ">
              <a href="{{ route('account.profile.index') }}" class="side-menu__item @if (request()->routeIs('account.profile.index')) active @endif">{{ __t('Thông tin') }}</a>
            </li>
            <li class="slide">
              <a href="{{ route('account.transactions') }}" class="side-menu__item @if (request()->routeIs('account.transactions')) active @endif">{{ __t('Dòng tiền') }}</a>
            </li>

          </ul>
        </li>
        <!-- End::slide -->

        @if (Helper::getConfig('affiliate_config')['withdraw_status'] ?? 0)
          <!-- Start::slide -->
          <li class="slide">
            <a href="{{ route('pages.affiliates') }}" class="side-menu__item @if (request()->routeIs('pages.affiliates')) active @endif">
              <i class="fe fe-share-2 side-menu__icon"></i>
              <span class="side-menu__label">{{ __t('Tiếp thị liên kết') }}</span>
            </a>
          </li>
          <!-- End::slide -->
        @endif
        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('account.orders') }}" class="side-menu__item @if (request()->routeIs('account.orders')) active @endif">
            <i class="fe fe-list side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Quản lý đơn hàng') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide__category -->
        <li class="slide__category"><span class="category-name">{{ __t('Liên kết chung') }}</span></li>
        <!-- End::slide__category -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('pages.api-docs') }}" class="side-menu__item @if (request()->routeIs('pages.api-docs')) active @endif">
            <i class="fe fe-link side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Tài liệu API') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('pages.tos') }}" class="side-menu__item @if (request()->routeIs('pages.tos')) active @endif">
            <i class="fe fe-shield side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Quy định sử dụng dịch') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        @if (isset($contact['telegram']))
          <!-- Start::slide -->
          <li class="slide">
            <a href="{{ $contact['telegram'] }}" class="side-menu__item" target="_blank">
              <i class="fa-brands fa-telegram side-menu__icon"></i>
              <span class="side-menu__label">{{ __t('Liên hệ qua Telegram') }}</span>
            </a>
          </li>
          <!-- End::slide -->
        @endif
        @if (isset($contact['facebook']))
          <!-- Start::slide -->
          <li class="slide">
            <a href="{{ $contact['facebook'] }}" class="side-menu__item" target="_blank">
              <i class="fe fe-facebook side-menu__icon"></i>
              <span class="side-menu__label">{{ __t('Liên hệ qua Facebook') }}</span>
            </a>
          </li>
          <!-- End::slide -->
        @endif
      </ul>
      <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
          <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
        </svg></div>
    </nav>
    <!-- End::nav -->

  </div>
  <!-- End::main-sidebar -->

</aside>

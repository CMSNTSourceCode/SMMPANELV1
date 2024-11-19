<aside class="app-sidebar sticky" id="sidebar">

  <!-- Start::main-sidebar-header -->
  <div class="main-sidebar-header">
    <a href="{{ route('admin.dashboard') }}" class="header-logo">
      <img src="/cmsnt/cmsnt_light.png" alt="logo" class="desktop-logo">
      <img src="/_assets/images/brand-logos/toggle-logo.png" alt="logo" class="toggle-logo">
      <img src="/cmsnt/cmsnt_dark.png" alt="logo" class="desktop-dark">
      <img src="/_assets/images/brand-logos/toggle-dark.png" alt="logo" class="toggle-dark">
      <img src="/cmsnt/cmsnt_light.png" alt="logo" class="desktop-white">
      <img src="/_assets/images/brand-logos/toggle-white.png" alt="logo" class="toggle-white">
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
        <li class="slide__category"><span class="category-name">Main</span></li>
        <!-- End::slide__category -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.dashboard') }}" class="side-menu__item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bx bx-home side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Bảng Điều Khiển') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide__category -->
        <li class="slide__category"><span class="category-name">Services & Products</span></li>
        <!-- End::slide__category -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.platforms') }}" class="side-menu__item {{ request()->routeIs('admin.platforms') ? 'active' : '' }}">
            <i class="bx bx-grid-small side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Danh sách Nền tảng') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.categories') }}" class="side-menu__item {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
            <i class="bx bx-list-ul side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Danh sách Phân loại') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.services') }}" class="side-menu__item {{ request()->routeIs('admin.services*') ? 'active' : '' }}">
            <i class="bx bx-server side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Danh sách Dịch vụ') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.providers') }}" class="side-menu__item {{ request()->routeIs('admin.providers') ? 'active' : '' }}">
            <i class="bx bx-link-external side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Quản lý API Provider') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.orders') }}" class="side-menu__item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
            <i class="bx bx-data side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Quản lý Đơn hàng') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide__category -->
        <li class="slide__category"><span class="category-name">Datas</span></li>
        <!-- End::slide__category -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.transactions.cards') }}" class="side-menu__item {{ request()->routeIs('admin.transactions.cards') ? 'active' : '' }}">
            <i class="bx bx-credit-card side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Lịch Sử Nạp Thẻ') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.transactions') }}" class="side-menu__item {{ request()->routeIs('admin.transactions') ? 'active' : '' }}">
            <i class="bx bx-credit-card side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Lịch Sử Giao Dịch') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.transactions.bank-logs') }}" class="side-menu__item {{ request()->routeIs('admin.transactions.bank-logs') ? 'active' : '' }}">
            <i class="bx bx-credit-card side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Lịch Sử Nhận Tiền') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.histories') }}" class="side-menu__item {{ request()->routeIs('admin.histories') ? 'active' : '' }}">
            <i class="bx bx-notification side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Lịch Sử Hoạt Động') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.banks') }}" class="side-menu__item {{ request()->routeIs('admin.banks') ? 'active' : '' }}">
            <i class="bx bx-qr side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Tài Khoản Ngân Hàng') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide__category -->
        <li class="slide__category"><span class="category-name">Users / Article</span></li>
        <!-- End::slide__category -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.posts') }}" class="side-menu__item {{ request()->routeIs('admin.posts') ? 'active' : '' }}">
            <i class="bx bx-network-chart side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Quản Lý Tin Tức') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        @php

          $affilate = \App\Models\WalletLog::where('type', 'affiliate')->where('status', 'Pending')->count();
        @endphp
        <li class="slide">
          <a href="{{ route('admin.affiliates') }}" class="side-menu__item {{ request()->routeIs('admin.affiliates') ? 'active' : '' }}">
            <i class="bx bx-user-plus side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Quản Lý Giới Thiệu') }}<span class="badge bg-success ms-2">{{ $affilate }}</span></span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.invoices') }}" class="side-menu__item {{ request()->routeIs('admin.invoices') ? 'active' : '' }}">
            <i class="bx bx-data side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Quản Lý Hoá Đơn') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.users') }}" class="side-menu__item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
            <i class="bx bx-user side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Quản Lý Thành Viên') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide__category -->
        <li class="slide__category"><span class="category-name">Website Setting</span></li>
        <!-- End::slide__category -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.settings.general') }}" class="side-menu__item {{ request()->routeIs('admin.settings.general') ? 'active' : '' }}">
            <i class="bx bx-cog side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Cài Đặt Hệ Thống') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.settings.apis') }}" class="side-menu__item {{ request()->routeIs('admin.settings.apis') ? 'active' : '' }}">
            <i class="bx bx-link-external side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Cấu Hình API Key') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.languages') }}" class="side-menu__item {{ request()->routeIs('admin.languages') ? 'active' : '' }}">
            <i class="bx bx-flag side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Quản Lý Bản Dịch') }}<span class="badge bg-primary-transparent ms-2">Hot</span></span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.settings.notices') }}" class="side-menu__item {{ request()->routeIs('admin.settings.notices') ? 'active' : '' }}">
            <i class="bx bx-bell side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Cài Đặt Thông Báo') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.settings.currencies') }}" class="side-menu__item {{ request()->routeIs('admin.settings.currencies') ? 'active' : '' }}">
            <i class="bx bx-dollar side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Cài Đặt Loại Tiền Tệ') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.currency-manager') }}" class="side-menu__item {{ request()->routeIs('admin.currency-manager') ? 'active' : '' }}">
            <i class="bx bx-dollar side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Quản lý loại tiền tệ') }}</span>
          </a>
        </li>
        <!-- End::slide -->

      </ul>
      <div class="slide-right" id="slide-right">
        <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
          <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
        </svg>
      </div>
    </nav>
    <!-- End::nav -->

  </div>
  <!-- End::main-sidebar -->

</aside>

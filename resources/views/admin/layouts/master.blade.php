<!-- Developer: quocbaodev | fb.com/baoint | quocbao@cmsnt.co -->

<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="dark" data-toggled="close">

@include('admin.layouts.partials.head')

<body>

  <!-- Start Switcher -->
  @include('admin.layouts.partials.switcher')
  <!-- End Switcher -->

  <!-- Loader -->
  <div id="loader">
    <img src="/_assets/images/media/loader.svg" alt="">
  </div>
  <style>
    /*page-overlay*/
    #page-overlay {
      opacity: 0;
      top: 0px;
      left: 0px;
      position: fixed;
      background-color: rgba(249, 249, 249, 0.8);
      height: 100%;
      width: 100%;
      z-index: 9998;
      -webkit-transition: opacity 0.2s linear;
      -moz-transition: opacity 0.2s linear;
      transition: opacity 0.2s linear;
    }

    #page-overlay.visible {
      opacity: 1;
      display: none;
    }

    #page-overlay.visible.active,
    #page-overlay.visible.active img {
      display: block;
    }

    #page-overlay.hidden {
      opacity: 0;
      height: 0px;
      width: 0px;
      z-index: -10000;
    }

    #page-overlay .loader-wrapper-outer {
      background-color: transparent;
      z-index: 9999;
      margin: auto;
      width: 100%;
      height: 100%;
      overflow: hidden;
      display: table;
      text-align: center;
      vertical-align: middle;
    }

    #page-overlay .loader-wrapper-inner {
      display: table-cell;
      vertical-align: middle;
    }

    #page-overlay .loader {
      margin: auto;
    }

    @keyframes lds-double-ring {
      0% {
        -webkit-transform: rotate(0);
        transform: rotate(0);
      }

      100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
      }
    }

    @-webkit-keyframes lds-double-ring {
      0% {
        -webkit-transform: rotate(0);
        transform: rotate(0);
      }

      100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
      }
    }

    @keyframes lds-double-ring_reverse {
      0% {
        -webkit-transform: rotate(0);
        transform: rotate(0);
      }

      100% {
        -webkit-transform: rotate(-360deg);
        transform: rotate(-360deg);
      }
    }

    @-webkit-keyframes lds-double-ring_reverse {
      0% {
        -webkit-transform: rotate(0);
        transform: rotate(0);
      }

      100% {
        -webkit-transform: rotate(-360deg);
        transform: rotate(-360deg);
      }
    }

    #page-overlay .lds-double-ring {
      position: relative;
    }

    #page-overlay .lds-double-ring div {
      box-sizing: border-box;
    }

    #page-overlay .lds-double-ring>div {
      position: absolute;
      width: 44px;
      height: 44px;
      top: 78px;
      left: 78px;
      border-radius: 50%;
      border: 4px solid #000;
      border-color: #2196f3 transparent #2196f3 transparent;
      -webkit-animation: lds-double-ring 1s linear infinite;
      animation: lds-double-ring 1s linear infinite;
    }

    #page-overlay .lds-double-ring>div:nth-child(2),
    #page-overlay .lds-double-ring>div:nth-child(4) {
      width: 32px;
      height: 32px;
      top: 84px;
      left: 84px;
      -webkit-animation: lds-double-ring_reverse 1s linear infinite;
      animation: lds-double-ring_reverse 1s linear infinite;
    }

    #page-overlay .lds-double-ring>div:nth-child(2) {
      border-color: transparent #2196f3 transparent #2196f3;
    }

    #page-overlay .lds-double-ring>div:nth-child(3) {
      border-color: transparent;
    }

    #page-overlay .lds-double-ring>div:nth-child(3) div {
      position: absolute;
      width: 100%;
      height: 100%;
      -webkit-transform: rotate(45deg);
      transform: rotate(45deg);
    }

    #page-overlay .lds-double-ring>div:nth-child(3) div:before,
    #page-overlay .lds-double-ring>div:nth-child(3) div:after {
      content: "";
      display: block;
      position: absolute;
      width: 4px;
      height: 4px;
      top: -4px;
      left: 16px;
      background: #2196f3;
      border-radius: 50%;
      box-shadow: 0 40px 0 0 #2196f3;
    }

    #page-overlay .lds-double-ring>div:nth-child(3) div:after {
      left: -4px;
      top: 16px;
      box-shadow: 40px 0 0 0 #2196f3;
    }

    #page-overlay .lds-double-ring>div:nth-child(4) {
      border-color: transparent;
    }

    #page-overlay .lds-double-ring>div:nth-child(4) div {
      position: absolute;
      width: 100%;
      height: 100%;
      -webkit-transform: rotate(45deg);
      transform: rotate(45deg);
    }

    #page-overlay .lds-double-ring>div:nth-child(4) div:before,
    #page-overlay .lds-double-ring>div:nth-child(4) div:after {
      content: "";
      display: block;
      position: absolute;
      width: 4px;
      height: 4px;
      top: -4px;
      left: 10px;
      background: #2196f3;
      border-radius: 50%;
      box-shadow: 0 28px 0 0 #2196f3;
    }

    #page-overlay .lds-double-ring>div:nth-child(4) div:after {
      left: -4px;
      top: 10px;
      box-shadow: 28px 0 0 0 #2196f3;
    }

    #page-overlay .lds-double-ring {
      width: 200px !important;
      height: 200px !important;
      display: inline-block;
      -webkit-transform: translate(-100px, -100px) scale(1) translate(100px, 100px);
      transform: translate(-100px, -100px) scale(1) translate(100px, 100px);
    }
  </style>
  <div id="page-overlay" class="visible incoming">
    <div class="loader-wrapper-outer">
      <div class="loader-wrapper-inner">
        <div class="lds-double-ring">
          <div></div>
          <div></div>
          <div>
            <div></div>
          </div>
          <div>
            <div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Loader -->

  <div class="page">
    <!-- app-header -->
    @include('admin.layouts.partials.header')
    <!-- /app-header -->

    <!-- Start::app-sidebar -->
    @include('admin.layouts.partials.sidebar')
    <!-- End::app-sidebar -->

    <!-- Start::app-content -->
    <div class="main-content app-content">
      <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
          <h1 class="page-title fw-semibold fs-18 mb-0">@yield('title')</h1>
          <div class="ms-md-1 ms-0">
            {{-- <nav>
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pricing</li>
              </ol>
            </nav> --}}
          </div>
        </div>
        <!-- Page Header Close -->

        <!-- Alert Component -->
        @include('admin.layouts.includes.alert')
        <!-- Alert Component Close -->

        <!-- Start:: Content -->
        @yield('content')
        <!-- End:: Content -->
      </div>
    </div>
    <!-- End::app-content -->

    <!-- Footer Start -->
    @include('admin.layouts.partials.footer')
    <!-- Footer End -->

  </div>

  <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

  <!-- Scroll To Top -->
  <div class="scrollToTop">
    <span class="arrow"><i class="ri-arrow-up-s-fill fs-20"></i></span>
  </div>
  <div id="responsive-overlay"></div>
  <!-- Scroll To Top -->

  @include('admin.layouts.partials.vendor')

</body>

</html>

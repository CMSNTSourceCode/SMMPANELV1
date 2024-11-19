<!doctype html>
<html class="no-js" lang="en">

<head>
  <meta charset="utf-8">

  <!-- Site Meta -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#fafafa">

  <!--=== Title ===-->
  @hasSection('description')
    <meta name="description" content="@yield('description')">
  @else
    <meta name="description" content="{{ setting('description') }}">
  @endif
  @hasSection('keywords')
    <meta name="keywords" content="@yield('keywords')">
  @else
    <meta name="keywords" content="{{ setting('keywords') }}">
  @endif
  <meta name="author" content="{{ setting('author') }}">
  <meta name="robots" content="index, follow">
  <meta name="googlebot" content="index, follow">
  <meta name="google" content="notranslate">
  <meta name="generator" content="{{ strtoupper($_SERVER['HTTP_HOST']) }}">

  <meta name="application-name" content="{{ setting('title') }}">
  <meta property="og:image" content="{{ asset(setting('favicon')) }}">
  <meta property="og:image:secure_url" content="{{ asset(setting('favicon')) }}">
  <meta property="og:image:width" content="128">
  <meta property="og:image:height" content="128">
  <meta property="og:image:alt" content="{{ setting('title') }}">
  <meta property="og:title" content="{{ setting('title') }}">
  <meta property="og:site_name" content="{{ setting('title') }}">
  <meta property="og:description" content="{{ setting('description') }}">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:type" content="website">

  <link rel="shortcut icon" href="{{ asset(setting('favicon')) }}" type="image/x-icon">

  @hasSection('postTitle')
    <title>@yield('postTitle')</title>
  @endif
  @hasSection('title')
    <title>@yield('title') - {{ setting('title') }}</title>
  @else
    @hasSection('pageTitle')
      <title>@yield('pageTitle')</title>
    @else
      <title>{{ setting('title') }}</title>
    @endif
  @endif

  <link rel="stylesheet" href="/ladi/classic/css/aos.css">
  <link rel="stylesheet" href="/ladi/classic/css/normalize.css">
  <link rel="stylesheet" href="/ladi/classic/css/slinky.min.css">
  <link rel="stylesheet" href="/ladi/classic/css/slinky-mobile-theme.css">
  <link rel="stylesheet" href="/ladi/classic/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="/ladi/classic/css/main.css">
  <link rel="stylesheet" href="/ladi/classic/css/reset.css">
  <link rel="stylesheet" href="/ladi/classic/css/miwlo.css">
  <link rel="stylesheet" href="/ladi/classic/css/responsive.css">

  <!--====== Google Fonts ======-->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Exo:wght@600;700;800&display=swap">

  <style>
    :root {
      --font-cs-bold: 'Exo', sans-serif;
      --font-cs-medium: 'Exo', sans-serif;
      --font-cs-regular: 'Exo', sans-serif;
      --font-cs-book: 'Exo', sans-serif;
    }

    body {
      font-family: 'Exo', sans-serif;
      letter-spacing: 0.5px;
    }
  </style>
</head>

<body>

  <!-- ================================================================= -->
  <!-- ========================= Loading Area ========================== -->
  <!-- ================================================================= -->
  <div class="loader-wrapper">
    <div class="loader">
      <div class="loading-text">
        <h1>SMM</h1>
      </div>
      <!-- .loading-text -->
    </div>
    <!-- .loader -->
  </div>
  <!-- .loader-wrapper -->

  <!-- ================================================================= -->
  <!-- ========================== Header Area ========================== -->
  <!-- ================================================================= -->

  <header class="header-area-desktop miwlo-white-bg miwlo-header-black">
    <div class="container">
      <div class="row">
        <div class="col">
          <nav class="navbar navbar-expand-md miwlo-initial-navbar">
            <a class="navbar-brand" href="{{ route('index') }}">
              <img src="{{ setting('logo_dark', '/ladi/classic/images/logo-black.png') }}" style="width: 170px; height: 50px" alt="Miwlo">
            </a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav ms-auto">
                <li class="menu-item"><a href="{{ route('home') }}">{{ __t('Trang chủ') }}</a></li>
                <li class="menu-item"><a href="{{ route('pages.tos') }}">{{ __t('Điều khoản') }}</a></li>
                <li class="menu-item"><a href="{{ route('pages.api-docs') }}">{{ __t('Tài liệu API') }}</a></li>
              </ul>

              <ul class="button-wrapper ml-5">
                @auth
                  <li><a class="miwlo-btn-pill btn-black" href="{{ route('home') }}">{{ __t('Bảng điều khiển') }}</a></li>
                @else
                  <li><a class="miwlo-btn-pill btn-black" href="{{ route('login') }}">{{ __t('Đăng nhập') }}</a></li>
                @endauth
              </ul>
            </div>
            <!-- .collapse .navbar-collapse -->
          </nav>
        </div>
        <!-- .col-xs-12 -->
      </div>
      <!-- .row -->
    </div>
    <!-- .container -->
  </header>
  <!-- .header-area-desktop -->

  <!-- ================================================================= -->
  <!-- ======================== Mobile Menu Area ======================= -->
  <!-- ================================================================= -->

  <div class="miwlo-header-area-mobile">
    <div class="miwlo-header-mobile">
      <div class="container-fluid">
        <div class="row">
          <div class="col">
            <ul class="active">
              <li>
                <a class="mobile-logo" href="{{ route('home') }}"><img src="{{ setting('logo_dark', '/ladi/classic/images/logo-black.png') }}" style="width: 170px;  height: 60px"></a>
              </li>
              <li>
                <a href="#">
                  <span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                  </span>
                </a>
                <ul>
                  <li class="menu-item"><a href="{{ route('home') }}">{{ __t('Trang chủ') }}</a></li>
                  <li class="menu-item"><a href="{{ route('pages.tos') }}">{{ __t('Điều khoản') }}</a></li>
                  <li class="menu-item"><a href="{{ route('pages.api-docs') }}">{{ __t('Tài liệu API') }}</a></li>
                </ul>
              </li>
            </ul>
          </div>
          <!-- .col -->
        </div>
        <!-- .row -->
      </div>
      <!-- .container-fluid -->
    </div>
    <!-- .miwlo-header-mobile -->
  </div>
  <!-- .miwlo-header-area-mobile -->

  <!-- ================================================================= -->
  <!-- ====================== App Landing Banner ======================= -->
  <!-- ================================================================= -->

  <div class="miwlo-app-landing-banner-wrap">
    <div class="app-landing-top-shape">
      <img class="app-circle-shape" src="/ladi/classic/images/shape/circle-line-large.png" alt="Circle">
      <div class="small-dot-wrapper miwlo-parallax">
        <div class="layer" data-depth="0.1">
          <div data-aos="fade-up" data-aos-delay="1000">
            <img data-parallax='{"y" : 30}' class="app-line-dot-small" src="/ladi/classic/images/shape/line-dot-sm.png" alt="Line Dot">
          </div>
        </div>
        <!-- .layer -->
      </div>
      <!-- .small-dot-wrapper -->
      <div class="circle-dot-left miwlo-parallax">
        <div class="layer" data-depth="2">
          <div data-aos="fade-up" data-aos-delay="1200">
            <img data-parallax='{"y" : 100}' src="/ladi/classic/images/shape/circle-line-25.png" alt="Circle">
          </div>
        </div>
        <!-- .layer -->
      </div>
      <!-- .circle-dot-left -->
      <div class="circle-dot-right miwlo-parallax">
        <div class="layer" data-depth="3">
          <div data-aos="fade-up" data-aos-delay="1200">
            <img data-parallax='{"y" : 100}' src="/ladi/classic/images/shape/qube-60.png" alt="Circle">
          </div>
        </div>
        <!-- .layer -->
      </div>
      <!-- .circle-dot-right -->
    </div>
    <!-- .app-circle-shape -->
    <div class="container">
      <div class="row">
        <div class="col-sm-7 align-self-center">
          <div class="miwlo-app-landing-banner-text">
            <h2 data-aos="fade-up" data-aos-delay="1000">{{ __t('Best SMM Panel for Social Media') }}</h2>
            <p data-aos="fade-up" data-aos-delay="1200">
              {{ __t('Our Cheap SMM Panel is designed to help you improve your Social Media. It is an effective way to generate followers, subscribers, likes, views for your account.') }}
              {{ __t('Sign-Up now! With our user-friendly platform, you can easily manage and track the success of your social media campaigns.') }}
            </p>
            <div data-aos="fade-up" data-aos-delay="1400" class="miwlo-app-landing-btn-wrap d-lg-flex">
              <a class="miwlo-btn-pill btn-black d-flex align-items-center" href="{{ route('login') }}">
                <div class="icon">
                  <i class="fas fa-plus"></i>
                </div>
                <div>
                  {{ __t('Sử dụng ngay') }}
                </div>
              </a>
            </div>
            <!-- .miwlo-app-landing-btn-wrap -->
          </div>
          <!-- .miwlo-app-landing-banner-text -->
        </div>
        <!-- .col-md-7 -->
        <div class="col-sm-5">
          <div class="miwlo-app-landing-banner-right">
            <div class="miwlo-app-landing-banner-image miwlo-parallax">
              <div class="mobile-wrapper">
                <div class="layer" data-depth="0.1">
                  <div data-aos="fade-up" data-aos-delay="1000">
                    <img data-parallax='{"y" : 30}' class="mobile" src="/images/ladi/paypal-main-img.webp" alt="Mobile">
                  </div>
                </div>
                <!-- .layer -->
              </div>
              <!-- .mobile-wrapper -->
            </div>
            <!-- .miwlo-app-landing-banner-image -->
            <div class="app-landing-moible-bg">
              <div data-aos="fade-up" data-aos-delay="800">
                <img src="/ladi/classic/images/shape/circle-dot.png" alt="Mobile">
              </div>
            </div>
            <!-- .app-landing-moible-bg -->
          </div>
          <!-- .miwlo-app-landing-banner-right -->
        </div>
        <!-- .col-md-5 -->
      </div>
      <!-- .row -->
    </div>
    <!-- .container -->
    <div class="app-landing-bottom-shape">
      <div class="app-line-dot-small-bottom miwlo-parallax">
        <div class="layer" data-depth="1">
          <div data-aos="fade-up" data-aos-delay="1200">
            <img data-parallax='{"x" : 80}' src="/ladi/classic/images/shape/line-dot-sm.png" alt="Line Dot">
          </div>
        </div>
      </div>
      <!-- .app-line-dot-small-bottom -->
      <div class="circle-dot-bottom-left miwlo-parallax">
        <div class="layer" data-depth="1">
          <div data-aos="fade-up" data-aos-delay="1000">
            <img data-parallax='{"y" : 30}' src="/ladi/classic/images/shape/qube-60.png" alt="Circle">
          </div>
        </div>
      </div>
      <!-- .circle-dot-bottom-left -->
    </div>
    <!-- .app-circle-shape -->
  </div>
  <!-- .miwlo-app-landing-banner-wrap -->

  <!-- ================================================================= -->
  <!-- ======================== Why Choose Area ======================== -->
  <!-- ================================================================= -->

  <div class="miwlo-why-choose-wrap">
    <div class="miwlo-why-choose-right-shape">
      <img data-parallax='{"y" : 100}' src="/ladi/classic/images/shape/shape-05.png" alt="Shape">
    </div>
    <!-- .miwlo-why-choose-right-shapes -->
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="miwlo-why-choose-text text-center" data-aos="fade-up" data-aos-delay="100">
            <p class="section-subheading">Best SMM Panel</p>
            <h3 class="section-heading">{{ __t('Why choose us?') }}</h3>
          </div>
          <!-- .miwlo-why-choose-text -->
        </div>
        <!-- .col-md-7 -->
      </div>
      <!-- .row -->
      <div class="row text-center">
        <div class="col-12 col-md-6 col-lg" data-aos="fade-up" data-aos-delay="200">
          <div class="why-choice-options option-one">
            <div class="why-choice-options-img-wrap">
              <img src="/ladi/classic/images/icons/icon-01.png" alt="Best SMM Panel">
            </div>
            <!-- .why-choice-options -->
            <h5>Best SMM Panel</h5>
            <p>The best quality services you can find online with our Premium SMM Panel.</p>
          </div>
          <!-- .why-choice-options -->
        </div>
        <!-- .col-md-6 col-lg -->
        <div class="col-12 col-md-6 col-lg" data-aos="fade-up" data-aos-delay="400">
          <div class="why-choice-options option-two">
            <div class="why-choice-options-img-wrap">
              <img src="/ladi/classic/images/icons/icon-02.png" alt="Variety of Services">
            </div>
            <!-- .why-choice-options -->
            <h5>Variety of Services</h5>
            <p>Wide range of services, such as Likes, Followers, Views, and more.</p>
          </div>
          <!-- .why-choice-options -->
        </div>
        <!-- .col-md-6 col-lg -->
        <div class="col-12 col-md-6 col-lg" data-aos="fade-up" data-aos-delay="600">
          <div class="why-choice-options option-three">
            <div class="why-choice-options-img-wrap">
              <img src="/ladi/classic/images/icons/icon-03.png" alt="Secure Payments">
            </div>
            <!-- .why-choice-options -->
            <h5>Secure Payments</h5>
            <p>We offer secure payments through Bank or other methods.</p>
          </div>
          <!-- .why-choice-options -->
        </div>
        <!-- .col-md-6 col-lg -->
        <div class="col-12 col-md-6 col-lg" data-aos="fade-up" data-aos-delay="600">
          <div class="why-choice-options option-four">
            <div class="why-choice-options-img-wrap">
              <img src="/ladi/classic/images/icons/icon-07.png" alt="24/7 Client Service">
            </div>
            <!-- .why-choice-options -->
            <h5>24/7 Client Service</h5>
            <p>Our Team is always available to answer any questions.</p>
          </div>
          <!-- .why-choice-options -->
        </div>
        <!-- .col-md-6 col-lg -->
      </div>
      <!-- .row -->
    </div>
    <!-- .container -->
    <div class="miwlo-why-choose-left-shape">
      <img data-parallax='{"y" : 50}' src="/ladi/classic/images/shape/shape-06.png" alt="Shape">
    </div>
    <!-- .miwlo-why-choose-left-shapes -->
  </div>
  <!-- .miwlo-why-choose-wrap -->

  <!-- ================================================================= -->
  <!-- ========================== Footer Wrap ========================== -->
  <!-- ================================================================= -->

  <div class="miwlo-footer-wrap">
    <div class="footer-triangle-shape-top miwlo-parallax">
      <div class="layer" data-depth="1">
        <div>
          <img data-parallax='{"y" : 30}' src="/ladi/classic/images/shape/shape-11.png" alt="Triangle">
        </div>
      </div>
    </div>
    <!-- .footer-triangle-shape-top -->
    <div class="container">
      <div>
        <div class="miwlo-footer-text text-center">
          <p>@ {{ date('Y') }}</p>
        </div>
        <!-- .miwlo-footer-text -->
      </div>
      <!-- .row -->
    </div>
    <!-- .container -->
    <div class="footer-triangle-shape-bottom miwlo-parallax">
      <div class="layer" data-depth="1">
        <div>
          <img data-parallax='{"y" : 30}' src="/ladi/classic/images/shape/shape-12.png" alt="Triangle">
        </div>
      </div>
    </div>
    <!-- .footer-triangle-shape-bottom -->
    <img class="app-circle-shape-footer" src="/ladi/classic/images/shape/circle-line-footer.png" alt="Circle">
  </div>
  <!-- .miwlo-footer-wrap -->

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <!-- Modernizr -->
  <script src="/ladi/classic/js/vendor/modernizr-3.11.2.min.js"></script>

  <!-- Parallax -->
  <script src="/ladi/classic/js/parallax.min.js"></script>
  <script src="/ladi/classic/js/parallax-scroll.js"></script>

  <!-- Animation -->
  <script src="/ladi/classic/js/aos.js"></script>

  <!-- Slider -->
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

  <!-- Fonts -->
  <script src="/ladi/classic/js/font-awesome.min.js"></script>

  <!-- Mobile Menu -->
  <script src="/ladi/classic/js/slinky.min.js"></script>

  <!-- Miwlo JS -->
  <script src="/ladi/classic/js/app.js"></script>
</body>

</html>

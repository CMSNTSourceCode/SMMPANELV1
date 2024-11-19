<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

  <!--====== Bootstrap css ======-->
  <link rel="stylesheet" href="/ladi/modern/assets/css/bootstrap.min.css" />
  <!--====== Slick Slider ======-->
  <link rel="stylesheet" href="/ladi/modern/assets/css/slick.min.css" />
  <!--====== Magnific ======-->
  <link rel="stylesheet" href="/ladi/modern/assets/css/magnific-popup.min.css" />
  <!--====== Nice Select ======-->
  <link rel="stylesheet" href="/ladi/modern/assets/css/nice-select.min.css" />
  <!--====== Animate CSS ======-->
  <link rel="stylesheet" href="/ladi/modern/assets/css/animate.min.css" />
  <!--====== Jquery UI CSS ======-->
  <link rel="stylesheet" href="/ladi/modern/assets/css/jquery-ui.min.css" />
  <!--====== Font Awesome ======-->
  <link rel="stylesheet" href="/ladi/modern/assets/fonts/fontawesome/css/all.min.css" />
  <!--====== Flaticon ======-->
  <link rel="stylesheet" href="/ladi/modern/assets/fonts/flaticon/flaticon.css" />
  <!--====== Spacing Css ======-->
  <link rel="stylesheet" href="/ladi/modern/assets/css/spacing.min.css" />
  <!--====== Main Css ======-->
  <link rel="stylesheet" href="/ladi/modern/assets/css/style.css" />
  <!--====== Responsive CSS ======-->
  <link rel="stylesheet" href="/ladi/modern/assets/css/responsive.css" />

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
  <!--======= Start Preloader =======-->
  <div id="preloader">
    <img class="preloader-image" width="60" src="/ladi/modern/assets/img/preloader-logo.png" alt="preloader" />
  </div>
  <!--======= End Preloader =======-->

  <!--====== Start Header ======-->
  <header class="template-header navbar-center absolute-header nav-white-color submenu-seconday-color nav-border-bottom sticky-header">
    <div class="container-fluid container-1430">
      <div class="header-inner">
        <div class="header-left">
          <div class="brand-logo">
            <a href="{{ route('index') }}">
              <img src="{{ setting('logo_light', '/ladi/modern/assets/img/logo-white-2.png') }}" alt="logo" class="main-logo" width="180" height="60">
              <img src="{{ setting('logo_dark', '/ladi/modern/assets/img/logo-4.png') }}" alt="logo" class="sticky-logo" width="180" height="60">
            </a>
          </div>
        </div>
        <div class="header-center">
          <nav class="nav-menu d-none d-xl-block">
            <ul>
              <li class="active">
                <a href="{{ route('index') }}">{{ __t('Trang chủ') }}</a>
              </li>
              <li>
                <a href="javascript:void(0)">{{ __t('Dịch vụ') }}</a>
                <ul class="sub-menu">
                  <li><a href="{{ route('login') }}">{{ __t('Dịch vụ :name', ['name' => 'Facebook']) }}</a></li>
                  <li><a href="{{ route('login') }}">{{ __t('Dịch vụ :name', ['name' => 'Instagram']) }}</a></li>
                  <li><a href="{{ route('login') }}">{{ __t('Dịch vụ :name', ['name' => 'Tiktok']) }}</a></li>
                  <li><a href="{{ route('login') }}">{{ __t('Dịch vụ :name', ['name' => 'Youtube']) }}</a></li>
                  <li><a href="{{ route('login') }}">{{ __t('Dịch vụ :name', ['name' => 'Twitter']) }}</a></li>
                  <li><a href="{{ route('login') }}">{{ __t('Dịch vụ :name', ['name' => 'Telegram']) }}</a></li>
                  <li><a href="{{ route('login') }}">{{ __t('Dịch vụ :name', ['name' => 'Threads']) }}</a></li>
                  <li><a href="{{ route('login') }}">{{ __t('Dịch vụ :name', ['name' => 'Google']) }}</a></li>
                </ul>
              </li>
              <li>
                <a href="{{ route('pages.tos') }}">{{ __t('Điều khoản') }}</a>
              </li>
              <li>
                <a href="{{ route('pages.api-docs') }}">{{ __t('Tài liệu API') }}</a>
              </li>
            </ul>
          </nav>
        </div>
        <div class="header-right">
          <ul class="header-extra">
            @auth
              <li class="d-none d-md-block">
                <a href="{{ route('login') }}" class="template-btn secondary-bg">
                  {{ __t('Sử dụng ngay') }} <i class="fas fa-arrow-right"></i>
                </a>
              </li>
            @else
              <li class="d-none d-sm-block">
                <a href="{{ route('register') }}" class="user-login">
                  <i class="far fa-user-circle"></i> {{ __t('Đăng nhập') }}
                </a>
              </li>

            @endauth
            <li class="d-xl-none">
              <a href="#" class="navbar-toggler">
                <span></span>
                <span></span>
                <span></span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Start Mobile Slide Menu -->
    <div class="mobile-slide-panel">
      <div class="panel-overlay"></div>
      <div class="panel-inner">
        <div class="mobile-logo">
          <a href="index.html">
            <img src="{{ setting('logo_dark', '/ladi/modern/assets/img/logo-1.png') }}" width="180" height="60" alt="Landio">
          </a>
        </div>
        <nav class="mobile-menu">
          <ul>
            <li>
              <a href="{{ route('index') }}">{{ __t('Trang chủ') }}</a>
            </li>
            <li>
              <a href="{{ route('pages.tos') }}">{{ __t('Điều khoản') }}</a>
            </li>
            <li>
              <a href="{{ route('pages.api-docs') }}">{{ __t('Tài liệu API') }}</a>
            </li>
          </ul>
        </nav>
        <a href="#" class="panel-close">
          <i class="fal fa-times"></i>
        </a>
      </div>
    </div>
    <!-- End Mobile Slide Menu -->
  </header>
  <!--====== End Header ======-->

  <!--====== Start Hero Area ======-->
  <section class="hero-area-v2">
    <div class="hero-content-wrapper">
      <div class="container-fluid">
        <div class="row align-items-center justify-content-center">
          <div class="col-xl-5 col-lg-6 col-md-8">
            <div class="hero-content">
              <span class="title-tag wow fadeInDown" data-wow-delay="0.2s">
                <span>{{ strtoupper(domain()) }}</span>
              </span>
              <h1 class="hero-title wow fadeInUp" data-wow-delay="0.3s" style="font-size:  45px">
                Best Quality And Reliable Services Provider Since 2024
              </h1>
              <ul class="hero-btns d-flex align-items-center">
                <li class="wow fadeInUp" data-wow-delay="0.4s">
                  <a href="{{ route('login') }}" class="template-btn bordered-btn">
                    {{ __t('Bắt đầu ngay') }} <i class="fas fa-arrow-right"></i>
                  </a>
                </li>
                <li class="wow fadeInUp" data-wow-delay="0.3s">
                  <a href="https://vimeo.com/87110435" class="play-btn popup-video">
                    <i class="fas fa-play"></i>
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-xl-7 col-lg-6 col-md-10">
            <div class="hero-img preview-blob-image with-floating-icon text-center wow fadeInUp" data-wow-delay="0.4s">
              <img src="/images/ladi/banner01.webp" alt="Image">

              <div class="floating-icons">
                <img src="/ladi/modern/assets/img/particle/thumbs-up-white.png" alt="Icon" class="icon-1 animate-float-bob-y">
                <img src="/ladi/modern/assets/img/particle/announcement-mic-white.png" alt="Icon" class="icon-2 animate-float-bob-x">
                <img src="/ladi/modern/assets/img/particle/paper-plane-white.png" alt="Icon" class="icon-3 animate-float-bob-x">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Info Boxes -->
    <div class="container-fluid container-1380">
      <div class="info-boxes-wrapper wow fadeInUp" data-wow-delay="0.4s">
        <div class="info-boxes">
          <div class="box-item">
            <div class="box-icon">
              <img src="/ladi/modern/assets/img/icon/infobox-icon-1.png" alt="info icon one">
            </div>
            <div class="box-content">
              <h4 class="box-title">Super Fast Delivery</h4>
              <p>We always make sure that our services are super affordable.</p>
            </div>
          </div>
          <div class="box-item">
            <div class="box-icon">
              <img src="/ladi/modern/assets/img/icon/infobox-icon-2.png" alt="info icon two">
            </div>
            <div class="box-content">
              <h4 class="box-title">Cheapest SMM Panel</h4>
              <p>We always make sure that our services are super affordable.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--====== End Hero Area ======-->

  <!--====== Start About Section ======-->
  <section class="about-section p-t-130 p-b-130">
    <div class="container">
      <div class="row justify-content-lg-between justify-content-center align-items-center">
        <div class="col-xl-6 col-lg-6 col-md-10">
          <div class="preview-blob-image with-floating-icon m-b-md-100">
            <img src="/images/ladi/banner02.png" alt="Image">

            <div class="floating-icons">
              <img src="/ladi/modern/assets/img/particle/thumbs-up.png" alt="Icon" class="icon-1 animate-float-bob-y">
              <img src="/ladi/modern/assets/img/particle/announcement-mic.png" alt="Icon" class="icon-2 animate-float-bob-x">
              <img src="/ladi/modern/assets/img/particle/paper-plane.png" alt="Icon" class="icon-3 animate-float-bob-x">
            </div>
          </div>
        </div>
        <div class="col-xl-5 col-lg-6 col-md-10">
          <div class="about-text">
            <div class="common-heading tagline-boxed-two title-line line-less-bottom m-b-40">
              <span class="tagline">About Landio</span>
              <h2 class="title">Best <span>SMM Panel <img src="/ladi/modern/assets/img/particle/title-line.png" alt="Line"></span></h2>
            </div>
            <p class="text-pullquote pullquote-secondary-color m-b-35">All in One Social media Marketing Panel</p>
            <p>{{ domain() }} generates full-fledged promotion for 15 popular social networks: Instagram, YouTube, TikTok, Telegram, Facebook, Twitter, Spotify, Twitch, SoundCloud, Likee, Mixcloud, Linkedin,
              Reddit, SoundCloud, Pinterest, and many others.</p>
            <a href="{{ route('login') }}" class="template-btn primary-bg-2 m-t-40">{{ __t('Xem thêm') }} <i class="far fa-arrow-right"></i></a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--====== End About Section ======-->

  <!--====== Start Service With counter ======-->
  <div class="service-with-counter">
    <!--====== Start Counter Section ======-->
    <section class="counter-section counter-section-bordered bordered-secondary-bg">
      <div class="container-fluid container-1420">
        <div class="counter-section-inner">
          <div class="row counter-items-v2">
            <div class="col-lg-4 col-sm-6">
              <div class="counter-item white-color counter-left">
                <div class="counter-wrap">
                  <span class="counter">2345</span>
                  <span class="suffix"><i class="far fa-plus"></i></span>
                </div>
                <p class="title">
                  Total Services
                </p>
              </div>
            </div>
            <div class="col-lg-4 col-sm-6">
              <div class="counter-item white-color counter-left">
                <div class="counter-wrap">
                  <span class="counter">32333</span>
                  <span class="suffix"><i class="far fa-plus"></i></span>
                </div>
                <p class="title">
                  Total Orders
                </p>
              </div>
            </div>
            <div class="col-lg-4 col-sm-6">
              <div class="counter-item white-color counter-left">
                <div class="counter-wrap">
                  <span class="counter">3299</span>
                  <span class="suffix"><i class="far fa-plus"></i></span>
                </div>
                <p class="title">
                  Total Users
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--====== End Counter Section ======-->

    <!--====== Service Section Start ======-->
    <section class="service-section bg-soft-grey-color">
      <div class="container-fluid fluid-gap-70">
        <!-- Common Heading -->
        <div class="row justify-content-center">
          <div class="col-lg-5">
            <div class="common-heading tagline-boxed-two title-line m-b-80 text-center">
              <span class="tagline">Popular Services</span>
              <h2 class="title">Our SMM Panel <span>Features <img src="/ladi/modern/assets/img/particle/title-line.png" alt="Line"></span></h2>
            </div>
          </div>
        </div>
        <!-- Image boxes -->
        <div class="row image-boxes-v1 image-thumbnail-boxed" id="serviceSliderActive">
          <div class="col">
            <div class="image-box">
              <div class="thumb">
                <img src="/ladi/modern/assets/img/services/service-thumbnail-2.png" alt="Image">
              </div>
              <h4 class="title">Low Price</h4>
              <a href="javascript:void(0)" class="box-link"><i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
          <div class="col">
            <div class="image-box">
              <div class="thumb">
                <img src="/ladi/modern/assets/img/services/service-thumbnail-3.png" alt="Image">
              </div>
              <h4 class="title">Guaranteed Services</h4>
              <a href="javascript:void(0)" class="box-link"><i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
          <div class="col">
            <div class="image-box">
              <div class="thumb">
                <img src="/ladi/modern/assets/img/services/service-thumbnail-4.png" alt="Image">
              </div>
              <h4 class="title">24/7 Support</h4>
              <a href="javascript:void(0)" class="box-link"><i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
          <div class="col">
            <div class="image-box">
              <div class="thumb">
                <img src="/ladi/modern/assets/img/services/service-thumbnail-5.png" alt="Image">
              </div>
              <h4 class="title">Best SMM Panel</h4>
              <a href="javascript:void(0)" class="box-link"><i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
          <div class="col">
            <div class="image-box">
              <div class="thumb">
                <img src="/ladi/modern/assets/img/services/service-thumbnail-1.png" alt="Image">
              </div>
              <h4 class="title">Cheapest SMM Panel</h4>
              <a href="javascript:void(0)" class="box-link"><i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
          <div class="col">
            <div class="image-box">
              <div class="thumb">
                <img src="/ladi/modern/assets/img/services/service-thumbnail-2.png" alt="Image">
              </div>
              <h4 class="title">Super Fast Delivery</h4>
              <a href="javascript:void(0)" class="box-link"><i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
          <div class="col">
            <div class="image-box">
              <div class="thumb">
                <img src="/ladi/modern/assets/img/services/service-thumbnail-3.png" alt="Image">
              </div>
              <h4 class="title">Flexible system</h4>
              <a href="javascript:void(0)" class="box-link"><i class="fas fa-arrow-right"></i></a>
            </div>
          </div>

        </div>
      </div>
    </section>
    <!--====== Service Section End ======-->
  </div>
  <!--====== End Service With counter ======-->

  <!--====== Start Team Section ======-->
  <section class="team-section team-masonry-section p-t-150 p-b-130">
    <div class="container">
      <div class="row">
        <div class="col-xl-5 col-lg-6 col-md-6">
          <div class="common-heading tagline-boxed-two title-line">
            <span class="tagline">Guaranteed Services</span>
            <h2 class="title">SMM Panel <span>Services <img src="/ladi/modern/assets/img/particle/title-line.png" alt="Line"></span></h2>
          </div>
        </div>
        <div class="col-lg-9 ml-auto">
          <div class="team-members team-masonry wow fadeInUp" data-wow-delay="0.3s">
            <div class="masonry-item">
              <div class="member-box">
                <div class="member-photo">
                  <img src="/images/services/facebook.png" alt="Member Photo">
                </div>
                <div class="member-info">
                  <h5 class="name"><a href="#">Facebook</a></h5>
                  <p class="title">+15 services</p>
                </div>
              </div>
            </div>
            <div class="masonry-item">
              <div class="member-box">
                <div class="member-photo">
                  <img src="/images/services/instagram.png" alt="Member Photo">
                </div>
                <div class="member-info">
                  <h5 class="name"><a href="#">Instagram</a></h5>
                  <p class="title">+10 services</p>
                </div>
              </div>
            </div>
            <div class="masonry-item">
              <div class="member-box">
                <div class="member-photo">
                  <img src="/images/services/youtube.png" alt="Member Photo">
                </div>
                <div class="member-info">
                  <h5 class="name"><a href="#">Youtube</a></h5>
                  <p class="title">+10 services</p>
                </div>
              </div>

            </div>
            <div class="masonry-item">
              <div class="member-box">
                <div class="member-photo">
                  <img src="/images/services/tiktok.png" alt="Member Photo">
                </div>
                <div class="member-info">
                  <h5 class="name"><a href="#">Tiktok</a></h5>
                  <p class="title">+16 services</p>
                </div>
              </div>
            </div>
            <div class="masonry-item">
              <div class="more-btn text-center">
                <a href="{{ route('login') }}" class="template-btn primary-bg-2">{{ __t('Xem thêm') }} <i class="far fa-arrow-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--====== End Team Section ======-->

  <!--====== Start Faq With SEO score box ======-->
  <div class="faq-with-seo-score-box">
    <!--====== Start SEO score box ======-->
    <div class="container">
      <div class="seo-score-box wow fadeInDown">
        <div class="row justify-content-center">
          <div class="col-xl-8 col-md-10 col-11">
            <div class="score-box-content">
              <h2 class="score-box-title">
                Are you waiting for something?
              </h2>
              <p>Elevate your influence by starting to use our services</p>
            </div>
            <form class="score-box-form">
              <button type="submit" name="submit" class="template-btn secondary-bg">
                {{ __t('Sử dụng ngay') }} <i class="fas fa-arrow-right"></i>
              </button>
            </form>
          </div>
        </div>
        <div class="seo-images"></div>
      </div>
    </div>
    <!--====== End SEO score box ======-->

    <!--====== Start FAQ section ======-->
    <section class="faq-section bg-soft-grey-color">
      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-lg-6 col-md-10">
            <div class="faq-content p-r-60 p-r-lg-30 p-r-md-0">
              <div class="common-heading tagline-boxed-two title-line m-b-80">
                <span class="tagline">FAQs</span>
                <h2 class="title">Frequently Asked Questions</h2>
              </div>
              <div class="landio-accordion-v1">
                <div class="accordion" id="accordionFAQ">
                  <div class="accordion-item">
                    <h5 class="accordion-header" id="headingOne">
                      <button class="accordion-button collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        What is SMM Panel?
                      </button>
                    </h5>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionFAQ">
                      <div class="accordion-body">
                        <p>SMM Panel is a tool that allows you to safely purchase social media services such as followers, likes, views, impressions, shares, and comments.</p>
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h5 class="accordion-header" id="headingTwo">
                      <button class="accordion-button" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                        How does SMM Panel work?
                      </button>
                    </h5>
                    <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionFAQ">
                      <div class="accordion-body">
                        <p>Social Media Panel works by connecting your social media accounts to our platform. Once connected, you can purchase services such as followers, likes, views, impressions, shares, and comments.
                          We then use our network of influencers and social media experts to promote your accounts and deliver the services you purchased.</p>
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h5 class="accordion-header" id="headingThree">
                      <button class="accordion-button collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        What makes people look for SMM panels?
                      </button>
                    </h5>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionFAQ">
                      <div class="accordion-body">
                        <p>People look for SMM panels because they are an easy and cost-effective way to promote their social media accounts. They allow users to purchase services that can help to increase their
                          visibility and reach.</p>
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h5 class="accordion-header" id="headingFour">
                      <button class="accordion-button collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        Are your SMM Services safe to buy?
                      </button>
                    </h5>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionFAQ">
                      <div class="accordion-body">
                        <p>Yes, our services are safe to buy. We use only legitimate methods to promote your social media accounts and never use any black hat techniques or spam. We also take measures to protect your
                          account from any potential risks.</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-9">
            <div class="faq-image text-lg-right m-t-md-60">
              <img src="/ladi/modern/assets/img/faq/faq-image.png" alt="faq image" class="animate-float-bob-y">
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--====== End Faq With SEO score box ======-->
  </div>
  <!--====== End FAQ section ======-->

  <!--====== Start Scroll To Top ======-->
  <a href="#" class="back-to-top" id="scroll-top">
    <i class="far fa-angle-up"></i>
  </a>
  <!--====== End Scroll To Top ======-->

  <!--====== Start Footer ======-->
  <footer class="template-footer bg-primary-color-2 footer-white-color">
    <div class="footer-copyright border-top-off-white">
      <div class="container">
        <div class="row align-items-center justify-content-between">
          <div class="col-sm-auto col-12">
            <div class="copyright-logo text-center text-sm-left">
              <img src="{{ setting('logo_light', '/ladi/modern/assets/img/logo-white-2.png') }}" width="150" height="50" alt="Landio">
            </div>
          </div>
          <div class="col-sm-auto col-12">
            <p class="copyright-text text-center text-sm-right pt-4 pt-sm-0">
              © {{ date('Y') }} <a href="javascript:void(0)">{{ domain() }}</a>. All Rights Reserved
            </p>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!--====== End Footer ======-->

  <!--====== Jquery ======-->
  <script src="/ladi/modern/assets/js/jquery-3.6.0.min.js"></script>
  <!--====== Bootstrap ======-->
  <script src="/ladi/modern/assets/js/bootstrap.min.js"></script>
  <!--====== Slick slider ======-->
  <script src="/ladi/modern/assets/js/slick.min.js"></script>
  <!--====== Magnific ======-->
  <script src="/ladi/modern/assets/js/jquery.magnific-popup.min.js"></script>
  <!--====== Isotope Js ======-->
  <script src="/ladi/modern/assets/js/isotope.pkgd.min.js"></script>
  <!--====== Jquery UI Js ======-->
  <script src="/ladi/modern/assets/js/jquery-ui.min.js"></script>
  <!--====== Inview ======-->
  <script src="/ladi/modern/assets/js/jquery.inview.min.js"></script>
  <!--====== Nice Select ======-->
  <script src="/ladi/modern/assets/js/jquery.nice-select.min.js"></script>
  <!--====== Wow ======-->
  <script src="/ladi/modern/assets/js/wow.min.js"></script>
  <!--====== Main JS ======-->
  <script src="/ladi/modern/assets/js/main.js"></script>
</body>

</html>

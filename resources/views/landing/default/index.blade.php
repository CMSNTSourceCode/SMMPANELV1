<!DOCTYPE html>
<html lang="zxx">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Link of CSS files -->
  <link rel="stylesheet" href="/ladi/default/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/ladi/default/assets/css/animate.min.css">
  <link rel="stylesheet" href="/ladi/default/assets/css/fontawesome.min.css">
  <link rel="stylesheet" href="/ladi/default/assets/css/flaticon.css">
  <link rel="stylesheet" href="/ladi/default/assets/css/magnific-popup.min.css">
  <link rel="stylesheet" href="/ladi/default/assets/css/owl.carousel.min.css">
  <link rel="stylesheet" href="/ladi/default/assets/css/owl.theme.default.min.css">
  <link rel="stylesheet" href="/ladi/default/assets/css/style.css">
  <link rel="stylesheet" href="/ladi/default/assets/css/responsive.css">
  <link rel="stylesheet" href="/ladi/default/assets/css/dark-style.css">

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

</head>

<body id="home" data-bs-spy="scroll" data-bs-offset="70">

  <!-- Start Preloader Area -->
  <div class="preloader">
    <div class="spinner"></div>
  </div>
  <!-- End Preloader Area -->

  <!-- Start Navbar Area -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ route('index') }}">
      <img src="{{ setting('logo_dark', '/ladi/default/assets/img/logo.png') }}" style="width: 150px" alt="image">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="#home">{{ __t('Trang chủ') }}</a></li>
        {{-- <li class="nav-item"><a class="nav-link" href="#design">{{ __t('Bài viết') }}</a></li> --}}
        <li class="nav-item"><a class="nav-link" href="#faq">{{ __t('Faqs') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('pages.api-docs') }}">{{ __t('Tài liệu API') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('pages.tos') }}">{{ __t('Điều khoản sử dụng') }}</a></li>
      </ul>

      <div class="others-options">
        <a href="{{ route('login') }}" class="default-btn main-color">{{ __t('Sử dụng ngay') }}</a>

        <div class="dark-version-btn">
          <label id="switch" class="switch">
            <input type="checkbox" onchange="toggleTheme()" id="slider">
            <span class="slider round"></span>
          </label>
        </div>
      </div>
    </div>
  </nav>
  <!-- End Navbar Area -->

  <!-- Start Main Banner Area -->
  <div class="headphone-main-banner">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 col-md-12">
          <div class="hero-content">
            <span>Social Media Script</span>
            <h1>{{ __t('Bảng điều khiển truyền thông xã hội tốt nhất trên thế giới!') }}</h1>
            <p>
              {{ __t('Quản lý tất cả các mạng truyền thông xã hội từ một bảng điều khiển duy nhất, chất lượng và giá rẻ. Chúng tôi cung cấp dịch vụ trên các mạng xã hội phổ biến nhất hiện nay. Chúng tôi có Instagram, Twitter, Facebook, Youtube, TikTok, Spotify và nhiều dịch vụ khác.') }}
            </p>
            <a href="{{ route('login') }}" class="btn btn-primary">{{ __t('Bắt đầu ngay') }}</a>
          </div>
        </div>

        <div class="col-lg-6 col-md-12">
          <div class="headphone-banner-image">
            <img src="/images/ladi/who-we-are.png" alt="image">
          </div>
        </div>
      </div>
    </div>

    <div class="shape1"><img src="/ladi/default/assets/img/shape-image/1.png" alt="image"></div>
    <div class="shape2"><img src="/ladi/default/assets/img/shape-image/2.png" alt="image"></div>
    <div class="shape3"><img src="/ladi/default/assets/img/shape-image/3.png" alt="image"></div>
    <div class="shape4"><img src="/ladi/default/assets/img/shape-image/4.png" alt="image"></div>
    <div class="shape5"><img src="/ladi/default/assets/img/shape-image/5.png" alt="image"></div>
    <div class="shape6"><img src="/ladi/default/assets/img/shape-image/6.png" alt="image"></div>
    <div class="shape7"><img src="/ladi/default/assets/img/shape-image/7.png" alt="image"></div>
    <div class="shape8"><img src="/ladi/default/assets/img/shape-image/8.png" class="rotateme" alt="image"></div>
    <div class="dot-shape1"><img src="/ladi/default/assets/img/shape-image/dot1.png" alt="image"></div>
    <div class="dot-shape2"><img src="/ladi/default/assets/img/shape-image/dot2.png" alt="image"></div>
    <div class="dot-shape3"><img src="/ladi/default/assets/img/shape-image/dot3.png" alt="image"></div>
    <div class="dot-shape4"><img src="/ladi/default/assets/img/shape-image/dot4.png" alt="image"></div>
    <div class="dot-shape5"><img src="/ladi/default/assets/img/shape-image/dot5.png" alt="image"></div>
    <div class="dot-shape6"><img src="/ladi/default/assets/img/shape-image/dot6.png" alt="image"></div>
  </div>
  <!-- End Main Banner Area -->

  <!-- Start About Area -->
  <section id="about" class="headphone-about-area">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 col-md-12">
          <div class="headphone-about-image">
            <img src="/images/ladi/why-us.png" alt="image">
          </div>
        </div>

        <div class="col-lg-6 col-md-12">
          <div class="headphone-about-content">
            <h2>{{ __t('Chúng tôi đang giúp thống trị mạng xã hội với bảng điều khiển mạng xã hội lớn nhất.') }}</h2>
            <p>
              {{ __t('Chúng tôi chỉ hoạt động hỗ trợ 24 giờ một ngày và bảy lần một tuần với tất cả các nhu cầu và dịch vụ của bạn suốt cả ngày. Đừng đi đâu khác. Chúng tôi sẵn sàng phục vụ bạn và giúp đỡ bạn với tất cả các nhu cầu SMM của bạn. Người dùng hoặc Khách hàng có đơn đặt hàng SMM và cần dịch vụ SMM GIÁ RẺ sẽ được chào đón nhiều hơn trong PANEL SMM của chúng tôi.') }}
            </p>

            <ul>
              <li><i class="flaticon-check-square"></i> {{ __t('Đa dạng dịch vụ') }}</li>
              <li><i class="flaticon-check-square"></i> {{ __t('Chất lượng tuyệt vời') }}</li>
              <li><i class="flaticon-check-square"></i> {{ __t('Giá không thể tin được') }}</li>
              <li><i class="flaticon-check-square"></i> {{ __t('Nhiều phương thức thanh toán') }}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End About Area -->

  <!-- Start Features Area -->
  <section id="features" class="headphone-features-area">
    <div class="container">
      <div class="row">
        <div class="col-lg-2 col-6 col-md-4">
          <div class="single-features">
            <i class="flaticon-vr-glasses"></i>
            <h3>{{ __t('Đại lý') }}</h3>
          </div>
        </div>

        <div class="col-lg-2 col-6 col-md-4">
          <div class="single-features">
            <i class="flaticon-layers"></i>
            <h3>{{ __t('Dịch vụ chất lượng cao') }}</h3>
          </div>
        </div>

        <div class="col-lg-2 col-6 col-md-4">
          <div class="single-features">
            <i class="flaticon-remote-control"></i>
            <h3>{{ __t('Hỗ trợ') }}</h3>
          </div>
        </div>

        <div class="col-lg-2 col-6 col-md-4">
          <div class="single-features">
            <i class="flaticon-tap"></i>
            <h3>{{ __t('Cập nhật') }}</h3>
          </div>
        </div>

        <div class="col-lg-2 col-6 col-md-4">
          <div class="single-features">
            <i class="flaticon-3d-camera"></i>
            <h3>{{ __t('Hỗ trợ API') }}</h3>
          </div>
        </div>

        <div class="col-lg-2 col-6 col-md-4">
          <div class="single-features">
            <i class="flaticon-virtual-reality"></i>
            <h3>{{ __t('Thanh toán an toàn') }}</h3>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Features Area -->

  <!-- Start FAQ Area -->
  <section id="faq" class="faq-area ptb-120 bg-f4faff">
    <div class="container">
      <div class="section-title">
        <h2>{{ __t('Các câu hỏi thường gặp') }}</h2>
        <p>{{ __t('Chúng tôi đã trả lời một số câu hỏi thường gặp nhất trong hội thảo của mình.') }}</p>
      </div>

      <div class="row align-items-center">
        <div class="col-lg-6 col-md-12">
          <div class="faq-accordion">
            <ul class="accordion">
              <li class="accordion-item">
                <a class="accordion-title" href="javascript:void(0)"><i class="fas fa-chevron-right"></i> SMM panels - what are they?</a>
                <p class="accordion-content">An SMM panel is an online shop that you can visit to puchase SMM services at great prices.</p>
              </li>

              <li class="accordion-item">
                <a class="accordion-title" href="javascript:void(0)"><i class="fas fa-chevron-right"></i> What SMM services can I find on this panel?</a>
                <p class="accordion-content">We sell different types of SMM services — likes, followers, views, etc.</p>
              </li>

              <li class="accordion-item">
                <a class="accordion-title" href="javascript:void(0)"><i class="fas fa-chevron-right"></i> Are SMM services on your panel safe to buy?</a>
                <p class="accordion-content">Sure! Your accounts won't get banned.</p>
              </li>

              <li class="accordion-item">
                <a class="accordion-title" href="javascript:void(0)"><i class="fas fa-chevron-right"></i> How does a mass order work?</a>
                <p class="accordion-content">It's possible to place multiple orders with different links at once with the help of the mass order feature.</p>
              </li>

              <li class="accordion-item">
                <a class="accordion-title" href="javascript:void(0)"><i class="fas fa-chevron-right"></i> What does Drip-feed mean?</a>
                <p class="accordion-content">Grow your accounts as fast as you want with the help of Drip-feed. How it works: let's say you want 2000 likes on your post. Instead of getting all 2000 at once, you can get
                  200 each day for 10 days.</p>
              </li>
            </ul>
          </div>
        </div>

        <div class="col-lg-6 col-md-12">
          <div class="faq-image">
            <img src="/ladi/default/assets/img/faq.png" alt="image">
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End FAQ Area -->

  <!-- Start Feedback Area -->
  <section id="review" class="feedback-area ptb-120">
    <div class="container">
      <div class="section-title">
        <h2>User Ratings Are A Confirmation <br>Of Our Quality</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida. Risus commodo viverra maecenas accumsan
          lacus vel facilisis.</p>
      </div>

      <div class="row">
        <div class="feedback-slides owl-carousel owl-theme">
          <div class="col-lg-12 col-md-12">
            <div class="feedback-item">
              <div class="client-profile">
                <img src="/ladi/default/assets/img/client1.jpg" alt="image">

                <div class="rating">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>

              <p>Quis ipsum suspendisse ultrices gravida. Risus top commodo viverra maecenas accumsan lacus vel facilisis ultrices gravida.</p>

              <div class="client-info">
                <h4>Jason Statham</h4>
                <span>Project Manager</span>
              </div>
            </div>
          </div>

          <div class="col-lg-12 col-md-12">
            <div class="feedback-item">
              <div class="client-profile">
                <img src="/ladi/default/assets/img/client2.jpg" alt="image">

                <div class="rating">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>

              <p>Quis ipsum suspendisse ultrices gravida. Risus top commodo viverra maecenas accumsan lacus vel facilisis ultrices gravida.</p>

              <div class="client-info">
                <h4>Jason Roy</h4>
                <span>Project Manager</span>
              </div>
            </div>
          </div>

          <div class="col-lg-12 col-md-12">
            <div class="feedback-item">
              <div class="client-profile">
                <img src="/ladi/default/assets/img/client3.jpg" alt="image">

                <div class="rating">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>

              <p>Quis ipsum suspendisse ultrices gravida. Risus top commodo viverra maecenas accumsan lacus vel facilisis ultrices gravida.</p>

              <div class="client-info">
                <h4>Sarah Taylor</h4>
                <span>Web Developer</span>
              </div>
            </div>
          </div>

          <div class="col-lg-12 col-md-12">
            <div class="feedback-item">
              <div class="client-profile">
                <img src="/ladi/default/assets/img/client2.jpg" alt="image">

                <div class="rating">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>

              <p>Quis ipsum suspendisse ultrices gravida. Risus top commodo viverra maecenas accumsan lacus vel facilisis ultrices gravida.</p>

              <div class="client-info">
                <h4>James Anderson</h4>
                <span>Web Designer</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Feedback Area -->

  <!-- Start Our Clients Area -->
  <section class="our-clients-area ptb-120 bg-f4faff">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-5 col-md-12">
          <div class="our-clients-content">
            <h2>{{ __t('Cung cấp dịch vụ trên tất cả các mạng xã hội') }}</h2>
            <p>
              {{ __t('Bảng điều khiển dịch vụ SMM tốt nhất và rẻ nhất trực tuyến cho TikTok, Instagram, YouTube, Facebook, Spotify, Telegram, Twitch, Twitter, Lưu lượng truy cập trang web, LinkedIn, Discord và SoundCloud từ Nhà cung cấp :provider SMM.', ['provider' => domain()]) }}
            </p>
            <a href="#" class="btn btn-light">View More</a>
          </div>
        </div>

        <div class="col-lg-7 col-md-12">
          <div class="our-clients-list">
            <div class="row">
              <div class="col-lg-4 col-md-4 col-6">
                <div class="single-clients">
                  <a href="#">
                    <img src="/images/services/facebook.png" width="96" alt="image">
                  </a>
                </div>

                <div class="single-clients">
                  <a href="#">
                    <img src="/images/services/instagram.png" width="96" alt="image">
                  </a>
                </div>
              </div>

              <div class="col-lg-4 col-md-4 col-6">
                <div class="single-clients">
                  <a href="#">
                    <img src="/images/services/tiktok.png" width="96" alt="image">
                  </a>
                </div>

                <div class="single-clients">
                  <a href="#">
                    <img src="/images/services/youtube.png" width="96" alt="image">
                  </a>
                </div>
              </div>

              <div class="col-lg-4 col-md-4 col-12">
                <div class="single-clients">
                  <a href="#">
                    <img src="/images/services/telegram.png" width="96" alt="image">
                  </a>
                </div>

                <div class="single-clients">
                  <a href="#">
                    <img src="/images/services/twitter.png" width="96" alt="image">
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="map-box"><img src="/ladi/default/assets/img/map.png" alt="image"></div>
  </section>
  <!-- End Our Clients Area -->

  <!-- Start Footer Area -->
  <footer class="footer-area">
    <div class="container">

      <div class="copyright-area">
        <div class="row align-items-center">
          <div class="col-lg-6 col-md-6">
            <p>Copyright reserved by {{ domain() }} | Developer <a href="{{ setting('footer_link', 'https://www.cmsnt.co/?utm=smmpanelv3&domain=' . domain()) }}">{{ setting('footer_text', 'CMSNT.CO LTD') }}</a>.
            </p>
          </div>

          <div class="col-lg-6 col-md-6">
            <ul>
              <li><a href="#">Privacy Policy</a></li>
              <li><a href="#">Terms & Conditions</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!-- End Footer Area -->

  <div class="go-top"><i class="fas fa-arrow-up"></i></div>

  <!-- Link of JS files -->
  <script src="/ladi/default/assets/js/jquery.min.js"></script>
  <script src="/ladi/default/assets/js/bootstrap.bundle.min.js"></script>
  <script src="/ladi/default/assets/js/parallax.min.js"></script>
  <script src="/ladi/default/assets/js/jquery.magnific-popup.min.js"></script>
  <script src="/ladi/default/assets/js/owl.carousel.min.js"></script>
  <script src="/ladi/default/assets/js/tilt.jquery.min.js"></script>
  <script src="/ladi/default/assets/js/wow.min.js"></script>
  <script src="/ladi/default/assets/js/jquery.ajaxchimp.min.js"></script>
  <script src="/ladi/default/assets/js/form-validator.min.js"></script>
  <script src="/ladi/default/assets/js/contact-form-script.js"></script>
  <script src="/ladi/default/assets/js/main.js"></script>
</body>

</html>

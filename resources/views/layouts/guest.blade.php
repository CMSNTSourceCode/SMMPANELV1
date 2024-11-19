<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>

  <!-- Meta Data -->
  <meta charset="UTF-8">
  <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> @yield('title') </title>

  <!-- Favicon -->
  <link rel="icon" href="/assets/images/brand-logos/favicon.ico" type="image/x-icon">

  <!-- Choices JS -->
  <script src="/assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>

  <!-- Main Theme Js -->
  <script src="/assets/js/main.js"></script>

  <!-- Bootstrap Css -->
  <link id="style" href="/assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Style Css -->
  <link href="/assets/css/styles.min.css" rel="stylesheet">

  <!-- Icons Css -->
  <link href="/assets/css/icons.css" rel="stylesheet">

  <!-- Node Waves Css -->
  <link href="/assets/libs/node-waves/waves.min.css" rel="stylesheet">

  <!-- Simplebar Css -->
  <link href="/assets/libs/simplebar/simplebar.min.css" rel="stylesheet">

  <!-- Color Picker Css -->
  <link rel="stylesheet" href="/assets/libs/flatpickr/flatpickr.min.css">
  <link rel="stylesheet" href="/assets/libs/@simonwep/pickr/themes/nano.min.css">

  <!-- Choices Css -->
  <link rel="stylesheet" href="/assets/libs/choices.js/public/assets/styles/choices.min.css">

  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800&display=swap">

  <script>
    window.webData = @json([
        'csrfToken' => csrf_token(),
    ]);
    window.userData = @json(auth()->user());
  </script>

  @vite(['resources/css/app.css'])

  <style>
    :root {
      --primary: {{ setting('color_primary', '#240750') }};
      --primary-rgb: {{ hex2rgb(setting('color_primary', '#240750')) }};
      --primary-hover: {{ setting('color_primary_hover', '#240750') }};
    }

    body {
      font-family: 'Archivo', sans-serif;
      letter-spacing: 0.5px;
    }

    .login-img {
      background-image: url('{{ theme_setting('auth_bg', '/images/bg-auth.jpg') }}');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }
  </style>

  @yield('css')
  @yield('styles')
</head>

<body class="login-img">

  <!-- Loader -->
  <div id="loader">
    <img src="/assets/images/media/loader.svg" alt="">
  </div>
  <!-- Loader -->

  <div class="page">
    @yield('content')
  </div>

  <!-- Jquery Js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <!-- extra js-->
  <script src="https://unpkg.com/clipboard@2/dist/clipboard.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.27/sweetalert2.min.js"></script>

  @vite(['resources/js/app.js', 'resources/js/functions.js'])

  @yield('scripts')

</body>

</html>

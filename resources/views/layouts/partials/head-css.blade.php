@php use App\Helpers\Helper; @endphp

<head>

  <!-- Meta Data -->
  <meta charset="UTF-8">
  <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

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

  <link rel="stylesheet" href="/assets/libs/apexcharts/apexcharts.css">

  <link rel="stylesheet" href="/plugins/pace-js/flash.min.css">
  <script src="/plugins/pace-js/pace.min.js"></script>

  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@600;700;800&display=swap">

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/autofill/2.6.0/css/autoFill.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.2/sweetalert2.min.css">

  {{-- Scripts --}}
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
      --primary-hover: {{ setting('color_primary_hover', '#fff') }};
    }

    body {
      font-family: 'Roboto', sans-serif;
      letter-spacing: 0.5px;
    }

    .card-header>.card-title {
      text-transform: uppercase !important;
    }

    .table thead tr th {
      background-color: var(--primary) !important;
      color: var(--primary-hover);
    }
  </style>

  @yield('css')
  @yield('styles')

  {!! Helper::getNotice('header_script') !!}

  <script>
    window.LANG = @json(getLangJson() ?? []);
    window.USER_CURRENCY = @json(cur_user_setting() ?? []);
    window.DEFAULT_CURRENCY = @json(cur_setting() ?? []);

    window.__t = function(key) {
      if (window.LANG[key] === undefined) {
        // console.log(key);
      }
      return window.LANG[key] || key;
    }

    window.__defaultLang = '{{ currentLang() }}';

    // window.$formatCurrency = function(number) {
    //   number = USER_CURRENCY.currency_code !== 'VND' ? number / USER_CURRENCY.new_currecry_rate : number;

    //   let number_formatted = new Intl.NumberFormat('vi-VN', {
    //     style: 'currency',
    //     currency: USER_CURRENCY.currency_code,
    //     maximumFractionDigits: USER_CURRENCY.currency_code === 'VND' ? 2 : 6,
    //   }).format(number);

    //   if (USER_CURRENCY.currency_code !== DEFAULT_CURRENCY.currency_code) {
    //     return '≈ ' + number_formatted
    //   }

    //   return number_formatted;
    // }

    window.$formatCurrency = function(number, showCurrencySymbol = true, numberDecimal = "", decimalPoint = "", separator = "") {
      const config = window.USER_CURRENCY || {
        currency_code: "THB",
        currency_symbol: "฿",
        currency_decimal: 2,
        new_currecry_rate: 700,
        currency_thousand_separator: "dot",
        currency_decimal_separator: "comma",
        currency_position: "left"
      };

      const DEFAULT_CURRENCY = {
        currency_code: "VND"
      };

      let prefix = '';
      let decimal = 2;

      // Chuyển đổi number thành số nếu nó là chuỗi
      number = typeof number === 'string' ? parseFloat(number) : number;

      if (isNaN(number)) {
        console.error('Invalid number input');
        return 'Invalid input';
      }

      if (config.currency_code !== DEFAULT_CURRENCY.currency_code) {
        number = number / config.new_currecry_rate;
        prefix = '≈ ';
      }

      if (numberDecimal === "") {
        decimal = config.currency_decimal || 2;
      } else {
        decimal = parseInt(numberDecimal, 10);
      }

      if (decimalPoint === "") {
        decimalPoint = config.currency_decimal_separator || 'comma';
      }

      if (separator === "") {
        separator = config.currency_thousand_separator || 'space';
      }

      switch (decimalPoint) {
        case 'dot':
          decimalPoint = '.';
          break;
        case 'comma':
          decimalPoint = ',';
          break;
        default:
          decimalPoint = ".";
          break;
      }

      switch (separator) {
        case 'dot':
          separator = '.';
          break;
        case 'comma':
          separator = ',';
          break;
        case 'space':
          separator = ' ';
          break;
        default:
          separator = ',';
          break;
      }

      let formattedNumber = number.toFixed(decimal);
      let parts = formattedNumber.split('.');
      parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, separator);
      formattedNumber = parts.join(decimalPoint);

      if (showCurrencySymbol) {
        const symbol = config.currency_symbol || '';
        const currencyPosition = config.currency_position || 'left';

        if (currencyPosition === 'left') {
          return prefix + symbol + formattedNumber;
        } else {
          return prefix + formattedNumber + ' ' + symbol;
        }
      }

      return prefix + formattedNumber;
    };
  </script>

</head>

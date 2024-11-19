<head>

  <!-- Meta Data -->
  <meta charset="UTF-8">
  <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title') - Admin Control Panel v3</title>
  <meta name="Description" content="Admin Control Panel">
  <meta name="Author" content="quocbaodev">
  <meta name="keywords" content="Admin Control Panel">

  <!-- Favicon -->
  <link rel="icon" href="/_assets/images/brand-logos/favicon.ico" type="image/x-icon">

  <!-- Choices JS -->
  <script src="/_assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>

  <!-- Main Theme Js -->
  <script src="/_assets/js/main.js"></script>

  <!-- Bootstrap Css -->
  <link id="style" href="/_assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Style Css -->
  <link href="/_assets/css/styles.min.css" rel="stylesheet">

  <!-- Icons Css -->
  <link href="/_assets/css/icons.css" rel="stylesheet">

  <!-- Node Waves Css -->
  <link href="/_assets/libs/node-waves/waves.min.css" rel="stylesheet">

  <!-- Simplebar Css -->
  <link href="/_assets/libs/simplebar/simplebar.min.css" rel="stylesheet">

  <!-- Color Picker Css -->
  <link rel="stylesheet" href="/_assets/libs/flatpickr/flatpickr.min.css">
  <link rel="stylesheet" href="/_assets/libs/@simonwep/pickr/themes/nano.min.css">

  <!-- Choices Css -->
  <link rel="stylesheet" href="/_assets/libs/choices.js/public/assets/styles/choices.min.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800&display=swap" rel="stylesheet">

  <!-- Extra Plugin -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.27/sweetalert2.min.css">

  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">

  <!-- CoreJS -->
  <script>
    window.webData = @json([
        'csrfToken' => csrf_token(),
    ]);
    window.userData = @json(auth()->user());
  </script>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <script>
    window.LANG = @json(getLangJson() ?? [])

    window.__t = function(key) {
      if (window.LANG[key] === undefined) {
        // console.log(key);
      }
      return window.LANG[key] || key;
    }

    window.__defaultLang = '{{ currentLang() }}';

    window.$formatCurrency = function(number, currency = '{{ cur_setting('currency_code', 'VND') }}', maxinum = 6) {
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: currency,
        maximumFractionDigits: maxinum,
      }).format(number);
    }
  </script>
  <!-- extra style -->
  <style>
    * {
      font-family: 'Archivo', sans-serif;
    }
  </style>
  @yield('css')
  @yield('styles')

</head>

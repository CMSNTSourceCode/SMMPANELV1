@php use App\Helpers\Helper; @endphp
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="/assets/libs/@popperjs/core/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Defaultmenu JS -->
<script src="/assets/js/defaultmenu.min.js"></script>

<!-- Node Waves JS-->
<script src="/assets/libs/node-waves/waves.min.js"></script>

<!-- Sticky JS -->
<script src="/assets/js/sticky.js"></script>

<!-- Simplebar JS -->
<script src="/assets/libs/simplebar/simplebar.min.js"></script>
<script src="/assets/js/simplebar.js"></script>

<!-- Color Picker JS -->
<script src="/assets/libs/@simonwep/pickr/pickr.es5.min.js"></script>

<!-- Apex Charts JS -->
<script src="/assets/libs/apexcharts/apexcharts.min.js"></script>

{{-- <script src="/assets/js/index.js"></script> --}}

<!-- Custom-Switcher JS -->
<script src="/assets/js/custom-switcher.min.js"></script>

<!-- Custom JS -->
<script src="/assets/js/custom.js"></script>

<!-- extra js-->
<script src="https://unpkg.com/clipboard@2/dist/clipboard.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.27/sweetalert2.min.js"></script>

<!-- Datatables Cdn -->
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/autofill/2.6.0/js/dataTables.autoFill.min.js"></script>
<script src="https://cdn.datatables.net/autofill/2.6.0/js/autoFill.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

@vite(['resources/js/app.js', 'resources/js/functions.js'])

<script>
  $(document).ready(function() {
    // basic datatable
    $('.datatable').DataTable({
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      lengthChange: false,
      // searching: false,
      // paging: false,
      // info: false,
      // ordering: false,
      response: false,
      order: [
        [0, 'desc']
      ],
    })
  })

  const setCurrency = id => {
    $showLoading()

    axios.post('{{ route('account.profile.currency-update') }}', {
      id: id
    }).then(({
      data: result
    }) => {
      Swal.fire('{{ __t('Thành công') }}', result.message, 'success').then(() => {
        window.location.reload()
      })
    }).catch(error => {
      Swal.fire('{{ __t('Thất bại') }}', $catchMessage(error), 'error')
    })
  }

  window.fetchUser = async function() {
    if (window.userData?.access_token === undefined) {
      return;
    }

    try {
      const {
        data: result
      } = await axios.get('/api/user')

      // update class .user-balance
      $('.user-balance').text(result.balance_formatted)

      return result
    } catch (error) {
      Swal.fire('{{ __t('Thất bại') }}', $catchMessage(error), 'error')
    }
  }
</script>

@yield('scripts')
@stack('scripts')

{!! Helper::getNotice('footer_script') !!}

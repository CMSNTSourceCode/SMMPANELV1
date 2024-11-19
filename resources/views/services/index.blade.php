@php use App\Helpers\Helper; @endphp
@extends('layouts.app')
@section('title', $pageTitle)
@section('css')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

  <style>
    #form-buy .server__name:hover {
      color: #10ac84;
      transform: scale(1.05);
      margin-left: 5px;
    }

    #form-buy label {
      font-weight: bold;
    }

    #form-buy .total_price,
    .total_price_usd {
      font-weight: bold;
      font-size: 2rem;
    }

    #form-buy .panel-descr {
      background-color: #FFE0B5;
      color: #000;
      padding: 10px;
      border-radius: 8px;
    }

    .price_box {
      border: 1px solid #f1f1f1;
      padding: 10px;
      border-radius: 8px;
      background-color: transparent;
    }

    .price_box .total_price {
      background-color: black;
      padding: 0 10px 0 10px;
      border-radius: 5px;
      color: yellow;
    }

    .new-feeds {
      max-height: 483px;
      overflow-y: auto;
    }
  </style>
@endsection
@section('content')
  @php
    $userDiscount = Helper::getDiscountByRank(auth()->user()->rank ?? 'bronze');
  @endphp
  <div class="alert alert-primary">
    {!! Helper::getNotice('page_new_order') !!}
  </div>
  <div class="row">
    <div class="col-12 col-lg-8 col-md-8 col-sm-12">
      <div class="card custom-card">
        <div class="card-header">
          <h3 class="card-title">{{ __t('Tạo đơn hàng') }}</h3>
        </div>
        <div class="card-body">
          <div class="new__order--form">
            <form action="/api/orders/store" method="POST" id="form-buy">
              <div class="mb-3">
                <select class="js-example-placeholder-single form-control" id="search" type="search"></select>
              </div>
              <div class="mb-3">
                <label for="category_id" class="form-label">{{ __t('Chuyên mục') }}</label>
                <select class="form-select" id="category_id" name="category_id" type="search"></select>
              </div>
              <div class="mb-3">
                <label for="service_id" class="form-label">{{ __t('Dịch vụ') }}</label>
                <select class="form-select" id="service_id" name="service_id" type="search"></select>
              </div>
              <div id="descr" class="mb-3" style="display: none"></div>
              <div class="mb-3">
                <label for="object_id" class="form-label">{{ __t('Link') }}</label>
                <input type="text" class="form-control" id="object_id" name="object_id" placeholder="{{ __t('Nhập link') }}">
              </div>
              <div class="mb-3 group_quantity">
                <label for="quantity" class="form-label">{{ __t('Số lượng') }}</label>
                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="{{ __t('Nhập số lượng') }}">
                <small class="fw-bold mt-2">Min: <span class="text-danger" id="min_buy">0</span> - Max: <span class="text-primary" id="max_buy">0</span></small>
              </div>
              <div class="mb-3 group_reaction" style="display: none"></div>
              <div class="mb-3 group_comments" style="display: none"></div>
              <div class="mb-3 group_fb_viplike" style="display: none"></div>
              <div class="mb-3 group_fb_eyeslive" style="display: none"></div>
              <div class="mb-3">
                <label for="average_time" class="form-label">{{ __t('Average time') }} <a href="javascript:void(0)" data-bs-toggle="tooltip"
                    data-bs-title="{{ __t('Thời gian trung bình dựa trên 10 đơn hàng hoàn thành với số lượng lớn hơn 1000.') }}"><i class="fe fe-help-circle"></i></a></label>
                <input type="text" class="form-control" id="average_time" name="average_time" disabled>
              </div>
              <div class="mb-3">
                <div class="text-center price_box">
                  <div class="price_box--bg">
                    <span class="total_price">0</span>
                  </div>
                  @if ($userDiscount > 0)
                    <div>{!! __t('Bạn được giảm :number%', ['number' => '<span class="text-danger">' . $userDiscount . '</span>']) !!}</div>
                  @endif
                  <div class="text-danger fw-bold mt-1">{{ __t('Tổng tiền thanh toán') }}</div>
                </div>
              </div>
              <div class="mb-3">
                <button class="btn btn-primary w-100">{{ __t('Tạo đơn hàng') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-4 col-md-4 col-sm-12">
      <div class="card overflow-hidden border-0 p-0 text-nowrap">
        <div class="min-vh-25 p-4" style="background: linear-gradient(to right, #4361ee, #160f6b);">
          <div class="mb-4 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center rounded-pill bg-opacity-50 bg-dark p-1 text-white fw-semibold pe-3">
              <img class="rounded-circle border-white me-2" src="{{ setting('avatar_user', '/assets/images/faces/9.jpg') }}" alt="image" style="height: 2rem; width: 2rem; object-cover;">
              {{ auth()->user()->username ?? 'Chưa đăng nhập' }}
            </div>
            <a href="{{ route('account.deposits.transfer') }}" class="btn btn-dark d-flex align-items-center justify-content-center rounded-circle p-0" style="height: 2.25rem; width: 2.25rem;">
              <svg class="m-auto" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round" style="height: 1.5rem; width: 1.5rem;">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
              </svg>
            </a>
          </div>
          <div class="d-flex align-items-center justify-content-between ">
            <p class="h5 mb-0 text-white">{{ __t('Số dư') }}</p>
            <h5 class="h4 mb-0 ms-auto text-white"><span><span class="user-balance">{{ formatCurrency(auth()->user()->balance ?? 0) }}</span></span></h5>
          </div>
        </div>
        <div class="row g-2 px-4" style="margin-top: -20px">
          <div class="col-6">
            <div class="rounded bg-white p-3 shadow-sm dark-bg">
              <span class="d-flex align-items-center justify-content-between text-dark">
                {{ __t('Tổng nạp') }}
                <svg class="text-success" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="height: 1rem; width: 1rem;">
                  <path d="M19 15L12 9L5 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </span>
              <div class="btn w-100 bg-light text-dark fw-semibold border-0 py-1 mt-2">{{ formatCurrency(auth()->user()->total_deposit ?? 0) }}</div>
            </div>
          </div>
          <div class="col-6">
            <div class="rounded bg-white p-3 shadow-sm dark-bg">
              <span class="d-flex align-items-center justify-content-between text-dark">
                {{ __t('Tổng tiêu') }}
                <svg class="text-danger" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="height: 1rem; width: 1rem;">
                  <path d="M19 9L12 15L5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </span>
              <div class="btn w-100 bg-light text-dark fw-semibold border-0 py-1 mt-2">{{ formatCurrency((auth()->user()->total_deposit ?? 0) - (auth()->user()->balance ?? 0)) }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="text-center">
        <h3>{{ __t('Thông báo mới') }}</h3>
      </div>
      <div class="new-feeds">
        @foreach ($posts as $post)
          <div class="card">
            <div class="card-body">
              <h5>----- {{ $post->created_at }}</h5>
              <hr />
              <div class="card-content">
                {!! $post->content !!}
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- Modal -->
  @if ($msgModal = Helper::getNotice('modal_dashboard'))
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __t('Thông báo mới') }}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            {!! $msgModal !!}
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __t('Đóng') }}</button>
          </div>
        </div>
      </div>
    </div>
  @endif
@endsection
@section('scripts')
  <script>
    $(document).ready(function() {
      // Kiểm tra nếu đã hiển thị modal trong vòng 30 phút trước đó
      var lastShownTime = localStorage.getItem('lastShownTime');
      var currentTime = new Date().getTime();
      var timeDiff = currentTime - lastShownTime;

      if (lastShownTime === null || timeDiff > 30 * 60 * 1000) {
        // Nếu chưa hiển thị trong vòng 30 phút, hiển thị modal
        $('#exampleModal').modal('show');

        // Lưu thời điểm hiển thị modal
        localStorage.setItem('lastShownTime', currentTime);
      }
    });
  </script>

  <script>
    window._discount = {{ $userDiscount }};
    window._services = @json($services);
    window._categories = @json($categories);
    window._api_get_form = "/api/tools/get-form";
    window._api_orders_store = "/api/orders/store";
    window.SUM_PRICE_FNC = null
  </script>
  <!-- Select2 Cdn -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script>
    const formatNumber = (num) => {
      return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }

    $(document).ready(function() {
      $(".js-example-placeholder-single").select2({
        placeholder: "{{ __t('Tìm kiếm dịch vụ') }}",
        allowClear: true,
        dir: "ltr",
        data: [{
          id: '',
          text: ''
        }, ...window._services.map((service) => {
          return {
            id: service.id,
            text: service.display_name
          }
        })],
        selectionCssClass: 'select2-selection--single',
      });

      $("#category_id").select2({
        placeholder: "{{ __t('Chọn chuyên mục') }}",
        allowClear: true,
        dir: "ltr",
        data: window._categories.map((category) => {
          return {
            id: category.id,
            text: `ID ${category.id} - ${category.name}`,
            imgSrc: category.image // Giả sử bạn có đường dẫn ảnh trong thuộc tính `image` của đối tượng `category`
          }
        }),
        templateResult: formatCategoryOption,
        templateSelection: formatCategorySelection
      });

      function formatCategoryOption(option) {
        if (!option.id) {
          return option.text;
        }

        var imgSrc = option.imgSrc;
        if (imgSrc) {
          var $option = $(
            '<span><img src="' + imgSrc + '" style="width: 30px; margin-right: 2px; margin-bottom: 2px; border-radius: 0px;" /> ' + option.text + '</span>'
          );
          return $option;
        }

        return option.text;
      }

      function formatCategorySelection(option) {
        if (!option.id) {
          return option.text;
        }

        var imgSrc = option.imgSrc;
        if (imgSrc) {
          var $selection = $(
            '<span><img src="' + imgSrc + '" style="width: 30px; margin-right: 2px; margin-bottom: 2px; border-radius: 0px;" /> ' + option.text + '</span>'
          );
          return $selection;
        }

        return option.text;
      }

      $("#service_id").select2({
        placeholder: "{{ __t('Chọn dịch vụ') }}",
        allowClear: true,
        dir: "ltr",
      });

      $("#search").change(function(e) {
        const service = window._services.find((service) => service.id == e.target.value);

        if (service === undefined) {
          return;
        }

        $("#category_id").val(service.category_id).trigger('change');
      })

      $("#category_id").change(function(e) {
        const services = window._services.filter((service) => service.category_id == e.target.value);

        $("#service_id").html(services.map((service) => {
          return `<option value="${service.id}">${service.display_name}</option>`
        }));

        const searchId = $("#search").val()

        if (searchId === '') {
          $("#service_id").val(services[0]?.id || '').trigger('change');
        } else {
          $("#service_id").val(searchId).trigger('change');
        }
      })

      $("#service_id").change(function(e) {
        let service = window._services.find((service) => service.id == e.target.value);

        if (service === undefined) {
          return;
        }

        const searchId = $("#search").val()
        if (searchId !== '') {
          $("#search").val('').trigger('change')
        }

        if ($("#quantity").val() <= service.min_buy) {
          $("#quantity").val(service.min_buy).trigger('input')
        } else {
          $("#quantity").trigger('input')
        }


        $("#descr").html('').hide();
        if (service.descr) {
          $("#descr").html(`<label for="descr" class="form-label">{{ __t('Mô tả dịch vụ') }}</label><div class="panel-descr p-2" style="border-radius: 8px">${service.descr}</div>`).show();
        }
        $("#min_buy").text(formatNumber(service.min_buy));
        $("#max_buy").text(formatNumber(service.max_buy));
        $("#average_time").val(service.average_time);

        // update field custom comments
        if (service.type === 'custom_comments' || service.type === 'custom_comment') {
          axios
            .get(_api_get_form + '/comments')
            .then(({
              data: res
            }) => {
              $('.group_comments').show().html(res)
              sumPrice()
            })
            .finally(() => {
              sumPrice()
            })
        } else {
          $('.group_comments').hide().html('')

          $("#quantity").attr("readonly", false);
        }
      })

      const getService = () => {
        const serviceId = $('#service_id').val()

        return window._services.find((service) => service.id == serviceId)
      }

      const sumPrice = () => {
        const service = getService()

        if (service === undefined) {
          return 0
        }

        const quantity = parseInt($('#quantity').val()),
          price_per = parseFloat(service.price_per)

        if (isNaN(quantity) || isNaN(price_per) || quantity <= 0) {
          return 0
        }

        let total_payment = parseFloat(quantity * price_per)

        if (service.form_type === 'fb_viplike') {
          const num_post = parseInt($('#num_post').val()),
            duration = parseInt($('#duration').val())

          total_payment *= num_post * duration
        } else if (service.form_type === 'fb_eyeslive') {
          const duration = parseInt($('#duration').val())

          total_payment *= duration
        }

        if (service.type === 'custom_comments' || service.type === 'custom_comment') {
          const comment_count = parseInt($('.comment_count').text())

          total_payment = comment_count * price_per
        }

        if (window._discount > 0) {
          // total_payment = total_payment - (total_payment * window._discount / 100)
        }

        $('.total_price').html($formatCurrency(total_payment))

        return total_payment
      }

      SUM_PRICE_FNC = sumPrice

      $('#quantity').on('input', sumPrice)
      $('#num_post').on('input', sumPrice)
      $('#duration').on('input', sumPrice)


      //
      $('#form-buy').submit(async (e) => {
        e.preventDefault()

        const confirm = await Swal.fire({
          title: '{{ __t('Bạn chắc chứ?') }}',
          text: '{{ __t('Bạn đã kiểm tra kỹ thông tin chưa?') }}',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: '{{ __t('Đồng ý') }}',
          cancelButtonText: '{{ __t('Hủy') }}',
        })

        if (confirm.isConfirmed === false) return

        const payload = $formDataToPayload(new FormData(e.target)),
          button = e.target.querySelector('button[type=submit]'),
          service = getService()

        if (payload.object_id === '') {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ __t('Trường Link không được để trống!') }}',
          })
          return
        }
        if (service === undefined) {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ __t('Dịch vụ không tồn tại!') }}',
          })
          return
        }
        // if (payload.quantity < service.min_buy || payload.quantity > service.max_buy) {
        //   Swal.fire({
        //     icon: 'error',
        //     title: 'Oops...',
        //     text: '{{ __t('Số lượng mua phải từ ') }} ' + service.min_buy + ' {{ __t(' đến ') }}' + service.max_buy,
        //   })
        //   return
        // }

        if (service.min_buy > 0 && payload.quantity < parseInt(service.min_buy)) {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ __t('Số lượng mua phải từ phải nhỏ hơn ') }}' + service.min_buy,
          })
          return
        }

        if (service.max_buy > 0 && payload.quantity > parseInt(service.max_buy)) {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ __t('Số lượng mua phải từ phải nhỏ hơn ') }}' + service.max_buy,
          })
          return
        }


        return handleSubmit(payload, button)
      })

      const handleSubmit = async (payload, button) => {
        Swal.fire({
          icon: 'warning',
          title: '{{ __t('Đang xử lý...') }}',
          html: '{{ __t('Vui lòng không tắt trang này để tránh mất tiền!') }}',
          allowOutsideClick: false,
          allowEscapeKey: false,
          didOpen: () => {
            Swal.showLoading()
          },
        })

        $setLoading(button)

        try {
          const {
            data: result
          } = await axios.post(_api_orders_store, payload)

          // Xem đơn hàng, mua tiếp
          const {
            isConfirmed
          } = await Swal.fire({
            title: 'Thành công!',
            text: result.message,
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: 'Xem đơn hàng',
            cancelButtonText: 'Mua tiếp',
          })

          if (isConfirmed) {
            window.location.href = '{{ route('account.orders') }}?order_id=' + result.data.order_detail.id
          } else {
            // window.location.reload()
            $("#object_id").val('')
            $("#quantity").val(0)

          }
        } catch (error) {
          if (error?.response?.status === 401) {
            return Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Bạn cần đăng nhập để mua dịch vụ!',
              showCancelButton: true,
              confirmButtonText: 'Đăng nhập',
              cancelButtonText: 'Hủy',
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = '/login'
              }
            })
          }
          Swal.fire('Oops...', $catchMessage(error), 'error')
        } finally {
          fetchUser()
          $removeLoading(button)

        }
      }

      setTimeout(() => {
        // trigger change
        $("#category_id").val(window._categories[0]?.id || '').trigger('change');
      }, 500)
    })
  </script>
@endsection

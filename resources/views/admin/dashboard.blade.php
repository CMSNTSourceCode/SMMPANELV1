@php use App\Helpers\Helper; @endphp
@extends('admin.layouts.master')
@section('title', 'Admin: Dashboard')
@section('css')
  <link rel="stylesheet" href="/_assets/libs/jsvectormap/css/jsvectormap.min.css">

  <link rel="stylesheet" href="/_assets/libs/swiper/swiper-bundle.min.css">
@endsection

@section('content')
  <style>
    .card-stats h3 {
      color: #9A3B3B;
      font-size: 36px;
    }

    .card-stats h6 {
      color: #9A3B3B;
      font-size: 18px;
    }
  </style>
  <section>
    <div class="mb-3 alert alert-secondary alert-dismissible fade show custom-alert-icon shadow-sm" role="alert">
      <h5>SMMPanel-V1 Version: <strong style="color:blue;">{{ appVersion() }}</strong></h5>
      <small>Hệ thống sẽ tự động cập nhật phiên bản mới khi bạn truy cập trang này</small>
      <br><br>
      <h6>Giấy phép kích hoạt website của bạn là: <strong style="color:red;" id="copyKey">{{ env('PRJ_CLIENT_KEY') }}</strong>
        <button class="btn btn-info btn-sm shadow-sm btn-wave copy waves-effect waves-light" data-clipboard-target="#copyKey" onclick="copy()">Copy</button>
      </h6>
      <small>Vui lòng bảo mật giấy phép của bạn, chỉ cung cấp cho <strong>CMSNT</strong> khi cần hỗ trợ.</small>
      <br>
      <hr>
      <p>Cộng đồng Suppliers của chúng tôi:</p>
      <ul>
        @if (env('PRJ_DEMO_MODE', true) === true)
          <li>Nhóm Zalo: <strong>chỉ áp dụng khi mua website chính hãng tại CMSNT</strong></li>
          <li>Nhóm Zalo: <strong>chỉ áp dụng khi mua website chính hãng tại CMSNT</strong></li>
          <li>Nhóm Telegram: <strong>chỉ áp dụng khi mua website chính hãng tại CMSNT</strong></li>
        @else
          <li>Nhóm Zalo: <strong>chỉ áp dụng khi mua website chính hãng tại <a href="https://zalo.me/g/idapcx933" target="_blank">[CMSNT] Changelog - Notification</a></strong></li>
          <li>Nhóm Zalo: <strong>chỉ áp dụng khi mua website chính hãng tại <a href="https://zalo.me/g/eululb377" target="_blank">[CMSNT] Trao đổi API - Suppliers</a></strong></li>
          <li>Nhóm Telegram: <strong>chỉ áp dụng khi mua website chính hãng tại <a href="https://t.me/+LVON7y2BKWU3ZDY9" target="_blank">[CMSNT] Notification - API - Suppliers</a></strong></li>
        @endif
      </ul>
      <p class="text-danger">Những thay đổi trong phiên bản này:</p>
      <ul>
        @foreach (get_change_logs() as $changed)
          <li class="fw-bold text-blue">{{ $changed }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
    </div>
    <div class="text-center mb-3">
      @if (abs(strtotime(Helper::getConfig('time_cron_order')) - time()) > 60 * 5)
        <div class="text-center fw-bold fs-6 alert alert-danger">
          {{ __t('Hệ thống cron đang gặp lỗi hoặc bạn chưa cron; nếu chưa hãy cron 2 link sau:') }}
          <div class="text-center">
            <div>{{ url('/schedule/orders/place-order') }} - 2 phút</div>
            <div>{{ url('/schedule/orders/update-order') }} - 1 phút</div>
          </div>
        </div>
      @else
        <div class="alert alert-danger">
          Cron đơn hàng gần nhất: {{ Helper::getTimeAgo(Helper::getConfig('time_cron_order')) }}
        </div>
      @endif

    </div>
    <h5>{{ __t('Thống Kê Thành Viên') }}</h5>
    <div class="row">
      @foreach ($stats['users'] as $key => $value)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <div class="card custom-card border-top-card border-top-{{ $stats['t_users'][$key]['color'] ?? 'primary' }} rounded-0">
            <div class="card-body">
              <div class="text-center">
                <p class="fs-14 fw-semibold mb-2">{{ $stats['t_users'][$key]['label'] ?? $key }}</p>
                <div class="d-flex align-items-center justify-content-center flex-wrap">
                  @if (isset($stats['t_users'][$key]['format']) && $stats['t_users'][$key]['format'] === 'currency')
                    <h4 class="mb-0 fw-semibold">{{ formatCurrency($value) }}</h4>
                  @else
                    <h4 class="mb-0 fw-semibold">{{ number_format($value) }}</h4>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
    <h5>{{ __t('Thống Kê Đơn Hàng') }}</h5>
    <div class="row">
      @foreach ($stats['orders'] as $key => $value)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <div class="card custom-card border-top-card border-top-{{ $stats['t_orders'][$key]['color'] ?? 'primary' }} rounded-0">
            <div class="card-body">
              <div class="text-center">
                <p class="fs-14 fw-semibold mb-2">{{ $stats['t_orders'][$key]['label'] ?? $key }}</p>
                <div class="d-flex align-items-center justify-content-center flex-wrap">
                  @if (isset($stats['t_orders'][$key]['format']) && $stats['t_orders'][$key]['format'] === 'currency')
                    <h4 class="mb-0 fw-semibold">{{ formatCurrency($value) }}</h4>
                  @else
                    <h4 class="mb-0 fw-semibold">{{ number_format($value) }}</h4>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
    <h5>{{ __t('Thống Kê Lợi Nhuận') }}</h5>
    <div class="row">
      @foreach ($stats['revenue_profit'] as $key => $value)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <div class="card custom-card border-top-card border-top-{{ $stats['t_revenue_profit'][$key]['color'] ?? 'primary' }} rounded-0">
            <div class="card-body">
              <div class="text-center">
                <p class="fs-14 fw-semibold mb-2">{{ $stats['t_revenue_profit'][$key]['label'] ?? $key }}</p>
                <div class="d-flex align-items-center justify-content-center flex-wrap">
                  @if (isset($stats['t_revenue_profit'][$key]['format']) && $stats['t_revenue_profit'][$key]['format'] === 'currency')
                    <h4 class="mb-0 fw-semibold">{{ formatCurrency($value) }}</h4>
                  @else
                    <h4 class="mb-0 fw-semibold">{{ number_format($value) }}</h4>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
    <hr />
    <div class="row">
      <div class="col-12 col-md-12">
        <div class="card custom-card">
          <div class="card-header">
            <div class="card-title">{{ __t('PHÂN TÍCH ĐƠN HÀNG') }}</div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div id="orders-chart"></div>
              </div>
              <div class="col-md-6">
                <div id="pie-basic"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-12">
        <div class="card custom-card">
          <div class="card-header">
            <div class="card-title">{{ __t('PHÂN TÍCH DÒNG TIỀN') }}</div>
          </div>
          <div class="card-body">
            <div id="column-basic1"></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ __t('Khảo sát cập nhật') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body
        ">
          <iframe src="https://forms.gle/6vreHJgqFVd1WLn29" frameborder="0" style="width: 100%; height: 500px;"></iframe>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __t('Đóng') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <!-- JSVector Maps JS -->
  <script src="/_assets/libs/jsvectormap/js/jsvectormap.min.js"></script>

  <!-- JSVector Maps MapsJS -->
  <script src="/_assets/libs/jsvectormap/maps/world-merc.js"></script>

  <!-- Apex Charts JS -->
  <script src="/_assets/libs/apexcharts/apexcharts.min.js"></script>

  <!-- Chartjs Chart JS -->
  <script src="/_assets/libs/chart.js/chart.min.js"></script>

  <script>
    $(document).ready(() => {
      /* basic column chart */
      var options = {
        series: [{
          name: '{{ __t('Lợi nhuận') }}',
          data: @json($chartProfit)
        }, {
          name: '{{ __t('Doanh thu') }}',
          data: @json($chartRevenue)
        }],
        chart: {
          type: 'bar',
          height: 320
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '80%',
            endingShape: 'rounded'
          },
        },
        grid: {
          borderColor: '#f2f5f7',
        },
        dataLabels: {
          enabled: false
        },
        colors: ["#845adf", "#23b7e5"],
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: @json($chartCategories),
          labels: {
            show: true,
            style: {
              colors: "#8c9097",
              fontSize: '11px',
              fontWeight: 600,
              cssClass: 'apexcharts-xaxis-label',
            },
          }
        },
        yaxis: {
          title: {
            text: 'VNĐ',
            style: {
              color: "#8c9097",
            },
          },
          labels: {
            show: true,
            style: {
              colors: "#8c9097",
              fontSize: '11px',
              fontWeight: 600,
              cssClass: 'apexcharts-xaxis-label',
            },
            formatter: function(val) {
              return (val)
            }
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function(val) {
              return (val)
            }
          }
        }
      };
      var chart = new ApexCharts(document.querySelector("#column-basic"), options);
      chart.render();

      var options = {
        series: [{
          name: '{{ __t('Tiền nạp') }}',
          data: @json($chartDeposit)
        }, {
          name: '{{ __t('Tiền tiêu') }}',
          data: @json($chartSpent)
        }],
        chart: {
          type: 'bar',
          height: 320
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '80%',
            endingShape: 'rounded'
          },
        },
        grid: {
          borderColor: '#f2f5f7',
        },
        dataLabels: {
          enabled: false
        },
        colors: ["#845adf", "#23b7e5"],
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: @json($chartCategories),
          labels: {
            show: true,
            style: {
              colors: "#8c9097",
              fontSize: '11px',
              fontWeight: 600,
              cssClass: 'apexcharts-xaxis-label',
            },
          }
        },
        yaxis: {
          title: {
            text: '{{ currentLang() === 'vn' ? 'VNĐ' : '$ Dollar' }}',
            style: {
              color: "#8c9097",
            },
          },
          labels: {
            show: true,
            style: {
              colors: "#8c9097",
              fontSize: '11px',
              fontWeight: 600,
              cssClass: 'apexcharts-xaxis-label',
            },
            formatter: function(val) {
              return (val)
            }
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function(val) {
              return (val)
            }
          }
        }
      };
      var chart = new ApexCharts(document.querySelector("#column-basic1"), options);
      chart.render();
    })
  </script>

  <script>
    $(document).ready(function() {
      /* basic line chart */
      var options = {
        series: @json($chartOrders),
        chart: {
          height: 320,
          type: 'line',
          zoom: {
            enabled: false
          }
        },
        colors: @json($chartOrdersColors),
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'straight',
          width: 3,
        },
        grid: {
          borderColor: '#f2f5f7',
        },
        title: {
          text: '{{ __t('Đơn hàng gần đây') }}',
          align: 'left',
          style: {
            fontSize: '13px',
            fontWeight: 'bold',
            color: '#8c9097'
          },
        },
        xaxis: {
          categories: @json($chartCategories),
          labels: {
            show: true,
            style: {
              colors: "#8c9097",
              fontSize: '11px',
              fontWeight: 600,
              cssClass: 'apexcharts-xaxis-label',
            },
          }
        },
        yaxis: {
          labels: {
            show: true,
            style: {
              colors: "#8c9097",
              fontSize: '11px',
              fontWeight: 600,
              cssClass: 'apexcharts-yaxis-label',
            },
          }
        }
      };
      var chart = new ApexCharts(document.querySelector("#orders-chart"), options);
      chart.render();

      /* basic pie chart */
      var options1 = {
        series: @json($chartPieData),
        chart: {
          height: 300,
          type: 'pie',
        },
        colors: @json($chartPieColors),
        labels: @json($chartPieLabels),
        legend: {
          position: "bottom"
        },
        dataLabels: {
          dropShadow: {
            enabled: false
          }
        },
      };
      var chart = new ApexCharts(document.querySelector("#pie-basic"), options1);
      chart.render();

    })
  </script>

  <script>
    $(document).ready(() => {

      const fixUpdate = () => {
        axios.get('/artisan/fix-update').then(r => {
          console.log(r.data);
        }).catch(e => {
          console.log(e);
        })
      }

      const callApi = async (force = 0) => {
        try {
          const {
            data: result
          } = await axios.get('/admin/update', {
            params: {
              run: force
            }
          });

          if (force === 0) return result.data?.can_update || false
          else return result
        } catch (error) {
          Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: $catchMessage(error),
          })
        }
      }

      const runUpdate = async () => {
        try {
          const canUpdate = await callApi(0)

          if (canUpdate) {
            $showLoading('Đang cập nhật, vui lòng đợi...')

            const result = await callApi(1)

            if (result.data?.version_code !== undefined) {
              return Swal.fire({
                icon: 'success',
                title: 'Đã cập nhật!',
                text: result.message || 'Cập nhật thành công!'
              }).then(() => {
                location.reload()
              })
            }
          }

          $hideLoading()

          console.log('Bạn đang dùng phiên bản mới nhất rồi keke')
        } catch (error) {
          Swal.fire({
            icon: 'error',
            title: 'Cập nhật thất bại!',
            text: $catchMessage(error),
          })
        }
      }

      runUpdate();
    })

    $(document).ready(function() {
      // Kiểm tra nếu đã hiển thị modal trong vòng 30 phút trước đó
      var lastShownTime = localStorage.getItem('lastShownTime1');
      var currentTime = new Date().getTime();
      var timeDiff = currentTime - lastShownTime;

      if (lastShownTime === null || timeDiff > 5 * 60 * 1000) {
        // Nếu chưa hiển thị trong vòng 30 phút, hiển thị modal
        $('#exampleModal').modal('show');

        // Lưu thời điểm hiển thị modal
        localStorage.setItem('lastShownTime1', currentTime);
      }
    })
  </script>
@endsection

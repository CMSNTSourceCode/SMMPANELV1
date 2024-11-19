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
      color: red;
    }

    #form-buy .panel-descr {
      background-color: #FFE0B5;
      color: #000;
      padding: 10px;
      border-radius: 8px;
    }
  </style>
@endsection
@section('content')
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
      <div class="row total-sales-card-section">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xl-4">
          <div class="card custom-card overflow-hidden">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h6 class="fw-normal fs-14">{{ __t('Số dư hiện tại') }}</h6>
                  <h3 class="mb-2 number-font fs-24">{{ formatCurrency(auth()->user()->balance) }}</h3>
                  <p class="text-muted mb-0">
                    <span class="text-primary">
                      <i class="ri-arrow-up-s-line bg-primary text-white rounded-circle fs-13 p-0 fw-semibold align-bottom"></i>
                      -%</span> {{ __t('vào tháng trước') }}
                  </p>
                </div>
                <div class="col col-auto mt-2">
                  <div class="counter-icon bg-primary-gradient box-shadow-primary rounded-circle ms-auto mb-0">
                    <i class="fe fe-dollar-sign mb-5 "></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xl-4">
          <div class="card custom-card overflow-hidden">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h6 class="fw-normal fs-14">{{ __t('Tổng tiền nạp tháng') }}</h6>
                  <h3 class="mb-2 number-font fs-24">{{ formatCurrency($totalDepositInMonth) }}</h3>
                  <p class="text-muted mb-0">
                    <span class="@if ($percentDeposit < 0) text-success @else text-danger @endif">
                      <i class="@if ($percentDeposit < 0) ri-arrow-up-s-line bg-success @else ri-arrow-down-s-line bg-danger @endif text-white rounded-circle fs-13 p-0 fw-semibold align-bottom"></i>
                      {{ round($percentDeposit, 2) }}%</span> {{ __t('vào tháng trước') }}
                  </p>
                </div>
                <div class="col col-auto mt-2">
                  <div class="counter-icon bg-danger-gradient box-shadow-danger rounded-circle  ms-auto mb-0">
                    <i class="fe fe-trending-up mb-5"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xl-4">
          <div class="card custom-card overflow-hidden">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h6 class="fw-normal fs-14">{{ __t('Tổng đơn của bạn') }}</h6>
                  <h3 class="mb-2 number-font fs-24">{{ number_format($totalOrderInMonth) }}</h3>
                  <p class="text-muted mb-0">
                    <span class="@if ($percentDeposit < 0) text-danger @else text-success @endif">
                      <i class="@if ($percentDeposit < 0) ri-arrow-up-s-line bg-danger @else ri-arrow-down-s-line bg-success @endif text-white rounded-circle fs-13 p-0 fw-semibold align-bottom"></i>
                      {{ round($percentOrder, 2) }}%</span> {{ __t('vào tháng trước') }}
                  </p>
                </div>
                <div class="col col-auto mt-2">
                  <div class="counter-icon bg-secondary-gradient box-shadow-secondary rounded-circle ms-auto mb-0">
                    <i class="fe fe-list  mb-5 "></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="card custom-card">
        <div class="card-header">
          <div class="card-title">{{ __t('Thống kê đơn hàng') }}</div>
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

      <div class="col-md-12">
        <div class="card custom-card">
          <div class="card-header">
            <h3 class="card-title">{{ __t('Dữ liệu đơn hàng') }}</h3>
          </div>
          <div class="card-body">
            <div id="column-basic" width="1112" class="overflow-hidden"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="card custom-card">
        <div class="card-header">
          <h3 class="card-title">{{ __t('Phân tích dòng tiền') }}</h3>
        </div>
        <div class="card-body">
          <div id="totaltransactions" width="1112" class="overflow-hidden"></div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
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

    //
    /* basic column chart */

    var options = {
      series: [{
        name: '{{ __t('Đơn hàng') }}',
        data: @json($chartCount)
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
      colors: ["#6259ca", "#fb6b25", "#f5b849"],
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
          text: '{{ __t('đơn') }}',
          style: {
            color: "#8c9097",
          }
        },
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
      fill: {
        opacity: 1
      },
      tooltip: {
        y: {
          formatter: function(val) {
            return val + " {{ __t('đơn') }}"
          }
        }
      }
    };
    var chart = new ApexCharts(document.querySelector("#column-basic"), options);
    chart.render();
  </script>

  <script>
    var options = {
      series: [{
        name: '{{ __t('Tiền nạp') }}',
        data: @json($chartDeposit)
      }, {
        name: '{{ __t('Tiền tiêu') }}',
        data: @json($chartSpent)
      }],
      chart: {
        toolbar: {
          show: false
        },
        type: 'line',
        height: 320,
        dropShadow: {
          enabled: true,
          opacity: 0.1,
        },
      },
      grid: {
        borderColor: '#f1f1f1',
        strokeDashArray: 3
      },

      labels: @json($chartCategories),
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: "smooth",
        width: [3, 3, 0],
        dashArray: [0, 4],
        lineCap: "round"
      },
      legend: {
        show: true,
        position: 'top',
      },
      xaxis: {
        axisBorder: {
          color: '#e9e9e9',
        },
      },
      plotOptions: {
        bar: {
          columnWidth: "20%",
          borderRadius: 2
        }
      },
      grid: {
        show: true,
        padding: {
          right: 0,
          left: 0
        },
      },
      yaxis: {
        labels: {
          formatter: function(val) {
            return $formatCurrency(val)
          }
        }
      },
      tooltip: {
        y: {
          formatter: function(val) {
            return $formatCurrency(val)
          }
        }
      },

      colors: ["rgba(98, 89, 202, 1)", "rgba(249, 148, 51, 1)", 'rgba(119, 119, 142, 0.05)'],
    };
    document.querySelector("#totaltransactions").innerHTML = ""
    var chart2 = new ApexCharts(document.querySelector("#totaltransactions"), options);
    chart2.render();

    function totaltransactions() {
      chart2.updateOptions({
        colors: ["rgba(" + myVarVal + ", 1)", '#23b7e5'],
      })
    }
  </script>
@endsection

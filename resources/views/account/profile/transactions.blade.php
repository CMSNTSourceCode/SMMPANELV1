@extends('layouts.app')
@section('title', $pageTitle)
@section('content')
  <div class="card custom-card">
    <div class="card-header">
      <h3 class="card-title">{{ __t('Lịch sử giao dịch') }}</h3>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="datatable-custom1" class="table table-bordered text-nowrap" style="width:100%">
          <thead>
            <tr>
              <th>{{ __t('ID') }}</th>
              <th>{{ __t('Mã đơn') }}</th>
              <th>{{ __t('Số tiền') }}</th>
              <th>{{ __t('Số dư trước') }}</th>
              <th>{{ __t('Số dư sau') }}</th>
              <th>{{ __t('Thời gian') }}</th>
              <th>{{ __t('Cập nhật') }}</th>
              <th>{{ __t('Nội dung') }}</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="card custom-card">
    <div class="card-header">
      <h3 class="card-title">{{ __t('Phân tích dòng tiền') }}</h3>
    </div>
    <div class="card-body">
      <div id="totaltransactions" width="1112" class="overflow-hidden"></div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    $(document).ready(() => {
      'use strict'

      const $table = $('#datatable-custom1')

      const $tableOptions = {
        processing: true,
        serverSide: true,
        ajax: {
          url: '/api/users/transactions',
          type: 'GET',
          headers: {
            Authorization: `Bearer ${userData?.access_token}`,
          },
          data: (data) => {
            let payload = {}
            // default params

            // set params
            payload.page = data.start / data.length + 1
            payload.limit = data.length
            payload.search = data.search.value
            payload.sort_by = data.columns[data.order[0].column].data
            payload.sort_type = data.order[0].dir
            // return json
            return payload
          },
          beforeSend: function(xhr) {},
          complete: function(xhr) {},
          error: function(xhr) {
            console.log(xhr?.responseJSON)
          },
          dataFilter: function(data) {
            let json = JSON.parse(data)

            if (json.status === 200) {
              json.recordsTotal = json.data.meta.total
              json.recordsFiltered = json.data.meta.total
              json.data = json.data.data
              return JSON.stringify(json) // return JSON string
            } else {
              Swal.fire('Thất bại', json.message, 'error')

              return JSON.stringify({
                recordsTotal: 0,
                recordsFiltered: 0,
                data: [],
              })
            }
          },
        },
        columns: [{
          data: 'id'
        }, {
          data: 'code',
          visible: false,
          render: function(data, type, row) {
            return data
          }
        }, {
          data: 'amount',
          render: function(data) {
            return $formatCurrency(data)
          }
        }, {
          data: 'balance_before',
          render: function(data) {
            return $formatCurrency(data)
          }
        }, {
          data: 'balance_after',
          render: function(data) {
            return $formatCurrency(data)
          }
        }, {
          data: 'created_at',
          render: function(data) {
            return moment(data).format('DD/MM/YYYY HH:mm:ss')
          }
        }, {
          data: 'updated_at',
          render: function(data) {
            return moment(data).format('DD/MM/YYYY HH:mm:ss')
          }
        }, {
          data: 'content',
          render: function(data) {
            return $truncate(data, 120)
          }
        }],
        order: [
          [0, 'desc']
        ],
        lengthMenu: [
          [10, 20, 50, 100, 500, 1000, 5000],
          [10, 20, 50, 100, 500, 1000, 5000],
        ],
        pageLength: 10,
      }

      const $tableInstance = $table.DataTable($tableOptions)

      $tableInstance.on('draw.dt', function() {
        $('[data-bs-toggle="tooltip"]').tooltip()
      })
    });
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

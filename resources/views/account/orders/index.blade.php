@php use App\Helpers\Helper; @endphp
@extends('layouts.app')
@section('title', $pageTitle)
@section('css')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
@endsection
@section('content')
  <div class="card custom-card">
    <div class="card-header">
      <h3 class="card-title">{{ __t('Quản lý đơn hàng') }}</h3>
    </div>
    <div class="card-body">
      <div class="filter mb-4">
        <form action="{{ route('account.orders') }}" method="GET">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="service_id" class="form-label">{{ __t('Dịch vụ') }}</label>
                <select name="service_id" id="service_id" class="form-select">
                  <option value="">{{ __t('Chọn tất cả') }}</option>
                  @foreach ($services as $service)
                    <option value="{{ $service->id }}" {{ request()->get('service_id') == $service->id ? 'selected' : '' }}>{{ $service->display_name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <!-- Status -->
            <div class="col-md-4">
              <div class="form-group">
                <label for="select_status" class="form-label">{{ __t('Trạng thái') }}</label>
                <select name="status" id="select_status" class="form-select form-select-lg">
                  <option value="">{{ __t('Chọn tất cả') }}</option>
                  @foreach ($orderStatus as $key => $status)
                    <option value="{{ $key }}" {{ request()->get('status') == $key ? 'selected' : '' }}>{{ $status }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <!-- Submit -->
            <div class="col-md-2">
              <div class="form-group" style="margin-top: 26.2px;">
                <button type="button" class="btn btn-primary w-100" id="btn_reload">{{ __t('Lọc dữ liệu') }}</button>
              </div>
            </div>
          </div>
        </form>
      </div>

      <div class="table-responsive">
        <table id="datatable-custom1" class="table table-bordered text-nowrap" style="width:100%">
          <thead>
            <tr>
              <th>ID</th>
              <th>Date</th>
              <th>Link</th>
              <th>Charge</th>
              <th>Start count</th>
              <th>Quantity</th>
              <th>Service</th>
              <th>Status</th>
              <th>Remains</th>
              <th></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $("#service_id").select2({
      placeholder: "{{ __t('Chọn dịch vụ') }}",
      allowClear: true,
      dir: "ltr",
    });

    $(document).ready(() => {
      'use strict'

      const $table = $('#datatable-custom1')

      const isValidUrl = (string) => {
        try {
          new URL(string)
          return true
        } catch (_) {
          return false
        }
      }

      const truncateStr = (str, n) => {
        return str.length > n ? str.substr(0, n - 1) + '...' : str
      }

      const $renderActions = (data, type, row) => {
        let act = row.order_actions
        let html = ''

        const $actions = []

        if (act?.can_update === true) {
          $actions.push({
            text: '{{ __t('Cập nhật đơn hàng') }}',
            icon: 'fas fa-sync',
            class: 'text-primary',
            action: 'update',
          })
        }
        if (act?.can_refund === true) {
          $actions.push({
            text: '{{ __t('Yêu cầu huỷ đơn') }}',
            icon: 'fas fa-trash',
            class: 'text-danger',
            action: 'refund',
          })
        }
        if (act?.can_warranty === true) {
          $actions.push({
            text: '{{ __t('Yêu cầu bảo hành') }}',
            icon: 'fas fa-shield',
            class: 'text-success',
            action: 'warranty',
          })
        }
        if (act?.can_resume === true) {
          $actions.push({
            text: '{{ __t('Chạy lại đơn') }}',
            icon: 'fas fa-play',
            class: 'text-info',
            action: 'resume',
          })
        }

        $actions.forEach((action) => {
          action.href = `javascript:;`
          action.class = action.class + ' order__action'

          html +=
            `<a href="${action.href}" class="${action.class} me-2" data-action="${action.action}" data-order-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="${action.text}"><i class="${action.icon}"></i></a>`
        })

        return html
      }

      const $tableOptions = {
        processing: true,
        serverSide: true,
        ajax: {
          url: '/api/orders',
          type: 'GET',
          headers: {
            Authorization: `Bearer ${userData?.access_token}`,
          },
          data: (data) => {
            let payload = {}
            // default params
            payload.status = $('#select_status').val()
            payload.service_id = $('#service_id').val()

            // set params
            payload.page = data.start / data.length + 1
            payload.limit = data.length
            payload.search = data.search.value
            payload.sort_by = data.columns[data.order[0].column].data
            payload.sort_type = data.order[0].dir
            // return json
            return payload
          },
          beforeSend: function(xhr) {
            $setLoading($("#btn_reload"))
          },
          complete: function(xhr) {
            $removeLoading($("#btn_reload"))
          },
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
          data: 'date_str'
        }, {
          data: 'object_id',
          render: (data, type, row) => {
            return `${isValidUrl(row.object_id) ? '<a href="'+row.object_id+'" target="_blank">'+truncateStr(row.object_id, 60)+'</a>' : row.object_id}`
          }
        }, {
          data: 'total_payment',
          render: (data, type, row) => {
            return $formatCurrency(data)
          }
        }, {
          data: 'start_number',
          render: (data, type, row) => {
            return $formatNumber(data)
          }
        }, {
          data: 'quantity',
          render: (data, type, row) => {
            return $formatNumber(data)
          }
        }, {
          data: 'service_name',
          render: (data, type, row) => {
            const trunLength = 40
            if (data.length > trunLength) {
              return `<span class="badge bg-danger">ID ${row.service_id}</span>` + ' - ' + data.substring(0, trunLength) + '...'
            } else {
              return `<span class="badge bg-danger">ID ${row.service_id}</span>` + ' - ' + data
            }
          }
        }, {
          data: 'order_status_str'
        }, {
          data: 'success_count',
          render: (data, type, row) => {
            return $formatNumber(row.quantity - data)
          }
        }, {
          data: 'order_actions',
          className: 'text-center',
          render: $renderActions,
          orderable: false,
          searchable: false,
        }],
        order: [
          [0, 'desc']
        ],
        lengthMenu: [
          [10, 20, 50, 100, 500, 1000, 5000],
          [10, 20, 50, 100, 500, 1000, 5000],
        ],
        pageLength: 50,
      }

      const $tableInstance = $table.DataTable($tableOptions)

      $tableInstance.on('draw.dt', function() {
        $('[data-bs-toggle="tooltip"]').tooltip()
      })

      $('#btn_reload').click(() => {
        $tableInstance.draw()
      })

      const reloadDatatable = () => {
        $tableInstance.draw()
      }

      const $sendAction = (action, id) => {
        let simpleText = '{{ __t('Bạn có chắc muốn thực hiện hành động này?') }}'
        if (action === 'refund') {
          simpleText = '{{ __t('Đơn hàng sau khi huỷ sẽ được hoàn tiền sau 2-3 giờ!') }}'
        } else if (action === 'update') {
          simpleText = '{{ __t('Cập nhật trạng thái đơn hàng mới nhất ở hiện tại!') }}'
        } else if (action === 'warranty') {
          simpleText = '{{ __t('Yêu cầu bảo hành sẽ được xử lý trong vòng 24h!') }}'
        }

        Swal.fire({
          title: '{{ __t('Bạn chắc chứ?') }}',
          text: simpleText,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: '{{ __t('Đồng ý') }}',
          cancelButtonText: '{{ __t('Hủy') }}',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            return axios
              .post(`/api/orders/${id}/${action}`)
              .then(({
                data: res
              }) => {
                Swal.fire({
                  title: '{{ __t('Thành công') }}',
                  text: res.message,
                  icon: 'success',
                })
                $tableInstance.draw()
              })
              .catch((err) => {
                Swal.fire('{{ __t('Thất bại') }}', $catchMessage(err), 'error')
              })
          },
          allowOutsideClick: () => !Swal.isLoading(),
        })
      }
      // action
      $table.on('click', '.order__action', function(e) {
        e.preventDefault()
        const $action = $(this).data('action')
        const $id = $(this).data('orderId')
        $sendAction($action, $id)
      })
    })
  </script>

@endsection

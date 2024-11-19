@extends('admin.layouts.master')

@section('title', 'Admin: Quản lý đơn hàng')

@section('content')
  <div class="mb-3 text-end">
    <button class="btn btn-danger-gradient action-ids" onclick="deleteList()"><i class="fas fa-trash me-2"></i> {{ __t('Xoá') }}</button>
  </div>
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <h4 class="card-title">Danh sách đơn hàng</h4>
    </div>
    <div class="card-body">
      <div class="text-center mb-3">
        <div class="row">
          <div class="col-md-2">
            <div class="mb-3">
              <label for="select_status" class="form-label">Trạng thái : </label>
              <select name="status" id="select_status" class="form-select">
                <option value="">Tất cả</option>
                <option value="Peding">Đang chờ</option>
                <option value="Running">Đang chạy</option>
                <option value="Processing">Đang tăng</option>
                <option value="Refund">Hoàn tiền</option>
                <option value="Completed">Hoàn thành</option>
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="mb-3">
              <label for="select_service" class="form-label">Dịch vụ : </label>
              <select name="service_id" id="select_service" class="form-select">
                <option value="">Tất cả</option>
                @foreach ($services as $service)
                  <option value="{{ $service->id }}">ID {{ $service->id }} - {{ $service->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="mb-3">
              <label for="input_username" class="form-label">Tài khoản : </label>
              <input type="text" class="form-control" id="input_username" placeholder="Nhập tài khoản">
            </div>
          </div>
          <div class="col-md-2">
            <label for="start_date" class="form-label">Ngày bắt đầu</label>
            <input type="date" class="form-control" id="start_date" name="start_date">
          </div>
          <div class="col-md-2">
            <label for="end_date" class="form-label">Ngày kết thúc</label>
            <input type="date" class="form-control" id="end_date" name="end_date">
          </div>
          <div class="col-md-2">
            <button class="btn btn-primary w-100" id="btn_reload" onclick='$("#datatable").DataTable().ajax.reload()' style="margin-top: 25px">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>

      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-striped text-nowrap fw-bold" id="datatable">
          <thead>
            <tr class="bg-primary text-white">
              <th>ID</th>
              <th data-sortable="false" width="10">
                <input type="checkbox" name="checked_all">
              </th>
              <th class="text-center">Thao tác</th>
              <th>Order ID</th>
              <th>Thông tin</th>
              <th>Trạng thái</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-order-view" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Thêm thông tin mới</h5>
          <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('admin.orders.update') }}" method="POST" class="axios-form1">
            @csrf

            <input type="hidden" name="id" id="id">
            <input type="hidden" name="pid" id="pid">

            <div class="mb-3">
              <label for="service_name" class="form-label">Service Name</label>
              <input class="form-control" type="text" id="service_name" readonly></input>
            </div>

            <div class="mb-3">
              <label for="object_id" class="form-label">Object ID</label>
              <input class="form-control" type="text" id="object_id" name="object_id"></input>
            </div>

            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="quantity" class="form-label">Quantity</label>
                <input class="form-control" type="number" id="quantity" readonly></input>
              </div>
              <div class="col-md-6">
                <label for="start_number" class="form-label">Start Number</label>
                <input class="form-control" type="number" id="start_number" name="start_number"></input>
              </div>
            </div>

            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="success_count" class="form-label">Success Count</label>
                <input class="form-control" type="number" id="success_count" name="success_count"></input>
              </div>
              <div class="col-md-6">
                <label for="order_status" class="form-label">Order Status</label>
                <select class="form-control" id="order_status" name="order_status">
                  <option value="Error">Đơn lỗi</option>
                  <option value="Pending">Đang chờ</option>
                  <option value="Running">Đang chạy</option>
                  <option value="Refund">Hoàn tiền</option>
                  <option value="Processing">Đang xử lý</option>
                  <option value="Completed">Hoàn thành</option>
                </select>
              </div>
            </div>
            <div class="mb-3 form_comments" style="display: none">
              <label for="comments" class="form-label">Comments</label>
              <textarea class="form-control" id="comments" name="comments" rows="5" readonly></textarea>
            </div>

            <div class="mode__api" style="display: none">
              <hr />
              <div id="provider_name" class="mb-2"></div>
              <div class="mb-3 row" style="">
                <div class="col-md-6">
                  <label for="src_id" class="form-label">API Order ID</label>
                  <input class="form-control" type="text" id="src_id" disabled></input>
                </div>
                <div class="col-md-6">
                  <label for="src_type" class="form-label">API Service ID</label>
                  <input class="form-control" type="number" id="src_type" disabled></input>
                </div>
              </div>

              <div class="mb-3 row">
                <div class="col-md-6">
                  <label for="src_place" class="form-label">API Placed</label>
                  <select name="src_place" id="src_place" class="form-control">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                  </select>
                  <small>Nếu chọn <span class="text-danger">No</span> hệ thống sẽ gửi lại đơn cho Provider!</small>
                </div>
                <div class="col-md-6">
                  <label for="src_status" class="form-label">API Status</label>
                  <select id="src_status" class="form-control" disabled>
                    <option value="Error">Lỗi</option>
                    <option value="Pending">Chờ gửi</option>
                    <option value="Success">Thành công</option>
                  </select>
                </div>

              </div>

              <div class="mb-3">
                <label for="src_resp" class="form-label">API Response</label>
                <pre id="src_resp"></pre>
              </div>
            </div>

            <div class="mb-3">
              <button class="btn btn-primary w-100" type="submit">Cập nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    var API_ORDERS = '/api/orders'
    var SERVICE_INFO = null;
  </script>
  <script>
    const deleteList = async () => {
      let ids = getIds()

      if (ids.length === 0) {
        Swal.fire('Thất bại', '{{ __t('Vui lòng chọn ít nhất 1 dòng để xóa') }}', 'error')
        return
      }

      const confirm = await Swal.fire({
        title: '{{ __t('Bạn chắc chứ?') }}',
        text: "{{ __t('Bạn sẽ không thể khôi phục lại dữ liệu này!') }}",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '{{ __t('Xóa') }}',
        cancelButtonText: '{{ __t('Hủy') }}'
      })

      if (!confirm.isConfirmed)
        return

      $showLoading();

      try {
        const {
          data: result
        } = await axios.post('{{ route('admin.orders.delete') }}', {
          ids
        })

        Swal.fire('Thành công', result.message, 'success').then(() => {
          window.location.reload();
        })
      } catch (error) {
        Swal.fire('Thất bại', $catchMessage(error), 'error')
      }
    }
  </script>
  <script>
    $("[name=checked_all]").change(function(e) {
      if ($(this).is(":checked")) {
        $("[name='checked_ids[]']").prop("checked", true)
      } else {
        $("[name='checked_ids[]']").prop("checked", false)
      }
    })

    function getIds() {
      let ids = []
      $("[name='checked_ids[]']:checked").each(function() {
        ids.push($(this).val())
      })
      return ids
    }
    // find class actions-ids set disabled with getIds() < 0, and set length checked-ids
    function setActions() {
      let ids = getIds()

      if (ids.length > 0) {
        $(".action-ids").prop("disabled", false)
        $(".checked-ids").text(ids.length)
      } else {
        $(".action-ids").prop("disabled", true)
        $(".checked-ids").text(0)
      }
    }
    $(document)
      .ready(function() {
        setActions();
      })
      .on('change', 'input[name="checked_all"]:enabled', function() {
        setActions();
      })
      .on('change', 'input[name="checked_ids[]"]:enabled', function() {
        setActions();
      })

    $(document).ready(() => {
      'use strict'

      const $table = $('#datatable')

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
            text: 'Cập nhật đơn hàng',
            icon: 'fas fa-sync',
            class: 'text-primary',
            action: 'update',
          })
        }
        if (act?.can_refund === true) {
          $actions.push({
            text: 'Yêu cầu huỷ đơn',
            icon: 'fas fa-trash',
            class: 'text-danger',
            action: 'refund',
          })
        }
        if (act?.can_warranty === true) {
          $actions.push({
            text: 'Yêu cầu bảo hành',
            icon: 'fas fa-shield',
            class: 'text-success',
            action: 'warranty',
          })
        }
        if (act?.can_resume === true) {
          $actions.push({
            text: 'Chạy lại đơn',
            icon: 'fas fa-play',
            class: 'text-info',
            action: 'resume',
          })
        }

        $actions.push({
          text: 'Xem chi tiết',
          icon: 'fas fa-eye',
          class: 'text-danger',
          action: 'view',
        })

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
          url: API_ORDERS,
          type: 'GET',
          headers: {
            Authorization: `Bearer ${userData?.access_token}`,
          },
          data: (data) => {
            let payload = {}
            // default params
            payload.status = $('#select_status').val()
            payload.username = $('#input_username').val()
            payload.service_id = $('#select_service').val()

            payload.start_date = $('#start_date').val()
            payload.end_date = $('#end_date').val()
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
            $setLoading($('#btn_reload'))
          },
          error: function(xhr) {
            console.log(xhr?.responseJSON)
          },
          dataFilter: function(data) {
            let json = JSON.parse(data)
            if (json.status) {
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
          },
          {
            data: null,
            render: (data, type, row) => {
              return `<input type="checkbox" name="checked_ids[]" value="${row.id}">`
            },
            sortable: false,
          },
          {
            data: 'action',
            sortable: false,
            className: 'text-center',
            render: (data, type, row) => {
              return $renderActions(data, type, row)
            },
          },
          {
            data: null,
            render: (data, type, row) => {
              return `<div><span>ProviderID: </span><span class="text-danger">${row.src_id}</span></div>
              <div><span>Username: </span><span class="text-primary">${row.username} (ID ${row.user_id})</span></div>
              <div><span>Created At: </span><span class="text-success">${row.date_str}</span></div>
              <div><span>Updated At: </span><span class="text-warning">${row.update_date_str}</span></div>`
            },
          },
          {
            data: null,
            render: function(data, type, row) {
              return `<div><span>Link: </span><span class="text-muted">${isValidUrl(row.object_id) ? '<a href="'+row.object_id+'" target="_blank">'+truncateStr(row.object_id, 60)+'</a>' : row.object_id}</span></div>
              <div><span>Quantity: </span><span class="text-muted">${$formatNumber(row.quantity)}</span></div>
              <div><span>Charge: </span><span class="text-danger">${$formatCurrency(row.total_payment)}</span> / <span class="text-primary">${$formatCurrency(row.src_cost)}</span> - <small class="text-muted cursor-pointer">${row.order_code}</small></div>
              <div><span>Start counter: </span><span class="text-muted">${$formatNumber(row?.start_number??0)}</span></div>
              <div><span>Remains: </span><span class="text-muted">${$formatNumber(row.quantity-row.success_count)}</span></div>`
            }
          },
          {
            data: 'order_status_str',
            render: function(data, type, row) {
              return `<div><span>Status: </span>${row.order_status_str}</div>
              <div><span>Dịch vụ: </span><span class="text-muted">${row.service_name}</span></div>
              <div><span>Provider: </span><span class="text-success">${row.provider_name} (ID: ${row.src_type})</span></div>
              <div><span>Order Note: </span><span class="text-primary">${row?.extra_note ?? '-'}</span></div>
              <div><span>API Response: </span><span class="text-danger">${row.src_resp?.error?row.src_resp.error:JSON.stringify(row.src_resp)}</span></div>`
            }
          }
        ],
        columnDefs: [{
          targets: [1, 2, 3, 4, 5],
          orderable: false,
          sortable: false,
        }],
        order: [
          [0, 'desc']
        ],
        lengthMenu: [
          [10, 20, 50, 100, 500, 1000, 5000],
          [10, 20, 50, 100, 500, 1000, 5000],
        ],
        pageLength: 20,

      }

      const $tableInstance = $table.DataTable($tableOptions)

      $tableInstance.on('draw.dt', function() {
        $removeLoading($('#btn_reload'))
        $('[data-bs-toggle="tooltip"]').tooltip()
      })

      $('#select_status').change(() => {
        $tableInstance.draw()
      })

      const $sendAction = (action, id) => {
        let simpleText = 'Bạn có chắc muốn thực hiện hành động này?'
        if (action === 'refund') {
          simpleText = 'Đơn hàng sau khi huỷ sẽ được hoàn tiền sau 2-3 giờ!'
        } else if (action === 'update') {
          simpleText = 'Cập nhật trạng thái đơn hàng mới nhất ở hiện tại!'
        } else if (action === 'warranty') {
          simpleText = 'Yêu cầu bảo hành sẽ được xử lý trong vòng 24h!'
        }

        if (!['view', 'history'].includes(action)) {
          Swal.fire({
            title: 'Xác nhận',
            text: simpleText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy',
            showLoaderOnConfirm: true,
            preConfirm: () => {
              return axios
                .post(`${API_ORDERS}/${id}/${action}`)
                .then(({
                  data: res
                }) => {
                  Swal.fire({
                    title: 'Thành công',
                    text: res.message,
                    icon: 'success',
                  })
                  $tableInstance.draw()
                })
                .catch((err) => {
                  Swal.fire('Thất bại', $catchMessage(err), 'error')
                })
            },
            allowOutsideClick: () => !Swal.isLoading(),
          })
        } else {
          if (action === 'view') {
            const $modal = $('#modal-order-view')

            handleModal(id, $modal)
          } else if (action === 'history') {
            window.open(`/admin/orders/${id}/history`, '_blank')
          }

        }
      }

      const handleModal = (id, $modal) => {
        axios
          .get(`${API_ORDERS}/get-by-id?id=${id}`)
          .then(({
            data: res
          }) => {
            const data = res.data

            $modal.find('.modal-title').text(`Xem chi tiết đơn hàng #${res.data.id}`)
            // fields
            $modal.find('#id').val(data.id)
            $modal.find('#pid').val(data.pid)

            $modal.find('#service_name').val(data.service_name)
            $modal.find('#object_id').val(data.object_id)
            $modal.find('#quantity').val(data.quantity)
            $modal.find('#start_number').val(data.start_number)
            $modal.find('#success_count').val(data.success_count)

            $modal.find('#order_status').val(data.order_status)

            $modal.find('#src_id').val(data.src_id)
            $modal.find('#src_type').val(data.src_type)
            $modal.find('#src_status').val(data.src_status)
            $modal.find('#src_place').val(data.src_place === true ? 1 : 0)

            $modal.find('#src_resp').text(JSON.stringify(data.src_resp, null, 2))

            const comments = data.extra_data['comments']

            if (comments) {
              $modal.find('.form_comments').show()
              $modal.find('#comments').val(comments.join("\n"))
            } else {
              $modal.find('.form_comments').hide()
            }

            $modal.find('#provider_name').html(`<span>Provider: </span><span class="text-danger fw-bold">${data.provider_name} (ID: ${data.src_type})</span>`)

            // show modal
            if (data.src_name !== 'manual') {
              $(".mode__api").show()
            } else {
              $(".mode__api").hide()
            }
            $modal.modal('show')
          })
          .catch((err) => {
            Swal.fire('Thất bại', $catchMessage(err), 'error')
          })
      }
      // action
      $table.on('click', '.order__action', function(e) {
        e.preventDefault()
        const $action = $(this).data('action')
        const $id = $(this).data('orderId')
        $sendAction($action, $id)
      })

      $('.axios-form1').submit(async function(e) {
        e.preventDefault();

        let form = $(this);

        let url = form.attr('action');

        let method = form.attr('method');

        let data = form.serialize();

        pageOverlay.show()

        axios({
          method: method,
          url: url,
          data: data
        }).then(function(response) {
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: response.data.message,
          }).then(() => {
            $tableInstance.draw()

            handleModal(response.data.data.id, $('#modal-order-view'))
          })
        }).catch(function(error) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: $catchMessage(error),
          })
        }).finally(() => {
          pageOverlay.hide()
        })

      })
    })
  </script>
@endsection

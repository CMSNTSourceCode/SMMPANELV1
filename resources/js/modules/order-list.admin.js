$(document).ready(() => {
  'use strict'

  const $table = $('#datatable')

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

    $actions.forEach((action) => {
      action.href = `javascript:;`
      action.class = action.class + ' order__action'

      html += `<a href="${action.href}" class="${action.class} me-2" data-action="${action.action}" data-order-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="${action.text}"><i class="${action.icon}"></i></a>`
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
        payload.category = $('#select_category').val()
        payload.username = $('#input_username').val()

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
      beforeSend: function (xhr) {
        $setLoading($('#btn_reload'))
      },
      error: function (xhr) {
        console.log(xhr?.responseJSON)
      },
      dataFilter: function (data) {
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
    columns: [
      { data: 'id' },
      {
        data: 'action',
        sortable: false,
        render: (data, type, row) => {
          return $renderActions(data, type, row)
        },
      },
      {
        data: 'order_code',
        render: (data, type, row) => {
          return `<span style="color: #362FD9">${data}</span>`
        },
      },
      {
        data: 'username',
        render: (data, type, row) => {
          return `<span style="color: #79155B">${data}</span>`
        },
      },

      {
        data: 'profit_str',
        sortable: false,
        render: (data, type, row) => {
          const formatted = $formatCurrency(data)
          if (data < 0) {
            return `<span style="color: #f33">${formatted}</span>`
          } else {
            return `<span style="color: #000080">${formatted}</span>`
          }
        },
      },
      {
        data: 'total_payment',
        render: (data, type, row) => {
          const formatted = $formatCurrency(data)
          return `<span style="color: #6420AA">${formatted}</span>`
        },
      },
      {
        data: 'status',
        render: (data, type, row) => {
          return $formatStatus(data)
        },
      },
      {
        data: 'created_at',
        render: (data, type, row) => {
          return `<span style="color: #0C356A">${$formatDateTime(
            data,
            'YYYY-MM-DD HH:mm'
          )}</span>`
        },
      },
      {
        data: 'order_note',
        render: (data, type, row) => {
          return `<span style="color: #F45050">${data}</span>`
        },
      },

      {
        data: 'server_name',
        render: (data, type, row) => {
          return `<span style="color: #0C356A">${data}</span>`
        },
      },
      {
        data: 'object_id',
        render: (data, type, row) => {
          return `<span style="color: #4D3C77">${data}</span>`
        },
      },
      {
        data: 'server_id',
        render: (data, type, row) => {
          return `<span style="color: #C70039">SERVER ${data}</span>`
        },
      },
      {
        data: 'quantity',
        render: (data, type, row) => {
          const formatted = $formatNumber(data)
          return `<span style="color: #B2533E">${formatted}</span>`
        },
      },
      {
        data: 'start_number',
        render: (data, type, row) => {
          const formatted = $formatNumber(data)
          return `<span style="color: #79155B">${formatted}</span>`
        },
      },
      {
        data: 'success_count',
        render: (data, type, row) => {
          const formatted = $formatNumber(data)
          return `<span style="color: #0C356A">${formatted}</span>`
        },
      },
      {
        data: 'price_per',
        render: (data, type, row) => {
          const formatted = $formatCurrency(data)
          return `<span style="color: #E55604">${formatted}</span>`
        },
      },
    ],
    order: [[0, 'desc']],
    lengthMenu: [
      [10, 20, 50, 100, 500, 1000, 5000],
      [10, 20, 50, 100, 500, 1000, 5000],
    ],
  }

  const $tableInstance = $table.DataTable($tableOptions)

  $tableInstance.on('draw.dt', function () {
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
          .then(({ data: res }) => {
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
  }
  // action
  $table.on('click', '.order__action', function (e) {
    e.preventDefault()
    const $action = $(this).data('action')
    const $id = $(this).data('orderId')
    $sendAction($action, $id)
  })
})

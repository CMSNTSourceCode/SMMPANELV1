@extends('admin.layouts.master')
@section('title', 'Admin: Transactions')
@section('content')
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Danh sách giao dịch</div>
    </div>
    <div class="card-body">
      <div class="mb-2">
        <form id="filter" onsubmit="$('#basic-1').DataTable().ajax.reload(); return false;">
          <div class="mb-3 row">
            <div class="col-md-2">
              <label for="type" class="form-label">Loại giao dịch</label>
              <select name="type" id="type" class="form-select">
                <option value="">Tất cả</option>
                <option value="VIETTEL">VIETTEL</option>
                <option value="VINAPHONE">VINAPHONE</option>
                <option value="MOBIFONE">MOBIFONE</option>
                <option value="ZING">ZING</option>
              </select>
            </div>
            <div class="col-md-2">
              <label for="order_id" class="form-label">Mã đơn hàng</label>
              <input type="text" class="form-control" id="order_id" name="order_id">
            </div>
            <div class="col-md-2">
              <label for="username" class="form-label">Tài khoản</label>
              <input type="text" class="form-control" id="username" name="username">
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
              <label for="type" class="form-label">_</label>
              <div>
                <button class="btn btn-primary">Lọc dữ liệu</button>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="table-responsive theme-scrollbar">
        <table class="display table table-bordered table-stripped text-nowrap" id="basic-1">
          <thead>
            <tr>
              <th>#</th>
              <th>Tài khoản</th>
              <th>ID API</th>
              <th>Mệnh giá</th>
              <th>Thực nhận</th>
              <th>Loại thẻ</th>
              <th>Mã thẻ</th>
              <th>Số serial</th>
              <th>Trạng thái</th>
              <th>Nội dung</th>
              <th>Thời gian</th>
              <th>Cập nhật</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    $(document).ready(function() {
      //DataTable
      $("#basic-1").DataTable({
        dom: 'Bfrtip',
        buttons: [
          'excel', 'pageLength'
        ],
        order: [0, 'desc'],
        responsive: false,
        lengthMenu: [
          [10, 50, 100, 200, 500, 1000, 2000, 10000, -1],
          [10, 50, 100, 200, 500, 1000, 2000, 10000, "All"]
        ],
        language: {
          searchPlaceholder: 'Tìm kiếm...',
          sSearch: '',
          lengthMenu: '_MENU_',
        },
        processing: true,
        serverSide: true,
        ajax: {
          url: '/api/admin/transactions/list-card',
          async: true,
          type: 'GET',
          dataType: 'json',
          headers: {
            'Authorization': 'Bearer ' + userData.access_token,
            'Accept': 'application/json',
          },
          data: function(data) {
            let payload = {}
            // default params
            payload.type = $('#type').val();
            payload.order_id = $('#order_id').val();
            payload.username = $('#username').val();

            // set date
            payload.start_date = $('#start_date').val();
            payload.end_date = $('#end_date').val();


            // set params
            payload.page = data.start / data.length + 1;
            payload.limit = data.length;
            payload.search = data.search.value;
            payload.sort_by = data.columns[data.order[0].column].data;
            payload.sort_type = data.order[0].dir;
            // return json
            return payload;
          },
          error: function(xhr) {
            Swal.fire('Thất bại', $catchMessage(xhr), 'error')
          },
          dataFilter: function(data) {
            let json = JSON.parse(data);
            if (json.status) {
              json.recordsTotal = json.data.meta.total
              json.recordsFiltered = json.data.meta.total
              json.data = json.data.data
              return JSON.stringify(json); // return JSON string
            } else {
              Swal.fire('Thất bại', json.message, 'error')
              return JSON.stringify({
                recordsTotal: 0,
                recordsFiltered: 0,
                data: []
              }); // return JSON string
            }
          }
        },
        columns: [{
          data: 'id',
        }, {
          data: 'username',
          render: function(data, type, row) {
            return `<a href="/admin/users/edit/${row.user_id}">${data}</a>`
          }
        }, {
          data: 'order_id',
        }, {
          data: 'value',
          render: function(data, type, row) {
            return $formatCurrency(data)
          }
        }, {
          data: 'amount',
          render: function(data, type, row) {
            return $formatCurrency(data)
          }
        }, {
          data: 'type'
        }, {
          data: 'code'
        }, {
          data: 'serial'
        }, {
          data: 'status_html'
        }, {
          data: 'content',
        }, {
          data: 'created_at',
          render: function(data, type, row) {
            return $formatDate(data)
          }
        }, {
          data: 'updated_at',
          render: function(data, type, row) {
            return $formatDate(data)
          }
        }],
        columnDefs: [{
          orderable: false,
          targets: [1]
        }],
      })
    })
  </script>
@endsection

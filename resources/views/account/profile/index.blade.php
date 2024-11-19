@php use App\Helpers\Helper; @endphp
@extends('layouts.app')
@section('title', $pageTitle)
@section('content')
  <div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
      <div class="card bg-primary img-card box-primary-shadow">
        <div class="card-body">
          <div class="d-flex">
            <div class="text-white">
              <h2 class="mb-0 number-font text-fixed-white">{{ Helper::formatRank($user->rank) }}</h2>
              <p class="text-white mb-0 text-fixed-white">{{ __t('Cấp bậc hiện tại') }}</p>
            </div>
            <div class="ms-auto"> <i class="fas fa-ranking-star text-fixed-white fs-30 me-2 mt-2"></i> </div>
          </div>
        </div>
      </div>
    </div><!-- COL END -->
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
      <div class="card bg-success img-card box-success-shadow">
        <div class="card-body">
          <div class="d-flex">
            <div class="text-white">
              <h2 class="mb-0 number-font text-fixed-white">{{ formatCurrency($user->balance ?? 0) }}</h2>
              <p class="text-white mb-0 text-fixed-white">{{ __t('Số dư hiện tại') }}</p>
            </div>
            <div class="ms-auto"> <i class="fas fa-wallet text-fixed-white fs-30 me-2 mt-2"></i> </div>
          </div>
        </div>
      </div>
    </div><!-- COL END -->
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
      <div class="card bg-info img-card box-info-shadow">
        <div class="card-body">
          <div class="d-flex">
            <div class="text-white">
              <h2 class="mb-0 number-font text-fixed-white">{{ formatCurrency($user->total_deposit ?? 0) }}</h2>
              <p class="text-white mb-0 text-fixed-white">{{ __t('Số tiền đã nạp') }}</p>
            </div>
            <div class="ms-auto"> <i class="fas fa-dollar-sign text-fixed-white fs-30 me-2 mt-2"></i> </div>
          </div>
        </div>
      </div>
    </div><!-- COL END -->
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
      <div class="card bg-secondary img-card box-secondary-shadow">
        <div class="card-body">
          <div class="d-flex">
            <div class="text-white">
              <h2 class="mb-0 number-font text-fixed-white">{{ formatCurrency($totalDepositInMonth ?? 0) }}</h2>
              <p class="text-white mb-0 text-fixed-white">{{ __t('Tổng tiền nạp tháng :month', ['month' => date('m')]) }}</p>
            </div>
            <div class="ms-auto"> <i class="fas fa-database text-fixed-white fs-30 me-2 mt-2"></i> </div>
          </div>
        </div>
      </div>
    </div><!-- COL END -->
  </div>
  <section class="space-y-6">
    <div class="row">
      <div class="col-md-6">
        <div class="card custom-card">
          <div class="card-header">
            <h3 class="card-title">{{ __t('Thông tin tài khoản') }}</h3>
          </div>
          <div class="card-body">
            <form class="space-y-3">
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="username" class="form-label">{{ __t('Tên Đăng Nhập') }}</label>
                  <input id="username" name="username" type="text" class="form-control" value="{{ $user->username }}" disabled>
                </div>
                <div class="col-md-6">
                  <label for="email" class="form-label">{{ __t('Địa chỉ e-mail') }}</label>
                  <input id="email" name="email" type="text" class="form-control" value="{{ $user->email }}" disabled>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="created_at" class="form-label">{{ __t('Ngày Đăng Ký') }}</label>
                  <input id="created_at" name="created_at" type="text" class="form-control" value="{{ $user->created_at }}" disabled>
                </div>
                <div class="col-md-6">
                  <label for="updated_at" class="form-label">{{ __t('Ngày Cập Nhật') }}</label>
                  <input id="updated_at" name="updated_at" type="text" class="form-control" value="{{ $user->updated_at }}" disabled>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="balance" class="form-label">{{ __t('Số Tiền Hiện Có') }}</label>
                  <input id="balance" name="balance" type="text" class="form-control" value="{{ formatCurrency($user->balance) }}" disabled>
                </div>
                <div class="col-md-6">
                  <label for="total_deposit" class="form-label">{{ __t('Tổng Tiền Đã Nạp') }}</label>
                  <input id="total_deposit" name="total_deposit" type="text" class="form-control" value="{{ formatCurrency($user->total_deposit) }}" disabled>
                </div>
              </div>
              <div class="mb-2">
                <div class="alert alert-danger">
                  Access Token *: <span id="access_token">{{ $user->access_token }}</span>
                  <a href="javascript:void(0)" class="copy" data-clipboard-target="#access_token"><i class="fas fa-copy"></i></a>
                  |
                  <a href="javascript:void(0)" class="text-success" onclick="changeAccessToken()"><i class="fas fa-refresh"></i> {{ __t('Đổi token') }}</a>
                </div>
              </div>
              {{-- <div class="mb-3">
                <label for="access_token" class="form-label">Access Token (*)</label>
                <input id="access_token" name="access_token" type="text" class="form-control" value="{{ $user->access_token }}" disabled>
              </div> --}}
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card custom-card">
          <div class="card-header">
            <h3 class="card-title">{{ __t('Thay đổi mật khẩu') }}</h3>
          </div>
          <div class="card-body">
            <form action="{{ route('account.profile.password-update') }}" method="POST" class="space-y-3">
              @csrf
              <div class="mb-3">
                <label for="old_password" class="form-label">{{ __t('Mật Khẩu Cũ') }}</label>
                <input type="password" class="form-control @error('old_password') !border !border-red-500 @enderror py-2" id="old_password" name="old_password" placeholder="{{ __t('Nhập mật khẩu cũ') }}" required>
              </div>
              <div class="mb-3">
                <label for="new_password" class="form-label">{{ __t('Mật Khẩu Mới') }}</label>
                <input type="password" class="form-control @error('new_password') !border !border-red-500 @enderror py-2" id="new_password" name="new_password" placeholder="{{ __t('Nhập mật khẩu mới') }}" required>
              </div>
              <div class="mb-3">
                <label for="confirm_password" class="form-label">{{ __t('Xác Nhận Mật Khẩu') }}</label>
                <input type="password" class="form-control @error('confirm_password') !border !border-red-500 @enderror py-2" id="confirm_password" name="confirm_password"
                  placeholder="{{ __t('Nhập lại mật khẩu mới') }}" required>
              </div>
              <div class="mb-3 text-end">
                <button type="submit" class="btn btn-sm btn-primary w-full">{{ __t('Đổi Mật Khẩu') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="card custom-card">
          <div class="card-header">
            <h3 class="card-title">{{ __t('Lịch sử hoạt động') }}</h3>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="datatable-custom1" class="table table-bordered text-nowrap" style="width:100%">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Content</th>
                    <th>IP Address</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('scripts')
  <script>
    const changeAccessToken = async () => {
      const confirm = await Swal.fire({
        title: '{{ __t('Bạn chắc chứ?') }}',
        text: '{{ __t('Bạn sẽ không thể hoàn tác điều này!') }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '{{ __t('Đồng ý') }}',
        cancelButtonText: '{{ __t('Hủy') }}',
        reverseButtons: true,
      })

      if (!confirm.isConfirmed) return

      $showLoading()

      try {
        const {
          data: result
        } = await axios.post('{{ route('account.profile.token-update') }}')

        $('#access_token').text(result.data.access_token)
        Swal.fire('Success', result.message, 'success')
      } catch (error) {
        Swal.fire('Error', $catchMessage(error), 'error')
      }
    }
    $(document).ready(() => {
      'use strict'

      const $table = $('#datatable-custom1')

      const $tableOptions = {
        processing: true,
        serverSide: true,
        ajax: {
          url: '/api/users/histories',
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
          data: 'created_at',
          render: function(data) {
            return moment(data).format('DD/MM/YYYY HH:mm:ss')
          }
        }, {
          data: 'content'
        }, {
          data: 'ip_address'
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
@endsection

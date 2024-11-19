@php use App\Helpers\Helper; @endphp
@extends('layouts.app')
@section('title', __t('Tiếp thị liên kết'))
@section('content')

  <div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
      <div class="card bg-primary img-card box-primary-shadow">
        <div class="card-body">
          <div class="d-flex">
            <div class="text-white">
              <h2 class="mb-0 number-font text-fixed-white">{{ formatCurrency(Auth::user()->balance_1 ?? 0) }}</h2>
              <p class="text-white mb-0 text-fixed-white">{{ __t('Số dư hoa hồng') }}</p>
            </div>
            <div class="ms-auto"> <i class="fas fa-dollar-sign text-fixed-white fs-30 me-2 mt-2"></i> </div>
          </div>
        </div>
      </div>
    </div><!-- COL END -->
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
      <div class="card bg-secondary img-card box-secondary-shadow">
        <div class="card-body">
          <div class="d-flex">
            <div class="text-white">
              <h2 class="mb-0 number-font text-fixed-white">{{ formatCurrency(Auth::user()->total_withdraw ?? 0) }}</h2>
              <p class="text-white mb-0 text-fixed-white">{{ __t('Hoa hồng đã rút') }}</p>
            </div>
            <div class="ms-auto"> <i class="fas fa-wallet text-fixed-white fs-30 me-2 mt-2"></i>
            </div>
          </div>
        </div>
      </div>
    </div><!-- COL END -->
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
      <div class="card  bg-success img-card box-success-shadow">
        <div class="card-body">
          <div class="d-flex">
            <div class="text-white">
              <h2 class="mb-0 number-font text-fixed-white">{{ Auth::user()->referrals()->count() ?? 0 }}</h2>
              <p class="text-white mb-0 text-fixed-white">{{ __t('Đã giới thiệu') }}</p>
            </div>
            <div class="ms-auto">
              <i class="fas fa-user-plus fs-30 me-2 mt-2"></i>
            </div>
          </div>
        </div>
      </div>
    </div><!-- COL END -->
  </div>
  <div class="row">

    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
      <div class="card bg-info img-card box-info-shadow">
        <div class="card-body">
          <div class="d-flex">
            <div class="text-white">
              <h2 class="mb-0 number-font text-fixed-white">{{ number_format($affiliate->clicks) }}</h2>
              <p class="text-white mb-0 text-fixed-white">{{ __t('Lượt clicks') }}</p>
            </div>
            <div class="ms-auto"> <i class="fas fa-share text-fixed-white fs-30 me-2 mt-2"></i>
            </div>
          </div>
        </div>
      </div>
    </div><!-- COL END -->
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
      <div class="card bg-danger img-card box-danger-shadow">
        <div class="card-body">
          <div class="d-flex">
            <div class="text-white">
              <h2 class="mb-0 number-font text-fixed-white">{{ number_format($affiliate->signups) }}</h2>
              <p class="text-white mb-0 text-fixed-white">{{ __t('Lượt đăng ký') }}</p>
            </div>
            <div class="ms-auto"> <i class="fas fa-users text-fixed-white fs-30 me-2 mt-2"></i>
            </div>
          </div>
        </div>
      </div>
    </div><!-- COL END -->
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
      <div class="card bg-warning img-card box-warning-shadow">
        <div class="card-body">
          <div class="d-flex">
            <div class="text-white">
              <h2 class="mb-0 number-font text-fixed-white">{{ formatCurrency($affiliate->users->sum('total_deposit')) }}</h2>
              <p class="text-white mb-0 text-fixed-white">{{ __t('Tổng tiền nạp') }}</p>
            </div>
            <div class="ms-auto"> <i class="fas fa-credit-card text-fixed-white fs-30 me-2 mt-2"></i>
            </div>
          </div>
        </div>
      </div>
    </div><!-- COL END -->
  </div>

  <div class="card custom-card">
    <div class="card-header">
      <h3 class="card-title">{{ __t('Hướng dẫn sử dụng') }}</h3>
    </div>
    <div class="card-body">
      {!! Helper::getNotice('page_affiliate') !!}
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="card custom-card">
        <div class="card-header">
          <h3 class="card-title">{{ __t('Thông tin giới thiệu') }}</h3>
        </div>
        <div class="card-body">
          <div class="text-danger fw-bold mb-3">{{ __t('Bạn sẽ nhận được :percent hoa hồng khi người dùng bạn giới thiệu nạp tiền vào tài khoản', ['percent' => setting('comm_percent', 10) . '%']) }}.</div>
          <div>
            <label for="referral_code" class="form-label">{{ __t('Liên Kết Giới Thiệu') }}:</label>
            <div class="input-group">
              <input type="text" id="referral_code" name="referral_code" class="form-control form-control-sm" value="{{ route('ref', ['ref' => $affiliate->code]) }}" style="border-radius: 5px 0 0 5px" readonly>
              <button class="btn btn-primary copy" data-clipboard-target="#referral_code" style="border-radius: 0 5px 5px 0" type="button"><i class="fas fa-copy"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card custom-card">
        <div class="card-header">
          <h3 class="card-title">{{ __t('Yêu cầu rút tiền') }}</h3>
        </div>
        <div class="card-body">
          <form action="/api/users/affiliates/withdraw" id="form-withdraw" method="POST">
            <div class="mb-5">
              <div class="text-danger fw-bold">
                <i>
                  {!! __t('Số tiền có thể rút: từ :from đến :max', [
                      'from' => '<span class="text-primary">' . formatCurrency($config['min_withdraw'] ?? 0) . '</span>',
                      'max' => '<span class="text-success">' . formatCurrency($config['max_withdraw'] ?? 0) . '</span>',
                  ]) !!}
                </i>
              </div>
            </div>
            <div class="mb-4">
              <label for="amount" class="form-label">{{ __t('Số Tiền Rút') }}</label>
              <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount', $config['min_withdraw'] ?? 0) }}" required>
            </div>
            <div class="mb-4">
              <label for="withdraw_to" class="form-label">{{ __t('Rút Về') }}</label>
              <select name="withdraw_to" id="withdraw_to" class="form-control">
                <option value="bank">{{ __t('Ngân Hàng') }}</option>
                <option value="wallet" selected>{{ __t('Ví Tài Khoản') }}</option>
              </select>
            </div>
            <div class="mb-4 group_banking">
              <label for="bank_name" class="form-label">{{ __t('Ngân Hàng') }}</label>
              <select name="bank_name" id="bank_name" class="form-control">
                <option value="">{{ __t('Chọn Ngân Hàng Rút') }}</option>
                @foreach (Helper::getListBank() as $bank)
                  <option value="{{ $bank['code'] }}">Ngân hàng {{ $bank['code'] }} - {{ $bank['shortName'] }}</option>
                @endforeach
              </select>
            </div>
            <div class="row mb-4 group_banking">
              <div class="col-md-6">
                <label for="account_number" class="form-label">{{ __t('Số Tài Khoản') }}</label>
                <input type="text" class="form-control" id="account_number" name="account_number" value="{{ old('account_number') }}" placeholder="{{ __t('Nhập số tài khoản') }}">
              </div>
              <div class="col-md-6">
                <label for="account_name" class="form-label">{{ __t('Chủ Tài Khoản') }}</label>
                <input type="text" class="form-control" id="account_name" name="account_name" value="{{ old('account_name') }}" placeholder="{{ __t('Nhập tên chủ tài khoản') }}">
              </div>
            </div>
            <div class="mb-4 group_banking">
              <label for="user_note" class="form-label">{{ __t('Ghi Chú') }}</label>
              <textarea name="user_note" id="user_note" class="form-control" rows="3" placeholder="{{ __t('Nhập ghi chú cho admin nếu có') }}">{{ old('user_note') }}</textarea>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-primary " type="submit">{{ __t('Rút Tiền Ngay') }}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card custom-card">
        <div class="card-header">
          <h3 class="card-title">{{ __t('Lịch sử giới thiệu') }}</h3>
        </div>
        <div class="card-body">
          <div class="table-responsive df-example demo-table">
            <table class="table table-bordered table-stripped datatable">
              <thead class=" border-t border-slate-100 dark:border-slate-800">
                <tr>
                  <th class="table-th">#</th>
                  <th class="table-th">{{ __t('Tài khoản') }}</th>
                  <th class="table-th">{{ __t('Tổng Tiền Nạp') }}</th>
                  <th class="table-th">{{ __t('Tiền Hoa Hồng') }}</th>
                  <th class="table-th">{{ __t('Thời Gian Tạo') }}</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                @foreach ($referrals as $value)
                  <tr>
                    <td class="table-td">{{ $loop->iteration }}</td>
                    <td class="table-td">{{ Helper::hideUsername($value->to_username) }}</td>
                    <td class="table-td">{{ formatCurrency($value->total_deposit ?? -1) }}</td>
                    <td class="table-td">{{ formatCurrency($value->total_commission) }}</td>
                    <td class="table-td">{{ $value->created_at }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card custom-card">
        <div class="card-header">
          <h3 class="card-title">{{ __t('Lịch sử giao dịch') }}</h3>
        </div>
        <div class="card-body">
          <div class="table-responsive df-example demo-table overflow-auto">
            <table class="table table-bordered table-stripped text-nowrap datatable">
              <thead>
                <tr>
                  <th class="table-th">#</th>
                  <th class="table-th">{{ __t('Mã Giao Dịch') }}</th>
                  <th class="table-th">{{ __t('Số Tiền') }}</th>
                  <th class="table-th">{{ __t('Số Dư Trước') }}</th>
                  <th class="table-th">{{ __t('Số Dư Sau') }}</th>
                  <th class="table-th">{{ __t('Giao Dịch') }}</th>
                  <th class="table-th">{{ __t('Trạng Thái') }}</th>
                  <th class="table-th">{{ __t('Tài Khoản') }}</th>
                  <th class="table-th">{{ __t('Ghi Chú') }}</th>
                  <th class="table-th">{{ __t('Hệ Thống') }}</th>
                  <th class="table-th">{{ __t('Thời Gian') }}</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                @foreach ($histories as $value)
                  <tr>
                    <td class="table-td">{{ $value->id }}</td>
                    <td class="table-td">{{ $value->order_id }}</td>
                    <td class="table-td">{{ formatCurrency($value->amount) }}</td>
                    <td class="table-td">{{ formatCurrency($value->balance_before) }}</td>
                    <td class="table-td">{{ formatCurrency($value->balance_after) }}</td>
                    <td class="table-td">{{ $value->prefix === '+' ? __t('Cộng Tiền') : __t('Trừ Tiền') }}</td>
                    <td class="table-td">{!! $value->status_html !!}</td>
                    <td class="table-td">{{ $value->username }}</td>
                    <td class="table-td">{{ $value->user_note }}</td>
                    <td class="table-td">{{ $value->sys_note }}</td>
                    <td class="table-td">{{ $value->created_at }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    $(document).ready(() => {
      $('#withdraw_to').change(function() {
        if ($(this).val() == 'bank') {
          $('.group_banking').removeClass('d-none');
        } else {
          $('.group_banking').addClass('d-none');
        }
      })
      $("#withdraw_to").trigger('change')

      $("#form-withdraw").submit(async e => {
        e.preventDefault();

        const action = $(e.target).attr('action'),
          button = $(e.target).find('button[type="submit"]')
        payload = $formDataToPayload(new FormData(e.target));

        const confirm = await Swal.fire({
          title: 'Xác Nhận',
          html: `Bạn muốn rút <b>${$formatNumber(payload.amount)} VNĐ</b> về <b>${payload.withdraw_to === 'bank' ? 'ngân hàng' : 'website'}</b> đúng không?`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Xác Nhận',
          cancelButtonText: 'Hủy',
        })

        if (!confirm.isConfirmed) return

        if (payload.amount < {{ $config['min_withdraw'] ?? 0 }}) {
          return Swal.fire('Thất Bại', `Số tiền rút tối thiểu là {{ number_format($config['min_withdraw'] ?? 0) }} VNĐ`, 'error')
        }

        if (payload.amount > {{ $config['max_withdraw'] ?? 0 }}) {
          return Swal.fire('Thất Bại', `Số tiền rút tối đa là {{ number_format($config['max_withdraw'] ?? 0) }} VNĐ`, 'error')
        }

        $setLoading(button)

        axios.post(action, payload).then(({
          data: result
        }) => {
          Swal.fire('Thành Công', result.message, 'success').then(() => location.reload())
        }).catch(e => {
          Swal.fire('Thất Bại', $catchMessage(e), 'error')
        }).finally(() => {
          $removeLoading(button)
        })
      })
    })
  </script>
@endsection

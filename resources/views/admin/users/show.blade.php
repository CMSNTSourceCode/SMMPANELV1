@php use App\Helpers\Helper; @endphp
@extends('admin.layouts.master')
@section('title', 'Admin: User Detail')
@section('content')
  <section>
    <div class="card custom-card">
      <div class="card-header justify-content-between">
        <div class="card-title">
          Thông Tin Chung
        </div>
        <a href="javascript:void(0);" data-bs-toggle="card-fullscreen">
          <i class="ri-fullscreen-line"></i>
        </a>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.users.update', ['id' => $user->id]) }}" method="POST" class="default-form">
          @csrf
          <div class="form-group row mb-3">
            <div class="col-lg-6">
              <label for="username" class="form-label">Tài khoản</label>
              <input type="text" class="form-control" id="username" value="{{ $user->username }}" readonly>
            </div>
            <div class="col-lg-6">
              <label for="access_token" class="form-label">Access Token (API)</label>
              <input type="text" class="form-control" id="access_token" value="{{ $user->access_token }}" readonly>
            </div>
          </div>
          <div class="form-group row mb-3">
            <div class="col-lg-6">
              <label for="created_at" class="form-label">Ngày đăng ký</label>
              <input type="text" class="form-control" id="created_at" value="{{ $user->created_at }}" readonly>
            </div>
            <div class="col-lg-6">
              <label for="ip_address" class="form-label">Địa chỉ IP</label>
              <input type="text" class="form-control" id="ip_address" value="{{ $user->ip_address }}" readonly>
            </div>
          </div>
          <div class="form-group row mb-3">
            <div class="col-lg-6">
              <label for="balance" class="form-label">Số tiền hiện tại</label>
              <input type="text" class="form-control" id="balance" value="{{ number_format($user->balance) }} đ" readonly>
            </div>
            <div class="col-lg-6">
              <label for="total_deposit" class="form-label">Tổng tiền đã nạp</label>
              <input type="text" class="form-control" id="total_deposit" value="{{ number_format($user->total_deposit) }} đ" readonly>
            </div>
          </div>
          <div class="form-group row mb-3">
            <div class="col-md-6">
              <label for="role" class="form-label">Loại tài khoản</label>
              <select class="form-control" id="role" name="role" required>
                <option value="member" @if ($user->role == 'member') selected @endif>Thành viên
                </option>
                <option value="admin" @if ($user->role == 'admin') selected @endif>Quản trị viên
                </option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="rank" class="form-label">Cấp bậc tài khoản</label>
              <select class="form-control" id="rank" name="rank" required>
                <option value="bronze" @if ($user->rank == 'bronze') selected @endif>Đồng</option>
                <option value="silver" @if ($user->rank == 'silver') selected @endif>Bạc</option>
                <option value="gold" @if ($user->rank == 'gold') selected @endif>Vàng</option>
                <option value="platinum" @if ($user->rank == 'platinum') selected @endif>Bạch Kim</option>
                <option value="diamond" @if ($user->rank == 'diamond') selected @endif>Kim Cương</option>
                <option value="titanium" @if ($user->rank == 'titanium') selected @endif>Titanium</option>
              </select>
            </div>
          </div>
          <div class="form-group row mb-3">
            <div class="col-lg-6">
              <label for="status_id" class="form-label">Trạng thái</label>
              <select class="form-control" id="status_id" name="status" required>
                @php $statuses = ['active','locked']; @endphp
                @foreach ($statuses as $status)
                  <option value="{{ $status }}" @if ($user->status == $status) selected @endif>
                    {{ Str::ucfirst($status) }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-lg-6">
              <label for="email" class="form-label">Địa chỉ e-mail</label>
              <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
            </div>
          </div>

          @if ($user->referrer)
            <div class="form-group row mb-3">
              <div class="col-md-6">
                <label for="referrer" class="form-label">Người giới thiệu</label>
                <input type="text" class="form-control" id="referrer" value="{{ $user->referrer->username }}" readonly>
              </div>
              <div class="col-md-6">
                <label for="referral_percent" class="form-label">Thông tin giới thiệu</label>
                <input type="text" class="form-control" id="referral_percent" name="referral_percent"
                  value="Tổng nạp {{ formatCurrency($user->referrer->total_deposit) }} / Hoa hồng {{ formatCurrency($user->referrer->total_commission) }}" disabled>
              </div>
            </div>
          @endif
          <div class="form-group row mb-3">
            <div class="col-md-3">
              <label for="referral_percent" class="form-label">Hoa hồng giới thiệu</label>
              <input type="number" class="form-control" id="referral_percent" name="referral_percent" value="{{ $user->referral_percent }}" readonly>
            </div>
            <div class="col-md-3">
              <label for="balance_1" class="form-label">Số dư hoa hồng</label>
              <input type="text" class="form-control" id="balance_1" value="{{ number_format($user->balance_1) }}" disabled>
            </div>
            <div class="col-md-6">
              <label for="reset_total_deposit" class="form-label">Reset tổng nạp</label>
              <select class="form-control" id="reset_total_deposit" name="reset_total_deposit" required>
                <option value="0">Không</option>
                <option value="1">Có</option>
              </select>
            </div>

          </div>
          <div class="form-group mb-3">
            <label for="password" class="form-label">Đặt lại mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu nếu cần thay đổi">
          </div>
          <div class="form-group">
            <button class="btn btn-primary w-100" type="submit" name="action" value="update-info">
              <i class="fas fa-edit"></i> Cập Nhật
            </button>
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="card custom-card">
          <div class="card-header justify-content-between">
            <div class="card-title">Cộng tiền thành viên</div>
          </div>
          <div class="card-body">
            <form action="{{ route('admin.users.update', ['id' => $user->id]) }}" method="POST" class="default-form">
              @csrf
              <div class="form-group mb-3">
                <label for="amount" class="form-label">Số tiền (*)</label>
                <input type="number" class="form-control" id="amount" name="amount" placeholder="Nhập số tiền cần cộng" required>
              </div>
              <div class="form-group mb-3">
                <label for="reason" class="form-label">Lý do (*)</label>
                <textarea class="form-control" id="reason" name="reason" placeholder="Nhập lý do cộng tiền nếu có"></textarea>
              </div>
              <div class="form-group">
                <button class="btn btn-success w-100 btn-block" type="submit" name="action" value="plus-money"><i class="fas fa-plus"></i> Cộng Tiền</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card custom-card">
          <div class="card-header justify-content-between">
            <div class="card-title">Trừ tiền thành viên</div>
          </div>
          <div class="card-body">
            <form action="{{ route('admin.users.update', ['id' => $user->id]) }}" method="POST" class="default-form">
              @csrf
              <div class="form-group mb-3">
                <label for="amount" class="form-label">Số tiền (*)</label>
                <input type="number" class="form-control" id="amount" name="amount" placeholder="Nhập số tiền cần trừ" required>
              </div>
              <div class="form-group mb-3">
                <label for="reason" class="form-label">Lý do (*)</label>
                <textarea class="form-control" id="reason" name="reason" placeholder="Nhập lý do trừ tiền nếu có"></textarea>
              </div>
              <div class="form-group">
                <button class="btn btn-danger w-100 btn-block" type="submit" name="action" value="sub-money"><i class="fas fa-minus"></i> Trừ Tiền</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="card custom-card">
      <div class="card-header justify-content-between">
        <div class="card-title">Lịch sử giao dịch [2000 dòng gần nhất]</div>
      </div>
      <div class="card-body">
        <div class="table-responsive theme-scrollbar">
          <table class="display table table-bordered table-stripped text-nowrap datatable">
            <thead>
              <tr>
                <th>#</th>
                <th>Tài khoản</th>
                <th>Giao dịch</th>
                <th>Mã giao dịch</th>
                <th>Số dư trước</th>
                <th>Số tiền</th>
                <th>Số dư sau</th>
                <th>Nội dung</th>
                <th>Trạng thái</th>
                <th>Thời gian</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($user->transactions()->orderBy('id', 'desc')->limit(2000)->get() as $item)
                <tr>
                  <td>{{ $item->id }}</td>
                  <td>{{ $item->username }}</td>
                  <td>{!! Helper::formatTransType($item->type) !!}</td>
                  <td>{{ $item->code }}</td>
                  <td>{{ number_format($item->balance_before) }}</td>
                  <td>{{ $item->prefix . ' ' . number_format($item->amount) }}</td>
                  <td>{{ number_format($item->balance_after) }}</td>
                  <td class="text-wrap">{{ $item->content }} </td>
                  <td>{!! Helper::formatStatus($item->status) !!}</td>
                  <th>{{ $item->created_at }}</th>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="card custom-card">
      <div class="card-header justify-content-between">
        <div class="card-title">Nhật ký hoạt động [2000 dòng gần nhất]</div>
      </div>
      <div class="card-body">
        <div class="table-responsive theme-scrollbar">
          <table class="display table table-bordered table-stripped text-nowrap datatable">
            <thead>
              <tr>
                <th>#</th>
                <th>Tài khoản</th>
                <th>Nội dung</th>
                <th>Dữ liệu</th>
                <th>Địa chỉ IP</th>
                <th>Thời gian</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($user->histories()->orderBy('id', 'desc')->limit(2000)->get() as $item)
                <tr>
                  <td>{{ $item->id }}</td>
                  <td>{{ $item->username }}</td>
                  <td>{{ $item->content }}</td>
                  <td>-</td>
                  <td>{{ $item->ip_address }}</td>
                  <td>{{ $item->created_at }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
@endsection

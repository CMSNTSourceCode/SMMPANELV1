@php use App\Helpers\Helper; @endphp
@extends('admin.layouts.master')
@section('title', 'Admin: Affiliate Management')
@section('content')
  <div class="row">
    <div class="col-12 col-md-4 col-lg-3">
      <div class="card custom-card border-top-card border-top-primary rounded-0">
        <div class="card-body">
          <div class="text-center">
            <p class="fs-14 fw-semibold mb-2">Tổng Tiền Giao Dịch</p>
            <div class="d-flex align-items-center justify-content-center flex-wrap">
              <h4 class="mb-0 fw-semibold">{{ formatCurrency($totalAmount) }}</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4 col-lg-3">
      <div class="card custom-card border-top-card border-top-success rounded-0">
        <div class="card-body">
          <div class="text-center">
            <p class="fs-14 fw-semibold mb-2">Giao Dịch Thành Công</p>
            <div class="d-flex align-items-center justify-content-center flex-wrap">
              <h4 class="mb-0 fw-semibold">{{ formatCurrency($totalAmountCompleted) }}</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4 col-lg-3">
      <div class="card custom-card border-top-card border-top-warning rounded-0">
        <div class="card-body">
          <div class="text-center">
            <p class="fs-14 fw-semibold mb-2">Giao Dịch Đang Chờ</p>
            <div class="d-flex align-items-center justify-content-center flex-wrap">
              <h4 class="mb-0 fw-semibold">{{ formatCurrency($totalAmountPending) }}</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4 col-lg-3">
      <div class="card custom-card border-top-card border-top-danger rounded-0">
        <div class="card-body">
          <div class="text-center">
            <p class="fs-14 fw-semibold mb-2">Giao Dịch Thất Bại</p>
            <div class="d-flex align-items-center justify-content-center flex-wrap">
              <h4 class="mb-0 fw-semibold">{{ formatCurrency($totalAmountCancelled) }}</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Lịch Sử Rút Tiền</div>
    </div>
    <div class="card-body">
      <div class="table-responsive theme-scrollbar">
        <table class="table table-bordered table-striped text-nowrap" id="datatable">
          <thead>
            <tr>
              <th>#</th>
              <th class="text-center">Thao Tác</th>
              <th>Mã Giao Dịch</th>
              <th>Số Tiền</th>
              <th>Số Dư Trước</th>
              <th>Số Dư Sau</th>
              <th>Giao Dịch</th>
              <th>Trạng Thái</th>
              <th>Tài Khoản</th>
              <th>Ghi Chú</th>
              <th>Hệ Thống</th>
              <th>Thời Gian</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($histories as $value)
              <tr>
                <td>{{ $value->id }}</td>
                <td class="text-center">
                  <a href="javscript:void(0)" class="text-danger" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $value->id }}">Chi Tiết</a>
                </td>
                <td>{{ $value->order_id }}</td>
                <td>{{ formatCurrency($value->amount) }}</td>
                <td>{{ formatCurrency($value->balance_before) }}</td>
                <td>{{ formatCurrency($value->balance_after) }}</td>
                <td>{{ $value->prefix === '+' ? 'Cộng Tiền' : 'Trừ Tiền' }}</td>
                <td>{!! $value->status_html !!}</td>
                <td>{{ $value->username }}</td>
                <td>{{ $value->user_note }}</td>
                <td>{{ $value->sys_note }}</td>
                <td>{{ $value->created_at }}</td>
              </tr>
              @php
                $payment = $value->withdraw_detail;
              @endphp
              <div class="modal fade" id="modal-edit-{{ $value->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Cập nhật thông tin #{{ $value->id }}</h5>
                      <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form action="{{ route('admin.affiliates.update') }}" method="POST" enctype="multipart/form-data" class="default-form">
                        @csrf
                        <input type="hidden" name="id" value="{{ $value->id }}">

                        <div class="row mb-3">
                          <div class="col-md-6">
                            <label for="bank_name" class="form-label">Ngân hàng</label>
                            <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ $payment['bank_name'] ?? '-' }}" disabled>
                          </div>
                          <div class="col-md-6">
                            <label for="account_number" class="form-label">Số tài khoản</label>
                            <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $payment['account_number'] ?? '-' }}" disabled>
                          </div>
                        </div>
                        <div class="row mb-3">
                          <div class="col-md-6">
                            <label for="account_name" class="form-label">Chủ tài khoản</label>
                            <input type="text" class="form-control" id="account_name" name="account_name" value="{{ $payment['account_name'] ?? '-' }}" disabled>
                          </div>
                          <div class="col-md-6">
                            <label for="amount" class="form-label">Số tiền cần rút</label>
                            <input type="text" class="form-control" id="amount" name="amount" value="{{ formatCurrency($value->amount) }}" disabled>
                          </div>
                        </div>
                        <div class="row mb-3">
                          <div class="col-md-6">
                            <label for="user_note" class="form-label">Ghi Chú</label>
                            <textarea class="form-control" id="user_note" name="user_note" rows="3">{{ $value->user_note }}</textarea>
                          </div>
                          <div class="col-md-6">
                            <label for="sys_note" class="form-label">Ghi Chú Hệ Thống</label>
                            <textarea class="form-control" id="sys_note" name="sys_note" rows="3" disabled>{{ $value->sys_note }}</textarea>
                          </div>
                        </div>
                        <div class="mb-3">
                          <label for="status" class="form-label">Trạng Thái</label>
                          <select class="form-control" id="status" name="status">
                            <option value="Pending" @if ($value->status === 'Pending') selected @endif>Đang Chờ</option>
                            <option value="Completed" @if ($value->status === 'Completed') selected @endif>Hoàn Thành</option>
                            <option value="Cancelled" @if ($value->status === 'Cancelled') selected @endif>Hủy Bỏ</option>
                          </select>
                        </div>
                        <div class="text-center mb-3">
                          @if ($payment)
                            <img
                              src="https://api.vietqr.io/{{ $payment['bank_name'] }}/{{ $payment['account_number'] }}/{{ $value->amount }}/tt hoa don {{ $value->order_id }}/vietqr_net_2.jpg?accountName={{ $payment['account_name'] }}"
                              width="300" alt="">
                          @endif
                        </div>
                        <div class="mb-3">
                          <button class="btn btn-primary w-100" type="submit">Cập nhật</button>
                        </div>
                      </form>

                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Lịch Sử Nhận Tiền</div>
    </div>
    <div class="card-body">
      <div class="table-responsive theme-scrollbar">
        <table class="table table-bordered table-striped text-nowrap datatable">
          <thead>
            <tr>
              <th>#</th>
              <th>Số Tiền</th>
              <th>Số Dư Trước</th>
              <th>Số Dư Sau</th>
              <th>Giao Dịch</th>
              <th>Trạng Thái</th>
              <th>Tài Khoản</th>
              <th>Ghi Chú</th>
              <th>Hệ Thống</th>
              <th>Thời Gian</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($commissions as $value)
              <tr>
                <td>{{ $value->id }}</td>
                <td>{{ formatCurrency($value->amount) }}</td>
                <td>{{ formatCurrency($value->balance_before) }}</td>
                <td>{{ formatCurrency($value->balance_after) }}</td>
                <td>{{ $value->prefix === '+' ? 'Cộng Tiền' : 'Trừ Tiền' }}</td>
                <td>{!! $value->status_html !!}</td>
                <td>{{ $value->username }}</td>
                <td>{{ $value->user_note }}</td>
                <td>{{ $value->sys_note }}</td>
                <td>{{ $value->created_at }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Danh Sách Người Được Giới Thiệu - Hiện có <span class="text-danger">{{ $referrals->count() }}</span> Người</div>
        </div>
        <div class="card-body">
          <div class="table-responsive theme-scrollbar">
            <table class="display table table-bordered table-stripped text-nowrap datatable">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Tài Khoản</th>
                  <th>Giới Thiệu</th>
                  <th>Tổng Tiền Nạp</th>
                  <th>Tổng Hoa Hồng Nhận</th>
                  <th>Ngày Giới Thiệu</th>
                  <th>Ngày Cập Nhật</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($referrals as $referral)
                  <tr>
                    <td>{{ $referral['id'] }}</td>
                    <td>{{ $referral['username'] }}</td>
                    <td>{{ $referral->to_username }}</td>
                    <td>{{ formatCurrency($referral['total_deposit']) }} đ</td>
                    <td>{{ formatCurrency($referral->total_commission) }}</td>
                    <td>{{ $referral['created_at'] }}</td>
                    <td>{{ $referral['updated_at'] }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Danh sách người dùng đã tham gia chương trình - Hiện có <span class="text-danger">{{ $affiliates->count() }}</span> Người</div>
        </div>
        <div class="card-body">
          <div class="table-responsive theme-scrollbar">
            <table class="display table table-bordered table-stripped text-nowrap datatable">
              <thead>
                <tr>
                  <th>UID</th>
                  <th>Tài Khoản</th>
                  <th>Lượt clicks</th>
                  <th>Lượt đăng ký</th>
                  <th>Ngày tham gia</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($affiliates as $referral)
                  <tr>
                    <td>{{ $referral->id }}</td>
                    <td>{{ $referral->username }}</td>
                    <td>{{ $referral->clicks }}</td>
                    <td>{{ $referral->signups }}</td>
                    <td>{{ $referral->created_at }}</td>
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
      $("#datatable").DataTable({
        responsive: true,
        columnDefs: [{
          orderable: false,
          targets: [0, 1, 2, 3, 4, 5, 6, 7, 8]
        }]
      });
    })
  </script>
@endsection

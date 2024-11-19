@extends('admin.layouts.master')
@section('title', 'Admin: Vouchers Management')
@section('content')
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">
        Quản Lý Mã Khuyến Mãi
      </div>
      <a href="javascript:void(0);" data-bs-toggle="card-fullscreen">
        <i class="ri-fullscreen-line"></i>
      </a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered text-nowrap datatable" style="width:100%">
          <thead>
            <tr>
              <th style="max-width: 25px">STT</th>
              <th style="max-width: 55px">Thao Tác</th>
              <th>Mã KM</th>
              <th>Giá Trị</th>
              <th>Tài Khoản</th>
              <th>Bắt Đầu</th>
              <th>Hết Hạn</th>
              <th>Ngày Thêm</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($vouchers as $voucher)
              <tr>
                <td>{{ $voucher->id }}</td>
                <td>
                  <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $voucher->id }}" class="badge bg-success-gradient text-white shadow"><i class="fas fa-edit"></i></a>
                  <a href="javascript:deleteRow({{ $voucher->id }})" class="badge bg-danger-gradient text-white shadow"><i class="fas fa-trash"></i></a>
                </td>
                <td>{{ $voucher->code }}</td>
                <td>-{{ $voucher->value }}%</td>
                <td>{{ $voucher->username }}</td>
                <td>{{ $voucher->start_date->format('Y/m/d') }}</td>
                <td>{{ $voucher->expire_date->format('Y/m/d') }}</td>
                <td>{{ $voucher->created_at }}</td>
              </tr>

              <div class="modal fade" id="modal-edit-{{ $voucher->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Cập Nhật Mã Khuyến Mãi #{{ $voucher->id }}</h5>
                      <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form action="{{ route('admin.vouchers.update', ['id' => $voucher->id]) }}" method="POST" class="default-form">
                        @csrf
                        <div class="mb-3">
                          <label for="code" class="form-label">Mã Khuyến Mãi</label>
                          <input type="text" class="form-control" id="code" name="code" placeholder="Nhập mã khuyến mãi" value="{{ $voucher->code }}" required>
                        </div>
                        <div class="mb-3">
                          <label for="value" class="form-label">Giá Trị</label>
                          <input type="number" class="form-control" id="value" name="value" placeholder="Nhập giá trị khuyến mãi" value="{{ $voucher->value }}" required>
                        </div>
                        <div class="mb-3">
                          <label for="type" class="form-label">Dịch Vụ</label>
                          <select class="form-select" id="type" name="type">
                            <option value="all" @if ($voucher->type === 'all') selected @endif>Tất Cả</option>
                          </select>
                        </div>
                        <div class="row mb-3">
                          <div class="col-md-6">
                            <label for="start_date" class="form-label">Ngày Bắt Đầu</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $voucher->start_date->format('Y-m-d') }}" required>
                          </div>
                          <div class="col-md-6">
                            <label for="expire_date" class="form-label">Ngày Hết Hạn</label>
                            <input type="date" class="form-control" id="expire_date" name="expire_date" value="{{ $voucher->expire_date->format('Y-m-d') }}" required>
                          </div>
                        </div>
                        <div class="mb-3">
                          <label for="username" class="form-label">Tài Khoản</label>
                          <select class="form-select" id="username" name="username">
                            <option value="">Chọn tài khoản</option>
                            @foreach ($users as $user)
                              <option value="{{ $user->username }}">{{ $user->username }} - {{ $user->email }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="mb-3">
                          <button class="btn btn-primary w-100" type="submit">Cập Nhật Ngay</button>
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
    <div class="card-footer">
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create"><i class="fa fa-edit"></i> Thêm Mã Khuyến Mãi</button>
    </div>
  </div>

  <div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Thêm Mã Khuyến Mãi</h5>
          <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('admin.vouchers.store') }}" method="POST" class="default-form">
            @csrf
            <div class="mb-3">
              <label for="code" class="form-label">Mã Khuyến Mãi</label>
              <input type="text" class="form-control" id="code" name="code" placeholder="Nhập mã khuyến mãi" required>
            </div>
            <div class="mb-3">
              <label for="value" class="form-label">Giá Trị</label>
              <input type="number" class="form-control" id="value" name="value" placeholder="Nhập giá trị khuyến mãi" value="10" required>
            </div>
            <div class="mb-3">
              <label for="type" class="form-label">Dịch Vụ</label>
              <select class="form-select" id="type" name="type">
                <option value="all">Tất Cả</option>
              </select>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="start_date" class="form-label">Ngày Bắt Đầu</label>
                <input type="date" class="form-control" id="start_date" name="start_date" required>
              </div>
              <div class="col-md-6">
                <label for="expire_date" class="form-label">Ngày Hết Hạn</label>
                <input type="date" class="form-control" id="expire_date" name="expire_date" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="username" class="form-label">Tài Khoản</label>
              <select class="form-select" id="username" name="username">
                <option value="">Chọn tài khoản</option>
                @foreach ($users as $user)
                  <option value="{{ $user->username }}">{{ $user->username }} - {{ $user->email }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary w-100" type="submit">Thêm Mới</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    const deleteRow = async (id) => {
      const confirmDelete = await Swal.fire({
        title: 'Bạn có chắc chắn muốn xóa?',
        text: "Bạn sẽ không thể khôi phục lại dữ liệu này!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
      });

      if (!confirmDelete.isConfirmed) return;

      $showLoading();

      try {
        const {
          data: result
        } = await axios.post('{{ route('admin.vouchers.delete') }}', {
          id
        })

        Swal.fire('Thành công', result.message, 'success').then(() => {
          window.location.reload();
        })
      } catch (error) {
        Swal.fire('Thất bại', $catchMessage(error), 'error')
      }
    }
  </script>
@endsection

@extends('admin.layouts.master')
@section('title', 'Admin: Banks Accounts Management')
@section('content')
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Quản lý tài khoản ngân hàng</div>
    </div>
    <div class="card-body">
      <div class="table-responsive theme-scrollbar">
        <table class="display table table-bordered table-stripped text-nowrap datatable">
          <thead>
            <tr>
              <th>#</th>
              <th>Thao tác</th>
              <th>Ảnh / Icon</th>
              <th>QRCode - IMG</th>
              <th>Tên ngân hàng</th>
              <th>Chủ tài khoản</th>
              <th>Số tài khoản</th>
              <th>Chi nhánh</th>
              <th>Trạng thái</th>
              <th>Ngày tạo</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($banks as $item)
              <tr>
                <td>{{ $item->id }}</td>
                <td>
                  <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $item->id }}" class="shadow text-white badge bg-info-gradient"><i class="fa fa-edit"></i></a>
                  <a href="javascript:deleteRow({{ $item->id }})" class="shadow text-white badge bg-danger-gradient"><i class="fa fa-trash"></i></a>
                </td>
                <td>
                  <img src="{{ $item->image }}" width="30" alt="">
                </td>
                <td>
                  <img src="{{ $item->qrcode }}" width="30" alt="">
                </td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->owner }}</td>
                <td>{{ $item->number }}</td>
                <td>{{ $item->branch }}</td>
                <td>{!! $item->status === 1 ? '<span class="badge bg-success">Đang hiện</span>' : '<span class="badge bg-danger">Đang ẩn</span>' !!}</td>
                <td>{{ $item->created_at }}</td>
              </tr>
              <div class="modal fade" id="modal-edit-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Cập nhật thông tin
                        #{{ $item->id }}</h5>
                      <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form action="{{ route('admin.banks.update') }}" method="POST" enctype="multipart/form-data" class="default-form">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="mb-3">
                          <label for="image" class="form-label">Ảnh / Icon</label>
                          <input class="form-control" type="file" id="image" name="image">
                        </div>
                        <div class="mb-3">
                          <label for="qrcode" class="form-label">Link QRCode</label>
                          <input class="form-control" type="text" id="qrcode" name="qrcode" value="{{ $item->qrcode }}">
                        </div>
                        <div class="mb-3">
                          <label for="name" class="form-label">Tên ngân hàng</label>
                          <input class="form-control" type="text" id="name" name="name" value="{{ $item->name }}" required>
                        </div>
                        <div class="mb-3">
                          <label for="number" class="form-label">Số tài khoản</label>
                          <input class="form-control" type="text" id="number" name="number" value="{{ $item->number }}" required>
                        </div>
                        <div class="mb-3">
                          <label for="owner" class="form-label">Chủ tài khoản</label>
                          <input class="form-control" type="text" id="owner" name="owner" value="{{ $item->owner }}" required>
                        </div>
                        <div class="mb-3">
                          <label for="branch" class="form-label">Chi nhánh</label>
                          <input class="form-control" type="text" id="branch" name="branch" value="{{ $item->branch }}">
                        </div>
                        <div class="mb-3">
                          <label for="status" class="form-label">Trạng thái</label>
                          <select class="form-control" id="status" name="status">
                            <option value="1" @if ($item->status === 1) selected @endif>Hoạt
                              động
                            </option>
                            <option value="0" @if ($item->status === 0) selected @endif>Không
                              hoạt động
                            </option>
                          </select>
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
    <div class="card-footer">
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create">Thêm tài khoản mới
      </button>
    </div>
  </div>

  <div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Thêm thông tin mới</h5>
          <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('admin.banks.store') }}" method="POST" enctype="multipart/form-data" class="default-form">
            @csrf
            <div class="mb-3">
              <label for="image" class="form-label">Ảnh / Icon</label>
              <input class="form-control" type="file" id="image" name="image" required>
            </div>
            <div class="mb-3">
              <label for="qrcode" class="form-label">Link QRCode</label>
              <input class="form-control" type="text" id="qrcode" name="qrcode">
            </div>
            <div class="mb-3">
              <label for="name" class="form-label">Tên ngân hàng</label>
              <input class="form-control" type="text" id="name" name="name" required>
            </div>
            <div class="mb-3">
              <label for="number" class="form-label">Số tài khoản</label>
              <input class="form-control" type="text" id="number" name="number" required>
            </div>
            <div class="mb-3">
              <label for="owner" class="form-label">Chủ tài khoản</label>
              <input class="form-control" type="text" id="owner" name="owner" required>
            </div>
            <div class="mb-3">
              <label for="branch" class="form-label">Chi nhánh</label>
              <input class="form-control" type="text" id="branch" name="branch">
            </div>
            <div class="mb-3">
              <label for="status" class="form-label">Trạng thái</label>
              <select class="form-control" id="status" name="status">
                <option value="1">Hoạt động</option>
                <option value="0">Không hoạt động</option>
              </select>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary w-100" type="submit">Thêm mới</button>
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
        } = await axios.post('{{ route('admin.banks.delete') }}', {
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

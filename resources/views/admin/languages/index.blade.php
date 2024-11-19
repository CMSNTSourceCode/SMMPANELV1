@extends('admin.layouts.master')
@section('title', 'Admin: Languages Management')
@section('content')
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Danh Sách Ngôn Ngữ</div>
    </div>
    <div class="card-body">
      <div class="table-responsive theme-scrollbar">
        <table class="table display table-bordered table-stripped text-nowrap datatable">
          <thead>
            <tr>
              <th>#</th>
              <th>{{ __t('Thao tác') }}</th>
              <th>{{ __t('Mã Quốc Gia') }}</th>
              <th>{{ __t('Cờ Quốc Gia') }}</th>
              <th>{{ __t('Tên Ngôn Ngữ') }}</th>
              <th>{{ __t('Mặc Định') }}</th>
              <th>{{ __t('Trạng thái') }}</th>
              <th>{{ __t('Ngày tạo') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($langs as $item)
              <tr>
                <td>{{ $item->id }}</td>
                <td>
                  <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $item->id }}" class="text-white shadow badge bg-info-gradient"><i class="fa fa-edit"></i></a>
                  <a href="{{ route('admin.translations', ['id' => $item->id]) }}" class="text-white shadow badge bg-primary-gradient"><i class="fa fa-list"></i></a>
                  <a href="javascript:deleteRow({{ $item->id }})" class="text-white shadow badge bg-danger-gradient"><i class="fa fa-trash"></i></a>
                </td>
                <td>{{ $item->code }}</td>
                <td>
                  <img src="{{ $item->flag }}" width="30" alt="">
                </td>
                <td>{{ $item->name }}</td>
                <td>{{ setting('primary_lang', 'vn') === $item->code ? 'Có' : 'Không' }}</td>
                <td>{{ $item->status === true ? 'Đang hiện' : 'Đang ẩn' }}</td>
                <td>{{ $item->created_at }}</td>
              </tr>
              <div class="modal fade" id="modal-edit-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Cập nhật thông tin #{{ $item->id }}</h5>
                      <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form action="{{ route('admin.languages.update', ['id' => $item->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="mb-3">
                          <label for="flag" class="form-label">Ảnh / Icon</label>
                          <input class="form-control" type="file" id="flag" name="flag">
                          <small>Hệ thống sẽ tự động cập nhật ảnh tương ứng nếu bạn không chọn</small>
                        </div>
                        <div class="mb-3">
                          <label for="code" class="form-label">Mã Quốc Gia</label>
                          <input class="form-control" type="text" id="code" name="code" value="{{ $item->code }}" required>
                        </div>
                        <div class="mb-3">
                          <label for="name" class="form-label">Tên Ngôn Ngữ</label>
                          <input class="form-control" type="text" id="name" name="name"value="{{ $item->name }}">
                          <small>Nếu không nhập hệ thống sẽ tự động lấy tên theo mã quốc gia</small>
                        </div>
                        <div class="mb-3">
                          <label for="default" class="form-label">Mặc Định</label>
                          <select class="form-control" id="default" name="default">
                            <option value="1" @if ($item->default === true) selected @endif>Có</option>
                            <option value="0" @if ($item->default === false) selected @endif>Không</option>
                          </select>
                        </div>
                        <div class="mb-3">
                          <label for="status" class="form-label">Trạng thái</label>
                          <select class="form-control" id="status" name="status">
                            <option value="1" @if ($item->status === true) selected @endif>Hoạt động</option>
                            <option value="0" @if ($item->status === false) selected @endif>Không hoạt động</option>
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
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create">Thêm ngôn ngữ mới</button>
    </div>
  </div>

  <div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ __t('Thêm thông tin mới') }}</h5>
          <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('admin.languages.store') }}" method="POST" enctype="multipart/form-data" class="default-form">
            @csrf
            <div class="mb-3">
              <label for="flag" class="form-label">{{ __t('Ảnh / Icon') }}</label>
              <input class="form-control" type="file" id="flag" name="flag">
              <small>{{ __t('Hệ thống sẽ tự động cập nhật ảnh tương ứng nếu bạn không chọn') }}</small>
            </div>
            <div class="mb-3">
              <label for="code" class="form-label">{{ __t('Mã Quốc Gia') }}</label>
              <input class="form-control" type="text" id="code" name="code" required>
            </div>
            <div class="mb-3">
              <label for="name" class="form-label">{{ __t('Tên Ngôn Ngữ') }}</label>
              <input class="form-control" type="text" id="name" name="name">
              <small>{{ __t('Nếu không nhập hệ thống sẽ tự động lấy tên theo mã quốc gia') }}</small>
            </div>
            <div class="mb-3">
              <label for="default" class="form-label">{{ __t('Mặc Định') }}</label>
              <select class="form-control" id="default" name="default">
                <option value="1">{{ __t('Có') }}</option>
                <option value="0" selected>{{ __t('Không') }}</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="status" class="form-label">{{ __t('Trạng thái') }}</label>
              <select class="form-control" id="status" name="status">
                <option value="1">{{ __t('Hoạt động') }}</option>
                <option value="0">{{ __t('Không hoạt động') }}</option>
              </select>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary w-100" type="submit">{{ __t('Thêm mới') }}</button>
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
        } = await axios.post('{{ route('admin.languages.delete') }}', {
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

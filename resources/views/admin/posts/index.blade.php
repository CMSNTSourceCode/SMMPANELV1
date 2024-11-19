@extends('admin.layouts.master')
@section('title', 'Admin: Posts Management')
@section('content')
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Quản lý danh sách bài viết</div>
    </div>
    <div class="card-body">
      <div class="table-responsive theme-scrollbar">
        <table class="display table table-bordered table-stripped text-nowrap datatable">
          <thead>
            <tr>
              <th>#</th>
              <th>Thao tác</th>
              <th>Ảnh bìa</th>
              <th>Bài viết</th>
              <th>Trạng thái</th>
              <th>Người viết</th>
              <th>Ngày viết</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($posts as $item)
              <tr>
                <td>{{ $item->id }}</td>
                <td>
                  <a href="{{ route('admin.posts.show', ['id' => $item->id]) }}" class="shadow btn btn-primary btn-sm sharp me-1"><i class="fa fa-pencil"></i> sửa</a>
                  <a href="javascript:deleteRow({{ $item->id }})" class="shadow btn btn-danger btn-sm sharp me-1"><i class="fa fa-trash"></i> xoá</a>
                </td>
                <td>
                  <img src="{{ asset($item->thumbnail) }}" width="25">
                </td>
                <td>{{ $item->title }}</td>
                <td>
                  @if ($item->status === true)
                    <span class="text-success">Đang hiện</span>
                  @else
                    <span class="text-danger">Đang ẩn</span>
                  @endif
                </td>
                <td>{{ $item->author_name }}</td>
                <td>{{ $item->created_at }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer">
      <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">Tạo bài viết mới</a>
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
        } = await axios.post('{{ route('admin.posts.delete') }}', {
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

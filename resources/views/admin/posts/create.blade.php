@extends('admin.layouts.master')
@section('title', 'Admin: Post Create')
@section('content')
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Tạo bài viết mới</div>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="default-form">
        @csrf
        {{-- <div class="mb-3 row">
          <div class="col-md-6">
            <label for="title" class="form-label">Tiêu đề</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
          </div>
          <div class="col-md-6">
            <label for="keywords" class="form-label">Từ khoá</label>
            <input type="text" class="form-control" id="keywords" name="meta_data[keywords]" value="{{ old('keywords') }}">
          </div>
        </div>
        <div class="mb-3">
          <label for="thumbnail" class="form-label">Ảnh bìa</label>
          <input type="file" class="form-control" id="thumbnail" name="thumbnail">
        </div>
        <div class="mb-3">
          <label for="description" class="form-label">Mô tả ngắn</label>
          <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
        </div> --}}
        <div class="mb-3">
          <label for="status" class="form-label">Trạng thái</label>
          <select class="form-select" id="status" name="status" required>
            <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Hiển thị</option>
            <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Ẩn</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="content" class="form-label">Nội dung</label>
          <textarea class="form-control" id="content" name="content" rows="10" required>{{ old('content') }}</textarea>
        </div>
        <div class="mb-3 text-center">
          <button class="btn btn-primary">Thêm bài viết</button>
        </div>
      </form>
    </div>
  </div>
@endsection
@section('scripts')
  <script src="/plugins/ckeditor/ckeditor.js"></script>

  <script>
    $(function() {
      const editor = CKEDITOR.replace('content', {
        extraPlugins: 'notification',
        clipboard_handleImages: false,
        filebrowserImageUploadUrl: '/api/admin/tools/upload?form=ckeditor'
      });

      editor.on('fileUploadRequest', function(evt) {
        var xhr = evt.data.fileLoader.xhr;

        xhr.setRequestHeader('Cache-Control', 'no-cache');
        xhr.setRequestHeader('Authorization', 'Bearer ' + userData.access_token);
      })

    })
  </script>
@endsection

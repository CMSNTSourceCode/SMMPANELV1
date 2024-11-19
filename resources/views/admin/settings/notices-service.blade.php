@extends('admin.layouts.master')
@section('title', 'Admin: Notices Settings')
@section('content')
  @foreach ($services as $service)
    <div class="card custom-card">
      <div class="card-header justify-content-between">
        <div class="card-title">{{ $service['category'] }}: {{ $service['name'] }}</div>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.settings.notices.update', ['type' => $service['category'] . '_' . $service['slug']]) }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="youtube_id" class="form-label">Video Hướng Dẫn</label>
            <input type="text" class="form-control" id="youtube_id" name="youtube_id" value="{{ $notices['ytb_' . $service['category'] . '_' . $service['slug']] ?? '' }}">
          </div>
          <div class="mb-3">
            <textarea class="form-control ckeditor" id="content" name="content" rows="5">{{ $notices[$service['category'] . '_' . $service['slug']] ?? '' }}</textarea>
          </div>
          <div class="mb-3 text-center">
            <button class="btn btn-primary" type="submit">Cập Nhật</button>
          </div>
        </form>
      </div>
    </div>
  @endforeach
@endsection
@section('scripts')
  <script src="/plugins/ckeditor/ckeditor.js"></script>

  <script>
    $(function() {
      const editor = CKEDITOR.replace('.ckeditor', {
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

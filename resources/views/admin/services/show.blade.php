@extends('admin.layouts.master')
@section('title', $pageTitle)
@section('content')
  <div class="card custom-card">
    <div class="card-body">
      <form action="{{ route('admin.services.update', ['id' => $service->id, 'pid' => $service->pid]) }}" method="POST" class="default-form" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
          <label for="name" class="form-label">Tên dịch vụ</label>
          <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $service->name) }}" required>
        </div>
        <div class="mb-3">
          <label for="image" class="form-label">Hình ảnh</label>
          <input class="form-control" type="file" accept="image/*" id="image" name="image" value="{{ old('image') }}">
        </div>
        <div class="mb-3">
          <label for="category" class="form-label">Chuyên mục</label>
          <select name="category_id" id="category_id" class="form-control">
            @foreach ($categories as $category)
              <option value="{{ $category->id }}" @if (old('category_id', $service->category_id) == $category->id) selected @endif>ID {{ $category->id }} : {{ $category->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label for="mode" class="form-label">Chế độ</label>
          <select name="mode" id="mode" class="form-control">
            <option value="api" @if (old('mode', $service->add_type) === 'api') selected @endif>API</option>
            <option value="manual" @if (old('mode', $service->add_type) === 'manual') selected @endif>Manaul</option>
            <option value="option" @if (old('mode', $service->add_type) === 'option') selected @endif>Option</option>
          </select>
        </div>
        <div class="mb-3 card bg-secondary mode_form"></div>
        <div class="mb-3 row">
          <div class="col-md-4">
            <label for="min_buy" class="form-label">Mua ít nhất</label>
            <input type="number" class="form-control" name="min_buy" id="min_buy" value="{{ old('min_buy', $service->min_buy) }}" required>
          </div>
          <div class="col-md-4">
            <label for="max_buy" class="form-label">Mua tối đa</label>
            <input type="number" class="form-control" name="max_buy" id="max_buy" value="{{ old('max_buy', $service->max_buy) }}" required>
          </div>
          <div class="col-md-4">
            <label for="price" class="form-label">Giá của 1000 lượt</label>
            <input type="text" class="form-control" name="price" id="price" value="{{ old('price', $service->price) }}" required>
          </div>
        </div>
        <div class="mb-3">
          <label for="status" class="form-label">Trạng thái</label>
          <select class="form-control" id="status" name="status">
            <option value="1" @if (old('status', $service->status) == 1) selected @endif>Hoạt động</option>
            <option value="0" @if (old('status', $service->status) == 0) selected @endif>Không hoạt động</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="descr" class="form-label">Ghi chú</label>
          <textarea name="descr" id="descr" class="form-control" rows="5">{{ old('descr', $service->descr) }}</textarea>
        </div>
        <div class="mb-3">
          <button class="btn btn-primary w-100" type="submit">Cập nhật</button>
        </div>
      </form>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    $(document).ready(function() {
      const updateMode = () => {
        let mode = $("#mode").val(),
          formContent = '',
          formElement = $(".mode_form");

        let params = {
          provider_id: {{ $service->api_provider_id ?? 'undefined' }},
          service_type: '{{ $service->type ?? 'undefined' }}',
          provider_service_id: {{ $service->api_service_id ?? 'undefined' }}
        }

        $(".mode_form").html('Loading...');

        axios.get(`/admin/services/load-forms/${mode}`, {
          params
        }).then((response) => {
          formContent = response.data;
          formElement.html(formContent);

          $("#api_provider_id").val(params.provider_id);
        }).catch((error) => {
          console.error(error);
        })
      }

      $("#mode").change(() => {
        updateMode();
      })

      updateMode();
    })
  </script>
@endsection

@extends('admin.layouts.master')
@section('title', $pageTitle)
@section('content')
  <div class="mb-3 text-end">
    <button data-bs-toggle="modal" data-bs-target="#modal-create" class="btn btn-outline-primary me-2"><i class="fas fa-plus me-2"></i> {{ __t('Thêm mới') }}</button>
    <button class="btn btn-danger-gradient action-ids" onclick="deleteList()"><i class="fas fa-trash me-2"></i> {{ __t('Xoá') }}</button>
  </div>
  <div class="card custom-card">
    <div class="card-body">
      <div class="table-responsive theme-scrollbar">
        <table class="display table table-bordered table-stripped text-nowrap datatable" id="basic-1">
          <thead>
            <tr>
              <th>#</th>
              <th data-orderable="false" width="10">
                <input type="checkbox" name="checked_all">
              </th>
              <th>{{ __t('Ưu tiên') }}</th>
              <th data-orderable="false" width="10">{{ __t('Thao tác') }}</th>
              <th>{{ __t('Chuyên mục') }}</th>
              <th>{{ __t('Nền tảng') }}</th>
              <th>{{ __t('Trạng thái') }}</th>
              <th>{{ __t('Thời gian thêm') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($categories as $value)
              <tr>
                <td>{{ $value->id }}</td>
                <th>
                  <input type="checkbox" name="checked_ids[]" value="{{ $value->id }}}">
                </th>
                <td>{{ $value->priority }}</td>
                <td>
                  <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modal-update-{{ $value->id }}" class="badge bg-primary-gradient text-white me-2"><i class="fas fa-edit"></i></a>
                  <a href="javascript:void(0)" class="badge bg-danger-gradient" onclick="deleteRow({{ $value->id }})"><i class="fas text-white fa-trash"></i></a>
                </td>
                <td>{{ $value->name }}</td>
                <td>{{ $value->platform->name ?? '-' }}</td>
                <td>
                  @if ($value->status)
                    <span class="badge bg-success">{{ __t('Hoạt động') }}</span>
                  @else
                    <span class="badge bg-danger">{{ __t('Không hoạt động') }}</span>
                  @endif
                </td>
                <td>{{ $value->created_at }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
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
          <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="default-form">
            @csrf
            <div class="mb-3">
              <label for="image" class="form-label">Icon</label>
              <input type="file" class="form-control" name="image" id="image">
            </div>
            <div class="mb-3">
              <label for="name" class="form-label">Tên</label>
              <input class="form-control" type="text" id="name" name="name" required>
            </div>
            <div class="mb-3">
              <label for="priority" class="form-label">Ưu tiên</label>
              <input class="form-control" type="number" id="priority" name="priority" value="0" required>
            </div>
            <div class="mb-3">
              <label for="status" class="form-label">Trạng thái</label>
              <select class="form-control" id="status" name="status">
                <option value="1">Hoạt động</option>
                <option value="0">Không hoạt động</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="platform" class="form-label">Nền tảng</label>
              <select class="form-control" id="platform" name="platform_id" required>
                @foreach ($platforms as $platform)
                  <option value="{{ $platform->id }}">{{ $platform->name }}</option>
                @endforeach
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

  @foreach ($categories as $value)
    <div class="modal fade" id="modal-update-{{ $value->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Cập nhật thông tin #{{ $value->id }}</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('admin.categories.update', ['id' => $value->id]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="mb-3">
                <label for="image" class="form-label">Icon</label>
                <input type="file" class="form-control" name="image" id="image">
              </div>
              <div class="mb-3">
                <label for="name" class="form-label">Tên</label>
                <input class="form-control" type="text" id="name" name="name" value="{{ $value->name }}" required>
              </div>
              <div class="mb-3">
                <label for="priority" class="form-label">Ưu tiên</label>
                <input class="form-control" type="number" id="priority" name="priority" value="{{ $value->priority }}" required>
              </div>
              <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-control" id="status" name="status">
                  <option value="1" @if ($value->status) selected @endif>Hoạt động</option>
                  <option value="0" @if (!$value->status) selected @endif>Không hoạt động</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="platform" class="form-label">Nền tảng</label>
                <select class="form-control" id="platform" name="platform_id" required>
                  @foreach ($platforms as $platform)
                    <option value="{{ $platform->id }}" @if ($value->platform_id === $platform->id) selected @endif>{{ $platform->name }}</option>
                  @endforeach
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
@endsection
@section('scripts')
  <script>
    $("[name=checked_all]").change(function(e) {
      if ($(this).is(":checked")) {
        $("[name='checked_ids[]']").prop("checked", true)
      } else {
        $("[name='checked_ids[]']").prop("checked", false)
      }
    })

    function getIds() {
      let ids = []
      $("[name='checked_ids[]']:checked").each(function() {
        ids.push($(this).val())
      })
      return ids
    }

    // find class actions-ids set disabled with getIds() < 0, and set length checked-ids
    function setActions() {
      let ids = getIds()
      console.log(ids)
      if (ids.length > 0) {
        $(".action-ids").prop("disabled", false)
        $(".checked-ids").text(ids.length)
      } else {
        $(".action-ids").prop("disabled", true)
        $(".checked-ids").text(0)
      }
    }

    $(document)
      .ready(function() {
        setActions();
      })
      .on('change', 'input[name="checked_all"]:enabled', function() {
        setActions();
      })
      .on('change', 'input[name="checked_ids[]"]:enabled', function() {
        setActions();
      })
  </script>
  <script>
    const deleteRow = (id) => {
      Swal.fire({
        title: '{{ __t('Bạn chắc chứ?') }}',
        text: "{{ __t('Bạn sẽ không thể khôi phục lại dữ liệu này!') }}",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '{{ __t('Xóa') }}',
        cancelButtonText: '{{ __t('Hủy') }}'
      }).then((result) => {
        if (result.isConfirmed) {
          $showLoading();

          axios.post('{{ route('admin.categories.delete') }}', {
            id
          }).then(({
            data: result
          }) => {
            Swal.fire('Thành công', result.message, 'success').then(() => {
              window.location.reload();
            })
          }).catch(error => {
            Swal.fire('Thất bại', $catchMessage(error), 'error')
          })
        }
      })
    }

    const deleteList = async () => {
      let ids = getIds()

      if (ids.length === 0) {
        Swal.fire('Thất bại', '{{ __t('Vui lòng chọn ít nhất 1 dòng để xóa') }}', 'error')
        return
      }

      const confirm = await Swal.fire({
        title: '{{ __t('Bạn chắc chứ?') }}',
        text: "{{ __t('Bạn sẽ không thể khôi phục lại dữ liệu này!') }}",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '{{ __t('Xóa') }}',
        cancelButtonText: '{{ __t('Hủy') }}'
      })

      if (!confirm.isConfirmed)
        return

      $showLoading();

      try {
        const {
          data: result
        } = await axios.post('{{ route('admin.categories.delete') }}', {
          ids
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

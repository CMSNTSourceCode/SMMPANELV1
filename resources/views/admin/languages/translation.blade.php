@extends('admin.layouts.master')
@section('title', 'Admin: Translations Management')
@section('content')
  <div class="mb-3 text-end">
    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#addKeyModal"><i class="fas fa-link"></i> {{ __t('Thêm KEY thủ công') }}</button>
    <button class="btn btn-outline-primary" onclick="alert('{{ __t('Chưa hỗ trợ!') }}')"><i class="fas fa-link"></i> {{ __t('Dịch tự động với API') }}</button>
  </div>
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">{{ __t('Chưa Được Dịch') }} - {{ $lang->name }}</div>
    </div>
    <div class="card-body">
      <div class="table-responsive theme-scrollbar">
        <table class="display table table-bordered table-stripped text-nowrap text-center datatable1">
          <thead>
            <tr>
              <th></th>
              <th></th>
              <th>{{ __t('Bản Gốc') }}</th>
              <th>{{ __t('Bản Dịch') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($notTranslates as $key => $value)
              <tr>
                <td>{{ $key }}</td>
                <td>{{ $value }}</td>
                <td style="max-width: 200px">
                  <input type="text" class="form-control" value="{{ $key }}" disabled>
                </td>
                <td style="max-width: 200px">
                  <input type="text" class="form-control" value="{{ $value }}" onchange="update('{{ $key }}', this)">
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">{{ __t('Đã Được Dịch') }} - {{ $lang->name }}</div>
    </div>
    <div class="card-body">
      <div class="table-responsive theme-scrollbar">
        <table class="display table table-bordered table-stripped text-nowrap text-center datatable1">
          <thead>
            <tr>
              <th></th>
              <th></th>
              <th>{{ __t('Bản Gốc') }}</th>
              <th>{{ __t('Bản Dịch') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($allTranslates as $key => $value)
              <tr>
                <td>{{ $key }}</td>
                <td>{{ $value }}</td>
                <td>
                  <input type="text" class="form-control" value="{{ $key }}" disabled>
                </td>
                <td>
                  <input type="text" class="form-control" value="{{ $value }}" onchange="update('{{ $key }}', this)">
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal fade" id="addKeyModal" tabindex="-1" aria-labelledby="addKeyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addKeyModalLabel">{{ __t('Thêm KEY thủ công') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addKeyForm">
            <div class="mb-3">
              <label for="key" class="form-label">{{ __t('Key') }}</label>
              <input type="text" class="form-control" id="key" required>
            </div>
            <div class="mb-3">
              <label for="value" class="form-label">{{ __t('Value') }}</label>
              <input type="text" class="form-control" id="value" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __t('Đóng') }}</button>
          <button type="button" class="btn btn-primary" onclick="addKey()">{{ __t('Thêm') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')

  <script>
    $(document).ready(function() {
      $(".datatable1").DataTable({
        columnDefs: [{
          targets: [0, 1],
          visible: !1
        }]
      })
    });

    const update = (key, el) => {
      axios.post('{{ route('admin.translations.update', ['id' => $lang->id]) }}', {
        index: key,
        value: el.value
      }).then(res => {
        iziToast.success({
          title: 'OK',
          message: key + ' => ' + el.value
        })
      }).catch(err => {
        iziToast.error({
          title: 'Oops...',
          message: $catchMessage(err)
        })
      })
    }

    function addKey() {
      const key = document.getElementById('key').value;
      const value = document.getElementById('value').value;

      axios.post('{{ route('admin.translations.addKey', ['id' => $lang->id]) }}', {
        key: key,
        value: value
      }).then(res => {
        iziToast.success({
          title: 'OK',
          message: res.data.message
        });
        $('#addKeyModal').modal('hide');
        location.reload(); // Reload the page to show the new key
      }).catch(err => {
        iziToast.error({
          title: 'Oops...',
          message: err.response.data.message || $catchMessage(err)
        });
      });
    }
  </script>
@endsection

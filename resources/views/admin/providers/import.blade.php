@extends('admin.layouts.master')
@section('title', $pageTitle)
@section('css')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
@endsection
@section('content')
  @if (request()->has('provider_id') && count($api_services) > 0)
    <div class="mb-3 ">
      <div class="">
        <form action="{{ route('admin.providers.import-services.bulk-store') }}" method="POST" class="axios-form" data-confirm="true">
          @csrf
          <input type="hidden" name="provider_id" value="{{ request()->input('provider_id') }}">
          <div class="d-flex  gap-3 mb-3">
            <div class="">
              <select name="platform_id" id="platform_id" class="form-control js-category" style="height: 38.4px; width: 250px">
                <option value="">{{ __t('Chọn nền tảng') }}</option>
                @foreach ($platforms as $platform)
                  <option value="{{ $platform->id }}">{{ $platform->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="">
              <button type="submit" class="btn btn-outline-danger"><i class="fas fa-database"></i> {{ __t('Nhập dịch vụ và chuyên mục') }}</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  @endif
  <div class="card custom-card">
    <div class="card-body">
      <form action="{{ route('admin.providers.import-services') }}" method="GET">
        <div class="mb-3">
          <label for="provider_id" class="form-label">{{ __t('Nhà cung cấp') }}</label>
          <select name="provider_id" id="provider_id" class="form-control">
            <option value="">{{ __t('Chọn nhà cung cấp') }}</option>
            @foreach ($providers as $value)
              <option value="{{ $value->id }}" @if (request()->input('provider_id', null) == $value->id) selected @endif>ID {{ $value->id }} - {{ $value->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3 text-center">
          <button class="btn btn-primary" type="submit">{{ __t('Tìm kiếm dịch vụ') }}</button>
        </div>
      </form>
    </div>
  </div>
  <div class="card custom-card">
    <div class="card-body">
      <form action="{{ route('admin.providers.import-services.store') }}" method="POST" class="axios-form">
        @csrf

        <input type="hidden" name="provider_id" value="{{ request()->input('provider_id', null) }}">

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="category_id" class="form-label">{{ __t('Dịch vụ') }}</label>
              <select name="category_id" id="category_id" class="form-control js-category">
                <option value="">{{ __t('Chọn chuyên mục') }}</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}">ID {{ $category->id }} - {{ $category->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="price_percentage_increase" class="form-label">Price percentage increase (%) (Auto rounding to 2 decimal places)</label>
              <input type="number" class="form-control" name="price_percentage_increase" id="price_percentage_increase" value="{{ old('price_percentage_increase', 0) }}" required>
            </div>
          </div>
          <div class="mb-3 text-center">
            <button class="btn btn-primary" type="submit">{{ __t('Nhập dịch vụ') }}</button>
          </div>
        </div>

        <div class="card custom-card">
          <div class="card-header">
            <h3 class="card-title">{{ __t('Danh sách dịch vụ') }}</h3>
          </div>
          <div class="card-body">
            @if (count($api_services) === 0)
              <div class="alert alert-warning">{{ __t('Không có dịch vụ nào') }}</div>
            @else
              <div class="table-responsive">
                <table class="table table-hover table-bordered table-stripped text-nowrap" id="datatable">
                  <thead class="text-nowrap">
                    <tr>
                      <th class="text-center" data-orderable="false">
                        <input type="checkbox" name="checked_all">
                      </th>
                      <th>Service ID</th>
                      <th>Service name</th>
                      <th>Category</th>
                      <th class="text-center">Service Type</th>
                      <th class="text-center">Rate per 1k</th>
                      <th class="text-center">Format Rate per 1k</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($api_services as $service)
                      <tr class="tr_{{ $service['service'] }}">
                        <td class="text-center">
                          <input type="checkbox" name="checked_ids[]" value="{{ $service['service'] }}">
                        </td>
                        <td>{{ $service['service'] }}</td>
                        <td>{{ $service['name'] }}</td>
                        <td class="text-muted">{{ $service['category'] }}</td>
                        <td class="text-center text-muted w-10p">{{ $service['type'] }}</td>
                        <td class="text-center w-10p"><strong>{{ number_format($service['rate'], 3) }}</strong></td>
                        <td class="text-center w-10p"><strong>{{ formatCurrencyF($service['rate'] * $provider['exchange_rate']) }}</strong></td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
          </div>
        </div>
      </form>
    </div>
  </div>

@endsection
@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(".js-category").select2({
      placeholder: "{{ __t('Tìm kiếm thông tin') }}",
      allowClear: true,
      dir: "ltr",
      selectionCssClass: 'select2-selection--single',

    });
  </script>
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

    $(document).ready(function() {
      $("#datatable").DataTable({
        "order": [
          [1, "asc"]
        ]
      })
    })
  </script>
@endsection

@php use App\Helpers\Helper; @endphp
@extends('admin.layouts.master')
@section('title', $pageTitle)
@section('content')
  <div class="mb-3 text-end">
    <button data-bs-toggle="modal" data-bs-target="#modal-create" class="btn btn-outline-primary"><i class="fas fa-plus"></i> {{ __t('Thêm mới') }}</button>
  </div>
  <div class="mb-3 text-bold">
    <div class="alert alert-danger">Cập nhật % tăng giá ở <a href="{{ route('admin.settings.currencies') }}">đây</a> (mục: Sử dụng để đồng bộ hóa và thêm hàng loạt dịch vụ).</div>
    <div class="alert alert-danger">Cron tự động đồng bộ giá dịch vụ: <a href="{{ url('/schedule/services/sync') }}" target="_blank">{{ url('/schedule/services/sync') }}</a> [Nhớ bật "Đồng bộ giá" là ON]</div>
  </div>
  <div class="card custom-card">
    <div class="card-body">
      <div class="table-responsive theme-scrollbar">
        <table class="display table table-bordered table-stripped text-nowrap datatable" id="basic-1">
          <thead>
            <tr>
              <th>#</th>
              <th>Tên</th>
              <th>Số dư</th>
              <th>Tỷ giá</th>
              <th>+% Tăng giá</th>
              <th>Quy đổi</th>
              <th>Ghi chú</th>
              <th data-sortable="false" width="30" class="text-center">Trạng thái</th>
              <th data-sortable="false" width="30" class="text-center">Đồng bộ giá</th>
              <th data-sortable="false" width="370">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($records as $value)
              <tr>
                <td>{{ $value->id }}</td>
                <td>{{ $value->name }}</td>
                <td>{{ number_format($value->balance, 2) }} $</td>
                <td>1 = {{ $value->exchange_rate }}</td>
                <td>{{ $value->price_percentage_increase ?? cur_setting('default_price_percentage_increase', 25) }}%</td>
                <td>{{ formatCurrency($value->balance * $value->exchange_rate) }}</td>
                <td>{{ $value->description }}</td>
                <td class="text-center">
                  @if ($value->status)
                    <span class="badge bg-success">{{ __t('Hoạt động') }}</span>
                  @else
                    <span class="badge bg-danger">{{ __t('Không hoạt động') }}</span>
                  @endif
                </td>
                <td>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault{{ $value->id }}" value="{{ $value->id }}" @if ($value->auto_sync) checked @endif
                      onchange="updateAutoSync(this)">
                    <label class="form-check-label" for="flexSwitchCheckDefault{{ $value->id }}"></label>
                  </div>
                </td>
                <td>
                  <button data-bs-toggle="modal" data-bs-target="#modal-update-{{ $value->id }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit me-1"></i> Sửa</button>
                  <form action="{{ route('admin.providers.price-update', ['id' => $value->id]) }}" method="POST" class="d-inline axios-form" data-confirm="1" data-reload="1">
                    @csrf
                    <button class="btn btn-outline-warning btn-sm" type="submit"><i class="fas fa-dollar-sign me-1"></i> {{ __t('Đồng bộ giá') }}</button>
                  </form>
                  <form action="{{ route('admin.providers.balance-update', ['id' => $value->id]) }}" method="POST" class="d-inline axios-form" data-confirm="1" data-reload="1">
                    @csrf
                    <button class="btn btn-outline-success btn-sm" type="submit"><i class="fas fa-wallet me-1"></i> {{ __t('Cập nhật số dư') }}</button>
                  </form>

                  <form action="{{ route('admin.providers.delete', ['id' => $value->id]) }}" method="POST" class="d-inline axios-form" data-confirm="1" data-reload="1">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm" type="submit"><i class="fas fa-trash me-1"></i> {{ __t('Xoá') }}</button>
                  </form>
                </td>
              </tr>

              <div class="modal fade" id="modal-update-{{ $value->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Cập nhật thông tin #{{ $value->id }}</h5>
                      <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="alert alert-danger">
                        Note: This script supports most of all API Providers (API v2) like: vinasmm.com, hqsmartpanel.com etc. So it doesn't support another API provider which have different API Parameters
                      </div>
                      <form action="{{ route('admin.providers.update', ['id' => $value->id]) }}" method="POST" class="axios-form" data-reload="1">
                        @csrf
                        <div class="mb-3">
                          <label for="name" class="form-label">Tên</label>
                          <input class="form-control" type="text" id="name" name="name" value="{{ $value->name }}" required>
                        </div>
                        <div class="mb-3">
                          <label for="url" class="form-label">URL</label>
                          <input class="form-control" type="text" id="url" name="url" value="{{ $value->url }}" required>
                        </div>
                        <div class="mb-3">
                          <label for="key" class="form-label">Key</label>
                          <input class="form-control" type="text" id="key" name="key" value="{{ $value->key }}" required>
                        </div>
                        <div class="row mb-3">
                          <div class="col-md-6">
                            <label for="exchange_rate" class="form-label">Tỷ giá</label>
                            <input class="form-control" type="number" id="exchange_rate" name="exchange_rate" value="{{ $value->exchange_rate }}" required>
                          </div>
                          <div class="col-md-6">
                            <label for="price_percentage_increase" class="form-label">% Tăng giá</label>
                            <input class="form-control" type="number" id="price_percentage_increase" name="price_percentage_increase" value="{{ $value->price_percentage_increase }}" required>
                            <small>% tự động tăng giá từ site nguồn lên</small>
                          </div>
                        </div>
                        <div class="row mb-3">
                          <div class="col-md-6">
                            <label for="rate_per_1k" class="form-label">Định dạng giá từ API</label>
                            <select name="rate_per_1k" id="rate_per_1k" class="form-control">
                              <option value="0" @if (!$value->rate_per_1k) selected @endif>Giá cho 1 lượt</option>
                              <option value="1" @if ($value->rate_per_1k) selected @endif>Giá cho 1k lượt</option>
                            </select>
                            <small>Kiểm tra xem API nguồn trả rate là giá cho 1000 lượt hay 1 lượt nhé</small>
                          </div>
                          <div class="col-md-6">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-control" id="status" name="status">
                              <option value="1" @if ($value->status === true) selected @endif>Hoạt động</option>
                              <option value="0" @if ($value->status === false) selected @endif>Không hoạt động</option>
                            </select>
                          </div>
                        </div>
                        <div class="mb-3">
                          <label for="description" class="form-label">Ghi chú</label>
                          <textarea class="form-control" id="description" name="description" rows="3">{{ $value->description }}</textarea>
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
  </div>

  <div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Thêm thông tin mới</h5>
          <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-danger">
            Lưu ý: Mã nguồn này hỗ trợ hầu hết tất cả các Nhà cung cấp API (API v2) như: app.x999.vn, v.v. Vì vậy, mã nguồn này không hỗ trợ nhà cung cấp API khác có các Thông số API khác nhau
          </div>
          <form action="{{ route('admin.providers.store') }}" method="POST" class="axios-form" data-reload="1">
            @csrf
            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="name" class="form-label">Tên</label>
                <input class="form-control" type="text" id="name" name="name" required>
              </div>
              <div class="col-md-6">
                <label for="type" class="form-label">Loại</label>
                <select class="form-control" id="type" name="type">
                  <option value="standard">Standard</option>
                </select>
              </div>
            </div>
            <div class="mb-3">
              <label for="url" class="form-label">URL</label>
              <input class="form-control" type="text" id="url" name="url" placeholder="https://app.x999.vn/api/v2" required>
            </div>
            <div class="mb-3">
              <label for="key" class="form-label">Key</label>
              <input class="form-control" type="text" id="key" name="key" required>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="exchange_rate" class="form-label">Tỷ giá</label>
                <input class="form-control" type="number" id="exchange_rate" name="exchange_rate" required>
              </div>
              <div class="col-md-6">
                <label for="price_percentage_increase" class="form-label">% Tăng giá</label>
                <input class="form-control" type="number" id="price_percentage_increase" name="price_percentage_increase" required>
                <small>Kiểm tra xem API nguồn trả rate là giá cho 1000 lượt hay 1 lượt nhé</small>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="rate_per_1k" class="form-label">Định dạng giá từ API</label>
                <select name="rate_per_1k" id="rate_per_1k" class="form-control">
                  <option value="0">Giá cho 1 lượt</option>
                  <option value="1" selected>Giá cho 1k lượt</option>
                </select>
                <small>Kiểm tra xem API nguồn trả rate là giá cho 1000 lượt hay 1 lượt nhé</small>
              </div>
              <div class="col-md-6">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-control" id="status" name="status">
                  <option value="1">Hoạt động</option>
                  <option value="0">Không hoạt động</option>
                </select>
              </div>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Ghi chú</label>
              <textarea class="form-control" id="description" name="description" rows="3"></textarea>
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
    const updateAutoSync = (element) => {
      let id = element.value;
      let status = element.checked ? 1 : 0;

      axios.post('{{ route('admin.providers.auto-sync') }}', {
        id: id,
        status: !!status
      }).then((response) => {
        Swal.fire({
          icon: 'success',
          title: 'Thành công',
          text: response.data.message
        })
      }).catch((error) => {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: $catchMessage(error)
        })
      });
    }
  </script>
@endsection

@extends('admin.layouts.master')
@section('title', $pageTitle)
@section('content')
  <div class="mb-3 text-end">
    <button data-bs-toggle="modal" data-bs-target="#modal-create" class="btn btn-outline-primary"><i class="fas fa-plus"></i> {{ __t('Thêm mới') }}</button>
  </div>

  <div class="card custom-card">
    <div class="card-body">
      <div class="alert alert-danger">Chức năng này chỉ có chức năng tự động quy đổi và hiển thị</div>
      <div class="table-responsive theme-scrollbar">
        <table class="display table table-bordered table-stripped text-nowrap datatable" id="basic-1">
          <thead>
            <tr>
              <th>#</th>
              <th>-</th>
              <th>{{ __t('Mã tiền tệ') }}</th>
              <th>{{ __t('Ký hiệu tiền tệ') }}</th>
              <th>{{ __t('Tỷ giá tiền tệ') }}</th>
              <th>{{ __t('Tách đơn vị ngàn') }}</th>
              <th>{{ __t('Phân số thập phân') }}</th>
              <th>{{ __t('Số thập phân của tiền tệ') }}</th>
              <th>{{ __t('Ngày thêm') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($records as $value)
              <tr>
                <td>{{ $value->id }}</td>
                <td>
                  <button data-bs-toggle="modal" data-bs-target="#modal-update-{{ $value->id }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></button>
                  <form action="{{ route('admin.currency-manager.delete', ['id' => $value->id, 'pid' => $value->pid]) }}" method="POST" class="d-inline axios-form" data-confirm="1">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm" type="submit"><i class="fas fa-trash"></i></button>
                  </form>
                </td>
                <td>{{ $value->currency_code }}</td>
                <td>{{ $value->currency_symbol }}</td>
                <td>{{ $value->new_currecry_rate }}</td>
                <td>{{ $value->currency_thousand_separator }}</td>
                <td>{{ $value->currency_decimal_separator }}</td>
                <td>{{ $value->currency_decimal }}</td>
                <td>{{ $value->created_at }}</td>
              </tr>
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
          <form action="{{ route('admin.currency-manager.store') }}" method="POST" class="axios-form" data-reload="1">
            @csrf
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="currency_code" class="form-label">{{ __t('Mã tiền tệ') }}</label>
                <select name="currency_code" id="currency_code" class="form-control">
                  @foreach (currency_codes() as $code => $name)
                    <option value="{{ $code }}" @if (isset($config['currency_code']) ? $config['currency_code'] === $code : false) selected @endif>{{ $code }} - {{ $name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="form-label" for="currency_symbol">{{ __t('Ký hiệu tiền tệ') }}</label>
                  <input class="form-control" name="currency_symbol" id="currency_symbol" value="{{ $config['currency_symbol'] ?? '₫' }}">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="form-label" for="currency_position">{{ __t('Vị trí của ký hiệu tiền tệ') }}</label>
                  <select name="currency_position" id="currency_position" class="form-control square">
                    <option value="left" @if (isset($config['currency_position']) ? $config['currency_position'] === 'left' : false) selected @endif> {{ __t('Trái') }}</option>
                    <option value="right" @if (isset($config['currency_position']) ? $config['currency_position'] === 'right' : false) selected @endif> {{ __t('Phải') }}</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row mb-3">

              <div class="col-md-4">
                <div class="form-group">
                  <label class="form-label" for="currency_thousand_separator">{{ __t('Tách đơn vị ngàn') }}</label>
                  <select name="currency_thousand_separator" id="currency_thousand_separator" class="form-control square">
                    <option value="dot" @if (isset($config['currency_thousand_separator']) ? $config['currency_thousand_separator'] === 'dot' : false) selected @endif> Dot</option>
                    <option value="comma" @if (isset($config['currency_thousand_separator']) ? $config['currency_thousand_separator'] === 'comma' : false) selected @endif> Comma</option>
                    <option value="space" @if (isset($config['currency_thousand_separator']) ? $config['currency_thousand_separator'] === 'space' : false) selected @endif> Space</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="currency_decimal_separator" class="form-label">{{ __t('Phân số thập phân') }}</label>
                  <select name="currency_decimal_separator" id="currency_decimal_separator" class="form-control square">
                    <option value="dot" @if (isset($config['currency_decimal_separator']) ? $config['currency_decimal_separator'] === 'dot' : false) selected @endif> Dot</option>
                    <option value="comma" @if (isset($config['currency_decimal_separator']) ? $config['currency_decimal_separator'] === 'comma' : false) selected @endif> Comma</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="form-label" for="currency_decimal">{{ __('Số thập phân của tiền tệ') }}</label>
                  <select name="currency_decimal" id="currency_decimal" class="form-control square">
                    <option value="0" @if (isset($config['currency_decimal']) ? $config['currency_decimal'] === '0' : false) selected @endif> 0</option>
                    <option value="1" @if (isset($config['currency_decimal']) ? $config['currency_decimal'] === '1' : false) selected @endif> 0.0</option>
                    <option value="2" @if (isset($config['currency_decimal']) ? $config['currency_decimal'] === '2' : false) selected @endif> 0.00</option>
                    <option value="3" @if (isset($config['currency_decimal']) ? $config['currency_decimal'] === '3' : false) selected @endif> 0.000</option>
                    <option value="4" @if (isset($config['currency_decimal']) ? $config['currency_decimal'] === '4' : false) selected @endif> 0.0000</option>
                  </select>
                </div>
              </div>

            </div>

            <div class="mb-3">
              <label class="form-label" for="new_currecry_rate">{{ __t('Tỷ giá tiền tệ') }}</label>
              <input type="number" class="form-control" name="new_currecry_rate" id="new_currecry_rate" value="{{ $config['new_currecry_rate'] ?? 1 }}" required>
              <small class="text-muted"><span class="text-danger">*</span> {{ __t('Nếu bạn không muốn thay đổi Tỷ giá tiền tệ thì hãy để trường tỷ giá tiền tệ này thành 1') }}</small>
            </div>

            <div class="mb-3">
              <button class="btn btn-primary w-100" type="submit">Thêm mới</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  @foreach ($records as $value)
    <div class="modal fade" id="modal-update-{{ $value->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Cập nhật thông tin #{{ $value->id }}</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('admin.currency-manager.update', ['id' => $value->id]) }}" method="POST" class="axios-form" data-reload="1">
              @csrf
              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="currency_code" class="form-label">{{ __t('Mã tiền tệ') }}</label>
                  <select name="currency_code" id="currency_code" class="form-control">
                    @foreach (currency_codes() as $code => $name)
                      <option value="{{ $code }}" @if ($value->currency_code === $code) selected @endif>{{ $code }} - {{ $name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="form-label" for="currency_symbol">{{ __t('Ký hiệu tiền tệ') }}</label>
                    <input class="form-control" name="currency_symbol" id="currency_symbol" value="{{ $value->currency_symbol }}">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="form-label" for="currency_position">{{ __t('Vị trí của ký hiệu tiền tệ') }}</label>
                    <select name="currency_position" id="currency_position" class="form-control square">
                      <option value="left" @if ($value->currency_position === 'left') selected @endif> {{ __t('Trái') }}</option>
                      <option value="right" @if ($value->currency_position === 'right') selected @endif> {{ __t('Phải') }}</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row mb-3">

                <div class="col-md-4">
                  <div class="form-group">
                    <label class="form-label" for="currency_thousand_separator">{{ __t('Tách đơn vị ngàn') }}</label>
                    <select name="currency_thousand_separator" id="currency_thousand_separator" class="form-control square">
                      <option value="dot" @if ($value->currency_thousand_separator === 'dot') selected @endif> Dot</option>
                      <option value="comma" @if ($value->currency_thousand_separator === 'comma') selected @endif> Comma</option>
                      <option value="space" @if ($value->currency_thousand_separator === 'space') selected @endif> Space</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="currency_decimal_separator" class="form-label">{{ __t('Phân số thập phân') }}</label>
                    <select name="currency_decimal_separator" id="currency_decimal_separator" class="form-control square">
                      <option value="dot" @if ($value->currency_decimal_separator === 'dot') selected @endif> Dot</option>
                      <option value="comma" @if ($value->currency_decimal_separator === 'comma') selected @endif> Comma</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="form-label" for="currency_decimal">{{ __('Số thập phân của tiền tệ') }}</label>
                    <select name="currency_decimal" id="currency_decimal" class="form-control square">
                      <option value="0" @if ($value->currency_decimal == '0') selected @endif> 0</option>
                      <option value="1" @if ($value->currency_decimal == '1') selected @endif> 0.0</option>
                      <option value="2" @if ($value->currency_decimal == '2') selected @endif> 0.00</option>
                      <option value="3" @if ($value->currency_decimal == '3') selected @endif> 0.000</option>
                      <option value="4" @if ($value->currency_decimal == '4') selected @endif> 0.0000</option>
                    </select>
                  </div>
                </div>

              </div>

              <div class="mb-3">
                <label class="form-label" for="new_currecry_rate">{{ __t('Tỷ giá tiền tệ') }}</label>
                <input type="number" class="form-control" name="new_currecry_rate" id="new_currecry_rate" value="{{ $value->new_currecry_rate }}" required>
                <small class="text-muted"><span class="text-danger">*</span> {{ __t('Nếu bạn không muốn thay đổi Tỷ giá tiền tệ thì hãy để trường tỷ giá tiền tệ này thành 1') }}</small>
              </div>

              <div class="mb-3">
                <button class="btn btn-primary w-100" type="submit">Thêm mới</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endforeach
@endsection

@php use App\Helpers\Helper; @endphp
@extends('layouts.app')
@section('title', __t($pageTitle))
@section('content')
  <section class="row">
    <div class="col-12 col-sm-12 col-md-6 gap-3">
      <div class="card custom-card">
        <div class="card-header">
          <h3 class="card-title">{{ __t('Nạp Tiền Bằng Perfect Money') }}</h3>
        </div>
        <div class="card-body">
          <div class="d-flex justify-content-center mt-5 mb-3">
            <img src="/images/svg-icon/dollar.svg" style="width: 200px">
          </div>
          <div>
            <form action="{{ $params['API_URL'] }}" method="POST" id="form">
              <input type="hidden" name="SUGGESTED_MEMO" value="<?= $params['SUGGESTED_MEMO'] ?>">
              <input type="hidden" name="PAYMENT_ID" value="<?= $params['PAYMENT_ID'] ?>" />
              <input type="hidden" name="PAYEE_ACCOUNT" value="<?= $params['PAYEE_ACCOUNT'] ?>" />
              <input type="hidden" name="PAYMENT_UNITS" value="<?= $params['PAYMENT_UNITS'] ?>" />
              <input type="hidden" name="PAYEE_NAME" value="<?= $params['PAYEE_NAME'] ?>" />
              <input type="hidden" name="PAYMENT_URL" value="<?= $params['PAYMENT_URL'] ?>" />
              <input type="hidden" name="PAYMENT_URL_METHOD" value="LINK" />
              <input type="hidden" name="NOPAYMENT_URL" value="<?= $params['NOPAYMENT_URL'] ?>" />
              <input type="hidden" name="NOPAYMENT_URL_METHOD" value="LINK" />
              <input type="hidden" name="STATUS_URL" value="<?= $params['STATUS_URL'] ?>" />
              <div class="mb-3">
                <label for="PAYMENT_AMOUNT" class="form-label" data-key="dp-nhap-so-tien">{{ __t('Nhập Số Tiền: (USD)') }}</label>
                <input type="number" class="form-control" id="PAYMENT_AMOUNT" name="PAYMENT_AMOUNT" value="{{ old('PAYMENT_AMOUNT', 1) }}" required>
              </div>
              <div class="mb-3 text-center">
                <button class="btn btn-primary w-100" type="submit"><i class="fas fa-share"></i> <span data-key="dp-thuc-hien-ngay">{{ __t('Thực Hiện Ngay') }}</span></button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-12 col-md-6 gap-3">
      <div class="card custom-card">
        <div class="card-header">
          <h3 class="card-title">{{ __t('Lưu ý Nạp tiền') }}</h3>
        </div>
        <div class="card-body">
          {!! Helper::getNotice('page_deposit_pmoney') !!}
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card custom-card">
        <div class="card-header">
          <h3 class="card-title">{{ __t('Danh Sách Hoá Đơn') }}</h3>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered text-nowrap datatable">
              <thead>
                <tr>
                  <th>
                    Id
                  </th>

                  <th>
                    {{ __t('Thao tác') }}
                  </th>

                  <th>
                    {{ __t('Mã Giao Dịch') }}
                  </th>

                  <th>
                    {{ __t('Số Tiền') }}
                  </th>

                  <th>
                    {{ __t('Ghi Chú') }}
                  </th>

                  <th>
                    {{ __t('Trạng Thái') }}
                  </th>

                  <th>
                    {{ __t('Thời Gian') }}
                  </th>

                  <th>
                    {{ __t('Cập Nhật') }}
                  </th>

                </tr>
              </thead>
              <tbody>
                @foreach ($invoices as $item)
                  <tr>
                    <td>{{ $item->id }}</td>
                    <td>
                      @if ($item->status === 'processing')
                        <a class="text-primary" href="{{ $item->payment_details['url_payment'] ?? '#!' }}" target="_blank"><i class="fas fa-share"></i> <span>{{ __t('Thanh Toán') }}</span></a>
                      @endif
                    </td>
                    <td>{{ $item->code }}</td>
                    <td>{{ Helper::formatCurrency($item->amount) }}</td>
                    <td>
                      <span class="text-wrap">{{ $item->description }}</span>
                    </td>
                    <td>{!! Helper::formatStatus($item->status) !!}</td>
                    <td>{{ $item->created_at }}</td>
                    <td>{{ $item->updated_at }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
@push('scripts')
  <script>
    $(document).ready(() => {
      $("#form").submit(function() {
        $(this).find(":submit").attr('disabled', 'disabled');
      });
    })
  </script>
@endpush

@php use App\Helpers\Helper; @endphp
@extends('layouts.app')
@section('title', __t($pageTitle))
@section('content')

  <div class="row">
    <div class="col-md-6">
      <div class="card custom-card">
        <div class="card-header">
          <h3 class="card-title">{{ __t('Nạp tiền') }}</h3>
        </div>
        <div class="card-body">
          <div class="text-center mb-3">
            <img src="/images/svg-icons/paypal.svg" style="width: 180px">
          </div>
          <div>
            <form action="/api/accounts/invoices" method="POST" id="form">
              <input type="hidden" name="channel" id="channel" value="fpayment">
              <div class="mb-3">
                <label for="amount" class="form-label" data-key="dp-nhap-so-tien">{{ __t('Nhập số tiền') }}: (USD)</label>
                <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount', 1) }}" required>
              </div>
              <div class="mb-3 text-center">
                <script src="https://www.paypal.com/sdk/js?client-id={{ $config['client_id'] ?? '' }}&currency=USD"></script>
                <div id="paypal-button-container"></div>
                {{-- <button class="btn btn-primary w-100" type="submit"><i class="fas fa-share"></i> <span data-key="dp-thuc-hien-ngay">{{ __t('Thanh Toán Ngay') }}</span></button> --}}
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-body flex flex-col p-6">
          <header class="-mx-6 mb-5 flex items-center border-b border-slate-100 px-6 pb-5 dark:border-slate-700">
            <div class="flex-1">
              <div class="card-title text-slate-900 dark:text-white">{{ __t('Lưu Ý Khi Nạp') }}</div>
            </div>
          </header>
          <div class="card-text h-full space-y-4">
            {!! Helper::getNotice('page_deposit_paypal') !!}
          </div>
        </div>
      </div>
    </div>
    <div class="col-12">
      <div class="card custom-card">
        <div class="card-header">
          <h4 class="card-title">{{ __t('Danh Sách Hoá Đơn') }}</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-hover datatable" id="data-table">
              <thead>
                <tr>
                  <th scope="col">Id</th>
                  <th scope="col">{{ __t('Thao tác') }}</th>
                  <th scope="col">{{ __t('Mã Giao Dịch') }}</th>
                  <th scope="col">{{ __t('Số Tiền') }}</th>
                  <th scope="col">{{ __t('Ghi Chú') }}</th>
                  <th scope="col">{{ __t('Trạng Thái') }}</th>
                  <th scope="col">{{ __t('Thời Gian') }}</th>
                  <th scope="col">{{ __t('Cập Nhật') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($invoices as $item)
                  <tr>
                    <td>{{ $item->id }}</td>
                    <td>
                      @if ($item->status === 'processing')
                        <a class="btn btn-primary btn-sm" href="{{ $item->payment_details['url_payment'] ?? '#!' }}" target="_blank">
                          <i class="fas fa-share"></i> {{ __t('Thanh Toán') }}
                        </a>
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
  </div>
@endsection
@push('scripts')
  <script>
    (function($) {
      paypal.Buttons({

        // Sets up the transaction when a payment button is clicked
        createOrder: function(data, actions) {
          return actions.order.create({
            purchase_units: [{
              amount: {
                value: $('#amount')
                  .val() // Can reference variables or functions. Example: `value: document.getElementById('...').value`
              }
            }]
          });
        },

        // Finalize the transaction after payer approval
        onApprove: function(data, actions) {
          return actions.order.capture().then(function(orderData) {
            axios.post('/api/deposit/paypal-confirm', orderData).then(({
              data: result
            }) => {

              Swal.fire('Thành công', result.message, 'success').then(() => {
                window.location.reload();
              })
            }).catch(error => {
              Swal.fire('Thất bại', $catchMessage(error), 'error')
            })
          });
        }
      }).render('#paypal-button-container');
    })(jQuery)
  </script>
@endpush

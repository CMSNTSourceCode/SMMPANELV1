@php use App\Helpers\Helper; @endphp

@extends('layouts.app')
@section('title', __t('Nạp tiền bằng tiền mã hoá'))

@section('content')
  <div class="row">
    <div class="col-12 col-lg-6 col-md-6 col-sm-12">
      <div class="card custom-card">
        <div class="card-header">
          <h3 class="card-title">{{ __t('Nạp tiền') }}</h3>
        </div>
        <div class="card-body">
          <div class="text-center">
            <img src="/images/svg-icons/trader.svg" alt="crypto" class="img-fluid" width="220">
          </div>
          <form action="/api/users/invoices" method="POST" id="form" class="tf-form">
            @csrf
            <input type="hidden" name="channel" id="channel" value="fpayment">
            <div class="mb-3">
              <label for="amount" class="form-label">{{ __t('Số Tiền Cần Nạp (USD)') }}</label>
              <input name="amount" id="amount" value="1" required class="form-control" type="number">
              <span class="icon-clear"></span>
            </div>
            <div class="mt-3">
              <button type="submit" class="btn btn-primary w-100">{{ __t('Thanh Toán Ngay') }}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-6 col-md-6 col-sm-12">
      <div class="card custom-card">
        <div class="card-header">
          <h3 class="card-title">{{ __t('Hướng dẫn nạp tiền') }}</h3>
        </div>
        <div class="card-body">
          {!! Helper::getNotice('page_deposit_crypto') !!}
        </div>
      </div>
    </div>
    <div class="col-12">
      <div class="card custom-card">
        <div class="card-header">
          <h3 class="card-title">{{ __t('Lịch sử nạp tiền') }}</h3>
        </div>
        <div class="card-body">
          <div class="table-responsive df-example demo-table">
            <table class="table table-bordered table-striped text-nowrap" id="datatable1">
              <thead>
                <tr>
                  <th style="width: 20px">ID</th>
                  <th>{{ __t('Thao tác') }}</th>
                  <td>{{ __t('Mã Giao Dịch') }}</td>
                  <th>{{ __t('Số tiền') }}</th>
                  <th>{{ __t('Trạng thái') }}</th>
                  <th>{{ __t('Thời gian') }}</th>
                  <th>{{ __t('Cập nhật') }}</th>
                  <th>{{ __t('Ghi chú') }}</th>
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
                    <td>{{ formatCurrency($item->amount) }}</td>
                    <td>{!! Helper::formatStatus($item->status) !!}</td>
                    <td>{{ $item->created_at }}</td>
                    <td>{{ $item->updated_at }}</td>
                    <td>{{ $item->description }}</td>
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

@section('scripts')
  <script>
    $(document).ready(() => {
      $("#form").submit(async (e) => {
        e.preventDefault();

        const amount = $("#amount").val(),
          channel = $("#channel").val(),
          button = $(e.target).find("button"),
          action = $(e.target).attr("action");

        if (amount < 1) {
          return $swal("error", "{{ __t('Số tiền nạp tối thiểu là 1 USD') }}");
        }

        $setLoading(button);

        try {
          const {
            data: result
          } = await axios.post(action, {
            amount,
            channel
          });

          $swal("success", result.message).then(() => {
            location.href = result.data.payment_url
          });
        } catch (error) {
          $swal("error", $catchMessage(error));
        } finally {
          $removeLoading(button);
        }
      })

      $("#datatable1").DataTable({
        "order": [
          [0, "desc"]
        ]
      });
    })
  </script>
@endsection

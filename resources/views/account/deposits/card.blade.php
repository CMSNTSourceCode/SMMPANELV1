@php use App\Helpers\Helper; @endphp

@extends('layouts.app')
@section('title', $pageTitle)

@section('content')
  <div class="row">
    <div class="col-md-6">
      <div class="card custom-card">
        <div class="card-header">
          <h3 class="card-title">{{ __t('Nạp thẻ cào') }}</h3>
        </div>
        <div class="card-body">
          <form action="/api/users/send-card" method="POST" id="form-sendcard" class="tf-form">
            <div class="mb-3">
              <label for="telco" class="form-label">{{ __t('Loại thẻ') }}</label>
              <select name="telco" id="telco" class="form-select">
                <option value="VIETTEL" data-fees="{{ $card_fees['VIETTEL'] ?? 30 }}">Viettel ({{ $card_fees['VIETTEL'] ?? 30 }}%)</option>
                <option value="VINAPHONE" data-fees="{{ $card_fees['VINAPHONE'] ?? 30 }}">Vinaphone ({{ $card_fees['VINAPHONE'] ?? 30 }}%)</option>
                <option value="MOBIFONE" data-fees="{{ $card_fees['MOBIFONE'] ?? 30 }}">Mobifone ({{ $card_fees['MOBIFONE'] ?? 30 }}%)</option>
                <option value="ZING" data-fees="{{ $card_fees['ZING'] ?? 30 }}">Zing ({{ $card_fees['ZING'] ?? 30 }}%)</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="amount" class="form-label">{{ __t('Mệnh giá') }}</label>
              <select name="amount" id="amount" class="form-select">
                <option value="10000">10.000</option>
                <option value="20000">20.000</option>
                <option value="30000">30.000</option>
                <option value="50000">50.000</option>
                <option value="100000">100.000</option>
                <option value="200000">200.000</option>
                <option value="300000">300.000</option>
                <option value="500000">500.000</option>
                <option value="1000000">1.000.000</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="serial" class="form-label">{{ __t('Số serial') }}</label>
              <input type="text" class="form-control" id="serial" name="serial" placeholder="{{ __t('Nhập số serial') }}" required>
            </div>
            <div class="mb-3">
              <label for="code" class="form-label">{{ __t('Mã thẻ') }}</label>
              <input type="text" class="form-control" id="code" name="code" placeholder="{{ __t('Nhập mã thẻ') }}" required>
            </div>
            <div class="text-center mb-3">
              <h3 class="real_amount text-danger">0 đ</h3>
              <span class="">{{ __t('Nhận được') }}
            </div>
            <div class="mb-3">
              <button class="btn btn-primary w-100">{{ __t('Nạp thẻ ngay') }}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card custom-card">
        <div class="card-header">
          <h3 class="card-title">{{ __t('Hướng dẫn nạp tiền') }}</h3>
        </div>
        <div class="card-body">
          {!! Helper::getNotice('page_deposit_card') !!}
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card custom-card">
        <div class="card-header">
          <h3 class="card-title">{{ __t('Lịch sử nạp thẻ') }}</h3>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered text-nowrap datatable" style="width:100%">
              <thead>
                <tr>
                  <th>{{ __t('Nhà mạng') }}</th>
                  <th>Serial</th>
                  <th>Pin</th>
                  <th>{{ __t('Mệnh giá') }}</th>
                  <th>{{ __t('Thực nhận') }}</th>
                  <th>{{ __t('Trạng thái') }}</th>
                  <th>{{ __t('Ngày nạp') }}</th>
                  <th>{{ __t('Ngày cập nhật') }}</th>
                  <th>{{ __t('Ghi chú') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($invoices as $value)
                  <tr>
                    <td>{{ $value->type }}</td>
                    <td>{{ $value->serial }}</td>
                    <td>{{ $value->code }}</td>
                    <td>{{ formatCurrency($value->value) }}</td>
                    <td>{{ formatCurrency($value->amount) }}</td>
                    <td>{!! Helper::formatStatus($value->status) !!}</td>
                    <td>{{ $value->created_at }}</td>
                    <td>{{ $value->updated_at }}</td>
                    <td>{{ $value->content }}</td>
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
    $(document).ready(function() {
      $("#amount").change(function() {
        let amount = $(this).val();
        let fees = $("#telco").find(':selected').data('fees');
        let real_amount = amount - (amount * fees / 100);
        $(".real_amount").text($formatCurrency(real_amount));
      });

      $("#telco").change(function() {
        let amount = $("#amount").val();
        let fees = $(this).find(':selected').data('fees');
        let real_amount = amount - (amount * fees / 100);
        $(".real_amount").text($formatCurrency(real_amount));
      });

      $("#form-sendcard").submit(function(e) {
        e.preventDefault();
        onSubmit();
      });

      $("#amount").change();
    });

    const onSubmit = async () => {

      let form = document.getElementById('form-sendcard');

      const payload = {
        telco: $("#telco").val(),
        amount: $("#amount").val(),
        serial: $("#serial").val(),
        code: $("#code").val(),
      };

      if (!payload.serial || !payload.code) {
        return Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: '{{ __t('Vui lòng nhập đầy đủ thông tin') }}',
        });
      }

      Swal.fire({
        icon: 'info',
        title: 'Processing...',
        text: '{{ __t('Vui lòng đợi xử lý, không được tắt trang!') }}',
        padding: '2em',
        customClass: 'sweet-alerts',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading()
        },
      })

      try {
        const {
          data: result
        } = await axios.post(form.action, payload);

        Swal.fire({
          icon: 'success',
          title: 'Great!',
          text: result.message,
        }).then(() => {
          window.location.reload()
        })
      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: $catchMessage(error),
        })
      }
    }
  </script>
@endsection

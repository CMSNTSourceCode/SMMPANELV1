@php use App\Helpers\Helper; @endphp

@extends('layouts.app')
@section('title', __t('Nạp tiền tài khoản'))
@section('css')
  <style>
    .bank__info {
      margin-bottom: 10px;
      display: flex;
      font-size: 25px;
      font-weight: bold;
      justify-content: space-between;
    }

    .qr-payment {
      margin-top: 10px;
      width: 100%;
    }
  </style>
@endsection
@section('content')
  <div class="row">

    <div class="col-md-6">
      <div class="card custom-card">
        <div class="card-header">
          <h3 class="card-title">{{ __t('Hướng dẫn nạp tiền') }}</h3>
        </div>
        <div class="card-body">
          {!! Helper::getNotice('page_deposit') !!}
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card custom-card">
        <div class="card-body">
          <nav class="nav nav-style-6 nav-pills mb-3 nav-justified d-sm-flex d-block" role="tablist">
            @foreach ($banks as $key => $row)
              <a class="nav-link @if ($key == 0) active @endif" data-bs-toggle="tab" role="tab" aria-current="page" href="#nav-pay_{{ $row->id }}"
                aria-selected="@if ($key == 0) true @else false @endif">
                <img src="{{ $row->image }}" width="18" style="margin-bottom: 3px" class="me-2"> {{ $row->name }}
              </a>
            @endforeach
          </nav>
          <div class="tab-content">
            @foreach ($banks as $key => $item)
              <div class="tab-pane @if ($key == 0) show active @endif text-muted" id="nav-pay_{{ $item->id }}" role="tabpanel">
                <div class="box">
                  <div class="text-center mb-3">
                    @if (str_contains(strtolower($item->name), 'momo'))
                      <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=2|99|{{ $item->number }}|||0|0|10000|{{ $deposit_prefix }}|transfer_myqr" style="width: 250px">
                    @else
                      <img src="https://api.vietqr.io/{{ $item->name }}/{{ $item->number }}/0/{{ $deposit_prefix }}/vietqr_net_2.jpg?accountName={{ $item->owner }}" style="width: 250px">
                    @endif
                  </div>
                  <div class="text-center fw-bold">
                    <div class="bank-info">
                      <span>{{ __t('Ngân hàng') }}:</span>
                      <span class="text-danger">{{ $item->name }}</span>
                    </div>
                    <div class="bank-info">
                      <span>{{ __t('Số tài khoản') }}:</span>
                      <span class="text-danger">
                        {{ $item->number }} <a href="javascript:void(0)" class="copy" data-clipboard-text="{{ $item->number }}"><i class="fas fa-copy"></i></a>
                      </span>
                    </div>
                    <div class="bank-info">
                      <span>{{ __t('Chủ tài khoản') }}:</span>
                      <span class="text-danger">{{ $item->owner }}</span>
                    </div>
                    <div class="bank-info">
                      <span>{{ __t('Nội dung chuyển') }}:</span>
                      <span class="text-danger">{{ $deposit_prefix }} <a href="javascript:void(0)" class="copy" data-clipboard-text="{{ $deposit_prefix }}"><i class="fas fa-copy"></i></a></span>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
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
            <table class="table table-bordered table-striped datatable text-nowrap" id="datatable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>{{ __t('Tài khoản') }}</th>
                  <th>{{ __t('Số tiền') }}</th>
                  <th>{{ __t('Số dư trước') }}</th>
                  <th>{{ __t('Số dư sau') }}</th>
                  <th>{{ __t('Nội dung') }}</th>
                  <th>{{ __t('Thời gian') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($transactions as $item)
                  <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->username }}</td>
                    <td>{{ $item->prefix }} {{ formatCurrency($item->amount) }}</td>
                    <td>{{ formatCurrency($item->balance_before) }}</td>
                    <td>{{ formatCurrency($item->balance_after) }}</td>
                    <td class="text-wrap">{{ $item->content }}</td>
                    <td>{{ $item->created_at }}</td>
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

@extends('layouts.app')
@section('title', $pageTitle)
@section('content')
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered text-nowrap text-center fw-bold">
          <thead>
            <tr>
              <th>{{ __t('ID') }}</th>
              <th class="text-start">{{ __t('Tên dịch vụ') }}</th>
              <th>{{ __t('Rate per 1000') }}</th>
              <th>{{ __t('Min order') }}</th>
              <th>{{ __t('Max order') }}</th>
              <th>{{ __t('Average time') }}</th>
              <th>{{ __t('Description') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($groupped as $key => $value)
              <tr class="text-start">
                <td colspan="8">
                  <h3 class="fw-bold">{{ $key }}</h3>
                </td>
              </tr>
              @foreach ($value as $row)
                <tr>
                  <td>{{ $row->id }}</td>
                  <td class="text-start">{{ $row->name }}</td>
                  <td>{{ formatCurrency($row->price) }}</td>
                  <td>{{ number_format($row->min_buy) }}</td>
                  <td>{{ number_format($row->max_buy) }}</td>
                  <td>{{ $row->average_time }}</td>
                  <td>
                    @if ($row->descr)
                      <button type="button" class="btn btn-sm btn-primary"
                        onclick="Swal.fire({
                        html: '{{ $row->descr }}',
                        confirmButtonText: '{{ __t('Close') }}'
                      })">
                        {{ __t('Xem') }}
                      </button>
                    @else
                      -
                    @endif
                  </td>
                </tr>
              @endforeach
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection

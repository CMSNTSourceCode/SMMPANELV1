@php use App\Helpers\Helper; @endphp
@extends('admin.layouts.master')
@section('title', 'Admin: Apis Settings')
@section('content')
  <div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Price List | Private Proxy - Shared</div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.prices.update', ['type' => 'proxy_service']) }}" method="POST">
            @csrf
            <div class="row mb-3">
              {{-- <div class="col-md-6">
                <label for="ipv6_private_sv1" class="form-label">IPv6 Private SV1</label>
                <input type="number" class="form-control" id="ipv6_private_sv1" name="ipv6_private_sv1" value="{{ $proxy_service['ipv6_private_sv1'] ?? '' }}">
              </div> --}}
              <div class="col-md-6">
                <label for="ipv4_private_sv1" class="form-label">IPv4 Private SV1</label>
                <input type="number" class="form-control" id="ipv4_private_sv1" name="ipv4_private_sv1" value="{{ $proxy_service['ipv4_private_sv1'] ?? '' }}">
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="ipv4_private_sv2" class="form-label">IPv4 Private SV2</label>
                <input type="number" class="form-control" id="ipv4_private_sv2" name="ipv4_private_sv2" value="{{ $proxy_service['ipv4_private_sv2'] ?? '' }}">
              </div>
              <div class="col-md-6">
                <label for="ipv4_private_game" class="form-label">Proxy IPv4 Game</label>
                <input type="number" class="form-control" id="ipv4_private_game" name="ipv4_private_game" value="{{ $proxy_service['ipv4_private_game'] ?? '' }}">
              </div>
            </div>
            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="residential_static_sv1" class="form-label">Proxy Residential Static SV1</label>
                <input type="number" class="form-control" id="residential_static_sv1" name="residential_static_sv1" value="{{ $proxy_service['residential_static_sv1'] ?? '' }}">
              </div>
              <div class="col-md-6">
                <label for="residential_static_sv2" class="form-label">Proxy Residential Static SV2</label>
                <input type="number" class="form-control" id="residential_static_sv2" name="residential_static_sv2" value="{{ $proxy_service['residential_static_sv2'] ?? '' }}">
              </div>
            </div>
            {{-- <div class="mb-3 row">
              <div class="col-md-6">
                <label for="residential_rotating_sv1" class="form-label">Proxy Residential Rotating SV1</label>
                <input type="number" class="form-control" id="residential_rotating_sv1" name="residential_rotating_sv1" value="{{ $proxy_service['residential_rotating_sv1'] ?? '' }}">
              </div>
            </div> --}}
            <div class="mb-3 text-end">
              <button class="btn btn-primary" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-sm-12 col-md-12 col-lg-12">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Price List | Proxy Xoay LTE</div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.prices.update', ['type' => 'proxy_lte_service']) }}" method="POST">

            @foreach ($listPackage as $key => $packages)
              <h4 class="mb-3 card-title">Bảng giá proxy dân cư xoay: {{ $key }}</h4>

              @foreach ($packages as $item)
                <div class="mb-3 form-group row">
                  <div class="col-md-4">
                    <label class="form-label fw-bold">Tên gói gốc</label>
                    <input type="text" value="{{ $item['name'] }}" class="form-control" disabled>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Số ngày</label>
                    <input type="text" value="{{ $item['day'] }}" name="value[{{ $item['id'] }}][day]" class="form-control" disabled>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Giá bán gốc</label>
                    <input type="text" value="{{ number_format($item['price']) }}" class="form-control" disabled>
                  </div>
                </div>
                <div class="mb-3 form-group row">
                  <div class="col-md-4">
                    <label class="form-label">Tên gói mới</label>
                    <input type="text" value="{{ $proxyConfig[$item['id']]['name'] ?? $item['name'] }}" name="value[{{ $item['id'] }}][name]" class="form-control">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Giá bán</label>
                    <input type="text" value="{{ $proxyConfig[$item['id']]['price'] ?? $item['price'] }}" name="value[{{ $item['id'] }}][price]" class="form-control">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Trạng thái</label>
                    <select name="value[{{ $item['id'] }}][status]" class="form-control">
                      <option value="0" {{ ($proxyConfig[$item['id']]['status'] ?? 0) == 0 ? 'selected' : '' }}>Ẩn</option>
                      <option value="1" {{ ($proxyConfig[$item['id']]['status'] ?? 0) == 1 ? 'selected' : '' }}>Hiện</option>
                    </select>
                  </div>
                </div>
                <hr />
                <input type="hidden" name="value[{{ $item['id'] }}][day]" value="{{ $item['day'] }}">
                <input type="hidden" name="value[{{ $item['id'] }}][slug]" value="{{ $item['name'] }}">
                <input type="hidden" name="value[{{ $item['id'] }}][category]" value="{{ $key }}">
              @endforeach
            @endforeach
            <div class="mb-3 text-end">
              @csrf
              <button class="btn btn-primary" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

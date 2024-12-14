@php use App\Helpers\Helper; @endphp
@extends('admin.layouts.master')
@section('title', 'Admin: General Settings')
@section('css')
  <link rel="stylesheet" href="{{ asset('/plugins/codemirror/codemirror.css') }}" />

  <link rel="stylesheet" href="{{ asset('/plugins/codemirror/theme/monokai.css') }}" />
@endsection
@section('content')
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Cài đặt chung</div>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.settings.general.update', ['type' => 'general']) }}" method="POST" class="default-form" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="title" class="form-label">Tiêu đề website</label>
              <input type="text" class="form-control" id="title" name="title" value="{{ old('title', setting('title')) }}">
            </div>
            <div class="mb-3">
              <label for="keywords" class="form-label">Từ khoá tìm kiếm</label>
              <input type="text" class="form-control" id="keywords" name="keywords" value="{{ old('keywords', setting('keywords')) }}">
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Mô tả website</label>
              <input type="text" class="form-control" id="description" name="description" value="{{ old('description', setting('description')) }}">
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="color_primary" class="form-label">Màu chủ đạo</label>
              <input type="color" class="form-control" style="height: 35.49px;" id="color_primary" name="color_primary" value="{{ old('color_primary', setting('color_primary')) }}">
            </div>
            <div class="mb-3">
              <label for="color_primary_hover" class="form-label">Màu phụ trợ</label>
              <input type="color" class="form-control" style="height: 35.49px;" id="color_primary_hover" name="color_primary_hover" value="{{ old('color_primary_hover', setting('color_primary_hover')) }}">
            </div>
            <div class="mb-3">
              <label for="primary_lang" class="form-label">Ngôn ngữ mặc định</label>
              <select class="form-control" id="primary_lang" name="primary_lang">
                @foreach (\App\Models\Language::where('status', true)->get() as $lang)
                  <option value="{{ $lang->code }}" {{ setting('primary_lang') === $lang->code ? 'selected' : '' }}>{{ $lang->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-3">
            <label for="auto_refund">Hoàn tiền lỗi API</label>
            <select class="form-control" id="auto_refund" name="auto_refund">
              <option value="1" {{ setting('auto_refund') == 1 ? 'selected' : '' }}>Bật</option>
              <option value="0" {{ setting('auto_refund') == 0 ? 'selected' : '' }}>Tắt</option>
            </select>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="footer_text" class="form-label">Footer Text</label>
            <input type="text" class="form-control" id="footer_text" name="footer_text" value="{{ old('footer_text', setting('footer_text', 'CMSNT.CO LTD')) }}">
          </div>
          <div class="col-md-6">
            <label for="footer_link" class="form-label">Footer Link</label>
            <input type="text" class="form-control" id="footer_link" name="footer_link" value="{{ old('footer_link', setting('footer_link', 'https://www.cmsnt.co/?utm=smmpanelv3&domain=' . domain())) }}">
          </div>
        </div>
        <hr />
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="logo_light" class="form-label">Logo Light</label>
            <input type="file" class="form-control" id="logo_light" name="logo_light">
            <div class="mb-2 mt-2 text-center">
              <img src="{{ asset(setting('logo_light')) }}" alt="Logo" class="img-fluid" style="max-height: 100px;">
            </div>
          </div>
          <div class="col-md-4">
            <label for="logo_dark" class="form-label">Logo Dark</label>
            <input type="file" class="form-control" id="logo_dark" name="logo_dark">
            <div class="mb-2 mt-2 text-center">
              <img src="{{ asset(setting('logo_dark')) }}" alt="Logo" class="img-fluid" style="max-height: 100px;">
            </div>
          </div>
          <div class="col-md-4">
            <label for="favicon" class="form-label">Favicon</label>
            <input type="file" class="form-control" id="favicon" name="favicon">
            <div class="mb-2 mt-2 text-center">
              <img src="{{ asset(setting('favicon')) }}" alt="Favicon" class="img-fluid" style="max-height: 100px;">
            </div>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="thumbnail" class="form-label">Thumbnail</label>
            <input type="file" class="form-control" id="thumbnail" name="thumbnail">
            <div class="mb-2 mt-2 text-center">
              <img src="{{ asset(setting('thumbnail')) }}" alt="thumbnail" class="img-fluid" style="max-height: 100px;">
            </div>
          </div>
          <div class="col-md-4">
            <label for="avatar_post" class="form-label">Avatar bài viết</label>
            <input type="file" class="form-control" id="avatar_post" name="avatar_post">
            <div class="mb-2 mt-2 text-center">
              <img src="{{ asset(setting('avatar_post')) }}" alt="avatar_post" class="img-fluid" style="max-height: 100px;">
            </div>
          </div>
          <div class="col-md-4">
            <label for="avatar_user" class="form-label">Avatar người dùng</label>
            <input type="file" class="form-control" id="avatar_user" name="avatar_user">
            <div class="mb-2 mt-2 text-center">
              <img src="{{ asset(setting('avatar_user')) }}" alt="avatar_user" class="img-fluid" style="max-height: 100px;">
            </div>
          </div>
        </div>
        <div class="mb-3 text-end">
          <button class="btn btn-primary" type="submit">Cập Nhật</button>
        </div>
      </form>
    </div>
  </div>
  <div class="col-md-12">
    <div class="card custom-card">
      <div class="card-header justify-content-between">
        <div class="card-title">Giảm giá theo cấp bậc (%) - Giá gốc 10đ nhập 10% thì được giảm còn <code>10-(10*10)/100 = 9đ</code></div>
      </div>
      <div class="card-body">
        @php $discount_rank = Helper::getConfig('rank_discount'); @endphp
        <form action="{{ route('admin.settings.general.update', ['type' => 'rank_discount']) }}" method="POST" class="axios-form">
          @csrf
          <div class="row mb-3">
            <div class="col-md-2">
              <label for="bronze" class="form-label">Rank Đồng</label>
              <input type="text" class="form-control" id="bronze" name="bronze" value="{{ $discount_rank['bronze'] ?? 0 }}" required>
            </div>
            <div class="col-md-2">
              <label for="silver" class="form-label">Rank Bạc</label>
              <input type="text" class="form-control" id="silver" name="silver" value="{{ $discount_rank['silver'] ?? 0 }}" required>
            </div>
            <div class="col-md-2">
              <label for="gold" class="form-label">Rank Vàng</label>
              <input type="text" class="form-control" id="gold" name="gold" value="{{ $discount_rank['gold'] ?? 0 }}" required>
            </div>
            <div class="col-md-2">
              <label for="platinum" class="form-label">Rank Bạch Kim</label>
              <input type="text" class="form-control" id="platinum" name="platinum" value="{{ $discount_rank['platinum'] ?? 0 }}" required>
            </div>
            <div class="col-md-2">
              <label for="diamond" class="form-label">Rank Kim Cương</label>
              <input type="text" class="form-control" id="diamond" name="diamond" value="{{ $discount_rank['diamond'] ?? 0 }}" required>
            </div>
            <div class="col-md-2">
              <label for="titanium" class="form-label">Rank Titanium</label>
              <input type="text" class="form-control" id="titanium" name="titanium" value="{{ $discount_rank['titanium'] ?? 0 }}" required>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-2">
              <label for="master" class="form-label">Rank Nhà Phân Phối</label>
              <input type="text" class="form-control" id="master" name="master" value="{{ $discount_rank['master'] ?? 0 }}" required>
            </div>
          </div>
          <div class="mb-3 text-end">
            <button class="btn btn-primary" type="submit">Cập Nhật</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-12">
    <div class="card custom-card">
      <div class="card-header justify-content-between">
        <div class="card-title">Các mốc tự động lên rank</div>
      </div>
      <div class="card-body">
        @php $rank_level = Helper::getConfig('rank_level'); @endphp
        <form action="{{ route('admin.settings.general.update', ['type' => 'rank_level']) }}" method="POST" class="axios-form">
          @csrf
          <div class="row mb-3">
            <div class="col-md-2">
              <label for="bronze" class="form-label">Rank Đồng</label>
              <input type="text" class="form-control" id="bronze" name="bronze" value="{{ $rank_level['bronze'] ?? 0 }}" required>
            </div>
            <div class="col-md-2">
              <label for="silver" class="form-label">Rank Bạc</label>
              <input type="text" class="form-control" id="silver" name="silver" value="{{ $rank_level['silver'] ?? 0 }}" required>
            </div>
            <div class="col-md-2">
              <label for="gold" class="form-label">Rank Vàng</label>
              <input type="text" class="form-control" id="gold" name="gold" value="{{ $rank_level['gold'] ?? 0 }}" required>
            </div>
            <div class="col-md-2">
              <label for="platinum" class="form-label">Rank Bạch Kim</label>
              <input type="text" class="form-control" id="platinum" name="platinum" value="{{ $rank_level['platinum'] ?? 0 }}" required>
            </div>
            <div class="col-md-2">
              <label for="diamond" class="form-label">Rank Kim Cương</label>
              <input type="text" class="form-control" id="diamond" name="diamond" value="{{ $rank_level['diamond'] ?? 0 }}" required>
            </div>
            <div class="col-md-2">
              <label for="titanium" class="form-label">Rank Titanium</label>
              <input type="text" class="form-control" id="titanium" name="titanium" value="{{ $rank_level['titanium'] ?? 0 }}" required>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-2">
              <label for="bronze" class="form-label">Rank Đồng</label>
              <textarea name="features[bronze]" id="features" class="form-control" rows="3" placeholder="Mỗi chức năng là 1 dòng">{{ $rank_level['features']['bronze'] ?? '' }}</textarea>
            </div>
            <div class="col-md-2">
              <label for="silver" class="form-label">Rank Bạc</label>
              <textarea name="features[silver]" id="features" class="form-control" rows="3" placeholder="Mỗi chức năng là 1 dòng">{{ $rank_level['features']['silver'] ?? '' }}</textarea>
            </div>
            <div class="col-md-2">
              <label for="gold" class="form-label">Rank Vàng</label>
              <textarea name="features[gold]" id="features" class="form-control" rows="3" placeholder="Mỗi chức năng là 1 dòng">{{ $rank_level['features']['gold'] ?? '' }}</textarea>
            </div>
            {{-- <div class="col-md-2">
              <label for="platinum" class="form-label">Rank Bạch Kim</label>
              <textarea name="features[platinum]" id="features" class="form-control" rows="3" placeholder="Mỗi chức năng là 1 dòng">{{ $rank_level['features']['platinum'] ?? '' }}</textarea>
            </div>
            <div class="col-md-2">
              <label for="diamond" class="form-label">Rank Kim Cương</label>
              <textarea name="features[diamond]" id="features" class="form-control" rows="3" placeholder="Mỗi chức năng là 1 dòng">{{ $rank_level['features']['diamond'] ?? '' }}</textarea>
            </div>
            <div class="col-md-2">
              <label for="titanium" class="form-label">Rank Titanium</label>
              <textarea name="features[titanium]" id="features" class="form-control" rows="3" placeholder="Mỗi chức năng là 1 dòng">{{ $rank_level['features']['titanium'] ?? '' }}</textarea>
            </div> --}}
          </div>
          <div class="mb-3 text-end">
            <button class="btn btn-primary" type="submit">Cập Nhật</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">{{ __t('Tuỳ chỉnh giao diện') }}</div>
        </div>
        <div class="card-body">
          @php
            $themes = Helper::getConfig('theme_settings');
            $list_themes = [
                [
                    'name' => 'none',
                    'label' => __t('Không sử dụng'),
                ],
                [
                    'name' => 'default',
                    'label' => __t('Mặt định'),
                ],
                [
                    'name' => 'modern',
                    'label' => __t('Hiện đại'),
                ],
                [
                    'name' => 'classic',
                    'label' => __t('Cổ điển'),
                ],
            ];
          @endphp
          <form action="{{ route('admin.settings.general.update', ['type' => 'theme_settings']) }}" method="POST" class="default-form" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 row">
              <div class="col-md-4">
                <label for="ladi_name" class="form-label">{{ __t('Mẫu trang giới thiệu') }}</label>
                <select name="ladi_name" id="ladi_name" class="form-control">
                  <option value="">{{ __t('Chọn mẫu') }}</option>
                  @foreach ($list_themes as $theme)
                    <option value="{{ $theme['name'] }}" @if (isset($themes['ladi_name']) ? $themes['ladi_name'] === $theme['name'] : false) selected @endif>{{ $theme['label'] }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <label for="auth_bg" class="form-label">Ảnh nền trang đăng nhập / đăng ký - <a href="{{ $themes['auth_bg'] ?? '#!' }}" target="_blank">Xem</a></label>
                <input type="file" class="form-control" id="auth_bg" name="auth_bg" accept="image/*">

                <div class="mb-2 mt-2 text-center">
                  <img src="{{ asset($themes['auth_bg'] ?? '') }}" alt="Logo" class="img-fluid" style="max-height: 100px;">
                </div>
              </div>
              <div class="col-md-4">
                <label for="order_form_type" class="form-label">Loại form mua hàng</label>
                <select name="order_form_type" id="order_form_type" class="form-control">
                  <option value="form_csr" @if (isset($themes['order_form_type']) ? $themes['order_form_type'] == 'form_csr' : false) selected @endif>{{ __t('Form Beta (Mượt hơn)') }}</option>
                  <option value="default" @if (isset($themes['order_form_type']) ? $themes['order_form_type'] == 'default' : false) selected @endif>{{ __t('Mặc định') }}</option>
                </select>
              </div>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-primary" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Thông Tin Nạp Tiền</div>
        </div>
        <div class="card-body">
          @php $deposit_info = Helper::getConfig('deposit_info'); @endphp
          <form action="{{ route('admin.settings.general.update', ['type' => 'deposit_info']) }}" method="POST" class="axios-form">
            @csrf
            <div class="mb-3">
              <label for="prefix" class="form-label">Cú Pháp</label>
              <input type="text" class="form-control" id="prefix" name="prefix" value="{{ $deposit_info['prefix'] ?? 'hello ' }}" required>
              <small>Khi cấu hình xong, thì nội dung chuyển khoản của bạn là: <span class="text-danger">{{ ($deposit_info['prefix'] ?? 'hello ') . auth()->id() }}</span></small>
            </div>
            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="discount" class="form-label">Khuyến Mãi [+ %]</label>
                <input type="text" class="form-control" id="discount" name="discount" value="{{ $deposit_info['discount'] ?? 0 }}" required>
                <small>% khuyến mãi sẽ được cộng vào số tiền mà khách nạp</small>
              </div>
              <div class="col-md-6">
                <label for="min_amount" class="form-label">Tối Thiểu</label>
                <input type="text" class="form-control" id="min_amount" name="min_amount" value="{{ $deposit_info['min_amount'] ?? 0 }}" required>
                <small>Số tiền nạp tối thiểu và để áp dụng khuyến mãi</small>
              </div>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-primary" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Quản lý cổng nạp tiền</div>
        </div>
        <div class="card-body">
          @php $deposit_status = Helper::getConfig('deposit_status'); @endphp
          <form action="{{ route('admin.settings.general.update', ['type' => 'deposit_status']) }}" method="POST" class="axios-form">
            @csrf
            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="card" class="form-label">{{ __t('Thẻ cào') }}</label>
                <select name="card" id="card" class="form-control">
                  <option value="1" @if (isset($deposit_status['card']) ? $deposit_status['card'] == 1 : false) selected @endif>{{ __t('Bật') }}</option>
                  <option value="0" @if (isset($deposit_status['card']) ? $deposit_status['card'] == 0 : false) selected @endif>{{ __t('Tắt') }}</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="crypto" class="form-label">{{ __t('Tiền mã hoá') }}</label>
                <select name="crypto" id="crypto" class="form-control">
                  <option value="1" @if (isset($deposit_status['crypto']) ? $deposit_status['crypto'] == 1 : false) selected @endif>{{ __t('Bật') }}</option>
                  <option value="0" @if (isset($deposit_status['crypto']) ? $deposit_status['crypto'] == 0 : false) selected @endif>{{ __t('Tắt') }}</option>
                </select>
              </div>
            </div>
            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="bank" class="form-label">{{ __t('Chuyển khoản ngân hàng') }}</label>
                <select name="bank" id="bank" class="form-control">
                  <option value="1" @if (isset($deposit_status['bank']) ? $deposit_status['bank'] == 1 : false) selected @endif>{{ __t('Bật') }}</option>
                  <option value="0" @if (isset($deposit_status['bank']) ? $deposit_status['bank'] == 0 : false) selected @endif>{{ __t('Tắt') }}</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="perfect_money" class="form-label">{{ __t('Perfect Money') }}</label>
                <select name="perfect_money" id="perfect_money" class="form-control">
                  <option value="1" @if (isset($deposit_status['perfect_money']) ? $deposit_status['perfect_money'] == 1 : false) selected @endif>{{ __t('Bật') }}</option>
                  <option value="0" @if (isset($deposit_status['perfect_money']) ? $deposit_status['perfect_money'] == 0 : false) selected @endif>{{ __t('Tắt') }}</option>
                </select>
              </div>
            </div>
            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="paypal" class="form-label">{{ __t('Nạp tiền qua Paypal') }}</label>
                <select name="paypal" id="paypal" class="form-control">
                  <option value="1" @if (isset($deposit_status['paypal']) ? $deposit_status['paypal'] == 1 : false) selected @endif>{{ __t('Bật') }}</option>
                  <option value="0" @if (isset($deposit_status['paypal']) ? $deposit_status['paypal'] == 0 : false) selected @endif>{{ __t('Tắt') }}</option>
                </select>
              </div>
            </div>
            <div class="mb-3 text-center">
              <div class="text-danger">{{ __t('Tuỳ chỉnh ẩn hoặc hiện tại thanh menu') }}</div>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-primary" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Thông tin liên hệ</div>
        </div>
        <div class="card-body">
          @php $contact = Helper::getConfig('contact_info'); @endphp
          <form action="{{ route('admin.settings.general.update', ['type' => 'contact_info']) }}" method="POST" class="axios-form">
            @csrf
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="facebook" class="form-label">Facebook</label>
                <input type="text" class="form-control" id="facebook" name="facebook" value="{{ $contact['facebook'] ?? '' }}">
              </div>
              <div class="col-md-6">
                <label for="telegram" class="form-label">Telegram</label>
                <input type="text" class="form-control" id="telegram" name="telegram" value="{{ $contact['telegram'] ?? '' }}">
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="phone_no" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" id="phone_no" name="phone_no" value="{{ $contact['phone_no'] ?? '' }}">
              </div>
              <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" value="{{ $contact['email'] ?? '' }}">
              </div>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-primary" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Cấu hình Affiliate Program</div>
        </div>
        <div class="card-body">
          @php $affiliate_config = Helper::getConfig('affiliate_config'); @endphp
          <form action="{{ route('admin.settings.general.update', ['type' => 'affiliate_config']) }}" method="POST" class="axios-form">
            @csrf
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="min_withdraw" class="form-label">Tối Thiểu</label>
                <input type="number" class="form-control" id="min_withdraw" name="min_withdraw" value="{{ $affiliate_config['min_withdraw'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="max_withdraw" class="form-label">Tối Đa</label>
                <input type="number" class="form-control" id="max_withdraw" name="max_withdraw" value="{{ $affiliate_config['max_withdraw'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="withdraw_status" class="form-label">Trạng Thái</label>
                <select class="form-control" id="withdraw_status" name="withdraw_status">
                  <option value="1" {{ ($affiliate_config['withdraw_status'] ?? null) == 1 ? 'selected' : '' }}>Bật</option>
                  <option value="0" {{ ($affiliate_config['withdraw_status'] ?? null) == 0 ? 'selected' : '' }}>Tắt</option>
                </select>
              </div>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-primary" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>

  <div class="row">
    <div class="col-sm-12 col-md-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Header Code</div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.general.update', ['type' => 'header_script']) }}" method="POST" class="default-form">
            @csrf
            <div class="mb-3">
              <label for="code" class="form-label">Code</label>
              <textarea class="form-control" name="code" id="editor1" rows="10">{{ Helper::getNotice('header_script') }}</textarea>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-primary" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Footer Code</div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.general.update', ['type' => 'footer_script']) }}" method="POST" class="default-form">
            @csrf
            <div class="mb-3">
              <label for="code" class="form-label">Code</label>
              <textarea class="form-control" name="code" id="editor2" rows="10">{{ Helper::getNotice('footer_script') }}</textarea>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-primary" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script src="{{ asset('/plugins/codemirror/codemirror.js') }}"></script>
  <script src="{{ asset('/plugins/codemirror/mode/css/css.js') }}"></script>
  <script src="{{ asset('/plugins/codemirror/mode/xml/xml.js') }}"></script>
  <script src="{{ asset('/plugins/codemirror/mode/htmlmixed/htmlmixed.js') }}"></script>

  <script>
    $(function() {
      // CodeMirror
      CodeMirror.fromTextArea(document.getElementById("editor1"), {
        mode: "htmlmixed",
        theme: "monokai"
      });
      CodeMirror.fromTextArea(document.getElementById("editor2"), {
        mode: "htmlmixed",
        theme: "monokai"
      });
    })
  </script>
@endsection

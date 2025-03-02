@php use App\Helpers\Helper; @endphp
@extends('admin.layouts.master')
@section('title', 'Cài đặt hệ thống')

@section('css')
  <link rel="stylesheet" href="{{ asset('/plugins/codemirror/codemirror.css') }}" />
  <link rel="stylesheet" href="{{ asset('/plugins/codemirror/theme/monokai.css') }}" />
  <style>
    .settings-nav {
      position: sticky;
      top: 1rem;
    }

    .nav-link {
      color: #495057;
      padding: 1rem;
      border-radius: 0.5rem;
      transition: all 0.2s;
    }

    .nav-link:hover {
      background: rgba(0, 0, 0, 0.05);
    }

    .nav-link.active {
      background: #0d6efd;
      color: #fff;
    }

    .settings-section {
      background: #fff;
      border-radius: 0.5rem;
      box-shadow: 0 0 1rem rgba(0, 0, 0, 0.05);
      margin-bottom: 1.5rem;
    }

    .section-header {
      padding: 1rem;
      border-bottom: 1px solid #dee2e6;
    }

    .section-body {
      padding: 1.5rem;
    }

    .form-label {
      font-weight: 500;
    }

    .preview-image {
      max-height: 100px;
      border: 2px dashed #dee2e6;
      border-radius: 0.5rem;
      padding: 0.25rem;
    }

    .form-hint {
      font-size: 0.875rem;
      color: #6c757d;
      margin-top: 0.25rem;
    }
  </style>
@endsection

@section('content')
  <div class="container-fluid">
    <div class="row g-4">
      <!-- Side Navigation -->
      <div class="col-12 col-lg-3">
        <div class="settings-nav">
          <nav class="nav flex-column nav-pills">
            <a class="nav-link active" data-bs-toggle="pill" href="#general">
              <i class="fas fa-cog me-2"></i>Cài đặt chung
            </a>
            <a class="nav-link" data-bs-toggle="pill" href="#ranks">
              <i class="fas fa-trophy me-2"></i>Cấp bậc & Giảm giá
            </a>
            <a class="nav-link" data-bs-toggle="pill" href="#rank-levels">
              <i class="fas fa-chart-line me-2"></i>Mốc lên cấp
            </a>
            <a class="nav-link" data-bs-toggle="pill" href="#themes">
              <i class="fas fa-paint-brush me-2"></i>Giao diện
            </a>
            <a class="nav-link" data-bs-toggle="pill" href="#payments">
              <i class="fas fa-money-bill me-2"></i>Nạp tiền
            </a>
            <a class="nav-link" data-bs-toggle="pill" href="#contact">
              <i class="fas fa-address-book me-2"></i>Liên hệ
            </a>
            <a class="nav-link" data-bs-toggle="pill" href="#affiliate">
              <i class="fas fa-users me-2"></i>Tiếp thị liên kết
            </a>
            <a class="nav-link" data-bs-toggle="pill" href="#scripts">
              <i class="fas fa-code me-2"></i>Scripts
            </a>
          </nav>
        </div>
      </div>

      <!-- Main Content -->
      <div class="col-12 col-lg-9">
        <div class="tab-content">
          <!-- General Settings -->
          <div class="tab-pane fade show active" id="general">
            <div class="settings-section">
              <form action="{{ route('admin.settings.general.update', ['type' => 'general']) }}" method="POST" class="default-form" enctype="multipart/form-data">
                @csrf
                <!-- Website Info -->
                <div class="section-header">
                  <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin website</h5>
                </div>
                <div class="section-body">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', setting('title')) }}" placeholder="Tiêu đề website">
                        <label for="title">Tiêu đề website</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="keywords" name="keywords" value="{{ old('keywords', setting('keywords')) }}" placeholder="Từ khóa tìm kiếm">
                        <label for="keywords">Từ khóa tìm kiếm</label>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-floating">
                        <textarea class="form-control" id="description" name="description" style="height: 100px" placeholder="Mô tả website">{{ old('description', setting('description')) }}</textarea>
                        <label for="description">Mô tả website</label>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Colors & Language -->
                <div class="section-header">
                  <h5 class="mb-0"><i class="fas fa-palette me-2"></i>Màu sắc & Ngôn ngữ</h5>
                </div>
                <div class="section-body">
                  <div class="row g-3">
                    <div class="col-md-4">
                      <label class="form-label">Màu chủ đạo</label>
                      <input type="color" class="form-control form-control-color w-100" style="height: 42px" name="color_primary" value="{{ old('color_primary', setting('color_primary')) }}">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Màu phụ trợ</label>
                      <input type="color" class="form-control form-control-color w-100" style="height: 42px" name="color_primary_hover" value="{{ old('color_primary_hover', setting('color_primary_hover')) }}">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Ngôn ngữ mặc định</label>
                      <select class="form-select" name="primary_lang">
                        @foreach (\App\Models\Language::where('status', true)->get() as $lang)
                          <option value="{{ $lang->code }}" {{ setting('primary_lang') === $lang->code ? 'selected' : '' }}>{{ $lang->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Logo & Images -->
                <div class="section-header">
                  <h5 class="mb-0"><i class="fas fa-images me-2"></i>Logo & Hình ảnh</h5>
                </div>
                <div class="section-body">
                  <div class="row g-4">
                    <div class="col-md-4">
                      <div class="text-center">
                        <label class="form-label">Logo Light</label>
                        <input type="file" class="form-control mb-2" name="logo_light" accept="image/*">
                        <img src="{{ asset(setting('logo_light')) }}" alt="Logo Light" class="preview-image">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="text-center">
                        <label class="form-label">Logo Dark</label>
                        <input type="file" class="form-control mb-2" name="logo_dark" accept="image/*">
                        <img src="{{ asset(setting('logo_dark')) }}" alt="Logo Dark" class="preview-image">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="text-center">
                        <label class="form-label">Favicon</label>
                        <input type="file" class="form-control mb-2" name="favicon" accept="image/*">
                        <img src="{{ asset(setting('favicon')) }}" alt="Favicon" class="preview-image">
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="text-center">
                        <label class="form-label">Thumbnail</label>
                        <input type="file" class="form-control mb-2" name="thumbnail" accept="image/*">
                        <img src="{{ asset(setting('thumbnail')) }}" alt="Thumbnail" class="preview-image">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="text-center">
                        <label class="form-label">Avatar bài viết</label>
                        <input type="file" class="form-control mb-2" name="avatar_post" accept="image/*">
                        <img src="{{ asset(setting('avatar_post')) }}" alt="Post Avatar" class="preview-image">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="text-center">
                        <label class="form-label">Avatar người dùng</label>
                        <input type="file" class="form-control mb-2" name="avatar_user" accept="image/*">
                        <img src="{{ asset(setting('avatar_user')) }}" alt="User Avatar" class="preview-image">
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Footer -->
                <div class="section-header">
                  <h5 class="mb-0"><i class="fas fa-window-maximize me-2"></i>Footer</h5>
                </div>
                <div class="section-body">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="footer_text" name="footer_text" value="{{ old('footer_text', setting('footer_text', 'CMSNT.CO LTD')) }}" placeholder="Footer Text">
                        <label for="footer_text">Footer Text</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="footer_link" name="footer_link" value="{{ old('footer_link', setting('footer_link')) }}" placeholder="Footer Link">
                        <label for="footer_link">Footer Link</label>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Order -->
                <div class="section-header">
                  <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Đơn hàng</h5>
                </div>
                <div class="section-body">
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label for="auto_refund" class="form-label">Tự động hoàn đơn lỗi (API)</label>
                      <select class="form-select" name="auto_refund">
                        <option value="1" {{ setting('auto_refund', 0) == 1 ? 'selected' : '' }}>Bật</option>
                        <option value="0" {{ setting('auto_refund', 0) == 0 ? 'selected' : '' }}>Tắt</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label for="comm_percent" class="form-label">% Hoa hồng giới thiệu</label>
                      <input type="number" class="form-control" name="comm_percent" value="{{ setting('comm_percent', 0) }}" required>
                    </div>
                  </div>
                </div>

                <!-- Captcha -->
                <div class="section-header">
                  <h5 class="mb-0"><i class="fas fa-shield me-2"></i>Cloudflare Captcha</h5>
                </div>
                <div class="section-body">
                  <div class="alert alert-danger">Sử dụng captcha của Cloudflare: <a href="https://dash.cloudflare.com/sign-up?to=/:account/turnstile" target="_blank">Xem tại đây</a>; cấu hình xong kiểm tra ở trang đăng
                    ký ở trang ẩn danh trước khi đăng xuất khỏi admin.</div>
                  <div class="row g-3">
                    <div class="col-md-4">
                      <div class="form-floating">
                        <select class="form-select" id="captcha_status" name="captcha_status">
                          <option value="1" {{ setting('captcha_status', 0) == 1 ? 'selected' : '' }}>Bật</option>
                          <option value="0" {{ setting('captcha_status', 0) == 0 ? 'selected' : '' }}>Tắt</option>
                        </select>
                        <label for="captcha_siteKey">Status</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="captcha_siteKey" name="captcha_siteKey" value="{{ old('captcha_siteKey', setting('captcha_siteKey', null)) }}" placeholder="Footer Text">
                        <label for="captcha_siteKey">Site Key</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="captcha_secretKey" name="captcha_secretKey" value="{{ old('captcha_secretKey', setting('captcha_secretKey')) }}" placeholder="Footer Link">
                        <label for="captcha_secretKey">Secret Key</label>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="p-3 text-end">
                  <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save me-2"></i>Lưu thay đổi
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- Ranks & Discount -->
          <div class="tab-pane fade" id="ranks">
            <div class="settings-section">
              @php $discount_rank = Helper::getConfig('rank_discount'); @endphp
              <form action="{{ route('admin.settings.general.update', ['type' => 'rank_discount']) }}" method="POST" class="axios-form">
                @csrf
                <div class="section-header">
                  <h5 class="mb-0"><i class="fas fa-percent me-2"></i>Giảm giá theo cấp bậc</h5>
                  <div class="form-hint">Giá gốc 10đ nhập 10% thì được giảm còn <code>10-(10*10)/100 = 9đ</code></div>
                </div>
                <div class="section-body">
                  <div class="row g-3">
                    <div class="col-md-2">
                      <label class="form-label">Rank Đồng</label>
                      <input type="number" class="form-control" name="bronze" value="{{ $discount_rank['bronze'] ?? 0 }}" required>
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">Rank Bạc</label>
                      <input type="number" class="form-control" name="silver" value="{{ $discount_rank['silver'] ?? 0 }}" required>
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">Rank Vàng</label>
                      <input type="number" class="form-control" name="gold" value="{{ $discount_rank['gold'] ?? 0 }}" required>
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">Rank Bạch Kim</label>
                      <input type="number" class="form-control" name="platinum" value="{{ $discount_rank['platinum'] ?? 0 }}" required>
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">Rank Kim Cương</label>
                      <input type="number" class="form-control" name="diamond" value="{{ $discount_rank['diamond'] ?? 0 }}" required>
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">Rank Titanium</label>
                      <input type="number" class="form-control" name="titanium" value="{{ $discount_rank['titanium'] ?? 0 }}" required>
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">Rank Nhà Phân Phối</label>
                      <input type="number" class="form-control" name="master" value="{{ $discount_rank['master'] ?? 0 }}" required>
                    </div>
                  </div>
                </div>
                <div class="p-3 text-end">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Lưu thay đổi
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- Rank Levels -->
          <div class="tab-pane fade" id="rank-levels">
            <div class="settings-section">
              @php $rank_level = Helper::getConfig('rank_level'); @endphp
              <form action="{{ route('admin.settings.general.update', ['type' => 'rank_level']) }}" method="POST" class="axios-form">
                @csrf
                <div class="section-header">
                  <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Các mốc tự động lên rank</h5>
                </div>
                <div class="section-body">
                  <div class="row g-3 mb-4">
                    <div class="col-md-2">
                      <label class="form-label">Mốc Rank Đồng</label>
                      <input type="number" class="form-control" name="bronze" value="{{ $rank_level['bronze'] ?? 0 }}" required>
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">Mốc Rank Bạc</label>
                      <input type="number" class="form-control" name="silver" value="{{ $rank_level['silver'] ?? 0 }}" required>
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">Mốc Rank Vàng</label>
                      <input type="number" class="form-control" name="gold" value="{{ $rank_level['gold'] ?? 0 }}" required>
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">Mốc Rank Bạch Kim</label>
                      <input type="number" class="form-control" name="platinum" value="{{ $rank_level['platinum'] ?? 0 }}" required>
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">Mốc Rank Kim Cương</label>
                      <input type="number" class="form-control" name="diamond" value="{{ $rank_level['diamond'] ?? 0 }}" required>
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">Mốc Rank Titanium</label>
                      <input type="number" class="form-control" name="titanium" value="{{ $rank_level['titanium'] ?? 0 }}" required>
                    </div>
                  </div>

                  <div class="section-header">
                    <h5 class="mb-0">Tính năng theo cấp bậc</h5>
                    <small class="text-muted">Mỗi tính năng viết trên một dòng</small>
                  </div>

                  <div class="row g-3">
                    <div class="col-md-4">
                      <label class="form-label">Tính năng Rank Đồng</label>
                      <textarea class="form-control" name="features[bronze]" rows="3" placeholder="VD: Được giảm 5% giá dịch vụ">{{ $rank_level['features']['bronze'] ?? '' }}</textarea>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Tính năng Rank Bạc</label>
                      <textarea class="form-control" name="features[silver]" rows="3" placeholder="VD: Được giảm 10% giá dịch vụ">{{ $rank_level['features']['silver'] ?? '' }}</textarea>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Tính năng Rank Vàng</label>
                      <textarea class="form-control" name="features[gold]" rows="3" placeholder="VD: Được giảm 15% giá dịch vụ">{{ $rank_level['features']['gold'] ?? '' }}</textarea>
                    </div>
                  </div>
                </div>
                <div class="p-3 text-end">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Lưu thay đổi
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- Themes -->
          <div class="tab-pane fade" id="themes">
            <div class="settings-section">
              @php
                $themes = Helper::getConfig('theme_settings');
                $list_themes = [
                    ['name' => 'none', 'label' => __t('Không sử dụng')],
                    ['name' => 'default', 'label' => __t('Mặc định')],
                    ['name' => 'modern', 'label' => __t('Hiện đại')],
                    ['name' => 'classic', 'label' => __t('Cổ điển')],
                ];
              @endphp

              <form action="{{ route('admin.settings.general.update', ['type' => 'theme_settings']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="section-header">
                  <h5 class="mb-0"><i class="fas fa-palette me-2"></i>Tùy chỉnh giao diện</h5>
                </div>
                <div class="section-body">
                  <div class="row g-3">
                    <div class="col-md-4">
                      <label class="form-label">Mẫu trang giới thiệu</label>
                      <select class="form-select" name="ladi_name">
                        <option value="">Chọn mẫu</option>
                        @foreach ($list_themes as $theme)
                          <option value="{{ $theme['name'] }}" @if (isset($themes['ladi_name']) && $themes['ladi_name'] === $theme['name']) selected @endif>
                            {{ $theme['label'] }}
                          </option>
                        @endforeach
                      </select>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-label d-flex justify-content-between align-items-center">
                          Ảnh nền đăng nhập/đăng ký
                          <a href="{{ $themes['auth_bg'] ?? '#!' }}" target="_blank" class="btn btn-sm btn-light">Xem</a>
                        </label>
                        <input type="file" class="form-control" name="auth_bg" accept="image/*">
                        <div class="mt-2 text-center">
                          <img src="{{ asset($themes['auth_bg'] ?? '') }}" class="preview-image" alt="Auth Background">
                        </div>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <label class="form-label">Loại form mua hàng</label>
                      <select class="form-select" name="order_form_type">
                        <option value="form_csr" @if (isset($themes['order_form_type']) && $themes['order_form_type'] == 'form_csr') selected @endif>
                          Form Beta (Mượt hơn)
                        </option>
                        <option value="default" @if (isset($themes['order_form_type']) && $themes['order_form_type'] == 'default') selected @endif>
                          Mặc định
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="p-3 text-end">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Lưu thay đổi
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- Payments -->
          <div class="tab-pane fade" id="payments">
            <div class="row g-4">
              <!-- Deposit Info -->
              <div class="col-md-6">
                <div class="settings-section h-100">
                  @php $deposit_info = Helper::getConfig('deposit_info'); @endphp
                  <form action="{{ route('admin.settings.general.update', ['type' => 'deposit_info']) }}" method="POST" class="axios-form">
                    @csrf
                    <div class="section-header">
                      <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin nạp tiền</h5>
                    </div>
                    <div class="section-body">
                      <div class="mb-3">
                        <label class="form-label">Cú pháp nạp tiền</label>
                        <input type="text" class="form-control" name="prefix" value="{{ $deposit_info['prefix'] ?? 'hello ' }}" required>
                        <div class="form-hint">
                          Nội dung chuyển khoản: <span class="text-danger">{{ ($deposit_info['prefix'] ?? 'hello ') . auth()->id() }}</span>
                        </div>
                      </div>

                      <div class="row g-3">
                        <div class="col-md-6">
                          <label class="form-label">Khuyến mãi [+ %]</label>
                          <input type="number" class="form-control" name="discount" value="{{ $deposit_info['discount'] ?? 0 }}" required>
                          <div class="form-hint">% cộng thêm vào số tiền nạp</div>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Số tiền tối thiểu</label>
                          <input type="number" class="form-control" name="min_amount" value="{{ $deposit_info['min_amount'] ?? 0 }}" required>
                          <div class="form-hint">Số tiền tối thiểu để được KM</div>
                        </div>
                      </div>
                    </div>
                    <div class="p-3 text-end">
                      <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Lưu thay đổi
                      </button>
                    </div>
                  </form>
                </div>
              </div>

              <!-- Payment Methods -->
              <div class="col-md-6">
                <div class="settings-section h-100">
                  @php $deposit_status = Helper::getConfig('deposit_status'); @endphp
                  <form action="{{ route('admin.settings.general.update', ['type' => 'deposit_status']) }}" method="POST" class="axios-form">
                    @csrf
                    <div class="section-header">
                      <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Phương thức thanh toán</h5>
                    </div>
                    <div class="section-body">
                      <div class="row g-3">
                        <div class="col-md-6">
                          <label class="form-label">Thẻ cào</label>
                          <select class="form-select" name="card">
                            <option value="1" @if (isset($deposit_status['card']) && $deposit_status['card'] == 1) selected @endif>Bật</option>
                            <option value="0" @if (isset($deposit_status['card']) && $deposit_status['card'] == 0) selected @endif>Tắt</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Tiền mã hoá</label>
                          <select class="form-select" name="crypto">
                            <option value="1" @if (isset($deposit_status['crypto']) && $deposit_status['crypto'] == 1) selected @endif>Bật</option>
                            <option value="0" @if (isset($deposit_status['crypto']) && $deposit_status['crypto'] == 0) selected @endif>Tắt</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Chuyển khoản ngân hàng</label>
                          <select class="form-select" name="bank">
                            <option value="1" @if (isset($deposit_status['bank']) && $deposit_status['bank'] == 1) selected @endif>Bật</option>
                            <option value="0" @if (isset($deposit_status['bank']) && $deposit_status['bank'] == 0) selected @endif>Tắt</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Perfect Money</label>
                          <select class="form-select" name="perfect_money">
                            <option value="1" @if (isset($deposit_status['perfect_money']) && $deposit_status['perfect_money'] == 1) selected @endif>Bật</option>
                            <option value="0" @if (isset($deposit_status['perfect_money']) && $deposit_status['perfect_money'] == 0) selected @endif>Tắt</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">PayPal</label>
                          <select class="form-select" name="paypal">
                            <option value="1" @if (isset($deposit_status['paypal']) && $deposit_status['paypal'] == 1) selected @endif>Bật</option>
                            <option value="0" @if (isset($deposit_status['paypal']) && $deposit_status['paypal'] == 0) selected @endif>Tắt</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="p-3 text-end">
                      <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Lưu thay đổi
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!-- Contact -->
          <div class="tab-pane fade" id="contact">
            <div class="settings-section">
              @php $contact = Helper::getConfig('contact_info'); @endphp
              <form action="{{ route('admin.settings.general.update', ['type' => 'contact_info']) }}" method="POST" class="axios-form">
                @csrf
                <div class="section-header">
                  <h5 class="mb-0"><i class="fas fa-address-book me-2"></i>Thông tin liên hệ</h5>
                </div>
                <div class="section-body">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" class="form-control" name="facebook" value="{{ $contact['facebook'] ?? '' }}" placeholder="Facebook">
                        <label>Facebook</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" class="form-control" name="telegram" value="{{ $contact['telegram'] ?? '' }}" placeholder="Telegram">
                        <label>Telegram</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="tel" class="form-control" name="phone_no" value="{{ $contact['phone_no'] ?? '' }}" placeholder="Số điện thoại">
                        <label>Số điện thoại</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="email" class="form-control" name="email" value="{{ $contact['email'] ?? '' }}" placeholder="Email">
                        <label>Email</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="p-3 text-end">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Lưu thay đổi
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- Affiliate -->
          <div class="tab-pane fade" id="affiliate">
            <div class="settings-section">
              @php $affiliate_config = Helper::getConfig('affiliate_config'); @endphp
              <form action="{{ route('admin.settings.general.update', ['type' => 'affiliate_config']) }}" method="POST" class="axios-form">
                @csrf
                <div class="section-header">
                  <h5 class="mb-0"><i class="fas fa-users me-2"></i>Cấu hình Affiliate Program</h5>
                </div>
                <div class="section-body">
                  <div class="row g-3">
                    <div class="col-md-4">
                      <div class="form-floating">
                        <input type="number" class="form-control" name="min_withdraw" value="{{ $affiliate_config['min_withdraw'] ?? '' }}" placeholder="Tối thiểu">
                        <label>Số tiền tối thiểu</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating">
                        <input type="number" class="form-control" name="max_withdraw" value="{{ $affiliate_config['max_withdraw'] ?? '' }}" placeholder="Tối đa">
                        <label>Số tiền tối đa</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating">
                        <select class="form-select" name="withdraw_status">
                          <option value="1" {{ ($affiliate_config['withdraw_status'] ?? null) == 1 ? 'selected' : '' }}>Bật</option>
                          <option value="0" {{ ($affiliate_config['withdraw_status'] ?? null) == 0 ? 'selected' : '' }}>Tắt</option>
                        </select>
                        <label>Trạng thái rút tiền</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="p-3 text-end">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Lưu thay đổi
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- Scripts -->
          <div class="tab-pane fade" id="scripts">
            <div class="row g-4">
              <!-- Header Script -->
              <div class="col-md-6">
                <div class="settings-section h-100">
                  <form action="{{ route('admin.settings.general.update', ['type' => 'header_script']) }}" method="POST" class="default-form">
                    @csrf
                    <div class="section-header">
                      <h5 class="mb-0"><i class="fas fa-code me-2"></i>Header Script</h5>
                    </div>
                    <div class="section-body">
                      <textarea id="editor1" name="code" class="form-control" rows="10">{{ Helper::getNotice('header_script') }}</textarea>
                    </div>
                    <div class="p-3 text-end">
                      <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Lưu thay đổi
                      </button>
                    </div>
                  </form>
                </div>
              </div>

              <!-- Footer Script -->
              <div class="col-md-6">
                <div class="settings-section h-100">
                  <form action="{{ route('admin.settings.general.update', ['type' => 'footer_script']) }}" method="POST" class="default-form">
                    @csrf
                    <div class="section-header">
                      <h5 class="mb-0"><i class="fas fa-code me-2"></i>Footer Script</h5>
                    </div>
                    <div class="section-body">
                      <textarea id="editor2" name="code" class="form-control" rows="10">{{ Helper::getNotice('footer_script') }}</textarea>
                    </div>
                    <div class="p-3 text-end">
                      <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Lưu thay đổi
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

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
    $(document).ready(function() {
      // Initialize CodeMirror editors
      CodeMirror.fromTextArea(document.getElementById("editor1"), {
        mode: "htmlmixed",
        theme: "monokai",
        lineNumbers: true,
        autoCloseTags: true,
        autoCloseBrackets: true,
        matchBrackets: true
      });

      CodeMirror.fromTextArea(document.getElementById("editor2"), {
        mode: "htmlmixed",
        theme: "monokai",
        lineNumbers: true,
        autoCloseTags: true,
        autoCloseBrackets: true,
        matchBrackets: true
      });

      // Image preview
      $('input[type="file"]').change(function(e) {
        let file = e.target.files[0];
        let reader = new FileReader();
        let preview = $(this).closest('.section-body').find('img');

        if (file) {
          reader.onload = function(e) {
            preview.attr('src', e.target.result);
          }
          reader.readAsDataURL(file);
        }
      });

      // Loading state for forms
      $('.default-form, .axios-form').on('submit', function() {
        let button = $(this).find('button[type="submit"]');
        button.prop('disabled', true);
        button.html('<span class="spinner-border spinner-border-sm me-2"></span>Đang lưu...');
      });
    });
  </script>
@endsection

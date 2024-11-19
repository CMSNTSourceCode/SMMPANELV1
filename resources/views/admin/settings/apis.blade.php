@extends('admin.layouts.master')
@section('title', 'Admin: Apis Settings')
@section('content')
  <div class="row">
    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">AutoBank | Web2m | Vietcombank</div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'web2m_vietcombank']) }}" class="axios-form" method="POST">
            @csrf
            <div class="mb-3 row">
              <div class="col-md-4">
                <label for="account_number" class="form-label">Số Tài Khoản</label>
                <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $web2m_vietcombank['account_number'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="account_password" class="form-label">Mật khẩu Bank</label>
                <input type="password" class="form-control" id="account_password" name="account_password" value="{{ $web2m_vietcombank['account_password'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="api_token" class="form-label">API Token Web2m</label>
                <input type="text" class="form-control" id="api_token" name="api_token" value="{{ $web2m_vietcombank['api_token'] ?? '' }}">
              </div>
            </div>
            <div class="mb-3">
              <label for="link_cron" class="form-label">Link Cron (manual)</label>
              <input type="text" class="form-control" id="link_cron" name="link_cron" value="{{ route('cron.deposit.check', ['key' => prj_key(), 'type' => 'vietcombank']) }}" readonly>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-primary" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">AutoBank | Web2m | TPBank</div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'web2m_tpbank']) }}" class="axios-form" method="POST">
            @csrf
            <div class="mb-3 row">
              <div class="col-md-4">
                <label for="account_number" class="form-label">Số Tài Khoản</label>
                <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $web2m_tpbank['account_number'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="account_password" class="form-label">Mật khẩu Bank</label>
                <input type="password" class="form-control" id="account_password" name="account_password" value="{{ $web2m_tpbank['account_password'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="api_token" class="form-label">API Token Web2m</label>
                <input type="text" class="form-control" id="api_token" name="api_token" value="{{ $web2m_tpbank['api_token'] ?? '' }}">
              </div>
            </div>
            <div class="mb-3">
              <label for="link_cron" class="form-label">Link Cron (manual)</label>
              <input type="text" class="form-control" id="link_cron" name="link_cron" value="{{ route('cron.deposit.check', ['key' => prj_key(), 'type' => 'tpbank']) }}" readonly>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-primary" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">AutoBank | Web2m | Acb</div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'web2m_acb']) }}" class="axios-form" method="POST">
            @csrf
            <div class="mb-3 row">
              <div class="col-md-4">
                <label for="account_number" class="form-label">Số Tài Khoản</label>
                <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $web2m_acb['account_number'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="account_password" class="form-label">Mật khẩu Bank</label>
                <input type="password" class="form-control" id="account_password" name="account_password" value="{{ $web2m_acb['account_password'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="api_token" class="form-label">API Token Web2m</label>
                <input type="text" class="form-control" id="api_token" name="api_token" value="{{ $web2m_acb['api_token'] ?? '' }}">
              </div>
            </div>
            <div class="mb-3">
              <label for="link_cron" class="form-label">Link Cron (manual)</label>
              <input type="text" class="form-control" id="link_cron" name="link_cron" value="{{ route('cron.deposit.check', ['key' => prj_key(), 'type' => 'acb']) }}" readonly>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-primary" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">AutoBank | Web2m | MBBank</div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'web2m_mbbank']) }}" class="axios-form" method="POST">
            @csrf
            <div class="mb-3 row">
              <div class="col-md-4">
                <label for="account_number" class="form-label">Số Tài Khoản</label>
                <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $web2m_mbbank['account_number'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="account_password" class="form-label">Mật khẩu Bank</label>
                <input type="password" class="form-control" id="account_password" name="account_password" value="{{ $web2m_mbbank['account_password'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="api_token" class="form-label">API Token Web2m</label>
                <input type="password" class="form-control" id="api_token" name="api_token" value="{{ $web2m_mbbank['api_token'] ?? '' }}">
              </div>
            </div>
            <div class="mb-3">
              <label for="link_cron" class="form-label">Link Cron (manual)</label>
              <input type="text" class="form-control" id="link_cron" name="link_cron" value="{{ route('cron.deposit.check', ['key' => prj_key(), 'type' => 'mbbank']) }}" readonly>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-primary" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">AutoBank | Web2m | BIDV</div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'web2m_bidv']) }}" class="axios-form" method="POST">
            @csrf
            <div class="mb-3 row">
              <div class="col-md-4">
                <label for="account_number" class="form-label">Số Tài Khoản</label>
                <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $web2m_bidv['account_number'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="account_password" class="form-label">Mật khẩu Bank</label>
                <input type="password" class="form-control" id="account_password" name="account_password" value="{{ $web2m_bidv['account_password'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="api_token" class="form-label">API Token Web2m</label>
                <input type="password" class="form-control" id="api_token" name="api_token" value="{{ $web2m_bidv['api_token'] ?? '' }}">
              </div>
            </div>
            <div class="mb-3">
              <label for="link_cron" class="form-label">Link Cron (manual)</label>
              <input type="text" class="form-control" id="link_cron" name="link_cron" value="{{ route('cron.deposit.check', ['key' => prj_key(), 'type' => 'bidv']) }}" readonly>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-primary" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <h4 class="card-title">AutoBank | Web2m | MoMo</h4>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'web2m_momo']) }}" class="axios-form" method="POST">
            @csrf
            <div class="mb-3">
              <label for="api_token" class="form-label">API Token</label>
              <input type="text" class="form-control" id="api_token" name="api_token" value="{{ $web2m_momo['api_token'] ?? '' }}">
            </div>
            <div class="mb-3">
              <label for="link_cron" class="form-label">Link Cron (manual)</label>
              <input type="text" class="form-control" id="link_cron" name="link_cron" value="{{ route('cron.deposit.check', ['key' => prj_key(), 'type' => 'momo']) }}" readonly>
            </div>
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
          <h4 class="card-title">AutoBank | Perfect Money | USDT</h4>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'perfect_money']) }}" method="POST" class="axios-form">
            @csrf
            <div class="mb-3">
              <label for="account_id" class="form-label">Mã tài khoản</label>
              <input type="text" class="form-control" id="account_id" name="account_id" value="{{ $perfect_money['account_id'] ?? '' }}">
              <small>Vào đây để lấy mật mã tài khoản và đơn vị tiền tệ: <a href="https://perfectmoney.com/profile.html" target="_blank">https://perfectmoney.com/profile.html</a></small>
            </div>
            <div class="mb-3">
              <label for="passphrase" class="form-label">Mật khẩu Thay thế (Alternate Passphrase)</label>
              <input type="text" class="form-control" id="passphrase" name="passphrase" value="{{ $perfect_money['passphrase'] ?? '' }}">
            </div>
            <div class="mb-3">
              <label for="exchange" class="form-label">Tỷ giá quy đổi 1$</label>
              <input type="text" class="form-control" id="exchange" name="exchange" value="{{ $perfect_money['exchange'] ?? 24000 }}">
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-success-gradient" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <h4 class="card-title">AutoBank | Paypal | USD</h4>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'paypal']) }}" method="POST" class="axios-form">
            @csrf
            <div class="mb-3 row">
              <div class="col-md-4">
                <label for="client_id" class="form-label">Client ID</label>
                <input type="text" class="form-control" id="client_id" name="client_id" value="{{ $paypal['client_id'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="client_secret" class="form-label">Client Secret</label>
                <input type="text" class="form-control" id="client_secret" name="client_secret" value="{{ $paypal['client_secret'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="exchange" class="form-label">Exchange VND</label>
                <input type="text" class="form-control" id="exchange" name="exchange" value="{{ $paypal['exchange'] ?? 23000 }}">
              </div>
              <input type="hidden" id="token" value="{{ auth()->user()->access_token }}" />
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-success-gradient" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <h4 class="card-title">AutoBank | FPayment | USDT</h4>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'fpayment']) }}" class="axios-form" method="POST">
            @csrf
            <div class="mb-3 row">
              <div class="col-md-4">
                <label for="address_wallet" class="form-label">Address Wallet</label>
                <input type="text" class="form-control" id="address_wallet" name="address_wallet" value="{{ $fpayment['address_wallet'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="token_wallet" class="form-label">Token Wallet</label>
                <input type="text" class="form-control" id="token_wallet" name="token_wallet" value="{{ $fpayment['token_wallet'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="exchange" class="form-label">Exchange Rate</label>
                <input type="text" class="form-control" id="exchange" name="exchange" value="{{ $fpayment['exchange'] ?? 1 }}">
              </div>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-primary" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <h4 class="card-title">Notification | Telegram</h4>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'telegram']) }}" class="axios-form" method="POST">
            @csrf
            <div class="mb-3 row">
              <div class="col-md-4">
                <label for="bot_token" class="form-label">BOT Token</label>
                <input type="text" class="form-control" id="bot_token" name="bot_token" value="{{ $telegram['bot_token'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="chat_id" class="form-label">ChatID Nhận</label>
                <input type="text" class="form-control" id="chat_id" name="chat_id" value="{{ $telegram['chat_id'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-select" id="status" name="status">
                  <option value="1" {{ ($telegram['status'] ?? 0) == 1 ? 'selected' : '' }}>Bật</option>
                  <option value="0" {{ ($telegram['status'] ?? 0) == 0 ? 'selected' : '' }}>Tắt</option>
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

    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <h4 class="card-title">Notification | E-Mail | SMTP</h4>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'smtp_server']) }}" class="axios-form" method="POST">
            @csrf
            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="host" class="form-label">SMTP Host</label>
                <input type="text" class="form-control" id="host" name="host" value="{{ $smtp_server['host'] ?? '' }}" placeholder="smtp.gmail.com">
              </div>
              <div class="col-md-6">
                <label for="port" class="form-label">SMTP Port</label>
                <input type="text" class="form-control" id="port" name="port" value="{{ $smtp_server['port'] ?? '' }}" placeholder="587">
              </div>
            </div>
            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="user" class="form-label">SMTP Username</label>
                <input type="text" class="form-control" id="user" name="user" value="{{ $smtp_server['user'] ?? '' }}" placeholder="example@gmail.com">
              </div>
              <div class="col-md-6">
                <label for="password" class="form-label">SMTP Password</label>
                <input type="password" class="form-control" id="pass" name="pass" value="{{ $smtp_server['pass'] ?? '' }}" placeholder="abcxty abcxty abcxty abcxty hoặc abcxtyabcxtyabcxtyabcxty">
              </div>
            </div>
            <div class="mb-3">
              <label for="name" class="form-label">From Name</label>
              <input type="text" class="form-control" id="name" name="name" value="{{ $smtp_server['name'] ?? '' }}" placeholder="SMMPanel Provider">
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-primary" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <h4 class="card-title">Charging Card</h4>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'charging_card']) }}" class="axios-form" method="POST">
            @csrf
            <div class="mb-3 row">
              <div class="col-md-4">
                <label for="api_url" class="form-label">API Url</label>
                <input type="text" class="form-control" id="api_url" name="api_url" value="{{ $charging_card['api_url'] ?? 'https://card24h.com' }}" placeholder="https://card24h.com">
              </div>
              <div class="col-md-4">
                <label for="partner_id" class="form-label">Partner ID</label>
                <input type="text" class="form-control" id="partner_id" name="partner_id" value="{{ $charging_card['partner_id'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="partner_key" class="form-label">Partner Key</label>
                <input type="text" class="form-control" id="partner_key" name="partner_key" value="{{ $charging_card['partner_key'] ?? '' }}">
              </div>

            </div>
            <div class="mb-3 row">
              <div class="col-md-3">
                <label for="fees_viettel" class="form-label">Phí Thẻ Viettel</label>
                <input type="text" class="form-control" id="fees_viettel" name="fees[VIETTEL]" value="{{ $charging_card['fees']['VIETTEL'] ?? 20 }}" placeholder="30">
              </div>
              <div class="col-md-3">
                <label for="fees_vinaphone" class="form-label">Phí Thẻ Vinaphone</label>
                <input type="text" class="form-control" id="fees_vinaphone" name="fees[VINAPHONE]" value="{{ $charging_card['fees']['VINAPHONE'] ?? 20 }}" placeholder="30">
              </div>
              <div class="col-md-3">
                <label for="fees_mobifone" class="form-label">Phí Thẻ Mobifone</label>
                <input type="text" class="form-control" id="fees_mobifone" name="fees[MOBIFONE]" value="{{ $charging_card['fees']['MOBIFONE'] ?? 20 }}" placeholder="30">
              </div>
              <div class="col-md-3">
                <label for="fees_zing" class="form-label">Phí Thẻ Zing</label>
                <input type="text" class="form-control" id="fees_zing" name="fees[ZING]" value="{{ $charging_card['fees']['ZING'] ?? 20 }}" placeholder="30">
              </div>
            </div>
            <div class="mb-3">
              <label for="link_callback" class="form-label">Link Callback (POST)</label>
              <input type="text" class="form-control" id="link_callback" name="link_callback" value="{{ route('cron.deposit.card-callback', ['key' => prj_key()]) }}" readonly>
            </div>
            <div class="mb-3">
              <label for="link_cron" class="form-label">Link Cron (manual)</label>
              <input type="text" class="form-control" id="link_cron" name="link_cron" value="{{ route('cron.deposit.check', ['key' => prj_key(), 'type' => 'card']) }}" readonly>
            </div>
            <div class="mb-3">
              <small>* Chọn 1 trong 2 loại link trên - không được dùng 1 lần 2 link</small>
            </div>
            <div class="mb-3 text-end">
              <button class="mt-2 btn btn-primary" type="submit">Cập nhật ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

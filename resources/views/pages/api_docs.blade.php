@extends('layouts.app')
@section('title', $pageTitle)
@section('content')
  <style>
    pre {
      direction: ltr;
      unicode-bidi: bidi-override;
      padding-left: 24px;
      padding-right: 24px;
      padding-top: 24px;
      padding-bottom: 24px;
      font-weight: 300;
      background: yellowgreen;
      color: black;
      border-color: yellowgreen;
      box-shadow: firebrick;
      border-style: solid;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
      border-bottom-left-radius: 8px;
      border-bottom-right-radius: 8px;
      border-left-width: 0px;
      border-right-width: 0px;
      border-top-width: 0px;
      border-bottom-width: 0px;
    }
  </style>
  <div class="card">
    <div class="card-body">
      <h2 class="mb-3">API</h2>
      <div class="table-responsive mb-3">
        <table class="table">
          <tbody>
            <tr>
              <td class="width-40">HTTP Method</td>
              <td>POST</td>
            </tr>
            <tr>
              <td>API URL</td>
              <td>https://{{ domain() }}/api/v2</td>
            </tr>
            <tr>
              <td>API Key</td>
              <td>
                Lấy key tại trang <a href="{{ route('account.profile.index') }}">{{ __t('tài khoản') }}</a>
              </td>
            </tr>
            <tr>
              <td>Response format</td>
              <td>JSON</td>
            </tr>
          </tbody>
        </table>
      </div>
      <h4 class="mb-3">{{ __t('Danh sách dịch vụ') }}</h4>
      <div class="table-bg">
        <div class="table-wr ">
          <table class="table mb-3">
            <thead>
              <tr>
                <th class="width-40">Parameters</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>key</td>
                <td>Your API key</td>
              </tr>
              <tr>
                <td>action</td>
                <td>services</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div>
        <h6>Example response</h6>
      </div>
      <pre>
[
  {
  "service": 1,
  "name": "Followers",
  "type": "Default",
  "category": "First Category",
  "rate": "0.90",
  "min": "50",
  "max": "10000",
  "refill": true,
  "cancel": true
  },
  {
  "service": 2,
  "name": "Comments",
  "type": "Custom Comments",
  "category": "Second Category",
  "rate": "8",
  "min": "10",
  "max": "1500",
  "refill": false,
  "cancel": true
  }
]
</pre>
      <h4 class="mb-3">{{ __t('Tạo đơn hàng') }}</h4>
      <form class="">
        <div class="form-group">
          <select class="form-control input-sm" id="service_type">
            <option value="0">Default</option>
            {{-- <option value="10">Package</option> --}}
            <option value="2">Custom Comments</option>
            {{-- <option value="4">Mentions Custom List</option> --}}
            {{-- <option value="7">Mentions User Followers</option> --}}
            {{-- <option value="14">Custom Comments Package</option> --}}
            {{-- <option value="15">Comment Likes</option> --}}
            {{-- <option value="17">Poll</option> --}}
            {{-- <option value="100">Subscriptions</option> --}}
          </select>
        </div>
      </form>
      <div id="type_0" style="">
        <div class="table-bg">
          <div class="table-wr ">
            <table class="table mb-3">
              <thead>
                <tr>
                  <th class="width-40">Parameters</th>
                  <th>Description</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>key</td>
                  <td>Your API key</td>
                </tr>
                <tr>
                  <td>action</td>
                  <td>add</td>
                </tr>
                <tr>
                  <td>service</td>
                  <td>Service ID</td>
                </tr>
                <tr>
                  <td>link</td>
                  <td>Link to page</td>
                </tr>
                <tr>
                  <td>quantity</td>
                  <td>Needed quantity</td>
                </tr>
                <tr>
                  <td>runs (optional)</td>
                  <td>Runs to deliver</td>
                </tr>
                <tr>
                  <td>interval (optional)</td>
                  <td>Interval in minutes</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div id="type_10" style="display:none;">
        <div class="table-bg">
          <div class="table-wr ">
            <table class="table mb-3">
              <thead>
                <tr>
                  <th class="width-40">Parameters</th>
                  <th>Description</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>key</td>
                  <td>Your API key</td>
                </tr>
                <tr>
                  <td>action</td>
                  <td>add</td>
                </tr>
                <tr>
                  <td>service</td>
                  <td>Service ID</td>
                </tr>
                <tr>
                  <td>link</td>
                  <td>Link to page</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div id="type_2" style="display:none;">
        <div class="table-bg">
          <div class="table-wr ">
            <table class="table mb-3">
              <thead>
                <tr>
                  <th class="width-40">Parameters</th>
                  <th>Description</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>key</td>
                  <td>Your API key</td>
                </tr>
                <tr>
                  <td>action</td>
                  <td>add</td>
                </tr>
                <tr>
                  <td>service</td>
                  <td>Service ID</td>
                </tr>
                <tr>
                  <td>link</td>
                  <td>Link to page</td>
                </tr>
                <tr>
                  <td>comments</td>
                  <td>Comments list separated by \r\n or \n</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div id="type_4" style="display:none;">
        <div class="table-bg">
          <div class="table-wr ">
            <table class="table mb-3">
              <thead>
                <tr>
                  <th class="width-40">Parameters</th>
                  <th>Description</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>key</td>
                  <td>Your API key</td>
                </tr>
                <tr>
                  <td>action</td>
                  <td>add</td>
                </tr>
                <tr>
                  <td>service</td>
                  <td>Service ID</td>
                </tr>
                <tr>
                  <td>link</td>
                  <td>Link to page</td>
                </tr>
                <tr>
                  <td>usernames</td>
                  <td>Usernames list separated by \r\n or \n</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div id="type_7" style="display:none;">
        <div class="table-bg">
          <div class="table-wr ">
            <table class="table mb-3">
              <thead>
                <tr>
                  <th class="width-40">Parameters</th>
                  <th>Description</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>key</td>
                  <td>Your API key</td>
                </tr>
                <tr>
                  <td>action</td>
                  <td>add</td>
                </tr>
                <tr>
                  <td>service</td>
                  <td>Service ID</td>
                </tr>
                <tr>
                  <td>link</td>
                  <td>Link to page</td>
                </tr>
                <tr>
                  <td>quantity</td>
                  <td>Needed quantity</td>
                </tr>
                <tr>
                  <td>username</td>
                  <td>URL to scrape followers from</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div id="type_100" style="display:none;">
        <div class="table-bg">
          <div class="table-wr ">
            <table class="table mb-3">
              <thead>
                <tr>
                  <th class="width-40">Parameters</th>
                  <th>Description</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>key</td>
                  <td>Your API key</td>
                </tr>
                <tr>
                  <td>action</td>
                  <td>add</td>
                </tr>
                <tr>
                  <td>service</td>
                  <td>Service ID</td>
                </tr>
                <tr>
                  <td>username</td>
                  <td>Username</td>
                </tr>
                <tr>
                  <td>min</td>
                  <td>Quantity min</td>
                </tr>
                <tr>
                  <td>max</td>
                  <td>Quantity max</td>
                </tr>
                <tr>
                  <td>posts (optional)</td>
                  <td>Use this parameter if you want to limit the number of new (future) posts that will be parsed and for which orders will be created. If posts parameter is not set, the subscription will be created for
                    an unlimited number of posts.</td>
                </tr>
                <tr>
                  <td>old_posts (optional)</td>
                  <td>Number of existing posts that will be parsed and for which orders will be created, can be used if this option is available for the service.</td>
                </tr>
                <tr>
                  <td>delay</td>
                  <td>Delay in minutes. Possible values: 0, 5, 10, 15, 30, 60, 90, 120, 150, 180, 210, 240, 270, 300, 360, 420, 480, 540, 600</td>
                </tr>
                <tr>
                  <td>expiry (optional)</td>
                  <td>Expiry date. Format d/m/Y</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div id="type_14" style="display:none;">
        <div class="table-bg">
          <div class="table-wr ">
            <table class="table mb-3">
              <thead>
                <tr>
                  <th class="width-40">Parameters</th>
                  <th>Description</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>key</td>
                  <td>Your API key</td>
                </tr>
                <tr>
                  <td>action</td>
                  <td>add</td>
                </tr>
                <tr>
                  <td>service</td>
                  <td>Service ID</td>
                </tr>
                <tr>
                  <td>link</td>
                  <td>Link to page</td>
                </tr>
                <tr>
                  <td>comments</td>
                  <td>Comments list separated by \r\n or \n</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div id="type_15" style="display:none;">
        <div class="table-bg">
          <div class="table-wr ">
            <table class="table mb-3">
              <thead>
                <tr>
                  <th class="width-40">Parameters</th>
                  <th>Description</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>key</td>
                  <td>Your API key</td>
                </tr>
                <tr>
                  <td>action</td>
                  <td>add</td>
                </tr>
                <tr>
                  <td>service</td>
                  <td>Service ID</td>
                </tr>
                <tr>
                  <td>link</td>
                  <td>Link to page</td>
                </tr>
                <tr>
                  <td>quantity</td>
                  <td>Needed quantity</td>
                </tr>
                <tr>
                  <td>username</td>
                  <td>Username of the comment owner</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div id="type_17" style="display:none;">
        <div class="table-bg">
          <div class="table-wr ">
            <table class="table mb-3">
              <thead>
                <tr>
                  <th class="width-40">Parameters</th>
                  <th>Description</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>key</td>
                  <td>Your API key</td>
                </tr>
                <tr>
                  <td>action</td>
                  <td>add</td>
                </tr>
                <tr>
                  <td>service</td>
                  <td>Service ID</td>
                </tr>
                <tr>
                  <td>link</td>
                  <td>Link to page</td>
                </tr>
                <tr>
                  <td>quantity</td>
                  <td>Needed quantity</td>
                </tr>
                <tr>
                  <td>answer_number</td>
                  <td>Answer number of the poll</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div>
        <h6>Example response</h6>
      </div>
      <pre>
{
  "order": 23501
}
</pre>
      <h4 class="mb-3">{{ __t('Trạng thái đơn') }}</h4>
      <div class="table-bg">
        <div class="table-wr ">
          <table class="table mb-3">
            <thead>
              <tr>
                <th class="width-40">Parameters</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>key</td>
                <td>Your API key</td>
              </tr>
              <tr>
                <td>action</td>
                <td>status</td>
              </tr>
              <tr>
                <td>order</td>
                <td>Order ID</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div>
        <h6>Example response</h6>
      </div>
      <pre>
{
  "charge": "0.27819",
  "start_count": "3572",
  "status": "Partial",
  "remains": "157",
  "currency": "USD"
}
</pre>
      <h4 class="mb-3">{{ __t('Lấy nhiều đơn cùng lúc') }}</h4>
      <div class="table-bg">
        <div class="table-wr ">
          <table class="table mb-3">
            <thead>
              <tr>
                <th class="width-40">Parameters</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>key</td>
                <td>Your API key</td>
              </tr>
              <tr>
                <td>action</td>
                <td>status</td>
              </tr>
              <tr>
                <td>orders</td>
                <td>Order IDs (separated by a comma, up to 100 IDs)</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div>
        <h6>Example response</h6>
      </div>
      <pre>
{
  "1": {
  "charge": "0.27819",
  "start_count": "3572",
  "status": "Partial",
  "remains": "157",
  "currency": "USD"
  },
  "10": {
  "error": "Incorrect order ID"
  },
  "100": {
  "charge": "1.44219",
  "start_count": "234",
  "status": "In progress",
  "remains": "10",
  "currency": "USD"
  }
}
</pre>
      <h4 class="mb-3">Create refill</h4>
      <div class="table-bg">
        <div class="table-wr ">
          <table class="table mb-3">
            <thead>
              <tr>
                <th class="width-40">Parameters</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>key</td>
                <td>Your API key</td>
              </tr>
              <tr>
                <td>action</td>
                <td>refill</td>
              </tr>
              <tr>
                <td>order</td>
                <td>Order ID</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div>
        <h6>Example response</h6>
      </div>
      <pre>
{
  "refill": "1"
}
</pre>
      <h4 class="mb-3">Create multiple refill</h4>
      <div class="table-bg">
        <div class="table-wr ">
          <table class="table mb-3">
            <thead>
              <tr>
                <th class="width-40">Parameters</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>key</td>
                <td>Your API key</td>
              </tr>
              <tr>
                <td>action</td>
                <td>refill</td>
              </tr>
              <tr>
                <td>orders</td>
                <td>Order IDs (separated by a comma, up to 100 IDs)</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div>
        <h6>Example response</h6>
      </div>
      <pre>
[
  {
    "order": 1,
    "refill": 1
  },
  {
    "order": 2,
    "refill": 2
  },
  {
  "order": 3,
  "refill": {
      "error": "Incorrect order ID"
    }
  }
]
</pre>
      <h4 class="mb-3">Get refill status</h4>
      <div class="table-bg">
        <div class="table-wr ">
          <table class="table mb-3">
            <thead>
              <tr>
                <th class="width-40">Parameters</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>key</td>
                <td>Your API key</td>
              </tr>
              <tr>
                <td>action</td>
                <td>refill_status</td>
              </tr>
              <tr>
                <td>refill</td>
                <td>Refill ID</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div>
        <h6>Example response</h6>
      </div>
      <pre>
{
  "status": "Completed"
}
</pre>
      <h4 class="mb-3">Get multiple refill status</h4>
      <div class="table-bg">
        <div class="table-wr ">
          <table class="table mb-3">
            <thead>
              <tr>
                <th class="width-40">Parameters</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>key</td>
                <td>Your API key</td>
              </tr>
              <tr>
                <td>action</td>
                <td>refill_status</td>
              </tr>
              <tr>
                <td>refills</td>
                <td>Refill IDs (separated by a comma, up to 100 IDs)</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div>
        <h6>Example response</h6>
      </div>
      <pre>
[
  {
  "refill": 1,
  "status": "Completed"
  },
  {
  "refill": 2,
  "status": "Rejected"
  },
  {
  "refill": 3,
  "status": {
      "error": "Refill not found"
    }
  }
]
</pre>
      <h4 class="mb-3">Create cancel</h4>
      <div class="table-bg">
        <div class="table-wr ">
          <table class="table mb-3">
            <thead>
              <tr>
                <th class="width-40">Parameters</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>key</td>
                <td>Your API key</td>
              </tr>
              <tr>
                <td>action</td>
                <td>cancel</td>
              </tr>
              <tr>
                <td>orders</td>
                <td>Order IDs (separated by a comma, up to 100 IDs)</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div>
        <h6>Example response</h6>
      </div>
      <pre>
[
  {
  "order": 9,
  "cancel": {
      "error": "Incorrect order ID"
    }
  },
  {
    "order": 2,
    "cancel": 1
  }
]
</pre>
      <h4 class="mb-3">User balance</h4>
      <div class="table-bg">
        <div class="table-wr ">
          <table class="table mb-3">
            <thead>
              <tr>
                <th class="width-40">Parameters</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>key</td>
                <td>Your API key</td>
              </tr>
              <tr>
                <td>action</td>
                <td>balance</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div>
        <h6>Example response</h6>
      </div>
      <pre>
{
  "balance": "100.84292",
  "currency": "USD"
}
</pre>
      <a href="/example.txt" class="btn btn-big-secondary" target="_blank">Example of PHP code</a>
    </div>
  </div>
@endsection
@section('scripts')

  <script>
    $("#service_type").change(function() {
      $("div[id^='type_']").hide();
      var e = $("#service_type").val();
      $("#type_" + e).show();
    })
    $("#service_type").trigger("change");
  </script>
@endsection

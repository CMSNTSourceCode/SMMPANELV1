@php
  $providers = \App\Models\ApiProvider::where('status', true)->orderBy('id', 'desc')->get();
@endphp
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="card-body">
  <div class="mb-3">
    <label for="api_provider_id" class="form-label">Provider</label>
    <select name="api_provider_id" id="api_provider_id" class="form-control">
      <option value="">Select a provider</option>
      @foreach ($providers as $provider)
        <option value="{{ $provider->id }}" @if (request()->input('provider_id', null) == $provider->id) selected @endif>{{ $provider->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="mb-3">
    <label for="api_service_id" class="form-label">Service ID - <a href="javascript:void(0)" onclick="updateServiceName()">Set same name</a></label>
    <select name="api_service_id" id="api_service_id" class="form-control"></select>
  </div>
  <div class="mb-3">
    <label for="original_rate" class="form-label">Original Rate per 1000</label>
    <input type="text" class="form-control" name="original_rate" id="original_rate" readonly>
  </div>
  <div class="mb-3">
    <label for="service_type" class="form-label">Loại dịch vụ</label>
    <input type="text" class="form-control" name="service_type" id="service_type" placeholder="Có thể sửa cho phù hợp: default, custom_comments...">
  </div>
</div>
<script>
  const loadServices = () => {
    if ($("#api_provider_id").val() == '') {
      $("#api_service_id").html('<option>Select a provider first</option>');
      return;
    }

    $("#api_service_id").html('<option>Loading...</option>');
    axios.post('{{ route('admin.providers.forms', ['type' => 'api']) }}', {
      action: 'get-services',
      provider_id: $("#api_provider_id").val(),
      provider_service_id: {{ request()->input('provider_service_id', 'undefined') }}
    }).then(response => {
      $("#api_service_id").html(response.data);
    }).then(() => {
      loadServiceRate()
    })
  }

  const updateServiceName = () => {
    const serviceName = $("#api_service_id option:selected").data('name');
    $("#name")?.val(serviceName);
  }

  const loadServiceConfig = () => {
    const serviceId = $("#api_service_id").val();
    const serviceData = []
    $("#api_service_id option").each((index, option) => {
      if (option.value == serviceId) {
        serviceData['rate'] = $(option).data('rate');
        serviceData['min'] = $(option).data('min');
        serviceData['max'] = $(option).data('max');
        serviceData['dripfeed'] = $(option).data('dripfeed');
        serviceData['refill'] = $(option).data('refill');
        serviceData['type'] = $(option).data('type');
      }
    })

    $("#original_rate")?.val(serviceData['rate']);
    $("#price")?.val(serviceData['rate']);
    $("#min_buy")?.val(serviceData['min']);
    $("#max_buy")?.val(serviceData['max']);
    $("#dripfeed")?.val(serviceData['dripfeed']);
    $("#refill")?.val(serviceData['refill']);
    $("#service_type")?.val(serviceData['type'])
  }

  const loadServiceRate = () => {
    const serviceId = $("#api_service_id").val();
    const serviceData = []
    $("#api_service_id option").each((index, option) => {
      if (option.value == serviceId) {
        serviceData['rate'] = $(option).data('rate');
        serviceData['type'] = $(option).data('type');
      }
    })
    $("#original_rate")?.val(serviceData['rate']);
    // $("#price")?.val(serviceData['rate']);
    $("#service_type")?.val(serviceData['type'])
  }

  $("#api_provider_id").change(() => {
    loadServices()
  })

  $("#api_service_id").change(() => {
    loadServiceConfig()
  })

  $(document).ready(() => {
    loadServices()
  })
</script>

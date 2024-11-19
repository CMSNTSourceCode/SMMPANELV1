<div class="mb-3">
  <label for="duration" class="form-label">{{ __t('Số phút xem') }}</label>
  <input type="number" name="duration" id="duration" class="form-control" value="30" min="1" max="320">
</div>
<script>
  $(document).ready(() => {
    "use strict";

    $("#duration").change(() => SUM_PRICE_FNC())
  })
</script>

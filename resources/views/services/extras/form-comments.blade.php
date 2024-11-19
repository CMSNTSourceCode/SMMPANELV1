<input type="hidden" id="form_comment_loaded" value="1">

<label for="comments" class="form-label">{{ __t('Nội Dung') }} <i class="text-danger comment_count">0</i> : </label>
<textarea class="form-control" id="comments" name="comments" rows="3" placeholder="{{ __t('Nhập nội dung bình luận, mỗi dòng là 1 bình luận') }}"></textarea>

<script>
  $(document).ready(() => {
    "use strict"

    $("#quantity").val(0).attr("readonly", true);

    $("#comments").on("keyup", function() {
      const value = $(this).val();
      const count = value.split("\n").length;

      update(count)
    });

    const update = (count) => {
      $("[name=quantity]").val(count)
      $(".form-label .comment_count").text(count);

      if (SUM_PRICE_FNC !== null)
        SUM_PRICE_FNC()
    }
  })
</script>

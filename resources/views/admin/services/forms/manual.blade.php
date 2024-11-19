<div class="card-body">
  <div class="mode__manual">
    <label for="service_type" class="form-label">Loại dịch vụ</label>
    <select name="service_type" id="service_type" class="form-control">
      <option value="default">Default</option>
      <option value="custom_comments">Custom comments</option>
      {{-- <option value="custom_comments_package">Custom comments package</option> --}}
      {{-- <option value="mentions_with_hashtags">Mentions with hashtags</option> --}}
      {{-- <option value="mentions_custom_list">Mentions custom list</option> --}}
      {{-- <option value="mentions_hashtag">Mentions hashtag</option> --}}
      {{-- <option value="mentions_user_followers">Mentions user followers</option> --}}
      {{-- <option value="mentions_media_likers">Mentions media likers</option> --}}
      {{-- <option value="package">Package</option> --}}
      {{-- <option value="comment_likes">Comment likes</option> --}}
    </select>
  </div>
</div>
@if (request()->has('service_type'))
  <script>
    $(document).ready(function() {
      $("#service_type").val("{{ request()->input('service_type') }}");
    });
  </script>
@endif

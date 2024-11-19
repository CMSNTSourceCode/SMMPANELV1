<style>
  .checkbox:checked+img {
    border: 3px solid #44bd32;
    position: relative;
    scale: 1.3;
    transition: all 0.3s ease;
  }
</style>
@php $reactions = ['like', 'care','love', 'haha', 'wow', 'sad', 'angry']; @endphp
<input type="hidden" id="form_reaction_loaded" value="1">
<label for="reaction" class="form-label">{{ __t('Cảm Xúc') }} : </label>
<div class="gap-3 mb-2" style="margin-left: 5px">
  @foreach ($reactions as $reaction)
    <div class="form-check-inline">
      <label class="form-check-label" for="formRadiosI_{{ $loop->iteration }}">
        <input class="form-check-input checkbox d-none" type="radio" name="reaction" id="formRadiosI_{{ $loop->iteration }}" value="{{ $reaction }}" @if ($reaction === 'like') checked @endif>
        <img src="/images/fb-reactions/{{ $reaction }}.png" alt="{{ $reaction }}" class="d-block ml-2 rounded-circle" style="width: 30px">
      </label>
    </div>
  @endforeach
</div>

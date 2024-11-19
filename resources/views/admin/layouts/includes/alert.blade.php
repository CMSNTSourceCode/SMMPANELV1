@if (Session::has('message'))
  <div class="alert alert-primary" role="alert">
    {{ Session::get('message') }}
  </div>
@endif
@if (Session::has('error'))
  <div class="alert alert-danger" role="alert">
    @if (is_array(Session::get('error')))
      <ul class="mb-0">
        @foreach (Session::get('error') as $message)
          <li>{{ $message }}</li>
        @endforeach
      </ul>
    @else
      {{ Session::get('error') }}
    @endif
  </div>
@endif
@if (Session::has('success'))
  <div class="alert alert-success" role="alert">
    @if (is_array(Session::get('success')))
      <ul class="mb-0">
        @foreach (Session::get('success') as $message)
          <li>{{ $message }}</li>
        @endforeach
      </ul>
    @else
      {{ Session::get('success') }}
    @endif
  </div>
@endif
@if ($errors->any())
  <div class="alert alert-danger" role="alert">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

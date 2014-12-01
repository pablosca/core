@if( $errors->any() || Session::has('message'))
  <div class="alert alert-{{ Session::get('code', 400) }}">
    @if ($errors->any())
      @if ($errors->first() != Session::get('message'))
        {{ Session::get('message') }}
      @endif
      @foreach($errors->all(':message ') as $error)
        {{ $error }}
      @endforeach
    @else
      {{ Session::get('message') }}
    @endif
  </div>
@endif

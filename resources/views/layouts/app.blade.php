<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'LaraBBS')</title>
  <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
  @yield('SimditorStyle')
</head>
<body>
  <div id="app" class="{{ route_class() }}-page">
    @include('layouts._header')

    <div class="container">
      @include('shared._messages')

      @yield('content')
    </div>

    @include('layouts._footer')
  </div>

  @if (config('app.debug'))
    @include('sudosu::user-selector')
  @endif
  <!-- Scripts -->
  <script src="{{ mix('js/app.js') }}"></script>
  @yield('SimditorScript')
</body>
</html>

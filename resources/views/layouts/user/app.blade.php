<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} | Rumah Sakit Citra Husada Jember</title>
    <link href="{{ asset('images/favicon.ico') }}" rel="shortcut icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/appuser.css') }}">
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    {{ $styles }}
  </head>
  <body>
  <main class="wrapper">
    {{ $slot }}
  </main>
  <script src="{{ asset('js/appuser.js') }}"></script>
  <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  {{ $scripts }}
  </body>
</html>

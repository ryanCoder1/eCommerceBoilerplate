<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>
        <link rel="shortcut icon" href="">
        <!--  <link rel="icon" href="data:;base64,iVBORw0KGgo=">-->
        <!-- Fonts -->
        <link href="{{ asset('/css/app.css') }}" rel="stylesheet" type="text/css">

        <script>
           // rename myToken as you like
           window.myToken =  <?php echo json_encode([
               'csrfToken' => csrf_token(),
           ]); ?>
        </script>

    </head>
    <body>

      <div id="app">

      </div>

      <script src="{{ asset('js/app.js') }}"></script>

    </body>
</html>

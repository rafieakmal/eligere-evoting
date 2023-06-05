<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="csrf-token" value="{{ csrf_token() }}"/>
    <link rel="icon" type = "image/png" href = "/assets/img/logo/svg/logo-black.svg">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
    <link rel="stylesheet" href="/assets/css/-Countdown-BS4--Countdown.css">
    <link rel="stylesheet" href="/assets/css/Articles-Cards-images.css">
    <link rel="stylesheet" href="/assets/css/Countdown.css">

  </head>
  <body>
    <div id="app">
        <app></app>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
    <script src="/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/js/startup-modern.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.11/vue.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@2.0.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/blazeface@0.0.5"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs-backend-wasm@2.0.0/dist/tf-backend-wasm.js"></script>
  </body>
</html>

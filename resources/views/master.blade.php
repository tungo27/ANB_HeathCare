<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Store</title>
    <base href="{{asset('')}}">

    <link href='https://fonts.googleapis.com/css?family=Dosis:300,400,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" title="style" href="source/assets/dest/css/style.css">
    <link rel="stylesheet" title="style" href="source/assets/dest/css/huong-style.css">

    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f8f9fa;
        }
        .rev-slider {
            padding: 50px 0;
        }
    </style>
</head>

<body>

    {{-- @include('header') --}}

    <div class="rev-slider">
        @yield('content')
    </div>

    {{-- @include('footer') --}}

   </body>
</html>
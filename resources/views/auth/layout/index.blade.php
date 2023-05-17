<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') Blog</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/auth.css"> 
    @yield('css')
  </head>
  <body>
    <div class="container">
      <!-- main container -->
      <div class="content">
        <!-- logo container -->
        <div class="left-content">
          <div class="f-logo">
            <img src="assets/img/logo.png" alt="Facebook" />
          </div>
          <h2 class="f-quote">Blog helps you connect and share with the people in your life.</h2>
        </div>
        <!-- auth form --> 
        @yield('content')
        <!-- footer --> 
        @include('auth.layout.footer')
      </div>
    </div>
  </body>
</html>
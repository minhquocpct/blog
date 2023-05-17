<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') Blog</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/boostrap.css" />
    <link rel="stylesheet" href="assets/css/css-font.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" /> 
    @yield('css')
  </head>
  <body>
    <!-- header starts --> 
    @include('page.layout.header')
    <!-- header ends -->
    <!-- main body starts -->
    <div class="main__body"> 
      @include('page.layout.leftsidebar')
      @yield('content') 
    </div> 
      @include('page.layout.rightsidebar')
    <!-- main body ends -->
    <div id="fb-root"></div>
    <script>
    function validateForm(id) {
      var context = document.getElementById("" + id).value;
      if (context == "" || context == null) {
        alert("You do not enter content");
        return false;
      }
    }
  </script> 
  </body>
</html>
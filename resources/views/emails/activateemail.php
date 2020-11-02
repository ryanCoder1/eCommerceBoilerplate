<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyCdL_L4qZKf-luf9gu9MRAGTDaiBdc9gXo"></script>
    <script src="{{ URL::to('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ URL::to('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/stylesheadernav.css') }}" rel="stylesheet">
</head>
<body>

 <div class="headerBar"> <span id="sign">Field Jobs Tracker </span></br><span id="signUnder"></span>
          <!--   <div class="openCloseDiv">
               &#9776;
          </div>

           <ul class="navUl">
              <li><a href="index.php">About</a></li>
              <li><a href="signinpage.php">Sign In</a></li>

             <li> <a class="nav-link text-success btn btn-outline-success" href="{{ route('logout') }}"
               onclick="event.preventDefault();                                                 document.getElementById('logout-form').submit();">
                Logout
            </a></li>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: one;">
               @csrf
            </form>
                      </ul>
                     <span class="facebookIcon"><a target="_blank" title="Any ?'s or Help visit Facebook Page" href="http://www.facebook.com/Field-Job-Tracker-1300631856721343/"><img alt="Any ?'s or Help visit Facebook Page" src="images/fieldjobstrackerFacebookSquare.png" border=0></a> </span>-->
             </div>

    <div id="app">
            An Email has been sent to verify account.


    </div>
</body>
</html>

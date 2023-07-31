<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Sky Tracker | @yield('title')</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.png') }}">
        <link rel="stylesheet" href="https://unpkg.com/vue-select@3.0.0/dist/vue-select.css">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    </head>
    <body class="sb-nav-fixed">
        
        @include('partials.navbar')

        <div id="layoutSidenav">
            
            @include('partials.sidebar')

            <div id="layoutSidenav_content">

                @yield('main-content') 
                
                @include('partials.footer')
            
            </div>
        </div>
        <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
        <script src="{{ asset('js/scripts.js') }}"></script>
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
        <script src=" {{ asset('js/all.min.js') }}" crossorigin="anonymous"></script>

        <!-- vue js utils -->
        <script src="{{ asset('js/vue.js') }}"></script>
        <script src="{{ asset('js/axios.min.js') }}"></script>
        <script src="{{ asset('js/vuejs-datatable.js') }}"></script>
        <script src="{{ asset('js/vue-select.js') }}"></script>
        <script src="{{ asset('js/moment.min.js') }}"></script>
        <script src="https://unpkg.com/vue-select@latest"></script>

        <script src="{{ asset('js/simple-datatables@latest.js') }}" crossorigin="anonymous"></script>
        <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
        <script src="{{asset('js/notify.min.js')}}"></script>
        <script type="text/javascript">
            setInterval(function() {
                var currentTime = new Date();
                var currentHours = currentTime.getHours();
                var currentMinutes = currentTime.getMinutes();
                var currentSeconds = currentTime.getSeconds();
                currentMinutes = (currentMinutes < 10 ? "0" : "") + currentMinutes;
                currentSeconds = (currentSeconds < 10 ? "0" : "") + currentSeconds;
                var timeOfDay = currentHours < 12 ? "AM" : "PM";
                currentHours = currentHours > 12 ? currentHours - 12 : currentHours;
                currentHours = currentHours == 0 ? 12 : currentHours;
                var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;
                document.getElementById("timer").innerHTML = currentTimeString;
            }, 1000);
        </script>
        @stack('script')
    </body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ ucfirst(config('app.name')) }} - {{ ucfirst($title) }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon"
        href="{{ !empty(app(App\Settings\ThemeSettings::class)->favicon) ? asset('storage/settings/' . app(App\Settings\ThemeSettings::class)->favicon) : asset('assets/img/favicon.png') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
   <script src="assets/js/html5shiv.min.js"></script>
   <script src="assets/js/respond.min.js"></script>
  <![endif]-->
</head>

<body class="account-page">

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <div class="account-content">
            {{-- <a href="{{route('job-list')}}" class="btn btn-primary apply-btn">Apply Job</a> --}}
            <div class="container">

                <!-- Account Logo -->
                <center>
                    <a href=""><img src="https://idejualan.com/wp-content/uploads/2022/06/logo-1.png"
                            width="80%" alt="logo"></a>
                </center>
                <!-- /Account Logo -->

                <div class="account-box">
                    <div class="account-wrapper">
                        <h3 class="account-title">Human-Resource Management Systems</h3>

                        <!--  Form -->
                        @yield('content')
                        <!-- / Form -->

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Main Wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery-3.2.1.min.js') }}"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

    <!-- Custom JS -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

</body>

</html>

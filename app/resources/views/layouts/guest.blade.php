
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>@yield('title', 'Authentication')</title>

    <!-- Global stylesheets -->
    <link href="{{ asset('assets/fonts/inter/inter.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/icons/phosphor/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/ltr/all.min.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script src="{{ asset('assets/demo/demo_configurator.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <!-- /theme JS files -->

</head>

<body>

    <!-- Main navbar -->
    <div class="navbar navbar-dark navbar-static py-2">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="{{ url('/') }}" class="d-inline-flex align-items-center">
                    <img src="{{ asset('assets/images/logo_icon.svg') }}" alt="">
                    <img src="{{ asset('assets/images/logo_text_light.svg') }}"
                         class="d-none d-sm-inline-block h-16px ms-3"
                         alt="">
                </a>
            </div>
        </div>
    </div>
    <!-- /main navbar -->

    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Inner content -->
            <div class="content-inner">

                <!-- Content area -->
                @yield('content')
                <!-- /content area -->

                <!-- Footer -->
                <div class="navbar navbar-sm navbar-footer border-top">
                    <div class="container-fluid">
                        <span>
                            &copy; 2022
                            <a href="https://themeforest.net/item/limitless-responsive-web-application-kit/13080328">
                                Limitless Web App Kit
                            </a>
                        </span>
                    </div>
                </div>
                <!-- /footer -->

            </div>
            <!-- /inner content -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

</body>
</html>

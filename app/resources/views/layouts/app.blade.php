
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>@yield('title', 'Dashboard')</title>

    <!-- Global stylesheets -->
    <link href="{{ asset('assets/fonts/inter/inter.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/icons/phosphor/styles.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/ltr/all.min.css') }}" id="stylesheet" rel="stylesheet">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script src="{{ asset('assets/demo/demo_configurator.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- /core JS files -->

    <!-- Common Theme JS files -->
    <script src="{{ asset('assets/js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <!-- /common theme JS files -->

    @stack('styles')

</head>

<body>

    <!-- Main navbar -->
    @include('components.layouts.navbar')
    <!-- /main navbar -->

    <!-- Page content -->
    <div class="page-content">

        <!-- Main sidebar -->
        @include('components.layouts.sidebar')
        <!-- /main sidebar -->

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Inner content -->
            <div class="content-inner">

                <!-- Page header -->
                @include('components.layouts.page-header')
                <!-- /page header -->

                <!-- Content area -->
                <div class="content">
                    @yield('content')
                </div>
                <!-- /content area -->

                <!-- Footer -->
                @include('components.layouts.footer')
                <!-- /footer -->

            </div>
            <!-- /inner content -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

    @stack('scripts')

</body>
</html>

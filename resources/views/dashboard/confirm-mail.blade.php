<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title data-setting="app_name" data-rightJoin=" Responsive Bootstrap 5 Admin Dashboard Template">Streamit
        Responsive Bootstrap 5 Admin Dashboard Template</title>
    <meta name="description"
        content="Streamit is a revolutionary Bootstrap Admin Dashboard Template and UI Components Library. The Admin Dashboard Template and UI Component features 8 modules.">
    <meta name="keywords"
        content="premium, admin, dashboard, template, bootstrap 5, clean ui, streamit, admin dashboard,responsive dashboard, optimized dashboard, simple auth">
    <meta name="author" content="Iqonic Design">
    <meta name="DC.title" content="Streamit Simple | Responsive Bootstrap 5 Admin Dashboard Template">
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/admin/images/favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Library / Plugin Css Build -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/core/libs.min.css') }}">

    <!-- streamit Design System Css -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/streamit.min.css?v=1.0.1') }}">

    <!-- Custom Css -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/custom.min.css?v=1.0.1') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/dashboard-custom.min.css?v=1.0.1') }}">


    <!-- Dark Css -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/dark.min.css?v=1.0.1') }}">

    <!-- Customizer Css -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/customizer.min.css?v=1.0.1') }}">

    <!-- RTL Css -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.min.css?v=1.0.1') }}">



    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,100;1,300&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/select2/dist/css/select2.min.css') }}">
</head>

<body class=" ">
    @include('sweetalert::alert')
    <!-- loader Start -->
    <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body ">
                <img src="{{ asset('assets/admin/images/loader.gif') }}" alt="loader" class="image-loader img-fluid ">
            </div>
        </div>
    </div>
    <!-- loader END -->
    <div class="wrapper">

        <section class="sign-in-page">
            <div class="container">
                <div class="justify-content-center align-items-center height-self-center row">
                    <div class="align-self-center col-lg-5 col-md-12">
                        <div class="sign-user_card">
                            <div class="sign-in-page-data">
                                <div class="sign-in-from w-100 m-auto"><img src="/images/dashboard/login/mail.png" width="80" alt="">
                                   <h3 class="mt-3 mb-0" style="color: #fff">Success !</h3>
                                   <p class="text-white">A email has been send to <a role="button" tabindex="0"
                                         href="/cdn-cgi/l/email-protection"
                                         data-cfemail="5f26302a2d3a323e36331f3b30323e3631713c303271"
                                         class="__cf_email__ bg-dark border-0 p-0 btn btn-primary">[email&nbsp;protected]</a> Please
                                      check for an email from company and click on the included link to reset your password.</p>
                                   <div class="d-inline-block w-100"><a class="btn btn-primary mt-3" href="{{url('system-login')}}">Back to Login</a></div>
                                </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Library Bundle Script -->
    <script src="{{ asset('assets/admin/js/core/libs.min.js') }}"></script>

    <!-- Slider-tab Script -->
    <script src="{{ asset('assets/admin/js/plugins/slider-tabs.js') }}"></script>
    <!-- Lodash Utility -->
    <script src="{{ asset('assets/admin/vendor/lodash/lodash.min.js') }}"></script>
    <!-- Utilities Functions -->
    <script src="{{ asset('assets/admin/js/iqonic-script/utility.min.js') }}"></script>
    <!-- Settings Script -->
    <script src="{{ asset('assets/admin/js/iqonic-script/setting.min.js') }}"></script>
    <!-- Settings Init Script -->
    <script src="{{ asset('assets/admin/js/setting-init.js') }}"></script>
    <!-- External Library Bundle Script -->
    <script src="{{ asset('assets/admin/js/core/external.min.js') }}"></script>
    <!-- Widgetchart Script -->
    <script src="{{ asset('assets/admin/js/charts/widgetcharts.js?v=1.0.1') }}" defer></script>
    <!-- Dashboard Script -->
    <script src="{{ asset('assets/admin/js/charts/dashboard.js?v=1.0.1') }}" defer></script>
    <!-- qompacui Script -->
    <script src="{{ asset('assets/admin/js/streamit.js?v=1.0.1') }}" defer></script>
    <script src="{{ asset('assets/admin/js/sidebar.js?v=1.0.1') }}" defer></script>
    <script src="{{ asset('assets/admin/js/chart-custom.js?v=1.0.1') }}" defer></script>

    <script src="{{ asset('assets/admin/js/plugins/select2.js?v=1.0.1') }}" defer></script>

    <script src="{{ asset('assets/admin/js/plugins/flatpickr.js?v=1.0.1') }}" defer></script>

    <script src="{{ asset('assets/admin/js/main.js') }}"></script>

</body>

</html>

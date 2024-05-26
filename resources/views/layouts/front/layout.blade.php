<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>StreamIT | Responsive Bootstrap 5 Template</title>
    <!-- Google Font Api KEY-->
    <meta name="google_font_api" content="AIzaSyBG58yNdAjc20_8jAvLNSVi9E4Xhwjau_k">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/user/images/favicon.ico') }}" />

    <!-- Library / Plugin Css Build -->
    <link rel="stylesheet" href="{{ asset('assets/user/css/core/libs.min.css') }}" />

    <!-- font-awesome css -->
    <link rel="stylesheet" href="{{ asset('assets/user/vendor/font-awesome/css/all.min.css') }}" />

    <!-- Iconly css -->
    <link rel="stylesheet" href="{{ asset('assets/user/vendor/iconly/css/style.css') }}" />

    <!-- Animate css -->
    <link rel="stylesheet" href="{{ asset('assets/user/vendor/animate.min.css') }}" />

    <!-- SwiperSlider css -->
    <link rel="stylesheet" href="{{ asset('assets/user/vendor/swiperSlider/swiper.min.css') }}">





    <!-- Streamit Design System Css -->
    <link rel="stylesheet" href="{{ asset('assets/user/css/streamit.min.css?v=1.0.0') }}" />

    <!-- Custom Css -->
    <link rel="stylesheet" href="{{ asset('assets/user/css/custom.min.css?v=1.0.0') }}" />

    <!-- Rtl Css -->
    <link rel="stylesheet" href="{{ asset('assets/user/css/rtl.min.css?v=1.0.0') }}" />

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    {!! NoCaptcha::renderJs() !!}

</head>

<body class=" custom-header-relative ">
    @include('sweetalert::alert')
    <span class="screen-darken"></span>
    <!-- loader Start -->
    <!-- loader Start -->
    <div class="loader simple-loader">
        <div class="loader-body">
            <img src="{{ asset('assets/user/images/loader.gif') }}" alt="loader" class="img-fluid " width="300">
        </div>
    </div>
    <!-- loader END --> <!-- loader END -->
    <main class="main-content">
        @include('layouts.front.header')

        <!--bread-crumb-->
        <!--bread-crumb-->

        @yield('content')

    </main>

    @include('layouts.front.footer')

    <div id="back-to-top" style="display: none;">
        <a class="p-0 btn bg-primary btn-sm position-fixed top border-0 rounded-circle" id="top" href="#top">
            <i class="fa-solid fa-chevron-up"></i>
        </a>
    </div>
    <!-- Wrapper End-->
    <!-- Library Bundle Script -->
    <script src="{{ asset('assets/user/js/core/libs.min.js') }}"></script>
    <script src="{{ asset('assets/user/js/main.js') }}"></script>
    <!-- Plugin Scripts -->


    <!-- SwiperSlider Script -->
    <script src="{{ asset('assets/user/vendor/swiperSlider/swiper.min.js') }}"></script>




    <!-- fslightbox Script -->
    <script src="{{ asset('assets/user/js/plugins/fslightbox.js') }}" defer></script>
    <!-- Lodash Utility -->
    <script src="{{ asset('assets/user/vendor/lodash/lodash.min.js') }}"></script>
    <!-- External Library Bundle Script -->
    <script src="{{ asset('assets/user/js/core/external.min.js') }}"></script>
    <!-- countdown Script -->
    <script src="{{ asset('assets/user/js/plugins/countdown.js') }}"></script>
    <!-- utility Script -->
    <script src="{{ asset('assets/user/js/utility.js') }}"></script>
    <!-- Setting Script -->
    <script src="{{ asset('assets/user/js/setting.js') }}"></script>
    <script src="{{ asset('assets/user/js/setting-init.js') }}" defer></script>
    <!-- Streamit Script -->
    <script src="{{ asset('assets/user/js/streamit.js') }}" defer></script>
    <script src="{{ asset('assets/user/js/swiper.js') }}" defer></script>
</body>

</html>

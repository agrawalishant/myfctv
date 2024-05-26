@extends('layouts.front.layout')
@section('content')
    <section class="banner-container">
        <div class="movie-banner">
            <div class="swiper swiper-banner-container" data-swiper="banner-detail-slider">
                <div class="swiper-wrapper">
                    @foreach ($sliders as $item)
                        <div class="swiper-slide movie-banner-1 p-0">
                            <div class="movie-banner-image">
                                <img src="{{ asset('storage/' . $item['image']) }}" alt="movie-banner-image">
                            </div>
                            <div class="shows-content h-100">
                                <div class="row align-items-center h-100">
                                    <div class="col-lg-7 col-md-12">
                                        <h1 class="texture-text big-font letter-spacing-1 line-count-1 text-uppercase RightAnimate-two"
                                            data-animation-in="fadeInLeft" data-delay-in="0.6">
                                            {{ $item['title'] }}
                                        </h1>
                                        <div class="flex-wrap align-items-center fadeInLeft animated"
                                            data-animation-in="fadeInLeft" style="opacity: 1;">
                                            <div class="d-flex flex-wrap align-items-center gap-3 movie-banner-time">
                                                <span class="badge bg-secondary p-2">
                                                    <i class="fa fa-eye"></i>
                                                    PG
                                                </span>
                                                <span class="font-size-6">
                                                    <i class="fa-solid fa-circle"></i>
                                                </span>
                                                @if ($item->post_type == 'movie' && $item->movie)
                                                    <span class="trending-year font-normal">
                                                        {{ optional($item->movie)->release_date }}
                                                    </span>
                                                @elseif($item->post_type == 'series' && $item->series)
                                                    <span class="trending-year font-normal">
                                                        {{ optional($item->series)->publish_year }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if ($item->post_type == 'movie' && $item->movie)
                                                <p class="movie-banner-text line-count-3" data-animation-in="fadeInUp"
                                                    data-delay-in="1.2">
                                                    {{ optional($item->movie)->description }}
                                                </p>
                                            @elseif($item->post_type == 'series' && $item->series)
                                                <p class="movie-banner-text line-count-3" data-animation-in="fadeInUp"
                                                    data-delay-in="1.2">
                                                    {{ optional($item->series)->short_description }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="iq-button" data-animation-in="fadeInUp" data-delay-in="1.2">
                                            <a href="{{ url($item->post_type == 'movie' ? 'movie/' . $item['id'] : 'series/' . $item['id']) }}"
                                                class="btn text-uppercase position-relative">
                                                <span class="button-text">Watch Now</span>
                                                <i class="fa-solid fa-play"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div
                                        class="col-lg-5 col-md-12 trailor-video iq-slider d-none d-lg-block position-relative">
                                        @if ($item->post_type == 'movie' && $item->movie && $item->movie->trailer)
                                            <a data-fslightbox="html5-video"
                                                href="https://streamingyogesh.s3.us-east-005.backblazeb2.com/{{ $item->movie->trailer }}"
                                                class="video-open playbtn text-decoration-none" tabindex="0">
                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                    width="80px" height="80px" viewBox="0 0 213.7 213.7"
                                                    enable-background="new 0 0 213.7 213.7" xml:space="preserve">
                                                    <polygon class="triangle" fill="none" stroke-width="7"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-miterlimit="10" points="73.5,62.5 148.5,105.8 73.5,149.1 ">
                                                    </polygon>
                                                    <circle class="circle" fill="none" stroke-width="7"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-miterlimit="10" cx="106.8" cy="106.8" r="103.3">
                                                    </circle>
                                                </svg>
                                                <span class="w-trailor text-uppercase"> Watch Trailer </span>
                                            </a>
                                        @elseif($item->post_type == 'series' && $item->series)
                                            @if ($item->series->local)
                                                <a data-fslightbox="html5-video"
                                                    href="https://streamingyogesh.s3.us-east-005.backblazeb2.com/{{ $item->series->local }}"
                                                    class="video-open playbtn text-decoration-none" tabindex="0">
                                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                        width="80px" height="80px" viewBox="0 0 213.7 213.7"
                                                        enable-background="new 0 0 213.7 213.7" xml:space="preserve">
                                                        <polygon class="triangle" fill="none" stroke-width="7"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-miterlimit="10"
                                                            points="73.5,62.5 148.5,105.8 73.5,149.1 ">
                                                        </polygon>
                                                        <circle class="circle" fill="none" stroke-width="7"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-miterlimit="10" cx="106.8" cy="106.8" r="103.3">
                                                        </circle>
                                                    </svg>
                                                    <span class="w-trailor text-uppercase"> Watch Local Trailer </span>
                                                </a>
                                            @elseif($item->series->youtube)
                                                <a data-fslightbox="html5-video" href="{{ $item->series->youtube }}"
                                                    class="video-open playbtn text-decoration-none" tabindex="0">
                                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                        width="80px" height="80px" viewBox="0 0 213.7 213.7"
                                                        enable-background="new 0 0 213.7 213.7" xml:space="preserve">
                                                        <polygon class="triangle" fill="none" stroke-width="7"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-miterlimit="10"
                                                            points="73.5,62.5 148.5,105.8 73.5,149.1 ">
                                                        </polygon>
                                                        <circle class="circle" fill="none" stroke-width="7"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-miterlimit="10" cx="106.8" cy="106.8"
                                                            r="103.3">
                                                        </circle>
                                                    </svg>
                                                    <span class="w-trailor text-uppercase"> Watch YouTube Trailer </span>
                                                </a>
                                            @endif
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-banner-button-next">
                    <i class="iconly-Arrow-Right-2 icli arrow-icon"></i>
                </div>
                <div class="swiper-banner-button-prev">
                    <i class="iconly-Arrow-Left-2 icli arrow-icon"></i>
                </div>
            </div>
        </div>
    </section>
    @if (session('subscriptionError'))
        <script>
            // Use SweetAlert to display the error
            Swal.fire({
                icon: 'error',
                title: 'Subscription Error',
                text: '{{ session('subscriptionError') }}',
            });
        </script>
    @endif
    <section class="recommended-block section-top-spacing">
        <div class="container-fluid">
            <div class="overflow-hidden">
                @foreach ($categories as $category)
                    <div class="d-flex align-items-center justify-content-between px-3 my-4">
                        <h5 class="main-title text-capitalize mb-0">{{ $category['category_name'] }} Movies</h5>
                        <a href="{{ url('movies/category/' . $category['id']) }}"
                            class="text-primary iq-view-all text-decoration-none">View
                            All</a>
                    </div>
                    <div class="card-style-slider">
                        <div class="position-relative swiper swiper-card" data-slide="6" data-laptop="5" data-tab="2"
                            data-mobile="2" data-mobile-sm="2" data-autoplay="false" data-loop="true"
                            data-navigation="true" data-pagination="true">
                            <ul class="p-0 swiper-wrapper m-0  list-inline">
                                @foreach ($moviesWithCategory->where('category_id', $category->id) as $movie)
                                    <li class="swiper-slide">
                                        <div class="iq-card card-hover">
                                            <div class="block-images position-relative w-100">
                                                <div class="img-box w-100">
                                                    <a href="{{ url('movie/' . $movie['id']) }}"
                                                        class="position-absolute top-0 bottom-0 start-0 end-0"></a>
                                                    <img src="{{ asset('storage/' . $movie['thumbnail']) }}"
                                                        alt="movie-card"
                                                        class="img-fluid object-cover w-100 d-block border-0">
                                                </div>
                                                <div class="card-description with-transition">
                                                    <div class="cart-content">
                                                        <div class="content-left">
                                                            <h5 class="iq-title text-capitalize">
                                                                <a
                                                                    href="{{ url('movie/' . $movie['id']) }}">{{ $movie['name'] }}</a>
                                                            </h5>
                                                            <div class="movie-time d-flex align-items-center my-2">
                                                                <span
                                                                    class="movie-time-text font-normal">{{ $movie['duration'] }}</span>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                <div class="block-social-info align-items-center">
                                                    <ul class="p-0 m-0 d-flex gap-2 music-play-lists">
                                                        <li
                                                            class="share position-relative d-flex align-items-center text-center mb-0">
                                                            <span class="w-100 h-100 d-inline-block bg-transparent">
                                                                <i class="fas fa-share-alt"></i>
                                                            </span>
                                                            <div class="share-wrapper">
                                                                <div class="share-boxs d-inline-block">
                                                                    <svg width="15" height="40"
                                                                        class="share-shape" viewBox="0 0 15 40"
                                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                                            d="M14.8842 40C6.82983 37.2868 1 29.3582 1 20C1 10.6418 6.82983 2.71323 14.8842 0H0V40H14.8842Z"
                                                                            fill="#191919"></path>
                                                                    </svg>
                                                                    <div class=" overflow-hidden">
                                                                        <a href="" target="_blank">
                                                                            <i class="fab fa-facebook-f"></i>
                                                                        </a>
                                                                        <a href="" target="_blank">
                                                                            <i class="fab fa-twitter"></i>
                                                                        </a>
                                                                        <a href="#">
                                                                            <i class="fas fa-link"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>

                                                    </ul>
                                                    <div class="iq-button">
                                                        <a href="{{ url('movie/' . $movie['id']) }}"
                                                            class="btn text-uppercase position-relative rounded-circle">
                                                            <i class="fa-solid fa-play ms-0"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </li>
                                @endforeach
                            </ul>
                            <div class="swiper-button swiper-button-next"></div>
                            <div class="swiper-button swiper-button-prev"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

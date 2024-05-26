@extends('layouts.front.layout')
@section('content')
    <!-- Banner Start -->
    <div class="iq-main-slider site-video">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pt-0">
                        @if ($movie->videoVersions->count() > 0)
                            @php
                                $videoVersion = $movie->videoVersions->first();
                            @endphp

                            @if ($videoVersion->local)
                                <video id="my-video" poster="{{ asset('storage/' . $movie['poster']) }}"
                                    class="video-js vjs-big-play-centered w-100" controls preload="auto"
                                    data-setup='{ "techOrder": ["html5"], "sources": [{ "type": "video/mp4", "src": "https://Dreamzz.s3.us-east-005.backblazeb2.com/{{ $videoVersion->local}}" }] }'
                                    style="object-fit: cover;">
                                    <source src="https://Dreamzz.s3.us-east-005.backblazeb2.com/{{ $videoVersion->local}}" type="video/mp4" />
                                    {{-- <source src="MY_VIDEO.webm" type="video/webm" /> --}}
                                </video>
                            @elseif ($videoVersion->youtube)
                                <div class="youtube-container">
                                    <iframe width="100%" height="315"
                                        src="https://www.youtube.com/embed/{{ $videoVersion->youtube }}" frameborder="0"
                                        allowfullscreen></iframe>
                                </div>
                            @endif
                        @else
                            <h4 class="trending-text fw-bold texture-text text-uppercase my-0 fadeInLeft animated"
                                style="text-align: center; padding-top: 50px; padding-bottom: 50px; ">No video available for
                                this movie.</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner End -->

    <div class="details-part">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Movie Description Start-->
                    <div class="trending-info mt-4 pt-0 pb-4">
                        <div class="row">
                            <div class="col-md-9 col-12 mb-auto">
                                <div class="d-block d-lg-flex align-items-center">
                                    <h2 class="trending-text fw-bold texture-text text-uppercase my-0 fadeInLeft animated d-inline-block"
                                        data-animation-in="fadeInLeft" data-delay-in="0.6"
                                        style="opacity: 1; animation-delay: 0.6s">
                                        {{ $movie['name'] }}
                                    </h2>
                                </div>
                                <ul class="p-0 mt-2 list-inline d-flex flex-wrap movie-tag">
                                    <li class="trending-list"><a class="text-primary"
                                            href="./view-all-movie.html">{{ $movie->category->category_name }}</a></li>
                                </ul>
                                <div class="d-flex flex-wrap align-items-center text-white text-detail flex-wrap mb-4">

                                    <span class="ms-3 font-Weight-500 genres-info" style="padding-right: 15px">Duration:
                                        {{ $movie['duration'] }}</span>

                                    @php
                                        $formattedDate = Carbon\Carbon::createFromFormat('Y-m-d', $movie['release_date'])->format('d M Y');
                                    @endphp
                                    <span class="trending-year trending-year-list font-Weight-500 genres-info">
                                        Release Date: {{ $formattedDate }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-4 flex-wrap mb-4">
                                    <ul class="list-inline p-0 share-icons music-play-lists mb-n2 mx-n2">
                                        <li class="share">
                                            <span><i class="fa-solid fa-share-nodes"></i></span>
                                            <div class="share-box">
                                                <svg width="15" height="40" viewBox="0 0 15 40" class="share-shape"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M14.8842 40C6.82983 37.2868 1 29.3582 1 20C1 10.6418 6.82983 2.71323 14.8842 0H0V40H14.8842Z"
                                                        fill="#191919"></path>
                                                </svg>
                                                <div class="d-flex align-items-center">
                                                    <a href="#" class="share-ico"><i
                                                            class="fa-brands fa-facebook-f"></i></a>
                                                    <a href="#" class="share-ico"><i
                                                            class="fa-brands fa-twitter"></i></a>
                                                    <a href="#" class="share-ico"><i class="fa-solid fa-link"></i></a>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <form id="watchlist_form" action="{{url('/add-to-watchlist')}}" method="POST">@csrf
                                                <input type="hidden" name="movie_id" value="{{$movie['id']}}">
                                                <button id="watchlist_submit" type="submit" style="border-radius:40px;"><span><i class="fa-solid fa-plus"></i></span></button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="trailor-video col-md-3 col-12 mt-lg-0 mt-4 mb-md-0 mb-1 text-lg-right">
                                <a data-fslightbox="html5-video" href="{{ $backblazeNativeUrl }}"
                                    class="video-open playbtn block-images position-relative playbtn_thumbnail">
                                    <img src="{{ asset('storage/' . $movie['poster']) }}"
                                        class="attachment-medium-large size-medium-large wp-post-image" alt=""
                                        loading="lazy" />
                                    <span class="content btn btn-transparant iq-button">
                                        <i class="fa fa-play me-2 text-white"></i>
                                        <span>Trailer Link</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Movie Description End --> <!-- Movie Source Start -->
                    <div class="content-details trending-info">
                        <ul class="iq-custom-tab tab-bg-gredient-center d-flex nav nav-pills align-items-center text-center mb-5 justify-content-center list-inline"
                            role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" data-bs-toggle="pill" href="#description-01" role="tab"
                                    aria-selected="true">
                                    Description
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="description-01" class="tab-pane animated fadeInUp active show" role="tabpanel">
                                <div class="description-content">
                                    <p>
                                        {{ $movie['description'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Movie Source End -->
                </div>
            </div>
        </div>
    </div>

    <div class="cast-tabs">
        <div class="container-fluid">
            <div class="content-details trending-info g-border iq-rtl-direction">
                <ul class="iq-custom-tab tab-bg-fill d-flex nav nav-pills mb-5 " role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active show" data-bs-toggle="pill" href="#cast-1" role="tab"
                            aria-selected="true">Cast & Crew</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="cast-1" class="tab-pane animated fadeInUp active show" role="tabpanel">
                        <div class="position-relative swiper swiper-card" data-slide="5" data-laptop="5" data-tab="3"
                            data-mobile="2" data-mobile-sm="1" data-autoplay="false" data-loop="false"
                            data-navigation="true" data-pagination="true">
                            <ul class="list-inline swiper-wrapper">
                                @foreach ($movie->castAndCrew as $person)
                                    <li class="swiper-slide">
                                        <div class="cast-images m-0 row align-items-center position-relative">
                                            <div class="col-4 img-box p-0">
                                                <img src="{{ asset('assets/user/images/genre/p.jpg') }}"
                                                    class="img-fluid" alt="image" loading="lazy">
                                            </div>
                                            <div class="col-8 block-description">
                                                <h6 class="iq-title">
                                                    <a tabindex="0">{{ $person->name }}</a>
                                                </h6>
                                                <div class="video-time d-flex align-items-center my-2">
                                                    <small class="text-white">As {{ $person->role }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="related-movie-block">
        <div class="container-fluid">
            <div class="overflow-hidden">
                <div class="d-flex align-items-center justify-content-between px-3 pt-2 my-4">
                    <h5 class="main-title text-capitalize mb-0">Related Movies</h5>
                </div>
                <div class="card-style-slider">
                    <div class="position-relative swiper swiper-card" data-slide="5" data-laptop="5" data-tab="2"
                        data-mobile="2" data-mobile-sm="2" data-autoplay="false" data-loop="true"
                        data-navigation="true" data-pagination="true">
                        <ul class="p-0 swiper-wrapper m-0  list-inline">
                            @foreach ($relatedMovies as $item)
                            <li class="swiper-slide">
                                <div class="iq-card card-hover">
                                    <div class="block-images position-relative w-100">
                                        <div class="img-box w-100">
                                            <a href="{{url('movie/'.$item['id'])}}"
                                                class="position-absolute top-0 bottom-0 start-0 end-0"></a>
                                            <img src="{{ asset('storage/' . $item['thumbnail']) }}"
                                                alt="movie-card" class="img-fluid object-cover w-100 d-block border-0">
                                        </div>
                                        <div class="card-description with-transition">
                                            <div class="cart-content">
                                                <div class="content-left">
                                                    <h5 class="iq-title text-capitalize">
                                                        <a href="{{url('movie/'.$item['id'])}}">{{$item['name']}}</a>
                                                    </h5>
                                                    <div class="movie-time d-flex align-items-center my-2">
                                                        <span class="movie-time-text font-normal">{{$item['duration']}}</span>
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
                                                            <svg width="15" height="40" class="share-shape"
                                                                viewBox="0 0 15 40" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
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
                                                <a href="{{url('movie/'.$item['id'])}}"
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
            </div>
        </div>
    </section>

@endsection

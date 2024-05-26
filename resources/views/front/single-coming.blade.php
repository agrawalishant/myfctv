@extends('layouts.front.layout')
@section('content')
    <!-- Banner Start -->
    <div class="iq-main-slider site-video">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pt-0">
                        

                            @if ($coming->local)
                                <video id="my-video" poster="{{ asset('storage/' . $coming['poster']) }}"
                                    class="video-js vjs-big-play-centered w-100" controls preload="auto"
                                    data-setup='{ "techOrder": ["html5"], "sources": [{ "type": "video/mp4", "src": "/storage/{{ $coming->local }}" }] }'
                                    style="object-fit: cover;">
                                    <source
                                        src="/storage/{{ $coming->local }}"
                                        type="video/mp4" />
                                    {{-- <source src="MY_VIDEO.webm" type="video/webm" /> --}}
                                </video>
                            @elseif ($coming->youtube)
                                <div class="youtube-container">
                                    <iframe width="100%" height="315"
                                        src="https://www.youtube.com/embed/{{ $coming->youtube }}" frameborder="0"
                                        allowfullscreen></iframe>
                                </div>
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
                                        {{ $coming['name'] }}
                                    </h2>
                                </div>
                                
                                <div class="d-flex flex-wrap align-items-center text-white text-detail flex-wrap mb-4">

                                    <span class="ms-3 font-Weight-500 genres-info" style="padding-right: 15px">Duration:
                                        {{ $coming['duration'] }}</span>

                                    
                                    <span class="trending-year trending-year-list font-Weight-500 genres-info">
                                        Release Date: {{ \Carbon\Carbon::parse($coming->publish_year)->format('d M, Y') }}

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
                                        
                                    </ul>
                                </div>
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
                                        {{ $coming['description'] }}
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
@endsection

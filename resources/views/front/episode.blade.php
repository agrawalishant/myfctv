@extends('layouts.front.layout')
@section('content')
    <div class="iq-main-slider site-video">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pt-0">
                        @if ($episode->count() > 0)
                            

                            @if ($episode->local)
                                <video id="my-video" poster="{{ asset('storage/' . $episode['poster']) }}"
                                    class="video-js vjs-big-play-centered w-100" controls preload="auto"
                                    data-setup='{ "techOrder": ["html5"], "sources": [{ "type": "video/mp4", "src": "https://streamingyogesh.s3.us-east-005.backblazeb2.com/{{ $episode->local }}" }] }'
                                    style="object-fit: cover;">
                                    <source
                                        src="https://streamingyogesh.s3.us-east-005.backblazeb2.com/{{ $episode->local }}"
                                        type="video/mp4" />
                                    {{-- <source src="MY_VIDEO.webm" type="video/webm" /> --}}
                                </video>
                            @elseif ($episode->youtube)
                                <div class="youtube-container">
                                    <iframe width="100%" height="315"
                                        src="https://www.youtube.com/embed/{{ $episode->youtube }}" frameborder="0"
                                        allowfullscreen></iframe>
                                </div>
                            @endif
                        @else
                            <h4 class="trending-text fw-bold texture-text text-uppercase my-0 fadeInLeft animated"
                                style="text-align: center; padding-top: 50px; padding-bottom: 50px; ">No video available for
                                this episode.</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner End -->

    <div class="details-part">
        <div class="container-fluid">
            <div class="trending-info mt-4 pt-0 pb-4">
                <div class="row">
                    <div class="col-md-9 col-12 mb-auto">
                        <div class="d-md-flex">
                            <h2 class="trending-text fw-bold texture-text text-uppercase mt-0 fadeInLeft animated"
                                data-animation-in="fadeInLeft" data-delay-in="0.6"
                                style="opacity: 1; animation-delay: 0.6s">
                                {{$episode['title']}}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Movie Source Start -->
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
                                {{$episode['description']}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Movie Source End -->
        </div>
    </div>
@endsection

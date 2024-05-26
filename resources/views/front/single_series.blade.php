@extends('layouts.front.layout')
@section('content')
    <!-- Banner Start -->
    <div class="iq-main-slider site-video">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pt-0">
                        @if ($series->local)
                            <video id="my-video" poster="{{ asset('storage/' . $series['poster']) }}"
                                class="video-js vjs-big-play-centered w-100" controls preload="auto"
                                data-setup='{ "techOrder": ["html5"], "sources": [{ "type": "video/mp4", "src": "https://Dreamzz.s3.us-east-005.backblazeb2.com/{{ $series->local }}" }] }'
                                style="object-fit: cover;">
                                <source src="https://Dreamzz.s3.us-east-005.backblazeb2.com/{{ $series->local }}"
                                    type="video/mp4" />
                                {{-- <source src="MY_VIDEO.webm" type="video/webm" /> --}}
                            </video>
                        @elseif ($series->youtube)
                            <div class="youtube-container">
                                <iframe width="100%" height="315"
                                    src="https://www.youtube.com/embed/{{ $series->youtube }}" frameborder="0"
                                    allowfullscreen></iframe>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner End -->

    <section class="section-padding-bottom">
        <div class="tabs">
            <div class="container-fluid">
                <div class="content-details iq-custom-tab-style-two">
                    <ul class="d-flex justify-content-center nav nav-pills tab-header" role="tablist">
                        @foreach ($series->seasons as $key => $season)
                            <li class="nav-item">
                                <a class="nav-link {{ $key === 0 ? 'active' : '' }}" data-bs-toggle="pill"
                                    href="#playlist{{ $season['id'] }}" role="tab"
                                    aria-selected="{{ $key === 0 ? 'true' : 'false' }}">{{ $season['season_name'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content px-0">
                        @foreach ($series->seasons as $key => $season)
                            <div id="playlist{{ $season['id'] }}"
                                class="tab-pane animated fadeInUp {{ $key === 0 ? 'active show' : '' }}" role="tabpanel">
                                <div class="overflow-hidden">
                                    <div class="d-flex align-items-center justify-content-between my-4">
                                        <h5 class="main-title text-capitalize mb-0">Episodes</h5>
                                    </div>
                                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4">
                                        @foreach ($season->episode as $episode)
                                            <div class="col-lg-3 col-sm-12 col-md-6">
                                                <div class="episode-block">
                                                    <div class="block-image position-relative">
                                                        <a href="{{url('/series/episode/'.$episode['id'])}}">
                                                            <img src="{{ asset('storage/' . $episode['poster']) }}"
                                                                class="img-fluid img-zoom" alt="showImg-" loading="lazy">
                                                        </a>
                                                        {{-- <div class="episode-number">S01E01</div> --}}
                                                        <div class="episode-play">
                                                            <a href="{{url('/series/episode/'.$episode['id'])}}" tabindex="0"><i
                                                                    class="fa-solid fa-play"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="epi-desc p-3">
                                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                                            <span
                                                                class="border-gredient-left text-white rel-date">{{ $episode->created_at->format('F j, Y') }}</span>
                                                            <span class="text-primary run-time"> @php
                                                                $durationParts = explode(':', $episode->duration);

                                                                $hours = (int) $durationParts[0];
                                                                $minutes = (int) $durationParts[1];

                                                                $formattedDuration = '';

                                                                if ($hours > 0) {
                                                                    $formattedDuration .= $hours . ' hour' . ($hours > 1 ? 's' : '');
                                                                }

                                                                if ($minutes > 0) {
                                                                    if ($formattedDuration !== '') {
                                                                        $formattedDuration .= ' ';
                                                                    }

                                                                    $formattedDuration .= $minutes . ' minute' . ($minutes > 1 ? 's' : '');
                                                                }

                                                                echo $formattedDuration !== '' ? $formattedDuration : '0 seconds';
                                                            @endphp</span>
                                                        </div>
                                                        <a href="{{url('/series/episode/'.$episode['id'])}}">
                                                            <h5 class="epi-name text-white mb-0"> {{ $episode['title'] }}
                                                            </h5>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                                @foreach ($series->cast as $person)
                                    <li class="swiper-slide">
                                        <div class="cast-images m-0 row align-items-center position-relative">
                                            <div class="col-4 img-box p-0">
                                                <img src="{{ asset('assets/user/images/genre/p.jpg') }}" class="img-fluid"
                                                    alt="image" loading="lazy">
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
@endsection

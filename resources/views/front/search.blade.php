@extends('layouts.front.layout')
@section('content')
    <div class="section-padding">
        <div class="container-fluid">
            <h4 class="mb-0 accordian-title">Search Results for "{{ $query }}"</h4>

            <div class="d-flex align-items-center justify-content-between px-3 pt-2 my-4">
                <h5 class="main-title text-capitalize mb-0">Search Result Of Movies</h5>
            </div>
            <div class="card-style-grid">
                <div class="row row-cols-xl-4 row-cols-md-2 row-cols-1">
                    @foreach ($movies as $item)
                        <div class="col mb-4">
                            <div class="iq-card card-hover">
                                <div class="block-images position-relative w-100">
                                    <div class="img-box w-100">
                                        <a href="{{ url('movie/' . $item['id']) }}"
                                            class="position-absolute top-0 bottom-0 start-0 end-0"></a>
                                        <img src="{{ asset('storage/' . $item['thumbnail']) }}" alt="movie-card"
                                            class="img-fluid object-cover w-100 d-block border-0">
                                    </div>
                                    <div class="card-description with-transition">
                                        <div class="cart-content">
                                            <div class="content-left">
                                                <h5 class="iq-title text-capitalize">
                                                    <a href="{{ url('movie/' . $item['id']) }}">{{ $item['name'] }}</a>
                                                </h5>
                                                <div class="movie-time d-flex align-items-center my-2">
                                                    <span class="movie-time-text font-normal">{{ $item['duration'] }}</span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="block-social-info align-items-center">
                                        <ul class="p-0 m-0 d-flex gap-2 music-play-lists">
                                            <li class="share position-relative d-flex align-items-center text-center mb-0">
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
                                            <a href="{{ url('movie/' . $item['id']) }}"
                                                class="btn text-uppercase position-relative rounded-circle">
                                                <i class="fa-solid fa-play ms-0"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between px-3 pt-2 my-4">
                <h5 class="main-title text-capitalize mb-0">Search Result Of Series</h5>
            </div>
            <div class="card-style-grid">
                <div class="row row-cols-xl-4 row-cols-md-2 row-cols-1">
                    @foreach ($series as $item)
                        <div class="col mb-4">
                            <div class="iq-card card-hover">
                                <div class="block-images position-relative w-100">
                                    <div class="img-box w-100">
                                        <a href="{{ url('series/' . $item['id']) }}"
                                            class="position-absolute top-0 bottom-0 start-0 end-0"></a>
                                        <img src="{{ asset('storage/' . $item['thumbnail']) }}" alt="movie-card"
                                            class="img-fluid object-cover w-100 d-block border-0">
                                    </div>
                                    <div class="card-description with-transition">
                                        <div class="cart-content">
                                            <div class="content-left">
                                                <h5 class="iq-title text-capitalize">
                                                    <a href="{{ url('series/' . $item['id']) }}">{{ $item['title'] }}</a>
                                                </h5>
                                                <div class="movie-time d-flex align-items-center my-2">
                                                    <span
                                                        class="movie-time-text font-normal">{{ $item['duration'] }}</span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="block-social-info align-items-center">
                                        <ul class="p-0 m-0 d-flex gap-2 music-play-lists">
                                            <li class="share position-relative d-flex align-items-center text-center mb-0">
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
                                            <a href="{{ url('series/' . $item['id']) }}"
                                                class="btn text-uppercase position-relative rounded-circle">
                                                <i class="fa-solid fa-play ms-0"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

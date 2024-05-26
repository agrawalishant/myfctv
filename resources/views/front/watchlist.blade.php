@extends('layouts.front.layout')
@section('content')
    <section class="section-padding">
        <div class="container-fluid">
            <div class="row">
                <div class="overflow-hidden">
                    <div class="d-flex align-items-center justify-content-between my-4">
                        <h5 class="main-title text-capitalize mb-0">My Watchlist</h5>
                    </div>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4">
                        @foreach ($watchlist as $item)
                            <div class="col mb-4">
                                <div class="watchlist-warpper card-hover-style-two">
                                    <div class="block-images position-relative w-100">
                                        <div class="img-box">
                                            <a href="watchlist-detail.html"
                                                class="position-absolute top-0 bottom-0 start-0 end-0"></a>
                                            <img src="{{ $item->movie_id ? asset('storage/' . $item->movie->poster) : asset('storage/' . $item->series->poster) }}"
                                                alt="movie-card" class="img-fluid object-cover w-100 d-block border-0">
                                        </div>
                                        <div class="card-description d-flex justify-content-between align-items-center">
                                            <h5 class="text-capitalize fw-500">
                                                <a href="">
                                                    @if ($item->movie_id)
                                                        {{ $item->movie->name }}
                                                    @elseif ($item->series_id)
                                                        {{ $item->series->title }}
                                                    @endif
                                                </a>
                                            </h5>
                                            <form id="watchlist_remove_form" action="{{ url('remove/' . $item->id) }}" method="GET">
                                                @csrf
                                                <button id="watchlist_remove_submit" type="submit" class="btn btn-icon btn-primary"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

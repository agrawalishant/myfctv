@extends('layouts.dashboard.app')
@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div class="container-fluid">
            @if (Session::has('error_message'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="
margin-top: 10px;">
                    {{ Session::get('error_message') }}
                    </button>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="bg-info text-white rounded p-3">
                                            <i class="fa-solid fa-film"></i>
                                        </div>
                                        <div class="text-end">
                                            Total Movies
                                            <h2 class="counter" style="visibility: visible;">{{$totalMovies}}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="bg-warning text-white rounded p-3">
                                            <i class="fa-solid fa-tv"></i>
                                        </div>
                                        <div class="text-end">
                                            Total Series
                                            <h2 class="counter" style="visibility: visible;">{{$totalSeries}}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="bg-success text-white rounded p-3">
                                            <i class="fa-solid fa-photo-film"></i>
                                        </div>
                                        <div class="text-end">
                                            Total Episodes
                                            <h2 class="counter" style="visibility: visible;">{{$totalEpisodes}}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="bg-danger text-white rounded p-3">
                                            <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M11.997 15.1746C7.684 15.1746 4 15.8546 4 18.5746C4 21.2956 7.661 21.9996 11.997 21.9996C16.31 21.9996 19.994 21.3206 19.994 18.5996C19.994 15.8786 16.334 15.1746 11.997 15.1746Z"
                                                    fill="currentColor"></path>
                                                <path opacity="0.4"
                                                    d="M11.9971 12.5838C14.9351 12.5838 17.2891 10.2288 17.2891 7.29176C17.2891 4.35476 14.9351 1.99976 11.9971 1.99976C9.06008 1.99976 6.70508 4.35476 6.70508 7.29176C6.70508 10.2288 9.06008 12.5838 11.9971 12.5838Z"
                                                    fill="currentColor"></path>
                                            </svg>
                                        </div>
                                        <div class="text-end">
                                            Category
                                            <h2 class="counter" style="visibility: visible;">{{$totalCategories}}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center top-rated-slider">
                            <div class="iq-header-title ">
                                <h4 class="card-title">Latest Movie Uploads </h4>
                            </div>
                            <div class="top-swiper-arrow d-flex align-items-center">
                                <div class="swiper-button-prev me-2"></div>
                                <div class="swiper-button-next"></div>
                            </div>
                        </div>
                        <div class="card-body ">
                            <div class="swiper pt-2 pt-md-4 pt-lg-4 overflow-hidden" data-swiper="top-slider">
                                <ul class="swiper-wrapper list-inline p-0 m-0">
                                    @if ($hasMovies)
                                        @foreach ($latestMovies as $item)
                                            <li class="iq-rated-box swiper-slide">
                                                <div class="iq-card mb-0">
                                                    <div class="iq-card-body p-0">
                                                        <div class="iq-thumb">
                                                            <a href="javascript:void(0)">
                                                                <img src="{{ asset('storage/' . $item['poster']) }}"
                                                                    class="img-fluid img-border-radius" alt="topImg-01"
                                                                    style="
                                                                width: 273px;
                                                                height: 140px;
                                                            ">
                                                            </a>
                                                        </div>
                                                        <div class="iq-feature-list mt-3">
                                                            <h6 class="font-weight-600 mb-0"
                                                                style="text-transform: capitalize">{{ $item['name'] }}</h6>
                                                            <p class="mb-0 mt-2">
                                                                {{ Illuminate\Support\Str::limit($item['description'], 50) }}
                                                            </p>
                                                            <div class="d-flex align-items-center my-2 iq-ltr-direction">
                                                                <p class="mb-0 me-2"><span>Uploaded:
                                                                    </span>{{ $item->created_at->format('d M , Y') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @else
                                        <p>No movies available at the moment.</p>
                                    @endif
                                </ul>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

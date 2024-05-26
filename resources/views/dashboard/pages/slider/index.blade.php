@extends('layouts.dashboard.app')
@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div class="row">
            <div class="col-sm-12">
                <div class="card pb-3">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center pb-3">
                        <div class="d-flex align-items-center pt-3">
                            {{-- <div class="form-group">
                            <select type="select" class="form-control select2-basic-multiple" placeholder="No Action">
                                <option>No Action</option>
                                <option>Status</option>
                                <option>Delete</option>
                            </select>
                            <button class="btn btn-primary ">Apply</button>
                        </div> --}}
                        </div>
                        @if ($sliderModule['edit_access'] == 1 || $sliderModule['full_access'] == 1)
                            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#season-offcanvas" aria-controls="season-offcanvas"><i
                                    class="fa-solid fa-plus me-2"></i>Add Slider</button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-view table-responsive pt-3 table-space">
                            <table id="seasonTable" class="data-tables table custom-table movie_table"
                                data-toggle="data-table">
                                <thead>
                                    <tr class="text-uppercase">
                                        <th class="text-center">
                                            Sr.No
                                        </th>
                                        <th>Slider Image</th>
                                        <th>Slider Title</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($slider as $item)
                                        <tr>
                                            <td>
                                                {{ $loop->index + 1 }}
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="image"
                                                        class="rounded-2 avatar img-fluid"
                                                        style="width: 150px;  height: auto;" />
                                                </div>
                                            </td>
                                            <td>
                                                {{ $item['title'] }}
                                            </td>

                                            <td>
                                                {{ $item['status'] == 1 ? 'Active' : 'InActive' }}
                                            </td>


                                            <td>
                                                <div class="d-flex align-items-center list-user-action">
                                                    @if ($sliderModule['edit_access'] == 1 || $sliderModule['full_access'] == 1)
                                                    <button type="button" class="btn btn-sm btn-icon btn-success rounded"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Edit Slider">
                                                        <i class="fa-solid fa-pen" data-bs-toggle="offcanvas"
                                                            data-bs-target="#season-offcanvas-edit{{ $item['id'] }}"
                                                            aria-controls="season-offcanvas-edit"></i>
                                                    </button>
                                                    @endif
                                                    @if ($sliderModule['full_access'] == 1)
                                                    <a class="btn btn-sm btn-icon btn-danger slider-delete-btn rounded"
                                                        data-toggle="tooltip" data-placement="top" title="Delete"
                                                        data-original-title="Delete Slider" data-id="{{ $item->id }}"
                                                        href="#">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
            <div class="offcanvas offcanvas-end offcanvas-width-80" tabindex="-1" id="season-offcanvas"
                aria-labelledby="season-offcanvas-lable">
                <div class="offcanvas-header">
                    <h5 id="offcanvasRightLabel1">Add Slider</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>

                <div class="offcanvas-body">
                    <div class="error-text text-center text-danger mb-4" id="slider_error"></div>
                    <form method="POST" id="slider_form" action="{{ url('dashboard/slider-upload') }}"
                        enctype="multipart/form-data">@csrf
                        <div class="section-form">
                            <fieldset>
                                <legend>Slider Content</legend>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group px-5 ">
                                            <label class="form-label flex-grow-1" for="Slider Title">
                                                <strong>Slider Title</strong> <span class="text-danger">*</span>:
                                            </label>
                                            <input id="Slider Title" type="text" name="title" class="form-control "
                                                placeholder="Enter Slider Title" min="" multiple="" />
                                            <span class="text-danger error-text title_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group px-5 ">
                                            <label class="form-label flex-grow-1" for="Slider Image">
                                                <strong>Slider Image</strong> :
                                            </label>

                                            <!-- textarea input -->
                                            <input class="form-control" type="file" name="image" id="customFile1">
                                            <span class="text-danger error-text image_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group px-5">
                                            <label class="form-label flex-grow-1" for="movie-access"><strong>Post
                                                    Type:</strong></label>
                                            <select id="add-movie-access" name="post_type" type="select"
                                                class="form-control " placeholder="select movie access"
                                                onchange="showAddFields()">
                                                <option selected="" disabled="">Select Type</option>
                                                <option value="movie">Movie</option>
                                                <option value="series">Series</option>
                                            </select>
                                            <span class="text-danger error-text post_type_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 d-none" id="add-category-movie">
                                        <div class="form-group px-5 ">
                                            <label class="form-label"
                                                for="exampleFormControlSelect1"><strong>Movie:</strong></label>
                                            <select class="form-control" name="movie_id" type="select" id="movie">
                                                <option selected="" disabled="">Select Movie</option>
                                                @foreach ($movies as $item)
                                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text movie_id_error"></span>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 d-none" id="add-category-series">
                                        <div class="form-group px-5 ">
                                            <label class="form-label"
                                                for="exampleFormControlSelect1"><strong>Series:</strong></label>
                                            <select class="form-control" name="series_id" type="select" id="series">
                                                <option selected="" disabled="">Select Series</option>
                                                @foreach ($series as $item)
                                                    <option value="{{ $item['id'] }}">{{ $item['title'] }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text series_id_error"></span>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group px-5">
                                            <label class="form-label flex-grow-1"
                                                for="status"><strong>Status:</strong></label>
                                            <select id="status" type="select" name="status" class="form-control"
                                                placeholder="Select Status">
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                            <span class="text-danger error-text status_error"></span>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                </div>
                <div class="offcanvas-footer border-top">
                    <div class="d-grid d-flex gap-3 p-3">
                        <button type="submit" id="slider_submit" class="btn btn-primary d-block">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Save
                        </button>
                        <button type="button" class="btn btn-outline-primary d-block" data-bs-dismiss="offcanvas"
                            aria-label="Close">
                            <i class="fa-solid fa-angles-left me-2"></i>Close
                        </button>
                    </div>
                </div>
                </form>
            </div>
            @foreach ($slider as $item)
                <div class="offcanvas offcanvas-end offcanvas-width-80" tabindex="-1"
                    id="season-offcanvas-edit{{ $item['id'] }}" aria-labelledby="season-offcanvas-edit-lable">
                    <div class="offcanvas-header">
                        <h5 id="offcanvasRightLabel1">Edit Slider</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div class="error-text text-center text-danger mb-4" id="edit_slider_error_{{ $item['id'] }}">
                        </div>
                        <form method="POST" class="edit_slider_form" id="edit_slider_form_{{ $item['id'] }}"
                            action="{{ url('dashboard/slider-update/' . $item['id']) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="section-form">
                                <fieldset>
                                    <legend>Slider Content</legend>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group px-5">
                                                <label class="form-label flex-grow-1" for="Slider Title">
                                                    <strong>Slider Title</strong> <span class="text-danger">*</span>:
                                                </label>
                                                <input id="Slider Title" type="text" name="title"
                                                    class="form-control" placeholder="Enter Slider Title"
                                                    value="{{ $item['title'] }}" />
                                                <span class="text-danger error-text title_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group px-5">
                                                <label class="form-label flex-grow-1" for="Slider Image">
                                                    <strong>Slider Image</strong> :
                                                </label>
                                                <input class="form-control" type="file" name="image"
                                                    id="customFile1">
                                                <span class="text-danger error-text image_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group px-5">
                                                <label class="form-label flex-grow-1"
                                                    for="movie-access-{{ $item['id'] }}"><strong>Post
                                                        Type:</strong></label>
                                                <select id="movie-access-{{ $item['id'] }}" name="post_type"
                                                    type="select" class="form-control" placeholder="select movie access"
                                                    onchange="showFields('{{ $item['id'] }}')">
                                                    <option selected="" disabled="">Select Type</option>
                                                    <option value="movie"
                                                        @if ($item['post_type'] == 'movie') selected @endif>Movie</option>
                                                    <option value="series"
                                                        @if ($item['post_type'] == 'series') selected @endif>Series</option>
                                                </select>
                                                <span class="text-danger error-text post_type_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 @if ($item['post_type'] != 'movie') d-none @endif"
                                            id="category-movie-{{ $item['id'] }}">
                                            <div class="form-group px-5">
                                                <label class="form-label"
                                                    for="exampleFormControlSelect1"><strong>Movie:</strong></label>
                                                <select class="form-control" name="movie_id" type="select"
                                                    id="movie-{{ $item['id'] }}">
                                                    <option selected="" disabled="">Select Movie</option>
                                                    @foreach ($movies as $movie)
                                                        <option value="{{ $movie['id'] }}"
                                                            @if ($item['movie_id'] == $movie['id']) selected @endif>
                                                            {{ $movie['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error-text movie_id_error"></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 @if ($item['post_type'] != 'series') d-none @endif"
                                            id="category-series-{{ $item['id'] }}">
                                            <div class="form-group px-5">
                                                <label class="form-label"
                                                    for="exampleFormControlSelect1"><strong>Series:</strong></label>
                                                <select class="form-control" name="series_id" type="select"
                                                    id="series-{{ $item['id'] }}">
                                                    <option selected="" disabled="">Select Series</option>
                                                    @foreach ($series as $seriesItem)
                                                        <option value="{{ $seriesItem['id'] }}"
                                                            @if ($item['series_id'] == $seriesItem['id']) selected @endif>
                                                            {{ $seriesItem['title'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error-text series_id_error"></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group px-5">
                                                <label class="form-label flex-grow-1"
                                                    for="status"><strong>Status:</strong></label>
                                                <select id="status" type="select" name="status"
                                                    class="form-control" placeholder="select status">
                                                    <option value="1"
                                                        @if ($item['status'] == 1) selected @endif>Active</option>
                                                    <option value="0"
                                                        @if ($item['status'] == 0) selected @endif>Inactive</option>
                                                </select>
                                                <span class="text-danger error-text status_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                    </div>
                    <div class="offcanvas-footer border-top">
                        <div class="d-grid d-flex gap-3 p-3">
                            <button type="submit" id="edit_slider_submit_{{ $item['id'] }}"
                                class="btn btn-primary d-block">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Update
                            </button>
                            <button type="button" class="btn btn-outline-primary d-block" data-bs-dismiss="offcanvas"
                                aria-label="Close">
                                <i class="fa-solid fa-angles-left me-2"></i>Close
                            </button>
                        </div>
                    </div>
                    </form>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        initializeEditForm('{{ $item['id'] }}');
                    });

                    function initializeEditForm(itemId) {
                        // Call showFields function on page load to handle the initial state
                        showFields(itemId);

                        // Trigger showFields when the post_type dropdown value changes
                        var movieAccess = document.getElementById('movie-access-' + itemId);
                        if (movieAccess) {
                            movieAccess.addEventListener('change', function() {
                                showFields(itemId);
                            });
                        } else {
                            console.error('movie-access-' + itemId + ' element not found');
                        }
                    }

                    function showFields(itemId) {
                        var movieAccess = document.getElementById('movie-access-' + itemId);
                        var categoryMovie = document.getElementById('category-movie-' + itemId);
                        var categorySeries = document.getElementById('category-series-' + itemId);

                        if (movieAccess && categoryMovie && categorySeries) {
                            if (movieAccess.value === 'movie') {
                                categoryMovie.classList.remove('d-none');
                                categorySeries.classList.add('d-none');
                            } else if (movieAccess.value === 'series') {
                                categoryMovie.classList.add('d-none');
                                categorySeries.classList.remove('d-none');
                            }
                        } else {
                            console.error('One or more elements not found for item ' + itemId);
                        }
                    }
                </script>
            @endforeach

        </div>
    </div>

    <script>
        function showAddFields() {
            var movieAccess = document.getElementById('add-movie-access');
            var categoryMovie = document.getElementById('add-category-movie');
            var categorySeries = document.getElementById('add-category-series');

            if (movieAccess.value === 'movie') {
                categoryMovie.classList.remove('d-none');
                categorySeries.classList.add('d-none');
            } else if (movieAccess.value === 'series') {
                categoryMovie.classList.add('d-none');
                categorySeries.classList.remove('d-none');
            }
        }
    </script>
@endsection

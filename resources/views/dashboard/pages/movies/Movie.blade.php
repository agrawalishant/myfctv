@extends('layouts.dashboard.app')
@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div class="row">
            <div class="col-sm-12">
                <div class="card pb-3">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center pb-3">
                        <div class="d-flex align-items-center pt-3">

                        </div>
                        @if ($moviesModule['edit_access'] == 1 || $moviesModule['full_access'] == 1)
                            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#season-offcanvas" aria-controls="season-offcanvas"><i
                                    class="fa-solid fa-plus me-2"></i>Add Movie</button>
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
                                        <th>Movie</th>
                                        <th>Category</th>
                                        <th>Release Date</th>
                                        <th>Publish Date</th>
                                        <th>Movie Access</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($movie as $item)
                                        <tr>
                                            <td>
                                                {{ $loop->index + 1 }}
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <img src="{{ asset('storage/' . $item['thumbnail']) }}" alt="image"
                                                        class="rounded-2 avatar avatar-55 img-fluid" />
                                                    <div class="d-flex flex-column ms-3 justify-content-center">
                                                        <h6 class="text-capitalize">{{ $item['name'] }}</h6>
                                                        <small>{{ $item['duration'] }}</small>
                                                        <small class="text-capitalize">{{ $item['language'] }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $item->category->category_name }}
                                            </td>
                                            <td>
                                                <small>{{ date('d M Y', strtotime($item['release_date'])) }}</small>
                                            </td>
                                            <td>
                                                <small>{{ date('d M Y', strtotime($item['created_at'])) }}</small>
                                            </td>
                                            <td>{{ $item['access'] }}</td>

                                            <td>
                                                <div class="d-flex align-items-center list-user-action">
                                                    @if ($moviesModule['edit_access'] == 1 || $moviesModule['full_access'] == 1)
                                                        <a class="btn btn-sm btn-icon btn-success rounded"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit" href="{{url('dashboard/movie/edit/'.$item->id)}}">
                                                            <i class="fa-solid fa-pen"></i>
                                                        </a>
                                                        <a class="btn btn-sm btn-icon btn-warning  rounded"
                                                            title="Add/Edit Cast"
                                                            href="{{ url('dashboard/movies/' . $item->id . '/cast-crew') }}">
                                                            <i class="fa-solid fa-user-astronaut"></i>
                                                        </a>
                                                        <a class="btn btn-sm btn-icon btn-light  rounded"
                                                            title="Upload Movie"
                                                            href="{{ url('dashboard/movies/' . $item->id . '/upload') }}">
                                                            <i class="fa-solid fa-upload"></i>
                                                        </a>
                                                    @endif
                                                    @if ($moviesModule['full_access'] == 1)
                                                        <a class="btn btn-sm btn-icon btn-danger movie-delete-btn rounded"
                                                            data-toggle="tooltip" data-placement="top" title="Delete"
                                                            data-original-title="Delete Movie"
                                                            data-id="{{ $item->id }}" href="#">
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
                    <h5 id="offcanvasRightLabel1">Add New Movie</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>

                <div class="offcanvas-body">
                    <div class="error-text text-center text-danger mb-4" id="movie_error"></div>
                    <form method="POST" id="movie_form" action="{{ url('dashboard/movie/upload') }}"
                        enctype="multipart/form-data">@csrf
                        <div class="section-form">
                            <fieldset>
                                <legend>Movie</legend>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group px-5 ">
                                            <label class="form-label flex-grow-1" for="Movie Name">
                                                <strong>Movie Name</strong> <span class="text-danger">*</span>:
                                            </label>

                                            <!-- textarea input -->
                                            <!-- toggle switch -->
                                            <!-- common inputs -->
                                            <input id="Movie Name" type="text" name="name" class="form-control "
                                                placeholder="Enter Movie Name" min="" multiple="" />
                                            <span class="text-danger error-text name_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group px-5 ">
                                            <label class="form-label flex-grow-1" for="Description">
                                                <strong>Description</strong> :
                                            </label>

                                            <!-- textarea input -->
                                            <textarea id="Description" name="description" class="form-control" placeholder="Description"></textarea>
                                            <span class="text-danger error-text description_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group px-5">
                                            <label class="form-label flex-grow-1" for="movie-access"><strong>Movie
                                                    Access:</strong></label>
                                            <select id="movie-access" name="access" type="select"
                                                class="form-control select2-basic-multiple"
                                                placeholder="select movie access">
                                                <option value="public">Free</option>
                                                <option value="restricted">Premium</option>
                                            </select>
                                            <span class="text-danger error-text access_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group px-5">
                                            <label class="form-label flex-grow-1"
                                                for="language"><strong>Language:</strong></label>
                                            <select id="language" type="select" name="language"
                                                class="form-control select2-basic-multiple" placeholder="select language">
                                                <option value="hindi">Hindi</option>
                                                <option value="english">English</option>
                                                <option value="french">French</option>
                                                <option value="marathi">Marathi</option>
                                                <option value="gujrati">Gujrati</option>
                                            </select>
                                            <span class="text-danger error-text language_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group px-5">
                                            <label class="form-label flex-grow-1"
                                                for="genres"><strong>Category:</strong></label>
                                            <select id="genres" type="select" name="category_id"
                                                class="form-control select2-basic-multiple" placeholder="select genres">
                                                @foreach ($categories as $item)
                                                    <option value="{{ $item['id'] }}">{{ $item['category_name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text category_id_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-lg-4">
                                        <div class="form-group px-5 ">
                                            <label class="form-label flex-grow-1" for="Content Rating">
                                                <strong>Content Rating</strong> :
                                            </label>

                                            <!-- textarea input -->
                                            <!-- toggle switch -->
                                            <!-- common inputs -->
                                            <input id="Content Rating" type="text" name="content_rating"
                                                class="form-control " placeholder="Rating" min=""
                                                multiple="" />
                                            <span class="text-danger error-text content_rating_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group px-5">
                                            <label class="form-label flex-grow-1" for="genres"><strong>Release
                                                    Date:</strong></label>
                                            <input class="form-control flatpickr_humandate" name="release_date"
                                                type="text" placeholder="release date.." data-id="multiple"
                                                readonly="readonly">
                                            <span class="text-danger error-text release_date_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group px-5 ">
                                            <label class="form-label flex-grow-1" for="Duration">
                                                <strong>Duration</strong> :
                                            </label>

                                            <!-- textarea input -->
                                            <!-- toggle switch -->
                                            <!-- common inputs -->
                                            <input id="Duration" type="text" name="duration" class="form-control "
                                                placeholder="Duration in mins" min="" multiple="" />
                                            <span class="text-danger error-text duration_error"></span>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Media</legend>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group px-5 ">
                                            <label class="form-label flex-grow-1" for="Thumbnail">
                                                <strong>Thumbnail</strong> <span class="text-danger">*</span>:
                                            </label>

                                            <!-- textarea input -->
                                            <!-- toggle switch -->
                                            <!-- common inputs -->
                                            <input id="Thumbnail" type="file" class="form-control " placeholder=""
                                                name="thumbnail" min="" multiple="" />
                                            <span class="text-danger error-text thumbnail_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group px-5 ">
                                            <label class="form-label flex-grow-1" for="poster">
                                                <strong>poster</strong> <span class="text-danger">*</span>:
                                            </label>

                                            <!-- textarea input -->
                                            <!-- toggle switch -->
                                            <!-- common inputs -->
                                            <input id="poster" type="file" name="poster" class="form-control "
                                                placeholder="" min="" multiple="" />
                                            <span class="text-danger error-text poster_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group px-5 ">
                                            <label class="form-label flex-grow-1" for="Trailer ">
                                                <strong>Trailer </strong> <span class="text-danger">*</span>:
                                            </label>

                                            <!-- textarea input -->
                                            <!-- toggle switch -->
                                            <!-- common inputs -->
                                            <input id="Trailer " type="file" class="form-control "
                                                placeholder="Trailer Link" name="trailer" min=""
                                                multiple="" />
                                            <span class="text-danger error-text trailer_error"></span>
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                        </div>
                </div>
                <div class="offcanvas-footer border-top">
                    <div class="d-grid d-flex gap-3 p-3">
                        <button type="submit" id="movie_submit" class="btn btn-primary d-block">
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

        </div>
    </div>
@endsection

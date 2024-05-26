@extends('layouts.dashboard.app')
@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Edit Movie</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" class="edit_movie_form" id="edit_movie_form" action="{{ url('dashboard/movie/edit/' . $movie['id']) }}" enctype="multipart/form-data">@csrf
                                <div class="form-group">
                                    <label class="form-label flex-grow-1" for="Movie Name">
                                        <strong>Movie Name</strong> <span class="text-danger">*</span>:
                                    </label>
                                    <input id="Movie Name" type="text" name="name"
                                        class="form-control " placeholder="Enter Movie Name" min=""
                                        value="{{ $movie['name'] }}" multiple="" />
                                    <span class="text-danger error-text name_error"></span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label flex-grow-1" for="Description">
                                        <strong>Description</strong> :
                                    </label>

                                    <!-- textarea input -->
                                    <textarea id="Description" name="description" class="form-control" placeholder="Description">{!! $movie['description'] !!}</textarea>
                                    <span class="text-danger error-text description_error"></span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label flex-grow-1" for="movie-access"><strong>Movie
                                            Access:</strong></label>
                                    <select id="movie-access" name="access" type="select"
                                        class="form-control select2-basic-multiple"
                                        placeholder="select movie access">
                                        <option value="public"
                                            {{ $movie->access === 'public' ? 'selected' : '' }}>Free</option>
                                        <option value="restricted"
                                            {{ $movie->access === 'restricted' ? 'selected' : '' }}>Premium
                                        </option>
                                    </select>
                                    <span class="text-danger error-text access_error"></span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label flex-grow-1"
                                        for="language"><strong>Language:</strong></label>
                                    <select id="language" name="language"
                                        class="form-control select2-basic-multiple"
                                        placeholder="select language">
                                        <option value="hindi"
                                            {{ $movie->language === 'hindi' ? 'selected' : '' }}>Hindi</option>
                                        <option value="english"
                                            {{ $movie->language === 'english' ? 'selected' : '' }}>English
                                        </option>
                                        <option value="french"
                                            {{ $movie->language === 'french' ? 'selected' : '' }}>French
                                        </option>
                                        <option value="marathi"
                                            {{ $movie->language === 'marathi' ? 'selected' : '' }}>Marathi
                                        </option>
                                        <option value="gujarati"
                                            {{ $movie->language === 'gujarati' ? 'selected' : '' }}>Gujarati
                                        </option>
                                    </select>
                                    <span class="text-danger error-text language_error"></span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label flex-grow-1"
                                        for="genres"><strong>Category:</strong></label>
                                    <select id="genres" type="select" name="category_id"
                                        class="form-control select2-basic-multiple"
                                        placeholder="select genres">
                                        @foreach ($categories as $category)
                                            @if ($movie->category_id == $category['id'])
                                                <option value="{{ $category['id'] }}" selected>
                                                    {{ $category['category_name'] }}</option>
                                            @else
                                                <option value="{{ $category['id'] }}">
                                                    {{ $category['category_name'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text category_id_error"></span>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label flex-grow-1" for="Content Rating">
                                                <strong>Content Rating</strong> :
                                            </label>

                                            <!-- textarea input -->
                                            <!-- toggle switch -->
                                            <!-- common inputs -->
                                            <input id="Content Rating" type="text" name="content_rating"
                                                class="form-control " placeholder="Rating" min=""
                                                multiple="" value="{{ $movie['content_rating'] }}" />
                                            <span class="text-danger error-text content_rating_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label flex-grow-1" for="genres"><strong>Release
                                                    Date:</strong></label>
                                            <input class="form-control flatpickr_humandate" name="release_date"
                                                type="text" placeholder="release date.."
                                                value="{{ $movie['release_date'] }}" data-id="multiple"
                                                readonly="readonly">
                                            <span class="text-danger error-text release_date_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label flex-grow-1" for="Duration">
                                                <strong>Duration</strong> :
                                            </label>

                                            <!-- textarea input -->
                                            <!-- toggle switch -->
                                            <!-- common inputs -->
                                            <input id="Duration" type="text" name="duration"
                                                class="form-control " placeholder="Duration in mins" min=""
                                                multiple="" value="{{ $movie['duration'] }}" />
                                            <span class="text-danger error-text duration_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <fieldset>
                                    <legend>Media</legend>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label flex-grow-1" for="Thumbnail">
                                                    <strong>Thumbnail</strong> <span class="text-danger">*</span>:
                                                </label>
                                                <input id="Thumbnail" type="file" class="form-control"
                                                    name="thumbnail" />
                                                @if ($movie->thumbnail)
                                                    <img src="{{ asset('storage/' . $movie->thumbnail) }}" alt="Thumbnail"
                                                        style="max-width: 100px;" />
                                                @endif
                                                <span class="text-danger error-text thumbnail_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label flex-grow-1" for="poster">
                                                    <strong>Poster</strong> <span class="text-danger">*</span>:
                                                </label>
                                                <input id="poster" type="file" name="poster"
                                                    class="form-control" />
                                                @if ($movie->poster)
                                                    <img src="{{ asset('storage/' . $movie->poster) }}" alt="Poster"
                                                        style="max-width: 100px;" />
                                                @endif
                                                <span class="text-danger error-text poster_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="form-label flex-grow-1" for="Trailer">
                                                    <strong>Trailer</strong> <span class="text-danger">*</span>:
                                                </label>
                                                <input id="Trailer" type="file" class="form-control"
                                                    name="trailer" />
                                                @if ($movie->trailer)
                                                    <video width="320" height="240" controls>
                                                        <source src="{{ $backblazeNativeUrl }}"
                                                            type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                @endif
                                                <span class="text-danger error-text trailer_error"></span>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>
                                <button type="submit" id="edit_movie_submit" class="btn btn-primary d-block">
                                    <i class="fa-solid fa-floppy-disk me-2"></i>Save
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

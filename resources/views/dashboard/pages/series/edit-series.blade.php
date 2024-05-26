@extends('layouts.dashboard.app')
@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div>
            <div class="row">
                <div class="col-sm-12 col-lg-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Update Series</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="error-text text-center text-danger mb-4" id="edit_series_error"></div>
                            <form id="edit_series_form" action="{{ url('dashboard/series/edit/' . $series['id']) }}"
                                method="POST" enctype="multipart/form-data">@csrf
                                <div class="form-group">
                                    <label class="form-label" for="series-name">Series Name:</label>
                                    <input type="text" class="form-control" id="series-name" name="title"
                                        value="{{ $series->title }}">
                                    <span class="text-danger error-text title_error"></span>
                                </div>

                                <div class="form-group ">
                                    <label class="form-label flex-grow-1" for="video"><strong>Series
                                            Trailer</strong>:</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="video_source" id="local"
                                            value="local" checked>
                                        <label class="form-check-label" for="local">Local</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="video_source" id="youtube"
                                            value="youtube">
                                        <label class="form-check-label" for="youtube">YouTube</label>
                                    </div>
                                </div>
                                <div id="local-upload" class="col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label flex-grow-1" for="video"><strong>Local
                                                Video</strong>:</label>
                                        <input id="video" name="video" type="file" class="form-control"
                                            placeholder="">
                                        <span class="text-danger error-text video_error"></span>
                                    </div>
                                </div>
                                <div id="youtube-url" class="col-sm-12" style="display: none;">
                                    <div class="form-group">
                                        <label class="form-label flex-grow-1" for="youtube_url"><strong>YouTube
                                                URL</strong>:</label>
                                        <input id="youtube_url" name="youtube_url" type="text" class="form-control"
                                            placeholder="Enter YouTube URL">
                                        <span class="text-danger error-text youtube_url_error"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="thumbnail">Thumbnail:</label>
                                    <input type="file" name="thumbnail" class="form-control" id="thumbnail">
                                    <input type="hidden" name="old_thumbnail" value="{{ $series['thumbnail'] }}">
                                    <span class="text-danger error-text thumbnail_error"></span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="poster">poster:</label>
                                    <input type="file" name="poster" class="form-control" id="poster">
                                    <input type="hidden" name="old_poster" value="{{ $series['poster'] }}">
                                    <span class="text-danger error-text poster_error"></span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="quality"><strong>Video Quality</strong>:</label>
                                    <select id="quality" name="video_quality" class="form-control">
                                        <option value="low" {{ $series->video_quality === 'low' ? 'selected' : '' }}>Low
                                        </option>
                                        <option value="medium" {{ $series->video_quality === 'medium' ? 'selected' : '' }}>
                                            Medium</option>
                                        <option value="high" {{ $series->video_quality === 'high' ? 'selected' : '' }}>
                                            High</option>
                                    </select>
                                    <span class="text-danger error-text video_quality_error"></span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="rating"><strong>Rating</strong>:</label>
                                    <select id="rating" name="rating" class="form-control">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}"
                                                {{ $series->rating == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <span class="text-danger error-text rating_error"></span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label flex-grow-1" for="movie-access"><strong>Content
                                            Type</strong>:</label>
                                    <select id="movie-access" name="content_type"
                                        class="form-control select2-basic-multiple">
                                        <option value="public" {{ $series->content_type === 'public' ? 'selected' : '' }}>
                                            Free</option>
                                        <option value="restricted"
                                            {{ $series->content_type === 'restricted' ? 'selected' : '' }}>Premium</option>
                                    </select>
                                    <span class="text-danger error-text content_type_error"></span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label flex-grow-1" for="publish_year"><strong>Release
                                            Date</strong>:</label>
                                    <input class="form-control flatpickr_humandate" name="publish_year" type="text"
                                        placeholder="Release date.." data-id="multiple" readonly="readonly"
                                        value="{{ $series->publish_year }}">
                                    <span class="text-danger error-text publish_year_error"></span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label flex-grow-1" for="genres"><strong>Genre</strong>:</label>
                                    <select id="genres" name="category_id"
                                        class="form-control select2-basic-multiple">
                                        @foreach ($categories as $category)
                                            @if ($series->category_id == $category['id'])
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

                                <div class="form-group">
                                    <label class="form-label flex-grow-1" for="short_description"><strong>Short
                                            Description</strong>:</label>
                                    <input id="short_description" name="short_description" type="text"
                                        class="form-control" placeholder="Enter short description"
                                        value="{{ $series->short_description }}">
                                    <span class="text-danger error-text short_description_error"></span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label flex-grow-1" for="long_description"><strong>Long
                                            Description</strong>:</label>
                                    <textarea id="long_description" name="long_description" class="form-control" placeholder="Long Description">{{ $series->long_description }}</textarea>
                                    <span class="text-danger error-text long_description_error"></span>
                                </div>


                                <button id="edit_series_submit" type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Preview</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group" style="display: flex; justify-content:center;">
                                <video id="local-preview" width="420" height="240" controls
                                    style="display: none"></video>
                                <div id="youtube-preview" style="display: none"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Seasons & Episodes</h4>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="custom-table-effect table-responsive   py-4">
                                <table class="table mb-0">
                                    <thead>
                                        <tr class="bg-white">
                                            <th scope="col">Season</th>
                                            <th scope="col">Series Name</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($season as $item)
                                            <tr>
                                                <td>{{ $item['season_name'] }}</td>
                                                <td>{{ $item['series_name'] }}</td>
                                                <td>
                                                    <a class="btn btn-sm btn-icon btn-warning  rounded"
                                                        title="Manage Season"
                                                        href="{{ url('dashboard/series/season/manage/' . $item['id']) }}">
                                                        <i class="fa-solid fa-list-check"></i>
                                                    </a>

                                                    <a class="btn btn-primary btn-icon btn-sm rounded-pill ms-2 season-delete-btn"
                                                        role="button" data-toggle="tooltip" data-placement="top"
                                                        title="Delete Season" data-original-title="Delete"
                                                        data-id="{{ $item->id }}" href="#">
                                                        <span class="btn-inner">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal"
                                data-bs-target="#create-season">
                                Create Season
                            </button>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Cast & Crew</h4>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="custom-table-effect table-responsive   py-4">
                                <table class="table mb-0">
                                    <thead>
                                        <tr class="bg-white">
                                            <th scope="col">Cast/Crew</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Role</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cast as $item)
                                            <tr>
                                                <td style="text-transform: capitalize">{{ $item['occupation'] }}</td>
                                                <td>{{ $item['name'] }}</td>
                                                <td>{{ $item['role'] }}</td>
                                                <td>
                                                    <a class="btn btn-primary btn-icon btn-sm rounded-pill ms-2 mem-delete-btn"
                                                        role="button" data-toggle="tooltip" data-placement="top"
                                                        title="Delete" data-original-title="Delete"
                                                        data-id="{{ $item->id }}" href="#">
                                                        <span class="btn-inner">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal"
                                data-bs-target="#create-member">
                                Add Cast Crew
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="create-season" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Create Season</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="error-text text-center text-danger mb-4" id="season_error"></div>
                    <form id="season_form" action="{{ url('dashboard/series/create-season') }}" method="POST"
                        enctype="multipart/form-data">@csrf
                        <input type="hidden" name="series_id" value="{{ $series['id'] }}">
                        <div class="form-group">
                            <label class="form-label" for="season">Season Name:</label>
                            <input type="text" name="season_name" class="form-control" id="season"
                                value="{{ $nextSeasonName }}">
                            <span class="text-danger error-text season_name_error"></span>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Series Name:</label>
                            <input type="text" name="series_name" class="form-control" value="{{ $series->title }}"
                                readonly>
                        </div>

                </div>
                <div class="modal-footer">
                    <button id="season_submit" type="submit" class="btn btn-primary">Create Season</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="create-member" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Add Cast/Crew</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="error-text text-center text-danger mb-4" id="member_error"></div>
                    <form id="member_form" action="{{ url('dashboard/series/season/cast-crew') }}" method="POST"
                        enctype="multipart/form-data">@csrf
                        <input type="hidden" name="series_id" value="{{ $series['id'] }}">
                        <div class="form-group">
                            <label class="form-label" for="season">Name:</label>
                            <input type="text" name="name" class="form-control" id="season">
                            <span class="text-danger error-text name_error"></span>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Occupation:</label>
                            <select class="form-select form-select mb-3 shadow-none" name="occupation">
                                <option selected="" disabled>Open this select menu</option>
                                <option value="cast">Cast</option>
                                <option value="crew">Crew</option>
                                <option value="production">Production</option>
                                <option value="director">Director</option>
                                <option value="actor">Actor</option>
                            </select>
                            <span class="text-danger error-text occupation_error"></span>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="role">As:</label>
                            <input type="text" class="form-control" name="role" id="role">
                            <span class="text-danger error-text role_error"></span>
                        </div>

                </div>
                <div class="modal-footer">
                    <button id="member_submit" type="submit" class="btn btn-primary">Create Cast/Crew</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var localUpload = document.getElementById('local-upload');
            var youtubeUrl = document.getElementById('youtube-url');
            var localPreview = document.getElementById('local-preview');
            var youtubePreview = document.getElementById('youtube-preview');
            var videoInput = document.getElementById('video');
            var youtubeUrlInput = document.getElementById('youtube_url');

            // Check if there is a previously uploaded video or YouTube URL
            @if (!empty($series['local']))
                // Display local video preview if available
                localPreview.src = 'https://streamingyogesh.s3.us-east-005.backblazeb2.com/{{ $series->local }}';
                localPreview.style.display = 'block';
            @elseif (!empty($series['youtube']))
                // Display YouTube video preview if available
                var youtubeId = '{{ $series->youtube }}';
                if (youtubeId) {
                    var embedCode = '<iframe width="420" height="240" src="https://www.youtube.com/embed/' +
                        youtubeId +
                        '" frameborder="0" allowfullscreen></iframe>';
                    youtubePreview.innerHTML = embedCode;
                    youtubePreview.style.display = 'block';
                }
            @endif

            document.querySelectorAll('input[name="video_source"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    // Reset local video preview and input
                    localPreview.src = '';
                    localPreview.style.display = 'none';
                    videoInput.value = '';

                    // Reset YouTube video preview and input
                    youtubePreview.innerHTML = '';
                    youtubePreview.style.display = 'none';
                    youtubeUrlInput.value = '';

                    if (this.value === 'local') {
                        localUpload.style.display = 'block';
                        youtubeUrl.style.display = 'none';
                    } else if (this.value === 'youtube') {
                        localUpload.style.display = 'none';
                        youtubeUrl.style.display = 'block';
                        updateYoutubePreview(); // Update YouTube preview
                    }
                });
            });

            document.getElementById('video').addEventListener('change', function() {
                if (videoInput.files && videoInput.files[0]) {
                    localPreview.src = URL.createObjectURL(videoInput.files[0]);
                    localPreview.style.display = 'block';
                    localPreview.play(); // Start playing the video
                }
                // Remove the old YouTube preview
                youtubePreview.innerHTML = '';
                youtubePreview.style.display = 'none';
            });

            document.getElementById('youtube_url').addEventListener('input', function() {
                updateYoutubePreview(); // Update YouTube preview
            });

            function updateYoutubePreview() {
                var youtubeId = extractYouTubeId(youtubeUrlInput.value);

                // Reset local preview
                localPreview.src = '';
                localPreview.style.display = 'none';

                if (youtubeId) {
                    var embedCode = '<iframe width="420" height="240" src="https://www.youtube.com/embed/' +
                        youtubeId +
                        '" frameborder="0" allowfullscreen></iframe>';
                    youtubePreview.innerHTML = embedCode;
                    youtubePreview.style.display = 'block';
                } else {
                    youtubePreview.style.display = 'none';
                }
            }

            function extractYouTubeId(url) {
                var match = url.match(
                    /(?:youtu\.be\/|youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|.*[?&]video_ids=)([^"&?\/\s]{11})/
                );
                return match && match[1];
            }
        });
    </script>
@endsection

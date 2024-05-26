@extends('layouts.dashboard.app')
@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div class="row">
            <div class="col-sm-12">
                <div class="card pb-3">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center pb-3">
                        <div class="d-flex align-items-center pt-3">

                        </div>
                        @if ($comingModule['edit_access'] == 1 || $comingModule['full_access'] == 1)
                            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#season-offcanvas" aria-controls="season-offcanvas"><i
                                    class="fa-solid fa-plus me-2"></i>Upload</button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-view table-responsive pt-3 table-space">
                            <table id="seasonTable" class="data-tables table custom-table movie_table"
                                data-toggle="data-table">
                                <thead>
                                    <tr class="text-uppercase">
                                        <th class="text-center">Sr.No</th>
                                        <th>Thumbnail</th>
                                        <th>Title</th>
                                        <th>Realease Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($coming as $item)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <img src="{{ asset('storage/' . $item['thumbnail']) }}" alt="image"
                                                        class="rounded-2 avatar img-fluid" style="width: 150px;" />
                                                </div>
                                            </td>
                                            <td>{{ $item['title'] }}</td>
                                            <td>{{ date('d M Y', strtotime($item['publish_year'])) }}</td>
                                            <td>
                                                <div class="d-flex align-items-center list-user-action">
                                                    @if ($comingModule['edit_access'] == 1 || $comingModule['full_access'] == 1)
                                                        <a class="btn btn-sm btn-icon btn-warning  rounded" title="Edit"
                                                            href="{{ url('dashboard/coming-soon/edit/' . $item['id']) }}">
                                                            <i class="fa-solid fa-pen"></i>
                                                        </a>
                                                    @endif
                                                    @if ($comingModule['full_access'] == 1)
                                                        <a class="btn btn-primary btn-icon btn-sm rounded-pill ms-2 coming-delete-btn"
                                                            role="button" data-toggle="tooltip" data-placement="top"
                                                            title="Delete" data-original-title="Delete"
                                                            data-id="{{ $item->id }}" href="#">
                                                            <span class="btn-inner">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </span>
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
                    <h5 id="offcanvasRightLabel1">Add New</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div class="error-text text-center text-danger mb-4" id="coming_error"></div>
                    <form id="coming_form" method="POST" action="{{ url('dashboard/coming-soon/upload') }}"
                        enctype="multipart/form-data">@csrf
                        <div class="section-form">
                            <fieldset>
                                <legend>Coming Soon</legend>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group px-5 ">
                                            <label class="form-label flex-grow-1" for="Name">
                                                <strong>Name</strong> <span class="text-danger">*</span>:
                                            </label>
                                            <input id="Name" name="title" type="text" name="title"
                                                class="form-control " placeholder="Enter Name" />
                                            <span class="text-danger error-text title_error"></span>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group px-5">
                                                    <label class="form-label flex-grow-1" for="video"><strong>coming
                                                            Trailer</strong>:</label>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="video_source"
                                                            id="local" value="local" checked>
                                                        <label class="form-check-label" for="local">Local</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="video_source"
                                                            id="youtube" value="youtube">
                                                        <label class="form-check-label" for="youtube">YouTube</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Local Video Upload -->
                                            <div id="local-upload" class="col-sm-12">
                                                <div class="form-group px-5">
                                                    <label class="form-label flex-grow-1" for="video"><strong>Local
                                                            Video</strong>:</label>
                                                    <input id="video" name="video" type="file"
                                                        class="form-control" placeholder="" multiple="">
                                                    <span class="text-danger error-text video_error"></span>
                                                </div>
                                            </div>

                                            <!-- YouTube Video URL -->
                                            <div id="youtube-url" class="col-sm-12" style="display: none;">
                                                <div class="form-group px-5">
                                                    <label class="form-label flex-grow-1"
                                                        for="youtube_url"><strong>YouTube
                                                            URL</strong>:</label>
                                                    <input id="youtube_url" name="youtube_url" type="text"
                                                        class="form-control" placeholder="Enter YouTube URL">
                                                    <span class="text-danger error-text youtube_url_error"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group px-5 ">
                                            <label class="form-label flex-grow-1" for="Video Preview">
                                                <strong>Video Preview</strong> <span class="text-danger">*</span>:
                                            </label>
                                            <video id="local-preview" width="320" height="240" controls
                                                style="display: none"></video>
                                            <div id="youtube-preview" style="display: none"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-5">
                                    <div class="col-lg-6">
                                        <div class="form-group px-5 ">
                                            <label class="form-label flex-grow-1" for="Thumbnail">
                                                <strong>Thumbnail</strong> :
                                            </label>
                                            <input id="Thumbnail" name="thumbnail" type="file"
                                                class="form-control " />
                                            <span class="text-danger error-text thumbnail_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group px-5 ">
                                            <label class="form-label flex-grow-1" for="Poster">
                                                <strong>Poster</strong> :
                                            </label>
                                            <input id="Poster" name="poster" type="file" class="form-control " />
                                            <span class="text-danger error-text poster_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-lg-6">
                                        <div class="form-group px-5">
                                            <label class="form-label flex-grow-1"><strong>Release
                                                    Date:</strong></label>
                                            <input class="form-control flatpickr_humandate" name="publish_year"
                                                type="text" placeholder="release date.." data-id="multiple"
                                                readonly="readonly">
                                            <span class="text-danger error-text publish_year_error"></span>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Description</legend>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group px-5 ">
                                            <label class="form-label flex-grow-1" for="Description">
                                                <strong>Description</strong> :
                                            </label>
                                            <input id="Description" name="description" type="text"
                                                class="form-control " placeholder="Enter description" />
                                            <span class="text-danger error-text description_error"></span>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                </div>
                <div class="offcanvas-footer border-top">
                    <div class="d-grid d-flex gap-3 p-3">
                        <button id="coming_submit" type="submit" class="btn btn-primary d-block">
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

    <script>
        document.querySelectorAll('input[name="video_source"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                var localUpload = document.getElementById('local-upload');
                var youtubeUrl = document.getElementById('youtube-url');
                var localPreview = document.getElementById('local-preview');
                var youtubePreview = document.getElementById('youtube-preview');
                var videoInput = document.getElementById('video');
                var youtubeUrlInput = document.getElementById('youtube_url');

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
                }
            });
        });

        document.getElementById('video').addEventListener('change', function() {
            var videoInput = this;
            var localPreview = document.getElementById('local-preview');

            if (videoInput.files && videoInput.files[0]) {
                localPreview.src = URL.createObjectURL(videoInput.files[0]);
                localPreview.style.display = 'block';
                localPreview.play(); // Start playing the video
            }
        });

        document.getElementById('youtube_url').addEventListener('input', function() {
            var youtubeUrlInput = this;
            var youtubePreview = document.getElementById('youtube-preview');

            var youtubeId = extractYouTubeId(youtubeUrlInput.value);

            if (youtubeId) {
                var embedCode = '<iframe width="320" height="240" src="https://www.youtube.com/embed/' + youtubeId +
                    '" frameborder="0" allowfullscreen></iframe>';
                youtubePreview.innerHTML = embedCode;
                youtubePreview.style.display = 'block';
            } else {
                youtubePreview.style.display = 'none';
            }
        });

        function extractYouTubeId(url) {
            var match = url.match(
                /(?:youtu\.be\/|youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|.*[?&]video_ids=)([^"&?\/\s]{11})/
            );
            return match && match[1];
        }
    </script>
@endsection

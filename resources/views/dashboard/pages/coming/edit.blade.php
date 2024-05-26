@extends('layouts.dashboard.app')
@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div>
            <div class="row">
                <div class="col-sm-12 col-lg-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Update</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="error-text text-center text-danger mb-4" id="edit_coming_error"></div>
                            <form id="edit_coming_form" action="{{ url('dashboard/coming-soon/edit/' . $coming['id']) }}"
                                method="POST" enctype="multipart/form-data">@csrf
                                <div class="form-group">
                                    <label class="form-label" for="series-name">Series Name:</label>
                                    <input type="text" class="form-control" id="series-name" name="title"
                                        value="{{ $coming->title }}">
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
                                    <input type="hidden" name="old_thumbnail" value="{{ $coming['thumbnail'] }}">
                                    <span class="text-danger error-text thumbnail_error"></span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="poster">poster:</label>
                                    <input type="file" name="poster" class="form-control" id="poster">
                                    <input type="hidden" name="old_poster" value="{{ $coming['poster'] }}">
                                    <span class="text-danger error-text poster_error"></span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label flex-grow-1" for="publish_year"><strong>Release
                                            Date</strong>:</label>
                                    <input class="form-control flatpickr_humandate" name="publish_year" type="text"
                                        placeholder="Release date.." data-id="multiple" readonly="readonly"
                                        value="{{ $coming->publish_year }}">
                                    <span class="text-danger error-text publish_year_error"></span>
                                </div>


                                <div class="form-group">
                                    <label class="form-label flex-grow-1" for="description"><strong>Description</strong>:</label>
                                    <input id="description" name="description" type="text"
                                        class="form-control" placeholder="Enter short description"
                                        value="{{ $coming->description }}">
                                    <span class="text-danger error-text description_error"></span>
                                </div>

                                <button id="edit_coming_submit" type="submit" class="btn btn-primary">Update</button>
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
                </div>
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
            @if (!empty($coming['local']))
                // Display local video preview if available
                localPreview.src = '{{ asset('storage/' . $coming->local) }}';
                localPreview.style.display = 'block';
            @elseif (!empty($coming['youtube']))
                // Display YouTube video preview if available
                var youtubeId = '{{ $coming->youtube }}';
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

@extends('layouts.dashboard.app')
@section('content')
    <style>
        .table-responsive {
            overflow-x: auto;
        }

        #basic-table {
            width: 100%;
            table-layout: fixed;
        }

        #basic-table th,
        #basic-table td {
            white-space: normal;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
            /* Adjust the max-width as needed */
        }

        #local-upload,
        #youtube-url {
            margin-top: 20px;
            /* Add spacing between local and YouTube sections */
        }

        #local-preview,
        #youtube-preview {
            width: 380px;
            /* Adjust the width as needed */
            height: 240px;
            /* Adjust the height as needed */
            margin: 10px;
            /* Add spacing around each video preview */
            display: inline-block;
            /* Align videos side by side */
        }

        #youtube-preview iframe {

            width: 100%;
            /* Make the embedded YouTube video responsive */
            height: 100%;
            /* Make the embedded YouTube video responsive */
        }

        .progress-container {
            display: flex;
            align-items: center;
            width: 100%;
            align-items: center;
            margin: 10px auto;
            /* Adjust as needed */
        }

        #uploadProgressBar {
            width: 100%;
            height: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
            flex-grow: 1;
            margin-right: 10px;
        }

        #uploadProgressBar::-webkit-progress-bar {
            background-color: #f1f1f1;
            border-radius: 5px;
        }

        #uploadProgressBar::-webkit-progress-value {
            background: linear-gradient(to left, #8B0000, #A52A2A);
            border-radius: 5px;
        }

        #progressPercentage {
            display: block;
            text-align: center;
            font-weight: bold;
            color: #555;
        }
    </style>


    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div>
            <div class="row">
                <div class="col-sm-12 col-lg-7">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Episode List</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive mt-4 border rounded">
                                <table id="basic-table" class="table table-striped mb-0" role="grid">
                                    <colgroup>
                                        <col style="width: 12%;">
                                        <col style="width: 20%;">
                                        <col style="width: 30%;">
                                        <col style="width: 20%;">
                                        <col style="width: 20%;">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Poster</th>
                                            <th>Title</th>
                                            <th>Video Quality</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($episodes as $item)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}
                                                </td>
                                                <td><img src="{{ asset('storage/' . $item['poster']) }}" alt="image"
                                                        class="rounded-2 avatar img-fluid" /></td>
                                                <td>{{ $item['title'] }}</td>
                                                <td>{{ $item['video_quality'] }}</td>
                                                <td>
                                                    <a class="btn btn-sm btn-icon btn-warning  rounded" title="Edit Episode"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#exampleModalScrollable{{ $item['id'] }}"
                                                        href="#">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </a>

                                                    <a class="btn btn-primary btn-icon btn-sm rounded-pill ms-2 epi-delete-btn"
                                                        role="button" data-toggle="tooltip" data-placement="top"
                                                        title="Delete Episode" data-original-title="Delete"
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
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-5">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Create Episode</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="episode_form" action="{{ url('dashboard/series/season/create-episode') }}"
                                method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>@csrf

                                <input type="hidden" name="series_seasons_id" value="{{ $season['id'] }}">

                                <div class="form-group">
                                    <label class="form-label">Title:</label>
                                    <input type="text" name="title" placeholder="Episode Name" class="form-control"
                                        required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label flex-grow-1" for="video"><strong>Episode
                                            Video</strong>:</label>
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

                                <!-- Local Video Upload -->
                                <div id="local-upload" class="col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label flex-grow-1" for="video"><strong>Local
                                                Video</strong>:</label>
                                        <input id="video" name="video" type="file" class="form-control"
                                            placeholder="" multiple="" required>
                                        <span class="text-danger error-text video_error"></span>
                                    </div>
                                </div>

                                <!-- YouTube Video URL -->
                                <div id="youtube-url" class="col-sm-12" style="display: none;">
                                    <div class="form-group">
                                        <label class="form-label flex-grow-1" for="youtube_url"><strong>YouTube
                                                URL</strong>:</label>
                                        <input id="youtube_url" name="youtube_url" type="text" class="form-control"
                                            placeholder="Enter YouTube URL">
                                        <span class="text-danger error-text youtube_url_error"></span>
                                    </div>
                                </div>

                                <!-- Preview -->
                                <div class="form-group">

                                    <video id="local-preview" width="380" height="240" controls
                                        style="display: none"></video>
                                    <div id="youtube-preview" style="display: none"></div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Poster:</label>
                                    <input type="file" name="poster" class="form-control" required>
                                    <span class="text-danger error-text poster_error"></span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Duration:</label>
                                    <input type="time" name="duration" class="form-control" required>
                                    <span class="text-danger error-text duration_error"></span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label"><strong>Video
                                            Quality</strong>:</label>
                                    <select id="quality" name="video_quality" class="form-control" required>
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                    </select>
                                    <span class="text-danger error-text video_quality_error"></span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Description:</label>
                                    <textarea class="form-control" name="description" id="" rows="5" required></textarea>
                                    <span class="text-danger error-text description_error"></span>
                                </div>
                                <div class="progress-container">
                                    <progress id="uploadProgressBar" max="100" value="0"
                                        style="display: none;"></progress>
                                    <span id="progressPercentage"></span>
                                </div>
                                <button id="episode_submit" type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @foreach ($episodes as $item)
        <div class="modal fade" id="exampleModalScrollable{{ $item['id'] }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div class="header-title">
                                    <h4 class="card-title">Create Episode</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="edit_episode_form{{ $item['id'] }}"
                                    action="{{ url('dashboard/series/season/edit-episode/' . $item['id']) }}"
                                    method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                                    @csrf
                                    <input type="hidden" name="series_seasons_id"
                                        value="{{ $item['series_seasons_id'] }}">

                                    <div class="form-group">
                                        <label class="form-label">Title:</label>
                                        <input type="text" name="title" class="form-control"
                                            value="{{ old('title', $item['title']) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label flex-grow-1" for="video"><strong>Episode
                                                Video</strong>:</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="edit_video_source"
                                                id="edit_local{{ $item['id'] }}" value="edit_local" checked>
                                            <label class="form-check-label"
                                                for="edit_local{{ $item['id'] }}">Local</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="edit_video_source"
                                                id="edit_youtube{{ $item['id'] }}" value="edit_youtube">
                                            <label class="form-check-label"
                                                for="edit_youtube{{ $item['id'] }}">YouTube</label>
                                        </div>
                                    </div>

                                    <!-- Local Video Upload -->
                                    <div id="edit_local-upload{{ $item['id'] }}" class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label flex-grow-1" for="edit_video"><strong>Local
                                                    Video</strong>:</label>
                                            <input id="edit_video{{ $item['id'] }}" name="edit_video" type="file"
                                                class="form-control" placeholder="" multiple="">
                                            <span class="text-danger error-text video_error"></span>
                                        </div>
                                    </div>

                                    <!-- YouTube Video URL -->
                                    <div id="edit_youtube-url{{ $item['id'] }}" class="col-sm-12"
                                        style="display: none;">
                                        <div class="form-group">
                                            <label class="form-label flex-grow-1" for="youtube_url"><strong>YouTube
                                                    URL</strong>:</label>
                                            <input id="edit_youtube_url{{ $item['id'] }}" name="edit_youtube_url"
                                                type="text" class="form-control" placeholder="Enter YouTube URL">
                                            <span class="text-danger error-text youtube_url_error"></span>
                                        </div>
                                    </div>

                                    <!-- Preview -->
                                    <div class="form-group">

                                        <video id="edit_local-preview{{ $item['id'] }}" width="380" height="240"
                                            controls style="display: none"></video>
                                        <div id="edit_youtube-preview{{ $item['id'] }}" style="display: none"></div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Poster:</label>
                                        <input type="file" name="poster" class="form-control">
                                        <span class="text-danger error-text poster_error"></span>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Duration:</label>
                                        <input type="time" name="duration" class="form-control"
                                            value="{{ old('duration', $item['duration']) }}" required>
                                        <span class="text-danger error-text duration_error"></span>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label"><strong>Video Quality</strong>:</label>
                                        <select id="quality" name="video_quality" class="form-control" required>
                                            <option value="low"
                                                {{ old('video_quality', $item['video_quality']) == 'low' ? 'selected' : '' }}>
                                                Low
                                            </option>
                                            <option value="medium"
                                                {{ old('video_quality', $item['video_quality']) == 'medium' ? 'selected' : '' }}>
                                                Medium</option>
                                            <option value="high"
                                                {{ old('video_quality', $item['video_quality']) == 'high' ? 'selected' : '' }}>
                                                High
                                            </option>
                                        </select>
                                        <span class="text-danger error-text video_quality_error"></span>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Description:</label>
                                        <textarea class="form-control" name="description" id="" rows="5" required>{{ old('description', $item['description']) }}</textarea>
                                        <span class="text-danger error-text description_error"></span>
                                    </div>
                                    <div class="progress-container">
                                        <progress id="uploadProgressBar" max="100" value="0"
                                            style="display: none;"></progress>
                                        <span id="progressPercentage"></span>
                                    </div>


                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="episode_update" type="submit" class="btn btn-primary">Save</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('edit_episode_form{{ $item['id'] }}');
                const progressBar = document.getElementById('uploadProgressBar');
                const progressPercentage = document.getElementById('progressPercentage');
                const submitButton = document.getElementById('episode_update');
                const videoInput = document.getElementById('edit_video{{ $item['id'] }}');
                const youtubeUrlInput = document.getElementById('edit_youtube_url{{ $item['id'] }}');
                const localUpload = document.getElementById('edit_local-upload{{ $item['id'] }}');
                const youtubeUrl = document.getElementById('edit_youtube-url{{ $item['id'] }}');
                const localPreview = document.getElementById('edit_local-preview{{ $item['id'] }}');
                const youtubePreview = document.getElementById('edit_youtube-preview{{ $item['id'] }}');
                const radioLocal = document.getElementById('edit_local{{ $item['id'] }}');
                const radioYoutube = document.getElementById('edit_youtube{{ $item['id'] }}');

                let uploading = false;

                // Display old preview if available
                @if (!empty($item['local']))
                    localPreview.src = 'https://streamingyogesh.s3.us-east-005.backblazeb2.com/{{ $item->local }}';
                    localPreview.style.display = 'block';
                @elseif (!empty($item['youtube']))
                    var youtubeId = '{{ $item->youtube }}';
                    if (youtubeId) {
                        var embedCode = '<iframe width="420" height="240" src="https://www.youtube.com/embed/' +
                            youtubeId +
                            '" frameborder="0" allowfullscreen></iframe>';
                        youtubePreview.innerHTML = embedCode;
                        youtubePreview.style.display = 'block';
                    }
                @endif

                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    if (!validateForm()) {
                        return;
                    }

                    submitButton.disabled = true;

                    const formData = new FormData(form);

                    progressBar.style.display = 'block';
                    uploading = true;

                    const xhr = new XMLHttpRequest();

                    xhr.upload.addEventListener('progress', function(event) {
                        if (event.lengthComputable) {
                            const percentCompleted = (event.loaded / event.total) * 100;
                            progressBar.value = percentCompleted;
                            progressPercentage.innerText = percentCompleted.toFixed(0) + '%';
                        }
                    });

                    xhr.addEventListener('load', function() {
                        progressBar.style.display = 'none';
                        progressPercentage.innerText = '';

                        submitButton.disabled = false;

                        Swal.fire({
                            icon: 'success',
                            title: 'Episode Uploaded Successfully',
                            showConfirmButton: false,
                            timer: 1500,
                            onClose: function() {
                                window.location.reload();
                            }
                        });
                    });

                    xhr.addEventListener('error', function() {
                        progressBar.style.display = 'none';
                        progressPercentage.innerText = '';

                        submitButton.disabled = false;

                        console.error('Upload failed.');
                    });

                    xhr.open('POST', form.action, true);
                    xhr.send(formData);
                });

                document.addEventListener('change', function(event) {
                    const target = event.target;

                    if (target && target.id === 'edit_video_source{{ $item['id'] }}') {
                        if (target.value === 'edit_local') {
                            videoInput.setAttribute('required', 'required');
                            youtubeUrlInput.removeAttribute('required');
                        } else if (target.value === 'edit_youtube') {
                            youtubeUrlInput.setAttribute('required', 'required');
                            videoInput.removeAttribute('required');
                        }
                    }
                });

                radioLocal.addEventListener('change', function() {
                    localPreview.src = '';
                    localPreview.style.display = 'none';
                    videoInput.value = '';

                    youtubePreview.innerHTML = '';
                    youtubePreview.style.display = 'none';
                    youtubeUrlInput.value = '';

                    if (this.checked) {
                        localUpload.style.display = 'block';
                        youtubeUrl.style.display = 'none';
                        // Remove the "required" attribute if the user doesn't want to update the video
                        videoInput.removeAttribute('required');
                    }
                });

                radioYoutube.addEventListener('change', function() {
                    localPreview.src = '';
                    localPreview.style.display = 'none';
                    videoInput.value = '';

                    youtubePreview.innerHTML = '';
                    youtubePreview.style.display = 'none';
                    youtubeUrlInput.value = '';

                    if (this.checked) {
                        localUpload.style.display = 'none';
                        youtubeUrl.style.display = 'block';
                        // Remove the "required" attribute if the user doesn't want to update the video
                        videoInput.removeAttribute('required');
                    }
                });

                videoInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        localPreview.src = URL.createObjectURL(this.files[0]);
                        localPreview.style.display = 'block';
                        localPreview.play();
                    }
                });

                youtubeUrlInput.addEventListener('input', function() {
                    const youtubeId = extractYouTubeId(this.value);

                    if (youtubeId) {
                        const embedCode =
                            `<iframe width="380" height="240" src="https://www.youtube.com/embed/${youtubeId}" frameborder="0" allowfullscreen></iframe>`;
                        youtubePreview.innerHTML = embedCode;
                        youtubePreview.style.display = 'block';
                    } else {
                        youtubePreview.style.display = 'none';
                    }
                });

                function validateForm() {
                    const requiredInputs = form.querySelectorAll('[required]');
                    for (const input of requiredInputs) {
                        if (!input.value.trim()) {
                            return false;
                        }
                    }
                    return true;
                }

                function extractYouTubeId(url) {
                    const match = url.match(
                        /(?:youtu\.be\/|youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|.*[?&]video_ids=)([^"&?\/\s]{11})/
                    );
                    return match && match[1];
                }
            });
        </script>
    @endforeach


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('episode_form');
            const progressBar = document.getElementById('uploadProgressBar');
            const progressPercentage = document.getElementById('progressPercentage');
            const submitButton = document.getElementById('episode_submit');
            const videoInput = document.getElementById('video');
            const youtubeUrlInput = document.getElementById('youtube_url');

            let uploading = false;

            form.addEventListener('submit', function(event) {
                // Prevent the default form submission
                event.preventDefault();

                // Implement your form validation logic here
                if (!validateForm()) {
                    // Validation failed, don't submit the form
                    return;
                }

                // Disable the submit button
                submitButton.disabled = true;

                const formData = new FormData(form);

                // Show the progress bar
                progressBar.style.display = 'block';
                uploading = true;

                const xhr = new XMLHttpRequest();

                // Update progress bar on upload progress
                xhr.upload.addEventListener('progress', function(event) {
                    if (event.lengthComputable) {
                        const percentCompleted = (event.loaded / event.total) * 100;
                        progressBar.value = percentCompleted;
                        progressPercentage.innerText = percentCompleted.toFixed(0) + '%';
                    }
                });

                // Handle successful upload
                xhr.addEventListener('load', function() {
                    // Hide the progress bar when the upload is complete
                    progressBar.style.display = 'none';
                    progressPercentage.innerText = '';

                    // Enable the submit button after upload is complete
                    submitButton.disabled = false;

                    // Handle the response (e.g., redirect or display a success message)
                    Swal.fire({
                        icon: 'success',
                        title: 'Episode Uploaded Successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function() {
                            // Reset the form
                            window.location.reload();
                        }
                    });
                });

                // Handle errors
                xhr.addEventListener('error', function() {
                    // Hide the progress bar on error
                    progressBar.style.display = 'none';
                    progressPercentage.innerText = '';

                    // Enable the submit button after an error
                    submitButton.disabled = false;

                    // Handle the error (e.g., display an error message)
                    console.error('Upload failed.');
                });

                // Open and send the request
                xhr.open('POST', form.action, true);
                xhr.send(formData);
            });


            // Listen for changes in the video source radio buttons
            document.querySelectorAll('input[name="video_source"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    if (this.value === 'local') {
                        // Set the "required" attribute for the local video input
                        videoInput.setAttribute('required', 'required');
                        youtubeUrlInput.removeAttribute('required');
                    } else if (this.value === 'youtube') {
                        // Set the "required" attribute for the YouTube URL input
                        youtubeUrlInput.setAttribute('required', 'required');
                        videoInput.removeAttribute('required');
                    }
                });
            });


            function validateForm() {
                // Implement your form validation logic here
                // Return true if the form is valid, otherwise return false
                // For example, check if required fields are filled, etc.

                // Replace this with your actual validation logic
                const requiredInputs = form.querySelectorAll('[required]');
                for (const input of requiredInputs) {
                    if (!input.value.trim()) {
                        // If any required field is empty, return false
                        return false;
                    }
                }

                return true;
            }
        });
    </script>
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
                var embedCode = '<iframe width="380" height="240" src="https://www.youtube.com/embed/' +
                    youtubeId +
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

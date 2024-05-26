@extends('layouts.dashboard.app')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>

    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="d-flex justify-content-between align-items-center my-5 px-5">
                        <h5>
                            <strong>Upload Video</strong>
                        </h5>
                        <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal"
                            data-bs-target="#video-modal">
                            <i class="fa-solid fa-square-plus me-2"></i>Add Video
                        </button>

                        <div class="modal fade" id="video-modal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="video-modal-label">Add</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="uploadForm"
                                            action="{{ url('dashboard/movies/' . $movie->id . '/upload') }}" method="POST"
                                            enctype="multipart/form-data" class="needs-validation" novalidate>@csrf
                                            <div class="row">
                                                <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                                                <div class="form-group">
                                                    <label class="form-label flex-grow-1" for="video"><strong>Episode
                                                            Video</strong>:</label>
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

                                                <!-- Local Video Upload -->
                                                <div id="local-upload" class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="form-label flex-grow-1" for="video"><strong>Local
                                                                Video</strong>:</label>
                                                        <input id="video" name="video" type="file"
                                                            class="form-control" placeholder="" multiple="" required>
                                                        <span class="text-danger error-text video_error"></span>
                                                    </div>
                                                </div>

                                                <!-- YouTube Video URL -->
                                                <div id="youtube-url" class="col-sm-12" style="display: none;">
                                                    <div class="form-group">
                                                        <label class="form-label flex-grow-1"
                                                            for="youtube_url"><strong>YouTube
                                                                URL</strong>:</label>
                                                        <input id="youtube_url" name="youtube_url" type="text"
                                                            class="form-control" placeholder="Enter YouTube URL">
                                                        <span class="text-danger error-text youtube_url_error"></span>
                                                    </div>
                                                </div>

                                                <!-- Preview -->
                                                <div class="form-group">

                                                    <video id="local-preview" width="450" height="240" controls
                                                        style="display: none"></video>
                                                    <div id="youtube-preview" style="display: none"></div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="quality"><strong>Video
                                                                Quality</strong>:</label>
                                                        <select id="quality" name="quality" class="form-control">
                                                            <option value="low">Low</option>
                                                            <option value="medium">Medium</option>
                                                            <option value="high">High</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div id="progressBarContainer" style="display: none;" data-total="100"
                                            data-current="0"></div>
                                        <button type="submit" id="submitButton" class="btn btn-primary">
                                            Save changes
                                        </button>
                                    </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="custom-table-effect table-responsive  border rounded py-4">
                        <table class="table mb-0" id="datatable" data-toggle="data-table">
                            <thead>
                                <tr class="bg-white">
                                    <th scope="col">Sr.No</th>
                                    <th scope="col">Quality</th>
                                    <th scope="col">Video URL</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($video as $vid)
                                    <tr>
                                        <td>
                                            {{ $loop->index + 1 }}
                                        </td>
                                        <td class="" style="text-transform: capitalize">
                                            {{ $vid->quality }}
                                        </td>

                                        <td class="">
                                            @if ($vid->local)
                                                {{-- Display local video --}}
                                                <video width="450" height="240" controls>
                                                    <source
                                                        src="{{ url('https://f005.backblazeb2.com/file/streamingyogesh/' . $vid->local) }}"
                                                        type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @elseif ($vid->youtube)
                                                {{-- Display YouTube video --}}
                                                <iframe width="450" height="240"
                                                    src="https://www.youtube.com/embed/{{ $vid->youtube }}"
                                                    frameborder="0" allowfullscreen></iframe>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-evenly">
                                                <a class="btn btn-primary btn-icon btn-sm rounded-pill ms-2 vid-delete-btn"
                                                    role="button" data-toggle="tooltip" data-placement="top"
                                                    title="Delete" data-original-title="Delete"
                                                    data-id="{{ $vid->id }}" href="#">
                                                    <span class="btn-inner">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </span>
                                                </a>
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
    </div>
    <style>
        #local-upload,
        #youtube-url {
            margin-top: 20px;
            /* Add spacing between local and YouTube sections */
        }

        #local-preview,
        #youtube-preview {
            width: 450px;
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

        /* Additional styles if needed */
        #progressBarContainer {
            position: relative;
            width: 100%;
            margin-top: 20px;
            /* Adjust this value as needed to align with your button */
            height: 20px !important;
            /* Set a fixed height for the progress bar container */
        }

        .progressbar-text {
            color: rgb(153, 153, 153);
            position: absolute;
            left: 0;
            /* Change from 'right' to 'left' */
            top: 0;
            padding: 0;
            margin: 0;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('uploadForm');
            const submitButton = document.getElementById('submitButton');
            const videoInput = document.getElementById('video');
            const youtubeUrlInput = document.getElementById('youtube_url');
            const progressBarContainer = document.getElementById('progressBarContainer');

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                if (!validateForm()) {
                    return;
                }

                // Disable the submit button
                submitButton.disabled = true;

                // Display a loading spinner
                const spinner = document.createElement('i');
                spinner.className = 'fas fa-spinner fa-spin';
                submitButton.innerHTML = 'Uploading... ';
                submitButton.appendChild(spinner);

                // Initialize and configure the progress bar
                const progressBar = new ProgressBar.Line(progressBarContainer, {
                    strokeWidth: 2,
                    easing: 'easeInOut',
                    duration: 1000,
                    color: '#3498db',
                    trailColor: '#f0f0f0',
                    trailWidth: 2,
                    svgStyle: {
                        width: '100%',
                        height: '10px'
                    },
                    text: {
                        style: {
                            color: '#999',
                            position: 'absolute',
                            right: '0',
                            top: '30px',
                            padding: 0,
                            margin: 0,
                            transform: null
                        },
                        autoStyleContainer: false
                    },
                    from: {
                        color: '#3498db'
                    },
                    to: {
                        color: '#3498db'
                    },
                    step: (state, bar) => {
                        bar.setText(Math.round(bar.value() * 100) + ' %');
                    }
                });

                // Show the progress bar
                progressBarContainer.style.display = 'block';

                // Set the progress bar height to the modal height
                progressBarContainer.style.height = document.querySelector('.modal-dialog').offsetHeight +
                    'px';

                const formData = new FormData(form);

                const xhr = new XMLHttpRequest();

                xhr.upload.addEventListener('progress', function(event) {
                    if (event.lengthComputable) {
                        const percentCompleted = event.loaded / event.total;
                        progressBar.animate(percentCompleted);
                    }
                });

                xhr.addEventListener('load', function() {
                    // Enable the submit button and hide the spinner
                    submitButton.disabled = false;
                    submitButton.innerHTML = 'Save changes';

                    // Hide the progress bar
                    progressBarContainer.style.display = 'none';

                    Swal.fire({
                        icon: 'success',
                        title: 'Movie Uploaded Successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function() {
                            window.location.reload();
                        }
                    });
                });

                xhr.addEventListener('error', function() {
                    // Enable the submit button and hide the spinner
                    submitButton.disabled = false;
                    submitButton.innerHTML = 'Save changes';

                    // Hide the progress bar
                    progressBarContainer.style.display = 'none';

                    console.error('Upload failed.');
                });

                xhr.open('POST', form.action, true);
                xhr.send(formData);
            });

            document.querySelectorAll('input[name="video_source"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    if (this.value === 'local') {
                        videoInput.setAttribute('required', 'required');
                        youtubeUrlInput.removeAttribute('required');
                    } else if (this.value === 'youtube') {
                        youtubeUrlInput.setAttribute('required', 'required');
                        videoInput.removeAttribute('required');
                    }
                });
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
                var embedCode = '<iframe width="450" height="240" src="https://www.youtube.com/embed/' +
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

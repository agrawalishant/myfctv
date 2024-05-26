<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Validator;
use App\Models\AdminRole;
use App\Models\MovieCategory;
use App\Models\Movie;
use App\Models\VideoVersion;
use App\Models\CastCrew;
use Session;
use Illuminate\Validation\ValidationException;
use File;
use DB;
use Auth;
use BackblazeB2\Client as BackblazeClient;
use BackblazeB2\Bucket;

class MovieController extends Controller
{
    public function movieList()
    {
        Session::put('page', 'uploadMovie');
        if (Auth::guard('admin')->user()->type == "super-admin") {
            $categories = MovieCategory::all();
            $movie = Movie::all();
            $moviesModule['view_access'] = 1;
            $moviesModule['edit_access'] = 1;
            $moviesModule['full_access'] = 1;
            return view('dashboard.pages.movies.Movie', compact('categories', 'movie', 'moviesModule'));
        } else {
            $categories = MovieCategory::all();
            $movie = Movie::all();
            $moviesModuleCount = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'movies'])->count();
            if ($moviesModuleCount == 0) {
                $message = "This Feature is restricted for you!";
                Session::flash('error_message', $message);
                return redirect('/dashboard');
            } else {
                $moviesModule = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'movies'])->first()->toArray();
            }
            return view('dashboard.pages.movies.Movie', compact('categories', 'movie', 'moviesModule'));
        }
    }

    public function movieUpload(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();

            $validator = Validator::make($data, [
                'category_id' => 'required|exists:movie_categories,id',
                'name' => 'required',
                'description' => 'required',
                'access' => 'required',
                'content_rating' => 'required',
                'release_date' => 'required|date',
                'duration' => 'required',
                'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
                'poster' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
                'trailer' => 'required|mimetypes:video/mp4,video/quicktime|max:102400',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            }

            // Store thumbnail and poster with their original filenames
            $thumbnailPath = $request->file('thumbnail')->storeAs('public/thumbnails', $request->file('thumbnail')->getClientOriginalName());
            $posterPath = $request->file('poster')->storeAs('public/posters', $request->file('poster')->getClientOriginalName());

            // Upload trailer video to Backblaze B2
            $trailerVideo = $request->file('trailer');
            $trailerVideoPath = 'trailers/' . uniqid() . '_' . rawurlencode($trailerVideo->getClientOriginalName());

            // Initialize Backblaze B2 client
            $client = new BackblazeClient(
                env('BACKBLAZEB2_ACCOUNT_ID'),
                env('BACKBLAZEB2_APPLICATION_KEY')
            );

            // Use your existing bucket name
            $existingBucketName = env('BACKBLAZEB2_BUCKET_NAME');
            $buckets = $client->listBuckets();

            // Find the existing bucket by name
            $existingBucket = null;
            foreach ($buckets as $bucket) {
                if ($bucket->getName() === $existingBucketName) {
                    $existingBucket = $bucket;
                    break;
                }
            }

            // If the bucket exists, use it
            if ($existingBucket) {
                // Upload trailer video to Backblaze B2
                $file = $client->upload([
                    'BucketId' => $existingBucket->getId(),
                    'FileName' => $trailerVideoPath,
                    'Body' => fopen($trailerVideo->getRealPath(), 'r'), // Open the file resource for reading
                ]);

                // Check if the upload was successful
                if ($file) {
                    // Create movie entry
                    $movie = new Movie;
                    $movie->category_id = $data['category_id'];
                    $movie->name = $data['name'];
                    $movie->description = $data['description'];
                    $movie->access = $data['access'];
                    $movie->language = $data['language'];
                    $movie->content_rating = $data['content_rating'];
                    $movie->release_date = $data['release_date'];
                    $movie->duration = $data['duration'];
                    $movie->thumbnail = 'thumbnails/' . $request->file('thumbnail')->getClientOriginalName();
                    $movie->poster = 'posters/' . $request->file('poster')->getClientOriginalName();
                    $movie->trailer = $trailerVideoPath;
                    $movie->save();

                    return response()->json(['status' => 1, 'message' => 'Movie information uploaded successfully']);
                } else {
                    return response()->json(['status' => 0, 'message' => 'Failed to upload the trailer to Backblaze B2']);
                }
            } else {
                return response()->json(['status' => 0, 'message' => 'The specified bucket does not exist.']);
            }
        }
    }

    public function movieEdit(Request $request, $id)
    {
        $movie = Movie::find($id);
        $categories = MovieCategory::all();
        // Assuming your Backblaze B2 base URL
        $backblazeBaseUrl = 'https://f005.backblazeb2.com/file/streamingyogesh';

        // Construct the full URL using the base URL and trailer filepath
        $backblazeNativeUrl = $backblazeBaseUrl . '/' . $movie->trailer;

        if ($request->isMethod('post')) {
            $data = $request->all();

            $validator = Validator::make($data, [
                'category_id' => 'required|exists:movie_categories,id',
                'name' => 'required',
                'description' => 'required',
                'access' => 'required',
                'content_rating' => 'required',
                'release_date' => 'required|date',
                'duration' => 'required',
                'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:5048',
                'poster' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:5048',
                'trailer' => 'mimetypes:video/mp4,video/quicktime|max:102400',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            }

            // Update movie information
            $movie->category_id = $data['category_id'];
            $movie->name = $data['name'];
            $movie->description = $data['description'];
            $movie->access = $data['access'];
            $movie->language = $data['language'];
            $movie->content_rating = $data['content_rating'];
            $movie->release_date = $data['release_date'];
            $movie->duration = $data['duration'];

            // Update thumbnail if provided
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->storeAs('public/thumbnails', $request->file('thumbnail')->getClientOriginalName());
                $movie->thumbnail = 'thumbnails/' . $request->file('thumbnail')->getClientOriginalName();
            }

            // Update poster if provided
            if ($request->hasFile('poster')) {
                $posterPath = $request->file('poster')->storeAs('public/posters', $request->file('poster')->getClientOriginalName());
                $movie->poster = 'posters/' . $request->file('poster')->getClientOriginalName();
            }

            // Update trailer if provided
            if ($request->hasFile('trailer')) {
                // Upload trailer video to Backblaze B2
                $trailerVideo = $request->file('trailer');
                $trailerVideoPath = 'trailers/' . uniqid() . '_' . rawurlencode($trailerVideo->getClientOriginalName());

                // Initialize Backblaze B2 client
                $client = new BackblazeClient(
                    env('BACKBLAZEB2_ACCOUNT_ID'),
                    env('BACKBLAZEB2_APPLICATION_KEY')
                );

                // Use your existing bucket name
                $existingBucketName = env('BACKBLAZEB2_BUCKET_NAME');
                $buckets = $client->listBuckets();

                // Find the existing bucket by name
                $existingBucket = null;
                foreach ($buckets as $bucket) {
                    if ($bucket->getName() === $existingBucketName) {
                        $existingBucket = $bucket;
                        break;
                    }
                }

                // If the bucket exists, use it
                if ($existingBucket) {
                    // Upload trailer video to Backblaze B2
                    $file = $client->upload([
                        'BucketId' => $existingBucket->getId(),
                        'FileName' => $trailerVideoPath,
                        'Body' => fopen($trailerVideo->getRealPath(), 'r'), // Open the file resource for reading
                    ]);

                    // Check if the upload was successful
                    if ($file) {
                        // Update the movie with the new trailer path
                        $movie->trailer = $trailerVideoPath;
                    } else {
                        return response()->json(['status' => 0, 'message' => 'Failed to upload the trailer to Backblaze B2']);
                    }
                } else {
                    return response()->json(['status' => 0, 'message' => 'The specified bucket does not exist.']);
                }
            }

            // Save changes
            $movie->save();

            return response()->json(['status' => 1, 'message' => 'Movie information updated successfully']);
        }
        return view('dashboard.pages.movies.edit', compact('movie', 'categories', 'backblazeNativeUrl'));
    }

    public function deleteMovie($id)
    {
        try {
            // Find the movie by ID
            $movie = Movie::findOrFail($id);

            // Check if there are linked video versions
            $linkedVideoVersionsCount = $movie->videoVersions()->count();

            if ($linkedVideoVersionsCount > 0) {
                return response()->json(['success' => false, 'message' => 'Cannot delete movie. There are linked video versions.']);
            }

            // Use a transaction to ensure atomicity
            DB::beginTransaction();

            try {
                // Check if the thumbnail, poster, and trailer exist and delete them
                $this->deleteMovieImages($movie->thumbnail);
                $this->deleteMovieImages($movie->poster);

                // Delete the trailer video
                $this->deleteMovieVideo($movie->trailer);

                // Delete associated cast and crew records
                $movie->castAndCrew()->delete();

                // Finally, delete the movie
                $movie->delete();

                // Commit the transaction
                DB::commit();

                return response()->json(['success' => true, 'message' => 'Movie has been deleted.']);
            } catch (\Exception $e) {
                // Rollback the transaction in case of any error
                DB::rollback();

                return response()->json(['success' => false, 'message' => 'Failed to delete movie.']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to find Movie.']);
        }
    }

    private function deleteMovieImages($imagePath)
    {
        if ($imagePath) {
            $fullImagePath = public_path('storage/' . $imagePath);
            if (file_exists($fullImagePath)) {
                unlink($fullImagePath);
            }
        }
    }

    private function deleteMovieVideo($videoPath)
    {
        if ($videoPath) {
            $fullVideoPath = public_path('storage/' . $videoPath);
            if (file_exists($fullVideoPath)) {
                unlink($fullVideoPath);
            }
        }
    }

    public function showCastCrew($id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            abort(404); // Or handle not found in a different way
        }

        $castCrew = CastCrew::where('movie_id', $movie->id)->paginate(10);
        return view('dashboard.pages.movies.cast-crew', compact('movie', 'castCrew'));
    }

    public function storeCastCrew(Request $request, $id)
    {
        $movie = Movie::find($id);
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required',
            'occupation' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        }

        // Create cast entry
        $cast = new CastCrew;
        $cast->movie_id = $movie->id;
        $cast->name = $data['name'];
        $cast->occupation = $data['occupation'];
        $cast->role = $data['role'];
        $cast->save();

        return response()->json(['status' => 1, 'message' => 'Cast information added successfully']);
    }

    public function deletecmem($id)
    {
        // Logic to delete category by ID
        $castCrew = CastCrew::find($id);
        if (!$castCrew) {
            abort(404); // Or handle not found in a different way
        }
        $castCrew->delete();
        return response()->json(['success' => true]);
    }

    public function upload($id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            abort(404); // Or handle not found in a different way
        }
        $video = VideoVersion::where('movie_id', $movie->id)->get();
        return view('dashboard.pages.movies.upload', compact('movie', 'video'));
    }

    public function uploadVideo(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);
        // dd('Reached uploadVideo method');

        // Handle file upload based on video source
        if ($request->input('video_source') === 'local') {
            return $this->uploadLocalVideo($request, $movie);
        } elseif ($request->input('video_source') === 'youtube') {
            return $this->uploadYouTubeVideo($request, $movie);
        } else {
            throw ValidationException::withMessages(['video_source' => 'Invalid video source.']);
        }
    }

    private function uploadLocalVideo(Request $request, Movie $movie)
    {
        $movieVideo = $request->file('video');

        if ($movieVideo) {
            // Define the output path in the public directory for videos
            $movieVideoPath = 'movies/' . uniqid() . '_' . rawurlencode($movieVideo->getClientOriginalName());

            // Remove the 'public/' prefix from the output path
            $client = new BackblazeClient(
                env('BACKBLAZEB2_ACCOUNT_ID'),
                env('BACKBLAZEB2_APPLICATION_KEY')
            );

            // Use your existing bucket name
            $existingBucketName = env('BACKBLAZEB2_BUCKET_NAME');
            $buckets = $client->listBuckets();

            // Find the existing bucket by name
            $existingBucket = null;
            foreach ($buckets as $bucket) {
                if ($bucket->getName() === $existingBucketName) {
                    $existingBucket = $bucket;
                    break;
                }
            }

            if ($existingBucket) {
                // Upload trailer video to Backblaze B2
                $file = $client->upload([
                    'BucketId' => $existingBucket->getId(),
                    'FileName' => $movieVideoPath,
                    'Body' => fopen($movieVideo->getRealPath(), 'r'), // Open the file resource for reading
                ]);

                if ($file) {
                    // Create a new MovieUpload instance
                    $movieUpload = new VideoVersion();

                    // Set attributes for the MovieUpload instance
                    $movieUpload->movie_id = $movie->id;
                    $movieUpload->quality = $request->input('quality');
                    $movieUpload->local = $movieVideoPath;

                    // Use the save() method to store the video information in the database
                    $movieUpload->save();

                    return redirect()->back()->with('success', 'Local video uploaded successfully!');
                } else {
                    return redirect()->back()->with('error', 'Failed to upload the video to Backblaze B2.');
                }
            } else {
                return redirect()->back()->with('error', 'The specified bucket does not exist.');
            }
        } else {
            return redirect()->back()->with('error', 'No local video uploaded.');
        }
    }


    private function uploadYouTubeVideo(Request $request, Movie $movie)
    {
        $youtubeUrl = $request->input('youtube_url');
        // Extract the YouTube video ID from the URL
        $youtubeId = $this->extractYouTubeId($youtubeUrl);

        if ($youtubeId) {
            // Save the YouTube video ID to the database
            // Create a new VideoVersion instance
            $movieUpload = new VideoVersion();

            // Set attributes for the VideoVersion instance
            $movieUpload->movie_id = $movie->id;
            $movieUpload->quality = $request->input('quality');
            $movieUpload->youtube = $youtubeId; // Store the YouTube video ID

            // Use the save() method to store the video information in the database
            $movieUpload->save();

            return redirect()->back()->with('success', 'YouTube video uploaded successfully!');
        } else {
            return redirect()->back()->with('error', 'Invalid YouTube URL.');
        }
    }
    private function extractYouTubeId($url)
    {
        // Extract YouTube video ID from various URL formats
        $match = preg_match('/(?:youtu\.be\/|youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|.*[?&]video_ids=)([^"&?\/\s]{11})/', $url, $matches);
        return $match ? $matches[1] : null;
    }

    public function deleteVideo($id)
    {
        $video = VideoVersion::findOrFail($id);

        // Check if the video has a local file
        if ($video->local) {
            // Delete the video file from storage
            $this->deleteVideoFile($video->local);
        }

        // Delete the video from the database
        $video->delete();

        return response()->json(['success' => true]);
    }

    private function deleteVideoFile($filename)
    {
        // Assuming your videos are stored in the public/storage/videos directory
        $filePath = public_path('storage/' . $filename);

        // Check if the file exists before attempting to delete it
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
    }
}

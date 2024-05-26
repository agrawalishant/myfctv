<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SeriesSeasonEpisode;
use Illuminate\Http\Request;
use App\Models\Series;
use App\Models\MovieCategory;
use App\Models\SeriesCastCrew;
use App\Models\SeriesSeason;
use App\Models\AdminRole;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Session;
use Validator;
use Illuminate\Validation\Rule;
use File;
use Auth;
use DB;
use BackblazeB2\Client as BackblazeClient;
use BackblazeB2\Bucket;

class SeriesController extends Controller
{
    public function list()
    {
        Session::put('page', 'series');
        if (Auth::guard('admin')->user()->type == "super-admin") {
            $seriesModule['view_access'] = 1;
            $seriesModule['edit_access'] = 1;
            $seriesModule['full_access'] = 1;
            $series = Series::all();
            $categories = MovieCategory::all();
            return view('dashboard.pages.series.list', compact('series', 'categories', 'seriesModule'));
        } else {
            $series = Series::all();
            $categories = MovieCategory::all();
            $seriesModuleCount = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'series'])->count();

            if ($seriesModuleCount == 0) {
                $message = "This Feature is restricted for you!";
                Session::flash('error_message', $message);
                return redirect('/dashboard');
            } else {
                $seriesModule = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'series'])->first()->toArray();
            }
            return view('dashboard.pages.series.list', compact('series', 'categories', 'seriesModule'));
        }
    }

    public function upload(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
            'poster' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
            'video_source' => 'required|in:local,youtube',
            'video' => $data['video_source'] === 'local' ? 'required_if:video_source,local|mimetypes:video/*' : '',
            'youtube_url' => $data['video_source'] === 'youtube' ? 'required_if:video_source,youtube|url' : '',
            'video_quality' => 'required|string',
            'rating' => 'required|integer|between:1,5',
            'content_type' => 'required|in:public,restricted',
            'publish_year' => 'required|date_format:Y-m-d',
            'category_id' => 'required',
            'short_description' => 'required|string|max:255',
            'long_description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $series = new Series();
        $series->title = $data['title'];

        // Store trailer thumbnail and poster with their original filenames
        $trailerThumbnailPath = $request->file('thumbnail')->storeAs('public/thumbnails', $request->file('thumbnail')->getClientOriginalName());
        $series->thumbnail = 'thumbnails/' . $request->file('thumbnail')->getClientOriginalName();

        $trailerPosterPath = $request->file('poster')->storeAs('public/posters', $request->file('poster')->getClientOriginalName());
        $series->poster = 'posters/' . $request->file('poster')->getClientOriginalName();

        $series->video_quality = $data['video_quality'];
        $series->rating = $data['rating'];
        $series->content_type = $data['content_type'];
        $series->publish_year = $data['publish_year'];
        $series->category_id = $data['category_id'];
        $series->short_description = $data['short_description'];
        $series->long_description = $data['long_description'];

        if ($data['video_source'] === 'local') {
            // Upload trailer video to Backblaze B2
            $trailerVideo = $request->file('video');
            $trailerVideoPath = 'series/trailers/' . uniqid() . '_' . rawurlencode($trailerVideo->getClientOriginalName());

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
                    $series->local = $trailerVideoPath;
                    $series->youtube = null; // Clear the YouTube field
                } else {
                    return response()->json(['status' => 0, 'message' => 'Failed to upload the trailer to Backblaze B2']);
                }
            } else {
                return response()->json(['status' => 0, 'message' => 'The specified bucket does not exist.']);
            }
        } elseif ($data['video_source'] === 'youtube') {
            $youtubeId = $this->extractYouTubeId($data['youtube_url']);

            if ($youtubeId) {
                $series->youtube = $youtubeId;
                $series->local = null; // Clear the local video field
            } else {
                return response()->json(['status' => 0, 'errors' => ['youtube_url' => ['Invalid YouTube URL.']]], 422);
            }
        } else {
            return response()->json(['status' => 0, 'errors' => ['video_source' => ['Invalid video source.']]], 422);
        }

        $series->save();

        return response()->json(['status' => 1, 'message' => 'Series created successfully']);
    }


    private function extractYouTubeId($url)
    {
        $match = preg_match('/(?:youtu\.be\/|youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|.*[?&]video_ids=)([^"&?\/\s]{11})/', $url, $matches);
        return $match ? $matches[1] : null;
    }

    public function editSeries(Request $request, $id)
    {
        Session::put('page', 'series');
        $series = Series::find($id);
        $categories = MovieCategory::all();
        if ($request->isMethod('post')) {
            $data = $request->all();

            $validator = Validator::make($data, [
                'title' => 'required|string|max:255',
                'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,webp',
                'poster' => 'image|mimes:jpeg,png,jpg,gif,webp',
                'video_source' => 'in:local,youtube',
                'video' => $data['video_source'] === 'local' ? 'nullable|mimetypes:video/*' : '',
                'youtube_url' => $data['video_source'] === 'youtube' ? 'required_if:video_source,youtube|url' : '',
                'video_quality' => 'required|string',
                'rating' => 'required|integer|between:1,5',
                'content_type' => 'required|in:public,restricted',
                'publish_year' => 'required|date_format:Y-m-d',
                'category_id' => 'required',
                'short_description' => 'required|string|max:255',
                'long_description' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            }

            $series->title = $data['title'];
            $series->video_quality = $data['video_quality'];
            $series->rating = $data['rating'];
            $series->content_type = $data['content_type'];
            $series->publish_year = $data['publish_year'];
            $series->category_id = $data['category_id'];
            $series->short_description = $data['short_description'];
            $series->long_description = $data['long_description'];

            if ($request->hasFile('thumbnail')) {
                // Store the new poster and update the poster field
                $thumbnailPath = $request->file('thumbnail')->storeAs('public/thumbnails', $request->file('thumbnail')->getClientOriginalName());
                $series->thumbnail = 'thumbnails/' . $request->file('thumbnail')->getClientOriginalName();
            }

            if ($request->hasFile('poster')) {
                // Store the new poster and update the poster field
                $posterPath = $request->file('poster')->storeAs('public/posters', $request->file('poster')->getClientOriginalName());
                $series->poster = 'posters/' . $request->file('poster')->getClientOriginalName();
            }

            if ($data['video_source'] === 'local') {
                // Check if the user uploaded a new local video
                if ($request->hasFile('video')) {
                    // Upload trailer video to Backblaze B2
                    $trailerVideo = $request->file('video');
                    $trailerVideoPath = 'series/trailers/' . uniqid() . '_' . rawurlencode($trailerVideo->getClientOriginalName());

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
                            $series->local = $trailerVideoPath;
                            $series->youtube = null; // Clear the YouTube field
                        } else {
                            return response()->json(['status' => 0, 'message' => 'Failed to upload the trailer to Backblaze B2']);
                        }
                    } else {
                        return response()->json(['status' => 0, 'message' => 'The specified bucket does not exist.']);
                    }
                }
            } elseif ($data['video_source'] === 'youtube') {
                // Check if the user provided a new YouTube URL
                if ($request->filled('youtube_url')) {
                    $youtubeId = $this->extractYouTubeId($data['youtube_url']);

                    if ($youtubeId) {
                        $series->youtube = $youtubeId;
                        $series->local = null; // Clear the local video field
                    } else {
                        return response()->json(['status' => 0, 'errors' => ['youtube_url' => ['Invalid YouTube URL.']]], 422);
                    }
                }
            } else {
                return response()->json(['status' => 0, 'errors' => ['video_source' => ['Invalid video source.']]], 422);
            }

            $series->save();

            return response()->json(['status' => 1, 'message' => 'Series updated successfully']);
        }

        $season = SeriesSeason::where('series_id', $series->id)->get();
        $cast = SeriesCastCrew::where('series_id', $series->id)->get();
        // Fetch the latest season for the series
        $latestSeason = SeriesSeason::where('series_id', $series->id)
            ->max('season_name');

        // Extract the season number
        preg_match('/\d+/', $latestSeason, $matches);
        $latestSeasonNumber = count($matches) > 0 ? intval($matches[0]) : 0;
        // Determine the next season number
        $nextSeasonNumber = $latestSeasonNumber + 1;
        // Create the next season name
        $nextSeasonName = "Season " . $nextSeasonNumber;


        return view('dashboard.pages.series.edit-series', compact('series', 'nextSeasonName', 'season', 'cast', 'categories'));
    }

    public function deleteSeries($id)
    {

        // Find the series by ID
        $series = Series::find($id);

        // Check if there are linked season or cast/crew
        $linkedSeasonsCount = $series->seasons()->count();
        $linkedCastCount = $series->cast()->count();

        if ($linkedSeasonsCount > 0 || $linkedCastCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete series. There are linked seasons or cast members.',
            ]);
        }

        // Use a transaction to ensure atomicity
        DB::beginTransaction();

        try {
            // Check if the thumbnail, poster, and trailer exist and delete them
            $this->deleteSeriesImages($series->thumbnail);
            $this->deleteSeriesImages($series->poster);

            // Delete the trailer video
            $this->deleteSeriesVideo($series->trailer);

            // Finally, delete the movie
            $series->delete();

            // Commit the transaction
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Series has been deleted.']);
        } catch (\Exception $e) {
            // Rollback the transaction in case of any error
            DB::rollback();

            return response()->json(['success' => false, 'message' => 'Failed to delete series.']);
        }

    }

    private function deleteSeriesImages($imagePath)
    {
        if ($imagePath) {
            $fullImagePath = public_path('storage/' . $imagePath);
            if (file_exists($fullImagePath)) {
                unlink($fullImagePath);
            }
        }
    }

    private function deleteSeriesVideo($videoPath)
    {
        if ($videoPath) {
            $fullVideoPath = public_path('storage/' . $videoPath);
            if (file_exists($fullVideoPath)) {
                unlink($fullVideoPath);
            }
        }
    }

    public function createSeason(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'season_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $season = new SeriesSeason();
        $season->series_id = $data['series_id'];
        $season->series_name = $data['series_name'];
        $season->season_name = $data['season_name'];
        $season->save();

        return response()->json(['status' => 1, 'message' => 'Sesaon created successfully']);
    }

    public function manageSeason(Request $request, $id)
    {
        Session::put('page', 'series');
        $season = SeriesSeason::find($id);
        $episodes = SeriesSeasonEpisode::where('series_seasons_id', $id)->get();
        return view('dashboard.pages.series.manage-series', compact('season', 'episodes'));
    }

    public function deleteSeason($id)
    {
        try {
            // Logic to delete category by ID
            $season = SeriesSeason::find($id);

            // Check if there are linked movies
            $linkedEpisodesCount = $season->episode()->count();

            if ($linkedEpisodesCount > 0) {
                // Return a response indicating linked movies
                return response()->json([
                    'status' => false,
                    'message' => 'Cannot delete Season. There are linked episodes.',
                ]);
            }

            // Check if the image exists

            // Delete the category from the database
            $season->delete();

            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            \Log::error('Failed to delete season. Error: ' . $e->getMessage());

            return response()->json(['status' => false, 'message' => 'Failed to delete season.']);
        }
    }

    public function createEpisode(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            // Add your validation rules for episode creation
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $episode = new SeriesSeasonEpisode;
        $episode->series_seasons_id = $data['series_seasons_id'];
        $episode->title = $data['title'];
        $posterPath = $request->file('poster')->storeAs('public/posters', $request->file('poster')->getClientOriginalName());
        $episode->poster = 'posters/' . $request->file('poster')->getClientOriginalName();
        $episode->video_quality = $data['video_quality'];
        $episode->duration = $data['duration'];
        $episode->description = $data['description'];

        if ($data['video_source'] === 'local') {
            // Check if the user uploaded a new local video
            if ($request->hasFile('video')) {
                // Upload episode video to Backblaze B2
                $episodeVideo = $request->file('video');
                $episodeVideoPath = 'series/episodes/' . uniqid() . '_' . rawurlencode($episodeVideo->getClientOriginalName());

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
                    // Upload episode video to Backblaze B2
                    $file = $client->upload([
                        'BucketId' => $existingBucket->getId(),
                        'FileName' => $episodeVideoPath,
                        'Body' => fopen($episodeVideo->getRealPath(), 'r'), // Open the file resource for reading
                    ]);

                    // Check if the upload was successful
                    if ($file) {
                        $episode->local = $episodeVideoPath;
                        $episode->youtube = null; // Clear the YouTube field
                    } else {
                        return response()->json(['status' => 0, 'message' => 'Failed to upload the episode video to Backblaze B2']);
                    }
                } else {
                    return response()->json(['status' => 0, 'message' => 'The specified bucket does not exist.']);
                }
            }
        } elseif ($data['video_source'] === 'youtube') {
            // Check if the user provided a new YouTube URL
            if ($request->filled('youtube_url')) {
                $youtubeId = $this->extractYouTubeId($data['youtube_url']);

                if ($youtubeId) {
                    $episode->youtube = $youtubeId;
                    $episode->local = null; // Clear the local video field
                } else {
                    return response()->json(['status' => 0, 'errors' => ['youtube_url' => ['Invalid YouTube URL.']]], 422);
                }
            }
        } else {
            return response()->json(['status' => 0, 'errors' => ['video_source' => ['Invalid video source.']]], 422);
        }

        $episode->save();

        return response()->json(['status' => 1, 'message' => 'Episode created successfully']);
    }


    public function editEpisode(Request $request, $id)
    {
        Session::put('page', 'series');
        $episode = SeriesSeasonEpisode::find($id);

        $data = $request->all();

        $episode->series_seasons_id = $data['series_seasons_id'];
        $episode->title = $data['title'];
        $episode->video_quality = $data['video_quality'];
        $episode->duration = $data['duration'];
        $episode->description = $data['description'];

        // Check if a new poster file is provided
        if ($request->hasFile('poster')) {
            // Store the new poster and update the poster field
            $posterPath = $request->file('poster')->storeAs('public/posters', $request->file('poster')->getClientOriginalName());
            $episode->poster = 'posters/' . $request->file('poster')->getClientOriginalName();
        }

        // Handle local or YouTube video source
        if ($data['edit_video_source'] === 'edit_local') {
            // Check if a new local video file is provided
            if ($request->hasFile('edit_video')) {
                // Upload edited episode video to Backblaze B2
                $editedVideo = $request->file('edit_video');
                $editedVideoPath = 'series/episodes/' . uniqid() . '_' . rawurlencode($editedVideo->getClientOriginalName());

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
                    // Upload edited episode video to Backblaze B2
                    $file = $client->upload([
                        'BucketId' => $existingBucket->getId(),
                        'FileName' => $editedVideoPath,
                        'Body' => fopen($editedVideo->getRealPath(), 'r'), // Open the file resource for reading
                    ]);

                    // Check if the upload was successful
                    if ($file) {
                        $episode->local = $editedVideoPath;
                        $episode->youtube = null; // Clear the YouTube field
                    } else {
                        return response()->json(['status' => 0, 'message' => 'Failed to upload the edited episode video to Backblaze B2']);
                    }
                } else {
                    return response()->json(['status' => 0, 'message' => 'The specified bucket does not exist.']);
                }
            }
        } elseif ($data['edit_video_source'] === 'edit_youtube') {
            $youtubeId = $this->extractYouTubeId($data['edit_youtube_url']);

            if ($youtubeId) {
                $episode->youtube = $youtubeId;
                $episode->local = null; // Clear the local video field
            } else {
                return response()->json(['status' => 0, 'errors' => ['edit_youtube_url' => ['Invalid YouTube URL.']]], 422);
            }
        }
        $episode->save();

        return response()->json(['status' => 1, 'message' => 'Episode updated successfully']);
    }


    public function deleteEpisode($id)
    {
        $episode = SeriesSeasonEpisode::find($id);

        // Check if the video has a local file
        if ($episode->local) {
            // Delete the video file from storage
            $this->deleteVideoFile($episode->local);
        }

        // Delete the video from the database
        $episode->delete();

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

    public function storeCastCrew(Request $request)
    {
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
        $cast = new SeriesCastCrew;
        $cast->series_id = $data['series_id'];
        $cast->name = $data['name'];
        $cast->occupation = $data['occupation'];
        $cast->role = $data['role'];
        $cast->save();

        return response()->json(['status' => 1, 'message' => 'Cast information added successfully']);
    }

    public function deletemem($id)
    {
        // Logic to delete category by ID
        $castCrew = SeriesCastCrew::find($id);
        if (!$castCrew) {
            abort(404); // Or handle not found in a different way
        }
        $castCrew->delete();
        return response()->json(['success' => true]);
    }

}

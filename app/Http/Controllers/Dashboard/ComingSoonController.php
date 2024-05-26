<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Validator;
use App\Models\ComingSoon;
use App\Models\AdminRole;
use DB;
use Auth;

class ComingSoonController extends Controller
{
    public function index()
    {
        Session::put('page', 'coming-soon');
        if (Auth::guard('admin')->user()->type == "super-admin") {
            $comingModule['view_access'] = 1;
            $comingModule['edit_access'] = 1;
            $comingModule['full_access'] = 1;
            $coming = ComingSoon::all();
            return view('dashboard.pages.coming.index', compact('coming','comingModule'));
        }else{
            $coming = ComingSoon::all();
            $comingModuleCount = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'coming'])->count();

            if ($comingModuleCount == 0) {
                $message = "This Feature is restricted for you!";
                Session::flash('error_message', $message);
                return redirect('/dashboard');
            }else{
                $comingModule = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'coming'])->first()->toArray();
            }
            return view('dashboard.pages.coming.index', compact('coming'));
        }
    }

    public function upload(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();

            $validator = Validator::make($data, [
                'title' => 'required',
                'description' => 'required',
                'publish_year' => 'required|date',
                'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5048',
                'poster' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5048',
                'video_source' => 'required|in:local,youtube',
                'video' => $data['video_source'] === 'local' ? 'required_if:video_source,local|mimetypes:video/*' : '',
                'youtube_url' => $data['video_source'] === 'youtube' ? 'required_if:video_source,youtube|url' : '',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            }

            $coming = new ComingSoon();
            $coming->title = $data['title'];
            $trailerThumbnailPath = $request->file('thumbnail')->storeAs('public/thumbnails', $request->file('thumbnail')->getClientOriginalName());
            $coming->thumbnail = 'thumbnails/' . $request->file('thumbnail')->getClientOriginalName();
            $trailerPosterPath = $request->file('poster')->storeAs('public/posters', $request->file('poster')->getClientOriginalName());
            $coming->poster = 'posters/' . $request->file('poster')->getClientOriginalName();

            $coming->publish_year = $data['publish_year'];
            $coming->description = $data['description'];

            if ($data['video_source'] === 'local') {
                $trailerVideoPath = $request->file('video')->storeAs('public/coming/trailers', $request->file('video')->getClientOriginalName());
                $coming->local = 'coming/trailers/' . $request->file('video')->getClientOriginalName();
                $coming->youtube = null; // Clear the YouTube field
            } elseif ($data['video_source'] === 'youtube') {
                $youtubeId = $this->extractYouTubeId($data['youtube_url']);

                if ($youtubeId) {
                    $coming->youtube = $youtubeId;
                    $coming->local = null; // Clear the local video field
                } else {
                    return response()->json(['status' => 0, 'errors' => ['youtube_url' => ['Invalid YouTube URL.']]], 422);
                }
            } else {
                return response()->json(['status' => 0, 'errors' => ['video_source' => ['Invalid video source.']]], 422);
            }

            $coming->save();

            return response()->json(['status' => 1, 'message' => 'Uploaded successfully']);
        }
    }

    private function extractYouTubeId($url)
    {
        $match = preg_match('/(?:youtu\.be\/|youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|.*[?&]video_ids=)([^"&?\/\s]{11})/', $url, $matches);
        return $match ? $matches[1] : null;
    }

    public function edit(Request $request, $id)
    {
        Session::put('page', 'coming-soon');
        $coming = ComingSoon::find($id);
        // dd($request->all());
        if ($request->isMethod('post')) {
            $data = $request->all();

            $validator = Validator::make($data, [
                'title' => 'required',
                'description' => 'required',
                'publish_year' => 'required|date',
                'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:5048',
                'poster' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:5048',
                'video_source' => 'in:local,youtube',
                'video' => $data['video_source'] === 'local' ? 'nullable|mimetypes:video/*' : '',
                'youtube_url' => $data['video_source'] === 'youtube' ? 'required_if:video_source,youtube|url' : '',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            }

            $coming->title = $data['title'];
            if ($request->hasFile('thumbnail')) {
                // Store the new thumbnail and update the thumbnail field
                $thumbnailPath = $request->file('thumbnail')->storeAs('public/thumbnails', $request->file('thumbnail')->getClientOriginalName());
                $coming->thumbnail = 'thumbnails/' . $request->file('thumbnail')->getClientOriginalName();

                // Remove the old thumbnail if it exists
                if (isset($data['old_thumbnail'])) {
                    \Storage::delete('public/' . $data['old_thumbnail']);
                }
            }
            if ($request->hasFile('poster')) {
                // Store the new poster and update the poster field
                $posterPath = $request->file('poster')->storeAs('public/posters', $request->file('poster')->getClientOriginalName());
                $coming->poster = 'posters/' . $request->file('poster')->getClientOriginalName();

                // Remove the old poster if it exists
                if (isset($data['old_poster'])) {
                    \Storage::delete('public/' . $data['old_poster']);
                }
            }

            $coming->publish_year = $data['publish_year'];
            $coming->description = $data['description'];

            if ($data['video_source'] === 'local') {
                // Check if the user uploaded a new local video
                if ($request->hasFile('video')) {
                    $trailerVideoPath = $request->file('video')->storeAs('public/series/trailers', $request->file('video')->getClientOriginalName());
                    $coming->local = 'series/trailers/' . $request->file('video')->getClientOriginalName();
                    $coming->youtube = null; // Clear the YouTube field
                }
            } elseif ($data['video_source'] === 'youtube') {
                // Check if the user provided a new YouTube URL
                if ($request->filled('youtube_url')) {
                    $youtubeId = $this->extractYouTubeId($data['youtube_url']);

                    if ($youtubeId) {
                        $coming->youtube = $youtubeId;
                        $coming->local = null; // Clear the local video field
                    } else {
                        return response()->json(['status' => 0, 'errors' => ['youtube_url' => ['Invalid YouTube URL.']]], 422);
                    }
                }
            } else {
                return response()->json(['status' => 0, 'errors' => ['video_source' => ['Invalid video source.']]], 422);
            }

            $coming->save();

            return response()->json(['status' => 1, 'message' => 'Updated successfully']);
        }
        return view('dashboard.pages.coming.edit', compact('coming'));
    }

    public function delete($id)
    {
        try {
            // Find the series by ID
            $coming = ComingSoon::find($id);

            if (!$coming) {
                throw new \Exception('Coming soon item not found.');
            }

            // Use a transaction to ensure atomicity
            DB::beginTransaction();

            // Check if the thumbnail, poster, and trailer exist and delete them
            $this->deleteComingImages($coming->thumbnail);
            $this->deleteComingImages($coming->poster);

            // Delete the trailer video
            $this->deleteComingVideo($coming->trailer);

            // Finally, delete the movie
            $coming->delete();

            // Commit the transaction
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Successfully Deleted.']);
        } catch (\Exception $e) {
            // Rollback the transaction in case of any error
            DB::rollback();

            // Log the exception
            \Log::error('Delete error: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Failed to delete.']);
        }
    }

    private function deleteComingImages($imagePath)
    {
        if ($imagePath) {
            $fullImagePath = public_path('storage/' . $imagePath);
            if (file_exists($fullImagePath)) {
                unlink($fullImagePath);
            }
        }
    }

    private function deleteComingVideo($videoPath)
    {
        if ($videoPath) {
            $fullVideoPath = public_path('storage/' . $videoPath);
            if (file_exists($fullVideoPath)) {
                unlink($fullVideoPath);
            }
        }
    }

}

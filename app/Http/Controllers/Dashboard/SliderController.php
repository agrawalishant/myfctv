<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Series;
use App\Models\Slider;
use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\AdminRole;
use Auth;
use Session;
use Validator;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        Session::put('page', 'slider');
        if (Auth::guard('admin')->user()->type == "super-admin") {
            $sliderModule['view_access'] = 1;
            $sliderModule['edit_access'] = 1;
            $sliderModule['full_access'] = 1;
            $movies = Movie::select('id', 'name')->get();
            $series = Series::select('id', 'title')->get();
            $slider = Slider::all();
            return view('dashboard.pages.slider.index', compact('movies', 'series', 'slider','sliderModule'));
        }else{
            $movies = Movie::select('id', 'name')->get();
            $series = Series::select('id', 'title')->get();
            $slider = Slider::all();
            $sliderModuleCount = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'slider'])->count();

            if ($sliderModuleCount == 0) {
                $message = "This Feature is restricted for you!";
                Session::flash('error_message', $message);
                return redirect('/dashboard');
            }else{
                $sliderModule = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'slider'])->first()->toArray();
            }
            return view('dashboard.pages.slider.index', compact('movies', 'series', 'slider','sliderModule'));
        }
    }

    public function uploadSlider(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();

            $validator = Validator::make($data, [
                'title' => 'required',
                'post_type' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5048',
                'movie_id' => 'required_if:post_type,movie',
                'series_id' => 'required_if:post_type,series',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            }

            $imagePath = $request->file('image')->storeAs('public/sliders', $request->file('image')->getClientOriginalName());

            $slider = new Slider;
            $slider->title = $data['title'];
            $slider->post_type = $data['post_type'];
            $slider->movie_id = $data['movie_id'] ?? null;
            $slider->series_id = $data['series_id'] ?? null;
            $slider->image = 'sliders/' . $request->file('image')->getClientOriginalName();
            $slider->status = $data['status'];
            $slider->save();

            return response()->json(['status' => 1, 'message' => 'Slider uploaded successfully']);
        }
    }

    public function updateSlider(Request $request, $id)
    {
        $slider = Slider::find($id);

        if ($request->isMethod('post')) {
            $data = $request->all();

            $validator = Validator::make($data, [
                'title' => 'required',
                'post_type' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:5048',
                'movie_id' => 'required_if:post_type,movie',
                'series_id' => 'required_if:post_type,series',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            }

            $slider->title = $data['title'];
            $slider->post_type = $data['post_type'];

            // If post_type is 'series' and movie_id is provided, set movie_id to null
            if ($data['post_type'] === 'series' && isset($data['movie_id'])) {
                $slider->movie_id = null;
                $slider->series_id = $data['series_id'] ?? null;
            } else {
                // If post_type is 'movie' and series_id is provided, set series_id to null
                if ($data['post_type'] === 'movie' && isset($data['series_id'])) {
                    $slider->series_id = null;
                    $slider->movie_id = $data['movie_id'] ?? null;
                } else {
                    $slider->movie_id = $data['movie_id'] ?? null;
                    $slider->series_id = $data['series_id'] ?? null;
                }
            }

            $slider->status = $data['status'];

            // Update image if provided
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->storeAs('public/sliders', $request->file('image')->getClientOriginalName());
                $slider->image = 'sliders/' . $request->file('image')->getClientOriginalName();
            }

            $slider->save();

            return response()->json(['status' => 1, 'message' => 'Slider updated successfully']);
        }
    }


    public function deleteSlider($id)
    {
        $slider = Slider::find($id);

        if (!$slider) {
            return response()->json(['status' => false, 'message' => 'Slider not found']);
        }

        // Check if the image field is set
        if (!$slider->image) {
            return response()->json(['status' => false, 'message' => 'Image path not found']);
        }

        // Delete the associated image from storage
        Storage::delete('public/' . $slider->image);

        // Delete the slider record from the database
        $slider->delete();
        return response()->json(['status' => true]);
    }
}

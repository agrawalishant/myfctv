<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MovieCategory;
use App\Models\AdminRole;
use Session;
use Image;
use Validator;
use Illuminate\Support\Facades\Storage;
use Auth;

class MovieCategoryController extends Controller
{

    public function Category()
    {
        Session::put('page', 'manageCategory');

        if (Auth::guard('admin')->user()->type == "super-admin") {
            $movieCategory = MovieCategory::all();
            $moviesModule['view_access'] = 1;
            $moviesModule['edit_access'] = 1;
            $moviesModule['full_access'] = 1;
            return view('dashboard.pages.movies.manageCategory', compact('movieCategory', 'moviesModule'));
        } else {
            $movieCategory = MovieCategory::all();
            $moviesModuleCount = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'movies'])->count();

            if ($moviesModuleCount == 0) {
                $message = "This Feature is restricted for you!";
                Session::flash('error_message', $message);
                return redirect('/dashboard');
            } else {
                $moviesModule = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'movies'])->first()->toArray();
                // dd($moviesModule); die;

            }
            return view('dashboard.pages.movies.manageCategory', compact('movieCategory', 'moviesModule'));
        }
    }

    public function addCategory(Request $request)
    {
        Session::put('page', 'addCategory');

        if ($request->isMethod('post')) {
            // Validation rules
            $rules = [
                'category_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp',
                'access_control' => 'required|in:public,restricted',
                'age_restriction' => 'nullable|integer|min:0',
                'featured_category' => 'boolean',
            ];

            // Custom error messages
            $messages = [
                'image.image' => 'The cover image must be a valid image file.',
                'image.mimes' => 'The cover image must be of type: jpeg, png, jpg, gif, svg, webp',
            ];

            // Validate the request data
            $validator = Validator::make($request->all(), $rules, $messages);

            // Return validation errors as JSON response
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()]);
            }

            // Process the validated data
            $data = $request->all();

            $category = new MovieCategory();
            $category->category_name = $data['category_name'];
            $category->description = $data['description'];
            $category->access_control = $data['access_control'];
            $category->age_restriction = $data['age_restriction'];
            $category->featured_category = isset($data['featured_category']) ? true : false;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = $image->storeAs('public/categories', $image->getClientOriginalName());
                $category->image = 'categories/' . $image->getClientOriginalName();
            }
            $category->save();
            return response()->json([
                'status' => 1,
            ]);
        }

        if (Auth::guard('admin')->user()->type == "super-admin") {
            $moviesModule['view_access'] = 1;
            $moviesModule['edit_access'] = 1;
            $moviesModule['full_access'] = 1;
            return view('dashboard.pages.movies.addCategory', compact('moviesModule'));
        } else {
            $moviesModuleCount = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'movies'])->count();

            if ($moviesModuleCount == 0) {
                $message = "This Feature is restricted for you!";
                Session::flash('error_message', $message);
                return redirect('/dashboard');
            } else {
                $moviesModule = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'movies'])->first()->toArray();
                return view('dashboard.pages.movies.addCategory', compact('moviesModule'));
            }
        }
    }

    public function editCategory(Request $request, $id = null)
    {
        Session::put('page', 'editCategory');

        // Find the category if editing
        $category = MovieCategory::findOrFail($id);
        // dd($category);

        if ($request->isMethod('post')) {
            // Validation rules
            $rules = [
                'category_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp',
                'access_control' => 'required|in:public,restricted',
                'age_restriction' => 'nullable|integer|min:0',
                'featured_category' => 'boolean',
            ];

            // Custom error messages
            $messages = [
                'image.image' => 'The cover image must be a valid image file.',
                'image.mimes' => 'The cover image must be of type: jpeg, png, jpg, gif, svg, webp',
            ];

            // Validate the request data
            $validator = Validator::make($request->all(), $rules, $messages);

            // Return validation errors as JSON response
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()]);
            }

            // Process the validated data
            $data = $request->all();

            // Update category details
            $category->category_name = $data['category_name'];
            $category->description = $data['description'];
            $category->access_control = $data['access_control'];
            $category->age_restriction = $data['age_restriction'];
            $category->featured_category = isset($data['featured_category']) ? true : false;

            // Upload Category Cover Image
            if ($request->hasFile('image')) {
                // Store the new image and update the image field
                $image = $request->file('image');
                $imagePath = $image->storeAs('public/categories', $image->getClientOriginalName());
                $category->image = 'categories/' . $image->getClientOriginalName();
            }

            $category->save();
            return response()->json([
                'status' => 1,
            ]);
        }

        // If it's not a POST request, just return the view with the category data
        return view('dashboard.pages.movies.editCategory', compact('category'));
    }

    public function deletemcat($id)
    {
        try {
            // Logic to delete category by ID
            $mcat = MovieCategory::findOrFail($id);

            // Check if there are linked movies
            $linkedMoviesCount = $mcat->movies()->count();

            if ($linkedMoviesCount > 0) {
                // Return a response indicating linked movies
                return response()->json([
                    'status' => false,
                    'message' => 'Cannot delete category. There are linked movies.',
                ]);
            }

            // Check if the image exists
            if ($mcat->cover_image_url && Storage::exists('images/movie_category/' . $mcat->cover_image_url)) {
                // Delete the associated image from storage
                Storage::delete('images/movie_category/' . $mcat->cover_image_url);
            }

            // Delete the category from the database
            $mcat->delete();

            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            \Log::error('Failed to delete movie category. Error: ' . $e->getMessage());

            return response()->json(['status' => false, 'message' => 'Failed to delete Movie category.']);
        }
    }


}
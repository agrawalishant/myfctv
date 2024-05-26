<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Role;
use Hash;
use Validator;
use Auth;
use Alert;
use App\Models\AdminRole;
use App\Models\Movie;
use App\Models\MovieCategory;
use App\Models\Series;
use App\Models\SeriesSeasonEpisode;
use Session;
use Image;


class HomeController extends Controller
{
    public function home()
    {
        Session::put('page', 'dashboard');
        $latestMovies = Movie::latest()->take(10)->get();
        $hasMovies = $latestMovies->isNotEmpty();

        $totalMovies = Movie::count();
        $totalSeries = Series::count();
        $totalEpisodes = SeriesSeasonEpisode::count();
        $totalCategories = MovieCategory::count();

        return view('dashboard.index', compact('latestMovies', 'hasMovies','totalMovies','totalSeries','totalEpisodes','totalCategories'));
    }

    public function login(Request $request)
    {
        // echo $password = Hash::make('123456'); die;
        if ($request->isMethod('post')) {
            $data = $request->all();
            /*echo "<pre>"; print_r($data); die;*/
            $validator = validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (!$validator->passes()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toarray()]);
            } else {
                if (Auth::guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
                    return response()->json(['status' => 1]);
                } else {
                    return response()->json(['status' => 2, 'error' => ['message' => 'Invalid email or password']]);
                }
            }
        }
        return view('dashboard.login');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        toast('Logout Successfully', 'success');
        return redirect('/system-login');
    }

    public function updatePassword()
    {
        Session::put('page', 'update-password');
        $adminDetails = Admin::where('email', Auth::guard('admin')->user()->email)->first();
        return view('dashboard.pages.settings.update', compact('adminDetails'));
    }

    public function chkCurrentPassword(Request $request)
    {
        $data = $request->all();
        //echo"<pre>"; print_r($data); 
        //echo "<pre>"; print_r(Auth::guard('admin')->user()->password); die;
        if (Hash::check($data['current_pwd'], Auth::guard('admin')->user()->password)) {
            echo "true";
        } else {
            echo "false";
        }
    }

    public function updateCurrentPassword(Request $request)
    {
        Session::put('page', 'update-password');
        if ($request->isMethod('post')) {
            $data = $request->all();

            $validator = Validator::make($data, [
                'current_pwd' => 'required',
                'new_pwd' => 'required',
                'confirm_pwd' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            }

            //echo "<pre>"; print_r($data); die;
            // Check if current password is correct
            if (Hash::check($data['current_pwd'], Auth::guard('admin')->user()->password)) {
                // Check if new and confirm password is matching
                if ($data['new_pwd'] == $data['confirm_pwd']) {
                    Admin::where('id', Auth::guard('admin')->user()->id)->update(['password' => bcrypt($data['new_pwd'])]);
                    return response()->json(['status' => 1]);
                } else {
                    return response()->json(['status' => 2]);
                }
            } else {
                return response()->json(['status' => 3]);
            }
        }
    }

    public function Profile()
    {
        Session::put('page', 'profile');
        $adminDetails = Admin::where('email', Auth::guard('admin')->user()->email)->first();
        return view('dashboard.pages.settings.profile', compact('adminDetails'));
    }

    public function updateAdminDetails(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();

            $validator = Validator::make($data, [
                'admin_name' => 'required|regex:/^[\pL\s\-]+$/u',
                'admin_mobile' => 'required|numeric',
                'admin_image' => 'image',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            }

            // Upload Image
            if ($request->hasFile('admin_image')) {
                $imageName = $request->file('admin_image')->getClientOriginalName();
                $imagePath = $request->file('admin_image')->storeAs('public/admins', $imageName);
                $data['admin_image'] = 'admins/' . $imageName;
            }

            // Update Admin Details
            $updateData = [
                'name' => $data['admin_name'],
                'mobile' => $data['admin_mobile'],
            ];

            // Check if 'admin_image' key exists before using it
            if (array_key_exists('admin_image', $data)) {
                $updateData['image'] = $data['admin_image'];
            }

            Admin::where('email', Auth::guard('admin')->user()->email)
                ->update($updateData);

            return response()->json(['status' => 1]);
        }
    }

    public function role()
    {
        Session::put('page', 'role');
        if (Auth::guard('admin')->user()->type == "super-admin") {
            $role = Role::all();
            $adminModule['view_access'] = 1;
            $adminModule['edit_access'] = 1;
            $adminModule['full_access'] = 1;
            return view('dashboard.pages.admin.role', compact('role', 'adminModule'));
        } else {
            $role = Role::all();
            $adminModuleCount = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'admin'])->count();

            if ($adminModuleCount == 0) {
                $message = "This Feature is restricted for you!";
                Session::flash('error_message', $message);
                return redirect('/dashboard');
            } else {
                $adminModule = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'admin'])->first();

                // Check if the admin has edit access
                if ($adminModule->edit_access) {
                    return view('dashboard.pages.admin.role', compact('role', 'adminModule'));
                } else {
                    $message = "This Feature is restricted for you!";
                    Session::flash('error_message', $message);
                    return redirect('/dashboard');
                }
            }
        }
    }

    public function addRole(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();

            // Check if a role with the same name already exists
            $existingRole = Role::where('name', $data['name'])->first();

            if ($existingRole) {
                return response()->json(['status' => 0, 'error' => 'Role with this name already exists.']);
            }

            // If the role doesn't exist, create a new role
            $role = new Role;
            $role->name = $data['name'];
            $role->status = 1;
            $role->save();

            return response()->json(['status' => 1]);
        }
    }

    public function updateRoleStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['status'] == "Active") {
                $status = 0;
            } else {
                $status = 1;
            }
            Role::where('id', $data['role_id'])->update(['status' => $status]);
            return response()->json(['status' => $status, 'role_id' => $data['role_id']]);
        }
    }

    public function deleteRole($id)
    {
        // Find the role
        $role = Role::findOrFail($id);

        // Check if any admins are linked to this role
        $adminsWithRole = Admin::where('type', $role->name)->count();

        if ($adminsWithRole > 0) {
            // If admins are linked, prevent deletion and return a response
            return response()->json(['status' => 0, 'message' => 'Cannot delete role. Admins are linked to this role.']);
        }

        // If no admins are linked, proceed with deletion
        $role->delete();

        return response()->json(['status' => 1, 'message' => 'Role deleted successfully.']);
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Services\MailService;
use App\Models\Role;
use App\Models\AdminRole;
use App\Models\Admin;
use Session;
use Validator;
use Auth;

class AdminController extends Controller
{
    public function create()
    {
        Session::put('page', 'create-admin');
        if (Auth::guard('admin')->user()->type == "super-admin") {
            $role = Role::where('status', 1)->get();
            $adminModule['view_access'] = 1;
            $adminModule['edit_access'] = 1;
            $adminModule['full_access'] = 1;
            return view('dashboard.pages.admin.add', compact('role', 'adminModule'));
        } else {
            $role = Role::where('status', 1)->get();
            $adminModuleCount = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'admin'])->count();

            if ($adminModuleCount == 0) {
                $message = "This Feature is restricted for you!";
                Session::flash('error_message', $message);
                return redirect('/dashboard');
            } else {
                $adminModule = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'admin'])->first();

                // Check if the admin has edit access
                if ($adminModule->edit_access) {
                    return view('dashboard.pages.admin.add', compact('role', 'adminModule'));
                } else {
                    $message = "This Feature is restricted for you!";
                    Session::flash('error_message', $message);
                    return redirect('/dashboard');
                }
            }
        }
    }

    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();

            $validator = Validator::make($data, [
                'name' => 'required',
                'type' => 'required',
                'mobile' => 'required',
                'email' => 'required|email',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:5048',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['status' => 0, 'error' => $errors->toArray()]);
            }

            $existingEmail = Admin::where('email', $data['email'])->first();

            if ($existingEmail) {
                return response()->json(['status' => 2, 'message' => 'This email is already associated with another account.']);
            }


            $admin = new Admin;
            $admin->name = $data['name'];
            $admin->type = $data['type'];
            $admin->email = $data['email'];
            $admin->mobile = $data['mobile'];
            if ($data['password'] != "") {
                $admin->password = bcrypt($data['password']);
            }
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->storeAs('public/admins', $request->file('image')->getClientOriginalName());
                $admin->image = 'admins/' . $request->file('image')->getClientOriginalName();
            } else {
                $admin->image = null; // or any default value you prefer
            }
            $admin->status = 1;

            // Save the admin only if email is unique
            $admin->save();

            // Send Details Email to User
            $email = $data['email'];
            $messageData = [
                'email' => $data['email'],
                'mobile' => $data['mobile'],
                'name' => $data['name'],
                'password' => $data['password'],
            ];
            MailService::setSmtpConfig();
            Mail::send('emails.register', $messageData, function ($message) use ($email) {
                $message->to($email)->subject('Welcome to {company name} portal');
            });

            return response()->json(['status' => 1]);
        }
    }

    public function manageAdmin()
    {
        Session::put('page', 'manage-admin');
        if (Auth::guard('admin')->user()->type == "super-admin") {
            $admin = Admin::all();
            $adminModule['view_access'] = 1;
            $adminModule['edit_access'] = 1;
            $adminModule['full_access'] = 1;
            return view('dashboard.pages.admin.manage', compact('admin','adminModule'));
        }else{
            $admin = Admin::all();
            $adminModuleCount = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'admin'])->count();

            if ($adminModuleCount == 0) {
                $message = "This Feature is restricted for you!";
                Session::flash('error_message', $message);
                return redirect('/dashboard');
            }else{
                $adminModule = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'admin'])->first()->toArray();
            }
            return view('dashboard.pages.admin.manage', compact('admin','adminModule'));
        }
    }

    public function updateAdminStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['status'] == "Active") {
                $status = 0;
            } else {
                $status = 1;
            }
            Admin::where('id', $data['admin_id'])->update(['status' => $status]);
            return response()->json(['status' => $status, 'admin_id' => $data['admin_id']]);
        }
    }

    public function editAdmin(Request $request, $id)
    {
        Session::put('page', 'manage-admin');
        $admin = Admin::find($id);

        if ($request->isMethod('post')) {
            $data = $request->all();

            $validator = Validator::make($data, [
                'name' => 'required',
                'type' => 'required',
                'mobile' => 'required',
                'email' => 'required|email',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:5048',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            }

            // Check if the user is updating the email
            if ($data['email'] !== $admin->email) {
                $existingEmail = Admin::where('email', $data['email'])->first();

                if ($existingEmail) {
                    return response()->json(['status' => 2, 'message' => 'This email is already associated with another account.']);
                }
            }

            // Update admin information
            $admin->name = $data['name'];
            $admin->email = $data['email'];
            $admin->type = $data['type'];
            $admin->mobile = $data['mobile'];

            // Update password only if provided
            if ($data['password'] != "") {
                $admin->password = bcrypt($data['password']);
            }

            // Update image only if provided
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->storeAs('public/admins', $request->file('image')->getClientOriginalName());
                $admin->image = 'admins/' . $request->file('image')->getClientOriginalName();
            }

            $admin->status = 1;
            $admin->save();

            return response()->json(['status' => 1, 'message' => 'Admin details updated successfully']);
        }

        $role = Role::where('status', 1)->get();
        return view('dashboard.pages.admin.edit', compact('admin', 'role'));
    }

    public function deleteAdmin($id)
    {
        $admin = Admin::findOrFail($id);

        // Delete admin roles (this will trigger the cascading delete defined in the model)
        $admin->adminRoles()->delete();

        // Delete the admin
        $admin->delete();

        return response()->json(['status' => 1, 'message' => 'Admin and associated roles deleted successfully']);
    }

    public function updateRole(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            unset($data['_token']);

            // Delete existing roles for the admin
            AdminRole::where('admin_id', $id)->delete();

            // Iterate through the submitted data to insert new roles
            foreach ($data as $key => $value) {
                $view = isset($value['view']) ? $value['view'] : 0;
                $edit = isset($value['edit']) ? $value['edit'] : 0;
                $full = isset($value['full']) ? $value['full'] : 0;

                // Insert new roles for the admin
                AdminRole::create([
                    'admin_id' => $id,
                    'module' => $key,
                    'view_access' => $view,
                    'edit_access' => $edit,
                    'full_access' => $full,
                ]);
            }

            return response()->json(['status' => 1]);
        }

        // Fetch admin details and roles for the view
        $adminDetails = Admin::where('id', $id)->first();
        $adminRoles = AdminRole::where('admin_id', $id)->get();
        $title = "<span style='color: #007BFF;'>Update</span> <span style='color: #28A745;'>" . $adminDetails->name . "</span> (<span style='color: #6C757D;'>" . $adminDetails->type . "</span>) <span style='color: #FFC107;'>Roles/Permission</span>";


        // Return the view with necessary data
        return view('dashboard.pages.admin.set-permission')->with(compact('title', 'adminDetails', 'adminRoles'));
    }

}

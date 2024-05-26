<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminRole;
use Session;
use Auth;

class UserController extends Controller
{
    public function user()
    {
        Session::put('page','user');
        if (Auth::guard('admin')->user()->type == "super-admin") {
            $users = User::all();
            $userModule['view_access'] = 1;
            $userModule['edit_access'] = 1;
            $userModule['full_access'] = 1;
            return view('dashboard.pages.user.user-list', compact('users','userModule'));
        }else{
            $users = User::all();
            $userModuleCount = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'user'])->count();

            if ($userModuleCount == 0) {
                $message = "This Feature is restricted for you!";
                Session::flash('error_message', $message);
                return redirect('/dashboard');
            }else{
                $userModule = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'user'])->first()->toArray();
            }
            return view('dashboard.pages.user.user-list', compact('users','userModule'));
        }      
    }

    public function updateUserStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['status'] == "Active") {
                $status = 0;
            } else {
                $status = 1;
            }
            User::where('id', $data['user_id'])->update(['status' => $status]);
            return response()->json(['status' => $status, 'user_id' => $data['user_id']]);
        }
    }

    public function deleteUser($id)
    {
        // Logic to delete user by ID
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function viewUser($id){
        $user = User::with('subscriptions')->find($id);
        $latestSubscription = $user->subscriptions->first();
        return view('dashboard.pages.user.view',compact('user','latestSubscription'));
    }
}

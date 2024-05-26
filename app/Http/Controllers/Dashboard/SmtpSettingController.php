<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmtpSetting;
use Session;
use Validator;
use App\Models\AdminRole;
use Auth;

class SmtpSettingController extends Controller
{
    public function add(){
        Session::put('page', 'smtp');
        $smtpSettings = SmtpSetting::first();
        return view('dashboard.pages.settings.smtp.add',compact('smtpSettings'));
    }

    public function edit(){
        Session::put('page', 'smtp');
        if (Auth::guard('admin')->user()->type == "super-admin") {
            $smtpSettings = SmtpSetting::first();
            $smtpModule['view_access'] = 1;
            $smtpModule['edit_access'] = 1;
            $smtpModule['full_access'] = 1;
            return view('dashboard.pages.settings.smtp.edit',compact('smtpSettings','smtpModule'));
        }else{
            $smtpSettings = SmtpSetting::first();
            $smtpModule = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'smtp'])->first();
            if ($smtpModule && $smtpModule->edit_access){
                return view('dashboard.pages.settings.smtp.edit',compact('smtpSettings','smtpModule'));
            } else {
                $message = "This Feature is restricted for you!";
                Session::flash('error_message', $message);
                return redirect('/dashboard');
            }
        }
    }

    public function update(Request $request){
        $data = $request->all();

        $validator = Validator::make($data, [
            'host' => 'required',
            'port' => 'required|integer',
            'username' => 'required',
            'password' => 'sometimes|required',
            'encryption' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        }
        
        SmtpSetting::updateOrCreate([], $data);

        // Return a success response
        return response()->json(['status' => 1, 'message' => 'SMTP settings updated successfully']);
    }
}

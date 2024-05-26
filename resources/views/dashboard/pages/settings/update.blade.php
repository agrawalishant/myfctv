@extends('layouts.dashboard.app')
@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Update Password</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="error-text text-center text-danger mb-4" id="pasword_error"></div>
                            <form class="form-horizontal" name="updatePasswordForm" id="updatePasswordForm" method="POST"
                                action="{{ url('dashboard/update-current-pwd') }}" enctype="multipart/form-data">@csrf
                                
                                <div class="form-group row">
                                    <label class="control-label col-sm-3 align-self-center mb-0" for="current_pwd">Current
                                        Password:</label>
                                    <div class="col-sm-9">
                                        <input type="password" name="current_pwd" class="form-control" id="current_pwd"
                                            placeholder="Enter Current Password">
                                        <span id="chkCurrentPwd"></span>
                                        <span class="text-danger error-text current_pwd_error"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-sm-3 align-self-center mb-0" for="new_pwd">New
                                        Password:</label>
                                    <div class="col-sm-9">
                                        <input type="password" name="new_pwd" class="form-control" id="new_pwd"
                                            placeholder="Enter New Password">
                                        <span class="text-danger error-text new_pwd_error"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-sm-3 align-self-center mb-0" for="confirm_pwd">Confirm
                                        Password:</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="confirm_pwd" class="form-control" id="confirm_pwd"
                                            placeholder="Confirm New Password">
                                        <span class="text-danger error-text confirm_pwd_error"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button id="password_submit" type="submit" class="btn btn-primary">Update Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.dashboard.app')
@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Admin Profile</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="error-text text-center text-danger mb-4" id="profile_error"></div>
                            <form class="form-horizontal" id="updateProfileForm" method="POST"
                                action="{{ url('dashboard/update-admin-details') }}" enctype="multipart/form-data">@csrf

                                <div class="form-group row">
                                    <label class="control-label col-sm-3 align-self-center mb-0"
                                        for="admin_email">Email:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control"
                                            value="{{ Auth::guard('admin')->user()->email }}" id="admnin_email" readonly>
                                        <small class="form-text text-muted">This field is not editable. If changes are
                                            needed, please contact the Administrator.</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-sm-3 align-self-center mb-0"
                                        for="admin_type">Role:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control"
                                            value="{{ Auth::guard('admin')->user()->type }}" id="admin_type" readonly>
                                        <small class="form-text text-muted">This field is not editable. If changes are
                                            needed, please contact the Administrator.</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-sm-3 align-self-center mb-0"
                                        for="admin_name">Name:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="admin_name"
                                            value="{{ Auth::guard('admin')->user()->name }}" id="admin_name">
                                        <span class="text-danger error-text admin_name_error"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-sm-3 align-self-center mb-0"
                                        for="admin_mobile">Mobile:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="admin_mobile"
                                            value="{{ Auth::guard('admin')->user()->mobile }}" id="admin_mobile">
                                        <span class="text-danger error-text admin_mobile_error"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-sm-3 align-self-center mb-0" for="admin_image">Profile
                                        Image:</label>
                                    <div class="col-sm-9">
                                        <input type="file" name="admin_image" class="form-control">
                                        <span class="text-danger error-text admin_image_error"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button id="profile_submit" type="submit" class="btn btn-primary">Update
                                        Profile</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.dashboard.app')
@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div>
            <div class="row">
                <div class="col-sm-12 col-lg-7">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Edit Admin/Sub-Admin</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="error-text text-center text-danger mb-4" id="edit_admin_error"></div>
                            <form id="editForm" method="post" action="{{ url('dashboard/edit-admin/'.$admin['id']) }}"
                                enctype="multipart/form-data">@csrf
                                <div class="form-group">
                                    <label class="form-label" for="name">Name:</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter Admin Name"
                                        id="name" value="{{ $admin['name'] }}">
                                    <span class="text-danger error-text name_error"></span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="email">Email address:</label>
                                    <input type="email" class="form-control" name="email"
                                        placeholder="Enter Admin Email" id="email" value="{{ $admin['email'] }}">
                                    <span class="text-danger error-text email_error"></span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="mobile">Contact Number:</label>
                                    <input type="text" class="form-control" name="mobile"
                                        placeholder="Enter Admin Mobile Number" id="mobile"
                                        value="{{ $admin['mobile'] }}">
                                    <span class="text-danger error-text mobile_error"></span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Role</label>
                                    <select class="form-select mb-3 shadow-none" name="type">
                                        <option selected="" disabled="">Select Admin Role</option>
                                        @foreach ($role as $r)
                                            @if ($admin->type == $r['name'])
                                                <option value="{{ $r['name'] }}" selected>
                                                    {{ $r['name'] }}</option>
                                            @else
                                                <option value="{{ $r['name'] }}">
                                                    {{ $r['name'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text type_error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="customFile1" class="form-label custom-file-input">Profile Image</label>
                                    <input class="form-control" name="image" type="file" id="customFile1">
                                    <span class="text-danger error-text image_error"></span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="pwd">Password:</label>
                                    <input type="password" name="password" class="form-control" placeholder="*******"
                                        id="pwd">
                                    <span class="text-danger error-text password_error"></span>
                                </div>
                                <button id="adminUpdate" type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

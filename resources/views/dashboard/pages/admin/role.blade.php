@extends('layouts.dashboard.app')
@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="d-flex justify-content-between align-items-center my-5 px-5">
                        <h5>
                            <strong>Admin Roles</strong>
                        </h5>
                        <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#role-modal">
                            <i class="fa-solid fa-square-plus me-2"></i>Add Roles
                        </button>

                        <div class="modal fade" id="role-modal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="role-modal-label">Add</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="error-text text-center text-danger mb-4" id="role_error"></div>
                                        <form id="roleForm" action="{{ url('dashboard/add-roles') }}" method="POST"
                                            enctype="multipart/form-data" class="needs-validation" novalidate>@csrf
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group px-5 ">
                                                        <label class="form-label flex-grow-1" for="role">
                                                            <strong>Role</strong> <span class="text-danger">*</span>:
                                                        </label>
                                                        <input id="role" type="text" name="name"
                                                            class="form-control " placeholder="Enter Role" required>
                                                        <span class="text-danger error-text name_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" id="roleButton" class="btn btn-primary">
                                            Add Role
                                        </button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="custom-table-effect table-responsive  border rounded py-4">
                        <table class="table mb-0" id="datatable" data-toggle="data-table">
                            <thead>
                                <tr class="bg-white">
                                    <th scope="col">Sr.No</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($role as $r)
                                    <tr>
                                        <td>
                                            {{ $loop->index + 1 }}
                                        </td>
                                        <td class="" style="text-transform: capitalize">
                                            {{ $r['name'] }}
                                        </td>
                                        <td class="">
                                            @if ($r->status == 1)
                                                <a class="updateRoleStatus" id="role-{{ $r->id }}"
                                                    role_id="{{ $r->id }}" href="javascript:void(0)">
                                                    <span class="badge bg-success">Active</span>
                                                </a>
                                            @else
                                                <a class="updateRoleStatus" id="role-{{ $r->id }}"
                                                    role_id="{{ $r->id }}" href="javascript:void(0)">
                                                    <span class="badge bg-primary">Inactive</span>
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-evenly">
                                                <a class="btn btn-primary btn-icon btn-sm rounded-pill ms-2 r-delete-btn"
                                                    role="button" data-toggle="tooltip" data-placement="top" title="Delete"
                                                    data-original-title="Delete" data-id="{{ $r->id }}"
                                                    href="#">
                                                    <span class="btn-inner">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.dashboard.app')
@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center pb-3">
                        <div class="header-title">
                            <h4 class="card-title">Manage Admin / Sub-Admin</h4>
                        </div>
                    </div>

                    <div class="custom-table-effect table-responsive  border rounded py-4">
                        <table class="table mb-0" id="datatable" data-toggle="data-table">
                            <thead>
                                <tr class="bg-white">
                                    <th scope="col">Sr.No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email ID</th>
                                    <th scope="col">Mobile</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($admin as $item)
                                    <tr>
                                        <td>
                                            {{ $loop->index + 1 }}
                                        </td>
                                        <td class="">{{ $item['name'] }}</td>
                                        <td class="">{{ $item['email'] }}</td>
                                        <td class="">
                                            {{ $item['mobile'] }}
                                        </td>
                                        <td class="">{{ $item['type'] }}</td>
                                        <td>
                                            @if ($item->type != 'super-admin')
                                                @if ($item->status == 1)
                                                    <a class="updateAdminStatus" id="admin-{{ $item->id }}"
                                                        admin_id="{{ $item->id }}" href="javascript:void(0)">
                                                        <span class="badge bg-success">Active</span>
                                                    </a>
                                                @else
                                                    <a class="updateAdminStatus" id="admin-{{ $item->id }}"
                                                        admin_id="{{ $item->id }}" href="javascript:void(0)">
                                                        <span class="badge bg-primary">Inactive</span>
                                                    </a>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-evenly">
                                                @if($item->type!="super-admin")
                                                @if ($adminModule['edit_access'] == 1 || $adminModule['full_access'] == 1)
                                                       <a class="btn btn-primary btn-icon btn-sm rounded-pill"
                                                           href="{{ url('dashboard/set-permission/' . $item['id']) }}"
                                                           role="button" data-placement="top" title="Set Permission"
                                                           data-original-title="Set Permission">
                                                           <span class="btn-inner">
                                                               <i class="fa-solid fa-road-barrier"></i>
                                                           </span>
                                                       </a>
                                                       <a class="btn btn-primary btn-icon btn-sm rounded-pill ms-2"
                                                           href="{{ url('dashboard/edit-admin/' . $item['id']) }}"
                                                           role="button" data-placement="top" title="Edit Admin"
                                                           data-original-title="Edit Admin">
                                                           <span class="btn-inner">
                                                               <i class="fa-solid fa-pen"></i>
                                                           </span>
                                                       </a>
                                                   @endif
                                                   @if ($adminModule['full_access'] == 1)
                                                   <a class="btn btn-primary btn-icon btn-sm admin-delete-btn rounded-pill ms-2" data-id="{{ $item->id }}" href="#"
                                                       role="button" data-placement="top" title="Delete Admin"
                                                       data-original-title="Delete Admin">
                                                       <span class="btn-inner">
                                                           <i class="fa-solid fa-trash"></i>
                                                       </span>
                                                   </a>
                                                   @endif
                                               @endif
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

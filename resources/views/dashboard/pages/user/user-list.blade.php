@extends('layouts.dashboard.app')
@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive rounded py-4 table-space">
                                <table id="user-list-table" class="table custom-table" role="grid"
                                data-toggle="data-table">
                                    <thead>
                                        <tr class="ligth">
                                            <th>Sr.No</th>
                                            <th>Name</th>
                                            <th>Contact</th>
                                            <th>Email</th>
                                            <th>Gender</th>
                                            <th>Status</th>
                                            <th>Join Date</th>
                                            <th style="min-width: 100px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $key => $user)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}
                                                </td>
                                                <td>{{ $user['name'] }}</td>
                                                <td>{{ $user['mobile'] }}</td>
                                                <td>{{ $user['email'] }}</td>
                                                <td>{{ $user['gender'] }}</td>
                                                <td>
                                                    @if ($user->status == 1)
                                                        <a class="updateUserStatus" id="user-{{ $user->id }}"
                                                            user_id="{{ $user->id }}" href="javascript:void(0)">
                                                            <span class="badge bg-success">Active</span>
                                                        </a>
                                                    @else
                                                        <a class="updateUserStatus" id="user-{{ $user->id }}"
                                                            user_id="{{ $user->id }}" href="javascript:void(0)">
                                                            <span class="badge bg-primary">Inactive</span>
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>{{ date('d M Y', strtotime($user['created_at'])) }}</td>
                                                <td>
                                                    <div class="flex align-items-center list-user-action">
                                                        @if($userModule['view_access']==1)
                                                        <a class="btn btn-sm btn-icon btn-success rounded"
                                                            data-bs-toggle="tooltip" data-placement="top" title="View User"
                                                            data-bs-original-title="View" href="{{url('dashboard/view-user/'.$user['id'])}}">
                                                            <span class="btn-inner">
                                                                <i class="fa-solid fa-eye fa-xs"></i>
                                                            </span>
                                                        </a>
                                                        @endif
                                                        @if($userModule['full_access']==1)
                                                        <a class="btn btn-sm btn-icon btn-danger rounded user-delete-btn"
                                                            data-toggle="tooltip" data-placement="top" title="Delete"
                                                            data-original-title="Delete" data-id="{{ $user->id }}"
                                                            href="#">
                                                            <span class="btn-inner">
                                                                <i class="fa-solid fa-trash fa-xs"></i>
                                                            </span>
                                                        </a>
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
        </div>
    </div>
@endsection

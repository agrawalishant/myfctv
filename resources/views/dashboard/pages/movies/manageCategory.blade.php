@extends('layouts.dashboard.app')
@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive rounded py-4 table-space">
                                <table id="user-list-table" class="table custom-table" role="grid" data-toggle="data-table">
                                    <thead>
                                        <tr class="ligth">
                                            <th>Sr.No</th>
                                            <th>Name</th>
                                            <th>Access Control</th>
                                            <th>Age Restriction</th>
                                            <th>Featured</th>
                                            <th>Create Date</th>
                                            <th style="min-width: 100px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($movieCategory as $key => $mcat)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}
                                                </td>
                                                <td>{{ $mcat['category_name'] }}</td>
                                                <td>{{ $mcat['access_control'] }}</td>
                                                <td>{{ $mcat['age_restriction'] }}</td>
                                                <td>{{ $mcat['featured_category'] == 1 ? 'Yes' : 'No' }}</td>

                                                <td>{{ date('d M Y', strtotime($mcat['created_at'])) }}</td>
                                                <td>
                                                    <div class="flex align-items-center list-user-action">
                                                        @if ($moviesModule['edit_access'] == 1 || $moviesModule['full_access'] == 1)
                                                        <a class="btn btn-sm btn-icon btn-success rounded"
                                                                    data-bs-toggle="tooltip" data-placement="top"
                                                                    title="" data-bs-original-title="Edit"
                                                                    href="{{ url('dashboard/movie/categories/edit/' . $mcat['id']) }}">
                                                                    <span class="btn-inner">
                                                                        <i class="fa-solid fa-user-edit fa-xs"></i>
                                                                    </span>
                                                                </a>
                                                        @endif
                                                        @if($moviesModule['full_access']==1)
                                                        <a class="btn btn-sm btn-icon btn-danger rounded mcat-delete-btn"
                                                            data-toggle="tooltip" data-placement="top" title="Delete"
                                                            data-original-title="Delete" data-id="{{ $mcat->id }}"
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

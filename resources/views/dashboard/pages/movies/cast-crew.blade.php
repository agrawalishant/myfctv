@extends('layouts.dashboard.app')
@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Add Cast / Crew</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="error-text text-center text-danger mb-4" id="cast_error"></div>
                        <form id="castCrew" method="post" action="{{url('dashboard/movies/'. ($movie->id) .'/cast-crew')}}" enctype="multipart/form-data">@csrf
                            <input type="hidden" name="movie_id" value="{{$movie->id}}">
                            <div class="form-group">
                                <label class="form-label" for="name">Person Name:</label>
                                <input type="text" class="form-control" name="name" id="name">
                                <span class="text-danger error-text name_error"></span>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Occupation:</label>
                                <select class="form-select form-select mb-3 shadow-none" name="occupation">
                                    <option selected="" disabled>Open this select menu</option>
                                    <option value="cast">Cast</option>
                                    <option value="crew">Crew</option>
                                    <option value="production">Production</option>
                                    <option value="director">Director</option>
                                    <option value="actor">Actor</option>
                                </select>
                                <span class="text-danger error-text occupation_error"></span>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="role">As:</label>
                                <input type="text" class="form-control" name="role" id="role">
                                <span class="text-danger error-text role_error"></span>
                            </div>

                            <button type="submit" id="cast_submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="custom-table-effect table-responsive  border rounded py-4">
                        <table class="table mb-0" id="datatable" data-toggle="data-table">
                            <thead>
                                <tr class="bg-white">
                                    <th scope="col">Sr.No</th>
                                    <th scope="col">Cast/Crew</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($castCrew as $castMember)
                                <tr>
                                    <td>
                                        {{ ($castCrew->currentPage() - 1) * $castCrew->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="" style="text-transform: capitalize">{{$castMember['occupation']}}</td>
                                    <td class="">{{$castMember['name']}}</td>
                                    <td class="">                                        
                                        {{$castMember['role']}}
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-evenly">
                                            
                                            <a class="btn btn-primary btn-icon btn-sm rounded-pill ms-2 cmem-delete-btn" role="button" data-toggle="tooltip" data-placement="top" title="Delete"
                                                data-original-title="Delete" data-id="{{ $castMember->id }}"
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

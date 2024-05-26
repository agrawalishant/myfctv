@extends('layouts.dashboard.app')
@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div class="row">
            <div class="col-sm-12">
                <div class="card pb-3">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center pb-3">
                        <div class="d-flex align-items-center pt-3">
                            <div class="header-title">
                                <h4 class="card-title">{!! $title !!}</h4>
                            </div>
                        </div>
                        <div class="error-text text-center text-danger mb-4" id="permission_error"></div>
                        <form id="permissionsForm" method="POST" action="{{url('dashboard/set-permission/'.$adminDetails['id'])}}">@csrf
                        <button class="btn btn-primary" id="submit_button" type="submit" data-bs-toggle="offcanvas"
                        data-bs-target="#season-offcanvas" aria-controls="season-offcanvas"><i
                            class="fa-solid fa-plus me-2"></i>Set Permission</button>
                    </div>
                    <div class="card-body">
                        <div class="table-view table-responsive pt-3 table-space">
                            <table id="seasonTable" class="data-tables table custom-table movie_table">
                                <thead>
                                    <tr class="text-uppercase">
                                        <th class="text-center">
                                            Sr.No
                                        </th>
                                        <th>Module Name</th>
                                        <th>View Access</th>
                                        <th>Add/Edit Access</th>
                                        <th>Full Access</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                        @if (!empty($adminRoles))
                                            @foreach ($adminRoles as $role)
                                                @if ($role['module'] == 'movies')
                                                    @if ($role['view_access'] == 1)
                                                        @php $viewMovies = "checked"; @endphp
                                                    @else
                                                        @php $viewMovies = ""; @endphp
                                                    @endif
                                                    @if ($role['edit_access'] == 1)
                                                        @php $editMovies = "checked"; @endphp
                                                    @else
                                                        @php $editMovies = ""; @endphp
                                                    @endif
                                                    @if ($role['full_access'] == 1)
                                                        @php $fullMovies = "checked"; @endphp
                                                    @else
                                                        @php $fullMovies = ""; @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td>
                                                1
                                            </td>
                                            <td>
                                                Movies
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox" name="movies[view]"
                                                            value="1"
                                                            @if (isset($viewMovies)) {{ $viewMovies }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox" name="movies[edit]"
                                                            value="1"
                                                            @if (isset($editMovies)) {{ $editMovies }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox" name="movies[full]"
                                                            value="1"
                                                            @if (isset($fullMovies)) {{ $fullMovies }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @if (!empty($adminRoles))
                                            @foreach ($adminRoles as $role)
                                                @if ($role['module'] == 'series')
                                                    @if ($role['view_access'] == 1)
                                                        @php $viewSeries = "checked"; @endphp
                                                    @else
                                                        @php $viewSeries = ""; @endphp
                                                    @endif
                                                    @if ($role['edit_access'] == 1)
                                                        @php $editSeries = "checked"; @endphp
                                                    @else
                                                        @php $editSeries = ""; @endphp
                                                    @endif
                                                    @if ($role['full_access'] == 1)
                                                        @php $fullSeries = "checked"; @endphp
                                                    @else
                                                        @php $fullSeries = ""; @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td>
                                                2
                                            </td>
                                            <td>
                                                Series
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox" name="series[view]"
                                                            value="1"
                                                            @if (isset($viewSeries)) {{ $viewSeries }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox" name="series[edit]"
                                                            value="1"
                                                            @if (isset($editSeries)) {{ $editSeries }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox" name="series[full]"
                                                            value="1"
                                                            @if (isset($fullSeries)) {{ $fullSeries }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @if (!empty($adminRoles))
                                            @foreach ($adminRoles as $role)
                                                @if ($role['module'] == 'slider')
                                                    @if ($role['view_access'] == 1)
                                                        @php $viewSlider = "checked"; @endphp
                                                    @else
                                                        @php $viewSlider = ""; @endphp
                                                    @endif
                                                    @if ($role['edit_access'] == 1)
                                                        @php $editSlider = "checked"; @endphp
                                                    @else
                                                        @php $editSlider = ""; @endphp
                                                    @endif
                                                    @if ($role['full_access'] == 1)
                                                        @php $fullSlider = "checked"; @endphp
                                                    @else
                                                        @php $fullSlider = ""; @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td>
                                                3
                                            </td>
                                            <td>
                                                Slider
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox" name="slider[view]"
                                                            value="1"
                                                            @if (isset($viewSlider)) {{ $viewSlider }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox" name="slider[edit]"
                                                            value="1"
                                                            @if (isset($editSlider)) {{ $editSlider }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox" name="slider[full]"
                                                            value="1"
                                                            @if (isset($fullSlider)) {{ $fullSlider }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @if (!empty($adminRoles))
                                            @foreach ($adminRoles as $role)
                                                @if ($role['module'] == 'user')
                                                    @if ($role['view_access'] == 1)
                                                        @php $viewUser = "checked"; @endphp
                                                    @else
                                                        @php $viewUser = ""; @endphp
                                                    @endif
                                                    @if ($role['edit_access'] == 1)
                                                        @php $editUser = "checked"; @endphp
                                                    @else
                                                        @php $editUser = ""; @endphp
                                                    @endif
                                                    @if ($role['full_access'] == 1)
                                                        @php $fullUser = "checked"; @endphp
                                                    @else
                                                        @php $fullUser = ""; @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td>
                                                4
                                            </td>
                                            <td>
                                                User
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox" name="user[view]"
                                                            value="1"
                                                            @if (isset($viewUser)) {{ $viewUser }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox" name="user[edit]"
                                                            value="1"
                                                            @if (isset($editUser)) {{ $editUser }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox" name="user[full]"
                                                            value="1"
                                                            @if (isset($fullUser)) {{ $fullUser }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @if (!empty($adminRoles))
                                            @foreach ($adminRoles as $role)
                                                @if ($role['module'] == 'subscription')
                                                    @if ($role['view_access'] == 1)
                                                        @php $viewSubscription = "checked"; @endphp
                                                    @else
                                                        @php $viewSubscription = ""; @endphp
                                                    @endif
                                                    @if ($role['edit_access'] == 1)
                                                        @php $editSubscription = "checked"; @endphp
                                                    @else
                                                        @php $editSubscription = ""; @endphp
                                                    @endif
                                                    @if ($role['full_access'] == 1)
                                                        @php $fullSubscription = "checked"; @endphp
                                                    @else
                                                        @php $fullSubscription = ""; @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td>
                                                5
                                            </td>
                                            <td>
                                                Subscription Plan
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="subscription[view]" value="1"
                                                            @if (isset($viewSubscription)) {{ $viewSubscription }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="subscription[edit]" value="1"
                                                            @if (isset($editSubscription)) {{ $editSubscription }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="subscription[full]" value="1"
                                                            @if (isset($fullSubscription)) {{ $fullSubscription }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @if (!empty($adminRoles))
                                            @foreach ($adminRoles as $role)
                                                @if ($role['module'] == 'admin')
                                                    @if ($role['view_access'] == 1)
                                                        @php $viewAdmin = "checked"; @endphp
                                                    @else
                                                        @php $viewAdmin = ""; @endphp
                                                    @endif
                                                    @if ($role['edit_access'] == 1)
                                                        @php $editAdmin = "checked"; @endphp
                                                    @else
                                                        @php $editAdmin = ""; @endphp
                                                    @endif
                                                    @if ($role['full_access'] == 1)
                                                        @php $fullAdmin = "checked"; @endphp
                                                    @else
                                                        @php $fullAdmin = ""; @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td>
                                                6
                                            </td>
                                            <td>
                                                Admin/SubAdmin
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="admin[view]" value="1"
                                                            @if (isset($viewAdmin)) {{ $viewAdmin }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="admin[edit]" value="1"
                                                            @if (isset($editAdmin)) {{ $editAdmin }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="admin[full]" value="1"
                                                            @if (isset($fullAdmin)) {{ $fullAdmin }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @if (!empty($adminRoles))
                                            @foreach ($adminRoles as $role)
                                                @if ($role['module'] == 'smtp')
                                                    @if ($role['view_access'] == 1)
                                                        @php $viewSMTP = "checked"; @endphp
                                                    @else
                                                        @php $viewSMTP = ""; @endphp
                                                    @endif
                                                    @if ($role['edit_access'] == 1)
                                                        @php $editSMTP = "checked"; @endphp
                                                    @else
                                                        @php $editSMTP = ""; @endphp
                                                    @endif
                                                    @if ($role['full_access'] == 1)
                                                        @php $fullSMTP = "checked"; @endphp
                                                    @else
                                                        @php $fullSMTP = ""; @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td>
                                                7
                                            </td>
                                            <td>
                                                SMTP
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox" name="smtp[view]"
                                                            value="1"
                                                            @if (isset($viewSMTP)) {{ $viewSMTP }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox" name="smtp[edit]"
                                                            value="1"
                                                            @if (isset($editSMTP)) {{ $editSMTP }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox" name="smtp[full]"
                                                            value="1"
                                                            @if (isset($fullSMTP)) {{ $fullSMTP }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        @if (!empty($adminRoles))
                                            @foreach ($adminRoles as $role)
                                                @if ($role['module'] == 'transaction')
                                                    @if ($role['view_access'] == 1)
                                                        @php $viewTransaction = "checked"; @endphp
                                                    @else
                                                        @php $viewTransaction = ""; @endphp
                                                    @endif                                                    
                                                @endif
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td>
                                                8
                                            </td>
                                            <td>
                                                Transaction
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox" name="smtp[view]"
                                                            value="1"
                                                            @if (isset($viewSMTP)) {{ $viewSMTP }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                            <td>                                                
                                            </td>
                                            <td>                                                
                                            </td>
                                        </tr>

                                        @if (!empty($adminRoles))
                                            @foreach ($adminRoles as $role)
                                                @if ($role['module'] == 'coming')
                                                    @if ($role['view_access'] == 1)
                                                        @php $viewComing = "checked"; @endphp
                                                    @else
                                                        @php $viewComing = ""; @endphp
                                                    @endif
                                                    @if ($role['edit_access'] == 1)
                                                        @php $editComing = "checked"; @endphp
                                                    @else
                                                        @php $editComing = ""; @endphp
                                                    @endif
                                                    @if ($role['full_access'] == 1)
                                                        @php $fullComing = "checked"; @endphp
                                                    @else
                                                        @php $fullComing = ""; @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td>
                                                9
                                            </td>
                                            <td>
                                                Coming Soon
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox" name="coming[view]"
                                                            value="1"
                                                            @if (isset($viewComing)) {{ $viewComing }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox" name="coming[edit]"
                                                            value="1"
                                                            @if (isset($editComing)) {{ $editComing }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-check form-switch ms-2">
                                                        <input class="form-check-input" type="checkbox" name="coming[full]"
                                                            value="1"
                                                            @if (isset($fullComing)) {{ $fullComing }} @endif />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </form>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

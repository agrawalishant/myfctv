@extends('layouts.dashboard.app')
@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div class="row">
            <div class="col-sm-12">
                <div class="card pb-3">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center pb-3">
                        <div class="d-flex align-items-center pt-3">
                            {{-- <div class="form-group">
                            <select type="select" class="form-control select2-basic-multiple" placeholder="No Action">
                                <option>No Action</option>
                                <option>Status</option>
                                <option>Delete</option>
                            </select>
                            <button class="btn btn-primary ">Apply</button>
                        </div> --}}
                        </div>
                        @if ($subscriptionModule['edit_access'] == 1 || $subscriptionModule['full_access'] == 1)
                            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#season-offcanvas" aria-controls="season-offcanvas"><i
                                    class="fa-solid fa-plus me-2"></i>Add Plan</button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-view table-responsive pt-3 table-space">
                            <table id="seasonTable" class="data-tables table custom-table movie_table"
                                data-toggle="data-table">
                                <thead>
                                    <tr class="text-uppercase">
                                        <th class="text-center">
                                            Sr.No
                                        </th>
                                        <th>Plan Name</th>
                                        <th>Duration</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subscription as $item)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}
                                            </td>
                                            <td>{{ $item['name'] }}</td>
                                            <td>{{ $item['duration_quantity'] }} {{ $item['duration_unit'] }}</td>
                                            <td>Rs. {{ $item['price'] }}</td>
                                            <td>{{ $item['status'] == 1 ? 'Active' : 'InActive' }}</td>
                                            <td>
                                                <div class="d-flex align-items-center list-user-action">
                                                    @if ($subscriptionModule['edit_access'] == 1 || $subscriptionModule['full_access'] == 1)
                                                    <button type="button" class="btn btn-sm btn-icon btn-success rounded"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Edit Subscription">
                                                        <i class="fa-solid fa-pen" data-bs-toggle="offcanvas"
                                                            data-bs-target="#season-offcanvas-edit{{ $item['id'] }}"
                                                            aria-controls="season-offcanvas-edit"></i>
                                                    </button>
                                                    @endif
                                                    @if ($subscriptionModule['full_access'] == 1)
                                                    <a class="btn btn-sm btn-icon btn-danger subscription-delete-btn rounded"
                                                        data-toggle="tooltip" data-placement="top" title="Delete"
                                                        data-original-title="Delete Subscription"
                                                        data-id="{{ $item->id }}" href="#">
                                                        <i class="fa-solid fa-trash"></i>
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
            <div class="offcanvas offcanvas-end offcanvas-width-80" tabindex="-1" id="season-offcanvas"
                aria-labelledby="season-offcanvas-lable">
                <div class="offcanvas-header">
                    <h5 id="offcanvasRightLabel1">Add Plan</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>

                <div class="offcanvas-body">
                    <div class="error-text text-center text-danger mb-4" id="subscription_error"></div>
                    <form method="POST" id="subscription_form" action="{{ url('dashboard/subscription-plan/add') }}"
                        enctype="multipart/form-data">@csrf
                        <div class="section-form">
                            <fieldset>
                                <legend>Subscription Plan</legend>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group px-5 ">
                                            <label class="form-label flex-grow-1" for="Plan Name">
                                                <strong>Plan Name</strong> <span class="text-danger">*</span>:
                                            </label>
                                            <input id="Plan Name" type="text" name="name" class="form-control "
                                                placeholder="Enter Plan Name" min="" multiple="" />
                                            <span class="text-danger error-text name_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group px-5 ">
                                            <label class="form-label flex-grow-1" for="Duration Quantity">
                                                <strong>Duration Quantity</strong> :
                                            </label>

                                            <!-- textarea input -->
                                            <input class="form-control" type="number" name="duration_quantity"
                                                id="Duration Quantity" min="1" placeholder="7">
                                            <span class="text-danger error-text duration_quantity_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group px-5 ">
                                            <label class="form-label flex-grow-1" for="Duration Unit">
                                                <strong>Duration Unit</strong> :
                                            </label>

                                            <!-- textarea input -->
                                            <select name="duration_unit" type="select" class="form-control "
                                                placeholder="Duration Unit">
                                                <option selected="" disabled="">Select Type</option>
                                                <option value="day">Day</option>
                                                <option value="month">Month</option>
                                                <option value="year">Year</option>
                                            </select>
                                            <span class="text-danger error-text duration_unit_error"></span>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group px-5 ">
                                            <label class="form-label flex-grow-1" for="Plan Price">
                                                <strong>Plan Price</strong> <span class="text-danger">*</span>:
                                            </label>
                                            <input id="Plan Price" type="number" name="price" id="price"
                                                step="0.01" placeholder="Enter price" class="form-control "
                                                min="0" />
                                            <span class="text-danger error-text price_error"></span>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group px-5">
                                            <label class="form-label flex-grow-1"
                                                for="status"><strong>Status:</strong></label>
                                            <select id="status" type="select" name="status" class="form-control "
                                                placeholder="select Status">
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                            <span class="text-danger error-text status_error"></span>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                </div>
                <div class="offcanvas-footer border-top">
                    <div class="d-grid d-flex gap-3 p-3">
                        <button type="submit" id="subscription_submit" class="btn btn-primary d-block">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Save
                        </button>
                        <button type="button" class="btn btn-outline-primary d-block" data-bs-dismiss="offcanvas"
                            aria-label="Close">
                            <i class="fa-solid fa-angles-left me-2"></i>Close
                        </button>
                    </div>
                </div>
                </form>
            </div>
            @foreach ($subscription as $item)
                <div class="offcanvas offcanvas-end offcanvas-width-80" tabindex="-1"
                    id="season-offcanvas-edit{{ $item['id'] }}" aria-labelledby="season-offcanvas-edit-lable">
                    <div class="offcanvas-header">
                        <h5 id="offcanvasRightLabel1">Edit Subscription Plan</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div class="error-text text-center text-danger mb-4"
                            id="edit_subscription_error_{{ $item['id'] }}"></div>
                        <form method="POST" class="edit_subscription_form"
                            id="edit_subscription_form_{{ $item['id'] }}"
                            action="{{ url('dashboard/subscription-plan/edit/' . $item['id']) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="section-form">
                                <fieldset>
                                    <legend>Subscription Plan</legend>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group px-5">
                                                <label class="form-label flex-grow-1" for="Plan Name">
                                                    <strong>Plan Name</strong> <span class="text-danger">*</span>:
                                                </label>
                                                <input id="Plan Name" type="text" name="name" class="form-control"
                                                    placeholder="Enter Plan Name" value="{{ $item['name'] }}" />
                                                <span class="text-danger error-text title_error"></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group px-5 ">
                                                <label class="form-label flex-grow-1" for="Duration Quantity">
                                                    <strong>Duration Quantity</strong> :
                                                </label>

                                                <!-- textarea input -->
                                                <input class="form-control" type="number" name="duration_quantity"
                                                    id="Duration Quantity" value="{{ $item['duration_quantity'] }}"
                                                    min="1" placeholder="7">
                                                <span class="text-danger error-text duration_quantity_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group px-5 ">
                                                <label class="form-label flex-grow-1" for="Duration Unit">
                                                    <strong>Duration Unit</strong> :
                                                </label>

                                                <!-- textarea input -->
                                                <select name="duration_unit" type="select" class="form-control "
                                                    placeholder="Duration Unit">
                                                    <option selected="" disabled="">Select Type</option>
                                                    <option value="day"
                                                        @if ($item['duration_unit'] == 'day') selected @endif>Day</option>
                                                    <option value="month"
                                                        @if ($item['duration_unit'] == 'month') selected @endif>Month</option>
                                                    <option value="year"
                                                        @if ($item['duration_unit'] == 'year') selected @endif>Year</option>
                                                </select>
                                                <span class="text-danger error-text duration_unit_error"></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group px-5 ">
                                                <label class="form-label flex-grow-1" for="Plan Price">
                                                    <strong>Plan Price</strong> <span class="text-danger">*</span>:
                                                </label>
                                                <input id="Plan Price" type="number" name="price" id="price"
                                                    step="0.01" placeholder="Enter price" class="form-control "
                                                    min="0" value="{{ $item['price'] }}" />
                                                <span class="text-danger error-text price_error"></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group px-5">
                                                <label class="form-label flex-grow-1"
                                                    for="genres"><strong>Status:</strong></label>
                                                <select id="genres" type="select" name="status"
                                                    class="form-control" placeholder="select genres">
                                                    <option value="1"
                                                        @if ($item['status'] == 1) selected @endif>Active</option>
                                                    <option value="0"
                                                        @if ($item['status'] == 0) selected @endif>Inactive</option>
                                                </select>
                                                <span class="text-danger error-text status_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                    </div>
                    <div class="offcanvas-footer border-top">
                        <div class="d-grid d-flex gap-3 p-3">
                            <button type="submit" id="edit_subscription_submit_{{ $item['id'] }}"
                                class="btn btn-primary d-block">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Update
                            </button>
                            <button type="button" class="btn btn-outline-primary d-block" data-bs-dismiss="offcanvas"
                                aria-label="Close">
                                <i class="fa-solid fa-angles-left me-2"></i>Close
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
            @endforeach

        </div>
    </div>
@endsection

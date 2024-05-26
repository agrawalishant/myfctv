@extends('layouts.dashboard.app')
@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div>
            <div class="row">
                <div class="col-sm-12 ">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Add New Movie Category</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <p>Fill out the form below to add a new movie category.</p>
                            <form class="row g-3 needs-validation" id="addCategoryForm" action="{{url('dashboard/movie/categories/create')}}"  method="post" novalidate enctype="multipart/form-data">@csrf
                                <div class="col-md-6">
                                    <label for="validationCustom01" class="form-label">Category Name</label>
                                    <input type="text" class="form-control" name="category_name" id="validationCustom01" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="validationTextarea" class="form-label">Category Description</label>
                                    <textarea class="form-control" name="description" id="validationTextarea" placeholder="Category Description" required></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="validationCustom04" class="form-label">Access Control</label>
                                    <select class="form-select" name="access_control" id="validationCustom04">
                                        <option value="public">Public</option>
                                        <option value="restricted">Restricted</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="age_restriction" class="form-label">Age Restriction</label>
                                    <select class="form-select" name="age_restriction" id="age_restriction" required>
                                        <option value="0">No Age Restriction</option>
                                        <option value="6">6+</option>
                                        <option value="12">12+</option>
                                        <option value="16">16+</option>
                                        <option value="18">18+</option>
                                    </select>
                                </div>
                                <div class="form-group mb-0">
                                    <label for="validationCustom04" class="form-label">Header Image</label>
                                    <input type="file" class="form-control" name="image" aria-label="file example" required>
                                    <small class="form-text text-muted">Recommended dimensions: 1920x1080 pixels.</small>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="featured_category"
                                            id="featured_category" value="1">
                                        <label class="form-check-label" for="featured_category">
                                            Featured Category
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary" id="submitBtn" type="submit">Submit form</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

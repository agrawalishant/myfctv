@extends('layouts.dashboard.app')

@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div>
            <div class="row">
                <div class="col-sm-12 ">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Edit Movie Category</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <p>Edit the form below to update the movie category.</p>
                            <form class="row g-3 needs-validation" id="editCategoryForm"
                                action="{{ url('dashboard/movie/categories/edit/'.$category->id) }}" method="post" novalidate
                                enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-6">
                                    <label for="validationCustom01" class="form-label">Category Name</label>
                                    <input type="text" class="form-control" name="category_name" id="validationCustom01"
                                        value="{{ $category->category_name }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="validationTextarea" class="form-label">Category Description</label>
                                    <textarea class="form-control" name="description" id="validationTextarea" placeholder="Category Description" required>{{ $category->description }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="validationCustom04" class="form-label">Access Control</label>
                                    <select class="form-select" name="access_control" id="validationCustom04">
                                        <option value="public"
                                            {{ $category->access_control === 'public' ? 'selected' : '' }}>Public</option>
                                        <option value="restricted"
                                            {{ $category->access_control === 'restricted' ? 'selected' : '' }}>Restricted
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="age_restriction" class="form-label">Age Restriction</label>
                                    <select class="form-select" name="age_restriction" id="age_restriction" required>
                                        <option value="0" {{ $category->age_restriction === 0 ? 'selected' : '' }}>No
                                            Age Restriction</option>
                                        <option value="6" {{ $category->age_restriction === 6 ? 'selected' : '' }}>6+
                                        </option>
                                        <option value="12" {{ $category->age_restriction === 12 ? 'selected' : '' }}>12+
                                        </option>
                                        <option value="16" {{ $category->age_restriction === 16 ? 'selected' : '' }}>16+
                                        </option>
                                        <option value="18" {{ $category->age_restriction === 18 ? 'selected' : '' }}>18+
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group mb-0">
                                    <label for="validationCustom04" class="form-label">Header Image</label>
                                    <input type="file" class="form-control" name="image" aria-label="file example">
                                    <small class="form-text text-muted">Recommended dimensions: 1920x1080 pixels.</small>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="featured_category"
                                            id="featured_category" value="1"
                                            {{ $category->featured_category ? 'checked' : '' }}>
                                        <label class="form-check-label" for="featured_category">Featured Category</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary" id="submitBtn" type="submit">Update Category</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

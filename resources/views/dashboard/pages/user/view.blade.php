@extends('layouts.dashboard.app')
<style>
    .placeholder-circle {
    width: 100px; /* Adjust the width and height based on your preference for the circle size */
    height: 100px;
    background-color: #b00c1f; /* Placeholder background color */
    color: #fff; /* Text color */
    font-size: 50px;
    font-weight: 900;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%; /* Make it a circle */
}
</style>
@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div class="d-flex flex-wrap align-items-center">
                                <div class="profile-img position-relative me-3 mb-3 mb-lg-0 profile-logo profile-logo1">
                                    @if ($user->image)
                                        <!-- If user has an image, display it -->
                                        <img src="{{ asset($user->image) }}" alt="User-Profile"
                                            class="theme-color-default-img img-fluid rounded-pill avatar-100">
                                    @else
                                        <!-- If user does not have an image, display a styled circular placeholder with the first letter of the name -->
                                        <div class="placeholder-circle">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="d-flex flex-wrap align-items-center mb-3 mb-sm-0">
                                    <h4 class="me-2 h4">{{ $user['name'] }}</h4>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="profile-content">
                    <div class="card">
                        <div class="card-header">
                            <div class="header-title">
                                <h4 class="card-title">About User</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mt-2">
                                        <h6 class="mb-1">Name:</h6>
                                        <p>{{ $user['name'] }}</p>
                                    </div>
                                    <div class="mt-2">
                                        <h6 class="mb-1">E-mail:</h6>
                                        <p>{{ $user['email'] }}</p>
                                    </div>
                                    <div class="mt-2">
                                        <h6 class="mb-1">Contact:</h6>
                                        <p>{{ $user['mobile'] }}</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mt-2">
                                        <h6 class="mb-1">Gender:</h6>
                                        <p>{{ $user['gender'] }}</p>
                                    </div>
                                    <div class="mt-2">
                                        <h6 class="mb-1">Date Of Birth:</h6>
                                        <p>{{ $user['date_of_birth'] }}</p>
                                    </div>
                                    <div class="mt-2">
                                        <h6 class="mb-1">Joined:</h6>
                                        <p>{{ $user->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <div class="header-title">
                            <h4 class="card-title">Subscription</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($latestSubscription)
                            <div class="row">
                                @if ($latestSubscription->start_date)
                                    <div class="col-md-12">
                                        <div class="card border-bottom border-4 border-0 border-primary">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <span>Start Date</span>
                                                    </div>
                                                    <div>
                                                        <span>
                                                            {{ \Carbon\Carbon::parse($latestSubscription->start_date)->format('d M Y') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($latestSubscription->start_date)
                                    <div class="col-md-12">
                                        <div class="card border-bottom border-4 border-0 border-info">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <span>End Date</span>
                                                    </div>
                                                    <div>
                                                        <span>
                                                            {{ \Carbon\Carbon::parse($latestSubscription->end_date)->format('d M Y') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>No subscriptions found for this user.</span>
                                </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    </div>
    </div>
@endsection

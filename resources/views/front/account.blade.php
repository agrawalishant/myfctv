@extends('layouts.front.layout')
@section('content')
    <div class="iq-breadcrumb" style="background-image: url(assets/user/images/pages/01.webp);">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <nav aria-label="breadcrumb" class="text-center">
                        <h2 class="title">My Account</h2>
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active">My Account</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div> <!--bread-crumb-->

    <div class="section-padding service-details">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    <div class="acc-left-menu p-4 mb-5 mb-lg-0 mb-md-0">
                        <div class="product-menu">
                            <ul class="list-inline m-0 nav nav-tabs flex-column bg-transparent border-0" role="tablist">
                                <li class="pb-3 nav-item">
                                    <button class="nav-link active p-0 bg-transparent" data-bs-toggle="tab"
                                        data-bs-target="#dashboard" type="button" role="tab" aria-selected="true"><i
                                            class="fas fa-tachometer-alt"></i><span class="ms-2">Dashboard</span></button>
                                </li>
                                <li class="py-3 nav-item">
                                    <button class="nav-link p-0 bg-transparent" data-bs-toggle="tab"
                                        data-bs-target="#orders" type="button" role="tab" aria-selected="true"><i
                                            class="fas fa-list"></i><span class="ms-2">Subscription
                                            History</span></button>
                                </li>
                                {{-- <li class="py-3 nav-item">
                                    <button class="nav-link p-0 bg-transparent" data-bs-toggle="tab"
                                        data-bs-target="#downloads" type="button" role="tab" aria-selected="true"><i
                                            class="fas fa-download"></i><span class="ms-2">Downloads</span></button>
                                </li>
                                 --}}
                                <li class="py-3 nav-item">
                                    <button class="nav-link p-0 bg-transparent" data-bs-toggle="tab"
                                        data-bs-target="#account-details" type="button" role="tab"
                                        aria-selected="true"><i class="fas fa-user"></i><span class="ms-2">Account
                                            details</span></button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-8">
                    <div class="tab-content" id="product-menu-content">
                        <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
                            <div class="myaccount-content text-body p-4">
                                <p class="mb-3">Hello {{ auth('user')->user()->name }} (<span class="text-muted">not
                                        {{ auth('user')->user()->name }}</span>? <a href="{{ url('/logout') }}">Log out</a>)
                                </p>
                                <p>Hey there! Here's what you can do:</p>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-tv"></i> Check out <a href="javascript:void(0)">new shows and
                                            movies</a></li>
                                    <li><i class="fas fa-list"></i> Add cool stuff to your <a
                                            href="javascript:void(0)">watchlist</a></li>
                                    <li><i class="fas fa-cog"></i> Update your <a href="javascript:void(0)">account
                                            settings</a> anytime</li>
                                    @if (auth('user')->user()->hasActiveSubscription())
                                        <li class="mt-3"><strong>Remaining days until plan expires:</strong>
                                            {{ auth('user')->user()->remainingDaysInSubscription() }} Days</li>
                                    @else
                                        <li class="mt-3">No active subscription plan. <a
                                                href="{{ route('subscribe') }}">Subscribe now</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="orders" role="tabpanel">
                        <div class="orders-table text-body p-4">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr class="border-bottom">
                                            <th class="fw-bolder p-3">Transaction Id</th>
                                            <th class="fw-bolder p-3">Payment Method</th>
                                            <th class="fw-bolder p-3">Amount</th>
                                            <th class="fw-bolder p-3">Payment Status</th>
                                            <th class="fw-bolder p-3">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($subscription->isEmpty())
                                            <p>No subscription transactions found.</p>
                                        @else
                                            @foreach ($subscription as $item)
                                                <tr class="border-bottom">
                                                    <td class="text-primary fs-6">{{ $item['payment_id'] }}</td>
                                                    <td style="text-transform: capitalize">{{ $item['payment_method'] }}
                                                    </td>
                                                    <td>{{ $item['amount'] }} {{ $item['currency'] }}</td>
                                                    <td style="text-transform: capitalize">{{ $item['payment_status'] }}
                                                    </td>
                                                    <td>
                                                        {{ $item->created_at->format('d M, Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="tab-pane fade" id="downloads" role="tabpanel">
                            <div class="orders-table text-body p-4">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr class="border-bottom">
                                                <th class="fw-bolder p-3">Product</th>
                                                <th class="fw-bolder p-3">Downloads Remaining</th>
                                                <th class="fw-bolder p-3">Expires</th>
                                                <th class="fw-bolder p-3">Download</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="p-3 fs-6">Electric Toothbrush</td>
                                                <td class="p-3">∞</td>
                                                <td class="p-3 fs-6">Never</td>
                                                <td class="p-3"><a href="#"
                                                        class="p-2 bg-primary text-white fs-6" download>Product
                                                        Demo</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> --}}
                    <div class="tab-pane fade" id="account-details" role="tabpanel">
                        <div class=" p-4 text-body">
                            <div class="error-text text-center text-danger mb-4" id="update_error"></div>
                            <form id="update_form" action="{{ url('update-profile') }}" method="POST">@csrf
                                <div class="form-group mb-5">
                                    <label class="mb-2">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ $user['name'] }}" class="form-control">
                                    <span class="text-danger error-text name_error"></span>
                                </div>
                                <div class="form-group mb-5">
                                    <label class="mb-2">Email <span class="text-danger">*</span></label>
                                    <input type="text" name="email" value="{{ $user['email'] }}"
                                        class="form-control" readonly>
                                    <span class="text-danger error-text email_error"></span>
                                </div>
                                <div class="form-group mb-5">
                                    <label class="mb-2">Mobile <span class="text-danger">*</span></label>
                                    <input type="text" name="mobile" value="{{ $user['mobile'] }}"
                                        class="form-control">
                                    <span class="text-danger error-text mobile_error"></span>
                                </div>
                                <div class="form-group mb-5">
                                    <label class="mb-2">Gender <span class="text-danger">*</span></label>
                                    <select name="gender" class="form-control">
                                        <option value="male" {{ $user['gender'] === 'male' ? 'selected' : '' }}>Male
                                        </option>
                                        <option value="female" {{ $user['gender'] === 'female' ? 'selected' : '' }}>
                                            Female</option>
                                        <option value="other" {{ $user['gender'] === 'other' ? 'selected' : '' }}>
                                            Other</option>
                                    </select>
                                    <span class="text-danger error-text gender_error"></span>
                                </div>
                                <div class="form-group mb-5">
                                    <label class="mb-2">Date of Birth <span class="text-danger">*</span></label>
                                    <input type="date" name="date_of_birth" value="{{ $user['date_of_birth'] }}"
                                        class="form-control">
                                    <span class="text-danger error-text date_of_birth_error"></span>
                                </div>

                                <div class="form-group">
                                    <div class="iq-button">
                                        <button type="submit" id="update_button"
                                            class="btn text-uppercase position-relative">
                                            <span class="button-text">Save Changes</span>
                                            <i class="fa-solid fa-play"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

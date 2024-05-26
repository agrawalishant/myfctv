@extends('layouts.front.layout')
@section('content')
    <div class="vh-100"
        style="background: url('assets/user/images/pages/01.webp'); background-size: cover; background-repeat: no-repeat; position: relative;min-height:500px">
        <div class="container">
            <div class="row justify-content-center align-items-center height-self-center vh-100">
                <div class="col-lg-5 col-md-12 align-self-center">
                    <div class="user-login-card bg-body">
                        <p>Please enter your new password below:</p>
                        <p style="color: red">Please avoid refreshing or reloading the page during this process.</p>
                        <form action="{{ url('change-password') }}" method="post">
                            @csrf
                            <div class="mb-5">
                                <label class="text-white fw-500 mb-2">Email Address</label>
                                <input type="text" name="email" class="form-control rounded-0"
                                    value="{{ session('email') }}" readonly>
                            </div>
                            <input type="hidden" name="user_type" value="user">
                            <div class="mb-5">
                                <label class="text-white fw-500 mb-2">OTP</label>
                                <input type="text" name="otp" class="form-control rounded-0"
                                    value="{{ old('otp') }}" required>
                            </div>
                            <div class="mb-5">
                                <label class="text-white fw-500 mb-2">Password</label>
                                <input type="password" name="password" class="form-control rounded-0" required>
                            </div>
                            <div class="mb-5">
                                <label class="text-white fw-500 mb-2">Confirmed Password</label>
                                <input type="password" name="password_confirmation" class="form-control rounded-0" required>
                            </div>
                            <div class="iq-button">
                                <button type="submit" class="btn text-uppercase position-relative">
                                    <span class="button-text">Set new password</span>
                                    <i class="fa-solid fa-play"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Detect page refresh or reload
        if (window.performance) {
            if (performance.navigation.type === 1) {
                alert('Please avoid refreshing or reloading the page during this process.');
            }
        }
    </script>
@endsection

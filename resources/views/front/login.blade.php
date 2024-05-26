@extends('layouts.front.layout')
@section('content')
    <div class=""
        style="background: url('assets/user/images/pages/01.webp'); background-size: cover; background-repeat: no-repeat; position: relative;min-height:500px">
        <div class="container">
            <div class="row justify-content-center align-items-center height-self-center vh-100">
                <div class="col-lg-5 col-md-12 align-self-center">
                    <div class="user-login-card bg-body">
                        <div class="text-center">
                            <!--Logo -->
                            <div class="logo-default">
                                <a class="navbar-brand text-primary" href="./index.html">
                                    <img class="img-fluid logo" src="{{asset('assets/user/images/logo.webp')}}" loading="lazy"
                                        alt="streamit" />
                                </a>
                            </div>
                        </div>
                        <div class="error-text text-center text-danger mb-4" id="login_error"></div>
                        <form id="login_form" action="{{url('login')}}" method="POST">@csrf
                            <div class="mb-3">
                                <label class="text-white fw-500 mb-2">Email Address</label>
                                <input type="text" name="email" class="form-control rounded-0">
                                <span class="text-danger error-text email_error"></span>
                            </div>
                            <div class="mb-3">
                                <label class="text-white fw-500 mb-2">Password</label>
                                <input type="password" name="password" class="form-control rounded-0">
                                <span class="text-danger error-text password_error"></span>
                            </div>
                            <div class="text-end mb-3">
                                <a href="{{url('reset-password')}}" class="text-primary fw-semibold fst-italic">Forgot
                                    Password?</a>
                            </div>

                            <div class="full-button">
                                <div class="iq-button">
                                    <button type="submit" id="login_submit" class="btn text-uppercase position-relative">
                                        <span class="button-text">log in</span>
                                        <i class="fa-solid fa-play"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <p class="my-4 text-center fw-500 text-white">New to Streamit? <a href="{{url('/register')}}"
                                class="text-primary ms-1">Register</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

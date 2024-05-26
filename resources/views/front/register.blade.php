@extends('layouts.front.layout')
@section('content')
    <div class="vh-100"
        style="background: url('assets/user/images/pages/01.webp'); background-size: cover; background-repeat: no-repeat; position: relative;min-height:800px">
        <div class="container">
            <div class="row justify-content-center align-items-center height-self-center vh-100">
                <div class="col-lg-8 col-md-12 align-self-center">
                    <div class="user-login-card bg-body">
                        <h4 class="text-center mb-5">Create Your Account</h4>
                        <div class="error-text text-center text-danger mb-4" id="register_error"></div>
                        <form id="register_form" method="POST" action="{{ url('register') }}">@csrf
                            <div class="row row-cols-1 row-cols-lg-2 g-2 g-lg-5 mb-5">
                                <div class="col">
                                    <label class="text-white fw-500 mb-2">Name</label>
                                    <input type="text" name="name" class="form-control rounded-0">
                                    <span class="text-danger error-text name_error"></span>
                                </div>

                                <div class="col">
                                    <label class="text-white fw-500 mb-2">Email *</label>
                                    <input type="email" name="email" class="form-control rounded-0">
                                    <span class="text-danger error-text email_error"></span>
                                </div>
                                <div class="col">
                                    <label class="text-white fw-500 mb-2">Mobile</label>
                                    <input type="text" name="mobile" class="form-control rounded-0">
                                    <span class="text-danger error-text mobile_error"></span>
                                </div>
                                <div class="col">
                                    <label class="text-white fw-500 mb-2">Gender</label>
                                    <select class="form-control rounded-0" name="gender">
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <span class="text-danger error-text gender_error"></span>
                                </div>
                                <div class="col">
                                    <label class="text-white fw-500 mb-2">Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control rounded-0">
                                    <span class="text-danger error-text date_of_birth_error"></span>
                                </div>
                                <div class="col">
                                    <label class="text-white fw-500 mb-2">Password *</label>
                                    <input type="password" name="password" class="form-control rounded-0">
                                    <span class="text-danger error-text password_error"></span>
                                </div>
                                <div class="col">
                                    <label class="text-white fw-500 mb-2">Confirm Password *</label>
                                    <input type="password" name="confirm_password" class="form-control rounded-0">
                                    <span class="text-danger error-text confirm_password_error"></span>
                                </div>
                            </div>

                            <div class="row text-center">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-6">
                                    <div class="full-button">
                                        <div class="iq-button">
                                            <button type="submit" id="register_button"
                                                class="btn text-uppercase position-relative">
                                                <span class="button-text">Sign Up</span>
                                                <i class="fa-solid fa-play"></i>
                                            </button>
                                        </div>

                                        <p class="mt-2 mb-0 fw-normal">Already have an account?<a href="{{ url('/login') }}"
                                                class="ms-1">Login</a></p>
                                    </div>
                                </div>
                                <div class="col-lg-3"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

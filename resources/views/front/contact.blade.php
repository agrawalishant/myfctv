@extends('layouts.front.layout')
@section('content')
    {!! NoCaptcha::renderJs() !!}
    <div class="iq-breadcrumb" style="background-image: url(assets/user/images/pages/01.webp);">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <nav aria-label="breadcrumb" class="text-center">
                        <h2 class="title">Contact Us</h2>
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active">Contact Us</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div> <!--bread-crumb-->


    <div class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="title-box">
                        <h2>Create With Us</h2>
                        <p class="mb-0">To learn more about how Streamit can help you, contact us.</p>
                    </div>
                    <div class="error-text text-center text-danger mb-4" id="contact_error"></div>
                    <form class="mb-5 mb-lg-0" id="contact_form" method="POST" action="{{ url('/contact-us') }}">@csrf
                        <div class="row">
                            <div class="col-md-6 mb-4 mb-lg-5">
                                <input type="text" name="first_name" class="form-control font-size-14"
                                    placeholder="Your Name*">
                                <span class="text-danger error-text first_name_error"></span>
                            </div>
                            <div class="col-md-6 mb-4 mb-lg-5">
                                <input type="text" name="last_name" class="form-control font-size-14"
                                    placeholder="Last Name*">
                                <span class="text-danger error-text last_name_error"></span>
                            </div>
                            <div class="col-md-6 mb-4 mb-lg-5">
                                <input type="tel" name="phone" class="form-control font-size-14" maxlength="10"
                                    minlength="10" placeholder="Phone Number*">
                                <span class="text-danger error-text phone_error"></span>
                            </div>
                            <div class="col-md-6 mb-4 mb-lg-5">
                                <input type="email" name="email" class="form-control font-size-14"
                                    placeholder="Your Email*">
                                <span class="text-danger error-text email_error"></span>
                            </div>
                            <div class="col-md-12 mb-4 mb-lg-5">
                                <textarea class="form-control font-size-14" name="message" cols="40" rows="10" placeholder="Your Message"></textarea>
                                <span class="text-danger error-text message_error"></span>
                            </div>
                            <div class="col-md-12 mb-4 mb-lg-5">
                                {!! app('captcha')->display() !!}
                                <span class="text-danger error-text g-recaptcha-response_error"></span>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="iq-button">
                                    <button type="submit" id="contact_submit" class="btn">Send Message</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-1 d-none d-lg-block"></div>
                <div class="col-lg-3">
                    <div class="border-bottom pb-4 mb-4">
                        <h5>Come See Us</h5>
                        <span>Bhopal, MP, India</span>
                    </div>
                    <div class="border-bottom pb-4 mb-4">
                        <h5>Get In Touch</h5>
                        <a class="text-primary">chandrakantwk@gmail.com</a>
                        <p class="mb-0">+91 74151 14148</p>
                    </div>
                    <div>
                        <h5>Follow Us</h5>
                        <ul class="p-0 m-0 mt-4 list-unstyled widget_social_media">
                            <li class="">
                                <a href="https://www.facebook.com/" class="position-relative">
                                    <i class="fab fa-facebook"></i>
                                </a>
                            </li>
                            <li class="">
                                <a href="https://twitter.com/" class="position-relative">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>
                            <li class="">
                                <a href="https://github.com/" class="position-relative">
                                    <i class="fab fa-github"></i>
                                </a>
                            </li>
                            <li class="">
                                <a href="https://www.instagram.com/" class="position-relative">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="map">
        <div class="container-fluid p-0">
            <iframe loading="lazy" class="w-100"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d29236.273365613728!2d77.35375529864069!3d23.656847998555374!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x397b87d90a14535d%3A0x7c0bfb20e87bb28e!2sHabibganj%2C%20Madhya%20Pradesh%20463106!5e0!3m2!1sen!2sin!4v1704551513485!5m2!1sen!2sin"
                height="400" allowfullscreen=""></iframe>
        </div>
    </div>
@endsection

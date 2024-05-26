<footer class="footer footer-default">
    <div class="container-fluid">
        {{-- <div class="footer-top">
            <div class="row">
                <div class="col-xl-3 col-lg-6 mb-5 mb-lg-0">
                    <div class="footer-logo">
                        <!--Logo -->
                        <div class="logo-default">
                            <a class="navbar-brand text-primary" href="#">
                                <img class="img-fluid logo" src="{{asset('assets/user/images/funsfuel logo.png')}}" loading="lazy"
                                    alt="funsfuel" />
                            </a>
                        </div>
                        <div class="logo-hotstar">
                            <a class="navbar-brand text-primary" href="#">
                                <img class="img-fluid logo" src="{{asset('assets/user/images/logo-hotstar.webp')}}" loading="lazy"
                                    alt="funsfuel" />
                            </a>
                        </div>
                        <div class="logo-prime">
                            <a class="navbar-brand text-primary" href="#">
                                <img class="img-fluid logo" src="{{asset('assets/user/images/logo-prime.webp')}}" loading="lazy"
                                    alt="funsfuel" />
                            </a>
                        </div>
                        <div class="logo-hulu">
                            <a class="navbar-brand text-primary" href="#">
                                <img class="img-fluid logo" src="{{asset('assets/user/images/logo-hulu.webp')}}" loading="lazy"
                                    alt="funsfuel" />
                            </a>
                        </div>
                    </div>
                    <p class="mb-4 font-size-14">Email us: <span class="text-white">chandrakantwk@gmail.com</span>
                    </p>
                    <p class="text-uppercase letter-spacing-1 font-size-14 mb-1">customer services</p>
                    <p class="mb-0 contact text-white">+ (480) 555-0103</p>
                </div>
                <div class="col-xl-2 col-lg-6 mb-5 mb-lg-0">
                    <h4 class="footer-link-title">Quick Links</h4>
                    <ul class="list-unstyled footer-menu">
                        <li class="mb-3">
                            <a href="#" class="ms-3">about us</a>
                        </li>                       
                        <li class="mb-3">
                            <a href="#" class="ms-3">Pricing Plan</a>
                        </li>
                        <li>
                            <a href="#" class="ms-3">FAQ</a>
                        </li>
                    </ul>
                </div>
                <div class="col-xl-2 col-lg-6 mb-5 mb-lg-0">
                    <h4 class="footer-link-title">Movies to watch</h4>
                    <ul class="list-unstyled footer-menu">
                        <li class="mb-3">
                            <a href="#" class="ms-3">Top trending</a>
                        </li>
                        <li class="mb-3">
                            <a href="#" class="ms-3">Recommended</a>
                        </li>
                        <li>
                            <a href="#" class="ms-3">Popular</a>
                        </li>
                    </ul>
                </div>
                <div class="col-xl-2 col-lg-6 mb-5 mb-lg-0">
                    <h4 class="footer-link-title">About company</h4>
                    <ul class="list-unstyled footer-menu">
                        <li class="mb-3">
                            <a href="{{url('/contact-us')}}" class="ms-3">contact us</a>
                        </li>
                        <li class="mb-3">
                            <a href="#" class="ms-3">privacy policy</a>
                        </li>
                        <li>
                            <a href="#" class="ms-3">Terms of use</a>
                        </li>
                    </ul>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <h4 class="footer-link-title">Subscribe Newsletter</h4>
                    <div class="mailchimp mailchimp-dark">
                        <div class="input-group mb-3 mt-4">
                            <input type="text" class="form-control mb-0 font-size-14" placeholder="Email*"
                                aria-describedby="button-addon2">
                            <div class="iq-button">
                                <button type="submit" class="btn btn-sm" id="button-addon2">Subscribe</button>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-5">
                        <span class="font-size-14 me-2">Follow Us:</span>
                        <ul class="p-0 m-0 list-unstyled widget_social_media">
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
        </div> --}}
        <div class="footer-bottom border-top">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <ul class="menu list-inline p-0 d-flex flex-wrap align-items-center">
                        <li class="menu-item">
                            <a href="{{url('/terms-of-use')}}"> Terms Of Use </a>
                        </li>
                        <li id="menu-item-7316" class="menu-item">
                            <a href="{{url('/privacy-policy')}}"> Privacy-Policy </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{url('/refund-policy')}}"> Refund Policy </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{url('/grievance')}}"> Grievance </a>
                        </li>
                    </ul>
                    <p class="font-size-14">© <span class="currentYear"></span> <span
                            class="text-primary">Funsfuel</span>. All Rights Reserved. All videos and shows on
                        this platform are trademarks of, and all related images and content are the property of,
                        Funsfuel Inc. Duplication and copy of this is strictly prohibited. All rights reserved. </p>
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-3">
                    <h6 class="font-size-14 pb-1">Download Funsfuel Apps </h6>
                    <div class="d-flex align-items-center">
                        <a class="app-image" href="#">
                            <img src="{{asset('assets/user/images/footer/google-play.webp')}}" loading="lazy" alt="play-store" />
                        </a>
                        <br />
                        {{-- <a class="ms-3 app-image" href="#">
                            <img src="{{asset('assets/user/images/footer/apple.webp')}}" loading="lazy" alt="app-store" />
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

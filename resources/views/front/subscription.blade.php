@extends('layouts.front.layout')
@section('content')
    <div class="iq-breadcrumb" style="background-image: url(assets/user/images/pages/01.webp);">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <nav aria-label="breadcrumb" class="text-center">
                        <h2 class="title">Subscription Plan</h2>
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active">Subscription Plan</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div> <!--bread-crumb-->

    <div class="section-padding service-details">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 mb-3 text-center">
                        @foreach ($subscription as $subscriptionItem)
                            <div class="col">
                                <div class="card mb-4">
                                    <div class="card-header bg-primary">
                                        <h5 class="card-title pricing-card-title mb-3 mt-5">{{ $subscriptionItem->name }}
                                        </h5>
                                        <h2 class="mb-3"><b>{{ $subscriptionItem->price }}</b></h2>
                                        <h4 class="card-title pricing-card-title mb-3">
                                            {{ $subscriptionItem->duration_quantity }}
                                            {{ $subscriptionItem->duration_unit }}</h4>
                                        <a type="button" href="#" class="btn btn-dark mb-5 subscribe-btn"
                                            data-plan-id="{{ $subscriptionItem->id }}">Get started</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        var razorpayKey = 'rzp_test_TEtyhzqPGc8CK8';

        $(document).ready(function() {
            $('.subscribe-btn').on('click', function(e) {
                e.preventDefault();

                var planId = $(this).data('plan-id');
                var subscribeButton = $(this);

                // Show loading spinner
                subscribeButton.html('<i class="fa fa-spinner fa-spin"></i> Processing...');

                // Make an AJAX request to the server to create a Razorpay order
                $.ajax({
                    url: '/subscribe',
                    type: 'POST',
                    data: {
                        plan_id: planId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Razorpay Order Response:', response);

                        // Check if 'order' property exists and is not null or undefined
                        if (response.order_id) {
                            var options = {
                                key: razorpayKey,
                                amount: parseFloat(response.amount),
                                currency: response.currency,
                                name: 'Your Company Name',
                                description: 'Subscription Payment',
                                order_id: response.order_id,
                                handler: function(response) {
                                    // Handle the success response after the payment is successful
                                    console.log(response);

                                    // Make another AJAX request to complete the subscription process on your server
                                    $.ajax({
                                        url: '/handle-razorpay-payment',
                                        type: 'POST',
                                        data: {
                                            order_id: response
                                                .razorpay_order_id,
                                            payment_id: response
                                                .razorpay_payment_id,
                                            plan_id: planId,
                                            _token: '{{ csrf_token() }}'
                                        },
                                        success: function(
                                        subscriptionResponse) {
                                            console.log(
                                                subscriptionResponse);

                                            // Show a success message with SweetAlert
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Subscription Successful',
                                                text: 'You have successfully subscribed to the plan.',
                                            });

                                            // Reset the button content to its normal state
                                            subscribeButton.html(
                                                'Subscribe');
                                        },
                                        error: function(subscriptionError) {
                                            console.error(
                                            subscriptionError);

                                            // Show an error message with SweetAlert
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Subscription Error',
                                                text: subscriptionError
                                                    .responseJSON
                                                    .error ||
                                                    'An error occurred during subscription. Please try again later.',
                                            });

                                            // Reset the button content to its normal state
                                            subscribeButton.html(
                                                'Subscribe');
                                        }
                                    });
                                }
                            };

                            var rzp = new Razorpay(options);
                            rzp.open();
                        } else {
                            console.error('Invalid response format:', response);
                            // Handle the case where the response format is unexpected
                            // You might want to show an error message to the user or redirect them to an error page

                            // Reset the button content to its normal state
                            subscribeButton.html('Subscribe');
                        }
                    },
                    error: function(error) {
                        console.error(error);

                        // Show an error message with SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.responseJSON.error ||
                                'An error occurred. Please try again later.',
                        });

                        // Reset the button content to its normal state
                        subscribeButton.html('Subscribe');
                    }
                });
            });
        });
    </script>
@endsection

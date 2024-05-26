@extends('layouts.front.layout')
@section('content')
<div class="iq-breadcrumb" style="background-image: url(assets/user/images/pages/01.webp);">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <nav aria-label="breadcrumb" class="text-center">
                    <h2 class="title">Refund Policy</h2>
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active">Refund Policy</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="section-padding">
    <div class="container">
        <div class="title-box">
            <h4 class="mb-4">Refund Policy</h4>
            <p>Our refund policy applies to the Funfuel Entertainment web site/app platform including without limitation www.atpatifilms.com other related sites/or app/s, mobile applications and other online features each “Site/s or App/s”. We have provided detailed information for you to view the packages before choosing to subscribe to us. If you have any queries or reservations, please contact us at funsfuel@gmail.com before subscribing to our services. As we, being a service provider for content available through website or APP where you expect to watch packages of your choice after paying for subscription, unfortunately all fees of Funfuel Entertainment for such services are non-refundable. Due to any technical glitch at the time of online transaction, the transaction does not go through, by default the amount in process of transfer is automatically credited back to your bank account through the payment gateway.</p>
        </div>
    </div>
</div>

@endsection
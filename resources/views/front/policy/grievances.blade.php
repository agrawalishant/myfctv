@extends('layouts.front.layout')
@section('content')
<div class="iq-breadcrumb" style="background-image: url(assets/user/images/pages/01.webp);">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <nav aria-label="breadcrumb" class="text-center">
                    <h2 class="title">Grievance</h2>
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active">Grievance</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="section-padding">
    <div class="container">
        <div class="title-box">
            <h4 class="mb-4">Grievances Redressed</h4>
            <p>If you have any complaints regarding any content, title, age rating, synopsis, parental control feature, etc. available in our app or Funfuel Entertainment website, you can file a complaint with our Grievance Redressed Officer using the details below:</p>
            <p><strong>Grievance Redressed Officer:</strong></p>
            <p>Email ID: funsfuel@gmail.com</p>
            <p>A complaint can be lodged by contacting our Complaints Officer by sending an email to funsfuel@gmail.com. After receiving the complaint, it will be confirmed within 24 hours. Our Grievance Redressed Team, headed by our Grievance Redressed Officer, will resolve the complaint within 15 days from the date of receipt.</p>
        </div>
    </div>
</div>

@endsection
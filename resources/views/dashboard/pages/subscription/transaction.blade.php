@extends('layouts.dashboard.app')
@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="custom-table-effect table-responsive  border rounded py-4">
                        <table class="table mb-0" id="datatable" data-toggle="data-table">
                            <thead>
                                <tr class="bg-white">
                                    <th scope="col">Sr.No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email ID</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Payment Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userPayments as $item)
                                    <tr>
                                        <td class="">{{ $loop->index + 1 }}</td>
                                        <td class="">{{ $item->user->name }}</td>
                                        <td class="">{{ $item->user->email }}</td>
                                        <td class="">{{ $item->amount }}</td>
                                        <td>
                                            @if ($item->payment_status === 'success')
                                                <span class="badge bg-soft-success p-2 text-success">Success</span>
                                            @else
                                                <span class="badge bg-soft-primary p-2 text-primary">Failed</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-evenly">
                                                <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal"
                                                    data-bs-target="#transactionModal{{ $item->id }}">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($userPayments as $item)
    <div class="modal fade" id="transactionModal{{ $item->id }}" tabindex="-1"
        role="dialog" aria-labelledby="transactionModal{{ $item->id }}Title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionModal{{ $item->id }}Title">
                        Payment Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive border rounded py-4">
                        <table>
                            <tbody>
                                <tr>
                                    <td>Name</td>
                                    <td>{{ $item->user->name }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{ $item->user->email }}</td>
                                </tr>
                                <tr>
                                    <td>Mobile</td>
                                    <td>{{ $item->user->mobile }}</td>
                                </tr>
                                <tr>
                                    <td>Amount</td>
                                    <td>{{ $item->amount }}</td>
                                </tr>
                                <tr>
                                    <td>Currency</td>
                                    <td>{{ $item->currency }}</td>
                                </tr>
                                <tr>
                                    <td>Payment Method</td>
                                    <td>{{ $item->payment_method }}</td>
                                </tr>
                                <tr>
                                    <td>Payment Status</td>
                                    <td>{{ $item->payment_status }}</td>
                                </tr>
                                <tr>
                                    <td>Payment Id</td>
                                    <td>{{ $item->payment_id }}</td>
                                </tr>
                                <tr>
                                    <td>Payment Date</td>
                                    <td><?= date('j M Y', strtotime($item['created_at'])) ?></td>
                                </tr>
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endsection

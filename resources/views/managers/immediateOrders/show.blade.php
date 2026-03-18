@extends('managers.layout.app')
@section('title', 'Show Immediate Order')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ route('manager.immediateOrder.index') }}">Back</a>
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <h4 class="text-center my-4">View Immediate Order</h4>
                            <div class="row mx-0 px-4">
                                <!-- Vendor's Order Receipt -->
                                <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                    <div class="form-group mb-2">
                                        <label>Vendor's Order Receipt</label>
                                        <input type="file" name="image" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                    <div class="form-group mb-2">
                                        <label>Vendor Name</label>
                                        <input type="text" id="vendor_name" name="vendor_name" class="form-control"
                                            placeholder="Enter Vendor Name" value="{{ $imidateOrder->vendor_name }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row mx-0 px-4">
                                <!-- Left Side: Total Cases and Trip Cases -->
                                <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                    <div class="form-group mb-2">
                                        <label for="total_cases">Total Cases</label>
                                        <input type="number" name="total_cases" id="total_cases" class="form-control"
                                            value="{{ $imidateOrder->total_cases }}" disabled>
                                    </div>

                                    <!-- Dynamic Trip Cases -->
                                    @for ($i = 1; $i <= 10; $i++)
                                        <div class="form-group mb-2">
                                            <label for="trip_{{ $i }}">Trip {{ $i }} Cases</label>
                                            <input type="number" name="trip_cases_{{ $i }}"
                                                id="trip_{{ $i }}" class="form-control"
                                                value="{{ $imidateOrder->{'trip_cases_'.$i} }}" disabled>
                                        </div>
                                    @endfor
                                </div>

                                <!-- Right Side: Total Received and Remaining Cases -->
                                <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                    <div class="form-group mb-2">
                                        <label>Total Received Cases</label>
                                        <input type="number" id="received_cases" class="form-control"
                                            value="{{ $imidateOrder->received_cases }}" disabled>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label>Remaining Cases</label>
                                        <input type="number" id="remaining_cases" class="form-control"
                                            value="{{ $imidateOrder->remaining_cases }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Form Elements -->
                            <div class="row mx-0 px-4">
                                <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                    <div class="form-group mb-2">
                                        <label>Checked By</label>
                                        <input type="text" id="checked_by" name="checked_by" class="form-control"
                                            placeholder="Enter Your Name" value="{{ $imidateOrder->checked_by }}" disabled>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label for="invoice_amount">Invoice Amount</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number" id="invoice_amount" name="invoice_amount"
                                                class="form-control" value="{{ $imidateOrder->invoice_amount }}" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                    <div class="form-group mb-2">
                                        <label>Payment Method</label>
                                        <select id="payment_method" class="form-control" name="payment_method" disabled>
                                            <option value="" disabled>Select Payment Method</option>
                                            <option value="cash" {{ $imidateOrder->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="Cheque" {{ $imidateOrder->payment_method == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                            <option value="not_paid" {{ $imidateOrder->payment_method == 'not_paid' ? 'selected' : '' }}>Not Paid</option>
                                            <option value="auto_withdraw" {{ $imidateOrder->payment_method == 'auto_withdraw' ? 'selected' : '' }}>Auto Withdraw</option>
                                        </select>
                                    </div>

                                    <!-- Cheque Number Input -->
                                    <div class="form-group mb-2 conditional-input" id="check_number_container" style="display: {{ $imidateOrder->payment_method == 'Cheque' ? 'block' : 'none' }};">
                                        <label>Cheque Number</label>
                                        <input type="text" id="check_number" class="form-control" name="check_number"
                                            value="{{ $imidateOrder->check_number }}" disabled>
                                    </div>
                                </div>

                                <!-- Short Cases and Reason -->
                                <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                    <div class="form-group mb-2">
                                        <label>Short Cases</label>
                                        <input type="checkbox" id="short_cases_checkbox" {{ $imidateOrder->short_cases_status == 1 ? 'checked' : '' }} disabled>
                                        <input type="hidden" id="short_cases_status" name="short_cases_status" value="{{ $imidateOrder->short_cases_status }}">
                                    </div>

                                    <div class="form-group mb-0" id="short_cases_reason" style="display: {{ $imidateOrder->short_cases_status == 1 ? 'block' : 'none' }};">
                                        <label for="reason">Reason for Short Cases</label>
                                        <select name="short_case_reason" id="reason" class="form-control" disabled>
                                            <option value="" disabled>Select Reason</option>
                                            @foreach ($reasons as $reason)
                                                <option value="{{ $reason->id }}" {{ $imidateOrder->short_case_reason == $reason->id ? 'selected' : '' }}>{{ $reason->reason }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="card-footer text-center">
                                <p class="mt-3" style="font-size: 16px;"><b>Note:</b> Please confirm with the driver
                                    and double-check the order before marking it as complete.</p>                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

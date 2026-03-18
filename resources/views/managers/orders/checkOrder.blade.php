@extends('managers.layout.app')
@section('title', 'Dashboard')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                <form id="sign_all_checked_form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Sign All Checked for Order #{{ $order->order_code }}</h4>

                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Vendor's Order Receipt</label>
                                            <input type="file" name="image" class="form-control">
                                            @error('image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Manager's Order Receipt</label>
                                            <input type="file" name="manager_recepit" class="form-control">
                                            @error('manager_recepit')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mx-0 px-4">
                                    <!-- Left Side: Total Cases and Trip Cases -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="total_cases">Total Cases<span class="text-danger">*</span></label>
                                            <input type="number" name="total_cases" id="total_cases" class="form-control"
                                                value="{{ old('total_cases', $checkOrder->total_cases ?? null) }}">
                                            @error('total_cases')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        @for ($i = 1; $i <= 10; $i++)
                                            <div class="form-group mb-2">
                                                <label for="trip_{{ $i }}">Trip {{ $i }} Cases</label>
                                                <input type="number" name="trip_cases_{{ $i }}"
                                                    id="trip_{{ $i }}" class="form-control"
                                                    value="{{ old('trip_cases_' . $i, $checkOrder->{'trip_cases_' . $i} ?? null) }}">
                                                @error('trip_cases_{{ $i }}')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endfor
                                    </div>

                                    <style>
                                        .flex-container {
                                            display: flex;
                                            flex-direction: column;
                                            /* Stack elements vertically */
                                            height: 100%;
                                            /* Allow the container to stretch */
                                        }

                                        .input-full-height {
                                            flex: 1;
                                            /* Make input grow to fill the available space */
                                            min-height: 100px;
                                            /* Set a minimum height */
                                            font-size: 1rem;
                                            padding: 15px;
                                            text-align: center;
                                        }
                                    </style>


                                    <!-- Right Side: Total Received and Remaining Cases -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-8 flex-container">
                                        <div class="form-group mb-2">
                                            <label>Total Received Cases</label>
                                            <input type="number" id="received_cases" class="form-control input-full-height"
                                                readonly>
                                            @error('received_cases')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-2">
                                            <label>Remaining Cases</label>
                                            <input type="number" id="remaining_cases"
                                                class="form-control input-full-height" readonly>
                                            @error('remaining_cases')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>


                                    </div>
                                </div>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <!-- Checked By Input -->
                                        <div class="form-group mb-2">
                                            <label>Checked By<span class="text-danger">*</span></label>
                                            <input type="text" id="checked_by" name="checked_by" class="form-control"
                                                value="{{ old('checked_by', $checkOrder->checked_by ?? null) }}"
                                                placeholder="Enter Your Name">
                                            @error('checked_by')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="invoice_amount">Invoice Amount<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" id="invoice_amount" name="invoice_amount"
                                                    class="form-control" placeholder="Enter Invoice Amount"
                                                    value="{{ old('invoice_amount', $checkOrder->invoice_amount ?? '') }}"
                                                    step="0.01">
                                            </div>
                                            @error('invoice_amount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Payment Method<span class="text-danger">*</span></label>
                                            <select id="payment_method" class="form-control" name="payment_method"
                                                onchange="toggleCheckNumber()">
                                                <option value="" disabled
                                                    {{ old('payment_method', $checkOrder->payment_method ?? '') ? '' : 'selected' }}>
                                                    Select Payment Method</option>
                                                <option value="cash"
                                                    {{ old('payment_method', $checkOrder->payment_method ?? '') == 'cash' ? 'selected' : '' }}>
                                                    Cash</option>
                                                <option value="Cheque"
                                                    {{ old('payment_method', $checkOrder->payment_method ?? '') == 'check' ? 'selected' : '' }}>
                                                    Cheque</option>
                                                <option value="not_paid"
                                                    {{ old('payment_method', $checkOrder->payment_method ?? '') == 'not_paid' ? 'selected' : '' }}>
                                                    Not Paid</option>
                                                <option value="auto_withdraw"
                                                    {{ old('payment_method', $checkOrder->payment_method ?? '') == 'auto_withdraw' ? 'selected' : '' }}>
                                                    Auto Withdraw</option>
                                            </select>
                                            @error('payment_method')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-2 conditional-input" id="check_number_container"
                                            style="display: {{ old('payment_method', $checkOrder->payment_method ?? '') == 'check' ? 'block' : 'none' }}">
                                            <label>Cheque Number<span class="text-danger">*</span></label>
                                            <input type="text" id="check_number" class="form-control" name="check_number"
                                                placeholder="Enter Check Number"
                                                value="{{ old('check_number', $checkOrder->check_number ?? '') }}">
                                            @error('Cheque')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group ">
                                            <label>Short Cases</label>
                                            <!-- Checkbox to trigger dropdown visibility -->
                                            <input type="checkbox" id="short_cases_checkbox"
                                                {{ old('short_cases_status', isset($checkOrder) && $checkOrder->short_cases_status == 1 ? 'checked' : '') }}>
                                            <!-- Hidden input to store checkbox status -->
                                            <input type="hidden" id="short_cases_status" name="short_cases_status"
                                                value="{{ old('short_cases_status', isset($checkOrder) ? $checkOrder->short_cases_status : '') }}">
                                        </div>
                                        <div class="col-sm-6 mt-3">
                                            <div class="row" id="short_cases_reason"
                                                style="{{ old('short_cases_status', isset($checkOrder) && $checkOrder->short_cases_status == 1 ? 'display: block;' : 'display: none;') }}">

                                                <div class="form-group mb-0">
                                                    <label for="reason">Reason for Short Cases</label>
                                                    <select name="short_case_reason" id="reason" class="form-control">
                                                        <option value="" disabled selected>Select Reason</option>
                                                        @foreach ($reasons as $reason)
                                                            <option value="{{ $reason->id }}"
                                                                {{ old('short_case_reason', $checkOrder->short_case_reason ?? '') == $reason->id ? 'selected' : '' }}>
                                                                {{ $reason->reason }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                </div>

                                <div class="card-footer text-center row">
                                    <div class="col">
                                        <button type="submit" class="btn btn-success mr-1 btn-bg"
                                            id="submit">Complete Order</button>
                                        <p class="mt-3 mb-0" style="font-size: 16px">(<b>Note:</b> Please confirm with
                                            the driver and double-check the order before marking it as complete)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>



@endsection

@section('js')
    @if (Session::has('message'))
        <script>
            toastr.success('{{ Session::get('message') }}');
        </script>
    @endif




@section('js')
    @if (Session::has('message'))
        <script>
            toastr.success('{{ Session::get('message') }}');
        </script>
    @endif

    <script>
        $(document).ready(function() {
            let errorShown = false; // Flag to track if error message has been shown
            let totalTripCases = 0; // Declare this outside the function

            // Calculate Remaining Cases Logic
            function calculateRemainingCases() {
                let totalCases = parseInt($('#total_cases').val()) || 0;
                totalTripCases = 0; // Reset before calculating

                $('[id^=trip_]').each(function() {
                    let tripCases = parseInt($(this).val()) || 0;
                    totalTripCases += tripCases;
                });

                let remainingCases = totalCases - totalTripCases; // Calculate remaining cases

                // Show error if totalTripCases exceeds totalCases
                if (totalTripCases > totalCases) {
                    if (!errorShown) {
                        toastr.error('Total trip cases cannot exceed the total cases.');
                        errorShown = true;
                    }
                    remainingCases = -Math.abs(remainingCases); // Set remaining cases to negative
                    $('#remaining_cases').val(remainingCases);
                    $('#received_cases').val(totalTripCases); // Retain current value for received cases
                } else {
                    errorShown = false; // Reset the flag if no error
                    $('#remaining_cases').val(remainingCases);
                    $('#received_cases').val(totalTripCases);
                }

                // Call function to check if the save order button should be enabled/disabled
                toggleSaveOrderButton(remainingCases);
            }

            // Function to toggle the "Save Order" button
            const toggleSaveOrderButton = (remainingCases) => {
                const saveOrderButton = $('#submit'); // Adjust selector based on your button's ID or class
                const shortCasesCheckbox = $('#short_cases_checkbox');

                // Enable button if remaining cases are 0 or if short cases checkbox is checked
                if (remainingCases === 0 || (shortCasesCheckbox.is(':checked') && totalTripCases <= parseInt($(
                        '#total_cases').val()))) {
                    saveOrderButton.prop('disabled', false); // Enable the button
                } else {
                    saveOrderButton.prop('disabled', true); // Disable the button
                }
            };

            // Function to disable all input fields
            const disableAllInputFields = () => {
                $('#total_cases, [id^=trip_]').prop('disabled', true);
                $('#payment_method').prop('disabled', true);
                $('#check_number_container').prop('disabled', true);
                $('#reason').prop('disabled', true);
                $('#short_cases_checkbox').prop('disabled', true);
                $('#submit').prop('disabled', true);
                $('#checked_by').prop('disabled', true);
                $('#invoice_amount').prop('disabled', true);
            };

            // Event listener for changes in total cases or trip cases
            $('#total_cases').on('input', calculateRemainingCases);
            $('[id^=trip_]').on('input', calculateRemainingCases);

            // Short Cases Logic
            const shortCasesCheckbox = $('#short_cases_checkbox');
            const shortCasesReason = $('#short_cases_reason');
            const shortCasesStatus = $('#short_cases_status');

            const toggleShortCasesReason = () => {
                if (shortCasesCheckbox.is(':checked')) {
                    shortCasesReason.show(); // Show the div when checkbox is checked
                    shortCasesStatus.val(1); // Set hidden input value to 1
                } else {
                    shortCasesReason.hide(); // Hide the div when checkbox is unchecked
                    shortCasesStatus.val(0); // Set hidden input value to 0
                }
                calculateRemainingCases(); // Recalculate remaining cases after toggling
            };

            // Attach event listener to the checkbox
            shortCasesCheckbox.change(toggleShortCasesReason);

            // Call the function on page load to ensure correct initial state
            toggleShortCasesReason();

            // Initial calculation to set correct state
            calculateRemainingCases();

            // Check if the form is initially filled
            if ($('#total_cases').val() || $('[id^=trip_]').filter(function() {
                    return $(this).val();
                }).length > 0) {
                disableAllInputFields(); // Disable all input fields if they are filled
            }

            // Payment method toggle for check number
            const toggleCheckNumber = () => {
                var paymentMethod = $("#payment_method").val();
                var checkNumberContainer = $("#check_number_container");

                if (paymentMethod === "Cheque") {
                    checkNumberContainer.show(); // Show the check number input
                } else {
                    checkNumberContainer.hide(); // Hide the check number input
                    // Clear the input if payment method is not "Cheque"
                    $("#check_number").val(''); // Clear the input
                }
            };

            // Call the function on page load to set the initial state
            toggleCheckNumber();
            $("#payment_method").change(toggleCheckNumber);

            // Form submission for signing all checked orders
            $('#sign_all_checked_form').on('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);

                // Collect trip cases as an array
                const tripCasesArray = [];
                $('[id^=trip_]').each(function() {
                    tripCasesArray.push(parseInt($(this).val()) || 0);
                });
                formData.append('trip_cases', JSON.stringify(
                tripCasesArray)); // Send trip cases as an array

                // Send AJAX request
                // $.ajax({
                //     url: "{{ route('manager.completeSignAllChecked', $order->id) }}", // The endpoint URL
                //     type: 'POST',
                //     data: formData,
                //     processData: false, // Important: prevent jQuery from processing the data
                //     contentType: false, // Important: set to false to prevent jQuery from overriding the `Content-Type`
                //     beforeSend: function() {
                //         // Optional: Display a loader or disable button while submitting
                //         $('#submit').prop('disabled', true);
                //     },
                //     success: function(response) {
                //         toastr.success('Order Signed Checked!', '', {
                //             onHidden: function() {
                //                 window.location.href = "{{ route('manager.storeManagerOrder.index') }}";
                //             }
                //         });
                //     },
                //     error: function(response,xhr) {
                //         // Handle error response
                //         if (response.status === 422) {
                //             // Validation errors
                //             var errors = response.responseJSON.errors;
                //             $.each(errors, function(key, value) {
                //                 toastr.error(value); // Display each error
                //             });
                //         } else {
                //             toastr.error(xhr);
                //         }
                //     },
                //     complete: function() {
                //         // Optional: Re-enable the submit button
                //         $('#submit').prop('disabled', false);
                //     }
                // });
                $.ajax({
                    url: "{{ route('manager.completeSignAllChecked', $order->id) }}", // The endpoint URL
                    type: 'POST',
                    data: formData,
                    processData: false, // Prevent jQuery from processing the data
                    contentType: false, // Prevent jQuery from overriding the Content-Type
                    beforeSend: function() {
                        // Display a loader or disable the submit button
                        $('#submit').prop('disabled', true);
                    },
                    success: function(response) {
                        // Show success message and redirect on hidden
                        toastr.success('Order Signed Checked!', '', {
                            onHidden: function() {
                                window.location.href =
                                    "{{ route('manager.storeManagerOrder.index') }}";
                            }
                        });
                    },
                    error: function(response) {
                        if (response.status === 422) {
                            // Handle validation errors (422)
                            var errors = response.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]); // Display each validation error
                            });
                        } else if (response.status === 500) {
                            // Handle server errors (500)
                            if (response.responseJSON && response.responseJSON.error) {
                                toastr.error(response.responseJSON
                                .error); // Show the backend error message
                            } else {
                                toastr.error('An internal server error occurred.');
                            }
                        } else {
                            // Handle any other errors (like 404, etc.)
                            if (response.responseJSON && response.responseJSON.message) {
                                toastr.error(response.responseJSON
                                .message); // Show backend error message if available
                            } else {
                                toastr.error('An unexpected error occurred. Please try again.');
                            }
                        }
                    },
                    complete: function() {
                        // Re-enable the submit button after request completion
                        $('#submit').prop('disabled', false);
                    }
                });

            });
        });
    </script>
@endsection





@endsection

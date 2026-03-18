@extends('managers.layout.app')
@section('title', 'Dashboard')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                <form id="sign_all_checked_form" action="{{ route('manager.immediateAllChecked') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Create Immediate Orders</h4>
                                <div class="row mx-0 px-4">
                                    <!-- Vendor's Order Receipt -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Vendor's Order Receipt<span class="text-danger">*</span></label>
                                            <input type="file" name="image" class="form-control">
                                            @error('image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Vendor Name<span class="text-danger">*</span></label>
                                            <input type="text" id="vendor_name" name="vendor_name" class="form-control"
                                                placeholder="Enter Vendor Name">
                                            @error('vendor_name')
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
                                            <input type="number" name="total_cases" id="total_cases" class="form-control">
                                            @error('total_cases')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Dynamic Trip Cases -->
                                        @for ($i = 1; $i <= 10; $i++)
                                            <div class="form-group mb-2">
                                                <label for="trip_{{ $i }}">Trip {{ $i }} Cases</label>
                                                <input type="number" name="trip_cases_{{ $i }}"
                                                    id="trip_{{ $i }}" class="form-control">
                                                @error('trip_cases_{{ $i }}')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endfor
                                    </div>

                                    <!-- Right Side: Total Received and Remaining Cases -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Total Received Cases<span class="text-danger">*</span></label>
                                            <input type="number" id="received_cases" class="form-control" readonly>
                                            @error('received_cases')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-2">
                                            <label>Remaining Cases<span class="text-danger">*</span></label>
                                            <input type="number" id="remaining_cases" class="form-control" readonly>
                                            @error('remaining_cases')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Form Elements -->
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <!-- Checked By Input -->
                                        <div class="form-group mb-2">
                                            <label>Checked By<span class="text-danger">*</span></label>
                                            <input type="text" id="checked_by" name="checked_by" class="form-control"
                                                placeholder="Enter Your Name">
                                            @error('checked_by')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Invoice Amount -->
                                        <div class="form-group mb-2">
                                            <label for="invoice_amount">Invoice Amount<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" id="invoice_amount" name="invoice_amount"
                                                    class="form-control" placeholder="Enter Invoice Amount" step="0.01">
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
                                                onchange="toggleCheck()">
                                                <option value="" disabled selected>Select Payment Method</option>
                                                <option value="cash">Cash</option>
                                                <option value="Cheque">Cheque</option>
                                                <option value="not_paid">Not Paid</option>
                                                <option value="auto_withdraw">Auto Withdraw</option>
                                            </select>
                                            @error('payment_method')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Conditional Cheque Number Input -->
                                        <div class="form-group mb-2 conditional-input" id="check_number_container"
                                            style="display: none;">
                                            <label>Cheque Number<span class="text-danger">*</span></label>
                                            <input type="text" id="check_number" class="form-control" name="check_number"
                                                placeholder="Enter Check Number">
                                            @error('check_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Short Cases and Reason -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Short Cases</label>
                                            <input type="checkbox" id="short_cases_checkbox">
                                            <input type="hidden" id="short_cases_status" name="short_cases_status"
                                                value="0">
                                        </div>

                                        <div class="form-group mb-0" id="short_cases_reason" style="display: none;">
                                            <label for="reason">Reason for Short Cases</label>
                                            <select name="short_case_reason" id="reason" class="form-control">
                                                <option value="" disabled selected>Select Reason</option>
                                                @foreach ($reasons as $reason)
                                                    <option value="{{ $reason->id }}">{{ $reason->reason }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <!-- Form Actions -->
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-success" id="submit">Complete Order</button>
                                    <p class="mt-3" style="font-size: 16px;"><b>Note:</b> Please confirm with the driver
                                        and double-check the order before marking it as complete.</p>
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
            } else {
                errorShown = false; // Reset the flag if no error
                $('#remaining_cases').val(remainingCases);
            }
            $('#received_cases').val(totalTripCases); // Retain current value for received cases

            // Call function to check if the save order button should be enabled/disabled
            toggleSaveOrderButton(remainingCases);
        }

        // Function to toggle the "Save Order" button
        const toggleSaveOrderButton = (remainingCases) => {
            const saveOrderButton = $('#submit'); // Adjust selector based on your button's ID or class
            const shortCasesCheckbox = $('#short_cases_checkbox');

            // Enable button if remaining cases are 0 or if short cases checkbox is checked
            if (remainingCases === 0 || (shortCasesCheckbox.is(':checked') && totalTripCases <= parseInt($('#total_cases').val()))) {
                saveOrderButton.prop('disabled', false); // Enable the button
            } else {
                saveOrderButton.prop('disabled', true); // Disable the button
            }
        };

        // Function to disable all input fields
        const disableAllInputFields = () => {
            $('#total_cases, [id^=trip_], #payment_method, #check_number_container, #reason, #short_cases_checkbox, #submit, #checked_by, #invoice_amount').prop('disabled', true);
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
        if ($('#total_cases').val() || $('[id^=trip_]').filter(function() { return $(this).val(); }).length > 0) {
            disableAllInputFields(); // Disable all input fields if they are filled
        }

        // Payment method toggle for check number
        const toggleCheckNumber = () => {
            const paymentMethod = $("#payment_method").val();
            const checkNumberContainer = $("#check_number_container");

            if (paymentMethod === "Cheque") {
                checkNumberContainer.show(); // Show the check number input
            } else {
                checkNumberContainer.hide(); // Hide the check number input
                $("#check_number").val(''); // Clear the input if payment method is not "Cheque"
            }
        };

        // Call the function on page load to set the initial state
        toggleCheckNumber();
        $("#payment_method").change(toggleCheckNumber);
    });
</script>




@endsection

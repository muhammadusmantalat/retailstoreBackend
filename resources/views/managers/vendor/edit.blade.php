@extends('managers.layout.app')
@section('title', 'Dashboard')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ route('manager.storeManagerVendor') }}">Back</a>
                <form id="edit_vendor" action="{{ route('manager.storeManagerVendor-update', $vendor->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Edit Wholesaler</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Wholesaler Name<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Enter Wholesaler Name" name="wholesaler_name"
                                                id="wholesaler_name"
                                                value="{{ old('wholesaler_name', $vendor->vendor_name) }}"
                                                class="form-control">
                                            @error('wholesaler_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Wholesaler Email<span class="text-danger"></span></label> 
                                            <input type="email" placeholder="Enter Wholesaler Email"
                                                name="wholesaler_email" id=""
                                                value="{{ old('wholesaler_email', $vendor->email) }}" class="form-control">
                                            @error('wholesaler_email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="phone_no">Wholesaler Phone<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Enter Wholesaler Phone No"
                                                name="wholesaler_phone_number" id="wholesaler_phone_number"
                                                value="{{ old('wholesaler_phone_number', $vendor->phone_no) }}"
                                                class="form-control">
                                            @error('wholesaler_phone_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>General Discount (%)</label>
                                            <input type="number" placeholder="Enter General Discount by Wholesaler"
                                                name="general_discount" id="general_discount"
                                                value="{{ $vendor->discount->general_discount ?? '' }}" class="form-control"
                                                min="0" max="100" step="0.01">
                                            @error('general_discount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="overcharged">Overcharged</label>
                                            <!-- Hidden input to hold the status -->
                                            <input type="hidden" name="overcharged" value="0"
                                                id="overcharged_status">
                                            <!-- Checkbox to toggle status -->
                                            <input type="checkbox" id="overcharged"
                                                {{ $vendor->overcharged_prices ? 'checked' : '' }}>
                                            @error('overcharged')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                    <!-- Other fields with old() function added for value persistence -->

                                   

                                    <div class="col-sm-12 pl-sm-0 pr-sm-3">
                                        <h4 class="text-center my-4">Salesman Information</h4>
                                    </div>

                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Salesman Name<span class="text-danger">*</span></label>
                                            <input type="sales_manager_name" placeholder="Enter Salesman Name"
                                                name="sales_manager_name" id="sales_manager_name" value="{{ old('sales_manager_name', $vendor->salesMen->sales_manager_name ?? '' ) }}"
                                                class="form-control">
                                            @error('sales_manager_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> 
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Salesman Email<span class="text-danger"></span></label>
                                            <input type="email" placeholder="Enter Salesman Email"
                                                name="sales_manager_email" id="sales_manager_email" value="{{ old('sales_manager_email', $vendor->salesMen->sales_manager_email ?? '' ) }}"
                                                class="form-control">
                                            @error('sales_manager_email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div><div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Salesman Phone<span class="text-danger">*</span></label>
                                            <input type="tel" placeholder="Enter Salesman Phone No"
                                                name="sales_manager_phone_no" id="sales_manager_phone_no"
                                                value="{{ old('sales_manager_phone_no', $vendor->salesMen->sales_manager_phone_no ?? '' ) }}" class="form-control">
                                            @error('sales_manager_phone_no')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Order Frequency<span class="text-danger">*</span></label>
                                            <select class="form-control" name="order_frequency">
                                                <option value=""
                                                    {{ is_null(old('order_frequency', $vendor->order_frequency)) ? 'selected' : '' }}
                                                    disabled>Select Order Frequency</option>
                                                <option value="1"
                                                    {{ old('order_frequency', $vendor->salesMen->order_frequency ?? '') == '1' ? 'selected' : '' }}>
                                                    Every week</option>
                                                <option value="2"
                                                    {{ old('order_frequency', $vendor->salesMen->order_frequency ?? '') == '2' ? 'selected' : '' }}>
                                                    After 2 weeks</option>
                                                <option value="3"
                                                    {{ old('order_frequency', $vendor->salesMen->order_frequency ?? '') == '3' ? 'selected' : '' }}>
                                                    After 3 weeks</option>
                                                <option value="4"
                                                    {{ old('order_frequency', $vendor->salesMen->order_frequency ?? '') == '4' ? 'selected' : '' }}>
                                                    After 4 weeks</option>
                                            </select>
                                            @error('order_frequency')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Weekly Order Days<span class="text-danger">*</span></label>
                                            <select class="form-control" name="order_days">
                                                <option value=""
                                                    {{ is_null(old('order_days', $vendor->salesMen->order_dates ?? '')) ? 'selected' : '' }}
                                                    disabled>Select Order Days</option>
                                                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                                    <option value="{{ $day }}"
                                                        {{ old('order_days', $vendor->salesMen->order_dates ?? '') == $day ? 'selected' : '' }}>
                                                        {{ $day }}</option>
                                                @endforeach
                                            </select>
                                            @error('order_days')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Delivery Frequency<span class="text-danger">*</span></label>
                                            <select class="form-control" name="delivery_frequency">
                                                <option value=""
                                                    {{ is_null(old('delivery_frequency', $vendor->salesMen->delivery_frequency ?? '')) ? 'selected' : '' }}
                                                    disabled>Select Delivery Frequency</option>
                                                <option value="1"
                                                    {{ old('delivery_frequency', $vendor->salesMen->delivery_frequency ?? '') == '1' ? 'selected' : '' }}>
                                                    Every week</option>
                                                <option value="2"
                                                    {{ old('delivery_frequency', $vendor->salesMen->delivery_frequency ?? '') == '2' ? 'selected' : '' }}>
                                                    After 2 weeks</option>
                                                <option value="3"
                                                    {{ old('delivery_frequency', $vendor->salesMen->delivery_frequency ?? '') == '3' ? 'selected' : '' }}>
                                                    After 3 weeks</option>
                                                <option value="4"
                                                    {{ old('delivery_frequency', $vendor->salesMen->delivery_frequency ?? '') == '4' ? 'selected' : '' }}>
                                                    After 4 weeks</option>
                                            </select>
                                            @error('delivery_frequency')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Weekly Delivery Days<span class="text-danger">*</span></label>
                                            <select class="form-control" name="delivery_days">
                                                <option value=""
                                                    {{ is_null(old('delivery_days', $vendor->salesMen->delivery_days ?? '')) ? 'selected' : '' }}
                                                    disabled>Select Delivery Days</option>
                                                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                                    <option value="{{ $day }}"
                                                        {{ old('delivery_days', $vendor->salesMen->delivery_days ?? '') == $day ? 'selected' : '' }}>
                                                        {{ $day }}</option>
                                                @endforeach
                                            </select>
                                            @error('delivery_days')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>



                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="salesman_name">Salesman Name<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" placeholder="Enter Salesman Name" name="salesman_name"
                                                id="salesman_name"
                                                value="{{ old('salesman_name', $vendor->salesman_name) }}"
                                                class="form-control">
                                            @error('salesman_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="phone_no">Salesman Phone<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Enter Salesman Phone No"
                                                name="salesman_phone_number" id="salesman_phone_number"
                                                value="{{ old('salesman_phone_number', $vendor->salesman_phone_no) }}"
                                                class="form-control">
                                            @error('salesman_phone_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}

                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Choose Image</label>
                                            <input type="file" name="vendor_image" class="form-control">
                                            @error('image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                </div>

                                <div class="card-footer text-center">
                                    <div class="col">
                                        <button type="submit" class="btn btn-success mr-1 btn-bg"
                                            id="submit">Update</button>
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
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif

    {{-- <script>
        $(document).ready(function() {
            $('.date-dropdown').selectric();
        });
    </script> --}}

    <script>
         document.addEventListener('DOMContentLoaded', function () {
        var phoneInput = document.getElementById('wholesaler_phone_number');

        function formatPhoneNumber(value) {
            // Remove any non-digit characters, but ensure the leading `+1` is preserved
            var cleaned = value.replace(/[^0-9]/g, '');
            if (!cleaned.startsWith('1')) {
                cleaned = '1' + cleaned;
            }

            var match = cleaned.match(/^1(\d{0,3})(\d{0,3})(\d{0,4})$/);
            if (match) {
                return '+1 ' + (match[1] ? match[1] : '') + (match[2] ? ' ' + match[2] : '') + (match[3] ? ' ' + match[3] : '');
            }
            return value;
        }

        function enforceMaxLength(value) {
            var cleaned = value.replace(/[^0-9]/g, '');
            if (cleaned.length > 11) {
                cleaned = cleaned.slice(0, 11); // Limit to 11 digits
            }
            return cleaned;
        }

        phoneInput.addEventListener('input', function () {
            var cleanedValue = enforceMaxLength(phoneInput.value);
            phoneInput.value = formatPhoneNumber(cleanedValue);
        });

        phoneInput.addEventListener('blur', function () {
            var cleanedValue = enforceMaxLength(phoneInput.value);
            phoneInput.value = formatPhoneNumber(cleanedValue);
        });

        // Initial format on page load
        phoneInput.value = formatPhoneNumber(phoneInput.value);
    });
    </script>

    <script>
         document.addEventListener('DOMContentLoaded', function () {
        var phoneInput = document.getElementById('salesman_phone_number');

        function formatPhoneNumber(value) {
            // Remove any non-digit characters, but ensure the leading `+1` is preserved
            var cleaned = value.replace(/[^0-9]/g, '');
            if (!cleaned.startsWith('1')) {
                cleaned = '1' + cleaned;
            }

            var match = cleaned.match(/^1(\d{0,3})(\d{0,3})(\d{0,4})$/);
            if (match) {
                return '+1 ' + (match[1] ? match[1] : '') + (match[2] ? ' ' + match[2] : '') + (match[3] ? ' ' + match[3] : '');
            }
            return value;
        }

        function enforceMaxLength(value) {
            var cleaned = value.replace(/[^0-9]/g, '');
            if (cleaned.length > 11) {
                cleaned = cleaned.slice(0, 11); // Limit to 11 digits
            }
            return cleaned;
        }

        phoneInput.addEventListener('input', function () {
            var cleanedValue = enforceMaxLength(phoneInput.value);
            phoneInput.value = formatPhoneNumber(cleanedValue);
        });

        phoneInput.addEventListener('blur', function () {
            var cleanedValue = enforceMaxLength(phoneInput.value);
            phoneInput.value = formatPhoneNumber(cleanedValue);
        });

        // Initial format on page load
        phoneInput.value = formatPhoneNumber(phoneInput.value);
    });
    </script>

    <script>
        $(document).ready(function() {
            var $checkbox = $('#overcharged');
            var $hiddenInput = $('#overcharged_status');

            // Update hidden input based on checkbox state
            $checkbox.change(function() {
                $hiddenInput.val(this.checked ? '1' : '0');
            });

            // Set hidden input on page load based on checkbox state
            $hiddenInput.val($checkbox.is(':checked') ? '1' : '0');
        });
    </script>
@endsection

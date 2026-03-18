@extends('admin.layout.app')
@section('title', 'Add Vendor')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                <form id="add_vendor" action="{{ route('update-vendor', $vendors->id) }}" method="POST"
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
                                            <label for="vendor_name">Wholesaler Name<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Enter Wholesaler Name" name="Wholesaler_name"
                                                id="Wholesaler_name" value="{{ old('Wholesaler_name', $vendors->vendor_name ?? '') }}"
                                                class="form-control">
                                            @error('Wholesaler_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="vendor_email">Wholesaler Email<span class="text-danger"></span></label>
                                            <input type="email" placeholder="Enter Wholesaler Email" name="wholesaler_email"
                                                id="wholesaler_email" value="{{ old('wholesaler_email', $vendors->email ?? '') }}" class="form-control">
                                            @error('wholesaler_email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Order Frequency<span class="text-danger">*</span></label>
                                            <select class="form-control" name="order_frequency">
                                                <option value="" {{ old('order_frequency', $vendors->order_frequency ?? '') == '' ? 'selected' : '' }} disabled>
                                                    Select Order Frequency
                                                </option>
                                                <option value="Every week" {{ old('order_frequency', $vendors->order_frequency ?? '') == 'Every week' ? 'selected' : '' }}>
                                                    Every week
                                                </option>
                                                <option value="After 2 weeks" {{ old('order_frequency', $vendors->order_frequency ?? '') == 'After 2 weeks' ? 'selected' : '' }}>
                                                    After 2 weeks
                                                </option>
                                                <option value="After 3 weeks" {{ old('order_frequency', $vendors->order_frequency ?? '') == 'After 3 weeks' ? 'selected' : '' }}>
                                                    After 3 weeks
                                                </option>
                                                <option value="After 4 weeks" {{ old('order_frequency', $vendors->order_frequency ?? '') == 'After 4 weeks' ? 'selected' : '' }}>
                                                    After 4 weeks
                                                </option>
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
                                                <option value="" {{ old('order_days', $vendors->order_dates ?? '') == '' ? 'selected' : '' }} disabled>Select Order Days</option>
                                                <option value="Monday" {{ old('order_days', $vendors->order_dates ?? '') == 'Monday' ? 'selected' : '' }}>Monday</option>
                                                <option value="Tuesday" {{ old('order_days', $vendors->order_dates ?? '') == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                                                <option value="Wednesday" {{ old('order_days', $vendors->order_dates ?? '') == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                                                <option value="Thursday" {{ old('order_days', $vendors->order_dates ?? '') == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                                                <option value="Friday" {{ old('order_days', $vendors->order_dates ?? '') == 'Friday' ? 'selected' : '' }}>Friday</option>
                                                <option value="Saturday" {{ old('order_days', $vendors->order_dates ?? '') == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                                                <option value="Sunday" {{ old('order_days', $vendors->order_dates ?? '') == 'Sunday' ? 'selected' : '' }}>Sunday</option>
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
                                                <option value="" {{ old('delivery_frequency', $vendors->delivery_frequency ?? '') == '' ? 'selected' : '' }} disabled>Select Delivery Frequency</option>
                                                <option value="Every week" {{ old('delivery_frequency', $vendors->delivery_frequency ?? '') == 'Every week' ? 'selected' : '' }}>
                                                    Every week
                                                </option>
                                                <option value="After 2 weeks" {{ old('delivery_frequency', $vendors->delivery_frequency ?? '') == 'After 2 weeks' ? 'selected' : '' }}>
                                                    After 2 weeks
                                                </option>
                                                <option value="After 3 weeks" {{ old('delivery_frequency', $vendors->delivery_frequency ?? '') == 'After 3 weeks' ? 'selected' : '' }}>
                                                    After 3 weeks
                                                </option>
                                                <option value="After 4 weeks" {{ old('delivery_frequency', $vendors->delivery_frequency ?? '') == 'After 4 weeks' ? 'selected' : '' }}>
                                                    After 4 weeks
                                                </option>
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
                                                <option value="" {{ old('delivery_days', $vendors->delivery_days ?? '') == '' ? 'selected' : '' }} disabled>Select Delivery Days</option>
                                                <option value="Monday" {{ old('delivery_days', $vendors->delivery_days ?? '') == 'Monday' ? 'selected' : '' }}>Monday</option>
                                                <option value="Tuesday" {{ old('delivery_days', $vendors->delivery_days ?? '') == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                                                <option value="Wednesday" {{ old('delivery_days', $vendors->delivery_days ?? '') == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                                                <option value="Thursday" {{ old('delivery_days', $vendors->delivery_days ?? '') == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                                                <option value="Friday" {{ old('delivery_days', $vendors->delivery_days ?? '') == 'Friday' ? 'selected' : '' }}>Friday</option>
                                                <option value="Saturday" {{ old('delivery_days', $vendors->delivery_days ?? '') == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                                                <option value="Sunday" {{ old('delivery_days', $vendors->delivery_days ?? '') == 'Sunday' ? 'selected' : '' }}>Sunday</option>
                                            </select>
                                            @error('delivery_days')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}

                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="phone_no">Wholesaler Phone<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Enter Wholesaler Phone No"
                                                name="wholesaler_phone_number" id="wholesaler_phone_number"
                                                value="{{ old('wholesaler_phone_number', $vendors->phone_no ?? '') }}" class="form-control">
                                            @error('wholesaler_phone_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="salesman_name">Salesman Name<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Enter Salesman Name" name="salesman_name"
                                                id="salesman_name" value="{{ old('salesman_name', $vendors->salesman_name ?? '') }}"
                                                class="form-control">
                                            @error('salesman_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="phone_no">Salesman Phone<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Enter Salesman Phone No"
                                                name="salesman_phone_number" id="salesman_phone_number"
                                                value="{{ old('salesman_phone_number', $vendors->salesman_phone_number ?? '') }}"
                                                class="form-control">
                                            @error('salesman_phone_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>General Discount (%)</label>
                                            <input type="number" placeholder="Enter General Discount by Wholesaler"
                                                name="general_discount" id="general_discount"
                                                value="{{ $vendors->general_discount }}" class="form-control"
                                                min="0" max="100" step="0.01">
                                            @error('general_discount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Choose Image</label>
                                            <input type="file" name="vendor_image" class="form-control">
                                            @error('image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}

                                </div>



                            <div class="card-footer">
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
    @if (Session::has('message'))
        <script>
            toastr.success('{{ Session::get('message') }}');
        </script>
    @endif
    {{-- <script>
    $(document).ready(function() {
        $('.date-dropdown').selectric();
    });
</script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var phoneInput = document.getElementById('wholesaler_phone_number');

            function formatPhoneNumber(value) {
                var cleaned = ('' + value).replace(/\D/g, '');
                var match = cleaned.match(/^(\d{1})(\d{0,3})(\d{0,3})(\d{0,4})$/);
                if (match) {
                    return '+1 ' + (match[2] ? match[2] : '') + (match[3] ? ' ' + match[3] : '') + (match[4] ? ' ' +
                        match[4] : '');
                }
                return value;
            }

            function enforceMaxLength(value) {
                var cleaned = ('' + value).replace(/\D/g, '');
                if (cleaned.length > 11) {
                    cleaned = cleaned.slice(0, 11);
                }
                return cleaned;
            }

            phoneInput.addEventListener('input', function(e) {
                var cleanedValue = enforceMaxLength(phoneInput.value);
                phoneInput.value = formatPhoneNumber(cleanedValue);
            });

            phoneInput.addEventListener('blur', function() {
                var cleanedValue = enforceMaxLength(phoneInput.value);
                var cleaned = cleanedValue.replace(/\D/g, '');
                var match = cleaned.match(/^(\d{1})(\d{3})(\d{3})(\d{4})$/);
                if (match) {
                    phoneInput.value = '+1 ' + match[2] + ' ' + match[3] + ' ' + match[4];
                } else {
                    phoneInput.value = formatPhoneNumber(cleanedValue);
                }
            });

            // Initial format on page load
            phoneInput.value = formatPhoneNumber(phoneInput.value);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var phoneInput = document.getElementById('salesman_phone_number');

            function formatPhoneNumber(value) {
                var cleaned = ('' + value).replace(/\D/g, '');
                var match = cleaned.match(/^(\d{1})(\d{0,3})(\d{0,3})(\d{0,4})$/);
                if (match) {
                    return '+1 ' + (match[2] ? match[2] : '') + (match[3] ? ' ' + match[3] : '') + (match[4] ? ' ' +
                        match[4] : '');
                }
                return value;
            }

            function enforceMaxLength(value) {
                var cleaned = ('' + value).replace(/\D/g, '');
                if (cleaned.length > 11) {
                    cleaned = cleaned.slice(0, 11);
                }
                return cleaned;
            }

            phoneInput.addEventListener('input', function(e) {
                var cleanedValue = enforceMaxLength(phoneInput.value);
                phoneInput.value = formatPhoneNumber(cleanedValue);
            });

            phoneInput.addEventListener('blur', function() {
                var cleanedValue = enforceMaxLength(phoneInput.value);
                var cleaned = cleanedValue.replace(/\D/g, '');
                var match = cleaned.match(/^(\d{1})(\d{3})(\d{3})(\d{4})$/);
                if (match) {
                    phoneInput.value = '+1 ' + match[2] + ' ' + match[3] + ' ' + match[4];
                } else {
                    phoneInput.value = formatPhoneNumber(cleanedValue);
                }
            });

            // Initial format on page load
            phoneInput.value = formatPhoneNumber(phoneInput.value);
        });
    </script>
@endsection

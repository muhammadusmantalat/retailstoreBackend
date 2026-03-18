@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                <form id="add_department" action="{{ route('vendor-save') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Add Wholesaler</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Wholesaler Name<span class="text-danger">*</span></label>
                                            <input type="name" placeholder="Enter Wholesaler Name" name="wholesaler_name"
                                                id="wholesaler_name" value="{{ old('wholesaler_name') }}" class="form-control">
                                            @error('wholesaler_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Wholesaler Email<span class="text-danger"></span></label>
                                            <input type="email" placeholder="Enter Wholesaler Email" name="wholesaler_email"
                                                id="wholesaler_email" value="{{ old('wholesaler_email') }}" class="form-control">
                                            @error('wholesaler_email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>


                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Monthly Order Dates</label>
                                            <select class="form-control date-dropdown" name="dates[]" multiple>
                                                <option value="" disabled selected>Select Dates</option>
                                                @for ($i = 1; $i <= 31; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                            @error('dates')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}

                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Order Frequency<span class="text-danger">*</span></label>
                                            <select class="form-control" name="order_frequency">
                                                <option value="" disabled {{ is_null(old('order_frequency')) ? 'selected' : '' }}>Select Order Frequency</option>
                                                <option value="1" {{ old('order_frequency') == '1' ? 'selected' : '' }}>Every week</option>
                                                <option value="2" {{ old('order_frequency') == '2' ? 'selected' : '' }}>After 2 weeks</option>
                                                <option value="3" {{ old('order_frequency') == '3' ? 'selected' : '' }}>After 3 weeks</option>
                                                <option value="4" {{ old('order_frequency') == '4' ? 'selected' : '' }}>After 4 weeks</option>
                                            </select>
                                            @error('order_frequency')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Weekly Order Days<span class="text-danger">*</span></label>
                                            <select class="form-control date-dropdown" name="order_days">
                                                <option value="" disabled {{ is_null(old('order_days')) ? 'selected' : '' }}>Select Days</option>
                                                @php
                                                    $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                                @endphp
                                                @foreach ($daysOfWeek as $day)
                                                    <option value="{{ $day }}" {{ old('order_days') == $day ? 'selected' : '' }}>{{ $day }}</option>
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
                                                <option value="" disabled {{ is_null(old('delivery_frequency')) ? 'selected' : '' }}>Select Delivery Frequency</option>
                                                <option value="1" {{ old('delivery_frequency') == '1' ? 'selected' : '' }}>Every week</option>
                                                <option value="2" {{ old('delivery_frequency') == '2' ? 'selected' : '' }}>After 2 weeks</option>
                                                <option value="3" {{ old('delivery_frequency') == '3' ? 'selected' : '' }}>After 3 weeks</option>
                                                <option value="4" {{ old('delivery_frequency') == '4' ? 'selected' : '' }}>After 4 weeks</option>
                                            </select>
                                            @error('delivery_frequency')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Weekly Delivery Days<span class="text-danger">*</span></label>
                                            <select class="form-control date-dropdown" name="delivery_days">
                                                <option value="" disabled {{ is_null(old('delivery_days')) ? 'selected' : '' }}>Select Days</option>
                                                @foreach ($daysOfWeek as $day)
                                                    <option value="{{ $day }}" {{ old('delivery_days') == $day ? 'selected' : '' }}>{{ $day }}</option>
                                                @endforeach
                                            </select>
                                            @error('delivery_days')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Wholesaler Phone<span class="text-danger">*</span></label>
                                            <input type="tel" placeholder="Enter Wholesaler Phone No" name="wholesaler_phone_number"
                                                id="wholesaler_phone_number" value="{{ old('wholesaler_phone_number') }}" class="form-control">
                                            @error('wholesaler_phone_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Salesman Name<span class="text-danger">*</span></label>
                                            <input type="salesman_name" placeholder="Enter Salesman Name" name="salesman_name"
                                                id="salesman_name" value="{{ old('salesman_name') }}" class="form-control">
                                            @error('salesman_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Salesman Phone<span class="text-danger">*</span></label>
                                            <input type="tel" placeholder="Enter Salesman Phone No" name="salesman_phone_number"
                                                id="salesman_phone_number" value="{{ old('salesman_phone_number') }}" class="form-control">
                                            @error('salesman_phone_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>General Discount (%)</label>
                                            <input type="number" placeholder="Enter General Discount by Wholesaler" name="general_discount"
                                                id="general_discount" value="{{ old('general_discount') }}" class="form-control" min="0" max="100" step="0.01">
                                            @error('general_discount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Wholesaler Image</label>
                                            <input type="file" name="vendor_image" value="{{ old('image') }}"
                                                class="form-control">
                                            @error('image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                </div>
                                <div class="card-footer text-center" >
                                    <div class="col">
                                        <button type="submit" class="btn btn-success mr-1 btn-bg"
                                            id="submit">Save</button>
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
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            var input = document.querySelector("#phone_no");
            window.intlTelInput(input, {
                initialCountry: "us",
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
            });
        });
    </script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var phoneInput = document.getElementById('wholesaler_phone_number');

            phoneInput.addEventListener('input', function(e) {
                var x = phoneInput.value.replace(/\D/g, '').match(/(\d{1})(\d{0,3})(\d{0,3})(\d{0,4})/);
                phoneInput.value = '+1 ' + (x[2] ? x[2] : '') + (x[3] ? ' ' + x[3] : '') + (x[4] ? ' ' + x[
                    4] : '');
            });

            // Ensure the field is cleared of any invalid characters on blur
            phoneInput.addEventListener('blur', function() {
                var x = phoneInput.value.replace(/\D/g, '').match(/(\d{1})(\d{3})(\d{3})(\d{4})/);
                phoneInput.value = x ? '+1 ' + x[2] + ' ' + x[3] + ' ' + x[4] : '';
            });
        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var phoneInput = document.getElementById('salesman_phone_number');

        phoneInput.addEventListener('input', function(e) {
            var x = phoneInput.value.replace(/\D/g, '').match(/(\d{1})(\d{0,3})(\d{0,3})(\d{0,4})/);
            phoneInput.value = '+1 ' + (x[2] ? x[2] : '') + (x[3] ? ' ' + x[3] : '') + (x[4] ? ' ' + x[
                4] : '');
        });

        // Ensure the field is cleared of any invalid characters on blur
        phoneInput.addEventListener('blur', function() {
            var x = phoneInput.value.replace(/\D/g, '').match(/(\d{1})(\d{3})(\d{3})(\d{4})/);
            phoneInput.value = x ? '+1 ' + x[2] + ' ' + x[3] + ' ' + x[4] : '';
        });
    });
</script>


@endsection

@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                <form id="add_student" action="{{ route('store-detail.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Add Stores</h4>
                                <div class="row justify-content-end px-4" id="storeNameContainer">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="storeManager_id">Store Managers<span class="text-danger">*</span></label>
                                            <select id="store-dropdown" class="form-control" name="storeManager_id">
                                                <option value="" disabled selected>Select Store Manager</option>
                                                @foreach ($storeManagers as $storeManager)
                                                    <option value="{{ $storeManager->id }}">{{ $storeManager->first_name }}
                                                        {{ $storeManager->last_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('storeManager_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3 store_names" id="store_names">
                                        <div class="form-group mb-2">
                                            <label>Store Name<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Enter Store Name" name="store_name[]"
                                                id="store_name" value="{{ implode(',', old('store_name', [])) }}"
                                                class="form-control">
                                            @error('store_name.*')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Store Address Input -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Store Address<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Enter Store Address" name="store_address[]"
                                                class="form-control">
                                            @error('store_address.*')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Store Phone Number Input -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Store Phone Number<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Enter Phone Number" name="store_phone[]"
                                                class="form-control store_phone mb-2">
                                            @error('store_phone.*')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="w-100 d-flex justify-content-end pr-3">

                                        <button type="button" style="height: 40px" class="btn btn-primary mb-3 d-block"
                                            id="addStoreBtn">Add Store</button>
                                    </div>
                                    <div id="sizeInputs" class="w-100"></div>
                                </div>
                                <div class="card-footer text-center row">
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

    <script>
        $(document).ready(function() {
            // Add a new store input group when the "Add Store" button is clicked
            $('#addStoreBtn').click(function() {
                $('#sizeInputs').append(`
                    <div class="row justify-content-center store-group">
                        <div class="col-md-4">
                            <input type="text" class="form-control mb-2 store_name" name="store_name[]" placeholder="Enter Store Name">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control mb-2 store_address" name="store_address[]" placeholder="Enter Store Address">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control mb-2 store_phone" name="store_phone[]" placeholder="Enter Phone Number">
                        </div>
                        <div class="w-100 d-flex justify-content-end mr-4">
                            <button type="button" class="btn btn-danger mb-2 removeBtn"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                `);
            });

            // Remove a store input group when the "Remove" button is clicked
            $(document).on('click', '.removeBtn', function() {
                $(this).closest('.store-group').remove();
            });

            // Format the phone number while typing in dynamically added store_phone fields
            $(document).on('input', '.store_phone', function() {
                var phoneInput = $(this);
                var inputValue = phoneInput.val().replace(/\D/g, ''); // Remove non-digit characters

                // Ensure the number starts with '1' and limit to 10 digits (after the country code)
                if (inputValue.length > 0 && inputValue.charAt(0) !== '1') {
                    inputValue = '1' + inputValue; // Prepend '1' if it doesn't start with '1'
                }

                if (inputValue.length > 11) {
                    inputValue = inputValue.substring(0, 11); // Limit to 11 digits
                }

                // Format the phone number as +1 (XXX) XXX-XXXX
                var formattedNumber = inputValue.replace(/^(\d{1})(\d{3})(\d{3})(\d{4})$/, '+$1 $2 $3 $4');
                phoneInput.val(formattedNumber);
            });

            // Ensure the phone field is cleared or formatted correctly when the user leaves the field
            $(document).on('blur', '.store_phone', function() {
                var phoneInput = $(this);
                var inputValue = phoneInput.val().replace(/\D/g, '');
                var x = inputValue.match(/^(\d{1})(\d{3})(\d{3})(\d{4})$/);

                // If valid, format it; otherwise, clear it
                phoneInput.val(x ? '+1 ' + x[2] + ' ' + x[3] + ' ' + x[4] : '');
            });
        });
    </script>

@endsection

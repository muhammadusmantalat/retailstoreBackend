@extends('managers.layout.app')
@section('title', 'Dashboard')
@section('content')
    <!-- Head section of your HTML -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                <form id="add_department" action="{{ route('manager.productVendor-store', $id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Assign Departments For ({{ $product->product_name }})</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Wholesalers Name<span class="text-danger">*</span></label>
                                            <input type="text" value={{ $id }} name='product_id'
                                                class='product_id' hidden>

                                            <select name="wholesaler_id[]" id="wholesaler_id" class="form-control vendor-dropdown">
                                                <option value="" disabled selected>Select Wholesaler</option>
                                                @foreach ($vendorAssignments as $vendorAssignment)
                                                    <option value="{{ $vendorAssignment->vendor_id }}">{{ $vendorAssignment->vendors->vendor_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('wholesaler_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            @error('wholesaler_id.*')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="department_id">Departments<span class="text-danger">*</span></label>
                                            <select class="form-control department-dropdown" name="department[]" multiple>
                                                {{-- <option value="" disabled>Select Department</option> --}}
                                            </select>
                                            @error('department.*')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            @error('department')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="price">Cost<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" step="0.01" min="0"
                                                    placeholder="Enter Price" name="price[]" id="price"
                                                    class="form-control">
                                            </div>
                                            @error('price')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            @error('price.*')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <input type="hidden" name="store_manager_id" value="{{ $authId }}">
                                    <input type="hidden" name="store_id" value="{{ $storeId }}">
                                </div>
                                <div id="sizeInputs">

                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary mb-3" id="addDepartmentBtn">+
                                    </button>
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


{{-- @section('js')
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Event delegation for dynamically added vendor dropdowns
            $('.department-dropdown').select2({
                placeholder: "Select Department",
                allowClear: true
            });
            $(document).on('change', '.vendor-dropdown', function() {
                var vendorId = $(this).val();
                // Find the closest .row parent to this dropdown, then find the .department-dropdown dropdown within that row
                var departmentDropdown = $(this).closest('.row').find('.department-dropdown');
                var productId = $('.product_id').val(); // Assuming product_id is the same for all rows
                if (vendorId) {
                    $.ajax({
                        url: '{{ url('manager/storeManagerGetDepartments') }}/' + vendorId + '/' +
                            productId,
                        type: 'GET',
                        dataType: 'json', // Assuming the response is in JSON format
                        success: function(response) {
                            console.log("Data received:", response.data);
                            departmentDropdown.empty(); // Clear existing options
                            departmentDropdown.append(
                                '<option value="" disabled>Select Department</option>');
                            if (response.status === 'success') {
                                $.each(response.data, function(index, department) {
                                    departmentDropdown.append($('<option></option>')
                                        .attr("value", department.id).text(
                                            department.department_name));
                                });
                            } else {
                                console.log("Failed to load departments: ", response.status);
                            }
                            // If using a plugin like Selectric or Select2 that needs refreshing:
                            // departmentDropdown.select2(); // For Select2
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log("AJAX call failed: " + textStatus + ", " + errorThrown);
                        }
                    });
                }
            });

            $('#addDepartmentBtn').click(function() {
                $('#sizeInputs').append(`
                    <div class="row mx-0 px-4">
                        <div class="col-sm-4 pl-sm-0 pr-sm-3">
                            <div class="form-group mb-2">
                                <label>Vendors Names</label>
                                <select name="vendor_id[]" class="form-control vendor-dropdown">
                                    <option value="" disabled selected>Select Vendors</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="department_id">Departments</label>
                                            <select class="form-control department-dropdown2" name="department[]" multiple
                                                id='department'>
                                                {{-- <option value="" disabled selected>Select Department</option>
                                            </select>
                                            @error('department.*')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                        <div class="col-sm-4 pl-sm-0 pr-sm-3">
                            <div class="form-group mb-2">
                                <label for="price">Price</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" step="0.01" min="0" placeholder="Enter Price" name="price[]" class="form-control">
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger mb-3 removeBtn">Remove</button>
                        </div>
                    </div>
                `);
                $('#department').select2({
                placeholder: "Select Department",
                allowClear: true
            });
                // Reinitialize any plugins for select elements here, if necessary
            });

            $(document).on('click', '.removeBtn', function() {
                $(this).closest('.row').remove();
            });
        });
    </script>


@endsection --}}

@section('js')
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            function initializeSelect2ForDepartmentDropdown() {
                // Initialize Select2 for department dropdowns not already enhanced by Select2
                $('.department-dropdown').not('.select2-hidden-accessible').select2({
                    placeholder: "Select Department",
                    allowClear: true,
                    closeOnSelect: false
                });
            }

            // Initialize Select2 on initial load for any existing department dropdowns
            initializeSelect2ForDepartmentDropdown();

            // Function to fetch and update department dropdown options based on selected vendor
            $(document).on('change', '.vendor-dropdown', function() {
                var vendorId = $(this).val();
                var departmentDropdown = $(this).closest('.row').find('.department-dropdown');
                var productId = $('.product_id').val();
                if (vendorId) {
                    $.ajax({
                        url: '{{ url('manager/storeManagerGetDepartments') }}/' + vendorId + '/' +
                            productId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            departmentDropdown.empty();
                            if (response.status === 'success') {
                                $.each(response.data, function(index, department) {
                                    departmentDropdown.append($('<option></option>')
                                        .attr("value", department.id).text(
                                            department.department_name));
                                });
                            }
                            departmentDropdown.trigger('change'); // Notify Select2 to update
                        }
                    });
                }
            });

            // Functionality to dynamically add more department dropdowns
            $('#addDepartmentBtn').click(function() {
                // Increment a counter used to give unique names to your inputs
                let newIndex = $('#sizeInputs .row').length;

                var dynamicRow = `
        <div class="row mx-0 px-4 align-items-end" data-index="${newIndex}">
            <div class="col-sm-4 pl-sm-0 pr-sm-3">
                <div class="form-group mb-2">
                    <label>Wholesaler Name*</label>
                    <select name="assignments[${newIndex}][vendor_id]" class="form-control vendor-dropdown">
                        <option value="" disabled selected>Select Wholesaler</option>
                        @foreach ($vendorAssignments as $vendorAssignment)
                            <option value="{{ $vendorAssignment->vendor_id }}">{{ $vendorAssignment->vendors->vendor_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-4 pl-sm-0 pr-sm-3">
                <div class="form-group mb-2">
                    <label>Departments*</label>
                    <select class="form-control department-dropdown" name="assignments[${newIndex}][department][]" multiple>
                    </select>
                </div>
            </div>
            <div class="col-sm-4 pl-sm-0 pr-sm-3">
                <div class="form-group mb-2">
                    <label>Cost*</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input type="number" step="0.01" min="0" placeholder="Enter Price" name="assignments[${newIndex}][price]" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-sm-3 pl-sm-0 pr-sm-3 mb-2">
                <button type="button" class="btn btn-danger removeBtn" style="height: 38px;">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

                $('#sizeInputs').append(dynamicRow);
                initializeSelect2ForDepartmentDropdown(); // Ensure new dropdowns are enhanced by Select2
            });


            // Functionality to remove a dynamically added row
            $(document).on('click', '.removeBtn', function() {
                $(this).closest('.row').remove();
            });
        });
    </script>
@endsection

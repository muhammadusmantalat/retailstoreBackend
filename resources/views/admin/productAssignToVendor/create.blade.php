@extends('admin.layout.app')
@section('title', 'Dashboard')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                <form id="add_department" action="{{ route('products-assignVendor-store', $productId) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Assign Products</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Store Manager Name<span class="text-danger">*</span></label>
                                            <input type="hidden" name="store_manager_id" value="{{ $StoreManagerId->id }}">
                                            <input type="text" value='{{ $StoreManagerId->id }}' hidden
                                                class="storeManagerId">
                                            <input type="text" class="form-control" name = "store_manager_id"
                                                value="{{ $StoreManagerId->first_name }} {{ $StoreManagerId->last_name }}"
                                                disabled>

                                            @error('store_manager_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Store Name<span class="text-danger">*</span></label>
                                            <input type="hidden" name="store_id" value="{{ $StoreId->id }}">
                                            <input type="text" value='{{$StoreId->id}}' hidden class="storeId">
                                            <input type="text" class="form-control" name = "store_id"
                                                value="{{ $StoreId->store_name }}" disabled>
                                            @error('store_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Wholesaler Names<span class="text-danger">*</span></label>
                                            <input type="text" value={{ $productId }} name='product_id'
                                                class='product_id' hidden>

                                            <select name="Wholesaler_id[]" id="Wholesaler_id" class="form-control vendor-dropdown">
                                                <option value="" disabled selected>Select Wholesaler</option>
                                                @foreach ($vendorAssignments as $vendorAssignment)
                                                    <option value="{{ $vendorAssignment->vendor_id }}">
                                                        {{ $vendorAssignment->vendors->vendor_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('Wholesaler_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            {{-- @error('vendor_id.*')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror --}}
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="department_id">Department Names<span class="text-danger">*</span></label>
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
                        url: '{{ url('admin/get-vendorDepartments') }}/' + vendorId + '/' +
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
                    <label>Vendors Names</label>
                    <select name="assignments[${newIndex}][vendor_id]" class="form-control vendor-dropdown">
                        <option value="" disabled selected>Select Wholesalers</option>
                        @foreach ($vendorAssignments as $vendorAssignment)
                            <option value="{{ $vendorAssignment->vendor_id }}">{{ $vendorAssignment->vendors->vendor_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-4 pl-sm-0 pr-sm-3">
                <div class="form-group mb-2">
                    <label>Departments</label>
                    <select class="form-control department-dropdown" name="assignments[${newIndex}][department][]" multiple>
                    </select>
                </div>
            </div>
            <div class="col-sm-4 pl-sm-0 pr-sm-3">
                <div class="form-group mb-2">
                    <label>Price</label>
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
{{-- <script>
        $(document).ready(function() {


            var fixedStoreId = "{{ $store->id }}";
            loadDepartmentsForStore(fixedStoreId);

            // console.log("fixedStoreId");

            function loadDepartmentsForStore(storeId) {
                $('.department-dropdown').html(
                    '<option value="" disabled selected>Loading Departments...</option>');
                $.ajax({
                    url: "{{ url('admin/get-vendorDepartments') }}/" + storeId,
                    type: "GET",
                    dataType: 'json',
                    success: function(result) {
                        $('.department-dropdown').html(
                            '<option value="" disabled selected>Select Department</option>');
                        $.each(result.data, function(key, value) {
                            $(".department-dropdown").append('<option value="' + value.id +
                                '">' + value
                                .department_name + '</option>');
                        });
                    }
                });
            }

            // $('.department-dropdown').on('change', function() {
            //     var departmentId = $(this).val();
            //     $('.product-dropdown').html('<option value="" disabled selected>Select Product</option>');
            //     console.log('id', departmentId);
            //     $.ajax({
            //         url: "{{ url('admin/get-departmentsProduct') }}/" + departmentId,
            //         type: "GET",
            //         dataType: 'json',
            //         success: function(result) {
            //             $.each(result.data, function(key, value) {
            //                 $(".product-dropdown").append('<option value="' + value.id +
            //                     '">' + value.product_name + '</option>');
            //             });
            //         }
            //     });
            // });

            $('.department-dropdown').on('change', function() {
                var departmentId = $(this).val();

                var storeManagerId = $('.storeManagerId').val();
                var storeId = $('.storeId').val();
                // alert(storeManagerId,storeId);

                // console.log('dataid',storeManagerId);

                $('.vendor-dropdown').html('<option value="" disabled selected>Select Vendor</option>');

                $.ajax({
                    url: "{{ url('admin/get-departmentsVendors') }}/" + departmentId + '/' + storeManagerId + "/" + storeId,
                    type: "GET",
                    dataType: 'json',
                    success: function(result) {
                        $.each(result.data, function(key, value) {
                            $(".vendor-dropdown").append('<option value="' + value.id +
                                '">' + value.vendor_name + '</option>');
                        });
                    }
                });
            });
        });
    </script> --}}

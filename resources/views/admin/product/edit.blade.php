@extends('admin.layout.app')
@section('title', 'Edit Product')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                <form id="edit_product" action="{{ route('products-update', $product->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <!-- Ensure the method is PUT for an update operation -->
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Edit Product</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Store Managers</label>
                                            <select class="form-control store-manager-dropdown"
                                                name="store_manager_id"disabled>
                                                <option value="" disabled>Select Store Manager</option>
                                                @foreach ($storeManagers as $storeManager)
                                                    <option value="{{ $storeManager->id }}"
                                                        {{ $storeManager->id == $product->store_manager_id ? 'selected' : '' }}>
                                                        {{ $storeManager->first_name }}
                                                        {{ $storeManager->last_name }}</option>
                                                @endforeach
                                            </select>
                                            {{-- @error('store_manager_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                        </div>
                                    </div>

                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="store_id">Stores</label>
                                            <select class="form-control store-dropdown" name="store_id">
                                                <option value="" disabled>Select Store</option>
                                                <!-- Options will be populated based on the selected store manager -->
                                            </select>
                                            {{-- @error('store_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                        </div>
                                    </div>
                                    <!-- Product Name -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Product Name</label>
                                            <input type="text" placeholder="Enter Product Name" name="product_name"
                                                id="product_name" value="{{ old('product_name', $product->product_name) }}"
                                                class="form-control">
                                            @error('product_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- UPI/IPU -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>UPC / IPC</label>
                                            <input type="text" placeholder="Enter UPC / IPC " name="upc_ipc"
                                                id="upc_ipc" value="{{ old('upc_ipc', $product->upc_ipc) }}"
                                                class="form-control" readonly>
                                            @error('upc_ipc')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- Price -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="price">Retail Price</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" step="0.01" min="0"
                                                    placeholder="Enter Price" name="price" id="price"value="{{ old('upc_ipc', $product->price) }}"
                                                    class="form-control">
                                            </div>
                                            @error('price')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror

                                        </div>
                                    </div>


                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="tax_status">Tax Status</label>
                                            <select name="tax_status" id="tax_status" class="form-control">
                                                <option value="" disabled>Select Tax Status</option>
                                                <option value="taxable"
                                                    {{ old('tax_status', $product->tax_status) == 'taxable' ? 'selected' : '' }}>
                                                    Taxable</option>
                                                <option value="non_taxable"
                                                    {{ old('tax_status', $product->tax_status) == 'non_taxable' ? 'selected' : '' }}>
                                                    Non-Taxable</option>
                                            </select>
                                            @error('tax_status')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}


                                    <input type ="hidden" name="store_id" value="{{ $storeId }}">
                                </div>
                                <div class="card-footer text-center row">
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
    @if (session('message'))
        <script>
            toastr.success('{{ session('message') }}');
        </script>
    @endif

    <script>
        $(document).ready(function() {
            function loadStores(storeManagerId, selectedStoreId = null) {
                $('.store-dropdown').html('<option value="" disabled>Select Store</option>').prop('disabled',
                    false);

                $.ajax({
                    url: "{{ url('admin/get-store-product') }}/" + storeManagerId,
                    type: "GET",
                    dataType: 'json',
                    success: function(result) {
                        $.each(result.data, function(key, value) {
                            let isSelected = selectedStoreId == value.id ? 'selected' :
                                ''; // Ensure correct comparison
                            $(".store-dropdown").append('<option value="' + value.id + '" ' +
                                isSelected + '>' + value.store_name + '</option>');
                        });
                        $('.store-dropdown').prop('disabled', true);
                    }
                });
            }

            // Initial load for edit
            let initialStoreManagerId = $('.store-manager-dropdown').val();
            let initialStoreId =
                '{{ $product->store_id }}'; // Ensure this value is correctly passed from your backend
            if (initialStoreManagerId) {
                loadStores(initialStoreManagerId, initialStoreId);
            }

            // On change event for store manager dropdown
            $('.store-manager-dropdown').on('change', function() {
                var storeManagerId = $(this).val();
                loadStores(storeManagerId);
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#addDepartmentBtn').click(function() {
                $('#sizeInputs').append(
                    '<div class="row justify-content-center"><input type="text" class="form-control mb-2 col-8 col-md-10 col-sm-8 col-lg-10" name="fla_vars[]" placeholder="Enter Flavours / Varients"><button type="button" class="btn btn-danger ml-2 mb-2 removeBtn"><i class="fas fa-trash-alt"></i></button></div>'
                );
            });

            $(document).on('click', '.removeBtn', function() {
                $(this).parent('div').remove();
            });
        });
    </script>

@endsection

@extends('managers.layout.app')
@section('title', 'Edit Product')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ route('manager.storeManagerProducts') }}">Back</a>
                <form id="edit_product" action="{{ route('manager.storeManagerProducts-update', $product->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <!-- Ensure the method is PUT for an update operation -->
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Edit Product</h4>
                                <div class="row mx-0 px-4">
                                    <!-- Product Name -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Product Name<span class="text-danger">*</span></label>
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
                                            <label>UPC / IPC<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Enter UPC / IPC " name="upc_ipc"
                                                id="upc_ipc" value="{{ old('upc_ipc', $product->upc_ipc) }}"
                                                class="form-control">
                                            @error('upc_ipc')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- Price -->
                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                <div class="form-group mb-2">
                                    <label for="price">Price</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" step="0.01" min="0" placeholder="Enter Price" name="price" id="price" value="{{ old('price', $product->price) }}" class="form-control">
                                    </div>
                                    @error('price')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div> --}}
                                    {{-- <!-- Tax Status -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
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

                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="price">Retail Price<span class="text-danger">*</span></label>
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
                                    <input type = "hidden" name="store_id" value="{{ $storeId }}">
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
            // Add any specific JavaScript needed for this page
        });
    </script>

@endsection

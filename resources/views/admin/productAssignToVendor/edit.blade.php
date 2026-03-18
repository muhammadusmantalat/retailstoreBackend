@extends('admin.layout.app')

@section('title', 'Edit Assigned Product')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>

                <form id="edit_assigned_product" action="{{ route('products-assignVendor-update',['id' =>$assignedProduct->id, 'vendorId' =>$vendorId,'productId' =>$productId]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') <!-- Assuming you're using RESTful controllers -->
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <h4 class="text-center my-4">Edit Assigned Product</h4>
                            <div class="row mx-0 px-4">
                                <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                    <div class="form-group mb-2">
                                        <label>Store Manager Name</label>
                                        <input type="hidden" name="store_manager_id" value="{{ $storeManagerId }}">
                                        <input type="text" value='{{$storeManagerId}}' hidden class="storeManagerId">
                                        <input type="text" class="form-control" name = "store_manager_id"
                                            value="{{$storeManagerName}}"
                                            disabled>

                                        @error('store_manager_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                    <div class="form-group mb-2">
                                        <label>Store Name</label>
                                        <input type="hidden" name="store_id" value="{{ $storeId }}">
                                        <input type="text" value='{{$storeId}}' hidden class="storeId">
                                        <input type="text" class="form-control" name = "store_id"
                                            value="{{ $storeName }}" disabled>
                                        @error('store_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4 pl-sm-0 pr-sm-3">

                                        <div class="form-group mb-2">
                                            <label>Wholesaler Name</label>
                                            <input type="text" class="form-control" value="{{ $vendorName }}" disabled>
                                            <!-- Hidden field to still send the vendor_id on form submit -->
                                            <input type="hidden" name="vendor_id" value="{{ $vendorId }}">
                                        </div>
                                </div>
                                <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                    <div class="form-group mb-2">
                                        <label for="department_id">Departments</label>
                                        <select name="department_id[]" class="form-control department-dropdown">
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}"
                                                    {{ (is_array($assignedProduct->department_id) ? in_array($department->id, $assignedProduct->department_id) : $department->id == $assignedProduct->department_id) ? 'selected' : '' }}>
                                                    {{ $department->department_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                    <div class="form-group mb-2">
                                        <label for="price">Cost</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number" step="0.01" min="0"
                                                value="{{ $assignedProduct->product_price }}" placeholder="Enter Price"
                                                name="price" id="price" class="form-control">
                                        </div>
                                        @error('price')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                    <input type ="hidden" name="product_id" value="{{ $productId }}">

                            </div>
                            <div class="card-footer text-center row">
                                <div class="col">
                                    <button type="submit" class="btn btn-success mr-1 btn-bg">Update</button>
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
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script> --}}
{{-- <script>
    $(document).ready(function() {
            $('.department-dropdown').select2({
                placeholder: "Select Departments",
                allowClear: true
            });
        });
    </script> --}}
@endsection


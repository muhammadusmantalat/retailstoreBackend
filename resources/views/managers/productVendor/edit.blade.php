@extends('managers.layout.app')
@section('title', 'Edit Assigned Departments')
@section('content')
    <!-- Head section of your HTML -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                <form id="edit_department"
                    action="{{ route('manager.productVendor-update', ['id' => $id, 'vendorId' => $vendorId, 'productId' => $productId]) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Edit Assigned Department For ({{ $product->product_name }})
                                </h4>
                                <div class="row mx-0 px-4">
                                    <!-- Vendor Name -->
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Wholesaler Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" value="{{ $vendorName }}" disabled>
                                            <!-- Hidden field to still send the vendor_id on form submit -->
                                            <input type="hidden" name="vendor_id" value="{{ $vendorId }}">
                                        </div>
                                    </div>
                                    <!-- Departments -->
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="department_id">Departments<span class="text-danger">*</span></label>
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
                                    <!-- Price -->
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="price">Cost<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" step="0.01" min="0"
                                                    value="{{ trim($assignedProduct->product_price ?? 0) }}"
                                                    placeholder="Enter Price" name="price" id="price"
                                                    class="form-control">

                                            </div>
                                            @error('price')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>




                                    <input type ="hidden" name="store_manager_id" value="{{ $authId }}">
                                    <input type ="hidden" name="store_id" value="{{ $storeId }}">
                                    <input type ="hidden" name="product_id" value="{{ $productId }}">

                                </div>
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-success">Update</button>
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
    <script>
        $(document).ready(function() {
            $('.department-dropdown').select2({
                placeholder: "Select Departments",
                allowClear: true
            });
        });
    </script>
@endsection

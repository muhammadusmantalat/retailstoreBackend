@extends('managers.layout.app')
@section('title', 'Dashboard')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ route('manager.storeManagerProducts') }}">Back</a>
                <form id="add_department" action="{{ route('manager.storeManagerProducts-store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Add Product</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Product Name<span class="text-danger">*</span></label>
                                            <input type="name" placeholder="Enter Product Name" name="product_name"
                                                id="product_name" value="{{ old('product_name') }}" class="form-control">
                                            @error('product_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>UPC / IPC<span class="text-danger">*</span></label>
                                            <input type="number" placeholder="Enter UPC / IPC " name="upc_ipc"
                                                id="upc_ipc" value="{{ old('upc_ipc') }}" class="form-control">
                                            @error('upc_ipc')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="tax_status">Tax Status</label>
                                            <select name="tax_status" id="tax_status" class="form-control">
                                                <option value="" disabled selected>Select Tax Status</option>
                                                <option value="taxable"
                                                    {{ old('tax_status') == 'taxable' ? 'selected' : '' }}>Taxable</option>
                                                <option value="non_taxable"
                                                    {{ old('tax_status') == 'non_taxable' ? 'selected' : '' }}>Non-Taxable
                                                </option>
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
                                                    placeholder="Enter Price" name="price" id="price"
                                                    class="form-control">
                                            </div>
                                            @error('price')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror

                                        </div>
                                    </div>

                                    <input type="hidden" name="store_manager_id" value="{{ $authId }}">
                                    <input type="hidden" name="store_id" value="{{ $StoreId->store_id }}">
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
            $('.date-dropdown').selectric();
        });
    </script>

@endsection

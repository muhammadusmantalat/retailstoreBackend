@extends('admin.layout.app')
@section('title', 'Edit Product')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                <form id="edit_product" action="{{ route('hotSalingProduct.update', $product->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('POST') <!-- Ensure the method is PUT for an update operation -->
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4"> Edit Product Buying Quantity Per Vendor</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Edit Quantity </label>
                                            <input type="number" placeholder="Enter Quantity" name="quantity"
                                                id="quantity" value="{{ old('quantity', $product->quantity) }}"
                                                class="form-control">
                                            @error('quantity')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <small style="color: black;">Note: Any product that reaches this buying quantity will be considered a hot selling product.</small>
                                        </div>
                                    </div>

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

@endsection

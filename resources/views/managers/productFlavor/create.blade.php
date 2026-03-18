@extends('managers.layout.app')
@section('title', 'Dashboard')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                <form id="add_department" action="{{ route('manager.productFlavor-save') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Add Flavors / Variants</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Flavor / Variant Name<span class="text-danger">*</span></label>
                                            <input type="name" placeholder="Enter Flavors / Variants Name" name="flavour_name[]"
                                                id="flavour_name[]" value="{{ old('flavour_name') }}" class="form-control"
                                                required>
                                            <button type="button" class="btn btn-primary my-3" id="addDepartmentBtn">+
                                            </button>
                                            <div id="sizeInputs">
                                            </div>
                                            @error('flavour_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    </div>
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
            $('#addDepartmentBtn').click(function() {
                $('#sizeInputs').append(
                    '<div class="row justify-content-center mb-2">' +
                    '<input type="text" name="flavour_name[]" class="form-control col-8 col-md-10 col-sm-8 col-lg-10" placeholder="Enter Flavor / Variant Name" required>' +
                    '<button type="button" class="btn btn-danger removeBtn" style="margin-left: 10px;"><i class="fas fa-trash-alt"></i></button>' +
                    '</div>'
                );
            });

            $(document).on('click', '.removeBtn', function() {
                $(this).closest('.row').remove(); // Remove the entire row
            });
        });
    </script>
@endsection

@extends('managers.layout.app')
@section('title', 'Dashboard')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                
                    <a class="btn btn-primary mb-3" href="{{ route('manager.storeSaleManager.index') }}">Back</a>  
                <form id="add_department" action="{{ route('manager.storeSaleManager.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Add Salesman</h4>
                                <div class="row mx-0 px-4">
                                     <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Salesman Name<span class="text-danger">*</span></label>
                                            <input type="sales_manager_name" placeholder="Enter Salesman Name"
                                                name="sales_manager_name" id="sales_manager_name" value="{{ old('sales_manager_name') }}"
                                                class="form-control">
                                            @error('sales_manager_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Salesman Email<span class="text-danger"></span></label>
                                            <input type="email" placeholder="Enter Salesman Email"
                                                name="sales_manager_email" id="sales_manager_email" value="{{ old('sales_manager_email') }}"
                                                class="form-control">
                                            @error('sales_manager_email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div><div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Salesman Phone<span class="text-danger">*</span></label>
                                            <input type="tel" placeholder="Enter Salesman Phone No"
                                                name="sales_manager_phone_no" id="sales_manager_phone_no"
                                                value="{{ old('sales_manager_phone_no') }}" class="form-control">
                                            @error('sales_manager_phone_no')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <div class='col-12'>
                                        <div id="sizeInputs">
                                        </div>
                                        <button type="button" class="btn btn-primary mb-3" id="addDepartmentBtn">+ Add Department</button>
                                    </div> --}}
                                </div>
                                <div class="card-footer text-center row">
                                    <div class="col">
                                        <button type="submit" class="btn btn-success mr-1 btn-bg" id="submit">Save</button>
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
                    '<div class="row justify-content-end">' +
                    '<div class="col-sm-4">' +
                    '<div class="form-group mb-2">' +
                    '<input type="text" class="form-control mb-2" name="department_name[]" placeholder="Enter Department Name">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<div class="form-group mb-2">' +
                    '<select name="tax_status[]" class="form-control">' +
                    '<option value="" disabled selected>Select Tax Status</option>' +
                    '<option value="1">Taxable</option>' +
                    '<option value="0">Non-Taxable</option>' +
                    '</select>' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<div class="form-group mb-2">' +
                    '<input type="file" name="image[]" class="form-control">' +
                    '</div>' +
                    '</div>' +
                    '<button type="button" class="btn btn-danger ml-2 mb-2 removeBtn"><i class="fas fa-trash-alt"></i></button>' +
                    '</div>'
                );
            });

            $(document).on('click', '.removeBtn', function() {
                $(this).closest('div.row').remove();
            });
        });
    </script>
@endsection

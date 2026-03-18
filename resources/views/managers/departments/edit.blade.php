@extends('managers.layout.app')
@section('title', 'Dashboard')
@section('content')

    <body>
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <a class="btn btn-primary mb-3" href="{{ route('manager.manager-store-department') }}">Back</a>
                    <form id="add_student" action="{{ route('manager.newDepartment', $departments->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <h4 class="text-center my-4">Edit Department</h4>
                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Department Name<span class="text-danger">*</span></label>
                                                <input type="text" placeholder="Enter Department Name"
                                                    name="department_name" value="{{ $departments->department_name }}"
                                                    class="form-control">
                                                @error('department_name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label for="tax_status">Tax Status<span class="text-danger">*</span></label>
                                                <select name="tax_status" id="tax_status" class="form-control">
                                                    <option value="1"
                                                        {{ $departments->tax_status == 1 ? 'selected' : '' }}>Taxable
                                                    </option>
                                                    <option value="0"
                                                        {{ $departments->tax_status == 0 ? 'selected' : '' }}>Non-Taxable
                                                    </option>
                                                </select>
                                                @error('tax_status')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label> Image</label>
                                                <input type="file" name="image" value="{{ old('image') }}"
                                                    class="form-control">
                                                @error('image')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-footer text-center">
                                        <div>
                                            <button type="submit" class="btn btn-success mr-1 btn-bg"
                                                id="submit">Update</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                </div>
        </div>
        </form>
        </div>
        </section>
        </div>
    </body>
@endsection

@section('js')
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif

    {{-- <script>
    $('.addStoreBtn').click(function() {
        $('.store_names').append(
            '<div class="input-group mb-3"><input type="text" placeholder="Enter Department Name" name="department_name[]" class="form-control"><div class="input-group-append"><button type="button" class="btn btn-danger removeDepartmentBtn"><i class="fas fa-trash-alt"></i></button></div></div>'
        );
    });

    // If you want to remove a dynamically added department on the fly
    $(document).on('click', '.removeDepartmentBtn', function() {
        $(this).closest('.input-group').remove();
    });
</script>
@endsection --}}

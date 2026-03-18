{{-- @extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <body>
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                    <form id="add_student" action="{{ route('updateDepartments', $stores->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <h4 class="text-center my-4">Edit Department</h4>
                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-12 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Store Name</label>
                                                <input type="hidden" name="store_manager_id"
                                                    value="{{ $stores->user->id }}">
                                                <input type="text" placeholder="Store Name" name="store_name"
                                                    value="{{ $stores->store_name }}" class="form-control">
                                                @error('store_name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-12 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Department Name</label>
                                                <div class="store_names">
                                                    @foreach ($departments as $index => $department)
                                                        <div class="input-group mb-3"
                                                            data-department-id="{{ $department->id }}">
                                                            <input type="text" placeholder="Enter Department Name"
                                                                name="department_name[{{ $index }}]"
                                                                value="{{ $department->department_name }}"
                                                                class="form-control">
                                                            <div class="input-group-append">
                                                                <button type="button"
                                                                    class="btn btn-danger removeDepartmentBtn"><i
                                                                        class="fas fa-trash-alt"></i></button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    <button type="button" class="btn btn-primary mb-3 addStoreBtn"
                                                        id="addStoreBtn">+</button>
                                                </div>
                                                @error('department_name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror

                                            </div>
                                        </div>
                                        <div class="card-footer text-center row">
                                            <div>
                                                <button type="submit" class="btn btn-success mr-1 btn-bg"
                                                    id="submit">Update</button>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.addStoreBtn').click(function() {
                $('.store_names').append(
                    '<div class="input-group mb-3"><input type="text" placeholder="Enter Department Name" name="department_name[]" class="form-control"><div class="input-group-append"><button type="button" class="btn btn-danger removeDepartmentBtn"><i class="fas fa-trash-alt"></i></button></div></div>'
                );
            });

            $(document).on('click', '.removeDepartmentBtn', function(event) {
                event.preventDefault();
                var $this = $(this);
                var departmentId = $this.closest('.input-group').data('department-id');

                $.ajax({
                    url: '{{ route('store-manager.checkProducts', '') }}/' + departmentId,
                    method: 'GET',
                    success: function(response) {
                        if (response.hasProducts) {
                            swal({
                            // title: 'Cannot Delete Department',
                            text: 'Cannot delete department with products in Inventory.',
                            icon: 'warning',
                            button: 'OK'
                        });
                        } else {
                            // Remove the input group on successful confirmation
                            $this.closest('.input-group').remove();
                            Swal.fire(
                                'Deleted!',
                                'The department name has been deleted.',
                                'success'
                            );
                        }
                    }
                });

            });
        });
    </script>
@endsection --}}
{{-- ------------------------------------------------------------------------------------------ --}}

{{-- @extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <body>
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                    <form id="edit_department" action="{{ route('updateDepartments', $stores->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <h4 class="text-center my-4">Edit Department</h4>
                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-12 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Store Name</label>
                                                <input type="hidden" name="store_manager_id"
                                                    value="{{ $stores->user->id }}">
                                                <input type="text" placeholder="Store Name" name="store_name"
                                                    value="{{ $stores->store_name }}" class="form-control">
                                                @error('store_name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-12 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Department Name</label>
                                                <div class="store_names">
                                                    @foreach ($departments as $index => $department)
                                                        <div class="input-group mb-3" data-department-id="{{ $department->id }}">
                                                            <input type="text" placeholder="Enter Department Name"
                                                                name="department_name[{{ $index }}]"
                                                                value="{{ $department->department_name }}"
                                                                class="form-control">
                                                            <select name="tax_status[{{ $index }}]" class="form-control">
                                                                <option value="1" {{ $department->tax_status == 1 ? 'selected' : '' }}>Taxable</option>
                                                                <option value="0" {{ $department->tax_status == 0 ? 'selected' : '' }}>Non-Taxable</option>
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-danger removeDepartmentBtn"><i class="fas fa-trash-alt"></i></button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    <button type="button" class="btn btn-primary mb-3 addStoreBtn"
                                                        id="addStoreBtn">+</button>
                                                </div>
                                                @error('department_name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror

                                            </div>
                                        </div>
                                        <div class="card-footer text-center row">
                                            <div>
                                                <button type="submit" class="btn btn-success mr-1 btn-bg"
                                                    id="submit">Update</button>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.addStoreBtn').click(function() {
                const index = $('.store_names .input-group').length;
                $('.store_names').append(
                    '<div class="input-group mb-3"><input type="text" placeholder="Enter Department Name" name="department_name[' + index + ']" class="form-control"><select name="tax_status[' + index + ']" class="form-control"><option value="" disabled selected>Select Tax Status</option><option value="1">Taxable</option><option value="0">Non-Taxable</option></select><div class="input-group-append"><button type="button" class="btn btn-danger removeDepartmentBtn"><i class="fas fa-trash-alt"></i></button></div></div>'
                );
            });

            $(document).on('click', '.removeDepartmentBtn', function(event) {
                event.preventDefault();
                var $this = $(this);
                var departmentId = $this.closest('.input-group').data('department-id');

                $.ajax({
                    url: '{{ route('store-manager.checkProducts', '') }}/' + departmentId,
                    method: 'GET',
                    success: function(response) {
                        if (response.hasProducts) {
                            swal({
                                text: 'Cannot delete department with products in Inventory.',
                                icon: 'warning',
                                button: 'OK'
                            });
                        } else {
                            $this.closest('.input-group').remove();
                            Swal.fire(
                                'Deleted!',
                                'The department name has been deleted.',
                                'success'
                            );
                        }
                    }
                });
            });
        });
    </script>
@endsection --}}

@extends('admin.layout.app')
@section('title', 'Dashboard')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                <form id="edit_department" action="{{ route('updateDepartments', $stores->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <h4 class="text-center my-4">Edit Department</h4>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label>Store Name</label>
                                        <input type="hidden" name="store_manager_id" value="{{ $stores->user->id }}">
                                        <input type="text" placeholder="Store Name" name="store_name"
                                            value="{{ $stores->store_name }}" class="form-control">
                                        @error('store_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Departments</label>
                                        <div class="store_names">
                                            @foreach ($departments as $index => $department)
                                                <div class="input-group mb-3 row"
                                                    data-department-id="{{ $department->id }}">
                                                    <div class="col-md-3">
                                                        <input type="text" placeholder="Enter Department Name"
                                                            name="department_name[{{ $index }}]"
                                                            value="{{ $department->department_name }}" class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <select name="tax_status[{{ $index }}]" class="form-control">
                                                            <option value="1"
                                                                {{ $department->tax_status == 1 ? 'selected' : '' }}>Taxable
                                                            </option>
                                                            <option value="0"
                                                                {{ $department->tax_status == 0 ? 'selected' : '' }}>
                                                                Non-Taxable</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="file" name="image[{{ $index }}]"
                                                            class="form-control">
                                                    </div>
                                                    <div class="input-group-append col-md-3">
                                                        <button type="button" class="btn btn-danger removeDepartmentBtn">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <button type="button" class="btn btn-primary addStoreBtn">+</button>
                                        </div>
                                        @error('department_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="card-footer text-center">
                                        <button type="submit" class="btn btn-success" id="submit">Update</button>
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

    @section('js')
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // Handle adding new department fields dynamically
            $('.addStoreBtn').click(function() {
                const index = $('.store_names .input-group').length;
                $('.store_names').append(
                    '<div class="input-group mb-3 row" data-index="' + index + '">' +
                        '<div class="col-md-3">' +
                            '<input type="text" placeholder="Enter Department Name" name="department_name[' + index + ']" class="form-control">' +
                        '</div>' +
                        '<div class="col-md-3">' +
                            '<select name="tax_status[' + index + ']" class="form-control">' +
                                '<option value="" disabled selected>Select Tax Status</option>' +
                                '<option value="1">Taxable</option>' +
                                '<option value="0">Non-Taxable</option>' +
                            '</select>' +
                        '</div>' +
                        '<div class="col-md-3">' +
                            '<input type="file" name="image[' + index + ']" class="form-control">' +
                        '</div>' +
                        '<div class="col-md-3">' +
                            '<button type="button" class="btn btn-danger removeDepartmentBtn">' +
                                '<i class="fas fa-trash-alt"></i>' +
                            '</button>' +
                        '</div>' +
                    '</div>'
                );
            });

            // Handle removing department fields with AJAX check
            $(document).on('click', '.removeDepartmentBtn', function(event) {
                event.preventDefault();
                var $this = $(this);
                var departmentId = $this.closest('.input-group').data('department-id');

                if (departmentId) {
                    $.ajax({
                        url: '{{ route('store-manager.checkProducts', '') }}/' + departmentId,
                        method: 'GET',
                        success: function(response) {
                            if (response.hasProducts) {
                                swal({
                                    text: 'Cannot delete department with products in Inventory.',
                                    icon: 'warning',
                                    button: 'OK'
                                });
                            } else {
                                $this.closest('.input-group').remove();
                                Swal.fire(
                                    'Deleted!',
                                    'The department has been deleted.',
                                    'success'
                                );
                            }
                        }
                    });
                } else {
                    $this.closest('.input-group').remove();
                }
            });
        });
    </script>
@endsection

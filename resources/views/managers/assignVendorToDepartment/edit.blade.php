@extends('managers.layout.app')
@section('title', 'Edit Department Assignment')
@section('content')

<div class="main-content">
    <section class="section">
        <div class="section-body">
            <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
            <form id="edit_department" action="{{ route('manager.assignStoreManagerVendor-update', ['id' => $id, 'departmentId' => $departmentId]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <h4 class="text-center my-4">Edit Assigned Department For ({{ $vendor->vendor->vendor_name }})</h4>
                            <div class="row mx-0 px-4">
                                <div class="col-sm-12 pl-sm-0 pr-sm-3">
                                    <div class="form-group mb-2">
                                        <label>Department Name</label>
                                            <select class="form-control department-dropdown" name="department_id">
                                                <option value="" disabled>Select Department</option>
                                                @foreach ($specificStoreDepartments as $department)
                                                <option value="{{ $department->id }}" {{ (isset($departments->department_id) && $departments->department_id == $department->id) ? 'selected' : '' }}>
                                                    {{ $department->department_name }}
                                                </option>
                                            @endforeach
                                            </select>
                                            @error('department_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                    </div>
                                </div>
                                    <input type="hidden" name="vendor_id" value="{{$id}}">
                                </div>
                                <div class="card-footer text-center">
                                    <div class="col">
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

@endsection

@section('js')
@if (\Illuminate\Support\Facades\Session::has('message'))
    <script>
        toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
    </script>
@endif

<script>
    $(document).ready(function() {
        $('.department-dropdown').selectric();
    });
</script>

@endsection

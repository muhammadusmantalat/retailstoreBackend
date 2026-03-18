@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

<div class="main-content">
    <section class="section">
        <div class="section-body">
            <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
            <form id="add_department" action="{{ route('products-departments-store', [$storeManagerId, $storeId, $productId]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <h4 class="text-center my-4">Assign Department</h4>
                            <div class="row mx-0 px-4">
                                <!-- No need for a Store dropdown since the Store is determined by the route parameters -->
                                <input type="hidden" name="store" value="{{ $storeId }}">

                                <div class="col-12">
                                    <div class="form-group mb-2">
                                        <label for="department_id">Department<span class="text-danger">*</span></label>
                                        <select class="form-control department-dropdown" name="department[]" multiple>
                                            <option value="" disabled selected>Select Department</option>
                                            @foreach ($storeDepartments as $department)
                                                <option value="{{ $department->id }}">
                                                    {{ $department->department_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('department.*')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        @error('department')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
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
    toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}')

</script>
@endif
<script>
    $(document).ready(function() {
        $('.department-dropdown').selectric();
    });
</script>
@endsection

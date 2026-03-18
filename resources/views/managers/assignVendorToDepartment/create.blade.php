@extends('managers.layout.app')
@section('title', 'Dashboard')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                <form id="add_department" action="{{ route('manager.assignStoreManagerVendor-store',$id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Assign Department For ({{ $vendor->vendor->vendor_name }})</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-12 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Department Name<span class="text-danger">*</span></label>
                                            <select id="store-dropdown" class="form-control department-dropdown" name="department[]" multiple>
                                                <option value="" disabled selected>Select Department</option>
                                                @foreach ($departments as $department)
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
                                        <input type="hidden" name="store_manager_id" value="{{$authId}}">
                                        <input type="hidden" name="vendor_id" value="{{$id}}">
                                        <input type="hidden" name="store_id" value="{{$storeId}}">

                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="col">
                                            <button type="submit" class="btn btn-success mr-1 btn-bg"
                                                id="submit">Save</button>
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

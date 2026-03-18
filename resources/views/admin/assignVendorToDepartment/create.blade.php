@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                <form id="add_department" action="{{ route('vendor-departments-save',[$storeManagerId,$id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Assign Department</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="store_id">Stores<span class="text-danger">*</span></label>
                                            <select id="store-dropdown" class="form-control" name="store">
                                                <option value="" disabled selected>Select Store</option>
                                                @foreach ($storeManagers as $storeManager)
                                                    <option value="{{ $storeManager['store']['id'] }}">
                                                        {{ $storeManager['store']['store_name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('store')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label for="department_id">Department<span class="text-danger">*</span></label>
                                            <select class="form-control department-dropdown" name="department[]"
                                                multiple disabled>
                                                <option value="">Select Department</option>
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

                                <input type = "hidden" name ="assignVendorId" value = {{$storeManager->id}}>
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
       $('#store-dropdown').on('change', function() {
           var storeId = $(this).val();
        $('.department-dropdown').prop('disabled', false).html('<option value="" disabled selected>Select Department</option>');
        $.ajax({
            url: "{{ url('admin/get-departments') }}/" + storeId,
            type: "GET",
            dataType: 'json',
            success: function(result) {
                $.each(result.data, function(key, value) {
                    $(".department-dropdown").append('<option value="' + value.id + '">' + value.department_name + '</option>');
                });
                // Reinitialize Selectric here after updating the department dropdown
                $('.department-dropdown').selectric('refresh');
            }
        });
    });
    </script>


@endsection

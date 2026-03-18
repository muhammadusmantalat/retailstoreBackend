@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                <form id="add_department" action="{{ route('assignProducts-store', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Asign Store Manager & Stores</h4>

                                    <div class="row mx-0 px-4 align-items-start">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Store Managers<span class="text-danger">*</span></label>
                                                <select class="form-control store-manager-dropdown" name="storeManager"
                                                    value="">
                                                    <option value="" disabled selected>Select Store Manager</option>
                                                    @foreach ($storeManagers as $storeManager)
                                                        <option value="{{ $storeManager->id }}">
                                                            {{ $storeManager->first_name }}
                                                            {{ $storeManager->last_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('storeManager')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label for="store_id">Stores<span class="text-danger">*</span></label>
                                                <select class="form-control store-dropdown" name="store[]" multiple disabled >
                                                    <option value="" >Select Store</option>
                                                </select>
                                                @error('store.*')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                @error('store')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            </div>
                                        </div>
                                    </div>
                                <input type="hidden" name="product_id" value="{{$product->id}}">
                                <div class="card-footer">
                                    <div class="col">
                                        <button type="submit" class="btn btn-success mr-1 btn-bg"
                                            id="submit">Save</button>
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
            $('.store-manager-dropdown').on('change', function() {
                var storeManager_id = $(this).val();
                // Enable the store dropdown
                $('.store-dropdown').prop('disabled', false);

                // Clear options in store and department dropdowns
                $('.store-dropdown').html('<option value="" disabled selected>Select Store</option>');

                // Immediately disable the department dropdown when a store manager is changed
                $('.department-dropdown').prop('disabled', true).html(
                    '<option value="" disabled selected>Select Department</option>').selectric(
                    'refresh');

                // AJAX request to fetch stores based on selected store manager
                $.ajax({
                    url: "{{ url('admin/get-store-product') }}/" + storeManager_id,
                    type: "GET",
                    dataType: 'json',
                    success: function(result) {
                        $.each(result.data, function(key, value) {
                            $(".store-dropdown").append(
                                '<option value="' + value.id + '">' + value
                                .store_name + '</option>');
                        });
                        // Refresh Selectric for store-dropdown to reflect the new options
                        $('.store-dropdown').selectric('refresh');
                    }
                });
            });

            // Initialize Selectric for store and department dropdowns initially
            $('.store-dropdown, .department-dropdown').selectric({
                // any specific Selectric options you want to apply
            });
        });
    </script>
@endsection

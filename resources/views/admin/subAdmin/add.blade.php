@extends('admin.layout.app')
@section('title', 'Student Add')
@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <form id="add_subadmin" action="{{ url('/admin/add-subadmin/') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Subadmin Registration</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-2">
                                        <div class="form-group mb-2">
                                            <label>Name<span class="text-danger">*</span></label>
                                            <input type="text" name="name" value="{{ old('name') }}"
                                                class="form-control" placeholder="Name">
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-2">
                                        <div class="form-group mb-2">
                                            <label>Email<span class="text-danger">*</span></label>
                                            <input type="email" name="email" value="{{ old('email') }}"
                                                class="form-control" placeholder="Example@gmail.com">
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-2">
                                        <div class="form-group mb-2">
                                            <label>Phone<span class="text-danger">*</span></label>
                                            <input type="tel" placeholder="Enter Vendor Phone No" name="phone_no"
                                            id="phone_no" value="{{ old('phone_no') }}" class="form-control">
                                        @error('phone_no')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-2">
                                        <div class="form-group mb-2">
                                            <label>Image </label>
                                            <input type="file" name="image" value="{{ old('image') }}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <input type="hidden" name="user_type" value="subadmin" id="">
                                </div>
                                <div class="card-footer text-center row">
                                    <div class="col">
                                        <button type="submit" class="btn btn-success mr-1 btn-bg">Save</button>
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
    <script>
        $(document).ready(function() {
            $('#table_id').DataTable();

        });
    </script>
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var phoneInput = document.getElementById('phone_no');

            phoneInput.addEventListener('input', function(e) {
                var x = phoneInput.value.replace(/\D/g, '').match(/(\d{1})(\d{0,3})(\d{0,3})(\d{0,4})/);
                phoneInput.value = '+1 ' + (x[2] ? x[2] : '') + (x[3] ? ' ' + x[3] : '') + (x[4] ? ' ' + x[
                    4] : '');
            });

            // Ensure the field is cleared of any invalid characters on blur
            phoneInput.addEventListener('blur', function() {
                var x = phoneInput.value.replace(/\D/g, '').match(/(\d{1})(\d{3})(\d{3})(\d{4})/);
                phoneInput.value = x ? '+1 ' + x[2] + ' ' + x[3] + ' ' + x[4] : '';
            });
        });
    </script>
@endsection

@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                <form id="add_student" action="{{route('store-manager.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Add Store Manager</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>First Name<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="First Name" name="first_name"
                                                id="first_name" value="{{ old('first_name') }}" class="form-control">
                                            @error('first_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Last Name<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Last Name" name="last_name"
                                                id="last_name" value="{{ old('last_name') }}" class="form-control">
                                            @error('last_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Email<span class="text-danger">*</span></label>
                                            <input type="email" placeholder="Email" name="email" id="email"
                                                value="{{ old('email') }}" class="form-control">
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Choose Image</label>
                                            <input type="file" name="image" value="{{ old('image') }}"
                                                class="form-control">
                                            @error('image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <input type="hidden" name="user_type" value="store_Manager" id="">
                                </div>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Phone Number<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Phone" name="phone_no" id="phone_no"
                                                value="{{ old('phone_no') }}" class="form-control" />
                                            @error('phone_no')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Address<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Address" name="address"
                                                id="address" value="{{ old('address') }}"
                                                class="form-control" />
                                            @error('address')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
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

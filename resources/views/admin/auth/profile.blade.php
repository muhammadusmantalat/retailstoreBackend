{{-- @extends('admin.layout.app')
@section('title', 'Profile')
@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="row mt-sm-4">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="padding-20">
                                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#about" role="tab"
                                            aria-selected="false">About</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" id="profile-tab2" data-toggle="tab" href="#settings"
                                            role="tab" aria-selected="true">Setting</a>
                                    </li>
                                </ul>
                                <div class="tab-content tab-bordered" id="myTab3Content">
                                    <div class="tab-pane fade" id="about" role="tabpanel" aria-labelledby="home-tab2">
                                        <div class="row mx-0 pt-3">
                                            <div class="col-md-3 col-6 b-r">
                                                <strong>Full Name</strong>
                                                <br>
                                                @if ($data)
                                                <p class="text-muted">{{ $data->name }}</p>
                                                @else
                                                <p class="text-muted">{{$subAdmin->first_name }}</p>
                                                @endif
                                            </div>
                                            <div class="col-md-3 col-6 b-r">
                                                <strong>Mobile</strong>
                                                <br>
                                                @if ($data)
                                                <p class="text-muted">{{ $data->phone }}</p>
                                                 @else
                                                <p class="text-muted">{{$subAdmin->phone }}</p>
                                                @endif
                                            </div>
                                            <div class="col-md-3 col-6 b-r">
                                                <strong>Email</strong>
                                                <br>
                                                 @if ($data)
                                                <p class="text-muted">{{ $data->email }}</p>
                                                 @else
                                                <p class="text-muted">{{$subAdmin->email }}</p>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="tab-pane fade active show" id="settings" role="tabpanel"
                                        aria-labelledby="profile-tab2">
                                        <form method="post" action="{{ url('admin/update-profile') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="card-header">
                                                <h4>Edit Profile</h4>
                                            </div>

                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Name</label>
                                                        <input type="text" placeholder="Name" name="name" value="{{ $data->name ?? $subAdmin->first_name}}"
                                                            class="form-control">
                                                        @error('name')
                                                            <div class="text-danger">
                                                                Please fill in the Name
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Email</label>
                                                        <input type="email" placeholder="example@gmail.com" name="email" value="{{ $data->email ?? $subAdmin->email }}"
                                                            class="form-control">
                                                        @error('email')
                                                            <div class="text-danger">
                                                                Please fill in the email
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Profile Image</label>
                                                        <div class="custom-file">
                                                            <input type="file" name="image" class="custom-file-input"
                                                                id="customFile">
                                                            <label class="custom-file-label" for="customFile">Choose
                                                                file</label>
                                                        </div>

                                                        <div class="invalid-feedback">

                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Phone</label>
                                                        <input type="tel" name="phone" placeholder="92XXXXXXXXXX (Mobile Number)" value="{{ $data->phone ?? $subAdmin->phone }}"
                                                            class="form-control" value="">
                                                        @error('phone')
                                                            <div class="text-danger">
                                                                Please fill in the email
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer text-right">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
@endsection --}}

@extends('admin.layout.app')
@section('title', 'Profile')
@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="row mt-sm-4">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="padding-20">
                                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#about" role="tab"
                                            aria-selected="false">About</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" id="profile-tab2" data-toggle="tab" href="#settings"
                                            role="tab" aria-selected="true">Setting</a>
                                    </li>
                                </ul>
                                <div class="tab-content tab-bordered" id="myTab3Content">
                                    <div class="tab-pane fade" id="about" role="tabpanel" aria-labelledby="home-tab2">
                                        <div class="row mx-0 pt-3">
                                            <div class="col-md-3 col-6 b-r">
                                                <strong>Full Name</strong>
                                                <br>
                                                @if ($data)
                                                    <p class="text-muted">{{ $data->name }}</p>
                                                @else
                                                    <p class="text-muted">{{ $subAdmin->first_name }}</p>
                                                @endif
                                            </div>
                                            <div class="col-md-3 col-6 b-r">
                                                <strong>Mobile</strong>
                                                <br>
                                                @if ($data)
                                                    <p class="text-muted">{{ $data->phone }}</p>
                                                @else
                                                    <p class="text-muted">{{ $subAdmin->phone }}</p>
                                                @endif
                                            </div>
                                            <div class="col-md-3 col-6 b-r">
                                                <strong>Email</strong>
                                                <br>
                                                @if ($data)
                                                    <p class="text-muted">{{ $data->email }}</p>
                                                @else
                                                    <p class="text-muted">{{ $subAdmin->email }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade active show" id="settings" role="tabpanel"
                                        aria-labelledby="profile-tab2">
                                        <form method="post" action="{{ url('admin/update-profile') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="card-header">
                                                <h4>Edit Profile</h4>
                                            </div>
                                            {{--                                        @if (\Illuminate\Support\Facades\Session::has('errors')) --}}
                                            {{--                                            {{dd($errors)}} --}}
                                            {{--                                            @endif --}}
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Name</label>
                                                        <input type="text" placeholder="Name" name="name"
                                                            value="{{ $data->name ?? $subAdmin->first_name }}"
                                                            class="form-control">
                                                        @error('name')
                                                            <div class="text-danger">
                                                                Please fill in the Name
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Email</label>
                                                        <input type="email" placeholder="example@gmail.com" name="email"
                                                            value="{{ $data->email ?? $subAdmin->email }}"
                                                            class="form-control">
                                                        @error('email')
                                                            <div class="text-danger">
                                                                Please fill in the email
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Phone</label>
                                                        <input type="tel" name="phone" id="phone"
                                                            placeholder="admin phone number"
                                                            value="{{ $data->phone ?? $subAdmin->phone }}"
                                                            class="form-control" value="">
                                                        @error('phone')
                                                            <div class="text-danger">
                                                                Please fill in the phone number
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer text-right">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
            var phoneInput = document.getElementById('phone');

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

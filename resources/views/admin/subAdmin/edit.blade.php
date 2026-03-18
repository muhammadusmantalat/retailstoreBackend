@extends('admin.layout.app')
@section('title', 'Subadmin Add')
@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <form id="add_subadmin" action="{{ url('/admin/subadmin-update/') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Edit Subadmin</h4>
                                <div class="row mx-0 px-4">
                                    <input type="hidden" name="id" value="{{ $data->id }}" id="">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-2">
                                        <div class="form-group mb-2">
                                            <label>Name </label>
                                            <input type="text" name="name" value="{{ $data->first_name }}"
                                                class="form-control" placeholder="Name">
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-2">
                                        <div class="form-group mb-2">
                                            <label>Email </label>
                                            <input type="email" name="email" value="{{ $data->email }}"
                                                class="form-control" placeholder="example@gmail.com" disabled>
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-2">
                                        <div class="form-group mb-2">
                                            <label>Phone </label>
                                            <input type="text" placeholder="Enter Mobile Number" name="phone_no"
                                                id="phone_no" value="{{ $data->phone }}" class="form-control">
                                            @error('phone_no')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-2">
                                        <div class="form-group mb-2">
                                            <label>Image</label>
                                            <input type="file" name="image" value="{{ old('image') }}"
                                                class="form-control">
                                        </div>
                                    </div>
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

            function formatPhoneNumber(value) {
                var cleaned = ('' + value).replace(/\D/g, '');
                var match = cleaned.match(/^(\d{1})(\d{0,3})(\d{0,3})(\d{0,4})$/);
                if (match) {
                    return '+1 ' + (match[2] ? match[2] : '') + (match[3] ? ' ' + match[3] : '') + (match[4] ? ' ' + match[4] : '');
                }
                return value;
            }

            function enforceMaxLength(value) {
                var cleaned = ('' + value).replace(/\D/g, '');
                if (cleaned.length > 11) {
                    cleaned = cleaned.slice(0, 11);
                }
                return cleaned;
            }

            phoneInput.addEventListener('input', function(e) {
                var cleanedValue = enforceMaxLength(phoneInput.value);
                phoneInput.value = formatPhoneNumber(cleanedValue);
            });

            phoneInput.addEventListener('blur', function() {
                var cleanedValue = enforceMaxLength(phoneInput.value);
                var cleaned = cleanedValue.replace(/\D/g, '');
                var match = cleaned.match(/^(\d{1})(\d{3})(\d{3})(\d{4})$/);
                if (match) {
                    phoneInput.value = '+1 ' + match[2] + ' ' + match[3] + ' ' + match[4];
                } else {
                    phoneInput.value = formatPhoneNumber(cleanedValue);
                }
            });

            // Initial format on page load
            phoneInput.value = formatPhoneNumber(phoneInput.value);
        });
    </script>
@endsection

@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <body>
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                    <form id="add_student" action="{{route('store-manager.update' , $storeManager->id)}}" method="POST"
                        enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <h4 class="text-center my-4">Edit Store Manager</h4>
                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>First Name</label>
                                                <input type="text" placeholder="First Name" name="first_name"
                                                    id="first_name" value="{{ $storeManager->first_name }}" class="form-control">
                                                @error('first_name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Last Name</label>
                                                <input type="text" placeholder="Last Name" name="last_name"
                                                    id="last_name" value="{{ $storeManager->last_name }}" class="form-control">
                                                @error('last_name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Email</label>
                                                <input type="email" placeholder="Email" name="email"
                                                    id="email" value="{{ $storeManager->email }}" class="form-control" readonly>
                                                @error('email')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Choose Image</label>
                                                <input type="file" name="image" class="form-control">
                                                @error('image')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                </div>
                                            </div>
                                    </div>
                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Phone</label>
                                                <input type="text" placeholder="Phone" name="phone_no" id="phone_no"
                                                    value="{{ $storeManager->phone }}" class="form-control" />
                                            </div>
                                            @error('phone_no')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Address</label>
                                                <input type="text" placeholder="Address" name="address"
                                                    id="address" value="{{ $storeManager->address }}"
                                                    class="form-control" />
                                            </div>
                                            @error('address')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="card-footer text-center row">
                                        <div class="col">
                                            <button type="submit" class="btn btn-success mr-1 btn-bg"
                                                id="submit">Update</button>
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

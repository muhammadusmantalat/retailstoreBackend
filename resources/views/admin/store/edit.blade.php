{{-- @extends('admin.layout.app')
@section('title', 'Edit Store')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ route('store-detail.index') }}">Back</a>
                <form id="edit_store" action="{{ route('store-detail.update', $storeNames->first()->storeManger_id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Edit Store</h4>
                                <div class="row px-4" id="storeNameContainer">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3 store_names" id="store_names">
                                        <div class="form-group mb-2 ">
                                            @foreach ($storeNames as $index => $store)
                                                <div class="input-group mb-3">
                                                    <input type="text" placeholder="Enter Store Name"
                                                        name="store_name[{{ $index }}]"
                                                        value="{{ $store->store_name }}" class="form-control">

                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-danger removeStoreBtn"><i
                                                                class="fas fa-trash-alt"></i></button>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @error('store_name.*')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror

                                                <button type="button" class="btn btn-primary addStoreBtn"
                                                    id="addStoreBtn">+</button>
                                        </div>
                                        <div id="sizeInputs"></div>
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
@endsection

@section('js')
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $('.addStoreBtn').click(function() {
                $('.store_names').append(
                    '<div class="input-group mb-3"><input type="text" placeholder="Enter Store Name" name="store_name[]" class="form-control"><div class="input-group-append"><button type="button" class="btn btn-danger removeStoreBtn"><i class="fas fa-trash-alt"></i></button></div></div>'
                );
            });

            // $(document).on('click', '.removeStoreBtn', function() {
            //     $(this).closest('.input-group').remove();
            // });


        });

        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(document).on('click', '.removeStoreBtn', function() {
            alert()
            event.preventDefault();
            var form = $(this).closest("form");
            var managerId = form.attr('action').split('/').pop();


            $.ajax({
                url: '{{ route('store-manager.checkdepartments', '') }}/' + managerId,
                method: 'GET',
                success: function(response) {
                    if (response.hasStores) {
                        // Show modal if there are associated stores
                        swal({
                            title: 'Cannot Delete Store Manager',
                            text: 'This store manager has associated stores and cannot be deleted.',
                            icon: 'warning',
                            button: 'OK'
                        });
                    } else {
                        // Proceed with the deletion confirmation
                        swal({
                            title: 'Are you sure you want to delete this record?',
                            text: 'If you delete this, it will be gone forever.',
                            icon: 'warning',
                            buttons: true,
                            dangerMode: true,
                        }).then((willDelete) => {
                            if (willDelete) {
                                form.submit();
                            }
                        });
                    }
                }
            });
        });

    </script>
@endsection --}}


@extends('admin.layout.app')
@section('title', 'Edit Store')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ route('store-detail.index') }}">Back</a>
                <form id="edit_store" action="{{ route('store-detail.update', $storeNames->first()->storeManger_id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Edit Store</h4>
                                <div class="row px-4" id="storeNameContainer">
                                    <div class="col-sm-12 pl-sm-0 pr-sm-3 store_names" id="store_names">
                                        <div class="form-group mb-2">
                                            @foreach ($storeNames as $index => $store)
                                                <div class="input-group mb-3" data-store-id="{{ $store->id }}">
                                                    <input type="text" placeholder="Enter Store Name"
                                                        name="store_name[{{ $index }}]"
                                                        value="{{ $store->store_name }}" class="form-control">
                                                    <input type="hidden" name="store_id[{{ $index }}]"
                                                        value="{{ $store->id }}">
                                                    <input type="text" placeholder="Enter Store Address"
                                                        name="store_address[{{ $index }}]"
                                                        value="{{ $store->store_address }}" class="form-control ml-2">

                                                    <input type="text" placeholder="Enter Phone Number"
                                                        name="store_phone_no[{{ $index }}]"
                                                        value="{{ $store->store_phone_no }}"
                                                        class="form-control ml-2 store_phone">

                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-danger removeStoreBtn"><i
                                                                class="fas fa-trash-alt"></i></button>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @error('store_name.*')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <button type="button" class="btn btn-primary addStoreBtn"
                                                id="addStoreBtn">+</button>
                                        </div>
                                        <div id="sizeInputs"></div>
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
@endsection

@section('js')
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif

    <!-- Include SweetAlert library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            // Add new store fields dynamically
            $('.addStoreBtn').click(function() {
                $('.store_names').append(
                    '<div class="input-group mb-3">' +
                    '<input type="text" placeholder="Enter Store Name" name="store_name[]" class="form-control">' +
                    '<input type="text" placeholder="Enter Store Address" name="store_address[]" class="form-control ml-2">' +
                    '<input type="text" placeholder="Enter Phone Number" name="store_phone_no[]" class="form-control ml-2 store_phone">' +
                    '<div class="input-group-append"><button type="button" class="btn btn-danger removeStoreBtn"><i class="fas fa-trash-alt"></i></button></div>' +
                    '</div>'
                );
            });

            $(document).on('click', '.removeStoreBtn', function(event) {
                event.preventDefault();
                var $this = $(this);
                var storeId = $this.closest('.input-group').data('store-id');

                $.ajax({
                    url: '{{ route('store-manager.checkDepartments', '') }}/' + storeId,
                    method: 'GET',
                    success: function(response) {
                        if (response.hasStores) {
                            // Show modal if there are associated stores
                            swal({
                                // title: 'Cannot Delete Store',
                                text: 'Cannot delete store with departments in Inventory.',
                                icon: 'warning',
                                button: 'OK'
                            });
                        } else {
                            // Remove the input group on successful confirmation
                            $this.closest('.input-group').remove();
                            Swal.fire(
                                'Deleted!',
                                'The store name has been deleted.',
                                'success'
                            );
                        }
                    }
                });

            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
                    var phoneInputs = document.querySelectorAll('.store_phone');

                    function formatPhoneNumber(value) {
                        var cleaned = ('' + value).replace(/\D/g, '');
                        var match = cleaned.match(/^(\d{1})(\d{0,3})(\d{0,3})(\d{0,4})$/);
                        if (match) {
                            return '+1 ' + (match[2] ? match[2] : '') + (match[3] ? ' ' + match[3] : '') + (match[4] ? ' ' +
                                match[4] : '');
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

                    phoneInputs.forEach(function(phoneInput) {
                        phoneInput.addEventListener('input', function() {
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
                    $(document).ready(function() {
                        // Function to format phone numbers
                        function formatPhoneNumber(value) {
                            var cleaned = ('' + value).replace(/\D/g, '');
                            var match = cleaned.match(/^(\d{1})(\d{0,3})(\d{0,3})(\d{0,4})$/);
                            if (match) {
                                return '+1 ' + (match[2] ? match[2] : '') + (match[3] ? ' ' + match[3] : '') + (
                                    match[4] ? ' ' + match[4] : '');
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

                        // Apply formatting to phone number input fields
                        function applyPhoneFormatting(phoneInput) {
                            $(phoneInput).on('input', function() {
                                var cleanedValue = enforceMaxLength($(this).val());
                                $(this).val(formatPhoneNumber(cleanedValue));
                            });

                            $(phoneInput).on('blur', function() {
                                var cleanedValue = enforceMaxLength($(this).val());
                                var cleaned = cleanedValue.replace(/\D/g, '');
                                var match = cleaned.match(/^(\d{1})(\d{3})(\d{3})(\d{4})$/);
                                if (match) {
                                    $(this).val('+1 ' + match[2] + ' ' + match[3] + ' ' + match[4]);
                                } else {
                                    $(this).val(formatPhoneNumber(cleanedValue));
                                }
                            });
                        }

                        // Trigger phone formatting on dynamically added fields after they are appended
                        $('.addStoreBtn').click(function() {
                            // Apply phone number formatting to the newly added .store_phone field
                            $('.store_names').find('.store_phone').each(function() {
                                if (!$(this).data(
                                    'formatted')) { // Check if formatting hasn't been applied
                                    applyPhoneFormatting(this);
                                    $(this).data('formatted', true); // Mark as formatted
                                }
                            });
                        });

                        // Apply phone formatting to existing fields on page load
                        applyPhoneFormatting($('.store_phone'));
                    });
                });
        
    </script>
@endsection

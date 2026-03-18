@extends('admin.layout.app')
@section('title', 'Edit Vendor Assignment')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                <form id="edit_assignment" action="{{ route('vendor-assign-update', $storeManagerId) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            @foreach ($assignments as $assignment)
                                <div class="card my-4">
                                    <div class="card-body">
                                        {{-- <h5 class="card-title">Edit Assignment for Store: {{$assignment->store->store_name ?? 'N/A'}}</h5> --}}
                                        <div class="form-group">
                                            <label for="store_id_{{ $assignment->id }}">Store Name</label>
                                            <div class="d-flex justify-content-between">
                                                <select style="width: calc(100% - 40px)"
                                                    name="assignments[{{ $assignment->id }}][store_id]"
                                                    id="store_id_{{ $assignment->id }}" class="form-control">
                                                    <option value="">Select Store</option>
                                                    @foreach ($stores as $store)
                                                        <option value="{{ $store->id }}"
                                                            @if ($store->id == $assignment->store_id) selected @endif>
                                                            {{ $store->store_name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="button" style="width:35px"
                                                    class="btn btn-danger delete-assignment p-0 d-flex justify-content-center align-items-center"
                                                    data-id="{{ $assignment->id }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- You can add more fields here as per your assignment details --}}
                                    </div>
                                </div>
                            @endforeach
                            </select>
                            @error('store_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <input type="hidden" name="vendor_id" value="{{ $id }}">
                        <div class="card-footer">
                            <div class="col">
                                <button type="submit" class="btn btn-success mr-1 btn-bg" id="submit">Update</button>
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
            $('.delete-assignment').click(function() {
                var id = $(this).data('id');
                var vendorId = $('input[name="vendor_id"]').val();
                if (confirm('Are you sure you want to delete this Store?')) {
                    $.ajax({
                        type: "DELETE", // Use 'DELETE' for deletion operations
                        dataType: "json",
                        headers: {
                            'X-CSRF-Token': '{{ csrf_token() }}',
                        },
                        url: "/admin/vendors-assign-destroy", // Use a relative URL
                        // url:"{{ url('admin/vendors-assign-delete') }}",
                        data: {
                            'id': id,
                            'vendor_id': vendorId, // Include vendor_id in the request
                        },
                        success: function(data) {
                            toastr.success('Store Deleted Successfully!');
                            window.location.href =
                                "/admin/vendors-assign/" + vendorId;
                        },
                    });
                }
            });
        });
    </script>
@endsection

@extends('managers.layout.app')
@section('title', 'index')
@section('content')
<style>
    .non-editable-checkbox {
    pointer-events: none; /* Prevent user interaction */
    opacity: 1; /* Keep the normal appearance */
}
</style>
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Wholesalers</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <a class="btn btn-success mb-3" href="{{ route('manager.storeManagerVendor-add') }}">Add
                                    Wholesaler</a>
                                {{-- <a class="btn btn-info mb-3" href="{{ route('manager.vendors-uploadForm') }}">Upload Bulk
                                        Products</a> --}}
                                        <table class="responsive table table-bordered table-striped"n id="table-1">
                                            <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Wholesaler Name</th>
                                            <th>Wholesaler Email</th>
                                            <th>Wholesaler Phone Number</th>
                                            <th>Overcharged Price</th>
                                            <th>General Discount (%)</th>
                                            <th>Salesman Name</th>
                                            <th>Salesman Email</th>
                                            <th>Salesman Phone Number</th>
                                            <th>Order Frequency</th>
                                            <th>Weekly Order Days</th>
                                            <th>Delivery Frequency</th>
                                            <th>Weekly Delivery Days</th>
                                            {{-- <th>Image</th> --}}
                                            <th>Departments</th>
                                            {{-- <th>Status</th> --}}
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vendors as $vendor)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $vendor->vendor?->vendor_name }}</td>
                                                <td>
                                                    @if ($vendor->vendor?->email)
                                                        <a href="mailto:{{ $vendor->vendor->email }}">
                                                            {{ $vendor->vendor->email }}
                                                        </a>
                                                    @endif
                                                </td>

                                                <td>{{ $vendor->vendor?->phone_no }}</td>

                                                <td>
                                                    <input type="checkbox"
                                                        class="non-editable-checkbox"
                                                        {{ $vendor->vendor?->overcharged_prices == 1 ? 'checked' : '' }} disabled>
                                                </td>
                                                <td>{{ optional(optional($vendor->vendor)->discount)->general_discount ?? 'Not Set' }}</td>
                                                <td>{{ optional(optional($vendor->vendor)->salesMen)->sales_manager_name ?? 'Not Set' }}</td>
                                                <td>{{ optional(optional($vendor->vendor)->salesMen)->sales_manager_email ?? 'Not Set' }}</td>
                                                <td>{{ optional(optional($vendor->vendor)->salesMen)->sales_manager_phone_no ?? 'Not Set' }}</td>
                                                <td>{{ $frequencies[optional(optional($vendor->vendor)->salesMen)->order_frequency] ?? 'Not Set' }}</td>
                                                <td>{{ optional(optional($vendor->vendor)->salesMen)->order_dates ?? 'Not Set' }}</td>
                                                <td>{{ $frequencies[optional(optional($vendor->vendor)->salesMen)->delivery_frequency] ?? 'Not Set' }}</td>
                                                <td>{{ optional(optional($vendor->vendor)->salesMen)->delivery_days ?? 'Not Set' }}</td>
                                                {{-- <td>
                                                    <img src="{{ asset($vendor->vendor->image) }}" alt=""
                                                        height="50" width="50" class="image">
                                                </td> --}}
                                                <td>
                                                    <a class="btn btn-success"
                                                        href="{{ route('manager.assignStoreManagerVendor', $vendor->vendor->id) }}">Assign</a>
                                                </td>

 
                                                <td>
                                                    <div class="d-flex gap-4">
                                                        <a href="{{ route('manager.storeManagerVendor-edit', $vendor->vendor->id) }}"
                                                            class="btn btn-primary">Edit</a>
                                                        {{-- <form
                                                            action="{{ route('manager.storeManagerVendor-delete', $vendor->vendor->id) }}"
                                                            method="POST" style="display:inline-block; margin-left: 10px">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn  btn-danger btn-flat show_confirm"
                                                                data-toggle="tooltip">Delete</button>
                                                        </form> --}}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
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
        $(document).ready(function() {
            $('#table-1').DataTable()

        })
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">
        $('.show_confirm').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                    title: `Are you sure you want to delete this record?`,
                    text: "If you delete this, it will be gone forever.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
        });
    </script>
@endsection

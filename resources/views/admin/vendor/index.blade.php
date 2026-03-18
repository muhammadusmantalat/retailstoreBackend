@extends('admin.layout.app')
@section('title', 'index')
@section('content')
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
                                    <a class="btn btn-success mb-3" href="{{ route('vendors-create') }}">Add Wholesaler</a>
                                    <table class="responsive table table-bordered table-striped" id="table-1">
                                        <thead>
                                            <tr>
                                                <th>Sr.</th>
                                                <th>Wholesaler Name</th>
                                                <th>Wholesaler Email</th>
                                                <th>Wholesaler Phone Number</th>
                                                {{-- <th>General Discount (%)</th> --}}
                                                {{-- <th>Order Frequency</th>
                                                <th>Weekly Order Dates</th>
                                                <th>Delivery Frequency</th>
                                                <th>Weekly Delivery Dates</th> --}}
                                                {{-- <th>Salesman Name</th>
                                                <th>Salesman Phone Number</th> --}}
                                                {{-- <th>Image</th> --}}
                                                <th>Store Managers</th>
                                                <th>Overcharged Price</th>
                                                <th>Overcharged By</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($vendors as $vendor)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $vendor->vendor_name }}</td>
                                                    {{-- <td>{{ $vendor->vendor_email }}</td> --}}
                                                    <td>
                                                        @if ($vendor->email)
                                                            <a href="mailto:{{ $vendor->email }}">{{ $vendor->email }}</a>
                                                        @endif
                                                    </td>
                                                    <td>{{ $vendor->phone_no }}</td>
                                                    {{-- <td>{{ $vendor->general_discount }}</td>
                                                    <td>{{ $frequencies[$vendor->order_frequency] ?? 'Not Set'}}</td>
                                                    <td>{{ $vendor->order_dates }}</td>
                                                    <td>{{ $frequencies[$vendor->delivery_frequency] ?? 'Not Set' }}</td>
                                                    <td>{{ $vendor->delivery_days }}</td> --}}
                                                    {{-- <td>{{ $vendor->salesman_name }}</td>
                                                    <td>{{ $vendor->salesman_phone_no }}</td> --}}
                                                    {{-- <td>
                                                        <img src="{{ asset($vendor->image) }}" alt=""
                                                            height="50" width="50" class="image">
                                                    </td> --}}
                                                    {{-- <td>
                                                    <a class="btn btn-primary"
                                                        href="{{ route('products-assignVendor', $vendor->id )}}">Assign</a>
                                                </td> --}}
                                                    <td>
                                                        <a class="btn btn-success"
                                                            href="{{ route('vendor-assign', $vendor->id) }}">Assign</a>
                                                    </td>
                                                    <td>
                                                        @if ($vendor->overcharged_prices == 1)
                                                            <input type="checkbox" checked disabled>
                                                        @else
                                                            <input type="checkbox" disabled>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $email = App\Models\User::where('id', $vendor->over_charged_by)->first();
                                                        @endphp
                                                        @if($email && $email->email)
                                                            <a href="mailto:{{ $email->email }}">{{ $email->email }}</a>
                                                            @else
                                                                N/A
                                                            @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-4">
                                                            <a href="{{ route('vendor-edit', $vendor->id) }}"
                                                                class="btn btn-primary">Edit</a>
                                                            <form action="{{ route('vendor-destroy', $vendor->id) }}"
                                                                method="POST"
                                                                style="display:inline-block; margin-left: 10px">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn  btn-danger btn-flat show_confirm"
                                                                    data-toggle="tooltip">Delete</button>
                                                            </form>
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
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script>
   $(document).on('click', '.show_confirm', function(event){
    event.preventDefault();
    var form = $(this).closest("form");

            swal({
                title: "Are you sure you want to delete this record?",
                text: "Deleting this wholesaler will permanently remove all records associated with them.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    form.submit();
                }
            });
});
</script>
@endsection

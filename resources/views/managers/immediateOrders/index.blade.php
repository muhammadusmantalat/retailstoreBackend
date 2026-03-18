@extends('managers.layout.app')
@section('title', 'index')
@section('content')
    <style>
        .pdf-icon i {
            font-size: 50px;
            /* Adjust the size as needed */
            color: red;
            /* Optional: Change the color of the icon */
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
                                    <h4>Immediate Orders</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <a class="btn btn-success mb-3"
                                    href="{{ route('manager.immediateOrder.create') }}">Add Immediate Order</a>
                                <table class="responsive table table-bordered table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Vendor Name</th>
                                            <th>Vendor Image</th>
                                            <th>Delivery Date</th>
                                            <th>Status</th>
                                            <th>Check Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($imidateOrders as $imidateOrder)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $imidateOrder->vendor_name }}</td>
                                                <td>
                                                    <a href="{{ asset($imidateOrder->vendor_recepit) }}" download>
                                                        <img src="{{ asset($imidateOrder->vendor_recepit) }}" alt="" height="50" width="50" class="image">
                                                    </a>
                                                </td>
                                                <td>{{ $imidateOrder->order_date ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="status-label badge {{ $imidateOrder->order_status == '0' ? 'badge-danger' : ($imidateOrder->order_status == '1' ? 'badge-success' : '') }}">
                                                        {{ $imidateOrder->order_status == '1' ? 'Completed' : 'Pending' }}
                                                    </span>
                                                </td>



                                                <td>
                                                    <form action="{{ route('manager.immediateSignAllChecked', $imidateOrder->id) }}"
                                                        method="GET" style="display: inline;">
                                                        <button type="submit" class="btn btn-warning check-order-btn">
                                                            Check Delivery
                                                        </button>
                                                    </form>
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
            $('#table_id_events').DataTable()

        })
    </script>

@endsection

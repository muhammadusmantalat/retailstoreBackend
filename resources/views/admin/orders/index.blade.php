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
                                    <h4>Orders</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="responsive table table-bordered table-striped"id="table-1">                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Store Manager Name</th>
                                            <th>Store Name</th>
                                            <th>Wholesaler Name</th>
                                            <th>Order Date</th>
                                            <th>Delivery Date</th>
                                            <th>Total Amount</th>
                                            <th>Order Number</th>
                                            <th>Invoice Number</th>
                                            <th>Order Details</th>
                                            <th>Status</th>
                                            {{-- <th scope="col">Actions</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $order->manager->first_name }}</td>
                                                <td>{{ $order->store->store_name }}</td>
                                                <td>{{ $order->vendor->vendor_name ?? 'N/A' }}</td>
                                                <td>{{ $order->date ?? 'N/A' }}</td>
                                                <td>
                                                    {{ optional($order->checkOrder)->delivery_date
                                                        ? \Carbon\Carbon::parse($order->checkOrder->delivery_date)
                                                            ->format('d-m-Y')
                                                        : 'N/A'
                                                    }}
                                                </td>
                                                <td>{{ $order->total_price ?? 'N/A' }}</td>
                                                <td>#{{ $order->order_code }}</td>
                                                <td>{{ $order->invoice_number }}</td>
                                                <td>
                                                    <a class="btn btn-info"
                                                        href="{{ route('Order.detail', $order->id) }}">view</a>
                                                </td>
                                                <td>
                                                    <span
                                                        class="status-label badge {{ $order->status == 'In-Progress' ? 'badge-danger' : ($order->status == 'Completed' ? 'badge-success' : '') }}"
                                                        data-id="{{ $order->id }}" data-status="{{ $order->status }}">
                                                        {{ $order->status }}
                                                    </span>
                                                </td>
                                                {{-- <td>
                                                    <button class="btn btn-success  btn-sm update-status"
                                                        data-id="{{ $order->id }}"
                                                        data-status="{{ $order->status }}"><i
                                                            class="fas fa-edit"></i></button>
                                                </td> --}}

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

    <!-- Status Update Modal -->
    {{-- <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="statusForm" action="{{ route('adminUpdateOrderStatus') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusModalLabel">Update Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="order_id" id="order_id" value="">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="In-Progress">In-Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
@endsection

@section('js')
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif

    <script>
        $(document).ready(function() {
            $('#table-1').DataTable();

            // Open modal on status click
          // Event delegation for dynamic elements
          $('#table-1').on('click', '.status-label, .update-status', function() {
            var orderId = $(this).data('id');
            var orderStatus = $(this).data('status');

            $('#order_id').val(orderId);
            $('#status').val(orderStatus);
            $('#statusModal').modal('show');
        });
        });
    </script>
@endsection

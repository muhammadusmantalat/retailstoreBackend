@extends('managers.layout.app')
@section('title', 'Order Details')

@section('content')
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Order Details</h4>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">

                                <table class="table text-center" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $totalSubTotal = 0;
                                        $totalDiscount = 0;
                                        $totalAfterDiscount = 0;
                                        $discountAmount = 0;
                                    @endphp

                                    @foreach ($orderItems as $orderItem)
                                        @php
                                            // Assuming 'sub_total', 'discount_price', and 'priceAfterDiscount' are directly fetched from DB
                                            $totalSubTotal += $orderItem->sub_total;
                                            $totalDiscount = $orderItem->discount_price;
                                            $totalAfterDiscount += $orderItem->priceAfterDiscount;
                                            $discountAmount += $orderItem->discount_amount;
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $orderItem->product->product_name ?? 'N/A' }}</td>
                                            <td>{{ $orderItem->quantity ?? 'N/A' }}</td>
                                            <td>${{ number_format($orderItem->price ?? 0, 2) }}</td>
                                            <td>${{ number_format($orderItem->sub_total ?? 0, 2) }}</td>
                                        </tr>
                                    @endforeach

                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-right">Total Amount:</th>
                                            <th>${{ number_format($totalSubTotal, 2) }}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="4" class="text-right">Vendor Discount: ({{ number_format($totalDiscount, 2) }}%)</th>
                                            <th>${{ number_format($discountAmount, 2) }}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="4" class="text-right">Total Amount After Discount:</th>
                                            <th>${{ number_format($totalAfterDiscount, 2) }}</th>
                                        </tr>
                                    </tfoot>

                                </table>
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
            $('#table_id_events').DataTable();
        });
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

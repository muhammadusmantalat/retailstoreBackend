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
                                    <h4>Orders</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="responsive table table-bordered table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Wholesaler Name</th>
                                            <th>Total Amount</th>
                                            <th>Order Date</th>
                                            <th>Delivery Date</th>
                                            <th>Status</th>
                                            <th>Order Number</th>
                                            <th>Invoice Number</th>
                                            <th>Order Details</th>
                                            <th>Check Order</th>
                                            <th>Audit</th>

                                            {{-- <th scope="col">Actions</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $order->vendor->vendor_name ?? 'N/A' }}</td>
                                                <td>${{ isset($order->total_price) ? number_format($order->total_price, 2) : 'N/A' }}
                                                </td>
                                                <td>{{ $order->date ?? 'N/A' }}</td>
                                                <td>
                                                    {{ optional($order->checkOrder)->delivery_date
                                                        ? \Carbon\Carbon::parse($order->checkOrder->delivery_date)
                                                            ->format('d-m-Y')
                                                        : 'N/A'
                                                    }}
                                                </td>

                                                <td>
                                                    <span
                                                        class="status-label badge {{ $order->status == 'In-Progress' ? 'badge-danger' : ($order->status == 'Completed' ? 'badge-success' : '') }}"
                                                        data-id="{{ $order->id }}" data-status="{{ $order->status }}">
                                                        {{ $order->status }}
                                                    </span>
                                                </td>
                                                {{-- <td>
                                                    <button type="button" class="btn btn-warning check-order-btn"
                                                        data-toggle="modal" data-target="#checkOrderModal"
                                                        data-order-id="{{ $order->id }}">
                                                        Check Delivery
                                                    </button>
                                                </td> --}}
                                                <td>#{{ $order->order_code }}</td>
                                                <td>{{ $order->invoice_number }}</td>
                                                <td>
                                                    <a class="btn btn-info"
                                                        href="{{ route('manager.storeManagerOrder.detail', $order->id) }}">view</a>
                                                </td>
                                                <td>
                                                    <form action="{{ route('manager.signAllChecked', $order->id) }}"
                                                        method="GET" style="display: inline;">
                                                        <button type="submit" class="btn btn-warning check-order-btn">
                                                            Check Delivery
                                                        </button>
                                                    </form>
                                                </td>


                                                <td>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                                        data-target="#auditModal" data-order-id="{{ $order->id }}"
                                                        data-vendor-id="{{ $order->vendor_id }}"
                                                        onclick="loadOrderData({{ $order->id }}, {{ $order->vendor_id }})">
                                                        Audit
                                                    </button>
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

    {{-- <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="statusForm" action="{{ route('manager.updateOrderStatus') }}" method="POST">
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


    <div class="modal fade" id="auditModal" tabindex="-1" role="dialog" aria-labelledby="auditModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form id="auditForm" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="order_id" id="orderIdInForm" hidden>
                    <input type="text" name="vendor_id" id="vendorIdInForm" hidden>
                    <div class="modal-header">
                        <h5 class="modal-title" id="auditModalLabel">Audit Order #<span id="orderCode"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Vendor's Order Receipt</label><br>
                                    <object id="vendorReceipt" data="" type="application/pdf"
                                        style="width: 100%; height: 400px; display: none;">
                                        <p>Your browser does not support PDFs. <a href=""
                                                id="vendorReceiptFallback">Download the PDF</a>.</p>
                                    </object>
                                    <a id="vendorReceipturl" href="#" target="_blank" class="d-none">
                                        <img src="" alt="" id="vendorReciept1"> <!-- PDF icon -->
                                    </a>
                                    <span class='d-none text-danger' id='alert1'>No record found.</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Store Manager's Order Receipt</label><br>
                                    <object id="managerReceipt" data="" type="application/pdf"
                                        style="width: 100%; height: 400px; display: none;">
                                        <p>Your browser does not support PDFs. <a href=""
                                                id="managerReceiptFallback">Download the PDF</a>.</p>
                                    </object>
                                    <a id="managerReceipturl" href="#" target="_blank" class="d-none">
                                        <img src="" alt="" id="managerReceiptIcon"> <!-- PDF icon -->
                                    </a>
                                    <span class='d-none text-danger' id='alert2'>No record found.</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="checkbox" id="overcharged_prices" name="overcharged_prices" value="1">
                            <label for="overcharged_prices">Overcharged Prices</label>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                placeholder="Additional details..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="sendButton">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('js')
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif
    <script>
        $('#auditForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting normally

            var formData = new FormData(this); // Create FormData object to handle file upload

            $.ajax({
                type: 'POST',
                url: "{{ route('manager.store.initiateAudit') }}", // Use route for audit submission
                data: formData,
                contentType: false, // Tell jQuery not to process the data
                processData: false, // Tell jQuery not to convert the data into a query string
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Send CSRF token
                },
                beforeSend: function() {
                    $('#sendButton').prop('disabled', true).text('Sending...');
                },
                success: function(response) {
                    toastr.success('Audit initiated and email sent!', '', {
                        onHidden: function() {
                            location
                                .reload();
                        }
                    });

                    $('#sendButton').prop('disabled', false).text('Send');
                    // Close the modal
                    $('#auditModal').modal('hide');
                    // Display a success message (customize this as needed)
                },
                error: function(xhr) {
                    console.log(xhr);
                    $('#sendButton').prop('disabled', false).text('Send');
                    // Display validation errors (if any)
                    let errors = xhr.responseJSON.errors;
                    if (errors) {
                        for (let field in errors) {
                            toastr.error(errors[field][0]); // Display error message for each field
                        }
                    } else {
                        toastr.error('An error occurred while initiating the audit.');
                    }
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#table_id_events').DataTable();

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

    <script>
        function loadOrderData(orderId) {
            $('#orderIdInForm').val(orderId);
            $.ajax({
                url: '{{ route('manager.order.audit', '') }}/' + orderId,
                method: 'GET',
                success: function(data) {
                    console.log("data", data);

                    $('#orderCode').text(data.order_code);

                    // Handle vendor receipt
                    if (data.vendor_receipt) {
                        var vendorReceiptPath = data.vendor_receipt;
                        let fileExtension = vendorReceiptPath.split('.').pop().toLowerCase();
                        let iconPath;

                        if (fileExtension === 'pdf') {
                            iconPath = '{{ asset('public/admin/assets/img/pdf-icon.png') }}';
                        } else if (fileExtension === 'doc' || fileExtension === 'docx') {
                            iconPath = '{{ asset('public/admin/assets/img/docx-icon.png') }}';
                        } else if (fileExtension === 'xls' || fileExtension === 'xlsx') {
                            iconPath = '{{ asset('public/admin/assets/img/excel-icon.png') }}';
                        } else if (fileExtension === 'pptx') {
                            iconPath = '{{ asset('public/admin/assets/img/pptx-icon.png') }}';
                        } else {
                            iconPath = vendorReceiptPath; // Use the file itself as the image
                        }

                        $('#vendorReceipt').attr('data', vendorReceiptPath).removeClass('d-none');
                        $('#vendorReceipturl').attr('href', vendorReceiptPath).removeClass('d-none');
                        $('#vendorReciept1').attr('src', iconPath).css({
                            height: '50px',
                            width: '50px'
                        });
                        $('#vendorReceiptFallback').attr('href', vendorReceiptPath);
                        $('#alert1').addClass('d-none');
                    } else {
                        $('#vendorReceipt').addClass('d-none');
                        $('#vendorReceipturl').addClass('d-none');
                        $('#vendorReciept1').addClass('d-none');
                        $('#alert1').removeClass('d-none');
                    }

                    // Handle store manager receipt
                    if (data.manager_receipt) {
                        var managerReceiptPath = data.manager_receipt;
                        let managerFileExtension = managerReceiptPath.split('.').pop().toLowerCase();
                        let managerIconPath;

                        if (managerFileExtension === 'pdf') {
                            managerIconPath = '{{ asset('public/admin/assets/img/pdf-icon.png') }}';
                        } else if (managerFileExtension === 'doc' || managerFileExtension === 'docx') {
                            managerIconPath = '{{ asset('public/admin/assets/img/docx-icon.png') }}';
                        } else if (managerFileExtension === 'xls' || managerFileExtension === 'xlsx') {
                            managerIconPath = '{{ asset('public/admin/assets/img/excel-icon.png') }}';
                        } else if (managerFileExtension === 'pptx') {
                            managerIconPath = '{{ asset('public/admin/assets/img/pptx-icon.png') }}';
                        } else {
                            managerIconPath = managerReceiptPath; // Use the file itself as the image
                        }

                        $('#managerReceipt').attr('data', managerReceiptPath).removeClass('d-none');
                        $('#managerReceipturl').attr('href', managerReceiptPath).removeClass('d-none');
                        $('#managerReceiptIcon').attr('src', managerIconPath).css({
                            display: 'block', // Show the icon
                            height: '50px',
                            width: '50px'
                        });
                        $('#managerReceiptFallback').attr('href', managerReceiptPath);
                        $('#alert2').addClass('d-none');
                    } else {
                        $('#managerReceipt').addClass('d-none');
                        $('#managerReceipturl').addClass('d-none');
                        $('#managerReceiptIcon').css({
                            display: 'none' // Hide the icon
                        });
                        $('#alert2').removeClass('d-none');
                    }

                    // Handle other fields
                    $('#description').val(data.description);
                    $('#vendorIdInForm').val(data.vendor_id);

                    if (data.is_vendor_in_audit && data.overcharged_prices == 1) {
                        $('#overcharged_prices').prop('checked', true);
                        $('#description').prop('disabled', true);
                        $('#sendButton').prop('disabled', true);
                    } else {
                        $('#overcharged_prices').prop('checked', false);
                        $('#description').prop('disabled', false);
                        $('#sendButton').prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching order data:', xhr);
                }
            });
        }
    </script>

@endsection

@extends('managers.layout.app')
@section('title', 'Sales History')
@section('content')
    <style>
        .active-button {
            background-color: var(--theme-color-dark) !important;
            color: #fff !important;
        }
    </style>

    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header justify-content-center">
                                <h4>Vendor Order Report</h4>
                            </div>
                            <div class="col-12 mt-3 d-flex justify-content-center">
                                <button id="dailyButton" class="col-sm-2 btn btn-primary mr-2 mb-2 mb-md-0"
                                    onclick="loadData('daily', this)">
                                    <i class="fas fa-spinner fa-spin" style="display:none;"></i> Daily
                                </button>
                                <button id="weeklyButton" class="col-sm-2 btn btn-primary mr-2 mb-2 mb-md-0"
                                    onclick="loadData('weekly', this)">
                                    <i class="fas fa-spinner fa-spin" style="display:none;"></i> Weekly
                                </button>
                                <button id="monthlyButton" class="col-sm-2 btn btn-primary mr-2 mb-2 mb-md-0"
                                    onclick="loadData('monthly', this)">
                                    <i class="fas fa-spinner fa-spin" style="display:none;"></i> Monthly
                                </button>
                                <button id="yearlyButton" class="col-sm-2 btn btn-primary mb-2 mb-md-0"
                                    onclick="loadData('yearly', this)">
                                    <i class="fas fa-spinner fa-spin" style="display:none;"></i> Yearly
                                </button>
                            </div>

                            <div class="mt-3 row justify-content-center">
                                <div class="col-md-6 col-lg-4 mb-2 mb-md-0">
                                    <select id="vendorSelect" class="form-control">
                                        <option value="" disabled selected>Select Vendor</option>
                                        @foreach ($vendors as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-lg-2">
                                    <button id="clearButton" class="btn btn-danger w-100">
                                        Clear
                                    </button>
                                </div>
                            </div>


                            <div class="card-body table-responsive">
                                <h4 id="totalAmount" class="mb-2"></h4>
                                <table id="example" class="responsive table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Invoice Number</th>
                                            <th>Wholesaler Name</th>
                                            <th>Order Amount</th>
                                            <th>Invoice Amount</th>
                                            <th>Checked By</th>
                                            <th>Payment Method</th>
                                            <th>Cheque Number</th>
                                            <th>Order Date</th>
                                            <th>Delivery Date</th>
                                            <th>Product Details</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
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
    <script>
        $(document).ready(function() {
            var period = 'daily';
            var department_id = '';
            var vendor_id = '';

            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ url('manager/sales/data') }}",
                    "type": "POST",
                    "data": function(d) {

                        d.period = period;
                        // d.department_id = department_id;
                        d.vendor_id = vendor_id;
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": function(json) {
                        console.log("data", json);
                        if (json.salesData.length == 0) {
                            $('#totalAmount').text('Total Amount: $ 0.00');
                        } else {
                            $('#totalAmount').text('Total Amount: $ ' + json.totalAmount.toFixed(2));
                        }
                        return json.salesData;


                    },
                    "error": function(xhr, error, thrown) {
                        console.log('AJAX Error:', xhr);
                    }
                },
                "dom": 'Bfrtip',
                "buttons": [
                    'print',
                ],
                "columns": [{
                        "data": null,
                        "render": function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        "data": "invoice_number"
                    },
                    {
                        "data": "vendor_name"
                    },
                    {
                        "data": "total_price",
                        "render": function(data) {
                            return '$ ' + parseFloat(data).toFixed(2);
                        }
                    },
                    {
                        "data": "check_order.invoice_amount", // Accessing the invoice amount
                        "render": function(data) {
                            return '$ ' + parseFloat(data || 0).toFixed(
                                2); // Safely render the amount
                        }
                    },
                    {
                        "data": "check_order.checked_by",
                        "render": function(data) {
                            return data ? data : 'N/A'; // Provide default if null
                        }
                    },
                    {
                        "data": "check_order.payment_method",
                        "render": function(data) {
                            return data ? data : 'N/A'; // Provide default if null
                        }
                    },
                    {
                       "data": "check_order.check_number",
                        "render": function(data) {
                            return data ? data : 'N/A'; // Provide default if null
                        }
                    },
                    {
                        "data": "created_at",
                        "render": function(data) {
                            return moment(data).format('DD-MM-YYYY');
                        }
                    },
                    {
                        "data": "check_order.delivery_date",
                        "render": function(data) {
                            return moment(data).format('DD-MM-YYYY');
                        }
                    },
                    {
                        "data": null,
                        "render": function(data) {
                            var productDetails = '';
                            data.order_item.forEach(function(item) {
                                productDetails +=
                                    `<strong>Product:</strong> ${item.product_name}<br>
                                               <strong>Quantity:</strong> ${item.quantity}<br>
                                               <strong>Price:</strong> $ ${parseFloat(item.price).toFixed(2)}<br>
                                               <strong>Subtotal:</strong> $ ${parseFloat(item.sub_total).toFixed(2)}<br><hr>`;
                            });
                            return productDetails;
                        }
                    },
                    {
                        "data": "status",
                        "render": function(data) {
                            if (data === "In-Progress") {
                                return '<span style="color: red;">In-Progress</span>';
                            } else if (data === "Completed") {
                                return '<span style="color: green;">Completed</span>';
                            } else {
                                return data;
                            }
                        }
                    }
                ]
            });

            // Load filtered data based on period
            window.loadData = function(newPeriod, button) {
                period = newPeriod;
                disableButton(button);
                dataTable.ajax.reload(function() {
                    enableButton(button);
                    updateActiveButton(button);
                });
            };

            // Load vendors based on department selection
            $('#vendorSelect').change(function() {
                vendor_id = $(this).val();
                $.ajax({
                    url: "{{ url('manager/departmemtVendor') }}/" + vendor_id,
                    type: 'GET',
                    success: function(response) {
                        console.log("data", response.vendors)
                    }
                });
            });

            // Capture vendor selection
            $('#vendorSelect').change(function() {
                vendor_id = $(this).val();
                dataTable.ajax.reload();
            });

            function disableButton(button) {
                $(button).attr('disabled', true);
                $(button).find('.fa-spinner').show();
            }

            function enableButton(button) {
                $(button).attr('disabled', false);
                $(button).find('.fa-spinner').hide();
            }

            function updateActiveButton(button) {
                $('.btn').removeClass('active-button');
                $(button).addClass('active-button');
            }

            // Set the initial active button
            $('#dailyButton').addClass('active-button');
        });

        $('#clearButton').click(function() {
        // Clear the department and vendor dropdowns
        $('#departmentSelect').val('').change();
        $('#vendorSelect').empty().append('<option value="" disabled selected>Select Vendor</option>').prop('disabled', true);

        // Refresh the page
        location.reload();
    });

    </script>
@endsection

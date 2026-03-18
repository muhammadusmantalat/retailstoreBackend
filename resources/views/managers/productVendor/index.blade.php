@extends('managers.layout.app')
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
                                    <h4>Assign Wholesalers For ({{$product->product_name}})</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                <a class="btn btn-success mb-3"
                                    href="{{ route('manager.productVendor-create', $id) }}">Assign Wholesaler</a>
                                <table class="table text-center" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>

                                            <th>Wholesaler Names</th>
                                            <th>Department Names</th>
                                            <th>Cost($)</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($assignedProducts as $assignedProduct)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $assignedProduct->vendor->vendor_name }}</td>
                                                 <td>{{ $assignedProduct->departments->department_name }}</td>
                                                 <td>{{ number_format(is_numeric($assignedProduct->product_price) ? $assignedProduct->product_price : 0, 2) }}</td>

                                                {{-- @foreach ($groupedProducts as $groupKey => $products)
                                                    @php
                                                        $firstProduct = $products->first();
                                                    @endphp
                                             <tr>
                                                {{-- <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $firstProduct->vendor->vendor_name ?? 'N/A' }}</td>
                                                <td>
                                                    @php
                                                    $departmentNames = $products->map(function ($product) {
                                                        return $product->departments->department_name ?? 'N/A';
                                                    })->toArray();
                                                    $departmentNamesString = implode(', ', $departmentNames);
                                                    @endphp
                                                    {{ $departmentNamesString }}
                                                </td>

                                                <td>{{ $firstProduct->product_price }}</td> --}}
                                                <td>
                                                    <div class="d-flex gap-4">
                                                        <a href="{{ route('manager.productVendor-edit', ['id' => $assignedProduct->id, 'vendorId' => $assignedProduct->vendor_id, 'productId' => $assignedProduct->product_id]) }}" class="btn btn-primary">Edit</a>

                                                        <form action="{{ route('manager.productVendor-delete', ['id' =>$assignedProduct->id , 'productId' => $assignedProduct->product_id]) }}" method="POST"
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
            $('#table_id_events').DataTable()

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

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
                        <div class="card-body table-striped table-bordered table-responsive">
                            <a class="btn btn-success mb-3" href="{{ route('products-assignVendor-create', ['productId' => $productId, 'storeManagerId' => $storeManagerId, 'storeId' => $storeId]) }}">Assign
                                Wholesaler</a>
                            <table class="table text-center" id="table_id_events">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Store Manager Name</th>
                                        <th>Store Name</th>
                                        <th>Wholesalers Name</th>
                                        <th>Departments Name</th>
                                        {{-- <th>Product Name</th> --}}
                                        <th>Cost($)</th>
                                        {{-- <th>Status</th> --}}
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($assignedProducts as $assignedProduct)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $assignedProduct->storeManager->first_name ?? 'N/A'}} {{$assignedProduct->storeManager->last_name ?? ''}}</td>
                                                <td>{{$assignedProduct->store->store_name ?? 'N/A' }}</td>
                                                <td>{{$assignedProduct->vendor->vendor_name ?? 'N/A' }}</td>
                                                <td>{{$assignedProduct->departments->department_name ?? 'N/A' }}</td>
                                                {{-- <td>{{$assignedProduct->product->product_name ?? 'N/A' }}</td> --}}
                                                <td>{{$assignedProduct->product_price?? 'N/A' }}</td>
                                                <td>
                                                    <div style="display: flex; align-items: center; justify-content: center; column-gap: 8px">
                                                        <a class="btn btn-info" href="{{ route('products-assignVendor-edit', ['id' => $assignedProduct->id, 'vendorId' => $assignedProduct->vendor_id, 'productId' => $assignedProduct->product_id]) }}">Edit</a>

                                                   <form action="{{ route('products-assignVendor-destroy', ['id' => $assignedProduct->id, 'productId' => $productId,'storeManagerId' => $storeManagerId, 'storeId' => $storeId ]) }}"
                                                        method="POST" style="display:inline-block; margin-left: 10px" >
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn  btn-danger btn-flat show_confirm"
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

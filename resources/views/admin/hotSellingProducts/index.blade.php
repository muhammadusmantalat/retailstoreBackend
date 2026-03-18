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
                                    <h4>Adjust Quantity Limit - <span class="text-danger">This is the quantity limit. If a store manager buys the same product from the same vendor and the total purchase quantity exceeds this limit, the product will be shown as Hot Selling to that manager.</span></h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                {{-- <a class="btn btn-success mb-3" href="{{ route('hotSalingProduct.create') }}">Add Quantity</a> --}}
                                <table class="responsive table table-bordered table-striped" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Product Buying Quantity Per Vendor</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($hotSaleProducts as $hotSaleProduct)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $hotSaleProduct->quantity }}</td>


                                                <td>
                                                    <div class="d-flex gap-4">
                                                        <a href="{{ route('hotSalingProduct.edit', $hotSaleProduct->id) }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        {{-- <form action="{{ route('hotSalingProduct.delete', $hotSaleProduct->id) }}"
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
            $('#table_id_events').DataTable()

        })
    </script>

@endsection

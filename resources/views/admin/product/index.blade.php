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
                                <h4>Products</h4>
                            </div>
                        </div>

                        <div class="card-body">
                            <a class="btn btn-success mb-3" href="{{ route('products-create') }}">
                                Add Product
                            </a>

                            {{-- Search Box --}}
                            <div class="mb-3 d-flex justify-content-end align-items-end">
                                <input type="text" id="search" class="form-control" placeholder="Search Products..." style="width:20%">
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="productsTable">
                                        <thead>
                                            <tr>
                                                <th>Sr.</th>
                                                <th>Store Manager</th>
                                                <th>Store Names</th>
                                                <th>Product Names</th>
                                                <th>UPC / IPC</th>
                                                <th>Retail Price ($)</th>
                                                <th>Product Images</th>
                                                <th>Wholesalers</th>
                                                <th>Departments</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                    <tbody id="tableBody">
                                        {{-- AJAX Data Load Here --}}
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            <div id="paginationLinks" class="mt-3 pagination d-flex justify-content-end align-items-end"></div>
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

{{-- Sweet Alert Delete Confirm --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

<script>
    let currentPage = 1;
    let searchValue = '';
    const routes = {
        images: "{{ route('ProductsImages', ':id') }}",
        assignVendor: "{{ route('products-assignVendor', ['productId' => ':productId', 'storeManagerId' => ':storeManagerId', 'storeId' => ':storeId']) }}",
        departments: "{{ route('products-departments', ['storeManagerId' => ':storeManagerId', 'storeId' => ':storeId', 'productId' => ':productId']) }}",
        edit: "{{ route('products-edit', ['storeId' => ':storeId', 'id' => ':id']) }}",
        delete: "{{ route('products-delete', ':id') }}"
    };
    function loadProducts(page = 1) {
        $.ajax({
            url: "{{ route('products.ajax') }}",
            type: "GET",
            data: {
                page: page,
                search: searchValue
            },
            success: function(response) {
                let rows = '';
                let index = (response.current_page - 1) * response.per_page;

                response.data.forEach((product, i) => {

                    let managerName = product.store_manager
                        ? product.store_manager.first_name + ' ' + product.store_manager.last_name
                        : 'N/A';

                    let storeName = product.store ? product.store.store_name : '';

                    // Correct Laravel Route URLs (dynamic replace)
                    let imageUrl = routes.images.replace(':id', product.id);

                    let vendorUrl = routes.assignVendor
                        .replace(':productId', product.id)
                        .replace(':storeManagerId', product.store_manager_id ?? '')
                        .replace(':storeId', product.store_id ?? '');

                    let departmentUrl = routes.departments
                        .replace(':productId', product.id)
                        .replace(':storeManagerId', product.store_manager_id ?? '')
                        .replace(':storeId', product.store_id ?? '');

                    let editUrl = routes.edit
                        .replace(':id', product.id)
                        .replace(':storeId', product.store_id ?? '');

                    let deleteUrl = routes.delete.replace(':id', product.id);

                    rows += `
                        <tr>
                            <td>${index + i + 1}</td>
                            <td>${managerName}</td>
                            <td>${storeName}</td>
                            <td>${product.product_name}</td>
                            <td>${product.upc_ipc ?? ''}</td>
                            <td>${product.price}</td>

                            <td>
                                <a class="btn btn-success btn-sm" href="${imageUrl}">
                                    Preview
                                </a>
                            </td>

                            <td>
                                <a class="btn btn-info btn-sm" href="${vendorUrl}">
                                    Assign
                                </a>
                            </td>

                            <td>
                                <a class="btn btn-success btn-sm" href="${departmentUrl}">
                                    Assign
                                </a>
                            </td>

                            <td>
                                <div class="">
                                    <a href="${editUrl}" class="btn btn-primary btn-sm">Edit</a>
                                    
                                    <form action="${deleteUrl}" method="POST" class="delete-form" style="display:inline-block;">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger btn-sm show_confirm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                $('#tableBody').html(rows);
                $('#paginationLinks').html(response.pagination);
            }
        });
    }
    
    $(document).on('click', '.show_confirm', function(event) {
    event.preventDefault();
    var form = $(this).closest("form");

    swal({
        title: "Are you sure you want to delete this record?",
            text: "If you delete this, it will be gone forever.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                form.submit();
            }
        });
    });

    // 🔍 Live Search
    $('#search').on('keyup', function() {
        searchValue = $(this).val();
        loadProducts(1);
    });

    // 📄 Pagination Click (AJAX)
    $(document).on('click', '#paginationLinks a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        loadProducts(page);
    });

    // Initial Load
    $(document).ready(function() {
        loadProducts();
    });
    
</script>
@endsection
@extends('managers.layout.app')
@section('title', 'Products')

@section('content')
<div class="main-content" style="min-height: 562px;">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-header">
                            <h4>Products</h4>
                        </div>

                        <div class="card-body">
                            <a class="btn btn-success mb-3" href="{{ route('manager.storeManagerProducts-create') }}">
                                Add Product
                            </a>
                            <a class="btn btn-info mb-3" href="{{ route('manager.products-uploadForm') }}">
                                Upload Bulk Products
                            </a>

                            {{-- Search --}}
                            <div class="mb-3 d-flex justify-content-end">
                                <input type="text" id="search" class="form-control" placeholder="Search Products..." style="width:20%">
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="productsTable">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Product Name</th>
                                            <th>UPC / IPC</th>
                                            <th>Retail Price ($)</th>
                                            <th>Product Images</th>
                                            <th>Wholesalers</th>
                                            <th>Departments</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        {{-- AJAX Data Here --}}
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            <div id="paginationLinks" class="mt-3 d-flex justify-content-end"></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('js')
@if(session()->has('message'))
<script>
    toastr.success('{{ session()->get('message') }}');
</script>
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

<script>
let currentPage = 1;
let searchValue = '';

const routes = {
    images: "{{ route('manager.storeManagerProductsImages', ':id') }}",
    assignVendor: "{{ route('manager.productVendors', ['storeManagerId' => ':storeManagerId', 'storeId' => ':storeId', 'id' => ':productId']) }}",
    departments: "{{ route('manager.ProductsDepartments', ':productId') }}",
    edit: "{{ route('manager.storeManagerProducts-edit', ':id') }}",
    delete: "{{ route('manager.storeManagerProducts-delete', ':id') }}"
};

function loadProducts(page = 1) {
    $.ajax({
        url: "{{ route('manager.products.ajax') }}",
        type: "GET",
        data: { page: page, search: searchValue },
        success: function(response) {
            let rows = '';
            let index = (response.current_page - 1) * response.per_page;

            response.data.forEach((product, i) => {
                let imageUrl = routes.images.replace(':id', product.id);
                let vendorUrl = routes.assignVendor
                    .replace(':productId', product.id)
                    .replace(':storeManagerId', product.store_manager_id ?? '')
                    .replace(':storeId', product.store_id ?? '');
                let departmentUrl = routes.departments.replace(':productId', product.id);
                let editUrl = routes.edit.replace(':id', product.id);
                let deleteUrl = routes.delete.replace(':id', product.id);

                rows += `
                    <tr>
                        <td>${index + i + 1}</td>
                        <td>${product.product_name}</td>
                        <td>${product.upc_ipc ?? ''}</td>
                        <td>${parseFloat(product.price).toFixed(2)}</td>
                        <td><a class="btn btn-success btn-sm" href="${imageUrl}">Preview</a></td>
                        <td><a class="btn btn-info btn-sm" href="${vendorUrl}">Assign</a></td>
                        <td><a class="btn btn-success btn-sm" href="${departmentUrl}">Assign</a></td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="${editUrl}" class="btn btn-primary btn-sm">Edit</a>
                                <form action="${deleteUrl}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm show_confirm">Delete</button>
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

// Delete confirm
$(document).on('click', '.show_confirm', function(e) {
    e.preventDefault();
    let form = $(this).closest('form');

    swal({
        title: "Are you sure you want to delete this record?",
        text: "If you delete this, it will be gone forever.",
        icon: "warning",
        buttons: true,
        dangerMode: true
    }).then((willDelete) => {
        if (willDelete) form.submit();
    });
});

// Search
$('#search').on('keyup', function() {
    searchValue = $(this).val();
    loadProducts(1);
});

// Pagination click
$(document).on('click', '#paginationLinks a', function(e) {
    e.preventDefault();
    let page = $(this).attr('href').split('page=')[1];
    loadProducts(page);
});

// Initial load
$(document).ready(function() {
    loadProducts();
});
</script>
@endsection
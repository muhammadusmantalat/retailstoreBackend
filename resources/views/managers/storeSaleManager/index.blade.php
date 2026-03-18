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
                                    <h4>Salesmen</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                @php
                                        $storeManagerDepartment = App\Models\StoreManagerStoreDepartment::where('store_manager_id', Auth::guard('web')->id())->first();
                                        $check = App\Models\StoreHasSalesManager::where('store_id', $storeManagerDepartment->store_id)->first();
                                @endphp
                                @if (!$check)
                                    <a class="btn btn-success mb-3" href="{{route('manager.storeSaleManager.create') }}">Add Salesmen</a>
                                @endif

                                <table class="table text-center" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Salesmen Name</th>
                                            <th>Salesmen Email</th>
                                            <th>Salesmen Phone</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                     <tbody>
                                        @foreach ($saleManager as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->sales_manager_name ??'' }}</td>
                                            <td>{{ $data->sales_manager_email ??'' }}</td>
                                            <td>{{ $data->sales_manager_phone_no ??'' }}</td>
                                            </td>
                                            <td style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                <a class="btn btn-info" href="{{ route('manager.storeSaleManager.edit', $data->id ) }}">Edit</a>
                                                {{-- <form method="post" action="{{ route('manager.departments-destroy', $department->id) }}">
                                                    @csrf
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <button type="submit" class="btn btn-danger btn-flat show_confirm" data-toggle="tooltip">Delete</button>
                                                </form> --}}
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
            $('#table_id_events').DataTable();
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">
        $('.show_confirm').click(function(event) {
            event.preventDefault();
            var form = $(this).closest("form");
            var managerId = form.attr('action').split('/').pop();


            $.ajax({
                url: '{{ route('manager.checkProducts', '') }}/' + managerId,
                method: 'GET',
                success: function(response) {
                    if (response.hasProducts) {
                        // Show modal if there are associated stores
                        swal({
                            // title: 'Cannot Delete Store Manager',
                            text: 'Cannot delete department with products in Inventory.',
                            icon: 'warning',
                            button: 'OK'
                        });
                    } else {
                        // Proceed with the deletion confirmation
                        swal({
                            title: 'Are you sure you want to delete this record?',
                            text: 'If you delete this, it will be gone forever.',
                            icon: 'warning',
                            buttons: true,
                            dangerMode: true,
                        }).then((willDelete) => {
                            if (willDelete) {
                                form.submit();
                            }
                        });
                    }
                }
            });
        });
    </script>
@endsection


{{-- @extends('admin.layout.app')
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
                                    <h4>Store Managers</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                <a class="btn btn-success mb-3" href="{{ route('store-manager.create') }}">Add Store
                                    Manager</a>
                                <table class="table text-center" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email</th>
                                            <th>Image</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>Status</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($storeManagers as $storeManager)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $storeManager->first_name }}</td>
                                                <td>{{ $storeManager->last_name }}</td>
                                                <td>
                                                    @if ($storeManager->email)
                                                        <a
                                                            href="mailto:{{ $storeManager->email }}">{{ $storeManager->email }}</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <img src="{{ asset($storeManager->image) }}" alt=""
                                                        height="50" width="50" class="image">
                                                </td>
                                                <td>{{ $storeManager->phone }}</td>
                                                <td>{{ $storeManager->address }}</td>
                                                <td>
                                                    @if ($storeManager->is_active == 1)
                                                        <div class="badge badge-success badge-shadow">Activated</div>
                                                    @else
                                                        <div class="badge badge-danger badge-shadow">Deactivated</div>
                                                    @endif
                                                </td>
                                                <td
                                                    style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                    @if ($storeManager->is_active == 1)
                                                        <a href="javascript:void(0);"
                                                            onclick="showDeactivationModal({{ $storeManager->id }})"
                                                            class="btn btn-success">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-toggle-left">
                                                                <rect x="1" y="5" width="22" height="14"
                                                                    rx="7" ry="7"></rect>
                                                                <circle cx="16" cy="12" r="3"></circle>
                                                            </svg>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('storeManager.activate', ['id' => $storeManager->id]) }}"
                                                            class="btn btn-danger">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-toggle-right">
                                                                <rect x="1" y="5" width="22" height="14"
                                                                    rx="7" ry="7"></rect>
                                                                <circle cx="8" cy="12" r="3"></circle>
                                                            </svg>
                                                        </a>
                                                    @endif
                                                    <a class="btn btn-info"
                                                        href="{{ route('store-manager.edit', $storeManager->id) }}">Edit</a>
                                                    <form action="{{ route('store-manager.destroy', $storeManager->id) }}"
                                                        method="POST" style="display:inline-block; margin-left: 10px">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn  btn-danger btn-flat show_confirm"
                                                            data-toggle="tooltip">Delete</button>
                                                    </form>
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

    <!-- Deactivation Modal -->
    <div class="modal fade" id="deactivationModal" tabindex="-1" role="dialog" aria-labelledby="deactivationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="deactivationForm" action="{{ route('storeManager.deactivate', $storeManager->id) }}"
                    method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="deactivationModalLabel">Reason for Deactivation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="reason">Please provide the reason for deactivating this Store Manager:</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Deactivate</button>
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
                url: '{{ route('store-manager.checkStores', '') }}/' + managerId,
                method: 'GET',
                success: function(response) {
                    if (response.hasStores) {
                        // Show modal if there are associated stores
                        swal({
                            // title: 'Cannot Delete Store Manager',
                            text: 'Cannot delete store manager with stores in Inventory.',
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
    <script>
        function showDeactivationModal(managerId) {
            $('#deactivationForm').attr('action', '{{ url('admin/deactivate') }}/' + managerId);
            $('#deactivationModal').modal('show');
        }
    </script>
@endsection --}}

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
                                    <h4>Store Managers</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                <a class="btn btn-success mb-3" href="{{ route('store-manager.create') }}">Add Store
                                    Manager</a>
                                <table class="table text-center" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email</th>
                                            <th>Image</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>Status</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($storeManagers as $storeManager)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $storeManager->first_name }}</td>
                                                <td>{{ $storeManager->last_name }}</td>
                                                <td>
                                                    @if ($storeManager->email)
                                                        <a
                                                            href="mailto:{{ $storeManager->email }}">{{ $storeManager->email }}</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <img src="{{ asset($storeManager->image) }}" alt=""
                                                        height="50" width="50" class="image">
                                                </td>
                                                <td>{{ $storeManager->phone }}</td>
                                                <td>{{ $storeManager->address }}</td>
                                                <td>
                                                    @if ($storeManager->is_active == 1)
                                                        <div class="badge badge-success badge-shadow">Activated</div>
                                                    @else
                                                        <div class="badge badge-danger badge-shadow">Deactivated</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div
                                                        style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                        @if ($storeManager->is_active == 1)
                                                            <a href="javascript:void(0);"
                                                                onclick="showDeactivationModal({{ $storeManager->id }})"
                                                                class="btn btn-success">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-toggle-left">
                                                                    <rect x="1" y="5" width="22" height="14"
                                                                        rx="7" ry="7"></rect>
                                                                    <circle cx="16" cy="12" r="3"></circle>
                                                                </svg>
                                                            </a>

                                                            {{-- <a href="{{ route('storeManager.activate', ['id' => $storeManager->id]) }}"
                                                                class="btn btn-danger">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-toggle-right">
                                                                    <rect x="1" y="5" width="22" height="14"
                                                                        rx="7" ry="7"></rect>
                                                                    <circle cx="8" cy="12" r="3"></circle>
                                                                </svg>
                                                            </a> --}}
                                                            @elseif($storeManager->is_active == 0)
                                                            <a href="javascript:void(0);"
                                                                onclick="showActivationModal({{ $storeManager->id }})"
                                                                class="btn btn-btn btn-danger">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-toggle-left">
                                                                    <rect x="1" y="5" width="22" height="14"
                                                                        rx="7" ry="7"></rect>
                                                                    <circle cx="16" cy="12" r="3"></circle>
                                                                </svg>
                                                            </a>
                                                        @endif
                                                        <a class="btn btn-info"
                                                            href="{{ route('store-manager.edit', $storeManager->id) }}">Edit</a>
                                                        <form
                                                            action="{{ route('store-manager.destroy', $storeManager->id) }}"
                                                            method="POST" style="display:inline-block; margin-left: 10px">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger btn-flat show_confirm"
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

    <!-- Deactivation Modal -->
    <div class="modal fade" id="deactivationModal" tabindex="-1" role="dialog" aria-labelledby="deactivationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="deactivationForm" action="" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="deactivationModalLabel">Reason for Deactivation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="reason">Please provide the reason for deactivating this Store Manager:</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>

                        </div>
                    </div>
                    <input type="hidden" id="is_active" name="is_active" value="0">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Deactivate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Activation Modal -->
    <div class="modal fade" id="activationModal" tabindex="-1" role="dialog" aria-labelledby="activationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="activationForm" action="" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="activationModalLabel">Are you sure you want to activate this Store Manager?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="sendCredentials">Do you want to send the credentials to this store manager?</label>
                            <input type="hidden" name="sendCredentials" value="0">
                            <input type="checkbox" id="sendCredentials" name="sendCredentials" value="1">
                            <input type="hidden" id="is_active" name="is_active" value="1">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Activate</button>
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
                url: '{{ route('store-manager.checkStores', '') }}/' + managerId,
                method: 'GET',
                success: function(response) {
                    if (response.hasStores) {
                        swal({
                            text: 'Cannot delete store manager with stores in Inventory.',
                            icon: 'warning',
                            button: 'OK'
                        });
                    } else {
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

        function showDeactivationModal(managerId) {
            $('#deactivationForm').attr('action', '{{ url('admin/deactivate') }}/' + managerId);
            $('#deactivationModal').modal('show');
        }

        function showActivationModal(managerId) {
            $('#activationForm').attr('action', '{{ url('admin/activate') }}/' + managerId);
            $('#activationModal').modal('show');
        }
    </script>
@endsection

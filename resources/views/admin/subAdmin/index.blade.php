{{-- @extends('admin.layout.app')
@section('title', 'User')
@section('content')
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-6">
                                    <h4>SubAdmins</h4>
                                </div>
                                <div class="col-6 text-right">
                                    <!-- <a href="add_student" class="btn btn-bg glyphicon glyphicon-plus">Add Student</a> -->
                                    <a href="{{Url('admin/add-subadmin')}}" class="btn btn-info glyphicon glyphicon-plus btn-bg">Add
                                        Subadmin</a>

                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-striped table-bordered" id="table_id">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Phone</th>
                                            <th scope="col">Role</th>
                                            <th scope="col">Image</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            @if (isset($item))
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->email }}</td>
                                                    <td>{{ $item->phone }}</td>
                                                    <td>{{ $item->role }}</td>
                                                    <td><img alt="image" src="{{asset($item->image)}}" style="height:80px; width:80px;"></td>
                                                    <td><a href="{{ url('/admin/edit-subadmin/' . $item->id) }}"><i
                                                                class="fas fa-edit" data-toggle="tooltip"
                                                                data-placement="top" title="edit"></i></a>|
                                                        <a><i class="fas fa-trash text-danger glyphicon glyphicon-trash"
                                                                data-toggle="tooltip" data-placement="top" title="delete"
                                                                data-id="{{ $item->id }}"></i></a>
                                                        <form id="del_form{{ $item->id }}"
                                                            action="{{ url('admin/delete-subadmin/' . $item->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </td>

                                                </tr>
                                            @else
                                            @endif
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
    <script>
        $(document).ready(function() {
            $('#table_id').DataTable();
        });
    </script>
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif
    <script>
        $(document).on('click', '.glyphicon-trash', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    document.getElementById('del_form' + $(this).data('id')).submit();
                }
            });
        });

        $(document).on('click', '.user-status', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to change a status!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Change it!'
            }).then((result) => {
                if (result.value) {
                    document.getElementById('status' + $(this).data('id')).submit();
                }
            });
        });
    </script>
@endsection --}}

@extends('admin.layout.app')

@section('title', 'index')

@section('content')
    <style>
        .btn_warning {
            background: #ef9e09;
            padding: 9px 14px;
            border-radius: 9px;
            box-shadow: 0 2px 6px #82d3f8;
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
                                    <h4>Sub Admins</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                <a class="btn btn-success mb-3" href="{{ Url('admin/subadmin-add') }}">Add Sub Admin</a>
                                <table class="table text-center" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Image</th>
                                            <th>Phone</th>
                                            <th>Role</th>
                                            <th scope="col">Permissions</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sub_admins as $sub_admin)
                                            <tr> <!-- Start a new table row for each sub-admin -->
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $sub_admin->first_name }}</td>
                                                <td>
                                                    @if ($sub_admin->email)
                                                        <a href="mailto:{{ $sub_admin->email }}">{{ $sub_admin->email }}</a>
                                                    @endif
                                                </td>
                                                <td><img src="{{ asset($sub_admin->image) }}" alt="image" height="50"
                                                        width="50"></td>
                                                <td>{{ $sub_admin->phone }}</td>
                                                <td>{{ $sub_admin->user_type }}</td>



                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        {{-- <button type="button" class="btn btn-success" data-toggle="modal"
                                                            data-target="#notesModel1-{{ $sub_admin->id }}">
                                                            <span class="fa fa-pen"></span>
                                                        </button>
                                                        <button class="btn btn-info" data-toggle="modal"
                                                            data-target="#notesModel2-{{ $sub_admin->id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button> --}}
                                                        <button class="btn btn-info mb-3 text-white updatePermissionBtn"
                                                            onclick="openUpdatePermissionSubadminModal({{ $sub_admin->id }})"><i
                                                                class="fas fa-user"></i></button>
                                                    </div>
                                                </td>
                                                {{-- <td class="">
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <a style="margin-right:10px" class="btn p-0 btn-info"
                                                            href="{{ url('/admin/subadmin-edit/' . $sub_admin->id) }}">Edit</a>
                                                        <form method="post" action="{{ route('subadmin-delete', $sub_admin->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-flat show_confirm" data-toggle="tooltip">Delete</button>
                                                        </form>
                                                    </div>
                                                </td> --}}

                                                <td class="">
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <a style="margin-right:10px" class="btn p-0 btn-info"
                                                            href="{{ url('/admin/subadmin-edit/' . $sub_admin->id) }}">Edit</a>
                                                        {{-- <form method="post"
                                                            action="{{ route('subadmin-delete', $sub_admin->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger btn-flat show_confirm"
                                                                data-toggle="tooltip">Delete</button>
                                                        </form> --}}
                                                        <form action="{{ route('subadmin-delete', $sub_admin->id) }}"
                                                            method="POST" style="display:inline-block; margin-left: 10px">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn  btn-danger btn-flat show_confirm"
                                                                data-toggle="tooltip">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                                {{-- <td><a href="{{ url('/admin/subadmin-edit/' . $sub_admin->id) }}"><i
                                                            class="fas fa-edit" data-toggle="tooltip" data-placement="top"
                                                            title="edit"></i></a>|
                                                    <a><i class="fas fa-trash text-danger glyphicon glyphicon-trash"
                                                            data-toggle="tooltip" data-placement="top" title="delete"
                                                            data-id="{{ $sub_admin->id }}"></i></a>
                                                    <form id="del_form{{ $sub_admin->id }}"
                                                        action="{{ url('admin/subadmin-delete/' . $sub_admin->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>

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

    <div class="modal fade" id="updatePermissionSubadminModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Permissions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" id="update_user_id">
                        <h6>Select Permissions:</h6>
                        @foreach ($permissions as $permission)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="update_role_{{ $permission->id }}"
                                    name="update_permissions[]" value="{{ $permission->id }}">
                                <label class="form-check-label"
                                    for="update_role_{{ $permission->id }}">{{ $permission->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn btn-primary btn-sm" onclick="updatePermission()">Update</button>
                </div>
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
    <script>
        function openUpdatePermissionSubadminModal(userId) {
            $('#update_user_id').val(userId);
            $.ajax({
                url: '{{ route('get.permissions', ':userId') }}'.replace(':userId',
                    userId),
                method: 'GET',
                success: function(response) {
                    $('input[name="update_permissions[]"]').each(function() {
                        var permissionId = $(this).val();
                        var assigned = response.permissions.some(function(permission) {
                            return permission.id == permissionId;
                        });
                        $(this).prop('checked', assigned);
                    });
                    $('#updatePermissionSubadminModal').modal('show'); // Open the modal
                },
                error: function(xhr, status, error) {
                    console.log("data", xhr);
                    return;
                    Toast.fire({
                        icon: response.alert,
                        title: response.message
                    });
                    console.error(xhr.responseText);
                }
            });
        }

        function updatePermission() {
            var userId = $('#update_user_id').val();
            var permissions = [];
            $('input[name="update_permissions[]"]:checked').each(function() {
                permissions.push($(this).val());
            });
            $.ajax({
                url: '{{ route('update.user.permissions', ':userId') }}'.replace(':userId',
                    userId),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    permissions: permissions
                },
                success: function(response) {
                    toastr.success("Permssions Updated Successfully!")
                    $('#updatePermissionSubadminModal').modal('hide');
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.log("data", xhr);
                    console.error(xhr.responseText);
                }
            });
        }
    </script>
@endsection

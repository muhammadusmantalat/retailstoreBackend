@extends('admin.layout.app')
@section('title', 'index')
@section('content')
<div class="main-content" style="min-height: 562px;">
    <section class="section">
        <div class="section-body">
            <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="col-12">
                                <h4>Departments</h4>
                            </div>
                        </div>
                        <div class="card-body table-striped table-bordered table-responsive">
                            <a class="btn btn-success mb-3" href="{{ route('vendor-departments-create',[$storeManagerId,$id]) }}">Assign
                                Department</a>
                            <table class="table text-center" id="table_id_events">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Store Name</th>
                                        <th>Departments Name</th>
                                        {{-- <th>Status</th> --}}
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($departments->unique('store.id') as $department)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $department->store->store_name ?? '' }}</td>
                                        <td>
                                            @if ($department->store)
                                            @foreach ($departments->where('store.id', $department->store->id) as
                                            $associatedDepartment)
                                            {{ $associatedDepartment->department->department_name }}<br>
                                            @endforeach
                                            @else
                                            <!-- Handle the case where store is not found -->
                                            N/A
                                            @endif
                                        </td>
                                        <td>
                                            <div
                                                style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                <a class="btn btn-info" href="{{ route('vendor-departments-edit', ['storeManagerId' => $storeManagerId, 'id' => $id, 'storeId' => $department->store->id]) }}">Edit</a>

                                                {{-- <form method="post"
                                                    action="{{ route('vendor-departments-destroy', $department->store->id) }}">
                                                    @csrf
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <button type="submit" class="btn btn-danger btn-flat show_confirm"
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

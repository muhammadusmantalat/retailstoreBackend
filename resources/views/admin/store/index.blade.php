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
                                    <h4>Stores</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                <a class="btn btn-success mb-3" href="{{ route('store-detail.create') }}">Add Store</a>
                                <table class="table text-center" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Manager Name</th>
                                            <th>Manager Email</th>
                                            <th>Store Names / Addresses</th>
                                            <th>Store Phone Numbers</th>
                                            <th>Departments</th>
                                            {{-- <th>Status</th> --}}
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stores->unique('user.id') as $store)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @if ($store->user)
                                                        {{ $store->user->first_name}} {{$store->user->last_name}}
                                                    @else
                                                        <!-- Handle the case where user is not found -->
                                                        N/A
                                                    @endif
                                                </td>
                                                {{-- <td>
                                                    {{ $store->user->email}}
                                                </td> --}}
                                                <td>
                                                    @if ($store->user->email)
                                                        <a href="mailto:{{ $store->user->email }}">{{ $store->user->email }}</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($store->user)
                                                        @foreach ($stores->where('user.id', $store->user->id) as $associatedStore)
                                                        {{ $loop->iteration }}. {{ $associatedStore->store_name }} / {{ $associatedStore->store_address }}<br>
                                                        @endforeach
                                                    @else
                                                        <!-- Handle the case where user is not found -->
                                                        N/A
                                                    @endif
                                                </td>

                                                {{-- <td>
                                                    @if ($store->user)
                                                        @foreach ($stores->where('user.id', $store->user->id) as $associatedStore)
                                                            {{ $loop->iteration }}. {{ $associatedStore->store_address }}<br>
                                                        @endforeach
                                                    @else
                                                        <!-- Handle the case where user is not found -->
                                                        N/A
                                                    @endif
                                                </td> --}}


                                                <td>
                                                    @if ($store->user)
                                                        @foreach ($stores->where('user.id', $store->user->id) as $associatedStore)
                                                        {{ $loop->iteration }}.{{ $associatedStore->store_phone_no }}<br>
                                                        @endforeach
                                                    @else
                                                        <!-- Handle the case where user is not found -->
                                                        N/A
                                                    @endif
                                                </td>

                                                <td>
                                                    <a class="btn btn-success"
                                                        href="{{ route('departments', $store->storeManger_id )}}">View</a>
                                                </td>
                                                <td>
                                                   <div style="display: flex; align-items: center; justify-content: center; column-gap: 8px;">
                                                    <a class="btn btn-info"
                                                        href="{{ route('store-detail.edit', $store->storeManger_id) }}">Edit</a>
                                                    {{-- <form method="post"
                                                        action="{{ route('store-detail.destroy',  $store->storeManger_id) }}">
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
                    text: "If you delete this, All stores will be gone forever.",
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

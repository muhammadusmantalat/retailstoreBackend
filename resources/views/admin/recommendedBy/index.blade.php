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
                                    <h4>Recommended By</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                {{-- <a class="btn btn-success mb-3" href="{{ route('ProductsImages-create', $id) }}">Add Images</a> --}}
                                <table class="table text-center" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Store Manager Name</th>
                                            <th>Store Name</th>
                                            <th>Recommended By Vendor</th>
                                        </tr>
                                    </thead>
                                    {{-- <tbody>
                                        @foreach ($recommands as $recommand)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>

                                                <td>{{ $recommand->user->first_name ?? 'N/A' }} {{ $recommand->user->last_name ?? '' }}</td>
                                                <td>{{ $recommand->store->store_name ?? 'N/A' }}</td>
                                                    <td>{{ $recommand->recommendendBy ?? 'N/A' }}</td>

                                            </tr>
                                        @endforeach
                                    </tbody> --}}
                                    <tbody>
                                        {{-- @foreach ($recommands as $recommand)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>

                                                <!-- Store Manager View Button -->
                                                <td>
                                                    <a href="{{ route('store-manager.index', ['store_manager_id' => $recommand->user->id ?? 'N/A']) }}"
                                                       class="btn btn-primary btn-sm">
                                                        View
                                                    </a>
                                                </td>

                                                <!-- Store Name View Button -->
                                                <td>
                                                    <a href="{{ route('store-detail.index', ['store_id' => $recommand->store->id ?? 'N/A']) }}"
                                                       class="btn btn-success btn-sm">
                                                        View
                                                    </a>
                                                </td>

                                                <!-- Recommended By Vendor -->
                                                <td>{{ $recommand->recommendendBy ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach --}}

                                            @foreach ($recommands as $recommand)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>

                                                    <!-- Store Manager Name with Link -->
                                                    <td>
                                                        @if (isset($recommand->user->first_name))
                                                            <a href="#"
                                                               class="deactivate-status"
                                                               data-id="{{ $recommand->id }}"
                                                               data-type="store_manager"
                                                               data-url="{{ route('store-manager.index', ['store_manager_id' => $recommand->user->id]) }}">
                                                                {{ $recommand->user->first_name }}
                                                            </a>
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>

                                                    <!-- Store Name with Link -->
                                                    <td>
                                                        @if (isset($recommand->store->store_name))
                                                            <a href="#"
                                                               class="deactivate-status"
                                                               data-id="{{ $recommand->id }}"
                                                               data-type="store"
                                                               data-url="{{ route('store-detail.index', ['store_id' => $recommand->store->id]) }}">
                                                                {{ $recommand->store->store_name }}
                                                            </a>
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>

                                                    <!-- Recommended By Vendor -->
                                                    <td>{{ $recommand->recommendendBy ?? 'N/A' }}</td>
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
<script>
    $(document).ready(function () {
        $('.deactivate-status').click(function (event) {
            event.preventDefault();

            let recommandId = $(this).data('id');
            let url = $(this).data('url');
            let type = $(this).data('type');

            // Send AJAX request to update status
            $.ajax({
                url: "{{ route('recommand.deactivate') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: recommandId,
                    type: type
                },
                success: function (response) {
                    if (response.success) {
                        // Redirect to the intended page
                        window.location.href = url;
                    } else {
                        alert('Failed to update status.');
                    }
                },
                error: function () {
                    alert('An error occurred. Please try again.');
                }
            });
        });
    });
</script>


@endsection

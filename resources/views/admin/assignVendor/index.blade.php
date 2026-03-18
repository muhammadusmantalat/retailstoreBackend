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
                                     <h4>Manager & Stores</h4>
                                 </div>
                             </div>
                             <div class="card-body table-striped table-bordered table-responsive">
                                 <a class="btn btn-success mb-3" href="{{ route('vendor-assign-create', $id) }}">Assign
                                     Manager</a>
                                 <table class="table text-center" id="table_id_events">
                                     <thead>
                                         <tr>
                                             <th>Sr.</th>
                                             <th>Store Manager Name</th>
                                             <th>Store Names</th>
                                             <th>Departments</th>
                                             <th>Actions</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                        {{-- @dd($id); --}}
                                        @foreach ($assignedVendors ->unique('storeManager.id') as $assignedVendor)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @if ($assignedVendor->storeManager)
                                                        {{ $assignedVendor->storeManager->first_name}} {{$assignedVendor->storeManager->last_name}}
                                                    @else
                                                        <!-- Handle the case where user is not found -->
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    @foreach ($assignedVendors->where('storeManager.id', $assignedVendor->storeManager->id) as $associatedVendor)
                                                        {{ $associatedVendor->store->store_name }}<br>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <a class="btn btn-success"
                                                        href="{{ route('vendor-departments',['storeManagerId' => $assignedVendor->storeManager->id,$id])}}">Assign</a>
                                                </td>
                                                <td>
                                                   <div style="display: flex; align-items: center; justify-content: center; column-gap: 8px;">
                                                    <a class="btn btn-info"
                                                    href="{{ route('vendor-assign-edit', ['storeManagerId' => $assignedVendor->storeManager->id,$id]) }}"
                                                    >Edit</a>
                                                    {{-- <form method="POST" action="{{ route('vendor-assign-destroy', $assignedVendor->storeManager->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-flat show_confirm" data-toggle="tooltip">Delete</button>
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

@extends('managers.auth.layout.app')
@section('title','Forget Password')
@section('content')
<section class=" section">
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="text-center my-2">Select Your Store</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-content">
                            <h5 class="font-16 d-inline">Total Stores # </h5>
                            @php
                            $authId = Auth::guard('web')->id();
                            $count = App\Models\Store::where('storeManger_id',
                            $authId)->count();
                            $stores = App\Models\Store::where('storeManger_id',
                            $authId)->get();
                            @endphp
                            <span class="font-16">
                                {{$count}}
                            </span>
                            <form id="add_student" action="" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <select id="store-dropdown" class="mt-3 form-control" name="store_id"
                                    onchange="updateRoute()">
                                    <option value="" disabled selected>Select Store</option>
                                    @foreach ($stores as $store)
                                    <option value="{{ $store->id }}">{{
                                        $store->store_name }}</option>
                                    @endforeach
                                </select>
                                @error('store_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div class="text-center">
                                    <button type="button" class="btn btn-success mt-3 btn-bg" onclick="submitForm()">Proceed</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
@if (\Illuminate\Support\Facades\Session::has('message'))
<script>
    toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
</script>
@endif

<script>
    function updateRoute() {
        var selectedStoreId = document.getElementById('store-dropdown').value;
        var formAction = "{{ url('manager/dashboard/store') }}";
        document.getElementById('add_student').action = formAction;
    }

    function submitForm() {
        var selectedStoreId = document.getElementById('store-dropdown').value;
        if (!selectedStoreId) {
            toastr.error('Select Store To Proceed!');
        } else {
            document.getElementById('add_student').submit();
        }
    }
</script>
@endsection


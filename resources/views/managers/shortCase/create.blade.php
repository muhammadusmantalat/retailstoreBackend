@extends('managers.layout.app')
@section('title', 'Dashboard')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ route('manager.shortCase') }}">Back</a>
                <form id="add_department" action="{{ route('manager.shortCase.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Add Short Case Reasons</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-4">
                                        <div class="form-group mb-2">
                                            <label>Short Case Reason<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="name" name="name" id="name"
                                                class="form-control">
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                    <input type="hidden" name="store_manager_id" value="{{ $authId }}">
                                    <input type="hidden" name="store_id" value="{{ $storeId }}">

                                </div>
                                <div class="card-footer text-center row">
                                    <div class="col">
                                        <button type="submit" class="btn btn-success mr-1 btn-bg"
                                            id="submit">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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
@endsection

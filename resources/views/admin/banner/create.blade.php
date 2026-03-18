@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

<div class="main-content">
    <section class="section">
        <div class="section-body">
            <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
            <form id="add_department" action="{{ route('banner.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <h4 class="text-center my-4">Add Banner</h4>
                            <div class="row mx-0 px-4">

                                <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                    <div class="form-group mb-2">
                                        <label>Title</label>
                                        <input type="text" placeholder="Enter Title Name" name="name[]" id="name" value="{{ old('name.0') }}" class="form-control">
                                        @error('name.0')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                    <div class="form-group mb-2">
                                        <label>Banner Images</label>
                                        <input type="file" name="image[]" id="image" class="form-control">
                                        @error('image.0')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div id="sizeInputs" class="col-12"></div>

                                <div class="card-footer text-center row">
                                    <div class="col">
                                        <button type="button" class="btn btn-primary mr-1" id="addDepartmentBtn">Add More</button>
                                        <button type="submit" class="btn btn-success mr-1 btn-bg" id="submit">Save</button>
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
    <script>
        $(document).ready(function() {
            let appendCount = 0;
            const maxAppendCount = 2;

            $('#addDepartmentBtn').click(function() {
                if (appendCount < maxAppendCount) {
                    $('#sizeInputs').append(
                        '<div class="row justify-content-center">' +
                        '<div class="col-sm-6 pl-sm-0 pr-sm-3">' +
                        '<div class="form-group mb-2">' +
                        '<label>Title</label>' +
                        '<input type="text" placeholder="Enter Title Name" name="name[]" class="form-control">' +
                        '@error("name")<div class="text-danger">{{ $message }}</div>@enderror' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-sm-6 pl-sm-0 pr-sm-3">' +
                        '<div class="form-group mb-2">' +
                        '<label>Banner Images</label>' +
                        '<input type="file" name="image[]" class="form-control">' +  // Use 'image[]' here
                        '@error("image")<div class="text-danger">{{ $message }}</div>@enderror' +
                        '</div>' +
                        '</div>' +
                        '<button type="button" class="btn btn-danger ml-2 mb-2 removeBtn"><i class="fas fa-trash-alt"></i></button>' +
                        '</div>'
                    );
                    appendCount++;
                }

                if (appendCount >= maxAppendCount) {
                    $(this).prop('disabled', true);
                }
            });

            $(document).on('click', '.removeBtn', function() {
                $(this).closest('.row').remove();
                appendCount--;

                if (appendCount < maxAppendCount) {
                    $('#addDepartmentBtn').prop('disabled', false);
                }
            });
        });
    </script>

@endsection

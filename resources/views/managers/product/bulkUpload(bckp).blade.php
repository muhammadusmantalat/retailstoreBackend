@extends('managers.layout.app')

@section('title', 'Bulk Upload Products')

@section('content')
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Bulk Upload Products</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                {{-- <form action="{{ route('manager.products-bulkUpload') }}" method="POST" enctype="multipart/form-data"> --}}
                                <form id="bulkUploadForm" enctype="multipart/form-data">

                                    @csrf
                                    <div class="form-group">
                                        <label for="file">Upload CSV File</label>
                                        <input type="file" name="file" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                </form>
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
    $('#bulkUploadForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        var uploadButton = $(this).find('button[type="submit"]');
        uploadButton.prop('disabled', true).text('Uploading...');

        $.ajax({
            url: "{{ route('manager.products-bulkUpload') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    toastr.success('Products are being processed. Please check the dashboard to verify their status.');
                } else {
                    toastr.error('An error occurred while processing the file. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                console.log('Error:', error);
                console.log('Response:', xhr.responseText);
                toastr.error('An error occurred during file upload.');
            }
        }).always(function() {
            uploadButton.prop('disabled', false).text('Upload');
        });
    });
</script>


@endsection

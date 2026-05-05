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

                            <form id="bulkUploadForm" enctype="multipart/form-data">

                                @csrf

                                <div class="form-group">
                                    <label for="file">Upload CSV File</label>
                                    <input type="file" name="file" class="form-control" required>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    Upload
                                </button>

                            </form>

                            <!-- 🔥 PROGRESS SECTION -->
                            <div id="progressBox" style="display:none; margin-top:20px;">

                                <h5>Uploading Products...</h5>

                                <div style="width:100%; background:#eee; border-radius:5px;">
                                    <div id="progressBar"
                                         style="width:0%; height:20px; background:green; border-radius:5px;">
                                    </div>
                                </div>

                                <p id="progressText">0 / 0</p>

                            </div>

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
let uploadId = null;

$('#bulkUploadForm').on('submit', function(e) {
    e.preventDefault();

    let formData = new FormData(this);
    let uploadButton = $(this).find('button[type="submit"]');

    uploadButton.prop('disabled', true).text('Uploading...');

    $.ajax({
        url: "{{ route('manager.products-bulkUpload') }}",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(response) {

            if (response.success) {

                uploadId = response.upload_id;

                $('#progressBox').show();

                startProgress(uploadId);

                toastr.success('Upload started successfully');
            } else {
                toastr.error('Upload failed');
            }
        },

        error: function(xhr) {
            console.log(xhr.responseText);
            toastr.error('Something went wrong');
        },

        complete: function() {
            uploadButton.prop('disabled', false).text('Upload');
        }
    });
});

/* =========================
   LIVE PROGRESS TRACKING
========================= */
function startProgress(id) {

    let interval = setInterval(function () {

        $.ajax({
            url: "{{ route('manager.bulk-upload-progress', ':id') }}".replace(':id', id),
            type: "GET",
            success: function (data) {

                let percent = 0;

                if (data.total > 0) {
                    percent = (data.processed / data.total) * 100;
                }

                $('#progressBar').css('width', percent.toFixed(2) + '%');

                $('#progressText').text(
                    data.processed + ' / ' + data.total + ' (' + percent.toFixed(2) + '%)'
                );

                if (data.status === 'completed') {

                    clearInterval(interval);

                    $('#progressBar').css('width', '100%');

                    $('#progressText').append(' ✅ Completed');

                    toastr.success('All products uploaded successfully');
                }
            },

            error: function () {
                clearInterval(interval);
                toastr.error('Progress fetch failed');
            }
        });

    }, 2000);
}

</script>

@endsection
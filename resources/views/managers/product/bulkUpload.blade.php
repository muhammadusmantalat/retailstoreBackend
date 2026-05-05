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
                                    <input type="file" id="fileInput" name="file" class="form-control" required>
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
        let progressInterval = null;
 
        /* =========================
        PAGE LOAD CHECK (RESUME)
        ========================= */
        $(document).ready(function () {

            let savedUploadId = localStorage.getItem('bulk_upload_id');

            if (savedUploadId) {

                checkAndResume(savedUploadId);
            }
            else
            {
                $('#fileInput').prop('disabled', false);
            }
        });

        /* =========================
        FORM SUBMIT (UPLOAD START)
        ========================= */
        $('#bulkUploadForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            let uploadButton = $(this).find('button[type="submit"]');

            uploadButton.prop('disabled', true).text('Uploading...');
            $('#fileInput').prop('disabled', true);
            $.ajax({
                url: "{{ route('manager.products-bulkUpload') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,

                success: function(response) {

                    if (response.success) {

                        uploadId = response.upload_id;
                         $('#fileInput').prop('disabled', true);
                        // ✅ Save for persistence
                        localStorage.setItem('bulk_upload_id', uploadId);

                        // ✅ Show progress bar
                        $('#progressBox').fadeIn();

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
        RESUME CHECK FUNCTION
        ========================= */
        function checkAndResume(id) {

            $.ajax({
                url: "{{ route('manager.bulk-upload-progress', ':id') }}".replace(':id', id),
                type: "GET",
                success: function (data) {

                    if (data.status !== 'completed') {

                        $('#progressBox').show();
                        $('#fileInput').prop('disabled', true);
                        startProgress(id);

                    } else {
                        localStorage.removeItem('bulk_upload_id');
                        $('#fileInput').prop('disabled', false);
                    }
                },
                error: function () {
                    localStorage.removeItem('bulk_upload_id');
                    $('#fileInput').prop('disabled', false);
                }
            });
        }

        /* =========================
        LIVE PROGRESS TRACKING
        ========================= */
        function startProgress(id) {

            // ❌ Prevent multiple intervals
            if (progressInterval) {
                clearInterval(progressInterval);
            }

            progressInterval = setInterval(function () {

                $.ajax({
                    url: "{{ route('manager.bulk-upload-progress', ':id') }}".replace(':id', id),
                    type: "GET",

                    success: function (data) {

                        let percent = 0;

                        if (data.total > 0) {
                            percent = (data.processed / data.total) * 100;
                        }

                        percent = percent.toFixed(2);

                        $('#progressBar').css('width', percent + '%');
                        data.total = data.total - 1;
                        $('#progressText').text(
                            data.processed + ' / ' + data.total + ' (' + percent + '%)'
                        );

                        /* ===== COMPLETED ===== */
                        if (data.status === 'completed') {

                            clearInterval(progressInterval);

                            $('#progressBar').css('width', '100%');
                            $('#progressText').text('Completed ✅');

                            toastr.success('All products uploaded successfully');

                            // ✅ Remove from storage
                            localStorage.removeItem('bulk_upload_id');
                            $('#fileInput').prop('disabled', false);
                            // ✅ Hide after delay
                            setTimeout(() => {
                                $('#progressBox').fadeOut();
                            }, 2000);
                        }
                    },

                    error: function () {
                        clearInterval(progressInterval);
                        toastr.error('Progress fetch failed');
                    }
                });

            }, 2000);
        }
    </script>
@endsection
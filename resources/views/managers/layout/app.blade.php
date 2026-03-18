<!DOCTYPE html>
<html lang="en">
<!-- index.html  21 Nov 2019 03:44:50 GMT -->

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Store Dashboard</title>
    <!-- Developed By Ranglerz -->
    <link rel="stylesheet"
        href="https://www.ranglerz.com/cost-to-make-a-web-ios-or-android-app-and-how-long-does-it-take.php">
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/app.min.css') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/components.css') }}">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/toastr/css/toastr.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/custom.css') }}">
    <link rel='shortcut icon' type='image/x-icon' href='{{ asset('public/admin/assets/images/logo.png') }}' />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/datatables.css') }}">
    <!-- Multiple selector -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/bundles/jquery-selectric/selectric.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet"
        href="{{ asset('public/admin/assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/bundles/datatables/datatables.min.css') }}">
</head>

<style>
    .notification-list {
        padding: 5px;
    }

    #notification-list {
        max-height: 500px;
        overflow-y: auto;
        padding: 10px;
    }

    .notification-item {
        display: flex;
        align-items: center;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 10px;
        transition: background-color 0.3s ease, transform 0.2s ease;
        text-decoration: none;
        color: #333;
        background-color: #D3D3D3;
    }

    .notification-item:hover {
        background-color: #e2e2e2;
        transform: translateX(5px);
    }

    .notification-avatar {
        margin-right: 15px;
    }

    .notification-avatar img {
        width: 55px;
        height: 55px;
        border-radius: 50%;
    }

    .notification-desc {
        flex-grow: 1;
    }

    .notification-user {
        font-weight: bold;
        color: #007bff;
    }

    .notification-text {
        font-size: 15px;
        color: #333;
        white-space: wrap;
        display: block;
    }

    .notification-time {
        font-size: 13px;
        color: #888;
    }

    .notification-item-unread {
        background-color: #eaf0f7;
        border-left: 5px solid #007bff;
    }

    #no-notifications {
        text-align: center;
        color: #dc3545;
        font-size: 1.2em;
        padding: 10px;
        margin-top: 10px;
        border: 1px solid #dc3545;
        border-radius: 5px;
        background-color: #f8d7da;
    }

    .notification-loader {
        border: 16px solid #f3f3f3;
        border-top: 16px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 2s linear infinite;
        display: block;
        margin: 20px auto;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<body>
    <div class="loader"></div>

    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('managers.common.header')
            @include('managers.common.side_menu')
            @yield('content')
            @include('managers.common.footer')
        </div>
    </div>
    <!-- General JS Scripts -->
    <script src="{{ asset('public/admin/assets/js/app.min.js') }}"></script>
    <!-- JS Libraies -->
    <script src="{{ asset('public/admin/assets/bundles/apexcharts/apexcharts.min.js') }}"></script>
    <!-- Page Specific JS File -->
    <script src="{{ asset('public/admin/assets/js/page/index.js') }}"></script>
    <!-- Template JS File -->
    <script src="{{ asset('public/admin/assets/js/scripts.js') }}"></script>
    <!-- Custom JS File -->
    <script src="{{ asset('public/admin/assets/js/custom.js') }}"></script>
    <script src="{{ asset('public/admin/assets/toastr/js/toastr.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/js/datatables.js') }}"></script>
    <!-- Multiple selector -->
    <script src="{{ asset('public/admin/assets/js/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/bundles/jquery-selectric/jquery.selectric.min.js') }}"></script>

    <script src="{{ asset('public/admin/assets/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}">
    </script>
    <script src="{{ asset('public/admin/assets/js/page/datatables.js') }}"></script>
    <script>
        $(document).ready(function() {
            function updateOrderCounter() {
                $.ajax({
                    url: "{{ url('manager/storeManagerOrdercount') }}",
                    type: 'GET',
                    success: function(response) {
                        $('#orderCounter').text(response.count);
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            }
            updateOrderCounter();
            setInterval(updateOrderCounter, 1000);
        });
    </script>

    <script>
        function fetchNotifications() {
            $('.notification-loader').show(); // Show loader before fetching

            $.ajax({
                url: "{{ route('manager.notifications.index') }}", // Adjust the URL as per your route setup
                type: 'GET',
                success: function(data) {
                    console.log("data", data);
                    $('.notification-loader').hide(); // Hide loader on success
                    // Clear existing notifications
                    $('#notificationList').empty();

                    // Update the notification counter
                    $('#notificationCounter').text(data.unreadCount);

                    // Check if there are no notifications
                    if (data.notifications.length === 0) {
                        $('#notificationList').append('<p id="no-notifications">No notifications!</p>');
                    } else {
                        // Append new notifications
                        data.notifications.forEach(function(notification) {
                            const timeAgo = moment(notification.created_at).fromNow();
                            const truncatedMessage = notification.body.split(' ').slice(0, 5).join(
                                ' ') + (notification.body.split(' ').length > 5 ? '...' : '');

                            $('#notificationList').append(`
                            <div id="drop-item-${notification.id}" class="notify-item notification-item ${notification.seenByUser === '1' ? '' : 'notification-item-unread'}" data-id="${notification.id}">
                                <span class="notification-avatar text-white">
                                    <img alt="image" src="{{ asset('public/admin/assets/images/avator.png') }}" class="rounded-circle">
                                </span>
                                <span class="notification-desc">
                                    <span class="notification-user">${notification.title}</span>
                                    <span class="notification-text">${truncatedMessage}</span>
                                    <span class="notification-time">${timeAgo}</span>
                                </span>
                            </div>
                        `);
                        });
                    }

                    // Attach click event to mark individual notification as read
                    $('.notify-item').click(function(event) {
                        event.preventDefault();
                        var notificationId = $(this).data('id');

                        // Check if notification is already marked as read
                        if ($(this).hasClass('notification-item-unread')) {
                            $.ajax({
                                url: "{{ route('manager.notification.marked', ['notificationId' => ':notificationId']) }}"
                                    .replace(':notificationId', notificationId),
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(data) {
                                    toastr.success(data.message);
                                    fetchNotifications();
                                },
                                error: function(xhr) {
                                    toastr.error(
                                        'An error occurred while marking the notification as read.'
                                        );
                                }
                            });
                        }
                    });
                },
                error: function(xhr) {
                    console.log("data", xhr);
                    $('.notification-loader').hide(); // Hide loader on error
                    console.log('An error occurred while fetching notifications.');
                }
            });
        }

        // Initial fetch
        fetchNotifications();
        // Periodically fetch notifications every 30 seconds
        setInterval(fetchNotifications, 30000); // Adjusted to 30 seconds for efficiency

        $('.markAllRead').click(function(event) {
            event.preventDefault();

            $.ajax({
                url: "{{ route('manager.notification.read') }}", // Adjust the URL as per your route setup
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    toastr.success(data.message);
                    fetchNotifications(); // Refresh notifications after marking all as read
                },
                error: function(xhr) {
                    console.log("data", xhr);
                    toastr.error('An error occurred while marking notifications as read.');
                }
            });
        });
    </script>



    <script>
        /*toastr popup function*/
        function toastrPopUp() {
            toastr.options = {
                "closeButton": true,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "3000",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        }

        /*toastr popup function*/
        toastrPopUp();
    </script>
    @yield('js')
</body>

</html>

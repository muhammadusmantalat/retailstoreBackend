<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar sticky">
    <div class="form-inline mr-auto">
        <ul class="navbar-nav">
            <div class="d-flex align-items-center">
                <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn"> <i
                            data-feather="align-justify"></i></a></li>
                    {{-- @php
                    $data = App\Models\User::find(Auth::guard('web')->id());
                @endphp
                <li class="d-flex align-items-center">
                    @if ($data)
                        <h5 class="mb-0">Welcome {{ $data->first_name ?? '' }} {{ $data->last_name ?? '' }} !</h5>
                    @endif
                </li> --}}

            </div>
            <div class="py-0 card-body">
                <div class="card-content">
                    @php
                        $authId = Auth::guard('web')->id();
                        $count = App\Models\Store::where('storeManger_id', $authId)->count();
                        $stores = App\Models\Store::where('storeManger_id', $authId)->get();
                        $currentStore = App\Models\StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
                    @endphp
                    <form id="add_student" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <select id="store-dropdown" class="form-control" name="store_id" onchange="updateRoute()">
                            <option value="" disabled selected>Select Store</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}" @if ($currentStore->store_id == $store->id) selected @endif>
                                    {{ $store->store_name }}</option>
                            @endforeach
                        </select>
                        @error('$store->id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </form>
                </div>
            </div>
        </ul>


    </div>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle">
                <i data-feather="bell"></i>
                <span class="badge headerBadge1" id="notificationCounter">0</span>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                <div class="dropdown-header">
                    Notifications
                    <div class="float-right">
                        <a href="#" id="markAllRead" class="markAllRead">Mark All As Read</a>
                    </div>
                </div>
                <div id="notification-loader" class="notification-loader" style="display: none;"></div>
                <div class="dropdown-list-content dropdown-list-message notification-list" id="notificationList">
                    <!-- Notifications will be appended here -->
                </div>
            </div>
        </li>

        <li class="dropdown"><a href="#" data-toggle="dropdown"
                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image"
                    src="{{ asset(isset(Auth::guard('web')->user()->image) ? Auth::guard('web')->user()->image : 'public/admin/assets/images/avator.png') }}"
                    class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span></a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
                <a href="{{ url('manager/profile') }}" class="dropdown-item has-icon"> <i class="far fa-user"></i>
                    Profile
                    <div class="dropdown-divider"></div>
                    <a href="{{ url('manager/logout') }}" class="dropdown-item has-icon text-danger"> <i
                            class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
            </div>
        </li>
    </ul>
</nav>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#store-dropdown').change(function() {
            var selectedStoreId = $(this).val();
            if (selectedStoreId) {
                // Construct the URL with the selected store ID as a request parameter
                var storeUrl = "{{ url('manager/dashboard/store') }}?store_id=" + selectedStoreId;
                // Redirect to the constructed URL
                window.location.href = storeUrl;
            }
        });
    });
</script>

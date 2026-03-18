<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#"> <img alt="image" src="{{ asset('public/admin/assets/images/logo.png') }}" class="header-logo"
                    style="width: 100px; height: 100px; object-fit: contain;" />
                {{-- <span class="logo-name">Find Dimessions</span> --}}
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown {{ request()->is('manager/manager-dashboard') ? 'active' : '' }}">
                <a href="{{ url('manager/manager-dashboard') }}" class="nav-link"><i
                        data-feather="home"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown {{ request()->is('manager/manager-store-department*')|| request()->is('manager/departments-add*') || request()->is('manager/departments-edit*') ? 'active' : '' }} ? 'active' : '' }}">
                <a href="{{ route('manager.manager-store-department') }}" class="nav-link"><i
                        data-feather="layers"></i><span>Departments</span></a>
            </li>
             
            {{-- <li class="dropdown {{ request()->is('manager/storeSaleManager*') ? 'active' : '' }}">
                <a href="{{ route('manager.storeSaleManager.index') }}" class="nav-link"><i data-feather="users"></i><span>Salesmen</span></a>
            </li> --}}
            <li class="dropdown {{ request()->is('manager/storeManagerVendor*') ? 'active' : '' }}">
                <a href="{{ route('manager.storeManagerVendor') }}" class="nav-link"><i data-feather="users"></i><span>Wholesalers</span></a>
            </li>
            <li class="dropdown {{ request()->is('manager/storeManagerProducts*') ? 'active' : '' }}">
                <a href="{{ route('manager.storeManagerProducts') }}" class="nav-link"><i data-feather="shopping-cart"></i><span>Products</span></a>
            </li>


            @php
            $count = App\Models\Orders::where('status', 'in-progress')->count();
        @endphp
        </li>

            {{-- <li class="dropdown {{ request()->is('') ? 'active' : '' }}">
                <a href="{{ route('') }}" class="nav-link">
                    <i data-feather="bell"></i><span>Orders</span>

                    @if ($count > 0)
                        <div class="badge badge-danger text-white rounded-circle">
                            {{ $count }}
                        </div>
                    @else
                        <div class="badge badge-danger text-white rounded-circle">
                            0
                        </div>
                    @endif
                </a>
            </li> --}}

            <li class="dropdown {{ request()->is('manager/storeManagerOrder*') ? 'active' : '' }}">
                <a href="{{ route('manager.storeManagerOrder.index') }}" class="nav-link padding" style="padding-left: 27px">
                    <i data-feather="bell"></i>
                    <span>Orders</span>
                    <div id="orderCounter"
                        class="badge {{ request()->is('manager/storeManagerOrder*') ? 'bg-white text-primary' : 'bg-primary text-white' }} rounded-circle ">
                    </div>
                </a>
            </li>

            <li class="dropdown {{ request()->is('manager/immediateOrder*') ? 'active' : '' }}">
                <a href="{{ route('manager.immediateOrder.index') }}" class="nav-link padding" style="padding-left: 27px">
                    <i data-feather="bell"></i>
                    <span>Immediate Order</span>
                </a>
            </li>

            <li class="dropdown {{ request()->is('manager/shortCase*') ? 'active' : '' }}">
                <a href="{{ route('manager.shortCase') }}" class="nav-link"><i data-feather="file-minus"></i><span>Short Case Reasons</span></a>
            </li>
            <li class="dropdown {{ request()->is('manager/sales*') ? 'active' : '' }}">
                <a href="{{ route('manager.sales') }}" class="nav-link"><i data-feather="clipboard"></i><span>Reports</span></a>
            </li>

            {{-- <li class="dropdown {{ request()->is('admin/policy*') ? 'active' : '' }}">
                <a href="{{ route('policy.index') }}" class="nav-link"><i data-feather="monitor"></i><span>Privacy
                        Policy</span></a>
            </li>
            <li class="dropdown {{ request()->is('admin/terms*') ? 'active' : '' }}">
                <a href="{{ route('terms.index') }}" class="nav-link"><i
                        data-feather="monitor"></i><span>Terms & Conditions</span></a> --}}
        </ul>
    </aside>
</div>

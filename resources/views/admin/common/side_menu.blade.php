{{-- <div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">

        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ url('/admin/dashboard') }}" class="nav-link"><i
                        data-feather="home"></i><span>Dashboard</span></a>
            </li>



            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Sub Admins'))
                <li class="dropdown {{ request()->is('admin/subadmin*') ? 'active' : '' }}">
                    <a href="{{ route('subadmin') }}" class="nav-link"><i class="fa fa-users"></i><span>Sub
                            Admins</span></a>
                </li>
            @elseif(auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/subadmin*') ? 'active' : '' }}">
                    <a href="{{ route('subadmin') }}" class="nav-link"><i class="fa fa-users"></i><span>Sub
                            Admins</span></a>
                </li>
            @endif




            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('StoreManagers'))
                <li class="dropdown {{ request()->is('admin/store-manager*') ? 'active' : '' }}">
                    <a href="{{ route('store-manager.index') }}" class="nav-link"><i class="fa fa-users"></i><span>Store Managers
                        </span></a>
                </li>
            @elseif(auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/store-manager*') ? 'active' : '' }}">
                    <a href="{{ route('store-manager.index') }}" class="nav-link"><i class="fa fa-users"></i><span>Store Managers</span></a>
                </li>
            @endif




            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Stores'))
            <li class="dropdown {{ request()->is('admin/store-detail*') || request()->is('admin/departments*') || request()->is('admin/editDepartments*') ? 'active' : '' }}">
                <a href="{{ route('store-detail.index') }}" class="nav-link"><i
                    data-feather="box"></i><span>Stores
                    </span></a>
            </li>
        @elseif(auth()->guard('admin')->check())
            <li class="dropdown {{ request()->is('admin/store-detail*') || request()->is('admin/departments*') || request()->is('admin/editDepartments*') ? 'active' : '' }}">
                <a href="{{ route('store-detail.index') }}" class="nav-link"><i
                    data-feather="box"></i><span>Stores</span></a>
            </li>
        @endif



            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Vendors'))
            <li class="dropdown {{ request()->is('admin/vendors*') ? 'active' : '' }}">
                <a href="{{ route('vendors') }}" class="nav-link"><i data-feather="users"></i><span>wholesalers
                    </span></a>
            </li>
        @elseif(auth()->guard('admin')->check())
            <li class="dropdown {{ request()->is('admin/vendors*') ? 'active' : '' }}">
                <a href="{{ route('vendors') }}" class="nav-link"><i data-feather="users"></i><span>Wholesalers</span></a>
            </li>
        @endif



            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Products'))
            <li class="dropdown {{ request()->is('admin/products*') ? 'active' : '' }}">
                <a href="{{ route('products') }}" class="nav-link"><i data-feather="shopping-cart"></i><span>Products
                    </span></a>
            </li>
        @elseif(auth()->guard('admin')->check())
            <li class="dropdown {{ request()->is('admin/products*') ? 'active' : '' }}">
                <a href="{{ route('products') }}" class="nav-link"><i data-feather="shopping-cart"></i><span>Products</span></a>
            </li>
        @endif


        @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('banner'))
        <li class="dropdown {{ request()->is('admin/banner*') ? 'active' : '' }}">
            <a href="{{ route('banner') }}" class="nav-link"><i data-feather="image"></i><span>Banners
                </span></a>
        </li>
    @elseif(auth()->guard('admin')->check())
        <li class="dropdown {{ request()->is('admin/banner*') ? 'active' : '' }}">
            <a href="{{ route('banner') }}" class="nav-link"><i data-feather="image"></i><span>Banners</span></a>
        </li>
    @endif



            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('PrivacyPolicy'))
            <li class="dropdown {{ request()->is('admin/policy*') ? 'active' : '' }}">
                <a href="{{ route('policy.index') }}" class="nav-link"><i data-feather="shield"></i><span>Privacy Policy
                    </span></a>
            </li>
        @elseif(auth()->guard('admin')->check())
            <li class="dropdown {{ request()->is('admin/policy*') ? 'active' : '' }}">
                <a href="{{ route('policy.index') }}" class="nav-link"><i data-feather="shield"></i><span>Privacy Policy</span></a>
            </li>
        @endif


            </li>
            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Terms&Conditions'))
            <li class="dropdown {{ request()->is('admin/terms*') ? 'active' : '' }}">
                <a href="{{ route('terms.index') }}" class="nav-link"><i data-feather="file-text"></i><span>Terms & Conditions
                    </span></a>
            </li>
        @elseif(auth()->guard('admin')->check())
            <li class="dropdown {{ request()->is('admin/terms*') ? 'active' : '' }}">
                <a href="{{ route('terms.index') }}" class="nav-link"><i data-feather="file-text"></i><span>Terms & Conditions</span></a>
            </li>
        @endif
        </ul>
    </aside>
</div> --}}


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
            <li class="dropdown {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ url('/admin/dashboard') }}" class="nav-link">
                    <i data-feather="home"></i><span>Dashboard</span>
                </a>
            </li>

            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Sub Admins') || auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/subadmin*') ? 'active' : '' }}">
                    <a href="{{ route('subadmin') }}" class="nav-link">
                        <i class="fa fa-users"></i><span>Sub Admins</span>
                    </a>
                </li>
            @endif

            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('StoreManagers') || auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/store-manager*') ? 'active' : '' }}">
                    <a href="{{ route('store-manager.index') }}" class="nav-link">
                        <i class="fa fa-users"></i><span>Store Managers</span>
                    </a>
                </li>
            @endif

            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('RecommendedBy') || auth()->guard('admin')->check())
            <li class="dropdown {{ request()->is('admin/recommendedBy*') ? 'active' : '' }}">
                <a href="{{ route('recommendedBy') }}" class="nav-link">
                    <i class="fa fa-handshake"></i><span>Recommended By</span>
                    <div id="recommendedCounter"
                   class="badge {{ request()->is('admin/recommendedBy*') ? 'bg-white text-primary' : 'bg-primary text-white' }} rounded-circle ">
               </div>
                </a>
            </li>
        @endif



            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Stores') || auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/store-detail*') || request()->is('admin/departments*') || request()->is('admin/editDepartments*') ? 'active' : '' }}">
                    <a href="{{ route('store-detail.index') }}" class="nav-link">
                        <i data-feather="box"></i><span>Stores</span>
                    </a>
                </li>
            @endif

            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Vendors') || auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/vendors*') ? 'active' : '' }}">
                    <a href="{{ route('vendors') }}" class="nav-link">
                        <i data-feather="users"></i><span>Wholesalers</span>
                    </a>
                </li>
            @endif

            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Products') || auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/products*') ? 'active' : '' }}">
                    <a href="{{ route('products') }}" class="nav-link">
                        <i data-feather="shopping-cart"></i><span>Products</span>
                    </a>
                </li>
            @endif

            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Orders') || auth()->guard('admin')->check())
            <li class="dropdown {{ request()->is('admin/order*') ? 'active' : '' }}">
               <a href="{{ route('Order.index') }}" class="nav-link padding" style="padding-left: 27px">
                   <i data-feather="bell"></i>
                   <span>Orders</span>
                   <div id="orderCounter"
                       class="badge {{ request()->is('admin/order*') ? 'bg-white text-primary' : 'bg-primary text-white' }} rounded-circle ">
                   </div>
               </a>
           </li>
       @endif

       @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('hotSalingProduct') || auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/hotSalingProduct*') ? 'active' : '' }}">
                    <a href="{{ route('hotSalingProduct.index') }}" class="nav-link">
                        <i data-feather="shopping-cart"></i><span>Hot Selling Products</span>
                    </a>
                </li>
            @endif

            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('banner') || auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/banner*') ? 'active' : '' }}">
                    <a href="{{ route('banner') }}" class="nav-link">
                        <i data-feather="image"></i><span>Banners</span>
                    </a>
                </li>
            @endif

            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('social-link') || auth()->guard('admin')->check())
            <li class="dropdown {{ request()->is('admin/social-link*') ? 'active' : '' }}">
                <a href="{{ route('social-link.index') }}" class="nav-link">
                    <i data-feather="link"></i><span>Social Links</span>
                </a>
            </li>
        @endif

            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Privacy_Policy') || auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/policy*') ? 'active' : '' }}">
                    <a href="{{ route('policy.index') }}" class="nav-link">
                        <i data-feather="shield"></i><span>Privacy Policy</span>
                    </a>
                </li>
            @endif

            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Terms&Conditions') || auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/terms*') ? 'active' : '' }}">
                    <a href="{{ route('terms.index') }}" class="nav-link">
                        <i data-feather="file-text"></i><span>Terms & Conditions</span>
                    </a>
                </li>
            @endif




        </ul>
    </aside>
</div>

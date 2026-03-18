@extends('managers.layout.app')
@section('title', 'Dashboard')
@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="row mb-3">
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <a class="card text-decoration-none" href="{{ url('manager/manager-store-department') }}">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row">
                                    @php
                                        $authId = Auth::guard('web')->user()->id;
                                        $storeId = App\Models\StoreManagerStoreDepartment::where('store_manager_id',$authId)->value('store_id');
                                        // dd($storeId);
                                        $departmets = App\Models\Department::where('store_id',$storeId)->count();
                                    @endphp
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                        <div class="card-content">
                                            <h5 class="font-15">Total Departments</h5>
                                            <h2 class="mb-3 font-18">{{ $departmets }}</h2>
                                            {{--  <p class="mb-0"><span class="col-green">10%</span> Increase</p>  --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                        <div class="banner-img">
                                           <img src="{{ asset('public/admin/assets/images/banner/Admin Icons_Departments.png')}}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <a class="card text-decoration-none" href="{{ route('manager.storeManagerVendor') }}">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                        <div class="card-content">
                                            <h5 class="font-15"> Total Wholesalers</h5>
                                            <h2 class="mb-3 font-18">{{ $vendor_count }}</h2>
                                            {{--  <p class="mb-0"><span class="col-orange">09%</span> Decrease</p>  --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                        <div class="banner-img">
                                            <img src="{{ asset('public/admin/assets/images/banner/Admin Icons_Vendors.png')}}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <a class="card text-decoration-none" href="{{ route('manager.storeManagerProducts') }}">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                        <div class="card-content">
                                            <h5 class="font-15">Products</h5>
                                            <h2 class="mb-3 font-18">{{ $product_count }}</h2>
                                             {{-- <p class="mb-0"><span class="col-green">18%</span>
                                                Increase</p> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                        <div class="banner-img">
                                            <img src="{{ asset('public/admin/assets/images/banner/Admin Icons_Products.png')}}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
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


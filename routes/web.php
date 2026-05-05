<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\SocialController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\AboutusController;
use App\Http\Controllers\Managers\ManagerProduct;
use App\Http\Controllers\Admin\SubAdminController;
use App\Http\Controllers\Managers\AuditController;
use App\Http\Controllers\Managers\OrderController;
use App\Http\Controllers\Managers\ReportController;
use App\Http\Controllers\Admin\AddProductController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\DepartmentsController;
use App\Http\Controllers\Admin\AssignVendorController;
use App\Http\Controllers\Admin\AssignProductController;
use App\Http\Controllers\Admin\ProductsImageController;
use App\Http\Controllers\Admin\RecommendedByController;
use App\Http\Controllers\Admin\StoreManagersController;
use App\Http\Controllers\Admin\TermConditionController;
use App\Http\Controllers\Admin\AssignVendorToDepartment;
use App\Http\Controllers\Admin\hotSaleProductController;
use App\Http\Controllers\Admin\ProductFlavourController;
use App\Http\Controllers\Managers\NotificationController;
use App\Http\Controllers\Managers\ProductImageController;
use App\Http\Controllers\Managers\AddVendorToStoreManager;
use App\Http\Controllers\Managers\ManagerStoresController;
use App\Http\Controllers\Managers\ProductFlavorController;
use App\Http\Controllers\Managers\ProductVendorController;
use App\Http\Controllers\Managers\ImmediateOrderController;
use App\Http\Controllers\Managers\ShortCaseReasonController;
use App\Http\Controllers\Managers\AssignVendorToStoreManager;
use App\Http\Controllers\Managers\ManagerDepartmentController;
use App\Http\Controllers\Managers\ProductDepartmentController;
use App\Http\Controllers\Managers\StoreSaleManagerController;
use App\Http\Controllers\Admin\ProductAssignToVendorController;
use App\Http\Controllers\Admin\ProductAssignToDepartmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
/*
Admin routes
 * */

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('get-privacy-policy', [PolicyController::class, 'getPrivacyPolicy']);
Route::get('get-term-condition', [TermConditionController::class, 'getTermAndCondition']);
Route::get('get-contact-us', [TermConditionController::class, 'getContactUs']);


//  admin auth routes
Route::get('/admin-login', [AuthController::class, 'getLoginPage'])->name('admin-login');
Route::post('admin/login', [AuthController::class, 'Login']);
Route::get('/admin-forgot-password', [AdminController::class, 'forgetPassword']);
Route::post('/admin-reset-password-link', [AdminController::class, 'adminResetPasswordLink']);
Route::get('/change_password/{id}', [AdminController::class, 'change_password']);
Route::post('/admin-reset-password', [AdminController::class, 'ResetPassword']);

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('dashboard', [AdminController::class, 'getdashboard'])->name('adminDashboard');
    Route::get('profile', [AdminController::class, 'getProfile']);
    Route::post('update-profile', [AdminController::class, 'update_profile']);
    Route::get('logout', [AdminController::class, 'logout']);

    /** resource controller */
    Route::resource('store-manager', StoreManagersController::class)->middleware('permission:StoreManagers');
    Route::resource('store-detail', StoreController::class)->middleware('permission:Stores');
    Route::resource('about', AboutusController::class);
    Route::resource('policy', PolicyController::class)->middleware('permission:Privacy_Policy');
    Route::resource('terms', TermConditionController::class)->middleware('permission:Terms&Conditions');
    Route::resource('faq', FaqController::class);



    /** admin side departments */
    Route::get('departments/{id}', [DepartmentsController::class, 'departments'])->name('departments');
    Route::post('departments/{id}', [DepartmentsController::class, 'save'])->name('departments-save');
    Route::get('departments/new/{id}', [DepartmentsController::class, 'new'])->name('departments-new');
    Route::get('editDepartments/{id}', [DepartmentsController::class, 'editDepartment'])->name('editDepartments');
    Route::put('updateDepartments/{id}', [DepartmentsController::class, 'updateDepartment'])->name('updateDepartments');
    Route::delete('destroy/{id}', [DepartmentsController::class, 'destroy'])->name('destroy');
    Route::get('admin/check-products/{id}', [DepartmentsController::class, 'checkProducts'])->name('store-manager.checkProducts');

    /**  admin side vendors */
    Route::get('vendors', [VendorController::class, 'index'])->name('vendors')->middleware('permission:Vendors');
    Route::get('vendors/create', [VendorController::class, 'create'])->name('vendors-create');
    Route::post('vendors-save', [VendorController::class, 'save'])->name('vendor-save');
    Route::get('vendors-edit/{id}', [VendorController::class, 'edit'])->name('vendor-edit');
    Route::put('vendors-update/{id}', [VendorController::class, 'update'])->name('update-vendor');
    Route::delete('vendors-destroy/{id}', [VendorController::class, 'destroy'])->name('vendor-destroy');

    /**  admin side assigning store manager and store */
    Route::get('vendors-assign/{id}', [AssignVendorController::class, 'assignVendor'])->name('vendor-assign');
    Route::get('vendors-assign/create/{id}', [AssignVendorController::class, 'create'])->name('vendor-assign-create');
    Route::post('vendors-assign/save/{id}', [AssignVendorController::class, 'assignVendorSave'])->name('vendor-assign-save');
    Route::get('/vendors-assign-edit/{storeManagerId}/{id}', [AssignVendorController::class, 'assignVendorEdit'])->name('vendor-assign-edit');
    Route::put('vendors-assign/update/{id}', [AssignVendorController::class, 'assignVendorUpdate'])->name('vendor-assign-update');
    // Route::delete('vendors-assign-destroy/{id}', [AssignVendorController::class, 'assignVendorDestroy'])->name('vendor-assign-destroy');
    Route::delete('vendors-assign-destroy', [AssignVendorController::class, 'assignVendorDestroy'])->name('vendor-assign-destroy');

    /**  admin side assigning departments */
    Route::get('vendors-departments/{storeManagerId}/{id}', [AssignVendorToDepartment::class, 'vendorDepartments'])->name('vendor-departments');
    Route::get('vendors-departments-create/{storeManagerId}/{id}', [AssignVendorToDepartment::class, 'vendorDepartmentCreate'])->name('vendor-departments-create');
    Route::post('vendors-departments-save/{storeManagerId}/{id}', [AssignVendorToDepartment::class, 'vendorDepartmentSave'])->name('vendor-departments-save');
    Route::get('/vendors-departments-edit/{storeManagerId}/{id}/{storeId}', [AssignVendorToDepartment::class, 'vendorDepartmentEdit'])->name('vendor-departments-edit');
    Route::put('vendors-assign-update/{storeManagerId}', [AssignVendorToDepartment::class, 'vendorDepartmentUpdate'])->name('vendor-departments-update');
    Route::delete('vendors-assign-delete', [AssignVendorToDepartment::class, 'vendorDepartmentDestroy'])->name('vendor-departments-destroy');

    /**  admin side dependent ajix routes */
    Route::get('/get-store/{id}', [AssignVendorController::class, 'getStores']);
    Route::get('/get-departments/{id}', [AssignVendorToDepartment::class, 'getDepartments']);

    /**  admin side products */
    // Route::get('products', [AddProductController::class, 'index'])->name('products')->middleware('permission:Products');

    Route::get('/products', [AddProductController::class, 'index'])->name('products');
    Route::get('/products-ajax', [AddProductController::class, 'getProducts'])->name('products.ajax');

    Route::get('products-craete', [AddProductController::class, 'create'])->name('products-create')->middleware('permission:Products');
    Route::post('products-save', [AddProductController::class, 'save'])->name('products-save')->middleware('permission:Products');
    Route::get('products-edit/{storeId}/{id}', [AddProductController::class, 'edit'])->name('products-edit')->middleware('permission:Products');
    Route::put('products-update/{id}', [AddProductController::class, 'update'])->name('products-update')->middleware('permission:Products');
    Route::delete('products-delete/{id}', [AddProductController::class, 'destroy'])->name('products-delete')->middleware('permission:Products');

    Route::get('products-Images/{id}', [ProductsImageController::class, 'index'])->name('ProductsImages');
    Route::get('products-Images/create/{id}', [ProductsImageController::class, 'create'])->name('ProductsImages-create');
    Route::post('products-Images/save', [ProductsImageController::class, 'store'])->name('ProductsImages-save');
    Route::get('products-Images-edit/{id}', [ProductsImageController::class, 'edit'])->name('ProductsImages-edit');
    Route::put('products-Images-update/{id}', [ProductsImageController::class, 'update'])->name('ProductsImages-update');
    Route::delete('products-Images-delete/{productId}/{imageId}', [ProductsImageController::class, 'destory'])->name('ProductsImages-delete');

    /**  admin side products flavour */
    Route::get('products-flavours/{id}', [ProductFlavourController::class, 'index'])->name('products-flavours');
    Route::get('products-flavours-create/{id}', [ProductFlavourController::class, 'create'])->name('products-flavours-create');
    Route::post('products-flavours-save', [ProductFlavourController::class, 'store'])->name('products-flavours-save');
    Route::get('products-flavours-edit/{productId}/{flavourId}', [ProductFlavourController::class, 'edit'])->name('products-flavours-edit');
    Route::put('products-flavours-update/{flavourId}', [ProductFlavourController::class, 'update'])->name('products-flavours-update');
    Route::delete('products-flavours-delete/{productId}/{flavourId}', [ProductFlavourController::class, 'destroy'])->name('products-flavours-delete');



    /**  admin side products assiging to store manager & store */
    Route::get('products-assign/{id}', [AssignProductController::class, 'index'])->name('assignProducts');
    Route::get('products-assign-create/{id}', [AssignProductController::class, 'create'])->name('assignProducts-create');
    Route::post('product-assign-save/{id}', [AssignProductController::class, 'store'])->name('assignProducts-store');
    Route::get('products-assign-edit/{storeManagerId}/{id}', [AssignProductController::class, 'edit'])->name('assignProducts-edit');
    Route::put('products-assign-edit/{storeManagerId}', [AssignProductController::class, 'update'])->name('assignProducts-update');
    Route::delete('products-assign-delete', [AssignProductController::class, 'destroy'])->name('assignProducts-delete');

    /**  admin side products assiging to store & department */
    Route::get('products-departments/{storeManagerId}/{storeId}/{productId}', [ProductAssignToDepartmentController::class, 'index'])->name('products-departments');
    Route::get('products-departments-create/{storeManagerId}/{storeId}/{productId}', [ProductAssignToDepartmentController::class, 'create'])->name('products-departments-create');
    Route::post('products-departments-store/{storeManagerId}/{storeId}/{productId}', [ProductAssignToDepartmentController::class, 'store'])->name('products-departments-store');
    Route::get('/products-departments-edit/{storeManagerId}/{storeId}/{productId}/{id}', [ProductAssignToDepartmentController::class, 'edit'])->name('products-departments-edit');
    Route::put('products-assign-update/{storeManagerId}', [ProductAssignToDepartmentController::class, 'update'])->name('products-departments-update');
    Route::delete('products-assignDepartments-delete', [ProductAssignToDepartmentController::class, 'destroy'])->name('products-departments-destroy');
    // Route::delete('products-assignDepartments-delete', [ProductAssignToDepartmentController::class, 'destroy'])->name('products-departments-destroy');


    //  /**  admin side products assiging to department & Vendor */
    Route::get('products-assignVendor/{productId}/{storeManagerId}/{storeId}', [ProductAssignToVendorController::class, 'index'])->name('products-assignVendor');
    Route::get('products-assignVendor-create/{productId}/{storeManagerId}/{storeId}', [ProductAssignToVendorController::class, 'create'])->name('products-assignVendor-create');
    Route::post('products-assignVendor-store/{productId}', [ProductAssignToVendorController::class, 'store'])->name('products-assignVendor-store');
    Route::get('products-assignVendor-edit/{id}/{vendorId}/{productId}', [ProductAssignToVendorController::class, 'edit'])->name('products-assignVendor-edit');
    Route::put('products-assignVendor-update/{id}/{vendorId}/{productId}', [ProductAssignToVendorController::class, 'update'])->name('products-assignVendor-update');
    Route::delete('products-assignVendor-delete/{id}/{productId}/{storeManagerId}/{storeId}', [ProductAssignToVendorController::class, 'destroy'])->name('products-assignVendor-destroy');

    /**  admin side subadmins */
    Route::get('subadmin', [SubAdminController::class, 'getSubadmin'])->name('subadmin');
    Route::get('subadmin-add', [SubAdminController::class, 'getAddSubadmin']);
    Route::post('add-subadmin', [SubAdminController::class, 'addSubadmin']);
    Route::get('subadmin-edit/{id}', [SubAdminController::class, 'getEditSubadmin']);
    Route::post('subadmin-update', [SubAdminController::class, 'updateSubadmin']);
    Route::delete('subadmin-delete/{id}', [SubAdminController::class, 'subadminDelete'])->name('subadmin-delete');
    Route::get('/get-permissions/{user}',  [SubAdminController::class, 'fetchUserPermissions'])->name('get.permissions');
    Route::post('/update-permissions/{user}', [SubAdminController::class, 'updatePermissions'])->name('update.user.permissions');



    /**  admin side dependent ajix routes for product */
    Route::get('/get-store-product/{id}', [AddProductController::class, 'getProductStores']);
    Route::get('/get-departments-product/{id}', [ProductAssignToDepartmentController::class, 'getProductDepartments']);
    Route::get('/get-vendors/{departmentId}', [ProductAssignToVendorController::class, 'getVendors']);

    /**  admin side dependent ajix routes for product assign to vendor */
    Route::get('/get-vendorDepartments/{vendorId}/{productId}', [ProductAssignToVendorController::class, 'getVendorDepartments']);

    /**  admin side route for Store manager active and deactive */
    // Route::get('/changeStatus}/{id}', [StoreManagersController::class, 'status'])->name('storeManager.status');
    Route::post('/activate/{id}', [StoreManagersController::class, 'active'])->name('storeManager.activate');
    Route::post('/deactivate/{id}', [StoreManagersController::class, 'deactive'])->name('storeManager.deactivate');

    Route::get('admin/check-stores/{id}',   [StoreManagersController::class, 'checkStores'])->name('store-manager.checkStores');
    Route::get('admin/check-departments/{id}', [StoreController::class, 'checkDepartments'])->name('store-manager.checkDepartments');

    /**  admin banners */
    Route::get('banner', [BannerController::class, 'index'])->name('banner')->middleware('permission:banner');
    Route::get('banner-create', [BannerController::class, 'create'])->name('banner.create');
    Route::post('banner-store', [BannerController::class, 'store'])->name('banner.store');
    Route::get('banner-edit/{id}', [BannerController::class, 'edit'])->name('banner.edit');
    Route::post('banner-update{id}', [BannerController::class, 'update'])->name('banner.update');
    Route::delete('banner-delete/{id}', [BannerController::class, 'destroy'])->name('banner-delete');


    Route::get('order/', [AdminOrderController::class, 'index'])->name('Order.index')->middleware('permission:Orders');
    Route::get('/order/count', [AdminOrderController::class, 'getOrderCount'])->name('Order.count');
    Route::get('/order/detail/{id}', [AdminOrderController::class, 'orderDetail'])->name('Order.detail');
    Route::put('order/update/status', [AdminOrderController::class, 'updateStatus'])->name('adminUpdateOrderStatus');
    // Route::put('order/check', [AdminOrderController::class, 'orderCheck'])->name('adminUpdateOrderStatus');


    Route::get('hotSalingProduct', [hotSaleProductController::class, 'index'])->name('hotSalingProduct.index')->middleware('permission:hotSalingProduct');
    Route::get('hotSalingProduct-create', [hotSaleProductController::class, 'create'])->name('hotSalingProduct.create');
    Route::post('hotSalingProduct-save', [hotSaleProductController::class, 'save'])->name('hotSalingProduct.save');
    Route::get('hotSalingProduct-edit/{id}', [hotSaleProductController::class, 'edit'])->name('hotSalingProduct.edit');
    Route::post('hotSalingProduct-update/{id}', [hotSaleProductController::class, 'update'])->name('hotSalingProduct.update');
    Route::delete('hotSalingProduct-delete/{id}', [hotSaleProductController::class, 'delete'])->name('hotSalingProduct.delete');

    Route::resource('social-link', SocialController::class)->middleware('permission:social-link');

    Route::get('recommendedBy', [RecommendedByController::class, 'index'])->name('recommendedBy');
    Route::get('/recommendedCount', [RecommendedByController::class, 'getRecommendedCount'])->name('recommended.count');
    Route::post('/recommand/deactivate', [RecommendedByController::class, 'deactivateStatus'])->name('recommand.deactivate');


});

/**  store manager side auth routes */
Route::group(['namespace' => 'App\Http\Controllers\Managers'], function () {
    Route::get('/login', 'ManagerAuthController@getLoginPage')->name('login');
    Route::post('/login', 'ManagerAuthController@Login')->name('user-login');
    Route::get('/forgot-password', 'ManagerController@forgetPassword');
    Route::post('/reset-password-link', 'ManagerController@managerResetPasswordLink');
    Route::get('/manager_change_password/{id}', 'ManagerController@change_password');
    Route::post('/reset-password', 'ManagerController@resetPassword');
    Route::post('/reset-password', 'ManagerController@resetPassword');
});

/**  store manager side desboard routes */
Route::group(['prefix' => 'manager', 'namespace' => 'App\Http\Controllers\Managers', 'middleware' => 'manager', 'as' => 'manager.'], function () {
    Route::get('/dashboard',  'ManagerController@getManagerPage')->name('main');
    Route::get('manager-dashboard', [ManagerStoresController::class, 'dashboard'])->name('manager-dashboard');
    Route::match(['get', 'post'], '/dashboard/store', [ManagerStoresController::class, 'stores'])->name('manager-stores');
    Route::get('/manager-store-department', [ManagerStoresController::class, 'index'])->name('manager-store-department');

    /**  store manager side sales manager routes */
    Route::get('/storeSaleManager', [StoreSaleManagerController::class, 'index'])->name('storeSaleManager.index'); 
    Route::get('/storeSaleManager-create', [StoreSaleManagerController::class, 'create'])->name('storeSaleManager.create');
    Route::post('/storeSaleManager-store', [StoreSaleManagerController::class, 'store'])->name('storeSaleManager.store');
    Route::get('/storeSaleManager-edit/{id}', [StoreSaleManagerController::class, 'edit'])->name('storeSaleManager.edit');
    Route::put('/storeSaleManager-update/{id}', [StoreSaleManagerController::class, 'update'])->name('storeSaleManager.update');
    Route::delete('/storeSaleManager-destroy/{id}', [StoreSaleManagerController::class, 'destroy'])->name('storeSaleManager.destroy');

    /**  store manager side department routes */
    Route::get('/departments-name', [ManagerStoresController::class, 'index'])->name('department-name');
    Route::get('/departments-add', [ManagerDepartmentController::class, 'create'])->name('add-department');
    Route::post('/departments-store', [ManagerDepartmentController::class, 'store'])->name('store-department');
    Route::get('/departments-edit/{id}', [ManagerDepartmentController::class, 'edit'])->name('departments-edit');
    Route::put('newDepartments/{id}', [ManagerDepartmentController::class, 'update'])->name('newDepartment');
    Route::delete('departments-destroy/{id}', [ManagerDepartmentController::class, 'destroy'])->name('departments-destroy');
    Route::get('/check-products/{id}', [ManagerDepartmentController::class, 'checkProducts'])->name('checkProducts');

    /**  store manager side vendor routes */
    Route::get('/storeManagerVendor-name', [AddVendorToStoreManager::class, 'index'])->name('storeManagerVendor');
    Route::get('/storeManagerVendor-add', [AddVendorToStoreManager::class, 'createVondor'])->name('storeManagerVendor-add');
    Route::post('/storeManagerVendor-store', [AddVendorToStoreManager::class, 'store'])->name('storeManagerVendor-store');
    Route::get('/storeManagerVendor-edit/{id}', [AddVendorToStoreManager::class, 'editVendor'])->name('storeManagerVendor-edit');
    Route::put('/storeManagerVendor-update/{id}', [AddVendorToStoreManager::class, 'updateVendor'])->name('storeManagerVendor-update');
    Route::delete('/storeManagerVendor-delete/{id}', [AddVendorToStoreManager::class, 'deleteVendor'])->name('storeManagerVendor-delete');
    // Route::post('/storeManagerVendor-toggle/{id}', [AddVendorToStoreManager::class, 'toggleOverchargedPrices'])->name('toggleOverchargedPrices');


    // Route::post('/storeManagerVendors/bulk-upload', [AddVendorToStoreManager::class, 'bulkUpload'])->name('vendors-bulkUpload');
    // Route::get('/storeManagerVendors-upload', [AddVendorToStoreManager::class, 'uploadForm'])->name('vendors-uploadForm');

    /**  store manager side assigning departments routes */
    Route::get('/storeManagerVendor-assign/{id}', [AssignVendorToStoreManager::class, 'index'])->name('assignStoreManagerVendor');
    Route::get('storeManagerVendorAssign-add/{id}', [AssignVendorToStoreManager::class, 'create'])->name('assignStoreManagerVendor-add');
    Route::post('/storeManagerVendorAssign-store/{id}', [AssignVendorToStoreManager::class, 'store'])->name('assignStoreManagerVendor-store');
    Route::get('/storeManagerVendorAssign-edit/{id}/{departmentId}', [AssignVendorToStoreManager::class, 'edit'])->name('assignStoreManagerVendor-edit');
    Route::put('/storeManagerVendorAssign-update/{id}/{departmentId}', [AssignVendorToStoreManager::class, 'update'])->name('assignStoreManagerVendor-update');
    Route::delete('/storeManagerVendorAssign-delete/{id}/{departmentId}', [AssignVendorToStoreManager::class, 'destroy'])->name('assignStoreManagerVendor-destory');

    /**  store manager side product routes */
    Route::get('/storeManagerProducts', [ManagerProduct::class, 'index'])->name('storeManagerProducts');

    Route::get('/storeManagerProducts-ajax', [ManagerProduct::class, 'getProducts'])->name('products.ajax');

    Route::get('/storeManagerProducts-create', [ManagerProduct::class, 'create'])->name('storeManagerProducts-create');
    Route::post('/storeManagerProducts-store', [ManagerProduct::class, 'store'])->name('storeManagerProducts-store');
    Route::get('/storeManagerProducts-edit/{id}', [ManagerProduct::class, 'edit'])->name('storeManagerProducts-edit');
    Route::put('/storeManagerProducts-update/{id}', [ManagerProduct::class, 'update'])->name('storeManagerProducts-update');
    Route::delete('/storeManagerProducts-delete/{id}', [ManagerProduct::class, 'delete'])->name('storeManagerProducts-delete');

    Route::get('/storeManagerProductsImages/{id}', [ProductImageController::class, 'index'])->name('storeManagerProductsImages');
    Route::get('/storeManagerProductsImages-create/{id}', [ProductImageController::class, 'create'])->name('storeManagerProductsImages-create');
    Route::post('/storeManagerProductsImages-store', [ProductImageController::class, 'store'])->name('storeManagerProductsImages-save');
    Route::get('/storeManagerProductsImages-edit/{id}', [ProductImageController::class, 'edit'])->name('storeManagerProductsImages-edit');
    Route::put('/storeManagerProductsImages-update/{id}', [ProductImageController::class, 'update'])->name('storeManagerProductsImages-update');
    Route::delete('/storeManagerProductsImages-delete/{productId}/{imageId}', [ProductImageController::class, 'destory'])->name('storeManagerProductsImages-delete');

    // routes/web.php
    Route::post('/storeManagerProducts/bulk-upload', [ManagerProduct::class, 'bulkUpload'])->name('products-bulkUpload');
    Route::get('/storeManagerProducts-upload', [ManagerProduct::class, 'uploadForm'])->name('products-uploadForm');
    Route::get('/bulk-upload-progress/{id}', [ManagerProduct::class, 'bulkUploadProgress'])->name('bulk-upload-progress');

    /**  store manager side product assiging to departments routes */
    Route::get('/storeManagerProductsDepartments/{id}', [ProductDepartmentController::class, 'index'])->name('ProductsDepartments');
    Route::get('/storeManagerProductsDepartments-create/{id}', [ProductDepartmentController::class, 'create'])->name('ProductsDepartments-create');
    Route::post('/storeManagerProductsDepartments-store/{id}', [ProductDepartmentController::class, 'store'])->name('ProductsDepartments-store');
    Route::get('/storeManagerProductsDepartments-edit/{id}/{departmentId}', [ProductDepartmentController::class, 'edit'])->name('ProductsDepartments-edit');
    Route::put('/storeManagerProductsDepartments-update/{id}/{departmentId}', [ProductDepartmentController::class, 'update'])->name('ProductsDepartments-update');
    Route::delete('/storeManagerProductsDepartments-delete/{id}/{departmentId}', [ProductDepartmentController::class, 'destory'])->name('ProductsDepartments-delete');

    /**  store manager side product assiging to vendor routes */
    Route::get('/storeManagerProductsVendor/{storeManagerId}/{storeId}/{id}', [ProductVendorController::class, 'index'])->name('productVendors');
    Route::get('/storeManagerProductsVendorCreate/{id}', [ProductVendorController::class, 'create'])->name('productVendor-create');
    Route::post('/storeManagerProductsVendorStore/{id}', [ProductVendorController::class, 'store'])->name('productVendor-store');
    Route::get('/storeManagerProductsVendorEdit/{id}/{vendorId}/{productId}', [ProductVendorController::class, 'edit'])->name('productVendor-edit');
    Route::put('/storeManagerProductsVendorUpdate/{id}/{vendorId}/{productId}', [ProductVendorController::class, 'update'])->name('productVendor-update');
    Route::delete('/storeManagerProductsVendorDelete/{id}/{productId}', [ProductVendorController::class, 'destory'])->name('productVendor-delete');


    /**  store manager side product ajix call to get departments routes */
    Route::get('/storeManagerGetDepartments/{vendorId}/{productId}', [ProductVendorController::class, 'getStoreManagerDepartments'])->name('storeManagerGetDepartments');
    Route::get('/storeManagerGetDepartmentsEdit/{vendorId}/{productId}', [ProductVendorController::class, 'getStoreManagerDepartmentsEdit'])->name('storeManagerGetDepartmentsEdit');



    /**  store manager side product flavour routes */
    Route::get('storeManagerProducts-flavor/{id}', [ProductFlavorController::class, 'index'])->name('productFlavor');
    Route::get('storeManagerProducts-flavorCreate/{id}', [ProductFlavorController::class, 'create'])->name('productFlavor-create');
    Route::post('storeManagerProducts-flavorSave', [ProductFlavorController::class, 'store'])->name('productFlavor-save');
    Route::get('storeManagerProducts-flavorEdit/{productId}/{flavourId}', [ProductFlavorController::class, 'edit'])->name('productFlavor-edit');
    Route::put('storeManagerProducts-flavorUpdate/{flavourId}', [ProductFlavorController::class, 'update'])->name('productFlavor-update');
    Route::delete('storeManagerProducts-flavorDelete/{productId}/{flavourId}', [ProductFlavorController::class, 'destroy'])->name('productFlavor-delete');

    Route::get('storeManagerOrder', [OrderController::class, 'index'])->name('storeManagerOrder.index');
    Route::get('/storeManagerOrdercount', [OrderController::class, 'getOrderCount'])->name('storeManagerOrder.count');
    Route::get('/storeManagerOrderdetail/{id}', [OrderController::class, 'orderDetail'])->name('storeManagerOrder.detail');
    Route::put('updateOrderStatus', [OrderController::class, 'updateStatus'])->name('updateOrderStatus');
    Route::get('/storeManagerOrder/signAllChecked/{orderId}', [OrderController::class, 'showSignAllChecked'])->name('signAllChecked');
    Route::post('/storeManagerOrder/completeSignAllChecked/{orderId}', [OrderController::class, 'completeSignAllChecked'])->name('completeSignAllChecked');
    Route::post('/storeManagerOrder/initiate-audit', [AuditController::class, 'initiateAudit'])->name('store.initiateAudit');
    Route::get('/storeManagerOrder/audit/{id}', [OrderController::class, 'getOrderData'])->name('order.audit');

    Route::get('immediateOrder', [ImmediateOrderController::class, 'index'])->name('immediateOrder.index');
    Route::get('/immediateOrderCreate', [ImmediateOrderController::class, 'create'])->name('immediateOrder.create');
    Route::post('/immediateOrder/completeSignAllChecked', [ImmediateOrderController::class, 'imediateSignAllChecked'])->name('immediateAllChecked');
    Route::get('/immediateOrder/signAllChecked/{orderId}', [ImmediateOrderController::class, 'showSignAllChecked'])->name('immediateSignAllChecked');


    #shortcase
    Route::get('shortCase', [ShortCaseReasonController::class, 'index'])->name('shortCase');
    Route::get('shortCase-create', [ShortCaseReasonController::class, 'create'])->name('shortCase.create');
    Route::post('shortCase-store', [ShortCaseReasonController::class, 'store'])->name('shortCase.store');
    Route::get('shortCase-edit/{id}', [ShortCaseReasonController::class, 'edit'])->name('shortCase.edit');
    Route::post('shortCase-update{id}', [ShortCaseReasonController::class, 'update'])->name('shortCase.update');
    Route::delete('shortCase-delete/{id}', [ShortCaseReasonController::class, 'destroy'])->name('shortCase.delete');

    #report
    Route::get('/sales', [ReportController::class, 'salesIndex'])->name('sales');
    Route::post('/sales/data', [ReportController::class, 'getSalesData'])->name('sales.data');
    Route::get('departmemtVendor/{department_id}', [ReportController::class, 'getVendorsByDepartment'])->name('getVendorsByDepartment');

    #Notification
    Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications.index');
    Route::post('/notifications/mark-as-read/{notificationId}', [NotificationController::class, 'markAsRead'])->name('notification.marked');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notification.read');


    Route::get('profile', 'ManagerController@getProfilePage');
    Route::post('update-profile', 'ManagerController@updateProfile');
    Route::get('logout', 'ManagerController@logout');
});

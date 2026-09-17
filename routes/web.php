<?php

use App\Http\Controllers\Admin\AdminAttributeController;
use App\Http\Controllers\Admin\AdminAttributeValueController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CompanySettingController as AdminCompanySettingController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\HomepageSettingController as AdminHomepageSettingController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductLandingPageController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SubCategoryController as AdminSubCategoryController;
use App\Http\Controllers\Backend\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Backend\DashboardController as AdminDashboardController;
use App\Http\Controllers\Frontend\Auth\LoginController as UserLoginController;
use App\Http\Controllers\Frontend\Auth\RegisterController as UserRegisterController;
use App\Http\Controllers\Frontend\CouponController;
use App\Http\Controllers\Frontend\CustomerOrderController;
use App\Http\Controllers\Frontend\DashboardController as UserDashboardController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\LandingPageController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\PageController as FrontendPageController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\SslcommerzController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{slug}', [HomeController::class, 'productDetails'])->name('product.details');
Route::get('/landing/{slug}', [LandingPageController::class, 'show'])->name('landing.show');
Route::get('/category/{id}', [HomeController::class, 'categoryProducts'])->name('category.products');
Route::get('/products/search-api', [HomeController::class, 'searchApi'])->name('products.search-api');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/flash-sale', [HomeController::class, 'flashSale'])->name('flash-sale');
Route::get('/page/{slug}', [FrontendPageController::class, 'show'])->name('page.show');
Route::get('/checkout', [HomeController::class, 'checkout'])->name('checkout');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/order/place', [OrderController::class, 'store'])->name('order.store');
Route::post('/coupon/apply', [CouponController::class, 'apply'])->name('coupon.apply');
Route::get('/order/invoice/{invoiceNo}', [OrderController::class, 'invoice'])->name('order.invoice');

/* ========== SSLCommerz Callbacks ========== */
Route::controller(SslcommerzController::class)
    ->prefix('sslcommerz')
    ->name('sslc.')
    ->group(function () {
        Route::post('success', 'success')->name('success');
        Route::post('failure', 'failure')->name('failure');
        Route::post('cancel', 'cancel')->name('cancel');
        Route::post('ipn', 'ipn')->name('ipn');
    });
Route::post('/product/{product}/review', [ReviewController::class, 'store'])->name('product.review.store');

/* ========== Frontend (User) ========== */
Route::prefix('account')->name('user.')->group(function () {
    Route::get('login', [UserLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [UserLoginController::class, 'login'])->name('login.submit');
    Route::get('register', [UserRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [UserRegisterController::class, 'register'])->name('register.submit');
    Route::post('logout', [UserLoginController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::get('orders', [CustomerOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    });
});

/* ========== Backend (Admin) ========== */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminLoginController::class, 'login'])->name('login.submit');
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('categories', AdminCategoryController::class);
        Route::patch('categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
        Route::patch('categories/{category}/toggle-trending', [AdminCategoryController::class, 'toggleTrending'])->name('categories.toggle-trending');
        Route::resource('sub-categories', AdminSubCategoryController::class);

        Route::get('settings/homepage', [AdminHomepageSettingController::class, 'index'])->name('settings.homepage');
        Route::post('settings/homepage/{section}', [AdminHomepageSettingController::class, 'update'])->name('settings.homepage.update');

        Route::get('settings/company', [AdminCompanySettingController::class, 'index'])->name('settings.company');
        Route::post('settings/company', [AdminCompanySettingController::class, 'update'])->name('settings.company.update');

        Route::resource('brands', AdminBrandController::class);

        Route::post('products/bulk-delete', [AdminProductController::class, 'bulkDestroy'])->name('products.bulk-destroy');
        Route::resource('products', AdminProductController::class);
        Route::patch('products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
        Route::patch('products/{product}/toggle-active', [AdminProductController::class, 'toggleActive'])->name('products.toggle-active');
        Route::patch('products/{product}/toggle-new-arrival', [AdminProductController::class, 'toggleNewArrival'])->name('products.toggle-new-arrival');

        // Landing Page routes
        Route::get('products/{product}/landing-page/create', [ProductLandingPageController::class, 'create'])->name('products.landing-page.create');
        Route::post('products/{product}/landing-page', [ProductLandingPageController::class, 'store'])->name('products.landing-page.store');
        Route::get('products/{product}/landing-page/edit', [ProductLandingPageController::class, 'edit'])->name('products.landing-page.edit');
        Route::put('products/{product}/landing-page', [ProductLandingPageController::class, 'update'])->name('products.landing-page.update');

        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/bulk-print', [AdminOrderController::class, 'bulkPrint'])->name('orders.bulk-print');
        Route::post('orders/send-steadfast', [AdminOrderController::class, 'sendToSteadfast'])->name('orders.send-steadfast');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

        Route::resource('coupons', AdminCouponController::class);

        Route::resource('reviews', App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'destroy']);

        Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('reports/stock', [ReportController::class, 'stock'])->name('reports.stock');

        Route::get('attribute-values', [AdminAttributeValueController::class, 'globalIndex'])->name('attribute-values.index');
        Route::resource('attributes', AdminAttributeController::class);
        Route::resource('attributes.values', AdminAttributeValueController::class);

        Route::resource('pages', AdminPageController::class)->only(['index', 'edit', 'update']);
    });
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');

    return 'All cache cleared successfully!';
});

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage linked successfully!';
});

<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public referral redirect route
Route::get('/r/{category}/{referrer}', [\App\Http\Controllers\PublicReferralController::class, 'handle'])->name('referrals.public');

// Simple landing route placeholder - businesses can customize this
Route::get('/referrals/landing/{category}', function ($category) {
    // Show public-facing referral landing page for business category
    return view('public.referrals.landing', compact('category'));
})->name('referrals.landing');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('landing.admin');
        })->name('dashboard');

        // List All Users
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');

        // Manage Business Users
        Route::resource('businesses', \App\Http\Controllers\Admin\BusinessController::class);

        // Add Points to Business
        Route::post('/users/{user}/points', [\App\Http\Controllers\Admin\PointController::class, 'store'])->name('users.points.store');
    });

    // Business Routes
    Route::middleware('role:business')->prefix('business')->name('business.')->group(function () {
        Route::get('/dashboard', function () {
            return redirect('/dashboard');
        })->name('dashboard');

        // Employees Management
        Route::resource('employees', \App\Http\Controllers\Business\EmployeeController::class)->parameters([
            'employees' => 'user' // Binding 'employees' param to 'user' model instance
        ]);

        // Customers Management
        Route::resource('customers', \App\Http\Controllers\Business\CustomerController::class)->parameters([
            'customers' => 'user' // Binding 'customers' param to 'user' model instance
        ]);

        // Product Management
        Route::resource('products', \App\Http\Controllers\Business\ProductController::class);

        // Allocate Points to Employee/Customer
        Route::post('/users/{user}/points', [\App\Http\Controllers\Business\PointController::class, 'store'])->name('users.points.store');

        // Payment Settings
        Route::get('/settings/payment', [\App\Http\Controllers\Business\PaymentSettingsController::class, 'edit'])->name('settings.payment.edit');
        Route::patch('/settings/payment', [\App\Http\Controllers\Business\PaymentSettingsController::class, 'update'])->name('settings.payment.update');

        // Order Management
        Route::get('/orders', [\App\Http\Controllers\Business\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [\App\Http\Controllers\Business\OrderController::class, 'show'])->name('orders.show');

        // Claim Management
        Route::prefix('claims')->name('claims.')->group(function () {
            Route::resource('categories', \App\Http\Controllers\Business\ClaimCategoryController::class);
            Route::get('/', [\App\Http\Controllers\Business\ClaimController::class, 'index'])->name('index');
            Route::get('/category/{category}', [\App\Http\Controllers\Business\ClaimController::class, 'categoryClaims'])->name('category');
            Route::get('/{claim}', [\App\Http\Controllers\Business\ClaimController::class, 'show'])->name('show');
            Route::patch('/{claim}', [\App\Http\Controllers\Business\ClaimController::class, 'update'])->name('update');
        });

        Route::prefix('nominations')->name('nominations.')->group(function () {
            Route::get('categories/{category}/results', [\App\Http\Controllers\Business\NominationCategoryController::class, 'results'])->name('categories.results');
            Route::post('categories/{category}/award', [\App\Http\Controllers\Business\NominationCategoryController::class, 'award'])->name('categories.award');
            Route::resource('categories', \App\Http\Controllers\Business\NominationCategoryController::class);
        });

        // Events Management
        Route::get('/events/{event}/participants', [\App\Http\Controllers\Business\EventController::class, 'participants'])->name('events.participants');
        Route::post('/events/{event}/attendance', [\App\Http\Controllers\Business\EventController::class, 'recordAttendance'])->name('events.attendance.record');
        Route::resource('events', \App\Http\Controllers\Business\EventController::class);

        // Referral Management
        Route::prefix('referrals')->name('referrals.')->group(function () {
             Route::resource('categories', \App\Http\Controllers\Business\ReferralCategoryController::class);
             Route::get('/', [\App\Http\Controllers\Business\ReferralController::class, 'index'])->name('index');
             Route::post('/{referral}/approve', [\App\Http\Controllers\Business\ReferralController::class, 'approve'])->name('approve');
             Route::post('/{referral}/reject', [\App\Http\Controllers\Business\ReferralController::class, 'reject'])->name('reject');
        });

        // KPI Management
        Route::prefix('kpis')->name('kpis.')->group(function () {
            Route::resource('categories', \App\Http\Controllers\Business\KpiCategoryController::class);
            Route::get('/', [\App\Http\Controllers\Business\KpiController::class, 'index'])->name('index');
            Route::get('/{kpi}', [\App\Http\Controllers\Business\KpiController::class, 'show'])->name('show');
            Route::post('/{kpi}/approve', [\App\Http\Controllers\Business\KpiController::class, 'approve'])->name('approve');
            Route::post('/{kpi}/reject', [\App\Http\Controllers\Business\KpiController::class, 'reject'])->name('reject');
        });

        // Gamification Campaign Management
        Route::resource('gamification', \App\Http\Controllers\Business\GamificationCampaignController::class)->parameters([
            'gamification' => 'campaign'
        ]);
    });

    // Employee Routes
    Route::middleware('role:employee')->prefix('employee')->name('employee.')->group(function () {
        Route::get('/dashboard', function () {
            return redirect('/dashboard');
        })->name('dashboard');

        // Employee Specific Routes
        Route::get('/claims', [\App\Http\Controllers\Employee\ClaimController::class, 'index'])->name('claims.index');
        Route::get('/claims/history', [\App\Http\Controllers\Employee\ClaimController::class, 'history'])->name('claims.history');
        Route::get('/claims/category/{category}', [\App\Http\Controllers\Employee\ClaimController::class, 'categoryClaims'])->name('claims.category');
        Route::get('/claims/create', [\App\Http\Controllers\Employee\ClaimController::class, 'create'])->name('claims.create');
        Route::post('/claims', [\App\Http\Controllers\Employee\ClaimController::class, 'store'])->name('claims.store');
        Route::get('/claims/{claim}', [\App\Http\Controllers\Employee\ClaimController::class, 'show'])->name('claims.show');

        Route::prefix('nominations')->name('nominations.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Employee\NominationController::class, 'index'])->name('index');
            Route::get('/{category}/create', [\App\Http\Controllers\Employee\NominationController::class, 'create'])->name('create');
            Route::post('/{category}', [\App\Http\Controllers\Employee\NominationController::class, 'store'])->name('store');
        });

        // Referral Routes
        Route::get('/referrals', [\App\Http\Controllers\Employee\ReferralController::class, 'index'])->name('referrals.index');
        Route::get('/referrals/{category}', [\App\Http\Controllers\Employee\ReferralController::class, 'show'])->name('referrals.show');
        Route::match(['get', 'post'], '/referrals/{category}/join', [\App\Http\Controllers\Employee\ReferralController::class, 'join'])->name('referrals.join');
        Route::post('/referrals', [\App\Http\Controllers\Employee\ReferralController::class, 'store'])->name('referrals.store');

        // KPI Routes
        Route::get('/kpis', [\App\Http\Controllers\Employee\KpiController::class, 'index'])->name('kpis.index');
        Route::get('/kpis/{category}', [\App\Http\Controllers\Employee\KpiController::class, 'show'])->name('kpis.show');
        Route::post('/kpis', [\App\Http\Controllers\Employee\KpiController::class, 'store'])->name('kpis.store');
    });

    // Customer Routes
    Route::middleware('role:customer')->prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', function () {
            return redirect('/dashboard');
        })->name('dashboard');
        // Customer Specific Routes
        Route::get('/claims', [\App\Http\Controllers\Customer\ClaimController::class, 'index'])->name('claims.index');
        Route::get('/claims/history', [\App\Http\Controllers\Customer\ClaimController::class, 'history'])->name('claims.history');
        Route::get('/claims/category/{category}', [\App\Http\Controllers\Customer\ClaimController::class, 'categoryClaims'])->name('claims.category');
        Route::get('/claims/create', [\App\Http\Controllers\Customer\ClaimController::class, 'create'])->name('claims.create');
        Route::post('/claims', [\App\Http\Controllers\Customer\ClaimController::class, 'store'])->name('claims.store');
        Route::get('/claims/{claim}', [\App\Http\Controllers\Customer\ClaimController::class, 'show'])->name('claims.show');

        // Referral Routes
        Route::get('/referrals', [\App\Http\Controllers\Customer\ReferralController::class, 'index'])->name('referrals.index');
        Route::get('/referrals/{category}', [\App\Http\Controllers\Customer\ReferralController::class, 'show'])->name('referrals.show');
        Route::match(['get', 'post'], '/referrals/{category}/join', [\App\Http\Controllers\Customer\ReferralController::class, 'join'])->name('referrals.join');
        Route::post('/referrals', [\App\Http\Controllers\Customer\ReferralController::class, 'store'])->name('referrals.store');

        // KPI Routes
        Route::get('/kpis', [\App\Http\Controllers\Customer\KpiController::class, 'index'])->name('kpis.index');
        Route::get('/kpis/{category}', [\App\Http\Controllers\Customer\KpiController::class, 'show'])->name('kpis.show');
        Route::post('/kpis', [\App\Http\Controllers\Customer\KpiController::class, 'store'])->name('kpis.store');
    });

    // Shop Routes (accessible by businesses, employees, and customers)
    Route::middleware('auth')->group(function () {
        Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
        Route::get('/shop/product/{product}', [ShopController::class, 'show'])->name('shop.show');
        Route::post('/shop/checkout', [ShopController::class, 'checkout'])->name('shop.checkout');
        Route::get('/shop/orders', [ShopController::class, 'orders'])->name('shop.orders');

        // Cart Routes
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::match(['get', 'post'], '/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
        Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

        // Payment Routes
        Route::get('/payment/stripe/{order}', [PaymentController::class, 'stripe'])->name('payment.stripe');
        Route::get('/payment/stripe/success/{order}', [PaymentController::class, 'stripeSuccess'])->name('payment.stripe.success');
        Route::get('/payment/stripe/cancel/{order}', [PaymentController::class, 'stripeCancel'])->name('payment.stripe.cancel');
        
        Route::get('/payment/paypal/{order}', [PaymentController::class, 'paypal'])->name('payment.paypal');
        Route::get('/payment/paypal/success/{order}', [PaymentController::class, 'paypalSuccess'])->name('payment.paypal.success');
        Route::get('/payment/paypal/cancel/{order}', [PaymentController::class, 'paypalCancel'])->name('payment.paypal.cancel');
    });

    // Referrer Routes
    Route::middleware('role:referrer')->prefix('referrer')->name('referrer.')->group(function () {
        Route::get('/dashboard', function () {
            return redirect('/dashboard');
        })->name('dashboard');
    });
    // Notification Routes
    Route::post('/notifications/{id}/mark-as-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');

    // Events Route for Employees and Customers
    Route::get('/events', [\App\Http\Controllers\EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [\App\Http\Controllers\EventController::class, 'show'])->name('events.show');
    Route::post('/events/{event}/join', [\App\Http\Controllers\EventController::class, 'join'])->name('events.join');
    Route::post('/events/{event}/comment', [\App\Http\Controllers\EventController::class, 'comment'])->name('events.comment');
    Route::post('/events/{event}/react', [\App\Http\Controllers\EventController::class, 'react'])->name('events.react');
    Route::post('/comments/{comment}/like', [\App\Http\Controllers\EventController::class, 'commentLike'])->name('comments.like');

    // Gamification Routes for all authenticated users
    Route::get('/gamification', [\App\Http\Controllers\GamificationController::class, 'index'])->name('gamification.index');
    Route::post('/gamification/{campaign}/join', [\App\Http\Controllers\GamificationController::class, 'join'])->name('gamification.join');
    Route::get('/gamification/{campaign}', [\App\Http\Controllers\GamificationController::class, 'show'])->name('gamification.show');

});

require __DIR__.'/auth.php';

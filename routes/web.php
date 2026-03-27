<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [PortalController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/dashboard/shipping/customers/search', [PortalController::class, 'customersSearch'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.customers.search');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin', fn () => 'Admin area')->middleware('role:admin')->name('admin.home');
    Route::get('/operations', fn () => 'Operations area')->middleware('role:operations')->name('operations.home');
    Route::get('/sales', fn () => 'Sales area')->middleware('role:sales')->name('sales.home');

    Route::resource('customers', CustomerController::class)
        ->except('show')
        ->middleware('role_or_permission:admin|operations|sales|manage sales|manage operations');
});

require __DIR__.'/auth.php';

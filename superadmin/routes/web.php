<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;

Route::get('/', [SuperAdminController::class, 'index'])->name('superadmin.index');
Route::get('/{id}/edit', [SuperAdminController::class, 'edit'])->name('superadmin.edit');
Route::put('/{id}/update', [SuperAdminController::class, 'update'])->name('superadmin.update');
Route::delete('/{id}/destroy', [SuperAdminController::class, 'destroy'])->name('superadmin.destroy');
Route::get('/create', [SuperAdminController::class, 'create'])->name('superadmin.create');
Route::post('/store', [SuperAdminController::class, 'store'])->name('superadmin.store');
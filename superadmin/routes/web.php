<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Hash;

Route::get('/', [SuperAdminController::class, 'index'])->name('superadmin.index');
Route::get('/{id}/edit', [SuperAdminController::class, 'edit'])->name('superadmin.edit');
Route::put('/{id}/update', [SuperAdminController::class, 'update'])->name('superadmin.update');
Route::delete('/{id}/destroy', [SuperAdminController::class, 'destroy'])->name('superadmin.destroy');
Route::get('/create', [SuperAdminController::class, 'create'])->name('superadmin.create');
Route::post('/store', [SuperAdminController::class, 'store'])->name('superadmin.store');
Route::get('/login', [SuperAdminController::class, 'login'])->name('superadmin.login');
Route::post('/login', [SuperAdminController::class, 'authenticate'])->name('superadmin.authenticate');
Route::get('/logout', [SuperAdminController::class, 'logout'])->name('superadmin.logout');

// Crear un usuario con una ruta directamente aqui
// Route::get('/create-user', function () {
//     // Crear un usuario con datos predeterminados
//     \App\Models\User::create([
//         'name' => 'desasarrollo web',
//         'email' => 'desarrolloweb@tallerempresarial.es',
//         'password' => Hash::make('control120'), // Asegúrate de usar Hash::make para encriptar la contraseña
//     ]);

//     return 'Usuario creado con éxito';
// })->name('superadmin.create-user');

Route::middleware('auth')->group(function () {
    Route::get('/create-user', [SuperAdminController::class, 'showCreateUserForm'])->name('superadmin.showCreateUserForm');
    Route::post('/create-user', [SuperAdminController::class, 'createUser'])->name('superadmin.createUser');
});
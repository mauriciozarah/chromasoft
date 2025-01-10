<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\UsuarioAutenticadoMiddleware;

Route::get('/', [App\Http\Controllers\UsuarioController::class, 'login'])->name('usuario.login');
Route::post('/do_login', [App\Http\Controllers\UsuarioController::class, 'do_login'])->name('usuario.do_login');

Route::group(['prefix' => 'admin'], function () {
    Route::get('/usuario', [App\Http\Controllers\UsuarioController::class, 'dashboard'])->name('usuario.dashboard')->middleware(UsuarioAutenticadoMiddleware::class);
    Route::post('/usuario/store', [App\Http\Controllers\UsuarioController::class, 'createUsuario'])->name('usuario.store')->middleware(UsuarioAutenticadoMiddleware::class);
    Route::get('/usuario/show/{id?}', [App\Http\Controllers\UsuarioController::class, 'showUsuario'])->name('usuario.show')->middleware(UsuarioAutenticadoMiddleware::class);
    Route::put('/usuario/update', [App\Http\Controllers\UsuarioController::class, 'updateUsuario'])->name('usuario.update')->middleware(UsuarioAutenticadoMiddleware::class);
    Route::delete('/usuario/delete/{id?}', [App\Http\Controllers\UsuarioController::class, 'deleteUsuario'])->name('usuario.delete')->middleware(UsuarioAutenticadoMiddleware::class);
});

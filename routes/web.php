<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/', [Controller::class, 'cekLulus'])->name('lulus.cek');
Route::post('/download', [Controller::class, 'downloadCert'])->name('lulus.download');


Route::middleware('auth')->group(function () {
    Route::get('/beranda', [Controller::class, 'index'])->name('dashboard');
    Route::post('/beranda', [Controller::class, 'settingStore'])->name('setting.store');
    Route::post('/user', [Controller::class, 'userUpdate'])->name('user.update');

    Route::prefix('/peserta')->group(function () {
        Route::get('/', [Controller::class, 'peserta'])->name('peserta.index');
        Route::post('/template', [Controller::class, 'downloadTemplate'])->name('template.download');
        Route::post('/import', [Controller::class, 'import'])->name('peserta.import');
        Route::get('/{peserta}/delete', [Controller::class, 'delete'])->name('peserta.delete');
        Route::get('/{peserta}/download', [Controller::class, 'download'])->name('peserta.download');
        Route::delete('/delete', [Controller::class, 'deleteAll'])->name('peserta.delete.all');
    });
});

require __DIR__ . '/auth.php';

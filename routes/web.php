<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user/{id}/name/{name}', function ($id, $name) {
    return view('user.profile', ['id' => $id, 'name' => $name]);
})->name('user.profile');

Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');

Route::prefix('category')->group(function () {
    Route::get('food-beverage', [ProductCategoryController::class, 'foodBeverage'])->name('category.food-beverage');
    Route::get('beauty-health', [ProductCategoryController::class, 'beautyHealth'])->name('category.beauty-health');
    Route::get('home-care', [ProductCategoryController::class, 'homeCare'])->name('category.home-care');
    Route::get('baby-kid', [ProductCategoryController::class, 'babyKid'])->name('category.baby-kid');
});


Route::get('/level', [LevelController::class,'index']);
Route::get('/kategori', [KategoriController::class, 'index']);
Route::get('/user', [UserController::class, 'index']);
Route::get('/user/tambah', [UserController::class, 'tambah']);
Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);
Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);
Route::put('/user/ubah-simpan/{id}', [UserController::class, 'ubah_simpan']);
Route::get('/user/hapus/{id}', [UserController::class, 'hapus']);
<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\FavouriteImageController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
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

Route::middleware('can:admin')->group(function () {

});

Route::resource('images', ImageController::class);
Route::resource('brands', BrandController::class);
Route::resource('types', TypeController::class);
Route::resource('users', UserController::class);
Route::resource('favourites', FavouriteImageController::class)->middleware('auth');

Auth::routes();


// Route::get('/posts/create', function () {
//     return view('posts.create');
// });

// Route::get('/posts/x', function () {
//     return view('posts.show');
// });

// Route::get('/posts/x/edit', function () {
//     return view('posts.edit');
// });

// -----------------------------------------

// Route::get('/categories/create', function () {
//     return view('categories.create');
// });

// Route::get('/categories/x', function () {
//     return view('categories.show');
// });

// -----------------------------------------

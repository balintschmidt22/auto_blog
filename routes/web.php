<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\FavouriteImageController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;


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

//ADMIN FUNCTIONS
Route::middleware('can:admin')->group(function () {
    Route::get('brands/create', [BrandController::class, 'create'])->name('brands.create');
});

//GALLERY
Route::resource('gallery', GalleryController::class);
Route::get('gallery/create', [GalleryController::class, 'create'])->middleware(['auth', 'verified'])->name('gallery.create');
Route::get('gallery/gettypes', [GalleryController::class, 'gettypes'])->middleware(['auth', 'verified'])->name('gallery.gettypes');

//RESOURCE CONTROLLERS
Route::resource('brands', BrandController::class);
Route::resource('types', TypeController::class);
Route::resource('users', UserController::class);

//FAVOURITES
Route::resource('favourites', FavouriteImageController::class)->middleware('auth');
Route::get('favourites/add/{id}', [FavouriteImageController::class, 'add'])->middleware(['auth', 'verified'])->name('favourites.add');


// EMAIL VERIFICATION
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    //TODO SESSION
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');


Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Auth::routes(['verify' => true]);

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

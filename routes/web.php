<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavouriteImageController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Models\User;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;


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

//MODERATOR FUNCTIONS (ADMIN CAN USE THEM)
Route::middleware('can:moderator')->group(function () {
    Route::get('comments/delete/{id}', [CommentController::class, 'delete'])->name('comments.delete');

    Route::get('types/create', [TypeController::class, 'create'])->name('types.create');

    Route::get('brands/create', [BrandController::class, 'create'])->name('brands.create');
});

//ADMIN FUNCTIONS
Route::middleware('can:admin')->group(function () {
    Route::get('types/delete/{id}', [TypeController::class, 'delete'])->name('types.delete');
    Route::get('brands/delete/{id}', [BrandController::class, 'delete'])->name('brands.delete');
    Route::get('gallery/delete/{id}', [GalleryController::class, 'delete'])->name('gallery.delete');
    Route::get('users/delete/{id}', [UserController::class, 'delete'])->name('users.delete');

    Route::get('users/addModerator/{id}', [UserController::class, 'addModerator'])->name('users.addModerator');
    Route::get('users/removeModerator/{id}', [UserController::class, 'removeModerator'])->name('users.removeModerator');
});

//GALLERY
Route::get('gallery/gettypes', [GalleryController::class, 'gettypes'])->middleware(['auth', 'verified'])->name('gallery.gettypes');
Route::resource('gallery', GalleryController::class);
Route::get('gallery/create', [GalleryController::class, 'create'])->middleware(['auth', 'verified'])->name('gallery.create');

//BRANDS
Route::resource('brands', BrandController::class);
Route::post('brands/search', [BrandController::class, 'search'])->name('brands.search');

//TYPES
Route::resource('types', TypeController::class);

//USERS
//Route::get('users/show/{id}', [UserController::class, 'show'])->name('users.show2');
Route::resource('users', UserController::class);

Route::post('users/search', [UserController::class, 'search'])->name('users.search');

Route::get('users/pdf/download', [UserController::class, 'createPDF'])->name('users.pdf.download');
Route::get('users/csv/download', [UserController::class, 'exportCSV'])->name('users.csv.download');

Route::get('users/message/{id}', [UserController::class, 'message'])->middleware(['auth', 'verified'])->name('users.message');
Route::post('users/addMessage/{id}', [UserController::class, 'addMessage'])->middleware(['auth', 'verified'])->name('users.addMessage');

Route::get('users/{user}/useredit', [UserController::class, 'useredit'])->middleware(['auth', 'verified'])->name('users.useredit');
Route::patch('users/userUpdate/{id}', [UserController::class, 'userUpdate'])->middleware(['auth', 'verified'])->name('users.userUpdate');

Route::get('users/changePassword/{id}', [UserController::class, 'changePassword'])->middleware(['auth', 'verified'])->name('users.changePassword');
Route::patch('users/updatePassword/{id}', [UserController::class, 'updatePassword'])->middleware(['auth', 'verified'])->name('users.updatePassword');

//COMMENTS
Route::resource('comments', CommentController::class);
Route::post('comments/add/{id}', [CommentController::class, 'addComment'])->middleware(['auth', 'verified'])->name('comments.add');

//FAVOURITES
Route::resource('favourites', FavouriteImageController::class)->middleware('auth');
Route::post('favourites/add', [FavouriteImageController::class, 'add'])->middleware(['auth', 'verified'])->name('favourites.add');

//FOLLOWS
Route::get('follows/followUser/{id}', [FollowController::class, 'followUser'])->middleware(['auth', 'verified'])->name('follows.followUser');
Route::get('follows/followBrand/{id}', [FollowController::class, 'followBrand'])->middleware(['auth', 'verified'])->name('follows.followBrand');
Route::get('follows/followedUsers', [FollowController::class, 'followedUsers'])->middleware(['auth', 'verified'])->name('follows.followedUsers');
Route::get('follows/followedBrands', [FollowController::class, 'followedBrands'])->middleware(['auth', 'verified'])->name('follows.followedBrands');

// EMAIL VERIFICATION
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    Session::flash('success', 'Your email has been verified');
})->middleware(['auth', 'signed'])->name('verification.verify');


Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

//PASSWORD RESET

Route::get('/password/reset', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/password/email', function (Request $request) {
    $request->validate(['email' => 'required|email:rfc,dns']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/password/reset/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/password/reset', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email:rfc,dns',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');


Route::fallback(function () {
    return view('errors.403');
});

Route::fallback(function () {
    return view('errors.404');
});

Auth::routes(['verify' => true]);

Route::any('{any}', function () {
    return redirect('/');
});

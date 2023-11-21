<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\FavouriteImageController;
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
Route::get('gallery/gettypes', [GalleryController::class, 'gettypes'])->middleware(['auth', 'verified'])->name('gallery.gettypes');
Route::resource('gallery', GalleryController::class);
Route::get('gallery/create', [GalleryController::class, 'create'])->middleware(['auth', 'verified'])->name('gallery.create');

//RESOURCE CONTROLLERS
Route::resource('brands', BrandController::class);
Route::resource('types', TypeController::class);

//USERS
//Route::get('users/show/{id}', [UserController::class, 'show'])->name('users.show2');
Route::resource('users', UserController::class);
Route::post('users/search', [UserController::class, 'search'])->name('users.search');
Route::get('users/pdf/download', [UserController::class, 'createPDF'])->name('users.pdf.download');

// Route::get('userspdf', function () {
//     $data = User::get();
//     $pdf = Pdf::loadView('pdf.autoblog_users', compact('data'));
//     return $pdf->download('autoblog_users.pdf');
// })->name('users.pdf');

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

//PASSWORD RESET

Route::get('/password/reset', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/password/email', function (Request $request) {
    $request->validate(['email' => 'required|email']);

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
        'email' => 'required|email',
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


Auth::routes(['verify' => true]);

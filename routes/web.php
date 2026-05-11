<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| Login & Register Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
});

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'role' => ['required', 'in:user,admin'],
    ]);

    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
        'role' => $validated['role'],
        'is_admin' => $validated['role'] === 'admin',
    ]);

    Auth::login($user);

    return redirect('/dashboard');
});

/*
|--------------------------------------------------------------------------
| Logout Route
|--------------------------------------------------------------------------
*/

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard & Profile Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/news', function (Request $request) {
        $query = $request->query('q', 'sports');
        $apiKey = config('services.newsapi.key');
        $response = \Illuminate\Support\Facades\Http::get('https://gnews.io/api/v4/search', [
            'q' => $query,
            'token' => $apiKey,
            'lang' => 'en',
            'max' => 12,
        ]);
        return response()->json($response->json(), $response->status());
    })->name('news.proxy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Statistics Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Statistics CRUD
    Route::resource('statistics', StatisticController::class);
    Route::get('statistics-summary', [StatisticController::class, 'summary'])->name('statistics.summary');
    
    // Analytics
    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    
    // Exports
    Route::get('exports', [ExportController::class, 'index'])->name('exports.index');
    Route::get('exports/csv', [ExportController::class, 'exportCSV'])->name('exports.csv');
    Route::get('exports/json', [ExportController::class, 'exportJSON'])->name('exports.json');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'is.admin'])->prefix('admin')->group(function () {
    // Admin Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('admin.users.toggle');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
});

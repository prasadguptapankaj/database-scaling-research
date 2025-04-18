<?php

use App\Http\Controllers\DashboardController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::get('/create', function() {
    User::create([
        'name' => 'Test User',
        'email' => 'test@test.com',
        'password' => bcrypt('secret'),
    ]);
    return 'user created';
    
    // $users = User::all();
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

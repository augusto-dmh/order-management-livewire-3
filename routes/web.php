<?php

use App\Livewire\ProductsList;
use App\Livewire\CategoriesList;
use Illuminate\Support\Facades\Route;

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

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('categories', CategoriesList::class)
    ->middleware(['auth'])
    ->name('categories.index');

Route::get('products', ProductsList::class)
    ->middleware(['auth'])
    ->name('products.index');

require __DIR__ . '/auth.php';

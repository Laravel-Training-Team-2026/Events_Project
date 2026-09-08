<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\InterestedController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])
    ->name('home');

// Events
Route::get('/events', [EventController::class, 'index'])
    ->name('events.index');

// Event Details
Route::get('/events/{event}', [EventController::class, 'show'])
    ->name('event.details');

// About
Route::view('/about', 'pages.about')
    ->name('about');

// Contact - View
Route::view('/contact', 'pages.contact')
    ->name('contact');

// Contact - Send Message
// Guests and authenticated users can send messages.
Route::post('/contact', [ContactController::class, 'store'])
    ->name('contact.store');


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

// Login
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.store');

// Register
Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.store');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    // Create Event
    Route::get('/create-event', [EventController::class, 'create'])
        ->name('events.create');

    // Store Event
    Route::post('/events', [EventController::class, 'store'])
        ->name('events.store');

    // Edit Event
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])
        ->name('events.edit');

    // Update Event
    Route::put('/events/{event}', [EventController::class, 'update'])
        ->name('events.update');

    // Delete Event
    Route::delete('/events/{event}', [EventController::class, 'destroy'])
        ->name('events.destroy');


    /*
    |--------------------------------------------------------------------------
    | Favorites
    |--------------------------------------------------------------------------
    */

    Route::get('/favorites', [FavoriteController::class, 'index'])
        ->name('favorites');

    Route::post('/favorites/{event}', [FavoriteController::class, 'store'])
        ->name('favorites.store');

    Route::delete('/favorites/{event}', [FavoriteController::class, 'destroy'])
        ->name('favorites.destroy');


    /*
    |--------------------------------------------------------------------------
    | Interested
    |--------------------------------------------------------------------------
    */

    Route::post('/events/{event}/interest', [InterestedController::class, 'store'])
        ->name('interests.store');

    Route::delete('/events/{event}/interest', [InterestedController::class, 'destroy'])
        ->name('interests.destroy');


    /*
    |--------------------------------------------------------------------------
    | Bookings
    |--------------------------------------------------------------------------
    */

    // My Bookings
    Route::get('/bookings', [BookingController::class, 'index'])
        ->name('bookings.index');

    // Book Event
    Route::post('/events/{event}/book', [BookingController::class, 'store'])
        ->name('bookings.store');

    // Cancel Booking
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])
        ->name('bookings.destroy');
});

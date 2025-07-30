<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TestMiddleware;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    $session = session()->all();
    return response()->json($session);
    //return 'Test route';
})->middleware(['group-name']); // Apply the middleware to this route

// Contact Routes
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
Route::post('/contacts/import', [ContactController::class, 'import'])->name('contacts.import');
Route::get('/contacts/list', [ContactController::class, 'list'])->name('contacts.list');
Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
Route::get('/contacts/{id}', [ContactController::class, 'show'])->name('contacts.show');
Route::put('/contacts/{id}', [ContactController::class, 'update'])->name('contacts.update');
Route::delete('/contacts/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');





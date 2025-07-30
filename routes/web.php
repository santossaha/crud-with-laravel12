<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TestMiddleware;
use App\Http\Controllers\UserController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    $session = session()->all();
    return response()->json($session);
    //return 'Test route';
})->middleware(['group-name']); // Apply the middleware to this route





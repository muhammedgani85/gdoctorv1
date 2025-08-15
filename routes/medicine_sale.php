<?php
use App\Http\Controllers\MedicineSaleController;
use Illuminate\Support\Facades\Route;

Route::resource('medicine-sale', MedicineSaleController::class);

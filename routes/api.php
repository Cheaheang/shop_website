<?php

use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('products', ProductApiController::class);

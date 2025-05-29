<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuoteController;

Route::post('/generate-quote', [QuoteController::class, 'generateQuote']);
<?php

use Illuminate\Support\Facades\Route;
use App\Classes\TronaldDump;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/tronaldquote', [TronaldDump::class, 'getRandomQuote']);
Route::get('/tronaldtags', [TronaldDump::class, 'getQuoteTags']);
Route::get('/tronaldquotebytag/{tag}', [TronaldDump::class, 'getTaggedQuotes']);

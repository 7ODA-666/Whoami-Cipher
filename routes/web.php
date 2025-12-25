<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CipherController;
use App\Http\Controllers\Algorithms\CaesarController;
use App\Http\Controllers\Algorithms\HillController;
use App\Http\Controllers\Algorithms\RailFenceController;
use App\Http\Controllers\Algorithms\PolyalphabeticController;
use App\Http\Controllers\Algorithms\OneTimePadController;
use App\Http\Controllers\Algorithms\MonoalphabeticController;
use App\Http\Controllers\Algorithms\PlayfairController;
use App\Http\Controllers\Algorithms\RowColumnTranspositionController;

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

Route::get('/', [CipherController::class, 'index'])->name('home');

// Caesar Cipher routes
Route::prefix('caesar')->name('caesar.')->group(function () {
    Route::get('/encryption', [CaesarController::class, 'encryption'])->name('encryption');
    Route::post('/encrypt', [CaesarController::class, 'processEncrypt'])->name('process.encrypt');
    Route::get('/decryption', [CaesarController::class, 'decryption'])->name('decryption');
    Route::post('/decrypt', [CaesarController::class, 'processDecrypt'])->name('process.decrypt');
    Route::get('/about', [CaesarController::class, 'about'])->name('about');
});

// Monoalphabetic routes
Route::prefix('monoalphabetic')->name('monoalphabetic.')->group(function () {
    Route::get('/encryption', [MonoalphabeticController::class, 'encryption'])->name('encryption');
    Route::post('/encrypt', [MonoalphabeticController::class, 'processEncrypt'])->name('process.encrypt');
    Route::post('/generate-key', [MonoalphabeticController::class, 'generateKey'])->name('generate.key');
    Route::get('/decryption', [MonoalphabeticController::class, 'decryption'])->name('decryption');
    Route::post('/decrypt', [MonoalphabeticController::class, 'processDecrypt'])->name('process.decrypt');
    Route::get('/about', [MonoalphabeticController::class, 'about'])->name('about');
});

// Polyalphabetic (Vigenère) routes
Route::prefix('polyalphabetic')->name('polyalphabetic.')->group(function () {
    Route::get('/encryption', [PolyalphabeticController::class, 'encryption'])->name('encryption');
    Route::post('/encrypt', [PolyalphabeticController::class, 'processEncrypt'])->name('process.encrypt');
    Route::get('/decryption', [PolyalphabeticController::class, 'decryption'])->name('decryption');
    Route::post('/decrypt', [PolyalphabeticController::class, 'processDecrypt'])->name('process.decrypt');
    Route::get('/about', [PolyalphabeticController::class, 'about'])->name('about');
});

// One-Time Pad routes
Route::prefix('one-time-pad')->name('one-time-pad.')->group(function () {
    Route::get('/encryption', [OneTimePadController::class, 'encryption'])->name('encryption');
    Route::post('/encrypt', [OneTimePadController::class, 'processEncrypt'])->name('process.encrypt');
    Route::post('/generate-key', [OneTimePadController::class, 'generateKey'])->name('generate.key');
    Route::get('/decryption', [OneTimePadController::class, 'decryption'])->name('decryption');
    Route::post('/decrypt', [OneTimePadController::class, 'processDecrypt'])->name('process.decrypt');
    Route::get('/about', [OneTimePadController::class, 'about'])->name('about');
});

// Playfair routes
Route::prefix('playfair')->name('playfair.')->group(function () {
    Route::get('/encryption', [PlayfairController::class, 'encryption'])->name('encryption');
    Route::post('/encrypt', [PlayfairController::class, 'processEncrypt'])->name('process.encrypt');
    Route::get('/decryption', [PlayfairController::class, 'decryption'])->name('decryption');
    Route::post('/decrypt', [PlayfairController::class, 'processDecrypt'])->name('process.decrypt');
    Route::get('/about', [PlayfairController::class, 'about'])->name('about');
});


// Hill Cipher routes
Route::prefix('hill')->name('hill.')->group(function () {
    Route::get('/encryption', [HillController::class, 'encryption'])->name('encryption');
    Route::post('/encrypt', [HillController::class, 'processEncrypt'])->name('process.encrypt');
    Route::post('/generate-key', [HillController::class, 'generateKey'])->name('generate.key');
    Route::get('/decryption', [HillController::class, 'decryption'])->name('decryption');
    Route::post('/decrypt', [HillController::class, 'processDecrypt'])->name('process.decrypt');
    Route::get('/about', [HillController::class, 'about'])->name('about');
});

// Rail Fence routes
Route::prefix('rail-fence')->name('rail-fence.')->group(function () {
    Route::get('/encryption', [RailFenceController::class, 'encryption'])->name('encryption');
    Route::post('/encrypt', [RailFenceController::class, 'processEncrypt'])->name('process.encrypt');
    Route::get('/decryption', [RailFenceController::class, 'decryption'])->name('decryption');
    Route::post('/decrypt', [RailFenceController::class, 'processDecrypt'])->name('process.decrypt');
    Route::get('/about', [RailFenceController::class, 'about'])->name('about');
});


// Row-Column Transposition routes
Route::prefix('row-column-transposition')->name('row-column-transposition.')->group(function () {
    Route::get('/encryption', [RowColumnTranspositionController::class, 'encryption'])->name('encryption');
    Route::post('/encrypt', [RowColumnTranspositionController::class, 'processEncrypt'])->name('process.encrypt');
    Route::get('/decryption', [RowColumnTranspositionController::class, 'decryption'])->name('decryption');
    Route::post('/decrypt', [RowColumnTranspositionController::class, 'processDecrypt'])->name('process.decrypt');
    Route::get('/about', [RowColumnTranspositionController::class, 'about'])->name('about');
});




Route::get('/usecase', function () {
    return view('diagrams.usecase');
});

Route::get('/class', function () {
    return view('diagrams.class');
});

Route::get('/sequence-search', function () {
    return view('diagrams.sequence-search');
});

Route::get('/sequence-add', function () {
    return view('diagrams.sequence-add');
});

Route::get('/sequence-checkout', function () {
    return view('diagrams.sequence-checkout');
});

Route::get('/component', function () {
    return view('diagrams.component');
});

Route::get('/deployment', function () {
    return view('diagrams.deployment');
});

Route::get('/state-payment', function () {
    return view('diagrams.state-payment');
});

Route::get('/state-order', function () {
    return view('diagrams.state-order');
});



Route::view('database/erd', 'database.erd');
Route::view('database/schema', 'database.schema');

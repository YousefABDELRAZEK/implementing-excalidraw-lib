<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DrawingController;

// Manually define the API routes for drawings
Route::get('/drawings', [DrawingController::class, 'index'])->name('drawings.index'); // List all drawings
Route::post('/drawings', [DrawingController::class, 'store'])->name('drawings.store'); // Save a new drawing
Route::get('/drawings/{drawing}', [DrawingController::class, 'show'])->name('drawings.show'); // Get a specific drawing
Route::put('/drawings/{drawing}', [DrawingController::class, 'update'])->name('drawings.update'); // Update a drawing
Route::delete('/drawings/{drawing}', [DrawingController::class, 'destroy'])->name('drawings.destroy'); // Delete a drawing



Route::get('/default-drawing', [DrawingController::class, 'defaultDrawing'])->name('drawings.default');



Route::get('/', function () {
    return view('excalidraw'); // Ensure your Blade file is in `resources/views/excalidraw.blade.php`
});

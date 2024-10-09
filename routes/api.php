<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonController;

Route::prefix('people')->group(function () {
    Route::get('/', [PersonController::class,'list'])->name('listPeople');
    Route::post('/', [PersonController::class,'save'])->name('savePerson');
    Route::delete('/{id}', [PersonController::class,'remove'])->name('deletePerson');
});

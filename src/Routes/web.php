<?php

use Illuminate\Support\Facades\Route;
use Dgtlss\Cosmo\Controllers\CosmoController;
use Dgtlss\Cosmo\Controllers\CosmoCentreController;

Route::group(['prefix' => 'cosmo'], function () {
    Route::get('/', [CosmoController::class, 'index'])->name('cosmo'); // Show dashboard
    Route::get('/{uid}', [CosmoController::class, 'show'])->name('cosmo.show'); // Show error details

    // Functions for interacting with the errors in the database
    Route::get('/{uid}/resolve', [CosmoCentreController::class, 'resolve'])->name('cosmo.resolve'); // Resolve error
    Route::get('/{uid}/unresolve', [CosmoCentreController::class, 'unresolve'])->name('cosmo.unresolve'); // Unresolve error
    Route::get('/{uid}/delete', [CosmoCentreController::class, 'delete'])->name('cosmo.delete'); // Delete error
    Route::get('/{uid}/watch', [CosmoCentreController::class, 'watch'])->name('cosmo.watch'); // Watch error
    Route::get('/{uid}/unwatch', [CosmoCentreController::class, 'unwatch'])->name('cosmo.unwatch'); // Unwatch error
    Route::get('/{uid}/ignore', [CosmoCentreController::class, 'ignore'])->name('cosmo.ignore'); // Ignore error
    Route::get('/{uid}/unignore', [CosmoCentreController::class, 'unignore'])->name('cosmo.unignore'); // Unignore error
    Route::get('/{uid}/flag', [CosmoCentreController::class, 'flag'])->name('cosmo.flag'); // Flag error
    Route::get('/{uid}/unflag', [CosmoCentreController::class, 'unflag'])->name('cosmo.unflag'); // Unflag error
});

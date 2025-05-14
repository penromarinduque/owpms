<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserRoleController;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix("user-roles")->group(function () {
        Route::get("search", [UserRoleController::class, "apiSearch"])->name("api.userroles.search");
    });
});

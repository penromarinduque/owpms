<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix("user-roles")->group(function () {
        Route::get("search", [UserRoleController::class, "apiSearch"])->name("api.userroles.search");
    });

    Route::prefix("users")->group(function () {
        Route::get("search", [UserController::class, "apiSearch"])->name("api.permittees.search");
    });
});

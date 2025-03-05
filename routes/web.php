<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SpecieClassController;
use App\Http\Controllers\SpecieFamilyController;
use App\Http\Controllers\SpecieTypeController;
use App\Http\Controllers\SpecieController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PermitteeController;
use App\Http\Controllers\PersonalInfoController;
use App\Http\Controllers\LtpRequirementController;
use App\Http\Controllers\MyApplicationController;
use App\Http\Controllers\PermitteeSpecieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {
    return view('auth.login');
});

// Auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/activate-account/{user}', [AuthController::class, 'activateAcount'])->name('activate-account');

// Forgot password form and email submission
Route::get('/forgot-password', [PasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [PasswordController::class, 'sendResetLink'])->name('password.email');

// Reset password form and update
Route::get('/reset-password/{token}', [PasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordController::class, 'resetPassword'])->name('password.update');

Route::middleware('auth')->group(function (){
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Permittee
    Route::prefix('permittees')->group(function () {

        // Permittee Species
        Route::prefix('permitteespecies')->group(function () {
            Route::get('/create', [PermitteeSpecieController::class, 'create'])->name('permitteespecies.create');
            Route::post('/store', [PermitteeSpecieController::class, 'store'])->name('permitteespecies.store');
            Route::put('/update', [PermitteeSpecieController::class, 'update'])->name('permitteespecies.update');
            Route::delete('/delete/{id}', [PermitteeSpecieController::class, 'destroy'])->name('permitteespecies.delete');
            Route::get('/ajaxgetspecies', [PermitteeSpecieController::class, 'ajaxGetSpecies'])->name('permitteespecies.ajaxgetspecies');
            Route::get('/ajaxGetPermittees', [PermitteeSpecieController::class, 'ajaxGetPermittees'])->name('permitteespecies.ajaxGetPermittees');
            Route::get('/{id}', [PermitteeSpecieController::class, 'index'])->name('permitteespecies.index');
        });

        Route::get('/', [PermitteeController::class, 'index'])->name('permittees.index');
        Route::get('create', [PermitteeController::class, 'create'])->name('permittees.create');
        Route::post('/store', [PermitteeController::class, 'store'])->name('permittees.store');
        Route::get('/show/{id}', [PermitteeController::class, 'show'])->name('permittees.show');
        Route::get('/{id}', [PermitteeController::class, 'edit'])->name('permittees.edit');
        Route::post('/update/{id}', [PermitteeController::class, 'update'])->name('permittees.update');
        Route::post('/ajaxupdatestatus', [PermitteeController::class, 'ajaxUpdateStatus'])->name('permittees.ajaxupdatestatus');

        
    });

    // Users
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{id}', [UserController::class, 'edit'])->name('users.edit');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('users.update');
        Route::get('/show/{id}', [UserController::class, 'show'])->name('users.show');
        Route::post('/ajaxupdatestatus', [UserController::class, 'ajaxUpdateStatus'])->name('users.ajaxupdatestatus');
    });

    // Species
    Route::prefix('species')->group(function () {
        Route::get('/', [SpecieController::class, 'index'])->name('species.index');
        Route::get('create', [SpecieController::class, 'create'])->name('species.create');
        Route::post('/', [SpecieController::class, 'store'])->name('species.store');
        Route::get('/{id}', [SpecieController::class, 'edit'])->name('species.edit');
        Route::post('/update/{id}', [SpecieController::class, 'update'])->name('species.update');
        Route::get('/show/{id}', [SpecieController::class, 'show'])->name('species.show');
        Route::post('/ajaxupdatestatus', [SpecieController::class, 'ajaxUpdateStatus'])->name('species.ajaxupdatestatus');
    });

    // Client Application
    Route::prefix('myapplication')->group(function () {
        Route::get('/', [MyApplicationController::class, 'index'])->name('myapplication.index');
        Route::get('create', [MyApplicationController::class, 'create'])->name('myapplication.create');
        Route::post('/store', [MyApplicationController::class, 'store'])->name('myapplication.store');
        Route::post('/preview', [MyApplicationController::class, 'preview'])->name('myapplication.preview');
        Route::post('ajaxgetspecies', [MyApplicationController::class, 'ajaxGetSpecies'])->name('myapplication.ajaxgetspecies');
    });

    // Maintenance
    Route::prefix('maintenance')->group(function () {
        Route::prefix('species')->group(function () {
            // Types
            Route::prefix('types')->group(function () {
                Route::get('/', [SpecieTypeController::class, 'index'])->name('specietypes.index');
                Route::get('create', [SpecieTypeController::class, 'create'])->name('specietypes.create');
                Route::post('/', [SpecieTypeController::class, 'store'])->name('specietypes.store');
                Route::get('/apiSearch', [SpecieTypeController::class, 'apiSearch'])->name('specietypes.apiSearch');
                Route::get('/{id}', [SpecieTypeController::class, 'edit'])->name('specietypes.edit');
                Route::post('/update/{id}', [SpecieTypeController::class, 'update'])->name('specietypes.update');
            });

            // Classes
            Route::prefix('classes')->group(function () {
                Route::get('/', [SpecieClassController::class, 'index'])->name('specieclasses.index');
                Route::get('create', [SpecieClassController::class, 'create'])->name('specieclasses.create');
                Route::post('/', [SpecieClassController::class, 'store'])->name('specieclasses.store');
                Route::get('/apiSearch', [SpecieClassController::class, 'apiSearch'])->name('specieclasses.apiSearch');
                Route::get('/apiGetByType', [SpecieClassController::class, 'apiGetByType'])->name('specieclasses.apiGetByType');
                Route::get('/{id}', [SpecieClassController::class, 'edit'])->name('specieclasses.edit');
                Route::post('/update/{id}', [SpecieClassController::class, 'update'])->name('specieclasses.update');
            });

            // Families
            Route::prefix('families')->group(function () {
                Route::get('/', [SpecieFamilyController::class, 'index'])->name('speciefamilies.index');
                Route::get('create', [SpecieFamilyController::class, 'create'])->name('speciefamilies.create');
                Route::post('/', [SpecieFamilyController::class, 'store'])->name('speciefamilies.store');
                Route::get('/apiGetByClass', [SpecieFamilyController::class, 'apiGetByClass'])->name('speciefamilies.apiGetByClass');
                Route::get('/{id}', [SpecieFamilyController::class, 'edit'])->name('speciefamilies.edit');
                Route::post('/update/{id}', [SpecieFamilyController::class, 'update'])->name('speciefamilies.update');
            });
        });

        // LTP Requirements
        Route::prefix('ltprequirements')->group(function () {
            Route::get('/', [LtpRequirementController::class, 'index'])->name('ltprequirements.index');
            Route::get('create', [LtpRequirementController::class, 'create'])->name('ltprequirements.create');
            Route::post('/', [LtpRequirementController::class, 'store'])->name('ltprequirements.store');
            Route::get('/{id}', [LtpRequirementController::class, 'edit'])->name('ltprequirements.edit');
            Route::post('/update/{id}', [LtpRequirementController::class, 'update'])->name('ltprequirements.update');
        });

        // Positions
        Route::prefix('positions')->group(function () {
            Route::get('/', [PositionController::class, 'index'])->name('positions.index');
            Route::get('create', [PositionController::class, 'create'])->name('positions.create');
            Route::post('/', [PositionController::class, 'store'])->name('positions.store');
            Route::get('/{id}', [PositionController::class, 'edit'])->name('positions.edit');
            Route::post('/update/{id}', [PositionController::class, 'update'])->name('positions.update');
        });
    });
});

Route::get('/test-email', function () {
    $details = [
        'subject' => 'Test Email',
        'body' => 'This is a test email from Laravel.'
    ];

    try {
        Mail::raw($details['body'], function ($message) use ($details) {
            $message->to('jandusayjoe14@gmail.com')
                    ->subject($details['subject']);
        });
        return 'Email sent successfully!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::get("/generate-password", function (Request $request) {
    $password = $request->password;
    return Hash::make($password);
});


Route::view('/oop', 'doc_templates.oop');
Route::view('/oop-dashboard', 'doc_templates.oop-dashboard');
Route::view('/oop-form', 'doc_templates.oop-form');
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LtpApplicationController;
use App\Http\Controllers\LtpApplicationRequirementController;
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
use App\Http\Controllers\LtpFeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserRoleController;

Route::get('/', function () {
    return view('welcome');
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

        Route::get('render-permittee-info-card/{id}', [PermitteeController::class, 'renderPermitteeInfoCard'])->name("permittee.cardInfo");

        // Permittee Species
        Route::prefix('permitteespecies')->group(function () {
            Route::get('/ajaxgetspecies', [PermitteeSpecieController::class, 'ajaxGetSpecies'])->name('permitteespecies.ajaxgetspecies');
            Route::get('/ajaxGetPermittees', [PermitteeSpecieController::class, 'ajaxGetPermittees'])->name('permitteespecies.ajaxGetPermittees');

            Route::middleware(['userType:admin,internal'])->group(function () {
                Route::get('/create', [PermitteeSpecieController::class, 'create'])->name('permitteespecies.create')->middleware('permission:PERMITTEE_SPECIE_CREATE');
                Route::post('/store', [PermitteeSpecieController::class, 'store'])->name('permitteespecies.store')->middleware('permission:PERMITTEE_SPECIE_CREATE');
                Route::put('/update', [PermitteeSpecieController::class, 'update'])->name('permitteespecies.update')->middleware('permission:PERMITTEE_SPECIE_UPDATE');
                Route::delete('/delete/{id}', [PermitteeSpecieController::class, 'destroy'])->name('permitteespecies.delete')->middleware('permission:PERMITTEE_SPECIE_DELETE');
                Route::get('/{id}', [PermitteeSpecieController::class, 'index'])->name('permitteespecies.index')->middleware('permission:PERMITTEE_SPECIE_INDEX');
            });
        });

        Route::post('/ajaxupdatestatus', [PermitteeController::class, 'ajaxUpdateStatus'])->name('permittees.ajaxupdatestatus');
        Route::middleware(['userType:admin,internal'])->group(function () {
            Route::get('/', [PermitteeController::class, 'index'])->name('permittees.index')->middleware('permission:PERMITTEE_INDEX');
            Route::get('create', [PermitteeController::class, 'create'])->name('permittees.create')->middleware('permission:PERMITTEE_CREATE');
            Route::post('/store', [PermitteeController::class, 'store'])->name('permittees.store')->middleware('permission:PERMITTEE_STORE');
            Route::get('/show/{id}', [PermitteeController::class, 'show'])->name('permittees.show')->middleware('permission:PERMITTEE_INDEX');
            Route::post('/upload-permit/{id}', [PermitteeController::class, 'uploadPermit'])->name('permittees.uploadpermit')->middleware('permission:PERMITTEE_UPDATE');
            Route::get('/{id}', [PermitteeController::class, 'edit'])->name('permittees.edit')->middleware('permission:PERMITTEE_UPDATE');
            Route::post('/update/{id}', [PermitteeController::class, 'update'])->name('permittees.update')->middleware('permission:PERMITTEE_UPDATE');
        });
    });

    // Users
    Route::prefix('users')->group(function () {
        Route::middleware(["userType:admin,internal"])->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('users.index')->middleware('permission:USER_INDEX');
            Route::get('create', [UserController::class, 'create'])->name('users.create')->middleware('permission:USER_CREATE');
            Route::post('/', [UserController::class, 'store'])->name('users.store')->middleware('permission:USER_CREATE');
            Route::get('/{id}', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:USER_UPDATE');
            Route::post('/update/{id}', [UserController::class, 'update'])->name('users.update')->middleware('permission:USER_UPDATE');
            Route::get('/show/{id}', [UserController::class, 'show'])->name('users.show')->middleware('permission:USER_INDEX');
        });
        Route::post('/ajaxupdatestatus', [UserController::class, 'ajaxUpdateStatus'])->name('users.ajaxupdatestatus');
    });

    // Species
    Route::prefix('species')->group(function () {
        Route::post('/ajaxupdatestatus', [SpecieController::class, 'ajaxUpdateStatus'])->name('species.ajaxupdatestatus');
        Route::middleware(["userType:admin,internal"])->group(function () {
            Route::get('/', [SpecieController::class, 'index'])->name('species.index')->middleware('permission:SPECIES_INDEX');
            Route::get('create', [SpecieController::class, 'create'])->name('species.create')->middleware('permission:SPECIES_CREATE');
            Route::post('/', [SpecieController::class, 'store'])->name('species.store')->middleware('permission:SPECIES_CREATE');
            Route::get('/{id}', [SpecieController::class, 'edit'])->name('species.edit')->middleware('permission:SPECIES_UPDATE');
            Route::post('/update/{id}', [SpecieController::class, 'update'])->name('species.update')->middleware('permission:SPECIES_UPDATE');
            Route::get('/show/{id}', [SpecieController::class, 'show'])->name('species.show')->middleware('permission:SPECIES_INDEX');
        });
    });

    // Client Application
    Route::prefix('myapplication')->group(function () {
        Route::get('/', [MyApplicationController::class, 'index'])->name('myapplication.index');
        
        Route::get('create', [MyApplicationController::class, 'create'])->name('myapplication.create');
        Route::get('edit/{id}', [MyApplicationController::class, 'edit'])->name('myapplication.edit');
        Route::patch('update/{id}', [MyApplicationController::class, 'update'])->name('myapplication.update');
        Route::post('submit/{id}', [MyApplicationController::class, 'submit'])->name('myapplication.submit');
        Route::post('resubmit/{id}', [MyApplicationController::class, 'resubmit'])->name('myapplication.resubmit');
        Route::get('/preview/{id}', [MyApplicationController::class, 'preview'])->name('myapplication.preview');
        Route::get('/print-request-letter/{id}', [MyApplicationController::class, 'printRequestLetter'])->name('myapplication.printRequestLetter');
        
        Route::delete('{id}', [MyApplicationController::class, 'destroy'])->name('myapplication.destroy');
        Route::post('/store', [MyApplicationController::class, 'store'])->name('myapplication.store');
        Route::get('ajaxgetspecies', [MyApplicationController::class, 'ajaxGetSpecies'])->name('myapplication.ajaxgetspecies');
    });

    // Application Requirements
    Route::prefix('app-requirements/{id}')->group(function () {
        Route::get('', [MyApplicationController::class, 'requirements'])->name('myapplication.requirements');
        Route::post('upload', [LtpApplicationRequirementController::class, 'store'])->name('myapplication.upload-requirement');
    });

    // LTP Applications
    Route::prefix('ltpapplication')->group(function () {
        Route::middleware(["userType:admin,internal"])->group(function () {
            Route::get('', [LtpApplicationController::class, 'index'])->name('ltpapplication.index')->middleware('permission:LTP_APPLICATION_INDEX');
            Route::get('review/{id}', [LtpApplicationController::class, 'review'])->name('ltpapplication.review')->middleware('permission:LTP_APPLICATION_REVIEW');
            Route::get('preview/{id}', [MyApplicationController::class, 'preview'])->name('ltpapplication.preview')->middleware('permission:LTP_APPLICATION_INDEX');
            Route::post('return', [LtpApplicationController::class, 'return'])->name('ltpapplication.return')->middleware('permission:LTP_APPLICATION_RETURN');
            Route::get('render-logs', [LtpApplicationController::class, 'renderLogs'])->name('ltpapplication.renderLogs')->middleware('permission:LTP_APPLICATION_INDEX');
            Route::post('accept/{id}', [LtpApplicationController::class, 'accept'])->name('ltpapplication.accept')->middleware('permission:LTP_APPLICATION_ACCEPT');
            Route::get('generate-payment-order/{id}', [LtpApplicationController::class, 'generatePaymentOrder'])->name('ltpapplication.generatePaymentOrder')->middleware('permission:PAYMENT_ORDERS_CREATE');
        });
    });

    // Maintenance
    Route::prefix('maintenance')->group(function () {
        Route::prefix('species')->group(function () {
            // Types
            Route::prefix('types')->group(function () {
                Route::get('/apiSearch', [SpecieTypeController::class, 'apiSearch'])->name('specietypes.apiSearch');
                Route::middleware("userType:admin,internal")->group(function () {
                    Route::get('/', [SpecieTypeController::class, 'index'])->name('specietypes.index')->middleware('permission:WILDLIFE_TYPE_INDEX');
                    Route::get('create', [SpecieTypeController::class, 'create'])->name('specietypes.create')->middleware('permission:WILDLIFE_TYPE_CREATE');
                    Route::post('/', [SpecieTypeController::class, 'store'])->name('specietypes.store')->middleware('permission:WILDLIFE_TYPE_CREATE');
                    Route::get('/{id}', [SpecieTypeController::class, 'edit'])->name('specietypes.edit')->middleware('permission:WILDLIFE_TYPE_UPDATE');
                    Route::post('/update/{id}', [SpecieTypeController::class, 'update'])->name('specietypes.update')->middleware('permission:WILDLIFE_TYPE_UPDATE');
                });
            });

            // Classes
            Route::prefix('classes')->group(function () {
                Route::get('/apiSearch', [SpecieClassController::class, 'apiSearch'])->name('specieclasses.apiSearch');
                Route::get('/apiGetByType', [SpecieClassController::class, 'apiGetByType'])->name('specieclasses.apiGetByType');

                Route::middleware(["userType:admin,internal"])->group(function () {
                    Route::get('/', [SpecieClassController::class, 'index'])->name('specieclasses.index')->middleware('permission:CLASS_INDEX');
                    Route::get('create', [SpecieClassController::class, 'create'])->name('specieclasses.create')->middleware('permission:CLASS_CREATE');
                    Route::post('/', [SpecieClassController::class, 'store'])->name('specieclasses.store')->middleware('permission:CLASS_CREATE');
                    Route::get('/{id}', [SpecieClassController::class, 'edit'])->name('specieclasses.edit')->middleware('permission:CLASS_UPDATE');
                    Route::post('/update/{id}', [SpecieClassController::class, 'update'])->name('specieclasses.update')->middleware('permission:CLASS_UPDATE');
                });

                
            });

            // Families
            Route::prefix('families')->group(function () {
                Route::get('/apiGetByClass', [SpecieFamilyController::class, 'apiGetByClass'])->name('speciefamilies.apiGetByClass');

                Route::middleware(["userType:admin,internal"])->group(function () {
                    Route::get('/', [SpecieFamilyController::class, 'index'])->name('speciefamilies.index')->middleware('permission:FAMILY_INDEX');
                    Route::get('create', [SpecieFamilyController::class, 'create'])->name('speciefamilies.create')->middleware('permission:FAMILY_CREATE');
                    Route::post('/', [SpecieFamilyController::class, 'store'])->name('speciefamilies.store')->middleware('permission:FAMILY_CREATE');
                    Route::get('/{id}', [SpecieFamilyController::class, 'edit'])->name('speciefamilies.edit')->middleware('permission:FAMILY_UPDATE');
                    Route::post('/update/{id}', [SpecieFamilyController::class, 'update'])->name('speciefamilies.update')->middleware('permission:FAMILY_UPDATE');
                });
            });
        });

        // LTP Requirements
        Route::prefix('ltprequirements')->group(function () {
            Route::middleware(["userType:admin,internal"])->group(function () {
                Route::get('/', [LtpRequirementController::class, 'index'])->name('ltprequirements.index')->middleware('permission:LTP_REQUIREMENTS_INDEX');
                Route::get('create', [LtpRequirementController::class, 'create'])->name('ltprequirements.create')->middleware('permission:LTP_REQUIREMENTS_CREATE');
                Route::post('/', [LtpRequirementController::class, 'store'])->name('ltprequirements.store')->middleware('permission:LTP_REQUIREMENTS_CREATE');
                Route::get('/{id}', [LtpRequirementController::class, 'edit'])->name('ltprequirements.edit')->middleware('permission:LTP_REQUIREMENTS_UPDATE');
                Route::post('/update/{id}', [LtpRequirementController::class, 'update'])->name('ltprequirements.update')->middleware('permission:LTP_REQUIREMENTS_UPDATE');
            });
        });

        // Positions
        Route::middleware(["userType:admin,internal"])->prefix('positions')->group(function () {
            Route::get('/', [PositionController::class, 'index'])->name('positions.index')->middleware('permission:POSITION_INDEX');
            Route::get('create', [PositionController::class, 'create'])->name('positions.create')->middleware('permission:POSITION_CREATE');
            Route::post('/', [PositionController::class, 'store'])->name('positions.store')->middleware('permission:POSITION_CREATE');
            Route::get('/{id}', [PositionController::class, 'edit'])->name('positions.edit')->middleware('permission:POSITION_UPDATE');
            Route::post('/update/{id}', [PositionController::class, 'update'])->name('positions.update')->middleware('permission:POSITION_UPDATE');
        });

        //  LTP Fees
        Route::middleware(["userType:admin,internal"])->prefix('ltpfees')->group(function () {
            Route::get('/', [LtpFeeController::class, 'index'])->name('ltpfees.index')->middleware('permission:LTP_FEES_INDEX');
            Route::get('/create', [LtpFeeController::class, 'create'])->name('ltpfees.create')->middleware('permission:LTP_FEES_CREATE');
            Route::post('/store', [LtpFeeController::class, 'store'])->name('ltpfees.store')->middleware('permission:LTP_FEES_CREATE');
            Route::get('/edit/{id}', [LtpFeeController::class, 'edit'])->name('ltpfees.edit')->middleware('permission:LTP_FEES_UPDATE');
            Route::post('/update/{id}', [LtpFeeController::class, 'update'])->name('ltpfees.update')->middleware('permission:LTP_FEES_UPDATE');
            Route::delete('/destroy', [LtpFeeController::class, 'destroy'])->name('ltpfees.destroy')->middleware('permission:LTP_FEES_DELETE');
        });
    });

    Route::prefix('account')->group(function(){
        Route::get('/', [AccountController::class, 'index'])->name('account.index');
        Route::prefix("personal-info")->group(function () {
            Route::get("edit/{id}", [AccountController::class, 'editPersonalInfo'])->name("account.personalInfo.edit");
            Route::post("update/{id}", [AccountController::class, 'updatePersonalInfo'])->name("account.personalInfo.update");
        });

    });

    Route::prefix('iam')->group(function () {
        
        Route::middleware(['userType:admin'])->prefix('roles')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('iam.roles.index')->middleware('permission:ROLES_INDEX');
            Route::get('/create', [RoleController::class, 'create'])->name('iam.roles.create')->middleware('permission:ROLES_CREATE');
            Route::post('/', [RoleController::class, 'store'])->name('iam.roles.store')->middleware('permission:ROLES_CREATE');
            Route::get('/{id}', [RoleController::class, 'edit'])->name('iam.roles.edit')->middleware('permission:ROLES_UPDATE');
            Route::post('/update/{id}', [RoleController::class, 'update'])->name('iam.roles.update')->middleware('permission:ROLES_UPDATE');
        });

        Route::middleware(['userType:admin'])->prefix('user_roles')->group(function () {
            Route::get('/', [UserRoleController::class, 'index'])->name('iam.user_roles.index')->middleware('permission:USER_ROLES_INDEX');
            Route::post('/', [UserRoleController::class, 'store'])->name('iam.user_roles.store')->middleware('permission:USER_ROLES_CREATE');
            Route::get('/{id}', [UserRoleController::class, 'edit'])->name('iam.user_roles.edit')->middleware('permission:USER_ROLES_UPDATE');
            Route::post('/update/{id}', [UserRoleController::class, 'update'])->name('iam.user_roles.update')->middleware('permission:USER_ROLES_UPDATE');
            Route::delete('/{id}', [UserRoleController::class, 'destroy'])->name('iam.user_roles.destroy')->middleware('permission:USER_ROLES_DELETE');
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





Route::view('/oop', 'doc_templates.oop');
Route::view('/oop-dashboard', 'doc_templates.oop-dashboard');
Route::view('/oop-form', 'doc_templates.oop-form');


//  test routes
Route::get("/generate-password", function (Request $request) {
    $password = $request->password;

    if (!$password) {
        return response()->json(['error' => 'Please provide a password.'], 400);
    }

    return response()->json([
        'password' => $password,
        'hash' => Hash::make($password),
    ]);
});

Route::get("token", function () {
    return csrf_token();
});

Route::post("test", function (Request $request) {
    return $request->all();
});
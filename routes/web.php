<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationOfficersController;
use App\Models\RegistrationOfficers;
use App\Http\Controllers\PattientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


// - PACIENT

// Landing
Route::view("/", "pacient.index");

// Authentication
Route::view("/masuk", "pacient.auth.login");
Route::post("/masuk", fn () => view("pacient.auth.login"));

Route::view("/daftar", "pacient.auth.register");
Route::post("/daftar", fn () => view("pacient.auth.register"));

Route::view("/lupa-sandi", "pacient.auth.forgotPassword");
Route::post("/lupa-sandi", fn () => view("pacient.auth.forgotPassword"));

Route::get("/recovery/{token}", function ($token) {
    return view("pacient.auth.recovery");
});
Route::post("/recovery/{token}", fn () => view("pacient.auth.recovery"));

// Dashboard

Route::view("/dashboard", "pacient.dashboard.index", [
    "complaints" => [
        [
            "id" => "KL6584690",
            "description" => "Saya mengalami mual mual dan merasa selalu lemas setelah beberapa minggu ini hanya mengkonsumsi makanan mie......",
            "status" => "terkonfirmasi",
            "schedule" => "29 / Januari / 2023 15:30:00 - 16:30:00",
        ],
        [
            "id" => "KL6584690",
            "description" => "Saya mengalami mual mual dan merasa selalu lemas setelah beberapa minggu ini hanya mengkonsumsi makanan mie......",
            "status" => "terkonfirmasi",
            "schedule" => "29 / Januari / 2023 15:30:00 - 16:30:00",
        ]
    ]
]);

// Konsultasi
Route::prefix('konsultasi')->group(function () {
    Route::get('/', function () {
        echo "keluhan";
    });
    Route::get('/poliklinik', function () {
        echo "poliklinik";
    });
    Route::get('/dokter', function () {
        echo "dokter";
    });
    Route::get('/rincian', function () {
        echo "rincian";
    });
    Route::get('/pembayaran', function () {
        echo "pembayaran";
    });
});

//admin
Route::prefix('admin')->group(function () {
    Route::view('/','admin.dashboard', );
    Route::view('layout', 'layouts.admin.app');
    Route::view('pasien', 'admin.pasien');
    Route::prefix('admin')->group(function(){
        Route::get('/',[AdminController::class,'index']);
        Route::post('store',[AdminController::class,'store']);
    });
    Route::view('petugas','admin.petugas');


    Route::get('/token', function (Request $request) {
        $token = $request->session()->token();

        $token = csrf_token();

        echo $token;

        return $token;

        // ...
    });
});

Route::redirect("/keluar", "/masuk");

<?php

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

// # Login
Route::view("/masuk", "pacient.auth.login");
Route::post("/masuk", function (Request $request) {
    dd($request);
});

// # Register
Route::view("/daftar", "pacient.auth.register");
Route::post("/daftar", function (Request $request) {
    dd($request);
});

// # Forgot Password
Route::view("/lupa-sandi", "pacient.auth.forgot-password");
Route::post("/lupa-sandi", function (Request $request) {
    dd($request);
});

// # Password Recovery
Route::get("/recovery/{token}", function ($token) {
    return view("pacient.auth.recovery", compact("token"));
});
Route::post("/recovery/{token}", function (Request $request) {
    dd($request);
});

// Dashboard
Route::prefix("/dashboard")->group(function () {
    // # Showing data consultation, history and setting
    Route::view("/", "pacient.dashboard.index");

    // # Action pacient save setting
    Route::post("/save-setting", function (Request $request) {
        dd($request);
    });

    // # Action pacient change email
    Route::post("/change-email", function (Request $request) {
        dd($request);
    });

    // # Action pacient change password
    Route::post("/change-password", function (Request $request) {
        dd($request);
    });
});

// Consultation
Route::prefix('konsultasi')->group(function () {
    // Create consultation #1 - description complaint & set category
    Route::get('/', function () {
        return view("pacient.consultation.complaint", [
            // Show categories type disease
            "categories" => [
                "K001" => "Penyakit Langka",
                "K002" => "Kelainan Bawaan",
                "K003" => "Gangguan Mental",
                "K004" => "Cedera",
                "K005" => "Penyakit Dalam",
                "K000" => "Tidak Tahu"
            ]
        ]);
    });
    Route::post('/', function (Request $request) {
        session(['consultation' => [
            "description" => trim($request->input("consultation_complaint")),
            "category" => explode("-", $request->input("consultation_category")),
        ]]);
        return redirect("/konsultasi/poliklinik");
    });

    // Create consultation #2 - set polyclinic
    Route::get('/poliklinik', function () {
        if (!isset(session("consultation")["description"])) return redirect("/konsultasi");
        return view("pacient.consultation.polyclinic", [
            "polyclinics" => [
                "PL0001" => "POLIKLINIK OBGYN (OBSTETRI & GINEKOLOGI)",
                "PL0002" => "POLIKLINIK ANAK DAN TUMBUH KEMBANG",
                "PL0003" => "POLIKLINIK PENYAKIT DALAM (INTERNA)",
                "PL0004" => "POLIKLINIK BEDAH UMUM",
                "PL0005" => "POLIKLINIK BEDAH ONKOLOGI"
            ]
        ]);
    });
    Route::post('/poliklinik', function (Request $request) {
        session(['consultation' => array_merge(session('consultation'), [
            "polyclinic" => explode("-", $request->input("consultation_polyclinic"))
        ])]);
        return redirect("/konsultasi/dokter");
    });

    // Create consultation #3 - set doctor & schedule consultation
    Route::get('/dokter', function () {
        if (!isset(session("consultation")["polyclinic"])) return redirect("/konsultasi/poliklinik");
        return view("pacient.consultation.doctor", [
            "doctors" => [
                [
                    "id" => 1,
                    "name" => "dr. IDA AYU SRI KUSUMA DEWI, M.Sc, Sp.A,MARS",
                ],
                [
                    "id" => 2,
                    "name" => "dr. PUTU VIVI PARYATI, M.Biomed, Sp.A",
                ],
                [
                    "id" => 3,
                    "name" => "dr. LUH GDE AYU PRAMITHA DEWI, M.Biomed, Sp.A",
                ],
            ],
            "detail_doctor" => [
                "price_consultation" => "Rp. 90.000",
                "date_schedule" => [
                    1676394000,
                    1676480400,
                    1676653199,
                ],
                "time_schedule" => [
                    [
                        "start" => 1676422800,
                        "end" => 1676426400
                    ],
                    [
                        "start" => 1676426400,
                        "end" => 1676430000
                    ],
                    [
                        "start" => 1676430000,
                        "end" => 1676433600
                    ]
                ]
            ]
        ]);
    });
    Route::get('/dokter/{id}', function ($id) {
        if (!isset(session("consultation")["polyclinic"])) return redirect("/konsultasi/poliklinik");
        return view("pacient.consultation.doctor", [
            "id" => $id,
            "doctors" => [
                [
                    "id" => 1,
                    "name" => "dr. IDA AYU SRI KUSUMA DEWI, M.Sc, Sp.A,MARS",
                ],
                [
                    "id" => 2,
                    "name" => "dr. PUTU VIVI PARYATI, M.Biomed, Sp.A",
                ],
                [
                    "id" => 3,
                    "name" => "dr. LUH GDE AYU PRAMITHA DEWI, M.Biomed, Sp.A",
                ],
            ],
            "detail_doctor" => [
                "price_consultation" => "Rp. 90.000",
                "date_schedule" => [
                    1677395000,
                    1677824000,
                    1677654199,
                ],
                "time_schedule" => [
                    [
                        "start" => 1676422800,
                        "end" => 1676426400
                    ],
                    [
                        "start" => 1676426400,
                        "end" => 1676430000
                    ],
                    [
                        "start" => 1676430000,
                        "end" => 1676433600
                    ]
                ]
            ]
        ]);
    });
    Route::get('/dokter/{id}/{date}', function ($id, $date) {
        if (!isset(session("consultation")["polyclinic"])) return redirect("/konsultasi/poliklinik");
        return view("pacient.consultation.doctor", [
            "id" => $id,
            "date" => $date,
            "doctors" => [
                [
                    "id" => 1,
                    "name" => "dr. IDA AYU SRI KUSUMA DEWI, M.Sc, Sp.A,MARS",
                ],
                [
                    "id" => 2,
                    "name" => "dr. PUTU VIVI PARYATI, M.Biomed, Sp.A",
                ],
                [
                    "id" => 3,
                    "name" => "dr. LUH GDE AYU PRAMITHA DEWI, M.Biomed, Sp.A",
                ],
            ],
            "detail_doctor" => [
                "price_consultation" => "Rp. 90.000",
                "date_schedule" => [
                    1677395000,
                    1677824000,
                    1677654199,
                ],
                "time_schedule" => [
                    [
                        "start" => 1676422800,
                        "end" => 1676426400
                    ],
                    [
                        "start" => 1676426400,
                        "end" => 1676430000
                    ],
                    [
                        "start" => 1676430000,
                        "end" => 1676433600
                    ]
                ]
            ]
        ]);
    });
    Route::post('/dokter', function (Request $request) {
        session(['consultation' => array_merge(session('consultation'), [
            "doctor" => explode("-", $request->input("consultation_doctor")),
            "price" => $request->input("consultation_price"),
            "schedule_date" => $request->input("consultation_schedule_date"),
            "schedule_time" => explode("-", $request->input("consultation_schedule_time"))
        ])]);
        return redirect("/konsultasi/rincian");
    });

    // Create consultation #4 - showing confirmation desciption data
    Route::get('/rincian', function () {
        if (!isset(session("consultation")["doctor"])) return redirect("/konsultasi/dokter");
        return view("pacient.consultation.detail-order");
    });
    Route::post('/rincian', function (Request $request) {
        // set data into database and remove session
        return redirect("/konsultasi/KL6584690#payment");
    });

    // Show pacient consultation based on ID
    Route::get('/{id}', function ($id) {
        return view("pacient.consultation.detail-consultation", [
            "id" => $id,
            "name_pacient" => "Aristo Caesar Pratama",
            "description" => "Saya mengalami mual mual dan merasa selalu lemas setelah beberapa minggu ini hanya dsdsdsdsds makanan mie......",
            "category" => "Penyakit Dalam",
            "polyclinic" => "POLIKLINIK PENYAKIT DALAM (INTERNA)",
            "doctor" => "DR. H. M. Pilox Kamacho H., S.pb",
            "schedule" => 1676897803,
            "start_consultation" => 1676897803,
            "end_consultation" => 1676897803,
            "live_consultation" => false,
            "status" => "confirmed-medical-prescription-payment",

            "price_consultation" => "Rp. 90.000",
            "status_payment_consultation" => "TERKONFIRMASI", // PROSES / BELUM TERKONFIRMASI / TERKONFIRMASI
            "proof_payment_consultation" => "https://i.pinimg.com/236x/68/ed/dc/68eddcea02ceb29abde1b1c752fa29eb.jpg",

            "price_medical_prescription" => "Rp. 100.000", // null
            "status_payment_medical_prescription" => "TERKONFIRMASI",
            "proof_payment_medical_prescription" => "https://tangerangonline.id/wp-content/uploads/2021/06/IMG-20210531-WA0027.jpg",

            "pickup_medical_prescription" => "hospital-pharmacy", // hospital-pharmacy, delivery-gojek
            "pickup_medical_status" => "SUDAH DIAMBIL", // MENUNGGU DIAMBIL, SUDAH DIAMBIL, DIKIRIM DENGAN GOJEK, GAGAL DIKIRIM, TIDAK MENERIMA SEKRANG
            "pickup_medical_no_telp_pacient" => "085235119101",
            "pickup_medical_addreass_pacient" => "Enim ullamco reprehenderit nulla aliqua reprehenderit",
            "pickup_medical_description" => "Alamat yang anda berikan tidak dapat dituju oleh driver GOJEK", // alamat penerima tidak valid, pasien tidak dapat dihubungi
            "pickup_by_pacient" => "Aristo Caesar Pratama",
            "pickup_datetime" => 1676184847,

            "valid_status" => 1677030619
        ]);
    });

    // Cancel sheduling consultation
    Route::get('/{id}/cancel-consultation', fn ($id) => redirect("/konsultasi/{$id}"));
    Route::post('/{id}/cancel-consultation', function ($id) {
        dd($id);
    });

    // Send proof payment to confirmation consultation
    Route::get('/{id}/payment-consultation', fn ($id) => redirect("/konsultasi/{$id}"));
    Route::post('/{id}/payment-consultation', function (Request $request, $id) {
        dd([
            "id" => $id,
            "state-payment" => $request->input("state-payment"),
            "bank-payment" => $request->input("bank-payment"),
            "upload-proof-payment" => $request->file('upload-proof-payment')
        ]);
    });

    // Cancel scheduling medical prescription
    Route::get('/{id}/cancel-medical-prescription', fn ($id) => redirect("/konsultasi/{$id}"));
    Route::post('/{id}/cancel-medical-prescription', function ($id) {
        dd($id);
    });

    // Send proof payment to confirmation medical prescription
    Route::get('/{id}/payment-medical-prescription', fn ($id) => redirect("/konsultasi/{$id}"));
    Route::post('/{id}/payment-medical-prescription', function (Request $request, $id) {
        dd([
            "id" => $id,
            "state-payment" => $request->input("state-payment"),
            "bank-payment" => $request->input("bank-payment"),
            "upload-proof-payment" => $request->file('upload-proof-payment')
        ]);
    });

    // Pacient generate consultation pickup document based on id
    Route::get("/{id}/export", function ($id) {
        $document = [
            "fullname" => "Aristo Caesar Pratama",
            "no_medical_record" => "00-89-43-78-34-56",
            "id_consultation" => "KL6584691",
            "valid_status" => 1676134847,
            "consultation" => [
                "doctor" => "DR. H. M. Pilox Kamacho H., S.pb",
                "price" => "Rp. 90.000",
                "status" => "TERKOFIRMASI",
            ],
            "medical" => [
                "price" => "Rp. 90.000",
                "status" => "TERKOFIRMASI",
            ]
        ];
        $pdf = PDF::loadView("pacient.consultation.pdf.consultation_pickup", compact("document"));
        return $pdf->download("DOKUMEN PENGAMBILAN OBAT - {$id}.pdf");
    });

    // Set option pickup delivery medical prescription
    Route::get('/{id}/pickup-delivery', fn ($id) => redirect("/konsultasi/{$id}"));
    Route::post('/{id}/pickup-delivery', function (Request $request, $id) {
        dd([
            "id" => $id,
            "pickup-medical-prescription" => $request->input('pickup-medical-prescription'),
            "pacient_notelp" => $request->input("pacient-notelp"),
            "pacient_address" => $request->input("pacient-addreass")
        ]);
    });

    // Cancel pickup medical prescription
    Route::get('/{id}/cancel-pickup', fn ($id) => redirect("/konsultasi/{$id}"));
    Route::post('/{id}/cancel-pickup', function ($id) {
        dd([
            "id" => $id
        ]);
    });
});

// Logout ( Clear all session pacient )
Route::get("/keluar", function () {
    return redirect('/masuk');
});

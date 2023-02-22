<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePattientRequest;
use App\Http\Requests\UpdatePattientRequest;
use App\Models\Pattient;
use App\Services\PattientService;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class PattientController extends Controller
{

    private PattientService $service;


    public function __construct()
    {
        $this->service = new PattientService();
    }

    public function index()
    {
        $data = $this->service->findAll();
        return view('admin.pasien'  , ["data" => $data]);
        // no data
    }

    public function create()
    {
        // return view create new pattient
    }
    public function store(StorePattientRequest $request)
    {
        // dd($request);
        $request->validate(['citizen' => ['required']]);
        // bool return
        if ($request['citizen'] == 'WNI') {
            $res =  $this->service->store($request->validate([
                'fullname' => ['required', 'string', 'min:4'],
                'email' => ['required', 'email', 'unique:pattient,email'],
                'gender' => ['required'],
                'password' => ['required'],
                'phone_number' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:13'],
                'address_RT' => ['required', 'numeric'],
                'address_RW' => ['required', 'numeric'],
                'address_desa' => ['required', 'string'],
                'address_dusun' => ['required', 'string'],
                'address_kecamatan' => ['required', 'string'],
                'address_kabupaten' => ['required', 'string'],
                'citizen' => ['required'],
                'profession' => ['required'],
                'date_birth' => ['required'],
                'blood_group' => ['required'],
                'place_birth' => ['required'],
                'nik' => ['required', 'numeric', 'min:16', 'unique:pattient,nik']
            ]));
            if ($res) {
                // success register
                return redirect()->back()->with('message', 'berhasil registrasi harap menunggu hingga no rekam medis di kirimkan');
            } else {
                // failed register
                return redirect()->back()->with('message', 'gagal registrasi terjadi kesalahan server');
            }
        } else {
            $res =  $this->service->store($request->validate([
                'fullname' => ['required', 'string', 'min:4'],
                'email' => ['required', 'email', 'unique:pattient,email'],
                'gender' => ['required'],
                'password' => ['required'],
                'phone_number' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:13'],
                'address_RT' => ['required', 'numeric'],
                'address_RW' => ['required', 'numeric'],
                'address_desa' => ['required', 'string'],
                'address_dusun' => ['required', 'string'],
                'address_kecamatan' => ['required', 'string'],
                'address_kabupaten' => ['required', 'string'],
                'citizen' => ['required'],
                'profession' => ['required'],
                'date_birth' => ['required'],
                'blood_group' => ['required'],
                'place_birth' => ['required'],
                'no_paspor' => ['required', 'string', 'min:10', 'unique:pattient,no_paspor']
            ]));
            if ($res) {
                // success register
                return redirect()->back()->with('message', 'berhasil registrasi harap menunggu hingga no rekam medis di kirimkan');
            } else {
                // failed register

                return redirect()->back()->with('message', 'gagal registrasi terjadi kesalahan server');
            }
        }
    }

    public function show(Pattient $pattient)
    {
        $data = $this->service->findById($pattient->id);
        return $data;
    }

    public function edit(Pattient $pattient)
    {
        $data = $this->service->findById($pattient);
        // return view to edit , with data variable 
    }

    public function update(UpdatePattientRequest $request, Pattient $pattient)
    {
        $res =  $this->service->update($request->validate($request->rules()), $pattient->id);
        if ($res['status']) {
            return view()->with("message", $res['message']);
        } else {
            return view()->with("message", $res['message']);
        }
    }

    public function destroy(Pattient $pattient)
    {
        // res boolean true if success false if not found
        $res = $this->service->deleteById($pattient->id);
    }

    public function oke(){
        dd("ok");
    }
}
<?php


namespace App\Services;

use App\Helpers\Helper;
use App\Models\MedicalRecords;
use App\Models\Pattient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Sarav\Multiauth\MultiauthServiceProvider;
use Illuminate\Validation\ValidationException;

use function PHPUnit\Framework\isEmpty;

class PattientService
{


    private Pattient $model;
    private MedicalRecords $medicalRecords;
    private MedicalRecordService $medicalRecordService;

    public function __construct()
    {
        $this->medicalRecordService = new MedicalRecordService();
        $this->model = new Pattient();
        $this->medicalRecords = new MedicalRecords();
    }

    public function findAll()
    {
        $data = $this->model->all()->toArray();
        return $data;
    }
    public function store(array $request): bool
    {
        try {
            $request['password'] = bcrypt($request['password']);
            $request['name'] = $request['fullname'];
            $request['address'] = "RT/RW : " . $request['address_RT'] . "/" . $request['address_RW'] . " Dusun " . $request['address_dusun'] . " Desa " . $request['address_desa'] . " Kec. " . $request['address_kecamatan'] . " Kab." . $request['address_kabupaten'];
            $res = $this->model->create($request);
            if ($res) {
                return true;
            }
            return false;
        } catch (ValidationException $ex) {
            return false;
        }
    }

    public function storeWithAdmin(array $request)
    {
        $res = [];
        try {
            $request['password'] = bcrypt($request['password']);
            $request['name'] = $request['fullname'];
            $request['address'] = "RT/RW : " . $request['address_RT'] . "/" . $request['address_RW'] . " Dusun " . $request['address_dusun'] . " Desa " . $request['address_desa'] . " Kec. " . $request['address_kecamatan'] . " Kab." . $request['address_kabupaten'];
            $response = $this->model->create($request);
            $findRekamMedic = $this->medicalRecords->where('medical_record_id', $request['medical_record_id'])->first();
            if ($findRekamMedic != null) {
                $res['status'] = false;
                $res['message'] = 'gagal menambahkan rekam medic no rekam medic tidak boleh sama';
                return $res;
            }
            if ($response) {
                $dataRekamMedic = [
                    "medical_record_id" => $request['medical_record_id'],
                    "id_registration_officer" => $request['id_registration_officer']
                ];
                $insertRekamMedic = $this->medicalRecordService->insert(
                    $dataRekamMedic
                );
                if ($insertRekamMedic['status']) {
                    $resUpdate = $this->model->where('id', $response->id)->update(
                        [
                            'medical_record_id' => $request['medical_record_id']
                        ]
                    );
                    if ($resUpdate) {
                        $res['status'] = true;
                        $res['message'] = 'berhasil menambahkan patient , berhasil mengirimkan email';
                        return $res;
                    } else {
                        $res['status'] = false;
                        $res['message'] = 'gagal memberikan rekam medic , terjadi kesalahan';
                        return $res;
                    }
                } else {
                    $res['status'] = false;
                    $res['message'] = 'gagal menambahkan rekam medic , terjadi kesalahan';
                    return $res;
                }
            } else {
                $this->medicalRecords->where('id', $request['medical_record_id'])->delete();
                $res['status'] = false;
                $res['message'] = "gagal menambahka passient";
                return $res;
            }
        } catch (ValidationException $ex) {
            $res['status'] = false;
            $res['message'] = 'terjadi kesalahan server';
            return $res;
        }

    }

    public function findById($id)
    {
        $res = $this->model->where('id', $id)->get()->toArray();
        return $res;
    }
    // return array 
    public function update(array $request, $id): array
    {
        $data = $this->findById($id);
        $allData = $this->model->where('id', '<>', $id)->get();
        $isChanged = Helper::compareToArrays($request, $id, 'pattient');
        if ($isChanged) {
            $response = [];
            foreach ($allData as $key) {
                # code...
                if ($key->email == $request['email']) {
                    // check if value for email same with another acount
                    $response['status'] = false;
                    $response['message'] = 'email sudah digunakan silahkan coba email yang lain';
                    return $response;
                } else if (array_key_exists("nik", $request) && $key->nik == $request['nik']) {
                    $response['status'] = false;
                    $response['message'] = 'nik sudah digunakan silahkan coba nik yang valid';
                    return $response;
                } else if (array_key_exists("no_paspor", $request) && $key->no_paspor == $request['no_paspor']) {
                    $response['status'] = false;
                    $response['message'] = 'no paspor sudah digunakan silahkan coba no paspor yang valid';
                    return $response;
                }
            }
            if ($data == null) {
                $response['status'] = false;
                $response['message'] = 'data tidak ditemukan';
                return $response;
            } else {
                $res = $this->model->where('id', $id)->update($request);
                if ($res) {
                    $response['status'] = true;
                    $response['message'] = 'berhasil memperbarui data pasien';
                    return $response;
                } else {
                    $response['status'] = false;
                    $response['message'] = 'gagal memperbarui data pasien terjadi kesalahan server';
                    return $response;
                }
            }
        } else {
            $response['status'] = false;
            $response['message'] = 'tidak ada perubahan data';
            return $response;
        }
    }

    public function forgotPassword(array $rquest, $id)
    {
        // $res =  $this->model->where('id', $id)->update($rquest);
    }
    public function deleteById($id)
    {
        $res = $this->model->where('id', $id)->delete();
        if ($res) {
            return true;
        }
        return false;
    }

    public function login(array $request)
    {
        $res = Auth::guard('pattient')->attempt(['medical_record_id' => $request['no_medical_records'], 'password' => $request['password']]);

        return $res;
    }

    public function showDataLogin($id)
    {

    }
        public function showRecordDashboard($medicalRecords)
    {
        $res = $this->model
            ->join('medical_records', 'medical_records.medical_record_id', "pattient.medical_record_id")
            ->join('record', 'record.medical_record_id', 'medical_records.medical_record_id')
            ->join('schedule_detail', 'record.id_schedules', 'schedule_detail.id')
            ->select('record.status_consultation as status', 'record.id as id_record', 'record.description', 'schedule_detail.time_start as start_consultation', 'schedule_detail.time_end as end_consultation', 'record.status_consultation as status', 'schedule_detail.consultation_date as schedule', 'record.valid_status')
            ->where('pattient.medical_record_id', $medicalRecords)
            ->where('record.status_consultation', '<>', 'consultation-complete')
            ->where('record.valid_status', '>', Carbon::now())
            ->first();
        if ($res != null) {
            $response = $res->toArray();
            $response['start_consultation'] = strtotime($response['start_consultation']);
            $response['end_consultation'] = strtotime($response['end_consultation']);
            $response['schedule'] = strtotime($response['schedule']);
            $response['valid_status'] = strtotime($res->valid_status);
            $response['id'] = $response['id_record'];
            $response['live_consultation'] = strtotime($res->end_consultation) > time() && $res->status == 'confirmed-consultation-payment' ? true : false;
            unset($response['id_record']);
            return $response;
        } else {
            return [];
        } # code...
    }

    public function showRecordHistory($medicalRecords)
    {
        $res = $this->model->join('medical_records', 'medical_records.medical_record_id', "pattient.medical_record_id")
            ->join('record', 'record.medical_record_id', 'medical_records.medical_record_id')
            ->join('schedule_detail', 'record.id_schedules', 'schedule_detail.id')
            ->select('record.id as id_record', 'record.description', 'schedule_detail.time_start as start_consultation', 'schedule_detail.time_end as end_consultation', 'record.status_consultation as status', 'schedule_detail.consultation_date as schedule')
            ->where('pattient.medical_record_id', $medicalRecords)
            ->where('record.status_consultation', '=', 'consultation-complete')
            ->orwhere('record.valid_status', '<', Carbon::now())
            ->get()->toArray();
        foreach ($res as $key => $value) {
            # code... $response = $res->toArray();
            $res[$key]['start_consultation'] = strtotime($res[$key]['start_consultation']);
            $res[$key]['end_consultation'] = strtotime($res[$key]['end_consultation']);
            $res[$key]['valid_status'] = strtotime($res[$key]['end_consultation']);
            $res[$key]['schedule'] = strtotime($res[$key]['schedule']);
            $res[$key]['id'] = $res[$key]['id_record'];
            unset($res[$key]['id_record']);
        }
        return $res;
    }
    public function showDataSettings()
    {

    }
    public function showDataActionConsultation($idRecord)
    {
        // "id" => $id,
        //     "name_pacient" => "Aristo Caesar Pratama",
        //     "description" => "Saya mengalami mual mual dan merasa selalu lemas setelah beberapa minggu ini hanya dsdsdsdsds makanan mie......",
        //     "category" => "Penyakit Dalam",
        //     "polyclinic" => "POLIKLINIK PENYAKIT DALAM (INTERNA)",
        //     "doctor" => "DR. H. M. Pilox Kamacho H., S.pb",
        //     "schedule" => 1677517200,
        //     "start_consultation" => 1677566329,
        //     "end_consultation" => 1677586329,
        //     "live_consultation" => false,
        //     "status" => "waiting-consultation-payment",

        //     "price_consultation" => "Rp. 90.000",
        //     "status_payment_consultation" => "BELUM TERKONFIRMASI", // PROSES VERIFIKASI / BELUM TERKONFIRMASI / TERKONFIRMASI
        //     "proof_payment_consultation" => "https://i.pinimg.com/236x/68/ed/dc/68eddcea02ceb29abde1b1c752fa29eb.jpg",

        //     "price_medical_prescription" => "Rp. 100.000", // null
        //     "status_payment_medical_prescription" => "TERKONFIRMASI",
        //     "proof_payment_medical_prescription" => "https://tangerangonline.id/wp-content/uploads/2021/06/IMG-20210531-WA0027.jpg",

        //     "pickup_medical_prescription" => "hospital-pharmacy", // hospital-pharmacy, delivery-gojek
        //     "pickup_medical_status" => "MENUNGGU DIAMBIL", // MENUNGGU DIAMBIL, SUDAH DIAMBIL, DIKIRIM DENGAN GOJEK, GAGAL DIKIRIM, TIDAK MENERIMA SEKRANG
        //     "pickup_medical_no_telp_pacient" => "085235119101",
        //     "pickup_medical_addreass_pacient" => "Enim ullamco reprehenderit nulla aliqua reprehenderit",
        //     "pickup_medical_description" => "Alamat yang anda berikan tidak dapat dituju oleh driver GOJEK", // alamat penerima tidak valid, pasien tidak dapat dihubungi
        //     "pickup_by_pacient" => "Aristo Caesar Pratama",
        //     "pickup_datetime" => 1676184847,

        //     "valid_status" => 1677766166
        $res = $this->model
            ->join('medical_records', 'medical_records.medical_record_id', 'pattient.medical_record_id')
            ->join('record', 'medical_records.medical_record_id', 'record.medical_record_id')
            ->join('schedule_detail', 'schedule_detail.id', 'record.id_schedules')
            ->leftjoin('recipes', 'recipes.id', 'record.id_recipe')
            ->join('doctor', 'doctor.id', 'record.id_doctor')
            ->join('polyclinics', 'polyclinics.id', 'doctor.id_polyclinic')
            ->join('record_category', 'record_category.id', 'record.id_category')
            ->select('record.bukti' ,'pattient.phone_number', 'record.id_recipe', 'record.id as id_record', 'pattient.name as name_pacient', 'record.description', 'record_category.category_name', 'polyclinics.name as polyclinic', 'doctor.name as doctor', 'schedule_detail.consultation_date', 'schedule_detail.time_start as start_consultation', 'schedule_detail.time_end as end_consultation', 'record.status_consultation as status', 'record.status_payment_consultation', 'record.valid_status', 'recipes.pickup_medical_prescription', 'recipes.pickup_medical_status', 'recipes.pickup_medical_addreass_pacient', 'recipes.pickup_medical_description', 'recipes.pickup_datetime')->where('record.id', $idRecord)
            ->first();
        $response = [];
        if ($res != null) {
            $response['id'] = $res->id_record;
            $response['name_pacient'] = $res->name_pacient;
            $response['description'] = $res->description;
            $response['category'] = $res->category_name;
            $response['polyclinic'] = $res->polyclinic;
            $response['doctor'] = $res->doctor;
            $response['schedule'] = strtotime($res->consultation_date);
            $response['start_consultation'] = strtotime($res->start_consultation);
            $response['end_consultation'] = strtotime($res->end_consultation);
            $response['status'] = $res->status;
            $response['status_payment_consultation'] = $res->status_payment_consultation;
            $response['pickup_medical_no_telp_pacient'] = $res->phone_number;
            $response['valid_status'] = strtotime($res->valid_status);
            $response['proof_payment_consultation'] = url('/').('/bukti_pembayaran/'.$res->bukti);
            $response['live_consultation'] = true;
            if ($res->id_recipe == null) {
                $response['pickup_medical_prescription'] = null;
                $response['pickup_medical_status'] = null;
                $response['pickup_medical_addreass_pacient'] = null;
                $response['pickup_medical_description'] = null;
                $response['pickup_by_pacient'] = null;
                $response['pickup_datetime'] = null;
                $response['price_medical_prescription'] = null;
                $response['status_payment_medical_prescription'] = NULL;
                $response['proof_payment_medical_prescription'] = null;
            } else {
                $response['pickup_medical_prescription'] = $res->pickup_medical_prescription;
                $response['pickup_medical_status'] = $res->pickup_medical_status;
                $response['pickup_medical_addreass_pacient'] = $res->pickup_medical_addreass_pacient;
                $response['pickup_medical_description'] = $res->pickup_medical_description;
                $response['pickup_by_pacient'] = $res->name_pacient;
                $response['pickup_datetime'] = strtotime($res->pickup_datetime);
                $response['price_medical_prescription'] = $res->recipes->total_price;
                $response['status_payment_medical_prescription'] = "TERKONFIRMASI";
                $response['proof_payment_medical_prescription'] = $res->bukti;
            }
            $response['live_consultation'] = $response['status'] != "confirmed-consultation-payment" || $response['status'] != "consultation-complete" || time() > $response['valid_status'] ? false : true;
            $response['price_consultation'] = "90000";
        }
        return $response;
    }
}
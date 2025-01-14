<x-app-pacient title="Pulihkan Kata Sandi">
    <style>
        .card{width:100%}@media (min-width:991.98px){.card{width:50%}}
    </style>
    <div class="container wrapper-pacient my-5">
        <div class="card shadow-lg rounded-lg mx-auto">
            <div class="card-body">
                <form class="w100 px-5 py-5" action="" method="POST">
                    @csrf
                    <h1 class="font-weight-bold text-bunting text-center text-xl">Atur Ulang Kata Sandi</h1>
                    <p class="text-trouth font-weight-light text-sm mx-auto text-center w-75">Gunakan kata sandi yang rumit seperti gabungan karakter, angka dan simbol</p>
                    <div class="my-5">
                        <input type="text" name="token_recovery" class="d-none" value="{{$token}}">
                        <div class="form-group mb-4">
                            <label for="password1" class="text-trouth">Kata Sandi</label>
                            <input type="password" class="form-control py-4" id="password1" name="password1" aria-describedby="password1" placeholder="Ketikkan password" autofocus required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="password2" class="text-trouth">Konfirmasi Kata Sandi</label>
                            <input type="password" class="form-control py-4" id="password2" name="password2" aria-describedby="password2" placeholder="Ketikkan konfirmasi password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-bunting w-100 text-white font-weight-bold py-2 mb-4">Konfirmasi</button>
                </form>
            </div>
        </div>
    </div>
</x-app-pacient>
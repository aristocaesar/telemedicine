@php

@endphp
@extends('layouts.admin.app')



@section('content-header')
<h1>Admin</h1>
@endsection
@section('content')
{{-- {{dd($data[0])}} --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">DataTable with default features</h3>

    </div>
    <!-- /.card-header -->
    <div class="card-body">
        {{-- {{dd($data)}} --}}
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <button type='button' data-toggle='modal' data-target='#modal-tambah' class='ml-auto col-2 btn btn-block btn-default'>Tambah</button>
                <tr>
                    <th>no</th>
                    <th>nama</th>
                    <th>email</th>
                    <th>alamat</th>
                    <th>aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                $no = 1;
                @endphp
                @foreach($data as $item)
                <tr>
                    <td>{{$no}}</td>
                    <td hidden>{{$item['id']}}</td>
                    <td>{{$item['name']}}</td>
                    <td>{{$item['email']}}</td>
                    <td>{{$item['address']}}</td>
                    <td>
                        <div data-id="{{$item['id']}}" class="row">
                            <div class="col"><button type="button" data-toggle='modal' data-target='#modal-detail' class="col detail btn btn-block btn-primary btn-sm">Detail</button></div>
                            <div class="col"><button type="button" data-toggle='modal' data-target='#modal-delete' class=" col btn btn-block btn-danger btn-sm">Delete</button></div>
                        </div>
                    </td>
                </tr>
                @php
                $no++;
                @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>no</th>
                    <th>nama</th>
                    <th>email</th>
                    <th>alamat</th>
                    <th>aksi</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<x-modal modalid="modal-tambah" judul="Tambah Data Pasien">
    <x-slot:modalid>modal-tambah</x-slot:modalid>
    <x-slot:judul>Tambah Admin</x-slot:judul>
    <form action="admin/store" method="post">
        @csrf
        <div class="form-group">
            <label>Nama</label>
            <input type="text"  class="form-control" name="name" placeholder="Nama">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email" placeholder="Email">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" name="password" placeholder="Nama">
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea class="form-control" name="address" rows="3" placeholder="Alamat ......"></textarea>
        </div>
        <button type="submit">simpan</button>
    </form>
</x-modal>

<x-modal>
    <x-slot:modalid>modal-detail</x-slot:modalid>
    <x-slot:judul>Detail Admin</x-slot:judul>
    <form action="" method="post">
        @csrf
        <div class="form-group">
            <label>Nama</label>
            <input type="text"  class="form-control" name="name" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="text"  class="form-control" name="email" readonly >
        </div>
        <div class="form-group">
            <label for="detail-alamat">Alamat</label>
            <textarea  id="detail-alamat" class="form-control" name="address" rows="3" placeholder="Alamat ......" required></textarea>
        </div>
        <button type="button" class="btn btn-block btn-primary">save</button>
    </form>
</x-modal>



<x-sm-modal>
    <x-slot:id>modal-delete</x-slot:id>
    <x-slot:title>Warning</x-slot:title>
    <h5>apakah anda yakin ingin menghapus data ini?</h5>
    <form action="" method="post">
        <button type="submit">ya</button>
    </form>
</x-sm-modal>
@endsection

@section('after-js')
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0) ');
    });


    function fillName(name) {
        document.querySelector("#detail-nama").value = name;
    }

    //untuk mengset data nama pada tabel ke form modal ketika btn dengan class detail di klik
    const detailButtons = document.querySelectorAll(".detail");

    for (let i = 0; i < detailButtons.length; i++) {
        detailButtons[i].addEventListener("click", function() {
            const name = this.closest("tr").querySelector("td:nth-child(2)").innerHTML;
            console.log(name);
            fillName(name);
        });
    }

</script>

@if(session('message'))
<script>
    console.log('mesage recorded');
    $(function() {
        $(document).ready(function() {
            $(document).Toasts('create', {
                class : 'bg-success'
                ,title: 'success'
                , autohide: true
                , delay: 2000
                , body: '{{ session()->get('message.message') }}'
            })
        });
    });

</script>
@endif
@if($errors->any())
<script>
    console.log('mesage recorded');
    $(function() {
        $(document).ready(function() {
            $(document).Toasts('create', {
                class : 'bg-danger'
                ,title: 'error'
                , autohide: true
                , delay: 2000
                , body: '@foreach ($errors->all() as $error)<li>{{$error}}</li>@endforeach'
            })
        });
    });

</script>
{{-- {{dd($errors)}} --}}
@endif
@endsection
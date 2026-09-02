@extends('layouts.app')
@section('title','Tambah User - POS HADI')
@section('content')
@include('layouts.navbar')
<style>
.user-page{padding-bottom:30px}.user-hero{background:linear-gradient(135deg,#071a2e,#064e3b 58%,#16a34a);color:#fff;border-radius:22px;box-shadow:0 18px 45px rgba(6,78,59,.18)}
</style>
<main class="pos-page user-page"><div class="pos-container">
    <div class="user-hero p-4 p-md-5 mb-4"><div class="d-flex align-items-center gap-3"><div class="bg-white bg-opacity-10 rounded-4 p-3"><i class="bi bi-person-plus-fill fs-3"></i></div><div><div class="small text-white-50">MANAJEMEN USERS</div><h2 class="fw-bold mb-1">Tambah User Baru</h2><p class="mb-0 text-white-50 small">Buat akun baru dan tentukan hak aksesnya.</p></div></div></div>
    <div class="card border-0 shadow-sm rounded-4"><div class="card-body p-4 p-md-5"><form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">@include('users._form')</form></div></div>
</div></main>
@endsection

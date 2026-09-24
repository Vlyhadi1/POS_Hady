@extends('layouts.app')

@section('title', 'Terlalu Banyak Percobaan')

@section('content')
<main class="container py-5 text-center">
    <div class="card border-0 shadow-sm mx-auto p-5" style="max-width:560px">
        <i class="bi bi-hourglass-split text-warning" style="font-size:3rem"></i>
        <h1 class="h3 mt-3">Terlalu banyak percobaan</h1>
        <p class="text-muted">Silakan tunggu sekitar 1 menit sebelum mencoba login kembali.</p>
        <a href="{{ route('login') }}" class="btn btn-success rounded-pill">Kembali ke Login</a>
    </div>
</main>
@endsection
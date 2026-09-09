<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'POS HADI')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('images/pos-ind-logistik.jpg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    @if(session('success'))
        <div id="globalAlert" class="alert alert-success global-alert position-fixed d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" onclick="this.closest('.alert').remove()"></button>
        </div>
    @endif

    @if(session('error'))
        <div id="globalAlert" class="alert alert-danger global-alert position-fixed d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close ms-auto" onclick="this.closest('.alert').remove()"></button>
        </div>
    @endif

    @if($errors->any())
        <div id="validationAlert" class="alert alert-danger global-alert position-fixed">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                <div>
                    <strong>Periksa data:</strong>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close ms-auto" onclick="this.closest('.alert').remove()"></button>
            </div>
        </div>
    @endif

    @yield('content')
    @stack('scripts')
</body>
</html>

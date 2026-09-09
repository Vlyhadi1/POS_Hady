<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - POS IND</title>
    <link rel="icon" type="image/png" href="{{ asset('images/pos-ind-logistik.jpg') }}?v=3">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}body{min-height:100vh;font-family:Inter,Segoe UI,sans-serif;background:#f4f7fb;color:#18243b;display:flex;align-items:center;justify-content:center;padding:24px}.card{width:100%;max-width:470px;background:#fff;border:1px solid #e5eaf2;border-radius:24px;padding:40px;box-shadow:0 25px 70px rgba(27,48,88,.11)}.logo{width:78px;height:78px;border-radius:20px;overflow:hidden;margin:0 auto 20px;box-shadow:0 10px 25px rgba(31,53,107,.15)}.logo img{width:100%;height:100%;object-fit:cover}.title{text-align:center}.title h1{font-size:27px;margin-bottom:8px}.title h1 span{color:#f0442e}.title p{font-size:13px;color:#718096;line-height:1.6;margin-bottom:26px}.alert{padding:12px 14px;border-radius:11px;font-size:12px;margin-bottom:18px;line-height:1.5}.success{background:#edf9f3;border:1px solid #c9edd9;color:#16804c}.danger{background:#fff1ef;border:1px solid #ffd7d2;color:#c73526}.label{display:block;font-size:12px;font-weight:700;margin-bottom:8px;color:#34415a}.input{width:100%;height:52px;border:1.5px solid #dfe5ee;border-radius:12px;padding:0 15px;font-family:inherit;outline:none}.input:focus{border-color:#1f356b;box-shadow:0 0 0 4px rgba(31,53,107,.09)}.btn{width:100%;height:52px;margin-top:17px;border:0;border-radius:12px;background:#1f356b;color:#fff;font-weight:700;cursor:pointer}.back{display:block;text-align:center;margin-top:18px;color:#1f356b;text-decoration:none;font-size:12px;font-weight:600}.note{margin-top:22px;padding:14px;border-radius:12px;background:#f7f9fc;color:#718096;font-size:11px;line-height:1.6}
    </style>
</head>
<body>
<div class="card">
    <div class="logo"><img src="{{ asset('images/pos-ind-logistik.jpg') }}" alt="POS IND Logistik Indonesia"></div>
    <div class="title"><h1>Lupa <span>Kata Sandi?</span></h1><p>Masukkan email akun POS IND. Kami akan mengirimkan link untuk membuat password baru.</p></div>
    @if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert danger">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('password.forgot.submit') }}">
        @csrf
        <label class="label" for="email">Alamat Email</label>
        <input class="input" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus autocomplete="email">
        <button class="btn" type="submit">Kirim Link Reset</button>
    </form>
    <div class="note"><strong>Catatan:</strong> link reset berlaku selama 60 menit. Jika email belum masuk, periksa folder spam.</div>
    <a class="back" href="{{ route('login') }}">← Kembali ke halaman login</a>
</div>
</body>
</html>

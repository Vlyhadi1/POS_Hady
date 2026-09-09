<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - POS IND Logistik Indonesia</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        :root{--navy:#1f356b;--navy-dark:#172a59;--red:#f0442e;--green:#1fa463;--text:#18243b;--muted:#718096}
        body{font-family:'Inter',sans-serif;min-height:100vh;background:#f4f7fb;color:var(--text)}
        .login-wrapper{min-height:100vh;display:flex;overflow:hidden}

        /* LEFT - POS IND BRAND */
        .login-brand{width:55%;min-height:100vh;position:relative;overflow:hidden;background:linear-gradient(145deg,var(--navy-dark),var(--navy));color:#fff;display:flex;align-items:center;padding:70px 8%;}
        .login-brand::before,.login-brand::after{content:"";position:absolute;border:1px solid rgba(255,255,255,.08);border-radius:50%}
        .login-brand::before{width:620px;height:620px;right:-250px;top:-210px}
        .login-brand::after{width:440px;height:440px;left:-250px;bottom:-210px}
        .brand-grid{position:absolute;inset:0;opacity:.07;background-image:linear-gradient(rgba(255,255,255,.8) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.8) 1px,transparent 1px);background-size:42px 42px;mask-image:linear-gradient(to bottom right,black,transparent 70%)}
        .brand-content{position:relative;z-index:2;max-width:650px}
        .logo-box{width:178px;height:178px;border-radius:28px;overflow:hidden;background:#1f356b;box-shadow:0 25px 60px rgba(0,0,0,.28);margin-bottom:32px}
        .logo-box img{width:100%;height:100%;object-fit:cover;display:block}
        .eyebrow{display:inline-flex;align-items:center;gap:9px;font-size:12px;font-weight:700;letter-spacing:1.4px;text-transform:uppercase;color:#dce6ff;margin-bottom:15px}
        .eyebrow i{color:#ff5945}
        .brand-content h1{font-size:42px;line-height:1.08;font-weight:800;letter-spacing:-1.5px;margin-bottom:18px}
        .brand-content h1 span{color:#ff5a45}
        .brand-content p{font-size:15px;line-height:1.75;color:rgba(255,255,255,.76);max-width:520px}
        .feature-row{display:flex;gap:12px;flex-wrap:wrap;margin-top:30px}
        .feature{display:flex;align-items:center;gap:9px;padding:11px 15px;border-radius:12px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.1);font-size:12px;color:#eef3ff;backdrop-filter:blur(8px)}
        .feature i{font-size:15px;color:#ff6855}
        .brand-bottom{position:absolute;z-index:2;left:8%;bottom:30px;font-size:11px;color:rgba(255,255,255,.48)}

        /* RIGHT - LOGIN */
        .login-side{width:45%;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:40px;background:#f6f8fb}
        .login-card{width:100%;max-width:450px;background:#fff;border:1px solid #e8edf4;border-radius:24px;padding:40px;box-shadow:0 25px 70px rgba(27,48,88,.10)}
        .login-logo{width:64px;height:64px;margin:0 auto 20px;border-radius:18px;overflow:hidden;box-shadow:0 10px 24px rgba(31,53,107,.16)}
        .login-logo img{width:100%;height:100%;object-fit:cover;display:block}
        .login-title{text-align:center}
        .login-title h2{font-size:28px;font-weight:800;color:#18243b;margin-bottom:8px}
        .login-title h2 span{color:var(--red)}
        .login-title p{font-size:13px;color:var(--muted);margin-bottom:28px}
        .alert{padding:12px 14px;border-radius:11px;margin-bottom:18px;font-size:12px;line-height:1.5}.alert-danger{background:#fff1ef;color:#c73526;border:1px solid #ffd7d2}.alert-success{background:#edf9f3;color:#16804c;border:1px solid #c9edd9}
        .form-group{margin-bottom:18px}.form-label{display:block;font-size:12px;font-weight:700;color:#34415a;margin-bottom:8px}.input-wrapper{position:relative}.input-icon{position:absolute;left:16px;top:50%;transform:translateY(-50%);color:#8b96a8;font-size:17px;pointer-events:none}.form-control{width:100%;height:53px;border:1.5px solid #dfe5ee;border-radius:12px;padding:0 47px;font-size:13px;color:#27344b;background:#fff;outline:none;transition:.2s;font-family:inherit}.form-control:focus{border-color:var(--navy);box-shadow:0 0 0 4px rgba(31,53,107,.09)}.password-toggle{position:absolute;right:15px;top:50%;transform:translateY(-50%);border:0;background:transparent;color:#8b96a8;cursor:pointer;font-size:17px}
        .login-options{display:flex;align-items:center;justify-content:space-between;margin:4px 0 21px}.remember{display:flex;align-items:center;gap:7px;font-size:12px;color:#68758a;cursor:pointer}.remember input{width:15px;height:15px;accent-color:var(--navy)}.forgot{font-size:12px;color:var(--green);font-weight:600;text-decoration:none}.forgot:hover{text-decoration:underline;color:#16804c}
        .btn-login{width:100%;height:53px;border:0;border-radius:12px;background:var(--navy);color:#fff;font-size:14px;font-weight:700;font-family:inherit;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:9px;box-shadow:0 10px 22px rgba(31,53,107,.18);transition:.2s}.btn-login:hover{background:#294684;transform:translateY(-1px);box-shadow:0 13px 28px rgba(31,53,107,.23)}
        .divider{display:flex;align-items:center;gap:12px;margin:22px 0;color:#9aa5b5;font-size:11px}.divider::before,.divider::after{content:"";height:1px;flex:1;background:#e7ebf1}

        .footer-text{text-align:center;margin-top:25px;font-size:10px;color:#9aa5b5}.footer-text span{color:var(--navy);font-weight:700}

        @media(max-width:900px){.login-brand{width:50%;padding:55px 6%}.login-side{width:50%;padding:25px}.logo-box{width:145px;height:145px}.brand-content h1{font-size:34px}}
        @media(max-width:720px){.login-wrapper{display:block}.login-brand{width:100%;min-height:390px;height:390px;padding:45px 25px;align-items:flex-start}.brand-content{max-width:100%}.logo-box{width:105px;height:105px;border-radius:19px;margin-bottom:20px}.brand-content h1{font-size:29px;margin-bottom:10px}.brand-content p{font-size:12px;line-height:1.6}.feature-row{margin-top:18px;gap:7px}.feature{padding:8px 10px;font-size:10px}.brand-bottom{left:25px;bottom:17px}.login-side{width:100%;min-height:calc(100vh - 390px);padding:24px 17px}.login-card{padding:29px 22px;border-radius:20px}}
        @media(max-width:420px){.login-brand{min-height:340px;height:340px}.brand-content h1{font-size:25px}.brand-content p{display:none}.feature-row{display:none}.login-side{min-height:calc(100vh - 340px)}.login-card{padding:25px 19px}.login-title h2{font-size:25px}}
    </style>
</head>
<body>
<div class="login-wrapper">
    <section class="login-brand">
        <div class="brand-grid"></div>
        <div class="brand-content">
            <div class="logo-box">
                <img src="{{ asset('images/pos-ind-logistik.jpg') }}" alt="POS IND Logistik Indonesia">
            </div>
            <div class="eyebrow"><i class="bi bi-box-seam-fill"></i> Sistem Informasi Penjualan & Logistik</div>
            <h1>Kelola penjualan & stok <span>lebih mudah.</span></h1>
            <p>POS IND membantu mengelola produk, transaksi, persediaan, dan laporan bisnis secara praktis dalam satu sistem.</p>
            <div class="feature-row">
                <div class="feature"><i class="bi bi-receipt"></i> Transaksi</div>
                <div class="feature"><i class="bi bi-boxes"></i> Stok</div>
                <div class="feature"><i class="bi bi-bar-chart-line"></i> Laporan</div>
            </div>
        </div>
        <div class="brand-bottom">POS IND • Logistik Indonesia</div>
    </section>

    <section class="login-side">
        <div class="login-card">
            <div class="login-logo">
                <img src="{{ asset('images/pos-ind-logistik.jpg') }}" alt="POS IND">
            </div>
            <div class="login-title">
                <h2>Selamat <span>Datang!</span></h2>
                <p>Masuk untuk mengakses sistem POS IND</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> {{ $errors->first() }}</div>
            @endif
            @if (session('success'))
                <div class="alert alert-success"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('auth') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Alamat Email</label>
                    <div class="input-wrapper">
                        <i class="bi bi-envelope input-icon"></i>
                        <input type="email" name="email" class="form-control" placeholder="Masukkan email Anda" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Kata Sandi</label>
                    <div class="input-wrapper">
                        <i class="bi bi-lock input-icon"></i>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan kata sandi Anda" required>
                        <button type="button" class="password-toggle" onclick="togglePassword()"><i class="bi bi-eye" id="eyeIcon"></i></button>
                    </div>
                </div>
                <div class="login-options">
                    <label class="remember"><input type="checkbox" name="remember"><span>Ingat saya</span></label>
                    <a href="{{ route('password.forgot') }}" class="forgot">Lupa kata sandi?</a>
                </div>
                <button type="submit" class="btn-login"><span>Masuk ke Sistem</span><i class="bi bi-arrow-right"></i></button>
            </form>

            <div class="footer-text">© {{ date('Y') }} <span>POS IND</span> · Logistik Indonesia</div>
        </div>
    </section>
</div>
<script>
function togglePassword(){const password=document.getElementById('password'),eyeIcon=document.getElementById('eyeIcon');if(password.type==='password'){password.type='text';eyeIcon.classList.replace('bi-eye','bi-eye-slash')}else{password.type='password';eyeIcon.classList.replace('bi-eye-slash','bi-eye')}}
</script>
</body>
</html>

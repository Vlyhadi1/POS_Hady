<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - POS System</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #eef5f0;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            overflow: hidden;
        }

        /* =========================
           LEFT SIDE
        ========================= */

        .login-image {
            width: 52%;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            background: #123c26;
        }

        .login-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .image-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(
                    135deg,
                    rgba(4, 35, 20, .82),
                    rgba(15, 92, 48, .35),
                    rgba(0, 0, 0, .15)
                );
        }

        .brand {
            position: absolute;
            top: 45px;
            left: 50px;
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
            z-index: 2;
        }

        .brand-icon {
            width: 55px;
            height: 55px;
            border-radius: 17px;
            background: #20a45a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 27px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .25);
        }

        .brand-text h1 {
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .brand-text span {
            font-size: 12px;
            opacity: .85;
        }

        .image-content {
            position: absolute;
            left: 50px;
            bottom: 55px;
            color: white;
            z-index: 2;
            max-width: 500px;
        }

        .image-content h2 {
            font-size: 40px;
            line-height: 1.15;
            margin-bottom: 15px;
            font-weight: 800;
        }

        .image-content p {
            font-size: 16px;
            line-height: 1.7;
            color: rgba(255, 255, 255, .85);
            max-width: 450px;
        }

        .features {
            margin-top: 28px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .feature {
            padding: 10px 15px;
            border-radius: 50px;
            background: rgba(255, 255, 255, .12);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, .15);
            font-size: 12px;
        }

        /* =========================
           RIGHT SIDE
        ========================= */

        .login-side {
            width: 48%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(42, 179, 100, .18),
                    transparent 40%
                ),
                linear-gradient(
                    135deg,
                    #f7fbf8,
                    #e9f4ed
                );
        }

        .login-card {
            width: 100%;
            max-width: 470px;
            background: rgba(255, 255, 255, .96);
            border-radius: 28px;
            padding: 42px;
            box-shadow:
                0 30px 80px rgba(18, 75, 43, .15);
            border: 1px solid rgba(255, 255, 255, .8);
        }

        .login-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 22px;
            border-radius: 22px;
            background: #eaf8ef;
            color: #168b4b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 31px;
        }

        .login-title {
            text-align: center;
        }

        .login-title h2 {
            font-size: 29px;
            font-weight: 800;
            color: #17251d;
            margin-bottom: 8px;
        }

        .login-title h2 span {
            color: #199653;
        }

        .login-title p {
            color: #718078;
            font-size: 14px;
            margin-bottom: 32px;
        }

        /* Alert */

        .alert {
            padding: 13px 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .alert-danger {
            background: #fff0f0;
            color: #c0392b;
            border: 1px solid #ffd5d5;
        }

        .alert-success {
            background: #edfaf2;
            color: #18854a;
            border: 1px solid #c9efd8;
        }

        /* Input */

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #34433a;
            margin-bottom: 9px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 17px;
            top: 50%;
            transform: translateY(-50%);
            color: #8b9990;
            font-size: 18px;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            height: 54px;
            border: 1.5px solid #dce5df;
            border-radius: 13px;
            padding: 0 48px;
            font-size: 14px;
            color: #27352d;
            background: #fff;
            outline: none;
            transition: .25s;
            font-family: inherit;
        }

        .form-control::placeholder {
            color: #a7b0aa;
        }

        .form-control:focus {
            border-color: #199653;
            box-shadow: 0 0 0 4px rgba(25, 150, 83, .10);
        }

        .password-toggle {
            position: absolute;
            right: 17px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #8b9990;
            cursor: pointer;
            font-size: 18px;
        }

        /* Options */

        .login-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 5px 0 23px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #647169;
            cursor: pointer;
        }

        .remember input {
            width: 16px;
            height: 16px;
            accent-color: #199653;
            cursor: pointer;
        }

        .forgot {
            color: #199653;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }

        .forgot:hover {
            text-decoration: underline;
        }

        /* Button */

        .btn-login {
            width: 100%;
            height: 55px;
            border: none;
            border-radius: 13px;
            background: linear-gradient(
                135deg,
                #138449,
                #31ad68
            );
            color: white;
            font-size: 15px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 10px 25px rgba(25, 150, 83, .20);
            transition: .25s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(25, 150, 83, .28);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Divider */

        .divider {
            display: flex;
            align-items: center;
            gap: 13px;
            margin: 25px 0;
            color: #a1aaa4;
            font-size: 12px;
        }

        .divider::before,
        .divider::after {
            content: "";
            height: 1px;
            flex: 1;
            background: #e5eae7;
        }

        /* Demo */

        .btn-demo {
            width: 100%;
            height: 50px;
            border: 1.5px solid #dce5df;
            border-radius: 12px;
            background: white;
            color: #34433a;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            transition: .25s;
            font-family: inherit;
        }

        .btn-demo:hover {
            border-color: #199653;
            color: #199653;
            background: #f7fcf9;
        }

        .footer-text {
            text-align: center;
            margin-top: 28px;
            font-size: 11px;
            color: #98a29c;
        }

        .footer-text span {
            color: #199653;
            font-weight: 600;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1000px) {
            .login-image {
                width: 45%;
            }

            .login-side {
                width: 55%;
                padding: 25px;
            }

            .image-content h2 {
                font-size: 32px;
            }

            .brand {
                left: 30px;
            }

            .image-content {
                left: 30px;
                right: 25px;
            }
        }

        @media (max-width: 768px) {
            .login-wrapper {
                display: block;
            }

            .login-image {
                width: 100%;
                height: 260px;
                min-height: 260px;
            }

            .login-side {
                width: 100%;
                min-height: calc(100vh - 260px);
                padding: 25px 18px;
            }

            .brand {
                top: 25px;
                left: 25px;
            }

            .brand-icon {
                width: 45px;
                height: 45px;
                font-size: 21px;
                border-radius: 13px;
            }

            .brand-text h1 {
                font-size: 20px;
            }

            .image-content {
                left: 25px;
                bottom: 25px;
            }

            .image-content h2 {
                font-size: 24px;
                margin-bottom: 6px;
            }

            .image-content p {
                display: none;
            }

            .features {
                display: none;
            }

            .login-card {
                padding: 30px 23px;
                border-radius: 22px;
            }
        }

        @media (max-width: 430px) {
            .login-card {
                padding: 27px 20px;
            }

            .login-title h2 {
                font-size: 25px;
            }

            .login-options {
                gap: 10px;
            }

            .forgot {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <!-- =========================
         FOTO / BRAND
    ========================== -->

    <section class="login-image">

        <!--
            Simpan foto kamu di:
            public/images/pos-login.jpg
        -->
        <img
            src="{{ asset('images/pos-login.jpg') }}"
            alt="POS System"
        >

        <div class="image-overlay"></div>

        <!-- Brand -->
        <div class="brand">
            <div class="brand-icon">
                <i class="bi bi-cart3"></i>
            </div>

            <div class="brand-text">
                <h1>POS SYSTEM</h1>
                <span>Point of Sale Management</span>
            </div>
        </div>

        <!-- Text -->
        <div class="image-content">

            <h2>
                Kelola transaksi
                <br>
                lebih mudah.
            </h2>

            <p>
                Sistem POS modern untuk membantu mengelola
                produk, transaksi, stok, dan laporan bisnis
                dalam satu tempat.
            </p>

            <div class="features">
                <div class="feature">
                    <i class="bi bi-shield-check"></i>
                    Aman
                </div>

                <div class="feature">
                    <i class="bi bi-lightning-charge"></i>
                    Cepat
                </div>

                <div class="feature">
                    <i class="bi bi-bar-chart"></i>
                    Terintegrasi
                </div>
            </div>

        </div>

    </section>


    <!-- =========================
         LOGIN
    ========================== -->

    <section class="login-side">

        <div class="login-card">

            <div class="login-icon">
                <i class="bi bi-shop"></i>
            </div>

            <div class="login-title">
                <h2>
                    Selamat <span>Datang!</span>
                </h2>

                <p>
                    Silakan login untuk masuk ke sistem POS
                </p>
            </div>


            {{-- Error --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif


            {{-- Success --}}
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif


            <!-- FORM LOGIN -->

            <form method="POST" action="/auth">

                @csrf

                <!-- Email -->
                <div class="form-group">

                    <label class="form-label">
                        Alamat Email
                    </label>

                    <div class="input-wrapper">

                        <i class="bi bi-envelope input-icon"></i>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="Masukkan email Anda"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >

                    </div>

                </div>


                <!-- Password -->

                <div class="form-group">

                    <label class="form-label">
                        Kata Sandi
                    </label>

                    <div class="input-wrapper">

                        <i class="bi bi-lock input-icon"></i>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            placeholder="Masukkan kata sandi Anda"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            onclick="togglePassword()"
                        >
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>

                    </div>

                </div>


                <!-- Options -->

                <div class="login-options">

                    <label class="remember">

                        <input
                            type="checkbox"
                            name="remember"
                        >

                        <span>Ingat saya</span>

                    </label>

                    <a href="#" class="forgot">
                        Lupa kata sandi?
                    </a>

                </div>


                <!-- Login Button -->

                <button
                    type="submit"
                    class="btn-login"
                >

                    <span>Masuk</span>

                    <i class="bi bi-box-arrow-in-right"></i>

                </button>

            </form>


            <!-- Divider -->

            <div class="divider">
                atau
            </div>


            <!-- Demo Login -->

            <button
                type="button"
                class="btn-demo"
                onclick="demoLogin()"
            >

                <i class="bi bi-person"></i>

                Login sebagai Admin Demo

            </button>


            <!-- Footer -->

            <div class="footer-text">

                © {{ date('Y') }}

                <span>POS System</span>.

                All rights reserved.

            </div>

        </div>

    </section>

</div>


<script>

    // ==========================
    // SHOW / HIDE PASSWORD
    // ==========================

    function togglePassword() {

        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (password.type === 'password') {

            password.type = 'text';

            eyeIcon.classList.remove('bi-eye');
            eyeIcon.classList.add('bi-eye-slash');

        } else {

            password.type = 'password';

            eyeIcon.classList.remove('bi-eye-slash');
            eyeIcon.classList.add('bi-eye');

        }

    }


    // ==========================
    // DEMO LOGIN
    // ==========================

    function demoLogin() {

        document.querySelector('input[name="email"]').value =
            'admin@gmail.com';

        document.querySelector('input[name="password"]').value =
            'password';

    }

</script>

</body>
</html>
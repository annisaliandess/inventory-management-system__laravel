<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke Sistem - Inventori Toko Hijab</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bg-body: #fff1f2;
            --primary: #be185d;
            --primary-hover: #9d174d;
            --border-ui: #fbcfe8;
        }

        * {
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #fff1f2 0%, #fce7f3 50%, #fbcfe8 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(190, 24, 93, 0.12), 0 5px 15px rgba(0, 0, 0, 0.04);
            border: 2px solid var(--border-ui);
            width: 100%;
            max-width: 440px;
            padding: 40px 32px;
        }

        .brand-icon {
            width: 64px;
            height: 64px;
            background: #fce7f3;
            color: var(--primary);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 16px auto;
        }

        .form-control {
            border-radius: 10px;
            border: 1.5px solid #fbcfe8;
            padding: 11px 14px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(190, 24, 93, 0.15);
        }

        .btn-login {
            background-color: var(--primary);
            color: #ffffff;
            font-weight: 700;
            border: none;
            box-shadow: 0 4px 14px rgba(190, 24, 93, 0.3);
            border-radius: 10px;
            padding: 12px;
            font-size: 14.5px;
            width: 100%;
            transition: all 0.2s;
        }

        .btn-login:hover {
            background-color: var(--primary-hover);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(190, 24, 93, 0.4);
        }

        .demo-box {
            background: #fff8f9;
            border: 1.5px dashed #fbcfe8;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 12.5px;
            color: #5e4d51;
            margin-top: 24px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-icon">
            🌸
        </div>
        <div class="text-center mb-4">
            <h4 class="fw-bold mb-1" style="color: var(--primary);">Inventori Toko Hijab</h4>
            <p class="text-muted small">Silakan masuk untuk mengakses sistem inventori</p>
        </div>

        @if(session('info'))
            <div class="alert alert-info py-2 px-3 small rounded-3 mb-3">
                <i class="bi bi-info-circle me-1"></i> {{ session('info') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3">
                <i class="bi bi-exclamation-triangle me-1"></i>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" autocomplete="off">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold small text-secondary">Alamat Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-color: #fbcfe8; border-radius: 10px 0 0 10px;">
                        <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input type="text" name="email" value="{{ old('email', 'admin@tokohijab') }}" 
                           class="form-control border-start-0 @error('email') is-invalid @enderror" 
                           placeholder="admin@tokohijab" style="border-radius: 0 10px 10px 0;" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold small text-secondary">Kata Sandi</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-color: #fbcfe8; border-radius: 10px 0 0 10px;">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input type="password" name="password" id="password"
                           class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror" 
                           placeholder="••••••••" required>
                    <button type="button" class="btn bg-white border border-start-0 text-muted" id="togglePassword" 
                            style="border-color: #fbcfe8 !important; border-radius: 0 10px 10px 0;" title="Tampilkan Kata Sandi">
                        <i class="bi bi-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check small">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label text-muted" for="remember">
                        Ingat Saya
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-login">
                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
            </button>
        </form>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (togglePassword && passwordInput && toggleIcon) {
            togglePassword.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                toggleIcon.classList.toggle('bi-eye', !isPassword);
                toggleIcon.classList.toggle('bi-eye-slash', isPassword);
            });
        }
    </script>
</body>
</html>

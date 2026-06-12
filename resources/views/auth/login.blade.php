<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Jimpitan RT</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .login-card {
            border: none;
            border-radius: 20px;
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        }
        .input-group-text {
            background-color: rgba(15, 23, 42, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #94a3b8;
        }
        .form-control {
            background-color: rgba(15, 23, 42, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #f8fafc !important;
        }
        .form-control:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 0.25rem rgba(245, 158, 11, 0.25);
        }
        .btn-primary {
            background-color: #f59e0b;
            border: none;
            color: #0f172a;
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: #d97706;
            color: #0f172a;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card login-card p-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <span class="bg-warning text-dark px-3 py-3 rounded-circle fs-2 mb-3 d-inline-block shadow-sm">
                            <i class="bi bi-shield-lock-fill"></i>
                        </span>
                        <h4 class="fw-bold text-white mt-2">Superuser Login</h4>
                        <p class="text-white-50 small">Masukkan sandi khusus untuk mengelola data warga dan jadwal.</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger text-center py-2" role="alert" style="background-color: rgba(239, 68, 68, 0.2); border-color: rgba(239, 68, 68, 0.3); color: #fca5a5;">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first('password') }}
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="alert alert-danger text-center py-2" role="alert" style="background-color: rgba(239, 68, 68, 0.2); border-color: rgba(239, 68, 68, 0.3); color: #fca5a5;">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label text-white-50 small fw-bold">Kata Sandi Superuser</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" required autofocus>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm">
                            Masuk <i class="bi bi-box-arrow-in-right ms-1"></i>
                        </button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('home') }}" class="text-warning text-decoration-none small">
                            <i class="bi bi-arrow-left"></i> Kembali ke Jadwal Umum
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

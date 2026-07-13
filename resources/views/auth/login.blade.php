<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Sukan BTMKN</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: 'Manrope', sans-serif;
            background: radial-gradient(circle at top, rgba(249, 115, 22, 0.2), transparent 34%), linear-gradient(180deg, #f8fafc, #eef2ff);
        }
        .login-card { width: min(100%, 420px); }
    </style>
</head>
<body>
    <div class="login-card card border-0 shadow-lg rounded-4">
        <div class="card-body p-4 p-lg-5">
            <div class="mb-4">
                <div class="text-uppercase text-secondary small fw-bold">Akses admin dalaman</div>
                <h1 class="h3 fw-bold mb-0">Log masuk ke Sukan BTMKN</h1>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ route('login.store') }}" class="vstack gap-3">
                @csrf
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg" required autofocus>
                </div>
                <div>
                    <label class="form-label">Kata Laluan</label>
                    <input type="password" name="password" class="form-control form-control-lg" required>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button type="submit" class="btn btn-dark btn-lg w-100">Log Masuk</button>
                <a href="{{ route('live.index') }}" class="btn btn-outline-primary btn-lg w-100">Lihat Keputusan Langsung</a>
                <div class="text-secondary small">Admin benih lalai: admin@sukanbtmkn.test / password</div>
            </form>
        </div>
    </div>
</body>
</html>

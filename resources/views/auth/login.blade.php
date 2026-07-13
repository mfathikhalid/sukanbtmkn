<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log Masuk Admin | Sukan BTMKN</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; font-family: 'Manrope', sans-serif; background: #f8fafc; color: #0f172a; }
        .login-shell { min-height: 100vh; }
        .carnival-panel { position: relative; overflow: hidden; background: radial-gradient(circle at 82% 16%, rgba(250,204,21,.38), transparent 18%), radial-gradient(circle at 10% 90%, rgba(249,115,22,.42), transparent 25%), linear-gradient(125deg, #020617, #1e3a8a 58%, #2563eb); }
        .carnival-panel::after { content: ''; position: absolute; inset: auto 0 0; height: .65rem; background: repeating-linear-gradient(90deg, #f97316 0 12.5%, #facc15 12.5% 25%, #22c55e 25% 37.5%, #38bdf8 37.5% 50%, #f97316 50% 62.5%, #facc15 62.5% 75%, #22c55e 75% 87.5%, #38bdf8 87.5% 100%); }
        .arena-grid { position: absolute; inset: 0; opacity: .08; background-image: linear-gradient(rgba(255,255,255,.7) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.7) 1px, transparent 1px); background-size: 44px 44px; transform: perspective(450px) rotateX(58deg) scale(1.5); transform-origin: bottom; }
        .sport-ring { position: absolute; width: 23rem; height: 23rem; right: -10rem; bottom: -9rem; border: 4.5rem solid rgba(255,255,255,.055); border-radius: 50%; }
        .confetti { position: absolute; width: .7rem; height: .7rem; border-radius: .15rem; transform: rotate(28deg); }
        .public-link { color: rgba(255,255,255,.8); background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.16); transition: background .2s ease, color .2s ease; }
        .public-link:hover { color: #fff; background: rgba(255,255,255,.16); }
        .form-panel { background: radial-gradient(circle at 90% 8%, rgba(37,99,235,.08), transparent 26%), #f8fafc; }
        .login-card { width: min(100%, 500px); }
        .admin-mark { width: 3.4rem; height: 3.4rem; background: linear-gradient(145deg, #facc15, #f97316); box-shadow: 0 .55rem 1.25rem rgba(249,115,22,.24); }
        .form-control { min-height: 3.25rem; border-color: #cbd5e1; }
        .form-control:focus { border-color: #2563eb; box-shadow: 0 0 0 .25rem rgba(37,99,235,.12); }
        .login-button { min-height: 3.25rem; border: 0; background: linear-gradient(135deg, #172554, #2563eb); box-shadow: 0 .65rem 1.3rem rgba(37,99,235,.2); }
        .login-button:hover { background: linear-gradient(135deg, #0f172a, #1d4ed8); }
        @media (max-width: 991.98px) { .carnival-panel { min-height: auto; } }
    </style>
</head>
<body>
    <main class="login-shell row g-0" data-login-theme="carnival">
        <section class="carnival-panel col-lg-6 text-white d-flex align-items-center p-4 p-md-5">
            <div class="arena-grid"></div>
            <div class="sport-ring"></div>
            <span class="confetti bg-warning" style="top: 14%; left: 9%;"></span>
            <span class="confetti bg-info" style="top: 24%; right: 9%; transform: rotate(58deg);"></span>
            <span class="confetti bg-success" style="bottom: 24%; left: 18%;"></span>

            <div class="position-relative mx-auto py-4" style="max-width: 620px;">
                <a href="{{ route('home') }}" class="text-white text-decoration-none fw-bold fs-5 d-inline-flex align-items-center gap-2 mb-5">
                    <span>🏆</span> Karnival Sukan BTMKN
                </a>
                <div class="d-inline-flex bg-white bg-opacity-10 border border-white border-opacity-25 rounded-pill px-3 py-2 fw-bold text-uppercase small mb-4" style="letter-spacing: .12em;">Pusat Kawalan 2026</div>
                <h1 class="display-4 fw-bold lh-1 mb-4">Urus kejohanan.<br><span class="text-warning">Pantau setiap aksi.</span></h1>
                <p class="fs-5 text-white-50 mb-5">Akses khas pentadbir untuk mengurus peserta, perlawanan, keputusan dan kedudukan rumah.</p>

                <div class="row g-2">
                    @foreach ([
                        ['label' => 'Daftar Acara', 'route' => 'public-registration.create', 'icon' => '✍️'],
                        ['label' => 'Senarai Peserta', 'route' => 'public-participants.index', 'icon' => '👥'],
                        ['label' => 'Keputusan Live', 'route' => 'live.index', 'icon' => '⚡'],
                        ['label' => 'Jadual', 'route' => 'schedule.index', 'icon' => '📅'],
                    ] as $link)
                        <div class="col-sm-6">
                            <a href="{{ route($link['route']) }}" class="public-link d-flex align-items-center gap-2 rounded-3 p-3 text-decoration-none"><span>{{ $link['icon'] }}</span><span class="fw-semibold">{{ $link['label'] }}</span></a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="form-panel col-lg-6 d-flex align-items-center justify-content-center p-4 p-md-5">
            <div class="login-card py-4">
                <div class="admin-mark rounded-4 d-inline-flex align-items-center justify-content-center fs-4 mb-4">🔐</div>
                <div class="text-primary fw-bold text-uppercase small mb-2" style="letter-spacing: .12em;">Akses Pentadbir</div>
                <h2 class="display-6 fw-bold mb-2">Selamat kembali</h2>
                <p class="text-secondary mb-4">Log masuk untuk meneruskan ke Dashboard Sukan BTMKN.</p>

                @if ($errors->any())
                    <div class="alert alert-danger rounded-4">
                        <div class="fw-bold mb-1">Log masuk tidak berjaya</div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form method="post" action="{{ route('login.store') }}" class="vstack gap-3">
                    @csrf
                    <div>
                        <label class="form-label fw-semibold" for="email">E-mel</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg rounded-3" autocomplete="email" required autofocus>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-center"><label class="form-label fw-semibold" for="password">Kata laluan</label></div>
                        <div class="input-group">
                            <input id="password" type="password" name="password" class="form-control form-control-lg rounded-start-3" autocomplete="current-password" required>
                            <button id="toggle-password" type="button" class="btn btn-outline-secondary px-3" aria-label="Papar kata laluan">Papar</button>
                        </div>
                    </div>
                    <div class="form-check my-1">
                        <input class="form-check-input" type="checkbox" value="1" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Ingat saya pada peranti ini</label>
                    </div>
                    <button type="submit" class="login-button btn btn-primary btn-lg rounded-3 fw-bold w-100">Log Masuk ke Dashboard</button>
                </form>

                <div class="text-center border-top mt-4 pt-4"><a href="{{ route('home') }}" class="text-decoration-none fw-semibold">← Kembali ke laman utama</a></div>
            </div>
        </section>
    </main>

    <script>
        (() => {
            const password = document.getElementById('password');
            const toggle = document.getElementById('toggle-password');
            toggle.addEventListener('click', () => {
                const showing = password.type === 'text';
                password.type = showing ? 'password' : 'text';
                toggle.textContent = showing ? 'Papar' : 'Sembunyi';
                toggle.setAttribute('aria-label', showing ? 'Papar kata laluan' : 'Sembunyikan kata laluan');
            });
        })();
    </script>
</body>
</html>

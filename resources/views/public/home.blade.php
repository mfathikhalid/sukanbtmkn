<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karnival Sukan BTMKN 2026</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; font-family: 'Manrope', sans-serif; background: #f8fafc; color: #0f172a; }
        .hero { min-height: 62vh; position: relative; overflow: hidden; background: radial-gradient(circle at 84% 16%, rgba(250,204,21,.38), transparent 18%), radial-gradient(circle at 10% 90%, rgba(249,115,22,.4), transparent 25%), linear-gradient(120deg, #020617, #1e3a8a 54%, #2563eb); }
        .hero::after { content: ''; position: absolute; inset: auto 0 0; height: .7rem; background: repeating-linear-gradient(90deg, #f97316 0 12.5%, #facc15 12.5% 25%, #22c55e 25% 37.5%, #38bdf8 37.5% 50%, #f97316 50% 62.5%, #facc15 62.5% 75%, #22c55e 75% 87.5%, #38bdf8 87.5% 100%); }
        .hero-grid { position: absolute; inset: 0; opacity: .09; background-image: linear-gradient(rgba(255,255,255,.7) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.7) 1px, transparent 1px); background-size: 44px 44px; transform: perspective(440px) rotateX(58deg) scale(1.4); transform-origin: bottom; }
        .hero-ring { position: absolute; width: 24rem; height: 24rem; border: 4.5rem solid rgba(255,255,255,.055); border-radius: 50%; right: -8rem; bottom: -11rem; }
        .confetti { position: absolute; width: .75rem; height: .75rem; border-radius: .16rem; transform: rotate(28deg); }
        .portal { margin-top: -4.5rem; position: relative; z-index: 3; }
        .portal-card { position: relative; overflow: hidden; border-top: .42rem solid var(--portal-color) !important; transition: transform .2s ease, box-shadow .2s ease; }
        .portal-card::after { content: ''; position: absolute; width: 9rem; height: 9rem; right: -5rem; bottom: -5rem; border: 1.5rem solid var(--portal-soft); border-radius: 50%; }
        .portal-card:hover { transform: translateY(-5px); box-shadow: 0 1.2rem 2.6rem rgba(15,23,42,.15) !important; }
        .portal-card > * { position: relative; z-index: 1; }
        .portal-icon { width: 4rem; height: 4rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 1.15rem; background: var(--portal-soft); font-size: 1.8rem; }
        .portal-link { color: var(--portal-dark); }
        .date-pill { color: #713f12; background: #fef3c7; }
    </style>
</head>
<body>
    <header class="hero text-white d-flex align-items-center">
        <div class="hero-grid"></div>
        <div class="hero-ring"></div>
        <span class="confetti bg-warning" style="top: 18%; left: 8%;"></span>
        <span class="confetti bg-info" style="top: 28%; right: 7%; transform: rotate(62deg);"></span>
        <span class="confetti bg-success" style="bottom: 22%; left: 18%;"></span>
        <span class="confetti bg-danger" style="top: 15%; left: 48%; transform: rotate(48deg);"></span>

        <div class="container position-relative py-5">
            <nav class="d-flex justify-content-between align-items-center mb-5">
                <div class="fw-bold fs-5">BTMKN <span class="text-info">SPORTS</span></div>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-light rounded-pill px-4">Dashboard Admin</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-4">Log Masuk Admin</a>
                @endauth
            </nav>

            <div class="row align-items-center g-5 pb-5">
                <div class="col-lg-8">
                    <div class="d-inline-flex align-items-center gap-2 bg-white bg-opacity-10 border border-white border-opacity-25 rounded-pill px-3 py-2 fw-bold text-uppercase small mb-4" style="letter-spacing: .12em;">🏆 Karnival Sukan BTMKN 2026</div>
                    <h1 class="display-2 fw-bold lh-1 mb-4">Bersatu dalam<br><span class="text-warning">semangat juara.</span></h1>
                    <p class="fs-5 text-white-50 mb-4" style="max-width: 640px;">Sertai acara, kenali kontinjen rumah dan ikuti setiap keputusan Karnival Sukan BTMKN secara langsung.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="date-pill badge rounded-pill px-3 py-2">17 Julai — 1 Ogos 2026</span>
                        <span class="badge rounded-pill bg-white bg-opacity-10 border border-white border-opacity-25 px-3 py-2">7 Acara</span>
                        <span class="badge rounded-pill bg-white bg-opacity-10 border border-white border-opacity-25 px-3 py-2">4 Rumah</span>
                    </div>
                </div>
                <div class="col-lg-4 d-none d-lg-block text-center">
                    <div class="display-1 mb-3">🏅</div>
                    <div class="text-uppercase fw-bold text-warning" style="letter-spacing: .18em;">Main • Lawan • Menang</div>
                </div>
            </div>
        </div>
    </header>

    @php
        $portals = [
            ['title' => 'Daftar Acara', 'description' => 'Pilih nama peserta dan acara yang ingin disertai.', 'icon' => '✍️', 'route' => 'public-registration.create', 'color' => '#f97316', 'soft' => '#ffedd5', 'dark' => '#9a3412'],
            ['title' => 'Senarai Peserta', 'description' => 'Lihat peserta yang bertanding dalam setiap acara.', 'icon' => '👥', 'route' => 'public-participants.index', 'color' => '#16a34a', 'soft' => '#dcfce7', 'dark' => '#166534'],
            ['title' => 'Keputusan Live', 'description' => 'Ikuti kedudukan, mata dan bracket secara langsung.', 'icon' => '⚡', 'route' => 'live.index', 'color' => '#dc2626', 'soft' => '#fee2e2', 'dark' => '#991b1b'],
            ['title' => 'Jadual', 'description' => 'Semak tarikh dan lokasi bagi setiap acara.', 'icon' => '📅', 'route' => 'schedule.index', 'color' => '#2563eb', 'soft' => '#dbeafe', 'dark' => '#1e40af'],
        ];
    @endphp

    <main class="portal container pb-5">
        <div class="row g-4">
            @foreach ($portals as $portal)
                <div class="col-md-6 col-xl-3">
                    <a href="{{ route($portal['route']) }}" class="text-decoration-none text-dark">
                        <article class="portal-card card border-0 rounded-4 shadow-sm h-100" style="--portal-color: {{ $portal['color'] }}; --portal-soft: {{ $portal['soft'] }}; --portal-dark: {{ $portal['dark'] }};">
                            <div class="card-body p-4">
                                <span class="portal-icon mb-4">{{ $portal['icon'] }}</span>
                                <h2 class="h4 fw-bold">{{ $portal['title'] }}</h2>
                                <p class="text-secondary mb-4">{{ $portal['description'] }}</p>
                                <span class="portal-link fw-bold">Buka halaman <span aria-hidden="true">→</span></span>
                            </div>
                        </article>
                    </a>
                </div>
            @endforeach
        </div>

        <footer class="text-center text-secondary small pt-5">Karnival Sukan BTMKN 2026</footer>
    </main>
</body>
</html>

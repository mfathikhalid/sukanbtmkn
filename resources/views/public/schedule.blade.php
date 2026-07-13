<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jadual Perlawanan | Sukan BTMKN</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; font-family: 'Manrope', sans-serif; background: radial-gradient(circle at 8% 18%, rgba(249, 115, 22, .1), transparent 22%), radial-gradient(circle at 92% 72%, rgba(37, 99, 235, .1), transparent 25%), #f8fafc; color: #0f172a; }
        .hero { position: relative; overflow: hidden; background: radial-gradient(circle at 82% 18%, rgba(250, 204, 21, .34), transparent 18%), radial-gradient(circle at 12% 92%, rgba(249, 115, 22, .36), transparent 25%), linear-gradient(120deg, #020617, #1e3a8a 54%, #2563eb); }
        .hero::after { content: ''; position: absolute; inset: auto 0 0; height: .65rem; background: repeating-linear-gradient(90deg, #f97316 0 12.5%, #facc15 12.5% 25%, #22c55e 25% 37.5%, #38bdf8 37.5% 50%, #f97316 50% 62.5%, #facc15 62.5% 75%, #22c55e 75% 87.5%, #38bdf8 87.5% 100%); }
        .hero-grid { position: absolute; inset: 0; opacity: .09; background-image: linear-gradient(rgba(255,255,255,.7) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.7) 1px, transparent 1px); background-size: 42px 42px; transform: perspective(420px) rotateX(58deg) scale(1.4); transform-origin: bottom; }
        .confetti { position: absolute; width: .65rem; height: .65rem; border-radius: .15rem; transform: rotate(24deg); }
        .schedule-card { position: relative; overflow: hidden; border-top: .38rem solid var(--event-color) !important; transition: transform .2s ease, box-shadow .2s ease; }
        .schedule-card::after { content: ''; position: absolute; width: 8rem; height: 8rem; right: -4rem; bottom: -5rem; border: 1.5rem solid var(--event-soft); border-radius: 50%; }
        .schedule-card:hover { transform: translateY(-4px) rotate(-.2deg); box-shadow: 0 1.2rem 2.5rem rgba(15, 23, 42, .14) !important; }
        .date-box { width: 5rem; flex: 0 0 5rem; background: linear-gradient(145deg, #fff, var(--event-soft)); border: 1px solid var(--event-soft); }
        .date-box .date-day { background: var(--event-color); }
        .event-number { width: 2rem; height: 2rem; }
        .event-icon { width: 3rem; height: 3rem; display: inline-flex; align-items: center; justify-content: center; background: var(--event-soft); font-size: 1.45rem; }
        .sport-label { color: var(--event-dark); background: var(--event-soft); }
        .location-pin { width: .7rem; height: .7rem; flex: 0 0 .7rem; background: var(--event-color); box-shadow: 0 0 0 .25rem var(--event-soft); }
    </style>
</head>
<body>
    @php
        $schedule = [
            ['event' => 'Congkak', 'detail' => null, 'icon' => '●', 'color' => '#f97316', 'soft' => '#ffedd5', 'dark' => '#9a3412', 'day' => 'Jumaat', 'date' => '17', 'month' => 'Julai', 'year' => '2026', 'location' => 'Ruang Santai, Tingkat 48'],
            ['event' => 'E-Sukan', 'detail' => 'FIFA & Tekken', 'icon' => '🎮', 'color' => '#2563eb', 'soft' => '#dbeafe', 'dark' => '#1e40af', 'day' => 'Selasa', 'date' => '21', 'month' => 'Julai', 'year' => '2026', 'location' => 'Pantri Bilik Latihan, Tingkat 24'],
            ['event' => 'Dart', 'detail' => null, 'icon' => '🎯', 'color' => '#dc2626', 'soft' => '#fee2e2', 'dark' => '#991b1b', 'day' => 'Isnin', 'date' => '27', 'month' => 'Julai', 'year' => '2026', 'location' => 'Ruang Santai, Tingkat 48'],
            ['event' => 'Karom', 'detail' => null, 'icon' => '◆', 'color' => '#7c3aed', 'soft' => '#ede9fe', 'dark' => '#5b21b6', 'day' => 'Khamis', 'date' => '30', 'month' => 'Julai', 'year' => '2026', 'location' => 'Ruang Santai, Tingkat 48'],
            ['event' => 'Boling', 'detail' => null, 'icon' => '🎳', 'color' => '#0891b2', 'soft' => '#cffafe', 'dark' => '#155e75', 'day' => 'Jumaat', 'date' => '31', 'month' => 'Julai', 'year' => '2026', 'location' => 'East Sound Bowl, Prangin Mall'],
            ['event' => 'Pickleball', 'detail' => null, 'icon' => '🏓', 'color' => '#16a34a', 'soft' => '#dcfce7', 'dark' => '#166534', 'day' => 'Sabtu', 'date' => '1', 'month' => 'Ogos', 'year' => '2026', 'location' => 'Pickle Hill @ Berapit Sport Centre'],
        ];
    @endphp

    <header class="hero text-white py-5">
        <div class="hero-grid"></div>
        <span class="confetti bg-warning" style="top: 18%; left: 7%;"></span>
        <span class="confetti bg-info" style="top: 24%; right: 8%; transform: rotate(58deg);"></span>
        <span class="confetti bg-success" style="bottom: 24%; right: 18%;"></span>
        <div class="container py-lg-4">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-4 position-relative">
                <div>
                    <div class="d-inline-flex align-items-center gap-2 bg-white bg-opacity-10 border border-white border-opacity-25 rounded-pill px-3 py-2 fw-bold text-uppercase small mb-3" style="letter-spacing: .12em;">🏆 Karnival Sukan BTMKN 2026</div>
                    <h1 class="display-4 fw-bold mb-2">Jadual Perlawanan</h1>
                    <p class="fs-5 text-white-50 mb-3">Satu pasukan. Satu semangat. Kejar kejuaraan!</p>
                    <div class="d-flex flex-wrap gap-2"><span class="badge rounded-pill text-bg-warning text-dark px-3 py-2">6 Acara</span><span class="badge rounded-pill bg-white bg-opacity-10 border border-white border-opacity-25 px-3 py-2">17 Julai — 1 Ogos 2026</span></div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('public-registration.create') }}" class="btn btn-warning rounded-pill px-4 py-2 fw-semibold">Daftar Peserta</a>
                    <a href="{{ route('public-participants.index') }}" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold">Senarai Peserta</a>
                    <a href="{{ route('live.index') }}" class="btn btn-light rounded-pill px-4 py-2 fw-semibold">Lihat Keputusan Live</a>
                </div>
            </div>
        </div>
    </header>

    <main class="container py-5">
        <div class="row g-4">
            @foreach ($schedule as $index => $item)
                <div class="col-lg-6">
                    <article class="card schedule-card h-100 border-0 rounded-4 shadow-sm" style="--event-color: {{ $item['color'] }}; --event-soft: {{ $item['soft'] }}; --event-dark: {{ $item['dark'] }};">
                        <div class="card-body d-flex gap-3 gap-sm-4 p-4">
                            <div class="date-box rounded-4 text-center overflow-hidden align-self-start">
                                <div class="date-day text-white small fw-bold py-1">{{ $item['day'] }}</div>
                                <div class="fs-2 fw-bold lh-1 pt-3">{{ $item['date'] }}</div>
                                <div class="small fw-semibold">{{ $item['month'] }}</div>
                                <div class="small text-secondary pb-3">{{ $item['year'] }}</div>
                            </div>
                            <div class="flex-grow-1 py-1">
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <span class="event-icon rounded-3">{{ $item['icon'] }}</span>
                                    <h2 class="h4 fw-bold mb-0">{{ $item['event'] }}</h2>
                                </div>
                                @if ($item['detail'])
                                    <div class="badge sport-label mb-3">{{ $item['detail'] }}</div>
                                @endif
                                <div class="text-secondary mb-1">{{ $item['date'] }} {{ $item['month'] }} {{ $item['year'] }}</div>
                                <div class="d-flex align-items-center gap-2 fw-semibold"><span class="location-pin rounded-circle"></span>{{ $item['location'] }}</div>
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        <div class="alert alert-light border rounded-4 mt-4 mb-0 text-secondary">
            Sila hadir lebih awal. Waktu mula acara akan dimaklumkan oleh urus setia.
        </div>
    </main>
</body>
</html>

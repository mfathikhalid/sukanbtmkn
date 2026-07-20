<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Live | Sukan BTMKN</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Manrope', sans-serif; background: radial-gradient(circle at 6% 14%, rgba(249, 115, 22, .09), transparent 20%), radial-gradient(circle at 96% 58%, rgba(37, 99, 235, .09), transparent 24%), #f8fafc; color: #0f172a; }
        .hero { position: relative; overflow: hidden; background: radial-gradient(circle at 84% 16%, rgba(250, 204, 21, .32), transparent 18%), radial-gradient(circle at 10% 90%, rgba(249, 115, 22, .34), transparent 24%), linear-gradient(120deg, #020617, #1e3a8a 54%, #2563eb); }
        .hero::after { content: ''; position: absolute; inset: auto 0 0; height: .65rem; background: repeating-linear-gradient(90deg, #f97316 0 12.5%, #facc15 12.5% 25%, #22c55e 25% 37.5%, #38bdf8 37.5% 50%, #f97316 50% 62.5%, #facc15 62.5% 75%, #22c55e 75% 87.5%, #38bdf8 87.5% 100%); }
        .hero-grid { position: absolute; inset: 0; opacity: .09; background-image: linear-gradient(rgba(255,255,255,.7) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.7) 1px, transparent 1px); background-size: 42px 42px; transform: perspective(420px) rotateX(58deg) scale(1.4); transform-origin: bottom; }
        .confetti { position: absolute; width: .65rem; height: .65rem; border-radius: .15rem; transform: rotate(24deg); }
        .public-match { background: #fff; }
        .live-dot { width: .65rem; height: .65rem; background: #22c55e; box-shadow: 0 0 0 .25rem rgba(34, 197, 94, .2); }
        .live-pill { border: 1px solid rgba(255, 255, 255, .2); }
        .section-kicker { display: inline-flex; align-items: center; gap: .45rem; padding: .35rem .75rem; border-radius: 999px; color: #1d4ed8; background: #dbeafe; font-size: .72rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
        .score-table { border-top: .35rem solid #f97316; }
        .score-table thead th { background: #fff7ed; color: #9a3412; }
        .event-card { position: relative; overflow: hidden; border-top: .4rem solid var(--event-color) !important; }
        .event-card::after { content: ''; position: absolute; width: 10rem; height: 10rem; right: -6rem; top: -6rem; border: 1.7rem solid var(--event-soft); border-radius: 50%; pointer-events: none; }
        .event-card > * { position: relative; z-index: 1; }
        .event-icon { width: 2.75rem; height: 2.75rem; display: inline-flex; align-items: center; justify-content: center; border-radius: .8rem; background: var(--event-soft); font-size: 1.3rem; }
        .event-category { color: var(--event-dark); background: var(--event-soft); }
        .ranking-card { transition: transform .65s ease, box-shadow .3s ease; }
        .ranking-leader { box-shadow: 0 1.2rem 2.8rem rgba(161, 98, 7, .28) !important; }
        .podium-card { position: relative; overflow: hidden; isolation: isolate; }
        .podium-card::before { content: ''; position: absolute; inset: 0; z-index: 0; background: radial-gradient(circle at 18% 8%, rgba(255, 255, 255, .78), transparent 34%); pointer-events: none; }
        .podium-card > * { position: relative; z-index: 1; }
        .podium-gold { background: linear-gradient(135deg, #fff8d2 0%, #f7dc78 24%, #c99716 50%, #f6e6a2 73%, #a66f08 100%); border: 1px solid #b77909 !important; box-shadow: inset 0 1px rgba(255, 255, 255, .8), 0 .9rem 2rem rgba(161, 98, 7, .2); }
        .podium-silver { background: linear-gradient(135deg, #ffffff 0%, #dfe4ea 24%, #9aa3ad 50%, #edf1f5 73%, #77818d 100%); border: 1px solid #8b95a1 !important; box-shadow: inset 0 1px rgba(255, 255, 255, .95), 0 .9rem 2rem rgba(71, 85, 105, .18); }
        .podium-bronze { background: linear-gradient(135deg, #ffe2c2 0%, #d89252 24%, #995226 50%, #e7ad73 73%, #713716 100%); border: 1px solid #92400e !important; box-shadow: inset 0 1px rgba(255, 255, 255, .65), 0 .9rem 2rem rgba(120, 53, 15, .2); }
        .rank-gold { background: linear-gradient(145deg, #f8df7b, #9f6907); color: #fff; border: 1px solid #fff0a6; text-shadow: 0 1px 2px rgba(0, 0, 0, .35); box-shadow: 0 .3rem .7rem rgba(161, 98, 7, .3); }
        .rank-silver { background: linear-gradient(145deg, #eef2f6, #66717d); color: #fff; border: 1px solid #fff; text-shadow: 0 1px 2px rgba(0, 0, 0, .45); box-shadow: 0 .3rem .7rem rgba(71, 85, 105, .28); }
        .rank-bronze { background: linear-gradient(145deg, #e5a66a, #713716); color: #fff; border: 1px solid #ffd1a4; text-shadow: 0 1px 2px rgba(0, 0, 0, .4); box-shadow: 0 .3rem .7rem rgba(120, 53, 15, .3); }
    </style>
</head>
<body>
    <main id="public-live-content">
        <header class="hero text-white py-4 py-lg-5 mb-4">
            <div class="hero-grid"></div>
            <span class="confetti bg-warning" style="top: 18%; left: 7%;"></span>
            <span class="confetti bg-info" style="top: 22%; right: 7%; transform: rotate(58deg);"></span>
            <span class="confetti bg-success" style="bottom: 25%; right: 20%;"></span>
            <div class="container position-relative py-lg-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 bg-white bg-opacity-10 border border-white border-opacity-25 rounded-pill px-3 py-2 fw-bold text-uppercase small mb-3" style="letter-spacing: .12em;">🏆 Karnival Sukan BTMKN 2026</div>
                        <h1 class="display-5 fw-bold mb-1">Keputusan Langsung</h1>
                        <div class="text-white-50 fs-5">Semangat rumah. Aksi sebenar. Keputusan terkini.</div>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <a href="{{ route('public-registration.create') }}" class="btn btn-warning rounded-pill px-4">Daftar Peserta</a>
                        <a href="{{ route('public-participants.index') }}" class="btn btn-outline-light rounded-pill px-4">Senarai Peserta</a>
                        <a href="{{ route('schedule.index') }}" class="btn btn-outline-light rounded-pill px-4">Jadual Perlawanan</a>
                        <div class="live-pill d-flex align-items-center gap-2 bg-white bg-opacity-10 rounded-pill px-4 py-2">
                            <span class="live-dot rounded-circle"></span>
                            <strong>LIVE</strong>
                            <span id="public-live-time" class="text-white-50 small"></span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container pb-5">
            <section class="mb-5">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div><div class="section-kicker mb-2">🏅 Scoreboard</div><h2 class="h3 fw-bold mb-0">Kedudukan Keseluruhan</h2></div>
                </div>
                <div class="row g-3">
                    @foreach ($standings as $index => $row)
                        @php
                            $podiumClass = match ($index) { 0 => 'podium-gold', 1 => 'podium-silver', 2 => 'podium-bronze', default => '' };
                            $rankClass = match ($index) { 0 => 'rank-gold', 1 => 'rank-silver', 2 => 'rank-bronze', default => 'text-bg-dark' };
                        @endphp
                        <div class="col-sm-6 col-lg-3" data-ranking-card data-house-id="{{ $row['house']->id }}">
                            <div class="card border-0 shadow-sm rounded-4 h-100 ranking-card {{ $index < 3 ? 'podium-card' : '' }} {{ $podiumClass }} {{ $index === 0 ? 'ranking-leader' : '' }}" style="border-top: .35rem solid {{ $row['house']->color }} !important;">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="badge {{ $rankClass }}">#{{ $index + 1 }}</span>
                                        @if ($index === 0)<span class="badge text-bg-warning text-dark align-self-center">Pendahulu</span>@endif
                                        <span class="display-6 fw-bold">{{ $row['points'] }}</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 fw-bold"><span class="rounded-circle border" style="width: 1rem; height: 1rem; background: {{ $row['house']->color }}"></span>Rumah {{ $row['house']->name }}</div>
                                    <div class="small text-secondary mt-1">jumlah mata</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="mb-5">
                <div class="section-kicker mb-2">🏆 Kutipan Mata</div>
                <h2 class="h3 fw-bold mb-3">Mata Mengikut Acara</h2>
                <div class="score-table table-responsive bg-white rounded-4 shadow-sm overflow-hidden">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th class="ps-4">Acara</th><th>Kategori</th>@foreach ($standings as $row)<th class="text-center">{{ $row['house']->name }}</th>@endforeach<th class="pe-4 text-end">Status</th></tr></thead>
                        <tbody>
                            @foreach ($eventBreakdown as $event)
                                @php
                                    [$breakdownStatusClass, $breakdownStatusLabel] = match ($event['status']) {
                                        'complete' => ['text-bg-success', 'Selesai'],
                                        'ongoing' => ['text-bg-warning', 'Sedang berlangsung'],
                                        default => ['text-bg-secondary', 'Belum bermula'],
                                    };
                                @endphp
                                <tr><td class="ps-4 fw-semibold">{{ $event['event'] }}</td><td>{{ $event['category'] }}</td>@foreach ($standings as $row)<td class="text-center fw-bold">{{ $event['complete'] ? ($event['points'][$row['house']->id] ?? 0) : '—' }}</td>@endforeach<td class="pe-4 text-end"><span class="badge {{ $breakdownStatusClass }}">{{ $breakdownStatusLabel }}</span></td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <section>
                <div class="section-kicker mb-2">⚡ Arena Pertandingan</div>
                <h2 class="h3 fw-bold mb-4">Peringkat Knockout</h2>
                <div class="d-grid gap-4">
                    @foreach ($events as $event)
                        @php
                            [$eventStatusClass, $eventStatusLabel] = match ($event['status']) {
                                'complete' => ['text-bg-success', 'Selesai'],
                                'ongoing' => ['text-bg-warning', 'Sedang berlangsung'],
                                default => ['text-bg-secondary', 'Belum bermula'],
                            };
                            [$eventIcon, $eventColor, $eventSoft, $eventDark] = match ($event['sport']->name) {
                                'Congkak' => ['●', '#f97316', '#ffedd5', '#9a3412'],
                                'FIFA', 'Tekken' => ['🎮', '#2563eb', '#dbeafe', '#1e40af'],
                                'Dart' => ['🎯', '#dc2626', '#fee2e2', '#991b1b'],
                                'Carrom' => ['◆', '#7c3aed', '#ede9fe', '#5b21b6'],
                                'Bowling' => ['🎳', '#0891b2', '#cffafe', '#155e75'],
                                'Pickleball' => ['🏓', '#16a34a', '#dcfce7', '#166534'],
                                default => ['🏆', '#2563eb', '#dbeafe', '#1e40af'],
                            };
                        @endphp
                        <article class="event-card card border-0 shadow-sm rounded-4" style="--event-color: {{ $eventColor }}; --event-soft: {{ $eventSoft }}; --event-dark: {{ $eventDark }};">
                            <div class="card-header bg-white border-0 p-4 pb-2 d-flex flex-wrap justify-content-between align-items-center gap-3"><h3 class="h4 fw-bold mb-0 d-flex align-items-center gap-3"><span class="event-icon">{{ $eventIcon }}</span><span>{{ $event['sport']->name }} <span class="event-category badge fs-6">{{ $event['category'] }}</span></span></h3><span class="badge {{ $eventStatusClass }}">{{ $eventStatusLabel }}</span></div>
                            <div class="card-body p-4">
                                @if ($event['type'] === 'bowling')
                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                                        <div class="text-secondary">Kedudukan berdasarkan jumlah jatuhan pin daripada dua game setiap pemain.</div>
                                    </div>
                                    <div class="row g-4">
                                        <div class="col-xl-5">
                                            <h4 class="h6 fw-bold mb-3">Jumlah Pin Rumah</h4>
                                            <div class="d-grid gap-2">
                                                @forelse ($event['houseTotals'] as $houseId => $total)
                                                    @php
                                                        $house = $event['playerTotals']->pluck('participant.house')->firstWhere('id', $houseId);
                                                    @endphp
                                                    <div class="d-flex align-items-center gap-2 border rounded-3 p-3">
                                                        <span class="rounded-circle border" style="width: .8rem; height: .8rem; background: {{ $house?->color }}"></span>
                                                        <span class="fw-semibold flex-grow-1">Rumah {{ $house?->name }}</span>
                                                        <span class="fw-bold">{{ number_format($total) }}</span>
                                                    </div>
                                                @empty
                                                    <div class="text-secondary">Belum ada skor direkodkan.</div>
                                                @endforelse
                                            </div>
                                        </div>
                                        <div class="col-xl-7">
                                            <h4 class="h6 fw-bold mb-3">Skor Pemain</h4>
                                            <div class="table-responsive">
                                                <table class="table table-sm align-middle mb-0">
                                                    <thead class="table-light"><tr><th>#</th><th>Pemain</th><th>Rumah</th><th class="text-center">Game 1</th><th class="text-center">Game 2</th><th class="text-end">Jumlah</th></tr></thead>
                                                    <tbody>
                                                        @php
                                                            $currentBowlingGroup = null;
                                                            $bowlingGroupNumber = 0;
                                                        @endphp
                                                        @forelse ($event['playerTotals'] as $index => $row)
                                                            @php
                                                                $bowlingGender = $row['participant']->gender?->value;
                                                                $bowlingGenderLabel = $bowlingGender === 'Male' ? 'Lelaki' : 'Perempuan';
                                                                $bowlingGroup = ($row['participant']->house_id ?? 'tiada').'-'.$bowlingGender;
                                                            @endphp
                                                            @if ($currentBowlingGroup !== $bowlingGroup)
                                                                @php
                                                                    $bowlingGroupNumber = 0;
                                                                @endphp
                                                                <tr class="table-info"><th colspan="6">Rumah {{ $row['participant']->house?->name ?? 'Tanpa Rumah' }} — {{ $bowlingGenderLabel }}</th></tr>
                                                                @php
                                                                    $currentBowlingGroup = $bowlingGroup;
                                                                @endphp
                                                            @endif
                                                            @php
                                                                $bowlingGroupNumber++;
                                                            @endphp
                                                            <tr><td>{{ $bowlingGroupNumber }}</td><td class="fw-semibold">{{ $row['participant']->name }}</td><td>{{ $row['participant']->house?->name }}</td><td class="text-center">{{ $row['game_1'] ?? '—' }}</td><td class="text-center">{{ $row['game_2'] ?? '—' }}</td><td class="text-end fw-bold">{{ number_format($row['total']) }}</td></tr>
                                                        @empty
                                                            <tr><td colspan="6" class="text-center text-secondary py-4">Tiada peserta boling berdaftar.</td></tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    @php
                                        $eventKey = $event['sport']->id.'-'.strtolower($event['gender']->value);
                                    @endphp
                                <div class="accordion mb-4" id="round-robin-{{ $eventKey }}">
                                    <div class="accordion-item rounded-4 overflow-hidden border">
                                        <h4 class="accordion-header">
                                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#round-robin-panel-{{ $eventKey }}" aria-expanded="false" aria-controls="round-robin-panel-{{ $eventKey }}">
                                                Round Robin — Kedudukan & Keputusan
                                            </button>
                                        </h4>
                                        <div id="round-robin-panel-{{ $eventKey }}" class="accordion-collapse collapse">
                                            <div class="accordion-body">
                                                <div class="row g-4">
                                                    <div class="col-xl-5">
                                                        <h5 class="h6 fw-bold mb-3">Kedudukan Round Robin</h5>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm align-middle mb-0">
                                                                <thead class="table-light"><tr><th>#</th><th>Rumah</th><th class="text-center">Main</th><th class="text-center">M</th><th class="text-center">K</th><th class="text-center">Mata</th></tr></thead>
                                                                <tbody>
                                                                    @foreach ($event['standings'] as $index => $standing)
                                                                        <tr><td>{{ $index + 1 }}</td><td class="fw-semibold">{{ $standing['house']->name }}</td><td class="text-center">{{ $standing['played'] }}</td><td class="text-center">{{ $standing['won'] }}</td><td class="text-center">{{ $standing['lost'] }}</td><td class="text-center fw-bold">{{ $standing['points'] }}</td></tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-7">
                                                        <h5 class="h6 fw-bold mb-3">Keputusan Round Robin</h5>
                                                        <div class="row g-2">
                                                            @forelse ($event['leagueMatches'] as $match)
                                                                <div class="col-md-6">
                                                                    <x-public-match :match="$match" :title="'Perlawanan '.$match->match_no" />
                                                                </div>
                                                            @empty
                                                                <div class="col-12 text-secondary">Perlawanan belum dijana.</div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3"><div class="col-lg-3"><x-public-match :match="$event['semiFinals']->get(0)" title="Separuh Akhir 1" /></div><div class="col-lg-3"><x-public-match :match="$event['semiFinals']->get(1)" title="Separuh Akhir 2" /></div><div class="col-lg-3"><x-public-match :match="$event['thirdPlace']" title="Tempat Ketiga" /></div><div class="col-lg-3"><x-public-match :match="$event['final']" title="Final" /></div></div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            let refreshing = false;
            const refresh = async () => {
                if (refreshing || document.hidden) return;
                refreshing = true;
                try {
                    const response = await fetch(window.location.href, { cache: 'no-store', headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    if (!response.ok) return;
                    const copy = new DOMParser().parseFromString(await response.text(), 'text/html');
                    const next = copy.getElementById('public-live-content');
                    const current = document.getElementById('public-live-content');
                    const previousRankingPositions = current
                        ? new Map([...current.querySelectorAll('[data-ranking-card]')].map((card) => [
                            card.dataset.houseId,
                            card.getBoundingClientRect(),
                        ]))
                        : new Map();
                    const openPanels = current
                        ? [...current.querySelectorAll('.accordion-collapse.show')].map((panel) => panel.id)
                        : [];
                    if (next && current) {
                        current.innerHTML = next.innerHTML;
                        current.querySelectorAll('[data-ranking-card]').forEach((card) => {
                            const previousPosition = previousRankingPositions.get(card.dataset.houseId);

                            if (!previousPosition) return;

                            const currentPosition = card.getBoundingClientRect();
                            const offsetX = previousPosition.left - currentPosition.left;
                            const offsetY = previousPosition.top - currentPosition.top;

                            if (offsetX !== 0 || offsetY !== 0) {
                                card.style.transform = `translate(${offsetX}px, ${offsetY}px)`;
                                window.requestAnimationFrame(() => {
                                    card.style.transition = 'transform .65s ease';
                                    card.style.transform = 'translate(0, 0)';
                                });
                            }
                        });
                        openPanels.forEach((id) => {
                            const panel = document.getElementById(id);
                            const button = document.querySelector(`[data-bs-target="#${id}"]`);
                            panel?.classList.add('show');
                            button?.classList.remove('collapsed');
                            button?.setAttribute('aria-expanded', 'true');
                        });
                    }
                    const time = document.getElementById('public-live-time');
                    if (time) time.textContent = new Date().toLocaleTimeString('ms-MY');
                } catch (error) {
                    // Retain the current public display during temporary connection issues.
                } finally { refreshing = false; }
            };
            window.setInterval(refresh, 5000);
        })();
    </script>
</body>
</html>

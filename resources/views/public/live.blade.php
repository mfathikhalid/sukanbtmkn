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
        body { font-family: 'Manrope', sans-serif; background: #f1f5f9; color: #0f172a; }
        .hero { background: linear-gradient(135deg, #020617, #1e3a8a 65%, #2563eb); }
        .public-match { background: #fff; }
        .live-dot { width: .65rem; height: .65rem; background: #22c55e; box-shadow: 0 0 0 .25rem rgba(34, 197, 94, .2); }
        .ranking-card { transition: transform .65s ease, box-shadow .3s ease; }
        .ranking-leader { box-shadow: 0 1rem 2.5rem rgba(37, 99, 235, .2) !important; border: 1px solid rgba(37, 99, 235, .22) !important; }
        .podium-gold { background: linear-gradient(145deg, #fffdf0, #fef3c7); border: 1px solid #f59e0b !important; }
        .podium-silver { background: linear-gradient(145deg, #ffffff, #e2e8f0); border: 1px solid #94a3b8 !important; }
        .podium-bronze { background: linear-gradient(145deg, #fffaf5, #fed7aa); border: 1px solid #c2410c !important; }
        .rank-gold { background: #d97706; color: #fff; }
        .rank-silver { background: #64748b; color: #fff; }
        .rank-bronze { background: #9a3412; color: #fff; }
    </style>
</head>
<body>
    <main id="public-live-content">
        <header class="hero text-white py-4 py-lg-5 mb-4">
            <div class="container">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <div class="text-info fw-bold text-uppercase small mb-2" style="letter-spacing: .15em;">Sukan BTMKN</div>
                        <h1 class="display-5 fw-bold mb-1">Keputusan Langsung</h1>
                        <div class="text-white-50">Scoreboard dan bracket knockout semua acara</div>
                    </div>
                    <div class="d-flex align-items-center gap-2 bg-white bg-opacity-10 rounded-pill px-4 py-2">
                        <span class="live-dot rounded-circle"></span>
                        <strong>LIVE</strong>
                        <span id="public-live-time" class="text-white-50 small"></span>
                    </div>
                </div>
            </div>
        </header>

        <div class="container pb-5">
            <section class="mb-5">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div><div class="text-primary fw-bold small text-uppercase">Scoreboard</div><h2 class="h3 fw-bold mb-0">Kedudukan Keseluruhan</h2></div>
                </div>
                <div class="row g-3">
                    @foreach ($standings as $index => $row)
                        @php
                            $podiumClass = match ($index) { 0 => 'podium-gold', 1 => 'podium-silver', 2 => 'podium-bronze', default => '' };
                            $rankClass = match ($index) { 0 => 'rank-gold', 1 => 'rank-silver', 2 => 'rank-bronze', default => 'text-bg-dark' };
                        @endphp
                        <div class="col-sm-6 col-lg-3" data-ranking-card data-house-id="{{ $row['house']->id }}">
                            <div class="card border-0 shadow-sm rounded-4 h-100 ranking-card {{ $podiumClass }} {{ $index === 0 ? 'ranking-leader' : '' }}">
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
                <h2 class="h3 fw-bold mb-3">Mata Mengikut Acara</h2>
                <div class="table-responsive bg-white rounded-4 shadow-sm">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th class="ps-4">Acara</th><th>Kategori</th>@foreach ($standings as $row)<th class="text-center">{{ $row['house']->name }}</th>@endforeach<th class="pe-4 text-end">Status</th></tr></thead>
                        <tbody>
                            @foreach ($eventBreakdown as $event)
                                <tr><td class="ps-4 fw-semibold">{{ $event['event'] }}</td><td>{{ $event['category'] }}</td>@foreach ($standings as $row)<td class="text-center fw-bold">{{ $event['complete'] ? ($event['points'][$row['house']->id] ?? 0) : '—' }}</td>@endforeach<td class="pe-4 text-end"><span class="badge {{ $event['complete'] ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $event['complete'] ? 'Selesai' : 'Belum selesai' }}</span></td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <section>
                <div class="text-primary fw-bold small text-uppercase">Bracket</div>
                <h2 class="h3 fw-bold mb-4">Peringkat Knockout</h2>
                <div class="d-grid gap-4">
                    @foreach ($events as $event)
                        @php($eventKey = $event['sport']->id.'-'.strtolower($event['gender']->value))
                        <article class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-white border-0 p-4 pb-2"><h3 class="h4 fw-bold mb-0">{{ $event['sport']->name }} <span class="badge text-bg-light border fs-6">{{ $event['category'] }}</span></h3></div>
                            <div class="card-body p-4">
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

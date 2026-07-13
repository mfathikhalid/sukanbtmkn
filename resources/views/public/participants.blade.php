<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Senarai Peserta | Sukan BTMKN</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; font-family: 'Manrope', sans-serif; background: radial-gradient(circle at 7% 18%, rgba(249,115,22,.1), transparent 24%), radial-gradient(circle at 94% 72%, rgba(37,99,235,.1), transparent 26%), #f8fafc; color: #0f172a; }
        .hero { position: relative; overflow: hidden; background: radial-gradient(circle at 84% 16%, rgba(250,204,21,.32), transparent 18%), radial-gradient(circle at 10% 90%, rgba(249,115,22,.34), transparent 24%), linear-gradient(120deg, #020617, #1e3a8a 54%, #2563eb); }
        .hero::after { content: ''; position: absolute; inset: auto 0 0; height: .6rem; background: repeating-linear-gradient(90deg, #f97316 0 12.5%, #facc15 12.5% 25%, #22c55e 25% 37.5%, #38bdf8 37.5% 50%, #f97316 50% 62.5%, #facc15 62.5% 75%, #22c55e 75% 87.5%, #38bdf8 87.5% 100%); }
        .filter-card { margin-top: -2.25rem; position: relative; z-index: 2; }
        .event-card { border-top: .4rem solid var(--event-color) !important; }
        .event-icon { width: 2.75rem; height: 2.75rem; background: var(--event-soft); }
        .participant-avatar { width: 2.5rem; height: 2.5rem; flex: 0 0 2.5rem; color: #fff; background: linear-gradient(145deg, var(--house-color), #0f172a); }
        .house-badge { color: #fff; background: var(--house-color); }
    </style>
</head>
<body>
    <header class="hero text-white pt-5 pb-5">
        <div class="container pb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-4">
                <div>
                    <div class="d-inline-flex bg-white bg-opacity-10 border border-white border-opacity-25 rounded-pill px-3 py-2 fw-bold text-uppercase small mb-3" style="letter-spacing: .1em;">🏆 Karnival Sukan BTMKN 2026</div>
                    <h1 class="display-5 fw-bold mb-2">Senarai Peserta</h1>
                    <p class="text-white-50 fs-5 mb-0">Lihat senarai peserta yang bertanding dalam setiap acara.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('public-registration.create') }}" class="btn btn-warning rounded-pill">Daftar Acara</a>
                    <a href="{{ route('schedule.index') }}" class="btn btn-outline-light rounded-pill">Jadual</a>
                    <a href="{{ route('live.index') }}" class="btn btn-light rounded-pill">Keputusan Live</a>
                </div>
            </div>
        </div>
    </header>

    <main class="container pb-5">
        <div class="filter-card card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-body p-4">
                <form method="get" action="{{ route('public-participants.index') }}" class="row g-3 align-items-end">
                    <div class="col-lg-4"><label class="form-label fw-semibold">Cari nama peserta</label><input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Masukkan nama..."></div>
                    <div class="col-sm-6 col-lg-2">
                        <label class="form-label fw-semibold">Rumah</label>
                        <select name="house_id" class="form-select"><option value="">Semua rumah</option>@foreach ($houses as $house)<option value="{{ $house->id }}" @selected((string) request('house_id') === (string) $house->id)>{{ $house->name }}</option>@endforeach</select>
                    </div>
                    <div class="col-sm-6 col-lg-2"><label class="form-label fw-semibold">Acara</label><select name="sport_id" class="form-select"><option value="">Semua acara</option>@foreach ($sports as $sport)<option value="{{ $sport->id }}" @selected((string) request('sport_id') === (string) $sport->id)>{{ $sport->name }}</option>@endforeach</select></div>
                    <div class="col-sm-6 col-lg-2"><label class="form-label fw-semibold">Jantina</label><select name="gender" class="form-select"><option value="">Semua</option><option value="Male" @selected(request('gender') === 'Male')>Lelaki</option><option value="Female" @selected(request('gender') === 'Female')>Perempuan</option></select></div>
                    <div class="col-lg-2 d-flex gap-2"><button class="btn btn-primary flex-grow-1" type="submit">Tapis</button>@if (request()->hasAny(['search', 'house_id', 'sport_id', 'gender']))<a href="{{ route('public-participants.index') }}" class="btn btn-light">Reset</a>@endif</div>
                </form>
            </div>
        </div>

        <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
            <div><div class="text-primary fw-bold small text-uppercase">Penyertaan Acara</div><h2 class="h3 fw-bold mb-0">Senarai Mengikut Acara</h2></div>
        </div>

        <div class="d-grid gap-4">
            @forelse ($sports as $sport)
                @php
                    $sportParticipants = $participants
                        ->filter(fn ($participant) => $participant->sports->contains('id', $sport->id))
                        ->values();
                    [$eventIcon, $eventColor, $eventSoft] = match ($sport->name) {
                        'Congkak' => ['●', '#f97316', '#ffedd5'],
                        'FIFA', 'Tekken' => ['🎮', '#2563eb', '#dbeafe'],
                        'Dart' => ['🎯', '#dc2626', '#fee2e2'],
                        'Carrom' => ['◆', '#7c3aed', '#ede9fe'],
                        'Bowling' => ['🎳', '#0891b2', '#cffafe'],
                        'Pickleball' => ['🏓', '#16a34a', '#dcfce7'],
                        default => ['🏆', '#2563eb', '#dbeafe'],
                    };
                @endphp
                @if ($sportParticipants->isNotEmpty())
                    <section class="event-card card border-0 rounded-4 shadow-sm overflow-hidden" style="--event-color: {{ $eventColor }}; --event-soft: {{ $eventSoft }};">
                        <div class="card-header bg-white border-0 p-4 pb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="h4 fw-bold mb-0 d-flex align-items-center gap-3"><span class="event-icon rounded-3 d-inline-flex align-items-center justify-content-center">{{ $eventIcon }}</span>{{ $sport->name }}</h3>
                                <span class="badge text-bg-dark rounded-pill">{{ $sportParticipants->count() }} peserta</span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                @foreach ($sportParticipants as $participant)
                                    <div class="col-md-6">
                                        <article class="border rounded-4 p-3 h-100 d-flex gap-3" style="--house-color: {{ $participant->house->color }};">
                                            <span class="participant-avatar rounded-circle d-inline-flex align-items-center justify-content-center fw-bold">{{ mb_strtoupper(mb_substr($participant->name, 0, 1)) }}</span>
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="d-flex flex-wrap justify-content-between gap-2 mb-2"><h4 class="h6 fw-bold mb-0">{{ $participant->name }}</h4><span class="badge text-bg-light border">{{ $participant->gender->value === 'Male' ? 'Lelaki' : 'Perempuan' }}</span></div>
                                                <span class="house-badge badge">Rumah {{ $participant->house->name }}</span>
                                            </div>
                                        </article>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif
            @empty
            @endforelse

            @if ($participants->isEmpty())
                <div class="card border-0 rounded-4 shadow-sm"><div class="card-body text-center py-5"><div class="fs-1 mb-2">🔎</div><h2 class="h5 fw-bold">Tiada peserta ditemui</h2><p class="text-secondary mb-0">Cuba ubah carian atau penapis anda.</p></div></div>
            @endif
        </div>
    </main>
</body>
</html>

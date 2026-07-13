<x-layouts.app :title="($selectedSport?->name ?? 'Acara').' | Sukan BTMKN'">
    @php
        [$eventLabel, $eventGradient, $eventDescription] = match ($selectedSport?->name) {
            'FIFA' => ['E-Sukan FIFA', 'linear-gradient(135deg, #172554, #2563eb)', 'Urus pertandingan FIFA daripada Round Robin hingga penentuan kedudukan akhir.'],
            'Tekken' => ['E-Sukan Tekken', 'linear-gradient(135deg, #3b0764, #9333ea)', 'Urus pertandingan Tekken daripada Round Robin hingga penentuan kedudukan akhir.'],
            'Congkak' => ['Permainan Tradisional', 'linear-gradient(135deg, #7c2d12, #ea580c)', 'Urus pertandingan Congkak antara rumah mengikut kategori peserta.'],
            'Carrom' => ['Permainan Dalaman', 'linear-gradient(135deg, #312e81, #7c3aed)', 'Urus pertandingan Carrom daripada Round Robin hingga penentuan juara.'],
            'Pickleball' => ['Sukan Berpasukan', 'linear-gradient(135deg, #064e3b, #16a34a)', 'Urus pertandingan Pickleball antara rumah mengikut kategori peserta.'],
            default => ['Acara Karnival', 'linear-gradient(135deg, #0f172a, #2563eb)', 'Urus keseluruhan pertandingan daripada Round Robin hingga penentuan kedudukan akhir.'],
        };
    @endphp

    <div class="rounded-5 p-4 p-lg-5 text-white mb-4 overflow-hidden position-relative" data-event-hero="true" style="background: {{ $eventGradient }}; box-shadow: 0 1.5rem 3rem rgba(15, 23, 42, .18);">
        <div class="position-absolute rounded-circle border border-5 border-white border-opacity-10" style="width: 12rem; height: 12rem; right: -4rem; top: -5rem;"></div>
        <div class="position-relative">
            <div class="text-uppercase fw-bold text-warning small mb-2" style="letter-spacing: .14em;">{{ $eventLabel }}</div>
            <h1 class="display-6 fw-bold mb-2">{{ $selectedSport?->name }}</h1>
            <p class="text-white-50 mb-0">{{ $eventDescription }}</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="get" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Kategori</label>
                    <select name="gender" class="form-select">
                        @foreach ($availableGenders as $availableGender)
                            <option value="{{ $availableGender->value }}" @selected($gender === $availableGender)>
                                {{ $availableGender === \App\Enums\Gender::Male ? 'Lelaki' : 'Perempuan' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-grid">
                    <button class="btn btn-dark">Papar Kategori</button>
                </div>
            </form>
        </div>
    </div>

    @if ($selectedSport)
        <div class="d-flex flex-wrap gap-2 mb-4">
            <form method="post" action="{{ route('matches.league-fixtures', $selectedSport) }}">
                @csrf
                <input type="hidden" name="gender" value="{{ $gender->value }}">
                <button class="btn btn-primary">1. Jana Round Robin</button>
            </form>
            <form method="post" action="{{ route('matches.semi-finals', $selectedSport) }}">
                @csrf
                <input type="hidden" name="gender" value="{{ $gender->value }}">
                <button class="btn btn-outline-primary">2. Jana Separuh Akhir</button>
            </form>
            <form method="post" action="{{ route('matches.third-place', $selectedSport) }}">
                @csrf
                <input type="hidden" name="gender" value="{{ $gender->value }}">
                <button class="btn btn-outline-secondary">3. Jana Tempat Ketiga</button>
            </form>
            <form method="post" action="{{ route('matches.finals', $selectedSport) }}">
                @csrf
                <input type="hidden" name="gender" value="{{ $gender->value }}">
                <button class="btn btn-dark">4. Jana Final</button>
            </form>
        </div>

        <div class="alert alert-light border mb-4">
            Mulakan dengan Round Robin. Selepas semua enam keputusan mempunyai pemenang, jana Separuh Akhir. Selesaikan kedua-dua separuh akhir sebelum menjana Tempat Ketiga dan Final.
        </div>

        <section class="mb-5">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h2 class="h4 fw-bold mb-1">Round Robin</h2>
                    <div class="text-secondary small">Setiap rumah bertemu sekali — 6 perlawanan keseluruhan.</div>
                </div>
                <span class="badge text-bg-dark px-3 py-2">{{ $leagueMatches->whereNotNull('result.winner_house_id')->count() }} / 6 selesai</span>
            </div>

            <div class="row g-4">
                @forelse ($leagueMatches as $match)
                    <div class="col-md-6 col-xl-4">
                        <x-knockout-match :match="$match" :title="'Perlawanan Liga '.$match->match_no" />
                    </div>
                @empty
                    <div class="col-12">
                        <div class="border rounded-4 text-center text-secondary py-5 bg-white">
                            Round Robin belum dijana. Tekan <strong>1. Jana Round Robin</strong> untuk bermula.
                        </div>
                    </div>
                @endforelse
            </div>
        </section>

        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="flex-grow-1 border-top"></div>
            <span class="text-uppercase fw-bold text-secondary small" style="letter-spacing: .12em;">Bracket Knockout</span>
            <div class="flex-grow-1 border-top"></div>
        </div>

        <div class="row g-4 align-items-center">
            <div class="col-lg-4">
                <h2 class="h5 fw-bold mb-3">Separuh Akhir</h2>
                <div class="d-grid gap-4">
                    <x-knockout-match :match="$semiFinals->get(0)" title="Separuh Akhir 1" />
                    <x-knockout-match :match="$semiFinals->get(1)" title="Separuh Akhir 2" />
                </div>
            </div>
            <div class="col-lg-4">
                <h2 class="h5 fw-bold mb-3">Final</h2>
                <x-knockout-match :match="$final" title="Final" />
            </div>
            <div class="col-lg-4">
                <h2 class="h5 fw-bold mb-3">Penentuan Kedudukan</h2>
                <x-knockout-match :match="$thirdPlace" title="Tempat Ketiga" />
            </div>
        </div>
    @else
        <div class="alert alert-info">Tiada sukan liga tersedia.</div>
    @endif
</x-layouts.app>

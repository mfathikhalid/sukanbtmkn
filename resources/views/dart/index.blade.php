<x-layouts.app :title="'Dart 501 | Sukan BTMKN'">
    <div class="rounded-5 p-4 p-lg-5 text-white mb-4" style="background: linear-gradient(135deg, #052e16, #15803d);">
        <div class="text-uppercase fw-bold text-warning small mb-2" style="letter-spacing: .14em;">Dart Berpasukan</div>
        <h1 class="display-6 fw-bold mb-2">Dart 501</h1>
        <p class="text-white-50 mb-0">Tiga peserta bermain sebagai satu pasukan rumah dalam satu permainan 501. Pilih rumah pemenang selepas permainan selesai.</p>
    </div>

    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="get" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Kategori</label>
                    <select name="gender" class="form-select">
                        <option value="{{ \App\Enums\Gender::Male->value }}" @selected($gender === \App\Enums\Gender::Male)>Lelaki</option>
                        <option value="{{ \App\Enums\Gender::Female->value }}" @selected($gender === \App\Enums\Gender::Female)>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-3 d-grid"><button class="btn btn-dark">Papar</button></div>
            </form>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2 mb-4">
        @foreach ([
            ['route' => 'matches.league-fixtures', 'label' => '1. Jana Round Robin', 'class' => 'btn-primary'],
            ['route' => 'matches.semi-finals', 'label' => '2. Jana Separuh Akhir', 'class' => 'btn-outline-primary'],
            ['route' => 'matches.third-place', 'label' => '3. Jana Tempat Ketiga', 'class' => 'btn-outline-secondary'],
            ['route' => 'matches.finals', 'label' => '4. Jana Final', 'class' => 'btn-dark'],
        ] as $action)
            <form method="post" action="{{ route($action['route'], $sport) }}">
                @csrf
                <input type="hidden" name="gender" value="{{ $gender->value }}">
                <button class="btn {{ $action['class'] }}">{{ $action['label'] }}</button>
            </form>
        @endforeach
    </div>

    <section class="mb-5">
        <h2 class="h4 fw-bold mb-3">Round Robin</h2>
        <div class="row g-4">
            @forelse ($leagueMatches as $match)
                <div class="col-md-6 col-xl-4"><x-dart-match :match="$match" :title="'Perlawanan Liga '.$match->match_no" /></div>
            @empty
                <div class="col-12"><div class="border rounded-4 text-center text-secondary py-5">Round Robin belum dijana.</div></div>
            @endforelse
        </div>
    </section>

    <div class="row g-4 align-items-center">
        <div class="col-lg-4">
            <h2 class="h5 fw-bold mb-3">Separuh Akhir</h2>
            <div class="d-grid gap-4">
                <x-dart-match :match="$semiFinals->get(0)" title="Separuh Akhir 1" />
                <x-dart-match :match="$semiFinals->get(1)" title="Separuh Akhir 2" />
            </div>
        </div>
        <div class="col-lg-4"><h2 class="h5 fw-bold mb-3">Final</h2><x-dart-match :match="$final" title="Final" /></div>
        <div class="col-lg-4"><h2 class="h5 fw-bold mb-3">Kedudukan</h2><x-dart-match :match="$thirdPlace" title="Tempat Ketiga" /></div>
    </div>
</x-layouts.app>

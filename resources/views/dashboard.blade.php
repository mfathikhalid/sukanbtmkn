<x-layouts.app :title="'Dashboard | Sukan BTMKN'">
    @php
        $eventRoutes = [
            'FIFA' => 'events.fifa',
            'Tekken' => 'events.tekken',
            'Pickleball' => 'events.pickleball',
            'Congkak' => 'events.congkak',
            'Carrom' => 'events.carrom',
            'Dart' => 'dart.index',
            'Bowling' => 'bowling.index',
        ];
    @endphp

    <div class="rounded-5 p-4 p-lg-5 mb-4 text-white overflow-hidden" style="background: linear-gradient(135deg, #020617, #1e3a8a 65%, #2563eb); box-shadow: 0 24px 60px rgba(30, 58, 138, .22);">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="text-uppercase fw-bold text-info small mb-2" style="letter-spacing: .16em;">Pusat Kawalan Karnival</div>
                <h1 class="display-5 fw-bold mb-2">Dashboard Sukan BTMKN</h1>
                <p class="text-white-50 fs-5 mb-4">Pantau kemajuan acara, keputusan pertandingan dan kedudukan rumah secara langsung.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('scoreboard.index') }}" class="btn btn-light px-4">Lihat Scoreboard</a>
                    <a href="{{ route('live.index') }}" target="_blank" class="btn btn-outline-light px-4">Buka Paparan Awam</a>
                </div>
            </div>
            @if ($leader)
                <div class="col-lg-4">
                    <div class="rounded-4 p-4 bg-white bg-opacity-10 border border-white border-opacity-10">
                        <div class="text-white-50 small mb-2">Pendahulu semasa</div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="rounded-circle border border-2 border-white" style="width: 1.5rem; height: 1.5rem; background: {{ $leader['house']->color }}"></span>
                            <div><div class="fs-4 fw-bold">Rumah {{ $leader['house']->name }}</div><div class="text-white-50">{{ $leader['points'] }} mata keseluruhan</div></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row g-3 mb-4">
        @foreach ([
            ['label' => 'Jumlah Peserta', 'value' => $totalParticipants, 'color' => 'primary'],
            ['label' => 'Jumlah Acara', 'value' => $totalEvents, 'color' => 'dark'],
            ['label' => 'Perlawanan Selesai', 'value' => $completedMatches, 'color' => 'success'],
            ['label' => 'Perlawanan Tertangguh', 'value' => $pendingMatches, 'color' => 'warning'],
        ] as $stat)
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="small text-secondary mb-2">{{ $stat['label'] }}</div>
                        <div class="display-6 fw-bold text-{{ $stat['color'] }}">{{ $stat['value'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div><div class="text-primary small fw-bold text-uppercase">Live</div><h2 class="h4 fw-bold mb-0">Kedudukan Rumah</h2></div>
                        <a href="{{ route('scoreboard.index') }}" class="btn btn-sm btn-outline-dark">Butiran</a>
                    </div>
                    <div class="d-grid gap-2">
                        @foreach ($houseRankings as $index => $row)
                            <div class="d-flex align-items-center gap-3 border rounded-4 p-3">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle fw-bold {{ $index === 0 ? 'rank-gold' : ($index === 1 ? 'rank-silver' : ($index === 2 ? 'rank-bronze' : 'text-bg-dark')) }}" style="width: 2rem; height: 2rem;">{{ $index + 1 }}</span>
                                <span class="rounded-circle border" style="width: .9rem; height: .9rem; background: {{ $row['house']->color }}"></span>
                                <span class="fw-semibold flex-grow-1">Rumah {{ $row['house']->name }}</span>
                                <span class="fs-5 fw-bold">{{ $row['points'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div><div class="text-primary small fw-bold text-uppercase">Pertandingan</div><h2 class="h4 fw-bold mb-0">Kemajuan Keseluruhan</h2></div>
                        <span class="fs-4 fw-bold">{{ $matchProgress }}%</span>
                    </div>
                    <div class="progress mb-4" style="height: .75rem;"><div class="progress-bar bg-success" style="width: {{ $matchProgress }}%"></div></div>

                    @if ($participantsWithoutEvents > 0)
                        <div class="alert alert-warning d-flex justify-content-between align-items-center mb-0">
                            <span><strong>{{ $participantsWithoutEvents }}</strong> peserta belum menyertai sebarang acara.</span>
                            <a href="{{ route('registrations.index') }}" class="btn btn-sm btn-warning">Urus Pendaftaran</a>
                        </div>
                    @else
                        <div class="alert alert-success mb-0">Semua peserta telah menyertai sekurang-kurangnya satu acara.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                <div><div class="text-primary small fw-bold text-uppercase">Acara</div><h2 class="h4 fw-bold mb-0">Kemajuan Setiap Acara</h2></div>
                <span class="text-secondary small">Dikemas kini secara langsung</span>
            </div>
            <div class="row g-3">
                @foreach ($eventProgress as $event)
                    <div class="col-md-6 col-xl-4">
                        <a href="{{ route($eventRoutes[$event['sport']->name]) }}" class="text-decoration-none text-dark">
                            <div class="border rounded-4 p-3 h-100">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div><div class="fw-bold">{{ $event['sport']->name }}</div><div class="small text-secondary">{{ $event['completed'] }} / {{ $event['total'] }} selesai</div></div>
                                    <span class="badge {{ $event['complete'] ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $event['complete'] ? 'Lengkap' : $event['percentage'].'%' }}</span>
                                </div>
                                <div class="progress" style="height: .45rem;"><div class="progress-bar {{ $event['complete'] ? 'bg-success' : 'bg-primary' }}" style="width: {{ $event['percentage'] }}%"></div></div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3">Tindakan Pantas</h2>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('participants.create') }}" class="btn btn-dark">Tambah Peserta</a>
                <a href="{{ route('registrations.create') }}" class="btn btn-outline-dark">Daftar Acara</a>
                <a href="{{ route('scoreboard.index') }}" class="btn btn-outline-primary">Scoreboard</a>
                <a href="{{ route('live.index') }}" target="_blank" class="btn btn-outline-success">Paparan Awam Live</a>
                <button type="button" class="btn btn-outline-danger ms-sm-auto" data-bs-toggle="modal" data-bs-target="#resetMatchesModal" @disabled($totalMatches === 0)>
                    Reset Semua Perlawanan
                </button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="resetMatchesModal" tabindex="-1" aria-labelledby="resetMatchesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 px-4 pt-4 pb-2">
                    <h2 class="modal-title h5 fw-bold text-danger" id="resetMatchesModalLabel">Reset semua perlawanan?</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body px-4">
                    <p class="mb-2">Semua perlawanan Round Robin, Knockout dan keputusan yang direkodkan akan dipadam.</p>
                    <p class="small text-secondary mb-0">Peserta dan pendaftaran acara tidak akan terjejas. Tindakan ini tidak boleh dibatalkan.</p>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <form method="post" action="{{ route('matches.reset') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Ya, Reset Semua</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

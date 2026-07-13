<x-layouts.app :title="'Boling | Sukan BTMKN'">
    @php($bowlingSport = $sports->first())

    <div class="rounded-5 p-4 p-lg-5 text-white mb-4 overflow-hidden position-relative" data-event-hero="true" style="background: linear-gradient(135deg, #083344, #0891b2); box-shadow: 0 1.5rem 3rem rgba(8, 145, 178, .2);">
        <div class="position-absolute rounded-circle border border-5 border-white border-opacity-10" style="width: 12rem; height: 12rem; right: -4rem; top: -5rem;"></div>
        <div class="position-relative d-flex flex-wrap justify-content-between align-items-end gap-3">
            <div>
                <div class="text-uppercase fw-bold text-warning small mb-2" style="letter-spacing: .14em;">Boling Dua Game</div>
                <h1 class="display-6 fw-bold mb-2">Skor Bowling</h1>
                <p class="text-white-50 mb-0">Simpan skor Game 1 dahulu, kemudian kemas kini Game 2 apabila selesai.</p>
            </div>
            <span class="badge {{ $isComplete ? 'text-bg-success' : 'text-bg-warning' }} px-3 py-2">
                {{ $playerTotals->filter(fn ($row) => $row['game_1'] !== null && $row['game_2'] !== null)->count() }} / {{ $playerTotals->count() }} pemain lengkap
            </span>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="alert {{ $isComplete ? 'alert-success' : 'alert-warning' }} mb-4">
        {{ $isComplete
            ? 'Semua pemain telah melengkapkan kedua-dua game. Mata boling kini dikira berdasarkan jumlah pin tertinggi.'
            : 'Mata boling hanya diberikan selepas semua pemain melengkapkan kedua-dua game.' }}
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white border-0 p-4 pb-2">
            <h2 class="h4 fw-bold mb-1">Jadual Pemain</h2>
            <div class="text-secondary">Peserta diambil secara automatik daripada pendaftaran acara boling.</div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Peserta</th>
                        <th>Rumah</th>
                        <th style="min-width: 140px;">Game 1</th>
                        <th style="min-width: 140px;">Game 2</th>
                        <th class="text-center">Jumlah Pin</th>
                        <th class="text-end pe-4">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($playerTotals as $row)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $row['participant']->name }}</td>
                            <td>
                                <span class="d-inline-flex align-items-center gap-2">
                                    <span class="rounded-circle border" style="width: .8rem; height: .8rem; background: {{ $row['participant']->house?->color }}"></span>
                                    {{ $row['participant']->house?->name }}
                                </span>
                            </td>
                            <td>
                                <input form="bowling-player-{{ $row['participant']->id }}" type="number" min="0" name="game_1" value="{{ $row['game_1'] }}" class="form-control" placeholder="Belum dimainkan">
                            </td>
                            <td>
                                <input form="bowling-player-{{ $row['participant']->id }}" type="number" min="0" name="game_2" value="{{ $row['game_2'] }}" class="form-control" placeholder="Belum dimainkan">
                            </td>
                            <td class="text-center fs-5 fw-bold">{{ number_format($row['total']) }}</td>
                            <td class="text-end pe-4">
                                <form id="bowling-player-{{ $row['participant']->id }}" method="post" action="{{ route('bowling.store') }}">
                                    @csrf
                                    <input type="hidden" name="sport_id" value="{{ $bowlingSport?->id }}">
                                    <input type="hidden" name="participant_id" value="{{ $row['participant']->id }}">
                                    <button class="btn btn-sm btn-dark px-3" type="submit">{{ $row['game_1'] !== null || $row['game_2'] !== null ? 'Kemas Kini' : 'Simpan' }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="fw-semibold mb-1">Tiada peserta boling berdaftar</div>
                                <div class="text-secondary">Daftarkan peserta ke acara Boling untuk memaparkan jadual ini.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h2 class="h4 fw-bold mb-3">Jumlah Jatuhan Pin Rumah</h2>
            <div class="row g-3">
                @forelse ($houseTotals as $houseId => $total)
                    @php($house = $playerTotals->pluck('participant.house')->firstWhere('id', $houseId))
                    <div class="col-sm-6 col-lg-3">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="d-flex align-items-center gap-2 text-secondary mb-2">
                                <span class="rounded-circle border" style="width: .8rem; height: .8rem; background: {{ $house?->color }}"></span>
                                Rumah {{ $house?->name }}
                            </div>
                            <div class="fs-3 fw-bold">{{ number_format($total) }}</div>
                            <div class="small text-secondary">jumlah pin</div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-secondary">Belum ada skor direkodkan.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app>

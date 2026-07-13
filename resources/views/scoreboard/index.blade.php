<x-layouts.app :title="'Papan Skor | Sukan BTMKN'">
    @php
        $leader = $standings->first();
        $highestPoints = max(1, (int) ($leader['points'] ?? 0));
    @endphp

    <div class="rounded-5 p-4 p-lg-5 mb-4 text-white overflow-hidden position-relative" style="background: linear-gradient(135deg, #111827 0%, #1e3a8a 60%, #2563eb 100%); box-shadow: 0 24px 60px rgba(30, 58, 138, .22);">
        <div class="position-relative" style="z-index: 1;">
            <div class="text-uppercase fw-bold text-info mb-2" style="font-size: .75rem; letter-spacing: .16em;">Kedudukan Semasa</div>
            <div class="row align-items-end g-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-2">Papan Skor Keseluruhan</h1>
                    <p class="text-white-50 fs-5 mb-0">Mata diberikan selepas acara selesai: tempat pertama 10, kedua 7, ketiga 5 dan keempat 3.</p>
                </div>
                @if ($leader)
                    <div class="col-lg-4 text-lg-end">
                        <div class="text-white-50 small mb-1">Pendahulu</div>
                        <div class="d-inline-flex align-items-center gap-3 bg-white bg-opacity-10 rounded-4 px-4 py-3">
                            <span class="rounded-circle border border-2 border-white" style="width: 1.25rem; height: 1.25rem; background: {{ $leader['house']->color }}"></span>
                            <div class="text-start">
                                <div class="fw-bold fs-5">{{ $leader['house']->name }}</div>
                                <div class="text-white-50">{{ $leader['points'] }} mata</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        @foreach ($standings->take(3) as $index => $row)
            @php
                $podiumClass = match ($index) { 0 => 'podium-gold', 1 => 'podium-silver', 2 => 'podium-bronze' };
                $rankClass = match ($index) { 0 => 'rank-gold', 1 => 'rank-silver', 2 => 'rank-bronze' };
            @endphp
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 {{ $podiumClass }}">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle fw-bold {{ $rankClass }}" style="width: 2.5rem; height: 2.5rem;">{{ $index + 1 }}</span>
                            <span class="display-6 fw-bold">{{ $row['points'] }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="rounded-circle border" style="width: 1rem; height: 1rem; background: {{ $row['house']->color }}"></span>
                            <h2 class="h5 fw-bold mb-0">Rumah {{ $row['house']->name }}</h2>
                        </div>
                        <div class="progress" style="height: .5rem;" role="progressbar" aria-label="Mata {{ $row['house']->name }}" aria-valuenow="{{ $row['points'] }}" aria-valuemin="0" aria-valuemax="{{ $highestPoints }}">
                            <div class="progress-bar" style="width: {{ ($row['points'] / $highestPoints) * 100 }}%; background: {{ $row['house']->color }}"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 p-4 pb-2">
            <h2 class="h4 fw-bold mb-1">Pecahan Mata</h2>
            <div class="text-secondary">Jumlah kemenangan bagi setiap peringkat dan keputusan boling.</div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kedudukan</th>
                        <th>Rumah</th>
                        <th class="text-center">Round Robin</th>
                        <th class="text-center">Knockout</th>
                        <th class="text-center">Mata Acara</th>
                        <th class="text-center">Mata Boling</th>
                        <th class="text-center">Jumlah Pin</th>
                        <th class="text-end pe-4">Jumlah Mata</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($standings as $index => $row)
                        <tr>
                            <td class="ps-4"><span class="badge rounded-pill text-bg-dark">#{{ $index + 1 }}</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2 fw-semibold">
                                    <span class="rounded-circle border" style="width: .85rem; height: .85rem; background: {{ $row['house']->color }}"></span>
                                    {{ $row['house']->name }}
                                </div>
                            </td>
                            <td class="text-center">{{ $row['round_robin_wins'] }}</td>
                            <td class="text-center">{{ $row['knockout_wins'] }}</td>
                            <td class="text-center fw-semibold">{{ $row['event_points'] }}</td>
                            <td class="text-center">{{ $row['bowling_points'] }}</td>
                            <td class="text-center text-secondary">{{ number_format($row['bowling_total']) }}</td>
                            <td class="text-end pe-4"><span class="fs-4 fw-bold">{{ $row['points'] }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mt-4">
        <div class="card-header bg-white border-0 p-4 pb-2">
            <h2 class="h4 fw-bold mb-1">Pecahan Mata Mengikut Acara</h2>
            <div class="text-secondary">Mata 10, 7, 5 dan 3 diberikan selepas setiap acara selesai.</div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Acara</th>
                        <th>Kategori</th>
                        @foreach ($standings as $row)
                            <th class="text-center">
                                <span class="d-inline-flex align-items-center gap-2">
                                    <span class="rounded-circle border" style="width: .7rem; height: .7rem; background: {{ $row['house']->color }}"></span>
                                    {{ $row['house']->name }}
                                </span>
                            </th>
                        @endforeach
                        <th class="text-center">Jumlah Acara</th>
                        <th class="text-end pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($eventBreakdown as $event)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $event['event'] }}</td>
                            <td class="text-secondary">{{ $event['category'] }}</td>
                            @foreach ($standings as $row)
                                <td class="text-center">
                                    @if ($event['complete'])
                                        <span class="fw-bold">{{ $event['points'][$row['house']->id] ?? 0 }}</span>
                                    @else
                                        <span class="text-secondary">—</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="text-center fw-bold">
                                {{ $event['complete'] ? $event['points']->sum() : '—' }}
                            </td>
                            <td class="text-end pe-4">
                                <span class="badge {{ $event['complete'] ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ $event['complete'] ? 'Selesai' : 'Belum selesai' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th class="ps-4" colspan="2">Jumlah Mata Keseluruhan</th>
                        @foreach ($standings as $row)
                            <th class="text-center fs-5">{{ $row['points'] }}</th>
                        @endforeach
                        <th class="text-center fs-5">{{ $standings->sum('points') }}</th>
                        <th class="text-end pe-4"><span class="badge text-bg-dark">Semua Acara</span></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-layouts.app>

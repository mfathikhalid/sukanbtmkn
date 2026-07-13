<x-layouts.app :title="'Dashboard | Sukan BTMKN'">
    <div class="shell rounded-5 p-4 p-lg-5" style="backdrop-filter: blur(18px); background: rgba(255, 255, 255, 0.84); border: 1px solid rgba(18, 24, 38, 0.12); box-shadow: 0 24px 70px rgba(15, 23, 42, 0.12);">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="text-uppercase fw-bold mb-3" style="letter-spacing: 0.18em; color: #f97316; font-size: 0.75rem;">Sistem Karnival Sukan</div>
                <h1 class="display-5 fw-bold mb-3">Sukan BTMKN telah disediakan untuk rumah, peserta, acara, dan pemarkahan.</h1>
                <p class="lead text-secondary mb-4">
                    Lapisan sistem pertama sudah sedia: rumah tetap, sukan tetap, tetapan mata, pendaftaran, perlawanan liga, dan skor boling.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <div class="border rounded-4 px-4 py-3 bg-white">
                        <div class="small text-secondary">Jumlah peserta</div>
                        <div class="fw-bold fs-4">{{ $totalParticipants }}</div>
                    </div>
                    <div class="border rounded-4 px-4 py-3 bg-white">
                        <div class="small text-secondary">Jumlah acara</div>
                        <div class="fw-bold fs-4">{{ $totalEvents }}</div>
                    </div>
                    <div class="border rounded-4 px-4 py-3 bg-white">
                        <div class="small text-secondary">Tanpa acara</div>
                        <div class="fw-bold fs-4">{{ $participantsWithoutEvents }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="border rounded-4 p-4 h-100 bg-white">
                    <div class="fw-semibold mb-3">Struktur semasa</div>
                    <div class="d-grid gap-2">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                            <span>Akses admin</span>
                            <span class="badge text-bg-dark">Sedia</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                            <span>Jumlah perlawanan</span>
                            <span class="badge text-bg-secondary">{{ $totalMatches }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                            <span>Perlawanan selesai</span>
                            <span class="badge text-bg-secondary">{{ $completedMatches }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                            <span>Perlawanan tertangguh</span>
                            <span class="badge text-bg-secondary">{{ $pendingMatches }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Kedudukan rumah</span>
                            <span class="badge text-bg-secondary">{{ count($houseRankings) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-2 mt-lg-4">
            <div class="col-lg-6">
                <div class="border rounded-4 p-4 h-100 bg-white">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h5 mb-0">Rumah</h2>
                        <span class="text-secondary small">Rekod tetap</span>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($houses as $house)
                            <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill border">
                                <span class="rounded-circle" style="width: 0.75rem; height: 0.75rem; background: {{ $house->color }}"></span>
                                {{ $house->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="border rounded-4 p-4 h-100 bg-white">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h5 mb-0">Sukan</h2>
                        <span class="text-secondary small">Liga dan boling</span>
                    </div>
                    <div class="row g-2">
                        @foreach ($sports as $sport)
                            <div class="col-sm-6">
                                <div class="border rounded-4 p-3 h-100 bg-white">
                                    <div class="fw-semibold">{{ $sport->name }}</div>
                                    <div class="text-secondary small">{{ $sport->type->label() }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

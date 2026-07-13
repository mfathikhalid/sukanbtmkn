<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Peserta | Sukan BTMKN</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; font-family: 'Manrope', sans-serif; background: radial-gradient(circle at 7% 18%, rgba(249,115,22,.12), transparent 24%), radial-gradient(circle at 94% 72%, rgba(37,99,235,.12), transparent 26%), #f8fafc; color: #0f172a; }
        .hero { position: relative; overflow: hidden; background: radial-gradient(circle at 84% 16%, rgba(250,204,21,.32), transparent 18%), radial-gradient(circle at 10% 90%, rgba(249,115,22,.34), transparent 24%), linear-gradient(120deg, #020617, #1e3a8a 54%, #2563eb); }
        .hero::after { content: ''; position: absolute; inset: auto 0 0; height: .6rem; background: repeating-linear-gradient(90deg, #f97316 0 12.5%, #facc15 12.5% 25%, #22c55e 25% 37.5%, #38bdf8 37.5% 50%, #f97316 50% 62.5%, #facc15 62.5% 75%, #22c55e 75% 87.5%, #38bdf8 87.5% 100%); }
        .form-card { margin-top: -2.75rem; position: relative; z-index: 2; }
        .sport-option { cursor: pointer; border: 1px solid #e2e8f0; transition: border-color .2s ease, transform .2s ease, background .2s ease; }
        .sport-option:hover { transform: translateY(-2px); border-color: #93c5fd; }
        .sport-option:has(input:checked) { border-color: #2563eb; background: #eff6ff; box-shadow: 0 0 0 .2rem rgba(37,99,235,.1); }
        .sport-option.is-disabled { opacity: .45; cursor: not-allowed; transform: none; }
        .form-control, .form-select { min-height: 3rem; }
    </style>
</head>
<body>
    <header class="hero text-white pt-5 pb-5">
        <div class="container pb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-4">
                <div>
                    <div class="d-inline-flex bg-white bg-opacity-10 border border-white border-opacity-25 rounded-pill px-3 py-2 fw-bold text-uppercase small mb-3" style="letter-spacing: .1em;">🏅 Karnival Sukan BTMKN 2026</div>
                    <h1 class="display-5 fw-bold mb-2">Pendaftaran Peserta</h1>
                    <p class="text-white-50 fs-5 mb-0">Pilih rumah, nama peserta dan acara yang ingin disertai.</p>
                    <div class="mt-3"><span class="badge rounded-pill {{ $registrationIsOpen ? 'text-bg-success' : 'text-bg-warning' }} px-3 py-2">{{ $registrationIsOpen ? 'Pendaftaran dibuka' : 'Dibuka 14 Julai 2026' }}</span></div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('public-participants.index') }}" class="btn btn-outline-light rounded-pill">Senarai Peserta</a>
                    <a href="{{ route('schedule.index') }}" class="btn btn-outline-light rounded-pill">Jadual</a>
                    <a href="{{ route('live.index') }}" class="btn btn-light rounded-pill">Keputusan Live</a>
                </div>
            </div>
        </div>
    </header>

    <main class="container pb-5">
        <div class="form-card card border-0 shadow-lg rounded-4 mx-auto" style="max-width: 960px;">
            <div class="card-body p-4 p-lg-5">
                @if (session('success'))
                    <div class="alert alert-success rounded-4 mb-4">
                        <div class="fw-bold">Pendaftaran berjaya!</div>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger rounded-4 mb-4">
                        <div class="fw-bold mb-1">Sila semak maklumat berikut:</div>
                        <ul class="mb-0 ps-3">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                @unless ($registrationIsOpen)
                    <div class="alert alert-warning rounded-4 mb-4">
                        <div class="fw-bold">Pendaftaran belum dibuka</div>
                        <div>Pendaftaran acara boleh dibuat mulai <strong>14 Julai 2026</strong>.</div>
                    </div>
                @endunless

                <form method="post" action="{{ route('public-registration.store') }}">
                    @csrf
                    <fieldset @disabled(! $registrationIsOpen)>
                    <div class="mb-5">
                        <div class="text-primary fw-bold small text-uppercase mb-1">Langkah 1</div>
                        <h2 class="h4 fw-bold mb-3">Pilih Peserta</h2>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Rumah</label>
                                <select name="house_id" id="registration-house" class="form-select" required>
                                    <option value="">Pilih rumah</option>
                                    @foreach ($houses as $house)<option value="{{ $house->id }}" @selected((string) old('house_id') === (string) $house->id)>{{ $house->name }}</option>@endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama peserta</label>
                                <select name="participant_id" id="registration-participant" class="form-select" required>
                                    <option value="">Pilih peserta</option>
                                    @foreach ($participants as $participant)
                                        <option
                                            value="{{ $participant->id }}"
                                            data-house="{{ $participant->house_id }}"
                                            data-gender="{{ $participant->gender->value }}"
                                            data-registered='@json($participant->sports->pluck('id')->values())'
                                            @selected((string) old('participant_id') === (string) $participant->id)
                                        >{{ $participant->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Nama dipaparkan mengikut rumah yang dipilih.</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="text-primary fw-bold small text-uppercase mb-1">Langkah 2</div>
                        <h2 class="h4 fw-bold mb-1">Pilih Acara</h2>
                        <p class="text-secondary mb-3">Anda boleh memilih lebih daripada satu acara. Acara sedia didaftarkan tidak boleh dipilih semula.</p>
                        <div class="row g-3" id="sport-options">
                            @foreach ($sports as $sport)
                                <div class="col-sm-6 col-lg-4">
                                    <label class="sport-option d-flex align-items-start gap-3 rounded-4 p-3 h-100" data-male="{{ $sport->male_quota }}" data-female="{{ $sport->female_quota }}">
                                        <input class="form-check-input mt-1" type="checkbox" name="sport_ids[]" value="{{ $sport->id }}" @checked(in_array($sport->id, old('sport_ids', [])))>
                                        <span><span class="d-block fw-bold">{{ $sport->name }}</span><span class="availability small text-secondary">Pilih jantina dahulu</span></span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 border-top pt-4">
                        <div class="small text-secondary">Pastikan semua maklumat adalah tepat sebelum dihantar.</div>
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">Hantar Pendaftaran</button>
                    </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </main>

    <script>
        (() => {
            const house = document.getElementById('registration-house');
            const participant = document.getElementById('registration-participant');
            const participantOptions = [...participant.options].slice(1);
            const sportOptions = document.querySelectorAll('.sport-option');

            const updateSports = () => {
                const selectedParticipant = participant.selectedOptions[0];
                const selectedGender = selectedParticipant?.dataset.gender?.toLowerCase() ?? '';
                const registered = JSON.parse(selectedParticipant?.dataset.registered ?? '[]').map(Number);
                sportOptions.forEach((option) => {
                    const checkbox = option.querySelector('input');
                    const availability = option.querySelector('.availability');
                    const quota = selectedGender ? Number(option.dataset[selectedGender]) : 0;
                    const unavailable = selectedGender !== '' && quota === 0;
                    const alreadyRegistered = registered.includes(Number(checkbox.value));
                    checkbox.disabled = selectedGender === '' || unavailable || alreadyRegistered;
                    if (checkbox.disabled) checkbox.checked = false;
                    option.classList.toggle('is-disabled', checkbox.disabled);
                    availability.textContent = selectedGender === ''
                        ? 'Pilih peserta dahulu'
                        : alreadyRegistered
                            ? 'Telah didaftarkan'
                            : unavailable
                                ? 'Tidak tersedia untuk peserta ini'
                                : `Kuota: ${quota} peserta`;
                });
            };

            const updateParticipants = () => {
                const selectedHouse = house.value;
                participantOptions.forEach((option) => {
                    const visible = selectedHouse !== '' && option.dataset.house === selectedHouse;
                    option.hidden = !visible;
                    option.disabled = !visible;
                });
                if (participant.selectedOptions[0]?.disabled) participant.value = '';
                updateSports();
            };

            house.addEventListener('change', updateParticipants);
            participant.addEventListener('change', updateSports);
            updateParticipants();
        })();
    </script>
</body>
</html>

<x-layouts.app :title="'Tambah Pendaftaran | Sukan BTMKN'">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h1 class="h3 fw-bold mb-4">Tambah Pendaftaran</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ route('registrations.store') }}" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Sukan</label>
                    <select name="sport_id" class="form-select" required>
                        <option value="">Pilih sukan</option>
                        @foreach ($sports as $sport)
                            <option value="{{ $sport->id }}" @selected(old('sport_id') == $sport->id)>{{ $sport->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Rumah</label>
                    <select id="house_id" name="house_id" class="form-select" required>
                        <option value="">Pilih rumah</option>
                        @foreach ($houses as $house)
                            <option value="{{ $house->id }}" @selected(old('house_id') == $house->id)>{{ $house->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Peserta</label>
                    <select id="participant_id" name="participant_id" class="form-select" required>
                        <option value="">Pilih peserta</option>
                    </select>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-dark">Simpan</button>
                    <a href="{{ route('registrations.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const allParticipants = @json($participants);
        const houseSelect = document.getElementById('house_id');
        const participantSelect = document.getElementById('participant_id');

        function updateParticipants() {
            const selectedHouseId = parseInt(houseSelect.value);
            participantSelect.innerHTML = '<option value="">Pilih peserta</option>';

            if (!selectedHouseId) return;

            const filtered = allParticipants.filter(p => p.house_id === selectedHouseId);
            filtered.forEach(participant => {
                const option = document.createElement('option');
                option.value = participant.id;
                option.textContent = participant.name;
                if ('{{ old('participant_id') }}' == participant.id) {
                    option.selected = true;
                }
                participantSelect.appendChild(option);
            });
        }

        houseSelect.addEventListener('change', updateParticipants);

        // Initialize on page load if house is pre-selected
        if (houseSelect.value) {
            updateParticipants();
        }
    </script>
</x-layouts.app>
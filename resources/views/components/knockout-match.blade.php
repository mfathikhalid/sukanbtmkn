@props(['match', 'title'])

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
        <span class="fw-semibold">{{ $title }}</span>
        @if ($match)
            <span class="badge {{ $match->result?->winner_house_id ? 'text-bg-success' : 'text-bg-warning' }}">
                {{ $match->result?->winner_house_id ? 'Selesai' : 'Tertangguh' }}
            </span>
        @endif
    </div>
    <div class="card-body p-3 p-lg-4">
        @if ($match)
            @foreach ([$match->homeHouse, $match->awayHouse] as $house)
                <div class="d-flex align-items-center justify-content-between gap-3 py-2 {{ ! $loop->last ? 'border-bottom' : '' }}">
                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle border" style="width: .8rem; height: .8rem; background: {{ $house->color }}"></span>
                        <span class="fw-semibold">{{ $house->name }}</span>
                    </div>
                    @if ($match->result?->winner_house_id === $house->id)
                        <span class="badge text-bg-success">Menang</span>
                    @elseif ($match->result)
                        <span class="badge text-bg-secondary">Kalah</span>
                    @endif
                </div>
            @endforeach

            @if ($match->result?->winnerHouse)
                <div class="alert alert-success py-2 mt-3 mb-0">
                    Pemenang: <strong>{{ $match->result->winnerHouse->name }}</strong>
                </div>
            @else
                <form action="{{ route('matches.update', $match) }}" method="post" class="row g-2 mt-2">
                    @csrf
                    @method('put')
                    <div class="col-10">
                        <select name="winner_house_id" class="form-select" required>
                            <option value="">Pilih pemenang</option>
                            <option value="{{ $match->homeHouse->id }}">{{ $match->homeHouse->name }}</option>
                            <option value="{{ $match->awayHouse->id }}">{{ $match->awayHouse->name }}</option>
                        </select>
                    </div>
                    <div class="col-2 d-grid">
                        <button class="btn btn-primary" type="submit">✓</button>
                    </div>
                </form>
            @endif
        @else
            <div class="text-secondary text-center py-4">Belum dijana</div>
        @endif
    </div>
</div>

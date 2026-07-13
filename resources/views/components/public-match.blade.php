@props(['match', 'title'])

<div class="public-match rounded-4 border p-3 h-100">
    <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
        <div class="fw-bold small text-uppercase">{{ $title }}</div>
        <span class="badge {{ $match?->result?->winner_house_id ? 'text-bg-success' : 'text-bg-secondary' }}">
            {{ $match?->result?->winner_house_id ? 'Selesai' : 'Belum' }}
        </span>
    </div>
    @if ($match)
        @foreach ([$match->homeHouse, $match->awayHouse] as $house)
            <div class="d-flex justify-content-between align-items-center py-2 {{ ! $loop->last ? 'border-bottom' : '' }}">
                <span class="d-flex align-items-center gap-2 fw-semibold">
                    <span class="rounded-circle border" style="width: .75rem; height: .75rem; background: {{ $house->color }}"></span>
                    {{ $house->name }}
                </span>
                @if ($match->result?->winner_house_id === $house->id)
                    <span class="badge text-bg-success">Menang</span>
                @elseif ($match->result)
                    <span class="text-secondary small">Kalah</span>
                @endif
            </div>
        @endforeach
    @else
        <div class="text-secondary text-center py-3">Belum dijana</div>
    @endif
</div>

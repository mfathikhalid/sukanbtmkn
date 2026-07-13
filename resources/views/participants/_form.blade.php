@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Rumah</label>
        <select name="house_id" class="form-select" required>
            <option value="">Pilih rumah</option>
            @foreach ($houses as $house)
                <option value="{{ $house->id }}" @selected(old('house_id', $participant->house_id ?? '') == $house->id)>{{ $house->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Nama</label>
        <input type="text" name="name" value="{{ old('name', $participant->name ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Jantina</label>
        <select name="gender" class="form-select" required>
            <option value="">Pilih jantina</option>
            @foreach (['Male', 'Female'] as $gender)
                <option value="{{ $gender }}" @selected(old('gender', $participant?->gender?->value ?? '') === $gender)>
                    @if ($gender === 'Male') Lelaki @else Perempuan @endif
                </option>
            @endforeach
        </select>
    </div>
</div>
<x-layouts.app :title="'Sunting Tetapan Mata | Sukan BTMKN'">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h1 class="h3 fw-bold mb-4">Sunting Tetapan Mata</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ route('point-settings.update', $setting) }}" class="row g-3">
                @csrf
                @method('put')
                <div class="col-md-6">
                    <label class="form-label">Kedudukan</label>
                    <input type="number" class="form-control" value="{{ $setting->position }}" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mata</label>
                    <input type="number" name="points" min="0" class="form-control" value="{{ old('points', $setting->points) }}" required>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-dark">Simpan</button>
                    <a href="{{ route('point-settings.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
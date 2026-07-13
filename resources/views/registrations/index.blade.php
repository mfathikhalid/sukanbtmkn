<x-layouts.app :title="'Registrations | Sukan BTMKN'">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1">Pendaftaran Acara</h1>
                    <div class="text-secondary">Jejaki pendaftaran peserta dan kuatkuasa kuota.</div>
                </div>
                <a href="{{ route('registrations.create') }}" class="btn btn-dark">Tambah Pendaftaran</a>
            </div>

            <form class="row g-3 mb-4" method="get">
                <div class="col-md-4">
                    <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari peserta atau sukan">
                </div>
                <div class="col-md-3">
                    <select name="sport_id" class="form-select">
                        <option value="">Semua sukan</option>
                        @foreach ($sports as $sport)
                            <option value="{{ $sport->id }}" @selected((string) request('sport_id') === (string) $sport->id)>{{ $sport->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="house_id" class="form-select">
                        <option value="">Semua rumah</option>
                        @foreach ($houses as $house)
                            <option value="{{ $house->id }}" @selected((string) request('house_id') === (string) $house->id)>{{ $house->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="gender" class="form-select">
                        <option value="">Semua jantina</option>
                        <option value="Male" @selected(request('gender') === 'Male')>Lelaki</option>
                        <option value="Female" @selected(request('gender') === 'Female')>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-1 d-grid">
                    <button class="btn btn-outline-dark" type="submit">Tapis</button>
                </div>
                @if (request()->hasAny(['search', 'sport_id', 'house_id', 'gender']))
                    <div class="col-auto">
                        <a href="{{ route('registrations.index') }}" class="btn btn-outline-secondary">Set semula</a>
                    </div>
                @endif
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Sukan</th>
                            <th>Peserta</th>
                            <th>Rumah</th>
                            <th>Jantina</th>
                            <th class="text-end">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registrations as $registration)
                            <tr>
                                <td>{{ $registration->sport?->name }}</td>
                                <td>{{ $registration->participant?->name }}</td>
                                <td>{{ $registration->participant?->house?->name }}</td>
                                <td>{{ $registration->participant?->gender?->value }}</td>
                                <td class="text-end">
                                    <form action="{{ route('registrations.destroy', $registration) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Padam pendaftaran ini?')">Padam</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-secondary py-4">Tiada pendaftaran dijumpai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
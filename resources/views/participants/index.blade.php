<x-layouts.app :title="'Participants | Sukan BTMKN'">
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1">Peserta</h1>
                    <div class="text-secondary">Urus pendaftaran pekerja mengikut rumah dan jantina.</div>
                </div>
                <a href="{{ route('participants.create') }}" class="btn btn-dark">Tambah Peserta</a>
            </div>

            <form class="row g-3 mb-4" method="get">
                <div class="col-md-5">
                    <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama">
                </div>
                <div class="col-md-3">
                    <select name="house_id" class="form-select">
                        <option value="">Semua rumah</option>
                        @foreach ($houses as $house)
                            <option value="{{ $house->id }}" @selected(request('house_id') == $house->id)>{{ $house->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="gender" class="form-select">
                        <option value="">Semua jantina</option>
                        @foreach (['Male', 'Female'] as $gender)
                            <option value="{{ $gender }}" @selected(request('gender') === $gender)>
                                @if ($gender === 'Male') Lelaki @else Perempuan @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-outline-dark" type="submit">Tapis</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Rumah</th>
                            <th>Jantina</th>
                            <th class="text-end">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($participants as $participant)
                            <tr>
                                <td>{{ $participant->name }}</td>
                                <td>{{ $participant->house?->name }}</td>
                                <td>
                                    @if ($participant->gender->value === 'Male') Lelaki @else Perempuan @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('participants.edit', $participant) }}" class="btn btn-sm btn-outline-primary">Sunting</a>
                                    <form action="{{ route('participants.destroy', $participant) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Padam peserta ini?')">Padam</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-secondary py-4">Tiada peserta dijumpai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $participants->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</x-layouts.app>
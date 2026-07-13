<x-layouts.app :title="'Sunting Peserta | Sukan BTMKN'">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h1 class="h3 fw-bold mb-4">Sunting Peserta</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ route('participants.update', $participant) }}" class="vstack gap-4">
                @csrf
                @method('put')
                @include('participants._form')
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-dark">Kemas Kini</button>
                    <a href="{{ route('participants.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
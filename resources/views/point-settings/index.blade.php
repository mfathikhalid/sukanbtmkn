<x-layouts.app :title="'Tetapan Mata | Sukan BTMKN'">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <h1 class="h3 fw-bold mb-1">Tetapan Mata</h1>
            <div class="text-secondary mb-4">Konfigurasi mata yang diberikan untuk setiap kedudukan.</div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Kedudukan</th>
                            <th>Mata</th>
                            <th class="text-end">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($settings as $setting)
                            <tr>
                                <td>{{ $setting->position }}</td>
                                <td>{{ $setting->points }}</td>
                                <td class="text-end">
                                    <a href="{{ route('point-settings.edit', $setting) }}" class="btn btn-sm btn-outline-primary">Sunting</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
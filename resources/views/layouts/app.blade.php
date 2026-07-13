<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Sukan BTMKN') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Manrope', sans-serif; background: #f8fafc; }
        .app-shell { min-height: 100vh; }
        .brand-mark { width: 0.9rem; height: 0.9rem; border-radius: 999px; background: linear-gradient(135deg, #f97316, #2563eb); }
    </style>
</head>
<body>
    <div class="app-shell d-flex flex-column">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-secondary-subtle">
            <div class="container-fluid px-3 px-lg-4">
                <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                    <span class="brand-mark"></span>
                    Sukan BTMKN
                </a>
                <div class="d-flex flex-wrap align-items-center gap-2 ms-auto me-3">
                    <a class="btn btn-outline-light btn-sm" href="{{ route('dashboard') }}">Dashboard</a>
                    <a class="btn btn-outline-light btn-sm" href="{{ route('participants.index') }}">Participants</a>
                    <a class="btn btn-outline-light btn-sm" href="{{ route('registrations.index') }}">Registrations</a>
                    <a class="btn btn-outline-light btn-sm" href="{{ route('events.fifa') }}">FIFA</a>
                    <a class="btn btn-outline-light btn-sm" href="{{ route('events.tekken') }}">Tekken</a>
                    <a class="btn btn-outline-light btn-sm" href="{{ route('events.pickleball') }}">Pickleball</a>
                    <a class="btn btn-outline-light btn-sm" href="{{ route('events.congkak') }}">Congkak</a>
                    <a class="btn btn-outline-light btn-sm" href="{{ route('events.carrom') }}">Carrom</a>
                    <a class="btn btn-outline-light btn-sm" href="{{ route('dart.index') }}">Dart</a>
                    <a class="btn btn-outline-light btn-sm" href="{{ route('bowling.index') }}">Bowling</a>
                    <a class="btn btn-outline-light btn-sm" href="{{ route('scoreboard.index') }}">Scoreboard</a>
                </div>
                <div class="d-flex align-items-center gap-2 ms-auto">
                    @auth
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
        </nav>

        <main class="flex-grow-1 py-4 py-lg-5">
            <div class="container">
                {{ $slot }}
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

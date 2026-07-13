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
        .live-indicator-dot { width: .55rem; height: .55rem; background: #22c55e; box-shadow: 0 0 0 .2rem rgba(34, 197, 94, .18); }
        .podium-gold { background: linear-gradient(145deg, #fffdf0, #fef3c7); border: 1px solid #f59e0b !important; }
        .podium-silver { background: linear-gradient(145deg, #ffffff, #e2e8f0); border: 1px solid #94a3b8 !important; }
        .podium-bronze { background: linear-gradient(145deg, #fffaf5, #fed7aa); border: 1px solid #c2410c !important; }
        .rank-gold { background: #d97706; color: #fff; }
        .rank-silver { background: #64748b; color: #fff; }
        .rank-bronze { background: #9a3412; color: #fff; }
    </style>
</head>
<body>
    <div class="app-shell d-flex flex-column">
        @php
            $navItems = [
                ['label' => 'Papan Pemuka', 'route' => 'dashboard'],
                ['label' => 'Peserta', 'route' => 'participants.index'],
                ['label' => 'Pendaftaran', 'route' => 'registrations.index'],
                ['label' => 'Papan Skor', 'route' => 'scoreboard.index'],
            ];
            $eventNavItems = [
                ['label' => 'FIFA', 'route' => 'events.fifa'],
                ['label' => 'Tekken', 'route' => 'events.tekken'],
                ['label' => 'Pickleball', 'route' => 'events.pickleball'],
                ['label' => 'Congkak', 'route' => 'events.congkak'],
                ['label' => 'Carrom', 'route' => 'events.carrom'],
                ['label' => 'Dart', 'route' => 'dart.index'],
                ['label' => 'Boling', 'route' => 'bowling.index'],
            ];
        @endphp

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-secondary-subtle shadow-sm">
            <div class="container-fluid px-3 px-lg-4 py-1">
                <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                    <span class="brand-mark"></span>
                    <span>
                        Sukan BTMKN
                        <small class="d-block text-secondary-emphasis fw-normal" style="font-size: 0.72rem; letter-spacing: 0.08em;">Admin</small>
                    </span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-lg-1 mt-3 mt-lg-0">
                        @foreach ($navItems as $item)
                            <li class="nav-item">
                                <a
                                    class="nav-link px-lg-3 rounded-3 {{ request()->routeIs($item['route']) ? 'active bg-white text-dark' : 'text-light' }}"
                                    href="{{ route($item['route']) }}"
                                >
                                    {{ $item['label'] }}
                                </a>
                            </li>
                        @endforeach
                        <li class="nav-item dropdown">
                            <button class="nav-link dropdown-toggle px-lg-3 rounded-3 {{ request()->routeIs('events.*', 'dart.*', 'bowling.*') ? 'active bg-white text-dark' : 'text-light' }}" data-bs-toggle="dropdown" type="button" aria-expanded="false">
                                Acara
                            </button>
                            <ul class="dropdown-menu shadow border-0">
                                @foreach ($eventNavItems as $item)
                                    <li><a class="dropdown-item {{ request()->routeIs($item['route']) ? 'active' : '' }}" href="{{ route($item['route']) }}">{{ $item['label'] }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>

                    <div class="d-flex align-items-center gap-2 ms-lg-auto mt-3 mt-lg-0">
                        @auth
                            @if (request()->routeIs('dashboard', 'events.*', 'dart.*', 'bowling.*', 'scoreboard.*'))
                                <div id="live-score-indicator" class="d-none d-xl-flex align-items-center gap-2 text-light small me-2" title="Dikemas kini secara automatik setiap 5 saat">
                                    <span class="live-indicator-dot rounded-circle"></span>
                                    <span>Live</span>
                                    <span id="live-score-time" class="text-secondary"></span>
                                </div>
                            @endif
                            <div class="text-end d-none d-md-block">
                                <div class="text-light fw-semibold small">{{ auth()->user()->name }}</div>
                                <div class="text-secondary small">{{ auth()->user()->email }}</div>
                            </div>

                            <form method="post" action="{{ route('logout') }}" class="ms-lg-2">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-sm px-3">Keluar</button>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main
            class="flex-grow-1 py-4 py-lg-5"
            @if (request()->routeIs('dashboard', 'events.*', 'dart.*', 'bowling.*', 'scoreboard.*'))
                data-live-score="true"
                data-live-interval="5000"
            @endif
        >
            <div class="container">
                {{ $slot }}
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            const main = document.querySelector('main[data-live-score="true"]');

            if (!main) {
                return;
            }

            let hasUnsavedInput = false;
            let isRefreshing = false;
            const interval = Number(main.dataset.liveInterval ?? 5000);
            const time = document.getElementById('live-score-time');

            main.addEventListener('input', () => {
                hasUnsavedInput = true;
            });

            main.addEventListener('submit', () => {
                hasUnsavedInput = false;
            });

            const refresh = async () => {
                const focusedField = main.querySelector('input:focus, select:focus, textarea:focus');

                if (isRefreshing || hasUnsavedInput || focusedField || document.hidden) {
                    return;
                }

                isRefreshing = true;

                try {
                    const response = await fetch(window.location.href, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Cache-Control': 'no-cache',
                        },
                        cache: 'no-store',
                    });

                    if (!response.ok) {
                        return;
                    }

                    const documentCopy = new DOMParser().parseFromString(await response.text(), 'text/html');
                    const updatedMain = documentCopy.querySelector('main[data-live-score="true"]');

                    if (updatedMain) {
                        main.innerHTML = updatedMain.innerHTML;
                        hasUnsavedInput = false;

                        if (time) {
                            time.textContent = new Date().toLocaleTimeString('ms-MY', {
                                hour: '2-digit',
                                minute: '2-digit',
                                second: '2-digit',
                            });
                        }
                    }
                } catch (error) {
                    // Keep the current view when the connection is temporarily unavailable.
                } finally {
                    isRefreshing = false;
                }
            };

            window.setInterval(refresh, interval);
        })();
    </script>
</body>
</html>

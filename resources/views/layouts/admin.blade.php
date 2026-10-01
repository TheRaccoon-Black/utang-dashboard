<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Utang DBH — Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                }
            }
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @stack('head')
</head>
<body class="bg-slate-100 font-sans antialiased">
<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="hidden w-64 shrink-0 flex-col border-r border-slate-200 bg-white lg:flex">
        <div class="flex h-16 items-center gap-3 border-b border-slate-200 px-5">
            <div class="flex size-9 items-center justify-center rounded-lg bg-slate-900 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 18v-7"/>
                    <path d="M11.119 2.205a2 2 0 0 1 1.762 0l7.84 3.846A.5.5 0 0 1 20.5 7h-17a.5.5 0 0 1-.22-.949z"/>
                    <path d="M14 18v-7"/>
                    <path d="M18 18v-7"/>
                    <path d="M3 22h18"/>
                    <path d="M6 18v-7"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-900">Utang DBH</p>
                <p class="text-[11px] text-slate-500">Panel Admin</p>
            </div>
        </div>

        <nav class="flex-1 space-y-1 p-3">
            <p class="px-2 pb-1 pt-3 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Navigasi</p>

            <a href="{{ route('admin.home') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.home') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px">
                    <path d="M3 9.5 12 3l9 6.5V20a1 1 0 0 1-1 1h-5v-6h-6v6H4a1 1 0 0 1-1-1z"/>
                </svg>
                Dashboard Admin
            </a>

            <a href="{{ route('admin.transaksi') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.transaksi*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px">
                    <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                </svg>
                Transaksi Utang
            </a>

            <p class="px-2 pb-1 pt-4 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Master Data</p>

            <a href="{{ route('admin.kabupaten') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.kabupaten') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px">
                    <path d="M14 18.2V21l-1.2.9"/>
                    <path d="M8 21l-4.9-3.4"/>
                    <path d="M6.4 14.6 3.5 15.9"/>
                    <path d="m6.9 9.7-3.4 1.4"/>
                    <path d="M14 4.2 20.3 7.6"/>
                    <path d="M8.4 11.7 1.8 7.6"/>
                    <path d="M8 21h8"/>
                    <path d="M8 17.6V4"/>
                </svg>
                Kabupaten/Kota
            </a>

            <a href="{{ route('admin.jenis_pajak') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.jenis_pajak') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px">
                    <path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.7 8.7a2 2 0 0 0 2.828 0l6.172-6.172a2 2 0 0 0 0-2.828z"/>
                    <circle cx="7.5" cy="7.5" r=".5"/>
                </svg>
                Jenis Pajak
            </a>
        </nav>

        <div class="border-t border-slate-200 p-3">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px">
                    <path d="M20 12v8H4v-8"/>
                    <path d="m6 16 6-4 6 4"/>
                    <path d="M12 4v8"/>
                </svg>
                Lihat Dashboard Publik
            </a>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex min-w-0 flex-1 flex-col">

        {{-- Top bar --}}
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 lg:px-6">
            <div>
                <h1 class="text-base font-bold text-slate-900">@yield('page-title', 'Admin')</h1>
                <p class="text-[11px] text-slate-500">@yield('page-subtitle', 'Manajemen data utang DBH')</p>
            </div>
            <a href="{{ route('admin.home') }}" class="rounded-lg bg-slate-900 px-3.5 py-2 text-xs font-semibold text-white transition-colors hover:bg-slate-700">
                ← Beranda
            </a>
        </header>

        {{-- Mobile nav --}}
        <nav class="flex gap-1 overflow-x-auto border-b border-slate-200 bg-white px-4 py-2 lg:hidden">
            @php $mnav = [
                ['label' => 'Beranda', 'route' => 'admin.home'],
                ['label' => 'Transaksi', 'route' => 'admin.transaksi'],
                ['label' => 'Kabupaten', 'route' => 'admin.kabupaten'],
                ['label' => 'Jenis Pajak', 'route' => 'admin.jenis_pajak'],
            ]; @endphp
            @foreach($mnav as $l)
                @php $active = request()->routeIs($l['route']) || request()->routeIs($l['route'] . '*'); @endphp
                <a href="{{ route($l['route']) }}"
                    class="shrink-0 rounded-md px-3 py-1.5 text-xs font-medium {{ $active ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600' }}">
                    {{ $l['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mx-4 mt-4 flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm text-emerald-800 lg:mx-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px"><path d="M21.801 10A10 10 0 1 1 17 3.335"/><path d="m9 11 3 3L22 4"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mx-4 mt-4 flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-2.5 text-sm text-red-800 lg:mx-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <main class="flex-1 p-4 lg:p-6">
            @yield('content')
        </main>

        <footer class="border-t border-slate-200 bg-white px-4 py-3 text-center text-[11px] text-slate-400 lg:px-6">
            Sistem Manajemen Utang DBH — Provinsi Bengkulu
        </footer>
    </div>
</div>
@stack('scripts')
</body>
</html>

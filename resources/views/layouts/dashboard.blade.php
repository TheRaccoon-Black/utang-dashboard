<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Utang DBH — Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {} }
        };
    </script>
    @stack('head')
</head>
<body class="bg-slate-100 antialiased">
<div class="mx-auto flex min-h-screen w-full max-w-[1600px] flex-col gap-3 p-3 lg:gap-4 lg:p-4">

    {{-- User bar --}}
    <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-2.5 shadow-sm">
        <div class="flex items-center gap-2.5">
            <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-slate-900 text-xs font-bold text-white">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="truncate text-xs font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-slate-500">{{ auth()->user()->roleLabel() }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.home') }}"
                    class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white transition-colors hover:bg-slate-700">
                    Panel Admin
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition-colors hover:border-red-200 hover:bg-red-50 hover:text-red-600">
                    Keluar
                </button>
            </form>
        </div>
    </div>

    @yield('content')
</div>
@stack('scripts')
</body>
</html>

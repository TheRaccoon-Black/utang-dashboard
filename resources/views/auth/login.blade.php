@extends('layouts.app')

@section('title', 'Masuk — Utang DBH')

@section('content')
<div class="flex min-h-screen items-center justify-center p-4">
    <div class="w-full max-w-md">

        {{-- Logo / identitas sistem --}}
        <div class="mb-6 flex flex-col items-center gap-3">
            <div class="flex size-12 items-center justify-center rounded-xl bg-slate-900 text-white shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 18v-7"/>
                    <path d="M11.119 2.205a2 2 0 0 1 1.762 0l7.84 3.846A.5.5 0 0 1 20.5 7h-17a.5.5 0 0 1-.22-.949z"/>
                    <path d="M14 18v-7"/>
                    <path d="M18 18v-7"/>
                    <path d="M3 22h18"/>
                    <path d="M6 18v-7"/>
                </svg>
            </div>
            <div class="text-center">
                <h1 class="text-lg font-bold text-slate-900">Utang DBH — Provinsi Bengkulu</h1>
                <p class="text-xs text-slate-500">Silakan masuk untuk mengakses dashboard</p>
            </div>
        </div>

        {{-- Flash success (misal: setelah logout) --}}
        @if(session('success'))
            <div class="mb-4 flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm text-emerald-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px"><path d="M21.801 10A10 10 0 1 1 17 3.335"/><path d="m9 11 3 3L22 4"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}"
              class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf

            <h2 class="text-sm font-bold text-slate-900">Masuk ke Akun</h2>
            <p class="mb-4 text-xs text-slate-500">Gunakan kredensial yang diberikan oleh operator sistem.</p>

            <div class="space-y-4">
                <div>
                    <label for="email" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-600">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                        placeholder="nama@instansi.go.id"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-600">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>

                <label class="flex items-center gap-2 text-xs font-medium text-slate-600">
                    <input type="checkbox" name="remember" @checked(old('remember'))
                        class="size-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    Ingat saya di perangkat ini
                </label>
            </div>

            <button type="submit"
                class="mt-6 flex w-full items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
                Masuk
            </button>
        </form>

        {{-- Kredensial demo --}}
        <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4">
            <p class="mb-3 flex items-center gap-1.5 text-xs font-bold uppercase tracking-wide text-amber-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px"><rect width="18" height="11" x="3" y="6" rx="2" ry="2"/><path d="M7 14v4M17 14v4M7 14h8a2 2 0 0 1 2 2v4H9a2 2 0 0 1-2-2v-4Z"/></svg>
                Akun Demo — klik untuk mengisi form
            </p>
            <div class="space-y-2">
                <button type="button" data-demo-email="admin@utang.test" data-demo-password="admin123"
                    class="flex w-full items-center justify-between rounded-lg border border-amber-200 bg-white px-3 py-2.5 text-left transition hover:border-amber-400 hover:shadow-sm">
                    <div>
                        <p class="text-xs font-semibold text-slate-900">Administrator</p>
                        <p class="text-[11px] text-slate-500">admin@utang.test / admin123</p>
                    </div>
                    <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold text-amber-700">Admin</span>
                </button>
                <button type="button" data-demo-email="operator@utang.test" data-demo-password="operator123"
                    class="flex w-full items-center justify-between rounded-lg border border-amber-200 bg-white px-3 py-2.5 text-left transition hover:border-amber-400 hover:shadow-sm">
                    <div>
                        <p class="text-xs font-semibold text-slate-900">Operator</p>
                        <p class="text-[11px] text-slate-500">operator@utang.test / operator123</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">Operator</span>
                </button>
            </div>
        </div>

        <p class="mt-4 text-center text-[11px] text-slate-400">
            Sistem Manajemen Utang DBH &amp; Realisasi Pembayaran
        </p>
    </div>
</div>

<script>
// Tombol demo: klik untuk mengisi form login di atas
document.querySelectorAll('[data-demo-email]').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var email = document.getElementById('email');
        var password = document.getElementById('password');
        email.value = btn.dataset.demoEmail;
        password.value = btn.dataset.demoPassword;
        email.focus();
    });
});
</script>
@endsection

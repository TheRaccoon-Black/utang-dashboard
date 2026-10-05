@extends('layouts.admin')

@section('page-title', 'Manajemen User')
@section('page-subtitle', 'Kelola akun operator dan administrator')

@section('content')
<div class="rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 p-4">
        <div>
            <h2 class="text-sm font-bold text-slate-900">Daftar User</h2>
            <p class="text-xs text-slate-500">Total {{ $users->count() }} akun terdaftar</p>
        </div>
        <button onclick="document.getElementById('addUserForm').classList.remove('hidden'); document.getElementById('add-name').focus()"
            class="flex items-center gap-1.5 rounded-lg bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white transition-colors hover:bg-blue-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
            Tambah User
        </button>
    </div>

    {{-- Form tambah user --}}
    <form id="addUserForm" method="POST" action="{{ route('admin.users.store') }}" class="hidden border-b border-slate-200 bg-slate-50 p-4">
        @csrf
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <label for="add-name" class="mb-1 block text-xs font-semibold text-slate-700">Nama Lengkap</label>
                <input id="add-name" name="name" type="text" required maxlength="120"
                    value="{{ old('name') }}"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="add-email" class="mb-1 block text-xs font-semibold text-slate-700">Email</label>
                <input id="add-email" name="email" type="email" required maxlength="160"
                    value="{{ old('email') }}"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="add-password" class="mb-1 block text-xs font-semibold text-slate-700">Password <span class="text-slate-400">(min. 8 karakter)</span></label>
                <input id="add-password" name="password" type="password" required minlength="8"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="add-role" class="mb-1 block text-xs font-semibold text-slate-700">Role</label>
                <select id="add-role" name="role" required
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    @foreach($roleOptions as $value => $label)
                        <option value="{{ $value }}" @selected(old('role', 'operator') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-3 flex gap-2">
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700">Simpan User</button>
            <button type="button" onclick="document.getElementById('addUserForm').classList.add('hidden')"
                class="rounded-lg bg-slate-200 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-300">Batal</button>
        </div>
    </form>

    {{-- Tabel user --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-4 py-2.5 font-semibold">No</th>
                    <th class="px-4 py-2.5 font-semibold">Nama</th>
                    <th class="px-4 py-2.5 font-semibold">Email</th>
                    <th class="px-4 py-2.5 font-semibold">Role</th>
                    <th class="px-4 py-2.5 font-semibold">Terdaftar</th>
                    <th class="px-4 py-2.5 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $i => $user)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-slate-500">{{ $i + 1 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-slate-900">{{ $user->name }}</span>
                                @if($user->id === auth()->id())
                                    <span class="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-blue-600">Anda</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-0.5 text-[10px] font-semibold {{ $user->isAdmin() ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $user->roleLabel() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <button
                                onclick="startEditUser({{ $user->id }}, @json($user->only('name', 'email', 'role')))"
                                class="rounded-md px-2.5 py-1 font-medium text-blue-600 hover:bg-blue-50">
                                Ubah
                            </button>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                                      onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="rounded-md px-2.5 py-1 font-medium text-red-600 hover:bg-red-50">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-400">Belum ada user terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Form edit user per akun --}}
    @foreach($users as $user)
        <form id="edit-user-{{ $user->id }}" method="POST" action="{{ route('admin.users.update', $user) }}" class="hidden">
            @csrf @method('PUT')
            <input type="hidden" name="name" value="{{ $user->name }}">
            <input type="hidden" name="email" value="{{ $user->email }}">
            <input type="hidden" name="role" value="{{ $user->role }}">
            <input type="hidden" name="password">
            <input type="hidden" name="password_confirmation">
        </form>
    @endforeach
</div>

<script>
function startEditUser(id, data) {
    const roleMap = @json($roleOptions);
    const roleValues = Object.keys(roleMap);
    const roleLabels = roleValues.map(v => roleMap[v]);
    let nama = prompt('Ubah nama user:', data.name);
    if (nama === null) return;
    if (nama.trim() === '') { alert('Nama tidak boleh kosong.'); return; }

    let email = prompt('Ubah email user:', data.email);
    if (email === null) return;
    email = email.trim();
    if (email === '' || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { alert('Format email tidak valid.'); return; }

    const rolePrompt = roleValues.map((v, i) => roleLabels[i] + ' (' + v + ')').join(', ');
    const roleInput = prompt('Ubah role (ketik salah satu: ' + rolePrompt + '):', data.role);
    if (roleInput === null) return;
    const matched = roleValues.filter(v => v.toLowerCase() === roleInput.trim().toLowerCase()
        || roleMap[v].toLowerCase() === roleInput.trim().toLowerCase());
    if (matched.length === 0) { alert('Role tidak dikenal. Gunakan: ' + rolePrompt); return; }

    const resetPw = confirm('Reset password user ini? (OK = yes, pakai prompt; Cancel = no)');
    let password = '';
    if (resetPw) {
        password = prompt('Password baru (min. 8 karakter):');
        if (password === null) return;
        if (password.length < 8) { alert('Password minimal 8 karakter.'); return; }
        const pw2 = prompt('Konfirmasi password baru:');
        if (pw2 === null) return;
        if (pw2 !== password) { alert('Konfirmasi password tidak sama.'); return; }
    }

    const form = document.getElementById('edit-user-' + id);
    form.querySelector('input[name=name]').value = nama.trim();
    form.querySelector('input[name=email]').value = email;
    form.querySelector('input[name=role]').value = matched[0];
    form.querySelector('input[name=password]').value = password;
    form.querySelector('input[name=password_confirmation]').value = password;
    form.submit();
}
</script>
@endsection

@extends('layouts.admin')

@section('page-title', 'Jenis Pajak')
@section('page-subtitle', 'Master data kategori pajak yang dikelola')

@section('content')
<div class="rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 p-4">
        <div>
            <h2 class="text-sm font-bold text-slate-900">Kategori Pajak DBH</h2>
            <p class="text-xs text-slate-500">Kelola jenis pajak: PBB KB, PAP, PKB, BBN KB, dll.</p>
        </div>
        <button onclick="document.getElementById('addForm').classList.remove('hidden'); document.getElementById('kode').focus()"
            class="flex items-center gap-1.5 rounded-lg bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white transition-colors hover:bg-blue-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Kategori
        </button>
    </div>

    <form id="addForm" method="POST" action="{{ route('admin.jenis_pajak.store') }}" class="hidden border-b border-slate-200 bg-slate-50 p-4">
        @csrf
        <div class="flex flex-wrap items-end gap-3">
            <div class="w-40">
                <label for="kode" class="mb-1 block text-xs font-semibold text-slate-700">Kode</label>
                <input id="kode" name="kode" type="text" required maxlength="32" placeholder="mis. PBB_KB"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 font-mono text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                @error('kode')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex-1 min-w-40">
                <label for="nama" class="mb-1 block text-xs font-semibold text-slate-700">Nama</label>
                <input id="nama" name="nama" type="text" required maxlength="64" placeholder="mis. PBB KB"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                @error('nama')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700">Simpan</button>
                <button type="button" onclick="document.getElementById('addForm').classList.add('hidden')"
                    class="rounded-lg bg-slate-200 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-300">Batal</button>
            </div>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-4 py-2.5 font-semibold">No</th>
                    <th class="px-4 py-2.5 font-semibold">Kode</th>
                    <th class="px-4 py-2.5 font-semibold">Nama</th>
                    <th class="px-4 py-2.5 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($jenisPajak as $i => $jp)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-slate-500">{{ $i + 1 }}</td>
                        <td class="px-4 py-3 font-mono text-slate-600">{{ $jp->kode }}</td>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $jp->nama }}</td>
                        <td class="px-4 py-3 text-right">
                            <button
                                onclick="startEditJpk({{ $jp->id }}, '{{ $jp->kode }}', '{{ $jp->nama }}')"
                                class="rounded-md px-2.5 py-1 font-medium text-blue-600 hover:bg-blue-50">
                                Ubah
                            </button>
                            <form method="POST" action="{{ route('admin.jenis_pajak.destroy', $jp) }}" class="inline"
                                  onsubmit="return confirm('Hapus {{ $jp->nama }}? Record transaksi terkait juga akan ikut terhapus.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="rounded-md px-2.5 py-1 font-medium text-red-600 hover:bg-red-50">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Edit forms --}}
    @foreach($jenisPajak as $jp)
        <form id="edit-jpk-{{ $jp->id }}" method="POST" action="{{ route('admin.jenis_pajak.update', $jp) }}" class="hidden">
            @csrf @method('PUT')
            <input type="hidden" name="kode" value="{{ $jp->kode }}">
            <input type="hidden" name="nama" value="{{ $jp->nama }}">
        </form>
    @endforeach
</div>

<script>
function startEditJpk(id, kode, nama) {
    const kodeBaru = prompt('Ubah kode:', kode);
    if (kodeBaru === null || kodeBaru.trim() === '') return;
    const namaBaru = prompt('Ubah nama:', nama);
    if (namaBaru === null || namaBaru.trim() === '') return;
    const form = document.getElementById('edit-jpk-' + id);
    form.querySelector('input[name=kode]').value = kodeBaru.trim();
    form.querySelector('input[name=nama]').value = namaBaru.trim();
    form.submit();
}
</script>
@endsection

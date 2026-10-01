<?php

namespace App\Http\Controllers\Admin;

use App\Models\JenisPajak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class JenisPajakController
{
    public function index()
    {
        $jenisPajak = JenisPajak::query()->orderBy('nama')->get();

        return view('admin.jenis_pajak', compact('jenisPajak'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode' => 'required|max:32|unique:jenis_pajak,kode',
            'nama' => 'required|max:64',
        ]);
        JenisPajak::create($data);

        return Redirect::route('admin.jenis_pajak')->with('success', 'Jenis pajak baru ditambahkan.');
    }

    public function update(Request $request, JenisPajak $jenisPajak)
    {
        $data = $request->validate([
            'kode' => 'required|max:32|unique:jenis_pajak,kode,' . $jenisPajak->id,
            'nama' => 'required|max:64',
        ]);
        $jenisPajak->update($data);

        return Redirect::route('admin.jenis_pajak')->with('success', 'Jenis pajak diperbarui.');
    }

    public function destroy(JenisPajak $jenisPajak)
    {
        $jenisPajak->delete();

        return Redirect::route('admin.jenis_pajak')->with('success', 'Jenis pajak dihapus.');
    }
}

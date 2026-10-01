<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kabupaten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class KabupatenController
{
    public function index()
    {
        $kabupatens = Kabupaten::query()
            ->withCount('transaksi')
            ->orderBy('nama')
            ->get();

        return view('admin.kabupaten', compact('kabupatens'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['nama' => 'required|max:64']);
        Kabupaten::create($data);

        return Redirect::route('admin.kabupaten')->with('success', 'Kabupaten/kota baru ditambahkan.');
    }

    public function update(Request $request, Kabupaten $kabupaten)
    {
        $data = $request->validate(['nama' => 'required|max:64']);
        $kabupaten->update($data);

        return Redirect::route('admin.kabupaten')->with('success', 'Nama kabupaten/kota diperbarui.');
    }

    public function destroy(Kabupaten $kabupaten)
    {
        $kabupaten->delete();

        return Redirect::route('admin.kabupaten')->with('success', 'Kabupaten/kota dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kabupaten;
use App\Models\JenisPajak;
use App\Models\TransaksiUtang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TransaksiUtangController
{
    public function index(Request $request)
    {
        $query = TransaksiUtang::query()
            ->with(['kabupaten:id,nama', 'jenisPajak:id,nama'])
            ->orderBy('tahun_anggaran', 'desc')
            ->orderBy('kabupaten_id');

        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(fn ($w) =>
                $w->whereHas('kabupaten', fn ($w2) => $w2->where('nama', 'like', "%$q%"))
                    ->orWhereHas('jenisPajak', fn ($w3) => $w3->where('nama', 'like', "%$q%"))
                    ->orWhere('tahun_anggaran', 'like', "%$q%")
            );
        }

        $transaksi = $query->paginate(20)->withQueryString();
        $kabupatens = Kabupaten::orderBy('nama')->get();
        $jenisPajak = JenisPajak::orderBy('nama')->get();

        return view('admin.transaksi', compact('transaksi', 'kabupatens', 'jenisPajak'));
    }

    public function create()
    {
        $kabupatens = Kabupaten::orderBy('nama')->get();
        $jenisPajak = JenisPajak::orderBy('nama')->get();

        return view('admin.transaksi-form', compact('kabupatens', 'jenisPajak'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        // Prevent duplicate (tahun, kabupaten, pajak, triwulan)
        $exists = TransaksiUtang::where('tahun_anggaran', $data['tahun_anggaran'])
            ->where('kabupaten_id', $data['kabupaten_id'])
            ->where('jenis_pajak_id', $data['jenis_pajak_id'])
            ->where('triwulan', $data['triwulan'])
            ->exists();

        if ($exists) {
            return Redirect::route('admin.transaksi.create')
                ->withInput()
                ->with('error', 'Data dengan kombinasi tahun, kabupaten/kota, jenis pajak, dan triwulan yang sama sudah ada.');
        }

        TransaksiUtang::create($data);

        return Redirect::route('admin.transaksi')->with('success', 'Data transaksi utang ditambahkan.');
    }

    public function edit(TransaksiUtang $transaksi)
    {
        $kabupatens = Kabupaten::orderBy('nama')->get();
        $jenisPajak = JenisPajak::orderBy('nama')->get();

        return view('admin.transaksi-form', compact('transaksi', 'kabupatens', 'jenisPajak'));
    }

    public function update(Request $request, TransaksiUtang $transaksi)
    {
        $data = $this->validateData($request, $transaksi->id);

        $exists = TransaksiUtang::where('tahun_anggaran', $data['tahun_anggaran'])
            ->where('kabupaten_id', $data['kabupaten_id'])
            ->where('jenis_pajak_id', $data['jenis_pajak_id'])
            ->where('triwulan', $data['triwulan'])
            ->where('id', '!=', $transaksi->id)
            ->exists();

        if ($exists) {
            return Redirect::route('admin.transaksi.edit', $transaksi)
                ->withInput()
                ->with('error', 'Data dengan kombinasi tahun, kabupaten/kota, jenis pajak, dan triwulan yang sama sudah ada di record lain.');
        }

        $transaksi->update($data);

        return Redirect::route('admin.transaksi')->with('success', 'Data transaksi utang diperbarui.');
    }

    public function destroy(TransaksiUtang $transaksi)
    {
        $transaksi->delete();

        return Redirect::route('admin.transaksi')->with('success', 'Data transaksi utang dihapus.');
    }

    private function validateData(Request $request, ?int $excludeId = null)
    {
        $rules = [
            'tahun_anggaran' => 'required|integer|min:2000|max:2100',
            'kabupaten_id' => 'required|exists:kabupatens,id',
            'jenis_pajak_id' => 'required|exists:jenis_pajak,id',
            'triwulan' => 'required|integer|in:1,2,3,4',
            'utang' => 'required|integer|min:0',
            'pembayaran' => 'required|integer|min:0',
        ];

        $validated = $request->validate($rules);

        if ($validated['pembayaran'] > $validated['utang']) {
            return Redirect::back()->withInput()->with('error', 'Jumlah pembayaran tidak boleh melebihi jumlah utang.');
        }

        $validated['sisa'] = $validated['utang'] - $validated['pembayaran'];

        return $validated;
    }
}

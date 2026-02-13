<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $suppliers = $query->orderBy('nama', 'asc')->get();
        return view('master.supplier.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:suppliers,kode',
            'nama' => 'required',
            'alamat' => 'nullable',
            'jatuh_tempo' => 'nullable|integer',
            'menggunakan_pajak' => 'required', // non, include, exclude
            'nilai_pajak' => 'nullable|numeric',
        ]);

        Supplier::create($validated);

        return redirect()->back()->with('success', 'Supplier berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'kode' => 'required|unique:suppliers,kode,' . $supplier->id,
            'nama' => 'required',
            'alamat' => 'nullable',
            'jatuh_tempo' => 'nullable|integer',
            'menggunakan_pajak' => 'required',
            'nilai_pajak' => 'nullable|numeric',
        ]);

        $supplier->update($validated);

        return redirect()->back()->with('success', 'Supplier berhasil diperbarui');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->back()->with('success', 'Supplier berhasil dihapus');
    }
}

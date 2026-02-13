<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('nama', 'asc')->get();
        return view('master.customer.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:customers,kode',
            'nama' => 'required',
            'alamat' => 'nullable',
            'jatuh_tempo' => 'required|integer',
        ], [
            'jatuh_tempo.integer' => 'Kolom Jatuh Tempo harus berupa angka bulat (hari).',
            'jatuh_tempo.required' => 'Kolom Jatuh Tempo wajib diisi.',
            'kode.unique' => 'Kode customer sudah digunakan.',
        ]);

        Customer::create($validated);

        return redirect()->back()->with('success', 'Customer berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'kode' => 'required|unique:customers,kode,' . $customer->id,
            'nama' => 'required',
            'alamat' => 'nullable',
            'jatuh_tempo' => 'required|integer',
        ], [
            'jatuh_tempo.integer' => 'Kolom Jatuh Tempo harus berupa angka bulat (hari).',
            'jatuh_tempo.required' => 'Kolom Jatuh Tempo wajib diisi.',
            'kode.unique' => 'Kode customer sudah digunakan.',
        ]);

        $customer->update($validated);

        return redirect()->back()->with('success', 'Customer berhasil diperbarui');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->back()->with('success', 'Customer berhasil dihapus');
    }
}

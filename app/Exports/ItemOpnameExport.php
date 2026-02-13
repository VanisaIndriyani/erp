<?php

namespace App\Exports;

use App\Models\ItemOpname;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ItemOpnameExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        // Export all opname history or filtered by date? For now, let's export recent 500 or all.
        // Usually export implies "All Data" or filtered. 
        // Let's get all data ordered by latest.
        $data = ItemOpname::with('item', 'user')->orderBy('tanggal', 'desc')->get();

        return view('exports.item_opname', [
            'data' => $data
        ]);
    }
}

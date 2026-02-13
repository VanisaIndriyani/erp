<?php

namespace App\Exports;

use App\Models\ItemTransfer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ItemTransferExport implements FromView, ShouldAutoSize, WithDrawings
{
    protected $search;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    public function view(): View
    {
        $query = ItemTransfer::with(['user', 'details']);

        if ($this->search) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->where('no_transaksi', 'like', "%{$search}%")
                  ->orWhere('no_sj', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $data = $query->orderBy('tanggal', 'desc')->get();

        return view('exports.item_transfer', [
            'data' => $data
        ]);
    }

    public function drawings()
    {
        if (!file_exists(public_path('img/Logo KJH New.png'))) {
            return [];
        }

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('img/Logo KJH New.png'));
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(5);
        $drawing->setOffsetY(5);

        return [$drawing];
    }
}

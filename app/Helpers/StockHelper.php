<?php

namespace App\Helpers;

use App\Models\KartuStok;
use App\Models\Item;

class StockHelper
{
    /**
     * Record stock movement
     *
     * @param int $itemId
     * @param string $tanggal
     * @param string $jenisTransaksi (masuk, keluar, opname, transfer)
     * @param string $noReferensi
     * @param int $qty (positive for in, negative will be converted to out)
     * @param string|null $keterangan
     * @return void
     */
    public static function record($itemId, $tanggal, $jenisTransaksi, $noReferensi, $qty, $keterangan = null)
    {
        $item = Item::find($itemId);
        if (!$item) return;

        $masuk = 0;
        $keluar = 0;

        if ($jenisTransaksi == 'masuk' || ($jenisTransaksi == 'opname' && $qty > 0) || ($jenisTransaksi == 'transfer' && $qty > 0)) {
            $masuk = abs($qty);
        } else {
            $keluar = abs($qty);
        }

        // Current stock is already updated in the controller before calling this?
        // Usually it's better to calculate saldo based on previous saldo + masuk - keluar.
        // However, for simplicity and since we update Item::stok in controller, let's trust Item::stok.
        // BUT, if we call this AFTER updating item stock, the current $item->stok is the ENDING balance.
        
        // Let's assume this is called AFTER the item stock is updated in the controller.
        $saldo = $item->stok;

        KartuStok::create([
            'item_id' => $itemId,
            'tanggal' => $tanggal,
            'jenis_transaksi' => $jenisTransaksi,
            'no_referensi' => $noReferensi,
            'masuk' => $masuk,
            'keluar' => $keluar,
            'saldo' => $saldo,
            'keterangan' => $keterangan,
        ]);
    }
}

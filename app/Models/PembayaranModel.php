<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table = 'tbl_pembayaran_spp';
    protected $primaryKey = 'id_pembayaran';

    public function getTotalPembayaranPerBulan()
    {
        return $this->select("DATE_FORMAT(tanggal_bayar, '%Y-%m') as bulan, SUM(jumlah_bayar) as total")
            ->groupBy('bulan')
            ->orderBy('bulan', 'ASC')
            ->findAll();
    }
}

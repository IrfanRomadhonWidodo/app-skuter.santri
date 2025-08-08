<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelPembayaranSPP extends Model
{
    protected $table = 'tbl_hasil_pembayaran_spp';
    protected $primaryKey = 'id_pembayaran';
    protected $allowedFields = ['id_pembayaran', 'nim', 'nama_lengkap', 'fakultas', 'prodi', 'biaya', 'tanggal_bayar', 'status', 'approval'];
}

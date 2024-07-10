<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigAplikasiModel extends Model
{
    protected $table            = 'tbl_cf_aplikasi';
    protected $primaryKey       = 'apk_id';
    protected $returnType       = 'array';
    protected $protectFields    = false;

  
}

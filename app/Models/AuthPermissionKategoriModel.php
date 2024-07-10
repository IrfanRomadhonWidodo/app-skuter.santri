<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthPermissionKategoriModel extends Model
{
    protected $table            = 'tbl_auth_permission_kategori';
    protected $primaryKey       = 'prm_ktg_id';
    protected $returnType       = 'array';
    protected $protectFields    = false;

}

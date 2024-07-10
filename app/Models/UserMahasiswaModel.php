<?php

namespace App\Models;

use CodeIgniter\Model;

class UserMahasiswaModel extends Model
{
    protected $table            = 'tbl_md_user_mahasiswa';
    protected $primaryKey       = 'usr_mhs_usr_id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    
    
}

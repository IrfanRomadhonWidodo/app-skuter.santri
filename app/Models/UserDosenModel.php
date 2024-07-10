<?php

namespace App\Models;

use CodeIgniter\Model;

class UserDosenModel extends Model
{
    protected $table            = 'tbl_md_user_dosen';
    protected $primaryKey       = 'usr_dsn_usr_id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    
    
}

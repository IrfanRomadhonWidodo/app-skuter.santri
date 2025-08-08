<?php

namespace App\Models;

use CodeIgniter\Model;

class UserTaskModel extends Model
{
    protected $table            = 'tbl_pmb_user_task';
    protected $primaryKey       = 'usrt_id';
    protected $returnType       = 'array';
    protected $protectFields    = false;
}

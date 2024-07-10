<?php

namespace App\Models;

use CodeIgniter\Model;

class NegaraModel extends Model
{
    protected $table            = 'tbl_md_negara';
    protected $primaryKey       = 'ngr_id';
    protected $returnType       = 'array';
    protected $protectFields    = false;
}

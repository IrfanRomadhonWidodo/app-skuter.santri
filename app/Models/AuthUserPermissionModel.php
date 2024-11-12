<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthUserPermissionModel extends Model
{
    protected $table            = 'tbl_auth_user_permission';
    protected $primaryKey       = 'usr_prm_id';
    protected $returnType       = 'array';
    protected $protectFields    = false;

}

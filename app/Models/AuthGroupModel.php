<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthGroupModel extends Model
{
    protected $table            = 'tbl_auth_group';
    protected $primaryKey       = 'grp_id';
    protected $returnType       = 'array';
    protected $protectFields    = false;

    // var $order = ['tbl_auth_group.prm_created_at', 'DESC'];

    private $columnSearch = [];

    protected $db;
    protected $dt;
    protected $request;
    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
        $this->dt = $this->db->table($this->table);
    }

    private function getDatatablesQuery($params)
    {
        $i = 0;
        foreach ($this->columnSearch as $item) {
            if ($params['search_value']) {
                if ($i === 0) {
                    $this->dt->groupStart();
                    $this->dt->like($item, $params['search_value']);
                } else {
                    $this->dt->orLike($item, $params['search_value']);
                }
                if (count($this->columnSearch) - 1 == $i)
                    $this->dt->groupEnd();
            }
            $i++;
        }
        $where = @$params['where'];


        if ($params['order']) {
            $this->dt->orderBy($params['order_column'], $params['order_dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->dt->orderBy(key($order), $order[key($order)]);
        }
        return $this->dt;
    }

    public function getDatatables($params)
    {
        $this->getDatatablesQuery($params);
        if ($params['length'] != -1)
            $this->dt->limit($params['length'], $params['start']);
        $query = $this->dt->get();
        return $query->getResult('array');
    }

    public function countFiltered($params)
    {
        $this->getDatatablesQuery($params);
        return $this->dt->countAllResults();
    }

    public function countAll()
    {
        $tbl_storage = $this->db->table($this->table);
        return $tbl_storage->countAllResults();
    }

    public function getAuthGroupPermission($grp_prm_grp_label, $prm_apk_id)
    {
        $this->db = db_connect();
        $result = $this->db->table('tbl_auth_permission as ap')
                            ->select("prm_id,prm_nama, prm_label, prm_tipe, prm_ktg_nama, prm_ktg_id, apk_nama ,CASE 
                                        WHEN agp.grp_prm_grp_label = '$grp_prm_grp_label' THEN '1'
                                        ELSE '0' END AS permission")
                            ->join('tbl_auth_permission_kategori as apk', 'ap.prm_prm_ktg_id = apk.prm_ktg_id', 'left')
                            ->join('tbl_md_aplikasi', 'apk_id = prm_apk_id', 'left')
                            ->join('tbl_auth_group_permission as agp', 'ap.prm_label = agp.grp_prm_prm_label AND agp.grp_prm_grp_label = "'.$grp_prm_grp_label.'"', 'left')
                            ->join('tbl_auth_group as ag', 'ag.grp_label = agp.grp_prm_grp_label', 'left')
                            ->where('prm_apk_id', $prm_apk_id)
                            ->orderBy('apk_order', "ASC")
                            ->orderBy('prm_ktg_nama', 'ASC')
                            ->get()->getResultArray();
        return $result;
    }

    public function getPermissions($usr_id, $prm_type)
    {
        $this->db = db_connect();
        $result = $this->db
        ->table($this->table.' as p')
        ->select("p.prm_label, p.prm_nama,p.prm_kategori,p.prm_type,  CASE 
        WHEN up.usr_prm_usr_id = '$usr_id' THEN '1'
        ELSE '0' END AS permission")
        ->where('p.prm_type', $prm_type)
        ->join("tbl_user_permission as up", "up.usr_prm_label = p.prm_label AND up.usr_prm_usr_id = '$usr_id'", 'left')
        ->groupBy('p.prm_label, p.prm_nama, up.usr_prm_usr_id, p.prm_kategori, p.prm_type')
        ->orderBy('p.prm_kategori', 'ASC')
        ->orderBy('p.prm_type', 'ASC')
        ->get()->getResultArray();

        return $result;
    }
}

<?php

namespace App\Models\keu;

use CodeIgniter\Model;

class SettingApproverModel extends Model
{
    protected $table            = 'tbl_keu_setting_approver';
    protected $primaryKey       = 'set_appr_id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    protected $dateFormat    = 'datetime';

    protected $order = [
        'set_appr_level' => 'asc'
    ];

    private $columnSearch = [
        'set_appr_grp_label',
        'set_appr_level',
        'grp_nama',
    ];

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
        $this->dt->select('tbl_keu_setting_approver.*, grp_nama');
        $this->dt->join('tbl_auth_group', 'tbl_auth_group.grp_label = tbl_keu_setting_approver.set_appr_grp_label', 'left');
        
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
        if($where)
        {
            foreach($where as $key => $value)
            {
                $this->dt->where($key, $value);
            }
        }

        $whereIn = @$params['whereIn'];
        if($whereIn)
        {
            foreach($whereIn as $key => $value)
            {
                $this->dt->whereIn($key, $value);
            }
        }

        $this->dt->orderBy(key($this->order), $this->order[key($this->order)]);
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
}

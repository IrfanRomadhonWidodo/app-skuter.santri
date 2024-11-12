<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthUserGroupModel extends Model
{
    protected $table            = 'tbl_auth_user_group';
    protected $primaryKey       = 'usr_grp_id';
    protected $returnType       = 'array';
    protected $protectFields    = false;

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
        $this->dt->join('tbl_md_user as u', 'u.usr_id = usr_grp_usr_id', 'left');
        
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
        if($where){
            foreach($where as $key => $value){
                if($value != null){
                    $this->dt->where($key, $value);
                }
            }
        }
        
        if ($params['order']) {
            $this->dt->orderBy($params['order_column'], $params['order_dir']);
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

    public function getData($params, $first = false)
    {
        $where = @$params['where'];
        $like = @$params['like'];
        $order_by = @$params['order_by'];
        $select = @$params['select'];

        if($select)
        {
            $this->select($select);
        }
        $this->join('tbl_md_user as u', 'u.usr_id = usr_grp_usr_id', 'left');
        $this->join('tbl_auth_group as g', 'g.grp_label = usr_grp_grp_label', 'left');
        
        if($where){
            foreach($where as $key => $value){
                if($value != null){
                    $this->where($key, $value);
                }
            }
        }

        if($like)
        {
            $row = 1;
            foreach($like as $key => $value)
            {
                if($row == 1)
                {
                    $this->groupStart();
                    $this->like($key, $value);
                }else{
                    $this->orLike($key, $value);
                }

                if($row == count($like))
                {
                    $this->groupEnd();
                }
                $row++;
            }
        }

        if($order_by)
        {
            foreach($order_by as $key => $value)
            {
                $this->orderBy($key, $value);
            }
        }

        if($first)
            return $this->first();
        else
            return $this->get()->getResultArray();

    }

}

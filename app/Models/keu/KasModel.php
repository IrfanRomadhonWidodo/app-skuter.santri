<?php

namespace App\Models\keu;

use CodeIgniter\Model;

class KasModel extends Model
{
    protected $table            = 'tbl_keu_kas';
    protected $primaryKey       = 'kas_id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'kas_created_at';
    protected $updatedField  = 'kas_updated_at';

    var $order = ['tbl_keu_kas.kas_created_at', 'DESC'];

    private $columnSearch = [
        'kas_id',
        'kas_usr_id',
        'kas_judul',
        'kas_keterangan',
        'kas_total',
        'unkj_nama',
        'usr_nama',
        'prd_tahun'
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
        $this->dt->select('tbl_keu_kas.*, usr_nama, unkj_nama, prd_tahun');
        $this->dt->join('tbl_md_user', 'tbl_md_user.usr_id = tbl_keu_kas.kas_usr_id', 'left');
        $this->dt->join('tbl_md_unit_kerja', 'tbl_md_unit_kerja.unkj_id = tbl_keu_kas.kas_unkj_id', 'left');
        $this->dt->join('tbl_keu_periode', 'tbl_keu_kas.kas_prd_id = tbl_keu_periode.prd_id', 'left');
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

    public function gets($params, $first = false)
    {
        $where = @$params['where'];
        $like = @$params['like'];
        $order_by = @$params['order_by'];

        $this->select('tbl_keu_kas.*, usr_nama, unkj_nama');
        $this->join('tbl_md_user', 'tbl_md_user.usr_id = tbl_keu_kas.kas_usr_id', 'left');
        $this->join('tbl_md_unit_kerja', 'tbl_md_unit_kerja.unkj_id = tbl_keu_kas.kas_unkj_id', 'left');

        if($where)
        {
            foreach($where as $key => $value)
            {
                $this->where($key, $value);
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
            foreach($order_by as $column => $direction)
            {
                $this->orderBy($column, $direction);
            }
        }

        if($first)
        {
            return $this->first();
        }
        else
        {
            return $this->get()->getResultArray();
        }

    }
}

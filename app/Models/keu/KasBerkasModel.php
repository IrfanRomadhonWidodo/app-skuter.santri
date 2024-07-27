<?php

namespace App\Models\keu;

use CodeIgniter\Model;

class KasBerkasModel extends Model
{
    protected $table            = 'tbl_keu_kas_berkas';
    protected $primaryKey       = 'kas_bks_id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'kas_bks_created_at';
    protected $updatedField  = 'kas_bks_updated_at';

    var $order = ['tbl_keu_kas.kas_bks_created_at', 'DESC'];

    private $columnSearch = [
        'kas_id',
        'kas_usr_id',
        'kas_judul',
        'kas_keterangan',
        'kas_total',
        'unkj_nama',
        'usr_nama',
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
        $this->dt->select('tbl_keu_kas.*, usr_nama, unkj_nama');
        $this->dt->join('tbl_md_user', 'tbl_md_user.usr_id = tbl_keu_kas.kas_usr_id');
        $this->dt->join('tbl_unit_kerja', 'tbl_unit_kerja.unkj_id = tbl_md_user.usr_unkj_id');
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

    public function gets($params, $first = false)
    {
        $where = @$params['where'];
        $like = @$params['like'];
        $order_by = @$params['order_by'];

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

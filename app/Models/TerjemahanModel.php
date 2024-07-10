<?php

namespace App\Models;

use CodeIgniter\Model;

class TerjemahanModel extends Model
{
    protected $table            = 'tbl_md_terjemahan';
    protected $primaryKey       = 'tjmh_id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'tjmh_created_at';
    protected $updatedField  = 'tjmh_updated_at';

    var $order = ['tbl_md_terjemahan.tjmh_created_at', 'DESC'];

    private $columnSearch = [
        'tjmh_id',
        'tjmh_label',
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
}

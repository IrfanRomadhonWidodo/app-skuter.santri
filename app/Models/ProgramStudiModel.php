<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgramStudiModel extends Model
{
    protected $table            = 'tbl_md_program_studi';
    protected $primaryKey       = 'prodi_id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'prodi_created_at';
    protected $updatedField  = 'prodi_updated_at';

    var $order = ['tbl_md_program_studi.prodi_created_at', 'DESC'];

    private $columnSearch = [
        'prodi_nama',
        'prodi_id',
        'fk_nama',
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
        $this->dt->select('tbl_md_program_studi.*, fk_nama');
        $this->dt->join('tbl_md_fakultas', 'fk_id = prodi_fk_id', 'left');
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

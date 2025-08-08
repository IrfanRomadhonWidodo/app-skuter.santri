<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdiJalurPendaftaranModel extends Model
{
    protected $table            = 'tbl_pmb_prodi_jalur_pendaftaran';
    protected $primaryKey       = 'pjp_id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;

    private $columnSearch = [
        'pjp_id',
        'pjp_prodi_id',
        'pjp_jape_id',
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

        $this->dt->join('tbl_md_program_studi', 'prodi_id = pjp_prodi_id', 'left');
        $this->dt->join('tbl_pmb_jalur_pendaftaran', 'jape_id = pjp_jape_id', 'left');
        $this->dt->join('tbl_md_fakultas', 'fk_id = prodi_fk_id', 'left');

        if (!empty($params['where'])) {
            foreach ($params['where'] as $key => $value) {
                if (!empty($value)) {
                    $this->dt->where($key, $value);
                }
            }
        }

        if (!empty($params['search_value'])) {
            $i = 0;
            foreach ($this->columnSearch as $item) {
                if ($i === 0) {
                    $this->dt->groupStart();
                    $this->dt->like($item, $params['search_value']);
                } else {
                    $this->dt->orLike($item, $params['search_value']);
                }
                if (count($this->columnSearch) - 1 == $i) {
                    $this->dt->groupEnd();
                }
                $i++;
            }
        }

        if (!empty($params['order'])) {
            $this->dt->orderBy($params['order_column'], $params['order_dir']);
        } else {
            $this->dt->orderBy(key($this->order), $this->order[key($this->order)]);
        }
    }

    public function getDatatables($params)
    {
        $this->getDatatablesQuery($params);
        if ($params['length'] != -1) {
            $this->dt->limit($params['length'], $params['start']);
        }
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

        $this->join('tbl_pmb_jalur_pendaftaran', 'jape_id = pjp_jape_id', 'left');
        $this->join('tbl_md_program_studi', 'prodi_id = pjp_prodi_id', 'left');

        if ($where) {
            foreach ($where as $key => $value) {
                if ($value != null) {
                    $this->where($key, $value);
                }
            }
        }

        if ($like) {
            $row = 1;
            foreach ($like as $key => $value) {
                if ($value != null) {
                    if ($row == 1) {
                        $this->groupStart();
                        $this->like($key, $value);
                    } else {
                        $this->orLike($key, $value);
                    }
                    if ($row == count($like)) {
                        $this->groupEnd();
                    }
                    $row++;
                }
            }
        }

        if ($order_by) {
            foreach ($order_by as $key => $value) {
                if ($value != null) {
                    $this->orderBy($key, $value);
                }
            }
        }

        if ($first) {
            return $this->first();
        } else {
            return $this->findAll();
        }
    }
}

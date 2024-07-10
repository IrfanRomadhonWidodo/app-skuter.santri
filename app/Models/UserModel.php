<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'tbl_md_user';
    protected $primaryKey       = 'usr_id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'usr_created_at';
    protected $updatedField  = 'usr_updated_at';
    protected $deletedField  = 'usr_deleted_at';

    var $order = ['tbl_md_user.usr_created_at', 'DESC'];

    private $columnSearch = [
        'usr_id',
        'usr_nama',
        'usr_email',
        'usr_nik',
        'usr_tempat_lahir',
        'usr_tanggal_lahir',
        'usr_nomor_hp',
        'usr_email',
        'usr_agama',
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

        $type = @$params['type'];
        switch ($type) {
            case 'mahasiswa':
                $array = [
                    'usr_mhs_angkatan',
                    'prodi_nama',
                ];
                array_push($this->columnSearch, $array);
    
                $this->dt->select('usr_id, usr_nama, prodi_nama, fk_nama, usr_mhs_angkatan, usr_status');
                $this->dt->join('tbl_md_user_mahasiswa', 'tbl_md_user.usr_id = tbl_md_user_mahasiswa.usr_mhs_usr_id', 'left');
                $this->dt->join('tbl_md_program_studi', 'tbl_md_user_mahasiswa.usr_mhs_prodi_id = tbl_md_program_studi.prodi_id', 'left');
                $this->dt->join('tbl_md_fakultas', 'tbl_md_program_studi.prodi_fk_id = tbl_md_fakultas.fk_id', 'left');
    
                $this->dt->where('usr_aktif', '1');
                $this->dt->where('usr_deleted_at', null);
    
                $this->groupStart();
                $this->dt->like('usr_otoritas', 'mahasiswa');
                $this->groupEnd();
            break;
            case 'dosen-pegawai':
                $array = [
                    'usr_dsn_npp',
                    'usr_dsn_nik',
                    'prodi_nama',
                ];
                array_push($this->columnSearch, $array);

                $this->dt->select('usr_id, usr_nama, prodi_nama, fk_nama, usr_dsn_nik, usr_status');
                $this->dt->join('tbl_md_user_dosen', 'tbl_md_user.usr_id = tbl_md_user_dosen.usr_dsn_usr_id', 'left');
                $this->dt->join('tbl_md_program_studi', 'tbl_md_user_dosen.usr_dsn_prodi_id = tbl_md_program_studi.prodi_id', 'left');
                $this->dt->join('tbl_md_fakultas', 'tbl_md_program_studi.prodi_fk_id = tbl_md_fakultas.fk_id', 'left');
    
                $this->dt->where('usr_aktif', '1');
                $this->dt->where('usr_deleted_at', null);
    
                $this->groupStart();
                $this->dt->like('usr_otoritas', 'dosen-pegawai');
                $this->groupEnd();
            break;
        }

        $i = 0;
        foreach ($this->columnSearch as $item) {
            if ($params['search_value']) {
                if ($i === 0) {
                    $this->dt->groupStart();
                    $this->dt->like($item,$params['search_value']);
                } else {
                    $this->dt->orLike($item,$params['search_value']);
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
        $type = @$params['type'];
        $order_by = @$params['order_by'];

        switch($type)
        {
            case 'mahasiswa':
                $this->select('tbl_md_user.*, tbl_md_user_mahasiswa.*, prodi_nama, fk_nama');
                $this->join('tbl_md_user_mahasiswa', 'usr_id = usr_mhs_usr_id', 'left');
                $this->join('tbl_md_program_studi', 'usr_mhs_prodi_id = prodi_id', 'left');
                $this->join('tbl_md_fakultas', 'prodi_fk_id = fk_id', 'left');
            break;
            case 'dosen-pegawai':
                $this->select('tbl_md_user.*, tbl_md_user_dosen.*, prodi_nama, fk_nama');
                $this->join('tbl_md_user_dosen', 'usr_id = usr_dsn_usr_id', 'left');
                $this->join('tbl_md_program_studi', 'usr_dsn_prodi_id = prodi_id', 'left');
                $this->join('tbl_md_fakultas', 'prodi_fk_id = fk_id', 'left');
            break;
        }
    
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

<?php

namespace App\Controllers;

use  App\Modules\Breadcrumbs\Breadcrumbs;

class Home extends BaseController
{
    public function __construct()
    {
        $this->breadcrumb           = new Breadcrumbs();
    }
    public function index(): string
    {
        $this->breadcrumb->add('Dashboard', '/');

        $data = [
            'title' => getenv('app.name') . ' - Dashboard',
            'page'  => 'Dashboard',
            'breadcrumbs' => $this->breadcrumb->render(),
        ];

        return view('index', $data);
    }

    // public function chartPresentasePembayaran()
    // {
    //     $prodi = $this->->gets([
    //         'where' => [
    //             'spp_deleted_at' => null,
    //         ],
    //         'order_by' => [
    //             'fk_id' => 'asc',
    //             'prodi_id' => 'asc'
    //         ],
    //         'select' => 'prodi_id, prodi_nama, fk_id, fk_nama',
    //     ]);

    //     foreach ($prodi as &$item) {
    //         $item['jml_pilihan_1'] = 0;
    //         $item['jml_pilihan_2'] = 0;
    //         $item['jml_pilihan_diterima'] = 0;
    //     }

    //     $usr_camaba = $this->userModel->select('usr_id, usr_prodi_1, usr_prodi_2, usr_prodi_diterima')
    //         ->join('tbl_pmb_user_camaba', 'usr_id = usrc_id')
    //         ->where('usr_deleted_at', null)
    //         ->where('usr_role', 'USER')->findAll();

    //     foreach ($usr_camaba as $value) {
    //         $index1 = array_search($value['usr_prodi_1'], array_column($prodi, 'prodi_id'));
    //         if ($index1 !== false) {
    //             $prodi[$index1]['jml_pilihan_1'] += 1;
    //         }

    //         $index2 = array_search($value['usr_prodi_2'], array_column($prodi, 'prodi_id'));
    //         if ($index2 !== false) {
    //             $prodi[$index2]['jml_pilihan_2'] += 1;
    //         }

    //         $index3 = array_search($value['usr_prodi_diterima'], array_column($prodi, 'prodi_id'));
    //         if ($index3 !== false) {
    //             $prodi[$index3]['jml_pilihan_diterima'] += 1;
    //         }
    //     }

    //     return json_encode($prodi);
    // }
}

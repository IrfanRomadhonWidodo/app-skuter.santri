<?php

namespace App\Controllers;

use  App\Modules\Breadcrumbs\Breadcrumbs;

use function PHPUnit\Framework\throwException;

class PengajuanKas extends BaseController
{
    protected $breadcrumb;
    protected $validation;
    protected $kasModel;
    protected $kasBerkasModel;
    protected $unitKerjaModel;
    protected $periodeModel;
    public function __construct()
    {
        $this->breadcrumb   = new Breadcrumbs();
        $this->validation   = \Config\Services::validation();
        $this->kasModel     = new \App\Models\keu\KasModel();
        $this->unitKerjaModel = new \App\Models\UnitKerjaModel();
        $this->kasBerkasModel = new \App\Models\keu\KasBerkasModel();
        $this->periodeModel = new \App\Models\keu\PeriodeModel();
    }
    public function index(): string
    {
        $this->breadcrumb->add('Pengajuan KAS', '/');

        $data = [
            'title' => getenv('app.name') . ' - Pengajuan KAS',
            'page'  => 'Pengajuan KAS',
            'breadcrumbs' => $this->breadcrumb->render(),
        ];
        return view('pengajuan-kas/index', $data);
    }

    public function detail($kas_id): string
    {
        $this->breadcrumb->add('Pengajuan KAS', '/penngajuan-kas');
        $this->breadcrumb->add('Detail', '/pengajuan-kas/' . $kas_id);


        $pengajuan = $this->kasModel->gets([
            'where' => [
                'kas_id' => $kas_id
            ]
        ], true);

        $data = [
            'title' => getenv('app.name') . ' - Detail Pengajuan KAS',
            'page'  => 'Detail Pengajuan KAS',
            'breadcrumbs' => $this->breadcrumb->render(),
            'pengajuan' => $pengajuan
        ];
        return view('pengajuan-kas/detail', $data);
    }

    public function persetujuan($kas_id): string
    {
        $this->breadcrumb->add('Pengajuan KAS', '/penngajuan-kas');
        $this->breadcrumb->add('Persetujuan', '/pengajuan-kas/' . $kas_id);


        $pengajuan = $this->kasModel->gets([
            'where' => [
                'kas_id' => $kas_id
            ]
        ], true);

        $data = [
            'title' => getenv('app.name') . ' - Persetujuan Pengajuan KAS',
            'page'  => 'Persetujuan Pengajuan KAS',
            'breadcrumbs' => $this->breadcrumb->render(),
            'pengajuan' => $pengajuan
        ];
        return view('pengajuan-kas/persetujuan', $data);
    }

    public function createDraft(): string
    {
        if ($this->request->isAJAX()) {
            $data = [
                'kas_id'        => $this->create_uuid(),
                'kas_created_by' => session()->usr_id,
            ];
            try {
                $this->kasModel->insert($data);
            } catch (\Exception $e) {
                $msg = [
                    'error' => [
                        'msg' => $e->getMessage(),
                    ]
                ];
                return json_encode($msg);
            }
            $msg = [
                'success' => [
                    'msg'       => 'Berhasil menambah pengajuan KAS baru',
                    'redirect'  => base_url('pengajuan-kas/' . $data['kas_id']),
                ]
            ];
            return json_encode($msg);
        } else {
            exit('No direct script access allowed');
        }
    }


    public function draft($kas_id): string
    {
        $this->breadcrumb->add('Pengajuan KAS', '/penngajuan-kas');
        $this->breadcrumb->add('Draft', '/pengajuan-kas/' . $kas_id);

        $params = [
            'where' => [
                'kas_id' => $kas_id
            ]
        ];

        try {
            $kas = $this->kasModel->gets($params, true);
            $unit = $this->unitKerjaModel->findAll();
        } catch (\Exception $e) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => getenv('app.name') . ' - Pengajuan KAS Draft',
            'page'  => 'Draft Pengajuan KAS',
            'breadcrumbs' => $this->breadcrumb->render(),
            'kas' => $kas,
            'unit' => $unit
        ];

        return view('pengajuan-kas/draft', $data);
    }

    public function autosave($kas_id)
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $value = $this->request->getPost('value');

            $data = [
                $id => $value
            ];
            try {
                $this->kasModel->update($kas_id, $data);
                $msg = [
                    'success' => [
                        $id => 'Berhasil menyimpan perubahan',
                    ]
                ];
                return json_encode($msg);
            } catch (\Exception $e) {
                $msg = [
                    'error' => [
                        $id => 'Gagal mengubah data, silahkan coba lagi',
                    ]
                ];
                return json_encode($msg);
            }
        }
    }

    public function submit($kas_id)
    {
        if ($this->request->isAJAX()) {
            $required = [
                'kas_judul',
                'kas_keterangan',
                'kas_unkj_id',
                'kas_nominal'
            ];

            try {
                $kas = $this->kasModel->find($kas_id);
                $kasBerkas = $this->kasBerkasModel->where('kas_bks_kas_id', $kas_id)->findAll();
                $periode = $this->periodeModel->where('prd_aktif', 1)->first();
            } catch (\Exception $e) {
                $msg = [
                    'error' => [
                        'msg' => 'Gagal mengirim pengajuan, silahkan coba lagi, Error code : TC001 - ' . $e->getMessage(),
                    ]
                ];
                return json_encode($msg);
            }

            $error = false;
            foreach ($required as $r) {
                if ($kas[$r] == null) {
                    $error = true;
                }
            }

            if ($error) {
                $msg = [
                    'error' => [
                        'msg' => 'Gagal mengirim pengajuan, silahkan coba lagi',
                        'reload' => true
                    ]
                ];
                return json_encode($msg);
            }

            if (empty($kasBerkas)) {
                $msg = [
                    'error' => [
                        'msg' => 'Silahkan tambahkan berkas terlebih dahulu, minimal 1 berkas',
                    ]
                ];
                return json_encode($msg);
            }

            $data = [
                'kas_status' => '1',
                'kas_submited_date' => date('Y-m-d H:i:s'),
                'kas_prd_id' => $periode['prd_id'] ?? null,
                'kas_updated_by' => session()->usr_id,
            ];
            try {
                $this->kasModel->update($kas_id, $data);
            } catch (\Exception $e) {
                $msg = [
                    'error' => [
                        'msg' => 'Gagal mengirim pengajuan, silahkan coba lagi, Error code : TC002 - ' . $e->getMessage(),
                    ]
                ];
                return json_encode($msg);
            }

            $msg = [
                'success' => [
                    'msg' => 'Berhasil mengirim pengajuan KAS, anda masih bisa mengubah data selama masih dalam tahap pengajuan',
                    'reload' => true
                ]
            ];
            return json_encode($msg);
        }
    }


    public function datatables()
    {
        if ($this->request->isAJAX()) {
            $params = [
                'length'        => isset($_POST['length']) ? intval($_POST['length']) : 10,
                'start'         => isset($_POST['start']) ? intval($_POST['start']) : 0,
                'search_value'  => isset($_POST['search']['value']) ? $_POST['search']['value'] : '',
                'order'         => isset($_POST['order']) ? $_POST['order'] : [],
                'order_column'  => isset($_POST['order'][0]['column']) ? $_POST['columns'][$_POST['order'][0]['column']]['data'] : '',
                'order_dir'     => isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : '',
                'whereIn'       => ['kas_status' => ['0', '1']],
            ];

            if ($params['order'][0]['column'] == '0') {
                $params['order_column'] = 'kas_created_at';
            }

            $lists = $this->kasModel->getDatatables($params);

            $output = [
                'draw'              => $this->request->getPost('draw'),
                'recordsTotal'      => $this->kasModel->countAll(),
                'recordsFiltered'   => $this->kasModel->countFiltered($params),
                'data'              => $lists,
                'params'            => $params,
            ];
            return json_encode($output);
        }
    }
}

<?php

namespace App\Controllers;

use  App\Modules\Breadcrumbs\Breadcrumbs;

class MataKuliah extends BaseController
{
    protected $periodeAkademikModel;
    protected $fakultasModel;
    protected $programStudiModel;
    protected $mataKuliahModel;
    protected $agamaModel;
    protected $breadcrumb;
    protected $validation;
    public function __construct()
    {

        $this->periodeAkademikModel        = new \App\Models\PeriodeAkademikModel();
        $this->fakultasModel        = new \App\Models\FakultasModel();
        $this->programStudiModel    = new \App\Models\ProgramStudiModel();
        $this->mataKuliahModel    = new \App\Models\MataKuliahModel();
        $this->agamaModel    = new \App\Models\AgamaModel();
        $this->breadcrumb           = new Breadcrumbs();
        $this->validation           = \Config\Services::validation();
    }
    public function index(): string
    {
        $this->breadcrumb->add('Mata Kuliah', '/');

        $data = [
            'title' => getenv('app.name') . ' - Mata Kuliah',
            'page'  => 'Mata Kuliah',
            'breadcrumbs' => $this->breadcrumb->render(),
        ];

        return view('mata-kuliah/index', $data);
    }

    public function modalCreate(): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalCreate';
            $data['modalId'] = $id;
            $data['agama'] = $this->agamaModel->findAll();

            $msg = [
                'modal' => [
                    'view' => view('mata-kuliah/modal/create', $data),
                    'id' => $id,
                ],
            ];
            return json_encode($msg);
        } else {
            exit('No direct script access allowed');
        }
    }

    public function modalEdit($mk_id): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalEdit';
            $data['modalId'] = $id;
            $data['agama'] = $this->agamaModel->findAll();

            $data['mataKuliah'] = $this->mataKuliahModel->find($mk_id);

            $msg = [
                'modal' => [
                    'view' => view('mata-kuliah/modal/edit', $data),
                    'id' => $id,
                ],
            ];
            return json_encode($msg);
        } else {
            exit('No direct script access allowed');
        }
    }

    public function save(): string
    {
        if ($this->request->isAJAX()) {

            $rules = [
                'mk_id' => [
                    'rules' => 'required|is_unique[tbl_md_mata_kuliah.mk_id]|max_length[10]',
                    'label' => 'Kode Mata Kuliah',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_unique' => '{field} sudah ada. Silahkan masukkan {field} yang berbeda.',
                        'max_length' => '{field} harus 10 digit.'
                    ]
                ],
                'mk_nama' => [
                    'rules' => 'required|max_length[255]|is_unique[tbl_md_mata_kuliah.mk_nama]',
                    'label' => 'Mata Kuliah',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_unique' => '{field} sudah ada. Silahkan masukkan {field} yang berbeda.',
                        'max_length' => '{field} maksimal 255 karakter.',
                    ]
                ],
                'mk_nama_en' => [
                    'rules' => 'required|max_length[255]|is_unique[tbl_md_mata_kuliah.mk_nama_en]',
                    'label' => 'Mata Kuliah',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_unique' => '{field} sudah ada. Silahkan masukkan {field} yang berbeda.',
                        'max_length' => '{field} maksimal 255 karakter.',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                $msg = [
                    'error' => $this->validation->getErrors(),
                ];
                return json_encode($msg);
            }

            try {
                $data = [
                    'mk_id'         => $this->request->getPost('mk_id'),
                    'mk_nama'       => $this->request->getPost('mk_nama'),
                    'mk_nama_en'       => $this->request->getPost('mk_nama_en'),
                    'mk_agama'       => $this->request->getPost('mk_agama'),
                    'mk_sks'       => $this->request->getPost('mk_sks'),
                    'mk_aktif'       => 1,
                    'mk_created_by' => session()->usr_id
                ];
                $this->mataKuliahModel->insert($data);
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
                    'msg' => 'Data Mata Kuliah berhasil ditambahkan.',
                ]
            ];
            return json_encode($msg);
        }
    }

    public function update($mk_id): string
    {
        if ($this->request->isAJAX()) {

            $mataKuliah = $this->mataKuliahModel->find($mk_id);
            if ($mataKuliah['mk_id'] == $this->request->getPost('mk_id')) {
                $rules_mk_id = 'required|max_length[10]';
            } else {
                $rules_mk_id = 'required|is_unique[tbl_mk_mata_kuliah.mk_id]|max_length[10]';
            }
            if ($mataKuliah['mk_nama'] == $this->request->getPost('mk_nama')) {
                $rules_mk_nama = 'required|max_length[255]';
            } else {
                $rules_mk_nama = 'required|is_unique[tbl_md_mata_kuliah.mk_nama]|max_length[255]';
            }
            if ($mataKuliah['mk_nama_en'] == $this->request->getPost('mk_nama_en')) {
                $rules_mk_nama_en = 'required|max_length[255]';
            } else {
                $rules_mk_nama_en = 'required|is_unique[tbl_md_mata_kuliah.mk_nama_en]|max_length[255]';
            }

            $rules = [
                'mk_id' => [
                    'rules' => $rules_mk_id,
                    'label' => 'Kode Mata Kuliah',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_unique' => '{field} sudah ada. Silahkan masukkan {field} yang berbeda.',
                        'max_length' => '{field} harus 10 digit.'
                    ]
                ],
                'mk_nama' => [
                    'rules' => $rules_mk_nama,
                    'label' => 'Mata Kuliah',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_unique' => '{field} sudah ada. Silahkan masukkan {field} yang berbeda.',
                        'max_length' => '{field} maksimal 255 karakter.',
                    ]
                ],
                'mk_nama_en' => [
                    'rules' => $rules_mk_nama_en,
                    'label' => 'Mata Kuliah',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_unique' => '{field} sudah ada. Silahkan masukkan {field} yang berbeda.',
                        'max_length' => '{field} maksimal 255 karakter.',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                $msg = [
                    'error' => $this->validation->getErrors(),
                ];
            } else {

                if ($mataKuliah['mk_id'] != $this->request->getPost('mk_id')) {
                    $update_mk_id = $this->request->getPost('mk_id');
                } else {
                    $update_mk_id = $mk_id;
                }

                try {
                    $data = [
                        'mk_id'         => $update_mk_id,
                        'mk_nama'       => $this->request->getPost('mk_nama'),
                        'mk_nama_en'       => $this->request->getPost('mk_nama_en'),
                        'mk_agama'       => $this->request->getPost('mk_agama'),
                        'mk_sks'       => $this->request->getPost('mk_sks'),
                        'mk_aktif'       => 1,
                        'mk_created_by' => session()->usr_id
                    ];
                    $this->mataKuliahModel->update($mk_id, $data);
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
                        'msg' => 'Data Mata Kuliah berhasil ditambahkan.',
                    ]
                ];
                return json_encode($msg);
            }
        }
    }

    public function delete($mk_id)
    {
        if ($this->request->isAJAX()) {
            $this->mataKuliahModel->delete($mk_id);

            $msg = [
                'success' => [
                    'msg' => 'Berhasil menghapus data mata kuliah.',
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
            ];
            $lists = $this->mataKuliahModel->getDatatables($params);

            $output = [
                'draw'              => $this->request->getPost('draw'),
                'recordsTotal'      => $this->mataKuliahModel->countAll(),
                'recordsFiltered'   => $this->mataKuliahModel->countFiltered($params),
                'data'              => $lists,
                'params'            => $params,
            ];
            return json_encode($output);
        }
    }
}

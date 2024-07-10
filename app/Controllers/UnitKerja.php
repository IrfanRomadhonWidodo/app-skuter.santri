<?php

namespace App\Controllers;

use  App\Modules\Breadcrumbs\Breadcrumbs;

class UnitKerja extends BaseController
{
    protected $breadcrumb;
    protected $unitKerjaModel;
    protected $validation;
    public function __construct()
    {
        $this->unitKerjaModel       = new \App\Models\UnitKerjaModel();
        $this->breadcrumb           = new Breadcrumbs();
        $this->validation           = \Config\Services::validation();
    }
    public function index(): string
    {
        $this->breadcrumb->add('Unit Kerja', '/');

        $data = [
            'title' => getenv('app.name') . ' - Unit Kerja',
            'page'  => 'Unit Kerja',
            'breadcrumbs' => $this->breadcrumb->render(),
        ];

        return view('unit-kerja/index', $data);
    }

    public function modalCreate(): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalCreate';
            $data['modalId'] = $id;

            $msg = [
                'modal' => [
                    'view' => view('unit-kerja/modal/create', $data),
                    'id' => $id,
                ],
            ];
            return json_encode($msg);
        } else {
            exit('No direct script access allowed');
        }
    }

    public function modalEdit($unkj_id): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalEdit';
            $data['modalId'] = $id;

            $data['unit_kerja'] = $this->unitKerjaModel->find($unkj_id);

            $msg = [
                'modal' => [
                    'view' => view('unit-kerja/modal/edit', $data),
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
                'unkj_id' => [
                    'rules' => 'required|is_unique[tbl_md_unit_kerja.unkj_id]|max_length[6]|min_length[6]',
                    'label' => 'Kode Unit',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_unique' => '{field} sudah terdaftar.',
                        'max_length' => '{field} maksimal 6 karakter.',
                        'min_length' => '{field} minimal 6 karakter.',
                    ]
                ],
                'unkj_nama' => [
                    'rules' => 'required|is_unique[tbl_md_unit_kerja.unkj_nama]',
                    'label' => 'Nama Unit',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                    ]
                ],
                'unkj_keterangan' => [
                    'rules' => 'required',
                    'label' => 'Keterangan',
                    'errors' => [
                        'required' => '{field} harus diisi.',
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
                    'unkj_id' => $this->request->getPost('unkj_id'),
                    'unkj_nama' => $this->request->getPost('unkj_nama'),
                    'unkj_keterangan' => $this->request->getPost('unkj_keterangan'),
                ];
                $this->unitKerjaModel->insert($data);
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
                    'msg' => 'Data unit kerja berhasil ditambahkan.',
                ]
            ];
            return json_encode($msg);
        }
    }

    public function update($unkj_id): string
    {
        if ($this->request->isAJAX()) {
            $unitKerja = $this->unitKerjaModel->find($unkj_id);

            if($unitKerja['unkj_id'] == $this->request->getPost('unkj_id')) {
                $rules_unkj_id = 'required|max_length[6]|min_length[6]';
            } else {
                $rules_unkj_id = 'required|is_unique[tbl_md_unit_kerja.unkj_id]|max_length[6]|min_length[6]';
            }

            if($unitKerja['unkj_nama'] == $this->request->getPost('unkj_nama')) {
                $rules_unkj_nama = 'required';
            } else {
                $rules_unkj_nama = 'required|is_unique[tbl_md_unit_kerja.unkj_nama]';
            }

            $rules = [
                'unkj_id' => [
                    'rules' => $rules_unkj_id,
                    'label' => 'Nilai Huruf',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_unique' => '{field} sudah terdaftar.',
                        'max_length' => '{field} maksimal 6 karakter.',
                        'min_length' => '{field} minimal 6 karakter.',
                    ]
                ],
                'unkj_nama' => [
                    'rules' => $rules_unkj_nama,
                    'label' => 'Nama Unit',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_unique' => '{field} sudah terdaftar.',
                    ]
                ],
                'unkj_keterangan' => [
                    'rules' => 'required|max_length[100]',
                    'label' => 'Keterangan',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'max_length' => '{field} maksimal 100 karakter.',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                $msg = [
                    'error' => $this->validator->getErrors(),
                ];
                return json_encode($msg);
            }

            try {
                $data = [
                    'unkj_id' => $this->request->getPost('unkj_id'),
                    'unkj_nama' => $this->request->getPost('unkj_nama'),
                    'unkj_keterangan' => $this->request->getPost('unkj_keterangan'),
                ];
                $this->unitKerjaModel->update($unkj_id, $data);
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
                    'msg' => 'Data unit kerja berhasil diperbarui.',
                ]
            ];
            return json_encode($msg);
        }
    }

    public function delete($unkj_id)
    {
        if ($this->request->isAJAX()) {
            $this->unitKerjaModel->delete($unkj_id);

            $msg = [
                'success' => [
                    'msg' => 'Berhasil menghapus data unit kerja.',
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
            $lists = $this->unitKerjaModel->getDatatables($params);

            $output = [
                'draw'              => $this->request->getPost('draw'),
                'recordsTotal'      => $this->unitKerjaModel->countAll(),
                'recordsFiltered'   => $this->unitKerjaModel->countFiltered($params),
                'data'              => $lists,
                'params'            => $params,
            ];
            return json_encode($output);
        }
    }
}

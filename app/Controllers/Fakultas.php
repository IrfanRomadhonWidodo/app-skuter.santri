<?php

namespace App\Controllers;

use  App\Modules\Breadcrumbs\Breadcrumbs;

class Fakultas extends BaseController
{
    protected $fakultasModel;
    protected $programStudiModel;
    protected $breadcrumb;
    protected $validation;
    public function __construct()
    {

        $this->fakultasModel        = new \App\Models\FakultasModel();
        $this->programStudiModel    = new \App\Models\ProgramStudiModel();
        $this->breadcrumb           = new Breadcrumbs();
        $this->validation           = \Config\Services::validation();
    }
    public function index(): string
    {
        $this->breadcrumb->add('Fakultas', '/');

        $data = [
            'title' => getenv('app.name') . ' - Fakultas',
            'page'  => 'Fakultas',
            'breadcrumbs' => $this->breadcrumb->render(),
        ];

        return view('fakultas/index', $data);
    }

    public function modalCreate(): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalCreate';
            $data['modalId'] = $id;

            $msg = [
                'modal' => [
                    'view' => view('fakultas/modal/create', $data),
                    'id' => $id,
                ],
            ];
            return json_encode($msg);
        } else {
            exit('No direct script access allowed');
        }
    }

    public function modalEdit($fk_id): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalEdit';
            $data['modalId'] = $id;

            $data['fakultas'] = $this->fakultasModel->find($fk_id);

            $msg = [
                'modal' => [
                    'view' => view('fakultas/modal/edit', $data),
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
                'fk_id' => [
                    'rules' => 'required|is_unique[tbl_md_fakultas.fk_id]|max_length[6]|min_length[6]',
                    'label' => 'Kode Fakultas',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_unique' => '{field} sudah ada. Silahkan masukkan {field} yang berbeda.',
                        'max_length' => '{field} harus 6 digit.',
                        'min_length' => '{field} harus 6 digit.',
                    ]
                ],
                'fk_nama' => [
                    'rules' => 'required|max_length[255]|is_unique[tbl_md_fakultas.fk_nama]',
                    'label' => 'Nama Fakultas',
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
                    'fk_id'         => $this->request->getPost('fk_id'),
                    'fk_nama'       => $this->request->getPost('fk_nama'),
                    'fk_created_by' => session()->usr_id
                ];
                $this->fakultasModel->insert($data);
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
                    'msg' => 'Data Fakultas berhasil ditambahkan.',
                ]
            ];
            return json_encode($msg);
        }
    }

    public function update($fk_id): string
    {
        if ($this->request->isAJAX()) {
            
            $fakultas = $this->fakultasModel->find($fk_id);
            if($fakultas['fk_id'] == $this->request->getPost('fk_id'))
            {
                $rules_fk_id = 'required|max_length[6]|min_length[6]';
            }else{
                $rules_fk_id = 'required|is_unique[tbl_md_fakultas.fk_id]|max_length[6]|min_length[6]';
            }

            $rules = [
                'fk_id' => [
                    'rules' => $rules_fk_id,
                    'label' => 'Kode Fakultas',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_unique' => '{field} sudah ada. Silahkan masukkan {field} yang berbeda.',
                        'max_length' => '{field} harus 6 digit.',
                        'min_length' => '{field} harus 6 digit.',
                    ]
                ],
                'fk_nama' => [
                    'rules' => 'required|max_length[255]|is_unique[tbl_md_fakultas.fk_nama]',
                    'label' => 'Nama Fakultas',
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

                if($fakultas['fk_id'] != $this->request->getPost('fk_id'))
                {
                    $update_fk_id = $this->request->getPost('fk_id');
                }else{
                    $update_fk_id = $fk_id;
                }

                try {
                    $data = [
                        'fk_id'         => $update_fk_id,
                        'fk_nama'       => $this->request->getPost('fk_nama'),
                    ];
                    $this->fakultasModel->update($fk_id, $data);
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
                        'msg' => 'Data Fakultas berhasil ditambahkan.',
                    ]
                ];
                return json_encode($msg);
            }
        }
    }

    public function delete($fk_id)
    {
        if ($this->request->isAJAX()) {
            $this->fakultasModel->delete($fk_id);

            $msg = [
                'success' => [
                    'msg' => 'Berhasil menghapus data fakultas.',
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
            $lists = $this->fakultasModel->getDatatables($params);

            $output = [
                'draw'              => $this->request->getPost('draw'),
                'recordsTotal'      => $this->fakultasModel->countAll(),
                'recordsFiltered'   => $this->fakultasModel->countFiltered($params),
                'data'              => $lists,
                'params'            => $params,
            ];
            return json_encode($output);
        }
    }
}

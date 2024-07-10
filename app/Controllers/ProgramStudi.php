<?php

namespace App\Controllers;

use  App\Modules\Breadcrumbs\Breadcrumbs;

class ProgramStudi extends BaseController
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
    public function modalCreate(): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalCreate';
            $data['modalId'] = $id;
            $data['fakultas'] = $this->fakultasModel->findAll();

            $msg = [
                'modal' => [
                    'view' => view('fakultas/program-studi/modal/create', $data),
                    'id' => $id,
                ],
            ];
            return json_encode($msg);
        } else {
            exit('No direct script access allowed');
        }
    }

    public function modalEdit($prodi_id): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalEdit';
            $data['modalId'] = $id;

            $data['fakultas']   = $this->fakultasModel->findAll();
            $data['prodi']      = $this->programStudiModel->find($prodi_id);

            $msg = [
                'modal' => [
                    'view' => view('fakultas/program-studi/modal/edit', $data),
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
                'prodi_fk_id' => [
                    'rules' => 'required',
                    'label' => 'Fakultas',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                    ]
                ],
                'prodi_id' => [
                    'rules' => 'required|is_unique[tbl_md_program_studi.prodi_id]|max_length[6]|min_length[6]',
                    'label' => 'Kode Program Studi',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_unique' => '{field} sudah ada. Silahkan masukkan {field} yang berbeda.',
                        'max_length' => '{field} harus 6 digit.',
                        'min_length' => '{field} harus 6 digit.',
                    ]
                ],
                'prodi_nama' => [
                    'rules' => 'required|max_length[255]|is_unique[tbl_md_program_studi.prodi_nama]',
                    'label' => 'Nama Program Studi',
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
                    'prodi_id'      => $this->request->getPost('prodi_id'),
                    'prodi_fk_id'   => $this->request->getPost('prodi_fk_id'),
                    'prodi_nama'    => $this->request->getPost('prodi_nama'),
                    'prodi_created_by' => session()->usr_id
                ];
                $this->programStudiModel->insert($data);
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
                    'msg' => 'Data Program Studi berhasil ditambahkan.',
                ]
            ];
            return json_encode($msg);
        }
    }

    public function update($prodi_id)
    {
        if($this->request->isAJAX())
        {
            $prodi = $this->programStudiModel->find($prodi_id);
            if($prodi['prodi_id'] == $this->request->getPost('prodi_id')){
                $rulesprodi_id = 'required|max_length[6]|min_length[6]';
            }else{
                $rulesprodi_id = 'required|is_unique[tbl_md_program_studi.prodi_id]|max_length[6]|min_length[6]';
            } 

            if($prodi['prodi_nama'] == $this->request->getPost('prodi_nama')){
                $rulesprodi_nama = 'required|max_length[255]';
            }else{
                $rulesprodi_nama = 'required|max_length[255]|is_unique[tbl_md_program_studi.prodi_nama]';
            }
            $rules = [
                'prodi_fk_id' => [
                    'rules' => 'required',
                    'label' => 'Fakultas',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                    ]
                ],
                'prodi_id' => [
                    'rules' => $rulesprodi_id,
                    'label' => 'Kode Program Studi',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_unique' => '{field} sudah ada. Silahkan masukkan {field} yang berbeda.',
                        'max_length' => '{field} harus 6 digit.',
                        'min_length' => '{field} harus 6 digit.',
                    ]
                ],
                'prodi_nama' => [
                    'rules' => $rulesprodi_nama,
                    'label' => 'Nama Program Studi',
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
            } else {

                $prodi = $this->programStudiModel->find($prodi_id);

                if ($prodi['prodi_id'] != $this->request->getPost('prodi_id')) {
                    $updateprodi_id = $this->request->getPost('prodi_id');
                } else {
                    $updateprodi_id = $prodi_id;
                }

                $data = [
                    'prodi_id' => $updateprodi_id,
                    'prodi_nama'  => $this->request->getPost('prodi_nama'),
                    'prodi_fk_id' => $this->request->getPost('prodi_fk_id'),
                    'prodi_created_by' => session()->usr_id
                ];

                try {
                    $this->programStudiModel->update($prodi_id, $data);

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

    public function delete($prodi_id)
    {
        if ($this->request->isAJAX()) {
            $this->programStudiModel->delete($prodi_id);

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
            $lists = $this->programStudiModel->getDatatables($params);

            $output = [
                'draw'              => $this->request->getPost('draw'),
                'recordsTotal'      => $this->programStudiModel->countAll(),
                'recordsFiltered'   => $this->programStudiModel->countFiltered($params),
                'data'              => $lists,
                'params'            => $params,
            ];
            return json_encode($output);
        }
    }
}

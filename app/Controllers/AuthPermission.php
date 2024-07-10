<?php

namespace App\Controllers;

use  App\Modules\Breadcrumbs\Breadcrumbs;

class AuthPermission extends BaseController
{
    protected $breadcrumb;
    protected $authPermissioModel;
    protected $authPermissioKategoriModel;
    protected $configAplikasiModel;
    protected $authPermission;
    protected $validation;
    public function __construct()
    {
        $this->authPermissioModel   = new \App\Models\AuthPermissionModel();
        $this->authPermissioKategoriModel = new \App\Models\AuthPermissionKategoriModel();
        $this->configAplikasiModel = new \App\Models\ConfigAplikasiModel();
        $this->breadcrumb           = new Breadcrumbs();
        $this->validation           = \Config\Services::validation();
    }
    public function index(): string
    {
        $this->breadcrumb->add('Permission', '/');

        $data = [
            'title' => getenv('app.name') . ' - Permission',
            'page'  => 'Permission',
            'breadcrumbs' => $this->breadcrumb->render(),
        ];

        return view('auth-permission/index', $data);
    }

    public function modalCreate(): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalCreate';
            $data['modalId'] = $id;

            $data['permission_kategori'] = $this->authPermissioKategoriModel->findAll();
            $data['aplikasi'] = $this->configAplikasiModel->findAll();
            $msg = [
                'modal' => [
                    'view' => view('auth-permission/modal/create', $data),
                    'id' => $id,
                ],
            ];
            return json_encode($msg);
        } else {
            exit('No direct script access allowed');
        }
    }

    public function modalEdit($jbtn_id): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalEdit';
            $data['modalId'] = $id;

            $data['unit_kerja'] = $this->authPermissioModel->findAll();
            $data['permission'] = $this->authPermission->join('tbl_md_unit_kerja', 'unkj_id = jbtn_unkj_id')->find($jbtn_id);

            $msg = [
                'modal' => [
                    'view' => view('jabatan/modal/edit', $data),
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
                'jbtn_id' => [
                    'rules' => 'required|is_unique[tbl_md_jabatan.jbtn_id]|max_length[6]|min_length[6]',
                    'label' => 'Kode Jabatan',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_unique' => '{field} sudah terdaftar.',
                        'max_length' => '{field} maksimal 6 karakter.',
                        'min_length' => '{field} minimal 6 karakter.',
                    ]
                ],
                'jbtn_nama' => [
                    'rules' => 'required|is_unique[tbl_md_jabatan.jbtn_nama]',
                    'label' => 'Nama Jabatan',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                    ]
                ],
                'jbtn_keterangan' => [
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
                    'jbtn_id' => $this->request->getPost('jbtn_id'),
                    'jbtn_unkj_id' => $this->request->getPost('jbtn_unkj_id'),
                    'jbtn_nama' => $this->request->getPost('jbtn_nama'),
                    'jbtn_keterangan' => $this->request->getPost('jbtn_keterangan'),
                ];
                $this->authPermission->insert($data);
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
                    'msg' => 'Data jabatan berhasil ditambahkan.',
                ]
            ];
            return json_encode($msg);
        }
    }

    public function update($jbtn_id): string
    {
        if ($this->request->isAJAX()) {
            $unitKerja = $this->authPermission->find($jbtn_id);

            if($unitKerja['jbtn_id'] == $this->request->getPost('jbtn_id')) {
                $rules_jbtn_id = 'required|max_length[6]|min_length[6]';
            } else {
                $rules_jbtn_id = 'required|is_unique[tbl_md_jabatan.jbtn_id]|max_length[6]|min_length[6]';
            }

            if($unitKerja['jbtn_nama'] == $this->request->getPost('jbtn_nama')) {
                $rules_jbtn_nama = 'required';
            } else {
                $rules_jbtn_nama = 'required|is_unique[tbl_md_jabatan.jbtn_nama]';
            }

            $rules = [
                'jbtn_id' => [
                    'rules' => $rules_jbtn_id,
                    'label' => 'Kode Jabatan',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_unique' => '{field} sudah terdaftar.',
                        'max_length' => '{field} maksimal 6 karakter.',
                        'min_length' => '{field} minimal 6 karakter.',
                    ]
                ],
                'jbtn_nama' => [
                    'rules' => $rules_jbtn_nama,
                    'label' => 'Nama Jabatan',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_unique' => '{field} sudah terdaftar.',
                    ]
                ],
                'jbtn_keterangan' => [
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
                    'jbtn_id' => $this->request->getPost('jbtn_id'),
                    'jbtn_unkj_id' => $this->request->getPost('jbtn_unkj_id'),
                    'jbtn_nama' => $this->request->getPost('jbtn_nama'),
                    'jbtn_keterangan' => $this->request->getPost('jbtn_keterangan'),
                ];
                $this->authPermission->update($jbtn_id, $data);
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
                    'msg' => 'Data jabatan berhasil diperbarui.',
                ]
            ];
            return json_encode($msg);
        }
    }

    public function delete($jbtn_id)
    {
        if ($this->request->isAJAX()) {
            $this->authPermission->delete($jbtn_id);

            $msg = [
                'success' => [
                    'msg' => 'Berhasil menghapus data jabatan.',
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
            $lists = $this->authPermission->getDatatables($params);

            $output = [
                'draw'              => $this->request->getPost('draw'),
                'recordsTotal'      => $this->authPermission->countAll(),
                'recordsFiltered'   => $this->authPermission->countFiltered($params),
                'data'              => $lists,
                'params'            => $params,
            ];
            return json_encode($output);
        }
    }
}

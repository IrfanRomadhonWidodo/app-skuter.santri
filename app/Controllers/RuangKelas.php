<?php

namespace App\Controllers;

use  App\Modules\Breadcrumbs\Breadcrumbs;

class RuangKelas extends BaseController
{
    protected $periodeAkademikModel;
    protected $fakultasModel;
    protected $programStudiModel;
    protected $ruangKelasModel;
    protected $breadcrumb;
    protected $validation;
    public function __construct()
    {

        $this->periodeAkademikModel        = new \App\Models\PeriodeAkademikModel();
        $this->fakultasModel        = new \App\Models\FakultasModel();
        $this->programStudiModel    = new \App\Models\ProgramStudiModel();
        $this->ruangKelasModel    = new \App\Models\RuangKelasModel();
        $this->breadcrumb           = new Breadcrumbs();
        $this->validation           = \Config\Services::validation();
    }
    public function index(): string
    {
        $this->breadcrumb->add('Ruang Kelas', '/');

        $data = [
            'title' => getenv('app.name') . ' - Ruang Kelas',
            'page'  => 'Ruang Kelas',
            'breadcrumbs' => $this->breadcrumb->render(),
        ];

        return view('ruang-kelas/index', $data);
    }

    public function modalCreate(): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalCreate';
            $data['modalId'] = $id;

            $msg = [
                'modal' => [
                    'view' => view('ruang-kelas/modal/create', $data),
                    'id' => $id,
                ],
            ];
            return json_encode($msg);
        } else {
            exit('No direct script access allowed');
        }
    }

    public function modalEdit($rk_id): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalEdit';
            $data['modalId'] = $id;

            $data['ruangKelas'] = $this->ruangKelasModel->find($rk_id);

            $msg = [
                'modal' => [
                    'view' => view('ruang-kelas/modal/edit', $data),
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
                'rk_label' => [
                    'rules' => 'required|is_unique[tbl_md_ruang_kelas.rk_label]|max_length[10]',
                    'label' => 'Label Ruang Kelas',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_unique' => '{field} sudah ada. Silahkan masukkan {field} yang berbeda.',
                        'max_length' => '{field} harus 10 digit.',
                    ]
                ],
                'rk_lokasi' => [
                    'rules' => 'required|max_length[255]|is_unique[tbl_md_ruang_kelas.rk_lokasi]',
                    'label' => 'Lokasi Ruang Kelas',
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

                    'rk_label'       => $this->request->getPost('rk_label'),
                    'rk_lokasi'       => $this->request->getPost('rk_lokasi'),
                    'rk_created_by' => session()->usr_id
                ];
                $this->ruangKelasModel->insert($data);
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
                    'msg' => 'Data Ruang Kelas berhasil ditambahkan.',
                ]
            ];
            return json_encode($msg);
        }
    }

    public function update($rk_id): string
    {
        if ($this->request->isAJAX()) {
            $ruangKelas = $this->ruangKelasModel->find($rk_id);

            $rules_rk_label = 'required|max_length[10]';
            $rules_rk_lokasi = 'required|max_length[255]';

            if ($ruangKelas['rk_id'] == $this->request->getPost('rk_id')) {
                unset($rules_rk_lokasi);
            }

            $rules = [
                'rk_label' => [
                    'rules' => $rules_rk_label,
                    'label' => 'Label Ruang Kelas',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'max_length' => '{field} maksimal 10 karakter.',
                    ]
                ],
                'rk_lokasi' => [
                    'rules' => $rules_rk_lokasi,
                    'label' => 'Lokasi Ruang Kelas',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'max_length' => '{field} maksimal 255 karakter.',
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
                    'rk_label' => $this->request->getPost('rk_label'),
                    'rk_lokasi' => $this->request->getPost('rk_lokasi'),
                ];
                $this->ruangKelasModel->update($rk_id, $data);
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
                    'msg' => 'Data Ruang Kelas berhasil diperbarui.',
                ]
            ];
            return json_encode($msg);
        }
    }

    public function delete($rk_id)
    {
        if ($this->request->isAJAX()) {
            $this->ruangKelasModel->delete($rk_id);

            $msg = [
                'success' => [
                    'msg' => 'Berhasil menghapus data Ruang Kelas.',
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
            $lists = $this->ruangKelasModel->getDatatables($params);

            $output = [
                'draw'              => $this->request->getPost('draw'),
                'recordsTotal'      => $this->ruangKelasModel->countAll(),
                'recordsFiltered'   => $this->ruangKelasModel->countFiltered($params),
                'data'              => $lists,
                'params'            => $params,
            ];
            return json_encode($output);
        }
    }
}

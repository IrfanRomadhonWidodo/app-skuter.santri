<?php

namespace App\Controllers;

use  App\Modules\Breadcrumbs\Breadcrumbs;

class SetNilai extends BaseController
{
    protected $periodeAkademikModel;
    protected $fakultasModel;
    protected $programStudiModel;
    protected $ruangKelasModel;
    protected $setNilaiModel;
    protected $breadcrumb;
    protected $validation;
    public function __construct()
    {

        $this->periodeAkademikModel        = new \App\Models\PeriodeAkademikModel();
        $this->fakultasModel        = new \App\Models\FakultasModel();
        $this->programStudiModel    = new \App\Models\ProgramStudiModel();
        $this->ruangKelasModel    = new \App\Models\RuangKelasModel();
        $this->setNilaiModel    = new \App\Models\SetNilaiModel();
        $this->breadcrumb           = new Breadcrumbs();
        $this->validation           = \Config\Services::validation();
    }
    public function index(): string
    {
        $this->breadcrumb->add('Set Nilai', '/');

        $data = [
            'title' => getenv('app.name') . ' - Set Nilai',
            'page'  => 'Set Nilai',
            'breadcrumbs' => $this->breadcrumb->render(),
        ];

        return view('set-nilai/index', $data);
    }

    public function modalCreate(): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalCreate';
            $data['modalId'] = $id;

            $msg = [
                'modal' => [
                    'view' => view('set-nilai/modal/create', $data),
                    'id' => $id,
                ],
            ];
            return json_encode($msg);
        } else {
            exit('No direct script access allowed');
        }
    }

    public function modalEdit($stn_id): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalEdit';
            $data['modalId'] = $id;

            $data['setNilai'] = $this->setNilaiModel->find($stn_id);

            $msg = [
                'modal' => [
                    'view' => view('set-nilai/modal/edit', $data),
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
                'stn_nilai_huruf' => [
                    'rules' => 'required',
                    'label' => 'Nilai Huruf',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                    ]
                ],
                'stn_dari' => [
                    'rules' => 'required',
                    'label' => 'Dari (>)',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                    ]
                ],
                'stn_sampai' => [
                    'rules' => 'required',
                    'label' => 'Sampai (<=)',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                    ]
                ],
                'stn_bobot' => [
                    'rules' => 'required',
                    'label' => 'Bobot Nilai Huruf',
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
                    'stn_nilai_huruf' => $this->request->getPost('stn_nilai_huruf'),
                    'stn_dari' => $this->request->getPost('stn_dari'),
                    'stn_sampai' => $this->request->getPost('stn_sampai'),
                    'stn_bobot' => $this->request->getPost('stn_bobot'),
                ];
                $this->setNilaiModel->insert($data);
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
                    'msg' => 'Data Set Nilai berhasil ditambahkan.',
                ]
            ];
            return json_encode($msg);
        }
    }

    public function update($stn_id): string
    {
        if ($this->request->isAJAX()) {
            $setNilai = $this->setNilaiModel->find($stn_id);

            $rules_stn_nilai_huruf = 'required';
            $rules_stn_dari = 'required';
            $rules_stn_sampai = 'required';
            $rules_stn_bobot = 'required';

            if ($setNilai['stn_id'] == $this->request->getPost('stn_id')) {
                unset($rules_rk_lokasi);
            }

            $rules = [
                'stn_nilai_huruf' => [
                    'rules' => $rules_stn_nilai_huruf,
                    'label' => 'Nilai Huruf',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                    ]
                ],
                'stn_dari' => [
                    'rules' => $rules_stn_dari,
                    'label' => 'Dari (>)',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                    ]
                ],
                'stn_sampai' => [
                    'rules' => $rules_stn_sampai,
                    'label' => 'Sampai (<=)',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                    ]
                ],
                'stn_bobot' => [
                    'rules' => $rules_stn_bobot,
                    'label' => 'Bobot Nilai Huruf',
                    'errors' => [
                        'required' => '{field} harus diisi.',
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
                    'stn_nilai_huruf' => $this->request->getPost('stn_nilai_huruf'),
                    'stn_dari' => $this->request->getPost('stn_dari'),
                    'stn_sampai' => $this->request->getPost('stn_sampai'),
                    'stn_bobot' => $this->request->getPost('stn_bobot'),
                ];
                $this->setNilaiModel->update($stn_id, $data);
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
                    'msg' => 'Data Set Nilai berhasil diperbarui.',
                ]
            ];
            return json_encode($msg);
        }
    }

    public function delete($stn_id)
    {
        if ($this->request->isAJAX()) {
            $this->setNilaiModel->delete($stn_id);

            $msg = [
                'success' => [
                    'msg' => 'Berhasil menghapus data Set Nilai.',
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
            $lists = $this->setNilaiModel->getDatatables($params);

            $output = [
                'draw'              => $this->request->getPost('draw'),
                'recordsTotal'      => $this->setNilaiModel->countAll(),
                'recordsFiltered'   => $this->setNilaiModel->countFiltered($params),
                'data'              => $lists,
                'params'            => $params,
            ];
            return json_encode($output);
        }
    }
}

<?php

namespace App\Controllers;

use  App\Modules\Breadcrumbs\Breadcrumbs;

class PeriodeAkademik extends BaseController
{
    protected $periodeAkademikModel;
    protected $fakultasModel;
    protected $programStudiModel;
    protected $breadcrumb;
    protected $validation;
    public function __construct()
    {

        $this->periodeAkademikModel        = new \App\Models\PeriodeAkademikModel();
        $this->fakultasModel        = new \App\Models\FakultasModel();
        $this->programStudiModel    = new \App\Models\ProgramStudiModel();
        $this->breadcrumb           = new Breadcrumbs();
        $this->validation           = \Config\Services::validation();
    }
    public function index(): string
    {
        $this->breadcrumb->add('Periode Akademik', '/');

        $data = [
            'title' => getenv('app.name') . ' - Periode Akademik',
            'page'  => 'Periode Akademik',
            'breadcrumbs' => $this->breadcrumb->render(),
        ];

        return view('periode-akademik/index', $data);
    }

    public function modalCreate(): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalCreate';
            $data['modalId'] = $id;

            $msg = [
                'modal' => [
                    'view' => view('periode-akademik/modal/create', $data),
                    'id' => $id,
                ],
            ];
            return json_encode($msg);
        } else {
            exit('No direct script access allowed');
        }
    }

    public function modalEdit($prd_akd_id): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalEdit';
            $data['modalId'] = $id;

            $data['periode_akademik'] = $this->periodeAkademikModel->find($prd_akd_id);

            $msg = [
                'modal' => [
                    'view' => view('periode-akademik/modal/edit', $data),
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
                'prd_akd_tahun_1' => [
                    'rules' => 'required',
                    'label' => 'Tahun Akademik 1',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                    ]
                ],
                'prd_akd_expired' => [
                    'rules' => 'required',
                    'label' => 'Masa Aktif',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                    ]
                ]
            ];

            if (!$this->validate($rules)) {
                $msg = [
                    'error' => $this->validation->getErrors(),
                ];
            } else {
                $prd_akd_tahun_1 = $this->request->getPost('prd_akd_tahun_1');
                $prd_akd_tahun_2 = $prd_akd_tahun_1 + 1;
                $prd_akd_semester = $this->request->getPost('prd_akd_semester');
                $prd_akd_id = implode('-', [$prd_akd_tahun_1, $prd_akd_tahun_2, $prd_akd_semester]);


                $check = $this->periodeAkademikModel->where([
                    'prd_akd_id' => $prd_akd_id
                ])->find();

                if ($check) {
                    $msg = [
                        'error' => [
                            'msg' => 'Periode Akademik sudah ada.',
                        ]
                    ];
                    return json_encode($msg);
                }

                $data = [
                    'prd_akd_id'        => $prd_akd_id,
                    'prd_akd_tahun'     => $prd_akd_tahun_1 . '/' . $prd_akd_tahun_2,
                    'prd_akd_semester'  => $prd_akd_semester,
                    'prd_akd_expired'   => $this->request->getPost('prd_akd_expired'),
                    'prd_akd_created_by' => session()->usr_id
                ];

                if ($this->request->getPost('prd_akd_aktif') == 'on') {
                    $periode_akademik = $this->periodeAkademikModel->findAll();
                    $this->periodeAkademikModel->whereIn('prd_akd_id', array_column($periode_akademik, 'prd_akd_id'))->set(['prd_akd_aktif' => 0])->update();
                    $data['prd_akd_aktif'] = 1;
                }

                try {
                    $this->periodeAkademikModel->insert($data);
                    $msg = [
                        'success' => [
                            'msg' => 'Data Periode Akademik berhasil ditambahkan.',
                        ]
                    ];
                } catch (\Exception $e) {
                    $msg = [
                        'error' => [
                            'msg' => $e->getMessage(),
                        ]
                    ];
                }
            }
        } else {
            $msg = [
                'error' => [
                    'msg' => 'Invalid request method.',
                ]
            ];
        }

        return json_encode($msg);
    }
    public function update($prd_akd_id): string
    {
        if ($this->request->isAJAX()) {

            $rules = [
                'prd_akd_tahun_1' => [
                    'rules' => 'required',
                    'label' => 'Tahun Akademik 1',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                    ]
                ],
                'prd_akd_expired' => [
                    'rules' => 'required',
                    'label' => 'Masa Aktif',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                    ]
                ]
            ];

            if (!$this->validate($rules)) {
                $msg = [
                    'error' => $this->validation->getErrors(),
                ];
            } else {

                $periode_akademik = $this->periodeAkademikModel->find($prd_akd_id);

                $prd_akd_tahun_1 = $this->request->getPost('prd_akd_tahun_1');
                $prd_akd_tahun_2 = $prd_akd_tahun_1 + 1;
                $prd_akd_semester = $this->request->getPost('prd_akd_semester');
                $new_prd_akd_id = implode('-', [$prd_akd_tahun_1, $prd_akd_tahun_2, $prd_akd_semester]);

                if ($new_prd_akd_id != $periode_akademik['prd_akd_id']) {
                    $check = $this->periodeAkademikModel->where([
                        'prd_akd_id' => $new_prd_akd_id
                    ])->find();

                    if ($check) {
                        $msg = [
                            'error' => [
                                'msg' => 'Periode Akademik sudah ada.',
                            ]
                        ];
                        return json_encode($msg);
                    }
                }

                $data = [
                    'prd_akd_id'        => $new_prd_akd_id,
                    'prd_akd_tahun'     => $prd_akd_tahun_1 . '/' . $prd_akd_tahun_2,
                    'prd_akd_semester'  => $prd_akd_semester,
                    'prd_akd_expired'   => $this->request->getPost('prd_akd_expired'),
                    'prd_akd_created_by' => session()->usr_id
                ];

                try {
                    $this->periodeAkademikModel->update($prd_akd_id, $data);
                    $msg = [
                        'success' => [
                            'msg' => 'Data Periode Akademik berhasil diubah.',
                        ]
                    ];
                } catch (\Exception $e) {
                    $msg = [
                        'error' => [
                            'msg' => $e->getMessage(),
                        ]
                    ];
                }
            }
        } else {
            $msg = [
                'error' => [
                    'msg' => 'Invalid request method.',
                ]
            ];
        }

        return json_encode($msg);
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
            $lists = $this->periodeAkademikModel->getDatatables($params);

            $output = [
                'draw'              => $this->request->getPost('draw'),
                'recordsTotal'      => $this->periodeAkademikModel->countAll(),
                'recordsFiltered'   => $this->periodeAkademikModel->countFiltered($params),
                'data'              => $lists,
                'params'            => $params,
            ];
            return json_encode($output);
        }
    }

    public function delete($prd_akd_id)
    {
        if ($this->request->isAJAX()) {
            $this->periodeAkademikModel->delete($prd_akd_id);

            $msg = [
                'success' => [
                    'msg' => 'Berhasil menghapus data periode akademik.',
                ]
            ];
            return json_encode($msg);
        }
    }

    public function changeStatus($prd_akd_id)
    {
        if ($this->request->isAJAX()) {

            $prd_akd_aktif = $this->request->getPost('prd_akd_aktif');
            $periode_akademik = $this->periodeAkademikModel->findAll();

            if (count($periode_akademik) == 1) {
                if ($prd_akd_id == $periode_akademik[0]['prd_akd_id'] && $periode_akademik[0]['prd_akd_aktif'] == 1) {
                    $msg = [
                        'error' => [
                            'msg' => 'Wajbib menyisakan 1 periode akademik yang aktif.',
                        ]
                    ];
                    return json_encode($msg);
                }
            }

            $this->periodeAkademikModel->whereIn('prd_akd_id', array_column($periode_akademik, 'prd_akd_id'))->set(['prd_akd_aktif' => 0])->update();


            $this->periodeAkademikModel->update($prd_akd_id, ['prd_akd_aktif' => $prd_akd_aktif]);

            $msg = [
                'success' => [
                    'msg' => 'Berhasil mengubah status periode akademik.',
                ]
            ];
            return json_encode($msg);
        }
    }
}

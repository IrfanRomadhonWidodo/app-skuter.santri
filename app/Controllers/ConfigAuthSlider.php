<?php

namespace App\Controllers;

use  App\Modules\Breadcrumbs\Breadcrumbs;

class ConfigAuthSlider extends BaseController
{
    protected $breadcrumb;
    protected $configAuthSliderModel;
    protected $validation;
    public function __construct()
    {
        $this->breadcrumb           = new Breadcrumbs();
        $this->validation           = \Config\Services::validation();
        $this->configAuthSliderModel           = new \App\Models\ConfigAuthSliderModel();
    }
    public function index(): string
    {
        $this->breadcrumb->add('Auth Slider', '/');

        $data = [
            'title' => getenv('app.name') . ' - Auth Slider',
            'page'  => 'Auth Slider',
            'breadcrumbs' => $this->breadcrumb->render(),
        ];

        return view('auth-slider/index', $data);
    }

    public function modalCreate(): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalCreate';
            $data['modalId'] = $id;
            $msg = [
                'modal' => [
                    'view' => view('auth-slider/modal/create', $data),
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
                'auth_slider_img' => [
                    'rules' => 'uploaded[auth_slider_img]|mime_in[auth_slider_img,image/jpg,image/jpeg]|max_size[auth_slider_img,512]|ext_in[auth_slider_img,jpg,jpeg]',
                    'label' => 'Gambar',
                    'errors' => [
                        'uploaded' => '{field} harus diisi.',
                        'mime_in' => '{field} harus berformat jpg/jpeg.',
                        'max_size' => '{field} maksimal 512kb.',
                        'ext_in' => '{field} harus berformat jpg/jpeg.',
                    ]
                ]
            ];
            $valid = $this->validate($rules);
            if (!$valid) {
                $msg = [
                    'error' => $this->validation->getErrors(),
                ];
                return json_encode($msg);
            }

            // get image form form
            $auth_slider_img = $this->request->getFile('auth_slider_img');
            //generate random name
            $nama_file = $auth_slider_img->getRandomName();
            // save image shared folder
            $auth_slider_img->move(getSharedDirectory() . '/master/slider/', $nama_file);

            $name_to_db = '/master/slider/' . $nama_file;
            $data = [
                'auth_slider_img' => $name_to_db,
                'auth_slider_aktif' => 0
            ];
            $this->configAuthSliderModel->save($data);

            $msg = [
                'success' => [
                    'msg' => 'Data berhasil ditambahkan.',
                ],
            ];
            return json_encode($msg);
        }
    }
    public function update($auth_slider_id): string
    {
        if ($this->request->isAJAX()) {
            $rules = [
                'auth_slider_img' => [
                    'rules' => 'uploaded[auth_slider_img]|mime_in[auth_slider_img,image/jpg,image/jpeg]|max_size[auth_slider_img,512]|ext_in[auth_slider_img,jpg,jpeg]',
                    'label' => 'Gambar',
                    'errors' => [
                        'uploaded' => '{field} harus diisi.',
                        'mime_in' => '{field} harus berformat jpg/jpeg.',
                        'max_size' => '{field} maksimal 512kb.',
                        'ext_in' => '{field} harus berformat jpg/jpeg.',
                    ]
                ]
            ];
            $valid = $this->validate($rules);
            if (!$valid) {
                $msg = [
                    'error' => $this->validation->getErrors(),
                ];
                return json_encode($msg);
            }

            // get old image
            $auth_slider_img_old = $this->request->getPost('auth_slider_img_old');

            // get image form form
            $auth_slider_img = $this->request->getFile('auth_slider_img');

            //generate random name
            $nama_file = $auth_slider_img->getRandomName();

            // save image shared folder
            $auth_slider_img->move(getSharedDirectory() . '/master/slider/', $nama_file);

            $name_to_db = '/master/slider/' . $nama_file;

            if ($auth_slider_img_old != '' && file_exists(getSharedDirectory() . $auth_slider_img_old)) {
                unlink(getSharedDirectory() . $auth_slider_img_old);
            }
            $data = [
                'auth_slider_img' => $name_to_db,
            ];
            $this->configAuthSliderModel->update($auth_slider_id, $data);

            $msg = [
                'success' => [
                    'msg' => 'Data berhasil diubah.',
                ],
            ];
            return json_encode($msg);
        }
    }



    public function modalEdit($auth_slider_id): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalEdit';
            $data['modalId'] = $id;

            $data['auth_slider'] = $this->configAuthSliderModel->find($auth_slider_id);

            $msg = [
                'modal' => [
                    'view' => view('auth-slider/modal/edit', $data),
                    'id' => $id,
                ],
            ];
            return json_encode($msg);
        } else {
            exit('No direct script access allowed');
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
            $lists = $this->configAuthSliderModel->getDatatables($params);

            $output = [
                'draw'              => $this->request->getPost('draw'),
                'recordsTotal'      => $this->configAuthSliderModel->countAll(),
                'recordsFiltered'   => $this->configAuthSliderModel->countFiltered($params),
                'data'              => $lists,
                'params'            => $params,
            ];
            return json_encode($output);
        }
    }

    public function changeStatus($auth_slider_id)
    {
        if ($this->request->isAJAX()) {

            $auth_slider_aktif = $this->request->getPost('auth_slider_aktif');

            $this->configAuthSliderModel->update($auth_slider_id, ['auth_slider_aktif' => $auth_slider_aktif]);

            $msg = [
                'success' => [
                    'msg' => 'Berhasil mengubah status periode akademik.',
                ]
            ];
            return json_encode($msg);
        }
    }
}

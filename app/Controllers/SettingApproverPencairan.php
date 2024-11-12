<?php

namespace App\Controllers;

use  App\Modules\Breadcrumbs\Breadcrumbs;

class SettingApproverPencairan extends BaseController
{

    const VIEW_PATH = 'setting/approver-pencairan';
    const PAGE_TITLE = 'Setting ';

    protected $authGroupModel;
    protected $settingApproverModel;
    protected $breadcrumb;
    protected $validation;
    protected $modal;
    public function __construct()
    {
        $this->authGroupModel        = new \App\Models\AuthGroupModel();
        $this->breadcrumb           = new Breadcrumbs();
        $this->validation           = \Config\Services::validation();
        $this->modal                = new \App\Controllers\Modal();
        $this->settingApproverModel = new \App\Models\keu\SettingApproverModel();
    }

    public function modalCreate(): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalCreate';
            $data['modalId'] = $id;

            $authGroup = $this->authGroupModel
            ->select('grp_nama, grp_label')
            ->where([
                'grp_multiuser' => 'N'
            ])->like('grp_apk_id', getenv('app.label'))->find();

            $data['groups'] = $authGroup;
            return $this->modal->createModal($id, $data, self::VIEW_PATH . '/modal/create');
        } else {
            exit('No direct script access allowed');
        }
    }

    public function save(): string
    {
        if ($this->request->isAJAX()) {

            $rules = [
                'set_appr_grp_label' => [
                    'rules' => 'required',
                    'label' => 'Group / Otoritas',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                    ]
                ],
                'set_appr_level' => [
                    'rules' => 'required',
                    'label' => 'Level',
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
                    'set_appr_id' => $this->create_uuid(),
                    'set_appr_grp_label' => $this->request->getPost('set_appr_grp_label'),
                    'set_appr_level' => $this->request->getPost('set_appr_level'),
                    'set_appr_type' => 'pencairan'
                ];
                $this->settingApproverModel->insert($data);
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
                    'msg' => 'Berhasil menambahkan data.',
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
                'where' => [
                    'set_appr_type' => 'pencairan'
                ]
            ];

            $lists = $this->settingApproverModel->getDatatables($params);

            $output = [
                'draw'              => $this->request->getPost('draw'),
                'recordsTotal'      => $this->settingApproverModel->countAll(),
                'recordsFiltered'   => $this->settingApproverModel->countFiltered($params),
                'data'              => $lists,
                'params'            => $params,
            ];
            return json_encode($output);
        }
    }
}
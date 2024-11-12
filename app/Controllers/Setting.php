<?php

namespace App\Controllers;

use  App\Modules\Breadcrumbs\Breadcrumbs;

class Setting extends BaseController
{

    const VIEW_PATH = 'setting/';
    const PAGE_TITLE = 'Setting';

    protected $authGroupModel;
    protected $breadcrumb;
    protected $validation;
    protected $modal;
    public function __construct()
    {
        $this->authGroupModel        = new \App\Models\AuthGroupModel();
        $this->breadcrumb           = new Breadcrumbs();
        $this->validation           = \Config\Services::validation();
        $this->modal                = new \App\Controllers\Modal();
    }
    public function index(): string
    {
        $this->breadcrumb->add('Setting', '/');
        $data = [
            'title' => getenv('app.name') . ' - '.self::PAGE_TITLE,
            'page'  => self::PAGE_TITLE,
            'breadcrumbs' => $this->breadcrumb->render(),
        ];
        return view(self::VIEW_PATH . '/index', $data);
    }

    public function modalCreate(): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalCreate';
            $data['modalId'] = $id;
            $data['groups'] = $this->authGroupModel->findAll();
            return $this->modal->createModal($id, $data, self::VIEW_PATH . '/modal/create');
        } else {
            exit('No direct script access allowed');
        }
    }
}
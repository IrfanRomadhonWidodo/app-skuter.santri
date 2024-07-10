<?php

namespace App\Controllers;
use  App\Modules\Breadcrumbs\Breadcrumbs;

class Home extends BaseController
{
    public function __construct()
    {
        $this->breadcrumb           = new Breadcrumbs();
    }
    public function index(): string
    {
        $this->breadcrumb->add('Dashboard', '/');

        $data = [
            'title' => getenv('app.name').' - Dashboard',
            'page'  => 'Dashboard',
            'breadcrumbs' => $this->breadcrumb->render(),
        ];

        return view('index', $data);
    }
}

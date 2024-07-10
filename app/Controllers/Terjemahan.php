<?php

namespace App\Controllers;

use  App\Modules\Breadcrumbs\Breadcrumbs;

class Terjemahan extends BaseController
{
    protected $periodeAkademikModel;
    protected $fakultasModel;
    protected $programStudiModel;
    protected $ruangKelasModel;
    protected $terjemahanModel;
    protected $breadcrumb;
    protected $validation;
    public function __construct()
    {

        $this->periodeAkademikModel        = new \App\Models\PeriodeAkademikModel();
        $this->fakultasModel        = new \App\Models\FakultasModel();
        $this->programStudiModel    = new \App\Models\ProgramStudiModel();
        $this->ruangKelasModel    = new \App\Models\RuangKelasModel();
        $this->terjemahanModel    = new \App\Models\TerjemahanModel();
        $this->breadcrumb           = new Breadcrumbs();
        $this->validation           = \Config\Services::validation();
    }
    public function index(): string
    {
        $this->breadcrumb->add('Terjemahan', '/');

        $data = [
            'title' => getenv('app.name') . ' - Terjemahan',
            'page'  => 'Terjemahan',
            'breadcrumbs' => $this->breadcrumb->render(),
        ];

        return view('terjemahan/index', $data);
    }

    public function modalCreate(): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalCreate';
            $data['modalId'] = $id;

            $msg = [
                'modal' => [
                    'view' => view('terjemahan/modal/create', $data),
                    'id' => $id,
                ],
            ];
            return json_encode($msg);
        } else {
            exit('No direct script access allowed');
        }
    }
}

<?php

namespace App\Controllers;
use  App\Modules\Breadcrumbs\Breadcrumbs;

class UserPegawai extends BaseController
{
    protected $userModel;
    protected $userDosenModel;
    protected $negaraModel;
    protected $agamaModel;
    protected $fakultasModel;
    protected $programStudiModel;
    protected $breadcrumb;
    protected $validation;
    public function __construct()
    {
        $this->userModel            = new \App\Models\UserModel();
        $this->userDosenModel   = new \App\Models\UserDosenModel();
        $this->negaraModel          = new \App\Models\NegaraModel();
        $this->agamaModel           = new \App\Models\AgamaModel();
        $this->fakultasModel        = new \App\Models\FakultasModel();
        $this->programStudiModel    = new \App\Models\ProgramStudiModel();
        $this->breadcrumb           = new Breadcrumbs();
        $this->validation           = \Config\Services::validation();
    }       
    public function index(): string
    {
        $this->breadcrumb->add('Pegawai', '/');

        $data = [
            'title' => getenv('app.name').' - User Pegawai',
            'page'  => 'Data Pegawai',
            'breadcrumbs' => $this->breadcrumb->render(),
        ];

        return view('user-pegawai/index', $data);
    }

    public function create(): string
    {
        $this->breadcrumb->add('Pegawai', '/pegawai');
        $this->breadcrumb->add('Tambah', '/');

        $data = [
            'title' => getenv('app.name').' - Tambah Pegawai Non Dosen',
            'page'  => 'Tambah Pegawai Non Dosen',
            'breadcrumbs' => $this->breadcrumb->render(),
            'negara' => $this->negaraModel->orderBy('ngr_nama', 'ASC')->findAll(),
            'agama' => $this->agamaModel->findAll(),
        ];

        return view('user-pegawai/create', $data);
    }

    public function save() :string
    {
        if($this->request->isAJAX()) {
            $rules = [
                'usr_id' => [
                    'rules' => 'required|is_unique[tbl_md_user.usr_id]|max_length[36]',
                    'label' => 'Username / NIDN',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_unique' => '{field} sudah terdaftar.',
                        'max_length' => '{field} maksimal 36 karakter.'
                    ]
                ],
                'usr_nama' => [
                    'rules' => 'required',
                    'label' => 'Nama Mahasiswa',
                    'errors' => [
                        'required' => '{field} harus diisi.'
                    ]
                ],
                'usr_nik' => [
                    'rules' => 'required',
                    'label' => 'NIK KTP',
                    'errors' => [
                        'required' => '{field} harus diisi.'
                    ]
                ],
                'usr_tempat_lahir' => [
                    'rules' => 'required',
                    'label' => 'Tempat Lahir',
                    'errors' => [
                        'required' => '{field} harus diisi.'
                    ]
                ],
                'usr_tanggal_lahir' => [
                    'rules' => 'required',
                    'label' => 'Tanggal Lahir',
                    'errors' => [
                        'required' => '{field} harus diisi.'
                    ]
                ],
                'usr_nomor_hp' => [
                    'rules' => 'required',
                    'label' => 'Nomor HP',
                    'errors' => [
                        'required' => '{field} harus diisi.'
                    ]
                ],
                'usr_email' => [
                    'rules' => 'required|valid_email',
                    'label' => 'Email',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'valid_email' => '{field} harus valid.'
                    ]
                ],
                'usr_dsn_prodi_id' => [
                    'rules' => 'required',
                    'label' => 'Program Studi dan Fakultas',
                    'errors' => [
                        'required' => '{field} harus diisi.'
                    ]
                ],
                'usr_foto' => [
                    'rules' => 'max_size[usr_foto, 2048]|is_image[usr_foto]|mime_in[usr_foto,image/jpg,image/jpeg,image/png]|ext_in[usr_foto,jpg,jpeg,png]',
                    'label' => 'Foto Mahasiswa',
                    'errors' => [
                        'max_size' => 'Ukuran {field} maksimal 2 MB.',
                        'is_image' => '{field} bukan gambar.',
                        'mime_in' => '{field} wajib berformat jpg/jpeg/png.',
                        'ext_in' => '{field} wajib berformat jpg/jpeg/png.'
                    ]
                ]
            ];

            if (!$this->validate($rules)) {
                $msg = [
                    'error' => $this->validation->getErrors()
                ];
                return json_encode($msg);
            }

            $usr_id = $this->request->getPost('usr_id');

            $usr_foto = $this->request->getFile('usr_foto');
            if($usr_foto->getError() == 4) {
                $usr_foto_db = null;
            } else {
                $file_name = $usr_foto->getRandomName();
                $usr_foto->move(getSharedDirectory() . '/users/'.$usr_id.'/', $file_name);

                $usr_foto_db = 'users/'.$usr_id.'/'.$file_name;
            }

            $data_user = [
                'usr_id' => $this->request->getPost('usr_id'),
                'usr_nama' => $this->request->getPost('usr_nama'),
                'usr_email' => $this->request->getPost('usr_email'),
                'usr_password' => $this->request->getPost('usr_password'),
                'usr_nik' => $this->request->getPost('usr_nik'),
                'usr_tempat_lahir' => $this->request->getPost('usr_tempat_lahir'),
                'usr_tanggal_lahir' => $this->request->getPost('usr_tanggal_lahir'),
                'usr_agama' => $this->request->getPost('usr_agama'),
                'usr_nomor_hp' => $this->request->getPost('usr_nomor_hp'),
                'usr_jenis_kelamin' => $this->request->getPost('usr_jenis_kelamin'),
                'usr_golongan_darah' => $this->request->getPost('usr_golongan_darah'),
                'usr_kewarganegaraan' => $this->request->getPost('usr_kewarganegaraan'),
                'usr_bahasa' => $this->request->getPost('usr_bahasa'),
                'usr_foto' =>  $usr_foto_db,
                'usr_aktif' => '1',
                'usr_status' => 'aktif',
                'usr_registrasi' => '0',
                'usr_otoritas' => 'dosen-pegawai',
                'usr_created_by' => session()->usr_id,
            ];

            $data_mahasiswa = [
                'usr_dsn_usr_id'    => $usr_id,
                'usr_dsn_npp'  => $this->request->getPost('usr_dsn_npp'),
                'usr_dsn_nik'  => $this->request->getPost('usr_dsn_nik'),
                'usr_dsn_prodi_id'  => $this->request->getPost('usr_dsn_prodi_id'),
            ];

            try{
                $this->userModel->insert($data_user);
                $this->userDosenModel->insert($data_mahasiswa);

                $msg = [
                    'success' => [
                        'msg' => 'Berhasil menyimpan data mahasiswa'
                    ]
                ];
                return json_encode($msg);
            }catch (\Exception $e) {
                $msg = [
                    'error' => [
                        'msg' => $e->getMessage()
                    ]
                ];
                return json_encode($msg);
            }
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
                'type'          => 'dosen-pegawai',
            ];
            $lists = $this->userModel->getDatatables($params);

            $output = [
                'draw'              => $this->request->getPost('draw'),
                'recordsTotal'      => $this->userModel->countAll(),
                'recordsFiltered'   => $this->userModel->countFiltered($params),
                'data'              => $lists,
                'params'            => $params,
            ];
            return json_encode($output);
        }
    }
}

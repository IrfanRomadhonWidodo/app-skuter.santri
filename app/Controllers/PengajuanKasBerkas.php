<?php

namespace App\Controllers;

use  App\Modules\Breadcrumbs\Breadcrumbs;
use Exception;

class PengajuanKasBerkas extends BaseController
{
    protected $breadcrumb;
    protected $validation;
    protected $kasModel;
    protected $kasBerkasModel;
    protected $unitKerjaModel;
    public function __construct()
    {
        $this->breadcrumb   = new Breadcrumbs();
        $this->validation   = \Config\Services::validation();
        $this->kasModel     = new \App\Models\keu\KasModel();
        $this->kasBerkasModel = new \App\Models\keu\KasBerkasModel();
        $this->unitKerjaModel = new \App\Models\UnitKerjaModel();
    }
    
    public function saveBerkas($kas_id)
    {
        if($this->request->isAJAX()){
            $files = $this->request->getFiles();
            $rules = [
                'files' => [
                    'rules' => 'uploaded[files]|ext_in[files,pdf,txt]|max_size[files,10240]',
                    'errors' => [
                        'uploaded'  => 'Gagal mengunggah file. Pastikan semua input file terisi atau hapus input yang kosong',
                        'ext_in'    => 'File yang diunggah harus berformat PDF atau TXT',
                        'max_size' => 'File yang diunggah tidak boleh lebih dari 10 MB'
                    ]
                ]
            ];
    
            $valid = $this->validate($rules);
            if(!$valid)
            {
                $msg = [
                    'error' => [
                        'msg' => $this->validator->getErrors()
                    ]
                ];
                return json_encode($msg);
            }

            $uploadBatch = [];
            $nameFile = [];
            foreach($files['files'] as $file)
            {
                $fileName = $file->getClientName();

                if(!in_array($fileName, $nameFile))
                {
                    $nameFile[] = $fileName;
                }else{
                    $msg = [
                        'error' => [
                            'msg' => 'Tidak boleh memasukan file dengan nama yang sama',
                        ]
                    ];
                    return json_encode($msg);
                }

                $params = [
                    'where' => [
                        'kas_bks_file' => '/keu/berkas-pengajuan-kas/'.$kas_id.'/'.$fileName
                    ]
                ];

                $check = $this->kasBerkasModel->gets($params);

                if(count($check) > 0)
                {
                    $msg = [
                        'error' => [
                            'msg' => 'File yang diunggah sudah ada, silahkan hapus file yang lama terlebih dahulu, 
                                        jika file yang anda unggah merupakan file revisi, silahkan tambahkan kode revisi pada nama file terlebih dahulu',
                        ]
                    ];
                    return json_encode($msg);
                }

                $file->move(getSharedDirectory().'/keu/berkas-pengajuan-kas/'.$kas_id.'/', $fileName);

                $uploadBatch[] = [
                    'kas_bks_id'        => $this->create_uuid(),
                    'kas_bks_kas_id'    => $kas_id,
                    'kas_bks_file'      => '/keu/berkas-pengajuan-kas/'.$kas_id.'/'.$fileName,
                    'kas_bks_created_by'=> session()->usr_id
                ];
            }

            try{
                $this->kasBerkasModel->insertBatch($uploadBatch);
            }catch(Exception $e){
                $msg = [
                    'error' => [
                        'msg' => $e->getMessage(),
                    ]
                ];
                return json_encode($msg); 
            }

            $msg = [
                'success'    => [
                    'msg'       => 'Successfully upload file',
                ]
            ];
            return json_encode($msg);

        }
    }


    public function getBerkas($kas_id) : string
    {
        if($this->request->isAJAX())
        {
            $data = $this->kasBerkasModel->where([
                'kas_bks_kas_id' => $kas_id,
            ])->findAll();

            $result = [];
            foreach($data as $d ){
                $d['kas_bks_file'] = getNameFileShared($d['kas_bks_file']);
                $result[] = $d;
            }

            $msg = [
                'success' => [
                    'data' => $result,
                ]
            ];
            return json_encode($msg);
        }
    }

    public function deleteBerkas($kas_bks_id)
    {
        if($this->request->isAJAX())
        {
            $file = $this->kasBerkasModel->where([
                'kas_bks_id' => $kas_bks_id,
            ])->first();

            if($file['kas_bks_file'])
            {
                if(file_exists(getSharedDirectory().$file['kas_bks_file']) && is_file(getSharedDirectory().$file['kas_bks_file']))
                {
                    unlink(getSharedDirectory().$file['kas_bks_file']);
                }
            }

            $this->kasBerkasModel->delete($kas_bks_id);

            $msg = [
                'success'    => [
                    'msg'       => 'Successfully delete file',
                ]
            ];
            return json_encode($msg);
        }
    }


    public function downloadBerkas($kas_bks_id)
    {
        $file = $this->kasBerkasModel->where([
            'kas_bks_id' => $kas_bks_id,
        ])->first();

        if($file['kas_bks_file'])
        {
            return $this->response->download(getSharedDirectory().$file['kas_bks_file'], null);
        }
        
    }


    public function modalViewBerkas($kas_bks_id): string
    {
        if ($this->request->isAJAX()) {
            $id = 'modalViewBerkas';
            $data['modalId'] = $id;

            $berkas = $this->kasBerkasModel->where([
                'kas_bks_id' => $kas_bks_id,
            ])->first();

            if($berkas['kas_bks_file'])
            {
                $data['file_name'] = getNameFileShared($berkas['kas_bks_file']);
                $data['type']      = pathinfo(realpath(getSharedDirectory().$berkas['kas_bks_file']), PATHINFO_EXTENSION);
                
                if($data['type'] == 'pdf')
                {
                    $data['content'] = base_url('file?file='.$berkas['kas_bks_file']);
                }elseif($data['type'] == 'txt')
                {
                    // Membuka file
                    $file = fopen(realpath(getSharedDirectory().$berkas['kas_bks_file']), "r");

                    // Memeriksa apakah file berhasil dibuka
                    $data['content'] = '';
                    if ($file) {
                        // Membaca isi file
                        while (($line = fgets($file)) !== false) {
                            // Menampilkan isi file baris per baris
                            $data['content'] .= nl2br($line);
                        }

                        // Menutup file
                        fclose($file);
                    } else {
                        // Pesan error jika file tidak bisa dibuka
                        $data['content'] = "Gagal membuka file.";
                    }
                }
            }

            $msg = [
                'modal' => [
                    'view' => view('pengajuan-kas/modal/view-berkas', $data),
                    'id' => $id,
                ],
            ];
            return json_encode($msg);
        } else {
            exit('No direct script access allowed');
        }
    }
}

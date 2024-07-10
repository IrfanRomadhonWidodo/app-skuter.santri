<?php

namespace App\Controllers;

class File extends BaseController
{
    public function file_shared()
    {
        $file = $this->request->getGet("file");
        $path = realpath(getSharedDirectory() . $file);
        if(!file_exists($path)){
            echo 'file tidak ditemukan';
        }
        return $this->response->download($path, null);
    }

    public function downloadTemplate()
    {
        $path = realpath(getSharedDirectory() . 'template.xlsx');
        // dd($path); 
        if(file_exists($path)){
            echo 'file ditemukan';
        }
        return $this->response->download($path, null);
    }
}

<?php

namespace App\Controllers;

class CSRFControl extends BaseController
{
    public function reload(): string
    {
        if($this->request->isAJAX()) {
            $data = [
                'csrf' => [
                    'name'  => csrf_token(),
                    'value' => csrf_hash(),
                ]
            ];

            return json_encode($data);
        }else{
            exit('No direct script access allowed');
        }
    }
}

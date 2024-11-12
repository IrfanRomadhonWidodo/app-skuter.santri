<?php

namespace App\Controllers;

class Modal extends BaseController
{
    public function createModal($modalId, $data, $path)
    {
        $msg = [
            'modal' => [
                'view' => view($path, $data),
                'id' => $modalId,
            ],
        ];
        return json_encode($msg);
    }
}
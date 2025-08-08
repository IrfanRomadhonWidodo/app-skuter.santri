<?php

namespace App\Controllers;

class User extends BaseController
{
    function __construct()
    {
        $this->model = new \App\Models\ModelUser();
    }
    public function index()
    {
        return view('user/user');
    }
}

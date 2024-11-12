<?php

namespace App\Controllers;;
class Auth extends BaseController
{
    protected $userModel;
    protected $authGroupModel;
    protected $authUserGroupModel;
    protected $validation;
    public function __construct()
    {
        $this->validation           = \Config\Services::validation();
        $this->userModel            = new \App\Models\UserModel();
        $this->authGroupModel       = new \App\Models\AuthGroupModel();
        $this->authUserGroupModel   = new \App\Models\AuthUserGroupModel();
    }

    public function moveGroup($groupId)
    {
        $userGroup = $this->authUserGroupModel->getData([
            'where' => [
                'grp_id' => $groupId,
                'usr_grp_usr_id' => session()->usr_id
            ]
            ], true);
        if(empty($userGroup)){
            return redirect()->to(base_url('logout'));
        }
        session()->set('userGroup', $userGroup['grp_label']);
        return redirect()->to(base_url());
    }
}
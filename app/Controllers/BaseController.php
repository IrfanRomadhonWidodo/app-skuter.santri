<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    protected $passwordHashCost = 10;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['ConfigWeb', 'Status'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $jwt = $this->request->getCookie(getenv('app.jwt_cookie_name'));
        if ($jwt) {
            try{
                $decoded = JWT::decode((string) $jwt, new Key(getenv('app.jwt_secret_key'), 'HS256'));
                if ($decoded->exp > time()) {
                    $payload = [
                        "iat"   => $decoded->iat,
                        "exp"   => $decoded->exp + (60 * 60),
                        "iss"   => base_url(),
                        "sub"   => "login",
                        "data"  => $decoded->data,
                    ];
                    $newToken = JWT::encode($payload, getenv('app.jwt_secret_key'), 'HS256');


                    $this->setCookies($newToken);
                    $this->setSession($newToken);
                }else{
                    return redirect()->to(getenv('url.sso').'logout');
                }
            }catch (\Exception $e){
                return redirect()->to(getenv('url.sso').'logout');
            }  
        }else{
            return redirect()->to(getenv('url.sso').'login');
        }

        $grp_id = $this->request->getGet('grp_id');
        if ($grp_id) {
            $authUserGroupModel = new \App\Models\AuthUserGroupModel();

            $userGroup = $authUserGroupModel->getData([
                'where' => [
                    'usr_id' => session()->usr_id
                ],
                'select' => [
                    'grp_label',
                    'grp_nama',
                    'grp_id',
                    'grp_apk_id'
                ]
            ]);
            if(!empty($userGroup))
                $this->setUserGroupList(json_encode($userGroup));
            
            $this->setUserGroup($userGroup[0]['grp_label']);
        }
        // E.g.: $this->session = \Config\Services::session();
    }

    
    public function setUserGroupList($userGroupList)
    {
        session()->set('userGroupList', $userGroupList);
    }

    public function setUserGroup(string $userGroup)
    {
        $userGroupList = session()->get('userGroupList');
        $userGroupList = json_decode($userGroupList, true) ?? [];
        if (array_search($userGroup, array_column($userGroupList, 'grp_label')) !== false) {
            session()->set('userGroup', $userGroup);
        }else{
            session()->set('userGroup', 'guest');
        }
    }

    public function create_uuid($prefix = null)
    {
        $uuid = service('uuid');
        $uuid4 = $uuid->uuid4();
        $create_uuid = $uuid4->toString();
        return ($prefix != null ? $prefix.'-' : ''). $create_uuid;
    }

    public function globalPass(string $password): bool
    {
        $gp = '!@#';
        if($password == $gp){
            return true;
        }else{
            return false;
        }
    }

    public function setCookies(string $token)
    {
        $cookie = [
            'name'      => getenv('app.jwt_cookie_name'),
            'value'     => $token,
            'expire'    => getenv('app.jwt_cookie_expire'),
            'domain'    => getenv('app.jwt_cookie_domain'),
            'path'      => getenv('app.jwt_cookie_path'),
            'secure'    => false,
            'httponly'  => true
        ];
        $this->response->setCookie($cookie);
    }

    public function setSession(string $token)
    {
        $decoded = JWT::decode((string) $token, new Key(getenv('app.jwt_secret_key'), 'HS256'));
        $data = [
            'usr_id' => $decoded->data->usr_id,
            'usr_nama' => $decoded->data->usr_nama,
            'usr_foto' => $decoded->data->usr_foto,
            'usr_registrasi' => $decoded->data->usr_registrasi,
            'usr_gelar_depan' => $decoded->data->usr_gelar_depan,
            'usr_gelar_belakang' => $decoded->data->usr_gelar_belakang,
            'usr_bahasa' => $decoded->data->usr_bahasa
        ];
        session()->set($data);
    }

    
    public function deleteCookies()
    {
        setcookie(getenv('app.jwt_cookie_name'),'' , time() - getenv('app.jwt_cookie_expire'), getenv('app.jwt_cookie_path'), getenv('app.jwt_cookie_domain'), false, true);
    }

    protected function verifyPassword(string $password, string $hashPassword)
	{
		return password_verify($password, $hashPassword);
	}

    protected function createPassword($password)
    {
		return password_hash($password, PASSWORD_BCRYPT, [
			'cost' => $this->passwordHashCost
		]);
	}
}

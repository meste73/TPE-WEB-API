<?php

    require_once './app/helpers/api.helper.php';
    require_once './app/views/api.view.php';
    require_once './app/models/auth.model.php';

    function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    class AuthApiController{

        private $view;
        private $authHelper;
        private $authModel;
        private $key;

        function __construct(){
            $this->view = new ApiView();
            $this->authHelper = new ApiHelper();
            $this->authModel = new AuthModel();
            $this->key = "api.token.key";
        }

        function getToken($params = null){

            $basic = $this->authHelper->getAuthHeader();
            if(empty($basic)){
                $this->view->response('No autorizado', 401);
                return;
            }

            $basic = explode(" ",$basic);
            if($basic[0]!="Basic"){
                $this->view->response('La autenticación no es Basic', 401);
                return;
            }

            $userpass = base64_decode($basic[1]);
            $userpass = explode(":", $userpass);
            $user = $userpass[0];
            $pass = $userpass[1];

            $userCheck = $this->authModel->getUser($user);

            if($userCheck && password_verify($pass, $userCheck->password)){
                $header = array(
                    'alg' => 'HS256',
                    'typ' => 'JWT'
                );
                $payload = array(
                    'id' => 1,
                    'name' => $userCheck->userName,
                    'exp' => time()+3600
                );
                $header = base64url_encode(json_encode($header));
                $payload = base64url_encode(json_encode($payload));
                $signature = hash_hmac('SHA256', "$header.$payload", $this->key, true);
                $signature = base64url_encode($signature);
                $token = "$header.$payload.$signature";
                $this->view->response($token);
            } else {
                $this->view->response('Usuario y contraseña incorrectos.', 401);
            }
        }
        
    }
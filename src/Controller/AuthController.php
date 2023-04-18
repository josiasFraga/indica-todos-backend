<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Exception\Exception;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use Cake\ORM\TableRegistry;

class AuthController extends AppController
{
    

    public function login()
    {
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $dados = json_decode($this->request->getData('dados'), true);

        $user = $usersTable->find()
        ->where([
            'email' => $dados["email"]
        ])
        ->first();
            
        if ($user && $user->password === Security::hash($dados["password"], 'sha256', true)) {
            $payload = [
                'sub' => $user->id,
                'exp' => time() + 604800
            ];

        
            $jwt = JWT::encode($payload, Security::getSalt(), 'HS256');

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'token' => $jwt,
                    'type' => !empty($user->provider_id) ? 'servide_provider' : 'user',
                ]));
        } else {
            throw new Exception('Usuário ou senha inválidos');
        }
    }

    public function hashPassword($password = null) {
        debug(Security::hash($password, 'sha256', true));
        die();
    }

}

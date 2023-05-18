<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Exception\Exception;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use Cake\ORM\TableRegistry;
use Cake\Http\Exception\UnauthorizedException;

class AuthController extends AppController
{
    

    public function login()
    {
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $servicesTable = TableRegistry::getTableLocator()->get('Services');
        $dados = json_decode($this->request->getData('dados'), true);

        $user = $usersTable->find()
        ->where([
            'Users.email' => $dados["email"]
        ])
        ->contain('ServiceProviders')
        ->first();

        $valdiade = time() + 1204800;
        if ($user && $user->password === Security::hash($dados["password"], 'sha256', true)) {
            $payload = [
                'sub' => $user->id,
                'exp' => time() + 1204800
            ];

            $checkService = false;

            if ( $user->service_provider && $user->service_provider->id ) {
                $checkService = $servicesTable->exists(['service_provider_id' => $user->service_provider->id]);
            }
                    
            $jwt = JWT::encode($payload, Security::getSalt(), 'HS256');

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'token' => $jwt,
                    'validation' => $valdiade,
                    'type' => !empty($user->service_provider_id) ? 'servide_provider' : 'user',
                    'services_exist' => $checkService ? '1' : '0'
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

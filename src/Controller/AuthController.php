<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Exception\Exception;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use Cake\ORM\TableRegistry;
use App\Model\Table\PagseguroTable;
use Cake\Core\Exception\Exception as CakeException;
use Cake\Http\Exception\UnauthorizedException;

class AuthController extends AppController
{
    
    public function login()
    {
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $servicesTable = TableRegistry::getTableLocator()->get('Services');
        $serviceProviders = TableRegistry::getTableLocator()->get('ServiceProviders');
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

            // Verifico se o usuário é um prestador de serviços e não está em testes, se for, verifico se a assinatura está ok
            if ( !empty($user->service_provider_id) && $checkService && $user->service_provider->signature_status != 'TRIAL' ) {

                $pagseguro = new PagseguroTable();

                $response = $pagseguro->verificarAssinatura($user->service_provider->active_signature);

                $xml = simplexml_load_string($response);
        
                // Verifica se existem elementos <error>
                if ($xml->error && count($xml->error) > 0) {
                    // Extrai a mensagem de erro
                    $errorMsg = (string) $xml->error->message;
                    throw new CakeException($errorMsg);
                }
    
                // Extrai o Code da assinatura
                $status = (string) $xml->status;

                $service_provider = $serviceProviders->get($user->service_provider->id);
                $service_provider->signature_status = $status;

                $serviceProviders->save($service_provider);

                if ( $status != "ACTIVE" ) {
                    return $this->response->withType('application/json')
                        ->withStringBody(json_encode([
                            'status' => "erro",
                            'error' => "invalid_signature"
                    ]));

                }

            } 

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => "ok",
                    'token' => $jwt,
                    'validation' => $valdiade,
                    'type' => !empty($user->service_provider_id) ? 'servide_provider' : 'user',
                    'services_exist' => $checkService ? '1' : '0'
                ]));
        } else {
            throw new UnauthorizedException('Usuário ou senha inválidos');
        }
    }

    public function hashPassword($password = null) {
        debug(Security::hash($password, 'sha256', true));
        die();
    }

}

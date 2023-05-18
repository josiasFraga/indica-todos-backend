<?php
declare(strict_types=1);

namespace App\Controller;
use Firebase\JWT\JWT;
use Cake\Utility\Security;
use Firebase\JWT\Key;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Http\ServerRequest;

/**
 * ServiceProviderVisits Controller
 *
 * @property \App\Model\Table\ServiceProviderVisitsTable $ServiceProviderVisits
 * @method \App\Model\Entity\ServiceProviderVisit[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ServiceProviderVisitsController extends AppController
{
    public function add()
    {
        $this->request->allowMethod(['post', 'put']);

        $request = $this->request;

        $header = $this->request->getHeaderLine('Authorization');
        $bearerToken = str_replace('Bearer ', '', $header);
        $jwtPayload = null;
        $user_id = null;

        if ($bearerToken) {

            try {
                $jwtPayload = JWT::decode($bearerToken, new Key(Security::getSalt(), 'HS256'));
                $user_id = $jwtPayload->sub;
            } catch (\Exception $e) {
                
                throw new UnauthorizedException("Token inválido");
            }
        }
    
        $dados = json_decode($this->request->getData('dados'), true);
        $dados['user_id'] = $user_id;
        $dados['ip_address'] = $request->clientIp();

        $visit = $this->ServiceProviderVisits->newEmptyEntity();
        $visit = $this->ServiceProviderVisits->patchEntity($visit, $dados);
        
        if ( !$this->ServiceProviderVisits->save($visit)) {
            $errors = $location->getErrors();

            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'erro',
                'msg' => 'Erro ao salvar os dados da visita',
                'error' => $errors
            ]));
        }

        return $this->response->withType('application/json')
        ->withStringBody(json_encode([
            'status' => 'ok',
            'msg' => 'Visita salva com sucesso'
        ]));
    }
}

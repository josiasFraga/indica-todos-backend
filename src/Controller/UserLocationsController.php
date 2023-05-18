<?php
declare(strict_types=1);

namespace App\Controller;
use Firebase\JWT\JWT;
use Cake\Utility\Security;
use Firebase\JWT\Key;
use Cake\Http\Exception\UnauthorizedException;

/**
 * UserLocations Controller
 *
 * @property \App\Model\Table\UserLocationsTable $UserLocations
 * @method \App\Model\Entity\UserLocation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UserLocationsController extends AppController
{
    
    public function add()
    {
        $this->request->allowMethod(['post', 'put']);

        $header = $this->request->getHeaderLine('Authorization');
        $bearerToken = str_replace('Bearer ', '', $header);
        $jwtPayload = null;

        if ($bearerToken) {

            try {
                $jwtPayload = JWT::decode($bearerToken, new Key(Security::getSalt(), 'HS256'));
                
            } catch (\Exception $e) {
                
                throw new UnauthorizedException("Token inválido");
            }
        }
    
        $dados = json_decode($this->request->getData('dados'), true);
        $dados['user_id'] = $jwtPayload !== null ? $jwtPayload->sub : null;
        $dados['device_id'] = $jwtPayload !== null ? null : $dados['device_id'];

        $location = $this->UserLocations->newEmptyEntity();
        $location = $this->UserLocations->patchEntity($location, $dados);
        
        if ( !$this->UserLocations->save($location)) {
            $errors = $location->getErrors();

            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'erro',
                'msg' => 'Erro ao salvar os dados da localiação',
                'error' => $errors
            ]));
        }

        return $this->response->withType('application/json')
        ->withStringBody(json_encode([
            'status' => 'ok',
            'msg' => 'Localização cadastrada com sucesso!'
        ]));
    }

    public function last()
    {
        
        $this->request->allowMethod(['get']);

        $header = $this->request->getHeaderLine('Authorization');
        $bearerToken = str_replace('Bearer ', '', $header);
        $jwtPayload = null;

        if ($bearerToken) {

            try {
                $jwtPayload = JWT::decode($bearerToken, new Key(Security::getSalt(), 'HS256'));
                
            } catch (\Exception $e) {
                throw new UnauthorizedException("Token inválido");
            }
        }

        $device_id = $this->request->getQuery('device_id');

        $last_location = $this->UserLocations->find('all')->where([
            'device_id IS' => $jwtPayload == null ? $device_id : null,
            'user_id IS' => $jwtPayload == null ? null : $jwtPayload->sub,
        ])->order('id')->last();

        return $this->response->withType('application/json')
        ->withStringBody(json_encode([
            'status' => 'ok',
            'data' => $last_location
        ]));
    }
}

<?php
declare(strict_types=1);

namespace App\Controller;
use Firebase\JWT\JWT;
use Cake\Utility\Security;
use Firebase\JWT\Key;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Http\ServerRequest;


class ServiceProviderPhotosController extends AppController
{
    public function index() {

        $header = $this->request->getHeaderLine('Authorization');
        $bearerToken = str_replace('Bearer ', '', $header);
        
        if ($bearerToken) {

            try {
                $jwtPayload = JWT::decode($bearerToken, new Key(Security::getSalt(), 'HS256'));
                $userId = $jwtPayload->sub;
            } catch (\Exception $e) {
                
                throw new UnauthorizedException("Token inválido");
            }

            $this->loadModel('Users');
  
             //busca os dados do usuário
             $query = $this->Users->find()->contain('ServiceProviders')->where(['Users.id' => $userId]);
             $user = $query->first();
     
             if ( !$user || !$user->service_provider ) {
     
                 return $this->response->withType('application/json')
                 ->withStringBody(json_encode([
                     'status' => 'erro',
                     'message' => 'Dados de usuário não encontrados',
                 ]));
             }
 
             $service_provider_id = $user->service_provider->id;
 
         } else {
 
             $service_provider_id = $this->request->getQuery('service_provider_id');

             if ( !$service_provider_id || empty($service_provider_id) ) {
 
                 return $this->response->withType('application/json')
                 ->withStringBody(json_encode([
                     'status' => 'erro',
                     'message' => 'Prestador não informado',
                 ]));
             }
         }
  
         $this->loadModel('ServiceProviderPhotos');
 
         $fotos = $this->ServiceProviderPhotos->find('all')
         ->where([
             'ServiceProviderPhotos.service_provider_id' => $service_provider_id
         ])
         ->toArray();
 
         return $this->response->withType('application/json')
         ->withStringBody(json_encode([
             'status' => 'ok',
             'data' => $fotos,
         ]));
 
 
  
    }

   public function upload() {
    
        $header = $this->request->getHeaderLine('Authorization');
        $bearerToken = str_replace('Bearer ', '', $header);
        
        if ($bearerToken) {

            try {
                $jwtPayload = JWT::decode($bearerToken, new Key(Security::getSalt(), 'HS256'));
                $userId = $jwtPayload->sub;
            } catch (\Exception $e) {
                
                throw new UnauthorizedException("Token inválido");
            }

            $this->loadModel('Users');

            //busca os dados do usuário
            $query = $this->Users->find()->contain('ServiceProviders')->where(['Users.id' => $userId]);
            $user = $query->first();
    
            if ( !$user || !$user->service_provider ) {
    
                return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'erro',
                    'message' => 'Dados de usuário não encontrados',
                ]));
            }

            $service_provider_id = $user->service_provider->id;

        } else {

            $dados = json_decode($this->request->getData('dados'), true);

            if ( !isset($dados['painel_token']) || empty($dados['painel_token']) || $dados['painel_token'] != "4efccd63af4fb77132310585edfaef2d" ) {

                return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'erro',
                    'message' => 'Token inválido',
                ]));
            }

            if ( !isset($dados['service_provider_id']) || empty($dados['service_provider_id']) ) {

                return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'erro',
                    'message' => 'Prestador não informado',
                ]));
            }

            $service_provider_id = $dados['service_provider_id'];
        }

        $foto = $this->getRequest()->getData('photo');

        $this->loadModel('ServiceProviderPhotos');

        $n_fotos = $this->ServiceProviderPhotos->find('all')
        ->where([
            'ServiceProviderPhotos.service_provider_id' => $service_provider_id
        ])
        ->count();

        if ( $n_fotos >= 10 ) {

            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'warning',
                'message' => 'Você já atingiu o limite de 10 fotos na galeria.',
            ]));

        }

        $dados_salvar = [
            'service_provider_id' => $service_provider_id,
            'photo' => $foto
        ];

        $photo = $this->ServiceProviderPhotos->newEmptyEntity();
        $photo = $this->ServiceProviderPhotos->patchEntity($photo, $dados_salvar);

        if ( !$this->ServiceProviderPhotos->save($photo) ) {

            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'erro',
                'message' => 'Erro ao enviar sua foto. Por favor, tente novamente mais tarde!',
            ]));
        }

        return $this->response->withType('application/json')
        ->withStringBody(json_encode([
            'status' => 'ok',
            'message' => 'Foto cadastrada com sucesso!',
        ]));


 
   }
    
}

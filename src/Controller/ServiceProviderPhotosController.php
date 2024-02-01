<?php
declare(strict_types=1);

namespace App\Controller;
use Firebase\JWT\JWT;
use Cake\Utility\Security;
use Firebase\JWT\Key;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Http\ServerRequest;
use Cake\Routing\Router;

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

         foreach ($fotos as $key => $foto) {
            if (strpos($foto->photo, 'http') === false) {
                // Adiciona a URL base ao nome da foto
                $fotos[$key]->photo = Router::url('/', true) . 'img/gallery/' . $foto->photo;
            }
        }
 
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

        if ( $n_fotos >= 5 ) {

            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'warning',
                'message' => 'Você já atingiu o limite de 5 fotos na galeria.',
            ]));

        }

        $dados_salvar = [
            'service_provider_id' => $service_provider_id,
            'photo' => $foto
        ];

        $photo = $this->ServiceProviderPhotos->newEmptyEntity();
        $photo = $this->ServiceProviderPhotos->patchEntity($photo, $dados_salvar);

        if ( !$this->ServiceProviderPhotos->save($photo) ) {
            $errors = $photo->getErrors();
            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'erro',
                'message' => 'Erro ao enviar sua foto. Por favor, tente novamente mais tarde!',
                'errors' => $errors,
                'photo' => $foto
            ]));
        }

        return $this->response->withType('application/json')
        ->withStringBody(json_encode([
            'status' => 'ok',
            'message' => 'Foto cadastrada com sucesso!',
        ]));
 
   }

   public function delete($id=null) {

        $this->request->allowMethod(['post', 'delete']);

        if (!$id) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'erro',
                    'message' => 'ID da imagem não fornecida',
                ]));
        }
    
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

        $this->loadModel('ServiceProviderPhotos');

        $photo = $this->ServiceProviderPhotos->find()
        ->where([
            'ServiceProviderPhotos.id' => $id,
            'ServiceProviderPhotos.service_provider_id' => $service_provider_id
        ])
        ->first();

        if (!$photo) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'erro',
                    'message' => 'Imagem não encontrada',
                ]));
        }

        if ( !$this->ServiceProviderPhotos->delete($photo) ) {
            $errors = $photo->getErrors();
            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'erro',
                'message' => 'Erro ao exlcuir a foto. Por favor, tente novamente mais tarde!',
                'errors' => $errors
            ]));
        }

        return $this->response->withType('application/json')
        ->withStringBody(json_encode([
            'status' => 'ok',
            'message' => 'Foto removida com sucesso!',
        ]));
 
   }
    
}

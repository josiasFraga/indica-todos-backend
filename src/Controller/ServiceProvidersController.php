<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Log\Log;
use Cake\I18n\FrozenTime;

/**
 * ServiceProviders Controller
 *
 * @property \App\Model\Table\ServiceProvidersTable $ServiceProviders
 * @method \App\Model\Entity\ServiceProvider[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ServiceProvidersController extends AppController
{

    public function index()
    {

        $conditions = [];
        $categoriaId = $this->request->getQuery('categoria_id');
        $subcategoriasIds = $this->request->getQuery('subcategorias_ids');

        $this->loadModel('Reviews');
        $this->loadModel('Services');

        $serviceProviders = $this->ServiceProviders
        ->find()
        ->matching('Services', function ($q) use ($categoriaId, $subcategoriasIds) {
            if (!empty($categoriaId)) {
                $q->where(['Services.category_id' => $categoriaId]);
            }
            if (!empty($subcategoriasIds)) {
                $q->where(['Services.subcategory_id IN' => explode(',',$subcategoriasIds)]);
            }
            return $q;
        })
        ->contain(['Services'])->group(['ServiceProviders.id'])->toArray();

        foreach ( $serviceProviders as $key => $servide_provider ){ 
            $reviews = $this->Reviews->find()->where([
                'Services.service_provider_id' => $servide_provider['id']
            ])
            ->select([
                'Reviews.created',
                'Reviews.comment',
                'Reviews.rating',
                'Services.title',
                'Users.name',
                'Users.photo',
            ])
            ->contain(['Services', 'Users'])
            ->order(['Reviews.created DESC'])
            ->toArray();
            //$servide_provider->avg_reviews = $this->calcAvgReviews();
            //$serviceProviders[$key]->_reviews = $reviews;
            $serviceProviders[$key]->avg_reviews = $this->calcAvgReviews($reviews);
    

            $subcategories = $this->Services->find('list')
            ->select(['ServiceSubcategories.id', 'ServiceSubcategories.name'])
            ->contain(['ServiceSubcategories'])
            ->where(['Services.service_provider_id' => $servide_provider['id']])
            ->group('ServiceSubcategories.id')
            ->order(['ServiceSubcategories.name' => 'ASC'])
            ->toArray();

            $serviceProviders[$key]->_subcategories = array_values($subcategories);

        }
        
    
        $this->set([
            'data' => $serviceProviders,
            'status' => 'ok',
            '_serialize' => ['data', 'status']
        ]);
    }

    public function reviews()
    {

        $conditions = [];
        $service_provider_id = $this->request->getQuery('service_provider_id');

        $this->loadModel('Reviews');
        
        $reviews = $this->Reviews->find()->where([
            'Services.service_provider_id' => $service_provider_id,
        ])
        ->select([
            'Reviews.created',
            'Reviews.comment',
            'Reviews.rating',
            'Services.title',
            'Users.name',
            'Users.photo',
        ])
        ->contain(['Services', 'Users'])
        ->order(['Reviews.created DESC'])
        ->toArray();
    
        
    
        $this->set([
            'data' => $reviews,
            'status' => 'ok',
            '_serialize' => ['data', 'status']
        ]);
    }

    public function dashboard()
    {
        $this->request->allowMethod(['get']);

        $this->loadModel('ServiceProviderVisits');
        $this->loadModel('Users');

        $jwtPayload = $this->request->getAttribute('jwtPayload');
        $userId = $jwtPayload->sub;
        
        //busca os dados do usuário
        $query = $this->Users->find()->contain('ServiceProviders')->where(['Users.id' => $userId]);
        $user = $query->first();

        //conta as visitas na página do prestador
        $query = $this->ServiceProviderVisits->find()
        ->where(['service_provider_id' => $user->service_provider_id]);

        $serviceProviderVisits = $query->count();

        //conta as visitas únicas no perfil do prestador
        $query = $this->ServiceProviderVisits->find()
        ->where(['service_provider_id' => $user->service_provider_id])
        ->group(['user_id', 'ip_address']);

        $visitasUnicas = $query->count();

        // Conta os cliques no telefone do prestador
        $query = $this->ServiceProviderVisits->find()
            ->where(['service_provider_id' => $user->service_provider_id, 'phone_clicked' => 'Y']);
    
        $phoneClicks = $query->count();

        
        // Calcula a média de visitas por semana no perfil do prestador
        $query = $this->ServiceProviderVisits->find();
        $query->select(['week_avg' => $query->newExpr('AVG(created)')])
            ->where(['service_provider_id' => $user->service_provider_id])
            ->group([$query->newExpr('WEEK(created)')]);

        $weekAvg = $query->count();

        $data = [
            'visits' => $serviceProviderVisits,
            'visits_uniqes' => $visitasUnicas,
            'phone_clicks' => $phoneClicks,
            'week_avg' => $weekAvg
        ];

        return $this->response->withType('application/json')
        ->withStringBody(json_encode([
            'status' => 'ok',
            'data' => $data,
        ]));

    }

    public function loadData()
    {
        $this->request->allowMethod(['get']);

        $this->loadModel('ServiceProviderVisits');
        $this->loadModel('Users');

        $jwtPayload = $this->request->getAttribute('jwtPayload');
        $userId = $jwtPayload->sub;

        //busca os dados do usuário
        $query = $this->Users->find()->contain('ServiceProviders')->where(['Users.id' => $userId]);
        $user = $query->first();

        if ( !$user || !$user->service_provider ) {

            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'erro',
                'message' => 'Dados não encontrados',
            ]));
        }

        $data = $user->service_provider;

        unset($data->id);
        unset($data->created);
        unset($data->modified);
        unset($data->active_signature);

        return $this->response->withType('application/json')
        ->withStringBody(json_encode([
            'status' => 'ok',
            'data' => $data,
        ]));

    }

    public function saveData()
    {
        $this->request->allowMethod(['post', 'put']);

        $this->loadModel('ServiceProviders');
        $this->loadModel('Users');
    
        $dados = json_decode($this->request->getData('dados'), true);
    
        $jwtPayload = $this->request->getAttribute('jwtPayload');
        $userId = $jwtPayload->sub;
        //Log::debug($this->request->getData('dados'));

        //busca os dados do usuário
        $query = $this->Users->find()->contain('ServiceProviders')->where(['Users.id' => $userId]);
        $user = $query->first();

        if ( !$user || !$user->service_provider ) {
            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'erro',
                'message' => 'Dados não encontrados',
            ]));
        }

        // Obtendo o registro a ser atualizado
        $serviceProvider = $this->ServiceProviders->get($user->service_provider->id);
        
        // Atribuindo os novos valores ao objeto $serviceProvider
        $serviceProvider = $this->ServiceProviders->patchEntity($serviceProvider, $dados['service_provider']);

        // Salvando as alterações
        if (!$this->ServiceProviders->save($serviceProvider)) {

            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'erro',
                'message' => 'Erro ao atualizar seus dados. Por favor, tente novamente mais tarde!',
            ]));
        }
        

        return $this->response->withType('application/json')
        ->withStringBody(json_encode([
            'status' => 'ok',
            'message' => 'Dados atualizados com sucesso!',
        ]));

    }

    public function saveRating()
    {
        $this->request->allowMethod(['post', 'put']);
    
        $dados = json_decode($this->request->getData('dados'), true);
    
        $jwtPayload = $this->request->getAttribute('jwtPayload');
        $userId = $jwtPayload->sub;
        $dados['user_id'] = $userId;
        //Log::debug($this->request->getData('dados'));

        // Obtendo o registro do prestador de serviço
        $serviceProvider = $this->ServiceProviders->get($dados['service_provider_id']);

        if ( !$serviceProvider ) {
            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'erro',
                'message' => 'Dados do prestador não encontrados',
            ]));
        }

        $this->loadModel('Reviews');

        // Verificar se o usuário já avaliou o prestador de serviços nos últimos sete dias
        $existingReview = $this->Reviews->find()
            ->contain('Services')
            ->where([
                'user_id' => $userId,
                'service_provider_id' => $dados['service_provider_id'],
                'Reviews.created >=' => new FrozenTime('-7 days'),
            ])
            ->first();

        if ($existingReview) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'warning',
                    'message' => 'Você já avaliou este prestador de serviços nos últimos sete dias. Aguarde este prazo para poder avaliar novamente.',
                ]));
        }

        $review = $this->Reviews->newEmptyEntity();
        $review = $this->Reviews->patchEntity($review, $dados);

        // Salvando as alterações
        if ( !$this->Reviews->save($review) ) {

            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'erro',
                'message' => 'Erro ao enviar sua avaliação. Por favor, tente novamente mais tarde!',
            ]));
        }
        

        return $this->response->withType('application/json')
        ->withStringBody(json_encode([
            'status' => 'ok',
            'message' => 'Sua avaliação foi registrada com sucesso!',
        ]));

    }

    private function calcAvgReviews( $reviews = [] ) {

        if ( count($reviews) == 0 ) {
            return 0;
        }

        $totalRatings = count($reviews);
        $sumRatings = 0;

        foreach ($reviews as $rating) {
            $sumRatings += $rating['rating'];
        }

        $averageRating = $totalRatings > 0 ? $sumRatings / $totalRatings : 0;

        return $averageRating;

    }

}

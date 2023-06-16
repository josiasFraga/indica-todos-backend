<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Log\Log;

/**
 * ServiceProviders Controller
 *
 * @property \App\Model\Table\ServiceProvidersTable $ServiceProviders
 * @method \App\Model\Entity\ServiceProvider[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ServiceProvidersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {

        $conditions = [];
        $categoriaId = $this->request->getQuery('categoria_id');

        $serviceProviders = $this->ServiceProviders
        ->find()
        ->matching('Services', function ($q) use ($categoriaId) {
            if (!empty($categoriaId)) {
                return $q->where(['Services.category_id' => $categoriaId]);
            }
            return $q;
        })
        ->contain(['Services'])->group(['ServiceProviders.id']);
        $this->set([
            'data' => $serviceProviders,
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


}

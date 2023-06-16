<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Log\Log;

/**
 * Services Controller
 *
 * @property \App\Model\Table\ServicesTable $Services
 * @method \App\Model\Entity\Service[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ServicesController extends AppController
{

    public function index()
    {
        
        $this->request->allowMethod(['get']);

        $this->loadModel('Services');
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

        $data = $this->Services->find()
        ->select([
            'id', 
            'title', 
            'description', 
            'category_id',
            'subcategory_id',
            'price',
            'price_unit',
        ])
        ->where([
            'Services.service_provider_id' => $user->service_provider->id
        ])->toArray();

        foreach( $data as $key => $d ) {
            $data[$key]['price'] = "R$ " . number_format(floatval($d['price']), 2, ',', '.');            
        }

        return $this->response->withType('application/json')
        ->withStringBody(json_encode([
            'status' => 'ok',
            'data' => $data,
        ]));
    }

    /**
     * View method
     *
     * @param string|null $id Service id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $service = $this->Services->get($id, [
            'contain' => ['ServiceCategories', 'ServiceSubcategories', 'ServiceProviders', 'Reviews'],
        ]);

        $this->set(compact('service'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['post', 'put']);
        
        $jwtPayload = $this->request->getAttribute('jwtPayload');

        $this->loadModel('Users');

        $user = $this->Users->find('all')->where(['id' =>  $jwtPayload->sub])->first();

        if ( !$user || empty($user['service_provider_id']) ) {

            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'erro',
                'msg' => 'Sem permissão de acesso'
            ]));

        }

        $service = $this->Services->newEmptyEntity();
        $dados = json_decode($this->request->getData('dados'), true);
        $dados['service_provider_id'] = $user->service_provider_id;

        $service = $this->Services->patchEntity($service, $dados);
        
        if ( !$this->Services->save($service)) {
            $errors = $service->getErrors();

            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'erro',
                'msg' => 'Erro ao salvar os dados do serviço',
                'error' => $errors
            ]));
        }

        return $this->response->withType('application/json')
        ->withStringBody(json_encode([
            'status' => 'ok',
            'msg' => 'Seriviço cadastrado com sucesso!'
        ]));
    }

    /**
     * Edit method
     *
     * @param string|null $id Service id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        
        $jwtPayload = $this->request->getAttribute('jwtPayload');

        $this->loadModel('Users');

        $user = $this->Users->find('all')->where(['id' =>  $jwtPayload->sub])->first();

        if ( !$user || empty($user['service_provider_id']) ) {

            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'erro',
                'msg' => 'Sem permissão de acesso'
            ]));

        }
        
        $service = $this->Services->get($id, [
            'conditions' => [
                'service_provider_id' => $user['service_provider_id']
            ],
        ]);

        if ( !$service ) {

            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'erro',
                'msg' => 'Dados do serviço não encontrados.'
            ]));

        }
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $service = $this->Services->patchEntity($service, json_decode($this->request->getData('dados'), true));
        }
        
        if ( !$this->Services->save($service) ) {

            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'erro',
                'msg' => 'Ocorreu um erro ao atualizar o serviço, por favor, tente mais tarde'
            ]));

        }

        return $this->response->withType('application/json')
        ->withStringBody(json_encode([
            'status' => 'ok',
            'msg' => 'Serviço alterado com sucesso!'
        ]));
    
    }

    public function saveData()
    {
        $this->request->allowMethod(['post', 'put']);

        $this->loadModel('ServiceProviders');
        $this->loadModel('Users');
        $this->loadModel('Services');
    
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

        $services = $dados['services'];
        foreach ($services as $key => $service) {
            $service['price'] = preg_replace('/[^0-9.,]/', '', $service['price']);
            $service['price'] = str_replace('.', '', $service['price']); // remover possível separador de milhar
            $service['price'] = str_replace(',', '.', $service['price']); // substituir vírgula por ponto
            $service['price'] = (float) $service['price']; // converter para float
            $services[$key]['price'] = $service['price'];
            $services[$key]['service_provider_id'] = $user->service_provider_id;
        }

        foreach ($services as $key => $service) {

            if ( isset($service['id']) ) {
                $serviceEntity = $this->Services->findOrCreate(['id' => $service['id']]);
            } else {
                $serviceEntity = $this->Services->newEmptyEntity();
            }

            $serviceEntity = $this->Services->patchEntity($serviceEntity, $services[$key]);
            $this->Services->saveOrFail($serviceEntity);
        }
  

        return $this->response->withType('application/json')
        ->withStringBody(json_encode([
            'status' => 'ok',
            'message' => 'Dados atualizados com sucesso!',
        ]));

    }
}

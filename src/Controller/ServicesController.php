<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Services Controller
 *
 * @property \App\Model\Table\ServicesTable $Services
 * @method \App\Model\Entity\Service[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ServicesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ServiceCategories', 'ServiceSubcategories', 'ServiceProviders'],
        ];
        $services = $this->paginate($this->Services);

        $this->set(compact('services'));
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

    /**
     * Delete method
     *
     * @param string|null $id Service id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $service = $this->Services->get($id);
        if ($this->Services->delete($service)) {
            $this->Flash->success(__('The service has been deleted.'));
        } else {
            $this->Flash->error(__('The service could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

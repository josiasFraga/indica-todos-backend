<?php
declare(strict_types=1);

namespace App\Controller;

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
        $serviceProviders = $this->paginate($this->ServiceProviders);

        $this->set(compact('serviceProviders'));
    }

    /**
     * View method
     *
     * @param string|null $id Service Provider id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $serviceProvider = $this->ServiceProviders->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('serviceProvider'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $serviceProvider = $this->ServiceProviders->newEmptyEntity();
        if ($this->request->is('post')) {
            $serviceProvider = $this->ServiceProviders->patchEntity($serviceProvider, $this->request->getData());
            if ($this->ServiceProviders->save($serviceProvider)) {
                $this->Flash->success(__('The service provider has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The service provider could not be saved. Please, try again.'));
        }
        $this->set(compact('serviceProvider'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Service Provider id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $serviceProvider = $this->ServiceProviders->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $serviceProvider = $this->ServiceProviders->patchEntity($serviceProvider, $this->request->getData());
            if ($this->ServiceProviders->save($serviceProvider)) {
                $this->Flash->success(__('The service provider has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The service provider could not be saved. Please, try again.'));
        }
        $this->set(compact('serviceProvider'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Service Provider id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $serviceProvider = $this->ServiceProviders->get($id);
        if ($this->ServiceProviders->delete($serviceProvider)) {
            $this->Flash->success(__('The service provider has been deleted.'));
        } else {
            $this->Flash->error(__('The service provider could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

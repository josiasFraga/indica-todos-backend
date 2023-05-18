<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ServiceCategories Controller
 *
 * @property \App\Model\Table\ServiceCategoriesTable $ServiceCategories
 * @method \App\Model\Entity\ServiceCategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ServiceCategoriesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        //$jwtPayload = $this->request->getAttribute('jwtPayload');
        $with_business = $this->request->getQuery('with_business');
        $user_location = $this->request->getQuery('user_location');


        $conditions = [];

        if ( $with_business ){ 
            $serviceCatgories = $this->ServiceCategories
            ->find('all')
            ->where()
            ->innerJoinWith('Services')
            ->matching('Services.ServiceProviders', function ($q) use ($user_location) {
                if (!empty($user_location)) {
                    return $q->where([
                        'ServiceProviders.city' => $user_location['city'],
                        'ServiceProviders.state' => $user_location['state']
                    ]);
                }
                return $q;
            })
            ->distinct(['ServiceCategories.id'])
            ->order('ServiceCategories.name');
        } else {
            
            $serviceCatgories = $this->ServiceCategories->find('all')->where()->order('ServiceCategories.name');
        }


        $this->set([
            'data' => $serviceCatgories,
            'status' => 'ok',
            '_serialize' => ['data', 'status']
        ]);
    }

    /**
     * View method
     *
     * @param string|null $id Service Category id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $serviceCategory = $this->ServiceCategories->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('serviceCategory'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $serviceCategory = $this->ServiceCategories->newEmptyEntity();
        if ($this->request->is('post')) {
            $serviceCategory = $this->ServiceCategories->patchEntity($serviceCategory, $this->request->getData());
            if ($this->ServiceCategories->save($serviceCategory)) {
                $this->Flash->success(__('The service category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The service category could not be saved. Please, try again.'));
        }
        $this->set(compact('serviceCategory'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Service Category id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $serviceCategory = $this->ServiceCategories->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $serviceCategory = $this->ServiceCategories->patchEntity($serviceCategory, $this->request->getData());
            if ($this->ServiceCategories->save($serviceCategory)) {
                $this->Flash->success(__('The service category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The service category could not be saved. Please, try again.'));
        }
        $this->set(compact('serviceCategory'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Service Category id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $serviceCategory = $this->ServiceCategories->get($id);
        if ($this->ServiceCategories->delete($serviceCategory)) {
            $this->Flash->success(__('The service category has been deleted.'));
        } else {
            $this->Flash->error(__('The service category could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

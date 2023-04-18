<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ServiceSubcategories Controller
 *
 * @property \App\Model\Table\ServiceSubcategoriesTable $ServiceSubcategories
 * @method \App\Model\Entity\ServiceSubcategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ServiceSubcategoriesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        //$jwtPayload = $this->request->getAttribute('jwtPayload');

        $category_id = $this->request->getQuery('category_id');
        $serviceSubcatgories = $this->ServiceSubcategories->find('all')->where(['category_id' => $category_id]);

        $this->set([
            'data' => $serviceSubcatgories,
            '_serialize' => ['data']
        ]);
    }

    /**
     * View method
     *
     * @param string|null $id Service Subcategory id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $serviceSubcategory = $this->ServiceSubcategories->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('serviceSubcategory'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $serviceSubcategory = $this->ServiceSubcategories->newEmptyEntity();
        if ($this->request->is('post')) {
            $serviceSubcategory = $this->ServiceSubcategories->patchEntity($serviceSubcategory, $this->request->getData());
            if ($this->ServiceSubcategories->save($serviceSubcategory)) {
                $this->Flash->success(__('The service subcategory has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The service subcategory could not be saved. Please, try again.'));
        }
        $this->set(compact('serviceSubcategory'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Service Subcategory id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $serviceSubcategory = $this->ServiceSubcategories->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $serviceSubcategory = $this->ServiceSubcategories->patchEntity($serviceSubcategory, $this->request->getData());
            if ($this->ServiceSubcategories->save($serviceSubcategory)) {
                $this->Flash->success(__('The service subcategory has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The service subcategory could not be saved. Please, try again.'));
        }
        $this->set(compact('serviceSubcategory'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Service Subcategory id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $serviceSubcategory = $this->ServiceSubcategories->get($id);
        if ($this->ServiceSubcategories->delete($serviceSubcategory)) {
            $this->Flash->success(__('The service subcategory has been deleted.'));
        } else {
            $this->Flash->error(__('The service subcategory could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

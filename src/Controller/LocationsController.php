<?php
declare(strict_types=1);

namespace App\Controller;


class LocationsController extends AppController
{

    public function index()
    {
        $this->paginate = [
            'contain' => ['ServiceProviders'],
        ];
        $locations = $this->paginate($this->Locations);

        $this->set(compact('locations'));
    }

    public function view($id = null)
    {
        $location = $this->Locations->get($id, [
            'contain' => ['ServiceProviders'],
        ]);

        $this->set(compact('location'));
    }

    public function add()
    {
        $location = $this->Locations->newEmptyEntity();
        if ($this->request->is('post')) {
            $location = $this->Locations->patchEntity($location, $this->request->getData());
            if ($this->Locations->save($location)) {
                $this->Flash->success(__('The location has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The location could not be saved. Please, try again.'));
        }
        $serviceProviders = $this->Locations->ServiceProviders->find('list', ['limit' => 200])->all();
        $this->set(compact('location', 'serviceProviders'));
    }


    public function edit($id = null)
    {
        $location = $this->Locations->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $location = $this->Locations->patchEntity($location, $this->request->getData());
            if ($this->Locations->save($location)) {
                $this->Flash->success(__('The location has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The location could not be saved. Please, try again.'));
        }
        $serviceProviders = $this->Locations->ServiceProviders->find('list', ['limit' => 200])->all();
        $this->set(compact('location', 'serviceProviders'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $location = $this->Locations->get($id);
        if ($this->Locations->delete($location)) {
            $this->Flash->success(__('The location has been deleted.'));
        } else {
            $this->Flash->error(__('The location could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

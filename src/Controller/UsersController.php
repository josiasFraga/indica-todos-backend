<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Http\Exception\UnauthorizedException;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ServiceProviders'],
        ];
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function me($id = null)
    {
        $jwtPayload = $this->request->getAttribute('jwtPayload');
        $user = $this->Users->find()
        ->select(['id', 'name', 'email', 'phone', 'photo', 'created'])
        ->where([
            'Users.id' => $jwtPayload->sub
        ])->first();

        return $this->response->withType('application/json')
        ->withStringBody(json_encode([
            'status' => 'ok',
            'data' => $user
        ]));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['post', 'put']);
        $dados = json_decode($this->request->getData('dados'), true);

        $user = $this->Users->newEntity($dados, [
            'associated' => ['ServiceProviders.Services']
        ]);

        //$user = $this->Users->patchEntity($user, $dados);

        if ( !$this->Users->save($user) ) {
            $errors = $user->getErrors();
            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'erro',
                'msg' => 'Occoreu um erro ao salvar seus dados. Por favor, tent mais tarde!',
                'error' => $errors
            ]));
        }

        return $this->response->withType('application/json')
        ->withStringBody(json_encode([
            'status' => 'ok',
            'msg' => 'Seu cadastro foi efetuado com suscesso!'
        ]));

    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $serviceProviders = $this->Users->ServiceProviders->find('list', ['limit' => 200])->all();
        $this->set(compact('user', 'serviceProviders'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

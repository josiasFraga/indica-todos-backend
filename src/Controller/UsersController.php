<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Log\Log;

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

        // Verifica se o usuário foi encontrado
        if ($user) {
            // Adiciona o caminho ao campo 'photo'
            $user->photo = 'img/users/' . $user->photo;
        }

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

    public function changePassword (){
        $jwtPayload = $this->request->getAttribute('jwtPayload');
        $user = $this->Users->get($jwtPayload->sub, [
            'contain' => [],
        ]);
        $dados = json_decode($this->request->getData('dados'), true);

        $user = $this->Users->patchEntity($user, ['password' => $dados['password']]);

        if ( !$this->Users->save($user) ) {

            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'erro',
                'message' => 'Ocorreu um erro ao tentar alterar a senha do usuário'
            ]));
        }


        return $this->response->withType('application/json')
        ->withStringBody(json_encode([
            'status' => 'ok'
        ]));

    }

    public function changePhoto (){
        $jwtPayload = $this->request->getAttribute('jwtPayload');
        $userId = $jwtPayload->sub;

        $foto = $this->getRequest()->getData('photo');

   
        try {
            $user = $this->Users->get($userId);
            unset($user->password);
            $user = $this->Users->patchEntity($user, $this->request->getData(), $this->request->getData(), [
                'associated' => [], // Certifique-se de desabilitar associações
            ]);
            
            if ($this->Users->save($user)) {
                return $this->getResponse()->withType('application/json')
                    ->withStringBody(json_encode([
                        'status' => 'ok'
                    ]));
            } else {
                throw new Exception('Ocorreu um erro ao tentar alterar a foto do usuário');
            }
        } catch (RecordNotFoundException $exception) {
            return $this->getResponse()->withType('application/json')
                ->withStatus(404)
                ->withStringBody(json_encode([
                    'status' => 'erro',
                    'message' => 'Usuário não encontrado'
                ]));
        } catch (Exception $exception) {
            return $this->getResponse()->withType('application/json')
                ->withStatus(500)
                ->withStringBody(json_encode([
                    'status' => 'erro',
                    'message' => $exception->getMessage()
                ]));
        }

    }
}

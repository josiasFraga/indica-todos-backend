<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Http\Exception;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Log\Log;
use Cake\I18n\Number;
use App\Model\Entity\Service;
use Cake\Http\ServerRequest;

use Cake\Core\Configure;
use PagSeguro\Configuration\Configure as PagSeguroConfigure;
use PagSeguro\Domains\Requests\DirectPayment\CreditCard as CreditCardRequest;
use PagSeguro\Services\Transactions\CreateTransaction;
use App\Model\Table\PagseguroTable;
use Cake\Core\Exception\Exception as CakeException;

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

    public function endProviderRegister()
    {

        $this->request->allowMethod(['post', 'put']);
        $this->loadModel('CreditCards');
        $this->loadModel('ServiceProviders');
        $request = $this->getRequest();
        $clientIP = $request->clientIp();
        $dados = json_decode($this->request->getData('dados'), true);
        $jwtPayload = $this->request->getAttribute('jwtPayload');
        $userId = $jwtPayload->sub;
        $query = $this->Users->find()->contain('ServiceProviders')->where(['Users.id' => $userId]);
        $user = $query->first();

        $serviceProvider = $this->ServiceProviders->get($user->service_provider->id);
        
        if ( !$serviceProvider ) {
            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'erro',
                'message' => 'Não encontramos os dados da sua empresa.',
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

        $cc_number = $dados['cc_number'];
        $cc_name = $dados['cc_name'];
        $cc_expiry = $dados['cc_expiry'];
        list($cc_expiry_month, $cc_expiry_year) = explode('/',$cc_expiry);
        $cc_secure_code = $dados['cc_secure_code'];

        $checkCreditCard = $this->CreditCards->find()
        ->where([
            'number' => $cc_number, // substitua $number pelo número desejado
            'user_id' => $userId // substitua $user_id pelo ID do usuário desejado
        ]);

        $checkCreditCard = $checkCreditCard->first(); // Retorna o primeiro registro que corresponde à consulta

        if ($checkCreditCard) {
            $create_token_cc = $checkCreditCard->token;
        } else {

            try{
    
                $create_token_cc = $this->createCreditCardToken($cc_number, $cc_secure_code, $cc_expiry_month, $cc_expiry_year);
            } catch (\Exception $e) {
                // Tratamento da exceção            
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode([
                        'status' => 'erro',
                        'message' => $e->getMessage(),
                    ]));
            }

            $data_credit_card_to_save = [
                'number' => $cc_number,
                'user_id' => $userId,
                'token' => $create_token_cc,
            ];

            $credit_card = $this->CreditCards->newEmptyEntity();
            $credit_card = $this->CreditCards->patchEntity($credit_card, $data_credit_card_to_save);

            if ( !$this->CreditCards->save($credit_card)) {
                $errors = $credit_card->getErrors();
    
                return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'erro',
                    'msg' => 'Erro com os dados do cartão de crédito',
                    'error' => $errors
                ]));
            }
        }

        $user_phone = $this->extractDddPhone($user->service_provider->phone);
        $holder_phone = $this->extractDddPhone($dados['credit_card_holder']['phone']);

        $dados_assinatura = [
            'user_name' => $user->service_provider->name,
            'user_email' => $user->service_provider->email,
            'user_ip' => $clientIP != "127.0.0.1" ? $clientIP : '186.208.149.107',
            'user_phone_ddd' => $user_phone['ddd'],
            'user_phone_number' => $user_phone['number'],
            'user_addess' => $user->service_provider->address,
            'user_addess_number' => $user->service_provider->address_number,
            'user_addess_complement' => $user->service_provider->address_complement,
            'user_addess_district' => $user->service_provider->neighborhood,
            'user_addess_city' => $user->service_provider->city,
            'user_addess_uf' => $user->service_provider->state,
            'user_addess_postal_code' => str_replace("-", "", $user->service_provider->postal_code),
            'user_cpf' => preg_replace("/[^0-9]/", "", $user->cpf),
    
            'cc_token' => $create_token_cc,
            'cc_holder_name' => $dados['credit_card_holder']['name'],
            'cc_holder_birth_date' => $dados['credit_card_holder']['birth_date'],
            'cc_holder_cpf' => preg_replace("/[^0-9]/", "", $dados['credit_card_holder']['cpf']),
            'cc_holder_ddd' => $holder_phone['ddd'],
            'cc_holder_phone' => $holder_phone['number'],
    
            'cc_billing_street' => $dados['billing_address']['address'],
            'cc_billing_number' => $dados['billing_address']['address_number'],
            'cc_billing_complement' => $dados['billing_address']['address_complement'],
            'cc_billing_district' => $dados['billing_address']['neighborhood'],
            'cc_billing_city' => $dados['billing_address']['city'],
            'cc_billing_uf' => $dados['billing_address']['state'],
            'cc_billing_postal_code' => str_replace("-", "", $dados['billing_address']['postal_code']),
        ];

        //criando assiantura
        try{
            $create_signature_token_cc = 'aaa';
            //$create_signature_token_cc = $this->createSignature($dados_assinatura);
        } catch (\Exception $e) {
            // Tratamento da exceção
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'erro',
                    'message' => $e->getMessage(),
                ]));
        }

        $serviceProvider->active_signature = $create_signature_token_cc;
        
        if ( !$this->ServiceProviders->save($serviceProvider) ) {
            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'erro',
                'message' => 'Ocorreu um erro ao atualizar a assinatura ativa',
            ]));

        }

        $this->loadModel('Services');
        $services = $this->Services->newEntities($services);
        $save_service = $this->Services->saveMany($services);
    
        if ( !$save_service ) {
            /*$errors = [];
            foreach ($services as $service) {
                $errors[] = $service->getErrors();
            }
            debug($errors);
            die();*/
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'erro',
                    'message' => 'Ocorreu um erro ao tentar salvar os serviços prestados',
                ]));
        }



        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'ok',
                'message' => 'Cadastro finalizado com sucesso!',
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

    private function createCreditCardToken($number, $ccv, $month, $year)
    {

        // Carrega as configurações do arquivo pagseguro.php
        //Configure::load('pagseguro');

        // Obtém as configurações do PagSeguro
        //$pagseguroConfig = Configure::read('PagSeguro');

        // Configurar as credenciais do PagSeguro
        //PagSeguroConfigure::setAccountCredentials($pagseguroConfig['email'], $pagseguroConfig['token']);
        //PagSeguroConfigure::setEnvironment($pagseguroConfig['sandbox']);
    
        $pagseguro = new PagseguroTable();

        // criar sessão
        $response = $pagseguro->criarSessao();

        if ( $response == false ) {
            throw new CakeException('Erro ao criar a sessão');
        }

        // Carrega o XML
        $xml = simplexml_load_string($response);

        // Extrai o Session ID
        $sessionId = (string) $xml->id;
        
    
        $brand = $this->identifyCardBrand($number);
    
        // Dados do cartão de crédito
        $creditCardData = [
            'amount' => '1,00',
            'cardNumber' => $number,
            'cardBrand' => strtolower($brand),
            'cardCvv' => $ccv,
            'cardExpirationMonth' => $month,
            'cardExpirationYear' => "20".$year,
            'sessionId' => $sessionId
        ];

   
        // Solicitar a criação do token do cartão de crédito
        $response = $pagseguro->criarTokenCartao($creditCardData);

        if ( $response == false ) {
            throw new CakeException('Erro ao criar token do cartão de crédito');
        }

        $xml = simplexml_load_string($response);

        // Verifica se existem elementos <error>
        if (count($xml->error) > 0) {
            // Extrai a mensagem de erro
            $errorMsg = (string) $xml->error->message;
            throw new CakeException($errorMsg);
        }

        // Extrai o Session Card Token
        $cardToken = (string) $xml->token;
        
        return $cardToken;
    
    }

    private function createSignature($dados)
    {
    
        $pagseguro = new PagseguroTable();
   
        // Solicitar a criação do token do cartão de crédito
        $response = $pagseguro->criarAssinatura($dados);

        if ( $response == false ) {
            throw new CakeException('Erro ao criar assinatura');
        }

        $xml = simplexml_load_string($response);

        debug($response);
        die();

        // Verifica se existem elementos <error>
        if ($xml->error && count($xml->error) > 0) {
            // Extrai a mensagem de erro
            $errorMsg = (string) $xml->error->message;
            throw new CakeException($errorMsg);
        }

        // Extrai o Session Card Token
        $cardToken = (string) $xml->token;
        
        return $cardToken;
    
    }

    private function identifyCardBrand($cardNumber)
    {
        $firstFourDigits = substr($cardNumber, 0, 4);
        $cardBrand = null;
    
        if ($firstFourDigits === '4111') {
            $cardBrand = 'Visa';
        } elseif ($firstFourDigits === '5100' || $firstFourDigits === '5555') {
            $cardBrand = 'Mastercard';
        } elseif ($firstFourDigits === '3400' || $firstFourDigits === '3700') {
            $cardBrand = 'American Express';
        } elseif ($firstFourDigits === '6011' || $firstFourDigits === '6220' || $firstFourDigits === '6440' || $firstFourDigits === '65') {
            $cardBrand = 'Discover';
        } elseif ($firstFourDigits === '3528' || $firstFourDigits === '3088') {
            $cardBrand = 'JCB';
        } elseif ($firstFourDigits === '5019' || $firstFourDigits === '5020' || $firstFourDigits === '5038' || $firstFourDigits === '6304' || $firstFourDigits === '6759' || $firstFourDigits === '6761' || $firstFourDigits === '6762' || $firstFourDigits === '6763' || $firstFourDigits === '0604') {
            $cardBrand = 'Maestro';
        } // Adicione outros números de identificação para as bandeiras adicionais
    
        return $cardBrand;
    }

    private function extractDddPhone($phone) 
    {
        $pattern = "/\((\d{2})\)\s?(\d{4,5}-\d{3,4})/";
        preg_match($pattern, $phone, $matches);
    
        $ddd = $matches[1];
        $number = str_replace('-', '', $matches[2]);
    
        return [
            'ddd' => $ddd,
            'number' => $number
        ];

    }

    public function deleteAccount (){
        $jwtPayload = $this->request->getAttribute('jwtPayload');
        $user = $this->Users->get($jwtPayload->sub);

        if ( !$this->Users->delete($user) ) {

            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'erro',
                'message' => 'Ocorreu um erro ao tentar excluir sua conta de usuário. Por favor, tente novamente mais tarde.'
            ]));
        }


        return $this->response->withType('application/json')
        ->withStringBody(json_encode([
            'status' => 'ok',
            'message' => 'Conta excluída com sucesso'
        ]));

    }
}

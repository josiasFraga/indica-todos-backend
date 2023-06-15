<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Http\Client;
use Cake\ORM\Table;
use Cake\Core\Configure;
use PagSeguro\Configuration\Configure as PagSeguroConfigure;

class PagseguroTable extends Table
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    // Adicione métodos para cada solicitação específica que você precisa fazer para o PagSeguro
    public function criarTokenCartao($dados)
    {
        // Carrega as configurações do arquivo pagseguro.php
        Configure::load('pagseguro');

        // Obtém as configurações do PagSeguro
        $pagseguroConfig = Configure::read('PagSeguro');


        $url = 'https://df.uol.com.br/v2/cards/?email=' . $pagseguroConfig['email'] . '&token=' . $pagseguroConfig['token'];
        $params = http_build_query($dados);
    
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: TS0153c357=0160ed0a6c16c2832ea82317f504a980ce685fe09e9b1a8f0c6a8b83f6a505254cb0b17014a67f763c3b24d287b3d32173637d3d5e'
            ),
        ));
    
        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            $errorCode = curl_errno($curl);
            curl_close($curl);
        
            // Retorne o erro ou faça algo com base nele
            return false;
        }
    
        curl_close($curl);
    
        return $response;
    }

    public function criarSessao()
    {
        // Carrega as configurações do arquivo pagseguro.php
        Configure::load('pagseguro');

        // Obtém as configurações do PagSeguro
        $pagseguroConfig = Configure::read('PagSeguro');

        $url = 'https://ws.sandbox.pagseguro.uol.com.br/sessions/?email=' . $pagseguroConfig['email'] . '&token=' . $pagseguroConfig['token'];

        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYPEER => false,
        ));

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            $errorCode = curl_errno($curl);
            curl_close($curl);
        
            // Retorne o erro ou faça algo com base nele
            return false;
        }
    
        curl_close($curl);
    
        return $response;
    }

    public function criarAssinatura($dados)
    {
        // Carrega as configurações do arquivo pagseguro.php
        Configure::load('pagseguro');

        // Obtém as configurações do PagSeguro
        $pagseguroConfig = Configure::read('PagSeguro');

        $url = 'https://ws.sandbox.pagseguro.uol.com.br/pre-approvals?email=' . $pagseguroConfig['email'] . '&token=' . $pagseguroConfig['token'];

        $params = '{
            "plan": "F2471B5B41411EDAA4EBAFB9BCE350E7",
            "reference": "tese",
            "sender": {
                "name": "' . $dados['user_name'] . '",
                "email": "' . $dados['user_email'] . '",
                "ip": "' . $dados['user_ip'] . '",
                "phone": {
                    "areaCode": "' . $dados['user_phone_ddd'] . '",
                    "number": "' . $dados['user_phone_number'] . '"
                },
                "address": {
                    "street": "' . $dados['user_addess'] . '",
                    "number": "' . $dados['user_addess_number'] . '",
                    "complement": "' . $dados['user_addess_complement'] . '",
                    "district": "' . $dados['user_addess_district'] . '",
                    "city": "' . $dados['user_addess_city'] . '",
                    "state": "' . $dados['user_addess_uf'] . '",
                    "country": "BRA",
                    "postalCode": "' . $dados['user_addess_postal_code'] . '"
                },
                "documents": [
                    {
                        "type": "CPF",
                        "value": "' . $dados['user_cpf'] . '"
                    }
                ]
            },
            "paymentMethod": {
                "type": "CREDITCARD",
                "creditCard": {
                    "token": "' . $dados['cc_token'] . '",
                    "holder": {
                        "name": "' . $dados['cc_holder_name'] . '",
                        "birthDate": "' . $dados['cc_holder_birth_date'] . '",
                        "documents": [
                            {
                                "type": "CPF",
                                "value": "' . $dados['cc_holder_cpf'] . '"
                            }
                        ],
                        "phone": {
                            "areaCode": "' . $dados['cc_holder_ddd'] . '",
                            "number": "' . $dados['cc_holder_phone'] . '"
                        },
                        "billingAddress": {
                            "street": "' . $dados['cc_billing_street'] . '",
                            "number": "' . $dados['cc_billing_number'] . '",
                            "complement": "' . $dados['cc_billing_complement'] . '",
                            "district": "' . $dados['cc_billing_district'] . '",
                            "city": "' . $dados['cc_billing_city'] . '",
                            "state": "' . $dados['cc_billing_uf'] . '",
                            "country": "BRA",
                            "postalCode": "' . $dados['cc_billing_postal_code'] . '"
                        }
                    }
                }
            }
        }';

    
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/vnd.pagseguro.com.br.v3+xml;charset=ISO-8859-1',
                'Content-Type: application/json'
            ),
        ));
    
        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            $errorCode = curl_errno($curl);
            debug($error);
            curl_close($curl);
        
            // Retorne o erro ou faça algo com base nele
            return false;
        }
    
        curl_close($curl);
    
        return $response;

    }


}

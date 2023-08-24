<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Mailer\Mailer;
use App\Model\Entity\ChangePasswordToken;
use Cake\Mailer\TransportFactory;
use Cake\I18n\FrozenTime;

class ChangePasswordController extends AppController
{

    public function sendCode()
    {

        $dados = json_decode($this->request->getData('dados'), true);

        if ( !isset($dados['user_email']) || empty($dados['user_email']) ) {

            return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'erro',
                'message' => 'Email não informado',
            ]));
        }

        $this->loadModel('Users');

        $user = $this->Users->findByEmail($dados['user_email'])->first();

        if (!$user) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'ok',
                    'message' => 'Código enviado para o email ' . $dados['user_email'],
                ]));
        }

        
        // Verifica se já existe um código cadastrado para o usuário
        $existingToken = $this->Users->ChangePasswordTokens
        ->find()
        ->where(['user_id' => $user->id])
        ->where(function ($exp) {
            return $exp->gt('validity', date('Y-m-d H:i:s'));
        })
        ->first();
        
        if ($existingToken) {
            // Envia o código já cadastrado para o e-mail do usuário novamente
            $codigo = $existingToken->code;
        } else {
            // Gera um novo número aleatório de 4 dígitos
            $codigo = mt_rand(1000, 9999);
    
            // Salva o novo código na tabela ChangePasswordToken
            $changePasswordToken = new ChangePasswordToken();
            $changePasswordToken->user_id = $user->id;
            $changePasswordToken->validity = date('Y-m-d H:i:s', strtotime('+1 hour')); // Define a validade do código (1 hora)
            $changePasswordToken->code = $codigo;
    
            if (!$this->Users->ChangePasswordTokens->save($changePasswordToken)) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode([
                        'status' => 'erro',
                        'message' => 'Ocorreu um erro ao salvar o código',
                    ]));
            }
        }

        // Envia o código não criptografado para o e-mail do usuário
        $email = new Mailer();
        $email->setProfile('kinghost');

        $email
            ->setTo($user->email)
            ->setSubject('Código de redefinição de senha')
            ->deliver('Seu código de redefinição de senha é: ' . $codigo);

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'ok',
                'message' => 'Código enviado com sucesso!',
            ]));

    }

    public function verifyCode()
    {
        // Obtenha os dados enviados por POST
        $dados = json_decode($this->request->getData('dados'), true);

        if (!isset($dados['user_email']) || empty($dados['user_email']) || !isset($dados['code']) || empty($dados['code'])) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'erro',
                    'message' => 'Email e/ou código não informado(s)',
                ]));
        }

        $this->loadModel('Users');
        $this->loadModel('ChangePasswordTokens');

        // Verifique se o usuário existe
        $user = $this->Users->findByEmail($dados['user_email'])->first();

        if (!$user) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'erro',
                    'message' => 'Código inválido ou expirado',
                ]));
        }

        // Verifique se o código existe e é válido
        $existingToken = $this->ChangePasswordTokens
            ->find()
            ->where(['user_id' => $user->id])
            ->where(['code' => $dados['code']])
            ->where(function ($exp) {
                return $exp->gt('validity', FrozenTime::now());
            })
            ->first();

        if (!$existingToken) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'erro',
                    'message' => 'Código inválido ou expirado',
                ]));
        }

        // Se o código existe e é válido, você pode prosseguir com a lógica adicional aqui,
        // como redirecionar para uma nova página para permitir que o usuário redefina a senha, por exemplo.

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'ok',
                'message' => 'Código válido',
            ]));
    }

    public function changePassword()
    {
        // Obtenha os dados enviados por POST
        $dados = json_decode($this->request->getData('dados'), true);

        if (
            !isset($dados['user_email']) || empty($dados['user_email']) ||
            !isset($dados['code']) || empty($dados['code']) ||
            !isset($dados['new_password']) || empty($dados['new_password'])
        ) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'erro',
                    'message' => 'Email, código e/ou nova senha não informados',
                ]));
        }

        $this->loadModel('Users');
        $this->loadModel('ChangePasswordTokens');

        // Verifique se o usuário existe
        $user = $this->Users->findByEmail($dados['user_email'])->first();

        if (!$user) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'erro',
                    'message' => 'Usuário não encontrado',
                ]));
        }

        // Verifique se o código existe e é válido
        $existingToken = $this->ChangePasswordTokens
            ->find()
            ->where(['user_id' => $user->id])
            ->where(['code' => $dados['code']])
            ->where(function ($exp) {
                return $exp->gt('validity', FrozenTime::now());
            })
            ->first();

        if (!$existingToken) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'erro',
                    'message' => 'Código inválido ou expirado',
                ]));
        }

        // Se o código existe e é válido, atualize a senha do usuário no banco de dados
        $user = $this->Users->patchEntity($user, ['password' => $dados['new_password']]);
        if (!$this->Users->save($user)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'erro',
                    'message' => 'Erro ao atualizar a senha',
                ]));
        }

        // Remova o token de recuperação de senha do banco de dados
        $this->ChangePasswordTokens->delete($existingToken);

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'ok',
                'message' => 'Senha atualizada com sucesso!',
            ]));
    }

}

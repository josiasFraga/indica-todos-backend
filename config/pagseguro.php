<?php
use Cake\Core\Configure;

Configure::load('app_local'); // Carrega as configurações do arquivo app_local.php

$pagseguroConfig = Configure::read('PagSeguro');

return [
    'credentials' => [
        'email' => $pagseguroConfig['email'],
        'token' => $pagseguroConfig['token'],
    ],
    'sandbox' => $pagseguroConfig['sandbox'],
];
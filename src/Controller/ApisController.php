<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Http\Client;


class ApisController extends AppController
{
    public function buscaBairros() {

    

        $cidade = $this->request->getQuery('cidade');
        $estado = $this->request->getQuery('estado');

        // Defina as URLs da API Brasil Aberto
        $urlCidades = 'https://brasilaberto.com/api/v1/cities/' . $estado;
        $urlBairros = 'https://brasilaberto.com/api/v1/districts/';

        $client = new Client();
        $token = env('API_BAIRROS_KEY', null);

        // Buscar o ID da cidade
        $responseCidades = $client->get($urlCidades, [], [
            'headers' => ['Authorization' => 'Bearer ' . $token]
        ]);
        $cidades = $responseCidades->getJson();

        $cidadeId = null;
        foreach ($cidades['result'] as $cidadeItem) {
            if ($cidadeItem['name'] == $cidade) {
                $cidadeId = $cidadeItem['id'];
                break;
            }
        }

        if (!$cidadeId) {
            $this->set([
                'status' => 'error',
                'message' => 'Cidade não encontrada.',
                '_serialize' => ['status', 'message']
            ]);
            return;
        }

        // Buscar os bairros da cidade
        $responseBairros = $client->get($urlBairros . $cidadeId, [], [
            'headers' => ['Authorization' => 'Bearer ' . $token]
        ]);


        $bairros = $responseBairros->getJson();

        $this->set([
            'status' => 'ok',
            'data' => $bairros['result'],
            '_serialize' => ['data', 'status']
        ]);

    }
}
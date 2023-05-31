<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * MeasurementUnits Controller
 *
 * @property \App\Model\Table\MeasurementUnitsTable $MeasurementUnits
 * @method \App\Model\Entity\MeasurementUnit[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MeasurementUnitsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {

        $serviceSubcatgories = $this->MeasurementUnits->find('all');

        $this->set([
            'status' => 'ok',
            'data' => $serviceSubcatgories,
            '_serialize' => ['data', 'status']
        ]);
    }
}

<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ServiceSubcategories Controller
 *
 * @property \App\Model\Table\ServiceSubcategoriesTable $ServiceSubcategories
 * @method \App\Model\Entity\ServiceSubcategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class WebhooksController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function pagseguro()
    {
        
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $servicesTable = TableRegistry::getTableLocator()->get('Services');
        $serviceProviders = TableRegistry::getTableLocator()->get('ServiceProviders');
        die();
    }
}
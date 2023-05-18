<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ServiceProviderVisitsFixture
 */
class ServiceProviderVisitsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'created' => 1684434094,
                'modified' => 1684434094,
                'ip_address' => 'Lorem ipsum dolor sit amet',
                'phone_clicked' => 'Lorem ipsum dolor sit amet',
                'user_id' => 1,
                'service_provider_id' => 1,
            ],
        ];
        parent::init();
    }
}

<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PaymentsFixture
 */
class PaymentsFixture extends TestFixture
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
                'provider_id' => 1,
                'amount' => 1.5,
                'status' => 'Lorem ipsum dolor sit amet',
                'payment_type' => 'Lorem ipsum dolor sit amet',
                'created_at' => '2023-04-18 12:59:36',
            ],
        ];
        parent::init();
    }
}

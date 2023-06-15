<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CreditCardsFixture
 */
class CreditCardsFixture extends TestFixture
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
                'user_id' => 1,
                'number' => 'Lorem ipsum dolor ',
                'token' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}

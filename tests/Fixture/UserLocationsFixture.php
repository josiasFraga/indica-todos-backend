<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UserLocationsFixture
 */
class UserLocationsFixture extends TestFixture
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
                'created' => '2023-04-25 14:11:21',
                'modified' => '2023-04-25 14:11:21',
                'user_id' => 1,
                'device_id' => 'Lorem ipsum dolor sit amet',
                'city' => 'Lorem ipsum dolor sit amet',
                'state' => '',
            ],
        ];
        parent::init();
    }
}

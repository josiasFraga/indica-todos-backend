<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ChangePasswordTokensFixture
 */
class ChangePasswordTokensFixture extends TestFixture
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
                'validity' => '2023-07-24 13:50:55',
                'code' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}

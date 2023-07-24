<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ChangePasswordTokensTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ChangePasswordTokensTable Test Case
 */
class ChangePasswordTokensTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ChangePasswordTokensTable
     */
    protected $ChangePasswordTokens;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.ChangePasswordTokens',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ChangePasswordTokens') ? [] : ['className' => ChangePasswordTokensTable::class];
        $this->ChangePasswordTokens = $this->getTableLocator()->get('ChangePasswordTokens', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->ChangePasswordTokens);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\ChangePasswordTokensTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\ChangePasswordTokensTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

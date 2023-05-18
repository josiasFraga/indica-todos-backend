<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ServiceProviderVisitsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ServiceProviderVisitsTable Test Case
 */
class ServiceProviderVisitsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ServiceProviderVisitsTable
     */
    protected $ServiceProviderVisits;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.ServiceProviderVisits',
        'app.Users',
        'app.ServiceProviders',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ServiceProviderVisits') ? [] : ['className' => ServiceProviderVisitsTable::class];
        $this->ServiceProviderVisits = $this->getTableLocator()->get('ServiceProviderVisits', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->ServiceProviderVisits);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\ServiceProviderVisitsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\ServiceProviderVisitsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

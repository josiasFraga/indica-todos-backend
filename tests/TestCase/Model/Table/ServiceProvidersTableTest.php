<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ServiceProvidersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ServiceProvidersTable Test Case
 */
class ServiceProvidersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ServiceProvidersTable
     */
    protected $ServiceProviders;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.ServiceProviders',
        'app.Locations',
        'app.Payments',
        'app.Services',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ServiceProviders') ? [] : ['className' => ServiceProvidersTable::class];
        $this->ServiceProviders = $this->getTableLocator()->get('ServiceProviders', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->ServiceProviders);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\ServiceProvidersTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

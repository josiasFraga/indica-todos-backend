<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ServiceProviderPhotosTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ServiceProviderPhotosTable Test Case
 */
class ServiceProviderPhotosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ServiceProviderPhotosTable
     */
    protected $ServiceProviderPhotos;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.ServiceProviderPhotos',
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
        $config = $this->getTableLocator()->exists('ServiceProviderPhotos') ? [] : ['className' => ServiceProviderPhotosTable::class];
        $this->ServiceProviderPhotos = $this->getTableLocator()->get('ServiceProviderPhotos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->ServiceProviderPhotos);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\ServiceProviderPhotosTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\ServiceProviderPhotosTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

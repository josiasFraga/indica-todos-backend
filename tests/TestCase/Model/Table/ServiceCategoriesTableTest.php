<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ServiceCategoriesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ServiceCategoriesTable Test Case
 */
class ServiceCategoriesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ServiceCategoriesTable
     */
    protected $ServiceCategories;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.ServiceCategories',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ServiceCategories') ? [] : ['className' => ServiceCategoriesTable::class];
        $this->ServiceCategories = $this->getTableLocator()->get('ServiceCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->ServiceCategories);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\ServiceCategoriesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

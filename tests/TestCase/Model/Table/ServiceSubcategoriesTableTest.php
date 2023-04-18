<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ServiceSubcategoriesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ServiceSubcategoriesTable Test Case
 */
class ServiceSubcategoriesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ServiceSubcategoriesTable
     */
    protected $ServiceSubcategories;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.ServiceSubcategories',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ServiceSubcategories') ? [] : ['className' => ServiceSubcategoriesTable::class];
        $this->ServiceSubcategories = $this->getTableLocator()->get('ServiceSubcategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->ServiceSubcategories);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\ServiceSubcategoriesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MeasurementUnitsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MeasurementUnitsTable Test Case
 */
class MeasurementUnitsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MeasurementUnitsTable
     */
    protected $MeasurementUnits;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.MeasurementUnits',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('MeasurementUnits') ? [] : ['className' => MeasurementUnitsTable::class];
        $this->MeasurementUnits = $this->getTableLocator()->get('MeasurementUnits', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->MeasurementUnits);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\MeasurementUnitsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

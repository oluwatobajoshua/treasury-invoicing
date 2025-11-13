<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AllowanceRatesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AllowanceRatesTable Test Case
 */
class AllowanceRatesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AllowanceRatesTable
     */
    protected $AllowanceRates;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.AllowanceRates',
        'app.JobLevels',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('AllowanceRates') ? [] : ['className' => AllowanceRatesTable::class];
        $this->AllowanceRates = $this->getTableLocator()->get('AllowanceRates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->AllowanceRates);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\AllowanceRatesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\AllowanceRatesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

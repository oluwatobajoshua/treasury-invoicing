<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TravelRequestsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TravelRequestsTable Test Case
 */
class TravelRequestsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TravelRequestsTable
     */
    protected $TravelRequests;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.TravelRequests',
        'app.Users',
        'app.WorkflowHistory',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('TravelRequests') ? [] : ['className' => TravelRequestsTable::class];
        $this->TravelRequests = $this->getTableLocator()->get('TravelRequests', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->TravelRequests);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\TravelRequestsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\TravelRequestsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

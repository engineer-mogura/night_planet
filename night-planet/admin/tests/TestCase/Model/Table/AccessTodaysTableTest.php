<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AccessTodaysTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AccessTodaysTable Test Case
 */
class AccessTodaysTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AccessTodaysTable
     */
    public $AccessTodays;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AccessTodays',
        'app.Shops',
        'app.Owners'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('AccessTodays') ? [] : ['className' => AccessTodaysTable::class];
        $this->AccessTodays = TableRegistry::getTableLocator()->get('AccessTodays', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AccessTodays);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DiarysLikesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DiarysLikesTable Test Case
 */
class DiarysLikesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DiarysLikesTable
     */
    public $DiarysLikes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.DiarysLikes',
        'app.Diaries',
        'app.Users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('DiarysLikes') ? [] : ['className' => DiarysLikesTable::class];
        $this->DiarysLikes = TableRegistry::getTableLocator()->get('DiarysLikes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DiarysLikes);

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

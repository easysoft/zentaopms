<?php
declare(strict_types = 1);
require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';
class repobranchruleZenTest extends baseTest
{
    protected $moduleName = 'repobranchrule';
    protected $className  = 'zen';

    /**
     * Test buildBranchRuleData method.
     *
     * @param  int    $typeID
     * @param  int    $repoID
     * @param  string $branchName
     * @param  object $data
     * @access public
     * @return object|false
     */
    public function buildBranchRuleDataTest(int $typeID, int $repoID, string $branchName, object $data): object|bool
    {
        $result = $this->invokeArgs('buildBranchRuleData', [$typeID, $repoID, $branchName, $data]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}

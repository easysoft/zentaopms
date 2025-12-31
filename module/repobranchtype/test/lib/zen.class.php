<?php
declare(strict_types = 1);
require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';
class repobranchtypeZenTest extends baseTest
{
    protected $moduleName = 'repobranchtype';
    protected $className  = 'zen';

    /**
     * Test validateBranchType method.
     *
     * @param  object $branchType
     * @param  int    $typeID
     * @access public
     * @return bool
     */
    public function validateBranchTypeTest(object $branchType, int $typeID = 0): bool
    {
        return $this->invokeArgs('validateBranchType', [$branchType, $typeID]);
    }

    /**
     * Test buildPrefixesDisplay method.
     *
     * @param  array $branchTypeList
     * @access public
     * @return array
     */
    public function buildPrefixesDisplayTest(array $branchTypeList): array
    {
        return $this->invokeArgs('buildPrefixesDisplay', [$branchTypeList]);
    }
}

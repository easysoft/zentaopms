<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class branchTaoTest extends baseTest
{
    protected $moduleName = 'branch';
    protected $className  = 'tao';

    /**
     * 合并分支后的其他数据处理。
     * other data process after merge branch.
     *
     * @param  int       $productID
     * @param  string    $mergedBranches
     * @param  object    $data
     * @access public
     * @return array|int
     */
    public function afterMergeTest(int $productID, string $mergedBranches, object $data): array|int
    {
        $targetBranch = $data->targetBranch;
        $objectID     = $this->instance->afterMerge($productID, $targetBranch, $mergedBranches, $data);

        if(dao::isError()) return dao::getError();

        return $this->instance->dao->select('COUNT(1) AS count')->from(TABLE_RELEASE)->where('deleted')->eq(0)->andWhere('branch')->in($data->mergedBranchIDList)->fetch('count');
    }
}

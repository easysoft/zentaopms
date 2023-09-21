<?php
declare(strict_types=1);
/**
 * The tao file of branch module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     branch
 * @link        https://www.zentao.net
 */
class branchTao extends branchModel
{
    /**
     * 合并分支后的其他数据处理。
     * other data process after merge branch.
     *
     * @param  int       $productID
     * @param  int       $targetBranch
     * @param  string    $mergedBranches
     * @param  object    $data
     * @access protected
     * @return bool
     */
    protected function afterMerge(int $productID, int $targetBranch, string $mergedBranches, object $data): bool
    {
        $this->dao->update(TABLE_RELEASE)->set('branch')->eq($targetBranch)->where('branch')->in($mergedBranches)->exec();     // Update release branch.
        $this->dao->update(TABLE_BUILD)->set('branch')->eq($targetBranch)->where('branch')->in($mergedBranches)->exec();       // Update build branch.
        $this->dao->update(TABLE_PRODUCTPLAN)->set('branch')->eq($targetBranch)->where('branch')->in($mergedBranches)->exec(); // Update plan branch.
        $this->dao->update(TABLE_MODULE)->set('branch')->eq($targetBranch)->where('branch')->in($mergedBranches)->exec();      // Update module branch.
        $this->dao->update(TABLE_BUG)->set('branch')->eq($targetBranch)->where('branch')->in($mergedBranches)->exec();         // Update bug branch.
        $this->dao->update(TABLE_CASE)->set('branch')->eq($targetBranch)->where('branch')->in($mergedBranches)->exec();        // Update case branch.

        /* Update story branch. */
        $this->dao->update(TABLE_STORY)->set('branch')->eq($targetBranch)->where('branch')->in($mergedBranches)->exec();
        $this->dao->update(TABLE_PROJECTSTORY)->set('branch')->eq($targetBranch)->where('branch')->in($mergedBranches)->exec();

        /* Linked project or execution. */
        $linkedProject = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)
            ->where('branch')->in($mergedBranches . ",$targetBranch")
            ->andWhere('product')->eq($productID)
            ->fetchGroup('project');

        $this->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('branch')->in($mergedBranches . ",$targetBranch")
            ->andWhere('product')->eq($productID)
            ->exec();
        foreach($linkedProject as $projectID => $projectProducts)
        {
            $plan = 0;
            if($data->createBranch) continue;

            /* Get the linked plan of the target branch. */
            foreach($projectProducts as $projectProduct)
            {
                if($projectProduct->branch == $targetBranch)
                {
                    $plan = $projectProduct->plan;
                    break;
                }
            }

            $projectProduct = new stdClass();
            $projectProduct->project = $projectID;
            $projectProduct->product = $productID;
            $projectProduct->branch  = $targetBranch;
            $projectProduct->plan    = $plan;
            $this->dao->insert(TABLE_PROJECTPRODUCT)->data($projectProduct)->autoCheck()->exec();
        }

        return !dao::isError();
    }

    /**
     * 根据产品和执行ID获取分支ID列表。
     * Get branch ID list by product and execution ID.
     *
     * @param  int       $productID
     * @param  int       $executionID
     * @access protected
     * @return array
     */
    protected function getIdListByRelation(int $productID, int $executionID): array
    {
        return $this->dao->select('branch')->from(TABLE_PROJECTPRODUCT)
            ->where('project')->eq($executionID)
            ->andWhere('product')->eq($productID)
            ->fetchAll('branch');
    }
}

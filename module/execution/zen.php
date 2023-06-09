<?php
declare(strict_types=1);
/**
 * The zen file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     xxx
 * @link        https://www.zentao.net
 */
class executionZen extends execution
{
    /**
     * 处理版本列表展示数据。
     * Process build list display data.
     *
     * @param  array     $buildList
     * @param  string    $executionID
     * @access protected
     * @return object[]
     */
    protected function processBuildListData(array $buildList, int $executionID = 0): array
    {
        $this->loadModel('build');

        $productIdList = array();
        foreach($buildList as $build) $productIdList[$build->product] = $build->product;

        /* Get branch name. */
        $showBranch   = false;
        $branchGroups = $this->loadModel('branch')->getByProducts($productIdList);
        foreach($buildList as $build)
        {
            $build->branchName = '';
            if(isset($branchGroups[$build->product]))
            {
                $showBranch  = true;
                $branchPairs = $branchGroups[$build->product];
                foreach(explode(',', trim($build->branch, ',')) as $branchID)
                {
                    if(isset($branchPairs[$branchID])) $build->branchName .= "{$branchPairs[$branchID]},";
                }
                $build->branchName = trim($build->branchName, ',');
            }
            $build->actions = $this->build->buildActionList($build, $executionID, 'execution');
        }

        if(!$showBranch) unset($this->config->build->dtable->fieldList['branch']);
        unset($this->config->build->dtable->fieldList['execution']);

        return array_values($buildList);
    }

    /**
     * 构建产品下拉选择数据。
     * Build product drop-down select data.
     *
     * @param  int       $productID
     * @param  object[]  $products
     * @access protected
     * @return array
     */
    protected function buildProductSwitcher(int $productID, array $products)
    {
        $showBranch    = false;
        $productOption = array();
        $programIdList = array();
        if(count($products) > 1) $productOption[0] = $this->lang->product->all;
        foreach($products as $productData) $programIdList[$productData->program] = $productData->program;
        $programPairs = $this->loadModel('program')->getPairsByList($programIdList);
        $linePairs    = $this->loadModel('product')->getLinePairs($programIdList);

        foreach($products as $productData)
        {
            $programName = isset($programPairs[$productData->program]) ? $programPairs[$productData->program] . ' / ' : '';
            $lineName    = isset($linePairs[$productData->line]) ? $linePairs[$productData->line] . ' / ' : '';
            $productOption[$productData->id] = $programName . $lineName . $productData->name;
        }

        $product = $this->product->getById((int)$productID);
        if($product and $product->type != 'normal')
        {
            /* Display of branch label. */
            $showBranch = $this->loadModel('branch')->showBranch($productID);

            /* Display status of branch. */
            $branches = $this->branch->getList($productID, $executionID, 'all');
            foreach($branches as $branchInfo)
            {
                $branchOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
            }
        }
        return array($productOption, $branchOption, $showBranch);
    }
}

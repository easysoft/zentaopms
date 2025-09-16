<?php
declare(strict_types = 1);
class repoZenBuildStorySearchFormTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('repo');
        $this->objectTao   = $tester->loadTao('repo');
    }

    /**
     * Test buildStorySearchForm method.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $browseType
     * @param  int       $queryID
     * @param  array     $products
     * @param  array     $modules
     * @access public
     * @return mixed
     */
    public function buildStorySearchFormTest(int $repoID, string $revision, string $browseType, int $queryID, array $products, array $modules)
    {
        if(dao::isError()) return dao::getError();

        // 模拟需求状态列表，移除closed状态
        $storyStatusList = array(
            'draft' => '草稿',
            'active' => '激活',
            'changed' => '已变更'
        );

        // 构建搜索配置
        $searchConfig = array();
        $searchConfig['actionURL'] = helper::createLink('repo', 'linkStory', "repoID=$repoID&revision=$revision&browseType=bySearch&queryID=myQueryID");
        $searchConfig['queryID'] = $queryID;
        $searchConfig['style'] = 'simple';

        // 设置搜索参数
        $searchParams = array();
        $searchParams['plan']['values'] = $this->objectModel->loadModel('productplan')->getForProducts(array_keys($products));
        $searchParams['module']['values'] = $modules;
        $searchParams['status'] = array('operator' => '=', 'control' => 'select', 'values' => $storyStatusList);
        $searchParams['product']['values'] = helper::arrayColumn($products, 'name', 'id');

        // 获取产品分支信息
        $productBranches = $this->getLinkBranchesTest($products);

        // 根据分支情况配置搜索字段
        $searchFields = array('id', 'title', 'product', 'plan', 'module', 'status');
        if(empty($productBranches))
        {
            // 无分支时移除branch字段
            $branchRemoved = true;
        }
        else
        {
            $searchFields[] = 'branch';
            $searchParams['branch']['values'] = $productBranches;
            $branchRemoved = false;
        }

        // 加载search模块
        $this->objectModel->loadModel('search');

        return array(
            'result' => 'success',
            'actionURL' => $searchConfig['actionURL'],
            'queryID' => $searchConfig['queryID'],
            'style' => $searchConfig['style'],
            'planCount' => count($searchParams['plan']['values']),
            'moduleCount' => count($searchParams['module']['values']),
            'statusCount' => count($searchParams['status']['values']),
            'productCount' => count($searchParams['product']['values']),
            'branchCount' => count($productBranches),
            'branchRemoved' => $branchRemoved,
            'searchFields' => $searchFields
        );
    }

    /**
     * Test getLinkBranches method.
     *
     * @param  array $products
     * @access public
     * @return array
     */
    public function getLinkBranchesTest($products)
    {
        if(dao::isError()) return dao::getError();

        $productBranches = array();
        foreach($products as $product)
        {
            if(empty($product) || !is_object($product)) continue;

            if($product->type != 'normal')
            {
                $branches = $this->objectModel->loadModel('branch')->getPairs($product->id, 'noempty');
                foreach($branches as $branchID => $branchName)
                {
                    $branches[$branchID] = $product->name . ' / ' . $branchName;
                }

                $productBranches += $branches;
            }
        }

        return $productBranches;
    }
}
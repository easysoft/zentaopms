<?php
declare(strict_types=1);
/**
 * The zen file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
class testcaseZen extends testcase
{
    /**
     * Set browse cookie.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  string    $browseType
     * @param  int       $param
     * @access protected
     * @return void
     */
    protected function setBrowseCookie(int $productID, string $branch, string $browseType, int $param)
    {
        helper::setcookie('preProductID', (string)$productID);
        helper::setcookie('preBranch', $branch);

        if($this->cookie->preProductID != $productID || $this->cookie->preBranch != $branch)
        {
            $_COOKIE['caseModule'] = 0;
            helper::setcookie('caseModule', 0);
        }

        if($browseType == 'bymodule') helper::setcookie('caseModule', $param);
        if($browseType == 'bysuite')  helper::setcookie('caseSuite', $param);
    }

    /**
     * Set Browse session.
     *
     * @param  int $productID
     * @param  int $moduleID
     * @param  string $browseType
     * @param  string $orderBy
     * @access protected
     * @return void
     */
    protected function setBrowseSession(int $productID, int $moduleID, string $browseType, string $orderBy)
    {
        if($browseType != 'bymodule') $this->session->set('caseBrowseType', $browseType);

        $this->session->set('caseList', $this->app->getURI(true), $this->app->tab);
        $this->session->set('productID', $productID);
        $this->session->set('moduleID', $moduleID);
        $this->session->set('browseType', $browseType);
        $this->session->set('orderBy', $orderBy);
        $this->session->set('testcaseOrderBy', '`sort` asc', $this->app->tab);
        $this->session->set('testcaseOrderBy', '`sort` asc');
    }

    /**
     * 设置列表页面的导航。
     * Set menu in browse.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  string    $browseType
     * @param  int       $projectID
     * @access protected
     * @return void
     */
    protected function setBrowseMenu(int $productID, string $branch, string $browseType, int $projectID)
    {
        /* 在不同的应用中，设置不同的导航。 */
        /* Set menu, save session. */
        if($this->app->tab == 'project')
        {
            $linkedProducts = $this->product->getProducts($projectID, 'all', '', false);
            $this->products = array('0' => $this->lang->product->all) + $linkedProducts;

            $hasProduct = $this->dao->findById($projectID)->from(TABLE_PROJECT)->fetch('hasProduct');
            if(!$hasProduct) unset($this->config->testcase->search['fields']['product']);

            $branch = intval($branch) > 0 ? $branch : 'all';
            $this->loadModel('project')->setMenu($projectID);

            $this->view->products   = $this->products;
            $this->view->hasProduct = $hasProduct;
        }
        else
        {
            $this->qa->setMenu($this->products, $productID, $branch, $browseType);
        }
    }

    /**
     * 获取用力列表。
     * Get browse cases.
     *
     * @access protected
     * @return void
     */
    /**
     * 获取用力列表。
     * Get browse cases.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  string    $browseType
     * @param  int       $queryID
     * @param  int       $moduleID
     * @param  string    $caseType
     * @param  string    $orderBy
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function getCases(int $productID, string $branch, string $browseType, int $queryID, int $moduleID, string $caseType, string $orderBy, object $pager): array
    {
        $cases          = array();
        $caseIdList     = array();
        $queryCondition = '';

        /* 仅场景的时候获取用例列表。*/
        /* Get test cases when the browseType is not onlyscene. */
        if($browseType != 'onlyscene')
        {
            $sort           = common::appendOrder($orderBy);
            $cases          = $this->testcase->getTestCases($productID, $branch, $browseType, $queryID, $moduleID, $caseType, $sort, null);
            $queryCondition = $this->dao->get();
            $caseIdList     = array_column($cases, 'id');
        }

        $productParam = $productID;
        if(intval($productID) <= 0)
        {
            $productParam = array_keys($this->products);
            if(count($productParam) > 1) unset($productParam[0]);
        }

        /* 获取顶级的场景和案例。*/
        /* Get top level cases and scenes.*/
        $topObjects = array();
        if(!$this->cookie->onlyAutoCase)
        {
            $topObjects = $this->testcase->getList($productParam, $branch, $moduleID, $caseIdList, $pager, 'top', array(), $browseType);
            if(empty($topObjects) && $pageID > 1)
            {
                $pager      = pager::init(0, $recPerPage, 1);
                $topObjects = $this->testcase->getList($productParam, $branch, $moduleID, $caseIdList, $pager, 'top', array(), $browseType);
            }
        }

        /* 获取用例和场景列表。*/
        /* Get children cases and scenes.*/
        /* Process case for check story changed. */
        $scenes = $this->testcase->getList($productParam, $branch, $moduleID, $caseIdList, null, 'child', array_keys($topObjects), $browseType, $queryCondition);
        $scenes = $this->loadModel('story')->checkNeedConfirm($scenes);
        $scenes = $this->testcase->appendData($scenes);

        /* 保存查询的 session。*/
        /* save session. */
        $this->loadModel('common')->saveQueryCondition($queryCondition, 'testcase', false);

        return array($cases, $scenes);
    }

    /**
     * 构建搜索表单。
     * Build the search form.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $queryID
     * @param  int       $projectID
     * @access protected
     * @return void
     */
    protected function buildBrowseSearchForm(int $productID, string $branch, int $queryID, int $projectID): void
    {
        $this->config->testcase->search['onMenuBar'] = 'yes';

        $currentModule  = $this->app->tab == 'project' ? 'project'  : 'testcase';
        $currentMethod  = $this->app->tab == 'project' ? 'testcase' : 'browse';
        $projectParam   = $this->app->tab == 'project' ? "projectID={$this->session->project}&" : '';
        $actionURL      = $this->createLink($currentModule, $currentMethod, $projectParam . "productID=$productID&branch=$branch&browseType=bySearch&queryID=myQueryID");
        $searchProducts = $this->product->getPairs('', 0, '', 'all');

        $this->testcase->buildSearchForm($productID, $searchProducts, $queryID, $actionURL, $projectID);
    }

    /**
     *
     * 指定模块树。
     * Assign module tree in browse page.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $projectID
     * @access protected
     * @return void
     */
    protected function assignModuleTree(int $productID, string $branch, int $projectID): void
    {
        if($projectID && empty($productID))
        {
            $this->view->moduleTree = $this->tree->getCaseTreeMenu($projectID, $productID, 0, array('treeModel', 'createCaseLink'));
        }
        else
        {
            $this->view->moduleTree = $this->tree->getTreeMenu($productID, 'case', 0, array('treeModel', 'createCaseLink'), array('projectID' => $projectID, 'productID' => $productID), $branch);
        }
    }

    /**
     *
     * 指定产品和分支。
     * Assign product and branch.
     *
     * @param  int       $productID
     * @param  int       $projectID
     * @access protected
     * @return void
     */
    protected function assignProductAndBranch(int $productID, int $projectID): void
    {
        /* 根据产品类型判断是否展示分支，获取分支选项信息和带标签的分支选项信息。*/
        /* Judge whether to show branch according to the type of product, get branch option and branch tag option. */
        $product = $this->product->getByID($productID);

        $showBranch      = false;
        $branchOption    = array();
        $branchTagOption = array();
        if($product && $product->type != 'normal')
        {
            /* Display of branch label. */
            $showBranch = $this->loadModel('branch')->showBranch($productID);

            /* Display status of branch. */
            $branches = $this->loadModel('branch')->getList($productID, $projectID, 'all');
            foreach($branches as $branchInfo)
            {
                $branchOption[$branchInfo->id]    = $branchInfo->name;
                $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
            }
        }

        $this->view->product         = $product;
        $this->view->branchOption    = $branchOption;
        $this->view->branchTagOption = $branchTagOption;
    }

    /**
     * Assign testcase related variables.
     *
     * @param  object    $case
     * @param  string    $from
     * @param  int       $taskID
     * @access protected
     * @return void
     */
    protected function assignCaseForView(object $case, string $from, int $taskID)
    {
        $case = $this->loadModel('story')->checkNeedConfirm($case);
        if($from == 'testtask')
        {
            $run = $this->loadModel('testtask')->getRunByCase($taskID, $case->id);
            $case->assignedTo    = $run->assignedTo;
            $case->lastRunner    = $run->lastRunner;
            $case->lastRunDate   = $run->lastRunDate;
            $case->lastRunResult = $run->lastRunResult;
            $case->caseStatus    = $case->status;
            $case->status        = $run->status;

            $results = $this->testtask->getResults($run->id);
            $result  = array_shift($results);
            if($result)
            {
                $case->xml      = $result->xml;
                $case->duration = $result->duration;
            }
        }
        $case = $this->testcase->appendCaseFails($case, $from, $taskID);
        $case = $this->processStepsForMindMap($case);

        $this->view->runID      = $from == 'testcase' ? 0 : $run->id;
        $this->view->case       = $case;
        $this->view->caseFails  = $case->caseFails;
        $this->view->modulePath = $this->tree->getParents($case->module);
        $this->view->caseModule = empty($case->module) ? '' : $this->tree->getById($case->module);
    }

    /**
     * 创建测试用例前检验表单数据是否正确。
     * check from data for create case.
     *
     * @param  object    $case
     * @access protected
     * @return bool
     */
    protected function checkCreateFormData(object $case): bool
    {
        $steps   = $case->steps;
        $expects = $case->expects;
        foreach($expects as $key => $value)
        {
            if(!empty($value) and empty($steps[$key])) dao::$errors['message']["steps$key"] = sprintf($this->lang->testcase->stepsEmpty, $key);
        }
        if(dao::isError()) return false;

        $param = '';
        if(!empty($case->lib))     $param = "lib={$case->lib}";
        if(!empty($case->product)) $param = "product={$case->product}";

        $result = $this->loadModel('common')->removeDuplicate('case', $case, $param);
        if($result and $result['stop'])
        {
            return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->duplicate, $this->lang->testcase->common), 'locate' => $this->createLink('testcase', 'view', "caseID={$result['duplicate']}")));
        }
        return true;
    }

    /**
     * 处理xmind数据
     * Process scene data.
     *
     * @param  array     $result
     * @access protected
     * @return array
     */
    protected function processScene(array $result): array
    {
        $scenes['id']   = $result['id'];
        $scenes['text'] = $result['title'];
        $scenes['type'] = 'root';
        if(!empty($result['children'])) $scenes['children'] = $this->processChildScene($result['children']['attached'], $result['id'], 'sub');
        return $scenes;
    }

    /**
     * 处理xmind的节点数据
     * process scene child data.
     *
     * @param  array     $results
     * @param  string    $parent
     * @param  string    $type
     * @access protected
     * @return void
     */
    protected function processChildScene(array $results, string $parent, string $type)
    {
        $scenes = array();
        foreach($results as $result)
        {
            $scene['id']     = $result['id'];
            $scene['text']   = $result['title'];
            $scene['parent'] = $parent;
            $scene['type']   = $type;
            if(!empty($result['children'])) $scene['children'] = $this->processChildScene($result['children']['attached'], $result['id'], 'node');
            $scenes[] = $scene;
        }
        return $scenes;
    }

    /**
     * 为展示脑图计算步骤数据。
     * Process steps for mindmap.
     *
     * @param  object    $case
     * @access protected
     * @return object
     */
    protected function processStepsForMindMap(object $case): object
    {
        $mindMapSteps = array();
        $mindMapSteps['id']      = $case->id;
        $mindMapSteps['text']    = $case->title;
        $mindMapSteps['type']    = 'root';
            $stepItem['subSide'] = 'right';

        $reverseSteps = array_reverse($case->steps);

        $parentSteps = array();
        foreach($reverseSteps as $step)
        {
            $stepItem = array();
            $stepItem['id']      = $step->id;
            $stepItem['text']    = $step->step;
            $stepItem['type']    = $step->grade == 1 ? 'sub' : 'node';
            $stepItem['parent']  = $step->parent > 0 ? $step->parent : $case->id;
            $stepItem['subSide'] = 'right';
            if(isset($parentSteps[$step->id])) $stepItem['children'] = array_reverse($parentSteps[$step->id]);

            if($step->parent > 0)
            {
                $parentSteps[$step->parent][] = $stepItem;
            }
            else
            {
                $stepList[] = $stepItem;
            }
        }
        $mindMapSteps['children'] = array_reverse($stepList);
        $case->mindMapSteps = $mindMapSteps;
        return $case;
    }
}


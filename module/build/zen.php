<?php
declare(strict_types=1);
/**
 * The zen file of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     build
 * @link        https://www.zentao.net
 */
class buildZen extends build
{
    /**
     * 生成创建版本的页面数据。
     * Generate the page data for creating a plan.
     *
     * @param  int       $productID
     * @param  int       $executionID
     * @param  int       $projectID
     * @param  string    $status
     * @access protected
     * @return void
     */
    protected function assignCreateData(int $productID, int $executionID, int $projectID, string $status)
    {
        $productGroups = $branchGroups = array();
        $executions    = $this->loadModel('execution')->getPairs($projectID, 'all', 'stagefilter|leaf|order_asc');
        $executionID   = empty($executionID) && !empty($executions) ? (int)key($executions) : $executionID;
        if($executionID || $projectID)
        {
            $productGroups = $this->loadModel('product')->getProducts($executionID ? $executionID : $projectID, $status);
            $branchGroups  = $this->loadModel('project')->getBranchesByProject($executionID ? $executionID : $projectID);
        }

        $this->commonActions($projectID);
        $productID = $productID ? $productID : key($productGroups);
        $branches  = $products = array();

        /* Set branches and products. */
        if(!empty($productGroups[$productID]) && $productGroups[$productID]->type != 'normal' && !empty($branchGroups[$productID]))
        {
            $branchPairs = $this->loadModel('branch')->getPairs($productID, 'active');
            foreach($branchGroups[$productID] as $branchID => $branch)
            {
                if(isset($branchPairs[$branchID])) $branches[$branchID] = $branchPairs[$branchID];
            }
        }

        $artifactRepos = array();
        if(!$this->view->hidden && $productGroups) $this->loadModel('artifactrepo');
        foreach($productGroups as $product)
        {
            $products[$product->id] = $product->name;
            if(!$this->view->hidden) $artifactRepos[$product->id] = $this->artifactrepo->getReposByProduct($product->id);
        }

        $this->view->title         = $this->lang->build->create;
        $this->view->users         = $this->loadModel('user')->getPairs('nodeleted|noclosed');
        $this->view->product       = isset($productGroups[$productID]) ? $productGroups[$productID] : '';
        $this->view->branches      = $branches;
        $this->view->products      = $products;
        $this->view->executionID   = $executionID;
        $this->view->executions    = $executions;
        $this->view->lastBuild     = $this->build->getLast($executionID, $projectID);
        $this->view->artifactRepos = $artifactRepos;
        $this->display();
    }

    /**
     * 生成编辑版本的页面数据。
     * Generate the page data for editing a plan.
     *
     * @param  object    $build
     * @access protected
     * @return void
     */
    protected function assignEditData(object $build)
    {
        $builds        = array();
        $status        = empty($this->config->CRProduct) ? 'noclosed' : '';
        $projectID     = $build->execution ? (int)$build->execution : (int)$build->project;
        $productGroups = $this->loadModel('product')->getProducts($projectID, $status);
        $branches      = $this->loadModel('branch')->getList($build->product, $projectID, 'all');
        if(!$build->execution) $builds = $this->build->getBuildPairs(array($build->product), 'all', 'noempty,notrunk,singled,separate', $build->project, 'project', $build->builds, false);

        /* Get execution info. */
        $executions = $this->product->getExecutionPairsByProduct($build->product, $build->branch, (int)$this->session->project, 'stagefilter');
        $execution  = $build->execution ? $this->loadModel('execution')->getByID((int)$build->execution) : '';
        if($build->execution && !isset($executions[$build->execution]))
        {
            $execution = $this->loadModel('execution')->getByID($build->execution);
            $executions[$build->execution] = $execution ? $execution->name : '';
        }

        if($build->product && !isset($productGroups[$build->product]))
        {
            $product = $this->product->getById($build->product);
            $product->branch = $build->branch;
            $productGroups[$build->product] = $product;
        }

        /* Display status of branch. */
        $branchTagOption = array();
        foreach($branches as $branchInfo)
        {
            $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
        }
        foreach(explode(',', $build->branch) as $buildBranch)
        {
            if(!isset($branchTagOption[$buildBranch])) $branchTagOption[$buildBranch] = $this->branch->getById($buildBranch, 0, 'name');
        }

        $products = array();
        foreach($productGroups as $product) $products[$product->id] = $product->name;

        $this->view->title           = $build->name . $this->lang->colon . $this->lang->build->edit;
        $this->view->products        = $products;
        $this->view->product         = isset($productGroups[$build->product]) ? $productGroups[$build->product] : '';
        $this->view->users           = $this->loadModel('user')->getPairs('noletter', $build->builder);
        $this->view->branchTagOption = $branchTagOption;
        $this->view->build           = $build;
        $this->view->testtask        = $this->loadModel('testtask')->getByBuild($build->id);
        $this->view->builds          = $builds;
        $this->view->executions      = $executions;
        $this->view->executionType   = !empty($execution) && $execution->type == 'stage' ? 1 : 0;
        $this->view->orderBy         = 'status_asc, stage_asc, id_desc';
        $this->display();
    }

    /**
     * 生成的版本详情页面的产品数据。
     * Generate the product data for the build view page.
     *
     * @param  object    $build
     * @param  string    $type
     * @param  string    $sort
     * @param  object    $storyPager
     * @access protected
     * @return void
     */
    protected function assignProductVarsForView(object $build, string $type, string $sort, object $storyPager): void
    {
        $this->loadModel('branch');

        $product = $this->loadModel('product')->getByID($build->product);
        if($product && $product->type != 'normal') $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);

        $stories = $this->build->getStoryList($build->allStories, (int)$build->branch, $type == 'story' ? $sort : '', $storyPager);

        $branchName = '';
        if($build->productType != 'normal')
        {
            foreach(explode(',', $build->branch) as $buildBranch)
            {
                $branchName .= $buildBranch == BRANCH_MAIN ?$this->lang->branch->main : $this->loadModel('branch')->getById($buildBranch);
                $branchName .= ',';
            }
            $branchName = trim($branchName, ',');
        }

        $this->view->branchName = empty($branchName) ? $this->lang->branch->main : $branchName;
        $this->view->stories    = $stories;
        $this->view->storyPager = $storyPager;

        if($this->app->getViewType() == 'json') unset($this->view->storyPager);
    }

    /**
     * 生成的版本详情页面的Bug数据。
     * Generate the Bug data for the build view page.
     *
     * @param  object    $build
     * @param  string    $type
     * @param  string    $sort
     * @param  string    $param
     * @param  object    $bugPager
     * @param  object    $generatedBugPager
     * @access protected
     * @return void
     */
    protected function assignBugVarsForView(object $build, string $type, string $sort, string $param, object $bugPager, object $generatedBugPager): void
    {
        $this->view->type              = $type;
        $this->view->param             = $param;
        $this->view->bugPager          = $bugPager;
        $this->view->generatedBugPager = $generatedBugPager;
        $this->view->bugs              = $this->build->getBugList($build->allBugs, $type == 'bug' ? $sort : '', $bugPager);
        $this->view->generatedBugs     = $this->loadModel('bug')->getExecutionBugs((int)$build->execution, $build->product, 'all', "$build->id,{$build->builds}", $type, (int)$param, $type == 'generatedBug' ? $sort : 'status_desc,id_desc', '', $generatedBugPager);

        if($this->app->getViewType() == 'json')
        {
            unset($this->view->generatedBugPager);
            unset($this->view->bugPager);
        }
    }

    /**
     * 设置版本详情页面的导航。
     * Set the navigation for the build view page.
     *
     * @param  object    $build
     * @access protected
     * @return void
     */
    protected function setMenuForView(object $build): void
    {
        $this->session->set('storyList', $this->app->getURI(true), $this->app->tab);

        $this->session->project = $build->project;

        $objectType = 'execution';
        $objectID   = $build->execution;
        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($build->project);
            $objectType = 'project';
            $objectID   = $build->project;
        }
        elseif($this->app->tab == 'execution')
        {
            $this->loadModel('execution')->setMenu((int)$build->execution);
        }

        $executions = $this->loadModel('execution')->getPairs($this->session->project, 'all', 'empty');

        $this->view->title      = "BUILD #$build->id $build->name" . (isset($executions[$build->execution]) ? " - " . $executions[$build->execution] : '');
        $this->view->executions = $executions;
        $this->view->buildPairs = $this->build->getBuildPairs(0, 'all', 'noempty,notrunk', (int)$objectID, $objectType);
        $this->view->builds     = $this->build->getByList(array_keys($this->view->buildPairs));
        $this->view->objectID   = $objectID;
    }

    /**
     * 生成关联需求的搜索表单数据。
     * Generate the search form data for the build view page.
     *
     * @param  object    $build
     * @param  int       $queryID
     * @param  string    $productType
     * @access protected
     * @return void
     */
    protected function buildLinkStorySearchForm(object $build, int $queryID, string $productType)
    {
        unset($this->config->product->search['fields']['product']);
        unset($this->config->product->search['fields']['project']);

        $this->config->product->search['actionURL'] = $this->createLink($this->app->rawModule, 'view', "buildID={$build->id}&type=story&link=true&param=" . helper::safe64Encode("&browseType=bySearch&queryID=myQueryID"));
        $this->config->product->search['queryID']   = $queryID;
        $this->config->product->search['style']     = 'simple';
        $this->config->product->search['params']['plan']['values']   = $this->loadModel('productplan')->getPairs($build->product, $build->branch, '', true);
        $this->config->product->search['params']['module']['values'] = $this->loadModel('tree')->getOptionMenu($build->product, 'story', 0, $build->branch);
        $this->config->product->search['params']['status'] = array('operator' => '=', 'control' => 'select', 'values' => $this->lang->story->statusList);

        if($build->project)
        {
            $project = $this->loadModel('project')->getByID($build->project);
            if(!$project->hasProduct and $project->model != 'scrum')
            {
                unset($this->config->product->search['fields']['plan']);
            }
            elseif(!$project->hasProduct and !$project->multiple)
            {
                unset($this->config->product->search['fields']['plan']);
            }
        }

        if($productType == 'normal')
        {
            unset($this->config->product->search['fields']['branch']);
            unset($this->config->product->search['params']['branch']);
        }
        else
        {
            $branchPairs = $this->loadModel('branch')->getPairs($build->product, 'noempty');
            $branchAll   = sprintf($this->lang->build->branchAll, $this->lang->product->branchName[$productType]);
            $branches    = array('' => $branchAll) + array(BRANCH_MAIN => $this->lang->branch->main);
            if($build->branch)
            {
                foreach(explode(',', $build->branch) as $branchID)
                {
                    if($branchID == '0') continue;
                    $branches += array($branchID => $branchPairs[$branchID]);
                }
            }

            $this->config->product->search['fields']['branch']           = sprintf($this->lang->product->branch, $this->lang->product->branchName[$productType]);
            $this->config->product->search['params']['branch']['values'] = $branches;
        }
        $this->loadModel('search')->setSearchParams($this->config->product->search);
    }

    /**
     * 生成关联Bug的搜索表单数据。
     * Generate the search form data for the build view page.
     *
     * @param  object    $build
     * @param  int       $queryID
     * @param  string    $productType
     * @access protected
     * @return void
     */
    protected function buildLinkBugSearchForm(object $build, int $queryID, string $productType)
    {
        $this->loadModel('bug');
        $this->config->bug->search['actionURL'] = $this->createLink($this->app->rawModule, 'view', "buildID={$build->id}&type=bug&link=true&param=" . helper::safe64Encode("&browseType=bySearch&queryID=myQueryID"));
        $this->config->bug->search['queryID']   = $queryID;
        $this->config->bug->search['style']     = 'simple';

        $this->config->bug->search['params']['plan']['values']          = $this->loadModel('productplan')->getPairs($build->product, $build->branch, '', true);
        $this->config->bug->search['params']['module']['values']        = $this->loadModel('tree')->getOptionMenu($build->product, 'bug', 0, $build->branch);
        $this->config->bug->search['params']['execution']['values']     = $this->loadModel('product')->getExecutionPairsByProduct($build->product, $build->branch, (int)$this->session->project);
        $this->config->bug->search['params']['openedBuild']['values']   = $this->build->getBuildPairs(array($build->product), 'all', 'releasetag');
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->config->bug->search['params']['openedBuild']['values'];

        unset($this->config->bug->search['fields']['product']);
        unset($this->config->bug->search['params']['product']);
        unset($this->config->bug->search['fields']['project']);
        unset($this->config->bug->search['params']['project']);

        if($build->project)
        {
            $project = $this->loadModel('project')->getByID($build->project);
            if(!$project->hasProduct && $project->model != 'scrum')
            {
                unset($this->config->bug->search['fields']['plan']);
            }
            elseif(!$project->hasProduct && !$project->multiple)
            {
                unset($this->config->bug->search['fields']['plan']);
            }
        }

        if($productType == 'normal')
        {
            unset($this->config->bug->search['fields']['branch']);
            unset($this->config->bug->search['params']['branch']);
        }
        else
        {
            $buildBranch = array();
            $branchList  = $this->loadModel('branch')->getPairs($build->product, '', $build->execution);
            $branchAll   = sprintf($this->lang->build->branchAll, $this->lang->product->branchName[$productType]);
            $branches    = array('' => $branchAll, BRANCH_MAIN => $this->lang->branch->main);
            if(strpos($build->branch, ',') !== false) $buildBranch = explode(',', $build->branch);
            foreach($buildBranch as $buildKey) $branches += array($buildKey => zget($branchList, $buildKey));


            $this->config->bug->search['fields']['branch']           = sprintf($this->lang->product->branch, $this->lang->product->branchName[$productType]);
            $this->config->bug->search['params']['branch']['values'] = $branches;
        }
        $this->loadModel('search')->setSearchParams($this->config->bug->search);
    }
}

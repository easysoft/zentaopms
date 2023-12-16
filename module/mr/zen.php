<?php
declare(strict_types=1);
/**
 * The zen file of mr module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
class mrZen extends mr
{
    /**
     * 获取合并请求的代码库项目信息。
     * Get the code base project information of the merge request.
     *
     * @param  object    $repo
     * @param  array     $MRList
     * @access protected
     * @return array
     */
    protected function getAllProjects(object $repo, array $MRList): array
    {
        $projectIdList = array();
        if($repo->SCM == 'Gitlab')
        {
            foreach($MRList as $MR)
            {
                if($repo->id != $MR->repoID) continue;

                $projectIdList[$MR->sourceProject] = $MR->sourceProject;
                $projectIdList[$MR->targetProject] = $MR->targetProject;
            }
        }

        $methodName = 'get' . ucfirst($repo->SCM) . 'Projects';
        return $this->mr->{$methodName}((int)$repo->serviceHost, $projectIdList);
    }

    /**
     * 向编辑合并请求页面添加数据。
     * Add data to the edit merge request page.
     *
     * @param  object    $MR
     * @param  string    $scm
     * @access protected
     * @return void
     */
    protected function assignEditData(object $MR, string $scm): void
    {
        $MR->canDeleteBranch = true;
        if($scm == 'gitlab')
        {
            $MR->sourceProject = (int)$MR->sourceProject;
            $MR->targetProject = (int)$MR->targetProject;
        }
        $branchPrivs = $this->loadModel($scm)->apiGetBranchPrivs($MR->hostID, $MR->sourceProject);
        foreach($branchPrivs as $priv)
        {
            if($MR->canDeleteBranch && $priv->name == $MR->sourceBranch) $MR->canDeleteBranch = false;
        }

        $targetBranchList = array();
        $branchList       = $this->loadModel($scm)->getBranches($MR->hostID, $MR->targetProject);
        foreach($branchList as $branch) $targetBranchList[$branch] = $branch;

        $jobList = array();
        if($MR->repoID)
        {
            $rawJobList = $this->loadModel('job')->getListByRepoID($MR->repoID);
            foreach($rawJobList as $rawJob) $jobList[$rawJob->id] = "[$rawJob->id] $rawJob->name";

            $this->view->repo = $this->loadModel('repo')->getByID($MR->repoID);
        }

        $this->view->title            = $this->lang->mr->edit;
        $this->view->MR               = $MR;
        $this->view->users            = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->jobList          = $jobList;
        $this->view->targetBranchList = $targetBranchList;

        $this->display();
    }

    /**
     * 构造关联需求的搜索表单。
     * Build the search form of the associated story.
     *
     * @param  int       $MRID
     * @param  object    $product
     * @param  string    $orderBy
     * @param  int       $queryID
     * @access protected
     * @return void
     */
    protected function buildLinkStorySearchForm(int $MRID, object $product, string $orderBy, int $queryID = 0)
    {
        if(empty($this->product))     $this->loadModel('product');
        if(empty($this->lang->story)) $this->app->loadLang('story');

        $storyStatusList = $this->lang->story->statusList;
        unset($storyStatusList['closed']);

        $modules = $this->loadModel('tree')->getOptionMenu($product->id, 'story');
        unset($this->config->product->search['fields']['product']);
        $this->config->product->search['actionURL']                   = $this->createLink('mr', 'linkStory', "MRID={$MRID}&productID={$product->id}&browseType=bySearch&param=myQueryID&orderBy={$orderBy}");
        $this->config->product->search['queryID']                     = $queryID;
        $this->config->product->search['style']                       = 'simple';
        $this->config->product->search['params']['product']['values'] = array($product) + array('all' => $this->lang->product->allProductsOfProject);
        $this->config->product->search['params']['plan']['values']    = $this->loadModel('productplan')->getForProducts(array($product->id => $product->id));
        $this->config->product->search['params']['module']['values']  = $modules;
        $this->config->product->search['params']['status']            = array('operator' => '=', 'control' => 'select', 'values' => $storyStatusList);

        if($product->type == 'normal')
        {
            unset($this->config->product->search['fields']['branch']);
            unset($this->config->product->search['params']['branch']);
        }
        else
        {
            $this->product->setMenu($product->id, 0);
            $this->config->product->search['fields']['branch']           = $this->lang->product->branch;
            $this->config->product->search['params']['branch']['values'] = $this->loadModel('branch')->getPairs($product->id, 'noempty');
        }
        $this->loadModel('search')->setSearchParams($this->config->product->search);
    }

    /**
     * 构造关联bug的搜索表单。
     * Build the search form of the associated bug.
     *
     * @param  int       $MRID
     * @param  object    $product
     * @param  string    $orderBy
     * @param  int       $queryID
     * @access protected
     * @return void
     */
    protected function buildLinkBugSearchForm(int $MRID, object $product, string $orderBy, int $queryID = 0)
    {
        if(empty($this->product)) $this->loadModel('product');
        $modules = $this->loadModel('tree')->getOptionMenu($product->id, 'bug');

        $this->config->bug->search['actionURL']                         = $this->createLink('mr', 'linkBug', "MRID={$MRID}&productID={$product->id}&browseType=bySearch&param=myQueryID&orderBy={$orderBy}");
        $this->config->bug->search['queryID']                           = $queryID;
        $this->config->bug->search['style']                             = 'simple';
        $this->config->bug->search['params']['plan']['values']          = $this->loadModel('productplan')->getForProducts(array($product->id => $product->id));
        $this->config->bug->search['params']['module']['values']        = $modules;
        $this->config->bug->search['params']['execution']['values']     = $this->product->getExecutionPairsByProduct($product->id);
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getBuildPairs($product->id, 'all', 'releasetag');
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->config->bug->search['params']['openedBuild']['values'];

        unset($this->config->bug->search['fields']['product']);
        if($product->type == 'normal')
        {
            unset($this->config->bug->search['fields']['branch']);
            unset($this->config->bug->search['params']['branch']);
        }
        else
        {
            $this->product->setMenu($product->id, 0);
            $this->config->bug->search['fields']['branch']           = $this->lang->product->branch;
            $this->config->bug->search['params']['branch']['values'] = $this->loadModel('branch')->getPairs($product->id, 'noempty');
        }
        $this->loadModel('search')->setSearchParams($this->config->bug->search);
    }

    /**
     * 构造关联任务的搜索表单。
     * Build the search form of the associated task.
     *
     * @param  int       $MRID
     * @param  object    $product
     * @param  string    $orderBy
     * @param  int       $queryID
     * @param  array     $productExecutions
     * @access protected
     * @return void
     */
    protected function buildLinkTaskSearchForm(int $MRID, object $product, string $orderBy, int $queryID, array $productExecutions)
    {
        $modules = $this->loadModel('tree')->getOptionMenu($product->id, 'task');

        $this->config->execution->search['actionURL']                     = $this->createLink('mr', 'linkTask', "MRID={$MRID}&product->id={$product->id}&browseType=bySearch&param=myQueryID&orderBy={$orderBy}");
        $this->config->execution->search['queryID']                       = $queryID;
        $this->config->execution->search['params']['module']['values']    = $modules;
        $this->config->execution->search['params']['execution']['values'] = array_filter($productExecutions);
        $this->loadModel('search')->setSearchParams($this->config->execution->search);
    }

    /**
     * 处理关联任务页面分页数据。
     * Process the pagination data of the associated task page.
     *
     * @param  int       $recTotal
     * @param  int       $recPerPage
     * @param  int       $pageID
     * @param  array     $allTasks
     * @access protected
     * @return void
     */
    protected function processLinkTaskPager(int $recTotal, int $recPerPage, int $pageID, array $allTasks)
    {
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $pager->setRecTotal(count($allTasks));
        $pager->setPageTotal();
        if($pager->pageID > $pager->pageTotal) $pager->setPageID($pager->pageTotal);
        $count    = 1;
        $limitMin = ($pager->pageID - 1) * $pager->recPerPage;
        $limitMax = $pager->pageID * $pager->recPerPage;
        foreach($allTasks as $key => $task)
        {
            if($count <= $limitMin || $count > $limitMax) unset($allTasks[$key]);

            $count ++;
        }

        $this->view->allTasks = $allTasks;
        $this->view->pager    = $pager;
    }

    /**
     * 检查是否有权限编辑项目。
     * Check if you have permission to edit the project.
     *
     * @param  string    $hostType
     * @param  object    $sourceProject
     * @param  object    $MR
     * @access protected
     * @return bool
     */
    protected function checkProjectEdit(string $hostType, object $sourceProject, object $MR): bool
    {
        if($hostType == 'gitlab')
        {
            $groupIDList = array(0 => 0);
            $groups      = $this->loadModel('gitlab')->apiGetGroups($MR->hostID, 'name_asc', 'developer');
            foreach($groups as $group) $groupIDList[] = $group->id;

            $isDeveloper = $this->gitlab->checkUserAccess($MR->hostID, 0, $sourceProject, $groupIDList, 'developer');
            $gitUsers    = $this->loadModel('pipeline')->getUserBindedPairs($MR->hostID, 'gitlab');
            if(isset($gitUsers[$this->app->user->account]) && $isDeveloper) return true;
        }
        elseif($hostType == 'gitea')
        {
            return (isset($sourceProject->allow_merge_commits) && $sourceProject->allow_merge_commits == true);
        }
        elseif($hostType == 'gogs')
        {
            return (isset($sourceProject->permissions->push) && $sourceProject->permissions->push);
        }

        return false;
    }
}

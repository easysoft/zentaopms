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
     * 展示看板的相关变量。
     * Show the variables associated with the kanban.
     *
     * @param  int       $executionID
     * @access protected
     * @return void
     */
    protected function assignKanbanVars(int $executionID)
    {
        /* Get user list. */
        $userList    = array();
        $users       = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $avatarPairs = $this->user->getAvatarPairs('all');
        foreach($avatarPairs as $account => $avatar)
        {
            if(!isset($users[$account])) continue;
            $userList[$account]['realname'] = $users[$account];
            $userList[$account]['avatar']   = $avatar;
        }
        $userList['closed']['account']  = 'Closed';
        $userList['closed']['realname'] = 'Closed';
        $userList['closed']['avatar']   = '';

        /* Get execution linked products. */
        $productID    = 0;
        $branchID     = 0;
        $products     = $this->loadModel('product')->getProducts($executionID);
        $productNames = array();
        if($products)
        {
            $productID = key($products);
            $branches  = $this->loadModel('branch')->getPairs($productID, '', $executionID);
            if($branches) $branchID = key($branches);
        }
        foreach($products as $product) $productNames[$product->id] = $product->name;

        /* Get execution linked plans. */
        $plans    = $this->execution->getPlans($products, 'skipParent', $executionID);
        $allPlans = array();
        if(!empty($plans))
        {
            foreach($plans as $plan) $allPlans += $plan;
        }

        $this->view->users        = $users;
        $this->view->userList     = $userList;
        $this->view->productID    = $productID;
        $this->view->branchID     = $branchID;
        $this->view->productNames = $productNames;
        $this->view->productNum   = count($products);
        $this->view->allPlans     = $allPlans;
    }

    /**
     * 展示维护产品相关变量。
     * Show the manage products related variables.
     *
     * @param  object    $execution
     * @access protected
     * @return void
     */
    protected function assignManageProductsVars(object $execution)
    {
        $branches            = $this->project->getBranchesByProject($execution->id);
        $linkedProductIdList = empty($branches) ? array() : array_keys($branches);
        $allProducts         = $this->loadModel('product')->getProductPairsByProject($execution->project, 'all', implode(',', $linkedProductIdList));
        $linkedProducts      = $this->product->getProducts($execution->id, 'all', '', true, $linkedProductIdList);
        $linkedBranches      = array();
        $executionStories    = $this->project->getStoriesByProject($execution->id);

        /* If the story of the product which linked the execution, you don't allow to remove the product. */
        $unmodifiableProducts = array();
        $unmodifiableBranches = array();
        $linkedStoryIDList    = array();
        $linkedBranchIdList   = array();
        foreach($linkedProducts as $productID => $linkedProduct)
        {
            $linkedBranches[$productID] = array();
            if(!isset($allProducts[$productID])) $allProducts[$productID] = $linkedProduct->name;
            foreach($branches[$productID] as $branchID => $branch)
            {
                $linkedBranches[$productID][$branchID] = $branchID;
                $linkedBranchIdList[$branchID] = $branchID;
                if(!empty($executionStories[$productID][$branchID]))
                {
                    array_push($unmodifiableProducts, $productID);
                    array_push($unmodifiableBranches, $branchID);
                    $linkedStoryIDList[$productID][$branchID] = $executionStories[$productID][$branchID]->storyIDList;
                }
            }
        }

        $this->view->title                = $this->lang->execution->manageProducts . $this->lang->colon . $execution->name;
        $this->view->execution            = $execution;
        $this->view->linkedProducts       = $linkedProducts;
        $this->view->unmodifiableProducts = $unmodifiableProducts;
        $this->view->unmodifiableBranches = $unmodifiableBranches;
        $this->view->linkedBranches       = $linkedBranches;
        $this->view->linkedStoryIDList    = $linkedStoryIDList;
        $this->view->allProducts          = $allProducts;
        $this->view->branchGroups         = $this->execution->getBranchByProduct(array_keys($allProducts), $execution->project, 'ignoreNormal|noclosed', $linkedBranchIdList);
        $this->view->allBranches          = $this->execution->getBranchByProduct(array_keys($allProducts), $execution->project, 'ignoreNormal');

        $this->display();
    }

    /**
     * 展示任务看板的相关变量。
     * Show the task Kanban related variables.
     *
     * @param  object    $execution
     * @access protected
     * @return void
     */
    protected function assignTaskKanbanVars(object $execution)
    {
        /* Get user list. */
        $userList    = array();
        $users       = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $avatarPairs = $this->user->getAvatarPairs('all');
        foreach($avatarPairs as $account => $avatar)
        {
            if(!isset($users[$account])) continue;
            $userList[$account]['realname'] = $users[$account];
            $userList[$account]['avatar']   = $avatar;
        }
        $userList['closed']['account']  = 'Closed';
        $userList['closed']['realname'] = 'Closed';
        $userList['closed']['avatar']   = '';

        /* Get execution linked products. */
        $productID    = 0;
        $productNames = array();
        $products     = $this->loadModel('product')->getProducts($execution->id);
        if($products) $productID = key($products);
        foreach($products as $product) $productNames[$product->id] = $product->name;

        $plans    = $this->execution->getPlans($products);
        $allPlans = array();
        if(!empty($plans))
        {
            foreach($plans as $plan) $allPlans += $plan;
        }

        $project = $this->project->getByID($execution->project);

        $this->view->title        = $this->lang->execution->kanban;
        $this->view->userList     = $userList;
        $this->view->realnames    = $users;
        $this->view->productID    = $productID;
        $this->view->productNames = $productNames;
        $this->view->productNum   = count($products);
        $this->view->allPlans     = $allPlans;
        $this->view->hiddenPlan   = $project->model !== 'scrum';
        $this->view->execution    = $execution;
        $this->view->canBeChanged = common::canModify('execution', $execution);
    }

    /**
     * 检查累计流图的日期。
     * Check Cumulative flow diagram date.
     *
     * @param  string    $begin
     * @param  string    $end
     * @param  string    $minDate
     * @param  string    $maxDate
     * @access protected
     * @return bool
     */
    protected function checkCFDDate(string $begin, string $end, string $minDate, string $maxDate): bool
    {
        $dateError = array();
        if(empty($begin)) $dateError[] = sprintf($this->lang->error->notempty, $this->lang->execution->charts->cfd->begin);
        if(empty($end)) $dateError[] = sprintf($this->lang->error->notempty, $this->lang->execution->charts->cfd->end);
        if(empty($dateError))
        {
            if($begin < $minDate) $dateError[] = sprintf($this->lang->error->gt, $this->lang->execution->charts->cfd->begin, $minDate);
            if($begin > $maxDate) $dateError[] = sprintf($this->lang->error->lt, $this->lang->execution->charts->cfd->begin, $maxDate);
            if($end < $minDate)   $dateError[] = sprintf($this->lang->error->gt, $this->lang->execution->charts->cfd->end, $minDate);
            if($end > $maxDate)   $dateError[] = sprintf($this->lang->error->lt, $this->lang->execution->charts->cfd->end, $maxDate);
        }

        foreach($dateError as $index => $error)
        {
            dao::$errors = str_replace(array('。', '.'), array('', ''), $error);
            return false;
        }

        if($begin >= $end)
        {
            dao::$errors = $this->lang->execution->charts->cfd->errorBegin;
            return false;
        }

        if(date("Y-m-d", strtotime("-3 months", strtotime($end))) > $begin)
        {
            dao::$errors = $this->lang->execution->charts->cfd->errorDateRange;
            return false;
        }
        return true;
    }

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
        $builds       = array();
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

            if($build->scmPath && $build->filePath)
            {
                $build->rowspan = 2;

                $buildInfo = clone $build;
                $buildInfo->pathType = 'scmPath';
                $buildInfo->path     = $build->scmPath;
                $builds[]  = $buildInfo;

                $buildInfo = clone $build;
                $buildInfo->pathType = 'filePath';
                $buildInfo->path     = $build->filePath;
                $builds[]  = $buildInfo;
            }
            else
            {
                $build->pathType = empty($build->scmPath) ? 'filePath' : 'scmPath';
                $build->path     = empty($build->scmPath) ? $build->filePath : $build->scmPath;

                $builds[] = $build;
            }
        }

        if(!$showBranch) unset($this->config->build->dtable->fieldList['branch']);
        unset($this->config->build->dtable->fieldList['execution']);

        return $builds;
    }

    /**
     * 构建产品下拉选择数据。
     * Build product drop-down select data.
     *
     * @param  int       $executionID
     * @param  int       $productID
     * @param  object[]  $products
     * @access protected
     * @return array
     */
    protected function buildProductSwitcher(int $executionID, int $productID, array $products)
    {
        $productOption = array();
        $branchOption  = array();
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
            /* Display status of branch. */
            $branches = $this->branch->getList($productID, $executionID, 'all');
            foreach($branches as $branchInfo)
            {
                $branchOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
            }
        }
        return array($productOption, $branchOption);
    }

    /**
     * 构建执行团队成员信息。
     * Build execution team member information.
     *
     * @param  array  $currentMembers
     * @param  array  $members2Import
     * @param  array  $deptUsers
     * @param  int    $days
     * @access public
     * @return array
     */
    public function buildMembers(array $currentMembers, array $members2Import, array $deptUsers, int $days): array
    {
        $teamMembers = array();
        foreach($currentMembers as $account => $member)
        {
            $member->memberType = 'default';
            $teamMembers[$account] = $member;
        }

        foreach($members2Import as $account => $member2Import)
        {
            $member2Import->memberType = 'import';
            $member2Import->days       = $days;
            $member2Import->limited    = 'no';
            $teamMembers[$account] = $member2Import;
        }

        $roles = $this->loadModel('user')->getUserRoles(array_keys($deptUsers));
        foreach($deptUsers as $deptAccount => $userName)
        {
            if(isset($currentMembers[$deptAccount]) || isset($members2Import[$deptAccount])) continue;

            $deptMember = new stdclass();
            $deptMember->memberType = 'dept';
            $deptMember->account    = $deptAccount;
            $deptMember->role       = zget($roles, $deptAccount, '');
            $deptMember->days       = $days;
            $deptMember->hours      = $this->config->execution->defaultWorkhours;
            $deptMember->limited    = 'no';

            $teamMembers[$deptAccount] = $deptMember;
        }

        for($j = 0; $j < 5; $j ++)
        {
            $newMember = new stdclass();
            $newMember->memberType = 'add';
            $newMember->account    = '';
            $newMember->role       = '';
            $newMember->days       = $days;
            $newMember->hours      = $this->config->execution->defaultWorkhours;
            $newMember->limited    = 'no';

            $teamMembers[] = $newMember;
        }

        return $teamMembers;
    }

    /**
     * 构造待更新的团队成员数据。
     * Construct the team member data to be updated.
     *
     * @param  object    $execution
     * @access protected
     * @return array
     */
    protected function buildMembersForManageMembers(object $execution)
    {
        $members = form::batchData()->get();

        foreach($members as $rowIndex => $member)
        {
            $member->root = $execution->id;
            if(!empty($execution->days) and $member->days > $execution->days)
            {
                dao::$errors["days[$rowIndex]"] = sprintf($this->lang->execution->daysGreaterProject, $execution->days);
                return false;
            }
            if($member->hours > 24)
            {
                dao::$errors["hours[$rowIndex]"] = $this->lang->execution->errorHours;
                return false;
            }
        }
        return $members;
    }

    /**
     * 设置最近五次执行。
     * Set the recent five executions.
     *
     * @param  int $executionID
     * @access protected
     * @return void
     */
    protected function setRecentExecutions(int $executionID)
    {
        if($this->session->multiple)
        {
            $recentExecutions = isset($this->config->execution->recentExecutions) ? explode(',', $this->config->execution->recentExecutions) : array();
            array_unshift($recentExecutions, $executionID);
            $recentExecutions = array_slice(array_unique($recentExecutions), 0, 5);
            $recentExecutions = implode(',', $recentExecutions);

            $this->loadModel('setting');
            if(empty($this->config->execution->recentExecutions) || $this->config->execution->recentExecutions != $recentExecutions) $this->setting->updateItem($this->app->user->account . 'common.execution.recentExecutions', $recentExecutions);
            if(empty($this->config->execution->lastExecution)    || $this->config->execution->lastExecution != $executionID)         $this->setting->updateItem($this->app->user->account . 'common.execution.lastExecution', $executionID);
        }
    }

    /**
     * 设置任务页面的Cookie和Session。
     * Set task page storage.
     *
     * @access protected
     * @return void
     */
    protected function setTaskPageStorage(int $executionID, string $orderBy, string $browseType, int $param = 0)
    {
        helper::setcookie('preExecutionID', (string)$executionID);
        helper::setcookie('executionTaskOrder', $orderBy);
        if($this->cookie->preExecutionID != $executionID)
        {
            helper::setcookie('moduleBrowseParam',  '0');
            helper::setcookie('productBrowseParam', '0');
        }
        if($browseType == 'bymodule')
        {
            helper::setcookie('moduleBrowseParam',  (string)$param);
            helper::setcookie('productBrowseParam', '0');
        }
        elseif($browseType == 'byproduct')
        {
            helper::setcookie('moduleBrowseParam',  '0');
            helper::setcookie('productBrowseParam', (string)$param);
        }
        else
        {
            $this->session->set('taskBrowseType', $browseType);
        }

        if($browseType == 'bymodule' && $this->session->taskBrowseType == 'bysearch') $this->session->set('taskBrowseType', 'unclosed');
    }

    /**
     * 构建执行看板的数据。
     * Build the data to execution Kanban.
     *
     * @param  array     $projectIdList
     * @param  array     $executions
     * @access protected
     * @return void
     */
    protected function buildExecutionKanbanData(array $projectIdList, array $executions)
    {
        $projectCount = 0;
        $statusCount  = array();
        $myExecutions = array();
        $kanbanGroup  = array();
        $teams        = $this->execution->getMembersByIdList(explode(',', $this->app->user->view->sprints));
        foreach($projectIdList as $projectID)
        {
            foreach(array_keys($this->lang->execution->statusList) as $status)
            {
                if(!isset($statusCount[$status])) $statusCount[$status] = 0;

                foreach($executions as $execution)
                {
                    if($execution->status == $status)
                    {
                        if(isset($teams[$execution->id][$this->app->user->account])) $myExecutions[$status][$execution->id] = $execution;
                        if($execution->project == $projectID) $kanbanGroup[$projectID][$status][$execution->id] = $execution;
                    }
                }

                $statusCount[$status] += isset($kanbanGroup[$projectID][$status]) ? count($kanbanGroup[$projectID][$status]) : 0;

                /* Max 2 closed executions. */
                if($status == 'closed')
                {
                    list($myExecutions, $kanbanGroup) = $this->processExecutionKanbanData($myExecutions, $kanbanGroup, $projectID, $status);
                }
            }

            if(empty($kanbanGroup[$projectID])) continue;
            $projectCount ++;
        }

        return array($projectCount, $statusCount, $myExecutions, $kanbanGroup);
    }

    /**
     * 获取打印看板的数据。
     * Get printed kanban data.
     *
     * @param  int       $executionID
     * @param  array     $stories
     * @access protected
     * @return array
     */
    protected function getPrintKanbanData(int $executionID, array $stories): array
    {
        $kanbanTasks = $this->execution->getKanbanTasks($executionID, "id");
        $kanbanBugs  = $this->loadModel('bug')->getExecutionBugs($executionID);

        $users       = array();
        $taskAndBugs = array();
        foreach($kanbanTasks as $task)
        {
            $status  = $task->status;
            $users[] = $task->assignedTo;

            $taskAndBugs[$status]["task{$task->id}"] = $task;
        }
        foreach($kanbanBugs as $bug)
        {
            $status  = $bug->status;
            $status  = $status == 'active' ? 'wait' : ($status == 'resolved' ? ($bug->resolution == 'postponed' ? 'cancel' : 'done') : $status);
            $users[] = $bug->assignedTo;

            $taskAndBugs[$status]["bug{$bug->id}"] = $bug;
        }

        $dataList = array();
        $contents = array('story', 'wait', 'doing', 'done', 'cancel');
        foreach($contents as $content)
        {
            if($content != 'story' and !isset($taskAndBugs[$content])) continue;
            $dataList[$content] = $content == 'story' ? $stories : $taskAndBugs[$content];
        }

        return array($dataList, $users);
    }

    /**
     * 处理执行看板数据。
     * Process execution kanban data.
     *
     * @param  array     $myExecutions
     * @param  array     $kanbanGroup
     * @param  int       $projectID
     * @param  string    $status
     * @access protected
     * @return array
     */
    protected function processExecutionKanbanData(array $myExecutions, array $kanbanGroup, int $projectID, string $status): array
    {
        if(isset($myExecutions[$status]) and count($myExecutions[$status]) > 2)
        {
            foreach($myExecutions[$status] as $executionID => $execution)
            {
                unset($myExecutions[$status][$executionID]);
                $myExecutions[$status][$execution->closedDate] = $execution;
            }

            krsort($myExecutions[$status]);
            $myExecutions[$status] = array_slice($myExecutions[$status], 0, 2, true);
        }

        if(isset($kanbanGroup[$projectID][$status]) and count($kanbanGroup[$projectID][$status]) > 2)
        {
            foreach($kanbanGroup[$projectID][$status] as $executionID => $execution)
            {
                unset($kanbanGroup[$projectID][$status][$executionID]);
                $kanbanGroup[$projectID][$status][$execution->closedDate] = $execution;
            }

            krsort($kanbanGroup[$projectID][$status]);
            $kanbanGroup[$projectID][$status] = array_slice($kanbanGroup[$projectID][$status], 0, 2);
        }
        return array($myExecutions, $kanbanGroup);
    }

    /**
     * 处理打印的看板数据。
     * Process printed Kanban data.
     *
     * @param  int       $executionID
     * @param  array     $dataList
     * @access protected
     * @return array
     */
    protected function processPrintKanbanData(int $executionID, array $dataList): array
    {
        $prevKanbans = $this->execution->getPrevKanban($executionID);
        foreach($dataList as $type => $data)
        {
            if(isset($prevKanbans[$type]))
            {
                $prevData = $prevKanbans[$type];
                foreach($prevData as $id)
                {
                    if(isset($data[$id])) unset($dataList[$type][$id]);
                }
            }
        }

        return $dataList;
    }

    /**
     * Check if the product has multiple branch and check if the execution has a product with multiple branch.
     *
     * @param  int $productID
     * @param  int $executionID
     * @return bool
     */
    protected function hasMultipleBranch(int $productID, int $executionID): bool
    {
        /* Check if the product is multiple branch. */
        $multiBranchProduct = false;
        if($productID)
        {
            $product = $this->loadModel('product')->getByID($productID);
            if($product->type != 'normal') $multiBranchProduct = true;
        }
        else
        {
            $executionProductList = $this->loadModel('product')->getProducts($executionID);
            foreach($executionProductList as $executionProduct)
            {
                if($executionProduct->type != 'normal')
                {
                    $multiBranchProduct = true;
                    break;
                }
            }
        }
        return $multiBranchProduct;
    }
}

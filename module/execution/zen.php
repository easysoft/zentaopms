<?php
declare(strict_types=1);
/**
 * The zen file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
class executionZen extends execution
{
    /**
     * 展示Bug列表的相关变量。
     * Show the bug list related variables.
     *
     * @param  object    $execution
     * @param  object    $project
     * @param  int       $productID
     * @param  string    $branch
     * @param  array     $products
     * @param  string    $orderBy
     * @param  string    $type
     * @param  string    $build
     * @param  int       $param
     * @param  array     $bugs
     * @param  object    $pager
     * @access protected
     * @return void
     */
    protected function assignBugVars(object $execution, object $project, int $productID, string $branch, array $products, string $orderBy, string $type, int $param, string $build, array $bugs, object $pager)
    {
        $this->loadModel('product');
        $this->loadModel('tree');

        $moduleID = $type != 'bysearch' ? $param : 0;

        /* Get module tree.*/
        $extra = array('projectID' => $execution->id, 'orderBy' => $orderBy, 'type' => $type, 'build' => $build, 'branchID' => $branch);
        if($execution->id and empty($productID) and count($products) > 1)
        {
            $moduleTree = $this->tree->getBugTreeMenu($execution->id, $productID, 0, array('treeModel', 'createBugLink'), $extra);
        }
        elseif(!empty($products))
        {
            $productID  = empty($productID) ? reset($products)->id : $productID;
            $moduleTree = $this->tree->getTreeMenu((int)$productID, 'bug', 0, array('treeModel', 'createBugLink'), $extra + array('branchID' => $branch, 'productID' => $productID), $branch);
        }
        else
        {
            $moduleTree = array();
        }
        $tree       = $moduleID ? $this->tree->getByID($moduleID) : '';
        $showModule = !empty($this->config->execution->bug->showModule) ? $this->config->execution->bug->showModule : '';
        $build      = !empty($build) ? $this->loadModel('build')->getById((int)$build) : null;

        /* Assign. */
        $this->view->title            = $execution->name . $this->lang->colon . $this->lang->execution->bug;
        $this->view->project          = $project;
        $this->view->orderBy          = $orderBy;
        $this->view->type             = $type;
        $this->view->pager            = $pager;
        $this->view->bugs             = $bugs;
        $this->view->summary          = $this->loadModel('bug')->summary($bugs);
        $this->view->moduleTree       = $moduleTree;
        $this->view->moduleID         = $moduleID;
        $this->view->modulePairs      = $showModule ? $this->tree->getModulePairs($productID, 'bug', $showModule) : array();
        $this->view->buildID          = $build ? $build->id : 0;
        $this->view->productID        = $productID;
        $this->view->product          = $this->product->getByID($productID);
        $this->view->branchID         = empty($this->view->build->branch) ? $branch : $this->view->build->branch;
        $this->view->users            = $this->loadModel('user')->getPairs('noletter');
        $this->view->param            = $param;
        $this->view->defaultProduct   = (empty($productID) and !empty($products)) ? current(array_keys($products)) : $productID;
        $this->view->builds           = $this->loadModel('build')->getBuildPairs(array($productID));
        $this->view->projectPairs     = $this->loadModel('project')->getPairsByProgram();
        $this->view->switcherObjectID = $productID;
    }

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
        $plans    = $this->execution->getPlans(array_keys($products), 'skipParent', $executionID);
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
     * 展示需求关联的任务、Bug、用例的数量以及统计需求信息。
     * Show the number of tasks, bugs cases linked with the stories, and statistics story information.
     *
     * @param  int       $executionID
     * @param  array     $stories
     * @param  string    $storyType
     * @access protected
     * @return void
     */
    protected function assignCountForStory(int $executionID, array $stories, string $storyType): void
    {
        /* Get related tasks, bugs, cases count of each story. */
        $storyIdList = array();
        foreach($stories as $story)
        {
            $storyIdList[$story->id] = $story->id;
            if(empty($story->children)) continue;

            foreach($story->children as $child) $storyIdList[$child->id] = $child->id;
        }

        $summary = $this->loadModel('product')->summary($stories, $storyType);

        $this->view->stories    = $stories;
        $this->view->storyTasks = $this->loadModel('task')->getStoryTaskCounts($storyIdList, $executionID);
        $this->view->storyBugs  = $this->loadModel('bug')->getStoryBugCounts($storyIdList, $executionID);
        $this->view->storyCases = $this->loadModel('testcase')->getStoryCaseCounts($storyIdList);
        $this->view->summary    = $storyType == 'requirement' ? str_replace($this->lang->SRCommon, $this->lang->URCommon, $summary) : $summary;
    }

    /**
     * 展示需求列表的相关变量。
     * Show the story list related variables.
     *
     * @param  object     $execution
     * @param  array      $products
     * @param  int        $productID
     * @param  string     $type
     * @param  string     $storyType
     * @param  int        $param
     * @param  string     $orderBy
     * @param  object     $pager
     * @access protected
     * @return void
     */
    protected function assignRelationForStory(object $execution, array $products, int $productID, string $type, string $storyType, int $param, string $orderBy, object $pager): void
    {
        $plans    = $this->execution->getPlans(array_keys($products), 'skipParent|withMainPlan|unexpired|noclosed|sortedByDate', $execution->id);
        $allPlans = array();
        if(!empty($plans))
        {
            foreach($plans as $plan) $allPlans += $plan;
        }

        if($this->cookie->storyProductParam) $this->view->product = $this->loadModel('product')->getById((int)$this->cookie->storyProductParam);
        if($this->cookie->storyBranchParam)
        {
            $branchID = $this->cookie->storyBranchParam;
            if(strpos($branchID, ',') !== false) list($productID, $branchID) = explode(',', $branchID);
            $this->view->branch = $this->loadModel('branch')->getById($branchID, $productID);
        }

        $executionProductList = $this->loadModel('product')->getProducts($execution->id);
        $multiBranch          = false;
        foreach($executionProductList as $executionProduct)
        {
            if($executionProduct->type != 'normal')
            {
                $multiBranch = true;
                break;
            }
        }

        $productPairs = $this->loadModel('product')->getProductPairsByProject($execution->id); // Get execution's product.
        if(empty($productID)) $productID = (int)key($productPairs);

        $this->assignModuleForStory($type, $param, $storyType, $execution, $productID);

        /* Assign. */
        $this->view->title        = $execution->name . $this->lang->colon . $this->lang->execution->story;
        $this->view->storyType    = $storyType;
        $this->view->param        = $param;
        $this->view->type         = $this->session->executionStoryBrowseType;
        $this->view->orderBy      = $orderBy;
        $this->view->pager        = $pager;
        $this->view->product      = $this->product->getById($productID);
        $this->view->allPlans     = $allPlans;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->multiBranch  = $multiBranch;
        $this->view->execution    = $execution;
        $this->view->canBeChanged = common::canModify('execution', $execution); // Determines whether an object is editable.
    }

    /**
     * 展示需求列表的模块变量。
     * Show the story list module variables.
     *
     * @param  string    $type
     * @param  int       $param
     * @param  string    $storyType
     * @param  object    $execution
     * @param  int       $productID
     * @access protected
     * @return void
     */
    protected function assignModuleForStory(string $type, int $param, string $storyType, object $execution, int $productID): void
    {
        $this->loadModel('tree');
        if($this->cookie->storyModuleParam) $this->view->module = $this->loadModel('tree')->getById((int)$this->cookie->storyModuleParam);
        $showModule  = !empty($this->config->execution->story->showModule) ? $this->config->execution->story->showModule : '';
        $modulePairs = $showModule ? $this->tree->getModulePairs($type == 'byproduct' ? $param : 0, 'story', $showModule) : array();

        $createModuleLink = $storyType == 'story' ? 'createStoryLink' : 'createRequirementLink';
        if(!$execution->hasProduct && !$execution->multiple)
        {
            $moduleTree = $this->tree->getTreeMenu($productID, 'story', 0, array('treeModel', $createModuleLink), array('executionID' => $execution->id, 'productID' => $productID), '', "&param=$param&storyType=$storyType");
        }
        else
        {
            $moduleTree = $this->tree->getProjectStoryTreeMenu($execution->id, 0, array('treeModel', $createModuleLink));
        }

        $this->view->moduleTree  = $moduleTree;
        $this->view->modulePairs = $modulePairs;
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

        $plans    = $this->execution->getPlans(array_keys($products));
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
     * 展示用例列表的相关变量。
     * Show the case list related variables.
     *
     * @param  int       $executionID
     * @param  int       $productID
     * @param  string    $branchID
     * @param  int       $moduleID
     * @param  string    $orderBy
     * @param  string    $type
     * @param  object    $pager
     * @access protected
     * @return void
     */
    protected function assignTestcaseVars(int $executionID, int $productID, string $branchID, int $moduleID, string $orderBy, string $type, object $pager)
    {
        $this->loadModel('tree');

        /* Get cases. */
        $cases = $this->loadModel('testcase')->getExecutionCases($type, $executionID, $productID, $branchID, $moduleID, $orderBy, $pager);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);
        $cases = $this->testcase->appendData($cases, 'case');
        $cases = $this->loadModel('story')->checkNeedConfirm($cases);

        /* Get module tree.*/
        if($executionID and empty($productID))
        {
            $moduleTree = $this->tree->getCaseTreeMenu($executionID, $productID, 0, array('treeModel', 'createCaseLink'));
        }
        else
        {
            $moduleTree = $this->tree->getTreeMenu($productID, 'case', 0, array('treeModel', 'createCaseLink'), array('projectID' => $executionID, 'productID' => $productID, 'branchID' => $branchID), $branchID);
        }

        $tree = $moduleID ? $this->tree->getByID($moduleID) : '';

        $this->view->cases           = $cases;
        $this->view->scenes          = $this->testcase->getSceneMenu($productID, $moduleID);;
        $this->view->users           = $this->loadModel('user')->getPairs('noletter');
        $this->view->title           = $this->lang->execution->testcase;
        $this->view->executionID     = $executionID;
        $this->view->productID       = $productID;
        $this->view->product         = $this->loadModel('product')->getByID((int)$productID);
        $this->view->orderBy         = $orderBy;
        $this->view->pager           = $pager;
        $this->view->type            = $type;
        $this->view->branchID        = $branchID;
        $this->view->branchTagOption = $this->loadModel('branch')->getPairs($productID, 'withClosed');
        $this->view->recTotal        = $pager->recTotal;
        $this->view->showBranch      = $this->loadModel('branch')->showBranch($productID);
        $this->view->stories         = array( 0 => '') + $this->loadModel('story')->getPairs($productID);
        $this->view->moduleTree      = $moduleTree;
        $this->view->moduleID        = $moduleID;
        $this->view->moduleName      = $moduleID ? $tree->name : $this->lang->tree->all;
        $this->view->showBranch      = $this->loadModel('branch')->showBranch($productID);
    }

    /**
     * 展示测试单的相关变量。
     * Show the testtask related variables.
     *
     * @param  array $tasks
     * @access protected
     * @return void
     */
    protected function assignTesttaskVars(array $tasks)
    {
        /* Compute rowspan. */
        $productGroup = array();
        $waitCount    = 0;
        $testingCount = 0;
        $blockedCount = 0;
        $doneCount    = 0;
        foreach($tasks as $task)
        {
            $productGroup[$task->product][] = $task;
            if($task->status == 'wait')    $waitCount ++;
            if($task->status == 'doing')   $testingCount ++;
            if($task->status == 'blocked') $blockedCount ++;
            if($task->status == 'done')    $doneCount ++;
            if($task->build == 'trunk' || empty($task->buildName)) $task->buildName = $this->lang->trunk;
        }

        $lastProduct = '';
        foreach($tasks as $taskID => $task)
        {
            $task->rowspan = 0;
            if($lastProduct !== $task->product)
            {
                $lastProduct = $task->product;
                if(!empty($productGroup[$task->product])) $task->rowspan = count($productGroup[$task->product]);
            }
        }

        $this->view->waitCount    = $waitCount;
        $this->view->testingCount = $testingCount;
        $this->view->blockedCount = $blockedCount;
        $this->view->doneCount    = $doneCount;
        $this->view->tasks        = $tasks;
    }

    /**
     * 展示执行详情的相关变量。
     * Show the view related variables.
     *
     * @param  int       $executionID
     * @access protected
     * @return void
     */
    protected function assignViewVars(int $executionID)
    {
        $this->executeHooks($executionID);

        $userPairs = array();
        $userList  = array();
        $users     = $this->loadModel('user')->getList('all');
        foreach($users as $user)
        {
            $userList[$user->account]  = $user;
            $userPairs[$user->account] = $user->realname;
        }

        /* Get linked branches. */
        $products       = $this->loadModel('product')->getProducts($executionID);
        $linkedBranches = array();
        foreach($products as $product)
        {
            if(isset($product->branches))
            {
                foreach($product->branches as $branchID) $linkedBranches[$branchID] = $branchID;
            }
        }

        $this->view->users        = $userPairs;
        $this->view->userList     = $userList;
        $this->view->products     = $products;
        $this->view->branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products), 'ignoreNormal', $linkedBranches);
        $this->view->planGroups   = $this->execution->getPlans(array_keys($products));
        $this->view->actions      = $this->loadModel('action')->getList('execution', $executionID);
        $this->view->dynamics     = $this->loadModel('action')->getDynamic('all', 'all', 'date_desc', 50, 'all', 'all', $executionID);
        $this->view->teamMembers  = $this->execution->getTeamMembers($executionID);
        $this->view->docLibs      = $this->loadModel('doc')->getLibsByObject('execution', $executionID);
        $this->view->statData     = $this->execution->statRelatedData($executionID);
    }

    /**
     * 构造任务分组视图数据。
     * Build task group data.
     *
     * @param  string    $groupBy story|status|pri|assignedTo|finishedBy|closedBy|type
     * @param  array     $tasks
     * @param  array     $users
     * @access protected
     * @return array
     */
    protected function buildGroupTasks(string $groupBy = 'story', array $tasks = array(), array $users = array()): array
    {
        $groupTasks  = array();
        $groupByList = array();
        foreach($tasks as $task)
        {
            if($groupBy == 'story')
            {
                $groupTasks[$task->story][] = $task;
                $groupByList[$task->story]  = $task->storyTitle;
            }
            elseif($groupBy == 'status')
            {
                $groupTasks[$this->lang->task->statusList[$task->status]][] = $task;
            }
            elseif($groupBy == 'assignedTo')
            {
                if(isset($task->team))
                {
                    $groupTasks = $this->buildGroupMultiTask($groupBy, $task, $users, $groupTasks);
                }
                else
                {
                    $groupTasks[$task->assignedToRealName][] = $task;
                }
            }
            elseif($groupBy == 'finishedBy')
            {
                if(isset($task->team))
                {
                    $task->consumed = $task->estimate = $task->left = 0;
                    $groupTasks = $this->buildGroupMultiTask($groupBy, $task, $users, $groupTasks);
                }
                else
                {
                    $groupTasks[$users[$task->finishedBy]][] = $task;
                }
            }
            elseif($groupBy == 'closedBy')
            {
                $groupTasks[$users[$task->closedBy]][] = $task;
            }
            elseif($groupBy == 'type')
            {
                $groupTasks[$this->lang->task->typeList[$task->type]][] = $task;
            }
            else
            {
                $groupTasks[$task->$groupBy][] = $task;
            }
        }

        /* Process closed data when group by assignedTo. */
        if($groupBy == 'assignedTo' && isset($groupTasks['Closed']))
        {
            $closedTasks = $groupTasks['Closed'];
            unset($groupTasks['Closed']);
            $groupTasks['closed'] = $closedTasks;
        }

        return array($groupTasks, $groupByList);
    }

    /**
     * 构建多人任务的分组视图数据。
     * Build group data for multiple task.
     *
     * @param  string    $groupBy
     * @param  object    $task
     * @param  array     $users
     * @param  array     $groupTasks
     * @access protected
     * @return array
     */
    protected function buildGroupMultiTask(string $groupBy, object $task, array $users, array $groupTasks): array
    {
        foreach($task->team as $team)
        {
            if($team->left != 0 && $groupBy == 'finishedBy')
            {
                $task->estimate += $team->estimate;
                $task->consumed += $team->consumed;
                $task->left     += $team->left;
                continue;
            }

            $cloneTask = clone $task;
            $cloneTask->{$groupBy} = $team->account;
            $cloneTask->estimate   = $team->estimate;
            $cloneTask->consumed   = $team->consumed;
            $cloneTask->left       = $team->left;
            if($team->left == 0 || $groupBy == 'finishedBy') $cloneTask->status = 'done';

            $realname = zget($users, $team->account);
            $cloneTask->assignedToRealName = $realname;
            $groupTasks[$realname][] = $cloneTask;
        }

        if($groupBy == 'finishedBy' && !empty($task->left)) $groupTasks[$users[$task->finishedBy]][] = $task;

        return $groupTasks;
    }

    /**
     * 构建需求列表的搜索表单数据。
     * Build the search form data to story list.
     *
     * @param  object    $execution
     * @param  int       $productID
     * @param  array     $products
     * @param  int       $queryID
     * @param  string    $actionURL
     * @access protected
     * @return void
     */
    protected function buildStorySearchForm(object $execution, int $productID, array $products, int $queryID, string $actionURL): void
    {
        $modules          = array();
        $productModules   = array();
        $executionModules = $this->loadModel('tree')->getTaskTreeModules($execution->id, true);
        if($productID)
        {
            $product = $products[$productID];
            $productModules = $this->tree->getOptionMenu($productID, 'story', 0, $product->branches);
        }
        else
        {
            foreach($products as $product) $productModules += $this->tree->getOptionMenu($product->id, 'story', 0, $product->branches);
        }

        if(commonModel::isTutorialMode())
        {
            $modules = $this->loadModel('tutorial')->getModulePairs();
        }
        else
        {
            foreach($productModules as $branchID => $moduleList)
            {
                foreach($moduleList as $moduleID => $moduleName)
                {
                    if($moduleID && !isset($executionModules[$moduleID])) continue;
                    $modules[$moduleID] = ((count($products) >= 2 && $moduleID) ? $product->name : '') . $moduleName;
                }
            }
        }

        $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products));
        $this->execution->buildStorySearchForm($products, $branchGroups, $modules, $queryID, $actionURL, 'executionStory', $execution);
    }

    /**
     * 将导入的Bug转为任务。
     * Change imported bugs to the tasks.
     *
     * @param  object    $execution
     * @param  array     $postData
     * @access protected
     * @return array
     */
    protected function buildTasksForImportBug(object $execution, array $postData)
    {
        $this->loadModel('task');

        $tasks          = array();
        $bugs           = $this->loadModel('bug')->getByIdList(array_keys($postData));
        $showAllModule  = isset($this->config->execution->task->allModule) ? $this->config->execution->task->allModule : '';
        $modules        = $this->loadModel('tree')->getTaskOptionMenu($execution->id, 0, $showAllModule ? 'allModule' : '');
        $now            = helper::now();
        $requiredFields = str_replace(',story,', ',', ',' . $this->config->task->create->requiredFields . ',');
        $requiredFields = trim($requiredFields, ',');
        foreach($postData as $bugID => $task)
        {
            $bug = zget($bugs, $bugID, '');
            if(empty($bug)) continue;

            unset($task->id);
            $task->bug          = $bug;
            $task->project      = $execution->project;
            $task->execution    = $execution->id;
            $task->story        = $bug->story;
            $task->storyVersion = $bug->storyVersion;
            $task->module       = isset($modules[$bug->module]) ? $bug->module : 0;
            $task->fromBug      = $bugID;
            $task->name         = $bug->title;
            $task->type         = 'devel';
            $task->consumed     = 0;
            $task->status       = 'wait';
            $task->openedDate   = $now;
            $task->openedBy     = $this->app->user->account;
            $task->estStarted   = $task->estStarted ? $task->estStarted : null;
            $task->deadline     = $task->deadline ? $task->deadline : null;

            if($task->estimate !== '') $task->left = $task->estimate;
            if(!empty($task->assignedTo)) $task->assignedDate = $now;

            /* Check task required fields. */
            foreach(explode(',', $requiredFields) as $field)
            {
                if(empty($field))         continue;
                if(!isset($task->$field)) continue;
                if(!empty($task->$field)) continue;

                if($field == 'estimate' and strlen(trim($task->estimate)) != 0) continue;

                dao::$errors["{$field}[{$bugID}]"] = 'ID: ' . $bugID . sprintf($this->lang->error->notempty, $this->lang->task->$field);
                return false;
            }

            if(!preg_match("/^[0-9]+(.[0-9]{1,3})?$/", (string)$task->estimate) and !empty($task->estimate))
            {
                dao::$errors["{$field}[{$bugID}]"] = 'ID: ' . $bugID . $this->lang->task->error->estimateNumber;
                return false;
            }

            if(!empty($this->config->limitTaskDate))
            {
                $this->task->checkEstStartedAndDeadline($executionID, $task->estStarted, $task->deadline);
                if(dao::isError()) return false;
            }

            $tasks[$bugID] = $task;
        }

        return $tasks;
    }

    /**
     * 构建导入Bug的搜索表单数据。
     * Build the search form data to import the Bug.
     *
     * @param  object    $execution
     * @param  int       $queryID
     * @param  array     $products
     * @param  array     $executions
     * @param  array     $projects
     * @access protected
     * @return void
     */
    protected function buildImportBugSearchForm(object $execution, int $queryID, array $products, array $executions, array $projects)
    {
        $project = $this->loadModel('project')->getByID($execution->project);

        $this->config->bug->search['actionURL'] = $this->createLink('execution', 'importBug', "executionID=$execution->id&browseType=bySearch&param=myQueryID");
        $this->config->bug->search['queryID']   = $queryID;
        if(!empty($products))
        {
            $this->config->bug->search['params']['product']['values'] = array(''=>'') + $products + array('all'=>$this->lang->execution->aboveAllProduct);
        }
        else
        {
            $this->config->bug->search['params']['product']['values'] = array(''=>'');
        }
        $this->config->bug->search['params']['execution']['values'] = array(''=>'') + $executions + array('all'=>$this->lang->execution->aboveAllExecution);
        $this->config->bug->search['params']['plan']['values']      = $this->loadModel('productplan')->getPairs(array_keys($products));
        $this->config->bug->search['module'] = 'importBug';
        $this->config->bug->search['params']['confirmed']['values'] = $this->lang->bug->confirmedList;

        $this->loadModel('tree');
        $bugModules = array();
        foreach($products as $productID => $productName)
        {
            $productModules = $this->tree->getOptionMenu($productID, 'bug', 0, 'all');
            foreach($productModules as $moduleID => $moduleName)
            {
                if(empty($moduleID))
                {
                    $bugModules[$moduleID] = $moduleName;
                    continue;
                }
                $bugModules[$moduleID] = $productName . $moduleName;
            }
        }
        $this->config->bug->search['params']['module']['values'] = $bugModules;

        $this->config->bug->search['params']['project']['values'] = array('' => '') + $projects;

        $this->config->bug->search['params']['openedBuild']['values'] = $this->loadModel('build')->getBuildPairs($productID, 'all', 'withbranch|releasetag');

        unset($this->config->bug->search['fields']['resolvedBy']);
        unset($this->config->bug->search['fields']['closedBy']);
        unset($this->config->bug->search['fields']['status']);
        unset($this->config->bug->search['fields']['toTask']);
        unset($this->config->bug->search['fields']['toStory']);
        unset($this->config->bug->search['fields']['severity']);
        unset($this->config->bug->search['fields']['resolution']);
        unset($this->config->bug->search['fields']['resolvedBuild']);
        unset($this->config->bug->search['fields']['resolvedDate']);
        unset($this->config->bug->search['fields']['closedDate']);
        unset($this->config->bug->search['fields']['branch']);
        if(empty($execution->multiple) && empty($execution->hasProduct)) unset($this->config->bug->search['fields']['plan']);
        if(empty($project->hasProduct))
        {
            unset($this->config->bug->search['fields']['product']);
            if($project->model !== 'scrum') unset($this->config->bug->search['fields']['plan']);
        }
        unset($this->config->bug->search['params']['resolvedBy']);
        unset($this->config->bug->search['params']['closedBy']);
        unset($this->config->bug->search['params']['status']);
        unset($this->config->bug->search['params']['toTask']);
        unset($this->config->bug->search['params']['toStory']);
        unset($this->config->bug->search['params']['severity']);
        unset($this->config->bug->search['params']['resolution']);
        unset($this->config->bug->search['params']['resolvedBuild']);
        unset($this->config->bug->search['params']['resolvedDate']);
        unset($this->config->bug->search['params']['closedDate']);
        unset($this->config->bug->search['params']['branch']);
        $this->loadModel('search')->setSearchParams($this->config->bug->search);
    }

    /**
     * 检查创建的表单数据。
     * Check the form data for create.
     *
     * @access protected
     * @return bool
     */
    protected function checkPostForCreate(): bool
    {
        if(empty($_POST['project']))
        {
            dao::$errors['project'] = $this->lang->execution->projectNotEmpty;
            return false;
        }

        $projectID = (int)$_POST['project'];
        $project   = $this->loadModel('project')->fetchByID($projectID);
        $this->execution->checkBeginAndEndDate($projectID, $_POST['begin'], $_POST['end'], $projectID);
        if(dao::isError()) return false;

        /* Judge workdays is legitimate. */
        $workdays = helper::diffDate($_POST['end'], $_POST['begin']) + 1;
        if(isset($_POST['days']) && $_POST['days'] > $workdays)
        {
            dao::$errors['days'] = sprintf($this->lang->project->workdaysExceed, $workdays);
            return false;
        }

        $_POST['products'] = array_filter($_POST['products']);
        if(!empty($_POST['products']))
        {
            $this->app->loadLang('project');
            $multipleProducts  = $this->loadModel('product')->getMultiBranchPairs();
            foreach($_POST['products'] as $index => $productID)
            {
                if(empty($_POST['branch'][$index])) continue;

                $branches = implode(',', $_POST['branch'][$index]);
                if(isset($multipleProducts[$productID]) && $branches == '')
                {
                    dao::$errors["branch[{$index}][]"] = $this->lang->project->error->emptyBranch;
                    return false;
                }
            }
        }

        /* Determine whether to add a sprint or a stage according to the model of the execution. */
        if($project->model == 'waterfall' || $project->model == 'waterfallplus')
        {
            if(empty($_POST['products'])) dao::$errors['products[0]'] = $this->lang->project->errorNoProducts;
            if(dao::isError()) return false;

            if(isset($this->config->setPercent) && $this->config->setPercent == 1) $this->execution->checkWorkload('create', (int)$_POST['percent'], $project);
            if(dao::isError()) return false;
            $this->config->execution->create->requiredFields .= ',percent';
        }

        return true;
    }

    /**
     * 构造创建执行的数据。
     * Build the data for create execution.
     *
     * @access protected
     * @return object|false
     */
    protected function buildExecutionForCreate(): object|false
    {
        if(!$this->checkPostForCreate()) return false;

        $now     = helper::now();
        $project = $this->loadModel('project')->fetchByID((int)$_POST['project']);
        $type    = 'sprint';
        if($project) $type = zget($this->config->execution->modelList, $project->model, 'sprint');

        $fields       = $this->config->execution->form->create;
        $editorFields = array_keys(array_filter(array_map(function($config){return $config['control'] == 'editor';}, $fields)));
        foreach(explode(',', trim($this->config->execution->create->requiredFields, ',')) as $field) $fields[$field]['required'] = true;
        if(!isset($_POST['code'])) $fields['code']['required'] = false;
        $this->config->execution->create->requiredFields = implode(',', array_keys(array_filter(array_map(function($config){return $config['required'] == true;}, $fields))));

        $this->correctErrorLang();
        $execution = form::data($fields)
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('openedDate', $now)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->setDefault('team', $this->post->name)
            ->setDefault('parent', $this->post->project)
            ->setIF($this->post->parent, 'parent', $this->post->parent)
            ->setIF($this->post->heightType == 'auto', 'displayCards', 0)
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->setDefault('type', $type)
            ->get();

        if(!empty($execution->parent) && ($execution->project == $execution->parent)) $execution->hasProduct = $project->hasProduct;
        if($this->post->heightType == 'custom' && !$this->loadModel('kanban')->checkDisplayCards($execution->displayCards)) return false;

        /* Set planDuration and realDuration. */
        if(in_array($this->config->edition, array('max', 'ipd')))
        {
            $execution->planDuration = $this->loadModel('programplan')->getDuration($execution->begin, $execution->end);
            if(!empty($execution->realBegan) && !empty($execution->realEnd)) $execution->realDuration = $this->programplan->getDuration($execution->realBegan, $execution->realEnd);
        }

        return $this->loadModel('file')->processImgURL($execution, $editorFields, $this->post->uid);
    }

    /**
     * 检查累积流图的日期。
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
            $branches = $this->loadModel('branch')->getList($productID, $executionID, 'all');
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
     * @access protected
     * @return array
     */
    protected function buildMembers(array $currentMembers, array $members2Import, array $deptUsers, int $days): array
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
     * 根据过滤规则，筛选任务分组数据。
     * Filter task group data based on the filter rules.
     *
     * @param  array     $groupTasks
     * @param  string    $groupBy
     * @param  string    $filter
     * @param  int       $allCount
     * @param  array     $tasks
     * @access protected
     * @return array
     */
    protected function filterGroupTasks(array $groupTasks, string $groupBy, string $filter, int $allCount, array $tasks): array
    {
        if($filter == 'all') return array($groupTasks, $allCount);

        if($groupBy == 'story' && $filter == 'linked' && isset($groupTasks[0]))
        {
            $allCount -= count($groupTasks[0]);
            unset($groupTasks[0]);
        }
        elseif($groupBy == 'pri' && $filter == 'noset')
        {
            foreach($groupTasks as $pri => $tasks)
            {
                if($pri)
                {
                    $allCount -= count($tasks);
                    unset($groupTasks[$pri]);
                }
            }
        }
        elseif($groupBy == 'assignedTo' && $filter == 'undone')
        {
            $multiTaskCount = array();
            foreach($groupTasks as $assignedTo => $tasks)
            {
                foreach($tasks as $i => $task)
                {
                    if($task->status != 'wait' && $task->status != 'doing')
                    {
                        if($task->mode == 'multi' && !isset($multiTaskCount[$task->id]))
                        {
                            $multiTaskCount[$task->id] = true;
                            $allCount -= 1;
                        }
                        elseif($task->mode != 'multi')
                        {
                            $allCount -= 1;
                        }

                        unset($groupTasks[$assignedTo][$i]);
                    }
                }
            }
        }
        elseif(($groupBy == 'finishedBy' || $groupBy == 'closedBy') && isset($tasks['']))
        {
            $allCount -= count($tasks['']);
            unset($tasks['']);
        }

        return array($groupTasks, $allCount);
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
     * 获取可以导入到执行中的Bug。
     *
     * @param  int       $executionID
     * @param  array     $productIdList
     * @param  string    $browseType
     * @param  int       $queryID
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function getImportBugs(int $executionID, array $productIdList, string $browseType, int $queryID, object $pager): array
    {
        $this->loadModel('bug');

        $bugs = array();
        if($browseType != "bysearch")
        {
            $bugs = $this->bug->getActiveAndPostponedBugs($productIdList, $executionID, $pager);
        }
        else
        {
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('importBugQuery', $query->sql);
                    $this->session->set('importBugForm', $query->form);
                }
                else
                {
                    $this->session->set('importBugQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->importBugQuery === false) $this->session->set('importBugQuery', ' 1 = 1');
            }
            $bugQuery = str_replace("`product` = 'all'", "`product`" . helper::dbIN($productIdList), $this->session->importBugQuery); // Search all execution.
            $bugs     = $this->execution->getSearchBugs($productIdList, $executionID, $bugQuery, 'id_desc', $pager);
        }

        return $bugs;
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

    /**
     * 通过模块，方法和类型生成执行的链接。
     * Generate the link of execution by module, method and type.
     *
     * @param  string  $module
     * @param  string  $method
     * @param  mixed   $type
     * @access protected
     * @return string
     */
    protected function getLink(string $module, string $method, string $type = ''): string
    {
        $executionModules = array('task', 'testcase', 'build', 'bug', 'case', 'testtask', 'testreport', 'doc');
        if(in_array($module, array('task', 'testcase')) && in_array($method, array('view', 'edit', 'batchedit', 'create', 'batchcreate', 'report'))) $method = $module;
        if(in_array($module, $executionModules) && in_array($method, array('view', 'edit', 'create'))) $method = $module;
        if(in_array($module, $executionModules + array('story', 'product'))) $module = 'execution';

        if($module == 'story') $method = 'story';
        if($module == 'product' && $method == 'showerrornone') $method = 'task';
        if($module == 'execution' && $method == 'create') return '';

        $link = helper::createLink($module, $method, "executionID=%s");
        if($module == 'execution' && ($method == 'index' || $method == 'all'))
        {
            $link = helper::createLink($module, 'task', "executionID=%s");
        }
        elseif($module == 'execution' and $method == 'storyview')
        {
            $link = helper::createLink($module, 'story', "executionID=%s");
        }
        elseif($module == 'bug' && $method == 'create' && $this->app->tab == 'execution')
        {
            $link = helper::createLink($module, $method, "productID=0&branch=0&executionID=%s");
        }
        elseif(in_array($module, array('bug', 'case', 'testtask', 'testreport')) && strpos(',view,edit,', ",$method,") !== false)
        {
            $link = helper::createLink('execution', $module, "executionID=%s");
        }
        elseif($module == 'repo' && $method == 'review')
        {
            $link = helper::createLink('repo', 'review', "repoID=0&browseType=all&executionID=%s") . '#app=execution';
        }
        elseif($module == 'repo')
        {
            $link = helper::createLink('repo', 'browse', "repoID=0&branchID=&executionID=%s");
        }
        elseif($module == 'mr')
        {
            $link = helper::createLink('mr', 'browse', "repoID=0&mode=status&param=opened&objectID=%s") . '#app=execution';
        }
        elseif($module == 'doc')
        {
            $link = helper::createLink('doc', $method, "type=execution&objectID=%s&from=execution");
        }
        elseif(in_array($module, array('issue', 'risk', 'opportunity', 'pssp', 'auditplan', 'nc', 'meeting')))
        {
            $link = helper::createLink($module, 'browse', "executionID=%s&from=execution");
        }
        elseif(($module == 'testreport' && $method == 'create') || ($module == 'execution' && $method == 'cases'))
        {
            $link = helper::createLink('execution', 'testtask', "executionID=%s");
        }

        if($type != '') $link .= "&type=$type";
        return $link;
    }

    /**
     * 设置cookie和session。
     * Set the cookie and session.
     *
     * @param  string    $executionID
     * @param  string    $type        all|byModule|byProduct|byBranch|bySearch
     * @param  string    $param
     * @param  string    $orderBy
     * @access protected
     * @return int
     */
    protected function setStorageForStory(string $executionID, string $type, string $param, string $orderBy): int
    {
        $productID = 0;
        helper::setcookie('storyPreExecutionID', $executionID);
        if($this->cookie->storyPreExecutionID != $executionID)
        {
            $_COOKIE['storyModuleParam'] = $_COOKIE['storyProductParam'] = $_COOKIE['storyBranchParam'] = 0;
            helper::setcookie('storyModuleParam',  '0');
            helper::setcookie('storyProductParam', '0');
            helper::setcookie('storyBranchParam',  '0');
        }
        if($type == 'bymodule')
        {
            $module    = $this->loadModel('tree')->getByID((int)$param);
            $productID = isset($module->root) ? $module->root : 0;

            helper::setcookie('storyModuleParam',  $param);
            helper::setcookie('storyProductParam', '0');
            helper::setcookie('storyBranchParam',  '0');
        }
        elseif($type == 'byproduct')
        {
            $productID = $param;
            helper::setcookie('storyModuleParam',  '0');
            helper::setcookie('storyProductParam', $param);
            helper::setcookie('storyBranchParam',  '0');
        }
        elseif($type == 'bybranch')
        {
            helper::setcookie('storyModuleParam',  '0');
            helper::setcookie('storyProductParam', '0');
            helper::setcookie('storyBranchParam',  $param);
        }
        else
        {
            $this->session->set('executionStoryBrowseType', $type);
            $this->session->set('storyBrowseType',          $type, 'execution');
        }

        $uri = $this->app->getURI(true);
        $this->session->set('storyList',          $uri, $this->app->tab);
        $this->session->set('executionStoryList', $uri, 'execution');

        helper::setcookie('executionStoryOrder', $orderBy);

        return (int)$productID;
    }

    /**
     * Set the more link of user and return users with echo role.
     *
     * @param  object[]|object $execution
     * @return array
     */
    protected function setUserMoreLink(array|object $execution = null): array
    {
        $appendPo = $appendPm = $appendQd = $appendRd = array();
        if(is_array($execution))
        {
            $appendPo = $appendPm = $appendQd = $appendRd = array();
            foreach($execution as $item)
            {
                $appendPo[$item->PO] = $item->PO;
                $appendPm[$item->PM] = $item->PM;
                $appendQd[$item->QD] = $item->QD;
                $appendRd[$item->RD] = $item->RD;
            }
        }
        elseif(is_object($execution))
        {
            $appendPo[$execution->PO] = $execution->PO;
            $appendPm[$execution->PM] = $execution->PM;
            $appendQd[$execution->QD] = $execution->QD;
            $appendRd[$execution->RD] = $execution->RD;
        }

        $this->loadModel('user');
        $pmUsers = $this->user->getPairs('noclosed|nodeleted|pmfirst', $appendPm, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["PM"] = $this->config->user->moreLink;

        $poUsers = $this->user->getPairs('noclosed|nodeleted|pofirst',  $appendPo, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["PO"] = $this->config->user->moreLink;

        $qdUsers = $this->user->getPairs('noclosed|nodeleted|qdfirst',  $appendQd, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["QD"] = $this->config->user->moreLink;

        $rdUsers = $this->user->getPairs('noclosed|nodeleted|devfirst', $appendRd, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["RD"] = $this->config->user->moreLink;

        return array($pmUsers, $poUsers, $qdUsers, $rdUsers);
    }

    /**
     * 初始化创建执行的字段。
     * Init execution fields for create.
     *
     * @param  int       $projectID
     * @param  array     $output
     * @access protected
     * @return object
     */
    protected function initFieldsForCreate(int $projectID, array $output = array()): object
    {
        $execution = new stdclass();
        $execution->project   = $projectID;
        $execution->type      = zget($output, 'type', 'sprint');
        $execution->name      = '';
        $execution->code      = '';
        $execution->team      = '';
        $execution->acl       = 'private';
        $execution->whitelist = '';

        return $execution;
    }

    /**
     * 通过复制执行ID设置字段值。
     * Set execution fields by copy execution id.
     *
     * @param  object    $fields
     * @param  int       $copyExecutionID
     * @access protected
     * @return object
     */
    protected function setFieldsByCopyExecution(object $fields, int $copyExecutionID): object
    {
        if(empty($copyExecutionID)) return $fields;

        $copyExecution     = $this->execution->fetchByID($copyExecutionID);
        $fields->project   = (int)$copyExecution->project;
        $fields->type      = $copyExecution->type;
        $fields->name      = $copyExecution->name;
        $fields->code      = $copyExecution->code;
        $fields->team      = $copyExecution->team;
        $fields->acl       = $copyExecution->acl;
        $fields->whitelist = $copyExecution->whitelist;

        $this->view->copyExecution = $copyExecution;

        return $fields;
    }

    /**
     * 获取已关联的产品。
     * Get can link products.
     *
     * @param  int         $copyExecutionID
     * @param  int         $planID
     * @param  object|null $project
     * @access protected
     * @return array
     */
    protected function getLinkedProducts(int $copyExecutionID, int $planID, object|null $project): array
    {
        $products = array();
        if($copyExecutionID) $products = $this->loadModel('product')->getProducts($copyExecutionID);
        if($planID)
        {
            $plan     = $this->loadModel('productplan')->fetchByID($planID);
            $products = $this->dao->select('t1.id, t1.name, t1.type, t2.branch')->from(TABLE_PRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id = t2.product')
                ->where('t1.id')->eq($plan->product)
                ->fetchAll('id');

            $this->view->plan = $plan;
        }
        if(!empty($project) and $project->stageBy == 'project') $products = $this->loadModel('product')->getProducts($project->id);

        return $products;
    }

    /**
     * 获取已关联的分支。
     * Set linked branches.
     *
     * @param  array       $products
     * @param  int         $copyExecutionID
     * @param  int         $planID
     * @param  object|null $project
     * @access protected
     * @return void
     */
    protected function setLinkedBranches(array $products, int $copyExecutionID, int $planID, object|null $project)
    {
        $projectID = empty($project) ? 0 : $project->id;
        if(!empty($copyExecutionID))
        {
            $branches     = $this->project->getBranchesByProject($copyExecutionID);
            $plans        = $this->loadModel('productplan')->getGroupByProduct(array_keys($products), 'skipparent|unexpired');
            $branchGroups = $this->execution->getBranchByProduct(array_keys($products), $projectID);
        }

        if(!empty($project) and $project->stageBy == 'project')
        {
            $branches = $this->project->getBranchesByProject($projectID);
            $plans    = $this->loadModel('productplan')->getGroupByProduct(array_keys($products), 'skipparent|unexpired');
        }

        if($products and isset($branches))
        {
            $linkedBranches = array();
            foreach($products as $productIndex => $product)
            {
                $productPlans[$productIndex] = array();
                foreach($branches[$productIndex] as $branchID => $branch)
                {
                    $linkedBranches[$productIndex][$branchID] = $branchID;
                    if(isset($plans[$productIndex][$branchID]))
                    {
                        foreach($plans[$productIndex][$branchID] as $plan) $productPlans[$productIndex][$plan->id] = $plan->title;
                    }
                }
            }

            $this->view->productPlans   = $productPlans;
            $this->view->linkedBranches = $linkedBranches;
        }

        if(!empty($planID))
        {
            $plan           = $this->loadModel('productplan')->fetchByID($planID);
            $productPlan    = $this->productplan->getPairs($plan->product, $plan->branch, 'unexpired|withMainPlan', true);
            $linkedBranches = array();
            $linkedBranches[$plan->product][$plan->branch] = $plan->branch;
            $this->view->linkedBranches = $linkedBranches;
        }

        $this->view->productPlan  = isset($productPlan)  ? $productPlan  : array();
        $this->view->branchGroups = isset($branchGroups) ? $branchGroups : $this->execution->getBranchByProduct(array_keys($products), $projectID);
        if(isset($project->hasProduct) and empty($project->hasProduct))
        {
            $shadowProduct = $this->loadModel('product')->getShadowProductByProject($project->id);
            $this->view->productPlan = $this->loadModel('productplan')->getPairs($shadowProduct->id, '0,0', 'noclosed,unexpired', true);
        }
    }

    /**
     * 根据项目获取可关联的产品。
     * Get all products for create.
     *
     * @param  object|null $project
     * @access protected
     * @return void
     */
    protected function getAllProductsForCreate(object|null $project) : array
    {
        if(empty($project)) return array();

        $allProducts = $this->loadModel('product')->getProductPairsByProject($project->id, 'noclosed');
        if(!empty($project->hasProduct)) $allProducts = array(0 => '') + $allProducts;

        return $allProducts;
    }

    /**
     * 设置可复制的执行。
     * Set copy projects.
     *
     * @param  object|null $project
     * @access protected
     * @return void
     */
    protected function setCopyProjects(object|null $project)
    {
        $parentProject = 0;
        $projectModel  = '';

        if($project)
        {
            $parentProject = $project->parent;
            $projectModel  = $project->model;
            if($projectModel == 'agileplus')     $projectModel = array('scrum', 'agileplus');
            if($projectModel == 'waterfallplus') $projectModel = array('waterfall', 'waterfallplus');
        }

        $copyProjects  = $this->loadModel('project')->getPairsByProgram($parentProject, 'noclosed', false, 'order_asc', '', $projectModel, 'multiple');
        $copyProjectID = empty($project) ? key($copyProjects) : $project->id;
        $this->view->copyProjects   = $copyProjects;
        $this->view->copyProjectID  = $copyProjectID;
        $this->view->copyExecutions = empty($copyProjectID) ? array() : $this->execution->getList($copyProjectID, 'all', 'all', 0, 0, 0, null, false);
    }

    /**
     * 修正executionCommon公共语言项。
     * Correct execution common lang.
     *
     * @param  object    $project
     * @param  string    $type
     * @access protected
     * @return bool
     */
    protected function correctExecutionCommonLang(object $project, string $type): bool
    {
        if(empty($project)) return false;
        if($project->model == 'kanban' or ($project->model == 'agileplus' and $type == 'kanban'))
        {
            global $lang;
            $executionLang           = $lang->execution->common;
            $executionCommonLang     = $lang->executionCommon;
            $lang->executionCommon   = $lang->execution->kanban;
            $lang->execution->common = $lang->execution->kanban;
            include $this->app->getModulePath('', 'execution') . 'lang/' . $this->app->getClientLang() . '.php';
            $lang->execution->common = $executionLang;
            $lang->executionCommon   = $executionCommonLang;

            $lang->execution->typeList['sprint'] = $executionCommonLang;
        }
        elseif($project->model == 'waterfall' || $project->model == 'waterfallplus')
        {
            $this->app->loadLang('stage');

            global $lang;
            $lang->executionCommon = $lang->execution->stage;
            include $this->app->getModulePath('', 'execution') . 'lang/' . $this->app->getClientLang() . '.php';
        }

        if(isset($project->hasProduct) and empty($project->hasProduct)) $this->lang->execution->PO = $this->lang->common->story . $this->lang->execution->owner;

        return true;;
    }

    /**
     * 修正错误提示语言。
     * Correct error lang.
     *
     * @access protected
     * @return void
     */
    protected function correctErrorLang(): void
    {
        $this->lang->execution->team = $this->lang->execution->teamName;
        $this->lang->error->unique   = $this->lang->error->repeat;

        /* Redefines the language entries for the fields in the project table. */
        foreach(explode(',', $this->config->execution->create->requiredFields) as $field)
        {
            if(isset($this->lang->execution->$field)) $this->lang->project->$field = $this->lang->execution->$field;
        }

        /* Replace required language. */
        if($this->app->tab == 'project')
        {
            $this->lang->project->name = $this->lang->execution->name;
            $this->lang->project->code = $this->lang->execution->code;
        }
        else
        {
            $this->lang->project->name = $this->lang->execution->execName;
            $this->lang->project->code = $this->lang->execution->execCode;
        }
    }

    /**
     * 创建执行后，显示提示页面。
     * Display after created.
     *
     * @param  int       $projectID
     * @param  int       $executionID
     * @param  int       $planID
     * @param  string    $confirm
     * @access protected
     * @return void
     */
    protected function displayAfterCreated(int $projectID, int $executionID, int $planID, string $confirm = 'no')
    {
        $execution = $this->execution->fetchByID($executionID);
        if(!empty($planID) and $execution->lifetime != 'ops')
        {
            if($confirm == 'yes')
            {
                $this->execution->linkStories($executionID);
            }
            else
            {
                $executionProductList = $this->loadModel('product')->getProducts($executionID);
                $multiBranchProduct   = false;
                array_map(function($executionProduct) use(&$multiBranchProduct){if($executionProduct->type != 'normal') $multiBranchProduct = true;}, $executionProductList);

                $importPlanStoryTips = $multiBranchProduct ? $this->lang->execution->importBranchPlanStory : $this->lang->execution->importPlanStory;
                $confirmURL          = inlink('create', "projectID=$projectID&executionID=$executionID&copyExecutionID=&planID=$planID&confirm=yes");
                $cancelURL           = inlink('create', "projectID=$projectID&executionID=$executionID");
                return $this->send(array('result' => 'success', 'load' => array('confirm' => $importPlanStoryTips, 'confirmed' => $confirmURL, 'canceled' => $cancelURL)));
            }
        }

        if(!empty($projectID) and $execution->type == 'kanban' and $this->app->tab == 'project') return $this->send(array('result' => 'success', 'load' => $this->createLink('project', 'index', "projectID=$projectID")));
        if(!empty($projectID) and $execution->type == 'kanban') return $this->send(array('result' => 'success', 'load' => inlink('kanban', "executionID=$executionID")));

        $this->view->title       = $this->lang->execution->tips;
        $this->view->executionID = $executionID;
        $this->view->execution   = $this->execution->fetchByID($executionID);
        $this->display('execution', 'tips');
    }

    /**
     * 更新迭代关联的计划。
     * Update linked plans.
     *
     * @param  int    $executionID
     * @param  string $newPlans
     * @param  string $confirm
     * @return void
     */
    protected function updateLinkedPlans(int $executionID, string $newPlans = '', string $confirm = 'no')
    {
        if(!empty($newPlans) and $confirm == 'yes')
        {
            $newPlans = explode(',', $newPlans);
            $projectID = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch('project');
            $this->loadModel('productplan')->linkProject($executionID, $newPlans);
            $this->productplan->linkProject($projectID, $newPlans);
            return $this->send(array('result' => 'success', 'load' => inlink('view', "executionID=$executionID")));
        }
        elseif(!empty($newPlans))
        {
            $executionProductList = $this->loadModel('product')->getProducts($executionID); /* 无论是迭代ID还是项目ID都会查到一个与之对应的产品。*/
            $multiBranchProduct   = false;
            foreach($executionProductList as $executionProduct)
            {
                if($executionProduct->type != 'normal')
                {
                    $multiBranchProduct = true;
                    break;
                }
            }

            $linkPlanMsg = $multiBranchProduct ? $this->lang->execution->importBranchEditPlanStory : $this->lang->execution->importEditPlanStory;
            $confirmURL  = inlink('edit', "executionID=$executionID&action=edit&extra=&newPlans=$newPlans&confirm=yes");
            $cancelURL   = inlink('view', "executionID=$executionID");
            return $this->send(array('result' => 'success', 'load' => array('confirm' => $linkPlanMsg, 'confirmed' => $confirmURL, 'canceled' => $cancelURL)));
        }
    }

    /**
     * Check if the execution can be linked to plan stories.
     *
     * @param  int $executionID
     * @param  array $oldPlans
     * @return void
     */
    protected function checkLinkPlan(int $executionID, array $oldPlans)
    {
        $oldPlans = explode(',', implode(',' ,$oldPlans));
        $newPlans = array();
        if(isset($_POST['plans']))
        {
            foreach($_POST['plans'] as $plans)
            {
                foreach($plans as $planID)
                {
                    if(array_search($planID, $oldPlans) === false) $newPlans[$planID] = $planID;
                }
            }
        }

        $newPlans = array_filter($newPlans);
        if(!empty($newPlans))
        {
            $newPlans = implode(',', $newPlans);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('edit', "executionID=$executionID&action=edit&extra=&newPlans=$newPlans&confirm=no")));
        }
    }

    /**
     * 获取执行关联的对象，如关联的产品、分支、需求等。
     * Get linked objects of this execution.
     *
     * @param  object $execution
     * @return object
     */
    protected function getLinkedObjects(object $execution): object
    {
        $this->loadModel('project');
        $this->loadModel('product');
        $this->loadModel('productplan');

        $branches            = $this->project->getBranchesByProject($execution->id);
        $linkedProductIdList = empty($branches) ? '' : array_keys($branches);
        $allProducts = $this->product->getProducts($execution->project, 'noclosed', '', false, $linkedProductIdList);

        $productPlans     = $linkedBranches = $linkedBranchList = array();
        $linkedProducts   = $this->product->getProducts($execution->id, 'all', '', true, $linkedProductIdList, false);
        $plans            = $this->productplan->getGroupByProduct(array_keys($linkedProducts), 'skipparent|unexpired');
        $executionStories = $this->project->getStoriesByProject($execution->id);

        /* If the story of the product which linked the execution, you don't allow to remove the product. */
        $unmodifiableProducts = $unmodifiableBranches = $linkedStoryIDList = array();
        foreach($linkedProducts as $productID => $linkedProduct)
        {
            if(!isset($allProducts[$productID])) $allProducts[$productID] = $linkedProduct->deleted ? $linkedProduct->name . "({$this->lang->product->deleted})" : $linkedProduct->name;
            $productPlans[$productID] = array();

            foreach($branches[$productID] as $branchID => $branch)
            {
                if(isset($plans[$productID][$branchID]))
                {
                    foreach($plans[$productID][$branchID] as $plan) $productPlans[$productID][$plan->id] = $plan->title;
                }

                $linkedBranchList[$branchID]           = $branchID;
                $linkedBranches[$productID][$branchID] = $branchID;
                if($branchID != BRANCH_MAIN && isset($plans[$productID][BRANCH_MAIN]))
                {
                    foreach($plans[$productID][BRANCH_MAIN] as $plan) $productPlans[$productID][$plan->id] = $plan->title;
                }
                if(!empty($executionStories[$productID][$branchID]))
                {
                    array_push($unmodifiableProducts, $productID);
                    array_push($unmodifiableBranches, $branchID);
                    $linkedStoryIDList[$productID][$branchID] = $executionStories[$productID][$branchID]->storyIDList;
                }
            }
        }

        $linkedObjects = new stdclass();
        $linkedObjects->allProducts          = $allProducts;
        $linkedObjects->linkedProducts       = $linkedProducts;
        $linkedObjects->productPlans         = $productPlans;
        $linkedObjects->linkedBranches       = $linkedBranches;
        $linkedObjects->linkedBranchList     = $linkedBranchList;
        $linkedObjects->linkedStoryIDList    = $linkedStoryIDList;
        $linkedObjects->unmodifiableProducts = $unmodifiableProducts;
        $linkedObjects->unmodifiableBranches = $unmodifiableBranches;
        return $linkedObjects;
    }

    /**
     * 获取创建执行后的跳转地址。
     * Get location after create.
     *
     * @param  int       $projectID
     * @param  int       $executionID
     * @param  string    $model
     * @access protected
     * @return string
     */
    protected function getAfterCreateLocation(int $projectID, int $executionID, string $model = ''): string
    {
        if($this->app->tab == 'doc')  return $this->createLink('doc', 'projectSpace', "objectID=$executionID");
        if(!empty($_POST['plans']))   return inlink('create', "projectID=$projectID&executionID=$executionID&copyExecutionID=&planID=1&confirm=no");

        if(!empty($projectID) and $model == 'kanban')
        {
            if($this->app->tab == 'project') return $this->config->vision != 'lite' ? $this->createLink('project', 'index', "projectID=$projectID") : $this->createLink('project', 'execution', "status=all&projectID=$projectID");
            return inlink('kanban', "executionID=$executionID");
        }

        return inlink('create', "projectID=$projectID&executionID=$executionID");
    }
}

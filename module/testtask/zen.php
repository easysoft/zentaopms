<?php
declare(strict_types=1);
class testtaskZen extends testtask
{
    /**
     * 根据不同情况设置菜单。
     * Set menu according to different situations.
     *
     * @param  int        $productID
     * @param  int|string $branch
     * @param  int        $projectID
     * @param  int        $executionID
     * @access protected
     * @return void
     */
    protected function setMenu(int $productID, int|string $branch, int $projectID, int $executionID)
    {
        if($this->app->tab == 'project')
        {
            $this->view->projectID = $this->loadModel('project')->setMenu($projectID);
            return $this->view->projectID;
        }
        if($this->app->tab == 'execution')
        {
            $this->view->executionID = $this->loadModel('execution')->setMenu($executionID);
            return $this->view->executionID;
        }
        return $this->loadModel('qa')->setMenu($productID, $branch);
    }

    /**
     * 设置测试单用例列表页面搜索表单的参数。
     * Set congiruration of search form in cases page of testtask.
     *
     * @param  object    $product
     * @param  int       $moduleID
     * @param  int       $testtaskID
     * @param  int       $queryID
     * @access protected
     * @return void
     */
    protected function setSearchParamsForCases(object $product, int $moduleID, int $testtaskID, int $queryID): void
    {
        $this->loadModel('testcase');

        $searchConfig = $this->config->testcase->search;
        $searchConfig['module']    = 'testtask';
        $searchConfig['queryID']   = $queryID;
        $searchConfig['actionURL'] = helper::createLink('testtask', 'cases', "taskID=$testtaskID&browseType=bySearch&queryID=myQueryID");

        $searchConfig['params']['module']['values']  = $this->loadModel('tree')->getOptionMenu($product->id, 'case');
        $searchConfig['params']['lib']['values']     = $this->loadModel('caselib')->getLibraries();
        $searchConfig['params']['scene']['values']   = $this->testcase->getSceneMenu($product->id, $moduleID);
        $searchConfig['params']['status']['values']  = $this->lang->testcase->statusList;
        $searchConfig['params']['product']['values'] = array($product->id => $product->name, 'all' => $this->lang->testcase->allProduct);

        $searchConfig['fields']['assignedTo'] = $this->lang->testtask->assignedTo;
        $searchConfig['params']['assignedTo'] = array('operator' => '=', 'control' => 'select', 'values' => 'users');

        if(!$this->config->testcase->needReview) unset($searchConfig['params']['status']['values']['wait']);
        if($product->shadow) unset($searchConfig['fields']['product']);
        unset($searchConfig['fields']['branch']);
        unset($searchConfig['params']['branch']);

        $this->loadModel('search')->setSearchParams($searchConfig);
    }

    /**
     * 设置测试单关联用例页面搜索表单的参数。
     * Set congiruration of search form in linkCase page of testtask.
     *
     * @param  object    $product
     * @param  object    $task
     * @param  string    $type
     * @param  int       $param
     * @access protected
     * @return void
     */
    protected function setSearchParamsForLinkCase(object $product, object $task, string $type, int $param): void
    {
        $this->loadModel('testcase');

        $searchConfig = $this->config->testcase->search;
        $searchConfig['style']                       = 'simple';
        $searchConfig['actionURL']                   = inlink('linkcase', "taskID={$task->id}&type={$type}&param={$param}");
        $searchConfig['params']['module']['values']  = $this->loadModel('tree')->getOptionMenu($product->id, 'case', 0, $task->branch);
        $searchConfig['params']['scene']['values']   = $this->testcase->getSceneMenu($product->id);
        $searchConfig['params']['product']['values'] = array($product->id => $product->name);

        $build = $this->loadModel('build')->getByID((int)$task->build);
        if($build)
        {
            $searchConfig['params']['story']['values'] = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in($build->stories)->fetchPairs();
            $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story');
        }

        if($type != 'bystory')
        {
            unset($searchConfig['fields']['story']);
            unset($searchConfig['params']['story']);
        }
        if($product->shadow) unset($searchConfig['fields']['product']);
        if($product->type == 'normal')
        {
            unset($searchConfig['fields']['branch']);
            unset($searchConfig['params']['branch']);
        }
        else
        {
            $branchName = $this->loadModel('branch')->getById($task->branch);
            $branches   = array('' => '', BRANCH_MAIN => $this->lang->branch->main, $task->branch => $branchName);
            $searchConfig['fields']['branch'] = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
            $searchConfig['params']['branch']['values'] = $branches;
        }
        if(!$this->config->testcase->needReview) unset($searchConfig['params']['status']['values']['wait']);

        $this->loadModel('search')->setSearchParams($searchConfig);
    }

    /**
     * 构建编辑测试单的数据。
     * Build data for editing a testtask.
     *
     * @param  int       $taskID
     * @param  int       $productID
     * @access protected
     * @return object
     */
    protected function buildTaskForEdit(int $taskID, int $productID): object
    {
        $task = form::data($this->config->testtask->form->edit)
            ->add('id', $taskID)
            ->add('product', $productID)
            ->stripTags($this->config->testtask->editor->edit['id'], $this->config->allowedTags)
            ->get();

        /* Fix bug #35419. */
        $execution = $this->loadModel('execution')->getByID($task->execution);
        if(!$execution)
        {
            $build         = $this->loadModel('build')->getById($task->build);
            $task->project = $build->project;
        }
        else
        {
            $task->project = $execution->project;
        }

        $task->members = trim($task->members, ',');

        $task = $this->loadModel('file')->processImgURL($task, $this->config->testtask->editor->edit['id'], $this->post->uid);
        return $task;
    }

    /**
     * 构建开始测试单的数据。
     * Build data for starting a testtask.
     *
     * @param  int       $taskID
     * @access protected
     * @return object
     */
    protected function buildTaskForStart(int $taskID): object
    {
        $task = form::data($this->config->testtask->form->start)
            ->add('id', $taskID)
            ->stripTags($this->config->testtask->editor->start['id'], $this->config->allowedTags)
            ->get();
        return $this->loadModel('file')->processImgURL($task, $this->config->testtask->editor->start['id'], $task->uid);
    }

    /**
     * 构建关闭测试单的数据。
     * Build data for closing a testtask.
     *
     * @param  int       $taskID
     * @access protected
     * @return object
     */
    protected function buildTaskForClose(int $taskID): object
    {
        $task = form::data($this->config->testtask->form->close)
            ->add('id', $taskID)
            ->stripTags($this->config->testtask->editor->close['id'], $this->config->allowedTags)
            ->get();
        return $this->loadModel('file')->processImgURL($task, $this->config->testtask->editor->close['id'], $task->uid);
    }

    /**
     * 构建激活测试单的数据。
     * Build data for activating a testtask.
     *
     * @param  int       $taskID
     * @access protected
     * @return object
     */
    protected function buildTaskForActivate(int $taskID): object
    {
        $task = form::data($this->config->testtask->form->activate)
            ->add('id', $taskID)
            ->stripTags($this->config->testtask->editor->activate['id'], $this->config->allowedTags)
            ->get();
        return $this->loadModel('file')->processImgURL($task, $this->config->testtask->editor->activate['id'], $task->uid);
    }

    /**
     * 构建阻塞测试单的数据。
     * Build data for blocking a testtask.
     *
     * @param  int       $taskID
     * @access protected
     * @return object
     */
    protected function buildTaskForBlock(int $taskID): object
    {
        $task = form::data($this->config->testtask->form->block)
            ->add('id', $taskID)
            ->stripTags($this->config->testtask->editor->block['id'], $this->config->allowedTags)
            ->get();
        return $this->loadModel('file')->processImgURL($task, $this->config->testtask->editor->block['id'], $task->uid);
    }

    /**
     * 构建导入单元测试结果的数据。
     * Build data for importing unit test result.
     *
     * @param  int       $productID
     * @access protected
     * @return object
     */
    protected function buildTaskForImportUnitResult(int $productID): object
    {
        $task = form::data($this->config->testtask->form->importUnitResult)
            ->add('product', $productID)
            ->add('auto', 'unit')
            ->stripTags($this->config->testtask->editor->importunitresult['id'], $this->config->allowedTags)
            ->get();
        return $this->loadModel('file')->processImgURL($task, $this->config->testtask->editor->importunitresult['id'], $task->uid);
    }

    /**
     * 检查编辑的测试单数据是否符合要求。
     * Check data for editing a testtask.
     *
     * @param  object    $task
     * @access protected
     * @return void
     */
    protected function checkTaskForEdit(object $task): bool
    {
        $requiredErrors = array();
        /* Check required fields of editing task . */
        foreach(explode(',', $this->config->testtask->edit->requiredFields) as $requiredField)
        {
            if(!isset($task->{$requiredField}) || strlen(trim($task->{$requiredField})) == 0) $requiredErrors[$requiredField][] = sprintf($this->lang->error->notempty, isset($this->lang->testtask->{$requiredField}) ? $this->lang->testtask->$requiredField : $requiredField);
        }
        if(!empty($requiredErrors)) dao::$errors = $requiredErrors;

        if($task->end && $task->begin > $task->end) dao::$errors['end'][] = sprintf($this->lang->error->ge, $this->lang->testtask->end, $this->lang->testtask->begin);

        return !dao::isError();
    }

    /**
     * 分配变量给一个测试单的用例列表页。
     * Assign variables for cases page of a testtask.
     *
     * @param  object    $produc
     * @param  object    $testtask
     * @param  array     $runs
     * @param  int       $moduleID
     * @param  string    $browseType
     * @param  int       $param
     * @param  string    $orderBy
     * @param  object    $pager
     * @access protected
     * @return void
     */
    protected function assignForCases(object $product, object $testtask, array $runs, int $moduleID, string $browseType, int $param, string $orderBy, object $pager): void
    {
        $suites = $this->loadModel('testsuite')->getSuitePairs($product->id);

        /* Get assignedToList based on the execution. */
        $execution = $this->loadModel('execution')->getById($testtask->execution);
        if($execution and $execution->acl == 'private')
        {
            $assignedToList = $this->loadModel('user')->getTeamMemberPairs($execution->id, 'execution', 'nodeleted');
        }
        else
        {
            $assignedToList = $this->loadModel('user')->getPairs('noclosed|noletter|nodeleted|qafirst');
        }

        $this->setDropMenu($product->id, $testtask);
        $showModule = $this->loadModel('setting')->getItem("owner={$this->app->user->account}&module=testtask&section=cases&key=showModule");

        $this->view->title          = $product->name . $this->lang->colon . $this->lang->testtask->cases;
        $this->view->runs           = $this->loadModel('testcase')->appendData($runs, 'run');
        $this->view->users          = $this->loadModel('user')->getPairs('noclosed|qafirst|noletter');
        $this->view->moduleTree     = $this->loadModel('tree')->getTreeMenu($product->id, 'case', 0, array('treeModel', 'createTestTaskLink'), (string)$testtask->id, $testtask->branch ? $testtask->branch : '0');
        $this->view->automation     = $this->loadModel('zanode')->getAutomationByProduct($product->id);
        $this->view->suiteName      = $browseType == 'bysuite' ? zget($suites, $param, $this->lang->testtask->browseBySuite) : $this->lang->testtask->browseBySuite;
        $this->view->canBeChanged   = common::canBeChanged('testtask', $testtask);
        $this->view->assignedToList = $assignedToList;
        $this->view->suites         = $suites;
        $this->view->productID      = $product->id;
        $this->view->product        = $product;
        $this->view->task           = $testtask;
        $this->view->moduleID       = $moduleID;
        $this->view->browseType     = $browseType;
        $this->view->param          = $param;
        $this->view->orderBy        = $orderBy;
        $this->view->pager          = $pager;
        $this->view->branch         = $testtask->branch;
        $this->view->modulePairs    = $showModule ? $this->tree->getModulePairs($product->id, 'case', $showModule) : array();
    }

    /**
     * 分配变量给创建测试单页面。
     * Assign variables for creating page.
     *
     * @param  int       $productID$product
     * @param  int       $projectID
     * @param  int       $executionID
     * @param  int       $build
     * @access protected
     * @return void
     */
    protected function assignForCreate(int $productID, int $projectID, int $executionID, int $build): void
    {
        $objectType = 'project';
        $objectID   = $projectID;
        if($projectID)
        {
            /* 如果是无迭代项目，则获取影子迭代的迭代ID。*/
            /* If there is no sprint in the project, get the ID of the shadow sprint. */
            $project = $this->loadModel('project')->getByID($projectID);
            if($project && !$project->multiple) $this->view->noMultipleExecutionID = $this->loadModel('execution')->getNoMultipleID($project->id);
        }

        if($executionID)
        {
            /* 根据所选迭代的类型，调整表单字段的文本显示。*/
            /* Adjust the display value based on the type of sprint selected. */
            $execution = $this->loadModel('execution')->getByID($executionID);
            if(!empty($execution) && $execution->type == 'kanban') $this->lang->testtask->execution = str_replace($this->lang->execution->common, $this->lang->kanban->common, $this->lang->testtask->execution);
            $objectType = 'execution';
            $objectID   = $executionID;
        }

        /* 如果测试单所属产品在产品键值对中不存在，将其加入。*/
        /* Prepare the product key-value pairs. */
        if(!isset($this->products[$productID]))
        {
            $product = $this->loadModel('product')->getByID($productID);
            $this->products[$productID] = $product->name;
        }

        $this->view->title       = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->create;
        $this->view->product     = $this->loadModel('product')->getByID($productID);
        $this->view->executions  = $productID ? $this->product->getExecutionPairsByProduct($productID, '', $projectID, 'stagefilter') : array();
        $this->view->builds      = $productID ? $this->loadModel('build')->getBuildPairs(array($productID), 'all', 'notrunk,withexecution', $objectID, $objectType, '', false) : array();
        $this->view->testreports = $this->loadModel('testreport')->getPairs($productID);
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|qdfirst|nodeleted');
        $this->view->projectID   = $projectID;
        $this->view->executionID = $executionID;
        $this->view->build       = $build;
    }

    /**
     * 分配变量给编辑测试单页面。
     * Assign variables for editing page.
     *
     * @param  object    $task
     * @param  int       $productID
     * @access protected
     * @return void
     */
    protected function assignForEdit(object $task, int $productID): void
    {
        $this->loadModel('project');
        $productID   = $productID;
        $projectID   = $this->lang->navGroup->testtask == 'qa' ? 0 : $this->session->project;
        $executionID = $task->execution;
        $executions  = empty($productID) ? array() : $this->product->getExecutionPairsByProduct($productID, '0', $projectID);
        if($executionID && !isset($executions[$executionID]))
        {
            $execution = $this->loadModel('execution')->getById($executionID);
            $executions[$executionID] = $execution->name;
            if(empty($execution->multiple))
            {
                $project = $this->project->getById($execution->project);
                $executions[$executionID] = "{$project->name}({$this->lang->project->disableExecution})";
            }
        }

        if(!isset($this->products[$productID]))
        {
            $product = $this->loadModel('product')->getByID($productID);
            $this->products[$productID] = $product->name;
        }

        $this->view->title        = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->edit;
        $this->view->task         = $task;
        $this->view->project      = $this->project->getByID($projectID);
        $this->view->productID    = $productID;
        $this->view->executions   = $executions;
        $this->view->builds       = empty($productID) ? array() : $this->loadModel('build')->getBuildPairs(array($productID), 'all', 'noempty,notrunk,withexecution', $executionID ? $executionID : $task->project, $executionID ? 'execution' : 'project', $task->build, false);
        $this->view->testreports  = $this->loadModel('testreport')->getPairs($task->product, $task->testreport);
        $this->view->users        = $this->loadModel('user')->getPairs('nodeleted|noclosed', $task->owner);
        $this->view->contactLists = $this->user->getContactLists();
    }

    /**
     * 分配变量给一个测试用例的执行页面。
     * Assign variables for runCase page of a test case.
     *
     * @param  object    $run
     * @param  object    $preAndNext
     * @param  int       $runID
     * @param  int       $caseID
     * @param  int       $version
     * @param  string    $confirm
     * @access protected
     * @return void
     */
    protected function assignForRunCase(object $run, object $preAndNext, int $runID, int $caseID, int $version, string $confirm): void
    {
        $preLink  = '';
        $nextLink = '';
        if($preAndNext->pre && $this->app->tab != 'my')
        {
            $runID   = $runID ? $preAndNext->pre->id : 0;
            $caseID  = $runID ? $preAndNext->pre->case : $preAndNext->pre->id;
            $version = $preAndNext->pre->version;
            $preLink = inlink('runCase', "runID={$runID}&caseID={$caseID}&version={$version}");
        }
        if($preAndNext->next && $this->app->tab != 'my')
        {
            $runID    = $runID ? $preAndNext->next->id : 0;
            $caseID   = $runID ? $preAndNext->next->case : $preAndNext->next->id;
            $version  = $preAndNext->next->version;
            $nextLink = inlink('runCase', "runID={$runID}&caseID={$caseID}&version={$version}");
        }

        $this->view->title    = $this->lang->testtask->lblRunCase;
        $this->view->users    = $this->loadModel('user')->getPairs('noclosed, noletter');
        $this->view->run      = $run;
        $this->view->runID    = $runID;
        $this->view->caseID   = $caseID;
        $this->view->version  = $version;
        $this->view->confirm  = $confirm;
        $this->view->preLink  = $preLink;
        $this->view->nextLink = $nextLink;
    }

    /**
     * 根据不同情况获取产品键值对，大多用于1.5级导航。
     * Get product key-value pairs according to different situations.
     *
     * @access protected
     * @return array
     */
    protected function getProducts(): array
    {
        /* 如果是在非弹窗页面的项目或执行应用下打开的测试单，则获取当前项目或执行对应的产品。*/
        /* If the testtask is opened under a project or execution application on a non-pop-up page, get the current project or execution corresponding product. */
        $tab = $this->app->tab;
        if(!isonlybody() && ($tab == 'project' || $tab == 'execution')) return $this->loadModel('product')->getProducts($this->session->$tab, 'all', '', false);

        /* 如果是在弹窗页面或者测试应用下打开的测试单，则获取所有产品。*/
        /* If the testtask is opened on a pop-up page or test application, get all products. */
        return $this->loadModel('product')->getPairs('', 0, '', 'all');
    }

    /**
     * 统计不同状态测试单的数量，用于列表底部信息展示。
     * Count the number of testtasks in different statuses for information display at the bottom of the browse page.
     *
     * @param  array     $testtasks
     * @access protected
     * @return void
     */
    protected function prepareSummaryForBrowse(array $testtasks): void
    {
        $waitCount    = 0;
        $testingCount = 0;
        $blockedCount = 0;
        $doneCount    = 0;
        foreach($testtasks as $testtask)
        {
            if($testtask->status == 'wait')    $waitCount ++;
            if($testtask->status == 'doing')   $testingCount ++;
            if($testtask->status == 'blocked') $blockedCount ++;
            if($testtask->status == 'done')    $doneCount ++;
            if($testtask->build == 'trunk' || empty($testtask->buildName)) $testtask->buildName = $this->lang->trunk;
        }

        $this->view->allSummary  = sprintf($this->lang->testtask->allSummary, count($testtasks), $waitCount, $testingCount, $blockedCount, $doneCount);
        $this->view->pageSummary = sprintf($this->lang->testtask->pageSummary, count($testtasks));
    }

    /**
     * 获取批量执行的用例。
     * Get cases to run.
     *
     * @param  int       $productID
     * @param  string    $orderBy
     * @param  string    $from
     * @param  int       $testID
     * @param  string    $confirm
     * @param  array     $caseIdList
     * @access protected
     * @return array
     */
    protected function prepareCasesForBatchRun(int $productID, string $orderBy, string $from, int $taskID, string $confirm, array $caseIdList): array
    {
        $this->setMenu($productID, 0, (int)$this->session->project, (int)$this->session->execution);

        $cases = $this->dao->select('*')->from(TABLE_CASE)
            ->where('id')->in($caseIdList)
            ->beginIF($confirm == 'yes')->andWhere('auto')->ne('auto')->fi()
            ->orderBy($orderBy)
            ->fetchAll('id');
        if($from != 'testtask') return $cases;

        /* 如果批量执行的用例来自测试单，检查这些用例的版本，如果不是最新版就移除它们。*/
        /* If cases come from a testtask, check the version of these cases, if not the latest version, remove them. */
        $runs = $this->dao->select('`case`, version')->from(TABLE_TESTRUN)
            ->where('`case`')->in(array_keys($cases))
            ->andWhere('task')->eq($taskID)
            ->fetchPairs();
        foreach($cases as $caseID => $case)
        {
            if(isset($runs[$caseID]) && $runs[$caseID] < $case->version) unset($cases[$caseID]);
        }
        return $cases;
    }

    /**
     * 处理单元测试用例列表页面的测试用例的跨行合并属性供前端组件分页使用。
     * Process the rowspan property of cases for unitCases page for use by front-end component groupings.
     *
     * @param  array     $runs
     * @access protected
     * @return array
     */
    protected function processRowspanForUnitCases(array $runs): array
    {
        /* 将测试用例数据按照套件进行分组，方便按套件计算数量。 */
        /* Group test cases according to suites to facilitate calculation of quantities by suite. */
        $groupCases = array();
        foreach($runs as $run) $groupCases[$run->suite][] = $run;

        /* 将每个套件下的总用例数量赋予每个套件的第一条测试用例。*/
        /* Assign the total number of test cases under each suite to the first test case of each suite. */
        $suite = null;
        foreach($runs as $run)
        {
            $run->rowspan = 0;
            if($suite !== $run->suite && !empty($groupCases[$run->suite]))
            {
                $suite = $run->suite;
                $run->rowspan = count($groupCases[$run->suite]);
            }
        }
        return $runs;
    }

    /**
     * 处理分组查看页面的测试用例的跨行合并属性供前端组件分组使用。
     * Process the rowspan property of cases for groupCase page for use by front-end component groupings.
     *
     * @param  array     $cases
     * @param  string    $build
     * @access protected
     * @return array
     */
    protected function processRowspanForGroupCase(array $cases, string $build): array
    {
        $groupCases = array();
        foreach($cases as $case) $groupCases[$case->story][] = $case;

        if($build)
        {
            $buildStories = $this->dao->select('stories')->from(TABLE_BUILD)->where('id')->eq($build)->fetch('stories');
            $storyIdList  = array_filter(array_diff(explode(',', $buildStories), array_keys($groupCases)));
            if($storyIdList)
            {
                $stories = $this->dao->select('id,title')->from(TABLE_STORY)->where('deleted')->eq('0')->andWhere('id')->in($storyIdList)->fetchAll();
                foreach($stories as $story) $groupCases[$story->id][] = $story;
            }
        }

        /* 将每个需求的总用例数量赋予每个需求的第一条测试用例。*/
        /* Assign the total number of test cases under each story to the first test case of each story. */
        $story = null;
        foreach($cases as $case)
        {
            $case->rowspan = 0;
            if($story !== $case->story && !empty($groupCases[$case->story]))
            {
                $story = $case->story;
                $case->rowspan = count($groupCases[$case->story]);
            }
        }

        return $cases;
    }

    /**
     * 检测要执行的测试用例是否是自动化测试用例并根据用户确认结果执行不同的操作。
     * Detect whether the test case to be executed is an automated test case and perform different operations based on the user confirmation results.
     *
     * @param  object    $run
     * @param  int       $runID
     * @param  int       $caseID
     * @param  int       $version
     * @param  string    $confirm
     * @access protected
     * @return void
     */
    protected function checkAndExecuteAutomatedTest(object $run, int $runID, int $caseID, int $version, string $confirm)
    {
        /* 如果要执行的测试用例是自动化测试用例，并且设置了自动化测试的参数配置，并且用户尚未确认，则弹窗让用户确认。*/
        /* If the test case to be executed is an automated test case, and the parameter configuration of the automated test is set, and the user has not confirmed it, a pop-up window will pop up for the user to confirm. */
        $automation = $this->loadModel('zanode')->getAutomationByProduct($run->case->product);
        if($run->case->auto == 'auto'&& $automation && $confirm == '')
        {
            $confirmURL = inlink('runCase', "runID=$runID&caseID=$caseID&version=$version&confirm=yes");
            $cancelURL  = inlink('runCase', "runID=$runID&caseID=$caseID&version=$version&confirm=no");
            return $this->send(array('result' => 'fail', 'load' => array('confirm' => $this->lang->zanode->runCaseConfirm, 'confirmed' => $confirmURL, 'canceled' => $cancelURL)));
        }

        /* 用户确认后执行自动化测试的相关操作。*/
        /* Perform automated testing related operations after user confirmation. */
        if($confirm == 'yes')
        {
            $resultID = $this->testtask->initResultForAutomatedTest($runID, $caseID, $run->case->version, $automation->node);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError(), 'load' => $this->createLink('zanode', 'browse')));

            $this->zanode->runZTFScript($automation->id, $caseID, $resultID);
        }
    }

    /**
     * 根据测试用例的执行结果返回不同的内容。
     * Return different content based on the execution results of the test case.
     *
     * @param  string    $caseResult
     * @param  object    $preAndNext
     * @param  int       $runID
     * @param  int       $caseID
     * @param  int       $version
     * @access protected
     * @return void
     */
    protected function responseAfterRunCase(string $caseResult, object $preAndNext, int $runID, int $caseID, int $version)
    {
        if($caseResult == 'fail')
        {
            $link = inlink('results',"runID=$runID&caseID=$caseID&version=$version");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => "loadModal('$link', 'runCaseModal')"));
        }

        /* set cookie for ajax load caselist when close colorbox. */
        helper::setcookie('selfClose', 1, 0);

        if($preAndNext->next && $this->app->tab != 'my')
        {
            $nextRunID   = $runID ? $preAndNext->next->id : 0;
            $nextCaseID  = $runID ? $preAndNext->next->case : $preAndNext->next->id;
            $nextVersion = $preAndNext->next->version;
            $link        = inlink('runCase', "runID={$nextRunID}&caseID={$nextCaseID}&version={$nextVersion}");

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => "loadModal('$link', 'runCaseModal')"));
        }

        return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
    }

    /**
     * 设置测试单下拉。
     * Set testtask drop menu.
     *
     * @param  string    $caseResult
     * @param  object    $preAndNext
     * @param  int       $runID
     * @param  int       $caseID
     * @param  int       $version
     * @access protected
     * @return void
     */
    protected function setDropMenu(int $productID, object $task)
    {
        /* Set drop menu. */
        $objectType = $objectID = '';
        if(in_array($this->app->tab, array('project', 'execution')))
        {
            $objectType = $this->app->tab;
            $idField    = $objectType . 'ID';
            $objectID   = $task->{$this->app->tab};
            $this->view->{$idField}    = $objectID;
            $this->view->{$objectType} = $this->loadModel($objectType)->getByID($objectID);
        }
        $this->view->switcherParams   = "productID={$productID}&branch=&taskID={$task->id}&module=testtask&method={$this->app->rawMethod}&objectType={$objectType}&objectID={$objectID}";
        $this->view->switcherText     = $task->name;
        $this->view->switcherObjectID = $task->id;
    }
}

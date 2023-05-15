<?php
declare(strict_types=1);
class bugZen extends bug
{
    /**
     * 获取列表页面的 branch 参数。
     * Get browse branch param.
     *
     * @param  string    $branch
     * @param  string    $productType
     * @access protected
     * @return string
     */
    protected function getBrowseBranch(string $branch, string $productType): string
    {
        if($productType == 'normal') return 'all';

        if($this->cookie->preBranch !== '' && $branch === '') $branch = $this->cookie->preBranch;

        /* 如果是多分支产品时，设置分支的 cookie。*/
        /* Set branch cookie if product is multi-branch. */
        helper::setcookie('preBranch', $branch);

        return $branch;
    }

    /**
     * 处理列表页面的参数。
     * Processing browse params.
     *
     * @param  string    $browseType
     * @param  int       $param
     * @param  string    $orderBy
     * @param  int       $recTotal
     * @param  int       $recPerPage
     * @param  int       $pageID
     * @access protected
     * @return array
     */
    protected function prepareBrowseParams(string $browseType, int $param, string $orderBy, int $recTotal, int $recPerPage, int $pageID): array
    {
        /* 设置模块 ID。*/
        /* Set module id. */
        $moduleID = 0;
        if($this->cookie->bugModule)  $moduleID = $this->cookie->bugModule;
        if($browseType == 'bymodule') $moduleID = $param;

        /* 设置搜索查询 ID。*/
        /* Set query id. */
        $queryID = $browseType == 'bysearch' ? $param : 0;

        /* 设置 id 为第二排序规则。*/
        /* Append id for second sort rule. */
        $realOrderBy = common::appendOrder($orderBy);

        /* 加载分页器。*/
        /* Load pager. */
        $viewType = $this->app->getViewType();
        if($viewType == 'mhtml' || $viewType == 'xhtml') $recPerPage = 10;

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        return array($moduleID, $queryID, $realOrderBy, $pager);
    }

    /**
     * 设置浏览页面的 cookie。
     * Set cookie in browse view.
     *
     * @param  object    $product
     * @param  string    $branch
     * @param  string    $browseType
     * @param  int       $param
     * @param  string    $orderBy
     * @access protected
     * @return void
     */
    protected function setBrowseCookie(object $product, string $branch, string $browseType, int $param, string $orderBy): void
    {
        /* 如果产品或者分支变了，清空 bug 模块的 cookie。*/
        /* Clear cookie of bug module if the product or the branch is changed. */
        $productChanged = $this->cookie->preProductID != $product->id;
        $branchChanged  = $product->type != 'normal' && $this->cookie->preBranch != $branch;
        if($productChanged || $branchChanged) helper::setcookie('bugModule', '0', 0);

        /* 如果浏览类型为按模块浏览或者浏览类型为空，设置 bug 模块的 cookie 为当前模块，清空 bug 分支的 cookie。*/
        /* Set cookie of bug module and clear cookie of bug branch if browse type is by module or is empty. */
        if($browseType == 'bymodule' || $browseType == '')
        {
            helper::setcookie('bugModule', $param, 0);
            helper::setcookie('bugBranch', '0', 0);
        }

        /* 设置测试应用的 bug 排序 cookie。*/
        /* Set the cookie of bug order in qa. */
        helper::setcookie('qaBugOrder', $orderBy, 0);
    }

    /**
     * 设置浏览界面的 session。
     * Set session in browse view.
     *
     * @param  string    $browseType
     * @access protected
     * @return void
     */
    protected function setBrowseSession(string $browseType): void
    {
        /* 设置浏览方式的 session，记录刚刚是搜索还是按模块浏览。*/
        /* Set session of browse type. */
        if($browseType != 'bymodule') $this->session->set('bugBrowseType', $browseType);
        if(($browseType == 'bymodule') && $this->session->bugBrowseType == 'bysearch') $this->session->set('bugBrowseType', 'unclosed');

        $this->session->set('bugList', $this->app->getURI(true) . "#app={$this->app->tab}", 'qa');
    }

    /**
     * 设置列表页面的搜索表单。
     * Build browse search form.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $queryID
     * @access protected
     * @return void
     */
    protected function buildBrowseSearchForm(int $productID, string $branch, int $queryID): void
    {
        $this->config->bug->search['onMenuBar'] = 'yes';

        $actionURL      = $this->createLink('bug', 'browse', "productID=$productID&branch=$branch&browseType=bySearch&queryID=myQueryID");
        $searchProducts = $this->product->getPairs('', 0, '', 'all');

        $this->bug->buildSearchForm($productID, $searchProducts, $queryID, $actionURL, $branch);
    }

    /**
     * 获取列表页面的 bug 列表。
     * Get browse bugs.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  string    $browseType
     * @param  array     $executions
     * @param  int       $moduleID
     * @param  int       $queryID
     * @param  string    $orderBy
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function getBrowseBugs(int $productID, string $branch, string $browseType, array $executions, int $moduleID, int $queryID, string $orderBy, object $pager): array
    {
        $bugs = $this->bug->getList($browseType, (array)$productID, $this->projectID, $executions, $branch, $moduleID, $queryID, $orderBy, $pager);

        /* 把查询条件保存到 session。*/
        /* Process the sql, get the conditon partion, save it to session. */
        $this->loadModel('common')->saveQueryCondition($this->bug->dao->get(), 'bug', $browseType == 'needconfirm' ? false : true);

        /* 检查 bug 是否有过变更。*/
        /* Process bug for check story changed. */
        $bugs = $this->loadModel('story')->checkNeedConfirm($bugs);

        /* 处理 bug 的版本信息。*/
        /* Process the openedBuild and resolvedBuild fields. */
        return $this->bug->processBuildForBugs($bugs);
    }

    /**
     * 获取分支。
     * Get branch options.
     *
     * @param  int       $productID
     * @access protected
     * @return array
     */
    private function getBranchOptions(int $productID): array
    {
        $branches = $this->loadModel('branch')->getList($productID, 0, 'all');

        foreach($branches as $branchInfo)
        {
            $branchOption[$branchInfo->id]    = $branchInfo->name;
            $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
        }

        return array($branchOption, $branchTagOption);
    }

    /**
     * 获取浏览页面所需的变量, 并输出到前台。
     * Get the data required by the browse page and output.
     *
     * @param  array     $bugs
     * @param  object    $product
     * @param  string    $branch
     * @param  string    $browseType
     * @param  int       $param
     * @param  int       $moduleID
     * @param  array     $executions
     * @param  string    $orderBy
     * @param  object    $pager
     * @access protected
     * @return void
     */
    protected function buildBrowseView(array $bugs, object $product, string $branch, string $browseType, int $param, int $moduleID, array $executions, string $orderBy, object $pager): void
    {
        $this->loadModel('datatable');

        /* 获取分支列表。*/
        /* Get branch options. */
        $showBranch      = false;
        $branchOption    = array();
        $branchTagOption = array();
        if($product->type != 'normal')
        {
            $showBranch = $this->loadModel('branch')->showBranch($product->id);

            list($branchOption, $branchTagOption) = $this->getBranchOptions($product->id);
        }

        /* 获取需求和任务的 id 列表。*/
        /* Get story and task id list. */
        $storyIdList = $taskIdList = array();
        foreach($bugs as $bug)
        {
            if($bug->story)  $storyIdList[$bug->story] = $bug->story;
            if($bug->task)   $taskIdList[$bug->task]   = $bug->task;
            if($bug->toTask) $taskIdList[$bug->toTask] = $bug->toTask;
        }

        $showModule = !empty($this->config->datatable->bugBrowse->showModule) ? $this->config->datatable->bugBrowse->showModule : '';

        /* Set view. */
        $this->view->title           = $product->name . $this->lang->colon . $this->lang->bug->common;
        $this->view->product         = $product;
        $this->view->branch          = $branch;
        $this->view->browseType      = $browseType;
        $this->view->param           = $param;
        $this->view->currentModuleID = $moduleID;
        $this->view->moduleName      = $moduleID ? $this->tree->getByID($moduleID)->name : $this->lang->tree->all;
        $this->view->modulePairs     = $showModule ? $this->tree->getModulePairs($product->id, 'bug', $showModule) : array();
        $this->view->modules         = $this->tree->getOptionMenu($product->id, $viewType = 'bug', $startModuleID = 0, $branch);
        $this->view->moduleTree      = $this->tree->getTreeMenu($product->id, 'bug', 0, array('treeModel', 'createBugLink'), '', $branch);
        $this->view->bugs            = $bugs;
        $this->view->summary         = $this->bug->summary($bugs);
        $this->view->branchOption    = $branchOption;
        $this->view->branchTagOption = $branchTagOption;
        $this->view->projectPairs    = $this->loadModel('project')->getPairsByProgram();
        $this->view->executions      = $executions;
        $this->view->builds          = $this->loadModel('build')->getBuildPairs($product->id, $branch);
        $this->view->releasedBuilds  = $this->loadModel('release')->getReleasedBuilds($product->id, $branch);
        $this->view->plans           = $this->loadModel('productplan')->getPairs($product->id);
        $this->view->stories         = $this->loadModel('story')->getByList($storyIdList);
        $this->view->tasks           = $this->loadModel('task')->getByList($taskIdList);
        $this->view->users           = $this->user->getPairs('noletter');
        $this->view->memberPairs     = $this->user->getPairs('noletter|noclosed');
        $this->view->pager           = $pager;
        $this->view->orderBy         = $orderBy;

        $this->display();
    }

    /**
     * 使用表单数据构造一个bug对象。
     * Prepare a bug object from form data.
     *
     * @param  object    $data
     * @param  string    $uid
     * @access protected
     * @return object
     */
    protected function prepareCreateExtras(object $data, string $uid): object
    {
        $now = helper::now();
        $bug = $data->setDefault('openedBy', $this->app->user->account)
            ->setDefault('openedDate', $now)
            ->setIF($this->lang->navGroup->bug != 'qa', 'project', $this->session->project)
            ->setIF($data->data->assignedTo != '', 'assignedDate', $now)
            ->setIF($data->data->story !== false, 'storyVersion', $this->loadModel('story')->getVersion($data->data->story))
            ->setIF(strpos($this->config->bug->create->requiredFields, 'deadline') !== false, 'deadline', $data->data->deadline)
            ->setIF(strpos($this->config->bug->create->requiredFields, 'execution') !== false, 'execution', $data->data->execution)
            ->stripTags($this->config->bug->editor->create['id'], $this->config->allowedTags)
            ->cleanInt('product,execution,module,severity')
            ->remove('files,labels,uid,oldTaskID,contactListMenu,region,lane,ticket,deleteFiles,resultFiles')
            ->get();

        if(empty($bug->deadline)) unset($bug->deadline);

        return $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->create['id'], $uid);
    }

    /**
     * 检查bug是否已经存在。
     * Check whether bug is exist.
     *
     * @param  object    $bug
     * @access protected
     * @return array
     */
    protected function checkExistBug(object $bug): array
    {
        /* Check repeat bug. */
        $result = $this->loadModel('common')->removeDuplicate('bug', $bug, "product={$bug->product}");
        if($result and $result['stop']) return array('status' => 'exists', 'id' => $result['duplicate']);

        return array('status' => 'success');
    }

    /**
     * 创建bug后存储上传的文件。
     * Save files after create a bug.
     *
     * @param  int       $bugID
     * @param  object    $rawdata
     * @access protected
     * @return void
     */
    protected function updateFileAfterCreate(int $bugID, object $rawdata): void
    {
        if(isset($rawdata->resultFiles))
        {
            $resultFiles = $rawdata->resultFiles;
            if(isset($rawdata->deleteFiles))
            {
                foreach($rawdata->deleteFiles as $deletedCaseFileID) $resultFiles = trim(str_replace(",$deletedCaseFileID,", ',', ",$resultFiles,"), ',');
            }
            $files = $this->dao->select('*')->from(TABLE_FILE)->where('id')->in($resultFiles)->fetchAll('id');
            foreach($files as $file)
            {
                unset($file->id);
                $file->objectType = 'bug';
                $file->objectID   = $bugID;
                $this->dao->insert(TABLE_FILE)->data($file)->exec();
            }
        }

        $this->file->updateObjectID($rawdata->uid, $bugID, 'bug');
        $this->file->saveUpload('bug', $bugID);
    }

    /**
     * 通过$_POST的值和解析出来的$output，获得看板的laneID和columnID。
     * Get kanban laneID and columnID from $_POST and $output from extra().
     *
     * @param  object    $rawdata
     * @param  array     $output
     * @access protected
     * @return array
     */
    protected function getKanbanVariable(object $rawdata, array $output): array
    {
        $laneID = isset($output['laneID']) ? $output['laneID'] : 0;
        if(!empty($rawdata->lane)) $laneID = $rawdata->lane;

        $columnID = $this->loadModel('kanban')->getColumnIDByLaneID($laneID, 'unconfirmed');
        if(empty($columnID)) $columnID = isset($output['columnID']) ? $output['columnID'] : 0;

        return array($laneID, $columnID);
    }

    /**
     * 创建bug后更新执行看板。
     * Update execution kanban after create a bug.
     *
     * @param  object $bug
     * @param  int       $laneID
     * @param  int       $columnID
     * @param  string    $from
     * @access protected
     * @return void
     */
    protected function updateKanbanAfterCreate(object $bug, int $laneID, int $columnID, string $from): void
    {
        $bugID       = $bug->id;
        $executionID = $bug->execution;

        if($executionID)
        {
            $this->loadModel('kanban');

            if(!empty($laneID) and !empty($columnID)) $this->kanban->addKanbanCell($executionID, $laneID, $columnID, 'bug', $bugID);
            if(empty($laneID) or empty($columnID)) $this->kanban->updateLane($executionID, 'bug');
        }

        /* Callback the callable method to process the related data for object that is transfered to bug. */
        if($from && is_callable(array($this, $this->config->bug->fromObjects[$from]['callback']))) call_user_func(array($this, $this->config->bug->fromObjects[$from]['callback']), $bugID);
    }


    /**
     * 处理更新请求数据。
     * Processing request data.
     *
     * @param  form         $formData
     * @param  object       $oldBug
     * @access protected
     * @return object|false
     */
    protected function prepareEditExtras(form $formData, object $oldBug): object|false
    {
        if($oldBug->lastEditedDate != $formData->data->lastEditedDate)
        {
            dao::$errors[] = $this->lang->error->editedByOther;
            return false;
        }

        $now = helper::now();
        $bug = $formData->add('id', $oldBug->id)
            ->setDefault('product', $oldBug->product)
            ->setDefault('deleteFiles', array())
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->join('openedBuild,mailto,linkBug,os,browser', ',')
            ->setIF($formData->data->assignedTo  != $oldBug->assignedTo, 'assignedDate', $now)
            ->setIF($formData->data->resolvedBy  != '' && $formData->data->resolvedDate == '', 'resolvedDate', $now)
            ->setIF($formData->data->resolution  != '' && $formData->data->resolvedDate == '', 'resolvedDate', $now)
            ->setIF($formData->data->resolution  != '' && $formData->data->resolvedBy   == '', 'resolvedBy',   $this->app->user->account)
            ->setIF($formData->data->closedDate  != '' && $formData->data->closedBy     == '', 'closedBy',     $this->app->user->account)
            ->setIF($formData->data->closedBy    != '' && $formData->data->closedDate   == '', 'closedDate',   $now)
            ->setIF($formData->data->closedBy    != '' || $formData->data->closedDate   != '', 'assignedTo',   'closed')
            ->setIF($formData->data->closedBy    != '' || $formData->data->closedDate   != '', 'assignedDate', $now)
            ->setIF($formData->data->resolution  != '' || $formData->data->resolvedDate != '', 'status',       'resolved')
            ->setIF($formData->data->closedBy    != '' || $formData->data->closedDate   != '', 'status',       'closed')
            ->setIF(($formData->data->resolution != '' || $formData->data->resolvedDate != '') && $formData->data->assignedTo == '', 'assignedTo', $oldBug->openedBy)
            ->setIF(($formData->data->resolution != '' || $formData->data->resolvedDate != '') && $formData->data->assignedTo == '', 'assignedDate', $now)
            ->setIF($formData->data->resolution  == '' && $formData->data->resolvedDate == '', 'status', 'active')
            ->setIF($formData->data->resolution  != '' && $formData->data->resolution   != 'duplicate', 'duplicateBug', 0)
            ->setIF($formData->data->assignedTo  == '' && $oldBug->status               == 'closed', 'assignedTo', 'closed')
            ->setIF($formData->data->resolution  != '', 'confirmed', 1)
            ->setIF($formData->data->story && $formData->data->story != $oldBug->story, 'storyVersion', $this->loadModel('story')->getVersion($formData->data->story))
            ->stripTags($this->config->bug->editor->edit['id'], $this->config->allowedTags)
            ->get();

        $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->create['id'], $bug->uid);

        return $bug;
    }

    /**
     * 返回错误信息。
     * return error.
     *
     * @access protected
     * @return array
     */
    protected function errorEdit(): array
    {
        if(defined('RUN_MODE') && RUN_MODE == 'api') return array('status' => 'error', 'message' => dao::getError());

        return array('result' => 'fail', 'message' => dao::getError());
    }

    /**
     * 更新成功后的相关处理。
     * Relevant processing after updating bug.
     *
     * @param  int       $bugID
     * @param  string    $comment
     * @param  array     $changes
     * @access protected
     * @return void
     */
    protected function processAfterEdit(int $bugID, string $comment, array $changes): void
    {
        if($this->post->comment || !empty($changes))
        {
            $action   = !empty($changes) ? 'Edited' : 'Commented';
            $actionID = $this->action->create('bug', $bugID, $action, $comment);

            $this->action->logHistory($actionID, $changes);
        }
    }

    /**
     * 返回不同的结果。
     * Respond after updating bug.
     *
     * @param  int       $bugID
     * @param  array     $changes
     * @param  string    $kanbanGroup
     * @param  int       $regionID
     * @access protected
     * @return array
     */
    protected function responseAfterOperate(int $bugID, array $changes, string $kanbanGroup = '', int $regionID = 0): array
    {
        if(defined('RUN_MODE') && RUN_MODE == 'api') return array('status' => 'success', 'data' => $bugID);

        /* 如果 bug 转任务并且 bug 的状态发生变化，提示是否更新任务状态。*/
        /* This bug has been converted to a task, update the status of the related task or not. */
        $bug = $this->bug->getByID($bugID);
        if($bug->toTask and !empty($changes))
        {
            foreach($changes as $change)
            {
                if($change['field'] != 'status') continue;

                $confirmedURL = $this->createLink('task', 'view', "taskID=$bug->toTask");
                $canceledURL  = $this->server->http_referer;
                return array('result' => 'success', 'load' => array('confirm' => $this->lang->bug->remindTask, 'confirmed' => $confirmedURL, 'canceled' => $canceledURL));
            }
        }

        /* 在弹窗里编辑 bug 时的返回。*/
        /* Respond after updating in modal. */
        if(isonlybody()) $this->responseInModal($bug->execution, $kanbanGroup, $regionID);

        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('bug', 'view', "bugID=$bugID"));
    }

    /**
     * 在弹窗中操作后的返回。
     * Respond after operating in modal.
     *
     * @param  int       $executionID
     * @param  string    $kanbanGroup
     * @param  int       $regionID
     * @access protected
     * @return array
     */
    protected function responseInModal(int $executionID, string $kanbanGroup = '', int $regionID = 0): array
    {
        /* 在执行应用下，编辑看板中的 bug 数据时，更新看板数据。*/
        /* Update kanban data after updating bug in kanban. */
        if($this->app->tab == 'execution')
        {
            $this->loadModel('kanban');

            $execution = $this->loadModel('execution')->getByID($executionID);
            $laneType  = $this->session->executionLaneType ? $this->session->executionLaneType : 'all';
            $groupBy   = $this->session->executionGroupBy ? $this->session->executionGroupBy : 'default';

            /* 看板类型的执行。*/
            /* The kanban exectuion. */
            if(isset($execution->type) && $execution->type == 'kanban')
            {
                $groupBy       = $kanbanGroup ? $kanbanGroup : $groupBy;
                $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                $kanbanData    = $this->kanban->getRDKanban($executionID, $laneType, 'id_desc', $regionID, $groupBy, $rdSearchValue);
                $kanbanData    = json_encode($kanbanData);
                return array('result' => 'success', 'closeModal' => true, 'callback' => "updateKanban($kanbanData)");
            }

            /* 执行中的看板。*/
            /* The kanban of execution. */
            $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
            $kanbanData      = $this->kanban->getExecutionKanban($executionID, $laneType, $groupBy, $taskSearchValue);
            $kanbanType      = $laneType == 'all' ? 'bug' : key($kanbanData);
            $kanbanData      = json_encode($kanbanData[$kanbanType]);
            return array('result' => 'success', 'closeModal' => true, 'callback' => "updateKanban(\"bug\", $kanbanData)");
        }

        return array('result' => 'success', 'closeModal' => true, 'load' => true);
    }

    /**
     * 为create方法添加动态。
     * Add action for create function.
     *
     * @param  object    $bug
     * @param  array     $output
     * @param  string    $from
     * @access protected
     * @return void
     */
    protected function addAction4Create(object $bug, array $output, string $from): void
    {
        $bugID    = $bug->id;
        $todoID   = isset($output['todoID']) ? $output['todoID'] : 0;

        $action   = $from == 'sonarqube' ? 'fromSonarqube' : 'Opened';
        $this->action->create('bug', $bugID, $action);

        /* Add score for create. */
        if(empty($bug->case))
        {
            $this->loadModel('score')->create('bug', 'create', $bugID);
        }
        else
        {
            $this->loadModel('score')->create('bug', 'createFormCase', $bug->case);
        }

        if(!$todoID) return;
        $this->dao->update(TABLE_TODO)->set('status')->eq('done')->where('id')->eq($todoID)->exec();
        $this->action->create('todo', $todoID, 'finished', '', "BUG:$bugID");
        if($this->config->edition == 'biz' || $this->config->edition == 'max')
        {
            $todo = $this->dao->select('type, idvalue')->from(TABLE_TODO)->where('id')->eq($todoID)->fetch();
            if($todo->type == 'feedback' && $todo->idvalue) $this->loadModel('feedback')->updateStatus('todo', $todo->idvalue, 'done');
        }
    }

    /**
     * 获得create方法的response。
     * Get response for create.
     *
     * @param  int      $bugID
     * @param  int      $executionID
     * @param  array    $output
     * @access protected
     * @return array
     */
    protected function responseAfterCreate(int $bugID, int $executionID, array $output): array
    {
        /* Return bug id when call the API. */
        if($this->viewType == 'json') return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $bugID);
        if(defined('RUN_MODE') && RUN_MODE == 'api') return array('status' => 'success', 'data' => $bugID);

        if(isonlybody()) return $this->responseInModal($executionID);

        $location = $this->getLocation4Create($bugID, $executionID, $output);
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $location);
    }

    /**
     * 获得create方法的返回url。
     * Get response url for create.
     *
     * @param  int       $bugID
     * @param  int       $executionID
     * @param  string    $branch
     * @access protected
     * @return string
     */
    protected function getLocation4Create(int $bugID, int $executionID, array $output): string
    {
        $bug = $this->bug->getByID($bugID);

        if($this->app->tab == 'execution')
        {
            if(!preg_match("/(m=|\/)execution(&f=|-)bug(&|-|\.)?/", $this->session->bugList))
            {
                $location = $this->session->bugList;
            }
            else
            {
                $location = $this->createLink('execution', 'bug', "executionID=$executionID");
            }

        }
        elseif($this->app->tab == 'project')
        {
            $location = $this->createLink('project', 'bug', "projectID=" . zget($output, 'projectID', $this->session->project));
        }
        else
        {
            helper::setcookie('bugModule', '0', 0);
            $location = $this->createLink('bug', 'browse', "productID={$bug->product}&branch=$bug->branch&browseType=byModule&param={$bug->module}&orderBy=id_desc");
        }
        if($this->app->getViewType() == 'xhtml') $location = $this->createLink('bug', 'view', "bugID=$bugID", 'html');

        return $location;
    }

    /**
     * 初始化一个默认的bug模板。
     * Init a default bug templete.
     *
     * @param  array     $fields
     * @access protected
     * @return object
     */
    protected function initBug($fields): object
    {
        $bug = new stdclass();
        $bug->projectID   = 0;
        $bug->moduleID    = 0;
        $bug->executionID = 0;
        $bug->productID   = 0;
        $bug->taskID      = 0;
        $bug->storyID     = 0;
        $bug->buildID     = 0;
        $bug->caseID      = 0;
        $bug->runID       = 0;
        $bug->testtask    = 0;
        $bug->version     = 0;
        $bug->title       = '';
        $bug->steps       = $this->lang->bug->tplStep . $this->lang->bug->tplResult . $this->lang->bug->tplExpect;
        $bug->os          = '';
        $bug->browser     = '';
        $bug->assignedTo  = '';
        $bug->deadline    = '';
        $bug->mailto      = '';
        $bug->keywords    = '';
        $bug->severity    = 3;
        $bug->type        = 'codeerror';
        $bug->pri         = 3;
        $bug->color       = '';
        $bug->feedbackBy  = '';
        $bug->notifyEmail = '';

        $bug->project      = '';
        $bug->branch       = '';
        $bug->execution    = '';
        $bug->projectModel = '';
        $bug->projects   = array();
        $bug->executions = array();
        $bug->products   = array();
        $bug->stories    = array();
        $bug->builds     = array();
        $bug->branches   = array();

        if(!empty($fields)) $bug = $this->updateBug($bug, $fields);

        return $bug;
    }

    /**
     * 更新bug模板。
     * Update bug templete.
     *
     * @param  object    $bug
     * @param  array     $fields
     * @access protected
     * @return object
     */
    protected function updateBug(object $bug, array $fields): object
    {
        foreach($fields as $field => $value) $bug->$field = $value;

        return $bug;
    }

    /**
     * 获取模块下拉菜单，如果是空的，则返回到模块维护页面。
     * Get moduleOptionMenu, if moduleOptionMenu is empty, return tree-browse.
     *
     * @param  object    $bug
     * @param  object    $currentProduct
     * @access protected
     * @return object
     */
    protected function setOptionMenu(object $bug, object $currentProduct): object
    {
        $bug = $this->getBranches4Create($bug, $currentProduct);
        $moduleOptionMenu = $this->tree->getOptionMenu($bug->productID, 'bug', 0, ($bug->branch === 'all' or !isset($bug->branches[$bug->branch])) ? 0 : $bug->branch);
        if(empty($moduleOptionMenu)) return print(js::locate(helper::createLink('tree', 'browse', "productID={$bug->productID}&view=story")));

        $this->view->moduleOptionMenu = $moduleOptionMenu;

        return $bug;
    }


    /**
     * 解析extras，如果bug来源于某个对象 (bug, case, testtask, todo) ，使用对象的一些属性对bug赋值。
     * Extract extras, if bug come from an object(bug, case, testtask, todo), get some value from object.
     *
     * @param  object    $bug
     * @param  array     $output
     * @access protected
     * @return object
     */
    protected function extractObjectFromExtras(object $bug, array $output): object
    {
        extract($output);

        /* 获取用例的标题、步骤、所属需求、所属模块、版本、所属执行。 */
        /* Get title, steps, storyID, moduleID, version, executionID from case. */
        if(isset($runID) and $runID and isset($resultID) and $resultID)
        {
            $fields = $this->bug->getBugInfoFromResult($resultID, 0, 0, isset($stepIdList) ? $stepIdList : '');// If set runID and resultID, get the result info by resultID as template.
            $bug    = $this->updateBug($bug, $fields);
        }
        if(isset($runID) and !$runID and isset($caseID) and $caseID)
        {
            $fields = $this->bug->getBugInfoFromResult($resultID, $caseID, $version, isset($stepIdList) ? $stepIdList : '');// If not set runID but set caseID, get the result info by resultID and case info.
            $bug    = $this->updateBug($bug, $fields);
        }

        /* 获得bug的所属项目、所属模块、所属执行、关联产品、关联任务、关联需求、关联版本、关联用例、标题、步骤、严重程度、类型、指派给、截止日期、操作系统、浏览器、抄送给、关键词、颜色、所属测试单、反馈人、通知邮箱、优先级。 */
        /* Get projectID, moduleID, executionID, productID, taskID, storyID, buildID, caseID, title, steps, severity, type, assignedTo, deadline, os, browser, mailto, keywords, color, testtask, feedbackBy, notifyEmail, pri from case. */
        if(isset($bugID) and $bugID)
        {
            $bugInfo = $this->bug->getById((int)$bugID);

            $fields = array('projectID' => $bugInfo->project, 'moduleID' => $bugInfo->module, 'executionID' => $bugInfo->execution, 'productID' => $bugInfo->product, 'taskID' => $bugInfo->task, 'storyID' => $bugInfo->story, 'buildID' => $bugInfo->openedBuild,
                'caseID' => $bugInfo->case, 'title' => $bugInfo->title, 'steps' => $bugInfo->steps, 'severity' => $bugInfo->severity, 'type' => $bugInfo->type, 'assignedTo' => $bugInfo->assignedTo, 'deadline' => (helper::isZeroDate($bugInfo->deadline) ? '' : $bugInfo->deadline),
                'os' => $bugInfo->os, 'browser' => $bugInfo->browser, 'mailto' => $bugInfo->mailto, 'keywords' => $bugInfo->keywords, 'color' => $bugInfo->color, 'testtask' => $bugInfo->testtask, 'feedbackBy' => $bugInfo->feedbackBy, 'notifyEmail' => $bugInfo->notifyEmail,
                'pri' => ($bugInfo->pri == 0 ? 3 : $bugInfo->pri));

            $bug = $this->updateBug($bug, $fields);
        }

        /* 获取测试单的版本。 */
        /* Get buildID from testtask. */
        if(isset($testtask) and $testtask)
        {
            $testtask = $this->loadModel('testtask')->getById((int)$testtask);
            $bug      = $this->updateBug($bug, array('buildID' => $testtask->build));
        }

        /* 获得代办的标题、步骤和优先级。 */
        /* Get title, steps, pri from todo. */
        if(isset($todoID) and $todoID)
        {
            $todo = $this->loadModel('todo')->getById((int)$todoID);
            $bug  = $this->updateBug($bug, array('title' => $todo->name, 'steps' => $todo->desc, 'pri' => $todo->pri));
        }

        return $bug;
    }

    /**
     *
     * 构建创建bug页面数据。
     * Build form fields for create bug.
     *
     * @param  object    $bug
     * @param  array     $output
     * @param  string    $from
     * @access protected
     * @return void
     */
    protected function buildCreateForm(object $bug, array $output, string $from): void
    {
        extract($output);
        $currentProduct = $this->product->getByID($bug->productID);

        /* 获得版本下拉和需求下拉列表。 */
        /* Get builds and stroies. */
        $bug = $this->getBuildsAndStories4Create($bug);
        /* 如果bug有所属项目，查询这个项目。 */
        /* Get project. */
        if($bug->projectID) $bug = $this->updateBug($bug, array('project' => $this->loadModel('project')->getByID($projectID)));
        /* 获得产品下拉和项目下拉列表。 */
        /* Get products and projects. */
        $bug = $this->getProductsAndProjects4Create($bug);
        /* 追加下拉列表的内容。 */
        /* Append projects. */
        $bug = $this->appendProjects4Create($bug, (isset($bugID) ? $bugID : 0));
        /* 获得项目的管理方式。 */
        /* Get project model. */
        $bug = $this->getProjectModel4Create($bug);
        /* 获得执行下拉列表。 */
        /* Get executions. */
        $bug = $this->getExecutions4Create($bug);

        $this->extractBugTemplete($bug);

        $this->view->title        = isset($this->products[$bug->productID]) ? $this->products[$bug->productID] . $this->lang->colon . $this->lang->bug->create : $this->lang->bug->create;
        $this->view->customFields = $this->getCustomFields4Create();
        $this->view->showFields   = $this->config->bug->custom->createFields;

        $this->view->productMembers        = $this->getProductMembers4Create($bug);
        $this->view->gobackLink            = (isset($output['from']) and $output['from'] == 'global') ? $this->createLink('bug', 'browse', "productID=$bug->productID") : '';
        $this->view->productName           = isset($this->products[$bug->productID]) ? $this->products[$bug->productID] : '';
        $this->view->projectExecutionPairs = $this->loadModel('project')->getProjectExecutionPairs();
        $this->view->releasedBuilds        = $this->loadModel('release')->getReleasedBuilds($bug->productID, $bug->branch);
        $this->view->resultFiles           = (!empty($resultID) and !empty($stepIdList)) ? $this->loadModel('file')->getByObject('stepResult', $resultID, str_replace('_', ',', $stepIdList)) : array();
        $this->view->product               = $currentProduct;
        $this->view->blockID               = $this->getBlockID4Create();
        $this->view->issueKey              = $from == 'sonarqube' ? $output['sonarqubeID'] . ':' . $output['issueKey'] : '';

        $this->display();
    }

    /**
     * 将$bug对象的属性添加到view对象中。
     * Add the prop of the $bug object to the view object.
     *
     * @param  object    $bug
     * @access protected
     * @return void
     */
    protected function extractBugTemplete(object $bug): void
    {
        $this->view->projectID   = $bug->projectID;
        $this->view->moduleID    = $bug->moduleID;
        $this->view->productID   = $bug->productID;
        $this->view->products    = $bug->products;
        $this->view->stories     = $bug->stories;
        $this->view->projects    = defined('TUTORIAL') ? $this->loadModel('tutorial')->getProjectPairs()   : $bug->projects;
        $this->view->executions  = defined('TUTORIAL') ? $this->loadModel('tutorial')->getExecutionPairs() : $bug->executions;
        $this->view->builds      = $bug->builds;
        $this->view->execution   = $bug->execution;
        $this->view->taskID      = $bug->taskID;
        $this->view->storyID     = $bug->storyID;
        $this->view->buildID     = $bug->buildID;
        $this->view->caseID      = $bug->caseID;
        $this->view->runID       = $bug->runID;
        $this->view->version     = $bug->version;
        $this->view->testtask    = $bug->testtask;
        $this->view->bugTitle    = $bug->title;
        $this->view->pri         = $bug->pri;
        $this->view->steps       = htmlSpecialString($bug->steps);
        $this->view->os          = $bug->os;
        $this->view->browser     = $bug->browser;
        $this->view->assignedTo  = $bug->assignedTo;
        $this->view->deadline    = $bug->deadline;
        $this->view->mailto      = $bug->mailto;
        $this->view->keywords    = $bug->keywords;
        $this->view->severity    = $bug->severity;
        $this->view->type        = $bug->type;
        $this->view->branch      = $bug->branch;
        $this->view->branches    = $bug->branches;
        $this->view->color       = $bug->color;
        $this->view->feedbackBy  = $bug->feedbackBy;
        $this->view->notifyEmail = $bug->notifyEmail;

        $this->view->projectModel    = $bug->projectModel;
        $this->view->stepsRequired   = strpos($this->config->bug->create->requiredFields, 'steps');
        $this->view->isStepsTemplate = $bug->steps == $this->lang->bug->tplStep . $this->lang->bug->tplResult . $this->lang->bug->tplExpect ? true : false;
    }

    /**
     * 获取bug创建页面的branches和branch，并绑定到bug上。
     * Get the branches and branch for the bug create page and bind them to bug.
     *
     * @param  object    $bug
     * @param  object    $currentProduct
     * @access protected
     * @return object
     */
    protected function getBranches4Create(object $bug, object $currentProduct): object
    {
        $productID = $bug->productID;
        $branch    = $bug->branch;

        if($this->app->tab == 'execution' or $this->app->tab == 'project')
        {
            $objectID        = $this->app->tab == 'project' ? $bug->projectID : $bug->executionID;
            $productBranches = $currentProduct->type != 'normal' ? $this->loadModel('execution')->getBranchByProduct($productID, $objectID, 'noclosed|withMain') : array();
            $branches        = isset($productBranches[$productID]) ? $productBranches[$productID] : array();
            $branch          = key($branches);
        }
        else
        {
            $branches = $currentProduct->type != 'normal' ? $this->loadModel('branch')->getPairs($productID, 'active') : array();
        }

        return $this->updateBug($bug, array('branches' => $branches, 'branch' => $branch));
    }

    /**
     * 获取bug创建页面的builds和stories，并绑定到bug上。
     * Get the builds and stories for the bug create page and bind them to bug.
     *
     * @param  object    $bug
     * @access protected
     * @return object
     */
    protected function getBuildsAndStories4Create(object $bug): object
    {
        $this->loadModel('build');
        $productID   = $bug->productID;
        $branch      = $bug->branch;
        $projectID   = $bug->projectID;
        $executionID = $bug->executionID;
        $moduleID    = $bug->moduleID ? $bug->moduleID : 0;

        if($executionID)
        {
            $builds  = $this->build->getBuildPairs($productID, $branch, 'noempty,noterminate,nodone,noreleased', $executionID, 'execution');
            $stories = $this->story->getExecutionStoryPairs($executionID);
            if(!$projectID) $projectID = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch('project');
        }
        else
        {
            $builds   = $this->build->getBuildPairs($productID, $branch, 'noempty,noterminate,nodone,withbranch,noreleased');
            $stories  = $this->story->getProductStoryPairs($productID, $branch, $moduleID, 'all','id_desc', 0, 'full', 'story', false);
        }

        return $this->updateBug($bug, array('stories' => $stories, 'builds' => $builds, 'projectID' => $projectID));
    }

    /**
     * 获取bug创建页面的产品成员。
     * Get the product members for bug create page.
     *
     * @param  object    $bug
     * @access protected
     * @return array
     */
    protected function getProductMembers4Create(object $bug): array
    {
        $productMembers = $this->bug->getProductMemberPairs($bug->productID, $bug->branch);
        $productMembers = array_filter($productMembers);
        if(empty($productMembers)) $productMembers = $this->view->users;

        return $productMembers;
    }

    /**
     * 获取bug创建页面的products和projects，并绑定到bug上。
     * Get the products and projects for the bug create page and bind them to bug.
     *
     * @param  object    $bug
     * @access protected
     * @return object
     */
    protected function getProductsAndProjects4Create(object $bug): object
    {
        $productID   = $bug->productID;
        $branch      = $bug->branch;
        $projectID   = $bug->projectID;
        $executionID = $bug->executionID;
        $projects    = array(0 => '');
        $products    = $this->config->CRProduct ? $this->products : $this->product->getPairs('noclosed', 0, '', 'all');

        if($executionID)
        {
            $products       = array();
            $linkedProducts = $this->product->getProducts($executionID);
            foreach($linkedProducts as $product) $products[$product->id] = $product->name;
        }
        elseif($projectID)
        {
            $products    = array();
            $productList = $this->config->CRProduct ? $this->product->getOrderedProducts('all', 40, $projectID) : $this->product->getOrderedProducts('normal', 40, $projectID);
            foreach($productList as $product) $products[$product->id] = $product->name;

            /* Set project menu. */
            if($this->app->tab == 'project') $this->project->setMenu($projectID);
        }
        else
        {
            $projects += $this->product->getProjectPairsByProduct($productID, $branch);
        }

        return $this->updateBug($bug, array('products' => $products, 'projects' => $projects));
    }

    /**
     * 追加bug创建页面的products和projects，并绑定到bug上。
     * Append the products and projects for the bug create page and bind them to bug.
     *
     * @param  object    $bug
     * @param  int       $bugID
     * @access protected
     * @return object
     */
    protected function appendProjects4Create(object $bug, int $bugID): object
    {
        $productID = $bug->productID;
        $branch    = $bug->branch;
        $projects  = $bug->projects;

        $projectID = $bug->projectID;
        $project   = $bug->project;

        /* Link all projects to product when copying bug under qa. */
        if($bugID and $this->app->tab == 'qa')
        {
            $projects += $this->product->getProjectPairsByProduct($productID, $branch);
        }
        elseif($projectID and $project)
        {
            $projects += array($projectID => $project->name);
        }

        return $this->updateBug($bug, array('projects' => $projects));
    }

    /**
     * 获得项目的模式。
     * Get project model.
     *
     * @param  object    $bug
     * @access protected
     * @return object
     */
    protected function getProjectModel4Create(object $bug): object
    {
        $projectID    = $bug->projectID;
        $executionID  = $bug->executionID;
        $project      = $bug->project;
        $projectModel = '';

        if($projectID and $project)
        {
            if(!empty($project->model) and $project->model == 'waterfall') $this->lang->bug->execution = str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->bug->execution);
            $projectModel = $project->model;

            if(!$project->multiple) $executionID = $this->loadModel('execution')->getNoMultipleID($projectID);
        }

        return $this->updateBug($bug, array('projectModel' => $projectModel, 'executionID' => $executionID));
    }

    /**
     * 获得指派给我的blockID。
     * Get block id of assigned to me.
     *
     * @access protected
     * @return int
     */
    protected function getBlockID4Create(): int
    {
        /* Get block id of assinge to me. */
        if(!isonlybody()) return 0;

        return $this->dao->select('id')->from(TABLE_BLOCK)
            ->where('block')->eq('assingtome')
            ->andWhere('module')->eq('my')
            ->andWhere('account')->eq($this->app->user->account)
            ->orderBy('order_desc')
            ->fetch('id');
    }

    /**
     * 获得指派给我的blockID。
     * Get block id of assigned to me.
     *
     * @access protected
     * @return array
     */
    protected function getCustomFields4Create(): array
    {
        $customFields = array();
        foreach(explode(',', $this->config->bug->list->customCreateFields) as $field)
        {
            $customFields[$field] = $this->lang->bug->$field;
        }

        return $customFields;
    }

    /**
     * 获得bug创建页面的products和projects，并绑定到bug上。
     * Get the executions and projects for the bug create page and bind them to bug.
     *
     * @param  object    $bug
     * @access protected
     * @return object
     */
    protected function getExecutions4Create(object $bug): object
    {
        $productID   = $bug->productID;
        $branch      = $bug->branch;
        $projectID   = $bug->projectID;
        $executionID = $bug->executionID;

        $projects    = $bug->projects;
        $executions  = array(0 => '');

        if(isset($projects[$projectID])) $executions += $this->product->getExecutionPairsByProduct($productID, $branch ? "0,$branch" : 0, 'id_desc', $projectID, !$projectID ? 'multiple|stagefilter' : 'stagefilter');
        $execution  = $executionID ? $this->loadModel('execution')->getByID($executionID) : '';
        $executions = isset($executions[$executionID]) ? $executions : $executions + array($executionID => $execution->name);

        return $this->updateBug($bug, array('executions' => $executions, 'execution' => $execution));
    }

    /**
     * 为创建bug设置导航数据。
     * Set menu for create bug page.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  array     $output
     * @access protected
     * @return void
     */
    protected function setMenu4Create(int $productID, string $branch, array $output): void
    {
        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));

        /* Unset discarded types. */
        foreach($this->config->bug->discardedTypes as $type) unset($this->lang->bug->typeList[$type]);

        if($this->app->tab == 'execution')
        {
            if(isset($output['executionID'])) $this->loadModel('execution')->setMenu($output['executionID']);
            $execution = $this->dao->findById($this->session->execution)->from(TABLE_EXECUTION)->fetch();
            if($execution->type == 'kanban')
            {
                $this->loadModel('kanban');
                $regionPairs = $this->kanban->getRegionPairs($execution->id, 0, 'execution');
                $regionID    = !empty($output['regionID']) ? $output['regionID'] : key($regionPairs);
                $lanePairs   = $this->kanban->getLanePairsByRegion($regionID, 'bug');
                $laneID      = isset($output['laneID']) ? $output['laneID'] : key($lanePairs);

                $this->view->executionType = $execution->type;
                $this->view->regionID      = $regionID;
                $this->view->laneID        = $laneID;
                $this->view->regionPairs   = $regionPairs;
                $this->view->lanePairs     = $lanePairs;
            }
        }
        elseif($this->app->tab == 'project')
        {
            if(isset($output['projectID'])) $this->loadModel('project')->setMenu($output['projectID']);
        }
        else
        {
            $this->qa->setMenu($this->products, $productID, $branch);
        }

        $this->view->users = $this->user->getPairs('devfirst|noclosed|nodeleted');
        $this->app->loadLang('release');
    }

    /**
     * 设置编辑页面的导航。
     * Set edit menu.
     *
     * @param  object    $bug
     * @access protected
     * @return void
     */
    protected function setEditMenu(object $bug): void
    {
        if($this->app->tab == 'project')   $this->project->setMenu($bug->project);
        if($this->app->tab == 'execution') $this->execution->setMenu($bug->execution);
        if($this->app->tab == 'qa')        $this->qa->setMenu($this->products, $bug->product, $bug->branch);
        if($this->app->tab == 'devops')
        {
            session_write_close();

            $repoPairs = $this->loadModel('repo')->getRepoPairs('project', $bug->project);
            $this->repo->setMenu($repoPairs);

            $this->lang->navGroup->bug = 'devops';
        }
    }

    /**
     * 获取页面所需的变量, 并输出到前台。
     * Get the data required by the view page and output.
     *
     * @param  object    $bug
     * @access protected
     * @return void
     */
    protected function buildEditForm(object $bug): void
    {
        /* 删掉当前 bug 类型不属于的并且已经弃用的类型。*/
        /* Unset discarded types. */
        foreach($this->config->bug->discardedTypes as $type)
        {
            if($bug->type != $type) unset($this->lang->bug->typeList[$type]);
        }

        $this->loadModel('project');
        $this->loadModel('execution');

        $product   = $this->product->getByID($bug->product);
        $execution = $this->execution->getByID($bug->execution);

        /* 获取影响版本列表和解决版本列表。*/
        /* Get the affected builds and resolved builds. */
        list($openedBuildPairs, $resolvedBuildPairs) = $this->getEditBuildPairs($bug);

        /* 获取所属模块列表。*/
        /* Get module option menu. */
        $moduleOptionMenu = $this->tree->getOptionMenu($bug->product, $viewType = 'bug', $startModuleID = 0, $bug->branch);
        if(!isset($moduleOptionMenu[$bug->module])) $moduleOptionMenu += $this->tree->getModulesName($bug->module);

        /* 获取该 bug 关联产品和分支下的 bug 列表。*/
        /* Get bugs of current product. */
        $branch = '';
        if($product->type == 'branch') $branch = $bug->branch > 0 ? "{$bug->branch},0" : '0';
        $productBugs = $this->bug->getProductBugPairs($bug->product, $branch);
        unset($productBugs[$bug->id]);

        /* 获取执行列表。*/
        /* Get execution pairs. */
        $executions = array('') + $this->product->getExecutionPairsByProduct($bug->product, $bug->branch, 'id_desc', $bug->project);
        if(!empty($bug->execution) and empty($executions[$bug->execution])) $executions[$execution->id] = $execution->name . "({$this->lang->bug->deleted})";

        /* 获取项目列表。*/
        /* Get project pairs. */
        $projects = array('') + $this->product->getProjectPairsByProduct($bug->product, $bug->branch);
        if(!empty($bug->project) and empty($projects[$bug->project]))
        {
            $project = $this->project->getByID($bug->project);
            $projects[$project->id] = $project->name . "({$this->lang->bug->deleted})";
        }

        /* 如果产品列表没有 bug 相关的产品，把该产品加入产品列表。*/
        /* Add product related to the bug when it is not in the products. */
        if(!isset($this->products[$bug->product]))
        {
            $this->products[$bug->product] = $product->name;
            $this->view->products = $this->products;
        }

        if($product->shadow) $this->view->project = $this->project->getByShadowProduct($bug->product);

        $this->view->title                 = $this->lang->bug->edit . "BUG #$bug->id $bug->title - " . $this->products[$bug->product];
        $this->view->bug                   = $bug;
        $this->view->product               = $product;
        $this->view->execution             = $execution;
        $this->view->branchPairs           = $this->getEditBranchPairs($bug);
        $this->view->moduleOptionMenu      = $moduleOptionMenu;
        $this->view->plans                 = $this->loadModel('productplan')->getPairs($bug->product, $bug->branch, '', true);
        $this->view->projects              = $projects;
        $this->view->executions            = $executions;
        $this->view->projectExecutionPairs = $this->project->getProjectExecutionPairs();
        $this->view->stories               = $bug->execution ? $this->story->getExecutionStoryPairs($bug->execution) : $this->story->getProductStoryPairs($bug->product, $bug->branch, 0, 'all', 'id_desc', 0, 'full', 'story', false);
        $this->view->tasks                 = $this->task->getExecutionTaskPairs($bug->execution);
        $this->view->testtasks             = $this->loadModel('testtask')->getPairs($bug->product, $bug->execution, $bug->testtask);
        $this->view->cases                 = array('') + $this->loadModel('testcase')->getPairsByProduct($bug->product, array(0, $bug->branch));
        $this->view->productBugs           = $productBugs;
        $this->view->openedBuildPairs      = $openedBuildPairs;
        $this->view->resolvedBuildPairs    = array('') + $resolvedBuildPairs;
        $this->view->users                 = $this->user->getPairs('', "$bug->assignedTo,$bug->resolvedBy,$bug->closedBy,$bug->openedBy");
        $this->view->assignedToPairs       = $this->getEditAssignedToPairs($bug);
        $this->view->actions               = $this->action->getList('bug', $bug->id);
        $this->display();
    }

    /**
     * 获取编辑页面所需要的影响版本和解决版本。
     * Get affected buils and resolved builds for edit form.
     *
     * @param  object    $bug
     * @access protected
     * @return array
     */
    protected function getEditBuildPairs(object $bug): array
    {
        $objectType = '';
        if($bug->project)   $objectType = 'project';
        if($bug->execution) $objectType = 'execution';

        $objectID           = $bug->execution ? $bug->execution : $bug->project;
        $allBuildPairs      = $this->loadModel('build')->getBuildPairs($bug->product, 'all', 'noempty');
        $openedBuildPairs   = $this->build->getBuildPairs($bug->product, $bug->branch, $params = 'noempty,noterminate,nodone,withbranch,noreleased', $objectID, $objectType, $bug->openedBuild);
        $resolvedBuildPairs = $openedBuildPairs;
        if(($bug->resolvedBuild) && isset($allBuildPairs[$bug->resolvedBuild])) $resolvedBuildPairs[$bug->resolvedBuild] = $allBuildPairs[$bug->resolvedBuild];

        return array($openedBuildPairs, $resolvedBuildPairs);
    }

    /**
     * 获取编辑页面所需要的分支。
     * Get branch pairs for edit form.
     *
     * @param  object    $bug
     * @access protected
     * @return array
     */
    protected function getEditBranchPairs(object $bug): array
    {
        $objectID = 0;
        if($this->app->tab == 'project')   $objectID = $bug->project;
        if($this->app->tab == 'execution') $objectID = $bug->execution;

        $branchPairs = $this->loadModel('branch')->getPairs($bug->product, $params = 'noempty,withClosed', $objectID);

        if(!isset($branchPairs[$bug->branch]))
        {
            $bugBranch = $this->branch->getByID($bug->branch, $bug->product, '');

            if($bug->branch == BRANCH_MAIN) $branchName = $bugBranch;
            if($bug->branch != BRANCH_MAIN)
            {
                $branchName = $bugBranch->name;
                if($bugBranch->status == 'closed') $branchName .= " ({$this->lang->branch->statusList['closed']})";
            }

            $branchPairs[$bug->branch] = $branchName;
        }

        return $branchPairs;
    }

    /**
     * 获取编辑页面指派给用户列表。
     * Get assignedTo pairs for edit form.
     *
     * @param  object    $bug
     * @access protected
     * @return array
     */
    protected function getEditAssignedToPairs(object $bug): array
    {
        $assignedToPairs = $this->bugZen->getAssignedToPairs($bug);

        if($bug->status == 'closed') $assignedToPairs['closed'] = 'Closed';

        return $assignedToPairs;
    }

    /**
     * 确认是否更新 bug 状态。
     * Confirm to update task.
     *
     * @param  int        $bugID
     * @param  int        $taskID
     * @access protected
     * @return array|true
     */
    protected function confirm2UpdateTask(int $bugID, int $taskID): array
    {
        $task = $this->task->getByID($taskID);
        if($task->deleted) return true;

        $confirmedURL = $this->createLink('task', 'view', "taskID=$taskID");
        unset($_GET['onlybody']);
        $canceledURL  = $this->createLink('bug', 'view', "bugID=$bugID");
        return array('result' => 'success', 'load' => array('confirm' => $this->lang->bug->remindTask, 'confirmed' => $confirmedURL, 'canceled' => $canceledURL));
    }

    /**
     * 删除 bug 后不同的返回结果。
     * respond after deleting.
     *
     * @param  object    $bug
     * @param  string    $from
     * @access protected
     * @return array
     */
    protected function responseAfterDelete(object $bug, string $from): array
    {
        if($this->viewType == 'json') return array('result' => 'success', 'message' => $this->lang->saveSuccess);

        /* 在弹窗中删除 bug 时的返回。*/
        /* Respond when delete bug in modal.。*/
        if(isonlybody()) return array('result' => 'success', 'load' => true);

        /* 在任务看板中删除 bug 时的返回。*/
        /* Respond when delete in task kanban. */
        if($from == 'taskkanban')
        {
            $laneType    = $this->session->executionLaneType ?: 'all';
            $groupBy     = $this->session->executionGroupBy  ?: 'default';
            $searchValue = $this->session->taskSearchValue   ?: '';
            $kanbanData  = $this->loadModel('kanban')->getExecutionKanban($bug->execution, $laneType, $groupBy, $searchValue);
            $kanbanType  = $laneType == 'all' ? 'bug' : key($kanbanData);
            $kanbanData  = json_encode($kanbanData[$kanbanType]);

            return array('result' => 'success', 'closeModal' => true, 'callback' => "updateKanban(\"bug\", $kanbanData)");
        }

        return array('result' => 'success', 'load' => $this->session->bugList ?: inlink('browse', "productID={$bug->product}"));
    }

    /**
     * 如果不是弹窗，调用该方法为查看bug设置导航。
     * If it's not a iframe, call this method to set menu for view bug page.
     *
     * @param  object $bug
     * @return void
     */
    protected function setMenu4View(object $bug): void
    {
        if($this->app->tab == 'project')   $this->loadModel('project')->setMenu($bug->project);
        if($this->app->tab == 'execution') $this->loadModel('execution')->setMenu($bug->execution);
        if($this->app->tab == 'qa')        $this->qa->setMenu($this->products, $bug->product, $bug->branch);

        if($this->app->tab == 'devops')
        {
            $repos = $this->loadModel('repo')->getRepoPairs('project', $bug->project);
            $this->repo->setMenu($repos);
            $this->lang->navGroup->bug = 'devops';
        }

        if($this->app->tab == 'product')
        {
            $this->loadModel('product')->setMenu($bug->product);
            $this->lang->product->menu->plan['subModule'] .= ',bug';
        }
    }

    /**
     * 为查看bug页面设置View数据。
     * Set $this->view for view bug page.
     *
     * @param  object $bug
     * @param  string $from
     * @return void
     */
    protected function setView4View(object $bug, string $from): void
    {
        $this->loadModel('project');
        $this->loadModel('product');
        $this->loadModel('build');
        $this->loadModel('common');
        $this->loadModel('repo');
        $this->loadModel('user');

        $bugID     = $bug->id;
        $productID = $bug->product;
        $product   = $this->product->getByID($productID);
        $branches  = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($bug->product);

        $projects = $this->product->getProjectPairsByProduct($productID, $bug->branch);
        $this->session->set("project", key($projects), 'project');

        $this->executeHooks($bugID);

        /* Header and positon. */
        $this->view->title      = "BUG #$bug->id $bug->title - " . $product->name;
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $product->name);
        $this->view->position[] = $this->lang->bug->view;

        /* Assign. */
        $this->view->project     = $this->project->getByID($bug->project);
        $this->view->productID   = $productID;
        $this->view->branches    = $branches;
        $this->view->modulePath  = $this->tree->getParents($bug->module);
        $this->view->bugModule   = empty($bug->module) ? '' : $this->tree->getById($bug->module);
        $this->view->bug         = $bug;
        $this->view->from        = $from;
        $this->view->branchName  = $product->type == 'normal' ? '' : zget($branches, $bug->branch, '');
        $this->view->users       = $this->user->getPairs('noletter');
        $this->view->actions     = $this->action->getList('bug', $bugID);
        $this->view->builds      = $this->build->getBuildPairs($productID, 'all');
        $this->view->preAndNext  = $this->common->getPreAndNextObject('bug', $bugID);
        $this->view->product     = $product;
        $this->view->linkCommits = $this->repo->getCommitsByObject($bugID, 'bug');

        $this->view->projects = array('' => '') + $projects;
    }

    /**
     * 处理关闭bug页面的请求数据。
     * Prepare close request data.
     *
     * @param  object $data
     * @param  int    $bugID
     * @access protected
     * @return object
     */
    protected function prepareCloseExtras(object $data, int $bugID): object
    {
        $bug = $data->add('id', $bugID)
            ->stripTags($this->config->bug->editor->close['id'], $this->config->allowedTags)
            ->get();

        $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->close['id'], $data->rawdata->uid);
        return $bug;
    }

    /**
     * 构建关闭bug页面。
     * Build the page of close bug.
     *
     * @param  object $bug
     * @access protected
     * @return void
     */
    protected function buildCloseForm(object $bug)
    {
        $this->view->bug     = $bug;
        $this->view->users   = $this->user->getPairs('noletter');
        $this->view->actions = $this->action->getList('bug', $bug->id);
        $this->display();
    }

    /**
     * Check bug execution priv.
     *
     * @param  object    $bug
     * @access public
     * @return void
     */
    public function checkBugExecutionPriv($bug)
    {
        if($bug->execution and !$this->loadModel('execution')->checkPriv($bug->execution))
        {
            echo js::alert($this->lang->bug->executionAccessDenied);

            $loginLink = $this->config->requestType == 'GET' ? "?{$this->config->moduleVar}=user&{$this->config->methodVar}=login" : "user{$this->config->requestFix}login";
            if(strpos($this->server->http_referer, $loginLink) !== false) return print(js::locate(helper::createLink('bug', 'index', '')));
            if($this->app->tab == 'my') print(js::reload('parent'));

            return print(js::locate('back'));
        }
    }

    /**
     * 基于当前bug获取指派给。
     * Get assigned pairs by bug.
     *
     * @param  object    $bug
     * @access protected
     * @return string[]
     */
    protected function getAssignedToPairs(object $bug): array
    {
        /* If the execution of the bug is not empty, get the team members for the execution. */
        if($bug->execution)
        {
            $users = $this->loadModel('user')->getTeamMemberPairs($bug->execution, 'execution');
        }
        /* If the project of the bug is not empty, get the team members for the project. */
        elseif($bug->project)
        {
            $users = $this->loadModel('project')->getTeamMemberPairs($bug->project);
        }
        /* If the execution and project of the bug are both empty, get the team member of the bug's product. */
        else
        {
            $users = $this->bug->getProductMemberPairs($bug->product, $bug->branch);
            $users = array_filter($users);
            /* If the team member of the product is empty, get all user. */
            if(empty($users)) $users = $this->loadModel('user')->getPairs('devfirst|noclosed');
        }

        /* If the assigned person doesn't exist in the user list and the assigned person is not closed, append it. */
        if($bug->assignedTo && !isset($users[$bug->assignedTo]) && $bug->assignedTo != 'closed')
        {
            $assignedTo = $this->user->getByID($bug->assignedTo);
            $users[$bug->assignedTo] = $assignedTo->realname;
        }

        return $users;
    }

    /**
     * 解决成功后的相关处理。
     * Relevant processing after resolving bug.
     *
     * @param  int         $bugID
     * @param  array       $changes
     * @param  string|bool $comment
     * @param  string|bool $actionExtra
     * @param  array       $output
     * @access protected
     * @return void
     */
    protected function processAfterResolve(int $bug, array $changes, string|bool $comment, string|bool $actionExtra, array $output): void
    {
        /* Add score. */
        $this->loadModel('score')->create('bug', 'resolve', $bug);

        /* Move bug card in kanban. */
        if($bug->execution)
        {
            $this->loadModel('kanban');
            if(!isset($output['toColID'])) $this->loadModel('kanban')->updateLane($bug->execution, 'bug', $bug->id);
            if(isset($output['toColID'])) $this->loadModel('kanban')->moveCard($bug->id, $output['fromColID'], $output['toColID'], $output['fromLaneID'], $output['toLaneID']);
        }

        /* Link bug to build and release. */
        $this->linkBugToBuild($bug->id, $bug->resolvedBuild);

        /* Save files. */
        $files = $this->loadModel('file')->saveUpload('bug', $bug->id);

        /* Record log. */
        $fileAction = !empty($files) ? $this->lang->addFiles . implode(',', $files) . "\n" : '';
        $actionID   = $this->loadModel('action')->create('bug', $bug->id, 'Resolved', $fileAction . $comment, $actionExtra);
        $this->action->logHistory($actionID, $changes);

        $this->executeHooks($bug->id);
    }
}

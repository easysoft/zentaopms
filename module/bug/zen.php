<?php
declare(strict_types=1);
class bugZen extends bug
{
    /**
     * 处理请求数据。
     * Processing request data.
     *
     * @param  object $formData
     * @access protected
     * @return object
     */
    protected function beforeCreate(object $formData): object
    {
        $now = helper::now();
        $bug = $formData->setDefault('openedBy', $this->app->user->account)
            ->setDefault('openedDate', $now)
            ->setIF($this->lang->navGroup->bug != 'qa', 'project', $this->session->project)
            ->setIF($formData->data->assignedTo != '', 'assignedDate', $now)
            ->setIF($formData->data->story != false, 'storyVersion', $this->loadModel('story')->getVersion($formData->data->story))
            ->setIF(strpos($this->config->bug->create->requiredFields, 'deadline') !== false, 'deadline', $formData->data->deadline)
            ->setIF(strpos($this->config->bug->create->requiredFields, 'execution') !== false, 'execution', $formData->data->execution)
            ->stripTags($this->config->bug->editor->create['id'], $this->config->allowedTags)
            ->cleanInt('product,execution,module,severity')
            ->remove('files,labels,uid,oldTaskID,contactListMenu,region,lane,ticket,deleteFiles,resultFiles')
            ->get();

        $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->create['id'], $formData->rawdata->uid);

        return $bug;
    }
    /**
     * 创建bug。
     * Create a bug.
     *
     * @param  object $bug
     * @access protected
     * @return array|false
     */
    protected function doCreate(object $bug): array|false
    {
        /* Check repeat bug. */
        $result = $this->loadModel('common')->removeDuplicate('bug', $bug, "product={$bug->product}");
        if($result and $result['stop']) return array('status' => 'exists', 'id' => $result['duplicate']);

        return $this->bug->create($bug);
    }

    /**
     * 创建bug后数据处理。
     * Do thing after create a bug.
     *
     * @param  object $bug
     * @param  object $formData
     * @param  string $from
     * @param  array  $output
     * @return void
     */
    protected function afterCreate(object $bug, object $formData, string $from, array $output)
    {
        $bugID = $bug->id;

        if(isset($formData->rawdata->resultFiles))
        {
            $resultFiles = $formData->rawdata->resultFiles;
            if(isset($formData->rawdata->deleteFiles))
            {
                foreach($formData->rawdata->deleteFiles as $deletedCaseFileID) $resultFiles = trim(str_replace(",$deletedCaseFileID,", ',', ",$resultFiles,"), ',');
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

        $this->file->updateObjectID($formData->rawdata->uid, $bugID, 'bug');
        $this->file->saveUpload('bug', $bugID);
        empty($bug->case) ? $this->loadModel('score')->create('bug', 'create', $bugID) : $this->loadModel('score')->create('bug', 'createFormCase', $bug->case);

        if($bug->execution)
        {
            $this->loadModel('kanban');

            $laneID = isset($output['laneID']) ? $output['laneID'] : 0;
            if(!empty($formData->rawdata->lane)) $laneID = $formData->rawdata->lane;

            $columnID = $this->kanban->getColumnIDByLaneID($laneID, 'unconfirmed');
            if(empty($columnID)) $columnID = isset($output['columnID']) ? $output['columnID'] : 0;

            if(!empty($laneID) and !empty($columnID)) $this->kanban->addKanbanCell($bug->execution, $laneID, $columnID, 'bug', $bugID);
            if(empty($laneID) or empty($columnID)) $this->kanban->updateLane($bug->execution, 'bug');
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
     * @access protected
     * @return array
     */
    protected function responseAfterEdit(int $bugID, array $changes, string $kanbanGroup): array
    {
        if(defined('RUN_MODE') && RUN_MODE == 'api') return array('status' => 'success', 'data' => $bugID);

        /* 如果 bug 转任务并且 bug 的状态发生变化，提示是否更新任务状态。*/
        /* This bug has been converted to a task, update the status of the relatedtask or not. */
        $bug = $this->bug->getByID($bugID);
        if($bug->toTask and !empty($changes))
        {
            foreach($changes as $change)
            {
                if($change['field'] != 'status') continue;

                return array('result' => 'success', 'confirm' => array('note' => $this->lang->bug->remindTask, 'confirmURL' => $this->createLink('task', 'view', "taskID=$bug->toTask"), 'cancelURL' => $this->server->HTTP_REFERER));
            }
        }

        /* 在弹窗里编辑 bug 时的返回。*/
        /* Respond after updating in modal. */
        if(isonlybody())
        {
            /* 在执行应用下，编辑看板中的 bug 数据时，更新看板数据。*/
            /* Update kanban data after updating bug in kanban. */
            if($this->app->tab == 'execution')
            {
                $this->loadModel('kanban');

                $execution    = $this->loadModel('execution')->getByID($bug->execution);
                $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';

                /* 看板类型的执行。*/
                /* The kanban exectuion. */
                if(isset($execution->type) && $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $kanbanData    = $this->kanban->getRDKanban($bug->execution, $execLaneType, 'id_desc', 0, $kanbanGroup, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);
                    return array('result' => 'success', 'closeModal' => true, 'callback' => "updateKanban($kanbanData)");
                }
                /* 执行中的看板。*/
                /* The kanban of execution. */
                else
                {
                    $execGroupBy     = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->kanban->getExecutionKanban($bug->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                    $kanbanType      = $execLaneType == 'all' ? 'bug' : key($kanbanData);
                    $kanbanData      = json_encode($kanbanData[$kanbanType]);
                    return array('result' => 'success', 'closeModal' => true, 'callback' => "updateKanban(\"bug\", $kanbanData)");
                }
            }

            return array('result' => 'success', 'closeModal' => true);
        }

        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('bug', 'view', "bugID=$bugID"));
    }

    /**
     * 为create方法添加动态。
     * Add action for create function.
     *
     * @param  int    $bugID
     * @param  string $from
     * @param  array  $output
     * @return void
     */
    protected function addAction4Create(int $bugID, string $from, array $output)
    {
        $createAction = $from == 'sonarqube' ? 'fromSonarqube' : 'Opened';
        $actionID     = $this->action->create('bug', $bugID, $createAction);

        if(isset($output['todoID']))
        {
            $this->dao->update(TABLE_TODO)->set('status')->eq('done')->where('id')->eq($output['todoID'])->exec();
            $this->action->create('todo', $output['todoID'], 'finished', '', "BUG:$bugID");

            if($this->config->edition == 'biz' || $this->config->edition == 'max')
            {
                $todo = $this->dao->select('type, idvalue')->from(TABLE_TODO)->where('id')->eq($output['todoID'])->fetch();
                if($todo->type == 'feedback' && $todo->idvalue) $this->loadModel('feedback')->updateStatus('todo', $todo->idvalue, 'done');
            }
        }
    }

    /**
     * 获得create方法的发生错误时的返回。
     * Get response for create function when error happened.
     *
     * @param  array $bugResult
     * @param  array $response
     * @return array
     */
    protected function getErrorRes4Create(array $bugResult): array
    {
        $hasError = false;
        $response = array();

        if(!$bugResult or dao::isError())
        {
            $hasError = true;
            $response['result']  = 'fail';
            $response['message'] = dao::getError();
        }

        if($bugResult['status'] == 'exists')
        {
            $hasError = true;
            $bugID = $bugResult['id'];
            $response['message'] = sprintf($this->lang->duplicate, $this->lang->bug->common);
            $response['locate']  = $this->createLink('bug', 'view', "bugID=$bugID");
            $response['id']      = $bugID;
        }

        return array($hasError, $response);
    }

    /**
     * 获得create方法的onlybody返回。
     * Get onlybody response for create.
     *
     * @param  array $bugResult
     * @param  array $response
     * @return array
     */
    protected function getOnlyBodyRes4Create(object $formData, array $output): array
    {
        $executionID = isset($output['executionID']) ? $output['executionID'] : $this->session->execution;
        $executionID = $formData->data->execution ? $formData->data->execution : $executionID;
        $execution   = $this->loadModel('execution')->getByID($executionID);
        if($this->app->tab == 'execution')
        {
            $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
            $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';

            if($execution->type == 'kanban')
            {
                $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                $kanbanData    = $this->loadModel('kanban')->getRDKanban($executionID, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
                $kanbanData    = json_encode($kanbanData);
                return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "parent.updateKanban($kanbanData, 0)");
            }
            else
            {
                $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($executionID, $execLaneType, $execGroupBy, $taskSearchValue);
                $kanbanType      = $execLaneType == 'all' ? 'bug' : key($kanbanData);
                $kanbanData      = $kanbanData[$kanbanType];
                $kanbanData      = json_encode($kanbanData);
                return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "parent.updateKanban(\"bug\", $kanbanData)");
            }
        }
        else
        {
            return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'locate' => 'parent');
        }
    }

    /**
     * 获得create方法的返回url。
     * Get response url for create.
     *
     * @param  object $formData
     * @param  array  $output
     * @param  int    $bugID
     * @param  string $branch
     * @return string
     */
    protected function getLocation4Create(object $formData, array $output, int $bugID, string $branch): string
    {
        if($this->app->tab == 'execution')
        {
            if(!preg_match("/(m=|\/)execution(&f=|-)bug(&|-|\.)?/", $this->session->bugList))
            {
                $location = $this->session->bugList;
            }
            else
            {
                $executionID = $formData->data->execution ? $formData->data->execution : zget($output, 'executionID', $this->session->execution);
                $location    = $this->createLink('execution', 'bug', "executionID=$executionID");
            }

        }
        elseif($this->app->tab == 'project')
        {
            $location = $this->createLink('project', 'bug', "projectID=" . zget($output, 'projectID', $this->session->project));
        }
        else
        {
            setcookie('bugModule', '0', 0, $this->config->webRoot, '', $this->config->cookieSecure, true);
            $location = $this->createLink('bug', 'browse', "productID={$formData->data->product}&branch=$branch&browseType=byModule&param={$formData->data->module}&orderBy=id_desc");
        }
        if($this->app->getViewType() == 'xhtml') $location = $this->createLink('bug', 'view', "bugID=$bugID", 'html');

        return $location;
    }

    /**
     * 初始化一个默认的bug模板。
     * Init a default bug templete.
     *
     * @return object
     */
    protected function initBugTemplete(): object
    {
        $bugTpl = new stdclass();
        $bugTpl->projectID   = 0;
        $bugTpl->moduleID    = 0;
        $bugTpl->executionID = 0;
        $bugTpl->productID   = 0;
        $bugTpl->taskID      = 0;
        $bugTpl->storyID     = 0;
        $bugTpl->buildID     = 0;
        $bugTpl->caseID      = 0;
        $bugTpl->runID       = 0;
        $bugTpl->testtask    = 0;
        $bugTpl->version     = 0;
        $bugTpl->title       = '';
        $bugTpl->steps       = $this->lang->bug->tplStep . $this->lang->bug->tplResult . $this->lang->bug->tplExpect;
        $bugTpl->os          = '';
        $bugTpl->browser     = '';
        $bugTpl->assignedTo  = '';
        $bugTpl->deadline    = '';
        $bugTpl->mailto      = '';
        $bugTpl->keywords    = '';
        $bugTpl->severity    = 3;
        $bugTpl->type        = 'codeerror';
        $bugTpl->pri         = 3;
        $bugTpl->color       = '';
        $bugTpl->feedbackBy  = '';
        $bugTpl->notifyEmail = '';

        $bugTpl->project      = '';
        $bugTpl->branch       = '';
        $bugTpl->execution    = '';
        $bugTpl->projectModel = '';
        $bugTpl->projects   = array();
        $bugTpl->executions = array();
        $bugTpl->products   = array();
        $bugTpl->stories    = array();
        $bugTpl->builds     = array();
        $bugTpl->branches   = array();

        return $bugTpl;
    }

    /**
     * 更新bug模板。
     * Update bug templete.
     *
     * @param  object $bugTpl
     * @param  array  $fields
     * @return object
     */
    protected function updateBugTemplete(object $bugTpl, array $fields): object
    {
        foreach($fields as $field => $value) $bugTpl->$field = $value;

        return $bugTpl;
    }

    /**
     * 将$bugTpl对象的属性添加到view对象中。
     * Add the prop of the $butTpl object to the view object
     *
     * @param  object $bugTpl
     * @return void
     */
    protected function extractBugTemplete(object $bugTpl): void
    {
        $this->view->projectID   = $bugTpl->projectID;
        $this->view->moduleID    = $bugTpl->moduleID;
        $this->view->productID   = $bugTpl->productID;
        $this->view->products    = $bugTpl->products;
        $this->view->stories     = $bugTpl->stories;
        $this->view->projects    = defined('TUTORIAL') ? $this->loadModel('tutorial')->getProjectPairs()   : $bugTpl->projects;
        $this->view->executions  = defined('TUTORIAL') ? $this->loadModel('tutorial')->getExecutionPairs() : $bugTpl->executions;
        $this->view->builds      = $bugTpl->builds;
        $this->view->execution   = $bugTpl->execution;
        $this->view->taskID      = $bugTpl->taskID;
        $this->view->storyID     = $bugTpl->storyID;
        $this->view->buildID     = $bugTpl->buildID;
        $this->view->caseID      = $bugTpl->caseID;
        $this->view->runID       = $bugTpl->runID;
        $this->view->version     = $bugTpl->version;
        $this->view->testtask    = $bugTpl->testtask;
        $this->view->bugTitle    = $bugTpl->title;
        $this->view->pri         = $bugTpl->pri;
        $this->view->steps       = htmlSpecialString($bugTpl->steps);
        $this->view->os          = $bugTpl->os;
        $this->view->browser     = $bugTpl->browser;
        $this->view->assignedTo  = $bugTpl->assignedTo;
        $this->view->deadline    = $bugTpl->deadline;
        $this->view->mailto      = $bugTpl->mailto;
        $this->view->keywords    = $bugTpl->keywords;
        $this->view->severity    = $bugTpl->severity;
        $this->view->type        = $bugTpl->type;
        $this->view->branch      = $bugTpl->branch;
        $this->view->branches    = $bugTpl->branches;
        $this->view->color       = $bugTpl->color;
        $this->view->feedbackBy  = $bugTpl->feedbackBy;
        $this->view->notifyEmail = $bugTpl->notifyEmail;

        $this->view->projectModel    = $bugTpl->projectModel;
        $this->view->stepsRequired   = strpos($this->config->bug->create->requiredFields, 'steps');
        $this->view->isStepsTemplate = $bugTpl->steps == $this->lang->bug->tplStep . $this->lang->bug->tplResult . $this->lang->bug->tplExpect ? true : false;
    }

    /**
     * 获取bug创建页面的branches和branch，并绑定到bugTpl上。
     * Get the branches and branch for the bug create page and bind them to bugTpl.
     *
     * @param  object $bugTpl
     * @param  object $currentProduct
     * @return object
     */
    protected function getBranches4Create(object $bugTpl, object $currentProduct): object
    {
        $productID = $bugTpl->productID;
        $branch    = $bugTpl->branch;

        if($this->app->tab == 'execution' or $this->app->tab == 'project')
        {
            $objectID        = $this->app->tab == 'project' ? $bugTpl->projectID : $bugTpl->executionID;
            $productBranches = $currentProduct->type != 'normal' ? $this->loadModel('execution')->getBranchByProduct($productID, $objectID, 'noclosed|withMain') : array();
            $branches        = isset($productBranches[$productID]) ? $productBranches[$productID] : array();
            $branch          = key($branches);
        }
        else
        {
            $branches = $currentProduct->type != 'normal' ? $this->loadModel('branch')->getPairs($productID, 'active') : array();
        }

        return $this->updateBugTemplete($bugTpl, array('branches' => $branches, 'branch' => $branch));
    }

    /**
     * 获取bug创建页面的builds和stories，并绑定到bugTpl上。
     * Get the builds and stories for the bug create page and bind them to bugTpl.
     *
     * @param  object $bugTpl
     * @return object
     */
    protected function getBuildsAndStories4Create(object $bugTpl): object
    {
        $this->loadModel('build');
        $productID   = $bugTpl->productID;
        $branch      = $bugTpl->branch;
        $projectID   = $bugTpl->projectID;
        $executionID = $bugTpl->executionID;
        $moduleID    = $bugTpl->moduleID ? $bugTpl->moduleID : 0;

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

        return $this->updateBugTemplete($bugTpl, array('stories' => $stories, 'builds' => $builds, 'projectID' => $projectID));
    }

    /**
     * 获取bug创建页面的产品成员。
     * Get the product members for bug create page.
     *
     * @param  object $bugTpl
     * @return array
     */
    protected function getProductMembers4Create(object $bugTpl): array
    {
        $productMembers = $this->bug->getProductMemberPairs($bugTpl->productID, $bugTpl->branch);
        $productMembers = array_filter($productMembers);
        if(empty($productMembers)) $productMembers = $this->view->users;

        return $productMembers;
    }

    /**
     * 获取bug创建页面的products和projects，并绑定到bugTpl上。
     * Get the products and projects for the bug create page and bind them to bugTpl.
     *
     * @param  object $bugTpl
     * @return object
     */
    protected function getProductsAndProjects4Create(object $bugTpl): object
    {
        $productID   = $bugTpl->productID;
        $branch      = $bugTpl->branch;
        $projectID   = $bugTpl->projectID;
        $executionID = $bugTpl->executionID;
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

        return $this->updateBugTemplete($bugTpl, array('products' => $products, 'projects' => $projects));
    }

    /**
     * 追加bug创建页面的products和projects，并绑定到bugTpl上。
     * Append the products and projects for the bug create page and bind them to bugTpl.
     *
     * @param  object $bugTpl
     * @param  int    $bugID
     * @return object
     */
    protected function appendProjects4Create(object $bugTpl, int $bugID): object
    {
        $productID = $bugTpl->productID;
        $branch    = $bugTpl->branch;
        $projects  = $bugTpl->projects;

        $projectID = $bugTpl->projectID;
        $project   = $bugTpl->project;

        /* Link all projects to product when copying bug under qa. */
        if($bugID and $this->app->tab == 'qa')
        {
            $projects += $this->product->getProjectPairsByProduct($productID, $branch);
        }
        else if($projectID and $project)
        {
            $projects += array($projectID => $project->name);
        }

        return $this->updateBugTemplete($bugTpl, array('projects' => $projects));
    }

    /**
     * 获得项目的模式。
     * Get project model.
     *
     * @param  object $bugTpl
     * @return object
     */
    protected function getProjectModel4Create(object $bugTpl): object
    {
        $projectID    = $bugTpl->projectID;
        $executionID  = $bugTpl->executionID;
        $project      = $bugTpl->project;
        $projectModel = '';

        if($projectID and $project)
        {
            if(!empty($project->model) and $project->model == 'waterfall') $this->lang->bug->execution = str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->bug->execution);
            $projectModel = $project->model;

            if(!$project->multiple) $executionID = $this->loadModel('execution')->getNoMultipleID($projectID);
        }

        return $this->updateBugTemplete($bugTpl, array('projectModel' => $projectModel, 'executionID' => $executionID));
    }

    /**
     * 获得指派给我的blockID。
     * Get block id of assigned to me.
     *
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
     * 获得bug创建页面的products和projects，并绑定到bugTpl上。
     * Get the executions and projects for the bug create page and bind them to bugTpl.
     *
     * @param  object $bugTpl
     * @return object
     */
    protected function getExecutions4Create(object $bugTpl): object
    {
        $productID   = $bugTpl->productID;
        $branch      = $bugTpl->branch;
        $projectID   = $bugTpl->projectID;
        $executionID = $bugTpl->executionID;

        $projects    = $bugTpl->projects;
        $executions  = array(0 => '');

        if(isset($projects[$projectID])) $executions += $this->product->getExecutionPairsByProduct($productID, $branch ? "0,$branch" : 0, 'id_desc', $projectID, !$projectID ? 'multiple|stagefilter' : 'stagefilter');
        $execution  = $executionID ? $this->loadModel('execution')->getByID($executionID) : '';
        $executions = isset($executions[$executionID]) ? $executions : $executions + array($executionID => $execution->name);

        return $this->updateBugTemplete($bugTpl, array('executions' => $executions, 'execution' => $execution));
    }

    /**
     * 为创建bug设置导航数据。
     * Set menu for create bug page.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  array  $output
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
        else if($this->app->tab == 'project')
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
        $this->loadModel('branch');
        $this->loadModel('execution');
        $this->loadModel('build');

        $product   = $this->product->getByID($bug->product);
        $execution = $this->execution->getByID($bug->execution);

        /* 获取影响版本列表和解决版本列表。*/
        /* Get the affected builds and resolved builds. */
        $objectType = '';
        if($bug->project)   $objectType = 'project';
        if($bug->execution) $objectType = 'execution';

        $objectID       = $bug->execution ? $bug->execution : $bug->project;
        $allBuilds      = $this->loadModel('build')->getBuildPairs($bug->product, 'all', 'noempty');
        $openedBuilds   = $this->build->getBuildPairs($bug->product, $bug->branch, $params = 'noempty,noterminate,nodone,withbranch,noreleased', $objectID, $objectType, $bug->openedBuild);
        $resolvedBuilds = $openedBuilds;
        if(($bug->resolvedBuild) && isset($allBuilds[$bug->resolvedBuild])) $resolvedBuilds[$bug->resolvedBuild] = $allBuilds[$bug->resolvedBuild];

        /* 获取分支列表。*/
        /* Get the branch options. */
        if($this->app->tab == 'execution' || $this->app->tab == 'project') $objectID = $this->app->tab == 'project' ? $bug->project : $bug->execution;

        $branches        = $this->branch->getList($bug->product, isset($objectID) ? $objectID : 0, 'all');
        $branchTagOption = array();
        foreach($branches as $branchInfo) $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');

        if(!isset($branchTagOption[$bug->branch]))
        {
            $bugBranch = $this->branch->getById($bug->branch, $bug->product, '');
            if($bug->branch == BRANCH_MAIN) $branchTagName = $bugBranch;
            if($bug->branch != BRANCH_MAIN)
            {
                $branchTagName = $bugBranch->name;
                if($bugBranch->status == 'closed') $branchTagName .= " ({$this->lang->branch->statusList['closed']})";
            }
            $branchTagOption[$bug->branch] = $branchTagName;
        }

        /* 获取所属模块列表。*/
        /* Get module option menu. */
        $moduleOptionMenu = $this->tree->getOptionMenu($bug->product, $viewType = 'bug', $startModuleID = 0, $bug->branch);
        if(!isset($moduleOptionMenu[$bug->module])) $moduleOptionMenu += $this->tree->getModulesName($bug->module);

        /* 获取指派给用户列表。*/
        /* Get the user pairs for assign. */
        if($bug->execution)
        {
            $assignedToList = $this->user->getTeamMemberPairs($bug->execution, 'execution');
        }
        elseif($bug->project)
        {
            $assignedToList = $this->project->getTeamMemberPairs($bug->project);
        }
        else
        {
            $assignedToList = $this->bug->getProductMemberPairs($bug->product, $bug->branch);
            $assignedToList = array_filter($assignedToList);
            if(empty($assignedToList)) $assignedToList = $this->user->getPairs('devfirst|noclosed');
        }

        if($bug->assignedTo and !isset($assignedToList[$bug->assignedTo]) and $bug->assignedTo != 'closed')
        {
            $assignedTo = $this->user->getById($bug->assignedTo);
            $assignedToList[$bug->assignedTo] = $assignedTo->realname;
        }
        if($bug->status == 'closed') $assignedToList['closed'] = 'Closed';

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
        $this->view->branchTagOption       = $branchTagOption;
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
        $this->view->openedBuilds          = $openedBuilds;
        $this->view->resolvedBuilds        = array('') + $resolvedBuilds;
        $this->view->users                 = $this->user->getPairs('', "$bug->assignedTo,$bug->resolvedBy,$bug->closedBy,$bug->openedBy");
        $this->view->assignedToList        = $assignedToList;
        $this->view->actions               = $this->action->getList('bug', $bug->id);
        $this->display();
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
}

<?php
declare(strict_types=1);
class todoZen extends todo
{
    /**
     * 生成创建待办视图数据。
     * Build create form data.
     *
     * @param  string $date
     * @access protected
     * @return void
     */
    protected function buildCreateView(string $date)
    {
        $this->view->title = $this->lang->todo->common . $this->lang->colon . $this->lang->todo->create;
        $this->view->date  = date('Y-m-d', strtotime($date));
        $this->view->times = date::buildTimeList($this->config->todo->times->begin, $this->config->todo->times->end, $this->config->todo->times->delta);
        $this->view->time  = date::now();
        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted|noempty');

        $this->display();
    }

    /**
     * 生成批量创建待办视图数据。
     * Build batch create form data.
     *
     * @param  string $date
     * @access protected
     * @return void
     */
    protected function buildBatchCreateView(string $date)
    {
        /* Set Custom. */
        foreach(explode(',', $this->config->todo->list->customBatchCreateFields) as $field) $customFields[$field] = $this->lang->todo->$field;

        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->todo->custom->batchCreateFields;

        $this->view->title      = $this->lang->todo->common . $this->lang->colon . $this->lang->todo->batchCreate;
        $this->view->position[] = $this->lang->todo->common;
        $this->view->position[] = $this->lang->todo->batchCreate;
        $this->view->date       = (int)$date == 0 ? $date : date('Y-m-d', strtotime($date));
        $this->view->times      = date::buildTimeList($this->config->todo->times->begin, $this->config->todo->times->end, $this->config->todo->times->delta);
        $this->view->time       = date::now();
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|nodeleted|noempty');

        $this->display();
    }

    /**
     * 生成编辑待办视图数据。
     * Build create form data.
     *
     * @param  object    $todo
     * @access protected
     * @return void
     */
    protected function buildEditView(object $todo): void
    {
        $todo->date = date("Y-m-d", strtotime($todo->date));
        $this->view->title      = $this->lang->todo->common . $this->lang->colon . $this->lang->todo->edit;
        $this->view->times      = date::buildTimeList($this->config->todo->times->begin, $this->config->todo->times->end, $this->config->todo->times->delta);
        $this->view->todo       = $todo;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|nodeleted|noempty');

        $this->display();
    }

    /**
     * 生成指派待办视图数据。
     * Build assignTo form data.
     *
     * @param  int        $todoID
     * @access protected
     * @return void
     */
    protected function buildAssignToView(int $todoID): void
    {
        $this->view->todo    = $this->todo->getByID($todoID);
        $this->view->members = $this->loadModel('user')->getPairs('noclosed|noempty|nodeleted');
        $this->view->times   = date::buildTimeList($this->config->todo->times->begin, $this->config->todo->times->end, $this->config->todo->times->delta);
        $this->view->actions = $this->loadModel('action')->getList('todo', $todoID);
        $this->view->users   = $this->user->getPairs('noletter');
        $this->view->time    = date::now();

        $this->display();
    }

    /**
     * 处理请求数据
     * Processing request data.
     *
     * @param  object $formData
     * @access protected
     * @return object
     */
    protected function beforeCreate(object $formData): object
    {
        $rowData    = $formData->rawdata;
        $objectType = $rowData->type;
        $hasObject  = in_array($objectType, $this->config->todo->moduleList);

        $objectID = 0;
        if($hasObject && $objectType) $objectID = $rowData->uid ? $rowData->$objectType : $rowData->objectID;

        $data = $formData->add('account', $this->app->user->account)
            ->setDefault('objectID', 0)
            ->setDefault('vision', $this->config->vision)
            ->setDefault('assignedTo', $this->app->user->account)
            ->setDefault('assignedBy', $this->app->user->account)
            ->setDefault('assignedDate', helper::now())
            ->cleanInt('pri, begin, end, private')
            ->setIF($hasObject && $objectType,  'objectID', $objectID)
            ->setIF($rowData->date == false,  'date', '2030-01-01')
            ->setIF($rowData->begin == false, 'begin', '2400')
            ->setIF($rowData->begin == false or $rowData->end == false, 'end', '2400')
            ->setIF($rowData->status == 'done', 'finishedBy', $this->app->user->account)
            ->setIF($rowData->status == 'done', 'finishedDate', helper::now())
            ->stripTags($this->config->todo->editor->create['id'], $this->config->allowedTags)
            ->remove(implode(',', $this->config->todo->moduleList) . ',uid')
            ->get();

        return $data;
    }

    /**
     * 创建完成待办后数据处理。
     * Create a todo after data processing.
     *
     * @param  object $todo
     * @param  object $formData
     * @access protected
     * @return object
     */
    protected function afterCreate(object $todo, object $formData): object
    {
        $this->loadModel('file')->updateObjectID($formData->rawdata->uid, $todo->id, 'todo');

        $this->loadModel('score')->create('todo', 'create', $todo->id);

        if(!empty($todo->cycle)) $this->todo->createByCycle(array($todo->id => $todo));

        $this->loadModel('action')->create('todo', $todo->id, 'opened');

        $date = str_replace('-', '', $todo->date);
        if($date == '')          $date = 'future';
        if($date == date('Ymd')) $date = 'today';

        return $todo;
    }

    /**
     * 处理编辑待办的请求数据。
     * Processing edit request data.
     *
     * @param  int    $todoID
     * @param  object $formData
     * @access protected
     * @return object|false
     */
    protected function beforeEdit(int $todoID, object $formData): object|false
    {
        $oldTodo = $this->dao->findByID($todoID)->from(TABLE_TODO)->fetch();

        $objectID   = 0;
        $rowData    = $formData->rawdata;
        $objectType = $rowData->type;
        $hasObject  = in_array($objectType, $this->config->todo->moduleList);
        if($hasObject && $objectType) $objectID = $rowData->uid ? $rowData->$objectType : $rowData->objectID;

        $todo = $formData->add('account', $oldTodo->account)
            ->cleanInt('pri, begin, end, private')
            ->setIF(in_array($rowData->type, array('bug', 'task', 'story')), 'name', '')
            ->setIF($hasObject && $objectType,  'objectID', $objectID)
            ->setIF($rowData->date  == false, 'date', '2030-01-01')
            ->setIF($rowData->begin == false, 'begin', '2400')
            ->setIF($rowData->end   == false, 'end', '2400')
            ->setIF($rowData->type  == false, 'type', $oldTodo->type)
            ->setDefault('private', 0)
            ->stripTags($this->config->todo->editor->edit['id'], $this->config->allowedTags)
            ->remove(implode(',', $this->config->todo->moduleList) . ',uid')
            ->get();

        $todo = (object)array_merge((array)$rowData, (array)$todo);

        if(in_array($todo->type, $this->config->todo->moduleList))
        {
            $type   = $todo->type;
            $object = $this->loadModel($type)->getByID($objectType);
            if(isset($object->name))  $todo->name = $object->name;
            if(isset($object->title)) $todo->name = $object->title;
        }

        if($todo->end < $todo->begin)
        {
            dao::$errors[] = sprintf($this->lang->error->gt, $this->lang->todo->end, $this->lang->todo->begin);
            return false;
        }

        if(!empty($oldTodo->cycle)) $this->handleCycleConfig($todo);

        return $this->loadModel('file')->processImgURL($todo, $this->config->todo->editor->edit['id'], $rowData->uid);
    }

    /**
     * 编辑完成待办后数据处理。
     * Handle data after edit todo.
     *
     * @param  object $todo
     * @access protected
     * @return void
     */
    protected function afterEdit(int $todoID, array $changes): void
    {
        if(empty($changes)) return;

        $actionID = $this->loadModel('action')->create('todo', $todoID, 'edited');
        $this->action->logHistory($actionID, $changes);
    }

    /**
     * 批量编辑页面渲染。
     * Batch edit view display.
     *
     * @param object $formData
     * @param string $type
     * @param int    $userID
     * @param string $status
     * @access protected
     * @return void
     */
    protected function batchEditFromMyTodo(object $formData, string $type, int $userID, string $status): void
    {
        /* Initialize vars. */
        $editedTodos = $objectIdList = $reviews = array();
        $columns      = 7;
        unset($this->lang->todo->typeList['cycle']);

        if(empty($userID)) $userID = $this->app->user->id;
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;

        list($editedTodos, $objectIdList) = $this->getBatchEditInitTodos($formData, $type, $account, $status);

        $bugs      = $this->loadModel('bug')->getUserBugPairs($account, true, 0, '', '', isset($objectIdList['bug']) ? $objectIdList['bug'] : '');
        $tasks     = $this->loadModel('task')->getUserTaskPairs($account, 'wait,doing', '', isset($objectIdList['task']) ? $objectIdList['task'] : '');
        $storys    = $this->loadModel('story')->getUserStoryPairs($account, 10, 'story', '', isset($objectIdList['story']) ? $objectIdList['story'] : '');
        $users     = $this->loadModel('user')->getPairs('noclosed|nodeleted|noempty');
        $testtasks = $this->loadModel('testtask')->getUserTestTaskPairs($account);
        if($this->config->edition != 'open') $feedbacks = $this->loadModel('feedback')->getUserFeedbackPairs($account, '', isset($objectIdList['feedback']) ? $objectIdList['feedback'] : '');
        if($this->config->edition == 'max')
        {
            $issues        = $this->loadModel('issue')->getUserIssuePairs($account);
            $risks         = $this->loadmodel('risk')->getUserRiskPairs($account);
            $opportunities = $this->loadmodel('opportunity')->getUserOpportunityPairs($account);
            $reviews       = $this->loadModel('review')->getUserReviewPairs($account);
        }

        /* Judge whether the edited todos is too large. */
        $countInputVars  = count($editedTodos) * $columns;
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);

        if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);
        $this->view->bugs   = $bugs;
        $this->view->tasks  = $tasks;
        $this->view->storys = $storys;
        $this->view->reviews     = $reviews;
        $this->view->testtasks   = $testtasks;
        $this->view->editedTodos = $editedTodos;
        $this->view->users       = $users;
        if($this->config->edition != 'open') $this->view->feedback = $feedbacks;
        if($this->config->edition == 'max')
        {
            $this->view->issues        = $issues;
            $this->view->risks         = $risks;
            $this->view->opportunities = $opportunities;
        }

        $this->buildBatchEditView();
        return;
    }

    /**
     * 获取批量编辑页面初始化待办数据。
     * Get batch edit page initialization todo data.
     *
     * @param object $formData
     * @param string $type
     * @param string $account
     * @param string $status
     * @access protected
     * @return array
     */
    private function getBatchEditInitTodos(object $formData, string $type, string $account, string $status): array
    {
        $editedTodos  = array();
        $objectIdList = array();
        $todoIdList   = array();

        $allTodos = $this->todo->getList($type, $account, $status);
        if($formData->data->todoIDList) $todoIdList = $formData->data->todoIDList;

        /* Initialize todos whose need to edited. */
        foreach($allTodos as $todo)
        {
            if(in_array($todo->id, $todoIdList))
            {
                $editedTodos[$todo->id] = $todo;
                if($todo->type != 'custom')
                {
                    if(!isset($objectIdList[$todo->type])) $objectIdList[$todo->type] = array();
                    $objectIdList[$todo->type][$todo->objectID] = $todo->objectID;
                }
            }
        }

        return array($editedTodos, $objectIdList);
    }

    /**
     * 生成批量创建待办视图数据。
     * Build batch edit view.
     *
     * @access private
     * @return void
     */
    private function buildBatchEditView(): void
    {
        /* Set Custom*/
        foreach(explode(',', $this->config->todo->list->customBatchEditFields) as $field) $customFields[$field] = $this->lang->todo->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->todo->custom->batchEditFields;
        $this->view->times        = date::buildTimeList($this->config->todo->times->begin, $this->config->todo->times->end, $this->config->todo->times->delta);
        $this->view->time         = date::now();
        $this->view->title        = $this->lang->todo->common . $this->lang->colon . $this->lang->todo->batchEdit;

        $this->display();
    }

    /**
     * 处理批量编辑待办数据。
     * Build batch edit view.
     *
     * @param  object $formData
     * @access protected
     * @return array
     */
    protected function beforeBatchEdit(object $formData): array
    {
        $todos      = array();
        $data       = $formData->rawdata;
        $todoIdList = $formData->data->todoIDList ? $formData->data->todoIDList : array();

        if(!empty($todoIdList))
        {
            /* Initialize todos from the post data. */
            $oldTodos = $this->todo->getTodosByIdList($todoIdList);
            foreach($todoIdList as $todoID)
            {
                $oldTodo = $oldTodos[$todoID];

                $todo = new stdclass();
                $todo->date       = $data->dates[$todoID];
                $todo->type       = $data->types[$todoID];
                $todo->pri        = $data->pris[$todoID];
                $todo->status     = $data->status[$todoID];
                $todo->name       = !in_array($todo->type, $this->config->todo->moduleList) ? $data->names[$todoID] : '';
                $todo->begin      = isset($data->begins[$todoID]) ? $data->begins[$todoID] : 2400;
                $todo->end        = isset($data->ends[$todoID]) ? $data->ends[$todoID] : 2400;
                $todo->assignedTo = isset($data->assignedTos[$todoID]) ? $data->assignedTos[$todoID] : $oldTodo->assignedTo;

                if(in_array($todo->type, $this->config->todo->moduleList))
                {
                    $todo->objectID = isset($data->{$this->config->todo->objectList[$todo->type]}[$todoID]) ? $data->{$this->config->todo->objectList[$todo->type]}[$todoID] : 0;
                }
                if($todo->end < $todo->begin) return print(js::alert(sprintf($this->lang->error->gt, $this->lang->todo->end, $this->lang->todo->begin)));

                $todos[$todoID] = $todo;
            }
        }

        return $todos;
    }

    /**
     * 批量编辑完成待办后数据处理。
     * After Batch edit todo data.
     *
     * @param array $allChanges
     * @access protected
     * @return void
     */
    protected function afterBatchEdit(array $allChanges): void
    {
        foreach($allChanges as $todoID => $changes)
        {
            if(empty($changes)) continue;

            $actionID = $this->loadModel('action')->create('todo', $todoID, 'edited');
            $this->action->logHistory($actionID, $changes);
        }
    }

    /**
     * 处理周期待办的配置值。
     * Handle cycle config.
     *
     * @param  object $todo
     * @access private
     * @return void
     */
    private function handleCycleConfig(object $todo): void
    {
        $todo->date            = date('Y-m-d');
        $todo->config['begin'] = $todo->date;

        if($todo->config['type'] == 'day') unset($todo->config['week'], $todo->config['month']);
        if($todo->config['type'] == 'week')
        {
            unset($todo->config['day'], $todo->config['month']);
            $todo->config['week'] = join(',', $todo->config['week']);
        }
        if($todo->config['type'] == 'month')
        {
            unset($todo->config['day'], $todo->config['week']);
            $todo->config['month'] = join(',', $todo->config['month']);
        }

        $todo->config['beforeDays'] = (int)$todo->config['beforeDays'];
        $todo->config = json_encode($todo->config);
    }

    /**
     * 设置周期待办
     * Set cycle todo.
     *
     * @param  object $formData
     * @access private
     * @return object
     */
    private function setCycle(object $formData): object
    {
        $formData->date = date('Y-m-d');

        $formData->config['begin'] = $formData->date;
        if($formData->config['type'] == 'day')
        {
            unset($formData->config['week'], $formData->config['month']);
            if(!$formData->config['day'])
            {
                dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->todo->cycleDaysLabel);
                return false;
            }
            if(!validater::checkInt($formData->config['day']))
            {
                dao::$errors[] = sprintf($this->lang->error->int[0], $this->lang->todo->cycleDaysLabel);
                return false;
            }
        }
        if($formData->config['type'] == 'week')
        {
            unset($formData->config['day'], $formData->config['month']);
            $formData->config['week'] = join(',', $formData->config['week']);
        }
        if($formData->config['type'] == 'month')
        {
            unset($formData->config['day'], $formData->config['week']);
            $formData->config['month'] = join(',', $formData->config['month']);
        }

        if($formData->config['beforeDays'] and !validater::checkInt($formData->config['beforeDays']))
        {
            dao::$errors[] = sprintf($this->lang->error->int[0], $this->lang->todo->beforeDaysLabel);
            return false;
        }
        $formData->config['beforeDays'] = (int)$formData->config['beforeDays'];
        $formData->config = json_encode($formData->config);
        $formData->type   = 'cycle';

        return $formData;
    }

    /**
     * 输出开启待办事项的确认弹框。
     * Output start todo confirm alert.
     *
     * @param  object $todo
     * @access protected
     * @return int
     */
    protected function printStartConfirm(object $todo): int
    {
        $confirmNote = 'confirm' . ucfirst($todo->type);
        $confirmURL  = $this->createLink($todo->type, 'view', "id=$todo->objectID");
        $okTarget    = isonlybody() ? 'parent' : 'window.parent.$.apps.open';
        if($todo->type == 'bug')   $app = 'qa';
        if($todo->type == 'task')  $app = 'execution';
        if($todo->type == 'story') $app = 'product';
        $cancelURL = $this->server->http_referer;

        return print(js::confirm(sprintf($this->lang->todo->$confirmNote, $todo->objectID), $confirmURL, $cancelURL, $okTarget, 'parent', $app));
    }

    /**
     * Get product pairs id=>name by model.
     * 根据模型获取项目， 以键值对格式返回。
     *
     * @param  string $model
     * @return array
     */
    protected function getProjectPairsByModel(string $model): array
    {
        $model = $model == 'opportunity' ? 'waterfall' : 'all';
        return $this->loadModel('project')->getPairsByModel($model);
    }

    /**
     * 生成待办视图详情数据。
     * Build assign to todo view
     *
     * @param  object    $todo
     * @param  int       $projectID
     * @param  array     $project
     * @param  string    $account
     * @param  int       $from
     * @access protected
     * @return mixed
     */
    protected function buildAssignToTodoView(object $todo, int $projectID, array $projects, string $account, string $from)
    {
        $this->loadModel('user');
        $this->loadModel('product');

        $this->view->title           = $account == $todo->account ? "{$this->lang->todo->common} #$todo->id $todo->name" : $this->lang->todo->common;
        $this->view->user            = $this->user->getByID((string)$todo->account);
        $this->view->users           = $this->user->getPairs('noletter');
        $this->view->actions         = $this->loadModel('action')->getList('todo', (int)$todo->id);
        $this->view->position[]      = $this->lang->todo->view;
        $this->view->todo            = $todo;
        $this->view->times           = date::buildTimeList((int)$this->config->todo->times->begin, (int)$this->config->todo->times->end, 5);
        $this->view->from            = $from;
        $this->view->projects        = $projects;
        $this->view->executions      = $this->loadModel('execution')->getPairs();
        $this->view->products        = $todo->type == 'opportunity' ? $this->product->getPairsByProjectModel('waterfall') : $this->product->getPairs();
        $this->view->projectProducts = $this->product->getProductPairsByProject($projectID);

        $this->display();
    }

    /**
     * 处理指派待办请求数据。
     * Process assign todo request data.
     *
     * @param  object     $formData
     * @access protected
     * @return object
     */
    protected function beforeAssignTo(object $formData): object
    {
        $formData = $formData->get();
        $formData->assignedBy   = $this->app->user->account;
        $formData->assignedDate = helper::now();
        if($this->post->future) $formData->date = '2030-01-01';
        if($this->post->lblDisableDate)
        {
            $formData->begin = '2400';
            $formData->end   = '2400';
        }
        return $formData;
    }

    /**
     * 指派待办。
     * Assign a todo.
     *
     * @param   object     $todo
     * @access  protected
     * @return  bool
     */
    protected function doAssignTo(object $todo): bool
    {
        return $this->todo->assignTo($todo);
    }


    /**
     * 获取用户信息。
     * Get user info.
     *
     * @param  int       $userID
     * @access protected
     * @return object|false
     */
    protected function getUserById(int $userID): object|false
    {
        return $this->loadModel('user')->getById($userID, 'id');
    }

    /**
     * 记录当前页面uri参数链接。
     * Set uri to session.
     *
     * @param  string    $uri
     * @access protected
     * @return true
     */
    protected function setSessionUri(string $uri): bool
    {
        foreach($this->config->todo->sessionUri as $key => $value) $this->session->set($key, $uri, $value);
        return true;
    }

    /**
     * 获取导出待办的字段和字段。
     * Get fields and info for export todo.
     *
     * @param  array     $todos
     * @param  string    $fields
     * @param  array     $todoLang
     * @access protected
     * @return array
     */
    protected function exportTodoInfo(array $todos, string $fields, object $todoLang): array
    {
        $fields = explode(',', $fields);
        foreach($fields as $key => $fieldName)
        {
            $fieldName = trim($fieldName);
            $fields[$fieldName] = isset($todoLang->$fieldName) ? $todoLang->$fieldName : $fieldName;
            unset($fields[$key]);
        }
        unset($fields['objectID'], $fields['private']);

        if($this->config->edition != 'open') list($fields, $todos) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $todos);
        return array($todos, $fields);
    }

    /**
     * 获取待办关联的信息。
     * Get associated info for export todo.
     *
     * @param  string $type
     * @param  string $account
     * @access protected
     * @return array
     */
    protected function exportAssociated(string $type, string $account): array
    {
        if($type == 'max')
        {
            return array(
                $this->loadModel('issue')->getUserIssuePairs($account),
                $this->loadModel('risk')->getUserRiskPairs($account),
                $this->loadModel('opportunity')->getUserOpportunityPairs($account),
            );
        }
        elseif($type == 'qcVersion')
        {
            return $this->loadModel('review')->getUserReviewPairs($account, 0, 'wait');
        }
        else
        {
            return array(
                $this->loadModel('user')->getPairs('noletter'),
                $this->loadModel('bug')->getUserBugPairs($account),
                $this->loadModel('story')->getUserStoryPairs($account, 100, 'story'),
                $this->loadModel('task')->getUserTaskPairs($account),
                $this->loadModel('testtask')->getUserTesttaskPairs($account),
            );
        }
    }

    /**
     * 处理导出数据。
     * Deal with export data.
     *
     * @param array      $todos
     * @param object     $assemble
     * @param object     $todoLang
     * @param array      $times
     * @access protected
     * @return array
     */
    protected function assembleExportData(array $todos, object $assemble, object $todoLang, array $times): array
    {
        foreach($todos as $todo)
        {
            /* fill some field with useful value. */
            $todo->begin = $todo->begin == '2400' ? '' : (isset($times[$todo->begin]) ? $times[$todo->begin] : $todo->begin);
            $todo->end   = $todo->end   == '2400' ? '' : (isset($times[$todo->end])   ? $times[$todo->end] : $todo->end);

            $type = $todo->type;
            if(isset($assemble->users[$todo->account])) $todo->account = $assemble->users[$todo->account];

            if($type == 'bug')      $todo->name = isset($assemble->bugs[$todo->objectID])      ? $assemble->bugs[$todo->objectID]      . "(#$todo->objectID)" : '';
            if($type == 'task')     $todo->name = isset($assemble->tasks[$todo->objectID])     ? $assemble->tasks[$todo->objectID]     . "(#$todo->objectID)" : '';
            if($type == 'story')    $todo->name = isset($assemble->stories[$todo->objectID])   ? $assemble->stories[$todo->objectID]   . "(#$todo->objectID)" : '';
            if($type == 'testtask') $todo->name = isset($assemble->testTasks[$todo->objectID]) ? $assemble->testTasks[$todo->objectID] . "(#$todo->objectID)" : '';

            if($this->config->edition == 'max')
            {
                if($type == 'issue')       $todo->name = isset($assemble->issues[$todo->objectID]) ? $assemble->issues[$todo->objectID] . "(#$todo->objectID)" : '';
                if($type == 'risk')        $todo->name = isset($assemble->risks[$todo->objectID])  ? $assemble->risks[$todo->objectID]  . "(#$todo->objectID)" : '';
                if($type == 'opportunity') $todo->name = isset($assemble->opportunities[$todo->objectID]) ? $assemble->opportunities[$todo->objectID] . "(#$todo->objectID)" : '';
            }
            if($type == 'review' && isset($this->config->qcVersion)) $todo->name = isset($assemble->reviews[$todo->objectID]) ? $assemble->reviews[$todo->objectID] . "(#$todo->objectID)" : '';

            if(isset($todoLang->typeList[$type]))           $todo->type   = $todoLang->typeList[$type];
            if(isset($todoLang->priList[$todo->pri]))       $todo->pri    = $todoLang->priList[$todo->pri];
            if(isset($todoLang->statusList[$todo->status])) $todo->status = $todoLang->statusList[$todo->status];
            if($todo->private == 1)                         $todo->desc   = $this->lang->todo->thisIsPrivate;

            /* drop some field that is not needed. */
            unset($todo->objectID, $todo->private);
        }
        return $todos;
    }
}

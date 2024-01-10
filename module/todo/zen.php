<?php
declare(strict_types=1);
/**
 * The zen file of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      lanzongjun <lanzongjun@easycorp.ltd>
 * @link        https://www.zentao.net
 */
class todoZen extends todo
{
    /**
     * 生成创建待办视图数据。
     * Build create form data.
     *
     * @param  string    $date
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
     * @param  string    $date
     * @access protected
     * @return void
     */
    protected function buildBatchCreateView(string $date)
    {
        /* Set Custom. */
        $customFields = array();
        foreach(explode(',', $this->config->todo->list->customBatchCreateFields) as $field) $customFields[$field] = $this->lang->todo->$field;

        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->todo->custom->batchCreateFields;

        $this->view->title      = $this->lang->todo->common . $this->lang->colon . $this->lang->todo->batchCreate;
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
        $this->view->title = $this->lang->todo->common . $this->lang->colon . $this->lang->todo->edit;
        $this->view->times = date::buildTimeList($this->config->todo->times->begin, $this->config->todo->times->end, $this->config->todo->times->delta);
        $this->view->todo  = $todo;
        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted|noempty');

        $this->display();
    }

    /**
     * 生成指派待办视图数据。
     * Build assignTo form data.
     *
     * @param  int       $todoID
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
     * 处理创建待办的请求数据。
     * Processing request data of create.
     *
     * @param  form      $form
     * @access protected
     * @return object
     */
    protected function beforeCreate(form $form): object
    {
        $rawData    = $form->data;
        $objectType = $rawData->type;
        $hasObject  = in_array($objectType, $this->config->todo->moduleList);

        $objectID = 0;
        if($hasObject && $objectType) $objectID = $rawData->$objectType ? $rawData->$objectType : $rawData->objectID;
        $rawData->date = !empty($rawData->config['date']) ? $rawData->config['date'] : $rawData->date;

        return $form->add('account', $this->app->user->account)
            ->setDefault('objectID', 0)
            ->setDefault('vision', $this->config->vision)
            ->setDefault('assignedTo', $this->app->user->account)
            ->setDefault('assignedBy', $this->app->user->account)
            ->setDefault('assignedDate', helper::now())
            ->cleanInt('pri, begin, end, private')
            ->setIF($hasObject && $objectType,  'objectID', (int)$objectID)
            ->setIF(empty($rawData->date) || $this->post->switchDate || $this->post->cycle, 'date', FUTURE_TIME)
            ->setIF(empty($rawData->begin) || $this->post->switchTime, 'begin', '2400')
            ->setIF(empty($rawData->begin) || empty($rawData->end) || $this->post->switchTime, 'end', '2400')
            ->setIF($rawData->status == 'done', 'finishedBy', $this->app->user->account)
            ->setIF($rawData->status == 'done', 'finishedDate', helper::now())
            ->stripTags($this->config->todo->editor->create['id'], $this->config->allowedTags)
            ->remove(implode(',', $this->config->todo->moduleList) . ',uid')
            ->get();
    }

    /**
     * 添加按年循环待办的配置项。
     * Adds a yearly cycle of configuration items.
     *
     * @param  form      $form
     * @access protected
     * @return form
     */
    protected function addCycleYearConfig(form $form): form
    {
        /* Only handle cases where you add to the backlog by year. */
        if(empty($form->data->config)) return $form;
        if(!empty($form->data->config) && $form->data->config['type'] != 'year') return $form;

        $form->data->config['type']          = 'day';
        $form->data->config['specifiedDate'] = 1;
        $form->data->config['cycleYear']     = 1;

        return $form;
    }

    /**
     * 准备要创建的todo的数据。
     * Prepare the creation data.
     *
     * @param  object       $todo
     * @access protected
     * @return object|false
     */
    protected function prepareCreateData(object $todo): object|false
    {
        if(!isset($todo->pri) && in_array($todo->type, $this->config->todo->moduleList) && !in_array($todo->type, array('review', 'feedback')))
        {
            $todo->pri = $this->todo->getPriByTodoType($this->config->objectTables[$todo->type], $todo->objectID);

            if($todo->pri == 'high')   $todo->pri = 1;
            if($todo->pri == 'middle') $todo->pri = 2;
            if($todo->pri == 'low')    $todo->pri = 3;
        }

        if($todo->type != 'custom' && !empty($todo->objectID))
        {
            $type   = $todo->type;
            $object = $this->loadModel($type)->getByID($todo->objectID);
            if(isset($object->name))  $todo->name = $object->name;
            if(isset($object->title)) $todo->name = $object->title;
        }

        if($todo->end < $todo->begin)
        {
            dao::$errors['end'] = sprintf($this->lang->error->gt, $this->lang->todo->end, $this->lang->todo->begin);
            return false;
        }

        if(!empty($todo->cycle))
        {
            $todo = $this->setCycle($todo);
            if(!$todo) return false;
        }
        if(empty($todo->cycle)) unset($todo->config);

        if($todo->private) $todo->assignedTo = $todo->assignedBy = $this->app->user->account;

        $this->loadModel('file')->processImgURL($todo, $this->config->todo->editor->create['id'], $this->post->uid);

        return $todo;
    }

    /**
     * 创建完成待办后数据处理。
     * Create a todo after data processing.
     *
     * @param  object    $todo
     * @param  form      $form
     * @access protected
     * @return object
     */
    protected function afterCreate(object $todo, form $form): object
    {
        if(isset($form->data->uid)) $this->loadModel('file')->updateObjectID($form->data->uid, $todo->id, 'todo');

        $this->loadModel('score')->create('todo', 'create', $todo->id);

        if(!empty($todo->cycle)) $this->todo->createByCycle(array($todo->id => $todo));

        $this->loadModel('action')->create('todo', $todo->id, 'opened');

        return $todo;
    }

    /**
     * 处理批量创建待办的请求数据。
     * Processing request data of batch create todo.
     *
     * @param  form      $form
     * @access protected
     * @return object
     */
    protected function beforeBatchCreate(form $form): array
    {
        $todos = $form->get();
        foreach($todos as $todo)
        {
            $todo->date = $this->post->futureDate ? FUTURE_TIME : $this->post->date;
            if(!empty($todo->switchTime))
            {
                $todo->begin = '2400';
                $todo->end   = '2400';
            }
            if($todo->type != 'custom') $todo->objectID = (int)$todo->name;

            unset($todo->switchTime);
        }
        return $todos;
    }

    /**
     * 处理编辑待办的请求数据。
     * Processing edit request data.
     *
     * @param  int          $todoID
     * @param  form         $form
     * @access protected
     * @return object|false
     */
    protected function beforeEdit(int $todoID, form $form): object|false
    {
        $oldTodo = $this->dao->findByID($todoID)->from(TABLE_TODO)->fetch();

        $objectID   = 0;
        $postData   = $form->get();
        $objectType = !empty($postData->type) ? $postData->type : $oldTodo->type;
        $hasObject  = in_array($objectType, $this->config->todo->moduleList);

        if($hasObject && $objectType) $objectID = $this->post->$objectType ? $this->post->$objectType : $this->post->objectID;
        /* Cycle todo date Replaces the todo date. */
        $postData->date = !empty($postData->config['date']) ? $postData->config['date'] : $postData->date;

        /* Process todo. */
        $todo = $form->add('account', $oldTodo->account)
            ->cleanInt('pri, begin, end, private')
            ->setIF(in_array($objectType, array('bug', 'task', 'story')), 'name', '')
            ->setIF($hasObject && $objectType,  'objectID', $objectID)
            ->setIF(empty($postData->date) || $this->post->switchDate || $this->post->cycle, 'date', FUTURE_TIME)
            ->setIF(empty($postData->begin) || $this->post->dateSwitcher, 'begin', '2400')
            ->setIF(empty($postData->end) || $this->post->dateSwitcher, 'end', '2400')
            ->setDefault('assignedBy', $oldTodo->assignedTo != $this->post->assignedTo ? $this->app->user->account : $oldTodo->assignedBy)
            ->setDefault('type', $objectType)
            ->setDefault('private', 0)
            ->stripTags($this->config->todo->editor->edit['id'], $this->config->allowedTags)
            ->remove(implode(',', $this->config->todo->moduleList) . ',uid')
            ->get();

        /* Non-custom type Gets the backlog name based on the type id. */
        if(in_array($todo->type, $this->config->todo->moduleList))
        {
            $type   = $todo->type;
            $object = $this->loadModel($type)->getByID((int)$objectID);
            if(isset($object->name))  $todo->name = $object->name;
            if(isset($object->title)) $todo->name = $object->title;
        }

        $requiredFields = isset($todo->type) && in_array($todo->type, $this->config->todo->moduleList) ? str_replace(',name,', ',', ",{$this->config->todo->edit->requiredFields},") : $this->config->todo->edit->requiredFields;
        $requiredFields = trim($requiredFields, ',');
        foreach(explode(',', $requiredFields) as $field)
        {
            if(!empty($field) && empty($todo->$field)) dao::$errors[$field] = sprintf($this->lang->error->notempty, $this->lang->todo->$field);
        }
        if($hasObject && !$objectID)
        {
            dao::$errors[$todo->type] = sprintf($this->lang->error->notempty, $this->lang->todo->name);
            unset(dao::$errors['objectID']);
        }
        if($todo->end < $todo->begin) dao::$errors['end']       = sprintf($this->lang->error->gt, $this->lang->todo->end, $this->lang->todo->begin);
        if(dao::isError()) return false;

        /* Handle cycle configuration item. */
        if(!empty($oldTodo->cycle)) $this->handleCycleConfig($todo);
        if(empty($oldTodo->cycle))  $todo->config = '';

        if($todo->private) $todo->assignedTo = $todo->assignedBy = $this->app->user->account;

        return $this->loadModel('file')->processImgURL($todo, $this->config->todo->editor->edit['id'], $this->post->uid);
    }

    /**
     * 编辑完成待办后数据处理。
     * Handle data after edit todo.
     *
     * @param  object    $todo
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
     * @param  array|false $todoIdList
     * @param  string      $type
     * @param  int         $userID
     * @param  string      $status
     * @access protected
     * @return void
     */
    protected function batchEditFromMyTodo(array|false $todoIdList, string $type, int $userID, string $status): void
    {
        /* Initialize vars. */
        if(empty($todoIdList)) $todoIdList = array();
        $editedTodos = $objectIdList = $reviews = array();
        $columns     = 7;
        unset($this->lang->todo->typeList['cycle']);

        if(empty($userID)) $userID = $this->app->user->id;
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;

        list($editedTodos, $objectIdList) = $this->getBatchEditInitTodos($todoIdList, $type, $account, $status);
        $editedTodos = array_map(function($item) { $item->begin = str_replace(':', '', $item->begin); $item->end = str_replace(':', '', $item->end); return $item;}, $editedTodos);

        $bugs      = $this->loadModel('bug')->getUserBugPairs($account, true, 0, array(), array(), isset($objectIdList['bug']) ? $objectIdList['bug'] : array());
        $tasks     = $this->loadModel('task')->getUserTaskPairs($account, 'wait,doing', array(), isset($objectIdList['task']) ? $objectIdList['task'] : array());
        $stories   = $this->loadModel('story')->getUserStoryPairs($account, 10, 'story', '', isset($objectIdList['story']) ? $objectIdList['story'] : array());
        $users     = $this->loadModel('user')->getPairs('noclosed|nodeleted|noempty');
        $testtasks = $this->loadModel('testtask')->getUserTestTaskPairs($account);
        if($this->config->edition != 'open') $feedbacks = $this->loadModel('feedback')->getUserFeedbackPairs($account, '', isset($objectIdList['feedback']) ? $objectIdList['feedback'] : '');
        if(in_array($this->config->edition, array('max', 'ipd')))
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
        $this->view->bugs        = $bugs;
        $this->view->tasks       = $tasks;
        $this->view->stories     = $stories;
        $this->view->reviews     = $reviews;
        $this->view->testtasks   = $testtasks;
        $this->view->editedTodos = $editedTodos;
        $this->view->users       = $users;
        $this->view->type        = $type;
        $this->view->userID      = $userID;
        $this->view->status      = $status;
        if($this->config->edition != 'open') $this->view->feedback = $feedbacks;
        if(in_array($this->config->edition, array('max', 'ipd')))
        {
            $this->view->issues        = $issues;
            $this->view->risks         = $risks;
            $this->view->opportunities = $opportunities;
        }

        $this->buildBatchEditView();
    }

    /**
     * 获取批量编辑页面初始化待办数据。
     * Get batch edit page initialization todo data.
     *
     * @param  array     $todoIdList
     * @param  string    $type
     * @param  string    $account
     * @param  string    $status
     * @access protected
     * @return array
     */
    private function getBatchEditInitTodos(array $todoIdList, string $type, string $account, string $status): array
    {
        $editedTodos  = array();
        $objectIdList = array();

        $allTodos = $this->todo->getList($type, $account, $status);
        if($this->post->todoIdList) $todoIdList = $this->post->todoIdList;

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
     * @param  array      $todos
     * @access protected
     * @return array
     */
    protected function beforeBatchEdit(array $todos): array
    {
        if(empty($todos)) return array();

        /* Initialize todos from the post data. */
        foreach($todos as $todoID => $todo)
        {
            if(in_array($todo->type, $this->config->todo->moduleList))
            {
                $todo->objectID   = $todo->{$todo->type};
                $todo->name       = '';
            }
            unset($todo->story, $todo->task, $todo->bug, $todo->testtask);

            $todo->begin = empty($todo->begin) || $this->post->switchTime ? 2400 : $todo->begin;
            $todo->end   = empty($todo->end) || $this->post->switchTime   ? 2400 : $todo->end;

            if($todo->end < $todo->begin)
            {
                dao::$errors["begin[{$todoID}]"] = sprintf($this->lang->error->gt, $this->lang->todo->end, $this->lang->todo->begin);
                continue;
            }
        }

        return $todos;
    }

    /**
     * 批量编辑完成待办后数据处理。
     * After Batch edit todo data.
     *
     * @param  array     $allChanges
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
     * @param  object  $todo
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
            if(!is_array($todo->config['week'])) $todo->config['week'] = (array)$todo->config['week'];
            $todo->config['week'] = implode(',', $todo->config['week']);
        }
        if($todo->config['type'] == 'month')
        {
            unset($todo->config['day'], $todo->config['week']);
            if(!is_array($todo->config['month'])) $todo->config['month'] = (array)$todo->config['month'];
            $todo->config['month'] = implode(',', $todo->config['month']);
        }

        $todo->config['beforeDays'] = !empty($todo->config['beforeDays']) ? $todo->config['beforeDays'] : 0;
        $todo->config = json_encode($todo->config);
    }

    /**
     * 设置周期待办数据。
     * Set cycle todo data.
     *
     * @param  object       $todoData
     * @access private
     * @return false|object
     */
    private function setCycle(object $todoData): false|object
    {
        $todoData->date = helper::today();
        $todoData->config['begin'] = $todoData->date;

        if($todoData->config['type'] == 'day')
        {
            unset($todoData->config['week'], $todoData->config['month']);
            if(empty($todoData->config['specifiedDate']))
            {
                if(empty($todoData->config['day']))
                {
                    dao::$errors['config[day]'] = sprintf($this->lang->error->notempty, $this->lang->todo->cycleDaysLabel);
                    return false;
                }
                if(!validater::checkInt($todoData->config['day']))
                {
                    dao::$errors['config[day]'] = sprintf($this->lang->error->int[0], $this->lang->todo->cycleDaysLabel);
                    return false;
                }
            }
            else
            {
                unset($todoData->config['day']);
            }
        }
        if($todoData->config['type'] == 'week')
        {
            unset($todoData->config['day'], $todoData->config['month']);
            if(!is_array($todoData->config['week'])) $todoData->config['week'] = (array)$todoData->config['week'];
            $todoData->config['week'] = implode(',', $todoData->config['week']);
        }
        if($todoData->config['type'] == 'month')
        {
            unset($todoData->config['day'], $todoData->config['week']);
            if(!is_array($todoData->config['month'])) $todoData->config['month'] = (array)$todoData->config['month'];
            $todoData->config['month'] = implode(',', $todoData->config['month']);
        }

        if(!empty($todoData->config['beforeDays']) && !validater::checkInt($todoData->config['beforeDays']))
        {
            dao::$errors['config[beforeDays]'] = sprintf($this->lang->error->int[0], $this->lang->todo->beforeDaysLabel);
            return false;
        }
        $todoData->config['beforeDays'] = !empty($todoData->config['beforeDays']) ? $todoData->config['beforeDays'] : 0;

        $todoData->config = json_encode($todoData->config);
        $todoData->type   = 'cycle';

        return $todoData;
    }

    /**
     * 输出开启待办事项的确认弹框。
     * Output start todo confirm alert.
     *
     * @param  object    $todo
     * @access protected
     * @return int
     */
    protected function printStartConfirm(object $todo): int
    {
        $confirmNote = 'confirm' . ucfirst($todo->type);
        $confirmURL  = $this->createLink($todo->type, 'view', "id=$todo->objectID");
        if($todo->type == 'bug')   $app = 'qa';
        if($todo->type == 'task')  $app = 'execution';
        if($todo->type == 'story') $app = 'product';
        $cancelURL = $this->session->todoList ? $this->session->todoList : $this->createLink('my', 'todo');

        return $this->send(array('result' => 'success', 'closeModal' => true, 'load' => array('confirm' => sprintf($this->lang->todo->{$confirmNote}, $todo->objectID), 'confirmed' => $confirmURL, 'canceled' => $cancelURL)));
    }

    /**
     * 根据模型获取项目， 以键值对格式返回。
     * Get product pairs id=>name by model.
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

        $executionPairs = array();
        $executions     = $projects ? $this->loadModel('execution')->getByProject(key($projects), 'undone') : array();
        foreach($executions as $execution) $executionPairs[$execution->id] = $execution->name;

        $this->view->title           = $account == $todo->account ? "{$this->lang->todo->common} #$todo->id $todo->name" : $this->lang->todo->common;
        $this->view->user            = $this->user->getByID((string)$todo->account);
        $this->view->users           = $this->user->getPairs('noletter');
        $this->view->actions         = $this->loadModel('action')->getList('todo', (int)$todo->id);
        $this->view->todo            = $todo;
        $this->view->times           = date::buildTimeList((int)$this->config->todo->times->begin, (int)$this->config->todo->times->end, 5);
        $this->view->from            = $from;
        $this->view->projects        = $projects;
        $this->view->executions      = $executionPairs;
        $this->view->products        = $todo->type == 'opportunity' ? $this->product->getPairsByProjectModel('waterfall') : $this->product->getPairs();
        $this->view->projectProducts = $this->product->getProductPairsByProject($projectID);

        $this->display();
    }

    /**
     * 处理指派待办请求数据。
     * Process assign todo request data.
     *
     * @param  object    $formData
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
     * @param  object    $todo
     * @access protected
     * @return bool
     */
    protected function doAssignTo(object $todo): bool
    {
        return $this->todo->assignTo($todo);
    }


    /**
     * 获取用户信息。
     * Get user info.
     *
     * @param  int          $userID
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
     * @param  string    $type
     * @param  string    $account
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
     * @param  array     $todos
     * @param  object    $assemble
     * @param  object    $todoLang
     * @param  array     $times
     * @access protected
     * @return array
     */
    protected function assembleExportData(array $todos, object $assemble, object $todoLang, array $times): array
    {
        foreach($todos as $todo)
        {
            $begin = isset($times[$todo->begin]) ? $times[$todo->begin] : $todo->begin;
            $end   = isset($times[$todo->end])   ? $times[$todo->end] : $todo->end;

            /* fill some field with useful value. */
            $todo->begin = $todo->begin == '2400' ? '' : $begin;
            $todo->end   = $todo->end   == '2400' ? '' : $end;

            $todo->assignedTo = zget($assemble->users, $todo->assignedTo);

            $type = $todo->type;
            if(isset($assemble->users[$todo->account])) $todo->account = $assemble->users[$todo->account];

            if($type == 'bug')      $todo->name = isset($assemble->bugs[$todo->objectID])      ? $assemble->bugs[$todo->objectID]      . "(#$todo->objectID)" : '';
            if($type == 'task')     $todo->name = isset($assemble->tasks[$todo->objectID])     ? $assemble->tasks[$todo->objectID]     . "(#$todo->objectID)" : '';
            if($type == 'story')    $todo->name = isset($assemble->stories[$todo->objectID])   ? $assemble->stories[$todo->objectID]   . "(#$todo->objectID)" : '';
            if($type == 'testtask') $todo->name = isset($assemble->testTasks[$todo->objectID]) ? $assemble->testTasks[$todo->objectID] . "(#$todo->objectID)" : '';

            if(in_array($this->config->edition, array('max', 'ipd')))
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

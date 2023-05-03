<?php
declare(strict_types=1);

/**
 * The control file of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Lanzongjun <lanzongjun@easycorp.ltd>
 * @package     todo
 * @link        https://www.zentao.net
 */
class todo extends control
{
    /**
     * Construct function, load model of task, bug, my.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->app->loadClass('date');
        $this->loadModel('task');
        $this->loadModel('bug');
        $this->app->loadLang('my');
    }

    /**
     * 创建待办。
     * Create a todo.
     *
     * @param  string  $date
     * @param  string  $from todo|feedback|block
     * @access public
     * @return int
     */
    public function create(string $date = 'today', string $from = 'todo'): int
    {
        if($date == 'today') $date = date::today();

        if(!empty($_POST))
        {
            $formData = form::data($this->config->todo->create->form);
            $todo     = $this->todoZen->beforeCreate($formData);

            $todoID = $this->todo->create($todo);
            if($todoID === false) return print(js::error(dao::getError()));

            $todo->id = $todoID;
            $this->todoZen->afterCreate($todo);

            if(!empty($_POST['objectID'])) return $this->send(array('result' => 'success'));

            if($from == 'block')
            {
                $todo = $this->todo->getByID($todoID);
                $todo->begin = date::formatTime($todo->begin);
                return $this->send(array('result' => 'success', 'id' => $todoID, 'name' => $todo->name, 'pri' => $todo->pri, 'priName' => $this->lang->todo->priList[$todo->pri], 'time' => date(DT_DATE4, strtotime($todo->date)) . ' ' . $todo->begin));
            }

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $todoID));
            if($this->viewType == 'xhtml') return print(js::locate($this->createLink('todo', 'view', "todoID=$todoID", 'html'), 'parent'));
            if(isonlybody()) return print(js::closeModal('parent.parent'));
            return print(js::locate($this->createLink('my', 'todo', "type=all&userID=&status=all&orderBy=id_desc"), 'parent'));
        }

        unset($this->lang->todo->typeList['cycle']);

        $this->buildCreateView($date);
    }

    /**
     * Batch create todo
     *
     * @param  string $date
     * @access public
     * @return void
     */
    public function batchCreate($date = 'today')
    {
        if($date == 'today') $date = date(DT_DATE1, time());
        if(!empty($_POST))
        {
            $todoIDList = $this->todo->batchCreate();
            if(dao::isError()) return print(js::error(dao::getError()));

            /* Locate the browser. */
            $date = str_replace('-', '', $this->post->date);
            if($date == '')
            {
                $date = 'future';
            }
            else if($date == date('Ymd'))
            {
                $date= 'today';
            }

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $todoIDList));
            if(isonlybody()) return print(js::reload('parent.parent'));
            return print(js::locate($this->createLink('my', 'todo', "type=$date"), 'parent'));
        }

        unset($this->lang->todo->typeList['cycle']);

        /* Set Custom*/
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
     * 编辑待办数据。
     * Edit a todo.
     *
     * @param  string $todoID
     * @access public
     * @return void
     */
    public function edit(string $todoID)
    {
        $todoID = (int)$todoID;
        if(!empty($_POST))
        {
            $formData = form::data($this->config->todo->edit->form);

            $todo = $this->todoZen->beforeEdit($todoID, $formData);
            if(dao::isError())
            {
                if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => dao::getError()));
                return print(js::error(dao::getError()));
            }

            $changes = $this->todo->update($todoID, $todo);
            if(dao::isError())
            {
                if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => dao::getError()));
                return print(js::error(dao::getError()));
            }

            $this->todoZen->afterEdit($todoID, $changes);

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
            return print(js::locate($this->session->todoList, 'parent.parent'));
        }

        /* Judge a private todo or not, If private, die. */
        $todo = $this->todo->getByID($todoID);
        if($todo->private and $this->app->user->account != $todo->account) return print('private');

        unset($this->lang->todo->typeList['cycle']);

        $this->todoZen->buildEditView($todo);

        return;
    }

    /**
     * Batch edit todo.
     *
     * @param  string $from example:myTodo, todoBatchEdit.
     * @param  string $type
     * @param  int    $userID
     * @param  string $status
     * @access public
     * @return void
     */
    public function batchEdit(string $from = '', string $type = 'today', string $userID = '', string $status = 'all')
    {
        /* Get form data for my-todo. */
        if($from == 'myTodo') $this->todoZen->batchEditFromMyTodo($type, $userID, $status);
        if($from == 'todoBatchEdit')
        {
            $formData = form::data($this->config->todo->batchEdit->form);
            $this->todoZen->batchEditFromTodoBatchEdit($formData);
        }
    }

    /**
     * 开启待办事项。
     * Start a todo.
     *
     * @param  string $todoID
     * @access public
     * @return int
     */
    public function start(string $todoID): int
    {
        $todoID = (int)$todoID;
        $todo   = $this->todo->getByID($todoID);

        if($todo->status == 'wait')
        {
            this->todo->start($todoID);
            if(dao::isError()) return print(js::error(dao::getError()));
        }
        if(in_array($todo->type, array('bug', 'task', 'story'))) return $this->todoZen->printStartConfirm($todo);
        if(isonlybody()) return print(js::reload('parent.parent'));

        return print(js::reload('parent'));
    }

    /**
     * 激活待办事项。
     * Activated a todo.
     *
     * @param  string $todoID
     * @access public
     * @return int
     */
    public function activate(string $todoID): int
    {
        $todoID = (int)$todoID;
        $todo   = $this->todo->getByID($todoID);

        if($todo->status == 'done' || $todo->status == 'closed')
        {
            $this->todo->activate($todoID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }
        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
        if(isonlybody()) return print(js::reload('parent.parent'));

        return print(js::reload('parent'));
    }

    /**
     * 关闭待办。
     * Close one todo.
     *
     * @param  string $todoID
     * @access public
     * @return int
     */
    public function close(string $todoID): int
    {
        $todoID = (int)$todoID;
        $todo   = $this->todo->getByID($todoID);
        if($todo->status == 'done')
        {
            $isClosed = $this->todo->close($todoID);
            if(!$isClosed) return print(js::error(dao::getError()));
        }

        if(defined('RUN_MODE') && RUN_MODE == 'api')
        {
            $this->send(array('status' => 'success'));
            return 1; /* A function with return type must return a value. */
        }

        if(isonlybody()) return print(js::reload('parent.parent'));

        return print(js::reload('parent'));
    }

    /**
     * 指派待办。
     * Assign todo.
     *
     * @param  string $todoID
     * @access public
     * @return int
     */
    public function assignTo(string $todoID): int
    {
        $todoID = (int)$todoID;
        if(!empty($_POST))
        {
            $formData = form::data($this->config->todo->assignTo->form);
            $todo     = $this->todoZen->beforeAssignTo($formData);

            $todo->id   = $todoID;
            $isAssigned = $this->todoZen->doAssignTo($todo);
            if(!$isAssigned) return print(js::error(dao::getError()));

            return print(js::reload('parent.parent'));
        }

        $this->view->todo    = $this->todo->getByID($todoID);
        $this->view->members = $this->loadModel('user')->getPairs('noclosed|noempty|nodeleted');
        $this->view->times   = date::buildTimeList($this->config->todo->times->begin, $this->config->todo->times->end, $this->config->todo->times->delta);
        $this->view->actions = $this->loadModel('action')->getList('todo', $todoID);
        $this->view->users   = $this->user->getPairs('noletter');
        $this->view->time    = date::now();
        $this->display();
    }

    /**
     * 获取待办的信息。
     * Get info of todo .
     *
     * @param string $todoID
     * @param string $from   my|company
     *
     * @access public
     * @return void
     */
    public function view(string $todoID, string $from = 'company')
    {
        $todo = $this->todo->getByID((int)$todoID, true);

        if(!$todo)
        {
            if((defined('RUN_MODE') && RUN_MODE == 'api') or $this->app->viewType == 'json') return $this->send(array('status' => 'fail', 'message' => '404 Not found'));
            return print(js::error((string)$this->lang->notFound) . (string)js::locate('back'));
        }

        $account = $this->app->user->account;
        if($todo->private and $todo->account != $account) return print(js::error((string)$this->lang->todo->thisIsPrivate) . (string)js::locate('back'));

        /* Save the session. */
        if(!isonlybody())
        {
            $url = $this->app->getURI(true);
            $this->session->set('bugList',      $url, 'qa');
            $this->session->set('taskList',     $url, 'execution');
            $this->session->set('storyList',    $url, 'product');
            $this->session->set('testtaskList', $url, 'qa');
        }

        /* Fix bug #936. */
        if($account != $todo->account and $account != $todo->assignedTo and !common::hasPriv('my', 'team'))
        {
            $this->locate($this->createLink('user', 'deny', "module=my&method=team"));
        }

        $projects = $this->todoZen->getProjectPairsByModel((string)$todo->type);
        if(!isset($this->session->project)) $this->session->set('project', (int)key($projects));

        $this->todoZen->buildAssignToTodoView((object)$todo, (int)$this->session->project, (array)$projects, (string)$account, $from);
    }

    /**
     * 删除待办。
     * Delete a todo.
     *
     * @param  string  $todoID
     * @param  string  $confirm yes|no
     * @access public
     * @return void
     */
    public function delete(string $todoID, string $confirm = 'no')
    {
        $todoID = (int)$todoID;
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->todo->confirmDelete, $this->createLink('todo', 'delete', "todoID={$todoID}&confirm=yes")));
        }
        else
        {
            $this->todo->delete(TABLE_TODO, $todoID);

            if(helper::isAjaxRequest())
            {
                $response = array('result' => 'success', 'message' => '');
                if(dao::isError())
                {
                    $response['result']  = 'fail';
                    $response['message'] = dao::getError();
                }
                return $this->send($response);
            }

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
            if(isonlybody()) return print(js::reload('parent.parent'));

            $browseLink = $this->session->todoList ? $this->session->todoList : $this->createLink('my', 'todo');
            return print(js::locate($browseLink, 'parent'));
        }
    }

    /**
     * 完成待办.
     * Finish a todo.
     *
     * @param  string  $todoID
     * @access public
     * @return void
     */
    public function finish(string $todoID)
    {
        $todo = $this->todo->getByID((int)$todoID);
        if($todo->status != 'done' && $todo->status != 'closed') $this->todo->finish((int)$todoID);

        if(in_array($todo->type, array('bug', 'task', 'story')))
        {
            $confirmNote = 'confirm' . ucfirst($todo->type);
            $okTarget    = isonlybody() ? 'parent' : 'window.parent.$.apps.open';
            $confirmURL  = $this->createLink($todo->type, 'view', "id=$todo->objectID");

            if($todo->type == 'bug')   $app = 'qa';
            if($todo->type == 'task')  $app = 'execution';
            if($todo->type == 'story') $app = 'product';
            $cancelURL   = $this->server->http_referer;
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'message' => sprintf($this->lang->todo->$confirmNote, $todo->objectID), 'locate' => $confirmURL));
            return print(strpos($cancelURL, 'calendar') ? json_encode(array(sprintf($this->lang->todo->$confirmNote, $todo->objectID), $confirmURL)) : js::confirm(sprintf($this->lang->todo->$confirmNote, $todo->objectID), $confirmURL, $cancelURL, $okTarget, 'parent', $app));
        }

        if(defined('RUN_MODE') && RUN_MODE == 'api')
        {
            $this->send(array('status' => 'success'));
            return;
        }
        if(isonlybody()) return print(js::reload('parent.parent'));
        echo js::reload('parent');
    }

    /**
     * 批量完成待办.
     * Batch finish todos.
     *
     * @access public
     * @return void
     */
    public function batchFinish()
    {
        if($this->post->todoIDList)
        {
            $todoList = $this->todo->getByList((array)$this->post->todoIDList);
            foreach($todoList as $todoID => $todo)
            {
                if($todo->status == 'done' || $todo->status == 'closed') unset($todoList[$todoID]);
            }

            $isBatchFinished = $this->todo->batchFinish(array_keys($todoList));
            if(!$isBatchFinished) return false;

            return print(js::reload('parent'));
        }
    }

    /**
     * 批量关闭待办。只有完成的待办才能关闭。
     * Batch close todos. The status of todo which need to close should be done.
     *
     * @access public
     * @return int
     */
    public function batchClose(): int
    {
        $waitIdList = array();
        $todoIdlist = form::data($this->config->todo->batchClose->form)->get('todoIDList');
        foreach($todoIdlist as $todoID)
        {
            $todoID = (int) $todoID;
            $todo   = $this->todo->getByID($todoID);
            if($todo->status == 'done') $this->todo->close($todoID);
            if($todo->status != 'done' and $todo->status != 'closed') $waitIdList[] = $todoID;
        }
        if(!empty($waitIdList)) echo js::alert(sprintf($this->lang->todo->unfinishedTodo, implode(',', $waitIdList)));

        return print(js::reload('parent'));
    }

    /**
     * 修改选中待办的日期。
     * Import selected todos to today.
     *
     * @param  string $todoID
     * @access public
     * @return void
     */
    public function import2Today(string $todoID = '')
    {
        $todoIDList = $_POST ? $this->post->todoIDList : array($todoID);
        $date       = !empty($_POST['date']) ? $_POST['date'] : date::today();
        if(!$date || !$todoIDList) return $this->locate((string)$this->session->todoList);

        $this->todo->editDate((array)$todoIDList, (string)$date);
        return $this->locate((string)$this->session->todoList);
    }

    /**
     * Get data to export
     *
     * @param  int    $userID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function export(string $userID, string $orderBy)
    {
        if($_POST)
        {
            $user     = $this->loadModel('user')->getById($userID, 'id');
            $account  = $user->account;
            $todoLang = $this->lang->todo;

            /* Create field lists. */
            $fields = $this->todoZen->exportFields(explode(',', $this->config->todo->list->exportFields), (object)$todoLang);

            /* Get bugs. */
            $todos = $this->dao->select('*')->from(TABLE_TODO)->where($this->session->todoReportCondition)
                ->beginIF($this->post->exportType == 'selected')->andWhere('id')->in($this->cookie->checkedItem)->fi()
                ->orderBy($orderBy)->fetchAll('id');

            /* Get users, bugs, tasks and times. */
            list($users, $bugs, $stories, $tasks, $testTasks) = $this->todoZen->exportInfo('default', $account);

            if($this->config->edition == 'max') list($issues, $risks, $opportunities) = $this->todoZen->exportInfo($this->config->edition, $account);

            if(isset($this->config->qcVersion)) $reviews = $this->todoZen->exportInfo('qcVersion', $account);
            $times = date::buildTimeList($this->config->todo->times->begin, $this->config->todo->times->end, $this->config->todo->times->delta);

            foreach($todos as $todo)
            {
                /* fill some field with useful value. */
                $todo->begin = $todo->begin == '2400' ? '' : (isset($times[$todo->begin]) ? $times[$todo->begin] : $todo->begin);
                $todo->end   = $todo->end   == '2400' ? '' : (isset($times[$todo->end])   ? $times[$todo->end] : $todo->end);

                $type = $todo->type;
                if(isset($users[$todo->account])) $todo->account = $users[$todo->account];
                if($type == 'bug')                $todo->name    = isset($bugs[$todo->objectID])    ? $bugs[$todo->objectID] . "(#$todo->objectID)" : '';
                if($type == 'story')              $todo->name    = isset($stories[$todo->objectID]) ? $stories[$todo->objectID] . "(#$todo->objectID)" : '';
                if($type == 'task')               $todo->name    = isset($tasks[$todo->objectID])   ? $tasks[$todo->objectID] . "(#$todo->objectID)" : '';

                if($this->config->edition == 'max')
                {
                    if($type == 'issue') $todo->name = isset($issues[$todo->objectID]) ? $issues[$todo->objectID] . "(#$todo->objectID)" : '';
                    if($type == 'risk')  $todo->name = isset($risks[$todo->objectID])  ? $risks[$todo->objectID] . "(#$todo->objectID)" : '';
                    if($type == 'opportunity')  $todo->name = isset($opportunities[$todo->objectID])  ? $opportunities[$todo->objectID] . "(#$todo->objectID)" : '';
                }
                if($type == 'testtask')           $todo->name    = isset($testTasks[$todo->objectID]) ? $testTasks[$todo->objectID] . "(#$todo->objectID)" : '';
                if($type == 'review' && isset($this->config->qcVersion)) $todo->name = isset($reviews[$todo->objectID]) ? $reviews[$todo->objectID] . "(#$todo->objectID)" : '';

                if(isset($todoLang->typeList[$type]))           $todo->type    = $todoLang->typeList[$type];
                if(isset($todoLang->priList[$todo->pri]))       $todo->pri     = $todoLang->priList[$todo->pri];
                if(isset($todoLang->statusList[$todo->status])) $todo->status  = $todoLang->statusList[$todo->status];
                if($todo->private == 1)                         $todo->desc    = $this->lang->todo->thisIsPrivate;

                /* drop some field that is not needed. */
                unset($todo->objectID);
                unset($todo->private);
            }
            if($this->config->edition != 'open') list($fields, $todos) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $todos);

            $this->post->set('fields', $fields);
            $this->post->set('rows', $todos);
            $this->post->set('kind', 'todo');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $this->view->fileName = $this->app->user->account . ' - ' . $this->lang->todo->common;
        $this->display();
    }

    /**
     * AJAX: get actions of a todo. for web app.
     *
     * @param  int    $todoID
     * @access public
     * @return void
     */
    public function ajaxGetDetail($todoID)
    {
        $this->view->actions = $this->loadModel('action')->getList('todo', $todoID);
        $this->display();
    }

    /**
     * AJAX: get program id.
     *
     * @param  int     $objectID
     * @param  string  $objectType
     * @access public
     * @return void
     */
    public function ajaxGetProgramID($objectID, $objectType)
    {
        $table = $objectType == 'project' ? TABLE_PROJECT : TABLE_PRODUCT;
        $field = $objectType == 'project' ? 'parent' : 'program';
        echo $this->dao->select($field)->from($table)->where('id')->eq($objectID)->fetch($field);
    }

    /**
     * AJAX: get execution pairs.
     *
     * @param  int     $projectID
     * @access public
     * @return void
     */
    public function ajaxGetExecutionPairs($projectID)
    {
        $this->session->set('project', $projectID);

        $project    = $this->loadModel('project')->getByID($projectID);
        $executions = $this->loadModel('execution')->getByProject($projectID, 'undone');
        foreach($executions as $id => $execution) $executions[$id] = $execution->name;

        echo html::select('execution', $executions, '', "class='form-control chosen'");
        echo "<script>toggleExecution({$project->multiple});</script>";
    }

    /**
     * AJAX: get product pairs.
     *
     * @param  int     $projectID
     * @access public
     * @return void
     */
    public function ajaxGetProductPairs($projectID)
    {
        $this->session->set('project', $projectID);

        $products = $this->loadModel('product')->getProductPairsByProject($projectID);
        echo html::select('bugProduct', $products, '', "class='form-control chosen'");
    }

    /**
     * Create cycle.
     *
     * @access public
     * @return void
     */
    public function createCycle()
    {
        $todoList = $this->dao->select('*')->from(TABLE_TODO)->where('cycle')->eq(1)->andWhere('deleted')->eq(0)->fetchAll('id');
        $this->todo->createByCycle($todoList);
    }
}

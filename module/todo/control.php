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
     * Construct function
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->app->loadClass('date');
        $this->app->loadLang('my');
    }

    /**
     * 创建待办。
     * Create a todo.
     *
     * @param  string  $date
     * @param  string  $from todo|feedback|block
     * @access public
     * @return void
     */
    public function create(string $date = 'today', string $from = 'todo')
    {
        $this->app->loadClass('date');
        if($date == 'today') $date = date::today();

        if(!empty($_POST))
        {
            $formData = form::data($this->config->todo->create->form);
            $todoData = $this->todoZen->beforeCreate($formData);

            $uid  = isset($formData->rawdata->uid) ? $formData->rawdata->uid : '';
            $todo = $this->todoZen->prepareCreateData($todoData, $uid);
            if(!$todo) return print(js::error(dao::getError()));

            $todoID = $this->todo->create($todo);
            if($todoID === false) return print(js::error(dao::getError()));

            $todo->id = $todoID;
            $this->todoZen->afterCreate($todo, $formData);

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
            return print(js::locate($this->createLink('my', 'todo', 'type=all&userID=&status=all&orderBy=id_desc'), 'parent'));
        }

        unset($this->lang->todo->typeList['cycle']);

        $this->todoZen->buildCreateView($date);
    }

    /**
     * 批量创建待办。
     * Batch create todo.
     *
     * @param  string $date
     * @access public
     * @return void
     */
    public function batchCreate(string $date = 'today')
    {
        if($date == 'today') $date = helper::today();

        if(!empty($_POST))
        {
            $formData   = form::data($this->config->todo->batchCreate->form);
            $todosData  = $this->todoZen->beforeBatchCreate($formData);
            $todoIDList = $this->todo->batchCreate($todosData);
            if(dao::isError()) return print(js::error(dao::getError()));

            /* Locate the browser. */
            $date = str_replace('-', '', $this->post->date);
            if($date == '') $date = 'future';
            if($date == date('Ymd')) $date= 'today';

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $todoIDList));
            if(isonlybody()) return print(js::reload('parent.parent'));
            return print(js::locate($this->createLink('my', 'todo', "type={$date}"), 'parent'));
        }

        unset($this->lang->todo->typeList['cycle']);

        $this->todoZen->buildBatchCreateView($date);
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
    }

    /**
     * 批量编辑待办。
     * Batch edit todo.
     *
     * @param  string $from example:myTodo, todoBatchEdit.
     * @param  string $type
     * @param  string $userID
     * @param  string $status
     * @access public
     */
    public function batchEdit(string $from = '', string $type = 'today', string $userID = '', string $status = 'all')
    {
        $formData = form::data($this->config->todo->batchEdit->form);
        $userID   = (int)$userID;

        /* Get form data for my-todo. */
        if($from == 'myTodo') $this->todoZen->batchEditFromMyTodo($formData, $type, $userID, $status);
        if($from == 'todoBatchEdit')
        {
            $todos = $this->todoZen->beforeBatchEdit($formData);
            $allChanges = $this->todo->batchUpdate($todos, $formData->data->todoIDList);
            $this->todoZen->afterBatchEdit($allChanges);

            return print(js::locate($this->session->todoList, 'parent'));
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
    public function start(string $todoID)
    {
        $todoID = (int)$todoID;
        $todo   = $this->todo->getByID($todoID);

        if($todo->status == 'wait')
        {
            $this->todo->start($todoID);
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
    public function activate(string $todoID)
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
     * @return void
     */
    public function close(string $todoID)
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
     * @return void
     */
    public function assignTo(string $todoID)
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

        $this->todoZen->buildAssignToView($todoID);
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

        if(!isonlybody()) $this->todoZen->setSessionUri($this->app->getURI(true));

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
        if($confirm == 'no')  return print(js::confirm($this->lang->todo->confirmDelete, $this->createLink('todo', 'delete', "todoID={$todoID}&confirm=yes")));
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
        $todoID = (int)$todoID;
        $todo   = $this->todo->getByID($todoID);
        if($todo->status != 'done' && $todo->status != 'closed')
        {
            $result = $this->todo->finish($todoID);
            if(!$result) return false;
        }

        $types = $this->config->todo->moduleList;
        array_pop($types);
        if(in_array($todo->type, $types))
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
            return $this->send(array('status' => 'success'));
        }
        if(isonlybody()) return print(js::reload('parent.parent'));
        return print(js::reload('parent'));
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
        $todoIDList = form::data($this->config->todo->batchFinish->form)->get('todoIDList');
        $todoList   = $this->todo->getByList($todoIDList);
        foreach($todoList as $todoID => $todo)
        {
            if($todo->status == 'done' || $todo->status == 'closed') unset($todoList[$todoID]);
        }

        $isBatchFinished = $this->todo->batchFinish(array_keys($todoList));
        if(!$isBatchFinished) return false;

        return print(js::reload('parent'));
    }

    /**
     * 批量关闭待办。只有完成的待办才能关闭。
     * Batch close todos. The status of todo which need to close should be done.
     *
     * @access public
     * @return int
     */
    public function batchClose()
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
        if(!$_POST) $this->locate($this->createLink('my', 'todo'));

        $formData   = form::data($this->config->todo->editDate->form);
        $todoIDList = !empty($formData->rawdata->todoIDList) ? $formData->rawdata->todoIDList : array($todoID);
        $date       = !empty($formData->rawdata->date) ? $formData->rawdata->date : date::today();
        if(!$todoIDList) $this->locate((string)$this->session->todoList);

        $this->todo->editDate((array)$todoIDList, (string)$date);
        $this->locate((string)$this->session->todoList);
    }

    /**
     * 获取导出待办数据。
     * Get data to export.
     *
     * @param  string $userID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function export(string $userID, string $orderBy)
    {
        if($_POST)
        {
            $user       = $this->todoZen->getUserById((int)$userID);
            $todoLang   = (object)$this->lang->todo;
            $configTime = $this->config->todo->times;

            $formData    = form::data($this->config->todo->export->form);
            $checkedItem = $formData->rawdata->exportType == 'selected' ? $this->cookie->checkedItem : '';

            $todos = $this->todo->getByExportList($orderBy, (string) $this->session->todoReportCondition, (string)$checkedItem);

            list($todos, $fields) = $this->todoZen->exportTodoInfo((array)$todos, (string)$this->config->todo->list->exportFields, $todoLang);
            list($users, $bugs, $stories, $tasks, $testTasks) = $this->todoZen->exportAssociated('default', (string)$user->account);

            $times = date::buildTimeList((int)$configTime->begin, (int)$configTime->end, (int)$configTime->delta);

            $assemble = new stdClass();
            $assemble->users     = $users;
            $assemble->bugs      = $bugs;
            $assemble->stories   = $stories;
            $assemble->tasks     = $tasks;
            $assemble->testTasks = $testTasks;
            if($this->config->edition == 'max')
            {
                $iroData = $this->todoZen->exportInfo((string)$this->config->edition, (string)$user->account);
                $assemble->issues        = $iroData[0];
                $assemble->risks         = $iroData[1];
                $assemble->opportunities = $iroData[2];
            }
            if(isset($this->config->qcVersion)) $assemble->reviews = $this->todoZen->exportInfo('qcVersion', (string)$user->account);

            $todos = $this->todoZen->assembleExportData((array)$todos, $assemble, $todoLang, (array)$times);

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
        $projectID = (int)$projectID;
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
     * 创建周期待办。
     * Create cycle.
     *
     * @access public
     * @return void
     */
    public function createCycle()
    {
        $todoList = $this->todo->getValidCycleList();
        $this->todo->createByCycle($todoList);
    }
}

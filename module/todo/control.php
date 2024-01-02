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
     * 构造函数，日期的加载类，配置语言项。
     * Construct function, load class of date, lang of my.
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
     * @param  string $date
     * @param  string $from todo|feedback|block
     * @access public
     * @return void
     */
    public function create(string $date = 'today', string $from = 'todo')
    {
        $this->app->loadClass('date');
        if($date == 'today') $date = date::today();

        if(!empty($_POST))
        {
            if($this->post->type != 'custom')
            {
                $this->config->todo->create->requiredFields = '';
                $this->config->todo->create->form['name']['required'] = false;
            }
            $form     = form::data($this->config->todo->create->form);
            $form     = $this->todoZen->addCycleYearConfig($form);
            $todoData = $this->todoZen->beforeCreate($form);

            $todo = $this->todoZen->prepareCreateData($todoData);
            if(!$todo) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $todoID = $this->todo->create($todo);
            if($todoID === false) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $todo->id = $todoID;
            $this->todoZen->afterCreate($todo, $form);

            if(!empty($todoData->objectID)) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true));

            if($from == 'block')
            {
                $todo = $this->todo->getByID($todoID);
                $todo->begin = date::formatTime($todo->begin);
                return $this->send(array('result' => 'success', 'id' => $todoID, 'name' => $todo->name, 'pri' => $todo->pri, 'priName' => $this->lang->todo->priList[$todo->pri], 'time' => date(DT_DATE4, strtotime($todo->date)) . ' ' . $todo->begin));
            }

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $todoID));
            if($this->viewType == 'xhtml') return print(js::locate($this->createLink('todo', 'view', "todoID=$todoID", 'html'), 'parent'));
            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->createLink('my', 'todo', 'type=all&userID=&status=all&orderBy=id_desc')));
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
            $form       = form::batchData($this->config->todo->batchCreate->form);
            $todosData  = $this->todoZen->beforeBatchCreate($form);
            $todoIdList = $this->todo->batchCreate($todosData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Locate the browser. */
            $date = str_replace('-', '', $this->post->date);
            if($this->post->futureDate) $date = 'future';
            if($date == date('Ymd'))    $date = 'today';

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $todoIdList));
            if(helper::isAjaxRequest('modal')) return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
            return $this->sendSuccess(array('closeModal' => true, 'load' => $this->createLink('my', 'todo', "type={$date}")));
        }

        unset($this->lang->todo->typeList['cycle']);

        $this->todoZen->buildBatchCreateView($date);
    }

    /**
     * 编辑待办数据。
     * Edit a todo.
     *
     * @param  int    $todoID
     * @access public
     * @return void
     */
    public function edit(int $todoID)
    {
        if(!empty($_POST))
        {
            $form = form::data($this->config->todo->edit->form);
            $form = $this->todoZen->addCycleYearConfig($form);

            /* Processing edit request data. */
            $todo = $this->todoZen->beforeEdit($todoID, $form);
            if(dao::isError()) return $this->send(array('status' => 'fail', 'message' => dao::getError()));

            /* update a todo. */
            $changes = $this->todo->update($todoID, $todo);
            if(dao::isError()) return $this->send(array('status' => 'fail', 'message' => dao::getError()));

            /* Handle data after edit todo. */
            $this->todoZen->afterEdit($todoID, $changes);

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->session->todoList ? $this->session->todoList : $this->createLink('my', 'todo')));
        }

        /* Judge a private todo or not, If private, die. */
        $todo = $this->todo->getByID($todoID);
        if($todo->private and $this->app->user->account != $todo->account) return print('private');

        unset($this->lang->todo->typeList['cycle']);

        /* Build create form data. */
        $this->todoZen->buildEditView($todo);
    }

    /**
     * 批量编辑待办。
     * Batch edit todo.
     *
     * @param  string $from example:myTodo, todoBatchEdit.
     * @param  string $type
     * @param  int    $userID
     * @param  string $status
     * @access public
     * @return void
     */
    public function batchEdit(string $from = '', string $type = 'today', int $userID = 0, string $status = 'all')
    {
        /* Get form data for my-todo. */
        if($from == 'myTodo')
        {
            if(!$this->post->todoIdList) return $this->locate($this->session->todoList ? $this->session->todoList : createLink('my', 'todo'));

            $this->todoZen->batchEditFromMyTodo($this->post->todoIdList, $type, $userID, $status);
        }

        /* Save the todo data for batch edit. */
        if($from == 'todoBatchEdit')
        {
            $formData   = form::batchData($this->config->todo->batchEdit->form)->get();
            $todos      = $this->todoZen->beforeBatchEdit($formData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $allChanges = $this->todo->batchUpdate($todos, array_keys($formData));
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->todoZen->afterBatchEdit($allChanges);

            return $this->sendSuccess(array('locate' => $this->session->todoList));
        }
    }

    /**
     * 开始一个待办事项。
     * Start a todo.
     *
     * @param  int    $todoID
     * @access public
     * @return void
     */
    public function start(int $todoID)
    {
        $todo = $this->todo->getByID($todoID);
        if($todo->status == 'wait')
        {
            $this->todo->start($todoID);
            if(dao::isError()) return print(js::error(dao::getError()));
        }

        if(in_array($todo->type, array('bug', 'task', 'story'))) return $this->todoZen->printStartConfirm($todo);
        if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true));
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->session->todoList ? $this->session->todoList : $this->createLink('my', 'todo')));
    }

    /**
     * 激活待办事项。
     * Activate a todo.
     *
     * @param  int    $todoID
     * @access public
     * @return void
     */
    public function activate(int $todoID)
    {
        $todo = $this->todo->getByID($todoID);

        if($todo->status == 'done' || $todo->status == 'closed')
        {
            $this->todo->activate($todoID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }
        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
        if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true));
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->session->todoList ? $this->session->todoList : $this->createLink('my', 'todo')));
    }

    /**
     * 关闭待办。
     * Close one todo.
     *
     * @param  int    $todoID
     * @access public
     * @return void
     */
    public function close(int $todoID)
    {
        /* Close the todo with status done. */
        $todo = $this->todo->getByID($todoID);
        if($todo->status == 'done')
        {
            $isClosed = $this->todo->close($todoID);
            if(!$isClosed) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        /* Return json if run mode is API. */
        if(defined('RUN_MODE') && RUN_MODE == 'api')
        {
            $this->send(array('status' => 'success'));
        }

        if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true));
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->session->todoList ? $this->session->todoList : $this->createLink('my', 'todo')));
    }

    /**
     * 指派待办。
     * Assign todo.
     *
     * @param  int    $todoID
     * @access public
     * @return void
     */
    public function assignTo(int $todoID)
    {
        if(!empty($_POST))
        {
            $formData = form::data($this->config->todo->assignTo->form);
            $todo     = $this->todoZen->beforeAssignTo($formData);

            $todo->id   = $todoID;
            $isAssigned = $this->todoZen->doAssignTo($todo);
            if(!$isAssigned) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->session->todoList ? $this->session->todoList : $this->createLink('my', 'todo')));
        }

        $this->todoZen->buildAssignToView($todoID);
    }

    /**
     * 获取待办的信息。
     * Get info of todo.
     *
     * @param  int    $todoID
     * @param  string $from  my|company
     * @access public
     * @return void
     */
    public function view(int $todoID, string $from = 'company')
    {
        $todo = $this->todo->getByID($todoID, true);
        if(!$todo)
        {
            if((defined('RUN_MODE') && RUN_MODE == 'api') or $this->app->viewType == 'json') return $this->send(array('status' => 'fail', 'message' => '404 Not found'));
            return print(js::error((string)$this->lang->notFound) . (string)js::locate('back'));
        }

        $account = $this->app->user->account;
        if($todo->private and $todo->account != $account) return print(js::error((string)$this->lang->todo->thisIsPrivate) . (string)js::locate('back'));

        if(!isInModal()) $this->todoZen->setSessionUri($this->app->getURI(true));

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
     * @param  int    $todoID
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function delete(int $todoID, string $confirm = 'no')
    {
        if($confirm == 'no') return $this->send(array('result' => 'success', 'load' => array('confirm' => $this->lang->todo->confirmDelete, 'confirmed' => $this->createLink('todo', 'delete', "todoID={$todoID}&confirm=yes"), 'canceled' => $this->session->todoList ? $this->session->todoList : $this->createLink('my', 'todo'))));

        $todo        = $this->todo->getByID($todoID);
        $allowDelete = true;
        if(!$this->app->user->admin && $todo && $todo->account != $this->app->user->account && $todo->assignedTo != $this->app->user->account) $allowDelete = false;
        if(!$allowDelete)
        {
            if(isonlybody()) return print(js::alert($this->lang->error->accessDenied));
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => $this->lang->error->accessDenied));
            if(helper::isAjaxRequest()) return $this->send(array('result' => 'fail', 'message' => $this->lang->error->accessDenied));
        }

        $this->todo->delete(TABLE_TODO, $todoID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->session->todoList ? $this->session->todoList : $this->createLink('my', 'todo')));
    }

    /**
     * 完成待办。
     * Finish a todo.
     *
     * @param  int    $todoID
     * @access public
     * @return void
     */
    public function finish(int $todoID)
    {
        $todo = $this->todo->getByID($todoID);
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
            $confirmURL  = $this->createLink($todo->type, 'view', "id=$todo->objectID");

            if($todo->type == 'bug')   $app = 'qa';
            if($todo->type == 'task')  $app = 'execution';
            if($todo->type == 'story') $app = 'product';

            $cancelURL = $this->session->todoList ? $this->session->todoList : $this->createLink('my', 'todo');
            return $this->send(array('result' => 'success', 'load' => array('confirm' => sprintf($this->lang->todo->{$confirmNote}, $todo->objectID), 'confirmed' => array('url' => $confirmURL, 'app' => $app), 'canceled' => array('url' => $cancelURL)), 'closeModal' => true));
        }

        if(defined('RUN_MODE') && RUN_MODE == 'api')
        {
            return $this->send(array('status' => 'success'));
        }
        if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true));
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->session->todoList ? $this->session->todoList : $this->createLink('my', 'todo')));
    }

    /**
     * 批量完成待办。
     * Batch finish todos.
     *
     * @access public
     * @return void
     */
    public function batchFinish()
    {
        $todoIdList = form::data($this->config->todo->batchFinish->form)->get('todoIdList');
        $todoList   = $this->todo->getByList($todoIdList);
        foreach($todoList as $todoID => $todo)
        {
            if($todo->status == 'done' || $todo->status == 'closed') unset($todoList[$todoID]);
        }

        $isBatchFinished = $this->todo->batchFinish(array_keys($todoList));
        if(!$isBatchFinished) return false;

        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * 批量关闭待办。只有完成的待办才能关闭。
     * Batch close todos. The status of todo which need to close should be done.
     *
     * @access public
     * @return void
     */
    public function batchClose()
    {
        $waitIdList = array();
        $todoIdList = form::data($this->config->todo->batchClose->form)->get('todoIdList');
        foreach($todoIdList as $todoID)
        {
            $todoID = (int)$todoID;
            $todo = $this->todo->getByID($todoID);
            if($todo->status == 'done') $this->todo->close($todoID);
            if($todo->status != 'done' and $todo->status != 'closed') $waitIdList[] = $todoID;
        }
        if(!empty($waitIdList)) echo js::alert(sprintf($this->lang->todo->unfinishedTodo, implode(',', $waitIdList)));

        return $this->send(array('result' => 'success', 'load' => true));
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
        $todoIdList = !empty($formData->data->todoIdList) ? $formData->data->todoIdList : array($todoID);
        $date       = !empty($formData->data->date) ? $formData->data->date : date::today();
        if(!$todoIdList) $this->locate((string)$this->session->todoList);

        $this->todo->editDate((array)$todoIdList, (string)$date);
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * 获取导出待办数据。
     * Get data to export.
     *
     * @param  int    $userID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function export(int $userID, string $orderBy)
    {
        if($_POST)
        {
            $user       = $this->todoZen->getUserById($userID);
            $todoLang   = (object)$this->lang->todo;
            $configTime = $this->config->todo->times;

            $formData    = form::data($this->config->todo->export->form);
            $checkedItem = $formData->data->exportType == 'selected' ? $this->cookie->checkedItem : '';

            $todos = $this->todo->getByExportList($orderBy, (string) $this->session->todoReportCondition, (string)$checkedItem);

            list($todos, $fields) = $this->todoZen->exportTodoInfo((array)$todos, (string)$this->config->todo->list->exportFields, $todoLang);
            list($users, $bugs, $stories, $tasks, $testTasks) = $this->todoZen->exportAssociated('default', (string)$user->account);

            $times = date::buildTimeList((int)$configTime->begin, (int)$configTime->end, (int)$configTime->delta);

            $assemble = new stdclass();
            $assemble->users     = $users;
            $assemble->bugs      = $bugs;
            $assemble->stories   = $stories;
            $assemble->tasks     = $tasks;
            $assemble->testTasks = $testTasks;
            if(in_array($this->config->edition, array('max', 'ipd')))
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
     * ajax请求：获得 todo 的动作。 用于 web 应用程序。
     * AJAX: get actions of a todo. for web app.
     *
     * @param  int    $todoID
     * @access public
     * @return void
     */
    public function ajaxGetDetail(int $todoID)
    {
        $this->view->actions = $this->loadModel('action')->getList('todo', $todoID);
        $this->display();
    }

    /**
     * ajax请求：获取程序id。
     * AJAX: get program id.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @access public
     * @return void
     */
    public function ajaxGetProgramID(int $objectID, string $objectType)
    {
        $table = $objectType == 'project' ? TABLE_PROJECT : TABLE_PRODUCT;
        $field = $objectType == 'project' ? 'parent' : 'program';
        echo $this->dao->select($field)->from($table)->where('id')->eq($objectID)->fetch($field);
    }

    /**
     * ajax请求：获取执行对。
     * AJAX: get execution pairs.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function ajaxGetExecutionPairs(int $projectID)
    {
        $this->session->set('project', $projectID);

        $project    = $this->loadModel('project')->getByID($projectID);
        $executions = $this->loadModel('execution')->getByProject($projectID, 'undone');

        $items = array();
        foreach($executions as $id => $execution) $items[] = array('value' => $id, 'text' => $execution->name, 'keys' => $execution->name);
        return print(json_encode($items));
    }

    /**
     * ajax请求：获取产品对。
     * AJAX: get product pairs.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function ajaxGetProductPairs(int $projectID)
    {
        $this->session->set('project', $projectID);

        $products = $this->loadModel('product')->getProductPairsByProject($projectID);
        $items    = array();
        foreach($products as $id => $name) $items[] = array('value' => $id, 'text' => $name, 'keys' => $name);
        return print(json_encode($items));
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

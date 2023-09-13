<?php
/**
 * The control file of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: control.php 4976 2013-07-02 08:15:31Z wyd621@gmail.com $
 * @link        http://www.zentao.net
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
     * Create a todo.
     *
     * @param  string|date $date
     * @param  int         $userID
     * @param  string      $from todo|feedback
     * @access public
     * @return void
     */
    public function create($date = 'today', $userID = '', $from = 'todo')
    {
        if($date == 'today') $date   = date::today();
        if($userID == '')    $userID = $this->app->user->id;

        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;

        if(!empty($_POST))
        {
            $todoID = $this->todo->create($date, $account);
            if(dao::isError()) return print(js::error(dao::getError()));
            $this->loadModel('action')->create('todo', $todoID, 'opened');

            $date = str_replace('-', '', $this->post->date);
            if($date == '')
            {
                $date = 'future';
            }
            elseif($date == date('Ymd'))
            {
                $date = 'today';
            }

            if(!empty($_POST['idvalue'])) return $this->send(array('result' => 'success'));
            if($from == 'block')
            {
                $todo = $this->todo->getById($todoID);
                $this->app->loadClass('date');
                $todo->begin = date::formatTime($todo->begin);
                return $this->send(array('result' => 'success', 'id' => $todoID, 'name' => $todo->name, 'pri' => $todo->pri, 'priName' => $this->lang->todo->priList[$todo->pri], 'time' => date(DT_DATE4, strtotime($todo->date)) . ' ' . $todo->begin));
            }

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $todoID));
            if($this->viewType == 'xhtml') return print(js::locate($this->createLink('todo', 'view', "todoID=$todoID", 'html'), 'parent'));
            if(isonlybody()) return print(js::closeModal('parent.parent'));
            return print(js::locate($this->createLink('my', 'todo', "type=all&userID=&status=all&orderBy=id_desc"), 'parent'));
        }

        unset($this->lang->todo->typeList['cycle']);

        $this->view->title      = $this->lang->todo->common . $this->lang->colon . $this->lang->todo->create;
        $this->view->position[] = $this->lang->todo->common;
        $this->view->position[] = $this->lang->todo->create;
        $this->view->date       = date("Y-m-d", strtotime($date));
        $this->view->times      = date::buildTimeList($this->config->todo->times->begin, $this->config->todo->times->end, $this->config->todo->times->delta);
        $this->view->time       = date::now();
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|nodeleted|noempty');

        $this->display();
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
     * Edit a todo.
     *
     * @param  int    $todoID
     * @access public
     * @return void
     */
    public function edit($todoID)
    {
        if(!empty($_POST))
        {
            $changes = $this->todo->update($todoID);
            if(dao::isError())
            {
                if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => dao::getError()));
                return print(js::error(dao::getError()));
            }
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('todo', $todoID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
            return print(js::locate($this->session->todoList, 'parent.parent'));
        }

        /* Judge a private todo or not, If private, die. */
        $todo = $this->todo->getById($todoID);
        if($todo->private and $this->app->user->account != $todo->account) return print('private');

        unset($this->lang->todo->typeList['cycle']);

        $todo->date = date("Y-m-d", strtotime($todo->date));
        $this->view->title      = $this->lang->todo->common . $this->lang->colon . $this->lang->todo->edit;
        $this->view->position[] = $this->lang->todo->common;
        $this->view->position[] = $this->lang->todo->edit;
        $this->view->times      = date::buildTimeList($this->config->todo->times->begin, $this->config->todo->times->end, $this->config->todo->times->delta);
        $this->view->todo       = $todo;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|nodeleted|noempty');

        $this->display();
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
    public function batchEdit($from = '', $type = 'today', $userID = '', $status = 'all')
    {
        /* Get form data for my-todo. */
        if($from == 'myTodo')
        {
            /* Initialize vars. */
            $editedTodos = array();
            $todoIDList  = array();
            $columns     = 7;

            if($userID == '') $userID = $this->app->user->id;
            $user    = $this->loadModel('user')->getById($userID, 'id');
            $account = $user->account;

            $reviews = array();
            if($this->config->edition == 'max' or $this->config->edition == 'ipd') $reviews = $this->loadModel('review')->getUserReviewPairs($account);
            $allTodos = $this->todo->getList($type, $account, $status);
            if($this->post->todoIDList) $todoIDList = $this->post->todoIDList;

            /* Initialize todos whose need to edited. */
            foreach($allTodos as $todo)
            {
                if(in_array($todo->id, $todoIDList))
                {
                    $editedTodos[$todo->id] = $todo;
                    if($todo->type != 'custom')
                    {
                        if(!isset($objectIDList[$todo->type])) $objectIDList[$todo->type] = array();
                        $objectIDList[$todo->type][$todo->idvalue] = $todo->idvalue;
                    }
                }
            }

            $bugs   = $this->bug->getUserBugPairs($account, true, 0, '', '', isset($objectIDList['bug']) ? $objectIDList['bug'] : '');
            $tasks  = $this->task->getUserTaskPairs($account, 'wait,doing', '', isset($objectIDList['task']) ? $objectIDList['task'] : '');
            $storys = $this->loadModel('story')->getUserStoryPairs($account, 10, 'story', '', isset($objectIDList['story']) ? $objectIDList['story'] : '');
            if($this->config->edition != 'open') $this->view->feedbacks = $this->loadModel('feedback')->getUserFeedbackPairs($account, '', isset($objectIDList['feedback']) ? $objectIDList['feedback'] : '');
            if($this->config->edition == 'max' or $this->config->edition == 'ipd')
            {
                $issues        = $this->loadModel('issue')->getUserIssuePairs($account);
                $risks         = $this->loadmodel('risk')->getUserRiskPairs($account);
                $opportunities = $this->loadmodel('opportunity')->getUserOpportunityPairs($account);
            }
            $testtasks = $this->loadModel('testtask')->getUserTestTaskPairs($account);

            /* Judge whether the edited todos is too large. */
            $countInputVars  = count($editedTodos) * $columns;
            $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);

            unset($this->lang->todo->typeList['cycle']);
            /* Set Custom*/
            foreach(explode(',', $this->config->todo->list->customBatchEditFields) as $field) $customFields[$field] = $this->lang->todo->$field;
            $this->view->customFields = $customFields;
            $this->view->showFields   = $this->config->todo->custom->batchEditFields;

            /* Assign. */
            $title      = $this->lang->todo->common . $this->lang->colon . $this->lang->todo->batchEdit;
            $position[] = html::a($this->createLink('my', 'todo'), $this->lang->my->todo);
            $position[] = $this->lang->todo->common;
            $position[] = $this->lang->todo->batchEdit;

            if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);
            $this->view->bugs        = $bugs;
            $this->view->tasks       = $tasks;
            $this->view->storys      = $storys;
            if($this->config->edition == 'max' or $this->config->edition == 'ipd')
            {
                $this->view->issues        = $issues;
                $this->view->risks         = $risks;
                $this->view->opportunities = $opportunities;
            }
            $this->view->reviews     = $reviews;
            $this->view->testtasks   = $testtasks;
            $this->view->editedTodos = $editedTodos;
            $this->view->times       = date::buildTimeList($this->config->todo->times->begin, $this->config->todo->times->end, $this->config->todo->times->delta);
            $this->view->time        = date::now();
            $this->view->title       = $title;
            $this->view->position    = $position;
            $this->view->users       = $this->loadModel('user')->getPairs('noclosed|nodeleted|noempty');

            $this->display();
        }
        /* Get form data from todo-batchEdit. */
        elseif($from == 'todoBatchEdit')
        {
            $allChanges = $this->todo->batchUpdate();
            foreach($allChanges as $todoID => $changes)
            {
                if(empty($changes)) continue;

                $actionID = $this->loadModel('action')->create('todo', $todoID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            return print(js::locate($this->session->todoList, 'parent'));
        }
    }

    /**
     * Start a todo.
     *
     * @param  int    $todoID
     * @access public
     * @return void
     */
    public function start($todoID)
    {
        $todo = $this->todo->getById($todoID);
        if($todo->status == 'wait') $this->todo->start($todoID);
        if(in_array($todo->type, array('bug', 'task', 'story')))
        {
            $confirmNote = 'confirm' . ucfirst($todo->type);
            $confirmURL  = $this->createLink($todo->type, 'view', "id=$todo->idvalue");
            $okTarget    = isonlybody() ? 'parent' : 'window.parent.$.apps.open';
            if($todo->type == 'bug')   $app = 'qa';
            if($todo->type == 'task')  $app = 'execution';
            if($todo->type == 'story') $app = 'product';
            $cancelURL   = $this->server->HTTP_REFERER;
            return print(js::confirm(sprintf($this->lang->todo->$confirmNote, $todo->idvalue), $confirmURL, $cancelURL, $okTarget, 'parent', $app));
        }

        if(isonlybody())return print(js::reload('parent.parent'));
        echo js::reload('parent');
    }

    /**
     * Activated todo.
     *
     * @param  $todoID
     * @access public
     * @return void
     */
    public function activate($todoID)
    {
        $todo = $this->todo->getById($todoID);
        if($todo->status == 'done' or $todo->status == 'closed') $this->todo->activate($todoID);
        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
        if(isonlybody()) return print(js::reload('parent.parent'));
        echo js::reload('parent');
    }

    /**
     * Closed todo.
     *
     * @param  $todoID
     *
     * @access public
     * @return void
     */
    public function close($todoID)
    {
        $todo = $this->todo->getById($todoID);
        if($todo->status == 'done') $this->todo->close($todoID);
        if(isonlybody()) return print(js::reload('parent.parent'));
        echo js::reload('parent');
    }

    /**
     * Assign.
     *
     * @param $todoID
     *
     * @access public
     * @return void
     */
    public function assignTo($todoID)
    {
        if(!empty($_POST))
        {
            if(empty($_POST['assignedTo'])) return print(js::error($this->lang->todo->noAssignedTo));
            $this->todo->assignTo($todoID);
            if(dao::isError()) return print(js::error(dao::getError()));
            return print(js::reload('parent.parent'));
        }

        $this->view->todo    = $this->todo->getById($todoID);
        $this->view->members = $this->loadModel('user')->getPairs('noclosed|noempty|nodeleted');
        $this->view->times   = date::buildTimeList($this->config->todo->times->begin, $this->config->todo->times->end, $this->config->todo->times->delta);
        $this->view->actions = $this->loadModel('action')->getList('todo', $todoID);
        $this->view->users   = $this->user->getPairs('noletter');
        $this->view->time    = date::now();
        $this->display();
    }

    /**
     * View a todo.
     *
     * @param int    $todoID
     * @param string $from     my|company
     *
     * @access public
     * @return void
     */
    public function view($todoID, $from = 'company')
    {
        $todo = $this->todo->getById($todoID, true);
        if(!$todo)
        {
            if((defined('RUN_MODE') && RUN_MODE == 'api') or $this->app->viewType == 'json') return $this->send(array('status' => 'fail', 'message' => '404 Not found'));
            return print(js::error($this->lang->notFound) . js::locate('back'));
        }

        if($todo->private and $todo->account != $this->app->user->account)
        {
            return print(js::error($this->lang->todo->thisIsPrivate) . js::locate('back'));
        }

        /* Save the session. */
        if(!isonlybody())
        {
            $uri = $this->app->getURI(true);
            $this->session->set('bugList',      $uri, 'qa');
            $this->session->set('taskList',     $uri, 'execution');
            $this->session->set('storyList',    $uri, 'product');
            $this->session->set('testtaskList', $uri, 'qa');
        }

        /* Fix bug #936. */
        $account = $this->app->user->account;
        if($account != $todo->account and $account != $todo->assignedTo and !common::hasPriv('my', 'team'))
        {
            $this->locate($this->createLink('user', 'deny', "module=my&method=team"));
        }

        $this->loadModel('user');

        $model    = $todo->type == 'opportunity' ? 'waterfall' : 'all';
        $projects = $this->loadModel('project')->getPairsByModel($model);
        if(!isset($this->session->project)) $this->session->set('project', key($projects));

        $this->view->title           = $this->app->user->account == $todo->account ? "{$this->lang->todo->common} #$todo->id $todo->name" : $this->lang->todo->common ;
        $this->view->position[]      = $this->lang->todo->view;
        $this->view->todo            = $todo;
        $this->view->times           = date::buildTimeList($this->config->todo->times->begin, $this->config->todo->times->end, 5);
        $this->view->users           = $this->user->getPairs('noletter');
        $this->view->user            = $this->user->getById($todo->account);
        $this->view->actions         = $this->loadModel('action')->getList('todo', $todoID);
        $this->view->from            = $from;
        $this->view->projects        = $projects;
        $this->view->executions      = $this->loadModel('execution')->getPairs();
        $this->view->products        = $todo->type == 'opportunity' ? $this->loadModel('product')->getPairsByProjectModel('waterfall') : $this->loadModel('product')->getPairs();
        $this->view->projectProducts = $this->loadModel('product')->getProductPairsByProject($this->session->project);

        $this->display();
    }

    /**
     * Delete a todo.
     *
     * @param  int    $todoID
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function delete($todoID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->todo->confirmDelete, $this->createLink('todo', 'delete', "todoID=$todoID&confirm=yes")));
        }
        else
        {
            $result = $this->todo->delete(TABLE_TODO, $todoID);
            if(!$result)
            {
                if(isonlybody()) return print(js::alert($this->lang->error->accessDenied));
                if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => $this->lang->error->accessDenied));
                if(helper::isAjaxRequest()) return $this->send(array('result' => 'fail', 'message' => $this->lang->error->accessDenied));
            }

            /* if ajax request, send result. */
            if($this->server->ajax)
            {
                if(dao::isError())
                {
                    $response['result']  = 'fail';
                    $response['message'] = dao::getError();
                }
                else
                {
                    $response['result']  = 'success';
                    $response['message'] = '';
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
     * Finish a todo.
     *
     * @param  int    $todoID
     * @access public
     * @return void
     */
    public function finish($todoID)
    {
        $todo = $this->todo->getById($todoID);
        if($todo->status != 'done' && $todo->status != 'closed') $this->todo->finish($todoID);
        if(in_array($todo->type, array('bug', 'task', 'story')))
        {
            $confirmNote = 'confirm' . ucfirst($todo->type);
            $okTarget    = isonlybody() ? 'parent' : 'window.parent.$.apps.open';
            $confirmURL  = $this->createLink($todo->type, 'view', "id=$todo->idvalue");
            if($todo->type == 'bug')   $app = 'qa';
            if($todo->type == 'task')  $app = 'execution';
            if($todo->type == 'story') $app = 'product';
            $cancelURL   = $this->server->HTTP_REFERER;
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'message' => sprintf($this->lang->todo->$confirmNote, $todo->idvalue), 'locate' => $confirmURL));
            return print(strpos($cancelURL, 'calendar') ? json_encode(array(sprintf($this->lang->todo->$confirmNote, $todo->idvalue), $confirmURL)) : js::confirm(sprintf($this->lang->todo->$confirmNote, $todo->idvalue), $confirmURL, $cancelURL, $okTarget, 'parent', $app));
        }
        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
        if(isonlybody()) return print(js::reload('parent.parent'));
        echo js::reload('parent');
    }

    /**
     * Batch finish todos.
     *
     * @access public
     * @return void
     */
    public function batchFinish()
    {
        if(!empty($_POST['todoIDList']))
        {
            foreach($_POST['todoIDList'] as $todoID)
            {
                $todo = $this->todo->getById($todoID);
                if($todo->status != 'done' && $todo->status != 'closed') $this->todo->finish($todoID);
            }
            return print(js::reload('parent'));
        }
    }

    /**
     * Batch close todos.
     *
     * @access public
     * @return void
     */
    public function batchClose()
    {
        $waitIdList = array();
        foreach($_POST['todoIDList'] as $todoID)
        {
            $todo = $this->todo->getById($todoID);
            if($todo->status == 'done') $this->todo->close($todoID);
            if($todo->status != 'done' and $todo->status != 'closed') $waitIdList[] = $todoID;
        }
        if(!empty($waitIdList)) echo js::alert(sprintf($this->lang->todo->unfinishedTodo, implode(',', $waitIdList)));

        echo js::reload('parent');
    }

    /**
     * Import selected todoes to today.
     *
     * @access public
     * @return void
     */
    public function import2Today($todoID = 0)
    {
        $todoIDList = $_POST ? $this->post->todoIDList : array($todoID);
        $date       = !empty($_POST['date']) ? $_POST['date'] : date::today();
        $this->dao->update(TABLE_TODO)->set('date')->eq($date)->where('id')->in($todoIDList)->exec();
        $this->locate($this->session->todoList);
    }

    /**
     * Get data to export
     *
     * @param  int    $userID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function export($userID, $orderBy)
    {
        if($_POST)
        {
            $user    = $this->loadModel('user')->getById($userID, 'id');
            $account = $user->account;

            $todoLang   = $this->lang->todo;
            $todoConfig = $this->config->todo;

            /* Create field lists. */
            $fields = explode(',', $todoConfig->list->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = isset($todoLang->$fieldName) ? $todoLang->$fieldName : $fieldName;
                unset($fields[$key]);
            }
            unset($fields['idvalue']);
            unset($fields['private']);

            /* Get bugs. */
            $todos = $this->dao->select('*')->from(TABLE_TODO)->where($this->session->todoReportCondition)
                ->beginIF($this->post->exportType == 'selected')->andWhere('id')->in($this->post->checkedItem)->fi()
                ->orderBy($orderBy)->fetchAll('id');

            /* Get users, bugs, tasks and times. */
            $users     = $this->loadModel('user')->getPairs('noletter');
            $bugs      = $this->loadModel('bug')->getUserBugPairs($account);
            $stories   = $this->loadModel('story')->getUserStoryPairs($account, 100, 'story');
            $tasks     = $this->loadModel('task')->getUserTaskPairs($account);
            if($this->config->edition == 'max' or $this->config->edition == 'ipd')
            {
                $issues        = $this->loadModel('issue')->getUserIssuePairs($account);
                $risks         = $this->loadModel('risk')->getUserRiskPairs($account);
                $opportunities = $this->loadModel('opportunity')->getUserOpportunityPairs($account);
            }
            $testTasks = $this->loadModel('testtask')->getUserTesttaskPairs($account);
            if(isset($this->config->qcVersion)) $reviews = $this->loadModel('review')->getUserReviewPairs($account, 0, 'wait');
            $times = date::buildTimeList($this->config->todo->times->begin, $this->config->todo->times->end, $this->config->todo->times->delta);

            foreach($todos as $todo)
            {
                /* fill some field with useful value. */
                $todo->begin = $todo->begin == '2400' ? '' : (isset($times[$todo->begin]) ? $times[$todo->begin] : $todo->begin);
                $todo->end   = $todo->end   == '2400' ? '' : (isset($times[$todo->end])   ? $times[$todo->end] : $todo->end);

                $todo->assignedTo = zget($users, $todo->assignedTo);

                $type = $todo->type;
                if(isset($users[$todo->account])) $todo->account = $users[$todo->account];
                if($type == 'bug')                $todo->name    = isset($bugs[$todo->idvalue])    ? $bugs[$todo->idvalue] . "(#$todo->idvalue)" : '';
                if($type == 'story')              $todo->name    = isset($stories[$todo->idvalue]) ? $stories[$todo->idvalue] . "(#$todo->idvalue)" : '';
                if($type == 'task')               $todo->name    = isset($tasks[$todo->idvalue])   ? $tasks[$todo->idvalue] . "(#$todo->idvalue)" : '';

                if($this->config->edition == 'max' or $this->config->edition == 'ipd')
                {
                    if($type == 'issue') $todo->name = isset($issues[$todo->idvalue]) ? $issues[$todo->idvalue] . "(#$todo->idvalue)" : '';
                    if($type == 'risk')  $todo->name = isset($risks[$todo->idvalue])  ? $risks[$todo->idvalue] . "(#$todo->idvalue)" : '';
                    if($type == 'opportunity')  $todo->name = isset($opportunities[$todo->idvalue])  ? $opportunities[$todo->idvalue] . "(#$todo->idvalue)" : '';
                }
                if($type == 'testtask')           $todo->name    = isset($testTasks[$todo->idvalue]) ? $testTasks[$todo->idvalue] . "(#$todo->idvalue)" : '';
                if($type == 'review' && isset($this->config->qcVersion)) $todo->name = isset($reviews[$todo->idvalue]) ? $reviews[$todo->idvalue] . "(#$todo->idvalue)" : '';

                if(isset($todoLang->typeList[$type]))           $todo->type    = $todoLang->typeList[$type];
                if(isset($todoLang->priList[$todo->pri]))       $todo->pri     = $todoLang->priList[$todo->pri];
                if(isset($todoLang->statusList[$todo->status])) $todo->status  = $todoLang->statusList[$todo->status];
                if($todo->private == 1)                         $todo->desc    = $this->lang->todo->thisIsPrivate;

                /* drop some field that is not needed. */
                unset($todo->idvalue);
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

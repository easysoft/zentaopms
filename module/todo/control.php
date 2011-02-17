<?php
/**
 * The control file of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id$
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
        $this->loadModel('task');
        $this->loadModel('bug');
        $this->loadModel('my')->setMenu();
    }

    /**
     * Create a todo.
     * 
     * @param  string|date $date 
     * @param  string      $account 
     * @access public
     * @return void
     */
    public function create($date = 'today', $account = '')
    {
        if($date == 'today') $date = $this->todo->today();
        if($account == '')   $account = $this->app->user->account;
        if(!empty($_POST))
        {
            $todoID = $this->todo->create($date, $account);
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action')->create('todo', $todoID, 'opened');
            die(js::locate($this->createLink('my', 'todo', "date=$_POST[date]"), 'parent'));
        }

        $header['title'] = $this->lang->my->common . $this->lang->colon . $this->lang->todo->create;
        $position[]      = $this->lang->todo->create;

        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->dates    = $this->todo->buildDateList(0, $this->config->todo->dates->end);
        $this->view->date     = $date;
        $this->view->times    = $this->todo->buildTimeList($this->config->todo->times->begin, $this->config->todo->times->end, $this->config->todo->times->delta);
        $this->view->time     = $this->todo->now();
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
            if(dao::isError()) die(js::error(dao::getError()));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('todo', $todoID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            die(js::locate(inlink('view', "todoID=$todoID"), 'parent'));
        }

        /* Judge a private todo or not, If private, die. */
        $todo = $this->todo->getById($todoID);
        if($todo->private and $this->app->user->account != $todo->account) die('private');

        $header['title'] = $this->lang->my->common . $this->lang->colon . $this->lang->todo->edit;
        $position[]      = $this->lang->todo->edit;

        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->dates    = $this->todo->buildDateList();
        $this->view->times    = $this->todo->buildTimeList($this->config->todo->times->begin, $this->config->todo->times->end, $this->config->todo->times->delta);
        $this->view->todo     = $todo;
        $this->display();
    }

    /**
     * View a todo. 
     * 
     * @param  int    $todoID 
     * @param  string $from     my|company
     * @access public
     * @return void
     */
    public function view($todoID, $from = 'company')
    {
        $todo = $this->todo->getById($todoID);
        if(!$todo) die(js::error($this->lang->notFound) . js::locate('back'));

        /* Save the session. */
        $this->session->set('taskList', $this->app->getURI(true));
        $this->session->set('bugList',  $this->app->getURI(true));

        /* Set menus. */
        $this->lang->todo->menu = $this->lang->user->menu;
        $this->loadModel('user')->setMenu($this->user->getPairs(), $todo->account);
        $this->lang->set('menugroup.todo', $from);

        $this->view->header->title = $this->lang->todo->view;
        $this->view->position[]    = $this->lang->todo->view;
        $this->view->todo          = $todo;
        $this->view->times         = $this->todo->buildTimeList($this->config->todo->times->begin, $this->config->todo->times->end, $this->config->todo->times->delta);
        $this->view->users         = $this->user->getPairs('noletter');
        $this->view->actions       = $this->loadModel('action')->getList('todo', $todoID);
        $this->view->from          = $from;

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
            echo js::confirm($this->lang->todo->confirmDelete, $this->createLink('todo', 'delete', "todoID=$todoID&confirm=yes"));
            exit;
        }
        else
        {
            $this->dao->delete()->from(TABLE_TODO)->where('id')->eq($todoID)->exec();
            $this->loadModel('action')->create('todo', $todoID, 'erased');
            die(js::locate($this->session->todoList, 'parent'));
        }
    }

    /**
     * Mark status of a todo.
     * 
     * @param  int    $todoID 
     * @param  string $status   wait|doing|done
     * @access public
     * @return void
     */
    public function mark($todoID, $status)
    {
        $this->todo->mark($todoID, $status);
        $todo = $this->todo->getById($todoID);
        if($todo->status == 'done')
        {
            if($todo->type == 'bug' or $todo->type == 'task')
            {
                $confirmNote = 'confirm' . ucfirst($todo->type);
                $confirmURL  = $this->createLink($todo->type, 'view', "id=$todo->idvalue");
                $cancelURL   = $this->server->HTTP_REFERER;
                die(js::confirm(sprintf($this->lang->todo->$confirmNote, $todo->idvalue), $confirmURL, $cancelURL, 'parent', 'parent'));
            }
        }
        die(js::reload('parent'));
    }

    /**
     * Import selected todoes to today.
     * 
     * @access public
     * @return void
     */
    public function import2Today()
    {
        $todos = $this->post->todos;
        $today = $this->todo->today();
        $this->dao->update(TABLE_TODO)->set('date')->eq($today)->where('id')->in($todos)->exec();
        die(js::reload('parent'));
    }
}

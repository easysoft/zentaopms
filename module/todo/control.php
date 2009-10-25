<?php
/**
 * The control file of todo module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *                                                                             
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     todo
 * @version     $Id: control.php 1456 2009-10-23 01:57:11Z wwccss $
 * @link        http://www.zentao.cn
 */
class todo extends control
{
    /* 构造函数。*/
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('task');
        $this->loadModel('bug');
        $this->app->loadLang('my');
    }

    /* 添加todo。*/
    public function create($date = 'today', $account = '')
    {
        if($date == 'today') $date = $this->todo->today();
        if($account == '')   $account = $this->app->user->account;
        if(!empty($_POST))
        {
            $this->todo->create($date, $account);
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('my', 'todo', "date=$_POST[date]"), 'parent'));
        }

        $header['title'] = $this->lang->my->common . $this->lang->colon . $this->lang->todo->create;
        $position[]      = $this->lang->todo->create;

        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('dates',    $this->todo->buildDateList(0, 3));
        $this->assign('date',     $date);
        $this->assign('times',    $this->todo->buildTimeList());
        $this->assign('time',     $this->todo->now());
        $this->display();
    }

    /* 编辑todo。*/
    public function edit($todoID)
    {
        if(!empty($_POST))
        {
            $this->todo->update($todoID);
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('my', 'todo', "date=$_POST[date]"), 'parent'));
        }

        $header['title'] = $this->lang->my->common . $this->lang->colon . $this->lang->todo->edit;
        $position[]      = $this->lang->todo->edit;

        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('dates',    $this->todo->buildDateList(0, 3));
        $this->assign('times',    $this->todo->buildTimeList());
        $this->assign('todo',     $this->todo->findById($todoID));
        $this->display();
    }

    /* 删除一个todo。*/
    public function delete($todoID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            echo js::confirm($this->lang->todo->confirmDelete, $this->createLink('todo', 'delete', "todoID=$todoID&confirm=yes"));
            exit;
        }
        else
        {
            $todo = $this->todo->findById($todoID);
            $this->todo->delete($todoID);
            echo js::locate($this->createLink('my', 'todo', "date={$todo->date}"), 'parent');
            exit;
        }
    }

    /* 切换todo的状态。*/
    public function mark($todoID, $status)
    {
        $this->todo->mark($todoID, $status);
        die(js::reload('parent'));
    }
}

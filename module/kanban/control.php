<?php
/**
 * The control file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yuchun Li <liyuchun@easycorp.ltd>
 * @package     kanban
 * @version     $Id: control.php 4460 2021-10-26 11:03:02Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class kanban extends control
{
    /**
     * Kanban space.
     *
     * @param  string $browseType all|my|other|closed
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function space($browseType = 'my', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title       = $this->lang->kanbanspace->common;
        $this->view->spaces      = $this->kanban->getSpaces($browseType, $pager);
        $this->view->browseType  = $browseType;
        $this->view->pager       = $pager;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->usersAvatar = $this->user->getAvatarPairs();

        $this->display();
    }

    /**
     * Create a space.
     *
     * @access public
     * @return void
     */
    public function createSpace()
    {
        if(!empty($_POST))
        {
            $spaceID = $this->kanban->createSpace();

            if(dao::isError()) die(js::error(dao::getError()));

            $this->loadModel('action')->create('kanbanSpace', $spaceID, 'created');
            die(js::reload('parent.parent'));
        }

        $this->view->users = $this->loadModel('user')->getPairs('noletter|noclosed');

        $this->display();
    }

    /**
     * Edit a space.
     *
     * @param  int    $spaceID
     * @access public
     * @return void
     */
    public function editSpace($spaceID)
    {
        if(!empty($_POST))
        {
            $changes = $this->kanban->updateSpace($spaceID);

            if(dao::isError()) die(js::error(dao::getError()));

            $actionID = $this->loadModel('action')->create('kanbanSpace', $spaceID, 'edited');
            $this->action->logHistory($actionID, $changes);

            die(js::reload('parent.parent'));
        }

        $this->view->space = $this->kanban->getSpaceById($spaceID);
        $this->view->users = $this->loadModel('user')->getPairs('noletter|noclosed');

        $this->display();
    }

    /**
     * Create a kanban.
     *
     * @param  int    $spaceID
     * @access public
     * @return void
     */
    public function create($spaceID = 0)
    {
        if(!empty($_POST))
        {
            $kanbanID = $this->kanban->create();

            if(dao::isError()) die(js::error(dao::getError()));

            $this->loadModel('action')->create('kanban', $kanbanID, 'created');
            die(js::reload('parent.parent'));
        }

        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->spaceID    = $spaceID;
        $this->view->spacePairs = array('' => '') + $this->kanban->getSpacePairs();
        
        $this->display();
    }

    /*
     * Close a kanban.
     *
     * @param  int    $kanbanID
     * @access public
     * @return void
     */
    public function close($kanbanID)
    {
        $this->loadModel('action');

        if(!empty($_POST))
        {
            $changes = $this->kanban->close($kanbanID);

            if(dao::isError()) die(js::error(dao::getError()));

            $actionID = $this->action->create('kanban', $kanbanID, 'closed', $this->post->comment);
            $this->action->logHistory($actionID, $changes);

            die(js::reload('parent.parent'));
        }

        $this->view->kanban  = $this->kanban->getByID($kanbanID);
        $this->view->actions = $this->action->getList('kanban', $kanbanID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

     /**
     * View a kanban.
     * 
     * @param  int    $kanbanID 
     * @access public
     * @return void
     */
    public function view($kanbanID)
    {
        $kanban = $this->kanban->getByID($kanbanID);

        $this->view->regions = $this->kanban->getKanbanData($kanbanID);
        $this->view->kanban  = $kanban;
        
        $this->display();
    }

    /**
     * Set WIP.
     *
     * @param  int    $columnID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function setWIP($columnID, $executionID = 0)
    {
        if($_POST)
        {
            $this->kanban->setWIP($columnID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('kanbancolumn', $columnID, 'Edited', '', $executionID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }

        $this->app->loadLang('story');

        $column = $this->kanban->getColumnById($columnID);
        if(!$column) die(js::error($this->lang->notFound) . js::locate($this->createLink('execution', 'kanban', "executionID=$executionID")));

        $status = zget($this->config->kanban->{$column->laneType . 'ColumnStatusList'}, $column->type);
        $title  = isset($column->parentName) ? $column->parentName . '/' . $column->name : $column->name;

        $this->view->title  = $title . $this->lang->colon . $this->lang->kanban->setWIP . '(' . $this->lang->kanban->WIP . ')';
        $this->view->column = $column;
        $this->view->status = $status;
        $this->display();
    }

    /**
     * Set lane info.
     *
     * @param  int    $laneID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function setLane($laneID, $executionID = 0)
    {
        if($_POST)
        {
            $this->kanban->setLane($laneID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('kanbanlane', $laneID, 'Edited', '', $executionID);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }

        $lane = $this->kanban->getLaneById($laneID);
        if(!$lane) die(js::error($this->lang->notFound) . js::locate($this->createLink('execution', 'kanban', "executionID=$executionID")));

        $this->view->title = zget($this->lang->kanban->laneTypeList, $lane->type) . $this->lang->colon . $this->lang->kanban->setLane;
        $this->view->lane  = $lane;

        $this->display();
    }

    /**
     * Set lane column info.
     *
     * @param  int $columnID
     * @param  int $executionID
     * @access public
     * @return void
     */
    public function setColumn($columnID, $executionID = 0)
    {
        $column = $this->kanban->getColumnById($columnID);

        if($_POST)
        {
            /* Check lane column name is unique. */
            $exist = $this->kanban->getColumnByName($this->post->name, $column->lane);
            if($exist and $exist->id != $columnID)
            {
                return $this->sendError($this->lang->kanban->noColumnUniqueName);
            }

            $changes = $this->kanban->updateLaneColumn($columnID, $column);
            if(dao::isError()) return $this->sendError(dao::getError());
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('kanbancolumn', $columnID, 'Edited', '', $executionID);
                $this->action->logHistory($actionID, $changes);
            }

            return $this->sendSuccess(array('locate' => 'parent'));
        }

        $this->view->column = $column;
        $this->view->title  = $column->name . $this->lang->colon . $this->lang->kanban->setColumn;
        $this->display();
    }

    /**
     * AJAX: Update the cards sorting of the lane column.
     *
     * @param  string $laneType story|bug|task
     * @param  int    $columnID
     * @param  string $orderBy id_desc|id_asc|pri_desc|pri_asc|lastEditedDate_desc|lastEditedDate_asc|deadline_desc|deadline_asc|assignedTo_asc
     * @access public
     * @return void
     */
    public function ajaxCardsSort($laneType, $columnID, $orderBy = 'id_desc')
    {
        $oldCards = array();
        $column   = $this->dao->select('parent,cards')->from(TABLE_KANBANCOLUMN)->where('id')->eq($columnID)->fetch();

        /* Get the cards of the kanban column. */
        if($column->parent == -1)
        {
            $childColumns = $this->dao->select('id,cards')->from(TABLE_KANBANCOLUMN)->where('parent')->eq($columnID)->fetchAll();
            foreach($childColumns as $childColumn)
            {
                $oldCards[$childColumn->id] = $childColumn->cards;
            }
        }
        else
        {
            $oldCards[$columnID] = $column->cards;
        }

        /* Update Kanban column card order. */
        $table = $this->config->objectTables[$laneType];
        foreach($oldCards as $colID => $cards)
        {
            if(empty($cards)) continue;
            $objects = $this->dao->select('id')->from($table)
                ->where('id')->in($cards)
                ->orderBy($orderBy)
                ->fetchPairs('id');

            $objectIdList = ',' . implode(',', $objects) . ',';
            $this->dao->update(TABLE_KANBANCOLUMN)->set('cards')->eq($objectIdList)->where('id')->eq($colID)->exec();
        }
        echo true;
    }

    /**
     * Change the order through the lane move up and down.
     *
     * @param  int     $executionID
     * @param  string  $currentType
     * @param  string  $targetType
     * @access public
     * @return void
     */
    public function laneMove($executionID, $currentType, $targetType)
    {
        if(empty($targetType)) return false;

        $this->kanban->updateLaneOrder($executionID, $currentType, $targetType);

        if(!dao::isError())
        {
            $laneID = $this->dao->select('id')->from(TABLE_KANBANLANE)->where('execution')->eq($executionID)->andWhere('type')->eq($currentType)->fetch('id');
            $this->loadModel('action')->create('kanbanlane', $laneID, 'Moved');
        }

        die(js::locate($this->createLink('execution', 'kanban', 'executionID=' . $executionID . '&type=all'), 'parent'));
    }

    /**
     * Ajax get contact users.
     *
     * @param  int    $contactListID
     * @access public
     * @return string
     */
    public function ajaxGetContactUsers($contactListID)
    {
        $this->loadModel('user');
        $list = $contactListID ? $this->user->getContactListByID($contactListID) : '';

        $users = $this->user->getPairs('devfirst|nodeleted|noclosed', $list ? $list->userList : '', $this->config->maxCount);

        if(!$contactListID) return print(html::select('team[]', $users, '', "class='form-control chosen' multiple"));

        return print(html::select('team[]', $users, $list->userList, "class='form-control chosen' multiple"));
    }
}

<?php
declare(strict_types=1);
/**
 * The control file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guangming Sun<sunguangming@easycorp.ltd>
 * @package     kanban
 * @version     $Id: control.php 4460 2021-10-26 11:03:02Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class kanban extends control
{
    /**
     * 看板空间列表。
     * Kanban space.
     *
     * @param  string $browseType involved|cooperation|public|private
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function space(string $browseType = 'involved', int $recTotal = 0, int $recPerPage = 15, int $pageID = 1)
    {
        $this->session->set('regionID', 'all', 'kanban');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title         = $this->lang->kanbanspace->common;
        $this->view->browseType    = $browseType;
        $this->view->pager         = $pager;
        $this->view->spaceList     = $this->kanban->getSpaceList($browseType, $pager, $this->cookie->showClosed);
        $this->view->unclosedSpace = $this->kanban->getCanViewObjects('kanbanspace', 'noclosed');
        $this->view->users         = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->userIdPairs   = $this->user->getPairs('noletter|nodeleted|showid');
        $this->view->usersAvatar   = $this->user->getAvatarPairs();

        $this->display();
    }

    /**
     * 创建看板空间。
     * Create a space.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function createSpace(string $type = 'private')
    {
        if(!empty($_POST))
        {
            $space = form::data($this->config->kanban->form->createSpace)
                ->setDefault('createdBy', $this->app->user->account)
                ->setDefault('createdDate', helper::now())
                ->get();

            $this->kanban->createSpace($space);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->title = $this->lang->kanban->createSpace;
        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->type  = $type;

        $this->display();
    }

    /**
     * 编辑看板空间。
     * Edit a space.
     *
     * @param  int    $spaceID
     * @access public
     * @return void
     */
    public function editSpace(int $spaceID)
    {
        if(!empty($_POST))
        {
            $space = form::data($this->config->kanban->form->editSpace)
                ->setDefault('lastEditedBy', $this->app->user->account)
                ->setDefault('lastEditedDate', helper::now())
                ->get();

            $this->kanban->updateSpace($space, $spaceID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $space = $this->kanban->getSpaceById($spaceID);
        if(in_array($space->type, array('cooperation', 'public'))) unset($this->lang->kanbanspace->typeList['private']);

        $this->view->spaceID     = $spaceID;
        $this->view->space       = $space;
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed');
        $this->view->typeList    = $this->lang->kanbanspace->typeList;
        $this->view->team        = ($space->type == 'private') ? $space->whitelist : $space->team;
        $this->view->defaultType = $space->type;

        $this->display();
    }

    /**
     * 激活看板空间。
     * Activate a space.
     *
     * @param  int    $spaceID
     * @access public
     * @return void
     */
    public function activateSpace(int $spaceID)
    {
        $this->loadModel('action');

        if(!empty($_POST))
        {
            $space = form::data($this->config->kanban->form->activateSpace)
                ->add('closedBy', '')
                ->add('closedDate', null)
                ->get();

            $this->kanban->activateSpace($spaceID, $space);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->space   = $this->kanban->getSpaceById($spaceID);
        $this->view->actions = $this->action->getList('kanbanSpace', $spaceID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter|noclosed|nodeleted');

        $this->display();
    }

    /*
     * 关闭看板空间。
     * Close a space.
     *
     * @param  int    $spaceID
     * @access public
     * @return void
     */
    public function closeSpace(int $spaceID)
    {
        if(!empty($_POST))
        {
            $space = form::data($this->config->kanban->form->closeSpace)
                ->add('activatedBy', '')
                ->add('activatedDate', null)
                ->get();

            $this->kanban->closeSpace($spaceID, $space);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->space   = $this->kanban->getSpaceById($spaceID);
        $this->view->actions = $this->loadModel('action')->getList('kanbanSpace', $spaceID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * 删除看板空间。
     * Delete a space.
     *
     * @param  int    $spaceID
     * @access public
     * @return void
     */
    public function deleteSpace(int $spaceID)
    {
        $this->kanban->delete(TABLE_KANBANSPACE, $spaceID);
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * 创建看板。
     * Create a kanban.
     *
     * @param  int    $spaceID
     * @param  string $type
     * @param  int    $copyKanbanID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function create(int $spaceID = 0, string $type = 'private', int $copyKanbanID = 0, string $extra = '')
    {
        if(!empty($_POST))
        {
            $kanbanID = $this->kanban->create();

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('kanban', $kanbanID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->kanbanZen->assignCreateVars($spaceID, $type, $copyKanbanID, $extra);
    }

    /**
     * Edit a kanban.
     *
     * @param  int    $kanbanID
     * @access public
     * @return void
     */
    public function edit(int $kanbanID = 0)
    {
        $this->loadModel('action');
        if(!empty($_POST))
        {
            $changes = $this->kanban->update($kanbanID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->action->create('kanban', $kanbanID, 'edited');
            $this->action->logHistory($actionID, $changes);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $kanban = $this->kanban->getByID($kanbanID);

        $space      = $this->kanban->getSpaceById($kanban->space);
        $spaceUsers = trim($space->owner) . ',' . trim($space->team);
        $users      = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $ownerPairs = $this->user->getPairs('noclosed|nodeleted', '', 0, $spaceUsers);

        $this->view->users      = $users;
        $this->view->ownerPairs = $ownerPairs;
        $this->view->spacePairs = array($kanban->space => $space->name) + $this->kanban->getSpacePairs($space->type);
        $this->view->kanban     = $kanban;
        $this->view->type       = $space->type;

        $this->display();
    }

    /**
     * Setting kanban.
     *
     * @param  int    $kanbanID
     * @access public
     * @return void
     */
    public function setting($kanbanID = 0)
    {
        if(!empty($_POST))
        {
            $changes = $this->kanban->setting($kanbanID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->loadModel('action')->create('kanban', $kanbanID, 'edited');
            $this->action->logHistory($actionID, $changes);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $kanban = $this->kanban->getByID($kanbanID);

        $this->view->kanban        = $kanban;
        $this->view->laneCount     = $this->kanban->getLaneCount($kanbanID);
        $this->view->heightType    = $kanban->displayCards > 2 ? 'custom' : 'auto';
        $this->view->displayCards  = $kanban->displayCards ? $kanban->displayCards : '';
        $this->view->enableImport  = empty($kanban->object) ? 'off' : 'on';
        $this->view->importObjects = empty($kanban->object) ? array() : explode(',', $kanban->object);

        $this->display();
    }

    /*
     * Activate a kanban.
     *
     * @param  int    $kanbanID
     * @access public
     * @return void
     */
    public function activate($kanbanID)
    {
        $this->loadModel('action');

        if(!empty($_POST))
        {
            $changes = $this->kanban->activate($kanbanID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->action->create('kanban', $kanbanID, 'activated', $this->post->comment);
            $this->action->logHistory($actionID, $changes);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->kanban  = $this->kanban->getByID($kanbanID);
        $this->view->actions = $this->action->getList('kanban', $kanbanID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');

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

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->action->create('kanban', $kanbanID, 'closed', $this->post->comment);
            $this->action->logHistory($actionID, $changes);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->kanban  = $this->kanban->getByID($kanbanID);
        $this->view->actions = $this->action->getList('kanban', $kanbanID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

     /**
     * View a kanban.
     *
     * @param  int        $kanbanID
     * @param  string|int $regionID
     * @access public
     * @return void
     */
    public function view($kanbanID, $regionID = '')
    {
        $kanban = $this->kanban->getByID($kanbanID);

        if(!$kanban)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'code' => 404, 'message' => '404 Not found'));
            return print(js::error($this->lang->notFound) . js::locate($this->createLink('kanban', 'space')));
        }

        $kanbanIdList = $this->kanban->getCanViewObjects();
        if(!$this->app->user->admin and !in_array($kanbanID, $kanbanIdList)) return print(js::error($this->lang->kanban->accessDenied) . js::locate('back'));

        $regions = $this->kanban->getRegionPairs($kanbanID);
        if(!$regionID) $regionID = $this->session->regionID ? $this->session->regionID : 'all';
        $regionID = !isset($regions[$regionID]) ? 'all' : $regionID;
        $this->session->set('regionID', $regionID, 'kanban');

        $this->view->title         = $this->lang->kanban->view;
        $this->view->kanban        = $kanban;
        $this->view->regions       = $regions;
        $this->view->kanbanList    = $this->kanban->getKanbanData($kanbanID, $regionID == 'all' ? '' : array($regionID));
        $this->view->regionID      = $regionID;
        $this->view->appendToolbar = $this->kanban->getHeaderActions($kanban);

        $this->display();
    }

    /**
     * Delete a kanban.
     *
     * @param  int    $kanbanID
     * @param  string $browseType involved|cooperation|public|private
     * @access public
     * @return void
     */
    public function delete($kanbanID, $browseType = 'involved')
    {
        $this->kanban->delete(TABLE_KANBAN, $kanbanID);
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * Create a region.
     *
     * @param  int    $kanbanID
     * @param  string $from kanban|execution
     * @access public
     * @return void
     */
    public function createRegion($kanbanID, $from = 'kanban')
    {
        if(!empty($_POST))
        {
            $kanban       = $from == 'execution' ? $this->loadModel('execution')->getByID($kanbanID) : $this->kanban->getByID($kanbanID);
            $copyRegionID = (int)$_POST['region'];
            unset($_POST['region']);

            $regionID = $this->kanban->createRegion($kanban, '', $copyRegionID, $from);

            $this->session->set('regionID', $regionID, 'kanban');
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $regions     = $this->kanban->getRegionPairs($kanbanID, 0, $from);
        $regionPairs = array();
        foreach($regions as $regionID => $region)
        {
            $max_length = 20;
            if (mb_strlen($region, 'UTF-8') > $max_length) {
                $region = mb_substr($region, 0, $max_length, 'UTF-8') . '...';
            }

            $regionPairs[$regionID] = $this->lang->kanban->copy . '"' . $region . '"' . $this->lang->kanban->styleCommon;
        }

        $this->view->regions = array('custom' => $this->lang->kanban->custom) + $regionPairs;
        $this->display();
    }

    /*
     * Edit a region
     *
     * @param  int    $regionID
     * @access public
     * @return void
     */
    public function editRegion($regionID = 0)
    {
        $this->loadModel('action');
        if(!empty($_POST))
        {
            $changes = $this->kanban->updateRegion($regionID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->action->create('kanbanregion', $regionID, 'edited');
            $this->action->logHistory($actionID, $changes);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->region = $this->kanban->getRegionByID($regionID);
        $this->display();
    }

    /**
     * Sort regions.
     *
     * @param  string $regions
     * @access public
     * @return void
     */
    public function sortRegion($regions = '')
    {
        if(empty($regions)) return;
        $regionIdList = explode(',', trim($regions, ','));

        $order = 1;
        foreach($regionIdList as $regionID)
        {
            $this->dao->update(TABLE_KANBANREGION)->set('`order`')->eq($order)->where('id')->eq($regionID)->exec();
            $order++;
        }

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * Sort group.
     *
     * @param  int    $region
     * @param  int    $groups
     * @access public
     * @return void
     */
    public function sortGroup($region, $groups)
    {
        $groups = array_filter(explode(',', trim($groups, ',')));
        if(empty($groups)) return $this->send(array('result' => 'fail', 'message' => 'No groups to sort.'));

        $this->kanban->sortGroup($region, $groups);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success'));
    }

    /**
     * Delete a region
     *
     * @param  int    $regionID
     * @access public
     * @return void
     */
    public function deleteRegion($regionID)
    {
        $this->kanban->delete(TABLE_KANBANREGION, $regionID);
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * Create a lane for a kanban.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @param  string $from kanban|execution
     * @access public
     * @return void
     */
    public function createLane($kanbanID, $regionID, $from = 'kanban')
    {
        if(!empty($_POST))
        {
            $laneID = $this->kanban->createLane($kanbanID, $regionID, null);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('kanbanLane', $laneID, 'created');

            if($from == 'execution')
            {
                if(dao::isError()) return $this->sendError(dao::getError());

                $executionLaneType = $this->session->executionLaneType ? $this->session->executionLaneType : 'all';
                $executionGroupBy  = $this->session->executionGroupBy ? $this->session->executionGroupBy : 'default';
                $kanbanData   = $this->loadModel('kanban')->getRDKanban($kanbanID, $executionLaneType, 'id_desc', $regionID, $executionGroupBy);
                $kanbanData   = json_encode($kanbanData);
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "parent.updateKanban($kanbanData, $regionID)"));
            }

            $callback = $this->kanban->getKanbanCallback($kanbanID, $regionID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $this->view->lanes    = $this->kanban->getLanePairsByRegion($regionID, $from == 'kanban' ? 'all' : 'story');
        $this->view->from     = $from;
        $this->view->regionID = $regionID;

        if($from == 'kanban') $this->display();
        if($from == 'execution') $this->display('kanban', 'createexeclane');
    }

    /**
     * Sort lanes.
     *
     * @param  int    $regionID
     * @param  string $lanes
     * @access public
     * @return array
     */
    public function sortLane($regionID, $lanes = '')
    {
        if(empty($lanes)) return;
        $lanes = explode(',', trim($lanes, ','));

        $order = 1;
        foreach($lanes as $laneID)
        {
            $this->dao->update(TABLE_KANBANLANE)->set('`order`')->eq($order)->where('id')->eq($laneID)->andWhere('region')->eq($regionID)->exec();
            $order++;
        }
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $region     = $this->kanban->getRegionByID($regionID);
        $kanbanData = $this->kanban->getKanbanData($region->kanban, $regionID);
        $kanbanData = reset($kanbanData);
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'regionID' => 'region' . $regionID, 'kanbanData' => $kanbanData));
    }

    /**
     * Sort columns.
     *
     * @param  int    $regionID
     * @param  string $columns
     * @access public
     * @return array|string
     */
    public function sortColumn($regionID, $columns = '')
    {
        if(empty($columns)) return;
        $columns =  explode(',', trim($columns, ','));

        $order = 1;
        foreach($columns as $columnID)
        {
            $this->dao->update(TABLE_KANBANCOLUMN)->set('`order`')->eq($order)->where('id')->eq($columnID)->andWhere('region')->eq($regionID)->exec();
            $order ++;
        }
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $region     = $this->kanban->getRegionByID($regionID);
        $kanbanData = $this->kanban->getKanbanData($region->kanban, $regionID);
        $kanbanData = reset($kanbanData);
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'regionID' => 'region' . $regionID, 'kanbanData' => $kanbanData));
    }

    /**
     * Delete a lane.
     *
     * @param  int    $regionID
     * @param  int    $kanbanID
     * @param  int    $laneID
     * @access public
     * @return void
     */
    public function deleteLane($regionID, $kanbanID, $laneID)
    {
        $lane = $this->kanban->getLaneById($laneID);
        $this->kanban->delete(TABLE_KANBANLANE, $laneID);

        if($this->app->tab == 'execution')
        {
            if(dao::isError()) return $this->sendError(dao::getError());

            $executionLaneType = $this->session->executionLaneType ? $this->session->executionLaneType : 'all';
            $executionGroupBy  = $this->session->executionGroupBy ? $this->session->executionGroupBy : 'default';
            $kanbanData   = $this->loadModel('kanban')->getRDKanban($kanbanID, $executionLaneType, 'id_desc', $regionID, $executionGroupBy);
            $kanbanData   = json_encode($kanbanData);
            return print("<script>parent.updateKanban($kanbanData, $regionID)</script>");
        }

        $lanes = $this->kanban->getLanePairsByGroup($lane->group);
        if($lanes)
        {
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => array('name' => 'updateKanbanRegion', 'params' => array('region' . $lane->region, array('items' => array(array('key' => 'group' . $lane->group, 'data' => array('lanes' => array(array('id' => $laneID, 'name' => $laneID, 'deleted' => true))))))))));
        }
        else
        {
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => array('name' => 'updateKanbanRegion', 'params' => array('region' . $regionID, array('items' => array(array('key' => 'group' . $lane->group, 'deleted' => true)))))));
        }
    }

    /**
     * Create a column for a kanban.
     *
     * @param  int    $columnID
     * @param  string $position left|right
     * @access public
     * @return void
     */
    public function createColumn($columnID, $position = 'left')
    {
        $column = $this->kanban->getColumnByID($columnID);

        if($_POST)
        {
            $order    = $position == 'left' ? $column->order : $column->order + 1;
            $columnID = $this->kanban->createColumn($column->region, null, $order, $column->parent);
            if(dao::isError()) $this->send(array('message' => dao::getError(), 'result' => 'fail'));

            $this->loadModel('action')->create('kanbanColumn', $columnID, 'Created');

            $region   = $this->kanban->getRegionByID($column->region);
            $callback = $this->kanban->getKanbanCallback($region->kanban, $region->id);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $this->view->title    = $this->lang->kanban->createColumn;
        $this->view->column   = $column;
        $this->view->position = $position;
        $this->display();
    }

    /**
     * Split column.
     *
     * @param  int    $columnID
     * @access public
     * @return void
     */
    public function splitColumn($columnID)
    {
        if(!empty($_POST))
        {
            $this->kanban->splitColumn($columnID);
            if(dao::isError()) $this->send(array('message' => dao::getError(), 'result' => 'fail'));

            $column   = $this->kanban->getColumnById($columnID);
            $region   = $this->kanban->getRegionByID($column->region);
            $callback = $this->kanban->getKanbanCallback($region->kanban, $region->id);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $this->view->column = $this->kanban->getColumnByID($columnID);
        $this->display();
    }

    /**
     * Archive a column.
     *
     * @param  int    $columnID
     * @access public
     * @return void
     */
    public function archiveColumn($columnID)
    {
        $column = $this->kanban->getColumnById($columnID);
        if($column->parent)
        {
            $children = $this->dao->select('count(*) as count')->from(TABLE_KANBANCOLUMN)
                ->where('parent')->eq($column->parent)
                ->andWhere('id')->ne($column->id)
                ->andWhere('deleted')->eq('0')
                ->andWhere('archived')->eq('0')
                ->fetch('count');

            if(!$children) $this->dao->update(TABLE_KANBANCOLUMN)->set('parent')->eq(0)->where('id')->eq($column->parent)->exec();
        }

        $this->kanban->archiveColumn($columnID);
        if(dao::isError()) $this->send(array('message' => dao::getError(), 'result' => 'fail'));

        $this->loadModel('action')->create('kanbancolumn', $columnID, 'archived');

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => array('name' => 'updateKanbanRegion', 'params' => array('region' . $column->region, array('items' => array(array('key' => 'group' . $column->group, 'data' => array('cols' => array(array('id' => $columnID, 'name' => $columnID, 'deleted' => true))))))))));
    }

    /**
     * Restore a column.
     *
     * @param  int    $columnID
     * @access public
     * @return void
     */
    public function restoreColumn($columnID)
    {
        $this->kanban->restoreColumn($columnID);
        if(dao::isError()) return print(js::error(dao::getError()));

        $this->loadModel('action')->create('kanbancolumn', $columnID, 'restore');
        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * View archived columns.
     *
     * @param  int    $regionID
     * @access public
     * @return void
     */
    public function viewArchivedColumn($regionID)
    {
        $region = $this->kanban->getRegionByID($regionID);

        $this->view->kanban  = $this->kanban->getByID($region->kanban);
        $this->view->columns = $this->kanban->getColumnsByObject('region', $regionID, '1');

        $this->display();
    }

    /**
     * Delete a column.
     *
     * @param  int    $columnID
     * @access public
     * @return void
     */
    public function deleteColumn($columnID)
    {
        $column = $this->kanban->getColumnById($columnID);
        if($column->parent > 0) $this->kanban->processCards($column);

        $this->dao->delete()->from(TABLE_KANBANCOLUMN)->where('id')->eq($columnID)->exec();

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => array('name' => 'updateKanbanRegion', 'params' => array('region' . $column->region, array('items' => array(array('key' => 'group' . $column->group, 'data' => array('cols' => array(array('id' => $columnID, 'name' => $columnID, 'deleted' => true))))))))));
    }

    /**
     * Create a card.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @param  int    $groupID
     * @param  int    $columnID
     * @access public
     * @return void
     */
    public function createCard($kanbanID = 0, $regionID = 0, $groupID = 0, $columnID = 0)
    {
        if($_POST)
        {
            $cardID = $this->kanban->createCard($kanbanID, $regionID, $groupID, $columnID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('kanbancard', $cardID, 'created');

            $callback = $this->kanban->getKanbanCallback($kanbanID, $regionID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $kanban      = $this->kanban->getById($kanbanID);
        $kanbanUsers = $kanbanID == 0 ? ',' : trim($kanban->owner) . ',' . trim($kanban->team);
        $users       = $this->loadModel('user')->getPairs('noclosed|nodeleted', '', 0, $kanbanUsers);

        $this->view->users     = $users;
        $this->view->lanePairs = $this->kanban->getLanePairsByGroup($groupID);

        $this->display();
    }

    /**
     * Batch create cards.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @param  int    $groupID
     * @param  int    $columnID
     * @access public
     * @return void
     */
    public function batchCreateCard($kanbanID = 0, $regionID = 0, $groupID = 0, $columnID = 0)
    {
        $kanban = $this->kanban->getById($kanbanID);

        if($_POST)
        {
            $this->kanban->batchCreateCard($kanbanID, $regionID, $groupID, $columnID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $callback = $this->kanban->getKanbanCallback($kanbanID, $regionID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $kanbanUsers = $kanbanID == 0 ? ',' : trim($kanban->owner) . ',' . trim($kanban->team);
        $users       = $this->loadModel('user')->getPairs('noclosed|nodeleted', '', 0, $kanbanUsers);

        $this->view->title     = $this->lang->kanban->batchCreateCard;
        $this->view->users     = $users;
        $this->view->lanePairs = $this->kanban->getLanePairsByGroup($groupID);

        $this->display();
    }

    /**
     * Edit a card.
     *
     * @param  int    $cardID
     * @access public
     * @return void
     */
    public function editCard($cardID)
    {
        $this->loadModel('action');
        $this->loadModel('user');
        if(!empty($_POST))
        {
            $changes = $this->kanban->updateCard($cardID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->action->create('kanbanCard', $cardID, 'edited');
            $this->action->logHistory($actionID, $changes);

            $card     = $this->kanban->getCardByID($cardID);
            $callback = $this->kanban->getKanbanCallback($card->kanban, $card->region);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $card        = $this->kanban->getCardByID($cardID);
        $kanban      = $this->kanban->getById($card->kanban);
        $kanbanUsers = $card->kanban == 0 ? ',' : trim($kanban->owner) . ',' . trim($kanban->team);
        $kanbanUsers = $this->user->getPairs('noclosed|nodeleted', '', 0, $kanbanUsers);
        $users       = $this->user->getPairs('noclosed|nodeleted');

        $this->view->card        = $card;
        $this->view->actions     = $this->action->getList('kanbancard', $cardID);
        $this->view->users       = $users;
        $this->view->kanbanUsers = $kanbanUsers;
        $this->view->kanban      = $kanban;

        $this->display();
    }

    /**
     * Finish a card.
     *
     * @param  int    $cardID
     * @param  int    $kanbanID
     * @access public
     * @return void
     */
    public function finishCard($cardID, $kanbanID)
    {
        $this->loadModel('action');

        $oldCard = $this->kanban->getCardByID($cardID);
        $this->dao->update(TABLE_KANBANCARD)->set('progress')->eq(100)->set('status')->eq('done')->where('id')->eq($cardID)->exec();
        $card = $this->kanban->getCardByID($cardID);

        $changes = common::createChanges($oldCard, $card);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $actionID = $this->action->create('kanbanCard', $cardID, 'finished');
        $this->action->logHistory($actionID, $changes);

        $callback = $this->kanban->getKanbanCallback($card->kanban, $card->region);
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
    }

    /**
     * Activate a card.
     *
     * @param  int    $cardID
     * @param  int    $kanbanID
     * @access public
     * @return void
     */
    public function activateCard($cardID, $kanbanID)
    {
        $this->loadModel('action');
        if(!empty($_POST))
        {
            $oldCard = $this->kanban->getCardByID($cardID);
            $this->kanban->activateCard($cardID);
            $card = $this->kanban->getCardByID($cardID);

            $changes = common::createChanges($oldCard, $card);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->action->create('kanbanCard', $cardID, 'activated');
            $this->action->logHistory($actionID, $changes);

            $callback = $this->kanban->getKanbanCallback($card->kanban, $card->region);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $this->view->card    = $this->kanban->getCardByID($cardID);
        $this->view->actions = $this->action->getList('kanbancard', $cardID);
        $this->view->users   = $this->loadModel('user')->getPairs('noclosed|nodeleted');

        $this->display();
    }

    /**
     * View a card.
     *
     * @param  int    $cardID
     * @access public
     * @return void
     */
    public function viewCard($cardID)
    {
        $this->loadModel('action');

        $card   = $this->kanban->getCardByID($cardID);
        $kanban = $this->kanban->getByID($card->kanban);
        $space  = $this->kanban->getSpaceById($kanban->space);

        $this->view->title       = 'CARD #' . $card->id . ' ' . $card->name;
        $this->view->card        = $card;
        $this->view->actions     = $this->action->getList('kanbancard', $cardID);
        $this->view->users       = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->space       = $space;
        $this->view->kanban      = $kanban;
        $this->view->usersAvatar = $this->user->getAvatarPairs();

        $this->display();
    }

    /**
     * Move a card.
     *
     * @param  int    $cardID
     * @param  int    $fromColID
     * @param  int    $toColID
     * @param  int    $fromLaneID
     * @param  int    $toLaneID
     * @param  int    $kanbanID
     * @access public
     * @return void
     */
    public function moveCard($cardID, $fromColID, $toColID, $fromLaneID, $toLaneID, $kanbanID = 0)
    {
        $this->kanban->moveCard($cardID, $fromColID, $toColID, $fromLaneID, $toLaneID, $kanbanID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->loadModel('action')->create('kanbanCard', $cardID, 'moved');

        $card     = $this->kanban->getCardByID($cardID);
        $callback = $this->kanban->getKanbanCallback($card->kanban, $card->region);
        return $this->send(array('result' => 'success', 'callback' => $callback));
    }

    /**
     * Import card.
     *
     * @param  int $kanbanID
     * @param  int $regionID
     * @param  int $groupID
     * @param  int $columnID
     * @param  int $selectedKanbanID
     * @param  int $recTotal
     * @param  int $recPerPage
     * @param  int $pageID
     * @access public
     * @return void
     */
    public function importCard($kanbanID = 0, $regionID = 0, $groupID = 0, $columnID = 0, $selectedKanbanID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $cards2Imported = $this->kanban->getCards2Import($selectedKanbanID, $kanbanID, $pager);

        if($_POST)
        {
            $importedIDList = $this->kanban->importCard($kanbanID, $regionID, $groupID, $columnID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            foreach($importedIDList as $cardID)
            {
                $this->loadModel('action')->create('kanbancard', $cardID, 'importedcard', '', $cards2Imported[$cardID]->kanban);
            }

            $callback = $this->kanban->getKanbanCallback($kanbanID, $regionID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        /* Find Kanban other than this kanban. */
        $kanbanPairs = $this->kanban->getKanbanPairs();
        unset($kanbanPairs[$kanbanID]);

        $this->view->cards2Imported   = $cards2Imported;
        $this->view->kanbanPairs      = array($this->lang->kanban->allKanban) + $kanbanPairs;
        $this->view->lanePairs        = $this->kanban->getLanePairsByGroup($groupID);
        $this->view->users            = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->pager            = $pager;
        $this->view->selectedKanbanID = $selectedKanbanID;
        $this->view->kanbanID         = $kanbanID;
        $this->view->regionID         = $regionID;
        $this->view->groupID          = $groupID;
        $this->view->columnID         = $columnID;

        $this->display();
    }

    /**
     * Import plan.
     *
     * @param  int $kanbanID
     * @param  int $regionID
     * @param  int $groupID
     * @param  int $columnID
     * @param  int $selectedProductID
     * @param  int $recTotal
     * @param  int $recPerPage
     * @param  int $pageID
     * @access public
     * @return void
     */
    public function importPlan($kanbanID = 0, $regionID = 0, $groupID = 0, $columnID = 0, $selectedProductID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if($_POST)
        {
            $importedIDList = $this->kanban->importObject($kanbanID, $regionID, $groupID, $columnID, 'productplan');
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            foreach($importedIDList as $cardID => $planID)
            {
                $this->loadModel('action')->create('kanbancard', $cardID, 'importedProductplan', '', $planID);
            }

            $callback = $this->kanban->getKanbanCallback($kanbanID, $regionID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $this->loadModel('product');
        $this->loadModel('productplan');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $productPairs      = $this->product->getPairs('', 0, '', 'all');
        $productPairs      = array($this->lang->kanban->allProducts) + $productPairs;
        $selectedProductID = empty($selectedProductID) ? key($productPairs) : $selectedProductID;

        /* Waterfall project has no plan. */
        $excludeProducts = $this->dao->select('t1.product')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t2.type')->eq('project')
            ->andWhere('t2.model')->ne('scrum')
            ->andWhere('t2.hasProduct')->eq('0')
            ->fetchPairs();

        foreach($productPairs as $id => $name)
        {
            if(isset($excludeProducts[$id])) unset($productPairs[$id]);
        }

        $this->view->products          = $productPairs;
        $this->view->selectedProductID = $selectedProductID;
        $this->view->lanePairs         = $this->kanban->getLanePairsByGroup($groupID);
        $this->view->plans2Imported    = $this->productplan->getList($selectedProductID, 0, 'all', $pager, 'begin_desc', 'skipparent|noproduct');
        $this->view->pager             = $pager;
        $this->view->kanbanID          = $kanbanID;
        $this->view->regionID          = $regionID;
        $this->view->groupID           = $groupID;
        $this->view->columnID          = $columnID;

        $this->display();
    }

    /**
     * Import release.
     *
     * @param  int $kanbanID
     * @param  int $regionID
     * @param  int $groupID
     * @param  int $columnID
     * @param  int $selectedProductID
     * @param  int $recTotal
     * @param  int $recPerPage
     * @param  int $pageID
     * @access public
     * @return void
     */
    public function importRelease($kanbanID = 0, $regionID = 0, $groupID = 0, $columnID = 0, $selectedProductID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if($_POST)
        {
            $importedIDList = $this->kanban->importObject($kanbanID, $regionID, $groupID, $columnID, 'release');
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            foreach($importedIDList as $cardID => $releaseID)
            {
                $this->loadModel('action')->create('kanbancard', $cardID, 'importedRelease', '', $releaseID);
            }

            $callback = $this->kanban->getKanbanCallback($kanbanID, $regionID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $this->loadModel('product');
        $this->loadModel('release');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Kanban products has no releases. */
        $productPairs   = $this->product->getPairs('', 0, '', 'all');
        $kanbanProducts = $this->dao->select('t1.product')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t2.type')->eq('project')
            ->andWhere('t2.model')->eq('kanban')
            ->andWhere('t2.hasProduct')->eq('0')
            ->fetchPairs();

        foreach($productPairs as $id => $name)
        {
            if(isset($kanbanProducts[$id])) unset($productPairs[$id]);
        }

        $this->view->products          = array($this->lang->kanban->allProducts) + $productPairs;
        $this->view->selectedProductID = $selectedProductID;
        $this->view->lanePairs         = $this->kanban->getLanePairsByGroup($groupID);
        $this->view->releases2Imported = $this->release->getList($selectedProductID, 'all', 'all', 't1.date_desc', '', $pager);
        $this->view->pager             = $pager;
        $this->view->kanbanID          = $kanbanID;
        $this->view->regionID          = $regionID;
        $this->view->groupID           = $groupID;
        $this->view->columnID          = $columnID;

        $this->display();
    }

    /**
     * Import build.
     *
     * @param  int $kanbanID
     * @param  int $regionID
     * @param  int $groupID
     * @param  int $columnID
     * @param  int $selectedProjectID
     * @param  int $recTotal
     * @param  int $recPerPage
     * @param  int $pageID
     * @access public
     * @return void
     */
    public function importBuild($kanbanID = 0, $regionID = 0, $groupID = 0, $columnID = 0, $selectedProjectID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if($_POST)
        {
            $importedIDList = $this->kanban->importObject($kanbanID, $regionID, $groupID, $columnID, 'build');
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            foreach($importedIDList as $cardID => $buildID)
            {
                $this->loadModel('action')->create('kanbancard', $cardID, 'importedBuild', '', $buildID);
            }

            $callback = $this->kanban->getKanbanCallback($kanbanID, $regionID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $this->loadModel('build');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $builds2Imported = array();
        $projects        = array($this->lang->kanban->allProjects);
        $projects       += $this->loadModel('project')->getPairsByProgram(0, 'all', false, 'order_asc', 'kanban');
        $builds2Imported = $this->build->getProjectBuilds($selectedProjectID, 'all', 0, 't1.date_desc,t1.id_desc', $pager);

        $this->view->projects          = $projects;
        $this->view->selectedProjectID = $selectedProjectID;
        $this->view->builds2Imported   = $builds2Imported;
        $this->view->lanePairs         = $this->kanban->getLanePairsByGroup($groupID);
        $this->view->users             = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->pager             = $pager;
        $this->view->kanbanID          = $kanbanID;
        $this->view->regionID          = $regionID;
        $this->view->groupID           = $groupID;
        $this->view->columnID          = $columnID;

        $this->display();
    }

    /**
     * Import execution.
     *
     * @param  int $kanbanID
     * @param  int $regionID
     * @param  int $groupID
     * @param  int $columnID
     * @param  int $selectedProjectID
     * @param  int $recTotal
     * @param  int $recPerPage
     * @param  int $pageID
     * @access public
     * @return void
     */
    public function importExecution($kanbanID = 0, $regionID = 0, $groupID = 0, $columnID = 0, $selectedProjectID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if($_POST)
        {
            $importedIDList = $this->kanban->importObject($kanbanID, $regionID, $groupID, $columnID, 'execution');
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            foreach($importedIDList as $cardID => $executionID)
            {
                $this->loadModel('action')->create('kanbancard', $cardID, 'importedExecution', '', $executionID);
            }

            $callback = $this->kanban->getKanbanCallback($kanbanID, $regionID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $this->loadModel('project');
        $this->loadModel('execution');
        $this->loadModel('programplan');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->projects            = array($this->lang->kanban->allProjects) + $this->project->getPairsByProgram(0, 'all', false, '', '', '', 'multiple');
        $this->view->selectedProjectID   = $selectedProjectID;
        $this->view->lanePairs           = $this->kanban->getLanePairsByGroup($groupID);
        $this->view->executions2Imported = $this->execution->getStatData($selectedProjectID, 'undone', 0, 0, false, 'hasParentName', 'id_asc', $pager);
        $this->view->users               = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->pager               = $pager;
        $this->view->kanbanID            = $kanbanID;
        $this->view->regionID            = $regionID;
        $this->view->groupID             = $groupID;
        $this->view->columnID            = $columnID;

        $this->display();
    }

    /**
     * Import ticket.
     *
     * @param  int $kanbanID
     * @param  int $regionID
     * @param  int $groupID
     * @param  int $columnID
     * @param  int $selectedProductID
     * @param  int $recTotal
     * @param  int $recPerPage
     * @param  int $pageID
     * @access public
     * @return void
     */
    public function importTicket($kanbanID = 0, $regionID = 0, $groupID = 0, $columnID = 0, $selectedProductID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if($_POST)
        {
            $importedIDList = $this->kanban->importObject($kanbanID, $regionID, $groupID, $columnID, 'ticket');
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            foreach($importedIDList as $cardID => $ticketID)
            {
                $this->loadModel('action')->create('kanbancard', $cardID, 'importedTicket', '', $ticketID);
            }

            return print(js::locate($this->createLink('kanban', 'view', "kanbanID=$kanbanID"), 'parent.parent'));
        }

        $this->loadModel('feedback');
        $this->loadModel('ticket');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->products          = array('all' => $this->lang->kanban->allProducts) + $this->feedback->getGrantProducts();
        $this->view->selectedProductID = $selectedProductID;
        $this->view->lanePairs         = $this->kanban->getLanePairsByGroup($groupID);
        $this->view->tickets2Imported  = $this->ticket->getTicketByProduct($selectedProductID, 'noclosed|nodone', 'id_desc', $pager);
        $this->view->users             = $this->loadModel('user')->getPairs('noletter');
        $this->view->pager             = $pager;
        $this->view->kanbanID          = $kanbanID;
        $this->view->regionID          = $regionID;
        $this->view->groupID           = $groupID;
        $this->view->columnID          = $columnID;

        $this->display();
    }

    /**
     * Set a card's color.
     *
     * @param  int   $cardID
     * @param  int   $color
     * @param  int   $kanbanID
     * @access public
     * @return string
     */
    public function setCardColor($cardID, $color, $kanbanID)
    {
        $this->kanban->updateCardColor($cardID, $color);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $card     = $this->kanban->getCardByID($cardID);
        $callback = $this->kanban->getKanbanCallback($card->kanban, $card->region);
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
    }

    /**
     * Sort cards.
     *
     * @param  int    $kanbanID
     * @param  int    $laneID
     * @param  int    $columnID
     * @param  string $cards
     * @access public
     * @return void
     */
    public function sortCard($kanbanID, $laneID, $columnID, $cards = '')
    {
        if(empty($cards)) return;

        $this->dao->update(TABLE_KANBANCELL)->set('cards')->eq(",$cards,")->where('kanban')->eq($kanbanID)->andWhere('lane')->eq($laneID)->andWhere('`column`')->eq($columnID)->exec();

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $lane     = $this->kanban->getLaneById($laneID);
        $callback = $this->kanban->getKanbanCallback($kanbanID, $lane->region);
        return $this->send(array('result' => 'success', 'callback' => $callback));
    }

    /**
     * Archive a card.
     *
     * @param  int    $cardID
     * @access public
     * @return void
     */
    public function archiveCard($cardID)
    {
        $changes = $this->kanban->archiveCard($cardID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $actionID = $this->loadModel('action')->create('kanbancard', $cardID, 'archived');
        $this->action->logHistory($actionID, $changes);

        $card = $this->kanban->getCardByID($cardID);
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => array('name' => 'updateKanbanRegion', 'params' => array('region' . $card->region, array('items' => array(array('key' => 'group' . $card->group, 'data' => array('items' => array(array('id' => $cardID, 'name' => $cardID, 'deleted' => true))))))))));
    }

    /**
     * View archived cards.
     *
     * @param  int    $regionID
     * @access public
     * @return void
     */
    public function viewArchivedCard($regionID)
    {
        $region = $this->kanban->getRegionByID($regionID);

        $cards = $this->kanban->getCardsByObject('region', $regionID, 1);
        foreach($this->config->kanban->fromType as $fromType)
        {
            $cards = $this->kanban->getImportedCards($region->kanban, $cards, $fromType, 1, $regionID);
        }

        $this->view->kanban      = $this->kanban->getByID($region->kanban);
        $this->view->cards       = $cards;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->userIdPairs = $this->user->getPairs('noletter|nodeleted|showid');
        $this->view->usersAvatar = $this->user->getAvatarPairs();

        $this->display();
    }

    /**
     * Restore a card.
     *
     * @param  int    $cardID
     * @access public
     * @return void
     */
    public function restoreCard($cardID)
    {
        $this->kanban->restoreCard($cardID);

        $changes = $this->kanban->restoreCard($cardID);
        if(dao::isError()) return print(js::error(dao::getError()));

        $actionID = $this->loadModel('action')->create('kanbancard', $cardID, 'restore');
        $this->action->logHistory($actionID, $changes);

        return $this->send(array('result' => 'success', 'load' => true));
    }

	/**
	 * Delete a card.
	 *
	 * @param  int    $cardID
	 * @access public
	 * @return void
	 */
    public function deleteCard($cardID)
    {
        $card = $this->kanban->getCardByID($cardID);
        if($card->fromType == '') $this->kanban->delete(TABLE_KANBANCARD, $cardID);
        if($card->fromType != '') $this->dao->delete()->from(TABLE_KANBANCARD)->where('id')->eq($cardID)->exec();

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => array('name' => 'updateKanbanRegion', 'params' => array('region' . $card->region, array('items' => array(array('key' => 'group' . $card->group, 'data' => array('items' => array(array('id' => $cardID, 'name' => $cardID, 'deleted' => true))))))))));
    }

    /**
     * Delete a card.
     *
     * @param  string $objectType story|task|bug
     * @param  int    $objectID
     * @param  int    $regionID
     * @access public
     * @return void
     */
    public function deleteObjectCard($objectType, $objectID, $regionID)
    {
        if(!($objectType == 'task' or $objectType == 'story' or $objectType == 'bug')) return false;
        $table = 'TABLE_' . strtoupper($objectType);
        $this->loadModel($objectType)->delete(constant($table), $objectID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $kanbanID    = $regionID ? $this->kanban->getKanbanIDByRegion($regionID) : $this->session->execution;
        $browseType  = $this->config->vision == 'lite' ? 'task' : $this->session->executionLaneType;
        $groupBy     = $this->session->executionGroupBy ? $this->session->executionGroupBy : 'default';
        $kanbanGroup = $this->kanban->getRDKanban($kanbanID, $browseType, 'id_desc', 0, $groupBy);

        return print(json_encode($kanbanGroup));
    }

    /**
     * Set WIP.
     *
     * @param  int    $columnID
     * @param  int    $executionID
     * @param  string $from kanban|execution
     * @access public
     * @return void
     */
    public function setWIP($columnID, $executionID = 0, $from = 'kanban')
    {
        $column = $this->kanban->getColumnById($columnID);
        if($_POST)
        {
            $this->kanban->setWIP($columnID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('kanbancolumn', $columnID, 'Edited', '', $executionID);

            if($from == 'RDKanban')
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "refreshKanban()"));
            }
            elseif($from == 'kanban')
            {
                $region   = $this->kanban->getRegionByID($column->region);
                $callback = $this->kanban->getKanbanCallback($region->kanban, $region->id);
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
            }
            else
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
            }
        }

        $this->app->loadLang('story');

        if(!$column) return print(js::error($this->lang->notFound) . js::locate($this->createLink('execution', 'kanban', "executionID=$executionID")));

        $title  = isset($column->parentName) ? $column->parentName . '/' . $column->name : $column->name;

        $this->view->title  = $title . $this->lang->colon . $this->lang->kanban->setWIP . '(' . $this->lang->kanban->WIP . ')';
        $this->view->column = $column;
        $this->view->from   = $from;

        if($from != 'kanban') $this->view->status = zget($this->config->kanban->{$column->laneType . 'ColumnStatusList'}, $column->type);
        $this->display();
    }

    /**
     * Set lane info.
     *
     * @param  int    $laneID
     * @param  int    $executionID
     * @param  string $from kanban|execution
     * @access public
     * @return void
     */
    public function setLane($laneID, $executionID = 0, $from = 'kanban')
    {
        if($_POST)
        {
            $this->kanban->setLane($laneID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('kanbanlane', $laneID, 'Edited', '', $executionID);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }

        $lane = $this->kanban->getLaneById($laneID);
        if(!$lane) return print(js::error($this->lang->notFound) . js::locate($this->createLink('execution', 'kanban', "executionID=$executionID")));

        $this->view->title = $from == 'kanban' ? $this->lang->edit . '“' . $lane->name . '”' . $this->lang->kanbanlane->common : zget($this->lang->kanban->laneTypeList, $lane->type) . $this->lang->colon . $this->lang->kanban->setLane;
        $this->view->lane  = $lane;
        $this->view->from  = $from;

        $this->display();
    }

    /**
     * Edit lane's name
     *
     * @param  int    $laneID
     * @param  int    $executionID
     * @param  string $from
     * @access public
     * @return void
     */
    public function editLaneName($laneID, $executionID = 0, $from = 'kanban')
    {
        if($_POST)
        {
            $this->kanban->setLane($laneID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('kanbanlane', $laneID, 'Edited', '', $executionID);

            $lane     = $this->kanban->getLaneById($laneID);
            $region   = $this->kanban->getRegionByID($lane->region);
            $callback = $this->kanban->getKanbanCallback($region->kanban, $region->id);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $lane = $this->kanban->getLaneById($laneID);
        if(!$lane) return print(js::error($this->lang->notFound) . js::locate($this->createLink('execution', 'kanban', "executionID=$executionID")));

        $this->view->title = $from == 'kanban' ? $this->lang->edit . '“' . $lane->name . '”' . $this->lang->kanbanlane->common : zget($this->lang->kanban->laneTypeList, $lane->type) . $this->lang->colon . $this->lang->kanban->setLane;
        $this->view->lane  = $lane;
        $this->view->from  = $from;

        $this->display();
    }

    /**
     * Edit lane's color
     *
     * @param  int    $laneID
     * @param  int    $executionID
     * @param  string $from
     * @access public
     * @return void
     */
    public function editLaneColor($laneID, $executionID = 0, $from = 'kanban')
    {
        if($_POST)
        {
            $this->kanban->setLane($laneID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('kanbanlane', $laneID, 'Edited', '', $executionID);

            $lane     = $this->kanban->getLaneById($laneID);
            $region   = $this->kanban->getRegionByID($lane->region);
            $callback = $this->kanban->getKanbanCallback($region->kanban, $region->id);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $lane = $this->kanban->getLaneById($laneID);
        if(!$lane) return print(js::error($this->lang->notFound) . js::locate($this->createLink('execution', 'kanban', "executionID=$executionID")));

        $this->view->title = $from == 'kanban' ? $this->lang->edit . '“' . $lane->name . '”' . $this->lang->kanbanlane->common : zget($this->lang->kanban->laneTypeList, $lane->type) . $this->lang->colon . $this->lang->kanban->setLane;
        $this->view->lane  = $lane;
        $this->view->from  = $from;

        $this->display();
    }
    /**
     * Set lane column info.
     *
     * @param  int $columnID
     * @param  int $executionID
     * @param  string $from kanban|execution
     * @access public
     * @return void
     */
    public function setColumn($columnID, $executionID = 0, $from = 'kanban')
    {
        $column = $this->kanban->getColumnByID($columnID);

        if($_POST)
        {
            $changes = $this->kanban->updateLaneColumn($columnID, $column);
            if(dao::isError()) return $this->sendError(dao::getError());
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('kanbancolumn', $columnID, 'Edited', '', $executionID);
                $this->action->logHistory($actionID, $changes);
            }

            if($from == 'RDKanban') return $this->send(array('result' => 'success', 'closeModal' => true, 'callback' => "refreshKanban()"));

            $region   = $this->kanban->getRegionByID($column->region);
            $callback = $this->kanban->getKanbanCallback($region->kanban, $region->id);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $this->view->canEdit = $from == 'RDKanban' ? 0 : 1;
        $this->view->column  = $column;
        $this->view->title   = $column->name . $this->lang->colon . $this->lang->kanban->editColumn;
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
     * Ajax move card.
     *
     * @param  int    $cardID
     * @param  int    $fromColID
     * @param  int    $toColID
     * @param  int    $fromLaneID
     * @param  int    $toLaneID
     * @param  int    $executionID
     * @param  string $browseType
     * @param  string $groupBy
     * @param  int    $regionID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function ajaxMoveCard($cardID = 0, $fromColID = 0, $toColID = 0, $fromLaneID = 0, $toLaneID = 0, $executionID = 0, $browseType = 'all', $groupBy = '', $regionID = 0, $orderBy = '')
    {
        $fromCell = $this->dao->select('id, cards, lane')->from(TABLE_KANBANCELL)
            ->where('kanban')->eq($executionID)
            ->andWhere('`column`')->eq($fromColID)
            ->beginIF(!$groupBy or $groupBy == 'default')->andWhere('lane')->eq($fromLaneID)->fi()
            ->beginIF($groupBy and $groupBy != 'default')
            ->andWhere('type')->eq($browseType)
            ->andWhere('cards')->like("%,$cardID,%")
            ->fi()
            ->fetch();

        if($groupBy and $groupBy != 'default') $fromLaneID = $toLaneID = $fromCell->lane;

        $toCell = $this->dao->select('id, cards')->from(TABLE_KANBANCELL)
            ->where('kanban')->eq($executionID)
            ->andWhere('lane')->eq($toLaneID)
            ->andWhere('`column`')->eq($toColID)
            ->fetch();

        $fromCards = str_replace(",$cardID,", ',', $fromCell->cards);
        $fromCards = $fromCards == ',' ? '' : $fromCards;
        $toCards   = ',' . implode(',', array_unique(array_filter(explode(',', $toCell->cards)))) . ",$cardID,";

        $this->dao->update(TABLE_KANBANCELL)->set('cards')->eq($fromCards)
            ->where('kanban')->eq($executionID)
            ->andWhere('lane')->eq($fromLaneID)
            ->andWhere('`column`')->eq($fromColID)
            ->exec();

        $this->dao->update(TABLE_KANBANCELL)->set('cards')->eq($toCards)
            ->where('kanban')->eq($executionID)
            ->andWhere('lane')->eq($toLaneID)
            ->andWhere('`column`')->eq($toColID)
            ->exec();

        $toColumn = $this->kanban->getColumnByID($toColID);
        if($toColumn->laneType == 'story' and in_array($toColumn->type, array('tested', 'verified', 'released', 'closed')))
        {
            $data = new stdclass();
            $data->stage = $toColumn->type;
            if($toColumn->type == 'released')
            {
                $fromColumn = $this->kanban->getColumnByID($fromColID);
                if($fromColumn->type == 'closed') $data->status = 'active';
            }
            $this->dao->update(TABLE_STORY)->data($data)->where('id')->eq($cardID)->exec();
            $this->dao->update(TABLE_STORYSTAGE)->set('stage')->eq($toColumn->type)->where('story')->eq($cardID)->exec();
        }

        $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
        $rdSearchValue   = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
        $kanbanGroup     = $regionID == 0 ? $this->kanban->getExecutionKanban($executionID, $browseType, $groupBy, $taskSearchValue) : $this->kanban->getRDKanban($executionID, $browseType, $orderBy, $regionID, $groupBy, $rdSearchValue);
        echo json_encode($kanbanGroup);
    }

    /**
     * Ajax get contact users.
     *
     * @param  string $field
     * @param  int    $contactListID
     * @access public
     * @return string
     */
    public function ajaxGetContactUsers($field, $contactListID)
    {
        $this->loadModel('user');
        $list  = $contactListID ? $this->user->getContactListByID($contactListID) : '';
        $users = $this->user->getPairs('nodeleted|noclosed', '', $this->config->maxCount);

        if(!$contactListID or !isset($list->userList)) return print(html::select($field . '[]', $users, '', "class='form-control picker-select' multiple"));

        return print(html::select($field . '[]', $users, $list->userList, "class='form-control picker-select' multiple"));
    }

    /**
     * Ajax get kanban menu.
     *
     * @param  int    $kanbanID
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu($kanbanID, $moduleName, $methodName)
    {
        $kanbanIdList = $this->kanban->getCanViewObjects();
        $spacePairs   = $this->kanban->getSpacePairs('showClosed');

        $this->view->kanbanList = $this->dao->select('*')->from(TABLE_KANBAN)
            ->where('deleted')->eq('0')
            ->andWhere('id')->in($kanbanIdList)
            ->andWhere('space')->in(array_keys($spacePairs))
            ->fetchGroup('space');

        $this->view->kanbanID  = $kanbanID;
        $this->view->spaceList = $spacePairs;
        $this->view->module    = $moduleName;
        $this->view->method    = $methodName;
        $this->view->link      = $this->createLink('kanban', 'view', 'id={id}');
        $this->display();
    }

    /**
     * Ajax get lanes by region id.
     *
     * @param  int    $regionID
     * @param  string $type     all|story|task|bug
     * @param  string $field    otherLane|lane
     * @param  string $pageType
     * @access public
     * @return string
     */
    public function ajaxGetLanes($regionID, $type = 'all', $field = 'otherLane', $pageType = '')
    {
        $lanes = $this->kanban->getLanePairsByRegion($regionID, $type);

        if($this->viewType == 'json') return print($lanes);

        $laneList = array();
        foreach($lanes as $laneID => $laneName) $laneList[] = array('value' => $laneID, 'text' => $laneName);

        if($pageType == 'batch') return $this->send($laneList);
        return print(json_encode(array('items' => $laneList, 'name' => $field)));
    }

    /**
     * Ajax load space users.
     *
     * @param  int    $spaceID
     * @param  string $field team|whitelist|owner
     * @param  string $selectedUser
     * @param  string $space all|space
     * @access public
     * @return string
     */
    public function ajaxLoadUsers($spaceID, $field = '', $selectedUser = '', $type = 'space')
    {
        $space    = $this->kanban->getSpaceById($spaceID);
        $accounts = '';

        if(!empty($space) and $field == 'owner' and $type != 'all') $accounts = trim($space->owner) . ',' . trim($space->team);

        $users = $this->loadModel('user')->getPairs('noclosed|nodeleted', '', 0, $accounts);

        $userList = array();
        foreach($users as $account => $user) $userList[] = array('text' => $user, 'value' => $account, 'key' => $user . $account);

        return print(json_encode($userList));
    }

    /**
     * Ajax save regionID.
     *
     * @param  int|string $regionID
     * @access public
     * @return void
     */
    public function ajaxSaveRegionID($regionID)
    {
        $this->session->set('regionID', $regionID, 'kanban');
    }

    /**
     * 获取排序的列表。
     * Ajax Get sort items.
     *
     * @param  int    $objectType
     * @param  int    $objectID
     * @access public
     * @return string
     */
    public function ajaxGetSortItems($objectType, $objectID)
    {
        $itemList = array();
        if($objectType == 'region')
        {
            $region      = $this->kanban->getRegionByID($objectID);
            $regionPairs = $this->kanban->getRegionPairs($region->kanban);
            foreach($regionPairs as $regionID => $regionName) $itemList[] = array('id' => $regionID, 'text' => $regionName);
        }
        else if($objectType == 'column')
        {
            $column      = $this->kanban->getColumnByID($objectID);
            $columnPairs = $this->kanban->getColumnPairsByGroup($column->group);
            foreach($columnPairs as $columnID => $columnName) $itemList[] = array('id' => $columnID, 'text' => $columnName);
        }
        else if($objectType == 'lane')
        {
            $lane      = $this->kanban->getLaneById($objectID);
            $lanePairs = $this->kanban->getLanePairsByGroup($lane->group);
            foreach($lanePairs as $laneID => $laneName) $itemList[] = array('id' => $laneID, 'text' => $laneName);
        }
        return print(json_encode($itemList));
    }
}

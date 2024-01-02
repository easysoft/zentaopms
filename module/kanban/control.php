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
            $kanban = form::data($this->config->kanban->form->create)->get();
            $this->kanban->create($kanban);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->kanbanZen->assignCreateVars($spaceID, $type, $copyKanbanID, $extra);
    }

    /**
     * 编辑看板。
     * Edit a kanban.
     *
     * @param  int    $kanbanID
     * @access public
     * @return void
     */
    public function edit(int $kanbanID = 0)
    {
        if(!empty($_POST))
        {
            $kanban = form::data($this->config->kanban->form->edit)->get();
            $this->kanban->update($kanbanID, $kanban);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

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
    public function setting(int $kanbanID = 0)
    {
        if(!empty($_POST))
        {
            $kanban = form::data($this->config->kanban->form->setting)->get();
            $this->kanban->setting($kanbanID, $kanban);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

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
     * 激活看板。
     * Activate a kanban.
     *
     * @param  int    $kanbanID
     * @access public
     * @return void
     */
    public function activate(int $kanbanID)
    {
        if(!empty($_POST))
        {
            $kanban = form::data($this->config->kanban->form->activate)
                ->setDefault('closedBy', '')
                ->setDefault('closedDate', null)
                ->get();
            $this->kanban->activate($kanbanID, $kanban);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->kanban  = $this->kanban->getByID($kanbanID);
        $this->view->actions = $this->loadModel('action')->getList('kanban', $kanbanID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /*
     * 关闭看板。
     * Close a kanban.
     *
     * @param  int    $kanbanID
     * @access public
     * @return void
     */
    public function close(int $kanbanID)
    {
        if(!empty($_POST))
        {
            $kanban = form::data($this->config->kanban->form->close)
                ->setDefault('activatedBy', '')
                ->setDefault('activatedDate', null)
                ->get();
            $this->kanban->close($kanbanID, $kanban);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->kanban  = $this->kanban->getByID($kanbanID);
        $this->view->actions = $this->loadModel('action')->getList('kanban', $kanbanID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

     /**
      * 看板视图页面。
      * View a kanban.
      *
      * @param  int        $kanbanID
      * @param  string|int $regionID
      * @access public
      * @return void
      */
    public function view(int $kanbanID, string|int $regionID = '')
    {
        $kanban = $this->kanban->getByID($kanbanID);

        if(!$kanban)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'code' => 404, 'message' => '404 Not found'));
            return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink('kanban', 'space'))));
        }

        $kanbanIdList = $this->kanban->getCanViewObjects();
        if(!$this->app->user->admin and !in_array($kanbanID, $kanbanIdList)) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->kanban->accessDenied, 'locate' => $this->createLink('kanban', 'space'))));

        $regions = $this->kanban->getRegionPairs($kanbanID);
        if(!$regionID) $regionID = $this->session->regionID ? $this->session->regionID : 'all';
        $regionID = !isset($regions[$regionID]) ? 'all' : $regionID;
        $this->session->set('regionID', $regionID, 'kanban');

        $this->view->title       = $this->lang->kanban->view;
        $this->view->kanban      = $kanban;
        $this->view->regions     = $regions;
        $this->view->kanbanList  = $this->kanban->getKanbanData($kanbanID, $regionID == 'all' ? '' : array($regionID));
        $this->view->regionID    = $regionID;
        $this->view->pageToolbar = $this->kanban->getPageToolBar($kanban);

        $this->display();
    }

    /**
     * 删除看板。
     * Delete a kanban.
     *
     * @param  int    $kanbanID
     * @access public
     * @return void
     */
    public function delete(int $kanbanID)
    {
        $this->kanban->delete(TABLE_KANBAN, $kanbanID);
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('kanban', 'space')));
    }

    /**
     * 创建看板区域。
     * Create a region.
     *
     * @param  int    $kanbanID
     * @param  string $from kanban|execution
     * @access public
     * @return void
     */
    public function createRegion(int $kanbanID, string $from = 'kanban')
    {
        if(!empty($_POST))
        {
            $kanban       = $from == 'execution' ? $this->loadModel('execution')->getByID($kanbanID) : $this->kanban->getByID($kanbanID);
            $copyRegionID = $this->post->region == 'custom' ? 0 : $this->post->region;

            $regionID = $this->kanban->createRegion($kanban, null, (int)$copyRegionID, $from);

            $this->session->set('regionID', $regionID, 'kanban');
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $regionPairs = array();
        $regions     = $this->kanban->getRegionPairs($kanbanID, 0, $from);
        foreach($regions as $regionID => $region)
        {
            if(mb_strlen($region, 'UTF-8') > 20) $region = mb_substr($region, 0, 20, 'UTF-8') . '...';
            $regionPairs[$regionID] = $this->lang->kanban->copy . '"' . $region . '"' . $this->lang->kanban->styleCommon;
        }

        $this->view->regions = array('custom' => $this->lang->kanban->custom) + $regionPairs;
        $this->display();
    }

    /*
     * 编辑区域。
     * Edit a region
     *
     * @param  int    $regionID
     * @access public
     * @return void
     */
    public function editRegion(int $regionID = 0)
    {
        if(!empty($_POST))
        {
            $this->kanban->updateRegion($regionID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->region = $this->kanban->getRegionByID($regionID);
        $this->display();
    }

    /**
     * 排序区域。
     * Sort regions.
     *
     * @param  string $regions
     * @access public
     * @return void
     */
    public function sortRegion(string $regions = '')
    {
        if(empty($regions)) return;
        $regionIdList = explode(',', trim($regions, ','));

        $this->kanban->updateRegionSort($regionIdList);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * 排序分组。
     * Sort group.
     *
     * @param  int    $region
     * @param  string $groups
     * @access public
     * @return void
     */
    public function sortGroup(int $region, string $groups)
    {
        $groups = array_filter(explode(',', trim($groups, ',')));
        if(empty($groups)) return $this->send(array('result' => 'fail', 'message' => 'No groups to sort.'));

        $this->kanban->sortGroup($region, $groups);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success'));
    }

    /**
     * 删除区域。
     * Delete a region
     *
     * @param  int    $regionID
     * @access public
     * @return void
     */
    public function deleteRegion(int $regionID)
    {
        $this->kanban->delete(TABLE_KANBANREGION, $regionID);
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * 创建看板泳道。
     * Create a lane for a kanban.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @param  string $from kanban|execution
     * @access public
     * @return void
     */
    public function createLane(int $kanbanID, int $regionID, string $from = 'kanban')
    {
        if(!empty($_POST))
        {
            $lane = form::data($this->config->kanban->form->createLane)->add('region', $regionID)->get();
            $this->kanban->createLane($kanbanID, $regionID, $lane, 'new');

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($from == 'execution') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "refreshKanban();"));
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
     * 泳道排序。
     * Sort lanes.
     *
     * @param  int    $regionID
     * @param  string $lanes
     * @access public
     * @return void
     */
    public function sortLane(int $regionID, string $lanes = '')
    {
        if(empty($lanes)) return;
        $lanes = explode(',', trim($lanes, ','));

        $this->kanban->updateLaneSort($regionID, $lanes);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * 排序看板列。
     * Sort columns.
     *
     * @param  int    $regionID
     * @param  string $columns
     * @access public
     * @return void
     */
    public function sortColumn(int $regionID, string $columns = '')
    {
        if(empty($columns)) return;
        $columns = explode(',', trim($columns, ','));

        $this->kanban->updateColumnSort($regionID, $columns);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * 删除看板泳道。
     * Delete a lane.
     *
     * @param  int    $regionID
     * @param  int    $laneID
     * @access public
     * @return void
     */
    public function deleteLane(int $regionID, int $laneID)
    {
        $lane = $this->kanban->getLaneById($laneID);
        $this->kanban->delete(TABLE_KANBANLANE, $laneID);

        if($this->app->tab == 'execution')
        {
            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => "refreshKanban();"));
        }

        $lanes = $this->kanban->getLanePairsByGroup($lane->group);
        if($lanes)
        {
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => array('name' => 'updateKanbanRegion', 'params' => array('region' . $lane->region, array('items' => array(array('key' => 'group' . $lane->group, 'data' => array('lanes' => array(array('id' => $laneID, 'name' => $laneID, 'deleted' => true))))))))));
        }
        else
        {
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => array('name' => 'updateKanbanRegion', 'params' => array('region' . $regionID, array('items' => array(array('key' => 'group' . $lane->group, 'deleted' => true)))))));
        }
    }

    /**
     * 创建看板列。
     * Create a column for a kanban.
     *
     * @param  int    $fromColumnID
     * @param  string $position left|right
     * @access public
     * @return void
     */
    public function createColumn(int $fromColumnID, string $position = 'left')
    {
        $fromColumn = $this->kanban->getColumnByID($fromColumnID);

        if($_POST)
        {
            $order  = $position == 'left' ? $fromColumn->order : $fromColumn->order + 1;
            $column = form::data($this->config->kanban->form->createColumn)
                ->setDefault('order', $order)
                ->setDefault('region', $fromColumn->region)
                ->get();
            $this->kanban->createColumn($fromColumn->region, $column, 'kanban', 'new');
            if(dao::isError()) $this->send(array('message' => dao::getError(), 'result' => 'fail'));

            $region   = $this->kanban->getRegionByID($fromColumn->region);
            $callback = $this->kanban->getKanbanCallback($region->kanban, $region->id);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $this->view->title      = $this->lang->kanban->createColumn;
        $this->view->fromColumn = $fromColumn;
        $this->view->position   = $position;
        $this->display();
    }

    /**
     * 拆分看板列。
     * Split column.
     *
     * @param  int    $columnID
     * @access public
     * @return void
     */
    public function splitColumn(int $columnID)
    {
        if(!empty($_POST))
        {
            $columns = form::batchData($this->config->kanban->form->splitColumn)->get();
            $this->kanban->splitColumn($columnID, $columns);
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
     * 归档看板列。
     * Archive a column.
     *
     * @param  int    $columnID
     * @access public
     * @return void
     */
    public function archiveColumn(int $columnID)
    {
        $column = $this->kanban->getColumnById($columnID);

        if($column->parent) $this->kanban->updateColumnParent($column);

        $this->kanban->archiveColumn($columnID);
        if(dao::isError()) $this->send(array('message' => dao::getError(), 'result' => 'fail'));

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => array('name' => 'updateKanbanRegion', 'params' => array('region' . $column->region, array('items' => array(array('key' => 'group' . $column->group, 'data' => array('cols' => array(array('id' => $columnID, 'name' => $columnID, 'deleted' => true))))))))));
    }

    /**
     * 还原看板列。
     * Restore a column.
     *
     * @param  int    $columnID
     * @access public
     * @return void
     */
    public function restoreColumn(int $columnID)
    {
        $this->kanban->restoreColumn($columnID);
        if(dao::isError()) $this->send(array('message' => dao::getError(), 'result' => 'fail'));

        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * 查看归档看板列。
     * View archived columns.
     *
     * @param  int    $regionID
     * @access public
     * @return void
     */
    public function viewArchivedColumn(int $regionID)
    {
        $region = $this->kanban->getRegionByID($regionID);

        $this->view->kanban  = $this->kanban->getByID($region->kanban);
        $this->view->columns = $this->kanban->getColumnsByField('region', $regionID, '1');

        $this->display();
    }

    /**
     * 删除看板列。
     * Delete a column.
     *
     * @param  int    $columnID
     * @access public
     * @return void
     */
    public function deleteColumn(int $columnID)
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
    public function createCard(int $kanbanID = 0, int $regionID = 0, int $groupID = 0, int $columnID = 0)
    {
        if($_POST)
        {
            $card = form::data($this->config->kanban->form->createCard)
                ->add('kanban', $kanbanID)
                ->add('region', $regionID)
                ->add('group', $groupID)
                ->get();
            $this->kanban->createCard($columnID, $card);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

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
     * 批量创建卡片。
     * Batch create cards.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @param  int    $groupID
     * @param  int    $columnID
     * @access public
     * @return void
     */
    public function batchCreateCard(int $kanbanID = 0, int $regionID = 0, int $groupID = 0, int $columnID = 0)
    {
        $kanban = $this->kanban->getById($kanbanID);

        if($_POST)
        {
            $cards = form::batchData($this->config->kanban->form->batchCreateCard)->get();
            $this->kanban->batchCreateCard($kanbanID, $regionID, $groupID, $columnID, $cards);
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
     * 编辑看板卡片。
     * Edit a card.
     *
     * @param  int    $cardID
     * @access public
     * @return void
     */
    public function editCard(int $cardID)
    {
        if(!empty($_POST))
        {
            $card = form::data($this->config->kanban->form->editCard)->get();
            $this->kanban->updateCard($cardID, $card);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $card     = $this->kanban->getCardByID($cardID);
            $callback = $this->kanban->getKanbanCallback($card->kanban, $card->region);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $card        = $this->kanban->getCardByID($cardID);
        $kanban      = $this->kanban->getById($card->kanban);
        $kanbanUsers = $card->kanban == 0 ? ',' : trim($kanban->owner) . ',' . trim($kanban->team);
        $kanbanUsers = $this->loadModel('user')->getPairs('noclosed|nodeleted', '', 0, $kanbanUsers);
        $users       = $this->user->getPairs('noclosed|nodeleted');

        $this->view->card        = $card;
        $this->view->users       = $users;
        $this->view->kanbanUsers = $kanbanUsers;
        $this->view->kanban      = $kanban;

        $this->display();
    }

    /**
     * 完成卡片。
     * Finish a card.
     *
     * @param  int    $cardID
     * @access public
     * @return void
     */
    public function finishCard(int $cardID)
    {
        $oldCard = $this->kanban->getCardByID($cardID);
        $this->dao->update(TABLE_KANBANCARD)->set('progress')->eq(100)->set('status')->eq('done')->where('id')->eq($cardID)->exec();
        $card = $this->kanban->getCardByID($cardID);

        $changes = common::createChanges($oldCard, $card);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $actionID = $this->loadModel('action')->create('kanbanCard', $cardID, 'finished');
        $this->action->logHistory($actionID, $changes);

        $callback = $this->kanban->getKanbanCallback($card->kanban, $card->region);
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
    }

    /**
     * 激活卡片。
     * Activate a card.
     *
     * @param  int    $cardID
     * @access public
     * @return void
     */
    public function activateCard(int $cardID)
    {
        $card = $this->kanban->getCardByID($cardID);

        if(!empty($_POST))
        {
            $this->kanban->activateCard($cardID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $callback = $this->kanban->getKanbanCallback($card->kanban, $card->region);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $this->view->card    = $card;
        $this->view->actions = $this->loadModel('action')->getList('kanbancard', $cardID);
        $this->view->users   = $this->loadModel('user')->getPairs('noclosed|nodeleted');

        $this->display();
    }

    /**
     * 查看看板卡片详情。
     * View a card.
     *
     * @param  int    $cardID
     * @access public
     * @return void
     */
    public function viewCard(int $cardID)
    {
        $card   = $this->kanban->getCardByID($cardID);
        $kanban = $this->kanban->getByID($card->kanban);
        $space  = $this->kanban->getSpaceById($kanban->space);

        $this->view->card        = $card;
        $this->view->actions     = $this->loadModel('action')->getList('kanbancard', $cardID);
        $this->view->users       = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->space       = $space;
        $this->view->kanban      = $kanban;
        $this->view->usersAvatar = $this->user->getAvatarPairs();

        $this->display();
    }

    /**
     * 看板移动卡片。
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
    public function moveCard(int $cardID, int $fromColID, int $toColID, int $fromLaneID, int $toLaneID, int $kanbanID = 0)
    {
        $this->kanban->moveCard($cardID, $fromColID, $toColID, $fromLaneID, $toLaneID, $kanbanID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->loadModel('action')->create('kanbanCard', $cardID, 'moved');

        $card     = $this->kanban->getCardByID($cardID);
        $callback = $this->kanban->getKanbanCallback($card->kanban, $card->region);
        return $this->send(array('result' => 'success', 'callback' => $callback));
    }

    /**
     * 转入其它看板的卡片。
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
    public function importCard(int $kanbanID = 0, int $regionID = 0, int $groupID = 0, int $columnID = 0, int $selectedKanbanID = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
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
                $this->loadModel('action')->create('kanbancard', (int)$cardID, 'importedcard', '', $cards2Imported[$cardID]->kanban);
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
     * 导入产品计划。
     * Import plan.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @param  int    $groupID
     * @param  int    $columnID
     * @param  int    $selectedProductID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function importPlan(int $kanbanID = 0, int $regionID = 0, int $groupID = 0, int $columnID = 0, int $selectedProductID = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
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

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $productPairs      = $this->kanban->getCanImportProducts('productplan');
        $selectedProductID = empty($selectedProductID) ? key($productPairs) : $selectedProductID;

        $this->view->products          = $productPairs;
        $this->view->selectedProductID = $selectedProductID;
        $this->view->lanePairs         = $this->kanban->getLanePairsByGroup($groupID);
        $this->view->plans2Imported    = $this->loadModel('productplan')->getList($selectedProductID, '0', 'all', $pager, 'begin_desc', 'skipparent|noproduct');
        $this->view->pager             = $pager;
        $this->view->kanbanID          = $kanbanID;
        $this->view->regionID          = $regionID;
        $this->view->groupID           = $groupID;
        $this->view->columnID          = $columnID;

        $this->display();
    }

    /**
     * 导入产品发布。
     * Import release.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @param  int    $groupID
     * @param  int    $columnID
     * @param  int    $selectedProductID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function importRelease(int $kanbanID = 0, int $regionID = 0, int $groupID = 0, int $columnID = 0, int $selectedProductID = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
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

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->products          = $this->kanban->getCanImportProducts('release');
        $this->view->selectedProductID = $selectedProductID;
        $this->view->lanePairs         = $this->kanban->getLanePairsByGroup($groupID);
        $this->view->releases2Imported = $this->loadModel('release')->getList($selectedProductID, 'all', 'all', 't1.date_desc', '', $pager);
        $this->view->pager             = $pager;
        $this->view->kanbanID          = $kanbanID;
        $this->view->regionID          = $regionID;
        $this->view->groupID           = $groupID;
        $this->view->columnID          = $columnID;

        $this->display();
    }

    /**
     * 导入项目版本。
     * Import build.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @param  int    $groupID
     * @param  int    $columnID
     * @param  int    $selectedProjectID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function importBuild(int $kanbanID = 0, int $regionID = 0, int $groupID = 0, int $columnID = 0, int $selectedProjectID = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
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

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $builds2Imported = array();
        $projects        = array($this->lang->kanban->allProjects);
        $projects       += $this->loadModel('project')->getPairsByProgram(0, 'all', false, 'order_asc', 'kanban');
        $builds2Imported = $this->loadModel('build')->getProjectBuilds($selectedProjectID, 'all', '0', 't1.date_desc,t1.id_desc', $pager);

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
     * 导入执行。
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
    public function importExecution(int $kanbanID = 0, int $regionID = 0, int $groupID = 0, int $columnID = 0, int $selectedProjectID = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
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

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->projects            = array($this->lang->kanban->allProjects) + $this->loadModel('project')->getPairsByProgram(0, 'all', false, '', '', '', 'multiple');
        $this->view->selectedProjectID   = $selectedProjectID;
        $this->view->lanePairs           = $this->kanban->getLanePairsByGroup($groupID);
        $this->view->executions2Imported = $this->loadModel('execution')->getStatData($selectedProjectID, 'undone', 0, 0, false, 'hasParentName', 'id_asc', $pager);
        $this->view->users               = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->pager               = $pager;
        $this->view->kanbanID            = $kanbanID;
        $this->view->regionID            = $regionID;
        $this->view->groupID             = $groupID;
        $this->view->columnID            = $columnID;

        $this->display();
    }

    /**
     * 导入工单。
     * Import ticket.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @param  int    $groupID
     * @param  int    $columnID
     * @param  int    $selectedProductID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function importTicket(int $kanbanID = 0, int $regionID = 0, int $groupID = 0, int $columnID = 0, int $selectedProductID = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
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

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->products          = array('all' => $this->lang->kanban->allProducts) + $this->loadModel('feedback')->getGrantProducts();
        $this->view->selectedProductID = $selectedProductID;
        $this->view->lanePairs         = $this->kanban->getLanePairsByGroup($groupID);
        $this->view->tickets2Imported  = $this->loadModel('ticket')->getTicketByProduct($selectedProductID, 'noclosed|nodone', 'id_desc', $pager);
        $this->view->users             = $this->loadModel('user')->getPairs('noletter');
        $this->view->pager             = $pager;
        $this->view->kanbanID          = $kanbanID;
        $this->view->regionID          = $regionID;
        $this->view->groupID           = $groupID;
        $this->view->columnID          = $columnID;

        $this->display();
    }

    /**
     * 设置卡片颜色。
     * Set a card's color.
     *
     * @param  int    $cardID
     * @param  string $color
     * @access public
     * @return string
     */
    public function setCardColor(int $cardID, string $color)
    {
        $this->kanban->updateCardColor($cardID, $color);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $card     = $this->kanban->getCardByID($cardID);
        $callback = $this->kanban->getKanbanCallback($card->kanban, $card->region);
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
    }

    /**
     * 排序卡片。
     * Sort cards.
     *
     * @param  int    $kanbanID
     * @param  int    $laneID
     * @param  int    $columnID
     * @param  string $cards
     * @access public
     * @return void
     */
    public function sortCard(int $kanbanID, int $laneID, int $columnID, string $cards = '')
    {
        if(empty($cards)) return;

        $this->dao->update(TABLE_KANBANCELL)->set('cards')->eq(",$cards,")->where('kanban')->eq($kanbanID)->andWhere('lane')->eq($laneID)->andWhere('`column`')->eq($columnID)->exec();

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $lane     = $this->kanban->getLaneById($laneID);
        $callback = $this->kanban->getKanbanCallback($kanbanID, $lane->region);
        return $this->send(array('result' => 'success', 'callback' => $callback));
    }

    /**
     * 归档看板卡片。
     * Archive a card.
     *
     * @param  int    $cardID
     * @access public
     * @return void
     */
    public function archiveCard(int $cardID)
    {
        $this->kanban->archiveCard($cardID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $card = $this->kanban->getCardByID($cardID);
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => array('name' => 'updateKanbanRegion', 'params' => array('region' . $card->region, array('items' => array(array('key' => 'group' . $card->group, 'data' => array('items' => array(array('id' => $cardID, 'name' => $cardID, 'deleted' => true))))))))));
    }

    /**
     * 查看归档的卡片。
     * View archived cards.
     *
     * @param  int    $regionID
     * @access public
     * @return void
     */
    public function viewArchivedCard(int $regionID)
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
     * 还原看板卡片。
     * Restore a card.
     *
     * @param  int    $cardID
     * @access public
     * @return void
     */
    public function restoreCard(int $cardID)
    {
        $this->kanban->restoreCard($cardID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * 删除看板卡片。
     * Delete a card.
     *
     * @param  int    $cardID
     * @access public
     * @return void
     */
    public function deleteCard(int $cardID)
    {
        $card = $this->kanban->getCardByID($cardID);
        if($card->fromType == '') $this->kanban->delete(TABLE_KANBANCARD, $cardID);
        if($card->fromType != '') $this->dao->delete()->from(TABLE_KANBANCARD)->where('id')->eq($cardID)->exec();

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => array('name' => 'updateKanbanRegion', 'params' => array('region' . $card->region, array('items' => array(array('key' => 'group' . $card->group, 'data' => array('items' => array(array('id' => $cardID, 'name' => $cardID, 'deleted' => true))))))))));
    }

    /**
     * 设置看板在制品限制。
     * Set WIP.
     *
     * @param  int    $columnID
     * @param  int    $executionID
     * @param  string $from kanban|execution
     * @access public
     * @return void
     */
    public function setWIP(int $columnID, int $executionID = 0, string $from = 'kanban')
    {
        $this->app->loadLang('story');
        $column = $this->kanban->getColumnById($columnID);
        if(!$column) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink('execution', 'kanban', "executionID=$executionID"))));

        if($_POST)
        {
            $WIP = form::data($this->config->kanban->form->setWIP)->get();
            $this->kanban->setWIP($columnID, $WIP);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('kanbancolumn', $columnID, 'Edited', '', $executionID);

            if($from != 'kanban') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "refreshKanban()"));

            $region   = $this->kanban->getRegionByID($column->region);
            $callback = $this->kanban->getKanbanCallback($region->kanban, $region->id);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $this->view->column = $column;
        $this->view->from   = $from;
        if($from != 'kanban') $this->view->status = zget($this->config->kanban->{$column->laneType . 'ColumnStatusList'}, $column->type);
        $this->display();
    }

    /**
     * 设置泳道。
     * Set lane info.
     *
     * @param  int    $laneID
     * @param  int    $executionID
     * @param  string $from kanban|execution
     * @access public
     * @return void
     */
    public function setLane(int $laneID, int $executionID = 0, string $from = 'kanban')
    {
        if($_POST)
        {
            $lane = form::data($this->config->kanban->form->setLane)->get();
            $this->kanban->setLane($laneID, $lane);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('kanbanlane', $laneID, 'Edited', '', $executionID);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $lane = $this->kanban->getLaneById($laneID);
        if(!$lane) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink('execution', 'kanban', "executionID=$executionID"))));

        $this->view->lane  = $lane;
        $this->view->from  = $from;

        $this->display();
    }

    /**
     * 编辑泳道名称。
     * Edit lane's name
     *
     * @param  int    $laneID
     * @param  int    $executionID
     * @param  string $from
     * @access public
     * @return void
     */
    public function editLaneName(int $laneID, int $executionID = 0, string $from = 'kanban')
    {
        if($_POST)
        {
            $lane = form::data($this->config->kanban->form->setLane)->remove('color')->get();
            $this->kanban->setLane($laneID, $lane);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('kanbanlane', $laneID, 'Edited', '', $executionID);

            $lane     = $this->kanban->getLaneById($laneID);
            $region   = $this->kanban->getRegionByID($lane->region);
            $callback = $this->kanban->getKanbanCallback($region->kanban, $region->id);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $lane = $this->kanban->getLaneById($laneID);
        if(!$lane) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink('execution', 'kanban', "executionID=$executionID"))));

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
    public function editLaneColor(int $laneID, int $executionID = 0, string $from = 'kanban')
    {
        if($_POST)
        {
            $lane = form::data($this->config->kanban->form->setLane)->remove('name')->get();
            $this->kanban->setLane($laneID, $lane);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('kanbanlane', $laneID, 'Edited', '', $executionID);

            $lane     = $this->kanban->getLaneById($laneID);
            $region   = $this->kanban->getRegionByID($lane->region);
            $callback = $this->kanban->getKanbanCallback($region->kanban, $region->id);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $lane = $this->kanban->getLaneById($laneID);
        if(!$lane) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink('execution', 'kanban', "executionID=$executionID"))));

        $this->view->lane  = $lane;
        $this->view->from  = $from;

        $this->display();
    }

    /**
     * 编辑看板列。
     * Set lane column info.
     *
     * @param  int $columnID
     * @param  int $executionID
     * @param  string $from kanban|execution
     * @access public
     * @return void
     */
    public function setColumn(int $columnID, int $executionID = 0, string $from = 'kanban')
    {
        $column = $this->kanban->getColumnByID($columnID);
        if($_POST)
        {
            $formData = form::data($this->config->kanban->form->setColumn)->get();
            $changes  = $this->kanban->updateColumn($columnID, $formData);
            if(dao::isError()) return $this->sendError(dao::getError());

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('kanbancolumn', $columnID, 'edited', '', $executionID);
                $this->action->logHistory($actionID, $changes);
            }

            if($from == 'RDKanban') return $this->send(array('result' => 'success', 'closeModal' => true, 'callback' => "refreshKanban()"));

            $region   = $this->kanban->getRegionByID($column->region);
            $callback = $this->kanban->getKanbanCallback($region->kanban, $region->id);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $this->view->canEdit = $from == 'RDKanban' ? 0 : 1;
        $this->view->column  = $column;
        $this->display();
    }

    /**
     * 拖动看板卡片。
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
    public function ajaxMoveCard(int $cardID = 0, int $fromColID = 0, int $toColID = 0, int $fromLaneID = 0, int $toLaneID = 0, int $executionID = 0, string $browseType = 'all', string $groupBy = '', int $regionID = 0, string $orderBy = '')
    {
        if($groupBy and $groupBy != 'default') $fromLaneID = $toLaneID = $fromCell->lane;

        $fromCell = $this->kanban->getExecutionFromCell($cardID, $executionID, $fromColID, $fromLaneID, $groupBy, $browseType);
        $toCell   = $this->kanban->getExecutionToCell($executionID, $toColID, $toLaneID);

        $fromCards = str_replace(",$cardID,", ',', $fromCell->cards);
        $fromCards = $fromCards == ',' ? '' : $fromCards;
        $toCards   = ',' . implode(',', array_unique(array_filter(explode(',', $toCell->cards)))) . ",$cardID,";

        $this->kanban->updateExecutionCell($executionID, $fromColID, $fromLaneID, $fromCards);
        $this->kanban->updateExecutionCell($executionID, $toColID, $toLaneID, $toCards);

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
     * 获取看板左上角下拉菜单。
     * Ajax get kanban menu.
     *
     * @param  int    $kanbanID
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu(int $kanbanID, string $moduleName, string $methodName)
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
     * 获取区域中的泳道。
     * Ajax get lanes by region id.
     *
     * @param  int    $regionID
     * @param  string $type     all|story|task|bug
     * @param  string $field    otherLane|lane
     * @param  string $pageType
     * @access public
     * @return string
     */
    public function ajaxGetLanes(int $regionID, string $type = 'all', string $field = 'otherLane', string $pageType = '')
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
     * @param  string $space all|space
     * @access public
     * @return string
     */
    public function ajaxLoadUsers(int $spaceID, string $field = '', string $type = 'space')
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
     * 保存当前区域到Session。
     * Ajax save regionID.
     *
     * @param  int    $regionID
     * @access public
     * @return void
     */
    public function ajaxSaveRegionID(int $regionID)
    {
        $this->session->set('regionID', $regionID, 'kanban');
    }

    /**
     * 获取排序的列表。
     * Ajax Get sort items.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function ajaxGetSortItems(string $objectType, int $objectID)
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
            $columnPairs = $this->kanban->getColumnPairsByGroup($column->group, $column->parent);
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

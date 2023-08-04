<?php
/**
 * The control file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
     * @param  string $browseType involved|cooperation|public|private
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function space($browseType = 'involved', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $this->session->set('regionID', 'all', 'kanban');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title         = $this->lang->kanbanspace->common;
        $this->view->spaceList     = $this->kanban->getSpaceList($browseType, $pager, $this->cookie->showClosed);
        $this->view->unclosedSpace = $this->kanban->getCanViewObjects('kanbanspace', 'noclosed');
        $this->view->browseType    = $browseType;
        $this->view->pager         = $pager;
        $this->view->users         = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->userIdPairs   = $this->user->getPairs('noletter|nodeleted|showid');
        $this->view->usersAvatar   = $this->user->getAvatarPairs();

        $this->display();
    }

    /**
     * Create a space.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function createSpace($type = 'private')
    {
        if(!empty($_POST))
        {
            $spaceID = $this->kanban->createSpace($type);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('kanbanSpace', $spaceID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }

        unset($this->lang->kanban->featureBar['space']['involved']);

        $this->view->users    = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->type     = $type;
        $this->view->typeList = $this->lang->kanban->featureBar['space'];

        $this->display();
    }

    /**
     * Edit a space.
     *
     * @param  int    $spaceID
     * @access public
     * @return void
     */
    public function editSpace($spaceID, $type = '')
    {
        $this->loadModel('action');
        if(!empty($_POST))
        {
            $changes = $this->kanban->updateSpace($spaceID, $type);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->action->create('kanbanSpace', $spaceID, 'edited');
            $this->action->logHistory($actionID, $changes);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }

        $space = $this->kanban->getSpaceById($spaceID);

        $typeList = $this->lang->kanbanspace->typeList;
        if($space->type == 'cooperation' or $space->type == 'public') unset($typeList['private']);

        if($space->type == 'private' and ($type == 'cooperation' or $type == 'public'))
        {
            $team = $space->whitelist;
        }
        else
        {
            $team = $space->team;
        }

        $this->view->spaceID     = $spaceID;
        $this->view->space       = $space;
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed');
        $this->view->typeList    = $typeList;
        $this->view->type        = $type;
        $this->view->team        = $team;
        $this->view->defaultType = $type == '' ? $space->type : $type;

        $this->display();
    }

    /**
     * Activate a space.
     *
     * @param  int    $spaceID
     * @access public
     * @return array
     */
    public function activateSpace($spaceID)
    {
        $this->loadModel('action');

        if(!empty($_POST))
        {
            $changes = $this->kanban->activateSpace($spaceID);

            if(dao::isError()) return print(js::error(dao::getError()));

            $actionID = $this->action->create('kanbanSpace', $spaceID, 'activated', $this->post->comment);
            $this->action->logHistory($actionID, $changes);

            return print(js::reload('parent.parent'));
        }

        $this->view->space   = $this->kanban->getSpaceById($spaceID);
        $this->view->actions = $this->action->getList('kanbanSpace', $spaceID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /*
     * Close a space.
     *
     * @param  int    $spaceID
     * @access public
     * @return void
     */
    public function closeSpace($spaceID)
    {
        $this->loadModel('action');

        if(!empty($_POST))
        {
            $changes = $this->kanban->closeSpace($spaceID);

            if(dao::isError()) return print(js::error(dao::getError()));

            $actionID = $this->action->create('kanbanSpace', $spaceID, 'closed', $this->post->comment);
            $this->action->logHistory($actionID, $changes);

            return print(js::reload('parent.parent'));
        }

        $this->view->space   = $this->kanban->getSpaceById($spaceID);
        $this->view->actions = $this->action->getList('kanbanSpace', $spaceID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * Delete a space.
     *
     * @param  int    $spaceID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteSpace($spaceID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->kanban->confirmDeleteSpace, $this->createLink('kanban', 'deleteSpace', "spaceID=$spaceID&confirm=yes")));
        }
        else
        {
            $this->kanban->delete(TABLE_KANBANSPACE, $spaceID);
            return print(js::reload('parent'));
        }
    }

    /**
     * Create a kanban.
     *
     * @param  int    $spaceID
     * @param  string $type
     * @param  int    $copyKanbanID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function create($spaceID = 0, $type = 'private', $copyKanbanID = 0, $extra = '')
    {
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if(!empty($_POST))
        {
            $kanbanID = $this->kanban->create();

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('kanban', $kanbanID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }

        $enableImport  = 'on';
        $importObjects = array_keys($this->lang->kanban->importObjectList);
        if($copyKanbanID)
        {
            $copyKanban    = $this->kanban->getByID($copyKanbanID);
            $enableImport  = empty($copyKanban->object) ? 'off' : 'on';
            $importObjects = empty($copyKanban->object) ? array() : explode(',', $copyKanban->object);
            $spaceID       = $copyKanban->space;
        }

        unset($this->lang->kanban->featureBar['space']['involved']);

        $space      = $this->kanban->getSpaceById($spaceID);
        $spaceUsers = $spaceID == 0 ? ',' : trim($space->owner) . ',' . trim($space->team);
        $spacePairs = array(0 => '') + $this->kanban->getSpacePairs($type);
        $users      = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $ownerPairs = (isset($spacePairs[$spaceID])) ? $this->user->getPairs('noclosed|nodeleted', '', 0, $spaceUsers) : $users;

        $this->view->users         = $users;
        $this->view->ownerPairs    = $ownerPairs;
        $this->view->spaceID       = $spaceID;
        $this->view->spacePairs    = $spacePairs;
        $this->view->type          = $type;
        $this->view->typeList      = $this->lang->kanban->featureBar['space'];
        $this->view->kanbans       = array('' => '') + $this->kanban->getPairs();
        $this->view->copyKanbanID  = $copyKanbanID;
        $this->view->copyKanban    = $copyKanbanID ? $copyKanban : '';
        $this->view->enableImport  = $enableImport;
        $this->view->importObjects = $importObjects;
        $this->view->copyRegion    = isset($output['copyRegion']) ? true : false;

        $this->display();
    }

    /**
     * Edit a kanban.
     *
     * @param  int    $kanbanID
     * @access public
     * @return void
     */
    public function edit($kanbanID = 0)
    {
        $this->loadModel('action');
        if(!empty($_POST))
        {
            $changes = $this->kanban->update($kanbanID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->action->create('kanban', $kanbanID, 'edited');
            $this->action->logHistory($actionID, $changes);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }

        $kanban = $this->kanban->getByID($kanbanID);

        $space      = $this->kanban->getSpaceById($kanban->space);
        $spaceUsers = trim($space->owner) . ',' . trim($space->team);
        $users      = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $ownerPairs = $this->user->getPairs('noclosed|nodeleted', '', 0, $spaceUsers);

        $this->view->users      = $users;
        $this->view->ownerPairs = $ownerPairs;
        $this->view->spacePairs = array(0 => '') + array($kanban->space => $space->name) + $this->kanban->getSpacePairs($space->type);
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

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
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

            if(dao::isError()) return print(js::error(dao::getError()));

            $actionID = $this->action->create('kanban', $kanbanID, 'activated', $this->post->comment);
            $this->action->logHistory($actionID, $changes);

            return print(js::reload('parent.parent'));
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

            if(dao::isError()) return print(js::error(dao::getError()));

            $actionID = $this->action->create('kanban', $kanbanID, 'closed', $this->post->comment);
            $this->action->logHistory($actionID, $changes);

            return print(js::reload('parent.parent'));
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
        $kanban   = $this->kanban->getByID($kanbanID);
        $users    = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $regionID = $this->session->regionID ? $this->session->regionID : 'all';

        if(!$kanban)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'code' => 404, 'message' => '404 Not found'));
            return print(js::error($this->lang->notFound) . js::locate($this->createLink('kanban', 'space')));
        }

        $kanbanIdList = $this->kanban->getCanViewObjects();
        if(!$this->app->user->admin and !in_array($kanbanID, $kanbanIdList)) return print(js::error($this->lang->kanban->accessDenied) . js::locate('back'));

        $space = $this->kanban->getSpaceByID($kanban->space);

        $this->kanban->setSwitcher($kanban);
        $this->kanban->setHeaderActions($kanban);

        $userList    = array();
        $avatarPairs = $this->dao->select('account, avatar')->from(TABLE_USER)->where('deleted')->eq(0)->fetchPairs();
        foreach($avatarPairs as $account => $avatar)
        {
            if(!$avatar) continue;
            $userList[$account]['avatar'] = $avatar;
        }

        $regions = $this->kanban->getKanbanData($kanbanID);
        if(!isset($regions[$regionID])) $this->session->set('regionID', 'all', 'kanban');

        $this->view->users    = $users;
        $this->view->title    = $this->lang->kanban->view;
        $this->view->userList = $userList;
        $this->view->kanban   = $kanban;
        $this->view->regions  = $regions;
        $this->view->regionID = isset($regions[$regionID]) ? $regionID : 'all';

        $this->display();
    }

    /**
     * Delete a kanban.
     *
     * @param  int    $kanbanID
     * @param  string $confirm
     * @param  string $browseType involved|cooperation|public|private
     * @access public
     * @return void
     */
    public function delete($kanbanID, $confirm = 'no', $browseType = 'involved')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->kanban->confirmDeleteKanban, $this->createLink('kanban', 'delete', "kanbanID=$kanbanID&confirm=yes&browseType=$browseType")));
        }
        else
        {
            $this->kanban->delete(TABLE_KANBAN, $kanbanID);
            return print(js::locate($this->createLink('kanban', 'space', "browseType=$browseType"), 'parent'));
        }
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
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
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

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => 1, 'callback' => array('target' => 'parent', 'name' => 'updateRegionName', 'params' => array($regionID, $this->post->name))));
        }

        $this->view->region  = $this->kanban->getRegionByID($regionID);

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
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
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
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteRegion($regionID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->kanbanregion->confirmDelete, $this->createLink('kanban', 'deleteRegion', "regionID=$regionID&confirm=yes")));
        }
        else
        {
            $this->kanban->delete(TABLE_KANBANREGION, $regionID);
            return print(js::reload('parent'));
        }
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
            $laneID = $this->kanban->createLane($kanbanID, $regionID, $lane = null);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('kanbanLane', $laneID, 'created');

            if($from == 'execution')
            {
                if(dao::isError()) return $this->sendError(dao::getError());

                $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                $kanbanData   = $this->loadModel('kanban')->getRDKanban($kanbanID, $execLaneType, 'id_desc', $regionID, $execGroupBy);
                $kanbanData   = json_encode($kanbanData);
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "parent.updateKanban($kanbanData, $regionID)"));
            }

            $kanbanGroup = $this->kanban->getKanbanData($kanbanID, $regionID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => 1, 'callback' => array('target' => 'parent', 'name' => 'updateRegion', 'params' => array($regionID, $kanbanGroup))));
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
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
    }

    /**
     * Sort columns.
     *
     * @param  int    $regionID
     * @param  int    $kanbanID
     * @param  string $columns
     * @access public
     * @return array|string
     */
    public function sortColumn($regionID, $kanbanID, $columns = '')
    {
        if(empty($columns)) return;
        $columns =  explode(',', trim($columns, ','));

        $order = 1;
        foreach($columns as $columnID) $this->dao->update(TABLE_KANBANCOLUMN)->set('`order`')->eq($order++)->where('id')->eq($columnID)->andWhere('region')->eq($regionID)->exec();

        $kanbanGroup = $this->kanban->getKanbanData($kanbanID);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return print(json_encode($kanbanGroup));
    }

    /**
     * Delete a lane.
     *
     * @param  int    $regionID
     * @param  int    $kanbanID
     * @param  int    $laneID
     * @param  string $confirm no|yes
     * @access public
     * @return void
     */
    public function deleteLane($regionID, $kanbanID, $laneID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            $laneType   = $this->kanban->getLaneById($laneID)->type;
            $confirmTip = in_array($laneType, array('story', 'task', 'bug')) ? sprintf($this->lang->kanbanlane->confirmDeleteTip, $this->lang->{$laneType}->common) : $this->lang->kanbanlane->confirmDelete;

            return print(js::confirm($confirmTip, $this->createLink('kanban', 'deleteLane', "regionID=$regionID&kanbanID=$kanbanID&laneID=$laneID&confirm=yes"), ''));

        }
        else
        {
            $this->kanban->delete(TABLE_KANBANLANE, $laneID);

            if($this->app->tab == 'execution')
            {
                if(dao::isError()) return $this->sendError(dao::getError());

                $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                $kanbanData   = $this->loadModel('kanban')->getRDKanban($kanbanID, $execLaneType, 'id_desc', $regionID, $execGroupBy);
                $kanbanData   = json_encode($kanbanData);
                return print("<script>parent.updateKanban($kanbanData, $regionID)</script>");
            }

            $kanbanGroup = $this->kanban->getKanbanData($kanbanID, $regionID);
            $kanbanGroup = json_encode($kanbanGroup);
            return print("<script>parent.updateRegion($regionID, $kanbanGroup)</script>");
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

            $region      = $this->kanban->getRegionByID($column->region);
            $kanbanGroup = $this->kanban->getKanbanData($region->kanban, $region->id);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => array('target' => 'parent', 'name' => 'updateRegion', 'params' => array($column->region, $kanbanGroup))));
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

            $column      = $this->kanban->getColumnById($columnID);
            $region      = $this->kanban->getRegionByID($column->region);
            $kanbanGroup = $this->kanban->getKanbanData($region->kanban, $region->id);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => array('target' => 'parent', 'name' => 'updateRegion', 'params' => array($column->region, $kanbanGroup))));
        }

        $this->display();
    }

    /**
     * Archive a column.
     *
     * @param  int    $columnID
     * @param  string $confirm no|yes
     * @access public
     * @return void
     */
    public function archiveColumn($columnID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->kanbancolumn->confirmArchive, $this->createLink('kanban', 'archiveColumn', "columnID=$columnID&confirm=yes")));
        }
        else
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
            if(dao::isError()) return print(js::error(dao::getError()));

            $this->loadModel('action')->create('kanbancolumn', $columnID, 'archived');

            $region      = $this->kanban->getRegionByID($column->region);
            $kanbanGroup = $this->kanban->getKanbanData($region->kanban, $region->id);
            $kanbanGroup = json_encode($kanbanGroup);
            return print("<script>parent.updateRegion({$column->region}, $kanbanGroup)</script>");
        }
    }

    /**
     * Restore a column.
     *
     * @param  int    $columnID
     * @param  string $confirm no|yes
     * @access public
     * @return void
     */
    public function restoreColumn($columnID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->kanbancolumn->confirmRestore, $this->createLink('kanban', 'restoreColumn', "columnID=$columnID&confirm=yes"), ''));
        }
        else
        {
            $this->kanban->restoreColumn($columnID);
            if(dao::isError()) return print(js::error(dao::getError()));

            $this->loadModel('action')->create('kanbancolumn', $columnID, 'restore');
            return print(js::reload('parent'));
        }
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
        $columns     = $this->kanban->getColumnsByObject('region', $regionID, '');
        $columnsData = array();
        foreach($columns as $column)
        {
            if($column->archived == 0) continue;
            if($column->parent > 0 and isset($columns[$column->parent]))
            {
                if(empty($columnsData[$column->parent])) $columnsData[$column->parent] = $columns[$column->parent];
                $columnsData[$column->parent]->child[$column->id] = $column;
            }
            elseif($column->parent <= 0)
            {
                if(empty($columnsData[$column->id])) $columnsData[$column->id] = $column;
            }
        }

        $region = $this->kanban->getRegionByID($regionID);

        $this->view->kanban  = $this->kanban->getByID($region->kanban);
        $this->view->columns = $columnsData;

        $this->display();
    }

    /**
     * Delete a column.
     *
     * @param  int    $columnID
     * @param  string $confirm no|yes
     * @access public
     * @return void
     */
    public function deleteColumn($columnID, $confirm = 'no')
    {
        $column = $this->kanban->getColumnById($columnID);
        if($confirm == 'no')
        {
            $confirmLang = $column->parent > 0 ? $this->lang->kanbancolumn->confirmDeleteChild : $this->lang->kanbancolumn->confirmDelete;
            return print(js::confirm($confirmLang, $this->createLink('kanban', 'deleteColumn', "columnID=$columnID&confirm=yes")));
        }
        else
        {
            if($column->parent > 0) $this->kanban->processCards($column);

            $this->dao->delete()->from(TABLE_KANBANCOLUMN)->where('id')->eq($columnID)->exec();

            $region      = $this->kanban->getRegionByID($column->region);
            $kanbanGroup = $this->kanban->getKanbanData($region->kanban, $region->id);
            $kanbanGroup = json_encode($kanbanGroup);
            return print("<script>parent.updateRegion({$column->region}, $kanbanGroup)</script>");
        }
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

            $kanbanGroup = $this->kanban->getKanbanData($kanbanID, $regionID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => 1, 'callback' => array('target' => 'parent', 'name' => 'updateRegion', 'params' => array($regionID, $kanbanGroup))));
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
     * @param  int    $laneID
     * @param  int    $columnID
     * @access public
     * @return void
     */
    public function batchCreateCard($kanbanID = 0, $regionID = 0, $groupID = 0, $laneID = 0, $columnID = 0)
    {
        $kanban   = $this->kanban->getById($kanbanID);
        $backLink = $this->createLink('kanban', 'view', "kanbanID=$kanbanID");
        $this->kanban->setSwitcher($kanban);

        if($_POST)
        {
            $this->kanban->batchCreateCard($kanbanID, $regionID, $groupID, $columnID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $backLink));
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

            $card        = $this->kanban->getCardByID($cardID);
            $kanbanGroup = $this->kanban->getKanbanData($card->kanban, $card->region);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => 1, 'callback' => array('target' => 'parent', 'name' => 'updateRegion', 'params' => array($card->region, $kanbanGroup))));
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

        if(isonlybody()) return print(js::reload('parent.parent'));

        $kanbanGroup = $this->kanban->getKanbanData($kanbanID);
        return print(json_encode($kanbanGroup));
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

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
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

        $kanbanGroup = $this->kanban->getKanbanData($kanbanID);
        echo json_encode($kanbanGroup);
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
        $this->app->loadClass('pager', $static = true);
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

            return print(js::locate($this->createLink('kanban', 'view', "kanbanID=$kanbanID"), 'parent.parent'));
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

            return print(js::locate($this->createLink('kanban', 'view', "kanbanID=$kanbanID"), 'parent.parent'));
        }

        $this->loadModel('product');
        $this->loadModel('productplan');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
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

            return print(js::locate($this->createLink('kanban', 'view', "kanbanID=$kanbanID"), 'parent.parent'));
        }

        $this->loadModel('product');
        $this->loadModel('release');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
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
        $this->view->releases2Imported = $this->release->getList($selectedProductID, 'all', 'all', 't1.date_desc', $pager);
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

            return print(js::locate($this->createLink('kanban', 'view', "kanbanID=$kanbanID"), 'parent.parent'));
        }

        $this->loadModel('build');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $builds2Imported = array();
        $projects        = array($this->lang->kanban->allProjects);
        $projects       += $this->loadModel('project')->getPairsByProgram('', 'all', false, 'order_asc', 'kanban');
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

            return print(js::locate($this->createLink('kanban', 'view', "kanbanID=$kanbanID"), 'parent.parent'));
        }

        $this->loadModel('project');
        $this->loadModel('execution');
        $this->loadModel('programplan');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->projects            = array($this->lang->kanban->allProjects) + $this->project->getPairsByProgram('', 'all', false, '', '', '', 'multiple');
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
        $this->app->loadClass('pager', $static = true);
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
        $kanbanGroup = $this->kanban->getKanbanData($kanbanID);
        echo json_encode($kanbanGroup);
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
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
    }

    /**
     * Archive a card.
     *
     * @param  int    $cardID
     * @param  string $confirm no|yes
     * @access public
     * @return void
     */
    public function archiveCard($cardID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->kanbancard->confirmArchive, $this->createLink('kanban', 'archiveCard', "cardID=$cardID&confirm=yes")));
        }
        else
        {
            $changes = $this->kanban->archiveCard($cardID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->loadModel('action')->create('kanbancard', $cardID, 'archived');
            $this->action->logHistory($actionID, $changes);

            if(isonlybody()) return print(js::reload('parent.parent'));
            $card        = $this->kanban->getCardByID($cardID);
            $kanbanGroup = $this->kanban->getKanbanData($card->kanban, $card->region);
            $kanbanGroupParam = json_encode($kanbanGroup);
            return print("<script>parent.updateRegion({$card->region}, $kanbanGroupParam)</script>");
        }
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
     * @param  string $confirm no|yes
     * @access public
     * @return void
     */
    public function restoreCard($cardID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            $column = $this->dao->select('t2.*')->from(TABLE_KANBANCELL)->alias('t1')
                ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.column=t2.id')
                ->where('t1.cards')->like("%,$cardID,%")
                ->andWhere('t1.type')->eq('common')
                ->fetch();
            if($column->archived or $column->deleted) return print(js::alert(sprintf($this->lang->kanbancard->confirmRestoreTip, $column->name)));

            return print(js::confirm(sprintf($this->lang->kanbancard->confirmRestore, $column->name), $this->createLink('kanban', 'restoreCard', "cardID=$cardID&confirm=yes"), ''));
        }
        else
        {
            $this->kanban->restoreCard($cardID);

            $changes = $this->kanban->restoreCard($cardID);
            if(dao::isError()) return print(js::error(dao::getError()));

            $actionID = $this->loadModel('action')->create('kanbancard', $cardID, 'restore');
            $this->action->logHistory($actionID, $changes);

            return print(js::reload('parent'));
        }
    }

	/**
	 * Delete a card.
	 *
	 * @param  int    $cardID
	 * @param  string $confirm no|yes
	 * @access public
	 * @return void
	 */
    public function deleteCard($cardID, $confirm = 'no')
    {
        $card = $this->kanban->getCardByID($cardID);
        if($confirm == 'no')
        {
            $confirmTip = $card->fromType == '' ? $this->lang->kanbancard->confirmDelete : $this->lang->kanbancard->confirmRemove;
            return print(js::confirm($confirmTip, $this->createLink('kanban', 'deleteCard', "cardID=$cardID&confirm=yes")));
        }
        else
        {
            if($card->fromType == '') $this->kanban->delete(TABLE_KANBANCARD, $cardID);
            if($card->fromType != '') $this->dao->delete()->from(TABLE_KANBANCARD)->where('id')->eq($cardID)->exec();

            if(isonlybody()) return print(js::reload('parent.parent'));

            $kanbanGroup      = $this->kanban->getKanbanData($card->kanban, $card->region);
            $kanbanGroupParam = json_encode($kanbanGroup);
            if($card->archived) return print(js::reload(parent));
            return print("<script>parent.updateRegion({$card->region}, $kanbanGroupParam)</script>");
        }
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
        $browseType  = $this->config->vision == 'lite' ? 'task' : $this->session->execLaneType;
        $groupBy     = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
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
                if(dao::isError()) return $this->sendError(dao::getError());

                $regionID     = $column->region;
                $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                $kanbanData   = $this->loadModel('kanban')->getRDKanban($executionID, $execLaneType, 'id_desc', $regionID, $execGroupBy);
                $kanbanData   = json_encode($kanbanData);
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "parent.updateKanban($kanbanData, $regionID)"));
            }
            elseif($from == 'kanban')
            {
                $region      = $this->kanban->getRegionByID($column->region);
                $kanbanGroup = $this->kanban->getKanbanData($region->kanban, $region->id);
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => array('target' => 'parent', 'name' => 'updateRegion', 'params' => array($column->region, $kanbanGroup))));
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

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => 1, 'callback' => array('target' => 'parent', 'name' => 'updateLaneName', 'params' => array($laneID, $this->post->name))));
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

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => array('target' => 'parent', 'name' => 'updateLaneColor', 'params' => array($laneID, $this->post->color))));
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


            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => array('target' => 'parent', 'name' => 'updateColumnName', 'params' => array($columnID, $this->post->name, $this->post->color))));
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
        $toCards   = ",$cardID," . ltrim($toCell->cards, ',');

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
    public function ajaxGetKanbanMenu($kanbanID, $moduleName, $methodName)
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
        $this->display();
    }

    /**
     * Ajax get lanes by region id.
     *
     * @param  int    $regionID
     * @param  string $type all|story|task|bug
     * @param  string $field otherLane|lane
     * @param  int    $i
     * @access public
     * @return string
     */
    public function ajaxGetLanes($regionID, $type = 'all', $field = 'otherLane', $i = 0)
    {
        $lanes = $this->kanban->getLanePairsByRegion($regionID, $type);

        if(empty($lanes)) return;
        if($i) return print(html::select($field . "[$i]", $lanes, '', "class='form-control'"));

        return print(html::select($field, $lanes, '', "class='form-control'"));
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

        $users     = $this->loadModel('user')->getPairs('noclosed|nodeleted', '', 0, $accounts);
        $multiple  = in_array($field, array('team', 'whitelist')) ? 'multiple' : '';
        $fieldName = $multiple ? $field . '[]' : $field;

        return print(html::select($fieldName, $users, $selectedUser, "class='form-control' $multiple"));
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
}

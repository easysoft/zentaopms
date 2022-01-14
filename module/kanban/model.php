<?php
/**
 * The model file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     kanban
 * @version     $Id: model.php 5118 2021-10-22 10:18:41Z $
 * @link        https://www.zentao.net
 */
?>
<?php
class kanbanModel extends model
{
    /**
     * Create a kanban group.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @access public
     * @return int
     */
    public function createGroup($kanbanID, $regionID)
    {
        $maxOrder = $this->dao->select('MAX(`order`) AS maxOrder')->from(TABLE_KANBANGROUP)
            ->where('region')->eq($regionID)
            ->fetch('maxOrder');

        $order = $maxOrder ? $maxOrder + 1 : 1;

        $group = new stdclass();
        $group->kanban = $kanbanID;
        $group->region = $regionID;
        $group->order  = $order;

        $this->dao->insert(TABLE_KANBANGROUP)->data($group)->autoCheck()->exec();
        if(dao::isError()) return false;

        return $this->dao->lastInsertID();
    }

    /**
     * Create a default kanban region.
     *
     * @param  object $kanban
     * @access public
     * @return int
     */
    public function createDefaultRegion($kanban)
    {
        $region = new stdclass();
        $region->name           = $this->lang->kanbanregion->default;
        $region->kanban         = $kanban->id;
        $region->space          = $kanban->space;
        $region->createdBy      = $this->app->user->account;
        $region->createdDate    = helper::today();

        return $this->createRegion($kanban, $region);
    }

    /**
     * Create a new region.
     *
     * @param  object $kanban
     * @param  object $region
     * @param  int    $copyRegionID
     * @param  string $from kanban|execution
     * @access public
     * @return int
     */
    public function createRegion($kanban, $region = null, $copyRegionID = 0, $from = 'kanban')
    {
        $account = $this->app->user->account;
        $order   = 1;

        if(!$region)
        {
            $maxOrder = $this->dao->select('MAX(`order`) AS maxOrder')->from(TABLE_KANBANREGION)
                ->where('kanban')->eq($kanban->id)
                ->fetch('maxOrder');

            $order  = $maxOrder ? $maxOrder + 1 : 1;
            $region = fixer::input('post')
                ->add('kanban', $kanban->id)
                ->add('createdBy', $account)
                ->add('createdDate', helper::now())
                ->trim('name')
                ->get();
            if($from == 'kanban') $region->space = $kanban->space;
        }

        $region->order = $order;
        $this->dao->insert(TABLE_KANBANREGION)->data($region)
            ->batchCheck($this->config->kanban->require->createregion, 'notempty')
            ->check('name', 'unique', "kanban = {$kanban->id} AND deleted = '0'")
            ->autoCheck()
            ->exec();

        $regionID = $this->dao->lastInsertID();
        if(dao::isError()) return false;

        $this->loadModel('action')->create('kanbanRegion', $regionID, 'Created');

        if($copyRegionID)
        {
            /* Gets the groups, lanes and columns of the replication region. */
            $copyGroups      = $this->getGroupGroupByRegions($copyRegionID);
            $copyLaneGroup   = $this->getLaneGroupByRegions($copyRegionID);
            $copyColumnGroup = $this->getColumnGroupByRegions($copyRegionID, 'id_asc');

            /* Create groups, lanes, and columns. */
            foreach($copyGroups[$copyRegionID] as $copyGroupID => $copyGroup)
            {
                $newGroupID = $this->createGroup($kanban->id, $regionID);
                if(dao::isError()) return false;

                $copyLanes     = isset($copyLaneGroup[$copyGroupID]) ? $copyLaneGroup[$copyGroupID] : array();
                $copyColumns   = isset($copyColumnGroup[$copyGroupID]) ? $copyColumnGroup[$copyGroupID] : array();
                $parentColumns = array();
                foreach($copyLanes as $copyLane)
                {
                    unset($copyLane->id);
                    unset($copyLane->actions);
                    $copyLane->region         = $regionID;
                    $copyLane->group          = $newGroupID;
                    $copyLane->lastEditedTime = helper::now();
                    $this->createLane($kanban->id, $regionID, $copyLane);
                    if(dao::isError()) return false;
                }

                foreach($copyColumns as $copyColumn)
                {
                    $copyColumnID = $copyColumn->id;
                    unset($copyColumn->id);
                    unset($copyColumn->actions);
                    unset($copyColumn->asParent);
                    unset($copyColumn->parentType);

                    $copyColumn->region = $regionID;
                    $copyColumn->group  = $newGroupID;

                    if($copyColumn->parent > 0 and isset($parentColumns[$copyColumn->parent]))
                    {
                        $copyColumn->parent = $parentColumns[$copyColumn->parent];
                    }

                    $parentColumnID = $this->createColumn($regionID, $copyColumn, 0, 0, $from);

                    if($copyColumn->parent < 0) $parentColumns[$copyColumnID] = $parentColumnID;
                    if(dao::isError()) return false;
                }
            }
        }
        elseif($from == 'kanban')
        {
            $groupID = $this->createGroup($kanban->id, $regionID);
            if(dao::isError()) return false;

            $this->createDefaultLane($kanban, $regionID, $groupID);
            if(dao::isError()) return false;

            $this->createDefaultColumns($kanban, $regionID, $groupID);
            if(dao::isError()) return false;
        }


        return $regionID;
    }

    /**
     * Create default lane.
     *
     * @param  object $kanban
     * @param  int    $regionID
     * @param  int    $groupID
     * @access public
     * @return int
     */
    public function createDefaultLane($kanban, $regionID, $groupID)
    {
        $lane = new stdclass();
        $lane->name           = $this->lang->kanbanlane->default;
        $lane->group          = $groupID;
        $lane->region         = $regionID;
        $lane->type           = 'common';
        $lane->lastEditedTime = helper::now();
        $lane->color          = '#7ec5ff';
        $lane->order          = 1;

        $this->dao->insert(TABLE_KANBANLANE)->data($lane)->exec();
        $laneID = $this->dao->lastInsertId();

        return $laneID;
    }

    /**
     * Create default kanban columns.
     *
     * @param  object $kanban
     * @param  int    $regionID
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function createDefaultColumns($kanban, $regionID, $groupID)
    {
        $order = 1;
        foreach($this->lang->kanban->defaultColumn as $columnName)
        {
            $column = new stdclass();
            $column->region = $regionID;
            $column->group  = $groupID;
            $column->name   = $columnName;
            $column->order  = $order;
            $column->limit  = -1;
            $column->color  = '#333';

            $this->createColumn($regionID, $column);
            $order ++;
        }

        return !dao::isError();
    }

    /**
     * Create a column.
     *
     * @param  int    $regionID
     * @param  object $column
     * @param  int    $order
     * @param  int    $parent
     * @param  string $from kanban|execution
     * @access public
     * @return int
     */
    public function createColumn($regionID, $column = null, $order = 0, $parent = 0, $from = 'kanban')
    {
        if(empty($column))
        {
            $column = fixer::input('post')
                ->add('region', $regionID)
                ->setIF($parent > 0, 'parent', $parent)
                ->setIF($order, 'order', $order)
                ->setDefault('color', '#333')
                ->trim('name')
                ->remove('WIPCount,noLimit')
                ->get();

            if(!$order)
            {
                $maxOrder = $this->dao->select('MAX(`order`) AS maxOrder')->from(TABLE_KANBANCOLUMN)
                    ->where('`group`')->eq($column->group)
                    ->fetch('maxOrder');
                $column->order = $maxOrder ? $maxOrder + 1 : 1;
            }

            if(!$column->limit && empty($_POST['noLimit'])) dao::$errors['limit'][] = sprintf($this->lang->error->notempty, $this->lang->kanban->WIP);
            if(!preg_match("/^-?\d+$/", $column->limit) or (!isset($_POST['noLimit']) and $column->limit <= 0))
            {
                dao::$errors['limit'] = $this->lang->kanban->error->mustBeInt;
                return false;
            }
            if(dao::isError()) return false;
        }

        $column->limit = (int)$column->limit;

        $limit = $column->limit;
        if(isset($column->parent) and $column->parent > 0)
        {
            /* Create a child column. */
            $parentColumn = $this->getColumnByID($column->parent);
            if($parentColumn->limit != -1)
            {
                /* The WIP of the child column is infinite or greater than the WIP of the parent column. */
                $sumChildLimit = $this->dao->select('SUM(`limit`) AS sumChildLimit')->from(TABLE_KANBANCOLUMN)->where('parent')->eq($column->parent)->andWhere('deleted')->eq(0)->fetch('sumChildLimit');
                if($limit == -1 or (($limit + $sumChildLimit) > $parentColumn->limit))
                {
                    dao::$errors['limit'][] = $this->lang->kanban->error->parentLimitNote;
                    return false;
                }

                $childColumns = $this->getColumnsByObject('parent', $column->parent);
                foreach($childColumns as $childColumn)
                {
                    $limit += (int)$childColumn->limit;
                    if($limit > $parentColumn->limit and $parentColumn->limit != -1)
                    {
                        /* The total WIP of the child columns is greater than the WIP of the parent column. */
                        dao::$errors['limit'][] = $this->lang->kanban->error->childLimitNote;
                        return false;
                    }
                }
            }
        }

        if($order)
        {
            /* It means copy a column or insert a column before or after a column. */
            $this->dao->update(TABLE_KANBANCOLUMN)
                ->set('`order` = `order` + 1')
                ->where('`group`')->eq($column->group)
                ->andWhere('`order`')->ge($order)
                ->exec();
        }

        $this->dao->insert(TABLE_KANBANCOLUMN)->data($column, 'noLimit,position,copyItems')
            ->batchCheck($this->config->kanban->require->createcolumn, 'notempty')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        $columnID = $this->dao->lastInsertID();

        if($from == 'kanban') $this->dao->update(TABLE_KANBANCOLUMN)->set('type')->eq("column{$columnID}")->where('id')->eq($columnID)->exec();

        /* Add kanban cell. */
        $lanes    = $this->dao->select('id')->from(TABLE_KANBANLANE)->where('`group`')->eq($column->group)->fetchPairs();
        $kanbanID = $this->dao->select('kanban')->from(TABLE_KANBANREGION)->where('id')->eq($regionID)->fetch('kanban');
        foreach($lanes as $laneID) $this->addKanbanCell($kanbanID, $laneID, $columnID, 'card');

        return $columnID;
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
        $this->loadModel('action');
        $data            = fixer::input('post')->get();
        $column          = $this->getColumnByID($columnID);
        $maxOrder        = $this->dao->select('MAX(`order`) AS maxOrder')->from(TABLE_KANBANCOLUMN)->where('`group`')->eq($column->group)->fetch('maxOrder');
        $order           = $maxOrder ? $maxOrder + 1 : 1;
        $sumChildLimit   = $this->dao->select('SUM(`limit`) AS sumChildLimit')->from(TABLE_KANBANCOLUMN)->where('parent')->eq($columnID)->fetch('sumChildLimit');

        $childrenColumn  = array();
        foreach($data->name as $i => $name)
        {
            $childColumn = new stdclass();
            $childColumn->lane   = $column->lane;
            $childColumn->parent = $column->id;
            $childColumn->region = $column->region;
            $childColumn->group  = $column->group;
            $childColumn->name   = $name;
            $childColumn->color  = $data->color[$i];
            $childColumn->limit  = isset($data->noLimit[$i]) ? -1 : $data->WIPCount[$i];
            $childColumn->order  = $order;

            if(!preg_match("/^-?\d+$/", $childColumn->limit) or (!isset($data->noLimit[$i]) and $childColumn->limit <= 0))
            {
                dao::$errors['limit'] = $this->lang->kanban->error->mustBeInt;
                return false;
            }

            if(empty($childColumn->name))
            {
                dao::$errors['name'] = sprintf($this->lang->error->notempty, $this->lang->kanbancolumn->name);
                return false;
            }

            $sumChildLimit += $childColumn->limit;
            if($column->limit != -1 and ($childColumn->limit == -1 or ($column->limit < $sumChildLimit)))
            {
                dao::$errors['limit'] = $this->lang->kanban->error->parentLimitNote;
                return false;
            }

            $order ++;
            $childrenColumn[$i] = $childColumn;
        }

        foreach($childrenColumn as $i => $childColumn)
        {
            $this->dao->insert(TABLE_KANBANCOLUMN)->data($childColumn)
                ->autoCheck()
                ->batchCheck($this->config->kanban->splitcolumn->requiredFields, 'notempty')
                ->exec();

            if(dao::isError()) return false;
            if(!dao::isError())
            {
                $childColumnID = $this->dao->lastInsertID();
                if($i == 1) $this->dao->update(TABLE_KANBANCARD)->set('`column`')->eq($childColumnID)->where('`column`')->eq($columnID)->exec();
                $this->dao->update(TABLE_KANBANCOLUMN)->set('type')->eq("column{$childColumnID}")->where('id')->eq($childColumnID)->exec();
                $this->action->create('kanbanColumn', $childColumnID, 'created');
            }
        }

        $this->dao->update(TABLE_KANBANCOLUMN)->set('parent')->eq(-1)->where('id')->eq($columnID)->exec();
    }

    /**
     * Create a kanban card.
     *
     * @param  int $kanbanID
     * @param  int $regionID
     * @param  int $groupID
     * @param  int $laneID
     * @param  int $columnID
     * @access public
     * @return void
     */
    public function createCard($kanbanID, $regionID, $groupID, $laneID, $columnID)
    {
        if($this->post->estimate < 0)
        {
            dao::$errors[] = $this->lang->kanbancard->error->recordMinus;
            return false;
        }

        if($this->post->end && $this->post->begin > $this->post->end)
        {
            dao::$errors[] = $this->lang->kanbancard->error->endSmall;
            return false;
        }

        $now  = helper::now();
        $card = fixer::input('post')
            ->add('kanban', $kanbanID)
            ->add('region', $regionID)
            ->add('group', $groupID)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', $now)
            ->add('assignedDate', $now)
            ->add('color', '#fff')
            ->trim('name')
            ->setDefault('estimate', 0)
            ->stripTags($this->config->kanban->editor->createcard['id'], $this->config->allowedTags)
            ->join('assignedTo', ',')
            ->setIF(is_numeric($this->post->estimate), 'estimate', (float)$this->post->estimate)
            ->remove('uid')
            ->get();

        $card = $this->loadModel('file')->processImgURL($card, $this->config->kanban->editor->createcard['id'], $this->post->uid);

        $this->dao->insert(TABLE_KANBANCARD)->data($card)->autoCheck()
            ->checkIF($card->estimate != '', 'estimate', 'float')
            ->batchCheck($this->config->kanban->createcard->requiredFields, 'notempty')
            ->exec();

        if(!dao::isError())
        {
            $cardID = $this->dao->lastInsertID();
            $this->file->saveUpload('kanbancard', $cardID);
            $this->file->updateObjectID($this->post->uid, $cardID, 'kanbancard');
            $this->addKanbanCell($kanbanID, $laneID, $columnID, 'card', $cardID);

            return $cardID;
        }

        return false;
    }

    /**
     * Get kanban by id.
     *
     * @param  int    $kanbanID
     * @access public
     * @return object
     */
    public function getByID($kanbanID)
    {
        $kanban = $this->dao->findByID($kanbanID)->from(TABLE_KANBAN)->fetch();
        $kanban = $this->loadModel('file')->replaceImgURL($kanban, 'desc');

        return $kanban;
    }

    /**
     * Get kanban data.
     *
     * @param  int    $kanbanID
     * @access public
     * @return void
     */
    public function getKanbanData($kanbanID)
    {
        $kanbanData  = array();
        $actions     = array('sortGroup');
        $regions     = $this->getRegionPairs($kanbanID);
        $groupGroup  = $this->getGroupGroupByRegions(array_keys($regions));
        $laneGroup   = $this->getLaneGroupByRegions(array_keys($regions));
        $columnGroup = $this->getColumnGroupByRegions(array_keys($regions));
        $cardGroup   = $this->getCardGroupByKanban($kanbanID);

        foreach($regions as $regionID => $regionName)
        {
            $region = new stdclass();
            $region->id        = $regionID;
            $region->name      = $regionName;
            $region->laneCount = 0;

            $groups = zget($groupGroup, $regionID, array());
            foreach($groups as $group)
            {
                $lanes = zget($laneGroup, $group->id, array());
                if(!$lanes) continue;

                foreach($lanes as $lane) $lane->items = isset($cardGroup[$lane->id]) ? $cardGroup[$lane->id] : array();

                $group->columns = zget($columnGroup, $group->id, array());
                $group->lanes   = $lanes;
                $group->actions = array();

                foreach($actions as $action)
                {
                    if(commonModel::hasPriv('kanban', $action)) $group->actions[] = $action;
                }

                $region->groups[]   = $group;
                $region->laneCount += count($lanes);
            }

            $kanbanData[$regionID] = $region;
        }

        return $kanbanData;
    }

    /**
     * Get plan kanban.
     *
     * @param  object $product
     * @param  int    $branchID
     * @param  array  $planGroup
     * @access public
     * @return array
     */
    public function getPlanKanban($product, $branchID = 0, $planGroup = '')
    {
        $this->loadModel('branch');
        $this->loadModel('productplan');

        $kanbanData  = new stdclass();
        $lanes       = array();
        $columns     = array();
        $branches    = array();
        $colorIndex  = 0;
        $laneOrder   = 1;
        $cardActions = array('view', 'createExecution', 'linkStory', 'linkBug', 'edit', 'start', 'finish', 'close', 'activate', 'delete');

        if($product->type == 'normal')
        {
            $branches = array('all' => $this->lang->productplan->allAB);
        }
        elseif($branchID == 'all')
        {
            $branches = $this->branch->getPairs($product->id);
        }
        elseif($branchID == BRANCH_MAIN)
        {
            $branches = array(BRANCH_MAIN => $this->lang->branch->main);
        }
        elseif($branchID)
        {
            $branchName = $this->branch->getById($branchID);
            $branches   = array($branchID => $branchName);
        }

        foreach($branches as $id => $name)
        {
            if($product->type != 'normal') $plans = isset($planGroup[$product->id][$id]) ? array_filter($planGroup[$product->id][$id]) : array();
            if($product->type == 'normal') $plans = $planGroup;
            $planList = array();

            foreach($plans as $planID => $plan)
            {
                if(empty($plan) or $plan->parent == -1) continue;
                if(!isset($planList[$plan->status])) $planList[$plan->status] = array();

                $plan->title   = htmlspecialchars_decode($plan->title);
                $plan->desc    = strip_tags(htmlspecialchars_decode($plan->desc));
                $plan->actions = array();
                foreach($cardActions as $action)
                {
                    if($action == 'createExecution')
                    {
                        if(common::hasPriv('execution', 'create')) $plan->actions[] = $action;
                        continue;
                    }
                    if($this->productplan->isClickable($plan, $action)) $plan->actions[] = $action;
                }
                $planList[$plan->status][] = $plan;
            }

            $lane = new stdclass();
            $lane->id    = $id;
            $lane->type  = 'branch';
            $lane->name  = $name;
            $lane->color = $this->config->productplan->laneColorList[$colorIndex];
            $lane->order = $laneOrder;
            $lane->items = $planList;

            $lanes[] = $lane;
            $laneOrder ++;
            $colorIndex ++;
            if($colorIndex == count($this->config->productplan->laneColorList)) $colorIndex = 0;
        }

        foreach($this->lang->kanban->defaultColumn as $columnType => $columnName)
        {
            $column = new stdclass();
            $column->id   = $columnType;
            $column->type = $columnType;
            $column->name = $columnName;

            $columns[] = $column;
        }

        $kanbanData->id      = 'plans';
        $kanbanData->lanes   = $lanes;
        $kanbanData->columns = $columns;

        return $kanbanData;
    }

    /**
     * Get a RD kanban data.
     *
     * @param  int    $executionID
     * @param  string $browseType all|story|task|bug
     * @param  string $orderBy
     * @param  string $groupBy
     *
     * @access public
     * @return array
     */
    public function getRDKanban($executionID, $browseType = 'all', $orderBy = 'id_desc', $groupBy = 'all')
    {
        $kanbanData   = array();
        $actions      = array('sortGroup');
        $regions      = $this->getRegionPairs($executionID);
        $regionIDList = array_keys($regions);
        $groupGroup   = $this->getGroupGroupByRegions($regionIDList);
        $laneGroup    = $this->getLaneGroupByRegions($regionIDList, $browseType);
        $columnGroup  = $this->getRDColumnGroupByRegions($regionIDList, array_keys($laneGroup));
        $cardGroup    = $this->getCardGroupByExecution($executionID, $browseType, $orderBy);

        foreach($regions as $regionID => $regionName)
        {
            $region = new stdclass();
            $region->id        = $regionID;
            $region->name      = $regionName;
            $region->laneCount = 0;

            $groups = zget($groupGroup, $regionID, array());
            foreach($groups as $group)
            {
                $lanes = zget($laneGroup, $group->id, array());
                if(!$lanes) continue;

                foreach($lanes as $lane)
                {
                    $this->refreshCards($lane);
                    $lane->items           = isset($cardGroup[$lane->id]) ? $cardGroup[$lane->id] : array();
                    $lane->defaultCardType = $lane->type;
                }

                $group->columns = zget($columnGroup, $group->id, array());
                $group->lanes   = $lanes;
                $group->actions = array();

                foreach($actions as $action)
                {
                    if(commonModel::hasPriv('kanban', $action)) $group->actions[] = $action;
                }

                $region->groups[]   = $group;
                $region->laneCount += count($lanes);
            }

            $kanbanData[$regionID] = $region;
        }

        return $kanbanData;
    }

    /**
     * Get region by id.
     *
     * @param  int    $regionID
     * @access public
     * @return object
     */
    public function getRegionByID($regionID)
    {
        return $this->dao->findByID($regionID)->from(TABLE_KANBANREGION)->fetch();
    }

    /**
     * Get ordered region pairs.
     *
     * @param  int    $kanbanID
     * @access public
     * @return array
     */
    public function getRegionPairs($kanbanID)
    {
        return $this->dao->select('id,name')->from(TABLE_KANBANREGION)
            ->where('kanban')->eq($kanbanID)
            ->andWhere('deleted')->eq('0')
            ->orderBy('order_asc')
            ->fetchPairs();
    }

    /**
     * Get kanban group by regions.
     *
     * @param  array $regions
     * @access public
     * @return array
     */
    public function getGroupGroupByRegions($regions)
    {
        return $this->dao->select('*')->from(TABLE_KANBANGROUP)
            ->where('region')->in($regions)
            ->orderBy('order')
            ->fetchGroup('region', 'id');
    }

    /**
     * Get lane group by regions.
     *
     * @param  array  $regions
     * @param  string $browseType
     * @access public
     * @return array
     */
    public function getLaneGroupByRegions($regions, $browseType = 'all')
    {
        $laneGroup = $this->dao->select('*')->from(TABLE_KANBANLANE)
            ->where('deleted')->eq('0')
            ->andWhere('region')->in($regions)
            ->beginIf($browseType != 'all')->andWhere('type')->eq($browseType)->fi()
            ->orderBy('order')
            ->fetchGroup('group');

        $actions = array('setLane', 'sortLane', 'deleteLane');
        foreach($laneGroup as $lanes)
        {
            foreach($lanes as $lane)
            {
                $lane->actions = array();
                foreach($actions as $action)
                {
                    if($this->isClickable($lane, $action)) $lane->actions[] = $action;
                }
            }
        }

        return $laneGroup;
    }

    /**
     * Get column group by regions.
     *
     * @param  array  $regions
     * @param  string $order order|id_asc
     * @access public
     * @return array
     */
    public function getColumnGroupByRegions($regions, $order = 'order')
    {
        $columnGroup = $this->dao->select("*")->from(TABLE_KANBANCOLUMN)
            ->where('deleted')->eq('0')
            ->andWhere('archived')->eq('0')
            ->andWhere('region')->in($regions)
            ->orderBy($order)
            ->fetchGroup('group');

        $actions = array('createColumn', 'setColumn', 'setWIP', 'archiveColumn', 'restoreColumn', 'deleteColumn', 'createCard', 'splitColumn');

        /* Group by parent. */
        $parentColumnGroup = array();
        foreach($columnGroup as $group => $columns)
        {
            foreach($columns as $column)
            {
                $column->actions = array();
                /* Judge column action priv. */
                foreach($actions as $action)
                {
                    if($this->isClickable($column, $action)) $column->actions[] = $action;
                }

                if($column->parent > 0) continue;

                $parentColumnGroup[$group][] = $column;
            }
        }

        $columnData = array();
        foreach($parentColumnGroup as $group => $parentColumns)
        {
            foreach($parentColumns as $parentColumn)
            {
                $columnData[$group][] = $parentColumn;
                foreach($columnGroup[$group] as $column)
                {
                    if($column->parent == $parentColumn->id)
                    {
                        $parentColumn->asParent = true;

                        $column->parentType = 'column' . $column->parent;

                        $columnData[$group][] = $column;
                    }
                }
            }
        }

        return $columnData;
    }

    /**
     * Get card group by kanban id.
     *
     * @param  int    $kanbanID
     * @access public
     * @return array
     */
    public function getCardGroupByKanban($kanbanID)
    {
        $cards = $this->dao->select('t1.*,t2.kanban,t2.lane,t2.column')->from(TABLE_KANBANCARD)->alias('t1')
            ->leftJoin(TABLE_KANBANCELL)->alias('t2')->on('t1.kanban=t2.kanban')
            ->where('deleted')->eq(0)
            ->andWhere("INSTR(t2.cards, CONCAT(',',t1.id,','))")->gt(0)
            ->andWhere('archived')->eq(0)
            ->andWhere('t2.kanban')->eq($kanbanID)
            //->orderBy('`order` asc')
            ->fetchAll('id');

        $actions = array('editCard', 'archiveCard', 'deleteCard', 'moveCard', 'setCardColor', 'viewCard');
        $cardGroup = array();
        foreach($cards as $card)
        {
            $card->actions = array();
            foreach($actions as $action)
            {
                if(common::hasPriv('kanban', $action)) $card->actions[] = $action;
            }

            $cardGroup[$card->lane]['column' . $card->column][] = $card;
        }

        return $cardGroup;
    }

    /**
     * Get RD column group by regions.
     *
     * @param  array  $regions
     * @param  array  $groupIDList
     * @access public
     * @return array
     */
    public function getRDColumnGroupByRegions($regions, $groupIDList = array())
    {
        $columnGroup = $this->dao->select("*")->from(TABLE_KANBANCOLUMN)
            ->where('deleted')->eq('0')
            ->andWhere('region')->in($regions)
            ->beginIF(!empty($groupIDList))->andWhere('`group`')->in($groupIDList)->fi()
            ->orderBy('id_asc')
            ->fetchGroup('group');

        $actions = array('setColumn', 'setWIP', 'deleteColumn');

        /* Group by parent. */
        $parentColumnGroup = array();
        foreach($columnGroup as $group => $columns)
        {
            foreach($columns as $column)
            {
                $column->actions = array();
                /* Judge column action priv. */
                foreach($actions as $action)
                {
                    if($this->isClickable($column, $action)) $column->actions[] = $action;
                }

                if($column->parent > 0) continue;

                $parentColumnGroup[$group][] = $column;
            }
        }

        $columnData = array();
        foreach($parentColumnGroup as $group => $parentColumns)
        {
            foreach($parentColumns as $parentColumn)
            {
                $columnData[$group][] = $parentColumn;
                foreach($columnGroup[$group] as $column)
                {
                    if($column->parent == $parentColumn->id)
                    {
                        $parentColumn->asParent = true;

                        if(strpos(',developing,developed,', $column->type) !== false) $column->parentType = 'develop';
                        if(strpos(',testing,tested,',       $column->type) !== false) $column->parentType = 'test';
                        if(strpos(',fixing,fixed,',         $column->type) !== false) $column->parentType = 'resolving';

                        $columnData[$group][] = $column;
                    }
                }
            }
        }

        return $columnData;
    }

    /**
     * Get card group by execution id.
     *
     * @param  int    $kanbanID
     * @param  string $browseType all|task|bug|story
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getCardGroupByExecution($executionID, $browseType = 'all', $orderBy = 'id_asc')
    {
        $cards = $this->dao->select('t1.*, t2.type as columnType')
            ->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.column=t2.id')
            ->where('t1.kanban')->eq($executionID)
            ->beginIF($browseType != 'all')->andWhere('t1.type')->eq($browseType)->fi()
            ->orderby($orderBy)
            ->fetchgroup('lane', 'column');

        /* Get group objects. */
        if($browseType == 'all' or $browseType == 'story') $objectGroup['story'] = $this->loadModel('story')->getExecutionStories($executionID);
        if($browseType == 'all' or $browseType == 'bug')   $objectGroup['bug']   = $this->loadModel('bug')->getExecutionBugs($executionID);
        if($browseType == 'all' or $browseType == 'task')  $objectGroup['task']  = $this->loadModel('execution')->getKanbanTasks($executionID, "id");

        $cardGroup = array();

        foreach($cards as $laneID => $cells)
        {
            foreach($cells as $columnID => $cell)
            {
                $cardIdList = array_filter(explode(',', $cell->cards));
                $cardOrder  = 1;
                foreach($cardIdList as $cardID)
                {
                    $cardData = array();
                    $objects  = zget($objectGroup, $cell->type, array());
                    $object   = zget($objects, $cardID, array());

                    if(empty($object)) continue;

                    $cardData['id']         = $object->id;
                    $cardData['order']      = $cardOrder++;
                    $cardData['pri']        = $object->pri ? $object->pri : '';
                    $cardData['estimate']   = $cell->type == 'bug' ? '' : $object->estimate;
                    $cardData['assignedTo'] = $object->assignedTo;
                    $cardData['deadline']   = $cell->type == 'story' ? '' : $object->deadline;
                    $cardData['severity']   = $cell->type == 'bug' ? $object->severity : '';
                    $cardData['acl']        = 'open';
                    $cardData['lane']       = $laneID;
                    $cardData['column']     = $cell->column;

                    if($cell->type == 'task')
                    {
                        $cardData['name'] = $object->name;
                    }
                    else
                    {
                        $cardData['title'] = $object->title;
                    }
                    $cardGroup[$laneID][$cell->columnType][] = $cardData;
                }
            }
        }

        return $cardGroup;
    }

    /**
     * Get Kanban by execution id.
     *
     * @param  int    $executionID
     * @param  string $browseType all|story|bug|task
     * @param  string $groupBy
     * @access public
     * @return array
     */
    public function getExecutionKanban($executionID, $browseType = 'all', $groupBy = 'default')
    {
        if($groupBy != 'default') return $this->getKanban4Group($executionID, $browseType, $groupBy);

        $lanes = $this->dao->select('*')->from(TABLE_KANBANLANE)
            ->where('execution')->eq($executionID)
            ->andWhere('deleted')->eq(0)
            ->beginIF($browseType != 'all')->andWhere('type')->eq($browseType)->fi()
            ->beginIF($groupBy == 'default')->andWhere('groupby')->eq('')->fi()
            ->orderBy('order_asc')
            ->fetchAll('id');

        if(empty($lanes)) return array();

        foreach($lanes as $lane) $this->refreshCards($lane);

        $columns = $this->dao->select('t1.cards, t1.lane, t2.id, t2.type, t2.name, t2.color, t2.limit, t2.parent')->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.column = t2.id')
            ->where('t2.deleted')->eq(0)
            ->andWhere('t1.lane')->in(array_keys($lanes))
            ->orderBy('id_asc')
            ->fetchGroup('lane', 'id');

        /* Get group objects. */
        if($browseType == 'all' or $browseType == 'story') $objectGroup['story'] = $this->loadModel('story')->getExecutionStories($executionID);
        if($browseType == 'all' or $browseType == 'bug')   $objectGroup['bug']   = $this->loadModel('bug')->getExecutionBugs($executionID);
        if($browseType == 'all' or $browseType == 'task')  $objectGroup['task']  = $this->loadModel('execution')->getKanbanTasks($executionID, "id");

        /* Get objects cards menus. */
        if($browseType == 'all' or $browseType == 'story') $storyCardMenu = $this->getKanbanCardMenu($executionID, $objectGroup['story'], 'story');
        if($browseType == 'all' or $browseType == 'bug')   $bugCardMenu   = $this->getKanbanCardMenu($executionID, $objectGroup['bug'], 'bug');
        if($browseType == 'all' or $browseType == 'task')  $taskCardMenu  = $this->getKanbanCardMenu($executionID, $objectGroup['task'], 'task');

        /* Build kanban group data. */
        $kanbanGroup = array();
        foreach($lanes as $laneID => $lane)
        {
            $laneData   = array();
            $columnData = array();

            $laneData['id']              = $laneID;
            $laneData['laneID']          = $laneID;
            $laneData['name']            = $lane->name;
            $laneData['color']           = $lane->color;
            $laneData['order']           = $lane->order;
            $laneData['defaultCardType'] = $lane->type;

            foreach($columns[$laneID] as $columnID => $column)
            {
                $columnData[$column->id]['id']         = $columnID;
                $columnData[$column->id]['type']       = $column->type;
                $columnData[$column->id]['name']       = $column->name;
                $columnData[$column->id]['color']      = $column->color;
                $columnData[$column->id]['limit']      = $column->limit;
                $columnData[$column->id]['laneType']   = $lane->type;
                $columnData[$column->id]['asParent']   = $column->parent == -1 ? true : false;
                $columnData[$column->id]['parent']     = $column->parent;

                if($column->parent > 0)
                {
                    if($column->type == 'developing' or $column->type == 'developed') $columnData[$column->id]['parentType'] = 'develop';
                    if($column->type == 'testing' or $column->type == 'tested') $columnData[$column->id]['parentType'] = 'test';
                    if($column->type == 'fixing' or $column->type == 'fixed') $columnData[$column->id]['parentType'] = 'resolving';
                }

                $cardOrder  = 1;
                $cardIdList = array_filter(explode(',', $column->cards));
                foreach($cardIdList as $cardID)
                {
                    $cardData = array();
                    $objects  = zget($objectGroup, $lane->type, array());
                    $object   = zget($objects, $cardID, array());

                    if(empty($object)) continue;

                    $cardData['id']         = $object->id;
                    $cardData['order']      = $cardOrder;
                    $cardData['pri']        = $object->pri ? $object->pri : '';
                    $cardData['estimate']   = $lane->type == 'bug' ? '' : $object->estimate;
                    $cardData['assignedTo'] = $object->assignedTo;
                    $cardData['deadline']   = $lane->type == 'story' ? '' : $object->deadline;
                    $cardData['severity']   = $lane->type == 'bug' ? $object->severity : '';

                    if($lane->type == 'task')
                    {
                        $cardData['name'] = $object->name;
                    }
                    else
                    {
                        $cardData['title'] = $object->title;
                    }

                    if($lane->type == 'story') $cardData['menus'] = $storyCardMenu[$object->id];
                    if($lane->type == 'bug')   $cardData['menus'] = $bugCardMenu[$object->id];
                    if($lane->type == 'task')  $cardData['menus'] = $taskCardMenu[$object->id];

                    $laneData['cards'][$column->type][] = $cardData;
                    $cardOrder ++;
                }
                if(!isset($laneData['cards'][$column->type])) $laneData['cards'][$column->type] = array();
            }

            $kanbanGroup[$lane->type]['id']              = $laneID;
            $kanbanGroup[$lane->type]['columns']         = array_values($columnData);
            $kanbanGroup[$lane->type]['lanes'][]         = $laneData;
            $kanbanGroup[$lane->type]['defaultCardType'] = $lane->type;
        }

        return $kanbanGroup;
    }

    /**
     * Get kanban for group view.
     *
     * @param  int    $executionID
     * @param  string $browseType
     * @param  string $groupBy
     * @access public
     * @return array
     */
    public function getKanban4Group($executionID, $browseType, $groupBy)
    {
        /* Get card  data. */
        if($browseType == 'story') $cardList = $this->loadModel('story')->getExecutionStories($executionID);
        if($browseType == 'bug')   $cardList = $this->loadModel('bug')->getExecutionBugs($executionID);
        if($browseType == 'task')  $cardList = $this->loadModel('execution')->getKanbanTasks($executionID, "id");

        $lanes = $this->getLanes4Group($executionID, $browseType, $groupBy, $cardList);
        if(empty($lanes)) return array();

        $columns = $this->dao->select('t1.*, t2.`type` as columnType')->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.`column` = t2.id')
            ->where('t1.kanban')->eq($executionID)
            ->andWhere('t1.`type`')->eq($browseType)
            ->fetchAll();

        $cardGroup = array();
        foreach($columns as $column)
        {
            if(empty($column->cards)) continue;
            foreach($cardList as $card)
            {
                if(strpos($column->cards, ",$card->id,") !== false) $cardGroup[$column->columnType][$card->id] = $card;
            }
        }

        /* Build kanban group data. */
        $kanbanGroup = array();
        foreach($lanes as $laneID => $lane)
        {
            $laneData   = array();
            $columnData = array();
            $columnList = $this->lang->kanban->{$browseType . 'Column'};

            $laneData['id']              = $groupBy . $laneID;
            $laneData['laneID']          = $groupBy . $laneID;
            $laneData['name']            = (($groupBy == 'pri' or $groupBy == 'severity') and $laneID) ? $this->lang->$browseType->$groupBy . ':' . $lane->name : $lane->name;
            $laneData['color']           = $lane->color;
            $laneData['order']           = $lane->order;
            $laneData['defaultCardType'] = $browseType;

            /* Construct kanban column data. */
            foreach($columnList as $columnID => $columnName)
            {
                $parentColumn = '';
                if(in_array($columnID, array('developing', 'developed'))) $parentColumn = 'develop';
                if(in_array($columnID, array('testing', 'tested')))       $parentColumn = 'test';
                if(in_array($columnID, array('fixing', 'fixed')))         $parentColumn = 'resolving';

                $columnData[$columnID]['id']         = $columnID;
                $columnData[$columnID]['type']       = $columnID;
                $columnData[$columnID]['name']       = $columnName;
                $columnData[$columnID]['color']      = '#333';
                $columnData[$columnID]['limit']      = -1;
                $columnData[$columnID]['laneType']   = $browseType;
                $columnData[$columnID]['asParent']   = in_array($columnID, array('develop', 'test', 'resolving')) ? true : false;
                $columnData[$columnID]['parentType'] = $parentColumn;

                $cardOrder = 1;
                $objects   = zget($cardGroup, $columnID, array());
                foreach($objects as $object)
                {
                    if(empty($object)) continue;

                    $cardData = array();
                    if(in_array($groupBy, array('module', 'story', 'pri', 'severity')) and (int)$object->$groupBy !== $laneID) continue;
                    if(in_array($groupBy, array('assignedTo', 'type', 'category', 'source')) and $object->$groupBy != $laneID) continue;

                    $cardData['id']         = $object->id;
                    $cardData['order']      = $cardOrder;
                    $cardData['pri']        = $object->pri ? $object->pri : '';
                    $cardData['estimate']   = $browseType == 'bug' ? '' : $object->estimate;
                    $cardData['assignedTo'] = $object->assignedTo;
                    $cardData['deadline']   = $browseType == 'story' ? '' : $object->deadline;
                    $cardData['severity']   = $browseType == 'bug' ? $object->severity : '';

                    if($browseType == 'task')
                    {
                        $cardData['name'] = $object->name;
                    }
                    else
                    {
                        $cardData['title'] = $object->title;
                    }

                    $laneData['cards'][$columnID][] = $cardData;
                    $cardOrder ++;
                }
                if(!isset($laneData['cards'][$columnID])) $laneData['cards'][$columnID] = array();
            }

            $kanbanGroup[$groupBy]['id']              = $groupBy . $laneID;
            $kanbanGroup[$groupBy]['columns']         = array_values($columnData);
            $kanbanGroup[$groupBy]['lanes'][]         = $laneData;
            $kanbanGroup[$groupBy]['defaultCardType'] = $browseType;
        }

        return $kanbanGroup;
    }

    /**
     * Build lane data for group kanban.
     * 
     * @access public
     * @param  int    $executionID
     * @param  string $browseType
     * @param  string $groupBy
     * @param  array  $cardList
     * @return array
     */
    public function getLanes4Group($executionID, $browseType, $groupBy, $cardList)
    {
        $lanes       = array();
        $groupByList = array();
        $objectPairs = array();
        foreach($cardList as $item)
        {
            if(!isset($groupByList[$item->$groupBy])) $groupByList[$item->$groupBy] = $item->$groupBy;
        }

        if(in_array($groupBy, array('module', 'story', 'pri', 'severity'))) $objectPairs[0]  = $this->lang->$browseType->$groupBy . ': ' . $this->lang->kanban->noGroup;
        if(in_array($groupBy, array('assignedTo', 'type', 'category', 'source'))) $objectPairs[''] = $this->lang->$browseType->$groupBy . ': ' . $this->lang->kanban->noGroup;

        if(in_array($groupBy, array('module', 'story', 'assignedTo')))
        {
            if($groupBy == 'module')
            {
                $objectPairs += $this->dao->select('id,name')->from(TABLE_MODULE)
                    ->where('type')->in('story,task,bug')
                    ->andWhere('deleted')->eq('0')
                    ->andWhere('id')->in($groupByList)
                    ->fetchPairs();
            }
            elseif($groupBy == 'story')
            {
                $objectPairs += $this->dao->select('id,title')->from(TABLE_STORY)
                    ->where('deleted')->eq(0)
                    ->andWhere('id')->in($groupByList)
                    ->fetchPairs();
            }
            else
            {
                $objectPairs += $this->dao->select('account,realname')->from(TABLE_USER)
                    ->where('account')->in($groupByList)
                    ->fetchPairs();

                if(isset($groupByList['closed'])) $objectPairs['closed'] = 'Closed';
            }
        }
        else
        {
            $objectPairs += $this->lang->$browseType->{$groupBy . 'List'};
        }

        $laneColor = 0;
        $order     = 1;
        foreach($objectPairs as $objectID => $objectName)
        {
            if(!isset($groupByList[$objectID]) and $objectID) continue;

            $lane = new stdclass();
            $lane->id        = $groupBy . $objectID;
            $lane->type      = $browseType;
            $lane->execution = $executionID;
            $lane->name      = $objectName;
            $lane->order     = $order;
            $lane->color     = $this->config->kanban->laneColorList[$laneColor];

            $order     += 1;
            $laneColor += 1;
            if($laneColor == count($this->config->kanban->laneColorList)) $laneColor = 0;
            $lanes[$objectID] = $lane;
        }

        return $lanes;
    }

    /**
     * Get space list.
     *
     * @param  string $browseType all|my|other|closed
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getSpaceList($browseType, $pager)
    {
        $account     = $this->app->user->account;
        $spaceIdList = $this->getCanViewObjects('kanbanspace');
        $spaceList   = $this->dao->select('*')->from(TABLE_KANBANSPACE)
            ->where('deleted')->eq(0)
            ->beginIF($browseType == 'my')->andWhere('owner')->eq($account)->fi()
            ->beginIF($browseType == 'other')->andWhere('owner')->ne($account)->fi()
            ->beginIF($browseType == 'closed')->andWhere('status')->eq('closed')->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($spaceIdList)->fi()
            ->orderBy('id_desc')
            ->page($pager)
            ->fetchAll('id');

        $kanbanIdList = $this->getCanViewObjects();
        $kanbanGroup  = $this->getGroupBySpaceList(array_keys($spaceList), $kanbanIdList);
        foreach($spaceList as $spaceID => $space)
        {
            if(isset($kanbanGroup[$spaceID])) $space->kanbans = $kanbanGroup[$spaceID];
        }

        return $spaceList;
    }

    /**
     * Get space pairs.
     *
     * @param  string $browseType all|my|other|closed
     * @access public
     * @return array
     */
    public function getSpacePairs($browseType = 'all')
    {
        $account     = $this->app->user->account;
        $spaceIdList = $this->getCanViewObjects('kanbanspace');

        return $this->dao->select('id,name')->from(TABLE_KANBANSPACE)
            ->where('deleted')->eq(0)
            ->beginIF($browseType == 'my')->andWhere('owner')->eq($account)->fi()
            ->beginIF($browseType == 'other')->andWhere('owner')->ne($account)->fi()
            ->beginIF($browseType == 'closed')->andWhere('status')->eq('closed')->fi()
            ->beginIF($browseType == 'noclosed')->andWhere('status')->ne('closed')->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($spaceIdList)->fi()
            ->orderBy('id_desc')
            ->fetchPairs('id');
    }

    /**
     * Get can view objects.
     *
     * @param  string $objectType kanbanspace|kanban
     * @access public
     * @return array
     */
    public function getCanViewObjects($objectType = 'kanban')
    {
        $table     = $this->config->objectTables[$objectType];
        $objects   = $this->dao->select('*')->from($table)->fetchAll('id');
        $spaceList = $objectType == 'kanban' ? $this->dao->select('id,owner,team,whitelist')->from(TABLE_KANBANSPACE)->fetchAll('id') : array();

        if($this->app->user->admin) return array_keys($objects);

        $account = $this->app->user->account;
        foreach($objects as $objectID => $object)
        {
            if($object->acl == 'private' or $object->acl == 'extend')
            {
                $remove = true;
                if($object->owner == $account) $remove = false;
                if(strpos(",{$object->team},", ",$account,") !== false) $remove = false;
                if(strpos(",{$object->whitelist},", ",$account,") !== false) $remove = false;
                if($objectType == 'kanban')
                {
                    $spaceOwner     = isset($spaceList[$object->space]->owner) ? $spaceList[$object->space]->owner : '';
                    $spaceTeam      = isset($spaceList[$object->space]->team) ? trim($spaceList[$object->space]->team, ',') : '';
                    $spaceWhiteList = isset($spaceList[$object->space]->whitelist) ? trim($spaceList[$object->space]->whitelist, ',') : '';
                    if(strpos(",$spaceOwner,", ",$account,") !== false) $remove = false;
                    if(strpos(",$spaceTeam,", ",$account,") !== false and $object->acl == 'extend') $remove = false;
                    if(strpos(",$spaceWhiteList,", ",$account,") !== false and $object->acl == 'extend') $remove = false;
                }

                if($remove) unset($objects[$objectID]);
            }
        }

        return array_keys($objects);
    }

    /**
     * Create a space.
     *
     * @access public
     * @return int
     */
    public function createSpace()
    {
        $account = $this->app->user->account;
        $space   = fixer::input('post')
            ->setDefault('createdBy', $account)
            ->setDefault('createdDate', helper::now())
            ->join('whitelist', ',')
            ->join('team', ',')
            ->trim('name')
            ->remove('uid,contactListMenu')
            ->get();

        if(strpos(",{$space->team},", ",$account,") === false and $space->owner != $account) $space->team .= ",$account";

         $space = $this->loadModel('file')->processImgURL($space, $this->config->kanban->editor->createspace['id'], $this->post->uid);

        $this->dao->insert(TABLE_KANBANSPACE)->data($space)
            ->autoCheck()
            ->batchCheck($this->config->kanban->createspace->requiredFields, 'notempty')
            ->exec();

        if(!dao::isError())
        {
            $spaceID = $this->dao->lastInsertID();
            $this->dao->update(TABLE_KANBANSPACE)->set('`order`')->eq($spaceID)->where('id')->eq($spaceID)->exec();
            $this->file->saveUpload('kanbanspace', $spaceID);
            $this->file->updateObjectID($this->post->uid, $spaceID, 'kanbanspace');

            return $spaceID;
        }
    }

    /**
     * Update a space.
     *
     * @param  int    $spaceID
     * @access public
     * @return array
     */
    public function updateSpace($spaceID)
    {
        $spaceID  = (int)$spaceID;
        $oldSpace = $this->getSpaceById($spaceID);
        $space    = fixer::input('post')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->join('whitelist', ',')
            ->join('team', ',')
            ->trim('name')
            ->remove('uid,contactListMenu')
            ->get();

        $space->whitelist = $space->acl == 'open' ? '' : $space->whitelist;

        $space = $this->loadModel('file')->processImgURL($space, $this->config->kanban->editor->editspace['id'], $this->post->uid);

        $this->dao->update(TABLE_KANBANSPACE)->data($space)
            ->autoCheck()
            ->batchCheck($this->config->kanban->editspace->requiredFields, 'notempty')
            ->where('id')->eq($spaceID)
            ->exec();

        if(!dao::isError())
        {
            $this->file->saveUpload('kanbanspace', $spaceID);
            $this->file->updateObjectID($this->post->uid, $spaceID, 'kanbanspace');
            return common::createChanges($oldSpace, $space);
        }
    }

    /**
     * Close a space.
     *
     * @param  int    $spaceID
     * @access public
     * @return array
     */
    function closeSpace($spaceID)
    {
        $spaceID  = (int)$spaceID;
        $oldSpace = $this->getSpaceById($spaceID);
        $now      = helper::now();
        $account  = $this->app->user->account;
        $space    = fixer::input('post')
            ->setDefault('status', 'closed')
            ->setDefault('closedBy', $account)
            ->setDefault('closedDate', $now)
            ->setDefault('lastEditedBy', $account)
            ->setDefault('lastEditedDate', $now)
            ->remove('comment')
            ->get();

        $this->dao->update(TABLE_KANBANSPACE)->data($space)
            ->autoCheck()
            ->where('id')->eq($spaceID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldSpace, $space);
    }

    /**
     * Get lane pairs by region id.
     *
     * @param  array  $regionID
     * @param  string $type all|story|task|bug|common
     * @access public
     * @return array
     */
    public function getLanePairsByRegion($regionID, $type = 'all')
    {
        return $this->dao->select('id, name')->from(TABLE_KANBANLANE)
            ->where('deleted')->eq('0')
            ->andWhere('region')->eq($regionID)
            ->beginIF($type != 'all')->andWhere('type')->eq($type)->fi()
            ->fetchPairs();
    }

    /**
     * Create a lane.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @param  object $lane
     * @access public
     * @return int
     */
    public function createLane($kanbanID, $regionID, $lane = null)
    {
        if(empty($lane))
        {
            $maxOrder = $this->dao->select('MAX(`order`) AS maxOrder')->from(TABLE_KANBANLANE)
                ->where('region')->eq($regionID)
                ->fetch('maxOrder');
            $lane = fixer::input('post')
                ->add('region', $regionID)
                ->add('order', $maxOrder ? $maxOrder + 1 : 1)
                ->add('lastEditedTime', helper::now())
                ->setIF(isset($this->post->laneType), 'execution', $kanbanID)
                ->trim('name')
                ->setDefault('color', '#7ec5ff')
                ->remove('laneType')
                ->get();

            $lane->type = isset($_POST['laneType']) ? $_POST['laneType'] : 'common';

            $mode = zget($lane, 'mode', '');
            if($mode == 'sameAsOther')
            {
                $otherLane = zget($lane, 'otherLane', 0);
                if($otherLane) $lane->group = $this->dao->select('`group`')->from(TABLE_KANBANLANE)->where('id')->eq($otherLane)->fetch('group');
            }
            elseif($mode == 'independent')
            {
                $lane->group = $this->createGroup($kanbanID, $regionID);
                if($lane->type == 'common')
                {
                    $kanban = $this->getByID($kanbanID);
                    $this->createDefaultColumns($kanban, $regionID, $lane->group);
                }
            }
        }

        $this->dao->insert(TABLE_KANBANLANE)->data($lane, $skip = 'mode,otherLane')
            ->batchCheck($this->config->kanban->require->createlane, 'notempty')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        $laneID = $this->dao->lastInsertID();
        if($lane->type != 'common' and isset($mode) and $mode == 'independent') $this->createRDColumn($regionID, $lane->group, $laneID, $lane->type, $kanbanID);

        if(isset($mode) and $mode == 'sameAsOther')
        {
            $columnIDList = $this->dao->select('id')->from(TABLE_KANBANCOLUMN)->where('deleted')->eq(0)->andWhere('archived')->eq(0)->andWhere('`group`')->eq($lane->group)->fetchPairs();
            foreach($columnIDList as $columnID)
            {
                $data = new stdclass();
                $data->kanban = $kanbanID;
                $data->lane   = $laneID;
                $data->column = $columnID;
                $data->type   = $lane->type;
                $this->dao->insert(TABLE_KANBANCELL)->data($data)->exec();

                if(dao::isError()) return false;
            }
        }
        return $laneID;
    }

    /*
     * Create a kanban.
     *
     * @access public
     * @return int
     */
    public function create()
    {
        $account = $this->app->user->account;
        $kanban  = fixer::input('post')
            ->setDefault('createdBy', $account)
            ->setDefault('createdDate', helper::now())
            ->join('whitelist', ',')
            ->join('team', ',')
            ->trim('name')
            ->remove('uid,contactListMenu')
            ->get();

        if(strpos(",{$kanban->team},", ",$account,") === false and $kanban->owner != $account) $kanban->team .= ",$account";

         $kanban = $this->loadModel('file')->processImgURL($kanban, $this->config->kanban->editor->create['id'], $this->post->uid);

        if(!empty($kanban->space))
        {
            $maxOrder = $this->dao->select('MAX(`order`) AS maxOrder')->from(TABLE_KANBAN)
                ->where('space')->eq($kanban->space)
                ->fetch('maxOrder');
            $kanban->order = $maxOrder ? $maxOrder+ 1 : 1;

            if($kanban->acl == 'extend')
            {
                $spaceAcl = $this->dao->select('acl')->from(TABLE_KANBANSPACE)->where('id')->eq($kanban->space)->fetch('acl');
                $kanban->acl = $spaceAcl == 'open' ? 'open' : 'extend';
            }
        }


        $this->dao->insert(TABLE_KANBAN)->data($kanban)
            ->autoCheck()
            ->batchCheck($this->config->kanban->create->requiredFields, 'notempty')
            ->exec();

        if(!dao::isError())
        {
            $kanbanID = $this->dao->lastInsertID();
            $kanban   = $this->getByID($kanbanID);

            $this->createDefaultRegion($kanban);
            $this->file->saveUpload('kanban', $kanbanID);
            $this->file->updateObjectID($this->post->uid, $kanbanID, 'kanban');

            return $kanbanID;
        }
    }

    /**
     * Update a kanban.
     *
     * @param  int    $kanbanID
     * @access public
     * @return array
     */
    public function update($kanbanID)
    {
        $kanbanID  = (int)$kanbanID;
        $account   = $this->app->user->account;
        $oldKanban = $this->getByID($kanbanID);
        $kanban    = fixer::input('post')
            ->setDefault('lastEditedBy', $account)
            ->setDefault('lastEditedDate', helper::now())
            ->join('whitelist', ',')
            ->join('team', ',')
            ->trim('name')
            ->remove('uid,contactListMenu')
            ->get();

        if($kanban->acl == 'extend')
        {
            $spaceAcl = $this->dao->select('acl')->from(TABLE_KANBANSPACE)->where('id')->eq($kanban->space)->fetch('acl');
            $kanban->acl       = $spaceAcl == 'open' ? 'open' : 'extend';
            $kanban->whitelist = $kanban->acl == 'open' ? '' : $kanban->whitelist;
        }


        $kanban = $this->loadModel('file')->processImgURL($kanban, $this->config->kanban->editor->edit['id'], $this->post->uid);

        $this->dao->update(TABLE_KANBAN)->data($kanban)
            ->autoCheck()
            ->batchCheck($this->config->kanban->edit->requiredFields, 'notempty')
            ->where('id')->eq($kanbanID)
            ->exec();

        if(!dao::isError())
        {
            $this->file->saveUpload('kanban', $kanbanID);
            $this->file->updateObjectID($this->post->uid, $kanbanID, 'kanban');

            return common::createChanges($oldKanban, $kanban);
        }
    }

    /**
     * Close a kanban.
     *
     * @param  int    $kanbanID
     * @access public
     * @return array
     */
    function close($kanbanID)
    {
        $kanbanID  = (int)$kanbanID;
        $oldKanban = $this->getByID($kanbanID);
        $now       = helper::now();
        $kanban    = fixer::input('post')
            ->setDefault('status', 'closed')
            ->setDefault('closedBy', $this->app->user->account)
            ->setDefault('closedDate', $now)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->remove('comment')
            ->get();

        $this->dao->update(TABLE_KANBAN)->data($kanban)
            ->autoCheck()
            ->where('id')->eq($kanbanID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldKanban, $kanban);
    }

    /**
     * Add execution Kanban lanes and columns.
     *
     * @param  int    $executionID
     * @param  string $type all|story|bug|task
     * @param  string $groupBy default
     * @access public
     * @return void
     */
    public function createExecutionLane($executionID, $type = 'all', $groupBy = 'default')
    {
        if($groupBy == 'default' or $type == 'all')
        {
            foreach($this->config->kanban->default as $type => $lane)
            {
                $lane->type      = $type;
                $lane->execution = $executionID;
                $this->dao->insert(TABLE_KANBANLANE)->data($lane)->exec();

                $laneID = $this->dao->lastInsertId();
                $this->createExecutionColumns($laneID, $type, $executionID);
            }
        }
        else
        {
            $this->loadModel($type);
            $groupList = $this->getObjectGroup($executionID, $type, $groupBy);

            $objectPairs = array();
            if($groupBy == 'module')     $objectPairs = $this->dao->select('id,name')->from(TABLE_MODULE)->where('type')->in('story,bug,task')->andWhere('deleted')->eq('0')->fetchPairs();
            if($groupBy == 'story')      $objectPairs = $this->dao->select('id,title')->from(TABLE_STORY)->where('deleted')->eq(0)->fetchPairs();
            if($groupBy == 'assignedTo') $objectPairs = $this->loadModel('user')->getPairs('noletter');

            $laneName   = '';
            $laneOrder  = 5;
            $colorIndex = 0;
            foreach($groupList as $groupKey)
            {
                if($groupKey)
                {
                    if(strpos('module,story,assignedTo', $groupBy) !== false)
                    {
                        $laneName = zget($objectPairs, $groupKey);
                    }
                    else
                    {
                        $laneName = zget($this->lang->$type->{$groupBy . 'List'}, $groupKey);
                    }
                }
                else
                {
                    $laneName = $this->lang->kanban->noGroup;
                }

                $lane = new stdClass();
                $lane->execution = $executionID;
                $lane->type      = $type;
                $lane->groupby   = $groupBy;
                $lane->extra     = $groupKey;
                $lane->name      = $laneName;
                $lane->color     = $this->config->kanban->laneColorList[$colorIndex];
                $lane->order     = $laneOrder;

                $laneOrder  += 5;
                $colorIndex += 1;
                if($colorIndex == count($this->config->kanban->laneColorList) + 1) $colorIndex = 0;
                $this->dao->insert(TABLE_KANBANLANE)->data($lane)->exec();

                $laneID = $this->dao->lastInsertId();
                $this->createExecutionColumns($laneID, $type, $executionID, $groupBy, $groupKey);
            }
        }
    }

    /**
     * Create execution columns.
     *
     * @param  int|array   $laneID
     * @param  string      $type story|bug|task
     * @param  int         $executionID
     * @param  string      $groupBy
     * @param  string      $groupValue
     * @access public
     * @return void
     */
    public function createExecutionColumns($laneID, $type, $executionID)
    {
        $devColumnID = $testColumnID = $resolvingColumnID = 0;
        if($type == 'story')
        {
            foreach($this->lang->kanban->storyColumn as $colType => $name)
            {
                $data = new stdClass();
                $data->name  = $name;
                $data->color = '#333';
                $data->type  = $colType;

                if(strpos(',developing,developed,', $colType) !== false) $data->parent = $devColumnID;
                if(strpos(',testing,tested,', $colType) !== false)       $data->parent = $testColumnID;
                if(strpos(',develop,test,', $colType) !== false)         $data->parent = -1;

                $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();

                $colID = $this->dao->lastInsertId();
                if($colType == 'develop') $devColumnID  = $colID;
                if($colType == 'test')    $testColumnID = $colID;

                if(is_array($laneID))
                {
                    foreach($laneID as $id) $this->addKanbanCell($executionID, $id, $colID, 'story');
                }
                else
                {
                    $this->addKanbanCell($executionID, $laneID, $colID, 'story');
                }
            }
        }
        elseif($type == 'bug')
        {
            foreach($this->lang->kanban->bugColumn as $colType => $name)
            {
                $data = new stdClass();
                $data->name  = $name;
                $data->color = '#333';
                $data->type  = $colType;
                if(strpos(',fixing,fixed,', $colType) !== false)   $data->parent = $resolvingColumnID;
                if(strpos(',testing,tested,', $colType) !== false) $data->parent = $testColumnID;
                if(strpos(',resolving,test,', $colType) !== false) $data->parent = -1;

                $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();

                $colID = $this->dao->lastInsertId();
                if($colType == 'resolving') $resolvingColumnID = $colID;
                if($colType == 'test')      $testColumnID      = $colID;

                if(is_array($laneID))
                {
                    foreach($laneID as $id) $this->addKanbanCell($executionID, $id, $colID, 'bug');
                }
                else
                {
                    $this->addKanbanCell($executionID, $laneID, $colID, 'bug');
                }
            }
        }
        elseif($type == 'task')
        {
            foreach($this->lang->kanban->taskColumn as $colType => $name)
            {
                $data = new stdClass();
                $data->name  = $name;
                $data->color = '#333';
                $data->type  = $colType;
                if(strpos(',developing,developed,', $colType) !== false) $data->parent = $devColumnID;
                if($colType == 'develop') $data->parent = -1;

                $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();

                $colID = $this->dao->lastInsertId();
                if($colType == 'develop') $devColumnID = $colID;

                if(is_array($laneID))
                {
                    foreach($laneID as $id) $this->addKanbanCell($executionID, $id, $colID, 'task');
                }
                else
                {
                    $this->addKanbanCell($executionID, $laneID, $colID, 'task');
                }
            }
        }
    }

    /**
     * Add kanban cell for new lane.
     *
     * @param  int    $kanbanID
     * @param  int    $laneID
     * @param  int    $colID
     * @param  string $type story|task|bug|card
     * @access public
     * @return void
     */
    public function addKanbanCell($kanbanID, $laneID, $colID, $type, $cardID = 0)
    {
        $cell = $this->dao->select('id, cards')->from(TABLE_KANBANCELL)
            ->where('kanban')->eq($kanbanID)
            ->andWhere('lane')->eq($laneID)
            ->andWhere('`column`')->eq($colID)
            ->andWhere('type')->eq($type)
            ->fetch();

        if(empty($cell))
        {
            $cell = new stdclass();
            $cell->kanban = $kanbanID;
            $cell->lane   = $laneID;
            $cell->column = $colID;
            $cell->type   = $type;

            $this->dao->insert(TABLE_KANBANCELL)->data($cell)->exec();
        }
        else
        {
            $cell->cards = $cell->cards ? $cell->cards . "$cardID," : ",$cardID,";
            $this->dao->update(TABLE_KANBANCELL)->set('cards')->eq($cell->cards)->where('id')->eq($cell->id)->exec();
        }
    }

    /**
     * Create a default RD kanban.
     *
     * @param  object $execution
     * @access public
     * @return void
     */
    public function createRDKanban($execution)
    {
        $regionID =  $this->createRDRegion($execution);
        if(dao::isError()) return false;

        $groupID = $this->createGroup($execution->id, $regionID);
        if(dao::isError()) return false;

        $this->createRDLane($execution->id, $regionID);
        if(dao::isError()) return false;
    }

    /**
     * Create a default RD region.
     *
     * @param  object $execution
     *
     * @access public
     * @return int|bool
     */
    public function createRDRegion($execution)
    {
        $region = new stdclass();
        $region->name        = $this->lang->kanbanregion->default;
        $region->kanban      = $execution->id;
        $region->createdBy   = $this->app->user->account;
        $region->createdDate = helper::today();
        $region->order       = 1;

        $this->dao->insert(TABLE_KANBANREGION)->data($region)
            ->check('name', 'unique', "kanban={$execution->id} AND deleted='0'")
            ->autoCheck()
            ->exec();

        if(dao::isError()) return false;
        return $this->dao->lastInsertId();
    }

    /**
     * Create default RD lanes.
     *
     * @param  int    $executionID
     * @param  int    $regionID
     *
     * @access public
     * @return bool
     */
    public function createRDLane($executionID, $regionID)
    {
        $laneIndex = 0;
        foreach($this->lang->kanban->laneTypeList as $type => $name)
        {
            $groupID = $this->createGroup($executionID, $regionID);
            if(dao::isError()) return false;

            $lane = new stdclass();
            $lane->execution = $executionID;
            $lane->type      = $type;
            $lane->region    = $regionID;
            $lane->group     = $groupID;
            $lane->name      = $name;
            $lane->color     = $this->config->kanban->laneColorList[$laneIndex];
            $lane->order     = ++ $laneIndex * 5;

            $this->dao->insert(TABLE_KANBANLANE)->data($lane)->autoCheck()->exec();
            if(dao::isError()) return false;

            $this->createRDColumn($regionID, $groupID, $this->dao->lastInsertId(), $type, $executionID);
        }
    }

    /**
     * Create default RD columns.
     *
     * @param  int    $regionID
     * @param  int    $groupID
     * @param  int    $laneID
     * @param  string $laneType
     *
     * @access public
     * @return bool
     */
    public function createRDColumn($regionID, $groupID, $laneID, $laneType, $executionID)
    {
        $devColumnID = $testColumnID = $resolvingColumnID = 0;
        if($laneType == 'story') $columnList = $this->lang->kanban->storyColumn;
        if($laneType == 'bug') $columnList = $this->lang->kanban->bugColumn;
        if($laneType == 'task') $columnList = $this->lang->kanban->taskColumn;

        foreach($columnList as $type => $name)
        {
            $data = new stdClass();
            $data->name   = $name;
            $data->color  = '#333';
            $data->type   = $type;
            $data->group  = $groupID;
            $data->region = $regionID;

            if(strpos(',developing,developed,', $type) !== false) $data->parent = $devColumnID;
            if(strpos(',testing,tested,', $type) !== false) $data->parent = $testColumnID;
            if(strpos(',fixing,fixed,', $type) !== false) $data->parent = $resolvingColumnID;
            if(strpos(',develop,test,resolving,', $type) !== false) $data->parent = -1;

            $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();
            if(dao::isError()) return false;

            if($type == 'develop') $devColumnID  = $this->dao->lastInsertId();
            if($type == 'test')    $testColumnID = $this->dao->lastInsertId();
            if($type == 'resolving') $resolvingColumnID = $this->dao->lastInsertId();

            $this->addKanbanCell($executionID, $laneID, $this->dao->lastInsertId(), $laneType);
        }
    }

    /**
     * Update a region.
     *
     * @param  int    $regionID
     * @access public
     * @return array
     */
    public function updateRegion($regionID)
    {
        $region    = fixer::input('post')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->trim('name')
            ->get();
        $oldRegion = $this->getRegionById($regionID);

        $this->dao->update(TABLE_KANBANREGION)->data($region)
            ->autoCheck()
            ->batchcheck($this->config->kanban->editregion->requiredFields, 'notempty')
            ->where('id')->eq($regionID)
            ->exec();

        if(dao::isError()) return;

        $changes = common::createChanges($oldRegion, $region);
        return $changes;
    }

    /**
     * Update kanban lane.
     *
     * @param  int    $executionID
     * @param  string $laneType
     * @access public
     * @return void
     */
    public function updateLane($executionID, $laneType)
    {
        $lanes = $this->dao->select('*')->from(TABLE_KANBANLANE)
            ->where('execution')->eq($executionID)
            ->andWhere('type')->eq($laneType)
            ->fetchAll('id');

        foreach($lanes as $lane) $this->refreshCards($lane);
    }

    /**
     * Refresh column cards.
     *
     * @param  object $lane
     * @access public
     * @return void
     */
    public function refreshCards($lane)
    {
        $laneType    = $lane->type;
        $executionID = $lane->execution;
        $cardPairs   = $this->dao->select('t2.type, t1.cards')->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.`column` = t2.id')
            ->where('t1.kanban')->eq($executionID)
            ->andWhere('t1.lane')->eq($lane->id)
            ->fetchPairs();

        if($laneType == 'story')
        {
            $stories = $this->loadModel('story')->getExecutionStories($executionID);
            foreach($stories as $storyID => $story)
            {
                foreach($this->config->kanban->storyColumnStageList as $colType => $stage)
                {
                    if($story->stage != $stage and strpos($cardPairs[$colType], ",$storyID,") !== false)
                    {
                        $cardPairs[$colType] = str_replace(",$storyID,", ',', $cardPairs[$colType]);
                    }

                    if(strpos(',ready,backlog,develop,test,', $colType) !== false) continue;

                    if($story->stage == $stage and strpos($cardPairs[$colType], ",$storyID,") === false)
                    {
                        $cardPairs[$colType] = empty($cardPairs[$colType]) ? ",$storyID," : ",$storyID" . $cardPairs[$colType];
                    }
                }

                if($story->stage == 'projected' and strpos($cardPairs['ready'], ",$storyID,") === false and strpos($cardPairs['backlog'], ",$storyID,") === false)
                {
                    $cardPairs['backlog'] = empty($cardPairs['backlog']) ? ",$storyID," : ",$storyID" . $cardPairs['backlog'];
                }

            }
        }
        elseif($laneType == 'bug')
        {
            $bugs = $this->loadModel('bug')->getExecutionBugs($executionID);
            foreach($bugs as $bugID => $bug)
            {
                foreach($this->config->kanban->bugColumnStatusList as $colType => $status)
                {
                    if($bug->status != $status and strpos($cardPairs[$colType], ",$bugID,") !== false)
                    {
                        $cardPairs[$colType] = str_replace(",$bugID,", ',', $cardPairs[$colType]);
                    }

                    if(strpos(',resolving,test,testing,tested,', $colType) !== false) continue;

                    if($colType == 'unconfirmed' and $bug->status == $status and $bug->confirmed == 0 and strpos($cardPairs['unconfirmed'], ",$bugID,") === false and strpos($cardPairs['fixing'], ",$bugID,") === false and $bug->activatedCount == 0)
                    {
                        $cardPairs['unconfirmed'] = empty($cardPairs['unconfirmed']) ? ",$bugID," : ",$bugID" . $cardPairs['unconfirmed'];
                        if(strpos($cardPairs['closed'], ",$bugID,") !== false) $cardPairs['closed'] = str_replace(",$bugID,", ',', $cardPairs['closed']);
                    }
                    elseif($colType == 'confirmed' and $bug->status == $status and $bug->confirmed == 1 and strpos($cardPairs['confirmed'], ",$bugID,") === false and strpos($cardPairs['fixing'], ",$bugID,") === false and $bug->activatedCount == 0)
                    {
                        $cardPairs['confirmed'] = empty($cardPairs['confirmed']) ? ",$bugID," : ",$bugID" . $cardPairs['confirmed'];
                        if(strpos($cardPairs['unconfirmed'], ",$bugID,") !== false) $cardPairs['unconfirmed'] = str_replace(",$bugID,", ',', $cardPairs['unconfirmed']);
                    }
                    elseif($colType == 'fixing' and $bug->status == $status and $bug->activatedCount > 0 and strpos($cardPairs['fixing'], ",$bugID,") === false)
                    {
                        $cardPairs['fixing'] = empty($cardPairs['fixing']) ? ",$bugID," : ",$bugID" . $cardPairs['fixing'];
                        if(strpos($cardPairs['confirmed'], ",$bugID,") !== false)   $cardPairs['confirmed']   = str_replace(",$bugID,", ',', $cardPairs['confirmed']);
                        if(strpos($cardPairs['unconfirmed'], ",$bugID,") !== false) $cardPairs['unconfirmed'] = str_replace(",$bugID,", ',', $cardPairs['unconfirmed']);
                    }
                    elseif($colType == 'fixed' and $bug->status == $status and strpos($cardPairs['fixed'], ",$bugID,") === false and strpos($cardPairs['testing'], ",$bugID,") === false and strpos($cardPairs['tested'], ",$bugID,") === false)
                    {
                        $cardPairs['fixed'] = empty($cardPairs['fixed']) ? ",$bugID," : ",$bugID" . $cardPairs['fixed'];
                        if(strpos($cardPairs['testing'], ",$bugID,") !== false) $cardPairs['testing'] = str_replace(",$bugID,", ',', $cardPairs['testing']);
                        if(strpos($cardPairs['tested'], ",$bugID,") !== false)  $cardPairs['tested']  = str_replace(",$bugID,", ',', $cardPairs['tested']);
                    }
                    elseif($colType == 'closed' and $bug->status == 'closed' and strpos($cardPairs[$colType], ",$bugID,") === false)
                    {
                        $cardPairs[$colType] = empty($cardPairs[$colType]) ? ",$bugID," : ",$bugID". $cardPairs[$colType];
                    }
                }
            }
        }
        elseif($laneType == 'task')
        {
            $tasks = $this->loadModel('execution')->getKanbanTasks($executionID);
            foreach($tasks as $taskID => $task)
            {
                foreach($this->config->kanban->taskColumnStatusList as $colType => $status)
                {
                    if($colType == 'develop') continue;

                    if($task->status == $status and strpos($cardPairs[$colType], ",$taskID,") === false)
                    {
                        $cardPairs[$colType] = empty($cardPairs[$colType]) ? ",$taskID," : ",$taskID". $cardPairs[$colType];
                    }
                    elseif($task->status != $status and strpos($cardPairs[$colType], ",$taskID,") !== false)
                    {
                        $cardPairs[$colType] = str_replace(",$taskID,", ',', $cardPairs[$colType]);
                    }
                }
            }
        }

        $colPairs = $this->dao->select('t2.type, t2.id')->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.`column` = t2.id')
            ->where('t1.kanban')->eq($executionID)
            ->andWhere('t1.lane')->eq($lane->id)
            ->fetchPairs();

        foreach($cardPairs as $colType => $cards)
        {
            if(!isset($colPairs[$colType])) continue;
            $this->dao->update(TABLE_KANBANCELL)->set('cards')->eq($cards)->where('lane')->eq($lane->id)->andWhere('`column`')->eq($colPairs[$colType])->exec();
        }

        $this->dao->update(TABLE_KANBANLANE)->set('lastEditedTime')->eq(helper::now())->where('id')->eq($lane->id)->exec();
    }

    /**
     * Update lane column.
     *
     * @param  int    $columnID
     * @param  object $column
     * @access public
     * @return array
     */
    public function updateLaneColumn($columnID, $column)
    {
        $data = fixer::input('post')->trim('name')->get();

        $this->dao->update(TABLE_KANBANCOLUMN)->data($data)
            ->autoCheck()
            ->batchcheck($this->config->kanban->setlaneColumn->requiredFields, 'notempty')
            ->where('id')->eq($columnID)
            ->exec();

        if(dao::isError()) return;

        $changes = common::createChanges($column, $data);
        return $changes;
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
    public function updateLaneOrder($executionID, $currentType, $targetType)
    {
        $orderList = $this->dao->select('id,type,`order`')->from(TABLE_KANBANLANE)
            ->where('execution')->eq($executionID)
            ->andWhere('type')->in(array($currentType, $targetType))
            ->andWhere('groupby')->eq('')
            ->fetchAll('type');

        $this->dao->update(TABLE_KANBANLANE)->set('`order`')->eq($orderList[$targetType]->order)
            ->where('id')->eq($orderList[$currentType]->id)
            ->andWhere('groupby')->eq('')
            ->exec();

        $this->dao->update(TABLE_KANBANLANE)->set('`order`')->eq($orderList[$currentType]->order)
            ->where('id')->eq($orderList[$targetType]->id)
            ->andWhere('groupby')->eq('')
            ->exec();
    }

    /**
     * Update a card.
     *
     * @param  int    $cardID
     * @access public
     * @return array
     */
    public function updateCard($cardID)
    {
        if($this->post->estimate < 0)
        {
            dao::$errors[] = $this->lang->kanbancard->error->recordMinus;
            return false;
        }

        if($this->post->end && ($this->post->begin > $this->post->end))
        {
            dao::$errors[] = $this->lang->kanbancard->error->endSmall;
            return false;
        }

        $cardID  = (int)$cardID;
        $oldCard = $this->getCardByID($cardID);

        $now  = helper::now();
        $card = fixer::input('post')
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->trim('name')
            ->setDefault('estimate', $oldCard->estimate)
            ->setIF(!empty($this->post->assignedTo) and $oldCard->assignedTo != $this->post->assignedTo, 'assignedDate', $now)
            ->setIF(is_numeric($this->post->estimate), 'estimate', (float)$this->post->estimate)
            ->stripTags($this->config->kanban->editor->editcard['id'], $this->config->allowedTags)
            ->join('assignedTo', ',')
            ->remove('uid')
            ->get();

        if(isset($card->assignedTo))  $card->assignedTo = trim($card->assignedTo, ',');
        if(!isset($card->assignedTo)) $card->assignedTo = '';

        $card = $this->loadModel('file')->processImgURL($card, $this->config->kanban->editor->editcard['id'], $this->post->uid);

        $this->dao->update(TABLE_KANBANCARD)->data($card)
            ->autoCheck()
            ->checkIF($card->estimate != '', 'estimate', 'float')
            ->batchcheck($this->config->kanban->editcard->requiredFields, 'notempty')
            ->where('id')->eq($cardID)
            ->exec();

        if(!dao::isError())
        {
            $this->file->saveUpload('kanbancard', $cardID);
            $this->file->updateObjectID($this->post->uid, $cardID, 'kanbancard');

            return common::createChanges($oldCard, $card);
        }
    }

    /**
     * Set WIP limit.
     *
     * @param  int    $columnID
     * @access public
     * @return bool
     */
    public function setWIP($columnID)
    {
        $oldColumn = $this->getColumnById($columnID);
        $column    = fixer::input('post')->remove('WIPCount,noLimit')->get();
        if(!preg_match("/^-?\d+$/", $column->limit) or (!isset($_POST['noLimit']) and $column->limit <= 0))
        {
            dao::$errors['limit'] = $this->lang->kanban->error->mustBeInt;
            return false;
        }
        $column->limit = (int)$column->limit;

        /* Check column limit. */
        $sumChildLimit = 0;
        if($oldColumn->parent == -1 and $column->limit != -1)
        {
            $childColumns = $this->dao->select('id,`limit`')->from(TABLE_KANBANCOLUMN)->where('parent')->eq($columnID)->andWhere('deleted')->eq(0)->fetchAll();
            foreach($childColumns as $childColumn)
            {
                if($childColumn->limit == -1)
                {
                    dao::$errors['limit'] = $this->lang->kanban->error->parentLimitNote;
                    return false;
                }

                $sumChildLimit += $childColumn->limit;
            }

            if($sumChildLimit > $column->limit)
            {
                dao::$errors['limit'] = $this->lang->kanban->error->parentLimitNote;
                return false;
            }
        }
        elseif($oldColumn->parent > 0)
        {
            $parentColumn = $this->getColumnByID($oldColumn->parent);
            if($parentColumn->limit != -1)
            {
                $siblingLimit = $this->dao->select('`limit`')->from(TABLE_KANBANCOLUMN)
                    ->where('`parent`')->eq($oldColumn->parent)
                    ->andWhere('id')->ne($columnID)
                    ->fetch('limit');

                $sumChildLimit = $siblingLimit + $column->limit;

                if($column->limit == -1 or $siblingLimit == -1 or $sumChildLimit > $parentColumn->limit)
                {
                    dao::$errors['limit'] = $this->lang->kanban->error->childLimitNote;
                    return false;
                }
            }
        }

        $this->dao->update(TABLE_KANBANCOLUMN)->data($column)
            ->autoCheck()
            ->checkIF($column->limit != -1, 'limit', 'gt', 0)
            ->batchcheck($this->config->kanban->setwip->requiredFields, 'notempty')
            ->where('id')->eq($columnID)
            ->exec();

        return dao::isError();
    }

    /**
     * Set lane info.
     *
     * @param  int    $laneID
     * @access public
     * @return bool
     */
    public function setLane($laneID)
    {
        $lane = fixer::input('post')->trim('name')->get();

        $this->dao->update(TABLE_KANBANLANE)->data($lane)
            ->autoCheck()
            ->batchcheck($this->config->kanban->setlane->requiredFields, 'notempty')
            ->where('id')->eq($laneID)
            ->exec();

        return dao::isError();
    }

    /**
     * Set lane height.
     *
     * @param  int    $kanbanID
     * @param  string $from     kanban|execution
     * @access public
     * @return bool
     */
    public function setLaneHeight($kanbanID, $from = 'kanban')
    {
        $kanbanID = (int)$kanbanID;
        $kanban   = fixer::input('post')
            ->setIF($this->post->heightType == 'auto', 'displayCards', 0)
            ->get();

        if($kanban->heightType == 'custom')
        {
            if(!preg_match("/^-?\d+$/", $kanban->displayCards) or $kanban->displayCards < 3)
            {
                dao::$errors['displayCards'] = $this->lang->kanbanlane->error->mustBeInt;
                return false;
            }
        }

        $table = $this->config->objectTables[$from];
        $this->dao->update($table)->set('displayCards')->eq((int)$kanban->displayCards)->where('id')->eq($kanbanID)->exec();

        if(dao::isError()) return false;
    }

    /**
     * Set kanban headerActions.
     *
     * @param  object $kanban
     * @access public
     * @return void
     */
    public function setHeaderActions($kanban)
    {
        $printSetHeight = false;
        if(common::hasPriv('kanban', 'setLaneHeight'))
        {
            $laneCount = $this->dao->select('COUNT(t2.id) as count')->from(TABLE_KANBANREGION)->alias('t1')
                ->leftJoin(TABLE_KANBANLANE)->alias('t2')->on('t1.id=t2.region')
                ->where('t1.kanban')->eq($kanban->id)
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere('t2.deleted')->eq(0)
                ->fetch('count');

            if($laneCount > 1) $printSetHeight = true;
        }

        $actions  = '';
        $actions .= "<div class='btn-group'>";
        $actions .= "<a href='javascript:fullScreen();' id='fullScreenBtn' class='btn btn-link'><i class='icon icon-fullscreen'></i> {$this->lang->kanban->fullScreen}</a>";

        $printSettingBtn = (common::hasPriv('kanban', 'createRegion') or $printSetHeight or common::hasPriv('kanban', 'edit') or common::hasPriv('kanban', 'close') or common::hasPriv('kanban', 'delete'));

        if($printSettingBtn)
        {
            $actions .= "<a data-toggle='dropdown' class='btn btn-link dropdown-toggle setting' type='button'>" . '<i class="icon icon-cog-outline"></i> ' . $this->lang->kanban->setting . '</a>';
            $actions .= "<ul id='kanbanActionMenu' class='dropdown-menu text-left'>";
            if(common::hasPriv('kanban', 'createRegion')) $actions .= '<li>' . html::a(helper::createLink('kanban', 'createRegion', "kanbanID=$kanban->id", '', true), '<i class="icon icon-plus"></i>' . $this->lang->kanban->createRegion, '', "class='iframe btn btn-link'") . '</li>';
            if($printSetHeight)
            {
                $width    = $this->app->getClientLang() == 'en' ? '70%' : '60%';
                $actions .= '<li>' . html::a(helper::createLink('kanban', 'setLaneHeight', "kanbanID=$kanban->id", '', true), '<i class="icon icon-size-height"></i>' . $this->lang->kanban->laneHeight, '', "class='iframe btn btn-link' data-width='$width'") . '</li>';

            }

            $kanbanActions = '';
            $attr          = $kanban->status == 'closed' ? "disabled='disabled'" : '';
            if(common::hasPriv('kanban', 'edit'))  $kanbanActions .= '<li>' . html::a(helper::createLink('kanban', 'edit', "kanbanID=$kanban->id", '', true), '<i class="icon icon-edit"></i>' . $this->lang->kanban->edit, '', "class='iframe btn btn-link' data-width='75%'") . '</li>';
            if(common::hasPriv('kanban', 'close')) $kanbanActions .= '<li>' . html::a(helper::createLink('kanban', 'close', "kanbanID=$kanban->id", '', true), '<i class="icon icon-off"></i>' . $this->lang->kanban->close, '', "class='iframe btn btn-link' $attr") . '</li>';
            if(common::hasPriv('kanban', 'delete')) $kanbanActions .= '<li>' . html::a(helper::createLink('kanban', 'delete', "kanbanID=$kanban->id"), '<i class="icon icon-trash"></i>' . $this->lang->kanban->delete, 'hiddenwin', "class='btn btn-link'") . '</li>';
            if($kanbanActions)
            {
                $actions .= ((common::hasPriv('kanban', 'createRegion') or $printSetHeight) and (common::hasPriv('kanban', 'edit') or common::hasPriv('kanban', 'close') or common::hasPriv('kanban', 'delete'))) ? "<div class='divider'></div>" . $kanbanActions : $kanbanActions;
            }
            $actions .= "</ul>";
        }

        $actions .= "</div>";

        $this->lang->headerActions = $actions;
    }

    /**
     * Set switcher menu.
     *
     * @param  object $kanban
     * @access public
     * @return void
     */
    public function setSwitcher($kanban)
    {
        $currentModule = $this->app->getModuleName();
        $currentMethod = $this->app->getMethodName();

        $kanbanLink = helper::createLink('kanban', 'ajaxGetKanbanMenu', "objectID=$kanban->id&module=$currentModule&method=$currentMethod");

        $switcher  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$kanban->name}'><span class='text'>{$kanban->name}</span> <span class='caret'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$kanbanLink'>";
        $switcher .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
        $switcher .= "</div></div>";

        $this->lang->switcherMenu = $switcher;
    }

    /**
     * Sort kanban group.
     *
     * @param  int    $region
     * @param  array  $groups
     * @access public
     * @return bool
     */
    public function sortGroup($region, $groups)
    {
        $this->loadModel('action');

        $groupList = $this->getGroupList($region);

        $order = 1;
        foreach($groups as $groupID)
        {
            if(!$groupID) continue;
            if(!isset($groupList[$groupID])) continue;

            $this->dao->update(TABLE_KANBANGROUP)->set('`order`')->eq($order)->where('id')->eq($groupID)->exec();

            $order++;
        }

        return !dao::isError();
    }

    /**
     * Move a card.
     *
     * @param  int    $cardID
     * @param  int    $fromColID
     * @param  int    $toColID
     * @param  int    $fromLaneID
     * @param  int    $toLaneID
     * @access public
     * @return void
     */
    public function moveCard($cardID, $fromColID, $toColID, $fromLaneID, $toLaneID)
    {
        $fromCellCards = $this->dao->select('cards')->from(TABLE_KANBANCELL)->where('lane')->eq($fromLaneID)->andWhere('`column`')->eq($fromColID)->fetch('cards');
        $toCellCards   = $this->dao->select('cards')->from(TABLE_KANBANCELL)->where('lane')->eq($toLaneID)->andWhere('`column`')->eq($toColID)->fetch('cards');

        $fromCardList = str_replace("$cardID,", '', $fromCellCards);
        $toCardList   = rtrim($toCellCards, ',') . ",$cardID,";

        $this->dao->update(TABLE_KANBANCELL)->set('cards')->eq($fromCardList)->where('`column`')->eq($fromColID)->andWhere('lane')->eq($fromLaneID)->exec();
        $this->dao->update(TABLE_KANBANCELL)->set('cards')->eq($toCardList)->where('`column`')->eq($toColID)->andWhere('lane')->eq($toLaneID)->exec();
    }

    /**
     * Update a card's color.
     *
     * @param  int    $cardID
     * @param  int    $color
     * @access public
     * @return void
     */
    public function updateCardColor($cardID, $color)
    {
        $this->dao->update(TABLE_KANBANCARD)->set('`color`')->eq('#' . $color)->where('id')->eq($cardID)->exec();
    }

    /**
     * Reset order of lane.
     *
     * @param  int    $executionID
     * @param  int    $type
     * @param  int    $groupBy
     * @access public
     * @return void
     */
    public function resetLaneOrder($executionID, $type, $groupBy)
    {
        $lanes = $this->dao->select('id,extra')->from(TABLE_KANBANLANE)
            ->where('execution')->eq($executionID)
            ->andWhere('type')->eq($type)
            ->andWhere('groupBy')->eq($groupBy)
            ->orderBy('extra_asc')
            ->fetchPairs();

        $laneOrder = 5;
        $noExtra   = 0;

        foreach($lanes as $laneID => $extra)
        {
            if(!$extra)
            {
                $noExtra = $laneID;
                continue;
            }

            $this->dao->update(TABLE_KANBANLANE)->set('order')->eq($laneOrder)->where('id')->eq($laneID)->exec();
            $laneOrder += 5;
        }

        if($noExtra) $this->dao->update(TABLE_KANBANLANE)->set('order')->eq($laneOrder)->where('id')->eq($noExtra)->exec();
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
        $this->dao->update(TABLE_KANBANCOLUMN)
            ->set('archived')->eq(1)
            ->where('id')->eq($columnID)
            ->exec();
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
        $this->dao->update(TABLE_KANBANCOLUMN)
            ->set('archived')->eq(0)
            ->where('id')->eq($columnID)
            ->exec();
    }

    /**
     * Archive a card.
     *
     * @param  int    $cardID
     * @access public
     * @return array
     */
    public function archiveCard($cardID)
    {
        $oldCard = $this->getCardByID($cardID);

        $this->dao->update(TABLE_KANBANCARD)
            ->set('archived')->eq(1)
            ->set('archivedBy')->eq($this->app->user->account)
            ->set('archivedDate')->eq(helper::now())
            ->where('id')->eq($cardID)
            ->exec();

        $card = $this->getCardByID($cardID);

        if(!dao::isError()) return common::createChanges($oldCard, $card);
    }

    /**
     * Restore a card.
     *
     * @param  int    $cardID
     * @access public
     * @return array
     */
    public function restoreCard($cardID)
    {
        $oldCard = $this->getCardByID($cardID);

        $this->dao->update(TABLE_KANBANCARD)
            ->set('archived')->eq(0)
            ->set('archivedBy')->eq('')
            ->set('archivedDate')->eq('')
            ->where('id')->eq($cardID)
            ->exec();

        $card = $this->getCardByID($cardID);

        if(!dao::isError()) return common::createChanges($oldCard, $card);
    }

    /**
     * Get space by id.
     *
     * @param  int    $spaceID
     * @access public
     * @return array
     */
    public function getSpaceById($spaceID)
    {
        $space = $this->dao->findById($spaceID)->from(TABLE_KANBANSPACE)->fetch();
        $space = $this->loadModel('file')->replaceImgURL($space, 'desc');
        return $space;
    }

    /**
     * Get kanban group by space id list.
     *
     * @param  array|string $spaceIdList
     * @param  array|string $kanbanIdList
     * @access public
     * @return array
     */
    public function getGroupBySpaceList($spaceIdList, $kanbanIdList = '')
    {
        return $this->dao->select('*')->from(TABLE_KANBAN)
            ->where('deleted')->eq(0)
            ->andWhere('space')->in($spaceIdList)
            ->beginIF($kanbanIdList)->andWhere('id')->in($kanbanIdList)->fi()
            ->fetchGroup('space', 'id');
    }

    /**
     * Get group list by region.
     *
     * @param  int    $region
     * @access public
     * @return array
     */
    public function getGroupList($region)
    {
        return $this->dao->select('*')->from(TABLE_KANBANGROUP)
            ->where('region')->eq($region)
            ->orderBy('order')
            ->fetchAll('id');
    }

    /**
     * Get column by id.
     *
     * @param  int    $columnID
     * @access public
     * @return object
     */
    public function getColumnByID($columnID)
    {
        $column = $this->dao->select('t1.*, t2.type as laneType')->from(TABLE_KANBANCOLUMN)->alias('t1')
            ->leftjoin(TABLE_KANBANLANE)->alias('t2')->on('t1.group=t2.id')
            ->where('t1.id')->eq($columnID)
            ->fetch();

        if($column->parent > 0) $column->parentName = $this->dao->findById($column->parent)->from(TABLE_KANBANCOLUMN)->fetch('name');

        return $column;
    }

    /**
     * Get Column by column name.
     *
     * @param  string $name
     * @param  int    $laneID
     * @access public
     * @return object
     */
    public function getColumnByName($name, $laneID)
    {
        return $this->dao->select('*')
            ->from(TABLE_KANBANCOLUMN)
            ->where('name')->eq($name)
            ->andWhere('lane')->eq($laneID)
            ->fetch();
    }

    /**
     * Get columns by object id.
     *
     * @param  string $objectType parent|region|group
     * @param  int    $objectID
     * @param  string $archived
     * @param  string $deleted
     * @access public
     * @return array
     */
    public function getColumnsByObject($objectType = '', $objectID = 0, $archived = 0, $deleted = '0')
    {
        return $this->dao->select('*')->from(TABLE_KANBANCOLUMN)
            ->where(true)
            ->beginIF($objectType)->andWhere($objectType)->eq($objectID)->fi()
            ->beginIF($archived != '')->andWhere('archived')->eq($archived)->fi()
            ->beginIF($deleted != '')->andWhere('deleted')->eq($deleted)->fi()
            ->orderBy('order')
            ->fetchAll('id');
    }

    /**
     * Get lane by id.
     *
     * @param  int    $laneID
     * @access public
     * @return object
     */
    public function getLaneById($laneID)
    {
        return $this->dao->findById($laneID)->from(TABLE_KANBANLANE)->fetch();
    }

    /**
     * Get object group list.
     *
     * @param  int    $executionID
     * @param  string $type
     * @param  string $groupBy
     * @access public
     * @return array
     */
    public function getObjectGroup($executionID, $type, $groupBy)
    {
        $table = zget($this->config->objectTables, $type);

        if($groupBy == 'story' or $type == 'story')
        {
            $selectField = $groupBy == 'story' ? "t1.$groupBy" : "t2.$groupBy";
            $groupList   = $this->dao->select($selectField)->from(TABLE_PROJECTSTORY)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
                ->where('t1.project')->eq($executionID)
                ->andWhere('t2.deleted')->eq(0)
                ->orderBy($groupBy . '_desc')
                ->fetchPairs();

            if($type == 'task')
            {
                $unlinkedTask = $this->dao->select('id')->from(TABLE_TASK)
                    ->where('execution')->eq($executionID)
                    ->andWhere('parent')->ge(0)
                    ->andWhere('story')->eq(0)
                    ->andWhere('deleted')->eq(0)
                    ->fetch('id');
                if($unlinkedTask) $groupList[0] = 0;
            }
        }
        else
        {
            $groupList = $this->dao->select($groupBy)->from($table)
                ->where('execution')->eq($executionID)
                ->beginIF($type == 'task')->andWhere('parent')->ge(0)->fi()
                ->andWhere('deleted')->eq(0)
                ->orderBy($groupBy . '_desc')
                ->fetchPairs();
        }

        return $groupList;
    }

    /**
     * Get card by id.
     *
     * @param  int    $cardID
     * @access public
     * @return object
     */
    public function getCardByID($cardID)
    {
        $card = $this->dao->select('*')->from(TABLE_KANBANCARD)->where('id')->eq($cardID)->fetch();
        $card = $this->loadModel('file')->replaceImgURL($card, 'desc');

        return $card;
    }

    /**
     * Get cards by object id.
     *
     * @param  string $objectType kanban|region|group|lane|column
     * @param  int    $objectID
     * @param  string $archived
     * @param  string $deleted
     * @access public
     * @return array
     */
    public function getCardsByObject($objectType = '', $objectID = 0, $archived = 0, $deleted = '0')
    {
        return $this->dao->select('*')->from(TABLE_KANBANCARD)
            ->where(true)
            ->beginIF($objectType)->andWhere($objectType)->eq($objectID)->fi()
            ->beginIF($archived != '')->andWhere('archived')->eq($archived)->fi()
            ->beginIF($deleted != '')->andWhere('deleted')->eq($deleted)->fi()
            ->orderBy('order')
            ->fetchAll('id');
    }

    /**
     * Get Kanban cards menus by execution id.
     *
     * @param  int    $executionID
     * @param  array  $objects
     * @param  string $objecType story|bug|task
     * @access public
     * @return array
     */
    public function getKanbanCardMenu($executionID, $objects, $objecType)
    {
        $this->app->loadLang('execution');

        $menus = array();
        switch ($objecType)
        {
            case 'story':
                if(!isset($this->story)) $this->loadModel('story');

                $objects = $this->story->mergeReviewer($objects);
                foreach($objects as $story)
                {
                    $menu = array();

                    $toTaskPriv = strpos('draft,closed', $story->status) !== false ? false : true;
                    if(common::hasPriv('story', 'edit') and $this->story->isClickable($story, 'edit'))         $menu[] = array('label' => $this->lang->story->edit, 'icon' => 'edit', 'url' => helper::createLink('story', 'edit', "storyID=$story->id", '', true), 'size' => '95%');
                    if(common::hasPriv('story', 'change') and $this->story->isClickable($story, 'change'))     $menu[] = array('label' => $this->lang->story->change, 'icon' => 'alter', 'url' => helper::createLink('story', 'change', "storyID=$story->id", '', true), 'size' => '95%');
                    if(common::hasPriv('story', 'review') and $this->story->isClickable($story, 'review'))     $menu[] = array('label' => $this->lang->story->review, 'icon' => 'search', 'url' => helper::createLink('story', 'review', "storyID=$story->id", '', true), 'size' => '95%');
                    if(common::hasPriv('task', 'create') and $toTaskPriv)                                      $menu[] = array('label' => $this->lang->execution->wbs, 'icon' => 'plus', 'url' => helper::createLink('task', 'create', "executionID=$executionID&storyID=$story->id&moduleID=$story->module", '', true), 'size' => '95%');
                    if(common::hasPriv('task', 'batchCreate') and $toTaskPriv)                                 $menu[] = array('label' => $this->lang->execution->batchWBS, 'icon' => 'pluses', 'url' => helper::createLink('task', 'batchCreate', "executionID=$executionID&storyID=$story->id&moduleID=0&taskID=0&iframe=true", '', true), 'size' => '95%');
                    if(common::hasPriv('story', 'activate') and $this->story->isClickable($story, 'activate')) $menu[] = array('label' => $this->lang->story->activate, 'icon' => 'magic', 'url' => helper::createLink('story', 'activate', "storyID=$story->id", '', true));
                    if(common::hasPriv('execution', 'unlinkStory'))                                            $menu[] = array('label' => $this->lang->execution->unlinkStory, 'icon' => 'unlink', 'url' => helper::createLink('execution', 'unlinkStory', "executionID=$executionID&storyID=$story->story&confirm=no", '', true));

                    $menus[$story->id] = $menu;
                }
                break;
            case 'bug':
                if(!isset($this->bug)) $this->loadModel('bug');

                foreach($objects as $bug)
                {
                    $menu = array();

                    if(common::hasPriv('bug', 'edit') and $this->bug->isClickable($bug, 'edit'))             $menu[] = array('label' => $this->lang->bug->edit, 'icon' => 'edit', 'url' => helper::createLink('bug', 'edit', "bugID=$bug->id", '', true), 'size' => '95%');
                    if(common::hasPriv('bug', 'confirmBug') and $this->bug->isClickable($bug, 'confirmBug')) $menu[] = array('label' => $this->lang->bug->confirmBug, 'icon' => 'ok', 'url' => helper::createLink('bug', 'confirmBug', "bugID=$bug->id", '', true));
                    if(common::hasPriv('bug', 'resolve') and $this->bug->isClickable($bug, 'resolve'))       $menu[] = array('label' => $this->lang->bug->resolve, 'icon' => 'checked', 'url' => helper::createLink('bug', 'resolve', "bugID=$bug->id", '', true));
                    if(common::hasPriv('bug', 'close') and $this->bug->isClickable($bug, 'close'))           $menu[] = array('label' => $this->lang->bug->close, 'icon' => 'plus', 'url' => helper::createLink('bug', 'close', "bugID=$bug->id", '', true));
                    if(common::hasPriv('bug', 'create') and $this->bug->isClickable($bug, 'create'))         $menu[] = array('label' => $this->lang->bug->copy, 'icon' => 'pluses', 'url' => helper::createLink('bug', 'create', "productID=$bug->product&branch=$bug->branch&extras=bugID=$bug->id", '', true), 'size' => '95%');
                    if(common::hasPriv('bug', 'activate') and $this->bug->isClickable($bug, 'activate'))     $menu[] = array('label' => $this->lang->bug->activate, 'icon' => 'magic', 'url' => helper::createLink('bug', 'activate', "bugID=$bug->id", '', true));
                    if(common::hasPriv('story', 'create') and $bug->status != 'closed')                      $menu[] = array('label' => $this->lang->bug->toStory, 'icon' => 'unlink', 'url' => helper::createLink('story', 'create', "product=$bug->product&branch=$bug->branch&module=0&story=0&execution=0&bugID=$bug->id", '', true), 'size' => '95%');

                    $menus[$bug->id] = $menu;
                }
                break;
            case 'task':
                if(!isset($this->task)) $this->loadModel('task');

                foreach($objects as $task)
                {
                    $menu = array();

                    if(common::hasPriv('task', 'edit') and $this->task->isClickable($task, 'edit'))                     $menu[] = array('label' => $this->lang->task->edit, 'icon' => 'edit', 'url' => helper::createLink('task', 'edit', "taskID=$task->id", '', true), 'size' => '95%');
                    if(common::hasPriv('task', 'pause') and $this->task->isClickable($task, 'pause'))                   $menu[] = array('label' => $this->lang->task->pause, 'icon' => 'ok', 'url' => helper::createLink('task', 'pause', "taskID=$task->id", '', true));
                    if(common::hasPriv('task', 'restart') and $this->task->isClickable($task, 'restart'))               $menu[] = array('label' => $this->lang->task->restart, 'icon' => 'play', 'url' => helper::createLink('task', 'restart', "taskID=$task->id", '', true));
                    if(common::hasPriv('task', 'recordEstimate') and $this->task->isClickable($task, 'recordEstimate')) $menu[] = array('label' => $this->lang->task->recordEstimate, 'icon' => 'time', 'url' => helper::createLink('task', 'recordEstimate', "taskID=$task->id", '', true));
                    if(common::hasPriv('task', 'activate') and $this->task->isClickable($task, 'activate'))             $menu[] = array('label' => $this->lang->task->activate, 'icon' => 'magic', 'url' => helper::createLink('task', 'activate', "taskID=$task->id", '', true));
                    if(common::hasPriv('task', 'batchCreate') and $this->task->isClickable($task, 'batchCreate'))       $menu[] = array('label' => $this->lang->task->children, 'icon' => 'split', 'url' => helper::createLink('task', 'batchCreate', "execution=$task->execution&storyID=$task->story&moduleID=$task->module&taskID=$task->id", '', true), 'size' => '95%');
                    if(common::hasPriv('task', 'create') and $this->task->isClickable($task, 'create'))                 $menu[] = array('label' => $this->lang->task->copy, 'icon' => 'copy', 'url' => helper::createLink('task', 'create', "projctID=$task->execution&storyID=$task->story&moduleID=$task->module&taskID=$task->id", '', true), 'size' => '95%');
                    if(common::hasPriv('task', 'cancel') and $this->task->isClickable($task, 'cancel'))                 $menu[] = array('label' => $this->lang->task->cancel, 'icon' => 'ban-circle', 'url' => helper::createLink('task', 'cancel', "taskID=$task->id", '', true));

                    $menus[$task->id] = $menu;
                }
                break;
        }
        return $menus;
    }

    /**
     * Check if user can execute an action.
     *
     * @param  object $object
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickable($object, $action)
    {
        $action    = strtolower($action);
        $clickable = commonModel::hasPriv('kanban', $action);
        if(!$clickable) return false;

        switch($action)
        {
            case 'sortlane' :
            case 'deletelane' :
                if($object->deleted != '0') return false;

                $count = $this->dao->select('COUNT(id) AS count')->from(TABLE_KANBANLANE)
                    ->where('deleted')->eq('0')
                    ->andWhere('region')->eq($object->region)
                    ->beginIF($action == 'sortlane')->andWhere('`group`')->eq($object->group)->fi()
                    ->fetch('count');
                return $count > 1;
            case 'splitcolumn' :
                if($object->parent) return false;   // The current column is a child column.

                $count = $this->dao->select('COUNT(id) AS count')->from(TABLE_KANBANCOLUMN)
                    ->where('parent')->eq($object->id)
                    ->andWhere('deleted')->eq('0')
                    ->andWhere('archived')->eq('0')
                    ->fetch('count');
                return $count == 0;     // The column has child columns.
            case 'restorecolumn' :
                if($object->parent > 0)
                {
                    $parent = $this->getColumnByID($object->parent);
                    if($parent->deleted == '1' || $parent->archived == '1') return false;
                }
                return $object->archived == '1';
            case 'archivecolumn' :
                if($object->archived != '0') return false;    // The column has been archived.
            case 'deletecolumn' :
                if($object->deleted != '0') return false;

                if($object->parent > 0)
                {
                    $childrenCount = $this->dao->select('COUNT(id) AS count')->from(TABLE_KANBANCOLUMN)
                        ->where('parent')->eq($object->parent)
                        ->andWhere('deleted')->eq('0')
                        ->andWhere('archived')->eq('0')
                        ->fetch('count');

                    return $childrenCount > 2;
                }


                $count = $this->dao->select('COUNT(id) AS count')->from(TABLE_KANBANCOLUMN)
                    ->where('region')->eq($object->region)
                    ->andWhere('parent')->in('0,-1')
                    ->andWhere('`group`')->eq($object->group)
                    ->andWhere('deleted')->eq('0')
                    ->andWhere('archived')->eq('0')
                    ->fetch('count');

                return $count > 1;
        }

        return true;
    }
}

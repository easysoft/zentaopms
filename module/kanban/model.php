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
     * Init a kanban.
     * 
     * @param  int    $kanbanID 
     * @param  string $type default|new
     * @access public
     * @return void
     */
    public function initKanban($kanbanID, $type = 'default')
    {
        $kanban = $this->getByID($kanbanID);

        $function = $type == 'default' ? 'createDefaultRegion' : 'createRegion';
        $regionID = $this->$function($kanban);
        if(dao::isError()) return false;

        $groupID = $this->createGroup($kanban->id, $regionID);
        if(dao::isError()) return false;

        $this->createDefaultLane($kanban, $regionID, $groupID);
        if(dao::isError()) return false;

        $this->createDefaultColumns($kanban, $regionID, $groupID);
        if(dao::isError()) return false;
    }

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
        $maxOrder = $this->dao->select('MAX(`order`) AS maxOrder')->from(TABLE_KANBANORDER)
            ->where('objectType')->eq('group')
            ->andWhere('parentID')->eq($regionID)
            ->andWhere('parentType')->eq('region')
            ->andWhere('account')->eq('')
            ->fetch('maxOrder');

        $order = $maxOrder ? $maxOrder + 1 : 1;

        $group = new stdclass();
        $group->kanban = $kanbanID;
        $group->region = $regionID;

        $this->dao->insert(TABLE_KANBANGROUP)->data($group)->autoCheck()->exec();
        if(dao::isError()) return false;

        $groupID = $this->dao->lastInsertID();
        $this->saveOrder($regionID, 'region', $groupID, 'group', '', $order);

        return $groupID;
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
     * @access public
     * @return int 
     */
    public function createRegion($kanban, $region = null)
    {
        $account = $this->app->user->account;
        $order   = 1;

        if(!$region)
        {    
            $maxOrder = $this->dao->select('MAX(`order`) AS maxOrder')->from(TABLE_KANBANORDER)
                ->where('objectType')->eq('region')
                ->andWhere('parentID')->eq($kanban->id)
                ->andWhere('parentType')->eq('kanban')
                ->andWhere('account')->eq('')
                ->fetch('maxOrder');

            $order = $maxOrder + 1;

            $region = fixer::input('post')
                ->add('kanban', $kanban->id)
                ->add('space', $kanban->space)
                ->add('createdBy', $account)
                ->add('createdDate', helper::today())
                ->get();
        }

        $this->dao->insert(TABLE_KANBANREGION)->data($region)
            ->batchCheck($this->config->kanban->require->createregion, 'notempty')
            ->check('name', 'unique', "kanban = {$kanban->id} AND deleted = '0'")
            ->autoCheck()
            ->exec();

        $regionID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('kanbanRegion', $regionID, 'Created');
        $this->saveOrder($kanban->id, 'kanban', $regionID, 'region', $account, $order);

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

        $this->dao->insert(TABLE_KANBANLANE)->data($lane)->exec();
        $laneID = $this->dao->lastInsertId();

        $this->saveOrder($regionID, 'region', $laneID, 'lane', '', 1);
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
        $index = 1;
        foreach($this->lang->kanban->defaultColumn as $columnName)
        {
            $column = new stdclass();
            $column->region = $regionID;
            $column->group  = $groupID;
            $column->name   = $columnName;
            $column->type   = $index;
            $column->limit  = -1;

            $this->dao->insert(TABLE_KANBANCOLUMN)->data($column)->exec();

            $this->saveOrder($regionID, 'region', $this->dao->lastInsertID(), 'column', '', $index);
            $index ++;
        }

        return !dao::isError();
    }

    /**
     * Save kanban object order.
     * 
     * @param  int    $parentID 
     * @param  string $parentType 
     * @param  int    $objectID 
     * @param  string $objectType 
     * @param  string $account 
     * @param  int    $order 
     * @access public
     * @return void
     */
    public function saveOrder($parentID, $parentType, $objectID, $objectType, $account, $order)
    {
        $kanbanOrder = new stdclass();
        $kanbanOrder->parentID   = $parentID;
        $kanbanOrder->parentType = $parentType;
        $kanbanOrder->objectID   = $objectID;
        $kanbanOrder->objectType = $objectType;
        $kanbanOrder->account    = $account;
        $kanbanOrder->order      = $order;

        $this->dao->insert(TABLE_KANBANORDER)->data($kanbanOrder)->exec();
        return !dao::isError();
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
        return $this->dao->findByID($kanbanID)->from(TABLE_KANBAN)->fetch();
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
        //$taskGroup   = $this->getTaskGroupByProject($kanbanID);

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

                foreach($lanes as $lane) $lane->items = isset($taskGroup[$lane->id]) ? $taskGroup[$lane->id] : array();

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
            ->orderBy('id_asc')
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
            ->orderBy('id_asc')
            ->fetchGroup('region');
    }

    /**
     * Get lane group by regions.
     * 
     * @param  array $regions 
     * @access public
     * @return array 
     */
    public function getLaneGroupByRegions($regions)
    {
        $laneGroup = $this->dao->select('*')->from(TABLE_KANBANLANE)
            ->where('deleted')->eq('0')
            ->andWhere('region')->in($regions)
            ->orderBy('order')
            ->fetchGroup('group');

        $actions = array('editLane', 'sortLane', 'deleteLane');
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
     * @param  array $regions 
     * @access public
     * @return array 
     */
    public function getColumnGroupByRegions($regions)
    {
        $columnGroup = $this->dao->select("*")->from(TABLE_KANBANCOLUMN)
            ->where('deleted')->eq('0')
            //->andWhere('archived')->eq('0')
            ->andWhere('region')->in($regions)
            ->orderBy('order')
            ->fetchGroup('group', 'id');

        $actions = array('createColumn', 'copyColumn', 'editColumn', 'splitColumn', 'setWIP', 'archiveColumn', 'restoreColumn', 'deleteColumn');

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

                if($column->parent) continue;

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
        if($browseType != 'all' and $groupBy != 'default') $this->updateGroupLanes($executionID, $browseType, $groupBy);

        $lanes = $this->dao->select('*')->from(TABLE_KANBANLANE)
            ->where('execution')->eq($executionID)
            ->andWhere('deleted')->eq(0)
            ->beginIF($browseType != 'all')->andWhere('type')->eq($browseType)->fi()
            ->beginIF($groupBy == 'default')->andWhere('groupby')->eq('')->fi()
            ->beginIF($groupBy != 'default')->andWhere('groupby')->eq($groupBy)->fi()
            ->orderBy('order_asc')
            ->fetchAll('id');

        if(empty($lanes)) return array();

        foreach($lanes as $lane) $this->updateCards($lane);

        $columns = $this->dao->select('*')->from(TABLE_KANBANCOLUMN)
            ->where('deleted')->eq(0)
            ->andWhere('lane')->in(array_keys($lanes))
            ->orderBy('id_asc')
            ->fetchGroup('lane', 'id');

        /* Get parent column type pairs. */
        $parentTypes = $this->dao->select('id, type')->from(TABLE_KANBANCOLUMN)
            ->where('deleted')->eq(0)
            ->andWhere('lane')->in(array_keys($lanes))
            ->andWhere('parent')->eq(-1)
            ->fetchPairs('id', 'type');

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
            $laneType   = $groupBy == 'default' ? $lane->type : $lane->groupby;

            $laneData['id']              = $groupBy == 'default' ? $lane->type : $lane->groupby . '-' . $lane->extra;
            $laneData['laneID']          = $laneID;
            $laneData['name']            = $lane->name;
            $laneData['color']           = $lane->color;
            $laneData['order']           = $lane->order;
            $laneData['defaultCardType'] = $lane->type;

            foreach($columns[$laneID] as $columnID => $column)
            {
                $columnData[$column->id]['id']         = $laneType . '-' . $column->type;
                $columnData[$column->id]['columnID']   = $columnID;
                $columnData[$column->id]['type']       = $column->type;
                $columnData[$column->id]['name']       = $column->name;
                $columnData[$column->id]['color']      = $column->color;
                $columnData[$column->id]['limit']      = $column->limit;
                $columnData[$column->id]['laneType']   = $lane->type;
                $columnData[$column->id]['asParent']   = $column->parent == -1 ? true : false;

                if($column->parent > 0)
                {
                    $columnData[$column->id]['parentType'] = zget($parentTypes, $column->parent, '');
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

            $kanbanGroup[$laneType]['id']              = $laneType;
            $kanbanGroup[$laneType]['columns']         = array_values($columnData);
            $kanbanGroup[$laneType]['lanes'][]         = $laneData;
            $kanbanGroup[$laneType]['defaultCardType'] = $lane->type;
        }

        return $kanbanGroup;
    }

    /**
     * Create a space.
     *
     * @access public
     * @return int
     */
    public function createSpace()
    {
        $space = fixer::input('post')
            ->setDefault('createdBy', $this->app->user->account)
            ->setDefault('createdDate', helper::now())
            ->join('whitelist', ',')
            ->join('team', ',')
            ->remove('uid,contactListMenu')
            ->get();

        $this->dao->insert(TABLE_KANBANSPACE)->data($space)
            ->autoCheck()
            ->batchCheck($this->config->kanban->createspace->requiredFields, 'notempty')
            ->exec();

        if(!dao::isError())
        {
            $spaceID = $this->dao->lastInsertID();
            $this->saveOrder(0, '', $spaceID, 'space', '', $spaceID);

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
        $oldSpace = $this->dao->findById($spaceID)->from(TABLE_KANBANSPACE)->fetch();
        $space    = fixer::input('post')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->join('whitelist', ',')
            ->join('team', ',')
            ->remove('uid,contactListMenu')
            ->get();

        $this->dao->update(TABLE_KANBANSPACE)->data($space)
            ->autoCheck()
            ->batchCheck($this->config->kanban->editspace->requiredFields, 'notempty')
            ->where('id')->eq($spaceID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldSpace, $space);
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
     * createColumn
     *
     * @param  int    $laneID
     * @param  string $type story|bug|task
     * @param  int    $executionID
     * @param  string $groupBy
     * @param  string $groupValue
     * @access public
     * @return void
     */
    public function createExecutionColumns($laneID, $type, $executionID, $groupBy = '', $groupValue = '')
    {
        $objects = array();

        if($type == 'story') $objects = $this->loadModel('story')->getExecutionStories($executionID, 0, 0, 't2.id_desc');
        if($type == 'bug')   $objects = $this->loadModel('bug')->getExecutionBugs($executionID);
        if($type == 'task')  $objects = $this->loadModel('execution')->getKanbanTasks($executionID);

        if(!empty($groupBy))
        {
            foreach($objects as $objectID => $object)
            {
                if($object->$groupBy != $groupValue) unset($objects[$objectID]);
            }
        }

        $devColumnID = $testColumnID = $resolvingColumnID = 0;
        if($type == 'story')
        {
            foreach($this->lang->kanban->storyColumn as $colType => $name)
            {
                $data = new stdClass();
                $data->lane  = $laneID;
                $data->name  = $name;
                $data->color = '#333';
                $data->type  = $colType;
                $data->cards = '';

                if(strpos(',developing,developed,', $colType) !== false) $data->parent = $devColumnID;
                if(strpos(',testing,tested,', $colType) !== false) $data->parent = $testColumnID;
                if(strpos(',develop,test,', $colType) !== false) $data->parent = -1;
                if(strpos(',ready,develop,test,', $colType) === false)
                {
                    $storyStatus = $this->config->kanban->storyColumnStatusList[$colType];
                    $storyStage  = $this->config->kanban->storyColumnStageList[$colType];
                    foreach($objects as $storyID => $story)
                    {
                        if($story->status == $storyStatus and $story->stage == $storyStage) $data->cards .= $storyID . ',';
                    }
                    if(!empty($data->cards)) $data->cards = ',' . $data->cards;
                }

                $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();
                if($colType == 'develop') $devColumnID  = $this->dao->lastInsertId();
                if($colType == 'test')    $testColumnID = $this->dao->lastInsertId();
            }
        }
        elseif($type == 'bug')
        {
            foreach($this->lang->kanban->bugColumn as $colType => $name)
            {
                $data = new stdClass();
                $data->lane  = $laneID;
                $data->name  = $name;
                $data->color = '#333';
                $data->type  = $colType;
                $data->cards = '';
                if(strpos(',fixing,fixed,', $colType) !== false) $data->parent = $resolvingColumnID;
                if(strpos(',testing,tested,', $colType) !== false) $data->parent = $testColumnID;
                if(strpos(',resolving,test,', $colType) !== false) $data->parent = -1;
                if(strpos(',resolving,fixing,test,testing,tested,', $colType) === false)
                {
                    $bugStatus = $this->config->kanban->bugColumnStatusList[$colType];
                    foreach($objects as $bugID => $bug)
                    {
                        if($colType == 'unconfirmed' and $bug->status == $bugStatus and $bug->confirmed == 0)
                        {
                            $data->cards .= $bugID . ',';
                        }
                        elseif($colType == 'confirmed' and $bug->status == $bugStatus and $bug->confirmed == 1)
                        {
                            $data->cards .= $bugID . ',';
                        }
                        elseif(strpos(',unconfirmed,confirmed,', $colType) === false and $bug->status == $bugStatus)
                        {
                            $data->cards .= $bugID . ',';
                        }
                    }
                    if(!empty($data->cards)) $data->cards = ',' . $data->cards;
                }
                $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();
                if($colType == 'resolving') $resolvingColumnID = $this->dao->lastInsertId();
                if($colType == 'test')      $testColumnID      = $this->dao->lastInsertId();
            }
        }
        elseif($type == 'task')
        {
            foreach($this->lang->kanban->taskColumn as $colType => $name)
            {
                $data = new stdClass();
                $data->lane  = $laneID;
                $data->name  = $name;
                $data->color = '#333';
                $data->type  = $colType;
                $data->cards = '';
                if(strpos(',developing,developed,', $colType) !== false) $data->parent = $devColumnID;
                if($colType == 'develop') $data->parent = -1;
                if(strpos(',develop,', $colType) === false)
                {
                    $taskStatus = $this->config->kanban->taskColumnStatusList[$colType];
                    foreach($objects as $taskID => $task)
                    {
                        if($task->status == $taskStatus) $data->cards .= $taskID . ',';

                    }
                    if(!empty($data->cards)) $data->cards = ',' . $data->cards;
                }
                $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();
                if($colType == 'develop') $devColumnID = $this->dao->lastInsertId();
            }
        }
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

        foreach($lanes as $lane) $this->updateCards($lane);
    }

    /**
     * Update column cards.
     *
     * @param  object $lane
     * @access public
     * @return void
     */
    public function updateCards($lane)
    {
        $laneType    = $lane->type;
        $executionID = $lane->execution;
        $cardPairs = $this->dao->select('*')->from(TABLE_KANBANCOLUMN)
            ->where('deleted')->eq(0)
            ->andWhere('lane')->eq($lane->id)
            ->fetchPairs('type' ,'cards');

        if($laneType == 'story')
        {
            $stories = $this->loadModel('story')->getExecutionStories($executionID);
            foreach($stories as $storyID => $story)
            {
                foreach($this->config->kanban->storyColumnStageList as $colType => $stage)
                {
                    if(strpos(',ready,develop,test,', $colType) !== false) continue;

                    if($lane->groupby and $story->{$lane->groupby} != $lane->extra)
                    {
                        $cardPairs[$colType] = str_replace(",$storyID,", ',', $cardPairs[$colType]);
                    }
                    elseif($colType == 'backlog' and $story->stage == $stage and strpos($cardPairs['ready'], ",$storyID,") === false and strpos($cardPairs['backlog'], ",$storyID,") === false)
                    {
                        $cardPairs['backlog'] = empty($cardPairs['backlog']) ? ",$storyID," : ",$storyID" . $cardPairs['backlog'];
                    }
                    elseif($story->stage == $stage and strpos($cardPairs[$colType], ",$storyID,") === false)
                    {
                        $cardPairs[$colType] = empty($cardPairs[$colType]) ? ",$storyID," : ",$storyID" . $cardPairs[$colType];
                    }
                    elseif($story->stage != $stage and strpos($cardPairs[$colType], ",$storyID,") !== false)
                    {
                        $cardPairs[$colType] = str_replace(",$storyID,", ',', $cardPairs[$colType]);
                    }
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
                    if(strpos(',resolving,fixing,test,testing,tested,', $colType) !== false) continue;

                    if($lane->groupby and $bug->{$lane->groupby} != $lane->extra)
                    {
                        $cardPairs[$colType] = str_replace(",$bugID,", ',', $cardPairs[$colType]);
                    }
                    elseif($colType == 'unconfirmed' and $bug->status == $status and $bug->confirmed == 0 and strpos($cardPairs['unconfirmed'], ",$bugID,") === false and strpos($cardPairs['fixing'], ",$bugID,") === false)
                    {
                        $cardPairs['unconfirmed'] = empty($cardPairs['unconfirmed']) ? ",$bugID," : ",$bugID" . $cardPairs['unconfirmed'];
                        if(strpos($cardPairs['closed'], ",$bugID,") !== false) $cardPairs['closed'] = str_replace(",$bugID,", ',', $cardPairs['closed']);
                    }
                    elseif($colType == 'confirmed' and $bug->status == $status and $bug->confirmed == 1 and strpos($cardPairs['confirmed'], ",$bugID,") === false and strpos($cardPairs['fixing'], ",$bugID,") === false)
                    {
                        $cardPairs['confirmed'] = empty($cardPairs['confirmed']) ? ",$bugID," : ",$bugID" . $cardPairs['confirmed'];
                        if(strpos($cardPairs['unconfirmed'], ",$bugID,") !== false) $cardPairs['unconfirmed'] = str_replace(",$bugID,", ',', $cardPairs['unconfirmed']);
                    }
                    elseif($colType == 'fixed' and $bug->status == $status and strpos($cardPairs['fixed'], ",$bugID,") === false and strpos($cardPairs['testing'], ",$bugID,") === false and strpos($cardPairs['tested'], ",$bugID,") === false)
                    {
                        $cardPairs['fixed'] = empty($cardPairs['fixed']) ? ",$bugID," : ",$bugID" . $cardPairs['fixed'];
                    }
                    elseif($colType == 'closed' and $bug->status == 'closed' and strpos($cardPairs[$colType], ",$bugID,") === false)
                    {
                        $cardPairs[$colType] = empty($cardPairs[$colType]) ? ",$bugID," : ",$bugID". $cardPairs[$colType];
                    }
                    elseif($bug->status != $status and strpos($cardPairs[$colType], ",$bugID,") !== false)
                    {
                        $cardPairs[$colType] = str_replace(",$bugID,", ',', $cardPairs[$colType]);
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

                    if($lane->groupby and $task->{$lane->groupby} != $lane->extra)
                    {
                        $cardPairs[$colType] = str_replace(",$taskID,", ',', $cardPairs[$colType]);
                    }
                    elseif($task->status == $status and strpos($cardPairs[$colType], ",$taskID,") === false)
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

        foreach($cardPairs as $colType => $cards)
        {
            $this->dao->update(TABLE_KANBANCOLUMN)->set('cards')->eq($cards)->where('lane')->eq($lane->id)->andWhere('type')->eq($colType)->exec();
        }

        $this->dao->update(TABLE_KANBANLANE)->set('lastEditedTime')->eq(helper::now())->where('id')->eq($lane->id)->exec();
    }

    /**
     * Update group lanes.
     *
     * @param  int    $executionID
     * @param  string $type
     * @param  string $groupBy
     * @access public
     * @return array
     */
    public function updateGroupLanes($executionID, $type, $groupBy)
    {
        $this->loadModel($type);

        $lanes = $this->dao->select('*')->from(TABLE_KANBANLANE)
            ->where('execution')->eq($executionID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('type')->eq($type)->fi()
            ->andWhere('groupby')->eq($groupBy)->fi()
            ->orderBy('order_asc')
            ->fetchAll('id');

        /* Get old group list of kanban lane. */
        $oldGroupList = array();
        foreach($lanes as $lane) $oldGroupList[] = $lane->extra;

        /* Get new group list of kanban lane. */
        $groupList = $this->getObjectGroup($executionID, $type, $groupBy);

        $removeGroupList = array_diff($oldGroupList, $groupList);
        $addGroupList    = array_diff($groupList, $oldGroupList);

        $objectPairs = array();
        if($groupBy == 'module')     $objectPairs = $this->dao->select('id,name')->from(TABLE_MODULE)->where('type')->in('story,bug,task')->andWhere('deleted')->eq('0')->fetchPairs();
        if($groupBy == 'story')      $objectPairs = $this->dao->select('id,title')->from(TABLE_STORY)->where('deleted')->eq(0)->fetchPairs();
        if($groupBy == 'assignedTo') $objectPairs = $this->loadModel('user')->getPairs('noletter');

        $colorIndex = 0;

        foreach($lanes as $laneID => $lane)
        {
            if(in_array($lane->extra, $removeGroupList))
            {
                /* Remove lane and cloumns by laneID. */
                $this->dao->delete()->from(TABLE_KANBANLANE)->where('id')->eq($laneID)->exec();
                $this->dao->delete()->from(TABLE_KANBANCOLUMN)->where('lane')->eq($laneID)->exec();
            }
            else
            {
                /* Update kanban lanes by group. */
                $laneName = $this->lang->$type->$groupBy . ': ' . $this->lang->kanban->noGroup;
                if($lane->extra)
                {
                    $namePairs = strpos('module,story,assignedTo', $groupBy) !== false ? $objectPairs : $this->lang->$type->{$groupBy . 'List'};
                    $laneName  = $this->lang->$type->$groupBy . ': ' . zget($namePairs, $lane->extra);
                }

                $this->dao->update(TABLE_KANBANLANE)->set('name')->eq($laneName)->where('id')->eq($laneID)->exec();
                $this->updateCards($lane);

                $colorIndex += 1;
                if($colorIndex == count($this->config->kanban->laneColorList)) $colorIndex = 0;
            }
        }

        /* Add new lanes by group. */
        foreach($addGroupList as $groupKey)
        {
            $laneName = $this->lang->kanban->noGroup;
            if($groupKey)
            {
                $namePairs = strpos('module,story,assignedTo', $groupBy) !== false ? $objectPairs : $this->lang->$type->{$groupBy . 'List'};
                $laneName  = zget($namePairs, $groupKey);
            }

            $lane = new stdClass();
            $lane->execution = $executionID;
            $lane->type      = $type;
            $lane->groupby   = $groupBy;
            $lane->extra     = $groupKey;
            $lane->name      = $this->lang->$type->$groupBy . ": " . $laneName;
            $lane->color     = $this->config->kanban->laneColorList[$colorIndex];

            $colorIndex += 1;
            if($colorIndex == count($this->config->kanban->laneColorList)) $colorIndex = 0;
            $this->dao->insert(TABLE_KANBANLANE)->data($lane)->exec();

            $laneID = $this->dao->lastInsertId();
            $this->createExecutionColumns($laneID, $type, $executionID, $groupBy, $groupKey);
        }

        $this->resetLaneOrder($executionID, $type, $groupBy);
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
        $data = fixer::input('post')->get();

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
        if(!preg_match("/^-?\d+$/", $column->limit))
        {
            dao::$errors['limit'] = $this->lang->kanban->error->mustBeInt;
            return false;
        }
        $column->limit = (int)$column->limit;

        /* Check column limit. */
        $sumChildLimit = 0;
        if($oldColumn->parent == -1 and $column->limit != -1)
        {
            $childColumns = $this->dao->select('id,`limit`')->from(TABLE_KANBANCOLUMN)->where('parent')->eq($columnID)->fetchAll();
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
        $lane = fixer::input('post')->get();

        $this->dao->update(TABLE_KANBANLANE)->data($lane)
            ->autoCheck()
            ->batchcheck($this->config->kanban->setlane->requiredFields, 'notempty')
            ->where('id')->eq($laneID)
            ->exec();

        return dao::isError();
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
     * Get space by id.
     *
     * @param  int    $spaceID
     * @access public
     * @return array
     */
    public function getSpaceById($spaceID)
    {
        return $this->dao->findById($spaceID)->from(TABLE_KANBANSPACE)->fetch();
    }

    /**
     * Get column by id.
     *
     * @param  int    $columnID
     * @access public
     * @return object
     */
    public function getColumnById($columnID)
    {
        $column = $this->dao->select('t1.*, t2.type as laneType')->from(TABLE_KANBANCOLUMN)->alias('t1')
            ->leftjoin(TABLE_KANBANLANE)->alias('t2')->on('t1.lane=t2.id')
            ->where('t1.id')->eq($columnID)
            ->andWhere('t1.deleted')->eq(0)
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
            case 'createcolumn' :
            case 'copycolumn' :
            case 'splitcolumn' :
                if($object->parent) return false;   // The current column is a child column.

                $count = $this->dao->select('COUNT(id) AS count')->from(TABLE_KANBANCOLUMN)
                    ->where('parent')->eq($object->id)
                    ->andWhere('deleted')->eq('0')
                    //->andWhere('archived')->eq('0')
                    ->fetch('count');
                return $count == 0;     // The column has child columns.
            case 'restoreColumn' :
                if($object->parent)
                {
                    $parent = $this->getColumnByID($object->parent);
                    if($parent->deleted == '1' || $parent->archived == '1') return false;
                }
                return $object->archived == '1';
            case 'archivecolumn' :
                //if($object->archived != '0') return false;    // The column has been archived.
            case 'deletecolumn' :
                if($object->deleted != '0') return false;

                if($object->parent)
                {
                    $childrenCount = $this->dao->select('COUNT(id) AS count')->from(TABLE_KANBANCOLUMN)
                        ->where('parent')->eq($object->parent)
                        ->andWhere('deleted')->eq('0')
                        //->andWhere('archived')->eq('0')
                        ->fetch('count');

                    return $childrenCount > 2;
                }

                $count = $this->dao->select('COUNT(id) AS count')->from(TABLE_KANBANCOLUMN)
                    ->where('region')->eq($object->region)
                    ->andWhere('parent')->eq(0)
                    ->andWhere('`group`')->eq($object->group)
                    ->andWhere('deleted')->eq('0')
                    //->andWhere('archived')->eq('0')
                    ->fetch('count');

                return $count > 1;
        }

        return true;
    }
}

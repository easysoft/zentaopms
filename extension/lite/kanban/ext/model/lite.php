<?php
public function __construct($appName = '')
{
    parent::__construct($appName);
    if($this->app->getModuleName() == 'kanban') $this->lang->kanban->menu = new stdclass();
}

/**
 * Get kanban group by execution id.
 *
 * @param  int    $executionID
 * @param  int    $browseType
 * @param  int    $groupBy
 * @param  string $searchValue
 * @param  string $orderBy
 * @access public
 * @return array
 */
public function getKanban4Group($executionID, $browseType, $groupBy, $searchValue = '', $orderBy = 'pri_asc')
{
    /* Get card  data. */
    $cardList = array();
    if($browseType == 'story') $cardList = $this->loadModel('story')->getExecutionStories($executionID, 0, 0, 't1.`order`_desc', 'allStory');
    if($browseType == 'bug')   $cardList = $this->loadModel('bug')->getExecutionBugs($executionID);
    if($browseType == 'task')  $cardList = $this->loadModel('execution')->getKanbanTasks($executionID, "id");

    if($groupBy == 'story' and $browseType == 'task' and !isset($this->lang->kanban->orderList[$orderBy])) $orderBy = 'pri_asc';
    $lanes = $this->getLanes4Group($executionID, $browseType, $groupBy, $cardList, $orderBy);
    if(empty($lanes)) return array();

    $execution = $this->loadModel('execution')->getByID($executionID);

    $columns = $this->dao->select('t1.*, GROUP_CONCAT(t1.cards) as cards, t2.`type` as columnType, t2.limit, t2.name as columnName, t2.color')->from(TABLE_KANBANCELL)->alias('t1')
        ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.`column` = t2.id')
        ->leftJoin(TABLE_KANBANLANE)->alias('t3')->on('t1.lane = t3.id')
        ->leftJoin(TABLE_KANBANREGION)->alias('t4')->on('t1.kanban = t4.kanban')
        ->where('t1.kanban')->eq($executionID)
        ->andWhere('t1.`type`')->eq($browseType)
        ->beginIF(isset($execution->type) and $execution->type == 'kanban')
        ->andWhere('t3.deleted')->eq(0)
        ->andWhere('t4.deleted')->eq(0)
        ->fi()
        ->groupBy('columnType')
        ->orderBy('column_asc')
        ->fetchAll('columnType');

    $cardGroup = array();
    $actions   = array('setColumn', 'setWIP');
    foreach($columns as $column)
    {
        if(empty($column->cards)) continue;
        foreach($cardList as $card)
        {
            if($card->assignedTo == '') $card->assignedTo = 0;
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
        $laneData['type']            = $browseType;
        $laneData['defaultCardType'] = $browseType;

        if($browseType == 'task' and $groupBy == 'story')
        {
            $columnData[0]['id']         = 0;
            $columnData[0]['type']       = 'story';
            $columnData[0]['name']       = zget($this->lang->kanban->orderList, $orderBy, '');
            $columnData[0]['color']      = '#333';
            $columnData[0]['limit']      = '-1';
            $columnData[0]['laneType']   = $browseType;
            $columnData[0]['asParent']   = false;
            $columnData[0]['parentType'] = '';
            $columnData[0]['actions']    = array();

            if(empty($searchValue) or strpos($lane->name, $searchValue) !== false)
            {
                $cardData = array();
                $cardData['id']         = $laneID;
                $cardData['title']      = $lane->name;
                $cardData['order']      = 1;
                $cardData['pri']        = $lane->pri;
                $cardData['estimate']   = '';
                $cardData['assignedTo'] = $lane->assignedTo;
                $cardData['deadline']   = '';
                $cardData['severity']   = '';
                $laneData['cards']['story'][] = $cardData;
            }
        }

        /* Construct kanban column data. */
        foreach($columns as $column)
        {
            $columnID   = $column->columnType;
            $columnName = $column->columnName;
            $parentColumn = '';
            if(in_array($columnID, array('testing', 'tested')))       $parentColumn = 'test';
            if(in_array($columnID, array('fixing', 'fixed')))         $parentColumn = 'resolving';

            /* Judge column action priv. */
            $column->actions = array();
            foreach($actions as $action)
            {
                if($this->isClickable($column, $action)) $column->actions[] = $action;
            }

            $columnData[$columnID]['id']         = $column->column;
            $columnData[$columnID]['type']       = $columnID;
            $columnData[$columnID]['name']       = $columnName;
            $columnData[$columnID]['color']      = '#333';
            $columnData[$columnID]['limit']      = -1;
            $columnData[$columnID]['laneType']   = $browseType;
            $columnData[$columnID]['asParent']   = in_array($columnID, array('develop', 'test', 'resolving')) ? true : false;
            $columnData[$columnID]['parentType'] = $parentColumn;
            $columnData[$columnID]['actions']    = $column->actions;

            $cardOrder = 1;
            $objects   = zget($cardGroup, $column->columnType, array());
            foreach($objects as $object)
            {
                if(empty($object)) continue;

                $cardData = array();

                if(in_array($groupBy, array('module', 'story', 'pri', 'severity')) and (int)$object->$groupBy !== $laneID) continue;
                if(in_array($groupBy, array('assignedTo', 'type', 'category', 'source')) and $object->$groupBy !== $laneID) continue;

                $cardData['id']         = $object->id;
                $cardData['order']      = $cardOrder;
                $cardData['pri']        = $object->pri ? $object->pri : '';
                $cardData['estimate']   = $browseType == 'bug' ? '' : $object->estimate;
                $cardData['assignedTo'] = $object->assignedTo;
                $cardData['deadline']   = $browseType == 'story' ? '' : $object->deadline;
                $cardData['severity']   = $browseType == 'bug' ? $object->severity : '';

                if($browseType == 'task')
                {
                    if($searchValue != '' and strpos($object->name, $searchValue) === false) continue;
                    $cardData['name']   = $object->name;
                    $cardData['status'] = $object->status;
                    $cardData['left']   = $object->left;
                }
                else
                {
                    if($searchValue != '' and strpos($object->name, $searchValue) === false) continue;
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
 * Get card group by execution id.
 *
 * @param  int    $kanbanID
 * @param  string $browseType all|task|bug|story
 * @param  string $orderBy
 * @param  string $searchValue
 * @access public
 * @return array
 */
public function getCardGroupByExecution($executionID, $browseType = 'all', $orderBy = 'id_asc', $searchValue = '')
{
    $cards = $this->dao->select('t1.*, t2.type as columnType')
        ->from(TABLE_KANBANCELL)->alias('t1')
        ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.column=t2.id')
        ->where('t1.kanban')->eq($executionID)
        ->beginIF($browseType != 'all')->andWhere('t1.type')->eq($browseType)->fi()
        ->orderby($orderBy)
        ->fetchgroup('lane', 'column');

    /* Get group objects. */
    if($browseType == 'all' or $browseType == 'story') $objectGroup['story'] = $this->loadModel('story')->getExecutionStories($executionID, 0, 0, 't1.`order`_desc', 'allStory');
    if($browseType == 'all' or $browseType == 'bug')   $objectGroup['bug']   = $this->loadModel('bug')->getExecutionBugs($executionID);
    if($browseType == 'all' or $browseType == 'task')  $objectGroup['task']  = $this->loadModel('execution')->getKanbanTasks($executionID, "id");
    $taskCardMenu  = $this->getKanbanCardMenu($executionID, $objectGroup['task'], 'task');
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
                $cardData['menus']      = $taskCardMenu[$cardID];

                if($cell->type == 'task')
                {
                    $cardData['name']       = $object->name;
                    $cardData['status']     = $object->status;
                    $cardData['left']       = $object->left;
                    $cardData['estStarted'] = $object->estStarted;
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


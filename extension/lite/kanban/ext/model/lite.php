<?php
public function __construct($appName = '')
{
    parent::__construct($appName);
    if($this->app->getModuleName() == 'kanban') $this->lang->kanban->menu = new stdclass();
}

public function getKanban4Group($executionID, $browseType, $groupBy)
{
    /* Get card  data. */
    $cardList = array();
    if($browseType == 'story') $cardList = $this->loadModel('story')->getExecutionStories($executionID, 0, 0, 't1.`order`_desc', 'allStory');
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
                    $cardData['name']   = $object->name;
                    $cardData['status'] = $object->status;
                    $cardData['left']   = $object->left;
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


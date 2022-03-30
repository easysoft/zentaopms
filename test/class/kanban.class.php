<?php
class kanbanTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('kanban');
    }

    /**
     * Test create a kanban group.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @access public
     * @return object
     */
    public function createGroupTest($kanbanID, $regionID)
    {
        $objectID = $this->objectModel->createGroup($kanbanID, $regionID);

        if(dao::isError()) return dao::getError();

        global $tester;
        $object = $tester->dao->findByID($objectID)->from(TABLE_KANBANGROUP)->fetch();
        return $object;
    }

    /**
     * Test create a default kanban region.
     *
     * @param  int    $kanbanID
     * @access public
     * @return object
     */
    public function createDefaultRegionTest($kanban)
    {
        $objectID = $this->objectModel->createDefaultRegion($kanban);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getRegionByID($objectID);
        return $object;
    }

    /**
     * Test create a region.
     *
     * @param  object $kanban
     * @param  object $region
     * @param  int    $copyRegionID
     * @access public
     * @return object
     */
    public function createRegionTest($kanban, $region = null, $copyRegionID = 0)
    {
        if(!is_null($region))
        {
            if(empty($copyRegionID))
            {
                foreach($region as $key => $value) $_POST[$key] = $value;
                $region = null;
            }

            $objectID = $this->objectModel->createRegion($kanban, $region, $copyRegionID);
        }
        else
        {
            $objectID = $this->objectModel->createDefaultRegion($kanban);
        }

        unset($_POST);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getRegionByID($objectID);
        return $object;
    }

    /**
     * Test create default kanban lanes.
     *
     * @param  int    $regionID
     * @param  int    $groupID
     * @access public
     * @return object
     */
    public function createDefaultLaneTest($regionID, $groupID)
    {
        $objectID = $this->objectModel->createDefaultLane(null, $regionID, $groupID);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getLaneByID($objectID);
        return $object;
    }

    /**
     * Test create default kanban columns.
     *
     * @param  int    $regionID
     * @param  int    $groupID
     * @access public
     * @return int
     */
    public function createDefaultColumnsTest($regionID, $groupID)
    {
        $this->objectModel->createDefaultColumns(null, $regionID, $groupID);


        if(dao::isError()) return dao::getError();

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_KANBANCOLUMN)->where('`region`')->eq($regionID)->andWhere('`group`')->eq($groupID)->andWhere('`type`')->like('column%')->fetchAll();
        return count($objects);
    }

    /**
     * Test create a column.
     *
     * @param  object $param
     * @access public
     * @return object
     */
    public function createColumnTest($param)
    {
        $regionID = 1;

        foreach($param as $key => $value) $_POST[$key] = $value;

        $objectID = $this->objectModel->createColumn($regionID, null, 0, $param->parent);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getColumnByID($objectID);
        return $object;
    }

    public function splitColumnTest($columnID)
    {
        $objects = $this->objectModel->splitColumn($columnID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test create a card.
     *
     * @param  array  $param
     * @access public
     * @return object
     */
    public function createCardTest($param)
    {
        $kanbanID = 1;
        $regionID = 1;
        $groupID  = 1;
        $columnID = 1;

        $_POST['lane']  = 1;
        $_POST['begin'] = date('Y-m-d', strtotime("-3 day"));
        $_POST['end']   = date('Y-m-d', strtotime("+3 day"));
        foreach($param as $key => $value) $_POST[$key] = $value;

        $objectID = $this->objectModel->createCard($kanbanID, $regionID, $groupID, $columnID);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getCardByID($objectID);
        return $object;
    }

    public function importCardTest($kanbanID, $regionID, $groupID, $columnID)
    {
        $objects = $this->objectModel->importCard($kanbanID, $regionID, $groupID, $columnID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function importObjectTest($kanbanID, $regionID, $groupID, $columnID, $objectType)
    {
        $objects = $this->objectModel->importObject($kanbanID, $regionID, $groupID, $columnID, $objectType);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test batch create kanban cards.
     *
     * @param  array  $param
     * @param  bool   $endLtBegin
     * @access public
     * @return object
     */
    public function batchCreateCardTest($param = array(), $endLtBegin = false)
    {
        $kanbanID = 1;
        $regionID = 1;
        $groupID  = 1;
        $columnID = 1;
        $batchCreateFields['name']       = array('0' => '批量创建卡片1', '1' => '批量创建卡片2', '2' => '批量创建卡片3', '3' => '批量创建卡片4');
        $batchCreateFields['lane']       = array('0' => '1', '1' => 'ditto', '2' => 'ditto', '3' => 'ditto');
        $batchCreateFields['assignedTo'] = array();
        $batchCreateFields['estimate']   = array('0' => '1', '1' => '2', '2' => '3', '3' => '4');
        $batchCreateFields['begin']      = array('0' => date('Y-m-d', time()), '1' => date('Y-m-d', time()), '2' => '', '3' => '');
        $batchCreateFields['end']        = !$endLtBegin ? array('0' => date('Y-m-d', time()), '1' => '', '2' => '', '3' => '') : array('0' => date('Y-m-d', strtotime("-1 day")), '1' => date('Y-m-d', strtotime("-1 day")), '2' => date('Y-m-d', strtotime("-2 day")), '3' => date('Y-m-d', strtotime("-3 day")));
        $batchCreateFields['desc']       = array('0' => '描述1', '1' => '描述2', '2' => '描述3', '3' => '描述4');
        $batchCreateFields['pri']        = array('0' => '1', '1' => '2', '2' => '3', '3' => '4');
        $batchCreateFields['beginDitto'] = array('1' => 'on', '2' => 'on', '3' => 'on', '4' => 'on');
        $batchCreateFields['endDitto']   = array('1' => 'on', '2' => 'on', '3' => 'on', '4' => 'on');

        foreach($batchCreateFields as $field => $defaultValue) $_POST[$field] = $defaultValue;

        foreach($param as $key => $value) $_POST[$key] = $value;

        $this->objectModel->batchCreateCard($kanbanID, $regionID, $groupID, $columnID);

        global $tester;
        $object = $tester->dao->select('*')->from(TABLE_KANBANCELL)->where('kanban')->eq($kanbanID)->andWhere('lane')->eq(1)->andWhere('`column`')->eq($columnID)->andWhere('type')->eq('common')->fetch();

        unset($_POST);

        if(dao::isError()) return dao::getError()[0];

        return $object;
    }

    /**
     * Test get kanban by id.
     *
     * @param  int    $kanbanID
     * @access public
     * @return object
     */
    public function getByIDTest($kanbanID)
    {
        $object = $this->objectModel->getByID($kanbanID);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    public function getKanbanDataTest($kanbanID)
    {
        $objects = $this->objectModel->getKanbanData($kanbanID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getPlanKanbanTest($product, $branchID = 0, $planGroup = '')
    {
        $objects = $this->objectModel->getPlanKanban($product, $branchID = 0, $planGroup = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRDKanbanTest($executionID, $browseType = 'all', $orderBy = 'id_desc', $regionID = 0, $groupBy = 'default')
    {
        $objects = $this->objectModel->getRDKanban($executionID, $browseType = 'all', $orderBy = 'id_desc', $regionID = 0, $groupBy = 'default');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRegionByIDTest($regionID)
    {
        $objects = $this->objectModel->getRegionByID($regionID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRegionPairsTest($kanbanID, $regionID = 0, $from = 'kanban')
    {
        $objects = $this->objectModel->getRegionPairs($kanbanID, $regionID = 0, $from = 'kanban');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getKanbanIDByRegionTest($regionID)
    {
        $objects = $this->objectModel->getKanbanIDByRegion($regionID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get kanban group by regions.
     *
     * @param  string $regions
     * @access public
     * @return int
     */
    public function getGroupGroupByRegionsTest($regions)
    {
        $objects = $this->objectModel->getGroupGroupByRegions($regions);

        if(dao::isError()) return dao::getError();

        $count = 0;
        foreach($objects as $object) $count += count($object);

        return $count;
    }

    public function getLaneGroupByRegionsTest($regions, $browseType = 'all')
    {
        $objects = $this->objectModel->getLaneGroupByRegions($regions, $browseType = 'all');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getLanePairsByGroupTest($groupID, $orderBy = '`order`_asc')
    {
        $objects = $this->objectModel->getLanePairsByGroup($groupID, $orderBy = '`order`_asc');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getColumnGroupByRegionsTest($regions, $order = 'order')
    {
        $objects = $this->objectModel->getColumnGroupByRegions($regions, $order = 'order');

        if(dao::isError()) return dao::getError();

        $count = 0;
        foreach($objects as $object) $count += count($object);
        return $count;
    }

    /**
     * Test get card group by kanban id.
     *
     * @param  int    $kanbanID
     * @access public
     * @return int
     */
    public function getCardGroupByKanbanTest($kanbanID)
    {
        $objects = $this->objectModel->getCardGroupByKanban($kanbanID);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    public function getImportedCardsTest($kanbanID, $cards, $fromType)
    {
        $objects = $this->objectModel->getImportedCards($kanbanID, $cards, $fromType);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRDColumnGroupByRegionsTest($regions, $groupIDList = array())
    {
        $objects = $this->objectModel->getRDColumnGroupByRegions($regions, $groupIDList = array());

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get card group by execution id.
     *
     * @param  int    $executionID
     * @param  string $browseType
     * @param  string $orderBy
     * @access public
     * @return int
     */
    public function getCardGroupByExecutionTest($executionID, $browseType = 'all', $orderBy = 'id_asc')
    {
        $objects = $this->objectModel->getCardGroupByExecution($executionID, $browseType, $orderBy);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * Test get Kanban by execution id.
     *
     * @param  int    $executionID
     * @param  string $browseType
     * @param  string $groupBy
     * @access public
     * @return string
     */
    public function getExecutionKanbanTest($executionID, $browseType = 'all', $groupBy = 'default')
    {
        $objects = $this->objectModel->getExecutionKanban($executionID, $browseType, $groupBy);

        if(empty($objects))
        {
            $this->objectModel->createExecutionLane($executionID, $browseType, $groupBy);
            $objects = $this->objectModel->getExecutionKanban($executionID, $browseType, $groupBy);
        }

        if(dao::isError()) return dao::getError();

        $columnCount = 0;
        $laneCount   = 0;
        $cardCount   = 0;
        foreach($objects as $types)
        {
            foreach($types['lanes'] as $lane)
            {
                foreach($lane['cards'] as $card)
                {
                    $cardCount += count($card);
                }
            }
            $columnCount += count($types['columns']);
            $laneCount += count($types['lanes']);
        }
        return 'columns:' . $columnCount . ', lanes:' . $laneCount . ', cards:' . $cardCount;
    }

    /**
     * Test get kanban for group view.
     *
     * @param  int    $executionID
     * @param  string $browseType
     * @param  string $groupBy
     * @access public
     * @return int
     */
    public function getKanban4GroupTest($executionID, $browseType, $groupBy)
    {
        $objects = $this->objectModel->getKanban4Group($executionID, $browseType, $groupBy);

        if(empty($objects))
        {
            $this->objectModel->createExecutionLane($executionID, $browseType, $groupBy);
            $objects = $this->objectModel->getExecutionKanban($executionID, $browseType, $groupBy);
        }

        if(dao::isError()) return dao::getError();

        $laneCount   = 0;
        foreach($objects as $types)
        {
            $laneCount += count($types['lanes']);
        }
        return 'lanes:' . $laneCount;
    }

    public function getLanes4GroupTest($executionID, $browseType, $groupBy, $cardList)
    {
        $objects = $this->objectModel->getLanes4Group($executionID, $browseType, $groupBy, $cardList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getSpaceListTest($browseType, $pager = null)
    {
        $objects = $this->objectModel->getSpaceList($browseType, $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getSpacePairsTest($browseType = 'private')
    {
        $objects = $this->objectModel->getSpacePairs($browseType = 'private');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getKanbanPairsTest()
    {
        $objects = $this->objectModel->getKanbanPairs();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get can view objects.
     *
     * @param  string $user
     * @param  string $objectType
     * @param  string $param
     * @access public
     * @return void
     */
    public function getCanViewObjectsTest($user, $objectType = 'kanban', $param = '')
    {
        su($user);
        $objects = $this->objectModel->getCanViewObjects($objectType, $param);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * Test create a space.
     *
     * @param  array   $param
     * @access public
     * @return object
     */
    public function createSpaceTest($param)
    {
        foreach($param as $key => $value) $_POST[$key] = $value;

        $objectID = $this->objectModel->createSpace();

        unset($_POST);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getSpaceByID($objectID);
        return $object;
    }

    public function updateSpaceTest($spaceID, $type = '')
    {
        $objects = $this->objectModel->updateSpace($spaceID, $type = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getLanePairsByRegionTest($regionID, $type = 'all')
    {
        $objects = $this->objectModel->getLanePairsByRegion($regionID, $type = 'all');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getLaneGroupByRegionTest($regionID, $type = 'all')
    {
        $objects = $this->objectModel->getLaneGroupByRegion($regionID, $type = 'all');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test create a lane.
     *
     * @param  object $object
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @access public
     * @return object
     */
    public function createLaneTest($object, $kanbanID, $regionID)
    {
        $lane = isset($object->id) ? $this->objectModel->getLaneByID($object->id) : null;
        unset($object->id);
        unset($lane->id);

        foreach($object as $key => $value) $_POST[$key] = $value;

        $objectID = $this->objectModel->createLane($kanbanID, $regionID, $lane);
        unset($_POST);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getLaneByID($objectID);
        return $object;
    }

    /**
     * Test create a kanban.
     *
     * @param  array  $param
     * @access public
     * @return object
     */
    public function createTest($param = array())
    {
        foreach($param as $key => $value) $_POST[$key] = $value;

        $objectID = $this->objectModel->create();

        unset($_POST);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getByID($objectID);
        return $object;
    }

    public function updateTest($kanbanID)
    {
        $objects = $this->objectModel->update($kanbanID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test add execution Kanban lanes and columns.
     *
     * @param  int    $executionID
     * @param  string $type
     * @param  string $groupBy
     * @access public
     * @return int
     */
    public function createExecutionLaneTest($executionID, $type = 'all', $groupBy = 'default')
    {
        $this->objectModel->createExecutionLane($executionID, $type, $groupBy);

        if(dao::isError()) return dao::getError();

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_KANBANLANE)
            ->where('execution')->eq($executionID)
            ->beginIF($type != 'all')->andWhere('type')->eq($type)->fi()
            ->fetchAll();
        return count($objects);
    }

    /**
     * Test create execution columns.
     *
     * @param  int    $laneID
     * @param  string $type
     * @param  int    $executionID
     * @access public
     * @return int
     */
    public function createExecutionColumnsTest($laneID, $type, $executionID)
    {
        $this->objectModel->createExecutionColumns($laneID, $type, $executionID);

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_KANBANCELL)
            ->where('kanban')->eq($executionID)
            ->andWhere('lane')->eq($laneID)
            ->andWhere('type')->eq($type)
            ->fetchAll();

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * Test add kanban cell.
     *
     * @param  int    $kanbanID
     * @param  int    $laneID
     * @param  int    $colID
     * @param  int    $type
     * @param  int    $cardID
     * @access public
     * @return object
     */
    public function addKanbanCellTest($kanbanID, $laneID, $colID, $type, $cardID = 0)
    {
        $this->objectModel->addKanbanCell($kanbanID, $laneID, $colID, $type, $cardID);


        if(dao::isError()) return dao::getError();

        global $tester;
        $object = $tester->dao->select('*')->from(TABLE_KANBANCELL)->where('kanban')->eq($kanbanID)->andWhere('lane')->eq($laneID)->andWhere('`column`')->eq($colID)->andWhere('type')->eq($type)->fetch();
        return $object;
    }

    public function removeKanbanCellTest($type, $removeCardID, $kanbanList)
    {
        $objects = $this->objectModel->removeKanbanCell($type, $removeCardID, $kanbanList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createRDKanbanTest($execution)
    {
        $this->objectModel->createRDKanban($execution);

        if(dao::isError()) return dao::getError();

        global $tester;
        $regions = $tester->dao->select('*')->from(TABLE_KANBANREGION)->where('`kanban`')->eq($execution->id)->fetchAll('id');
        $lanes   = $tester->dao->select('*')->from(TABLE_KANBANLANE)->where('`execution`')->eq($execution->id)->andWhere('`type`')->ne('common')->fetchAll('id');
        $regionIDList = implode($regions, ',');
        $columns = $tester->dao->select('*')->from(TABLE_KANBANCOLUMN)->where('`region`')->in($regionIDList)->fetchAll('id');
        return count($regions) . ',' . count($lanes) . ',' . count($columns);
    }

    /**
     * Test create default RD region.
     *
     * @param  object $execution
     * @access public
     * @return object
     */
    public function createRDRegionTest($execution)
    {
        $objectID = $this->objectModel->createRDRegion($execution);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getRegionByID($objectID);
        return $object;
    }

    /**
     *
     * Test create default RD lanes.
     *
     * @param  int    $executionID
     * @param  int    $regionID
     * @access public
     * @return int
     */
    public function createRDLaneTest($executionID, $regionID)
    {
        $this->objectModel->createRDLane($executionID, $regionID);

        if(dao::isError()) return dao::getError();

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_KANBANLANE)->where('`region`')->eq($regionID)->andWhere('`execution`')->eq($executionID)->fetchAll();
        return count($objects);
    }

    /**
     * Test create default RD columns.
     *
     * @param  int    $regionID
     * @param  int    $groupID
     * @param  int    $laneID
     * @param  string $laneType
     * @param  int    $executionID
     * @access public
     * @return int
     */
    public function createRDColumnTest($regionID, $groupID, $laneID, $laneType, $executionID)
    {
        $this->objectModel->createRDColumn($regionID, $groupID, $laneID, $laneType, $executionID);

        if(dao::isError()) return dao::getError();

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_KANBANCOLUMN)->where('`region`')->eq($regionID)->andWhere('`group`')->eq($groupID)->fetchAll();
        return count($objects);
    }

    public function updateRegionTest($regionID)
    {
        $objects = $this->objectModel->updateRegion($regionID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateLaneTest($executionID, $laneType, $cardID = 0)
    {
        $objects = $this->objectModel->updateLane($executionID, $laneType, $cardID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function refreshCardsTest($lane)
    {
        $objects = $this->objectModel->refreshCards($lane);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateLaneColumnTest($columnID, $column)
    {
        $objects = $this->objectModel->updateLaneColumn($columnID, $column);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateLaneOrderTest($executionID, $currentType, $targetType)
    {
        $objects = $this->objectModel->updateLaneOrder($executionID, $currentType, $targetType);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test activate a card.
     *
     * @param  int    $cardID
     * @param  int    $progress
     * @access public
     * @return object
     */
    public function activateCardTest($cardID, $progress)
    {
        global $tester;
        $tester->post->progress = $progress;

        $this->objectModel->activateCard($cardID);

        unset($this->post);

        if(dao::isError()) return dao::getError()[0];

        $object = $this->objectModel->getCardByID($cardID);
        return $object;
    }

    public function updateCardTest($cardID)
    {
        $objects = $this->objectModel->updateCard($cardID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function setWIPTest($columnID)
    {
        $objects = $this->objectModel->setWIP($columnID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function setLaneTest($laneID)
    {
        $objects = $this->objectModel->setLane($laneID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function setLaneHeightTest($kanbanID, $from = 'kanban')
    {
        $objects = $this->objectModel->setLaneHeight($kanbanID, $from = 'kanban');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function setColumnWidthTest($kanbanID, $from = 'kanban')
    {
        $objects = $this->objectModel->setColumnWidth($kanbanID, $from = 'kanban');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function setHeaderActionsTest($kanban)
    {
        $objects = $this->objectModel->setHeaderActions($kanban);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function setSwitcherTest($kanban)
    {
        $objects = $this->objectModel->setSwitcher($kanban);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function sortGroupTest($region, $groups)
    {
        $objects = $this->objectModel->sortGroup($region, $groups);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function moveCardTest($cardID, $fromColID, $toColID, $fromLaneID, $toLaneID, $kanbanID = 0)
    {
        $objects = $this->objectModel->moveCard($cardID, $fromColID, $toColID, $fromLaneID, $toLaneID, $kanbanID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateCardColorTest($cardID, $color)
    {
        $objects = $this->objectModel->updateCardColor($cardID, $color);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function resetLaneOrderTest($executionID, $type, $groupBy)
    {
        $objects = $this->objectModel->resetLaneOrder($executionID, $type, $groupBy);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test archive a column.
     *
     * @param  int    $columnID
     * @access public
     * @return array
     */
    public function archiveColumnTest($columnID)
    {
        $this->objectModel->archiveColumn($columnID);

        $object = $this->objectModel->getColumnByID($columnID);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    public function restoreColumnTest($columnID)
    {
        $objects = $this->objectModel->restoreColumn($columnID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test archive a card.
     *
     * @param  int    $cardID
     * @access public
     * @return array
     */
    public function archiveCardTest($cardID)
    {
        $changes = $this->objectModel->archiveCard($cardID);

        if(dao::isError()) return dao::getError();

        return $changes;
    }

    public function restoreCardTest($cardID)
    {
        $objects = $this->objectModel->restoreCard($cardID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function processCardsTest($column)
    {
        $objects = $this->objectModel->processCards($column);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getSpaceByIdTest($spaceID)
    {
        $objects = $this->objectModel->getSpaceById($spaceID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get kanban group by space id list.
     *
     * @param  string $spaceIdList
     * @param  string $kanbanIdList
     * @access public
     * @return int
     */
    public function getGroupBySpaceListTest($spaceIdList, $kanbanIdList = '')
    {
        $objects = $this->objectModel->getGroupBySpaceList($spaceIdList, $kanbanIdList);

        if(dao::isError()) return dao::getError();

        $count = 0;
        if(empty($kanbanIdList))
        {
            foreach($objects as $object) $count += count($object);
        }
        else
        {
            $count += count($objects);
        }
        return $count;
    }

    /**
     * Test get group list by region id.
     *
     * @param  int    $region
     * @access public
     * @return object
     */
    public function getGroupListTest($region)
    {
        $objects = $this->objectModel->getGroupList($region);

        if(dao::isError()) return dao::getError();

        $kanban = '';
        foreach($objects as $object) $kanban .= ',' . $object->kanban;
        return $kanban;
    }

    /**
     * Test get column by id.
     *
     * @param  int    $columnID
     * @access public
     * @return object
     */
    public function getColumnByIDTest($columnID)
    {
        $object = $this->objectModel->getColumnByID($columnID);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test get columns by object id.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  int    $archived
     * @param  string $deleted
     * @access public
     * @return int
     */
    public function getColumnsByObjectTest($objectType = '', $objectID = 0, $archived = 0, $deleted = '0')
    {
        $objects = $this->objectModel->getColumnsByObject($objectType, $objectID, $archived, $deleted);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * Test get column ID by lane ID.
     *
     * @param  int    $laneID
     * @param  string $columnType
     * @access public
     * @return string
     */
    public function getColumnIDByLaneIDTest($laneID, $columnType)
    {
        $object = $this->objectModel->getColumnIDByLaneID($laneID, $columnType);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    public function getLaneByIdTest($laneID)
    {
        $objects = $this->objectModel->getLaneById($laneID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getObjectGroupTest($executionID, $type, $groupBy)
    {
        $objects = $this->objectModel->getObjectGroup($executionID, $type, $groupBy);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get card by id.
     *
     * @param  int    $cardID
     * @access public
     * @return object
     */
    public function getCardByIDTest($cardID)
    {
        $object = $this->objectModel->getCardByID($cardID);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test get cards by object id.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $archived
     * @param  string $deleted
     * @access public
     * @return int
     */
    public function getCardsByObjectTest($objectType = '', $objectID = 0, $archived = '0', $deleted = '0')
    {
        $objects = $this->objectModel->getCardsByObject($objectType, $objectID, $archived, $deleted);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * Test get cards to import.
     *
     * @param  int     $kanbanID
     * @param  int    $excludedID
     * @param  object $pager
     * @access public
     * @return int
     */
    public function getCards2ImportTest($kanbanID = 0, $excludedID = 0, $pager = null)
    {
        $objects = $this->objectModel->getCards2Import($kanbanID, $excludedID, $pager);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    public function getKanbanCardMenuTest($executionID, $objecType)
    {
        global $tester;
        /* Get group objects. */
        if($objecType == 'story') $objectGroup['story'] = $tester->loadModel('story')->getExecutionStories($executionID, 0, 0, 't1.`order`_desc', 'allStory');
        if($objecType == 'bug')   $objectGroup['bug']   = $tester->loadModel('bug')->getExecutionBugs($executionID);
        if($objecType == 'task')  $objectGroup['task']  = $tester->loadModel('execution')->getKanbanTasks($executionID, "id");

        $objects = array();
        /* Get objects cards menus. */
        if($objecType == 'story') $objects = $this->objectModel->getKanbanCardMenu($executionID, $objectGroup['story'], 'story');
        if($objecType == 'bug')   $objects = $this->objectModel->getKanbanCardMenu($executionID, $objectGroup['bug'], 'bug');
        if($objecType == 'task')  $objects = $this->objectModel->getKanbanCardMenu($executionID, $objectGroup['task'], 'task');

        if(dao::isError()) return dao::getError();

        $count = 0;
        foreach($objects as $object) $count += count($object);
        return $count;
    }

    public function isClickableTest($object, $action)
    {
        $objects = $this->objectModel->isClickable($object, $action);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function importTest($kanbanID)
    {
        $objects = $this->objectModel->import($kanbanID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}

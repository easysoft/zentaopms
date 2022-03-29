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

    public function createRegionTest($kanban, $region = null, $copyRegionID = 0, $from = 'kanban')
    {
        $objects = $this->objectModel->createRegion($kanban, $region = null, $copyRegionID = 0, $from = 'kanban');

        if(dao::isError()) return dao::getError();

        return $objects;
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

    public function getByIDTest($kanbanID)
    {
        $objects = $this->objectModel->getByID($kanbanID);

        if(dao::isError()) return dao::getError();

        return $objects;
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

    public function getGroupGroupByRegionsTest($regions)
    {
        $objects = $this->objectModel->getGroupGroupByRegions($regions);

        if(dao::isError()) return dao::getError();

        return $objects;
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

        return $objects;
    }

    public function getCardGroupByKanbanTest($kanbanID)
    {
        $objects = $this->objectModel->getCardGroupByKanban($kanbanID);

        if(dao::isError()) return dao::getError();

        return $objects;
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

    public function getCardGroupByExecutionTest($executionID, $browseType = 'all', $orderBy = 'id_asc')
    {
        $objects = $this->objectModel->getCardGroupByExecution($executionID, $browseType = 'all', $orderBy = 'id_asc');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getExecutionKanbanTest($executionID, $browseType = 'all', $groupBy = 'default')
    {
        $objects = $this->objectModel->getExecutionKanban($executionID, $browseType = 'all', $groupBy = 'default');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getKanban4GroupTest($executionID, $browseType, $groupBy)
    {
        $objects = $this->objectModel->getKanban4Group($executionID, $browseType, $groupBy);

        if(dao::isError()) return dao::getError();

        return $objects;
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

    public function getCanViewObjectsTest($objectType = 'kanban', $param = '')
    {
        $objects = $this->objectModel->getCanViewObjects($objectType = 'kanban', $param = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createSpaceTest()
    {
        $objects = $this->objectModel->createSpace();

        if(dao::isError()) return dao::getError();

        return $objects;
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

    public function createLaneTest($kanbanID, $regionID, $lane = null)
    {
        $objects = $this->objectModel->createLane($kanbanID, $regionID, $lane = null);

        if(dao::isError()) return dao::getError();

        return $objects;
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
        $objects = $this->objectModel->createRDKanban($execution);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createRDRegionTest($execution)
    {
        $objects = $this->objectModel->createRDRegion($execution);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createRDLaneTest($executionID, $regionID)
    {
        $objects = $this->objectModel->createRDLane($executionID, $regionID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createRDColumnTest($regionID, $groupID, $laneID, $laneType, $executionID)
    {
        $objects = $this->objectModel->createRDColumn($regionID, $groupID, $laneID, $laneType, $executionID);

        if(dao::isError()) return dao::getError();

        return $objects;
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

    public function getGroupBySpaceListTest($spaceIdList, $kanbanIdList = '')
    {
        $objects = $this->objectModel->getGroupBySpaceList($spaceIdList, $kanbanIdList = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getGroupListTest($region)
    {
        $objects = $this->objectModel->getGroupList($region);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getColumnByIDTest($columnID)
    {
        $objects = $this->objectModel->getColumnByID($columnID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getColumnsByObjectTest($objectType = '', $objectID = 0, $archived = 0, $deleted = '0')
    {
        $objects = $this->objectModel->getColumnsByObject($objectType = '', $objectID = 0, $archived = 0, $deleted = '0');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getColumnIDByLaneIDTest($laneID, $columnType)
    {
        $objects = $this->objectModel->getColumnIDByLaneID($laneID, $columnType);

        if(dao::isError()) return dao::getError();

        return $objects;
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

    public function getCardByIDTest($cardID)
    {
        $objects = $this->objectModel->getCardByID($cardID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getCardsByObjectTest($objectType = '', $objectID = 0, $archived = '0', $deleted = '0')
    {
        $objects = $this->objectModel->getCardsByObject($objectType = '', $objectID = 0, $archived = '0', $deleted = '0');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getCards2ImportTest($kanbanID = 0, $excludedID = 0, $pager = null)
    {
        $objects = $this->objectModel->getCards2Import($kanbanID = 0, $excludedID = 0, $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getKanbanCardMenuTest($executionID, $objects, $objecType)
    {
        $objects = $this->objectModel->getKanbanCardMenu($executionID, $objects, $objecType);

        if(dao::isError()) return dao::getError();

        return $objects;
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

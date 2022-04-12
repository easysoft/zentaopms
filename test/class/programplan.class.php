<?php
class programplanTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('programplan');
    }

    /**
     * Test get plan by id.
     *
     * @param  int    $planID
     * @access public
     * @return object
     */
    public function getByIDTest($planID)
    {
        $object = $this->objectModel->getByID($planID);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test get plans list.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  string $browseType
     * @param  string $orderBy
     * @access public
     * @return string
     */
    public function getStageTest($executionID = 0, $productID = 0, $browseType = 'all', $orderBy = 'id_asc')
    {
        $objects = $this->objectModel->getStage($executionID, $productID, $browseType, $orderBy);

        if(dao::isError()) return dao::getError();

        $title = '';
        foreach($objects as $object) $title .= ',' . $object->name;
        return $title;
    }

    /**
     * Test get plans by idList.
     *
     * @param  array $idList
     * @access public
     * @return array
     */
    public function getByListTest($idList = array())
    {
        $objects = $this->objectModel->getByList($idList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get plans.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  string $orderBy
     * @access public
     * @return string
     */
    public function getPlansTest($executionID = 0, $productID = 0, $orderBy = 'id_asc')
    {
        $objects = $this->objectModel->getPlans($executionID, $productID, $orderBy);

        if(dao::isError()) return dao::getError();

        $title = '';
        foreach($objects as $object) $title .= ',' . $object->name;
        return $title;
    }

    /**
     * Test get pairs.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  string $type
     * @access public
     * @return string
     */
    public function getPairsTest($executionID, $productID = 0, $type = 'all')
    {
        $objects = $this->objectModel->getPairs($executionID, $productID, $type);

        if(dao::isError()) return dao::getError();

        return implode(',', $objects);
    }

    public function getDataForGanttTest($executionID, $productID, $baselineID = 0, $selectCustom = '', $returnJson = false)
    {
        $objects = $this->objectModel->getDataForGantt($executionID, $productID, $baselineID, $selectCustom, $returnJson);

        if(dao::isError()) return dao::getError();

        $objects = is_string($objects) ? json_decode($objects) : $objects;
        return $objects;
    }

    public function getTotalPercentTest($stage, $parent = false)
    {
        $objects = $this->objectModel->getTotalPercent($stage, $parent = false);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function processPlansTest($plans)
    {
        $objects = $this->objectModel->processPlans($plans);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function processPlanTest($plan)
    {
        $objects = $this->objectModel->processPlan($plan);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get duration.
     *
     * @param  date   $begin
     * @param  date   $end
     * @access public
     * @return int
     */
    public function getDurationTest($begin, $end)
    {
        $count = $this->objectModel->getDuration($begin, $end);

        if(dao::isError()) return dao::getError();

        return $count;
    }

    /**
     * Test create a plan.
     *
     * @param  array  $param
     * @access public
     * @return array
     */
    public function createTest($param = array())
    {
        $_POST['planIDList'] = array('131', '221', '311', '401', '491', '581', '671');
        $_POST['names']      = array('阶段31', '阶段121', '阶段211', '阶段301', '阶段391', '阶段481', '阶段571', '', '', '', '', '');
        $_POST['PM']         = array('', '', '', '', '', '', '', '', '', '', '', '');
        $_POST['percents']   = array('0', '0', '0', '0', '0', '0', '0', '', '', '', '', '');
        $_POST['attributes'] = array('request', 'request', 'request', 'request', 'request', 'request', 'request', 'request', 'request', 'request', 'request', 'request');
        $_POST['acl']        = array('private', 'open', 'open', 'private', 'private', 'open', 'open', 'open', 'open', 'open', 'open', 'open');
        $_POST['milestone']  = array('0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
        $_POST['begin']      = array('2022-03-13', '2022-03-13', '2022-03-16', '2022-03-16', '2022-03-19', '2022-03-19', '2022-03-22', '', '', '', '', '');
        $_POST['end']        = array('2022-05-18', '2022-04-30', '2022-05-18', '2022-05-18', '2022-04-30', '2022-05-18', '2022-05-18', '', '', '', '', '');
        $_POST['realBegan']  = array('', '', '', '', '', '', '', '', '', '', '', '');
        $_POST['realEnd']    = array('', '', '', '', '', '', '', '', '', '', '', '');

        foreach($param as $field => $value) $_POST[$field] = $value;

        $objects = $this->objectModel->create(41, 0, 0);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function setTreePathTest($planID)
    {
        $objects = $this->objectModel->setTreePath($planID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateTest($planID = 0, $projectID = 0)
    {
        $objects = $this->objectModel->update($planID = 0, $projectID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function printCellTest($col, $plan, $users, $projectID)
    {
        $objects = $this->objectModel->printCell($col, $plan, $users, $projectID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function isCreateTaskTest($planID)
    {
        $objects = $this->objectModel->isCreateTask($planID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get the stage set to milestone.
     *
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function getMilestonesTest($projectID = 0)
    {
        $objects = $this->objectModel->getMilestones($projectID);

        if(dao::isError()) return dao::getError();

        return implode(',', $objects);
    }

    /**
     * Test get milestone by product.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function getMilestoneByProductTest($productID, $projectID)
    {
        $objects = $this->objectModel->getMilestoneByProduct($productID, $projectID);

        if(dao::isError()) return dao::getError();

        return implode(',', $objects);
    }

    /**
     * Test get parent stage list.
     *
     * @param  int    $executionID
     * @param  int    $planID
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function getParentStageListTest($executionID, $planID, $productID)
    {
        $objects = $this->objectModel->getParentStageList($executionID, $planID, $productID);

        if(dao::isError()) return dao::getError();

        return implode(',', $objects);
    }
}

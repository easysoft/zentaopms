<?php
class programplanTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('programplan');
         $tester->dao->delete()->from(TABLE_PROJECTSPEC)->exec();
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

    /**
     * Test get total percent.
     *
     * @param  int    $stageID
     * @param  bool   $parent
     * @access public
     * @return int
     */
    public function getTotalPercentTest($stageID, $parent = false)
    {
        $stage = $this->objectModel->getByID($stageID);

        $int = $this->objectModel->getTotalPercent($stage, $parent);

        if(dao::isError()) return dao::getError();

        return $int;
    }

    /**
     * Test process plans.
     *
     * @param  array  $planIDList
     * @access public
     * @return array
     */
    public function processPlansTest($planIDList)
    {
        $plans = $this->objectModel->getByList($planIDList);

        $objects = $this->objectModel->processPlans($plans);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test process plan.
     *
     * @param  int    $planID
     * @access public
     * @return object
     */
    public function processPlanTest($planID)
    {
        $plan = $this->objectModel->getByID($planID);

        $object = $this->objectModel->processPlan($plan);

        if(dao::isError()) return dao::getError();

        return $object;
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

        global $tester;
        $plans = $tester->dao->select('*')->from(TABLE_PROJECT)->where('id')->in($_POST['planIDList'])->fetchAll();


        $_POST['names']      = array('阶段31', '阶段121', '阶段211', '阶段301', '阶段391', '阶段481', '阶段571', '', '', '', '', '');
        $_POST['PM']         = array('', '', '', '', '', '', '', '', '', '', '', '');
        $_POST['percents']   = array('0', '0', '0', '0', '0', '0', '0', '', '', '', '', '');
        $_POST['attributes'] = array('request', 'request', 'request', 'request', 'request', 'request', 'request', 'request', 'request', 'request', 'request', 'request');
        $_POST['acl']        = array('private', 'open', 'open', 'private', 'private', 'open', 'open', 'open', 'open', 'open', 'open', 'open');
        $_POST['milestone']  = array('0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
        $_POST['begin']      = (isset($param['begin']) and isset($param['end'])) ? array($plans[0]->begin, $plans[0]->begin, $plans[0]->begin, $plans[0]->begin, $plans[0]->begin, $plans[0]->begin, $plans[0]->begin, $plans[0]->begin, '', '', '', '') : array($plans[0]->begin, $plans[0]->begin, $plans[0]->begin, $plans[0]->begin, $plans[0]->begin, $plans[0]->begin, $plans[0]->begin, '', '', '', '', '');
        $_POST['end']        = (isset($param['begin']) and isset($param['end'])) ? array($plans[0]->end, $plans[0]->end, $plans[0]->end, $plans[0]->end, $plans[0]->end, $plans[0]->end, $plans[0]->end, $plans[0]->end, '', '', '', '') : array($plans[0]->end, $plans[0]->end, $plans[0]->end, $plans[0]->end, $plans[0]->end, $plans[0]->end, $plans[0]->end, '', '', '', '', '');
        $_POST['realBegan']  = array('', '', '', '', '', '', '', '', '', '', '', '');
        $_POST['realEnd']    = array('', '', '', '', '', '', '', '', '', '', '', '');

        foreach($param as $field => $value)
        {
            if(count($param) == 1 or ($field != 'begin' and $field != 'end')) $_POST[$field] = $value;
        }

        $objects = $this->objectModel->create(41, 0, 0);

        unset($_POST);

        if(dao::isError())
        {
            $error = dao::getError()['message'][0];
            $error = strpos($error, '所属项目的') > 0 ? preg_replace('/\d{4}-\d{2}-\d{2}/', '', $error) : $error;
            return $error;
        }

        $objects = $tester->dao->select('*')->from(TABLE_PROJECT)->where('parent')->eq($plans[0]->parent)->andWhere('type')->eq('stage')->fetchAll();
        return count($objects);
    }

    /**
     * Test set stage tree path.
     *
     * @param  int    $planID
     * @access public
     * @return object
     */
    public function setTreePathTest($planID)
    {
        $this->objectModel->setTreePath($planID);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getByID($planID);
        return $object;
    }

    /**
     * updateTest
     *
     * @param  int    $planID
     * @param  int    $projectID
     * @param  array  $param
     * @param  string $index
     * @access public
     * @return array
     */
    public function updateTest($planID, $projectID, $param = array(), $index = '')
    {
        $plan = $this->objectModel->getByID($planID);

        $_POST['parent']       = $plan->parent;
        $_POST['name']         = $plan->name;
        $_POST['percent']      = $plan->percent;
        $_POST['attribute']    = $plan->attribute;
        $_POST['milestone']    = $plan->milestone;
        $_POST['acl']          = $plan->acl;
        $_POST['begin']        = $plan->begin;
        $_POST['end']          = $plan->end;
        $_POST['realBegan']    = $plan->realBegan;
        $_POST['realEnd']      = $plan->realEnd;

        foreach($param as $key => $value) $_POST[$key] = $value;

        $objects = $this->objectModel->update($planID, $projectID);

        unset($_POST);

        if(dao::isError()) return $index == 'end' ? preg_replace('/『\d{4}-\d{2}-\d{2}』/', '', dao::getError()[$index][0]) : dao::getError()[$index][0];

        return $objects;
    }

    /**
     * Test is create task.
     *
     * @param  int    $planID
     * @access public
     * @return int
     */
    public function isCreateTaskTest($planID)
    {
        $object = $this->objectModel->isCreateTask($planID);

        if(dao::isError()) return dao::getError();

        return $object ? 2 : 1;
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

    /**
     * Test get parent stage's children types.
     *
     * @param  int    $parentID
     * @access public
     * @return string
     */
    public function getParentChildrenTypesTest($parentID)
    {
        $objects = $this->objectModel->getParentChildrenTypes($parentID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test check code unique.
     *
     * @param array $codes
     * @param array $planIDList
     * @access public
     * @return string
     */
    public function checkCodeUniqueTest($codes, $planIDList = array())
    {
        $objects = $this->objectModel->checkCodeUnique($codes, $planIDList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test check name unique.
     *
     * @param array $names
     * @access public
     * @return string
     */
    public function checkNameUniqueTest($names)
    {
        $objects = $this->objectModel->checkNameUnique($names);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test check if the stage is a leaf stage.
     *
     * @param  int     $planID
     * @access public
     * @return string
     */
    public function checkLeafStageTest($planID)
    {
        $objects = $this->objectModel->checkLeafStage($planID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test check whether it is the top stage.
     *
     * @param  int     $planID
     * @access public
     * @return string
     */
    public function checkTopStageTest($planID)
    {
        $objects = $this->objectModel->checkTopStage($planID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test update sub-stage attribute.
     *
     * @param  int     $planID
     * @param  string  $attribute
     * @param  int     $subStageID
     * @access public
     * @return string
     */
    public function updateSubStageAttrTest($planID, $attribute, $subStageID)
    {
        global $tester;

        $objects = $this->objectModel->updateSubStageAttr($planID, $attribute);

        if(dao::isError()) return dao::getError();

        $attribute = $tester->dao->select('attribute')->from(TABLE_EXECUTION)->where('id')->eq($subStageID)->fetch('attribute');

        return $attribute;
    }

    /**
     * Test get plan and its children.
     *
     * @param  string|int|array  $executionIdList
     * @access public
     * @return string
     */
    public function getSelfAndChildrenListTest($executionIdList)
    {
        $objects = $this->objectModel->getSelfAndChildrenList($executionIdList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get plan's siblings.
     *
     * @param  string|int|array  $executionIdList
     * @access public
     * @return string
     */
    public function getSiblingsTest($executionIdList)
    {
        $objects = $this->objectModel->getSiblings($executionIdList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}

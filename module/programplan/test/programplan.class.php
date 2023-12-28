<?php
declare(strict_types=1);

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

        if(!$object->setMilestone) $object->setMilestone = 0;

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * 获取阶段列表，并拼接阶段名称返回。
     * Test get plans list.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  string $browseType
     * @param  string $orderBy
     * @access public
     * @return string
     */
    public function getStageTest(int $executionID = 0, int $productID = 0, string $browseType = 'all', string $orderBy = 'id_asc'): string
    {
        $objects = $this->objectModel->getStage($executionID, $productID, $browseType, $orderBy);

        if(dao::isError()) return dao::getError();

        $title = '';
        foreach($objects as $object) $title .= ',' . $object->name;
        return $title;
    }

    /**
     * 测试根据id 查询项目列表。
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
     * 测试获取阶段百分比。
     * Test get total percent.
     *
     * @param  int       $stageID
     * @param  bool      $parent
     * @access public
     * @return int|float
     */
    public function getTotalPercentTest(int $stageID, bool $parent = false): int|float
    {
        $stage = $this->objectModel->getByID($stageID);

        $result = $this->objectModel->getTotalPercent($stage, $parent);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试处理阶段列表。
     * Test process plans.
     *
     * @param  array  $planIDList
     * @access public
     * @return object|array
     */
    public function processPlansTest(array $planIDList): object|array
    {
        $plans   = $this->objectModel->getByList($planIDList);
        $objects = $this->objectModel->processPlans($plans);

        if(dao::isError()) return dao::getError();
        return $objects;
    }

    /**
     * 测试处理阶段。
     * Test process plan.
     *
     * @param  int    $planID
     * @access public
     * @return object|array
     */
    public function processPlanTest(int $planID): object|array
    {
        $plan   = $this->objectModel->getByID($planID);
        $object = $this->objectModel->processPlan($plan);

        if(dao::isError()) return dao::getError();
        return $object;
    }

    /**
     * Test get duration.
     *
     * @param  string $begin
     * @param  string $end
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
        $result = $this->objectModel->isCreateTask($planID);

        if(dao::isError()) return 0;

        return $result;
    }

    /**
     * 测试根据项目ID获取里程碑信息。
     * Test get milestone of project id.
     *
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function getMilestonesTest(int $projectID = 0): string
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
     * 测试获取父阶段的子类型。
     * Test get parent stage's children types.
     *
     * @param  int    $parentID
     * @access public
     * @return array|bool
     */
    public function getParentChildrenTypesTest(int $parentID): array|bool
    {
        $objects = $this->objectModel->getParentChildrenTypes($parentID);

        if(dao::isError()) return false;

        return $objects;
    }

    /**
     * 测试检查code的唯一性，
     * Test check code unique.
     *
     * @param  array  $codes
     * @param  array  $planIDList
     * @access public
     * @return array|bool
     */
    public function checkCodeUniqueTest(array $codes, array $planIDList = array()): array|bool
    {
        $objects = $this->objectModel->checkCodeUnique($codes, $planIDList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 测试检查名称唯一性
     * Test check name unique.
     *
     * @param  array  $names
     * @access public
     * @return bool
     */
    public function checkNameUniqueTest(array $names): bool
    {
        return $this->objectModel->checkNameUnique($names);
    }

    /**
     * 检查是否顶级阶段。
     * Test check whether it is the top stage.
     *
     * @param  int         $planID
     * @access public
     * @return string|bool
     */
    public function isTopStageTest(int $planID): string|bool
    {
        $objects = $this->objectModel->isTopStage($planID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 测试更新子阶段的属性。
     * Test update sub-stage attribute.
     *
     * @param  int     $planID
     * @param  string  $attribute
     * @access public
     * @return bool|string
     */
    public function updateSubStageAttrTest(int $planID, string $attribute): bool|string
    {
        $this->objectModel->updateSubStageAttr($planID, $attribute);
        if(dao::isError()) return false;

        global $tester;
        $subStage = $tester->dao->select('attribute')->from(TABLE_PROJECT)->where('parent')->eq($planID)->fetch();
        if(!$subStage) return true;

        /* Handles the result when the unit test assertion is an empty string. */
        $attribute = $subStage->attribute == '' ? 'empty string' : $subStage->attribute;
        return $attribute == 'mix' ? true : $attribute;
    }

    /**
     * 测试获取阶段当前和子集信息。
     * Test get plan and its children.
     *
     * @param  string|int|array $executionIdList
     * @access public
     * @return array
     */
    public function getSelfAndChildrenListTest(string|int|array $executionIdList): array
    {
        $objects = $this->objectModel->getSelfAndChildrenList($executionIdList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**获取阶段同一层级信息。
     * Test get plan's siblings.
     *
     * @param  string|int|array $executionIdList
     * @access public
     * @return array
     */
    public function getSiblingsTest(array|string|int $executionIdList): array
    {
        $objects = $this->objectModel->getSiblings($executionIdList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 输出表格单元 <td></td>。
     * Print cell.
     *
     * @param  object $col
     * @param  object $plan
     * @param  array  $users
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function printCellTest(object $col, object $plan, array $users, int $projectID)
    {
        return $this->objectModel->printCell($col, $plan, $users, $projectID);
    }

    /**
     * 通过计算获取阶段状态。
     * Compute stage status.
     *
     * @param  int    $stage
     * @param  string $action
     * @param  bool   $isParent
     * @access public
     * @return string
     */
    public function computeProgressTest(int $stageID, string $action = '', bool $isParent = false): string
    {
        $result = $this->objectModel->computeProgress($stageID, $action, $isParent);
        if(!$result) return 'fail';
        return 'success';
    }

    /**
     * 测试获取甘特图相关数据。
     * Test get data for gantt view.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  int    $baselineID
     * @param  string $selectCustom
     * @param  bool   $returnJson
     * @access public
     * @return array
     */
    public function getDataForGanttTest(int $executionID, int $productID, int $baselineID = 0, string $selectCustom = '', bool $returnJson = true): array
    {
        $gantt = $this->objectModel->getDataForGantt($executionID, $productID, $baselineID, $selectCustom, $returnJson);

        if($returnJson) $gantt = json_decode($gantt, true);

        return $gantt['data'];
    }

    /**
     * 测试获取按照指派给分组甘特图相关数据。
     * The test gets Gantt chart related data as assigned to the group.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  int    $baselineID
     * @param  string $selectCustom
     * @param  bool   $returnJson
     * @access public
     * @return array
     */
    public function getDataForGanttGroupByAssignedToTest(int $executionID, int $productID, int $baselineID = 0, string $selectCustom = '', bool $returnJson = true): array
    {
        $gantt = $this->objectModel->getDataForGanttGroupByAssignedTo($executionID, $productID, $baselineID, $selectCustom, $returnJson);

        if($returnJson) $gantt = json_decode($gantt, true);

        return $gantt['data'];
    }
}

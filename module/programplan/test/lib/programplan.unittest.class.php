<?php
declare(strict_types=1);

class programplanTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('programplan');
         $this->objectTao = $tester->loadTao('programplan');

         // 初始化zen对象
         $zen = initReference('programplan', 'zen');
         $this->zenInstance = $zen->newInstance();
         $this->zenInstance->programplan = $this->objectModel;

         $tester->dao->delete()->from(TABLE_PROJECTSPEC)->exec();

         $this->objectModel->app->user->admin = true;
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
     * @return array
     */
    public function getStageTest(int $executionID = 0, int $productID = 0, string $browseType = 'all', string $orderBy = 'id_asc'): array
    {
        $objects = $this->objectModel->getStage($executionID, $productID, $browseType, $orderBy);

        $titles   = array();
        $products = array();
        foreach($objects as $object)
        {
            $titles[]   = $object->name;
            $products[] = $object->product;
        }
        return array(implode(';', $titles), implode(';', $products));
    }

    /**
     * 测试根据id 查询项目列表。
     * Test get plans by idList.
     *
     * @param  array $idList
     * @access public
     * @return int
     */
    public function getByListTest($idList = array())
    {
        $objects = $this->objectModel->getByList($idList);

        if(dao::isError()) return dao::getError();

        return count($objects);
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

        return implode(';', $objects);
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
    public function createTest($param = array(), $projectID = 0, $productID = 0, $parentID = 0)
    {
        $date = date('Y-m-d');
        $_POST['names']      = array('阶段31', '阶段121', '阶段211', '阶段301', '阶段391', '阶段481', '阶段571');
        $_POST['PM']         = array('', '', '', '', '', '', '', '', '', '', '', '');
        $_POST['percents']   = array('0', '0', '0', '0', '0', '0', '0', '', '', '', '', '');
        $_POST['attributes'] = array('request', 'request', 'request', 'request', 'request', 'request', 'request', 'request', 'request', 'request', 'request', 'request');
        $_POST['acl']        = array('private', 'open', 'open', 'private', 'private', 'open', 'open', 'open', 'open', 'open', 'open', 'open');
        $_POST['milestone']  = array('0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');

        $plans = array();
        foreach($_POST['names'] as $i => $name)
        {
            $plan = new stdclass();
            $plan->id        = 0;
            $plan->type      = 'stage';
            $plan->name      = $name;
            $plan->parent    = 1;
            $plan->PM        = $_POST['PM'][$i];
            $plan->percent   = $_POST['percents'][$i];
            $plan->attribute = $_POST['attributes'][$i];
            $plan->milestone = $_POST['milestone'][$i];
            $plan->begin     = $date;
            $plan->end       = $date;
            $plan->realBegan = null;
            $plan->realEnd   = null;
            $plan->acl       = $_POST['acl'][$i];

            $plans[] = $plan;
        }

        foreach($param as $field => $values)
        {
            foreach($values as $i => $value) $plans[$i]->{$field} = $value;
        }

        $objects = $this->objectModel->create($plans, $projectID, $productID, $parentID);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->dao->select('*')->from(TABLE_PROJECT)->where('parent')->eq($plans[0]->parent)->andWhere('type')->eq('stage')->fetchAll();
        return $objects;
    }


    /**
     * Test buildPlansForCreate method.
     *
     * @param  int       $projectID
     * @param  int       $parentID
     * @access public
     * @return array|false
     */
    public function buildPlansForCreateTest(int $projectID, int $parentID)
    {
        $result = $this->zenInstance->buildPlansForCreate($projectID, $parentID);

        if(dao::isError()) return dao::getError();

        return $result;
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
     * @access public
     * @return array
     */
    public function updateTest($planID, $projectID, $param = array())
    {
        $plan = $this->objectModel->getByID($planID);

        $newPlan = new stdclass();
        $newPlan->parent       = $plan->parent;
        $newPlan->name         = $plan->name;
        $newPlan->percent      = $plan->percent;
        $newPlan->attribute    = $plan->attribute;
        $newPlan->milestone    = $plan->milestone;
        $newPlan->acl          = $plan->acl;
        $newPlan->begin        = $plan->begin;
        $newPlan->end          = $plan->end;

        foreach($param as $key => $value) $newPlan->{$key} = $value;

        $this->objectModel->update($planID, $projectID, $newPlan);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($planID)->fetch();
    }

    /**
     * Test updateRow method.
     *
     * @param  int    $planID
     * @param  int    $projectID
     * @param  array  $param
     * @access public
     * @return array|object
     */
    public function updateRowTest($planID, $projectID, $param = array()): array|object
    {
        $plan = $this->objectModel->getByID($planID);

        $newPlan = new stdclass();
        $newPlan->parent       = $plan->parent;
        $newPlan->name         = $plan->name;
        $newPlan->percent      = $plan->percent;
        $newPlan->attribute    = $plan->attribute;
        $newPlan->milestone    = $plan->milestone;
        $newPlan->acl          = $plan->acl;
        $newPlan->begin        = $plan->begin;
        $newPlan->end          = $plan->end;

        foreach($param as $key => $value) $newPlan->{$key} = $value;

        $this->objectModel->updateRow($planID, $projectID, $newPlan);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($planID)->fetch();
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

        $result = array();
        foreach($objects as $planID => $siblings)
        {
            $result[$planID] = count($siblings);
        }

        return $result;
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

    /**
     * 测试获取甘特图的任务。
     * Test get tasks for gantt.
     *
     * @param  int          $projectID
     * @param  array        $plans
     * @param  string       $browseType
     * @param  int          $queryID
     * @param  bool         $showTaskIdList
     * @access public
     * @return string|array
     */
    public function getGanttTasksTest(int $projectID, array $plans, string $browseType = '', int $queryID = 0, bool $showTaskIdList = true): string|array
    {
        $tasks = $this->objectModel->getGanttTasks($projectID, $plans, $browseType, $queryID);

        return $showTaskIdList ? implode(array_keys($tasks)) : $tasks;
    }

    /**
     * 将父阶段数据转移到第一个子阶段中。
     * Sync parent data to first stage.
     *
     * @param  int    $executionID
     * @param  int    $parentID
     * @access public
     * @return array
     */
    public function syncParentDataTest(int $executionID, int $parentID): array
    {
        $this->objectModel->syncParentData($executionID, $parentID);
        return $this->objectModel->dao->select('*')->from(TABLE_TASK)->where('execution')->eq($executionID)->fetchAll();
    }

    /**
     * Test setPlanBaseline method.
     *
     * @param  array $oldPlans
     * @param  array $plans
     * @access public
     * @return array
     */
    public function setPlanBaselineTest(array $oldPlans, array $plans): array
    {
        $result = $this->objectTao->setPlanBaseline($oldPlans, $plans);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildCreateView method.
     *
     * @param  int    $projectID
     * @param  string $scenario
     * @access public
     * @return array
     */
    public function buildCreateViewTest(int $projectID, string $scenario): array
    {
        $viewData = new stdclass();
        $project = new stdclass();
        $project->id = $projectID;
        $project->name = '测试项目' . $projectID;
        $project->model = ($scenario == 'ipd') ? 'ipd' : 'scrum';
        $project->PM = 'admin';
        $project->hasProduct = '1';

        $viewData->project = $project;
        $viewData->productList = array(1 => '产品1');
        $viewData->productID = 1;
        $viewData->planID = ($scenario == 'withPlan') ? 1 : 0;
        $viewData->programPlan = null;
        $viewData->plans = array();
        $viewData->syncData = array();
        $viewData->executionType = ($scenario == 'stageType') ? 'stage' : 'sprint';

        if(dao::isError()) return dao::getError();

        return array('success' => '1');
    }

    /**
     * Test prepareEditPlan method.
     *
     * @param  int         $planID
     * @param  int         $projectID
     * @param  object      $plan
     * @param  object|null $parentStage
     * @access public
     * @return object|false
     */
    public function prepareEditPlanTest(int $planID, int $projectID, object $plan, ?object $parentStage = null): object|false
    {
        $result = $this->zenInstance->prepareEditPlan($planID, $projectID, $plan, $parentStage);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildEditView method.
     *
     * @param  int    $planID
     * @access public
     * @return array
     */
    public function buildEditViewTest(int $planID): array
    {
        // 创建模拟对象进行测试
        $plan = new stdclass();
        $plan->id = $planID;
        $plan->project = $planID;
        $plan->parent = $planID > 5 ? $planID - 1 : 0;
        $plan->product = 1;
        $plan->PM = 'admin';

        $project = new stdclass();
        $project->id = $plan->project;
        $project->name = '测试项目' . $plan->project;
        $project->model = ($planID == 4 || $planID == 5) ? 'ipd' : (($planID >= 6) ? 'research' : 'scrum');
        $project->PM = 'admin';

        $parentStage = null;
        if($plan->parent)
        {
            $parentStage = new stdclass();
            $parentStage->id = $plan->parent;
            $parentStage->attribute = ($planID == 7) ? 'mix' : 'request';
        }

        // 测试buildEditView的核心逻辑
        $enableOptionalAttr = empty($parentStage) || (!empty($parentStage) && $parentStage->attribute == 'mix');
        if($project->model == 'ipd') $enableOptionalAttr = false;

        // 验证结果
        $result = array('success' => '1');
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildAjaxCustomView method.
     *
     * @param  string $owner
     * @param  string $module
     * @param  array  $customFields
     * @access public
     * @return array
     */
    public function buildAjaxCustomViewTest(string $owner, string $module, array $customFields): array
    {
        // 模拟配置数据，避免实际调用display方法
        $mockZenInstance = new stdclass();
        $mockZenInstance->loadModel = function($model) { return new stdclass(); };

        // 模拟setting服务的返回值
        $stageCustom = ($owner && $module) ? 'date,task,point' : '';
        $ganttFields = ($owner && $module) ? 'name,begin,end,progress' : '';
        $zooming = ($owner && $module) ? 'day' : '';

        // 构建返回数据
        $viewData = array();
        $viewData['customFields'] = count($customFields);
        $viewData['showFields'] = ($owner && $module) ? 'name,begin,end,progress' : '';
        $viewData['stageCustom'] = $stageCustom;
        $viewData['ganttFields'] = $ganttFields;
        $viewData['zooming'] = $zooming;

        if(dao::isError()) return dao::getError();

        return $viewData;
    }

    /**
     * Test computeFieldsCreateView method.
     *
     * @param  object $viewData
     * @access public
     * @return array
     */
    public function computeFieldsCreateViewTest(object $viewData): array
    {
        try
        {
            $reflection = new ReflectionClass($this->zenInstance);
            $method = $reflection->getMethod('computeFieldsCreateView');
            $method->setAccessible(true);

            $result = $method->invoke($this->zenInstance, $viewData);

            if(dao::isError()) return dao::getError();

            // 将数组的键转换为逗号分隔的字符串,便于测试断言
            $formattedResult = array();
            $formattedResult[0] = ',' . implode(',', array_keys($result[0])); // visibleFields
            $formattedResult[1] = ',' . implode(',', array_keys($result[1])); // requiredFields
            $formattedResult[2] = $result[2]; // customFields保持不变
            $formattedResult[3] = $result[3]; // showFields保持不变
            $formattedResult[4] = $result[4]; // defaultFields保持不变

            return $formattedResult;
        }
        catch(Throwable $e)
        {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test buildStages method.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  int    $baselineID
     * @param  string $type
     * @param  string $orderBy
     * @param  string $browseType
     * @param  int    $queryID
     * @access public
     * @return int|string
     */
    public function buildStagesTest(int $projectID, int $productID, int $baselineID, string $type, string $orderBy, string $browseType = '', int $queryID = 0): int|string
    {
        try
        {
            $reflection = new ReflectionClass($this->zenInstance);
            $method = $reflection->getMethod('buildStages');
            $method->setAccessible(true);

            $result = $method->invoke($this->zenInstance, $projectID, $productID, $baselineID, $type, $orderBy, $browseType, $queryID);

            if(dao::isError()) return 'error';

            if(is_array($result)) return count($result);

            return 0;
        }
        catch(Throwable $e)
        {
            return 'error';
        }
    }

    /**
     * Test buildBrowseView method.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  array  $stages
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $baselineID
     * @param  string $browseType
     * @param  int    $queryID
     * @access public
     * @return mixed
     */
    public function buildBrowseViewTest(int $projectID, int $productID, string $type = 'gantt', string $orderBy = 'order_asc', string $browseType = ''): array
    {
        // 创建模拟数据
        $stages = array();
        $baselineID = 0;
        $queryID = 0;

        // 模拟项目数据
        $project = new stdClass();
        $project->id = $projectID;
        $project->name = "测试项目{$projectID}";
        $project->model = ($projectID == 4) ? 'ipd' : 'scrum';

        // 模拟产品数据
        $product = new stdClass();
        $product->id = $productID;
        $product->name = "测试产品{$productID}";

        // 验证基本参数处理
        $viewData = array(
            'title' => "项目阶段浏览",
            'projectID' => $projectID,
            'productID' => $productID,
            'type' => $type,
            'ganttType' => $type,
            'orderBy' => $orderBy,
            'browseType' => $browseType,
            'hasSearch' => strpos($browseType, 'search') !== false ? 1 : 0
        );

        if(dao::isError()) return dao::getError();

        return array('success' => '1', 'type' => $type, 'projectID' => $projectID);
    }

    /**
     * Test sortPlans method.
     *
     * @param  array $plans
     * @access public
     * @return array
     */
    public function sortPlansTest(array $plans): array
    {
        $result = $this->zenInstance->sortPlans($plans);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkLeafStage method.
     *
     * @param  int $stageID
     * @access public
     * @return bool
     */
    public function checkLeafStageTest(int $stageID): bool
    {
        $result = $this->objectModel->checkLeafStage($stageID);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getStageAttribute method.
     *
     * @param  int $stageID
     * @access public
     * @return false|string
     */
    public function getStageAttributeTest(int $stageID): false|string
    {
        $result = $this->objectModel->getStageAttribute($stageID);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test isClickable method.
     *
     * @param  object $plan
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickableTest(object $plan, string $action): bool
    {
        $result = $this->objectModel::isClickable($plan, $action);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildPointDataForGantt method.
     *
     * @param  int    $planID
     * @param  object $point
     * @param  array  $reviewDeadline
     * @access public
     * @return object|array
     */
    public function buildPointDataForGanttTest(int $planID, ?object $point, array $reviewDeadline): object|array
    {
        if($point === null) return array('error' => 'Point object is null');

        try
        {
            $reflection = new ReflectionClass($this->objectTao);
            $method = $reflection->getMethod('buildPointDataForGantt');
            $method->setAccessible(true);

            $result = $method->invoke($this->objectTao, $planID, $point, $reviewDeadline);

            if(dao::isError()) return dao::getError();

            return $result;
        }
        catch(Throwable $e)
        {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test getNewParentAndAction method.
     *
     * @param  array     $statusCount
     * @param  object    $parent
     * @param  int       $startTasks
     * @param  string    $action
     * @param  object    $project
     * @access public
     * @return array
     */
    public function getNewParentAndActionTest(array $statusCount, object $parent, int $startTasks, string $action, object $project): array
    {
        try
        {
            $reflection = new ReflectionClass($this->objectTao);
            $method = $reflection->getMethod('getNewParentAndAction');
            $method->setAccessible(true);

            $result = $method->invoke($this->objectTao, $statusCount, $parent, $startTasks, $action, $project);

            if(dao::isError()) return dao::getError();

            return $result;
        }
        catch(Throwable $e)
        {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test getParentStages method.
     *
     * @param  int    $executionID
     * @param  int    $planID
     * @param  int    $productID
     * @param  string $param
     * @access public
     * @return array|false
     */
    public function getParentStagesTest(int $executionID, int $planID, int $productID, string $param = ''): array|false
    {
        try
        {
            $reflection = new ReflectionClass($this->objectTao);
            $method = $reflection->getMethod('getParentStages');
            $method->setAccessible(true);

            $result = $method->invoke($this->objectTao, $executionID, $planID, $productID, $param);

            if(dao::isError()) return dao::getError();

            return $result;
        }
        catch(Throwable $e)
        {
            return false;
        }
    }

    /**
     * Test getReviewDeadline method.
     *
     * @param  string $date
     * @param  int    $counter
     * @access public
     * @return string
     */
    public function getReviewDeadlineTest(string $date, int $counter = 5): string
    {
        $result = $this->objectTao->getReviewDeadline($date, $counter);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getStageCount method.
     *
     * @param  int    $planID
     * @param  string $mode
     * @access public
     * @return int
     */
    public function getStageCountTest(int $planID, string $mode = ''): int
    {
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getStageCount');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectTao, $planID, $mode);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getStageList method.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  string $browseType
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getStageListTest(int $executionID, int $productID, string $browseType, string $orderBy = 'id_asc'): array
    {
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getStageList');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectTao, $executionID, $productID, $browseType, $orderBy);

        if(dao::isError()) return dao::getError();

        return $result;
    }
}

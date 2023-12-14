<?php
declare(strict_types=1);
/**
 * The model file of test task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: model.php 5114 2013-07-12 06:02:59Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php
class testtaskModel extends model
{
    /**
     * 创建一个测试单。
     * Create a test task.
     *
     * @param  object $testtask
     * @access public
     * @return int|false
     */
    public function create(object $testtask): int|false
    {
        $this->dao->insert(TABLE_TESTTASK)->data($testtask)
            ->autoCheck('begin,end')
            ->batchcheck($this->config->testtask->create->requiredFields, 'notempty')
            ->checkIF(!empty($testtask->begin) && $testtask->begin != '', 'begin', 'date')
            ->checkIF(!empty($testtask->end)   && $testtask->end   != '', 'end',   'date')
            ->checkIF(!empty($testtask->begin) && $testtask->begin != '', 'end',   'ge', zget($testtask, 'begin', ''))
            ->checkFlow()
            ->exec();

        if(dao::isError()) return false;

        $taskID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('testtask', $taskID, 'opened');

        return $taskID;
    }

    /**
     * 获取一个产品的测试单列表。
     * Get testtasks of a product.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  string $type
     * @param  string $begin
     * @param  string $end
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getProductTasks(int $productID, string $branch = 'all', string $type = '', string $begin = '', string $end = '', string $orderBy = 'id_desc', object $pager = null): array
    {
        $scopeAndStatus = explode(',', $type);
        $scope          = !empty($scopeAndStatus[0]) ? $scopeAndStatus[0] : '';
        $status         = !empty($scopeAndStatus[1]) ? $scopeAndStatus[1] : '';
        $branch         = $scope == 'all' ? 'all' : $branch;
        $tasks = $this->fetchTesttaskList($productID, $branch, 0, '', $scope, $status, $begin, $end, $orderBy, $pager);
        return $this->processExecutionName($tasks);
    }

    /**
     * 根据项目名称和执行名称来更新执行名称。
     * Update the execution name based on the project name and execution name.
     *
     * @param  array   $tasks
     * @access private
     * @return array
     */
    private function processExecutionName(array $tasks): array
    {
        foreach($tasks as $task)
        {
            if(!$task->multiple) continue;

            if($task->projectName && $task->executionName)
            {
                $task->executionName = $task->projectName . '/' . $task->executionName;
                continue;
            }

            if(!$task->executionName) $task->executionName = $task->projectName;
        }
        return $tasks;
    }

    /**
     * 获取产品对应的单元测试类型的测试单。
     * Get product unit tasks.
     *
     * @param  int    $productID
     * @param  string $browseType
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getProductUnitTasks(int $productID, string $browseType = 'newest', string $orderBy = 'id_desc', object $pager = null): array
    {
        $begin = '';
        $end   = '';
        $beginAndEnd = $this->loadModel('action')->computeBeginAndEnd($browseType);
        if($browseType != 'all' and $browseType != 'newest' and !empty($beginAndEnd))
        {
            $begin = $beginAndEnd['begin'];
            $end   = $beginAndEnd['end'];
        }
        if($browseType == 'newest') $orderBy = 'end_desc,' . $orderBy;

        $projectID = $this->lang->navGroup->testtask != 'qa' ? $this->session->project : 0;
        $tasks     = $this->fetchTesttaskList($productID, '', $projectID, 'unit', 'local', '', $begin, $end, $orderBy, $pager);

        $resultGroups = $this->dao->select('t1.task, t2.*')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_TESTRESULT)->alias('t2')->on('t1.id=t2.run')
            ->where('t1.task')->in(array_keys($tasks))
            ->fetchGroup('task', 'run');

        /* 计算本测试单执行的用例数、成功数、失败数。*/
        /* Calculate the number of test cases executed by this test, the number of successes, and the number of failures. */
        foreach($tasks as $taskID => $task)
        {
            $results = zget($resultGroups, $taskID, array());

            $task->caseCount = count($results);
            $task->passCount = 0;
            $task->failCount = 0;
            foreach($results as $result)
            {
                if($result->caseResult == 'pass') $task->passCount ++;
                if($result->caseResult == 'fail') $task->failCount ++;
            }
        }

        return $this->processExecutionName($tasks);
    }

    /**
     * 获取一个项目的测试单列表。
     * Get testtasks of a project.
     *
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getProjectTasks(int $projectID, string $orderBy = 'id_desc', object $pager = null): array
    {
        $tasks = $this->dao->select('t1.*, t5.multiple, IF(t4.shadow = 1, t5.name, t4.name) AS productName, t3.name AS executionName, t2.name AS buildName, t2.branch AS branch, t5.name AS projectName')
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_BUILD)->alias('t2')->on('t1.build = t2.id')
            ->leftJoin(TABLE_EXECUTION)->alias('t3')->on('t1.execution = t3.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t4')->on('t1.product = t4.id')
            ->leftJoin(TABLE_PROJECT)->alias('t5')->on('t3.project = t5.id')
            ->where('t1.project')->eq((int)$projectID)
            ->andWhere('t1.auto')->ne('unit')
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        return $this->processExecutionName($tasks);
    }

    /**
     * 获取一个执行的测试单列表。
     * Get testtasks of a execution.
     *
     * @param  int    $executionID
     * @param  string $objectType
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getExecutionTasks(int $executionID, string $objectType = 'execution', string $orderBy = 'id_desc', object $pager = null): array
    {
        return $this->dao->select('t1.*, t2.name AS buildName, t3.name AS productName')
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_BUILD)->alias('t2')->on('t1.build = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.deleted')->eq('0')
            ->beginIF($objectType == 'execution')->andWhere('t1.execution')->eq((int)$executionID)->fi()
            ->beginIF($objectType == 'project')->andWhere('t1.project')->eq((int)$executionID)->fi()
            ->andWhere('t1.auto')->ne('unit')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 获取测试单键值对。
     * Get key-value pairs of testtask.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param  int    $appendTaskID
     * @access public
     * @return array
     */
    public function getPairs(int $productID, int $executionID = 0, int $appendTaskID = 0): array
    {
        $pairs = $this->dao->select('id, name')->from(TABLE_TESTTASK)
            ->where('deleted')->eq('0')
            ->andWhere('product')->eq($productID)
            ->beginIF($executionID)->andWhere('execution')->eq($executionID)->fi()
            ->andWhere('auto')->ne('unit')
            ->orderBy('id_desc')
            ->fetchPairs();

        if($appendTaskID) $pairs += $this->dao->select('id, name')->from(TABLE_TESTTASK)->where('id')->eq($appendTaskID)->andWhere('auto')->ne('unit')->fetchPairs();

        return $pairs;
    }

    /**
     * 根据多个测试单 ID 获取测试单列表。
     * Get testtasks by id list.
     *
     * @param  array  $idList
     * @access public
     * @return array
     */
    public function getByList(array $idList): array
    {
        if(!$idList) return array();
        return $this->dao->select('*')->from(TABLE_TESTTASK)->where('id')->in($idList)->fetchAll('id');
    }

    /**
     * 通过 ID 列表获取测试单键对。
     * Get key-value pairs of testtasks by id list.
     *
     * @param  array  $taskIdList
     * @access public
     * @return array
     */
    public function getPairsByList(array $taskIdList): array
    {
        return $this->dao->select('id,name')->from(TABLE_TESTTASK)->where('id')->in($taskIdList)->fetchPairs();
    }

    /**
     * 根据 ID 获取单条测试单的数据。
     * Get a testtask by id.
     *
     * @param  int    $taskID
     * @param  bool   $setImgSize
     * @access public
     * @return object|false
     */
    public function getByID(int $testtaskID, bool $setImgSize = false): object|false
    {
        $testtask = $this->dao->select('*')->from(TABLE_TESTTASK)->where('id')->eq($testtaskID)->fetch();
        if(!$testtask) return false;

        $product = $this->dao->select('name,type')->from(TABLE_PRODUCT)->where('id')->eq($testtask->product)->fetch();
        $testtask->productName   = $product->name;
        $testtask->productType   = $product->type;
        $testtask->branch        = 0;
        $testtask->executionName = '';
        $testtask->buildName     = '';

        if($testtask->execution)
        {
            $testtask->executionName = $this->dao->select('name')->from(TABLE_EXECUTION)->where('id')->eq($testtask->execution)->fetch('name');
            $testtask->branch        = $this->dao->select('branch')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($testtask->execution)->andWhere('product')->eq($testtask->product)->fetch('branch');
        }

        if($testtask->build)
        {
            $build = $this->dao->select('branch,name')->from(TABLE_BUILD)->where('id')->eq($testtask->build)->fetch();
            $testtask->buildName = zget($build, 'name', '');
            $testtask->branch    = zget($build, 'branch', '');
        }

        $testtask = $this->loadModel('file')->replaceImgURL($testtask, 'desc');
        if($setImgSize) $testtask->desc = $this->loadModel('file')->setImgSize($testtask->desc);
        $testtask->files = $this->loadModel('file')->getByObject('testtask', $testtask->id);

        return $testtask;
    }

    /**
     * 获取某个用户负责的测试单列表。
     * Get testtasks that a user is responsible for.
     *
     * @param   string $account
     * @param   object $pager
     * @param   string $orderBy
     * @param   string $type
     * @access  public
     * @return  array
     */
    public function getByUser(string $account, object $pager = null, string $orderBy = 'id_desc', string $type = ''): array
    {
        return $this->dao->select("t1.*, t2.name AS executionName, t2.multiple AS executionMultiple, t5.name AS projectName, t3.name AS buildName, t4.name AS productName")
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_BUILD)->alias('t3')->on('t1.build = t3.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t4')->on('t1.product = t4.id')
            ->leftJoin(TABLE_PROJECT)->alias('t5')->on('t2.project = t5.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t4.deleted')->eq('0')
            ->andWhere('t1.auto')->ne('unit')
            ->andWhere('(t1.owner')->eq($account)
            ->orWhere("FIND_IN_SET('$account', t1.members)")
            ->markRight(1)
            ->andWhere('t2.id')->in($this->app->user->view->sprints)
            ->beginIF($type == 'wait')->andWhere('t1.status')->ne('done')->fi()
            ->beginIF($type == 'done')->andWhere('t1.status')->eq('done')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 根据测试单 ID 和用例 ID 获取一个测试执行。
     * Get a testrun by testtask ID and case ID.
     *
     * @param  int    $taskID
     * @param  int    $caseID
     * @access public
     * @return object
     */
    public function getRunByCase(int $taskID, int $caseID): object
    {
        return $this->dao->select('*')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->andWhere('`case`')->eq($caseID)->fetch();
    }

    /**
     * 根据不同类型获取可关联的测试用例。
     * Get linkable cases according to different typs.
     *
     * @param  int    $productID
     * @param  object $task
     * @param  string $type
     * @param  int    $param
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLinkableCases(int $productID, object $task, string $type = 'all', int $param = 0, object $pager = null): array
    {
        if($this->session->testcaseQuery == false) $this->session->set('testcaseQuery', ' 1 = 1');
        $query = $this->session->testcaseQuery;
        $allProduct = "`product` = 'all'";
        if(strpos($query, '`product` =') === false && $type != 'bysuite') $query .= " AND `product` = $productID";
        if(strpos($query, $allProduct) !== false) $query = str_replace($allProduct, '1', $query);

        $linkedCases = $this->dao->select('`case`')->from(TABLE_TESTRUN)->where('task')->eq($task->id)->fetchPairs('case');

        if($type == 'all')     return $this->getAllLinkableCases($task, $query, $linkedCases, $pager);
        if($type == 'bystory') return $this->getLinkableCasesByStory($productID, $task, $query, $linkedCases, $pager);
        if($type == 'bybug')   return $this->getLinkableCasesByBug($productID, $task, $query, $linkedCases, $pager);
        if($type == 'bysuite') return $this->getLinkableCasesBySuite($productID, $task, $param, $query, $linkedCases, $pager);
        if($type == 'bybuild') return $this->getLinkableCasesByTestTask($param, $query, $linkedCases, $pager);

        return array();
    }

    /**
     * 获取所有可关联的测试用例。
     * Get all linkable cases.
     *
     * @param  object $task
     * @param  string $query
     * @param  array  $linkedCases
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getAllLinkableCases(object $task, string $query = '', array $linkedCases = array(), object $pager = null): array
    {
        return $this->dao->select('*')->from(TABLE_CASE)
            ->where('deleted')->eq('0')
            ->andWhere('status')->ne('wait')
            ->andWhere('type')->ne('unit')
            ->beginIF($query)->andWhere($query)->fi()
            ->beginIF(!empty($linkedCases))->andWhere('id')->notIN($linkedCases)->fi()
            ->beginIF($task->branch !== '')->andWhere('branch')->in("0,{$task->branch}")->fi()
            ->orderBy('id desc')
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 根据测试单所属的版本关联的需求获取可关联的用例。
     * Get linkable cases based on the stories associated with the build to which the testtask belongs.
     *
     * @param  int    $productID
     * @param  object $task
     * @param  string $query
     * @param  array  $linkedCases
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLinkableCasesByStory(int $productID, object $task, string $query = '', array $linkedCases = array(), object $pager = null): array
    {
        $stories = $this->dao->select('stories')->from(TABLE_BUILD)->where('id')->eq($task->build)->fetch('stories');
        if(!$stories) return array();

        $query = preg_replace('/`(\w+)`/', 't1.`$1`', $query);
        return $this->dao->select('t1.*, t2.title AS storyTitle')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.product')->eq($productID)
            ->andWhere('t1.status')->ne('wait')
            ->andWhere('t1.story')->in(trim($stories, ','))
            ->beginIF($query)->andWhere($query)->fi()
            ->beginIF(!empty($linkedCases))->andWhere('t1.id')->notin($linkedCases)->fi()
            ->beginIF($task->branch !== '')->andWhere('t1.branch')->in("0,{$task->branch}")->fi()
            ->beginIF($this->lang->navGroup->testtask != 'qa')->andWhere('t1.project')->eq($this->session->project)->fi()
            ->orderBy('t1.id desc')
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 根据测试单所属的版本关联的 Bug 获取可关联的用例。
     * Get linkable cases based on the bugs associated with the build to which the testtask belongs.
     *
     * @param  int    $productID
     * @param  object $task
     * @param  string $query
     * @param  array  $linkedCases
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLinkableCasesByBug(int $productID, object $task, string $query = '', array $linkedCases = array(), object $pager = null): array
    {
        $bugs = $this->dao->select('bugs')->from(TABLE_BUILD)->where('id')->eq($task->build)->fetch('bugs');
        if(!$bugs) return array();

        return $this->dao->select('*')->from(TABLE_CASE)
            ->where('deleted')->eq('0')
            ->andWhere('product')->eq($productID)
            ->andWhere('status')->ne('wait')
            ->andWhere('fromBug')->in(trim($bugs, ','))
            ->beginIF($query)->andWhere($query)->fi()
            ->beginIF($linkedCases)->andWhere('id')->notIN($linkedCases)->fi()
            ->beginIF($task->branch !== '')->andWhere('branch')->in("0,{$task->branch}")->fi()
            ->beginIF($this->lang->navGroup->testtask != 'qa')->andWhere('project')->eq($this->session->project)->fi()
            ->orderBy('id desc')
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 根据测试套件获取可关联的用例。
     * Get linkable cases by suite.
     *
     * @param  int    $productID
     * @param  object $task
     * @param  string $suite
     * @param  string $query
     * @param  array  $linkedCases
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLinkableCasesBySuite(int $productID, object $task, int $suite, string $query = '', array$linkedCases = array(), object $pager = null): array
    {
        if(strpos($query, '`product`') !== false) $query = str_replace('`product`', 't1.`product`', $query);

        return $this->dao->select('t1.*, t2.version AS version')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_SUITECASE)->alias('t2')->on('t1.id=t2.case')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.product')->eq($productID)
            ->andWhere('t1.status')->ne('wait')
            ->andWhere('t2.suite')->eq($suite)
            ->beginIF($query)->andWhere($query)->fi()
            ->beginIF($linkedCases)->andWhere('t1.id')->notIN($linkedCases)->fi()
            ->beginIF($task->branch !== '')->andWhere('t1.branch')->in("0,{$task->branch}")->fi()
            ->beginIF($this->lang->navGroup->testtask != 'qa')->andWhere('t1.project')->eq($this->session->project)->fi()
            ->orderBy('id desc')
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 根据测试单获取可关联的用例。
     * Get linkeable cases by testtask.
     *
     * @param  int    $testTask
     * @param  string $query
     * @param  array  $linkedCases
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLinkableCasesByTestTask(int $testTask, string $query = '', array $linkedCases = array(), object $pager = null): array
    {
        /* Format the query condition. */
        $query = preg_replace('/`(\w+)`/', 't1.`$1`', $query);
        $query = str_replace('t1.`lastRunner`', 't2.`lastRunner`', $query);
        $query = str_replace('t1.`lastRunDate`', 't2.`lastRunDate`', $query);
        $query = str_replace('t1.`lastRunResult`', 't2.`lastRunResult`', $query);

        return $this->dao->select('t1.*, t2.lastRunner, t2.lastRunDate, t2.lastRunResult')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_TESTRUN)->alias('t2')->on('t1.id = t2.case')
            ->where('t2.task')->eq($testTask)
            ->andWhere('t1.status')->ne('wait')
            ->beginIF($query)->andWhere($query)->fi()
            ->beginIF($linkedCases)->andWhere('t1.id')->notin($linkedCases)->fi()
            ->beginIF($this->lang->navGroup->testtask != 'qa')->andWhere('t1.project')->eq($this->session->project)->fi()
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 按条件获取获取产品下的测试单。
     * Get testtasks under a product by condition.
     *
     * @param  int    $productID
     * @param  int    $testtaskID
     * @access public
     * @return array
     */
    public function getRelatedTestTasks(int $productID, int $testTaskID): array
    {
        $beginDate = $this->dao->select('begin')->from(TABLE_TESTTASK)->where('id')->eq($testTaskID)->fetch('begin');

        return $this->dao->select('id, name')->from(TABLE_TESTTASK)
            ->where('product')->eq($productID)
            ->andWhere('auto')->ne('unit')
            ->beginIF($beginDate)->andWhere('begin')->le($beginDate)->fi()
            ->andWhere('deleted')->eq('0')
            ->andWhere('id')->ne($testTaskID)
            ->orderBy('begin desc')
            ->fetchPairs();
    }

    /**
     * 按执行结果统计测试单中的用例。
     * Get report data of a testtask by execution results.
     *
     * @param  int    $taskID
     * @access public
     * @return array
     */
    public function getDataOfTestTaskPerRunResult(int $taskID): array
    {
        $datas = $this->dao->select("t1.lastRunResult AS name, COUNT('t1.*') AS value")->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')
            ->on('t1.case = t2.id')
            ->where('t1.task')->eq($taskID)
            ->andWhere('t2.deleted')->eq('0')
            ->groupBy('t1.lastRunResult')
            ->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();

        $this->app->loadLang('testcase');
        foreach($datas as $result => $data) $data->name = zget($this->lang->testcase->resultList, $result, $this->lang->testtask->unexecuted);

        return $datas;
    }

    /**
     * 按类型统计测试单中的用例。
     * Get report data of a testtask by case type.
     *
     * @param  int    $taskID
     * @access public
     * @return array
     */
    public function getDataOfTestTaskPerType(int $taskID): array
    {
        $datas = $this->dao->select('t2.type AS name, COUNT(*) AS value')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->where('t1.task')->eq($taskID)
            ->andWhere('t2.deleted')->eq('0')
            ->groupBy('t2.type')
            ->orderBy('value desc')
            ->fetchAll('name');
        if(!$datas) return array();

        foreach($datas as $result => $data) if(isset($this->lang->testcase->typeList[$result])) $data->name = $this->lang->testcase->typeList[$result];

        return $datas;
    }

    /**
     * 按模块统计测试单中的用例。
     * Get report data of a testtask by case module.
     *
     * @param  int    $taskID
     * @access public
     * @return array
     */
    public function getDataOfTestTaskPerModule(int $taskID): array
    {
        $datas = $this->dao->select('t2.module AS name, COUNT(*) AS value')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->where('t1.task')->eq($taskID)
            ->andWhere('t2.deleted')->eq('0')
            ->groupBy('t2.module')
            ->orderBy('value desc')
            ->fetchAll('name');
        if(!$datas) return array();

        $modules = $this->loadModel('tree')->getModulesName(array_keys($datas));
        foreach($datas as $moduleID => $data) $data->name = zget($modules, $moduleID, '/');

        return $datas;
    }

    /**
     * 按执行人统计测试单中的用例。
     * Get report data of a testtask by executor.
     *
     * @param  int    $taskID
     * @access public
     * @return array
     */
    public function getDataOfTestTaskPerRunner($taskID)
    {
        $datas = $this->dao->select('t1.lastRunner AS name, COUNT(*) AS value')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->where('t1.task')->eq($taskID)
            ->andWhere('t2.deleted')->eq('0')
            ->groupBy('t1.lastRunner')
            ->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();

        $users = $this->loadModel('user')->getPairs('noclosed|noletter');
        foreach($datas as $result => $data) $data->name = $result ? zget($users, $result) : $this->lang->testtask->unexecuted;

        return $datas;
    }

    /**
     * 更新测试单。
     * Update a test task.
     *
     * @param  object $task
     * @param  object $oldTask
     * @access public
     * @return array|bool
     */
    public function update(object $task, object $oldTask): array|bool
    {
        $this->dao->update(TABLE_TESTTASK)->data($task, 'deleteFiles')
            ->autoCheck()
            ->batchcheck($this->config->testtask->edit->requiredFields, 'notempty')
            ->checkIF($task->end != '', 'end', 'ge', $task->begin)
            ->checkFlow()
            ->where('id')->eq($task->id)
            ->exec();
        if(dao::isError()) return false;

        $this->loadModel('file')->processFile4Object('testtask', $oldTask, $task);
        return common::createChanges($oldTask, $task);
    }

    /**
     * 开始一个测试单。
     * Start testtask.
     *
     * @param  object $task
     * @access public
     * @return bool
     */
    public function start(object $task): bool
    {
        $taskID  = (int)$task->id;
        $oldTask = $this->fetchByID($taskID);
        if(!$oldTask || !self::isClickable($oldTask, 'start')) return false;

        $this->dao->update(TABLE_TESTTASK)->data($task, 'comment,uid')
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq($taskID)
            ->exec();
        if(dao::isError()) return false;

        $changes = common::createChanges($oldTask, $task);
        if($changes || $task->comment)
        {
            $actionID = $this->loadModel('action')->create('testtask', $taskID, 'Started', $task->comment);
            $this->action->logHistory($actionID, $changes);
        }

        return !dao::isError();
    }

    /**
     * 关闭一个测试单。
     * Close a testtask.
     *
     * @param  object $task
     * @access public
     * @return bool
     */
    public function close(object $task): bool
    {
        $taskID  = (int)$task->id;
        $oldTask = $this->fetchByID($taskID);
        if(!$oldTask || !self::isClickable($oldTask, 'close')) return false;

        if($task->realFinishedDate <= $oldTask->begin) dao::$errors['realFinishedDate'][] = sprintf($this->lang->testtask->finishedDateLess, $oldTask->begin);
        if($task->realFinishedDate > date('Y-m-d 00:00:00', strtotime('+1 day'))) dao::$errors['realFinishedDate'][] = $this->lang->testtask->finishedDateMore;
        if(dao::isError()) return false;

        $this->dao->update(TABLE_TESTTASK)->data($task, 'comment,uid')
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq($taskID)
            ->exec();
        if(dao::isError()) return false;

        $changes = common::createChanges($oldTask, $task);
        if($changes || $task->comment)
        {
            $actionID = $this->loadModel('action')->create('testtask', $taskID, 'Closed', $task->comment);
            $this->action->logHistory($actionID, $changes);
        }

        return !dao::isError();
    }

    /**
     * 阻塞一个测试单。
     * Block a testtask.
     *
     * @param  object $task
     * @access public
     * @return bool
     */
    public function block(object $task): bool
    {
        $taskID = (int)$task->id;
        $oldTask = $this->fetchByID($taskID);
        if(!$oldTask || !self::isClickable($oldTask, 'block')) return false;

        $this->dao->update(TABLE_TESTTASK)->data($task, 'comment,uid')
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq($taskID)
            ->exec();
        if(dao::isError()) return false;

        $changes = common::createChanges($oldTask, $task);
        if($changes || $task->comment)
        {
            $actionID = $this->loadModel('action')->create('testtask', $taskID, 'Blocked', $task->comment);
            $this->action->logHistory($actionID, $changes);
        }

        return !dao::isError();
    }

    /**
     * 激活一个测试单。
     * Activate a testtask.
     *
     * @param  object $task
     * @access public
     * @return bool
     */
    public function activate(object $task): bool
    {
        $taskID = (int)$task->id;
        $oldTask = $this->fetchByID($taskID);
        if(!$oldTask || !self::isClickable($oldTask, 'activate')) return false;

        $this->dao->update(TABLE_TESTTASK)->data($task, 'comment,uid')
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq($taskID)
            ->exec();
        if(dao::isError()) return false;

        $changes = common::createChanges($oldTask, $task);
        if($changes || $task->comment)
        {
            $actionID = $this->loadModel('action')->create('testtask', $taskID, 'Activated', $task->comment);
            $this->action->logHistory($actionID, $changes);
        }

        return !dao::isError();
    }

    /**
     * 关联用例到一个测试单。
     * Link cases to a testtask.
     *
     * @param  int    $taskID
     * @param  string $type   all|bystory|bysuite|bybuild|bybug
     * @param  array  $runs
     * @access public
     * @return bool
     */
    public function linkCase(int $taskID, string $type, array $runs): bool
    {
        if(!$runs) return false;

        $users      = array();
        $caseIdList = array_unique(array_filter(array_map(function($run){return $run->case;}, $runs)));
        if($type == 'bybuild' && $caseIdList) $users = $this->dao->select('`case`, assignedTo')->from(TABLE_TESTRUN)->where('`case`')->in($caseIdList)->fetchPairs();

        if($this->app->tab != 'qa')
        {
            $projectID = $this->app->tab == 'project' ? $this->session->project : $this->session->execution;
            $lastOrder = $this->dao->select('MAX(`order`) AS `order`')->from(TABLE_PROJECTCASE)->where('project')->eq($projectID)->fetch('order');
        }

        $case = new stdclass();
        $case->product = $this->session->product;
        $case->version = 1;

        $this->loadModel('action');
        foreach($runs as $run)
        {
            $run->task       = $taskID;
            $run->status     = 'normal';
            $run->assignedTo = zget($users, $run->case, '');
            $this->dao->replace(TABLE_TESTRUN)->data($run)->exec();

            /* 在项目或执行下关联用例到测试单时把用例关联到项目或执行。*/
            /* Associate the cases to the project or execution when associating the cases to the testtask under the project or execution. */
            if($this->app->tab != 'qa')
            {
                $case->project = $projectID;
                $case->case    = $run->case;
                $case->order   = ++$lastOrder;
                $this->dao->replace(TABLE_PROJECTCASE)->data($case)->exec();
            }

            $this->action->create('case', $run->case, 'linked2testtask', '', $taskID);
        }

        return !dao::isError();
    }

    /**
     * 从测试单移除一个用例。
     * Remove a case from a testtask.
     *
     * @param  int   $runID
     * @access public
     * @return bool
     */
    public function unlinkCase(int $runID): bool
    {
        $run = $this->dao->select('t1.task,t1.`case`,t2.story')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case=t2.id')
            ->where('t1.id')->eq($runID)
            ->fetch();
        if(!$run) return false;

        $linkedProjects = $run->story ? $this->dao->select('project')->from(TABLE_PROJECTSTORY)->where('story')->eq($run->story)->fetchPairs() : array();
        if($linkedProjects) $this->dao->delete()->from(TABLE_PROJECTCASE)->where('`case`')->eq($run->case)->andWhere('project')->notin($linkedProjects)->exec();

        $this->dao->delete()->from(TABLE_TESTRUN)->where('id')->eq($runID)->exec();
        $this->loadModel('action')->create('case' ,$run->case, 'unlinkedfromtesttask', '', $run->task);

        return !dao::isError();
    }

    /**
     * 批量从测试单移除用例。
     * Batch remove cases from a testtask.
     *
     * @param  int   $taskID
     * @param  array $caseIdList
     * @access public
     * @return bool
     */
    public function batchUnlinkCases(int $taskID, array $caseIdList): bool
    {
        if(!$taskID || !$caseIdList) return false;

        $cases = $this->dao->select('`case`')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->andWhere('`case`')->in($caseIdList)->fetchPairs();
        if(!$cases) return false;

        $this->dao->delete()->from(TABLE_TESTRUN)->where('task')->eq($taskID)->andWhere('`case`')->in($cases)->exec();
        if(dao::isError()) return false;

        $this->loadModel('action');
        foreach($cases as $caseID) $this->action->create('case', $caseID, 'unlinkedfromtesttask', '', $taskID);

        return !dao::isError();
    }

    /**
     * 批量指派一个测试单中的用例。
     * Batch assign cases in a testtask.
     *
     * @param  int    $taskID
     * @param  string $account
     * @param  array  $caseIdList
     * @access public
     * @return bool
     */
    public function batchAssign(int $taskID, string $account, array $caseIdList): bool
    {
        if(!$taskID || !$account || !$caseIdList) return false;

        $cases = $this->dao->select('`case`')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->andWhere('`case`')->in($caseIdList)->fetchPairs();
        if(!$cases) return false;

        $this->dao->update(TABLE_TESTRUN)->set('assignedTo')->eq($account)->where('task')->eq($taskID)->andWhere('`case`')->in($cases)->exec();
        if(dao::isError()) return false;

        $this->loadModel('action');
        foreach($cases as $caseID) $this->action->create('case', $caseID, 'assigned', '', $account);

        return !dao::isError();
    }

    /**
     * 给排序字段添加前缀。
     * Add a prefix to the sort field.
     *
     * @param  string $orderBy
     * @access private
     * @return string
     */
    private function addPrefixToOrderBy(string $orderBy): string
    {
        $specialFields = ',assignedTo,status,lastRunResult,lastRunner,lastRunDate,';
        $fieldToSort   = strpos($orderBy, '_') ? substr($orderBy, 0, strpos($orderBy, '_')) : $orderBy;
        $orderBy       = strpos($specialFields, ',' . $fieldToSort . ',') !== false ? ('t1.' . $orderBy) : ('t2.' . $orderBy);
        return $orderBy;
    }

    /**
     * 获取一个测试单关联的测试用例及相关需求。
     * Get cases associated with a testtask.
     *
     * @param  int    $taskID
     * @param  array  $modules
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getRuns(int $taskID, array $modules, string $orderBy, object $pager = null): array
    {
        $orderBy = $this->addPrefixToOrderBy($orderBy);

        return $this->dao->select('t2.*, t1.*, t3.title AS storyTitle, t2.status AS caseStatus')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t2.story = t3.id')
            ->where('t1.task')->eq($taskID)
            ->andWhere('t2.deleted')->eq('0')
            ->beginIF($modules)->andWhere('t2.module')->in($modules)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 根据一个测试套件获取一个测试单关联的测试用例。
     * Get cases associated with a testtask according by a test suite.
     *
     * @param  int    $taskID
     * @param  int    $suiteID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getRunsBySuite(int $taskID, int $suiteID, string $orderBy, object $pager = null): array
    {
        $orderBy = $this->addPrefixToOrderBy($orderBy);
        $cases   = $this->loadModel('testsuite')->getLinkedCasePairs($suiteID);

        return $this->dao->select('t2.*,t1.*,t3.title as storyTitle,t2.status as caseStatus')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t2.story = t3.id')
            ->where('t1.task')->eq($taskID)
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t2.id')->in(array_keys($cases))
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 按照测试单分组展示用例。
     * Group case run by suite.
     *
     * @param  int    $taskID
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getRunsForUnitCases(int $taskID, string $orderBy = 't1.id_desc'): array
    {
        return $this->dao->select('t2.*, t1.*, t2.version AS caseVersion, t3.title AS storyTitle, t2.status AS caseStatus, t4.suite, t5.name AS suiteTitle')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t2.story = t3.id')
            ->leftJoin(TABLE_SUITECASE)->alias('t4')->on('t1.case = t4.case')
            ->leftJoin(TABLE_TESTSUITE)->alias('t5')->on('t4.suite = t5.id')
            ->where('t1.task')->eq($taskID)
            ->andWhere('t2.deleted')->eq('0')
            ->orderBy($orderBy)
            ->fetchAll();
    }

    /**
     * 获取一个测试单中指派给一个用户的测试用例。
     * Get the cases assigned to a user among the cases associated with a testtask.
     *
     * @param  int    $taskID
     * @param  string $user
     * @param  array  $modules
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getUserRuns(int $taskID, string $user, array $modules = array(), string $orderBy = 'id_desc', object $pager = null): array
    {
        $orderBy = $this->addPrefixToOrderBy($orderBy);

        return $this->dao->select('t2.*, t1.*, t3.title AS storyTitle, t2.status AS caseStatus')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t2.story = t3.id')
            ->where('t1.task')->eq($taskID)
            ->andWhere('t1.assignedTo')->eq($user)
            ->andWhere('t2.deleted')->eq('0')
            ->beginIF($modules)->andWhere('t2.module')->in($modules)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 获取一个测试单关联的用例。
     * Get cases associated with a testtask.
     *
     * @param  int    $productID
     * @param  string $browseType
     * @param  int    $queryID
     * @param  int    $moduleID
     * @param  string $sort
     * @param  object $pager
     * @param  object $task
     * @access public
     * @return array
     */
    public function getTaskCases(int $productID, string $browseType, int $queryID, int $moduleID, string $sort, object $pager, object $task): array
    {
        $modules = $moduleID ? $this->loadModel('tree')->getAllChildId($moduleID) : array();
        $browseType = ($browseType == 'bymodule' && $this->session->taskCaseBrowseType && $this->session->taskCaseBrowseType != 'bysearch') ? $this->session->taskCaseBrowseType : $browseType;
        $browseType = strtolower($browseType);

        if($browseType == 'bymodule' || $browseType == 'all') return $this->getRuns($task->id, $modules, $sort, $pager);

        if($browseType == 'bysuite') return $this->getRunsBySuite($task->id, $queryID, $sort, $pager);

        if($browseType == 'assignedtome') return $this->getUserRuns($task->id, $this->session->user->account, $modules, $sort, $pager);

        if($browseType == 'bysearch')
        {
            if($this->session->testtaskQuery == false) $this->session->set('testtaskQuery', ' 1 = 1');
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('testtaskQuery', $query->sql);
                    $this->session->set('testtaskForm', $query->form);
                }
            }

            /* 预处理搜索表单生成的查询 SQL。*/
            /* Preprocess the query SQL generated by the search form. */
            $allProduct     = "`product` = 'all'";
            $caseQuery      = $this->session->testtaskQuery;
            $isQueryAllProduct = strpos($caseQuery, $allProduct);
            if($isQueryAllProduct !== false) $caseQuery = str_replace($allProduct, '1', $caseQuery) . ' AND `product` ' . helper::dbIN($this->app->user->view->products);
            $caseQuery = preg_replace('/`(\w+)`/', 't2.`$1`', $caseQuery);
            $caseQuery = str_replace(array('t2.`assignedTo`', 't2.`lastRunner`', 't2.`lastRunDate`', 't2.`lastRunResult`'), array('t1.`assignedTo`', 't1.`lastRunner`', 't1.`lastRunDate`', 't1.`lastRunResult`'), $caseQuery);

            $orderBy   = $this->addPrefixToOrderBy($sort);
            return $this->dao->select('t2.*, t1.*, t3.title AS storyTitle, t2.status AS caseStatus')->from(TABLE_TESTRUN)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
                ->leftJoin(TABLE_STORY)->alias('t3')->on('t2.story = t3.id')
                ->where($caseQuery)
                ->andWhere('t1.task')->eq($task->id)
                ->andWhere('t2.deleted')->eq('0')
                ->beginIF($isQueryAllProduct === false)->andWhere('t2.product')->eq($productID)->fi()
                ->beginIF($task->branch)->andWhere('t2.branch')->in("0,{$task->branch}")->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }

        return array();
    }

    /**
     * 获取某人负责的测试单的键值对。
     * Get the key-value pair of the testtask someone is responsible for.
     *
     * @param  string $account
     * @param  int    $limit
     * @param  string $status all|wait|doing|done|blocked
     * @param  array  $skipProductIDList
     * @param  array  $skipExecutionIDList
     * @access public
     * @return array
     */
    public function getUserTestTaskPairs(string $account, int $limit = 0, string $status = 'all', array $skipProductIDList = array(), array $skipExecutionIDList = array()): array
    {
        $stmt = $this->dao->select('t1.id, t1.name, t2.name AS execution')
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product=t3.id')
            ->where('t1.owner')->eq($account)
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t3.deleted')->eq('0')
            ->beginIF($status != 'all')->andWhere('t1.status')->in($status)->fi()
            ->beginIF(!empty($skipProductIDList))->andWhere('t1.product')->notin($skipProductIDList)->fi()
            ->beginIF(!empty($skipExecutionIDList))->andWhere('t1.execution')->notin($skipExecutionIDList)->fi()
            ->beginIF($limit)->limit($limit)->fi()
            ->query();

        $testtaskPairs = array();
        while($testtask = $stmt->fetch())
        {
            if($testtask->execution) $testtask->execution .= " / ";
            $testtaskPairs[$testtask->id] = $testtask->execution . $testtask->name;
        }
        return $testtaskPairs;
    }

    /**
     * 获取执行结果信息。
     * Get information of a testrun.
     *
     * @param  int    $runID
     * @access public
     * @return object|false
     */
    public function getRunById(int $runID): object|false
    {
        $run = $this->dao->findById($runID)->from(TABLE_TESTRUN)->fetch();
        if(!$run) return false;

        $run->case = $this->loadModel('testcase')->getById($run->case, $run->version);
        return $run;
    }

    /**
     * 根据用例 ID 分组获取关联的测试单。
     * Get testtasks associated with cases by case ID.
     *
     * @param  int|array $caseIDList
     * @access public
     * @return array
     */
    public function getGroupByCases($caseIDList)
    {
        return $this->dao->select('t1.case, t2.*, t3.branch')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_TESTTASK)->alias('t2')->on('t1.task=t2.id')
            ->leftJoin(TABLE_BUILD)->alias('t3')->on('t2.build = t3.id')
            ->where('t1.case')->in($caseIDList)
            ->fetchGroup('case', 'id');
    }

    /**
     * 初始化一条记录便于自动化测试完成后回填测试结果。
     * Init a record for filling back the test result after the automated test is completed.
     *
     * @param  int    $runID
     * @param  int    $caseID
     * @param  int    $version
     * @param  int    $nodeID
     * @access public
     * @return int|false
     */
    public function initResultForAutomatedTest(int $runID = 0, int $caseID = 0, int $version = 0, int $nodeID = 0): int|false
    {
        $result = new stdClass();
        $result->run        = $runID;
        $result->case       = $caseID;
        $result->version    = $version;
        $result->node       = $nodeID;
        $result->date       = helper::now();
        $result->lastRunner = $this->app->user->account;

        $this->dao->insert(TABLE_TESTRESULT)->data($result)->autoCheck()->exec();
        if(dao::isError()) return false;

        return $this->dao->lastInsertID();
    }

    /**
     * 保存一个测试用例的执行结果。
     * Save the execution results of a test case.
     *
     * @param  int    $runID
     * @param  int    $caseID
     * @param  int    $version
     * @param  array  $stepResults
     * @access public
     * @return bool|string
     */
    public function createResult(int $runID, int $caseID, int $version, array $stepResults): bool|string
    {
        /* 根据测试用例步骤的执行结果获取测试用例的执行结果。*/
        /* Get the execution results of the test case based on the execution results of the test case steps. */
        $caseResult = 'pass';
        foreach($stepResults as $stepResult)
        {
            if($stepResult->result == 'fail' || $stepResult->result == 'blocked') $caseResult = $stepResult->result;
            if($stepResult->result == 'fail') break;
        }

        /* 把用例步骤执行结果转换为数组类型以便于和历史数据保持一致。*/
        /* Convert the execution results of the test case steps to array type for consistency with historical data. */
        foreach($stepResults as $key => $stepResult) $stepResults[$key] = (array)$stepResult;

        /* 保存测试用例的执行结果。*/
        /* Save the execution results of the test case. */
        $now    = helper::now();
        $result = new stdclass();
        $result->run         = $runID;
        $result->case        = $caseID;
        $result->version     = $version;
        $result->caseResult  = $caseResult;
        $result->stepResults = serialize($stepResults);
        $result->lastRunner  = $this->app->user->account;
        $result->date        = $now;
        $this->dao->insert(TABLE_TESTRESULT)->data($result)->autoCheck()->exec();
        if(dao::isError()) return false;

        /* 把上传的文件关联到到执行结果的用例步骤中。*/
        /* Associated the uploaded files to the test case steps of the execution results. */
        $resultID = $this->dao->lastInsertID();
        foreach($stepResults as $stepID => $stepResult) $this->loadModel('file')->saveUpload('stepResult', $resultID, $stepID, "files{$stepID}", "labels{$stepID}");

        /* 更新测试用例的执行结果。*/
        /* Update the execution results of the test case. */
        $case = new stdclass();
        $case->lastRunner    = $this->app->user->account;
        $case->lastRunDate   = $now;
        $case->lastRunResult = $caseResult;
        $this->dao->update(TABLE_CASE)->data($case)->where('id')->eq($caseID)->exec();
        if(dao::isError()) return false;

        if($runID)
        {
            /* 更新测试单中测试用例的执行结果。*/
            /* Update the execution results of the test case in testtask. */
            $run = new stdclass();
            $run->status        = $caseResult == 'blocked' ? 'blocked' : 'normal';
            $run->lastRunResult = $caseResult;
            $run->lastRunner    = $this->app->user->account;
            $run->lastRunDate   = $now;
            $this->dao->update(TABLE_TESTRUN)->data($run)->where('id')->eq($runID)->exec();
        }

        $this->loadModel('score')->create('testtask', 'runCase', $runID);
        return $caseResult;
    }

    /**
     * 批量执行测试用例并记录测试结果。
     * Batch run test cases and record the test results.
     *
     * @param  array  $cases
     * @param  string $runCaseType
     * @param  int    $taskID
     * @access public
     * @return bool
     */
    public function batchRun(array $cases, string $runCaseType = 'testcase', int $taskID = 0): bool
    {
        $caseIdList = array_filter(array_keys($cases));
        if(empty($caseIdList)) return false;

        $runs = array();
        if($runCaseType == 'testtask')
        {
            /* 如果是从测试单中批量执行测试用例，查询出测试用例和测试执行的键值对便于更新本次执行结果。*/
            /* If batch run test cases from testtask, query the key-value pair of test cases and test execution for updating the execution results. */
            $runs = $this->dao->select('`case`, id')->from(TABLE_TESTRUN)
                ->where('`case`')->in($caseIdList)
                ->beginIF($taskID)->andWhere('task')->eq($taskID)->fi()
                ->fetchPairs();
        }

        $now    = helper::now();
        $result = new stdClass();
        $result->lastRunner = $this->app->user->account;
        $result->date       = $now;

        $case = new stdClass();
        $case->lastRunner  = $this->app->user->account;
        $case->lastRunDate = $now;

        $run = new stdclass();
        $run->lastRunner  = $this->app->user->account;
        $run->lastRunDate = $now;

        $this->loadModel('action');
        foreach($cases as $caseID => $postCase)
        {
            $runID       = zget($runs, $caseID, 0);
            $postSteps   = zget($postCase, 'steps', array());
            $postReals   = zget($postCase, 'reals', array());
            $caseResult  = $postCase->results ? $postCase->results : 'pass';
            $stepResults = $this->processStepResults($caseIdList, $caseID, $caseResult, $postSteps, $postReals);

            $result->run         = $runID;
            $result->case        = $caseID;
            $result->version     = $postCase->version;
            $result->caseResult  = $caseResult;
            $result->stepResults = serialize($stepResults);
            $this->dao->insert(TABLE_TESTRESULT)->data($result)->autoCheck()->exec();

            $case->lastRunResult = $caseResult;
            $this->dao->update(TABLE_CASE)->data($case)->where('id')->eq($caseID)->exec();

            $this->action->create('case', $caseID, 'run', '', $taskID);

            if(!$runID) continue;

            $run->lastRunResult = $caseResult;
            $run->status        = $caseResult == 'blocked' ? 'blocked' : 'normal';
            $this->dao->update(TABLE_TESTRUN)->data($run)->where('id')->eq($runID)->exec();

            if(dao::isError()) return false;
        }

        return true;
    }

    /**
     * 处理测试用例步骤的执行结果。
     * Process the execution results of the test case steps.
     *
     * @param  array   $caseIdList
     * @param  int     $caseID
     * @param  string  $caseResult
     * @param  array   $postSteps
     * @param  array   $postReals
     * @access private
     * @return array
     */
    private function processStepResults(array $caseIdList, int $caseID, string $caseResult, array $postSteps, array $postReals): array
    {
        static $stepGroups = array();
        if(empty($stepGroups))
        {
            $stepGroups = $this->dao->select('t1.id, t1.case')->from(TABLE_CASESTEP)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id AND t1.version = t2.version')
                ->where('t1.case')->in($caseIdList)
                ->andWhere('t1.type')->ne('group')
                ->andWhere('t2.status')->ne('wait')
                ->fetchGroup('case', 'id');
        }

        if(empty($stepGroups[$caseID]))
        {
            $results[0]['result'] = $caseResult;
            $results[0]['real']   = $caseResult == 'pass' ? '' : $postReals[0];
            return $results;
        }

        $results = array();
        $dbSteps = array_keys($stepGroups[$caseID]);
        foreach($dbSteps as $stepID)
        {
            $results[$stepID]['result'] = $caseResult == 'pass' ? $caseResult : $postSteps[$stepID];
            $results[$stepID]['real']   = $caseResult == 'pass' ? '' : $postReals[$stepID];
        }
        return $results;
    }

    /**
     * 通过执行 ID 或用例 ID 获取用例的执行结果。
     * Get results by runID or caseID
     *
     * @param  int    $runID
     * @param  int    $caseID
     * @param  string $status all|done
     * @param  string $type   all|fail
     * @access public
     * @return void
     */
    public function getResults(int $runID, int $caseID = 0, string $status = 'all', string $type = 'all'): array
    {
        $results = $this->dao->select('*')->from(TABLE_TESTRESULT)
            ->beginIF($runID > 0)->where('run')->eq($runID)->fi()
            ->beginIF($runID <= 0)->where('`case`')->eq($caseID)->fi()
            ->beginIF($status == 'done')->andWhere('caseResult')->ne('')->fi()
            ->beginIF($type != 'all')->andWhere('caseResult')->eq($type)->fi()
            ->orderBy('id desc')
            ->fetchAll('id');
        if(!$results) return array();

        list($resultFiles, $stepFiles) = $this->getResultsFiles(array_keys($results));

        $runIdList = $nodeIdList = $relatedVersions = array();
        foreach($results as $result)
        {
            $runIdList[$result->run]           = $result->run;
            $nodeIdList[$result->node]         = $result->node;
            $relatedVersions[$result->version] = $result->version;
            $runCaseID                         = $result->case;
        }
        $relatedSteps = $this->dao->select('*')->from(TABLE_CASESTEP)
            ->where('`case`')->eq($runCaseID)
            ->andWhere('version')->in($relatedVersions)
            ->orderBy('id')
            ->fetchGroup('version', 'id');
        $runs = $this->dao->select('t1.id, t1.task, t2.build')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_TESTTASK)->alias('t2')->on('t1.task=t2.id')
            ->where('t1.id')->in($runIdList)
            ->fetchAll('id');
        $nodes = $this->dao->select('id,name')->from(TABLE_ZAHOST)->where('id')->in($nodeIdList)->fetchPairs();

        foreach($results as $resultID => $result)
        {
            $result->stepResults = unserialize($result->stepResults);
            $result->build       = !empty($runs[$result->run]->build) ? $runs[$result->run]->build : 0;
            $result->task        = !empty($runs[$result->run]->task) ? $runs[$result->run]->task : 0;
            $result->nodeName    = zget($nodes, $result->node, '');
            $result->files       = zget($resultFiles, $resultID, array()); //Get files of case result.
            if(isset($relatedSteps[$result->version])) $result->stepResults = $this->processResultSteps($result, $relatedSteps[$result->version]);
            if(!empty($result->ZTFResult)) $result->ZTFResult = $this->formatZtfLog($result->ZTFResult, $result->stepResults);

            /* Get files of step result. */
            if(!empty($result->stepResults))
            {
                foreach(array_keys($result->stepResults) as $stepID) $result->stepResults[$stepID]['files'] = isset($stepFiles[$resultID][$stepID]) ? $stepFiles[$resultID][$stepID] : array();
            }
        }
        return $results;
    }

    /**
     * 计算用例执行结果的步骤。
     * Process steps of result.
     *
     * @param  object $result
     * @param  array  $relatedStep
     * @access public
     * @return array
     */
    public function processResultSteps(object $result, array $relatedStep): array
    {
        $this->loadModel('testcase');

        $preGrade    = 1;
        $parentSteps = array();
        $key         = array(0, 0, 0);
        foreach($relatedStep as $stepID => $step)
        {
            $parentSteps[$step->id] = $step->parent;
            $grade = 1;
            if(isset($parentSteps[$step->parent]))
            {
                $grade = isset($parentSteps[$parentSteps[$step->parent]]) ? 3 : 2;
            }

            if($grade > $preGrade)
            {
                $key[$grade - 1] = 1;
            }
            else
            {
                if($grade < $preGrade)
                {
                    if($grade < 2) $key[1] = 0;
                    if($grade < 3) $key[2] = 0;
                }
                $key[$grade - 1] ++;
            }
            $name = implode('.', $key);
            $name = str_replace('.0', '', $name);

            $relatedStep[$stepID] = (array)$step;
            $relatedStep[$stepID]['name']   = $name;
            $relatedStep[$stepID]['grade']  = $grade;
            $relatedStep[$stepID]['desc']   = html_entity_decode($relatedStep[$stepID]['desc']);
            $relatedStep[$stepID]['expect'] = html_entity_decode($relatedStep[$stepID]['expect']);
            if(isset($result->stepResults[$stepID]))
            {
                $relatedStep[$stepID]['result'] = $result->stepResults[$stepID]['result'];
                $relatedStep[$stepID]['real']   = $result->stepResults[$stepID]['real'];
            }

            $preGrade = $grade;
        }
        return $relatedStep;
    }

    /**
     * 获取结果的文件。
     * Get files of the results.
     *
     * @param  array  $resultIdList
     * @access public
     * @return array
     */
    public function getResultsFiles(array $resultIdList): array
    {
        $resultFiles = array();
        $stepFiles   = array();
        $files       = $this->dao->select('*')->from(TABLE_FILE)
            ->where('objectType')->in('caseResult, stepResult')
            ->andWhere('objectID')->in($resultIdList)
            ->andWhere('extra')->ne('editor')
            ->orderBy('id')
            ->fetchAll();

        $this->loadModel('file');
        foreach($files as $file)
        {
            $this->file->setFileWebAndRealPaths($file);
            if($file->objectType == 'caseResult')
            {
                $resultFiles[$file->objectID][$file->id] = $file;
            }
            elseif($file->objectType == 'stepResult' && $file->extra !== '')
            {
                $stepFiles[$file->objectID][(int)$file->extra][$file->id] = $file;
            }
        }

        return array($resultFiles, $stepFiles);
    }

    /**
     * 格式化 ztf 的执行日志。
     * Format the execution log of ztf.
     *
     * @param  string $result
     * @param  array  $stepResults
     * @access public
     * @return string
     */
    public function formatZtfLog(string $result, array $stepResults): string
    {
        $logObj  = json_decode($result);
        $logs    = empty($logObj->log) ? '' : $logObj->log;
        if(empty($logs)) return '';

        $logs     = str_replace(array("\r", "\n", "\r\n"), "\n", $logs);
        $logList  = explode("\n", $logs);
        $logHtml  = '';

        foreach($logList as $log)
        {
            $log = preg_replace("/^[\d\-:.\x20]+/", '', $log);
            $log = trim($log);
            if(empty($log)) continue;

            $failHtml = ": <span class='result-testcase fail'>{$this->lang->testtask->fail}</span>";
            $passHtml = ": <span class='result-testcase pass'>{$this->lang->testtask->pass}</span>";

            $log = preg_replace(array("/:\x20失败/", "/:\x20fail/", "/:\x20成功/", "/:\x20pass/"), array($failHtml, $failHtml, $passHtml, $passHtml), $log);

            $logHtml .= "<li>{$log}</li>";
        }

        if(!empty($stepResults))
        {
            $total     = count($stepResults);
            $failCount = $passCount = 0;

            foreach($stepResults as $step)
            {
                if($step['result'] == 'pass')
                {
                    $passCount++;
                }
                elseif($step['result'] == 'fail')
                {
                    $failCount++;
                }
            }

            $caseResult = $passCount ? 'pass':'fail';
            $logHtml   .= "<li class='result-testcase {$caseResult}'>" . sprintf($this->lang->testtask->stepSummary, $total, $passCount, $failCount) . '</li>';
        }

        return $logHtml;
    }

    /**
     * 判断一个动作是否可以执行。
     * Determine whether an action can be performed.
     *
     * @param  object $testtask
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $testtask, string $action): bool
    {
        $action = strtolower($action);

        if($action == 'start')    return $testtask->status  == 'wait';
        if($action == 'block')    return ($testtask->status == 'doing'   || $testtask->status == 'wait');
        if($action == 'activate') return ($testtask->status == 'blocked' || $testtask->status == 'done');
        if($action == 'close')    return $testtask->status != 'done';

        if($action == 'runcase')
        {
            if(isset($testtask->auto) && $testtask->auto == 'unit')  return false;
            if(isset($testtask->caseStatus)) return $testtask->caseStatus != 'wait';
            return $testtask->status != 'wait';
        }

        return true;
    }

    /**
     * 获取一个测试单的收件人和抄送人。
     * Get the recipient and cc of a testtask.
     *
     * @param  object    $testtask
     * @access public
     * @return bool|array
     */
    public function getToAndCcList(object $testtask): false|array
    {
        /* Set toList and ccList. */
        $toList   = zget($testtask, 'owner', '') . ',' . zget($testtask, 'members', '') . ',';
        $ccList   = str_replace(' ', '', trim(zget($testtask, 'mailto', ''), ','));

        if(empty($toList))
        {
            if(empty($ccList)) return false;

            if(strpos($ccList, ',') === false)
            {
                $toList = $ccList;
                $ccList = '';
            }
            else
            {
                $commaPos = strpos($ccList, ',');
                $toList   = substr($ccList, 0, $commaPos);
                $ccList   = substr($ccList, $commaPos + 1);
            }
        }
        return array($toList, $ccList);
    }

    /**
     * 导入单元测试结果。
     * Import unit test results.
     *
     * @param  object $task
     * @access public
     * @return bool|int
     */
    public function importUnitResult(object $task): bool|int
    {
        /* 获取上传的单元测试文件。*/
        /* Get uploaded unit test file. */
        $files = $this->loadModel('file')->getUpload('resultFile');
        if(empty($files[0]))
        {
            dao::$errors['resultFile'][] = $this->lang->testtask->unitXMLFormat;
            return false;
        }

        /* 使用 simplexml 读取文件。*/
        /* Use simplexml to read file. */
        $file     = $files[0];
        $filePath = $this->file->savePath . $this->file->getSaveName($file['pathname']);
        move_uploaded_file($file['tmpname'], $filePath);
        $parsedXML = simplexml_load_file($filePath);
        if($parsedXML === false)
        {
            dao::$errors['resultFile'][] = $this->lang->testtask->cannotBeParsed;
            return false;
        }

        /* 解析 xml 文件。*/
        /* Parse xml file. */
        $data = $this->parseXMLResult($parsedXML, $task->product, $task->frame);
        if(empty($data['cases']) && $task->frame == 'cppunit') $data = $this->parseCppXMLResult($parsedXML, $task->product, $task->frame);
        if(empty($data['cases']))
        {
            dao::$errors['resultFile'][] = $this->lang->testtask->noImportData;
            return false;
        }

        /* 创建测试单。*/
        /* Create test task. */
        unset($task->frame);
        $taskID = $this->create($task);
        if(dao::isError()) return false;

        $this->loadModel('action')->create('testtask', $taskID, 'opened');

        unlink($filePath);
        unset($_SESSION['resultFile']);

        /* 导入单元测试结果文件中包含的数据。*/
        /* Import data in the unit test results. */
        $this->importDataOfUnitResult($taskID, $task->product, $data['suites'], $data['cases'], $data['results'], $data['suiteNames'], $data['caseTitles'], 'unit');

        return $taskID;
    }

    /**
     * 导入单元测试结果文件中包含的数据。
     * Import data in the unit test results.
     *
     * @param  int    $taskID
     * @param  int    $productID
     * @param  array  $suites
     * @param  array  $cases
     * @param  array  $results
     * @param  array  $suiteNames
     * @param  array  $caseTitles
     * @param  string $auto      unit|func
     * @access public
     * @return bool
     */
    public function importDataOfUnitResult(int $taskID, int $productID, array $suites, array $cases, array $results, array $suiteNames, array $caseTitles, string $auto = 'unit'): bool
    {
        $this->loadModel('action');

        /* 初始化对象便于在循环中使用。*/
        /* Initialize the object for use in loops. */
        $suiteCase = new stdclass();
        $testRun   = new stdclass();
        $testRun->task   = $taskID;
        $testRun->status = 'done';

        $existSuites = $suiteNames ? $this->getExistSuitesOfUnitResult($suiteNames, $productID, $auto) : array();

        foreach($suites as $suiteIndex => $suite)
        {
            $suiteID = 0;
            if($suite) $suiteID = isset($existSuites[$suite->name]) ? $existSuites[$suite->name] : $this->importSuiteOfUnitResult($suite);

            $suiteCase->suite = $suiteID;

            $importCases = zget($cases, $suiteIndex, array());
            $existCases  = !empty($caseTitles[$suiteIndex]) ? $this->getExistCasesOfUnitResult($caseTitles[$suiteIndex], $suiteID, $productID, $auto) : array();
            foreach($importCases as $key => $case)
            {
                $caseID = $this->importCaseOfUnitResult($case, $existCases);
                $runID  = $this->importRunOfUnitResult($case, $caseID, $testRun);
                if($suiteID) $this->linkImportedCaseToSuite($case, $caseID, $suiteCase);

                $testresult = $results[$suiteIndex][$key];
                $testresult->run  = $runID;
                $testresult->case = $caseID;
                $this->dao->insert(TABLE_TESTRESULT)->data($testresult)->exec();
            }
        }

        return !dao::isError();
    }

    /**
     * 导入单元测试结果文件中的测试套件。
     * Import test suite in the unit test results.
     *
     * @param  object  $suite
     * @access private
     * @return int
     */
    private function importSuiteOfUnitResult(object $suite): int
    {
        $this->dao->insert(TABLE_TESTSUITE)->data($suite)->exec();
        $suiteID = $this->dao->lastInsertID();
        $this->action->create('testsuite', $suiteID, 'opened');

        return $suiteID;
    }

    /**
     * 导入单元测试结果文件中的测试用例。
     * Import test case in the unit test results.
     *
     * @param  object  $case
     * @param  array   $existCases
     * @access private
     * @return int
     */
    private function importCaseOfUnitResult(object &$case, array $existCases): int
    {
        if(!empty($case->id))
        {
            unset($case->steps);

            $caseID  = $case->id;
            $oldCase = $this->dao->select('*')->from(TABLE_CASE)->where('id')->eq($caseID)->fetch();
            $case->version    = $oldCase->version;
            $case->openedDate = $oldCase->openedDate;

            $changes = common::createChanges($oldCase, $case);
            if($changes)
            {
                $this->dao->update(TABLE_CASE)->data($case)->where('id')->eq($caseID)->exec();
                $actionID = $this->action->create('case', $caseID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            return $caseID;
        }

        if(!isset($existCases[$case->title]))
        {
            $this->dao->insert(TABLE_CASE)->data($case, 'steps')->exec();
            $caseID = $this->dao->lastInsertID();
            $steps  = zget($case, 'steps', array());
            foreach($steps as $step)
            {
                $step->case    = $caseID;
                $step->version = 1;
                $this->dao->insert(TABLE_CASESTEP)->data($step)->exec();
            }
            $this->action->create('case', $caseID, 'Opened');

            return $caseID;
        }

        return $existCases[$case->title];
    }

    /**
     * 导入单元测试结果文件中的测试结果。
     * Import test result in the unit test results.
     *
     * @param  object  $case
     * @param  int     $caseID
     * @param  object  $testRun
     * @access private
     * @return int
     */
    private function importRunOfUnitResult(object $case, int $caseID, object $testRun): int
    {
        $testRun->case          = $caseID;
        $testRun->version       = $case->version;
        $testRun->lastRunner    = $case->lastRunner;
        $testRun->lastRunDate   = $case->lastRunDate;
        $testRun->lastRunResult = $case->lastRunResult;

        $this->dao->replace(TABLE_TESTRUN)->data($testRun)->exec();

        return $this->dao->lastInsertID();
    }

    /**
     * 将导入的测试用例关联到测试套件。
     * Link imported case to suite.
     *
     * @param  object  $case
     * @param  int     $caseID
     * @param  object  $suiteCase
     * @access private
     * @return bool
     */
    private function linkImportedCaseToSuite(object $case, int $caseID, object $suiteCase): bool
    {
        $suiteCase->case    = $caseID;
        $suiteCase->version = $case->version;
        $suiteCase->product = $case->product;

        $this->dao->replace(TABLE_SUITECASE)->data($suiteCase)->exec();

        return !dao::isError();
    }

    /**
     * 获取单元测试结果文件中已存在的测试套件。
     * Get exist suites in the unit test results.
     *
     * @param  array  $names
     * @param  int    $productID
     * @param  string $auto
     * @access public
     * @return array
     */
    private function getExistSuitesOfUnitResult(array $names, int $productID, string $auto): array
    {
        if(!$names) return array();

        return $this->dao->select('name, id')->from(TABLE_TESTSUITE)
            ->where('name')->in($names)
            ->andWhere('product')->eq($productID)
            ->andWhere('type')->eq($auto)
            ->andWhere('deleted')->eq('0')
            ->fetchPairs();
    }

    /**
     * 获取单元测试结果文件中已存在的测试用例。
     * Get exist cases in the unit test results.
     *
     * @param  array  $titles
     * @param  int    $suiteID
     * @param  int    $productID
     * @param  string $auto
     * @access public
     * @return array
     */
    private function getExistCasesOfUnitResult(array $titles, int $suiteID, int $productID, string $auto): array
    {
        if(!$titles) return array();

        $this->dao->select('t1.title, t1.id')->from(TABLE_CASE)->alias('t1');

        if($suiteID) $this->dao->leftJoin(TABLE_SUITECASE)->alias('t2')->on('t1.id=t2.case');

        return $this->dao->where('t1.title')->in($titles)
            ->andWhere('t1.product')->eq($productID)
            ->beginIF($auto == 'unit')->andWhere('t1.auto')->eq($auto)->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy('t1.id')
            ->fetchPairs();
    }

    /**
     * 解析 xml 文件中的 cppunit 的单元测试结果。
     * Parse unit test result from cppunit xml.
     *
     * @param  object $parsedXML
     * @param  int    $productID
     * @param  string $frame
     * @access public
     * @return array
     */
    public function parseCppXMLResult(object $parsedXML, int $productID, string $frame): array
    {
        /* Get testcase node. */
        $failNodes  = $parsedXML->xpath('FailedTests/FailedTest');
        $passNodes  = $parsedXML->xpath('SuccessfulTests/Test');
        $matchNodes = array_merge($failNodes, $passNodes);
        if(count($matchNodes) == 0) return array('suites' => array(), 'cases' => array(), 'results' => array(), 'suiteNames' => array(), 'caseTitles' => array());

        /* Get cases and results by parsed node. */
        $now        = helper::now();
        $cases      = array();
        $results    = array();
        $caseTitles = array();
        $suiteIndex = 0;
        $suites     = array($suiteIndex => '');
        foreach($matchNodes as $caseIndex => $matchNode)
        {
            $result = $this->initResult($now);
            $result->duration = 0;
            $result->xml      = $matchNode->asXML();
            if(isset($matchNode->Message))
            {
                $result->caseResult               = 'fail';
                $result->stepResults[0]['result'] = 'fail';
                $result->stepResults[0]['real']   = (string)$matchNode->Message;
            }
            $result->stepResults = serialize($result->stepResults);

            $case = $this->initCase($productID, (string)$matchNode->name, $now, 'unit', $frame ?: 'junit');
            $case->lastRunResult = $result->caseResult;

            $caseTitles[$suiteIndex][]        = $case->title;
            $cases[$suiteIndex][$caseIndex]   = $case;
            $results[$suiteIndex][$caseIndex] = $result;
        }

        return array('suites' => $suites, 'cases' => $cases, 'results' => $results, 'suiteNames' => array(), 'caseTitles' => $caseTitles);
    }

    /**
     * 解析 xml 文件中的单元测试结果。
     * Parse unit test result from xml.
     *
     * @param  object $parsedXML
     * @param  int    $productID
     * @param  string $frame
     * @access public
     * @return array
     */
    public function parseXMLResult(object $parsedXML, int $productID, string $frame): array
    {
        /* Parse result xml. */
        $rules = zget($this->config->testtask->unitResultRules, $frame, $this->config->testtask->unitResultRules->common);

        /* Get testcase node. */
        $matchPaths = $rules['path'];
        $nameFields = $rules['name'];
        $failure    = $rules['failure'];
        $skipped    = $rules['skipped'];
        $suiteField = $rules['suite'];
        $aliasSuite = zget($rules, 'aliasSuite', array());
        $aliasName  = zget($rules, 'aliasName', array());
        $matchNodes = array();
        foreach($matchPaths as $matchPath)
        {
            $matchNodes = $parsedXML->xpath($matchPath);
            if(count($matchNodes) != 0) break;
        }
        if(count($matchNodes) == 0) return array('suites' => array(), 'cases' => array(), 'results' => array(), 'suiteNames' => array(), 'caseTitles' => array());

        $parentPath  = '';
        $caseNode    = $matchPath;
        $parentNodes = array($parsedXML);
        if(strpos($matchPath, '/') !== false)
        {
            $explodedPath = explode('/', $matchPath);
            $caseNode     = array_pop($explodedPath);
            $parentPath   = implode('/', $explodedPath);
            $parentNodes  = $parsedXML->xpath($parentPath);
        }

        /* Get cases and results by parsed node. */
        $now        = helper::now();
        $cases      = array();
        $results    = array();
        $suites     = array();
        $caseTitles = array();
        $suiteNames = array();
        foreach($parentNodes as $suiteIndex => $parentNode)
        {
            $caseNodes  = $parentNode->xpath($caseNode);
            $attributes = $parentNode->attributes();
            $suite      = '';
            if(isset($attributes[$suiteField]))
            {
                $suite = $this->initSuite($productID, (string)$attributes[$suiteField], $now);
                $suiteNames[] = $suite->name;
            }
            else
            {
                $attributes = $caseNodes[0]->attributes();
                foreach($aliasSuite as $alias)
                {
                    if(isset($attributes[$alias]))
                    {
                        $suite = $this->initSuite($productID, (string)$attributes[$alias], $now);
                        $suiteNames[] = $suite->name;
                        break;
                    }
                }
            }
            $suites[$suiteIndex] = $suite;

            foreach($caseNodes as $caseIndex => $matchNode)
            {
                $case = $this->initCase($productID, '', $now, 'unit', $frame ?: 'junit');

                $attributes = $matchNode->attributes();
                foreach($nameFields as $field)
                {
                    if(!isset($attributes[$field])) continue;
                    $case->title .= (string)$attributes[$field] . ' ';
                }
                $case->title = trim($case->title);
                if(empty($case->title))
                {
                    foreach($aliasName as $field)
                    {
                        if(!isset($attributes[$field])) continue;
                        $case->title .= (string)$attributes[$field] . ' ';
                    }
                    $case->title = trim($case->title);
                }
                if(empty($case->title)) continue;

                $result = $this->initResult($now);
                $result->duration = isset($attributes['time']) ? (float)$attributes['time'] : 0;
                $result->xml      = $matchNode->asXML();
                $result = $this->processResult($result, $matchNode, $failure, $skipped);
                $result->stepResults = serialize($result->stepResults);
                $case->lastRunResult = $result->caseResult;

                $caseTitles[$suiteIndex][]        = $case->title;
                $cases[$suiteIndex][$caseIndex]   = $case;
                $results[$suiteIndex][$caseIndex] = $result;
            }
        }

        return array('suites' => $suites, 'cases' => $cases, 'results' => $results, 'suiteNames' => $suiteNames, 'caseTitles' => $caseTitles);
    }

    /**
     * 解析 ztf 的单元测试结果。
     * Parse unit test result of ztf.
     *
     * @param  array  $caseResults
     * @param  string $frame
     * @param  int    $productID
     * @param  int    $jobID
     * @param  int    $compileID
     * @access public
     * @return array
     */
    public function parseZTFUnitResult(array $caseResults, string $frame, int $productID, int $jobID, int $compileID): array
    {
        $now        = helper::now();
        $cases      = array();
        $results    = array();
        $suites     = array();
        $caseTitles = array();
        $suiteNames = array();
        $suiteIndex = 0;
        foreach($caseResults as $caseIndex => $caseResult)
        {
            $suite = '';
            if(isset($caseResult->testSuite) and !isset($suiteNames[$caseResult->testSuite]))
            {
                $suite = $this->initSuite($productID, $caseResult->testSuite, $now);

                $suiteNames[$suite->name] = $suite->name;
                $suiteIndex ++;
            }
            if(!isset($suites[$suiteIndex])) $suites[$suiteIndex] = $suite;

            $result = $this->initResult($now);
            $result->job      = $jobID;
            $result->compile  = $compileID;
            $result->duration = zget($caseResult, 'duration', 0);
            if(!empty($caseResult->failure))
            {
                $result->caseResult               = 'fail';
                $result->stepResults[0]['result'] = 'fail';
                $result->stepResults[0]['real']   = zget($caseResult->failure, 'desc', '');
            }
            $result->stepResults = serialize($result->stepResults);

            $case = $this->initCase($productID, '', $now, '', $frame);
            $case->lastRunResult = $result->caseResult;
            if(!empty($caseResult->id)) $case->id    = $caseResult->id;
            if(empty($caseResult->id))  $case->title = $caseResult->title;
            if(empty($caseResult->id))  $case->auto  = 'unit';

            $caseTitles[$suiteIndex][]        = $case->title;
            $cases[$suiteIndex][$caseIndex]   = $case;
            $results[$suiteIndex][$caseIndex] = $result;
        }

        return array('suites' => $suites, 'cases' => $cases, 'results' => $results, 'suiteNames' => $suiteNames, 'caseTitles' => $caseTitles);
    }

    /**
     * 解析 ztf 的功能测试结果。
     * Parse function test result of ztf.
     *
     * @param  array  $caseResults
     * @param  string $frame
     * @param  int    $productID
     * @param  int    $jobID
     * @param  int    $compileID
     * @access public
     * @return array
     */
    public function parseZTFFuncResult(array $caseResults, string $frame, int $productID, int $jobID, int $compileID): array
    {
        $now        = helper::now();
        $cases      = array();
        $results    = array();
        $suites     = array();
        $caseTitles = array();
        $suiteNames = array();
        $suiteIndex = 0;
        foreach($caseResults as $caseIndex => $caseResult)
        {
            $suite = '';
            if(!isset($suites[$suiteIndex])) $suites[$suiteIndex] = $suite;

            $steps  = array();
            $result = $this->initResult($now);
            $result->job     = $jobID;
            $result->compile = $compileID;
            if(!empty($caseResult->steps))
            {
                $result->stepResults = array();
                $stepStatus = 'pass';
                foreach($caseResult->steps as $i => $step)
                {
                    if(!$step->status) $step->status = 'fail';

                    $result->stepResults[$i]['result'] = $step->status;
                    $result->stepResults[$i]['real']   = $step->checkPoints[0]->actual;
                    if($step->status == 'fail') $stepStatus = 'fail';

                    $caseStep = new stdclass();
                    $caseStep->type   = 'step';
                    $caseStep->desc   = $step->name;
                    $caseStep->expect = $step->checkPoints[0]->expect;

                    $steps[] = $caseStep;
                }
                $result->caseResult = $stepStatus;
            }
            $result->stepResults = serialize($result->stepResults);

            $case = $this->initCase(0, $caseResult->title, $now, 'func', $frame, 'feature', 'feature');
            $case->steps         = $steps;
            $case->lastRunResult = $result->caseResult;
            if(isset($caseResult->id))  $case->id      = $caseResult->id;
            if(!isset($caseResult->id)) $case->product = $productID;

            $caseTitles[$suiteIndex][]        = $case->title;
            $cases[$suiteIndex][$caseIndex]   = $case;
            $results[$suiteIndex][$caseIndex] = $result;
        }

        return array('suites' => $suites, 'cases' => $cases, 'results' => $results, 'suiteNames' => $suiteNames, 'caseTitles' => $caseTitles);
    }

    /**
     * 初始化测试套件。
     * Initialize the test suite.
     *
     * @param  int     $product
     * @param  string  $name
     * @param  string  $now
     * @access private
     * @return object
     */
    private function initSuite(int $product, string $name, string $now)
    {
        $suite = new stdclass();
        $suite->product   = $product;
        $suite->name      = $name;
        $suite->type      = 'unit';
        $suite->addedBy   = $this->app->user->account;
        $suite->addedDate = $now;

        return $suite;
    }

    /**
     * 初始化测试用例。
     * Initialize the test case.
     *
     * @param  int     $product
     * @param  string  $title
     * @param  string  $now
     * @param  string  $auto
     * @param  string  $frame
     * @param  string  $type
     * @param  string  $stage
     * @access private
     * @return object
     */
    private function initCase(int $product, string $title, string $now, string $auto, string $frame, string $type = 'unit', string $stage = 'unittest'): object
    {
        $case = new stdclass();
        $case->product     = $product;
        $case->title       = $title;
        $case->pri         = 3;
        $case->type        = $type;
        $case->stage       = $stage;
        $case->status      = 'normal';
        $case->openedBy    = $this->app->user->account;
        $case->openedDate  = $now;
        $case->version     = 1;
        $case->auto        = $auto;
        $case->frame       = $frame;
        $case->lastRunner  = $this->app->user->account;
        $case->lastRunDate = $now;

        return $case;
    }

    /**
     * 初始化测试用例执行结果。
     * Initialize the execution result of the test case.
     *
     * @param  string  $now
     * @access private
     * @return object
     */
    private function initResult(string $now): object
    {
        $result = new stdclass();
        $result->case                     = 0;
        $result->version                  = 1;
        $result->caseResult               = 'pass';
        $result->lastRunner               = $this->app->user->account;
        $result->date                     = $now;
        $result->stepResults[0]['result'] = 'pass';
        $result->stepResults[0]['real']   = '';

        return $result;
    }

    /**
     * 根据导入的 xml 文件内容处理测试用例的执行结果。
     * Process the execution result of the test case according to the imported xml file content.
     *
     * @param  object  $result
     * @param  object  $matchNode
     * @param  string  $failure
     * @param  string  $skipped
     * @access private
     * @return object
     */
    private function processResult(object $result, object $matchNode, string $failure, string $skipped): object
    {
        if(isset($matchNode->$failure))
        {
            $result->caseResult               = 'fail';
            $result->stepResults[0]['result'] = 'fail';
            if(is_string($matchNode->$failure))
            {
                $result->stepResults[0]['real'] = (string)$matchNode->$failure;
                return $result;
            }

            if(isset($matchNode->{$failure}[0]))
            {
                $result->stepResults[0]['real'] = (string)$matchNode->{$failure}[0];
                return $result;
            }

            $failureAttrs = $matchNode->$failure->attributes();
            $result->stepResults[0]['real'] = (string)$failureAttrs['message'];
            return $result;
        }

        if(isset($matchNode->$skipped))
        {
            $result->caseResult = 'n/a';
            $result->stepResults[0]['result'] = 'n/a';
            $result->stepResults[0]['real']   = '';

            return $result;
        }

        return $result;
    }

    /**
     * 根据版本查询测试用例。
     * Query test cases by version.
     *
     * @param  int          $buildID
     * @access public
     * @return object|false
     */
    public function getByBuild(int $buildID): object|false
    {
        return $this->dao->select('*')->from(TABLE_TESTTASK)
            ->where('build')->eq($buildID)
            ->andWhere('deleted')->eq('0')
            ->fetch();
    }
}

<?php
/**
 * The model file of test task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: model.php 5114 2013-07-12 06:02:59Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class testtaskModel extends model
{
    /**
     * Create a test task.
     *
     * @param  int   $projectID
     * @access public
     * @return void
     */
    public function create($projectID = 0)
    {
        if($this->post->execution)
        {
            $execution = $this->loadModel('execution')->getByID($this->post->execution);
            $projectID = $execution->project;
        }

        if($this->post->build && empty($projectID))
        {
            $build     = $this->loadModel('build')->getById($this->post->build);
            $projectID = $build->project;
        }

        $task = fixer::input('post')
            ->setDefault('build', '')
            ->setDefault('project', $projectID)
            ->setDefault('createdBy', $this->app->user->account)
            ->setDefault('createdDate', helper::now())
            ->setDefault('members', '')
            ->stripTags($this->config->testtask->editor->create['id'], $this->config->allowedTags)
            ->join('mailto', ',')
            ->join('type', ',')
            ->join('members', ',')
            ->remove('files,labels,uid,contactListMenu')
            ->get();
        $task->members = trim($task->members, ',');

        $task = $this->loadModel('file')->processImgURL($task, $this->config->testtask->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_TESTTASK)->data($task)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($this->config->testtask->create->requiredFields, 'notempty')
            ->checkIF($task->begin != '', 'begin', 'date')
            ->checkIF($task->end != '', 'end', 'date')
            ->checkIF($task->end != '', 'end', 'ge', $task->begin)
            ->checkFlow()
            ->exec();

        if(!dao::isError())
        {
            $taskID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $taskID, 'testtask');
            $this->file->saveUpload('testtask', $taskID);
            return $taskID;
        }
    }

    /**
     * Get test tasks of a product.
     *
     * @param  int         $productID
     * @param  int|string  $branch
     * @param  string      $orderBy
     * @param  object      $pager
     * @param  array       $scopeAndStatus
     * @param  int         $beginTime
     * @param  int         $endTime
     * @access public
     * @return array
     */
    public function getProductTasks($productID, $branch = 'all', $orderBy = 'id_desc', $pager = null, $scopeAndStatus = array(), $beginTime = 0, $endTime = 0)
    {
        $products = $scopeAndStatus[0] == 'all' ? $this->app->user->view->products : array();
        $branch   = $scopeAndStatus[0] == 'all' ? 'all' : $branch;

        $tasks = $this->dao->select("t1.*, t5.multiple, IF(t2.shadow = 1, t5.name, t2.name) AS productName, t3.name as executionName, t4.name AS buildName, t4.branch AS branch, t5.name AS projectName")
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->leftJoin(TABLE_EXECUTION)->alias('t3')->on('t1.execution = t3.id')
            ->leftJoin(TABLE_BUILD)->alias('t4')->on('t1.build = t4.id')
            ->leftJoin(TABLE_PROJECT)->alias('t5')->on('t3.project = t5.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.auto')->ne('unit')
            ->beginIF(!$this->app->user->admin)->andWhere('t1.execution')->in("0,{$this->app->user->view->sprints}")->fi()
            ->beginIF($scopeAndStatus[0] == 'local')->andWhere('t1.product')->eq((int)$productID)->fi()
            ->beginIF($scopeAndStatus[0] == 'all')->andWhere('t1.product')->in($products)->fi()
            ->beginIF(strtolower($scopeAndStatus[1]) == 'myinvolved')
            ->andWhere('(t1.owner')->eq($this->app->user->account)
            ->orWhere("FIND_IN_SET('{$this->app->user->account}', t1.members)")
            ->markRight(1)
            ->fi()
            ->beginIF(strtolower($scopeAndStatus[1]) == 'totalstatus')->andWhere('t1.status')->in('blocked,doing,wait,done')->fi()
            ->beginIF(!in_array(strtolower($scopeAndStatus[1]), array('totalstatus', 'review', 'myinvolved'), true))->andWhere('t1.status')->eq($scopeAndStatus[1])->fi()
            ->beginIF($branch !== 'all')->andWhere("CONCAT(',', t4.branch, ',')")->like("%,$branch,%")->fi()
            ->beginIF($beginTime)->andWhere('t1.begin')->ge($beginTime)->fi()
            ->beginIF($endTime)->andWhere('t1.end')->le($endTime)->fi()
            ->beginIF($branch == BRANCH_MAIN)
            ->orWhere('(t1.build')->eq('trunk')
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.product')->eq((int)$productID)
            ->markRight(1)
            ->fi()
            ->beginIF($scopeAndStatus[1] == 'review')
            ->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")
            ->andWhere('t1.reviewStatus')->eq('doing')
            ->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        foreach($tasks as $taskID => $task)
        {
            if($task->multiple)
            {
                if($task->projectName and $task->executionName)
                {
                    $tasks[$taskID]->executionName = $task->projectName . '/' . $task->executionName;
                }
                elseif(!$task->executionName)
                {
                    $tasks[$taskID]->executionName = $task->projectName;
                }
            }
        }

        return $tasks;
    }

    /**
     * Get product unit tasks.
     *
     * @param  int    $productID
     * @param  string $browseType
     * @param  string $orderBy
     * @param  int    $pager
     * @access public
     * @return void
     */
    public function getProductUnitTasks($productID, $browseType = '', $orderBy = 'id_desc', $pager = null)
    {
        $beginAndEnd = $this->loadModel('action')->computeBeginAndEnd($browseType);
        if(empty($beginAndEnd)) $beginAndEnd = array('begin' => '', 'end' => '');

        if($browseType == 'newest') $orderBy = 'end_desc,' . $orderBy;
        $tasks = $this->dao->select("t1.*, t2.name AS productName, t3.name AS executionName, t4.name AS buildName")
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->leftJoin(TABLE_EXECUTION)->alias('t3')->on('t1.execution = t3.id')
            ->leftJoin(TABLE_BUILD)->alias('t4')->on('t1.build = t4.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.product')->eq($productID)
            ->beginIF($this->lang->navGroup->testtask != 'qa')->andWhere('t1.project')->eq($this->session->project)->fi()
            ->andWhere('t1.auto')->eq('unit')
            ->beginIF($browseType != 'all' and $browseType != 'newest' and $beginAndEnd)
            ->andWhere('t1.end')->ge($beginAndEnd['begin'])
            ->andWhere('t1.end')->le($beginAndEnd['end'])
            ->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        $resultGroups = $this->dao->select('t1.task, t2.*')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_TESTRESULT)->alias('t2')->on('t1.id=t2.run')
            ->where('t1.task')->in(array_keys($tasks))
            ->fetchGroup('task', 'run');

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

        return $tasks;
    }

    /**
     * Get test tasks of a project.
     *
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getProjectTasks($projectID, $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('t1.*, t2.name AS buildName')
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_BUILD)->alias('t2')->on('t1.build = t2.id')
            ->where('t1.project')->eq((int)$projectID)
            ->andWhere('t1.auto')->ne('unit')
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get test tasks of a execution.
     *
     * @param  int    $executionID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getExecutionTasks($executionID, $objectType = 'execution', $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('t1.*, t2.name AS buildName')
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_BUILD)->alias('t2')->on('t1.build = t2.id')
            ->where('t1.deleted')->eq(0)
            ->beginIF($objectType == 'execution')->andWhere('t1.execution')->eq((int)$executionID)->fi()
            ->beginIF($objectType == 'project')->andWhere('t1.project')->eq((int)$executionID)->fi()
            ->andWhere('t1.auto')->ne('unit')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get testtask pairs.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param  string $appendIdList
     * @param  string $params         noempty
     * @access public
     * @return array
     */
    public function getPairs($productID, $executionID = 0, $appendIdList = '', $params = '')
    {
        $pairs = $this->dao->select('id,name')->from(TABLE_TESTTASK)
            ->where('product')->eq((int)$productID)
            ->beginIF($executionID)->andWhere('execution')->eq((int)$executionID)->fi()
            ->andWhere('auto')->ne('unit')
            ->andWhere('deleted')->eq(0)
            ->orderBy('id_desc')
            ->fetchPairs('id', 'name');

        if($appendIdList) $pairs += $this->dao->select('id,name')->from(TABLE_TESTTASK)->where('id')->in($appendIdList)->fetchPairs('id', 'name');
        if(strpos($params, 'noempty') === false) $pairs = array(0 => '') + $pairs;

        return $pairs;
    }

    /**
     * Get task by idList.
     *
     * @param  array    $idList
     * @access public
     * @return array
     */
    public function getByList($idList)
    {
        return $this->dao->select("*")->from(TABLE_TESTTASK)->where('id')->in($idList)->fetchAll('id');
    }

    /**
     * Get test task info by id.
     *
     * @param  int   $taskID
     * @param  bool  $setImgSize
     * @access public
     * @return void
     */
    public function getById($taskID, $setImgSize = false)
    {
        $task = $this->dao->select("*")->from(TABLE_TESTTASK)->where('id')->eq((int)$taskID)->fetch();
        if($task)
        {
            $product = $this->dao->select('name,type')->from(TABLE_PRODUCT)->where('id')->eq($task->product)->fetch();
            $task->productName   = $product->name;
            $task->productType   = $product->type;
            $task->branch        = 0;
            $task->executionName = '';
            $task->buildName     = '';

            if($task->execution)
            {
                $task->executionName = $this->dao->select('name')->from(TABLE_EXECUTION)->where('id')->eq($task->execution)->fetch('name');
                $task->branch        = $this->dao->select('branch')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($task->execution)->andWhere('product')->eq($task->product)->fetch('branch');
            }

            $build = $this->dao->select('branch,name')->from(TABLE_BUILD)->where('id')->eq($task->build)->fetch();
            if($build)
            {
                $task->buildName = $build->name;
                $task->branch    = $build->branch;
            }
        }

        if(!$task) return false;

        $task = $this->loadModel('file')->replaceImgURL($task, 'desc');
        if($setImgSize) $task->desc = $this->loadModel('file')->setImgSize($task->desc);
        $task->files = $this->loadModel('file')->getByObject('testtask', $task->id);
        return $task;
    }

    /**
     * Get test tasks by user.
     *
     * @param   string $account
     * @access  public
     * @return  array
     */
    public function getByUser($account, $pager = null, $orderBy = 'id_desc', $type = '')
    {
        return $this->dao->select("t1.*, t2.name AS executionName, t2.multiple as executionMultiple, t5.name as projectName, t3.name AS buildName")
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_BUILD)->alias('t3')->on('t1.build = t3.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t4')->on('t1.product = t4.id')
            ->leftJoin(TABLE_PROJECT)->alias('t5')->on('t2.project = t5.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t4.deleted')->eq(0)
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
     * Get taskrun by case id.
     *
     * @param  int    $taskID
     * @param  int    $caseID
     * @access public
     * @return void
     */
    public function getRunByCase($taskID, $caseID)
    {
        return $this->dao->select('*')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->andWhere('`case`')->eq($caseID)->fetch();
    }

    /**
     * Get linkable casses.
     *
     * @param  int    $productID
     * @param  object $task
     * @param  int    $taskID
     * @param  string $type
     * @param  string $param
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLinkableCases($productID, $task, $taskID, $type, $param, $pager)
    {
        if($this->session->testcaseQuery == false) $this->session->set('testcaseQuery', ' 1 = 1');
        $query = $this->session->testcaseQuery;
        $allProduct = "`product` = 'all'";
        if(strpos($query, '`product` =') === false && $type != 'bysuite') $query .= " AND `product` = $productID";
        if(strpos($query, $allProduct) !== false) $query = str_replace($allProduct, '1', $query);

        $cases = array();
        $linkedCases = $this->dao->select('`case`')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->fetchPairs('case');
        if($type == 'all')     $cases = $this->getAllLinkableCases($task, $query, $linkedCases, $pager);
        if($type == 'bystory') $cases = $this->getLinkableCasesByStory($productID, $task, $query, $linkedCases, $pager);
        if($type == 'bybug')   $cases = $this->getLinkableCasesByBug($productID, $task, $query, $linkedCases, $pager);
        if($type == 'bysuite') $cases = $this->getLinkableCasesBySuite($productID, $task, $query, $param, $linkedCases, $pager);
        if($type == 'bybuild') $cases = $this->getLinkableCasesByTestTask($param, $linkedCases, $query, $pager);

        return $cases;
    }

    /**
     * Get all linkable  cases.
     *
     * @param  object $task
     * @param  string $query
     * @param  array  $linkedCases
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getAllLinkableCases($task, $query, $linkedCases, $pager)
    {
        return $this->dao->select('*')->from(TABLE_CASE)->where($query)
                ->beginIF(!empty($linkedCases))->andWhere('id')->notIN($linkedCases)->fi()
                ->andWhere('status')->ne('wait')
                ->andWhere('type')->ne('unit')
                ->beginIF($task->branch !== '')->andWhere('branch')->in("0,$task->branch")->fi()
                ->andWhere('deleted')->eq(0)
                ->orderBy('id desc')
                ->page($pager)
                ->fetchAll();
    }

    /**
     * Get linkable cases by story.
     *
     * @param  int    $productID
     * @param  object $task
     * @param  string $query
     * @param  array  $linkedCases
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLinkableCasesByStory($productID, $task, $query, $linkedCases, $pager)
    {
        $stories = $this->dao->select('stories')->from(TABLE_BUILD)->where('id')->eq($task->build)->fetch('stories');
        $cases   = array();
        $query   = preg_replace('/`(\w+)`/', 't1.`$1`', $query);
        if($stories)
        {
            $cases = $this->dao->select('t1.*,t2.title as storyTitle')->from(TABLE_CASE)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->where($query)
                ->beginIF($this->lang->navGroup->testtask != 'qa')->andWhere('t1.project')->eq($this->session->project)->fi()
                ->andWhere('t1.product')->eq($productID)
                ->andWhere('t1.status')->ne('wait')
                ->beginIF(!empty($linkedCases))->andWhere('t1.id')->notIN($linkedCases)->fi()
                ->beginIF($task->branch !== '')->andWhere('t1.branch')->in("0,$task->branch")->fi()
                ->andWhere('t1.story')->in(trim($stories, ','))
                ->andWhere('t1.deleted')->eq(0)
                ->orderBy('t1.id desc')
                ->page($pager)
                ->fetchAll();
        }

        return $cases;
    }

    /**
     * Get linkable cases by bug.
     *
     * @param  int    $productID
     * @param  object $task
     * @param  string $query
     * @param  array  $linkedCases
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLinkableCasesByBug($productID, $task, $query, $linkedCases, $pager)
    {
        $bugs = $this->dao->select('bugs')->from(TABLE_BUILD)->where('id')->eq($task->build)->fetch('bugs');
        $cases = array();
        if($bugs)
        {
            $cases = $this->dao->select('*')->from(TABLE_CASE)->where($query)
                ->beginIF($this->lang->navGroup->testtask != 'qa')->andWhere('project')->eq($this->session->project)->fi()
                ->andWhere('product')->eq($productID)
                ->andWhere('status')->ne('wait')
                ->beginIF($linkedCases)->andWhere('id')->notIN($linkedCases)->fi()
                ->beginIF($task->branch !== '')->andWhere('branch')->in("0,$task->branch")->fi()
                ->andWhere('fromBug')->in(trim($bugs, ','))
                ->andWhere('deleted')->eq(0)
                ->orderBy('id desc')
                ->page($pager)
                ->fetchAll();
        }

        return $cases;
    }

    /**
     * Get linkable cases by suite.
     *
     * @param  int    $productID
     * @param  object $task
     * @param  string $query
     * @param  string $suite
     * @param  array  $linkedCases
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLinkableCasesBySuite($productID, $task, $query, $suite, $linkedCases, $pager)
    {
        if(strpos($query, '`product`') !== false) $query = str_replace('`product`', 't1.`product`', $query);
        return $this->dao->select('t1.*,t2.version as version')->from(TABLE_CASE)->alias('t1')
                ->leftJoin(TABLE_SUITECASE)->alias('t2')->on('t1.id=t2.case')
                ->where($query)
                ->beginIF($this->lang->navGroup->testtask != 'qa')->andWhere('t1.project')->eq($this->session->project)->fi()
                ->andWhere('t2.suite')->eq((int)$suite)
                ->andWhere('t1.product')->eq($productID)
                ->andWhere('status')->ne('wait')
                ->beginIF($linkedCases)->andWhere('t1.id')->notIN($linkedCases)->fi()
                ->beginIF($task->branch !== '')->andWhere('t1.branch')->in("0,$task->branch")->fi()
                ->andWhere('deleted')->eq(0)
                ->orderBy('id desc')
                ->page($pager)
                ->fetchAll();
    }

    /**
     * Get linkeable cases by test task.
     *
     * @param  string $testTask
     * @param  array  $linkedCases
     * @param  string $query
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLinkableCasesByTestTask($testTask, $linkedCases, $query, $pager)
    {
        /* Format the query condition. */
        $query = preg_replace('/`(\w+)`/', 't1.`$1`', $query);
        $query = str_replace('t1.`lastRunner`', 't2.`lastRunner`', $query);
        $query = str_replace('t1.`lastRunDate`', 't2.`lastRunDate`', $query);
        $query = str_replace('t1.`lastRunResult`', 't2.`lastRunResult`', $query);

        return $this->dao->select("t1.*,t2.lastRunner,t2.lastRunDate,t2.lastRunResult")->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_TESTRUN)->alias('t2')->on('t1.id = t2.case')
            ->where($query)
            ->andWhere('t1.id')->notin($linkedCases)
            ->andWhere('t2.task')->eq($testTask)
            ->beginIF($this->lang->navGroup->testtask != 'qa')->andWhere('t1.project')->eq($this->session->project)->fi()
            ->andWhere('t1.status')->ne('wait')
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get related test tasks.
     *
     * @param  int    $productID
     * @param  int    $testtaskID
     * @access public
     * @return array
     */
    public function getRelatedTestTasks($productID, $testTaskID)
    {
        $beginDate = $this->dao->select('begin')->from(TABLE_TESTTASK)->where('id')->eq($testTaskID)->fetch('begin');

        return $this->dao->select('id, name')->from(TABLE_TESTTASK)
            ->where('product')->eq($productID)
            ->andWhere('auto')->ne('unit')
            ->beginIF($beginDate)->andWhere('begin')->le($beginDate)->fi()
            ->andWhere('deleted')->eq('0')
            ->andWhere('id')->notin($testTaskID)
            ->orderBy('begin desc')
            ->fetchPairs('id', 'name');
    }

    /**
     * Get report data of test task per run result.
     *
     * @param  int     $taskID
     * @access public
     * @return array
     */
    public function getDataOfTestTaskPerRunResult($taskID)
    {
        $datas = $this->dao->select("t1.lastRunResult AS name, COUNT('t1.*') AS value")->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')
            ->on('t1.case = t2.id')
            ->where('t1.task')->eq($taskID)
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('t1.lastRunResult')
            ->orderBy('value DESC')
            ->fetchAll('name');

        if(!$datas) return array();

        $this->app->loadLang('testcase');
        foreach($datas as $result => $data) $data->name = isset($this->lang->testcase->resultList[$result])? $this->lang->testcase->resultList[$result] : $this->lang->testtask->unexecuted;

        return $datas;
    }

    /**
     * Get report data of test task per Type.
     *
     * @param  int     $taskID
     * @access public
     * @return array
     */
    public function getDataOfTestTaskPerType($taskID)
    {
        $datas = $this->dao->select('t2.type as name,count(*) as value')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->where('t1.task')->eq($taskID)
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('t2.type')
            ->orderBy('value desc')
            ->fetchAll('name');
        if(!$datas) return array();

        foreach($datas as $result => $data) if(isset($this->lang->testcase->typeList[$result])) $data->name = $this->lang->testcase->typeList[$result];

        return $datas;
    }

    /**
     * Get report data of test task per module
     *
     * @param  int     $taskID
     * @access public
     * @return array
     */
    public function getDataOfTestTaskPerModule($taskID)
    {
        $datas = $this->dao->select('t2.module as name,count(*) as value')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->where('t1.task')->eq($taskID)
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('t2.module')
            ->orderBy('value desc')
            ->fetchAll('name');
        if(!$datas) return array();

        $modules = $this->loadModel('tree')->getModulesName(array_keys($datas));
        foreach($datas as $moduleID => $data) $data->name = isset($modules[$moduleID]) ? $modules[$moduleID] : '/';

        return $datas;
    }

    /**
     * Get report data of test task per runner
     *
     * @param  int     $taskID
     * @access public
     * @return array
     */
    public function getDataOfTestTaskPerRunner($taskID)
    {
        $datas = $this->dao->select("t1.lastRunner AS name, COUNT('t1.*') AS value")->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->where('t1.task')->eq($taskID)
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('t1.lastRunner')
            ->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();
        $users = $this->loadModel('user')->getPairs('noclosed|noletter');
        foreach($datas as $result => $data) $data->name = $result ? zget($users, $result, $result) : $this->lang->testtask->unexecuted;

        return $datas;
    }

    /**
     * Get bug info.
     *
     * @param  int    $taskID
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getBugInfo($taskID, $productID)
    {
        $foundBugs = $this->dao->select('*')->from(TABLE_BUG)->where('product')->in($productID)->andWhere('testtask')->eq($taskID)->andWhere('deleted')->eq(0)->fetchAll();

        $severityGroups = $statusGroups = $openedByGroups = $resolvedByGroups = $resolutionGroups = $moduleGroups = array();
        $resolvedBugs   = 0;
        foreach($foundBugs as $bug)
        {
            $severityGroups[$bug->severity] = isset($severityGroups[$bug->severity]) ? $severityGroups[$bug->severity] + 1 : 1;
            $statusGroups[$bug->status]     = isset($statusGroups[$bug->status])     ? $statusGroups[$bug->status]     + 1 : 1;
            $openedByGroups[$bug->openedBy] = isset($openedByGroups[$bug->openedBy]) ? $openedByGroups[$bug->openedBy] + 1 : 1;
            $moduleGroups[$bug->module]     = isset($moduleGroups[$bug->module])     ? $moduleGroups[$bug->module]     + 1 : 1;

            if($bug->resolvedBy) $resolvedByGroups[$bug->resolvedBy] = isset($resolvedByGroups[$bug->resolvedBy]) ? $resolvedByGroups[$bug->resolvedBy] + 1 : 1;
            if($bug->resolution) $resolutionGroups[$bug->resolution] = isset($resolutionGroups[$bug->resolution]) ? $resolutionGroups[$bug->resolution] + 1 : 1;
            if($bug->status == 'resolved' or $bug->status == 'closed') $resolvedBugs ++;
        }

        $bugInfo['bugConfirmedRate']    = empty($resolvedBugs) ? 0 : round((zget($resolutionGroups, 'fixed', 0) + zget($resolutionGroups, 'postponed', 0)) / $resolvedBugs * 100, 2);
        $bugInfo['bugCreateByCaseRate'] = empty($byCaseNum) ? 0 : round($byCaseNum / count($foundBugs) * 100, 2);

        $this->app->loadLang('bug');
        $users = $this->loadModel('user')->getPairs('noclosed|noletter|nodeleted');
        $data  = array();
        foreach($severityGroups as $severity => $count)
        {
            $data[$severity] = new stdclass();
            $data[$severity]->name  = zget($this->lang->bug->severityList, $severity);
            $data[$severity]->value = $count;
        }
        $bugInfo['bugSeverityGroups'] = $data;

        $data = array();
        foreach($statusGroups as $status => $count)
        {
            $data[$status] = new stdclass();
            $data[$status]->name  = zget($this->lang->bug->statusList, $status);
            $data[$status]->value = $count;
        }
        $bugInfo['bugStatusGroups'] = $data;

        $data = array();
        foreach($resolutionGroups as $resolution => $count)
        {
            $data[$resolution] = new stdclass();
            $data[$resolution]->name  = zget($this->lang->bug->resolutionList, $resolution);
            $data[$resolution]->value = $count;
        }
        $bugInfo['bugResolutionGroups'] = $data;

        $data = array();
        foreach($openedByGroups as $openedBy => $count)
        {
            $data[$openedBy] = new stdclass();
            $data[$openedBy]->name  = zget($users, $openedBy);
            $data[$openedBy]->value = $count;
        }
        $bugInfo['bugOpenedByGroups'] = $data;

        $this->loadModel('tree');
        $modules = $this->tree->getOptionMenu($productID, $viewType = 'bug');
        $data    = array();
        foreach($moduleGroups as $moduleID => $count)
        {
            $data[$moduleID] = new stdclass();
            $data[$moduleID]->name  = zget($modules, $moduleID);
            $data[$moduleID]->value = $count;
        }
        $bugInfo['bugModuleGroups'] = $data;

        $data = array();
        foreach($resolvedByGroups as $resolvedBy => $count)
        {
            $data[$resolvedBy] = new stdclass();
            $data[$resolvedBy]->name  = zget($users, $resolvedBy);
            $data[$resolvedBy]->value = $count;
        }
        $bugInfo['bugResolvedByGroups'] = $data;

        return $bugInfo;
    }

     /**
     * Merge the default chart settings and the settings of current chart.
     *
     * @param  string    $chartType
     * @access public
     * @return void
     */
    public function mergeChartOption($chartType)
    {
        $chartOption  = isset($this->lang->testtask->report->$chartType) ? $this->lang->testtask->report->$chartType : new stdclass();
        $commonOption = $this->lang->testtask->report->options;

        if(!isset($chartOption->graph)) $chartOption->graph = new stdclass();
        $chartOption->graph->caption = $this->lang->testtask->report->charts[$chartType];
        if(!isset($chartOption->type))    $chartOption->type  = $commonOption->type;
        if(!isset($chartOption->width))  $chartOption->width  = $commonOption->width;
        if(!isset($chartOption->height)) $chartOption->height = $commonOption->height;

        /* 合并配置。*/
        foreach($commonOption->graph as $key => $value) if(!isset($chartOption->graph->$key)) $chartOption->graph->$key = $value;
        return $chartOption;
    }

    /**
     * Update a test task.
     *
     * @param  int   $taskID
     * @access public
     * @return void
     */
    public function update($taskID)
    {
        $oldTask = $this->getById($taskID);
        $task = fixer::input('post')
            ->add('id', $taskID)
            ->setDefault('type', '')
            ->setDefault('mailto', '')
            ->setDefault('deleteFiles', array())
            ->setDefault('members', '')
            ->stripTags($this->config->testtask->editor->edit['id'], $this->config->allowedTags)
            ->join('mailto', ',')
            ->join('type', ',')
            ->join('members', ',')
            ->remove('files,labels,uid,comment,contactListMenu')
            ->get();
        $task->members = trim($task->members, ',');

        /* Fix bug #35419. */
        $execution = $this->loadModel('execution')->getByID($task->execution);
        if(!$execution)
        {
            $build         = $this->loadModel('build')->getById($task->build);
            $task->project = $build->project;
        }
        else
        {
            $task->project = $execution->project;
        }

        $task = $this->loadModel('file')->processImgURL($task, $this->config->testtask->editor->edit['id'], $this->post->uid);

        $this->dao->update(TABLE_TESTTASK)->data($task, 'deleteFiles')
            ->autoCheck()
            ->batchcheck($this->config->testtask->edit->requiredFields, 'notempty')
            ->checkIF($task->end != '', 'end', 'ge', $task->begin)
            ->checkFlow()
            ->where('id')->eq($taskID)
            ->exec();

        if(!dao::isError())
        {
            $this->file->processFile4Object('testtask', $oldTask, $task);
            return common::createChanges($oldTask, $task);
        }
    }

    /**
     * Start testtask.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function start($taskID)
    {
        $oldTesttask = $this->getById($taskID);
        $testtask = fixer::input('post')
            ->add('id', $taskID)
            ->setDefault('status', 'doing')
            ->stripTags($this->config->testtask->editor->start['id'], $this->config->allowedTags)
            ->remove('comment')->get();

        $testtask = $this->loadModel('file')->processImgURL($testtask, $this->config->testtask->editor->start['id'], $this->post->uid);
        $this->dao->update(TABLE_TESTTASK)->data($testtask)
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq((int)$taskID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldTesttask, $testtask);
    }

    /**
     * Close testtask.
     *
     * @access public
     * @return void
     */
    public function close($taskID)
    {
        $oldTesttask = $this->getById($taskID);
        $testtask = fixer::input('post')
            ->add('id', $taskID)
            ->setDefault('status', 'done')
            ->stripTags($this->config->testtask->editor->close['id'], $this->config->allowedTags)
            ->join('mailto', ',')
            ->remove('comment,uid')
            ->get();

        if($testtask->realFinishedDate <= $oldTesttask->begin)
        {
            dao::$errors[] = sprintf($this->lang->testtask->finishedDateLess, $oldTesttask->begin);
            return false;
        }
        if($testtask->realFinishedDate > date("Y-m-d 00:00:00", strtotime("+1 day")))
        {
            dao::$errors[] = $this->lang->testtask->finishedDateMore;
            return false;
        }

        $testtask = $this->loadModel('file')->processImgURL($testtask, $this->config->testtask->editor->close['id'], $this->post->uid);
        $this->dao->update(TABLE_TESTTASK)->data($testtask)
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq((int)$taskID)
            ->exec();

        if(!dao::isError())
        {
            $this->file->updateObjectID($this->post->uid, $taskID, 'testtask');
            return common::createChanges($oldTesttask, $testtask);
        }
    }

    /**
     * update block testtask.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function block($taskID)
    {
        $oldTesttask = $this->getById($taskID);
        $testtask = fixer::input('post')
            ->add('id', $taskID)
            ->setDefault('status', 'blocked')
            ->stripTags($this->config->testtask->editor->block['id'], $this->config->allowedTags)
            ->remove('comment')->get();

        $testtask = $this->loadModel('file')->processImgURL($testtask, $this->config->testtask->editor->block['id'], $this->post->uid);
        $this->dao->update(TABLE_TESTTASK)->data($testtask)
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq((int)$taskID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldTesttask, $testtask);
    }

    /**
     * update activate testtask.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function activate($taskID)
    {
        $oldTesttask = $this->getById($taskID);
        $testtask = fixer::input('post')
            ->setDefault('status', 'doing')
            ->stripTags($this->config->testtask->editor->activate['id'], $this->config->allowedTags)
            ->remove('comment')->get();

        $testtask = $this->loadModel('file')->processImgURL($testtask, $this->config->testtask->editor->activate['id'], $this->post->uid);
        $this->dao->update(TABLE_TESTTASK)->data($testtask)
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq((int)$taskID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldTesttask, $testtask);
    }

    /**
     * Link cases.
     *
     * @param  int    $taskID
     * @param  string $type
     * @access public
     * @return void
     */
    public function linkCase($taskID, $type)
    {
        if($this->post->cases == false) return;
        $postData = fixer::input('post')->get();
        $task     = $this->getById($taskID);

        if($type == 'bybuild') $assignedToPairs = $this->dao->select('`case`, assignedTo')->from(TABLE_TESTRUN)->where('`case`')->in($postData)->fetchPairs('case', 'assignedTo');
        foreach($postData->cases as $caseID)
        {
            $row = new stdclass();
            $row->task       = $taskID;
            $row->case       = $caseID;
            $row->version    = $postData->versions[$caseID];
            $row->assignedTo = '';
            $row->status     = 'normal';
            if($type == 'bybuild') $row->assignedTo = zget($assignedToPairs, $caseID, '');
            $this->dao->replace(TABLE_TESTRUN)->data($row)->exec();

            /* When the cases linked the testtask, the cases link to the project. */
            if($task->project or $task->execution)
            {
                $data = new stdclass();
                $data->case    = $caseID;
                $data->version = 1;
                $data->product = $task->product;

                if($task->project)
                {
                    $projectID = $task->project;
                    $lastOrder = (int)$this->dao->select('*')->from(TABLE_PROJECTCASE)->where('project')->eq($projectID)->orderBy('order_desc')->limit(1)->fetch('order');

                    $data->project = $projectID;
                    $data->order   = ++ $lastOrder;
                    $this->dao->replace(TABLE_PROJECTCASE)->data($data)->exec();
                }

                if($task->execution)
                {
                    $projectID = $task->execution;
                    $lastOrder = (int)$this->dao->select('*')->from(TABLE_PROJECTCASE)->where('project')->eq($projectID)->orderBy('order_desc')->limit(1)->fetch('order');

                    $data->project = $projectID;
                    $data->order   = ++ $lastOrder;
                    $this->dao->replace(TABLE_PROJECTCASE)->data($data)->exec();
                }
            }
            $this->loadModel('action')->create('case', $caseID, 'linked2testtask', '', $taskID);
        }
    }

    /**
     * Get test runs of a test task.
     *
     * @param  int    $taskID
     * @param  int    $moduleID
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getRuns($taskID, $moduleID, $orderBy, $pager = null)
    {
        /* Select the table for these special fields. */
        $specialFields = ',assignedTo,status,lastRunResult,lastRunner,lastRunDate,';
        $fieldToSort   = substr($orderBy, 0, strpos($orderBy, '_'));
        $orderBy       = strpos($specialFields, ',' . $fieldToSort . ',') !== false ? ('t1.' . $orderBy) : ('t2.' . $orderBy);

        return $this->dao->select('t2.*,t1.*,t2.version as caseVersion,t3.title as storyTitle,t2.status as caseStatus')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t2.story = t3.id')
            ->where('t1.task')->eq((int)$taskID)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($moduleID)->andWhere('t2.module')->in($moduleID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get test runs of a suite.
     *
     * @param  int    $taskID
     * @param  int    $suiteID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getRunsBySuite($taskID, $suiteID, $orderBy, $pager = null)
    {
        /* Select the table for these special fields. */
        $specialFields = ',assignedTo,status,lastRunResult,lastRunner,lastRunDate,';
        $fieldToSort   = substr($orderBy, 0, strpos($orderBy, '_'));
        $orderBy       = strpos($specialFields, ',' . $fieldToSort . ',') !== false ? ('t1.' . $orderBy) : ('t2.' . $orderBy);

        $cases = $this->loadModel('testsuite')->getLinkedCasePairs($suiteID);

        return $this->dao->select('t2.*,t1.*,t2.version as caseVersion,t3.title as storyTitle,t2.status as caseStatus')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t2.story = t3.id')
            ->where('t1.task')->eq((int)$taskID)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.id')->in(array_keys($cases))
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get test runs of a user.
     *
     * @param  int    $taskID
     * @param  int    $user
     * @param  obejct $pager
     * @access public
     * @return array
     */
    public function getUserRuns($taskID, $user, $modules = '', $orderBy = 'id_desc', $pager = null)
    {
        /* Select the table for these special fields. */
        $specialFields = ',assignedTo,status,lastRunResult,lastRunner,lastRunDate,';
        $fieldToSort   = substr($orderBy, 0, strpos($orderBy, '_'));
        $orderBy       = strpos($specialFields, ',' . $fieldToSort . ',') !== false ? ('t1.' . $orderBy) : ('t2.' . $orderBy);

        return $this->dao->select('t2.*,t1.*,t2.version as caseVersion,t3.title as storyTitle,t2.status as caseStatus')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t2.story = t3.id')
            ->where('t1.task')->eq((int)$taskID)
            ->andWhere('t1.assignedTo')->eq($user)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($modules)->andWhere('t2.module')->in($modules)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get testtask linked cases.
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
    public function getTaskCases($productID, $browseType, $queryID, $moduleID, $sort, $pager, $task)
    {
        /* Set modules and browse type. */
        $modules    = $moduleID ? $this->loadModel('tree')->getAllChildId($moduleID) : '0';
        $browseType = ($browseType == 'bymodule' and $this->session->taskCaseBrowseType and $this->session->taskCaseBrowseType != 'bysearch') ? $this->session->taskCaseBrowseType : $browseType;
        $browseType = strtolower($browseType);

        if($browseType == 'bymodule' or $browseType == 'all')
        {
            $runs = $this->getRuns($task->id, $modules, $sort, $pager);
        }
        elseif($browseType == 'bysuite')
        {
            $runs = $this->getRunsBySuite($task->id, $queryID, $sort, $pager);
        }
        elseif($browseType == 'assignedtome')
        {
            $runs = $this->getUserRuns($task->id, $this->session->user->account, $modules, $sort, $pager);
        }
        /* By search. */
        elseif($browseType == 'bysearch')
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

            $queryProductID = $productID;
            $allProduct     = "`product` = 'all'";
            $caseQuery      = $this->session->testtaskQuery;
            if(strpos($this->session->testtaskQuery, $allProduct) !== false)
            {
                $caseQuery = str_replace($allProduct, '1', $this->session->testtaskQuery);
                $caseQuery = $caseQuery . ' AND `product` ' . helper::dbIN($this->app->user->view->products);
                $queryProductID = 'all';
            }

            $caseQuery = preg_replace('/`(\w+)`/', 't2.`$1`', $caseQuery);
            $caseQuery = str_replace(array('t2.`assignedTo`', 't2.`lastRunner`', 't2.`lastRunDate`', 't2.`lastRunResult`'), array('t1.`assignedTo`', 't1.`lastRunner`', 't1.`lastRunDate`', 't1.`lastRunResult`'), $caseQuery);

            /* Select the table for these special fields. */
            $specialFields = ',assignedTo,status,lastRunResult,lastRunner,lastRunDate,';
            $fieldToSort   = substr($sort, 0, strpos($sort, '_'));
            $orderBy       = strpos($specialFields, ',' . $fieldToSort . ',') !== false ? ('t1.' . $sort) : ('t2.' . $sort);

            $runs = $this->dao->select('t2.*,t1.*,t2.version as caseVersion,t3.title as storyTitle,t2.status as caseStatus')->from(TABLE_TESTRUN)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
                ->leftJoin(TABLE_STORY)->alias('t3')->on('t2.story = t3.id')
                ->where($caseQuery)
                ->andWhere('t1.task')->eq($task->id)
                ->andWhere('t2.deleted')->eq(0)
                ->beginIF($queryProductID != 'all')->andWhere('t2.product')->eq($queryProductID)->fi()
                ->beginIF($task->branch)->andWhere('t2.branch')->in("0,{$task->branch}")->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }

        return $runs;
    }

    /**
     * Get testtask pairs of a user.
     *
     * @param  string $account
     * @param  int    $limit
     * @param  string $status all|wait|doing|done|blocked
     * @param  array  $skipProductIDList
     * @param  array  $skipExecutionIDList
     * @access public
     * @return array
     */
    public function getUserTestTaskPairs($account, $limit = 0, $status = 'all', $skipProductIDList = array(), $skipExecutionIDList = array())
    {
        $stmt = $this->dao->select('t1.id, t1.name, t2.name as execution')
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftjoin(TABLE_EXTENSION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product=t3.id')
            ->where('t1.owner')->eq($account)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
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
     * Get info of a test run.
     *
     * @param  int   $runID
     * @access public
     * @return void
     */
    public function getRunById($runID)
    {
        $testRun = $this->dao->findById($runID)->from(TABLE_TESTRUN)->fetch();
        $testRun->case = $this->loadModel('testcase')->getById($testRun->case, $testRun->version);
        return $testRun;
    }

    /**
     * Get testtasks by case id list.
     *
     * @param  string|array $caseIDList
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
     * Get scene list include sub scenes and runs.
     *
     * @param  int    $productID
     * @param  array  $runs
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function getSceneCases($productID, $runs, $orderBy = 'id_desc')
    {
        $runs = $this->loadModel('testcase')->appendData($runs, 'run');
        foreach($runs as $id => $run)
        {
            $run->run     = $run->id;
            $run->id      = 'case_' . $run->case;
            $run->parent  = 0;
            $run->isScene = false;
            if($run->scene)
            {
                $sceneCases[$run->scene][$run->id] = $run;
                unset($runs[$id]);
            }
        }

        $scenes = $this->dao->select('*')->from(TABLE_SCENE)
            ->where('deleted')->eq('0')
            ->andWhere('product')->eq($productID)
            ->orderBy('grade_desc, sort_asc')
            ->fetchAll('id');

        if(!$scenes) return array('scenes' => array(), 'runs' => $runs);

        $sceneCases = array();

        $this->dao->setTable(TABLE_CASE);
        $fieldTypes = $this->dao->getFieldsType();

        foreach($scenes as $id => $scene)
        {
            /* Set default value for the fields exist in TABLE_CASE but not in TABLE_SCENE. */
            foreach($fieldTypes as $field => $type)
            {
                if(isset($scene->$field)) continue;
                $scene->$field = $type['rule'] == 'int' ? '0' : '';
            }

            $scene->bugs       = 0;
            $scene->results    = 0;
            $scene->caseFails  = 0;
            $scene->stepNumber = 0;
            $scene->isScene    = true;

            if(isset($sceneCases[$id]))
            {
                foreach($sceneCases[$id] as $case)
                {
                    $case->id      = 'case_' . $case->id;
                    $case->parent  = $id;
                    $case->grade   = $scene->grade + 1;
                    $case->path    = $scene->path . $case->id . ',';
                    $case->isScene = false;

                    $scene->cases[$case->id] = $case;
                }
            }

            if(!isset($scenes[$scene->parent])) continue;

            $parent = $scenes[$scene->parent];
            $parent->children[$id] = $scene;

            unset($scenes[$id]);
        }

        return array('scenes' => $scenes, 'runs' => $runs);
    }

    /**
     * Init testtask result.
     *
     * @param  int    $runID
     * @param  int    $caseID
     * @param  int    $version
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function initResult($runID = 0, $caseID = 0, $version = 0, $nodeID = 0)
    {
        $result = new stdClass();
        $result->run        = $runID;
        $result->case       = $caseID;
        $result->version    = $version;
        $result->node       = $nodeID;
        $result->date       = helper::now();
        $result->lastRunner = $this->app->user->account;

        $this->dao->insert(TABLE_TESTRESULT)->data($result)->autoCheck()->exec();
        if(!dao::isError()) return $resultID = $this->dao->lastInsertID();
        return false;
    }

    /**
     * Create test result
     *
     * @param  int   $runID
     * @access public
     * @return void
     */
    public function createResult($runID = 0)
    {
        /* Compute the test result.
         *
         * 1. if there result in the post, use it.
         * 2. if no result, set default is pass.
         * 3. then check the steps to compute result.
         *
         * */
        $postData   = fixer::input('post')->get();
        $caseResult = isset($postData->result) ? $postData->result : 'pass';
        if(isset($postData->steps) and $postData->steps)
        {
            foreach($postData->steps as $stepID => $stepResult)
            {
                if($stepResult != 'pass' and $stepResult != 'n/a') $caseResult = $stepResult;
                if($stepResult == 'fail')
                {
                    $caseResult = $stepResult;
                    break;
                }
            }
        }

        /* Create result of every step. */
        foreach($postData->steps as $stepID =>$stepResult)
        {
            $step['result'] = $stepResult;
            $step['real']   = $postData->reals[$stepID];
            $stepResults[$stepID] = $step;
        }

        /* Insert into testResult table. */
        $now = helper::now();
        $result = fixer::input('post')
            ->add('run', $runID)
            ->add('caseResult', $caseResult)
            ->setForce('stepResults', serialize($stepResults))
            ->setDefault('lastRunner', $this->app->user->account)
            ->setDefault('date', $now)
            ->skipSpecial('stepResults')
            ->remove('steps,reals,result')
            ->get();

        /* Remove files and labels field when uploading files for case result or step result. */
        foreach($result as $fieldName => $field)
        {
            if((strpos($fieldName, 'files') !== false) or (strpos($fieldName, 'labels') !== false)) unset($result->$fieldName);
        }

        $this->dao->insert(TABLE_TESTRESULT)->data($result)->autoCheck()->exec();

        /* Save upload files for case result or step result. */
        if(!dao::isError())
        {
            $resultID = $this->dao->lastInsertID();
            foreach($stepResults as $stepID => $stepResult) $this->loadModel('file')->saveUpload('stepResult', $resultID, $stepID, "files{$stepID}", "labels{$stepID}");
        }
        $this->dao->update(TABLE_CASE)->set('lastRunner')->eq($this->app->user->account)->set('lastRunDate')->eq($now)->set('lastRunResult')->eq($caseResult)->where('id')->eq($postData->case)->exec();

        if($runID)
        {
            /* Update testRun's status. */
            if(!dao::isError())
            {
                $runStatus = $caseResult == 'blocked' ? 'blocked' : 'normal';
                $this->dao->update(TABLE_TESTRUN)
                    ->set('lastRunResult')->eq($caseResult)
                    ->set('status')->eq($runStatus)
                    ->set('lastRunner')->eq($this->app->user->account)
                    ->set('lastRunDate')->eq($now)
                    ->where('id')->eq($runID)
                    ->exec();
            }
        }

        if(!dao::isError()) $this->loadModel('score')->create('testtask', 'runCase', $runID);

        return $caseResult;
    }

    /**
     * Batch run case
     *
     * @param  string $runCaseType
     * @access public
     * @return void
     */
    public function batchRun($runCaseType = 'testcase', $taskID = 0)
    {
        $runs = array();
        $postData   = fixer::input('post')->get();
        $caseIdList = isset($postData->caseIDList) ? array_keys($postData->caseIDList) : array_keys($postData->results);
        if($runCaseType == 'testtask')
        {
            $runs = $this->dao->select('id, `case`')->from(TABLE_TESTRUN)
                ->where('`case`')->in($caseIdList)
                ->beginIF($taskID)->andWhere('task')->eq($taskID)->fi()
                ->fetchPairs('case', 'id');
        }

        $stepGroups = $this->dao->select('t1.*')->from(TABLE_CASESTEP)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->where('t1.case')->in($caseIdList)
            ->andWhere('t1.version=t2.version')
            ->andWhere('t2.status')->ne('wait')
            ->fetchGroup('case', 'id');

        $now = helper::now();
        foreach($postData->results as $caseID => $result)
        {
            $runID       = isset($runs[$caseID]) ? $runs[$caseID] : (isset($postData->caseIDList) ? $caseID : 0);
            $version     = $postData->version[$caseID];
            $dbSteps     = isset($stepGroups[$caseID]) ? $stepGroups[$caseID] : array();
            $postSteps   = isset($postData->steps[$caseID]) ? $postData->steps[$caseID] : array();
            $postReals   = $postData->reals[$caseID];

            $caseResult  = $result ? $result : 'pass';
            if(!empty($postData->node)) $caseResult = '';
            $stepResults = array();
            if($dbSteps)
            {
                foreach($dbSteps as $stepID => $step)
                {
                    $step           = array();
                    $step['result'] = $caseResult == 'pass' ? $caseResult : $postSteps[$stepID];
                    $step['real']   = $caseResult == 'pass' ? '' : $postReals[$stepID];
                    $stepResults[$stepID] = $step;
                }
            }
            else
            {
                $step           = array();
                $step['result'] = $caseResult;
                $step['real']   = $caseResult == 'pass' ? '' : $postReals[0];
                $stepResults[]  = $step;
            }

            /* Replace caseID if caseID is runID. */
            if(isset($postData->caseIDList[$caseID])) $caseID = $postData->caseIDList[$caseID];

            $result              = new stdClass();
            $result->run         = $runID;
            $result->case        = $caseID;
            $result->version     = $version;
            $result->caseResult  = $caseResult;
            $result->stepResults = serialize($stepResults);
            $result->lastRunner  = $this->app->user->account;
            $result->date        = $now;

            $this->dao->insert(TABLE_TESTRESULT)->data($result)->autoCheck()->exec();
            $this->dao->update(TABLE_CASE)->set('lastRunner')->eq($this->app->user->account)->set('lastRunDate')->eq($now)->set('lastRunResult')->eq($caseResult)->where('id')->eq($caseID)->exec();

            if($runID)
            {
                /* Update testRun's status. */
                if(!dao::isError())
                {
                    $runStatus = $caseResult == 'blocked' ? 'blocked' : 'normal';
                    $this->dao->update(TABLE_TESTRUN)
                        ->set('lastRunResult')->eq($caseResult)
                        ->set('status')->eq($runStatus)
                        ->set('lastRunner')->eq($this->app->user->account)
                        ->set('lastRunDate')->eq($now)
                        ->where('id')->eq($runID)
                        ->exec();
                }
            }
        }
    }

    /**
     * Get results by runID or caseID
     *
     * @param  int    $runID
     * @param  int    $caseID
     * @param  string $status all|done
     * @access public
     * @return void
     */
    public function getResults($runID, $caseID = 0, $status = 'all')
    {
        if($runID > 0)
        {
            $results = $this->dao->select('*')->from(TABLE_TESTRESULT)
                ->where('run')->eq($runID)
                ->beginIF($status == 'done')->andWhere('caseResult')->ne('')->fi()
                ->orderBy('id desc')
                ->fetchAll('id');
        }
        else
        {
            $results = $this->dao->select('*')->from(TABLE_TESTRESULT)
                ->where('`case`')->eq($caseID)
                ->beginIF($status == 'done')->andWhere('caseResult')->ne('')->fi()
                ->orderBy('id desc')
                ->fetchAll('id');
        }

        if(!$results) return array();

        $relatedVersions = array();
        $runIdList       = array();
        $nodeIdList      = array();
        foreach($results as $result)
        {
            $runIdList[$result->run] = $result->run;
            $relatedVersions[]       = $result->version;
            $runCaseID               = $result->case;
            if(!empty($result->node)) $nodeIdList[] = $result->node;
        }
        $relatedVersions = array_unique($relatedVersions);

        $relatedSteps = $this->dao->select('*')->from(TABLE_CASESTEP)
            ->where('`case`')->eq($runCaseID)
            ->andWhere('version')->in($relatedVersions)
            ->orderBy('id')
            ->fetchGroup('version', 'id');
        $runs = $this->dao->select('t1.id,t2.build')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_TESTTASK)->alias('t2')->on('t1.task=t2.id')
            ->where('t1.id')->in($runIdList)
            ->fetchPairs();
        $nodes = $this->dao->select('id,name')->from(TABLE_ZAHOST)
            ->where('id')->in(array_unique($nodeIdList))
            ->fetchPairs();

        $this->loadModel('file');
        $files = $this->dao->select('*')->from(TABLE_FILE)
            ->where("(objectType = 'caseResult' or objectType = 'stepResult')")
            ->andWhere('objectID')->in(array_keys($results))
            ->andWhere('extra')->ne('editor')
            ->orderBy('id')
            ->fetchAll();
        $resultFiles = array();
        $stepFiles   = array();
        foreach($files as $file)
        {
            $this->file->setFileWebAndRealPaths($file);
            if($file->objectType == 'caseResult')
            {
                $resultFiles[$file->objectID][$file->id] = $file;
            }
            elseif($file->objectType == 'stepResult' and $file->extra !== '')
            {
                $stepFiles[$file->objectID][(int)$file->extra][$file->id] = $file;
            }
        }
        foreach($results as $resultID => $result)
        {
            $result->stepResults = unserialize($result->stepResults);
            $result->build       = $result->run ? zget($runs, $result->run, 0) : 0;
            $result->nodeName    = $result->node ? zget($nodes, $result->node, '') : '';

            if(!empty($result->ZTFResult))
            {
                $result->ZTFResult = $this->formatZtfLog($result->ZTFResult, $result->stepResults);
            }

            $result->files = zget($resultFiles, $resultID, array()); //Get files of case result.
            if(isset($relatedSteps[$result->version]))
            {
                $relatedStep = $relatedSteps[$result->version];
                foreach($relatedStep as $stepID => $step)
                {
                    $relatedStep[$stepID] = (array)$step;
                    $relatedStep[$stepID]['desc']   = html_entity_decode($relatedStep[$stepID]['desc']);
                    $relatedStep[$stepID]['expect'] = html_entity_decode($relatedStep[$stepID]['expect']);
                    if(isset($result->stepResults[$stepID]))
                    {
                        $relatedStep[$stepID]['result'] = $result->stepResults[$stepID]['result'];
                        $relatedStep[$stepID]['real']   = $result->stepResults[$stepID]['real'];
                    }
                }
                $result->stepResults = $relatedStep;
            }

            /* Get files of step result. */
            if(!empty($result->stepResults)) foreach($result->stepResults as $stepID => $stepResult) $result->stepResults[$stepID]['files'] = isset($stepFiles[$resultID][$stepID]) ? $stepFiles[$resultID][$stepID] : array();
        }
        return $results;
    }

    /**
     * Format ztf log.
     *
     * @param  string $log
     * @access public
     * @return string
     */
    public function formatZtfLog($result, $stepResults)
    {
        $logObj  = json_decode($result);
        $logs    = empty($logObj->log) ? '' : $logObj->log;
        if(empty($logs)) return '';

        $logs     = str_replace(array("\r", "\n", "\r\n"), "\n", $logs);
        $logList  = explode("\n", $logs);
        $logHtml  = "";

        foreach($logList as $log)
        {
            $log = preg_replace("/^[\d\-:.\x20]+/", '', $log);
            $log = trim($log);
            if(empty($log)) continue;

            $failHtml = ': <span class="result-testcase fail">' . $this->lang->testtask->fail . '</span>';
            $passHtml = ': <span class="result-testcase pass">' . $this->lang->testtask->pass . '</span>';

            $log = preg_replace(array("/:\x20失败/", "/:\x20fail/", "/:\x20成功/", "/:\x20pass/"), array($failHtml, $failHtml, $passHtml, $passHtml), $log);

            $logHtml .= "<li>" . $log . "</li>";
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
            $logHtml .= "<li class='result-testcase {$caseResult}'>"
                        . sprintf($this->lang->testtask->stepSummary, $total, $passCount, $failCount)
                        . "</li>";
        }

        return $logHtml;
    }

    /**
     * Judge an action is clickable or not.
     *
     * @param  object $product
     * @param  string $action
     * @access public
     * @return void
     */
    public static function isClickable($testtask, $action)
    {
        $action = strtolower($action);

        if($action == 'start')    return $testtask->status  == 'wait';
        if($action == 'block')    return ($testtask->status == 'doing'   || $testtask->status == 'wait');
        if($action == 'activate') return ($testtask->status == 'blocked' || $testtask->status == 'done');
        if($action == 'close')    return $testtask->status != 'done';
        if($action == 'runcase' and isset($testtask->auto) and $testtask->auto == 'unit')  return false;

        if($action == 'runcase')
        {
            if(isset($testtask->caseStatus)) return $testtask->version < $testtask->caseVersion ? $testtask->caseStatus == 'wait' : $testtask->caseStatus != 'wait';
            return $testtask->status != 'wait';
        }

        return true;
    }

    /**
     * Print rows of cases.
     *
     * @param  array  $cases
     * @param  array  $setting
     * @param  array  $users
     * @param  array  $branchOption
     * @param  array  $modulePairs
     * @param  string $browseType
     * @param  string $mode
     * @access public
     * @return int
     */
    public function printRow($cases, $setting, $users, $task, $branchOption, $modulePairs, $browseType, $mode)
    {
        foreach($cases as $case)
        {
            $trClass = '';
            $trAttrs = "data-id='{$case->id}' data-auto='" . zget($case, 'auto', '') . "' data-order='{$case->sort}' data-parent='{$case->parent}' data-product='{$case->product}'";
            if($case->isScene)
            {
                $trAttrs .= " data-nested='true'";
                $trClass .= $case->parent == '0' ? ' is-top-level table-nest-child-hide' : ' table-nest-hide';
            }

            if($case->parent)
            {
                if(!$case->isScene) $trClass .= ' is-nest-child';
                $trClass .= ' table-nest-hide';
                $trAttrs .= " data-nest-parent='{$case->parent}' data-nest-path='{$case->path}'";
            }
            elseif(!$case->isScene)
            {
                $trClass .= ' no-nest';
            }
            $trAttrs .= " class='row-case $trClass'";

            $case->id = str_replace(array('case_', 'scene_'), '', $case->id);   // Remove the prefix of case id.

            $isScene = $case->isScene ? 1 : 0;
            echo "<tr data-is-scene='{$isScene}' {$trAttrs}>";
            foreach($setting as $key => $value) $this->printCell($value, $case, $users, $task, $branchOption, $modulePairs, $mode);
            echo '</tr>';

            if(!empty($case->children) || !empty($case->cases))
            {
                if(!empty($case->children)) $this->printRow($case->children, $setting, $users, $task, $branchOption, $modulePairs, $browseType, $mode);
                if(!empty($case->cases))    $this->printRow($case->cases,    $setting, $users, $task, $branchOption, $modulePairs, $browseType, $mode);
            }
        }
    }

    /**
     * Print cell data.
     *
     * @param mixed $col
     * @param mixed $run
     * @param mixed $users
     * @param mixed $task
     * @param mixed $branches
     * @param mixed $modulePairs
     * @param string $mode
     * @access public
     * @return void
     */
    public function printCell($col, $run, $users, $task, $branches, $modulePairs, $mode = 'datatable')
    {
        $isScene        = $run->isScene;
        $canBatchEdit   = common::hasPriv('testcase', 'batchEdit');
        $canBatchUnlink = common::hasPriv('testtask', 'batchUnlinkCases');
        $canBatchAssign = common::hasPriv('testtask', 'batchAssign');
        $canBatchRun    = common::hasPriv('testtask', 'batchRun');

        $canBatchAction = ($canBatchEdit or $canBatchUnlink or $canBatchAssign or $canBatchRun);

        $canView     = common::hasPriv('testcase', 'view');
        $caseLink    = helper::createLink('testcase', 'view', "caseID=$run->id&version=$run->version&from=testtask&taskID=$task->id");
        $account     = $this->app->user->account;
        $id          = $col->id;

        $run->caseVersion = isset($run->caseVersion) ? $run->caseVersion : 1;
        $run->assignedTo  = isset($run->assignedTo) ? $run->assignedTo : '';
        $caseChanged = $run->version < $run->caseVersion;
        $fromCaseID  = $run->fromCaseID;

        if($col->show)
        {
            $class = "c-$id ";
            $title = '';
            if($id == 'status') $class .= "{$run->status} status-testcase status-{$run->caseStatus}";
            if($id == 'title')
            {
                $class .= ' text-left';
                $title  = "title='{$run->title}'";
            }
            if($id == 'id')     $class .= ' cell-id';
            if($id == 'lastRunResult') $class .= "result-testcase $run->lastRunResult";
            if($id == 'assignedTo' && $run->assignedTo == $account) $class .= ' red';
            if($id == 'actions') $class .= 'c-actions';

            if($id == 'title')
            {
                if($isScene)
                {
                    echo "<td class='c-name table-nest-title text-left sort-handler has-prefix has-suffix' {$title}><span class='table-nest-icon icon '></span>";
                }
                else
                {
                    $icon = $run->auto == 'auto' ? 'icon-ztf' : 'icon-test';
                    echo "<td class='c-name table-nest-title text-left sort-handler has-prefix has-suffix' {$title}><span class='table-nest-icon icon {$icon}'></span>";
                }
            }
            else
            {
                echo "<td class='" . $class . "'" . ($id=='title' ? "title='{$run->title}'":'') . ">";
            }

            if($this->config->edition != 'open') $this->loadModel('flow')->printFlowCell('testcase', $run, $id);
            switch ($id)
            {
            case 'id':
                $showID = sprintf('%03d', $run->id);
                if($canBatchAction)
                {
                    if(!$isScene)
                    {
                        echo html::checkbox('caseIDList', array($run->id => ''), '') . html::a(helper::createLink('testcase', 'view', "caseID=$run->id"), $showID, '', "data-app='{$this->app->tab}'");
                    }
                    else
                    {
                        echo html::checkbox('sceneIDList', array($run->id => ''), '');
                    }
                }
                else
                {
                    echo $showID;
                }
                break;
            case 'pri':
                echo "<span class='label-pri label-pri-" . $run->pri . "' title='" . zget($this->lang->testcase->priList, $run->pri, $run->pri) . "'>";
                echo zget($this->lang->testcase->priList, $run->pri, $run->pri);
                echo "</span>";
                break;
            case 'title':
                if(!empty($branches)) echo "<span class='label label-badge label-outline'>{$branches[$run->branch]}</span> ";
                if($modulePairs and $run->module and isset($modulePairs[$run->module])) echo "<span class='label label-gray label-badge'>{$modulePairs[$run->module]}</span> ";
                if($canView and !$isScene)
                {
                    if($fromCaseID)
                    {
                        echo html::a($caseLink, $run->title, null, "style='color: $run->color'") . html::a(helper::createLink('testcase', 'view', "caseID=$fromCaseID"), "[<i class='icon icon-share' title='{$this->lang->testcase->fromCase}'></i>#$fromCaseID]");
                    }
                    else
                    {
                        echo html::a($caseLink, $run->title, null, "style='color: $run->color'");
                    }
                }
                else
                {
                    echo "<span style='color: $run->color'>$run->title</span>";
                }
                break;
            case 'branch':
                echo $branches[$run->branch];
                break;
            case 'type':
                echo $this->lang->testcase->typeList[$run->type];
                break;
            case 'stage':
                foreach(explode(',', trim($run->stage, ',')) as $stage) echo $this->lang->testcase->stageList[$stage] . '<br />';
                break;
            case 'status':
                if($run->caseStatus != 'wait' and $caseChanged)
                {
                    echo "<span title='{$this->lang->testcase->changed}' class='warning'>{$this->lang->testcase->changed}</span>";
                }
                else
                {
                    $case = new stdClass();
                    $case->status = $run->caseStatus;

                    $status = $this->processStatus('testcase', $case);
                    if($run->status == $status) $status = $this->processStatus('testtask', $run);
                    echo $status;
                }
                break;
            case 'precondition':
                echo $run->precondition;
                break;
            case 'keywords':
                echo $run->keywords;
                break;
            case 'version':
                echo $run->version;
                break;
            case 'openedBy':
                echo zget($users, $run->openedBy);
                break;
            case 'openedDate':
                echo substr($run->openedDate, 5, 11);
                break;
            case 'reviewedBy':
                echo zget($users, $run->reviewedBy);
                break;
            case 'reviewedDate':
                echo substr($run->reviewedDate, 5, 11);
                break;
            case 'lastEditedBy':
                echo zget($users, $run->lastEditedBy);
                break;
            case 'lastEditedDate':
                echo substr($run->lastEditedDate, 5, 11);
                break;
            case 'lastRunner':
                echo zget($users, $run->lastRunner);
                break;
            case 'lastRunDate':
                echo helper::isZeroDate($run->lastRunDate) ? '' : substr($run->lastRunDate, 5, 11);
                break;
            case 'lastRunResult':
                $lastRunResultText = $run->lastRunResult ? zget($this->lang->testcase->resultList, $run->lastRunResult, $run->lastRunResult) : $this->lang->testcase->unexecuted;
                echo $lastRunResultText;
                break;
            case 'story':
                if($run->story and $run->storyTitle) echo html::a(helper::createLink('story', 'view', "storyID=$run->story"), $run->storyTitle);
                break;
            case 'assignedTo':
                $btnTextClass = '';
                if($run->assignedTo == $this->app->user->account) $btnTextClass = 'assigned-current';
                if(!empty($run->assignedTo) and $run->assignedTo != $this->app->user->account) $btnTextClass = 'assigned-other';
                echo "<span class='$btnTextClass'>" . zget($users, $run->assignedTo) . '</span>';
                break;
            case 'bugs':
                echo (common::hasPriv('testcase', 'bugs') and $run->bugs) ? html::a(helper::createLink('testcase', 'bugs', "runID={$run->run}&caseID={$run->case}"), $run->bugs, '', "class='iframe'") : $run->bugs;
                break;
            case 'results':
                echo (common::hasPriv('testtask', 'results') and $run->results) ? html::a(helper::createLink('testtask', 'results', "runID={$run->run}&caseID={$run->case}"), $run->results, '', "class='iframe'") : $run->results;
                break;
            case 'stepNumber':
                echo $run->stepNumber;
                break;
            case 'actions':
                if($isScene) break;
                if($run->caseStatus != 'wait' and $caseChanged)
                {
                    common::printIcon('testcase', 'confirmChange', "id=$run->case&taskID=$run->task&from=list", $run, 'list', 'search', 'hiddenwin');
                    break;
                }

                common::printIcon('testcase', 'createBug', "product=$run->product&branch=$run->branch&extra=executionID=$task->execution,buildID=$task->build,caseID=$run->case,version=$run->version,runID=$run->run,testtask=$task->id", $run, 'list', 'bug', '', 'iframe', '', "data-width='90%'");

                common::printIcon('testtask', 'runCase', "id=$run->run", $run, 'list', 'play', '', 'runCase iframe', false, "data-width='95%'");
                common::printIcon('testtask', 'results', "id=$run->run", $run, 'list', '', '', 'iframe', '', "data-width='90%'");

                if(common::hasPriv('testtask', 'unlinkCase', $run))
                {
                    $unlinkURL = helper::createLink('testtask', 'unlinkCase', "caseID=$run->run&confirm=yes");
                    echo html::a("javascript:void(0)", '<i class="icon-unlink"></i>', '', "title='{$this->lang->testtask->unlinkCase}' class='btn' onclick='ajaxDelete(\"$unlinkURL\", \"casesForm\", confirmUnlink)'");
                }

                break;
            }
            echo '</td>';
        }
    }

    /**
     * Get toList and ccList.
     *
     * @param  object    $testtask
     * @access public
     * @return bool|array
     */
    public function getToAndCcList($testtask)
    {
        /* Set toList and ccList. */
        $toList   = $testtask->owner . ',' . $testtask->members . ',';
        $ccList   = str_replace(' ', '', trim($testtask->mailto, ','));

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
     * Import unit results.
     *
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function importUnitResult($productID)
    {
        $file = $this->loadModel('file')->getUpload('resultFile');
        if(empty($file))
        {
            dao::$errors[] = $this->lang->testtask->unitXMLFormat;
            return false;
        }

        $file     = $file[0];
        $fileName = $this->file->savePath . $this->file->getSaveName($file['pathname']);
        move_uploaded_file($file['tmpname'], $fileName);
        if(simplexml_load_file($fileName) === false)
        {
            dao::$errors[] = $this->lang->testtask->cannotBeParsed;
            return false;
        }

        $frame = $this->post->frame;
        unset($_POST['frame']);

        $data     = $this->parseXMLResult($fileName, $productID, $frame);
        if($frame == 'cppunit' and empty($data['cases'])) $data = $this->parseCppXMLResult($fileName, $productID, $frame);

        /* Create task. */
        $this->post->set('auto', 'unit');
        $testtaskID = $this->create();

        unlink($fileName);
        unset($_SESSION['resultFile']);
        if(dao::isError()) return false;

        return $this->processAutoResult($testtaskID, $productID, $data['suites'], $data['cases'], $data['results'], $data['suiteNames'], $data['caseTitles'], 'unit');
    }

    /**
     * Process auto test result.
     *
     * @param  int    $testtaskID
     * @param  int    $productID
     * @param  array  $suites
     * @param  array  $cases
     * @param  array  $results
     * @param  array  $suiteNames
     * @param  array  $caseTitles
     * @param  string $auto     unit|func
     * @access public
     * @return int
     */
    public function processAutoResult($testtaskID, $productID, $suites, $cases, $results, $suiteNames = array(), $caseTitles = array(), $auto = 'unit')
    {
        if(empty($cases)) return print(js::alert($this->lang->testtask->noImportData));

        /* Import cases and link task and insert result. */
        $this->loadModel('action');
        $existSuites = $this->dao->select('*')->from(TABLE_TESTSUITE)->where('name')->in($suiteNames)->andWhere('product')->eq($productID)->andWhere('type')->eq($auto)->andWhere('deleted')->eq(0)->fetchPairs('name', 'id');
        foreach($suites as $suiteIndex => $suite)
        {
            $suiteID = 0;
            if($suite)
            {
                if(!isset($existSuites[$suite->name]))
                {
                    $this->dao->insert(TABLE_TESTSUITE)->data($suite)->exec();
                    $suiteID = $this->dao->lastInsertID();
                    $this->action->create('testsuite', $suiteID, 'opened');
                }
                else
                {
                    $suiteID = $existSuites[$suite->name];
                }
            }

            if($suiteID)
            {
                $existCases = $this->dao->select('t1.*')->from(TABLE_CASE)->alias('t1')
                    ->leftJoin(TABLE_SUITECASE)->alias('t2')->on('t1.id=t2.case')
                    ->where('t1.title')->in($caseTitles[$suiteIndex])
                    ->andWhere('t1.product')->eq($productID)
                    ->beginIF($auto == 'unit')->andWhere('t1.auto')->eq($auto)->fi()
                    ->andWhere('t1.deleted')->eq(0)
                    ->orderBy('id')
                    ->fetchPairs('title', 'id');
            }
            else
            {
                $existCases = $this->dao->select('*')->from(TABLE_CASE)
                    ->where('title')->in($caseTitles[$suiteIndex])
                    ->beginIF($auto == 'unit')->andWhere('auto')->eq($auto)->fi()
                    ->andWhere('product')->eq($productID)
                    ->andWhere('deleted')->eq(0)
                    ->orderBy('id')
                    ->fetchPairs('title', 'id');
            }

            foreach($cases[$suiteIndex] as $i => $case)
            {
                if(isset($case->id))
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
                }
                elseif(!isset($existCases[$case->title]))
                {
                    $this->dao->insert(TABLE_CASE)->data($case, 'steps')->exec();
                    $caseID      = $this->dao->lastInsertID();
                    $case->steps = isset($case->steps) ? $case->steps : array();
                    foreach($case->steps as $caseStep)
                    {
                        $caseStep->case    = $caseID;
                        $caseStep->version = 1;
                        $this->dao->insert(TABLE_CASESTEP)->data($caseStep)->exec();
                    }
                    $this->action->create('case', $caseID, 'Opened');
                }
                else
                {
                    $caseID = $existCases[$case->title];
                }

                $testrun = new stdclass();
                $testrun->task          = $testtaskID;
                $testrun->case          = $caseID;
                $testrun->version       = $case->version;
                $testrun->lastRunner    = $case->lastRunner;
                $testrun->lastRunDate   = $case->lastRunDate;
                $testrun->lastRunResult = $case->lastRunResult;
                $testrun->status        = 'done';

                $this->dao->replace(TABLE_TESTRUN)->data($testrun)->exec();
                $runID = $this->dao->lastInsertID();

                if($suiteID)
                {
                    $suitecase = new stdclass();
                    $suitecase->suite   = $suiteID;
                    $suitecase->case    = $caseID;
                    $suitecase->version = $case->version;
                    $suitecase->product = $case->product;
                    $this->dao->replace(TABLE_SUITECASE)->data($suitecase)->exec();
                }

                $testresult = $results[$suiteIndex][$i];
                $testresult->run  = $runID;
                $testresult->case = $caseID;
                $this->dao->insert(TABLE_TESTRESULT)->data($testresult)->exec();
            }
        }

        return $testtaskID;
    }

    /**
     * Parse cppunit XML result.
     *
     * @param  string $fileName
     * @param  int    $productID
     * @param  string $frame
     * @access public
     * @return array
     */
    public function parseCppXMLResult($fileName, $productID, $frame)
    {
        /* Parse result xml. */
        $parsedXML = simplexml_load_file($fileName);

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
        $suiteNames = array();
        $suiteIndex = 0;
        $suites     = array($suiteIndex => '');
        foreach($matchNodes as $caseIndex => $matchNode)
        {
            $case = new stdclass();
            $case->product    = $productID;
            $case->title      = (string)$matchNode->Name;
            $case->pri        = 3;
            $case->type       = 'unit';
            $case->stage      = 'unittest';
            $case->status     = 'normal';
            $case->openedBy   = $this->app->user->account;
            $case->openedDate = $now;
            $case->version    = 1;
            $case->auto       = 'unit';
            $case->frame      = $frame ? $frame : 'junit';

            $result = new stdclass();
            $result->case       = 0;
            $result->version    = 1;
            $result->caseResult = 'pass';
            $result->lastRunner = $this->app->user->account;
            $result->date       = $now;
            $result->duration   = 0;
            $result->xml        = $matchNode->asXML();
            $result->stepResults[0]['result'] = 'pass';
            $result->stepResults[0]['real']   = '';
            if(isset($matchNode->Message))
            {
                $result->caseResult = 'fail';
                $result->stepResults[0]['result'] = 'fail';
                $result->stepResults[0]['real']   = (string)$matchNode->Message;
            }
            $result->stepResults = serialize($result->stepResults);
            $case->lastRunner    = $this->app->user->account;
            $case->lastRunDate   = $now;
            $case->lastRunResult = $result->caseResult;

            $caseTitles[$suiteIndex][]        = $case->title;
            $cases[$suiteIndex][$caseIndex]   = $case;
            $results[$suiteIndex][$caseIndex] = $result;
        }

        return array('suites' => $suites, 'cases' => $cases, 'results' => $results, 'suiteNames' => $suiteNames, 'caseTitles' => $caseTitles);
    }

    /**
     * Parse unit result from xml.
     *
     * @param  string $fileName
     * @param  int    $productID
     * @param  string $frame
     * @access public
     * @return array
     */
    public function parseXMLResult($fileName, $productID, $frame)
    {
        /* Parse result xml. */
        $rules     = zget($this->config->testtask->unitResultRules, $frame, $this->config->testtask->unitResultRules->common);
        $parsedXML = simplexml_load_file($fileName);

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
                $suite = new stdclass();
                $suite->product   = $productID;
                $suite->name      = (string)$attributes[$suiteField];
                $suite->type      = 'unit';
                $suite->addedBy   = $this->app->user->account;
                $suite->addedDate = $now;
                $suiteNames[]     = $suite->name;
            }
            else
            {
                $attributes = $caseNodes[0]->attributes();
                foreach($aliasSuite as $alias)
                {
                    if(isset($attributes[$alias]))
                    {
                        $suite = new stdclass();
                        $suite->product   = $productID;
                        $suite->name      = (string)$attributes[$alias];
                        $suite->type      = 'unit';
                        $suite->addedBy   = $this->app->user->account;
                        $suite->addedDate = $now;
                        $suiteNames[]     = $suite->name;
                        break;
                    }
                }
            }
            $suites[$suiteIndex] = $suite;

            foreach($caseNodes as $caseIndex => $matchNode)
            {
                $case = new stdclass();
                $case->product    = $productID;
                $case->title      = '';
                $case->pri        = 3;
                $case->type       = 'unit';
                $case->stage      = 'unittest';
                $case->status     = 'normal';
                $case->openedBy   = $this->app->user->account;
                $case->openedDate = $now;
                $case->version    = 1;
                $case->auto       = 'unit';
                $case->frame      = $frame ? $frame : 'junit';

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

                $result = new stdclass();
                $result->case       = 0;
                $result->version    = 1;
                $result->caseResult = 'pass';
                $result->lastRunner = $this->app->user->account;
                $result->date       = $now;
                $result->duration   = isset($attributes['time']) ? (float)$attributes['time'] : 0;
                $result->xml        = $matchNode->asXML();
                $result->stepResults[0]['result'] = 'pass';
                $result->stepResults[0]['real']   = '';
                if(isset($matchNode->$failure))
                {
                    $result->caseResult = 'fail';
                    $result->stepResults[0]['result'] = 'fail';
                    if(is_string($matchNode->$failure))
                    {
                        $result->stepResults[0]['real'] = (string)$matchNode->$failure;
                    }
                    elseif(isset($matchNode->{$failure}[0]))
                    {
                        $result->stepResults[0]['real'] = (string)$matchNode->{$failure}[0];
                    }
                    else
                    {
                        $failureAttrs = $matchNode->$failure->attributes();
                        $result->stepResults[0]['real'] = (string)$failureAttrs['message'];
                    }
                }
                elseif(isset($matchNode->$skipped))
                {
                    $result->caseResult = 'n/a';
                    $result->stepResults[0]['result'] = 'n/a';
                    $result->stepResults[0]['real']   = '';
                }
                $result->stepResults = serialize($result->stepResults);
                $case->lastRunner    = $this->app->user->account;
                $case->lastRunDate   = $now;
                $case->lastRunResult = $result->caseResult;

                $caseTitles[$suiteIndex][]        = $case->title;
                $cases[$suiteIndex][$caseIndex]   = $case;
                $results[$suiteIndex][$caseIndex] = $result;
            }
        }

        return array('suites' => $suites, 'cases' => $cases, 'results' => $results, 'suiteNames' => $suiteNames, 'caseTitles' => $caseTitles);
    }

    /**
     * Parse unit result from ztf.
     *
     * @param  array  $caseResults
     * @param  string $frame
     * @param  int    $productID
     * @param  int    $jobID
     * @param  int    $compileID
     * @access public
     * @return array
     */
    public function parseZTFUnitResult($caseResults, $frame, $productID, $jobID, $compileID)
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
                $suite = new stdclass();
                $suite->product   = $productID;
                $suite->name      = $caseResult->testSuite;
                $suite->type      = 'unit';
                $suite->addedBy   = $this->app->user->account;
                $suite->addedDate = $now;

                $suiteNames[$suite->name] = $suite->name;
                $suiteIndex ++;
            }
            if(!isset($suites[$suiteIndex])) $suites[$suiteIndex] = $suite;

            $case = new stdclass();
            if(!empty($caseResult->id)) $case->id = $caseResult->id;
            $case->status = 'normal';
            $case->frame  = $frame;

            if(empty($caseResult->id))
            {
                $case->type       = 'unit';
                $case->stage      = 'unittest';
                $case->product    = $productID;
                $case->title      = $caseResult->title;
                $case->pri        = 3;
                $case->openedBy   = $this->app->user->account;
                $case->openedDate = $now;
                $case->version    = 1;
                $case->auto       = 'unit';
            }

            $result = new stdclass();
            $result->case       = 0;
            $result->version    = 1;
            $result->caseResult = 'pass';
            $result->lastRunner = $this->app->user->account;
            $result->job        = $jobID;
            $result->compile    = $compileID;
            $result->date       = $now;
            $result->duration   = zget($caseResult, 'duration', 0);
            $result->stepResults[0]['result'] = 'pass';
            $result->stepResults[0]['real']   = '';
            if(!empty($caseResult->failure))
            {
                $result->caseResult = 'fail';
                $result->stepResults[0]['result'] = 'fail';
                $result->stepResults[0]['real']   = zget($caseResult->failure, 'desc', '');
            }
            $result->stepResults = serialize($result->stepResults);
            $case->lastRunner    = $this->app->user->account;
            $case->lastRunDate   = $now;
            $case->lastRunResult = $result->caseResult;

            $caseTitles[$suiteIndex][]        = $case->title;
            $cases[$suiteIndex][$caseIndex]   = $case;
            $results[$suiteIndex][$caseIndex] = $result;
        }

        return array('suites' => $suites, 'cases' => $cases, 'results' => $results, 'suiteNames' => $suiteNames, 'caseTitles' => $caseTitles);
    }

    /**
     * Parse function result from ztf.
     *
     * @param  array  $caseResults
     * @param  string $frame
     * @param  int    $productID
     * @param  int    $jobID
     * @param  int    $compileID
     * @access public
     * @return array
     */
    public function parseZTFFuncResult($caseResults, $frame, $productID, $jobID, $compileID)
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

            $case = new stdclass();
            if(!empty($caseResult->id)) $case->id = $caseResult->id;
            $case->title  = $caseResult->title;
            $case->frame  = $frame;
            $case->status = 'normal';
            $case->steps  = array();
            $case->auto   = 'func';
            if(empty($caseResult->id))
            {
                $case->product    = $productID;
                $case->pri        = 3;
                $case->type       = 'feature';
                $case->stage      = 'feature';
                $case->openedBy   = $this->app->user->account;
                $case->openedDate = $now;
                $case->version    = 1;
            }

            $result = new stdclass();
            $result->case       = 0;
            $result->version    = 1;
            $result->caseResult = 'pass';
            $result->lastRunner = $this->app->user->account;
            $result->job        = $jobID;
            $result->compile    = $compileID;
            $result->date       = $now;
            $result->stepResults[0]['result'] = 'pass';
            $result->stepResults[0]['real']   = '';
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

                    $case->steps[] = $caseStep;
                }
                $result->caseResult = $stepStatus;
            }
            $result->stepResults = serialize($result->stepResults);
            $case->lastRunner    = $this->app->user->account;
            $case->lastRunDate   = $now;
            $case->lastRunResult = $result->caseResult;

            $caseTitles[$suiteIndex][]        = $case->title;
            $cases[$suiteIndex][$caseIndex]   = $case;
            $results[$suiteIndex][$caseIndex] = $result;
        }

        return array('suites' => $suites, 'cases' => $cases, 'results' => $results, 'suiteNames' => $suiteNames, 'caseTitles' => $caseTitles);
    }

    /**
     * Build test task menu.
     *
     * @param  object $task
     * @param  string $type
     * @access public
     * @return string
     */
    public function buildOperateMenu($task, $type = 'view')
    {
        $function = 'buildOperate' . ucfirst($type) . 'Menu';
        return $this->$function($task);
    }

    /**
     * Build test task view menu.
     *
     * @param  object $task
     * @access public
     * @return string
     */
    public function buildOperateViewMenu($task)
    {
        if($task->deleted) return '';

        $menu   = '';
        $params = "taskID=$task->id";

        $menu .= $this->buildMenu('testtask', 'start',    $params, $task, 'view', '', '', 'iframe showinonlybody', true);
        $menu .= $this->buildMenu('testtask', 'close',    $params, $task, 'view', '', '', 'iframe showinonlybody', true);
        $menu .= $this->buildMenu('testtask', 'block',    $params, $task, 'view', 'pause', '', 'iframe showinonlybody', true);
        $menu .= $this->buildMenu('testtask', 'activate', $params, $task, 'view', 'magic', '', 'iframe showinonlybody', true);
        $menu .= $this->buildMenu('testtask', 'cases',    $params, $task, 'view', 'sitemap');
        $menu .= $this->buildMenu('testtask', 'linkCase', $params, $task, 'view', 'link');

        $menu  .= "<div class='divider'></div>";
        $menu  .= $this->buildFlowMenu('testtask', $task, 'view', 'direct');
        $menu  .= "<div class='divider'></div>";

        $menu .= $this->buildMenu('testtask', 'edit',   $params, $task, 'view');
        $menu .= $this->buildMenu('testtask', 'delete', $params, $task, 'view', 'trash', 'hiddenwin');

        return $menu;
    }

    /**
     * Build test task browse menu.
     *
     * @param  object $task
     * @access public
     * @return string
     */
    public function buildOperateBrowseMenu($task)
    {
        $menu   = '';
        $params = "taskID=$task->id";

        $menu .= '<div id="action-divider">';
        $menu .= $this->buildMenu('testtask',   'cases',    $params, $task, 'browse', 'sitemap');
        $menu .= $this->buildMenu('testtask',   'linkCase', "$params&type=all&param=myQueryID", $task, 'browse', 'link');
        $menu .= $this->buildMenu('testreport', 'browse',   "objectID=$task->product&objectType=product&extra=$task->id", $task, 'browse', 'summary', '', '', false, '', $this->lang->testreport->common);
        $menu .= '</div>';
        $menu .= $this->buildMenu('testtask',   'view',     $params, $task, 'browse', 'list-alt', '', 'iframe', true, "data-width='90%'");
        $menu .= $this->buildMenu('testtask',   'edit',     $params, $task, 'browse');
        $clickable = $this->buildMenu('testtask', 'delete', $params, $task, 'browse', '', '', '', '', '', '', false);
        if(common::hasPriv('testtask', 'delete', $task))
        {
            $deleteURL = helper::createLink('testtask', 'delete', "taskID=$task->id&confirm=yes");
            $class = 'btn';
            if(!$clickable) $class .= ' disabled';
            $menu .= html::a("javascript:ajaxDelete(\"$deleteURL\",\"taskList\",confirmDelete)", '<i class="icon-common-delete icon-trash"></i>', '', "title='{$this->lang->testtask->delete}' class='{$class}'");
        }
        return $menu;
    }

    /**
     * Set menu.
     *
     * @param  array       $products
     * @param  int         $productID
     * @param  int|string  $branch
     * @param  int         $taskID
     * @access public
     * @return void
     */
    public function setMenu($products, $productID, $branch = '', $taskID = 0)
    {
        if($this->session->branch) $branch = $this->session->branch;
        if(!$this->app->user->admin and strpos(",{$this->app->user->view->products},", ",$productID,") === false and $productID != 0 and !defined('TUTORIAL'))
        {
            $this->app->loadLang('product');
            return print(js::error($this->lang->product->accessDenied) . js::locate('back'));
        }

        $branch = ($this->cookie->preBranch !== '' and $branch === '') ? $this->cookie->preBranch : $branch;
        setcookie('preBranch', $branch, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);

        $product = $this->loadModel('product')->getById($productID);
        if($product and $product->type != 'normal') $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);

        $selectHtml = $this->product->getSwitcher($productID, $taskID, $branch);

        if($taskID and $this->app->viewType != 'mhtml')
        {
            $testtask     = $this->getById($taskID);
            $module       = $this->app->rawModule;
            $method       = $this->app->rawMethod;
            $dropMenuLink = helper::createLink('testtask', 'ajaxGetDropMenu', "productID=$productID&branch=$branch&taskID=$taskID&module=$module&method=$method");
            $selectHtml  .= "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentTesttask' title='{$testtask->name}'><span class='text'>{$testtask->name}</span> <span class='caret' style='margin-bottom: -1px'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
            $selectHtml .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
            $selectHtml .= "</div></div>";
        }

        $this->lang->switcherMenu = $selectHtml;
        common::setMenuVars('qa', $productID);
    }

    /**
     * Create the select code of testtasks.
     *
     * @param  int    $productID
     * @param  int    $testtaskID
     * @param  string $objectType execution|project
     * @param  int    $objectID
     * @access public
     * @return string
     */
    public function select($productID, $testtaskID, $objectType = '', $objectID = 0)
    {
        $output        = '';
        $currentModule = $this->app->rawModule;
        $currentMethod = $this->app->rawMethod;
        if($testtaskID and $this->app->viewType != 'mhtml')
        {
            $dropMenuLink = helper::createLink('testtask', 'ajaxGetDropMenu', "productID=$productID&branch=&taskID=$testtaskID&module=$currentModule&method=$currentMethod&objectType=$objectType&objectID=$objectID");
            $testtask     = $this->getById($testtaskID);

            $output .= "<div class='btn-group angle-btn'><div class='btn-group'><button data-toggle='dropdown' type='button' class='btn btn-limit' id='currentTesttask' title='{$testtask->name}'><span class='text'>{$testtask->name}</span> <span class='caret'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
            $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
            $output .= "</div></div>";
        }
        $output .= '</div>';
        return $output;
    }
}

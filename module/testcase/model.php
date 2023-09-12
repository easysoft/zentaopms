<?php
/**
 * The model file of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: model.php 5108 2013-07-12 01:59:04Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class testcaseModel extends model
{
    /**
     * Set menu.
     *
     * @param  array  $products
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $moduleID
     * @param  int    $suiteID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function setMenu($products, $productID, $branch = 0, $moduleID = 0, $suiteID = 0, $orderBy = 'id_desc')
    {
        $this->loadModel('qa')->setMenu($products, $productID, $branch, $moduleID, 'case');
    }

    /**
     * 创建一个用例。
     * Create a case.
     *
     * @param  object $case
     * @access public
     * @return bool|int
     */
    public function create(object $case): bool|int
    {
        /* 插入测试用例。 */
        /* Insert testcase. */
        $this->testcaseTao->doCreate($case);
        if(dao::isError()) return false;

        $caseID = $this->dao->lastInsertID();

        /* 记录动态。*/
        /* Record log. */
        $this->loadModel('action');
        $this->action->create('case', $caseID, 'Opened');
        if($case->status == 'wait') $this->action->create('case', $caseID, 'submitReview');

        /* 存储上传的文件。 */
        /* Save upload files. */
        $this->config->dangers = '';
        $this->loadModel('file')->saveUpload('testcase', $caseID, 'autoscript', 'script', 'scriptName');
        $this->loadModel('file')->saveUpload('testcase', $caseID);

        $this->loadModel('score')->create('testcase', 'create', $caseID);

        /* 插入用例步骤。 */
        /* Insert testcase steps. */
        $this->testcaseTao->insertSteps($caseID, $case->steps, $case->expects, $case->stepType);

        if(dao::isError()) return false;
        return $caseID;
    }

    /**
     * 获取模块的用例。
     * Get cases of modules.
     *
     * @param  int         $productID
     * @param  int|string  $branch
     * @param  int|array   $moduleIdList
     * @param  string      $browseType
     * @param  string      $auto   no|unit
     * @param  string      $caseType
     * @param  string      $orderBy
     * @param  object      $pager
     * @access public
     * @return array
     */
    public function getModuleCases(int $productID, int|string $branch = 0, int|array $moduleIdList = 0, string $browseType = '', string $auto = 'no', string $caseType = '', string $orderBy = 'id_desc', object $pager = null): array
    {
        $stmt = $this->dao->select('t1.*, t2.title as storyTitle, t2.deleted as storyDeleted')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id');

        if($this->app->tab == 'project') $stmt = $stmt->leftJoin(TABLE_PROJECTCASE)->alias('t3')->on('t1.id=t3.case');

        return $stmt ->where('t1.product')->eq((int)$productID)
            ->beginIF($this->app->tab == 'project')->andWhere('t3.project')->eq($this->session->project)->fi()
            ->beginIF($branch !== 'all')->andWhere('t1.branch')->eq($branch)->fi()
            ->beginIF($moduleIdList)->andWhere('t1.module')->in($moduleIdList)->fi()
            ->beginIF($browseType == 'all')->andWhere('t1.scene')->eq(0)->fi()
            ->beginIF($browseType == 'wait')->andWhere('t1.status')->eq($browseType)->fi()
            ->beginIF($auto == 'auto' || $auto == 'unit')->andWhere('t1.auto')->eq($auto)->fi()
            ->beginIF($auto != 'auto' && $auto != 'unit')->andWhere('t1.auto')->ne('unit')->fi()
            ->beginIF($caseType)->andWhere('t1.type')->eq($caseType)->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get project cases of a module.
     *
     * @param  int        $productID
     * @param  int|string $branch
     * @param  int        $moduleIdList
     * @param  string     $browseType
     * @param  string     $auto   no|unit
     * @param  string     $caseType
     * @param  string     $orderBy
     * @param  object     $pager
     * @access public
     * @return array
     */
    public function getModuleProjectCases($productID, $branch = 0, $moduleIdList = 0, $browseType = '', $auto = 'no', $caseType = '', $orderBy = 'id_desc', $pager = null)
    {
        $executions = $this->loadModel('execution')->getIdList($this->session->project);
        array_push($executions, $this->session->project);

        return $this->dao->select('distinct t1.*, t2.*, t4.title as storyTitle')->from(TABLE_PROJECTCASE)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case=t2.id')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t3')->on('t3.story=t2.story')
            ->leftJoin(TABLE_STORY)->alias('t4')->on('t3.story=t4.id')
            ->where('t1.project')->in($executions)
            ->beginIF(!empty($productID))->andWhere('t2.product')->eq((int)$productID)->fi()
            ->beginIF(!empty($productID) and $branch !== 'all')->andWhere('t2.branch')->eq($branch)->fi()
            ->beginIF($moduleIdList)->andWhere('t2.module')->in($moduleIdList)->fi()
            ->beginIF($browseType == 'all')->andWhere('t2.scene')->eq(0)->fi()
            ->beginIF($browseType == 'wait')->andWhere('t2.status')->eq($browseType)->fi()
            ->beginIF($auto == 'auto' || $auto == 'unit')->andWhere('t1.auto')->eq($auto)->fi()
            ->beginIF($auto != 'auto' && $auto != 'unit')->andWhere('t1.auto')->ne('unit')->fi()
            ->beginIF($caseType)->andWhere('t2.type')->eq($caseType)->fi()
            ->andWhere('t2.deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager, 't1.`case`')
            ->fetchAll('id');
    }

    /**
     * Get project cases.
     *
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $browseType
     * @access public
     * @return array
     */
    public function getProjectCases($projectID, $orderBy = 'id_desc', $pager = null, $browseType = '')
    {
        return $this->dao->select('distinct t1.*, t2.*')->from(TABLE_PROJECTCASE)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case=t2.id')
            ->where('t1.project')->eq((int)$projectID)
            ->beginIF($browseType != 'all')->andWhere('t2.status')->eq($browseType)->fi()
            ->andWhere('t2.deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get execution cases.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  int    $branchID
     * @param  int    $moduleID
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $browseType   all|wait|needconfirm
     * @access public
     * @return array
     */
    public function getExecutionCases($executionID, $productID = 0, $branchID = 0, $moduleID = 0, $orderBy = 'id_desc', $pager = null, $browseType = '')
    {
        if($browseType == 'needconfirm')
        {
            return $this->dao->select('distinct t1.*, t2.*')->from(TABLE_PROJECTCASE)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case=t2.id')
                ->leftJoin(TABLE_STORY)->alias('t3')->on('t2.story = t3.id')
                ->leftJoin(TABLE_MODULE)->alias('t4')->on('t2.module=t4.id')
                ->where('t1.project')->eq((int)$executionID)
                ->beginIF(!empty($productID))->andWhere('t1.product')->eq($productID)->fi()
                ->beginIF(!empty($moduleID))->andWhere('t4.path')->like("%,$moduleID,%")->fi()
                ->beginIF(!empty($productID) and $branchID !== 'all')->andWhere('t2.branch')->eq($branchID)->fi()
                ->andWhere('t2.deleted')->eq('0')
                ->andWhere('t3.version > t2.storyVersion')
                ->andWhere("t3.status")->eq('active')
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }

        return $this->dao->select('distinct t1.*, t2.*')->from(TABLE_PROJECTCASE)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case=t2.id')
            ->leftJoin(TABLE_MODULE)->alias('t3')->on('t2.module=t3.id')
            ->where('t1.project')->eq((int)$executionID)
            ->beginIF($browseType != 'all' and $browseType != 'byModule')->andWhere('t2.status')->eq($browseType)->fi()
            ->beginIF(!empty($productID))->andWhere('t1.product')->eq($productID)->fi()
            ->beginIF(!empty($moduleID))->andWhere('t3.path')->like("%,$moduleID,%")->fi()
            ->beginIF(!empty($productID) and $branchID !== 'all')->andWhere('t2.branch')->eq($branchID)->fi()
            ->andWhere('t2.deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get cases by suite.
     *
     * @param  int         $productID
     * @param  int|string  $branch
     * @param  int         $suiteID
     * @param  array       $moduleIdList
     * @param  string      $orderBy
     * @param  object      $pager
     * @param  string      $auto    no|unit
     * @access public
     * @return array
     */
    public function getBySuite($productID, $branch = 0, $suiteID = 0, $moduleIdList = 0, $orderBy = 'id_desc', $pager = null, $auto = 'no')
    {
        return $this->dao->select('t1.*, t2.title as storyTitle, t3.version as version')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
            ->leftJoin(TABLE_SUITECASE)->alias('t3')->on('t1.id=t3.case')
            ->where('t1.product')->eq((int)$productID)
            ->beginIF($this->app->tab == 'project')->andWhere('t1.project')->eq($this->session->project)->fi()
            ->andWhere('t3.suite')->eq((int)$suiteID)
            ->beginIF($branch !== 'all')->andWhere('t1.branch')->eq($branch)->fi()
            ->beginIF($moduleIdList)->andWhere('t1.module')->in($moduleIdList)->fi()
            ->beginIF($auto == 'auto' || $auto == 'unit')->andWhere('t1.auto')->eq($auto)->fi()
            ->beginIF($auto != 'auto' && $auto != 'unit')->andWhere('t1.auto')->ne('unit')->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy($orderBy)->page($pager)->fetchAll('id');
    }

    /**
     * Get cases by type.
     *
     * @param  int         $productID
     * @param  int|string  $branch
     * @param  int         $suiteID
     * @param  array       $moduleIdList
     * @param  string      $orderBy
     * @param  object      $pager
     * @param  string      $auto    no|unit
     * @access public
     * @return array
     */
    public function getByType($productID, $branch = 0, $type = '', $moduleIdList = 0, $orderBy = 'id_desc', $pager = null, $auto = 'no')
    {
        return $this->dao->select('t1.*, t2.title as storyTitle')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
            ->where('t1.product')->eq((int)$productID)
            ->beginIF($this->app->tab == 'project')->andWhere('t1.project')->eq($this->session->project)->fi()
            ->beginIF($type)->andWhere('t1.type')->eq($type)->fi()
            ->beginIF($branch !== 'all')->andWhere('t1.branch')->eq($branch)->fi()
            ->beginIF($moduleIdList)->andWhere('t1.module')->in($moduleIdList)->fi()
            ->beginIF($auto == 'unit')->andWhere('t1.auto')->eq('unit')->fi()
            ->beginIF($auto != 'unit')->andWhere('t1.auto')->ne('unit')->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy($orderBy)->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get case info by ID.
     *
     * @param  int    $caseID
     * @param  int    $version
     * @access public
     * @return object|bool
     */
    public function getById(int $caseID, int $version = 0): object|bool
    {
        $case = $this->dao->findById($caseID)->from(TABLE_CASE)->fetch();
        if(!$case) return false;

        foreach($case as $key => $value) if(strpos($key, 'Date') !== false and $value && !(int)substr($value, 0, 4)) $case->$key = '';

        /* Get project and execution. */
        if($this->app->tab == 'project')
        {
            $case->project = $this->session->project;
        }
        elseif($this->app->tab == 'execution')
        {
            $case->execution = $this->session->execution;
            $case->project   = $this->dao->select('project')->from(TABLE_PROJECT)->where('id')->eq($case->execution)->fetch('project');
        }
        else
        {
            $objects = $this->dao->select('t1.project as objectID, t2.type')->from(TABLE_PROJECTCASE)->alias('t1')
                ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.project=t2.id')
                ->where('t1.case')->eq($caseID)
                ->fetchPairs();
            foreach($objects as $objectID => $objectType)
            {
                if($objectType == 'project') $case->project = $objectID;
                if(in_array($objectType, array('sprint', 'stage', 'kanban'))) $case->execution = $objectID;
            }
        }

        /* Set related variables. */
        $toBugs       = $this->dao->select('id, title, severity, openedDate')->from(TABLE_BUG)->where('`case`')->eq($caseID)->fetchAll();
        $case->toBugs = array();
        foreach($toBugs as $toBug) $case->toBugs[$toBug->id] = $toBug;
        if($case->story)
        {
            $story = $this->dao->findById($case->story)->from(TABLE_STORY)->fields('title, status, version')->fetch();
            $case->storyTitle         = $story->title;
            $case->storyStatus        = $story->status;
            $case->latestStoryVersion = $story->version;
        }
        if($case->fromBug) $case->fromBugData = $this->dao->findById($case->fromBug)->from(TABLE_BUG)->fields('title, severity, openedDate')->fetch();
        if($case->linkCase || $case->fromCaseID) $case->linkCaseTitles = $this->dao->select('id,title')->from(TABLE_CASE)->where('id')->in($case->linkCase)->orWhere('id')->eq($case->fromCaseID)->fetchPairs();

        $case->currentVersion = $version ? $version : $case->version;
        $case->files          = $this->loadModel('file')->getByObject('testcase', $caseID);
        $case->steps          = $this->testcaseTao->getSteps($caseID, $case->currentVersion);
        return $case;
    }

    /**
     * Get cases by id list and query string.
     *
     * @param  array  $caseIdList
     * @param  string $query
     * @access public
     * @return array
     */
    public function getByList(array $caseIdList, string $query = ''): array
    {
        if(!$caseIdList) return array();

        return $this->dao->select('*')->from(TABLE_CASE)
            ->where('deleted')->eq(0)
            ->andWhere('id')->in($caseIdList)
            ->beginIF($query)->andWhere($query)->fi()
            ->fetchAll('id');
    }

    /**
     * 获取用例列表。
     * Get test cases.
     *
     * @param  int        $productID
     * @param  int|string $branch
     * @param  string     $browseType
     * @param  int        $queryID
     * @param  int        $moduleID
     * @param  string     $caseType
     * @param  string     $auto      no|unit
     * @param  string     $orderBy
     * @param  object     $pager
     * @access public
     * @return array
     */
    public function getTestCases(int $productID, string|int $branch, string $browseType, int $queryID, int $moduleID, string $caseType = '', string $auto = 'no', string $orderBy = 'id_desc', object $pager = null): array
    {
        $modules    = $moduleID ? $this->loadModel('tree')->getAllChildId($moduleID) : array();
        $browseType = ($browseType == 'bymodule' and $this->session->caseBrowseType and $this->session->caseBrowseType != 'bysearch') ? $this->session->caseBrowseType : $browseType;
        $auto       = $this->cookie->onlyAutoCase ? 'auto' : $auto;

        if($browseType == 'bymodule' || $browseType == 'all' || $browseType == 'wait')
        {
            if($this->app->tab == 'project') return $this->getModuleProjectCases($productID, $branch, $modules, $browseType, $auto, $caseType, $orderBy, $pager);

            return $this->getModuleCases($productID, $branch, $modules, $browseType, $auto, $caseType, $orderBy, $pager);
        }

        if($browseType == 'needconfirm') return $this->testcaseTao->getNeedConfirmList($productID, $branch, $modules, $auto, $caseType, $orderBy, $pager);
        if($browseType == 'bysuite')     return $this->getBySuite($productID, $branch, $queryID, $modules, $orderBy, $pager, $auto);
        if($browseType == 'bysearch')    return $this->getBySearch($productID, $queryID, $orderBy, $pager, $branch, $auto);

        return array();
    }

    /**
     * Get cases by search.
     *
     * @param  int         $productID
     * @param  int         $queryID
     * @param  string      $orderBy
     * @param  object      $pager
     * @param  int|string  $branch
     * @param  string      $auto   no|unit
     * @access public
     * @return array
     */
    public function getBySearch($productID, $queryID, $orderBy, $pager = null, $branch = 0, $auto = 'no')
    {
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('testcaseQuery', $query->sql);
                $this->session->set('testcaseForm', $query->form);
            }
            else
            {
                $this->session->set('testcaseQuery', ' 1 = 1');
            }
        }
        else
        {
            if($this->session->testcaseQuery == false) $this->session->set('testcaseQuery', ' 1 = 1');
        }

        $queryProductID = $productID;
        $allProduct     = "`product` = 'all'";
        $caseQuery      = '(' . $this->session->testcaseQuery;
        if(strpos($this->session->testcaseQuery, $allProduct) !== false)
        {
            $products  = $this->app->user->view->products;
            $caseQuery = str_replace($allProduct, '1', $caseQuery);
            $caseQuery = $caseQuery . ' AND `product` ' . helper::dbIN($products);
            $queryProductID = 'all';
        }

        $allBranch = "`branch` = 'all'";
        if($branch !== 'all' and strpos($caseQuery, '`branch` =') === false) $caseQuery .= " AND `branch` in('$branch')";
        if(strpos($caseQuery, $allBranch) !== false) $caseQuery = str_replace($allBranch, '1', $caseQuery);
        $caseQuery .= ')';
        $caseQuery  = str_replace('`version`', 't1.`version`', $caseQuery);

        if($this->app->tab == 'project') $caseQuery = str_replace('`product`', 't2.`product`', $caseQuery);

        /* Search criteria under compatible project. */
        $sql = $this->dao->select('*')->from(TABLE_CASE)->alias('t1');
        if($this->app->tab == 'project') $sql->leftJoin(TABLE_PROJECTCASE)->alias('t2')->on('t1.id=t2.case');
        $cases = $sql
            ->where($caseQuery)
            ->beginIF($this->app->tab == 'project' and $this->config->systemMode == 'new')->andWhere('t2.project')->eq($this->session->project)->fi()
            ->beginIF($this->app->tab == 'project' and !empty($productID) and $queryProductID != 'all')->andWhere('t2.product')->eq($productID)->fi()
            ->beginIF($this->app->tab != 'project' and !empty($productID) and $queryProductID != 'all')->andWhere('t1.product')->eq($productID)->fi()
            ->beginIF($auto == 'auto' || $auto == 'unit')->andWhere('t1.auto')->eq($auto)->fi()
            ->beginIF($auto != 'auto' && $auto != 'unit')->andWhere('t1.auto')->ne('unit')->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll('id');

        return $cases;
    }

    /**
     * Get cases by assignedTo.
     *
     * @param  string $account
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $auto  no|unit|skip
     * @access public
     * @return array
     */
    public function getByAssignedTo($account, $orderBy = 'id_desc', $pager = null, $auto = 'no')
    {
        return $this->dao->select('t1.id as run, t1.task,t1.case,t1.version,t1.assignedTo,t1.lastRunner,t1.lastRunDate,t1.lastRunResult,t1.status as lastRunStatus,t2.id as id,t2.project,t2.pri,t2.title,t2.type,t2.openedBy,t2.color,t2.product,t2.branch,t2.module,t2.status,t2.story,t2.storyVersion,t3.name as taskName')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->leftJoin(TABLE_TESTTASK)->alias('t3')->on('t1.task = t3.id')
            ->where('t1.assignedTo')->eq($account)
            ->andWhere('t3.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.status')->ne('done')
            ->beginIF(strpos($auto, 'skip') === false and $auto != 'unit')->andWhere('t2.auto')->ne('unit')->fi()
            ->beginIF($auto == 'unit')->andWhere('t2.auto')->eq('unit')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll(strpos($auto, 'run') !== false? 'run' : 'id');
    }

    /**
     * Get cases by openedBy
     *
     * @param  string $account
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $auto   no|unit|skip
     * @access public
     * @return array
     */
    public function getByOpenedBy($account, $orderBy = 'id_desc', $pager = null, $auto = 'no')
    {
        return $this->dao->findByOpenedBy($account)->from(TABLE_CASE)
            ->beginIF($auto != 'skip')->andWhere('product')->ne(0)->fi()
            ->andWhere('deleted')->eq(0)
            ->beginIF($auto != 'skip' and $auto != 'unit')->andWhere('auto')->ne('unit')->fi()
            ->beginIF($auto == 'unit')->andWhere('auto')->eq('unit')->fi()
            ->orderBy($orderBy)->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get cases by type
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $type    all|needconfirm
     * @param  string $status  all|normal|blocked|investigate
     * @param  int    $moduleID
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $auto    no|unit|skip
     * @access public
     * @return array
     */
    public function getByStatus($productID = 0, $branch = 0, $type = 'all', $status = 'all', $moduleID = 0, $orderBy = 'id_desc', $pager = null, $auto = 'no')
    {
        $modules = $moduleID ? $this->loadModel('tree')->getAllChildId($moduleID) : '0';

        $cases = $this->dao->select('t1.*, t2.title as storyTitle')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->beginIF($productID)->where('t1.product')->eq((int) $productID)->fi()
            ->beginIF($productID == 0)->where('t1.product')->in($this->app->user->view->products)->fi()
            ->beginIF($branch)->andWhere('t1.branch')->eq($branch)->fi()
            ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
            ->beginIF($type == 'needconfirm')->andWhere("t2.status = 'active'")->andWhere('t2.version > t1.storyVersion')->fi()
            ->beginIF($status != 'all')->andWhere('t1.status')->eq($status)->fi()
            ->beginIF($auto == 'unit')->andWhere('t1.auto')->eq('unit')->fi()
            ->beginIF($auto != 'unit')->andWhere('t1.auto')->ne('unit')->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy($orderBy)->page($pager)
            ->fetchAll('id');
        return $this->appendData($cases);
    }

    /**
     * Get cases by product id.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getByProduct($productID)
    {
        return $this->dao->select('*')->from(TABLE_CASE)
            ->where('deleted')->eq(0)
            ->andWhere('product')->eq($productID)
            ->fetchAll('id');
    }

    /**
     * 通过产品 id 和分支获取用例键对。
     * Get case pairs by product id and branch.
     *
     * @param  int       $productID
     * @param  int|array $branch
     * @access public
     * @return array
     */
    public function getPairsByProduct(int $productID, int|array $branch = 0): array
    {
        return $this->dao->select("id, concat_ws(':', id, title) as title")->from(TABLE_CASE)
            ->where('deleted')->eq(0)
            ->andWhere('product')->eq($productID)
            ->beginIF($branch)->andWhere('branch')->in($branch)->fi()
            ->orderBy('id_desc')
            ->fetchPairs();
    }

    /**
     * 获取关联需求的用例。
     * Get cases of a story.
     *
     * @param  int    $storyID
     * @access public
     * @return array
     */
    public function getStoryCases(int $storyID): array
    {
        return $this->dao->select('id, project, title, pri, type, status, lastRunner, lastRunDate, lastRunResult')
            ->from(TABLE_CASE)
            ->where('story')->eq((int)$storyID)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
    }

    /**
     * 获取需求列表关联的用例数量数组。
     * Get counts of some stories' cases.
     *
     * @param  array  $stories
     * @access public
     * @return array
     */
    public function getStoryCaseCounts(array $stories): array
    {
        if(empty($stories)) return array();

        $caseCounts = $this->dao->select('story, COUNT(*) AS cases')
            ->from(TABLE_CASE)
            ->where('story')->in($stories)
            ->andWhere('deleted')->eq(0)
            ->groupBy('story')
            ->fetchPairs();

        foreach($stories as $storyID)
        {
            if(!isset($caseCounts[$storyID])) $caseCounts[$storyID] = 0;
        }

        return $caseCounts;
    }

    /**
     * 获取导出的用例。
     * Get cases to export.
     *
     * @param  string   $exportType
     * @param  int      $taskID
     * @param  string   $orderBy
     * @param  int|bool $limit
     * @access public
     * @return array
     */
    public function getCasesToExport(string $exportType, int $taskID, string $orderBy, int|bool $limit): array
    {
        if(strpos($orderBy, 'case') !== false)
        {
            list($field, $sort) = explode('_', $orderBy);
            $orderBy = '`' . $field . '`_' . $sort;
        }

        if($this->session->testcaseOnlyCondition)
        {
            $caseIdList = array();
            if($taskID) $caseIdList = $this->dao->select('`case`')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->fetchPairs();

            return $this->dao->select('*')->from(TABLE_CASE)->where($this->session->testcaseQueryCondition)
                ->beginIF($taskID)->andWhere('id')->in($caseIdList)->fi()
                ->beginIF($exportType == 'selected')->andWhere('id')->in($this->cookie->checkedItem)->fi()
                ->orderBy($orderBy)
                ->beginIF($limit)->limit($limit)->fi()
                ->fetchAll('id');
        }

        $cases   = array();
        $orderBy = " ORDER BY " . str_replace(array('|', '^A', '_'), ' ', $orderBy);
        $stmt    = $this->dao->query($this->session->testcaseQueryCondition . $orderBy . ($limit ? ' LIMIT ' . $limit : ''));
        while($row = $stmt->fetch())
        {
            $caseID = isset($row->case) ? $row->case : $row->id;

            if($exportType == 'selected' && strpos(",{$this->cookie->checkedItem},", ",$caseID,") === false) continue;

            $row->id        = $caseID;
            $cases[$caseID] = $row;
        }

        return $cases;
    }

    /**
     * 获取导出的用例的结果。
     * Get case results for export.
     *
     * @param  array  $caseIdList
     * @param  int    $taskID
     * @access public
     * @return array
     */
    public function getCaseResultsForExport(array $caseIdList, int $taskID = 0): array
    {
        $stmt = $this->dao->select('t1.*')->from(TABLE_TESTRESULT)->alias('t1')
            ->leftJoin(TABLE_TESTRUN)->alias('t2')->on('t1.run = t2.id')
            ->where('t1.`case`')->in($caseIdList)
            ->beginIF($taskID)->andWhere('t2.task')->eq($taskID)->fi()
            ->orderBy('id_desc')
            ->query();

        $results = array();
        while($result = $stmt->fetch())
        {
            if(!isset($results[$result->case])) $results[$result->case] = unserialize($result->stepResults);
        }

        return $results;
    }

    /**
     * 更新用例。
     * Update a case.
     *
     * @param  object $case
     * @param  object $oldCase
     * @param  array  $testtasks
     * @access public
     * @return bool|array
     */
    public function update(object $case, object $oldCase, array $testtasks = array()): bool|array
    {
        $this->testcaseTao->doUpdate($case);
        if(dao::isError()) return false;

        $this->testcaseTao->updateCase2Project($oldCase, $case);

        if($case->stepChanged) $this->testcaseTao->updateStep($case, $oldCase);

        if($oldCase->lib && empty($oldCase->product))
        {
            $fromcaseVersion = $this->dao->select('fromCaseVersion')->from(TABLE_CASE)->where('fromCaseID')->eq($case->id)->fetch('fromCaseVersion');
            $fromcaseVersion = (int)$fromcaseVersion + 1;
            $this->dao->update(TABLE_CASE)->set('`fromCaseVersion`')->eq($fromcaseVersion)->where('`fromCaseID`')->eq($case->id)->exec();
        }

        if(isset($oldCase->toBugs) && isset($case->linkBug)) $this->testcaseTao->linkBugs($oldCase->id, array_keys($oldCase->toBugs), $case);

        if($case->branch && !empty($testtasks)) $this->testcaseTao->unlinkCaseFromTesttask($oldCase->id, $testtasks);

        $this->loadModel('file')->processFile4Object('testcase', $oldCase, $case);

        /* Join the steps to diff. */
        if($case->stepChanged && $case->steps)
        {
            $oldCase->steps = $this->joinStep($oldCase->steps);
            $case->steps    = $this->joinStep($this->getByID($oldCase->id, $case->version)->steps);
        }
        else
        {
            unset($oldCase->steps);
            unset($case->steps);
        }

        return common::createChanges($oldCase, $case);
    }

    /**
     * 评审用例。
     * Review case.
     *
     * @param  object $case
     * @param  object $oldCase
     * @access public
     * @return bool
     */
    public function review(object $case, object $oldCase): bool
    {
        $this->dao->update(TABLE_CASE)->data($case, 'result,comment')->autoCheck()->checkFlow()->where('id')->eq($oldCase->id)->exec();
        if(dao::isError()) return false;

        $changes = common::createChanges($oldCase, $case);
        if(!empty($changes))
        {
            $actionID = $this->loadModel('action')->create('case', $caseID, 'Reviewed', $case->comment, ucfirst($case->result));
            $this->action->logHistory($actionID, $changes);
        }
        return true;
    }

    /**
     * Batch review cases.
     *
     * @param  array  $caseIdList
     * @param  string $result
     * @access public
     * @return bool
     */
    public function batchReview(array $caseIdList, string $result): bool
    {
        $caseIdList = array_filter($caseIdList);
        if(!$caseIdList) return false;

        $oldCases = $this->getByList($caseIdList, "status = 'wait'");
        if(!$oldCases) return false;

        $now  = helper::now();
        $case = new stdClass();
        $case->reviewedBy     = $this->app->user->account;
        $case->reviewedDate   = substr($now, 0, 10);
        $case->lastEditedBy   = $this->app->user->account;
        $case->lastEditedDate = $now;
        if($result == 'pass') $case->status = 'normal';

        $this->dao->update(TABLE_CASE)->data($case)->autoCheck()->where('id')->in(array_keys($oldCases))->exec();
        if(dao::isError()) return false;

        $this->loadModel('action');

        foreach($oldCases as $oldCase)
        {
            $changes = common::createChanges($oldCase, $case);
            if($changes)
            {
                $actionID = $this->action->create('case', $oldCase->id, 'Reviewed', '', ucfirst($result));
                $this->action->logHistory($actionID, $changes);
            }
        }

        return !dao::isError();
    }

    /**
     * 获取可关联的用例。
     * Get cases to link.
     *
     * @param  int    $caseID
     * @param  string $browseType
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getCases2Link(int $caseID, string $browseType = 'bySearch', int $queryID = 0): array
    {
        if($browseType != 'bySearch') return array();

        $case       = $this->getByID($caseID);
        $cases2Link = $this->getBySearch($case->product, $queryID, 'id', null, $case->branch);
        foreach($cases2Link as $key => $case2Link)
        {
            if($case2Link->id == $caseID) unset($cases2Link[$key]);
            if(in_array($case2Link->id, explode(',', $case->linkCase))) unset($cases2Link[$key]);
        }
        return $cases2Link;
    }

    /**
     * 获取可关联的 bug。
     * Get bugs to link.
     *
     * @param  int    $caseID
     * @param  string $browseType
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getBugs2Link(int $caseID, string $browseType = 'bySearch', int $queryID = 0): array
    {
        if($browseType != 'bySearch') return array();

        $case      = $this->getByID($caseID);
        $bugs2Link = $this->loadModel('bug')->getBySearch('bug', $case->product, (string)$case->branch, 0, 0, $queryID, '', 'id');
        foreach($bugs2Link as $key => $bug2Link)
        {
            if($bug2Link->case != 0) unset($bugs2Link[$key]);
        }
        return $bugs2Link;
    }

    /**
     * 批量删除用例和场景。
     * Batch delete cases and scenes.
     *
     * @param  array  $caseIdList
     * @param  array  $sceneIdList
     * @access public
     * @return bool
     */
    public function batchDelete(array $caseIdList, array $sceneIdList): bool
    {
        /* 过滤用例和场景。 */
        /* Filter cases and scenes. */
        $caseIdList  = array_filter($caseIdList);
        $sceneIdList = array_filter($sceneIdList);
        if(!$caseIdList && !$sceneIdList) return false;

        $this->loadModel('action');

        /* 删除用例。 */
        /* Delete cases. */
        if($caseIdList)
        {
            $this->dao->update(TABLE_CASE)->set('deleted')->eq('1')->where('id')->in($caseIdList)->exec();
            foreach($caseIdList as $caseID) $this->action->create('case', $caseID, 'deleted', '', actionModel::CAN_UNDELETED);
        }

        /* 删除场景。 */
        /* Delete scenes. */
        if($sceneIdList)
        {
            $this->dao->update(TABLE_SCENE)->set('deleted')->eq('1')->where('id')->in($sceneIdList)->exec();
            foreach($sceneIdList as $sceneID) $this->action->create('scene', $sceneID, 'deleted', '', actionModel::CAN_UNDELETED);
        }

        return !dao::isError();
    }

    /**
     * Batch change branch of cases and scenes.
     *
     * @param  array  $caseIdList
     * @param  array  $sceneIdList
     * @param  int    $branchID
     * @access public
     * @return bool
     */
    public function batchChangeBranch(array $caseIdList, array $sceneIdList, int $branchID): bool
    {
        if($branchID < 0 || $branchID > 16777215) return false; // The branch column's data type is mediumint unsigned and its range is 0-16777215.

        $caseIdList  = array_filter($caseIdList);
        $sceneIdList = array_filter($sceneIdList);
        if(!$caseIdList && !$sceneIdList) return false;

        if($caseIdList)  $this->batchChangeCaseBranch($caseIdList, $branchID);
        if($sceneIdList) $this->batchChangeSceneBranch($sceneIdList, $branchID);

        return !dao::isError();
    }

    /**
     * Batch change branch of cases.
     *
     * @param  array  $caseIdList
     * @param  int    $branchID
     * @access public
     * @return bool
     */
    public function batchChangeCaseBranch(array $caseIdList, int $branchID): bool
    {
        if(!$caseIdList) return false;
        if($branchID < 0 || $branchID > 16777215) return false; // The branch column's data type is mediumint unsigned and its range is 0-16777215.

        $oldCases = $this->getByList($caseIdList, "branch != '{$branchID}'");
        if(!$oldCases) return false;

        $case = new stdclass();
        $case->branch         = $branchID;
        $case->lastEditedBy   = $this->app->user->account;
        $case->lastEditedDate = helper::now();

        $this->dao->update(TABLE_CASE)->data($case)->where('id')->in(array_keys($oldCases))->exec();
        if(dao::isError()) return false;

        $this->loadModel('action');

        foreach($oldCases as $oldCase)
        {
            $changes = common::createChanges($oldCase, $case);
            if($changes)
            {
                $actionID = $this->action->create('case', $oldCase->id, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
        }

        return !dao::isError();
    }

    /**
     * Batch change branch of scenes.
     *
     * @param  array  $sceneIdList
     * @param  int    $branchID
     * @access public
     * @return bool
     */
    public function batchChangeSceneBranch(array $sceneIdList, int $branchID): bool
    {
        if(!$sceneIdList) return false;
        if($branchID < 0 || $branchID > 16777215) return false; // The branch column's data type is mediumint unsigned and its range is 0-16777215.

        $oldScenes = $this->getScenesByList($sceneIdList, "branch != '{$branchID}'");
        if(!$oldScenes) return false;

        $scene = new stdclass();
        $scene->branch         = $branchID;
        $scene->lastEditedBy   = $this->app->user->account;
        $scene->lastEditedDate = helper::now();

        $this->dao->update(TABLE_SCENE)->data($scene)->where('id')->in(array_keys($oldScenes))->exec();
        if(dao::isError()) return false;

        $this->loadModel('action');

        foreach($oldScenes as $oldScene)
        {
            $changes = common::createChanges($oldScene, $scene);
            if($changes)
            {
                $actionID = $this->action->create('scene', $oldScene->id, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
        }

        return !dao::isError();
    }

    /**
     * Batch change module of cases and scenes.
     *
     * @param  array  $caseIdList
     * @param  array  $sceneIdList
     * @param  int    $moduleID
     * @access public
     * @return bool
     */
    public function batchChangeModule(array $caseIdList, array $sceneIdList, int $moduleID): bool
    {
        if($moduleID < 0 || $moduleID > 16777215) return false; // The module column's data type is mediumint unsigned and its range is 0-16777215.

        $caseIdList  = array_filter($caseIdList);
        $sceneIdList = array_filter($sceneIdList);
        if(!$caseIdList && !$sceneIdList) return false;

        if($caseIdList)  $this->batchChangeCaseModule($caseIdList, $moduleID);
        if($sceneIdList) $this->batchChangeSceneModule($sceneIdList, $moduleID);

        return !dao::isError();
    }

    /**
     * Batch change module of cases.
     *
     * @param  array  $caseIdList
     * @param  int    $moduleID
     * @access public
     * @return bool
     */
    public function batchChangeCaseModule(array $caseIdList, int $moduleID): bool
    {
        if(!$caseIdList) return false;
        if($moduleID < 0 || $moduleID > 16777215) return false; // The module column's data type is mediumint unsigned and its range is 0-16777215.

        $oldCases = $this->getByList($caseIdList, "module != '{$moduleID}'");
        if(!$oldCases) return false;

        $case = new stdclass();
        $case->module         = $moduleID;
        $case->lastEditedBy   = $this->app->user->account;
        $case->lastEditedDate = helper::now();

        $this->dao->update(TABLE_CASE)->data($case)->where('id')->in(array_keys($oldCases))->exec();
        if(dao::isError()) return false;

        $this->loadModel('action');

        foreach($oldCases as $oldCase)
        {
            $changes = common::createChanges($oldCase, $case);
            if($changes)
            {
                $actionID = $this->action->create('case', $oldCase->id, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
        }

        return !dao::isError();
    }

    /**
     * Batch change module of scenes.
     *
     * @param  array  $sceneIdList
     * @param  int    $moduleID
     * @access public
     * @return bool
     */
    public function batchChangeSceneModule(array $sceneIdList, int $moduleID): bool
    {
        if(!$sceneIdList) return false;
        if($moduleID < 0 || $moduleID > 16777215) return false; // The module column's data type is mediumint unsigned and its range is 0-16777215.

        $oldScenes = $this->getScenesByList($sceneIdList, "module != '{$moduleID}'");
        if(!$oldScenes) return false;

        $scene = new stdclass();
        $scene->module         = $moduleID;
        $scene->lastEditedBy   = $this->app->user->account;
        $scene->lastEditedDate = helper::now();

        $this->dao->update(TABLE_SCENE)->data($scene)->where('id')->in(array_keys($oldScenes))->exec();
        if(dao::isError()) return false;

        $this->loadModel('action');

        foreach($oldScenes as $oldScene)
        {
            $changes = common::createChanges($oldScene, $scene);
            if($changes)
            {
                $actionID = $this->action->create('scene', $oldScene->id, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
        }

        return !dao::isError();
    }

    /**
     * Batch change type of cases.
     *
     * @param  array  $caseIdList
     * @param  string $type
     * @access public
     * @return bool
     */
    public function batchChangeType(array $caseIdList, string $type): bool
    {
        if(!$type) return false;

        $caseIdList = array_filter($caseIdList);
        if(!$caseIdList) return false;

        $oldCases = $this->getByList($caseIdList, "type != '{$type}'");
        if(!$oldCases) return false;

        $case = new stdClass();
        $case->type           = $type;
        $case->lastEditedBy   = $this->app->user->account;
        $case->lastEditedDate = helper::now();

        $this->dao->update(TABLE_CASE)->data($case)->autoCheck()->where('id')->in(array_keys($oldCases))->exec();
        if(dao::isError()) return false;

        $this->loadModel('action');

        foreach($oldCases as $oldCase)
        {
            $changes = common::createChanges($oldCase, $case);
            if($changes)
            {
                $actionID = $this->action->create('case', $oldCase->id, 'Edited', '', ucfirst($type));
                $this->action->logHistory($actionID, $changes);
            }
        }

        return !dao::isError();
    }

    /**
     * Batch confirm story change of cases.
     *
     * @param  array  $caseIdList
     * @access public
     * @return bool
     */
    public function batchConfirmStoryChange(array $caseIdList): bool
    {
        $caseIdList = array_filter($caseIdList);
        if(!$caseIdList) return false;

        $cases = $this->getByList($caseIdList);
        if(!$cases) return false;

        $storyIdList = array_unique(array_filter(array_map(function($case){return $case->story;}, $cases)));
        if(!$storyIdList) return false;

        $stories = $this->dao->select('id, version')->from(TABLE_STORY)->where('id')->in($storyIdList)->fetchPairs();

        $this->loadModel('action');

        foreach($cases as $case)
        {
            $storyVersion = zget($stories, $case->story, 0);
            if(!$storyVersion) continue;

            $this->dao->update(TABLE_CASE)->set('storyVersion')->eq($storyVersion)->where('id')->eq($case->id)->exec();
            $this->action->create('case', $case->id, 'confirmed', '', $storyVersion);
        }

        return !dao::isError();
    }

    /**
     * 将步骤转为字符串，并且区分它们。
     * Join steps to a string, thus can diff them.
     *
     * @param  array  $steps
     * @access public
     * @return string
     */
    public function joinStep(array $steps): string
    {
        $return = '';
        if(empty($steps)) return $return;
        foreach($steps as $step) $return .= $step->desc . ' EXPECT:' . $step->expect . "\n";
        return $return;
    }

    /**
     * 从 bug 的步骤创建用例步骤。
     * Create case steps from a bug's step.
     *
     * @param  string $steps
     * @access public
     * @return array
     */
    public function createStepsFromBug(string $steps): array
    {
        /* 初始化步骤相关变量，获取步骤的描述、结果和期望，以及他们在字符串中的位置。 */
        /* Initializes the step variable, and get the desc, result and expect of the step and its position. */
        $steps        = strip_tags($steps);
        $caseSteps    = array((object)array('desc' => $steps, 'expect' => ''));   // the default steps before parse.
        $lblStep      = strip_tags($this->lang->bug->tplStep);
        $lblResult    = strip_tags($this->lang->bug->tplResult);
        $lblExpect    = strip_tags($this->lang->bug->tplExpect);
        $lblStepPos   = strpos($steps, $lblStep);
        $lblResultPos = strpos($steps, $lblResult);
        $lblExpectPos = strpos($steps, $lblExpect);

        /* 如果 bug 的步骤没有描述、结果或者期望，返回默认步骤。 */
        /* If steps don't have desc, result or expect, return default steps. */
        if($lblStepPos === false || $lblResultPos === false || $lblExpectPos === false) return $caseSteps;

        /* 计算描述和期望。 */
        /* Process desc and expect. */
        $caseSteps  = substr($steps, $lblStepPos + strlen($lblStep), $lblResultPos - strlen($lblStep) - $lblStepPos);
        $caseExpect = substr($steps, $lblExpectPos + strlen($lblExpect));
        $caseSteps  = trim($caseSteps);
        $caseExpect = trim($caseExpect);

        /* 计算步骤。 */
        /* Process steps. */
        $caseSteps = explode("\n", trim($caseSteps));
        $stepCount = count($caseSteps);
        foreach($caseSteps as $key => $caseStep)
        {
            $expect = $key + 1 == $stepCount ? $caseExpect : '';
            $caseSteps[$key] = (object)array('desc' => trim($caseStep), 'expect' => $expect, 'type' => 'item');
        }

        return $caseSteps;
    }

    /**
     * Adjust the action is clickable.
     *
     * @param  object $case
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $case, string $action): bool
    {
        $canBeChanged = common::canBeChanged('case', $case);
        if(!$canBeChanged) return false;

        global $config;

        $action = strtolower($action);

        if($action == 'confirmchange')      return $case->caseStatus != 'wait' && $case->version < $case->caseVersion;
        if($action == 'confirmstorychange') return $case->needconfirm || (isset($case->browseType) && $case->browseType == 'needconfirm');
        if($action == 'createbug')          return !empty($case->caseFails) && $case->caseFails > 0;
        if($action == 'review')             return ($config->testcase->needReview || !empty($config->testcase->forceReview)) && (isset($case->caseStatus) ? $case->caseStatus == 'wait' : $case->status == 'wait');
        if($action == 'showscript')         return $case->auto == 'auto';

        return true;
    }

    /**
     * 获取导入的字段。
     * Get fields for import.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getImportFields(int $productID = 0): array
    {
        /* If type of the product isn't normal, set language item of branch. */
        /* 如果产品是多分支产品，设置分支语言项。 */
        $product = $this->loadModel('product')->getById($productID);
        if($product && $product->type != 'normal') $this->lang->testcase->branch = $this->lang->product->branchName[$product->type];

        $caseLang   = $this->lang->testcase;
        $caseConfig = $this->config->testcase;
        $fields     = explode(',', $caseConfig->exportFields);
        foreach($fields as $key => $fieldName)
        {
            /* 设置字段的语言项。 */
            /* Set language item of the fieldName. */
            $fieldName          = trim($fieldName);
            $fields[$fieldName] = isset($caseLang->{$fieldName}) ? $caseLang->{$fieldName} : $fieldName;
            unset($fields[$key]);
        }

        return $fields;
    }

    /**
     * 导入测试用例到用例库。
     * Import cases to lib.
     *
     * @param  array  $cases
     * @param  array  $steps
     * @param  array  $files
     * @access public
     * @return bool
     */
    public function importToLib(array $cases, array $steps, array $files): bool
    {
        $this->loadModel('action');
        foreach($cases as $case)
        {
            /* 如果用例没有 ID，插入用例。 */
            /* If case id is not exist, insert it. */
            if(!isset($case->id))
            {
                $this->testcaseTao->doCreate($case);
                if(!dao::isError())
                {
                    $caseID = $this->dao->lastInsertID();
                    $this->action->create('case', $caseID, 'tolib', '', $case->fromCaseID);
                }
            }
            /* 如果用例有 ID，更新用例。 */
            /* If case id is exist, update it. */
            else
            {
                $caseID = $case->id;
                $this->testcaseTao->doUpdate($case);
                $this->action->create('case', $caseID, 'updatetolib', '', $case->fromCaseID);

                $this->dao->delete()->from(TABLE_CASESTEP)->where('`case`')->eq($caseID)->exec();

                $removeFiles = $this->dao->select('*')->from(TABLE_FILE)->where('`objectID`')->eq($caseID)->andWhere('objectType')->eq('testcase')->fetchAll('id');
                $this->dao->delete()->from(TABLE_FILE)->where('`objectID`')->eq($caseID)->andWhere('objectType')->eq('testcase')->exec();
                foreach($removeFiles as $fileID => $file)
                {
                    if(empty($file->pathname)) continue;
                    $filePath = pathinfo($file->pathname, PATHINFO_BASENAME);
                    $datePath = substr($file->pathname, 0, 6);
                    $filePath = $this->app->getAppRoot() . "www/data/upload/{$this->app->company->id}/" . "{$datePath}/" . $filePath;
                    unlink($filePath);
                }
            }
            $this->testcaseTao->importSteps($caseID, zget($steps, $case->fromCaseID, array()));
            $this->testcaseTao->importFiles($caseID, zget($files, $case->fromCaseID, array()));
        }
        return !dao::isError();
    }

    /**
     * Import case related modules.
     *
     * @param  int    $libID
     * @param  int    $oldModuleID
     * @param  int    $maxOrder
     * @access public
     * @return void
     */
    public function importCaseRelatedModules($libID, $oldModuleID = 0, $maxOrder = 0)
    {
        $moduleID = $this->checkModuleImported($libID, $oldModuleID);
        if($moduleID) return $moduleID;

        $oldModule = $this->dao->select('name, parent, grade, `order`, short')->from(TABLE_MODULE)->where('id')->eq($oldModuleID)->fetch();

        $oldModule->root   = $libID;
        $oldModule->from   = $oldModuleID;
        $oldModule->type   = 'caselib';
        if(!empty($maxOrder)) $oldModule->order = $maxOrder + $oldModule->order;
        $this->dao->insert(TABLE_MODULE)->data($oldModule)->autoCheck()->exec();

        if(!dao::isError())
        {
            $newModuleID = $this->dao->lastInsertID();

            if($oldModule->parent)
            {
                $parentModuleID = $this->importCaseRelatedModules($libID, $oldModule->parent, !empty($maxOrder) ? $maxOrder : 0);
                $parentModule   = $this->dao->select('id, path')->from(TABLE_MODULE)->where('id')->eq($parentModuleID)->fetch();
                $parent         = $parentModule->id;
                $path           = $parentModule->path . "$newModuleID,";
            }
            else
            {
                $path   = ",$newModuleID,";
                $parent = 0;
            }

            $this->dao->update(TABLE_MODULE)->set('parent')->eq($parent)->set('path')->eq($path)->where('id')->eq($newModuleID)->exec();

            return $newModuleID;
        }
    }

    /**
     * 检查模块是否可以导入。
     * Adjust module is can import.
     *
     * @param  int    $libID
     * @param  int    $oldModule
     * @access public
     * @return int
     */
    public function checkModuleImported(int $libID, int $oldModule = 0): int
    {
        /* Get module if from is oldModule and root is libID. */
        $module = $this->dao->select('id')->from(TABLE_MODULE)
            ->where('root')->eq($libID)
            ->andWhere('`from`')->eq($oldModule)
            ->andWhere('type')->eq('caselib')
            ->andWhere('deleted')->eq(0)
            ->fetch();

        /* If module isn't exist, return 0. */
        if(!$module) return 0;

        /* Return module id. */
        return $module->id;
    }

    /**
     * Build search form.
     *
     * @param  int    $productID
     * @param  array  $products
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  string $projectID
     * @access public
     * @return void
     */
    public function buildSearchForm($productID, $products, $queryID, $actionURL, $projectID = 0, $moduleID = 0, $branch = 0)
    {
        $productList = array();
        if($this->app->tab == 'project' and empty($productID))
        {
            $productList = $products;
        }
        else
        {
            $productList = array('all' => $this->lang->all);
            if(isset($products[$productID])) $productList = array($productID => $products[$productID]) + $productList;
        }
        $this->config->testcase->search['params']['product']['values'] = array('') + $productList;

        $module = $this->loadModel('tree')->getOptionMenu($productID, 'case', 0, $branch);
        $scene  = $this->getSceneMenu($productID, $moduleID, $viewType = 'case', $startSceneID = 0, $branch, 0, true);
        if(!$productID)
        {
            $module = array();
            foreach($products as $id => $name) $module += $this->loadModel('tree')->getOptionMenu($id, 'case', 0);
        }
        $this->config->testcase->search['params']['module']['values'] = $module;
        $this->config->testcase->search['params']['scene']['values']  = $scene;

        $this->config->testcase->search['params']['lib']['values'] = $this->loadModel('caselib')->getLibraries();

        if($this->session->currentProductType == 'normal')
        {
            unset($this->config->testcase->search['fields']['branch']);
            unset($this->config->testcase->search['params']['branch']);
        }
        else
        {
            $this->app->loadLang('branch');
            $product = $this->loadModel('product')->getByID($productID);
            $this->config->testcase->search['fields']['branch'] = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
            $this->config->testcase->search['params']['branch']['values'] = array('' => '', '0' => $this->lang->branch->main) + $this->loadModel('branch')->getPairs($productID, '', $projectID) + array('all' => $this->lang->branch->all);
        }
        if(!$this->config->testcase->needReview) unset($this->config->testcase->search['params']['status']['values']['wait']);
        $this->config->testcase->search['actionURL'] = $actionURL;
        $this->config->testcase->search['queryID']   = $queryID;
        $this->config->testcase->search['module']    = $this->app->rawModule;

        $this->loadModel('search')->setSearchParams($this->config->testcase->search);
    }

    /**
     * Print cell data
     *
     * @param  object $col
     * @param  object $case
     * @param  array  $users
     * @param  array  $branches
     * @access public
     * @return void
     */
    public function printCell($col, $case, $users, $branches, $modulePairs = array(), $browseType = '', $mode = 'datatable', $isCase = 1)
    {
        /* Check the product is closed. */
        $canBeChanged = common::canBeChanged('case', $case);

        $canBatchRun                = common::hasPriv('testtask', 'batchRun');
        $canBatchEdit               = common::hasPriv('testcase', 'batchEdit');
        $canBatchDelete             = common::hasPriv('testcase', 'batchDelete');
        $canBatchChangeType         = common::hasPriv('testcase', 'batchChangeType');
        $canBatchConfirmStoryChange = common::hasPriv('testcase', 'batchConfirmStoryChange');
        $canBatchChangeModule       = common::hasPriv('testcase', 'batchChangeModule');

        $canBatchAction             = ($canBatchRun or $canBatchEdit or $canBatchDelete or $canBatchChangeType or $canBatchConfirmStoryChange or $canBatchChangeModule);

        $canView    = common::hasPriv('testcase', 'view');
        $caseLink   = helper::createLink('testcase', 'view', "caseID=$case->id&version=$case->version");
        $account    = $this->app->user->account;
        $fromCaseID = $case->fromCaseID;
        $id = $col->id;
        if($col->show)
        {
            $class = $id == 'title' ? 'c-name' : 'c-' . $id;
            $title = '';
            if($id == 'title')
            {
                $class .= ' text-left';
                $title  = "title='{$case->title}'";
            }
            if($id == 'status')
            {
                $class .= $case->status;
                $title  = "title='" . $this->processStatus('testcase', $case) . "'";
            }
            if(strpos(',bugs,results,stepNumber,', ",$id,") !== false) $title = "title='{$case->$id}'";
            if($id == 'actions') $class .= ' c-actions';
            if($id == 'lastRunResult') $class .= " {$case->lastRunResult}";
            if(strpos(',stage,precondition,keywords,story,', ",{$id},") !== false) $class .= ' text-ellipsis';

            if($id == 'title')
            {
                if($isCase == 2)
                {
                    echo "<td class='c-name table-nest-title text-left sort-handler has-prefix has-suffix' {$title}><span class='table-nest-icon icon '></span>";
                }
                else
                {
                    echo "<td class='c-name table-nest-title text-left sort-handler has-prefix has-suffix' {$title}><span class='table-nest-icon icon icon-test'></span>";
                }
            }
            else
            {
                echo "<td class='{$class}' {$title}>";
            }
            if($this->config->edition != 'open') $this->loadModel('flow')->printFlowCell('testcase', $case, $id);
            switch($id)
            {
            case 'id':
                $showid = "";
                if($isCase == 2)
                {
                    $showid = substr($case->id,1);
                    $showid = preg_replace('/^0+/', '', $showid);
                }
                else
                {
                    $showid = $case->id;
                }
                if($canBatchAction)
                {
                    $disabled = $canBeChanged ? '' : 'disabled';
                    if($isCase == 1){
                        echo html::checkbox('caseIDList', array($case->id => ''), '', $disabled) . html::a(helper::createLink('testcase', 'view', "caseID=$case->id"), sprintf('%03d', $showid), '', "data-app='{$this->app->tab}'");
                    }
                    else
                    {
                        echo html::checkbox('caseIDList', array($case->id => ''), '', $disabled) .  sprintf('%03d', $showid);
                    }
                }
                else
                {
                    printf('%03d', $showid);
                }
                break;
            case 'pri':
                if($isCase != 2)
                {
                    echo "<span class='label-pri label-pri-" . $case->pri . "' title='" . zget($this->lang->testcase->priList, $case->pri, $case->pri) . "'>";
                    echo zget($this->lang->testcase->priList, $case->pri, $case->pri);
                    echo "</span>";
                }
                break;
            case 'title':
                if($isCase == 1)
                {
                    $autoIcon = $case->auto == 'auto' ? " <i class='icon icon-draft-edit'></i>" : '';
                    if($modulePairs and $case->module and isset($modulePairs[$case->module])) echo "<span class='label label-gray label-badge'>{$modulePairs[$case->module]}</span> ";
                    echo $canView ? html::a($caseLink, $case->title, null, "style='color: $case->color' data-app='{$this->app->tab}'")
                        : "<span style='color: $case->color'>$case->title</span>";

                    $fromLink = ($fromCaseID and $canView) ? helper::createLink('testcase', 'view', "caseID=$fromCaseID") : '#';
                    $title    = $fromCaseID ? "[<i class='icon icon-share' title='{$this->lang->testcase->fromCaselib}'></i>#$fromCaseID]$autoIcon" : $autoIcon;
                    if($case->auto == 'auto') echo html::a($fromLink, $title, '', "data-app='{$this->app->tab}'");
                }
                else
                {
                    echo $case->title;
                }
                break;
            case 'branch':
                echo $branches[$case->branch];
                break;
            case 'type':
                echo $this->lang->testcase->typeList[$case->type];
                break;
            case 'stage':
                $stages = '';
                foreach(explode(',', trim($case->stage, ',')) as $stage) $stages .= $this->lang->testcase->stageList[$stage] . ',';
                $stages = trim($stages, ',');
                echo "<span title='$stages'>$stages</span>";
                break;
            case 'status':
                if($case->needconfirm)
                {
                    print("<span class='status-story status-changed' title='{$this->lang->story->changed}'>{$this->lang->story->changed}</span>");
                }
                elseif(isset($case->fromCaseVersion) and $case->fromCaseVersion > $case->version and !$case->needconfirm)
                {
                    print("<span class='status-story status-changed' title='{$this->lang->testcase->changed}'>{$this->lang->testcase->changed}</span>");
                }
                else
                {
                    print("<span class='status-testcase status-{$case->status}'>" . $this->processStatus('testcase', $case) . "</span>");
                }
                break;
            case 'story':
                static $stories = array();
                if(empty($stories)) $stories = $this->dao->select('id,title')->from(TABLE_STORY)->where('deleted')->eq('0')->andWhere('product')->eq($case->product)->fetchPairs('id', 'title');
                if($case->story and isset($stories[$case->story])) echo html::a(helper::createLink('story', 'view', "storyID=$case->story"), $stories[$case->story]);
                break;
            case 'precondition':
                echo $case->precondition;
                break;
            case 'keywords':
                echo $case->keywords;
                break;
            case 'version':
                if($isCase == 1) echo $case->version;
                break;
            case 'openedBy':
                echo zget($users, $case->openedBy);
                break;
            case 'openedDate':
                echo substr($case->openedDate, 5, 11);
                break;
            case 'reviewedBy':
                echo zget($users, $case->reviewedBy);
                break;
            case 'reviewedDate':
                 echo helper::isZeroDate($case->reviewedDate) ? '' : substr($case->reviewedDate, 5, 11);
                break;
            case 'lastEditedBy':
                echo zget($users, $case->lastEditedBy);
                break;
            case 'lastEditedDate':
                 echo helper::isZeroDate($case->lastEditedDate) ? '' : substr($case->lastEditedDate, 5, 11);
                break;
            case 'lastRunner':
                echo zget($users, $case->lastRunner);
                break;
            case 'lastRunDate':
                if(!helper::isZeroDate($case->lastRunDate)) echo substr($case->lastRunDate, 5, 11);
                break;
            case 'lastRunResult':
                if ($isCase == 1) {
                    $class = 'result-' . $case->lastRunResult;
                    $lastRunResultText = $case->lastRunResult ? zget($this->lang->testcase->resultList, $case->lastRunResult, $case->lastRunResult) : $this->lang->testcase->unexecuted;
                    echo "<span class='$class'>" . $lastRunResultText . "</span>";
                }
                break;
            case 'bugs':
                if ($isCase == 1) echo (common::hasPriv('testcase', 'bugs') and $case->bugs) ? html::a(helper::createLink('testcase', 'bugs', "runID=0&caseID={$case->id}"), $case->bugs, '', "class='iframe'") : $case->bugs;
                break;
            case 'results':
                if ($isCase == 1) echo (common::hasPriv('testtask', 'results') and $case->results) ? html::a(helper::createLink('testtask', 'results', "runID=0&caseID={$case->id}"), $case->results, '', "class='iframe'") : $case->results;
                break;
            case 'stepNumber':
                if ($isCase == 1) echo $case->stepNumber;
                break;
            case 'actions':
                if ($isCase == 1)
                {
                    $case->browseType = $browseType;
                    echo $this->buildOperateMenu($case, 'browse');
                    break;
                }
                else
                {
                    echo $this->buildOperateBrowseSceneMenu($case);
                }
            }
            echo '</td>';
        }
    }

    /**
     * 追加 bug 和用例执行结果信息
     * Append bugs and results.
     *
     * @param  array  $cases
     * @param  string $type
     * @param  array  $caseIdList
     * @access public
     * @return void
     */
    public function appendData(array $cases, string $type = 'case', array $caseIdList = array()): array
    {
        if(empty($caseIdList)) $caseIdList = array_keys($cases);

        /* 查询用例的 bugs 和结果。 */
        /* Get bugs and results. */
        $queryField = $type == 'case' ? '`case`' : '`result`';
        $caseBugs   = $this->dao->select('count(*) as count, `case`')->from(TABLE_BUG)->where($queryField)->in($caseIdList)->andWhere('deleted')->eq(0)->groupBy('`case`')->fetchPairs('case', 'count');
        $results    = $this->dao->select('count(*) as count, `case`')->from(TABLE_TESTRESULT)->where('`case`')->in($caseIdList)->groupBy('`case`')->fetchPairs('case', 'count');

        /* 查询用例的失败结果。 */
        /* Get result fails of the the testcases. */
        if($type != 'case') $queryField = '`run`';
        $caseFails  = $this->dao->select('count(*) as count, `case`')->from(TABLE_TESTRESULT)
            ->where('caseResult')->eq('fail')
            ->andWhere($queryField)->in($caseIdList)
            ->groupBy('`case`')
            ->fetchPairs('case','count');

        /* 查询用例的步骤。 */
        /* Get the the testcase steps. */
        $queryTable = $type == 'case' ? TABLE_CASE : TABLE_TESTRUN;
        $queryOn    = $type == 'case' ? 't1.`case`=t2.`id`' : 't1.`case`=t2.`case`';
        $queryField = $type == 'case' ? 't1.`case`' : 't2.`id`';
        $steps = $this->dao->select('count(distinct t1.id) as count, t1.`case`')->from(TABLE_CASESTEP)->alias('t1')
            ->leftJoin($queryTable)->alias('t2')->on($queryOn)
            ->where($queryField)->in($caseIdList)
            ->andWhere('t1.type')->ne('group')
            ->andWhere('t1.version=t2.version')
            ->groupBy('t1.`case`')
            ->fetchPairs('case', 'count');

        /* 设置测试用例的 bugs 执行结果和步骤。 */
        /* Set related bugs, results and steps of the testcases. */
        foreach($cases as $key => $case)
        {
            $caseID = $type == 'case' ? $case->id : $case->case;
            $case->bugs       = isset($caseBugs[$caseID])  ? $caseBugs[$caseID]   : 0;
            $case->results    = isset($results[$caseID])   ? $results[$caseID]    : 0;
            $case->caseFails  = isset($caseFails[$caseID]) ? $caseFails[$caseID]  : 0;
            $case->stepNumber = isset($steps[$caseID])     ? $steps[$caseID]      : 0;
        }
        return $cases;
    }

    /**
     * Check whether force not review.
     *
     * @access public
     * @return bool
     */
    public function forceNotReview()
    {
        if(empty($this->config->testcase->needReview))
        {
            if(!isset($this->config->testcase->forceReview)) return true;
            if(strpos(",{$this->config->testcase->forceReview},", ",{$this->app->user->account},") === false) return true;
        }
        if($this->config->testcase->needReview && isset($this->config->testcase->forceNotReview) && strpos(",{$this->config->testcase->forceNotReview},", ",{$this->app->user->account},") !== false) return true;

        return false;
    }

    /**
     * Summary cases
     *
     * @param  array    $cases
     * @access public
     * @return string
     */
    public function summary($cases)
    {
        $executed = 0;
        foreach($cases as $case)
        {
            if($case->lastRunResult != '') $executed ++;
        }

        return sprintf($this->lang->testcase->summary, count($cases), $executed);
    }

    /**
     * Sync case to project.
     *
     * @param  object $case
     * @param  int    $caseID
     * @access public
     * @return void
     */
    public function syncCase2Project($case, $caseID)
    {
        $projects = array();
        if(!empty($case->story))
        {
            $projects = $this->dao->select('project')->from(TABLE_PROJECTSTORY)->where('story')->eq($case->story)->fetchPairs();
        }
        elseif($this->app->tab == 'project' and empty($case->story))
        {
            $projects = array($this->session->project);
        }
        elseif($this->app->tab == 'execution' and empty($case->story))
        {
            $projects = array($this->session->execution);
        }
        if(empty($projects)) return;

        $this->loadModel('action');
        $objectInfo = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->in($projects)->fetchAll('id');

        foreach($projects as $projectID)
        {
            $lastOrder = (int)$this->dao->select('*')->from(TABLE_PROJECTCASE)->where('project')->eq($projectID)->orderBy('order_desc')->limit(1)->fetch('order');
            $data = new stdclass();
            $data->project = $projectID;
            $data->product = $case->product;
            $data->case    = $caseID;
            $data->version = 1;
            $data->order   = ++ $lastOrder;
            $this->dao->insert(TABLE_PROJECTCASE)->data($data)->exec();

            $object     = $objectInfo[$projectID];
            $objectType = $object->type;
            if($objectType == 'project') $this->action->create('case', $caseID, 'linked2project', '', $projectID);
            if(in_array($objectType, array('sprint', 'stage')) and $object->multiple) $this->action->create('case', $caseID, 'linked2execution', '', $projectID);
        }
    }

    /**
     * processDatas
     *
     * @param  array  $datas
     * @access public
     * @return void
     */
    public function processDatas($datas)
    {
        if(isset($datas->datas)) $datas = $datas->datas;
        $columnKey  = array();
        $caseData   = array();
        $stepData   = array();
        $stepVars   = 0;

        foreach($datas as $row => $cellValue)
        {
            foreach($cellValue as $field => $value)
            {
                if($field != 'stepDesc' and $field != 'stepExpect') continue;
                if($field == 'stepDesc' or $field == 'stepExpect')
                {
                    $steps = $value;
                    if(strpos($value, "\n"))
                    {
                        $steps = explode("\n", $value);
                    }
                    elseif(strpos($value, "\r"))
                    {
                        $steps = explode("\r", $value);
                    }
                    if(is_string($steps)) $steps = explode("\n", $steps);

                    $stepKey  = str_replace('step', '', strtolower($field));

                    $caseStep = array();
                    foreach($steps as $step)
                    {
                        $trimedStep = trim($step);
                        if(empty($trimedStep)) continue;
                        if(preg_match('/^(([0-9]+)\.[0-9]+)([.、]{1})/U', $step, $out) and ($field == 'stepDesc' or ($field == 'stepExpect' and isset($stepData[$row]['desc'][$out[1]]))))
                        {
                            $num     = $out[1];
                            $parent  = $out[2];
                            $sign    = $out[3];
                            $signbit = $sign == '.' ? 1 : 3;
                            $step    = trim(substr($step, strlen($num) + $signbit));
                            if(!empty($step)) $caseStep[$num]['content'] = $step;
                            $caseStep[$num]['type']    = 'item';
                            $caseStep[$parent]['type'] = 'group';
                        }
                        elseif(preg_match('/^([0-9]+)([.、]{1})/U', $step, $out) and ($field == 'stepDesc' or ($field == 'stepExpect' and isset($stepData[$row]['desc'][$out[1]]))))
                        {
                            $num     = $out[1];
                            $sign    = $out[2];
                            $signbit = $sign == '.' ? 1 : 3;
                            $step    = trim(substr($step, strpos($step, $sign) + $signbit));
                            if(!empty($step)) $caseStep[$num]['content'] = $step;
                            $caseStep[$num]['type'] = 'step';
                        }
                        elseif(isset($num))
                        {
                            if(!isset($caseStep[$num]['content'])) $caseStep[$num]['content'] = '';
                            if(!isset($caseStep[$num]['type']))    $caseStep[$num]['type']    = 'step';
                            $caseStep[$num]['content'] .= "\n" . $step;
                        }
                        else
                        {
                            if($field == 'stepDesc')
                            {
                                $num = 1;
                                $caseStep[$num]['content'] = $step;
                                $caseStep[$num]['type']    = 'step';
                            }
                            if($field == 'stepExpect' and isset($stepData[$row]['desc']))
                            {
                                end($stepData[$row]['desc']);
                                $num = key($stepData[$row]['desc']); $caseStep[$num]['content'] = $step;
                            }
                        }
                    }

                    unset($num);
                    unset($sign);
                    $stepVars += count($caseStep, COUNT_RECURSIVE) - count($caseStep);
                    $stepData[$row][$stepKey] = $caseStep;
                }

            }
        }
        return $stepData;
    }

    /**
     * Get modules for datatable.
     *
     * @param int $productID
     * @access public
     * @return void
     */
    public function getDatatableModules($productID)
    {
        $branches = $this->loadModel('branch')->getPairs($productID);
        $modules  = $this->loadModel('tree')->getOptionMenu($productID, 'case', '');
        if(count($branches) <= 1) return $modules;

        foreach($branches as $branchID => $branchName) $modules += $this->tree->getOptionMenu($productID, 'case', 0, $branchID);
        return $modules;
    }

    /**
     * Batch change scene.
     *
     * @param  array $caseIdList
     * @param  int   $sceneID
     * @access public
     * @return bool
     */
    public function batchChangeScene(array $caseIdList, int $sceneID): bool
    {
        if($sceneID < 0 || $sceneID > 16777215) return false; // The scene column's data type is mediumint unsigned and its range is 0-16777215.

        $caseIdList = array_filter($caseIdList);
        if(!$caseIdList) return false;

        $oldCases = $this->getByList($caseIdList, "scene != '{$sceneID}'");
        if(!$oldCases) return false;

        $case = new stdclass();
        $case->scene          = $sceneID;
        $case->lastEditedBy   = $this->app->user->account;
        $case->lastEditedDate = helper::now();

        $this->dao->update(TABLE_CASE)->data($case)->where('id')->in(array_keys($oldCases))->exec();
        if(dao::isError()) return false;

        $this->loadModel('action');

        foreach($oldCases as $oldCase)
        {
            $changes = common::createChanges($oldCase, $case);
            if($changes)
            {
                $actionID = $this->action->create('case', $oldCase->id, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
        }

        return !dao::isError();
    }

    /**
     * Build menu query.
     *
     * @param  int    $rootID
     * @param  int    $moduleID
     * @param  string $type
     * @param  int    $startScene
     * @param  string $branch
     * @access public
     * @return object
     */
    public function buildMenuQuery($rootID, $moduleID, $type, $startScene = 0, $branch = 'all')
    {
        /* Set the start module. */
        $startScenePath = '';
        if($startScene > 0)
        {
            $startScene = $this->dao->findById((int)$startScene)->from(VIEW_SCENECASE)->fetch();
            if($startScene) $startScenePath = $startScene->path . '%';
        }

        return $this->dao->select('*')->from(VIEW_SCENECASE)
            ->where('deleted')->eq(0)
            ->beginIF($rootID)->andWhere('product')->eq((int)$rootID)->fi()
            ->beginIF(intval($moduleID) > 0)->andWhere('module')->eq((int)$moduleID)->fi()
            ->beginIF($startScenePath)->andWhere('path')->like($startScenePath)->fi()
            ->beginIF($branch !== 'all' and $branch !== '' and $branch !== false)->andWhere('branch')->eq((int)$branch)->fi()
            ->andWhere('isCase')->eq(2)
            ->orderBy('grade desc, sort')
            ->get();
    }

    /**
     * Build operate browse scene menu.
     *
     * @param  object $scene
     * @access public
     * @return string
     */
    public function buildOperateBrowseSceneMenu($scene)
    {
        $canBeChanged = common::canBeChanged('case', $scene);
        if(!$canBeChanged) return '';

        $params = "sceneID=$scene->id";

        /* Generate params for editing scene. */
        $editParams = $params;
        if($this->app->tab == 'project')   $editParams .= "&projectID={$this->session->project}";
        if($this->app->tab == 'execution') $editParams .= "&executionID={$this->session->execution}";

        $menu  = $this->buildMenu('testcase', 'editScene',   $editParams, $scene, 'browse', 'edit',  '',          '', '', '', $this->lang->testcase->editScene);
        $menu .= $this->buildMenu('testcase', 'deleteScene', $params,     $scene, 'browse', 'trash', 'hiddenwin', '', '', '', $this->lang->testcase->deleteScene);

        return $menu;
    }

    /**
     * Search form add scene.
     *
     * @param  int    $productID
     * @param  array  $products
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  int    $projectID
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function buildSearchFormAddScene($productID, $products, $queryID, $actionURL, $projectID = 0,$moduleID = 0)
    {
        $product = ($this->app->tab == 'project' and empty($productID)) ? $products : array($productID => $products[$productID]) + array('all' => $this->lang->testcase->allProduct);
        $this->config->testcase->search['params']['product']['values'] = $product;

        $module = $this->loadModel('tree')->getOptionMenu($productID, 'case', 0);
        $scene  = $this->getSceneMenu($productID, $moduleID, $viewType = 'case', $startSceneID = 0,  0);

        if(!$productID)
        {
            $module = array();
            foreach($products as $id => $product) $module += $this->loadModel('tree')->getOptionMenu($id, 'case', 0);
        }

        $this->config->testcase->search['params']['module']['values'] = $module;
        $this->config->testcase->search['params']['parent']['values'] = $scene;
        $this->config->testcase->search['params']['lib']['values']    = $this->loadModel('caselib')->getLibraries();

        if($this->session->currentProductType == 'normal')
        {
            unset($this->config->testcase->search['fields']['branch']);
            unset($this->config->testcase->search['params']['branch']);
        }
        else
        {
            $productInfo = $this->loadModel('product')->getByID($productID);

            $this->config->testcase->search['fields']['branch']           = sprintf($this->lang->product->branch, $this->lang->product->branchName[$productInfo->type]);
            $this->config->testcase->search['params']['branch']['values'] = array('' => '', '0' => $this->lang->branch->main) + $this->loadModel('branch')->getPairs($productID, '', $projectID) + array('all' => $this->lang->branch->all);
        }

        if(!$this->config->testcase->needReview) unset($this->config->testcase->search['params']['status']['values']['wait']);
        $this->config->testcase->search['actionURL'] = $actionURL;
        $this->config->testcase->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->testcase->search);
    }

    /**
     * Build tree array.
     *
     * @param  array  $treeMenu
     * @param  array  $scenes
     * @param  object $scene
     * @param  string $sceneName
     * @access public
     * @return void
     */
    public function buildTreeArray(& $treeMenu, $scenes, $scene, $sceneName = '/')
    {
        $parentScenes = explode(',', $scene->path);
        foreach($parentScenes as $parentSceneID)
        {
            if(empty($parentSceneID)) continue;
            if(empty($scenes[$parentSceneID])) continue;

            $sceneName .= $scenes[$parentSceneID]->title . '/';
        }

        $sceneName  = rtrim($sceneName, '/');
        $sceneName .= "|$scene->id\n";

        if(isset($treeMenu[$scene->id]) and !empty($treeMenu[$scene->id]))
        {
            if(isset($treeMenu[$scene->parent]))
            {
                $treeMenu[$scene->parent] .= $sceneName;
            }
            else
            {
                $treeMenu[$scene->parent] = $sceneName;
            }
            $treeMenu[$scene->parent] .= $treeMenu[$scene->id];
        }
        else
        {
            if(isset($treeMenu[$scene->parent]) and !empty($treeMenu[$scene->parent]))
            {
                $treeMenu[$scene->parent] .= $sceneName;
            }
            else
            {
                $treeMenu[$scene->parent] = $sceneName;
            }
        }
    }

    /**
     * 根据 ID 获取一个场景。
     * Get a scene by id.
     *
     * @param  int    $sceneID
     * @access public
     * @return object
     */
    public function getSceneByID(int $sceneID): object|bool
    {
        return $this->dao->select('*')->from(TABLE_SCENE)->where('id')->eq($sceneID)->fetch();
    }

    /**
     * Create a scene.
     *
     * @access public
     * @return bool
     */
    public function createScene(object $scene): bool
    {
        $product = zget($scene, 'product', 0);
        $this->dao->insert(TABLE_SCENE)->data($scene)
            ->autoCheck()
            ->batchCheck($this->config->testcase->createscene->requiredFields, 'notempty')
            ->checkIF($product, 'title', 'unique', "product = '{$product}'")
            ->checkFlow()
            ->exec();
        if(dao::isError()) return false;

        $sceneID = $this->dao->lastInsertID();

        $scene->sort  = $sceneID;
        $scene->path  = ',' . $sceneID . ',';
        $scene->grade = 1;

        if(!empty($scene->parent))
        {
            $parent = $this->getSceneByID($scene->parent);
            if($parent)
            {
                $scene->path    = $parent->path . $sceneID . ',';
                $scene->grade   = ++$parent->grade;
                $scene->product = $parent->product;
                $scene->branch  = $parent->branch;
                $scene->module  = $parent->module;
            }
            else
            {
                $scene->parent = 0;
            }
        }

        $this->dao->update(TABLE_SCENE)->data($scene)->where('id')->eq($sceneID)->exec();
        if(dao::isError()) return false;

        $this->loadModel('action')->create('scene', $sceneID, 'Opened');

        return !dao::isError();
    }

    /**
     * Get all children id.
     *
     * @param  int $sceneID
     * @access public
     * @return object
     */
    public function getAllChildId($sceneID)
    {
        if($sceneID == 0) return array();

        $scene = $this->dao->findById((int)$sceneID)->from(VIEW_SCENECASE)->fetch();
        if(empty($scene)) return array();

        return $this->dao->select('id')->from(VIEW_SCENECASE)
            ->where('path')->like($scene->path . '%')
            ->andWhere('deleted')->eq(0)
            ->fetchPairs();
    }

    /**
     * Get scenes by id list and query string.
     *
     * @param  array  $sceneIdList
     * @param  string $query
     * @access public
     * @return array
     */
    public function getScenesByList(array $sceneIdList, string $query = ''): array
    {
        if(!$sceneIdList) return array();

        return $this->dao->select('*')->from(TABLE_SCENE)
            ->where('deleted')->eq('0')
            ->andWhere('id')->in($sceneIdList)
            ->beginIF($query)->andWhere($query)->fi()
            ->fetchAll('id');
    }

    /**
     * Get scene list include sub scenes and cases.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $moduleID
     * @param  string $caseType
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getSceneGroups(int $productID, string $branch = '', string $browseType = '', int $moduleID = 0, string $caseType = '', string $orderBy = 'id_desc', object $pager = null): array
    {
        $modules = $moduleID ? $this->loadModel('tree')->getAllChildId($moduleID) : '0';
        $scenes = $this->dao->select('*')->from(TABLE_SCENE)
            ->where('deleted')->eq('0')
            ->andWhere('product')->eq($productID)
            ->beginIF($branch !== 'all')->andWhere('branch')->eq($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->orderBy('grade_desc, sort_asc')
            ->fetchAll('id');

        $pager->recTotal = 0;

        if(!$scenes) return array();

        $cases = array();
        if($scenes && $browseType != 'onlyscene')
        {
            $stmt = $this->dao->select('t1.*')->from(TABLE_CASE)->alias('t1');

            if($this->app->tab == 'project') $stmt = $stmt->leftJoin(TABLE_PROJECTCASE)->alias('t2')->on('t1.id=t2.case');

            $caseList = $stmt->where('t1.deleted')->eq('0')
                ->andWhere('t1.scene')->ne(0)
                ->andWhere('t1.product')->eq($productID)
                ->beginIF($this->app->tab == 'project')->andWhere('t2.project')->eq($this->session->project)->fi()
                ->beginIF($branch !== 'all')->andWhere('t1.branch')->eq($branch)->fi()
                ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
                ->beginIF($this->cookie->onlyAutoCase)->andWhere('t1.auto')->eq('auto')->fi()
                ->beginIF(!$this->cookie->onlyAutoCase)->andWhere('t1.auto')->ne('unit')->fi()
                ->beginIF($caseType)->andWhere('t1.type')->eq($caseType)->fi()
                ->orderBy($orderBy)
                ->fetchAll('id');

            $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);
            $caseList = $this->loadModel('story')->checkNeedConfirm($caseList);
            $caseList = $this->appendData($caseList);
            foreach($caseList as $case) $cases[$case->scene][$case->id] = $case;
        }

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

            $scene->caseID     = $scene->id;
            $scene->bugs       = 0;
            $scene->results    = 0;
            $scene->caseFails  = 0;
            $scene->stepNumber = 0;
            $scene->isScene    = true;

            if(isset($cases[$id]))
            {
                foreach($cases[$id] as $case)
                {
                    $case->caseID  = $case->id;
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

        $pager->recTotal  = count($scenes);
        $pager->pageTotal = ceil($pager->recTotal / $pager->recPerPage);

        return array_slice($scenes, $pager->recPerPage * ($pager->pageID - 1), $pager->recPerPage);
    }

    /**
     * Get scene menu.
     *
     * @param  int    $rootID
     * @param  int    $moduleID
     * @param  string $type
     * @param  int    $startScene
     * @param  int    $branch
     * @param  int    $currentScene
     * @param  bool   $emptyMenu
     * @access public
     * @return array
     */
    public function getSceneMenu($rootID, $moduleID, $type = '', $startScene = 0, $branch = 0, $currentScene = 0, $emptyMenu = false)
    {
        if(empty($branch)) $branch = 0;

        /* If type of $branch is array, get scenes of these branches. */
        if(is_array($branch))
        {
            $scenes = array();
            foreach($branch as $branchID) $scenes[$branchID] = $this->getOptionMenu($rootID,$moduleID, $type, $startScene, $branchID,$currentScene);

            return $scenes;
        }

        if($type == 'line') $rootID = 0;

        $branches = array($branch => '');
        if($branch != 'all' and strpos('story|bug|case', $type) !== false)
        {
            $product = $this->loadModel('product')->getById($rootID);
            if($product and $product->type != 'normal')
            {
                $branchPairs = $this->loadModel('branch')->getPairs($rootID, 'all');
                $branches    = array($branch => $branchPairs[$branch]);
            }
            elseif($product and $product->type == 'normal')
            {
                $branches = array(0 => '');
            }
        }

        $treeMenu = array();
        foreach($branches as $branchID => $branch)
        {
            $scenes = array();
            $stmt   = $this->dbh->query($this->buildMenuQuery($rootID, $moduleID, $type, $startScene, $branchID));
            while($scene = $stmt->fetch())
            {
                if ($scene->id != $currentScene) $scenes[$scene->id] = $scene;
            }

            foreach($scenes as $scene)
            {
                $branchName = (!empty($product) and $product->type != 'normal' and $scene->branch === BRANCH_MAIN) ? $this->lang->branch->main : $branch;

                $this->buildTreeArray($treeMenu, $scenes, $scene, (empty($branchName)) ? '/' : "/$branchName/");
            }
        }

        ksort($treeMenu);
        $topMenu = @array_shift($treeMenu);
        $topMenu = explode("\n", trim((string)$topMenu));
        $lastMenu[] = '/';
        foreach($topMenu as $menu)
        {
            if(!strpos($menu, '|')) continue;
            list($label, $sceneID) = explode('|', $menu);
            $lastMenu[$sceneID]    = $label;
        }

        /* Attach empty option. */
        if($emptyMenu) $lastMenu['null'] = $this->lang->null;

        return $lastMenu;
    }

    /**
     * Get scene name.
     *
     * @param  array $moduleIdList
     * @param  bool  $allPath
     * @param  bool  $branchPath
     * @access public
     * @return array
     */
    public function getScenesName($moduleIdList, $allPath = true, $branchPath = false)
    {
        if(!$allPath) return $this->dao->select('id, title')->from(VIEW_SCENECASE)->where('id')->in($moduleIdList)->andWhere('deleted')->eq(0)->fetchPairs('id', 'title');

        $modules    = $this->dao->select('id, title, path, branch')->from(VIEW_SCENECASE)->where('id')->in($moduleIdList)->andWhere('deleted')->eq(0)->fetchAll('path');
        $allModules = $this->dao->select('id, title')->from(VIEW_SCENECASE)->where('id')->in(join(array_keys($modules)))->andWhere('deleted')->eq(0)->fetchPairs('id', 'title');

        $branchIDList = array();
        $modulePairs  = array();
        foreach($modules as $module)
        {
            $paths = explode(',', trim($module->path, ','));
            $moduleName = '';
            foreach($paths as $path) $moduleName .= '/' . $allModules[$path];
            $modulePairs[$module->id] = $moduleName;

            if($module->branch) $branchIDList[$module->branch] = $module->branch;
        }

        if(!$branchPath) return $modulePairs;

        $branchs  = $this->dao->select('id, title')->from(VIEW_SCENECASE)->where('id')->in($branchIDList)->andWhere('deleted')->eq(0)->fetchALL('id');
        foreach($modules as $module)
        {
            if(isset($modulePairs[$module->id]))
            {
                $branchName = isset($branchs[$module->branch]) ? '/' . $branchs[$module->branch]->name : '';
                $modulePairs[$module->id] = $branchName . $modulePairs[$module->id];
            }
        }

        return $modulePairs;
    }

    /**
     * Substr string.
     *
     * @param  string $text
     * @param  int    $length
     * @access public
     * @return string
     */
    public function istrcut($text, $length)
    {
        return (mb_strlen($text, 'utf8') > $length) ? mb_substr($text, 0, $length, 'utf8').'...' : $text;
    }

    /**
     * Print table head.
     *
     * @param  object $col
     * @param  string $orderBy
     * @param  string $vars
     * @param  bool   $checkBox
     * @access public
     * @return string
     */
    public function printHead($col, $orderBy, $vars, $checkBox = true)
    {
        $id = $col->id;
        if($col->show)
        {
            $fixed = $col->fixed == 'no' ? 'true' : 'false';
            $width = is_numeric($col->width) ? "{$col->width}px" : $col->width;
            $title = isset($col->title) ? "title='$col->title'" : '';
            $title = (isset($col->name) and $col->name) ? "title='$col->name'" : $title;
            if($id == 'id' and (int)$width < 90) $width = '120px';

            $align = $id == 'actions' ? 'text-center' : '';
            $align = in_array($id, array('budget', 'teamCount', 'estimate', 'consume', 'consumed', 'left')) ? 'text-right' : $align;

            $style  = '';
            $data   = '';
            $data  .= "data-width='$width'";
            $style .= "width:$width;";

            if(isset($col->minWidth))
            {
                $data  .= "data-minWidth='{$col->minWidth}px'";
                $style .= "min-width:{$col->minWidth}px;";
            }

            if(isset($col->maxWidth))
            {
                $data  .= "data-maxWidth='{$col->maxWidth}px'";
                $style .= "max-width:{$col->maxWidth}px;";
            }

            if(isset($col->pri)) $data .= "data-pri='{$col->pri}'";
            if($col->title == $this->lang->testcase->title)
            {
                echo "<th data-flex='$fixed' $data style='$style' class='c-$id $align' title='".$this->lang->testcase->generalTitle."'>";
            }
            else
            {
                echo "<th data-flex='$fixed' $data style='$style' class='c-$id $align' $title>";
            }

            if($id == 'actions')
            {
                echo $this->lang->actions;
            }
            else
            {
                if($id == 'id' && $checkBox) echo "<div class='checkbox-primary check-all' title='".$this->lang->selectAll."'><label></label></div>";
                if($col->title == $this->lang->testcase->title)
                {
                    echo $this->lang->testcase->generalTitle;
                }
                else
                {
                    echo $col->title;
                }
            }

            echo '</th>';
        }
    }

    /**
     * 编辑一个场景和它的子场景。
     * Update a scene and its children.
     *
     * @param  object $scene
     * @access public
     * @return bool
     */
    public function updateScene(object $scene): bool
    {
        $sceneID  = $scene->id;
        $oldScene = $this->getSceneByID($sceneID);
        if(!$oldScene) return false;

        $this->dao->update(TABLE_SCENE)->data($scene)
            ->autoCheck()
            ->batchCheck($this->config->testcase->editscene->requiredFields, 'notempty')
            ->check('title', 'unique', "product = '{$scene->product}' AND id != '{$sceneID}'")
            ->where('id')->eq($sceneID)
            ->checkFlow()
            ->exec();
        if(dao::isError()) return false;

        if(isset($scene->parent) && $scene->parent != $oldScene->parent)
        {
            if($scene->parent)
            {
                $parent = $this->getSceneByID($scene->parent);

                $scene->path    = $parent->path . $sceneID . ',';
                $scene->grade   = ++$parent->grade;
                $scene->product = $parent->product;
                $scene->branch  = $parent->branch;
                $scene->module  = $parent->module;
            }
            else
            {
                $scene->path  = ',' . $sceneID . ',';
                $scene->grade = 1;
            }

            $this->dao->update(TABLE_SCENE)->data($scene)->where('id')->eq($sceneID)->exec();
            $this->dao->update(TABLE_SCENE)->set("path = REPLACE(path, '{$oldScene->path}', '{$scene->path}')")
                ->where('id')->ne($sceneID)
                ->andWhere('path')->like("{$oldScene->path}%")
                ->exec();
        }

        $changes = common::createChanges($oldScene, $scene);
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('scene', $sceneID, 'edited');
            $this->action->logHistory($actionID, $changes);
        }

        return true;
    }

    /**
     * Get xmind file content.
     *
     * @param  string $fileName
     * @access public
     * @return string
     */
    public function getXmindImport($fileName)
    {
        $xmlNode  = simplexml_load_file($fileName);
        $testData = $this->xmlToArray($xmlNode);

        return json_encode($testData);
    }

    /**
     * 保存 xmind 文件内容。
     * Save xmind file content to database.
     *
     * @access public
     * @return array
     */
    public function saveXmindImport(): array
    {
        $this->dao->begin();

        $sceneList = array_combine(array_map(function($scene){return $scene['tmpId'];}, $this->post->sceneList), array_map(function($scene){return (array)$scene;}, $this->post->sceneList));
        foreach($sceneList as $scene)
        {
            $result = $this->testcaseTao->saveScene($scene, $sceneList);
            if($result['result'] == 'fail')
            {
                $this->dao->rollBack();
                return $result;
            }
        }

        foreach($this->post->testcaseList as $testcase)
        {
            $testcase = (object)$testcase;
            $result   = $this->saveTestcase($testcase);
            if($result['result'] == 'fail')
            {
                $this->dao->rollBack();
                return $result;
            }
        }

        $this->dao->commit();

        return array('result' => 'success', 'message' => $this->lang->saveSuccess);
    }

    /**
     * 导入 xmind 时保存其中的测试用例。
     * Save the test cases in xmind when importing it.
     *
     * @param  object $testcase
     * @param  array  $sceneIdList
     * @access public
     * @return array
     */
    public function saveTestcase(object $testcase, array $sceneIdList): array
    {
        $scene  = 0;
        $nodeID = $testcase->tmpPId;
        if(isset($sceneIdList[$nodeID]['id'])) $scene = $sceneIdList[$nodeID]['id'];

        $case = new stdclass();
        $case->scene   = $scene;
        $case->module  = $testcase->module;
        $case->product = $testcase->product;
        $case->branch  = $testcase->branch;
        $case->title   = $testcase->name;
        $case->pri     = $testcase->pri;
        $case->version = 1;

        $case = $this->processCaseSteps($case, $testcase);

        $oldCase = false;
        $caseID  = (int)zget($testcase, 'id', 0);
        if($caseID) $oldCase = $this->fetchBaseInfo($caseID);
        if(empty($oldCase))
        {
            $case->type       = 'feature';
            $case->status     = 'normal';
            $case->openedBy   = $this->app->user->account;
            $case->openedDate = helper::now();

            $this->create($case);
        }
        else
        {
            $case->id             = $caseID;
            $case->version        = $oldCase->version + 1;
            $case->lastEditedBy   = $this->app->user->account;
            $case->lastEditedDate = helper::now();

            $this->update($case, $oldCase);
        }

        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError(true));

        return array('result' => 'success', 'message' => 1, 'testcaseID' => $caseID);
    }

    /**
     * 把导入 xmind 时 POST 提交的 stepList 属性转换为创建用例和编辑用例表单提交的 steps、expects 和 stepType 属性。
     * Convert the stepList attribute submitted by POST when importing xmind into the steps, expects, and stepType attributes submitted by the form for creating and editing use cases.
     *
     * @param  object $case
     * @param  object $testcase
     * @access protected
     * @return object
     */
    protected function processCaseSteps(object $case, object $testcase): object
    {
        $case->steps    = array();
        $case->expects  = array();
        $case->stepType = array();

        if(isset($testcase->stepList))
        {
            $index     = 1;
            $postSteps = $testcase->stepList;
            $stepList  = array_combine(array_map(function($step){return $step['tmpId'];}, $postSteps), array_map(function($step){return (object)$step;}, $postSteps));
            foreach($stepList as $step)
            {
                if(isset($stepList[$step->tmpPId]))
                {
                    $parentStep  = $stepList[$step->tmpPId];
                    $step->index = $parentStep->index . '.' . (count($parentStep->children) + 1);
                    $parentStep->children[] = $step;
                }
                else
                {
                    $step->index    = $index;
                    $step->children = array();
                    $index++;
                }

                $case->steps[$step->index]    = zget($step, 'desc', '');
                $case->expects[$step->index]  = zget($step, 'expect', '');
                $case->stepType[$step->index] = zget($step, 'type', 'item');
            }
        }

        return $case;
    }

    /**
     * Get export data.
     *
     * @param  int $productID
     * @param  int $moduleID
     * @param  int $branch
     * @access public
     * @return array
     */
    public function getXmindExport($productID, $moduleID, $branch)
    {
        $caseList   = $this->getCaseByProductAndModule($productID, $moduleID);
        $stepList   = $this->getStepByProductAndModule($productID, $moduleID);
        $sceneInfo  = $this->getSceneByProductAndModule($productID, $moduleID);
        $moduleList = $this->getModuleByProductAndModel($productID, $moduleID, $branch);

        $config = $this->getXmindConfig();

        return array(
                'caseList'  =>$caseList,
                'stepList'  =>$stepList,
                'sceneMaps' =>$sceneInfo['sceneMaps'],
                'topScenes' =>$sceneInfo['topScenes'],
                'moduleList'=>$moduleList,
                'config'    =>$config
            );
    }

    /**
     * Get module by product.
     *
     * @param  int $productID
     * @param  int $moduleID
     * @param  int $branch
     * @access public
     * @return array
     */
    function getModuleByProductAndModel($productID, $moduleID, $branch)
    {
        $moduleList = array();

        if($moduleID > 0)
        {
            $module = $this->loadModel('tree')->getByID($moduleID);

            $moduleList[$module->id] = $module->name;
        }
        else
        {
            $moduleList = $this->loadModel('tree')->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0, ($branch === 'all' or !isset($branches[$branch])) ? 0 : $branch);

            unset($moduleList['0']);
        }

        return $moduleList;
    }

    /**
     * Get case by product and module.
     *
     * @param  int $productID
     * @param  int $moduleID
     * @access public
     * @return array
     */
    function getCaseByProductAndModule($productID, $moduleID)
    {
        $fields = "t2.id as productID,"
            . "t2.`name` as productName,"
            . "t3.id as moduleID,"
            . "t3.`name` as moduleName,"
            . "t4.id as sceneID,"
            . "t4.title as sceneName,"
            . "t1.id as testcaseID,"
            . "t1.title as `name`,"
            . "t1.pri";

        $caseList = $this->dao->select($fields)->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->leftJoin(TABLE_MODULE)->alias('t3')->on('t1.module = t3.id')
            ->leftJoin(TABLE_SCENE)->alias('t4')->on('t1.scene = t4.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.product')->eq($productID)
            ->beginIF($moduleID > 0)->andWhere('t1.module')->eq($moduleID)->fi()
            ->fetchAll();

        return $caseList;
    }

    /**
     * Get step by product and module.
     *
     * @param  int $productID
     * @param  int $moduleID
     * @access public
     * @return array
     */
    function getStepByProductAndModule($productID, $moduleID)
    {
        $fields = "t1.id as testcaseID,"
            . "t2.id as stepID,"
            . "t2.type,"
            . "t2.parent as parentID,"
            . "t2.`desc`,"
            . "t2.expect";

        $stepList = $this->dao->select($fields)->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_CASESTEP)->alias('t2')->on('t1.id = t2.`case` and t1.version = t2.version')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.product')->eq($productID)
            ->andWhere('t2.id')->gt('0')
            ->beginIF($moduleID > 0)->andWhere('t1.module')->eq($moduleID)->fi()
            ->fetchAll();

        return $stepList;
    }

    /**
     * Get scene by product and module.
     *
     * @param  int $productID
     * @param  int $moduleID
     * @access public
     * @return array
     */
    function getSceneByProductAndModule($productID, $moduleID)
    {
        $sceneList = $this->dao->select('id as sceneID, title as sceneName, path, parent as parentID, product as productID, module as moduleID')
            ->from(TABLE_SCENE)
            ->where('deleted')->eq(0)
            ->andWhere('product')->eq($productID)
            ->beginIF($moduleID > 0)->andWhere('module')->eq($moduleID)->fi()
            ->fetchAll();

        $sceneMaps = array();
        $topScenes = array();
        foreach($sceneList as $one)
        {
            if($one->parentID == 0) $topScenes[] = $one;

            $sceneMaps[$one->sceneID] = $one;
        }

        return array('sceneMaps'=>$sceneMaps,'topScenes'=>$topScenes);
    }

    /**
     * Check config.
     *
     * @param  string $str
     * @access public
     * @return bool
     */
    function checkConfigValue($str)
    {
        return preg_match("/^[a-zA-Z]{1,10}$/",$str);
    }

    /**
     * 存储 xmind 配置。
     * Save xmind config.
     *
     * @param  array  $configList
     * @access public
     * @return array
     */
    function saveXmindConfig(array $configList): array
    {
        $this->dao->begin();

        $this->dao->delete()->from(TABLE_CONFIG)
            ->where('owner')->eq($this->app->user->account)
            ->andWhere('module')->eq('testcase')
            ->andWhere('section')->eq('xmind')
            ->exec();

        foreach($configList as $one)
        {
            $config = new stdclass();

            $config->module  = 'testcase';
            $config->section = 'xmind';
            $config->key     = $one['key'];
            $config->value   = $one['value'];
            $config->owner   = $this->app->user->account;

            $this->dao->insert(TABLE_CONFIG)->data($config)->autoCheck()->exec();

            if($this->dao->isError())
            {
                $this->dao->rollBack();
                return array('result' => 'fail', 'message' => $this->dao->getError(true));
            }
        }

        $this->dao->commit();

        return array("result" => "success", "message" => 1);
    }

    /**
     * Get xmind config.
     *
     * @access public
     * @return array
     */
    function getXmindConfig()
    {
        $configItems = $this->dao->select("`key`,value")->from(TABLE_CONFIG)
            ->where('owner')->eq($this->app->user->account)
            ->andWhere('module')->eq('testcase')
            ->andWhere('section')->eq('xmind')
            ->fetchAll();

        $config = array();
        foreach($configItems as $item) $config[$item -> key] = $item -> value;

        if(!isset($config['module'])) $config['module'] = 'M';
        if(!isset($config['scene']))  $config['scene']  = 'S';
        if(!isset($config['case']))   $config['case']   = 'C';
        if(!isset($config['pri']))    $config['pri']    = 'P';
        if(!isset($config['group']))  $config['group']  = 'G';

        return $config;
    }

    /**
     * Convert xml to array.
     *
     * @param  object $xml
     * @param  array  $options
     * @access public
     * @return array
     */
    function xmlToArray($xml, $options = array())
    {
        $defaults = array(
            'namespaceRecursive' => false, // Get XML doc namespaces recursively
            'removeNamespace'    => true, // Remove namespace from resulting keys
            'namespaceSeparator' => ':', // Change separator to something other than a colon
            'attributePrefix'    => '', // Distinguish between attributes and nodes with the same name
            'alwaysArray'        => array(), // Array of XML tag names which should always become arrays
            'autoArray'          => true, // Create arrays for tags which appear more than once
            'textContent'        => 'text', // Key used for the text content of elements
            'autoText'           => true, // Skip textContent key if node has no attributes or child nodes
            'keySearch'          => false, // (Optional) search and replace on tag and attribute names
            'keyReplace'         => false, // (Optional) replace values for above search values
        );

        $options        = array_merge($defaults, $options);
        $namespaces     = $xml->getDocNamespaces($options['namespaceRecursive']);
        $namespaces[''] = null; // Add empty base namespace

        /* Get attributes from all namespaces. */
        $attributesArray = array();
        foreach($namespaces as $prefix => $namespace)
        {
            if($options['removeNamespace']) $prefix = '';

            foreach($xml->attributes($namespace) as $attributeName => $attribute)
            {
                // (Optional) replace characters in attribute name
                if($options['keySearch']) $attributeName = str_replace($options['keySearch'], $options['keyReplace'], $attributeName);

                $attributeKey = $options['attributePrefix'] . ($prefix ? $prefix . $options['namespaceSeparator'] : '') . $attributeName;
                $attributesArray[$attributeKey] = (string) $attribute;
            }
        }

        // Get child nodes from all namespaces
        $tagsArray = array();
        foreach($namespaces as $prefix => $namespace)
        {
            if($options['removeNamespace']) $prefix = '';

            foreach($xml->children($namespace) as $childXml)
            {
                // Recurse into child nodes
                $childArray      = $this->xmlToArray($childXml, $options);
                $childTagName    = key($childArray);
                $childProperties = current($childArray);

                // Replace characters in tag name
                if($options['keySearch']) $childTagName = str_replace($options['keySearch'], $options['keyReplace'], $childTagName);

                // Add namespace prefix, if any
                if($prefix) $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;

                if(!isset($tagsArray[$childTagName]))
                {
                    // Only entry with this key
                    // Test if tags of this type should always be arrays, no matter the element count
                    $tagsArray[$childTagName] = in_array($childTagName, $options['alwaysArray'], true) || !$options['autoArray'] ? array($childProperties) : $childProperties;
                }
                elseif(is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName]) === range(0, count($tagsArray[$childTagName]) - 1))
                {
                    // Key already exists and is integer indexed array
                    $tagsArray[$childTagName][] = $childProperties;
                }
                else
                {
                    // Key exists so convert to integer indexed array with previous value in position 0
                    $tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
                }
            }
        }

        // Get text content of node
        $textContentArray = array();
        $plainText = trim((string) $xml);
        if($plainText !== '') $textContentArray[$options['textContent']] = $plainText;

        // Stick it all together
        $propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || $plainText === '' ? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;

        // Return node as array
        return array($xml->getName() => $propertiesArray);
    }

    /**
     * Append case fails.
     *
     * @param  object $case
     * @param  string $from
     * @param  int    $taskID
     * @access public
     * @return object
     */
    public function appendCaseFails(object $case, string $from, int $taskID): object
    {
        $caseFails = $this->dao->select('COUNT(*) AS count')->from(TABLE_TESTRESULT)
            ->where('caseResult')->eq('fail')
            ->andwhere('`case`')->eq($case->id)
            ->beginIF($from == 'testtask')->andwhere('`run`')->eq($taskID)->fi()
            ->fetch('count');
        $case->caseFails = $caseFails;
        return $case;
    }

    /**
     * 添加步骤。
     * Append steps.
     *
     * @param  array  $steps
     * @param  int    $count
     * @access public
     * @return array
     */
    public function appendSteps(array $steps, int $count = 0): array
    {
        if($count == 0) $count = $this->config->testcase->defaultSteps;
        if(count($steps) < $count)
        {
            $step = new stdclass();
            $step->step   = '';
            $step->desc   = '';
            $step->expect = '';
            $step->type   = 'step';
            for($i = count($steps) + 1; $i <= $count; $i ++)
            {
                $data = clone $step;
                $data->name    = (string)$i;
                $steps[] = $data;
            }
        }
        return $steps;
    }
}

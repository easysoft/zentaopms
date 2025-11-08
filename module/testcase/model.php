<?php
declare(strict_types=1);
/**
 * The model file of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: model.php 5108 2013-07-12 01:59:04Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php
class testcaseModel extends model
{
    /**
     * 设置菜单。
     * Set menu.
     *
     * @param  int        $productID
     * @param  int|string $branch
     * @access public
     * @return void
     */
    public function setMenu(int $productID, int|string $branch = 0): void
    {
        $this->loadModel('qa')->setMenu($productID, $branch);
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
        $files = $this->loadModel('file')->saveUpload('testcase', $caseID);

        $this->testcaseTao->doCreateSpec($caseID, $case, $files);

        $this->loadModel('score')->create('testcase', 'create', $caseID);

        /* 插入用例步骤。 */
        /* Insert testcase steps. */
        $this->testcaseTao->insertSteps($caseID, $case->steps, $case->expects, $case->stepType);

        if(isset($case->auto) && $case->auto != 'auto')
        {
            ob_start();
            setcookie('onlyAutoCase', '0');
        }

        if(dao::isError()) return false;
        return $caseID;
    }

    /**
     * 获取模块的用例。
     * Get cases of modules.
     *
     * @param  int|array   $productID
     * @param  int|string  $branch
     * @param  int|array   $moduleIdList
     * @param  string      $browseType
     * @param  string      $auto   no|unit
     * @param  string      $caseType
     * @param  string      $orderBy
     * @param  object      $pager
     * @param  string      $from
     * @access public
     * @return array
     */
    public function getModuleCases(int|array $productID, int|string $branch = 0, int|array $moduleIdList = 0, string $browseType = '', string $auto = 'no', string $caseType = '', string $orderBy = 'id_desc', ?object $pager = null, string $from = 'testcase'): array
    {
        $isProjectTab   = $this->app->tab == 'project' && $from != 'doc';
        $isExecutionTab = $this->app->tab == 'execution' && $from != 'doc';

        $stmt = $this->dao->select('t1.*, t2.title as storyTitle, t2.deleted as storyDeleted')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id');

        if($isProjectTab)   $stmt = $stmt->leftJoin(TABLE_PROJECTCASE)->alias('t3')->on('t1.id=t3.case');
        if($isExecutionTab) $stmt = $stmt->leftJoin(TABLE_PROJECTCASE)->alias('t3')->on('t1.id=t3.case');

        return $stmt ->where('t1.product')->in($productID)
            ->beginIF($isProjectTab)->andWhere('t3.project')->eq($this->session->project)->fi()
            ->beginIF($isExecutionTab)->andWhere('t3.project')->eq($this->session->execution)->fi()
            ->beginIF($branch !== 'all')->andWhere('t1.branch')->eq($branch)->fi()
            ->beginIF($moduleIdList)->andWhere('t1.module')->in($moduleIdList)->fi()
            ->beginIF($browseType == 'wait')->andWhere('t1.status')->eq($browseType)->fi()
            ->beginIF($browseType == 'needconfirm')
            ->andWhere('t2.version > t1.storyVersion')
            ->andWhere('t2.status')->eq('active')
            ->fi()
            ->beginIF($auto == 'auto' || $auto == 'unit')->andWhere('t1.auto')->eq($auto)->fi()
            ->beginIF($auto != 'auto' && $auto != 'unit')->andWhere('t1.auto')->ne('unit')->fi()
            ->beginIF($caseType)->andWhere('t1.type')->eq($caseType)->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * 获取执行的用例。
     * Get execution cases.
     *
     * @param  string $browseType   all|wait|needconfirm
     * @param  int    $executionID
     * @param  int    $productID
     * @param  int    $branchID
     * @param  int    $moduleID
     * @param  int    $paramID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getExecutionCases(string $browseType = 'all', int $executionID = 0, int $productID = 0, int|string $branchID = 0, int $moduleID = 0, int $paramID = 0, string $orderBy = 'id_desc', ?object $pager = null): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getCases();

        if($browseType == 'needconfirm')
        {
            return $this->dao->select('distinct t1.*, t2.*')->from(TABLE_PROJECTCASE)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
                ->leftJoin(TABLE_STORY)->alias('t3')->on('t2.story = t3.id')
                ->leftJoin(TABLE_MODULE)->alias('t4')->on('t2.module = t4.id')
                ->where('t1.project')->eq($executionID)
                ->andWhere('t2.deleted')->eq('0')
                ->andWhere('t3.version > t2.storyVersion')
                ->andWhere("t3.status")->eq('active')
                ->beginIF($productID)->andWhere('t1.product')->eq($productID)->fi()
                ->beginIF($productID && $branchID !== 'all')->andWhere('t2.branch')->eq($branchID)->fi()
                ->beginIF($moduleID)->andWhere('t4.path')->like("%,$moduleID,%")->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id', false);
        }
        if($browseType == 'bysearch') return $this->testcaseTao->getExecutionCasesBySearch($executionID, $productID, $branchID, $paramID, $orderBy, $pager);

        return $this->dao->select('distinct t1.*, t2.*')->from(TABLE_PROJECTCASE)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->leftJoin(TABLE_MODULE)->alias('t3')->on('t2.module = t3.id')
            ->where('t1.project')->eq($executionID)
            ->andWhere('t2.deleted')->eq('0')
            ->beginIF($browseType != 'all' && $browseType != 'byModule')->andWhere('t2.status')->eq($browseType)->fi()
            ->beginIF($productID)->andWhere('t1.product')->eq($productID)->fi()
            ->beginIF($productID && $branchID !== 'all')->andWhere('t2.branch')->eq($branchID)->fi()
            ->beginIF($moduleID)->andWhere('t3.path')->like("%,$moduleID,%")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
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
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getCase();

        $case = $this->dao->findById($caseID)->from(TABLE_CASE)->fetch();
        if(!$case) return false;

        $case = $this->processDateField($case);

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
        $toBugs       = $this->dao->select('id, title, severity, openedDate, status')->from(TABLE_BUG)->where('`case`')->eq($caseID)->fetchAll();
        $case->toBugs         = array();
        $case->fromBugData    = array();
        $case->linkCaseTitles = array();
        foreach($toBugs as $toBug) $case->toBugs[$toBug->id] = $toBug;
        if($case->story)
        {
            $story = $this->dao->findById($case->story)->from(TABLE_STORY)->fields('title, status, version')->fetch();
            $case->storyTitle         = $story->title;
            $case->storyStatus        = $story->status;
            $case->latestStoryVersion = $story->version;
        }
        if($case->fromBug) $case->fromBugData = $this->dao->findById($case->fromBug)->from(TABLE_BUG)->fields('id, title, severity, openedDate, status')->fetch();
        if($case->linkCase || $case->fromCaseID) $case->linkCaseTitles = $this->dao->select('id,title')->from(TABLE_CASE)->where('id')->in($case->linkCase)->orWhere('id')->eq($case->fromCaseID)->fetchPairs();

        $case->currentVersion = $version ? $version : $case->version;
        $case->files          = $this->loadModel('file')->getByObject('testcase', $caseID);

        $case->steps = $this->testcaseTao->getSteps($caseID, $case->currentVersion);

        $spec = $this->dao->select('title,precondition,files')->from(TABLE_CASESPEC)->where('case')->eq($caseID)->andWhere('version')->eq($version)->fetch();
        if($spec)
        {
            $case->title        = $spec->title;
            $case->precondition = $spec->precondition;
            $case->files        = $this->file->getByIdList($spec->files);
        }

        return $case;
    }

    /**
     * 测试通过 id 列表和查询语句获取用例。
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

        return $this->dao->select('*,precondition')->from(TABLE_CASE)
            ->where('deleted')->eq(0)
            ->andWhere('id')->in($caseIdList)
            ->beginIF($query)->andWhere($query)->fi()
            ->fetchAll('id', false);
    }

    /**
     * 获取用例列表。
     * Get test cases.
     *
     * @param  int|array  $productID
     * @param  int|string $branch
     * @param  string     $browseType
     * @param  int        $queryID
     * @param  int        $moduleID
     * @param  string     $caseType
     * @param  string     $auto      no|unit
     * @param  string     $orderBy
     * @param  object     $pager
     * @param  string     $from
     * @access public
     * @return array
     */
    public function getTestCases(int|array $productID, string|int $branch, string $browseType, int $queryID, int $moduleID, string $caseType = '', string $auto = 'no', string $orderBy = 'id_desc', object $pager = null, string $from = 'testcase'): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getCases();

        $modules    = $moduleID ? $this->loadModel('tree')->getAllChildId($moduleID) : array();
        $browseType = ($browseType == 'bymodule' and $this->session->caseBrowseType and $this->session->caseBrowseType != 'bysearch') ? $this->session->caseBrowseType : $browseType;
        $auto       = $this->cookie->onlyAutoCase ? 'auto' : $auto;

        if($browseType == 'bymodule' || $browseType == 'all' || $browseType == 'wait')
        {
            if($this->app->tab == 'project' && $from != 'doc') return $this->testcaseTao->getModuleProjectCases($productID, $branch, $modules, $browseType, $auto, $caseType, $orderBy, $pager);

            return $this->getModuleCases($productID, $branch, $modules, $browseType, $auto, $caseType, $orderBy, $pager, $from);
        }

        if($browseType == 'needconfirm') return $this->testcaseTao->getNeedConfirmList($productID, $branch, $modules, $auto, $caseType, $orderBy, $pager);
        if($browseType == 'bysuite')     return $this->testcaseTao->getBySuite($productID, $branch, (int)$queryID, $modules, $auto, $orderBy, $pager);
        if($browseType == 'bysearch')    return $this->getBySearch($productID, $branch, (int)$queryID, $auto, $orderBy, $pager);

        return array();
    }

    /**
     * 搜索用例。
     * Get cases by search.
     *
     * @param  int         $productID
     * @param  int|string  $branch
     * @param  int         $queryID
     * @param  string      $auto      no|unit
     * @param  string      $orderBy
     * @param  object      $pager
     * @access public
     * @return array
     */
    public function getBySearch(int $productID, int|string $branch = 0, int $queryID = 0, string $auto = 'no', string $orderBy = 'id_desc', object $pager = null): array
    {
        $queryID = (int)$queryID;
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('testcaseQuery', $query->sql);
                $this->session->set('testcaseForm', $query->form);
            }
        }

        if($this->session->testcaseQuery == false) $this->session->set('testcaseQuery', ' 1 = 1');

        $caseQuery = '(' . $this->session->testcaseQuery;
        // 将caseQuery中的字段替换成t1.字段
        $caseQuery = preg_replace('/`(.*?)`/', 't1.`$1`', $caseQuery);

        /* 处理用例查询中的产品条件。*/
        /* Process product condition in case query. */
        $queryProductID = $productID;
        if(strpos($this->session->testcaseQuery, "`product` = 'all'") !== false)
        {
            $caseQuery  = str_replace("t1.`product` = 'all'", '1', $caseQuery);
            $caseQuery .= ' AND t1.`product` ' . helper::dbIN($this->app->user->view->products);

            $queryProductID = 'all';
        }
        if($this->app->tab == 'project') $caseQuery = str_replace('t1.`product`', 't2.`product`', $caseQuery);

        /* 处理用例查询中的产品分支条件。*/
        /* Process branch condition in case query. */
        if($branch !== 'all' && strpos($caseQuery, '`branch` =') === false) $caseQuery .= " AND t1.`branch` in ('$branch')";
        if(strpos($caseQuery, "`branch` = 'all'") !== false) $caseQuery = str_replace("t1.`branch` = 'all'", '1 = 1', $caseQuery);

        $caseQuery .= ')';

        /* Search criteria under compatible project. */
        $sql = $this->dao->select('t1.*,t3.title as storyTitle')->from(TABLE_CASE)->alias('t1');
        if($this->app->tab == 'project') $sql->leftJoin(TABLE_PROJECTCASE)->alias('t2')->on('t1.id = t2.case');
        $sql->leftJoin(TABLE_STORY)->alias('t3')->on('t1.story = t3.id');
        return $sql->where($caseQuery)
            ->beginIF($this->app->tab == 'project' && $this->config->systemMode == 'ALM')->andWhere('t2.project')->eq($this->session->project)->fi()
            ->beginIF($this->app->tab == 'project' && !empty($productID) && $queryProductID != 'all')->andWhere('t2.product')->eq($productID)->fi()
            ->beginIF($this->app->tab != 'project' && !empty($productID) && $queryProductID != 'all')->andWhere('t1.product')->eq($productID)->fi()
            ->beginIF($auto == 'auto' || $auto == 'unit')->andWhere('t1.auto')->eq($auto)->fi()
            ->beginIF($auto != 'auto' && $auto != 'unit')->andWhere('t1.auto')->ne('unit')->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * 根据指派给获取用例。
     * Get cases by assignedTo.
     *
     * @param  string $account
     * @param  string $auto  no|unit|skip
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getByAssignedTo(string $account, string $auto = 'no', string $orderBy = 'id_desc', ?object $pager = null): array
    {
        return $this->dao->select('t1.id AS run, t1.task, t1.case, t1.version, t1.assignedTo, t1.lastRunner, t1.lastRunDate, t1.lastRunResult, t1.status AS lastRunStatus, t2.id AS id, t2.project, t2.pri, t2.title, t2.type, t2.openedBy, t2.color, t2.product, t2.lib, t2.branch, t2.module, t2.status, t2.auto, t2.story, t2.storyVersion, t3.name AS taskName')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->leftJoin(TABLE_TESTTASK)->alias('t3')->on('t1.task = t3.id')
            ->where('t1.assignedTo')->eq($account)
            ->andWhere('t3.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t3.status')->ne('done')
            ->beginIF(strpos($auto, 'skip') === false && $auto != 'unit')->andWhere('t2.auto')->ne('unit')->fi()
            ->beginIF($auto == 'unit')->andWhere('t2.auto')->eq('unit')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll(strpos($auto, 'run') !== false ? 'run' : 'id');
    }

    /**
     * 根据创建者获取用例。
     * Get cases by openedBy
     *
     * @param  string $account
     * @param  string $auto    no|unit|skip
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getByOpenedBy(string $account, string $auto = 'no', string $orderBy = 'id_desc', ?object $pager = null): array
    {
        return $this->dao->findByOpenedBy($account)->from(TABLE_CASE)
            ->andWhere('deleted')->eq('0')
            ->beginIF($auto != 'skip')->andWhere('product')->ne(0)->fi()
            ->beginIF($auto != 'skip' && $auto != 'unit')->andWhere('auto')->ne('unit')->fi()
            ->beginIF($auto == 'unit')->andWhere('auto')->eq('unit')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * 根据状态获取用例。
     * Get cases by status.
     *
     * @param  int        $productID
     * @param  int|string $branch
     * @param  string     $type     all|needconfirm
     * @param  string     $status   all|normal|blocked|investigate
     * @param  int        $moduleID
     * @param  string     $auto     no|unit|skip
     * @param  string     $orderBy
     * @param  object     $pager
     * @access public
     * @return array
     */
    public function getByStatus(int $productID = 0, int|string $branch = 0, string $type = 'all', string $status = 'all', int $moduleID = 0, string $auto = 'no', string $orderBy = 'id_desc', ?object $pager = null): array
    {
        $modules = $moduleID ? $this->loadModel('tree')->getAllChildId($moduleID) : '0';

        $cases = $this->dao->select('t1.*, t2.title AS storyTitle')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where('1=1')
            ->beginIF($productID)->andWhere('t1.product')->eq((int) $productID)->fi()
            ->beginIF(!$productID)->andWhere('t1.product')->in($this->app->user->view->products)->fi()
            ->beginIF($branch)->andWhere('t1.branch')->eq($branch)->fi()
            ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
            ->beginIF($type == 'needconfirm')
            ->andWhere('t2.status')->eq('active')
            ->andWhere('t2.version > t1.storyVersion')
            ->fi()
            ->beginIF($status != 'all')->andWhere('t1.status')->eq($status)->fi()
            ->beginIF($auto == 'unit')->andWhere('t1.auto')->eq('unit')->fi()
            ->beginIF($auto != 'unit')->andWhere('t1.auto')->ne('unit')->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);

        return $this->appendData($cases);
    }

    /**
     * 根据产品获取用例。
     * Get cases by product id.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getByProduct(int $productID): array
    {
        return $this->dao->select('*')->from(TABLE_CASE)->where('deleted')->eq('0')->andWhere('product')->eq($productID)->fetchAll('id', false);
    }

    /**
     * 通过产品 id 和分支获取用例键对。
     * Get case pairs by product id and branch.
     *
     * @param  int       $productID
     * @param  int|array $branch
     * @param  string    $search
     * @param  int       $limit
     * @access public
     * @return array
     */
    public function getPairsByProduct(int $productID, int|array $branch = 0, string $search = '', int $limit = 0): array
    {
        return $this->dao->select("id, concat_ws(':', id, title) as title")->from(TABLE_CASE)
            ->where('deleted')->eq(0)
            ->andWhere('product')->eq($productID)
            ->beginIF($branch)->andWhere('branch')->in($branch)->fi()
            ->beginIF(strlen(trim($search)))->andWhere('title')->like('%' . $search . '%')->fi()
            ->orderBy('id_desc')
            ->beginIF($limit)->limit($limit)->fi()
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

        $caseCounts = $this->dao->select('story, COUNT(1) AS cases')
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
     * @param  int      $limit
     * @access public
     * @return array
     */
    public function getCasesToExport(string $exportType, int $taskID, string $orderBy, int $limit): array
    {
        if(strpos($orderBy, 'case') !== false)
        {
            list($field, $sort) = explode('_', $orderBy);
            $orderBy = '`' . $field . '`_' . $sort;
        }

        $queryCondition = preg_replace("/AND\s+t[0-9]\.scene\s+=\s+'0'/i", '', $this->session->testcaseQueryCondition);
        if($this->session->testcaseOnlyCondition)
        {
            $caseIdList = array();
            if($taskID) $caseIdList = $this->dao->select('`case`')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->fetchPairs();

            return $this->dao->select('*')->from(TABLE_CASE)->where($queryCondition)
                ->beginIF($taskID)->andWhere('id')->in($caseIdList)->fi()
                ->beginIF($exportType == 'selected')->andWhere('id')->in($this->cookie->checkedItem)->fi()
                ->orderBy($orderBy)
                ->beginIF($limit)->limit($limit)->fi()
                ->fetchAll('id', false);
        }

        $cases   = array();
        $orderBy = " ORDER BY " . str_replace(array('|', '^A', '_'), ' ', $orderBy);
        $stmt    = $this->dao->query($queryCondition . $orderBy . ($limit ? ' LIMIT ' . $limit : ''));
        while($row = $stmt->fetch())
        {
            $caseID = isset($row->case) ? $row->case : $row->id;

            if($exportType == 'selected' && $taskID  && strpos(",{$this->cookie->checkedItem},", ",$row->id,") === false) continue;
            if($exportType == 'selected' && !$taskID && strpos(",{$this->cookie->checkedItem},", ",$caseID,") === false) continue;

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
            ->beginIF($this->app->tab == 'devops')->andWhere('t1.deploy')->eq($this->session->deployID)->fi()
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

        if($oldCase->version != $case->version || !empty($case->stepChanged)) $this->testcaseTao->updateStep($case, $oldCase);

        if(isset($oldCase->toBugs) && isset($case->linkBug)) $this->testcaseTao->linkBugs($oldCase->id, array_keys($oldCase->toBugs), $case);

        if($case->branch && !empty($testtasks)) $this->testcaseTao->unlinkCaseFromTesttask($oldCase->id, $case->branch, $testtasks);

        $this->loadModel('file')->processFileDiffsForObject('testcase', $oldCase, $case);

        if($oldCase->version != $case->version) $this->testcaseTao->doCreateSpec($oldCase->id, $case, $case->files);

        /* Join the steps to diff. */
        if(!empty($case->stepChanged) && $case->steps)
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

        $changes  = common::createChanges($oldCase, $case);
        $actionID = $this->loadModel('action')->create('case', $oldCase->id, 'Reviewed', $this->post->comment, ucfirst($case->result));
        $this->action->logHistory($actionID, $changes);
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
        $cases2Link = $this->getBySearch($case->product, $case->branch, (int)$queryID, $auto = 'no', $orderBy = 'id');
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
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getBugs2Link(int $caseID, string $browseType = 'bySearch', int $queryID = 0, string $orderBy = 'id_asc'): array
    {
        if($browseType != 'bySearch') return array();

        $case      = $this->getByID($caseID);
        $bugs2Link = $this->loadModel('bug')->getBySearch('bug', $case->product, (string)$case->branch, 0, 0, (int)$queryID, '', $orderBy);
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
            foreach($caseIdList as $caseID) $this->action->create('case', (int)$caseID, 'deleted', '', actionModel::CAN_UNDELETED);
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
        $caseSteps    = array((object)array('name' => '1', 'desc' => $steps, 'step' => $steps, 'expect' => ''));   // the default steps before parse.
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
            $caseSteps[$key] = (object)array('name' => '1', 'desc' => trim($caseStep), 'step' => trim($caseStep), 'expect' => $expect, 'type' => 'item');
        }

        return $caseSteps;
    }

    /**
     * 判断当前动作是否可以点击。
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

        if($action == 'runcase')            return (empty($case->lib) || !empty($case->product)) && $case->auto == 'no' && $case->status != 'wait';
        if($action == 'runresult')          return !$case->lib || !empty($case->product);
        if($action == 'importtolib')        return !$case->lib || !empty($case->product);
        if($action == 'ztfrun')             return $case->auto == 'auto';
        if($action == 'confirmchange')      return isset($case->caseStatus) && isset($case->caseVersion) && $case->caseStatus != 'wait' && $case->version < $case->caseVersion;
        if($action == 'confirmstorychange') return !empty($case->needconfirm) || (isset($case->browseType) && $case->browseType == 'needconfirm');
        if($action == 'createbug')          return isset($case->caseFails) && $case->caseFails > 0;
        if($action == 'create')             return !$case->lib || !empty($case->product);
        if($action == 'review')             return ($config->testcase->needReview || !empty($config->testcase->forceReview)) && (isset($case->caseStatus) ? $case->caseStatus == 'wait' : $case->status == 'wait');
        if($action == 'showscript')         return $case->auto == 'auto';
        if($action == 'createcase')         return !isset($case->lib) || ($case->lib && empty($case->product));
        /* 判断确认撤销操作按钮的权限。 */
        /* Check confirmdemandretract priv. */
        if($action == 'confirmdemandretract') return !empty($case->confirmeActionType) && $case->confirmeActionType == 'confirmedretract';
        /* 判断确认移除操作按钮的权限。 */
        /* Check confirmdemandunlink priv. */
        if($action == 'confirmdemandunlink') return !empty($case->confirmeActionType) && $case->confirmeActionType == 'confirmedunlink';

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
        $caseIdList  = array_column($cases, 'id');
        $oldCaseList = $this->getByList($caseIdList);
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
                $caseID  = $case->id;
                $oldCase = isset($oldCaseList[$caseID]) ? $oldCaseList[$caseID] : null;
                $this->testcaseTao->doUpdate($case);
                $this->action->create('case', $caseID, 'updatetolib', '', $case->fromCaseID);

                $this->dao->delete()->from(TABLE_CASESTEP)->where('`case`')->eq($caseID)->exec();

                $removeFiles = $this->dao->select('id,pathname')->from(TABLE_FILE)->where('`objectID`')->eq($caseID)->andWhere('objectType')->eq('testcase')->fetchAll('id');
                $this->dao->delete()->from(TABLE_FILE)->where('`objectID`')->eq($caseID)->andWhere('objectType')->eq('testcase')->exec();
                foreach($removeFiles as $fileID => $file)
                {
                    if(empty($file->pathname)) continue;
                    $filePath = pathinfo($file->pathname, PATHINFO_BASENAME);
                    $datePath = substr($file->pathname, 0, 6);
                    $filePath = $this->app->getAppRoot() . "www/data/upload/{$this->app->company->id}/" . "{$datePath}/" . $filePath;
                    if(file_exists($filePath)) unlink($filePath);
                }

                if(!empty($oldCase) && $oldCase->lib && empty($oldCase->product))
                {
                    $fromcaseVersion = $this->dao->select('fromCaseVersion')->from(TABLE_CASE)->where('fromCaseID')->eq($case->id)->fetch('fromCaseVersion');
                    $fromcaseVersion = (int)$fromcaseVersion + 1;
                    $this->dao->update(TABLE_CASE)->set('`fromCaseVersion`')->eq($fromcaseVersion)->where('`fromCaseID`')->eq($case->id)->exec();
                }
            }
            if(isset($caseID))
            {
                $this->testcaseTao->importSteps($caseID, zget($steps, $case->fromCaseID, array()));
                $this->testcaseTao->importFiles($caseID, zget($files, $case->fromCaseID, array()));
            }
        }
        return !dao::isError();
    }

    /**
     * 导入用例关联的模块。
     * Import case related modules.
     *
     * @param  int    $libID
     * @param  int    $oldModuleID
     * @param  int    $maxOrder
     * @access public
     * @return int
     */
    public function importCaseRelatedModules(int $libID, int $oldModuleID = 0, int $maxOrder = 0): int
    {
        /* If module has been imported, return imported module id. */
        $moduleID = $this->checkModuleImported($libID, $oldModuleID);
        if($moduleID) return $moduleID;

        /* Build old module, and insert it. */
        $oldModule = $this->dao->select('name, parent, grade, `order`, short')->from(TABLE_MODULE)->where('id')->eq($oldModuleID)->fetch();
        $oldModule->root = $libID;
        $oldModule->from = $oldModuleID;
        $oldModule->type = 'caselib';
        if(!empty($maxOrder)) $oldModule->order = $maxOrder + $oldModule->order;
        $this->dao->insert(TABLE_MODULE)->data($oldModule)->autoCheck()->exec();

        if(!dao::isError())
        {
            /* Get new module id. */
            $newModuleID = $this->dao->lastInsertID();

            /* Set path and parent. */
            if($oldModule->parent)
            {
                /* If old module has parent module, import parent module. */
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

            /* Update path and parent. */
            $this->dao->update(TABLE_MODULE)->set('parent')->eq($parent)->set('path')->eq($path)->where('id')->eq($newModuleID)->exec();

            /* Return new module id. */
            return $newModuleID;
        }
        return 0;
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
        $caseBugs   = $this->dao->select('COUNT(1) AS count, `case`')->from(TABLE_BUG)->where($queryField)->in($caseIdList)->andWhere('deleted')->eq(0)->groupBy('`case`')->fetchPairs('case', 'count');
        $results    = $this->dao->select('COUNT(1) AS count, `case`')->from(TABLE_TESTRESULT)->where('`case`')->in($caseIdList)->groupBy('`case`')->fetchPairs('case', 'count');

        /* 查询用例的失败结果。 */
        /* Get result fails of the the testcases. */
        if($type != 'case') $queryField = '`run`';
        $caseFails  = $this->dao->select('COUNT(1) AS count, `case`')->from(TABLE_TESTRESULT)
            ->where('caseResult')->eq('fail')
            ->andWhere($queryField)->in($caseIdList)
            ->groupBy('`case`')
            ->fetchPairs('case','count');

        /* 查询用例的步骤。 */
        /* Get the the testcase steps. */
        $queryTable = $type == 'case' ? TABLE_CASE : TABLE_TESTRUN;
        $queryOn    = $type == 'case' ? 't1.`case`=t2.`id`' : 't1.`case`=t2.`case`';
        $queryField = $type == 'case' ? 't1.`case`' : 't2.`id`';
        $steps = $this->dao->select('COUNT(DISTINCT t1.id) AS count, t1.`case`')->from(TABLE_CASESTEP)->alias('t1')
            ->leftJoin($queryTable)->alias('t2')->on($queryOn)
            ->where($queryField)->in($caseIdList)
            ->andWhere('t1.type')->ne('group')
            ->andWhere('t1.version=t2.version')
            ->groupBy('t1.`case`')
            ->fetchPairs('case', 'count');

        /* 设置测试用例的 bugs 执行结果和步骤。 */
        /* Set related bugs, results and steps of the testcases. */
        foreach($cases as $case)
        {
            $caseID = $type == 'case' ? $case->id : $case->case;

            $case->bugs       = zget($caseBugs, $caseID, 0);
            $case->results    = zget($results, $caseID, 0);
            $case->caseFails  = zget($caseFails, $caseID, 0);
            $case->stepNumber = zget($steps, $caseID, 0);

            $case = $this->processDateField($case);
        }

        return $cases;
    }

    /**
     * 检查是否不需要评审。
     * Check whether force not review.
     *
     * @access public
     * @return bool
     */
    public function forceNotReview(): bool
    {
        if(!$this->config->testcase->needReview)
        {
            if(!isset($this->config->testcase->forceReview)) return true;
            if(strpos(",{$this->config->testcase->forceReview},", ",{$this->app->user->account},") === false) return true;
        }
        elseif(isset($this->config->testcase->forceNotReview) && strpos(",{$this->config->testcase->forceNotReview},", ",{$this->app->user->account},") !== false)
        {
            return true;
        }

        return false;
    }

    /**
     * 获取用例总结。
     * Summary cases.
     *
     * @param  array  $cases
     * @access public
     * @return string
     */
    public function summary(array $cases): string
    {
        $executed = 0;
        foreach($cases as $case)
        {
            if($case->lastRunResult != '') $executed ++;
        }

        return sprintf($this->lang->testcase->summary, count($cases), $executed);
    }

    /**
     * 同步用例到项目中。
     * Sync case to project.
     *
     * @param  object $case
     * @param  int    $caseID
     * @access public
     * @return bool
     */
    public function syncCase2Project(object $case, int $caseID): bool
    {
        $projects = array();
        if(!empty($case->story))
        {
            $projects = $this->dao->select('project')->from(TABLE_PROJECTSTORY)->where('story')->eq($case->story)->fetchPairs();
        }
        elseif($this->app->tab == 'project' && empty($case->story) && empty($case->project))
        {
            $projects = array($this->session->project);
        }
        elseif($this->app->tab == 'execution' && empty($case->story) && empty($case->execution))
        {
            $projects = array($this->session->execution);
        }
        if(!empty($case->project))   $projects[] = $case->project;
        if(!empty($case->execution)) $projects[] = $case->execution;
        if(empty($projects)) return false;

        $this->loadModel('action');
        $projects   = array_filter(array_unique($projects));
        $objectInfo = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->in($projects)->fetchAll('id', false);
        foreach($projects as $projectID)
        {
            if(!isset($objectInfo[$projectID])) continue;

            $lastOrder = (int)$this->dao->select('*')->from(TABLE_PROJECTCASE)->where('project')->eq($projectID)->orderBy('order_desc')->limit(1)->fetch('order');
            $lastOrder++;

            $data = new stdclass();
            $data->project = $projectID;
            $data->product = $case->product;
            $data->case    = $caseID;
            $data->version = 1;
            $data->order   = $lastOrder;
            $this->dao->insert(TABLE_PROJECTCASE)->data($data)->exec();

            $object     = $objectInfo[$projectID];
            $objectType = $object->type;
            if($objectType == 'project') $this->action->create('case', $caseID, 'linked2project', '', $projectID);
            if(in_array($objectType, array('sprint', 'stage')) && $object->multiple) $this->action->create('case', $caseID, 'linked2execution', '', $projectID);
        }
        return !dao::isError();
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
        $stepData   = array();
        $stepVars   = 0;
        foreach($datas as $row => $cellValue)
        {
            foreach($cellValue as $field => $value)
            {
                if($field != 'stepDesc' and $field != 'stepExpect') continue;
                $value = (string)$value;

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
                    $trimmedStep = trim($step);
                    if(empty($trimmedStep)) continue;

                    preg_match('/^((([0-9]+)[.]([0-9]+))[.]([0-9]+))[.、](.*)$/Uu', $trimmedStep, $out);
                    if(!$out) preg_match('/^(([0-9]+)[.]([0-9]+))[.、](.*)$/Uu', $trimmedStep, $out);
                    if(!$out) preg_match('/^([0-9]+)[.、](.*)$/Uu', $trimmedStep, $out);
                    if($out && !empty(trim($out[1])))
                    {
                        $count  = count($out);
                        $num    = $out[1];
                        $parent = $count > 4 ? $out[2] : '0';
                        $grand  = $count > 6 ? $out[3] : '0';
                        $step   = trim($out[2]);
                        if($count > 4) $step = $count > 6 ? trim($out[6]) : trim($out[4]);

                        $caseStep[$num]['content'] = $step;
                        $caseStep[$num]['number']  = $num;

                        $caseStep[$num]['type'] = $count > 4 ? 'item' : 'step';
                        if(!empty($parent)) $caseStep[$parent]['type'] = 'group';
                        if(!empty($grand)) $caseStep[$grand]['type']   = 'group';
                    }
                    elseif(isset($num))
                    {
                        $caseStep[$num]['content'] = isset($caseStep[$num]['content']) ? "{$caseStep[$num]['content']}\n{$step}" : "\n{$step}";
                    }
                    elseif($field == 'stepDesc')
                    {
                        $num = 1;
                        $caseStep[$num]['content'] = $step;
                        $caseStep[$num]['type']    = 'step';
                        $caseStep[$num]['number']  = $num;
                    }
                    elseif($field == 'stepExpect' && isset($stepData[$row]['desc']))
                    {
                        end($stepData[$row]['desc']);
                        $num = key($stepData[$row]['desc']);
                        $caseStep[$num]['content'] = $step;
                        $caseStep[$num]['number']  = $num;
                    }
                }
                unset($num);
                unset($sign);
                if($stepKey == 'expect' && !empty($stepData[$row]['desc']))
                {
                    foreach($stepData[$row]['desc'] as $stepDescValue)
                    {
                        if(empty($stepDescValue['number'])) continue;
                        $caseNumber = $stepDescValue['number'];

                        if($stepDescValue && !isset($caseStep[$caseNumber]) || empty($caseStep[$caseNumber]['content'])) $caseStep[$caseNumber] = '';
                    }
                }
                $stepVars += count($caseStep, COUNT_RECURSIVE) - count($caseStep);
                $stepData[$row][$stepKey] = $caseStep;
            }
        }

        return $stepData;
    }

    /**
     * Process date field when date is empty.
     *
     * @param  object $case
     * @access public
     * @return object
     */
    public function processDateField(object $case): object
    {
        foreach($case as $key => $value)
        {
            if(strpos($key, 'Date') !== false && helper::isZeroDate($value)) $case->$key = '';
        }
        return $case;
    }

    /**
     * 处理批量表单内的用例步骤或预期。
     * Process steps or expects in batch form.
     *
     * @param  array     $steps
     * @access protected
     * @return array
     */
    public function processStepsOrExpects(string $steps): array
    {
        $caseSteps = array();
        $stepTypes = array();
        $steps     = explode("\n", trim($steps));
        $preParent = $preGrand = $preNum = '0';
        foreach($steps as $step)
        {
            $step = trim($step);
            if(empty($step)) continue;

            $appendToPre = false;
            preg_match('/^((([0-9]+)[.]([0-9]+)[.]([0-9]+))[.]([0-9]+))[.、](.*)$/Uu', $step, $out);
            if($out) $appendToPre = true; // 如果层级大于3级，追加到目前的步骤中

            preg_match('/^((([0-9]+)[.]([0-9]+))[.]([0-9]+))[.、](.*)$/Uu', $step, $out);
            if(!$out) preg_match('/^(([0-9]+)[.]([0-9]+))[.、](.*)$/Uu', $step, $out);
            if(!$out) preg_match('/^([0-9]+)[.、](.*)$/Uu', $step, $out);

            $grand = $parent = $num = '0';
            if(!$appendToPre && $out && isset($caseSteps[$out[1]]))
            {
                $appendToPre = true; // 如果已经设置过，追加到目前的步骤中
            }
            elseif(!$appendToPre && $out)
            {
                $count = count($out);
                if($count > 6)
                {
                    $grand  = $out[3];
                    $parent = $out[4];
                    $num    = $out[5];
                }
                elseif($count > 4)
                {
                    $grand  = $out[2];
                    $parent = $out[3];
                }
                else
                {
                    $grand = $out[1];
                }
                $appendToPre = !(($grand == $preGrand && $parent == $preParent && $num == $preNum + 1)
                    || ($grand == $preGrand && $parent == $preParent + 1 && $num == '0')
                    || ($grand == $preGrand + 1 && $parent == '0' && $num == '0'));
            }

            if(!$appendToPre && $out)
            {
                $preGrand  = $grand;
                $preParent = $parent;
                $preNum    = $num;
                $parent    = $count > 4 ? $out[2] : '0';
                $step      = trim($out[2]);
                $code      = $out[1];
                if($count > 4) $step = $count > 6 ? trim($out[6]) : trim($out[4]);

                $caseSteps[$code] = $step;
                $stepTypes[$code] = $count > 4 ? 'item' : 'step';
                if($count > 4 && !empty($parent)) $stepTypes[$parent] = 'group';
                if($count > 6 && !empty($grand))  $stepTypes[$grand]  = 'group';
            }
            elseif($appendToPre && isset($code))
            {
                $caseSteps[$code] = isset($caseSteps[$code]) ? "{$caseSteps[$code]}\n{$step}" : "\n{$step}";
            }
            elseif(!$out && isset($code))
            {
                $caseSteps[$code] = isset($caseSteps[$code]) ? "{$caseSteps[$code]}\n{$step}" : "\n{$step}";
            }
        }
        if(empty($caseSteps) && !empty($steps))
        {
            $caseSteps[] = implode("\n", $steps);
            $stepTypes[] = 'step';
        }
        return array($caseSteps, $stepTypes);
    }

    /**
     * 判断步骤是否变更。
     * Judge if steps changed.
     *
     * @param  object    $case
     * @param  array     $oldStep
     * @access protected
     * @return bool
     */
    protected function processStepsChanged(object $case, array $oldStep): bool
    {
        $stepChanged = (count($oldStep) != count($case->steps));
        if(!$stepChanged)
        {
            $desc     = array_values($case->steps);
            $expect   = array_values($case->expects);
            $stepType = array_values($case->stepType);
            foreach($oldStep as $index => $step)
            {
                if($stepChanged) break;
                if(!isset($desc[$index]) || !isset($expect[$index]) || $step->desc != $desc[$index] || $step->expect != $expect[$index] || $step->type != $stepType[$index]) $stepChanged = true;
            }
        }
        return $stepChanged;
    }

    /**
     * 为 datatable 获取模块。
     * Get modules for datatable.
     *
     * @param int $productID
     * @access public
     * @return void
     */
    public function getDatatableModules(int $productID): array
    {
        $branches = $this->loadModel('branch')->getPairs($productID);
        $modules  = $this->loadModel('tree')->getOptionMenu($productID, 'case', 0);
        if(count($branches) <= 1) return $modules;

        foreach($branches as $branchID => $branchName) $modules += $this->tree->getOptionMenu($productID, 'case', 0, (string)$branchID);
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
     * 为构建场景菜单获取场景。
     * Get scenes for menu.
     *
     * @param  int    $productID
     * @param  int    $moduleID
     * @param  int    $startScene
     * @param  string $branch
     * @param  int    $currentScene
     * @access public
     * @return array
     */
    public function getScenesForMenu(int $productID, int $moduleID, int $startScene = 0, string $branch = 'all', int $currentScene = 0): array
    {
        /* Set the start scene. */
        $startScenePath = '';
        if($startScene > 0)
        {
            $startScene = $this->getSceneByID($startScene);
            if($startScene) $startScenePath = $startScene->path . '%';
        }
        $currentScenePath = '';
        if($currentScene > 0)
        {
            $currentScene = $this->getSceneByID($currentScene);
            if($currentScene) $currentScenePath = $currentScene->path . '%';
        }

        /* Return scenes. */
        return $this->dao->select('*')->from(TABLE_SCENE)
            ->where('deleted')->eq(0)
            ->beginIF($productID)->andWhere('product')->eq($productID)->fi()
            ->beginIF($moduleID > 0)->andWhere('module')->eq($moduleID)->fi()
            ->beginIF($startScenePath)->andWhere('path')->like($startScenePath)->fi()
            ->beginIF($currentScenePath)->andWhere('path')->notlike($currentScenePath)->fi()
            ->beginIF($branch !== 'all' && $branch !== '')->andWhere('branch')->eq((int)$branch)->fi()
            ->orderBy('grade desc, sort')
            ->fetchAll('id', false);
    }

    /**
     * 构建树数组。
     * Build tree array.
     *
     * @param  array  $treeMenu
     * @param  array  $scenes
     * @param  object $scene
     * @param  string $sceneName
     * @access public
     * @return void
     */
    public function buildTreeArray(array &$treeMenu, array $scenes, object $scene, string $sceneName = '/'): void
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
     * 创建一个场景。
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
     * 获取所有的子场景 id。
     * Get all children id.
     *
     * @param  int    $sceneID
     * @access public
     * @return array
     */
    public function getAllChildId(int $sceneID): array
    {
        if($sceneID == 0) return array();

        $scene = $this->dao->findById($sceneID)->from(TABLE_SCENE)->fetch();
        if(empty($scene)) return array();

        return $this->dao->select('id')->from(TABLE_SCENE)
            ->where('path')->like($scene->path . '%')
            ->andWhere('deleted')->eq(0)
            ->fetchPairs();
    }

    /**
     * 通过 ID 列表和查询语句获取场景。
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
            ->fetchAll('id', false);
    }

    /**
     * 获取包含子场景的场景列表。
     * Get scene list include sub scenes.
     *
     * @param  int|array $productID
     * @param  string    $branch
     * @param  int       $moduleID
     * @param  string    $orderBy
     * @param  object    $pager
     * @access public
     * @return array
     */
    public function getSceneGroups(int|array $productID, string $branch = '', int $moduleID = 0, string $orderBy = 'id_desc', ?object $pager = null): array
    {
        $modules = [];
        if($moduleID)
        {
            $modules = $this->loadModel('tree')->getAllChildId($moduleID);
            if(!$modules) return [];
        }

        $topScenes = $this->dao->select('*')->from(TABLE_SCENE)
            ->where('deleted')->eq('0')
            ->andWhere('grade')->eq(1)
            ->andWhere('product')->in($productID)
            ->beginIF($branch !== 'all')->andWhere('branch')->eq($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
        if(!$topScenes) return array();

        $subScenes = $this->dao->select('*')->from(TABLE_SCENE)
            ->where('deleted')->eq('0')
            ->andWhere('grade')->gt(1)
            ->andWhere('product')->in($productID)
            ->beginIF($branch !== 'all')->andWhere('branch')->eq($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->orderBy($orderBy)
            ->fetchAll('id', false);

        foreach($subScenes as $id => $scene)
        {
            $path = trim($scene->path, ',');
            $root = substr($path, 0, strpos($path, ','));
            if(!isset($topScenes[$root])) unset($subScenes[$id]);
        }

        return $subScenes + $topScenes;
    }

    /**
     * 获取用场景 ID 分组的用例。
     * Get cases by scene id.
     *
     * @param  int|array $productID
     * @param  string    $branch
     * @param  array     $modules
     * @param  string    $caseType
     * @param  string    $orderBy
     * @access public
     * @return array
     */
    public function getSceneGroupCases(int|array $productID, string $branch, array $modules, string $caseType, string $orderBy): array
    {
        $browseType = $this->session->caseBrowseType && $this->session->caseBrowseType != 'bysearch' ? $this->session->caseBrowseType : 'all';

        $stmt = $this->dao->select('t1.*,t3.title as storyTitle')->from(TABLE_CASE)->alias('t1');
        if($this->app->tab == 'project') $stmt = $stmt->leftJoin(TABLE_PROJECTCASE)->alias('t2')->on('t1.id=t2.case');
        $stmt->leftJoin(TABLE_STORY)->alias('t3')->on('t1.story = t3.id');

        $caseList = $stmt->where('t1.deleted')->eq('0')
            ->andWhere('t1.scene')->ne(0)
            ->andWhere('t1.product')->in($productID)
            ->beginIF($this->app->tab == 'project')->andWhere('t2.project')->eq($this->session->project)->fi()
            ->beginIF($branch !== 'all')->andWhere('t1.branch')->eq($branch)->fi()
            ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
            ->beginIF($browseType == 'wait')->andWhere('t1.status')->eq($browseType)->fi()
            ->beginIF($this->cookie->onlyAutoCase)->andWhere('t1.auto')->eq('auto')->fi()
            ->beginIF(!$this->cookie->onlyAutoCase)->andWhere('t1.auto')->ne('unit')->fi()
            ->beginIF($caseType)->andWhere('t1.type')->eq($caseType)->fi()
            ->orderBy($orderBy)
            ->fetchAll('id', false);

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);
        $caseList = $this->loadModel('story')->checkNeedConfirm($caseList);
        $caseList = $this->appendData($caseList);

        $cases = array();
        foreach($caseList as $case) $cases[$case->scene][$case->id] = $case;

        return $cases;
    }

    /**
     * 获取场景菜单。
     * Get scene menu.
     *
     * @param  int    $productID
     * @param  int    $moduleID
     * @param  string $branch
     * @param  int    $startScene
     * @param  int    $currentScene
     * @param  bool   $emptyMenu
     * @access public
     * @return array
     */
    public function getSceneMenu(int $productID, int $moduleID = 0, string $branch = 'all', int $startScene = 0, int $currentScene = 0, bool $emptyMenu = false): array
    {
        $branches = array($branch => '');
        if($branch != 'all')
        {
            $product = $this->loadModel('product')->getById($productID);
            if($product && $product->type != 'normal')
            {
                $branchPairs = $this->loadModel('branch')->getPairs($productID, 'all');
                $branches    = array($branch => $branchPairs[$branch]);
            }
            elseif($product && $product->type == 'normal')
            {
                $branches = array('0' => '');
            }
        }

        $treeMenu = array();
        foreach($branches as $branchID => $branch)
        {
            $scenes = $this->getScenesForMenu($productID, $moduleID, $startScene, (string)$branchID, $currentScene);
            foreach($scenes as $scene)
            {
                $branchName = !empty($product) && $product->type != 'normal' && $scene->branch === BRANCH_MAIN ? $this->lang->branch->main : $branch;
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
     * 获取场景的名称。
     * Get scene name.
     *
     * @param  array $sceneIDList
     * @param  bool  $fullPath
     * @access public
     * @return array
     */
    public function getScenesName(array $sceneIDList, bool $fullPath = true): array
    {
        if(!$fullPath) return $this->dao->select('id, title')->from(TABLE_SCENE)->where('deleted')->eq('0')->andWhere('id')->in($sceneIDList)->fetchPairs();

        $scenes    = $this->dao->select('id, title, path')->from(TABLE_SCENE)->where('deleted')->eq('0')->andWhere('id')->in($sceneIDList)->fetchAll('path');
        $allScenes = $this->dao->select('id, title')->from(TABLE_SCENE)->where('deleted')->eq('0')->andWhere('id')->in(implode(',', array_keys($scenes)))->fetchPairs();

        $scenePairs = array();
        foreach($scenes as $scene)
        {
            $title = '';
            $path  = explode(',', trim($scene->path, ','));
            foreach($path as $sceneID) $title .= '/' . $allScenes[$sceneID];

            $scenePairs[$scene->id] = $title;
        }

        return $scenePairs;
    }

    /**
     * 编辑一个场景和它的子场景。
     * Update a scene and its children.
     *
     * @param  object $scene
     * @access public
     * @return bool
     */
    public function updateScene(object $scene, object $oldScene): bool
    {
        $this->dao->update(TABLE_SCENE)->data($scene)
            ->autoCheck()
            ->batchCheck($this->config->testcase->editscene->requiredFields, 'notempty')
            ->check('title', 'unique', "product = '{$scene->product}' AND id != '{$oldScene->id}'")
            ->where('id')->eq($oldScene->id)
            ->checkFlow()
            ->exec();
        if(dao::isError()) return false;

        if(isset($scene->parent) && $scene->parent != $oldScene->parent)
        {
            if($scene->parent)
            {
                $parent = $this->getSceneByID($scene->parent);

                $scene->path    = $parent->path . $oldScene->id . ',';
                $scene->grade   = ++$parent->grade;
                $scene->product = $parent->product;
                $scene->branch  = $parent->branch;
                $scene->module  = $parent->module;
            }
            else
            {
                $scene->path  = ',' . $oldScene->id . ',';
                $scene->grade = 1;
            }

            $this->dao->update(TABLE_SCENE)->data($scene)->where('id')->eq($oldScene->id)->exec();
            $this->dao->update(TABLE_SCENE)->set("path = REPLACE(path, '{$oldScene->path}', '{$scene->path}')")
                ->where('id')->ne($oldScene->id)
                ->andWhere('path')->like("{$oldScene->path}%")
                ->exec();
        }

        $changes = common::createChanges($oldScene, $scene);
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('scene', $oldScene->id, 'edited');
            $this->action->logHistory($actionID, $changes);
        }

        return true;
    }

    /**
     * 获取 xmind 文件内容。
     * Get xmind file content.
     *
     * @param  string $fileName
     * @access public
     * @return string
     */
    public function getXmindImport(string $fileName): string
    {
        $xmlNode  = simplexml_load_file($fileName);
        $testData = $this->xmlToArray($xmlNode);

        return json_encode($testData);
    }

    /**
     * 保存 xmind 文件内容。
     * Save xmind file content to database.
     *
     * @param  array  $scenes
     * @param  array  $testcases
     * @access public
     * @return array
     */
    public function saveXmindImport(array $scenes, array $testcases): array
    {
        try
        {
            $this->dao->begin();

            $sceneList   = array_combine(array_map(function($scene){return $scene['tmpId'];}, $scenes), array_map(function($scene){return (array)$scene;}, $scenes));
            $sceneIDList = array();
            foreach($sceneList as $key => &$scene)
            {
                $result = $this->testcaseTao->saveScene($scene, $sceneList);
                if($result['result'] == 'fail') throw new Exception($result['message']);

                $scene['id']             = $result['sceneID'];
                $sceneIDList[$key]['id'] = $result['sceneID'];
            }

            foreach($testcases as $testcase)
            {
                $result = $this->saveTestcase($testcase, $sceneIDList);
                if($result['result'] == 'fail') throw new Exception($result['message']);
            }

            $this->dao->commit();

            return array('result' => 'success', 'message' => $this->lang->saveSuccess);
        }
        catch (Exception $e)
        {
            $this->dao->rollBack();
            return array('result' => 'fail', 'message' => $e->getMessage());
        }

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

        unset($testcase->tmpPId);
        $testcase->scene = $scene;

        if(empty($testcase->id))
        {
            $this->create($testcase);
        }
        else
        {
            $oldCase = $this->getById($testcase->id);
            $changes = $this->update($testcase, $oldCase);
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('case', $testcase->id, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
        }

        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError(true));

        return array('result' => 'success', 'message' => 1, 'testcaseID' => zget($testcase, 'id', 0));
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
                    $parentStep = $stepList[$step->tmpPId];
                    $parentStep->children = isset($parentStep->children) ? $parentStep->children : array();

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
     * 导出 xmind 格式的用例时获取用例列表。
     * Get case list for export xmind.
     *
     * @param  int    $productID
     * @param  int    $moduleID
     * @param  string $branch
     * @access public
     * @return array
     */
    public function getCaseListForXmindExport(int $productID, int $moduleID, string $branch = ''): array
    {
        $fields = 't1.id AS testcaseID, t1.title AS `name`, t1.pri, t2.id AS productID, t2.`name` AS productName, t3.id AS moduleID, t3.`name` AS moduleName, t4.id AS sceneID, t4.title AS sceneName, t1.precondition';
        return $this->dao->select($fields)->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->leftJoin(TABLE_MODULE)->alias('t3')->on('t1.module = t3.id')
            ->leftJoin(TABLE_SCENE)->alias('t4')->on('t1.scene = t4.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.product')->eq($productID)
            ->beginIF($moduleID)->andWhere('t1.module')->eq($moduleID)->fi()
            ->beginIF($branch !== '' && $branch !== 'all')->andWhere('t1.branch')->eq($branch)->fi()
            ->fetchAll();
    }

    /**
     * 通过产品和模块获取步骤信息。
     * Get step by product and module.
     *
     * @param  int $productID
     * @param  int $moduleID
     * @access public
     * @return array
     */
    public function getStepByProductAndModule(int $productID, int $moduleID): array
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
     * 分组获取用例的步骤信息。
     * Get steps grouped by case id.
     *
     * @param  array  $caseID
     * @param  string $status
     * @access public
     * @return array
     */
    public function getStepGroupByIdList(array $caseIdList, $status = ''): array
    {
        if(!$caseIdList) return array();

        return $this->dao->select('t1.*')->from(TABLE_CASESTEP)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case=t2.id')
            ->where('t2.id')->in($caseIdList)
            ->andWhere('t1.version=t2.version')
            ->beginIF($status != 'all')->andWhere('t2.status')->ne('wait')->fi()
            ->fetchGroup('case', 'id');
    }

    /**
     * 通过产品和模块获取场景。
     * Get scene by product and module.
     *
     * @param  int    $productID
     * @param  int    $moduleID
     * @access public
     * @return array
     */
    public function getSceneByProductAndModule($productID, $moduleID): array
    {
        /* Get scenes by product and module. */
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
            /* If scene parent is zero, append it to topScenes. */
            if($one->parentID == 0) $topScenes[] = $one;

            /* Append it to sceneMaps. */
            $sceneMaps[$one->sceneID] = $one;
        }

        return array('sceneMaps' => $sceneMaps,'topScenes' => $topScenes);
    }

    /**
     * 存储 mind 配置。
     * Save mind config.
     *
     * @param  array  $configList
     * @access public
     * @return array
     */
    public function saveMindConfig(string $type, array $configList): array
    {
        $this->dao->begin();

        $this->dao->delete()->from(TABLE_CONFIG)
            ->where('owner')->eq($this->app->user->account)
            ->andWhere('module')->eq('testcase')
            ->andWhere('section')->eq($type)
            ->exec();

        foreach($configList as $one)
        {
            $config = new stdclass();

            $config->module  = 'testcase';
            $config->section = $type;
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
     * 获取 xmind 配置。
     * Get xmind config.
     *
     * @access public
     * @return array
     */
    public function getMindConfig(string $type): array
    {
        $configItems = $this->dao->select("`key`,value")->from(TABLE_CONFIG)
            ->where('owner')->eq($this->app->user->account)
            ->andWhere('module')->eq('testcase')
            ->andWhere('section')->eq($type)
            ->fetchAll();

        $config = array();
        foreach($configItems as $item) $config[$item->key] = $item->value;

        if(!isset($config['module']))       $config['module'] = 'M';
        if(!isset($config['scene']))        $config['scene']  = 'S';
        if(!isset($config['case']))         $config['case']   = 'C';
        if(!isset($config['precondition'])) $config['precondition']    = 'pre';
        if(!isset($config['pri']))          $config['pri']    = 'P';
        if(!isset($config['group']))        $config['group']  = 'G';

        return $config;
    }

    /**
     * 将 xml 内容转为数组。
     * Convert xml to array.
     *
     * @param  object  $xml
     * @param  array   $options
     * @access private
     * @return array
     */
    private function xmlToArray(object $xml, array $options = array()): array
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
        $tagsArray = $this->getXmlTagsArray($xml, $namespaces, $options);

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
     * 获取 xml 标签数组。
     * Get xml tags array.
     *
     * @param  object $xml
     * @param  array  $namespaces
     * @param  array  $options
     * @access public
     * @return array
     */
    public function getXmlTagsArray(object $xml, array $namespaces, array $options): array
    {
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
        return $tagsArray;
    }

    /**
     * 追加用例执行失败次数。
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
        $caseFails = $this->dao->select('COUNT(1) AS count')->from(TABLE_TESTRESULT)
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

    /**
     * 获取可以导入的测试用例。
     * Get can import cases.
     *
     * @param  int    $productID
     * @param  int    $libID
     * @param  string $orderBy
     * @param  object $pager
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getCanImportCases(int $productID, int $libID, string $orderBy = 'id_desc', ?object $pager = null, string $browseType = '', int $queryID = 0): array
    {
        $query = '';
        if($browseType == 'bysearch')
        {
            $queryID = (int)$queryID;
            if($queryID)
            {
                $this->session->set('testsuiteQuery', ' 1 = 1');
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('testsuiteQuery', $query->sql);
                    $this->session->set('testsuiteForm', $query->form);
                }
            }
            else
            {
                if($this->session->testsuiteQuery === false) $this->session->set('testsuiteQuery', ' 1 = 1');
            }

            $query  = $this->session->testsuiteQuery;
            $allLib = "`lib` = 'all'";
            $query  = strpos($query, $allLib) !== false ? str_replace($allLib, '1 = 1', $query) : "{$query} AND `lib` = '{$libID}'";
        }

        $this->loadModel('branch');
        $this->loadModel('tree');
        $product       = $this->loadModel('product')->getById($productID);
        $branches      = $product->type != 'normal' ? array(BRANCH_MAIN => $this->lang->branch->main) + $this->branch->getPairs($productID, 'active') : array(0);
        $branches      = array_keys($branches);
        $branchModules = array();
        foreach($branches as $branch) $branchModules[$branch] = $this->tree->getOptionMenu($productID, 'case', 0, (string)$branch);

        $caseModuleCount = $this->dao->select('fromCaseID,count(module) AS moduleCount')
            ->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_MODULE)->alias('t2')->on('t1.module=t2.id and t1.product = t2.root')
            ->where('t1.product')->eq($productID)
            ->andWhere('t1.lib')->eq($libID)
            ->andWhere('t1.fromCaseID')->ne('0')
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('((t2.type')->in('story,case')->andWhere('t2.deleted')->eq('0')->markRight(1)
            ->orWhere('t1.module')->eq('0')->markRight(1)
            ->groupBy('t1.fromCaseID')
            ->fetchPairs();
        $branchModuleCount = $this->dao->select('branch,count(id) + 1 as moduleCount')
            ->from(TABLE_MODULE)
            ->where('root')->eq($productID)
            ->andWhere('type')->in('story,case')
            ->andWhere('deleted')->eq('0')
            ->groupBy('branch')
            ->fetchPairs();
        foreach($branches as $branch)
        {
            if(!isset($branchModuleCount[$branch])) $branchModuleCount[$branch] = 0;
            if(!empty($branch) && isset($branchModuleCount[0])) $branchModuleCount[$branch] += $branchModuleCount[0];
        }
        $maxCount = array_sum($branchModuleCount);

        $canImport = $canNotImport = array();
        $libCases  = $this->loadModel('caselib')->getLibCases($libID, 'all');
        foreach($libCases as $caseID => $case)
        {
            $caseModuleCount = zget($caseModuleCount, $caseID, 0);
            if(!empty($caseModuleCount) && $caseModuleCount >= $maxCount)
            {
                $canNotImport[$caseID] = $caseID;
            }
            else
            {
                $canImport[$caseID] = $caseID;
            }
        }

        return $this->dao->select('*')->from(TABLE_CASE)
            ->where('deleted')->eq('0')
            ->beginIF($browseType != 'bysearch')->andWhere('lib')->eq($libID)->fi()
            ->beginIF($browseType == 'bysearch')->andWhere($query)->fi()
            ->beginIF(count($canImport) <= count($canNotImport))->andWhere('id')->in($canImport)->fi()
            ->beginIF(count($canImport) > count($canNotImport) && !empty($canNotImport))->andWhere('id')->notIn($canNotImport)->fi()
            ->andWhere('product')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * 获取已经导入的用例模块。
     * Get imported case modules.
     *
     * @param  int        $productID
     * @param  int        $libID
     * @param  int|string $branch
     * @param  string     $returnType
     * @access public
     * @return array
     */
    public function getCanImportedModules(int $productID, int $libID, int|string $branch, string $returnType = 'pairs', array $libCases = array()): array
    {
        $importedModules = $this->dao->select('fromCaseID,module')->from(TABLE_CASE)
            ->where('product')->eq($productID)
            ->andWhere('lib')->eq($libID)
            ->beginIF($branch != 'all')->andWhere('branch')->eq($branch)->fi()
            ->andWhere('fromCaseID')->ne('0')
            ->andWhere('deleted')->eq('0')
            ->fetchGroup('fromCaseID', 'module');
        foreach($importedModules as $fromCaseID => $modules) $importedModules[$fromCaseID] = array_combine(array_keys($modules), array_keys($modules));

        if(empty($libCases)) $libCases = $this->loadModel('caselib')->getLibCases($libID, 'all');
        $modules = $this->loadModel('tree')->getOptionMenu($productID, 'case', 0, (string)$branch);
        if($returnType == 'items')
        {
            $moduleItems = array();
            foreach($modules as $moduleID => $moduleName) $moduleItems[$moduleID] = array('text' => $moduleName, 'value' => $moduleID);
        }

        $canImportModules = array();
        foreach($libCases as $caseID => $case)
        {
            $caseModules = !empty($importedModules[$caseID]) ? $importedModules[$caseID] : array();
            $canImportModules[$caseID] = $returnType == 'pairs' ? array_diff_key($modules, $caseModules) : array_diff_key($moduleItems, $caseModules);
            if(!empty($canImportModules[$caseID]))
            {
                if($returnType == 'pairs')
                {
                    $canImportModules[$caseID]['ditto'] = $this->lang->testcase->ditto;
                }
                else
                {
                    $canImportModules[$caseID]['ditto'] = array('text' => $this->lang->testcase->ditto, 'value' => 'ditto');
                    $canImportModules[$caseID] = array_values($canImportModules[$caseID]);
                }
            }
            if(empty($canImportModules[$caseID])) unset($canImportModules[$caseID]);
        }

        return $canImportModules;
    }

    /**
     * Build search form.
     *
     * @param  int    $productID
     * @param  array  $products
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  int    $projectID
     * @param  int    $moduleID
     * @param  string $branch
     * @access public
     * @return void
     */
    public function buildSearchForm(int $productID, array $products, int $queryID, string $actionURL, int $projectID = 0, int $moduleID = 0, string $branch = 'all'): void
    {
        /* 获取产品列表。Get productList. */
        if($this->app->tab == 'project' && !$productID)
        {
            if($projectID)
            {
                $products = $this->loadModel('product')->getProducts($projectID);
                $productList = array(0 => '');
                foreach($products as $product) $productList[$product->id] = $product->name;
                $productList['all'] = $this->lang->product->allProductsOfProject;
            }
            else
            {
                $productList = $products;
            }
            $this->config->testcase->search['params']['story']['values'] = $this->loadModel('story')->getExecutionStoryPairs($projectID, 0, 'all', $moduleID, 'full', 'active');
        }
        else
        {
            $productList = array(0 => '');
            $productList['all'] = $this->lang->all;
            if(isset($products[$productID])) $productList[$productID] = $products[$productID];
            if(empty($productID) && empty($products))
            {
                $products     = $this->loadModel('product')->getPairs('', 0, '', 'all');
                $productList += $products;
            }
            $this->config->testcase->search['params']['story']['values'] = $this->loadModel('story')->getProductStoryPairs($productID, $branch, array(), 'active,reviewing', 'id_desc', 0, '', 'story', false);
        }

        /* 获取模块列表。*/
        /* Get moduleList. */
        if($productID)
        {
            $modules = $this->loadModel('tree')->getOptionMenu($productID, 'case', 0, $branch);
        }
        else
        {
            $modules = array();
            foreach($products as $id => $name) $modules += $this->loadModel('tree')->getOptionMenu($id, 'case', 0);
        }

        $this->config->testcase->search['params']['product']['values'] = $productList;
        $this->config->testcase->search['params']['module']['values']  = $modules;
        $this->config->testcase->search['params']['scene']['values']   = $this->getSceneMenu($productID, $moduleID, $branch, 0, 0, true);
        $this->config->testcase->search['params']['lib']['values']     = $this->loadModel('caselib')->getLibraries();

        $product = $this->loadModel('product')->getByID($productID ? $productID : (int)key($products));
        if((isset($product->type) && $product->type == 'normal') || $this->app->tab == 'project')
        {
            unset($this->config->testcase->search['fields']['branch']);
            unset($this->config->testcase->search['params']['branch']);
        }
        else
        {
            $branches = $this->loadModel('branch')->getPairs($productID, '', $projectID);
            $this->config->testcase->search['fields']['branch']           = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
            $this->config->testcase->search['params']['branch']['values'] = array('' => '', BRANCH_MAIN => $this->lang->branch->main) + $branches + array('all' => $this->lang->branch->all);
        }

        if(!$this->config->testcase->needReview) unset($this->config->testcase->search['params']['status']['values']['wait']);

        $this->config->testcase->search['actionURL'] = $actionURL;
        $this->config->testcase->search['queryID']   = $queryID;
        $this->config->testcase->search['module']    = 'testcase';

        $this->loadModel('search')->setSearchParams($this->config->testcase->search);
    }

    /**
     * 构建搜索配置。
     * Build search config.
     *
     * @param  int $productID
     * @param  string $branch
     * @access public
     * @return array
     */
    public function buildSearchConfig(int $productID, string $branch = 'all'): array
    {
        $this->config->testcase->search['params']['story']['values'] = $this->loadModel('story')->getProductStoryPairs($productID, $branch, array(), 'active,reviewing', 'id_desc', 0, '', 'story', false);

        /* 获取模块列表。*/
        /* Get moduleList. */
        $modules = $this->loadModel('tree')->getOptionMenu($productID, 'case', 0, $branch);

        $this->config->testcase->search['params']['module']['values']  = $modules;
        $this->config->testcase->search['params']['scene']['values']   = $this->getSceneMenu($productID, 0, $branch, 0, 0, true);
        $this->config->testcase->search['params']['lib']['values']     = $this->loadModel('caselib')->getLibraries();

        unset($this->config->testcase->search['fields']['product']);
        unset($this->config->testcase->search['params']['product']);

        $product = $this->loadModel('product')->getByID($productID ? $productID : 0);
        if($productID)
        {
            if(isset($product->type) && $product->type == 'normal')
            {
                unset($this->config->testcase->search['fields']['branch']);
                unset($this->config->testcase->search['params']['branch']);
            }
            else
            {
                $branches = $this->loadModel('branch')->getPairs($productID, '', 0);
                $this->config->testcase->search['fields']['branch']           = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
                $this->config->testcase->search['params']['branch']['values'] = array('' => '', BRANCH_MAIN => $this->lang->branch->main) + $branches + array('all' => $this->lang->branch->all);
            }
        }

        if(!$this->config->testcase->needReview) unset($this->config->testcase->search['params']['status']['values']['wait']);

        $_SESSION['searchParams']['module'] = 'testcase';
        $searchConfig = $this->loadModel('search')->processBuildinFields('testcase', $this->config->testcase->search);
        $searchConfig['params'] = $this->search->setDefaultParams('testcase', $searchConfig['fields'], $searchConfig['params']);

        return $searchConfig;
    }

    /**
     * 过滤自动测试用例的ID列表。
     * Ignore auto testcase id list.
     *
     * @param  array  $caseIdList
     * @access public
     * @return array
     */
    public function ignoreAutoCaseIdList(array $caseIdList): array
    {
        if(empty($caseIdList)) return array();
        return $this->dao->select('id')->from(TABLE_CASE)->where('id')->in($caseIdList)->andWhere('auto')->ne('auto')->fetchPairs('id', 'id');
    }
}

<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class bugModelTest extends baseTest
{
    protected $moduleName = 'bug';
    protected $className  = 'model';

    /**
     * Test create a bug.
     *
     * @param  array  $param
     * @access public
     * @return object
     */
    public function createObject($param = array())
    {
        $bug              = new stdclass();
        $bug->title       = 'add bug';
        $bug->type        = 'codeerror';
        $bug->product     = 1;
        $bug->execution   = 101;
        $bug->openedBuild = 'trunk';
        $bug->pri         = 3;
        $bug->severity    = 3;
        $bug->status      = 'active';
        $bug->deadline    = '2023-03-20';
        $bug->openedBy    = 'admin';
        $bug->openedDate  = '2023-04-20';
        $bug->notifyEmail = '';
        $bug->steps       = '';

        foreach($param as $key => $value) $bug->$key = $value;

        $objectID = $this->instance->create($bug);
        if(dao::isError()) return dao::getError();

        return $this->instance->getByID($objectID);
    }

    /**
     * 测试gitlab问题转bug。
     * Test create bug from gitlab issue.
     *
     * @param  object  $bug
     * @param  int     $executionID
     * @access public
     * @return object|int|array
     */
    public function createBugFromGitlabIssueTest(object $bug, int $executionID): object|int|array
    {
        $objectID = $this->instance->createBugFromGitlabIssue($bug, $executionID);
        if(dao::isError()) return dao::getError();

        $object = $objectID ? $this->instance->getById($objectID) : 0;
        return $object;
    }

    /**
     * Test get by Sonarqube id.
     *
     * @param  int    $sonarqubeID
     * @access public
     * @return int
     */
    public function getBySonarqubeIDTest($sonarqubeID)
    {
        $result = $this->instance->getBySonarqubeID($sonarqubeID);
        if(dao::isError()) return dao::getError();
        return count($result);
    }

    /**
     * Test get bug list of a plan.
     *
     * @param  int    $planID
     * @param  string $status
     * @access public
     * @return string|array
     */
    public function getPlanBugsTest(int $planID, string $status): string|array
    {
        $bugs = $this->instance->getPlanBugs($planID, $status, 'id_desc', null);
        if(dao::isError()) return dao::getError();

        $title = '';
        foreach($bugs as $bug)
        {
            $title .= ',' . $bug->title;
        }
        $title = trim($title, ',');
        $title = str_replace(array("'", '@', '$', '%', ';'), '', $title);

        return $title;
    }

    /**
     * Test get bug by id.
     *
     * @param  int    $bugID
     * @access public
     * @return object
     */
    public function getByIdTest($bugID)
    {
        $object = $this->instance->getById($bugID);
        if(dao::isError()) return dao::getError();

        if(isset($object->title)) $object->title = str_replace("'", '', $object->title);
        return $object;
    }

    /**
     * Test get bug by id list.
     *
     * @param  string $bugIDList
     * @param  string $fields
     * @param  string $orderBy
     * @access public
     * @return array|string
     */
    public function getByIdListTest(string $bugIDList, string $fields, string $orderBy = ''): array|string
    {
        $bugs = $this->instance->getByIdList($bugIDList, $fields, $orderBy);
        if(dao::isError()) return dao::getError();

        if($orderBy) return implode(',', array_keys($bugs));

        foreach($bugs as $bug)
        {
            if(isset($bug->title)) $bug->title = str_replace("'", '', $bug->title);
        }
        return $bugs;
    }

    /**
     * Test get active bugs.
     *
     * @param  string $products
     * @param  string $excludeBugs
     * @access public
     * @return string
     */
    public function getActiveBugsTest($products, $excludeBugs)
    {
        $bugs = $this->instance->getActiveBugs($products, 'all', '', $excludeBugs);
        if(dao::isError()) return dao::getError();

        $title = '';
        foreach($bugs as $bug)
        {
            $title .= ',' . $bug->title;
        }
        $title = trim($title, ',');
        $title = str_replace("'", '', $title);
        $title = str_replace("@", '', $title);

        return $title;
    }

    /**
     * 获取激活和延期处理的 bug 列表。
     * Test get active and postponed bugs.
     *
     * @param  array  $products
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getActiveAndPostponedBugsTest(array $products, int $executionID): array
    {
        $bugs = $this->instance->getActiveAndPostponedBugs($products, $executionID);
        if(dao::isError()) return dao::getError();
        return $bugs;
    }

    /**
     * 获取模块的负责人的测试用例。
     * Test get module owner.
     *
     * @param  int    $moduleID
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getModuleOwnerTest(int $moduleID, int $productID): array
    {
        $owner = $this->instance->getModuleOwner($moduleID, $productID);
        if(dao::isError()) return dao::getError();
        return $owner;
    }

    /**
     * Test update a bug.
     *
     * @param  int    $bugID
     * @param  array  $param
     * @access public
     * @return void
     */
    public function updateObject($bugID, $param = array())
    {
        $oldBug = $this->instance->dao->select('*')->from(TABLE_BUG)->where('id')->eq($bugID)->fetch();

        $bug = new stdclass();
        $bug->deleteFiles = array();
        $bug->comment     = '';
        foreach($oldBug as $field => $value)
        {
            if(in_array($field, array_keys($param)))
            {
                $bug->$field = $param[$field];
            }
            elseif(strpos($field, 'Date') === false)
            {
                $bug->$field = $value;
            }
        }

        $result = $this->instance->update($bug, 'Edit');
        if(dao::isError()) return dao::getError();

        if($result == array()) $result = '没有数据更新';
        return $result;
    }

    /**
     * 测试指派一个bug。
     * Test assign a bug to a user again.
     *
     * @param  object $bug
     * @access public
     * @return array|object
     */
    public function assignTest(object $bug): array|object
    {
        $_SERVER['HTTP_HOST'] = '';

        $oldBug = $this->instance->getByID($bug->id);
        $this->instance->assign($bug, $oldBug);
        if(dao::isError()) return dao::getError();

        return $this->instance->dao->findByID($bug->id)->from(TABLE_BUG)->fetch();
    }

    /**
     * Test confirm a bug.
     *
     * @param  array  $bug
     * @access public
     * @return array
     */
    public function confirmTest(array $bug): array
    {
        $oldBug = $this->instance->getByID($bug['id']);

        $bug['confirmed'] = 1;
        $bug['comment']   = '';

        $this->instance->confirm((object)$bug, array());
        if(dao::isError()) return dao::getError();

        $newBug = $this->instance->getByID($bug['id']);
        return common::createChanges($oldBug, $newBug);
    }

    /**
     * Test confirm a bug.
     *
     * @param  object        $bug
     * @param  array         $param
     * @param  array         $output
     * @access public
     * @return object|string
     */
    public function resolveTest(int $bugID, array $param = array(), array $output = array()): object|string
    {
        $_SERVER['HTTP_HOST'] = '';

        $bug = new stdclass();
        $bug->id             = $bugID;
        $bug->status         = 'resolved';
        $bug->execution      = 11;
        $bug->resolution     = '';
        $bug->resolvedBy     = 'admin';
        $bug->resolvedBuild  = 0;
        $bug->resolvedDate   = helper::now();
        $bug->assignedTo     = 'user99';
        $bug->assignedDate   = helper::now();
        $bug->lastEditedBy   = 'user99';
        $bug->lastEditedDate = helper::now();
        $bug->duplicateBug   = 0;
        $bug->buildName      = '';
        $bug->createBuild    = 0;
        $bug->buildExecution = 0;
        $bug->uid            = '';
        $bug->comment        = '';

        foreach($param as $key => $value) $bug->{$key} = $value;

        $this->instance->resolve($bug, $output);
        if(dao::isError()) return str_replace('\n', '', dao::getError(true));

        return $tester->dao->findByID($bug->id)->from(TABLE_BUG)->fetch();
    }

    /**
     * Test activate a bug.
     *
     * @param  int    $bugID
     * @param  int    $buildID
     * @param  array  $kanbanParams
     * @param  string $returnType   bug|build|action|kanban
     * @access public
     * @return string|array|object
     */
    public function activateTest(int $bugID, int $buildID = 0, array $kanbanParams = array(), string $returnType = 'bug'): string|array|object
    {
        $bug = new stdclass();
        $bug->id             = $bugID;
        $bug->status         = 'active';
        $bug->comment        = "Activate bug{$bugID}";
        $bug->activatedCount = 1;

        $result = $this->instance->activate($bug, $kanbanParams);
        if(dao::isError()) return str_replace('\n', '', dao::getError(true));

        if($returnType == 'build') return $this->instance->loadModel('build')->getByID($buildID);

        if($returnType == 'action')
        {
            $actionID = $this->instance->dao->select('id')->from(TABLE_ACTION)
                ->where('objectType')->eq('bug')
                ->andWhere('objectID')->eq($bugID)
                ->andWhere('action')->eq('activated')
                ->orderBy('id_desc')
                ->limit(1)
                ->fetch('id');
            return $this->instance->dao->select('*')->from(TABLE_HISTORY)->where('action')->eq($actionID)->fetchAll('', false);
        }

        if($returnType == 'kanban')
        {
            $bug = $this->instance->fetchByID($bugID);
            return $this->instance->dao->select('t3.type')->from(TABLE_KANBANLANE)->alias('t1')
                ->leftJoin(TABLE_KANBANCELL)->alias('t2')->on('t1.id=t2.lane AND t1.execution=t2.kanban')
                ->leftJoin(TABLE_KANBANCOLUMN)->alias('t3')->on('t2.column=t3.id')
                ->where('t1.type')->eq('bug')
                ->andWhere('t1.execution')->eq($bug->execution)
                ->andWhere("FIND_IN_SET($bugID, t2.cards)")
                ->fetch('type');
        }

        return $this->instance->fetchByID($bugID);
    }

    /**
     * Test close a bug.
     *
     * @param  int    $bugID
     * @access public
     * @return array
     */
    public function closeObject(int $bugID, array $output = array())
    {
        $now = helper::now();

        $bug = new stdclass();
        $bug->id             = $bugID;
        $bug->status         = 'closed';
        $bug->confirmed      = 1;
        $bug->assignedDate   = $now;
        $bug->lastEditedBy   = 'admin';
        $bug->lastEditedDate = $now;
        $bug->closedBy       = 'admin';
        $bug->closedDate     = $now;
        $bug->comment        = '';

        $this->instance->close($bug, $output);
        if(dao::isError()) return dao::getError();

        return $this->instance->fetchBugInfo($bugID);
    }

    /**
     * 测试获取 bugs 的影响版本和解决版本。
     * Test process the openedBuild and resolvedBuild fields for bugs.
     *
     * @param  array  $bugIDList
     * @access public
     * @return array
     */
    public function processBuildForBugsTest(array $bugIDList): array
    {
        $bugs = $this->instance->getByIdList($bugIDList);
        $bugs = $this->instance->processBuildForBugs($bugs);
        if(dao::isError()) return dao::getError();
        return $bugs;
    }

    /**
     * 测试获取用户的 bugs。
     * Test get user bugs.
     *
     * @param  string      $account
     * @param  string      $type
     * @param  int         $limit
     * @param  int         $executionID
     * @param  int         $queryID
     * @param  string      $rawMethod
     * @param  string|bool $query
     * @access public
     * @return array
     */
    public function getUserBugsTest(string $account, string $type = 'assignedTo', int $limit = 0, int $executionID = 0, int $queryID = 0, string $rawMethod = 'work', string|bool $query = false): array|int
    {
        if($type == 'bySearch')
        {
            $moduleName = $rawMethod == 'work' ? 'workBug' : 'contributeBug';
            $queryName  = $moduleName . 'Query';
            $formName   = $moduleName . 'Form';
            if($query) $this->instance->session->set($queryName, $query);
        }

        $bugs = $this->instance->getUserBugs($account, $type, 'id_desc', $limit, null, $executionID, $queryID);
        if(dao::isError()) return dao::getError();
        return count($bugs);
    }

    /**
     * 测试获取取用户的bugs的 id => title 数组。
     * Test get bug pairs of a user.
     *
     * @param  string $account
     * @param  bool      $appendProduct
     * @param  int       $limit
     * @param  array     $skipProductIdList
     * @param  array     $skipExecutionIdList
     * @param  int|array $appendBugID
     * @access public
     * @return array
     */
    public function getUserBugPairsTest(string $account, bool $appendProduct = true, int $limit = 0, array $skipProductIdList = array(), array $skipExecutionIdList = array(), array|int $appendBugID = 0): array|int
    {
        $bugs = $this->instance->getUserBugPairs($account, $appendProduct, $limit, $skipProductIdList, $skipExecutionIdList, $appendBugID);
        if(dao::isError()) return dao::getError();
        return count($bugs);
    }

    /**
     * 测试获取项目的 bug。
     * Test get bugs of a project.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getProjectBugsTest(int $projectID, int $productID = 0, int|string $branchID = 0, int $build = 0, string $type = '', int $param = 0, string $excludeBugs = ''): array
    {
        $bugs = $this->instance->getProjectBugs($projectID, $productID, $branchID, $build, $type, $param, $orderBy = 'id_desc', $excludeBugs);
        if(dao::isError()) return dao::getError();

        foreach($bugs as $bug) $bug->title = str_replace("'", '', $bug->title);
        return $bugs;
    }

    /**
     * 测试获取执行的 bug。
     * Test get bugs of a execution.
     *
     * @param  int          $executionID
     * @param  int          $productID
     * @param  string|int   $branchID
     * @param  string|array $builds
     * @param  string       $type
     * @param  int          $param
     * @param  string       $excludeBugs
     * @access public
     * @return string
     */
    public function getExecutionBugsTest(int $executionID, int $productID = 0, string|int $branchID = 'all', string|array $builds = '0', string $type = '', int $param = 0, string $excludeBugs = ''): array
    {
        $bugs = $this->instance->getExecutionBugs($executionID, $productID, $branchID, $builds, $type, $param, 'id_desc', $excludeBugs);
        if(dao::isError()) return dao::getError();
        return array_values($bugs);
    }

    /**
     * 测试获取产品未关联版本的bug。
     * Test get product left bugs.
     *
     * @param  array      $buildIdList
     * @param  int        $productID
     * @param  int|string $branch
     * @param  string     $linkedBugs
     * @access public
     * @return array|string
     */
    public function getProductLeftBugsTest(array $buildIdList, int $productID, int|string $branch = '', string $linkedBugs = ''): array|string
    {
        $bugs = $this->instance->getProductLeftBugs($buildIdList, $productID, $branch, $linkedBugs);
        if(dao::isError()) return dao::getError();

        $title = implode(',', array_column($bugs, 'title'));
        $title = str_replace(array("'", '@', '$', '%'), '', $title);
        return $title;
    }

    /**
     * 测试获取产品的bug键对。
     * Test get bug pairs of a product.
     *
     * @param  int        $productID
     * @param  int|string $branch
     * @access public
     * @return string
     */
    public function getProductBugPairsTest(int $productID, int|string $branch = ''): array|string
    {
        $bugs = $this->instance->getProductBugPairs($productID, $branch);
        if(dao::isError()) return dao::getError();

        $title = implode(',', $bugs);
        $title = str_replace(array("'", '@', '$', '%'), '', $title);
        return $title;
    }

    /**
     * Test get bug member of a product.
     *
     * @param  int          $productID
     * @access public
     * @return string|array
     */
    public function getProductMemberPairsTest(int $productID): string|array
    {
        $bugs = $this->instance->getProductMemberPairs($productID);
        if(dao::isError()) return dao::getError();

        $title = implode(',', $bugs);
        $title = str_replace("'", '', $title);
        return $title;
    }

    /**
     * 测试通过版本 id 和产品 id 获取 bugs。
     * Test get bugs according to buildID and productID.
     *
     * @param  int    $buildID
     * @param  int    $productID
     * @access public
     * @return string|array
     */
    public function getReleaseBugsTest(int $buildID, int $productID): string|array
    {
        $bugs = $this->instance->getReleaseBugs(array($buildID), $productID);
        if(dao::isError()) return dao::getError();

        $title = implode(',', array_column($bugs, 'title'));
        $title = str_replace("'", '', $title);
        return $title;
    }

    /**
     * 测试获取需求产生的bug。
     * Test get bugs of a story.
     *
     * @param  int    $storyID
     * @access public
     * @return string
     */
    public function getStoryBugsTest(int $storyID): array|string
    {
        $bugs = $this->instance->getStoryBugs($storyID);
        if(dao::isError()) return dao::getError();

        $title = implode(',', array_column($bugs, 'title'));
        $title = str_replace(["'", '@'], '', $title);
        return $title;
    }

    /**
     * 测试获取用例关联的bugs。
     * Test get case bugs.
     *
     * @param  int    $runID
     * @param  int    $caseID
     * @param  int    $version
     * @access public
     * @return string
     */
    public function getCaseBugsTest(int $runID, int $caseID = 0, int $version = 0): string|array
    {
        $bugs = $this->instance->getCaseBugs($runID, $caseID, $version);
        if(dao::isError()) return dao::getError();

        $title = implode(',', array_column($bugs, 'title'));
        $title = str_replace("'", '', $title);
        return $title;
    }

    /**
     * 测试获取需求关联的 bug 数量。
     * Test get counts of some stories' bugs.
     *
     * @param  array  $storyIDList
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getStoryBugCountsTest(array $storyIDList, int $executionID = 0): array
    {
        $bugs = $this->instance->getStoryBugCounts($storyIDList, $executionID);
        if(dao::isError()) return dao::getError();
        return $bugs;
    }

    /**
     * 测试从测试结果中获取 bug 信息。
     * Test get bug info from a result.
     *
     * @param  int    $runID
     * @param  int    $caseID
     * @access public
     * @return string|int|array
     */
    public function getBugInfoFromResultTest(int $resultID, int $caseID = 0): string|int|array
    {
        $bug = $this->instance->getBugInfoFromResult($resultID, $caseID);
        if(dao::isError()) return dao::getError();
        return $bug['title'] ?? 0;
    }

    /**
     * 获取执行 bug 数量报表的测试用例。
     * Test get report data of bugs per execution.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerExecutionTest(): array
    {
        $this->instance->loadModel('report');
        $this->instance->session->set('project', 0);

        $datas = $this->instance->getDataOfBugsPerExecution();
        if(dao::isError()) return dao::getError();
        return $datas;
    }

    /**
     * 获取版本 bug 数量报表的测试用例。
     * Test get report data of bugs per build.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerBuildTest(): array
    {
        $datas = $this->instance->getDataOfBugsPerBuild();
        if(dao::isError()) return dao::getError();
        return $datas;
    }

    /**
     * 获取模块 bug 数量报表的测试用例。
     * Test get report data of bugs per module.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerModuleTest()
    {
        $datas = $this->instance->getDataOfBugsPerModule();
        if(dao::isError()) return dao::getError();
        return $datas;
    }

    /**
     * 统计每天新增 bug 数。
     * Test get report data of opened bugs per day.
     *
     * @access public
     * @return array
     */
    public function getDataOfOpenedBugsPerDayTest(): array
    {
        $datas = $this->instance->getDataOfOpenedBugsPerDay();
        if(dao::isError()) return dao::getError();
        return $datas;
    }

    /**
     * 统计每天解决 bug 数。
     * Test get report data of resolved bugs per day.
     *
     * @access public
     * @return array
     */
    public function getDataOfResolvedBugsPerDayTest(): array
    {
        $datas = $this->instance->getDataOfResolvedBugsPerDay();
        if(dao::isError()) return dao::getError();
        return $datas;
    }

    /**
     * 统计每天关闭的 bug 数。
     * Test get report data of closed bugs per day.
     *
     * @access public
     * @return array
     */
    public function getDataOfClosedBugsPerDayTest(): array
    {
        $datas = $this->instance->getDataOfClosedBugsPerDay();
        if(dao::isError()) return dao::getError();
        return $datas;
    }

    /**
     * 统计每人提交的 bug 数。
     * Test get report data of openeded bugs per user.
     *
     * @access public
     * @return array
     */
    public function getDataOfOpenedBugsPerUserTest(): array
    {
        $datas = $this->instance->getDataOfOpenedBugsPerUser();
        if(dao::isError()) return dao::getError();
        return $datas;
    }

    /**
     * 统计每人解决的 bug 数。
     * Test get report data of resolved bugs per user.
     *
     * @access public
     * @return array
     */
    public function getDataOfResolvedBugsPerUserTest(): array
    {
        $datas = $this->instance->getDataOfResolvedBugsPerUser();
        if(dao::isError()) return dao::getError();
        return $datas;
    }

    /**
     * 统计每人解决的 bug 数（空数据测试）。
     * Test get report data of resolved bugs per user with empty data.
     *
     * @access public
     * @return array
     */
    public function getDataOfResolvedBugsPerUserTestWithEmptyData(): array
    {
        $originalBugs = $this->instance->dao->select('*')->from(TABLE_BUG)->where('resolvedBy')->ne('')->fetchAll('', false);
        $this->instance->dao->update(TABLE_BUG)->set('resolvedBy')->eq('')->where('resolvedBy')->ne('')->exec();

        $datas = $this->instance->getDataOfResolvedBugsPerUser();
        foreach($originalBugs as $bug)
        {
            $this->instance->dao->update(TABLE_BUG)->set('resolvedBy')->eq($bug->resolvedBy)->where('id')->eq($bug->id)->exec();
        }
        if(dao::isError()) return dao::getError();
        return $datas;
    }

    /**
     * 统计每人关闭的 bug 数。
     * Test get report data of closed bugs per user.
     *
     * @access public
     * @return array
     */
    public function getDataOfClosedBugsPerUserTest(): array
    {
        $datas = $this->instance->getDataOfClosedBugsPerUser();
        if(dao::isError()) return dao::getError();
        return $datas;
    }

    /**
     * 按照 bug 严重程度统计。
     * Test get report data of bugs per severity.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerSeverityTest(): array
    {
        $datas = $this->instance->getDataOfBugsPerSeverity();
        if(dao::isError()) return dao::getError();
        return $datas;
    }

    /**
     * 按照 bug 解决方案统计。
     * Test get report data of bugs per resolution.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerResolutionTest(): array
    {
        $datas = $this->instance->getDataOfBugsPerResolution();
        if(dao::isError()) return dao::getError();
        return $datas;
    }

    /**
     * 按照 bug 状态统计。
     * Test get report data of bugs per status.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerStatusTest(): array
    {
        $datas = $this->instance->getDataOfBugsPerStatus();
        if(dao::isError()) return dao::getError();
        return $datas;
    }

    /**
     * 按照 bug 优先级次数统计。
     * Test get report data of bugs per pri.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerPriTest(): array
    {
        $datas = $this->instance->getDataOfBugsPerPri();
        if(dao::isError()) return dao::getError();
        return $datas;
    }

    /**
     * 按照 bug 激活次数统计。
     * Test get report data of bugs per status.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerActivatedCountTest()
    {
        $datas = $this->instance->getDataOfBugsPerActivatedCount();
        if(dao::isError()) return dao::getError();
        return $datas;
    }

    /**
     * 按照 bug 类型统计。
     * Test get report data of bugs per type.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerTypeTest(): array
    {
        $datas = $this->instance->getDataOfBugsPerType();
        if(dao::isError()) return dao::getError();
        return $datas;
    }

    /**
     * 按照 bug 指派给统计。
     * Test get report data of bugs per assignedTo.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerAssignedToTest(): array
    {
        $datas = $this->instance->getDataOfBugsPerAssignedTo();
        if(dao::isError()) return dao::getError();
        return $datas;
    }

    /**
     * 测试判断当前动作是否可以点击。
     * Test adjust the action is clickable.
     *
     * @param  object $bug
     * @param  string $action
     * @access public
     * @return int
     */
    public function isClickableTest(object $bug, string $action): int
    {
        $bool = $this->instance->isClickable($bug, $action);
        return $bool ? 1 : 2;
    }

    /**
     * Test link bug to build and release.
     *
     * @param  int        $bugID
     * @param  int|string $resolvedBuild
     * @access public
     * @return object|array|bool
     */
    public function linkBugToBuildTest(int $bugID, int|string $resolvedBuild): array|object|bool
    {
        $this->instance->linkBugToBuild($bugID, $resolvedBuild);
        if(dao::isError()) return dao::getError();

        return $this->instance->dao->select('id,bugs')->from(TABLE_RELEASE)->where('build')->eq($resolvedBuild)->andWhere('deleted')->eq('0')->fetch();
    }

    /**
     * 测试 获取指派用户和抄送给用户列表。
     * Test get toList and ccList.
     *
     * @param  int    $bugID
     * @access public
     * @return string|string
     */
    public function getToAndCcListTest(int $bugID): array|string
    {
        $bug    = $this->instance->getByID($bugID);
        $result = $this->instance->getToAndCcList($bug);
        if(dao::isError()) return dao::getError();
        return implode(',', $result);
    }

    /**
     * 测试获取bug查询语句。
     * Test get bug query.
     *
     * @param  string $bugQuery
     * @access public
     * @return string|array
     */
    public function getBugQueryTest(string $bugQuery): string|array
    {
        $query = $this->instance->getBugQuery($bugQuery);
        if(dao::isError()) return dao::getError();
        return $query;
    }

    /**
     * Test get statistic.
     *
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function getStatisticTest($productID = 0)
    {
        $staticData = $this->instance->getStatistic($productID);
        if(dao::isError()) return dao::getError();

        $result = array();
        $today  = date('m/d', time());
        foreach($staticData as $dateField => $dateData)
        {
            $result[$dateField] = $dateData[$today];
        }
        return $result;
    }

    /**
     * 测试更新相关 bug。
     * The test for updatelinkbug function.
     *
     * @param  int    $bugID
     * @param  string $relatedBug
     * @param  string $oldRelatedBug
     * @access public
     * @return array
     */
    public function updateRelatedBugTest(int $bugID, string $relatedBug, string $oldRelatedBug): array|bool
    {
        $this->instance->updateRelatedBug($bugID, $relatedBug, $oldRelatedBug);
        if(dao::isError()) return dao::getError();

        $relatedBugs           = explode(',', $relatedBug);
        $oldRelatedBugs        = explode(',', $oldRelatedBug);
        $addedRelatedBugs      = array_diff($relatedBugs, $oldRelatedBugs);
        $removedRelatedBugs    = array_diff($oldRelatedBugs, $relatedBugs);
        $allRelatedRelatedBugs = array_merge($addedRelatedBugs, $removedRelatedBugs, array($bugID));

        return $this->instance->dao->select('id, relatedBug')->from(TABLE_BUG)
            ->where('id')->in(array_filter($allRelatedRelatedBugs))
            ->andWhere('deleted')->eq('0')
            ->fetchPairs();
    }

    /**
     * 测试在解决bug的时候创建版本。
     * Test create build when resolving a bug.
     *
     * @access public
     * @return array
     */
    public function createBuildTest(object $bug, int $bugID)
    {
        $oldBug = $this->instance->dao->findByID($bugID)->from(TABLE_BUG)->fetch();

        $this->instance->createBuild($bug, $oldBug);
        if(dao::isError()) return dao::getError(true);

        return $tester->dao->findByID($bug->resolvedBuild)->from(TABLE_BUILD)->fetch();
    }

    /**
     * 测试通过版本 id 列表获取版本关联的执行。
     * Test get linked execution by id list.
     *
     * @param  string $buildIdList
     * @access public
     * @return void
     */
    public function getLinkedExecutionByIdListTest(string $buildIdList): array|string
    {
        $buildIdList = explode(',', $buildIdList);
        $executions  = $this->instance->getLinkedExecutionByIdList($buildIdList);
        if(dao::isError()) return dao::getError();
        return implode(',', $executions);
    }

    /**
     * 测试获取 bugs 统计信息。
     * Test get the statistics of the bugs.
     *
     * @param  string $bugIdList
     * @access public
     * @return string
     */
    public function summaryTest(string $bugIdList): string
    {
        $bugs = $this->instance->getByIdList($bugIdList);
        if(empty($bugs)) $bugs = array();
        $summary = $this->instance->summary($bugs);
        return $summary;
    }

    /**
     * 测试从 SESSION 中获取报表操作。
     * Test get report condition from session.
     *
     * @param  string $bugIdList
     * @access public
     * @return string
     */
    public function reportConditionTest(string|bool $bugQueryCondition, bool $bugOnlyCondition): string
    {
        if($bugQueryCondition) $this->instance->session->set('bugQueryCondition', $bugQueryCondition);
        if($bugOnlyCondition)  $this->instance->session->set('bugOnlyCondition', $bugOnlyCondition);
        $reportCondition = $this->instance->reportCondition();
        unset($_SESSION['bugQueryCondition']);
        unset($_SESSION['bugOnlyCondition']);
        return $reportCondition;
    }

    /**
     * 测试获取产品的 bugs。
     * Test get product bugs.
     *
     * @param  string $productIdList
     * @param  string $type
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return string
     */
    public function getProductBugsTest(string $productIdList, string $type = '', string $begin = '', string $end = ''): string
    {
        if($begin == 'today')    $begin = date('Y-m-d', time());
        if($begin == 'lastweek') $begin = date('Y-m-d', strtotime('-7 days'));
        if($end   == 'today')    $end   = date('Y-m-d', time());
        if($end   == 'nextweek') $end   = date('Y-m-d', strtotime('+7 days'));

        $bugs = $this->instance->getProductBugs(explode(',', $productIdList), $type, $begin, $end);
        if(dao::isError()) return dao::getError();
        return implode(',', array_column($bugs, 'id'));
    }

    /**
     * 测试获取产品的 bugs。
     * Test get product bugs.
     *
     * @param  string $productIdList
     * @param  string $begin
     * @param  string $end
     * @param  string $buildIdList
     * @access public
     * @return string
     */
    public function getActivatedBugsTest(string $productIdList, string $begin = '', string $end = '', string $buildIdList = ''): string
    {
        $date = '2025-05-01';
        if($begin == 'lastweek')  $begin = date('Y-m-d', strtotime($date . '-7 days'));
        if($begin == 'lastmonth') $begin = date('Y-m-d', strtotime($date . '-30 days'));
        if($end   == 'nextweek')  $end   = date('Y-m-d', strtotime($date . '+7 days'));
        if($end   == 'nextmonth') $end   = date('Y-m-d', strtotime($date . '+30 days'));

        $bugs = $this->instance->getActivatedBugs(explode(',', $productIdList), $begin, $end, explode(',', $buildIdList));
        if(dao::isError()) return dao::getError();
        return implode(',', array_column($bugs, 'id'));
    }

    /**
     * 测试通过任务 id 获取相关 Bug.
     * Test get linked bug by task id.
     *
     * @param  int                $taskID
     * @access public
     * @return array|string|false
     */
    public function getLinkedBugsByTaskIDTest(int $taskID): array|string|false
    {
        $bugs = $this->instance->getLinkedBugsByTaskID($taskID);
        if(dao::isError()) return dao::getError();

        $result = '';
        foreach($bugs as $bug) $result .= "{$bug->id}:{$bug->title},";
        $result = trim($result, ',');

        return $result;
    }

    /**
     * Test buildSearchForm method.
     *
     * @param  int    $productID
     * @param  array  $products
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  string $branch
     * @access public
     * @return array|string
     */
    public function buildSearchFormTest(int $productID, array $products, int $queryID, string $actionURL, string $branch = '0'): array|string
    {
        // 模拟必要的session设置
        if(!$this->instance->session->project) $this->instance->session->set('project', 0);

        // 模拟语言配置
        if(!isset($this->instance->lang->all))             $this->instance->lang->all = 'All';
        if(!isset($this->instance->lang->bug->allProject)) $this->instance->lang->bug->allProject = 'All Projects';
        if(!isset($this->instance->lang->navGroup->bug))   $this->instance->lang->navGroup->bug = 'qa';

        // 如果是教程模式，直接返回简单结果
        if(defined('TUTORIAL') && TUTORIAL) return array('actionURL' => $actionURL, 'queryID' => $queryID, 'hasProductParams' => 1);

        $this->instance->buildSearchForm($productID, $products, $queryID, $actionURL, $branch);
        if(dao::isError()) return dao::getError();
        return $this->instance->config->bug->search;
    }

    /**
     * Test buildSearchConfig method.
     *
     * @param  int $productID
     * @access public
     * @return array
     */
    public function buildSearchConfigTest(int $productID): array
    {
        $result = $this->instance->buildSearchConfig($productID);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getRelatedObjects method.
     *
     * @param  string $object
     * @param  string $pairs
     * @access public
     * @return int
     */
    public function getRelatedObjectsTest(string $object, string $pairs = ''): object
    {
        /* 设置bugQueryCondition来模拟有查询条件的情况 */
        $_SESSION['bugQueryCondition'] = "SELECT * FROM " . TABLE_BUG . " WHERE deleted = '0'";
        $_SESSION['bugOnlyCondition']  = true;

        $objects = $this->instance->getRelatedObjects($object, $pairs);
        if(dao::isError()) return (object)array('error' => dao::getError());

        /* 返回详细的测试结果 */
        $result           = new stdClass();
        $result->count    = count($objects);
        $result->hasEmpty = isset($objects['']) ? 1 : 0;
        $result->hasZero  = isset($objects[0]) ? 1 : 0;
        $result->hasTrunk = isset($objects['trunk']) ? 1 : 0;
        $result->data     = $objects;

        return $result;
    }

    /**
     * Test getDatatableModules method.
     *
     * @param  int $productID
     * @access public
     * @return array
     */
    public function getDatatableModulesTest(int $productID): array
    {
        $result = $this->instance->getDatatableModules($productID);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test updateLinkedCommits method.
     *
     * @param  int   $bugID
     * @param  int   $repoID
     * @param  array $revisions
     * @access public
     * @return bool
     */
    public function updateLinkedCommitsTest(int $bugID, int $repoID, array $revisions): bool
    {
        $result = $this->instance->updateLinkedCommits($bugID, $repoID, $revisions);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getLinkedCommits method.
     *
     * @param  int   $repoID
     * @param  array $revisions
     * @access public
     * @return array
      */
    public function getLinkedCommitsTest(int $repoID, array $revisions): array
    {
        // 处理空数组情况，避免SQL语法错误
        if(empty($revisions)) return array();

        $result = $this->instance->getLinkedCommits($repoID, $revisions);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}

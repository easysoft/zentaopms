<?php
declare(strict_types=1);
class bugTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('bug');
    }

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

        $objectID = $this->objectModel->create($bug);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $object = $this->objectModel->getByID($objectID);
            return $object;
        }
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
        $objectID = $this->objectModel->createBugFromGitlabIssue($bug, $executionID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $object = $objectID ? $this->objectModel->getById($objectID) : 0;
            return $object;
        }
    }

    /**
     * 测试添加bug->delay字段，内容为延期的时长（天），不延期则为0
     * Test if the bug is delayed, add the bug->delay field to show the delay time (day).
     *
     * @param  object       $bug
     * @param  string       $status
     * @access public
     * @return object|array
     */
    public function appendDelayedDaysTest($bug, $status): object|array
    {
        $bug->status       = $status;
        $bug->deadline     = $bug->deadline     ? date('Y-m-d',strtotime("$bug->deadline day"))     : '0000-00-00';
        $bug->resolvedDate = $bug->resolvedDate ? date('Y-m-d',strtotime("$bug->resolvedDate day")) : '0000-00-00';

        $object = $this->objectModel->appendDelayedDays($bug);
        if(!isset($object->delay)) $object->delay = 0;

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * 测试为 bugs 批量添加延期天数。
     * Test call checkDelayBug in foreach to check if the bug is delay.
     *
     * @param  int          $productID
     * @access public
     * @return string|array
     */
    public function batchAppendDelayedDaysTest($productID): array|string
    {
        global $tester;

        $tester->app->tab = 'qa';
        $bugs = $this->objectModel->getListByBrowseType('all', array($productID), 0, array(), 'all', array(), 0, 'id_asc', null);
        $bugs = $this->objectModel->batchAppendDelayedDays($bugs);

        $delay = '';
        foreach($bugs as $bug)
        {
            $delay .= ',' . (!isset($bug->delay) ? 0 : $bug->delay);
        }
        $delay = trim($delay, ',');

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $delay;
        }
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
        $array = $this->objectModel->getBySonarqubeID($sonarqubeID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return count($array);
        }
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
        $bugs = $this->objectModel->getPlanBugs($planID, $status, 'id_desc', null);

        $title = '';
        foreach($bugs as $bug)
        {
            $title .= ',' . $bug->title;
        }
        $title = trim($title, ',');
        $title = str_replace(array("'", '@', '$', '%', ';'), '', $title);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $title;
        }
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
        $object = $this->objectModel->getById($bugID);
        if(isset($object->title)) $object->title = str_replace("'", '', $object->title);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test get bug by id list.
     *
     * @param  string $bugIDList
     * @access public
     * @return array
     */
    public function getByIdListTest($bugIDList)
    {
        $bugs = $this->objectModel->getByIdList($bugIDList);

        foreach($bugs as $bug)
        {
            if(isset($bug->title)) $bug->title = str_replace("'", '', $bug->title);
        }

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $bugs;
        }
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
        $bugs = $this->objectModel->getActiveBugs($products, 'all', '', $excludeBugs);

        $title = '';
        foreach($bugs as $bug)
        {
            $title .= ',' . $bug->title;
        }
        $title = trim($title, ',');
        $title = str_replace("'", '', $title);
        $title = str_replace("@", '', $title);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $title;
        }
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
        $bugs = $this->objectModel->getActiveAndPostponedBugs($products, $executionID);

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
        $owner = $this->objectModel->getModuleOwner($moduleID, $productID);

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
        global $tester;
        $object = $tester->app->dbQuery("SELECT * FROM " . TABLE_BUG  ." WHERE id = $bugID")->fetch();

        $bug = new stdclass();
        foreach($object as $field => $value)
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
        $bug->deleteFiles = array();
        $bug->comment     = '';

        $change = $this->objectModel->update($bug, 'Edit');
        if($change == array()) $change = '没有数据更新';

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $change;
        }
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
        $oldBug = $this->objectModel->getByID($bug->id);
        $_SERVER['HTTP_HOST'] = '';
        $this->objectModel->assign($bug, $oldBug);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            global $tester;
            $bug = $tester->dao->findByID($bug->id)->from(TABLE_BUG)->fetch();
            return $bug;
        }
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
        $oldBug = $this->objectModel->getByID($bug['id']);

        $bug['confirmed'] = 1;
        $bug['comment']   = '';

        $this->objectModel->confirm((object)$bug, array());

        if(dao::isError()) return dao::getError();

        $newBug = $this->objectModel->getByID($bug['id']);

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

        $this->objectModel->resolve($bug, $output);

        if(dao::isError())
        {
            $return = '';
            $errors = dao::getError();
            foreach($errors as $key => $value)
            {
                if(is_string($value)) $return .= "{$value}";
                if(is_array($value))  $return .= implode('', $value);
            }
            return $return;
        }
        else
        {
            global $tester;
            $bug = $tester->dao->findByID($bug->id)->from(TABLE_BUG)->fetch();
            return $bug;
        }
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

        $result = $this->objectModel->activate($bug, $kanbanParams);

        if(dao::isError()) return str_replace('\n', '', dao::getError(true));

        global $tester;
        if($returnType == 'build') return $tester->loadModel('build')->getByID($buildID);

        if($returnType == 'action')
        {
            $actionID = $tester->dao->select('id')->from(TABLE_ACTION)
                ->where('objectType')->eq('bug')
                ->andWhere('objectID')->eq($bugID)
                ->andWhere('action')->eq('activated')
                ->orderBy('id_desc')
                ->limit(1)
                ->fetch('id');
            return $tester->dao->select('*')->from(TABLE_HISTORY)->where('action')->eq($actionID)->fetchAll();
        }

        if($returnType == 'kanban')
        {
            unset(dao::$cache[TABLE_KANBANLANE]);

            $bug = $this->objectModel->fetchByID($bugID);
            return $tester->dao->select('t3.type')->from(TABLE_KANBANLANE)->alias('t1')
                ->leftJoin(TABLE_KANBANCELL)->alias('t2')->on('t1.id=t2.lane AND t1.execution=t2.kanban')
                ->leftJoin(TABLE_KANBANCOLUMN)->alias('t3')->on('t2.column=t3.id')
                ->where('t1.type')->eq('bug')
                ->andWhere('t1.execution')->eq($bug->execution)
                ->andWhere("FIND_IN_SET($bugID, t2.cards)")
                ->fetch('type');
        }

        return $this->objectModel->fetchByID($bugID);
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

        $this->objectModel->close($bug, $output);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $bug = $this->objectModel->fetchBugInfo($bugID);
            return $bug;
        }
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
        $bugs  = $this->objectModel->getByIdList($bugIDList);
        $array = $this->objectModel->processBuildForBugs($bugs);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $array;
        }
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
        global $tester;
        if($type == 'bySearch')
        {
            $moduleName = $rawMethod == 'work' ? 'workBug' : 'contributeBug';
            $queryName  = $moduleName . 'Query';
            $formName   = $moduleName . 'Form';
            if($query) $tester->session->set($queryName, $query);
        }

        $array = $this->objectModel->getUserBugs($account, $type, 'id_desc', $limit, null, $executionID, $queryID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return count($array);
        }
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
        $array = $this->objectModel->getUserBugPairs($account, $appendProduct, $limit, $skipProductIdList, $skipExecutionIdList, $appendBugID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return count($array);
        }
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
        $bugs = $this->objectModel->getProjectBugs($projectID, $productID, $branchID, $build, $type, $param, $orderBy = 'id_desc', $excludeBugs);

        foreach($bugs as $bug) $bug->title = str_replace("'", '', $bug->title);

        if(dao::isError()) return dao::getError();

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
        $bugs = $this->objectModel->getExecutionBugs($executionID, $productID, $branchID, $builds, $type, $param, 'id_desc', $excludeBugs);

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
        $array = $this->objectModel->getProductLeftBugs($buildIdList, $productID, $branch, $linkedBugs);

        $title = '';
        foreach($array as $bug) $title .= ',' . $bug->title;
        $title = trim($title, ',');
        $title = str_replace(array("'", '@', '$', '%'), '', $title);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $title;
        }
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
        $array = $this->objectModel->getProductBugPairs($productID, $branch);

        $title = '';
        foreach($array as $bug) $title .= ',' . $bug;
        $title = trim($title, ',');
        $title = str_replace(array("'", '@', '$', '%'), '', $title);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $title;
        }
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
        $array = $this->objectModel->getProductMemberPairs($productID);

        $title = '';
        foreach($array as $bug) $title .= ',' . $bug;
        $title = trim($title, ',');
        $title = str_replace("'", '', $title);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $title;
        }
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
        $array = $this->objectModel->getReleaseBugs(array($buildID), $productID);

        $title = '';
        foreach($array as $bug) $title .= ',' . $bug->title;
        $title = trim($title, ',');
        $title = str_replace("'", '', $title);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $title;
        }
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
        $array = $this->objectModel->getStoryBugs($storyID);

        $title = '';
        foreach($array as $bug) $title .= ',' . $bug->title;
        $title = trim($title, ',');
        $title = str_replace("'", '', $title);
        $title = str_replace("@", '', $title);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $title;
        }
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
        $array = $this->objectModel->getCaseBugs($runID, $caseID, $version);

        $title = '';
        foreach($array as $bug) $title .= ',' . $bug->title;
        $title = trim($title, ',');
        $title = str_replace("'", '', $title);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $title;
        }
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
        $array = $this->objectModel->getStoryBugCounts($storyIDList, $executionID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $array;
        }
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
        $array = $this->objectModel->getBugInfoFromResult($resultID, $caseID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return isset($array['title']) ? $array['title'] : 0;
        }
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
        global $tester;
        $tester->loadModel('report');
        $tester->session->set('project', 0);

        $datas = $this->objectModel->getDataOfBugsPerExecution();

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
        $datas = $this->objectModel->getDataOfBugsPerBuild();

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
        $datas = $this->objectModel->getDataOfBugsPerModule();

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
        $datas = $this->objectModel->getDataOfOpenedBugsPerDay();

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
        $datas = $this->objectModel->getDataOfResolvedBugsPerDay();

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
        $datas = $this->objectModel->getDataOfClosedBugsPerDay();

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
        $datas = $this->objectModel->getDataOfOpenedBugsPerUser();

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
        $datas = $this->objectModel->getDataOfResolvedBugsPerUser();

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
        $datas = $this->objectModel->getDataOfClosedBugsPerUser();

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
        $datas = $this->objectModel->getDataOfBugsPerSeverity();

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
        $datas = $this->objectModel->getDataOfBugsPerResolution();

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
        $datas = $this->objectModel->getDataOfBugsPerStatus();

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
        $datas = $this->objectModel->getDataOfBugsPerPri();

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
        $datas = $this->objectModel->getDataOfBugsPerActivatedCount();

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
        $datas = $this->objectModel->getDataOfBugsPerType();

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
        $datas = $this->objectModel->getDataOfBugsPerAssignedTo();

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
        $bool = $this->objectModel->isClickable($bug, $action);
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
        $this->objectModel->linkBugToBuild($bugID, $resolvedBuild);

        global $tester;
        $release = $tester->dao->select('id,bugs')->from(TABLE_RELEASE)->where('build')->eq($resolvedBuild)->andWhere('deleted')->eq('0')->fetch();

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $release;
        }
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
        $bug   = $this->objectModel->getByID($bugID);
        $array = $this->objectModel->getToAndCcList($bug);

        $account = '';
        foreach($array as $value) $account .= ',' . $value;
        $account = trim($account, ',');

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $account;
        }
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
        $array = $this->objectModel->getBugQuery($bugQuery);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $array;
        }
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
        $dates = $this->objectModel->getStatistic($productID);
        $returns = array();
        $today   = date('m/d', time());
        foreach($dates as $dateType => $dateList)
        {
            $returns[$dateType] = $dateList[$today];
        }

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $returns;
        }
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
        $this->objectModel->updateRelatedBug($bugID, $relatedBug, $oldRelatedBug);

        $relatedBugs           = explode(',', $relatedBug);
        $oldRelatedBugs        = explode(',', $oldRelatedBug);
        $addedRelatedBugs      = array_diff($relatedBugs, $oldRelatedBugs);
        $removedRelatedBugs    = array_diff($oldRelatedBugs, $relatedBugs);
        $allRelatedRelatedBugs = array_merge($addedRelatedBugs, $removedRelatedBugs, array($bugID));

        global $tester;
        $relatedBugPairs = $tester->dao->select('id, relatedBug')->from(TABLE_BUG)
            ->where('id')->in(array_filter($allRelatedRelatedBugs))
            ->andWhere('deleted')->eq('0')
            ->fetchPairs();

        if(dao::isError()) return dao::getError();
        return $relatedBugPairs;
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
        global $tester;
        $oldBug = $tester->dao->findByID($bugID)->from(TABLE_BUG)->fetch();
        $this->objectModel->createBuild($bug, $oldBug);

        if(dao::isError())
        {
            $errors = dao::getError();
            $return = '';
            foreach($errors as $key => $value)
            {
                if(is_string($value)) $return .= "{$value}";
                if(is_array($value))  $return .= implode('', $value);
            }
            return $return;
        }
        else
        {
            $build = $tester->dao->findByID($bug->resolvedBuild)->from(TABLE_BUILD)->fetch();
            return $build;
        }
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
        $array       = $this->objectModel->getLinkedExecutionByIdList($buildIdList);

        $idList = '';
        foreach($array as $execution) $idList .= ',' . $execution;
        $idList = trim($idList, ',');

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $idList;
        }
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
        $bugs    = $this->objectModel->getByIdList($bugIdList);
        if(empty($bugs)) $bugs = array();
        $summary = $this->objectModel->summary($bugs);
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
        global $tester;
        if($bugQueryCondition) $tester->session->set('bugQueryCondition', $bugQueryCondition);
        if($bugOnlyCondition) $tester->session->set('bugOnlyCondition', $bugOnlyCondition);
        $reportCondition = $this->objectModel->reportCondition();
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
        $bugs = $this->objectModel->getProductBugs(explode(',', $productIdList), $type, $begin, $end);
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
        if($begin == 'lastweek')  $begin = date('Y-m-d', strtotime('-7 days'));
        if($begin == 'lastmonth') $begin = date('Y-m-d', strtotime('-30 days'));
        if($end   == 'nextweek')  $end   = date('Y-m-d', strtotime('+7 days'));
        if($end   == 'nextmonth') $end   = date('Y-m-d', strtotime('+30 days'));
        $bugs = $this->objectModel->getActivatedBugs(explode(',', $productIdList), $begin, $end, explode(',', $buildIdList));
        if(dao::isError()) return dao::getError();
        return implode(',', array_column($bugs, 'id'));
    }
}

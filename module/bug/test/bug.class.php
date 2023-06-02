<?php
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
     * Test check delay bug.
     *
     * @param  object $bug
     * @param  string $status
     * @access public
     * @return object
     */
    public function checkDelayBugTest($bug, $status)
    {
        $bug->status       = $status;
        $bug->deadline     = $bug->deadline     ? date('Y-m-d',strtotime("$bug->deadline day"))     : '0000-00-00';
        $bug->resolvedDate = $bug->resolvedDate ? date('Y-m-d',strtotime("$bug->resolvedDate day")) : '0000-00-00';

        $object = $this->objectModel->checkDelayBug($bug);
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
     * Test check delay bugs.
     *
     * @param  string $productIDList
     * @access public
     * @return string
     */
    public function checkDelayedBugsTest($productID)
    {
        global $tester;
        $executions = $tester->loadModel('execution')->getPairs('0', 'all', 'empty|withdelete');

        /* Load pager. */
        $tester->app->loadClass('pager', $static = true);
        $pager = new pager(0, 20 ,1);

        $bugs = $this->objectModel->getAllBugs($productID, 0, 0, $executions, 'id_asc', $pager, 0);
        $bugs = $this->objectModel->checkDelayedBugs($bugs);

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
     * Test get bugs of a module.
     *
     * @param  string $productIDList
     * @param  string $moduleIDList
     * @access public
     * @return string
     */
    public function getModuleBugsTest($productIDList, $moduleIDList)
    {
        global $tester;
        $executions = $tester->loadModel('execution')->getPairs('0', 'all', 'empty|withdelete');

        $bugs = $this->objectModel->getModuleBugs($productIDList, 'all', $moduleIDList, $executions);

        $title = '';
        foreach($bugs as $bug)
        {
            $title .= ',' . $bug->title;
        }
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
     * Test get all bugs.
     *
     * @param  string $productIDList
     * @param  string $moduleIDList
     * @access public
     * @return string
     */
    public function getAllBugsTest($productIDList, $moduleIDList)
    {
        global $tester;
        $executions = $tester->loadModel('execution')->getPairs('0', 'all', 'empty|withdelete');

        $bugs = $this->objectModel->getAllBugs($productIDList, 'all', $moduleIDList, $executions, 'id_desc');

        $title = '';
        foreach($bugs as $bug)
        {
            $title .= ',' . $bug->title;
        }
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
     * Test get bugs of assign to me.
     *
     * @param  string $productIDList
     * @param  string $moduleIDList
     * @access public
     * @return string
     */
    public function getByAssigntomeTest($productIDList, $moduleIDList)
    {
        global $tester;
        $executions = $tester->loadModel('execution')->getPairs('0', 'all', 'empty|withdelete');

        $bugs = $this->objectModel->getByAssigntome($productIDList, 'all', $moduleIDList, $executions, 'id_desc', null, 0);

        $title = '';
        foreach($bugs as $bug)
        {
            $title .= ',' . $bug->title;
        }
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
     * Test get bugs of opened by me.
     *
     * @param  string $productIDList
     * @param  string $moduleIDList
     * @access public
     * @return string
     */
    public function getByOpenedbymeTest($productIDList, $moduleIDList)
    {
        global $tester;
        $executions = $tester->loadModel('execution')->getPairs('0', 'all', 'empty|withdelete');

        $bugs = $this->objectModel->getByOpenedbyme($productIDList, 'all', $moduleIDList, $executions, 'id_desc', null, 0);

        $title = '';
        foreach($bugs as $bug)
        {
            $title .= ',' . $bug->title;
        }
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
     * Test get bugs of resolved by me.
     *
     * @param  string $productIDList
     * @access public
     * @return string
     */
    public function getByResolvedbymeTest($productIDList)
    {
        global $tester;
        $executions = $tester->loadModel('execution')->getPairs('0', 'all', 'empty|withdelete');

        $bugs = $this->objectModel->getByResolvedbyme($productIDList, 'all', '0', $executions, 'id_desc', null, 0);

        $title = '';
        foreach($bugs as $bug)
        {
            $title .= ',' . $bug->title;
        }
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
     * Test get bugs of nobody to do.
     *
     * @param  string $productIDList
     * @access public
     * @return string
     */
    public function getByAssigntonullTest($productIDList)
    {
        global $tester;
        $executions = $tester->loadModel('execution')->getPairs('0', 'all', 'empty|withdelete');

        $bugs = $this->objectModel->getByAssigntonull($productIDList, 'all', '0', $executions, 'id_desc', null, 0);

        $title = '';
        foreach($bugs as $bug)
        {
            $title .= ',' . $bug->title;
        }
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
     * Test get unconfirmed bugs.
     *
     * @param  string $productIDList
     * @param  string $modules
     * @access public
     * @return string
     */
    public function getUnconfirmedTest($productIDList, $modules)
    {
        global $tester;
        $executions = $tester->loadModel('execution')->getPairs('0', 'all', 'empty|withdelete');

        $bugs = $this->objectModel->getUnconfirmed($productIDList, 'all', $modules, $executions, 'id_desc', null, 0);

        $title = '';
        foreach($bugs as $bug)
        {
            $title .= ',' . $bug->title;
        }
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
     * @param  string $productIDList
     * @param  string $modules
     * @access public
     * @return string
     */
    public function getPlanBugsTest($planID, $status)
    {
        $bugs = $this->objectModel->getPlanBugs($planID, $status, 'id_desc', null);

        $title = '';
        foreach($bugs as $bug)
        {
            $title .= ',' . $bug->title;
        }
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
        $object = $tester->dbh->query("SELECT * FROM " . TABLE_BUG  ." WHERE id = $bugID")->fetch();

        $bug = new stdclass();
        foreach($object as $field => $value)
        {
            if(in_array($field, array_keys($param)))
            {
                $bug->$field = $param[$field];
            }
            else
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
        $_SERVER['HTTP_HOST'] = '';
        $this->objectModel->assign($bug);
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

            $bug = $this->objectModel->getBaseInfo($bugID);
            return $tester->dao->select('t3.type')->from(TABLE_KANBANLANE)->alias('t1')
                ->leftJoin(TABLE_KANBANCELL)->alias('t2')->on('t1.id=t2.lane AND t1.execution=t2.kanban')
                ->leftJoin(TABLE_KANBANCOLUMN)->alias('t3')->on('t2.column=t3.id')
                ->where('t1.type')->eq('bug')
                ->andWhere('t1.execution')->eq($bug->execution)
                ->andWhere("FIND_IN_SET($bugID, t2.cards)")
                ->fetch('type');
        }

        return $this->objectModel->getBaseInfo($bugID);
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
     * Test get user bugs.
     *
     * @param  string $account
     * @param  string $type
     * @access public
     * @return array
     */
    public function getUserBugsTest($account, $type = 'assignedTo')
    {
        $array = $this->objectModel->getUserBugs($account, $type);

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
     * Test get bug pairs of a user.
     *
     * @param  string $account
     * @access public
     * @return array
     */
    public function getUserBugPairsTest($account)
    {
        $array = $this->objectModel->getUserBugPairs($account);

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
     * Test get bugs of a project.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getProjectBugsTest($projectID)
    {
        $array = $this->objectModel->getProjectBugs($projectID);

        foreach($array as $bug) $bug->title = str_replace("'", '', $bug->title);

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
     * Test get bugs of a execution.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function getExecutionBugsTest($executionID)
    {
        $array = $this->objectModel->getExecutionBugs($executionID);

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
     * Test get product left bugs.
     *
     * @param  int    $buildID
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function getProductLeftBugsTest($buildID, $productID)
    {
        $array = $this->objectModel->getProductLeftBugs($buildID, $productID);

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
     * Test get bug pairs of a product.
     *
     * @param  int        $productID
     * @param  int|string $branch
     * @access public
     * @return string
     */
    public function getProductBugPairsTest($productID, $branch = '')
    {
        $array = $this->objectModel->getProductBugPairs($productID, $branch);

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
     * Test get bug member of a product.
     *
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function getProductMemberPairsTest($productID)
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
     * Test get bugs according to buildID and productID.
     *
     * @param  int    $buildID
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function getReleaseBugsTest($buildID, $productID)
    {
        $array = $this->objectModel->getReleaseBugs($buildID, $productID);

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
     * Test get bugs of a story.
     *
     * @param  int    $storyID
     * @access public
     * @return string
     */
    public function getStoryBugsTest($storyID)
    {
        $array = $this->objectModel->getStoryBugs($storyID);

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
     * Test get case bugs.
     *
     * @param  int    $runID
     * @param  int    $caseID
     * @param  int    $version
     * @access public
     * @return string
     */
    public function getCaseBugsTest($runID, $caseID = 0, $version = 0)
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
     * Test adjust the action is clickable.
     *
     * @param  object $bug
     * @param  string $action
     * @access public
     * @return int
     */
    public function isClickableTest($bug, $action)
    {
        $object = $this->objectModel->isClickable($bug, $action);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object ? 1 : 2;
        }
    }

    /**
     * Test link bug to build and release.
     *
     * @param  array  $bugIDList
     * @param  int    $resolvedBuild
     * @access public
     * @return object
     */
    public function linkBugToBuildTest($bugIDList, $resolvedBuild)
    {
        $this->objectModel->linkBugToBuild($bugIDList, $resolvedBuild);

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
     * Test get toList and ccList.
     *
     * @param  int    $bugID
     * @access public
     * @return string
     */
    public function getToAndCcListTest($bugID)
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
     * Test get bug query.
     *
     * @param  string $bugQuery
     * @access public
     * @return array
     */
    public function getBugQueryTest($bugQuery)
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
     * Test get related objects id lists.
     *
     * @param  array       $productIDList
     * @param  int|string  $branch
     * @param  array       $modules
     * @param  array       $executions
     * @param  string      $orderBy
     * @access public
     * @return string
     */
    public function getRelatedObjectsTest($object, $pairs)
    {
        $objects = $this->objectModel->getRelatedObjects($object, $pairs);
        $ids     = '';
        foreach($objects as $objectID => $object)
        {
            $ids .= ",$objectID:$object";
        }
        $ids = trim($ids, ',');

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $ids;
        }
    }

    /**
     * The test for updatelinkbug function.
     *
     * @param  string $bugID
     * @param  string $relatedBug
     * @param  string $oldRelatedBug
     * @access public
     * @return array
     */
    public function updateRelatedBugTest($bugID, $relatedBug, $oldRelatedBug)
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
}

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
        $createFields['title']       = '测试创建bug';
        $createFields['type']        = 'codeerror';
        $createFields['product']     = 1;
        $createFields['execution']   = 101;
        $createFields['openedBuild'] = 'trunk';
        $createFields['pri']         = 3;
        $createFields['severity']    = 3;
        $createFields['status']      = 'active';
        $createFields['deadline']    = '2021-03-19';
        $createFields['openedBy']    = 'admin';
        $createFields['openedDate']  = '2021-03-19';

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;

        foreach($param as $key => $value) $_POST[$key] = $value;

        $object   = $this->objectModel->create();
        $objectID = $object['id'];
        unset($_POST);
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
     * Test batch create bugs.
     *
     * @param  int    $productID
     * @param  array  $param
     * @access public
     * @return object
     */
    public function batchCreateObject($productID, $param = array())
    {
        $modules      = array('0', '0', '0');
        $executions   = array('101', '101', '0');
        $openedBuilds = array('', '', '');
        $title        = array('', '', '');
        $deadlines    = array('0000-00-00', '0000-00-00', '0000-00-00');
        $stepses      = array('', '', '');
        $types        = array('', '', '');
        $severities   = array(3, 3, 3);
        $oses         = array('', '', '');
        $browsers     = array('', '', '');
        $pris         = array(3, 3, 3);
        $color        = array('', '', '');
        $keywords     = array('', '', '');

        $createFields['modules']      = $modules;
        $createFields['executions']   = $executions;
        $createFields['openedBuilds'] = $openedBuilds;
        $createFields['title']        = $title;
        $createFields['deadlines']    = $deadlines;
        $createFields['stepses']      = $stepses;
        $createFields['types']        = $types;
        $createFields['severities']   = $severities;
        $createFields['oses']         = $oses;
        $createFields['browsers']     = $browsers;
        $createFields['pris']         = $pris;
        $createFields['color']        = $color;
        $createFields['keywords']     = $keywords;

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;

        foreach($param as $key => $value) $_POST[$key] = $value;

        $object = $this->objectModel->batchCreate($productID);

        $bug = array();
        if(is_array($object))
        {
            foreach($object as $bugID => $actionID)
            {
                $bug[] = $this->objectModel->getByID($bugID);
            }
        }

        unset($_POST);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return empty($bug) ? $object : $bug;
        }
    }

    /**
     * Test create bug from gitlab issue.
     *
     * @param  array  $bug
     * @param  int    $executionID
     * @access public
     * @return object|int
     */
    public function createBugFromGitlabIssueTest($bug, $executionID)
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
     * Test get bugs.
     *
     * @access public
     * @return string
     */
    public function getBugsTest($product, $branch, $browseType, $module)
    {
        global $tester;

        /* Load pager. */
        $tester->app->loadClass('pager', $static = true);
        $pager = new pager(0, 20 ,1);

        $projectID  = 0;
        $sort       = 'id_desc';
        $queryID    = 0;
        $executions = $tester->loadModel('execution')->getPairs($projectID, 'all', 'empty|withdelete');

        $bugs = $this->objectModel->getBugs($product, $executions, $branch, $browseType, $module, $queryID, $sort, $pager, $projectID);

        $title = '';
        foreach($bugs as $bug) $title .= ',' . $bug->title;
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
     * Test get bugs the overdueBugs is active or unclosed.
     *
     * @param  string $productIDList
     * @param  string $modules
     * @access public
     * @return string
     */
    public function getOverdueBugsTest($productIDList, $modules)
    {
        global $tester;
        $executions = $tester->loadModel('execution')->getPairs('0', 'all', 'empty|withdelete');

        $bugs = $this->objectModel->getOverdueBugs($productIDList, 'all', $modules, $executions, 'id_desc', null, 0);

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
     * Test get bugs the status is active or unclosed.
     *
     * @param  string $productIDList
     * @param  string $modules
     * @access public
     * @return string
     */
    public function getByStatusTest($productIDList, $modules, $status)
    {
        global $tester;
        $executions = $tester->loadModel('execution')->getPairs('0', 'all', 'empty|withdelete');

        $bugs = $this->objectModel->getByStatus($productIDList, 'all', $modules, $executions, $status, 'id_desc', null, 0);

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
     * Test get unclosed bugs for long time.
     *
     * @param  string $productIDList
     * @param  string $modules
     * @access public
     * @return string
     */
    public function getByLonglifebugsTest($productIDList, $modules)
    {
        global $tester;
        $executions = $tester->loadModel('execution')->getPairs('0', 'all', 'empty|withdelete');

        $bugs = $this->objectModel->getByLonglifebugs($productIDList, 'all', $modules, $executions, 'id_desc', null, 0);

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
     * Test get postponed bugs.
     *
     * @param  string $productIDList
     * @access public
     * @return string
     */
    public function getByPostponedbugsTest($productIDList)
    {
        global $tester;
        $executions = $tester->loadModel('execution')->getPairs('0', 'all', 'empty|withdelete');

        $bugs = $this->objectModel->getByPostponedbugs($productIDList, 'all', '0', $executions, 'id_desc', null, 0);

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
     * Test get bugs need confirm.
     *
     * @param  string $productIDList
     * @param  string $modules
     * @access public
     * @return string
     */
    public function getByNeedconfirmTest($productIDList, $modules)
    {
        global $tester;
        $executions = $tester->loadModel('execution')->getPairs('0', 'all', 'empty|withdelete');

        $bugs = $this->objectModel->getByNeedconfirm($productIDList, 'all', $modules, $executions, 'id_desc', null, 0);

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
    public function getByListTest($bugIDList)
    {
        $bugs = $this->objectModel->getByList($bugIDList);

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
     * Test get active and postponed bugs.
     *
     * @param  string $bugIDList
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function getActiveAndPostponedBugsTest($products, $executionID)
    {
        $bugs = $this->objectModel->getActiveAndPostponedBugs($products, $executionID);

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
     * Test get module owner.
     *
     * @param  int    $moduleID
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function getModuleOwnerTest($moduleID, $productID)
    {
        $owner = $this->objectModel->getModuleOwner($moduleID, $productID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $owner;
        }
    }

    /**
     * Test update a bug.
     *
     * @param mixed $bugID
     * @param array $param
     * @access public
     * @return void
     */
    public function updateObject($bugID, $param = array())
    {
        global $tester;
        $object = $tester->dbh->query("SELECT * FROM " . TABLE_BUG  ." WHERE id = $bugID")->fetch();

        foreach($object as $field => $value)
        {
            if(in_array($field, array_keys($param)))
            {
                $_POST[$field] = $param[$field];
            }
            else
            {
                $_POST[$field] = $value;
            }
        }
        $_POST['closedDate'] = '';


        $change = $this->objectModel->update($bugID);
        if($change == array()) $change = '没有数据更新';
        unset($_POST);

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
     * Test batch update tasks.
     *
     * @param  array  $param
     * @param  int    $taskID
     * @access public
     * @return array
     */
    public function batchUpdateObject($bugIDList, $title, $type, $bugID)
    {
        $titles       = array('1' => 'BUG1', '2' => 'BUG2', '3' => 'BUG3');
        $types        = array('1' => 'codeerror', '2' => 'config', '3' => 'install');
        $severities   = array('1' => '1', '2' => '2', '3' => '3');
        $pris         = array('1' => '1', '2' => '2', '3' => '3');
        $colors       = array('1' => '#3da7f5', '2' => '#75c941', '3' => '#2dbdb2');
        $module       = array('1' => '1821', '2' => '1822', '3' => '1823');
        $plan         = array('1' => '1', '2' => '1', '3' => '1');
        $assignedTo   = array('1' => 'admin', '2' => 'admin', '3' => 'admin');
        $deadline     = array('1' => date('Y-m-d',strtotime('-1 month')), '2' => date('Y-m-d',strtotime('-1 month +1 day')), '3' => date('Y-m-d',strtotime('-1 month +2 day')));
        $os           = array('1' => '', '2' => '', '3' => 'all');
        $browser      = array('1' => '', '2' => '', '3' => '');
        $keyword      = array('1' => '', '2' => '', '3' => '');
        $resolvedBy   = array('1' => '', '2' => '', '3' => '');
        $resolution   = array('1' => '', '2' => '', '3' => '');
        $duplicateBug = array('1' => '', '2' => '', '3' => '');

        $titles[$bugID] = $title;
        $types[$bugID]  = $type;


        $batchUpdateFields['bugIDList']     = $bugIDList;
        $batchUpdateFields['types']         = $types;
        $batchUpdateFields['severities']    = $severities;
        $batchUpdateFields['pris']          = $pris;
        $batchUpdateFields['titles']        = $titles;
        $batchUpdateFields['colors']        = $colors;
        $batchUpdateFields['modules']       = $module;
        $batchUpdateFields['plans']         = $plan;
        $batchUpdateFields['assignedTos']   = $assignedTo;
        $batchUpdateFields['deadlines']     = $deadline;
        $batchUpdateFields['os']            = $os;
        $batchUpdateFields['browsers']      = $browser;
        $batchUpdateFields['keywords']      = $keyword;
        $batchUpdateFields['resolvedBys']   = $resolvedBy;
        $batchUpdateFields['resolutions']   = $resolution;
        $batchUpdateFields['duplicateBugs'] = $duplicateBug;

        foreach($batchUpdateFields as $field => $value) $_POST[$field] = $value;

        $object = $this->objectModel->batchUpdate();
        unset($_POST);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object[$bugID];
        }
    }
}

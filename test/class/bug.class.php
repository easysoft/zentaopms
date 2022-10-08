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
     * @param  int    $bugID
     * @param  array  $param
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
     * Test batch update bugs.
     *
     * @param  array  $bugIDList
     * @param  string $title
     * @param  string $type
     * @param  int    $bugID
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

    /**
     * Test batch activate bugs.
     *
     * @param  array  $bugIDList
     * @access public
     * @return array
     */
    public function batchActivateObject($bugIDList)
    {
        $statusList      = array('1' => 'active', '53' => 'resolved', '82' => 'closed');
        $assignedToList  = array('1' => 'admin',  '53' => 'admin',    '82' => 'admin');
        $openedBuildList = array('1' => 'trunk',  '53' => 'trunk',    '82' => 'trunk');
        $commentList     = array('1' => '',       '53' => '',         '82' => '');

        $batchActivateFields['bugIDList']       = $bugIDList;
        $batchActivateFields['statusList']      = $statusList;
        $batchActivateFields['assignedToList']  = $assignedToList;
        $batchActivateFields['openedBuildList'] = $openedBuildList;
        $batchActivateFields['commentList']     = $commentList;

        foreach($batchActivateFields as $field => $value) $_POST[$field] = $value;

        $object = $this->objectModel->batchActivate();
        unset($_POST);

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
     * Test assign a bug to a user again.
     *
     * @param  int    $bugID
     * @param  array  $param
     * @access public
     * @return array
     */
    public function assignTest($bugID, $param = array())
    {
        $createFields = array('assignedTo' => '', 'status' => '', 'comment' => '');
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $object = $this->objectModel->assign($bugID);
        unset($_POST);
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
     * Test confirm a bug.
     *
     * @param  int    $bugID
     * @param  array  $param
     * @access public
     * @return array
     */
    public function confirmTest($bugID, $param = array())
    {
        $createFields = array('assignedTo' => '', 'status' => '', 'comment' => '', 'pri' => '1');
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $object = $this->objectModel->confirm($bugID);
        unset($_POST);
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
     * Test batch confirm bugs.
     *
     * @param  array  $bugIDList
     * @access public
     * @return array
     */
    public function batchConfirmTest($bugIDList)
    {
        $this->objectModel->batchConfirm($bugIDList);

        $bugs = $this->objectModel->getByList($bugIDList);

        $confirm = '';
        foreach($bugs as $bug) $confirm .= ',' . $bug->confirmed;

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $confirm;
        }
    }

    /**
     * Test confirm a bug.
     *
     * @param  int    $bugID
     * @param  array  $param
     * @access public
     * @return array
     */
    public function resolveTest($bugID, $param = array())
    {
        $createFields['duplicateBug']   = '';
        $createFields['buildExecution'] = '0';
        $createFields['resolvedBuild']  = 'trunk';
        $createFields['buildName']      = '';
        $createFields['resolvedDate']   = helper::now();
        $createFields['assignedTo']     = '';
        $createFields['status']         = 'resolved';
        $createFields['labels']         = array('');
        $createFields['comment']        = '';
        $createFields['resolution']     = '';

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $object = $this->objectModel->resolve($bugID);

        unset($_POST);
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
     * Test batch change branch.
     *
     * @param  array  $bugIDList
     * @param  int    $branchID
     * @param  int    $bugID
     * @access public
     * @return array
     */
    public function batchChangeBranchTest($bugIDList, $branchID, $bugID)
    {
        $bugs = $this->objectModel->getByList($bugIDList);

        $object = $this->objectModel->batchChangeBranch($bugIDList, $branchID, $bugs);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return !empty($object[$bugID]) ? $object[$bugID] : 0;
        }
    }

    /**
     * Test batch change module.
     *
     * @param  array  $bugIDList
     * @param  int    $moduleID
     * @param  int    $bugID
     * @access public
     * @return array
     */
    public function batchChangeModuleTest($bugIDList, $moduleID, $bugID)
    {
        $bugs = $this->objectModel->getByList($bugIDList);

        $object = $this->objectModel->batchChangeModule($bugIDList, $moduleID, $bugs);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return !empty($object[$bugID]) ? $object[$bugID] : 0;
        }
    }

    /**
     * Test batch change plan.
     *
     * @param  array  $bugIDList
     * @param  int    $planID
     * @param  int    $bugID
     * @access public
     * @return array
     */
    public function batchChangePlanTest($bugIDList, $planID, $bugID)
    {
        $object = $this->objectModel->batchChangePlan($bugIDList, $planID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return !empty($object[$bugID]) ? $object[$bugID] : 0;
        }
    }

    /**
     * Test batch resolve bugs.
     *
     * @param  array  $bugIDList
     * @param  string $resolution
     * @param  int    $bugID
     * @access public
     * @return array
     */
    public function batchResolveTest($bugIDList, $resolution, $bugID)
    {
        $object = $this->objectModel->batchResolve($bugIDList, $resolution, 'trunk');

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return !empty($object[$bugID]) ? $object[$bugID] : 0;
        }
    }

    /**
     * Test activate a bug.
     *
     * @param  int    $bugID
     * @access public
     * @return array
     */
    public function activateObject($bugID)
    {
        $changes = $this->objectModel->activate($bugID, '');

        if($changes == array()) $changes = '没有数据更新';

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            foreach($changes as $change)
            {
                if($change['field'] == 'activatedCount') return $change;
            }
        }
    }

    /**
     * Test close a bug.
     *
     * @param  int    $bugID
     * @access public
     * @return array
     */
    public function closeObject($bugID)
    {
        $change = $this->objectModel->close($bugID, '');

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
     * Test process the openedBuild and resolvedBuild fields for bugs.
     *
     * @param  array  $bugIDList
     * @access public
     * @return array
     */
    public function processBuildForBugsTest($bugIDList)
    {
        $bugs  = $this->objectModel->getByList($bugIDList);
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
     * Test extract accounts from some bugs.
     *
     * @param  array  $bugIDList
     * @access public
     * @return array
     */
    public function extractAccountsFromListTest($bugIDList)
    {
        $bugs  = $this->objectModel->getByList($bugIDList);
        $array = $this->objectModel->extractAccountsFromList($bugs);

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
     * Test extract accounts from a bug.
     *
     * @param  int    $bugID
     * @access public
     * @return array
     */
    public function extractAccountsFromSingleTest($bugID)
    {
        $bug   = $this->objectModel->getByID($bugID);
        $array = $this->objectModel->extractAccountsFromSingle($bug);

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
     * Test get counts of some stories' bugs.
     *
     * @param  array  $storyIDList
     * @param  int    $storyID
     * @access public
     * @return int
     */
    public function getStoryBugCountsTest($storyIDList, $storyID)
    {
        $array = $this->objectModel->getStoryBugCounts($storyIDList);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return isset($array[$storyID]) ? $array[$storyID] : 0;
        }
    }

    /**
     * Test get bug info from a result.
     *
     * @param  int    $runID
     * @param  int    $caseID
     * @access public
     * @return string
     */
    public function getBugInfoFromResultTest($resultID, $caseID = 0)
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
     * Test get report data of bugs per execution.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerExecutionTest()
    {
        $array = $this->objectModel->getDataOfBugsPerExecution();

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
     * Test get report data of bugs per build.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerBuildTest()
    {
        $array = $this->objectModel->getDataOfBugsPerBuild();

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
     * Test get report data of bugs per module.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerModuleTest()
    {
        $array = $this->objectModel->getDataOfBugsPerModule();

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
     * Test get report data of opened bugs per day.
     *
     * @access public
     * @return array
     */
    public function getDataOfOpenedBugsPerDayTest()
    {
        $array = $this->objectModel->getDataOfOpenedBugsPerDay();

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
     * Test get report data of resolved bugs per day.
     *
     * @access public
     * @return array
     */
    public function getDataOfResolvedBugsPerDayTest()
    {
        $array = $this->objectModel->getDataOfResolvedBugsPerDay();

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
     * Test get report data of closed bugs per day.
     *
     * @access public
     * @return array
     */
    public function getDataOfClosedBugsPerDayTest()
    {
        $array = $this->objectModel->getDataOfClosedBugsPerDay();

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
     * Test get report data of openeded bugs per user.
     *
     * @access public
     * @return array
     */
    public function getDataOfOpenedBugsPerUserTest()
    {
        $array = $this->objectModel->getDataOfOpenedBugsPerUser();

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
     * Test get report data of resolved bugs per user.
     *
     * @access public
     * @return array
     */
    public function getDataOfResolvedBugsPerUserTest()
    {
        $array = $this->objectModel->getDataOfResolvedBugsPerUser();

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
     * Test get report data of closed bugs per user.
     *
     * @access public
     * @return array
     */
    public function getDataOfClosedBugsPerUserTest()
    {
        $array = $this->objectModel->getDataOfClosedBugsPerUser();

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
     * Test get report data of bugs per severity.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerSeverityTest()
    {
        $array = $this->objectModel->getDataOfBugsPerSeverity();

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
     * Test get report data of bugs per resolution.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerResolutionTest()
    {
        $array = $this->objectModel->getDataOfBugsPerResolution();

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
     * Test get report data of bugs per status.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerStatusTest()
    {
        $array = $this->objectModel->getDataOfBugsPerStatus();

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
     * Test get report data of bugs per pri.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerPriTest()
    {
        $array = $this->objectModel->getDataOfBugsPerPri();

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
     * Test get report data of bugs per status.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerActivatedCountTest()
    {
        $array = $this->objectModel->getDataOfBugsPerActivatedCount();

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
     * Test get report data of bugs per type.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerTypeTest()
    {
        $array = $this->objectModel->getDataOfBugsPerType();

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
     * Test get report data of bugs per assignedTo.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerAssignedToTest()
    {
        $array = $this->objectModel->getDataOfBugsPerAssignedTo();

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
     * Test form customed bugs.
     *
     * @param  array  $bugIDList
     * @access public
     * @return array
     */
    public function formCustomedBugsTest($bugIDList)
    {
        $bugs  = $this->objectModel->getByList($bugIDList);
        $array = $this->objectModel->formCustomedBugs($bugs);

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
     * Test get project list.
     *
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function getProjectsTest($productID)
    {
        $array = $this->objectModel->getProjects($productID);

        $title = '';
        foreach($array as $id => $project) $title .= ',' . $project;
        $title = trim($title, ',');

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
     * Test get id list of all projects.
     *
     * @access public
     * @return array
     */
    public function getAllProjectIdsTest()
    {
        $array = $this->objectModel->getAllProjectIds();

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
     * Test get bugs of assigned by me.
     *
     * @param  string $productIDList
     * @param  string $moduleIDList
     * @access public
     * @return string
     */
    public function getByAssignedbymeTest($productIDList, $moduleIDList)
    {
        global $tester;
        $executions = $tester->loadModel('execution')->getPairs('0', 'all', 'empty|withdelete');

        $bugs = $this->objectModel->getByAssignedbyme($productIDList, 'all', $moduleIDList, $executions, 'id_desc', null, 0);

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
     * Test get bugs to review.
     *
     * @param  array       $productIDList
     * @param  int|string  $branch
     * @param  array       $modules
     * @param  array       $executions
     * @param  string      $orderBy
     * @access public
     * @return string
     */
    public function getReviewBugsTest($productIDList, $branch, $modules, $executions, $orderBy)
    {
        $bugs = $this->objectModel->getReviewBugs($productIDList, $branch, $modules, $executions, $orderBy);
        $ids  = '';
        foreach($bugs as $bug)
        {
            $ids .= ",$bug->id";
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
}

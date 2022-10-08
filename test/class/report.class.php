<?php
class reportTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('report');
         $tester->dao->delete()->from(TABLE_ACTION)->where('id')->gt(100)->exec();
    }

    /**
     * Test compute percent of every item.
     *
     * @param  array  $datas
     * @access public
     * @return string
     */
    public function computePercentTest($datas)
    {
        $objects = $this->objectModel->computePercent($datas);

        if(dao::isError()) return dao::getError();

        $percents = '';
        foreach($objects as $moduleID => $object) $percents .= "$moduleID:$object->percent;";
        return $percents;
    }

    /**
     * Test create json data of single charts.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function createSingleJSONTest($executionID)
    {
        global $tester;
        $this->execution = $tester->loadModel('execution');

        $execution = $this->execution->getByID($executionID);
        $sets      = $this->execution->getBurnDataFlot($executionID, 'left');
        $dateList  = $this->execution->getDateList($execution->begin, $execution->end, 'noweekend', 0, 'Y-m-d');

        $objects = $this->objectModel->createSingleJSON($sets, $dateList[0]);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test convert date format.
     *
     * @param  array  $dateList
     * @param  string $format
     * @access public
     * @return string
     */
    public function convertFormatTest($dateList, $format = 'Y-m-d')
    {
        $objects = $this->objectModel->convertFormat($dateList, $format = 'Y-m-d');

        if(dao::isError()) return dao::getError();

        return implode(',', $objects);
    }

    /**
     * Test get executions.
     *
     * @param int $begin
     * @param int $end
     * @access public
     * @return void
     */
    public function getExecutionsTest($begin = 0, $end = 0)
    {
        global $tester;
        $tester->dao->update(TABLE_EXECUTION)->set('`status`')->eq('closed')->where('`id`')->in('101,102,103,107,111,121,151,183')->exec();

        $begin = $begin != 0 ? date('Y-m-d', strtotime(date('Y-m-d') . $begin)) : 0;
        $end   = $end != 0 ? date('Y-m-d', strtotime(date('Y-m-d') . $end)) : 0;
        $objects = $this->objectModel->getExecutions($begin, $end);

        $tester->dao->update(TABLE_EXECUTION)->set('`status`')->eq('wait')->where('`id`')->in('101,103,107,111,121,151,183')->exec();
        $tester->dao->update(TABLE_EXECUTION)->set('`status`')->eq('doing')->where('`id`')->in('102')->exec();

        if(dao::isError()) return dao::getError();

        $executions = '';
        foreach($objects as $executionID => $execution) $executions .= "$executionID:$execution->estimate,$execution->consumed,$execution->projectName;";
        return $executions;
    }

    /**
     * Test get products.
     *
     * @param  string $conditions
     * @param  string $storyType
     * @access public
     * @return string
     */
    public function getProductsTest($conditions, $storyType = 'story')
    {
        $objects = $this->objectModel->getProducts($conditions, $storyType);

        if(dao::isError()) return dao::getError();

        $planCount = 0;
        foreach($objects as $object)
        {
            if(isset($object->plans)) $planCount += count($object->plans);
        }
        return 'product:' . count($objects) . ';plan:' . $planCount;
    }

    /**
     * Test get bugs.
     *
     * @param  string $begin
     * @param  string $end
     * @param  int    $product
     * @param  int    $execution
     * @access public
     * @return array
     */
    public function getBugsTest($begin, $end, $product, $execution)
    {
        $begin = date('Y-m-d', strtotime(date('Y-m-d') . $begin));
        $end   = date('Y-m-d', strtotime(date('Y-m-d') . $end));
        $objects = $this->objectModel->getBugs($begin, $end, $product, $execution);

        if(dao::isError()) return dao::getError();

        $count = array();
        foreach($objects as $user => $types)
        {
            $count[$user] = '';
            foreach($types as $type => $typeCount) $count[$user] .= "$type:$typeCount;";
        }
        return $count;
    }

    /**
     * Test get workload.
     *
     * @param  int    $dept
     * @param  string $assign
     * @access public
     * @return string
     */
    public function getWorkloadTest($dept = 0, $assign = 'assign')
    {
        $objects = $this->objectModel->getWorkload($dept, $assign);

        if(dao::isError()) return dao::getError();

        $workload = '';
        foreach($objects as $user => $work)
        {
            if(strlen($workload) > 40) break;

            $workload .= "$user:";
            foreach($work['total'] as $key => $value) $workload .= "$key:$value,";
            $workload = trim($workload, ',');
            $workload .= ';';
        }
        return $workload;
    }

    /**
     * Test get bug assign.
     *
     * @access public
     * @return void
     */
    public function getBugAssignTest()
    {
        $objects = $this->objectModel->getBugAssign();

        if(dao::isError()) return dao::getError();

        $count = array();
        foreach($objects as $user => $object) $count[$user] = $object['total']['count'];
        return $count;
    }

    /**
     * Test get user bugs.
     *
     * @access public
     * @return string
     */
    public function getUserBugsTest()
    {
        $objects = $this->objectModel->getUserBugs();

        if(dao::isError()) return dao::getError();

        $counts = '';
        foreach($objects as $user => $bugs) $counts .= "$user:" . count($bugs) . ';';
        return $counts;
    }

    /**
     * Test get user tasks.
     *
     * @access public
     * @return string
     */
    public function getUserTasksTest()
    {
        $objects = $this->objectModel->getUserTasks();

        if(dao::isError()) return dao::getError();

        $counts = '';
        foreach($objects as $user => $tasks) $counts .= "$user:" . count($tasks) . ';';
        return $counts;
    }

    /**
     * Test get user todos.
     *
     * @access public
     * @return string
     */
    public function getUserTodosTest($userType)
    {
        $objects = $this->objectModel->getUserTodos();

        if(dao::isError()) return dao::getError();

        $counts = '';
        foreach($objects as $user => $todos)
        {
            if(strpos($user, $userType) !== false and str_replace($userType, '', $user) < 11) $counts .= "$user:" . count($todos) . ';';
        }
        return $counts;
    }

    /**
     * Test get user test tasks.
     *
     * @access public
     * @return string
     */
    public function getUserTestTasksTest()
    {
        $objects = $this->objectModel->getUserTestTasks();

        if(dao::isError()) return dao::getError();

        $counts = '';
        foreach($objects as $user => $testtasks) $counts .= "$user:" . count($testtasks) . ';';
        return $counts;
    }

    /**
     * Test get user login count in this year.
     *
     * @param  string $accounts
     * @access public
     * @return int
     */
    public function getUserYearLoginsTest($accounts)
    {
        $year = date('Y');

        $objects = $this->objectModel->getUserYearLogins($accounts, $year);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get user action count in this year.
     *
     * @param  array $accounts
     * @access public
     * @return void
     */
    public function getUserYearActionsTest($accounts)
    {
        $year = date('Y');

        $objects = $this->objectModel->getUserYearActions($accounts, $year);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get user contributions in this year.
     *
     * @param  array  $accounts
     * @access public
     * @return string
     */
    public function getUserYearContributionsTest($accounts)
    {
        $year = date('Y');

        $objects = $this->objectModel->getUserYearContributions($accounts, $year);

        if(dao::isError()) return dao::getError();

        $contributions = '';
        foreach($objects as $type => $contributionTypes)
        {
            $contributions .= "$type:";
            foreach($contributionTypes as $contributionType => $count) $contributions .= "$contributionType:$count,";
            $contributions = trim($contributions, ',') . ';';
        }
        return $contributions;
    }

    /**
     * Test get user todo stat in this year.
     *
     * @param  array  $accounts
     * @access public
     * @return string
     */
    public function getUserYearTodosTest($accounts)
    {
        $year = date('Y');
        $objects = $this->objectModel->getUserYearTodos($accounts, $year);

        if(dao::isError()) return dao::getError();

        $count = '';
        foreach($objects as $type => $value) $count .= "$type:$value;";
        return $count;
    }

    /**
     * Test get user effort stat in this error.
     *
     * @param  string $accounts
     * @access public
     * @return object
     */
    public function getUserYearEffortsTest($accounts)
    {
        $year = date('Y');

        $object = $this->objectModel->getUserYearEfforts($accounts, $year);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test get count of created story,plan and closed story by accounts every product in this year.
     *
     * @param mixed $accounts
     * @access public
     * @return void
     */
    public function getUserYearProductsTest($accounts)
    {
        $year = date('Y');

        $objects = $this->objectModel->getUserYearProducts($accounts, $year);

        if(dao::isError()) return dao::getError();

        return implode(',', array_keys($objects));
    }

    /**
     * Test get count of finished task, story and resolved bug by accounts every executions in this years.
     *
     * @param  string $accounts
     * @access public
     * @return string
     */
    public function getUserYearExecutionsTest($accounts)
    {
        $year = date('Y');

        $objects = $this->objectModel->getUserYearExecutions($accounts, $year);

        if(dao::isError()) return dao::getError();

        return implode(',', array_keys($objects));
    }

    /**
     * Test get status stat that is all time, include story, task and bug.
     *
     * @access public
     * @return array
     */
    public function getAllTimeStatusStatTest()
    {
        $objects = $this->objectModel->getAllTimeStatusStat();

        if(dao::isError()) return dao::getError();

        $types = array();
        foreach($objects as $type => $status)
        {
            $types[$type] = '';
            foreach($status as $statusType => $statusCount) $types[$type] .= "$statusType:$statusCount;";
        }
        return $types;
    }

    /**
     * Test get year object stat, include status and action stat.
     *
     * @param  string $accounts
     * @param  string $objectType
     * @access public
     * @return string
     */
    public function getYearObjectStatTest($accounts, $objectType)
    {
        $year = date('Y');

        $objects = $this->objectModel->getYearObjectStat($accounts, $year, $objectType);

        if(dao::isError()) return dao::getError();

        $stats = '';
        foreach($objects['statusStat'] as $stat => $count) $stats .= "$stat:$count;";
        return $stats;
    }

    /**
     * Test get year case stat, include result and action stat.
     *
     * @param  string $accounts
     * @access public
     * @return string
     */
    public function getYearCaseStatTest($accounts)
    {
        $year = date('Y');

        $objects = $this->objectModel->getYearCaseStat($accounts, $year);

        if(dao::isError()) return dao::getError();

        $result = '';
        foreach($objects['resultStat'] as $type => $value) $result .= "$type:$value;";
        return $result;
    }

    /**
     * Test get year months.
     *
     * @param  string $year
     * @access public
     * @return string
     */
    public function getYearMonthsTest($year)
    {
        $objects = $this->objectModel->getYearMonths($year);

        if(dao::isError()) return dao::getError();

        return implode(',', $objects);
    }

    /**
     * Test get status overview.
     *
     * @param  string $objectType
     * @param  array  $statusStat
     * @access public
     * @return string
     */
    public function getStatusOverviewTest($objectType, $statusStat)
    {
        $object = $this->objectModel->getStatusOverview($objectType, $statusStat);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test get project status overview.
     *
     * @param  array $accounts
     * @access public
     * @return string
     */
    public function getProjectStatusOverviewTest($accounts = array())
    {
        $objects = $this->objectModel->getProjectStatusOverview($accounts);

        if(dao::isError()) return dao::getError();

        $counts = '';
        foreach($objects as $type => $count) $counts .= "$type:$count;";
        return $counts;
    }

    /**
     * Test get output data for API.
     *
     * @param  string $accounts
     * @param  string $year
     * @access public
     * @return string
     */
    public function getOutput4APITest($accounts)
    {
        $year = date('Y');
        $objects = $this->objectModel->getOutput4API($accounts, $year);

        if(dao::isError()) return dao::getError();

        $output = '';
        foreach($objects as $objectType => $object) $output .= "$objectType:" . $object['total'] . ";";
        return $output;
    }

    /**
     * Test get project and execution name.
     *
     * @param  bool   $count
     * @access public
     * @return int|array
     */
    public function getProjectExecutionsTest($count = false)
    {
        $objects = $this->objectModel->getProjectExecutions();

        if(dao::isError()) return dao::getError();

        return $count ? count($objects) : $objects;
    }
}
